<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 6:03 PM
 */

namespace HTTPKit\Cookie\Handler;


use HTTPKit\Cookie\CookieInterface;
use HTTPKit\Response\ResponseInterface;
use HTTPKit\Cookie\Cookie;

class MemoryCookieHandler extends AbstractCookieHandler
{
  /**
   * @var Cookie[]
   */
  private $cookies = array();

  public function __construct(ResponseInterface $response = null) {
    if (null !== $response && $response->isSuccess()) {
      $this->parse($response->getRawHeader());
    }
  }

  private function toRaw() {
    $raw = "";

    foreach ($this->cookies as $cookie) {
      $raw .= $cookie.";";
    }

    return strlen($raw) >= 4 ? $raw : null;
  }

  public function setCookies(array $cookies) {
    $this->clearCookies();
    foreach ($cookies as $cookie) {
      if ($cookie instanceof CookieInterface) {
        $this->setCookie($cookie);
      }
    }

    return $this;
  }

  public function clearCookies() {
    $this->cookies = array();

    return $this;
  }
  /**
   * @return array
   */
  public function getCookies() {
    return $this->cookies;
  }

  public function setCookie(CookieInterface $cookie) {
    $this->cookies[$cookie->getName()] = $cookie;

    return $this;
  }

  public function getCookie($name) {
    return $this->cookies[$name];
  }

  public function __toString() {
    return $this->toRaw();
  }

}