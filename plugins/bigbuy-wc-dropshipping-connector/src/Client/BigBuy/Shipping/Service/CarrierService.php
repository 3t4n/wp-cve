<?php

namespace WcMipConnector\Client\BigBuy\Shipping\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Client\BigBuy\Base\Service\AbstractService;

class CarrierService extends AbstractService
{
    const URL_SHIPPING_CARRIERS = '/rest/shipping/carriers';

    /** @var CarrierService */
    public static $instance;

    /**
     * @param string $apiKey
     * @throws \Exception
     */
    public function __construct($apiKey) {
        parent::__construct($apiKey);
    }

    /**
     * @param string $apiKey
     * @return CarrierService
     * @throws \Exception
     */
    public static function getInstance($apiKey)
    {
        if (!self::$instance) {
            self::$instance = new self($apiKey);
        }

        return self::$instance;
    }

    /**
     * @return array
     */
    public function getCarriers()
    {
        try {
            $shippingOptions = $this->get(self::URL_SHIPPING_CARRIERS);

            if (empty($shippingOptions)) {
                return null;
            }
        } catch (\Exception $exception) {
            return null;
        }

        return $shippingOptions;
    }
}