<?php

/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/24/15
 * Time: 6:40 PM
 */

require_once "HttpTestCase.php";

class StreamTransportTest extends HttpTestCase
{
  public function testConnection()
  {
    $transport = new \HTTPKit\Transport\StreamTransport();
    $request = new \HTTPKit\Request\Request('http://localhost:8000/');
    $response = $transport->send($request);
    $this->assertTrue($response->isSuccess());
    $this->assertEquals('Success', $response->getContent());
  }

  public function testSecureConnection() {
    $transport = new \HTTPKit\Transport\StreamTransport();
    $request = new \HTTPKit\Request\Request('https://localhost/');
    $response = $transport->send($request);
    $this->assertTrue($response->isSuccess());
    $this->assertEquals('Success', $response->getContent());
  }
}
