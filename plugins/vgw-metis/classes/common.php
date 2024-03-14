<?php

namespace WP_VGWORT;

/**
 * Metis Common Class for general CMS independent functions
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Common {
	// amount of free pixels left before we auto-order new ones
	const MIN_PIXEL_THRESHOLD = 5;

	// number of pixels to order on auto-order
	const NUMBER_ORDER_PIXEL = 3;

	// API base URL
	const API_BASE_URL = 'https://tom.vgwort.de';

	// pixel count domain pattern - used for searching pixel in content
	const PIXEL_DOMAIN = 'met.vgwort.de';

	const DEFAULT_TEXT_LENGTH_MIN = 1800;
	const LONG_TEXT_LENGTH_MIN = 10000;
	const TEXT_LIMIT_MINIMUM_PERCENTAGE = 0.5;

	// pixel states
	const STATE_PIXEL_ASSIGNED = 'assigned';
	const STATE_PIXEL_AVAILABLE = 'available';
	const STATE_PIXEL_RESERVED = 'reserved';
	const STATE_PIXEL_DISABLED = 'disabled';

	const STATE_PIXEL_LIMIT_REACHED = 'FULL_LIMIT';
	const STATE_PIXEL_REDUCED_LIMIT_REACHED = 'REDUCED_LIMIT';
	const STATE_PIXEL_LIMIT_NOT_SET = 'NOT_SET';
	const STATE_PIXEL_WITHOUT_LIMIT = 'WITHOUT_LIMIT';

	// text states
	const STATE_MESSAGE_REPORTED = 'reported';
	const STATE_MESSAGE_NOT_REPORTED = 'not_reported';
	const STATE_MESSAGE_NOT_REPORTABLE = 'not_reportable';

	// text types
	const TEXT_TYPE_DEFAULT = 'standard';
	const TEXT_TYPE_LYRIC = 'lyrik';
	const TEXT_TYPE_EMPTY = '-';

	// pixel source types
	const SOURCE_RESTAPI = 'restapi';
	const SOURCE_SCANWORDPRESS = 'scanwordpress';
	const SOURCE_CSVIMPORT = 'csvimport';
	const SOURCE_MANUAL = 'manual';
	const SOURCE_UNKNOWN = 'unknown';

	// default value for auto-add ('yes' or 'no')
	const AUTO_ADD_POSTS_DEFAULT = 'yes';
	const AUTO_ADD_PAGES_DEFAULT = 'yes';

	// api pixel states
	const API_STATE_VALID = 'VALID';
	const API_STATE_NOT_FOUND = 'NOT_FOUND';
	const API_STATE_NOT_OWNER = 'NOT_OWNER';
	const API_STATE_NOT_VALID = 'NOT_VALID';

	// std functions
	const INVOLVEMENT_AUTHOR = 'AUTHOR';
	const INVOLVEMENT_PUBLISHER = 'PUBLISHER';
	const INVOLVEMENT_TRANSLATOR = 'TRANSLATOR';
	const INVOLVEMENT_NONE = 'NO_PARTICIPATION';


	/**
	 * Generates the image pixel html with given domain and public id
	 *
	 * @param string $domain
	 * @param string $public_identification_id
	 * @param bool $lazy_loading
	 *
	 * @return string pixel
	 */
	public static function generate_pixel_html_image( string $domain, string $public_identification_id, bool $lazy_loading = false ): string {
		if ( ! $lazy_loading ) {
			return '<img id="metis-img-pixel" class="skip-lazy"  loading="eager" src="https://' . esc_html( $domain ) . '/na/' . esc_html( $public_identification_id ) . '" width="1" height="1" alt="">';
		} else {
			return '<img id="metis-img-pixel" src="https://' . esc_html( $domain ) . '/na/' . esc_html( $public_identification_id ) . '" width="1" height="1" alt="">';
		}

	}

	/**
	 * Generates a debug message for pixel
	 *
	 * @param string $domain
	 * @param string $public_identification_id
	 * @param bool $lazy_loading
	 *
	 * @return string
	 */
	public static function generate_pixel_html_image_debug( string $domain, string $public_identification_id, bool $lazy_loading = false ): string {
		if ( ! $lazy_loading ) {
			return 'DEBUG : (no lazy loading) Metis pixel added https://' . esc_html( $domain ) . '/na/' . esc_html( $public_identification_id );
		} else {
			return 'DEBUG : (mit lazy loading) Metis pixel added https://' . esc_html( $domain ) . '/na/' . esc_html( $public_identification_id );
		}
	}

	/**
	 * checks if a public or private identification code is of valid format
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	public static function is_valid_pixel_id_format( string $id ): bool {
		return preg_match( '/^[a-fA-F0-9]{32,100}$/', $id ) === 1;
	}

	/**
	 * check if a string is a valid date
	 *
	 * @param string $date   the date string
	 * @param string $format the format string according to DateTime Class
	 *
	 * @return bool
	 */
	public static function is_valid_date( string $date, string $format = null ): bool {
		if ( empty( $format ) ) {
			if ( strlen( $date ) === 19 ) {
				$format = 'Y-m-d H:i:s';
			} else {
				$format = 'Y-m-d';
			}
		}

		$d = \DateTime::createFromFormat( $format, $date );

		return $d && $d->format( $format ) === $date;
	}

	/**
	 * get the message state of a Pixel or an array of db result pixels
	 *
	 * @param Pixel $pixel
	 *
	 * @return string
	 */
	public static function get_text_message_state( Pixel $pixel ): string {
		$state = Common::STATE_MESSAGE_NOT_REPORTABLE;

		// if there is a message date, status is message reported
		if ( ! empty( $pixel->message_created_at ) && Common::is_valid_date( $pixel->get_message_created_at_as_string() ) ) {
			$state = Common::STATE_MESSAGE_REPORTED;
			// if there is no pixel ownership > status is not reportable
		} else if ( $pixel->ownership ) {
			$reduced_limit_reached = false;
			$limit_reached         = false;
			$years                 = $pixel->min_hits;
			// loop through years of limits and check status according to limit reached / text type / text length
			if ( count( $years ) ) {
				foreach ( $years as $year ) {
					if ( $year->type === Common::STATE_PIXEL_REDUCED_LIMIT_REACHED ) {
						$reduced_limit_reached = true;
					}

					if ( $year->type === Common::STATE_PIXEL_LIMIT_REACHED ) {
						$limit_reached = true;
					}
				}
			}

			if (
				( $pixel->text_type === Common::TEXT_TYPE_DEFAULT && $reduced_limit_reached && $pixel->text_length >= Common::LONG_TEXT_LENGTH_MIN ) ||
				( $pixel->text_type === Common::TEXT_TYPE_DEFAULT && $limit_reached && $pixel->text_length >= Common::DEFAULT_TEXT_LENGTH_MIN ) ||
				( $pixel->text_type === Common::TEXT_TYPE_LYRIC && $limit_reached && $pixel->text_length >= 1 )
			) {
				$state = Common::STATE_MESSAGE_NOT_REPORTED;
			}
		}

		return $state;
	}

	/**
	 * sanitizes text type
	 *
	 * @param mixed $text_type
	 *
	 * @return string returns text type or "-" on error
	 */
	public static function sanitize_text_type( mixed $text_type ): string {
		if ( ! is_string( $text_type ) ) {
			return Common::TEXT_TYPE_EMPTY;
		}

		return match ( $text_type ) {
			Common::TEXT_TYPE_DEFAULT, Common::TEXT_TYPE_LYRIC => $text_type,
			default => Common::TEXT_TYPE_EMPTY,
		};
	}
}