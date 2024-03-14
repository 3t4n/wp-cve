<?php
/**
 * Plugin Name: PriceList for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/pricelist-for-woocommerce/
 * Description: With this plugin you'll be able to generate price lists for your WooCommerce products and display them in a overview or create a pdf. Shortcodes included.
 * Author: Inner Join
 * Author URI: https://inner-join.nl/over-ons/
 * License: GPLv3
 * Version: 1.1.0
 * WC requires at least: 2.3
 * WC tested up to: 7.1
 * 
 * @package PriceList_for_WooCommerce
 */
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 3, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if (plugin_basename(__DIR__) !== 'pricelist-for-woocommerce') return;

define('PRICELIST_WC', true);
require_once 'pricelist-for-woocommerce-plugin.php';
$pricelist_plugin = new pricelist_wc(__FILE__);
$pricelist_plugin->init();
?>