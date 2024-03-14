<?php
/* wppa-links.php
* Package: wp-photo-album-plus
*
* Frontend links
*
* Version: 8.6.01.004
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// get permalink plus ? or & and possible debug switch
function wppa_get_permalink( $key = '', $plain = false ) {
global $wppa_lang;
global $wppa_locale;

	if ( ! $key && is_search() ) $key = wppa_opt( 'search_linkpage' );

	switch ( $key ) {
		case '0':
		case '':	// normal permalink
		case '-1':
			if ( wppa_in_widget() ) {
				$pl = get_permalink(); // home_url();
				if ( $plain ) return $pl;
				if ( strpos( $pl, '?' ) ) $pl .= '&amp;';
				else $pl .= '?';
			}
			else {
				if ( wppa( 'ajax' ) ) {
					if ( wppa_get( 'page_id' ) ) $id = wppa_get( 'page_id' );
					elseif ( wppa_get( 'p' ) ) $id = wppa_get( 'p' );
					elseif ( wppa_get( 'fromp' ) ) $id = wppa_get( 'fromp' );
					else $id = '';
					$pl = $id ? get_permalink( intval( $id ) ) : get_permalink();
					if ( $plain ) return $pl;
					if ( strpos( $pl, '?' ) ) $pl .= '&amp;';
					else $pl .= '?';
				}
				else {
					$pl = get_permalink();
					if ( $plain ) return $pl;
					if ( strpos( $pl, '?' ) ) $pl .= '&amp;';
					else $pl .= '?';
				}
			}
			break;
		case 'js':	// normal permalink for js use
			if ( wppa_in_widget() ) {
				$pl = home_url();
				if ( $plain ) return $pl;
				if ( strpos($pl, '?' ) ) $pl .= '&';
				else $pl .= '?';
			}
			else {
				if ( wppa( 'ajax' ) ) {
					if ( wppa_get( 'page_id' ) ) $id = wppa_get( 'page_id' );
					elseif ( wppa_get( 'p' ) ) $id = wppa_get( 'p' );
					elseif ( wppa_get( 'fromp' ) ) $id = wppa_get( 'fromp' );
					else $id = '';
					$pl = $id ? get_permalink( intval( $id ) ) : get_permalink();
					if ( $plain ) return $pl;
					if ( strpos( $pl, '?' ) ) $pl .= '&';
					else $pl .= '?';
				}
				else {
					$pl = get_permalink();
					if ( $plain ) return $pl;
					if ( strpos( $pl, '?' ) ) $pl .= '&';
					else $pl .= '?';
				}
			}
			break;
		default:	// pagelink
			$pl = $key > '0' ? get_permalink( $key ) : get_permalink();
			if ( $plain ) return $pl;
			if ( strpos( $pl, '?' ) ) $pl .= '&amp;';
			else $pl .= '?';
			break;
	}

	if ( $wppa_lang ) {	// If lang in querystring: keep it
		if ( strpos( $pl, 'lang=' ) === false ) { 	// Not yet
			if ( $key == 'js' ) $pl .= 'lang=' . $wppa_lang . '&';
			else $pl .= 'lang=' . $wppa_lang . '&amp;';
		}
	}

	if ( wppa( 'is_rootsearch' ) ) {
		if ( $key == 'js' ) $pl .= 'rootsearch=1&';
		else $pl .= 'rootsearch=1&amp;';
	}

	if ( wppa_is_virtual() ) {
		if ( $key == 'js' ) $pl .= 'vt=1&';
		else $pl .= 'vt=1&amp;';
	}

 	if ( wppa( 'cache' ) ) {
		if ( $key == 'js' ) $pl .= 'cache=1&';
		else $pl .= 'cache=1&amp;';
	}

	if ( wppa_is_anon() ) {
		$pl .= 'anon=1&amp;';
	}

	if ( wppa_is_meonly() ) {
		$pl .= 'meonly=1&amp;';
	}

	return $pl;
}

// Like get_permalink but for ajax use
function wppa_get_ajaxlink( $key = '', $deltamoccur = '0' ) {
global $wppa_lang;
global $wppa_locale;
global $wppa_runtime_settings;

	if ( ! $key && is_search() ) $key = wppa_opt( 'search_linkpage' );

	$method = wppa_opt( 'ajax_method' );

	if ( wppa_is_file( dirname( __FILE__ ) . '/wppa-ajax-front.php' ) && $method == 'extern' ) {
		if ( is_admin() ) $al = site_url() . '/wp-admin/admin-ajax.php?action=wppa';
		else $al = WPPA_URL . '/wppa-ajax-front.php?action=wppa';
	}
	else switch ( $method ) {
		case 'admin':
		case 'none':
			$al = site_url() . '/wp-admin/admin-ajax.php?action=wppa';
			break;
		default: // 'normal'
			if ( is_admin() ) $al = site_url() . '/wp-admin/admin-ajax.php?action=wppa';
			else $al = ( wppa_switch( 'ajax_home' ) ? home_url() : site_url() ) . '/wppaajax?action=wppa';
			break;
	}

	if ( $key == 'plain' ) {
		return $al;
	}

	$al .= '&amp;wppa-action=render';

	// See if this call is from an ajax operation or...
	if ( wppa( 'ajax' ) ) {
		if ( wppa_get( 'size' ) ) $al .= '&amp;wppa-size=' . wppa_get( 'size' );
		if ( wppa_get( 'occur' ) ) $al .= '&amp;wppa-occur=' . ( wppa_get( 'occur' ) + $deltamoccur );
		if ( is_numeric( $key ) && $key > '0' ) {
			$al .= '&amp;page_id='.$key;
		}
		else {
			if ( wppa_get( 'page_id' ) ) $al .= '&amp;page_id=' . wppa_get( 'page_id' );
		}
		if ( wppa_get( 'p' ) ) $al .= '&amp;p=' . wppa_get( 'p' );
		if ( wppa_get( 'fromp' ) ) $al .= '&amp;wppa-fromp=' . wppa_get( 'fromp' );
	}
	else {	// directly from a page or post
		$al .= '&amp;wppa-size='.wppa_get_container_width();
		$al .= '&amp;wppa-occur=' . ( wppa( 'mocc' ) + $deltamoccur );
		if ( is_numeric($key) && $key > '0' ) {
			$al .= '&amp;page_id=' . $key;
		}
		else {
			if ( wppa_get( 'p' ) ) $al .= '&amp;p=' . wppa_get( 'p' );
			if ( wppa_get( 'page_id' ) ) $al .= '&amp;page_id=' . wppa_get( 'page_id' );
		}
		$al .= '&amp;wppa-fromp=' . wppa_get_the_ID();
	}

	if ( $wppa_lang ) {	// If lang in querystring: keep it
		if ( strpos($al, 'lang=') === false ) { 	// Not yet
			if ( $key == 'js' ) $al .= '&lang=' . $wppa_lang;
			else $al .= '&amp;lang=' . $wppa_lang;
		}
	}

	if ( wppa( 'is_rootsearch' ) ) {
		if ( $key == 'js' ) $al .= '&rootsearch=1';
		else $al .= '&amp;rootsearch=1';
	}

	if ( wppa_is_virtual() ) {
		if ( $key == 'js' ) $al .= '&vt=1';
		else $al .= '&amp;vt=1';
	}

	if ( wppa( 'cache' ) ) {
		$al .= '&amp;cache=1';
	}

	if ( wppa_is_anon() ) {
		$al .= '&amp;anon=1';
	}

	if ( wppa_is_meonly() ) {
		$al .= '&amp;meonly=1';
	}

	if ( is_array( $wppa_runtime_settings ) ) {
		foreach( array_keys( $wppa_runtime_settings ) as $key ) {
			$value = $wppa_runtime_settings[$key];
			$al .= '&amp;' . $key . '=' . $value;
		}
	}

	return $al.'&amp;';
}

// get page url of current album image
function wppa_get_image_page_url_by_id( $id, $single = false, $alb = false, $page = null ) {

	if ( ! wppa_is_int( $id ) || $id < '1' ) {
		return '';
	}

	$thumb = wppa_cache_photo( $id );

	if ( ! $alb ) {
		$alb = $thumb['album'];
	}

	$result = wppa_get_permalink($page).'wppa-album='.$alb.'&amp;wppa-photo='.$thumb['id'].'&amp;wppa-cover=0&amp;wppa-occur='.wppa('mocc');
	if ( $single ) {
		$result .= '&amp;wppa-single=1';
	}
	if ( wppa( 'is_potdhis' ) ) {
		$result .= '&amp;wppa-potdhis=1';
	}

	return wppa_encrypt_url( $result );
}

// get page url of current album image, ajax version
function wppa_get_image_url_ajax_by_id($id) {

	if ( ! wppa_is_int($id) || $id < '1' ) {
		return '';
	}

	$thumb = wppa_cache_photo( $id );

	$result = wppa_get_ajaxlink().'wppa-album='.$thumb['album'].'&amp;wppa-photo='.$thumb['id'].'&amp;wppa-cover=0&amp;wppa-occur='.wppa('mocc');

	return wppa_encrypt_url( $result );
}

// get link to album by id or in loop
function wppa_get_album_url( $args ) { //$id, $pag = '', $type = 'content', $occur = '0' ) {

	$defaults = array( 'album' => '',
					   'page' => '',
					   'type' => 'content',
					   'mocc' => wppa( 'mocc' ),
					   );
	$args = wppa_parse_args( $args, $defaults );

	if ( $args['album'] ) {
		$link = wppa_get_permalink( $args['page'] ) . 'wppa-album=' . $args['album'] . '&amp;wppa-cover=0&amp;wppa-occur=' . $args['mocc'];
		if ( $args['type'] == 'thumbs' ) {
			$link .= '&amp;wppa-photos-only=1';
		}
		if ( $args['type'] == 'albums' ) {
			$link .= '&amp;wppa-albums-only=1';
		}
	}
	else $link = '';
    return wppa_convert_to_pretty( wppa_encrypt_url( $link ) );
}

// get link to album by id or in loop ajax version
function wppa_get_album_url_ajax( $args ) { //$id, $pag = '', $type = 'content' ) {

	$defaults = array( 'album' => '',
					   'page' => '',
					   'type' => 'content',
					   'mocc' => wppa( 'mocc' ),
					   );
	$args = wppa_parse_args( $args, $defaults );

	if ( $args['album'] ) {
		$link = wppa_get_ajaxlink( $args['page'] ) . 'wppa-album=' . $args['album'] . '&amp;wppa-cover=0&amp;wppa-occur=' . $args['mocc'];
		if ( $args['type'] == 'thumbs' ) {
			$link .= '&amp;wppa-photos-only=1';
		}
		if ( $args['type'] == 'albums' ) {
			$link .= '&amp;wppa-albums-only=1';
		}
	}
	else $link = '';
    return wppa_encrypt_url( $link );
}

// get link to slideshow (in loop)
function wppa_get_slideshow_url( $args ) { //$id, $page = '', $pid = '', $occ = '' ) {

	$defaults =  array(
		'album' => '',
		'page' => '',
		'photo' => '',
		'mocc' => wppa( 'mocc' ),
	);
	$args = wppa_parse_args( $args, $defaults );

	if ( $args['album'] ) {

		$link = wppa_get_permalink( $args['page'] ) . 'wppa-album=' . $args['album'] . '&amp;wppa-slide=1&amp;wppa-occur=' . $args['mocc'];
		if ( $args['photo'] ) $link .= '&amp;wppa-photo=' . $args['photo'];
		if ( wppa( 'is_upldr' ) ) $link .= '&amp;wppa-upldr=' . wppa( 'is_upldr' );
		// can be extended for other special cases, see wppa_thumb_default() in wppa-functions.php
	}
	elseif ( wppa( 'calendar' ) ) {
		$link = wppa_get_permalink( $args['page'] );
	}
	else {
		$link = '';
	}

	return wppa_convert_to_pretty( wppa_encrypt_url( $link ) );
}

// get link to slideshow (in loop) ajax version
function wppa_get_slideshow_url_ajax( $args ) {

	$defaults = array( 'album' => '',
					   'page' => '',
					   'photo' => '',
					   'mocc' => wppa( 'mocc' ),
	);
	$args = wppa_parse_args( $args, $defaults );

	if ( $args[ 'album' ] ) {

		$link = wppa_get_ajaxlink( $args['page'] ) . 'wppa-album=' . $args['album'] . '&amp;wppa-slide=1&amp;wppa-occur=' . $args['mocc'];
		if ( $args['photo'] ) $link .= '&amp;wppa-photo=' . $args['photo'];
		if ( wppa( 'is_upldr' ) ) $link .= '&amp;wppa-upldr='.wppa( 'is_upldr' );
		// can be extended for other special cases, see wppa_thumb_default() in wppa-functions.php
	}
	elseif ( wppa( 'calendar' ) ) {
		$link = wppa_get_ajaxlink( $args['page'] ) .
					'&amp;wppa-calendar=' . wppa( 'calendar' ) .
					'&amp;wppa-caldate=' . wppa( 'caldate' ) .
					'&amp;wppa-slide=1' .
					( wppa( 'start_album' ) ? '&amp; wppa-album=' . wppa( 'start_album' ) : '' ) .
					'&amp;wppa-occur=' . wppa( 'mocc' );
	}
	elseif ( wppa( 'is_potdhis' ) ) {
		$link = wppa_get_ajaxlink( $args['page'] ) .
					'&amp;wppa-potdhis=1' .
					'&amp;wppa-occur=' . wppa( 'mocc' );
	}
	else {
		$link = '';
	}

	return wppa_encrypt_url( $link );
}

// Pretty links decode
function wppa_convert_from_pretty( $uri ) {

	// Is a pretty link given?
	$wppapos = stripos( $uri, '/wppaspec/' );
	if ( $wppapos === false ) return $uri;

	// Works only on non-default permalinks
	if ( ! wppa_get_option( 'permalink_structure' ) ) return $uri;

	// Remove and save garbage if present
	if ( strpos( $uri, '%3F' ) !== false ) {
		$temp = explode( '%3F', $uri );
		$uri = $temp[0];
		$garbage = isset( $temp[1] ) ? $temp[1] : '';
//		wppa_dump('Garbage1='.$garbage);
	}
	elseif ( strpos( $uri, '?' ) !== false ) {
		$temp = explode( '?', $uri );
		$uri = $temp[0];
		$garbage = isset( $temp[1] ) ? $temp[1] : '';
//		wppa_dump('Garbage2='.$garbage);
	}
	else {
		$garbage = '';
	}

	// copy start up to including slash before wppaspec
	$newuri = substr( $uri, 0, $wppapos + 1 );

	// explode part after wppaspec/
	$args = explode( '/', substr( $uri, $wppapos + 10 ) );

	// process 'arguments'
	if ( is_array( $args ) && count( $args ) > 0 ) {
		$first = true;

		foreach ( $args as $arg ) {
			if ( $first ) $newuri .= '?'; else $newuri .= '&';
			$first = false;
			$code = substr($arg, 0, 2);
			switch ( $code ) {
				case 'ab':
					$deltauri = 'wppa-album=';
					break;
				case 'pt':
					$deltauri = 'wppa-photo=';
					break;
				case 'sd':
					$deltauri = 'wppa-slide=';
					break;
				case 'cv':
					$deltauri = 'wppa-cover=';
					break;
				case 'oc':
					$deltauri = 'wppa-occur=';
					break;
				case 'pg':
					$deltauri = 'wppa-paged=';
					break;
				case 'ss':
					$deltauri = 'wppa-searchstring=';
					break;
				case 'su':
					$deltauri = 'wppa-supersearch=';
					break;
				case 'tt':
					$deltauri = 'wppa-topten=';
					break;
				case 'lt':
					$deltauri = 'wppa-lasten=';
					break;
				case 'ct':
					$deltauri = 'wppa-comten=';
					break;
				case 'ft':
					$deltauri = 'wppa-featen=';
					break;
				case 'ln':
					$deltauri = 'lang=';
					break;
				case 'si':
					$deltauri = 'wppa-single=';
					break;
				case 'tg':
					$deltauri = 'wppa-tag=';
					break;
				case 'po':
					$deltauri = 'wppa-photos-only=';
					break;
				case 'ao':
					$deltauri = 'wppa-albums-only=';
					break;
				case 'mo':
					$deltauri = 'wppa-medals-only=';
					break;
				case 'rl':
					$deltauri = 'wppa-rel=';
					break;
				case 'rc':
					$deltauri = 'wppa-relcount=';
					break;
				case 'ul':
					$deltauri = 'wppa-upldr=';
					break;
				case 'ow':
					$deltauri = 'wppa-owner=';
					break;
				case 'rt':
					$deltauri = 'wppa-rootsearch=';
					break;
				case 'hl':
					$deltauri = 'wppa-hilite=';
					break;
				case 'ca':
					$deltauri = 'wppa-calendar=';
					break;
				case 'cd':
					$deltauri = 'wppa-caldate=';
					break;
				case 'in':
					$deltauri = 'wppa-inv=';
					break;
				case 'vt':
					$deltauri = 'wppa-vt=';
					break;
				case 'cb':
					$deltauri = 'wppa-catbox=';
					break;
				case 'ph':
					$deltauri = 'wppa-potdhis=';
					break;
				case 'ps':
					$deltauri = 'wppa-photos=';
					break;

				default:
					$deltauri = '';
			}

			if ( $deltauri ) {
				$newuri .= $deltauri;
				$newuri .= substr( $arg, 2 );
			}
			else {
				$newuri = rtrim( $newuri, '&' );
			}
		}
	}

	$newuri = wppa_trim_wppa_( $newuri );
	if ( $garbage ) {
		if ( strpos( $newuri, '?' ) === false ) {
			$newuri .= '?' . $garbage;
		}
		else {
			$newuri .= '&' . $garbage;
		}
	}

	return $newuri;
}

// Pretty links Encode
function wppa_convert_to_pretty( $xuri, $no_names = false, $overrule = false ) {

	// Make local copy, decompresse
	$uri = $xuri;

	// Only when permalink structure is not default
	if ( ! wppa_get_option( 'permalink_structure' ) ) return $uri;

	// Not on front page, the redirection will fail...
//	if ( is_front_page() ) {
//		return $uri;
//	}

	// Any querystring?
	if ( strpos( $uri, '?' ) === false ) {
		return $uri;
	}

	// Not during search. Otherwise wppa_test_for_search('at_begin_session') returns '';
	// and this will destroy use and display searchstrings in wppa_begin_session()
	// Fix in 6.3.9
	if ( strpos( $uri, 'searchstring' ) ) {
		return $uri;
	}

	// Not in supersearch: FNumber: f/nn. Replaceing '/' by '%2F' does not work
	if ( wppa( 'supersearch' ) ) {
		return $uri;
	}

	// Re-order
	if ( strpos( $uri, '&amp;' ) !== false ) {
		$amps = true;
		$uri = str_replace( '&amp;', '&', $uri );
	}
	else {
		$amps = false;
	}
	$parts = explode( '?', $uri );
	$args = explode( '&', $parts[1] );
	$order = array( 'occur',
					'searchstring',
					'supersearch',
					'topten', 'lasten', 'comten', 'featen',
					'lang',
					'single',
					'tag',
					'photos-only',
					'albums-only',
					'medals-only',
					'rel',
					'relcount',
					'upldr',
					'owner',
					'rootsearch',
					'slide', 'cover', 'paged',
					'album', 'photo', 'hilite',
					'calendar', 'caldate',
					'inv',
					'vt',
					'catbox',
					'potdhis',
					'photos',
					);

	$uri = $parts[0] . '?';
	$first = true;
	foreach ( $order as $item ) {
		foreach ( array_keys($args) as $argidx ) {
			if ( strpos( $args[$argidx], $item ) === 0 || strpos( $args[$argidx], 'wppa-' . $item ) === 0 ) {
				if ( ! $first ) {
					$uri .= '&';
				}
				$uri .=  $args[$argidx];
				unset ( $args[$argidx] );
				$first = false;
			}
		}
	}
	foreach ( $args as $arg ) {	// append unprocessed items
		$uri .= '&' . $arg;
	}
	if ( $amps ) {
		$uri = str_replace( '&', '&amp;', $uri );
	}

	// First filter for short query args
	$uri = wppa_trim_wppa_( $uri );

	// Now urlencode for funny chars
	$uri = str_replace( array( ' ', '[', ']' ), array( '%20', '%5B', '%5D' ), $uri );

	// Now the actual conversion to pretty links
	if ( wppa_opt( 'use_pretty_links' ) == '-none-' && ! $overrule ) return $uri;
	if ( ! wppa_get_option( 'permalink_structure' ) ) return $uri;

	// Leaving the next line out gives 404 on pretty links under certain circumstances.
	// Can not reproduce and also do not understand why, and do not remember why i have put it in.
	//
	// nov 5 2014: changed add_action to test on redirection form init to pplugins_loaded.
	// also skipped if ( ! isset($_ENV["SCRIPT_URI"]) ) return; in redirect test. See wpp-non-admin.php. Seems to work now
//	if ( ! isset($_ENV["SCRIPT_URI"]) ) return $uri;

	// Do some preprocessing
	$uri = str_replace('&amp;', '&', $uri);
	$uri = str_replace('?wppa-', '?', $uri);
	$uri = str_replace('&wppa-', '&', $uri);

	// Test if querystring exists
	$qpos = stripos($uri, '?');
	if ( ! $qpos ) return $uri;

	// Make sure we end without '/'
	$newuri = trim(substr($uri, 0, $qpos), '/');
	$newuri .= '/wppaspec';

	// explode querystring
	$args = explode('&', substr($uri, $qpos+1));
	$support = array(	'album',
						'photo',
						'slide',
						'cover',
						'occur',
						'page',
						'paged',
						'searchstring',
						'supersearch',
						'topten',
						'lasten',
						'comten',
						'featen',
						'lang',
						'single',
						'tag',
						'photos-only',
						'albums-only',
						'medals-only',
						'rel',
						'relcount',
						'upldr',
						'owner',
						'rootsearch',
						'hilite',
						'calendar',
						'caldate',
						'inv',
						'vt',
						'catbox',
						'potdhis',
						'photos',
					);
	if ( count($args) > 0 ) {
		foreach ( $args as $arg ) {
			$t = explode('=', $arg);
			$code = $t['0'];
			if ( isset($t['1']) ) $val = $t['1']; else $val = '1';
			if ( in_array( $code, $support ) ) {
				$newuri .= '/';
				switch ( $code ) {
					case 'album': 			$newuri .= 'ab'; break;
					case 'photo': 			$newuri .= 'pt'; break;
					case 'slide': 			$newuri .= 'sd'; break;
					case 'cover': 			$newuri .= 'cv'; break;
					case 'occur': 			$newuri .= 'oc'; break;
					case 'page': 			$newuri .= 'pg'; break;
					case 'paged': 			$newuri .= 'pg'; break;
					case 'searchstring': 	$newuri .= 'ss'; break;
					case 'supersearch': 	$newuri .= 'su'; break;
					case 'topten': 			$newuri .= 'tt'; break;
					case 'lasten': 			$newuri .= 'lt'; break;
					case 'comten': 			$newuri .= 'ct'; break;
					case 'featen': 			$newuri .= 'ft'; break;
					case 'lang': 			$newuri .= 'ln'; break;
					case 'single': 			$newuri .= 'si'; break;
					case 'tag': 			$newuri .= 'tg'; break;
					case 'photos-only': 	$newuri .= 'po'; break;
					case 'albums-only': 	$newuri .= 'ao'; break;
					case 'medals-only': 	$newuri .= 'mo'; break;
					case 'rel': 			$newuri .= 'rl'; break;
					case 'relcount': 		$newuri .= 'rc'; break;
					case 'upldr': 			$newuri .= 'ul'; break;
					case 'owner': 			$newuri .= 'ow'; break;
					case 'rootsearch': 		$newuri .= 'rt'; break;
					case 'hilite': 			$newuri .= 'hl'; break;
					case 'calendar': 		$newuri .= 'ca'; break;
					case 'caldate': 		$newuri .= 'cd'; break;
					case 'inv': 			$newuri .= 'in'; break;
					case 'vt': 				$newuri .= 'vt'; break;
					case 'catbox': 			$newuri .= 'cb'; break;
					case 'potdhis': 		$newuri .= 'ph'; break;
					case 'photos': 			$newuri .= 'ps'; break;
					default: wppa_log( 'err', sprintf( 'err', 'Unimplemented code %s encountered in wppa_convert_to_pretty()', $code ) );
				}
				if ( $val !== false ) {
					if ( $code == 'searchstring' ) $newuri .= str_replace(' ', '_', $val);
					else $newuri .= $val;
				}
			}
		}
	}

	return $newuri;
}

// Moderate links
function wppa_moderate_links( $type, $id, $comid = '' ) {

	$thumb = wppa_cache_photo( $id );
	$result = '';

	if ( wppa_user_is_admin() || current_user_can('wppa_moderate') || ( current_user_can('wppa_comments') && $type == 'comment' ) ) {
		switch ( $type ) {
			case 'thumb':
				$app = __('App', 'wp-photo-album-plus' );
				$mod = __('Mod', 'wp-photo-album-plus' );
				$del = __('Del', 'wp-photo-album-plus' );

				$result = '
				<div style="clear:both;"></div>
				<a class="wppa-approve-'.$id.'" style="font-weight:bold; color:green; cursor:pointer" onclick="if ( confirm(\''.__('Are you sure you want to publish this photo?', 'wp-photo-album-plus' ).'\') ) wppaAjaxApprovePhoto(\''.$id.'\')">
					'.$app.
				'</a>
				<a class="wppa-approve-'.$id.'" style="font-weight:bold; color:blue; cursor:pointer" onclick="document.location=\''.get_admin_url().'admin.php?page=wppa_moderate_photos&amp;photo='.$id.'\'" >
					'.$mod.
				'</a>
				<a class="wppa-approve-'.$id.'" style="font-weight:bold; color:red; cursor:pointer" onclick="if ( confirm(\''.__('Are you sure you want to remove this photo?', 'wp-photo-album-plus' ).'\') ) wppaAjaxRemovePhoto('.wppa('mocc').', \''.$id.'\', false)">
					'.$del.
				'</a><br class="wppa-approve-'.$id.'" />';
				break;
			case 'slide':
				$app = __('Approve', 'wp-photo-album-plus' );
				$mod = __('Moderate', 'wp-photo-album-plus' );
				$del = __('Delete', 'wp-photo-album-plus' );

				$result = '
				<div style="clear:both;"></div>
				<a class="wppa-approve-'.$id.'" style="font-weight:bold; color:green; cursor:pointer" onclick="if ( confirm(\''.__('Are you sure you want to publish this photo?', 'wp-photo-album-plus' ).'\') ) wppaAjaxApprovePhoto(\''.$id.'\')">
					'.$app.
				'</a>
				<a class="wppa-approve-'.$id.'" style="font-weight:bold; color:blue; cursor:pointer" onclick="document.location=\''.get_admin_url().'admin.php?page=wppa_moderate_photos&amp;photo='.$id.'\'" >
					'.$mod.
				'</a>
				<a class="wppa-approve-'.$id.'" style="font-weight:bold; color:red; cursor:pointer" onclick="if ( confirm(\''.__('Are you sure you want to remove this photo?', 'wp-photo-album-plus' ).'\') ) wppaAjaxRemovePhoto('.wppa('mocc').', \''.$id.'\', true)">
					'.$del.
				'</a><br class="wppa-approve-'.$id.'" />';
				break;
			case 'comment':
				$app = __('Approve', 'wp-photo-album-plus' );
				$mod1 = __('PhotoAdmin', 'wp-photo-album-plus' );
				$mod2 = __('CommentAdmin', 'wp-photo-album-plus' );
				$del = __('Delete', 'wp-photo-album-plus' );
				$result = '
				<br class="wppa-approve-'.$comid.'" />
				<a class="wppa-approve-'.$comid.'" style="font-weight:bold; color:green; cursor:pointer" onclick="if ( confirm(\''.__('Are you sure you want to publish this comment?', 'wp-photo-album-plus' ).'\') ) wppaAjaxApproveComment(\''.$comid.'\')">
					'.$app.
				'</a>';
				if ( current_user_can('wppa_moderate') && ! wppa_switch( 'moderate_bulk' ) ) $result .= '
				<a class="wppa-approve-'.$comid.'" style="font-weight:bold; color:blue; cursor:pointer" onclick="document.location=\''.get_admin_url().'admin.php?page=wppa_moderate_photos&amp;photo='.$id.'\'" >
					'.$mod1.
				'</a>';
				if ( current_user_can('wppa_comments') || current_user_can('wppa_moderate') ) $result .= '
				<a class="wppa-approve-'.$comid.'" style="font-weight:bold; color:blue; cursor:pointer" onclick="document.location=\''.get_admin_url().'admin.php?page=wppa_manage_comments&amp;commentid='.$comid.'\'" >
					'.$mod2.
				'</a>';
				$result .= '
				<a class="wppa-approve-'.$comid.'" style="font-weight:bold; color:red; cursor:pointer" onclick="if ( confirm(\''.__('Are you sure you want to remove this comment?', 'wp-photo-album-plus' ).'\') ) wppaAjaxRemoveComment(\''.$comid.'\', true)">
					'.$del.
				'</a><br class="wppa-approve-'.$comid.'" />';
				break;
			default:
			wppa_echo( 'error type='.$type );
				break;
		}
	}

	$the_id = $type == 'comment' ? $comid : $id;

	if ( $type == 'comment' || $thumb['status'] != 'scheduled' ) {
//		$result .= '<div class="wppa-approve-'.$the_id.'" style="clear:both; color:red">'.__('Awaiting moderation', 'wp-photo-album-plus' ).'</div>';
	}
	else {
		$result .= '<div class="wppa-approve-'.$the_id.'" style="clear:both; color:red">'.sprintf( __( 'Scheduled for %s' , 'wp-photo-album-plus' ), wppa_format_scheduledtm( $thumb['scheduledtm'] ) ).'</div>';
	}

	return $result;
}

// Get the type of link for an album title. Used in wppa-breadcrumb.php
function wppa_get_album_title_linktype( $alb ) {

	if ( $alb ) {
		$result = wppa_get_album_item( $alb, 'cover_linktype' );
	}
	else {
		$result = '';
	}

	return $result;
}

function wppa_get_slide_callback_url( $id ) {

	$url = wppa_get_permalink();
	if ( wppa( 'start_album' ) ) $url .= 'wppa-album=' . wppa( 'start_album' ) . '&amp;';
	else $url .= 'wppa-album=0&amp;';
	$url .= 'wppa-cover=0&amp;';
	$url .= 'wppa-slide=1&amp;';
	if ( wppa( 'is_single' ) ) $url .= 'wppa-single=1&amp;';
	$url .= 'wppa-occur=' . wppa( 'mocc' ) . '&amp;';
	if ( wppa( 'is_topten' ) ) $url .= 'wppa-topten=' . wppa( 'topten_count' ) . '&amp;';
	if ( wppa( 'is_lasten' ) ) $url .= 'wppa-lasten=' . wppa( 'lasten_count' ) . '&amp;';
	if ( wppa( 'is_comten' ) ) $url .= 'wppa-comten=' . wppa( 'comten_count' ) . '&amp;';
	if ( wppa( 'is_related' ) ) $url .= 'wppa-rel=' . wppa( 'is_related' ) . '&amp;wppa-relcount=' . wppa( 'related_count' ) . '&amp;';
	elseif ( wppa( 'is_tag' ) ) $url .= 'wppa-tag=' . wppa( 'is_tag' ) . '&amp;';
	$url .= 'wppa-photo=' . $id;
//	if ( wppa( 'is_owner' ) ) $url .= 'wppa-owner=' . wppa( 'is_owner' ) . '&amp;';
	if ( wppa( 'is_upldr' ) ) $url .= 'wppa-upldr=' . wppa( 'is_upldr' ) . '&amp;';

	return wppa_encrypt_url( $url );
}

function wppa_get_thumb_callback_url() {

	$url = wppa_get_permalink();
	if ( wppa( 'start_album' ) ) $url .= 'wppa-album=' . wppa( 'start_album' ) . '&amp;';
	else $url .= 'wppa-album=0&amp;';
	$url .= 'wppa-cover=0&amp;';
	if ( wppa( 'is_single' ) ) $url .= 'wppa-single=1&amp;';
	$url .= 'wppa-occur=' . wppa( 'mocc' ) . '&amp;';
	if ( wppa( 'is_topten' ) ) $url .= 'wppa-topten=' . wppa( 'topten_count' ) . '&amp;';
	if ( wppa( 'is_lasten' ) ) $url .= 'wppa-lasten=' . wppa( 'lasten_count' ) . '&amp;';
	if ( wppa( 'is_comten' ) ) $url .= 'wppa-comten=' . wppa( 'comten_count' ) . '&amp;';
	if ( wppa( 'is_related' ) ) $url .= 'wppa-rel=' . wppa( 'is_related' ) . '&amp;wppa-relcount=' . wppa( 'related_count' ) . '&amp;';
	elseif ( wppa( 'is_tag' ) ) $url .= 'wppa-tag=' . wppa( 'is_tag' ) . '&amp;';
//	if ( wppa( 'is_owner' ) ) $url .= 'wppa-owner=' . wppa( 'is_owner' ) . '&amp;';
	if ( wppa( 'is_upldr' ) ) $url .= 'wppa-upldr=' . wppa( 'is_upldr' ) . '&amp;';

	$url = substr($url, 0, strlen($url) - 5);	// remove last '&amp;'

	return wppa_encrypt_url( $url );
}

function wppa_get_upldr_link( $user ) {

	$result = wppa_get_permalink( wppa_opt( 'upldr_widget_linkpage' ) );
	$result .= 'wppa-upldr=' . $user . '&amp;';
	$result .= 'wppa-cover=0&amp;';
	$result .= 'wppa-occur=1';
	$result = str_replace( ' ', '%20', wppa_trim_wppa_( $result ) );

	return $result;
}

function wppa_page_links( $npages = '1', $curpage = '1', $slide = false ) {
global $previous_page_last_id;

	if ( $npages < '2' ) return;	// Nothing to display
	if ( is_feed() ) {
		return;
	}
	if ( ! $curpage ) {
		$curpage = '1';
	}

	$mocc = wppa( 'mocc' );

	// Compose the Previous and Next Page urls

	// Get the main link
	$link_url = wppa_get_permalink();
	$ajax_url = wppa_get_ajaxlink();

	// cover
	if ( wppa_get( 'cover' ) ) $ic = wppa_get( 'cover' );
	else {
		if ( wppa( 'is_cover' ) == '1' ) $ic = '1'; else $ic = '0';
	}
	$extra_url = 'wppa-cover='.$ic;

	// occur
	$occur = wppa_get('occur');
	$ref_occur = wppa( 'mocc' );

	// album
	if ( ( $occur == $ref_occur || wppa( 'ajax' ) ) && wppa_get('album') ) {
			$alb = wppa_get( 'album' );
	}
	elseif ( wppa( 'start_album' ) ) {
		$alb = wppa( 'start_album' );
	}
	else {
		$alb = '0';
	}
	$extra_url .= '&amp;wppa-album='.$alb;

	// slide or photo
	if ( $slide ) {
		$extra_url .= '&amp;wppa-slide=1';
	}
	elseif ( wppa_get( 'photo' ) ) {
		$extra_url .= '&amp;wppa-photo=' . wppa_get( 'photo' );
	}

	// Slideshow timeout
	if ( wppa( 'timeout' ) ) {
		$extra_url .= '&amp;wppa-timeout=' . wppa( 'timeout' );
	}

	// occur
	if ( ! wppa( 'ajax' ) ) {
		$occur = wppa( 'mocc' );
		$extra_url .= '&amp;wppa-occur=' . $occur;
	}
	else {
		if ( wppa_get( 'occur' ) ) {
			$occur = wppa_get( 'occur' );
			$extra_url .= '&amp;wppa-occur=' . $occur;
		}
		else {
			$extra_url .= '&amp;wppa-occur=' . wppa( 'mocc' );	// Should never get here?
		}
	}

	// Topten?
	if ( wppa( 'is_topten' ) ) $extra_url .= '&amp;wppa-topten='.wppa( 'topten_count' );

	// Lasten?
	if ( wppa( 'is_lasten' ) ) $extra_url .= '&amp;wppa-lasten='.wppa( 'lasten_count' );

	// Comten?
	if ( wppa( 'is_comten' ) ) $extra_url .= '&amp;wppa-comten='.wppa( 'comten_count' );

	// Featen?
	if ( wppa( 'is_featen' ) ) $extra_url .= '&amp;wppa-featen='.wppa( 'featen_count' );

	// Tag?
	if ( wppa( 'is_tag' ) && ! wppa( 'is_related' ) ) $extra_url .= '&amp;wppa-tag='.wppa( 'is_tag' );

	// Search?
	if ( wppa( 'src' ) && ! wppa( 'is_related' ) ) $extra_url .= '&amp;wppa-searchstring='.urlencode( wppa( 'searchstring' ) );

	// Supersearch?
	if ( wppa( 'supersearch' ) ) $extra_url .= '&amp;wppa-supersearch=' . str_replace( '/', '%2F', urlencode( wppa( 'supersearch' ) ) );

	// Related
	if ( wppa( 'is_related' ) ) $extra_url .= '&amp;wppa-rel='.wppa( 'is_related' ).'&amp;wppa-relcount='.wppa( 'related_count' );

	// Uploader?
	if ( wppa( 'is_upldr' ) ) $extra_url .= '&amp;wppa-upldr='.wppa( 'is_upldr' );

	// Calendar ?
	if ( wppa( 'calendar' ) ) $extra_url .= '&amp;wppa-calendar='.wppa( 'calendar' ).'&amp;wppa-caldate='.wppa( 'caldate' );

	// Photos only?
	if ( wppa( 'photos_only' ) ) $extra_url .= '&amp;wppa-photos-only=1';

	// Albums only?
	if ( wppa( 'albums_only' ) ) $extra_url .= '&amp;wppa-albums-only=1';

	// Inverse?
	if ( wppa( 'is_inverse' ) ) $extra_url .= '&amp;wppa-inv=1';

	// Almost ready
	$link_url .= $extra_url;
	$ajax_url .= $extra_url;

	// Compress
	$link_url = wppa_trim_wppa_( $link_url );
	$ajax_url = wppa_trim_wppa_( $ajax_url );

	// Encrypt
	$link_url = wppa_encrypt_url( $link_url );
	$ajax_url = wppa_encrypt_url( $ajax_url );

	// Adjust display range
	$from = 1;
	$to = $npages;
	if ( $npages > wppa_opt( 'pagelinks_max' ) ) {
		$delta = floor( wppa_opt( 'pagelinks_max' ) / 2 );
		$from = $curpage - $delta;
		$to = $curpage + $delta;
		while ($from < '1') {
			$from++;
			$to++;
		}
		while ($to > $npages) {
			$from--;
			$to--;
		}
	}

	// Doit

	// Icons
	if ( wppa_get_navigation_type() == 'icons' ) {
		$iconsize = wppa_icon_size( '1.5em' );
		$result = "\n" .
		'<div' .
			' class="wppa-nav-text wppa-box wppa-nav"' .
			' style="clear:both;text-align:center;"' .
			' >';

			$vis = $curpage == '1' ? 'visibility: hidden;' : '';
			$result .=
			'<div' .
				' style="float:left;text-align:left;'.$vis.'"' .
				' >
				<a' .
					' style="cursor:pointer"' .
					' title="' . esc_attr( __( 'Previous page', 'wp-photo-album-plus' ) ) . '"' .
					' onclick="wppaDoAjaxRender( ' . wppa( 'mocc' ) . ', \'' . $ajax_url . '&amp;wppa-paged=' . ( $curpage - 1 ) . '\', \'' . wppa_convert_to_pretty ( $link_url . '&amp;wppa-paged=' . ( $curpage - 1 ) ) . '\' )"' .
					' >' .
					wppa_get_svghtml( 'Prev-Button', $iconsize ) .
				'</a>
			</div><!-- #prev-page -->';

			$vis = $curpage == $npages ? 'visibility: hidden;' : '';
			$result .=
			'<div
				style="float:right;text-align:right;' . $vis . '"
				>
				<a' .
					' id="wppa-next-pagelink-' . $mocc . '"' .
					' style="cursor:pointer"' .
					' title="' . esc_attr( __( 'Next page', 'wp-photo-album-plus' ) ) . '"' .
					' onclick="wppaDoAjaxRender( ' . wppa( 'mocc' ) . ', \'' . $ajax_url . '&amp;wppa-paged=' . ( $curpage + 1 ) . '\', \'' . wppa_convert_to_pretty( $link_url . '&amp;wppa-paged=' . ( $curpage + 1 ) ) . '\')"' .
					' >' .
					wppa_get_svghtml( 'Next-Button', $iconsize ) .
				'</a>
			</div><!-- #next-page -->';

			// The numbered pagelinks ?
			if ( wppa_opt( 'pagelinks_max' ) ) {
				if ( $from > '1' ) {
					$result .= '.&nbsp;.&nbsp;.&nbsp;';
				}
				for ( $i = $from; $i <= $to; $i++ ) {
					if ( $curpage == $i ) {
						$result .=
						'<div' .
							' class="wppa-mini-box wppa-alt wppa-black wppa-active-pagelink"' .
							' style="display:inline;text-align:center;text-decoration:none;cursor:default;"' .
							' >' .
							'&nbsp;' . $i . '&nbsp;' .
						'</div>';
					}
					else {
						$result .=
						'<div' .
							' class="wppa-mini-box wppa-even"' .
							' style="display:inline;text-align:center;"' .
							' >' .

							'<a' .
								' id="wppa-pagelink-' . $mocc . '-' . $i . '"' .
								' style="cursor:pointer"' .
								' onclick="wppaDoAjaxRender( ' . wppa( 'mocc' ) . ', \'' . $ajax_url . '&amp;wppa-paged=' . $i . '\', \'' . wppa_convert_to_pretty( $link_url . '&amp;wppa-paged=' . $i ) . '\')"' .
								' >' .
								'&nbsp;' . $i . '&nbsp;' .
							'</a>';

						$result .=
						'</div>';
					}
				}
				if ( $to < $npages ) {
					$result .=
					'&nbsp;.&nbsp;.&nbsp;.';
				}
			}

			// The 3/17 indicator
			else {
				$result .= $curpage . ' / ' . $npages;
			}

		$result .= '<div style="clear:both"></div>';
		wppa_out( $result );
	}

	// Text
	else {

		$result = "\n" . '
			<div
				class="wppa-nav-text wppa-box wppa-nav"
				style="clear:both; text-align:center;"
				>';

			$vis = $curpage == '1' ? 'visibility: hidden;' : '';
			$result .= '
				<div
					style="float:left; text-align:left; ' . $vis . '"
					>
					<span
						class="wppa-arrow"
						style="cursor:default">&laquo;
					</span>
					<a
						style="cursor:pointer"
						title="' . esc_attr( __('Previous page', 'wp-photo-album-plus') ) . '"
						onclick="wppaDoAjaxRender(' . wppa( 'mocc' ) . ', \'' . $ajax_url . '&amp;wppa-paged=' . ( $curpage - 1 ) . '\', \'' . wppa_convert_to_pretty( $link_url . '&amp;wppa-paged=' . ( $curpage - 1 ) ) . '\')"
						>' .
						__( 'Previous page', 'wp-photo-album-plus' ) . '
					</a>
				</div><!-- #prev-page -->';

			$vis = $curpage == $npages ? 'visibility: hidden;' : '';
			$result .= '
				<div
					style="float:right; text-align:right; ' . $vis . '"
					>
					<a
						id="wppa-next-pagelink-' . $mocc . '"
						style="cursor:pointer"
						title="' . esc_attr( __('Next page', 'wp-photo-album-plus') ) . '"
						onclick="wppaDoAjaxRender(' . wppa( 'mocc' ) . ', \'' . $ajax_url . '&amp;wppa-paged=' . ( $curpage + 1 ) . '\', \'' . wppa_convert_to_pretty( $link_url . '&amp;wppa-paged=' . ( $curpage + 1 ) ) . '\')"
						>' .
						__( 'Next page', 'wp-photo-album-plus' ) . '
					</a>
					<span
						class="wppa-arrow"
						style="cursor:default">&raquo;</span
					>
				</div><!-- #next-page -->';

			// The numbered pagelinks ?
			if ( wppa_opt( 'pagelinks_max' ) ) {
				if ( $from > '1' ) {
					$result .= '.&nbsp;.&nbsp;.&nbsp;';
				}
				for ( $i = $from; $i <= $to; $i++ ) {
					if ( $curpage == $i ) {
						$result .= '
						<div
							class="wppa-mini-box wppa-alt wppa-black wppa-active-pagelink"
							style="display:inline; text-align:center; text-decoration: none; cursor: default;"
							>&nbsp;' . $i . '&nbsp;</div
						>';
					}
					else {
						$result .= '
						<div
							class="wppa-mini-box wppa-even"
							style="display:inline; text-align:center;"
							>
							<a
								id="wppa-pagelink-' . $mocc . '-' . $i . '"
								style="cursor:pointer"
								onclick="wppaDoAjaxRender(' . wppa( 'mocc' ) . ', \'' . $ajax_url . '&amp;wppa-paged=' . $i . '\', \'' . wppa_convert_to_pretty( $link_url . '&amp;wppa-paged=' . $i ) . '\')"
								>&nbsp;'.$i.'&nbsp;</a
							>
						</div>';
					}
				}
				if ( $to < $npages ) {
					$result .= '&nbsp;.&nbsp;.&nbsp;.';
				}
			}

			// The 3/17 indicator
			else {
				$result .= $curpage . ' / ' . $npages;
			}

		$result .= '<div style="clear:both"></div>';
		wppa_out( $result );
	}

	$result = '';

	// A hidden 'first page' link for wrapping from last to first page in a wrapping slideshow
	// and a hidden link to the last item of the previous page for scrolling prev through page boundry
	if ( wppa( 'is_slide' ) ) {
		$result .= '
		<a
			id="wppa-first-pagelink-' . $mocc . '"
			onclick="wppaDoAjaxRender( ' . wppa( 'mocc' ) . ', \'' . $ajax_url . '&amp;wppa-paged=1\', \'' . wppa_convert_to_pretty( $link_url . '&amp;wppa-paged=1' ) . '\')"
			>
		</a>
		<a
			id="wppa-prev-page-last-item-' . $mocc . '"
			onclick="wppaDoAjaxRender( ' . wppa( 'mocc' ) . ', \'' . $ajax_url . '&amp;wppa-photo=' . $previous_page_last_id . '\', \'' . wppa_convert_to_pretty( $link_url . '&amp;wppa-photo=' . $previous_page_last_id ) . '\')"
			>
		</a>';
	}

	$result .= '</div><!-- #prevnext-a-' . wppa( 'mocc' ) . ' -->';
	wppa_out( $result );
}

function wppa_album_download_link( $albumid ) {

	if ( ! wppa_switch( 'allow_download_album' ) ) return;	// Not enabled
	if ( wppa_switch( 'download_album_is_restricted' ) && ! wppa_user_is_admin() ) return; // restricted to admin

	$mocc = wppa( 'mocc' );

	$result = '
	<div style="clear:both"></div>
	<a
		onclick="wppaAjaxDownloadAlbum(' . $mocc . ', \'' . wppa_encrypt_album( $albumid ) . '\' );"
		style="cursor:pointer"
		class="wppa-album-cover-link"
		title="' . esc_attr( __( 'Download', 'wp-photo-album-plus' ) ) . '"
		>' .
		__( 'Download album', 'wp-photo-album-plus' ) . '
	</a>
	<img
		id="dwnspin-' . $mocc . '-' . wppa_encrypt_album( $albumid ) . '"
		src="' . wppa_get_imgdir() . 'spinner.gif"
		style="margin-left:6px; display:none;"
		alt="spin"
	/>';

	wppa_out( $result );
}

function wppa_get_imglnk_a( $wich, $id, $lnk = '', $tit = '', $onc = '', $noalb = false, $album = '' ) {
global $wpdb;

	// make sure the photo data ia available
	$thumb = wppa_cache_photo( $id );
	if ( ! $thumb ) {
		wppa_log('err', 'Cannot cache photo '.$id);
		return false;
	}

	// Init result
	$result['url'] = '';
	$result['title'] = '';
	$result['is_url'] = false;
	$result['is_lightbox'] = false;
	$result['onclick'] = '';
	$result['target'] = '';

	// Is it a video?
	$is_video = wppa_is_video( $id, true );

	// Photo Specific Overrule?
	if ( ( $wich == 'sphoto'     && wppa_switch( 'sphoto_overrule' ) ) ||
		 ( $wich == 'mphoto'     && wppa_switch( 'mphoto_overrule' ) ) ||
		 ( $wich == 'xphoto' 	 && wppa_switch( 'xphoto_overrule' ) ) ||
		 ( $wich == 'thumb'      && wppa_switch( 'thumb_overrule' ) ) ||
		 ( $wich == 'topten'     && wppa_switch( 'topten_overrule' ) ) ||
		 ( $wich == 'featen'	 && wppa_switch( 'featen_overrule' ) ) ||
		 ( $wich == 'lasten'     && wppa_switch( 'lasten_overrule' ) ) ||
		 ( $wich == 'sswidget'   && wppa_switch( 'sswidget_overrule' ) ) ||
		 ( $wich == 'potdwidget' && wppa_switch( 'potdwidget_overrule' ) ) ||
		 ( $wich == 'coverimg'   && wppa_switch( 'coverimg_overrule' ) ) ||
		 ( $wich == 'comten'	 && wppa_switch( 'comment_overrule' ) ) ||
		 ( $wich == 'slideshow'  && wppa_switch( 'slideshow_overrule' ) ) ||
		 ( $wich == 'tnwidget' 	 && wppa_switch( 'thumbnail_widget_overrule' ) ) ) {

		// Look for a photo specific link
		if ( $thumb ) {
			// If it is there...
			if ( $thumb['linkurl'] ) {
				// Use it. It superceeds other settings
				$result['url'] = esc_attr( $thumb['linkurl'] );
				$result['title'] = esc_attr( __( stripslashes( $thumb['linktitle'] ) ) );
				$result['is_url'] = true;
				$result['is_lightbox'] = false;
				$result['onclick'] = '';
				$result['target'] = $thumb['linktarget'];
				return $result;
			}
		}
	}

	$result['target'] = '_self';
	$result['title'] = '';
	$result['onclick'] = '';
	$result['ajax_url'] = '';
	switch ( $wich ) {
		case 'grid':
			$type = wppa_opt( 'grid_linktype' );
			$page = wppa_opt( 'grid_linkpage' );
			if ( wppa_switch( 'grid_blank' ) ) $result['target'] = '_blank';
			$result['url'] = '';
			$result['title'] = '';
			$result['is_url'] = true;
			$result['is_lightbox'] = false;
			$result['onclick'] = '';
			if ( $page == '0' ) {
				$occ = wppa( 'mocc' );
				$can_ajax = true;
				$page = get_the_ID();
			}
			else {
				$occ = '1';
				$can_ajax = false;
			}
			switch ( $type ) {
				case 'none':
					return false;
					break;
				case 'file':
					$result['is_url'] = true;
					$result['url'] = wppa_get_photo_url( $id );
					$result['target'] = '_blank';
					return $result;
					break;
				case 'photo': 	// slideshow
				case 'single': 	// single image
					if ( $type == 'single' ) {
						$a = wppa_get_photo_item( $id, 'album' );
						$result['url'] = wppa_encrypt_url( wppa_get_permalink( $page ) . 'wppa-occur=' . $occ . '&amp;wppa-photo=' . $id );
					}
					elseif ( wppa( 'start_album' ) ) {
						$album = wppa( 'start_album' );
						$result['url'] = wppa_encrypt_url( wppa_get_permalink( $page ) . 'wppa-occur=' . $occ . '&amp;wppa-slide=1&amp;wppa-album=' . $album . '&amp;wppa-photo=' . $id );
					}
					elseif ( wppa( 'start_photos' ) ) {
						$photos = wppa( 'start_photos' );
						$result['url'] = wppa_encrypt_url( wppa_get_permalink( $page ) . 'wppa-occur=' . $occ . '&amp;wppa-slide=1&amp;wppa-photos=' . $photos . '&amp;wppa-photo=' . $id );
					}
					else {
						return false;
					}
					$result['is_url'] = true;
					$result['is_lightbox'] = false;
					break;
				case 'lightbox':
					$result['is_lightbox'] = true;
					if ( $is_video ) {
						$result['url'] = wppa_get_photo_url( $id );
					}
					else {
						if ( ( wppa_switch( 'lb_hres' ) && ! wppa_is_stereo( $id ) && ! wppa_is_panorama( $id ) ) || ( wppa_is_panorama( $id ) && ! wppa_is_mobile() ) ) {
							$result['url'] = wppa_get_hires_url( $id );
						}
						else {
							$siz = array( wppa_get_photox( $id ), wppa_get_photoy( $id ) );
							$result['url'] = wppa_get_photo_url( $id, false, '', $siz['0'], $siz['1'] );
						}
					}
					$result['title'] = __( wppa_get_photo_name( $id ) );
					$result['is_url'] = false;
					break;
			}
			if ( $can_ajax ) {
				$result['ajax_url'] = str_replace( wppa_get_permalink( $page ), wppa_get_ajaxlink(), $result['url'] );
				$result['ajax_url'] = str_replace( '&amp;', '&', $result['ajax_url'] );
			}
			$result['url'] = wppa_convert_to_pretty( $result['url'] );
			return $result;
			break;
		case 'sphoto':
			$type = wppa_opt( 'sphoto_linktype' );
			$page = wppa_opt( 'sphoto_linkpage' );
			if ( $page == '0' ) $page = '-1';
			if ( wppa_switch( 'sphoto_blank' ) ) $result['target'] = '_blank';
			break;
		case 'mphoto':
			$type = wppa_opt( 'mphoto_linktype' );
			$page = wppa_opt( 'mphoto_linkpage' );
			if ( $page == '0' ) $page = '-1';
			if ( wppa_switch( 'mphoto_blank' ) ) $result['target'] = '_blank';
			break;
		case 'xphoto':
			$type = wppa_opt( 'xphoto_linktype' );
			$page = wppa_opt( 'xphoto_linkpage' );
			if ( $page == '0' ) $page = '-1';
			if ( wppa_switch( 'xphoto_blank' ) ) $result['target'] = '_blank';
			break;
		case 'thumb':
			$type = wppa_opt( 'thumb_linktype' );
			$page = wppa_opt( 'thumb_linkpage' );
			if ( wppa_switch( 'thumb_blank' ) ) $result['target'] = '_blank';
			break;
		case 'topten':
			$type = wppa_opt( 'topten_widget_linktype' );
			$page = wppa_opt( 'topten_widget_linkpage' );
			if ( $page == '0' ) $page = '-1';
			if ( wppa_switch( 'topten_blank' ) ) $result['target'] = '_blank';
			break;
		case 'featen':
			$type = wppa_opt( 'featen_widget_linktype' );
			$page = wppa_opt( 'featen_widget_linkpage' );
			if ( $page == '0' ) $page = '-1';
			if ( wppa_switch( 'featen_blank' ) ) $result['target'] = '_blank';
			break;
		case 'lasten':
			$type = wppa_opt( 'lasten_widget_linktype' );
			$page = wppa_opt( 'lasten_widget_linkpage' );
			if ( $page == '0' ) $page = '-1';
			if ( wppa_switch( 'lasten_blank' ) ) $result['target'] = '_blank';
			break;
		case 'comten':
			$type = wppa_opt( 'comment_widget_linktype' );
			$page = wppa_opt( 'comment_widget_linkpage' );
			if ( $page == '0' ) $page = '-1';
			if ( wppa_switch( 'comment_blank' ) ) $result['target'] = '_blank';
			break;
		case 'sswidget':
			$type = wppa_opt( 'slideonly_widget_linktype' );
			$page = wppa_opt( 'slideonly_widget_linkpage' );
			if ( $page == '0' ) $page = '-1';
			if ( wppa_switch( 'sswidget_blank' ) ) $result['target'] = '_blank';
			$result['url'] = '';
			if ( $type == 'lightbox' || $type == 'lightboxsingle' || $type == 'file' ) {
				$result['title'] = wppa_zoom_in( $id );
				$result['target'] = '';
				return $result;
			}
			break;
		case 'potdwidget':
			$type = wppa_opt( 'potd_linktype' );
			$page = wppa_opt( 'potd_linkpage' );
			if ( $page == '0' ) $page = '-1';
			if ( wppa_switch( 'potd_blank' ) ) $result['target'] = '_blank';
			break;
		case 'coverimg':
			$type = wppa_opt( 'coverimg_linktype' );
			$page = wppa_opt( 'coverimg_linkpage' );
			if ( $page == '0' ) $page = '-1';
			if ( wppa_switch( 'coverimg_blank' ) ) $result['target'] = '_blank';
			if ( $type == 'slideshowstartatimage' ) {
				$result['url'] = wppa_get_slideshow_url( array( 'album' => $album,
															    'page' => $page,
																'photo' => $id ) );
				$result['is_url'] = true;
				$result['is_lightbox'] = false;
				return $result;
			}
			break;
		case 'tnwidget':
			$type = wppa_opt( 'thumbnail_widget_linktype' );
			$page = wppa_opt( 'thumbnail_widget_linkpage' );
			if ( $page == '0' ) $page = '-1';
			if ( wppa_switch( 'thumbnail_widget_blank' ) ) $result['target'] = '_blank';
			break;
		case 'slideshow':
			$type = wppa_opt( 'slideshow_linktype' );	//'';
			$page = wppa_opt( 'slideshow_linkpage' );
			$result['url'] = '';
			if ( $type == 'lightbox' || $type == 'lightboxsingle' || $type == 'file' ) {
				$result['title'] = wppa_zoom_in( $id );
				$result['target'] = '';
				return $result;
			}
			if ( $type == 'thumbs' ) {
				$result['url'] = wppa_encrypt_url( wppa_get_ss_to_tn_link( $page, $id ) );
				$result['title'] = __('View thumbnails', 'wp-photo-album-plus' );
				$result['is_url'] = true;
				$result['is_lightbox'] = false;
				if ( wppa_switch( 'slideshow_blank' ) ) $result['target'] = '_blank';
				return $result;
			}
			if ( $type == 'slide' ) {	// Extension for Johnnymosaic
				$t = wppa( 'mocc' );
				if ( $page != '0' ) {
					wppa( 'mocc', '1' );
				}
				$result['url'] = wppa_get_slideshow_url( array( 'album' => wppa( 'start_album' ),
																'page' => $page,
																'photo' => $id ) );
				wppa( 'mocc', $t );
				$result['title'] = __('View fullsize slideshow', 'wp-photo-album-plus' );
				$result['is_url'] = true;
				$result['is_lightbox'] = false;
				if ( wppa_switch( 'slideshow_blank' ) ) $result['target'] = '_blank';
				return $result;
			}
			if ( $type == 'none' ) {
				return;
			}
			if ( $type == 'single' ) {
				if ( wppa_switch( 'slideshow_blank' ) ) $result['target'] = '_blank';
			}
			break;
		case 'albwidget':
		case 'albnavwidget':
			$n = ( $wich == 'albnavwidget' );
			if ( wppa_switch( 'album_' . ( $n ? 'navigator_' : '' ) . 'widget_overrule' ) ) {
				$aid = $album ? $album : wppa_get_photo_item( $id, 'album' );
				$pid = wppa_get_album_item( $aid, 'cover_linkpage' );
				if ( $pid ) {
					$type = wppa_get_album_item( $aid, 'cover_linktype' );
					switch ( $type ) {
						case 'page':
							$type = 'plainpage';
							break;
						case 'albums':
						case 'thumbs':
							$type = 'content';
							break;
						case 'none':
							return false;
							break;
						default:
							break;
					}
					$page = $pid;
				}
				else {
					$type = wppa_opt( 'album_' . ( $n ? 'navigator_' : '' ) . 'widget_linktype' );
					$page = wppa_opt( 'album_' . ( $n ? 'navigator_' : '' ) . 'widget_linkpage' );
					if ( $page == '0' ) $page = '-1';
				}
			}
			else {
				$type = wppa_opt( 'album_' . ( $n ? 'navigator_' : '' ) . 'widget_linktype' );
				$page = wppa_opt( 'album_' . ( $n ? 'navigator_' : '' ) . 'widget_linkpage' );
				if ( $page == '0' ) $page = '-1';
			}
			if ( wppa_switch( 'album_' . ( $n ? 'navigator_' : '' ) . 'widget_blank' ) ) $result['target'] = '_blank';
			break;
		default:
			return false;
			break;
	}

	if ( ! $album ) {
		$album = wppa( 'start_album' );
	}
	if ( $album == '' && ! wppa( 'is_upldr' ) ) {	/**/
		$album = wppa_get_album_id_by_photo_id( $id );
	}
	if ( wppa_is_int( $album ) ) {
		$album_name = wppa_get_album_name( $album );
	}
	else $album_name = '';

	if ( ! $album ) $album = '0';
	if ( $wich == 'comten' ) $album = '0';

//	if ( wppa( 'is_tag' ) ) $album = '0'; // Tags can now also be on an album selecion ( made by album="#cat,cat|#tags,tag" )

	if ( wppa( 'supersearch' ) ) $album = '0';

	if ( wppa( 'calendar' ) ) $album = wppa( 'start_album' ) ? wppa( 'start_album' ) : '0';

	if ( wppa( 'is_potdhis' ) ) $album = '0';

//	if ( wppa( 'is_upldr' ) ) $album = '0';	// probeersel upldr parent

	// owner/public?
	if ( $album == '-3' ) {
		$temp = $wpdb->get_results( "SELECT id FROM $wpdb->wppa_albums WHERE owner = '" . wppa_get_user() . "' OR owner = '--- public ---' ORDER BY id", ARRAY_A );
		$album = '';
		if ( $temp ) {
			foreach( $temp as $t ) {
				$album .= '.' . $t['id'];
			}
			$album = ltrim( $album, '.' );
		}
		$album = wppa_compress_enum( $album );
	}

	if ( $id ) {
		$photo_name = wppa_get_photo_name( $id );
	}
	else $photo_name = '';

	$photo_name_js = esc_js( $photo_name );
	$photo_name = esc_attr( $photo_name );

	if ( $id ) {
		$photo_desc = esc_attr( wppa_get_photo_desc( $id ) );
	}
	else $photo_desc = '';

	$title = __( $photo_name , 'wp-photo-album-plus' );

	$result['onclick'] = '';	// Init
	switch ( $type ) {
		case 'none':		// No link at all
			return false;
			break;
		case 'file':		// The plain file
			if ( $is_video ) {
				$siz = array( wppa_get_videox( $id ), wppa_get_videoy( $id ) );
				$result['url'] = wppa_get_photo_url( $id, false, '', $siz['0'], $siz['1'] );
				reset( $is_video );
				$result['url'] = str_replace( 'xxx', current( $is_video ), $result['url'] );
			}
			else {
				$siz = array( wppa_get_photox( $id ), wppa_get_photoy( $id ) );
				$result['url'] = wppa_get_photo_url( $id, false, '', $siz['0'], $siz['1'] );
			}
			$result['title'] = $title;
			$result['is_url'] = true;
			$result['is_lightbox'] = false;
			$result['url'] = wppa_fix_poster_ext( $result['url'], $id );
			return $result;
			break;
		case 'lightbox':
		case 'lightboxsingle':
			if ( $is_video ) {
				$siz = array( wppa_get_videox( $id ), wppa_get_videoy( $id ) );
				$result['url'] = wppa_get_photo_url( $id, false, '', $siz['0'], $siz['1'] );
				//$result['url'] = str_replace( 'xxx', $is_video['0'], $result['url'] );
			}
			else {
				if ( ( wppa_switch( 'lb_hres' ) && ! wppa_is_stereo( $id ) && ! wppa_is_panorama( $id ) ) || ( wppa_is_panorama( $id ) && ! wppa_is_mobile() ) ) {
					$result['url'] = wppa_get_hires_url( $id );
				}
				else {
					$siz = array( wppa_get_photox( $id ), wppa_get_photoy( $id ) );
					$result['url'] = wppa_get_photo_url( $id, false, '', $siz['0'], $siz['1'] );
				}
			}
			$result['title'] = $title;
			$result['is_url'] = false;
			$result['is_lightbox'] = true;
			$result['url'] = wppa_fix_poster_ext( $result['url'], $id );
			return $result;
		case 'widget':		// Defined at widget activation
			$result['url'] = wppa( 'in_widget_linkurl' );
			$result['title'] = esc_attr( wppa( 'in_widget_linktitle' ) );
			$result['is_url'] = true;
			$result['is_lightbox'] = false;
			return $result;
			break;
		case 'album':		// The albums thumbnails
		case 'content':		// For album widget
		case 'thumbs':
			switch ( $page ) {
				case '-1':
					return false;
					break;
				case '0':
					if ( $noalb ) {
						$result['url'] = wppa_encrypt_url( wppa_get_permalink() . 'wppa-album=0&amp;wppa-cover=0' );
						$result['title'] = ''; // $album_name;
						$result['is_url'] = true;
						$result['is_lightbox'] = false;
					}
					else {
						$result['url'] = wppa_encrypt_url( wppa_get_permalink() . 'wppa-album=' . $album . '&amp;wppa-cover=0' );
						$result['title'] = $album_name;
						$result['is_url'] = true;
						$result['is_lightbox'] = false;
					}
					break;
				default:
					if ( $noalb ) {
						$result['url'] = wppa_encrypt_url( wppa_get_permalink( $page ) . 'wppa-album=0&amp;wppa-cover=0' );
						$result['title'] = ''; //$album_name;//'a++';
						$result['is_url'] = true;
						$result['is_lightbox'] = false;
					}
					else {
						$result['url'] = wppa_encrypt_url( wppa_get_permalink( $page ) . 'wppa-album=' . $album . '&amp;wppa-cover=0' );
						$result['title'] = $album_name;//'a++';
						$result['is_url'] = true;
						$result['is_lightbox'] = false;
					}
					break;
			}
			if ( $type == 'thumbs' && $wich == 'albnavwidget' ) {
				$result['url'] .= '&amp;wppa-photos-only=1';
			}
			break;
		case 'thumbalbum':
			$album = $thumb['album'];
			$album_name = wppa_get_album_name( $album );
			switch ( $page ) {
				case '-1':
					return false;
					break;
				case '0':
					$result['url'] = wppa_encrypt_url( wppa_get_permalink() . 'wppa-album=' . $album . '&amp;wppa-cover=0' );
					$result['title'] = $album_name;
					$result['is_url'] = true;
					$result['is_lightbox'] = false;
					break;
				default:
					$result['url'] = wppa_encrypt_url( wppa_get_permalink( $page ) . 'wppa-album=' . $album . '&amp;wppa-cover=0' );
					$result['title'] = $album_name;//'a++';
					$result['is_url'] = true;
					$result['is_lightbox'] = false;
					break;
			}
			break;
		case 'photo':		// This means: The fullsize photo in a slideshow
		case 'slphoto':		// This means: The single photo in the style of a slideshow
			if ( $type == 'slphoto' ) {
				$si = '&amp;wppa-single=1';
			}
			else {
				$si = '';
			}
			switch ( $page ) {
				case '-1':
					return false;
					break;
				case '0':
					if ( $noalb ) {
						$result['url'] = wppa_encrypt_url( wppa_get_permalink() . 'wppa-album=0&amp;wppa-photo=' . $id . $si );
						$result['title'] = $title;
						$result['is_url'] = true;
						$result['is_lightbox'] = false;
					}
					else {
						$result['url'] = wppa_encrypt_url( wppa_get_permalink() . 'wppa-album=' . $album . '&amp;wppa-photo=' . $id . $si );
						$result['title'] = $title;
						$result['is_url'] = true;
						$result['is_lightbox'] = false;
					}
					break;
				default:
					if ( $noalb ) {
						$result['url'] = wppa_encrypt_url( wppa_get_permalink( $page ) . 'wppa-album=0&amp;wppa-photo=' . $id . $si );
						$result['title'] = $title;
						$result['is_url'] = true;
						$result['is_lightbox'] = false;
					}
					else {
						$result['url'] = wppa_encrypt_url( wppa_get_permalink( $page ) . 'wppa-album=' . $album . '&amp;wppa-photo=' . $id . $si );
						$result['title'] = $title;
						$result['is_url'] = true;
						$result['is_lightbox'] = false;
					}
					break;
			}
			if ( wppa( 'is_potdhis' ) ) {
				$result['url'] .= '&wppa-potdhis=1';
			}
			break;
		case 'single':
			switch ( $page ) {
				case '-1':
					return false;
					break;
				case '0':
					$result['url'] = wppa_encrypt_url( wppa_get_permalink() . 'wppa-photo=' . $id );
					$result['title'] = $title;
					$result['is_url'] = true;
					$result['is_lightbox'] = false;
					break;
				default:
					$result['url'] = wppa_encrypt_url( wppa_get_permalink( $page ) . 'wppa-photo=' . $id );
					$result['title'] = $title;
					$result['is_url'] = true;
					$result['is_lightbox'] = false;
					break;
			}
			break;
		case 'same':
			$result['url'] = $lnk;
			$result['title'] = $tit;
			$result['is_url'] = true;
			$result['is_lightbox'] = false;
			$result['onclick'] = $onc;
			return $result;
			break;
		case 'fullpopup':
			// Only if download photos is enabled and it is a photo
			if ( ! wppa_is_mobile() && wppa_switch( 'art_monkey_on' ) && ( strpos( wppa_opt( 'art_monkey_types' ), 'photo' ) !== false ) && wppa_is_photo( $id ) ) {
				$wid = wppa_get_photox( $id );
				$hig = wppa_get_photoy( $id );
				$url = esc_url( wppa_download_url( $id ) );
				$name = esc_js( wppa_strip_ext( wppa_get_photo_name( $id ) ) . '.' . wppa_get_ext( wppa_get_photo_path( $id ) ) );
				$result['url'] = esc_attr( 'wppaFullPopUp( ' . wppa( 'mocc' ) . ', ' . $id . ', "' . $url . '", ' . $wid . ', ' . $hig . ', "' . $name . '" )' );
				$result['title'] = $title;
				$result['is_url'] = false;
				$result['is_lightbox'] = false;
			}
			else {
				$result = ['url' => '', 'title' => '', 'is_url' => false, 'is_lightbox' => false, 'onclick' => ''];
			}
			return $result;
			break;
		case 'custom':
			if ( $wich == 'potdwidget' ) {
				$result['url'] = wppa_opt( 'potd_linkurl' );
				$result['title'] = wppa_opt( 'potd_linktitle' );
				$result['is_url'] = true;
				$result['is_lightbox'] = false;
				return $result;
			}
			break;
		case 'slide':	// for album widget
			$result['url'] = wppa_encrypt_url( wppa_get_permalink( wppa_opt( 'album_widget_linkpage' ) ) . 'wppa-album=' . $album . '&amp;slide' );
			$result['title'] = '';
			$result['is_url'] = true;
			$result['is_lightbox'] = false;
			break;
		case 'slidealbum': 	// for lasten widget, slide of the photos in te thumbs album
			$album = $thumb['album'];
			$album_name = wppa_get_album_name( $album );
			switch ( $page ) {
				case '-1':
					return false;
					break;
				case '0':
					$result['url'] = wppa_encrypt_url( wppa_get_permalink() . 'wppa-album=' . $album . '&amp;wppa-photo=' . $thumb['id'] );
					$result['title'] = $album_name;
					$result['is_url'] = true;
					$result['is_lightbox'] = false;
					break;
				default:
					$result['url'] = wppa_encrypt_url( wppa_get_permalink( $page ) . 'wppa-album=' . $album . '&amp;wppa-photo=' . $thumb['id'] );
					$result['title'] = $album_name;//'a++';
					$result['is_url'] = true;
					$result['is_lightbox'] = false;
					break;
			}
			break;
		case 'autopage':
			if ( ! wppa_switch( 'auto_page' ) ) {
				wppa_log('err', 'Auto page has been switched off, but there are still links to it (' . $wich . ')' );
				$result['url'] = '';
			}
			else {
				$result['url'] = wppa_get_permalink( wppa_get_the_auto_page( $id ) );
			}
			$result['title'] = '';
			$result['is_url'] = true;
			$result['is_lightbox'] = false;
			break;
		case 'plainpage':
			$result['url'] = get_permalink( $page );
			$result['title'] = $wpdb->get_var( $wpdb->prepare( "SELECT post_title FROM " . $wpdb->prefix . "posts WHERE ID = %s", $page ) );
			$result['is_url'] = true;
			$result['is_lightbox'] = false;
			return $result;
			break;
		default:
			wppa_log( 'Err', 'Wrong type: ' . $type . ' in wppa_get_imglink_a' );
			return false;
			break;
	}

	if ( $type != 'thumbalbum' && $type != 'slidealbum' ) {

		if ( wppa( 'calendar' ) ) {
			$result['url'] .= '&amp;wppa-calendar=' . wppa( 'calendar' ) . '&amp;wppa-caldate=' . wppa( 'caldate' );
		}

		if ( wppa( 'supersearch' ) ) {
			$result['url'] .= '&amp;wppa-supersearch=' . str_replace( '/', '%2F', urlencode( wppa( 'supersearch' ) ) );
		}

		if ( wppa( 'src' ) && ! wppa( 'is_related' ) && ! wppa_in_widget() ) {
			$result['url'] .= '&amp;wppa-searchstring=' . urlencode( wppa( 'searchstring' ) );
		}

		if ( wppa( 'catbox' ) ) {
			$result['url'] .= '&amp;wppa-catbox=' . urlencode( trim( wppa( 'catbox' ), ',' ) );
		}

		if ( $wich == 'topten' ) {
			$result['url'] .= '&amp;wppa-topten=' . wppa_opt( 'topten_count' );
		}
		elseif ( wppa( 'is_topten' ) ) {
			$result['url'] .= '&amp;wppa-topten=' . wppa( 'topten_count' );
		}

		if ( $wich == 'lasten' ) {
			$result['url'] .= '&amp;wppa-lasten=' . wppa_opt( 'lasten_count' );
		}
		elseif ( wppa( 'is_lasten' ) ) {
			$result['url'] .= '&amp;wppa-lasten=' . wppa( 'lasten_count' );
		}

		if ( $wich == 'comten' ) {
			$result['url'] .= '&amp;wppa-comten=' . wppa_opt( 'comten_count' );
		}
		elseif ( wppa( 'is_comten' ) ) {
			$result['url'] .= '&amp;wppa-comten=' . wppa( 'comten_count' );
		}

		if ( $wich == 'featen' ) {
			$result['url'] .= '&amp;wppa-featen=' . wppa_opt( 'featen_count' );
		}
		elseif ( wppa( 'is_featen' ) ) {
			$result['url'] .= '&amp;wppa-featen=' . wppa( 'featen_count' );
		}

		if ( wppa( 'is_related' ) ) {
			$result['url'] .= '&amp;wppa-rel=' . wppa( 'is_related' ) . '&amp;wppa-relcount=' . wppa( 'related_count' );
		}
		elseif ( wppa( 'is_tag' ) ) {
			$result['url']  .= '&amp;wppa-tag=' . wppa( 'is_tag' );
		}

		if ( wppa( 'is_upldr' ) ) {
			$result['url'] .= '&amp;wppa-upldr=' . wppa( 'is_upldr' );
		}

		if ( wppa( 'is_inverse' ) ) {
			$result['url'] .= '&amp;wppa-inv=1';
		}

		if ( wppa( 'medals_only' ) ) {
			$result['url'] .= '&amp;wppa-medals-only=1';
		}

	}

	if ( $page != '0' ) {	// on a different page
		$occur = '1';
	}
	else {				// on the same page, post or widget
		$occur = wppa( 'mocc' );
	}
	$result['url'] .= '&amp;wppa-occur=' . $occur;
	$result['url'] = wppa_encrypt_url( $result['url'] );
	$result['url'] = wppa_convert_to_pretty( $result['url'] );

	if ( $result['title'] == '' ) $result['title'] = $tit;	// If still nothing, try arg

	return $result;
}

// Remove wppa- from query string arguments
function wppa_trim_wppa_( $link ) {
static $trimmable;

	if ( empty( $trimmable ) ) {
		$trimmable = array(	'album',
							'photo',
							'slide',
							'cover',
							'occur',
							'searchstring',
							'topten',
							'lasten',
							'comten',
							'featen',
							'single',
							'photos-only',
							'albums-only',
							'medals-only',
							'relcount',
							'upldr',
							'owner',
							'rootsearch',
							'hilite'
							);
	}
	$result = $link;

	// In wppa_redirect() is $wppa_opt not yet initialized, do not use wppa_switch() to avoid error
	// if ( wppa_switch( 'use_short_qargs' ) ) {
	if ( wppa_get_option( 'wppa_use_short_qargs' ) == 'yes' ) {
		foreach ( $trimmable as $item ) {
			$result = str_replace( 'wppa-'.$item, $item, $result );
		}
	}

	return $result;
}

// Get the link from slideshow to thumbnail view
function wppa_get_ss_to_tn_link( $page = '0', $id = '0' ) {
global $thumbs_ids;

	// Search ?
	if ( wppa( 'src' ) && wppa( 'mocc' ) == '1' && ! wppa( 'is_related' ) ) {
		$thumbhref = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-occur='.wppa( 'mocc' ).'&amp;wppa-searchstring='.stripslashes( wppa( 'searchstring' ) );
	}
	// Uploader ?
	elseif ( wppa( 'is_upldr' ) ) {
		if ( wppa( 'start_album' ) ) {
			$thumbhref = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-occur='.wppa( 'mocc' ).'&amp;wppa-upldr='.wppa( 'is_upldr' ).'&amp;wppa-album='.wppa( 'start_album' );
		}
		else {
			$thumbhref = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-occur='.wppa( 'mocc' ).'&amp;wppa-upldr='.wppa( 'is_upldr' );
		}
	}
	// Topten ?
	elseif ( wppa( 'is_topten' ) ) {
		$thumbhref = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-occur='.wppa( 'mocc' ).'&amp;wppa-topten='.wppa( 'topten_count' ).'&amp;wppa-album='.wppa( 'start_album' );
	}
	// Lasten ?
	elseif ( wppa( 'is_lasten' ) ) {
		$thumbhref = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-occur='.wppa( 'mocc' ).'&amp;wppa-lasten='.wppa( 'lasten_count' ).'&amp;wppa-album='.wppa( 'start_album' );
	}
	// Comten ?
	elseif ( wppa( 'is_comten' ) ) {
		$thumbhref = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-occur='.wppa( 'mocc' ).'&amp;wppa-comten='.wppa( 'comten_count' ).'&amp;wppa-album='.wppa( 'start_album' );
	}
	// Featen ?
	elseif ( wppa( 'is_featen' ) ) {
		$thumbhref = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-occur='.wppa( 'mocc' ).'&amp;wppa-featen='.wppa( 'featen_count' ).'&amp;wppa-album='.wppa( 'start_album' );
	}
	// Related ?
//	elseif ( wppa( 'is_related' ) ) {
//		$thumbhref = '';
//	}
	// Tag ?
	elseif ( wppa( 'is_tag' ) ) {
		$thumbhref = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-occur='.wppa( 'mocc' ).'&amp;wppa-tag='.wppa( 'is_tag' ).'&amp;wppa-album='.wppa( 'start_album' );
	}
	// Cat ?
	elseif ( wppa( 'is_cat' ) ) {
		$thumbhref = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-occur='.wppa( 'mocc' ).'&amp;wppa-cat='.wppa( 'is_cat' ).'&amp;wppa-album='.wppa( 'start_album' );
	}
	// Last ?
//	elseif ( wppa( 'last_albums' ) ) {
//		$thumbhref = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-occur='.wppa( 'mocc' ).'&amp;wppa-album='.wppa( 'start_album' );
//	}
	// Default ?
	else {
		$thumbhref = wppa_get_permalink( $page ).'wppa-cover=0&amp;wppa-occur='.wppa( 'mocc' ).'&amp;wppa-album='.wppa( 'start_album' );
	}

	// $id is the id. See to what page we have to go
	$page = '1';
	$p = wppa_opt( 'thumb_page_size' );
	if ( $p ) {
		$i = '0';
		if ( is_array( $thumbs_ids ) ) foreach ( $thumbs_ids as $ti ) {	// $thumbs_ids is setup in function wppa_prepare_slideshow_pagination()
			if ( $id == $ti ) {
				$page = floor( $i / $p ) + '1';
			}
			$i++;
		}
		if ( $page > '1' ) {
			$thumbhref .= '&amp;wppa-paged='.$page;
		}
	}

	// Make sure the clicked photos thumb is highligted
	$thumbhref .='&amp;wppa-hilite='.$id;

	$thumbhref = wppa_convert_to_pretty( wppa_trim_wppa_( wppa_encrypt_url( $thumbhref ) ) );

	return $thumbhref;
}

// Convert runtime parameters to ajax url.
// Used by wppa_albums() in case a shortcode is delayed
function wppa_runtime_to_ajax_url() {
global $wppa_locale;

	$url = str_replace( '&amp;', '&', wppa_get_ajaxlink() ) . 'lang=' . substr( $wppa_locale, 0, 2 ) . '&wppa-occur=' . wppa( 'mocc' ) . '&';

	// Any album?
	if ( wppa( 'start_album' ) !== '' ) {

		// Numeric or enumerated album(s)?
		if ( is_numeric( wppa( 'start_album' ) ) || wppa_is_enum( wppa( 'start_album' ) ) ) {
			$url .= 'wppa-album=' . wppa( 'start_album' ) . '&';
		}

		// Named album?
		elseif ( substr( wppa( 'start_album' ), 0, 1 ) == '$' ) {
			$url .= 'wppa-album=' . wppa_album_name_to_number( wppa( 'start_album' ) ) . '&';
		}

		// Virtual album?
		elseif ( substr( wppa( 'start_album' ), 0, 1 ) == '#' ) {

			$url .= 'vt=1&';

			$t 		= explode( ',', wppa( 'start_album' ) );
			$type 	= substr( $t[0], 1 );

			if ( in_array( $type, array( 'topten', 'lasten', 'featen', 'comten' ) ) ) {
				$cnt = isset( $t[2] ) ? $t[2] : wppa_opt( $type . '_count' );
				$url .= 'wppa-' . $type . '=' . $cnt . '&';
				if ( isset( $t[1] ) ) $url .= 'wppa-album=' . $t[1] . '&';
			}
			else {
				wppa( 'delayerror', sprintf( __( 'Can not delay shortcode with virtual album type %s', 'wp-photo-album-plus' ), $t[0] ) );
				return '';
			}
		}
	}

	if ( wppa( 'is_filmonly' ) ) 	$url .= 'wppa-filmonly=1&';
	if ( wppa( 'is_slide' ) ) 		$url .= 'wppa-slide=1&';
	if ( wppa( 'is_slideonly' ) ) 	$url .= 'wppa-slideonly=1&';
	if ( wppa( 'is_cover' ) ) 		$url .= 'wppa-cover=1&';// else $url .= 'wppa-cover=0&';
	if ( wppa( 'start_photo' ) ) 	$url .= 'wppa-photo=' . wppa( 'start_photo' ) . '&';
	if ( wppa( 'single_photo' ) ) 	$url .= 'wppa-photo=' . wppa( 'single_photo' ) . '&';
	if ( wppa( 'is_single' ) ) 		$url .= 'wppa-single=1&';
	if ( wppa( 'albums_only' ) ) 	$url .= 'wppa-albums-only=1&';
	if ( wppa( 'photos_only' ) ) 	$url .= 'wppa-photos-only=1&';
	if ( wppa( 'medals_only' ) )	$url .= 'wppa-medals-only=1&';
	if ( wppa( 'calendar' ) ) 		$url .= 'wppa-calendar=' . wppa( 'calendar' ) . '&';
	if ( wppa( 'year' ) ) 			$url .= 'wppa-calendar-year=' . wppa( 'year' ) . '&';
	if ( wppa( 'month' ) ) 			$url .= 'wppa-calendar-month=' . wppa( 'month' ) . '&';
	if ( wppa( 'is_inverse' ) ) 	$url .= 'wppa-inv=1&';
	if ( wppa( 'timeout' ) ) 		$url .= 'wppa-timeout=' . wppa( 'timeout' ) . '&';
	if ( wppa( 'cache' ) ) 			$url .= 'wppa-cache=' . wppa( 'cache' ) . '&';
	elseif ( wppa_get( 'cache' ) ) {
		$url .= 'wppa-cache=' . wppa_get( 'cache' ) . '&';
	}

	$url = rtrim( $url, '&' );

	return $url;
}

// Get the html for Art monkey download feature
function wppa_get_download_html( $id, $where, $label = '' ) {
global $wppa_supported_photo_extensions;

	// Feature enabled?
	if ( ! wppa_switch( 'art_monkey_on' ) ) {
		return $label;
	}

	// Init
	$photo 		= false;
	$video 		= false;
	$audio 		= false;
	$document 	= false;

	// The activated filetypes
	$filetypes 	= explode( ',', wppa_opt( 'art_monkey_types' ) );

	// The name without Extension
	$name = wppa_strip_ext( wppa_get_photo_item( $id, 'name' ) );

	// No ver in urls please
	wppa( 'no_ver', true );

	// Is there an image?
	$ext = wppa_get_ext( wppa_get_photo_path( $id ) );
	if ( in_array( $ext, $wppa_supported_photo_extensions ) && in_array( 'photo', $filetypes ) ) {
		$photo 		= true;
		$photo_ext 	= $ext;
		$photo_url 	= wppa_switch( 'art_monkey_source' ) ? wppa_get_hires_url( $id ) : wppa_get_photo_url( $id );
		if ( basename( $photo_url ) == 'audiostub.jpg' || basename( $photo_url ) == 'documentstub.png' ) {
			$photo = false;
		}
	}

	// Is there a video?
	$video_exts = wppa_is_video( $id );
	if ( $video_exts && in_array( 'video', $filetypes ) ) {
		$video 		= true;
		$video_ext 	= reset( $video_exts );
		$video_url 	= wppa_strip_ext( wppa_get_photo_url( $id, false ) ) . '.' . $video_ext;
	}

	// Is there an audio?
	$audio_exts = wppa_has_audio( $id );
	if ( $audio_exts && in_array( 'audio', $filetypes ) ) {
		$audio 		= true;
		$audio_ext 	= reset( $audio_exts );
		$audio_url 	= wppa_strip_ext( wppa_get_photo_url( $id, false ) ) . '.' . $audio_ext;
	}

	// Is there a document?
	if ( wppa_is_pdf( $id ) && in_array( 'document', $filetypes ) ) {
		$document 		= true;
		$document_ext 	= 'pdf';
		$document_url 	= wppa_strip_ext( wppa_get_hires_url( $id, false ) ) . '.' . $document_ext;
	}

	// reset ver
	wppa( 'no_ver', false );

	// Display type
	$display = wppa_opt( 'art_monkey_display' );

	// Dispatch on where
	switch ( $where ) {

		// May have multiple texts/buttons
		case 'slidedescription':
			$style = 'float:right;margin-right:6px;';
			break;

		// May have only one name/button
		case 'nameonly':
		case 'lightbox':
			$style = '';
			break;

		// Under s/m/x photo
		case 'single':
			$style = 'text-align:center;margin-top:8px;';
			break;

		// Default (should never get here)
		default:
			$style = '';
			break;
	}

	$result = '';
	if ( $photo && ( ( ! $video && ! $audio && ! $document ) || $where == 'slidedescription' ) ) {
		$result .= _wppa_get_download_html( $photo_url, $name . '.' . $photo_ext, $label ? $label : __( 'Download photo', 'wp-photo-album-plus' ), $style, $display );
	}
	if ( $video ) {
		$result .= _wppa_get_download_html( $video_url, $name . '.' . $video_ext, $label ? $label : __( 'Download video', 'wp-photo-album-plus' ), $style, $display );
	}
	if ( $audio ) {
		$result .= _wppa_get_download_html( $audio_url, $name . '.' . $audio_ext, $label ? $label : __( 'Download audio', 'wp-photo-album-plus' ), $style, $display );
	}
	if ( $document ) {
		$result .= _wppa_get_download_html( $document_url, $name . '.' . $document_ext, $label ? $label : __( 'Download document', 'wp-photo-album-plus' ), $style, $display );
	}

	// Done
	return $result;
}

function _wppa_get_download_html( $url, $download, $label, $style, $display ) {

	if ( $display == 'text' ) {

		$result = '
		<div style="cursor:pointer;' . esc_attr( $style ) . '">
		<a
			href="' . esc_url( $url ) . '"
			download="' . esc_attr( $download ) . '"
			title="' . esc_attr( __( 'Download', 'wp-photo-album-plus' ) ) . '"
			data-rel="wppa-download"
			class="wppa-download-text"
			>' .
			$label . '
		</a>
		</div>';

	}
	elseif ( $display == 'button' ) {

		$result = '
		<div style="' . esc_attr( $style ) . '">
		<a
			href="' . esc_url( $url ) . '"
			download="' . esc_attr( $download ) . '"
			title="' . esc_attr( __( 'Download', 'wp-photo-album-plus' ) ) . '"
			data-rel="wppa-download"
			>
			<input
				type="button"
				class="wppa-download-button"
				style="cursor:pointer;"
				value="' . esc_attr( $label ) . '"
			/>
		</a>
		</div>';

	}
	else {
		wppa_log( 'err', 'Unimplemented display type ' . $display . ' in _wppa_get_download_html()' );
		$result = '';
	}

	// Return the result
	return $result;
}
