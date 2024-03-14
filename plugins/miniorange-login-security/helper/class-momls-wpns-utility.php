<?php
/** The miniOrange enables user to log in through mobile authentication as an additional layer of security over password.
 *
 * @package      miniorange-login-security/helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Momls_Wpns_Utility' ) ) {
	/**
	 * This class has all functions used throughout the plugin to log in through mobile authentication as an additional layer of security over password.
	 */
	class Momls_Wpns_Utility {

		/**
		 * To check whether the customer has registered a miniOrange account.
		 *
		 * @return boolean
		 */
		public static function momls_icr() {
			$email        = get_site_option( 'mo2f_email' );
			$customer_key = get_site_option( 'mo2f_customerKey' );
			if ( ! $email || ! $customer_key || ! is_numeric( trim( $customer_key ) ) ) {
				return 0;
			} else {
				return 1;
			}
		}
		/**
		 * To check whether the variable is empty or null
		 *
		 * @param string $value variable that needs to chekc if empty or null.
		 * @return boolean
		 */
		public static function momls_check_empty_or_null( $value ) {
			if ( ! isset( $value ) || empty( $value ) ) {
				return true;
			}
			return false;
		}
		/**
		 * To check if the curl extension in installed.
		 *
		 * @return boolean
		 */
		public static function momls_is_curl_installed() {
			if ( in_array( 'curl', get_loaded_extensions(), true ) ) {
				return 1;
			} else {
				return 0;
			}
		}
		/**
		 * Collect data for the plugin configurations.
		 *
		 * @return string
		 */
		public static function momls_send_configuration() {
			global $momlsdb_queries;
			$user_object      = wp_get_current_user();
			$other_methods    = $momlsdb_queries->mo2f_get_all_user_2fa_methods();
			$key              = get_option( 'mo2f_customerKey' );
			$no_of_2fa_users  = $momlsdb_queries->mo2f_get_no_of_2fa_users();
			$user_count       = isset( ( count_users() )['total_users'] ) ? ( count_users() )['total_users'] : '';
			$specific_plugins = array(
				'UM_Functions'   => 'Ultimate Member',
				'wc_get_product' => 'WooCommerce',
				'pmpro_gateways' => 'Paid MemberShip Pro',
			);
			$space            = '<span>&nbsp;&nbsp;&nbsp;</span>';

			$plugin_configuration = '<br><I>Plugin Configuration :-</I>' . $space . 'No. of 2FA users :' . $no_of_2fa_users . $space . 'Total users : ' . $user_count . $space . 'Methods of users:' . ( empty( $other_methods ) ? 'NONE' : $other_methods ) . ( ( momls_is_customer_registered() ) ? ( $space . 'Customer Key:' . $key ) : ( $space . "Customer Registered:'No" ) ) . $space;

			$plugins = '';
			foreach ( $specific_plugins as $class_name => $plugin_name ) {
				if ( class_exists( $class_name ) || function_exists( $class_name ) ) {
					$plugins = $plugins . $plugin_name . "'";
				}
			}

			if ( time() - get_site_option( 'mo_2fa_pnp' ) < 2592000 && ( get_site_option( 'mo_2fa_plan_type' ) || get_site_option( 'mo_2fa_addon_plan_type' ) ) ) {
				$plugin_configuration = $plugin_configuration . $space . "Checked plans:'";
				if ( get_site_option( 'mo_2fa_plan_type' ) ) {
					$plugin_configuration = $plugin_configuration . get_site_option( 'mo_2fa_plan_type' ) . "'";
				}
				if ( get_site_option( 'mo_2fa_addon_plan_type' ) ) {
					$plugin_configuration = $plugin_configuration . get_site_option( 'mo_2fa_addon_plan_type' ) . "'";
				}
			}
			$plugin_configuration = $plugin_configuration . $space . 'PHP_version:' . phpversion() . $space . 'Wordpress_version:' . get_bloginfo( 'version' );

			if ( get_site_option( 'enable_form_shortcode' ) ) {
				$forms = array( 'mo2f_custom_reg_bbpress', 'mo2f_custom_reg_wocommerce', 'mo2f_custom_reg_custom' );
				foreach ( $forms as $form ) {
					if ( get_site_option( $form ) ) {
						$plugin_configuration = $plugin_configuration . $space . $form . ':' . get_option( $form );
					}
				}
			}

			return $plugin_configuration;

		}

	}
}
