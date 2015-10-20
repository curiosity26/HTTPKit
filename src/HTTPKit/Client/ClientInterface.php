<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 1:50 PM
 */

namespace HTTPKit\Client;


use HTTPKit\Cookie\Handler\CookieHandlerInterface;
use HTTPKit\Request\RequestInterface;
use HTTPKit\Security\SecurityInterface;
use HTTPKit\Transport\TransportInterface;

interface ClientInterface
{
  public function setTransport(TransportInterface $transport);
  public function getTransport();
  public function setSecurity(SecurityInterface $security);
  public function getSecurity();
  public function setCookieHandler(CookieHandlerInterface $cookie_handler);
  public function getCookieHandler();
  public function send(RequestInterface $request);
}