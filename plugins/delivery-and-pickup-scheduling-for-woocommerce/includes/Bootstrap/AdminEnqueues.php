<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://uriahsvictor.com
 * @since      1.0.0
 *
 * @package    Lpac_DPS
 * @author_name    Uriahs Victor <info@soaringleads.com>
 */

namespace Lpac_DPS\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Controllers\Admin\Order_View_Page;

/**
 * Class responsible for loading frontend styles and scripts.
 *
 * @package Lpac_DPS\Bootstrap
 * @since 1.0.0
 */
class AdminEnqueues {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = LPAC_DPS_PLUGIN_NAME;
		$this->version     = LPAC_DPS_VERSION;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'admin/css/lpac-dps-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'notices-css', LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'admin/css/notices.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$path = ( LPAC_DPS_DEBUG ) ? '' : 'build/';
		$time = ( LPAC_DPS_DEBUG ) ? time() : '';

		wp_enqueue_script( $this->plugin_name, LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'admin/js/' . $path . 'lpac-dps-admin.js', array( 'jquery' ), $this->version . $time, false );
		$this->add_inline_scripts();
	}

	/**
	 * Compile our inline scripts that should be available on frontend.
	 *
	 * @return void
	 */
	private function add_inline_scripts(): void {
		$controller_admin_order_edit_page = new Order_View_Page();
		wp_add_inline_script( $this->plugin_name, $controller_admin_order_edit_page->get_js_globals(), 'before' );
	}
}
