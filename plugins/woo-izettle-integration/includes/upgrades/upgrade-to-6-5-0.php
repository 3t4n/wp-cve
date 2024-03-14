<?php

/**
 * Handles upgrading changes in 5.1.3
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2020 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!get_site_transient('izettle_upgraded_6_5_0')) {
    set_site_transient('izettle_upgraded_6_5_0', date('c'));

    if ('_ean13' == get_option('izettle_product_update_barcode')) {
        update_option('izettle_product_update_barcode', '_barcode');
        update_option('izettle_product_barcode_generate', 'ean13_automatic');
    }

}