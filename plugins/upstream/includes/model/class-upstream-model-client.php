<?php
/**
 * UpStream_Model_Client
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

/**
 * Class UpStream_Model_Client
 */
class UpStream_Model_Client extends UpStream_Model_Post_Object {

	/**
	 * UserAssignments
	 *
	 * @var array
	 */
	protected $userAssignments = array(); // phpcs:ignore

	/**
	 * Website
	 *
	 * @var undefined
	 */
	protected $website = null;

	/**
	 * Address
	 *
	 * @var undefined
	 */
	protected $address = null;

	/**
	 * Phone
	 *
	 * @var undefined
	 */
	protected $phone = null;

	/**
	 * PostType
	 *
	 * @var string
	 */
	protected $postType = 'client'; // phpcs:ignore

	/**
	 * Type
	 *
	 * @var undefined
	 */
	protected $type = UPSTREAM_ITEM_TYPE_CLIENT;

	/**
	 * UpStream_Model_Client constructor.
	 *
	 * @param int $id Client Id.
	 */
	public function __construct( $id ) {
		if ( $id > 0 ) {
			parent::__construct(
				$id,
				array(
					'website'         => '_upstream_client_website',
					'address'         => '_upstream_client_address',
					'phone'           => '_upstream_client_phone',
					'userAssignments' => function ( $m ) {

						if ( isset( $m['_upstream_new_client_users'][0] ) ) {
							$arr = unserialize( $m['_upstream_new_client_users'][0] );

							$out = array();
							foreach ( $arr as $item ) {
								$s             = new stdClass();
								$s->id         = $item['user_id'];
								$s->assignedBy = $item['assigned_by']; // phpcs:ignore
								$s->assignedAt = $item['assigned_at']; // phpcs:ignore
								$out[]         = $s;
							}
							return $out;

						}
						return array();

					},
				)
			);

		} else {
			parent::__construct( 0, array() );
		}

		$this->type = UPSTREAM_ITEM_TYPE_CLIENT;
	}

	/**
	 * Fields
	 */
	public static function fields() {
		$fields = parent::fields();

		$fields['website'] = array(
			'type'    => 'string',
			'title'   => __( 'Website' ),
			'search'  => true,
			'display' => true,
		);
		$fields['address'] = array(
			'type'    => 'string',
			'title'   => __( 'Address' ),
			'search'  => true,
			'display' => true,
		);
		$fields['phone']   = array(
			'type'    => 'string',
			'title'   => __( 'Phone' ),
			'search'  => true,
			'display' => true,
		);
		$fields['userIds'] = array(
			'type'     => 'user_id',
			'is_array' => true,
			'title'    => __( 'Users' ),
			'search'   => true,
			'display'  => true,
		);

		return $fields;
	}

	/**
	 * Store
	 *
	 * @return void
	 */
	public function store() {
		parent::store();

		if ( null !== $this->phone ) {
			update_post_meta( $this->id, '_upstream_client_phone', $this->phone );
		}
		if ( null !== $this->address ) {
			update_post_meta( $this->id, '_upstream_client_address', $this->address );
		}
		if ( null !== $this->website ) {
			update_post_meta( $this->id, '_upstream_client_website', $this->website );
		}

		if ( null !== $this->userAssignments ) { // phpcs:ignore

			$arr = array();
			foreach ( $this->userAssignments as $assignment ) { // phpcs:ignore
				$arr[] = array(
					'user_id'     => $assignment->id,
					'assigned_by' => $assignment->assignedBy, // phpcs:ignore
					'assigned_at' => $assignment->assignedAt, // phpcs:ignore
				);
			}
			update_post_meta( $this->id, '_upstream_new_client_users', $arr );

		}
	}

	/**
	 * AddUser
	 *
	 * @param  mixed $user_id userId.
	 * @param  mixed $assigned_by assignedBy.
	 * @throws UpStream_Model_ArgumentException Exception.
	 * @return void
	 */
	public function addUser( $user_id, $assigned_by ) {
		if ( get_userdata( $user_id ) === false ) {
			throw new UpStream_Model_ArgumentException(
				sprintf(
					// translators: %s: user id.
					__( 'User ID %s does not exist.', 'upstream' ),
					$user_id
				)
			);
		}

		if ( get_userdata( $assigned_by ) === false ) {
			throw new UpStream_Model_ArgumentException(
				sprintf(
					// translators: %s: user id.
					__( 'User ID %s does not exist.', 'upstream' ),
					$assigned_by
				)
			);
		}

		foreach ( $this->userAssignments as $assignment ) { // phpcs:ignore
			if ( $assignment->id === $user_id ) {
				throw new UpStream_Model_ArgumentException(
					sprintf(
						// translators: %s: user id.
						__( 'User ID %s is already attached.', 'upstream' ),
						$assigned_by
					)
				);
			}
		}

		$assignment              = new stdClass();
		$assignment->id          = $user_id;
		$assignment->assignedBy  = $assigned_by; // phpcs:ignore
		$assignment->assignedAt  = gmdate( 'Y-m-d H:i:s' ); // phpcs:ignore
		$this->userAssignments[] = $assignment; // phpcs:ignore
	}

	/**
	 * RemoveUser
	 *
	 * @param  mixed $user_id userId.
	 * @throws UpStream_Model_ArgumentException Exception.
	 * @return void
	 */
	public function removeUser( $user_id ) {
		$count_user_assignments = count( $this->userAssignments ); // phpcs:ignore
		for ( $i = 0; $i < $count_user_assignments; $i++ ) {
			if ( $this->userAssignments[ $i ]->id === $user_id ) { // phpcs:ignore
				array_splice( $this->userAssignments, $i, 1 ); // phpcs:ignore
				return;
			}
		}

		throw new UpStream_Model_ArgumentException(
			sprintf(
				// translators: %s: user id.
				__( 'User ID %s is not attached.', 'upstream' ),
				$user_id
			)
		);
	}

	/**
	 * IncludesUser
	 *
	 * @param  mixed $user_id userId.
	 */
	public function includesUser( $user_id ) {
		$count_user_assignments = count( $this->userAssignments ); // phpcs:ignore
		for ( $i = 0; $i < $count_user_assignments; $i++ ) {
			if ( $this->userAssignments[ $i ]->id === $user_id ) { // phpcs:ignore
				return true;
			}
		}

		return false;
	}

	/**
	 * Get
	 *
	 * @param  mixed $property property.
	 */
	public function __get( $property ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'phone':
			case 'website':
			case 'address':
				return $this->{$property};

			case 'userIds':
				if ( null === $this->userAssignments ) { // phpcs:ignore
					return array();
				}

				$user_ids = array();
				foreach ( $this->userAssignments as $assignment ) { // phpcs:ignore
					$user_ids[] = $assignment->id;
				}

				return $user_ids;

			default:
				return parent::__get( $property );

		}
	}

	/**
	 * Set
	 *
	 * @param  mixed $property property.
	 * @param  mixed $value value.
	 * @return void
	 */
	public function __set( $property, $value ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'phone':
			case 'website':
				$this->{$property} = sanitize_text_field( $value );
				break;

			case 'address':
				$this->{$property} = sanitize_textarea_field( $value );
				break;

			case 'userIds':
				if ( ! is_array( $value ) ) {
					$value = array( $value );
				}

				foreach ( $value as $user_id ) {
					$exception = null;
					try {
						$this->addUser( $user_id, get_current_user_id() );
					} catch ( \UpStream_Model_ArgumentException $e ) {
						$exception = $e; // ignore errors here.
					}
				}
				break;

			default:
				parent::__set( $property, $value );
				break;

		}
	}

	/**
	 * Create
	 *
	 * @param  mixed $title title.
	 * @param  mixed $created_by created_by.
	 * @throws UpStream_Model_ArgumentException Exception.
	 */
	public static function create( $title, $created_by ) {
		if ( get_userdata( $created_by ) === false ) {
			throw new UpStream_Model_ArgumentException( __( 'User ID does not exist.', 'upstream' ) );
		}

		$item = new \UpStream_Model_Client( 0 );

		$item->title     = sanitize_text_field( $title );
		$item->createdBy = $created_by; // phpcs:ignore

		return $item;
	}

}
