<?php
/**
 * Plugin Name: Restrict Elementor Widgets
 * Description: Restrict Elementor Widgets based on different conditions. Works for any widgets from any plugins.
 * Plugin URI: https://codexpert.io/product/restrict-elementor-widgets/?utm_campaign=plugin-uri
 * Author: Codexpert
 * Author URI: https://codexpert.io/?utm_campaign=author-uri
 * Version: 1.4
 * Text Domain: restrict-elementor-widgets
 * Domain Path: /languages
 *
 * Restrict Elementor Widgets is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Restrict Elementor Widgets is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

namespace Codexpert\Restrict_Elementor_Widgets;
use Codexpert\Plugin\Notice;
use Pluggable\Marketing\Survey;
use Pluggable\Marketing\Feature;
use Pluggable\Marketing\Deactivator;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class for the plugin
 * @package Plugin
 * @author codexpert <hello@codexpert.io>
 */
final class Plugin {
	
	/**
	 * Plugin instance
	 * 
	 * @return Plugin
	 */
	public static $_instance;

	/**
	 * The constructor method
	 * 
	 * @since 0.9
	 */
	private function __construct() {

		/**
		 * Includes required files
		 */
		$this->include();

		/**
		 * Defines contants
		 */
		$this->define();

		/**
		 * Run actual hooks
		 */
		$this->hook();
	}

	/**
	 * Includes files
	 */
	public function include() {
		require_once( dirname( __FILE__ ) . '/functions/helpers.php' );
		require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
	}

	/**
	 * Define variables and constants
	 */
	public function define() {
		// constants
		define( 'REW', __FILE__ );
		define( 'REW_DIR', dirname( REW ) );
		define( 'REW_DEBUG', true );

		// plugin data
		$this->plugin				= get_plugin_data( REW );
		$this->plugin['basename']	= plugin_basename( REW );
		$this->plugin['file']		= REW;
		$this->plugin['server']		= apply_filters( 'restrict-elementor-widgets_server', 'https://my.pluggable.io' );
		$this->plugin['min_php']	= '5.6';
		$this->plugin['min_wp']		= '4.0';
		$this->plugin['depends']	= [ 'elementor/elementor.php' => 'Elementor' ];
		$this->plugin['doc_id']		= 11269;
	}

	/**
	 * Hooks
	 */
	public function hook() {

		if( is_admin() ) :

			/**
			 * Admin facing hooks
			 *
			 * To add an action, use $admin->action()
			 * To apply a filter, use $admin->filter()
			 */
			$admin = new Admin( $this->plugin );
			$admin->activate( 'install' );
			$admin->action( 'restrict-elementor-widgets_daily', 'sync_docs' );
			$admin->action( 'plugins_loaded', 'i18n' );
			$admin->action( 'wp_dashboard_setup', 'dashboard_widget', 99 );
			$admin->action( 'admin_enqueue_scripts', 'enqueue_scripts' );
			$admin->filter( "plugin_action_links_{$this->plugin['basename']}", 'action_links' );
			$admin->filter( 'plugin_row_meta', 'plugin_row_meta', 10, 2 );
			$admin->action( 'admin_footer_text', 'footer_text', 99 );
			$admin->action( 'elementor/controls/controls_registered', 'register_controls' );
			$admin->action( 'elementor/element/common/_section_style/before_section_start', 'register_control_section' );
			$admin->action( 'elementor/element/column/layout/after_section_end', 'register_control_section' );
			$admin->action( 'elementor/element/section/section_typo/after_section_end', 'register_control_section' );
			$admin->action( 'elementor/element/common/rew_control_section/before_section_end', 'control_actions', 10, 2 );
			$admin->action( 'elementor/element/column/rew_control_section/before_section_end', 'control_actions', 10, 2 );
			$admin->action( 'elementor/element/section/rew_control_section/before_section_end', 'control_actions', 10, 2 );

			/**
			 * Settings related hooks
			 *
			 * To add an action, use $settings->action()
			 * To apply a filter, use $settings->filter()
			 */
			$settings = new Settings( $this->plugin );
			$settings->action( 'plugins_loaded', 'init_menu' );
			$settings->action( 'cx-settings-before-form', 'tab_content' );

			// Product related classes
			$notice			= new Notice( $this->plugin );
			$survey			= new Survey( REW );
			$feature		= new Feature( REW );
			$deeactivator	= new Deactivator( REW );

		else : // ! is_admin() ?

			/**
			 * Front facing hooks
			 *
			 * To add an action, use $front->action()
			 * To apply a filter, use $front->filter()
			 */
			$front = new Front( $this->plugin );
			$front->action( 'wp_enqueue_scripts', 'enqueue_scripts' );
			$front->filter( 'elementor/frontend/section/should_render', 'restrict_render_section', 10, 2 );
			$front->filter( 'elementor/frontend/column/should_render', 'restrict_render_section', 10, 2 );
			$front->filter( 'elementor/widget/render_content', 'restrict_render_widgets', 10, 2 );

		endif;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() { }

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() { }

	/**
	 * Instantiate the plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

Plugin::instance();