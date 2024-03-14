<?php

/**
 * Handles upgrading changes in 5.1.2
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

    if (get_option('izettle_webhook_name')) {
        update_option('izettle_import_name', get_option('izettle_webhook_name'));
        delete_option('izettle_webhook_name');
    }

    if (get_option('izettle_webhook_price')) {
        update_option('izettle_import_price', get_option('izettle_webhook_price'));
        delete_option('izettle_webhook_price');
    }

    if (get_option('izettle_webhook_barcode')) {
        update_option('izettle_import_barcode', get_option('izettle_webhook_barcode'));
        delete_option('izettle_webhook_barcode');
    }

    if (get_option('izettle_webhook_sku')) {
        update_option('izettle_import_sku', get_option('izettle_webhook_sku'));
        delete_option('izettle_webhook_sku');
    }

    if (get_option('izettle_webhook_cost_price')) {
        update_option('izettle_import_cost_price', get_option('izettle_webhook_cost_price'));
        delete_option('izettle_webhook_cost_price');
    }

    if (get_option('izettle_webhook_category')) {
        update_option('izettle_import_category', get_option('izettle_webhook_category'));
        delete_option('izettle_webhook_category');
    }

    if (get_option('izettle_webhook_images')) {
        update_option('izettle_import_images', get_option('izettle_webhook_images'));
        delete_option('izettle_webhook_images');
    }

    if (get_option('izettle_webhook_create_global_attributes')) {
        update_option('izettle_import_create_global_attributes', get_option('izettle_webhook_create_global_attributes'));
        delete_option('izettle_webhook_create_global_attributes');
    }

    if (get_option('izettle_webhook_additional_images')) {
        update_option('izettle_import_additional_images', get_option('izettle_webhook_additional_images'));
        delete_option('izettle_webhook_additional_images');
    }

    if (get_option('izettle_webhook_variant_images')) {
        update_option('izettle_import_variant_images', get_option('izettle_webhook_variant_images'));
        delete_option('izettle_webhook_variant_images');
    }

    if (get_option('izettle_webhook_weight')) {
        update_option('izettle_import_weight', get_option('izettle_webhook_weight'));
        delete_option('izettle_webhook_weight');
    }

}