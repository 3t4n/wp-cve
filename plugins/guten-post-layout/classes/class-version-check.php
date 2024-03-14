<?php
/**
 * GPL Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package gpl
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if( !class_exists('GPL_Version_Check') ){
	class GPL_Version_Check{

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}


		// PHP Error Notice
		public static function php_version_error_notice(){
			/* translators: %s: PHP version */
			$message      = sprintf( esc_html__( 'Guten Post Layout for Gutenberg requires PHP version %s or more.', 'guten-post-layout' ), '5.6' );
			$html_message = sprintf( '<div class="notice notice-error is-dismissible">%s</div>', wpautop( $message ) );
			echo wp_kses_post( $html_message );
		}
		
		// Wordpress Error Notice
		public static function wp_version_error_notice(){
			/* translators: %s: PHP version */
			$message      = sprintf( esc_html__( 'Guten Post Layout for Gutenberg requires WordPress version %s or more.', 'guten-post-layout' ), '4.7' );
			$html_message = sprintf( '<div class="notice notice-error is-dismissible">%s</div>', wpautop( $message ) );
			echo wp_kses_post( $html_message );
		}

	}
}
GPL_Version_Check::get_instance();
