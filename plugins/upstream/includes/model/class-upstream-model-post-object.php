<?php
/**
 * UpStream_Model_Post_Object
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
 * Class UpStream_Model_Post_Object.
 */
class UpStream_Model_Post_Object extends UpStream_Model_Object {

	/**
	 * CategoryIds
	 *
	 * @var array
	 */
	protected $categoryIds = array(); // phpcs:ignore

	/**
	 * ParentId
	 *
	 * @var int
	 */
	protected $parentId = 0; // phpcs:ignore

	/**
	 * PostType
	 *
	 * @var string
	 */
	protected $postType = 'post'; // phpcs:ignore

	/**
	 * UpStream_Model_Post_Object constructor.
	 *
	 * @param  mixed $id id.
	 * @param  mixed $fields fields.
	 * @return void
	 */
	public function __construct( $id, $fields ) {
		parent::__construct( $id );

		if ( $id > 0 ) {
			$this->load( $id, $fields );
		}
	}

	/**
	 * Load
	 *
	 * @param  mixed $id id.
	 * @param  mixed $fields fields.
	 * @throws UpStream_Model_ArgumentException Exception.
	 * @return void
	 */
	protected function load( $id, $fields ) {
		$post = get_post( $id );

		if ( ! $post ) {
			throw new UpStream_Model_ArgumentException(
				sprintf(
					// translators: %s: object id.
					__( 'Object with ID %s does not exist.', 'upstream' ),
					$id
				)
			);
		}

		$metadata = get_post_meta( $id );
		if ( empty( $metadata ) ) {
			return;
		}

		if ( ! isset( $post->post_title ) ) {
			throw new UpStream_Model_ArgumentException(
				sprintf(
					// translators: %s: object name.
					__( 'Object %s title does not exist.', 'upstream' ),
					$id
				)
			);
		}
		$this->title = $post->post_title;

		if ( ! isset( $post->post_author ) ) {
			throw new UpStream_Model_ArgumentException(
				sprintf(
					// translators: %s: object author.
					__( 'Object %s author does not exist.', 'upstream' ),
					$id
				)
			);
		}
		$this->createdBy = $post->post_author; // phpcs:ignore

		if ( ! isset( $post->post_content ) ) {
			throw new UpStream_Model_ArgumentException(
				sprintf(
				// translators: %s: object name.
					__( 'Object %s content does not exist.', 'upstream' ),
					$id
				)
			);
		}
		$this->description = $post->post_content;

		foreach ( $fields as $field => $input ) {
			if ( is_string( $input ) ) {
				if ( ! empty( $metadata[ $input ] ) ) {

					if ( count( $metadata[ $input ] ) > 0 ) {
						$this->{$field} = $metadata[ $input ][0];
					}
				}
			} elseif ( $input instanceof Closure ) {
				$this->{$field} = $input( $metadata );
			}
		}

		$data_to_load = array();
		foreach ( $metadata as $key => $val ) {
			if ( is_array( $val ) ) {
				$data_to_load[ $key ] = $val[0];
			}
		}

		$this->additionalFields = apply_filters( // phpcs:ignore
			'upstream_model_load_fields',
			$this->additionalFields, // phpcs:ignore
			$data_to_load,
			$this->type,
			$this->id
		);
	}

	/**
	 * Store
	 *
	 * @throws UpStream_Model_ArgumentException Exception.
	 * @return void
	 */
	protected function store() {
		$res = null;

		if ( $this->id > 0 ) {

			$post_arr = array(
				'ID'           => $this->id,
				'post_title'   => ( null === $this->title ? '(New Item)' : $this->title ),
				'post_content' => ( null === $this->description ? '' : $this->description ),
			);

			$res = wp_update_post( $post_arr, true );

		} else {
			$post_arr = array(
				'post_title'   => ( null === $this->title ? '(New Item)' : $this->title ),
				'post_author'  => $this->createdBy, // phpcs:ignore
				'post_parent'  => $this->parentId, // phpcs:ignore
				'post_content' => ( null === $this->description ? '' : $this->description ),
				'post_status'  => 'publish',
				'post_type'    => $this->postType, // phpcs:ignore
			);

			$res = wp_insert_post( $post_arr, true );
		}

		if ( $res instanceof \WP_Error ) {
			throw new UpStream_Model_ArgumentException(
				sprintf(
				// translators: %s: post id.
					__( 'Could not load post with ID %s.', 'upstream' ),
					$this->id
				)
			);
		} else {
			$this->id = (int) $res;
		}

		$data_to_store = array();
		$data_to_store = apply_filters(
			'upstream_model_store_fields',
			$data_to_store,
			$this->additionalFields, // phpcs:ignore
			$this->type,
			$this->id
		);

		foreach ( $data_to_store as $key => $value ) {
			update_post_meta( $this->id, $key, $value );
		}

	}

	/**
	 * Get
	 *
	 * @param  mixed $property property.
	 */
	public function __get( $property ) {
		$property = apply_filters( 'upstream_wcs_model_variable', $property );

		switch ( $property ) {

			case 'parentId':
				return $this->{$property};

			default:
				return parent::__get( $property );

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
				if ( ! filter_var( $value, FILTER_VALIDATE_INT ) ) {
					throw new UpStream_Model_ArgumentException( __( 'ID must be a valid numeric.', 'upstream' ) );
				}
				$this->{$property} = $value;
				break;

			default:
				parent::__set( $property, $value );
				break;

		}
	}

}
