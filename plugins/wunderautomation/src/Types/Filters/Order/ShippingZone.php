<?php

namespace WunderAuto\Types\Filters\Order;

use WC_Shipping_Zones;
use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class ShippingZone
 */
class ShippingZone extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('Order shipping zone', 'wunderauto');
        $this->description = __('Filter WooCommerce orders based on shipping zone', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators = $this->setOperators();
        $this->inputType = 'multiselect';
    }

    /**
     * Initialize
     *
     * @return void
     */
    public function initialize()
    {
        $zones = WC_Shipping_Zones::get_zones();
        foreach ($zones as $zone) {
            $this->compareValues[] = ['value' => $zone['zone_id'], 'label' => $zone['zone_name']];
        }
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $order = $this->getObject();
        if (!($order instanceof \WC_Order)) {
            return false;
        }

        $actualValue = $this->getShippingZoneId($order);
        if ($actualValue === false) {
            return false;
        }

        return $this->evaluateCompare($actualValue);
    }

    /**
     * @param \WC_Order $order
     *
     * @return int|false
     */
    private function getShippingZoneId($order)
    {
        $allShippingItems = $order->get_items('shipping');

        if (count($allShippingItems) == 0) {
            return false;
        }

        $shipping   = reset($allShippingItems);
        $instanceId = (int)$shipping->get_instance_id(); // @phpstan-ignore-line
        if ($instanceId == 0) {
            return false;
        }

        $shippingZone = WC_Shipping_Zones::get_zone_by('instance_id', $instanceId);
        if (!($shippingZone instanceof \WC_Shipping_Zone)) {
            return false;
        }

        return $shippingZone->get_id();
    }
}
