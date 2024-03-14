<?php
/*
  Plugin Name: Without payment woocommerce
  Plugin URI: https://github.com/northmule/without-payment-for-woocommerce
  Description: The payment gateway is a stub. Allows you to make a purchase by notifying the buyer that the manager will contact him later.
  Version: 1.3.2
  Author: Djo
  Author URI: https://habr.com/ru/users/northmule/
  WC requires at least: 5.2
  WC tested up to: 8.2
  Requires at least: 5.1
  Tested up to: 5.9
  Text Domain: coderun-without-payment-woocommerce
  Domain Path: /languages
 */

/*  Copyright 2023  Djo  (email: izm@zixn.ru)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */

__('Without payment woocommerce');
__('The payment gateway is a stub. Allows you to make a purchase by notifying the buyer that the manager will contact him later.');

add_filter('woocommerce_payment_gateways',
    static function(array $gateways): array {
        $currentPath = plugin_dir_path(__FILE__);
        require $currentPath.'/vendor/autoload.php';
        $methods[] = new \Coderun\WithoutPaymentWoocommerce\Gateway(plugin_dir_url(__FILE__));
        return $methods;
    }
);

add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
            'custom_order_tables', __FILE__, true
        );
    }
});
