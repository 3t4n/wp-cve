<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.ozanwp.com
 * @since      1.0.0
 *
 * @package    Live_Code_Editor
 * @subpackage Live_Code_Editor/includes
 * @author     Ozan Canakli <ozan@ozanwp.com>
 */

class Live_Code_Editor_Public {

	public function __construct() {

		/**
		 * Renders wp_header codes
		 *
		 * @since    1.0.0
		 */
		function head_codes() {

		    $css      = get_option( 'live_code_css_field' );
		    $js       = get_option( 'live_code_js_field' );
		    $header   = get_option( 'live_code_header_field' );

		    // CSS
		    	echo '<style id="live-code-editor-css">' . "\n" . $css . ' '. "\n" .'</style>' . "\n";

		    // JS
		    if( ! empty($js) ) {
		        echo '<script id="live-code-editor-js">' . "\n" . $js . ' '. "\n" .'</script>' . "\n";
		    }

		    // HEADER Code
		    if( ! empty($header) ) {
		        echo $header . "\n";
		    }

		}
		add_action('wp_head', 'head_codes');

		/**
		 * Renders wp_footer codes
		 *
		 * @since    1.0.0
		 */
		function footer_code() {

		    $footer   = get_option( 'live_code_footer_field' );

		    // FOOTER Code
		    if( ! empty($footer) ) {
		        echo $footer . "\n";
		    }
		    
		}
		add_action('wp_footer', 'footer_code');


	}
}
