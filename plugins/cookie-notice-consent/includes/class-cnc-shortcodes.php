<?php

defined( 'ABSPATH' ) || die();

class Cookie_Notice_Consent_Shortcodes {
	
	/**
	 * Constructor
	 */
	public function __construct( $instance ) {
		$this->cnc = $instance;
		$this->register_shortcodes();
	}
	
	/**
	 * Register shortcodes
	 */
	public function register_shortcodes() {
		if( !shortcode_exists( 'revoke_cookie_consent' ) )
			add_shortcode( 'revoke_cookie_consent', array( $this, 'shortcode_revoke_cookie_consent' ) );
		if( !shortcode_exists( 'cookie_consent_status' ) )
			add_shortcode( 'cookie_consent_status', array( $this, 'shortcode_cookie_consent_status' ) );
	}
	
	/**
	 * Cookie revoke shortcode function
	 */
	public function shortcode_revoke_cookie_consent( $args, $content ) {
		$shortcode = '<a href="#cookies-revoked" id="cookie-notice-consent__revoke-button" class="cookie-notice-consent__revoke-button cookie-notice-consent__button cookie-notice-consent__button--inline" title="' . esc_html( $this->cnc->settings->get_option( 'general_settings', 'revoke_consent_button_label' ) ) . '">' . esc_html( $this->cnc->settings->get_option( 'general_settings', 'revoke_consent_button_label' ) ) . '</a>';
		return wpautop( $shortcode );
	}
	
	/**
	 * Cookie consent status shortcode function
	 */
	public function shortcode_cookie_consent_status( $args, $content ) {
		$status = '<p class="cookie-notice-consent__status">';
		$status .= __( 'Cookie consent status', 'cookie-notice-consent' ) . ': ';
		if( !$this->cnc->helper->is_cookie_consent_set() ) {
			$status .= __( 'No consent given', 'cookie-notice-consent' );
		} else {
			$status .= __( 'Consent given for', 'cookie-notice-consent' ) . ' ';
			$registered_categories = $this->cnc->helper->get_registered_cookie_categories();
			$accepted_categories = array();
			foreach( $registered_categories as $slug => $label ) {
				if( $this->cnc->helper->is_cookie_category_accepted( $slug ) ) {
					$accepted_categories[] = $label;
				}
			}
			if( !empty( $accepted_categories ) ) {
				$status .= '<em>' . implode( '</em>, <em>', $accepted_categories ) . '</em>';
			} else {
				$status .= __( 'no category', 'cookie-notice-consent' );
			}
		}
		$status .= '.</p>';
		return $status;
	}
	
}
