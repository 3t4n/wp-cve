<?php
/* wppa-statistics.php
* Package: wp-photo-album-plus
*
* Functions for counts etc
* Common use front and admin
* Version: 8.4.03.002
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// show system statistics
function wppa_show_statistics() {

	$count = wppa_get_total_album_count();
	$y_id = wppa_get_youngest_album_id();
	$y_name = __(wppa_get_album_name($y_id), 'wp-photo-album-plus' );
	$p_id = wppa_get_parentalbumid($y_id);
	$p_name = __(wppa_get_album_name($p_id), 'wp-photo-album-plus' );

	$result = '<div class="wppa-box wppa-nav" style="text-align: center;">';
	$result .= sprintf( _n( 'There is %d photo album', 'There are %d photo albums', $count, 'wp-photo-album-plus' ), $count );
	$result .= ' '.__('The last album added is', 'wp-photo-album-plus' ).' ';
	$result .= '<a href="'.wppa_get_permalink().'wppa-album='.$y_id.'&amp;wppa-cover=0&amp;wppa-occur=1">'.$y_name.'</a>';

	if ($p_id > '0') {
		$result .= __(', a sub album of', 'wp-photo-album-plus' ).' ';
		$result .= '<a href="'.wppa_get_permalink().'wppa-album='.$p_id.'&amp;wppa-cover=0&amp;wppa-occur=1">'.$p_name.'</a>';
	}

	$result .= '.</div>';

	wppa_out( $result );
}

// get number of albums in system
function wppa_get_total_album_count() {
global $wpdb;
static $count;

	if ( ! $count ) {
		$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_albums" );
	}

	return $count;
}

// Get the number of albums the user can upload to
// @: array containing album numbers that are in the pool
function wppa_get_uploadable_album_count( $alb = false ) {
global $wpdb;

	// If album array given, prepare partial where clause to limit album ids.
	if ( is_array( $alb ) ) {
		$where = " id IN (" . implode( ',', $alb ) . ") ";
		$where = trim( $where, ',' );
	}
	else {
		$where = false;
	}

	// Admin, do not look to owner
	if ( wppa_user_is_admin() ) {
		$result = $wpdb->get_var( 	"SELECT COUNT(*) " .
									"FROM $wpdb->wppa_albums " .
									( $where ? "WHERE " . $where : "" )
								);
	}

	// Owner or public
	else {
		$result = $wpdb->get_var( $wpdb->prepare( 	"SELECT COUNT(*) " .
													"FROM $wpdb->wppa_albums " .
													"WHERE owner = '--- public ---' OR owner = %s" .
													( $where ? "AND " . $where : "" ),
													wppa_get_user()
												)
								);
	}

	// Done!
	return $result;
}

// get youngest photo id
function wppa_get_youngest_photo_id( $alb = '0' ) {
global $wpdb;

	if ( ! is_numeric( $alb ) ) {
		$alb = '0';
	}
	if ( $alb ) {
		$result = $wpdb->get_var( "SELECT id FROM $wpdb->wppa_photos
								   WHERE status <> 'pending' AND status <> 'scheduled'
								   AND album = $alb
								   ORDER BY timestamp DESC, id DESC LIMIT 1" );
	}
	else {
		$result = $wpdb->get_var( "SELECT id FROM $wpdb->wppa_photos
								   WHERE status <> 'pending' AND status <> 'scheduled'
								   ORDER BY timestamp DESC, id DESC LIMIT 1" );
	}
	return $result;
}

// get n youngest photo ids
function wppa_get_youngest_photo_ids( $n = '3' ) {
global $wpdb;

	if ( ! wppa_is_int( $n ) ) $n = '3';
	$result = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos
											   WHERE status <> 'pending' AND status <> 'scheduled'
											   ORDER BY timestamp DESC, id DESC LIMIT %d", $n ) );

	return $result;
}

// get youngest album id
function wppa_get_youngest_album_id() {
global $wpdb;

	$result = $wpdb->get_var( "SELECT id FROM $wpdb->wppa_albums ORDER BY timestamp DESC, id DESC LIMIT 1" );

	return $result;
}

// get youngest album name
function wppa_get_youngest_album_name() {
global $wpdb;

	$result = $wpdb->get_var( "SELECT name FROM $wpdb->wppa_albums ORDER BY timestamp DESC, id DESC LIMIT 1" );

	return stripslashes($result);
}

// Bump Clivkcount
function wppa_bump_clickcount( $id ) {
global $wppa_session;

	// Feature enabled?
	if ( ! wppa_switch( 'track_clickcounts' ) ) {
		return;
	}

	// Sanitize input
	if ( ! wppa_is_int( $id ) || $id < '1' ) {
		return;
	}

	// Init clicks in session?
	if ( ! isset ( $wppa_session['click'] ) ) {
		$wppa_session['click'] = array();
	}

	// Remember click and update photodata, only if first time
	if ( ! isset( $wppa_session['click'][$id] ) ) {
		$wppa_session['click'][$id] = true;
		$count = wppa_get_photo_item( $id, 'clicks' );
		$count++;
		wppa_update_photo( $id, ['clicks' => $count] );

		// Invalidate cache
		wppa_cache_photo( 'invalidate', $id );
	}
}

// Bump Viewcount
function wppa_bump_viewcount( $type, $id ) {
global $wppa_session;

	// Feature enabled?
	if ( ! wppa_switch( 'track_viewcounts') ) return;

	// Validate args
	if ( ! wppa_is_int( $id ) ) {
		wppa_log( 'err', 'Non numeric id: ' . $id . ' of type ' . $type . ' found in wppa_bump_viewcount()' );
		return;
	}
	if ( ! in_array( $type, array( 'album', 'photo' ) ) ) {
		wppa_log( 'err', 'Unimplemented type: ' . $type . ' with id ' . $id . ' found in wppa_bump_viewcount()' );
		return;
	}
	if ( $id == 0 ) return; // Vieuwcount of all albums is meaningless

	// Init session for this if not yet done
	if ( ! isset( $wppa_session[$type] ) ) {
		$wppa_session[$type] = array();
	}

	// This one not bumped yet this session?
	if ( ! isset($wppa_session[$type][$id] ) ) {

		// Mark as viewed
		$wppa_session[$type][$id] = true;

		// Dispatch on type
		switch( $type ) {

			case 'album':
				$count = wppa_get_album_item( $id, 'views' );
				$count++;
				wppa_update_album( $id, ['views' => $count] );
				break;

			case 'photo':
				$count = wppa_get_photo_item( $id, 'views' );
				$count++;
				wppa_update_photo( $id, ['views' => $count] );
				break;

			default:
				break;
		}

		// If 'wppa_owner_to_name'
		if ( $type == 'photo' ) {
			wppa_set_owner_to_name( $id );
		}

		// Mark Treecounts need update
		if ( $type == 'photo' ) {
			$alb = wppa_get_photo_item( $id, 'album' );
			wppa_mark_treecounts( $alb );
		}
	}
}

// Flush uploader cache selectively
function wppa_flush_upldr_cache( $key = '', $id = '' ) {

	$upldrcache	= wppa_get_option( 'wppa_upldr_cache', array() );

	foreach ( array_keys( $upldrcache ) as $widget_id ) {

		switch ( $key ) {

			case 'widgetid':
				if ( $id == $widget_id ) {
					unset ( $upldrcache[$widget_id] );
				}
				break;

			case 'photoid':
				$usr = wppa_get_photo_item( $id, 'owner');
				if ( isset ( $upldrcache[$widget_id][$usr] ) ) {
					unset ( $upldrcache[$widget_id][$usr] );
				}
				break;

			case 'username':
				$usr = $id;
				if ( isset ( $upldrcache[$widget_id][$usr] ) ) {
					unset ( $upldrcache[$widget_id][$usr] );
				}
				break;

			case 'all':
				$upldrcache = array();
				break;

			default:
				break;
		}
	}
	wppa_update_option('wppa_upldr_cache', $upldrcache);
}

// Mark treecounts of album $alb as being update required, default: clear all
function wppa_invalidate_treecounts( $alb = '' ) {

	// Sanitize arg
	if ( $alb ) {
		$alb = strval( intval( $alb ) );
	}

	// Album id given
	if ( $alb ) {

		if ( ! wppa_album_exists( $alb ) ) {
			return;
		}

		// Flush this albums treecounts
		wppa_mark_treecounts( $alb );
	}

	// No album id, flush them all
	else {
		$iret = wppa_clear_col( WPPA_ALBUMS, 'treecounts' );
		if ( ! $iret ) {
			wppa_log( 'dbg', 'Unable to clear all treecounts' );
		}
	}
}

// Get and verify correctness of treecount values. Fix if needs update
// Essentially the same as wppa_get_treecounts_a(), but updates if needed
function wppa_verify_treecounts_a( $alb ) {
global $wpdb;

	// Sanitize arg
	if ( $alb ) {
		$alb = strval( intval( $alb ) );
	}

	// Anything to do here?
	if ( ! $alb ) {
		return false;
	}

	// Get data
	$treecounts = wppa_get_treecounts_a( $alb );
	if ( ! $treecounts['needupdate'] ) {
		return $treecounts;
	}

	// Get the ids of the sub albums
	$child_ids 	= $wpdb->get_col( 	"SELECT id " .
									"FROM $wpdb->wppa_albums " .
									"WHERE a_parent = $alb"
								);


	// Items to compute
	/*
	'needupdate',
	'selfalbums',
	'treealbums',
	'selfphotos',
	'treephotos',
	'pendselfphotos',
	'pendtreephotos',
	'scheduledselfphotos',
	'scheduledtreephotos',
	'selfphotoviews',
	'treephotoviews',
	*/

	// Do the dirty work
	$result = array();

	// Need Update
	$result['needupdate'] 			= '0';

	// Self albums
	$result['selfalbums'] 			= $wpdb->get_var( 	"SELECT COUNT(*) " .
														"FROM $wpdb->wppa_albums " .
														"WHERE a_parent = $alb "
													);

	// Tree albums
	$result['treealbums'] 			= $result['selfalbums'];
	foreach( $child_ids as $child ) {

		// Recursively get childrens tree album count and add it
		$child_treecounts = wppa_verify_treecounts_a( $child );
		$result['treealbums'] += $child_treecounts['treealbums'];
	}

	// Self photos
	$result['selfphotos'] 			= $wpdb->get_var( 	"SELECT COUNT(*) " .
														"FROM $wpdb->wppa_photos " .
														"WHERE album = $alb " .
														"AND status <> 'pending' " .
														"AND status <> 'scheduled'"
													);

	// Tree photos
	$result['treephotos'] 			= $result['selfphotos'];
	foreach( $child_ids as $child ) {

		// Recursively get childrens tree photo count and add it
		$child_treecounts = wppa_verify_treecounts_a( $child );
		$result['treephotos'] += $child_treecounts['treephotos'];
	}

	// Pending self photos
	$result['pendselfphotos'] 		= $wpdb->get_var( 	"SELECT COUNT(*) " .
														"FROM $wpdb->wppa_photos " .
														"WHERE album = $alb " .
														"AND status = 'pending'"
													);

	// Pending tree photos
	$result['pendtreephotos'] 		= $result['pendselfphotos'];
	foreach( $child_ids as $child ) {

		// Recursively get childrens pend tree photo count and add it
		$child_treecounts = wppa_verify_treecounts_a( $child );
		$result['pendtreephotos'] += $child_treecounts['pendtreephotos'];
	}

	// Scheduled self photos
	$result['scheduledselfphotos'] 	= $wpdb->get_var( 	"SELECT COUNT(*) " .
														"FROM $wpdb->wppa_photos " .
														"WHERE album = $alb " .
														"AND status = 'scheduled'"
													);

	// Scheduled tree photos
	$result['scheduledtreephotos'] 	= $result['scheduledselfphotos'];
	foreach( $child_ids as $child ) {

		// Recursively get childrens scheduled tree photo views and add it
		$child_treecounts = wppa_verify_treecounts_a( $child );
		$result['scheduledtreephotos'] += $child_treecounts['scheduledtreephotos'];
	}

	// Self photo views
	$views = $wpdb->get_col( "SELECT views FROM $wpdb->wppa_photos WHERE album = $alb" );
	$result['selfphotoviews'] 		= array_sum( $views );

	// Tree photo views
	$result['treephotoviews'] 		= $result['selfphotoviews'];
	foreach( $child_ids as $child ) {

		// Recursively get childrens pend tree photo views and add it
		$child_treecounts = wppa_verify_treecounts_a( $child );
		$result['treephotoviews'] += $child_treecounts['treephotoviews'];
	}

	// Save result
	wppa_save_treecount_a( $alb, $result );

	// Done
	return $result;
}

// Set treecounts to need update
function wppa_mark_treecounts( $alb ) {

	// Sanitize arg
	if ( $alb ) {
		$alb = strval( intval( $alb ) );
	}

	if ( ! wppa_album_exists( $alb ) ) {
		return;
	}

	// Do it
	if ( $alb ) {
		$treecounts = wppa_get_treecounts_a( $alb );
		if ( is_array( $treecounts ) ) {
			$treecounts['needupdate'] = '1';
			wppa_save_treecount_a( $alb, $treecounts );
			$parent = wppa_get_album_item( $alb, 'a_parent' );

			// Bubble up
			if ( $parent > '0' ) {
				wppa_mark_treecounts( $parent );
			}
		}
	}

	// Schedule cron to fix it up
	wppa_schedule_treecount_update();
}

// Save update treecount array
function wppa_save_treecount_a( $alb, $treecounts ) {

	// Sanitize arg
	if ( $alb ) {
		$alb = strval( intval( $alb ) );
	}
	if ( is_array( $treecounts ) ) {
		foreach( array_keys( $treecounts ) as $key ) {
			$treecounts[$key] = strval( intval( $treecounts[$key] ) );
		}
	}

	// Do it
	if ( $alb && is_array( $treecounts ) ) {

		$keys 	= array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10' );
		if ( count( $keys ) == count( $treecounts ) ) {
			$result = array_combine( $keys, $treecounts );
			$result = serialize( $result );
		}
		else {
			$result = '';
		}

		wppa_update_album( $alb, ['treecounts' => $result] );
		wppa_cache_album( 'invalidate', $alb );
	}
}

// Get the treecounts for album $alb
function wppa_get_treecounts_a( $alb, $update = false ) {

	// Array index defintions
	$needupdate 			= '0';
	$selfalbums 			= '1';
	$treealbums 			= '2';
	$selfphotos 			= '3';
	$treephotos 			= '4';
	$pendselfphotos 		= '5';
	$pendtreephotos 		= '6';
	$scheduledselfphotos 	= '7';
	$scheduledtreephotos 	= '8';
	$selfphotoviews 		= '9';
	$treephotoviews 		= '10';

	// Sanitize arg
	if ( $alb ) {
		$alb = strval( intval( $alb ) );
	}

	// If album id given
	if ( $alb ) {

		// Get db data field
		$treecount_string = wppa_get_album_item( $alb, 'treecounts' );

		// Convert to array
		if ( $treecount_string ) {
			$treecount_array = wppa_unserialize( $treecount_string );
		}
		else {
			$treecount_array = array();
		}

		// Set up defaults
		$defaults = array( 1,0,0,0,0,0,0,0,0,0,0 );

		// If array is too big, trim it down to the currrent size
		if ( count( $treecount_array ) > count( $defaults ) ) {
			$treecount_array = array_slice( $treecount_array, 0, count( $defaults ) );
		}

		// If array is too small, fill in missing elements wiuth default values
		if ( count( $treecount_array ) < count( $defaults ) ) {
			$i = 0;
			$n = count( $defaults );
			while ( $i < $n ) {
				if ( ! isset( $treecount_array[$i] ) ) {
					$treecount_array[$i] = $defaults[$i];
				}
				$i++;
			}
		}

		// Convert numeric keys to alphabetic keys
		$keys = array( 	'needupdate',
						'selfalbums',
						'treealbums',
						'selfphotos',
						'treephotos',
						'pendselfphotos',
						'pendtreephotos',
						'scheduledselfphotos',
						'scheduledtreephotos',
						'selfphotoviews',
						'treephotoviews',
						);

		$result = array_combine( $keys, $treecount_array );

		if ( $result['needupdate'] && $update ) {
			return wppa_verify_treecounts_a( $alb );
		}
	}

	// No album given
	else {
		$result = false;
	}

	// Done
	return $result;
}

// An album is physically empty when it has no sub albums, no photos and no recoverable deleted photos
function wppa_is_album_empty( $id ) {
global $wpdb;

	// Sub albums?
	$has_albums = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_albums WHERE a_parent = %s", $id ) );
	if ( $has_albums ) {
		return false;
	}

	// Photos?
	$has_photos = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE album = %s", $id ) );
	if ( $has_photos ) {
		return false;
	}

	// Deleted photos?
	$has_deleted_photos = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE album = %s", - ( $id + '9' ) ) );
	if ( $has_deleted_photos ) {
		return false;
	}

	return true;
}

// See if an album is visible to the current user. This takes 'skip empty albums' into account.
function wppa_is_album_visible( $id ) {
global $wpdb;
static $user;
static $admin;
static $login;

	// Validate arg
	if ( ! wppa_is_int( $id ) || $id < '1' || ! wppa_cache_album( $id ) ) {
		return false;
	}

	// Get usefull data
	$status = wppa_get_album_item( $id, 'status' );
	$owner  = wppa_get_album_item( $id, 'owner' );
	if ( $user === NULL ) $user = wppa_get_user();
	if ( $admin === NULL ) $admin = wppa_user_is_admin();
	if ( $login === NULL ) $login = is_user_logged_in();

	// Always visible for admin and owner
	if ( $admin || $user == $owner ) {
		return true;
	}

	// Dispaych on status
	switch( $status ) {
		case 'hidden':
			return false;
			break;
		case 'private':
			if ( ! $login ) {
				return false;
			}
			break;
		default: 		//	case 'publish':
			break;
	}

	// If the user can upload to it, it is visible
	if ( wppa_switch( 'user_upload_on' ) && $owner == '--- public ---' ) {
		return true;
	}

	// The user is logged in or the status is publish
	// Dispatch on whether we skip empty albums
	if ( wppa_switch( 'skip_empty_albums' ) ) {

		// Look at photos
		$photos = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE album = %s", $id ) );
		foreach( $photos as $p ) {

			if ( wppa_is_photo_visible( $p ) ) {
				return true; 	// Found at least one
			}
		}

		// Look at sub albums
		$albs = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE a_parent = %s", $id ) );
		foreach( $albs as $a ) {

			if ( wppa_is_album_visible( $a ) ) {
				return true; 	// Found at least one
			}
		}
	}
	else {
		return true;
	}

	// This album is empty and therefor not visible for the current user
	return false;
}

// Get number of visible 1st generation albums
function wppa_get_visible_album_count( $id ) {

	$result = '0';

	if ( ! wppa_is_album_visible( $id ) ) {
		return '0';
	}

	$albs = wppa_get_sub_album_ids( $id );
	if ( $albs ) {
		foreach( $albs as $alb ) {
			if ( wppa_is_album_visible( $alb ) ) {
				$result++;
			}
		}
	}
	return $result;
}

// Get the total tree number of visible albums, assuming this album is visible
function wppa_get_visible_subtree_album_count( $id, $first = true ) {
static $cache;

	if ( ! is_array( $cache ) ) $cache = array();
	if ( isset( $cache[$id] ) ) {
		return $cache[$id];
	}
	$result = '0';

	$albs = wppa_get_sub_album_ids( $id );
	if ( $albs ) {
		foreach( $albs as $alb ) {
			if ( wppa_is_album_visible( $alb ) ) {
				$result += wppa_get_visible_subtree_album_count( $alb, false );
			}
		}
	}

	if ( ! $first ) $result += '1'; // Myself
	$cache[$id] = $result;
	return $result;
}

// Find out if a given photo is visible for the current user
function wppa_is_photo_visible( $id ) {
static $user;
static $admin;
static $login;

	// Validate arg
	if ( ! wppa_is_int( $id ) || $id < '0' ) {
		wppa_log( 'err', 'Invalid arg in wppa_is_photo_visible: '.serialize($id) );
		return false;
	}

	// Get usefull data
	$status = wppa_get_photo_item( $id, 'status' );
	$owner  = wppa_get_photo_item( $id, 'owner' );

	if ( $status === false && $owner === false ) return false; // Photo does not exist

	if ( $user === NULL ) $user = wppa_get_user();
	if ( $admin === NULL ) $admin = wppa_user_is_admin();
	if ( $login === NULL ) $login = is_user_logged_in();

	// Dispatch on photo status
	switch( $status ) {
		case 'publish': 	// A published photo found, visible to every one
		case 'featured':
		case 'gold':
		case 'silver':
		case 'bronze':
			return true;
			break;
		case 'private':
			if ( $login ) { 	// A logged in user may see private photos
				return true;
			}
			break;
		case 'pending': 					// Pending and scheduled may be seen by owner
		case 'scheduled':
			if ( $admin || $owner == $user ) {
				return true;
			}
			break;
		default:
			break;
	}

	return false; // This photo is not visible for the current user
}

// Get visible photocount
function wppa_get_visible_photo_count( $id ) {
global $wpdb;
static $cache;

	if ( ! is_array( $cache ) ) $cache = array();
	if ( isset( $cache[$id] ) ) {
		return $cache[$id];
	}

	if ( ! wppa_is_album_visible( $id ) ) {
		return '0';
	}

	if ( wppa_user_is_admin() ) {
		$result = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE album = %s", $id ) );
	}
	elseif ( is_user_logged_in() ) {
		$user   = wppa_get_user();
		$result = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE album = %s AND ( status NOT IN ('pending','scheduled')  OR owner = %s )", $id, $user ) );
	}
	else {
		$result = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE album = %s AND status NOT IN ('pending','private','scheduled')", $id ) );
	}
	$cache[$id] = $result;
	return $result;
}

function wppa_get_visible_subtree_photo_count( $id ) {
static $cache;

	if ( ! is_array( $cache ) ) $cache = array();
	if ( isset( $cache[$id] ) ) {
		return $cache[$id];
	}
	$result = '0';

	$albs = wppa_get_sub_album_ids( $id );
	if ( $albs ) {
		foreach( $albs as $alb ) {
			if ( wppa_is_album_visible( $alb ) ) {
				$result += wppa_get_visible_subtree_photo_count( $alb );
			}
		}
	}

	$result += wppa_get_visible_photo_count( $id );
	$cache[$id] = $result;
	return $result;
}

// Get array of sub album ids, not checked for visibility
function wppa_get_sub_album_ids( $id ) {
global $wpdb;
static $cache;

	if ( ! is_array( $cache ) ) $cache = array();
	if ( isset( $cache[$id] ) ) {
		return $cache[$id];
	}

	$result = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE a_parent = %s", $id ) );

	$cache[$id] = $result;

	return $result;
}