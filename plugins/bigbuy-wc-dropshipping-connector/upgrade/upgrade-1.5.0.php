<?php

use WcMipConnector\Manager\SystemManager;
use WcMipConnector\Service\DirectoryService;
use WcMipConnector\Service\ShippingService;

defined('ABSPATH') || exit;

$systemManager = new SystemManager();
$shippingService = new ShippingService();
$directoryService = new DirectoryService();

$systemManager->createWcMipConnectorTables();
$shippingService->updateShippingServices();
$directoryService->removeDirectory($directoryService->getModuleDir().'/.idea');
$directoryService->removeDirectory($directoryService->getModuleDir().'/.git');

return true;