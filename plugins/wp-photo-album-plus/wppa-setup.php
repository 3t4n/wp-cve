<?php
/* wppa-setup.php
* Package: wp-photo-album-plus
*
* Contains all the setup stuff
* Version 8.6.01.001
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

/* SETUP */
// It used to be: register_activation_hook(WPPA_FILE, 'wppa_setup');
// The activation hook is useless since wp does no longer call this hook after upgrade of the plugin
// this routine is now called at action init, so also after initial install
// Additionally it can now output messages about success or failure
// Just for people that rely on the healing effect of de-activating and re-activating a plugin
// we still do a setup on activation by faking that we are not up to rev, and so invoking
// the setup on the first init event. This has the advantage that we can display messages
// instead of characters of unexpected output.
// register_activation_hook(WPPA_FILE, 'wppa_activate_plugin'); is in wppa.php
function wppa_activate_plugin() {
	$old_rev = wppa_get_option( 'wppa_revision', '100' );
	$new_rev = $old_rev - '0.01';
	wppa_update_option( 'wppa_revision', $new_rev );
}

// Set force to true to re-run it even when on rev (happens in wppa-settings.php)
// Force will NOT redefine constants
function wppa_setup( $force = false ) {
global $silent;
global $wpdb;
global $wppa_revno;
global $current_user;
global $wppa_error;
global $wppa_cron_maintenance_slugs;

	$old_rev = wppa_get_option( 'wppa_revision', '100' );

	if ( $old_rev == $wppa_revno && ! $force ) return; // Nothing to do here

	$wppa_error = false;	// Init no error

	$create_albums = "CREATE TABLE $wpdb->wppa_albums (
					id bigint(20) NOT NULL,
					name text NOT NULL,
					description text NOT NULL,
					a_order smallint(5) NOT NULL,
					main_photo bigint(20) NOT NULL,
					a_parent bigint(20) NOT NULL,
					p_order_by smallint(5) NOT NULL,
					cover_linktype tinytext NOT NULL,
					cover_linkpage bigint(20) NOT NULL,
					cover_link tinytext NOT NULL,
					owner text NOT NULL,
					timestamp tinytext NOT NULL,
					modified tinytext NOT NULL,
					upload_limit tinytext NOT NULL,
					alt_thumbsize tinytext NOT NULL,
					default_tags tinytext NOT NULL,
					cover_type tinytext NOT NULL,
					suba_order_by tinytext NOT NULL,
					views bigint(20) NOT NULL default '0',
					cats text NOT NULL,
					scheduledtm tinytext NOT NULL,
					custom text NOT NULL,
					crypt tinytext NOT NULL,
					treecounts text NOT NULL,
					wmfile tinytext NOT NULL,
					wmpos tinytext NOT NULL,
					indexdtm tinytext NOT NULL,
					sname text NOT NULL,
					zoomable tinytext NOT NULL,
					displayopts tinytext NOT NULL,
					upload_limit_tree tinytext NOT NULL,
					scheduledel tinytext NOT NULL,
					status tinytext NOT NULL,
					max_children tinytext NOT NULL,
					rml_id tinytext NOT NULL,
					usedby tinytext NOT NULL,
					PRIMARY KEY  (id),
					KEY parentkey (a_parent)
					) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci";

	$create_photos = "CREATE TABLE $wpdb->wppa_photos (
					id bigint(20) NOT NULL,
					album bigint(20) NOT NULL,
					ext tinytext NOT NULL,
					name text NOT NULL,
					description text NOT NULL,
					p_order smallint(5) NOT NULL,
					mean_rating tinytext NOT NULL,
					linkurl text NOT NULL,
					linktitle text NOT NULL,
					linktarget tinytext NOT NULL,
					owner text NOT NULL,
					timestamp tinytext NOT NULL,
					status tinytext NOT NULL,
					rating_count bigint(20) NOT NULL default '0',
					tags text NOT NULL,
					alt tinytext NOT NULL,
					filename tinytext NOT NULL,
					modified tinytext NOT NULL,
					location tinytext NOT NULL,
					views bigint(20) NOT NULL default '0',
					clicks bigint(20) NOT NULL default '0',
					page_id bigint(20) NOT NULL default '0',
					exifdtm tinytext NOT NULL,
					videox smallint(5) NOT NULL default '0',
					videoy smallint(5) NOT NULL default '0',
					thumbx smallint(5) NOT NULL default '0',
					thumby smallint(5) NOT NULL default '0',
					photox smallint(5) NOT NULL default '0',
					photoy smallint(5) NOT NULL default '0',
					scheduledtm tinytext NOT NULL,
					scheduledel tinytext NOT NULL,
					custom text NOT NULL,
					stereo smallint NOT NULL default '0',
					crypt tinytext NOT NULL,
					magickstack text NOT NULL,
					indexdtm tinytext NOT NULL,
					panorama smallint(5) NOT NULL default '0',
					angle smallint(5) NOT NULL default '0',
					sname text NOT NULL,
					dlcount bigint(20) NOT NULL default '0',
					thumblock smallint(5) default '0',
					duration tinytext NOT NULL,
					rml_id tinytext NOT NULL,
					usedby tinytext NOT NULL,
					misc tinytext NOT NULL,
					PRIMARY KEY  (id),
					KEY albumkey (album),
					KEY statuskey (status(6))
					) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci";

	$create_rating = "CREATE TABLE " . WPPA_RATING . " (
					id bigint(20) NOT NULL,
					timestamp tinytext NOT NULL,
					photo bigint(20) NOT NULL,
					value smallint(5) NOT NULL,
					user text NOT NULL,
					userid int NOT NULL,
					ip tinytext NOT NULL,
					status tinytext NOT NULL,
					PRIMARY KEY  (id),
					KEY photokey (photo)
					) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci";

	$create_comments = "CREATE TABLE " . WPPA_COMMENTS . " (
					id bigint(20) NOT NULL,
					timestamp tinytext NOT NULL,
					photo bigint(20) NOT NULL,
					user text NOT NULL,
					userid int NOT NULL,
					ip tinytext NOT NULL,
					email text NOT NULL,
					comment text NOT NULL,
					status tinytext NOT NULL,
					PRIMARY KEY  (id),
					KEY photokey (photo)
					) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci";

	$create_iptc = "CREATE TABLE " . WPPA_IPTC . " (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					photo bigint(20) NOT NULL,
					tag tinytext NOT NULL,
					description text NOT NULL,
					status tinytext NOT NULL,
					PRIMARY KEY  (id),
					KEY photokey (photo)
					) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci";

	$create_exif = "CREATE TABLE " . WPPA_EXIF . " (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					photo bigint(20) NOT NULL,
					tag tinytext NOT NULL,
					description text NOT NULL,
					status tinytext NOT NULL,
					f_description text NOT NULL,
					brand tinytext NOT NULL,
					PRIMARY KEY  (id),
					KEY photokey (photo)
					) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci";

	$create_index = "CREATE TABLE " . WPPA_INDEX . " (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					slug tinytext NOT NULL,
					albums text NOT NULL,
					photos mediumtext NOT NULL,
					PRIMARY KEY  (id),
					KEY slugkey (slug(20))
					) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci";

	$create_session = "CREATE TABLE " . WPPA_SESSION . " (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					session tinytext NOT NULL,
					timestamp tinytext NOT NULL,
					user tinytext NOT NULL,
					ip tinytext NOT NULL,
					status tinytext NOT NULL,
					data text NOT NULL,
					count bigint(20) NOT NULL default '0',
					PRIMARY KEY  (id),
					KEY sessionkey (session(20))
					) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci";

	$create_caches = "CREATE TABLE " . WPPA_CACHES . " (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					filename tinytext NOT NULL,
					albums text NOT NULL,
					photos text NOT NULL,
					page bigint(20) NOT NULL,
					other tinytext NOT NULL,
					PRIMARY KEY  (id)
					) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	// Create or update db tables
	$tn = array( WPPA_ALBUMS, WPPA_PHOTOS, WPPA_RATING, WPPA_COMMENTS, WPPA_IPTC, WPPA_EXIF, WPPA_INDEX, WPPA_SESSION, WPPA_CACHES );
	$tc = array( $create_albums, $create_photos, $create_rating, $create_comments, $create_iptc, $create_exif, $create_index, $create_session, $create_caches );

	// Find existing tables
	$r = $wpdb->get_results( "SHOW TABLES", ARRAY_A );
	$s = array();
	foreach( $r as $i ) {
		foreach( $i as $j ) {
			$existing_tables[] = $j;
		}
	}

	// Add missing tables
	// We do this here because dbDelta() generates an error when a new table is created
	$idx = 0;
	while ( $idx < 9 ) {
		if ( ! in_array( $tn[$idx], $existing_tables ) ) {
			$bret = $wpdb->query( $tc[$idx] );
			if ( ! $bret ) {
				wppa_log( 'err', 'Failed to create ' . $tn[$idx] );
			}
		}
		$idx++;
	}

	// Change longtext into text in existring tables
	if ( $old_rev < '8400001' ) {
		$wpdb->query( "ALTER TABLE $wpdb->wppa_albums CHANGE `custom` `custom` TEXT CHARACTER SET utf8mb4 NOT NULL;" );
		$wpdb->query( "ALTER TABLE $wpdb->wppa_photos CHANGE `custom` `custom` TEXT CHARACTER SET utf8mb4 NOT NULL;" );
		$wpdb->query( "ALTER TABLE $wpdb->wppa_albums CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 NOT NULL;" );
		$wpdb->query( "ALTER TABLE $wpdb->wppa_photos CHANGE `description` `description` TEXT CHARACTER SET utf8mb4 NOT NULL;" );
	}

	// Update tables with possibly new fields
	$idx = 0;
	while ( $idx < 9 ) {
		@ dbDelta( $tc[$idx] );
		$idx++;
	}

	// Change collate
	if ( $old_rev < '8400001' ) {
		foreach( $tn as $t ) {
			$wpdb->query( "ALTER TABLE $t CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci" );
			wppa_log( 'obs', "$t converted to support emoji's" );
			if ( wppa_is_time_up() ) {
				return;
			}
		}
		wppa_log( 'obs', 'DB UPDATED' );
	}

	// Clear cache and statistics
	wppa_clear_cache( array( 'force' => true ) );
	delete_option( 'wppa_cache_hits' );
	delete_option( 'wppa_cache_misses' );

	// Convert any changed and remove obsolete setting options
	if ( $old_rev > '100' ) {	// On update only

		if ( $old_rev <= '7211' ) {

			// Fix bug because of different usernames in rating and comments
			if ( wppa_get_option( 'wppa_vote_needs_comment' ) == 'yes' || wppa_get_option( 'wppa_comment_need_vote' ) == 'yes' ) {
				$wpdb->query( "UPDATE $wpdb->wppa_rating SET status = 'publish' WHERE status = 'pending'" );
				$wpdb->query( "UPDATE $wpdb->wppa_comments SET status = 'approved' WHERE status = 'pending'" );
				wppa_schedule_maintenance_proc( 'wppa_rerate' );
			}
		}

		if ( $old_rev <= '7300' ) {
			wppa_schedule_maintenance_proc( 'wppa_fix_userids' );
		}

		if ( $old_rev <= '7400' ) {
			if ( wppa_get_option( 'wppa_enable_admins_choice', 'no' ) == 'yes' ) {
				wppa_update_option( 'wppa_admins_choice', 'admin' );
			}
		}

		if ( $old_rev <= '7610' ) {
			delete_option( 'wppa_child_list' );
		}

		if ( $old_rev <= '7702006' ) {
			$wpdb->query( "UPDATE $wpdb->wppa_albums SET upload_limit_tree = '0' WHERE upload_limit_tree = ''" );
			if ( wppa_opt( 'area_size' ) == '' ) delete_option( 'wppa_area_size' );
		}

		if ( $old_rev <= '7704999' ) {
			$sent_mails = wppa_get_option( 'wppa_sent_mails', '' );
			if ( $sent_mails ) {
				$arr = explode( ',', $sent_mails );
				foreach( $arr as $item ) {
					set_transient( 'wppa_' . $item, getmypid(), WEEK_IN_SECONDS );
				}
				delete_option( 'wppa_sent_mails' );
			}
		}

		if ( $old_rev <= '8001000' ) {
			if ( wppa_get_option( 'wppa_resize_on_upload', 'yes' ) == 'no' ) {
				wppa_update_option( 'wppa_resize_to', '-1' );
			}
			if ( wppa_get_option( 'wppa_keep_source_admin', 'yes' ) == 'no' &&
				wppa_get_option( 'wppa_keep_source_frontend', 'yes' ) == 'no' ) {
					wppa_update_option( 'wppa_keep_source', 'no' );
			}
			if ( wppa_get_option( 'wppa_lazy', 'no' ) == 'no' ) {
				wppa_update_option( 'wppa_lazy', 'none' );
			}
			elseif ( wppa_get_option( 'wppa_lazy', 'no' ) == 'yes' ) {
				wppa_update_option( 'wppa_lazy', 'all' );
			}
		}

		if ( $old_rev <= '8004004' ) {
			$wpdb->query( "UPDATE $wpdb->wppa_albums SET status = 'publish' WHERE status = ''" );
		}

		if ( $old_rev <= '8110000' ) {
			if ( wppa_get_option( 'wppa_use_thumb_popup', 'yes' ) == 'no' ) {
				wppa_update_option( 'wppa_thumb_popup', 'none' );
			}
		}

		if ( $old_rev <= '8204006' ) {
			wppa_invalidate_treecounts();
		}

		if ( $old_rev <= '8303005' ) {
			if ( is_array( $wppa_cron_maintenance_slugs ) ) {
				foreach( $wppa_cron_maintenance_slugs as $slug ) {
					delete_option( $slug . '_lasttimestamp' );
					delete_option( $slug . '_user' );
					delete_option( $slug . '_status' );
					delete_option( $slug . '_togo' );
				}
			}
			wppa_schedule_cleanup( true );
		}

		if ( $old_rev <= '8403099' ) {
			if ( get_option( 'wppa_slideshow_linktype' ) == 'same' ) {
				update_option( 'wppa_slideshow_linktype', 'lightbox' );
			}
		}

		$ca = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_albums WHERE crypt = ''" );
		$cp = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE crypt = ''" );
		if ( $ca ) {
			wppa_schedule_maintenance_proc( 'wppa_crypt_albums' );
		}
		if ( $cp ) {
			wppa_schedule_maintenance_proc( 'wppa_crypt_photos' );
		}

	}

	// Sanitize nicescroll opts
	$nso = wppa_get_option( 'wppa_nicescroll_opts', '' );
	if ( $nso ) {
		wppa_update_option( 'wppa_nicescroll_opts', wppa_sanitize_nso( $nso ) );
	}

	// Check required directories
	if ( ! wppa_check_dirs() ) $wppa_error = true;

	// Create .htaccess file in .../wp-content/uploads/wppa
	wppa_create_wppa_htaccess();

	// Copy factory supplied watermarks
	$frompath = WPPA_PATH . '/watermarks';
	$watermarks = wppa_glob($frompath . '/*.png');
	if ( is_array($watermarks) ) {
		foreach ($watermarks as $fromfile) {
			$tofile = WPPA_UPLOAD_PATH . '/watermarks/' . basename($fromfile);
			wppa_copy( $fromfile, $tofile );
		}
	}

	// Copy factory supplied watermark fonts
	$frompath = WPPA_PATH . '/fonts';
	$fonts = wppa_glob($frompath . '/*');
	if ( is_array($fonts) ) {
		foreach ($fonts as $fromfile) {
			if ( is_file ( $fromfile ) ) {
				$tofile = WPPA_UPLOAD_PATH . '/fonts/' . basename($fromfile);
				wppa_copy( $fromfile, $tofile );
			}
		}
	}

	// Copy audiostub.jpg, the default audiostub
	$fromfile = WPPA_PATH . '/img/audiostub.jpg';
	$tofile = WPPA_UPLOAD_PATH . '/audiostub.jpg';
	if ( ! wppa_is_file( $tofile ) ) {
		wppa_copy( $fromfile, $tofile );
	}

	// Copy transparent.png
	$fromfile = WPPA_PATH . '/img/transparent.png';
	$tofile = WPPA_UPLOAD_PATH . '/transparent.png';
	if ( ! wppa_is_file( $tofile ) ) {
		wppa_copy( $fromfile, $tofile );
	}

	// Copy documentstub.png, the default documentstub
	$fromfile = WPPA_PATH . '/img/documentstub.png';
	$tofile = WPPA_UPLOAD_PATH . '/documentstub.png';
	if ( ! wppa_is_file( $tofile ) ) {
		wppa_copy( $fromfile, $tofile );
	}

	// Copy factory supplied icons
	$fromfiles = array( 'Document-File.svg', 'Music-Note-1.svg', 'Film-Clapper.svg', 'Acrobat.jpg', 'Video-icon.png', 'Audio-icon.jpg' );
	foreach ( $fromfiles as $file ) {
		$from 	= WPPA_PATH . '/img/' . $file;
		$to  	= WPPA_UPLOAD_PATH . '/icons/' . $file;
		wppa_copy( $from, $to );
	}

	// Make sure virtual album crypt exist
	$albs = array( '0', '1', '2', '3', '9' );
	foreach( $albs as $alb ) {
		if ( ! wppa_get_option( 'wppa_album_crypt_' . $alb ) ) {
			wppa_update_option( 'wppa_album_crypt_' . $alb, wppa_get_unique_crypt() );
		}
	}

	// Clear email locks
	if ( defined( 'WPPA_LOCKDIR' ) && wppa_is_dir( WPPA_LOCKDIR ) ) {
		$locks = glob( WPPA_LOCKDIR . '/' );
		foreach( $locks as $lock ) {
			if ( wppa_is_file( $lock ) ) {
				wppa_unlink( $lock );
			}
		}
	}

	// Done!
	if ( ! $wppa_error ) {
		$old_rev = round($old_rev); // might be 0.01 off
		if ( $old_rev != $wppa_revno ) { 	// was a real up/down grade,
			wppa_update_option('wppa_prevrev', $old_rev);	// Remember prev rev. For support purposes. They say they stay up to rev, but they come from stoneage...
		}
		wppa_update_option('wppa_revision', $wppa_revno);
	}

	wppa_schedule_cleanup();
}

// Function used during setup when existing settings are changed or removed
function wppa_convert_setting($oldname, $oldvalue, $newname, $newvalue) {
	if ( wppa_get_option($oldname, 'nil') == 'nil' ) return;	// no longer exists
	if ( wppa_get_option($oldname, 'nil') == $oldvalue ) wppa_update_option($newname, $newvalue);
}
function wppa_remove_setting($oldname) {
	if ( wppa_get_option($oldname, 'nil') != 'nil' ) delete_option($oldname);
}
function wppa_rename_setting($oldname, $newname) {
	if ( wppa_get_option($oldname, 'nil') == 'nil' ) return;	// no longer exists
	wppa_update_option($newname, wppa_get_option($oldname));
	delete_option($oldname);
}
function wppa_copy_setting($oldname, $newname) {
	if ( wppa_get_option($oldname, 'nil') == 'nil' ) return;	// no longer exists
	wppa_update_option($newname, wppa_get_option($oldname));
}
function wppa_revalue_setting($oldname, $oldvalue, $newvalue) {
	if ( wppa_get_option($oldname, 'nil') == $oldvalue ) wppa_update_option($oldname, $newvalue);
}

// Check if the required directories exist, if not, try to create them and optionally report it
function wppa_check_dirs() {

	// check if wppa dir exists
	$dir = WPPA_UPLOAD_PATH;
	if ( ! is_dir( $dir ) ) {
		mkdir( $dir );
	}
	chmod( $dir, 0755 );

	$subdirs = array( 'thumbs', 'watermarks', 'fonts', 'icons', 'temp', 'dynamic' );

	// Check all subdirs
	foreach( $subdirs as $subdir ) {
		$dir = WPPA_UPLOAD_PATH . '/' . $subdir;
		if ( ! is_dir( $dir ) ) {
			mkdir( $dir );
		}
		chmod( $dir, 0755 );
	}

	// check if depot dir exists
	if ( ! is_multisite() ) {

		// check if master depot dir exists
		$dir = WPPA_CONTENT_PATH . '/wppa-depot';
		if ( ! is_dir( $dir ) ) {
			mkdir( $dir );
		}
		chmod( $dir, 0755 );
	}

	// check the plugin activators depot directory
	$dir = WPPA_DEPOT_PATH;
	if ( ! is_dir( $dir ) ) {
		mkdir( $dir );
	}
	chmod( $dir, 0755 );

	return true;
}

// Create grated album(s)
// @1: int album id that may be a grant parent, if so, create child for current user if not already exists
function wppa_grant_albums( $xparent = false ) {
global $wpdb;
static $grant_parents;
static $my_albs_parents;
static $owner;
static $user;

	// Feature enabled?
	if ( ! wppa_switch( 'grant_an_album' ) ) {
		return false;
	}

	// User logged in?
	if ( ! is_user_logged_in() ) {
		return false;
	}

	// Restrict?
	if ( wppa_switch( 'grant_restrict' ) && ! current_user_can( 'wppa_admin' ) ) {
		return false;
	}

	// Can user upload? If restricted need no upload.
	if ( ! wppa_switch( 'grant_restrict' ) && ! current_user_can( 'wppa_upload' ) && ! wppa_switch( 'user_upload_on' ) ) {
		return false;
	}

	// Init
	$albums_created = array();

	// Get required data if not done already
	// First get the grant parent album(s)
	if ( ! is_array( $grant_parents ) ) {
		switch( wppa_opt( 'grant_parent_sel_method' ) ) {

			case 'selectionbox':

				// Album ids are and expanded enumeration sep by , in the setting
				$grant_parents = explode( ',', wppa_opt( 'grant_parent' ) );
				if ( empty( $grant_parents ) ) {
					// Selection box method chosen, but no album(s) selected
					return array();
				}
				else {
					foreach( array_keys( $grant_parents ) as $key ) {
						if ( $grant_parents[$key] == 'zero' ) {
							$grant_parents[$key] = '0';
						}
					}
				}
				break;

			case 'category':

				// The option hold a category
				$grant_parents = $wpdb->get_col( 	"SELECT id " .
													"FROM $wpdb->wppa_albums " .
													"WHERE cats LIKE '%," . wppa_opt( 'grant_parent' ) . ",%'"
												);
				if ( empty( $grant_parents ) ) {
					// Selection set to category, but no albums exist with that category
					return array();
				}
				break;

			case 'indexsearch':
				$temp = $wpdb->get_var( "SELECT albums " .
										"FROM $wpdb->wppa_index " .
										"WHERE slug = '" . wppa_opt( 'grant_parent' ) . "'"
										);

				$grant_parents = explode( '.', wppa_expand_enum( $temp ) );
				if ( empty( $grant_parents ) ) {
					// Selection set to indexsearch but no albums found matching the search criteria
					return array();
				}
				break;

			default:
				wppa_log( 'err', 'Unimplemented grant_parent_sel_method: ' . wppa_opt( 'grant_parent_sel_method' ) . ' in wppa_grant_albums()' );
				break;
		}
	}

	// Retrieve the users login name if not done already
	if ( ! $owner ) {
		$owner = wppa_get_user( 'login' );	// The current users login name
	}

	// Get all the parents of the current user albums if not done already
	if ( ! is_array( $my_albs_parents ) ) {
		$query = $wpdb->prepare( "SELECT DISTINCT a_parent FROM $wpdb->wppa_albums WHERE owner = %s", $owner );
		$my_albs_parents = $wpdb->get_col( $query );
		if ( ! is_array( $my_albs_parents ) ) {
			$my_albs_parents = array();
		}
	}

	// Get the current users name as how the album should be named
	if ( ! $user ) {
		$user = wppa_get_user( wppa_opt( 'grant_name' ) );
	}

	// If a parent is given and it is not a grant parent, quit
	if ( $xparent !== false && ! in_array( $xparent, $grant_parents ) ) {
		return false;
	}

	// If a parent is given, it will now be a grant parent (see directly above), only create the granted album inside this parent.
	if ( $xparent !== false ) {
		$parents = array( $xparent );
	}
	// Else create granted albums for all grant parents
	else {
		$parents = $grant_parents;
	}

	// Parent independant album data
	$name = $user;
//	$desc = __( 'Default photo album for', 'wp-photo-album-plus' ) . ' ' . $user;
	$desc = str_replace( '$user', $user, wppa_opt( 'grant_desc' ) );

	// May be multiple granted parents. Check for all parents.
	foreach( $parents as $parent ) {

		// Create only grant album if: parent is either -1 or existing
		if ( $parent == '-1' || $parent == '0' || wppa_album_exists( $parent ) ) {
			if ( ! in_array( $parent, $my_albs_parents, true ) ) {

				// make an album for this user
				$cats = wppa_opt( 'grant_cats' );
				$deftags = wppa_opt( 'grant_tags' );
				$id = wppa_create_album_entry( array ( 'name' => $name, 'description' => $desc, 'a_parent' => $parent, 'cats' => $cats, 'default_tags' => $deftags ) );
				if ( $id ) {

					$albums_created[] = $id;

					// Add this parent to the array of my albums parents
					$my_albs_parents[] = $parent;
				}
				else {
					wppa_log( 'Err', 'Could not create sub album of ' . $parent . ' for ' . $user );
				}
				wppa_invalidate_treecounts( $parent );
				wppa_index_update( 'album', $id );

			}
		}
	}

	// Remake permalink redirects
	if ( ! empty( $albums_created ) ) {
		wppa_create_pl_htaccess();
	}

	return $albums_created;

}