<?php
/* wppa-photo-files.php
*
* Functions used to create/manipulate photofiles
* Version: 8.6.03.001
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Unfortunately there is no php function to rotate or resize an image file while the exif data is preserved.
// The origianal sourcefile is normally saved, to be available for download or hires uses e.g. in lightbox.
// The orientation of photos made by mobile devices is often non-standard ( 1 ), so we need a higres file,
// rotated and/or mirrored to the correct position.
// When the sourefile name is e.g.: .../wp-content/uploads/wppa-source/album-1/MyImage.jpg,
// We create the correct oriented file: .../wp-content/uploads/wppa-source/album-1/MyImage-o1.jpg. ( o1 stands for orientation=1 ).
// Note: wppa_get_source_path() should return the un-oriented file always, while wppa_get_hires_url() must return the -o1 file, if available.
function wppa_make_o1_source( $id ) {

	// Init
	$src_path = wppa_get_source_path( $id );

	// Source available?
	if ( ! is_file( $src_path ) ) return false;

	// Only needed for non-standard orientations
	$orient = wppa_get_exif_orientation( $src_path );
	if ( ! in_array( $orient, array( '2', '3', '4', '5', '6', '7', '8' ) ) ) return false;

	// Only on jpg file type
	$ext = wppa_get_ext( $src_path );
	if ( ! in_array( $ext, array( 'jpg', 'JPG', 'jpeg', 'JPEG' ) ) ) return false;

	// Make destination path
	$dst_path = wppa_get_o1_source_path( $id );

	// ImageMagick
	if ( wppa_can_magick() ) {
		wppa_image_magick( 'convert ' . $src_path . ' -auto-orient ' . $dst_path );
	}

	// Classic
	else {

		// Copy source to destination
		wppa_copy( $src_path, $dst_path );

		// Correct orientation
		if ( ! wppa_orientate_image_file( $dst_path, $orient ) ) {
			wppa_unlink( $dst_path );
			return false;
		}
	}

	// Done
	return true;
}

// Convert source file path to proper oriented source file path
function wppa_get_o1_source_path( $id ) {

	$src_path = wppa_get_source_path( $id );
	if ( $src_path ) {
		$src_path = wppa_strip_ext( $src_path ) . '-o1.' . wppa_get_ext( $src_path );
	}

	return $src_path;
}

// Rotate/mirror a photo display image by id
function wppa_orientate_image( $id, $ori ) {

	// If orientation right, do nothing
	if ( ! $ori || $ori == '1' ) {
		return;
	}

	wppa_orientate_image_file( wppa_get_photo_path( $id ), $ori );
	wppa_bump_photo_rev();
}

// Rotate/mirror an image file by pathname
function wppa_orientate_image_file( $file, $ori ) {

	// Validate args
	if ( ! is_file( $file ) ) {
		wppa_log( 'Err', 'File not found (wppa_orientate_image_file())' );
		return false;
	}
	if ( ! wppa_is_int( $ori ) || $ori < '2' || $ori > '8' ) {
		wppa_log( 'Err', 'Bad arg $ori:'.$ori.' (wppa_orientate_image_file())' );
		return false;
	}

	// Load image
	$source = wppa_imagecreatefromjpeg( $file );
	if ( ! $source ) {
		return false;
	}

	// Perform operation
	switch ( $ori ) {
		case '2':
			$orientate = $source;
			imageflip( $orientate, IMG_FLIP_HORIZONTAL );
			break;
		case '3':
			$orientate = imagerotate( $source, 180, 0 );
			break;
		case '4':
			$orientate = $source;
			imageflip( $orientate, IMG_FLIP_VERTICAL );
			break;
		case '5':
			$orientate = imagerotate( $source, 270, 0 );
			imageflip( $orientate, IMG_FLIP_HORIZONTAL );
			break;
		case '6':
			$orientate = imagerotate( $source, 270, 0 );
			break;
		case '7':
			$orientate = imagerotate( $source, 90, 0 );
			imageflip( $orientate, IMG_FLIP_HORIZONTAL );
			break;
		case '8':
			$orientate = imagerotate( $source, 90, 0 );
			break;
		default:
			break;
	}

	// Output
	wppa_imagejpeg( $orientate, $file, wppa_opt( 'jpeg_quality' ) );

	// Free the memory
	imagedestroy( $source );
	@ imagedestroy( $orientate );

	// Done
	return true;
}

// Make the display and thumbnails from a given pathname or upload temp image file.
// The id and extension must be supplied.
function wppa_make_the_photo_files( $file, $id, $ext, $do_thumb = true, $do_exif = true ) {

	$thumb = wppa_cache_photo( $id );

	$documentstub = WPPA_UPLOAD_PATH . '/documentstub.png';

	// If it is a pdf, check if the .jpg source exists, and change $file to the source .jpg
	if ( wppa_is_pdf( $id ) ) {

		$pdf = wppa_strip_ext( wppa_get_source_path( $id ) ) . '.pdf';
		$jpg = wppa_strip_ext( wppa_get_source_path( $id ) ) . '.jpg';
		$png = wppa_strip_ext( wppa_get_source_path( $id ) ) . '.png';

		if ( wppa_can_magick() ) {

			// Try making the jpg from the pdf
			wppa_image_magick( 'convert -density 200 ' . $pdf . '[0] -quality 90 -background white -alpha remove ' . $jpg );
			$err = ! wppa_is_file( $jpg );
			if ( ! $err ) {
				switch ( wppa_opt( 'newphoto_name_method' ) ) {
					case 'filename':
						$name = wppa_strip_ext( $thumb['filename'] ) . '.pdf';
						break;
					case 'noext':
						$name = wppa_strip_ext( $thumb['filename'] );
						break;
					case 'noextspace':
						$name = str_replace( '-', ' ', wppa_strip_ext( $thumb['filename'] ) );
						break;
					case 'Photo w#id':
						$name = 'Photo w#id';
						break;
					default:
						$name = '';
						break;
				}

				wppa_update_photo( $id, ['ext' => 'jpg', 'name' => $name] );

				// Remove possible old stubfile
				if ( wppa_is_file( $png ) ) {
					wppa_unlink( $png );
				}

				// Remove possible old displayfile
				$disp = wppa_strip_ext( wppa_get_photo_path( $id ) ) . '.png';
				if ( wppa_is_file( $disp ) ) {
					wppa_unlink( $disp );
				}

				// Remove possible old thumbnailfile
				$disp = wppa_strip_ext( wppa_get_thumb_path( $id ) ) . '.png';
				if ( wppa_is_file( $disp ) ) {
					wppa_unlink( $disp );
				}
			}
		}

		// Find what we have to create display and thumbfiles
		// First look for jpg
		if ( wppa_is_file( $jpg ) ) {
			wppa_update_photo( $id, ['ext' => 'jpg'] );
			$file = $jpg;
			$ext = 'jpg';
		}
		// Then look for png
		elseif( wppa_is_file( $png ) ) {
			wppa_update_photo( $id, ['ext' => 'png'] );
			$file = $png;
			$ext = 'png';
		}
		// Last resort: use document stub
		else {
			wppa_copy( $documentstub, $png );
			wppa_update_photo( $id, ['ext' => 'png'] );
			$file = $png;
			$ext = 'png';
		}

		// Housekeeping
		clearstatcache();
		wppa_cache_photo( 'invalidate', $id );
		$thumb = wppa_cache_photo( $id );
	}

	$src_size = @getimagesize( $file, $info );

	// If the given file is not an image file, log error and exit
	if ( ! $src_size ) {
		if ( is_admin() ) wppa_error_message( sprintf( __( 'ERROR: File %s is not a valid picture file.' , 'wp-photo-album-plus' ), htmlspecialchars( $file  ) ) );
		else wppa_alert( __( 'ERROR: File is not a valid picture file.', 'wp-photo-album-plus' ) );
		return false;
	}

	// Find output path photo file
	$newimage = wppa_get_photo_path( $id, false );
	if ( $ext ) {
		$newimage = wppa_strip_ext( $newimage ) . '.' . strtolower( $ext );
	}

	// Max sizes
	if ( wppa_opt( 'resize_to' ) == '0' ) {	// from fullsize
		$max_width 	= wppa_opt( 'fullsize' );
		$max_height = wppa_opt( 'maxheight' );
		$do_resize = true;
	}
	elseif ( wppa_opt( 'resize_to' ) == '-1' ) { // no resize
		$do_resize = false;
	}
	else {										// from selection
		$screen = explode( 'x', wppa_opt( 'resize_to' ) );
		$max_width 	= $screen[0];
		$max_height = $screen[1];
		$do_resize = true;
	}

	// If Resize on upload is checked
	if ( $do_resize && wppa_can_resize( $file, max( $max_width, $max_height ) ) ) {

		// ImageMagick
		if ( wppa_can_magick() ) {

			// If jpg, apply jpeg quality
			$q = wppa_opt( 'jpeg_quality' );
			$quality = '';
			if ( wppa_get_ext( $file ) == 'jpg' ) {
				$quality = '-quality ' . $q;
			}

			$iret = wppa_image_magick( 'convert ' . $file . ' ' . $quality . ' -resize ' . ( $thumb['stereo'] ? 2 * $max_width : $max_width ) . 'x' . $max_height . ' ' . $newimage );
			if ( $iret ) {
				wppa_log( 'fso', 'Magick could not create ' . $newimage );
			}
		}

		// Classic GD
		if ( ! wppa_can_magick() || ! is_file( $newimage ) ) {

			// Picture sizes
			$src_width 	= $src_size[0];

			// Temp convert to logical width if stereo
			if ( $thumb['stereo'] ) {
				$src_width /= 2;
			}
			$src_height = $src_size[1];

			// If orientation needs +/- 90 deg rotation, swap max x and max y
			$ori = wppa_get_exif_orientation( $file );
			if ( $ori >= 5 && $ori <= 8 ) {
				$t = $max_width;
				$max_width = $max_height;
				$max_height = $t;
			}

			// Is source more landscape or more portrait than max window
			if ( $src_width/$src_height > $max_width/$max_height ) {	// focus on width
				$focus = 'W';
				$need_downsize = ( $src_width > $max_width );
			}
			else {														// focus on height
				$focus = 'H';
				$need_downsize = ( $src_height > $max_height );
			}

			// Convert back to physical size
			if ( $thumb['stereo'] ) {
				$src_width *= 2;
			}

			// Downsize required ?
			if ( $need_downsize ) {

				// Find mime type
				$mime = $src_size[2];

				// Create the source image
				switch ( $mime ) {	// mime type
					case 1: // gif
						$temp = @ wppa_imagecreatefromgif( $file );
						if ( $temp ) {
							$src = imagecreatetruecolor( $src_width, $src_height );
							imagecopy( $src, $temp, 0, 0, 0, 0, $src_width, $src_height );
							imagedestroy( $temp );
						}
						else $src = false;
						break;
					case 2:	// jpeg
						if ( ! function_exists( 'imagecreatefromjpeg' ) ) {
							wppa_log( 'Error', 'Function imagecreatefromjpeg does not exist.' );
						}
						$src = @ wppa_imagecreatefromjpeg( $file );
						break;
					case 3:	// png
						$src = @ wppa_imagecreatefrompng( $file );
						break;
					case 18: // webp
						$src = @ wppa_imagecreatefromwebp( $file );
						break;
					default:
						wppa_log( 'err', 'Unimplemented mime type: ' . $mime . ' in wppa_make_the_photo_files()' );
						break;
				}

				if ( ! $src ) {
					return false;
				}

				// Create the ( empty ) destination image
				if ( $focus == 'W') {
					if ( $thumb['stereo'] ) $max_width *= 2;
					$dst_width 	= $max_width;
					$dst_height = round( $max_width * $src_height / $src_width );
				}
				else {
					$dst_height = $max_height;
					$dst_width = round( $max_height * $src_width / $src_height );
				}
				$dst = imagecreatetruecolor( $dst_width, $dst_height );

				// If Png, save transparancy
				if ( $mime == 3 ) {
					imagealphablending( $dst, false );
					imagesavealpha( $dst, true );
				}

				// Do the copy
				imagecopyresampled( $dst, $src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height );

				// Remove source image
				imagedestroy( $src );

				// Save the photo
				switch ( $mime ) {	// mime type
					case 1:
						wppa_imagegif( $dst, $newimage );
						break;
					case 2:
						wppa_imagejpeg( $dst, $newimage, wppa_opt( 'jpeg_quality' ) );
						break;
					case 3:
						wppa_imagepng( $dst, $newimage );
						break;
					case 18:
						wppa_imagewebp( $dst, $newimage );
						break;
					default:
						wppa_log( 'err', sprintf( 'Unimplemented mime type %s encountered in wppa_create_thumbnail()', $mime ) );
				}

				// Remove destination image
				imagedestroy( $dst );

			}
			else {	// No downsize needed, picture is small enough
				wppa_copy( $file, $newimage );
			}
		}
	}

	// No resize on upload checked or too big
	else {
		wppa_copy( $file, $newimage );
	}

	// These things do not exist in pdfs
	if ( ! wppa_is_pdf( $id ) && $do_exif ) {

		// Process the iptc data
		wppa_import_iptc( $id, $info );

		// Process the exif data
		wppa_import_exif( $id, $file );

		// GPS
		wppa_get_coordinates( $file, $id );

		// Set ( update ) exif date-time if available
		$exdt = wppa_get_exif_datetime( $file );
		if ( $exdt ) {
			wppa_update_photo( $id, ['exifdtm' => $exdt] );
		}

		// Check orientation
		wppa_orientate_image( $id, wppa_get_exif_orientation( $file ) );
	}

	// Compute and save sizes
	wppa_get_photox( $id, 'force' );

	// Show progression
	if ( is_admin() && ! wppa( 'ajax' ) ) wppa_echo( '.' );

	// Update CDN
	$cdn = wppa_cdn( 'admin' );
	if ( $cdn ) {
		switch ( $cdn ) {
			case 'cloudinary':
				wppa_upload_to_cloudinary( $id );
				break;
			case 'local':
				wppa_cdn_delete( $id ); // Remove existing local cdn files. They will be re-created automatically
				break;
			default:
				wppa_log( 'Err', 'Missing upload instructions for '.$cdn );
		}
	}

	// Create stereo images
	wppa_create_stereo_images( $id );

	// Create thumbnail...
	if ( $do_thumb ) {
		wppa_create_thumbnail( $id );
	}

	// Reset sizes
	wppa_get_photox( $id, true );
	wppa_get_photoy( $id, true );
	wppa_get_thumbx( $id, true );
	wppa_get_thumby( $id, true );

	// Clear magickstack
	wppa_update_photo( $id, ['magickstack' => ''] );

	// Clear (super)cache
	wppa_clear_cache( array( 'photos' => true ) );

	// Optimize optionally
	wppa_optimize_image( wppa_get_photo_path( $id ) );

	return true;

}

// Create thubnail
function wppa_create_thumbnail( $id, $use_source = true ) {

	$locked = wppa_get_photo_item( $id, 'thumblock' );
	if ( $locked ) {
		return false;
	}

	$documentstub = WPPA_UPLOAD_PATH . '/documentstub.png';

	if ( $use_source && ! wppa_switch( 'watermark_thumbs' ) ) {

		// Try o1 source
		$file = wppa_get_o1_source_path( $id );

		// Try source path
		if ( ! wppa_is_file( $file ) || wppa_get_photo_item( $id, 'panorama' ) == '1' ) {
			$file = wppa_get_source_path( $id );
		}

		// Use photo path
		if ( ! wppa_is_file( $file ) ) {
			$file = wppa_get_photo_path( $id, false );
		}
	}

	// Not source requested
	else {
		$file = wppa_fix_poster_ext( wppa_get_photo_path( $id, false ), $id );
	}

	// If pdf, find image file
	if ( wppa_get_ext( $file ) == 'pdf' ) {
		$file = wppa_strip_ext( $file ) . '.' . wppa_get_photo_item( $id, 'ext' );

		// If no (poster) file exists, take the document-stub file
		if ( ! is_file( $file ) ) {
			$file = $documentstub;
			if ( is_file( $file ) ) {
				wppa_update_photo( $id, ['ext' => 'png'] );
			}
		}
	}

	// Max side
	$max_side = wppa_get_minisize();

	// Check file
	if ( ! wppa_is_file( $file ) ) return false;		// No file, fail
	$img_attr = getimagesize( $file );
	if ( ! $img_attr ) return false;				// Not an image, fail

	// Retrieve aspect
	$asp_attr = explode( ':', wppa_opt( 'thumb_aspect' ) );

	// Get output path
	$thumbpath = wppa_get_thumb_path( $id );

	// If already existing, save the filetime
	if ( wppa_is_file( $thumbpath ) ) {
		$thumbtime = wppa_filetime( $thumbpath );
	}
	else {
		$thumbtime = 0;
	}

	// Source size
	$src_size_w = $img_attr[0];
	$src_size_h = $img_attr[1];

	// Temp convert width if stereo
	if ( wppa_get_photo_item( $id, 'stereo' ) ) {
		$src_size_w /= 2;
	}

	// Mime type and thumb type
	$mime = $img_attr[2];
	$type = $asp_attr[2];

	// Source native aspect
	$src_asp = $src_size_h / $src_size_w;

	// Required aspect
	if ( $type == 'none' || ! $asp_attr[0] || ! $asp_attr[1] ) {
		$dst_asp = $src_asp;
	}
	else {
		$dst_asp = $asp_attr[0] / $asp_attr[1];
	}

	// Convert back width if stereo
	if ( wppa_get_photo_item( $id, 'stereo' ) ) {
		$src_size_w *= 2;
	}

	$done = false;

	// Enough memory?
	if ( wppa_can_resize( $file, $max_side ) ) {

		$frac = $max_side / max( $img_attr[0],$img_attr[1] );
		$perc = 100 * $frac;

		// Image Magick class?
		if ( false && class_exists( 'Imagick' ) && wppa_can_magick() && $type == 'none' ) {

			try {
				$im = new Imagick( $file );
				$im->thumbnailImage( $frac * $img_attr[0], $frac * $img_attr[1] );
				$iret = $im->writeImage( $thumbpath );
				$im->destroy();
			}
			catch (Exception $e) {
				wppa_log( 'err', 'Remake thumbnail raised exception: ',  $e->getMessage());
			}
			if ( $iret ) {
				wppa_log( 'fso', 'Magick class created ' . $thumbpath );
				$done = true;
			}
			else {
				wppa_log( 'fso', 'Magick class could not create ' . $thumbpath );
			}
		}

		// External Magick command?
		if ( ! $done && wppa_can_magick() && $type == 'none' ) {

			$cmd = 'convert ' . $file . ' -thumbnail ' . $perc . '% ' . $thumbpath;
			$iret = wppa_image_magick( $cmd );
			if ( $iret ) {
				wppa_log( 'fso', 'Magick command could not create ' . $thumbpath );
			}
			else {
				wppa_log( 'fso', 'Magick command created ' . $thumbpath );
				$done = true;
			}
		}

		// Classic GD
		if ( ! $done ) {

			// Create the source image
			switch ( $mime ) {	// mime type
				case 1: // gif
					$temp = @ wppa_imagecreatefromgif( $file );
					if ( $temp ) {
						$src = imagecreatetruecolor( $src_size_w, $src_size_h );
						imagecopy( $src, $temp, 0, 0, 0, 0, $src_size_w, $src_size_h );
						imagedestroy( $temp );
					}
					else $src = false;
					break;
				case 2:	// jpeg
					if ( ! function_exists( 'imagecreatefromjpeg' ) ) wppa_log( 'Error', 'Function imagecreatefromjpeg does not exist.' );
					$src = @ wppa_imagecreatefromjpeg( $file );
					break;
				case 3:	// png
					$src = @ wppa_imagecreatefrompng( $file );
					break;
				case 18: // webp
					$src = @ wppa_imagecreatefromwebp( $file );
					break;
				default:
					$src = null;
					break;
			}
			if ( ! $src ) {
				wppa_log( 'Error', 'Image file ' . $file . ' is corrupt while creating thmbnail' );
				return true;
			}

			// Compute the destination image size
			if ( $dst_asp < 1.0 ) {	// Landscape
				$dst_size_w = $max_side;
				$dst_size_h = round( $max_side * $dst_asp );
			}
			else {					// Portrait
				$dst_size_w = round( $max_side / $dst_asp );
				$dst_size_h = $max_side;
			}

			// Create the ( empty ) destination image
			$dst = imagecreatetruecolor( $dst_size_w, $dst_size_h );
			if ( $mime == 3 ) {	// Png, save transparancy
				imagealphablending( $dst, false );
				imagesavealpha( $dst, true );
			}

			// Fill with the required color
			$c = trim( strtolower( wppa_opt( 'bgcolor_thumbnail' ) ) );
			if ( $c != '#000000' ) {
				$r = hexdec( substr( $c, 1, 2 ) );
				$g = hexdec( substr( $c, 3, 2 ) );
				$b = hexdec( substr( $c, 5, 2 ) );
				$color = imagecolorallocate( $dst, $r, $g, $b );
				if ( $color === false ) {
					wppa_log( 'Err', 'Unable to set background color to: '.$r.', '.$g.', '.$b.' in wppa_create_thumbnail' );
				}
				else {
					imagefilledrectangle( $dst, 0, 0, $dst_size_w, $dst_size_h, $color );
				}
			}

			// Switch on what we have to do
			switch ( $type ) {
				case 'none':	// Use aspect from fullsize image
					$src_x = 0;
					$src_y = 0;
					$src_w = $src_size_w;
					$src_h = $src_size_h;
					$dst_x = 0;
					$dst_y = 0;
					$dst_w = $dst_size_w;
					$dst_h = $dst_size_h;
					break;
				case 'clip':	// Clip image to given aspect ratio
					if ( $src_asp < $dst_asp ) {	// Source image more landscape than destination
						$dst_x = 0;
						$dst_y = 0;
						$dst_w = $dst_size_w;
						$dst_h = $dst_size_h;
						$src_x = round( ( $src_size_w - $src_size_h / $dst_asp ) / 2 );
						$src_y = 0;
						$src_w = round( $src_size_h / $dst_asp );
						$src_h = $src_size_h;
					}
					else {
						$dst_x = 0;
						$dst_y = 0;
						$dst_w = $dst_size_w;
						$dst_h = $dst_size_h;
						$src_x = 0;
						$src_y = round( ( $src_size_h - $src_size_w * $dst_asp ) / 2 );
						$src_w = $src_size_w;
						$src_h = round( $src_size_w * $dst_asp );
					}
					break;
				case 'padd':	// Padd image to given aspect ratio
					if ( $src_asp < $dst_asp ) {	// Source image more landscape than destination
						$dst_x = 0;
						$dst_y = round( ( $dst_size_h - $dst_size_w * $src_asp ) / 2 );
						$dst_w = $dst_size_w;
						$dst_h = round( $dst_size_w * $src_asp );
						$src_x = 0;
						$src_y = 0;
						$src_w = $src_size_w;
						$src_h = $src_size_h;
					}
					else {
						$dst_x = round( ( $dst_size_w - $dst_size_h / $src_asp ) / 2 );
						$dst_y = 0;
						$dst_w = round( $dst_size_h / $src_asp );
						$dst_h = $dst_size_h;
						$src_x = 0;
						$src_y = 0;
						$src_w = $src_size_w;
						$src_h = $src_size_h;
					}
					break;
				default:		// Not implemented
					return false;
			}

			// Copy left half if stereo
			if ( wppa_get_photo_item( $id, 'stereo' ) ) {
				$src_w /= 2;
			}

			// Do the copy
			imagecopyresampled( $dst, $src, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );

			// Save the thumb
			$thumbpath = wppa_strip_ext( $thumbpath );
			switch ( $mime ) {	// mime type
				case 1:
					$full_thumbpath = $thumbpath . '.gif';
					wppa_imagegif( $dst, $full_thumbpath );
					break;
				case 2:
					$full_thumbpath = $thumbpath . '.jpg';
					wppa_imagejpeg( $dst, $full_thumbpath, wppa_opt( 'jpeg_quality' ) );
					break;
				case 3:
					$full_thumbpath = $thumbpath . '.png';
					wppa_imagepng( $dst, $full_thumbpath );
					break;
				case 18:
					$full_thumbpath = $thumbpath . '.webp';
					wppa_imagewebp( $dst, $full_thumbpath );
					break;
				default:
					wppa_log( 'err', sprintf( 'Unimplemented mime type %s encountered in wppa_create_thumbnail()', $mime ) );
			}
			$thumbpath = $full_thumbpath;

			wppa_log( 'fso', 'GD created ' . $thumbpath );
			$done = true;

			// Cleanup
			imagedestroy( $src );
			imagedestroy( $dst );
		}
	}

	// Too litlle memory
	if ( ! $done ) {
		wppa_copy( $file, $thumbpath );
	}

	// Optimize optionally
	wppa_optimize_image( wppa_get_thumb_path( $id ) );

	// Compute and save sizes
	wppa_get_thumbx( $id, 'force' );	// forces recalc x and y

	// Invalidate cache
	wppa_cache_photo( 'invalidate', $id );

	// If existing file not updated, return false
	if ( wppa_is_file( $thumbpath ) ) {
		$newtime = wppa_filetime( $thumbpath );
	}
	if ( $newtime == $thumbtime ) {
		return false;
	}
	return true;
}

// See if ImageMagick command exists
function wppa_is_magick( $command ) {
	if ( ! $command ) {
		return false;
	}
	if ( ! wppa_can_magick() ) {
		return false;
	}
	return is_file( rtrim( wppa_opt( 'image_magick' ), '/' ) . '/' . $command );
}

// Process ImageMagick command
function wppa_image_magick( $command ) {

	// Image magic enabled?
	if ( ! wppa_can_magick() ) {
		return '-9';
	}

	// Image Magick root dir
	$path = rtrim( wppa_opt( 'image_magick' ), '/' ) . '/';

	// Try to prepend 'magick' to the command if its not already there.
	// This is for forward compatibility, e.g. when 'magick' exists but 'convert' not.
	if ( wppa_is_magick( 'magick' ) && substr( $command, 0, 6 ) != 'magick' ) {
		$command = 'magick ' . $command;
	}
	$out  = array();
	$err  = 0;

	try {
		$run  = exec( escapeshellcmd( $path . $command ), $out, $err );
	}
	catch (Exception $e) {
		wppa_log( 'err', 'Exec imagick cmd raised exception: ',  $e->getMessage());
	}

	$logcom = $command;
	$logcom = str_replace( ABSPATH, '...', $logcom );
	$logcom = str_replace( wppa_opt( 'image_magick' ), '...', $logcom );

	wppa_log( 'Fso', 'Exec ' . $logcom . ' return status: ' . $err );

	if ( $err == '46' ) $err = '0';
	return $err;
}

// Convert .png's to .jpg's
//
// @1: photo id (integer) or pathname (string)
function wppa_convert_png_to_jpg( $arg ) {

	// Photo id given
	if ( is_numeric( $arg ) ) {

		// Does photo exist?
		$photo = wppa_cache_photo( $arg );
		if ( ! $photo ) {
			wppa_log( 'Err', 'Non existent photo: ' . $arg . ' in wppa_convert_png_to_jpg()' );
			return false;
		}
		$id 	= $photo['id'];
		$ext 	= wppa_get_photo_item( $id, 'ext' );
		$alb 	= wppa_get_photo_item( $id, 'album' );

		// Is it a photo with extension .png, or a multimedia or pdf with possible png posterfile?
		if ( strtolower( $ext ) == 'png' || wppa_is_multi( $id ) || wppa_is_pdf( $id ) ) {

			// Convert files
			wppa_convert_png_to_jpg( wppa_get_source_path( $id ) );
			wppa_convert_png_to_jpg( wppa_get_o1_source_path( $id ) );
			wppa_convert_png_to_jpg( wppa_get_photo_path( $id ) );
			wppa_convert_png_to_jpg( wppa_get_thumb_path( $id ) );

			// Fix local CDN files
			$cdn_files = wppa_cdn_files( $id );
			foreach( $cdn_files as $file ) {
				wppa_convert_png_to_jpg( $file );
			}

			// Make new filename
			if ( strpos( $photo['filename'], '.' ) ) {

				// Do not change filename for PDF, VIDEO and AUDIO
				if ( wppa_is_pdf( $id ) || wppa_is_multi( $id ) ) {
					$new_filename = $photo['filename'];
				}
				else {
					$new_filename = wppa_strip_ext( $photo['filename'] ) . '.jpg';
				}
			}
			else {
				$new_filename = $photo['filename'] . '.jpg';
			}

			// Make new ext, do not chnage for VIDEO and AUDIO
			if ( $ext == 'xxx' ) {
				$new_ext = $ext;
			}
			else {
				$new_ext = 'jpg';
			}

			// Update DB
			wppa_update_photo( $id, ['ext' => $new_ext, 'filename' => $new_filename] );
		}

		// Nothing to convert
		else {
			return false;
		}
	}

	// Is it a filepath with .png extension?
	elseif ( strtolower( wppa_get_ext( $arg ) ) == 'png' ) {
		$file 	= $arg;
		if ( ! wppa_is_file( $file ) ) {
			return false;
		}

		// Process the file
		$img = wppa_imagecreatefrompng( $file );
		if ( $img ) {
			$newfile = wppa_strip_ext( $file ) . '.jpg';
			if ( wppa_imagejpeg( $img, $newfile, wppa_opt( 'jpeg_quality' ) ) ) {
				wppa_unlink( $file );
				wppa_log( 'Fso', "Converted $file to $newfile" );
				return true;
			}
		}
		else {
			wppa_log( 'Err', 'Invalid .png file found in wppa_convert_png_to_jpg(): ' . $file );
			return false;
		}
	}

	// No, its not a .png file
	else {
		return false;
	}
}

// Convert a pdf into an album with all pages of the pdf converted to separate jpg files
function wppa_pdf_to_album( $id, $alb = 0, $page = 0 ) {
global $wpdb;

	// Where to log
	if ( wppa_is_cron() ) {
		$log = 'cron';
	}
	else {
		$log = 'misc';
	}

	if ( get_option( 'stop-pdfcnv-' . $id ) ) {
		wppa_log( $log, "Pdf conversion of item $id intentionally stopped, cron job aborted" );
		return;
	}
	wppa_log( $log, "Pdf conversion started for id={b}$id{/b}, alb={b}$alb{/b} at page={b}{$page}{/b}" );

	if ( ! wppa_is_pdf( $id ) ) {
		return "$id is not a pdf (wppa_pdf_to_album())";
	}

	if ( ! wppa_can_magick() ) {
		return 'The imageMagick externam command convert not available on the server (wppa_pdf_to_album())';
	}

	$pdf = wppa_strip_ext( wppa_get_source_path( $id ) ) . '.pdf';

	if ( ! wppa_is_file( $pdf ) ) {
		return "The expected pdf file $pdf not found (wppa_pdf_to_album())";
	}

	$name 		= wppa_get_photo_name( $id );
	$album_name = $name ? $name : wppa_strip_ext( wppa_get_photo_item( $id, 'filename' ) );
	$parent 	= wppa_get_photo_item( $id, 'album' );
	$parentdir 	= wppa_get_source_album_dir( $parent );
	$cnvparms 	= wppa_get_pdf_conv_parms( $id );
	$paging 	= intval( $cnvparms['pagtype'] );

	// Create the album if it does not exist yet (from previous conversion)
	if ( ! $alb ) {
		$alb 	= $cnvparms['album'];
		if ( $alb ) wppa_update_album( $alb, ['p_order_by' => '1'] );
	}

	if ( ! $alb ) {
		$alb 	= wppa_create_album_entry( ['name' => $name, 'a_parent' => $parent, 'p_order_by' => '1'] );
		if ( ! $alb ) return "Could no create album";
		wppa_update_pdf_conv_parms( $id, ['album' => $alb] );
	}

	// Create the album source dir if it does not exist yet
	$albdir = wppa_get_source_album_dir( $alb );
	if ( ! wppa_is_dir( $albdir ) ) {
		@ wppa_mktree( $albdir );
	}
	if ( ! wppa_is_dir( $albdir ) ) {
		return 'Could not create source directory (wppa_pdf_to_album())';
	}

	// Postpone cron
	wppa_update_option( 'wppa_maint_ignore_cron', 'yes' );

	// Do all pages until error or timeout
	$err  = false;

	// Use the owner of the album even when its in cron
	$owner = wppa_get_album_item( $alb, 'owner' );

	// Expected album = $alb. Could alreay exist in a subalbum of $alb
	$potentialbs = explode( '.', wppa_alb_to_enum_children( $alb ) );

	// Do all pages, start at page (arg) $page
	while ( ! $err && ! wppa_is_time_up() ) {

		// Try making the jpg from the pdf
		$bookpage = $page + 1;
		$jpgfilename = basename( wppa_strip_ext( wppa_get_source_path( $id ) ) . "-page-$bookpage.jpg" );

		// See if photo entry exists in one or more potemtial albums
		$pot = implode( ',', $potentialbs );
		$inalbs = $wpdb->get_col( $wpdb->prepare( "SELECT album FROM $wpdb->wppa_photos WHERE filename = %s AND album IN ($pot)", $jpgfilename ) );

		// If not present yet, do it in the major target album only
		if ( empty( $inalbs ) ) {
			$inalbs = [$alb];
		}

		// Now do it in all $inalbs
		foreach( $inalbs as $targetalb ) {

			// Find target source dir path
			$targetalbdir = wppa_get_source_album_dir( $targetalb );

			// Find target jpg path
			$jpg = $targetalbdir . '/' . $jpgfilename;

			// Do the extraction
			$command = "convert -density 200 $pdf"."[$page] -quality 90 -background white -alpha remove $jpg";
			wppa_image_magick( $command );

			// Possible error done
			$err = ! wppa_is_file( $jpg );

			// If no error, proceed
			if ( ! $err ) {

				// Make the name of the Page
				switch( $paging ) {
					case 0: // Second pic = page #1
						$pagename = sprintf( __( 'Page %s', 'wp-photo-album-plus' ), $bookpage - 1 );
						break;
					case 1: // Second pic = page #2
						$pagename = sprintf( __( 'Page %s', 'wp-photo-album-plus' ), $bookpage );
						break;
					case 10: // Second pic = Cover - page #1
						switch ( $bookpage ) {
							case 1:
								$pagename = __( 'Cover - Cover', 'wp-photo-album-plus' );
								break;
							case 2:
								$pagename = __( 'Cover - Page 1', 'wp-photo-album-plus' );
								break;
							default:
								$pagename = sprintf( __( 'Page %d-%d', 'wp-photo-album-plus' ), 2 * $page - 2, 2 * $page - 1 );
								break;
						}
						break;
					case 11: // Second pic = Pge 2-3
						switch ( $bookpage ) {
							case 1:
								$pagename = __( 'Cover - Page 1', 'wp-photo-album-plus' );
								break;
							default:
								$pagename = sprintf( __( 'Page %d-%d', 'wp-photo-album-plus' ), 2 * $page, 2 * $page + 1 );
								break;
						}
						break;
					case 12: // Second pic = Pge 4-5
						$pagename = sprintf( __( 'Page %d-%d', 'wp-photo-album-plus' ), 2 * $page + 2, 2 * $page + 3 );
						break;
				}

				// See if entry already exists
				$pho = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE album = %d AND filename = %s", $targetalb, $jpgfilename ) );

				// Update db entry
				if ( $pho ) {
					wppa_update_photo( $pho, ['name' => $pagename, 'p_order' => $bookpage, 'owner' => $owner] );
				}

				// Create new entry
				else {
					$pho = wppa_create_photo_entry(['name' => $pagename, 'album' => $alb, 'filename' => basename( $jpg ), 'p_order' => $bookpage, 'owner' => $owner]);
					wppa_invalidate_treecounts( $alb );
				}

				// Failed?
				if ( ! $pho ) {
					return "Could not add photo entry for pdf page $bookpage (wppa_pdf_to_album())";
				}

				// Make the files
				ob_start();
				wppa_make_the_photo_files( $jpg, $pho, 'jpg', true, false );
				ob_end_clean();

				wppa_log( $log, "Page {b}$bookpage{/b} of pdf {b}$name{/b} converted" );

				// Update paging parms
				wppa_update_pdf_conv_parms( $id, ['pagesdone' => $page] );

				$page++;

				if ( get_option( 'stop-pdfcnv-' . $id ) ) {
					wppa_log( $log, "Pdf conversion of item $id intentionally stopped" );
					return;
				}
			}
		}
	}

	if ( $page == 0 ) {
		return '<span style="color:red">' . sprintf( __( 'Could not covert %s to album', 'wp-photo-album-plus' ), basename( $pdf ) ) . '</span>';
	}

	if ( wppa_is_time_up() ) {
		wp_schedule_single_event( time() + 10, 'wppa_pdf_to_album', [$id, $alb, $page, $paging] );
		$p = $page++;
		wppa_log( 'cron', "{b}wppa_pdf_to_album{/b} scheduled to run in 10 seconds for album {b}$alb{/b} starting at page {b}$p{/b}" );
		return '<span style="color:orange">'. sprintf( __( 'Time out converting, %d pages done. Will continue at the background', 'wp-photo-album-plus'), $page ) . '</span>';
	}

	// Restart cron
	wppa_update_option( 'wppa_maint_ignore_cron', 'no' );

	wppa_log( $log, "Conversion of {b}$album_name{/b} ($alb) completed. Total {b}$page{/b} pages" );
	$url 	= wppa_ea_url( $alb );
	$link 	= '<a href="' . $url . '">' . $album_name . '</a>';
	wppa_update_pdf_conv_parms( $id, ['ready' => true] );
	return sprintf( __( 'Conversion completed. %d pages total', 'wp-photo-album-plus' ), $page ) . ' ' . sprintf( __( 'See album %s', 'wp-photo-album-plus' ), $link );
}

add_action( 'wppa_pdf_to_album', 'wppa_pdf_to_album', 10, 4 );

// find out if a pdf has more than one Page
// No error reporting, just return false on error
function wppa_is_pdf_multiple( $id ) {

	$file = wppa_get_source_path( $id );
	if ( wppa_get_ext( $file ) != 'pdf' ) return false;
	if ( ! wppa_can_magick() ) return false;

	// Try converting the second Page
	$tempfile = WPPA_UPLOAD_PATH . '/' . md5( microtime( true ) ) . '.jpg';
	wppa_image_magick( "convert -density 200 $file"."[1] -quality 90 -background white -alpha remove $tempfile" );

	$result = wppa_is_file( $tempfile );
	if ( $result ) wppa_unlink( $tempfile );

	return $result;
}