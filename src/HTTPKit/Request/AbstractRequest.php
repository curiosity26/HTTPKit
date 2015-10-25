<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/22/15
 * Time: 9:54 PM
 */

namespace HTTPKit\Request;


abstract class AbstractRequest implements RequestInterface
{
  protected $url;
  protected $scheme = self::SCHEME_HTTP;
  protected $host;
  protected $port = 80;
  protected $method = self::METHOD_GET;
  protected $headers = array();
  protected $content;
  protected $cookies;
  protected $maxRedirects = 10;
  protected $timeout = 10;
  protected $authMethod;
  protected $authCredentials;

  public function setUrl($url)
  {
    $this->url = $url;
    $this->setScheme(parse_url($url, PHP_URL_SCHEME))
      ->setHost(parse_url($url, PHP_URL_HOST));

    $port = parse_url($url, PHP_URL_PORT);
    if (null === $port) {
      $port = $this->getScheme() == self::SCHEME_HTTPS ? 443 : 80;
    }

    $this->setPort($port);

    return $this;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setHost($host) {
    $this->host = $host;

    return $this;
  }

  public function getHost() {
    return $this->host;
  }

  public function setScheme($scheme = self::SCHEME_HTTP) {
    $this->scheme = $scheme;

    return $this;
  }

  public function getScheme() {
    return $this->scheme;
  }

  public function setMethod($method = self::METHOD_GET)
  {
    if (in_array(
      $method,
      array(
        self::METHOD_CONNECT,
        self::METHOD_DELETE,
        self::METHOD_GET,
        self::METHOD_HEAD,
        self::METHOD_POST,
        self::METHOD_PUT,
        self::METHOD_PATCH
      )
    )) {
      $this->method = $method;
    }
    else {
      $this->method = self::METHOD_GET;
    }

    return $this;
  }

  public function getMethod() {
    return $this->method;
  }

  public function setPort($port = 80)
  {
    $this->port = $port;

    return $this;
  }

  public function getPort()
  {
    return $this->port;
  }

  public function buildHeaders()
  {
    $headers = array();
    foreach ($this->headers as $name => $value) {
      $headers[] = "$name: $value";
    }

    return $headers;
  }

  public function addHeader($name, $value)
  {
    $this->headers[$name] = $value;

    return $this;
  }

  public function removeHeader($name)
  {
    unset($this->headers[$name]);

    return $this;
  }

  public function setHeaders(array $headers)
  {
    $this->headers = $headers;

    return $this;
  }

  public function getHeaders() {
    return $this->headers;
  }

  public function getRawHeader() {
    $method = $this->getMethod();

    $url = $this->getUrl();
    $host = $this->getHost();
    $path = parse_url($url, PHP_URL_PATH);
    $query = parse_url($url, PHP_URL_QUERY);

    $uri = $path.($query ? "?$query": null);

    return $this->raw_header = "$method $uri HTTP/1.1"."\r\n"."Host: $host"."\r\n".implode("\r\n", $this->buildHeaders());
  }

  public function setContent($data)
  {
    $this->content = $data;

    return $this;
  }

  public function getContent()
  {
    return $this->content;
  }
}