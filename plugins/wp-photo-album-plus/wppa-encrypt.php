<?php
/* wppa-encrypt.php
* Package: wp-photo-album-plus
*
* Contains all ecryption/decryption logic
* Version 8.6.03.004
*
*/

// Find a unique crypt
function wppa_get_unique_crypt() {
global $wpdb;

	$result = '0';
	while ( wppa_is_int( $result ) ) {
		$result = substr( md5( microtime( true ) ), rand( 0, 16 ), 16 );
	}
	return $result;
}

// Convert photo id to crypt
function wppa_encrypt_photo( $id ) {

	// If enumeration, split
	if ( strpos( $id, '.' ) !== false ) {
		$ids = explode( '.', $id );
		foreach( array_keys( $ids ) as $key ) {
			if ( strlen( $ids[$key] ) ) {
				$ids[$key] = wppa_encrypt_photo( $ids[$key] );
			}
		}
		$crypt = implode( '.', $ids );
		return $crypt;
	}

	// Encrypt single item
	if ( wppa_is_posint( $id ) ) {
		$crypt = wppa_get_photo_item( $id, 'crypt' );
	}
	else {
		$crypt = $id; 	// Already encrypted
	}

	if ( ! $crypt ) {
		$crypt = 'yyyyyyyyyyyyyyyy';
	}
	return $crypt;
}

// Convert album id to crypt
function wppa_encrypt_album( $album ) {

	// Encrypted album enumeration must always be expanded
	$album = wppa_expand_enum( $album );

	// Decompose possible album enumeration
	$album_ids 		= strpos( $album, '.' ) === false ? array( $album ) : explode( '.', $album );
	$album_crypts 	= array();
	$i 				= 0;

	// Process all tokens
	while ( $i < count( $album_ids ) ) {
		$id = $album_ids[$i];

		// Check for existance of album, otherwise return dummy
		if ( wppa_is_posint( $id ) && ! wppa_album_exists( $id ) ) {
			$id= '999999';
		}

		switch ( $id ) {
			case '-3':
				$crypt = wppa_get_option( 'wppa_album_crypt_3', false );
				break;
			case '-2':
				$crypt = wppa_get_option( 'wppa_album_crypt_2', false );
				break;
			case '-1':
				$crypt = wppa_get_option( 'wppa_album_crypt_1', false );
				break;
			case '':
			case '0':
				$crypt = wppa_get_option( 'wppa_album_crypt_0', false );
				break;
			case '999999':
				$crypt = wppa_get_option( 'wppa_album_crypt_9', false );
				break;
			default:
				if ( wppa_is_posint( $id ) ) {
					$crypt = wppa_get_album_item( $id, 'crypt' );
				}
				else {
					$crypt = $id; 	// Already encrypted
				}
		}
		$album_crypts[$i] = $crypt;
		$i++;
	}

	// Compose result
	$result = implode( '.', $album_crypts );

	if ( ! $result ) {
		$result = 'xxxxxxxxxxxxxxxx';
	}
	return $result;
}

// Decrypt or find photo is(s) from names in urls
function wppa_decode_photo( $photo ) {
global $wpdb;

	// Init
	$result = false;

	// If not mandatoty cryptic, try anything else first
	if ( /* ! wppa_switch( 'refuse_unencrypted' ) || */ is_admin() ) {

		// Leave '', '0' and false untouched
		if ( ! $photo ) return $photo;

		// Leave any single integer string untouched
		elseif ( wppa_is_int( $photo ) ) return $photo;

		// Leave an enumeration of integers untouched
		elseif ( wppa_is_enum( $photo ) ) return $photo;

		// Try a single photoname. Can not be an enumeration because names may contain dots
		$id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE sname = %s LIMIT 1", $photo ) );
		if ( $id ) {
			$result = $id;
		}
	}

	// Nothing yet, go for (enumeration of) cryptic
	if ( ! $result ) {
		$result = trim( _wppa_decode_photo( $photo ), '.' );
	}

	// Done
	return $result;
}
function _wppa_decode_photo( $photo ) {
global $wpdb;
static $cache;
static $hits;

	// Check for non numeric enum
	if ( $photo && strpos( $photo, '.' ) !== false ) {

		$result = '';
		$parray = explode( '.', $photo );
		foreach( $parray as $p ) {

			if ( $p == '' ) {
				$result .= '.';
			}
			else {
				$id = _wppa_decode_photo( $p );
				if ( $id !== false ) {
					$result .= $id . '.';
				}
			}
		}

		return $result;
	}

	// Single item
	else {

		// Init cache
		if ( ! $cache ) {
			$cache = array();
			$hits = 0;
		}

		// Look in cache
		if ( isset( $cache[$photo] ) ) {
			$hits++;
			return $cache[$photo];
		}

		// Phase 1: check encryption
		$p = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE crypt = %s", $photo ) );
		if ( $p ) {
			$result = $p;
			$cache[$photo] = $p;
			return $result;
		}
		else {
			return false;
		}
	}

	// Done
	return false;
}

// Album name to id. One only because of possible . in name or try to expand enum
function wppa_decode_album( $album, $strict = true ) {
global $wpdb;

	// Init
	$result = false;

	// If not mandatoty cryptic, try anything else first
	if ( /* ! wppa_switch( 'refuse_unencrypted' ) || */ ! $strict || is_admin() ) {

		// Leave '', '0' and false untouched
		if ( ! $album ) return $album;

		// Leave any single integer string untouched
		elseif ( wppa_is_int( $album ) ) return $album;

		// Leave an enumeration of integers untouched
		elseif ( wppa_is_enum( $album ) ) return $album;

		// If from shortcode, the name may start with a $
		if ( substr( $album, 0, 1 ) == '$' ) {
			$album = substr( $album, 1 );
		}

		// Try a single albumname. Can not be an enumeration because names may contain dots
		$id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE sname = %s LIMIT 1", $album ) );
		if ( $id ) {
			$result = $id;
		}
	}

	// Nothing yet, go for (enumeration of) cryptic
	if ( ! $result ) {
		$result = trim( _wppa_decode_album( $album ), '.' );
	}

	// Done
	return $result;
}
function _wppa_decode_album( $album ) {
global $wpdb;
static $cache;
static $hits;

	// Check for non numeric enum
	if ( $album && strpos( $album, '.' ) !== false ) {

		$result = '';
		$aarray = explode( '.', $album );
		foreach( $aarray as $a ) {

			if ( $a == '' ) {
				$result .= '.';
			}
			else {
				$id = _wppa_decode_album( $a );
				if ( $id !== false ) {
					$result .= $id . '.';
				}
			}
		}

		return $result;
	}

	// Single item
	else {

		// Init cache
		if ( ! $cache ) {
			$cache = array();
			$cache[wppa_get_option( 'wppa_album_crypt_9' )] = false;
			$cache[wppa_get_option( 'wppa_album_crypt_0' )] = '0';
			$cache[wppa_get_option( 'wppa_album_crypt_1' )] = '-1';
			$cache[wppa_get_option( 'wppa_album_crypt_2' )] = '-2';
			$cache[wppa_get_option( 'wppa_album_crypt_3' )] = '-3';
		}

		// Look in cache
		if ( isset( $cache[$album] ) ) {
			$hits++;
			return $cache[$album];
		}

		// Phase 1: check encryption
		$a = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE crypt = %s", $album ) );
		if ( $a ) {
			$result = $a;
			$cache[$album] = $a;
			return $result;
		}
		else {
			return false;
		}
	}

	// Done
	return false;
}

// Encrypt a full url
function wppa_encrypt_url( $url ) {

	// Querystring present?
	if ( strpos( $url, '?' ) === false ) {
		return $url;
	}

	// Has it &amp; 's ?
	if ( strpos( $url, '&amp;' ) === false ) {
		$hasamp = false;
	}
	else {
		$hasamp = true;
	}

	// Disassemble url
	$temp = explode( '?', $url );

	// Has it a querystring?
	if ( count( $temp ) == '1' ) {
		return $url;
	}

	// Disassemble querystring
	$qarray = explode( '&', str_replace( '&amp;', '&', $temp['1'] ) );

	// Search and replace album and photo ids by crypts
	$i = 0;
	while ( $i < count( $qarray ) ) {
		$item = $qarray[$i];
		$t = explode( '=', $item );
		if ( isset( $t['1'] ) ) {
			switch ( $t['0'] ) {
				case 'wppa-album':
				case 'album':
					if ( ! $t['1'] ) $t['1'] = '0';
					$t['1'] = wppa_encrypt_album( $t['1'] );
					break;
				case 'wppa-photo':
				case 'wppa-photos':
				case 'photo':
					$t['1'] = wppa_encrypt_photo( $t['1'] );
					break;
				default:
					break;
			}
		}
		$item = implode( '=', $t );
		$qarray[$i] = $item;
		$i++;
	}

	// Re-assemble url
	$temp['1'] = implode( '&', $qarray );
	$newurl = implode( '?', $temp );
	if ( $hasamp ) {
		$newurl = str_replace( '&', '&amp;', $newurl );
	}

	return $newurl;
}

