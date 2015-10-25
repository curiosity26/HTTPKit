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

class StreamTransport extends AbstractTransport implements StreamTransportInterface
{

  private $protocol = self::PROTOCOL_TCP;

  public function __construct($timeout = 120, $max_redirects = 0) {
    $this->setTimeout($timeout);
    $this->setMaxRedirects($max_redirects);
  }

  public function setProtocol($protocol = self::PROTOCOL_TCP) {
    $this->protocol = $protocol;

    return $this;
  }

  public function getProtocol() {
    return $this->protocol;
  }

  /**
   * @return resource
   */
  protected function buildContext(RequestInterface $request) {

    $context = array(
      'http' => array(
        'follow_location' => $this->getMaxRedirects() > 0,
        'max_redirects' => $this->getMaxRedirects(),
        'protocol_version' => '1.1'
      )
    );

    if ($request->getScheme() == $request::SCHEME_HTTPS) {
      $context['ssl'] = array(
        'CN_match' => $request->getHost(),
        'verify_peer' => TRUE,
        'verify_peer_name' => TRUE,
        'allow_self_signed' => TRUE
      );
    }

    return stream_context_create($context);
  }

  protected function buildTransportUrl(RequestInterface $request) {
    $protocol = $this->getProtocol();
    if (($request->getScheme() == $request::SCHEME_HTTPS
      || $request->getPort() == 443)
      && !in_array($protocol, array(
        self::PROTOCOL_SSL,
        self::PROTOCOL_SSLv2,
        self::PROTOCOL_SSLv3,
        self::PROTOCOL_TLS
      ))) {
      $protocol = self::PROTOCOL_SSL;
    }

    return "$protocol://{$request->getHost()}:{$request->getPort()}";
  }

  public function build(RequestInterface $request) {

    $build = $request->getRawHeader()."\r\n\r\n";

    $body = $request->getContent();

    if (is_array($body)) {
      $body = http_build_query($body);
    }

    $build .= $body;

    return $build;
  }

  protected function parse($content, Response $response) {
    $length = strpos($content, "\r\n\r\n");
    $header = substr($content, 0, $length);
    $body =  substr($content, ($length + strlen("\r\n\r\n")));
    $response->setContent($body);
    $response->setRawHeader($header);
  }

  /**
   * @return Response
   */
  public function send(RequestInterface $request) {
    $content = "";
    $errno = null;
    $errstr = null;
    try {
      $fp = stream_socket_client($this->buildTransportUrl($request), $errno, $errstr,
        $this->getTimeout(), STREAM_CLIENT_CONNECT, $this->buildContext($request));

      if ($fp !== false) {
        $body = $this->build($request);

        for ($written = 0; $written < strlen($body); $written += $fwrite) {
          $fwrite = fwrite($fp, substr($body, $written));
          if ($fwrite == false) {
            break;
          }
        }

        if (!strcmp($body, $written)) {
          throw new SocketInterruptException();
        }

        while (!feof($fp)) {
          $line = @fgets($fp);
          $content .= $line;
        }

        fclose($fp);
      } else {
        throw new SocketConnectionException();
      }
    }
    catch(\Exception $e) {
      throw new SocketConnectionException("", $errno, $e);
    }

    $response = new Response();
    $response->setRequest($request);

    $this->parse($content, $response);

    return $response;
  }
}