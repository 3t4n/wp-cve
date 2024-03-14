<?php
/**
 * Media Library Enable Infinite Scrolling
 *
 * @package media-library-enable-infinite-scrolling
 * @author David Baumwald <david@dream-encode.com>
 * @license MIT
 */

/**
 * Plugin Name: Media Library Enable Infinite Scrolling
 * Author: David Baumwald
 * Description: A small plugin to re-enable infinite scrolling in the Media Library after WordPress 5.8.
 * Version: 0.1.0
 * Network: true
 * License: MIT
 * Text Domain: media-library-enable-infinite-scrolling
 * Requires PHP: 7.0
 * Requires at least: 5.7
 * GitHub Plugin URI: https://github.com/dream-encode/media-library-enable-infinite-scrolling
 * Primary Branch: main
 */

 namespace DreamEncode;

 // Bail early if accessed directly around WP.
 defined( 'ABSPATH' ) || die( 'We\'re sorry, but you can not directly access this file.' );

 // Define the current plugin version.
 define( 'DE_MEDIA_LIBRARY_ENABLE_INFINITE_SCROLLING_VERSION', '0.1.0' );

/**
 * Class Media_Library_Enable_Infinite_Scrolling.
 *
 * A small plugin to re-enable infinite scrolling in the Media Library after WordPress 5.7.
 */
class Media_Library_Enable_Infinite_Scrolling {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->add_hooks();
	}

	/**
	 * Add our custom filters and actions.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function add_hooks() {
		add_filter( 'media_library_infinite_scrolling', '__return_true' );
	}
}

new Media_Library_Enable_Infinite_Scrolling();