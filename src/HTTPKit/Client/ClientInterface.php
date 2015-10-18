<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 1:50 PM
 */

namespace HTTPKit\Client;


use HTTPKit\Request\RequestInterface;
use HTTPKit\Transport\TransportInterface;

interface ClientInterface
{
  public function setTransport(TransportInterface $transport);
  public function getTransport();
  public function send(RequestInterface $request);
}