<?php

use WcMipConnector\Service\DirectoryService;

defined('ABSPATH') || exit;

$directoryService = new DirectoryService();
$directoryService->removeDirectory($directoryService->getModuleDir().'/vendor/monolog');
$directoryService->removeDirectory($directoryService->getModuleDir().'/trunk');
$directoryService->removeDirectory($directoryService->getModuleDir().'/bin');
$directoryService->removeDirectory($directoryService->getModuleDir().'/tests');
$directoryService->deleteFile('createRelease.sh', $directoryService->getModuleDir());

return true;