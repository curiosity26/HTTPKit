<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 5:52 PM
 */

namespace HTTPKit\Client;


use HTTPKit\Cookie\Handler\CookieHandlerInterface;
use HTTPKit\Security\SecurityInterface;
use HTTPKit\Transport\TransportInterface;

abstract class AbstractClient implements ClientInterface
{
  protected $transport;
  protected $security;
  protected $cookie_handler;

  /**
   * @param TransportInterface $transport
   * @return $this
   */
  public function setTransport(TransportInterface $transport) {
    $this->transport = $transport;

    return $this;
  }

  /**
   * @return TransportInterface
   */
  public function getTransport() {
    return $this->transport;
  }

  public function setSecurity(SecurityInterface $security = null) {
    $this->security = $security;

    return $this;
  }

  /**
   * @return SecurityInterface|null
   */
  public function getSecurity() {
    return $this->security;
  }

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
}