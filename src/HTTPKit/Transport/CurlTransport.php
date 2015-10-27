<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/9/15
 * Time: 5:33 AM
 */

namespace HTTPKit\Transport;


use HTTPKit\Request\RequestInterface;
use HTTPKit\Response\Response;
use HTTPKit\Response\ResponseInterface;
use HTTPKit\Security\Authentication\AuthenticationInterface;
use HTTPKit\Security\Authorization\AuthorizationInterface;

class CurlTransport extends AbstractTransport
{
  private $ch;

  public function __construct($timeout = 120, $max_redirects = 0) {
    $this->setTimeout($timeout);
    $this->setMaxRedirects($max_redirects);
  }

  protected function build(RequestInterface $request)
  {
    $content = $request->getContent();

    curl_setopt_array(
      $this->ch,
      array(
        CURLOPT_URL => $request->getUrl(),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_VERBOSE => true,
        CURLOPT_HEADER => true,
        CURLOPT_PORT => $request->getPort() !== NULL ? $request->getPort() : 80,
        CURLOPT_FAILONERROR => false,
        CURLOPT_TIMEOUT => $this->timeout,
        CURLOPT_MAXREDIRS => $this->max_redirects,
        CURLOPT_AUTOREFERER => true,
        CURLINFO_HEADER_OUT => true
      )
    );

    if ($request->getScheme() == $request::SCHEME_HTTPS) {
      curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
      if ($request->getPort() === null) {
        $request->setPort(443);
        curl_setopt($this->ch, CURLOPT_PORT, $request->getPort());
      }
    }

    if ($request->getMethod() == $request::METHOD_PUT && get_resource_type($content) == 'file') {
      curl_setopt($this->ch, CURLOPT_INFILE, $content);
    } elseif (is_string($content) || is_array($content)) {
      curl_setopt($this->ch, CURLOPT_POSTFIELDS, $content);
      if (is_string($content)) {
        $request->addHeader('Content-Length', strlen($content));
      }
    }

    $builtHeaders = $request->buildHeaders();

    switch ($request->getMethod()) {
      case RequestInterface::METHOD_POST:
        if ($request->getHeader('Content-Type') == 'application/json') {
          curl_setopt(
            $this->ch,
            CURLOPT_CUSTOMREQUEST,
            "POST"
          ); // Posting JSON Data needs to POST while sidestepping CURLOPT_POST
        }
        else {
          curl_setopt($this->ch, CURLOPT_POST, true);
        }
        break;
      case RequestInterface::METHOD_PUT:
        ;
        curl_setopt($this->ch, CURLOPT_PUT, true);
        break;
      case RequestInterface::METHOD_HEAD:
      case RequestInterface::METHOD_DELETE:
      case RequestInterface::METHOD_CONNECT:
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        break;
      default:
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);
    }

    curl_setopt($this->ch, CURLOPT_HTTPHEADER, $builtHeaders);
  }

  public function parse($rawResponse, ResponseInterface $response)
  {
    list($header, $body) = explode("\r\n\r\n", $rawResponse, 2);
    $response->setRawHeader($header);
    $response->setRawResponse($rawResponse);
    $response->setContent($body);
  }

  /**
   * @return ResponseInterface
   */
  public function send(RequestInterface $request) {
    if ($request->getUrl() === null) {
      throw new \RuntimeException("URL has not been provided");
    }

    $this->ch = curl_init();
    $this->build($request);
    $body = curl_exec($this->ch);
    curl_close($this->ch);

    $response = new Response();
    $response->setRequest($request);

    $this->parse($body, $response);

    return $response;
  }
}