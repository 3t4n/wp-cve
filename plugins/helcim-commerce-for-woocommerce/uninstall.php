<?php
/**
 * Helcim Inc Payment Gateway
 *
 * Uninstalls Helcim Gateway.
 *
 * @version    4.0.3
 * @author        Helcim Inc.
 */

if ( !defined('WP_UNINSTALL_PLUGIN') ) exit;

delete_option( 'woocommerce_helcim_settings' );
