<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 6:03 PM
 */

namespace HTTPKit\Cookie;


class MemoryCookieHandler implements CookieHandlerInterface
{
  private $raw_cookie;
  private $cookies = array();

  public function __construct($cookie = null) {
    if (null !== $cookie) {
      if (is_array($cookie)) {
        $this->setCookies($cookie);
      }
      else {
        $this->setRawCookie($cookie);
      }
    }
  }

  public function setRawCookie($cookie) {
    $this->raw_cookie = $cookie;
    $this->parse();

    return $this;
  }

  /**
   * @return string|null
   */
  public function getRawCookie() {
    $this->toRaw();
    return $this->raw_cookie;
  }

  private function parse() {
    // TODO: Parse cookie string to array per RFC spec
  }

  private function toRaw() {
    // TODO: Assemble raw string from array per RFC spec
  }

  public function setCookies(array $cookies) {
    $this->cookies = $cookies;

    return $this;
  }

  /**
   * @return array
   */
  public function getCookies() {
    return $this->cookies;
  }

  public function setCookie($name, $value) {
    $this->cookies[$name] = $value;

    return $this;
  }

  public function getCookie($name) {
    return $this->cookies[$name];
  }

  public function __toString() {
    return $this->getRawCookie();
  }

}