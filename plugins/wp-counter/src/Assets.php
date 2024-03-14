<?php
/**
 * Asset Management
 *
 * @package Haruncpi\WpCounter
 * @author Harun<harun.cox@gmail.com>
 * @link https://learn24bd.com
 * @since 1.2
 */

namespace Haruncpi\WpCounter;

/**
 * Assets Class
 *
 * @since 1.2
 */
class Assets {
	/**
	 * Register hooks.
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
	}

	/**
	 * Load admin scripts.
	 *
	 * @since 1.2
	 *
	 * @return void
	 */
	public function load_admin_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'l24bd_wpcounter_chart_js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js' );
		wp_register_script( 'l24bd_wpcounter_functions', plugins_url( 'js/functions.js', __FILE__ ) );

		wp_enqueue_script( 'l24bd_wpcounter_chart_js' );
		wp_enqueue_script( 'l24bd_wpcounter_functions' );
	}
}
