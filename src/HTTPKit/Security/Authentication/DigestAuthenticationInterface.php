<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/19/15
 * Time: 11:12 PM
 */

namespace HTTPKit\Security\Authentication;


use HTTPKit\Response\ResponseInterface;

interface DigestAuthenticationInterface extends AuthenticationInterface
{
  const METHOD = 'Digest';

  public function parseHeader(ResponseInterface $response);
}