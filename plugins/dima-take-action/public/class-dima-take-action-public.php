<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://pixeldima.com
 * @since      1.0.0
 *
 * @package    Dima_Take_Action
 * @subpackage Dima_Take_Action/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dima_Take_Action
 * @subpackage Dima_Take_Action/public
 * @author     Your Name <email@example.com>
 */
class Dima_Take_Action_Public extends PixelDima_Base {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;


	/**
	 * Markup Loaded variable
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var [type]
	 */
	private $markupLoaded;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'wp_head', array( &$this, 'write_markup' ) );

		$this->markupLoaded = false;
	}

	/**
	 * writes the html and script for the bar
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function write_markup() {
		if ( $this->markupLoaded ) {
			return;
		}

		include( $this->pluginDIRRoot . 'partials/dima-take-action-public-display.php' );

		$this->markupLoaded = true;


	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dima_Take_Action_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dima_Take_Action_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dima-take-action-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'dima_ta_banner_js', plugin_dir_url( __FILE__ ) . 'js/dima-take-action-public.js', array( 'jquery' ), $this->version, false );

		//====================
		// button
		//====================
		global $dima_ta_demo;
		if ( sizeof( $dima_ta_demo ) == 0 ) {
			return;
		}
		$campaignName = $dima_ta_demo['dima-ta-banner-campaign-name'];
		$campaignN    = $dima_ta_demo['dima-ta-banner-campaign-id'];
		$campaignID   = $campaignN . '-' . $campaignName;
		$dataCacheKey = 'banner_' . $campaignID . '_cache';

		$banner_enabled    = $dima_ta_demo['dima-ta-banner-enabled'];
		$float_btn_enabled = $dima_ta_demo['dima-ta-float-button-enabled'];
		$img_url           = '';
		$float_btn_url     = $dima_ta_demo['dima-ta-float-button-url'];
		$float_btn_txt     = $dima_ta_demo['dima-ta-float-button-txt'];
		$banner_pos        = $dima_ta_demo['dima-ta-banner-pos'];
		$close_active      = $dima_ta_demo['dima-ta-use-close'];
		$btn_active        = $dima_ta_demo['dima-ta-use-button'];
		$banner_txt        = $dima_ta_demo['dima-ta-banner-msg'];
		$btn_txt           = $dima_ta_demo['dima-ta-button-txt'];
		$btn_url           = $dima_ta_demo['dima-ta-button-url'];
		$btn_target        = $dima_ta_demo['dima-ta-button-target'];
		$btn_float_target  = $dima_ta_demo['dima-ta-float-button-target'];
		$banner_on_mobile  = $dima_ta_demo['dima-ta-use-banner-mobile'];
		$mobile_txt        = $dima_ta_demo['dima-ta-banner-mobile-msg'];
		$mobile_url        = $dima_ta_demo['dima-ta-banner-mobile-url'];

		if ( isset( $dima_ta_demo['dima-ta-float-button-logo']['url'] ) ) {
			$img_url = $dima_ta_demo['dima-ta-float-button-logo']['url'];
		}
		$class = '';
		if ( $banner_pos == 'buttom' ) {
			$class .= ' take-action-on-buttom';
		}
		if ( ! $close_active ) {
			$class .= ' take-action-no-close';
		}
		if ( ! $btn_active ) {
			$class .= ' take-action-no-button';
		}

		$dima_ta_banner = array(
			'banner_enabled'    => $banner_enabled,
			'float_btn_enabled' => $float_btn_enabled,
			'class'             => $class,
			'img_url'           => $img_url,
			'float_btn_url'     => $float_btn_url,
			'float_btn_txt'     => $float_btn_txt,
			'dataCacheKey'      => $dataCacheKey,
			'banner_txt'        => $banner_txt,
			'close_active'      => $close_active,
			'btn_active'        => $btn_active,
			'btn_txt'           => $btn_txt,
			'btn_url'           => $btn_url,
			'btn_target'        => $btn_target,
			'btn_float_target'  => $btn_float_target,
			'banner_on_mobile'  => $banner_on_mobile,
			'mobile_txt'        => $mobile_txt,
			'mobile_url'        => $mobile_url,
		);
		// sending the options to the js file
		wp_localize_script( 'dima_ta_banner_js', 'dima_ta_banner_name', $dima_ta_banner );

	}

}
