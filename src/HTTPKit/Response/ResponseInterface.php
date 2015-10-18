<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 9/22/15
 * Time: 8:32 PM
 */

namespace HTTPKit\Response;


interface ResponseInterface
{
  public function setResponseCode($code);
  public function getResponseCode();
  static public function getResponseStatus($responseCode);
  public function getContent();
  public function setContent($content);
  public function getRawHeader();
  public function setRawHeader($header);
  public function getHeaders();
  public function getRawResponse();
  public function setRawResponse($response);
  public function getRequestHeader();
  public function setRequestHeader($header);
  public function isSuccess();
}