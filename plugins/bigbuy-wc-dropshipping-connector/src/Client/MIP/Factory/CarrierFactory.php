<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Client\MIP\Model\Carrier;

class CarrierFactory
{
    /** @var CarrierFactory */
    private static $instance;

    /**
     * @return CarrierFactory
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param array $mipCarrier
     * @return Carrier
     */
    public function create($mipCarrier)
    {
        $carrier = new Carrier();
        $carrier->id = $mipCarrier['id'];
        $carrier->name = $mipCarrier['name'];
        $carrier->nameCanonical = $mipCarrier['name_canonical'];
        $carrier->carrierSystemId = $mipCarrier['carrier_system_id'];

        return $carrier;
    }
}
