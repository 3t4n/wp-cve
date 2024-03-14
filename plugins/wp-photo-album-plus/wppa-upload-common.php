<?php
/* wppa-upload-common.php
* Package: wp-photo-album-plus
*
* Contains common upload functions
* Version: 8.6.03.001
*
*/

// Upload a single item; an element form $_FILES
// @1: Array( 'name'     => the original filename
// 			  'tmp_name' => the path to the just uploaded file or a file somewhere else in the filesystem
// 			  'error' 	 => error code )
// @2: Text: 'upload', 'front', 'import'
function wppa_upload_one_item( $file, $album, $from = '' ) {
global $wpdb;
global $wppa_supported_photo_extensions;
global $wppa_supported_video_extensions;
global $wppa_supported_audio_extensions;
global $wppa_supported_document_extensions;

	// Check arg
	if ( ! is_array( $file ) ) {
		wppa_upload_common_error( __( 'Unexpected internal error in wppa_upload_one_item()', 'wp-photo-album-plus' ), $from );
		return false;
	}

	// Check on upload error
	if ( $file['error'] ) {
		wppa_upload_common_error( __( 'Upload error', 'wp-photo-album-plus' ) . ': ' . $file['error'], $from );
		return false;
	}

	// Save some data and sanitize some data
	wppa( 'unsanitized_filename', $file['name'] );
	$file['name'] = wppa_down_ext( $file['name'] );
	$file['name'] = wppa_sanitize_album_photo_name( $file['name'] );

	// Test if we try to upload a supported filetype
	$supp = wppa_get_supported_extensions( $from );

	$the_type = wppa_get_ext( $file['name'] );
	if ( ! in_array( $the_type, $supp ) ) {
		wppa_upload_common_error( __( 'Invalid file-extension found during upload. Supported filetypes are', 'wp-photo-album-plus' ) . ': ' . implode( ', ', $supp ), $from );
		return false;;
	}

	// See if this filename (except extension) already exists in this album
	$ext_type = '';
	$ext_id = wppa_is_file_alias( $file['name'], $album );

	if ( $ext_id ) {
		$ext_ext = wppa_get_ext( wppa_get_photo_item( $ext_id, 'filename' ) );
		if ( wppa_is_video( $ext_id ) ) {
			$ext_type = 'video';
		}
		elseif ( wppa_has_audio( $ext_id ) ) {
			$ext_type = 'audio';
		}
		elseif ( wppa_is_pdf( $ext_id ) ) {
			$ext_type = 'document';
		}
		elseif ( in_array( $ext_ext, array( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
			$ext_type = 'image';
		}
		else {
			$ext_type = 'unknown';
		}
	}

	if ( in_array( $the_type, $wppa_supported_video_extensions ) ) {
		$new_type = 'video';
	}
	elseif ( in_array( $the_type, $wppa_supported_audio_extensions ) ) {
		$new_type = 'audio';
	}
	elseif ( in_array( $the_type, $wppa_supported_document_extensions ) ) {
		$new_type = 'document';
	}
	elseif ( in_array( $the_type, array( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
		$new_type = 'image';
	}
	else {
		$new_type = 'unknown';
	}

	// Cheack against incompatible combinations
	$cerr = false;
	if ( $ext_type == 'video' && $new_type == 'audio' || $ext_type == 'audio' && $new_type == 'video' ) {
		$cerr = true;
	}
	elseif ( $ext_type == 'video' && $new_type == 'document' || $ext_type == 'document' && $new_type == 'video' ) {
		$cerr = true;
	}
	elseif ( $ext_type == 'audio' && $new_type == 'document' || $ext_type == 'document' && $new_type == 'audio' ) {
		$cerr = true;
	}
	elseif ( $ext_type == 'audio' && $new_type == 'document' || $ext_type == 'document' && $new_type == 'audio' ) {
		$cerr = true;
	}
	elseif ( $ext_type == 'video' && $new_type == 'document' || $ext_type == 'document' && $new_type == 'video' ) {
		$cerr = true;
	}
	if ( $cerr ) {
		wppa_upload_common_error( sprintf( __( 'You can not combine %s with %s files', 'wp-photo-album-plus' ), $ext_type, $new_type ), $from );
		return false;;
	}

	// Check against duplicate files
	if ( $new_type == 'image' || $new_type == 'document' ) {
		if ( $ext_type == $new_type ) {
			if ( wppa_switch( 'void_dups' ) ) {
				wppa_upload_common_ok( sprintf( __( 'Item %s already exists in this album and will be overwritten', 'wp-photo-album-plus' ), $file['name'] ), $from );
			}
			else {

				// Forget its a dup
				$ext_type 	= '';
				$ext_id 	= false;
			}
		}
	}

	// Dispatch on filetype
	switch ( $the_type ) {

		case 'pdf':

			// If image already exists, just add the pdf and set the file extension right
			if ( $ext_id ) {
				wppa_copy( $file['tmp_name'], wppa_get_source_album_dir( $album ) . '/' . $file['name'] );
				wppa_update_photo( $ext_id, ['filename' => wppa_strip_ext( $file['name'] ) . '.pdf'] );
				wppa_upload_post_process( $the_type, $album, $ext_id, $from );
				return true;;
			}

			// Assume poster image will be creted  by imagick or uploaded, so set ext to jpg. wppa_is_pdf looks at filename extension.
			if ( file_exists( $file['tmp_name'] ) ) {
				$id = wppa_create_photo_entry( array( 	'album' 	=> $album,
														'ext' 		=> 'jpg',
														'name' 		=> $file['name'],
														'filename' 	=> wppa_strip_ext( $file['name'] ) . '.pdf',
														'sname' 	=> wppa_name_slug( $file['name'] ),
													) );

				wppa_copy( $file['tmp_name'], wppa_get_source_album_dir( $album ) . '/' . wppa_strip_ext( $file['name'] ) . '.pdf' );

				// Make the photo files will creatre poster .jpg if imagick is active
				wppa_make_the_photo_files( wppa_get_source_album_dir( $album ) . '/' . wppa_strip_ext( $file['name'] ) . '.pdf', $id, 'pdf' );
			}

			wppa_upload_post_process( $the_type, $album, $id, $from );
			return true;; // Yes we uploaded a file
			break;

		case 'jpg':
		case 'jpeg':
		case 'png':
		case 'gif':
		case 'webp':

			// Process posterfile or update existing image file (dups not allowed)
			if ( $ext_type == 'video' || $ext_type == 'audio' || $ext_type == 'document' || $ext_type == $new_type ) {
				wppa_log( 'dbg', 'Updating ' . $ext_id . ', ' . $file['name'] . ' ' . $ext_type . ' with ' . $the_type );
				wppa_move_uploaded_file( $file['tmp_name'], wppa_get_source_album_dir( $album ) . '/' . $file['name'] );
				if ( $ext_type == 'document' ) {
					wppa_update_photo( $ext_id, ['ext' => $the_type] );
				}
				wppa_cache_photo( 'invalidate', $ext_id );
				wppa_make_the_photo_files( wppa_get_source_album_dir( $album ) . '/' . $file['name'], $ext_id, wppa_get_ext( $file['name'] ) );
				wppa_upload_post_process( $the_type, $album, $ext_id, $from );
				return true;; // Yes we uploaded a file
			}

			// Just a photo
			$id = wppa_insert_photo( $file['tmp_name'], $album, $file['name'] );
			if ( $id ) {
				wppa_upload_post_process( $the_type, $album, $id, $from );
				if ( wppa_switch( 'beuploadnotify' ) ) {
					wppa_schedule_mailinglist( 'feuploadnotify', $album, $id, 0, '', 0, 900 );
				}

				return true;; // Yes we uploaded a file
			}
			else {
				wppa_upload_common_error( __( 'Error inserting photo' , 'wp-photo-album-plus' ) . ' ' . wppa_sanitize_file_name( basename( $file['name'] ) ) . '.', $from );
				return false;; // No we did not uploaded a file
			}
			break;

		case 'mp4':
		case 'ogv':
		case 'webm':
		case 'mp3':
		case 'wav':
		case 'ogg':

			// Add new entry
			if ( ! $ext_id ) {
				$id = wppa_create_photo_entry( array( 'album' => $album, 'filename' => $file['name'], 'ext' => 'xxx', 'name' => wppa_strip_ext( $file['name'] ) ) );
			}
			else {
				$id = $ext_id;
			}

			// Add file
			$newpath = wppa_strip_ext( wppa_get_photo_path( $id, false ) ) . '.' . $the_type;
			$fs = filesize( $file['tmp_name'] );
			if ( $fs > 1024*1024*64 ) {	// copy fails for files > 64 Mb

				// Remove old version if already exists
				if ( wppa_is_file( $newpath ) ) {
					wppa_unlink( $newpath );
				}
				wppa_rename( $file['tmp_name'], $newpath );
			}
			else {
				wppa_copy( $file['tmp_name'], $newpath );
			}

			// Make sure ext is set to xxx after adding audio or video
			wppa_update_photo( $id, ['filename' => wppa_strip_ext( $file['name'] ) . '.xxx', 'ext' => 'xxx'] );
			wppa_upload_post_process( $the_type, $album, $id, $from );
			return true; // Yes we uploaded a file
			break;

		case 'zip':

			$zipfile = WPPA_DEPOT_PATH . '/' . wppa_sanitize_file_name( $file['name'] );
			wppa_copy( $file['tmp_name'], $zipfile );

			$zip = new ZipArchive;

			if ( $zip->open( $zipfile ) === true ) {

				$done = '0';
				$skip = '0';
				for ( $i = 0; $i < $zip->numFiles; $i++ ) {

					$stat = $zip->statIndex( $i );
					/* statIndex returns:
					Array
					(
						[name] => foobar/baz
						[index] => 3
						[crc] => 499465816
						[size] => 27
						[mtime] => 1123164748
						[comp_size] => 24
						[comp_method] => 8
					)
					*/
					$file_ext = @ strtolower( end( explode( '.', $stat['name'] ) ) );

					if ( in_array( $file_ext, $supp ) ) {
						$zip->extractTo( WPPA_UPLOAD_PATH . '/temp/', $stat['name'] );

						// Find out if the file is ment for a sub(sub)album
						$p = $album;
						if ( strpos( $stat['name'], '/' ) !== false ) {
							$fullname = $stat['name'];
							$names = explode( '/', $fullname );
							$i = 0;

							while ( $i < count( $names ) -1 ) {

								// Find album with parent is current and name is sub album name
								$a = $wpdb->get_var( $wpdb->prepare( "SELECT id
																	  FROM $wpdb->wppa_albums
																	  WHERE name = %s
																	  AND a_parent = %d
																	  LIMIT 1", $names[$i], $p ) );

								// If not found, create it
								if ( ! $a ) {
									$a = wppa_create_album_entry( [ 'name' 		=> $names[$i],
																	'a_parent' 	=> $p,
																	'owner' 	=> wppa_switch( 'backend_album_public' ) ? '--- public ---' : wppa_get_user()
																	] );
								}

								$p = $a;
								$i++;
							}
						}

						// Recursively call wppa_upload_one_item()
						$zfile = array( 'tmp_name' 	=> WPPA_UPLOAD_PATH . '/temp/' . $stat['name'],
										'name' 		=> sanitize_file_name( basename( $stat['name'] ) ),
										'error' 	=> 0,
										);
						$iret = wppa_upload_one_item( $zfile, $p, $from );
						if ( $iret ) {
							$done++;
						}
						else {
							$skip++;
						}
						wppa_unlink( WPPA_UPLOAD_PATH . '/temp/' . $stat['name'] );
					}

					// Assuming that entries without a file extension are directries. No warning on directory.
					elseif ( strpos( $stat['name'], '.' ) !== false && strlen( $file_ext ) < 5 ) {
						wppa_upload_common_error( sprintf( __( 'File %s is of an unsupported filetype and has been ignored during extraction.', 'wp-photo-album-plus' ), wppa_sanitize_file_name( $stat['name'] ) ), $from );
						$skip++;
					}
				}

				$zip->close();
				wppa_upload_common_ok( sprintf( __( 'Zipfile %s processed. %s files extracted, %s files skipped.', 'wp-photo-album-plus' ), basename( $zipfile ), $done, $skip ), $from );

				wppa_unlink( $zipfile );
				return true;; // Yes we uploaded a file
			}

			// Zipfile can not be opened
			else {
				wppa_upload_common_error( __( 'Failed to extract', 'wp-photo-album-plus' ).' '.$zipfile, $from );
				wppa_unlink( $zipfile );
				return false;;
			}
			break;

		default:
			wppa_upload_common_error( __( 'Unsupported filetype encountered', 'wp-photo-album-plus' ) . ': ' . $the_type, $from );
			return false;; // No we did not uploaded a file
	}
}

function wppa_upload_common_error( $error, $from = '' ) {

	switch( $from ) {
		case 'upload':
			wppa_error_message( $error );
			break;
		case 'import':
			wppa_log( 'err', $error );
			break;
		default:
			wppa_log( 'err', 'Unimplemented from: ' . $from . ' in wppa_upload_common_error()' );
			break;
	}
}

function wppa_upload_common_ok( $error, $from = '' ) {

	switch( $from ) {
		case 'upload':
			wppa_ok_message( $error );
			break;
		case 'import':
			break;
		default:
			wppa_log( 'err', 'Unimplemented from: ' . $from . ' in wppa_upload_common_ok()' );
			break;
	}
}

function wppa_get_supported_extensions( $from = '' ) {
global $wppa_supported_photo_extensions;
global $wppa_supported_video_extensions;
global $wppa_supported_audio_extensions;
global $wppa_supported_document_extensions;
global $wppa_supported_extensions;

	if ( is_array( $wppa_supported_extensions ) ) {
		return $wppa_supported_extensions;
	}

	$supp = $wppa_supported_photo_extensions;
	if ( wppa_switch( 'enable_video' ) ) {
		$supp = array_merge( $supp, $wppa_supported_video_extensions );
	}
	if ( wppa_switch( 'enable_audio' ) ) {
		$supp = array_merge( $supp, $wppa_supported_audio_extensions );
	}
	if ( wppa_switch( 'enable_pdf' ) ) {
		$supp = array_merge( $supp, $wppa_supported_document_extensions );
	}
	if ( $from == 'import' ) {
		if ( class_exists( 'ZipArchive' ) && ( wppa_user_is_admin() || ! wppa_switch( 'upload_one_only' ) ) ) {
			$supp[] = 'zip';
		}
		$supp[] = 'amf';
		$supp[] = 'pmf';
		$supp[] = 'csv';
	}

	// For backward compatibility, add uppercase extensions
	$upcase = array();
	foreach( $supp as $ext ) {
		$upcase[] = strtoupper( $ext );
	}
	$supp = array_merge( $supp, $upcase );
	return $supp;
}

function wppa_upload_post_process( $the_type, $album, $id, $from ) {

	wppa_set_default_name( $id, wppa( 'unsanitized_filename' ) );
	wppa_invalidate_treecounts( $album );
	wppa_cache_photo( 'invalidate', $id );
	wppa_update_photo( $id, ['sname' => wppa_name_slug( wppa_get_photo_item( $id, 'name' ) )] );
	wppa( 'is_pdf', false );

	if ( $from == 'import' ) {
		if ( wppa( 'ajax' ) ) {
			wppa( 'ajax_import_files_done', true );
		}
	}
	wppa_fix_video_metadata( $id, $from );
	wppa_fix_audio_metadata( $id, $from );
}

// To check on possible duplicate
// Returns the id if the given file is a photo and this photo is already in the given album as a photo
function wppa_is_file_duplicate_photo( $file, $album ) {
global $wppa_supported_photo_extensions;
global $wpdb;

	if ( ! $file || ! $album ) return false;

	// Identical?
	if ( $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE filename = %s AND album = %d", basename( $file ), $album ) ) ) {
		return true;
	}

	// Alias?
	$ext_id = wppa_is_file_alias( $file, $album );

	if ( ! $ext_id ) {
		return false;
	}

	$ext_ext = wppa_get_photo_item( $ext_id, 'ext' );
	$new_ext = wppa_get_ext( basename( $file ) );

	if ( in_array( $ext_ext, $wppa_supported_photo_extensions ) &&
		 in_array( $new_ext, $wppa_supported_photo_extensions ) ) {
		return $ext_id;
	}
	else {
		return false;
	}
}

// To check for a possible poster or second filetype of existing item
// Returns the id if a given file with any extension not being a photo already exists in the given album
function wppa_is_file_alias( $file, $album ) {
global $wpdb;
global $wppa_supported_photo_extensions;

	if ( ! $file ) return false;	// Copy/move very old photo, before filnametracking
	$id = $wpdb->get_var( $wpdb->prepare( "SELECT id
										   FROM $wpdb->wppa_photos
										   WHERE filename LIKE %s
										   AND album = %d",
										   $wpdb->esc_like( wppa_strip_ext( basename( $file ) ) ) . '.%',
										   $album
										) );
	/* Start debug
	$aliasses = $wpdb->get_results( $wpdb->prepare( "SELECT *
												    FROM $wpdb->wppa_photos
												    WHERE filename LIKE %s
												    AND album = %d",
												    $wpdb->esc_like( wppa_strip_ext( basename( $file ) ) ) . '.%',
												    $album
												), ARRAY_A );
	foreach( $aliasses as $a ) {
		wppa_log( 'obs', 'Alias is '.$a['id'].' in album '.$a['album'].' filename '.$a['filename']);
	}
	if ( $id ) wppa_log( 'obs', 'Is file alias returns '.$id);
	/* End debug */
	return $id;
	/*
	if ( ! $id ) return false;
	$ext = wppa_get_photo_item( $id, 'ext' );
	if ( in_array( $ext, $wppa_supported_photo_extensions ) ) return false;
	return $id;
	*/
}

// Fix for missing rotate in video metadata. see https://core.trac.wordpress.org/ticket/56217#comment:10
function wppa_fix_wp_read_video_metadata_function( $metadata, $file, $file_format, $data ) {

	// Finally fixed in wp?
	if ( isset( $metadata['rotate'] ) ) {
		return $metadata;
	}

	// Fix if present
	if ( isset( $data['video']['rotate'] ) ) {
		$metadata['rotate'] = $data['video']['rotate'];
		wppa_log( 'dbg', 'Rotate added by wppa: ' . $metadata['rotate'] );
	}
	return $metadata;
}
add_filter( 'wp_read_video_metadata', 'wppa_fix_wp_read_video_metadata_function', 10, 4 );

// Try to find the framesize of a video, and update the items db entry if found.
// Return true on success, false on failure
function wppa_fix_video_metadata( $id, $where ) {

	// Is it a video?
	$files = wppa_is_video( $id );
	if ( ! $files ) {
		return false;
	}
	if ( ! in_array( 'mp4', $files ) ) {
		return false;
	}

	// Does file exist?
	$file = wppa_strip_ext( wppa_get_photo_path( $id ) ) . '.mp4';
	if ( ! wppa_is_file( $file ) ) {
		wppa_log( 'dbg', 'wppa_fix_video_metadata quit because ' . $file . ' does not exists' );
	}

	// Get the info
	$mp4info = wp_read_video_metadata( $file );

	// Make sure its a video
	if ( $mp4info['fileformat'] != 'mp4' ) {
		wppa_log( 'dbg', 'No mp4 fileformat in ' . $file . ' ' . $where );
		return false;
	}

	// Find sizes
	$videox = isset( $mp4info['width'] ) ? $mp4info['width'] : '0';
	$videoy = isset( $mp4info['height'] ) ? $mp4info['height'] : '0';

	// Rotated?
	if ( isset( $mp4info['rotate'] ) ) {
		$rot = $mp4info['rotate'];
		if ( $rot == 90 || $rot == 270 ) {
			$t = $videox;
			$videox = $videoy;
			$videoy = $t;
		}
	}
	else {
		$rot = 0;
	}

	// Update item
	wppa_update_photo( $id, ['videox' => $videox, 'videoy' => $videoy] );

	// Duration available?
	$duration = isset( $mp4info['length'] ) ? $mp4info['length'] : false;
	if ( $duration ) {
		wppa_update_photo( $id, ['duration' => $duration] );
	}

	// Date/time original?
	$datetime = isset( $mp4info['created_timestamp'] ) ? $mp4info['created_timestamp'] : false;
	if ( $datetime ) {
		$exifdtm = date( 'Y:m:d H:i:s', $datetime );
		wppa_update_photo( $id, ['exifdtm' => $exifdtm] );
	}
	else $exifdtm = '';

	wppa_log( 'dbg', 'MP4 Metadata found (' . $videox . 'x' . $videoy . '), ' . $duration . ', ' . $exifdtm . ' in ' . $file . ' ' . $where . ' ' . $rot );

	return true;
}

// Find duration of audio
function wppa_fix_audio_metadata( $id, $where ) {

	$file = wppa_strip_ext( wppa_get_photo_path( $id, false ) ) . '.mp3';
	if ( wppa_is_file( $file ) ) {
		if ( function_exists( 'wp_read_audio_metadata' ) ) {
			$mp3info = wp_read_audio_metadata( $file );
			if ( $mp3info ) {
				$duration = $mp3info['length'];
				if ( $duration ) {
					wppa_update_photo( $id, ['duration' => $duration] );
				}
			}
		}
	}
}
