<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/19/15
 * Time: 6:28 PM
 */

namespace HTTPKit\Security\Authentication;


use HTTPKit\Request\RequestInterface;
use HTTPKit\Response\ResponseInterface;

class DigestAuthentication implements DigestAuthenticationInterface
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
  private $nc;
  private $algorithm = self::ALG_MD5;
  private $opaque;
  private $response;

  public function setCredentials($username, $password = '') {
    $this->username = $username;
    $this->password = $password;

    return $this;
  }

  public function reset() {
    $this->realm =
    $this->qop =
    $this->nonce =
    $this->cnonce =
    $this->uri =
    $this->nc =
    $this->opaque =
    $this->response = null;

    $this->algorithm = self::ALG_MD5;
  }

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
      }
    }
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

  public function handleRequest(RequestInterface $request) {
    $header = $this->getMethod()." username=\"{$this->username}\",
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

  public function getMethod() {
    return self::METHOD;
  }
}