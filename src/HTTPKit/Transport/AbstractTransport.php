<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/9/15
 * Time: 5:16 AM
 */

namespace HTTPKit\Transport;

abstract class AbstractTransport implements TransportInterface
{
  protected $timeout = 120;
  protected $max_redirects = 0;

  public function setTimeout($timeout = 120) {
    $this->timeout = $timeout;

    return $this;
  }

  public function getTimeout() {
    return $this->timeout;
  }

  public function setMaxRedirects($redirects = 0) {
    $this->max_redirects = $redirects;

    return $this;
  }

  public function getMaxRedirects() {
    return $this->max_redirects;
  }
}