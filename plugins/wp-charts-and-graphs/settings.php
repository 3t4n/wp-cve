<?php
defined( 'ABSPATH' ) OR exit;
if ( ! class_exists( 'pantherius_wp_charts_settings' ) ) {
	class pantherius_wp_charts_settings extends pantherius_wp_charts {
		/**
		* Construct the plugin object
		**/
		public function __construct() {
			/**
			* register actions, hook into WP's admin_init action hook
			**/
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'add_menu' ) );
		}

		/**
		* This function provides textarea inputs for settings fields
		**/
		public function settings_field_input_textarea($args) {
			$other = $args['other'];
			// Get the field name from the $args array or get the value of this setting
			$field = $args['field'];
			if ($args['field_value']) $value = $args['field_value'];
			else $value = get_option($field);
			// echo a proper input type="textarea"
			if ( ! empty( $other ) ) {
				echo sprintf( '<textarea name="%s" id="%s" %s />%s</textarea>', $field, $field, $other, $value );
			}
			else {
				echo sprintf( '<textarea name="%s" id="%s" />%s</textarea>', $field, $field, $value );
			}
		}

		/**
		* include custom scripts and style to the admin page
		**/
		function enqueue_admin_custom_scripts_and_styles() {
			wp_enqueue_style( 'pantherius_wp_charts_admin_style', plugins_url( '/assets/css/pantherius_wp_charts_settings.css', __FILE__ ) );
			wp_enqueue_style( 'pantherius_wp_charts_admin_colorpicker_style', plugins_url( '/assets/css/colorpicker.css', __FILE__ ) );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'pantherius_wp_charts_admin_chart', plugins_url( '/assets/js/Chart.min.js', __FILE__ ), array( 'pantherius_wp_charts_admin' ), '100018', false );
			wp_enqueue_script( 'pantherius_wp_charts_script', plugins_url( '/assets/js/pantherius_wp_charts.js', __FILE__ ), array( 'jquery', 'pantherius_wp_charts_admin_chart' ), '1.0' );
			wp_enqueue_script( "pantherius_wp_charts_admin_colorpicker_script", plugins_url('/assets/js/colorpicker.js', __FILE__ ), array( 'jquery' ) );
			wp_register_script('pantherius_wp_charts_admin', plugins_url( '/assets/js/pantherius_wp_charts_admin.js', __FILE__ ) , array( 'jquery' ), '100018', false );
			wp_localize_script( 'pantherius_wp_charts_admin', 'sspa_params', array( 'plugin_url' => plugins_url( '', __FILE__ ), 'admin_url' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script( 'pantherius_wp_charts_admin' );
		}

		public function pantherius_wp_charts_settings_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', PWPC_CHARTS_TEXT_DOMAIN ) );
			}
			// Render the settings template
			// add your settings section
			add_settings_section('pantherius_wp_charts-section', '', array( $this, 'settings_section_pantherius_wp_charts' ), 'pantherius_wp_charts' );
			// add your setting's fields
			add_settings_field( 'pantherius_wp_charts-setting_include_files', esc_html__( 'Include Plugin Files to Specified Pages Only (comma separated list of page IDs - except home page)', PWPC_CHARTS_TEXT_DOMAIN ), array($this, 'settings_field_input_textarea'), 'pantherius_wp_charts', 'pantherius_wp_charts-section', array( 'field' => 'setting_include_files', 'field_value' => '', 'other' => 'rows="3" cols="70"' ) );
			add_settings_field('pantherius_wp_charts-setting_exclude_files', esc_html__( 'Exclude Plugin Files from Specified Pages (comma separated list of page IDs - except home page)', PWPC_CHARTS_TEXT_DOMAIN ), array( $this, 'settings_field_input_textarea'), 'pantherius_wp_charts', 'pantherius_wp_charts-section', array( 'field' => 'setting_exclude_files', 'field_value' => '', 'other' => 'rows="3" cols="70"' ) );

			include(sprintf("%s/templates/options.php", dirname(__FILE__)));			
		}

		/**
		* initialize datas on wp admin
		**/
		public function admin_init() {
			$settings_page = '';
			if ( isset( $_REQUEST[ 'page' ] ) ) {
				$settings_page = $_REQUEST[ 'page' ];
			}
			if ( $settings_page == 'pantherius_wp_charts' || $settings_page == 'pantherius_wp_charts_settings' ) {
				add_action( 'admin_head', array( &$this, 'enqueue_admin_custom_scripts_and_styles' ) );
			}
			// Possibly do additional admin_init tasks
			register_setting( 'pantherius_wp_charts-group', 'setting_include_files' );
			register_setting( 'pantherius_wp_charts-group', 'setting_exclude_files' );
		}

		/**
		* add a menu
		**/		
		public function add_menu() {
			// Add a page to manage this plugin's settings
			add_menu_page( 'Charts and Graphs', 'Charts and Graphs', 'manage_options', 'pantherius_wp_charts', array( &$this, 'plugin_settings_page' ), 'dashicons-chart-bar', '65.014' );
			add_submenu_page( 'pantherius_wp_charts', 'Charts and Graphs', esc_html__( 'Generate Chart', PWPC_CHARTS_TEXT_DOMAIN ), 'manage_options', 'pantherius_wp_charts', array( $this, 'plugin_settings_page' ) );
			add_submenu_page( 'pantherius_wp_charts', 'Charts and Graphs', esc_html__( 'Settings', PWPC_CHARTS_TEXT_DOMAIN ), 'manage_options', 'pantherius_wp_charts_settings', array( $this, 'pantherius_wp_charts_settings_page' ) );
		}
		
		/**
		* Menu Callback
		**/		
		public function plugin_settings_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', PWPC_CHARTS_TEXT_DOMAIN ) );
			}
			// Render the settings template
			include( sprintf( "%s/templates/settings.php", dirname( __FILE__ ) ) );
		}
		public function settings_section_wp_sap() {
		
		}
	}
}
?>