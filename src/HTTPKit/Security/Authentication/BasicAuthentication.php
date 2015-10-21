<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 5:14 PM
 */

namespace HTTPKit\Security\Authentication;


use HTTPKit\Request\RequestInterface;

class BasicAuthentication implements BasicAuthenticationInterface
{
  const METHOD = 'Basic';
  private $username;
  private $password;

  public function __construct($username = null, $password = null) {
    $this->setUsername($username);
    $this->setPassword($password);
  }

  public function setUsername($username) {
    $this->username;

    return $this;
  }

  public function setPassword($password) {
    $this->password = $password;

    return $this;
  }

  public function setCredentials($creds) {
    $parts = explode(':', base64_decode($creds));
    $this->username = $parts[0];
    $this->password = $parts[1];

    return $this;
  }

  public function getCredentials() {
    return base64_encode("$this->username:$this->password");
  }

  public function getMethod() {
    return self::METHOD;
  }

  public function handleRequest(RequestInterface $request) {
    $request->addHeader('Authentication', "{$this->getMethod()} {$this->getCredentials()}");
  }
}