<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/1/15
 * Time: 8:23 PM
 */

namespace HTTPKit\Cookie\Handler;


use HTTPKit\Response\ResponseInterface;

class FileCookieHandler extends MemoryCookieHandler
{
  private $path;

  public function __construct(ResponseInterface $response = null, $path = null) {
    parent::__construct($response);
    $this->setPath($path);
  }

  public function setPath($path = null) {
    if (null === $path) {
      $dir = sys_get_temp_dir().'/httpkit/cookies/';
      $path = $dir.uniqid();

      if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
      }
    }

    $this->path = $path;
    $fh = @fopen($path, 'w+');
    $file = '';

    while (!feof($fh)) {
      $file .= @fread($fh, 4096);
    }

    $this->parse($file);
    @fclose($fh);

    return $this;
  }

  public function getPath() {
    return $this->path;
  }
}