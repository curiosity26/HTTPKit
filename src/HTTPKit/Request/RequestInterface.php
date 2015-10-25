<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/22/15
 * Time: 8:27 PM
 */

namespace HTTPKit\Request;


interface RequestInterface
{
  /* Method Constants */
  const METHOD_GET      = 'GET';
  const METHOD_POST     = 'POST';
  const METHOD_PUT      = 'PUT';
  const METHOD_PATCH    = 'PATCH';
  const METHOD_DELETE   = 'DELETE';
  const METHOD_HEAD     = 'HEAD';
  const METHOD_CONNECT  = 'CONNECT';

  const SCHEME_HTTP = 'http';
  const SCHEME_HTTPS = 'https';

  public function setUrl($url);
  public function getUrl();
  public function setHost($host);
  public function getHost();
  public function setScheme($scheme = self::SCHEME_HTTP);
  public function getScheme();
  public function setMethod($method = self::METHOD_GET);
  public function getMethod();
  public function setPort($port = 80);
  public function getPort();
  public function setHeaders(array $headers);
  public function getHeaders();
  public function addHeader($name, $value);
  public function removeHeader($name);
  public function buildHeaders();
  public function getRawHeader();
  public function setContent($data);
  public function getContent();
}