<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/9/15
 * Time: 9:00 AM
 */

namespace HTTPKit\Response;


class Response extends AbstractResponse
{
  public function __construct($response = null, $header = null, $code = 200)
  {
    // This can still be overridden. If the raw header contains the HTTP/1.1 ### Status line, then that value will win
    $this->setResponseCode($code);
    $this->setRawHeader($header);
    $this->setRawResponse($response);
  }
}