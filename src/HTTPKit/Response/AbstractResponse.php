<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 5/6/15
 * Time: 9:51 AM
 */

namespace HTTPKit\Response;


use HTTPKit\Request\RequestInterface;

abstract class AbstractResponse implements ResponseInterface {
  protected $raw_response;
  protected $raw_header;
  protected $request;
  protected $content;
  protected $response_code;
  protected $headers = array();

  static $HTTP_RESPONSE_CODES = array(
    100 => "Continue",
    101 => "Switching Protocols",
    200 => "OK",
    201 => "Created",
    202 => "Accepted",
    203 => "Non-Authoritative Information",
    204 => "No Content",
    205 => "Reset Content",
    206 => "Partial Content",
    300 => "Multiple Choices",
    301 => "Moved Permanently",
    302 => "Found",
    303 => "See Other",
    304 => "Not Modified",
    305 => "Use Proxy",
    306 => "(Unused)",
    307 => "Temporary Redirect",
    400 => "Bad Request",
    401 => "Unauthorized",
    402 => "Payment Required",
    403 => "Forbidden",
    404 => "Not Found",
    405 => "Method Not Allowed",
    406 => "Not Acceptable",
    407 => "Proxy Authentication Required",
    408 => "Request Timeout",
    409 => "Conflict",
    410 => "Gone",
    411 => "Length Required",
    412 => "Precondition Failed",
    413 => "Request Entity Too Large",
    414 => "Request-URI Too Long",
    415 => "Unsupported Media Type",
    416 => "Requested Range Not Satisfiable",
    417 => "Expectation Failed",
    500 => "Internal Server Error",
    501 => "Not Implemented",
    502 => "Bad Gateway",
    503 => "Service Unavailable",
    504 => "Gateway Timeout",
    505 => "HTTP Version Not Supported"
  );

  /**
   * @return mixed
   * @deprecated
   */
  public function getResponseBody() {
    return $this->getContent();
  }

  public function setContent($content) {
    $this->content = $content;

    return $this;
  }

  public function getContent()
  {
    return $this->content;
  }

  public function getRawHeader()
  {
    return $this->raw_header;
  }

  public function setRawHeader($header) {
    $this->raw_header = $header;
    $header_lines = explode('\r\n', $header);
    $matches = array();

    if (preg_match('/^(\n|\s+)?HTTP\/\d.\d\s(?<code>\d{3})\s(?<message>.*)/', $header, $matches) !== false) {
      $this->setResponseCode($matches['code']);
      array_shift($header_lines);
    }

    $this->headers = array();

    if (!empty($header_lines)) {
      foreach ($header_lines as $line) {
        if (strpos($line, ':') != false) {
          $h = explode(':', $line);
          $this->headers[trim($h[0])] = trim($h[1]);
        }
      }
    }

    return $this;
  }

  public function getHeaders()
  {
    return $this->headers;
  }

  public function getRawResponse()
  {
    return $this->raw_response;
  }

  public function setRawResponse($response) {
    $this->raw_response = $response;

    return $this;
  }

  /**
   * @param RequestInterface $request
   */
  public function setRequest(RequestInterface $request) {
    $this->request = $request;
  }

  /**
   * @return RequestInterface|null
   */
  public function getRequest() {
    return $this->request;
  }

  public function setResponseCode($code) {
    $this->response_code = $code;

    return $this;
  }

  public function getResponseCode() {
    return $this->response_code;
  }

  static public function getResponseStatus($responseCode)
  {
    return isset(self::$HTTP_RESPONSE_CODES[$responseCode]) ? self::$HTTP_RESPONSE_CODES[$responseCode] : false;
  }

  /**
   * @return bool
   */
  public function isSuccess()
  {
    return in_array(
      $this->getResponseCode(),
      array(200, 201, 202, 206, 302, 304)
    ); // 201 is reserved for successful posts
  }
}