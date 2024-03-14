<?php

namespace WcMipConnector\Client\BigBuy\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Client\BigBuy\Model\ShippingOption;
use WcMipConnector\Client\BigBuy\Model\ShippingService;

class ShippingFactory
{
    /** @var ShippingFactory */
    private static $instance;

    /**
     * @return ShippingFactory
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param array $shippingOptionResponse
     * @return ShippingOption
     */
    public function create($shippingOptionResponse)
    {
        $shippingOption = new ShippingOption();
        $shippingOption->cost = $shippingOptionResponse['cost'];
        $shippingOption->weight = $shippingOptionResponse['weight'];
        $shippingService = new ShippingService();
        $shippingService->id = $shippingOptionResponse['shippingService']['id'];
        $shippingService->name = $shippingOptionResponse['shippingService']['name'];
        $shippingService->delay = $shippingOptionResponse['shippingService']['delay'];
        $shippingService->transportMethod = $shippingOptionResponse['shippingService']['transportMethod'];
        $shippingService->serviceName = \array_key_exists('serviceName', $shippingOptionResponse['shippingService']) ? $shippingOptionResponse['shippingService']['serviceName'] : $shippingOptionResponse['shippingService']['name'];
        $shippingOption->shippingService = $shippingService;

        return $shippingOption;
    }
}
