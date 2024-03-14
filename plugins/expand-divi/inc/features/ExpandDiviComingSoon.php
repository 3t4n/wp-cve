<?php
/**
 * Expand Divi Coming Soon
 * Enables the coming soon mode
 *
 * @package  ExpandDivi/ExpandDiviComingSoon
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviComingSoon {
	public $options;

	/**
	 * constructor
	 */
	function __construct() {
		$this->options = get_option( 'expand_divi' );
		add_action( 'template_redirect', array( $this, 'expand_divi_coming_soon' ) );
	}

	/**
	 * enables the coming soon mode
	 *
	 */
	public function expand_divi_coming_soon() {
		// only non-admins will be redirected
		if ( ! current_user_can('manage_options') ) {

			// $coming_soon_page is the id of the coming soon page
			isset( $this->options['coming_soon_page'] ) ? $coming_soon_page = $this->options['coming_soon_page'] : $coming_soon_page = '';

			// no caching should occur
			nocache_headers();		
			if ( ! defined('DONOTCACHEPAGE') ) {
				define( 'DONOTCACHEPAGE', true );
			}
			if ( ! defined( 'DONOTCDN' ) ) {
				define( 'DONOTCDN', true );
			}
			if ( ! defined( 'DONOTCACHEOBJECT' ) ) {
				define( 'DONOTCACHEOBJECT', true );
			}
			if ( ! defined( 'DONOTCACHEDB' ) ) {
				define( 'DONOTCACHEDB', true );
			}

			if ( ! is_page( $coming_soon_page ) ) {
				wp_redirect( get_permalink( $coming_soon_page ) );
				exit();
			} else {
				return false;
			} 
		}
	}
}

new ExpandDiviComingSoon();