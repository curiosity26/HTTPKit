<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/24/15
 * Time: 7:38 PM
 */

namespace HTTPKit\Transport;


interface StreamTransportInterface
{
  const PROTOCOL_TCP = 'tcp';
  const PROTOCOL_UDP = 'udp';
  const PROTOCOL_SSL = 'ssl';
  const PROTOCOL_SSLv2 = 'sslv2';
  const PROTOCOL_SSLv3 = 'sslv3';
  const PROTOCOL_TLS = 'tls';

  public function setProtocol($protocol);
  public function getProtocol();
}