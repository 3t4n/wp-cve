<?php

if ( class_exists( 'BQW_Hideable_Gallery' ) === false ) {
	class BQW_Hideable_Gallery {

		/**
		 * Current class instance.
		 * 
		 * @since 4.0.0
		 * 
		 * @var object
		 */
		protected static $instance = null;

		/**
		 * Initialize the class
		 *
		 * @since 4.0.0
		 */
		public function __construct() {
			add_filter( 'post_gallery', array( $this, 'overwrite_gallery' ), 10, 2 );
		}

		/**
		 * Return the current class instance.
		 *
		 * @since 4.0.0
		 * 
		 * @return object The instance of the current class.
		 */
		public static function get_instance() {
			if ( self::$instance == null ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Overwrite the gallery shortcode by adding support
		 * for the 'hide' attribute.
		 *
		 * @since 4.0.0
		 *
		 * @param  array $output The initial output of the gallery.
		 * @param  array $atts   The attributes passed to the shortcode.
		 * @return string        The gallery output.
		 */
		public function overwrite_gallery( $output = '', $atts = null ) {
			if ( isset( $atts['hide'] ) && $atts['hide'] === 'true' ) {
				$output = ' ';
			}

			return $output;
		}
	}
}