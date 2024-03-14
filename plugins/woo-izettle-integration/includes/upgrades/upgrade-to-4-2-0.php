<?php

/**
 * Handles upgrading changes in 4.2.0
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2020 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!get_site_transient('izettle_upgraded_4_2_0')) {
    set_site_transient('izettle_upgraded_4_2_0', date('c'));
    if (!empty($product_status = get_option('izettle_product_status')) && !is_array($product_status)) {
        update_option('izettle_product_status', array($product_status));
    }
}