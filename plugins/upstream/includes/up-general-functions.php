<?php
/**
 * Handle general functions.
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Alias for wp_kses_post
 *
 * @param string $s String data.
 */
function upstream_esc_w( $s ) {
	return wp_kses_post( $s );
}

/**
 * Get a post id,
 * no matter where we are or what we are doing.
 */
function upstream_post_id() {
	global $post, $wp_query;

	$post_id   = 0;
	$get_data  = isset( $_GET ) ? wp_unslash( $_GET ) : array();
	$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();
	$post_type = isset( $post_data['post_type'] ) ? sanitize_text_field( $post_data['post_type'] ) : null;

	// Try getting the post ID from get_the_ID function.
	if ( ! $post_id ) {
		$post_id = function_exists( 'get_the_ID' ) ? get_the_ID() : 0;
	}

	// Try getting the post ID from $post global variable.
	if ( ! $post_id ) {
		$post_id = ( $post && $post instanceof WP_Post ) ? $post->ID : 0;
	}

	// Try getting the post ID from $wp_query global variable.
	if ( ! $post_id ) {
		$post_id = ( $wp_query && $wp_query instanceof WP_Query ) ? $wp_query->get_queried_object_id() : 0;
	}

	// Try getting the post ID from $_GET['post'] variable.
	if ( ! $post_id ) {
		$post_id = isset( $get_data['post'] ) ? absint( $get_data['post'] ) : 0;
	}

	// Try getting the post ID from $_POST variable.
	if ( ! empty( $post_data ) && $post_type && ! $post_id ) {
		switch ( $post_type ) {
			case 'project':
				$nonce = isset( $post_data['upstream_admin_project_form_nonce'] ) ? $post_data['upstream_admin_project_form_nonce'] : null;
				if ( ! wp_verify_nonce( $nonce, 'upstream_admin_project_form' ) ) {
					return $post_id;
				}
				break;
			case 'client':
				$nonce = isset( $post_data['upstream_admin_client_form_nonce'] ) ? $post_data['upstream_admin_client_form_nonce'] : null;
				if ( ! wp_verify_nonce( $nonce, 'upstream_admin_client_form' ) ) {
					return $post_id;
				}
				break;
			case 'upst_milestone':
				$nonce = isset( $post_data['upstream_admin_upst_milestone_form_nonce'] ) ? $post_data['upstream_admin_upst_milestone_form_nonce'] : null;
				if ( ! wp_verify_nonce( $nonce, 'upstream_admin_upst_milestone_form' ) ) {
					return $post_id;
				}
				break;
			case 'up_custom_field':
				$nonce = isset( $post_data['upstream_admin_up_custom_field_form_nonce'] ) ? $post_data['upstream_admin_up_custom_field_form_nonce'] : null;
				if ( ! wp_verify_nonce( $nonce, 'upstream_admin_up_custom_field_form' ) ) {
					return $post_id;
				}
				break;
		}

		if ( ! $post_id ) {
			$post_id = isset( $post_data['post'] ) ? absint( $post_data['post'] ) : 0;
		}
		if ( ! $post_id ) {
			$post_id = isset( $post_data['post_ID'] ) ? absint( $post_data['post_ID'] ) : 0;
		}
		if ( ! $post_id ) {
			$post_id = isset( $post_data['post_id'] ) ? absint( $post_data['post_id'] ) : 0;
		}
		if ( ! $post_id ) {
			$post_id = isset( $post_data['post'] ) ? absint( $post_data['post'] ) : 0;
		}
		if ( ! $post_id ) {
			if ( isset( $post_data['formdata'] ) ) {
				parse_str( sanitize_text_field( $post_data['formdata'] ), $posted );
				if ( isset( $posted['post_id'] ) ) {
					$post_id = (int) $posted['post_id'];
				}
			}
		}
	}

	// Will be an int post ID -- caller will check that it is a real post and accessible.
	return $post_id;
}


/**
 * Url for logging out, depending on client or WP user
 */
function upstream_logout_url() {
	$url = '';

	if (
		( ! empty( $_SESSION ) && isset( $_SESSION['upstream'] ) && isset( $_SESSION['upstream']['user_id'] ) ) ||
		( ! is_user_logged_in() )
	) {
		$url = '?action=logout';
	} else {
		$url = wp_logout_url( get_post_type_archive_link( 'project' ) );
	}

	return apply_filters( 'upstream_logout_url', $url );
}


/**
 * Disable the bugs option
 */
function upstream_disable_bugs() {
	$options      = get_option( 'upstream_general' );
	$disable_bugs = isset( $options['disable_bugs'] ) ? $options['disable_bugs'] : array( 'no' );

	return 'yes' === $disable_bugs[0];
}

/**
 * Upstream_filesytem_enabled
 */
function upstream_filesytem_enabled() {
	$options  = get_option( 'upstream_general' );
	$use_upfs = isset( $options['use_upfs'] ) ? (int) $options['use_upfs'] : 0;

	return 1 === $use_upfs && trim( upstream_filesystem_path() ) !== '';
}

/**
 * Upstream_filesystem_max_size
 */
function upstream_filesystem_max_size() {
	return 100000000;
}

/**
 * Upstream_filesystem_path
 */
function upstream_filesystem_path() {
	$options       = get_option( 'upstream_general' );
	$upfs_location = isset( $options['upfs_location'] ) ? $options['upfs_location'] : '';

	return $upfs_location;
}

/**
 * Set a unique id.
 */
function upstream_admin_set_unique_id() {
	return uniqid( get_current_user_id() );
}

/**
 * Is a user logged in.
 *
 * @since   1.0.0
 */
function upstream_is_user_logged_in() {
	// Checks if the user is logged in through WordPress.
	if ( is_user_logged_in() ) {
		return true;
	}

	return UpStream_Login::user_is_logged_in();
}

/**
 * Checks if current user is a WordPress user or client.
 *
 * @since   1.0.0
 */
function upstream_current_user_id() {
	if ( is_user_logged_in() ) {
		return get_current_user_id();
	} else {
		return isset( $_SESSION['upstream'] ) && isset( $_SESSION['upstream']['user_id'] ) ? absint( $_SESSION['upstream']['user_id'] ) : 0;
	}
}

/**
 * Checks if current user is a WordPress user or client
 */
function upstream_user_type() {
	if ( is_user_logged_in() ) {
		return 'wp';
	} else {
		return 'client';
	}
}

/**
 * Gets the client id that a user belongs to
 *
 * @param int $user_id user_id.
 */
function upstream_get_users_client_id( $user_id ) {
	$r = upstream_get_users_client_ids( $user_id );

	if ( count( $r ) > 0 ) {
		return $r[0];
	}
	return 0;
}

/**
 * Upstream_get_users_client_ids
 *
 * @param int $user_id user_id.
 */
function upstream_get_users_client_ids( $user_id ) {
	global $wpdb;

	$client_ids = Upstream_Cache::get_instance()->get( 'upstream_get_users_client_ids' . $user_id );

	if ( false === $client_ids ) {
		$client_ids = array();

		$rowset = $wpdb->get_results(
			sprintf(
				'
        SELECT `ID`, `post_title`
        FROM `%s`
        WHERE `post_type` = "client"
        AND `post_status` = "publish"',
				$wpdb->prefix . 'posts'
			)
		);

		foreach ( $rowset as $row ) {

			$client_id         = $row->ID;
			$client_users_list = array_filter( (array) get_post_meta( $client_id, '_upstream_new_client_users', true ) );
			if ( count( $client_users_list ) > 0 ) {
				foreach ( $client_users_list as $client_user ) {
					if ( isset( $client_user['user_id'] ) && $client_user['user_id'] == $user_id ) {
						$client_ids[] = $client_id;
					}
				}
			}
		}

		Upstream_Cache::get_instance()->set( 'upstream_get_users_client_ids' . $user_id, $client_ids );

	}

	return $client_ids;
}

/**
 * Get some data for current user
 * returns a single item
 * basically a wrapper for upstream_user_data()
 *
 * @param mixed $item Item data.
 */
function upstream_current_user( $item = null ) {
	if ( ! $item ) {
		return;
	}
	$user_data = upstream_user_data( upstream_current_user_id() );
	$return    = isset( $user_data[ $item ] ) ? $user_data[ $item ] : '';

	return $return;
}

/**
 * Get some data for a user with ID passed
 * returns a single item
 * basically a wrapper for upstream_user_data()
 *
 * @param int   $id Item id.
 * @param mixed $item Item data.
 */
function upstream_user_item( $id = 0, $item = null ) {
	if ( ! $item || ! $id ) {
		return;
	}
	$user_data = upstream_user_data( $id );
	$return    = isset( $user_data[ $item ] ) ? $user_data[ $item ] : '';

	return $return;
}

/**
 * Get the user avatar with full name in tooltips
 *
 * @param int  $user_id User id.
 * @param bool $display_tooltip Is display_tooltip.
 */
function upstream_user_avatar( $user_id, $display_tooltip = true ) {
	if ( ! $user_id ) {
		return;
	}

	// get user data & ignore current user.
	// if we want current user, pass the ID.
	$user_data         = upstream_user_data( $user_id, true );
	$user_display_name = $user_data['display_name'];

	// Display the name only.
	if ( ! upstream_show_users_name() ) {
		$url     = isset( $user_data['avatar'] ) ? $user_data['avatar'] : '';
		$tooltip = (bool) $display_tooltip ?
		sprintf(
			'title="%s" data-toggle="tooltip" data-placement="top" data-original-title="%1$s"',
			esc_attr( $user_display_name )
		) : '';
		$return  = sprintf(
			'<img class="avatar" src="%s" %s />',
			esc_attr( $url ),
			$tooltip
		);
	} else {
		$return = '<span class="avatar_custom_text">' . esc_html( $user_display_name ) . '</span>';
	}

	return apply_filters( 'upstream_user_avatar', $return );
}

/**
 * Get data for any user including current
 * can send id
 *
 * @param mixed $data Data.
 * @param bool  $ignore_current Is ignore_current.
 */
function upstream_user_data_uncached( $data = 0, $ignore_current = false ) {
	// if no data sent, find current user email.
	if ( ! $data && ! $ignore_current ) {
		$data = upstream_get_email_address();
	}

	$user_data = null;
	$type      = is_email( $data ) ? 'email' : 'id';

	$wp_user = Upstream_Cache::get_instance()->get( 'upstream_user_data_by' . $type . '.' . $data );

	if ( false === $wp_user ) {
		$wp_user = get_user_by( $type, $data );
	}
	Upstream_Cache::get_instance()->set( 'upstream_user_data_by' . $type . '.' . $data, $wp_user );

	if ( empty( $wp_user ) ) {
		$wp_user = wp_get_current_user();
	}

	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$is_buddy_press_running = is_plugin_active( 'buddypress/bp-loader.php' ) && class_exists( 'BuddyPress' ) && function_exists( 'bp_core_fetch_avatar' );

	if ( $wp_user && is_object( $wp_user ) ) {
		$role = '';

		if ( isset( $wp_user->roles )
			&& is_array( $wp_user->roles )
			&& count( $wp_user->roles ) > 0
		) {
			$role = ucwords( array_values( $wp_user->roles )[0] );
		}

		if ( in_array( 'upstream_user', $wp_user->roles, true ) ) {
			$role = sprintf(
				// translators: %s: upstream_project_label.
				__( '%s User', 'upstream' ),
				upstream_project_label()
			);
		}
		if ( in_array( 'upstream_manager', $wp_user->roles, true ) ) {
			$role = sprintf(
				// translators: %s: upstream_project_label.
				__( '%s Manager', 'upstream' ),
				upstream_project_label()
			);
		}
		if ( in_array( 'upstream_client_user', $wp_user->roles, true ) ) {
			$role = sprintf(
				// translators: %s: upstream_project_label.
				__( '%s Client User', 'upstream' ),
				upstream_project_label()
			);
		}

		$user_data = array(
			'id'           => $wp_user->ID,
			'fname'        => $wp_user->first_name,
			'lname'        => $wp_user->last_name,
			'full_name'    => $wp_user->first_name . ' ' . $wp_user->last_name,
			'email'        => $wp_user->user_email,
			'display_name' => $wp_user->display_name,
			'phone'        => '',
			'projects'     => upstream_get_users_projects( $wp_user->ID ),
			'role'         => $role,
			'avatar'       => '',
		);

		if ( $is_buddy_press_running ) {
			$user_data['avatar'] = bp_core_fetch_avatar(
				array(
					'item_id' => $wp_user->ID,
					'type'    => 'thumb',
					'html'    => false,
				)
			);
		} else {
			if ( is_plugin_active( 'wp-user-avatar/wp-user-avatar.php' ) && function_exists( 'wpua_functions_init' ) ) {
				global $wp_query;

				$exception     = false;
				$wp_query_data = $wp_query;

				// Make sure WP_Query is loaded.
				if ( ! ( $wp_query instanceof \WP_Query ) ) {
					$wp_query_data = new WP_Query();
				}

				try {
					// Make sure WP User Avatar dependencies are loaded.
					require_once ABSPATH . 'wp-settings.php';
					require_once ABSPATH . 'wp-includes/pluggable.php';
					require_once ABSPATH . 'wp-includes/query.php';
					require_once WP_PLUGIN_DIR . '/wp-user-avatar/wp-user-avatar.php';

					// Load WP User Avatar plugin and its dependencies.
					wpua_functions_init();

					// Retrieve current user id.
					$user_id = upstream_current_user_id();

					// Retrieve the current user avatar URL.
					$user_data['avatar'] = get_wp_user_avatar_src( $wp_user->ID );
				} catch ( Exception $e ) {
					$exception = $e;
				}
			} elseif ( is_plugin_active( 'custom-user-profile-photo/3five_cupp.php' ) && function_exists( 'get_cupp_meta' ) ) {
				$user_data['avatar'] = get_cupp_meta( $wp_user->ID );
			}

			if ( empty( $user_data['avatar'] ) ) {
				if ( ! function_exists( 'get_avatar_url' ) ) {
					require_once ABSPATH . 'wp-includes/link-template.php';
				}

				$user_data['avatar'] = get_avatar_url(
					$wp_user->user_email,
					96,
					get_option( 'avatar_default', 'mystery' )
				);
			}
		}
	} else {
		global $wpdb;
		$users = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * 
				FROM $wpdb->postmeta
				WHERE meta_key = %s AND
				meta_value REGEXP '.*\"%s\";s:[0-9]+:\"%s\".*'",
				array(
					'_upstream_client_users',
					$type,
					$data,
				)
			)
		);

		if ( ! $users ) {
			return;
		}

		$metavalue = unserialize( $users[0]->meta_value );

		foreach ( $metavalue as $key => $user ) {

			// get the matching user.
			if ( in_array( $data, array( $user['id'], $user['email'] ) ) ) {
				$fname     = isset( $user['fname'] ) ? trim( $user['fname'] ) : '';
				$lname     = isset( $user['lname'] ) ? trim( $user['lname'] ) : '';
				$user_data = array(
					'id'        => $user['id'],
					'fname'     => $fname,
					'lname'     => $lname,
					'full_name' => trim( $fname . ' ' . $lname ),
					'email'     => isset( $user['email'] ) ? $user['email'] : '',
					'phone'     => isset( $user['phone'] ) ? $user['phone'] : '',
					'projects'  => upstream_get_users_projects( $user['id'] ),
					'role'      => __( 'Client User', 'upstream' ),
				);

				$display_name              = ! empty( $user_data['full_name'] ) ? $user_data['full_name'] : $user_data['email'];
				$user_data['display_name'] = $display_name;

				if ( $is_buddy_press_running ) {
					$user_data['avatar'] = bp_core_fetch_avatar(
						array(
							'item_id' => $user['id'],
							'type'    => 'thumb',
							'html'    => false,
						)
					);
				} else {
					if ( ! function_exists( 'get_avatar_url' ) ) {
						require_once ABSPATH . 'wp-includes/link-template.php';
					}

					$user_data['avatar'] = get_avatar_url(
						$user['email'],
						96,
						get_option( 'avatar_default', 'mystery' )
					);
				}
			}
		}
	}

	return $user_data;
}

/**
 * Upstream_user_data
 *
 * @param  mixed $data User data.
 * @param  mixed $ignore_current isignore_current.
 */
function upstream_user_data( $data = 0, $ignore_current = false ) {

	$res = Upstream_Cache::get_instance()->get( 'upstream_user_data' . $data . '.' . $ignore_current );

	if ( false === $res ) {

		$res = upstream_user_data_uncached( $data, $ignore_current );
		Upstream_Cache::get_instance()->set( 'upstream_user_data' . $data . '.' . $ignore_current, $res );
	}
	return $res;
}

/**
 * Get a users email address from anything
 * normalizes things as we can pass either nothing, or an id or an email.
 *
 * @param int $user User id.
 */
function upstream_get_email_address( $user = 0 ) {
	// if $user is already an email, simply return it.
	if ( is_email( $user ) ) {
		return $user;
	}

	$email = null;

	// this assumes that $user is a WordPress user id.
	if ( 0 != $user && is_numeric( $user ) ) {
		$wp_user = get_user_by( 'id', $user );
		$email   = $wp_user->user_email;
	}

	// this assumes that $user is a client user id.
	if ( 0 != $user && ! is_numeric( $user ) ) {
		$client_id = upstream_get_users_client_id( $user );
		$users     = get_post_meta( $client_id, '_upstream_client_users', true );
		if ( is_array( $users ) && count( $users ) > 0 ) :
			foreach ( $users as $key => $user ) {
				if ( $user['id'] == $user ) {
					$email = $user['email'];
				}
			}
		endif;
	}

	// this assumes we are a logged in WordPress user looking for our own info.
	if ( ! $user && upstream_user_type() == 'wp' ) {
		$wp_user = get_user_by( 'id', get_current_user_id() );
		$email   = $wp_user->user_email;
	}

	// this assumes we are a logged in client user looking for our own info.
	if ( ! $user && upstream_user_type() == 'client' ) {
		if ( ! isset( $_SESSION['upstream'] ) ) {
			return null;
		}

		$client_id = absint( $_SESSION['upstream']['client_id'] );
		$user_id   = absint( $_SESSION['upstream']['user_id'] );
		$users     = get_post_meta( $client_id, '_upstream_client_users', true );

		if ( is_array( $users ) && count( $users ) > 0 ) {
			foreach ( $users as $key => $user ) {
				if ( $user['id'] == $user_id ) {
					$email = isset( $user['email'] ) ? $user['email'] : '';
				}
			}
		}
	}

	return $email;
}

/**
 * Gets a users name
 * displays full name or email if no name set
 *
 * @param  int  $id User id.
 * @param  bool $show_email Is show_email.
 */
function upstream_users_name( $id = 0, $show_email = false ) {
	$user = upstream_user_data( $id, true );

	if ( ! $user ) {
		return;
	}

	// if first name exists, then show name. Else show email.
	$output = $user['display_name'];

	if ( $show_email && ! empty( $user['email'] ) ) {
		$output .= " <a target='_blank' href='mailto:" . esc_html( $user['email'] ) . "' title='" . esc_html( $user['email'] ) . "'><span class='dashicons dashicons-email-alt'></span></a>";
	}

	return $output;
}


/**
 * Retrieve all projects where the user has access to.
 *
 * @param numeric/WP_User $user    The user to be checked.
 *
 * @return  array
 * @since   1.12.2
 */
function upstream_get_users_projects( $user ) {
	$user = $user instanceof \WP_User ? $user : new \WP_User( $user );
	if ( 0 === $user->ID && ! apply_filters( 'upstream_permissions_filter_page_access', false ) ) {
		return array();
	}

	$data = array();

	$rowset = Upstream_Cache::get_instance()->get( 'upstream_get_users_projects' );

	if ( false === $rowset ) {
		$rowset = (array) get_posts(
			array(
				'post_type'      => 'project',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			)
		);
		Upstream_Cache::get_instance()->set( 'upstream_get_users_projects', $rowset );
	}

	if ( count( $rowset ) > 0 ) {
		foreach ( $rowset as $project ) {
			if ( upstream_user_can_access_project( $user, $project->ID ) ) {
				$data[ $project->ID ] = $project;
			}
		}
	}

	return $data;
}


/**
 * Returns percentages for use in dropdowns.
 */
function upstream_get_percentages_for_dropdown() {
	$array = array(
		''    => '0%',
		'5'   => '5%',
		'10'  => '10%',
		'15'  => '15%',
		'20'  => '20%',
		'25'  => '25%',
		'30'  => '30%',
		'35'  => '35%',
		'40'  => '40%',
		'45'  => '45%',
		'50'  => '50%',
		'55'  => '55%',
		'60'  => '60%',
		'65'  => '65%',
		'70'  => '70%',
		'75'  => '75%',
		'80'  => '80%',
		'85'  => '85%',
		'90'  => '90%',
		'95'  => '95%',
		'100' => '100%',
	);

	return apply_filters( 'upstream_percentages', $array );
}


/**
 * Run date formatting through here
 *
 * @param  mixed $timestamp Timestamp.
 * @param  mixed $date_format Date_format.
 */
function upstream_format_date( $timestamp, $date_format = null ) {
	if ( empty( $date_format ) ) {
		$date_format = get_option( 'date_format', 'Y-m-d' );
	}

	if ( ! $timestamp ) {
		$date = null;
	} else {
		$date = date_i18n( $date_format, $timestamp );
	}

	return apply_filters( 'upstream_format_date', $date, $timestamp );
}


/**
 * Convert date to unixtime format
 *
 * @param  mixed $timestamp Timestamp.
 * @param  mixed $date_format Date_format.
 */
function upstream_date_unixtime( $timestamp, $date_format = null ) {
	// Return empty string if timestamp is empty.
	if ( is_string( $timestamp ) ) {
		$timestamp = trim( $timestamp );
	}

	if ( empty( $timestamp ) ) {
		return '';
	}

	if ( is_null( $date_format ) ) {
		$date_format = get_option( 'date_format', 'Y-m-d' );
	}

	$date = \DateTime::createFromFormat( $date_format, $timestamp );

	if ( $date ) {
		$date = $date->format( 'U' );
	}

	return apply_filters( 'upstream_date_mysql', $date, $timestamp );
}


/**
 * Run time formatting through here
 *
 * @param mixed $timestamp Timestamp.
 */
function upstream_format_time( $timestamp ) {
	if ( ! $timestamp ) {
		$time = null;
	} else {
		$time = date_i18n( get_option( 'time_format' ), $timestamp, false );
	}

	return apply_filters( 'upstream_format_date', $time, $timestamp );
}


/**
 * Used within class-up-project
 *
 * @param mixed $value Timestamp value.
 */
function upstream_timestamp_from_date( $value ) {
	// if blank, return empty string.
	if ( ! $value || empty( $value ) ) {
		return '';
	}

	$timestamp = null;

	// if already a timestamp, return the timestamp.
	if ( is_numeric( $value ) && (int) $value == $value ) {
		$timestamp = $value;
	}

	if ( ! $timestamp ) {
		if ( empty( $value ) || is_array( $value ) ) {
			return 0;
		}

		$date_format = get_option( 'date_format' );
		$date        = DateTime::createFromFormat( $date_format, trim( $value ) );

		if ( $date ) {
			$timestamp = $date->getTimestamp();
		} else {
			$date_object = date_create_from_format( $date_format, $value );
			$timestamp   = $date_object ? $date_object->setTime( 0, 0, 0 )->getTimeStamp() : strtotime( $value );
		}
	}

	// returns the timestamp and sets it to the start of the day.
	return strtotime( 'today', $timestamp );
}


/**
 * Function to convert date format
 * pinched from CMB2
 */
function upstream_php_to_js_dateformat() {
	$format            = get_option( 'date_format' );
	$supported_options = array(
		'd' => 'dd',  // Day, leading 0.
		'j' => 'd',   // Day, no 0.
		'z' => 'o',   // Day of the year, no leading zeroes,.
		// 'D' => 'D',   // Day name short, not sure how it'll work with translations.
		// 'l' => 'DD',  // Day name full, idem before.
		'm' => 'mm',  // Month of the year, leading 0.
		'n' => 'm',   // Month of the year, no leading 0.
		// 'M' => 'M',   // Month, Short name.
		'F' => 'MM',  // Month, full name,.
		'y' => 'y',   // Year, two digit.
		'Y' => 'yy',  // Year, full.
		'H' => 'HH',  // Hour with leading 0 (24 hour).
		'G' => 'H',   // Hour with no leading 0 (24 hour).
		'h' => 'hh',  // Hour with leading 0 (12 hour).
		'g' => 'h',   // Hour with no leading 0 (12 hour),.
		'i' => 'mm',  // Minute with leading 0,.
		's' => 'ss',  // Second with leading 0,.
		'a' => 'tt',  // am/pm.
		'A' => 'TT',   // AM/PM.
	);

	foreach ( $supported_options as $php => $js ) {
		// replaces every instance of a supported option, but skips escaped characters.
		$format = preg_replace( "~(?<!\\\\)$php~", $js, $format );
	}

	$format = preg_replace_callback( '~(?:\\\.)+~', 'upstream_wrap_escaped_chars', $format );

	return $format;
}

/**
 * Upstream_wrap_escaped_chars
 *
 * @param array $value Value.
 */
function upstream_wrap_escaped_chars( $value ) {
	return '&#39;' . str_replace( '\\', '', $value[0] ) . '&#39;';
}

/**
 * Upstream_filter_closed_items
 */
function upstream_filter_closed_items() {
	$option = get_option( 'upstream_general' );

	return isset( $option['filter_closed_items'] ) ? (bool) $option['filter_closed_items'] : false;
}

/**
 * Upstream_archive_closed_items
 */
function upstream_archive_closed_items() {
	$option = get_option( 'upstream_general' );

	return isset( $option['archive_closed_items'] ) ? (bool) $option['archive_closed_items'] : true;
}

/**
 * Upstream_show_users_name
 */
function upstream_show_users_name() {
	$option = get_option( 'upstream_general' );

	return isset( $option['show_users_name'] ) ? (bool) $option['show_users_name'] : false;
}

/**
 * Upstream_logo_url
 */
function upstream_logo_url() {
	$option = get_option( 'upstream_general' );
	$logo   = $option['logo'];

	return apply_filters( 'upstream_logo', $logo );
}

/**
 * Upstream_login_heading
 */
function upstream_login_heading() {
	$option = get_option( 'upstream_general' );

	return isset( $option['login_heading'] ) ? $option['login_heading'] : '';
}

/**
 * Upstream_login_text
 */
function upstream_login_text() {
	$option = get_option( 'upstream_general' );

	return isset( $option['login_text'] ) ? wp_kses_post( wpautop( $option['login_text'] ) ) : '';
}

/**
 * Upstream_admin_email
 */
function upstream_admin_email() {
	$option = get_option( 'upstream_general' );

	return isset( $option['admin_email'] ) ? $option['admin_email'] : '';
}

/**
 * Retrieve the `admin_support_link` option value.
 *
 * @param array $option Array of options. If provided, there's no need to fetch everything again from DB.
 *
 * @return  string
 * @since   1.12.0
 *
 * @see     https://github.com/upstreamplugin/UpStream/issues/81
 */
function upstream_admin_support( $option ) {
	if ( empty( $option ) ) {
		$option = get_option( 'upstream_general' );
	}

	if ( isset( $option['admin_support_link'] ) ) {
		return ! empty( $option['admin_support_link'] ) ? $option['admin_support_link'] : 'mailto:' . $option['admin_email'];
	} else {
		return isset( $option['admin_email'] ) ? 'mailto:' . $option['admin_email'] : '#';
	}
}

/**
 * Retrieve the `admin_support_link_label` option value.
 *
 * @param array $option Array of options. If provided, there's no need to fetch everything again from DB.
 *
 * @return  string
 * @since   1.12.0
 *
 * @see     https://github.com/upstreamplugin/UpStream/issues/81
 */
function upstream_admin_support_label( $option ) {
	if ( empty( $option ) ) {
		$option = get_option( 'upstream_general' );
	}

	if ( isset( $option['admin_support_label'] ) ) {
		return ! empty( $option['admin_support_label'] ) ? $option['admin_support_label'] : '';
	} else {
		return __( 'Contact Admin', 'upstream' );
	}
}

/**
 * Check if Milestones are disabled for the current open project.
 * If no ID is passed, this function tries to guess it by checking $_GET/$_POST vars.
 *
 * @param int $post_id The project ID to be checked.
 *
 * @return  bool
 * @since   1.8.0
 */
function upstream_are_milestones_disabled( $post_id = 0 ) {
	$are_milestones_disabled = false;
	$post_id                 = (int) $post_id;

	if ( $post_id <= 0 ) {
		$post_id = (int) upstream_post_id();
	}

	if ( $post_id > 0 ) {
		$the_meta                = get_post_meta( $post_id, '_upstream_project_disable_milestones', false );
		$are_milestones_disabled = ! empty( $the_meta ) && 'on' === $the_meta[0];
	}

	return $are_milestones_disabled;
}

/**
 * Check if Tasks are disabled for the current open project.
 * If no ID is passed, this function tries to guess it by checking $_GET/$_POST vars.
 *
 * @param int $post_id The project ID to be checked.
 *
 * @return  bool
 * @since   1.8.0
 */
function upstream_are_tasks_disabled( $post_id = 0 ) {
	$are_tasks_disabled = false;
	$post_id            = (int) $post_id;

	if ( $post_id <= 0 ) {
		$post_id = (int) upstream_post_id();
	}

	if ( $post_id > 0 ) {
		$the_meta           = get_post_meta( $post_id, '_upstream_project_disable_tasks', false );
		$are_tasks_disabled = ! empty( $the_meta ) && 'on' === $the_meta[0];
	}

	return $are_tasks_disabled;
}

/**
 * Check if Bugs are disabled for the current open project.
 * If no ID is passed, this function tries to guess it by checking $_GET/$_POST vars.
 *
 * @param int $post_id The project ID to be checked.
 *
 * @return  bool
 * @since   1.8.0
 */
function upstream_are_bugs_disabled( $post_id = 0 ) {
	$are_bugs_disabled = false;
	$post_id           = (int) $post_id;

	if ( $post_id <= 0 ) {
		$post_id = (int) upstream_post_id();
	}

	if ( $post_id > 0 ) {
		$the_meta          = get_post_meta( $post_id, '_upstream_project_disable_bugs', false );
		$are_bugs_disabled = ! empty( $the_meta ) && 'on' === $the_meta[0];
	}

	return $are_bugs_disabled;
}

/**
 * Check if Files are disabled for the current open project.
 * If no ID is passed, this function tries to guess it by checking $_GET/$_POST vars.
 *
 * @param int $post_id The project ID to be checked.
 *
 * @return  bool
 * @since   1.8.0
 */
function upstream_are_files_disabled( $post_id = 0 ) {
	$are_bugs_disabled = false;
	$post_id           = (int) $post_id;

	if ( $post_id <= 0 ) {
		$post_id = (int) upstream_post_id();
	}

	if ( $post_id > 0 ) {
		$the_meta          = get_post_meta( $post_id, '_upstream_project_disable_files', false );
		$are_bugs_disabled = ! empty( $the_meta ) && 'on' === $the_meta[0];
	}

	return $are_bugs_disabled;
}

/**
 * Upstream_tinymce_quicktags_settings
 *
 * @param array $tiny_mce tiny_mce data.
 */
function upstream_tinymce_quicktags_settings( $tiny_mce ) {
	if ( preg_match( '/^(?:_upstream_project_|description|notes|new_message)/i', $tiny_mce['id'] ) ) {
		$buttons = 'strong,em,link,del,ul,ol,li,close';

		/**
		 * Upstream_tinymce_buttons
		 *
		 * @param array $buttons
		 */
		$buttons = apply_filters( 'upstream_tinymce_buttons', $buttons );

		$tiny_mce['buttons'] = $buttons;
	}

	return $tiny_mce;
}

/**
 * Upstream_tinymce_before_init_setup_toolbar
 *
 * @param array $tiny_mce tiny_mce data.
 */
function upstream_tinymce_before_init_setup_toolbar( $tiny_mce ) {
	if ( ! isset( $tiny_mce['selector'] ) ) {
		return $tiny_mce;
	}

	if ( preg_match( '/_upstream_project_|#description|#notes|#new_message|#upstream/i', $tiny_mce['selector'] ) ) {
		/**
		 * Upstream_tinymce_toolbar
		 *
		 * @param string $buttons
		 * @param string $toolbar
		 */
		$tiny_mce['toolbar1'] = apply_filters(
			'upstream_tinymce_toolbar',
			'bold,italic,underline,strikethrough,bullist,numlist,link',
			'toolbar1'
		);

		/**
		 * This filter is documented above.
		 */
		$tiny_mce['toolbar2'] = apply_filters( 'upstream_tinymce_toolbar', '', 'toolbar2' );
		/**
		 * This filter is documented above.
		 */
		$tiny_mce['toolbar3'] = apply_filters( 'upstream_tinymce_toolbar', '', 'toolbar3' );
		/**
		 * This filter is documented above.
		 */
		$tiny_mce['toolbar4'] = apply_filters( 'upstream_tinymce_toolbar', '', 'toolbar4' );
	}

	return $tiny_mce;
}

/**
 * Upstream_tinymce_before_init
 *
 * @param array $tiny_mce tiny_mce data.
 */
function upstream_tinymce_before_init( $tiny_mce ) {
	if ( ! isset( $tiny_mce['selector'] ) ) {
		return $tiny_mce;
	}

	if ( preg_match( '/_upstream_project_|#description|#notes|#new_message|#upstream/i', $tiny_mce['selector'] ) ) {
		if ( isset( $tiny_mce['plugins'] ) ) {
			$plugins_to_be_added = array(
				'charmap',
				'hr',
				'media',
				'paste',
				'tabfocus',
				'textcolor',
				'wpautoresize',
				'wpemoji',
				'wpgallery',
				'wpdialogs',
				'wptextpattern',
				'wpview',
			);

			$plugins_list        = explode( ',', $tiny_mce['plugins'] );
			$plugins_list_unique = array_unique( array_merge( $plugins_list, $plugins_to_be_added ) );

			/**
			 * Upstream_tinymce_plugins
			 *
			 * @param array $plugins_list
			 */
			$plugins_list_unique = apply_filters( 'upstream_tinymce_plugins', $plugins_list_unique );

			$tiny_mce['plugins'] = implode( ',', $plugins_list_unique );
		}

		$external_plugins             = apply_filters( 'upstream_tinymce_external_plugins', array() );
		$tiny_mce['external_plugins'] = wp_json_encode( $external_plugins );
	}

	return $tiny_mce;
}

/**
 * Upstream_disable_tasks
 */
function upstream_disable_tasks() {
	$options = get_option( 'upstream_general' );

	$disable_tasks = isset( $options['disable_tasks'] ) ? (array) $options['disable_tasks'] : array( 'no' );

	$are_tasks_disabled = 'yes' === $disable_tasks[0];

	return $are_tasks_disabled;
}

/**
 * Upstream_disable_milestones
 */
function upstream_disable_milestones() {
	$options = get_option( 'upstream_general' );

	$disable_milestones = isset( $options['disable_milestones'] ) ? (array) $options['disable_milestones'] : array( 'no' );

	$are_milestones_disabled = 'yes' === $disable_milestones[0];

	return $are_milestones_disabled;
}

/**
 * Upstream_disable_milestone_categories
 */
function upstream_disable_milestone_categories() {
	$options = get_option( 'upstream_general' );

	$checked = isset( $options['disable_milestone_categories'] ) ? (array) $options['disable_milestone_categories'] : array( 0 );

	return 1 === $checked[0];
}

/**
 * Upstream_disable_files
 */
function upstream_disable_files() {
	$options = get_option( 'upstream_general' );

	$disable_files = isset( $options['disable_files'] ) ? (array) $options['disable_files'] : array( 'no' );

	$are_files_disabled = 'yes' === $disable_files[0];

	return $are_files_disabled;
}

/**
 * Apply OEmbed filters to a given string in an attempt to render potential embeddable content.
 * This function is called as a callback from CMB2 field method 'escape_cb'.
 *
 * @param mixed       $content    The unescaped content to be analyzed.
 * @param array       $field_args Array of field arguments.
 * @param \CMB2_Field $field      The field instance.
 *
 * @return  mixed                   Escaped value to be displayed.
 * @see     https://github.com/CMB2/CMB2/wiki/Field-Parameters#escape_cb
 *
 * @uses    $wp_embed
 *
 * @since   1.10.0
 */
function upstream_apply_oembed_filters_to_wysiwyg_editor_content( $content, $field_args, $field ) {
	global $wp_embed;

	$content = (string) $content;

	if ( strlen( $content ) > 0 ) {
		$content = $wp_embed->autoembed( $content );
		$content = $wp_embed->run_shortcode( $content );
		$content = wpautop( $content );
		$content = do_shortcode( $content );
	}

	return $content;
}

/**
 * Check if Comments/Discussion are disabled for the current open project.
 * If no ID is passed, this function tries to guess it by checking $_GET/$_POST vars.
 *
 * @param int $post_id The project ID to be checked.
 *
 * @return  bool
 * @since   1.8.0
 */
function upstream_are_comments_disabled( $post_id = 0 ) {
	// General settings.
	$plugin_options = get_option( 'upstream_general' );
	$disabled       = isset( $plugin_options['disable_project_comments'] ) && false === (bool) $plugin_options['disable_project_comments'];

	if ( $disabled ) {
		return true;
	}

	// Project's settings.
	$are_comments_disabled = false;
	$post_id               = (int) $post_id;

	if ( $post_id <= 0 ) {
		$post_id = (int) upstream_post_id();
	}

	if ( $post_id > 0 ) {
		$the_meta              = get_post_meta( $post_id, '_upstream_project_disable_comments', false );
		$are_comments_disabled = ! empty( $the_meta ) && 'on' === $the_meta[0];
	}

	return $are_comments_disabled;
}

/**
 * Check if Projects Categorization is currently disabled.
 *
 * @return  bool
 * @since   1.12.0
 */
function upstream_is_project_categorization_disabled() {
	$options = get_option( 'upstream_general' );

	$is_disabled = isset( $options['disable_categories'] ) ? (bool) $options['disable_categories'] : false;

	return $is_disabled;
}

/**
 * Check if Clients feature is disabled.
 *
 * @return  bool
 * @since   1.12.0
 */
function upstream_is_clients_disabled() {
	$options = get_option( 'upstream_general' );

	$is_disabled = isset( $options['disable_clients'] ) ? (bool) $options['disable_clients'] : false;

	return $is_disabled;
}

/**
 * Check if should Select Users by Default.
 *
 * @return  bool
 */
function upstream_select_users_by_default() {
	$options = get_option( 'upstream_general' );

	$enabled = isset( $options['pre_select_users'] ) ? (bool) $options['pre_select_users'] : false;

	return $enabled;
}

/**
 * Retrieve the avatar URL from a given user.
 *
 * @param int $user_id The user ID.
 *
 * @return  string|bool
 * @since   1.12.0
 */
function upstream_get_user_avatar_url( $user_id ) {
	$user_id = (int) $user_id;
	if ( $user_id <= 0 ) {
		return false;
	}

	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$avatar_url = '';

	// Check if BuddyPress is running so we can borrow its functions.
	$is_buddy_press_running = is_plugin_active( 'buddypress/bp-loader.php' ) && class_exists( 'BuddyPress' ) && function_exists( 'bp_core_fetch_avatar' );
	if ( $is_buddy_press_running ) {
		$avatar_url = (string) bp_core_fetch_avatar(
			array(
				'item_id' => $user_id,
				'type'    => 'thumb',
				'html'    => false,
			)
		);
	}

	// Check if WP-User-Avatar is running so we can borrow its functions.
	if ( empty( $avatar_url ) && is_plugin_active( 'wp-user-avatar/wp-user-avatar.php' ) && function_exists( 'wpua_functions_init' ) ) {
		global $wp_query;

		$wp_query_data = $wp_query;
		$exception     = false;

		// Make sure WP_Query is loaded.
		if ( ! ( $wp_query instanceof \WP_Query ) ) {
			$wp_query_data = new WP_Query();
		}

		try {
			// Make sure WP User Avatar dependencies are loaded.
			require_once ABSPATH . 'wp-settings.php';
			require_once ABSPATH . 'wp-includes/pluggable.php';
			require_once ABSPATH . 'wp-includes/query.php';
			require_once WP_PLUGIN_DIR . '/wp-user-avatar/wp-user-avatar.php';

			// Load WP User Avatar plugin and its dependencies.
			wpua_functions_init();

			// Retrieve the current user avatar URL.
			$avatar_url = (string) get_wp_user_avatar_src( $user_id );
		} catch ( Exception $e ) {
			// Do nothing.
			$exception = $e;
		}
	}

	// Check if Custom User Profile Photo is running so we can borrow its functions.
	if ( empty( $avatar_url ) && is_plugin_active( 'custom-user-profile-photo/3five_cupp.php' ) && function_exists( 'get_cupp_meta' ) ) {
		$avatar_url = (string) get_cupp_meta( $user_id );
	}

	if ( empty( $avatar_url ) ) {
		if ( ! function_exists( 'get_avatar_url' ) ) {
			require_once ABSPATH . 'wp-includes/link-template.php';
		}

		$avatar_url = (string) get_avatar_url( $user_id, 96, get_option( 'avatar_default', 'mystery' ) );
	}

	return $avatar_url;
}

/**
 * Check if the current user is either administrator or UpStream Manager.
 *
 * @param   WP_User $user User object.
 * @return  bool
 * @since   1.12.0
 */
function upstream_is_user_either_manager_or_admin( $user = null ) {
	if ( empty( $user ) || ! ( $user instanceof \WP_User ) ) {
		$user = wp_get_current_user();
	}

	if ( $user->ID > 0 && isset( $user->roles ) ) {
		return count( array_intersect( (array) $user->roles, array( 'administrator', 'upstream_manager' ) ) ) > 0;
	}

	return false;
}

/**
 * Generates a random string of custom length.
 *
 * @param int    $length    The length of the random string.
 * @param string $chars_pool The characters that might compose the string.
 *
 * @return  string
 * @since   1.12.2
 */
function upstream_generate_random_string(
	$length,
	$chars_pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
) {
	$random_string         = '';
	$max_chars_pool_length = mb_strlen( $chars_pool, '8bit' ) - 1;

	for ( $length_index = 0; $length_index < $length; ++$length_index ) {
		$random_string .= $chars_pool[ random_int( 0, $max_chars_pool_length ) ];
	}

	return $random_string;
}

/**
 * Check if comments are allowed on projects.
 *
 * @return  bool
 * @since   1.13.0
 */
function upstream_are_project_comments_enabled() {
	// Retrieve UpStream general options.
	$options     = get_option( 'upstream_general' );
	$option_name = 'disable_project_comments';
	// Check if the option exists.
	if ( isset( $options[ $option_name ] ) ) {
		$allow = (bool) $options[ $option_name ];
	} else {
		$legacy_option_name = 'disable_discussion';
		// Check if user has legacy option set.
		if ( isset( $options[ $legacy_option_name ] ) ) {
			if ( is_array( $options[ $legacy_option_name ] ) || is_object( $options[ $legacy_option_name ] ) ) {
				$options[ $legacy_option_name ] = json_decode( json_encode( $options[ $legacy_option_name ] ), true );
				if ( ! empty( $options[ $legacy_option_name ] ) ) {
					$options[ $legacy_option_name ] = array_reverse( $options[ $legacy_option_name ] );
					$legacy_option_value            = array_pop( $options[ $legacy_option_name ] );
				} else {
					$legacy_option_value = '';
				}
			} else {
				$legacy_option_value = (string) $options[ $legacy_option_name ];
			}

			if ( is_string( $legacy_option_value ) ) {
				$allow = strtoupper( trim( $legacy_option_value ) ) !== 'YES';
			} else {
				$allow = true;
			}

			unset( $options[ $legacy_option_name ] );

			// Migrate existent legacy option.
			$options[ $option_name ] = (int) ! $allow;

			// Update options.
			update_option( 'upstream_general', $options );
		} else {
			// Default value.
			$allow = true;
		}
	}

	return $allow;
}

/**
 * Check if comments are allowed on milestones.
 *
 * @return  bool
 * @since   1.13.0
 */
function upstream_are_comments_enabled_on_milestones() {
	$options = get_option( 'upstream_general' );

	$option_name = 'disable_comments_on_milestones';

	$allow = isset( $options[ $option_name ] ) ? (bool) $options[ $option_name ] : true;

	return $allow;
}

/**
 * Check if comments are allowed on tasks.
 *
 * @return  bool
 * @since   1.13.0
 */
function upstream_are_comments_enabled_on_tasks() {
	$options = get_option( 'upstream_general' );

	$option_name = 'disable_comments_on_tasks';

	$allow = isset( $options[ $option_name ] ) ? (bool) $options[ $option_name ] : true;

	return $allow;
}

/**
 * Check if comments are allowed on bugs.
 *
 * @return  bool
 * @since   1.13.0
 */
function upstream_are_comments_enabled_on_bugs() {
	$options = get_option( 'upstream_general' );

	$option_name = 'disable_comments_on_bugs';

	$allow = isset( $options[ $option_name ] ) ? (bool) $options[ $option_name ] : true;

	return $allow;
}

/**
 * Check if comments are allowed on files.
 *
 * @return  bool
 * @since   1.13.0
 */
function upstream_are_comments_enabled_on_files() {
	$options = get_option( 'upstream_general' );

	$option_name = 'disable_comments_on_files';

	$allow = isset( $options[ $option_name ] ) ? (bool) $options[ $option_name ] : true;

	return $allow;
}

/**
 * Check if should show all the projects in the sidebar.
 *
 * @return  bool
 * @since   1.13.0
 */
function upstream_show_all_projects_in_sidebar() {
	$options = get_option( 'upstream_general' );

	$option_name = 'show_all_projects_sidebar';

	$allow = isset( $options[ $option_name ] ) ? (bool) $options[ $option_name ] : false;

	return $allow;
}


/**
 * Check if should send emails on new comment.
 *
 * @return  bool
 * @since   1.13.0
 */
function upstream_send_notifications_for_new_comments() {
	$options = get_option( 'upstream_general' );

	$option_name = 'send_notifications_for_new_comments';

	$allow = isset( $options[ $option_name ] ) ? (bool) $options[ $option_name ] : true;

	return $allow;
}

/**
 * Slighted modification of PHP's native nl2br function.
 *
 * @param string $subject String to be processed.
 *
 * @return  string
 * @since   1.13.1
 */
function upstream_nl2br( $subject ) {
	// Step 1: Add <br /> tags for each line-break.
	$subject = nl2br( $subject );

	// Step 2: Remove the actual line-breaks.
	$subject = str_replace( "\n", '', $subject );
	$subject = str_replace( "\r", '', $subject );

	// Step 3: Restore the line-breaks that are inside <pre></pre> tags.
	if ( preg_match_all( '/\<pre\>(.*?)\<\/pre\>/', $subject, $match ) ) {
		foreach ( $match as $a ) {
			foreach ( $a as $b ) {
				$subject = str_replace(
					'<pre>' . $b . '</pre>',
					'<pre>' . str_replace( '<br />', PHP_EOL, $b ) . '</pre>',
					$subject
				);
			}
		}
	}

	// Step 4: Removes extra <br /> tags.

	// Before <pre> tags.
	$subject = str_replace( '<br /><br /><br /><pre>', '<br /><br /><pre>', $subject );
	// After </pre> tags.
	$subject = str_replace( '</pre><br /><br />', '</pre><br />', $subject );

	// Arround <ul></ul> tags.
	$subject = str_replace( '<br /><br /><ul>', '<br /><ul>', $subject );
	$subject = str_replace( '</ul><br /><br />', '</ul><br />', $subject );
	// Inside <ul> </ul> tags.
	$subject = str_replace( '<ul><br />', '<ul>', $subject );
	$subject = str_replace( '<br /></ul>', '</ul>', $subject );

	// Arround <ol></ol> tags.
	$subject = str_replace( '<br /><br /><ol>', '<br /><ol>', $subject );
	$subject = str_replace( '</ol><br /><br />', '</ol><br />', $subject );
	// Inside <ol> </ol> tags.
	$subject = str_replace( '<ol><br />', '<ol>', $subject );
	$subject = str_replace( '<br /></ol>', '</ol>', $subject );

	// Arround <li></li> tags.
	$subject = str_replace( '<br /><li>', '<li>', $subject );
	$subject = str_replace( '</li><br />', '</li>', $subject );

	return $subject;
}

/**
 * Upstream Should Run Cmb2
 */
function upstream_should_run_cmb2() {
	global $pagenow;

	$get_data  = isset( $_GET ) ? wp_unslash( $_GET ) : array();
	$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();

	if (
		'post.php' === $pagenow ||
		'post-new.php' === $pagenow
	) {
		$post_id   = isset( $get_data['post'] ) ? absint( $get_data['post'] ) : 0;
		$post_type = get_post_type( $post_id );

		if ( empty( $post_type ) ) {
			$post_type = isset( $get_data['post_type'] ) ? sanitize_text_field( $get_data['post_type'] ) : '';
		}

		// Check post type based on the $_POST and nonce.
		if ( empty( $post_type ) && isset( $post_data['post_type'] ) ) {
			$post_type = sanitize_text_field( $post_data['post_type'] );

			switch ( $post_type ) {
				case 'project':
					$nonce = isset( $post_data['upstream_admin_project_form_nonce'] ) ? $post_data['upstream_admin_project_form_nonce'] : null;
					if ( wp_verify_nonce( $nonce, 'upstream_admin_project_form' ) ) {
						$nonce_passed = true;
					}
					break;
				case 'client':
					$nonce = isset( $post_data['upstream_admin_client_form_nonce'] ) ? $post_data['upstream_admin_client_form_nonce'] : null;
					if ( wp_verify_nonce( $nonce, 'upstream_admin_client_form' ) ) {
						$nonce_passed = true;
					}
					break;
				default:
					$nonce_passed = false;
					break;
			}

			return $nonce_passed;
		}

		$post_types_using_cmb2 = apply_filters( 'upstream:post_types_using_cmb2', array( 'project', 'client' ) );

		if ( in_array( $post_type, $post_types_using_cmb2, true ) ) {
			return true;
		}
	} elseif (
		'admin.php' === $pagenow
		&& isset( $get_data['page'] )
		&& preg_match( '/^upstream_/i', sanitize_text_field( $get_data['page'] ) )
	) {
		return true;
	}

	return false;
}

/**
 * UpstreamGetUsersMap
 */
function upstream_get_users_map() {
	$map = array();

	$rowset = get_users(
		array(
			'fields' => array( 'ID', 'display_name' ),
		)
	);

	foreach ( $rowset as $user ) {
		$map[ (int) $user->ID ] = $user->display_name;
	}

	return $map;
}

/**
 * UpstreamGetDateFormatForJsDatepicker
 */
function upstream_get_date_format_for_js_datepicker() {
	$format            = get_option( 'date_format' );
	$supported_options = array(
		'd' => 'dd',  // Day, leading 0.
		'j' => 'd',   // Day, no 0.
		'z' => 'o',   // Day of the year, no leading zeroes,.
		// 'D' => 'D',   // Day name short, not sure how it'll work with translations.
		// 'l' => 'DD',  // Day name full, idem before.
		'm' => 'mm',  // Month of the year, leading 0.
		'n' => 'm',   // Month of the year, no leading 0.
		// 'M' => 'M',   // Month, Short name.
		'F' => 'MM',  // Month, full name,.
		'y' => 'yy',   // Year, two digit.
		'Y' => 'yyyy',  // Year, full.
		'H' => 'HH',  // Hour with leading 0 (24 hour).
		'G' => 'H',   // Hour with no leading 0 (24 hour).
		'h' => 'hh',  // Hour with leading 0 (12 hour).
		'g' => 'h',   // Hour with no leading 0 (12 hour),.
		'i' => 'mm',  // Minute with leading 0,.
		's' => 'ss',  // Second with leading 0,.
		'a' => 'tt',  // am/pm.
		'A' => 'TT',   // AM/PM.
		'S' => '',   // th, rd, st.
	);

	foreach ( $supported_options as $php => $js ) {
		// replaces every instance of a supported option, but skips escaped characters.
		$format = preg_replace( "~(?<!\\\\)$php~", $js, $format );
	}

	$format = preg_replace_callback( '~(?:\\\.)+~', 'upstream_wrap_escaped_chars', $format );

	return $format;
}

/**
 * UserCanReceiveCommentRepliesNotification
 *
 * @param  int $user_id User id.
 */
function upstream_user_can_receive_comment_replies_notification( $user_id = 0 ) {
	if ( ! is_numeric( $user_id ) ) {
		return false;
	}

	if ( (int) $user_id <= 0 ) {
		$user_id = get_current_user_id();
	}

	$receive_notifications = get_user_meta( $user_id, 'upstream_comment_replies_notification', true ) !== 'no';

	return $receive_notifications;
}

/**
 * Retrieve a list of Milestones available on this instance.
 *
 * @return  array
 * @since   1.17.0
 */
function upstream_get_milestones() {
	$posts = get_posts(
		array(
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'post_type'      => 'upst_milestone',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);

	$milestones = array();

	if ( ! empty( $posts ) ) {
		foreach ( $posts as $post ) {
			$data                    = $post;
			$milestones[ $post->ID ] = $data;
		}
	}

	return $milestones;

}

/**
 * Retrieve a list of Milestones titles available on this instance.
 *
 * @return  array
 * @since   1.17.0
 */
function upstream_get_milestones_titles() {
	$data = array();

	$milestones = upstream_get_milestones();
	foreach ( $milestones as $milestone ) {
		if ( isset( $milestone->ID ) ) {
			$data[ $milestone->ID ] = $milestone->post_title;
		}
	}

	return $data;
}

/**
 * Retrieve a list of Tasks available on this instance.
 *
 * @return  array
 * @since   1.17.0
 */
function upstream_get_tasks_statuses() {
	$data = array();

	$tasks = (array) get_option( 'upstream_tasks' );
	if ( isset( $tasks['statuses'] ) ) {
		foreach ( $tasks['statuses'] as $index => $task ) {
			if ( isset( $task['id'] ) ) {
				$task['order'] = $index;

				$data[ $task['id'] ] = $task;
			}
		}
	}

	return $data;
}

/**
 * Retrieve a list of Task statuses titles available on this instance.
 *
 * @return  array
 * @since   1.17.0
 */
function upstream_get_tasks_statuses_titles() {
	$data = array();

	$tasks = upstream_get_tasks_statuses();
	foreach ( $tasks as $task ) {
		if ( isset( $task['id'] ) ) {
			$data[ $task['id'] ] = $task['name'];
		}
	}

	return $data;
}

/**
 * GetBugsStatuses
 */
function upstream_get_bugs_statuses() {
	$data = array();

	$bugs = (array) get_option( 'upstream_bugs' );
	if ( isset( $bugs['statuses'] ) ) {
		foreach ( $bugs['statuses'] as $index => $bug_status ) {
			if ( isset( $bug_status['id'] ) ) {
				$bug_status['order'] = $index;

				$data[ $bug_status['id'] ] = $bug_status;
			}
		}
	}

	return $data;
}

/**
 * GetBugsSeverities
 */
function upstream_get_bugs_severities() {
	$data = array();

	$bugs = (array) get_option( 'upstream_bugs' );
	if ( isset( $bugs['severities'] ) ) {
		foreach ( $bugs['severities'] as $index => $bug_severity ) {
			if ( isset( $bug_severity['id'] ) ) {
				$bug_severity['order'] = $index;

				$data[ $bug_severity['id'] ] = $bug_severity;
			}
		}
	}

	return $data;
}

/**
 * Upstream_media_unrestricted_roles
 */
function upstream_media_unrestricted_roles() {
	$option = get_option( 'upstream_general' );

	return isset( $option['media_unrestricted_roles'] ) ? $option['media_unrestricted_roles'] : array( 'administrator' );
}


/**
 * DEPRECATED
 */

/**
 * Retrieve a DateTimeZone object of the current WP's timezone.
 * This function falls back to UTC in case of an invalid/empty timezone option.
 *
 * @return  \DateTimeZone
 * @deprecated
 *
 * @since   1.12.3
 */
function upstreamGetTimeZone() {
	$tz = (string) get_option( 'timezone_string' );

	try {
		$the_time_zone = new DateTimeZone( $tz );
	} catch ( Exception $e ) {
		$the_time_zone = new DateTimeZone( 'UTC' );
	}

	return $the_time_zone;
}

/**
 * Convert a given date (UTC)/timestamp to the instance's timezone.
 *
 * @param int|string $subject The date to be converted. If int, assume it's a timestamp.
 * @param bool       $include_time Is include_time.
 *
 * @return  string|false The converted string or false in case of failure.
 * @since   1.11.0
 * @deprecated
 */
function upstream_convert_UTC_date_to_timezone( $subject, $include_time = true ) {
	try {
		$date_format = get_option( 'date_format' );

		if ( true === $include_time ) {
			$date_format .= ' ' . get_option( 'time_format' );
		}

		if ( is_numeric( $subject ) ) {
			$the_date = new DateTime();
			$the_date->setTimestamp( $subject );
		} else {
			$the_date = new DateTime( $subject );
		}

		$instance_timezone = upstreamGetTimeZone();
		$the_date->setTimeZone( $instance_timezone );

		return $the_date->format( $date_format );
	} catch ( Exception $e ) {
		return false;
	}
}

/**
 * Upstream_get_users_display_name
 *
 * @param array $users Users data.
 *
 * @return string
 */
function upstream_get_users_display_name( $users ) {
	return upstream_get_users_display_name_cached( $users );
}

/**
 * Upstream_get_users_display_name_cached
 *
 * @param array $users Users data.
 */
function upstream_get_users_display_name_cached( $users ) {
	$allusers = Upstream_Cache::get_instance()->get( 'upstream_get_users_display_name_cached' );
	if ( false === $allusers ) {
		$allusers = get_users();
		Upstream_Cache::get_instance()->set( 'upstream_get_users_display_name_cached', $allusers );
	}

	$userhash = array();
	foreach ( $users as $u ) {
		$userhash[ $u ] = true;
	}

	$allusernames = array();
	foreach ( $allusers as $user ) {
		if ( isset( $userhash[ $user->ID ] ) ) {
			$allusernames[] = $user->display_name;
		}
	}

	return implode( '<br>', $allusernames );
}

/**
 * Upstream_admin_get_options_milestones
 *
 * @return array
 * @deprecated
 */
function upstream_admin_get_options_milestones() {
	return upstream_project_milestones();
}
