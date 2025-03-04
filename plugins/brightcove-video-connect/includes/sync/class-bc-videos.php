<?php
/**
 * BC_Videos class file.
 *
 * @package Brightcove Video Connect
 */

/**
 * BC_Videos class.
 */
class BC_Videos {

	/**
	 * Custom post type name.
	 *
	 * @var string
	 */
	public $video_cpt = 'bc-in-process-video';

	/**
	 * BC_CMS_API instance.
	 *
	 * @var BC_CMS_API
	 */
	protected $cms_api;

	/**
	 * BC_Tags instance.
	 *
	 * @var BC_Tags
	 */
	protected $tags;

	/**
	 * Constructor method.
	 */
	public function __construct() {
		$this->cms_api = new BC_CMS_API();
		$this->tags    = new BC_Tags();
	}

	/**
	 * Updates Metadata to the Brightcove API
	 *
	 * @param array       $sanitized_post_data This should be sanitized POST data.
	 * @param string|bool $subtype Sub-type. Could be either false or "variant". See: https://apis.support.brightcove.com/cms/references/reference.html#operation/updateVideoVariant
	 *
	 * @return bool|WP_Error
	 */
	public function update_bc_video( $sanitized_post_data, $subtype = false ) {
		global $bc_accounts;

		$video_id    = BC_Utility::sanitize_id( $sanitized_post_data['video_id'] );
		$update_data = array();

		if ( array_key_exists( 'name', $sanitized_post_data ) && '' !== $sanitized_post_data['name'] ) {
			$update_data['name'] = utf8_uri_encode( sanitize_text_field( $sanitized_post_data['name'] ) );
		}

		if ( array_key_exists( 'description', $sanitized_post_data ) && ! empty( $sanitized_post_data['description'] ) ) {
			$update_data['description'] = BC_Utility::sanitize_payload_item( $sanitized_post_data['description'] );
		}

		if ( array_key_exists( 'long_description', $sanitized_post_data ) && ! empty( $sanitized_post_data['long_description'] ) ) {
			$update_data['long_description'] = BC_Utility::sanitize_payload_item( $sanitized_post_data['long_description'] );
		}

		if ( array_key_exists( 'custom_fields', $sanitized_post_data ) && ! empty( $sanitized_post_data['custom_fields'] ) ) {
			$update_data['custom_fields'] = $sanitized_post_data['custom_fields'];
		}

		if ( ! $subtype ) {
			if ( array_key_exists( 'tags', $sanitized_post_data ) && ! empty( $sanitized_post_data['tags'] ) ) {

				// Convert tags string to an array.
				$update_data['tags'] = array_map( 'trim', explode( ',', $sanitized_post_data['tags'] ) );

			}

			if ( array_key_exists( 'labels', $sanitized_post_data ) ) {
				$update_data['labels'] = $sanitized_post_data['labels'];
			}

			if ( array_key_exists( 'state', $sanitized_post_data ) ) {
				$update_data['state'] = $sanitized_post_data['state'];
			}

			if ( ! empty( $sanitized_post_data['scheduled_start_date'] ) ) {
				$start_date = date_create( $sanitized_post_data['scheduled_start_date'], new DateTimeZone( 'Europe/London' ) );

				if ( $start_date ) {
					// ISO 8601
					$update_data['schedule']['starts_at'] = $start_date->format( 'c' );
				}
			} else {
				$update_data['schedule']['starts_at'] = null;
			}

			if ( ! empty( $sanitized_post_data['scheduled_end_date'] ) ) {
				$end_date = date_create( $sanitized_post_data['scheduled_end_date'], new DateTimeZone( 'Europe/London' ) );

				if ( $end_date ) {
					// ISO 8601
					$update_data['schedule']['ends_at'] = $end_date->format( 'c' );
				}
			} else {
				$update_data['schedule']['ends_at'] = null;
			}
		}

		$bc_accounts->set_current_account( $sanitized_post_data['account'] );

		if ( 'variant' === $subtype ) {
			$language = sanitize_text_field( $sanitized_post_data['language'] );
			$request  = $this->cms_api->variant_update( $video_id, $language, $update_data );
		} else {
			$request = $this->cms_api->video_update( $video_id, $update_data );
			/**
			 * If we had any tags in the update, add them to the tags collection if we don't already track them.
			 */
			if ( array_key_exists( 'tags', $update_data ) && is_array( $update_data['tags'] ) && count( $update_data['tags'] ) ) {

				$existing_tags = $this->tags->get_tags();
				$new_tags      = array_diff( $update_data['tags'], $existing_tags );

				if ( count( $new_tags ) ) {
					$this->tags->add_tags( $new_tags );
				}
			}
		}

		$bc_accounts->restore_default_account();

		if ( is_wp_error( $request ) || false === $request ) {
			return false;
		}

		return true;
	}

	/**
	 * In the event video object data is stale in WordPress, or a video has never been generated,
	 * create/update WP data store with Brightcove data.
	 *
	 * @param array $video Video details.
	 * @param bool  $add_only True denotes that we know the object is not in our library and we are adding it first time to the library. This is to improve the initial sync.
	 *
	 * @return bool|WP_Error
	 */
	public function add_or_update_wp_video( $video, $add_only = false ) {
		$hash     = BC_Utility::get_hash_for_object( $video );
		$video_id = $video['id'];

		if ( ! $add_only ) {
			$stored_hash = $this->get_video_hash_by_id( $video_id );
			// No change to existing playlist
			if ( $hash === $stored_hash ) {
				return true;
			}
		}

		$post_excerpt = ( ! is_null( $video['description'] ) ) ? $video['description'] : '';
		$post_content = ( ! is_null( $video['long_description'] ) ) ? $video['long_description'] : $post_excerpt;
		$post_title   = ( ! is_null( $video['name'] ) ) ? $video['name'] : '';

		$post_date = new DateTime( $video['created_at'] );
		$post_date = $post_date->format( 'Y-m-d g:i:s' );

		$utc_timezone = new DateTimeZone( 'GMT' );
		$gmt          = new DateTime( $video['created_at'], $utc_timezone );
		$gmt          = $gmt->format( 'Y-m-d g:i:s' );

		$video_post_args = array(
			'post_type'     => $this->video_cpt,
			'post_title'    => $post_title,
			'post_content'  => $post_content,
			'post_excerpt'  => $post_excerpt,
			'post_date'     => $post_date,
			'post_date_gmt' => $gmt,
			'post_status'   => 'publish',
		);

		if ( ! $add_only ) {
			$existing_post = $this->get_video_by_id( $video_id );

			if ( $existing_post ) {

				$video_post_args['ID'] = $existing_post->ID;
				$post_id               = wp_update_post( $video_post_args );

			} else {

				$post_id = wp_insert_post( $video_post_args );

			}
		} else {
			$post_id = wp_insert_post( $video_post_args );
		}

		if ( ! $post_id ) {

			$error_message = esc_html__( 'The video has not been created in WordPress', 'brightcove' );
			BC_Logging::log( sprintf( 'BC WordPress ERROR: %s' ), $error_message );

			return new WP_Error( 'post-not-created', $error_message );

		}

		// Translators: #%d is the video ID.
		BC_Logging::log( sprintf( esc_html__( 'BC WordPress: Video with ID #%d has been created', 'brightcove' ), $post_id ) );

		if ( ! empty( $video['tags'] ) ) {
			wp_set_post_terms( $post_id, $video['tags'], 'brightcove_tags', false );
		}

		update_post_meta( $post_id, '_brightcove_hash', $hash );
		update_post_meta( $post_id, '_brightcove_video_id', BC_Utility::sanitize_and_generate_meta_video_id( $video['id'] ) );
		update_post_meta( $post_id, '_brightcove_transcoded', $video['complete'] );
		update_post_meta( $post_id, '_brightcove_account_id', $video['account_id'] );
		update_post_meta( $post_id, '_brightcove_video_object', $video );

		$meta      = array();
		$meta_keys = apply_filters(
			'brightcove_meta_keys',
			array(
				'images',
				'state',
				'cue_points',
				'custom_fields',
				'duration',
				'economics',
			)
		);

		foreach ( $meta_keys as $key ) {

			if ( ! empty( $video[ $key ] ) ) {
				$meta[ $key ] = $video[ $key ];
			}
		}

		update_post_meta( $post_id, '_brightcove_metadata', $meta );

		return true;

	}

	/**
	 * Accepts a video ID and checks to see if there is a record in WordPress. Returns the post object on success and false on failure.
	 *
	 * @param int $video_id The video ID.
	 *
	 * @return bool|WP_Post False on failure, WP_Post on success.
	 */
	public function get_video_by_id( $video_id ) {

		$video_id = BC_Utility::sanitize_and_generate_meta_video_id( $video_id );

		$existing_video = new WP_Query(
			array(
				'meta_key'               => '_brightcove_video_id',
				'meta_value'             => $video_id,
				'post_type'              => $this->video_cpt,
				'posts_per_page'         => 1,
				'update_post_term_cache' => false,
			)
		);

		if ( ! $existing_video->have_posts() ) {
			return false;
		}

		return end( $existing_video->posts );
	}

	/**
	 * Get the hash for a video by ID
	 *
	 * @param  int $video_id The video ID.
	 * @return false|mixed   The hash on success, false on failure.
	 */
	public function get_video_hash_by_id( $video_id ) {
		$video = $this->get_video_by_id( $video_id );

		if ( ! $video ) {
			return false;
		} else {
			return get_post_meta( $video->ID, '_brightcove_hash', true );
		}
	}

	/**
	 * Get the list of videos that are in progress.
	 *
	 * @return array List of videos
	 */
	public function get_in_progress_videos() {
		$args = array(
			'no_rows_found' => true,
			'fields'        => 'ids',
			'post_type'     => $this->video_cpt,
			'post_status'   => array( 'publish', 'future' ),
		);

		$wp_query = new \WP_Query();
		return $wp_query->query( $args );
	}
}
