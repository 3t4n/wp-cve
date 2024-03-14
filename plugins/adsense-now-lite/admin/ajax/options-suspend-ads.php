<?php
require_once('../../EzGA.php');
$slug = EzGA::getSlug();

if (!empty($_REQUEST['action'])) {
  $action = $_REQUEST['action'];
}
else {
  $action = '';
}
switch ($action) {
  case 'status':
    http_response_code(200);
    exit();
    break;
  default:
    http_response_code(400);
    die("This feature is available only in the <a href='#' class='goPro' data-product='$slug'>Pro Version</a> of this plugin.");
}
