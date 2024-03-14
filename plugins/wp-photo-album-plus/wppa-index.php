<?php
/* wppa-index.php
* Package: wp-photo-album-plus
*
* Contains all indexing functions
* Version: 8.4.05.001
*
*
*/

// Add an item to the index
//
// @1: string. Type. Can be 'album' os 'photo'
// @2: int. Id. The id of the album or the photo.
//
// The actual addition of searchable words and ids into the index db table is handled in a cron job.
// If this function is called real-time, it simply notifys cron to scan all albums or photos on missing items.
function wppa_index_add( $type, $id, $force = false ) {
global $wpdb;
global $acount;
global $pcount;

	if ( $type == 'album' ) {

		// Make sure this album will be re-indexed some time if we are not a cron job
		if ( ! wppa_is_cron() && ! $force ) {
			wppa_update_album( $id, ['indexdtm' => ''] );
		}

		// If there is a cron job running adding to the index and this is not that cron job, do nothing, unless force
		if ( wppa_get_option( 'wppa_remake_index_albums_user' ) == 'cron-job' && ! wppa_is_cron() && ! $force ) {
			wppa_log( wppa_logtype( 'wppa_remake_index_albums' ), 'Exit a1, already a cron running (wppa_index_add)' );
			return;
		}

		// If no user runs the remake proc, start it as cron job
		if ( ! wppa_get_option( 'wppa_remake_index_albums_user' ) && ! $force ) {
			wppa_schedule_maintenance_proc( 'wppa_remake_index_albums' );
			wppa_log( wppa_logtype( 'wppa_remake_index_albums' ), 'Exit a2, Started as cron job (wppa_index_add)' );
			return;
		}

		// Find the raw text, all qTranslate languages
		$words = wppa_index_get_raw_album( $id );

		// Convert to santized array of indexable words
		$words = wppa_index_raw_to_words( $words );

		// Process all the words to see if they must be added to the index
		$words_added = '';
		foreach ( $words as $word ) {

			// Get the row of the index table where the word is registered.
			$indexline = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_index WHERE slug = %s", $word ), ARRAY_A );

			// If this line does not exist yet, create it with only one album number as data
			if ( ! $indexline ) {
				wppa_create_index_entry( array( 'slug' => $word, 'albums' => $id ) );
				wppa_log( 'idx', 'Adding index slug {b}{span style="color:darkred"}' . $word . '{/span}{/b} for album {b}' . $id . '{/b}' );
				$words_added .= $word . ' ';
			}

			// Index line already exitst, process this album id for this word
			else {

				// Convert existing album ids to an array
				$oldalbums = wppa_index_string_to_array( $indexline['albums'] );

				// If not in yet...
				if ( ! in_array( $id, $oldalbums ) ) {

					// Add it
					$oldalbums[] = $id;

					// Covert to string again
					$newalbums = wppa_index_array_to_string( $oldalbums );

					// Update db
					wppa_update_index( $indexline['id'], ['albums' => $newalbums] );

					$words_added .= $word . ' ';
				}
			}
		}
		wppa_update_album( $id, ['indexdtm' => time()] );

		$acount++;
	}

	elseif ( $type == 'photo' ) {

		// Make sure this photo will be re-indexed some time if we are not a cron job
		if ( ! wppa_is_cron() && ! $force ) {
			wppa_update_photo( $id, ['indexdtm' => ''] );
		}

		// If there is a cron job running adding to the index and this is not that cron job, do nothing
		if ( wppa_get_option( 'wppa_remake_index_photos_user' ) == 'cron-job' && ! wppa_is_cron() && ! $force ) {
			wppa_log( wppa_logtype( 'wppa_remake_index_photos' ), 'Exit p1, already a cron running' );
			return;
		}

		// If no user runs the remake proc, start it as cron job
		if ( ! wppa_get_option( 'wppa_remake_index_photos_user' ) && ! $force ) {
			wppa_schedule_maintenance_proc( 'wppa_remake_index_photos' );
			wppa_log( wppa_logtype( 'wppa_remake_index_photos' ), 'Exit p2, Started as cron job' );
			return;
		}

		// Find the raw text, all qTranslate languages
		$words = wppa_index_get_raw_photo( $id );

		// Convert to santized array of indexable words
		$words = wppa_index_raw_to_words( $words );

		// Process all the words to see if they must be added to the index
		$words_added = '';
		foreach ( $words as $word ) {

			// Get the row of the index table where the word is registered.
			$indexline = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_index WHERE slug = %s", $word ), ARRAY_A );

			// If this line does not exist yet, create it with only one album number as data
			if ( ! $indexline ) {
				wppa_create_index_entry( array( 'slug' => $word, 'photos' => $id ) );
				wppa_log( 'idx', 'Adding index slug {b}{span style="color:darkred"}' . $word . '{/span}{/b} for photo {b}' . $id . '{/b}' );
				$words_added .= $word . ' ';
			}

			// Index line already exitst, process this photo id for this word
			else {

				// Convert existing album ids to an array
				$oldphotos = wppa_index_string_to_array( $indexline['photos'] );

				// If not in yet...
				if ( ! in_array( $id, $oldphotos ) ) {

					// Add it
					$oldphotos[] = $id;

					// Covert to string again
					$newphotos = wppa_index_array_to_string( $oldphotos );

					// Update db
					wppa_update_index( $indexline['id'], ['photos' => $newphotos] );

					$words_added .= $word . ' ';
				}
			}

		}
		wppa_update_photo( $id, ['indexdtm' => time()] );
		$pcount++;
	}

	else {

		// Log error
		wppa_log( 'err, unimplemented type {b}' . $type . '{/b} in wppa_index_add().' );
	}

	return $words_added;
}

// Convert raw data string to indexable word array
// Sanitizes any string and clips it into an array of potential slugs in the index db table.
//
// @1: string. Any test string may contain all kind of garbage.
//
function wppa_index_raw_to_words( $xtext, $no_skips = false, $minlen = '3', $no_excl = true ) {

	// Find chars to be replaced by delimiters (spaces)
	$ignore = array( 	'"', "'", '`', '\\', '>', '<', ',', ':', ';', '?', '=', '_',
						'[', ']', '(', ')', '{', '}', '..', '...', '....', "\n", "\r",
						"\t", '.jpg', '.png', '.gif', '&#039', '&amp',
						'w#cc0', 'w#cc1', 'w#cc2', 'w#cc3', 'w#cc4', 'w#cc5', 'w#cc6', 'w#cc7', 'w#cc8', 'w#cc9',
						'w#cd0', 'w#cd1', 'w#cd2', 'w#cd3', 'w#cd4', 'w#cd5', 'w#cd6', 'w#cd7', 'w#cd8', 'w#cd9',
						'#',
					);
	if ( wppa_switch( 'index_ignore_slash' ) ) {
		$ignore[] = '/';
	}
	if ( $no_excl ) {
		$ignore[] = '!';
	}

	// Find words to skip
	$skips = $no_skips ? array() : wppa_get_option( 'wppa_index_skips', array() );

	// Find minimum token length
	$minlen = wppa_opt( 'search_min_length' );

	// Init results array
	$result = array();

	// Process text
	if ( $xtext ) {

		// Sanitize
		$text = $xtext;

		// Convert to real chars/symbols
		$text = html_entity_decode( $text );

		// strip style and script tags inclusive content
		$text = wppa_strip_tags( $text, 'script&style' );

		// Make sure <td>word1</td><td>word2</td> will not endup in 'word1word2', but in 'word1' 'word2'
		$text = str_replace( '>', '> ', $text );

		// Now strip remaining tags without stripping the content
		$text = strip_tags( $text );

		// Strip qTranslate language shortcodes: [:*]
		$text = preg_replace( '/\[:..\]|\[:\]/', ' ', $text );

		// Replace ignorable chars and words by delimiters ( $ignore is an array )
		$text = str_replace( $ignore, ' ', $text );

		// Remove accents
		$text = remove_accents( $text );

		// Use downcase only
		$text = strtolower( $text );

		// Trim
		$text = trim( $text );
		$text = trim( $text, " ./-" );

		// Replace multiple space chars by one space char
		while ( strpos( $text, '  ' ) ) {
			$text = str_replace( '  ', ' ', $text );
		}

		// Convert to array
		$words = explode( ' ', $text );

		// Decide for each word if it is in
		foreach ( $words as $word ) {

			// Trim word
			$word = trim( $word );
			$word = trim( $word, " ./-" );

			// If lare enough and not a word to skip, use it: copy to array $result
			if ( strlen( $word ) >= $minlen && ! in_array( $word, $skips ) ) {
				$result[] = $word;
			}

			// If the word contains (a) dashe(s), also process the fractions before/between/after the dash(es)
			if ( strpos( $word, '-' ) !== false ) {

				// Break word into fragments
				$fracts = explode( '-', $word );
				foreach ( $fracts as $fract ) {

					// Trim
					$fract = trim( $fract );
					$fract = trim( $fract, " ./-" );

					// If large enough and not a word to skip, use it: copy to array $result
					if ( strlen( $fract ) >= $minlen && ! in_array( $fract, $skips ) ) {
						$result[] = $fract;
					}
				}
			}
		}
	}

	// Remove numbers optionaly
	if ( wppa_switch( 'search_numbers_void' ) ) {
		foreach ( array_keys( $result ) as $key ) {

			// Strip leading zeroes
			$t = ltrim( $result[$key], '0' );

			// If nothing left (zoroes only) or numeric, discard it
			if ( ! $t || is_numeric( $t ) ) {
				unset( $result[$key] );
			}
		}
	}

	// Remove dups and sort
	$result = array_unique( $result );

	// Done !
	return $result;

}

// Expand compressed string
function wppa_index_string_to_array( $string ) {

	// Anything?
	if ( ! $string ) return array();

	// Any ranges?
	if ( strstr( $string, '..' ) ) {
		$temp = explode(',', $string);
		$result = array();
		foreach ( $temp as $t ) {

			// Single value
			if ( ! strstr( $t, '..' ) ) {
				$result[] = $t;
			}

			// Range
			else {
				$range = explode( '..', $t );
				$from = $range['0'];
				$to = $range['1'];
				if ( $from >= $to ) {
					wppa_log( 'err', 'Illegal range: ' . $t );
					$result[] = $range['0'];
					$result[] = $range['1'];
				}
				else while ( $from <= $to ) {
					$result[] = strval($from);
					$from++;
				}
			}
		}
	}

	// No
	else {
		$result = explode(',', $string);
	}

	// Sort
	if ( ! sort( $result, SORT_NUMERIC ) ) {
		wppa_log( 'err', 'Sort index failed' );
	}

	// Remove dups
	$result = array_unique( $result );

	return $result;
}

// Compress array ranges and convert to string
function wppa_index_array_to_string( $array ) {

	// Remove empty elements
	foreach( array_keys( $array ) as $idx ) {
		if ( ! $array[$idx] ) {
			unset( $array[$idx] );
		}
	}

	// Remove dups and sort
	$array = array_unique( $array, SORT_NUMERIC );
	sort( $array, SORT_NUMERIC );

	// Build string
	$result = '';
	$lastitem = '-1';
	$isrange = false;
	foreach ( $array as $item ) {
		if ( $item == $lastitem+'1' ) {
			$isrange = true;
		}
		else {
			if ( $isrange ) {	// Close range
				$result .= '..'.$lastitem.','.$item;
				$isrange = false;
			}
			else {				// Add single item
				$result .= ','.$item;
			}
		}
		$lastitem = $item;
	}
	if ( $isrange ) {	// Don't forget the last if it ends in a range
		$result .= '..'.$lastitem;
	}
	$result = trim($result, ',');
	return $result;
}

// Request Re-index an edited item
function wppa_index_update( $type, $id ) {

	if ( ! $id ) {
		wppa_log( 'err', 'Missing id in wppa_index_update()' );
		return;
	}

	switch( $type ) {
		case 'album':
			wppa_update_album( $id, ['indexdtm' => ''] );
			wppa_schedule_maintenance_proc( 'wppa_remake_index_albums' );
			break;
		case 'photo':
			wppa_update_photo( $id, ['indexdtm' => ''] );
			wppa_schedule_maintenance_proc( 'wppa_remake_index_photos' );
			break;
		default:
			wppa_log( 'err', 'Unimplemented type in wppa_index_update()' );
	}
}

// The words in the new photo description should be left out
function wppa_index_compute_skips() {

	$user_skips 	= wppa_opt( 'search_user_void' );
	$system_skips 	= 'w#name,w#filename,w#owner,w#displayname,w#id,w#tags,w#cats,w#timestamp,w#modified,w#views,w#amx,w#amy,w#amfs,w#url,w#hrurl,w#tnurl,w#pl';
	$words 			= wppa_index_raw_to_words( wppa_opt( 'newphoto_description' ) . ',' . $user_skips . ',' . $system_skips, 'noskips' );
	$result 		= array_unique( $words );

	wppa_update_option( 'wppa_index_skips', $result );
}

// Find the raw text for indexing album, all qTranslate languages
//
// @1: int: album id
function wppa_index_get_raw_album( $id ) {

	// Get album data
	$album = wppa_cache_album( $id );

	// Name
	$words = wppa_get_album_name( $id, array( 'translate' => false ) );

	// Description
	if ( wppa_switch( 'search_desc' ) ) {
		$words .= ' ' . wppa_get_album_desc( $id, array( 'translate' => false ) );
	}

	// Categories
	if ( wppa_switch( 'search_cats' ) ) {
		$words .= ' ' . $album['cats'];
	}

	// Strip tags, but prevent cluttering
	$words = str_replace( '<', ' <', $words );
	$words = strip_tags( $words );

	// Done!
	return $words;
}

function wppa_index_get_raw_photo( $id ) {
global $wpdb;

	// Get item data
	$thumb = wppa_cache_photo( $id );
	
	// Photo gone?
	if ( ! $thumb ) {
		return '';
	}

	// Name
	$words = wppa_get_photo_name( $id, array( 'translate' => false ) );

	// Description
	if ( wppa_switch( 'search_desc' ) ) {
		$words .= ' ' . wppa_get_photo_desc( $id, array( 'translate' => false ) );
	}

	// Tags
	if ( wppa_switch( 'search_tags' ) ) {
		$words .= ' ' . $thumb['tags'];
	}

	// Comments
	if ( wppa_switch( 'search_comments' ) ) {
		$coms = $wpdb->get_results($wpdb->prepare( "SELECT comment FROM $wpdb->wppa_comments WHERE photo = %s AND status = 'approved'", $thumb['id'] ), ARRAY_A );
		if ( $coms ) {
			foreach ( $coms as $com ) {
				$words .= ' ' . stripslashes( $com['comment'] );
			}
		}
	}

	// Strip tags, but prevent cluttering
	$words = str_replace( '<', ' <', $words );
	$words = strip_tags( $words );

	// Done!
	return $words;
}
