<?php
/**
 * UpStream_Model_Meta_Object
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
 * Class UpStream_Model_Meta_Object
 */
class UpStream_Model_Meta_Object extends UpStream_Model_Object {

	/**
	 * Parent
	 *
	 * @var undefined
	 */
	protected $parent = null;

	/**
	 * MetadataKey
	 *
	 * @var undefined
	 */
	protected $metadataKey = null; // phpcs:ignore

	/**
	 * UpStream_Model_Meta_Object constructor.
	 *
	 * @param  mixed $parent parent.
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	public function __construct( $parent, $item_metadata ) {
		parent::__construct();

		$this->parent = $parent;
		$this->loadFromArray( $item_metadata );
	}

	/**
	 * LoadFromArray
	 *
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	protected function loadFromArray( $item_metadata ) {
		$this->id          = ! empty( $item_metadata['id'] ) ? $item_metadata['id'] : 0;
		$this->title       = ! empty( $item_metadata['title'] ) ? $item_metadata['title'] : null;
		$this->assignedTo  = ! empty( $item_metadata['assigned_to'] ) ? $item_metadata['assigned_to'] : array(); // phpcs:ignore
		$this->createdBy   = ! empty( $item_metadata['created_by'] ) ? $item_metadata['created_by'] : array(); // phpcs:ignore
		$this->description = ! empty( $item_metadata['description'] ) ? $item_metadata['description'] : null;

		$this->additionalFields = apply_filters( // phpcs:ignore
			'upstream_model_load_fields',
			$this->additionalFields, // phpcs:ignore
			$item_metadata,
			$this->type,
			$this->id
		);
	}

	/**
	 * StoreToArray
	 *
	 * @param  mixed $item_metadata item_metadata.
	 * @return void
	 */
	public function storeToArray( &$item_metadata ) {
		$throw_error = false;

		if ( ! ( $this->parent instanceof UpStream_Model_Post_Object ) ) {
			$throw_error = true; // TODO: throw error.
		}

		if ( 0 === $this->id ) {
			$this->id = uniqid( get_current_user_id() );
		}
		$item_metadata['id'] = $this->id;

		if ( null !== $this->title ) {
			$item_metadata['title'] = $this->title;
		}
		if ( count( $this->assignedTo ) > 0 ) { // phpcs:ignore
			$item_metadata['assigned_to'] = $this->assignedTo; // phpcs:ignore
		}
		if ( $this->createdBy > 0 ) { // phpcs:ignore
			$item_metadata['created_by'] = $this->createdBy; // phpcs:ignore
		}
		if ( null !== $this->description ) {
			$item_metadata['description'] = $this->description;
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
			$item_metadata[ $key ] = $value;
		}

	}

	/**
	 * Store
	 *
	 * @throws UpStream_Model_ArgumentException Exception.
	 * @return void
	 */
	public function store() {
		if ( ! ( $this->parent instanceof UpStream_Model_Post_Object ) ) {
			throw new UpStream_Model_ArgumentException( __( 'Parent is of the wrong type.', 'upstream' ) );
		}

		$added = false;

		$new_item = array();
		$this->storeToArray( $new_item );

		$itemset       = get_post_meta( $this->parent->id, $this->metadataKey ); // phpcs:ignore
		$itemset_is_ok = false;

		if ( $itemset && count( $itemset ) === 1 && is_array( $itemset[0] ) ) {
			$itemset_is_ok = true; // it's ok.
		} else {
			$itemset = array( array() );
		}

		$count_itemset = count( $itemset[0] );

		for ( $i = 0; $i < $count_itemset; $i++ ) {
			if ( $itemset[0][ $i ]['id'] === $this->id ) {

				$itemset[0][ $i ] = $new_item;
				$added            = true;
				break;

			}
		}

		if ( ! $added ) {
			$itemset[0][] = $new_item;
		}

		$r = update_post_meta( $this->parent->id, $this->metadataKey, $itemset[0] ); // phpcs:ignore

		$project_object = new UpStream_Project( $this->parent->id );
		$project_object->update_project_meta();

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
				if ( null !== $this->parent ) {
					return $this->parent->id;
				}
				return 0;

			case 'parent':
				return $this->parent;

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

			default:
				parent::__set( $property, $value );
				break;

		}
	}

}
