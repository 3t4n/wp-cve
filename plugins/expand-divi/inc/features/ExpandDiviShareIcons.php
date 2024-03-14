<?php
/**
 * Expand Divi Social Share
 * adds social share icons to posts
 *
 * @package  ExpandDivi/ExpandDiviShareIcons
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ExpandDiviShareIcons {
	public $options;

	/**
	 * constructor
	 */
	function __construct() {
		$this->options = get_option( 'expand_divi' );
		add_filter( 'the_content', array( $this, 'expand_divi_output_share_icons' ) );	
	}

	/**
	 * append social icons html to the content
	 *
	 * @return string
	 */
	function expand_divi_output_share_icons( $content ) {
		if ( is_singular('post') ) {
			include( EXPAND_DIVI_PATH . 'inc/temp/share_icons.php' );
			return $content . $html;
		} else {
			return $content;
		}
	}
}

new ExpandDiviShareIcons();