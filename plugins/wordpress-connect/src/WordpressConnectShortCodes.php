<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectComments.php' );
require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectLikeButton.php' );

/**
 * Controlls Wordpress Short Codes
 * @since 2.0
 */
class WordpressConnectShortCodes {

	/**
	 * Creates a new WordpressConnectShortCodes object
	 *
	 * @since	2.0
	 *
	 */
	function WordpressConnectShortCodes(){

		$this->addInitHooks();

	}

	/**
	 * Adds init wordpress hook.
	 *
	 * @private
	 * @since	2.0
	 */
	function addInitHooks(){
		add_action( 'init', array( &$this, 'addShortcodes' ) );
	}

	/**
	 * adds shortcodes
	 *
	 * @private
	 * @since	2.0
	 */
	function addShortcodes(){

		add_shortcode( 'wp_connect_comments',  array( &$this, 'shortcodeHandlerForComments' ) );
		add_shortcode( 'wp_connect_like_button',  array( &$this, 'shortcodeHandlerForLikeButton' ) );

	}


	/**
	 * @param array $atts		array of attributes
	 * @param string $content	text within enclosing form of shortcode element
	 * 							- the vaue is ignored in this shortcode
	 *
	 * @see WordpressConnectComments::shortcodeHandler()
	 */
	function shortcodeHandlerForComments( $atts, $content = NULL ){

		return WordpressConnectComments::shortcodeHandler( $atts, $content );

	}

	/**
	 * @param array $atts		array of attributes
	 * @param string $content	text within enclosing form of shortcode element
	 * 							- the vaue is ignored in this shortcode
	 *
	 * @see WordpressConnectLikeButton::shortcodeHandler()
	 */
	function shortcodeHandlerForLikeButton( $atts, $content = NULL ){

		return WordpressConnectLikeButton::shortcodeHandler( $atts, $content );

	}
}
?>