<?php

namespace WP_VGWORT;

use DateTime;

/**
 * Metis Pixel Model
 *
 * Pixel data structure with getters and setters
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Pixel {
	// all Pixel DB columns
	/**
	 * @var string the pixels public identification id
	 */
	public string $public_identification_id = '';

	/**
	 * @var string | null the pixels private identification id
	 */
	public string|null $private_identification_id = null;

	/**
	 * @var bool count started info
	 */
	public bool $count_started = false;

	/**
	 * @var string | null pixel counter domain
	 */
	public string|null $domain = null;

	/**
	 * @var DateTime | null order date
	 */
	public DateTime|null $ordered_at = null;

	/**
	 * @var array array of min hits (year, type) of the last 3 years
	 */
	public array $min_hits = [];

	/**
	 * @var string | null creation source: api, csv import, manual, unknown
	 */
	public string|null $source = null;

	/**
	 * @var bool active status (inactive pixel means: pixel appears as free but still has post<>pixel connection, so on
	 *      next assignment gets same pixel)
	 */
	public bool $active = true;

	/**
	 * @var bool owned = true, not owned = false
	 */
	public bool $ownership = true;

	/**
	 * @var DateTime|null message creation date
	 */
	public DateTime|null $message_created_at = null;

	/**
	 * @var string|null state
	 */
	public string|null $state = null;

	/**
	 * @var string|null state
	 */
	public string|null $text_type = null;

	/**
	 * @var int|null state
	 */
	public int|null $text_length = null;

	/**
	 * Constructor
	 *
	 * @var object | array | null $pixel pixel object from db or api
	 */
	public function __construct( object| array | null $pixel = null ) {
		if(is_array($pixel)) {
			$this->hydrate_from_array($pixel);
		}

		if(is_object($pixel)) {
			$this->hydrate_from_object($pixel);
		}
	}

	/**
	 * Hydrate the Pixel object with data from an assoc array
	 *
	 * @param array $pixel an assoc array with data of one pixel
	 *
	 * @return void
	 */
	public function hydrate_from_array( array $pixel): void {
		// public identification id DB version
		if ( isset( $pixel['public_identification_id'] ) ) {
			$this->set_public_identification_id($pixel['public_identification_id'] );
		}

		// public identification id API version
		if ( isset( $pixel['publicUID'] ) ) {
			$this->set_public_identification_id( $pixel['publicUID'] );
		}

		// public identification id API version 2
		if ( isset( $pixel['publicIdentificationId'] ) ) {
			$this->set_public_identification_id( $pixel['publicIdentificationId'] );
		}

		// private identification id DB version
		if ( isset( $pixel['private_identification_id'] ) ) {
			$this->set_private_identification_id( $pixel['private_identification_id'] );
		}

		// private identification id API version
		if ( isset( $pixel['privateUID'] ) ) {
			$this->set_private_identification_id( $pixel['privateUID'] );
		}

		// private identification id API version 2
		if ( isset( $pixel->privateIdentificationId ) ) {
			$this->set_private_identification_id( $pixel['privateIdentificationId'] );
		}

		// count started DB version
		if ( isset( $pixel['count_started'] ) ) {
			$this->set_count_started( $pixel['count_started'] );
		}

		// count started API version
		if ( isset( $pixel['countStarted'] ) ) {
			$this->set_count_started( $pixel['countStarted'] );
		}

		// domain DB and API version
		if ( isset( $pixel['domain'] ) ) {
			$this->set_domain( $pixel['domain'] );
		}

		// ordered date DB version
		if ( isset( $pixel['ordered_at'] ) ) {
			$this->set_ordered_at( $pixel['ordered_at'] );
		}

		// ordered date API version
		if ( isset( $pixel['orderDate'] ) ) {
			$this->set_ordered_at( $pixel['orderDate'] );
		}

		// ordered datetime API version
		if ( isset( $pixel['orderDateTime'] ) ) {
			$this->set_ordered_at( $pixel['orderDateTime'] );
		}

		// yearly limits DB version
		if ( !empty($pixel['min_hits']) ) {
			$this->set_min_hits( $pixel['min_hits'] );
		}

		// yearly limits API version
		if ( !empty($pixel['limitsInYear']) ) {
			$this->set_min_hits( $pixel['limitsInYear'] );
		}

		// source DB and API version
		if ( isset( $pixel['source'] ) ) {
			$this->set_source( $pixel['source'] );
		}

		// active DB and API version
		if ( isset( $pixel['active'] ) ) {
			$this->set_active( $pixel['active'] );
		}

		// ownership DB and API version
		if ( isset( $pixel['ownership'] ) ) {
			$this->set_ownership( $pixel['ownership'] );
		}

		// message created date DB version
		if ( ! empty( $pixel['message_created_at'] ) ) {
			$this->set_message_created_at( $pixel['message_created_at'] );
		}

		// message created date API version
		if ( ! empty( $pixel['messageCreatedDate'] ) ) {
			$this->set_message_created_at( $pixel['messageCreatedDate'] );
		}

		// state API version
		if ( isset( $pixel['state'] ) ) {
			$this->set_state( $pixel['state'] );
		}

		// text type
		if( ! empty($pixel['text_type'])) {
			$this->set_text_type($pixel['text_type']);
		}

		// text length
		if( ! empty($pixel['text_length'])) {
			$this->set_text_length($pixel['text_length']);
		}

	}

	/**
	 * Hydrate the Pixel object with data from an object
	 *
	 * @param object $pixel pixel object data
	 *
	 * @return void
	 */
	public function hydrate_from_object( object $pixel): void {
		// public identification id DB version
		if ( isset( $pixel->public_identification_id ) ) {
			$this->set_public_identification_id( $pixel->public_identification_id );
		}

		// public identification id API version
		if ( isset( $pixel->publicUID ) ) {
			$this->set_public_identification_id( $pixel->publicUID );
		}

		// public identification id API version 2
		if ( isset( $pixel->publicIdentificationId ) ) {
			$this->set_public_identification_id( $pixel->publicIdentificationId );
		}

		// private identification id DB version
		if ( isset( $pixel->private_identification_id ) ) {
			$this->set_private_identification_id( $pixel->private_identification_id );
		}

		// private identification id API version
		if ( isset( $pixel->privateUID ) ) {
			$this->set_private_identification_id( $pixel->privateUID );
		}

		// private identification id API version 2
		if ( isset( $pixel->privateIdentificationId ) ) {
			$this->set_private_identification_id( $pixel->privateIdentificationId );
		}

		// count started DB version
		if ( isset( $pixel->count_started ) ) {
			$this->set_count_started( $pixel->count_started );
		}

		// count started API version
		if ( isset( $pixel->countStarted ) ) {
			$this->set_count_started( $pixel->countStarted );
		}

		// domain DB and API version
		if ( isset( $pixel->domain ) ) {
			$this->set_domain( $pixel->domain );
		}

		// ordered date DB version
		if ( isset( $pixel->ordered_at ) ) {
			$this->set_ordered_at( $pixel->ordered_at );
		}

		// ordered date API version
		if ( isset( $pixel->orderDate ) ) {
			$this->set_ordered_at( $pixel->orderDate );
		}

		// ordered datetime API version
		if ( isset( $pixel->orderDateTime ) ) {
			$this->set_ordered_at( $pixel->orderDateTime );
		}

		// yearly limits DB version
		if ( !empty($pixel->min_hits) ) {
			$this->set_min_hits( $pixel->min_hits );
		}

		// yearly limits API version
		if ( !empty($pixel->limitsInYear) ) {
			$this->set_min_hits( $pixel->limitsInYear );
		}

		// source DB and API version
		if ( isset( $pixel->source ) ) {
			$this->set_source( $pixel->source );
		}

		// active DB and API version
		if ( isset( $pixel->active ) ) {
			$this->set_active( $pixel->active );
		}

		// ownership DB and API version
		if ( isset( $pixel->ownership ) ) {
			$this->set_ownership( $pixel->ownership );
		}

		// message created date DB version
		if ( ! empty( $pixel->message_created_at ) ) {
			$this->set_message_created_at( $pixel->message_created_at );
		}

		// message created date API version
		if ( ! empty( $pixel->messageCreatedDate ) ) {
			$this->set_message_created_at( $pixel->messageCreatedDate );
		}

		// state API version
		if ( isset( $pixel->state ) ) {
			$this->set_state( $pixel->state );
		}

		// text type
		if( ! empty($pixel->text_type)) {
			$this->set_text_type($pixel->text_type);
		}

		// text length
		if( ! empty($pixel->text_length)) {
			$this->set_text_length($pixel->text_length);
		}
	}

	/**
	 * getter public_identification_id
	 *
	 * @return string
	 */
	public function get_public_identification_id(): string {
		return $this->public_identification_id;
	}

	/**
	 * setter public_identification_id
	 *
	 * @param string $public_identification_id
	 *
	 * @return void
	 */
	public function set_public_identification_id( string $public_identification_id ): void {
		$this->public_identification_id = $public_identification_id;
	}

	/**
	 * getter private identification id
	 *
	 * @return string | null
	 */
	public function get_private_identification_id(): string | null {
		return $this->private_identification_id;
	}

	/**
	 * setter private identification id
	 *
	 * @param string|null $private_identification_id
	 *
	 * @return void
	 */
	public function set_private_identification_id( string|null $private_identification_id ): void {
		$this->private_identification_id = $private_identification_id ?: '-';
	}

	/**
	 * getter count started
	 *
	 * @return bool
	 */
	public function get_count_started(): bool {
		return $this->count_started;
	}

	/**
	 * setter count started
	 *
	 * @param bool $count_started
	 *
	 * @return void
	 */
	public function set_count_started( bool $count_started ): void {
		$this->count_started = $count_started;
	}

	/**
	 * getter domain
	 *
	 * @return string
	 */
	public function get_domain(): string {
		return $this->domain;
	}

	/**
	 * setter domain
	 *
	 * @param string $domain
	 */
	public function set_domain( string $domain ): void {
		$this->domain = $domain;
	}


	/**
	 * getter ordered at
	 *
	 * @return DateTime
	 */
	public function get_ordered_at(): DateTime {
		return $this->ordered_at;
	}

	/**
	 * getter for ordered_at
	 *
	 * @return string | null
	 */
	public function get_ordered_at_as_string(): string|null {
		return $this->ordered_at?->format( 'Y-m-d H:i:s' );
	}

	/**
	 * setter ordered at
	 *
	 * @param DateTime | string | null $ordered_at
	 *
	 * @return void
	 */
	public function set_ordered_at( DateTime|string|null $ordered_at ): void {
		switch ( gettype( $ordered_at ) ) {
			case 'string':
				$this->ordered_at = new \DateTime( $ordered_at );
				break;
			case 'object':
				if ( get_class( $ordered_at ) == 'DateTime' ) {
					$this->ordered_at = $ordered_at;
				}
				break;
			default:
				$this->ordered_at = null;
		}
	}

	/**
	 * getter min hits
	 *
	 * @return array
	 */
	public function get_min_hits(): array {
		return $this->min_hits;
	}

	/**
	 * setter min hits
	 *
	 * @param string | array $min_hits
	 *
	 * @return void
	 */
	public function set_min_hits( string|array $min_hits ): void {
		if ( is_array( $min_hits ) ) {
			$this->min_hits = $min_hits;
		} else if ( is_string( $min_hits ) ) {
			$this->min_hits = json_decode( $min_hits );
		} else {
			$this->min_hits = [];
		}
	}

	/**
	 * getter for active
	 *
	 * @return bool
	 */
	public function get_active(): bool {
		return $this->active;
	}

	/**
	 * setter for active
	 *
	 * @param bool $status
	 *
	 * @return void
	 */
	public function set_active( bool $status ): void {
		$this->active = $status;
	}

	/**
	 * getter for ownership
	 *
	 * @return bool owned = true, not owned = false
	 */
	public function get_ownership(): bool {
		return $this->ownership;
	}

	/**
	 * setter for ownership
	 *
	 * @param bool $status owned = true, not owned = false
	 *
	 * @return void
	 */
	public function set_ownership( bool $status ): void {
		$this->ownership = $status;
	}


	/**
	 * getter for message_created_at
	 *
	 * @return DateTime | null
	 */
	public function get_message_created_at(): DateTime|null {
		return $this->message_created_at;
	}

	/**
	 * getter for message_created_at
	 *
	 * @return string | null
	 */
	public function get_message_created_at_as_string(): string|null {
		return gettype( $this->message_created_at ) === 'object'
		       && get_class( $this->message_created_at ) == 'DateTime' ?
		      $this->message_created_at->format( 'Y-m-d H:i:s' ) : null;
	}

	/**
	 * setter for message_created_at
	 *
	 * @param DateTime | string | null $date
	 *
	 * @return void
	 */
	public function set_message_created_at( DateTime|string|null $date ): void {
		switch ( gettype( $date ) ) {
			case 'string':
				if($date === '0000-00-00 00:00:00') {
					$this->message_created_at = null;
				} else {
					$this->message_created_at = new \DateTime( $date );
				}

				break;
			case 'object':
				if ( get_class( $date ) == 'DateTime' ) {
					$this->message_created_at = $date;
				}
				break;
			default:
				$this->message_created_at = null;
		}
	}


	/**
	 * getter for source
	 *
	 * @return string
	 */
	public function get_source(): string {
		return $this->source;
	}

	/**
	 * setter for source
	 *
	 * @param string $source
	 *
	 * @return bool is set source a success?
	 */
	public function set_source( string $source ): bool {
		switch ( $source ) {
			case Common::SOURCE_RESTAPI:
			case Common::SOURCE_CSVIMPORT:
			case Common::SOURCE_SCANWORDPRESS:
			case Common::SOURCE_UNKNOWN:
			case Common::SOURCE_MANUAL:
				$this->source = $source;

				return true;
			default:
				return false;
		}
	}

	/**
	 * getter for state
	 *
	 * @return string | null
	 */
	public function get_state(): string|null {
		return $this->state;
	}

	/**
	 * setter for source
	 *
	 * @param string | null $state
	 *
	 * @return bool is set source a success?
	 */
	public function set_state( string|null $state ): bool {
		switch ( $state ) {
			case null:
			case Common::API_STATE_VALID:
			case Common::API_STATE_NOT_VALID:
			case Common::API_STATE_NOT_OWNER:
			case Common::API_STATE_NOT_FOUND:
				$this->state = $state;

				return true;
			default:
				return false;
		}
	}

	/**
	 * getter for text type
	 *
	 * @return string | null
	 */
	public function get_text_type(): string|null {
		return $this->text_type;
	}

	/**
	 * setter for text type
	 *
	 * @param string | null $text_type
	 *
	 * @return void
	 */
	public function set_text_type( string|null $text_type ): void {
		switch ( $text_type ) {
			case null:
			case Common::TEXT_TYPE_DEFAULT:
			case Common::TEXT_TYPE_LYRIC:
			case Common::TEXT_TYPE_EMPTY:
				$this->text_type = $text_type;
				break;
		}
	}

	/**
	 * getter for text length
	 *
	 * @return int | null
	 */
	public function get_text_length(): int|null {
		return $this->text_length;
	}

	/**
	 * setter for text length
	 *
	 * @param int | null $text_length
	 *
	 * @return void
	 */
	public function set_text_length( int|null $text_length ): void {
		$this->text_length = match ( $text_length ) {
			null => null,
			default => (int) $text_length,
		};
	}

	/**
	 * Convert the Order Pixel API Response to Pixel DB Format
	 *
	 * @param mixed $pixels_from_api order API response
	 * @param string $source         source (see class metis common constants)
	 *
	 * @return array|bool
	 */
	public static function batch_transform_api_to_db_pixel( object $pixels_from_api, string $source = Common::SOURCE_UNKNOWN ): array|bool {
		if ( ! count( $pixels_from_api->pixels ) > 0 ) {
			return false;
		}

		$db_pixels = [];

		foreach ( $pixels_from_api->pixels as $pixel ) {
			$insert = new Pixel( $pixel );
			$insert->set_source( $source );
			$insert->set_domain( $pixels_from_api->domain );
			$db_pixels[] = $insert;
		}

		return $db_pixels;
	}

}