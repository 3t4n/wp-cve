<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Strip EXIF Data without Changing Image Data
function stillbe_iqc_strip_exif( $filename ) {

	if( ! file_exists( $filename ) ) {
		return false;
	}

	$dir = dirname( $filename );
	$i   = 1;
	$tempname = path_join( $dir, "___sb_csp_strip_exif_temp_{$i}.jpg" );
	while( file_exists( $tempname ) ) {
		++$i;
		$tempname = path_join( $dir, "___sb_csp_strip_exif_temp_{$i}.jpg" );
	}

	// 
	

	// Open the input file for binary reading
	$f1 = fopen( $filename, 'rb' );

	// Open the output file for binary writing
	$f2 = fopen( $tempname, 'wb' );

	// Find EXIF marker
	$s = fread( $f1, 2 );

	while( $s ) {
		if( ! ctype_cntrl( $s ) && 1 < strlen( $s ) ) {
			$word = unpack( 'ni', $s )['i'];
			if( $word == 0xFFE1 ) {
				// Read length (includes the word used for the length)
				$s   = fread( $f1, 2 );
				$len = unpack( 'ni', $s )['i'];
				// Skip the EXIF info
				fread( $f1, $len - 2 );
				break;
			}
		}
		fwrite( $f2, $s, 2 );
		$s = fread( $f1, 2 );
	}

	// Write the rest of the file
	$s = fread( $f1, 4096 );

	while( $s ) {
		fwrite( $f2, $s, strlen( $s ) );
		$s = fread( $f1, 4096 );
	}

	// Closing
	fclose( $f1 );
	fclose( $f2 );

	// Replace the original file with a temporary file
	if( wp_filesize( $tempname ) > 0 ) {
	//	unlink( $filename );
		return rename( $tempname, $filename );
	}

	return false;

}