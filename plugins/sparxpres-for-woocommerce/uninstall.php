<?php
/**
 * Sparxpres WebSale Uninstall
 *
 * Uninstalling Sparxpres WebSale deletes Sparxpres WebSale settings
 *
 */

defined('WP_UNINSTALL_PLUGIN') || exit;

require_once dirname(__FILE__) . '/includes/sparxpres-utils.php';

delete_option(SparxpresUtils::$DK_SPARXPRES_LINK_ID);
delete_option(SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_PRODUCT_PAGE);
delete_option(SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_CART_PAGE);
delete_option(SparxpresUtils::$DK_SPARXPRES_VIEW_TYPE);
delete_option(SparxpresUtils::$DK_SPARXPRES_CALLBACK_IDENTIFIER);

delete_option(SparxpresUtils::$DK_SPARXPRES_MAIN_COLOR);
delete_option(SparxpresUtils::$DK_SPARXPRES_SLIDER_BG_COLOR);

delete_option(SparxpresUtils::$DK_SPARXPRES_INFO_PAGE_ID);
delete_option(SparxpresUtils::$DK_SPARXPRES_CONTENT_DISPLAY_TYPE);

delete_option('dk_spx_old_order_status_converted');
delete_option('dk_spx_callback_key_send');
delete_option('dk_spx_convert_old_order_status');

delete_option('woocommerce_sparxpres_settings');
delete_option('woocommerce_xprespay_settings');
