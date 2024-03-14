<?php
/**
 * Main Public Class
 * Enqueue styles and scripts
 * php version 7.2
 *
 * @category   Plugin
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/includes
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 * @license    GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @link       https://hamidrezasepehr.com/
 * @since      2.1.0
 */

/**
 * Wp_Custom_Cursors_Public
 *
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/admin
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 */
class Wp_Custom_Cursors_Public {

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
	 * @since 1.0.0
	 * @param string $plugin_name       The name of the plugin.
	 * @param string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Enqueue styles
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp_custom_cursors_main_style.css', array(), $this->version, 'all' );
	}

	/**
	 * Enqueue scripts
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $wpdb;
		$added_cursors_table   = $wpdb->prefix . 'added_cursors';
		$created_cursors_table = $wpdb->prefix . 'created_cursors';

		$added_cursors_query   = $wpdb->prepare( 'SELECT * FROM %i', $added_cursors_table );
		$created_cursors_query = $wpdb->prepare( 'SELECT * FROM %i', $created_cursors_table );

		$added_cursors   = $wpdb->get_results( $added_cursors_query, ARRAY_A );
		$created_cursors = $wpdb->get_results( $created_cursors_query, ARRAY_A );

		$added_cursors_array   = array();
		$created_cursors_stripped = array();

		if ( is_admin() || is_preview() ) {
			foreach ( $added_cursors as $cursor ) {
				if ( 'on' !== $cursor['hide_admin'] ) {
					array_push( $added_cursors_array, $cursor );
				}
			}
		} else {

			foreach ( $added_cursors as $cursor ) {
				array_push( $added_cursors_array, $cursor );
			}
		}

		foreach ( $created_cursors as $cursor ) {
			$stripped                 = stripslashes( $cursor['cursor_options'] );
			$decoded                  = json_decode( $stripped, false );
			$cursor['cursor_options'] = $decoded;

			$stripped_hover          = stripslashes( $cursor['hover_cursors'] );
			$decoded_hover           = json_decode( $stripped_hover, false );
			$cursor['hover_cursors'] = $decoded_hover;

			array_push( $created_cursors_stripped, $cursor );
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp_custom_cursors_main_script.js', array(), $this->version, true );

		wp_localize_script( $this->plugin_name, 'added_cursors', $added_cursors_array );
		wp_localize_script( $this->plugin_name, 'created_cursors', $created_cursors_stripped );
	}
}
