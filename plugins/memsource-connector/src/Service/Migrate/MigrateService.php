<?php

namespace Memsource\Service\Migrate;

use Memsource\Service\OptionsService;

class MigrateService
{
    /**
     * @var SchemaService
     */
    private $schemaService;

    /**
     * @var UpdateService
     */
    private $updateService;

    /**
     * @var OptionsService
     */
    private $optionsService;

    public function __construct(SchemaService $schemaService, UpdateService $updateService, OptionsService $optionsService)
    {
        $this->schemaService = $schemaService;
        $this->updateService = $updateService;
        $this->optionsService = $optionsService;
    }

    public function migrate()
    {
        $pluginVersion = $this->optionsService->getVersion();
        $dbVersion = $this->optionsService->getDbVersion();

        if ($dbVersion !== $pluginVersion) {
            $this->schemaService->createDatabaseSchema();
            $this->updateService->updateDatabase($pluginVersion);
            $this->optionsService->updateDbVersion($pluginVersion);
        }
    }
}
