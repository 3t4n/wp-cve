<?php
/**
 * Project functions.
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Upstream_project_status
 *
 * @param int $id Project id.
 */
function upstream_project_status( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_meta( 'status' );

	return apply_filters( 'upstream_project_status', $result, $id );
}

/**
 * Upstream_project_statuses_colors
 */
function upstream_project_statuses_colors() {
	$option = get_option( 'upstream_projects' );
	$colors = wp_list_pluck( $option['statuses'], 'color', 'id' );

	return apply_filters( 'upstream_project_statuses_colors', $colors );
}

/**
 * Upstream_get_all_project_statuses
 */
function upstream_get_all_project_statuses() {
	$data = Upstream_Cache::get_instance()->get( 'upstream_get_all_project_statuses' );

	if ( false === $data ) {
		$data = array();

		$rowset = get_option( 'upstream_projects' );
		foreach ( $rowset['statuses'] as $status ) {
			$data[ $status['id'] ] = $status;
		}

		Upstream_Cache::get_instance()->set( 'upstream_get_all_project_statuses', $data );

	}

	return $data;
}

/**
 * Upstream_get_open_project_status_ids
 */
function upstream_get_open_project_status_ids() {
	$all_statuses    = upstream_get_all_project_statuses();
	$open_status_ids = array();

	foreach ( $all_statuses as $key => $status ) {
		if ( 'open' === $status['type'] ) {
			$open_status_ids[] = $status['id'];
		}
	}

	return $open_status_ids;
}

/**
 * Upstream_project_status_color
 *
 * @param int $project_id Project id.
 */
function upstream_project_status_color( $project_id = 0 ) {
	$status = array(
		'status' => '',
		'color'  => '#aaa',
	);

	$project_status_id = (string) upstream_project_status( $project_id );
	if ( ! empty( $project_status_id ) ) {
		$rowset = get_option( 'upstream_projects' );
		if ( ! empty( $rowset )
		&& ! empty( $rowset['statuses'] )
		) {
			foreach ( $rowset['statuses'] as $row ) {
				if ( isset( $row['id'] )
					&& $row['id'] === $project_status_id &&
					isset( $row['name'] ) && isset( $row['color'] )
				) {
					$status['status'] = $row['name'];
					$status['color']  = $row['color'];
					break;
				}
			}
		}
	}

	return apply_filters( 'upstream_project_status_color', $status );
}

/**
 * Upstream_project_status_type
 *
 * @param int $id Project id.
 */
function upstream_project_status_type( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_project_status_type();

	return apply_filters( 'upstream_project_status_type', $result );
}

/**
 * Upstream_project_progress
 *
 * @param int $id Project id.
 */
function upstream_project_progress( $id = 0 ) {
	$project  = new UpStream_Project( $id );
	$result   = $project->get_meta( 'progress' );
	$result   = $result ? $result : '0';

	return apply_filters( 'upstream_project_progress', $result, $id );
}

/**
 * Upstream_project_owner_id
 *
 * @param int $id Project id.
 */
function upstream_project_owner_id( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result   = $project->get_meta( 'owner' );

	return apply_filters( 'upstream_project_owner_id', $result, $id );
}

/**
 * Upstream_project_owner_name
 *
 * @param int  $id Project id.
 * @param bool $show_email Show the email or not.
 */
function upstream_project_owner_name( $id = 0, $show_email = false ) {
	$project  = new UpStream_Project( $id );
	$owner_id = $project->get_meta( 'owner' );
	$result   = $owner_id ? upstream_users_name( $owner_id, $show_email ) : null;

	return apply_filters( 'upstream_project_owner_name', $result, $id, $show_email );
}

/**
 * Upstream_project_client_id
 *
 * @param int $id Project id.
 */
function upstream_project_client_id( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_meta( 'client' );

	return apply_filters( 'upstream_project_client_id', $result, $id );
}

/**
 * Upstream_project_client_name
 *
 * @param int $id Project id.
 */
function upstream_project_client_name( $id = 0 ) {
	$res = Upstream_Cache::get_instance()->get( 'upstream_project_client_name' . $id );

	if ( false === $res ) {
		$project = new UpStream_Project( $id );
		$result  = $project->get_client_name();

		$res = apply_filters( 'upstream_project_client_name', $result, $id );
		Upstream_Cache::get_instance()->set( 'upstream_project_client_name' . $id, $res );

	}

	return $res;
}

/**
 * Upstream_project_client_users
 *
 * @param int $id Project id.
 */
function upstream_project_client_users( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = (array) $project->get_meta( 'client_users' );
	$result  = ! empty( $result ) ? array_filter( $result, 'is_numeric' ) : '';

	return apply_filters( 'upstream_project_client_users', $result, $id );
}

/**
 * Upstream_project_members_ids
 *
 * @param int $id Project id.
 */
function upstream_project_members_ids( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_meta( 'members' );

	return apply_filters( 'upstream_project_members_ids', $result, $id );
}

/**
 * Upstream_project_users
 *
 * @param int $id Project id.
 */
function upstream_project_users( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_meta( 'members' );
	$result  = isset( $result ) ? array_filter( $result, 'is_numeric' ) : '';

	return apply_filters( 'upstream_project_users', $result, $id );
}

/**
 * Upstream_project_start_date
 *
 * @param int $id Project id.
 */
function upstream_project_start_date( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_meta( 'start' );

	return apply_filters( 'upstream_project_start_date', $result, $id );
}

/**
 * Upstream_project_end_date
 *
 * @param int $id Project id.
 */
function upstream_project_end_date( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result   = $project->get_meta( 'end' );

	return apply_filters( 'upstream_project_end_date', $result, $id );
}


/**
 * Upstream_project_files
 *
 * @param int $id Project id.
 */
function upstream_project_files( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_meta( 'files' );

	return apply_filters( 'upstream_project_files', $result, $id );
}

/**
 * Upstream_project_description
 *
 * @param int $id Project id.
 */
function upstream_project_description( $id = 0 ) {
	$project = new UpStream_Project( (int) $id );
	$result  = $project->get_meta( 'description' );

	return apply_filters( 'upstream_project_description', $result, $id );
}

/* ------------ MILESTONES -------------- */

/**
 * Upstream_project_milestones
 *
 * @param int $id Project id.
 */
function upstream_project_milestones( $id = 0 ) {
	if ( empty( $id ) ) {
		$id = get_the_ID();
	}

	$result = \UpStream\Milestones::getInstance()->get_milestones_as_rowset( $id );

	return apply_filters( 'upstream_project_milestones', $result, $id );
}

/**
 * Upstream_project_milestone_by_id
 *
 * @param int $id Project id.
 * @param int $milestone_id Milestone id.
 */
function upstream_project_milestone_by_id( $id = 0, $milestone_id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_item_by_id( $milestone_id, 'milestones' );

	return apply_filters( 'upstream_project_milestone_by_id', $result, $id, $milestone_id );
}

/**
 * Upstream_project_milestone_colors
 *
 * @return mixed|void
 * @deprecated Each milestone instance returns its color.
 */
function upstream_project_milestone_colors() {
	return array();
}

/* ------------ TASKS -------------- */

/**
 * Upstream_project_tasks
 *
 * @param int $id Project id.
 */
function upstream_project_tasks( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_meta( 'tasks' );

	return apply_filters( 'upstream_project_tasks', $result, $id );
}

/**
 * Upstream_project_task_by_id
 *
 * @param int $id Project id.
 * @param int $task_id Task id.
 */
function upstream_project_task_by_id( $id = 0, $task_id = 0 ) {
	$project = new UpStream_Project( $id );
	$result   = $project->get_item_by_id( $task_id, 'tasks' );

	return apply_filters( 'upstream_project_task_by_id', $result, $id, $task_id );
}

/**
 * Upstream_project_tasks_counts
 *
 * @param int $id Project id.
 */
function upstream_project_tasks_counts( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_statuses_counts( 'tasks' );

	return apply_filters( 'upstream_project_tasks_statuses_counts', $result, $id );
}

/**
 * Upstream_project_tasks_counts
 */
function upstream_project_task_statuses_colors() {
	$option = get_option( 'upstream_tasks' );
	$colors = wp_list_pluck( $option['statuses'], 'color', 'id' );

	return apply_filters( 'upstream_project_tasks_statuses_colors', $colors );
}

/**
 * Upstream_project_tasks_counts
 *
 * @param int $id Project id.
 * @param int $item_id Item id.
 */
function upstream_project_task_status_color( $id = 0, $item_id ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_item_colors( $item_id, 'tasks', 'status' );

	return apply_filters( 'upstream_project_task_status_color', $result );
}

/* ------------ BUGS -------------- */

/**
 * Upstream_project_bugs
 *
 * @param int $id Project id.
 */
function upstream_project_bugs( $id = 0 ) {
	 $project = new UpStream_Project( $id );
	$result   = $project->get_meta( 'bugs' );

	return apply_filters( 'upstream_project_bugs', $result, $id );
}

/**
 * Upstream_project_bug_by_id
 *
 * @param int $id Project id.
 * @param int $bug_id Bug id.
 */
function upstream_project_bug_by_id( $id = 0, $bug_id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_item_by_id( $bug_id, 'bugs' );

	return apply_filters( 'upstream_project_bug_by_id', $result, $id, $bug_id );
}

/**
 * Upstream_project_bugs_counts
 *
 * @param int $id Project id.
 */
function upstream_project_bugs_counts( $id = 0 ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_statuses_counts( 'bugs' );

	return apply_filters( 'upstream_project_bugs_statuses_counts', $result, $id );
}

/**
 * Upstream_project_bug_statuses_colors
 */
function upstream_project_bug_statuses_colors() {
	$option = get_option( 'upstream_bugs' );
	$colors = wp_list_pluck( $option['statuses'], 'color', 'id' );

	return apply_filters( 'upstream_project_bugs_statuses_colors', $colors );
}

/**
 * Upstream_project_bug_severity_colors
 */
function upstream_project_bug_severity_colors() {
	$option = get_option( 'upstream_bugs' );
	$colors = wp_list_pluck( $option['severities'], 'color', 'id' );

	return apply_filters( 'upstream_project_bugs_severity_colors', $colors );
}

/**
 * Upstream_project_bug_status_color
 *
 * @param int $id Project id.
 * @param int $item_id Item id.
 */
function upstream_project_bug_status_color( $id = 0, $item_id ) {
	$project = new UpStream_Project( $id );
	$result  = $project->get_item_colors( $item_id, 'bugs', 'status' );

	return apply_filters( 'upstream_project_bug_status_color', $result );
}


/**
 * Upstream_project_item_by_id
 *
 * @param int $id Project id.
 * @param int $item_id Item id.
 */
function upstream_project_item_by_id( $id = 0, $item_id = 0 ) {
	$project = new UpStream_Project( $id );
	$result   = $project->get_item_by_id( $item_id, 'milestones' );
	if ( ! $result ) {
		$result = $project->get_item_by_id( $item_id, 'tasks' );
	}
	if ( ! $result ) {
		$result = $project->get_item_by_id( $item_id, 'bugs' );
	}
	if ( ! $result ) {
		$result = $project->get_item_by_id( $item_id, 'files' );
	}
	if ( ! $result ) {
		$result = $project->get_item_by_id( $item_id, 'discussion' );
	}

	return apply_filters( 'upstream_project_item_by_id', $result, $id, $item_id );
}

/* ------------ COUNTS -------------- */


/**
 * Get the count of items for a type.
 *
 * @param string $type Type of item such as bug, task etc.
 * @param int    $id Id of the project you want the count for.
 */
function upstream_count_total( $type, $id = 0 ) {
	if ( ! $id && is_admin() ) {
		$id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;

		// checks if the project ID is valid and accessible.
		if ( ! upstream_user_can_access_project( get_current_user_id(), $id ) ) {
			return 0;
		}
	}

	$count = new Upstream_Counts( $id );

	return $count->total( $type );
}

/**
 * Get the count of OPEN items for a type.
 *
 * @param string $type Type of item such as bug, task etc.
 * @param int    $id Id of the project you want the count for.
 */
function upstream_count_total_open( $type, $id = 0 ) {
	$count = new Upstream_Counts( $id );

	return $count->total_open( $type );
}

/**
 * Get the count of items for a type that is assigned to current user.
 *
 * @param string $type Type of item such as bug, task etc.
 * @param int    $id Id of the project you want the count for.
 */
function upstream_count_assigned_to( $type, $id = 0 ) {
	$count = new Upstream_Counts( $id );

	return $count->assigned_to( $type );
}

/**
 * Get the count of OPEN items for a type that is assigned to current user.
 *
 * @param string $type Type of item such as bug, task etc.
 * @param int    $id Id of the project you want the count for.
 */
function upstream_count_assigned_to_open( $type, $id = 0 ) {
	$count = new Upstream_Counts( $id );

	return $count->assigned_to_open( $type );
}

/**
 * Retrieve details from a given project.
 *
 * @param int $project_id The project ID.
 *
 * @return  object
 * @since   1.12.0
 */
function get_up_stream_project_details_by_id( $project_id ) {
	$post = get_post( $project_id );
	if ( $post instanceof \WP_Post ) {
		global $wpdb;

		$project                 = new stdClass();
		$project->id             = (int) $project_id;
		$project->title          = $post->post_title;
		$project->description    = '';
		$project->progress       = 0;
		$project->status         = null;
		$project->client_id      = 0;
		$project->client_name    = '';
		$project->owner_id       = 0;
		$project->owner_name     = '';
		$project->date_start     = 0;
		$project->date_end       = 0;
		$project->date_start_ymd = '';
		$project->date_end_ymd   = '';
		$project->members        = array();
		$project->client_users   = array();

		$metas = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_key, meta_value
            	FROM $wpdb->postmeta
            	WHERE post_id = %d
              	AND meta_key LIKE %s",
				array(
					$project->id,
					'_upstream_project_%',
				)
			)
		);

		foreach ( $metas as $meta ) {
			if ( '_upstream_project_description' === $meta->meta_key ) {
				$project->description = $meta->meta_value;
			} elseif ( '_upstream_project_progress' === $meta->meta_key ) {
				$project->progress = (int) $meta->meta_value;
			} elseif ( '_upstream_project_status' === $meta->meta_key ) {
				$project->status = $meta->meta_value;
			} elseif ( '_upstream_project_client' === $meta->meta_key ) {
				$project->client_id = (int) $meta->meta_value;
			} elseif ( '_upstream_project_owner' === $meta->meta_key ) {
				$project->owner_id = (int) $meta->meta_value;
			} elseif ( '_upstream_project_start' === $meta->meta_key ) {
				$project->date_start = (int) $meta->meta_value;
			} elseif ( '_upstream_project_end' === $meta->meta_key ) {
				$project->date_end = (int) $meta->meta_value;
			} elseif ( '_upstream_project_start.YMD' === $meta->meta_key ) {
				$project->date_start_ymd = $meta->meta_value;
			} elseif ( '_upstream_project_end.YMD' === $meta->meta_key ) {
				$project->date_end_ymd = $meta->meta_value;
			} elseif ( '_upstream_project_members' === $meta->meta_key ) {
				$project->members = (array) maybe_unserialize( $meta->meta_value );
			} elseif ( '_upstream_project_client_users' === $meta->meta_key ) {
				$project->client_users = (array) maybe_unserialize( $meta->meta_value );
			}
		}

		$users_rowset = (array) get_users(
			array(
				'fields' => array( 'ID', 'display_name' ),
			)
		);

		$users = array();
		foreach ( $users_rowset as $user ) {
			$users[ (int) $user->ID ] = (object) array(
				'id'   => (int) $user->ID,
				'name' => $user->display_name,
			);
		}

		if ( $project->client_id > 0 ) {
			$client = get_post( $project->client_id );
			if ( $client instanceof \WP_Post ) {
				if ( ! empty( $client->post_title ) ) {
					$project->client_name = $client->post_title;
				}
			}
		}

		if ( $project->owner_id > 0 && isset( $users[ $project->owner_id ] ) ) {
			$project->owner_name = $users[ $project->owner_id ]->name;
		}

		if ( count( $project->members ) > 0 ) {
			foreach ( $project->members as $member_index => $member_id ) {
				$member_id = (int) $member_id;
				if ( $member_id > 0 && isset( $users[ $member_id ] ) ) {
					$project->members[ $member_index ] = $users[ $member_id ];
				}
			}
		}

		if ( count( $project->client_users ) > 0 ) {
			foreach ( $project->client_users as $client_user_index => $client_user_id ) {
				$client_user_id = (int) $client_user_id;
				if ( $client_user_id > 0 && isset( $users[ $client_user_id ] ) ) {
					$project->client_users[ $client_user_index ] = $users[ $client_user_id ];
				}
			}
		}

		return $project;
	}

	return false;
}

/**
 * Count_items_for_user_on_project
 *
 * @param string $item_type Item type.
 * @param int    $user_id User id.
 * @param int    $project_id Project id.
 */
function count_items_for_user_on_project( $item_type, $user_id, $project_id ) {
	$user_id = (int) $user_id;
	if ( ! in_array( $item_type, array( 'milestones', 'tasks', 'bugs' ), true ) ) {
		return null;
	}

	$count = 0;

	if ( 'milestones' === $item_type ) {

		$project_milestones = \UpStream\Milestones::getInstance()->get_milestones_from_project( $project_id );

		$total = 0;
		if ( count( $project_milestones ) > 0 ) {
			foreach ( $project_milestones as $milestone ) {
				$milestone = \UpStream\Factory::get_milestone( $milestone );
				if ( in_array( $user_id, $milestone->getAssignedTo() ) ) {
					$count++;
				}
			}
		}

		return $count;
	}

	$metas = (array) get_post_meta( (int) $project_id, '_upstream_project_' . $item_type );
	$metas = count( $metas ) > 0 ? (array) $metas[0] : array();

	if ( is_array( $metas ) && count( $metas ) > 0 ) {
		foreach ( $metas as $meta ) {
			if ( isset( $meta['assigned_to'] ) ) {
				$assigned_to = $meta['assigned_to'];

				if (
					( is_array( $assigned_to ) && in_array( $user_id, $assigned_to ) )
					&& ( (int) $meta['assigned_to'] === $user_id )
				) {
					$count++;
				}
			}
		}
	}

	return $count;
}

/**
 * Retrieve the number of approved comments within a given project.
 *
 * @param int $project_id The project ID.
 *
 * @return  int
 * @since   1.13.0
 */
function get_project_comments_count( $project_id ) {
	if ( ! is_numeric( $project_id ) || $project_id < 0 ) {
		return;
	}

	$comments_count = get_comments(
		array(
			'post_id' => $project_id,
			'count'   => true,
			'status'  => 'approve',
		)
	);

	return (int) $comments_count;
}

/**
 * Upstream_user_projects
 *
 * @return array
 */
function upstream_user_projects() {
	$projects_list = array();
	$user_id       = absint( @$_SESSION['upstream']['user_id'] );
	$current_user  = (object) upstream_user_data( $user_id );

	if ( isset( $current_user->projects ) ) {
		if ( is_array( $current_user->projects ) && count( $current_user->projects ) > 0 ) {
			$archive_closed_items = upstream_archive_closed_items();
			$are_clients_enabled  = ! upstream_is_clients_disabled();

			foreach ( $current_user->projects as $project_id => $project ) {
				$data = (object) array(
					'id'                   => $project_id,
					'title'                => $project->post_title,
					'slug'                 => $project->post_name,
					'status'               => $project->post_status,
					'permalink'            => get_permalink( $project_id ),
					'start_date_timestamp' => (int) upstream_project_start_date( $project_id ),
					'end_date_timestamp'   => (int) upstream_project_end_date( $project_id ),
					'progress'             => (float) upstream_project_progress( $project_id ),
					'status'               => (string) upstream_project_status( $project_id ),
					'client_name'          => null,
					'categories'           => array(),
					'features'             => array(
						'',
					),
				);

				// If should archive closed items, we filter the rowset.
				if ( $archive_closed_items ) {

					$open_statuses = upstream_get_open_project_status_ids();
					if ( ! empty( $data->status ) && ! in_array( $data->status, $open_statuses ) ) {
						continue;
					}
				}

				$data->start_date = (string) upstream_format_date( $data->start_date_timestamp );
				$data->end_date   = (string) upstream_format_date( $data->end_date_timestamp );

				if ( $are_clients_enabled ) {
					$data->client_name = trim( (string) upstream_project_client_name( $project_id ) );
				}

				$statuses = upstream_get_all_project_statuses();

				if ( isset( $statuses[ $data->status ] ) ) {
					$data->status = $statuses[ $data->status ];
				}

				$data->timeframe = $data->start_date;
				if ( ! empty( $data->end_date ) ) {
					if ( ! empty( $data->timeframe ) ) {
						$data->timeframe .= ' - ';
					} else {
						$data->timeframe = '<i>' . __( 'Ends at', 'upstream' ) . '</i>';
					}

					$data->timeframe .= $data->end_date;
				}

				$categories = (array) wp_get_object_terms( $data->id, 'project_category' );
				if ( count( $categories ) > 0 ) {
					foreach ( $categories as $category ) {
						if ( is_object( $category ) ) {
							$data->categories[ $category->term_id ] = $category->name;
						}
					}
				}

				$projects_list[ $project_id ] = $data;
			}

			unset( $project, $project_id );
		}

		unset( $current_user->projects );
	}

	unset( $current_user );

	return $projects_list;
}
