<?php
/*
 * Get Giveaway Lists
 */
function rafflepress_lite_get_giveaway_list() {
	if ( check_ajax_referer( 'rafflepress_lite_get_giveaway_list' ) ) {
		global $wpdb;

		$tablename = $wpdb->prefix . 'rafflepress_giveaways';

		$sql = "SELECT id,name FROM $tablename";

		$sql .= ' WHERE deleted_at is null ORDER BY name asc ';

		$response = $wpdb->get_results( $sql );

		wp_send_json( $response );
	}
}

/*
 * New Giveaway
 */
function rafflepress_lite_new_giveaway() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'rafflepress_lite_builder' && isset( $_GET['id'] ) && $_GET['id'] == '0' ) {
		global $wpdb;
		$tablename = $wpdb->prefix . 'rafflepress_giveaways';

		// get app settings
		$timezone = 'UTC';


		$id = absint( $_GET['id'] );
		//2019-05-28T04:00:00.000Z Y-m-d
		$starts = date( 'c', strtotime( ' + 2 days' ) );
		$ends   = date( 'c', strtotime( ' + 16 days' ) );
		// $starts = null;
		// $ends = null;

		require_once RAFFLEPRESS_PLUGIN_PATH . 'resources/giveaway-templates/basic-giveaway.php';
		$settings           = json_decode( $rafflepress_basic_giveaway );
		$settings->starts   = $starts;
		$settings->ends     = $ends;
		$settings->timezone = $timezone;
		$settings->is_new   = true;
		$settings           = wp_json_encode( $settings );

		// Insert
		$r = $wpdb->insert(
			$tablename,
			array(
				'name'                => '',
				'giveawaytemplate_id' => 'basic-giveaway',
				'starts'              => null,
				'ends'                => null,
				'settings'            => $settings,
				'uuid'                => wp_generate_uuid4(),

			),
			array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);

		 $id = $wpdb->insert_id;
		if ( is_numeric( $id ) ) {
			$giveaway_name = esc_html__( 'New Giveaway', 'rafflepress' ) . " (ID #$id)";
		} else {
			$giveaway_name = esc_html__( 'New Giveaway', 'rafflepress' );
		}

		 // Update name
		$wpdb->update(
			$tablename,
			array(
				'name' => $giveaway_name,
			),
			array( 'id' => $id ),
			array(
				'%s',
			),
			array( '%d' )
		);

		 wp_redirect( 'admin.php?page=rafflepress_lite_builder&id=' . $id . '#/template/' . $id );
		 exit();
	}
}

/*
 * Giveaway Datatable
 */
function rafflepress_lite_giveaway_datatable() {
	if ( check_ajax_referer( 'rafflepress_lite_giveaway_datatable' ) ) {
		$data         = array( '' );
		$current_page = 1;
		if ( ! empty( absint( $_GET['current_page'] ) ) ) {
			$current_page = absint( $_GET['current_page'] );
		}
		$per_page = 10;

		$filter = null;
		if ( ! empty( $_GET['filter'] ) ) {
			$filter = sanitize_text_field( $_GET['filter'] );
			if ( $filter == 'all' ) {
				$filter = null;
			}
		}

		if ( ! empty( $_GET['s'] ) ) {
			$filter = null;
		}

		// Get records
		global $wpdb;
		$tablename              = $wpdb->prefix . 'rafflepress_giveaways';
		$entries_tablename      = $wpdb->prefix . 'rafflepress_entries';
		$constestants_tablename = $wpdb->prefix . 'rafflepress_contestants';

		$sql = "SELECT *,
        (select count(*) from $entries_tablename where
        $tablename.`id` = $entries_tablename.`giveaway_id` and deleted_at IS NULL) as `entries_count`,
        (select count(*) from $constestants_tablename where
        $tablename.`id` = $constestants_tablename.`giveaway_id`) as `contestants_count` 
        FROM $tablename";

		$sql .= ' WHERE 1 = 1  ';

		if ( ! empty( $filter ) ) {
			if ( esc_sql( $filter ) == 'running' ) {
				$sql .= ' AND  UTC_TIMESTAMP() > starts AND deleted_at is null';
				$sql .= ' AND  UTC_TIMESTAMP() < ends ';
				$sql .= ' AND  active = 1 ';
			}
			if ( esc_sql( $filter ) == 'upcoming' ) {
				$sql .= ' AND  UTC_TIMESTAMP() < starts AND deleted_at is null';
				$sql .= ' AND  active = 1 ';
			}
			if ( esc_sql( $filter ) == 'ended' ) {
				$sql .= ' AND deleted_at is null ';
				$sql .= ' AND  UTC_TIMESTAMP() > ends ';
				$sql .= ' AND  active = 1 ';
			}
			if ( esc_sql( $filter ) == 'needs_winners' ) {
				$tablename2 = $wpdb->prefix . 'rafflepress_contestants';
				$sql       .= ' AND deleted_at is null ';
				$sql       .= ' AND  UTC_TIMESTAMP() > ends ';
				$sql       .= ' AND  active = 1 ';
				$sql       .= " AND  giveawaytemplate_id = 'basic_giveaway'";
				$sql       .= " AND  NOT EXISTS (SELECT 1 FROM $tablename2 WHERE giveaway_id = $tablename.id AND winner = 1)";
			}
			if ( esc_sql( $filter ) == 'archived' ) {
				$sql .= ' AND deleted_at is not null';
			}
		} else {
			$sql .= ' AND deleted_at is null';
		}

		if ( ! empty( $_GET['s'] ) ) {
			$sql .= " AND id LIKE '%" . esc_sql( trim( sanitize_text_field( $_GET['s'] ) ) ) . "%' OR name LIKE '%" . esc_sql( trim( sanitize_text_field( $_GET['s'] ) ) ) . "%'";
		}

		if ( ! empty( $_GET['orderby'] ) ) {
			$orderby = esc_sql(sanitize_text_field($_GET['orderby']));
			if ( $orderby == 'status' ) {
				$sql .= ' ORDER BY starts';
			}
			if ( $orderby == 'entries' ) {
				$sql .= ' ORDER BY entries_count';
			}
			if ( $orderby == 'contestants' ) {
				$sql .= ' ORDER BY contestants_count';
			}

			if ( esc_sql(sanitize_text_field( $_GET['order'] )) === 'desc' ) {
				$order = 'DESC';
			} else {
				$order = 'ASC';
			}
			$sql .= ' ' . $order;
		} else {
			$sql .= ' ORDER BY created_at DESC';
		}

		$sql .= " LIMIT $per_page";
		if ( empty( $_POST['s'] ) ) {
			$sql .= ' OFFSET ' . ( $current_page - 1 ) * $per_page;
		}

		$results = $wpdb->get_results( $sql );

		$data = array();
		foreach ( $results as $v ) {

			// Format Date
			$created_at = date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $v->created_at ) );

			$status = __( 'Draft - ', 'rafflepress' ) . '|needs_setup';
			if ( $v->starts != '0000-00-00 00:00:00' && $v->ends != '0000-00-00 00:00:00' ) {
				if ( ! empty( $v->starts ) && ! empty( $v->ends ) ) {
					if ( time() < strtotime( $v->starts . ' UTC' ) ) {
						$status = __( 'Scheduled - Starts in', 'rafflepress' ) . ' ' . human_time_diff( time(), strtotime( $v->starts . ' UTC' ) ) . '|start_now';
					} elseif ( time() > strtotime( $v->starts . ' UTC' ) && time() < strtotime( $v->ends . ' UTC' ) ) {
						$status = __( 'Running - Ends in', 'rafflepress' ) . ' ' . human_time_diff( time(), strtotime( $v->ends . ' UTC' ) ) . '|end_now';
					} elseif ( time() > strtotime( $v->ends . ' UTC' ) ) {
						//$status = __('Ended', 'rafflepress').' '.human_time_diff(time(), strtotime($v->ends." UTC")).' ago';
						$status = __( 'Ended', 'rafflepress' ) . ' - ';
						if ( strpos( $v->giveawaytemplate_id, 'giveaway' ) !== false ) {
							$tablename = $wpdb->prefix . 'rafflepress_contestants';
							$sql       = "SELECT count(id) FROM $tablename WHERE giveaway_id = %d AND winner = 1";
							$safe_sql  = $wpdb->prepare( $sql, absint( $v->id ) );
							$winners   = $wpdb->get_var( $safe_sql );
							if ( empty( $winners ) ) {
								$needs_winners = '|needs_winners';
								$status        = $status . $needs_winners;
							} else {
								$see_winners = '|see_winners';
								$status      = $status . $see_winners;
							}
						}
					}
				}
			}

			if ( empty( $v->active ) ) {
				$status = __( 'Disabled', 'rafflepress' );
			}

			$contestants = $v->contestants_count;
			$entries     = $v->entries_count;

			$active = $v->active;
			if ( $active == 1 ) {
				$active = true;
			} else {
				$active = false;
			}

			$type = '';
			if ( $v->giveawaytemplate_id == 'basic_giveaway' ) {
				$type = __( 'Classic Giveaway', 'rafflepress' );
			} elseif ( $v->giveawaytemplate_id == 'leaderboard_giveaway' ) {
				$type = __( 'Leaderboard Giveaway', 'rafflepress' );
			} elseif ( $v->giveawaytemplate_id == 'reward_giveaway' ) {
				$type = __( 'Rewards Giveaway', 'rafflepress' );
			} elseif ( $v->giveawaytemplate_id == 'milestone_rewards_giveaway' ) {
				$type = __( 'Milestone Rewards Giveaway', 'rafflepress' );
			} elseif ( $v->giveawaytemplate_id == 'pre_launch_giveaway' ) {
				$type = __( 'Pre Launch Giveaway', 'rafflepress' );
			}

			$settings = json_decode( $v->settings );

			if ( empty( $v->starts ) || $v->starts == '0000-00-00 00:00:00' ) {
				$starts = __( 'N/A', 'rafflepress' );
			} else {
				$starts = $date = date( get_option( 'date_format' ), strtotime( $settings->starts ) );
			}

			if ( empty( $v->ends ) || $v->ends == '0000-00-00 00:00:00' ) {
				$ends = __( 'N/A', 'rafflepress' );
			} else {
				$ends = $date = date( get_option( 'date_format' ), strtotime( $settings->ends ) );
			}

			// Check if it has Image Submissions or Polls
			$has_images = false;
			$has_polls  = false;
			if ( ! empty( $v->settings ) ) {
				$settings = json_decode( $v->settings );
				if ( ! empty( $settings->entry_options ) ) {
					$entry_options = $settings->entry_options;
					foreach ( $entry_options as $v2 ) {
						if ( $v2->type == 'submit-image' ) {
							$has_images = true;
						}
						if ( $v2->type == 'polls-surveys' ) {
							$has_polls = true;
						}
					}
				}
			}

			// Load Data

			$data[] = array(
				'id'          => $v->id,
				'name'        => $v->name,
				'type'        => $type,
				'status'      => $status,
				'starts'      => $starts,
				'ends'        => $ends,
				'contestants' => $contestants,
				'entries'     => $entries,
				'active'      => $active,
				'created_at'  => $created_at,
				'has_images'  => $has_images,
				'has_polls'   => $has_polls,
			);
		}

		$totalitems = rafflepress_lite_giveaway_get_data_total( $filter );
		$views      = rafflepress_lite_giveaway_get_views( $filter );

		$response = array(
			'rows'        => $data,
			'totalitems'  => $totalitems,
			'totalpages'  => ceil( $totalitems / 10 ),
			'currentpage' => $current_page,
			'views'       => $views,
		);

		wp_send_json( $response );
	}
}


function rafflepress_lite_giveaway_get_data_total( $filter = null ) {
	global $wpdb;

	$tablename = $wpdb->prefix . 'rafflepress_giveaways';

	$sql = "SELECT count(id) FROM $tablename";

	$sql .= ' WHERE 1 = 1 ';

	if ( ! empty( $filter ) ) {
		if ( esc_sql( $filter ) == 'running' ) {
			$sql .= ' AND  UTC_TIMESTAMP() > starts ';
			$sql .= ' AND  UTC_TIMESTAMP() < ends ';
			$sql .= ' AND  active = 1 ';
			$sql .= ' AND deleted_at is null';
		}
		if ( esc_sql( $filter ) == 'upcoming' ) {
			$sql .= ' AND  UTC_TIMESTAMP() < starts ';
			$sql .= ' AND  active = 1 ';
			$sql .= ' AND deleted_at is null';
		}
		if ( esc_sql( $filter ) == 'ended' ) {
			$sql .= ' AND  UTC_TIMESTAMP() > ends ';
			$sql .= ' AND  active = 1 ';
			$sql .= ' AND deleted_at is null';
		}
		if ( esc_sql( $filter ) == 'needs_winners' ) {
			$tablename2 = $wpdb->prefix . 'rafflepress_contestants';
			$sql       .= ' AND  UTC_TIMESTAMP() > ends ';
			$sql       .= ' AND  active = 1 ';
			$sql       .= " AND  giveawaytemplate_id = 'basic_giveaway'";
			$sql       .= " AND  NOT EXISTS (SELECT 1 FROM $tablename2 WHERE giveaway_id = $tablename.id AND winner = 1)";
			$sql       .= ' AND deleted_at is null';
		}
		if ( esc_sql( $filter ) == 'archived' ) {
			$sql .= ' AND deleted_at is not null';
		}
	} else {
		$sql .= ' AND deleted_at is null';
	}

	if ( ! empty( $_GET['s'] ) ) {
		$sql .= " AND name LIKE '%" . esc_sql( sanitize_text_field( $_GET['s'] ) ) . "%'";
	}

	$results = $wpdb->get_var( $sql );
	return $results;
}



function rafflepress_lite_giveaway_get_views( $filter = null ) {
	$views   = array();
	$current = ( ! empty( $filter ) ? $filter : 'all' );
	$current = sanitize_text_field( $current );

	global $wpdb;
	$tablename = $wpdb->prefix . 'rafflepress_giveaways';

	//All link
	$sql = "SELECT count(id) FROM $tablename";

	$sql .= ' WHERE 1 = 1 AND deleted_at is null ';

	$results      = $wpdb->get_var( $sql );
	$class        = ( $current == 'all' ? ' class="current"' : '' );
	$all_url      = remove_query_arg( 'filter' );
	$views['all'] = $results;

	//Running link
	$sql  = "SELECT count(id) FROM $tablename";
	$sql .= ' WHERE 1 = 1 AND deleted_at is null ';
	$sql .= ' AND  UTC_TIMESTAMP() > starts ';
	$sql .= ' AND  UTC_TIMESTAMP() < ends ';
	$sql .= ' AND  active = 1 ';

	$results          = $wpdb->get_var( $sql );
	$running_url      = add_query_arg( 'filter', 'running' );
	$class            = ( $current == 'running' ? ' class="current"' : '' );
	$views['running'] = $results;

	//Upcoming link
	$sql  = "SELECT count(id) FROM $tablename";
	$sql .= ' WHERE 1 = 1 AND deleted_at is null ';
	$sql .= ' AND  UTC_TIMESTAMP() < starts ';
	$sql .= ' AND  active = 1 ';

	$results           = $wpdb->get_var( $sql );
	$upcoming_url      = add_query_arg( 'filter', 'upcoming' );
	$class             = ( $current == 'upcoming' ? ' class="current"' : '' );
	$views['upcoming'] = $results;

	//Ended link
	$sql  = "SELECT count(id) FROM $tablename";
	$sql .= ' WHERE 1 = 1 AND deleted_at is null ';
	$sql .= ' AND  UTC_TIMESTAMP() > ends ';
	$sql .= ' AND  active = 1 ';

	$results        = $wpdb->get_var( $sql );
	$ended_url      = add_query_arg( 'filter', 'ended' );
	$class          = ( $current == 'ended' ? ' class="current"' : '' );
	$views['ended'] = $results;

	//Needs Winners link
	$tablename2 = $wpdb->prefix . 'rafflepress_contestants';
	$sql        = "SELECT count(id) FROM $tablename ";
	$sql       .= ' WHERE 1 = 1 ';
	$sql       .= ' AND deleted_at is null ';
	$sql       .= ' AND  UTC_TIMESTAMP() > ends ';
	$sql       .= ' AND  active = 1 ';
	$sql       .= " AND  giveawaytemplate_id = 'basic_giveaway'";
	$sql       .= " AND  NOT EXISTS (SELECT 1 FROM $tablename2 WHERE giveaway_id = $tablename.id AND winner = 1)";

	$results                = $wpdb->get_var( $sql );
	$needs_winners_url      = add_query_arg( 'filter', 'needs_winners' );
	$class                  = ( $current == 'needs_winners' ? ' class="current"' : '' );
	$views['needs_winners'] = $results;

	//Archived link
	$sql  = "SELECT count(id) FROM $tablename";
	$sql .= ' WHERE 1 = 1 AND deleted_at is not null ';

	$results           = $wpdb->get_var( $sql );
	$archived_url      = add_query_arg( 'filter', 'archived' );
	$class             = ( $current == 'archived' ? ' class="current"' : '' );
	$views['archived'] = $results;

	return $views;
}

/*
 * Duplicate Giveaway
 */

function rafflepress_lite_duplicate_giveaway() {
	if ( check_ajax_referer( 'rafflepress_lite_duplicate_giveaway' ) ) {
		$id = '';
		if ( ! empty( $_GET['id'] ) ) {
			$id = absint( $_GET['id'] );
		}

		// Get the giveaway of the id passed in.
		global $wpdb;
		$tablename = $wpdb->prefix . 'rafflepress_giveaways';
		$sql       = "SELECT * FROM $tablename";
		$sql      .= ' WHERE id = %d';
		$safe_sql  = $wpdb->prepare( $sql, $id );
		$result    = $wpdb->get_row( $safe_sql );

		if ( ! empty( $result ) ) {
			$r = $wpdb->insert(
				$tablename,
				array(
					'name'                => $result->name . ' ' . __( 'Copy', 'rafflepress' ),
					'settings'            => $result->settings,
					'starts'              => $result->starts,
					'ends'                => $result->ends,
					'active'              => $result->active,
					'giveawaytemplate_id' => $result->giveawaytemplate_id,
					'uuid'                => wp_generate_uuid4(),
				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s',
					'%s',
				)
			);
		}

		wp_send_json( array( 'status' => true ) );
	}
}


/*
* Archive Selected Giveaway
*/
function rafflepress_lite_archive_selected_giveaways() {
	if ( check_ajax_referer( 'rafflepress_lite_archive_selected_giveaways' ) ) {
		if ( current_user_can( apply_filters( 'rafflepress_list_users_capability', 'list_users' ) ) ) {
			if ( ! empty( $_GET['ids'] ) ) {
				$ids          = array_map( 'intval', explode( ',', $_GET['ids'] ) );
				$how_many     = count( $ids );
				$placeholders = array_fill( 0, $how_many, '%d' );
				$format       = implode( ', ', $placeholders );

				global $wpdb;
				$tablename = $wpdb->prefix . 'rafflepress_giveaways';
				$sql       = 'UPDATE ' . $tablename . " SET deleted_at = CURRENT_TIMESTAMP() WHERE id IN ( $format )";
				$safe_sql  = $wpdb->prepare( $sql, $ids );
				$result    = $wpdb->query( $safe_sql );
				if ( $result ) {
					wp_send_json( array( 'status' => true ) );
				}
			}
		}
	}
}

/*
* Unarchive Selected Giveaway
*/
function rafflepress_lite_unarchive_selected_giveaways( $ids ) {
	if ( check_ajax_referer( 'rafflepress_lite_unarchive_selected_giveaways' ) ) {
		if ( current_user_can( apply_filters( 'rafflepress_list_users_capability', 'list_users' ) ) ) {
			if ( ! empty( $_GET['ids'] ) ) {
				$ids          = array_map( 'intval', explode( ',', $_GET['ids'] ) );
				$how_many     = count( $ids );
				$placeholders = array_fill( 0, $how_many, '%d' );
				$format       = implode( ', ', $placeholders );

				global $wpdb;
				$tablename = $wpdb->prefix . 'rafflepress_giveaways';
				$sql       = 'UPDATE ' . $tablename . " SET deleted_at = NULL WHERE id IN ( $format )";
				$safe_sql  = $wpdb->prepare( $sql, $ids );
				$result    = $wpdb->query( $safe_sql );
				if ( $result ) {
					wp_send_json( array( 'status' => true ) );
				}
			}
		}
	}
}

/*
* Delete Archived Giveaway
*/
function rafflepress_lite_delete_archived_giveaways() {
	if ( check_ajax_referer( 'rafflepress_lite_delete_archived_giveaways' ) ) {
		if ( current_user_can( apply_filters( 'rafflepress_list_users_capability', 'list_users' ) ) ) {
			global $wpdb;
			$tablename = $wpdb->prefix . 'rafflepress_giveaways';

			$sql  = "SELECT id FROM $tablename";
			$sql .= ' WHERE deleted_at is not null';
			$ids  = $wpdb->get_col( $sql );

			$how_many     = count( $ids );
			$placeholders = array_fill( 0, $how_many, '%d' );
			$format       = implode( ', ', $placeholders );

			// Delete giveaways
			$sql      = 'DELETE FROM ' . $tablename . " WHERE id IN ($format )";
			$safe_sql = $wpdb->prepare( $sql, $ids );
			$result   = $wpdb->query( $safe_sql );

			// Deleted contestants
			$tablename = $wpdb->prefix . 'rafflepress_contestants';
			$sql       = 'DELETE FROM ' . $tablename . " WHERE giveaway_id IN ($format )";
			$safe_sql  = $wpdb->prepare( $sql, $ids );
			$result    = $wpdb->query( $safe_sql );

			// Delete entries
			$tablename = $wpdb->prefix . 'rafflepress_entries';
			$sql       = 'DELETE FROM ' . $tablename . " WHERE giveaway_id IN ($format )";
			$safe_sql  = $wpdb->prepare( $sql, $ids );
			$result    = $wpdb->query( $safe_sql );

			wp_send_json( array( 'status' => true ) );
		}
	}
}

/*
 * Start Giveaway Now
 */

function rafflepress_lite_start_giveaway() {
	if ( check_ajax_referer( 'rafflepress_lite_start_giveaway' ) ) {
		$id = '';
		if ( ! empty( $_GET['id'] ) ) {
			$id = absint( $_GET['id'] );
		}

		global $wpdb;
		$tablename = $wpdb->prefix . 'rafflepress_giveaways';
		$sql       = "SELECT * FROM $tablename";
		$sql      .= ' WHERE id = %d';
		$safe_sql  = $wpdb->prepare( $sql, $id );
		$result    = $wpdb->get_row( $safe_sql );

		$starts = gmdate( 'Y-m-d H:i:s', time() - 60 );

		$settings         = json_decode( $result->settings );
		$settings->starts = $starts;
		$settings         = wp_json_encode( $settings );

		$r = $wpdb->update(
			$tablename,
			array(
				'starts'   => $starts,
				'settings' => $settings,
			),
			array( 'id' => $id ),
			array(
				'%s',
				'%s',
			),
			array( '%d' )
		);

		wp_send_json( array( 'status' => true ) );
	}
}


/*
 * End Giveaway Now
 */

function rafflepress_lite_end_giveaway() {
	if ( check_ajax_referer( 'rafflepress_lite_end_giveaway' ) ) {
		$id = '';
		if ( ! empty( $_GET['id'] ) ) {
			$id = absint( $_GET['id'] );
		}

		global $wpdb;
		$tablename = $wpdb->prefix . 'rafflepress_giveaways';
		$sql       = "SELECT * FROM $tablename";
		$sql      .= ' WHERE id = %d';
		$safe_sql  = $wpdb->prepare( $sql, $id );
		$result    = $wpdb->get_row( $safe_sql );

		$ends = gmdate( 'Y-m-d H:i:s', time() - 60 );

		$settings       = json_decode( $result->settings );
		$settings->ends = $ends;
		$settings       = wp_json_encode( $settings );

		$r = $wpdb->update(
			$tablename,
			array(
				'ends'     => $ends,
				'settings' => $settings,
			),
			array( 'id' => $id ),
			array(
				'%s',
				'%s',
			),
			array( '%d' )
		);
		 wp_send_json( array( 'status' => true ) );
	}
}


/*
 * enable Disable Giveaway
 */

function rafflepress_lite_enable_disable_giveaway() {
	if ( check_ajax_referer( 'rafflepress_lite_enable_disable_giveaway' ) ) {
		$id = '';
		if ( ! empty( $_GET['id'] ) ) {
			$id = absint( $_GET['id'] );
		}

		$active = true;
		if ( ! empty( $_GET['current_state'] ) && $_GET['current_state'] != 'true' ) {
			$active = false;
		}

		// Get the giveaway of the id passed in.
		global $wpdb;
		$tablename = $wpdb->prefix . 'rafflepress_giveaways';
		$r         = $wpdb->update(
			$tablename,
			array(
				'active' => $active,
			),
			array( 'id' => $id ),
			array(
				'%d',

			),
			array( '%d' )
		);
		if ( $active ) {
			wp_send_json( array( 'status' => 'enabled' ) );
		} else {
			wp_send_json( array( 'status' => 'disabled' ) );
		}
	}
}


/*
 * Save Slug
 **/

function rafflepress_lite_save_slug() {
	if ( check_ajax_referer( 'rafflepress_lite_save_slug' ) ) {

		// Validate
		$errors = array();
		// if(!is_email($_POST['product']['email'])){
		//     $errors['email'] = 'Please enter a valid email.';
		// }

		if ( ! empty( $errors ) ) {
			header( 'Content-Type: application/json' );
			header( 'Status: 400 Bad Request' );
			echo json_encode( $errors );
			exit();
		}

		$_POST = stripslashes_deep( $_POST );

		global $wpdb;
		$tablename = $wpdb->prefix . 'rafflepress_giveaways';

		$giveaway_slug = sanitize_title( $_POST['giveaway_slug'] );

		// see if a slug exists for this giveaway
		$sql             = "SELECT slug FROM $tablename WHERE id = %d";
		$safe_sql        = $wpdb->prepare( $sql, absint( $_POST['giveaway_id'] ) );
		$this_slug_exist = $wpdb->get_var( $safe_sql );
		if ( ! empty( $giveaway_slug ) ) {
			if ( empty( $this_slug_exist ) || $this_slug_exist != $giveaway_slug ) {

				// check if slug exists first
				$sql        = "SELECT * FROM $tablename WHERE slug = %s";
				$safe_sql   = $wpdb->prepare( $sql, $giveaway_slug );
				$slug_exist = $wpdb->get_row( $safe_sql );

				if ( ! empty( $slug_exist ) ) {
					$response = array(
						'status' => 'error',
						'msg'    => __( 'Sorry this url already exists. Please choose a new one.', 'rafflepress' ),
					);
					wp_send_json( $response );
				}

				// check wp posts table
				if ( ! empty( $giveaway_slug ) ) {
					$tableposts      = $wpdb->prefix . 'posts';
					$sql             = "SELECT * FROM $tableposts WHERE post_name = %s";
					$safe_sql        = $wpdb->prepare( $sql, $giveaway_slug );
					$post_slug_exist = $wpdb->get_row( $safe_sql );

					if ( ! empty( $post_slug_exist ) ) {
						$response = array(
							'status' => 'error',
							'msg'    => __( 'Sorry this url already exists. Please choose a new one.', 'rafflepress' ),
						);
						wp_send_json( $response );
					}
				}
			}
		}

		$status = '';
		if ( ! empty( $_POST['giveaway_id'] ) ) {
			$giveaway_id = absint( $_POST['giveaway_id'] );
			// Update
			$r      = $wpdb->update(
				$tablename,
				array(
					'slug' => $giveaway_slug,
				),
				array( 'id' => $giveaway_id ),
				array(
					'%s',
				),
				array( '%d' )
			);
			$status = 'updated';
		}

		$response = array(
			'status' => $status,
			'id'     => $giveaway_id,
		);

		wp_send_json( $response );
	}
}


/*
 * Save/Update Giveaway
 */

function rafflepress_lite_save_giveaway() {
	if ( check_ajax_referer( 'rafflepress_lite_save_giveaway' ) ) {

		// Validate
		$errors = array();
		// if(!is_email($_POST['product']['email'])){
		//     $errors['email'] = 'Please enter a valid email.';
		// }

		if ( ! empty( $errors ) ) {
			header( 'Content-Type: application/json' );
			header( 'Status: 400 Bad Request' );
			echo json_encode( $errors );
			exit();
		}

		$_POST = stripslashes_deep( $_POST );

		$timezone    = sanitize_text_field( $_POST['settings']['timezone'] );
		$starts      = sanitize_text_field( $_POST['settings']['starts'] );
		$ends        = sanitize_text_field( $_POST['settings']['ends'] );
		$starts_time = sanitize_text_field( $_POST['settings']['starts_time'] );
		$ends_time   = sanitize_text_field( $_POST['settings']['ends_time'] );

		//if (strpos($ends, "T") !== false) {
			//$ends = substr($ends, 0, strpos($ends, 'T'));
			$ends           = $ends . ' ' . $ends_time;
			$ends_timestamp = strtotime( $ends . ' ' . $timezone );
			$ends_utc       = date( 'Y-m-d H:i:s', $ends_timestamp );
		//} else {
		//    $ends_utc =  $ends;
		//}

		//if (strpos($starts, "T") !== false) {
			//$starts = substr($starts, 0, strpos($starts, 'T'));
			$starts           = $starts . ' ' . $starts_time;
			$starts_timestamp = strtotime( $starts . ' ' . $timezone );
			$starts_utc       = date( 'Y-m-d H:i:s', $starts_timestamp );
		//} else {
		//    $starts_utc =  $starts;
		//}

		global $wpdb;
		$tablename = $wpdb->prefix . 'rafflepress_giveaways';

		$settings = $_POST['settings'];

		$giveaway_name = sanitize_text_field( $_POST['giveaway_name'] );
		if( empty( $giveaway_name ) ){
			$giveaway_name = 'New Giveaway (ID#'.$_POST['giveaway_id'].')';
		}
		$giveaway_slug = sanitize_title( $_POST['giveaway_slug'] );

		array_walk_recursive( $settings, 'rafflepress_lite_convert_string_to_boolean' );

		$settings = json_encode( $settings );

		$status = '';
		if ( empty( $_POST['giveaway_id'] ) ) {

			// Insert
			$r           = $wpdb->insert(
				$tablename,
				array(
					'settings' => $settings,
					'starts'   => $starts_utc,
					'ends'     => $ends_utc,
				),
				array(
					'%s',
					'%s',
					'%s',
				)
			);
			$giveaway_id = $wpdb->insert_id;
			$status      = 'inserted';
		} else {
			$giveaway_id = absint( $_POST['giveaway_id'] );

			// check slug
			// see if a slug exists for this giveaway
			$sql             = "SELECT slug FROM $tablename WHERE id = %d";
			$safe_sql        = $wpdb->prepare( $sql, $giveaway_id );
			$this_slug_exist = $wpdb->get_var( $safe_sql );
			if ( ! empty( $giveaway_slug ) ) {
				if ( empty( $this_slug_exist ) || $this_slug_exist != $giveaway_slug ) {

					// check if slug exists first
					$sql        = "SELECT * FROM $tablename WHERE slug = %s AND id != %d";
					$safe_sql   = $wpdb->prepare( $sql, $giveaway_slug, $giveaway_id );
					$slug_exist = $wpdb->get_row( $safe_sql );

					if ( ! empty( $slug_exist ) ) {
						$response = array(
							'status' => 'error',
							'msg'    => __( 'Sorry the Page Permalink you assigned already exists. Please Choose a New Page Permalink under Settings > General', 'rafflepress' ),
						);
						wp_send_json( $response, 500 );
					}

					// check wp posts table
					//if (!empty($giveaway_slug)) {
					$tableposts      = $wpdb->prefix . 'posts';
					$sql             = "SELECT * FROM $tableposts WHERE post_name = %s";
					$safe_sql        = $wpdb->prepare( $sql, $giveaway_slug );
					$post_slug_exist = $wpdb->get_row( $safe_sql );

					if ( ! empty( $post_slug_exist ) ) {
						$response = array(
							'status' => 'error',
							'msg'    => __( 'Sorry the Page Permalink you assigned already exists. Please Choose a New Page Permalink under Settings > General', 'rafflepress' ),
						);
						wp_send_json( $response, 500 );
					}
					//}
				}
			}
			// Update
			$r      = $wpdb->update(
				$tablename,
				array(
					'name'     => $giveaway_name,
					'slug'     => $giveaway_slug,
					'settings' => $settings,
					'starts'   => $starts_utc,
					'ends'     => $ends_utc,
				),
				array( 'id' => $giveaway_id ),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
				),
				array( '%d' )
			);
			$status = 'updated';
		}

		$response = array(
			'status' => $status,
			'id'     => $giveaway_id,
		);

		wp_send_json( $response );
	}
}


function rafflepress_lite_get_utc_offset() {
	if ( check_ajax_referer( 'rafflepress_lite_get_utc_offset' ) ) {
		$_POST = stripslashes_deep( $_POST );

		$timezone    = sanitize_text_field( $_POST['timezone'] );
		$starts      = sanitize_text_field( $_POST['starts'] );
		$ends        = sanitize_text_field( $_POST['ends'] );
		$starts_time = sanitize_text_field( $_POST['starts_time'] );
		$ends_time   = sanitize_text_field( $_POST['ends_time'] );

		//$starts = substr($starts, 0, strpos($starts, 'T'));
		$starts           = $starts . ' ' . $starts_time;
		$starts_timestamp = strtotime( $starts . ' ' . $timezone );
		$starts_utc       = date( 'Y-m-d H:i:s', $starts_timestamp );

		//$ends = substr($ends, 0, strpos($ends, 'T'));
		$ends           = $ends . ' ' . $ends_time;
		$ends_timestamp = strtotime( $ends . ' ' . $timezone );
		$ends_utc       = date( 'Y-m-d H:i:s', $ends_timestamp );

		// countdown status
		$countdown_status = '';
		if ( ! empty( $starts_utc ) && time() < strtotime( $starts_utc . ' UTC' ) ) {
			$countdown_status = __( 'Starts in', 'rafflepress' ) . ' ' . human_time_diff( time(), $starts_timestamp );
		} elseif ( ! empty( $ends_utc ) && time() > strtotime( $ends_utc . ' UTC' ) ) {
			$countdown_status = __( 'Ended', 'rafflepress' ) . ' ' . human_time_diff( time(), $ends_timestamp ) . ' ago';
		}

		$response = array(
			'starts_timestamp' => $starts_timestamp,
			'ends_timestamp'   => $ends_timestamp,
			'countdown_status' => $countdown_status,
		);

		wp_send_json( $response );
	}
}




/*
 * Save/Update Giveaways Template
 */

function rafflepress_lite_save_template() {
	if ( check_ajax_referer( 'rafflepress_lite_save_template' ) ) {
		$_POST = stripslashes_deep( $_POST );

		global $wpdb;
		$tablename = $wpdb->prefix . 'rafflepress_giveaways';

		$status      = '';
		$giveaway_id = null;

		// get app settings
		$rafflepress_settings = get_option( 'rafflepress_settings' );
		if ( ! empty( $rafflepress_settings ) ) {
			$rafflepress_settings = json_decode( $rafflepress_settings );
			$timezone             = $rafflepress_settings->default_timezone;
		}

		if ( empty( absint( $_POST['giveaway']['id'] ) ) ) {
			$starts = date( 'Y-m-d H:i:s', strtotime( ' + 24 hours' ) );
			$ends   = date( 'Y-m-d H:i:s', strtotime( ' + 14 days' ) );

			$giveaway_template_id = sanitize_text_field( $_POST['giveaway']['giveawaytemplate_id'] );
			if ( $giveaway_template_id == 'basic_giveaway' ) {
				require_once RAFFLEPRESS_PLUGIN_PATH . 'resources/giveaway-templates/basic-giveaway.php';
				$settings           = json_decode( $rafflepress_basic_giveaway );
				$settings->starts   = '';
				$settings->ends     = '';
				$settings->timezone = $timezone;
				$settings           = wp_json_encode( $settings );
			}

			// Insert
			$r = $wpdb->insert(
				$tablename,
				array(
					'name'                => sanitize_text_field( $_POST['giveaway']['name'] ),
					'giveawaytemplate_id' => $giveaway_template_id,
					'starts'              => $starts,
					'ends'                => $ends,
					'settings'            => $settings,
					'uuid'                => wp_generate_uuid4(),

				),
				array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
				)
			);
			if ( $r ) {
				$giveaway_id = absint( $wpdb->insert_id );
				// get giveaway
				$tablename = $wpdb->prefix . 'rafflepress_giveaways';
				$sql       = "SELECT * FROM $tablename WHERE id = %d";
				$safe_sql  = $wpdb->prepare( $sql, $giveaway_id );
				$giveaway  = $wpdb->get_row( $safe_sql );

				$status = 'inserted';

				$response = array(
					'status'   => $status,
					'id'       => $giveaway_id,
					'giveaway' => $giveaway,
				);

				wp_send_json( $response );
			} else {
				$status = 'error';
			}
		} else {
			$giveaway_id = absint( $_POST['giveaway']['id'] );

			// Update
			$r = $wpdb->update(
				$tablename,
				array(
					'name'                => sanitize_text_field( $_POST['giveaway']['name'] ),
					'giveawaytemplate_id' => sanitize_text_field( $_POST['giveaway']['giveawaytemplate_id'] ),
				),
				array( 'id' => $giveaway_id ),
				array(
					'%s',
					'%s',

				),
				array( '%d' )
			);
			$status = 'updated';
		}

		$response = array(
			'status' => $status,
			'id'     => $giveaway_id,
		);

		wp_send_json( $response );
	}
}


function rafflepress_lite_get_automation_tool_list(){
		
	if ( check_ajax_referer( 'rafflepress_lite_get_automation_tool_list' ) ) {
		
		$am_plugins  = array(
			'uncanny-automator/uncanny-automator.php' => 'uncanny-automator',
			'uncanny-automator-pro/uncanny-automator-pro.php'  => 'uncanny-automator-pro',
		);
		$all_plugins = get_plugins();

		$response = array();

		foreach ( $am_plugins as $slug => $label ) {
			if ( array_key_exists( $slug, $all_plugins ) ) {
				if ( is_plugin_active( $slug ) ) {
					$response[ $label ] = array(
						'label'  => __( 'Active', 'seedprod-pro' ),
						'status' => 1,
					);
				} else {
					$response[ $label ] = array(
						'label'  => __( 'Inactive', 'seedprod-pro' ),
						'status' => 2,
					);
				}
			} else {
				$response[ $label ] = array(
					'label'  => __( 'Not Installed', 'seedprod-pro' ),
					'status' => 0,
				);
			}
		}

		wp_send_json( $response );
	}
	
}