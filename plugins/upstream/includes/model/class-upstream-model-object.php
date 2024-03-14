<?php
/**
 * UpStream_Model_Object
 *
 * WordPress Coding Standart (WCS) note:
 * All camelCase methods and object properties on this file are not converted to snake_case,
 * because it being used (heavily) on another add-on plugins.
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'UPSTREAM_ITEM_TYPE_PROJECT' ) ) {
	define( 'UPSTREAM_ITEM_TYPE_PROJECT', 'project' );
	define( 'UPSTREAM_ITEM_TYPE_MILESTONE', 'milestone' );
	define( 'UPSTREAM_ITEM_TYPE_CLIENT', 'client' );
	define( 'UPSTREAM_ITEM_TYPE_TASK', 'task' );
	define( 'UPSTREAM_ITEM_TYPE_BUG', 'bug' );
	define( 'UPSTREAM_ITEM_TYPE_FILE', 'file' );
	define( 'UPSTREAM_ITEM_TYPE_DISCUSSION', 'discussion' );
}

/**
 * Class UpStream_Model_Object
 */
class UpStream_Model_Object {

	/**
	 * Id
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Type
	 *
	 * @var undefined
	 */
	protected $type = null;

	/**
	 * Title
	 *
	 * @var undefined
	 */
	protected $title = null;

	/**
	 * AssignedTo
	 *
	 * @var array
	 */
	protected $assignedTo = array(); // phpcs:ignore

	/**
	 * Created_by
	 *
	 * @var int
	 */
	protected $createdBy = 0; // phpcs:ignore

	/**
	 * Description
	 *
	 * @var undefined
	 */
	protected $description = null;

	/**
	 * AdditionalFields
	 *
	 * @var array
	 */
	protected $additionalFields = array(); // phpcs:ignore

	/**
	 * UpStream_Model_Object constructor.
	 *
	 * @param int $id Id.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public function __construct( $id = 0 ) {
		if ( ! preg_match( '/^[a-zA-Z0-9]+$/', $id ) ) {
			throw new UpStream_Model_ArgumentException(
				sprintf(
					// translators: %s: ID.
					__( 'ID %s must be a valid alphanumeric.', 'upstream' ),
					$id
				)
			);
		}

		$this->id = $id;
	}

	/**
	 * IsAssignedTo
	 *
	 * @param  int $user_id The user_id of the user to check.
	 * @return bool True if this object is assigned to user_id, or false otherwise.
	 */
	public function isAssignedTo( $user_id ) { // phpcs:ignore
		foreach ( $this->assignedTo as $a ) { // phpcs:ignore
			if ( $a === $user_id ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Fields
	 */
	public static function fields() {
		return array(
			'id'          => array(
				'type'    => 'id',
				'title'   => __( 'ID' ),
				'search'  => false,
				'display' => true,
			),
			'title'       => array(
				'type'    => 'string',
				'title'   => __( 'Title' ),
				'search'  => true,
				'display' => true,
			),
			'description' => array(
				'type'    => 'text',
				'title'   => __( 'Description' ),
				'search'  => true,
				'display' => true,
			),
			'createdBy'   => array(
				'type'    => 'user_id',
				'title'   => __( 'Created By' ),
				'search'  => true,
				'display' => true,
			),
			'assignedTo'  => array(
				'type'     => 'user_id',
				'is_array' => true,
				'title'    => __( 'Assigned To' ),
				'search'   => true,
				'display'  => true,
			),
		);
	}

	/**
	 * CustomFields
	 *
	 * @param  mixed $fields fields.
	 * @param  mixed $type type.
	 * @param  mixed $id id.
	 */
	public static function customFields( $fields, $type, $id = 0 ) { // phpcs:ignore
		$list = apply_filters( 'upstream_model_list_properties', array(), $type, $id );
		return array_merge( $fields, $list );
	}

	/**
	 * Get
	 *
	 * @param  mixed $property property.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public function __get( $property ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'id':
			case 'title':
			case 'assignedTo':
			case 'createdBy':
			case 'type':
			case 'description':
				return $this->{$property};

			default:
				if ( array_key_exists( $property, $this->additionalFields ) ) { // phpcs:ignore

					$value = apply_filters( 'upstream_model_get_property_value', $this->additionalFields[ $property ], $this->type, $this->id, $property ); // phpcs:ignore

				} else {

					$property_exists = apply_filters( 'upstream_model_property_exists', false, $this->type, $this->id, $property );
					if ( ! $property_exists ) {
						throw new UpStream_Model_ArgumentException(
							sprintf(
								// translators: %s: property.
								__( 'This (%s) is not a valid property.', 'upstream' ),
								$property
							)
						);
					}

					$this->additionalFields[ $property ] = null; // phpcs:ignore

					return null;
				}

				return $value;
		}
	}

	/**
	 * Set
	 *
	 * @param  mixed $property property.
	 * @param  mixed $value value.
	 * @throws UpStream_Model_ArgumentException Exception.
	 * @return void
	 */
	public function __set( $property, $value ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'id':
				if ( ! preg_match( '/^[a-zA-Z0-9]+$/', $value ) ) {
					throw new UpStream_Model_ArgumentException(
						sprintf(
							// translators: %s: ID.
							__( 'ID %s must be a valid alphanumeric.', 'upstream' ),
							$value
						)
					);
				}
				$this->{$property} = $value;
				break;

			case 'title':
				if ( trim( sanitize_text_field( $value ) ) === '' ) {
					throw new UpStream_Model_ArgumentException( __( 'You must enter a title.', 'upstream' ) );
				}

				$this->{$property} = trim( sanitize_text_field( $value ) );
				break;

			case 'description':
				$this->{$property} = wp_kses_post( $value );
				break;

			case 'assignedTo':
			case 'assignedTo:byUsername':
			case 'assignedTo:byEmail':
				if ( ! is_array( $value ) ) {
					$value = explode( ',', $value );
				}

				$new_value = array();

				foreach ( $value as $uid ) {
					$user = false;
					if ( 'assignedTo' === $property ) {
						$user = get_user_by( 'id', $uid );
					}
					if ( 'assignedTo:byUsername' === $property ) {
						$user = get_user_by( 'login', trim( $uid ) );
					}
					if ( 'assignedTo:byEmail' === $property ) {
						$user = get_user_by( 'email', trim( $uid ) );
					}

					if ( false === $user ) {
						throw new UpStream_Model_ArgumentException(
							sprintf(
								// translators: %1$s: user name.
								// translators: %2$s: user field.
								__( 'User "%1$s" (for field %2$s) does not exist.', 'upstream' ),
								$uid,
								$property
							)
						);
					}

					$new_value[] = $user->ID;
				}

				$this->assignedTo = $new_value; // phpcs:ignore
				break;

			case 'createdBy':
				if ( get_userdata( $value ) === false ) {
					throw new UpStream_Model_ArgumentException(
						sprintf(
							// translators: %s: ID.
							__( 'User ID %s does not exist.', 'upstream' ),
							$value
						)
					);
				}

				$this->{$property} = $value;
				break;

			default:
				$orig_value = ( array_key_exists( $property, $this->additionalFields ) ) ? $this->additionalFields[ $property ] : null; // phpcs:ignore

				$property_exists = apply_filters( 'upstream_model_property_exists', false, $this->type, $this->id, $property );
				if ( ! $property_exists ) {
					throw new UpStream_Model_ArgumentException(
						sprintf(
							// translators: %s: property.
							__( 'This (%s) is not a valid property.', 'upstream' ),
							$property
						)
					);
				}

				$new_value                           = apply_filters( 'upstream_model_set_property_value', $orig_value, $this->type, $this->id, $property, $value );
				$this->additionalFields[ $property ] = $new_value; // phpcs:ignore
		}
	}

	/**
	 * LoadDate
	 *
	 * @param  mixed $data data.
	 * @param  mixed $field field.
	 */
	public static function loadDate( $data, $field ) { // phpcs:ignore
		if ( ! empty( $data[ $field . '.YMD' ] ) && self::isValidDate( $data[ $field . '.YMD' ] ) ) {
			return $data[ $field . '.YMD' ];
		} elseif ( ! empty( $data[ $field ] ) ) {
			return self::timestampToYMD( $data[ $field ] );
		}
		return null;
	}

	/**
	 * TimestampToYMD
	 *
	 * @param  mixed $timestamp timestamp.
	 */
	public static function timestampToYMD( $timestamp ) { // phpcs:ignore
		$offset              = get_option( 'gmt_offset' );
		$sign                = $offset < 0 ? '-' : '+';
		$hours               = (int) $offset;
		$minutes             = abs( ( $offset - (int) $offset ) * 60 );
		$offset              = (int) sprintf( '%s%d%02d', $sign, abs( $hours ), $minutes );
		$calc_offset_seconds = $offset < 0 ? $offset * -1 * 60 : $offset * 60;

		$date = date_i18n( 'Y-m-d', $timestamp + $calc_offset_seconds );
		return $date;
	}

	/**
	 * YmdToTimestamp
	 *
	 * @param  mixed $ymd ymd.
	 */
	public static function ymdToTimestamp( $ymd ) { // phpcs:ignore
		// TODO: check timezones with this.
		return date_create_from_format( 'Y-m-d', $ymd )->getTimestamp();
	}

	/**
	 * IsValidDate
	 *
	 * @param  mixed $ymd ymd.
	 */
	public static function isValidDate( $ymd ) { // phpcs:ignore
		$d = DateTime::createFromFormat( 'Y-m-d', $ymd );
		return $d && $d->format( 'Y-m-d' ) === $ymd;
	}

}
