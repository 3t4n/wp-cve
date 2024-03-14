<?php

/*******************************************************************************
 *
 *  Copyrights 2017 to Present - Sellergize Web Technology Services Pvt. Ltd. - ALL RIGHTS RESERVED
 *
 * All information contained herein is, and remains the
 * property of Sellergize Web Technology Services Pvt. Ltd.
 *
 * The intellectual and technical concepts & code contained herein are proprietary
 * to Sellergize Web Technology Services Pvt. Ltd. (India), and are covered and protected
 * by copyright law. Reproduction of this material is strictly forbidden unless prior
 * written permission is obtained from Sellergize Web Technology Services Pvt. Ltd.
 * 
 * ******************************************************************************/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

function couponapi_deactivate()
{
    // DROP couponapi coupons
    couponapi_delete_offers();

    global $wpdb;
    $wp_prefix = $wpdb->prefix;

    // DROP plugin tables
    $wpdb->query("DROP TABLE IF EXISTS " . $wp_prefix . "couponapi_logs, " . $wp_prefix . "couponapi_config, " . $wp_prefix . "couponapi_upload");
}
