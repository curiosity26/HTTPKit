<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/9/15
 * Time: 5:26 AM
 */

namespace HTTPKit\Transport;

use HTTPKit\Request\RequestInterface;
use HTTPKit\Response\Response;
use HTTPKit\Transport\Exception\SocketConnectionException;
use HTTPKit\Transport\Exception\SocketInterruptException;

class StreamTransport extends AbstractTransport
{

  public function __construct($timeout = 120, $max_redirects = 0) {
    $this->setTimeout($timeout);
    $this->setMaxRedirects($max_redirects);
  }
  /**
   * @return resource
   */
  protected function build(RequestInterface $request) {
    $url = $request->getUrl();
    $matches = array();
    $method = $request->getMethod();
    $security = $this->getSecurity();

    if (null !== $security) {
      $request->handleRequest($request);
    }

    $context = array(
      'http' => array(
        'method' => $request->getMethod() === $request::METHOD_JSON ? 'POST' : $method,
        'follow_location' => $this->getMaxRedirects() > 0,
        'max_redirects' => $this->getMaxRedirects(),
        'protocol_version' => '1.1',
        'header' => $request->buildHeaders()
      )
    );

    $body = $request->getContent();

    if (is_array($body)) {
      $body = http_build_query($body);
    }

    $context['http']['content'] = $body;

    if (preg_match('/^https:\/\/(?<host>[^:\/]+)/', $url, $matches) !== FALSE) {
      $context['ssl'] = array(
        'CN_match' => $matches['host'],
        'verify_peer' => TRUE,
        'verify_peer_name' => TRUE,
        'allow_self_signed' => TRUE
      );
    }

    return stream_context_create($context);
  }

  protected function parse($content, Response $response) {
    $length = strpos($content, "\n\n");
    $header = substr($content, 0, $length);
    $body =  substr($content, ($length + strlen("\n\n")));

    $response->setContent($body);
    $response->setRawHeader($header);
  }

  /**
   * @return Response
   */
  public function send(RequestInterface $request) {

    $context = $this->build($request);

    $content = "";
    if ($fp = @fopen($request->getUrl(), 'r+b', false, $context)) {
      $body = $request->getContent();

      if ($body) {
        for ($written = 0; $written < strlen($body); $written += $fwrite) {
          $fwrite = fwrite($fp, substr($body, $written));
          if ($fwrite == false) {
            break;
          }
        }

        if (!strcmp($body, $written)) {
          throw new SocketInterruptException();
        }
      }

      while(!feof($fp)) {
        $line = @fgets($fp);
        $content .= $line;
      }

      fclose($fp);
    }
    else {
      throw new SocketConnectionException();
    }

    $response = new Response();
    $response->setRequestHeader($request->getRawHeader());
    $this->parse($content, $response);

    return $response;
  }
}