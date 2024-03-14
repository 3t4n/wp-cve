<?php
/* wppa-cron.php
* Package: wp-photo-album-plus
*
* Contains all cron functions
 Version: 8.4.01.005
*
*/

// Are we in a cron job?
function wppa_is_cron() {

	if ( defined( 'DOING_CRON' ) ) {
		return DOING_CRON;
	}
	return false;
}

// Activate our maintenance hook
add_action( 'wppa_cron_event', 'wppa_do_maintenance_proc', 10, 1 );

// Schedule maintenance proc
function wppa_schedule_maintenance_proc( $slug, $from_settings_page = false ) {
global $is_reschedule;

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	// Schedule cron job
	$next_schedule = wp_next_scheduled( 'wppa_cron_event', array( $slug ) );
	if ( ! $next_schedule || $next_schedule > ( time() + 300 ) ) {

		switch ( $slug ) {
			case 'wppa_cleanup_index':
				$delay = 60; 	// 1 minutes
				break;
			case 'wppa_remake_index_albums':
				$delay = 5;
				if ( $from_settings_page ) {
					wppa_clear_col( WPPA_ALBUMS, 'indexdtm' );
					wppa_log( 'idx', 'Marked all albums as need re-index' );
				}
				break;
			case 'wppa_remake_index_photos':
				$delay = 5; // 180;
				if ( $from_settings_page ) {
					wppa_clear_col( WPPA_PHOTOS, 'indexdtm' );
					wppa_log( 'idx', 'Marked all photos as need re-index' );
				}
				break;
			default:
				$delay = 5;
		}
		if ( $is_reschedule || $from_settings_page ) {
			$delay = 5;
		}

		wp_schedule_single_event( time() + $delay, 'wppa_cron_event', array( $slug ) );
		$backtrace = debug_backtrace();
		$args = '';
		if ( is_array( $backtrace[1]['args'] ) ) {
			foreach( $backtrace[1]['args'] as $arg ) {
				if ( $args ) {
					$args .= ', ';
				}
				$args .= str_replace( "\n", '', var_export( $arg, true ) );
			}
			$args = trim( $args );
			if ( $args ) {
				$args = ' ' . str_replace( ',)', ', )', $args ) . ' ';
			}
		}
		elseif ( $backtrace[1]['args'] ) {
			$args = " '" . $backtrace[1]['args'] . "' ";
		}

		$re = $is_reschedule ? 're-' : '';
		wppa_log( 'cron', '{b}' . $slug . '{/b} ' . $re . 'scheduled by {b}' . $backtrace[1]['function'] . '(' . $args . '){/b} on line {b}' . $backtrace[0]['line'] . '{/b} of ' . basename( $backtrace[0]['file'] ) . ' called by ' . $backtrace[2]['function'] . ' to run in ' . $delay . ' seconds' );
	}

	// Update appropriate options
	wppa_update_option( $slug . '_status', 'Cron job' );
	wppa_update_option( $slug . '_user', 'cron-job' );

	// Inform calling Ajax proc about the results
	if ( $from_settings_page ) {
		wppa_echo( '||' . $slug . '||' . 'Cron job' . '||0||' );//reload' );
	}
}

// Is maint proc running?
function wppa_is_maintenance_proc_running( $slug ) {

	// Timedelta
	$timedelta = 10;

	// If there is a last timestamp less than some tme ago...
	$lasttime = wppa_get_option( $slug . '_lasttimestamp', '0' );

	// Than it runs
	return ( $lasttime > ( time() - $timedelta ) );

}

// Is cronjob crashed?
function wppa_is_maintenance_cron_job_crashed( $slug ) {

	// Timedelta
	$timedelta = 10;

	// If there is a last timestamp longer than some time ago...
	$lasttime = wppa_get_option( $slug.'_lasttimestamp', '0' );
	if ( $lasttime && $lasttime < ( time() - $timedelta ) ) {

		// And proc is not scheduled
		if ( ! wp_next_scheduled( 'wppa_cron_event', array( $slug ) ) ) {

			// It is crashed
			return true;
		}
	}

	return false;
}

// Activate our cleanup session hook
add_action( 'wppa_cleanup', 'wppa_do_cleanup' );

// Schedule cleanup session database table
function wppa_schedule_cleanup( $now = false ) {

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	// Immediate action requested?
	if ( $now ) {
		wp_schedule_single_event( time() + 1, 'wppa_cleanup' );
	}
	// Schedule cron job
	if ( ! wp_next_scheduled( 'wppa_cleanup' ) ) {
		wp_schedule_event( time(), 'hourly', 'wppa_cleanup' );
	}
}

// The actual cleaner
function wppa_do_cleanup() {
global $wpdb;
global $wppa_endtime;

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	wppa_log( 'Cron', '{b}wppa_cleanup{/b} started.' );

	// Fix invalid ratings
	$iret = wppa_del_row( WPPA_RATING, 'value', '0' );
	if ( $iret ) {
		wppa_schedule_maintenance_proc('wppa_rerate');
	}

	// Cleanup obsolete settings
	if ( $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->options
						  WHERE option_name LIKE 'wppa_last_album_used-%'" ) > 100 ) {
		$iret = $wpdb->query( "DELETE FROM $wpdb->options
							   WHERE option_name LIKE 'wppa_last_album_used-%'" );
		wppa_log( 'Cron', sprintf( '%s last album used settings removed.', $iret ) );
	}

	// Start renew crypt processes if configured
	if ( wppa_opt( 'crypt_albums_every' ) ) {
		wppa_log( 'Cron', '{b}wppa_cleanup{/b} renew albumcrypt.' );
		$last = wppa_get_option( 'wppa_crypt_albums_lasttimestamp', '0' );
		if ( $last + wppa_opt( 'crypt_albums_every' ) * 3600 < time() ) {
			wppa_schedule_maintenance_proc( 'wppa_crypt_albums' );
			wppa_update_option( 'wppa_crypt_albums_lasttimestamp', time() );
		}
	}

	if ( wppa_opt( 'crypt_photos_every' ) ) {
		wppa_log( 'Cron', '{b}wppa_cleanup{/b} renew photocrypt.' );
		$last = wppa_get_option( 'wppa_crypt_photos_lasttimestamp', '0' );
		if ( $last + wppa_opt( 'crypt_photos_every' ) * 3600 < time() ) {
			wppa_schedule_maintenance_proc( 'wppa_crypt_photos' );
			wppa_update_option( 'wppa_crypt_photos_lasttimestamp', time() );
		}
	}

	// Cleanup session db table
	wppa_log( 'Cron', '{b}wppa_cleanup{/b} cleanup sessions.' );
	$lifetime 	= 3600;			// Sessions expire after one hour
	$savetime 	= 86400;		// Save session data for 24 hour
	$expire 	= time() - $lifetime;
	$purge 		= time() - $savetime;
	$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_session SET status = 'expired' WHERE timestamp < %s", $expire ) );
	$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->wppa_session WHERE timestamp < %s", $purge ) );

	// Delete obsolete spam
	$spammaxage = wppa_opt( 'spam_maxage' );
	if ( $spammaxage != 'none' ) {
		wppa_log( 'Cron', '{b}wppa_cleanup{/b} cleanup spam.' );
		$time = time();
		$obsolete = $time - $spammaxage;
		$iret = $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->wppa_comments WHERE status = 'spam' AND timestamp < %s", $obsolete ) );
		if ( $iret ) wppa_update_option( 'wppa_spam_auto_delcount', wppa_get_option( 'wppa_spam_auto_delcount', '0' ) + $iret );
	}

	// Re-animate crashed cronjobs
	wppa_log( 'Cron', '{b}wppa_cleanup{/b} reanimate cron.' );
	wppa_re_animate_cron();

	// Remove 'deleted' photos from system
	$dels = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_photos WHERE album <= '-9' AND modified < " . ( time() - 3600 ) );
	if ( !empty( $dels ) ) foreach( $dels as $del ) {
		wppa_delete_photo( $del );
		wppa_log( 'Cron', 'Removed photo {b}' . $del . '{/b} from system' );
	}

	// Re-create permalink htaccess file
	wppa_log( 'Cron', '{b}wppa_cleanup{/b} creating pl htaccess.' );
	wppa_create_pl_htaccess();

	// Retry failed mails
	if ( wppa_opt( 'retry_mails' ) ) {

		$failed_mails = wppa_get_option( 'wppa_failed_mails' );
		if ( is_array( $failed_mails ) && count( $failed_mails ) ) {
			wppa_log( 'Cron', '{b}wppa_cleanup{/b} retrying failed mails.' );

			foreach( array_keys( $failed_mails ) as $key ) {

				$mail = $failed_mails[$key];
				$mess = $mail['message'] . '(retried mail)';

				// Retry
				if ( wp_mail( $mail['to'], $mail['subj'], $mess, $mail['headers'] ) ) {

					wppa_log( 'Eml', 'Retried mail to ' . $mail['to'] . ' succeeded.' );

					// Set counter to 0
					$failed_mails[$key]['retry'] = '0';
				}
				else {

					// Decrease retry counter
					$failed_mails[$key]['retry']--;
					wppa_log( 'Eml', 'Retried mail to ' . $mail['to'] . ' failed. Tries to go = ' . $failed_mails[$key]['retry'] );

					// If no tries left, add to permanently failed
					if ( $failed_mails[$key]['retry'] < '1' ) {
						$perm_fail = wppa_get_option( 'wppa_perm_failed_mails', array() );
						$perm_fail[] = $failed_mails[$key];
						wppa_update_option( 'wppa_perm_failed_mails', $perm_fail );
					}
				}
			}

			// Cleanup
			foreach( array_keys( $failed_mails ) as $key ) {
				if ( $failed_mails[$key]['retry'] < '1' ) {
					unset( $failed_mails[$key] );
				}
			}
		}

		// Store updated failed mails
		wppa_update_option( 'wppa_failed_mails', $failed_mails );
	}

	// Add url-sanitized names to new albums
	$albs = $wpdb->get_results( "SELECT id, name FROM $wpdb->wppa_albums WHERE sname = ''", ARRAY_A );
	if ( ! empty( $albs ) ) {
		foreach( $albs as $alb ) {
			wppa_update_album( $alb['id'], ['sname' => wppa_name_slug( $alb['name'] )] );
			wppa_log( 'dbg', 'Set sname from ' . $alb['name'] . ' to ' . wppa_name_slug( $alb['name'] ) . ' for album ' . $alb['id'] );
			if ( wppa_is_time_up() ) {
				wppa_log( 'Cron', 'Reschedule cleanup' );
				wppa_schedule_cleanup( true );
				wppa_exit();
			}
		}
	}

	// Add url-sanitized names to new photos
	$photos = $wpdb->get_results( "SELECT id, name FROM $wpdb->wppa_photos WHERE sname = '' AND name <> '' LIMIT 10000", ARRAY_A );
	if ( ! empty( $photos ) ) {
		foreach( $photos as $photo ) {
			wppa_update_photo( $photo['id'], ['sname' => wppa_sanitize_album_photo_name( $photo['name'] ) ] );
			wppa_log( 'dbg', 'Set sname from ' . $photo['name'] . ' to ' . wppa_sanitize_album_photo_name( $photo['name'] ) . ' for photo ' . $photo['id'] );
			if ( wppa_is_time_up() ) {
				wppa_log( 'Cron', 'Reschedule cleanup' );
				wppa_schedule_cleanup( true );
				wppa_exit();
			}
		}
	}

	// Cleanup tempfiles
	wppa_delete_obsolete_tempfiles();

	// Cleanup unused depot dirs
	$root 	= is_user_logged_in() ? dirname( WPPA_DEPOT_PATH ) : WPPA_DEPOT_PATH;
	$depot 	= dir( $root );
	if ( substr( $root, -10 ) != 'wppa-depot' ) $depot = false; // Just to be sure we are in the right dir
	if ( $depot ) {
		while ( false !== ( $entry = $depot->read() ) && ! wppa_is_time_up() ) {
			if ( $entry != '.' && $entry != '..' && is_dir( $root . '/' . $entry ) ) {
				$user = get_user_by( 'login', $entry );
				if ( ! $user || ! user_can( $user, 'wppa_import' ) ) {
					wppa_rmdir( $root . '/' . $entry );
					wppa_log( 'Fso', 'Removed unused depot dir for' . ( $user ? '': ' non existent' ) . ' user {b}' . $entry . '{/b}' );
				}
			}
		}
	}
	else {
		wppa_log( 'err', 'No depot found ' . $root );
	}

	// Cleanup empty source dirs
	$dirs = wppa_glob( wppa_opt( 'source_dir' ) . '/*', WPPA_ONLYDIRS );
	if ( $dirs ) foreach( $dirs as $dir ) {
		wppa_rmdir( $dir, true ); // when empty
	}

	$a_need_index = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_albums WHERE indexdtm = ''" );
	if ( $a_need_index ) {
		wppa_schedule_maintenance_proc( 'wppa_remake_index_albums' );
	}
	$p_need_index = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE indexdtm = ''" );
	if ( $p_need_index ) {
		wppa_schedule_maintenance_proc( 'wppa_remake_index_photos' );
	}

	wppa_log( 'Cron', '{b}wppa_cleanup{/b} completed.' );

}

// Activate treecount update proc
add_action( 'wppa_update_treecounts', 'wppa_do_update_treecounts' );

function wppa_schedule_treecount_update() {

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	// Schedule cron job
	if ( ! wp_next_scheduled( 'wppa_update_treecounts' ) ) {
		$time = 10;
		wp_schedule_single_event( time() + $time, 'wppa_update_treecounts' );
		wppa_log( 'Cron', '{b}wppa_update_treecounts{/b} scheduled for run in ' . $time . ' sec.' );
	}
}

function wppa_do_update_treecounts() {
global $wpdb;

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	wppa_log( 'Cron', '{b}wppa_update_treecounts{/b} started.' );

	$start = time();

	$albs = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_albums WHERE a_parent < '1' ORDER BY id" );

	foreach( $albs as $alb ) {
		$treecounts = wppa_get_treecounts_a( $alb );
		if ( $treecounts['needupdate'] ) {
			wppa_verify_treecounts_a( $alb );
			wppa_log( 'Cron', 'Cron fixed treecounts for ' . $alb );
		}
		if ( time() > $start + 15 ) {
			wppa_schedule_treecount_update();
			exit();
		}
	}

	wppa_log( 'Cron', '{b}wppa_update_treecounts{/b} completed.' );
}

function wppa_re_animate_cron() {
global $wppa_cron_maintenance_slugs;
global $is_reschedule;

	// Are we temp disbled?
	if ( wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	foreach ( $wppa_cron_maintenance_slugs as $slug ) {
		if ( wppa_is_maintenance_cron_job_crashed( $slug ) ) {
			$last = wppa_get_option( $slug . '_last' );
			wppa_update_option( $slug . '_last', $last + 1 );
			delete_option( $slug . '_user' );

			$is_reschedule = true;
			wppa_schedule_maintenance_proc( $slug );

			if ( in_array( $slug, ['wppa_remake_index_albums', 'wppa_remake_index_photos', 'wppa_cleanup_index'] ) && wppa_switch( 'log_idx' ) ) {
				$logtype = 'idx';
			}
			else {
				$logtype = 'cron';
			}
			wppa_log( $logtype, '{b}' . $slug . '{/b} re-animated at item {b}#' . $last . '{/b}' );
		}
	}
}