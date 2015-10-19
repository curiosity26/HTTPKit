<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/18/15
 * Time: 6:33 PM
 */

namespace HTTPKit\Cookie\Handler;


use HTTPKit\Cookie\Cookie;
use HTTPKit\Request\RequestInterface;
use HTTPKit\Response\ResponseInterface;

abstract class AbstractCookieHandler implements CookieHandlerInterface
{
  public function parse(ResponseInterface $response) {
    if (!$response->isSuccess()) {
      return;
    }
    $this->clearCookies();

    $header = $response->getRawHeader();
    $start = 0;
    while (($start = stripos($header, "Set-Cookie:", $start)) !== false) {
      if (($set_cookie = substr($header, $start, stripos($header, "\r\n", $start))) !== FALSE) {
        $start += strlen($set_cookie) + 2; // +2 for the \r\n
        $vars = explode(';', $set_cookie);
        $name_value = explode('=', array_shift($vars));

        if (count($name_value) < 2) {
          continue;
        }

        $cookie = new Cookie($name_value[0], $name_value[1]);

        foreach ($vars as $var) {
          if (stripos($var, '=') === false) {
            continue;
          }
          $parts = explode('=', $var);
          if (count($parts) < 2) {
            continue;
          }
          $var_name = array_shift($parts);
          $var_value = implode('=', $parts);

          $this->setParsedCookieVar($cookie, $var_name, $var_value);
        }

        if (!$cookie->isExpired()) {
          $this->setCookie($cookie);
        }
      }
    }
  }

  protected function setParsedCookieVar(Cookie $cookie, $name, $value) {
    switch (strtolower($name)) {
      case 'max-age':
        if ((int)$value > 0) {
          $cookie->setMaxAge($value);
        }
        break;
      case 'expires':
        $cookie->setExpires(new \DateTime($value));
        break;
      case 'domain':
        $cookie->setDomain($value);
        break;
      case 'path':
        $cookie->setPath($value);
        break;
      case 'secure':
        $cookie->setSecure();
        break;
      case 'httponly':
        $cookie->setHttpOnly();
        break;
    }
  }

  public function handleRequest(RequestInterface $request) {
    $request->addHeader('Cookie', $this->__toString());
  }
}