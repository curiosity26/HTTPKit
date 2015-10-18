<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/18/15
 * Time: 5:54 PM
 */

namespace HTTPKit\Cookie;


class Cookie extends AbstractCookie
{
  private $created;

  public function __construct(
    $name = null,
    $value = null,
    $max_age = null,
    \DateTime $expires = null,
    $domain = null,
    $path = '/',
    $http_only = true,
    $secure = false
  ) {
    $this->setName($name);
    $this->setValue($value);
    $this->setPath($path);
    $this->setDomain($domain);
    $this->setHttpOnly($http_only);
    $this->setSecure($secure);
    $this->created = new \DateTime();

    // RFC6265 Section 4.1.2.2 : Prefer Max-Age over Expires if present
    if (null !== $max_age) {
      $this->setMaxAge($max_age);
    }
    else {
      if (null === $expires || $expires < $this->created) {
        // Defaultly expire a cookie after 30 days, this is for internal handling
        $expires = new \DateTime("+30 Days");
      }
      $this->setExpires($expires);
    }
  }

  public function getCreated() {
    return $this->created;
  }
}