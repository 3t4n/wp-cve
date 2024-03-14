<?php
/* wppa-source.php
* Package: wp-photo-album-plus
*
* Contains photo source file management routines
* Version 8.0.00.004
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

function wppa_save_source( $file, $name, $alb, $always = false ) {

	wppa_log('dbg', 'Save source called with always='.$always );
	$doit = true;

	if ( wppa_switch( 'keep_source' ) || $always ) {

		wppa_log('dbg', 'Saving source');
		if ( ! wppa_is_dir( wppa_opt( 'source_dir' ) ) ) {
			@ wppa_mktree( wppa_opt( 'source_dir' ) );
		}
		$sourcedir = wppa_get_source_dir();
		if ( ! wppa_is_dir( $sourcedir ) ) {
			@ wppa_mktree( $sourcedir );
		}
		$albdir = wppa_get_source_album_dir( $alb );
		if ( ! wppa_is_dir( $albdir ) ) {
			@ wppa_mktree( $albdir );
		}
		if ( ! wppa_is_dir( $albdir ) ) {
			wppa_log( 'Err', 'Could not create source directory ' . $albdir );
		}

		$dest = $albdir . '/' . wppa_sanitize_file_name( $name );
		if ( $file != $dest ) {

			// Delete possible old o1 file
			$o1 = wppa_strip_ext( $dest ) . '-o1.' . wppa_get_ext( $dest );
			if ( wppa_is_file( $o1 ) ) {
				wppa_unlink( $o1 );
			}

			wppa_copy( $file, $dest );

		}
		if ( ! wppa_is_file( $dest ) ) {
			wppa_log( 'Err', 'Could not save ' . $dest, true );
		}
	}
}

function wppa_delete_source( $name, $alb ) {

	if ( wppa_switch( 'keep_sync') ) {
		$path = wppa_get_source_album_dir( $alb ).'/'.$name;
		$path = wppa_strip_ext( $path );

		$all_paths = wppa_glob( $path . '.*' );
		$o1paths = wppa_glob( $path . '-o1.*' );

		if ( is_array( $all_paths ) && is_array( $o1paths ) ) {
			$all_paths = array_merge( $all_paths, $o1paths );
		}

		// Delete all possible file-extensions
		if ( is_array( $all_paths ) ) foreach( $all_paths as $p ) if ( is_file( $p ) ) {
			unlink( $p );								// Ignore error
		}
	}
}

function wppa_move_source( $name, $from, $to ) {
global $wppa_supported_photo_extensions;

	// Source files can have uppercase extensions.
	$temp = array();
	foreach( $wppa_supported_photo_extensions as $ext ) {
		$temp[] = strtoupper( $ext );
	}
	$supext = array_merge( $wppa_supported_photo_extensions, $temp );

	if ( wppa_switch( 'keep_sync') ) {
		$frompath 	= wppa_get_source_album_dir( $from ).'/'.wppa_strip_ext($name);
		$todir 		= wppa_get_source_album_dir( $to );
		$topath 	= wppa_get_source_album_dir( $to ).'/'.wppa_strip_ext($name);
		if ( ! wppa_is_dir( $todir ) ) @ wppa_mktree( $todir );

		foreach( $supext as $ext ) {
			if ( is_file( $frompath.'.'.$ext ) ) {

				// rename. Will fail if target already exists
				wppa_rename( $frompath.'.'.$ext, $topath.'.'.$ext );
				wppa_rename( $frompath.'-o1.'.$ext, $topath.'-o1.'.$ext );

				// therefor delete if still exists
				if ( is_file( $frompath.'.'.$ext ) ) {
					@ unlink( $frompath.'.'.$ext );
				}
				if ( is_file( $frompath.'-o1.'.$ext ) ) {
					@ unlink( $frompath.'-o1.'.$ext );
				}
			}
		}
	}
}

function wppa_copy_source( $name, $from, $to ) {
global $wppa_supported_photo_extensions;

	// Source files can have uppercase extensions.
	$temp = array();
	foreach( $wppa_supported_photo_extensions as $ext ) {
		$temp[] = strtoupper( $ext );
	}
	$supext = array_merge( $wppa_supported_photo_extensions, $temp );

	if ( wppa_switch( 'keep_sync') ) {
		$frompath 	= wppa_get_source_album_dir( $from ).'/'.wppa_strip_ext($name);
		$todir 		= wppa_get_source_album_dir( $to );
		$topath 	= wppa_get_source_album_dir( $to ).'/'.wppa_strip_ext($name);
		if ( ! wppa_is_dir( $todir ) ) @ wppa_mktree( $todir );

		foreach( $supext as $ext ) {
			if ( is_file( $frompath.'.'.$ext ) ) {
				wppa_copy( $frompath.'.'.$ext, $topath.'.'.$ext );
			}
			if ( is_file( $frompath.'-o1.'.$ext ) ) {
				wppa_copy( $frompath.'-o1.'.$ext, $topath.'-o1.'.$ext );
			}
		}
	}
}

// Get o1 source ratio, or source ratio if o1 source does not exist
function wppa_get_source_ratio( $id ) {
	$file = $source_file = wppa_get_o1_source_path( $id );
	if ( ! wppa_is_file( $file ) ) {
		$file = wppa_get_source_path( $id );
		if ( ! wppa_is_file( $file ) ) {
			return false;
		}
	}
	$file_sizes = wppa_getimagesize( $file );
	if ( isset( $file_sizes[1] ) && $file_sizes[1] > 0 ) {
		return $file_sizes[0] / $file_sizes[1];
	}
	return false;
}