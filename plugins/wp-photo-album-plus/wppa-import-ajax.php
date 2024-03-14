<?php
/* wppa-import-ajax.php
* Package: wp-photo-album-plus
*
* Contains the actual import functions
* Version: 8.4.01.004
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Do the import albums photos etc,
// This function is called by wppa-ajax.php
function wppa_import_photos() {
global $wppa_supported_photo_extensions;
global $wppa_supported_video_extensions;
global $wppa_supported_audio_extensions;
global $wppa_supported_document_extensions;
global $wppa_session;
global $wppa_done;
global $wppa_skip;

	$wppa_done = 0;
	$wppa_skip = 0;

	// Used for personal header switches
	$user = wppa_get_user();

	// Are we remote?
	wppa( 'is_remote', wppa_get_option( 'wppa_import_source_type_' . $user, 'local' ) == 'remote' );

	// Preliminary name (can be changed for remote treestructure file)
	$name = basename( wppa_get( 'import-ajax-file' ) );

	// From realmedia?
	if ( wppa_get( 'import-realmedia', '', 'text' ) ) {
		if ( ! check_admin_referer( '$wppa_nonce', WPPA_NONCE ) ) {
			wppa_import_quit( '13' );
		}

		wppa_import_from_realmedia();
		wppa_exit();
	}

	// Remote?
	if ( wppa( 'is_remote' ) ) {
		$tfile = wppa_get( 'import-ajax-file' );
		$file = wppa_is_url_a_photo( $tfile );
		$name = basename( $file ); 	// Maybe updated name
		if ( ! wppa_is_file( $file ) && ! wppa_is_dir( $file ) ) {
			wppa_import_quit( '14' ); // Not found!
		}
		wppa_update_option( 'wppa_import_source_url_' . $user, wppa_compress_tree_path( $tfile ) );
	}

	// Local not from realmedia
	if ( ! wp_verify_nonce( wppa_get( 'update-check' ), '$wppa_nonce' ) ) {
		wppa_import_quit( '13' ); 	// Secuerity check
	}

	/* We are legally here */

	// Remember header check statusses
	$switches =    ["cre-album",
					"use-backup",
					"wppa-update",
					"wppa-nodups",
					"wppa-zoom",
					"del-after-z",		// zip
					"del-after-fz",
					"del-after-a",		// album
					"del-after-fa",
					"del-after-p",		// photo
					"del-after-fp",
					"del-after-v",		// video
					"del-after-fv",
					"del-after-u",		// audio
					"del-after-fu",
					"del-after-d",		// doc
					"del-after-fd",
					"del-after-c", 		// csv
					"del-after-fc",
					"del-dir",
					"del-dir-cont",
					];
	foreach( $switches as $switch ) {
		if ( wppa_get( $switch, '9' ) != '9' ) {
			update_option( $switch . '-' . $user, wppa_get( $switch ) );
		}
	}

	// Get the file to be done specification
	$file = wppa_get( 'import-ajax-file' );
	if ( substr( $file, 0, 7 ) == 'http://' || substr( $file, 0, 8 ) == 'https://' ) {
		$file = wppa_is_url_a_photo( $file );
	}

	$ext 			= wppa_get_ext( $file );
	$name 			= basename( $file );
	$is_file 		= $name != $file && wppa_is_file( $file );
	$is_dir 		= $name != $file && wppa_is_dir( $file ) && $name != '.' && $name != '..';

	// Part #1: Do the file
	if ( $is_file ) {

		// ZIP First extract a zip if we have zip onboard
		if ( $ext == 'zip' ) {
			wppa_import_a_zip( $file );
		}

		// AMF If its an album...
		if ( $ext == 'amf' ) {
			wppa_import_a_album( $file );
		}

		// PHOTO
		if ( in_array( $ext, $wppa_supported_photo_extensions ) ) {
			wppa_import_a_photo( $file );
		}

		// VIDEO
		if ( in_array( $ext, $wppa_supported_video_extensions ) ) {
			wppa_import_a_video( $file );
		}

		// AUDIO
		if ( in_array( $ext, $wppa_supported_audio_extensions ) ) {
			wppa_import_a_audio( $file );
		}

		// DOCUMENT
		if ( in_array( $ext, $wppa_supported_document_extensions ) ) {
			wppa_import_a_document( $file );
		}

		// CSV
		if ( $ext == 'csv' ) {
			wppa_import_a_csv( $file );
		}
	}
	// is file

	// Part #2: Do the dir
	if ( $is_dir ) {
		wppa_import_a_dir( $file );
	}

	// Part #3: Try realmedia
	if ( wppa_has_realmedia() ) {

		// RM Album
		if ( wppa_get( 'type', '', 'text' ) == 'rma' ) {
			wppa_import_a_rm_album( $name );
		}

		// RM Item
		if ( wppa_get( 'type', '', 'text' ) == 'rmi' ) {
			wppa_import_a_rm_item( $name );
		}
	}

	wppa_import_quit( '14' );
}

/* Actual importing functions below here */

// Import a zip
function wppa_import_a_zip( $file ) {

	$name = basename( $file );

	// Extract a zip
	// We extract all files to the users depot.
	// The illegal files will be deleted there by the wppa_sanitize_files routine,
	// so there is no chance a depot/destroy.php or the like will get a chance to be created.
	// so...

	$err = '0';
	if ( ! class_exists( 'ZipArchive' ) ) {
		wppa_import_quit( '10' );
		wppa_log( 'err', 'Class ZipArchive does not exist! Check your php configuration' );
		wppa_import_quit( '30' ); // not implemented
	}

	// Start security fix
	$path = wppa_sanitize_file_name( $file );
	if ( ! wppa_is_file( $file ) ) {
		wppa_log( 'err', 'Zipfile ' . htmlspecialchars( $path ) . ' does not exist.' );
		wppa_import_quit( '11' );
	}
	// End security fix

	// Do the dirty work if it is a .zip file
	$ext = strtolower( wppa_get_ext( $file ) );
	if ( $ext == 'zip' ) {
		$zip = new ZipArchive;
		if ( $zip->open( WPPA_DEPOT_PATH . '/' . basename( $file ) ) === true ) {
			$zip->extractTo( WPPA_DEPOT_PATH );
			$zip->close();
			if ( wppa_get( 'del-after-z' ) ) {
				wppa_unlink( $file );
				wppa_import_quit( '0', true, false, true );
			}
			wppa_import_quit( '0', false, false, true );
		}
		else {
			wppa_log( 'err', __( 'Failed to extract', 'wp-photo-album-plus' ) . ' ' . $path );
			if ( wppa_get( 'del-after-fz' ) ) {
				wppa_unlink( $file );
				wppa_import_quit( '8', true );
			}
			wppa_import_quit( '8', false );
		}
	}
}

// Import an album ( .amf file )
function wppa_import_a_album( $path ) {

	// Validate file
	$ext = strtolower( wppa_get_ext( $path ) );
	if ( $ext != 'amf' ) {
		wppa_import_quit( '1' );
	}

	$name 		= '';
	$desc 		= '';
	$aord 		= '0';
	$parent 	= '0';
	$porder 	= '0';
	$owner 		= ( wppa_switch( 'backend_album_public' ) ? '--- public ---' : wppa_get_user() );
	$dela 		= wppa_get( 'del-after-a' );
	$delfa 		= wppa_get( 'del-after-fa' );
	$buffers 	= wppa_get_contents_array( $path );
	foreach( $buffers as $buffer ) {
		$tag = substr( $buffer, 0, 5 );
		$data = substr( $buffer, 5 ); //, $len );
		$data = trim( $data, "\n" );
		switch( $tag ) {
			case 'name=':
				$name = $data;
				break;
			case 'desc=':
				$desc = wppa_txt_to_nl( $data );
				break;
			case 'aord=':
				if ( is_numeric( $data ) ) $aord = $data;
				break;
			case 'prnt=':
				if ( $data == __( '--- none ---', 'wp-photo-album-plus' ) ) $parent = '0';
				elseif ( $data == __( '--- separate ---', 'wp-photo-album-plus' ) ) $parent = '-1';
				else {
					$prnt = wppa_get_album_id( $data );
					if ( $prnt != '' ) {
						$parent = $prnt;
					}
					else {
						$parent = '0';
						wppa_log('war', __( 'Unknown parent album:', 'wp-photo-album-plus' ).' '.htmlspecialchars($data).' '.__( '--- none --- used.', 'wp-photo-album-plus' ) );
					}
				}
				break;
			case 'pord=':
				if ( is_numeric( $data ) ) $porder = $data;
				break;
			case 'ownr=':
				$owner = $data;
				break;
			default:
				break;
		}
	}

	if ( wppa_get_album_id( $name ) ) {
		wppa_log( 'err', 'Album already exists ' . htmlspecialchars( $name ) );
		if ( $delfa ) {
			wppa_unlink( $path );
			wppa_import_quit( '9', true );
		}
		wppa_import_quit( '9' );
	}

//	$id = basename( $album );
//	$id = substr( $id, 0, strpos( $id, '.' ) );
	$id = wppa_create_album_entry( array ( 	// 'id' 			=> $id,
											'name' 			=> stripslashes( $name ),
											'description' 	=> stripslashes( $desc ),
											'a_order' 		=> $aord,
											'a_parent' 		=> $parent,
											'p_order_by' 	=> $porder,
											'owner' 		=> $owner
										 ) );

	if ( $id === false ) {
		wppa_log( 'err', __( 'Could not create album.', 'wp-photo-album-plus' ) );
		if ( $delfa ) {
			wppa_unlink( $path );
			wppa_import_quit( '16', true );
		}
		wppa_import_quit( '16' );
	}

	wppa_set_last_album( $id );
	wppa_index_update( 'album', $id );

	if ( $dela ) {
		wppa_unlink( $path );
		wppa_clear_cache( ['albums' => true] );
		wppa_invalidate_treecounts( $id );
		wppa_import_quit( '0', true );
	}

	// Done
	wppa_import_quit();
}

// Import a photo
function wppa_import_a_photo( $file, $album = '' ) {
global $wppa_done;
global $wppa_skip;

	$ok = false;

	// Update last used album
	if ( wppa_get( 'photo-album' ) ) {
		wppa_update_option( 'wppa-photo-album-import-' . wppa_get_user(), wppa_get( 'photo-album' ) );
	}

	// Find album id if not supplied
	if ( ! $album ) {
		if ( wppa_get( 'cre-album' ) ) {	// use album ngg gallery name for ngg conversion
			$album 	= wppa_get_album_id( wppa_get( 'cre-album' ) );
			if ( ! $album ) {				// the album does not exist yet, create it
				$name	= wppa_get( 'cre-album' );
				$desc 	= sprintf( __( 'This album has been converted from ngg gallery %s', 'wp-photo-album-plus' ), $name );
				$uplim	= '0/0';	// Unlimited not to destroy the conversion process!!
				$album 	= wppa_create_album_entry( array ( 	'name' 			=> $name,
															'description' 	=> $desc,
															'upload_limit' 	=> $uplim,
															'owner' 		=> wppa_switch( 'backend_album_public' ) ? '--- public ---' : wppa_get_user()
															 ) );
				if ( $album === false ) {
					wppa_log( 'Err', __( 'Could not create album.', 'wp-photo-album-plus' ).' '.$name );
					wppa_import_quit( '16' ); 	// Unknown album
				}
			}
		}
		else {
			$album = wppa_get( 'photo-album' );
		}
	}

	// Do the photo
	wppa_is_wppa_tree( $file );	// Sets wppa( 'is_wppa_tree' )
	if ( wppa_get( 'use-backup' ) && is_file( $file.'_backup' ) ) {
		$file = $file.'_backup';
	}
	if ( wppa( 'is_wppa_tree' ) ) {
		wppa( 'ajax_import_files', basename( wppa_compress_tree_path( $file ) ) );
	}
	else {
		wppa( 'ajax_import_files', basename( $file ) );
	}
	$ext = wppa_get_ext( $file );
	$ext = str_replace( '_backup', '', $ext );

	// See if a metafile exists
	$meta = wppa_strip_ext( $file ) . '.pmf';

	// find all data: name, desc, porder form metafile
	if ( is_file( $meta ) ) {
		$alb 		= wppa_get_album_id( wppa_get_meta_album( $meta ) );
		$name 		= wppa_get_meta_name( $meta );
		$desc 		= wppa_txt_to_nl( wppa_get_meta_desc( $meta ) );
		$porder 	= wppa_get_meta_porder( $meta );
		$linkurl 	= wppa_get_meta_linkurl( $meta );
		$linktitle 	= wppa_get_meta_linktitle( $meta );
		$owner 		= wppa_get_meta_owner( $meta );
	}
	else {
		$alb = $album;	// default album
		$name = basename( $file );		// default name
		$desc = '';		// default description
		$porder = '0';	// default p_order
		$linkurl = '';
		$linktitle = '';
		$owner = wppa_get_user();
	}

	// If there is a video or audio or document with the same name, this is the poster.
	// Current item is a photo here, so need not check on duplicate photo
	$ext_id = wppa_is_file_alias( $file, $alb );
	if ( $ext_id && ( wppa_is_video( $ext_id ) || wppa_has_audio( $ext_id ) || wppa_is_pdf( $ext_id ) ) ) {
		$is_poster = $ext_id;
	}
	else {
		$is_poster = false;
	}

	if ( $is_poster ) {

		// Clear sizes on db
		wppa_update_photo( $is_poster, ['thumbx' => '0', 'thumby' => '0', 'photox' => '0', 'photoy' => '0'] );
		if ( wppa_get_photo_item( $is_poster, 'ext' ) == 'pdf' ) {
			wppa_update_photo( $is_poster, ['ext' => wppa_get_ext( basename( $file  ) )] );
		}

		wppa_save_source( $file, basename( $file ), $alb, wppa_is_pdf( $is_poster ) );
		wppa_make_o1_source( $is_poster );
		wppa_bump_photo_rev();
		wppa_bump_thumb_rev();

		// Make new files
		$bret = wppa_make_the_photo_files( $file, $is_poster, strtolower( wppa_get_ext( basename( $file ) ) ) );
		if ( $bret ) { 	// Success
			$wppa_done++;
			$ok = true;
			if ( wppa_get( 'del-after-p' ) ) {
				wppa_unlink( $file );
				wppa_import_quit( '-10' ); 	// Poster added
			}
			wppa_import_quit( '-10' );
		}
		else { 			// Failed
			$wppa_skip++;
			wppa_log_skip( $file, 'Make the photofiles Failed' );
			if ( wppa_get( 'del-after-fp' ) ) {
				wppa_unlink( $file );
				wppa_import_quit( '34', true ); // Poster failed
			}
			wppa_import_quit( '34' ); // Poster failed
		}
	}

	// Update the photo ?
	elseif ( wppa_get( 'update' ) && wppa_is_file_duplicate_photo( $name, $alb ) ) {

		if ( wppa( 'is_wppa_tree' ) ) {
			$tmp = explode( '/wppa/', $file );
			$name = str_replace( '/', '', $tmp[1] );
		}

		$iret = wppa_update_photo_files( $file, $name );
		if ( $iret ) {
			$wppa_done++;
			$ok = true;
			if ( wppa_get( 'del-after-p' ) ) {
				wppa_unlink( $file );
				wppa_import_quit( '0', true );
			}
			wppa_import_quit();
		}
		else {
			$wppa_skip++;
			wppa_log_skip( $file, 'update failed' );
			if ( wppa_get( 'del-after-fp' ) ) {
				wppa_unlink( $file );
				wppa_import_quit( '3', true );
			}
			wppa_import_quit( '3', true );
		}
	}

	// Insert the photo
	else {

		// Compress possible treestructure
		if ( ! $name ) {
			$name = basename( wppa_compress_tree_path( $file ) );
		}

		// Do we have an album id?
		if ( is_numeric( $alb ) && $alb != '0' ) {

			// Is it a dup and no dups?
			if ( ( wppa_switch( 'void_dups' ) || wppa_get( 'nodups' ) ) && wppa_is_file_duplicate_photo( $name, $alb ) ) {
				$wppa_skip++;
				wppa_log_skip( $name, 'duplicate not allowed' );
				if ( wppa( 'is_remote' ) ) {
					$path = WPPA_DEPOT_PATH . '/' . basename( wppa_compress_tree_path( $file ) );
					if ( wppa_is_file( $path ) ) {
						wppa_unlink( $path );
					}
					wppa_import_quit( '15' );
				}
				if ( wppa_get( 'del-after-fp' ) ) {
					wppa_unlink( $file );
					wppa_import_quit( '15', true );
				}
				else {
					wppa_import_quit( '15' );
				}
			}

			// Not a dup or allowed dup
			else {

				// If the name is a number, use that for the id, if free
				$id = wppa_strip_ext( $name ); // substr( $id, 0, strpos( $id, '.' ) );
				if ( ! is_numeric( $id ) || ! wppa_is_id_free( WPPA_PHOTOS, $id ) ) {
					$id = 0; 	// Nope
				}

				$id = wppa_insert_photo( $file, $alb, stripslashes( $name ), stripslashes( $desc ), $porder, $id, stripslashes( $linkurl ), stripslashes( $linktitle ), $owner );
				if ( $id ) {
					$wppa_done++;
					$ok = true;
					wppa_set_default_name( $id, stripslashes( $name ) );
					wppa_invalidate_treecounts( $alb );
					if ( wppa_switch( 'beuploadnotify' ) ) {
						wppa_schedule_mailinglist( 'feuploadnotify', $alb, $id, 0, '', 0, 900 );
					}

					// Rmote?
					if ( wppa( 'is_remote' ) ) {
						$path = WPPA_DEPOT_PATH . '/' . basename( wppa_compress_tree_path( $file ) );
						$e = array( 'jpg', 'png' );
						$p = wppa_strip_ext( $path );
						foreach( $e as $ex ) {
							$pt = $p . '.' . $ex;
							if ( is_file( $pt ) ) {
								wppa_unlink( $pt );
								wppa_log('dbg', $pt . ' removed (1)' );
							}
						}
						wppa_update_option( 'wppa_import_source_url_found_' . wppa_get_user(), $file );
						wppa_import_quit();
					}

					// Local
					if ( wppa_get( 'del-after-p' ) ) {
						wppa_unlink( $file );
						if ( is_file( $meta ) ) {
							wppa_unlink( $meta );
						}
						wppa_import_quit( '0', true );
					}
					wppa_import_quit();

				}
				else {
					$wppa_skip++;
					wppa_log_skip( $file, 'could not insert in db' );
					if ( wppa_get( 'del-after-fp' ) ) {
						wppa_unlink( $file );
						wppa_import_quit( '4', true );
					}
					wppa_import_quit( '4' );
				}
			}
		}
		else {
			$wppa_skip++;
			wppa_log_skip( $file, 'unknown or not existing album' );
			wppa_import_quit( '1' ); 	// Unknown album
		}
	}
	return $ok;
}

// Import a video
function wppa_import_a_video( $file, $album = '' ) {
global $wppa_supported_video_extensions;
global $wppa_done;
global $wppa_skip;

	// Update last used album
	if ( wppa_get( 'video-album' ) ) {
		wppa_update_option( 'wppa-video-album-import-' . wppa_get_user(), wppa_get( 'video-album' ) );
	}

	$alb = $album ? $album : wppa_get( 'video-album', $album );
	if ( ! $alb ) {
		wppa_import_quit( '1' );
	}

	$name = basename( $file );
	$ext  = strtolower( wppa_get_ext( $file ) );
	if ( in_array( $ext, $wppa_supported_video_extensions ) ) {
		$the_file = array( 'name'		=> sanitize_file_name( $name ),
						   'tmp_name'	=> $file,
						   'error' 		=> 0,
						   );
		if ( wppa_upload_one_item( $the_file, $alb, 'import' ) ) {
			$wppa_done++;
			if ( wppa_get( 'del-after-v' ) ) {
				if ( wppa_is_file( $file ) ) {
					wppa_unlink( $file );
				}
				wppa_import_quit( '0', true );
			}
			wppa_import_quit();
		}
		else {
			$wppa_skip++;
			wppa_log_skip( $file, 'upload one item failed' );
			if ( wppa_get( 'del-after-fv' ) ) {
				if ( wppa_is_file( $file ) ) {
					wppa_unlink( $file );
				}
				wppa_import_quit( '5', true );
			}
			wppa_import_quit( '5' );
		}
	}
}

// Import an audio
function wppa_import_a_audio( $file, $album = '' ) {
global $wppa_supported_audio_extensions;
global $wppa_done;
global $wppa_skip;

	// Update last used album
	if ( wppa_get( 'audio-album' ) ) {
		wppa_update_option( 'wppa-audio-album-import-' . wppa_get_user(), wppa_get( 'audio-album' ) );
	}

	$alb = $album ? $album : wppa_get( 'audio-album', $album );
	if ( ! $alb ) {
		wppa_import_quit( '1' );
	}

	$name = basename( $file );
	$ext  = strtolower( wppa_get_ext( $file ) );
	if ( in_array( $ext, $wppa_supported_audio_extensions ) ) {

		$the_file = array( 'name'		=> sanitize_file_name( basename( $file ) ),
						   'tmp_name'	=> $file,
						   'error' 		=> 0,
						   );
		if ( wppa_upload_one_item( $the_file, $alb, 'import' ) ) {
			$wppa_done++;
			if ( wppa_get( 'del-after-u' ) ) {
				if ( wppa_is_file( $file ) ) {
					wppa_unlink( $file );
				}
				wppa_import_quit( '0', true );
			}
			wppa_import_quit();
		}
		else {
			$wppa_skip++;
			wppa_log_skip( $file, 'upload one item failed' );
			if ( wppa_get( 'del-after-fu' ) ) {
				if ( wppa_is_file( $file ) ) {
					wppa_unlink( $file );
				}
				wppa_import_quit( '5', true );
			}
			wppa_import_quit( '5' );
		}
	}
}

// Import a pdf
function wppa_import_a_document( $file, $album = '' ) {
global $wppa_supported_document_extensions;
global $wppa_done;
global $wppa_skip;

	// Update last used album
	if ( wppa_get( 'document-album' ) ) {
		wppa_update_option( 'wppa-document-album-import-' . wppa_get_user(), wppa_get( 'document-album' ) );
	}

	$alb = $album ? $album : wppa_get( 'document-album', $album );
	if ( ! $alb ) {
		wppa_import_quit( '1' );
	}

	$name = basename( $file );
	$ext  = strtolower( wppa_get_ext( $file ) );
	if ( in_array( $ext, $wppa_supported_document_extensions ) ) {

		$the_file = array( 'name'		=> sanitize_file_name( basename( $file ) ),
						   'tmp_name'	=> $file,
						   'error' 		=> 0,
						   );
		if ( wppa_upload_one_item( $the_file, $alb, 'import' ) ) {
			$wppa_done++;
			if ( wppa_get( 'del-after-d' ) ) {
				if ( wppa_is_file( $file ) ) {
					wppa_unlink( $file );
					wppa_import_quit( '0', true );
				}
			}
			wppa_import_quit();
		}
		else {
			$wppa_skip++;
			wppa_log_skip( $file, 'upload one item failed' );
			if ( wppa_get( 'del-after-fd' ) ) {
				if ( wppa_is_file( $file ) ) {
					wppa_unlink( $file );
					wppa_import_quit( '7', true );
				}
			}
			wppa_import_quit( '7' );
		}
	}
}

// Import a csv
function wppa_import_a_csv( $file ) {
global $wpdb;

	// Make sure the feature is on
	if ( ! wppa_switch( 'custom_fields' ) ) {
		wppa_update_option( 'wppa_custom_fields', 'yes' );
	}

	// Get the captions we already have
	$cust_labels = array();
	for ( $i = '0'; $i < '10'; $i++ ) {
		$cust_labels[$i] = wppa_opt( 'custom_caption_' . $i );
	}

	// Get the system datafields that may be filled using .csv import
	$syst_lables = array(
						// id bigint(20) NOT NULL,
		'album',		// bigint(20) NOT NULL,
						// ext tinytext NOT NULL,
		'name',			// text NOT NULL,
		'description',	// longtext NOT NULL,
		'p_order',		// smallint(5) NOT NULL,
						// mean_rating tinytext NOT NULL,
		'linkurl',		// text NOT NULL,
		'linktitle',	// text NOT NULL,
		'linktarget', 	// tinytext NOT NULL,
		'owner', 		// text NOT NULL,
		'timestamp', 	// tinytext NOT NULL,
		'status', 		// tinytext NOT NULL,
						// rating_count bigint(20) NOT NULL default '0',
		'tags',			// text NOT NULL,
		'alt',			// tinytext NOT NULL,
						// filename tinytext NOT NULL,
		'modified',		// tinytext NOT NULL,
		'location',		// tinytext NOT NULL,
		'views',		// bigint(20) NOT NULL default '0',
		'clicks',		// bigint(20) NOT NULL default '0',
						// page_id bigint(20) NOT NULL default '0',
		'exifdtm', 		// tinytext NOT NULL,
						// videox smallint(5) NOT NULL default '0',
						// videoy smallint(5) NOT NULL default '0',
						// thumbx smallint(5) NOT NULL default '0',
						// thumby smallint(5) NOT NULL default '0',
						// photox smallint(5) NOT NULL default '0',
						// photoy smallint(5) NOT NULL default '0',
						// scheduledtm tinytext NOT NULL,
						// custom longtext NOT NULL,
						// stereo smallint NOT NULL default '0',
						// crypt tinytext NOT NULL,
	);

	// Process the file
	$name 					= basename( $file );
	$processed 		= '0';
	$skipped 		= '0';
	$is_db_table 	= false;
	$tables 		= array( WPPA_ALBUMS, WPPA_PHOTOS, WPPA_RATING, WPPA_COMMENTS, WPPA_IPTC, WPPA_EXIF, WPPA_INDEX, WPPA_SESSION );

	// See if it is a db table
	foreach( array_keys( $tables ) as $idx ) {
		$table_name = $tables[$idx];
		$file_name = wppa_strip_ext( $name );

		if ( $table_name == $file_name ) {
			$is_db_table = $tables[$idx];

			// Only administrators may do this
			if ( ! current_user_can( 'administrator' ) ) {
				wppa_import_quit( '17' );
			}
		}
	}

	// Copy the file to a temp file
	$tempfile = dirname( $file ) . '/temp.csv';
	wppa_copy ( $file, $tempfile );

	// Open file
	$handle = wppa_fopen( $tempfile, "rt" );
	if ( ! $handle ) {
		wppa_import_quit( '18' );
	}
	$write_handle = wppa_fopen( $file, "wt" );
	if ( ! $write_handle ) {
		wppa_fclose( $handle );
		wppa_import_quit( '19' );
	}

	// Read header
	$header = fgets( $handle, 4096 );
	if ( ! $header ) {
		wppa_fclose( $handle );
		wppa_fclose( $write_handle );
		wppa_import_quit( '20' );
	}
	if ( ! fputs( $write_handle, $header ) ) {
		wppa_fclose( $handle );
		wppa_fclose( $write_handle );
		wppa_import_quit( '21' );
	}

	// If its a db table?
	if ( $is_db_table ) {

		// Functions for inserting db table data
		$entry_functions = array(	WPPA_ALBUMS 	=> 'wppa_create_album_entry',
									WPPA_PHOTOS 	=> 'wppa_create_photo_entry',
									WPPA_RATING 	=> 'wppa_create_rating_entry',
									WPPA_COMMENTS 	=> 'wppa_create_comments_entry',
									WPPA_IPTC 		=> 'wppa_create_iptc_entry',
									WPPA_EXIF 		=> 'wppa_create_exif_entry',
									WPPA_INDEX 		=> 'wppa_create_index_entry',
								);

		// Interprete and verify header. All fields from .csv MUST be in table fields, else fail
		$csv_fields = str_getcsv( $header, wppa_opt( 'csv_sep' ) );
		$db_fields  = $wpdb->get_results( "DESCRIBE " . $is_db_table . "", ARRAY_A );

		foreach( $csv_fields as $csv_field ) {
			$ok = false;
			foreach( $db_fields as $db_field ) {
				if ( $db_field['Field'] === $csv_field ) {
					$ok = true;
				}
			}
			if ( ! $ok ) {

				// Wrap up, no need to delete
				wppa_import_csv_wrapup( $handle, $write_handle, '12', $tempfile, $file );
			}
		}

		// Now process the lines
		while ( ! feof( $handle ) ) {
			$dataline = fgets( $handle, 16*4096 );
			if ( $dataline ) {
				$data_arr = str_getcsv( $dataline, wppa_opt( 'csv_sep' ) );

				// Embedded newlines?
				while ( ( count( $csv_fields ) > count( $data_arr ) ) && ! feof( $handle ) ) {

					// Assume continue after embedded linebreak
					$dataline .= "\n" . fgets( $handle, 16*4096 );
					$data_arr = str_getcsv( $dataline, wppa_opt( 'csv_sep' ) );

				}

				reset( $data_arr );
				$id = trim( current( $data_arr ) );
				if ( wppa_is_int( $id ) && $id > '0' ) {

					$existing_data = $wpdb->get_row( "SELECT * FROM " . $is_db_table . " WHERE id = $id", ARRAY_A );

					// If entry exists:
					// 1. save existing data,
					// 2. remove entry,
					if ( $existing_data ) {
						$data = $existing_data;
						$wpdb->query( "DELETE FROM " . $is_db_table . " WHERE id = $id" );
					}

					// Entry does not / no longer exist, add csv data to data array
					foreach( array_keys( $csv_fields ) as $key ) {
						if ( isset( $data_arr[$key] ) ) {
							$data[$csv_fields[$key]] = $data_arr[$key];
						}
					}

					// Insert 'new' entry
					if ( isset ( $entry_functions[$is_db_table] ) ) {
						$iret = call_user_func_array( $entry_functions[$is_db_table], array( $data ) );
						if ( $iret ) {
							$processed++;
						}
						else {
							$skipped++;
						}
					}
					else {
						wppa_fclose( $handle );
						wppa_fclose( $write_handle );
						wppa_import_quit( '22' );
					}
				}
				else{
					wppa_log( 'err', 'Id field not positive numeric: ' . htmlspecialchars( $id ) );

					// Write back to original file
					$skipped++;
				}
			}

			// Time up?
			if ( wppa_is_time_up() ) {

				// Copy rest of file back to original
				while ( ! feof( $handle ) ) {
					$temp = fgets( $handle, 16*4096 );
					fputs( $write_handle, $temp );
				}
				wppa_unlink( $tempfile );
				wppa_import_quit( '23', false, true, false, $processed, $skipped );
			}
		}
	}

	// Not a db table, a photo cusom data .csv file
	else {

		// Interprete header
		$captions = str_getcsv( $header, wppa_opt( 'csv_sep' ) );

		if ( ! is_array( $captions ) || count( $captions ) < '2' ) {

			// Wrap up, no need to delete
			wppa_import_csv_wrapup( $handle, $write_handle, '24', $tempfile, $file );
		}

		// Verify or add cutom fields
		foreach ( array_keys( $captions ) as $captidx ) {

			// First item must be 'name', 'photoname' or 'filename'
			if ( $captidx == '0' ) {
				if ( ! in_array( strtolower( trim( $captions['0'] ) ), array( 'name', 'photoname', 'filename' ) ) ) {

					// Wrap up, no need to delete
					wppa_import_csv_wrapup( $handle, $write_handle, '25', $tempfile, $file );
				}
			}

			// If a systemlabel, first caption must be filename
			elseif ( in_array( $captions[$captidx], $syst_lables ) ) {
				if ( $captions['0'] != 'filename' ) {

					// Wrap up, no need to delete
					wppa_import_csv_wrapup( $handle, $write_handle, '26', $tempfile, $file );
				}
			}

			// If a new custom lable, there needs to be an empty slot
			elseif ( ! in_array( $captions[$captidx], $cust_labels ) ) {
				if ( ! in_array( '', $cust_labels ) ) {

					// Wrap up, no need to delete
					wppa_import_csv_wrapup( $handle, $write_handle, '27', $tempfile, $file );
				}

				// Add a new caption
				$i = '0';
				while ( $cust_labels[$i] ) $i++;
				$cust_labels[$i] = $captions[$captidx];
				wppa_update_option( 'wppa_custom_caption_' . $i, $cust_labels[$i] );
				wppa_update_option( 'wppa_custom_visible_' . $i, 'yes' );
			}
		}

		// Find the correlation between caption index and custom data index.
		// $custptrs is an array of custom data field numbers
		$custptrs = array();
		for ( $captidx = '1'; $captidx < count( $captions ); $captidx++ ) {
			if ( ! in_array( $captions[$captidx], $syst_lables ) ) {
				for ( $custidx = '0'; $custidx < '10'; $custidx++ ) {
					if ( $captions[$captidx] == $cust_labels[$custidx] ) {
						$custptrs[$custidx] = $captidx;
					}
				}
			}
		}

		// Find the correlation betwwn caption index and system data field names.
		// $systptrs is an array of system data field names. Key is data filed number, value is system field name
		$systptrs = array();
		for ( $captidx = '1'; $captidx < count( $captions ); $captidx++ ) {
			if ( in_array( $captions[$captidx], $syst_lables ) ) {
				$systptrs[$captidx] = $captions[$captidx];
			}
		}

		// Now process the lines
		while ( ! feof( $handle ) ) {
			$dataline = fgets( $handle, 4096 );
			if ( $dataline ) {
				wppa_log( 'dbg', __( 'Read data:', 'wp-photo-album-plus' ) . ' ' . trim( $dataline ) );
				$data_arr = str_getcsv( $dataline, wppa_opt( 'csv_sep' ) );
				foreach( array_keys( $data_arr ) as $i ) {
					if ( ! seems_utf8( $data_arr[$i] ) ) {
						$data_arr[$i] = utf8_encode( $data_arr[$i] );
					}
				}
				$search = $data_arr[0];
				switch ( strtolower($captions[0]) ) {
					case 'photoname':
						$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos WHERE name = %s", $data_arr[0] ), ARRAY_A );
						break;
					case 'filename':
						$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos WHERE filename = %s", $data_arr[0] ), ARRAY_A );
						break;
					case 'name':
						$photos = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos WHERE name = %s OR filename = %s", $data_arr[0], $data_arr[0] ), ARRAY_A );
						break;
					default:
						wppa_log( 'err', 'Unimplemented captions[0]: ' . strtolower( $captions[0] ) . ' in wppa_import_photos()' );
						break;
				}
				if ( $photos ) {
					foreach( $photos as $photo ) {
						$fields = array();

						$cust_data = $photo['custom'] ? wppa_unserialize( $photo['custom'] ) : array( '', '', '', '', '', '', '', '', '', '' );

						// Prepare custom fields
						foreach( array_keys( $custptrs ) as $idx ) {
							if ( isset( $data_arr[$custptrs[$idx]] ) ) {
								$cust_data[$idx] = wppa_sanitize_custom_field( $data_arr[$custptrs[$idx]] );
							}
							else {
								$cust_data[$idx] = '';
							}
						}
						$fields['custom'] = serialize( $cust_data );

						// Prepare system fields
						foreach( array_keys( $systptrs ) as $idx ) {
							$fields[$systptrs[$idx]] = $data_arr[$idx];
						}

						// Do the update
						wppa_update_photo( $photo['id'], $fields );
						$processed ++;
					}
					wppa_log( 'dbg', 'Processed: ' . $data_arr[0] );
				}

				// This line could not be processed
				else {
					wppa_log( 'dbg', 'Could not find: ' . $data_arr[0] );

					$skipped++;
				}
			}

			// Time up?
			if ( wppa_is_time_up() ) {

				// Copy rest of file back to original
				while ( ! feof( $handle ) ) {
					$temp = fgets( $handle, 16*4096 );
					fputs( $write_handle, $temp );
				}
				wppa_unlink( $tempfile );
				wppa_import_quit( '23', false, true, false, $processed, $skipped );
			}
		}
	}

	// Done
	wppa_fclose( $handle );
	wppa_fclose( $write_handle );
	wppa_unlink( $tempfile );
	wppa_schedule_maintenance_proc( 'wppa_remake_index_albums' );
	wppa_schedule_maintenance_proc( 'wppa_remake_index_photos' );

	if ( 'del-after-c' ) {
		wppa_unlink( $file );
		wppa_import_quit( '0', true, false, false, $processed, $skipped );
	}

	wppa_import_quit();
}

// Import a dir
function wppa_import_a_dir( $file ) {
global $wppa_noquit;
global $wppa_nodelete;
global $wppa_done;
global $wppa_skip;

	$wppa_noquit = true;
	$wppa_nodelete = wppa_switch( 'keep_import_files' );

	$bret = wppa_import_dir_to_album( $file, '0' );

	$wppa_noquit = false;
	$wppa_nodelete = false;

	if ( wppa_is_time_up() ) {
		wppa_import_quit( '23', false, true, false, $wppa_done, $wppa_skip );
	}
	if ( ! $bret ) {
		wppa_import_quit( '28' );
	}
	clearstatcache();
	if ( ! wppa_is_dir( $file ) ) { // has it been deleted?
		$del = true;
	}
	else {
		$del = false;
	}
	wppa_import_quit( '0', $del, false, false, $wppa_done, $wppa_skip );
}

// Import a dir to album
function wppa_import_dir_to_album( $file, $parent ) {
global $photocount;
global $wppa_session;
global $wppa_supported_photo_extensions;
global $wppa_supported_video_extensions;
global $wppa_supported_audio_extensions;
global $wppa_supported_document_extensions;
global $wppa_done;
global $wppa_skip;

	// Init
	$name = basename( $file );

	// see if album exists
	if ( wppa_is_dir( $file ) ) {

		// Check parent
		if ( wppa_switch( 'import_parent_check' ) ) {

			$alb = wppa_get_album_id( $name, $parent );

			// If parent = 0 ( top-level album ) and album not found,
			// try a 'separate' album ( i.e. parent = -1 ) with this name
			if ( ! $alb && $parent == '0' ) {
				$alb = wppa_get_album_id( $name, '-1' );
			}
		}

		// All albums have unique names, do'nt worry about parent
		else {
			$alb = wppa_get_album_id( $name, false );
		}

		if ( ! $alb ) {	// Album must be created
			$uplim	= wppa_opt( 'upload_limit_count' ). '/' . wppa_opt( 'upload_limit_time' );
			$alb 	= wppa_create_album_entry( array ( 	'name' 		=> $name,
														'a_parent' 	=> $parent,
														'owner' 	=> wppa_switch( 'backend_album_public' ) ? '--- public ---' : wppa_get_user()
													 ) );
			if ( $alb === false ) {
				wppa_log( 'err', 'Could not create album.' );
				$wppa_skip++;
				wppa_log_skip( $name, 'no albumm' );
				wppa_import_quit( '16', false, false, false, $wppa_done, $wppa_skip );
			}
			else {
				$wppa_done++;
				wppa_set_last_album( $alb );
				wppa_invalidate_treecounts( $alb );
				wppa_create_pl_htaccess();
				if ( wppa_switch( 'newpag_create' ) && $parent <= '0' ) {

					// Create post object
					$my_post = array(
					  'post_title'    => $name,
					  'post_content'  => str_replace( 'w#album', $alb, wppa_opt( 'newpag_content' ) ),
					  'post_status'   => wppa_opt( 'newpag_status' ),
					  'post_type'	  => wppa_opt( 'newpag_type' )
					 );

					// Insert the post into the database
					$pagid = wp_insert_post( $my_post );
					if ( $pagid ) {
						wppa_update_album( $alb, ['cover_linkpage' => $pagid] );
					}
					else {
						wppa_log( 'err', 'Could not create page.' );
					}
				}
			}
		}

		// First escape special regexp chars
		$mediafiles = wppa_glob( $file . '/*', WPPA_ONLYFILES );

		// First do the mediafiles not being photos because the photos can be posterfiles and they need to be added after the video/audoio/document
		if ( $mediafiles ) foreach ( $mediafiles as $mediafile ) {

			$filename = basename( $mediafile );
			$may_delete = false;

			// Existing already?
			if ( wppa_is_file_duplicate_photo( $filename, $alb ) ) {
				$may_delete = true;
			}

			// New media item
			else {

				$ext = strtolower( wppa_get_ext( $mediafile ) );
				if ( in_array( $ext, $wppa_supported_photo_extensions ) ) {
					// Do nothing
				}
				elseif ( in_array( $ext, $wppa_supported_video_extensions ) ) {
					wppa_import_a_video( $mediafile, $alb );
					$may_delete = true;
				}
				elseif ( in_array( $ext, $wppa_supported_audio_extensions ) ) {
					wppa_import_a_audio( $mediafile, $alb );
					$may_delete = true;
				}
				elseif ( in_array( $ext, $wppa_supported_document_extensions ) ) {
					wppa_import_a_document( $mediafile, $alb );
					$may_delete = true;
				}
				else {
					wppa_log( 'err', 'Not supported during dir to album import: ' . $mediafile );
				}
			}

			if ( $may_delete && ( wppa_get( 'del-dir-cont' ) || ! wppa_switch( 'keep_import_files' ) ) ) {
				wppa_unlink( $mediafile );
			}

			if ( wppa_is_time_up() ) {
				wppa_import_quit( '23', false, true, false, $wppa_done, $wppa_skip );
			}
		}

		// Now do the photos because the photos can be posterfiles and they need to be added after the video/audoio/document
		if ( $mediafiles ) foreach ( $mediafiles as $mediafile ) {

			$filename = basename( $mediafile );
			$may_delete = false;

			// Existing already?
			if ( wppa_is_file_duplicate_photo( $filename, $alb ) ) {
				$may_delete = true;
			}

			// New photo
			else {

				$ext = wppa_get_ext( $mediafile );
				if ( in_array( $ext, $wppa_supported_photo_extensions ) ) {
					$size = getimagesize( $mediafile );
					$w = $size[0];
					$h = $size[1];
					$mf = str_replace( WPPA_DEPOT_PATH, '...', $mediafile );
					if ( wppa_check_memory_limit( false, $w, $h ) ) {
						$tl = wppa_time_left();
						wppa_log( 'obs', "Importing $mf time left $tl" );
						wppa_import_a_photo( $mediafile, $alb );
						$may_delete = true;
					}
					else {
						wppa_log( 'war', "Importing $mf is too large to handle: $w wide, $h high" );
						$may_delete = true;
					}
				}

				else {
					wppa_log( 'err', 'Not supported during dir to album import: ' . $mediafile );
				}
			}

			if ( $may_delete && ( wppa_get( 'del-dir-cont' ) || ! wppa_switch( 'keep_import_files' ) ) ) {
				wppa_unlink( $mediafile );
			}

			if ( wppa_is_time_up() ) {
				wppa_import_quit( '23', false, true, false, $wppa_done, $wppa_skip );
			}
		}

		// Now go deeper, process the subdirs
		$subdirs = wppa_glob( $file . '/*', WPPA_ONLYDIRS );
		if ( $subdirs ) foreach ( $subdirs as $subdir ) {
			if ( ! wppa_import_dir_to_album( $subdir, $alb ) ) {
				return false;	// Time out or error
			}
		}

		// Remove empty dirs if requested
		if ( wppa_get( 'del-dir' ) ) {
			wppa_rmdir( $file, true );
		}
	}
	else {
		return false;
	}
	return true;
}

// RealMedia album
function wppa_import_a_rm_album( $name ) {
global $wpdb;
global $wppa_done;
global $wppa_skip;

	$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_realmedialibrary WHERE name = %s LIMIT 1", $name );
	$rm_album = $wpdb->get_row( $query, ARRAY_A );
	if ( ! $rm_album ) {
		return; // It is not an rm album
	}

	// Get the rml album id
	$rm_album_id 	= $rm_album['id'];
	$rm_parent 		= $rm_album['parent'];
	$name 			= $rm_album['name'];

	// Already created?
	if ( $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_albums WHERE rml_id = $rm_album_id" ) ) {
		wppa_import_quit( '9' );
	}

	// Not yet, create it
	if ( $rm_parent > '0' ) {
		$parent = $wpdb->get_var( "SELECT id FROM $wpdb->wppa_albums WHERE rml_id = $rm_parent" );
		if ( ! $parent ) {
			wppa_import_quit( '29' ); // Missing parent album
		}
	}
	else {
		$parent = '0';
	}

	$iret = wppa_create_album_entry( ['name' 		=> $name,
									  'a_parent' 	=> $parent,
									  'rml_id' 		=> $rm_album_id,
									  'owner' 		=> wppa_switch( 'backend_album_public' ) ? '--- public ---' : wppa_get_user()
									  ] );
	if ( $iret ) {

		// Make sure album source dir exists
		$albdir = wppa_get_source_album_dir( $iret );
		if ( ! wppa_is_dir( $albdir ) ) {
			@ wppa_mktree( $albdir );
		}
	}

	wppa_import_quit();
}

// Import a FealMedia item
function wppa_import_a_rm_item( $name ) {
global $wpdb;
global $wppa_done;
global $wppa_skip;

	// Make sure source dir exists
	$sourcedir = wppa_get_source_dir();
	if ( ! wppa_is_dir( $sourcedir ) ) {
		@ wppa_mktree( $sourcedir );
		if ( ! wppa_is_dir( $sourcedir ) ) {
			wppa_import_quit( '31' );
		}
	}

	$rml_posts = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_realmedialibrary_posts", ARRAY_A );
	$done = true;
	foreach( $rml_posts as $rml_post ) {

		$post_id = $rml_post['attachment'];
		$wp_post = $wpdb->get_row( "SELECT * FROM $wpdb->posts WHERE ID = $post_id", ARRAY_A );

		// Post found?
		if ( ! $wp_post ) {
			wppa_import_quit( '32' );
		}

		$item_name 		= $wp_post['post_title'];
		$url 			= $wp_post['guid'];
		$path 			= str_replace( WPPA_CONTENT_URL, WPPA_CONTENT_PATH, $url );
		$file 			= basename( $path );

		// Found ?
		if ( $item_name == $name ) {

			// Already converted?
			$id = $wpdb->get_var( "SELECT id FROM $wpdb->wppa_photos WHERE rml_id = $post_id LIMIT 1" );

			if ( $id ) {
				$wppa_skip++;
				wppa_log_skip( $file, 'already converted' );
				wppa_import_quit( '33' );
			}

			// Is a new one
			$mime 			= $wp_post['post_mime_type'];
			$wp_post_id 	= $wp_post['ID'];
			$rml_album 		= $rml_post['fid'];
			$album 			= $wpdb->get_var( "SELECT id FROM $wpdb->wppa_albums WHERE rml_id = $rml_album" );
			$desc 			= $wp_post['post_content'];
			$ext 			= strtolower( wppa_get_ext( $file ) );

			if ( $album ) {

				$id = '';

				$entry_found = $wpdb->get_var( "SELECT id FROM $wpdb->wppa_photos WHERE rml_id = $post_id AND album = $album" );

				if ( ! $entry_found ) {

					// Dispatch on filetype
					switch ( $ext ) {

						case 'pdf':

							$id = wppa_create_photo_entry( array( 	'album' 	=> $album,
																	'ext' 		=> 'jpg',
																	'filename' 	=> wppa_strip_ext( $file ) . '.pdf',
																	'rml_id' 	=> $post_id,
																	'name' 		=> $name,
																) );

							// Success
							if ( $id ) {

								$wppa_done++;

								// Copy source file
								wppa_copy( $path, wppa_get_source_album_dir( $album ) . '/' . wppa_strip_ext( $file ) . '.pdf' );

								// Make the photo files will creatre poster .jpg if imagick is active
								wppa_make_the_photo_files( wppa_get_source_album_dir( $album ) . '/' . wppa_strip_ext( $file ) . '.pdf', $id, 'pdf' );
							}

							// Failed
							else {

								$wppa_skip++;
								wppa_log_skip( $file, 'create photo entry failed' );
								wppa_import_quit( '4' );
							}
							break;

						case 'jpg':
						case 'jpeg':
						case 'png':
						case 'gif':

							$id = wppa_create_photo_entry( array( 	'album' 	=> $album,
																	'ext' 		=> $ext,
																	'filename' 	=> wppa_strip_ext( $file ) . '.' . $ext,
																	'rml_id' 	=> $post_id,
																	'name' 		=> $name,
																) );

							// Success
							if ( $id ) {

								$wppa_done++;

								// Copy source file
								wppa_copy( $path, wppa_get_source_album_dir( $album ) . '/' . wppa_strip_ext( $file ) . '.' . $ext );

								// Make the photo files
								wppa_make_the_photo_files( wppa_get_source_album_dir( $album ) . '/' . wppa_strip_ext( $file ) . '.' . $ext, $id, $ext );
							}

							// Failed
							else {
								$wppa_skip++;
								wppa_log_skip( $file, 'create phot entry failed' );
								wppa_import_quit( '4' );
							}
							break;

						case 'mp4':
						case 'ogv':
						case 'webm':
						case 'mp3':
						case 'wav':
						case 'ogg':

							$id = wppa_create_photo_entry( array( 	'album' 	=> $album,
																	'ext' 		=> 'xxx',
																	'filename' 	=> wppa_strip_ext( $file ) . '.' . $ext,
																	'rml_id' 	=> $post_id,
																	'name' 		=> $name,
																) );

							// Success
							if ( $id ) {

								$wppa_done++;

								// Add file
								$newpath = wppa_strip_ext( wppa_get_photo_path( $id, false ) ) . '.' . $ext;
								wppa_copy( $path, $newpath );

								// Make sure ext is set to xxx after adding audio or video
								wppa_update_photo( $id, ['filename'	=> wppa_strip_ext( $file ) . '.xxx', 'ext' => 'xxx'] );

							}

							// Failed
							else {
								$wppa_skip++;
								wppa_log_skip( $file, 'create photo entry failed' );
								wppa_import_quit( '4' );
							}
							break;

						default:
							wppa_import_quit( '30' );
					}
				}

				if ( $id ) {
					$filename = wppa_get_photo_item( $id, 'filename' );
					wppa_set_default_name( $id, $filename );
					wppa_invalidate_treecounts( $album );
					wppa_import_quit();
				}
			}

			// Album not found (not converted yet)
			else {
				$wppa_skip++;
				wppa_log_skip( $file, 'album not converted yet' );
				wppa_import_quit( '1' );
			}
		}
	}
	$wppa_skip++;
	wppa_log_skip( $file, 'not found' );
	wppa_import_quit( '35' );
}