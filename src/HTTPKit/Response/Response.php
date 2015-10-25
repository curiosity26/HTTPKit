<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/9/15
 * Time: 9:00 AM
 */

namespace HTTPKit\Response;


use HTTPKit\Request\RequestInterface;

class Response extends AbstractResponse
{
  public function __construct($response = null, $header = null, $code = 200, RequestInterface $request = null)
  {
    // This can still be overridden. If the raw header contains the HTTP/1.1 ### Status line, then that value will win
    $this->setResponseCode($code);

    if (null !== $request) {
      $this->setRequest($request);
    }

    if (null !== $header) {
      $this->setRawHeader($header);
    }

    if (null !== $response) {
      $this->setRawResponse($response);
    }
  }
}