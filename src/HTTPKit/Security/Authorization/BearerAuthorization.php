<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 5:34 PM
 */

namespace HTTPKit\Security\Authorization;


class BearerAuthorization extends AbstractAuthorization
{
  const METHOD = 'Bearer';

  public function __construct($token = null) {
    $this->token = $token;
  }

  public function getMethod() {
    return self::METHOD;
  }
}