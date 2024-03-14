<?php
/**
 * UpStream_View
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use UpStream\Traits\Singleton;

/**
 * Class UpStream_View
 *
 * @since   1.15.0
 */
class UpStream_View {

	use Singleton;

	/**
	 * Project
	 *
	 * @var undefined
	 */
	protected static $project = null;

	/**
	 * Milestones
	 *
	 * @var array
	 */
	protected static $milestones = array();


	/**
	 * Tasks
	 *
	 * @var array
	 */
	protected static $tasks = array();

	/**
	 * Users
	 *
	 * @var array
	 */
	protected static $users = array();

	/**
	 * Construct
	 *
	 * @return void
	 */
	public function __construct() {
		self::$namespace = get_class(
			empty( self::$instance )
				? $this
				: self::$instance
		);
	}

	/**
	 * Get Milestones
	 *
	 * @param  int $project_id Project Id.
	 */
	public static function get_milestones( $project_id = 0 ) {
		return \UpStream\Milestones::getInstance()->get_milestones_as_rowset( $project_id );
	}

	/**
	 * Get Project
	 *
	 * @param  int $id Project ID.
	 */
	public static function get_project( $id = 0 ) {
		if ( empty( $project ) ) {
			self::set_project( $id );
		}

		return self::$project;
	}

	/**
	 * Set Project
	 *
	 * @param  int $id Project ID.
	 * @return void
	 */
	public static function set_project( $id = 0 ) {
		self::$project = new UpStream_Project( $id );
	}

	/**
	 * Get Time Zone Offset
	 */
	public static function get_time_zone_offset() {
		$offset              = get_option( 'gmt_offset' );
		$sign                = $offset < 0 ? '-' : '+';
		$hours               = (int) $offset;
		$minutes             = abs( ( $offset - (int) $offset ) * 60 );
		$offset              = (int) sprintf( '%s%d%02d', $sign, abs( $hours ), $minutes );
		$calc_offset_seconds = $offset < 0 ? $offset * -1 * 60 : $offset * 60;
		return (int) ( $calc_offset_seconds );
	}

	/**
	 * Get Tasks
	 *
	 * @param  int $project_id Project Id.
	 */
	public static function get_tasks( $project_id = 0 ) {
		$project = self::get_project( $project_id );

		if ( count( self::$tasks ) === 0 ) {
			$data    = array();
			$frowset = array_filter( (array) $project->get_meta( 'tasks' ) );

			$rowset = array();
			foreach ( $frowset as $row ) {
				if ( upstream_override_access_object( true, UPSTREAM_ITEM_TYPE_TASK, $row['id'], UPSTREAM_ITEM_TYPE_PROJECT, $project_id, UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
					$rowset[] = $row;
				}
			}

			$statuses = upstream_get_tasks_statuses();

			foreach ( $rowset as $row ) {
				$row['created_by']   = (int) $row['created_by'];
				$row['created_time'] = isset( $row['created_time'] ) ? (int) $row['created_time'] : 0;
				$assignees           = array();
				if ( isset( $row['assigned_to'] ) ) {
					$assignees = array_map(
						'intval',
						! is_array( $row['assigned_to'] ) ? (array) $row['assigned_to'] : $row['assigned_to']
					);
				}

				$row['assigned_to'] = $assignees;

				if ( ! empty( $assignees ) ) {
					// Get the name of assignees to fix ordering.
					$row['assigned_to_order'] = upstream_get_users_display_name( $assignees );
				}

				$row['status_order'] = isset( $row['status'] ) ? @$statuses[ $row['status'] ]['order'] : '0';
				$row['progress']     = isset( $row['progress'] ) ? (float) $row['progress'] : 0.00;
				$row['notes']        = isset( $row['notes'] ) ? (string) $row['notes'] : '';

				$row['start_date'] = ! isset( $row['start_date'] ) || ! is_numeric( $row['start_date'] ) || $row['start_date'] < 0 ? 0 : (int) $row['start_date'];// + self::get_time_zone_offset();
				$row['end_date']   = ! isset( $row['end_date'] ) || ! is_numeric( $row['end_date'] ) || $row['end_date'] < 0 ? 0 : (int) $row['end_date'];// + self::get_time_zone_offset();

				$data[ $row['id'] ] = $row;
			}

			self::$tasks = $data;
		} else {
			$data = self::$tasks;
		}

		return $data;
	}

	/**
	 * Get Bugs
	 *
	 * @param  int $project_id Project Id.
	 */
	public static function get_bugs( $project_id = 0 ) {
		$rowset = array();

		$severities = upstream_get_bugs_severities();
		$statuses   = upstream_get_bugs_statuses();

		$fmeta = (array) get_post_meta( $project_id, '_upstream_project_bugs', true );

		$meta = array();
		foreach ( $fmeta as $row ) {
			if ( ! isset( $row['id'] ) ) {
				continue;
			}
			if ( upstream_override_access_object( true, UPSTREAM_ITEM_TYPE_BUG, $row['id'], UPSTREAM_ITEM_TYPE_PROJECT, $project_id, UPSTREAM_PERMISSIONS_ACTION_VIEW ) ) {
				$meta[] = $row;
			}
		}

		foreach ( $meta as $data ) {
			if ( ! isset( $data['id'] ) || ! isset( $data['created_by'] ) ) {
				continue;
			}

			$data['created_by']   = (int) $data['created_by'];
			$data['created_time'] = isset( $data['created_time'] ) ? (int) $data['created_time'] : 0;

			$assignees = array();
			if ( isset( $data['assigned_to'] ) ) {
				$assignees = array_map(
					'intval',
					! is_array( $data['assigned_to'] ) ? (array) $data['assigned_to'] : $data['assigned_to']
				);
			}

			$data['assigned_to'] = $assignees;

			if ( ! empty( $assignees ) ) {
				// Get the name of assignees to fix ordering.
				$data['assigned_to_order'] = upstream_get_users_display_name( $assignees );
			}

			$data['description']    = isset( $data['description'] ) ? (string) $data['description'] : '';
			$data['severity']       = isset( $data['severity'] ) ? (string) $data['severity'] : '';
			$data['severity_order'] = isset( $data['severity'] ) ? @$severities[ $data['severity'] ]['order'] : '0';
			$data['status']         = isset( $data['status'] ) ? (string) $data['status'] : '';
			$data['status_order']   = isset( $data['status'] ) ? @$statuses[ $data['status'] ]['order'] : '0';
			$data['start_date']     = ! isset( $data['start_date'] ) || ! is_numeric( $data['start_date'] ) || $data['start_date'] < 0 ? 0 : (int) $data['start_date'];// + self::get_time_zone_offset();
			$data['end_date']       = ! isset( $data['end_date'] ) || ! is_numeric( $data['end_date'] ) || $data['end_date'] < 0 ? 0 : (int) $data['end_date'];// + self::get_time_zone_offset();

			$rowset[ $data['id'] ] = $data;
		}

		return $rowset;
	}

	/**
	 * Get Users
	 */
	protected static function get_users() {
		if ( count( self::$users ) === 0 ) {
			self::$users = upstream_get_users_map();
		}

		return self::$users;
	}
}
