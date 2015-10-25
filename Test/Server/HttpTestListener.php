<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/24/15
 * Time: 5:59 PM
 */;


class HttpTestListener extends PHPUnit_Framework_BaseTestListener
{
  private $ip_address;
  private $port;
  private $web_root;
  private $server_pid;

  public function __construct($ip_address = '127.0.0.1', $port = '8000', $web_root = null) {
    $this->ip_address = $ip_address;
    $this->port = $port;
    $this->web_root = $web_root;
  }

  public function startTestSuite(\PHPUnit_Framework_TestSuite $suite) {
    $web_root = $this->web_root;

    if ($web_root === null) {
      $web_root = __DIR__.'/../public_html/';
    }

    $cmd = "nohup php -S {$this->ip_address}:{$this->port} -t $web_root >/dev/null 2>&1 & echo $!";
    print "Server running on PID = ";
    $this->server_pid = system($cmd);
    sleep(1); // Wait a second for the socket to fully open
  }

  public function endTestSuite(\PHPUnit_Framework_TestSuite $suite) {
    if (null !== $this->server_pid) {
      system("kill {$this->server_pid}");
      print "Server running on PID {$this->server_pid} has stopped.".PHP_EOL;
      $this->server_pid = null;
    }
  }
}