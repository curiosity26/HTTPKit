<?php

/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/25/15
 * Time: 1:17 PM
 */
require_once "HttpTestCase.php";

class CurlTransportTest extends HttpTestCase
{
  public function testConnection() {
    $transport = new \HTTPKit\Transport\CurlTransport();
    $request = new \HTTPKit\Request\Request('http://localhost:8000/');
    $response = $transport->send($request);
    $this->assertTrue($response->isSuccess());
    $this->assertEquals('Success', $response->getContent());
  }

  public function testSecureConnection() {
    $transport = new \HTTPKit\Transport\CurlTransport();
    $request = new \HTTPKit\Request\Request('https://localhost/');
    $response = $transport->send($request);
    $this->assertTrue($response->isSuccess());
    $this->assertEquals('Success', $response->getContent());
  }
}
