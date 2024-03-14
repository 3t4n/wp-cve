<?php

namespace WcMipConnector\Service;

use WcMipConnector\Factory\PublicationOptionsFactory;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Client\MIP\Customer\Service\SellingChannelService as SellingChannelServiceMIP;

defined('ABSPATH') || exit;

class PublicationOptionsService
{
    /** @var SellingChannelServiceMIP */
    private $sellingChannelServiceMIP;

    public function __construct()
    {
        $accessToken = ConfigurationOptionManager::getAccessToken();
        $this->sellingChannelServiceMIP = SellingChannelServiceMIP::getInstance($accessToken);
    }

    /**
     * @return bool
     */
    public function isUpdatePublicationOptionRequired(): bool
    {
        $publicationOptionsStore = ConfigurationOptionManager::getPublicationOptions();

        if (!$publicationOptionsStore
            || !\array_key_exists('LastRequest', \json_decode($publicationOptionsStore, true))
        ) {
            return true;
        }

        $lastRequest = \json_decode($publicationOptionsStore, true);

        if (!$lastRequest['LastRequest']) {
            return true;
        }

        try {
            $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));
            $dateLastRequest = new \DateTime($lastRequest['LastRequest'], new \DateTimeZone('UTC'));

            return $dateNow->diff($dateLastRequest)->days >= 1;
        } catch (\Exception $exception) {
            return true;
        }
    }

    /**
     * @return array{conversionFactor: float, LastRequest: \DateTime, shippingRateIncludedCountryIsoCode: string}
     */
    public function getPublicationOptions(): array
    {
        $cacheId = CacheService::generateCacheKey(__METHOD__);

        if (CacheService::getInstance()->has($cacheId)) {
            return \json_decode(CacheService::getInstance()->get($cacheId), true);
        }

        if ($this->isUpdatePublicationOptionRequired()) {
            $customerPublicationOptions = $this->sellingChannelServiceMIP->getCustomerPublicationOptions();
            $publicationOptions = PublicationOptionsFactory::getInstance()->create($customerPublicationOptions);
            ConfigurationOptionManager::setPublicationOptions(\json_encode($publicationOptions));
            CacheService::getInstance()->set($cacheId, \json_encode($publicationOptions));

            return $publicationOptions;
        }

        return \json_decode(ConfigurationOptionManager::getPublicationOptions(), true);
    }
}