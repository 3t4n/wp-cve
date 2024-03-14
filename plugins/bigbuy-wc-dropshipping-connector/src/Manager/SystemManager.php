<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Factory\SystemFactory;
use WcMipConnector\Repository\SystemRepository;

class SystemManager
{
    /** @var SystemRepository */
    private $repository;

    /** @var SystemFactory */
    private $factory;

    public function __construct()
    {
        $this->repository = new SystemRepository();
        $this->factory = new SystemFactory();
    }

    /**
     * @param string $name
     * @param string $topic
     * @param string $web
     * @param int|null $id
     * @throws \Exception
     */
    public function installWebHook(string $name, string $topic, string $web, int $id = null): void
    {
        if ($id) {
            $webHook = $this->factory->create($name, $topic, $web, $id);
        } else {
            $webHook = $this->factory->create($name, $topic, $web);
            $webHook->set_user_id(get_current_user_id());
        }

        $webHook->save();
    }

    public function uninstallMipConnector(): void
    {
        $this->repository->deleteWcMipConnectorTables();
        $this->repository->deleteWcMipConnectorOptions();

        $filter = [
            'name' => 'MIP_ORDER_ADD',
        ];
        $this->repository->deleteWebHooks($filter);

        $filter = [
            'name' => 'MIP_ORDER_UPDATE',
        ];
        $this->repository->deleteWebHooks($filter);
    }

    /**
     * @return array
     */
    public function getMipTablesFromDatabase(): array
    {
        return $this->repository->getMipTablesFromDatabase();
    }

    /**
     * @return bool
     */
    public function resetFailureCount(): bool
    {
        $data = [
            'failure_count' => 0,
        ];

        $filter = [
            'name' => 'MIP_ORDER_ADD',
            'name' => 'MIP_ORDER_UPDATE',
        ];

        return $this->repository->resetFailureCount($data, $filter);
    }

    /**
     * @param string $sql
     * @return bool
     */
    public function executeSql(string $sql): bool
    {
        return $this->repository->executeSql($sql);
    }

    /**
     * @return string
     */
    public function getWoocommerceDefaultCountryIsoCode(): string
    {
        return $this->repository->getWoocommerceDefaultCountryIsoCode();
    }

    public function createWcMipConnectorOptionsIfNotExists():void
    {
        if (!ConfigurationOptionManager::existsPluginDatabaseWcVersion()) {
            ConfigurationOptionManager::setPluginDatabaseVersion(ConfigurationOptionManager::getPluginFilesVersion());
        }

        if (!ConfigurationOptionManager::existsSecretKey()) {
            ConfigurationOptionManager::setSecretKey();
        }

        if (!ConfigurationOptionManager::existsAccessToken()) {
            ConfigurationOptionManager::setAccessToken();
        }

        if (!ConfigurationOptionManager::existsSendEmail()) {
            ConfigurationOptionManager::setSendEmail();
        }

        if (!ConfigurationOptionManager::existsActiveTag()) {
            ConfigurationOptionManager::setActiveTag();
        }

        if (!ConfigurationOptionManager::existsTagName()) {
            ConfigurationOptionManager::setTagName();
        }

        if (!ConfigurationOptionManager::existsBrandId()) {
            ConfigurationOptionManager::setBrandId();
        }

        if (!ConfigurationOptionManager::existsCarrierOption()) {
            ConfigurationOptionManager::setCarrierOption();
        }

        if (!ConfigurationOptionManager::existsProductOption()) {
            ConfigurationOptionManager::setProductOption();
        }

        if (!ConfigurationOptionManager::existsApiKey()) {
            ConfigurationOptionManager::setApiKey();
        }

        if (!ConfigurationOptionManager::existsLastStockUpdate()) {
            ConfigurationOptionManager::setLastStockUpdate();
        }

        if (!ConfigurationOptionManager::existsLastCarrierUpdate()) {
            ConfigurationOptionManager::setLastCarrierUpdate();
        }

        if (!ConfigurationOptionManager::existsPermalink()) {
            ConfigurationOptionManager::setDefaultPermalink();
        }

        if (!ConfigurationOptionManager::existsUserId()) {
            ConfigurationOptionManager::setUserId();
        }
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function findWebHookByName(string $name): ?array
    {
        return $this->repository->findWebHookByName($name);
    }


    public function createWcMipConnectorTables(): void
    {
        $mipTables = $this->getMipTablesFromDatabase();
        $this->repository->createWcMipConnectorTables($mipTables);
    }
}