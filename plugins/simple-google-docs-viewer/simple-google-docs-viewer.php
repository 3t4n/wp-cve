<?php
/*
 Plugin Name: Simple Google Docs Viewer
 Plugin URI: http://www.illuminea.com/
 Description: Enables you to easily embed documents with Google Docs Viewer - that are supported by Google Docs (PDF/DOC/DOCX/PPTX/etc).
 Author: illuminea
 Author URI: http://www.illuminea.com
 Version: 1.2
 License: GPL2+
 */

/**
 * Shortcode handler wrapper
 * 
 * @author Maor Chasen <info@illuminea.com>
 */
class Simple_Google_Docs_Viewer {

	/**
	 * @var The real deal
	 */
	private static $instance;

	/**
	 * Setup the plugin. Runs only once.
	 * 
	 * @return Simple_Google_Docs_Viewer the one true instance
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Simple_Google_Docs_Viewer;
			self::$instance->setup();
		}
		return self::$instance;
	}

	/**
	 * A dummy constructor to prevent bbPress from being loaded more than once.
	 *
	 * @see Simple_Google_Docs_Viewer::instance()
	 */
	private function __construct() { /* Do nothing here */ }

	/**
	 * Get things moving
	 *
	 * @uses add_shortcode() for initializing the shortcode
	 */
	private function setup() {
		add_shortcode( 'gviewer', array( $this, 'the_shortcode' ) );
	}

	/**
	 * The actual shortcode.
	 *
	 * @since 1.0
	 * @param  array $atts Shortcode attributes
	 * @param  string $content Not used at this moment
	 * @return mixed The embed HTML on success, null on failure
	 */
	function the_shortcode( $atts, $content = '' ) {
		$content_width 	= isset( $GLOBALS['content_width'] ) ? $GLOBALS['content_width'] : 720;
		$content_height = is_numeric( $content_width ) ? $content_width * 1.2 : 700;

		extract( apply_filters( 'simple_gviewer_atts', shortcode_atts( array(
			'file' => '',
			'width' => (int) $content_width,
			'height' => (int) $content_height,
			'language' => 'en'
		), $atts ) ) );

		if ( '' != ( $file = apply_filters( 'simple_gviewer_file_url', $file ) ) ) {
			$embed_format = '<iframe src="https://docs.google.com/viewer?url=%1$s&embedded=true&hl=%2$s" width="100%%" height="%4$d" style="border: none; max-width:%3$dpx;"></iframe>';
			
			return sprintf( $embed_format, 
				urlencode( esc_url( $file, array( 'http', 'https' ) ) ),
				esc_attr( $language ),
				absint( $width ),
				absint( $height )
			);
		}
		// No file specified, bail.
		return;
	}
}

/**
 * Template tag for using the Google Docs Viewer shortcode.
 *
 * @since 1.0
 * @param  string $file The absolute URL to the document you wish to embed
 * @param  array $args Optional, associative array with shortcode attributes
 * @return string The iframe URL to print in your template files
 */
function simple_gviewer_embed( $file, $args = array() ) {
	global $simple_google_docs_viewer;

	// If file is empty, we have really nothing to show
	if ( '' == $file || ! $simple_google_docs_viewer )
		return;
	
	return $simple_google_docs_viewer->the_shortcode(
		array_merge( $args, array( 'file' => $file ) ) 
	);
}

/**
 * This function is responsible for jump-starting this plugin.
 * Can be also useful for getting the real instance.
 * 
 * @return Simple_Google_Docs_Viewer the one true SGDV instance
 */
function simple_google_docs_viewer() {
	return Simple_Google_Docs_Viewer::instance();
}
add_action( 'plugins_loaded', 'simple_google_docs_viewer' );