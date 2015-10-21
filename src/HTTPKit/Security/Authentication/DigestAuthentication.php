<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/19/15
 * Time: 6:28 PM
 */

namespace HTTPKit\Security\Authentication;


use HTTPKit\Request\Request;
use HTTPKit\Request\RequestInterface;
use HTTPKit\Response\ResponseInterface;
use HTTPKit\Transport\AbstractTransportAware;

class DigestAuthentication extends AbstractTransportAware implements DigestAuthenticationInterface
{
  const ALG_MD5 = 'MD5';
  const ALG_MD5_SESS = 'MD5-sess';
  const QOP_AUTH = 'auth';
  const QOP_AUTH_INT = 'auth-int';

  private $username;
  private $password;
  private $realm;
  private $qop;
  private $nonce;
  private $cnonce;
  private $uri;
  private $nc = 1;
  private $algorithm;
  private $opaque;
  private $response;

  public function __construct($username = null, $password = null, $algorithm = self::ALG_MD5) {
    $this->cnonce = substr(uniqid(),0,8);
    $this->username = $username;
    $this->password = $password;
    $this->algorithm = $algorithm;
  }

  private function a1() {
    $a1 = md5("{$this->username}:{$this->realm}:{$this->password}");

    if ($this->algorithm == self::ALG_MD5_SESS) {
      $a1 = md5("$a1:{$this->nonce}:{$this->cnonce}");
    }

    return $a1;
  }

  private function a2(RequestInterface $request) {
    $a2 = "{$request->getMethod()}:{$this->uri}";

    if ($this->qop == self::QOP_AUTH_INT) {
      $a2 .= ':'.md5($request->getContent());
    }

    return $a2;
  }

  public function getUsername() {
    return $this->username;
  }

  public function getRealm() {
    return $this->realm;
  }

  public function getUri() {
    return $this->uri;
  }

  public function getNonce() {
    return $this->nonce;
  }

  public function getCnonce() {
    return $this->cnonce;
  }

  public function getNc() {
    return $this->nc;
  }

  public function setAlgorithm($algorithm = self::ALG_MD5) {
    $this->algorithm = $algorithm;

    return $this;
  }

  public function getAlgorithm() {
    return $this->algorithm;
  }

  public function getOpaque() {
    return $this->opaque;
  }

  public function getResponse(RequestInterface $request) {
    if (null === $this->qop) {
      return '"'.$this->a1().$this->nonce.$this->a2($request).'"';
    }

    return '"'.$this->a1().
    "{$this->nonce}:{$this->nc}:{$this->cnonce}:{$this->qop}"
    .$this->a2($request).'"';
  }

  public function setUsername($username) {
    $this->username = $username;

    return $this;
  }

  public function setPassword($password) {
    $this->password = $password;

    return $this;
  }

  public function reset() {
    $this->realm =
    $this->qop =
    $this->nonce =
    $this->uri =
    $this->opaque =
    $this->response = null;
    $this->nc = 1;
    $this->algorithm = self::ALG_MD5;
  }

  /**
   * parseHeader
   *
   * Parse the header from a response, successful or not. Typically, most servers will respond with a 401 status
   * but we can pull from any response headers, and if the WWW-Authenticate header with the Digest method exists,
   * we can get the info we need from it.
   *
   * @param ResponseInterface $response
   * @return bool
   */
  public function parseHeader(ResponseInterface $response) {
    $header = $response->getRawHeader();
    $matches = array();

    if (preg_match('/WWW\-Authenticate:\sDigest\s(?<digest>.*?)\r\n/', $header, $match) !== false) {
      $digest = $matches['digest'];

      if (preg_match_all('/(?<name>[^=]+)=\"(?<value>[^\"]+)\",?/g', $digest, $match) !== false) {
        $this->reset();
        foreach ($matches as $match) {
          if (property_exists($this, $match['name'])) {
            // If there's a comma, there's more than one possible value, get the first
            if (strpos($match['value'], ',') !== false) {
              $match['value'] = explode(',', $match['value']);
              $this->{$match['name']} = array_shift($match['value']);
            }
            else {
              $this->{$match['name']} = $match['value'];
            }
          }
        }
        return true;
      }
    }
    return false;
  }

  /**
   * requestAuthentication
   *
   * Setup a quickie transport to send off a HEAD request to get a response to parse for the WWW-Auth header. While
   * HEAD is the preferred method, because it doesn't require the server to actually process and return info, it isn't
   * always permitted by the server. If the server doesn't implement the HEAD method, use GET instead.
   *
   * @param RequestInterface $request
   * @return ResponseInterface
   */
  protected function requestAuthentication(RequestInterface $request) {
    $transport = $this->getTransport() ?: $this->guessTransport();
    $head = new Request($request->getUrl(), RequestInterface::METHOD_HEAD);
    $response = $transport->send($head);

    // HEAD might not be permitted
    if ($response->getResponseCode() == 405) {
      $head->setMethod(RequestInterface::METHOD_GET);
      return $transport->send($head);
    }

    return $response;
  }

  /**
   * handleRequest
   *
   * Send off a probe to the server to get the WWW-Auth headers and parse them. If the WWW-Auth header exists and
   * has the Digest method, then we will modify the Client request's header and add the Authenticate properly formatted
   * for Digest. Otherwise, do nothing to the Client request.
   *
   * @param RequestInterface $request
   */
  public function handleRequest(RequestInterface $request) {
    // Make an initial head request
    // and process if the WWW-Auth for Digest exists
    if ($this->parseHeader($this->requestAuthentication($request))) {
      $header = $this->getMethod() . " username=\"{$this->username}\",
        realm=\"{$this->realm}\",
        nonce=\"{$this->nonce}\",
        uri=\"{$this->uri}\",
        opaque=\"{$this->opaque}\"";

      if (in_array($this->qop, array(self::QOP_AUTH, self::QOP_AUTH_INT))) {
        $header .= ",
          qop={$this->qop},
          cnonce=\"{$this->cnonce}\",
          nc={$this->nc},
          response=\"{$this->getResponse($request)}\"}";
      }

      $request->addHeader('Authentication', $header);
    }
  }

  public function getMethod() {
    return self::METHOD;
  }
}