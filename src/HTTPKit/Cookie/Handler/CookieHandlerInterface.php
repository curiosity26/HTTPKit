<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 6:00 PM
 */

namespace HTTPKit\Cookie\Handler;


use HTTPKit\Cookie\CookieInterface;
use HTTPKit\Request\RequestInterface;
use HTTPKit\Response\ResponseInterface;

interface CookieHandlerInterface
{
  public function setCookie(CookieInterface $cookie);
  public function getCookie($name);
  public function parse(ResponseInterface $response);
  public function clearCookies();
  public function handleRequest(RequestInterface $request);
  public function __toString();
}