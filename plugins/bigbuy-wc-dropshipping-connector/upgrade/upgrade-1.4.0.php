<?php

use WcMipConnector\Manager\SystemManager;
use WcMipConnector\Service\ShippingService;

defined('ABSPATH') || exit;

$systemManager = new SystemManager();
$shippingService = new ShippingService();

$systemManager->createWcMipConnectorTables();
$shippingService->updateShippingServices();

return true;