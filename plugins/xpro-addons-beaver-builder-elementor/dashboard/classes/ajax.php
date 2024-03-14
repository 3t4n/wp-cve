<?php

defined( 'ABSPATH' ) || exit;

class Xpro_Beaver_Dashboard_Ajax {

	private $utils;
	public static $instance = null;

	public function init() {
		add_action( 'wp_ajax_xpro_beaver_addons_admin_action', array( $this, 'xpro_beaver_addons_admin_action' ) );
		$this->utils = Xpro_Beaver_Dashboard_Utils::instance();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	public function xpro_beaver_addons_admin_action() {

		check_ajax_referer( 'xpro-dashboard-nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$modules   = $_POST['xpro_beaver_modules_list'] ? filter_var_array( wp_unslash( $_POST['xpro_beaver_modules_list'] ), FILTER_SANITIZE_STRING ) : array();
		$features  = $_POST['xpro_beaver_features_list'] ? filter_var_array( wp_unslash( $_POST['xpro_beaver_features_list'] ), FILTER_SANITIZE_STRING ) : array();
		$user_data = $_POST['xpro_beaver_user_data'] ? filter_var_array( wp_unslash( $_POST['xpro_beaver_user_data'] ), FILTER_SANITIZE_STRING ) : array();

		$this->utils->save_option( 'xpro_beaver_modules_list', $modules ? $modules : array() );
		$this->utils->save_option( 'xpro_beaver_features_list', $features ? $features : array() );
		$this->utils->save_option( 'xpro_beaver_user_data', $user_data ? $user_data : array() );

		wp_die(); // this is required to terminate immediately and return a proper response
	}
}

Xpro_Beaver_Dashboard_Ajax::instance();
