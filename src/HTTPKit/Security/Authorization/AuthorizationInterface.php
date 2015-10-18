<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 2:11 PM
 */

namespace HTTPKit\Security\Authorization;


use HTTPKit\Security\SecurityInterface;

interface AuthorizationInterface extends SecurityInterface
{
  public function setToken($token);
  public function getToken();
  public function getHeaderName();
}