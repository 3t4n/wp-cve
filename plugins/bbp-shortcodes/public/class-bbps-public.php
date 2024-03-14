<?php
/**
 * This class defines all plugin functionality for the site front.
 *
 * @package BBPS
 * @since    1.0
 */

if ( ! class_exists( 'BBPS_Public' ) ) {

	class BBPS_Public {

		/**
		 * Stores plugin options.
		 */
		public $opt;

		/**
		 * Core singleton class
		 * @var self
		 */
		private static $_instance;

		/**
		 * Initializes this class and stores the plugin options.
		 */
		public function __construct() {
			$bbps = BBPress_Shortcodes::getInstance();
			$this->opt = ( null !== $bbps ) ? $bbps->opt : get_option( 'bbpress_shortcodes' );
		}

		/**
		 * Gets the instance of this class.
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( ! ( self::$_instance instanceof self ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Parses short codes placed in topic and reply content.
		 */
		function do_bbp_shortcodes( $content, $reply_id ) {

			$reply_author = bbp_get_reply_author_id( $reply_id );

			if ( user_can( $reply_author, $this->bbps_parse_capability() ) ) {
				return do_shortcode( $content );
			}

			return $content;
		}

		/**
		 * Checks capability to parse short codes placed in topic and reply content.
		 */
		function bbps_parse_capability() {
			return apply_filters( 'bbps_parse_shortcodes_cap', 'publish_forums' );
		}
	}
}
