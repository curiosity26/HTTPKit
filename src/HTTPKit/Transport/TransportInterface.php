<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/9/15
 * Time: 5:15 AM
 */

namespace HTTPKit\Transport;



use HTTPKit\Cookie\Handler\CookieHandlerInterface;
use HTTPKit\Request\RequestInterface;
use HTTPKit\Response\ResponseInterface;
use HTTPKit\Security\SecurityInterface;

interface TransportInterface
{

  /**
   * @param $timeout
   * @return TransportInterface
   */
  public function setTimeout($timeout);
  public function getTimeout();

  /**
   * @param $redirects
   * @return TransportInterface
   */
  public function setMaxRedirects($redirects);
  public function getMaxRedirects();

  public function setSecurity(SecurityInterface $security);

  /**
   * @return SecurityInterface|null
   */
  public function getSecurity();

  public function setCookieHandler(CookieHandlerInterface $cookie_handler);

  /**
   * @return CookieHandlerInterface|null
   */
  public function getCookieHandler();

  /**
   * @param RequestInterface $request
   * @return ResponseInterface
   */
  public function send(RequestInterface $request);
}