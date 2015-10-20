<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 2:09 PM
 */

namespace HTTPKit\Security\Authentication;


use HTTPKit\Security\SecurityInterface;

interface AuthenticationInterface extends SecurityInterface
{
  public function setCredentials($creds);
}