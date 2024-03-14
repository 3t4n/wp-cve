<?php
/* wppa-users.php
* Package: wp-photo-album-plus
*
* Contains user and capabilities related routines
* Version 8.6.04.009
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Get number of users
function wppa_get_user_count() {
global $wpdb;
static $usercount;

	if ( empty( $usercount ) ) {
		$usercount = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->users" );
	}

	return $usercount;
}

// Get all users
function wppa_get_users() {
global $wpdb;
static $users;

	if ( empty($users) ) {
		if ( wppa_get_user_count() > wppa_opt( 'max_users' ) ) {
			$users = array();
		}
		else {
			$users = $wpdb->get_results( "SELECT * FROM $wpdb->users
										  ORDER BY display_name", ARRAY_A );
		}
	}
	return $users;
}

// Get the options html for a selectionbox with users
function wppa_get_user_select( $select = '' ) {

	// Init
	$result = '';

	// Unknown user
	$result .= '<option value="" disabled selected>' . __( 'Please select' , 'wp-photo-album-plus' ) . '</option>';

	// Who am i?
	$iam = $select == '' ? wppa_get_user() : $select;

	// Get all users
	$users = wppa_get_users();

	// Add user --- public ---
	$sel = $select == '--- public ---' ? 'selected' : '';
	$result .= '<option value="--- public ---" '.$sel.'>'.__( '--- public ---' , 'wp-photo-album-plus' ).'</option>';

	// Add all users
	foreach ( $users as $usr ) {
		if ( $usr['user_login'] == $iam ) $sel = 'selected';
		else $sel = '';
		$result .= '<option value="' . esc_attr( $usr['user_login'] ) . '" ' . $sel . '>' . sanitize_text_field( $usr['display_name'] ) . '</option>';
	}

	// Done
	return $result;
}

// Wrapper for get_user_by()
function wppa_get_user_by( $key, $user, $check_anon = false ) {

	if ( $user == '#me' ) {
		$user = wppa_get_user();
	}
	$result = get_user_by( $key, $user );

	if ( ! $result ) {
		$result = new WP_User;
		$result -> display_name = __( 'Nomen Nescio', 'wp-photo-album-plus' );
		$result -> login_name = __( 'Anonymous', 'wp-photo-album-plus' );
	}

	if ( $check_anon && wppa_is_anon() ) {
		$result -> display_name = '';
		$result -> login_name = '';
	}

	return $result;
}

// Get current user
// If logged in, return userdata as specified in $type
// If logged out, return IP
function wppa_get_user( $type = 'login' ) {
static $current_user;

	if ( wppa_is_cron() ) {
		return 'cron-job';
	}
	if ( ! $current_user ) {
		$current_user = wp_get_current_user();
	}
	if ( $current_user->exists() ) {
		switch ( $type ) {
			case 'login':
				return $current_user->user_login;
				break;
			case 'display':
				return $current_user->display_name;
				break;
			case 'id':
				return $current_user->ID;
				break;
			case 'email':
				return $current_user->user_email;
				break;
			case 'firstlast':
				return $current_user->user_firstname.' '.$current_user->user_lastname;
				break;
			default:
				return '';
		}
	}
	else {
		return wppa_get_user_ip();
	}
}

// Get display name of owner from login name
function wppa_get_owner_display( $owner ) {

	if ( $owner == '#me' ) {
		$owner = wppa_get_user();
	}
	$usr = get_user_by( 'login', $owner );

	// If user does not exist, probably ip address, return untranslated
	if ( ! $usr ) {
		return $owner;
	}

	return $usr->display_name;
}

// Get display name of login name
function wppa_get_user_display( $login ) {
	return wppa_get_owner_display( $login );
}

// Test if a given user has a given role.
// @1: str role
// @2: int user id, default current user
// returns bool
function wppa_user_is( $role, $user_id = null ) {

 	if ( ! is_user_logged_in() ) return false;

	if ( $role == 'administrator' && wppa_is_user_superuser( $user_id ) ) {
		return true;
	}

	// WP itsself mixes roles and capabilities ( on multisites administrator is a cap of the superadmin )
	if ( $user_id ) {
		return user_can( $user_id, $role );
	}
	else {
		return current_user_can( $role );
	}
}

function wppa_user_is_admin() {
static $bret;

	if ( $bret === NULL ) {
		$bret = wppa_user_is( 'administrator' );
	}
	return $bret;
}

// Test if current user has extended access
// returns bool
function wppa_extended_access() {

	if ( wppa_user_is_admin() ) {
		return true;
	}

	return false;
}

// Test if current user is allowed to craete albums
// returns bool
function wppa_can_create_album() {
global $wpdb;
global $wp_roles;

	// Test for logged out users
	if ( ! is_user_logged_in() ) {
		return false;
	}

	// Admin can do everything
	if ( wppa_user_is_admin() ) {
		return true;
	}

	// A blacklisted user can not create albums
	if ( wppa_is_user_blacklisted() ) {
		return false;
	}

	// Check for global max albums per user setting
	$albs = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_albums
											 WHERE owner = %s", wppa_get_user() ) );
	$gmax = wppa_opt( 'max_albums' );
	if ( $gmax && $albs >= $gmax ) {
		return false;
	}

	// Check for role dependant max albums per user setting
	$user 	= wp_get_current_user();
	$roles 	= $wp_roles->roles;
	foreach ( array_keys( $roles ) as $role ) {

		// Find firste role the user has
		if ( wppa_user_is( $role ) ) {
			$rmax = wppa_get_option( 'wppa_'.$role.'_album_limit_count', '0' );
			if ( ! $rmax || $albs < $rmax ) {
				return true;
			}
			else {
				return false;
			}
		}
	}

	// If a user has no role, deny creation
	return false;
}

// Test if current user is allowed to craete top level albums
// returns bool
function wppa_can_create_top_album() {

	if ( wppa_user_is_admin() ) {
		return true;
	}
	if ( ! wppa_can_create_album() ) {
		return false;
	}
	if ( wppa_switch( 'grant_an_album' ) &&
		'0' != wppa_opt( 'grant_parent' ) ) {
			return false;
		}

	return true;
}

// Test if a user is on the blacklist
// @1: user id, default current user
// returns bool
function wppa_is_user_blacklisted( $user = -1 ) {
global $wpdb;
static $result = -1;

	$cur = ( -1 == $user );

	if ( $cur && -1 != $result ) {	// Already found out for current user
		return $result;
	}

	if ( $cur && ! is_user_logged_in() ) {	// An logged out user can not be on the blacklist
		$result = false;
		return false;
	}

	$blacklist = wppa_get_option( 'wppa_black_listed_users', array() );
	if ( empty( $blacklist ) ) {	// Anybody on the blacklist?
		$result = false;
		return false;
	}

	if ( $cur ) {
		$user = get_current_user_id();
	}

	if ( is_numeric( $user ) ) {
		$user = $wpdb->get_var( $wpdb->prepare( "SELECT user_login FROM $wpdb->users
												 WHERE ID = %d", $user ) );
	}
	else {
		return false;
	}

	if ( $cur ) {
		$result = in_array( $user, $blacklist );	// Save current users result.
	}

	return in_array( $user, $blacklist );
}

function wppa_is_user_superuser( $user = '' ) {

	// Default
	if ( ! $user ) {
		$login = wppa_get_user();
	}
	// Id given
	elseif ( is_numeric( $user ) ) {
		$usr = get_user_by( 'ID', $user );
		$login = $usr -> user_login;
	}
	// Login name given
	else {
		$login = $user;
	}

	$superlist = wppa_get_option( 'wppa_super_users', array() );

	if ( in_array( $login, $superlist ) ) {
		return true;
	}
	return false;
}

// See if the current user may edit a given photo
function wppa_may_user_fe_edit( $id ) {

	// Feature enabled?
	if ( wppa_opt( 'upload_edit' ) == '-none-' ) return false;

	// Blacklisted?
	if ( wppa_is_user_blacklisted() ) return false;

	// Superuser?
	if ( wppa_is_user_superuser() ) return true;

	// Basic user?
	if ( wppa_user_is_basic() ) return false;

	// Can edit albums?
	if ( current_user_can( 'wppa_admin' ) ) return true;

	// Test criteria
	switch( wppa_opt( 'upload_edit_users') ) {

		case 'owner':
			if ( wppa_get_user() == wppa_get_photo_owner( $id ) ) {
				if ( wppa_opt( 'upload_edit_period' ) ) {
					$up = wppa_get_photo_item( $id, 'timestamp' );
					$to = $up + wppa_opt( 'upload_edit_period' );
					if ( time() < $to ) {
						return true;
					}
					else {
						return false;
					}
				}
				else {
					return true;
				}
			}
			break;

	}

	return false;
}

// See if the current user may delete a given photo
function wppa_may_user_fe_delete( $id ) {

	// Basic user?
	if ( wppa_user_is_basic() ) return false;

	// Superuser?
	if ( wppa_is_user_superuser() ) return true;

	// Can edit albums?
	if ( current_user_can( 'wppa_admin' ) ) {
		$alb = wppa_get_photo_item( $id, 'album' );
		if ( wppa_have_access( $alb ) ) {
			return true;
		}
	}

	// If owner and owners may delete?
	if ( wppa_get_user() == wppa_get_photo_owner( $id ) ) {
		if ( wppa_switch( 'upload_delete' ) ) {
			if ( wppa_opt( 'upload_delete_period' ) ) {
				$up = wppa_get_photo_item( $id, 'timestamp' );
				$to = $up + wppa_opt( 'upload_delete_period' );
				if ( time() < $to ) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return true;
			}
		}
	}

	return false;
}

// Convert user loginname or email into a link to the users BuddyPress domain.
// Only if configured and available.
// Otherwise return display name. If user no longer exists, return $owner
function wppa_bp_userlink( $owner, $esc_js = false, $email = false ) {
static $usercache;

	// Init
	if ( ! is_array( $usercache ) ) {
		$usercache = array();
	}

	// This owner already found?
	if ( isset( $usercache[$owner] ) ) {
		$result = $usercache[$owner];
	}

	// Get userdata
	else {
		$user = $email ? get_user_by( 'email', $owner ) : get_user_by( 'login', $owner );

		// User exists
		if ( $user ) {

			// Buddypress link configured and available?
			if ( wppa_switch( 'domain_link_buddypress' ) && function_exists( 'bp_core_get_userlink' ) ) {
				$result = bp_core_get_userlink( $user->ID );
			}
			else {
				$result = $user->display_name;
			}
		}

		// User vanished
		else {
			$result = $owner;
		}
	}

	// Cache the result
	$usercache[$owner] = $result;

	// Filter
	if ( $esc_js ) {
		$result = str_replace( array( '<', '>' ), array( '[', ']' ), $result );
	}

	// Done
	return $result;
}

// Convert login name to displayname
function wppa_display_name( $owner ) {

	// Init
	$result = $owner;

	// Get userdata
	$user = get_user_by( 'login', $owner );
	if ( ! $user ) {
		return $result; // User deleted
	}

	return $user->display_name;
}

// Get array of admin user ids
function wppa_get_admin_ids_a() {
global $wpdb;

	$admins = $wpdb->get_col( $wpdb->prepare( "SELECT user_id
											   FROM $wpdb->usermeta
											   WHERE meta_key = 'wp_capabilities'
											   AND meta_value LIKE %s", '%' . $wpdb->esc_like( 'administrator' ) . '%' ) );
	if ( is_array( $admins ) ) {

		// Remove possble roles like 'xyzadministrator'
		foreach( array_keys( $admins ) as $key ) {
			if ( ! user_can( $admins[$key], 'administrator' ) ) {
				unset( $admins[$key] );
			}
		}
	}
	else {
		$admins = array();
	}

	return $admins;
}

// Get array of superuser ids
function wppa_get_superuser_ids_a() {

	$susers = wppa_get_option( 'wppa_super_users', array() );

	if ( is_array( $susers ) ) {

		// Convert login names to user ids
		foreach ( array_keys( $susers ) as $k ) {
			$u = get_user_by( 'login', $susers[$k] );
			$susers[$k] = $u->ID;
		}
	}
	else {
		$susers = array();
	}

	return $susers;
}

// Has the current user rated photo $id?
function wppa_has_user_rated( $id ) {
global $wpdb;

	if ( is_user_logged_in() ) {
		$uid = wppa_get_user_id();
		$cnt = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_rating WHERE photo = %d AND userid = %d", $id, $uid ) );
	}
	else {
		$uid = wppa_get_user_ip();
		$cnt = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_rating WHERE photo = %d AND ip = %s", $id, $uid ) );
	}
	return ( $cnt > 0 );
}

// Has the current user commented photo $id?
function wppa_has_user_commented( $id ) {
global $wpdb;

	if ( is_user_logged_in() ) {
		$uid = wppa_get_user_id();
		$cnt = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_comments WHERE photo = %d AND userid = %d", $id, $uid ) );
	}
	else {
		$uid = wppa_get_user_ip();
		$cnt = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_comments WHERE photo = %d AND ip = %s", $id, $uid ) );
	}
	return ( $cnt > 0 );
}

// Get current users id
function wppa_get_user_id() {
	$user = wp_get_current_user();
	if ( $user ) {
		return $user->ID;
	}
	return -1;
}

// Get the users ip address
function wppa_get_user_ip() {

	$ip = '';
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ip;
}

// Look for users premium level
function wppa_get_premium( $user_id ) {
global $wp_roles;

	// Init
	$medals = array( 'gold' => false, 'silver' => false, 'bronze' => false, 'plus' => false, 'none' => false );
	$roles 	= $wp_roles->roles;

	foreach ( array_keys( $roles ) as $role ) {

		// Find roles the user has
		if ( user_can( $user_id, $role ) ) {

			// Is this role a premium role?
			$medal = wppa_get_option( 'wppa_medal-' . $role, 'none' );
			$medals[$medal] = true;
			if ( $medal == 'gold' ) {
				return $medal; // There is no higher
			}

		}
	}

	if ( $medals['silver'] ) return 'silver';
	if ( $medals['bronze'] ) return 'bronze';
	if ( $medals['plus'] ) return 'plus';
	return 'none';
}

// Is current user basic level?
function wppa_user_is_basic() {
global $wp_roles;

	// Admin and super can not be basic
	if ( wppa_user_is_admin() ) {
		return false;
	}

	$roles 	= $wp_roles->roles;

	foreach ( array_keys( $roles ) as $role ) {

		// Find roles the user has
		if ( current_user_can( $role ) ) {

			// Is this role limited to basic?
			$medal = wppa_get_option( 'wppa_medal-' . $role, 'none' );
			if ( $medal == 'basic' ) {
				return true;
			}
		}
	}

	// No basic userrole found for the current user
	return false;
}

// Get the html for the premium medal
function wppa_get_premium_html( $userid ) {

	$result 	= '';
	$premium 	= wppa_get_premium( $userid );
	switch( $premium ) {
		case 'gold':
			$title = __('Gold member', 'wp-photo-album-plus' );
			break;
		case 'silver':
			$title = __('Silver member', 'wp-photo-album-plus' );
			break;
		case 'bronze':
			$title = __('Bronze member', 'wp-photo-album-plus' );
			break;
		case 'plus':
			$title = __('Plus member', 'wp-photo-album-plus' );
			break;
		default:
			$title = '';
			break;
	}

	if ( in_array( $premium, array( 'gold', 'silver', 'bronze' ) ) ) {
		$result = '
		<img
			src="' . wppa_get_imgdir( 'medal_' . $premium . '_' . wppa_opt( 'medal_color' ) . '.png' ) . '"
			style="height:1em;cursor:pointer"
			title="' . esc_attr( $title ) . '"
		/>';
	}
	elseif ( $premium == 'plus' ) {
		$result = '
		<img
			src="' . wppa_get_imgdir( 'plus.png' ) . '"
			style="height:1em;cursor:pointer"
			title="' . esc_attr( $title ) . '"
		/>';
	}

	return $result;
}