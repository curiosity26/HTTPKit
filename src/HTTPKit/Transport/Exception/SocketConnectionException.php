<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/12/15
 * Time: 12:38 PM
 */

namespace HTTPKit\Transport\Exception;


class SocketConnectionException extends \RuntimeException
{
  public function __construct($message = "", $code = 0, \Exception $previous = null) {
    if (strlen($message) < 1) {
      $message = 'Failed to open a socket connection to the URL provided.';
    }

    parent::__construct($message, $code, $previous);
  }
}