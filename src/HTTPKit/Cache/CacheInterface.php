<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/17/15
 * Time: 6:11 PM
 */

namespace HTTPKit\Cache;


use HTTPKit\Request\RequestInterface;

interface CacheInterface
{
  public function setContentCache($cache);
  public function getContentCache();
  public function setExpires(\DateTime $expires);
  public function getExpires();
  public function setETag($etag);
  public function getETag();
  public function setLastModified(\DateTime $modified);
  public function getLastModified();
  public function handleRequest(RequestInterface $request);
}