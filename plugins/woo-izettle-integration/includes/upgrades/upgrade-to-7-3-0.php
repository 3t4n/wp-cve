<?php

/**
 * Handles upgrading changes in 7.3.0
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2020 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!get_site_transient('izettle_upgraded_7_3_0')) {

    set_site_transient('izettle_upgraded_7_3_0', date('c'));

    if ('purchases' === get_option('izettle_stocklevel_from_izettle_change')) {
        update_option('zettle_enable_purchase_processing', 'yes');
        delete_option('izettle_stocklevel_from_izettle_change');
    }

}
