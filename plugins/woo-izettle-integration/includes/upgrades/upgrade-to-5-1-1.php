<?php

/**
 * Handles upgrading changes in 5.1.1
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2020 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!get_site_transient('izettle_upgraded_5_1_1')) {
    set_site_transient('izettle_upgraded_5_1_1', date('c'));

    if ('yes' == get_option('izettle_when_changed_in_izettle')) {
        update_option('izettle_when_changed_in_izettle', 'update');
    }

    if ('yes' == get_option('izettle_update_name_from_webhook')) {
        update_option('izettle_import_name', 'iz_name');
    }

    if ('yes' == get_option('izettle_update_price_from_webhook')) {
        update_option('izettle_webhook_price', 'iz_price');
    }

    if ('yes' == get_option('izettle_import_price')) {
        update_option('izettle_import_price', 'wc_price');
    }

    if ('wc_order' == ($purchase_sync_function = get_option('izettle_purchase_sync_function'))) {
        update_option('izettle_stocklevel_from_izettle_change', 'purchases'); // check
    }

    if ('wc_stockchange' == $purchase_sync_function) {
        update_option('izettle_stocklevel_from_izettle_change', 'purchases'); // check
    }

    if ('iz_stockchange' == $purchase_sync_function) {
        update_option('izettle_stocklevel_from_izettle_change', 'iz_stockchange'); // check
        remove_option('izettle_purchase_sync_function'); // check
    }

    if (get_option('izettle_stocklevel_sync_model')) {
        update_option('izettle_stocklevel_from_woocommerce', 'yes');
    }

    if (get_option('izettle_update_barcode_from_webhook')) {
        update_option('izettle_webhook_barcode', get_option('izettle_update_barcode_from_webhook'));
    }

    if (get_option('izettle_update_cost_price_from_webhook')) {
        update_option('izettle_webhook_cost_price', get_option('izettle_update_cost_price_from_webhook'));
    }

    if (get_option('izettle_create_global_attributes')) {
        update_option('izettle_import_create_global_attributes', get_option('izettle_create_global_attributes'));
    }

}