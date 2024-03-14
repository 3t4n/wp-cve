<?php

Namespace Unbloater;

defined( 'ABSPATH' ) || die();

class Unbloater_Init {
	
	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->init_helper();
		$this->init_settings();
		$this->init_admin();
		$this->init_unbloat();
	}
	
	/**
	 * Initialize helper
	 */
	public function init_helper() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'classes/ub-helper.php';
		new Unbloater_Helper();
	}
	
	/**
	 * Initialize plugin settings
	 */
	public function init_settings() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'classes/ub-settings.php';
		new Unbloater_Settings();
	}
	
	/**
	 * Initialize admin settings page
	 */
	public function init_admin() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'classes/ub-admin.php';
		new Unbloater_Admin();
	}	
	
	/**
	 * Initialize unbloat functions
	 */
	public function init_unbloat() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'classes/ub-unbloat.php';
		new Unbloater_Unbloat();
	}
	
}
