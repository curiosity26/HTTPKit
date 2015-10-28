<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 10/27/15
 * Time: 9:16 PM
 */

$input = file_get_contents('php://input');

if (strlen($input) > 0) {
  http_response_code(201);
  print 'Created';
}
else {
  http_response_code(406);
  print 'Error';
}

