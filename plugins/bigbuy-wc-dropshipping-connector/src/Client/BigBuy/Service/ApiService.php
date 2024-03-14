<?php

declare(strict_types=1);

namespace WcMipConnector\Client\BigBuy\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Client\Base\Exception\ClientErrorException;
use WcMipConnector\Client\BigBuy\Base\Service\AbstractService;

class ApiService extends AbstractService
{
    const MODULE_PLATFORMS_ENDPOINT = '/rest/module/platforms';

    /** @var ApiService */
    public static $instance;

    public function __construct($apiKey)
    {
        parent::__construct($apiKey);
    }

    /**
     * @param string $apiKey
     * @return ApiService
     */
    public static function getInstance(string $apiKey): ApiService
    {
        if (!self::$instance) {
            self::$instance = new self($apiKey);
        }

        return self::$instance;
    }

    public function getModulePlatforms(): array
    {
        try {
            $modulePlatformResponse = $this->get(self::MODULE_PLATFORMS_ENDPOINT);
        } catch (ClientErrorException $exception) {
            return [];
        }

        return $modulePlatformResponse ?? ['code' => 200];
    }
}