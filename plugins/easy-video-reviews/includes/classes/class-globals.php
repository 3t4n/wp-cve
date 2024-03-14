<?php

/**
 * Handles all the global functions for Easy Video Reviews
 *
 * @since 1.3.8
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews;

// Exit if accessed directly.
defined('ABSPATH') || exit(1);


if ( ! class_exists(__NAMESPACE__ . '\Globals') ) {

	/**
	 * Handles all the global functions for Easy Video Reviews
	 */
	class Globals extends \EasyVideoReviews\Base\Controller {

		// Use the trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Stores the instance of the class
		 *
		 * @var object
		 */
		private static $instance;

		/**
		 * Returns the instance of the class
		 *
		 * @return object
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Returns the email append message
		 *
		 * @return string
		 */
		public function get_email_append_message() {
			$email         = $this->option()->get('email_template');
			$button        = $email['button'];
			$recorder_page = $this->option()->get('recording_page_id');

			$url = get_permalink($recorder_page) . '?folder=orders&source=email';

			$message = wp_sprintf( '
			<div style="padding-top:15px; font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;">
				<div style="text-align: center;">
					<div style="text-align:center; padding-top: 8px; margin-top: 5px;">
						<a href="%s" target="_blank" style="background-color: %s; color: %s; font-size: %spx; padding: 10px 12px; border-radius: 5px; text-decoration: none;">
							%s
						</a>
					</div>
				</div>
			</div>', esc_url( $url ), esc_attr( $button['background'] ), esc_attr( $button['color'] ), esc_attr( $button['size'] ), esc_html( $button['text'] ) );

			return apply_filters('evr_thankyou_email_html', $message);
		}
	}
}
