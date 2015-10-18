<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/18/15
 * Time: 4:47 PM
 */

namespace HTTPKit\Cookie;


abstract class AbstractCookie implements CookieInterface
{
  protected $name;
  protected $value;
  protected $expires;
  protected $max_age;
  protected $domain;
  protected $path;
  protected $secure = false;
  protected $http_only = true;

  public function setName($name) {
    // Sanitize name
    $this->name = preg_replace("/[^a-zA-Z0-9!$%^&*+\'\.\^~\|`_]/g", '', $name);

    return $this;
  }

  public function getName() {
    return $this->name;
  }

  public function setValue($value) {
    // sanitize value
    $this->value = preg_replace("/[^a-zA-Z0-9!@$%^&*\(\)'+\-\.\/:><=\?\[\]\^_`\{\}\|~]/g", '', $value);

    return $this;
  }

  public function getValue() {
    return $this->value;
  }

  public function setExpires(\DateTime $expires) {
    $this->expires = $expires;

    return $this;
  }

  public function getExpires() {
    return $this->expires;
  }

  public function setDomain($domain) {
    $this->domain = $domain;

    return $this;
  }

  public function setMaxAge(Integer $max_age) {
    $this->max_age = max(1, $max_age); // Must be a positive, non-zero value

    return $this;
  }

  public function getMaxAge() {
    return $this->max_age;
  }

  public function getDomain() {
    return $this->domain;
  }

  public function setPath($path = '/') {
    $this->path = $path;

    return $this;
  }

  public function getPath() {
    return $this->path;
  }

  public function setSecure(Boolean $secure = true) {
    $this->secure = $secure;

    return $this;
  }

  public function getSecure() {
    return $this->secure;
  }

  public function setHttpOnly(Boolean $http_only = true) {
    $this->http_only = $http_only;

    return $this;
  }

  public function getHttpOnly() {
    return $this->http_only;
  }

  public function __toString() {
    return "{$this->getName()}={$this->getValue()}; ";
  }
}