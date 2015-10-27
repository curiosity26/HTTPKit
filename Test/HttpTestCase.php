<?php

/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/25/15
 * Time: 9:44 AM
 * Update: 10/26/15 11:57 pm
 */
class HttpTestCase extends PHPUnit_Framework_TestCase
{
  static $server_pid;
  static $tunnel_pid;

  static public function startServer($ip_address = '127.0.0.1', $port = 8000, $web_root = "public_html") {
    if (self::$server_pid === null) {
      $cmd = "nohup php -S $ip_address:$port -t $web_root >/dev/null 2>&1 & echo $!";
      print "Server running at $ip_address:$port on PID = ";
      self::$server_pid = system($cmd);
      sleep(1); // Wait a second for the socket to open
    }
  }

  static public function startSecureServer($web_root = "public_html/") {
    if (self::$tunnel_pid === null) {
      self::$tunnel_pid = system("sudo -u root sh server.sh");
      self::startServer('127.0.0.1', 8000, $web_root);
    }
  }

  static public function stopServer() {
    if (null !== ($pid = self::$server_pid)) {
      system("kill $pid");
      print "Server running on PID $pid has stopped.".PHP_EOL;
      self::$server_pid = null;
    }
  }

  static public function stopSecureServer() {
    self::stopServer();
    if (null !== ($pid = self::$tunnel_pid)) {
      system("sudo -u root sh server.sh");
      print "Secure tunnel is closed".PHP_EOL;
      self::$tunnel_pid = null;
    }
  }
}
