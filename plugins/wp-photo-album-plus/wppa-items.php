<?php
/* wppa-items.php
* Package: wp-photo-album-plus
*
* Contains functions to retrieve album and photo items
* Version: 8.6.03.004
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Bring album into cache and return album data
function wppa_cache_album( $id, $data = '' ) {
global $wpdb;
static $cache;

	if ( $id <= '-9' ) {
		return false;
	}

	if ( ! is_array( $cache ) ) {
		$cache = array();
	}

	// Init. If there are less than 1000 albums, cache them all on beforehand.
	// This reduces the number of queries for albums to two.
	// Only for front-end
	if ( ! count( $cache ) ) {

		// Get max ... albums
		$max = wppa_opt( 'pre_cache_albums' );
		$allalbs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums LIMIT %d", $max ), ARRAY_A );

		// Store in cache
		foreach( $allalbs as $album ) {			// Add multiple
			if ( isset( $album['id'] ) ) {		// Looks valid
				$cache[$album['id']] = $album;
			}
		}
	}

	// Action?
	if ( $id == 'invalidate' ) {
		if ( $data ) {
			if ( isset( $cache[$data] ) ) {
				unset( $cache[$data] );
			}
			return false;
		}
		else {
			$cache = array();
			return false;
		}
	}

	// Add
	if ( $id == 'add' ) {
		if ( ! is_array( $data ) ) return;
		foreach( $data as $item ) {
			if ( is_array( $item ) && isset( $item['id'] ) ) {
				$cache[$item['id']] = $item;
			}
			else {
				wppa_log( 'err', 'Invalid data in wppa_cache_album' );
				$cache = array();
				return false;
			}
		}
		return false;
	}

	// Valid id?
	if ( ! wppa_is_int( $id ) || $id < '1' ) {
		wppa_log( 'err', 'Invalid arg wppa_cache_album(' . serialize( $id ) . ')' );
		return false;
	}

	// In cache?
	if ( isset( $cache[$id] ) ) {
		return $cache[$id];
	}

	// Not in cache, do query
	$cache[$id] = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums WHERE id = %s", $id ), ARRAY_A );

	// Found one?
	if ( isset( $cache[$id] ) ) {
		return $cache[$id];
	}
	else {
		return false;
	}
}

// Bring photo into cache and return photo data
function wppa_cache_photo( $id, $data = '' ) {
global $wpdb;
static $cache;

	// $id?
	if ( ! $id ) {
		return false;
	}

	// Init
	if ( ! is_array( $cache ) ) {
		$cache = array();
	}

	// Load max ... photos order by id descending, so you have the most recent
	if ( ! count( $cache ) ) {

		// Get max ... photos
		$max = wppa_opt( 'pre_cache_photos' );
		$allpho = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos ORDER BY id DESC LIMIT %d", $max ), ARRAY_A );

		// Store in cache
		foreach( $allpho as $photo ) {			// Add multiple
			if ( isset( $photo['id'] ) ) {		// Looks valid
				$cache[$photo['id']] = $photo;
			}
		}
	}

	// Invalidate ?
	if ( $id == 'invalidate' ) {
		if ( $data ) {
			if ( isset( $cache[$data] ) ) {
				unset( $cache[$data] );
			}
			return false;
		}
		else {
			$cache = array();
			return false;
		}
	}

	// Add ?
	if ( $id == 'add' ) {
		if ( ! is_array( $data ) ) return;
		foreach( $data as $item ) {
			if ( is_array( $item ) && isset( $item['id'] ) ) {
				$cache[$item['id']] = $item;
			}
			else {
				wppa_log('err', 'Invalid data in wppa_cache_photo');
				$cache = array();
				return false;
			}
		}
		return false;
	}

	// Error in arg?
	if ( ! wppa_is_int( $id ) || $id < '1' ) {
		wppa_log( 'err', 'Invalid arg wppa_cache_photo(' . serialize( $id ) . ')' );
		return false;
	}

	// In cache?
	if ( isset( $cache[$id] ) ) {
		wppa( 'current_photo', $cache[$id] );
		return $cache[$id];
	}

	// Not in cache, do query
	$cache[$id] = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos WHERE id = %s", $id ), ARRAY_A );
	if ( $cache[$id] ) {
		wppa( 'current_photo', $cache[$id] );
		return $cache[$id];
	}
	else {
		unset( $cache[$id] );
		wppa( 'current_photo', false );
		return false;
	}
}

// get the name of a full sized image
function wppa_get_photo_name( $id, $xargs = array() ) {
global $wppa_skip_alb_to_gal;

	// Init
	$result = '';

	// Verify args
	if ( ! is_numeric( $id ) || $id < '1' ) {
		return $result;
	}

	// Anonymus?
	if ( wppa_is_anon() ) return '';

	// Fill in the optional args
	$defaults = array( 	'translate' => true,
						'addowner' 	=> false,
						'addmedal' 	=> false,
						'escjs' 	=> false,
						'showname' 	=> true,
						'nobpdomain' => false,
						'isthumb' 	=> false,
						);
	$args = wp_parse_args( $xargs, $defaults );

	// Get data
	$thumb = wppa_cache_photo( $id );

	// Photo gone?
	if ( ! $thumb ) {
		return '';
	}

	// Name ?
	if ( $args['showname'] ) {
		$wppa_skip_alb_to_gal = true;
		$result .= __( stripslashes( $thumb['name'] ) , 'wp-photo-album-plus' );
	}

	// Add owner?
	if ( $args['addowner'] ) {
		if ( $args['nobpdomain'] ) {
			$owner = wppa_display_name( $thumb['owner'] );
		}
		else {
			$owner = wppa_bp_userlink( $thumb['owner'] , $args['escjs'] );
		}

		if ( $args['showname'] ) {
			if ( wppa_switch( 'owner_on_new_line' ) && wppa_opt( 'art_monkey_display' ) != 'button' ) {
				if ( ! $args['escjs'] ) {
					$result .= '<br>';
				}
				else {
					$result .= ' [br /]';
				}
			}
			else {
				$result .= ' ';
			}
			$user = wppa_get_user_by( 'login', $thumb['owner'] );
			if ( $user && ( $args['isthumb'] || ( ! ( wppa_switch( 'art_monkey_on' ) && wppa_opt( 'art_monkey_display' ) == 'button' ) ) ) ) {
				$premium = wppa_get_premium_html( $user->ID );
			}
			else {
				$premium = '';
			}
			if ( $args['escjs'] ) {
				$premium = str_replace( array( '<', '>' ), array( '[', ']' ), $premium );
			}
			$result .= '(' . $owner . $premium . ')';
		}
		else {
			$result .= ' ' . $owner;
		}
	}

	// For js use?
	if ( $args['escjs'] ) $result = esc_js( $result );

	// Medal?
	if ( $args['addmedal'] ) {

		$result .= wppa_the_medal_html( $id );
	}

	// To prevent recursive rendering of scripts or shortcodes:
	$result = str_replace( array( '%%wppa%%', '[wppa', '[/wppa]' ), array( '%-wppa-%', '{wppa', '{/wppa}' ), $result );
	if ( wppa_switch( 'allow_foreign_shortcodes_general' ) ) {
		$result = do_shortcode( $result );
	}
	else {
		$result = strip_shortcodes( $result );
	}

	// Translate keywords
	$result = str_replace( 'w#id', $id, $result );

	return wppa_utf8( $result );
}

function wppa_the_medal_html( $id ) {

	$thumb = wppa_cache_photo( $id );
	$result = '';
	$color = wppa_opt( 'medal_color' );
	$wppa_url = is_ssl() ? str_replace( 'http://', 'https://', WPPA_URL ) : WPPA_URL;	// Probably redundant... but it is not clear in to the codex if plugins_url() returns https
	if ( $thumb['status'] == 'gold' ) $result .= '<img src="'.$wppa_url.'/img/medal_gold_'.$color.'.png" title="'.esc_attr(__('Gold medal', 'wp-photo-album-plus' )).'" alt="'.__('Gold', 'wp-photo-album-plus' ).'" style="border:none; margin:0; padding:0; box-shadow:none; height:32px;" />';
	if ( $thumb['status'] == 'silver' ) $result .= '<img src="'.$wppa_url.'/img/medal_silver_'.$color.'.png" title="'.esc_attr(__('Silver medal', 'wp-photo-album-plus' )).'" alt="'.__('Silver', 'wp-photo-album-plus' ).'" style="border:none; margin:0; padding:0; box-shadow:none; height:32px;" />';
	if ( $thumb['status'] == 'bronze' ) $result .= '<img src="'.$wppa_url.'/img/medal_bronze_'.$color.'.png" title="'.esc_attr(__('Bronze medal', 'wp-photo-album-plus' )).'" alt="'.__('Bronze', 'wp-photo-album-plus' ).'" style="border:none; margin:0; padding:0; box-shadow:none; height:32px;" />';
	return $result;
}

// get the description of an image
function wppa_get_photo_desc( $id, $xargs = array() ) {
global $wppa_skip_alb_to_gal;

	// Verify args
	if ( ! is_numeric( $id ) || $id < '1' ) {
		return '';
	}

	// Anonymus?
	if ( wppa_is_anon() ) return '';

	// Fill in the optional args
	$defaults = array( 	'translate' 	=> true,
						'doshortcodes' 	=> false,
						'dogeo' 		=> false,
						);
	$args = wp_parse_args( $xargs, $defaults );

	// Get data
	$thumb = wppa_cache_photo( $id );
	if ( ! $thumb ) { 	// Photo vanished
		return '';
	}

	$desc = $thumb['description'];			// Raw data
	$desc = stripslashes( $desc );			// Unescape
	$wppa_skip_alb_to_gal = true;
	$desc = __( $desc , 'wp-photo-album-plus' );					// qTranslate

	// To prevent recursive rendering of scripts or shortcodes:
	$desc = str_replace( array( '%%wppa%%', '[wppa', '[/wppa]' ), array( '%-wppa-%', '{wppa', '{/wppa}' ), $desc );

	// Geo. This is only in a slideshow
	if ( $thumb['location'] && ! wppa_in_widget() && strpos( wppa_opt( 'custom_content' ), 'w#location' ) !== false && $args['dogeo'] ) {
		wppa_do_geo( $id, $thumb['location'] );
	}

	// Shortcodes
	if ( $args['doshortcodes'] ) $desc = do_shortcode( $desc );	// Do shortcodes if wanted
	else $desc = strip_shortcodes( $desc );						// Remove shortcodes if not wanted

	// Other keywords
	$desc = wppa_translate_photo_keywords( $id, $desc ); 		// w# keywords
	$desc = wppa_filter_iptc( $desc, $id, true );				// 2# tags
	$desc = wppa_filter_exif( $desc, $id, true );				// E# tags

	// Other filters
	$desc = wppa_html( $desc );				// Enable html
	$desc = balanceTags( $desc, true );		// Balance tags
	$desc = make_clickable( $desc );		// Auto make a tags for links
	$desc = convert_smilies( $desc );		// Make smilies visible

	// CMTooltipGlossary on board?
	$desc = wppa_filter_glossary( $desc );

	// Formatting
	switch( wppa_opt( 'wpautop_on_desc' ) ) {
		case 'nil':
			break;
		case 'nl2br':
			$desc = nl2br( $desc );
			break;
		case 'wpautop':
			$desc = wpautop( $desc );
			break;
		default:
			wppa_log('Err', 'Unimplemented option value: ' . wppa_opt( 'wpautop_on_desc' ) . ' for wppa_opt( \'wpautop_on_desc\' )' );
	}
	$desc = wppa_echo( $desc, '', '', true );

	return wppa_utf8( $desc );
}

// Translate keywords
function wppa_translate_photo_keywords( $id, $text ) {
global $wpdb;

	$result = $text;

	// Is there any 'w#' ?
	if ( strpos( $result, 'w#' ) !== false ) {
		$thumb = wppa_cache_photo( $id );

		// General keywords
		if ( strpos( $result, 'w#albumname' ) !== false ) {
			$result = str_replace( 'w#albumname', wppa_get_album_name( $thumb['album'] ), $result );
		}
		if ( strpos( $result, 'w#albumdesc' ) !== false ) {
			$result = str_replace( 'w#albumdesc', wppa_get_album_desc( $thumb['album'] ), $result );
		}
		$result = str_replace( 'w#albumid', $thumb['album'], $result );
		if ( strpos( $result, 'w#owner' ) !== false ) {
			$result = str_replace( 'w#owner', wppa_bp_userlink( $thumb['owner'] ), $result );
		}
		if ( $thumb['location'] && strpos( $result, 'w#gpx' ) !== false ) {
			$geo = explode( '/', $thumb['location'] );
			$loc = str_replace( '&deg;', '<sup>o</sup>', $geo['0'].' '.$geo['1'] );
			$result = str_replace( 'w#gpx', htmlspecialchars( $loc ), $result );
		}
		if ( strpos( $result, 'w#mime' ) !== false ) {
			$source = wppa_get_source_path( $id );
			if ( file_exists( $source ) ) {
				$mime = mime_content_type( $source );
			}
			else {
				$mime = '';
			}
			$result = str_replace( 'w#mime', $mime, $result );
		}
		$result = str_replace( 'w#gpx', htmlspecialchars( $thumb['location'] ), $result );
		$result = str_replace( 'w#exifdtm', $thumb['exifdtm'], $result );
		$keywords = array( 'name', 'filename', 'id', 'tags', 'views', 'album', 'dlcount' );
		foreach ( $keywords as $keyword ) {
			$wppa_skip_alb_to_gal = true;
			$replacement = __( trim( stripslashes( $thumb[$keyword] ) ) );
			if ( $keyword == 'tags' ) {
				$replacement = trim( $replacement, ',' );

				// Translated multilang tags may not be ucfirtsed
				if ( $replacement ) {
					$arr = explode( ',', $replacement );
					foreach ( array_keys( $arr ) as $key ) {
						$arr[$key] = ucfirst( $arr[$key] );
					}
					$replacement = implode( ', ', $arr );
				}

			}
			$result = str_replace( 'w#'.$keyword, $replacement, $result );
		}

		// Urls
		if ( strpos( $result, 'w#url' ) !== false ) {
			$result 	= str_replace( 'w#url', wppa_get_lores_url( $id ), $result );
		}
		if ( strpos( $result, 'w#hrurl' ) !== false ) {
			$result 	= str_replace( 'w#hrurl', wppa_get_hires_url( $id ), $result );
		}
		if ( strpos( $result, 'w#tnurl' ) !== false ) {
			$result 	= str_replace( 'w#tnurl', wppa_get_tnres_url( $id ), $result );
		}
		if ( strpos( $result, 'w#pl' ) !== false ) {
			$result 	= str_replace( 'w#pl', wppa_get_source_pl( $id ), $result );
		}

		// Rating
		$result 	= str_replace( 'w#rating', wppa_get_rating_by_id( $id, 'nolabel' ), $result );

		// Owner
		$user = wppa_get_user_by( 'login', $thumb['owner'], true );
		if ( $user ) {
			$result = str_replace( 'w#displayname', $user->display_name . wppa_get_premium_html( $user->ID ), $result );
		}
		else {
			$owner = wppa_get_photo_item( $id, 'owner' );
			if ( strpos( $owner, '.' ) == false && strpos( $owner, ':' ) == false ) {	// Not an ip, a deleted user
				$result = str_replace( 'w#displayname', __( 'Nomen Nescio', 'wp-photo-album-plus' ), $result );
			}
			else {																		// An ip
				$result = str_replace( 'w#displayname', __( 'Anonymus', 'wp-photo-album-plus' ), $result );
			}
		}

		// Art monkey sizes
		if ( strpos( $result, 'w#amx' ) !== false || strpos( $result, 'w#amy' ) !== false || strpos( $result, 'w#amfs' ) !== false ) {
			$amxy = wppa_get_artmonkey_size_a( $id );
			if ( is_array( $amxy ) ) {
				$result = str_replace( 'w#amx', $amxy['x'], $result );
				$result = str_replace( 'w#amy', $amxy['y'], $result );
				$result = str_replace( 'w#amfs', $amxy['s'], $result );
			}
			else {
				$result = str_replace( 'w#amx', '', $result );
				$result = str_replace( 'w#amy', '', $result );
				$result = str_replace( 'w#amfs', '', $result );
			}
		}

		// Timestamps
		$timestamps = array( 'timestamp', 'modified' );
		foreach ( $timestamps as $timestamp ) {
			if ( $thumb[$timestamp] ) {
				$result = str_replace( 'w#'.$timestamp, wppa_local_date( wppa_get_option( 'date_format', "F j, Y," ).' '.wppa_get_option( 'time_format', "g:i a" ), $thumb[$timestamp] ), $result );
			}
			else {
				$result = str_replace( 'w#'.$timestamp, '', $result );
			}
		}

		// Sequence no in album
		if ( strpos( $result, 'w#seqno' ) !== false ) {
			$alb = $thumb['album'];

			$no = wppa_get_seqno( $alb, $id );

			if ( $no ) {
				$result = str_replace( 'w#seqno', $no, $result );
			}
			else {
				$result = str_replace( 'w#seqno', '', $result );
			}
		}

		// Custom data fields
		if ( wppa_switch( 'custom_fields' ) ) {
			$custom_data = wppa_unserialize( $thumb['custom'] );
			if ( ! is_array( $custom_data ) ) {
				$custom_data = array( '', '', '', '', '', '', '', '', '', '' );
			}
			for ( $i = '0'; $i < '10'; $i++ ) {
				$wppa_skip_alb_to_gal = true;
				$rep = __( stripslashes( $custom_data[$i] ) ); 					// Replacement, qTranslate style translated

				// Patch for Rasada. Replace yyyy0101 by yyyy, yyyymmdd by yyyy.mm.dd
				if ( strlen( $rep ) == 8 && is_numeric( $rep ) ) {
					if ( substr( $rep, 4 ) == '0101' ) {
						$rep = substr( $rep, 0, 4 );
					}
					else {
						$rep = substr( $rep, 0, 4 ) . '.' . substr( $rep, 4, 2 ) . '.' . substr( $rep, 6 );
					}
				}

				$wppa_skip_alb_to_gal = true;
				$cap = __( wppa_opt( 'custom_caption_'.$i ) );					// Caption
				$dis = wppa_switch( 'custom_visible_'.$i );						// Visible
				if ( $rep == ', , ' ) $rep = '';

				if ( $cap && $rep && $dis ) {									// Field defined and not empty and may be displayed
					$result = str_replace( 'w#cc'.$i, $cap, $result );			// Caption
					$result = str_replace( 'w#cd'.$i, $rep, $result );			// Data
				}
				else { 															// Not defined or empty or not displayable
					$result = str_replace( 'w#cc'.$i, '', $result ); 			// Remove
					$result = str_replace( 'w#cd'.$i, '', $result ); 			// Remove
				}

			}
		}

		// Video
		if ( $thumb['duration'] ) {
			$mins = floor( $thumb['duration'] / 60 );
			$secs = round( $thumb['duration'] % 60 );
			$result = str_replace( 'w#duration', sprintf( '%s&#39;%s&#34;.', $mins, $secs ), $result );
		}
		else {
			$result = str_replace( 'w#duration', '', $result );
		}
	}

	// Remove empty lines
	$result = preg_replace( '@<tr><td[^>]*?>[^<]*?</td><td[^>]*?></td></tr>@siu', '', $result );

	return $result;
}

// Get photo sequence no in album
function wppa_get_seqno( $alb, $id ) {
static $seqs;
global $wpdb;

	if ( isset( $seqs[$alb] ) ) {
		$seq = $seqs[$alb];
	}
	else {
		$ord = wppa_get_photo_order( $alb );
		$seq = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE album = %d " . $ord, $alb ) );
		$seqs[$alb] = $seq;
	}
	$no = 0;
	for ( $i = 0; $i < count( $seq ); $i++ ) {
		if ( $seq[$i] == $id ) {
			$no = $i + 1;
		}
	}
	return $no;
}

// get album name
function wppa_get_album_name( $id, $xargs = array() ) { // $extended = false ) {
global $wppa_skip_alb_to_gal;

	// Sanitize args
	if ( ! is_numeric( $id ) ) {
		return '';
	}

	// Fill in the optional args
	$defaults = array( 	'translate' => true,
						'extended' 	=> false,
						'raw' 		=> false,
						);
	$args = wp_parse_args( $xargs, $defaults );

	// Init
 	$album = $id > '0' ? wppa_cache_album( $id ) : false;

	if ( $args['extended'] ) {
		switch( $id ) {
			case '0':
				return __( '--- none ---', 'wp-photo-album-plus' );
				break;
			case '-1':
				return __( '--- separate ---', 'wp-photo-album-plus' );
				break;
			case '-2':
				return __( '--- all ---', 'wp-photo-album-plus' );
				break;
			case '-3':
				return __( '--- public ---', 'wp-photo-album-plus' );
				break;
			default:
				if ( $id <= '-9' ) {
					return __( '--- deleted ---', 'wp-photo-album-plus' );
					break;
				}
				if ( $args['raw'] ) {
					return $album['name'];
				}
				break;
		}
	}
	else {
		switch( $id ) {
			case '-2':
				return __( 'All albums', 'wp-photo-album-plus' );
				break;
			case '-3':
				return __( 'My and public albums', 'wp-photo-album-plus' );
				break;
			default:
				if ( ! is_numeric( $id ) || ! $id || $id < '1' ) return '';
		}
	}

	if ( ! $album ) {
		return __( '--- deleted ---', 'wp-photo-album-plus' );
	}
	else {
		if ( $args['translate'] ) {
			$wppa_skip_alb_to_gal = true;
			$name = __( stripslashes( $album['name'] ) );
		}
		else {
			$name = stripslashes( $album['name'] );
		}
	}

	// To prevent recursive rendering of scripts or shortcodes:
	$name = str_replace( array( '%%wppa%%', '[wppa', '[/wppa]' ), array( '%-wppa-%', '{wppa', '{/wppa}' ), $name );
	if ( wppa_switch( 'allow_foreign_shortcodes_general' ) ) {
		$name = do_shortcode( $name );
	}
	else {
		$name = strip_shortcodes( $name );
	}

	return wppa_utf8( $name );
}

// get album description
function wppa_get_album_desc( $id, $xargs = array() ) {
global $wppa_skip_alb_to_gal;

	// Sanitize args
	if ( ! is_numeric( $id ) || $id < '1' ) {
		return '';
	}

	// Fill in the optional args
	$defaults = array( 'translate' => true );
	$args = wp_parse_args( $xargs, $defaults );

	// Get the album data
	$album = wppa_cache_album( $id );

	// Raw data
	if ( ! $album ) {
		wppa_log( 'dbg', 'Album desc of non existent album #' . $id . ' requested', true );
		return '';
	}
	$desc = $album['description'];

	// No content, need no filtering
	if ( ! $desc ) {
		return '';
	}

	// Unescape
	$desc = stripslashes( $desc );

	// Optionally translate
	if ( $args['translate'] ) {

		// qTranslate, wpGlobus
		$wppa_skip_alb_to_gal = true;
		$desc = __( $desc );
	}

	// Enable or strip html
	$desc = wppa_html( $desc );

	// Balance tags
	$desc = balanceTags( $desc, true );

	// To prevent recursive rendering of scripts or shortcodes:
	$desc = str_replace( array( '%%wppa%%', '[wppa', '[/wppa]' ), array( '%-wppa-%', '{wppa', '{/wppa}' ), $desc );
	if ( wppa_switch( 'allow_foreign_shortcodes_general' ) ) {
		$desc = do_shortcode( $desc );
	}
	else {
		$desc = strip_shortcodes( $desc );
	}

	// Convert links and mailto:
	$desc = make_clickable( $desc );

	// Album keywords
	$desc = wppa_translate_album_keywords( $id, $desc, $args['translate'] );

	// CMTooltipGlossary on board?
	$desc = wppa_filter_glossary( $desc );

	// Formatting
	switch( wppa_opt( 'wpautop_on_album_desc' ) ) {
		case 'nil':
			break;
		case 'nl2br':
			$desc = nl2br( $desc );
			break;
		case 'wpautop':
			$desc = wpautop( $desc );
			break;
		default:
			wppa_log('Err', 'Unimplemented option value: ' . wppa_opt( 'wpautop_on_album_desc' ) . ' for wppa_opt( \'wpautop_on_album_desc\' )' );
	}
	$desc = wppa_echo( $desc, '', '', true );

	// Done!
	return wppa_utf8( $desc );
}

// Translate album keywords
function wppa_translate_album_keywords( $id, $text, $translate = true ) {

	// Init
	$result = $text;

	// Does album exist and is there any 'w#' ?
	if ( wppa_album_exists( $id ) && strpos( $result, 'w#' ) !== false ) {

		// Get album data
		$album = wppa_cache_album( $id );

		// Keywords
		$result = str_replace( 'w#owner', wppa_bp_userlink( $album['owner'] ), $result );
		$keywords = array( 'name', 'id', 'views' );
		foreach ( $keywords as $keyword ) {
			$replacement = trim( stripslashes( $album[$keyword] ) );
			if ( $translate ) {
				$replacement = __( $replacement );
			}

		//	if ( $replacement == '' ) $replacement = '&lsaquo;' . __( 'none' , 'wp-photo-album-plus' ) . '&rsaquo;';
			$result = str_replace( 'w#'.$keyword, $replacement, $result );
		}

		// FS views
		if ( strpos( $result, 'w#fsviews' ) !== false ) {

			$treecounts = wppa_get_treecounts_a( $id, true );
			$count 		= $treecounts['selfphotoviews'];
			$result 	= str_replace( 'w#fsviews', $count, $result );
		}

		// Timestamps
		$timestamps = array( 'timestamp', 'modified' );
		foreach ( $timestamps as $timestamp ) {
			if ( $album[$timestamp] ) {
				$result = str_replace( 'w#'.$timestamp, wppa_local_date( wppa_get_option( 'date_format', "F j, Y," ).' '.wppa_get_option( 'time_format', "g:i a" ), $album['timestamp'] ), $result );
			}
			else {
				$result = str_replace( 'w#'.$timestamp, '', $result );
			}
		}

		// Custom data fields
		if ( wppa_switch( 'album_custom_fields' ) ) {

			// Get raw data
			$custom_data = wppa_unserialize( $album['custom'] );
			if ( ! is_array( $custom_data ) ) {
				$custom_data = array( '', '', '', '', '', '', '', '', '', '' );
			}

			// Process max all 10 sub-items
			for ( $i = '0'; $i < '10'; $i++ ) {
				if ( wppa_opt( 'album_custom_caption_'.$i ) ) {					// Field defined
					if ( wppa_switch( 'album_custom_visible_'.$i ) ) {			// May be displayed
						if ( $translate ) {
							$result = str_replace( 'w#cc'.$i, __( wppa_opt( 'album_custom_caption_'.$i ) ) . ':', $result );	// Caption
							$result = str_replace( 'w#cd'.$i, __( stripslashes( $custom_data[$i] ) ), $result );	// Data
						}
						else {
							$result = str_replace( 'w#cc'.$i, wppa_opt( 'album_custom_caption_' . $i ) . ':', $result );	// Caption
							$result = str_replace( 'w#cd'.$i, stripslashes( $custom_data[$i] ), $result );	// Data
						}
					}
					else { 													// May not be displayed
						$result = str_replace( 'w#cc'.$i, '', $result ); 	// Remove
						$result = str_replace( 'w#cd'.$i, '', $result ); 	// Remove
					}
				}
				else { 														// Field not defined
					$result = str_replace( 'w#cc'.$i, '', $result ); 		// Remove
					$result = str_replace( 'w#cd'.$i, '', $result ); 		// Remove
				}
			}
		}
	}

	// Done!
	return $result;
}

// Get any album field of any album, raw data from the db
function wppa_get_album_item( $id, $item ) {

	$album = wppa_cache_album( $id );

	if ( $album ) {
		if ( isset( $album[$item] ) ) {
			return trim( $album[$item] );
		}
		else {
			wppa_log( 'Err', 'Album item ' . $item . ' does not exist. ( get_album_item )' );
		}
	}

	return false;
}

// Get any photo field of any photo, raw data from the db
function wppa_get_photo_item( $id, $item ) {

	$photo = wppa_cache_photo( $id );

	// Anonymus?
	if ( $item == 'name' || $item == 'owner' ) 	{
		if ( wppa_is_anon() ) return '';
	}

	if ( $photo ) {
		if ( isset( $photo[$item] ) ) {
			return trim( $photo[$item] );
		}
		else {
			wppa_log( 'Err', 'Photo item ' . $item . ' does not exist. ( get_photo_item )', true );
		}
	}

	return false;
}

// Get sizes routines
// $id: int photo id
// $force: bool force recalculation, both x and y
function wppa_get_thumbx( $id, $force = false ) {
	if ( ! wppa_is_file( wppa_get_thumb_path( $id ) ) ) {
		$x = wppa_get_videox( $id );
		$y = wppa_get_videoy( $id );
		if ( $x > $y ) { 	// Landscape
			$result = wppa_opt( 'thumbsize' );
		}
		elseif ( $y ) {
			$result = wppa_opt( 'thumbsize' ) * $x / $y;
		}
		else {
			$result = '';
		}
	}
	else {
		$result = wppa_get_thumbphotoxy( $id, 'thumbx', $force );
	}
	if ( ! $result && wppa_has_audio( $id ) ) {
		$result = wppa_opt( 'thumbsize' );
	}
	return $result;
}
function wppa_get_thumby( $id, $force = false ) {
	if ( ! wppa_is_file( wppa_get_thumb_path( $id ) ) ) {
		$x = wppa_get_videox( $id );
		$y = wppa_get_videoy( $id );
		if ( $x > $y ) { 	// Landscape
			$result = wppa_opt( 'thumbsize' ) * $y / $x;
		}
		else {
			$result = wppa_opt( 'thumbsize' );
		}
	}
	else {
		$result = wppa_get_thumbphotoxy( $id, 'thumby', $force );
	}
	if ( ! $result && wppa_has_audio( $id ) ) {
		$result = wppa_opt( 'thumbsize' );// * 1080 / 1920;
		$siz = getimagesize( WPPA_UPLOAD_PATH . '/' . wppa_opt( 'audiostub' ) );
		$result *= $siz['1'] / $siz['0'];
	}
	return $result;
}
function wppa_get_photox( $id, $force = false ) {
	$result = wppa_get_thumbphotoxy( $id, 'photox', $force );
	if ( wppa_is_stereo( $id ) ) {
		return floor( $result / 2 );
	}
	else {
		return $result;
	}
}
function wppa_get_photoy( $id, $force = false ) {
	return wppa_get_thumbphotoxy( $id, 'photoy', $force );
}
function wppa_get_thumbratioxy( $id ) {
	if ( wppa_is_video( $id ) ) {
		$result = wppa_get_videox( $id ) / wppa_get_videoy( $id );
	}
	else {
		if ( wppa_get_thumby( $id ) ) {
			$result = wppa_get_thumbx( $id ) / wppa_get_thumby( $id );
		}
		else {
			$result = '1';
		}
	}
	return $result;
}
function wppa_get_thumbratioyx( $id ) {
	if ( wppa_is_video( $id ) ) {
		$result = wppa_get_videoy( $id ) / wppa_get_videox( $id );
	}
	else {
		if ( wppa_get_thumbx( $id ) ) {
			$result = wppa_get_thumby( $id ) / wppa_get_thumbx( $id );
		}
		else {
			$result = '1';
		}
	}
	return $result;
}
function wppa_get_thumbphotoxy( $id, $key, $force = false ) {

	$result = wppa_get_photo_item( $id, $key );
	if ( $result && ! $force ) {
		return $result; 			// Value found
	}

	if ( $key == 'thumbx' || $key == 'thumby' ) {
		$file = wppa_get_thumb_path( $id );
	}
	else {
		$file = wppa_get_photo_path( $id );
	}

	if ( ! is_file( $file ) && ! $force ) {
		return '1';	// File not found
	}

	if ( is_file( $file ) ) {
		$size = getimagesize( $file );
	}
	else {
		$size = array( '1', '1');
	}
	if ( is_array( $size ) ) {
		if ( $key == 'thumbx' || $key == 'thumby' ) {
			wppa_update_photo( $id, ['thumbx' => $size[0], 'thumby' => $size[1]] );
		}
		else {
			wppa_update_photo( $id, ['photox' => $size[0], 'photoy' => $size[1]] );
		}
		wppa_cache_photo( 'invalidate', $id );

		if ( $key == 'thumbx' || $key == 'photox' ) {
			return $size[0];
		}
		else {
			return $size[1];
		}
	}

	// No size found
	else {
		return wppa_opt( 'thumbsize' );
	}
}

function wppa_get_imagexy( $id, $key = 'photo' ) {
	if ( wppa_is_video( $id ) ) {
		$result = array( wppa_get_videox( $id ), wppa_get_videoy( $id ) );
	}
	elseif ( $key == 'thumb' ) {
		$result = array( wppa_get_thumbx( $id ), wppa_get_thumby( $id ) );
	}
	else {
		$result = array( wppa_get_photox( $id ), wppa_get_photoy( $id ) );
	}
	return $result;
}

function wppa_get_imagex( $id, $key = 'photo' ) {
	if ( wppa_is_video( $id ) ) {
		$result = wppa_get_videox( $id );
	}
	elseif ( $key == 'thumb' ) {
		$result = wppa_get_thumbx( $id );
	}
	else {
		$result = wppa_get_photox( $id );
	}
	return $result;
}

function wppa_get_imagey( $id, $key = 'photo' ) {
	if ( wppa_is_video( $id ) ) {
		$result = wppa_get_videoy( $id );
	}
	elseif ( $key == 'thumb' ) {
		$result = wppa_get_thumby( $id );
	}
	else {
		$result = wppa_get_photoy( $id );
	}
	return $result;
}

// See if a photo item should be displayed for a given album (enumeration)
function wppa_is_item_displayable( $xalb, $item, $default_slug ) {
static $cache;

	// Computed this one before?
	if ( isset( $cache[$xalb . $item . $default_slug] ) ) return $cache[$xalb . $item . $default_slug];

	$albs = explode( '.', wppa_expand_enum( $xalb ) );

	if ( is_array( $albs ) ) {
		foreach( $albs as $alb ) {
			if ( _wppa_is_item_displayable( $alb, $item, $default_slug ) ) {
				$cache[$xalb . $item . $default_slug] = true;
				return true;
			}
		}
	}
	$cache[$xalb . $item . $default_slug] = false;
	return false;
}
function _wppa_is_item_displayable( $alb, $item, $default_slug ) {

	$default = wppa_switch( $default_slug );

	if ( in_array( $alb, array( '', '0', '-1' ) ) ) return $default;

	// Validate args
	if ( ! is_numeric( $alb ) ) {
		wppa_log( 'err', 'Album ' . $alb . ' not numeric in wppa_is_item_displayable()' );
		return false;
	}
	if ( ! in_array( $item, array( 'name', 'description', 'rating', 'comments' ) ) ) {
		wppa_log( 'err', 'Item ' . $item . ' not implemted in wppa_is_item_displayable()' );
		return false;
	}
	if ( $default !== true && $default !== false ) {
		wppa_log( 'err', 'Default ' . $default_slug . ' not implemted in wppa_is_item_displayable()' );
		return false;
	}

	// Get the albums display options
	$display_opts = wppa_get_album_item( $alb, 'displayopts' );
	if ( $display_opts ) {
		$opts = explode( ',', $display_opts );
	}

	// Add missing items to be the default ('0')
	for ( $i = 0; $i < 4; $i++ ) {
		if ( ! isset( $opts[$i] ) ) {
			$opts[$i] = '0';
		}
	}

	// Translate itemname to index in $opts array
	$indexes = array( 'name' => 0, 'description' => 1, 'rating' => 2, 'comments' => 3 );

	// $opts[$indexes[item]] can have 3 values: -1 = no, 0 = use default, 1 = yes
	if ( $opts[$indexes[$item]] == '0' ) {
		return $default;
	}
	else {
		return $opts[$indexes[$item]] == '1';
	}
}
