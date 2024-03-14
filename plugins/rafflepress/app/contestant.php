<?php

/*
 * contestants Datatable
 */
function rafflepress_lite_contestants_datatable() {
	if ( check_ajax_referer( 'rafflepress_lite_contestants_datatable' ) ) {
		$data         = array( '' );
		$current_page = 1;
		if ( ! empty( absint( $_GET['current_page'] ) ) ) {
			$current_page = absint( $_GET['current_page'] );
		}
		$per_page = 20;

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

		global $wpdb;

		$tablename          = $wpdb->prefix . 'rafflepress_contestants';
		$entries_tablename  = $wpdb->prefix . 'rafflepress_entries';
		$giveaway_tablename = $wpdb->prefix . 'rafflepress_giveaways';

		// Get name
		$sql           = "SELECT * FROM $giveaway_tablename WHERE id = %d";
		$safe_sql      = $wpdb->prepare( $sql, absint( $_GET['id'] ) );
		$giveaway_data = $wpdb->get_row( $safe_sql );

		$giveaway_name     = $giveaway_data->name;
		$giveaway_settings = json_decode( $giveaway_data->settings );

		$enable_confirmation_email = false;
		if ( ! empty( $giveaway_settings->enable_confirmation_email ) ) {
			$enable_confirmation_email = true;
		}

		// Get records
		$sql = "SELECT *,
             (select count(*) from $entries_tablename  where
             $tablename.`id` = $entries_tablename.`contestant_id` AND deleted_at IS NULL) as `entries_count`
             FROM $tablename 
             ";

		$sql .= ' WHERE giveaway_id = ' . esc_sql( absint( $_GET['id'] ) );

		if ( ! empty( $filter ) ) {
			if ( esc_sql( $filter ) == 'confirmed' ) {
				$sql .= " AND  status = 'confirmed' ";
			}
			if ( esc_sql( $filter ) == 'unconfirmed' ) {
				$sql .= " AND  status = 'unconfirmed' ";
			}
			if ( esc_sql( $filter ) == 'invalid' ) {
				$sql .= " AND  status = 'invalid' ";
			}
			if ( esc_sql( $filter ) == 'winners' ) {
				$sql .= ' AND  winner = 1 ';
			}
			if ( esc_sql( $filter ) == 'all' ) {
				$sql .= " AND  status != 'invalid' ";
			}
		}

		if ( ! empty( $_GET['s'] ) ) {
			$sql .= " AND email LIKE '%" . esc_sql( trim( sanitize_text_field( $_GET['s'] ) ) ) . "%'";
		}

		if ( ! empty( $_GET['orderby'] ) ) {
			$orderby = esc_sql( sanitize_text_field($_GET['orderby']));
			if ( $orderby == 'entries' ) {
				$sql .= ' ORDER BY entries_count';
			}
			if ( $orderby == 'email' ) {
				$sql .= ' ORDER BY email';
			}
			if ( $orderby == 'created_at' ) {
				$sql .= ' ORDER BY created_at';
			}
			if ( $orderby == 'status' ) {
				$sql .= ' ORDER BY status';
			}

			if ( esc_sql(sanitize_text_field( $_GET['order'] )) === 'desc' ) {
				$order = 'DESC';
			} else {
				$order = 'ASC';
			}
			$sql .= ' ' . $order;
		} else {
			$sql .= ' ORDER BY winner DESC,created_at DESC';
		}

		$sql .= " LIMIT $per_page";
		if ( empty( $_GET['s'] ) ) {
			$sql .= ' OFFSET ' . ( $current_page - 1 ) * $per_page;
		}

		$results = $wpdb->get_results( $sql );
		//var_dump($results);
		$data = array();
		foreach ( $results as $v ) {

			   // Format Date
			$created_at = date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $v->created_at ) );

			$class  = '';
			$status = '';
			if ( $v->status == 'confirmed' ) {
				$status = __( 'Yes', 'rafflepress' );
			} elseif ( $v->status == 'unconfirmed' ) {
				$status = __( 'No', 'rafflepress' );
			}

			if ( $v->winner ) {
				$class = 'rafflepress-winner';
			}

			// Load Data
			$data[] = array(
				'id'          => $v->id,
				'email'       => $v->email,
				'name'        => $v->fname . ' ' . $v->lname,
				'status'      => $status,
				'status_raw'  => $v->status,
				'entries'     => $v->entries_count,
				'created_at'  => $created_at,
				'giveaway_id' => $v->giveaway_id,
				'class'       => $class,
				'winner'      => $v->winner,
			);
		}

		$totalitems = rafflepress_lite_contestants_get_data_total( $filter );
		$views      = rafflepress_lite_contestants_get_views( $filter );

		$response = array(
			'rows'                      => $data,
			'giveaway_name'             => $giveaway_name,
			'enable_confirmation_email' => $enable_confirmation_email,
			'totalitems'                => $totalitems,
			'totalpages'                => ceil( $totalitems / $per_page ),
			'currentpage'               => $current_page,
			'views'                     => $views,
		);

		wp_send_json( $response );
	}
}

function rafflepress_lite_contestants_get_data_total( $filter = null ) {
	global $wpdb;

	$tablename = $wpdb->prefix . 'rafflepress_contestants';

	$sql = "SELECT count(id) FROM $tablename";

	$sql .= ' WHERE giveaway_id = ' . esc_sql( absint( $_GET['id'] ) );

	if ( ! empty( $filter ) ) {
		if ( esc_sql( $filter ) == 'confirmed' ) {
			$sql .= " AND  status = 'confirmed' ";
		}
		if ( esc_sql( $filter ) == 'unconfirmed' ) {
			$sql .= " AND  status = 'unconfirmed' ";
		}
		if ( esc_sql( $filter ) == 'invalid' ) {
			$sql .= " AND  status = 'invalid' ";
		}
		if ( esc_sql( $filter ) == 'winners' ) {
			$sql .= ' AND  winner = 1 ';
		}
		if ( esc_sql( $filter ) == 'all' ) {
			$sql .= " AND  status != 'invalid' ";
		}
	} else {
		$sql .= " AND  status != 'invalid' ";
	}

	if ( ! empty( $_GET['s'] ) ) {
		$sql .= " AND email LIKE '%" . esc_sql( trim( sanitize_text_field( $_GET['s'] ) ) ) . "%'";
	}

	$results = $wpdb->get_var( $sql );
	return $results;
}

function rafflepress_lite_contestants_get_views( $filter = null ) {
	$views   = array();
	$current = ( ! empty( $filter ) ? $filter : 'all' );

	global $wpdb;
	$tablename         = $wpdb->prefix . 'rafflepress_contestants';
	$tablename_entries = $wpdb->prefix . 'rafflepress_entries';

	//All link
	$sql = "SELECT count(id) FROM $tablename";

	$sql .= ' WHERE giveaway_id = ' . esc_sql( absint( $_GET['id'] ) ) . " AND deleted_at is null AND status != 'invalid' ";

	$results      = $wpdb->get_var( $sql );
	$class        = ( $current == 'all' ? ' class="current"' : '' );
	$all_url      = remove_query_arg( 'filter' );
	$views['all'] = $results;

	//Confirmed link
	$sql  = "SELECT count(id) FROM $tablename";
	$sql .= ' WHERE giveaway_id = ' . esc_sql( absint( $_GET['id'] ) );
	$sql .= " AND  status = 'confirmed' ";

	$results            = $wpdb->get_var( $sql );
	$confirmed_url      = add_query_arg( 'filter', 'confirmed' );
	$class              = ( $current == 'confirmed' ? ' class="current"' : '' );
	$views['confirmed'] = $results;

	//Unconfirmed link
	$sql  = "SELECT count(id) FROM $tablename";
	$sql .= ' WHERE giveaway_id = ' . esc_sql( absint( $_GET['id'] ) );
	$sql .= " AND  status = 'unconfirmed' ";

	$results              = $wpdb->get_var( $sql );
	$unconfirmed_url      = add_query_arg( 'filter', 'unconfirmed' );
	$class                = ( $current == 'unconfirmed' ? ' class="current"' : '' );
	$views['unconfirmed'] = $results;

	//Invalid link
	$sql  = "SELECT count(id) FROM $tablename";
	$sql .= ' WHERE giveaway_id = ' . esc_sql( absint( $_GET['id'] ) );
	$sql .= " AND  status = 'invalid' ";

	$results          = $wpdb->get_var( $sql );
	$invalid_url      = add_query_arg( 'filter', 'invalid' );
	$class            = ( $current == 'invalid' ? ' class="current"' : '' );
	$views['invalid'] = $results;

	//Winners link
	$sql  = "SELECT count(id) FROM $tablename";
	$sql .= ' WHERE giveaway_id = ' . esc_sql( absint( $_GET['id'] ) );
	$sql .= ' AND  winner = 1 ';

	$results          = $wpdb->get_var( $sql );
	$winners_url      = add_query_arg( 'filter', 'winners' );
	$class            = ( $current == 'winners' ? ' class="current"' : '' );
	$views['winners'] = $results;

	//Entries link
	$sql  = "SELECT count(id) FROM $tablename_entries";
	$sql .= ' WHERE giveaway_id = ' . esc_sql( absint( $_GET['id'] ) );
	$sql .= ' AND  deleted_at IS NULL ';

	$results          = $wpdb->get_var( $sql );
	$views['entries'] = $results;

	return $views;
}


/*
* Confirm Selected contestants
*/
function rafflepress_lite_confirm_selected_contestants() {
	if ( check_ajax_referer( 'rafflepress_lite_confirm_selected_contestants' ) ) {
		if ( current_user_can( apply_filters( 'rafflepress_list_users_capability', 'list_users' ) ) ) {
			if ( ! empty( $_GET['ids'] ) && strpos( $_GET['ids'], ',' ) !== false ) {
				$ids          = array_map( 'intval', explode( ',', $_GET['ids'] ) );
				$how_many     = count( $ids );
				$placeholders = array_fill( 0, $how_many, '%d' );
				$format       = implode( ', ', $placeholders );

				global $wpdb;
				$tablename = $wpdb->prefix . 'rafflepress_contestants';
				$sql       = 'UPDATE ' . $tablename . " SET status = 'confirmed' WHERE id IN ($format)";
				$safe_sql  = $wpdb->prepare( $sql, $ids );
				$result    = $wpdb->query( $safe_sql );

				// confirm any refer a friend entries if email confirmation enabled
				$tablename = $wpdb->prefix . 'rafflepress_entries';
				$sql       = 'UPDATE ' . $tablename . " SET deleted_at = NULL WHERE referrer_id IN ($format)";
				$safe_sql  = $wpdb->prepare( $sql, $$ids );
				$result    = $wpdb->query( $safe_sql );

			} else {
				global $wpdb;
				$tablename = $wpdb->prefix . 'rafflepress_contestants';
				$result    = $wpdb->update(
					$tablename,
					array(
						'status' => 'confirmed',
					),
					array( 'id' => $_GET['ids'] ),
					array(
						'%s',
					),
					array( '%d' )
				);

				// confirm any refer a friend entries if email confirmation enabled
				$tablename = $wpdb->prefix . 'rafflepress_entries';
				$sql       = 'UPDATE ' . $tablename . ' SET deleted_at = NULL WHERE referrer_id = %d';
				$safe_sql  = $wpdb->prepare( $sql, $_GET['ids'] );
				$result    = $wpdb->query( $safe_sql );

			}

			wp_send_json( array( 'status' => true ) );
		}
	}
}

/*
* Delete Invalid Entries
*/
function rafflepress_lite_contestants_resend_email() {
	if ( check_ajax_referer( 'rafflepress_lite_contestants_resend_email' ) ) {

			$contestant_id = absint( $_GET['id'] );
			$giveaway_id   = absint( $_GET['giveaway_id'] );

			global $wpdb;

			$tablename = $wpdb->prefix . 'rafflepress_giveaways';
			$sql       = "SELECT * FROM $tablename WHERE id = %d";
			$safe_sql  = $wpdb->prepare( $sql, $giveaway_id );
			$giveaway  = $wpdb->get_row( $safe_sql );
			$settings  = json_decode( $giveaway->settings );

			$tablename  = $wpdb->prefix . 'rafflepress_contestants';
			$sql        = "SELECT * FROM $tablename WHERE id = %d";
			$safe_sql   = $wpdb->prepare( $sql, $contestant_id );
			$contestant = $wpdb->get_row( $safe_sql );

			// send confirmation email
		if ( ! empty( $settings->enable_confirmation_email ) && $settings->enable_confirmation_email == 'true' ) {
			$slug = rafflepress_lite_get_slug();

			if ( ! empty( $slug ) ) {
				$giveaway_url = home_url() . '?rafflepress_id=' . $giveaway_id;
			}

			$giveaway_url = $giveaway_url . '&confirm=' . $contestant->token . '&id=' . $contestant_id;

			$template_tags = array(
				'{confirmation-link}' => $giveaway_url,
			);
			$msg           = strtr( $settings->confirmation_email, $template_tags );

			$subject = __( '[Action Required] Confirm your entry', 'rafflepress' );
			if ( $settings->confirmation_subject ) {
				$subject = $settings->confirmation_subject;
			}

			$from_email = get_option( 'admin_email' );
			if ( ! empty( $settings->from_email ) ) {
				$from_email = $settings->from_email;
			}

			$from_name = $from_email;
			if ( ! empty( $settings->from_name ) ) {
				$from_name = $settings->from_name;
			}

			$headers   = array();
			$headers[] = "From: $from_name <$from_email>";

			// Send confirmation email

			$mresult = wp_mail( $contestant->email, $subject, $msg, $headers );

			wp_send_json( array( 'status' => true ) );
		}
	}
	wp_send_json( array( 'status' => false ) );
}

/*
* Unconfirm Selected contestants
*/
function rafflepress_lite_unconfirm_selected_contestants() {
	if ( check_ajax_referer( 'rafflepress_lite_unconfirm_selected_contestants' ) ) {
		if ( current_user_can( apply_filters( 'rafflepress_list_users_capability', 'list_users' ) ) ) {
			if ( ! empty( $_GET['ids'] ) && strpos( $_GET['ids'], ',' ) !== false ) {
				$ids          = array_map( 'intval', explode( ',', $_GET['ids'] ) );
				$how_many     = count( $ids );
				$placeholders = array_fill( 0, $how_many, '%d' );
				$format       = implode( ', ', $placeholders );

				global $wpdb;
				$tablename = $wpdb->prefix . 'rafflepress_contestants';
				$sql       = 'UPDATE ' . $tablename . " SET status = 'unconfirmed' WHERE id IN ($format)";
				$safe_sql  = $wpdb->prepare( $sql, $ids );
				$result    = $wpdb->query( $safe_sql );
			} else {
				global $wpdb;
				$tablename = $wpdb->prefix . 'rafflepress_contestants';
				$result    = $wpdb->update(
					$tablename,
					array(
						'status' => 'unconfirmed',
					),
					array( 'id' => $_GET['ids'] ),
					array(
						'%s',
					),
					array( '%d' )
				);
			}
			wp_send_json( array( 'status' => true ) );
		}
	}
}

/*
* Invalid Selected contestants
*/
function rafflepress_lite_invalid_selected_contestants() {
	if ( check_ajax_referer( 'rafflepress_lite_invalid_selected_contestants' ) ) {
		if ( current_user_can( apply_filters( 'rafflepress_list_users_capability', 'list_users' ) ) ) {
			if ( ! empty( $_GET['ids'] ) && strpos( $_GET['ids'], ',' ) !== false ) {
				$ids          = array_map( 'intval', explode( ',', $_GET['ids'] ) );
				$how_many     = count( $ids );
				$placeholders = array_fill( 0, $how_many, '%d' );
				$format       = implode( ', ', $placeholders );

				global $wpdb;
				$tablename = $wpdb->prefix . 'rafflepress_contestants';
				$sql       = 'UPDATE ' . $tablename . " SET status = 'invalid',winner = 0, winning_entry_id = 0 WHERE id IN ($format)";
				$safe_sql  = $wpdb->prepare( $sql, $ids );
				$result    = $wpdb->query( $safe_sql );
			} else {
				global $wpdb;
				$tablename = $wpdb->prefix . 'rafflepress_contestants';
				$result    = $wpdb->update(
					$tablename,
					array(
						'status'           => 'invalid',
						'winner'           => 0,
						'winning_entry_id' => 0,
					),
					array( 'id' => $_GET['ids'] ),
					array(
						'%s',
						'%d',
						'%d',
					),
					array( '%d' )
				);
			}

			wp_send_json( array( 'status' => true ) );
		}
	}
}


/*
* Delete Invalid Entries
*/
function rafflepress_lite_delete_invalid_contestants() {
	if ( check_ajax_referer( 'rafflepress_lite_delete_invalid_contestants' ) ) {
		if ( current_user_can( apply_filters( 'rafflepress_list_users_capability', 'list_users' ) ) ) {
			global $wpdb;
			$tablename = $wpdb->prefix . 'rafflepress_contestants';
			$sql       = "SELECT id FROM $tablename";
			$sql      .= " WHERE status = 'invalid'";
			$ids       = $wpdb->get_col( $sql );

			$how_many     = count( $ids );
			$placeholders = array_fill( 0, $how_many, '%d' );
			$format       = implode( ', ', $placeholders );

			// Deleted contestants
			$tablename = $wpdb->prefix . 'rafflepress_contestants';
			$sql       = 'DELETE FROM ' . $tablename . " WHERE id IN ($format)";
			$safe_sql  = $wpdb->prepare( $sql, $ids );
			$result    = $wpdb->query( $safe_sql );

			// Delete entries
			$tablename = $wpdb->prefix . 'rafflepress_entries';
			$sql       = 'DELETE FROM ' . $tablename . " WHERE contestant_id IN ($format)";
			$safe_sql  = $wpdb->prepare( $sql, $ids );
			$result    = $wpdb->query( $safe_sql );

			wp_send_json( array( 'status' => true ) );
		}
	}
}

/*
* Export Contestants
*/
