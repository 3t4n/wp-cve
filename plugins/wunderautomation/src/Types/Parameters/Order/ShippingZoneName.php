<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WC_Shipping_Zones;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class ShippingZoneName
 */
class ShippingZoneName extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'order';
        $this->title       = 'shippingzone';
        $this->description = __('Shipping zone name', 'wunderauto');
        $this->objects     = ['order'];

        $this->usesDefault = true;
    }

    /**
     * @param WC_Order  $order
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($order, $modifiers)
    {
        $allShippingItems = $order->get_items('shipping');

        if (count($allShippingItems) == 0) {
            return '';
        }
        $shipping   = reset($allShippingItems);
        $instanceId = (int)$shipping->get_instance_id(); // @phpstan-ignore-line
        if ($instanceId == 0) {
            return '';
        }

        $shippingZone = WC_Shipping_Zones::get_zone_by('instance_id', $instanceId);
        if (!($shippingZone instanceof \WC_Shipping_Zone)) {
            return '';
        }

        return $this->formatField($shippingZone->get_zone_name(), $modifiers);
    }
}
