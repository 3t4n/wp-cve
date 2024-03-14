<?php

require_once('../../EzGA.php');

if (!EzGA::isLoggedIn()) {
  http_response_code(400);
  die("Please login before messing with this!");
}

extract($_REQUEST, EXTR_PREFIX_ALL, 'posted');
if ($posted_validator) { // a server-side validator is specified
  $fun = "validate_$posted_validator";
  if (method_exists('EzGA', $fun)) {
    $valid = EzGA::$fun($posted_value);
  }
  else {
    http_response_code(400);
    die("Unknown validator ($posted_validator) specified");
  }
  if ($valid !== true) {
    http_response_code(400);
    die("$valid");
  }
}

http_response_code(200);
