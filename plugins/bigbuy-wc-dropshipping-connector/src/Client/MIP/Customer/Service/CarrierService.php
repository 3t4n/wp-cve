<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Customer\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Client\MIP\Base\Service\AbstractService;
use WcMipConnector\Client\MIP\Factory\CarrierFactory;
use WcMipConnector\Client\MIP\Model\Carrier;

class CarrierService extends AbstractService
{
    const URL_SHIPPING_CARRIERS = '/rest/customer/carriers';

    /** @var CarrierService */
    public static $instance;

    /** @var CarrierFactory */
    private $carrierFactory;

    /**
     * @param CarrierFactory $carrierFactory
     * @param string $apiKey
     */
    public function __construct(CarrierFactory $carrierFactory, $apiKey)
    {
        $this->carrierFactory = $carrierFactory;

        parent::__construct($apiKey);
    }

    /**
     * @param string $apiKey
     * @return CarrierService
     */
    public static function getInstance($apiKey)
    {
        if (!self::$instance) {
            $carrierFactory = CarrierFactory::getInstance();
            self::$instance = new self($carrierFactory, $apiKey);
        }

        return self::$instance;
    }

    /**
     * @return Carrier[]
     */
    public function getCarriers()
    {
        try {
            $mipCarriers = $this->get(self::URL_SHIPPING_CARRIERS);
        } catch (\Exception $exception) {
            return [];
        }

        $carriers = [];

        foreach ($mipCarriers as $mipCarrier) {
            $carriers[] = $this->carrierFactory->create($mipCarrier);
        }

        return $carriers;
    }
}
