<?php
/* wppa-common-functions.php
*
* Functions used in admin and in themes
* Version: 8.6.04.002
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Initialize globals and option settings
function wppa_initialize_runtime( $force = false, $reset = true ) {
global $wppa;
global $wppa_opt;
global $wppa_revno;
global $wppa_version;
global $wppa_defaults;

	if ( is_array( $wppa ) && is_array( $wppa_opt ) && ! $force ) {
		return; 	// Done already
	}

	// Rebuild options array, start with the defaults
	wppa_set_defaults();

	// Now find the non defaults
	$non_defaults = wp_load_alloptions();

	// Init with defaults
	$wppa_opt = $wppa_defaults;

	// Check all existing wppa_xxx options
	foreach( array_keys( $non_defaults ) as $name ) {

		$value = $non_defaults[$name];

		// If it is a setting
		if ( isset( $wppa_opt[$name] ) ) {

			// If it is not a default value, copy value in optins array
			if ( $value != $wppa_opt[$name] ) {
				$wppa_opt[$name] = $value;
			}

			// It is default, remove it
			else {
				delete_option( $name );
			}
		}
	}

	// Fix mobile/fixed dependant settings
	if ( wppa_is_mobile() ) {
		if ( $wppa_opt['wppa_thumbtype'] == 'masonry-mix' ) {
			$wppa_opt['wppa_thumbtype'] = 'masonry-plus';
		}
	}
	else {
		if ( $wppa_opt['wppa_thumbtype'] == 'masonry-mix' ) {
			$wppa_opt['wppa_thumbtype'] = 'masonry-v';
		}
	}

	// Reset runtime parms
	if ( $reset ) wppa_reset_occurrance();

	// Check if grant parents must be made
	// If there is only one grant parent, make sure the granted album is created regardless of if it is used.
	if ( ! wppa_is_cron() && ! wppa( 'ajax' ) ) {
		if ( wppa_is_int( wppa_get_option( 'wppa_grant_parent' ) ) ) {
			wppa_grant_albums();
		}
	}
}

function wppa_reset_occurrance() {
global $wppa;
global $wppa_revno;
global $wppa_version;
static $first_pla = true;
global $wppa_current_shortcode;
global $wppa_current_shortcode_atts;

	$wppa_current_shortcode = false;
	$wppa_current_shortcode_atts = false;

	$mocc 	= isset( $wppa['mocc'] ) ? $wppa['mocc'] : '0';
	$pano 	= isset( $wppa['has_panorama'] ) ? $wppa['has_panorama'] : false;
	$ajax 	= defined( 'DOING_AJAX' ) ? true : false;
	$cron 	= wppa_is_cron();

	// Non wppa ajax? External ajax operation, maybe from pla, called us
	if ( defined( 'DOING_AJAX' ) && ! defined( 'DOING_WPPA_AJAX' ) ) {
		$ajax = false;
		if ( $first_pla ) {
			$first_pla = false;
			$mocc = wppa_get( 'page', strval(rand(200,400)), 'int' ); // Kind of random
		}
	}

	// If doing wppa ajax, get occur from query arg
	elseif ( defined( 'DOING_WPPA_AJAX' ) ) {
		$m = wppa_get( 'occur' );
		if ( $m ) {
			$mocc = $m;
		}
	}

	$wppa = array (
		'revno' 					=> $wppa_revno,				// set in wppa.php
		'api_version' 				=> $wppa_version,		// set in wppa.php
		'fullsize' 					=> '',
		'enlarge' 					=> false,
		'mocc' 						=> $mocc,
		'in_widget' 				=> false,
		'is_cover' 					=> '0',
		'is_slide' 					=> '0',
		'is_slideonly' 				=> '0',
		'is_slideonlyf'				=> '0',
		'is_filmonly'				=> '0',
		'film_on' 					=> '0',
		'browse_on' 				=> '0',
		'name_on' 					=> '0',
		'desc_on' 					=> '0',
		'numbar_on' 				=> '0',
		'single_photo' 				=> '',
		'is_mphoto' 				=> '0',
		'is_xphoto' 				=> '0',
		'start_album' 				=> '',
		'align' 					=> '',
		'src' 						=> false,
		'portrait_only' 			=> false,
		'in_widget_linkurl' 		=> '',
		'in_widget_linktitle' 		=> '',
		'ss_widget_valign' 			=> '',
		'album_count' 				=> '0',
		'thumb_count' 				=> '0',
		'out' 						=> '',
		'auto_colwidth' 			=> false,
		'permalink' 				=> '',
		'tabcount' 					=> '0',
		'comment_id' 				=> '',
		'comment_photo' 			=> '0',
		'comment_user' 				=> '',
		'comment_email' 			=> '',
		'comment_text' 				=> '',
		'no_default' 				=> false,
		'in_widget_frame_height' 	=> '0',
		'in_widget_frame_width'		=> '0',
		'current_album'				=> '0',
		'searchstring'				=> wppa_test_for_search(),
		'searchresults'				=> '',
		'any'						=> false,
		'ajax'						=> $ajax,
		'error'						=> false,
		'iptc'						=> false,
		'exif'						=> false,
		'is_topten'					=> false,
		'topten_count'				=> '0',
		'is_lasten'					=> false,
		'lasten_count'				=> '0',
		'is_featen'					=> false,
		'featen_count'				=> '0',
		'start_photo'				=> '0',
		'is_single'					=> false,
		'is_landing'				=> '0',
		'is_comten'					=> false,
		'comten_count'				=> '0',
		'is_tag'					=> false,
		'photos_only'				=> false,
		'albums_only'				=> false,
		'medals_only' 				=> false,
		'page'						=> '',
		'geo'						=> '',
		'continue'					=> '',
		'is_upload'					=> false,
		'ajax_import_files'			=> false,
		'ajax_import_files_done'	=> false,
		'ajax_import_files_error' 	=> '',
		'last_albums'				=> false,
		'last_albums_parent'		=> '0',
		'is_multitagbox' 			=> false,
		'is_tagcloudbox' 			=> false,
		'taglist' 					=> '',
		'tagcols'					=> '2',
		'is_related'				=> false,
		'related_count'				=> '0',
		'is_owner'					=> '',
		'is_upldr'					=> '',
		'no_esc'					=> false,
		'front_edit'				=> false,
		'is_autopage'				=> false,
		'is_cat'					=> false,
		'bestof' 					=> false,
		'is_subsearch' 				=> false,
		'is_rootsearch' 			=> false,
		'is_superviewbox' 			=> false,
		'is_searchbox'				=> false,
		'may_sub'					=> false,
		'may_root'					=> false,
		'links_no_page' 			=> array( 'none', 'file', 'lightbox', 'lightboxsingle', 'fullpopup' ),
		'shortcode_content' 		=> '',
		'is_remote' 				=> false,
		'is_supersearch' 			=> false,
		'supersearch' 				=> '',
		'is_mobile' 				=> wppa_is_mobile(),
		'is_wppa_tree' 				=> false,
		'is_calendar' 				=> false,
		'calendar' 					=> '',
		'caldate' 					=> '',
		'reverse' 					=> false,
		'current_photo' 			=> false,
		'is_stereobox' 				=> false,
		'npages' 					=> '',
		'curpage'					=> '',
		'ss_pag' 					=> false,
		'slideframewidth' 			=> '',
		'slideframeheight' 			=> '',
		'ajax_import_files_error' 	=> '',
		'src_script' 				=> '',
		'is_url' 					=> false,
		'is_inverse' 				=> false,
		'coverphoto_pos' 			=> '',
		'forceroot' 				=> '',
		'landingpage' 				=> '',
		'is_admins_choice' 			=> false,
		'admins_choice_users' 		=> '',
		'for_sm' 					=> false,
		'max_width' 				=> false,
		'no_ver' 					=> false,
		'catbox' 					=> '',
		'is_pdf' 					=> false,
		'is_button' 				=> '',
		'max_slides_in_ss_widget' 	=> '',
		'is_random' 				=> false,
		'cron' 						=> $cron,
		'has_panorama' 				=> $pano,
		'unsanitized_filename' 		=> '',
		'fromp' 					=> '',
		'is_combinedsearch' 		=> false,
		'delay' 					=> false,
		'comneedsvote' 				=> false,
		'year' 						=> '',
		'month' 					=> '',
		'cache' 					=> '0',
		'delayerror' 				=> '',
		'is_potdhis' 				=> false,
		'is_contest' 				=> false,
		'start_photos' 				=> '',
		'is_grid' 					=> false,
		'gridcols' 					=> '1',
		'landscape' 				=> '',
		'portrait' 					=> '',
		'is_audioonly' 				=> false,
		'audio_item' 				=> '',
		'audio_album' 				=> '',
		'audio_poster' 				=> '',
		'is_notify' 				=> false,
		'is_virtual' 				=> false,
		'timeout' 					=> '',
		'is_preview' 				=> false,
		'anon' 						=> '',
		'meonly' 					=> '',
		'container-wrapper-class' 	=> '',
	);
}

function wppa_get_randseed( $type = '' ) {
global $wppa_session;
global $wppa_volitile_randseed;
global $wppa_randseed_modified;

	switch ( $type ) {

		// This randseed survives pageloads up to the duration of the session ( usually 1 hour )
		case 'session':
			$result = $wppa_session['id'];
			break;

		// This randseed is for one pageload only
		case 'page':

			// Reset the default randseed
			wppa_renew_randseed();

			// Not Been here before?
			if ( ! $wppa_volitile_randseed ) {

				// Make new pageload specific rsandseed
				$wppa_volitile_randseed = time() % 7487;
			}
			$result = $wppa_volitile_randseed;
			break;

		// This randseed is new for each pagelad and all subsequent ajax calls.
		// It must remain at lease 30 seconds the same, even when non ajax pageloads happen.
		// This is to survive themes that do a reload right after the beginning.
		default:

			// Init, get the now saved in the session
			if ( isset( $wppa_session['randseed'] ) && $wppa_session['randseed'] ) {
				$result = $wppa_session['randseed'];
			}

			// No saved one, initialize randseed, save it and save the time we made it
			else {
				$result = time() % 4721;
				$wppa_session['randseed'] = $result;
				$wppa_session['randseedtime'] = time();

//				wppa_save_session();
			}

			// We have a seed. Assume we need to renew it
			$renew = true;

			// The following conditions make we do not need to renew
			if ( $wppa_randseed_modified ) $renew = false; 										// Already renewed this pageload
			if ( wppa( 'ajax' ) ) $renew = false; 												// Not during ajax call
			if ( strpos( $_SERVER['REQUEST_URI'], 'wp-content' ) !== false ) $renew = false; 	// Url to a content item needs no renew
			if ( wppa_get( 'paged', '0' ) ) $renew = false; 								// A subsequent wppa page needs the same sequence
			if ( isset( $wppa_session['randseedtime'] ) && $wppa_session['randseedtime'] > ( time() - 10 ) ) $renew = false; // Not within 10 seconds

			// Still renew if the current wp page differs from the last
			$wp_page = wppa_get( 'fromp' ) ? wppa_get( 'fromp' ) : get_the_ID();
			if ( isset( $wppa_session['randpage'] ) && $wp_page && $wp_page != $wppa_session['randpage'] ) {
				$renew = true;
			}

			// Make new
			if ( $renew ) {
				$result = wppa_renew_randseed();
			}
	}

	// Return the randseed
	return $result;
}

function wppa_renew_randseed() {
global $wppa_session;
global $wppa_volitile_randseed;
global $wppa_randseed_modified;

	// Make new saved randseed
	$result = time() % 4721;
	$wppa_session['randseed'] = $result;
	$wppa_session['randseedtime'] = time();
	$p = wppa_get( 'fromp' ) ? wppa_get( 'fromp' ) : get_the_ID();
	if ( $p ) {
		$wppa_session['randpage'] = $p;
	}

	// Indicaate we found a new randseed during this pageload
	$wppa_randseed_modified = true;

	// Make sure we save it
//	wppa_save_session();

	return $result;
}

// get the url to the plugins image directory
function wppa_get_imgdir( $file = '', $rel = false ) {

	$result = WPPA_URL . '/img/';
	if ( is_ssl() ) $result = str_replace( 'http://', 'https://', $result );
	$result .= $file;

	$result = wppa_make_relative( $result, $rel );
	return $result;
}

// Protect url against making relative
function wppa_protect_relative( $url ) {

	// Init
	$result = $url;

	// Only if feature enabled
	if ( wppa_get_option( 'wppa_relative_urls' ) == 'yes' ) {
		$result = str_replace( 'http', 'h t t p ', $result );
	}

	return $result;
}

// Make url relative
function wppa_make_relative( $url, $rel = '' ) {

	// Init
	if ( ! $url ) {
		return '';
	}
	$result = $url;

	// Can not use wppa_opt(). $wppa_opt is not initialized when called from wppa_set_defaults
	if ( $rel != 'abs' ) {	// Not if absulute is explicitly requested
		if ( wppa_get_option( 'wppa_relative_urls' ) == 'yes' || $rel == 'rel' ) {
			if ( isset( $_ENV['HTTP_HOST'] ) ) {
				if ( is_ssl() ) {
					$result = str_replace( 'https://' . $_ENV['HTTP_HOST'], '', $result );
				}
				else {
					$result = str_replace( 'http://' . $_ENV['HTTP_HOST'], '', $result );
				}
			}
		}
	}

	// Repair protected urls
	$result = str_replace( 'h t t p ', 'http', $result );
	$result = str_replace( 'h+t+t+p+', 'http', $result );

	return $result;
}

function wppa_get_wppa_url() {

	$result = WPPA_URL;
	if ( is_ssl() ) $result = str_replace( 'http://', 'https://', $result );
	return $result;
}

// get album order
function wppa_get_album_order( $parent = '0' ) {
global $wppa;

	// Init
    $result = '';
	$order = '0';

	// Album given ?
	if ( $parent > '0' ) {
		$album = wppa_cache_album( $parent );
		if ( $album ) {
			$order = $album['suba_order_by'];
		}
	}
	if ( ! $order ) $order = wppa_opt( 'list_albums_by' );

	switch ( $order ) {
		case '':
		case '0':
			$result = '';
			break;
		case '1':
			$result = 'ORDER BY a_order';
			break;
		case '-1':
			$result = 'ORDER BY a_order DESC';
			break;
		case '2':
			$result = 'ORDER BY name';
			break;
		case '-2':
			$result = 'ORDER BY name DESC';
			break;
		case '3':
			$result = 'ORDER BY RAND( '.wppa_get_randseed().' )';
			break;
		case '5':
			$result = 'ORDER BY timestamp';
			break;
		case '-5':
			$result = 'ORDER BY timestamp DESC';
			break;
		default:
	}

	return $result;
}

function wppa_get_album_order_column( $parent = '0' ) {

	// Album given ?
	if ( $parent > '0' ) {
		$order = wppa_get_album_item( $parent, 'suba_order_by' );
	}
	else {
		$order = '0';
	}
	if ( ! $order ) {
		$order = wppa_opt( 'list_albums_by' );
	}

	switch ( $order ) {
		case '1':
		case '-1':
			$result = 'a_order';
			break;
		case '2':
		case '-2':
			$result = 'name';
			break;
		case '3':
			$result = 'random';
			break;
		case '5':
		case '-5':
			$result = 'timestamp';
			break;
		default:
			$result = 'id';
	}

	return $result;
}

function wppa_is_album_order_desc( $parent = '0' ) {

	// Album given ?
	if ( $parent > '0' ) {
		$order = wppa_get_album_item( $parent, 'suba_order_by' );
	}
	else {
		$order = '0';
	}
	if ( ! $order ) {
		$order = wppa_opt( 'list_albums_by' );
	}

	return ( $order < '0' ) ? "DESC" : "";
}

// get photo order
function wppa_get_photo_order( $id = '0', $no_random = false ) {
global $wppa;

	// Random overrule?
	if ( wppa( 'is_random' ) ) {
		$result = " ORDER BY RAND(" . time() % 4711 . ")";
		return $result;
	}

	// Filmonly random?
	if ( wppa( 'is_filmonly' ) && wppa_switch( 'filmonly_random' ) ) {
		$result = " ORDER BY RAND()";
		return $result;
	}

	// Album specified?
	if ( wppa_is_int( $id ) && $id > '0' ) {
		$order = wppa_get_album_item( $id, 'p_order_by' );
	}

	// No album specified
	else {
		$order = '0';
	}

	// No order yet? Use default
    if ( ! $order ) {
		$order = wppa_opt( 'list_photos_by' );
	}

    switch ( $order )
    {
	case '':
	case '0':
		$result = '';
		break;
    case '1':
        $result = 'ORDER BY p_order';
        break;
	case '-1':
		$result = 'ORDER BY p_order DESC';
		break;
    case '2':
        $result = 'ORDER BY name';
        break;
    case '-2':
        $result = 'ORDER BY name DESC';
        break;
    case '3':
		if ( $no_random ) $result = 'ORDER BY name';
        else $result = 'ORDER BY RAND( '.wppa_get_randseed().' )';
        break;
    case '-3':
		if ( $no_random ) $result = 'ORDER BY name DESC';
        else $result = 'ORDER BY RAND( '.wppa_get_randseed().' ) DESC';
        break;
	case '4':
		$result = 'ORDER BY mean_rating';
		break;
	case '-4':
		$result = 'ORDER BY mean_rating DESC';
		break;
	case '5':
		$result = 'ORDER BY timestamp';
		break;
	case '-5':
		$result = 'ORDER BY timestamp DESC';
		break;
	case '6':
		$result = 'ORDER BY rating_count';
		break;
	case '-6':
		$result = 'ORDER BY rating_count DESC';
		break;
	case '7':
		$result = 'ORDER BY exifdtm';
		break;
	case '-7':
		$result = 'ORDER BY exifdtm DESC';
		break;

    default:
 		$result = '';
    }

    return $result;
}

function wppa_is_photo_order_desc( $id = '0' ) {

	// Album specified?
	if ( wppa_is_int( $id ) && $id > '0' ) {
		$order = wppa_get_album_item( $id, 'p_order_by' );
	}

	// No album specified
	else {
		$order = '0';
	}

	// No order yet? Use default
    if ( ! $order ) {
		$order = wppa_opt( 'list_photos_by' );
	}

	return ( $order < '0' ) ? "DESC" : "";
}

// Returns the columname for ORDER BY clause, DESC added where appliccable
function wppa_get_poc( $id = '0', $no_random = false ) {
global $wppa;

	// Init
	$order = '0';

	// Random overrule?
	if ( wppa( 'is_random' ) ) {
		$result = 'random';
		return $result;
	}

	// Album specified?
	if ( wppa_is_int( $id ) && $id > '0' ) {
		$order = wppa_get_album_item( $id, 'p_order_by' );
	}

	// No album specified
	else {
		$order = wppa_opt( 'list_photos_by' );
	}

	// If No random and is random so far, use default by id
	if ( $no_random && $order == '3' ) {
		$order = '2';
	}
	if ( $no_random && $order == '-3' ) {
		$order = '-2';
	}

    switch ( $order ) {

		case '1': $result = 'p_order'; break;
		case '-1': $result = 'p_order DESC'; break;
		case '2': $result = 'name'; break;
		case '-2': $result = 'name DESC'; break;
		case '3': $result = 'RAND(' . wppa_get_randseed() . ')'; break;
		case '-3': $result = 'RAND(' . wppa_get_randseed() . ')'; break;
		case '4': $result = 'mean_rating'; break;
		case '-4': $result = 'mean_rating DESC'; break;
		case '5': $result = 'timestamp'; break;
		case '-5': $result = 'timestamp DESC'; break;
		case '6': $result = 'rating_count'; break;
		case '-6': $result = 'rating_count DESC'; break;
		case '7': $result = 'exifdtm'; break;
		case '-7': $result = 'exifdtm DESC'; break;
		default: $result = 'id';
    }

    return $result;
}

// See if an album is another albums ancestor
function wppa_is_ancestor( $anc, $xchild ) {

	$child = $xchild;
	if ( is_numeric( $anc ) && is_numeric( $child ) ) {
		$parent = wppa_get_parentalbumid( $child );
		while ( $parent > '0' ) {
			if ( $anc == $parent ) return true;
			$child = $parent;
			$parent = wppa_get_parentalbumid( $child );
		}
	}
	return false;
}



function wppa_get_album_id( $name = '', $parent = false ) {
global $wpdb;

	if ( $name == '' ) return '';
    $name = stripslashes( $name );

    $albs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums WHERE name = %s", $name ), ARRAY_A );

    if ( empty( $albs ) ) {
		return '';
	}
	else {
		if ( $parent === false ) {
			return $albs['0']['id'];
		}
		else {
			foreach ( $albs as $alb ) {
				if ( $alb['a_parent'] == $parent ) {
					return $alb['id'];
				}
			}
		}
	}
}

// Check if an image is more landscape than the width/height ratio set in Slideshow -> I -> Item 1 and 2
function wppa_is_wider( $x, $y, $refx = '', $refy = '' ) {

	if ( $refx == '' ) {
		$ratioref = wppa_opt( 'fullsize' ) / wppa_opt( 'maxheight' );
	}
	else {
		$ratioref = $refx/$refy;
	}
	$ratio = $x / $y;
	return ( $ratio > $ratioref );
}

// qtrans hook to see if qtrans is installed
function wppa_qtrans_enabled() {
	return ( function_exists( 'qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage' ) );
}


function wppa_get_time_since( $oldtime ) {

	$newtime = time();
	$diff = $newtime - $oldtime;
	if ( $diff < 60 ) {
		return sprintf( _n( '%d second', '%d seconds', $diff, 'wp-photo-album-plus' ), $diff );
	}
	$diff = floor( $diff / 60 );
	if ( $diff < 60 ) {
		return sprintf( _n( '%d minute', '%d minutes', $diff, 'wp-photo-album-plus' ), $diff );
	}
	$diff = floor( $diff / 60 );
	if ( $diff < 24 ) {
		return sprintf( _n( '%d hour', '%d hours', $diff, 'wp-photo-album-plus' ), $diff );
	}
	$diff = floor( $diff / 24 );
	if ( $diff < 7 ) {
		return sprintf( _n( '%d day', '%d days', $diff, 'wp-photo-album-plus' ), $diff );
	}
	elseif ( $diff < 31 ) {
		$t = floor( $diff / 7 );
		return sprintf( _n( '%d week', '%d weeks', $t, 'wp-photo-album-plus' ), $t );
	}
	$diff = floor( $diff / 30.4375 );
	if ( $diff < 12 ) {
		return sprintf( _n( '%d month', '%d months', $diff, 'wp-photo-album-plus' ), $diff );
	}
	$diff = floor( $diff / 12 );
	return sprintf( _n( '%d year', '%d years', $diff, 'wp-photo-album-plus' ), $diff );

}

// See if an album or any album is accessible for the current user
function wppa_have_access( $alb = '0' ) {
global $wpdb;
global $current_user;

//	if ( !$alb ) $alb = 'any'; //return false;

	// See if there is any album accessible
	if ( ! $alb ) { // == 'any' ) {

		// Administrator has always access OR If all albums are public
		if ( wppa_user_is_admin() ) {
			$albs = $wpdb->get_results( "SELECT id FROM $wpdb->wppa_albums" );
			if ( $albs ) return true;
			else return false;	// No albums in system
		}

		// Any --- public --- albums?
		$albs = $wpdb->get_results( "SELECT id FROM $wpdb->wppa_albums WHERE owner = '--- public ---'" );

		if ( $albs ) return true;

		// Any logged out created albums? ( owner = ip )
		$albs = $wpdb->get_results( "SELECT owner FROM $wpdb->wppa_albums", ARRAY_A );
		if ( $albs ) foreach ( $albs as $a ) {
			if ( wppa_is_int( str_replace( '.', '', $a['owner'] ) ) ) return true;
		}

		// Any albums owned by this user?
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$user = $current_user->user_login;
			$any_albs = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_albums WHERE owner = %s", $user ) );

			if ( $any_albs ) return true;
			else return false;	// No albums for user accessible
		}
	}

	// See for given album data array or album number
	else {

		// Administrator has always access
		if ( wppa_user_is_admin() ) return true;	// Do NOT change this into 'wppa_admin', it will enable access to all albums at backend while owners only

		// Limited to separate for non admin/super?
		if ( wppa_switch( 'admin_separate' ) && ! wppa_user_is_admin() ) {

			if ( is_array( $alb ) ) {
				$id = $alb['id'];
			}
			else {
				$id = $alb;
			}
			if ( ! wppa_is_separate( $id ) ) {
				return false;
			}
		}

		// Find the owner
		$owner = '';
		if ( is_array( $alb ) ) {
			$owner = $alb['owner'];
		}
		elseif ( is_numeric( $alb ) ) {
			$owner = $wpdb->get_var( $wpdb->prepare( "SELECT owner FROM $wpdb->wppa_albums WHERE id = %s", $alb ) );
		}

		// -- public --- ?
		if ( $owner == '--- public ---' ) return true;
		if ( wppa_is_int( str_replace( '.', '', $owner ) ) ) return true;	// Owner is an ip

		// Find the user
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			if ( $current_user->user_login == $owner ) return true;
		}
	}
	return false;
}

// See if this image is the default cover image
function wppa_check_coverimage( $id ) {
	if ( wppa_opt( 'default_coverimage_name' ) ) { 	// Feature enabled
		$name = wppa_strip_ext( wppa_get_photo_item( $id, 'filename' ) );
		$dflt = wppa_strip_ext( wppa_opt( 'default_coverimage_name' ) );
		if ( ! strcasecmp( $name, $dflt ) ) {	// Match
			wppa_update_album( wppa_get_photo_item( $id, 'album' ), ['main_photo' => $id] );
		}
	}
}

// Get the max size, rounded up to a multiple of 25 px, of all the possible small images
// in order to create the thumbnail file big enough but not too big.
function wppa_get_minisize() {

	// Init
	$result = '100';

	// Thumbnail used / sizes found for...
	$things = array( 	'thumbsize',
						'thumbsize_alt',
						'topten_size',
						'comten_size',
						'thumbnail_widget_size',
						'lasten_size',
						'album_widget_size',
						'featen_size',
						'popupsize',
						'film_thumbsize',
						 );

	// Find the max
	foreach ( $things as $thing ) {
		$tmp = wppa_opt( $thing );
		if ( is_numeric( $tmp ) && $tmp > $result ) {
			$result = $tmp;
		}
	}

	// Cover image size
	if ( wppa_switch( 'coverphoto_responsive' ) ) {
		$tmp = wppa_opt( 'smallsize_percentage' ) * wppa_opt( 'initial_colwidth' ) / 100;
	}
	else {
		$tmp = wppa_opt( 'smallsize' );
	}
	if ( is_numeric( $tmp ) && $tmp > $result ) {
		$result = $tmp;
	}

	// Optionally correct for 'size=height' for album cover images
	$temp = wppa_opt( 'smallsize' );
	if ( wppa_switch( 'coversize_is_height' ) ) {
		$temp = round( $temp * 4 / 3 );		// assume aspectratio 4:3
	}
	if ( is_numeric( $temp ) && $temp > $result ) $result = $temp;

	// Round up to x * 25, so not a small change results in remake
	$result = ceil( $result / 25 ) * 25;

	// Done
	return $result;
}


function wppa_test_for_search( $at_begin_session = false ) {
global $wppa;
global $wppa_session;

	// Assume not
	$str = '';
	if ( ! is_array( $wppa ) ) {
		return $str;
	}

	if ( wppa_get( 'searchstring' ) ) {	// wppa+ search
		$str = wppa_get( 'searchstring' );
	}
	elseif ( wppa_get( 's' ) ) {				// wp search
		$str = wppa_get( 's' );
	}

	// Selection boxes present and with a value?
	$t = '';
	for ( $i = 0; $i < 3; $i++ ) {
		if ( wppa_get( 'searchselbox-' . $i, '', 'text' ) ) {
			$t .= ' ' . wppa_get( 'searchselbox-' . $i, '', 'text' );
		}
	}
	if ( $t ) {
		$str = $t . ' ' . $str;
		$str = trim( $str );
	}

	// Sanitize
	$ignore = array( '"', "'", '\\', '>', '<', ':', ';', '?', '=', '_', '[', ']', '(', ')', '{', '}' );
	$str = wppa_decode_uri_component( $str );
	$str = str_replace( $ignore, ' ', $str );
	$str = strip_tags( $str );
	$str = stripslashes( $str );
	$str = trim( $str );
	$inter = chr( 226 ).chr( 136 ).chr( 169 );
	$union = chr( 226 ).chr( 136 ).chr( 170 );
	$str = str_replace ( $inter, ' ', $str );
	$str = str_replace ( $union, ',', $str );
	while ( strpos ( $str, '  ' ) !== false ) $str = str_replace ( '  ', ' ', $str );	// reduce spaces
	while ( strpos ( $str, ',,' ) !== false ) $str = str_replace ( ',,', ',', $str );	// reduce commas
	while ( strpos ( $str, ', ' ) !== false ) $str = str_replace ( ', ', ',', $str );	// trim commas
	while ( strpos ( $str, ' ,' ) !== false ) $str = str_replace ( ' ,', ',', $str );	// trim commas

	// Did we do wppa_initialize_runtime() ?
	if ( is_array( $wppa ) && ! $at_begin_session ) {
		$wppa['searchstring'] = $str;
		if ( $wppa['searchstring'] && $wppa['mocc'] == wppa_opt( 'search_oc' ) && ! wppa_in_widget() ) $wppa['src'] = true;
		else $wppa['src'] = false;
		if ( wppa_get( 's' ) ) {
			$wppa['src'] = true;
		}
		$result = $str;
	}
	else {
		$result = $str;
	}

	// If it looks to be a wp search, allow this only when either is_landing, is ajax or is combined search
	if ( $wppa['src'] && wppa_get( 's' ) ) {
		if ( ! $wppa['is_landing'] &&
			 ! $wppa['ajax'] &&
			 ! $wppa['is_combinedsearch']
		) {
			$wppa['src'] = false;
		}
	}

	$wppa_session['use_searchstring'] = $str;
	$wppa_session['display_searchstring'] = str_replace ( ',', ' &#8746 ', str_replace ( ' ', ' &#8745 ', $wppa_session['use_searchstring'] ) );
//	wppa_save_session();


	if ( $wppa['src'] ) {
		switch ( wppa_opt( 'search_display_type' ) ) {
			case 'slide':
				$wppa['is_slide'] = '1';
				break;
			case 'slideonly':
				$wppa['is_slide'] = '1';
				$wppa['is_slideonly'] = '1';
				break;
			case 'albums':
				$wppa['albums_only'] = true;
				break;
			default:
				break;
		}
	}

	if ( $wppa['src'] ) {
		if ( wppa_get( 'catbox' ) ) {
			$wppa['catbox'] = wppa_get( 'catbox' );
		}
		if ( wppa_get( 'catbox' ) ) {
			$wppa['catbox'] = wppa_get( 'catbox' );
		}
		if ( isset ( $wppa['catbox'] ) ) {
			$wppa['catbox'] = wppa_sanitize_cats( $wppa['catbox'] );
		}
	}

	return $result;
}

// Removes the content of $dir, ignore errors
function wppa_tree_empty( $dir ) {
	$files = wppa_glob( $dir.'/*' );
	if ( is_array( $files ) ) foreach ( $files as $file ) {
		$name = basename( $file );
		if ( $name != '.' && $name != '..' ) {
			if ( wppa_is_dir( $file ) ) {
				wppa_tree_empty( $file );
			}
			else {
				@ unlink( $file );
			}
		}
	}
}

// Produce an aleretbox and kill the content afterwards
function wppa_alert( $msg, $reload = false ) {
global $wppa;

	// No message? quit
	if ( ! $msg ) {
		return;
	}

	// Sanitize message
	$msg = wppa_echo( $msg, false, false, true );

	// Reload home?
	if ( $reload == 'home' ) {
		wppa_echo( '
		<div class="wppa-kamikaze">
			<img
				src="dummy"
				onerror="alert( \'' . $msg . '\' );document.location.href=\'' . home_url() . '\';" >
		</div>' );
	}

	// Just reload after?
	elseif ( $reload ) {
		wppa_echo( '
		<div class="wppa-kamikaze">
			<img
				src="dummy"
				onerror="alert( \'' . $msg . '\' );document.location.reload( true );" >
		</div>' );
	}

	// Just alert (once)
	else {
		wppa_echo( '
		<div class="wppa-kamikaze">
			<img
				src="dummy"
				style="display:none"
				onerror="alert( \'' . $msg . '\' );jQuery( \'.wppa-kamikaze\' ).html( \'\' )" >
		</div>' );
	}
}

// Return the allowed number to upload in an album. -1 = unlimited
function wppa_allow_uploads( $alb = '0' ) {
global $wpdb;
static $result_cache;

	if ( isset( $result_cache[$alb] ) ) {
		return $result_cache[$alb];
	}

	if ( ! $alb ) return '-1';//'0';

	$album = wppa_cache_album( $alb );
	if ( ! $album ) return '-1';

	$limits = $album['upload_limit'];

	$temp = explode( '/', $limits );
	$limit_max  = isset( $temp[0] ) ? $temp[0] : '0';
	$limit_time = isset( $temp[1] ) ? $temp[1] : '0';

	if ( ! $limit_max ) return '-1';		// Unlimited max

	if ( ! $limit_time ) {					// For ever
		$curcount = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE album = %s", $alb ) );
	}
	else {									// Time criterium in place
		$timnow = time();
		$timthen = $timnow - $limit_time;
		$curcount = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE album = %s AND timestamp > %s", $alb, $timthen ) );
	}

	if ( $curcount >= $limit_max ) $result = '0';	// No more allowed
	else $result = $limit_max - $curcount;

	$result_cache[$alb] = $result;

	return $result;
}

// Return the allowed number of uploads for a certain user. -1 = unlimited
// First: if album <> 0 and a (grand)parent has a tree limit set, examine the tree
// A set tree limit has priority over other limitations
// Then: if album <> 0 and 'role_limit_per_album' is set, look at the album, not global
function wppa_allow_user_uploads( $album = false ) {
global $wpdb;

	if ( ! is_user_logged_in() ) {
		return '0';
	}

	// PHASE 1 // TEST FOR TREE LIMIT
	// Find possible (grand)parent treelimit
	if ( $album ) {
		$parent = $album; 	// Fake initial parent to album mitsself (for the while loop)
		$last_parent = $album;
		$tree_limit = '0'; 	// Init to 0: no limit
		while ( $parent > '0' && $tree_limit == '0' ) {
			$tree_limit = wppa_get_album_item( $parent, 'upload_limit_tree' );
			$last_parent = $parent;
			$parent = wppa_get_album_item( $parent, 'a_parent' );
		}
		if ( $tree_limit ) {
			$me = wppa_get_user();
			$tree_albums = wppa_alb_to_enum_children( $last_parent );
			$alb_list = str_replace( '.', ',', $tree_albums );
			$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*)
													  FROM $wpdb->wppa_photos
													  WHERE album IN ($alb_list)
													  AND owner = %s", $me ) );
			return max( '0', $tree_limit - $count );
		}
	}

	// PHASE 2 // TEST FOR REGULAR LIMITS
	// Get the limits
	$limits 	= wppa_get_user_upload_limits();
	$temp 		= explode( '/', $limits );
	$limit_max  = isset( $temp[0] ) ? $temp[0] : '0';
	$limit_time = isset( $temp[1] ) ? $temp[1] : '0';

	// Unlimited max
	if ( ! $limit_max ) return '-1';

	// If the userlimits are per album and no album given, return the limit_max if not zero, else -1
	if ( wppa_switch( 'role_limit_per_album' ) && ! $album ) {
		if ( $limit_max > 0 ) {
			return $limit_max;
		}
		else {
			return '-1';
		}
	}

	// Make the album clause
	if ( wppa_switch( 'role_limit_per_album' ) && $album ) {
		$album_clause = sprintf( " AND album = %d", $album );
	}
	else {
		$album_clause = " AND album > 0";
	}

	// Get the user
	$user = wppa_get_user( 'login' );

	// Get the currently uploaded photos
	if ( ! $limit_time ) {					// For ever
		$curcount = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE owner = %s" . $album_clause, $user ) );
	}
	else {									// Time criterium in place
		$timnow = time();
		$timthen = $timnow - $limit_time;
		$curcount = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE owner = %s AND timestamp > %s" . $album_clause, $user, $timthen ) );
	}

	// Compute the allowed number of photos
	return max( '0', $limit_max - $curcount );
}
function wppa_get_user_upload_limits() {
global $wp_roles;

	$limits = '0/0';
	if ( is_user_logged_in() ) {

		// Unlimited if you have wppa_upload capabilities
		if ( current_user_can( 'wppa_upload' ) ) {
			$limits = '0/0';
		}
		else {
			$roles = $wp_roles->roles;
			unset ( $roles['administrator'] );
			foreach ( array_keys( $roles ) as $role ) if ( $limits == '0/0' ) {
				if ( current_user_can( $role ) ) {
					$limits = wppa_get_option( 'wppa_'.$role.'_upload_limit_count', '0' ).'/'.wppa_get_option( 'wppa_'.$role.'_upload_limit_time', '0' );
				}
			}
		}
	}
	return $limits;
}


function wppa_alfa_id( $id = '0' ) {
	return str_replace( array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '0' ), array( 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j' ), $id );
}

// Checks if there is enough memory for an image to be resized to a given size
// @1: string - path to target file
// @2: int - size in pixels of the largest edge of the target image
// Returns bool
function wppa_can_resize( $file, $size, $log_error = true ) {
//ini_set('memory_limit', '32M');

	// Do we need memory check?
	if ( ! function_exists( 'memory_get_usage' ) ) return true;
	if ( ! wppa_switch( 'memcheck_copy' ) ) return true;

	$bytes_per_pixel = 4.6;

	// If file does not exists, log error and return true
	if ( ! file_exists( $file ) ) {
		wppa_log( 'err', 'wppa_can_resize() called with non existing file: ' . $file );
		return true;
	}

	// get memory limit
	$memory_limit = 0;
	$memory_limini = wppa_convert_bytes( ini_get( 'memory_limit' ) );
	$memory_limcfg = wppa_convert_bytes( get_cfg_var( 'memory_limit' ) );

	// find the smallest not being <= zero
	if ( $memory_limini > 0 && $memory_limcfg > 0 ) {
		$memory_limit = min( $memory_limini, $memory_limcfg );
	}
	elseif ( $memory_limini > 0 ) {
		$memory_limit = $memory_limini;
	}
	elseif ( $memory_limcfg > 0 ) {
		$memory_limit = $memory_limcfg;
	}

	// If no data, assume yes
	if ( ! $memory_limit ) {
		return true;
	}

	// Calculate the free memory
	$free_memory = $memory_limit - memory_get_usage( true );

	// Substract filesize
	$free_memory -= filesize( $file );

	// Compute required memorysize
	$imagesize = getimagesize( $file );
	$source_pixels = $imagesize[0] * $imagesize[1];
	if ( $size ) {
		if ( $imagesize[0] > $imagesize[1] ) {
			$target_pixels = $size * $size * $imagesize[1] / $imagesize[0];
		}
		else {
			$target_pixels = $size * $size * $imagesize[0] / $imagesize[1];
		}
	}
	else {
		$target_pixels = 0;
	}
	$required_memorysize = ( $source_pixels + $target_pixels ) * $bytes_per_pixel;

	// Does it fit?
	if ( $required_memorysize <= $free_memory ) {
		return true;
	}
	elseif ( $log_error ) {
		if ( $size ) {
			wppa_log( 'War', 'Too little memory to resize ' . $file . ' (' . $imagesize[0] . 'x' . $imagesize[1] . ' px) to ' . $size );
		}
		else {
			wppa_log( 'War', 'Too little memory to manipulate ' . $file . ' (' . $imagesize[0] . 'x' . $imagesize[1] . ' px)' );
		}
		return false;
	}
	return false;
}

// Thanx to the maker of nextgen, but greatly improved
// Usage: wppa_check_memory_limit() return string telling the max upload size
// @1: if false, return array ( 'maxx', 'maxy', 'maxp' )
// @2: width to test an image,
// @3: height to test an image.
// If both present: return true if fit in memory, false if not.
//
//
function wppa_check_memory_limit( $verbose = true, $x = '0', $y = '0' ) {

// ini_set( 'memory_limit', '18M' );	// testing
	if ( ! function_exists( 'memory_get_usage' ) ) return '';
	if ( ! wppa_switch( 'memcheck' ) ) return '';

	// get memory limit
	$memory_limit = 0;
	$memory_limini = wppa_convert_bytes( ini_get( 'memory_limit' ) );
	$memory_limcfg = wppa_convert_bytes( get_cfg_var( 'memory_limit' ) );

	// find the smallest not being zero
	if ( $memory_limini && $memory_limcfg ) $memory_limit = min( $memory_limini, $memory_limcfg );
	elseif ( $memory_limini ) $memory_limit = $memory_limini;
	else $memory_limit = $memory_limcfg;

	// No data
	if ( ! $memory_limit ) return '';

	// Calculate the free memory
	$free_memory = $memory_limit - memory_get_usage( true );

	// Calculate number of pixels largest target resized image
	switch ( wppa_opt( 'resize_to' ) ) {

		case '-1': // Not resize, look at minisize only
			$resizedpixels = wppa_get_minisize() * wppa_get_minisize() * 3 / 4;
			break;
		case '0': // Look at max slideshow size
			$resizedpixels = wppa_opt( 'fullsize' ) * wppa_opt( 'maxheight' );
			break;
		default: // XXXXxYYYY
			$to = explode( 'x', wppa_opt( 'resize_to' ) );
			$resizedpixels = $to['0'] * $to['1'];
	}

	// Number of bytes per pixel ( found by trial and error )
	//	$factor = '5.60';	//  5.60 for 17M: 386 x 289 ( 0.1 MP ) thumb only
	//	$factor = '5.10';	//  5.10 for 104M: 4900 x 3675 ( 17.2 MP ) thumb only
	$memlimmb = $memory_limit / ( 1024 * 1024 );
	$factor = 4.6; // '6.00' - '0.58' * ( $memlimmb / 104 );	// 6.00 .. 0.58

	// Calculate max size
	$maxpixels = ( $free_memory / $factor ) - $resizedpixels;

	// Safety margin
	// $maxpixels = round( $maxpixels * 0.95 );

	// If obviously faulty: quit silently
	if ( $maxpixels < 0 ) return '';

	// What are we asked for?
	if ( $x && $y ) { 	// Request for check an image
		if ( $x * $y <= $maxpixels ) $result = true;
		else $result = false;
	}
	else {	// Request for tel me what is the limit
		$maxx = sqrt( $maxpixels / 12 ) * 4;
		$maxy = sqrt( $maxpixels / 12 ) * 3;
		$maxxhd = sqrt( $maxpixels / 144 ) * 16;
		$maxyhd = sqrt( $maxpixels / 144 ) * 9;
		if ( $verbose ) {		// Make it a string
			$result = '<br />'.sprintf(  __( 'Based on your server memory limit you should not upload images larger then <b>%2.1f</b> Mega pixels', 'wp-photo-album-plus' ), $maxpixels / ( 1024 * 1024 ) );
			$result .= '<br />'.sprintf( __( 'E.g. not bigger than approx %s x %s pixels (4:3) or %s x %s (16:9)', 'wp-photo-album-plus' ),
										'<b>' . ( round( $maxx / 25 ) * 25 ) . '</b>',
										'<b>' . ( round( $maxy / 25 ) * 25 ) . '</b>',
										'<b>' . ( round( $maxxhd / 25 ) * 25 ) . '</b>',
										'<b>' . ( round( $maxyhd / 25 ) * 25 ) . '</b>'
										);
		}
		else {					// Or an array
			$result['maxx'] = $maxx;
			$result['maxy'] = $maxy;
			$result['maxp'] = $maxpixels;
		}
	}
	return $result;
}

/**
 * Convert a shorthand byte value from a PHP configuration directive to an integer value. Negative values return 0.
 * @param    string   $value
 * @return   int
 */
function wppa_convert_bytes( $value ) {
    if ( is_numeric( $value ) ) {
        return max( '0', $value );
    } else {
        $value_length = strlen( $value );
        $qty = substr( $value, 0, $value_length - 1 );
        $unit = strtolower( substr( $value, $value_length - 1 ) );
        switch ( $unit ) {
            case 'k':
                $qty *= 1024;
                break;
            case 'm':
                $qty *= 1048576;
                break;
            case 'g':
                $qty *= 1073741824;
                break;
        }
        return max( '0', $qty );
    }
}

function wppa_format_geo( $lat, $lon ) {

	if ( ! $lat && ! $lon ) return '';	// Both zero: clear

	if ( ! $lat ) $lat = '0.0';
	if ( ! $lon ) $lon = '0.0';

	$geo['latitude_format'] = $lat >= '0.0' ? 'N ' : 'S ';
	$d = floor( $lat );
	$m = floor( ( $lat - $d ) * 60 );
	$s = round( ( ( ( $lat - $d ) * 60 - $m ) * 60 ), 4 );
	$geo['latitude_format'] .= $d.'&deg;'.$m.'&#x27;'.$s.'&#x22;';

	$geo['longitude_format'] = $lon >= '0.0' ? 'E ' : 'W ';
	$d = floor( $lon );
	$m = floor( ( $lon - $d ) * 60 );
	$s = round( ( ( ( $lon - $d ) * 60 - $m ) * 60 ), 4 );
	$geo['longitude_format'] .= $d.'&deg;'.$m.'&#x27;'.$s.'&#x22;';

	$geo['latitude'] = $lat;
	$geo['longitude'] = $lon;

	$result = implode( '/', $geo );
	return $result;
}


function wppa_album_select_a( $args ) {
global $wpdb;

	$args = wp_parse_args( $args, array( 	'exclude' 			=> '',
											'selected' 			=> '',
											'disabled' 			=> '',
											'addpleaseselect' 	=> false,
											'addnone' 			=> false,
											'addall' 			=> false,
											'addgeneric'		=> false,
											'addblank' 			=> false,
											'addselected'		=> false,
											'addseparate' 		=> false,
											'addselbox'			=> false,
											'addowner' 			=> false,
											'disableancestors' 	=> false,
											'checkaccess' 		=> false,
											'checkowner' 		=> false,
											'checkupload' 		=> false,
											'addmultiple' 		=> false,
											'addnumbers' 		=> false,
											'path' 				=> false,
											'root' 				=> false,
											'content'			=> false,
											'sort'				=> false,
											'checkarray' 		=> false,
											'array' 			=> array(),
											'optionclass' 		=> '',
											'tagopen' 			=> '',
											'tagname' 			=> '',
											'tagid' 			=> '',
											'tagonchange' 		=> '',
											'multiple' 			=> false,
											'tagstyle' 			=> '',
											'checkcreate' 		=> false,
											'crypt' 			=> false,
											 ) );

	// See if new format is used
	if ( $args['tagopen'] ) {
		$is_newformat = true;
	}
	else {
		$is_newformat = false;
	}

	// Provide default selection if no selected given
	if ( $args['selected'] === '' ) {
        $args['selected'] = wppa_get_last_album();
    }

	// See if selection is valid
	if ( ( $args['selected'] == $args['exclude'] ) ||
		 ( $args['checkupload'] && ( ! wppa_allow_uploads( $args['selected'] ) || ! wppa_allow_user_uploads( $args['selected'] ) ) ) ||
		 ( $args['disableancestors'] && wppa_is_ancestor( $args['exclude'], $args['selected'] ) )
	   ) {
		$args['selected'] = '0';
	}

	// Calculate roughly the expected number of albums to be in the selection and decide if the number is 'many'
	$is_many = false;
	if ( $args['checkarray'] && ! empty( $args['array'] ) ) {
		$c = count( $args['array'] );
		if ( wppa_opt( 'photo_admin_max_albums' ) && $c > wppa_opt( 'photo_admin_max_albums' ) ) {
			$is_many = true;
		}
	}
	elseif ( wppa_has_many_albums() ) {
		$is_many = true;
	}


	// Process the many case
	if ( $is_many && ! $args['crypt'] ) {

		// Many newformat
		if ( $is_newformat ) {

			$result =
			'<input' .
				' name="' . $args['tagname'] . '"' .
				( $args['tagid'] ? ' id="'  . $args['tagid'] . '"' : '' ) .
				( $args['multiple'] ? '' : ' type="number"' ) .
				' value="' . $args['selected'] . '"' .
				' onchange="' . $args['tagonchange'] . '"' .
				' style="' . $args['tagstyle'] . '"' .
				' title="' .
					esc_attr( __( 'Enter album number', 'wp-photo-album-plus' ) );

					if ( $args['addnone'] ) 	$result .= esc_attr( "\n" . __( '0 for --- none ---', 'wp-photo-album-plus' ) );
					if ( $args['addall'] ) 		$result .= esc_attr( "\n" . __( '0 for --- all ---', 'wp-photo-album-plus' ) );
					if ( $args['addall'] ) 		$result .= esc_attr( "\n" . __( '-2 for --- generic ---', 'wp-photo-album-plus' ) );
					if ( $args['addowner'] ) 	$result .= esc_attr( "\n" . __( '-3 for --- owner/public ---', 'wp-photo-album-plus' ) );
					if ( $args['addmultiple'] ) $result .= esc_attr( "\n" . __( '-99 for --- multiple see below ---', 'wp-photo-album-plus' ) );
					if ( $args['addselbox'] ) 	$result .= esc_attr( "\n" . __( '0 for --- a selection box ---', 'wp-photo-album-plus' ) );
					if ( $args['addseparate'] ) $result .= esc_attr( "\n" . __( '-1 for --- separate ---', 'wp-photo-album-plus' ) );

					$result .=
					'"' .
			' />' ;

			return $result;
		}

		// Many old format
		else {
			$result = '';

			$selected = $args['selected'] == '0' ? ' selected' : '';
			if ( $args['addpleaseselect'] ) $result .=
				'<option value="0" disabled '.$selected.'>' .
					__( '- select an album -', 'wp-photo-album-plus' ) .
				'</option>';

			$selected = $args['selected'] == '0' ? ' selected' : '';
			if ( $args['addnone'] ) $result .=
				'<option value="0"'.$selected.'>' .
					__( '--- none ---', 'wp-photo-album-plus' ) .
				'</option>';

			$selected = $args['selected'] == '0' ? ' selected' : '';
			if ( $args['addall'] ) $result .=
				'<option value="0"'.$selected.'>' .
					__( '--- all ---', 'wp-photo-album-plus' ) .
				'</option>';

			$selected = $args['selected'] == '-2' ? ' selected' : '';
			if ( $args['addall'] ) $result .=
				'<option value="-2"'.$selected.'>' .
					__( '--- generic ---', 'wp-photo-album-plus' ) .
				'</option>';

			$selected = $args['selected'] == '-3' ? ' selected' : '';
			if ( $args['addowner'] ) $result .=
				'<option value="-3"'.$selected.'>' .
					__( '--- owner/public ---', 'wp-photo-album-plus' ) .
				'</option>';

			$selected = $args['selected'] == '0' ? ' selected' : '';
			if ( $args['addblank'] ) $result .=
				'<option value="0"'.$selected.'>' .
				'</option>';

			$selected = $args['selected'] == '-99' ? ' selected' : '';
			if ( $args['addmultiple'] ) $result .=
				'<option value="-99"'.$selected.'>' .
					__( '--- multiple see below ---', 'wp-photo-album-plus' ) .
				'</option>';

			$selected = $args['selected'] == '0' ? ' selected' : '';
			if ( $args['addselbox'] ) $result .=
				'<option value="0"'.$selected.'>' .
					__( '--- a selection box ---', 'wp-photo-album-plus' ) .
				'</option>';
			$selected = $args['selected'] == '-1' ? ' selected' : '';
			if ( $args['addseparate'] ) $result .=
				'<option value="-1"' . $selected . '>' .
					__( '--- separate ---', 'wp-photo-album-plus' ) .
				'</option>';

			return $result;
		}
	}

	// Continue processing Not many albums or encryption requested
	else {

		// Get roughly the albums that might be in the selection
		if ( $args['checkarray'] && ! empty( $args['array'] ) ) {

			// $albums = $args['array'];
			$albums = array();

			$temp = $wpdb->get_results( 	"SELECT id, name, max_children, crypt " .
											"FROM $wpdb->wppa_albums " .
											"WHERE id IN (" . implode( ',', $args['array'] ) . ") " .
											( $args['checkowner'] && ! wppa_user_is_admin() ? "AND owner IN ( '--- public ---', '" . wppa_get_user() . "' ) " : "" ) .
											wppa_get_album_order( $args['root'] ),
											ARRAY_A
										);

			// To keep the preciously created sequence intact when an array is given, copy the data from $temp in the sequence of $args['array']
			foreach( $args['array'] as $id ) {
				foreach( $temp as $item ) {
					if ( $item['id'] == $id ) {
						$albums[] = $item;
					}
				}
			}
		}
		else {
			$albums = $wpdb->get_results( 	"SELECT id, name, max_children, crypt " .
											"FROM $wpdb->wppa_albums " .
											( $args['checkowner'] && ! wppa_user_is_admin() ? "WHERE owner IN ( '--- public ---', '" . wppa_get_user() . "' ) " : "" ) .
											wppa_get_album_order( $args['root'] ),
											ARRAY_A
										);

		}

		if ( $albums ) {

			// Filter for root
			if ( $args['root'] ) {
				$root = $args['root'];
				switch ( $root ) {	// case '0': all, will be skipped as it returns false in 'if ( $args['root'] )'
					case '-2':	// Generic only
					foreach ( array_keys( $albums ) as $albidx ) {
						if ( wppa_is_separate( $albums[$albidx]['id'] ) ) unset ( $albums[$albidx] );
					}
					break;
					case '-1':	// Separate only
					foreach ( array_keys( $albums ) as $albidx ) {
						if ( ! wppa_is_separate( $albums[$albidx]['id'] ) ) unset ( $albums[$albidx] );
					}
					break;
					default:
					foreach ( array_keys( $albums ) as $albidx ) {
						if ( ! wppa_is_ancestor( $root, $albums[$albidx]['id'] ) ) unset ( $albums[$albidx] );
					}
					break;
				}
			}
			// Filter for must have content
			if ( $args['content'] ) {
				foreach ( array_keys( $albums ) as $albidx ) {
					if ( ! wppa_get_visible_photo_count( $albums[$albidx]['id'] ) ) unset ( $albums[$albidx] );
				}
			}
			// Add paths
			if ( $args['path'] ) {
				$albums = wppa_add_paths( $albums );
			}
			// Or just translate
			else foreach ( array_keys( $albums ) as $index ) {
				$albums[$index]['name'] = __( stripslashes( $albums[$index]['name'] ) );
			}
			// Sort
			if ( $args['sort'] ) $albums = wppa_array_sort( $albums, 'name' );
		}

		// Output
		$result = '';

		// New format
		if ( $is_newformat ) {
			$result .= $args['tagopen'];
		}

		$selected = $args['selected'] == '0' ? ' selected' : '';
		if ( $args['addpleaseselect'] ) $result .=
			'<option value="' . ( $args['crypt'] ? wppa_encrypt_album( '0' ) : '0' ) . '" disabled'.$selected.'>' .
				__( '- select an album -', 'wp-photo-album-plus' ) .
			'</option>';

		$selected = $args['selected'] == '0' ? ' selected' : '';
		if ( $args['addnone'] ) $result .=
			'<option value="' . ( $args['crypt'] ? wppa_encrypt_album( '0' ) : '0' ) . '"'.$selected.'>' .
				__( '--- none ---', 'wp-photo-album-plus' ) .
			'</option>';

		$selected = $args['selected'] == '0' ? ' selected' : '';
		if ( $args['addall'] ) $result .=
			'<option value="' . ( $args['crypt'] ? wppa_encrypt_album( '0' ) : '0' ) . '"'.$selected.'>' .
				__( '--- all ---', 'wp-photo-album-plus' ) .
			'</option>';

		$selected = $args['selected'] == '-2' ? ' selected' : '';
		if ( $args['addall'] ) $result .=
			'<option value="' . ( $args['crypt'] ? wppa_encrypt_album( '-2' ) : '-2' ) . '"'.$selected.'>' .
				__( '--- generic ---', 'wp-photo-album-plus' ) .
			'</option>';

		$selected = $args['selected'] == '-3' ? ' selected' : '';
		if ( $args['addowner'] ) $result .=
			'<option value="' . ( $args['crypt'] ? wppa_encrypt_album( '-3' ) : '-3' ) . '"'.$selected.'>' .
				__( '--- owner/public ---', 'wp-photo-album-plus' ) .
			'</option>';

		$selected = $args['selected'] == '0' ? ' selected' : '';
		if ( $args['addblank'] ) $result .=
			'<option value="' . ( $args['crypt'] ? wppa_encrypt_album( '0' ) : '0' ) . '"'.$selected.'>' .
			'</option>';

		$selected = $args['selected'] == '-99' ? ' selected' : '';
		if ( $args['addmultiple'] ) $result .=
			'<option value="' . ( $args['crypt'] ? wppa_encrypt_album( '-99' ) : '-99' ) . '"'.$selected.'>' .
				__( '--- multiple see below ---', 'wp-photo-album-plus' ) .
			'</option>';

		$selected = $args['selected'] == '0' ? ' selected' : '';
		if ( $args['addselbox'] ) $result .=
			'<option value="' . ( $args['crypt'] ? wppa_encrypt_album( '0' ) : '0' ) . '"'.$selected.'>' .
				__( '--- a selection box ---', 'wp-photo-album-plus' ) .
			'</option>';

		// In case multiple
		if ( strpos( $args['selected'], ',' ) !== false ) {
			$selarr = explode( ',', $args['selected'] );
		}
		else {
			$selarr = array( $args['selected'] );
		}

		if ( $albums ) foreach ( $albums as $album ) {
			$lim = '-1'; 	// Assume no limit on album
			if ( wppa_switch( 'role_limit_per_album' ) ) {
				$lim = wppa_allow_user_uploads( $album['id'] );
			}
			if ( ( $args['disabled'] == $album['id'] ) ||
				 ( $args['exclude'] == $album['id'] ) ||
				 ( $args['checkupload'] && ( ! wppa_allow_uploads( $album['id'] ) || ! $lim ) ) ||
				 ( $args['disableancestors'] && wppa_is_ancestor( $args['exclude'], $album['id'] ) )
				 ) $disabled = ' disabled'; else $disabled = '';
			if ( in_array( $album['id'], $selarr ) && ! $disabled ) $selected = ' selected'; else $selected = '';

			$ok = true; // Assume this will be in the list
			if ( $args['checkaccess'] && ! wppa_have_access( $album['id'] ) ) {
				$ok = false;
			}
			if ( $args['checkcreate'] ) {
				$mc = $album['max_children'];
				if ( $mc == '-1' ) $ok = false;
				elseif ( $mc ) {
					$cnt = wppa_get_treecounts_a( $album['id'] );
					if ( $cnt['selfalbums'] >= $mc ) $disabled = ' disabled';
				}
			}
			if ( $selected && $args['addselected'] ) {
				$ok = true;
			}
			if ( $ok ) {
				if ( $args['addnumbers'] ) {
					$number = ' (' . $album['id'] . ')';
				}
				else {
					$number = '';
				}
				if ( $lim > '0' ) {
					$limt = ' (max ' . $lim . ')';
				}
				else {
					$limt = '';
				}
				$result .=
				'<option' .
					' class="' . $args['optionclass']. '"' .
					' value="' . ( $args['crypt'] ? $album['crypt'] : $album['id'] ) . '"' .
					' ' . $selected . $disabled .
					'>' .
					$album['name'] . $number . $limt .
				'</option>';
			}
		}

		$selected = $args['selected'] == '-1' ? ' selected' : '';
		if ( $args['addseparate'] ) $result .=
			'<option value="' . ( $args['crypt'] ? wppa_encrypt_album( '-1' ) : '-1' ) . '"' . $selected . '>' .
				__( '--- separate ---', 'wp-photo-album-plus' ) .
			'</option>';

		// New format
		if ( $is_newformat ) {
			$result .= '</select>';
		}

		return $result;
	}
}

function wppa_delete_obsolete_tempfiles( $force = false ) {

	// To prevent filling up diskspace, divide lifetime by 2 and repeat removing obsolete files until count <= 10
	$filecount 	= 51;
	$lifetime 	= 3600;
	$max 		= $force ? 1 : 50;
	$delcount 	= 0;

	while ( $filecount > $max ) {

		$files = wppa_glob( WPPA_UPLOAD_PATH.'/temp/*' );
		$filecount = 0;

		if ( $files ) {

			$timnow = time();
			$expired = $timnow - $lifetime;

			foreach ( $files as $file ) {

				if ( is_file( $file ) && basename( $file ) != 'index.php' && basename( $file ) != 'wmfdummy.png' ) {
					$modified = filemtime( $file );
					if ( $modified < $expired || $force ) {
						wppa_unlink( $file );
						$delcount++;
					}
					else {
						$filecount++;
					}
				}
			}
		}
		$lifetime /= 2;
	}
	if ( wppa_is_cron() && $delcount ) {
		wppa_log( 'cron', 'Deleted ' . $delcount . ' tempfiles' );
	}
}

function wppa_publish_scheduled() {
global $wpdb;

	$last_check = wppa_get_option( 'wppa_last_schedule_check', '0' );
	if ( $last_check < ( time() - 300 ) ) {	// Longer than 5 mins ago

		// Publish scheduled photos
		$to_publish = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
														   WHERE status = 'scheduled'
														   AND scheduledtm < %s",
														   wppa_get_default_scheduledtm() ), ARRAY_A );
		if ( $to_publish ) foreach( $to_publish as $photo ) {
			wppa_update_photo( $photo['id'], ['scheduledtm' => '', 'status' => 'publish'] );
			wppa_update_album( $photo['album'] );	// For New indicator on album
			wppa_invalidate_treecounts( $photo['album'] );
		}

		// Remove scheduledtm from albums when it is in the past, so new photos do not get this anymore
		$to_update = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums
													  WHERE scheduledtm <> ''
													  AND scheduledtm < %s",
													  wppa_get_default_scheduledtm() ) );
		if ( $to_update ) foreach( $to_update as $id ) {
			wppa_update_album( $id, ['scheduledtm' => ''] );
			wppa_invalidate_treecounts( $id );
		}

		// Delete only when the time modified is more than ten minutes ago, to prevent accidental removal during configuration
		$maxtime = time() - 600;

		// Delete photos scheduled for deletion
		$to_delete = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos
													  WHERE scheduledel <> ''
													  AND scheduledel < %s
													  AND modified < %s",
													  wppa_get_default_scheduledtm(),
													  $maxtime ) );
		if ( $to_delete ) foreach( $to_delete as $id ) {
			wppa_delete_photo( $id );
		}

		// Delete albums scheduled for deletion
		$albums_to_delete = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums
															 WHERE scheduledel <> ''
															 AND scheduledel < %s
															 AND modified < %s",
															 wppa_get_default_scheduledtm(),
															 $maxtime ) );
		if ( $albums_to_delete ) {
			$delalbs = implode( ',', $albums_to_delete );
			$photos_to_delete = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_photos WHERE album IN ( $delalbs )" );
			if ( $photos_to_delete ) foreach( $photos_to_delete as $id ) {
				wppa_delete_photo( $id );
			}
			$wpdb->query( "DELETE FROM $wpdb->wppa_albums WHERE id IN ( $delalbs )" );
			$wpdb->query( "UPDATE $wpdb->wppa_albums SET a_parent = '-1' WHERE a_parent IN ( $delalbs )" );
		}

		// Update timestamp of this action
		wppa_update_option( 'wppa_last_schedule_check', time() );
	}
}

function wppa_add_credit_points( $amount, $reason = '', $id = '', $value = '', $user = '' ) {

	// Anything to do?
	if ( ! $amount ) {
		return;
	}

	// Initialize
	$bret = false;
	if ( $user ) {
		$usr = wppa_get_user_by( 'login', $user );
	}
	else {
		$usr = wp_get_current_user();
	}
	if ( ! $usr ) {
		wppa_log( 'err', 'Could not add points to user '.$user );
		return false;
	}

	// Cube points
	if ( function_exists( 'cp_alterPoints' )  ) {
		cp_alterPoints( $usr->ID, $amount );
		$bret = true;
	}

	// myCred
	if ( function_exists( 'mycred_add' ) ) {
		$entry = $reason . ( $id ? ', '.__('Photo id =', 'wp-photo-album-plus' ).' '.$id : '' ) . ( $value ? ', '.__('Value =', 'wp-photo-album-plus' ).' '.$value : '' );
		$bret = mycred_add( str_replace( ' ', '_', $reason ), $usr->ID, $amount, $entry, '', '', '' );
	}

	return $bret;
}
