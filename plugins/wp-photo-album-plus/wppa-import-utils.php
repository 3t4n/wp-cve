<?php
/* wppa-import-utils.php
* Package: wp-photo-album-plus
*
* Contains import utility functions
* Version 8.3.07.003
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// get array of files to import
function wppa_get_import_files() {
global $wppa_session;

	// Init
	$user 			= wppa_get_user();
	$source_type 	= wppa_get_option( 'wppa_import_source_type_'.$user, 'local' );
	$files			= array();

	// Dispatch on source type local/remote
	if ( $source_type == 'local' ) {
		$source 		= wppa_get_option( 'wppa_import_source_'.$user, WPPA_DEPOT_PATH );
		$source_path 	= $source;	// Filesystem
		$files 			= wppa_glob( $source_path . '/*' );
	}
	else { // remote
		$max_tries 		= wppa_get_option( 'wppa_import_remote_max_'.$user, '10' );
		$setting 		= wppa_get_option( 'wppa_import_source_url_'.$user, 'http://' );
		$pattern		= '/src=".*?"/';

		// Is it a photofile in a wppa tree filestructure?
		$old_setting = $setting;

		// assume not
		if ( ! $wppa_session['is_wppa_tree'] && wppa_is_url_a_photo( $setting ) ) {
			$wppa_session['is_wppa_tree'] = false;
			wppa( 'is_wppa_tree', false );
			$is_image = true;
		}
		else {
			$setting = wppa_expand_tree_path( $old_setting );

			// though?
			if ( wppa_is_url_a_photo( $setting ) ) {
				$wppa_session['is_wppa_tree'] = true;
				wppa( 'is_wppa_tree', true );
				$is_image = true;
			}
			else {
				$is_image = false;
			}
		}

		// Is it a photofile?
		if ( $is_image ) {
			$files = array( $setting );
			$pid = wppa_strip_ext( basename( $old_setting ) );
			if ( is_numeric( $pid ) ) {
				$tries = 1;
				$before = substr( $old_setting, 0, strpos( $old_setting, $pid) );
				while ( $tries < $max_tries ) {
					$tries++;
					$pid++;
					if ( wppa( 'is_wppa_tree' ) ) {
						$files[] = $before . wppa_expand_id($pid) . '.jpg';
					}
					else {
						$files[] = $before . $pid . '.jpg';
					}
				}
			}
		}

		// is it a page url
		else {
			$files = wppa_get_option( 'wppa_import_source_url_found_' . $user, false );
			if ( ! $files ) {

				// Init
				$files = array();

				// Get page content
				$response = wp_remote_get( $setting );
				$contents = wp_remote_retrieve_body( $response );
				$httpcode = wp_remote_retrieve_response_code( $response );

				// Process result
				if ( $httpcode == 200 ) {

					// Preprocess
					$contents = str_replace( '\'', '"', $contents );

					// Find matches
					preg_match_all( $pattern, $contents, $matches, PREG_PATTERN_ORDER );
					if ( is_array( $matches[0] ) ) {

						// Sort
						sort( $matches[0] );

						// Copy to $files, skipping dups
						$val = '';
						$count = 0;
						$sfxs = array( 'jpg', 'jpeg', 'gif', 'png', 'JPG', 'JPEG', 'GIF', 'PNG' );
						foreach ( array_keys( $matches[0] ) as $idx ) {
							if ( $matches[0][$idx] != $val ) {
								$val = $matches[0][$idx];

								// Post process found item
								$match 		= substr( $matches[0][$idx], 5 );
								$matchpos 	= strpos( $contents, $match );
								$match 		= trim( $match, '"' );
								if ( strpos( $match, '?' ) ) $match = substr( $match, 0, strpos( $match, '?' ) );
								$match 		= str_replace( '/uploads/wppa/thumbs/', '/uploads/wppa/', $match );
								$sfx = wppa_get_ext( $match );
								if ( in_array( $sfx, $sfxs ) ) {
									// Save it
									$count++;
									if ( $count <= $max_tries ) {
										$files[] = $match;
									}
								}
							}
						}
					}
				}
				wppa_update_option( 'wppa_import_source_url_found_'.$user, $files );
			}
		}
	}

	// Remove non originals
	if ( is_array( $files ) ) foreach ( array_keys( $files ) as $key ) {
		if ( ! wppa_is_orig( $files[$key] ) ) {
			unset ( $files[$key] );
		}
	}

	// Security fix: remove paths with path traversal character sequences (../)
	if ( is_array( $files ) ) foreach ( array_keys( $files ) as $key ) {
		if ( strpos( $files[$key], '../' ) || strpos( $files[$key], '..\\' ) ) {
			unset( $files[$key] );
		}
	}

	// Sort to keep synchronicity when doing ajax import
	if ( is_array( $files ) ) sort( $files );

	// Done, return result
	return $files;
}

function wppa_wrong_value( $value, $field, $extra = '' ) {
	$message = htmlspecialchars( sprintf( __( 'Value %s is not valid for %s.', 'wp-photo-album-plus' ), $value, $field ) );
	if ( $extra ) {
		$message .= ' ' . $extra;
	}
	$message .= ' ' . __( 'This value is ignored.', 'wp-photo-album-plus' );
	wppa_log( 'err', $message );
}

function wppa_get_zipcount( $files ) {
	$result = 0;
	if ( $files ) {
		foreach ( $files as $file ) {
			$ext = strtolower( substr( strrchr( $file, "." ), 1 ) );
			if ( $ext == 'zip' ) $result++;
		}
	}
	return $result;
}

function wppa_get_albumcount( $files ) {
	$result = 0;
	if ( $files ) {
		foreach ( $files as $file ) {
			$ext = strtolower( substr( strrchr( $file, "." ), 1 ) );
			if ( $ext == 'amf' ) $result++;
		}
	}
	return $result;
}

function wppa_get_photocount( $files ) {
global $wppa_supported_photo_extensions;

	$result = 0;
	if ( $files ) {
		foreach ( $files as $file ) {
			$ext = strtolower( wppa_get_ext( $file ) );
			if ( in_array( $ext, $wppa_supported_photo_extensions ) ) $result++;
		}
	}
	return $result;
}

function wppa_get_video_count( $files ) {
global $wppa_supported_video_extensions;

	$result = 0;
	if ( $files ) {
		foreach ( $files as $file ) {
			$ext = strtolower( wppa_get_ext( $file ) );
			if ( in_array( $ext, $wppa_supported_video_extensions ) ) $result++;
		}
	}
	return $result;
}

function wppa_get_audio_count( $files ) {
global $wppa_supported_audio_extensions;

	$result = 0;
	if ( $files ) {
		foreach ( $files as $file ) {
			$ext = strtolower( wppa_get_ext( $file ) );
			if ( in_array( $ext, $wppa_supported_audio_extensions ) ) $result++;
		}
	}
	return $result;
}

function wppa_get_documentcount( $files ) {

	$result = 0;
	if ( ! wppa_switch( 'enable_pdf' ) ) {
		return 0;
	}
	if ( $files ) {
		foreach ( $files as $file ) {
			$ext = strtolower( wppa_get_ext( $file ) );
			if ( in_array( $ext, array( 'pdf', 'PDF' ) ) ) $result++;
		}
	}
	return $result;
}

// Find dir is new album candidates
function wppa_get_dircount( $files ) {
	$result = 0;
	if ( $files ) {
		foreach ( $files as $file ) {
			if ( basename( $file ) != '.' &&
				 basename( $file ) != '..' &&
				 wppa_is_dir( $file ) ) {
					$result++;
				 }
		}
	}
	return $result;
}

// Find .csv file count
function wppa_get_csvcount( $files ) {
	$result = 0;
	if ( $files ) {
		foreach ( $files as $file ) {
			if ( strtolower( wppa_get_ext( $file ) ) == 'csv' ) $result++;
		}
	}
	return $result;
}

function wppa_get_meta_name( $file, $opt = '' ) {
	return wppa_get_meta_data( $file, 'name', $opt );
}
function wppa_get_meta_album( $file, $opt = '' ) {
	return wppa_get_meta_data( $file, 'albm', $opt );
}
function wppa_get_meta_desc( $file, $opt = '' ) {
	return wppa_get_meta_data( $file, 'desc', $opt );
}
function wppa_get_meta_porder( $file, $opt = '' ) {
	return wppa_get_meta_data( $file, 'pord', $opt );
}
function wppa_get_meta_linkurl( $file, $opt = '' ) {
	return wppa_get_meta_data( $file, 'lnku', $opt );
}
function wppa_get_meta_linktitle( $file, $opt = '' ) {
	return wppa_get_meta_data( $file, 'lnkt', $opt );
}
function wppa_get_meta_owner( $file, $opt = '' ) {
	$user 	= wppa_get_user();
	$owner 	= wppa_get_meta_data( $file, 'ownr', $opt );
	if ( $owner ) {
		return $owner;
	}
	return $user;
}
function wppa_get_meta_data( $file, $item, $opt ) {
	$result = '';
	$opt2 = '';
	if ( $opt == '( ' ) $opt2 = ' )';
	if ( $opt == '{' ) $opt2 = '}';
	if ( $opt == '[' ) $opt2 = ']';
	if ( wppa_is_file( $file ) ) {

		$buffers = wppa_get_contents_array( $file );
		if ( $buffers ) {
			foreach ( $buffers as $buffer ) {
				if ( substr( $buffer, 0, 5 ) == $item.'=' ) {
					if ( $opt == '' ) $result = substr( $buffer, 5, strlen( $buffer )-6 );
					else $result = $opt.__( substr( $buffer, 5, strlen( $buffer )-6 ) ).$opt2;		// Translate for display purposes only
				}
			}
		}
	}
	return $result;
}

// Treewalk for finding potential import from local dirs
function wppa_abs_walktree( $root, $source ) {
static $void_dirs;

	$result = '';

	// Init void dirs
	if ( ! $void_dirs ) {
		$void_dirs = array( '.', '..',
							'wp-admin',
							'wp-includes',
							'themes',
							'upgrade',
							'plugins',
							'languages',
							'wppa',
							'cache',
							'widget-cache',
							'wppa-cdn',
							( wppa_switch( 'allow_import_source') ? '' : 'wppa-source' ),
							);
	}

	// If currently in selected dir, set selected
	$sel = $root == $source ? ' selected' : '';

	// Set disabled if there are no files inside
	$files 	 = wppa_glob( $root . '/*', null, true );
	$n_files = ! empty( $files ) ? count( $files ) : 0;
	$dirs 	 = wppa_glob( $root . '/*', GLOB_ONLYDIR, true );
	$n_dirs  = ! empty( $dirs ) ? count( $dirs ) : 0;
	$dis     = $n_files == $n_dirs ? ' disabled' : '';

	// Check for (sub)depot
	$my_depot = __( '--- My depot --- ' ,'wp-photo-album-plus' );
	$display  = str_replace( WPPA_DEPOT_PATH, $my_depot, $root );
	if ( strpos( $display, $my_depot ) !== false ) {
		$dis = '';
	}

	// Check for ngg gallery dir
	$ngg_opts = wppa_get_option( 'ngg_options', false );
	if ( $ngg_opts ) {
		$ngg_gal =  __( '--- Ngg Galleries --- ', 'wp-photo-album-plus' );
		$display = str_replace( rtrim( $ngg_opts['gallerypath'], '/' ), $ngg_gal, $display );
		$pos = strpos( $display, $ngg_gal );
		if ( $pos ) {
			$display = substr( $display, $pos );
		}
	}

	// Remove ABSPATH from display string
	$display = str_replace( ABSPATH, '', $display );

	// Output the selecion if not in the wp dir
	if ( $root.'/' != ABSPATH ) {
		$result .=
			'<option' .
				' value="' . $root . '"' .
				$sel .
				$dis .
				' data-nfiles="' . $n_files . '"' .
				' data-ndirs="' . $n_dirs . '"' .
				' >' .
				$display .
			'</option>';
	}

	// See if subdirs exist
	$dirs = wppa_glob( $root . '/*', GLOB_ONLYDIR, true );

	// Go deeper if not in a list of void disnames
	if ( $dirs ) foreach( $dirs as $path ) {
		$dir = basename( $path );
		if ( ! in_array( $dir, $void_dirs ) ) {
			$newroot = $root . '/' . $dir;
			$result .= wppa_abs_walktree( $newroot, $source );
		}
	}

	return $result;
}

// The Edit Album link button html
function wppa_edit_album_link_button( $tagid ) {

	if ( ! current_user_can( 'wppa_admin' ) ) {
		return '';
	}

	$url = get_admin_url() . 'admin.php?page=wppa_admin_menu&tab=edit&wppa-nonce=' . wp_create_nonce( 'wppa-nonce', 'wppa-nonce' ) . '&edit-id=';
	$result = '
	<input
		type="button"
		value="' . esc_attr( __( 'Edit album', 'wp-photo-album-plus' ) ) . '"
		onclick="
			var albId = parseInt(jQuery(\'#'. $tagid .'\').val());
			if ( ! albId ) {
				alert(\'' . esc_js( __( 'No album specified', 'wp-photo-album-plus' ) ) . '\');
			}
			else {
				if (confirm(\'' . esc_js( __( 'Connecting to album #', 'wp-photo-album-plus' ) ) . '\' + albId + \'?\') ) {
					window.open(\''. $url . '\'+albId,\'_blank\');
				}
			}
		"
	/> ';

	return $result;
}

// For import csv: quit with optional deleted
function wppa_import_csv_wrapup( $handle, $write_handle, $code, $tempfile, $file ) {

	wppa_fclose( $handle );
	wppa_fclose( $write_handle );
	wppa_copy( $tempfile, $file );
	wppa_unlink( $tempfile, true );
	wppa_import_quit( $code );
}

// Output appropriate message and exit
function wppa_import_quit( $code = '0', $deleted = false, $continue = false, $reload = false, $done = 0, $skip = 0 ) {
global $wppa_noquit;

	if ( $wppa_noquit && $code != '23' ) {
		return;
	}

	$data = [ 'code' => $code,
			  'deleted' => ( $deleted ? '1' : '0' ),
			  'continue' => ( $continue ? '1' : '0' ),
			  'reload' => ( $reload ? '1' : '0' ),
			  'done' => $done,
			  'skip' => $skip,
			];

	$result = json_encode( $data );
	wppa_echo( $result );
	if ( $code == '0' ) {
		wppa_clear_cache( ['force' => true] );
	}
	wppa_exit();
}

// Do we have realmedia on board?
function wppa_has_realmedia() {
static $been_here;
static $result;
global $wpdb;

	if ( $been_here ) {
		return $result;
	}

	$been_here = true;

	$pl = implode( ',', wppa_get_option( 'active_plugins' ) );
	$result = strpos( $pl, 'real-media-library' ) !== false;

	if ( $result ) { 	// Installed realmedia
		if ( is_multisite() && WPPA_MULTISITE_GLOBAL ) {
			$wppa_prefix = $wpdb->base_prefix;
		}
		else {
			$wppa_prefix = $wpdb->prefix;
		}
		define( 'WPPA_REALMEDIA', $wppa_prefix . 'realmedialibrary' );
		$wpdb->wppa_realmedialibrary = WPPA_REALMEDIA;
		define( 'WPPA_REALMEDIA_POSTS', $wppa_prefix . 'realmedialibrary_posts' );
		$wpdb->wppa_realmedialibrary_posts = WPPA_REALMEDIA_POSTS;
	}

	return $result;
}

// Are we in depot?
function wppa_is_depot() {

	$here = wppa_get_option( 'wppa_import_source_' . wppa_get_user(), WPPA_DEPOT_PATH );
	return $here == WPPA_DEPOT_PATH;
}

// Are we in a subdepot?
function wppa_is_subdepot() {

	$here = wppa_get_option( 'wppa_import_source_' . wppa_get_user(), WPPA_DEPOT_PATH );
	return substr( $here, 0, strlen( WPPA_DEPOT_PATH ) ) == WPPA_DEPOT_PATH;
}

// Log the reason why an import item is skipped
function wppa_log_skip( $file, $reason ) {

	$f = basename( $file );
	wppa_log( 'war', "Importing $f skipped. Reason: $reason" );
}