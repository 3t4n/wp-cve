<?php
defined( 'ABSPATH' ) OR exit;
/**
 * Plugin Name: WP Charts and Graphs
 * Plugin URI: http://modalsurvey.com
 * Description: Add custom charts to your website
 * Author: Pantherius
 * Version: 1.2.2
 * Author URI: http://pantherius.com
 */

define( 'PWPC_CHARTS_TEXT_DOMAIN' , 'pwpcharts' );
define( 'PWPC_CHARTS_VERSION' , '1.2.2' );
 
if ( ! class_exists( 'pantherius_wp_charts' ) ) {
	class pantherius_wp_charts {
		var $pwpcharts_init_array = array();
		protected static $instance = null;
		/**
		 * Construct the plugin object
		 */
		public function __construct() {
			// installation and uninstallation hooks
			register_activation_hook( __FILE__, array( 'pantherius_wp_charts', 'activate' ) );
			register_deactivation_hook( __FILE__, array( 'pantherius_wp_charts', 'deactivate' ) );
			register_uninstall_hook( __FILE__, array( 'pantherius_wp_charts', 'uninstall' ) );
			add_action( 'plugins_loaded', array(&$this, 'pwpc_localization'));
			if ( is_admin() ) {
				require_once( sprintf( "%s/settings.php", dirname( __FILE__ ) ) );
				$pantherius_wp_charts_settings = new pantherius_wp_charts_settings();
				add_filter( "plugin_action_links_" . plugin_basename( __FILE__ ), array( &$this, 'add_action_links' ) );
				$wpcag_ltime = get_option( 'wpcag_ltime' );
				if ( empty( $wpcag_ltime ) ) {
					$date = date_create();
					update_option( 'wpcag_ltime', serialize( date_timestamp_get( $date ) ) );
				}
			}
			else {
				$pantherius_wp_charts_url = $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
				$pantherius_wp_charts_load = true;
				if ( ( strpos( $pantherius_wp_charts_url, 'wp-login' ) ) !== false ) {
					$pantherius_wp_charts_load = false;
				}
				if ( ( strpos( $pantherius_wp_charts_url, 'wp-admin' ) ) !== false ) {
					$pantherius_wp_charts_load = false;
				}
				if ( $pantherius_wp_charts_load || isset( $_REQUEST[ 'sspcmd' ] ) ) {
					//integrate the public functions
					add_shortcode( 'wpcharts', array( &$this, 'pantherius_wpcharts_shortcode' ) );
					add_action( 'init', array( &$this, 'enqueue_custom_scripts_and_styles' ) );
					add_action( 'get_footer' , array( &$this, 'initialize_chartjs' ), 175 );						
				}
			}
		}
		
		/**
		* Enable Localization
		**/
		public function pwpc_localization() {
			// Localization
			load_plugin_textdomain( 'pwpcharts', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		function pantherius_wpcharts_shortcode( $atts ) {
			$params = shortcode_atts( array(
						'type' => 'piechart',
						'titles' => '',
						'values' => '',
						'max' => '',
						'min' => 0,
						'legend' => 'false',
						'after' => '',
						'percentage' => '',
						'bgcolor' => '',
						'id' => ''
					), $atts );
			if ( empty( $params[ 'titles' ] ) ) {
				return __( "The titles parameter couldn't be empty! Please specify a comma separated list.", PWPC_CHARTS_TEXT_DOMAIN );
			}
			if ( empty( $params[ 'values' ] ) ) {
				return __( "The values parameter couldn't be empty! Please specify a comma separated list.", PWPC_CHARTS_TEXT_DOMAIN );
			}
			$params[ 'id' ] = uniqid();
			$params[ 'titles_array' ] =  explode( ",", $params[ 'titles' ] );
			$params[ 'values_array' ] =  explode( ",", $params[ 'values' ] );
			foreach( $params[ 'titles_array' ] as $key=>$ds ) {
				$params[ 'datas' ][ 0 ][ $key + 1 ][ 'answer' ] = $ds;				
				$params[ 'datas' ][ 0 ][ $key + 1 ][ 'count' ] = $params[ 'values_array' ][ $key ];				
			}
			$params[ 'style' ][ 'style' ] = $params[ 'type' ];
			$params[ 'style' ][ 'min' ] = ( int ) $params[ 'min' ];
			$params[ 'style' ][ 'max' ] = ( int ) $params[ 'max' ];
			$params[ 'style' ][ 'aftertag' ] = $params[ 'after' ];
			$params[ 'style' ][ 'percentage' ] = $params[ 'percentage' ];
			$params[ 'style' ][ 'bgcolor' ] = $params[ 'bgcolor' ];
			$params[ 'style' ][ 'legend' ] = $params[ 'legend' ];
			$this->pwpcharts_init_array[ $params[ 'id' ] ] = $params;
			$wpcag_ltime = get_option( 'wpcag_ltime' );
			if ( empty( $wpcag_ltime ) ) {
				$date = date_create();
				update_option( 'wpcag_ltime', serialize( date_timestamp_get( $date ) ) );
			}
			return '<div id="pwp-charts-' . $params[ 'id' ] . '"><canvas style="width: 100%; height: 100%;"></canvas></div>';
		}
		
		function initialize_chartjs() {
			wp_register_script( 'pantherius_wp_charts_init_script', plugins_url( '/assets/js/pantherius_wp_charts_init.js', __FILE__ ), array( 'jquery', 'jquery-chartjs', 'pantherius_wp_charts_script' ), PWPC_CHARTS_VERSION, true );
			wp_localize_script( 'pantherius_wp_charts_init_script', 'pwpc_params', $this->pwpcharts_init_array );
			wp_enqueue_script( 'pantherius_wp_charts_init_script' );			
		}
		
		//include custom CSS and JS files
		function enqueue_custom_scripts_and_styles() {
			$thispid = url_to_postid( site_url( $_SERVER[ 'REQUEST_URI' ] ) );
			$limitload = get_option( 'setting_include_files' );
			$exlimitload = get_option( 'setting_exclude_files' );
			$limitpages = array();
			if ( ! empty( $limitload ) ) {
				$limitpages = explode( ',', $limitload );
				if ( ( count( $limitpages ) > 0 && in_array( $thispid, $limitpages ) ) || ( count( $limitpages ) == 0 ) ) {
				}
				else {
					return;
				}
			}
			if ( ! empty( $exlimitload ) ) {
				$exlimitpages = explode( ',', $exlimitload );
			if ( ( count( $exlimitpages ) > 0 && in_array( $thispid, $exlimitpages ) ) || ( count( $exlimitpages ) == 0 ) ) {
					return;
				}
				else {
				}
			}
			wp_enqueue_style( 'pantherius_wp_charts_style', plugins_url( '/assets/css/pantherius_wp_charts.css', __FILE__ ) );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-chartjs', plugins_url( '/assets/js/Chart.min.js', __FILE__ ), array( 'jquery' ), '2.3.0' );
			wp_enqueue_script( 'pantherius_wp_charts_script', plugins_url( '/assets/js/pantherius_wp_charts.js', __FILE__ ), array( 'jquery', 'jquery-chartjs' ), PWPC_CHARTS_VERSION );
		}

		public static function getInstance() {
			if ( ! isset( $instance ) ) {
				$instance = new pantherius_wp_charts;
			}
		return $instance;
		}
		
		/**
		* Activate the plugin
		**/
		public static function activate() {
		}
		
		/**
		* Deactivate the plugin
		**/
		public static function deactivate() {
		}
		
		/**
		* Uninstall the plugin
		**/
		public static function uninstall() {
			delete_option( 'wpcag_ltime' );
		}
		
		/**
		* Add the settings link to the plugins page
		**/
		function add_action_links( $links ) { 
			$action_link = array(
				'<a href="' . admin_url( 'options-general.php?page=pantherius_wp_charts' ) . '">' . __( "Settings", PWPC_CHARTS_TEXT_DOMAIN ) . '</a>',
			);
			return array_merge( $links, $action_link );
		}
	}
}
if( class_exists( 'pantherius_wp_charts' ) ) {
	// call the main class
	$pantherius_wp_charts = pantherius_wp_charts::getInstance();
}
?>