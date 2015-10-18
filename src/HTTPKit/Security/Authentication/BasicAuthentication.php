<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 5:14 PM
 */

namespace HTTPKit\Security\Authentication;


use HTTPKit\Request\RequestInterface;

class BasicAuthentication implements AuthenticationInterface
{
  const METHOD = 'Basic';
  private $credentials;

  public function __construct($creds = null, $password = null) {
    if ($creds !== null) {
      $this->setCredentials($creds, $password);
    }
  }

  public function setCredentials($creds, $password = null) {
    if (null !== $password) {
      $this->credentials = base64_encode("$creds:$password");

      return $this;
    }

    $this->credentials = $creds;

    return $this;
  }

  public function getCredentials() {
    return $this->credentials;
  }

  public function getMethod() {
    return self::METHOD;
  }

  public function handleRequest(RequestInterface $request) {
    $request->addHeader('Authentication', "{$this->getMethod()} {$this->getCredentials()}");
  }
}