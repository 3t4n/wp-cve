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

if (!get_site_transient('izettle_upgraded_7_0_0')) {

    set_site_transient('izettle_upgraded_7_0_0', date('c'));

    if ('iz_stockchange' == get_option('izettle_stocklevel_from_izettle_change')) {
        remove_option('izettle_stocklevel_from_izettle_change');
        update_option('izettle_import_stocklevel', 'yes');
    }

}
