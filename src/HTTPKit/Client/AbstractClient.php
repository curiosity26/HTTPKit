<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 5:52 PM
 */

namespace HTTPKit\Client;


use HTTPKit\Transport\TransportInterface;

abstract class AbstractClient implements ClientInterface
{
  protected $transport;

  /**
   * @param TransportInterface $transport
   * @return $this
   */
  public function setTransport(TransportInterface $transport) {
    $this->transport = $transport;

    return $this;
  }

  /**
   * @return TransportInterface
   */
  public function getTransport() {
    return $this->transport;
  }
}