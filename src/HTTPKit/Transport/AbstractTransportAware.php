<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/20/15
 * Time: 8:51 PM
 */

namespace HTTPKit\Transport;


abstract class AbstractTransportAware implements TransportAwareInterface
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
   * @return TransportInterface|null
   */
  public function getTransport() {
    return $this->transport;
  }

  /**
   * @param int $timeout
   * @param int $max_redirects
   * @return TransportInterface
   */
  protected function guessTransport($timeout = 120, $max_redirects = 0) {
    return function_exists('curl_init')
      ? new CurlTransport($timeout, $max_redirects)
      : new StreamTransport($timeout, $max_redirects);
  }
}