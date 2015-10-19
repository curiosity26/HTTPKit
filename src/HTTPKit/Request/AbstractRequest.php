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
  protected $port;
  protected $method;
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

    return $this;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setMethod($method)
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
        self::METHOD_PATCH,
        self::METHOD_JSON
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

  public function setPort($port)
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

    if ($method === self::METHOD_JSON) {
      $method = 'POST';
      $this->addHeader('Content-Type', 'application/json');
    }

    $host = parse_url($this->getUrl(), PHP_URL_HOST);
    $uri = preg_replace('/^.*?:?\/\/[^\/]+', '', $this->getUrl());

    $this->raw_header = "$method $uri HTTP/1.1".'\r\n'."Host: $host".'\r\n'.$this->buildHeaders();
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