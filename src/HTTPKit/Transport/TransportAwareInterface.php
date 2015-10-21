<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/20/15
 * Time: 8:48 PM
 */

namespace HTTPKit\Transport;


interface TransportAwareInterface
{
  public function setTransport(TransportInterface $transport);

  /**
   * @return TransportInterface|null
   */
  public function getTransport();
}