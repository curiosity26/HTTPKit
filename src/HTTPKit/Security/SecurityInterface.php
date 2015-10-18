<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 2:07 PM
 */

namespace HTTPKit\Security;



use HTTPKit\Request\RequestInterface;

interface SecurityInterface
{
  public function getMethod();
  public function handleRequest(RequestInterface $request);
}