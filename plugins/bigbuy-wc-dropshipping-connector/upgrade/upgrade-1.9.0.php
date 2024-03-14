<?php

declare(strict_types=1);

use WcMipConnector\Manager\SystemManager;

defined('ABSPATH') || exit;

$systemManager = new SystemManager();
$systemManager->createWcMipConnectorTables();

return true;