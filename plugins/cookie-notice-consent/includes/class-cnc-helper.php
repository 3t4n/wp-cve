<?php

defined( 'ABSPATH' ) || die();

class Cookie_Notice_Consent_Helper {
	
	/**
	 * Constructor
	 */
	public function __construct( $instance ) {
		$this->cnc = $instance;
	}
	
	/**
	 * Check if cookies are accepted for given category
	 */
	public static function is_cookie_category_accepted( $category ) {
		// Bail early if cookie isn't set
		if( !isset( $_COOKIE['cookie_consent'] ) )
			return false;
		// Add prefix if not present
		$category = ( 0 !== strpos( $category, 'category_' ) ) ? ( 'category_' . $category ) : $category;
		// Decode cookie data
		$cookieData = json_decode( stripslashes( $_COOKIE['cookie_consent'] ) );
		// Bail if json_decode was not successful
		if( null === $cookieData )
			return false;
		// See if requested category is set
		$result = isset( $cookieData->cookie_categories ) && in_array( $category, $cookieData->cookie_categories ) == '1' ? true : false;
		// Return result
		return apply_filters( 'cookie_notice_consent_is_cookie_category_accepted', $result, $category );
	}
	
	/**
	 * Check if cookies are set
	 */
	public static function is_cookie_consent_set() {
		return apply_filters( 'cookie_notice_consent_is_cookie_consent_set', isset( $_COOKIE['cookie_consent'] ) );
	}
	
	/**
	 * Get settings option groups with labels
	 */
	public function get_option_groups() {
		return array_merge(
			array(
				'general_settings'	=> __( 'General Settings', 'cookie-notice-consent' ),
				'design_settings'	=> __( 'Design Settings', 'cookie-notice-consent' ),
			),
			$this->get_registered_cookie_categories(),
			array(
				'consent_settings'	=> __( 'Consent Settings', 'cookie-notice-consent' ),
			)
		);
	}
	
	/**
	 * Get registered cookie categories with labels
	 */
	public function get_registered_cookie_categories() {
		return array(
			'category_essential'	=> __( 'Essential Cookies', 'cookie-notice-consent' ),
			'category_functional'	=> __( 'Functional Cookies', 'cookie-notice-consent' ),
			'category_marketing'	=> __( 'Marketing Cookies', 'cookie-notice-consent' ),
		);
	}
	
	/**
	 * Get activated categories
	 */
	public function get_active_cookie_categories() {
		// Get registered categories
		$registered_categories = array_keys( $this->get_registered_cookie_categories() );
		$active_categories = array();
		// Loop through registered categories
		foreach( $registered_categories as $category ) {
			// See if category is active
			if( $this->cnc->settings->get_option( $category, 'active' ) )
				$active_categories[] = $category;
		}
		// Return result
		return $active_categories;
	}
	
	/**
	 * Print out logged categories for given post_id in prettified format
	 */
	public function pretty_print_logged_categories( $post_id ) {
		if( is_array( $meta = unserialize( get_post_meta( $post_id , 'categories' , true ) ) ) )
			echo ucwords( str_replace( 'category_', '', implode( ', ', $meta ) ) );
	}
	
	/**
	 * Get registered themes with labels
	 */
	public function get_registered_themes() {
		return array(
			'default'	=> __( 'Default / None', 'cookie-notice-consent' ),
			'labs'		=> __( 'Labs', 'cookie-notice-consent' ),
			'sidecar'	=> __( 'Sidecar', 'cookie-notice-consent' ),
			'lowkey'	=> __( 'Low-key', 'cookie-notice-consent' ),
		);
	}
	
	/**
	 * Check whether a certain plugin is active
	 * Returns true if any plugin in the given array is active
	 */
	public function is_plugin_active( $filenames ) {
		foreach( is_array( $filenames ) ? $filenames : (array)$filenames as $filename ) {
			if( in_array( $filename, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
				return true;
		}
		return false;
	}
	
	/**
	 * Check whether WordPress is running with a cache
	 */
	public function is_cache_active() {
		return ( defined( 'WP_CACHE' ) && WP_CACHE );
	}
	
	/**
	 * Get allowed code tags
	 */
	public function get_allowed_html() {
		return apply_filters(
			'cookie_notice_consent_allowed_html',
			array_merge(
				wp_kses_allowed_html( 'post' ),
				array(
					'script' => array(
						'type' => array(),
						'src' => array(),
						'charset' => array(),
						'async' => array()
					),
					'noscript' => array(),
					'style' => array(
						'type' => array()
					),
					'iframe' => array(
						'src' => array(),
						'height' => array(),
						'width' => array(),
						'frameborder' => array(),
						'allowfullscreen' => array()
					)
				)
			)
		);
	}
	
}
