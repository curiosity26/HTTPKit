<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 5:23 PM
 */

namespace HTTPKit\Security\Authorization;


class TokenAuthorization extends AbstractAuthorization
{

  const METHOD = "Token";

  public function __construct($token = null) {
    $this->token = $token;
  }

  public function getMethod() {
    return self::METHOD;
  }
}