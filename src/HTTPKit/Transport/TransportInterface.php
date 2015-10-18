<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/9/15
 * Time: 5:15 AM
 */

namespace HTTPKit\Transport;



use HTTPKit\Request\RequestInterface;
use HTTPKit\Response\ResponseInterface;
use HTTPKit\Security\SecurityInterface;

interface TransportInterface
{
  public function setCookies($cookies);
  public function getCookies();

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
  public function getSecurity();

  /**
   * @param RequestInterface $request
   * @return ResponseInterface
   */
  public function send(RequestInterface $request);
}