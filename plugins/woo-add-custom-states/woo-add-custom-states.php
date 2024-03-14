<?php

/*
Plugin Name: Woo Add Custom States
Plugin URI: https://www.trustech.net/product/add-custom-states/
Description: A plugin used to add custom states to any country which can be used later on for setting the shipping zones.
Version: 1.8.5
Author: Trusted Technology Solutions (TrusTech)
Author URI: https://www.trustech.net/
Text Domain: woo-add-custom-states
Copyright: © 2009-2017 Trusted Technology Solutions (TrusTech).
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
WC requires at least: 4.0
WC tested up to: 6.3.1
*/

/*
 * WE PROVIDE THE PROGRAM “AS IS” WITHOUT WARRANTY OF ANY KIND,
 * EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.
 * THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU.
 * SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING,
 * REPAIR OR CORRECTION.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/**
	 * Includes and Requires Section
	 **/

	require_once ('includes/wacs_menu_manager.php');
	require_once ('includes/wacs_list_table.php');
	include_once ('includes/wacs_settings_page.php');

	/**
	 * Filters and Add Action Section
	 **/

    add_action( 'woocommerce_loaded', 'wacs_actions' );

    function wacs_actions()
    {
        add_action('admin_menu', array(new Wacs_Menu_Manager(), 'wacs_add_menu_item'));
        add_action('admin_enqueue_scripts', 'wacs_script_enqueue');
        add_action('wp_ajax_wacs_update_table', 'wacs_settings_page');
        add_filter( 'woocommerce_states', array(new Wacs_Functions(), 'custom_wacs_states' ));
    }

	function wacs_script_enqueue() {
		if (isset($_GET['page'])) {
			if ( $_GET['page'] != 'wacs_add_states' ) {
				return;
			}
            wp_register_script('wacs_scripts', plugins_url('js/scripts.js'),array('jquery'),'1.0', true);
			wp_enqueue_script(
				'wacs_custom_scripts',
				plugin_dir_url( __FILE__ ) . 'js/scripts.js',
				array( 'jquery' )
			);
			add_filter('set-screen-option', array(new Wacs_List_Table(), 'states_table_set_option'), 10, 3);
		}
	}
}