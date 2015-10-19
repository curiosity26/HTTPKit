<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/9/15
 * Time: 5:16 AM
 */

namespace HTTPKit\Transport;

use HTTPKit\Cookie\Handler\CookieHandlerInterface;
use HTTPKit\Security\SecurityInterface;

abstract class AbstractTransport implements TransportInterface
{
  protected $cookie_handler;
  protected $timeout = 120;
  protected $max_redirects = 0;
  protected $security;

  public function setCookieHandler(CookieHandlerInterface $cookie_handler = null) {
    $this->cookie_handler = $cookie_handler;

    return $this;
  }

  /**
   * @return CookieHandlerInterface|null
   */
  public function getCookieHandler() {
    return $this->cookie_handler;
  }

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

  /**
   * @param SecurityInterface $security
   * @return $this
   */
  public function setSecurity(SecurityInterface $security) {
    $this->security = $security;

    return $this;
  }

  /**
   * @return SecurityInterface|null
   */
  public function getSecurity() {
    return $this->security;
  }
}