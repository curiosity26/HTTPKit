<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 5:29 PM
 */

namespace HTTPKit\Security\Authorization;



use HTTPKit\Request\RequestInterface;

abstract class AbstractAuthorization implements AuthorizationInterface
{
  protected $token;

  public function setToken($token) {
    $this->token = $token;

    return $this;
  }

  public function getToken() {
    return $this->token;
  }

  public function getHeaderName() {
    return 'Authorization';
  }

  public function handleRequest(RequestInterface $request) {
    $request->addHeader($this->getHeaderName(), "{$this->getMethod()} {$this->getToken()}");
  }

}