<?php
/**
 * Abstract factory class.
 *
 * @package UpStream
 */

namespace UpStream;

use Upstream_Counter;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract factory class.
 *
 * @since   1.24.0
 */
abstract class Factory {
	/**
	 * Counters.
	 *
	 * @var array
	 */
	protected static $counters = array();

	/**
	 * Create milestone data.
	 *
	 * @param string $name Milestone name.
	 *
	 * @return Milestone
	 */
	public static function create_milestone( $name ) {
		$post_id = wp_insert_post(
			array(
				'post_type'   => Milestone::POST_TYPE,
				'post_title'  => sanitize_text_field( $name ),
				'post_status' => 'publish',
			)
		);

		return self::get_milestone( $post_id );
	}

	/**
	 * Get milestone data.
	 *
	 * @param int|\WP_Post $post WP post data.
	 *
	 * @return Milestone
	 */
	public static function get_milestone( $post ) {
		return new Milestone( $post );
	}

	/**
	 * Get project activity.
	 *
	 * @return \UpStream_Project_Activity
	 */
	public static function get_activity() {
		return \UpStream_Project_Activity::getInstance();
	}

	/**
	 * Get project counter
	 *
	 * @param int|array $project_ids Project IDs.
	 *
	 * @return Upstream_Counter
	 */
	public static function get_project_counter( $project_ids ) {
		if ( ! isset( self::$counters[ $project_ids ] ) ) {
			self::$counters[ $project_ids ] = new Upstream_Counter( $project_ids );
		}

		return self::$counters[ $project_ids ];
	}
}
