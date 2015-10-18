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
    $this->setUrl($url);
    $scheme = parse_url($url, PHP_URL_SCHEME);
    $port = parse_url($this->getUrl(), PHP_URL_PORT);
    if (null === $port) {
      $port = $scheme == 'https' ? 443 : 80;
    }
    $this->setPort($port);
    $this->setMethod($method);
    if (isset($data)) {
      $this->setContent($data);
    }
    $this->setHeaders($headers);
  }
}