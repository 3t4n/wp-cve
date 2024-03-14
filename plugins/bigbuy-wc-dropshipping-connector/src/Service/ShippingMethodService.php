<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Client\Base\Exception\ClientErrorException;
use WcMipConnector\Client\BigBuy\Model\ShippingOption;
use WcMipConnector\Factory\BigBuy\ShippingFactory;
use WcMipConnector\Manager\ShippingServiceManager;

class ShippingMethodService extends \WC_Shipping_Method
{
    public const SHIPPING_METHOD_ID = 'wc_mip_connector';

    private const TAX_DIVISION = 100.0;

    private const SHIPPING_RATE_ID_PREFIX = 'wc_mip_connector_';

    /** @var string|null */
    private static $shippingCosts = null;

    /** @var TaxesService */
    protected $taxesService;

    /** @var LoggerService  */
    private $loggerService;

    /** @var ShippingServiceManager */
    private $shippingServiceManager;

    /** @var ShippingFactory */
    private $shippingRequestFactory;

    /** @var ShippingService */
    private $shippingService;

    /** @var PublicationOptionsService */
    private $publicationOptionsService;

    /** @var ShippingServiceDelayTranslationService */
    private $shippingServiceDelayTranslationService;

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->taxesService = new TaxesService();
        $this->loggerService = new LoggerService();
        $this->shippingServiceManager = new ShippingServiceManager();
        $this->shippingRequestFactory = new ShippingFactory();
        $this->shippingService = new ShippingService();
        $this->publicationOptionsService = new PublicationOptionsService();
        $this->shippingServiceDelayTranslationService = new ShippingServiceDelayTranslationService();

        $this->method_title = __('BigBuy Shipping Method');
        $this->method_description = __('BigBuy Dropshipping Connector for WooCommerce Shipping Method');
        $this->enabled = 'yes';
        $this->title = '';
        $this->id = 'wc_mip_connector';

        $this->init();
    }

    function init(): void
    {
        $this->init_form_fields();
        $this->init_settings();

        add_action('woocommerce_update_options_shipping_'.$this->id, [$this, 'process_admin_options']);
    }

    /**
     * @param array $package
     */
    public function calculate_shipping($package = []): void
    {
        $shippingRates = $this->getShippingRates($package);

        foreach ($shippingRates as $shippingRate) {
            $this->add_rate($shippingRate);
        }
    }

    /**
     * @throws \Throwable
     * @throws ClientErrorException
     */
    private function getShippingRates(array $package): array
    {
        $products = [];
        $shippingRates = [];

        foreach ($package['contents'] as $productCart) {
            $product['sku'] = $productCart['data']->get_sku();
            $product['quantity'] = $productCart['quantity'];
            $products[] = $product;
        }

        $countryIsoCode = $package['destination']['country'];
        $postalCode = $package['destination']['postcode'];

        $publicationOptions = $this->publicationOptionsService->getPublicationOptions();

        if (
            \array_key_exists('shippingRateIncludedCountryIsoCode', $publicationOptions)
            && \strtolower($publicationOptions['shippingRateIncludedCountryIsoCode']) === \strtolower($countryIsoCode)
        ) {
            $shippingRate['id'] = self::SHIPPING_RATE_ID_PREFIX.ShippingService::NAME_FREE_SHIPPING;
            $shippingRate['package'] = $package;
            $shippingRate['label'] = ShippingService::NAME_FREE_SHIPPING;
            $shippingRate['cost'] = 0.0;
            $shippingRate['calc_tax'] = 'per_order';
            $shippingRate['taxes'] = 0.0;

            $shippingRates[] = $shippingRate;

            return $shippingRates;
        }

        $requestParams = $this->shippingRequestFactory->create($countryIsoCode, $postalCode, $products);

        if (self::$shippingCosts === null) {
            $shippingCosts = $this->shippingService->getShippingCosts($requestParams);
        }

        if (empty($shippingCosts)) {
            return [];
        }

        $conversionFactor = $publicationOptions['conversionFactor'];
        $taxRate = 0.0;

        try {
            $tax = $this->taxesService->getTaxWithMaxRate($countryIsoCode);
            $taxRate = $tax['Rate'] / self::TAX_DIVISION;
        } catch (\Exception $exception) {
            $this->loggerService->info('Empty taxes for country with iso_code '.$countryIsoCode);
        }

        $disabledShippingServiceNames = $this->shippingServiceManager->getDisabledNamesIndexedByName();
        $shippingCosts = $this->sortShippingCostsByLowerCost($shippingCosts);

        foreach ($shippingCosts as $shippingCost) {
            if (\array_key_exists($shippingCost->shippingService->name, $disabledShippingServiceNames)) {
                continue;
            }

            $shippingCostWithConversionRate = $shippingCost->cost * $conversionFactor;
            $shippingTax = $shippingCostWithConversionRate * $taxRate;

            $shippingServiceDelay = $this->shippingServiceDelayTranslationService->getDelayTranslationFromIsoCode(
                $shippingCost->shippingService->delay
            );

            $shippingRate['id'] = self::SHIPPING_RATE_ID_PREFIX.$shippingCost->shippingService->name;
            $shippingRate['package'] = $package;
            $shippingRate['label'] = $shippingCost->shippingService->serviceName.' '.$shippingServiceDelay;
            $shippingRate['cost'] = $shippingCostWithConversionRate;
            $shippingRate['calc_tax'] = 'per_order';
            $shippingRate['taxes'] = $shippingTax;

            $shippingRates[] = $shippingRate;
        }

        return $shippingRates;
    }

    /**
     * @param ShippingOption[] $shippingCosts
     *
     * @return array
     */
    private function sortShippingCostsByLowerCost(array $shippingCosts): array
    {
        \usort($shippingCosts, static function (ShippingOption $a, ShippingOption $b) {
            $costA = $a->cost;
            $costB = $b->cost;

            if ($costA === $costB) {
                return 0;
            }

            return ($costA > $costB) ? +1 : -1;
        });

        return $shippingCosts;
    }
}