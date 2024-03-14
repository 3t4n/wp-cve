<?php
/*
Plugin Name: Mobble Shortcodes
Plugin URI: http://philipjohn.co.uk/category/plugins/mobble-shortcodes
Description: Deliver mobile-specific content using the functionality in the Mobble plugin.
Version: 0.2.4
Author: Philip John
Author URI: http://philipjohn.co.uk
License: WTFPL
Text Domain: mobble-shortcodes
GitHub Plugin URI: philipjohn/mobble-shortcodes
*/

/**
 * Mobble_Shortcodes Class
 * Does all the work with nice function names, avoiding conflicts
 * @author philipjohn
 *
 */
class Mobble_Shortcodes {
	
	/**
	 * Constructor for the Class
	 * Calls the activation hook and creates the many shortcodes
	 */
	function __construct() {
		// Add textdomain
		add_action('init', array($this, 'load_textdomain'));
		
		// Call the activation hook
		register_activation_hook( __FILE__, array($this, 'activation') );
		
		// An array of Mobble functions to create shortcodes for
		$mobbles = array(
				'handheld',
				'mobile',
				'tablet',
				'ios',
				'iphone',
				'ipad',
				'ipod',
				'android',
				'blackberry',
				'opera_mobile',
				'symbian',
				'kindle',
				'windows_mobile',
				'motorola',
				'samsung',
				'samsung_tablet',
				'sony_ericsson',
				'nintendo'
				);
		
		// The shortcodes - a positive and negative for each mobble function
		foreach ($mobbles as $mobble){
			add_shortcode('is_'.$mobble, array($this, 'shortcode')); // positive check
			add_shortcode('is_not_'.$mobble, array($this, 'shortcode')); // negative check
		}
		
	}
	
	/**
	 * Our activation hook
	 * Stops folks activating this plugin without having Mobble active
	 */
	function activation() {
		// Let's not let people activate it without Mobble
		if (is_plugin_inactive('mobble/mobble.php'))
			die(__('Ooops, you must have the Mobble plugin installed before you can use this! Please activate Mobble.', 'mobble-shortcodes'));
	}
	
	function load_textdomain(){
		load_plugin_textdomain('mobble-shortcodes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
	}
	
	/**
	 * Process the shortcodes
	 * @param array $atts Any attributes from the shortcode. Not currently used
	 * @param string $content The content used within the shortcode
	 * @param string $tag The shortcode being used (mobble function)
	 * @return string The content
	 */
	function shortcode( $atts, $content, $tag ) {

		// IS or IS NOT?
		if ( strpos( $tag, 'is_not_' ) !== false ) { // not

			$tag = substr( $tag, 7 );

			// Call the Mobble function, look for false
			if ( ! call_user_func( 'is_' . $tag ) )
				return do_shortcode( $content );

			return '';

		}

		else if ( strpos( $tag, 'is_' ) !== false ) { // is

			$tag = substr( $tag, 3 );

			// Call the mobile function, look for true
			if ( call_user_func( 'is_' . $tag ) )
				return do_shortcode( $content );

			return '';

		}

		// Default to assuming no content should be shown just in case
		return '';

	}
}
new Mobble_Shortcodes();