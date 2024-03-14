<?php

namespace FedExVendor\WPDesk\WooCommerceShipping\Cache;

use FedExVendor\WPDesk\AbstractShipping\Shop\ShopSettings;
/**
 * Can generate MD5 hash for shop settings.
 */
class ShopSettingsMd5HashGenerator
{
    /**
     * @param ShopSettings $shop_settings
     * @return string
     */
    public function generate_md5_hash(\FedExVendor\WPDesk\AbstractShipping\Shop\ShopSettings $shop_settings)
    {
        return \md5($shop_settings->get_currency() . $shop_settings->get_default_currency() . $shop_settings->get_locale() . $shop_settings->get_origin_country() . $shop_settings->get_weight_unit() . $shop_settings->is_tax_enabled() . $shop_settings->get_price_rounding_precision() . $shop_settings->is_testing());
    }
}
