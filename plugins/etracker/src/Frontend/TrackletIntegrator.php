<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://etracker.com
 * @since      1.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Frontend;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class TrackletIntegrator {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 *
	 * @var string $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 *
	 * @var string $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Instance of TrackletGenerator.
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 *
	 * @var Generator\TrackletGenerator $tracklet    The current version of this plugin.
	 */
	private $tracklet;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 * @param object $tracklet    Instance of TrackletGenerator.
	 */
	public function __construct( $plugin_name, $version, $tracklet ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->tracklet    = $tracklet;
	}

	/**
	 * Output etracker tracklet.
	 *
	 * This method will be called from WordPress action wp_head.
	 */
	public function html_head_code() {
		// Prevent tracking code from clashing with divi builder.
		if ( isset( $_GET['et_fb'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification
			return;
		}
		// render tracklet.
		echo $this->tracklet->generate(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
