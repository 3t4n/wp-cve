<?php

namespace WcMipConnector\Factory;

use WcMipConnector\Client\MIP\Model\PublicationSummary;

defined('ABSPATH') || exit;

class PublicationOptionsFactory
{
    /** @var PublicationOptionsFactory */
    private static $instance;

    /**
     * @return PublicationOptionsFactory
     */
    public static function getInstance(): PublicationOptionsFactory
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function create(PublicationSummary $customerPublicationOptions): array
    {
        $publicationOptions = [];
        $publicationOptions['conversionFactor'] = $customerPublicationOptions->conversionFactor;
        $publicationOptions['LastRequest'] = date('Y-m-d H:i:s');
        $publicationOptions['shippingRateIncludedCountryIsoCode'] = $customerPublicationOptions->shippingRateIncludedCountryIsoCode;

        return $publicationOptions;
    }
}