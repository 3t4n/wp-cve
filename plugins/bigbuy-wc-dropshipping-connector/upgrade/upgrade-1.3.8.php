<?php

use WcMipConnector\Manager\SystemManager;

defined('ABSPATH') || exit;

$systemManager = new SystemManager();
$systemManager->createWcMipConnectorTables();

return true;