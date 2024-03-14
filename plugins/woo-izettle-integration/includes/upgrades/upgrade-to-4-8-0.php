<?php

/**
 * Handles upgrading changes in 4.8.0
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2020 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!get_site_transient('izettle_upgraded_4_8_0')) {
    set_site_transient('izettle_upgraded_4_8_0', date('c'));
    delete_option('izettle_last_purchase_hash');
    if (WC_Zettle_Helper::update_product_data()) {
        update_option('izettle_when_changed_in_izettle', 'update');
    }
}