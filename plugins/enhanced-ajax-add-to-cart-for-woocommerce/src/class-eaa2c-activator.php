<?php

/**
 * Fired during plugin activation
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       www.theritesites.com
 * @since      1.0.0
 * @package    Enhanced_Ajax_Add_To_Cart_Wc
 * @subpackage Enhanced_Ajax_Add_To_Cart_Wc/includes
 * @author     TheRiteSites <contact@theritesites.com>
 */

if ( ! class_exists( 'Enhanced_Ajax_Add_To_Cart_Wc_Activator' ) ) {
	class Enhanced_Ajax_Add_To_Cart_Wc_Activator {

		/**
		 * Short Description. (use period)
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function activate() {
			if ( false === get_option( 'a2cp_out_of_stock' ) ) {
				update_option( 'a2cp_out_of_stock', null, false );
				update_option( 'a2cp_default_text', null, false );
				update_option( 'a2cp_custom_class', null, false );
				update_option( 'a2cp_debug', null, false );
				update_option( 'a2cp_dom_check', null, false );
				update_option( 'a2cp_button_blocking', null, false );
				register_setting(
					'a2cp_settings',
					'a2cp_button_blocking',
					array(
						'type' => 'boolean',
						'description' => '',
						// 'sanitize_callback' => array( $this, '' ),
						'show_in_rest' => true
						// 'default' => false
					)
				);
				register_setting(
					'a2cp_settings',
					'a2cp_debug',
					array(
						'type' => 'boolean',
						'description' => '',
						// 'sanitize_callback' => array( $this, '' ),
						'show_in_rest' => true
						// 'default' => false
					)
				);
				register_setting(
					'a2cp_settings',
					'a2cp_dom_check',
					array(
						'type' => 'boolean',
						'description' => '',
						// 'sanitize_callback' => array( $this, '' ),
						'show_in_rest' => true
						// 'default' => false
					)
				);
				register_setting(
					'a2cp_settings',
					'a2cp_delete_on_deactivation',
					array(
						'type' => 'boolean',
						'description' => '',
						// 'sanitize_callback' => array( $this, '' ),
						'show_in_rest' => true
						// 'default' => false
					)
				);
				register_setting(
					'a2cp_settings',
					'a2cp_custom_class',
					array(
						'type' => 'text',
						'description' => '',
						// 'sanitize_callback' => array( $this, '' ),
						'show_in_rest' => true
						// 'default' => false
					)
				);
				register_setting(
					'a2cp_settings',
					'a2cp_default_text',
					array(
						'type' => 'text',
						'description' => '',
						// 'sanitize_callback' => array( $this, '' ),
						'show_in_rest' => true
						// 'default' => false
					)
				);
				register_setting(
					'a2cp_settings',
					'a2cp_out_of_stock',
					array(
						'type' => 'boolean',
						'description' => '',
						// 'sanitize_callback' => array( $this, '' ),
						'show_in_rest' => true
						// 'default' => false
					)
				);
			}
		}
	}
}