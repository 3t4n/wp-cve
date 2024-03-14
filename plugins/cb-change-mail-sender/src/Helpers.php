<?php

namespace CBChangeMailSender;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Helpers class.
 *
 * Class with all the misc helper functions.
 *
 * @since 1.3.0
 */
class Helpers {

	/**
	 * Get UTM URL.
	 *
	 * @since 1.3.0
	 *
	 * @param string       $url Base url.
	 * @param array|string $utm Array of UTM params, or if string provided - utm_content URL parameter.
	 *
	 * @return string
	 */
	public static function get_utm_url( $url, $utm ) {

		// Defaults.
		$source   = 'WordPress';
		$medium   = 'plugin';
		$campaign = 'cb-change-mail-sender'; // TODO - Double check if this is the correct slug.
		$content  = 'general';

		if ( is_array( $utm ) ) {

			if ( isset( $utm['source'] ) ) {
				$source = $utm['source'];
			}

			if ( isset( $utm['medium'] ) ) {
				$medium = $utm['medium'];
			}

			if ( isset( $utm['campaign'] ) ) {
				$campaign = $utm['campaign'];
			}

			if ( isset( $utm['content'] ) ) {
				$content = $utm['content'];
			}

		} elseif ( is_string( $utm ) ) {
			$content = $utm;
		}

        $query_args = [
            'utm_source'   => esc_attr( rawurlencode( $source ) ),
            'utm_medium'   => esc_attr( rawurlencode( $medium ) ),
            'utm_campaign' => esc_attr( rawurlencode( $campaign ) ),
        ];

        if ( ! empty( $content ) ) {
            $query_args['utm_content'] = esc_attr( rawurlencode( $content ) );
        }

        return add_query_arg( $query_args, $url );
    }
}
