<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 1:54 PM
 */

namespace HTTPKit\Client;


use HTTPKit\Request\RequestInterface;
use HTTPKit\Response\ResponseInterface;
use HTTPKit\Security\SecurityInterface;
use HTTPKit\Transport\CurlTransport;
use HTTPKit\Transport\TransportInterface;
use RESTKit\Transport\StreamTransport;

class Client extends AbstractClient
{

  public function __construct(TransportInterface $transport = null) {
    if (null === $transport) {
      $transport = function_exists('curl_init') ? new CurlTransport() : new StreamTransport();
    }

    $this->transport = $transport;
  }

  /**
   * @param RequestInterface $request
   * @return ResponseInterface
   */
  public function send(RequestInterface $request) {
    return $this
      ->getTransport()
      ->send($request);
  }
}