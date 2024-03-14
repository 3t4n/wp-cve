<?php
/* wppa-admin-functions.php
* Package: wp-photo-album-plus
*
* gp admin functions
* Version: 8.6.03.001
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

function wppa_backup_settings() {
global $wppa_opt;


	// Make contents
	$contents = '';
	foreach( array_keys( $wppa_opt ) as $key ) {
		$value = str_replace( "\n", "\\n", $wppa_opt[$key] );
		$contents .= $key.":".$value."\n";
	}

	// Find filename
	$filename = sanitize_file_name( basename( wppa_opt( 'backup_filename' ) ) );
	$ext = wppa_get_ext( $filename );
	if ( ! in_array( $ext, array( 'bak', 'skin' ) ) ) {
		$filename = wppa_strip_ext( $filename ) . '.bak';
	}
	if ( $filename == '.bak' ) {
		$filename = 'settings.bak';
	}

	// Forget filename for the next time or another user
	wppa_update_option( 'wppa_backup_filename', '' );
	wppa_initialize_runtime();

	// Save to file
	wppa_ok_message( __( 'Backing up to', 'wp-photo-album-plus' ) . ': ' . str_replace( WPPA_ABSPATH, '.../', WPPA_DEPOT_PATH ) . '/' . $filename );
	$bret = wppa_put_contents( WPPA_DEPOT_PATH . '/' . $filename, $contents );

	// Report
	if ( $bret ) {
		wppa_ok_message( __( 'Settings successfully backed up' , 'wp-photo-album-plus' ) );
	}
	else {
		wppa_error_message( __( 'Unable to backup settings' , 'wp-photo-album-plus' ) );
	}

	return $bret;
}

function wppa_restore_settings( $fname, $type = '' ) {

	if ( $type == 'skin' ) {
		$void_these = array(
							'wppa_revision',
							'wppa_thumb_linkpage',
							'wppa_potd_linkpage',
							'wppa_slideonly_widget_linkpage',
							'wppa_topten_widget_linkpage',
							'wppa_lasten_widget_linkpage',
							'wppa_coverimg_linkpage',
							'wppa_search_linkpage',
							'wppa_album_widget_linkpage',
							'wppa_thumbnail_widget_linkpage',
							'wppa_comment_widget_linkpage',
							'wppa_featen_widget_linkpage',
							'wppa_sphoto_linkpage',
							'wppa_mphoto_linkpage',
							'wppa_xphoto_linkpage',
							'wppa_slideshow_linkpage',
							'wppa_tagcloud_linkpage',
							'wppa_multitag_linkpage',
							'wppa_super_view_linkpage',
							'wppa_upldr_widget_linkpage',
							'wppa_bestof_widget_linkpage',
							'wppa_album_navigator_widget_linkpage',
							'wppa_supersearch_linkpage',
							'wppa_widget_sm_linkpage',
							'wppa_permalink_structure',
							'wppa_rating_max',
							'wppa_file_system',
							'wppa_source_dir',
							 );
	}
	else {
		$void_these = array(
							'wppa_revision',
							'wppa_rating_max',
							'wppa_file_system',
							 );
	}

	// Get file contents
	$buffers = wppa_get_contents_array( $fname );

	// Restore
	if ( $buffers ) {
		foreach( $buffers as $buffer ) {
			$buflen = strlen( $buffer );
			if ( $buflen > '0' && substr( $buffer, 0, 1 ) != '/' ) {	// lines that start with '/' are comment
				$cpos = strpos( $buffer, ':' );
				$delta_l = $buflen - $cpos - 2;
				if ( $cpos && $delta_l >= 0 ) {
					$slug = substr( $buffer, 0, $cpos );
					$value = substr( $buffer, $cpos+1, $delta_l );
					$value = str_replace( '\n', "\n", $value );	// Replace substr '\n' by nl char value
					$value = stripslashes( $value );
					if ( ! in_array( $slug, $void_these ) ) wppa_update_option( $slug, $value );
				}
			}
		}
		wppa_initialize_runtime( true );
		return true;
	}
	else {
		wppa_error_message( __( 'Settings file not found' , 'wp-photo-album-plus' ) );
		return false;
	}
}

// Remake
function wppa_remake_files( $alb = '', $pid = '' ) {
global $wpdb;

	// Init
	$count = '0';

	// Find the album( s ) if any
	if ( ! $alb && ! $pid ) {
		$start_time = wppa_get_option( 'wppa_remake_start', '0' );
		$albums = $wpdb->get_results( "SELECT id FROM $wpdb->wppa_albums", ARRAY_A );
	}
	elseif ( $alb ) {
		$start_time = wppa_get_option( 'wppa_remake_start_album_'.$alb, '0' );
		$albums = array( array( 'id' => $alb ) );
	}
	else $albums = false;

	// Do it with albums
	if ( $albums ) foreach ( $albums as $album ) {
		$source_dir = wppa_get_source_album_dir( $album['id'] );
		if ( wppa_is_dir( $source_dir ) ) {
			$files = wppa_glob( $source_dir.'/*' );
			if ( $files ) foreach ( $files as $file ) {
				if ( ! wppa_is_dir( $file ) ) {
					$filename = basename( $file );
					$photos =
						$wpdb->get_results( $wpdb->prepare(
							"SELECT * FROM $wpdb->wppa_photos
							 WHERE filename = %s
							 OR filename = %s
							 OR ( filename = '' AND name = %s )",
							 $filename,
							 wppa_strip_ext( $filename ) . '.xxx',	// May be a multimedia iten
							 $filename ),
						ARRAY_A );

					if ( $photos ) foreach ( $photos as $photo ) {	// Photo exists
						$modified_time = $photo['modified'];
						if ( $modified_time < $start_time ) {
							wppa_update_single_photo( $file, $photo['id'], $filename );
							$count++;
						}
						if ( wppa_is_time_up( $count ) ) {
							return false;
						}
					}
					else {	// No photo yet
						if ( wppa_switch( 'remake_add' ) ) {
							wppa_insert_photo( $file, $album['id'], $filename );
							$count++;
						}
					}
					if ( wppa_is_time_up( $count ) ) {
						return false;
					}
				}
			}
		}
	}

	// Do it with a single photo
	elseif ( $pid ) {
		$photo = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos WHERE id = %s", $pid ), ARRAY_A );
		if ( $photo ) {
			$file = wppa_get_o1_source_path( $photo['id'] );
			if ( ! wppa_is_file( $file ) ) {
				$file = wppa_get_source_path( $photo['id'] );
			}
			if ( is_file( $file ) ) {
				$name = $photo['filename'];
				wppa_update_single_photo( $file, $pid, $name );
			}
			else return false;
		}
		else return false;
	}
	return true;
}

// display usefull message
function wppa_update_message( $msg, $fixed = false, $id = '' ) {

	if ( defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) ) return;
	wppa_echo( '<div class="notice notice-info is-dismissible" ><p>' . $msg . '</p></div>', 'post' );
}

// display error message
function wppa_error_message( $msg ) {

	if ( defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) ) return;
	wppa_echo( '<div class="notice notice-error is-dismissible"><p>' . $msg . '</p></div>', 'post' );
}

// display warning message
function wppa_warning_message( $msg ) {

	if ( defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) ) return;
	wppa_echo( '<div class="notice notice-warning is-dismissible"><p>' . $msg . '</p></div>', 'post' );
}

// display ok message
function wppa_ok_message( $msg ) {

	if ( defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) ) return;
	wppa_echo( '<div class="notice notice-success is-dismissible"><p>' . $msg . '</p></div>', 'post' );
}

function wppa_check_numeric( $value, $minval, $target, $maxval = '' ) {
	if ( $maxval == '' ) {
		if ( is_numeric( $value ) && $value >= $minval ) return true;
		wppa_error_message( __( 'Please supply a numeric value greater than or equal to' , 'wp-photo-album-plus' ) . ' ' . $minval . ' ' . __( 'for' , 'wp-photo-album-plus' ) . ' ' . $target );
	}
	else {
		if ( is_numeric( $value ) && $value >= $minval && $value <= $maxval ) return true;
		wppa_error_message( __( 'Please supply a numeric value greater than or equal to' , 'wp-photo-album-plus' ) . ' ' . $minval . ' ' . __( 'and less than or equal to' , 'wp-photo-album-plus' ) . ' ' . $maxval . ' ' . __( 'for' , 'wp-photo-album-plus' ) . ' ' . $target );
	}
	return false;
}

// check if albums 'exists'
function wppa_has_albums() {
	return wppa_have_access( '0' );
}


function wppa_copy_photo( $photoid, $albumto ) {
global $wpdb;

	$err = '1';
	// Check args
	if ( ! is_numeric( $photoid ) || ! is_numeric( $albumto ) ) return $err;

	$err = '2';
	// Find photo details
	$photo = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
											  WHERE id = %s", $photoid ), ARRAY_A );
	if ( ! $photo ) return $err;
	$albumfrom 	= $photo['album'];
	$album 		= $albumto;
	$ext 		= $photo['ext'];
	$name 		= $photo['name'];
	$porder		= $photo['p_order'];
	$desc 		= $photo['description'];
	$linkurl 	= $photo['linkurl'];
	$linktitle 	= $photo['linktitle'];
	$linktarget = $photo['linktarget'];
	$status 	= $photo['status'];
	$filename 	= $photo['filename'];
	$location	= $photo['location'];
	$oldimage 	= wppa_get_photo_path( strval( intval( $photo['id'] ) ) );
	$oldthumb 	= wppa_get_thumb_path( strval( intval( $photo['id'] ) ) );
	$tags 		= $photo['tags'];
	$exifdtm 	= $photo['exifdtm'];
	$custom 	= $photo['custom'];
	$stereo 	= $photo['stereo'];
	$panorama 	= $photo['panorama'];

	$err = '3';
	// Make new db table entry
	$owner = wppa_switch( 'copy_owner' ) ? $photo['owner'] : wppa_get_user();
	$time = wppa_switch( 'copy_timestamp' ) ? $photo['timestamp'] : time();
	$id = wppa_create_photo_entry( array( 	'album' 		=> $album,
											'ext' 			=> $ext,
											'name' 			=> $name,
											'p_order' 		=> $porder,
											'description' 	=> $desc,
											'linkurl' 		=> $linkurl,
											'linktitle' 	=> $linktitle,
											'linktarget' 	=> $linktarget,
											'timestamp' 	=> $time,
											'owner' 		=> $owner,
											'status' 		=> $status,
											'filename' 		=> $filename,
											'location' 		=> $location,
											'tags' 			=> $tags,
											'exifdtm' 		=> $exifdtm,
											'videox' 		=> $photo['videox'],
											'videoy' 		=> $photo['videoy'],
											'custom' 		=> wppa_switch( 'copy_custom' ) ? $photo['custom'] : '',
											'stereo' 		=> $photo['stereo'],
											'panorama' 		=> $photo['panorama'],
											'photox' 		=> 0, //$photo['photox'],
											'photoy' 		=> 0, //$photo['photoy'],
											'thumbx' 		=> 0, //$photo['thumbx'],
											'thumby' 		=> 0, //$photo['thumby'],
											'duration' 		=> $photo['duration'],

										 )
								 );
	if ( ! $id ) return $err;
	wppa_invalidate_treecounts( $album );
	wppa_index_update( 'photo', $id );

	$err = '4';
	// Find copied photo details
	$id = strval( intval( $id ) );
	if ( ! $id ) return $err;
	$image_id = $id;
	$newimage = wppa_strip_ext( wppa_get_photo_path( $image_id, false ) ) . '.' . wppa_get_ext( $oldimage );
	$newthumb = wppa_strip_ext( wppa_get_thumb_path( $image_id, false ) ) . '.' . wppa_get_ext( $oldthumb );

	$err = '5';
	// Do the filesystem copy
	if ( wppa_is_video( $photo['id'] ) ) {
		if ( ! wppa_copy_video_files( $photo['id'], $image_id ) ) return $err;
	}
	elseif ( wppa_has_audio( $photo['id'] ) ) {
		if ( ! wppa_copy_audio_files( $photo['id'], $image_id ) ) return $err;
	}

	$err = '6';
	// Copy photo or poster
	if ( wppa_is_file( $oldimage ) ) {
		if ( ! wppa_copy( $oldimage, $newimage ) ) return $err;
	}

	$err = '7';
	// Copy thumbnail
	if ( wppa_is_file( $oldthumb ) ) {
		if ( ! wppa_copy( $oldthumb, $newthumb ) ) return $err;
	}

	$err = '8';
	// Copy to cloudinary
	if ( wppa_opt( 'cdn_service' ) == 'cloudinary' || wppa_opt( 'cdn_service' ) == 'cloudinarymaintenance' ) {
		wppa_upload_to_cloudinary( $id );
	}

	$err = '9';
	// Copy source
	wppa_copy_source( $filename, $albumfrom, $albumto );

	$err = '10';
	// Copy Exif and iptc
	wppa_copy_exif( $photoid, $id );
	wppa_copy_iptc( $photoid, $id );

	// Bubble album timestamp
	if ( ! wppa_switch( 'copy_timestamp' ) ) wppa_update_album( $albumto );
	return false;	// No error
}
function wppa_copy_exif( $fromphoto, $tophoto ) {
global $wpdb;

	$exiflines = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_exif WHERE photo = %d", $fromphoto ), ARRAY_A );
	if ( $exiflines ) foreach ( $exiflines as $line ) {
		$bret = wppa_create_exif_entry( array( 'photo' => $tophoto, 'tag' => $line['tag'], 'description' => $line['description'], 'status' => $line['status'] ) );
	}
}
function wppa_copy_iptc( $fromphoto, $tophoto ) {
global $wpdb;

	$iptclines = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_iptc WHERE photo = %d", $fromphoto ), ARRAY_A );
	if ( $iptclines ) foreach ( $iptclines as $line ) {
		$bret = wppa_create_iptc_entry( array( 'photo' => $tophoto, 'tag' => $line['tag'], 'description' => $line['description'], 'status' => $line['status'] ) );
	}
}

function wppa_rotate( $id, $ang ) {
global $wpdb;

	// Check args
	$err = '1';
	if ( ! is_numeric( $id ) || ( ! in_array( $ang, array( 'rotright', 'rot180', 'rotleft', 'flip', 'flop' ) ) ) ) return $err;

	// Get the ext
	$err = '2';
	$ext = $wpdb->get_var( $wpdb->prepare( "SELECT ext FROM $wpdb->wppa_photos
											WHERE id = %d", $id ) );
	if ( ! $ext ) return $err;

	// Get the image
	$err = '3';
	$file = wppa_get_photo_path( $id );
	if ( ! is_file( $file ) ) return $err;

	// Get the imgdetails
	$err = '4';
	$img = getimagesize( $file );
	if ( ! $img ) return $err;

	// Get the image
	switch ( $img[2] ) {
		case 1:	// gif
			$err = '5';
			$source = wppa_imagecreatefromgif( $file );
			break;
		case 2: // jpg
			$err = '6';
			$source = wppa_imagecreatefromjpeg( $file );
			break;
		case 3: // png
			$err = '7';
			$source = wppa_imagecreatefrompng( $file );
			break;
		case 18: // webp
			$err = '8';
			$source = wppa_imagecreatefromwebp( $file );
			break;
		default: // unsupported mimetype
			$err = '10';
			$source = false;
	}
	if ( ! $source ) return $err;

	// Rotate the image
	$err = '11';
	switch( $ang ) {

		case 'rotright':
			$rotate = imagerotate( $source, -90, 0 );
			if ( ! $rotate ) {
				return $err;
			}
			break;
		case 'rot180':
			$rotate = imagerotate( $source, 180, 0 );
			if ( ! $rotate ) {
				return $err;
			}
			break;
		case 'rotleft':
			$rotate = imagerotate( $source, 90, 0 );
			if ( ! $rotate ) {
				return $err;
			}
			break;
		case 'flip':
			if ( ! imageflip( $source, IMG_FLIP_VERTICAL ) ) {
				return $err;
			}
			$rotate = $source;
			break;
		case 'flop':
			if ( ! imageflip( $source, IMG_FLIP_HORIZONTAL ) ) {
				return $err;
			}
			$rotate = $source;
			break;
		default:
			return $err;
	}

	// Save the image
	switch ( $img[2] ) {
		case 1:
			$err = '15';
			$bret = wppa_imagegif( $rotate, $file );
			break;
		case 2:
			$err = '16';
			$bret = wppa_imagejpeg( $rotate, $file );
			break;
		case 3:
			$err = '17';
			$bret = wppa_imagepng( $rotate, $file );
			break;
		case 18:
			$err = '18';
			$bret = wppa_imagewebp( $rotate, $file );
			break;
		default:
			$err = '20';
			$bret = false;
	}
	if ( ! $bret ) return $err;

	// Destroy the source
	@ imagedestroy( $source );

	// Destroy the result
	@ imagedestroy( $rotate );

	// Clear stored dimensions
	wppa_update_photo( $id, ['thumbx' => '0', 'thumby' => '0', 'photox' => '0', 'photoy' => '0'] );
	$err = '30';

	// Recreate the thumbnail, do NOT use source: source can not be rotated
	$bret = wppa_create_thumbnail( $id, false );
	if ( ! $bret ) return $err;

	// Return success
	return false;
}

// Remove illegal files in WPPA_DEPOT_PATH
function wppa_sanitize_files() {

	// Get this users depot directory
	$depot = WPPA_DEPOT_PATH;
	_wppa_sanitze_files( $depot, 'import' );
}

function _wppa_sanitze_files( $root, $from = '' ) {

	$allowed_types = wppa_get_supported_extensions( $from );
	if ( wppa_user_is_admin() ) {
		$allowed_types = array_merge( $allowed_types, array( 'amf', 'pmf', 'csv' ) );
	}
	if ( current_user_can( 'wppa_settings' ) ) {
		$allowed_types = array_merge( $allowed_types, array( 'bak', 'skin' ) );
	}

	$paths = $root.'/*';
	$files = wppa_glob( $paths );

	$count = '0';
	if ( $files ) foreach ( $files as $file ) {

		if ( wppa_is_file( $file ) ) {

			// Check extension
			$ext = strtolower( wppa_get_ext( $file ) );
			if ( ! in_array( $ext, $allowed_types ) ) {
				if ( basename( $file ) != 'index.php' ) {
					wppa_unlink( $file );
					wppa_error_message( sprintf( __( 'File %s is of an unsupported filetype and has been removed.' , 'wp-photo-album-plus' ), basename( wppa_sanitize_file_name( $file ) ) ) );
				}
				$count++;
			}
		}
		elseif ( wppa_is_dir( $file ) ) {
			$entry = basename( $file );
			if ( $entry != '.' && $entry != '..' ) {
				_wppa_sanitze_files( $file, $from );
			}
		}
	}
	return $count;
}


function wppa_update_single_photo( $file, $id, $name ) {
global $wpdb;

	$photo = $wpdb->get_row( $wpdb->prepare( "SELECT id, name, ext, album, filename FROM $wpdb->wppa_photos WHERE id = %s", $id ), ARRAY_A );

	// Find extension
	$ext = $photo['ext'];

	if ( $ext == 'xxx' ) {
		$ext = strtolower( wppa_get_ext( $file ) ); 	// Copy from source
		if ( $ext == 'jpeg' ) $ext = 'jpg';
	}

	// Make proper oriented source
	wppa_make_o1_source( $id );

	// Make the files
	wppa_make_the_photo_files( $file, $id, $ext );

	// and add watermark ( optionally ) to fullsize image only
	wppa_add_watermark( $id );

	// create new thumbnail
	wppa_create_thumbnail( $id );

	// Update filename if not present. this is for backward compatibility when there were no filenames saved yet
	if ( ! wppa_get_photo_item( $id, 'filename' ) ) {
		wppa_update_photo( $id, ['filename' => $name] );
	}

	// Clear magick stack
	wppa_update_photo( $id, ['magickstack' => ''] );
}

function wppa_update_photo_files( $file, $xname ) {
global $wpdb;
global $allphotos;

	if ( $xname == '' ) $name = basename( $file );
	else $name = __( $xname , 'wp-photo-album-plus' );

	// Find photo entries that apply to the supplied filename
	$query = $wpdb->prepare(
			"SELECT * FROM $wpdb->wppa_photos WHERE ".
			"filename = %s OR ".
			"filename = %s OR ".
			"( filename = '' AND name = %s ) OR ".
			"( filename = %s )",
			wppa_sanitize_file_name( basename( $file ) ),								// Usual
			$name,																		// Filename is different in is_wppa_tree import
			$name,																		// Old; pre saving filenames
			wppa_strip_ext( wppa_sanitize_file_name( basename( $file ) ) ) . '.xxx'		// Media poster file
		);
	$photos = $wpdb->get_results( $query, ARRAY_A );

	// If photo entries found, process them all
	if ( $photos ) {
		foreach ( $photos as $photo ) {

			// Find photo details
			$id 	= $photo['id'];
			$ext 	= wppa_is_video( $id ) ? 'jpg' : $photo['ext'];
			$alb 	= $photo['album'];

			// Save the new source
			wppa_save_source( $file, basename( $file ), $alb );

			// Remake the files
			wppa_make_the_photo_files( $file, $id, $ext );

			// and add watermark ( optionally ) to fullsize image only
			wppa_add_watermark( $id );

			// create new thumbnail
			if ( wppa_switch( 'watermark_thumbs' ) ) {
				wppa_create_thumbnail( $id );
			}

			// Make proper oriented source
			wppa_make_o1_source( $id );

			// Update filename if still empty ( Old )
			if ( ! $photo['filename'] ) {
				wppa_update_photo( $id, ['filename' => $file] );
			}

			// Update modified flag
			wppa_update_photo( $id );
		}
		return count( $photos );
	}
	return false;
}

function wppa_insert_photo( $file = '', $alb = '', $name = '', $desc = '', $porder = '0', $id = '0', $linkurl = '', $linktitle = '', $owner = '' ) {
global $warning_given_small;

	$album = wppa_cache_album( $alb );
	if ( ! $name ) {
		$name = basename( $file );
	}

	if ( ! wppa_allow_uploads( $alb ) ) {
		if ( is_admin() && ! wppa( 'ajax' ) ) {
			wppa_error_message( __( 'Album is full' , 'wp-photo-album-plus' ) );
		}
		else {
			wppa_alert( __( 'Album is full' , 'wp-photo-album-plus' ) );
		}
		wppa_log('war'. 'Album '.$alb.' is full');
		return false;
	}

	if ( $file != '' && $alb != '' ) {

		// Sanitize name
		$filename 	= wppa_sanitize_file_name( $name );
		if ( $name ) {
			$name = stripslashes( $name );
		}
		else {
			$name = wppa_sanitize_photo_name( $name );
		}

		// If not dups allowed and its already here, quit
		if ( wppa_get( 'nodups' ) || wppa_switch( 'void_dups' ) ) {
			$exists = wppa_is_file_duplicate_photo( $filename, $alb );
			if ( $exists ) {
				if ( wppa_get( 'del-after-p' ) ) {
					wppa_unlink( $file );
					$msg = __( 'Photo %s already exists in album number %s. Removed from depot.' , 'wp-photo-album-plus' );
				}
				else {
					$msg = __( 'Photo %s already exists in album number %s.' , 'wp-photo-album-plus' );
				}
				wppa_warning_message( sprintf( $msg, $name, $alb ) );
				wppa_log('war', sprintf( $msg, $name, $alb ));
				return false;
			}
		}

		// Verify file exists
		if ( ! wppa( 'is_remote' ) && ! file_exists( $file ) ) {
			if ( ! wppa_is_dir( dirname( $file ) ) ) {
				wppa_error_message( htmlentities( 'Error: Directory '.dirname( $file ).' does not exist.' ) );
				return false;
			}
			if ( ! is_writable( dirname( $file ) ) ) {
				wppa_error_message( htmlentities( 'Error: Directory '.dirname( $file ).' is not writable.' ) );
				return false;
			}
			wppa_error_message( htmlentities( 'Error: File '.$file.' does not exist.' ) );
			return false;
		}
		elseif ( wppa( 'is_remote' ) ) {
			/* is now done in wppa_import_photos()
			if ( ! wppa_is_url_a_photo( $file ) ) {
				if ( wppa( 'ajax' ) ) {
					wppa( 'ajax_import_files_error', __( 'Not found', 'wp-photo-album-plus' ) );
				}
				return false;
			}
			*/
//			else {
				// Is a photo. Maybe found a different ext.
				$filename 	= wppa_strip_ext( $filename ) . '.' . wppa_get_ext( $file );
				$name 		= wppa_strip_ext( $name ) . '.' . wppa_get_ext( $file );
//			}
		}

		// Get and verify the size
		$img_size = getimagesize( $file );

		// Assume success finding image size
		if ( $img_size ) {
			if ( wppa_check_memory_limit( '', $img_size['0'], $img_size['1'] ) === false ) {
				wppa_error_message( htmlentities( sprintf( __( 'ERROR: Attempt to upload a photo that is too large to process (%s).' , 'wp-photo-album-plus' ), $name ).wppa_check_memory_limit() ) );
				wppa( 'ajax_import_files_error', __( 'Too big', 'wp-photo-album-plus' ) );
				return false;
			}
			if ( ! $warning_given_small && ( $img_size['0'] < wppa_get_minisize() && $img_size['1'] < wppa_get_minisize() ) ) {
				wppa_warning_message( htmlentities( __( 'WARNING: You are uploading photos that are too small. Photos must be larger than the thumbnail size and larger than the coverphotosize.' , 'wp-photo-album-plus' ) ) );
				wppa( 'ajax_import_files_error', __( 'Too small', 'wp-photo-album-plus' ) );
				$warning_given_small = true;
			}
		}

		// No image size found
		else {
			wppa_error_message( htmlentities( __( 'ERROR: Unable to retrieve image size of' , 'wp-photo-album-plus' ).' '.$file.' '.__( 'Are you sure it is a photo?' , 'wp-photo-album-plus' ) ) );
			wppa( 'ajax_import_files_error', __( 'No photo found', 'wp-photo-album-plus' ) );
			return false;
		}

		// Get ext based on mimetype, regardless of ext
		switch( $img_size[2] ) { 	// mime type
			case 1: $ext = 'gif'; break;
			case 2: $ext = 'jpg'; break;
			case 3: $ext = 'png'; break;
			case 18: $ext = 'webp'; break;
			default:
				wppa_error_message( htmlentities( __( 'Unsupported mime type encountered:' , 'wp-photo-album-plus' ).' '.$img_size[2].'.' ) );
				return false;
		}
		// Get an id if not yet there
		if ( $id == '0' ) {
			$id = wppa_nextkey( WPPA_PHOTOS );
		}
		// Get opt deflt desc if empty
		if ( $desc == '' && wppa_switch( 'apply_newphoto_desc' ) ) {
			$desc = stripslashes( wppa_opt( 'newphoto_description' ) );
		}
		// Reset rating
		$mrat = '0';
		// Find ( new ) owner
		$owner = wppa_get_user();
		// Validate album
		if ( !is_numeric( $alb ) || $alb < '1' ) {
			wppa_error_message( __( 'Album not known while trying to add a photo' , 'wp-photo-album-plus' ) );
			return false;
		}
		if ( ! wppa_have_access( $alb ) ) {
			wppa_error_message( htmlentities( sprintf( __( 'Album %s does not exist or is not accessible while trying to add a photo' , 'wp-photo-album-plus' ), $alb ) ) );
			return false;
		}
		$status = ( wppa_switch( 'moderatephoto' ) && ! current_user_can( 'wppa_admin' ) ) ? 'pending' : wppa_opt( 'status_new' );

		// Add photo to db
		$id = wppa_create_photo_entry( array( 	'id' => $id,
												'album' => $alb,
												'ext' => $ext,
												'name' => $name,
												'p_order' => $porder,
												'description' => $desc,
												'linkurl' => $linkurl,
												'linktitle' => $linktitle,
												'owner' => $owner ? $owner : wppa_get_user(),
												'status' => $status,
												'filename' => $filename
											 ) );
		if ( ! $id ) {
			wppa_error_message( __( 'Could not insert photo.' , 'wp-photo-album-plus' ) );
		}
		else {	// Save the source
			wppa_save_source( $file, $filename, $alb );
			wppa_make_o1_source( $id );
			wppa_invalidate_treecounts( $alb );
			wppa_update_album( $alb );
			wppa_flush_upldr_cache( 'photoid', $id );
		}

		// For photo file creation, if possible, use proper oriented source file, not temp file and also not url
		$t = wppa_get_o1_source_path( $id );
		if ( is_file( $t ) ) {
			$file = $t;
		}
		else {
			$t = wppa_get_source_path( $id );
			if ( is_file( $t ) ) {
				$file = $t;
			}
		}

		// Make the photo files.
		if ( wppa_make_the_photo_files( $file, $id, $ext, ! wppa_does_thumb_need_watermark( $id ) ) ) {

			// Repair photoname if not supplied and not standard
			wppa_set_default_name( $id );

			// Tags
			wppa_set_default_tags( $id );

			// Custom fields defaults
			wppa_set_default_custom( $id );

			// Index
			wppa_index_update( 'photo', $id );

			// and add watermark ( optionally ) to fullsize image only
			wppa_add_watermark( $id );

			// also to thumbnail?
			if ( wppa_does_thumb_need_watermark( $id ) ) {
				wppa_create_thumbnail( $id );
			}
			// Is it a default coverimage?
			wppa_check_coverimage( $id );

			return $id;
		}
	}
	else {
		wppa_error_message( __( 'ERROR: Unknown file or album.' , 'wp-photo-album-plus' ) );
		return false;
	}
}

function wppa_admin_spinner() {

	$result = 	'<img
					id="wppa-admin-spinner"
					src="' . esc_url( wppa_get_imgdir( wppa_use_svg( 'admin' ) ? 'loader.svg' : 'loader.gif' ) ) . '"
					alt="Spinner"
					/>';
	wppa_echo( $result );
}

// Export db table to .csv file
function wppa_export_table( $table ) {
global $wpdb;

	// Open outputfile
	$path = WPPA_UPLOAD_PATH . '/temp/' . $table . '.csv';
	$file = wppa_fopen( $path, 'wb' );
	if ( ! $file ) {
		return false;
	}

	// Init output buffer
	$result = '';

	// Get the fieldnames
	$fields = $wpdb->get_results( "DESCRIBE ".$table."", ARRAY_A );

	// Write the .csv header
	if ( is_array( $fields ) ) {
		foreach( $fields as $field ) {
			$result .= $field['Field'] . wppa_opt( 'csv_sep' );
		}
		$result = rtrim( $result, wppa_opt( 'csv_sep' ) ) . "\n";
	}
	fwrite( $file, $result );

	// Init getting the data
	$count = $wpdb->get_var( "SELECT COUNT(*) FROM " . $table . "" );
	$iters = ceil( $count / 1000 );
	$iter  = 0;

	// Read chunks of 1000 rows
	while ( $iter < $iters ) {
		$query = "SELECT * FROM " . $table . " ORDER BY id LIMIT " . 1000 * $iter . ",1000";
		$data  = $wpdb->get_results( $query, ARRAY_N );

		// Process rows
		if ( $data ) {
			foreach( $data as $row ) {

				// Write to file
				fputcsv( $file, $row, wppa_opt( 'csv_sep' ) );
			}
		}
		$iter++;
	}

	// Close file
	fclose( $file );

	// Done !
	return true;
}

/*
// Convert one text token to a .csv token
function wppa_prep_for_csv( $data ) {

	// Replace " by ""
	$result = str_replace( '"', '""', $data );

	if ( wppa_is_int( $result ) ) {
		$result = strval( intval( $result ) );
	}
	elseif ( $result ) {
		$result = '"' . $result . '"';
	}
	return $result;
}
*/

function wppa_album_admin_footer() {
global $wpdb;

	$albcount 		= $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_albums" );
	$photocount 	= $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos" );
	$pendingcount 	= $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE status = 'pending'" );
	$schedulecount 	= $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE status = 'scheduled'" );

	$result = '
	<div style="clear:both;display:block">' .
		sprintf( __( 'There are <strong>%d</strong> albums and <strong>%d</strong> photos in the system.', 'wp-photo-album-plus' ), $albcount, $photocount );
		if ( $pendingcount ) {
			$result .= ' ' . sprintf( __( '<strong>%d</strong> photos are pending moderation.', 'wp-photo-album-plus' ), $pendingcount );
		}
		if ( $schedulecount ) {
			$result .= ' ' . sprintf( __( '<strong>%d</strong> photos are scheduled for later publishing.', 'wp-photo-album-plus' ), $pendingcount );
		}

		$lastalbum = $wpdb->get_row( "SELECT id, name FROM $wpdb->wppa_albums ORDER BY id DESC LIMIT 1", ARRAY_A );
		if ( $lastalbum ) {
			$result .= '<br>' . sprintf( __( 'The most recently added album is <strong>%s</strong> (%d).', 'wp-photo-album-plus' ), esc_html( stripslashes( $lastalbum['name'] ) ), $lastalbum['id'] );
		}
		$lastphoto = $wpdb->get_row( "SELECT id, name, album FROM $wpdb->wppa_photos ORDER BY timestamp DESC LIMIT 1", ARRAY_A );
		if ( ! $lastphoto ) {
			$result .= '<br>' . __( 'There are no photos yet', 'wp-photo-album-plus' );
			return $result;
		}
		if ( $lastphoto['album'] < '1' ) {
			$trashed = true;
			$album = - ( $lastphoto['album'] + '9' );
		}
		else {
			$trashed = false;
			$album = $lastphoto['album'];
		}
		$lastphotoalbum = $wpdb->get_row( $wpdb->prepare( "SELECT id, name FROM $wpdb->wppa_albums WHERE id = %s", $album ), ARRAY_A );
		if ( $lastphoto ) {
			$result .= '<br>' . sprintf( __( 'The most recently added photo is <strong>%s</strong> (%d)', 'wp-photo-album-plus' ), sanitize_text_field( $lastphoto['name'] ), $lastphoto['id'] );
			if ( $lastphotoalbum ) {
				$result .= ' ' . sprintf( __( 'in album <strong>%s</strong> (%d).', 'wp-photo-album-plus' ), esc_html( stripslashes( $lastphotoalbum['name'] ) ), $lastphotoalbum['id'] );
			}
			if ( $trashed ) {
				$result .= ' <span style="color:red" >' . __( 'Deleted', 'wp-photo-album-plus' ) . '</span>';
			}
		}
	$result .= '</div>';

	return $result;
}

// edit album url
function wppa_ea_url( $edit_id, $tab = 'edit' ) {

	$nonce = wp_create_nonce( 'wppa-nonce' );

	return get_admin_url() . 'admin.php?page=wppa_admin_menu&amp;tab=' . $tab . '&amp;edit-id=' . $edit_id . '&amp;wppa-nonce=' . $nonce;
}

// Convert a non 360 deg spheric panorama photo to 360 deg by padding
function wppa_make_360( $id, $degs ) {

	// Does source exist?
	$file = wppa_get_source_path( $id );
	if ( ! wppa_is_file( $file ) ) {
		return false;
	}

	$img_tmp = null;

	// If $degs > 360, first trim to 360
	if ( $degs > 360 ) {
		$img_old = imagecreatefromjpeg( $file );
		$sizes = getimagesize( $file );
		$w_old = $sizes[0];
		$h_old = $sizes[1];
		$w_tmp = round( $w_old * 360 / $degs );
		$img_tmp = imagecreatetruecolor( $w_tmp, $h_old );
		imagecopy( $img_tmp, $img_old, 0, 0, round( ( $w_old - $w_tmp ) / 2 ), 0, $w_tmp, $h_old );
		$w_old = $w_tmp;
	}

	// Get current size if <= 360
	else {
		$sizes = getimagesize( $file );
		$w_old = $sizes[0];
		$h_old = $sizes[1];
	}

	// Compute new sizes
	$w_new = $w_old * 360 / $degs;
	$h_new = $w_new / 2;

	// Compute compressionfactor
	$c = 1.0;
	$w_max = wppa_opt( 'panorama_max' );
	if ( $w_new > $w_max ) {
		$c = $w_max / $w_new;
	}

	// Correct new sizes for compressionfactor
	$w_new = round( $w_new * $c );
	$h_new = round( $h_new * $c );

	// Create old image
	if ( $img_tmp ) {
		$image_old = $img_tmp;
	}
	else {
		$image_old = imagecreatefromjpeg( $file );
	}

	// Create canvas
	$canvas = imagecreatetruecolor( $w_new, $h_new );

	// Compute coords
	$dst_x = round( ( $w_new - $w_old * $c ) / 2 );
	$dst_y = round( ( $h_new - $h_old * $c ) / 2 );
	$src_x = 0;
	$src_y = 0;
	$dst_w = round( $w_old * $c );
	$dst_h = round( $h_old * $c );
	$src_w = $w_old;
	$src_h = $h_old;

	// Copy into canvas
	if ( ! imagecopyresampled( $canvas, $image_old, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) ) {
		return false;
	}

	// Save new image as o1 source
	$dst_path = wppa_get_o1_source_path( $id );
	if ( ! wppa_imagejpeg( $canvas, $dst_path, wppa_opt( 'jpeg_quality' ) ) ) {
		return false;
	}

	// Housekeeping
	imagedestroy( $canvas );
	wppa_bump_photo_rev();
	wppa_bump_thumb_rev();

	// All done
	return true;
}

// Admin pagelinks
// @1: string, from where slug
// @2: int, current pagesize
// @3: int, current page
// @4: int: total count
// @5: string: reload url without '&paged=...'
// @6: bool: return if true, else output directly
function wppa_admin_pagination( $pagesize, $current, $total_items, $url, $which = 'bottom' ) {

	// Init
	if ( $which != 'top' ) {
		$which = 'bottom';
	}
	$output = '';
	$link 	= $url . '&paged=';
	$total_pages = $pagesize ? ceil( $total_items / $pagesize ) : '1';
	$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
	$current_url = remove_query_arg( 'paged', $current_url );

	$page_links = array();

	$disable_first = false;
	$disable_last  = false;
	$disable_prev  = false;
	$disable_next  = false;

	if ( 1 == $current ) {
		$disable_first = true;
		$disable_prev  = true;
	}
	if ( $total_pages == $current ) {
		$disable_last = true;
		$disable_next = true;
	}

	// Overall wrapper, for bottom only
	if ( $which == 'bottom' ) {
		$output .= '<div class="test" style="line-height:2.1em;margin-top:6px;">';
	}

	// Inner wrapper
	$output .= '<div class="wppa-admin-pagination">';

	// Total number of items
	$output .= '
			<span class="displaying-num">' .
				sprintf(
				/* translators: %s: Number of items. */
				_n( '%s item', '%s items', $total_items ),
				number_format_i18n( $total_items ) ) . '
			</span>';

	// Start pagination links
	$output .= "\n".'<span class="pagination-links">';

	// First indicator / button
	if ( $disable_first ) {
		$output .= "\n".'<span class="tablenav-pages-navspan button disabled" aria-hidden="true"><img src="'.wppa_get_imgdir('Left-3.svg').'" style="height:1em;margin-bottom:-1px;" /></span>';
	} else {
		$output .= "\n" . sprintf(
			'<a class="first-page button" href="%s"><span class="screen-reader-text">%s</span><span aria-hidden="true"><img src="'.wppa_get_imgdir('Left-3.svg').'" style="height:1em;margin-bottom:-1px;" /></span></a>',
			esc_url( add_query_arg( 'paged', 1, $current_url ) ),
			__( 'First page' )
		);
	}

	// Prev indicator / button
	if ( $disable_prev ) {
		$output .= "\n".'<span class="tablenav-pages-navspan button disabled" aria-hidden="true"><img src="'.wppa_get_imgdir('Left-2.svg').'" style="height:1em;margin-bottom:-1px;" /></span>';
	} else {
		$output .= "\n".sprintf(
			'<a class="prev-page button" href="%s"><span class="screen-reader-text">%s</span><span aria-hidden="true"><img src="'.wppa_get_imgdir('Left-2.svg').'" style="height:1em;margin-bottom:-1px;" /></span></a>',
			esc_url( add_query_arg( 'paged', max( 1, $current - 1 ), $current_url ) ),
			__( 'Previous page' )
		);
	}

	// Current page bottom
	if ( 'bottom' === $which ) {
		$html_current_page  = $current;
		$total_pages_before = '
		<span class="screen-reader-text">' . __( 'Current Page' ) . '</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">';
	}

	// Current page top
	else {
		$html_current_page = sprintf(
			'%s<input
				class="current-page pietje"
				id="current-page-selector"
				type="text"
				name="paged"
				value="%s"
				size="%d"
				aria-describedby="table-paging"
				onchange="document.location.href=\'%s&paged=\'+Math.min(Math.max(parseInt(this.value)||1,1),'.$total_pages.')"
			/><span class="tablenav-paging-text">',
			'<label for="current-page-selector" class="screen-reader-text">' . __( 'Current Page' ) . '</label>',
			$current,
			strlen( $total_pages ),
			esc_url( remove_query_arg( 'paged' ) )
		);
		$total_pages_before = '<span class="paging-input">';
	}

	$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
	$output .= $total_pages_before . sprintf(

		/* translators: 1: Current page, 2: Total pages. */
		__( '%1$s of %2$s', 'wp-photo-album-plus' ),
		$html_current_page,
		$html_total_pages
	) . '</span></span>';

	// Next button / indicator
	if ( $disable_next ) {
		$output .= "\n".'<span class="tablenav-pages-navspan button disabled" aria-hidden="true"><img src="'.wppa_get_imgdir('Right-2.svg').'" style="height:1em;margin-bottom:-1px;" /></span>';
	} else {
		$output .= "\n". sprintf(
			'<a class="next-page button" href="%s"><span class="screen-reader-text">%s</span><span aria-hidden="true"><img src="'.wppa_get_imgdir('Right-2.svg').'" style="height:1em;margin-bottom:-1px;" /></span></a>',
			esc_url( add_query_arg( 'paged', min( $total_pages, $current + 1 ), $current_url ) ),
			__( 'Next page' )
		);
	}

	// Last button / indicator
	if ( $disable_last ) {
		$output .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true"><img src="'.wppa_get_imgdir('Right-3.svg').'" style="height:1em;margin-bottom:-1px;" /></span>';
	} else {
		$output .= sprintf(
			'<a class="last-page button" href="%s"><span class="screen-reader-text">%s</span><span aria-hidden="true"><img src="'.wppa_get_imgdir('Right-3.svg').'" style="height:1em;margin-bottom:-1px;" /></span></a>',
			esc_url( add_query_arg( 'paged', $total_pages, $current_url ) ),
			__( 'Last page' )
		);
	}

	// The pagesize selectionbox
	if ( $which == 'top' ) {
		$output .= ' ' .
		__( 'Pagesize', 'wp-photo-album-plus' ) . '
		<select style="vertical-align:top"
			onchange="jQuery( \'#wppa-admin-spinner\' ).show();document.location.href=\''.$link.'1&wppa-pagesize=\'+this.value;">
			<option value="10"' . ( $pagesize == '10' ? ' selected' : '' ) . '>10</option>
			<option value="20"' . ( $pagesize == '20' ? ' selected' : '' ) . '>20</option>
			<option value="50"' . ( $pagesize == '50' ? ' selected' : '' ) . '>50</option>
			<option value="100"' . ( $pagesize == '100' ? ' selected' : '' ) . '>100</option>
			<option value="200"' . ( $pagesize == '200' ? ' selected' : '' ) . '>200</option>
			<option value="500"' . ( $pagesize == '500' ? ' selected' : '' ) . '>500</option>
			<option value="1000"' . ( $pagesize == '1000' ? ' selected' : '' ) . '>1000</option>
			<option value="2000"' . ( $pagesize == '2000' ? ' selected' : '' ) . '>2000</option>
			<option value="5000"' . ( $pagesize == '5000' ? ' selected' : '' ) . '>5000</option>
			<option value="10000"' . ( $pagesize == '10000' ? ' selected' : '' ) . '>10000</option>
		</select>';
	}

	// Close pagination links
	$output .= '</span>';

	// Close overall inner
	$output .= '</div>';

	// Close overall wrapper, bottom only
	if ( $which == 'bottom' ) {
		$output .= '</div>';
	}

	// Done
	wppa_echo( $output, false, false, false, true );
}

// Get paging parameters
function wppa_get_paging_parms( $slug, $page_1 = false ) {
static $all_parms;
static $user_id;
static $last_slug;
static $p1_overrule;

	// Subsequent identical calls: no work to do, just return previous result
	if ( ! $page_1 && is_array( $all_parms ) && isset( $all_parms[$slug] ) && $last_slug == $slug ) {
		return $all_parms[$slug];
	}

	// Init
	$user_id 	= wppa_get_user_id();
	$all_parms 	= get_transient( 'wppa_paging_parms_' . $user_id );
	if ( ! $all_parms ) {
		$all_parms = array();
	}
	if ( ! isset( $all_parms[$slug] ) ) {
		$all_parms[$slug] = array( 'order' => 'id', 'dir' => 'asc', 'page' => '1', 'pagesize' => '10' );
	}
	if ( $page_1 ) {
		$p1_overrule = true;
	}

	// Order by
	$all_parms[$slug]['order'] = wppa_get( 'order-by', $all_parms[$slug]['order'], 'text' );

	// Dir
	$all_parms[$slug]['dir'] = wppa_get( 'dir', $all_parms[$slug]['dir'], 'text' );

	// Page
	if ( $p1_overrule ) {
		$all_parms[$slug]['page'] = '1';
	}
	else {
		$all_parms[$slug]['page'] = wppa_get( 'paged', $all_parms[$slug]['page'], 'int' );
	}

	// Pagesize
	$all_parms[$slug]['pagesize'] = wppa_get( 'pagesize', $all_parms[$slug]['pagesize'], 'int' );

	set_transient( 'wppa_paging_parms_' . $user_id, $all_parms, MONTH_IN_SECONDS );
	return $all_parms[$slug];
}

// Get admin page reload url
// arg1: string. where slug
// arg2: string. a vailt order column
function wppa_admin_reload_url( $slug, $for ) {

	// Init
	$err = false;

	// Validate args
	switch( $slug ) {
		case 'edit_email':
			if ( ! in_array( $for, ['ID', 'user_login', 'display_name'] ) ) {
				$err = true;
			}
			break;
		case 'album_admin':
			if ( ! in_array( $for, ['id', 'name', 'description', 'owner', 'a_order', 'a_parent'] ) ) {
				$err = true;
			}
			break;
		default:
			$err = true;
	}

	if ( $err ) {
		wppa_log( 'err', 'Invalid for in wppa_admin_reload_url('.$slug.','.$for.')');
		return '';
	}

	// Do it
	$parms = wppa_get_paging_parms( $slug );
	if ( $for == $parms['order'] ) {
		if ( $parms['dir'] == 'asc' ) {
			$dir = 'desc';
		}
		else {
			$dir = 'asc';
		}
		$page = $parms['page'];
	}
	else {
		$dir = 'asc';
		$page = '1';
	}

	switch( $slug ) {

		case 'edit_email':
			$url = get_admin_url() . 'admin.php?page=wppa_edit_email&wppa-order-by=' . $for . '&wppa-dir=' . $dir . '&paged=' . $page;
			break;

		case 'album_admin':
			$url = get_admin_url() . 'admin.php?page=wppa_admin_menu&wppa-order-by=' . $for . '&wppa-dir=' . $dir . '&paged=' . $page;
			break;

		default:
			$url = '';
	}

	return $url;
}

function wppa_status_display_name( $status ) {
static $wppa_statarray;

	if ( ! $wppa_statarray ) {
		$wppa_statarray = [
			'pending' 	=> __( 'Pending', 'wp-photo-album-plus' ),
			'publish' 	=> __( 'Publish', 'wp-photo-album-plus' ),
			'featured' 	=> __( 'Featured', 'wp-photo-album-plus' ),
			'gold' 		=> __( 'Gold', 'wp-photo-album-plus' ),
			'silver' 	=> __( 'Silver', 'wp-photo-album-plus' ),
			'bronze' 	=> __( 'Bronze', 'wp-photo-album-plus' ),
			'scheduled' => __( 'Scheduled', 'wp-photo-album-plus' ),
			'private' 	=> __( 'Private', 'wp-photo-album-plus' ),
			];
	}
	if ( isset( $wppa_statarray[$status] ) ) {
		return $wppa_statarray[$status];
	}
	else {
		wppa_log( 'err', "Uniplemented status found in wppa_status_display_name( $status )" );
		return '';
	}
}



// Get the pdf to album conversion parms
// Arg 1: id of the pdf
// Reurns: array(int pagtype, int album, int pagesdone, bool ready, bool crashed)
function wppa_get_pdf_conv_parms( $id ) {
global $wpdb;

	$defaults 	= ['0', '0', '0', false];
	$keys 		= ['pagtype', 'album', 'pagesdone', 'ready', 'crashed', 'running'];
	$temp 		= wppa_get_photo_item( $id, 'misc' );
	$items 		= explode( ',', $temp );
	for ( $i = 0; $i < 4; $i++ ) {
		if ( ! isset( $items[$i] ) ) {
			$items[$i] = $defaults[$i];
		}
		$result[$keys[$i]] = $items[$i];
	}

	// Try to find the expected album if not known yet
	if ( ! $result['album'] ) {
		$name 		= wppa_get_photo_name( $id );
		$album_name = $name ? $name : wppa_strip_ext( wppa_get_photo_item( $id, 'filename' ) );
		$parent 	= wppa_get_photo_item( $id, 'album' );
		$alb 		= $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE name = %s AND a_parent = %d LIMIT 1", $name, $parent ) );
		$result['album'] = $alb;
	}
	$result['crashed'] =
	( get_option( 'stop-pdfcnv-' . $id ) == 'yes' ) ||
	( ! $result['ready'] && $result['album'] && wppa_get_photo_item( $id, 'modified' ) < ( time() - max ( min( intval( ini_get( 'max_execution_time' ) ), 120 ), 30 ) ) );
	$result['running'] =
	( ! $result['crashed'] ) &&
	$result['album'] && wppa_get_photo_item( $id, 'modified' ) >= ( time() - max ( min( intval( ini_get( 'max_execution_time' ) ), 120 ), 30 ) );
	return $result;
}

// Update the pdf to album conversion parms
function wppa_update_pdf_conv_parms( $id, $fields ) {
	$current 	= wppa_get_pdf_conv_parms( $id );
	$new 		= [];
	foreach ( array_keys( $current ) as $key ) {
		if ( isset( $fields[$key] ) ) {
			$new[$key] = $fields[$key];
		}
		else {
			$new[$key] = $current[$key];
		}
	}
	$result = implode( ',', $new );
	wppa_update_photo( $id, ['misc' => $result, 'modified' => time()] );
}
