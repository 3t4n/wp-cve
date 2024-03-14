<?php
/**
 * Trait that abstracts the metadata functions
 *
 * @package UpStream\Traits
 */

namespace UpStream\Traits;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait that abstracts the metadata functions
 *
 * @package     UpStream
 * @subpackage  Traits
 * @author      UpStream <https://upstreamplugin.com>
 * @copyright   Copyright (c) 2018 UpStream Project Management
 * @license     GPL-3
 * @since       1.11.0
 */
trait PostMetadata {

	/**
	 * Post ID
	 *
	 * @var int
	 */
	protected $post_id;

	/**
	 * Get metadata
	 *
	 * @param string $meta_key Meta key.
	 * @param bool   $single Is single.
	 *
	 * @return mixed
	 */
	public function get_metadata( $meta_key, $single = false ) {
		return get_post_meta( $this->post_id, $meta_key, $single );
	}

	/**
	 * Update metadata.
	 *
	 * @param array $dataset Dataset.
	 */
	public function update_metadata( $dataset ) {
		if ( ! empty( $dataset ) ) {
			foreach ( $dataset as $meta_key => $meta_value ) {
				update_post_meta( $this->post_id, $meta_key, $meta_value );
			}
		}
	}

	/**
	 * Delete metadata
	 *
	 * @param string|array $meta_key Meta key.
	 */
	public function delete_metadata( $meta_key ) {
		if ( empty( $meta_key ) ) {
			return;
		}

		// Only one meta key?
		if ( is_string( $meta_key ) ) {
			delete_post_meta( $this->post_id, $meta_key );

			return;
		}

		// An array of meta keys?
		if ( is_array( $meta_key ) ) {
			foreach ( $meta_key as $key ) {
				if ( ! empty( $key ) ) {
					delete_post_meta( $this->post_id, $key );
				}
			}
		}
	}

	/**
	 * Add Unique Metadata
	 *
	 * @param array $dataset Dataset.
	 *
	 * @return array|false
	 */
	public function add_unique_metadata( $dataset ) {
		if ( empty( $dataset ) ) {
			return false;
		}

		$meta_ids = array();

		foreach ( $dataset as $meta_key => $meta_value ) {
			$meta_ids[ $meta_key ] = add_post_meta( $this->post_id, $meta_key, $meta_key, true );
		}

		return $meta_ids;
	}

	/**
	 * Add Non Unique Metadata
	 *
	 * @param string $meta_key Meta key.
	 * @param array  $meta_values Meta values.
	 *
	 * @return array|false
	 */
	public function add_non_unique_metadata( $meta_key, $meta_values ) {
		if ( empty( $meta_key ) || empty( $meta_values ) ) {
			return false;
		}

		$meta_ids = array();

		foreach ( $meta_values as $meta_value ) {
			$meta_ids[] = add_post_meta( $this->post_id, $meta_key, $meta_value );
		}

		return $meta_ids;
	}

	/**
	 * Update Non Unique Metadata
	 *
	 * @param string $meta_key Meta key.
	 * @param array  $meta_values Meta values.
	 *
	 * @return array|false
	 */
	public function update_non_unique_metadata( $meta_key, $meta_values ) {
		$this->delete_metadata( $meta_key );

		return $this->add_non_unique_metadata( $meta_key, $meta_values );
	}
}
