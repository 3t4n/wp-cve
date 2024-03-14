<?php

namespace XproElementorAddons\Libs\Dashboard\Classes;

defined( 'ABSPATH' ) || exit;

class Xpro_Elementor_Dashboard_Ajax {

	private $utils;

	public function __construct() {
		add_action( 'wp_ajax_xpro_elementor_addons_admin_action', array( $this, 'xpro_elementor_addons_admin_action' ) );
		$this->utils = Xpro_Elementor_Dashboard_Utils::instance();
	}

	public function xpro_elementor_addons_admin_action() {

		check_ajax_referer( 'xpro-dashboard-nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$widgets   = filter_var_array( wp_unslash( $_POST['xpro_elementor_widget_list'] ), FILTER_UNSAFE_RAW );
		$modules   = filter_var_array( wp_unslash( $_POST['xpro_elementor_module_list'] ), FILTER_UNSAFE_RAW );
		$user_data = filter_var_array( wp_unslash( $_POST['xpro_elementor_user_data'] ), FILTER_UNSAFE_RAW );

		$this->utils->save_option( 'xpro_elementor_widget_list', $widgets ? $widgets : array() );
		$this->utils->save_option( 'xpro_elementor_module_list', $modules ? $modules : array() );
		$this->utils->save_option( 'xpro_elementor_user_data', $user_data ? $user_data : array() );

		wp_die(); // this is required to terminate immediately and return a proper response
	}
}
