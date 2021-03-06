<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/18/15
 * Time: 4:22 PM
 */

namespace HTTPKit\Cookie;


interface CookieInterface
{
  const DATE_FORMAT = \DateTime::RFC1123;

  public function setName($name);
  public function getName();
  public function setValue($value);
  public function getValue();
  public function setPath($path = '/');
  public function getPath();
  public function setExpires(\DateTime $expires);
  public function getExpires();
  public function setMaxAge($maxAge);
  public function getMaxAge();
  public function setDomain($domain);
  public function getDomain();
  public function setSecure($secure = true);
  public function getSecure();
  public function setHttpOnly($http_only = true);
  public function getHttpOnly();
  public function getCreated();
  public function isExpired();
  public function __toString();
}