<?php

/**
 * Class EU_COOKIES_BAR_Frontend_Frontend
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EU_COOKIES_BAR_Frontend_Shortcode {
	protected $settings;
	protected $data;

	public function __construct() {
		add_action( 'init', array( $this, 'shortcode_init' ) );
	}
	public function shortcode_init() {
		add_shortcode( 'eucookiesbar_settings', array( $this, 'register_shortcode' ) );
	}
	public function register_shortcode( $atts,$content ) {
		return '<span class="eu-cookies-bar-cookies-bar-button eu-cookies-bar-cookies-bar-button-settings">'.$content.'</span>';
	}
}