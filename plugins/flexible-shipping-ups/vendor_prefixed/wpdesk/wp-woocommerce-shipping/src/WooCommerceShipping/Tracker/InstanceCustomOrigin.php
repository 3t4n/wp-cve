<?php

namespace UpsFreeVendor\WPDesk\WooCommerceShipping\Tracker;

use UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\InstanceCustomOriginFields;
use UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasInstanceCustomOrigin;
class InstanceCustomOrigin
{
    public function append_instance_custom_origin_data(array $data, \WC_Shipping_Method $shipping_method) : array
    {
        if (!isset($data['instance_custom_origin_count'])) {
            $data['instance_custom_origin_count'] = 0;
        }
        if ($shipping_method instanceof \UpsFreeVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasInstanceCustomOrigin && $shipping_method->get_option(\UpsFreeVendor\WPDesk\WooCommerceShipping\CustomOrigin\InstanceCustomOriginFields::CUSTOM_ORIGIN, 'no') === 'yes') {
            $data['instance_custom_origin_count']++;
        }
        return $data;
    }
}
