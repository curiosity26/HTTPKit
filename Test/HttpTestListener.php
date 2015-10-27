<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/24/15
 * Time: 5:59 PM
 */;

require_once "HttpTestCase.php";

class HttpTestListener extends PHPUnit_Framework_BaseTestListener
{

  public function startTestSuite(\PHPUnit_Framework_TestSuite $suite) {
    HttpTestCase::startSecureServer();
  }

  public function endTestSuite(\PHPUnit_Framework_TestSuite $suite) {
    HttpTestCase::stopSecureServer();
  }
}