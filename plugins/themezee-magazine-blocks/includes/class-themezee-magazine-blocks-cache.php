<?php
/**
 * Transient caching complex database queries for more performance.
 *
 * @package ThemeZee Magazine Blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ThemeZee Magazine Blocks Magazine Grid Class
 */
class ThemeZee_Magazine_Blocks_Cache {
	/**
	 * Setup the class
	 *
	 * @return void
	 */
	static function setup() {
		// Flush Cache if needed.
		add_action( 'save_post', array( __CLASS__, 'flush_cached_post_ids' ) );
		add_action( 'deleted_post', array( __CLASS__, 'flush_cached_post_ids' ) );
		add_action( 'customize_save_after', array( __CLASS__, 'flush_cached_post_ids' ) );
		add_action( 'switch_theme', array( __CLASS__, 'flush_cached_post_ids' ) );
	}

	/**
	 * Delete Cached Post IDs
	 *
	 * @return void
	 */
	static function flush_cached_post_ids() {
		delete_transient( 'themezee_magazine_blocks_cached_post_ids' );
	}

	/**
	 * Query Posts from cache or database.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return array Returns the post query.
	 */
	static function query_posts( $attributes ) {
		// Get post ids from cache or database.
		$post_ids = self::get_post_ids( $attributes );

		// Set query arguments.
		$query_arguments = array(
			'post__in'            => $post_ids,
			'posts_per_page'      => absint( $attributes['numberOfPosts'] ),
			'order'               => esc_attr( $attributes['order'] ),
			'orderby'             => esc_attr( $attributes['orderBy'] ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		// Fetch posts from database.
		$posts_query = new WP_Query( $query_arguments );

		return $posts_query;
	}

	/**
	 * Get Post IDs.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return array Returns the post ids.
	 */
	static function get_post_ids( $attributes ) {

		// Generate Cache ID.
		$cache_id = self::get_cache_id( $attributes );

		// Get cached post ids.
		$cached_post_ids = get_transient( 'themezee_magazine_blocks_cached_post_ids' );
		//$cached_post_ids = null; // Uncomment this to debug.

		if ( ! isset( $cached_post_ids[ $cache_id ] ) || is_customize_preview() ) {

			// Get query arguments.
			$query_arguments = self::get_query_arguments( $attributes );

			// Get Posts from Database.
			$query = new WP_Query( $query_arguments );

			// Create an array of all post ids.
			$cached_post_ids[ $cache_id ] = $query->posts;

			// Set Transient.
			set_transient( 'themezee_magazine_blocks_cached_post_ids', $cached_post_ids );
		}

		return apply_filters( 'themezee_magazine_blocks_cached_post_ids', $cached_post_ids[ $cache_id ], $cache_id );
	}

	/**
	 * Get Query Arguments.
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return array Returns query arguments.
	 */
	static function get_query_arguments( $attributes ) {
		$query_arguments = array(
			'fields'              => 'ids',
			'posts_per_page'      => absint( $attributes['numberOfPosts'] ),
			'post_status'         => 'publish',
			'order'               => esc_attr( $attributes['order'] ),
			'orderby'             => esc_attr( $attributes['orderBy'] ),
			'suppress_filters'    => false,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		if ( isset( $attributes['categories'] ) ) {
			if ( is_string( $attributes['categories'] ) ) {
				$attributes['categories'] = (array) $attributes['categories'];
			}

			$query_arguments['category__in'] = array_map( 'intval', $attributes['categories'] );
		}

		if ( isset( $attributes['tags'] ) ) {
			$query_arguments['tag'] = esc_attr( $attributes['tags'] );
		}

		if ( isset( $attributes['author'] ) ) {
			$query_arguments['author'] = intval( $attributes['author'] );
		}

		if ( isset( $attributes['offset'] ) && $attributes['offset'] > 0 ) {
			$query_arguments['offset'] = absint( $attributes['offset'] );
		}

		return $query_arguments;
	}

	/**
	 * Get Cache ID
	 *
	 * @param array $attributes The block attributes.
	 *
	 * @return string Returns cache id.
	 */
	static function get_cache_id( $attributes ) {

		// Add query arguments to cache id string.
		$cache_id = (string) intval( $attributes['numberOfPosts'] ) . esc_attr( $attributes['order'] ) . esc_attr( $attributes['orderBy'] );

		if ( isset( $attributes['categories'] ) ) {
			$cache_id .= implode( array_map( 'intval', (array) $attributes['categories'] ) );
		}

		if ( isset( $attributes['tags'] ) ) {
			$cache_id .= esc_attr( $attributes['tags'] );
		}

		if ( isset( $attributes['author'] ) ) {
			$cache_id .= (string) intval( $attributes['author'] );
		}

		if ( isset( $attributes['offset'] ) && $attributes['offset'] > 0 ) {
			$cache_id .= (string) intval( $attributes['offset'] );
		}

		return sanitize_key( $cache_id );
	}
}

// Run Class.
ThemeZee_Magazine_Blocks_Cache::setup();
