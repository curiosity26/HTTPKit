<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 5:56 PM
 */

namespace HTTPKit\Security\Authorization;


use HTTPKit\Request\RequestInterface;

class XAuthTokenAuthorization extends AbstractAuthorization
{
  const METHOD = 'X-Auth-Token';

  public function __construct($token = null) {
    $this->token = $token;
  }

  public function getMethod() {
    return self::METHOD;
  }

  public function getHeaderName() {
    return self::METHOD;
  }

  public function handleRequest(RequestInterface $request) {
    $request->addHeader($this->getMethod(), $this->getToken());
  }
}