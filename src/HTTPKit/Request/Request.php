<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/23/15
 * Time: 7:33 PM
 */

namespace HTTPKit\Request;


class Request extends AbstractRequest
{
  public function __construct($url, $method = self::METHOD_GET, $data = null, $headers = array()) {
    $this
      ->setUrl($url)
      ->setMethod($method);

    if (isset($data)) {
      $this->setContent($data);
    }
    $this->setHeaders($headers);
  }
}