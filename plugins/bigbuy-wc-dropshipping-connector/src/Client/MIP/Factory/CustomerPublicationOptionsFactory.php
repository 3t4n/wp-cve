<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Client\MIP\Model\PublicationSummary;

class CustomerPublicationOptionsFactory
{
    /** @var PublicationSummary */
    private static $instance;

    /**
     * @return PublicationSummary
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function create(array $customerPublicationOptions): PublicationSummary
    {
        $publicationSummary = new PublicationSummary();
        $publicationSummary->conversionFactor = $customerPublicationOptions['ConversionFactor'];
        $publicationSummary->notSelectedProductsToUpdateCount = $customerPublicationOptions['NotSelectedProductsToUpdateCount'];
        $publicationSummary->shippingRateIncludedCountryIsoCode = \array_key_exists('ShippingRateIncludedCountryIsoCode', $customerPublicationOptions)
        ? $customerPublicationOptions['ShippingRateIncludedCountryIsoCode']
        : null;

        return $publicationSummary;
    }
}
