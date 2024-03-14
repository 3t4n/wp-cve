<?php

/**
 * Handles URL events for admin panel
 *
 * @since 1.1.0
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Admin;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Events' ) ) {

	/**
	 * Admin Actions
	 *
	 * @since 1.1.0
	 */
	class Events extends \EasyVideoReviews\Base\Controller {

		/**
		 * Registers all admin menus
		 *
		 * @return void
		 */
		public function register_hooks() {
		}
	}

	// Instantiate.
	Events::init();
}
