<?php
/**
 * Item
 *
 * Item class.
 *
 * @since   2.6.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Item {

	/**
	 * @var int
	 */

	private $item_id;

	/**
	 * @var false|string
	 */

	private $post_type;

	/**
	 * Item constructor.
	 *
	 * @param $item_id
	 */

	public function __construct( $item_id ) {
		$this->item_id = (int) $item_id;
		$this->post_type = get_post_type( $this->item_id );
	}

	/**
	 * Get a single item.
	 *
	 * @return mixed|void
	 */

	public function get() {

		$item_data                   = [];
		$item_data['source_item_id'] = $this->item_id;

		if ( ! Settings::is_data_type_excluded( 'post_data' ) ) {
			$item_data['post_data'] = $this->post_data();
		}

		if ( ! Settings::is_data_type_excluded( 'post_meta' ) ) {
			$item_data['post_meta'] = $this->post_meta();
		}

		if ( ! Settings::is_data_type_excluded( 'taxonomies' ) ) {
			$item_data['taxonomies'] = $this->taxonomies();
		}

		if ( ! Settings::is_data_type_excluded( 'featured_image' ) ) {

			$item_data['featured_image'] = $this->featured_image();

			if ( empty( $item_data['featured_image']['image_url'] ) ) {
				unset( $item_data['featured_image'] );
			}

		}

		if ( 'attachment' === $this->post_type ) {
			$item_data['attachment'] = $this->attachment();
		}

		if ( ! Settings::is_data_type_excluded( 'integrations' ) ) {
			$item_data['integrations'] = apply_filters( 'wp_data_sync_item_request_integrations', [], $this->item_id, $this );
		}

		return apply_filters( 'wp_data_sync_item', array_filter( $item_data ), $this->item_id, $this );

	}

	/**
	 * Post Data.
	 *
	 * @return array|\WP_Post|null
	 */

	public function post_data() {

		global $wpdb;

		$post_data = $wpdb->get_row( $wpdb->prepare(
			"
			SELECT * 
			FROM $wpdb->posts
			WHERE ID = %d
			",
			$this->item_id
		), ARRAY_A );

		if ( empty( $post_data ) || is_wp_error( $post_data ) ) {
			return [];
		}

		unset( $post_data['ID'] );

		return apply_filters( 'wp_data_sync_item_request_post_data', $post_data, $this->item_id, $this );

	}

	/**
	 * Post Meta.
	 *
	 * @return array
	 */

	public function post_meta() {

		$post_meta   = [];
		$meta_values = get_post_meta( $this->item_id );

		foreach ( $meta_values as $meta_key => $values ) {

			// Get the first element of array.
			$meta_value = array_shift( $values );

			$post_meta[ $meta_key ] = maybe_unserialize( $meta_value );

		}

		// Save the post ID into meta data.
		$post_meta['_source_item_id'] = $this->item_id;

		return apply_filters( 'wp_data_sync_item_request_post_meta', $post_meta, $this->item_id, $this );

	}

	/**
	 * Featured image.
	 *
	 * @since 1.6.0
	 *
	 * @return mixed|void
	 */

	public function featured_image() {

		$featured_image = [
			'image_url'   => get_the_post_thumbnail_url( $this->item_id, 'full' ),
			'title'       => get_the_title( $this->item_id ) ?: '',
			'description' => get_the_content( $this->item_id ) ?: '',
			'caption'     => get_the_excerpt( $this->item_id ) ?: '',
			'alt'         => get_post_meta( $this->item_id, '_wp_attachment_image_alt', true ) ?: ''
		];

		return apply_filters( 'wp_data_sync_item_request_featured_image', $featured_image, $this->item_id, $this );

	}

	/**
	 * Get attachment details.
	 *
	 * @return mixed|void
	 */

	public function attachment() {

		$attachment = [
			'image_url'   => wp_get_attachment_image_url( $this->item_id, 'full' ),
			'title'       => get_the_title( $this->item_id ) ?: '',
			'description' => get_the_content( $this->item_id ) ?: '',
			'caption'     => get_the_excerpt( $this->item_id ) ?: '',
			'alt'         => get_post_meta( $this->item_id, '_wp_attachment_image_alt', true ) ?: ''
		];

		return apply_filters( 'wp_data_sync_item_request_attachment', $attachment, $this->item_id, $this );

	}

	/**
	 * Taxonomies.
	 *
	 * @return array|\WP_Error|bool
	 */

	public function taxonomies() {

		$results = [];
		$taxonomies = get_object_taxonomies( $this->post_type );

		foreach ( $taxonomies as $taxonomy ) {

			$term_ids = wp_get_object_terms( $this->item_id, $taxonomy, [ 'fields' => 'ids' ] );

			if ( ! empty( $term_ids ) && is_array( $term_ids ) ) {
				$results[ $taxonomy ] = $this->format_terms( $term_ids, $taxonomy );
			}

		}

		return apply_filters( 'wp_data_sync_item_request_taxonomies', array_filter( $results ), $this->item_id, $this );

	}

	/**
	 * Format Terms.
	 *
	 * @param $term_ids
	 * @param $taxonomy
	 *
	 * @return array
	 */

	public function format_terms( $term_ids, $taxonomy ) {

		$term_ids = wp_parse_id_list( $term_ids );

		if ( ! count( $term_ids ) ) {
			return [];
		}

		$formatted_terms = [];

		if ( is_taxonomy_hierarchical( $taxonomy ) ) {

			foreach ( $term_ids as $term_id ) {

				$ancestor_ids = array_reverse( get_ancestors( $term_id, $taxonomy ) );
				$ancestors    = [];

				foreach ( $ancestor_ids as $ancestor_id ) {

					$term = get_term( $ancestor_id, $taxonomy );

					if ( $term && ! is_wp_error( $term ) ) {
						$ancestors[ $term->slug ] = $this->term_array( $term );
					}

				}

				$term = get_term( $term_id, $taxonomy );

				if ( $term && ! is_wp_error( $term ) ) {
					$formatted_terms[ $term->slug ] = $this->term_array( $term );
				}

				$formatted_terms[ $term->slug ]['parents'] = $ancestors;

			}

		} else {

			foreach ( $term_ids as $term_id ) {

				$term = get_term( $term_id, $taxonomy );

				if ( $term && ! is_wp_error( $term ) ) {
					$formatted_terms[ $term->slug ] = $this->term_array( $term );
				}

			}

		}

		return $formatted_terms;

	}

	/**
	 * Term array.
	 *
	 * @param $term
	 *
	 * @return array
	 */

	public function term_array( $term ) {

		$term_array = [
			'name'        => $term->name,
			'description' => $term->description,
			'thumb_url'   => $this->term_thumb_url( $term ),
			'term_meta'   => $this->term_meta( $term )
		];

		return apply_filters( 'wp_data_sync_item_request_term_array', $term_array, $term->term_id );

	}

	/**
	 * Get term thumnail URL.
	 *
	 * @param $term
	 *
	 * @return bool|false|string
	 */

	public function term_thumb_url( $term ) {

		if ( $attach_id = get_term_meta( $term->term_id, 'thumbnail_id', true ) ) {
			return wp_get_attachment_image_url( (int) $attach_id, 'full' );
		}

		return false;

	}

	/**
	 * Term meta.
	 *
	 * @param $term
	 *
	 * @return array
	 */

	public function term_meta( $term ) {

		$meta_values = [];
		$term_meta   = get_term_meta( $term->term_id );

		foreach ( $term_meta as $meta_key => $values ) {

			// Get the first element of array.
			$meta_value = array_shift( $values );

			$meta_values[ $meta_key ] = maybe_unserialize( $meta_value );

		}

		return apply_filters( 'wp_data_sync_item_request_term_meta', $meta_values, $term, $this );

	}

	/**
	 * Get the post type.
	 *
	 * @return string
	 */

	public function get_post_type() {
		return $this->post_type;
	}

}
