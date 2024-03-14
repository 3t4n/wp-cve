<?php
/**
 * The plugin shortcode handler class.
 *
 * Register and work with QR code shortcode
 *
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WQM_Shortcode' ) ) {

	class WQM_Shortcode {

		/**
		 * Shortcode name
		 */
		const SHORTCODE_NAME = 'qr-code-contact-information-card';

		/**
		 * Render and show shortcode content
		 *
		 * @param $attributes
		 *
		 * @return mixed|string|void
		 */
		public static function add_shortcode( $attributes ) {
			$attributes = apply_filters( self::SHORTCODE_NAME . '-attributes', $attributes );

			if ( empty( $attributes['card_id'] ) ) {
				return '';
			}

			$card_id = self::validate_attributes( $attributes );

			$qr_code = get_the_post_thumbnail_url( $card_id, 'full' );

			return apply_filters( self::SHORTCODE_NAME . '-render', WQM_Common::render( 'shortcode.php', array(
				'card_id' => $card_id,
				'qr_code' => $qr_code
			) ) );
		}

		/**
		 * Validate and check security of attributes
		 *
		 * @param $attributes
		 *
		 * @return mixed
		 */
		private static function validate_attributes( $attributes ) {

			$card_id = preg_replace( '@[^\d]+@si', '', $attributes['card_id'] );

			return ! empty( $card_id ) ? $card_id : false;
		}
	}
}