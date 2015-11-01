<?php

/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/24/15
 * Time: 6:40 PM
 */

class StreamTransportTest extends PHPUnit_Framework_TestCase
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
    $request = new \HTTPKit\Request\Request('https://localhost:4343/');
    $response = $transport->send($request);
    $this->assertTrue($response->isSuccess());
    $this->assertEquals('Success', $response->getContent());
  }

  public function testSendData() {
    $transport = new \HTTPKit\Transport\StreamTransport();
    $request = new \HTTPKit\Request\Request('http://localhost:8000/post.php', \HTTPKit\Request\RequestInterface::METHOD_POST);
    $data = sprintf("uniqid(): %s\r\n", uniqid());
    $request->setContent($data);
    $response = $transport->send($request);
    $this->assertTrue($response->isSuccess());
    $this->assertEquals('Success', $response->getContent());
  }
}
