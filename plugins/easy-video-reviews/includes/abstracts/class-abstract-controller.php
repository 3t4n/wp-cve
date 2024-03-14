<?php

/**
 * Abstract class for all Easy Video Reviews classes
 * Base Controller
 *
 * @since 1.3.8
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Base;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );

if ( ! class_exists( __NAMESPACE__ . '\Controller' ) ) {

	/**
	 * Abstract class for all Easy Video Reviews classes
	 *
	 * @since 1.3.8
	 */
	abstract class Controller {

		/**
		 * Contains instance of the class
		 *
		 * @var object
		 */
		private static $instance;

		/**
		 * Register hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			$this->add_actions();
			$this->add_filters();
		}

		/**
		 * Add actions
		 *
		 * @return void
		 */
		public function add_actions() {}

		/**
		 * Add filters
		 *
		 * @return void
		 */
		public function add_filters() {}

		/**
		 * Returns the instance.
		 *
		 * @return self
		 */
		public static function get_instance() {
			if ( ! static::$instance ) {
				static::$instance = new static();
			}

			return static::$instance;
		}


		/**
		 * Static init
		 *
		 * @return self
		 */
		public static function init() {

			$instance = new static();
			// Register hooks.
			$instance->register_hooks();

			return $instance;
		}
	}

}
