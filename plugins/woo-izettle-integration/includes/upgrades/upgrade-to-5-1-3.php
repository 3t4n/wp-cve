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

if (!get_site_transient('izettle_upgraded_5_1_2')) {
    set_site_transient('izettle_upgraded_5_1_2', date('c'));

    if ('_ean13' == ($current_barcode_option = get_option('izettle_product_generate_barcode'))) {
        update_option('izettle_product_update_barcode', '_ean13');
    } else {
        update_option('izettle_product_update_barcode', $current_barcode_option);
    }

}