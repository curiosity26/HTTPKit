<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/20/15
 * Time: 10:01 PM
 */

namespace HTTPKit\Security\Authentication;


interface BasicAuthenticationInterface extends AuthenticationInterface
{
  const METHOD = 'Basic';

  public function setCredentials($creds);
  public function getCredentials();
}