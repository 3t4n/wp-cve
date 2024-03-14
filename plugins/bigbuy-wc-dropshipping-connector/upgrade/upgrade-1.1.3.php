<?php

defined('ABSPATH') || exit;

use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\FileLogManager;
use WcMipConnector\Manager\SystemManager;
use WcMipConnector\Repository\SystemRepository;
use WcMipConnector\Service\DirectoryService;

$directoryService = new DirectoryService();

try {
    $directoryService->createDirectory($directoryService->getImportFilesDir());
    $directoryService->createDirectory($directoryService->getLogDir());
} catch (\Exception $e) {
    return;
}

$fileLogManager = new FileLogManager();
$fileLogs = $fileLogManager->getAllFileNames();

if (!$fileLogs) {
    return;
}

foreach ($fileLogs as $fileLog) {
    $fileLocation = __DIR__.'/../../../uploads/mip-conector/importFiles/'.$fileLog['name'].'.json';
    $newFileLocation = $directoryService->getImportFilesDir().'/'.$fileLog['name'].'.json';

    if (file_exists($fileLocation)) {
        rename($fileLocation, $newFileLocation);
    }
}

if (empty(ConfigurationOptionManager::getApiKey())) {
    ConfigurationOptionManager::setApiKey();
}

$systemManager = new SystemManager();
$systemManager->createWcMipConnectorTables();

return true;