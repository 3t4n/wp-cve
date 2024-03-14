<?php
/**
 * This file contains a class with some post-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/helpers
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

use function Nelio_Content\Helpers\key_by;

/**
 * This class implements post-related helper functions.
 */
class Nelio_Content_Post_Helper {

	protected static $instance;
	private static $networks = array( 'facebook', 'googleplus', 'instagram', 'linkedin', 'pinterest', 'twitter', 'tumblr', 'telegram', 'tiktok', 'gmb', 'reddit' );

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	/**
	 * This function returns the suggested and external references of the post.
	 *
	 * @param integer|WP_Post $post_id The post whose reference we want or its ID.
	 * @param string          $type    Optional. Type of references to pull. Accepted values: `all` | `included` | `suggested`. Default: `all`.
	 *
	 * @return array an array with two lists: _suggested_ and _included_ references.
	 *
	 * @since  1.3.4
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function get_references( $post_id, $type = 'all' ) {

		if ( $post_id instanceof WP_Post ) {
			$post_id = $post_id->ID;
		}//end if

		if ( empty( $post_id ) ) {
			return array();
		}//end if

		$included_ids  = nc_get_post_reference( $post_id, 'included' );
		$suggested_ids = nc_get_post_reference( $post_id, 'suggested' );
		if ( 'included' === $type ) {
			$reference_ids = $included_ids;
		} elseif ( 'suggested' === $type ) {
			$reference_ids = $suggested_ids;
		} else {
			$reference_ids = array_values( array_unique( array_merge( $included_ids, $suggested_ids ) ) );
		}//end if

		$references = array_map(
			function( $ref_id ) use ( $post_id ) {
				$reference = new Nelio_Content_Reference( $ref_id );
				$meta      = nc_get_suggested_reference_meta( $post_id, $ref_id );
				if ( ! empty( $meta ) ) {
					$reference->mark_as_suggested( $meta['advisor'], $meta['date'] );
				}//end if
				return $reference->json_encode();
			},
			$reference_ids
		);

		$references = array_filter(
			$references,
			function ( $ref ) {
				return ! empty( $ref['url'] );
			}
		);

		return array_values( $references );

	}//end get_references()

	/**
	 * This function returns a list with the domains that shouldn't be considered
	 * as references.
	 *
	 * @return array an array with the external references
	 *
	 * @since  1.3.4
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function get_non_reference_domains() {

		/**
		 * List of domain names that shouldn't be considered as external references.
		 *
		 * @param array domains list of domain names that shouldn't be considered as
		 *                      external references. It accepts the star (*) char as
		 *                      a wildcard.
		 *
		 * @since 1.3.4
		 */
		return apply_filters(
			'nelio_content_non_reference_domains',
			array(
				'bing.*',
				'*.bing.*',
				'flickr.com',
				'giphy.com',
				'google.*',
				'*.google.*',
				'linkedin.com',
				'unsplash.com',
				'twitter.com',
				'facebook.com',
			)
		);

	}//end get_non_reference_domains()

	/**
	 * Modifies the metas so that we know whether the post can be auto shared or not.
	 *
	 * @param int     $post_id Post ID.
	 * @param boolean $enabled whether the post can be auto shared or not.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function enable_auto_share( $post_id, $enabled ) {

		if ( $enabled ) {
			delete_post_meta( $post_id, '_nc_exclude_from_auto_share' );
			update_post_meta( $post_id, '_nc_include_in_auto_share', true );
		} else {
			delete_post_meta( $post_id, '_nc_include_in_auto_share' );
			update_post_meta( $post_id, '_nc_exclude_from_auto_share', true );
		}//end if

	}//end enable_auto_share()

	/**
	 * Returns whether the post can be auto shared or not.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return boolean whether the post can be auto shared or not.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function is_auto_share_enabled( $post_id ) {

		$explicitly_included = ! empty( get_post_meta( $post_id, '_nc_include_in_auto_share', true ) );
		if ( $explicitly_included ) {
			return true;
		}//end if

		$explicitly_excluded = ! empty( get_post_meta( $post_id, '_nc_exclude_from_auto_share', true ) );
		if ( $explicitly_excluded ) {
			return false;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		return 'include-in-auto-share' === $settings->get( 'auto_share_default_mode' );

	}//end is_auto_share_enabled()

	/**
	 * Returns the auto share end mode.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string the auto share end mode.
	 *
	 * @since  2.2.8
	 * @access public
	 */
	public function get_auto_share_end_mode( $post_id ) {
		$end_mode  = get_post_meta( $post_id, '_nc_auto_share_end_mode', true );
		$end_modes = wp_list_pluck( nc_get_auto_share_end_modes(), 'value' );
		if ( ! in_array( $end_mode, $end_modes, true ) ) {
			$end_mode = 'never';
		}//end if
		return empty( $end_mode ) ? 'never' : $end_mode;
	}//end get_auto_share_end_mode()

	/**
	 * Updates the auto share end mode.
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $end_mode New end mode.
	 *
	 * @since  2.2.8
	 * @access public
	 */
	public function update_auto_share_end_mode( $post_id, $end_mode ) {
		if ( 'never' === $end_mode ) {
			delete_post_meta( $post_id, '_nc_auto_share_end_mode' );
		} else {
			update_post_meta( $post_id, '_nc_auto_share_end_mode', $end_mode );
		}//end if
	}//end update_auto_share_end_mode()

	/**
	 * Returns the date in which auto share is going to end (if any), `never` if it will never end, or `unknown` otherwise.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return string the date in which the auto share ends/ended.
	 *
	 * @since  2.2.8
	 * @access public
	 */
	public function get_auto_share_end_date( $post_id ) {
		if ( ! in_array( get_post_status( $post_id ), array( 'publish', 'future' ), true ) ) {
			return 'unknown';
		}//end if

		$end_mode = $this->get_auto_share_end_mode( $post_id );
		if ( 'never' === $end_mode ) {
			return 'never';
		}//end if

		$end_mode = str_replace( '-', ' ', $end_mode );
		$pub_date = get_the_date( 'Y-m-d', $post_id );
		return gmdate( 'Y-m-d', strtotime( "{$pub_date} + {$end_mode}" ) );
	}//end get_auto_share_end_date()

	/**
	 * Sets users to follow specified post.
	 *
	 * @param int   $post_id     ID of the post.
	 * @param array $suggestions URLs of suggested references.
	 * @param array $included    URLs of included references.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function update_post_references( $post_id, $suggestions, $included ) {

		// 1. SUGGESTED REFERENCES
		$suggestions     = wp_list_pluck( array_map( 'nc_create_reference', $suggestions ), 'ID' );
		$old_suggestions = nc_get_post_reference( $post_id, 'suggested' );

		$new_suggestions = array_diff( $suggestions, $old_suggestions );
		foreach ( $new_suggestions as $ref_id ) {
			nc_suggest_post_reference( $post_id, $ref_id, get_current_user_id() );
		}//end foreach

		$invalid_suggestions = array_diff( $old_suggestions, $suggestions );
		foreach ( $invalid_suggestions as $ref_id ) {
			nc_discard_post_reference( $post_id, $ref_id );
		}//end foreach

		// 2. INCLUDED REFERENCES
		$included     = wp_list_pluck( array_map( 'nc_create_reference', $included ), 'ID' );
		$old_included = nc_get_post_reference( $post_id, 'included' );

		$new_included = array_diff( $included, $old_included );
		foreach ( $new_included as $ref_id ) {
			nc_add_post_reference( $post_id, $ref_id );
		}//end foreach

		$invalid_included = array_diff( $old_included, $included );
		foreach ( $invalid_included as $ref_id ) {
			nc_delete_post_reference( $post_id, $ref_id );
		}//end foreach
	}//end update_post_references()

	/**
	 * Sets users to follow specified post.
	 *
	 * @param int   $post_id ID of the post.
	 * @param array $users   User IDs that follow the post.
	 *
	 * @return boolean true on success and false on failure
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function save_post_followers( $post_id, $users ) {

		if ( ! is_array( $users ) ) {
			$users = array();
		}//end if

		$users = array_values( array_filter( array_unique( array_map( 'absint', $users ) ) ) );
		return nc_update_post_meta_array( $post_id, '_nc_following_users', $users );

	}//end save_post_followers()

	/**
	 * This function creates a ncselect2-ready object with (a) the current post
	 * in the loop or (b) the post specified in `$post_id`.
	 *
	 * @param WP_Post|integer $post The post we want to stringify (or its ID).
	 *
	 * @return array a ncselect2-ready object with (a) the current post in the
	 *               loop or (b) the post specified in `$post_id`.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function post_to_json( $post ) {

		if ( is_int( $post ) ) {
			$post = get_post( $post );
		}//end if

		if ( is_wp_error( $post ) || ! $post ) {
			return false;
		}//end if

		require_once ABSPATH . '/wp-admin/includes/post.php';
		$analytics = Nelio_Content_Analytics_Helper::instance();
		$result    = array(
			'id'                 => $post->ID,
			'author'             => absint( $post->post_author ),
			'authorName'         => $this->get_the_author( $post ),
			'customFields'       => $this->get_custom_fields( $post->ID, $post->post_type ),
			'customPlaceholders' => $this->get_custom_placeholders( $post->ID, $post->post_type ),
			'date'               => $this->get_post_time( $post ),
			'editLink'           => $this->get_edit_post_link( $post ),
			'excerpt'            => $this->get_the_excerpt( $post ),
			'followers'          => $this->get_post_followers( $post ),
			'imageId'            => $this->get_post_thumbnail_id( $post ),
			'imageSrc'           => $this->get_post_thumbnail( $post, false ),
			'images'             => $this->get_images( $post ),
			'permalink'          => $this->get_permalink( $post ),
			'permalinkTemplate'  => get_sample_permalink( $post->ID, $this->get_the_title( $post ), '' )[0],
			'statistics'         => $analytics->get_post_stats( $post->ID ),
			'status'             => $post->post_status,
			'taxonomies'         => $this->get_taxonomies( $post ),
			'thumbnailSrc'       => $this->get_featured_thumb( $post ),
			'title'              => $this->get_the_title( $post ),
			'type'               => $post->post_type,
			'typeName'           => $this->get_post_type_name( $post ),
			'viewLink'           => get_permalink( $post ),
		);

		return $result;

	}//end post_to_json()

	/**
	 * This function creates an AWS-ready post object.
	 *
	 * @param integer $post_id The ID of the post we want to stringify.
	 *
	 * @return array an AWS-ready post object.
	 *
	 * @since  1.4.5
	 * @access public
	 */
	public function post_to_aws_json( $post_id ) {

		$result = $this->post_to_json( $post_id );
		if ( ! $result ) {
			return false;
		}//end if

		$post = get_post( $post_id );

		unset( $result['followers'] );
		unset( $result['statistics'] );

		$result = array_merge(
			$result,
			array(
				'autoShareEndMode'    => $this->get_auto_share_end_mode( $post->ID ),
				'automationSources'   => $this->get_automation_sources( $post->ID ),
				'content'             => $this->get_the_content( $post ),
				'date'                => ! empty( $result['date'] ) ? $result['date'] : 'none',
				'excludedFromReshare' => ! $this->is_auto_share_enabled( $post->ID ),
				'featuredImage'       => $this->get_post_thumbnail( $post, 'none' ),
				'highlights'          => $this->get_post_highlights( $post->ID ),
				'isAutoShareEnabled'  => $this->is_auto_share_enabled( $post->ID ),
				'references'          => $this->get_external_references( $post ),
				'timezone'            => nc_get_timezone(),
				'networkImages'       => $this->get_network_images( $post ),
				'permalinks'          => $this->get_network_permalinks( $post ),
			)
		);

		return $result;

	}//end post_to_aws_json()

	/**
	 * This function returns whether the given post has changed since the last update or not.
	 *
	 * @param integer $post_id the post ID.
	 *
	 * @return boolean whether the post has changed since the last update or not.
	 *
	 * @since  3.0.0
	 * @access public
	 */
	public function has_relevant_changes( $post_id ) {

		$new_hash = $this->get_post_hash( $post_id );
		if ( empty( $new_hash ) ) {
			return false;
		}//end if

		$new_full_hash  = $new_hash['full_hash'];
		$new_force_hash = $new_hash['force_synch_hash'];

		$old_hash       = get_post_meta( $post_id, '_nc_sync_hash', true );
		$old_full_hash  = isset( $old_hash['full_hash'] ) ? $old_hash['full_hash'] : '';
		$old_force_hash = isset( $old_hash['force_synch_hash'] ) ? $old_hash['force_synch_hash'] : '';

		if ( $new_force_hash !== $old_force_hash ) {
			return true;
		}//end if

		if ( $new_full_hash === $old_full_hash ) {
			return false;
		}//end if

		$recent = get_transient( "nc_recent_sync_{$post_id}" );
		return empty( $recent );

	}//end has_relevant_changes()

	/**
	 * This function adds a custom meta so that we know that the post, as is right now, has been synched with AWS.
	 *
	 * @param integer $post_id the post ID.
	 *
	 * @since  1.6.8
	 * @access public
	 */
	public function mark_post_as_synched( $post_id ) {

		$hash = $this->get_post_hash( $post_id );
		if ( $hash ) {
			update_post_meta( $post_id, '_nc_sync_hash', $hash );
			set_transient( "nc_recent_sync_{$post_id}", true, 15 * MINUTE_IN_SECONDS );
		}//end if

	}//end mark_post_as_synched()

	/**
	 * Returns the list of post followers for the given post
	 *
	 * @param int|WP_Post $post_id the ID of the post whose followers we want.
	 *
	 * @return array the list of post followers for the given post
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function get_post_followers( $post_id ) {

		if ( $post_id instanceof WP_Post ) {
			$post_id = $post_id->ID;
		}//end if

		if ( empty( $post_id ) ) {
			return array();
		}//end if

		$follower_ids = get_post_meta( $post_id, '_nc_following_users', false );
		if ( ! is_array( $follower_ids ) ) {
			$follower_ids = array();
		}//end if

		return array_values( array_unique( array_map( 'absint', $follower_ids ) ) );

	}//end get_post_followers()

	/**
	 * Returns the automation sources.
	 *
	 * @param int|WP_Post $post_id The ID of the post.
	 *
	 * @return array the automation sources.
	 *
	 * @since  2.2.6
	 * @access public
	 */
	public function get_automation_sources( $post_id ) {

		if ( $post_id instanceof WP_Post ) {
			$post_id = $post_id->ID;
		}//end if

		$sources = get_post_meta( $post_id, '_nc_automation_sources', true );
		return $this->fix_automation_sources( $sources, get_post_type( $post_id ) );
	}//end get_automation_sources()

	/**
	 * Updates the automation sources meta.
	 *
	 * @param int|WP_Post $post_id Post ID.
	 * @param array       $sources New automation sources value.
	 *
	 * @since  2.2.6
	 * @access public
	 */
	public function update_automation_sources( $post_id, $sources ) {
		if ( $post_id instanceof WP_Post ) {
			$post_id = $post_id->ID;
		}//end if
		$sources = $this->fix_automation_sources( $sources, get_post_type( $post_id ) );
		update_post_meta( $post_id, '_nc_automation_sources', $sources );
	}//end update_automation_sources()

	/**
	 * Returns the post highlights.
	 *
	 * @param int|WP_Post $post_id The ID of the post.
	 *
	 * @return array Post higlights.
	 *
	 * @since  2.2.6
	 * @access public
	 */
	public function get_post_highlights( $post_id ) {
		if ( $post_id instanceof WP_Post ) {
			$post_id = $post_id->ID;
		}//end if
		$highlights = get_post_meta( $post_id, '_nc_post_highlights', true );
		return is_array( $highlights ) ? $highlights : array();
	}//end get_post_highlights()

	/**
	 * Updates the post highlights.
	 *
	 * @param int|WP_Post $post_id    Post ID.
	 * @param array       $highlights New post highlights.
	 *
	 * @since  2.2.6
	 * @access public
	 */
	public function update_post_highlights( $post_id, $highlights ) {
		if ( ! is_array( $highlights ) ) {
			return;
		}//end if
		if ( $post_id instanceof WP_Post ) {
			$post_id = $post_id->ID;
		}//end if
		update_post_meta( $post_id, '_nc_post_highlights', $highlights );
	}//end update_post_highlights()

	public function get_supported_custom_fields_in_templates() {
		$settings = Nelio_Content_Settings::instance();
		$types    = $settings->get( 'calendar_post_types' );
		$types    = is_array( $types ) ? $types : array();
		$fields   = array_map( array( $this, 'get_supported_custom_fields' ), $types );
		return array_combine( $types, $fields );
	}//end get_supported_custom_fields_in_templates()

	public function get_supported_custom_placeholders_in_templates() {
		$settings     = Nelio_Content_Settings::instance();
		$types        = $settings->get( 'calendar_post_types' );
		$types        = is_array( $types ) ? $types : array();
		$placeholders = array_map( array( $this, 'get_supported_custom_placeholders' ), $types );
		$placeholders = json_decode( wp_json_encode( $placeholders ), ARRAY_A );
		return array_combine( $types, $placeholders );
	}//end get_supported_custom_placeholders_in_templates()

	private function get_post_hash( $post_id ) {

		$post = $this->post_to_aws_json( $post_id );
		if ( ! $post ) {
			return false;
		}//end if

		unset( $post['content'] );
		$post = array_map(
			function( $value ) {
				if ( is_array( $value ) ) {
					sort( $value );
				}//end if
				return $value;
			},
			$post
		);

		$post['date'] = substr( $post['date'], 0, strlen( 'YYYY-MM-DDThh:mm' ) );

		$relevant_attributes = array(
			'date'    => $post['date'],
			'excerpt' => $post['excerpt'],
			'status'  => $post['status'],
			'title'   => $post['title'],
		);

		$encoded_post  = wp_json_encode( $post );
		$encoded_post  = empty( $encoded_post ) ? '' : $encoded_post;
		$encoded_attrs = wp_json_encode( $relevant_attributes );
		$encoded_attrs = empty( $encoded_attrs ) ? '' : $encoded_attrs;

		return array(
			'full_hash'        => md5( $encoded_post ),
			'force_synch_hash' => md5( $encoded_attrs ),
		);

	}//end get_post_hash()

	private function get_the_author( $post ) {

		$name = get_the_author_meta( 'display_name', $post->post_author );

		/**
		 * Filters the post’s author name.
		 *
		 * @param string  $name Name of the author.
		 * @param WP_Post $post Post object.
		 *
		 * @since 2.2.4
		 */
		return apply_filters( 'nelio_content_post_author_name', $name, $post );

	}//end get_the_author()

	private function get_post_thumbnail( $post, $default ) {

		$featured_image = wp_get_attachment_url( $this->get_post_thumbnail_id( $post ) );

		$settings = Nelio_Content_Settings::instance();
		if ( $settings->get( 'use_external_featured_image' ) && empty( $featured_image ) ) {
			$efi_helper      = Nelio_Content_External_Featured_Image_Helper::instance();
			$featured_image  = $efi_helper->get_external_featured_image( $post->ID );
			$auto_feat_image = $settings->get( 'auto_feat_image' );
			if ( empty( $featured_image ) && 'disabled' !== $auto_feat_image ) {
				$featured_image = $efi_helper->get_auto_featured_image( $post->ID, $auto_feat_image );
			}//end if
		}//end if

		return empty( $featured_image ) ? $default : $featured_image;
	}//end get_post_thumbnail()

	private function get_featured_thumb( $post ) {

		$default        = Nelio_Content()->plugin_url . '/assets/dist/images/default-featured-image-thumbnail.png';
		$featured_image = wp_get_attachment_thumb_url( $this->get_post_thumbnail_id( $post ) );

		$settings = Nelio_Content_Settings::instance();
		if ( $settings->get( 'use_external_featured_image' ) && empty( $featured_image ) ) {
			$efi_helper      = Nelio_Content_External_Featured_Image_Helper::instance();
			$featured_image  = $efi_helper->get_external_featured_image( $post->ID );
			$auto_feat_image = $settings->get( 'auto_feat_image' );
			if ( empty( $featured_image ) && 'disabled' !== $auto_feat_image ) {
				$featured_image = $efi_helper->get_auto_featured_image( $post->ID, $auto_feat_image );
			}//end if
		}//end if

		return empty( $featured_image ) ? $default : $featured_image;

	}//end get_featured_thumb()

	private function get_post_thumbnail_id( $post ) {

		$post_thumbnail_id = get_post_meta( $post->ID, '_thumbnail_id', true );
		if ( empty( $post_thumbnail_id ) ) {
			$post_thumbnail_id = 0;
		}//end if

		return absint( $post_thumbnail_id );

	}//end get_post_thumbnail_id()

	private function get_post_type_name( $post ) {

		$post_type_name = _x( 'Post', 'text (default post type name)', 'nelio-content' );
		$post_type      = get_post_type_object( $post->post_type );
		if ( ! empty( $post_type ) && isset( $post_type->labels ) && isset( $post_type->labels->singular_name ) ) {
			$post_type_name = $post_type->labels->singular_name;
		}//end if

		return $post_type_name;

	}//end get_post_type_name()

	private function get_the_title( $post ) {

		/**
		 * Modifies the title of the post.
		 *
		 * @param string $title   the title.
		 * @param int    $post_id the ID of the post.
		 *
		 * @since 1.0.0
		 */
		$title = apply_filters( 'nelio_content_post_title', apply_filters( 'the_title', $post->post_title, $post->ID ), $post->ID );

		return html_entity_decode( wp_strip_all_tags( $title ), ENT_HTML5 );

	}//end get_the_title()

	private function get_the_excerpt( $post ) {

		if ( ! empty( $post->post_excerpt ) ) {
			$excerpt = $post->post_excerpt;
		} else {
			$excerpt = '';
		}//end if

		/**
		 * Modifies the excerpt of the post.
		 *
		 * @param string $excerpt the excerpt.
		 * @param int    $post_id the ID of the post.
		 *
		 * @since 1.0.0
		 */
		$excerpt = apply_filters( 'nelio_content_post_excerpt', $excerpt, $post->ID );

		return html_entity_decode( wp_strip_all_tags( $excerpt ), ENT_HTML5 );

	}//end get_the_excerpt()

	private function get_post_time( $post ) {

		$date = ' ' . $post->post_date_gmt;
		if ( strpos( $date, '0000-00-00' ) ) {
			return false;
		}//end if

		$timezone = date_default_timezone_get();
		date_default_timezone_set( 'UTC' ); // phpcs:ignore
		$date = get_post_time( 'c', true, $post );
		date_default_timezone_set( $timezone ); // phpcs:ignore

		$has_seconds = strlen( $date ) >= 19;
		if ( $has_seconds ) {
			$date[17] = '0';
			$date[18] = '0';
		}//end if

		return $date;

	}//end get_post_time()

	private function get_edit_post_link( $post ) {

		$link = get_edit_post_link( $post->ID, 'default' );
		if ( empty( $link ) ) {
			$link = '';
		}//end if

		return $link;

	}//end get_edit_post_link()

	private function get_permalink( $post ) {

		$permalink = get_permalink( $post );
		if ( 'publish' !== $post->post_status ) {
			$aux              = clone $post;
			$aux->post_status = 'publish';
			if ( empty( $aux->post_name ) ) {
				$aux->post_name = sanitize_title( $aux->post_title, $aux->ID );
			}//end if
			$aux->post_name = wp_unique_post_slug( $aux->post_name, $aux->ID, 'publish', $aux->post_type, $aux->post_parent );
			$permalink      = get_permalink( $aux );
		}//end if

		/**
		 * Filters the permalink that will be used when sharing the post on social media.
		 *
		 * @param string $permalink the post permalink.
		 * @param id     $post_id   the post ID.
		 *
		 * @since 1.3.6
		 */
		$permalink = apply_filters( 'nelio_content_post_permalink', $permalink, $post->ID );

		return $permalink;

	}//end get_permalink()

	private function is_external_reference( $url, $non_ref_domains ) {

		// Internal URLs are not external.
		if ( 0 === strpos( $url, get_home_url() ) ) {
			return false;
		}//end if

		// Discard any URL that is an external reference.
		foreach ( $non_ref_domains as $pattern ) {
			if ( preg_match( $pattern, $url ) ) {
				return false;
			}//end if
		}//end foreach

		return true;

	}//end is_external_reference()

	private function get_the_content( $post ) {

		$aux = Nelio_Content_Public::instance();
		remove_filter( 'the_content', array( $aux, 'remove_share_blocks' ), 99 );
		$content = apply_filters( 'the_content', $post->post_content );
		/**
		 * Filters the content used in Nelio Content for a given post.
		 *
		 * This is specially useful to ACF users who don’t use the
		 * `the_content` filter to render the content of their posts.
		 *
		 * @param string  $content   the post content.
		 * @param integer $post_id   the post ID.
		 *
		 * @since 2.3.1
		 */
		return apply_filters( 'the_nelio_content', $content, $post->ID );

	}//end get_the_content()

	private function get_images( $post ) {

		$content = $this->get_the_content( $post );
		preg_match_all( '/<img[^>]+>/i', $content, $matches );

		$result = array();
		foreach ( $matches[0] as $img ) {
			$url = $this->get_url_from_image_tag( $img );
			if ( $url ) {
				array_push( $result, $url );
			}//end if
		}//end foreach

		shuffle( $result );
		return array_slice( $result, 0, 10 );

	}//end get_images()

	private function get_url_from_image_tag( $img ) {
		/**
		 * HTML attributes that might contain the actual URL in an image tag.
		 *
		 * @param array $attributes list of attributes. Default: `[ 'src', 'data-src' ]`.
		 *
		 * @since 2.0.0
		 */
		$attributes = apply_filters( 'nelio_content_url_attributes_in_image_tag', array( 'src', 'data-src' ) );

		$attributes = implode( '|', $attributes );
		preg_match_all( '/(' . $attributes . ')=("[^"]*"|\'[^\']*\')/i', $img, $aux );

		if ( count( $aux ) <= 2 ) {
			return false;
		}//end if

		$urls = array_map(
			function( $url ) {
				return substr( $url, 1, strlen( $url ) - 2 );
			},
			$aux[2]
		);

		foreach ( $urls as $url ) {
			if ( preg_match( '/^https?:\/\//', $url ) ) {
				return $url;
			}//end if
		}//end foreach

		return false;
	}//end get_url_from_image_tag()

	private function get_custom_fields( $post_id, $post_type ) {
		$metas = $this->get_supported_custom_fields( $post_type );
		$metas = array_map(
			function( $meta ) use ( $post_id ) {
				return array(
					'key'   => $meta['key'],
					'name'  => $meta['name'],
					'value' => get_post_meta( $post_id, $meta['key'], true ),
				);
			},
			$metas
		);
		return key_by( $metas, 'key' );
	}//end get_custom_fields()

	private function get_custom_placeholders( $post_id, $post_type ) {

		$placeholders = $this->get_supported_custom_placeholders( $post_type );
		$placeholders = array_map(
			function( $placeholder ) use ( $post_id, $post_type ) {
				return array(
					'key'   => $placeholder['key'],
					'name'  => $placeholder['name'],
					'value' => call_user_func( $placeholder['callback'], $post_id, $post_type ),
				);
			},
			$placeholders
		);

		return key_by( $placeholders, 'key' );

	}//end get_custom_placeholders()

	private function get_external_references( $post ) {

		$references = $this->get_references( $post, 'all' );

		$non_ref_domains = $this->get_non_reference_domains();
		$count           = count( $non_ref_domains );
		for ( $i = 0; $i < $count; ++$i ) {
			$pattern               = $non_ref_domains[ $i ];
			$pattern               = str_replace( '.', '\\.', $pattern );
			$pattern               = preg_replace( '/\*$/', '[^\/]+', $pattern );
			$pattern               = str_replace( '*', '[^\/]*', $pattern );
			$pattern               = '/^[^:]+:\/\/[^\/]*' . $pattern . '/';
			$non_ref_domains[ $i ] = $pattern;
		}//end for

		$external_references = array_values(
			array_filter(
				$references,
				function( $reference ) use ( &$non_ref_domains ) {
					return $this->is_external_reference( $reference['url'], $non_ref_domains );
				}
			)
		);

		return array_values(
			array_map(
				function( $reference ) {
					return array(
						'url'     => $reference['url'],
						'author'  => $reference['author'],
						'title'   => $reference['title'],
						'twitter' => $reference['twitter'],
					);
				},
				$external_references
			)
		);
	}//end get_external_references()

	private function fix_automation_sources( $sources, $type ) {
		if ( ! is_array( $sources ) ) {
			$sources = array();
		}//end if

		$defaults = array(
			'useCustomSentences' => false,
			'customSentences'    => array(),
		);

		/**
		 * Filters default automation sources values.
		 *
		 * @param array  $defaults Default automation sources.
		 * @param string $type     Post type.
		 *
		 * @since 2.2.6
		 */
		$filtered_defaults = apply_filters( 'nelio_content_default_automation_sources', $defaults, $type );

		$fix = function( $vals, $defs ) {
			$res = array();
			foreach ( $defs as $key => $defval ) {
				$res[ $key ] = isset( $vals[ $key ] ) ? $vals[ $key ] : $defval;
			}//end foreach
			return $res;
		};

		$sources = $fix( $sources, $fix( $filtered_defaults, $defaults ) );
		if ( empty( $sources['useCustomSentences'] ) ) {
			$sources['customSentences'] = array();
		}//end if

		if ( ! is_array( $sources['customSentences'] ) ) {
			$sources['customSentences'] = str_replace( "\r\n", "\n", $sources['customSentences'] );
			$sources['customSentences'] = explode( "\n", $sources['customSentences'] );
			$sources['customSentences'] = array_values( array_filter( array_map( 'trim', $sources['customSentences'] ) ) );
		}//end if

		return $sources;
	}//end fix_automation_sources()

	private function get_network_images( $post ) {
		$post_id = $post->ID;
		$images  = array_map(
			function( $network ) use ( $post_id ) {
				/**
				 * Sets the exact image that should be used when sharing the post on a certain network.
				 *
				 * Notice that not all messages that Nelio Content generates will contain an image.
				 * This filter only overwrites the shared image on those messages that contain one.
				 *
				 * @param boolean|string $image   The image that should be used. Default: `false` (i.e. “none”).
				 * @param int            $post_id The post that’s about to be shared.
				 *
				 * @since 1.4.5
				 */
				return apply_filters( "nelio_content_{$network}_featured_image", false, $post_id );
			},
			self::$networks
		);
		return array_filter( array_combine( self::$networks, $images ) );
	}//end get_network_images()

	private function get_network_permalinks( $post ) {
		$post_id    = $post->ID;
		$default    = $this->get_permalink( $post );
		$permalinks = array_map(
			function( $network ) use ( $default, $post_id ) {
				$permalink = $default;

				/**
				 * Filters the permalink used in a certain network.
				 *
				 * @param string $permalink The permalink to use in the given network.
				 * @param int    $post_id   The post that’s about to be shared.
				 *
				 * @since 2.3.2
				 */
				$permalink = apply_filters( "nelio_content_post_permalink_on_{$network}", $permalink, $post_id );

				/**
				 * Filters the permalink used in a certain network.
				 *
				 * @param string $permalink The permalink to use in the given network.
				 * @param string $network   The network in which the post will be shared.
				 * @param int    $post_id   The post that’s about to be shared.
				 *
				 * @since 2.3.2
				 */
				$permalink = apply_filters( 'nelio_content_post_permalink_on_network', $permalink, $network, $post_id );

				return $permalink === $default ? false : $permalink;
			},
			self::$networks
		);
		return array_filter( array_combine( self::$networks, $permalinks ) );
	}//end get_network_permalinks()

	private function get_taxonomies( $post ) {
		$taxonomies = array_map( 'get_taxonomy', get_post_taxonomies( $post ) );
		$taxonomies = array_map(
			function( $tax ) {
				return $tax->public && $tax->show_in_rest ? $tax->name : false;
			},
			$taxonomies
		);
		$taxonomies = array_values( array_filter( $taxonomies ) );

		$post_id   = $post->ID;
		$get_terms = function( $tax ) use ( $post_id ) {
			return array_map(
				function( $term ) {
					return array(
						'id'   => $term->term_id,
						'name' => $term->name,
						'slug' => $term->slug,
					);
				},
				wp_get_post_terms( $post_id, $tax )
			);
		};
		$all_terms = array_map( $get_terms, $taxonomies );

		return array_combine( $taxonomies, $all_terms );
	}//end get_taxonomies()

	private function get_supported_custom_fields( $post_type ) {
		/**
		 * List of supported custom fields of a post.
		 *
		 * @param array  $metas List of post meta objects that can be used
		 * as placeholders in the content of social messages. Each item in the
		 * array contains key and name.
		 * @param string $type  post type.
		 *
		 * @since 2.5.0
		 */
		return apply_filters(
			'nelio_content_supported_post_metas',
			array(),
			$post_type
		);
	}//end get_supported_custom_fields()

	private function get_supported_custom_placeholders( $post_type ) {
		/**
		 * List of supported custom placeholders of a post.
		 *
		 * @param array  $placeholders List of custom objects that can be used
		 * as placeholders in the content of social messages. Each item in the
		 * array contains key, name, and a callback function to get the value.
		 * @param string $type  post type.
		 *
		 * @since 2.5.0
		 */
		return apply_filters(
			'nelio_content_custom_placeholders',
			array(),
			$post_type
		);
	}//end get_supported_custom_placeholders()

}//end class
