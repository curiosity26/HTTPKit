<?php

/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/24/15
 * Time: 6:40 PM
 */

class ClientTest extends PHPUnit_Framework_TestCase
{
  public function testStreamConnection()
  {
    $client = new \HTTPKit\Client\Client(new \HTTPKit\Transport\StreamTransport());
    $request = new \HTTPKit\Request\Request('http://localhost:8000/');
    $response = $client->send($request);
    $this->assertTrue($response->isSuccess());
    $this->assertEquals('Success', $response->getContent());
  }
}
