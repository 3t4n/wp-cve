<?php
/* wppa-local-cdn.php
* Package: wp-photo-album-plus
*
* Contains routines for local cdn implementation
* Version 8.6.03.001
*
*/

// Compute a pathname for a local cdn image
function wppa_cdn_path( $id, $x, $y ) {

	// Sanitize input
	$x = floor( $x );
	$y = floor( $y );
	$id = strval( intval( $id ) );

	// Make the path
	$ext 	= wppa_get_photo_item( $id, 'ext' );
	$path 	= WPPA_UPLOAD_PATH . '-cdn/' . wppa_expand_id( $id ) . '/' . $x . '-' . $y . '.' . $ext;
	return $path;
}

// Return the url to a local cdn image file, or false if the file does not exist and can not be created
function wppa_cdn_url( $id, $x, $y ) {

	// Sanitize input
	$x = floor( $x );
	$y = floor( $y );
	$id = strval( intval( $id ) );

	$path 	= wppa_cdn_path( $id, $x, $y );
	if ( ! wppa_is_file( $path ) ) {
		wppa_cdn_make( $id, $x, $y );
	}
	$url 	= ( wppa_is_file( $path ) ? str_replace( WPPA_UPLOAD_PATH, WPPA_UPLOAD_URL, $path ) : false );
	return $url;
}

// Attempt to make a local cdn image file
function wppa_cdn_make( $id, $x, $y ) {

	// Sanitize input
	$x = floor( $x );
	$y = floor( $y );
	$id = strval( intval( $id ) );

	// Find from path. Use display image, not source.
	$fmpath 	= wppa_get_photo_path( $id );

	// Only continue if from path exists
	if ( ! wppa_is_file( $fmpath ) ) return;

	// From path exists, continue
	$fmsize 	= wppa_getimagesize( $fmpath );
	$topath 	= wppa_cdn_path( $id, $x, $y );
	$tpshort 	= str_replace( WPPA_UPLOAD_PATH, '.../wppa', $topath );

	// Create file's dir if not exists
	$dir = dirname( $topath );
	if ( ! wppa_is_dir( $dir ) ) {
		wppa_mktree( $dir );
	}

	// Create scaled image
	// ImageMagick
	if ( wppa_can_magick() ) {

		// If jpg, apply jpeg quality
		$q = wppa_opt( 'jpeg_quality' );
		$quality = '';
		if ( wppa_get_ext( $fmpath ) == 'jpg' ) {
			$quality = '-quality ' . $q;
		}

		// Internal?
		if ( false && class_exists( 'Imagick' ) ) {
			$im = new Imagick( $fmpath );
			$im->resizeImage( $x, $y, Imagick::FILTER_CATROM, 1 );
			$bret = $im->writeImage( $topath );
			$im->destroy();
			$err = ! $bret;
		}
		else {
			$err = wppa_image_magick( 'convert ' . $fmpath . ' ' . $quality . ' -resize ' . $x . 'x' . $y . ' ' . $topath );
		}

		// Log what we did
		if ( $err ) {
			wppa_log( 'fso', 'Imagick failed to create ' . $tpshort );
		}
		else {
			wppa_log( 'fso', 'Imagick Created ' . $tpshort );
		}
	}

	// Classic GD
	else {

		$src 	= false;
		$dst 	= false;
		$tmp 	= false;
		$bret 	= false;

		switch( $fmsize[2] ) {
			case 1: // gif

				// Make source image
				$tmp = wppa_imagecreatefromgif( $fmpath );
				if ( ! $tmp ) return false;

				// Make empty intermediate full color image
				$src = imagecreatetruecolor( $fmsize[0], $fmsize[1] );

				// Copy gif to jpg, orig size
				imagecopy( $src, $tmp, 0, 0, 0, 0, $fmsize[0], $fmsize[1] );

				// Create destinarion image
				$dst = imagecreatetruecolor( $x, $y );

				// Copy rescaled
				imagecopyresampled( $dst, $src, 0, 0, 0, 0, $x, $y, $fmsize[0], $fmsize[1] );

				// Save new image
				$bret = wppa_imagegif( $dst, $topath );

				break;

			case 2: // jpg

				// Make source image
				$src = wppa_imagecreatefromjpeg( $fmpath );
				if ( ! $src ) return false;

				// Make empty destination image
				$dst = imagecreatetruecolor( $x, $y );

				// Copy rescaled
				imagecopyresampled( $dst, $src, 0, 0, 0, 0, $x, $y, $fmsize[0], $fmsize[1] );

				// Save new image
				$bret = wppa_imagejpeg( $dst, $topath, wppa_opt( 'jpeg_quality' ) );

				break;

			case 3: // png

				// Make source image
				$src = wppa_imagecreatefrompng( $fmpath );
				if ( ! $src ) return false;

				// Make empty destination image
				$dst = imagecreatetruecolor( $x, $y );

				// Copy rescaled
				imagecopyresampled( $dst, $src, 0, 0, 0, 0, $x, $y, $fmsize[0], $fmsize[1] );

				// Save new image
				$bret = wppa_imagepng( $dst, $topath );

				break;

			case 18: // webp

				// Make source image
				$src = wppa_imagecreatefromwebp( $fmpath );
				if ( ! $src ) return false;

				// Make empty destination image
				$dst = imagecreatetruecolor( $x, $y );

				// Copy rescaled
				imagecopyresampled( $dst, $src, 0, 0, 0, 0, $x, $y, $fmsize[0], $fmsize[1] );

				// Save new image
				$bret = wppa_imagewebp( $dst, $topath );

				break;

			default: 	// Not implemented mimetype

				// Indicat error
				$bret = false;

				break;
		}

		// Cleanup
		if ( $src ) imagedestroy( $src );
		if ( $dst ) imagedestroy( $dst );
		if ( $tmp ) imagedestroy( $tmp );

		// Log what we did
		if ( $bret ) {
			wppa_log( 'fso', 'GD Created ' . $tpshort );
		}
		else {
			wppa_log( 'fso', 'GD failed to create ' . $tpshort );
		}
	}
}

// Return an array of existing files in a local cdn files id folder. May include index.php
function wppa_cdn_files( $id ) {

	$dir 	= dirname( wppa_cdn_path( $id, 1, 1 ) );
	$files 	= wppa_glob( $dir . '/*' );
	$result = array();
	if ( is_array( $files ) ) {
		foreach( $files as $file ) {
			if ( wppa_is_file( $file ) ) {
				$result[] = $file;
			}
		}
	}
	return $result;
}

// Deletes the existing local cdn files
function wppa_cdn_delete( $id ) {

	$dir = dirname( wppa_cdn_path( $id, 1, 1 ) );
	if ( ! wppa_is_dir( $dir ) ) return;

	$files = wppa_glob( $dir . '/*' );
	if ( is_array( $files ) ) {
		foreach( $files as $file ) {
			if ( is_file( $file ) ) {
				unlink( $file );
				wppa_log( 'fso', $file . ' removed from local CDN' );
			}
		}
	}
	wppa_rmdir( $dir );
}