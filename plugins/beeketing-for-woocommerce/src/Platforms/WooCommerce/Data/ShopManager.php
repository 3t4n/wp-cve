<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:21 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Data;


use BeeketingConnect_beeketing_woocommerce\Common\Data\CommonHelper;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Manager\ShopManagerAbstract;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Shop;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Helper;

class ShopManager extends ShopManagerAbstract
{

    /**
     * Get shop
     * @return Shop
     */
    public function get()
    {
        $shop = new Shop();

        // Set shop domain from response data
        $shop->domain = Helper::getShopDomain();
        $shop->absolute_path = CommonHelper::parseAbsolutePath(get_home_url());
        $shop->currency = Helper::getCurrency();
        $shop->currency_format = Helper::getCurrencyFormat();
        $shop->name = get_bloginfo('name');
        $shop->id = '';

        $shop->address = get_option('woocommerce_store_address');
        $shop->city = get_option('woocommerce_store_city');
        $shop->zip = get_option('woocommerce_store_postcode');
        $shop->country = get_option('woocommerce_default_country');

        return $shop;
    }
}
