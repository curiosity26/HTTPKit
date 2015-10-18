<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/9/15
 * Time: 5:33 AM
 */

namespace HTTPKit\Transport;


use HTTPKit\Request\RequestInterface;
use HTTPKit\Response\Response;
use HTTPKit\Response\ResponseInterface;
use HTTPKit\Security\Authentication\AuthenticationInterface;
use HTTPKit\Security\Authorization\AuthorizationInterface;

class CurlTransport extends AbstractTransport
{
  private $ch;

  public function __construct($timeout = 120, $max_redirects = 0) {
    $this->setTimeout($timeout);
    $this->setMaxRedirects($max_redirects);
  }

  protected function build(RequestInterface $request)
  {
    $cookies = $this->getCookies();

    curl_setopt_array(
      $this->ch,
      array(
        CURLOPT_URL => $request->getUrl(),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_VERBOSE => true,
        CURLOPT_COOKIEJAR => $this->getCookies(),
        CURLOPT_PORT => $request->getPort() !== NULL ? $request->getPort() : 80,
        CURLOPT_FAILONERROR => false,
        CURLOPT_TIMEOUT => $this->timeout,
        CURLOPT_MAXREDIRS => $this->max_redirects,
        CURLOPT_AUTOREFERER => true,
        CURLINFO_HEADER_OUT => true
      )
    );

    if (preg_match('/^https:/', $request->getUrl()) !== false) {
      curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
      if ($request->getPort() === null) {
        $this->port = 443;
        curl_setopt($this->ch, CURLOPT_PORT, $request->getPort());
      }
    }

    if (is_string($cookies)) {
      if (file_exists($cookies)) {
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $cookies);
      }
      else {
        curl_setopt($this->ch, CURLOPT_COOKIE, $cookies);
      }
    }

    $content = $request->getContent();

    if ($request->getMethod() == $request::METHOD_PUT && get_resource_type($content) == 'file') {
      curl_setopt($this->ch, CURLOPT_INFILE, $content);
    } elseif (is_string($content) || is_array($content)) {
      curl_setopt($this->ch, CURLOPT_POSTFIELDS, $content);
      if (is_string($content)) {
        $request->addHeader('Content-Length', strlen($content));
      }
    }

    // Handle Security
    $security = $this->getSecurity();

    if (null !== $security && $security instanceof AuthenticationInterface) {
      curl_setopt($this->ch, CURLOPT_HTTPAUTH, $security->getMethod());
      curl_setopt($this->ch, CURLOPT_USERPWD, $security->getCredentials());
    }
    elseif (null !== $security && $security instanceof AuthorizationInterface) {
      $request->addHeader($security->getHeaderName(), "{$security->getMethod()} {$security->getToken()}");
    }

    $builtHeaders = $request->buildHeaders();

    switch ($request->getMethod()) {
      case RequestInterface::METHOD_POST:
        curl_setopt($this->ch, CURLOPT_POST, true);
        break;
      case RequestInterface::METHOD_PUT:
        ;
        curl_setopt($this->ch, CURLOPT_PUT, true);
        break;
      case RequestInterface::METHOD_HEAD:
      case RequestInterface::METHOD_DELETE:
      case RequestInterface::METHOD_CONNECT:
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        break;
      case RequestInterface::METHOD_JSON:
        curl_setopt(
          $this->ch,
          CURLOPT_CUSTOMREQUEST,
          "POST"
        ); // Posting JSON Data needs to POST while sidestepping CURLOPT_POST
        $request->addHeader('Content-Type', 'application/json');
        break;
      default:
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        curl_setopt($this->ch, CURLOPT_HEADER, $builtHeaders);
    }

    curl_setopt($this->ch, CURLOPT_HTTPHEADER, $builtHeaders);
  }

  public function parse($rawResponse, $requestInfo, ResponseInterface $response)
  {
    $headerLength = 0;
    if ($requestInfo['http_code'] !== 201 && $requestInfo['header_size'] > 0) {
      $headerLength = $requestInfo['header_size'];
    }

    $rawHeader = substr($rawResponse, 0, $headerLength);
    $content = substr($rawResponse, $headerLength);

    $response->setResponseCode($requestInfo['http_code']);
    $response->setRawHeader($rawHeader);
    $response->setRawResponse($content);
  }

  /**
   * @return ResponseInterface
   */
  public function send(RequestInterface $request) {
    if ($request->getUrl() === null) {
      throw new \RuntimeException("URL has not been provided");
    }

    $this->ch = curl_init();
    $this->build($request);
    $body = curl_exec($this->ch);
    $info = curl_getinfo($this->ch);
    curl_close($this->ch);

    $response = new Response();

    $this->parse($body, $info, $response);

    return $response;
  }
}