<?php

require_once('../../EzGA.php');

if (!EzGA::isLoggedIn()) {
  http_response_code(400);
  die("Please login before messing with this!");
}

http_response_code(200);
require_once '../Migrator.php';
$migrator = new Migrator();
$migrator->migrate(true);

exit();