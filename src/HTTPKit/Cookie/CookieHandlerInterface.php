<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 6:00 PM
 */

namespace HTTPKit\Cookie;


interface CookieHandlerInterface
{
  public function setRawCookie($cookie);
  public function getRawCookie();
}