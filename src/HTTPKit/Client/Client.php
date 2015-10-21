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
use HTTPKit\Transport\CurlTransport;
use HTTPKit\Transport\TransportInterface;
use HTTPKit\Transport\StreamTransport;

class Client extends AbstractClient
{

  public function __construct(TransportInterface $transport = null) {
    $this->transport = $transport ?: $this->guessTransport();
  }

  /**
   * @param RequestInterface $request
   * @return ResponseInterface
   */
  public function send(RequestInterface $request) {
    $security = $this->getSecurity();
    $cookie = $this->getCookieHandler();

    if (null !== $security) {
      $security->handleRequest($request);
    }

    if (null !== $cookie) {
      $cookie->handleRequest($request);
    }

    $response = $this
      ->getTransport()
      ->send($request);

    if (null !== $cookie) {
      $cookie->parse($response);
    }

    return $response;
  }
}