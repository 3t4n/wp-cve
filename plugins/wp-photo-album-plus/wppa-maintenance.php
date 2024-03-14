<?php
/* wppa-maintenance.php
* Package: wp-photo-album-plus
*
* Contains (not yet, but in the future maybe) all the maintenance routines
* Version: 8.6.02.001
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// For cron:
require_once 'wppa-admin-functions.php';

global $wppa_all_maintenance_slugs;
$wppa_all_maintenance_slugs = array( 	'wppa_remake_index_albums',
										'wppa_remove_empty_albums',
										'wppa_remake_index_photos',
										'wppa_apply_default_photoname_all',
										'wppa_apply_new_photodesc_all',
										'wppa_append_to_photodesc',
										'wppa_remove_from_photodesc',
										'wppa_remove_file_extensions',
										'wppa_readd_file_extensions',
										'wppa_all_ext_to_lower',
										'wppa_regen_thumbs',
										'wppa_rerate',
										'wppa_recup',
										'wppa_format_exif',
										'wppa_file_system',
										'wppa_cleanup',
										'wppa_remake',
										'wppa_list_index',
										'wppa_blacklist_user',
										'wppa_un_blacklist_user',
										'wppa_rating_clear',
										'wppa_viewcount_clear',
										'wppa_iptc_clear',
										'wppa_exif_clear',
										'wppa_watermark_all',
										'wppa_create_all_autopages',
										'wppa_delete_all_autopages',
										'wppa_leading_zeros',
										'wppa_add_gpx_tag',
										'wppa_add_hd_tag',
										'wppa_optimize_ewww',
										'wppa_comp_sizes',
										'wppa_edit_tag',
										'wppa_sync_cloud',
										'wppa_sanitize_tags',
										'wppa_sanitize_cats',
										'wppa_custom_album_proc',
										'wppa_custom_photo_proc',
										'wppa_crypt_photos',
										'wppa_crypt_albums',
										'wppa_create_o1_files',
										'wppa_owner_to_name_proc',
										'wppa_move_all_photos',
										'wppa_cleanup_index',
										'wppa_photos_hyphens_to_spaces',
										'wppa_png_to_jpg',
										'wppa_fix_mp4_meta',
										'wppa_fix_userids',
										'wppa_fix_custom_tags',
										'wppa_covert_usertags',
										'wppa_clear_vanished_user_photos',
										'wppa_clear_vanished_user_albums',
										'wppa_renew_slugs_albums',
										'wppa_renew_slugs_photos',
									);

global $wppa_cron_maintenance_slugs;
$wppa_cron_maintenance_slugs = array(	'wppa_remake_index_albums',
										'wppa_remake_index_photos',
										'wppa_regen_thumbs',
										'wppa_rerate',
										'wppa_recup',
										'wppa_format_exif',
										'wppa_cleanup_index',
										'wppa_remake',
										'wppa_comp_sizes',
										'wppa_add_gpx_tag',
										'wppa_add_hd_tag',
										'wppa_crypt_photos',
										'wppa_crypt_albums',
										'wppa_photos_hyphens_to_spaces',
										'wppa_fix_userids',
										'wppa_sanitize_tags',
										'wppa_sanitize_cats',
										'wppa_fix_custom_tags',
										'wppa_covert_usertags',
										'wppa_all_ext_to_lower',
										'wppa_custom_album_proc',
										'wppa_custom_photo_proc',
										'wppa_clear_vanished_user_photos',
										'wppa_clear_vanished_user_albums',
										'wppa_renew_slugs_albums',
										'wppa_renew_slugs_photos',
									);

// Main maintenace module
// Must return a string like: errormesssage||$slug||status||togo
function wppa_do_maintenance_proc( $slug ) {
	global $is_reschedule;

	$chunk 	= 0;
	$togo 	= 'many';

	// As long as togo != 0 and no time up, do next chunk
	while ( $togo && ! wppa_is_time_up() ) {
		$chunk++;
		wppa_log( wppa_logtype( $slug ), "Starting {b}$slug{/b} chunk # $chunk" );
		$result = _wppa_do_maintenance_proc( $slug, true );
		$t = explode( '||', $result );
		$togo = isset( $t[3] ) ? $t[3] : '0';
	}

	// Time is up and togo != 0, reschedule
	if ( $togo ) {
		$is_reschedule = true;
		wppa_schedule_maintenance_proc( $slug );
	}
	return $result;
}

function _wppa_do_maintenance_proc( $slug, $its_me = false ) {
global $wpdb;
global $wppa_session;
global $wppa_supported_video_extensions;
global $wppa_supported_audio_extensions;
global $wppa_all_maintenance_slugs;
global $wppa_endtime;
global $is_reschedule;

	$logtype = wppa_logtype( $slug );

	// Are we temp disbled?
	if ( wppa_is_cron() && wppa_switch( 'maint_ignore_cron' ) ) {
		return;
	}

	// If we do clean index by cron and remake index still active, reschedule and give up
	if ( wppa_is_cron() && $slug == 'wppa_cleanup_index' ) {
		if ( wppa_get_option( 'wppa_remake_index_photos_user', false ) || wppa_get_option( 'wppa_remake_index_albums_user', false ) ) {
			wppa_log( $logtype, '{b}' . $slug . '{/b} delayed because a remake index is still active' );
			wppa_schedule_maintenance_proc( $slug );
			wppa_update_option( 'wppa_cleanup_index_status', 'Delayed' );
			return;
		}
	}

	// Lock this proc, or delay when already and i am cron
	if ( wppa_is_cron() && ! $its_me ) {

		// Already running?
		if ( wppa_is_maintenance_proc_running( $slug ) ) {

			// Reschedule
			$is_reschedule = true;
			wppa_schedule_maintenance_proc( $slug );
			wppa_log( $logtype, '{b}' . $slug . '{/b} delayed because this proc is still running' );
			return;
		}
		else {
			wppa_update_option( $slug.'_user', 'cron-job' );
			wppa_update_option( $slug.'_lasttimestamp', time() );
		}
	}
	else {
		wppa_update_option( $slug.'_user', wppa_get_user() );
	}

	// Extend session
	wppa_extend_session();

	// Initialize
	$chunksize 	= '100';
	$lastid 	= strval( intval ( wppa_get_option( $slug . '_last', '0' ) ) );
	$errtxt 	= '';
	$id 		= '0';
	$topid 		= '0';
	$reload 	= '';
	$to_delete_from_cloudinary = array();
	$aborted 	= false;

	if ( ! isset( $wppa_session ) ) $wppa_session = array();
	if ( ! isset( $wppa_session[$slug.'_fixed'] ) )   $wppa_session[$slug.'_fixed'] = '0';
	if ( ! isset( $wppa_session[$slug.'_added'] ) )   $wppa_session[$slug.'_added'] = '0';
	if ( ! isset( $wppa_session[$slug.'_deleted'] ) ) $wppa_session[$slug.'_deleted'] = '0';
	if ( ! isset( $wppa_session[$slug.'_skipped'] ) ) $wppa_session[$slug.'_skipped'] = '0';

	if ( $lastid == '0' ) {
		$wppa_session[$slug.'_fixed'] = '0';
		$wppa_session[$slug.'_deleted'] = '0';
		$wppa_session[$slug.'_skipped'] = '0';
	}

	// Pre-processing needed?
	if ( $lastid == '0' ) {
		wppa_update_option( $slug.'_status', 'Busy' );
		if ( in_array( $slug, ['wppa_remake_index_albums', 'wppa_remake_index_photos', 'wppa_cleanup_index'] ) ) {
			wppa_log( $logtype, '{b}' . $slug . '{/b} started. Allowed runtime: ' . wppa_time_left() . 's.' );
		}
		elseif ( wppa_is_cron() ) {
			wppa_log( $logtype, '{b}' . $slug . '{/b} started. Allowed runtime: ' . wppa_time_left() . 's.' );
		}
		else {
			wppa_log( $logtype, 'Maintenance proc {b}' . $slug . '{/b} started. Allowed runtime: ' . wppa_time_left() . 's.' );
		}
		switch ( $slug ) {

			case 'wppa_remake_index_albums':

				// Pre-Clear album index only if not cron
				if ( ! wppa_is_cron() ) {
					wppa_clear_col( WPPA_INDEX, 'albums' );
					wppa_clear_col( WPPA_ALBUMS, 'indexdtm' );
					wppa_log( 'idx', 'Removed all album data from index and marked all albums as need re-index' );
				}

				// Make sure the void words are up-to-date
				wppa_index_compute_skips();
				break;

			case 'wppa_remake_index_photos':

				// Pre-Clear photo index only if not cron
				if ( ! wppa_is_cron() ) {
					wppa_clear_col( WPPA_INDEX, 'photos' );
					wppa_clear_col( WPPA_PHOTOS, 'indexdtm' );
					wppa_log( 'idx', 'Removed all photo data from index and marked all photos as need re-index' );
				}

				// Make sure the void words are up-to-date
				wppa_index_compute_skips();
				break;

			case 'wppa_cleanup_index':

				// Make sure the void words are up-to-date
				wppa_index_compute_skips();
				break;

			case 'wppa_recup':

				// Pre-Clear exif and iptc tables only if not cron
				if ( ! wppa_is_cron() ) {
					wppa_clear_table( WPPA_IPTC );
					wppa_clear_table( WPPA_EXIF );
				}
				break;
			case 'wppa_file_system':
				if ( wppa_get_option('wppa_file_system') == 'flat' ) wppa_update_option( 'wppa_file_system', 'to-tree' );
				if ( wppa_get_option('wppa_file_system') == 'tree' ) wppa_update_option( 'wppa_file_system', 'to-flat' );
				break;
			case 'wppa_cleanup':
				$orphan_album = wppa_get_option( 'wppa_orphan_album', '0' );
				$album_exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_albums WHERE id = %s", $orphan_album ) );
				if ( ! $album_exists ) $orphan_album = false;
				if ( ! $orphan_album ) {
					$orphan_album = wppa_create_album_entry( ['name' 		=> __('Orphan photos', 'wp-photo-album-plus' ),
															  'a_parent' 	=> '-1',
															  'description' => __( 'This album contains refound lost photos', 'wp-photo-album-plus' ),
															  'owner' 		=> wppa_switch( 'backend_album_public' ) ? '--- public ---' : wppa_get_user()
															] );
					wppa_update_option( 'wppa_orphan_album', $orphan_album );
				}
				break;
			case 'wppa_sync_cloud':
				if ( ! wppa_get_present_at_cloudinary_a() ) {
					// Still Initializing
					$status = 'Initializing';
					if ( ! isset( $wppa_session['fun-count'] ) ) {
						$wppa_session['fun-count'] = 0;
					}
					$wppa_session['fun-count'] = ( $wppa_session['fun-count'] + 1 ) % 3;
					for ( $i=0; $i < $wppa_session['fun-count']; $i++ ) $status .= '.';
					$togo   = 'all';
					$reload = false;
					wppa_echo( '||'.$slug.'||'.$status.'||'.$togo.'||'.$reload );
					wppa_exit();
				}
				break;
			case 'wppa_crypt_albums':
				update_option( 'wppa_album_crypt_0', wppa_get_unique_crypt() );
				update_option( 'wppa_album_crypt_1', wppa_get_unique_crypt() );
				update_option( 'wppa_album_crypt_2', wppa_get_unique_crypt() );
				update_option( 'wppa_album_crypt_3', wppa_get_unique_crypt() );
				update_option( 'wppa_album_crypt_9', wppa_get_unique_crypt() );
				break;
			case 'wppa_owner_to_name_proc':
				if ( ! wppa_switch( 'owner_to_name' ) ) {
					wppa_echo( __( 'Feature must be enabled in', 'wp-photo-album-plus' ) . ' ' . wppa_setting_path( 'a', 'system', 1, 32 ).'||'.$slug.'||||||' );
					wppa_exit();
				}
				break;
			case 'wppa_move_all_photos':
				$fromalb = wppa_get_option( 'wppa_move_all_photos_from' );
				if ( ! wppa_album_exists( $fromalb ) ) {
					wppa_echo( sprintf(__( 'From album %d does not exist', 'wp-photo-album-plus' ), $fromalb ) );
					wppa_exit();
				}
				$toalb = wppa_get_option( 'wppa_move_all_photos_to' );
				if ( ! wppa_album_exists( $toalb ) ) {
					wppa_echo( sprintf(__( 'To album %d does not exist', 'wp-photo-album-plus' ), $toalb ) );
					wppa_exit();
				}
				if ( $fromalb == $toalb ) {
					wppa_echo( __( 'From and To albums are identical', 'wp-photo-album-plus' ) );
					wppa_exit();
				}
				break;

			default:
				break;
		}
	}

	if ( $lastid != '0' ) {
		if ( wppa_is_cron() ) {
			wppa_log( $logtype, '{b}' . $slug . '{/b} continued at item # ' . ( $lastid + 1 ) . '. Allowed runtime: ' . wppa_time_left() . 's.' );
		}
	}

	// Dispatch on albums / photos / single actions
	switch ( $slug ) {

		case 'wppa_remake_index_albums':
		case 'wppa_remove_empty_albums':
		case 'wppa_sanitize_cats':
		case 'wppa_crypt_albums':
		case 'wppa_custom_album_proc':
		case 'wppa_clear_vanished_user_albums':
		case 'wppa_renew_slugs_albums':

			// Process albums
			$table 		= WPPA_ALBUMS;

			if ( $slug == 'wppa_remake_index_albums' ) {
				$topid 		= $wpdb->get_var( "SELECT id FROM $wpdb->wppa_albums ORDER BY id DESC LIMIT 1" );
				$albums 	= $wpdb->get_results( 	"SELECT * FROM $wpdb->wppa_albums " .
													"WHERE id > " . $lastid . " " .
													"AND indexdtm < modified " .
													"ORDER BY id " .
													"LIMIT $chunksize", ARRAY_A );
			}
			else {
				$topid 		= $wpdb->get_var( "SELECT id FROM $wpdb->wppa_albums ORDER BY id DESC LIMIT 1" );
				$albums 	= $wpdb->get_results( "SELECT * FROM $wpdb->wppa_albums WHERE id > ".$lastid." ORDER BY id LIMIT 100", ARRAY_A );
			}
			$togo = wppa_get_count( $table, ['id' => $lastid], ['>'] );

			wppa_cache_album( 'add', $albums );

			if ( $albums ) {

				wppa_maintenance_update_status( $slug, $lastid, $togo );

				foreach ( $albums as $album ) {

					$id = $album['id'];

					switch ( $slug ) {

						case 'wppa_remake_index_albums':
							$wa = wppa_index_add( 'album', $id, 'force' );
							if ( $wa ) {
								wppa_log( $logtype, 'Indexed album {b}' . $id . '{/b} added words: ' . $wa );
							}
							break;

						case 'wppa_remove_empty_albums':
							if ( wppa_is_album_empty( $id ) ) {
								wppa_del_row( WPPA_ALBUMS, 'id', $id );
								wppa_invalidate_treecounts( $id );
								wppa_index_update( 'album', $id );
								wppa_clear_catlist();
								wppa_childlist_remove( $id );
							}
							break;

						case 'wppa_sanitize_cats':
							$cats = $album['cats'];
							if ( $cats ) {
								wppa_update_album( $album['id'], ['cats' => wppa_sanitize_tags( $cats )] );
							}
							break;

						case 'wppa_crypt_albums':
							wppa_update_album( $album['id'], ['crypt' => wppa_get_unique_crypt()] );
							wppa_log( $logtype, 'New album crypt found for album '.$album['id']);
							break;

						case 'wppa_custom_album_proc':
							$file = WPPA_UPLOAD_PATH . '/procs/wppa_custom_album_proc.php';
							include $file;
							break;

						case 'wppa_clear_vanished_user_albums':
							$owner = wppa_get_album_item( $id, 'owner' );

							// Not an ip address?
							if ( strpos( $owner, '.' ) === false && strpos( $owner, ':' ) === false ) {

								// Not public?
								if ( $owner != '--- public ---' ) {

									$user  = wppa_get_user_by( 'login', $owner );
									if ( ! $user || $user->user_login !== $owner ) {

										// User vanished
										wppa_del_row( WPPA_ALBUMS, 'id', $i );
									}
								}
							}
							break;

						case 'wppa_renew_slugs_albums':
							$sname = wppa_name_slug( wppa_get_album_item( $id, 'name' ) );
							wppa_update_album( $id, ['sname' => $sname] );
							break;

						default:
							break;
					}

					// Update where we are
					$lastid = $id;
					$togo--;

					wppa_maintenance_update_status( $slug, $lastid, $togo );

					if ( wppa_is_time_up() ) break; 	// Time out
				}
			}
			else {	// Nothing to do, Done anyway
				$lastid = $topid;
				wppa_log( $logtype, 'Maintenance proc {b}' . $slug . '{/b} Done! 1' );
			}
			break;	// End process albums

		case 'wppa_remake_index_photos':
		case 'wppa_apply_default_photoname_all':
		case 'wppa_apply_new_photodesc_all':
		case 'wppa_append_to_photodesc':
		case 'wppa_remove_from_photodesc':
		case 'wppa_remove_file_extensions':
		case 'wppa_readd_file_extensions':
		case 'wppa_all_ext_to_lower':
		case 'wppa_regen_thumbs':
		case 'wppa_rerate':
		case 'wppa_recup':
		case 'wppa_format_exif':
		case 'wppa_file_system':
		case 'wppa_cleanup':
		case 'wppa_remake':
		case 'wppa_watermark_all':
		case 'wppa_create_all_autopages':
		case 'wppa_delete_all_autopages':
		case 'wppa_leading_zeros':
		case 'wppa_add_gpx_tag':
		case 'wppa_add_hd_tag':
		case 'wppa_optimize_ewww':
		case 'wppa_comp_sizes':
		case 'wppa_edit_tag':
		case 'wppa_sync_cloud':
		case 'wppa_sanitize_tags':
		case 'wppa_crypt_photos':
		case 'wppa_custom_photo_proc':
		case 'wppa_clear_vanished_user_photos':
		case 'wppa_create_o1_files':
		case 'wppa_owner_to_name_proc':
		case 'wppa_move_all_photos':
		case 'wppa_photos_hyphens_to_spaces':
		case 'wppa_png_to_jpg':
		case 'wppa_fix_mp4_meta':
		case 'wppa_fix_custom_tags':
		case 'wppa_fix_userids':
		case 'wppa_covert_usertags':
		case 'wppa_renew_slugs_photos':

			// Process photos
			$table 		= WPPA_PHOTOS;

			if ( $slug == 'wppa_cleanup' ) {
				$topid 		= wppa_get_option( 'wppa_'.WPPA_PHOTOS.'_lastkey', '1' ) * 10;
				$photos 	= array();
				for ( $i = ( $lastid + '1'); $i <= $topid; $i++ ) {
					$photos[]['id'] = $i;
				}
			}
			elseif ( $slug == 'wppa_remake_index_photos' ) {
				$topid 		= $wpdb->get_var( "SELECT id FROM $wpdb->wppa_photos ORDER BY id DESC LIMIT 1" );
				$photos 	= $wpdb->get_results( 	"SELECT * FROM $wpdb->wppa_photos " .
													"WHERE id > " . $lastid . " " .
													"AND indexdtm < modified " .
													"ORDER BY id " .
													"LIMIT $chunksize", ARRAY_A );
			}
			else {
				$topid 		= $wpdb->get_var( "SELECT id FROM $wpdb->wppa_photos ORDER BY id DESC LIMIT 1" );
				$photos 	= $wpdb->get_results( "SELECT * FROM $wpdb->wppa_photos WHERE id > ".$lastid." ORDER BY id LIMIT ".$chunksize, ARRAY_A );
			}
			$togo = wppa_get_count( $table, ['id' => $lastid], ['>'] );

			if ( $slug == 'wppa_edit_tag' ) {
				$edit_tag 	= wppa_get_option( 'wppa_tag_to_edit' );
				$new_tag 	= wppa_get_option( 'wppa_new_tag_value' );
			}

			if ( ! $photos && $slug == 'wppa_file_system' ) {
				$fs = wppa_get_option( 'wppa_file_system' );
				if ( $fs == 'to-tree' ) {
					$to = 'tree';
				}
				elseif ( $fs == 'to-flat' ) {
					$to = 'flat';
				}
				else {
					$to = $fs;
				}
			}

			if ( $photos ) {

				wppa_maintenance_update_status( $slug, $lastid, $togo );

				foreach ( $photos as $photo ) {

					$thumb = $photo;	// Make globally known

					$id = $photo['id'];

					switch ( $slug ) {

						case 'wppa_remake_index_photos':
							$wa = wppa_index_add( 'photo', $id, 'force' );
							if ( $wa ) {
								wppa_log( $logtype, 'Indexed photo {b}' . $id . '{/b} words added: {b}{span style="color:darkred"}' . $wa .  '{/span}{/b}');
							}
							break;

						case 'wppa_apply_default_photoname_all':
							$filename 	= wppa_get_photo_item( $id, 'filename' );
							wppa_set_default_name( $id, $filename );
							break;

						case 'wppa_apply_new_photodesc_all':
							$value = wppa_opt( 'newphoto_description' );
							$description = trim( $value );
							if ( $description != $photo['description'] ) {	// Modified photo description
								wppa_update_photo( $id, ['description' => $description] );
							}
							wppa_set_default_custom( $id ); // Update customfields defaults
							break;

						case 'wppa_append_to_photodesc':
							$value = trim( wppa_opt( 'append_text' ) );
							if ( ! $value ) return 'Unexpected error: missing text to append||'.$slug.'||Error||0';
							$description = rtrim( $photo['description'] . ' '. $value );
							if ( $description != $photo['description'] ) {	// Modified photo description
								wppa_update_photo( $id, ['description' => $description] );
							}
							break;

						case 'wppa_remove_from_photodesc':
							$value = trim( wppa_opt( 'remove_text' ) );
							if ( ! $value ) return 'Unexpected error: missing text to remove||'.$slug.'||Error||0';
							$description = rtrim( str_replace( $value, '', $photo['description'] ) );
							if ( $description != $photo['description'] ) {	// Modified photo description
								wppa_update_photo( $id, ['description' => $description] );
							}
							break;

						case 'wppa_remove_file_extensions':
							$name = wppa_strip_ext( $photo['name'] );
							wppa_update_photo( $id, ['name' => $name] );
							break;

						case 'wppa_readd_file_extensions':
							$name = wppa_strip_ext( $photo['name'] ) . '.' . $photo['ext'];
							wppa_update_photo( $id, ['name' => $name] );
							break;

						case 'wppa_all_ext_to_lower':
							$EXT = wppa_get_photo_item( $id, 'ext' );
							$ext = strtolower( $EXT );
							if ( $EXT != $ext ) {
								wppa_update_photo( $id, ['ext' => $ext] );
								$fixed_this = true;
							}
							$EXT = strtoupper( $ext );
							$rawpath = wppa_strip_ext( wppa_get_photo_path( $id, false ) );
							$rawthumb = wppa_strip_ext( wppa_get_thumb_path( $id, false ) );
							$fixed_this = false;
							if ( ! wppa_is_multi( $id ) ) {
								if ( is_file( $rawpath . '.' . $EXT ) ) {
									if ( is_file( $rawpath . '.' . $ext ) ) {
										wppa_unlink( $rawpath . '.' . $EXT );
									}
									else {
										wppa_rename( $rawpath . '.' . $EXT, $rawpath . '.' . $ext );
									}
									$fixed_this = true;
								}
								if ( is_file( $rawthumb . '.' . $EXT ) ) {
									if ( is_file( $rawthumb . '.' . $ext ) ) {
										wppa_unlink( $rawthumb . '.' . $EXT );
									}
									else {
										wppa_rename( $rawthumb . '.' . $EXT, $rawthumb . '.' . $ext );
									}
									$fixed_this = true;
								}
							}
							if ( $fixed_this ) {
								$wppa_session[$slug.'_fixed']++;
							}
							else {
								$wppa_session[$slug.'_skipped']++;
							}
							break;

						case 'wppa_regen_thumbs':
							if ( ! wppa_is_video( $id ) || file_exists( wppa_get_photo_path( $id ) ) ) {
								wppa_create_thumbnail( $id );
							}
							break;

						case 'wppa_rerate':
							wppa_rate_photo( $id );
							break;

						case 'wppa_recup':
							$a_ret = wppa_recuperate( $id );
							if ( $a_ret['iptcfix'] ) $wppa_session[$slug.'_fixed']++;
							if ( $a_ret['exiffix'] ) $wppa_session[$slug.'_fixed']++;
							break;

						case 'wppa_format_exif':
							wppa_fix_exif_format( $id );
							break;

						case 'wppa_file_system':
							$fs = wppa_get_option('wppa_file_system');
							if ( $fs == 'to-tree' || $fs == 'to-flat' ) {
								if ( $fs == 'to-tree' ) {
									$from = 'flat';
									$to = 'tree';
								}
								else {
									$from = 'tree';
									$to = 'flat';
								}

								// Media files
								if ( wppa_is_multi( $id ) ) {	// Can NOT use wppa_has_audio() or wppa_is_video(), they use wppa_get_photo_path() without fs switch!!
									$exts 		= array_merge( $wppa_supported_video_extensions, $wppa_supported_audio_extensions );
									$pathfrom 	= wppa_get_photo_path( $id, false, $from );
									$pathto 	= wppa_get_photo_path( $id, false, $to );
									foreach ( $exts as $ext ) {
										if ( is_file( str_replace( '.xxx', '.'.$ext, $pathfrom ) ) ) {
											wppa_rename ( str_replace( '.xxx', '.'.$ext, $pathfrom ), str_replace( '.xxx', '.'.$ext, $pathto ) );
										}
									}
								}

								// Poster / photo
								if ( file_exists( wppa_get_photo_path( $id, true, $from ) ) ) {
									wppa_rename ( wppa_get_photo_path( $id, true, $from ), wppa_get_photo_path( $id, true, $to ) );
								}

								// Thumbnail
								if ( file_exists( wppa_get_thumb_path( $id, true, $from ) ) ) {
									wppa_rename ( wppa_get_thumb_path( $id, true, $from ), wppa_get_thumb_path( $id, true, $to ) );
								}

							}
							break;

						case 'wppa_cleanup':
							$photo_files = wppa_glob( WPPA_UPLOAD_PATH.'/'.$id.'.*' );
							// Remove dirs
							if ( $photo_files ) {
								foreach( array_keys( $photo_files ) as $key ) {
									if ( wppa_is_dir( $photo_files[$key] ) ) {
										unset( $photo_files[$key] );
									}
								}
							}
							// files left? process
							if ( $photo_files ) foreach( $photo_files as $photo_file ) {
								$basename 	= basename( $photo_file );
								$ext 		= substr( $basename, strpos( $basename, '.' ) + '1');
								if ( ! wppa_get_count( WPPA_PHOTOS, ['id' => $id] ) ) { // no db entry for this photo
									if ( wppa_is_id_free( WPPA_PHOTOS, $id ) ) {
										if ( wppa_create_photo_entry( array( 'id' => $id, 'album' => $orphan_album, 'ext' => $ext, 'filename' => $basename ) ) ) { 	// Can create entry
											$wppa_session[$slug.'_fixed']++;	// Bump counter
											wppa_log( $logtype, 'Lost photo file '.$photo_file.' recovered' );
										}
										else {
											wppa_log( $logtype, 'Unable to recover lost photo file '.$photo_file.' Create photo entry failed' );
										}
									}
									else {
										wppa_log( $logtype, 'Could not recover lost photo file '.$photo_file.' The id is not free' );
									}
								}
							}
							break;

						case 'wppa_remake':
							$doit = true;
							if ( wppa_switch( 'remake_orientation_only' ) ) {
								$ori = wppa_get_exif_orientation( wppa_get_source_path( $id ) );
								if ( $ori < '2' ) {
									$doit = false;
								}
							}
							if ( wppa_switch( 'remake_missing_only' ) ) {
								if ( is_file( wppa_get_thumb_path( $id ) ) &&
									 is_file( wppa_get_photo_path( $id ) ) ) {
									$doit = false;
								}
							}
							if ( $doit && wppa_remake_files( '', $id ) ) {
								$wppa_session[$slug.'_fixed']++;
							}
							else {
								$wppa_session[$slug.'_skipped']++;
							}
							break;

						case 'wppa_watermark_all':
							if ( ! wppa_is_video( $id ) ) {
								if ( wppa_add_watermark( $id ) ) {
									wppa_create_thumbnail( $id );	// create new thumb
									$wppa_session[$slug.'_fixed']++;
								}
								else {
									$wppa_session[$slug.'_skipped']++;
								}
							}
							else {
								$wppa_session[$slug.'_skipped']++;
							}
							break;

						case 'wppa_create_all_autopages':
							wppa_get_the_auto_page( $id );
							break;

						case 'wppa_delete_all_autopages':
							wppa_remove_the_auto_page( $id );
							break;

						case 'wppa_leading_zeros':
							$name = $photo['name'];
							if ( wppa_is_int( $name ) ) {
								$target_len = wppa_opt( 'zero_numbers' );
								$name = strval( intval( $name ) );
								while ( strlen( $name ) < $target_len ) $name = '0'.$name;
							}
							if ( $name !== $photo['name'] ) {
								wppa_update_photo( $id, ['name' => $name] );
							}
							break;

						case 'wppa_add_gpx_tag':
							$tags 	= wppa_sanitize_tags( $photo['tags'] );
							$temp 	= explode( '/', $photo['location'] );
							if ( ! isset( $temp['2'] ) ) $temp['2'] = false;
							if ( ! isset( $temp['3'] ) ) $temp['3'] = false;
							$lat 	= $temp['2'];
							$lon 	= $temp['3'];
							if ( $lat < 0.01 && $lat > -0.01 &&  $lon < 0.01 && $lon > -0.01 ) {
								$lat = false;
								$lon = false;
							}
							if ( $photo['location'] && $lat && $lon ) {	// Add it
								$tags = wppa_sanitize_tags( $tags . ',GPX' );
								wppa_update_photo( $id, ['tags' => $tags] );
								wppa_index_update( 'photo', $photo['id'] );
								wppa_clear_taglist();
							}
							break;

						case 'wppa_add_hd_tag':
							$tags 	= wppa_sanitize_tags( $photo['tags'] );
							$size 	= wppa_get_artmonkey_size_a( $photo['id'] );
							if ( is_array( $size ) && $size['x'] >= 1920 && $size['y'] >= 1080 ) {
								$tags = wppa_sanitize_tags( $tags . ',HD' );
								wppa_update_photo( $id, ['tags' => $tags] );
								wppa_index_update( 'photo', $photo['id'] );
								wppa_clear_taglist();
							}
							break;

						case 'wppa_optimize_ewww':
							$file = wppa_get_photo_path( $photo['id'] );
							if ( is_file( $file ) ) {
								wppa_optimize_image( $file );
							}
							$file = wppa_get_thumb_path( $photo['id'] );
							if ( is_file( $file ) ) {
								wppa_optimize_image( $file );
							}
							break;

						case 'wppa_comp_sizes':
							$tx = 0; $ty = 0; $px = 0; $py = 0;
							$file = wppa_get_photo_path( $photo['id'] );
							if ( is_file( $file ) ) {
								$temp = getimagesize( $file );
								if ( is_array( $temp ) ) {
									$px = $temp[0];
									$py = $temp[1];
								}
							}
							$file = wppa_get_thumb_path( $photo['id'] );
							if ( is_file( $file ) ) {
								$temp = getimagesize( $file );
								if ( is_array( $temp ) ) {
									$tx = $temp[0];
									$ty = $temp[1];
								}
							}
							wppa_update_photo( $id, ['thumbx' => $tx, 'thumby' => $ty, 'photox' => $px, 'photoy' => $py] );
							break;

						case 'wppa_edit_tag':
							$phototags = explode( ',', wppa_get_photo_item( $photo['id'], 'tags' ) );
							if ( in_array( $edit_tag, $phototags ) ) {
								foreach( array_keys( $phototags ) as $key ) {
									if ( $phototags[$key] == $edit_tag ) {
										$phototags[$key] = $new_tag;
									}
								}
								wppa_update_photo( $id, ['tags' => implode( ',', $phototags )] );
								$wppa_session[$slug.'_fixed']++;
							}
							else {
								$wppa_session[$slug.'_skipped']++;
							}
							break;

						case 'wppa_sync_cloud':
							$is_old 	 = ( wppa_opt( 'max_cloud_life' ) ) && ( time() > ( $photo['timestamp'] + wppa_opt( 'max_cloud_life' ) ) );
							$is_in_cloud = isset( $wppa_session['cloudinary_ids'][$photo['id']] );

							if ( $is_old && $is_in_cloud ) {
								$to_delete_from_cloudinary[] = strval( $photo['id'] );
								if ( count( $to_delete_from_cloudinary ) == 10 ) {
									wppa_delete_from_cloudinary( $to_delete_from_cloudinary );
									$to_delete_from_cloudinary = array();
								}
								$wppa_session[$slug.'_deleted']++;
							}
							if ( ! $is_old && ! $is_in_cloud ) {
								wppa_upload_to_cloudinary( $photo['id'] );
								$wppa_session[$slug.'_added']++;
							}
							if ( $is_old && ! $is_in_cloud ) {
								$wppa_session[$slug.'_skipped']++;
							}
							if ( ! $is_old && $is_in_cloud ) {
								$wppa_session[$slug.'_skipped']++;
							}
							break;

						case 'wppa_sanitize_tags':

							// If raw data exists, update with sanitized data
							if ( $photo['tags'] ) {
								wppa_update_photo( $id, ['tags' => $photo['tags']] );
							}
							break;

						case 'wppa_crypt_photos':
							wppa_update_photo( $id, ['crypt' => wppa_get_unique_crypt()] );
							break;

						case 'wppa_create_o1_files':
							wppa_make_o1_source( $id );
							break;

						case 'wppa_owner_to_name_proc':
							$iret = wppa_set_owner_to_name( $id );
							if ( $iret === true ) {
								$wppa_session[$slug.'_fixed']++;
							}
							if ( $iret === '0' ) {
								$wppa_session[$slug.'_skipped']++;
							}
							break;

						case 'wppa_move_all_photos':
							$fromalb = wppa_get_option( 'wppa_move_all_photos_from' );
							$toalb = wppa_get_option( 'wppa_move_all_photos_to' );
							$alb = wppa_get_photo_item( $id, 'album' );
							if ( $alb == $fromalb ) {
								wppa_update_photo( $id, ['album' => $toalb] );
								wppa_move_source( wppa_get_photo_item( $id, 'filename' ), $fromalb, $toalb );
								wppa_invalidate_treecounts( $fromalb );
								wppa_invalidate_treecounts( $toalb );
								$wppa_session[$slug.'_fixed']++;
							}
							break;

						case 'wppa_photos_hyphens_to_spaces':
							$name = wppa_get_photo_item( $id, 'name' );
							$newname = str_replace( '-', ' ', $name );
							if ( $name != $newname ) {
								wppa_update_photo( $id, ['name' => $newname] );
							}
							break;

						case 'wppa_png_to_jpg':
							wppa_convert_png_to_jpg( $id );
							break;

						case 'wppa_fix_mp4_meta':
							wppa_fix_video_metadata( $id, 'maintproc' );
							wppa_fix_audio_metadata( $id, 'maintproc' );
							break;

						case 'wppa_fix_custom_tags':
							wppa_set_default_tags( $id );
							wppa_set_default_custom( $id, true );
							break;

						case 'wppa_fix_userids':
							$ratings = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_rating
																			WHERE photo = %d", $id ), ARRAY_A );
							foreach ( $ratings as $rating ) {
								$username 	= $rating['user'];
								$userid 	= $wpdb->get_var( $wpdb->prepare(  "SELECT ID
																				FROM $wpdb->users
																				WHERE user_login = %s", $username ) );	// try login name
								if ( ! $userid ) {
									$usrs = $wpdb->get_col( $wpdb->prepare(    "SELECT ID
																				FROM $wpdb->users
																				WHERE display_name = %s", $username ) ); // try display name
									if ( count( $usrs ) == 1 ) {
										$userid = $usrs[0];
									}
								}
								if ( ! $userid ) {
									$userid = -1; 		// logged out
								}
								// Update
								$rid = $rating['id'];
								wppa_update_rating( $rid, ['userid' => $userid] );
							}

							$comments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_comments WHERE photo = %s", $id ), ARRAY_A );
							foreach ( $comments as $comment ) {
								$username 	= $comment['user'];
								$useremail 	= $comment['email'];
								$userid 	= $wpdb->get_var( $wpdb->prepare(  "SELECT ID
																				FROM $wpdb->users
																				WHERE user_login = %s", $username ) );	// try login name
								if ( ! $userid ) {
									$usrs = $wpdb->get_col( $wpdb->prepare(    "SELECT ID
																				FROM $wpdb->users
																				WHERE user_email = %s", $useremail ) ); // try email address
									if ( count( $usrs ) == 1 ) {
										$userid = $usrs[0];
									}
								}
								if ( ! $userid ) {
									$usrs = $wpdb->get_col( $wpdb->prepare(    "SELECT ID
																				FROM $wpdb->users
																				WHERE display_name = %s", $username ) ); // try display name
									if ( count( $usrs ) == 1 ) {
										$userid = $usrs[0];
									}
								}
								if ( ! $userid ) {
									$userid = -1; 		// logged out
								}
								// Update
								$cid = $comment['id'];
								wppa_update_comment( $cid, ['userid' => $userid] );
							}
							break;

						case 'wppa_covert_usertags':
							$tags = wppa_get_photo_item( $id, 'tags' );
							if ( stripos( $tags, ',user-' ) !== false ) {
								$tag_arr = explode( ',', $tags );
								foreach( array_keys( $tag_arr ) as $tag_key ) {
									$tag = $tag_arr[$tag_key];
									if ( stripos( $tag, 'user-' ) !== false ) {
										$uid = substr( $tag, 5 );
										if ( wppa_is_int( $uid ) ) {
											$user = wppa_get_user_by( 'id', $uid ) -> display_name;
											if ( $user ) {
												$tag_arr[$tag_key] = $user;
												$tags = wppa_sanitize_tags( implode( ',', $tag_arr ) );
												wppa_update_photo( $id, ['tags' => $tags] );
											}
										}
									}
								}
							}
							break;

						case 'wppa_custom_photo_proc':
							$file = WPPA_UPLOAD_PATH . '/procs/wppa_custom_photo_proc.php';
							include $file;
							break;

						case 'wppa_clear_vanished_user_photos':
							$owner = wppa_get_photo_item( $id, 'owner' );

							// Not an ip address?
							if ( strpos( $owner, '.' ) === false && strpos( $owner, ':' ) === false ) {
								$user  = wppa_get_user_by( 'login', $owner );
								if ( ! $user || $user->user_login !== $owner ) {

									// User vanished
									wppa_delete_photo( $id );
								}
							}
							break;

						case 'wppa_renew_slugs_photos':
							$sname = wppa_name_slug( wppa_get_photo_item( $id, 'name' ) );
							wppa_update_photo( $id, ['sname' => $sname] );
							break;

						default:
							break;
					}

					// Update where we are
					$lastid = $id;
					$togo--;

					wppa_maintenance_update_status( $slug, $lastid, $togo );

					if ( wppa_is_time_up() ) break; 	// Time out
				}
			}
			else {	// Nothing to do, Done anyway
				$lastid = $topid;
				wppa_log( $logtype, 'Maintenance proc {b}' . $slug . '{/b} Done! 2' );
			}
			break;	// End process photos

		case 'wppa_cleanup_index':
		case 'wppa_something_else_for_index':

			// Process index
			$table 		= WPPA_INDEX;

			$topid 		= $wpdb->get_var( "SELECT id FROM $wpdb->wppa_index ORDER BY id DESC LIMIT 1" );
			$indexes 	= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_index
															   WHERE id > %d
															   ORDER BY id LIMIT %d", $lastid, $chunksize ), ARRAY_A );

			$togo 		= wppa_get_count( $table, ['id' => $lastid], ['>'] );
			$didsome 	= false;

			if ( $indexes ) foreach ( array_keys( $indexes ) as $idx ) {

				switch ( $slug ) {

					case 'wppa_cleanup_index':

						// The albums
						$aborted 		= false;
						$index   		= $indexes[$idx];
						$current_word 	= $index['slug'];
						$albums 		= wppa_index_string_to_array( $indexes[$idx]['albums'] );

						wppa_log( 'idx', 'Start cleanup index # ' . $indexes[$idx]['id'] . ' word {b}{span style="color:darkred"}' . $indexes[$idx]['slug'] . '{/b}' );

						if ( is_array( $albums ) ) foreach( array_keys( $albums ) as $aidx ) {

							if ( wppa_is_time_up() || wppa_is_memory_up() ) {
								$aborted = true;
							}

							if ( ! $aborted ) {

								$alb 	= $albums[$aidx];

								// If album gone, remove it from index
								if ( ! wppa_album_exists( $alb ) ) {
									unset( $albums[$aidx] );
									wppa_log( $logtype, '{b}wppa_cleanup_index{/b} Removed ' . $alb . ' from album index word {b}{span style="color:darkred"}' . $current_word . '{/span}{/b} because album vanished' );
									$didsome = true;
								}

								// Check if keyword appears in album data
								else {
									$words 	= wppa_index_raw_to_words( wppa_index_get_raw_album( $alb ) );
									if ( ! in_array( $indexes[$idx]['slug'], $words ) ) {
										unset( $albums[$aidx] );
										wppa_log( $logtype, '{b}wppa_cleanup_index{/b} Removed ' . $alb . ' from album index word {b}{span style="color:darkred"}' . $current_word . '{/span}{/b} because word no longer in album' );
										$didsome = true;
									}
									wppa_cache_album( 'invalidate', $alb );	// Prevent cache overflow
								}
							}
							else break;
						}

						// The photos
						$photos = wppa_index_string_to_array( $indexes[$idx]['photos'] );
						$cp 	= is_array( $photos ) ? count( $photos ) : 0;
						$pidx 	= 0;
						$last 	= wppa_get_option( $slug.'_last_photo', 0 );

						if ( ! $aborted && is_array( $photos ) ) foreach( array_keys( $photos ) as $pidx ) {

							if ( wppa_is_time_up() || wppa_is_memory_up() ) {
								$aborted = true;
							}

							if ( ! $aborted ) {

								if ( $pidx < $last ) continue;	// Skip already done

								if ( $last && $pidx == $last ) {
									wppa_log( $logtype, 'Continuing cleanup index at slug = {b}' . $indexes[$idx]['slug'] . '{/b}, element # = {b}' . $last . '{/b}' );
								}

								$pho 	= $photos[$pidx];

								// If photo gone, remove it from index
								if ( ! wppa_photo_exists( $pho ) ) {
									unset( $photos[$pidx] );
									wppa_log( $logtype, '{b}wppa_cleanup_index{/b} Removed ' . $pho . ' from photo index word {b}{span style="color:darkred"}' . $current_word . '{/span}{/b} because photo vanished' );
									$didsome = true;
								}

								// Check if keyword appears in photo data
								else {
									$words = wppa_index_raw_to_words( wppa_index_get_raw_photo( $pho ) );
									if ( ! in_array( $indexes[$idx]['slug'], $words ) ) {
										unset( $photos[$pidx] );
										wppa_log( $logtype, '{b}wppa_cleanup_index{/b} Removed ' . $pho . ' from photo index word {b}{span style="color:darkred"}' . $current_word . '{/span}{/b} because word no longer in photo' );
										$didsome = true;
									}
									wppa_cache_photo( 'invalidate' );	// Prevent cache overflow
								}
							}
							else break;
						}
						if ( $cp && $pidx != ( $cp - 1 ) ) {
							wppa_log( $logtype, 	'Could not complete scan of index item # {b}' . $indexes[$idx]['id'] . '{/b},' .
												' slug = {b}' . $indexes[$idx]['slug'] . '{/b},' .
												' count = {b}' . $cp . '{/b},' .
												' photo id = {b}' . $photos[$pidx] .'{/b},' .
												' next element # = {b}' . $pidx . '{/b},'
									);
							$aborted = true;
						}

						$lastid = $indexes[$idx]['id'];
						if ( $aborted ) {
							$lastid--;
							wppa_update_option( $slug.'_last_photo', $pidx );
						}
						wppa_update_option( $slug.'_last', $lastid );
						$albums = wppa_index_array_to_string( $albums );
						$photos = wppa_index_array_to_string( $photos );
						if ( $didsome ) {
							wppa_update_index( $indexes[$idx]['id'], ['albums' => $albums, 'photos' => $photos] );
							wppa_log( $logtype, '{b}wppa_cleanup_index{/b} Updated index word {b}{span style="color:darkred"}' . $indexes[$idx]['slug'] . '{/span}{/b} with albums = ' . $albums . ' and photos = ' . $photos );
						}
						break;

					case 'wppa_something_else_for_index':
						// Just example to make extensions easy
						// So you know here to out the code
						break;

					default:
						break;
				}

				// Update where we are
				if ( ! $aborted ) $togo--;

				wppa_maintenance_update_status( $slug, $lastid, $togo );

				if ( wppa_is_time_up() ) break; 	// Time out

				if ( $aborted ) break;
			}

			break; 	// End process index

		default:
			$errtxt = 'Unimplemented maintenance slug: '.strip_tags( $slug );
	}

	// either $albums / $photos / $indexes has been exhousted ( for this try ) or time is up
	if ( wppa_is_time_up() ) {
		wppa_log( $logtype, 'Time up running {b}' . $slug . '{/b}' );
	}
	elseif ( wppa_is_memory_up() ) {
		wppa_log( $logtype, 'Memory up running {b}' . $slug . '{/b}' );
	}
	else {
		wppa_log( $logtype, 'Chunk done running {b}' . $slug . '{/b}' );
	}

	// Post proc this try:
	switch ( $slug ) {

		case 'wppa_sync_cloud':
			if ( count( $to_delete_from_cloudinary ) > 0 ) {
				wppa_delete_from_cloudinary( $to_delete_from_cloudinary );
			}
			break;

		default: 		// Nothing to postprocess
			break;
	}

	// Register lastid
	wppa_update_option( $slug.'_last', $lastid );

	// Find togo
	if ( $slug == 'wppa_cleanup' ) {
		$togo 	= $topid - $lastid;
	}

	// Find status
	if ( ! $errtxt ) {
		$status = $togo ? 'Working' : 'Ready';
	}
	else $status = 'Error';

	// Not done yet?
	if ( $togo > 0 ) {

		wppa_log( $logtype, 'Togo = ' . $togo );

		// If a cron job, reschedule next chunk
		if ( wppa_is_cron() ) {

			wppa_update_option( $slug.'_togo', $togo );
			wppa_update_option( $slug.'_status', 'Cron job' );
		}
		else {
			wppa_update_option( $slug.'_togo', $togo );
			wppa_update_option( $slug.'_status', 'Pending' );
		}
	}

	// Really done
	else {

		wppa_log( $logtype, '{b}'.$slug.'{/b} Job completed' );

		// Report fixed/skipped/deleted
		if ( $wppa_session[$slug.'_fixed'] ) {
			$status .= ' fixed:'.$wppa_session[$slug.'_fixed'];
			unset ( $wppa_session[$slug.'_fixed'] );
		}
		if ( $wppa_session[$slug.'_added'] ) {
			$status .= ' added:'.$wppa_session[$slug.'_added'];
			unset ( $wppa_session[$slug.'_added'] );
		}
		if ( $wppa_session[$slug.'_deleted'] ) {
			$status .= ' deleted:'.$wppa_session[$slug.'_deleted'];
			unset ( $wppa_session[$slug.'_deleted'] );
		}
		if ( $wppa_session[$slug.'_skipped'] ) {
			$status .= ' skipped:'.$wppa_session[$slug.'_skipped'];
			unset ( $wppa_session[$slug.'_skipped'] );
		}

		// Post-processing needed?
		switch ( $slug ) {
			case 'wppa_remake_index_albums':

				// If not done, reschedule
				$na = wppa_get_count( WPPA_ALBUMS, ['indexdtm' => ''] );

				if ( $na ) {
					wppa_log( $logtype, 'Found '.$na.' new album items to re-index' );
					wppa_schedule_maintenance_proc( 'wppa_remake_index_albums' );
				}

				// Schedule cleanup
				else {
					wppa_schedule_maintenance_proc( 'wppa_cleanup_index' );
				}
				break;
			case 'wppa_remake_index_photos':

				// If not done, reschedule
				$np = wppa_get_count( WPPA_PHOTOS, ['indexdtm' => ''] );
				if ( $np ) {
					wppa_log( $logtype, 'Found '.$np.' new photo items to re-index' );
					wppa_schedule_maintenance_proc( 'wppa_remake_index_photos' );
				}

				// Schedule cleanup
				else {
					wppa_schedule_maintenance_proc( 'wppa_cleanup_index' );
				}
				break;
			case 'wppa_cleanup_index':
				$items_to_delete = $wpdb->get_col( "SELECT slug FROM $wpdb->wppa_index WHERE albums = '' AND photos = ''" );
				$wpdb->query( "DELETE FROM $wpdb->wppa_index WHERE albums = '' AND photos = ''" );	// Remove empty entries
				wppa_log( $logtype, 'Words deleted from index: ' . implode( ',', $items_to_delete ) );
				delete_option( 'wppa_index_need_remake' );
				break;
			case 'wppa_apply_default_photoname_all':
			case 'wppa_apply_new_photodesc_all':
			case 'wppa_append_to_photodesc':
			case 'wppa_remove_from_photodesc':
				wppa_schedule_maintenance_proc( 'wppa_remake_index_photos' );
				break;
			case 'wppa_regen_thumbs':
				wppa_bump_thumb_rev();
				break;
			case 'wppa_file_system':
				wppa_update_option( 'wppa_file_system', $to );
				$reload = 'reload';
				break;
			case 'wppa_remake':
				wppa_bump_photo_rev();
				wppa_bump_thumb_rev();
				break;
			case 'wppa_edit_tag':
			case 'wppa_covert_usertags':
			case 'wppa_sanitize_tags':
			case 'wppa_sanitize_cats':
				wppa_clear_taglist();
				wppa_clear_catlist();
				wppa_schedule_maintenance_proc( 'wppa_remake_index_photos' );
				break;
			case 'wppa_sync_cloud':
				unset( $wppa_session['cloudinary_ids'] );
				break;
			case 'wppa_clear_vanished_user_photos':
				wppa_schedule_maintenance_proc( 'wppa_clear_vanished_user_albums' );
				break;
			default:
				break;
		}

		if ( wppa_is_cron() ) {
			wppa_log( $logtype, '{b}' . $slug . '{/b} completed' );
		}
		else {
			wppa_log( $logtype, 'Maintenance proc {b}' . $slug . '{/b} completed' );
		}

		// Clear cache after a maintenance proc ended
		if ( in_array( $slug, array( 'wppa_crypt_photos', 'wppa_crypt_albums' ) ) ) {
			wppa_clear_cache( array( 'force' => true ) );
		}

		wppa_maintenance_update_status( $slug, $lastid, $togo );
	}

	return $errtxt.'||'.$slug.'||'.get_option($slug . '_status','').'||'.$togo.'||'.$reload;
}

// Stutus update
function wppa_maintenance_update_status( $slug, $lastid, $togo ) {

	if ( $togo ) {
		wppa_update_option( $slug.'_last', $lastid );
		wppa_update_option( $slug.'_lasttimestamp', time() );
		wppa_update_option( $slug.'_togo', $togo );
		if ( wppa_is_cron() ) {
			wppa_update_option( $slug.'_status', 'Cron job' );
		}
		else {
			wppa_update_option( $slug.'_status', 'Working' );
		}
	}

	else if ( $slug != 'wppa_custom_photo_proc' || ! wppa_switch( 'custom_photo_proc_keep_last' ) ) {
		delete_option( $slug . '_togo' );
		update_option( $slug . '_status', __( 'Ready', 'wp-photo-album-plus' ) );
		delete_option( $slug . '_last' );
		delete_option( $slug . '_user' );
		delete_option( $slug . '_lasttimestamp' );
	}
}

function wppa_do_maintenance_popup( $slug ) {
global $wpdb;
global $wppa_log_file;

	// Init
	$header = '';

	// Open wrapper with dedicated styles
	$result =
	'<div' .
		' id="wppa-maintenance-list"' .
		( strpos( $_SERVER['REQUEST_URI'], 'page=wppa_log' ) !== false || wppa_get( 'raw' ) ? '' : ' style="max-height:500px; overflow:hidden;width:100%;"' ) .
		' >';

	// Open nicescroller wrapper
	$result .= '<div class="wppa-nicewrap" >';

	switch ( $slug ) {

		// List the search index table
		case 'wppa_list_index':
			$start = wppa_get_option( 'wppa_list_index_display_start', '' );
			$total = wppa_get_count( WPPA_INDEX );
			$indexes = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_index
															WHERE slug >= %s ORDER BY slug LIMIT 1000", $start ), ARRAY_A );

			$header = sprintf( __( 'List of Searcheable words <small>( Max 1000 entries of total %d )</small>', 'wp-photo-album-plus' ), $total );

			$result .= '
			<div
				style="float:left;clear:both;width:100%;overflow:auto;margin-left:-1px;"
				>';

			if ( $indexes ) {
				$result .= '
				<table>
					<thead>
						<tr>
							<th><span style="float:left">Id</span></th>
							<th><span style="float:left">Word</span></th>
							<th style="max-width:400px"><span style="float:left">Albums</span></th>
							<th><span style="float:left">Photos</span></th>
						</tr>
						<tr><td colspan="3"><hr /></td></tr>
					</thead>
					<tbody>';

				foreach ( $indexes as $index ) {
					$result .= '
						<tr>
							<td>' . htmlspecialchars( $index['id'] ) . '</td>
							<td>' . htmlspecialchars( $index['slug'] ) . '</td>
							<td style="max-width:400px; word-wrap: break-word">' . htmlspecialchars( $index['albums'] ) . '</td>
							<td>' . htmlspecialchars( $index['photos'] ) . '</td>
						</tr>';
				}

				$result .= '
					</tbody>
				</table>';
			}
			else {
				$result .= __('There are no index items.', 'wp-photo-album-plus' );
			}
			$result .= '
				</div><div style="clear:both;"></div>';

			break;

		case 'wppa_list_errorlog':
			if ( wppa( 'ajax' ) && ! wppa_get( 'raw' ) ) {
				$header = __( 'List of WPPA+ log messages', 'wp-photo-album-plus' );
			}
			else {
				$header = '';
			}

			$result .= '
			<div
				style="float:left;clear:both;width:100%;overflow:auto;margin-left:-1px;"
				>';

			$rec = wppa_get_option( 'wppa_recursive_log', '' );
			if ( $rec ) {
				$result .= __( 'Recursive log detected', 'wp-photo-album-plus' ) . ': ' .
				$rec . '<br><br>';
				delete_option( 'wppa_recursive_log' );
			}

			$pre = wppa_get_option( 'wppa_last_error', '' );
			if ( $pre ) {
				$result .= __( 'Last pre-initialisation error', '' ) . ':  ' .
				str_replace( array( '{', '}' ), array( '<', '>' ), $pre ) .
				'<br><br>';
				delete_option( 'wppa_last_error' );
			}

			if ( ! wppa_is_file( $wppa_log_file ) ) {
				$result .= __( 'There are no log messages', 'wp-photo-album-plus' );
			}
			else {
				$data 	= wppa_get_contents_array( $wppa_log_file );
				$data 	= implode( '', array_reverse( $data ) );
				$data 	= str_replace( array( '{b}', '{/b}', '{i}', '{/i}' ), array( '<b>', '</b>', '<i>', '</i>' ), $data );
				$data 	= str_replace( array( '{/span}', '{span' ), array( '</span>', '<span' ), $data );
				$data 	= str_replace( array( "\n", '"}', '" }', '{}' ), array( '<br>', '">', ' ">', '<>' ), $data );
				$data 	= str_replace( '\\', '', $data );

				$result .= $data;
			}

			$result .= '
				</div><div style="clear:both;"></div>
				';
			break;

		case 'wppa_list_rating':
			$total = wppa_get_count( WPPA_RATING );
			$ratings = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_rating ORDER BY timestamp DESC LIMIT 1000", ARRAY_A );

			$header = sprintf( __( 'List of recent ratings <small>( Max 1000 entries of total %d )</small>', 'wp-photo-album-plus' ), $total );

			$result .= '
			<div
				style="float:left;clear:both;width:100%;overflow:auto;margin-left:-1px;"
				>';

			if ( $ratings ) {
				$result .= '
				<table>
					<thead>
						<tr>
							<th>Id</th>
							<th>Timestamp</th>
							<th>Date/time</th>
							<th>Status</th>
							<th>User</th>
							<th>UserId</th>
							<th>Value</th>
							<th>Photo id</th>
							<th></th>
							<th># ratings</th>
							<th>Average</th>
						</tr>
						<tr><td colspan="10"><hr /></td></tr>
					</thead>
					<tbody>';

				foreach ( $ratings as $rating ) {
					$thumb = wppa_cache_photo( $rating['photo'] );
					$result .= '
						<tr>
							<td>' . htmlspecialchars( $rating['id'] ) . '</td>
							<td>' . htmlspecialchars( $rating['timestamp'] ) . '</td>
							<td>' . htmlspecialchars( ( $rating['timestamp'] ? wppa_local_date( '', $rating['timestamp'] ) : 'pre-historic' ) ) . '</td>
							<td>' . htmlspecialchars( $rating['status'] ) . '</td>
							<td>' . htmlspecialchars( $rating['user'] ) . '</td>
							<td>' . htmlspecialchars( $rating['userid'] ) . '</td>
							<td>' . htmlspecialchars( $rating['value'] ) . '</td>
							<td>' . htmlspecialchars( $rating['photo'] ) . '</td>
							<td style="width:250px; text-align:center;"><img src="' . esc_url( wppa_get_thumb_url( $rating['photo'] ) ) . '"
								style="height: 40px;"
								onmouseover="jQuery(this).stop().animate({height:this.naturalHeight}, 200);"
								onmouseout="jQuery(this).stop().animate({height:\'40px\'}, 200);" /></td>
							<td>' . htmlspecialchars( $thumb['rating_count'] ) . '</td>
							<td>' . htmlspecialchars( $thumb['mean_rating'] ) . '</td>
						</tr>';
				}

				$result .= '
					</tbody>
				</table>';
			}
			else {
				$result .= __( 'There are no ratings', 'wp-photo-album-plus' );
			}
			$result .= '
				</div><div style="clear:both;"></div>';
			break;

		case 'wppa_list_session':
			$total = wppa_get_count( WPPA_SESSION );
			$sessions = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_session ORDER BY id DESC LIMIT 1000", ARRAY_A );

			$header = sprintf( __( 'List of sessions <small>( Max 1000 entries of total %d )</small>', 'wp-photo-album-plus' ), $total );

			$result .= '
			<div
				style="float:left;clear:both;width:100%;overflow:auto;margin-left:-1px;"
				>';

			if ( $sessions ) {
				$result .= '
				<table>
					<thead>
						<tr>
							<th>Id</th>
							<th>Session id</th>
							<th>IP</th>
							<th>Started</th>
							<th>Count</th>
							<th>Status</th>
							<th>Data</th>
							<th>Uris</th>
						</tr>
						<tr><td colspan="7"><hr /></td></tr>
					</thead>
					<tbody style="overflow:auto">';
					foreach ( $sessions as $session ) {
						$data = wppa_unserialize( $session['data'] );
						$result .= '
							<tr>
								<td>'.$session['id'].'</td>
								<td>'.$session['session'].'</td>
								<td>' . htmlspecialchars( strlen( $session['ip'] ) > 15 ? substr( $session['ip'], 0, 12 ) . '...' : $session['ip'] ) . '</td>
								<td style="width:150px">'.wppa_local_date(wppa_get_option('date_format', "F j, Y,").' '.wppa_get_option('time_format', "g:i a"), $session['timestamp']).'</td>
								<td>' . htmlspecialchars( $session['count'] ) . '</td>
								<td>' . htmlspecialchars( $session['status'] ) . '</td>
								<td style="border-bottom:1px solid gray">';
									if ( is_array( $data ) ) foreach ( array_keys( $data ) as $key ) {
										if ( $key != 'uris' ) {
											if ( is_array( $data[$key] ) ) {
												$result .= '['.$key.'] => Array(<br>';
												foreach( array_keys($data[$key]) as $k ) {
													$result .= '&nbsp;' . $k . ' = ' . $data[$key][$k] . '<br>';
												}
												$result .=
												')<br>';
											}
											elseif ( is_object( $data[$key] ) ) {
												$temp = var_export( $data[$key], true );
												$result .= '['.$key.'] => ' . $temp;
											}
											else {
												$result .= '['.$key.'] => '.$data[$key].'<br>';
											}
										}
									}

						$result .= '
								</td>
								<td style="border-bottom:1px solid gray">';
								if ( $data['uris'] ) {
									if ( is_array( $data['uris'] ) ) {
										foreach ( $data['uris'] as $uri ) {
											$result .= $uri.'<br>';
										}
									}
								}
						$result .= '
								</td>
							</tr>';
					}
				$result .= '
					</tbody>
				</table>';
			}
			else {
				$result .= __( 'There are no active sessions', 'wp-photo-album-plus' );
			}
			$result .= '
				</div><div style="clear:both;"></div>';

			break;

		case 'wppa_list_comments':
			$total = wppa_get_count( WPPA_COMMENTS );
			$order = wppa_opt( 'list_comments_by' );
			if ( $order == 'timestamp' ) $order .= ' DESC';
			if ( $order == 'name' ) $order = 'user';
			$query = "SELECT * FROM $wpdb->wppa_comments ORDER BY $order LIMIT 1000";

			$comments = $wpdb->get_results( $query, ARRAY_A );

			$header = sprintf( __( 'List of comments <small>( Max 1000 entries of total %d )</small>', 'wp-photo-album-plus' ), $total );

			$result .= '
			<div
				style="float:left;clear:both;width:100%;overflow:auto;margin-left:-1px;"
				>';

			if ( $comments ) {
				$result .= '
				<table>
					<thead>
						<tr>
							<th>Id</th>
							<th>Timestamp</th>
							<th>Date/time</th>
							<th>Status</th>
							<th>User</th>
							<th>UserId</th>
							<th>Email</th>
							<th>Photo id</th>
							<th></th>
							<th>Comment</th>
						</tr>
						<tr><td colspan="10"><hr /></td></tr>
					</thead>
					<tbody>';

				foreach ( $comments as $comment ) {
					$thumb = wppa_cache_photo( $comment['photo'] );
					$result .= '
						<tr>
							<td>' . htmlspecialchars( $comment['id'] ) . '</td>
							<td>' . htmlspecialchars( $comment['timestamp'] ) . '</td>
							<td>' . htmlspecialchars( $comment['timestamp'] ? wppa_local_date( '', $comment['timestamp'] ) : 'pre-historic' ) . '</td>
							<td>' . htmlspecialchars( $comment['status'] ) . '</td>
							<td>' . htmlspecialchars( $comment['user'] ) . '</td>
							<td>' . htmlspecialchars( $comment['userid'] ) . '</td>
							<td>' . htmlspecialchars( $comment['email'] ) . '</td>
							<td>' . htmlspecialchars( $comment['photo'] ) . '</td>
							<td style="width:250px; text-align:center">
								<img
									src="' . esc_url( wppa_get_thumb_url( $comment['photo'] ) ) . '"
									style="height: 40px;"
									onmouseover="jQuery(this).stop().animate({height:this.naturalHeight}, 200);"
									onmouseout="jQuery(this).stop().animate({height:\'40px\'}, 200);"
								/>
							</td>
							<td>' . htmlspecialchars( $comment['comment'] ) . '</td>
						</tr>';
				}

				$result .= '
					</tbody>
				</table>';
			}
			else {
				$result .= __( 'There are no comments', 'wp-photo-album-plus' );
			}
			$result .= '
				</div><div style="clear:both;"></div>';
			break;

		case 'wppa_list_debuglog':
			$header = __( 'List of debug error messages', 'wp-photo-album-plus' );

			$result .= '
			<div
				style="float:left;clear:both;width:100%;overflow:auto;margin-left:-1px;"
				>';

			$debug_log = WP_CONTENT_DIR . '/debug.log';
			if ( is_readable( $debug_log ) ) {

				$data = wppa_get_contents( $debug_log );
				$result .= nl2br( $data );
			}
			else {

				// Should never get here
				$result .= __('The logfile does not exist', 'wp-photo-album-plus');
			}

			$result .= '
				</div><div style="clear:both;"></div>';
			break;

		default:
			$result = 'Error: Unimplemented slug: ' . $slug . ' in wppa_do_maintenance_popup()';
	}

	$result .= '
	</div></div>';

	return $header . '|' . $result;
}

function wppa_recuperate( $id ) {

	$thumb = wppa_cache_photo( $id );
	$iptcfix = false;
	$exiffix = false;
	$file = wppa_get_source_path( $id );
	if ( ! is_file( $file ) ) $file = wppa_get_photo_path( $id, false );

	if ( is_file ( $file ) ) {					// Not a dir
		$attr = getimagesize( $file, $info );
		if ( is_array( $attr ) ) {				// Is a picturefile
			if ( $attr[2] == IMAGETYPE_JPEG ) {	// Is a jpg

				// Save iptc is on?
				if ( wppa_switch( 'save_iptc' ) ) {

					// There is IPTC data
					if ( isset( $info["APP13"] ) ) {

						// If this is a cron prcess, the table is not pre-emptied
						if ( wppa_is_cron() ) {

							// Replace or add data
							wppa_import_iptc( $id, $info );
						}

						// Normal real-time action, no pre-delete required
						else {
							wppa_import_iptc( $id, $info, 'nodelete' );

						}
						$iptcfix = true;
					}
				}

				// Save exif is on?
				if ( wppa_switch( 'save_exif') ) {
					$image_type = exif_imagetype( $file );

					// EXIF supported by server
					if ( $image_type == IMAGETYPE_JPEG ) {

						// Get exif data
						$exif = wppa_exif_read_data( $file, 'ANY_TAG' );

						// Exif data found
						if ( $exif ) {

							// If this is a cron prcess, the table is not pre-emptied
							if ( wppa_is_cron() ) {

								// Replace or add data
								wppa_import_exif( $id, $file );
							}

							// Normal real-time action, no pre-delete required
							else {
								wppa_import_exif($id, $file, 'nodelete');
							}
							$exiffix = true;
						}
					}
				}
			}
		}
	}
	return array( 'iptcfix' => $iptcfix, 'exiffix' => $exiffix );
}

// Fix erroneous source path in case of migration to an other host
function wppa_fix_source_path() {

	if ( strpos( wppa_opt( 'source_dir' ), ABSPATH ) === 0 ) return; 					// Nothing to do here

	$wp_content = trim( str_replace( home_url(), '', content_url() ), '/' );

	// The source path should be: ( default ) WPPA_ABSPATH.WPPA_UPLOAD.'/wppa-source',
	// Or at least below WPPA_ABSPATH
	if ( strpos( wppa_opt( 'source_dir' ), WPPA_ABSPATH ) === false ) {
		if ( strpos( wppa_opt( 'source_dir' ), $wp_content ) !== false ) {	// Its below wp-content
			$temp = explode( $wp_content, wppa_opt( 'source_dir' ) );
			$temp['0'] = WPPA_ABSPATH;
			wppa_update_option( 'wppa_source_dir', implode( $wp_content, $temp ) );
			wppa_log( 'Fix', 'Sourcepath set to ' . wppa_opt( 'source_dir' ) );
		}
		else { // Give up, set to default
			wppa_update_option( 'wppa_source_dir', WPPA_ABSPATH.WPPA_UPLOAD.'/wppa-source' );
			wppa_log( 'Fix', 'Sourcepath set to default.' );
		}
	}
}

function wppa_log_page() {

	wppa_echo( '
	<div class="wrap">' .
		wppa_admin_spinner() .
		wp_nonce_field( 'wppa-nonce', 'wppa-nonce', true, false ) . '

		<h1 style="display:inline">' . get_admin_page_title() . '
			<input
				class="button button-primary"
				style="float:right;"
				value="Purge logfile"
				onclick="wppaAjaxUpdateOptionValue(\'errorlog_purge\', 0);jQuery(\'#wppa-maintenance-list\').fadeOut(2000);"
				type="button" >
		</h1><br>' .

		wp_nonce_field('wppa-nonce', 'wppa-nonce') . '

		&nbsp;<img
			id="wppa-spinner"
			src="' . wppa_get_imgdir( 'spinner.gif' ) . '"
			style="display:none;"
			onload="setInterval(function(){wppaAjaxReplaceLog();}, 10000)"
		/>
		<div id="wppa-logbody" >' .

			ltrim( wppa_do_maintenance_popup( 'wppa_list_errorlog' ), '| ' ) .

		'</div>
	</div>' );

}
