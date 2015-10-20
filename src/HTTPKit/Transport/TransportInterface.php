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

  /**
   * @param RequestInterface $request
   * @return ResponseInterface
   */
  public function send(RequestInterface $request);
}