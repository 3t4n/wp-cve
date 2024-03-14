<?php
/* wppa-input.php
* Package: wp-photo-album-plus
*
* Contains functions for sanitizing and formatting user input
* Version: 8.6.01.001
*
*/

/* CHECK REDIRECTION */
add_action( 'plugins_loaded', 'wppa_redirect', '1' );

function wppa_redirect() {

	if ( ! isset( $_SERVER["REQUEST_URI"] ) ) return;

	$uri = $_SERVER["REQUEST_URI"];
	$wppapos = stripos( $uri, '/wppaspec/' );
	if ( $wppapos === false ) {

		$wppapos = strpos( $uri, '/-/' );
		if ( wppa_get_option( 'wppa_use_pretty_links' ) != 'compressed' ) {
			$wppapos = false;
		}
	}

	if ( $wppapos !== false && wppa_get_option( 'permalink_structure' ) ) {

		// old style solution, still required when qTranslate is active
		$plugins = implode( ',', wppa_get_option( 'active_plugins' ) );
		if ( stripos( $plugins, 'qtranslate' ) !== false ) {

			$newuri = wppa_convert_from_pretty( $uri );
			if ( $newuri == $uri ) return;

			// Although the url is urlencoded it is damaged by wp_redirect when it contains chars like ë, so we do a header() call
			header( 'Location: '.$newuri, true, 302 );
			exit;
		}

		// New style solution
		$newuri = wppa_convert_from_pretty( $uri );
		if ( $newuri == $uri ) return;
		$_SERVER["REQUEST_URI"] = $newuri;
		wppa_convert_uri_to_get( $newuri );
	}
}

// Gert the filter slug to use for the querystring var
function wppa_get_get_filter( $name ) {

	switch ( $name ) {

		// Integer
		case 'occur':
		case 'topten':
		case 'lasten':
		case 'comten':
		case 'featen':
		case 'relcount':
		case 'paged':
		case 'page_id':
		case 'p':
		case 'size':
		case 'fromp':
		case 'forceroot':
		case 'comment-id':
		case 'comid':
//		case 'photoid':
		case 'upload-album':
		case 'user':
		case 'rating':
		case 'index':
		case 'next-after':
		case 'commentid':
		case 'bulk-album':
		case 'set-album':
		case 'photo-album':
		case 'video-album':
		case 'audio-album':
		case 'document-album':
		case 'del-id':
		case 'move-album':
		case 'parent-id':
		case 'is-sibling-of':
		case 'sub':
		case 'subtab':
		case 'pano-val':
		case 'album-page-no':
		case 'high':
		case 'albumeditid':
		case 'album-parent':
		case 'captcha':
		case 'import-remote-max':
		case 'comment-edit':
		case 'del-after-p':
		case 'del-after-fp':
		case 'del-after-f':
		case 'del-after-a':
		case 'del-after-fa':
		case 'del-after-z':
		case 'del-after-fz':
		case 'del-after-v':
		case 'del-after-fv':
		case 'del-after-u':
		case 'del-after-fu':
		case 'del-after-c':
		case 'del-after-fc':
		case 'del-after-d':
		case 'del-after-fd':
		case 'del-dir-cont':
		case 'zoom':
		case 'parent_id':
		case 'timeout':
		case 'mocc':
			$result = 'int';
			break;

		// Array of integers
		case 'commentids':
			$result = 'intarr';
			break;

		// Boolean
		case 'cover':
		case 'slide':
		case 'slideonly':
		case 'filmonly':
		case 'single':
		case 'photos-only':
		case 'albums-only':
		case 'medals-only':
		case 'rel':
		case 'rootsearch':
		case 'potdhis':
		case 'inv':
		case 'vt':
		case 'catbox':
		case 'resp':
		case 'quick':
		case 'continue':
		case 'del-dir':
		case 'use-backup':
		case 'update':
		case 'superview':
		case 'nodups':
		case 'raw':
		case 'bulk':
		case 'applynewdesc':
		case 'remakealbum':
		case 'search-submit':
		case 'export-submit':
		case 'blogit':
		case 'cron':
		case 'seq':
		case 'fe-create':
			$result = 'bool';
			break;

		// Searchstring
		case 'searchstring':
		case 's':
			$result = 'src';
			break;

		// Html
		case 'comment':
		case 'commenttext':
		case 'upn-description':
		case 'user-desc': 		// Desc by user during fe upload
		case 'albumeditdesc': 	// Fe album desc
			$result = 'html';
			break;

		// Tags / Cats
		case 'tag':
		case 'tags':
		case 'upn-tags':
		case 'new-tags':
			$result = 'tags';
			break;

		// Custom data
		case 'custom_0':
		case 'custom_1':
		case 'custom_2':
		case 'custom_3':
		case 'custom_4':
		case 'custom_5':
		case 'custom_6':
		case 'custom_7':
		case 'custom_8':
		case 'custom_9':
			$result = 'custom';
			break;

		// Text
		case 'supersearch':
		case 'lang':
		case 'wppalocale':
		case 'calendar':
		case 'upldr':
		case 'owner':
		case 'nonce':
		case 'user-name': 	// Photo/video name supplied by user
		case 'ntfy-nonce':
		case 'qr-nonce':
		case 'crypt':
		case 'slug':
		case 'just-edit':
		case 'filter':
		case 'orderby':
		case 'order':
		case 'bulk-status':
		case 'bulk-owner':
		case 'watermark-file':
		case 'watermark-pos':
		case 'cre-album':
		case 'bulk-action':
		case 'action':
		case 'option':
		case 'local-remote':
		case 'upn-name':
		case 'del-confirm':
		case 'del-photos':
		case 'tab':
		case 'edit-id':
		case 'settings-submit':
		case 'key':
		case 'subtab':
		case 'switchto':
		case 'order_by':
		case 'comname':
		case 'value':
		case 'post-title':
		case 'blogit-pretext':
		case 'blogit-posttext':
		case 'update-check':
		case 'import-ajax-file':
		case 'import-submit':
		case 'delete':
		case 'cache':
		case 'item':
		case 'error':
		case 'list':
		case 'onoff':
		case 'albumeditnonce':
		case 'albumeditsubmit':
		case 'album-desc':
		case 'album-name':
		case 'type':
		case 'import-set-source-url':
		case 'caldate':
		case 'shortcode':
		case 'subsearch':
		case 'rootsearch':
		case 'page':
		case 'comment-status':
		case 'exiftag':
		case 'table':
			$result = 'text';
			break;

		// Possibly encrypted or named photo(s)
		case 'photo':
		case 'photos':
		case 'hilite':
		case 'photo-id':
		case 'rating-id':
	case 'photoid':
			$result = 'pcrypt';
			break;

		// Possibly encrypted or nemed album
		case 'album':
		case 'album-id':
			$result = 'acrypt';
			break;

		// Email
		case 'comemail':
			$result = 'email';
			break;

		// Url
		case 'url':
		case 'returnurl':
		case 'source-remote':
			$result = 'url';
			break;

		// Array text
		case 'bulk-photo':
			$result = 'arraytxt';
			break;

		default:
			$result = 'raw';
			break;
	}

	return $result;
}

// Retrieve a get- or post- variable, sanitized and post-processed
function wppa_get( $xname, $default = false, $filter = false ) {

	// Sanitize
	$xname 		= sanitize_text_field( $xname );
	$default 	= $default ? sanitize_text_field( $default ) : false;
	$filter 	= $filter ? sanitize_text_field( $filter ) : '';

	// Ajax call ?
	if ( $xname == 'wppa-action' ) {
		if ( isset( $_REQUEST['wppa-action'] ) ) {
			$result = sanitize_text_field( $_REQUEST['wppa-action'] );
			return $result;
		}
		else {
			return $default;
		}
	}

	// Normalize $name and $xname
	if ( substr( $xname, 0, 5 ) == 'wppa-' ) {
		$name = substr( $xname, 5 );
	}
	else {
		$name = $xname;
		$xname = 'wppa-' . $name;
	}

	// Find the key if any
	if ( isset( $_REQUEST[$xname] ) ) {		// with prefix wppa-
		$key = $xname;
	}
	elseif ( isset( $_REQUEST[$name] ) ) {	// without prefix wppa-
		$key = $name;
	}
	else {									// neither
		return $default;
	}

	// Get the right filter
	if ( ! $filter ) {
		$filter = wppa_get_get_filter( $name );
	}

	// Now we have the right key for $request and the right sanitize / validate scheme
	// Do the filtering
	switch ( $filter ) {

		case 'int':
			$result = strval( intval ( $_REQUEST[$key] ) );
			break;

		case 'posint':
			$result = max( '1', strval( intval ( $_REQUEST[$key] ) ) );
			break;

		case 'intarr':
			if ( is_array( $_REQUEST[$key] ) ) {
				$value = array();
				foreach( array_keys( $_REQUEST[$key] ) as $i ) {
					$value[$i] = strval( intval( $_REQUEST[$key][$i] ) );
				}
			}
			else {
				$value = strval( intval( $_REQUEST[$key] ) );
			}
			$result = $value;
			break;

		case 'bool':
			if ( $_REQUEST[$key] !== '0' && $_REQUEST[$key] != 'nil' && $_REQUEST[$key] != 'no' ) {
				$result = '1';
			}
			else {
				$result = '0';
			}
			break;

		case 'src':
			$result = wppa_sanitize_searchstring( sanitize_text_field( $_REQUEST[$key] ) );
			break;

		case 'html':
			if ( current_user_can( 'unfiltered_html' ) ) {
				$result = wppa_echo( force_balance_tags( $_REQUEST[$key] ), '', '', true, true );
			}
			else {
				$result = strip_tags( $_REQUEST[$key] );
			}
			break;

		case 'tag':
		case 'tags':
		case 'cat':
			$result = trim( wppa_sanitize_tags( sanitize_text_field( $_REQUEST[$key] ), ',' ) );
			break;

		case 'custom':
			$result = wppa_sanitize_custom_field( $_REQUEST[$key] );
			break;

		case 'textarea':
			$result = stripslashes( sanitize_textarea_field( $_REQUEST[$key] ) );
			break;

		case 'text':
			$result = stripslashes( sanitize_text_field( $_REQUEST[$key] ) );
			break;

		case 'pcrypt':
			$result = wppa_decode_photo( sanitize_text_field( $_REQUEST[$key] ) );
			break;

		case 'acrypt':
			$result = wppa_decode_album( sanitize_text_field( $_REQUEST[$key] ) );
			break;

		case 'email':
			$result = sanitize_email( $_REQUEST[$key] );
			break;

		case 'url':
			$result = esc_url_raw( $_REQUEST[$key] );
			break;

		case 'arraytxt':
			$result = array();
			foreach ( array_keys( $_REQUEST[$key] ) as $i ) {
				$result[$i] = sanitize_text_field( $_REQUEST[$key][$i] );
			}
			break;

		case 'strip':
			$result = strip_tags( $_REQUEST[$key] );
			break;

		case 'php':
			$result = str_replace( ['%26', '%23', '%2B', '\\', '<?php'], ['&', '#', '+', '', ''], $_REQUEST['value'] );
			break;

		case 'gutsc':
			$result = stripslashes( $_REQUEST[$key] ); // str_replace( '%23', '#', stripslashes( $_REQUEST['shortcode'] ) );
			break;

		case 'raw':
			wppa_log( 'err', $filter.' Unfiltered (raw) querystring arg = ' . $name . ', value =  ' . sanitize_text_field( var_export( $_REQUEST[$key], true ) ) );
			$result = sanitize_text_field( $_REQUEST[$key] );
			break;

		case 'textarea':
			$result = esc_textarea( $_REQUEST[$key] );
			break;

		default:
			wppa_log( 'err', 'Unknown filter '.$filter.' for querystring arg = ' . $name . ', value =  ' . sanitize_text_field( var_export( $_REQUEST[$key], true ) ) );
			$result = sanitize_text_field( $_REQUEST[$key] );
			break;
	}

	return $result;
}

// Sanitize a searchstring
function wppa_sanitize_searchstring( $str ) {

	$result = remove_accents( $str );
	$result = strip_tags( $result );
	$result = stripslashes( $result );
	$result = str_replace( array( "'", '"', ':', ), '', $result );
	$temp 	= explode( ',', $result );
	foreach ( array_keys( $temp ) as $key ) {
		$temp[$key] = trim( $temp[$key] );
	}
	$result = implode( ',', $temp );

	return $result;
}

// Retrieve a cookie, sanitized and verified
function wppa_get_cookie( $name, $default = '' ) {

	// Sanitize
	$name 		= sanitize_text_field( $name );
	$default 	= sanitize_text_field( $default );

	// Validate
	if ( isset( $_COOKIE[$name] ) ) {

		$result = sanitize_text_field( $_COOKIE[$name] );
	}
	else {
		$result = $default;
	}

	return $result;
}