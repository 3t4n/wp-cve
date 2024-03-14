<?php
/* wppa-functions.php
* Package: wp-photo-album-plus
*
* Various functions
* Version: 8.6.04.009
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Get the albums by calling the theme module and do some parameter processing
// This is the main entrypoint for the wppa+ invocation, either 'by hand' or through the filter.
// As of version 3.0.0 this routine returns the entire html created by the invocation.
function wppa_albums( $id = '', $type = '', $size = '', $align = '' ) {
global $wppa_lang;
global $wppa_locale;
global $wpdb;
global $wppa_session;
global $wppa;
global $locale;
global $wppa_current_shortcode;
global $wppa_current_shortcode_atts;
global $wppa_current_shortcode_no_delay;
global $albums_used;
global $photos_used;
global $other_deps;

	// Init timer
	wppa_occur_timer( 'init' );

	// Process a user upload request, if any. Do it here: it may affect this occurences display
	wppa_user_upload();

	// Test for scheduled publications
	wppa_publish_scheduled();

	// First calculate the occurance
	if ( wppa( 'ajax' ) ) {

		if ( wppa_get( 'occur' ) ) {
			wppa( 'mocc', wppa_get( 'occur' ) );
		}
		else {
			wppa_bump_mocc();
		}
	}

	// Widgets compile their own mocc
	elseif ( ! wppa( 'in_widget' ) ) {
		wppa_bump_mocc();
	}

	// Set wppa( 'src' ) = true and wppa( 'searchstring' ) if this occurrance processes a search request.
	wppa_test_for_search();

	// Test for caching
	$temp 		= wppa_test_for_caching();
	$caching 	= $temp['caching'];
	$cache_id 	= $temp['cache_id'];
	$cache_file = $temp['cache_file'];
	$cache_data = $temp['cache_data'];

	if ( $cache_data ) {
		wppa_reset_occurrance();

		return $cache_data;
	}

	// There are 3 ways to get here:
	// in order of priority:
	// 1. The given query string applies to this invocation ( occurrance )
	//    This invocation requires the ignorance of the filter results and the interpretation of the querystring.
	// if  occur in qstring    &&   occur             == currrent mocc    && ! autopage and not from gutenberg preview
	global $wppa_gutenberg_preview;
	if ( ( wppa_get( 'occur' ) && wppa_get( 'occur' ) == wppa( 'mocc' ) ) &&
		! wppa( 'is_autopage' ) &&
		! $wppa_gutenberg_preview &&
		wppa_get( 'action', '', 'text' ) != 'getshortcodedrenderedfenodelay' ) {

		// Test validity of album arg
		wppa( 'start_album', wppa_get( 'album' ) );

		// Save photo enumeration
		wppa( 'start_photos', wppa_get( 'photos' ) );

		wppa( 'is_cover', wppa_get( 'cover' ) );

		wppa( 'is_slide', wppa_get( 'slide' ) || ( wppa_get( 'album' ) !== false && ( wppa_get( 'photo' ) || wppa_get( 'photos' ) ) ) );

		if ( ! wppa_is_url_valid() ) {
			if ( wppa_opt( 'crypt_albums_every' ) || wppa_opt( 'crypt_photos_every' ) ) {
				$msg = __( 'Invalid or expired url supplied', 'wp-photo-album-plus' );
			}
			else {
				$msg = __( 'Invalid or incomplete url supplied', 'wp-photo-album-plus' );
			}
			wppa_errorbox( $msg );
			return wppa( 'out' );
		}

		if ( wppa_get( 'slideonly' ) ) {
			wppa( 'is_slide', true );
			wppa( 'is_slideonly', true );
		}
		if ( wppa_get( 'filmonly' ) ) {
			wppa( 'is_slide', true );
			wppa( 'is_filmonly', true );
			wppa( 'is_slideonly', true );
			wppa( 'film_on', true );
		}
		if ( wppa( 'is_slide' ) ) {
			wppa( 'start_photo', wppa_get( 'photo' ) );		// Start a slideshow here
			wppa( 'is_grid', false );
		}
		else {
			wppa( 'single_photo', wppa_get( 'photo' ) ); 	// Photo is the single photoid
			wppa( 'start_photo', wppa_get( 'photo' ) ); // Fix for the potd ussue ?????
		}
		wppa( 'is_single', wppa_get( 'single' ) );			// Is a one image slideshow
		wppa( 'topten_count', wppa_force_numeric_else( wppa_get( 'topten' ), wppa_opt( 'topten_count' ) ) );
		wppa( 'is_topten', wppa( 'topten_count' ) != '0' );
		wppa( 'lasten_count', wppa_force_numeric_else( wppa_get( 'lasten' ), wppa_opt( 'lasten_count' ) ) );
		wppa( 'is_lasten', wppa( 'lasten_count' ) != '0' );
		wppa( 'comten_count', wppa_force_numeric_else( wppa_get( 'comten' ), wppa_opt( 'comten_count' ) ) );
		wppa( 'is_comten', wppa( 'comten_count' ) != '0' );
		wppa( 'featen_count', wppa_force_numeric_else( wppa_get( 'featen' ), wppa_opt( 'featen_count' ) ) );
		wppa( 'is_featen', wppa( 'featen_count' ) != '0' );
		wppa( 'albums_only', wppa_get( 'albums-only' ) );
		wppa( 'photos_only', wppa_get( 'photos-only' ) );
		wppa( 'medals_only', wppa_get( 'medals-only' ) );
		wppa( 'related_count', wppa_force_numeric_else( wppa_get( 'relcount' ), wppa_opt( 'related_count' ) ) );
		wppa( 'is_related', wppa_get( 'rel' ) );
		wppa( 'is_potdhis', wppa_get( 'potdhis' ) );

		if ( wppa( 'is_related' ) == 'tags' ) {
			wppa( 'is_tag', wppa_get_related_data() );
			if ( wppa( 'related_count' ) == '0' ) {
				wppa( 'related_count', wppa_opt( 'related_count' ) );
			}
		}
		else {
			wppa( 'is_tag', trim( strip_tags( wppa_get( 'tag', '', 'text' ) ), ',;' ) );
		}

		if ( wppa( 'is_related' ) == 'desc' ) {
			wppa( 'src', true );
			if ( wppa( 'related_count' ) == '0' ) wppa( 'related_count', wppa_opt( 'related_count' ) );
			wppa( 'searchstring', str_replace( ';', ',', wppa_get_related_data() ) );
			wppa( 'photos_only', true );
		}

		wppa( 'page', wppa_get( 'paged', '1' ) );

		if ( wppa_get( 'superview' ) ) {
			$wppa_session['superview'] = wppa( 'is_slide' ) ? 'slide': 'thumbs';
			$wppa_session['superalbum'] = wppa( 'start_album' );
			wppa( 'photos_only', true );
		}
		wppa( 'is_upldr', wppa_get( 'upldr' ) );

		if ( wppa( 'is_upldr' ) ) wppa( 'photos_only', true );
		wppa( 'is_owner', wppa_get( 'owner' ) );

		if ( wppa( 'is_owner' ) ) {
			$albs = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
														 WHERE owner = %s", wppa( 'is_owner' ) ), ARRAY_A );
			wppa_cache_album( 'add', $albs );
			$id = '';
			if ( $albs ) foreach ( $albs as $alb ) {
				$id .= $alb['id'].'.';
			}
			$id = rtrim( $id, '.' );
			wppa( 'start_album', $id );
		}
		wppa( 'supersearch', strip_tags( wppa_get( 'supersearch' ) ) );
		$wppa_session['supersearch'] = wppa( 'supersearch' );

		if ( wppa( 'supersearch' ) ) {
			$ss_info = explode( ',', wppa( 'supersearch' ) );
			if ( $ss_info['0'] == 'a' ) {
				wppa( 'albums_only', true );
			}
			else {
				wppa( 'photos_only', true );
			}
		}
		wppa( 'calendar', strip_tags( wppa_get( 'calendar' ) ) );

		// New style calendar and ajax: set is_calendar
		if ( substr( wppa_get( 'calendar' ), 0, 4 ) == 'real' ) {
			wppa( 'calendar', wppa_get( 'calendar' ) );
			wppa( 'is_calendar', true );
		}
		wppa( 'caldate', strip_tags( wppa_get( 'caldate' ) ) );
		wppa( 'is_inverse', wppa_get( 'inv' ) );

	}

	// 2. wppa_albums is called directly. Assume any arg. If not, no worry, system defaults are used == generic
	elseif ( $id != '' || $type != '' || $size != '' || $align != '' ) {

		// Interprete function args
		if ( $type == 'cover' ) {
			wppa( 'is_cover', true );
		}
		elseif ( $type == 'slide' ) {
			wppa( 'is_slide', true );
		}
		elseif ( $type == 'slideonly' ) {
			wppa( 'is_slideonly', true );
		}

		if ( $type == 'photo' || $type == 'mphoto' || $type == 'slphoto' || $type == 'xphoto' ) {	// Any type of single photo? id given is photo id
			if ( $id ) wppa( 'single_photo', $id );
		}
		else {																	// Not single photo: id given is album id
			if ( $id ) wppa( 'start_album', $id );
		}
	}

	// 3. The filter supplied the data
	else {

		// If delayed and not in the editor or on any other admin page
		if ( wppa( 'delay' ) && ! is_admin() && ! defined( 'DOING_AJAX' ) ) {

			$delay = wppa( 'delay' );
			$delay_arr = explode( ',', $delay );
// wppa_out(var_export($delay_arr,true));
			// Maybo to different mocc
			$tomocc = wppa( 'mocc' );

			if ( $delay_arr[0] == 'button' || $delay_arr[0] == 'text' ) {
				if ( isset( $delay_arr[2] ) && wppa_is_posint( $delay_arr[2] ) ) {
					$tomocc = $delay_arr[2];
				}
			}

			// If tomocc given on a delay text or button do not place it in a container to avoid mocc collisions
			$no_container = isset( $delay_arr[2] );

			// Make container with ajax render command at dom ready, or button or link
			// with querystring build from runtime parms converted from shortcode args by filter
			if ( ! $no_container ) wppa_container( 'open' );

			// The ajax request
			$sc = str_replace( '#', '%23', $wppa_current_shortcode_no_delay );
			$request = "jQuery(document).ready(function(){wppaDoFetchShortcodeRendered(" . $tomocc . ", '" . $sc . "' , '" . get_the_ID() . "');});";

			// The container content
			// wppa_runtime_to_ajax_url() produced an error?
			if ( wppa( 'delayerror' ) ) {
				wppa_out( '<div style="color:red; font-weight:bold">' . wppa( 'delayerror' ) . '</div>' );
			}

			// delay="yes[,nnnn]"
			elseif ( $delay_arr[0] == 'yes' ) {

				if ( isset( $delay_arr[1] ) && wppa_is_posint( $delay_arr[1] ) ) {
					wppa_js( 'jQuery(document).ready(function(){setTimeout( function(){'.$request.'}, ' . $delay_arr[1] . ')});' );
				}
				else {
					wppa_js( 'jQuery(document).ready(function(){'.$request.'});' );
				}
			}

			// delay="text,Linktext"
			elseif (  $delay_arr[0] == 'text' ) {

				if ( wppa_is_posint( wppa( 'start_album' ) ) ) {
					$linktext = wppa_translate_album_keywords( wppa( 'start_album' ), $delay_arr[1] );
				}
				elseif ( wppa_is_posint( wppa( 'single_photo' ) ) ) {
					$linktext = wppa_translate_photo_keywords( wppa( 'single_photo' ), $delay_arr[1] );
				}
				else {
					$linktext = $delay_arr[1];
				}
				wppa_out( '
					<a
						id="wppa-delay-' . wppa( 'mocc' ) . '"
						class="wppa-delay"
						onclick="jQuery(\'.wppa-delay\').removeClass(\'wppa-delay-active\');jQuery(this).addClass(\'wppa-delay-active\');' . esc_attr( $request ) . '"
						style="cursor:pointer"
						>' .
						htmlspecialchars( $linktext ) .
					'</a>' );
			}

			// delay="button,Buttontext"
			elseif ( $delay_arr[0] == 'button' ) {

				if ( wppa_is_posint( wppa( 'start_album' ) ) ) {
					$linktext = wppa_translate_album_keywords( wppa( 'start_album' ), $delay_arr[1] );
				}
				elseif ( wppa_is_posint( wppa( 'single_photo' ) ) ) {
					$linktext = wppa_translate_photo_keywords( wppa( 'single_photo' ), $delay_arr[1] );
				}
				else {
					$linktext = $delay_arr[1];
				}
				wppa_out( '
					<input
						id="wppa-delay-' . wppa( 'mocc' ) . '"
						type="button"
						onclick="' . esc_attr( $request ) . '"
						value="' . esc_attr( $linktext ) . '"
						style="cursor:pointer"
					/>' );
			}
			else {
				wppa_out( ' Syntax error in delay specification ' . wppa( 'delay' ) );
			}

			// Close container
			if ( $no_container ) {
				wppa_out( '<br/>' );
			}
			else wppa_container( 'close' );

			// Save output
			$out = wppa( 'out' );

			// Cache?
			if ( $caching ) {
				wppa_save_cache_file( ['file' => $cache_file, 'data' => $out] );
			}

			// Done
			$tim = wppa_occur_timer( 'show', $_SERVER['REQUEST_URI'] . ' oc ' . wppa( 'mocc' ) , false, true );
			wppa_reset_occurrance();

			return $out . $tim;
		}

		// The following cases are not item selection dependant so they can be handled directly
		if ( wppa( 'is_admins_choice' ) ) {
			$args = wppa( 'admins_choice_users' );
			wppa_admins_choice_box( $args );
			$out = wppa( 'out' );
			wppa_reset_occurrance();
			return $out;
		}

		elseif ( wppa( 'bestof' ) ) {
			$args = wppa( 'bestof_args' );
			wppa_bestof_box( $args );
			$out = wppa( 'out' );
			wppa_reset_occurrance();
			return $out;
		}

		elseif ( wppa( 'is_notify' ) ) {
			wppa_notify_box();
			$out = wppa( 'out' );
			wppa_reset_occurrance();
			return $out;
		}

		elseif ( wppa( 'is_landing' ) && ! wppa( 'src' ) ) {
			wppa_reset_occurrance();
			return '';	// Do nothing on a landing page without a querystring while it is also not a search operation
		}
		// End direct handled

		// Single autopage item
		elseif ( wppa( 'is_autopage' ) ) {
			$photo = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos
													  WHERE page_id = %d
													  LIMIT 1", wppa_get_the_id() ) );
			if ( ! $photo ) {
				wppa_reset_occurrance();
				return '';	// Give up
			}

			wppa( 'single_photo', $photo );
			$type = wppa_opt( 'auto_page_type' );
			switch ( $type ) {
				case 'photo':
					break;
				case 'mphoto':
					wppa( 'is_mphoto', true );
					break;
				case 'xphoto':
					wppa( 'is_xphoto', true );
					break;
				case 'slphoto':
					wppa( 'is_slide', true );
					wppa( 'start_photo', wppa( 'single_photo' ) );
					wppa( 'is_single', true );
					break;
				default:
			}
		}
	}

	// Convert any keywords and / or names to numbers
	// Search for album keyword
	$bret = wppa_virt_album_to_runparms( wppa( 'start_album' ) );
	if ( ! $bret ) {
		wppa_reset_occurrance();
		return; // Error happened
	}

	// If single album, test if it is a granted parent
	if ( wppa_is_int( wppa( 'start_album' ) ) && wppa( 'start_album' ) > '0' ) {
		wppa_grant_albums( wppa( 'start_album' ) );
	}

	// See if the photo id is a keyword and convert it if possible
	$bret = wppa_virt_photo_to_runparms( wppa( 'single_photo' ) );
	if ( ! $bret ) {
		wppa_reset_occurrance();
		return;	// Forget this occurrance
	}

	// Size and align
	if ( is_numeric( $size ) ) {
		wppa( 'fullsize', $size );
	}
	elseif ( $size == 'auto' ) {
		wppa( 'auto_colwidth', true );
	}
	if ( $align == 'left' || $align == 'center' || $align == 'right' ) {
		wppa( 'align', $align );
	}

	// Empty related shortcode?
	if ( wppa( 'is_related' ) ) {
		$thumbs = wppa_get_photos();
		if ( empty( $thumbs ) ) {
			wppa_errorbox( __( 'No related photos found.', 'wp-photo-album-plus' ) );
			$result = wppa( 'out' );
			wppa_reset_occurrance();	// Forget this occurrance
			return $result;
		}
	}

	// Subsearch or rootsearch?
	if ( wppa( 'mocc' ) == wppa_opt( 'search_oc' ) &&
		 ! wppa( 'in_widget' ) &&
		 ( ( isset( $wppa_session['has_searchbox'] ) && $wppa_session['has_searchbox'] ) ||
			 wppa_get( 'forceroot' )
		 ) ) {

		// Is it a search now?
		if ( wppa( 'src' ) ) {

			// Is the subsearch box checked?
			wppa( 'is_subsearch', wppa_get( 'subsearch', '', 'text' ) );

			// Is the rootsearch box checked?
			wppa( 'is_rootsearch', wppa_get( 'rootsearch', '', 'text' ) );

			// Is it even a forced root search?
			if ( wppa_get( 'forceroot' ) ) {
				$wppa_session['search_root'] = wppa_get( 'forceroot' );
				wppa( 'is_rootsearch', true );
				wppa( 'start_album', wppa_get( 'forceroot' ) );
			}

			// No rootsearch, forget previous root
			if ( ! wppa( 'is_rootsearch' ) ) {
				$wppa_session['search_root'] = '0';
			}
		}

		// It is not a search now
		else {

			// Find new potential searchroot
			if ( wppa_get( 'searchroot' ) ) {
				wppa( 'start_album', wppa_get( 'searchroot' ) );
			}

			// Update session with new searchroot
			$wppa_session['search_root'] = wppa( 'start_album' );
		}

		// Update searchroot in search boxes
		$rt = $wppa_session['search_root'];
		if ( ! $rt || wppa_is_enum( $rt ) ) $rt = '0';	// must be non-empty string
		wppa_add( 'src_script', 'jQuery(document).ready(function(){wppaUpdateSearchRoot( \'' . esc_js( wppa_display_root( $rt ) ) . '\', \'' . $rt . '\' )});' );

		// If not search forget previous results
		if ( ! wppa( 'src' )  ) {
			$wppa_session['use_searchstring'] = '';
			$wppa_session['display_searchstring'] = '';
			wppa_add( 'src_script', 'jQuery(document).ready(function(){wppaClearSubsearch()});' );
		}
		else { // Enable subbox
			wppa_add( 'src_script', 'jQuery(document).ready(function(){wppaEnableSubsearch()});' );
		}
	}

	// Is it hidden behind an Ajax activating button?
	if ( wppa( 'is_button' ) ) {
		wppa_button_box();
	}
	// Is it url?
	elseif ( wppa( 'is_url' ) ) {
		if ( wppa_photo_exists( wppa( 'single_photo' ) ) ) {
			wppa_out( wppa_get_hires_url( wppa( 'single_photo' ) ) );
		}
		else {
			wppa_out( sprintf( 'Photo %s not found', wppa( 'single_photo' ) ) );
		}
	}
	// Is is a stereo settings box?
	elseif ( wppa( 'is_stereobox' ) ) {
		wppa_stereo_box();
	}
	// Is it the search box?
	elseif ( wppa( 'is_searchbox' ) ) {
		wppa_search_box( '', wppa( 'may_sub' ), wppa( 'may_root' ) );
	}
	// Is it the superview box?
	elseif ( wppa( 'is_superviewbox' ) ) {
		wppa_superview_box( wppa( 'start_album' ) );
		$albums_used = '*';
	}
	// Is it the multitag box?
	elseif ( wppa( 'is_multitagbox' ) ) {
		wppa_multitag_box( wppa( 'tagcols' ), wppa( 'taglist' ) );
	}
	// Is it the tagcloud box?
	elseif ( wppa( 'is_tagcloudbox' ) ) {
		wppa_tagcloud_box( wppa( 'taglist' ), wppa_opt( 'tagcloud_min' ), wppa_opt( 'tagcloud_max' ) );
	}
	// Is it an upload box?
	elseif ( wppa( 'is_upload' ) ) {
		wppa_upload_box();
	}
	// Is it a supersearch box?
	elseif ( wppa( 'is_supersearch' ) ) {
		wppa_supersearch_box();
	}

	// Is it newstyle single photo xtended mediastyle?
	elseif ( wppa( 'is_xphoto' ) == '1' ) {
		if ( wppa( 'is_autopage' ) ) wppa_auto_page_links( 'top' );
		wppa_smx_photo( wppa( 'start_photo' ), 'x' );
		if ( wppa( 'is_autopage' ) ) wppa_auto_page_links( 'bottom' );
	}
	// Is it newstyle single photo mediastyle?
	elseif ( wppa( 'is_mphoto' ) == '1' ) {
		if ( wppa( 'is_autopage' ) ) wppa_auto_page_links( 'top' );
		wppa_smx_photo( wppa( 'start_photo' ), 'm' );
		if ( wppa( 'is_autopage' ) ) wppa_auto_page_links( 'bottom' );
	}
	// Is it newstyle single photo plain?
	elseif ( wppa_page( 'oneofone' ) ) {
		if ( wppa( 'is_autopage' ) ) wppa_auto_page_links( 'top' );
		wppa_smx_photo( wppa( 'start_photo' ), 's' );
		if ( wppa( 'is_autopage' ) ) wppa_auto_page_links( 'bottom' );
	}
	// Is it the calendar?
	elseif ( wppa( 'is_calendar' ) ) {

		// New style?
		if ( substr( wppa( 'calendar' ), 0, 4 ) == 'real' ) {
			wppa_real_calendar_box();
		}
		else {
			wppa_calendar_box();
		}

		$photos_used = '*';
	}
	// Is it a contest
	elseif ( wppa( 'is_contest' ) ) {
		wppa_contest_box();
	}
	// Is it grid display?
	elseif ( wppa( 'is_grid' ) ) {
		wppa_grid_box();
	}
	// Is it audio only?
	elseif ( wppa( 'is_audioonly' ) ) {
		wppa_audio_only_box();
	}

	// The normal case
	else {
		if ( wppa( 'is_autopage' ) ) wppa_auto_page_links( 'top' );
		wppa_theme();	// Call the theme module
		if ( wppa( 'is_autopage' ) ) wppa_auto_page_links( 'bottom' );
	}

	// Insert geo data
	$out = str_replace( 'w#location', wppa( 'geo' ), wppa( 'out' ) );

	// Cache?
	if ( $caching ) {
		wppa_save_cache_file( ['file' => $cache_file, 'data' => $out] );
	}

	// Reset timer and occurrance
	$timer = wppa_occur_timer( 'show', $_SERVER['REQUEST_URI'] . ' oc ' . wppa( 'mocc' ) );
	wppa_reset_occurrance();

	// Return our valuable ouput
	return $out . $timer;
}

// Check if a meaningful url is supplied
// It is assumed mocc is given and equal to current mocc
function wppa_is_url_valid() {

	$args = ['photo', 'album'];
	foreach( $args as $arg ) {
		$val = wppa_get( $arg );
		if ( $val || $val === '0' ) { 	// not false and not null
			return true;
		}
	}
	return false;
}

// Convert album name ti id
function wppa_album_name_to_number( $xalb, $return_dups = false ) {

	// Sanitize
	$xalb = strip_tags( $xalb );

	// Any non integer input left?
	if ( $xalb && ! wppa_is_int( $xalb ) ) {

		// Is it a name?
		if ( substr( $xalb, 0, 1 ) == '$' ) {

			if ( $return_dups ) {
				$id = wppa_get_album_id_by_name( substr( $xalb, 1 ), 'return_dups' );
			}
			else {
				$id = wppa_get_album_id_by_name( substr( $xalb, 1 ) );
			}

			// Anything found?
			if ( $id > '0' ) return $id;

			// Handle exceptions
			elseif ( $id < '0' ) {
				wppa_out( 'Duplicate album names found: '.$xalb );
				wppa_reset_occurrance();
				return false;	// Forget this occurrance
			}
			else {
				wppa_out( 'Album name not found: '.$xalb );
				wppa_reset_occurrance();
				return false;	// Forget this occurrance
			}
		}
		else return $xalb; // Is album enum
	}
	else return $xalb; // Is non zero integer
}

function wppa_get_related_data() {
global $wpdb;

	$pagid = wppa_get_the_id();
	$data = $wpdb->get_var( $wpdb->prepare( "SELECT post_content FROM $wpdb->posts WHERE ID = %d", $pagid ) );
	$data = str_replace( array( ' ', ',', '.', "\t", "\r", "0", "x0B", "\n" ), ';', $data );
	$data = strip_tags( $data );
	$data = str_replace( array( '<', '>' ), ' ', $data );
	$data = strip_shortcodes( $data );
	$data = str_replace( array( '&amp;', "'" ), '', $data );
	$data = str_replace( array( '(', ')', '[', ']', '{', '}', ':', '=' ), ' ', $data );
	$data = wppa_sanitize_tags( $data );
	$data = trim( $data, "; \t\n\r\0\x0B" );
	return $data;
}

// Determine in wich theme page we are, Album covers, Thumbnails or slideshow
function wppa_page( $page ) {

	if ( wppa( 'is_slide' ) ) $cur_page = 'slide';				// Do slide or single when explixitly on
	elseif ( wppa( 'is_slideonly' ) ) $cur_page = 'slide';		// Slideonly is a subset of slide
	elseif ( is_numeric( wppa( 'single_photo' ) ) ) $cur_page = 'oneofone';
	else $cur_page = 'albums';

	if ( $cur_page == $page ) {
		return true;
	}
	else {
		return false;
	}
}

// loop album
function wppa_get_albums() {
global $wpdb;
global $wppa_session;
global $albums_used;

	if ( wppa( 'is_topten' ) ) 	return false;
	if ( wppa( 'is_lasten' ) ) 	return false;
	if ( wppa( 'is_comten' ) ) 	return false;
	if ( wppa( 'is_featen' ) ) 	return false;
	if ( wppa( 'is_tag' ) ) 	return false;
	if ( wppa( 'photos_only' ) ) return false;

	if ( wppa( 'src' ) && wppa_switch( 'photos_only' ) ) return false;
	if ( wppa( 'is_owner' ) && ! wppa( 'start_album' ) ) return false; 	// No owner album( s )

	if ( wppa( 'calendar' ) == 'exifdtm' ) return false;
	if ( wppa( 'calendar' ) == 'timestamp' ) return false;
	if ( wppa( 'calendar' ) == 'modified' ) return false;

	// Supersearch?
	if ( wppa( 'supersearch' ) ) {
		$ss_data = explode( ',', wppa( 'supersearch' ) );
		$data = $ss_data['3'];
		switch ( $ss_data['1'] ) {

			// Category
			case 'c':
				$catlist 	= wppa_get_catlist();
				if ( strpos( $data, '.' ) ) {
					$temp = explode( '.', $data );
					$ids = $catlist[$temp['0']]['ids'];
					$i = '1';
					while ( $i < count( $temp ) ) {
						$ids = array_intersect( $ids, $catlist[$temp[$i]]['ids'] );
						$i++;
					}
				}
				else {
					$ids 	= $catlist[$data]['ids'];
				}
				if ( empty( $ids ) ) {
					$ids = array( '0' );	// Dummy
				}
				$query 		= "SELECT * FROM $wpdb->wppa_albums WHERE id IN (" . implode( ',',$ids ) . ")";
				$albums 	= $wpdb->get_results( $query, ARRAY_A );
				break;

			// Name. Name is converted to number or enum
			case 'n':
				$query 		= $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums WHERE sname = %s", wppa_name_slug( $data ) );
				$albums 	= $wpdb->get_results( $query, ARRAY_A );
				break;

			// Text
			case 't':
				if ( strpos( $data, '.' ) ) {
					$temp 		= explode( '.', $data );
					$query 		= $wpdb->prepare( "SELECT * FROM $wpdb->wppa_index WHERE slug = %s", $temp['0'] );
					$indexes 	= $wpdb->get_row( $query, ARRAY_A );
					$ids 		= explode( '.', wppa_expand_enum( $indexes['albums'] ) );
					$i = '1';
					while ( $i < count( $temp ) ) {
						$query 		= $wpdb->prepare( "SELECT * FROM $wpdb->wppa_index WHERE slug = %s", $temp[$i] );
						$indexes 	= $wpdb->get_row( $query, ARRAY_A );
						$ids 		= array_intersect( $ids, explode( '.', wppa_expand_enum( $indexes['albums'] ) ) );
						$i++;
					}
				}
				else {
					$query 		= $wpdb->prepare( "SELECT * FROM $wpdb->wppa_index WHERE slug = %s", $data );
					$indexes 	= $wpdb->get_row( $query, ARRAY_A );
					$ids 		= explode( '.', wppa_expand_enum( $indexes['albums'] ) );
				}
				if ( empty( $ids ) ) {
					$ids = array( '0' ); 	// Dummy
				}
				$query 		= "SELECT * FROM $wpdb->wppa_albums WHERE id IN (" . implode( ',', $ids ) . ")";
				$albums 	= $wpdb->get_results( $query, ARRAY_A );
				break;

			default:
				wppa_log( 'err', 'Unimplemented supersearch album selection method: ' . $ss_data['1'] . ' in wppa_get_albums()' );
				break;
		}
	}

	// Search?
	elseif ( wppa( 'src' ) ) {

		$searchstring = wppa( 'searchstring' );
		if ( ! empty ( $wppa_session['use_searchstring'] ) ) $searchstring = $wppa_session['use_searchstring'];

		$final_array = wppa_get_array_ids_from_searchstring( $searchstring, 'albums' );

		// If Catbox specifies a category to limit, remove all albums that do not have the desired cat.
		if ( wppa( 'catbox' ) ) {
			$likecats = '%' . $wpdb->esc_like( wppa( 'catbox' ) ) . '%';
			$catalbs = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums
														WHERE cats LIKE %s", $likecats ) );
			$final_array = array_intersect( $final_array, $catalbs );
		}

		// Compose WHERE clause
		$selection = " id = '0' ";
		foreach ( array_keys( $final_array ) as $p ) {
			$selection .= "OR id = '".$final_array[$p]."' ";
		}

		// Get them
		$albums = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_albums
									   WHERE " . $selection . " " . wppa_get_album_order( '0' ), ARRAY_A );

		// Exclusive separate albums?
		if ( wppa_switch( 'excl_sep' ) ) {
			foreach ( array_keys( $albums ) as $idx ) {
				if ( wppa_is_separate( $albums[$idx]['id'] ) ) unset ( $albums[$idx] );
			}
		}

		// Rootsearch?
		if ( wppa( 'is_rootsearch' ) ) {
			$root = $wppa_session['search_root'];
			if ( is_array( $albums ) ) {
				$c1=count( $albums );
				foreach ( array_keys ( $albums ) as $idx ) {
					if ( ! wppa_is_ancestor( $root, $albums[$idx]['id'] ) ) unset ( $albums[$idx] );
				}
				$c2=count( $albums );
			}
		}

		// Check maximum
		$max = intval ( wppa_opt( 'max_search_albums' ) );
		if ( $max && is_array( $albums ) && count( $albums ) > $max ) {
			$alert_text = sprintf( 	__( 'There are %s albums found. Only the first %s will be shown. Please refine your search criteria.', 'wp-photo-album-plus' ),
									count( $albums ),
									wppa_opt( 'max_search_albums' ) );
			wppa_alert( $alert_text );
			$albums = array_slice( $albums, 0, $max );
		}

		if ( is_array( $albums ) && count( $albums ) ) wppa( 'any', true );
	}
	else {	// Its not search
		$id = wppa( 'start_album' );
		if ( ! $id ) $id = '0';

		// Do the query
		if ( $id == '-2' ) {	// All albums
			if ( wppa( 'is_cover' ) ) {
				$q = "SELECT * FROM $wpdb->wppa_albums ".wppa_get_album_order();
				$albums = $wpdb->get_results( $q, ARRAY_A );
			}
			else $albums = false;
		}
		elseif ( wppa( 'last_albums' ) ) {	// is_cover = true. For the order sequence, see remark in wppa_albums()
			if ( wppa( 'last_albums_parent' ) ) {
				$q = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
									  WHERE a_parent = %s
									  ORDER BY timestamp DESC
									  LIMIT %d", wppa( 'last_albums_parent' ), wppa( 'last_albums' ) );
			}
			else {
				$q = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
									  WHERE id > 0
									  ORDER BY timestamp DESC
									  LIMIT %d", wppa( 'last_albums' ) );
			}
			$albums = $wpdb->get_results( $q, ARRAY_A );
		}
		elseif ( wppa_is_int( $id ) ) {
			if ( wppa( 'is_cover' ) ) {
				$q = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
									  WHERE id = %d", $id );
			}
			else {
				$q = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
									  WHERE a_parent = %d " . wppa_get_album_order( $id ), $id );
			}
			$albums = $wpdb->get_results( $q, ARRAY_A );
		}
		elseif ( strpos( $id, '.' ) !== false ) {	// Album enum
			$ids = wppa_series_to_array( $id );
			if ( wppa( 'is_cover' ) ) {
				$q = "SELECT * FROM $wpdb->wppa_albums
					  WHERE id = " . implode( " OR id = ", $ids ) . " " . wppa_get_album_order();
			}
			else {
				$q = "SELECT * FROM $wpdb->wppa_albums
					  WHERE a_parent = " . implode( " OR a_parent = ", $ids ) . " " . wppa_get_album_order();
			}
			$albums = $wpdb->get_results( $q, ARRAY_A );
		}
		else $albums = false;
	}

	// Check for album status
	$albums = wppa_strip_void_albums( $albums );

	// Copy data into secondary cache
	if ( $albums ) {
		wppa_cache_album( 'add', $albums );
	}

	// Get the ids for search admin
	$result_ids_a = array_column( $albums, 'id' );

	// Post process for subsearch
	if ( wppa( 'is_subsearch' ) ) {
		$found_before_a = explode( '.', wppa_expand_enum( $wppa_session['search_albums'] ) );
		if ( $found_before_a ) {
			$result_ids_a = array_intersect( $found_before_a, $result_ids_a );
		}
	}

	// Save ids for page caching
	$albums_used .= '.' . implode( '.', $result_ids_a );

	// If searching, save result ids in session for possible subsearch
	if ( wppa( 'src' ) ) {
		$wppa_session['search_albums'] = wppa_compress_enum( implode( '.', $result_ids_a ) );
	}

	// Setup the final result array of photo data
	$the_result_a = array();
	foreach( $albums as $album ) {
		if ( in_array( $album['id'], $result_ids_a ) ) {
			$the_result_a[] = $album;
		}
	}

	wppa( 'album_count', ( $the_result_a ? count( $the_result_a ) : 0 ) );

	return $the_result_a;
}

// loop thumbs
function wppa_get_photos() {
global $wpdb;
global $wppa_session;

	// A cover -> no thumbs
	if ( wppa( 'is_cover' ) ) {
		return false;
	}

	// Albums only -> no thumbs
	if ( wppa( 'albums_only' ) ) {
		return false;
	}

	// Init
	$count_first = true;

	// Start timer
	$time = -microtime( true );

	// Compute separate albums
	$seps = str_replace( '.', ',', wppa_expand_enum( wppa_alb_to_enum_children( '-1' ) ) );

	// Limit
	$limit_clause = '';

	// Start photos given?
	if ( wppa( 'start_photos' ) ) {

		$photos = wppa_expand_enum( wppa( 'start_photos' ) );
		$ids = explode( '.', $photos );
		$ids = wppa_strip_void_photos( $ids );
		if ( count( $ids ) ) {
			$photos = implode( ',', $ids );
			$query = "SELECT * FROM $wpdb->wppa_photos
					  WHERE id IN ($photos)";
			$thumbs = wppa_do_get_thumbs_query( $query );
		}
		else {
			$thumbs = false;
		}
		return $thumbs;
	}

	// Make Album clause if album given
	if ( wppa( 'start_album' ) ) {

		// See if album is an enumeration or range
		$fullalb = wppa( 'start_album' );

		// Single album
		if ( strpos( $fullalb, '.' ) == false ) {
			$album_clause = " album = $fullalb ";
		}

		// Enum albums
		else {
			$ids = wppa_series_to_array( $fullalb );
			$ids = wppa_strip_void_albums( $ids );
			$album_clause = " album IN ( " . implode( ',', $ids ) . " ) ";
		}
	}

	// No album given, make sure trashed photos are not found
	else {
		$fullalb = '';
		$album_clause = " album > '0' ";
	}

	// For upload link on thumbarea: if startalbum is a single real album, put it in current album
	if ( wppa_is_int( wppa( 'start_album' ) ) ) {
		wppa( 'current_album', wppa( 'start_album' ) );
	}

	// So far so good
	// Now make the query, dependant of type of selection
	// Init
	$query = '';

	// Landscape or portrait only?
	if ( wppa( 'landscape' ) ) {
		$landscape_clause = ' AND (photox > photoy || videox > videoy) ';
	}
	elseif ( wppa( 'portrait' ) ) {
		$landscape_clause = ' AND (photox < photoy || videox < videoy) ';
	}
	else {
		$landscape_clause = '';
	}

	// Single image slideshow?
	if ( wppa( 'start_photo' ) && wppa( 'is_single' ) ) {

		$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
								  WHERE id = %s", wppa( 'start_photo' ) );
	}

	// Uploader?	// lasten with owner rstriction is handled at the Lasten case
	elseif ( wppa( 'is_upldr' ) && ! wppa( 'is_lasten' ) ) {
		$status = "status <> 'pending' AND status <> 'scheduled'";
		if ( ! is_user_logged_in() ) $status .= " AND status <> 'private'";

		$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
								  WHERE " . $album_clause . $landscape_clause . " AND owner = %s AND ( " . $status . " ) " .
								  wppa_get_photo_order(), wppa( 'is_upldr' ) );
	}

	// Topten?
	elseif ( wppa( 'is_topten' ) ) {
		$max = wppa( 'topten_count' );
		switch ( wppa_opt( 'topten_sortby' ) ) {
			case 'mean_rating':
				$sortby = "mean_rating DESC, rating_count DESC, views DESC";
				break;
			case 'rating_count':
				$sortby = "rating_count DESC, mean_rating DESC, views DESC";
				break;
			case 'views':
				$sortby = "views DESC, mean_rating DESC, rating_count DESC";
				break;
			case 'dlcount':
				$sortby = "dlcount DESC, mean_rating DESC, rating_count DESC, views DESC";
				break;
			default:
				wppa_error_message( 'Unimplemented sorting method' );
				$sortby = '';
				break;
		}
		if ( wppa( 'medals_only' ) ) {
			$status = "status IN ( 'gold', 'silver', 'bronze' )";
		}
		else {
			$status = "status <> 'pending' AND status <> 'scheduled'";
		}
		if ( ! is_user_logged_in() ) $status .= " AND status <> 'private'";

		$non_zero = "";
		if ( wppa_switch( 'topten_non_zero' ) ) {
			if ( wppa_opt( 'topten_sortby' ) == 'views' ) {
				$non_zero = "AND views > 0 ";
			}
			elseif ( wppa_opt( 'topten_sortby' ) == 'dlcount' ) {
				$non_xero = "AND dlcount > 0";
			}
			else {
				$non_zero = "AND rating_count > 0 ";
			}
		}
		$query = "SELECT * FROM $wpdb->wppa_photos
				  WHERE $album_clause AND ( $status )
				  $landscape_clause
				  $non_zero
				  ORDER BY $sortby
				  LIMIT $max";

		$count_first = false;
	}

	// Featen?
	elseif ( wppa( 'is_featen' ) ) {
		$max = wppa( 'featen_count' );

		$query = "SELECT * FROM $wpdb->wppa_photos
				  WHERE $album_clause $landscape_clause
				  AND status = 'featured'
				  ORDER BY RAND( " . wppa_get_randseed() . " )
				  LIMIT $max";

		$count_first = false;
	}

	// Lasten?
	elseif ( wppa( 'is_lasten' ) ) {
		$max = wppa( 'lasten_count' );
		$status = "status <> 'pending' AND status <> 'scheduled'";
		if ( ! is_user_logged_in() ) $status .= " AND status <> 'private'";
		$order_by = wppa_switch( 'lasten_use_modified' ) ? 'modified' : 'timestamp';
		$owner = sanitize_user( wppa( 'is_upldr' ) );
		if ( $owner == '#me' ) {
			$owner = wppa_get_user();
//			wppa( 'is_upldr', $owner );
		}
		$owner_restriction = ( wppa( 'is_upldr' ) ) ? "AND owner = '" . $owner . "' " : "";

		// If you want only 'New' photos in the selection, the period must be <> 0;
		if ( wppa_switch( 'lasten_limit_new' ) && wppa_opt( 'max_photo_newtime' ) ) {
			$newtime = " " . $order_by . " >= ".( time() - wppa_opt( 'max_photo_newtime' ) );
//			$owner_restriction = ( wppa( 'is_upldr' ) ) ? "AND owner = '" . sanitize_user( wppa( 'is_upldr' ) ) . "' " : "";

			if ( current_user_can( 'wppa_moderate' ) ) {

				$query = "SELECT * FROM $wpdb->wppa_photos
						  WHERE ( $album_clause ) $landscape_clause
						  AND ( $newtime )
						  $owner_restriction
						  ORDER BY $order_by DESC
						  LIMIT $max";
			}
			else {

				$query = "SELECT * FROM $wpdb->wppa_photos
						  WHERE ( $album_clause ) $landscape_clause
						  AND ( $status )
						  AND ( $newtime )
						  $owner_restriction
						  ORDER BY $order_by DESC
						  LIMIT $max";
			}
		}

		// No 'New' limitation
		else {
			if ( current_user_can( 'wppa_moderate' ) ) {

				$query = "SELECT * FROM $wpdb->wppa_photos
						  WHERE $album_clause $landscape_clause
						  $owner_restriction
						  ORDER BY $order_by DESC
						  LIMIT $max";
			}
			else {

				$query = "SELECT * FROM $wpdb->wppa_photos
						  WHERE ( $album_clause ) $landscape_clause
						  AND ( $status )
						  $owner_restriction
						  ORDER BY $order_by DESC
						  LIMIT $max";
			}
		}

		$count_first = false;
	}

	// Comten?
	elseif ( wppa( 'is_comten' ) ) {
		$alb_ids = wppa( 'start_album' );
		if ( strpos( $alb_ids, '.' ) !== false ) {
			$alb_ids = wppa_series_to_array( $alb_ids );
		}

		// Comments only visible if logged in or not required to log in
		if ( ! wppa_switch( 'comment_view_login' ) || is_user_logged_in() ) {
			$photo_ids = wppa_get_comten_ids( wppa( 'comten_count' ), (array) $alb_ids );
		}
		else {
			$photo_ids = false;
		}

		$status = "status <> 'pending' AND status <> 'scheduled'";
		if ( ! is_user_logged_in() ) $status .= " AND status <> 'private'";

		// To keep the sequence ok ( in sequence of comments desc ), do the queries one by one
		$thumbs = array();
		if ( is_array( $photo_ids ) ) foreach( $photo_ids as $id ) {
			$temp = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
													 WHERE $status $landscape_clause
													 AND album > '0'
													 AND id = %s", $id ), ARRAY_A );
			if ( $temp ) {
				$thumbs[] = $temp;
			}
		}

		wppa( 'any', ! empty ( $thumbs ) );
		wppa( 'thumb_count', empty( $thumbs ) ? '0' : count( $thumbs ) );
		$time += microtime( true );

		return $thumbs;
	}

	// Tagcloud or multitag? Tags do not look at album
	elseif ( wppa( 'is_tag' ) ) {

		// Init
		$andor = 'AND';
		if ( strpos( wppa( 'is_tag' ), ';' ) ) $andor = 'OR';

		// Related?
		if ( wppa( 'is_related' ) ) {
			$andor = 'OR';
			$limit_clause = sprintf( " LIMIT %d", wppa( 'related_count' ) );
		}

		// Compute status clause for query
		$status = "status <> 'pending' AND status <> 'scheduled'";
		if ( ! is_user_logged_in() ) $status .= " AND status <> 'private'";

		// Define tags clause for query
		$seltags = explode( ',', trim( wppa_sanitize_tags( wppa( 'is_tag' ) ), ',' ) );
		$tags_like = '';
		$first = true;
		foreach ( $seltags as $tag ) {
			if ( ! $first ) {
				$tags_like .= " " . $andor;
			}
			$tags_like .= " tags LIKE '%" . str_replace( "'", "\'", ',' . $wpdb->esc_like( $tag ) . ',' ) . "%'";
			$first = false;
		}

		// Album spec?
		if ( wppa_switch( 'excl_sep' ) && $seps ) {
			$album_clause = "album > 0 AND album NOT IN (" . $seps . ")";
		}
		else {
			$album_clause = "album > 0 ";
		}

		// Prepare the query
		if ( current_user_can( 'wppa_moderate' ) ) {
			$query = "SELECT * FROM $wpdb->wppa_photos
					  WHERE ( $tags_like )
					  AND $album_clause $landscape_clause
					  ORDER BY " . wppa_get_poc( '0' ) . "
					  $limit_clause";
		}
		else {
			$query = "SELECT * FROM $wpdb->wppa_photos
					  WHERE ( $tags_like )
					  AND $album_clause $landscape_clause
					  AND $status
					  ORDER BY " . wppa_get_poc( '0' ) . "
					  $limit_clause";
		}
	}

	// Supersearch?
	elseif ( wppa( 'supersearch' ) ) {

		$ss_data = explode( ',', wppa( 'supersearch' ) );

		// To preserve comma's in data[3], reconstruct a possible exploded data
		$data = $ss_data;
		unset( $data[0] );
		unset( $data[1] );
		unset( $data[2] );
		$data = implode( ',', $data );
		$ss_data[3] = $data;

		$status = "status <> 'pending' AND status <> 'scheduled'";
		if ( ! is_user_logged_in() ) $status .= " AND status <> 'private'";

		if ( isset( $ss_data['1'] ) ) switch ( $ss_data['1'] ) {

			// Name
			case 'n':
				$is = '=';
				if ( substr( $data, -3 ) == '...' ) {
					$data = substr( $data, 0, strlen( $data ) - 3 ) . '%';
					$is = 'LIKE';
				}
				if ( current_user_can( 'wppa_moderate' ) ) {
					$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
											  WHERE sname $is %s
											  AND album > 0 $landscape_clause
											  ORDER BY " . wppa_get_poc(), wppa_name_slug( $data ) );
				}
				else {
					$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
											  WHERE sname $is %s
											  AND album > 0 $landscape_clause
											  AND $status
											  ORDER BY " . wppa_get_poc(), wppa_name_slug( $data ) );
				}

				break;

			// Owner
			case 'o':
				if ( current_user_can( 'wppa_moderate' ) ) {
					$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
											  WHERE owner = %s
											  AND album > 0 $landscape_clause
											  ORDER BY " . wppa_get_poc(), $data );
				}
				else {
					$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
											  WHERE owner = %s
											  AND album > 0 $landscape_clause
											  AND $status
											  ORDER BY " . wppa_get_poc(), $data );
				}
				break;

			// Tag
			case 'g':
				$taglist = wppa_get_taglist();
				if ( strpos( $data, '.' ) ) {
					$qtags 	= explode( '.', $data );
					$tagids = $taglist[$qtags['0']]['ids'];
					$i = '0';
					while ( $i < count( $qtags ) ) {
						$tagids = array_intersect( $tagids, $taglist[$qtags[$i]]['ids'] );
						$i++;
					}
				}
				else {
					$tagids 	= $taglist[$data]['ids'];
				}
				if ( count( $tagids ) > '0' ) {
					$query = "SELECT * FROM $wpdb->wppa_photos
							  WHERE $status
							  AND id IN (" . implode( ',',$tagids ) . ")
							  AND album > 0 $landscape_clause
							  ORDER BY " . wppa_get_poc();
				}
				break;

			// Text
			case 't':

				// To distinguish items with ellipses, we temporary replace them with ***
				$data = str_replace( '...', '***', $data );
				if ( strpos( $data, '.' ) ) {
					$temp 		= explode( '.', $data );
					$is = '=';
					if ( wppa_opt( 'ss_text_max' ) ) {
						if ( substr( $temp['0'], -3 ) == '***' ) {
							$temp['0'] = substr( $temp['0'], 0, strlen( $temp['0'] ) - 3 ) . '%';
							$is = 'LIKE';
						}
					}
					$query 		= $wpdb->prepare( "SELECT * FROM $wpdb->wppa_index
												   WHERE slug $is %s", $temp['0'] );
					$indexes 	= $wpdb->get_results( $query, ARRAY_A );
					$ids 		= array();
					foreach( $indexes as $item ) {
						$ids 	= array_merge( $ids, explode( '.', wppa_expand_enum( $item['photos'] ) ) );
					}
					$i = '1';
					while ( $i < count( $temp ) ) {
						$is = '=';
						if ( wppa_opt( 'ss_text_max' ) ) {
							if ( substr( $temp[$i], -3 ) == '***' ) {
								$temp[$i] = substr( $temp[$i], 0, strlen( $temp[$i] ) - 3 ) . '%';
								$is = 'LIKE';
							}
						}

						$query 		= $wpdb->prepare( "SELECT * FROM $wpdb->wppa_index
													   WHERE slug $is %s", $temp[$i] );
						$indexes 	= $wpdb->get_results( $query, ARRAY_A );
						$deltaids 	= array();
						foreach( $indexes as $item ) {
							$deltaids 	= array_merge( $deltaids, explode( '.', wppa_expand_enum( $item['photos'] ) ) );
						}

						$ids 		= array_intersect( $ids, $deltaids );
						$i++;
					}
				}
				else {
					$is = '=';
					if ( wppa_opt( 'ss_text_max' ) ) {
						if ( substr( $data, -3 ) == '***' ) {
							$data = substr( $data, 0, strlen( $data ) - 3 ) . '%';
							$is = 'LIKE';
						}
					}
					$query 		= $wpdb->prepare( "SELECT * FROM $wpdb->wppa_index
												   WHERE slug $is %s", $data );
					$indexes 	= $wpdb->get_results( $query, ARRAY_A );
					$ids 		= array();
					foreach( $indexes as $item ) {
						$ids 	= array_merge( $ids, explode( '.', wppa_expand_enum( $item['photos'] ) ) );
					}
				}
				if ( empty( $ids ) ) {
					$ids = array( '0' ); 	// Dummy
				}
				$query = "SELECT * FROM $wpdb->wppa_photos
						  WHERE $status
						  AND album > 0 $landscape_clause
						  AND id IN (" . trim( implode( ',', $ids ), ',' ) . ")
						  ORDER BY " . wppa_get_poc();
				break;

			// Iptc
			case 'i':
				$itag 		= str_replace( 'H', '#', $ss_data['2'] );
				$desc 		= $ss_data['3'];
				$query 		= $wpdb->prepare( "SELECT * FROM $wpdb->wppa_iptc
											   WHERE tag = %s
											   AND description = %s", $itag, $desc );
				$iptclines 	= $wpdb->get_results( $query, ARRAY_A );
				$ids 		= '0';
				if ( is_array( $iptclines ) ) foreach( $iptclines as $item ) {
					$ids .= ','.$item['photo'];
				}
				$query 		= "SELECT * FROM $wpdb->wppa_photos
							   WHERE $status
							   AND album > '0' $landscape_clause
							   AND id IN (" . $ids . ")
							   ORDER BY " . wppa_get_poc();
				break;

			// Exif
			case 'e':
				$etag 		= substr( str_replace( 'H', '#', $ss_data['2'] ), 0, 6 );
				$brand 		= substr( $ss_data[2], 6 );
				$desc 		= $ss_data['3'];
				$query 		= $wpdb->prepare( "SELECT * FROM $wpdb->wppa_exif
											   WHERE tag = %s
											   AND f_description = %s $landscape_clause
											   AND brand = %s", $etag, $desc, $brand );
				$exiflines 	= $wpdb->get_results( $query, ARRAY_A );
				$ids 		= '0';
				if ( is_array( $exiflines ) ) foreach( $exiflines as $item ) {
					$ids .= ',' . $item['photo'];
				}
				$query 		= "SELECT * FROM $wpdb->wppa_photos
							   WHERE $status
							   AND album > 0 $landscape_clause
							   AND id IN (" . $ids . ")
							   ORDER BY " . wppa_get_poc();
				break;

			default:
				break;
		}
	}

	// Search?
	elseif ( wppa( 'src' ) ) {	// Searching

		$status = "status <> 'pending' AND status <> 'scheduled'";
		if ( ! is_user_logged_in() ) $status .= " AND status <> 'private'";

		$searchstring = wppa( 'searchstring' );
		if ( ! empty ( $wppa_session['use_searchstring'] ) ) $searchstring = $wppa_session['use_searchstring'];

		$final_array = array();
		$final_array = wppa_get_array_ids_from_searchstring( $searchstring, 'photos' );

		// Remove scheduled and pending and trashed when not can moderate
		if ( ! current_user_can( 'wppa_moderate' ) ) {
			$needmod = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_photos
										WHERE status = 'scheduled'
										OR status = 'pending'
										OR album <= '-9'" );
			if ( is_array( $needmod ) ) {
				$final_array = array_diff( $final_array, $needmod );
			}
		}

		// Remove private and trashed when not logged in
		if ( ! is_user_logged_in() ) {
			$needlogin = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_photos
										  WHERE status = 'private' OR album <= '-9'" );
			if ( is_array( $needlogin ) ) {
				$final_array = array_diff( $final_array, $needlogin );
			}
		}

		// remove dups from $final_array
		$final_array = array_unique( $final_array );

		// Remove empty element from array
		$final_array = array_diff( $final_array, array( '' ) );

		// Make album clause
		$alb_clause = '';

		// If rootsearch, the album clause restricts to sub the root
		// else: maybe category limited or exclude separates
		// See for rootsearch
		if ( wppa( 'is_rootsearch' ) && isset ( $wppa_session['search_root'] ) ) {

			// Find all albums below root
			$root = $wppa_session['search_root'];
			$root_albs = wppa_expand_enum( wppa_alb_to_enum_children( $root ) );
			$root_albs = str_replace( '.', ',', $root_albs );
			$alb_clause = $root_albs ? " AND album IN (" . $root_albs . ") " : "";
		}


		// Maybe cats limitation
		elseif ( wppa( 'catbox' ) ) {

			$catalbs = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_albums
										WHERE cats LIKE '%" . wppa( 'catbox' ) . "%' " );

			if ( wppa_switch( 'excl_sep' ) ) {
				$sep = explode( '.', wppa_expand_enum( wppa_alb_to_enum_children( '-1' ) ) );
				$catalbs = array_diff( $catalbs, $sep );
			}

			if ( ! empty( $catalbs ) ) {
				$alb_clause = " AND album IN ( " . implode( ',', $catalbs ) . " ) ";
			}
		}

		// exclude separate if required
		if ( wppa_switch( 'excl_sep' ) && $seps && ! wppa( 'catbox' ) ) {
			if ( $alb_clause ) {
				$alb_clause .= " AND album NOT IN (" . $seps . ")";
			}
		}

		// Exclude deleted
		$alb_clause .= " AND album > 0 ";

		// compose photo selection
		if ( ! empty( $final_array ) ) {
			$selection = " id IN (";
			$selection .= implode( ',', $final_array );
			$selection .= ") ";
		}
		else {
			$selection = " id = '0' ";
		}

		// If Related, add related count max
		$limit = '';
		if ( wppa( 'is_related' ) ) {
			if ( wppa( 'related_count' ) ) {
				$limit = " LIMIT " . strval( intval( wppa( 'related_count' ) ) );
			}
		}

		// Construct the query
		$query = "SELECT * FROM $wpdb->wppa_photos
				  WHERE " . $selection .
				  $alb_clause .
				  $landscape_clause .
				  wppa_get_photo_order( '0' ) .
				  $limit;
	}

	// Calendar?
	elseif ( wppa( 'calendar' ) ) {
		$order = wppa_is_int( wppa( 'start_album' ) ) ? wppa_get_photo_order( wppa( 'start_album' ) ) : wppa_get_photo_order( '0' );
		if ( wppa( 'start_album' ) ) {
			$alb_clause = " AND album IN ( ". str_replace( '.', ',', wppa_expand_enum( wppa( 'start_album' ) ) ) ." ) ";
		}
		else {
			$alb_clause = " AND album > 0 ";
		}
		switch ( wppa( 'calendar' ) ) {
			case 'exifdtm':
				$query = 	"SELECT * FROM $wpdb->wppa_photos
							 WHERE exifdtm LIKE '" . strip_tags( wppa( 'caldate' ) ) . "%'
							 AND status <> 'pending'
							 AND status <> 'scheduled' " .
							 $alb_clause .
							 $landscape_clause .
							 $order;
				break;

			case 'timestamp':
				$t1 = strval( intval( wppa( 'caldate' ) * 24*60*60 ) );
				$t2 = $t1 + 24*60*60;
				$query = 	"SELECT * FROM $wpdb->wppa_photos
							 WHERE timestamp >= $t1
							 AND timestamp < $t2
							 AND status <> 'pending'
							 AND status <> 'scheduled' " .
							 $alb_clause .
							 $landscape_clause .
							 $order;
				break;

			case 'modified':
				$t1 = strval( intval( wppa( 'caldate' ) * 24*60*60 ) );
				$t2 = $t1 + 24*60*60;
				$query = 	"SELECT * FROM $wpdb->wppa_photos
							 WHERE modified >= $t1
							 AND modified < $t2
							 AND status <> 'pending'
							 AND status <> 'scheduled' " .
							 $alb_clause .
							 $landscape_clause .
							 $order;
				break;

			default:
				break;
		}
	}

	// Potd history?
	elseif ( wppa( 'is_potdhis' ) ) {
		$history = wppa_get_option( 'wppa_potd_log_data', array() );
		if ( is_array( $history ) ) {
			$thumbs = array();
			foreach ( $history as $item ) {
				$photo = wppa_cache_photo( $item['id'] );
				if ( $photo['album'] > '0' ) {
					$thumbs[] = $photo;
				}
			}
			return $thumbs;
		}
		return false;
	}

	// Normal
	else {

		// Special case slideshow widget limit?
		$lim = '';
		if ( wppa( 'max_slides_in_ss_widget' ) ) {
			$lim = " LIMIT " . wppa( 'max_slides_in_ss_widget' );
		}

		// Status
		$status = "status <> 'pending' AND status <> 'scheduled'";
		if ( ! is_user_logged_in() ) $status .= " AND status <> 'private'";

		// On which album( s )?
		if ( strpos( wppa( 'start_album' ), '.' ) !== false ) $allalb = wppa_series_to_array( wppa( 'start_album' ) );
		else $allalb = false;

		// All albums ?
		if ( wppa( 'start_album' ) == -2 ) {

			if ( current_user_can( 'wppa_moderate' ) ) {
				$query = "SELECT * FROM $wpdb->wppa_photos " . wppa_get_photo_order( '0' ) . $lim;
			}
			else {
				$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
										  WHERE ( ( " . $status . " )
										  OR owner = %s )
										  AND album > '0' " .
										  $landscape_clause .
										  wppa_get_photo_order( '0' ) .
										  $lim,
										  wppa_get_user() );
			}
		}

		// Single album ?
		elseif ( wppa_is_int( wppa( 'start_album' ) ) ) {
			if ( current_user_can( 'wppa_moderate' ) ) {
				$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
										  WHERE album = %d " .
										  $landscape_clause .
										  wppa_get_photo_order( wppa( 'start_album' ) ) .
										  $lim, wppa( 'start_album' ) );
			}
			else {
				$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
										  WHERE ( ( " . $status . " )
										  OR owner = %s )
										  AND album = %d " .
										  $landscape_clause .
										  wppa_get_photo_order( wppa( 'start_album' ) ) .
										  $lim,
										  wppa_get_user(), wppa( 'start_album' ) );
			}
		}

		// Album enumeration?
		elseif ( is_array( $allalb ) ) {
			$wherealbum = ' album IN (' . implode( ',', $allalb ) . ') ';
			if ( current_user_can( 'wppa_moderate' ) ) {
				$query = "SELECT * FROM $wpdb->wppa_photos
						  WHERE " . $wherealbum . $landscape_clause . " " .
						  wppa_get_photo_order( '0' ) .
						  $lim;
			}
			else {
				$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_photos
										  WHERE ( ( " . $status . " )
										  OR owner = %s )
										  AND " . $wherealbum . $landscape_clause . " " .
										  wppa_get_photo_order( '0' ) .
										  $lim,
										  wppa_get_user() );
			}
		}
	}

	// Anything to look for?
	if ( ! $query ) {

		// Not implemented or impossable shortcode
		return false;
	}

	// Do query and return result after copy result to $thumbs!!
	$thumbs = wppa_do_get_thumbs_query( $query );

	return $thumbs;
}

// Get the array of ids based on the supplied searchstring
function wppa_get_array_ids_from_searchstring( $searchstring, $type ) {
global $wpdb;

	// Sanitize input
	if ( ! in_array( $type, array( 'albums', 'photos' ) ) ) {
		die( 'Unsupported type:' . $type . ' in wppa_get_array_ids_from_searchstring()' );
	}

	// Split searchstring into OR chunks
	$chunks = explode( ',', stripslashes( strtolower( $searchstring ) ) );

	// Init
	$final_array 	= array();

	// Do all non empty chunks
	foreach ( $chunks as $chunk ) if ( strlen( trim( $chunk ) ) ) {

		// Init this chunk
		$not_words 		= array();
		$item_array 	= array();
		$not_item_array = array();

		// Get the words of this chunk
		$words = wppa_index_raw_to_words( $chunk, false, wppa_opt( 'search_min_length' ), false );

		// Remove !words and put them into the not_words array.
		if ( ! empty( $words ) ) foreach( array_keys( $words ) as $key ) {
			if ( substr( $words[$key], 0, 1 ) == '!' ) {
				$not_words[] = substr( $words[$key], 1 );
				unset( $words[$key] );
			}
		}

		// Meet all words in the chunk if it is not empty
		if ( ! empty( $words ) ) {

			// Process all words from this chunk
			foreach ( $words as $word ) {

				// Ceanup word
				$word = trim( $word );

				// Process only if the search token is long enough
				if ( strlen( $word ) >= wppa_opt( 'search_min_length' ) ) {

					// Trim searchword to a max of 20 chars
					if ( strlen( $word ) > 20 ) $word = substr( $word, 0, 20 );

					// Floating searchtoken?
					if ( wppa_switch( 'wild_front' ) ) {
						$idxs = $wpdb->get_col( $wpdb->prepare( "SELECT $type
																 FROM $wpdb->wppa_index
																 WHERE slug LIKE %s", '%' . $wpdb->esc_like( $word ) . '%' ) );
					}
					else {
						$idxs = $wpdb->get_col( $wpdb->prepare( "SELECT $type
																 FROM $wpdb->wppa_index
																 WHERE slug LIKE %s", $wpdb->esc_like( $word ) . '%' ) );
					}

					// $item_array is an array of arrays with item ids per word.
					$ids = array();
					if ( ! empty( $idxs ) ) foreach( $idxs as $i ) {
						$ids = array_merge( $ids, wppa_index_string_to_array( $i ) );
					}
					$item_array[] = $ids;

				}
			}

			// Must meet all words: intersect item sets. The first element serves as accumulator.
			foreach ( array_keys( $item_array ) as $idx ) {
				if ( $idx > 0 ) {
					$item_array[0] = array_intersect( $item_array[0], $item_array[$idx] );
				}
			}
		}

		// Now remove possible results that are excluded by the !words in this chunk
		if ( ! empty( $not_words ) ) {

			// Do all not words
			foreach( $not_words as $word ) {

				// Process only if the search token is long enough
				if ( strlen( $word ) >= wppa_opt( 'search_min_length' ) ) {

					// Trim searchword to a max of 20 chars
					if ( strlen( $word ) > 20 ) $word = substr( $word, 0, 20 );

					// Floating searchtoken?
					if ( wppa_switch( 'wild_front' ) ) {
						$idxs = $wpdb->get_col( $wpdb->prepare( "SELECT $type
																 FROM $wpdb->wppa_index
																 WHERE slug LIKE %s", '%' . $wpdb->esc_like( $word ) . '%' ) );
					}
					else {
						$idxs = $wpdb->get_col( $wpdb->prepare( "SELECT $type
																 FROM $wpdb->wppa_index
																 WHERE slug LIKE %s", $wpdb->esc_like( $word ) . '%' ) );
					}

					// Find ids to exclude for the current !word
					$ids = array();
					if ( ! empty( $idxs ) ) foreach( $idxs as $i ) {
						$ids = array_merge( $ids, wppa_index_string_to_array( $i ) );
					}

					// Accumuate items to exclude in $not_item_array for this chunk.
					$not_item_array = array_merge( $not_item_array, $ids );
				}
			}
		}

		// All words and not words of this chunk processed, remove not_array from item_array
		if ( ! empty( $not_item_array ) ) {
			$item_array[0] = array_diff( $item_array[0], $not_item_array );
		}

		// Save partial result of this chunk into the final_array accumulator
		if ( isset( $item_array[0] ) ) {
			$final_array = array_merge( $final_array, $item_array[0] );
		}
	}

	// Remove dups
	$final_array = array_unique( $final_array );

	return $final_array;
}

// Handle the select thumbs query
// @1: The MySql query
// @2: bool. Set to false if the expected count of thumbs is always less than 2500
function wppa_do_get_thumbs_query( $query ) {
global $wpdb;
global $photos_used;
global $wppa_session;

	// Anything to do here?
	if ( ! $query ) {
		wppa( 'thumb_count', '0' );
		wppa( 'any', false );
		return false;
	}

	// Init
	$time = -microtime( true );

	// Inverse requested?
	$invers = wppa( 'is_inverse' );

	// Extended dups removal?
	$exduprem = wppa_switch( 'extended_duplicate_remove' ) &&
				( wppa( 'src' ) ||
				  wppa( 'is_tag' ) ||
				  wppa( 'supersearch' ) ||
				  wppa( 'calendar' )
				);

	// Do we need to get the count first to decide if we get the full data and probably cache it ?
	if ( ! $exduprem ) {

		// Find count of the query result
		$tempquery 	= str_replace( 'SELECT *', 'SELECT id', $query );
		$wpdb->query( $tempquery );
		$count 		= $wpdb->get_var( 'SELECT FOUND_ROWS()' );

		// If less than 5000, get them and cache them
		if ( $count <= 5000 && ! $invers ) {
			$thumbs 	= $wpdb->get_results( $query, ARRAY_A );
			$caching 	= true;
		}

		// If more than 5000, or inverse requested, use the ids only, and do not cache them
		else {
			$thumbs 	= $wpdb->get_results( $tempquery, ARRAY_A );
			$caching 	= false;
		}
	}

	// Need no count first, just do it.
	else {
		$thumbs 	= $wpdb->get_results( $query, ARRAY_A );
		$caching 	= true;
		$count 		= count( $thumbs );
	}

	// Inverse selection requested?
	if ( $invers ) {
		$all = $wpdb->get_results( "SELECT id, album FROM $wpdb->wppa_photos ".wppa_get_photo_order( '0' ), ARRAY_A );
		if ( is_array( $thumbs ) ) foreach ( array_keys($thumbs) as $thumbs_key ) {
			foreach ( array_keys($all) as $all_key ) {
				if ( $thumbs[$thumbs_key]['id'] == $all[$all_key]['id'] ) {
					unset( $all[$all_key] );
				}
			}
		}

		// Resequence for slideshow pagination
		$thumbs = array();
		if ( ! empty( $all ) ) foreach( $all as $item ) {
			$thumbs[] = $item;
		}
	}

	// Log query
	if ( strlen( $query ) > 25000 ) {
		wppa_log( 'war', 'Long query: ' . strlen($query) . ' chars. ' . substr( htmlspecialchars( $query ), 0, 100 ) . '...' , true );
	}

	// Remove items because of status album and possibly because of meonly shortcode attribute
	$thumbs = wppa_strip_void_photos( $thumbs );

	// Process extended duplicate removal
	if ( $exduprem ) {
		wppa_extended_duplicate_remove( $thumbs );
	}

	// Pre-cache
	if ( $caching ) {
		wppa_cache_photo( 'add', $thumbs );
	}
	wppa( 'any', ! empty ( $thumbs ) );

	// Get the ids for search admin
	$result_ids_a = array_column( $thumbs, 'id' );

	// Post process for subsearch
	if ( wppa( 'is_subsearch' ) ) {
		$found_before_a = explode( '.', wppa_expand_enum( $wppa_session['search_photos'] ) );
		if ( $found_before_a ) {
			$result_ids_a = array_intersect( $found_before_a, $result_ids_a );
		}
	}

	// Check maximum
	$max = intval( wppa_opt( 'max_search_photos' ) );
	if ( $max && wppa( 'src' ) && count( $result_ids_a ) > $max )  {
		$alert_text = sprintf( 	__( 'There are %s photos found. Only the first %s will be shown. Please refine your search criteria.', 'wp-photo-album-plus' ),
									count( $result_ids_a ),
									$max );
		wppa_alert( $alert_text );
		$result_ids_a = array_slice( $result_ids_a, 0, $max );
	}

	// Save ids for page caching
	$photos_used .= '.' . implode( '.', $result_ids_a );

	// If searching, save result ids in session for possible subsearch
	if ( wppa( 'src' ) ) {
		$wppa_session['search_photos'] = wppa_compress_enum( implode( '.', $result_ids_a ) );
	}

	// Setup the final result array of photo data
	$the_result_a = array();
	foreach( $thumbs as $thumb ) {
		if ( in_array( $thumb['id'], $result_ids_a ) ) {
			$the_result_a[] = $thumb;
		}
	}

	wppa( 'thumb_count', count( $the_result_a ) );
	return $the_result_a;
}

// Strip void albums from array
// $albarr may contain ids only or full db rows
function wppa_strip_void_albums( $albarr ) {
global $wpdb;

	$result = array();

	// strip non visible for this visitor
	if ( is_array( $albarr ) ) foreach( $albarr as $album ) {
		if ( wppa_is_int( $album ) ) {
			if ( wppa_is_album_visible( $album ) ) {
				$result[] = $album;
			}
		}
		elseif ( is_array( $album ) ) {
			if ( wppa_is_album_visible( $album['id'] ) ) {
				$result[] = $album;
			}
		}
	}

	// optionally strip non meonly items, i.e. items not owned by this visitor
	if ( wppa_is_meonly() ) {
		$albarr 	= $result; 	// Start with stripped array
		$result 	= array(); 		// Re-init result
		$me 		= wppa_get_user();
		foreach( $albarr as $album ) {
			if ( wppa_is_int( $album ) ) {
				if ( wppa_get_album_owner( $album ) == $me ) {
					$result[] = $album;
				}
			}
			elseif ( is_array( $album ) ) {
				if ( wppa_get_album_owner( $album['id'] ) == $me ) {
					$result[] = $album;
				}
			}
		}
	}

	return $result;
}

// Strip void photos from array
// $photoarr may contain ids only or full db rows
function wppa_strip_void_photos( $photoarr ) {
global $wpdb;

	$result = array();

	// strip non visible for this visitor
	foreach( $photoarr as $photo ) {
		if ( wppa_is_int( $photo ) ) {
			if ( wppa_is_photo_visible( $photo ) ) {
				$result[] = $photo;
			}
		}
		elseif ( is_array( $photo ) ) {
			if ( wppa_is_photo_visible( $photo['id'] ) ) {
				$result[] = $photo;
			}
		}
	}

	// optionally strip non meonly items, i.e. items not owned by this visitor
	if ( wppa_is_meonly() ) {
		$photoarr 	= $result; 	// Start with stripped array
		$result 	= array(); 		// Re-init result
		$me 		= wppa_get_user();
		foreach( $photoarr as $photo ) {
			if ( wppa_is_int( $photo ) ) {
				if ( wppa_get_photo_owner( $photo ) == $me ) {
					$result[] = $photo;
				}
			}
			elseif ( is_array( $photo ) ) {
				if ( wppa_get_photo_owner( $photo['id'] ) == $me ) {
					$result[] = $photo;
				}
			}
		}
	}

	return $result;
}

// Remove duplicates where name, description and display files are identical
function wppa_extended_duplicate_remove( &$thumbs ) {

	$start = microtime( true );

	if ( is_array( $thumbs ) ) {
		$c = count( $thumbs );

		// New algorithm
		$to_remove = array();

		// Check for duplicate
		$temp = wppa_array_sort( $thumbs, 'name' );

		// Make indices sequential
		$temp1 = $temp;
		$temp = array();
		foreach ( array_keys( $temp1 ) as $key ) {
			$temp[] = $temp1[$key];
		}

		$i = 0;
		$j = count( $temp ) - 1;
		while ( $i < $j ) {
			if ( $temp[$i]['name'] == $temp[$i+1]['name'] ) {

				// Names are equal.
				if ( wppa_are_items_equal( $temp[$i], $temp[$i+1] ) ) {
					$to_remove[] = $temp[$i+1]['id'];
				}
			}
			$i++;
		}

		// Now remove
		$rem_cnt = 0;
		foreach ( array_keys( $thumbs ) as $key ) {
			if ( in_array( $thumbs[$key]['id'], $to_remove ) ) {
				unset( $thumbs[$key] );
				$rem_cnt++;
			}
		}
	}

	$end = microtime( true );
}

// Compare two items for equality
function wppa_are_items_equal( $xit1, $xit2 ) {
global $wpdb;
static $unique_ids;

	// Init
	if ( ! $unique_ids ) {
		$unique_ids = array();
	}
	$id1 = $xit1['id'];
	$it1 = wppa_cache_photo( $id1 );
	$id2 = $xit2['id'];
	$it2 = wppa_cache_photo( $id2 );

	$score = 0;

	// If one of these item is equal, the items are the same
	// EXIF ImageUniqueID
	if ( isset( $unique_ids[$id1] ) ) {
		$E1 = $unique_ids[$id1];
		if ( $E1 == 'none' ) {
			$E1 = false;
		}
	}
	else {
		$E1 = $wpdb->get_var( $wpdb->prepare( "SELECT description FROM $wpdb->wppa_exif WHERE photo=%s AND tag=%s", $it1['id'], 'E#A420' ) );
		$unique_ids[$id1] = ( $E1 ? $E1 : 'none' );
	}

	if ( isset( $unique_ids[$id2] ) ) {
		$E2 = $unique_ids[$id2];
		if ( $E2 == 'none' ) {
			$E2 = false;
		}
	}
	else {
		$E2 = $wpdb->get_var( $wpdb->prepare( "SELECT description FROM $wpdb->wppa_exif WHERE photo=%s AND tag=%s", $it2['id'], 'E#A420' ) );
		$unique_ids[$id2] = ( $E2 ? $E2 : 'none' );
	}

	if ( $E1 && $E2 && $E1 == $E2 ) {
		return true;
	}
	if ( $E1 && $E2 && $E1 != $E2 ) {
		return false;
	}

	// EXIF date/time
	$E1 = $it1['exifdtm'];
	$E2 = $it2['exifdtm'];
	if ( $E1 && $E2 && $E1 == $E2 ) {
		return true;
	}
	if ( $E1 && $E2 && $E1 != $E2 ) {
		return false;
	}

	// Name
	if ( wppa_looks_equal( $it1['name'], $it2['name'] ) && $it1['name'] ) $score++; 						// equal and not empty

	// Description
	if ( wppa_looks_equal( $it1['description'], $it2['description'] ) && $it1['description'] ) $score++; 	// equal and not empty

	// Filename
	if ( wppa_looks_equal( $it1['filename'], $it2['filename'] ) && $it1['filename'] ) $score++; 			// equal and not empty

	if ( $score == 3 ) return true;

	// Equal source filesize?
	$s1 = wppa_get_source_path( $it1['id'] );
	$s2 = wppa_get_source_path( $it2['id'] );
	if ( wppa_is_file( $s1 ) && wppa_is_file( $s2 ) ) {
		if ( wppa_filesize( $s1 ) == wppa_filesize( $s2 ) ) {
			$score++;
		}
	}
	if ( $score >= 4 ) return true;

	// Equal display filesize?
	$d1 = wppa_get_photo_path( $it1['id'] );
	$d2 = wppa_get_photo_path( $it2['id'] );
	if ( wppa_is_file( $d1 ) && wppa_is_file( $d2 ) ) {
		if ( wppa_filesize( $d1 ) == wppa_filesize( $d2 ) ) {
			$score++;
		}
	}
	if ( $score >= 4 ) return true;

	// Equal thumnail filesize?
	$t1 = wppa_get_thumb_path( $it1['id'] );
	$t2 = wppa_get_thumb_path( $it2['id'] );
	if ( wppa_is_file( $t1 ) && wppa_is_file( $t2 ) ) {
		if ( wppa_filesize( $t1 ) == wppa_filesize( $t2 ) ) {
			$score++;
		}
	}
	if ( $score >= 4 ) return true;

	// Equal thumbnail file content?
	if ( wppa_is_file( $t1 ) && wppa_is_file( $t2 ) ) {
		if ( wppa_get_contents( $t1 ) == wppa_get_contents( $t2 ) ) {
			$score++;
		}
	}
	if ( $score >= 4 ) return true;

	// Equal display file content?
	if ( wppa_is_file( $d1 ) && wppa_is_file( $d2 ) ) {
		if ( wppa_get_contents( $d1 ) == wppa_get_contents( $d2 ) ) {
			$score++;
		}
	}
	if ( $score >= 4 ) return true;

	// Equal source file content?
	if ( wppa_is_file( $s1 ) && wppa_is_file( $s2 ) ) {
		if ( wppa_get_contents( $s1 ) == wppa_get_contents( $s2 ) ) {
			$score++;
		}
	}
	if ( $score >= 4 ) return true;

	// No match afterall
	return false;
}

// Compare 2 strings for equality
function wppa_looks_equal( $s1, $s2 ) {
	if ( $s1 == $s2 ) return true;
	$s1 = str_replace( array( ' ', "\r\n", "\r", "\n" ), '', $s1 );
	$s2 = str_replace( ' ', '', $s2 );
	if ( $s1 == $s2 ) return true;
	$s1 = strtolower( $s1 );
	$s2 = strtolower( $s2 );
	if ( $s1 == $s2 ) return true;
	return false;
}

function wppa_get_all_children( $root ) {
global $wpdb;

	$result = array();
	$albs = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE a_parent = %s", $root ), ARRAY_A );
	if ( ! $albs ) return $result;
	foreach ( $albs as $alb ) {
		$result[] = $alb['id'];
		$part = wppa_get_all_children( $alb['id'] );
		if ( $part ) $result = array_merge( $result, $part );
	}
	return $result;
}

// get slide info
function wppa_get_slide_info( $index, $id, $callbackid = '' ) {
global $wpdb;
static $user;

	// Make sure $thumb contains our image data
	$thumb 	= wppa_cache_photo( $id );
	$alb 	= $thumb['album'];

	if ( ! $user ) $user = wppa_get_user( 'display' );
	$photo = wppa_get( 'photo' );
	$ratingphoto = wppa_get( 'rating-id' );

	if ( ! $callbackid ) $callbackid = $id;

	// Process a comment if given for this photo
	$comment_request = ( wppa_get( 'commentbtn' ) && ( $id == $photo ) );
	$comment_allowed = ! wppa_user_is_basic() && is_user_logged_in();
	if ( wppa_switch( 'show_comments' ) && $comment_request && $comment_allowed ) {
		wppa_do_comment( $id );
	}

	// Find rating
	if ( wppa_is_item_displayable( $alb, 'rating', 'rating_on' ) && ! wppa( 'is_slideonly' ) && ! wppa( 'is_filmonly' ) ) {

		// Find my ( avg ) rating
		if ( wppa_opt( 'rating_display_type' ) == 'likes' ) {
			$lt = wppa_get_like_title_a( $id );
			$myrat = $lt['mine'];
			$my_youngest_rating_dtm = 0;
		}
		else {
			$rats = $wpdb->get_results( $wpdb->prepare( "SELECT value, timestamp FROM $wpdb->wppa_rating
														 WHERE photo = %d
														 AND user = %s
														 AND status = 'publish'", $id, $user ), ARRAY_A );
			if ( $rats ) {
				$n = 0;
				$accu = 0;
				foreach ( $rats as $rat ) {
					$accu += $rat['value'];
					$n++;
					$my_youngest_rating_dtm = $rat['timestamp'];
				}
				$myrat = $accu / $n;
				$i = wppa_opt( 'rating_prec' );
				$j = $i + '1';
				$myrat = sprintf( '%'.$j.'.'.$i.'f', $myrat );
			}
			else {
				$myrat = '0';
				$my_youngest_rating_dtm = 0;
			}
		}

		// If no rate your own, set myrat to 'void'.
		if ( ! wppa_switch( 'allow_owner_votes' ) ) {
			if ( wppa_get_photo_item( $id, 'owner' ) == wppa_get_user() ) {
				$myrat = 'void';
			}
		}

		// If user is restricted to 'basic', set myrat to 'void'.
		if ( wppa_user_is_basic() ) {
			$myrat = 'void';
		}

		// Find the avg rating
		if ( wppa_opt( 'rating_display_type' ) == 'likes' ) {

			$avgrat = esc_js( $lt['title'] . '|' . $lt['display'] );

		}
		else {
			$avgrat = wppa_get_rating_by_id( $id, 'nolabel' );
			if ( ! $avgrat ) {
				$avgrat = '0';
			}
			$avgrat .= '|'.wppa_get_rating_count_by_id( $id );
		}

		// Find the dislike count
		$discount = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_rating
													 WHERE photo = %d
													 AND value = -1
													 AND status = 'publish'", $id ) );

		// Make the discount textual
		$distext = wppa_get_distext( $discount, $myrat );

		// Test if rating is one per period and period not expired yet
		$wait_text = esc_js( wppa_get_rating_wait_text( $id ) );
	}
	else {	// Rating off
		$myrat 		= '0';
		$avgrat 	= '0';
		$discount 	= '0';
		$distext 	= 'void';
		$wait_text 	= '';
	}

	// Find comments
	if ( wppa_is_item_displayable( $alb, 'comments', 'show_comments' ) && ! wppa( 'is_filmonly' ) && ! wppa( 'is_slideonly' ) && wppa_check_user_comment_role() ) {
		$comment = wppa_comment_html( $id, $comment_allowed );
	}
	else {
		$comment = 'void';
	}

	// Get the callback url.
	if ( wppa_switch( 'rating_on' ) ) {
		$url = wppa_get_slide_callback_url( $callbackid );
		$url = str_replace( '&amp;', '&', $url );	// js use
	}
	else {
		$url = '';
	}

	// Find link url, link title and link target
	if ( wppa_in_widget() == 'ss' ) {
		$link = wppa_get_imglnk_a( 'sswidget', $id );
	}
	else {
		$link = wppa_get_imglnk_a( 'slideshow', $id );
	}
	if ( $link ) {
		$linkurl 	= $link['url'];
		$linktitle 	= $link['title'];
		$linktarget = $link['target'];
	}
	else {
		$linkurl 	= '';
		$linktitle 	= '';
		$linktarget = '';
	}
	if ( ! $linktitle ) {
		$linktitle = esc_js( strip_tags( wppa_get_photo_item( $id, 'name' ) ) );
	}

	// Find full image style and size
	if ( wppa( 'is_filmonly' ) ) {
		$style_a['style'] = '';
		$style_a['width'] = '';
		$style_a['height'] = '';
	}
	else {
		$style_a = wppa_get_fullimgstyle_a( $id );
	}

	// Find image url
	if ( wppa_is_pdf( $id ) ) {
		$photourl = wppa_get_hires_url( $id );
		if ( wppa_is_file( str_replace( '.pdf', '.jpg', wppa_get_source_path( $id ) ) ) ) {
			$photourl = str_replace( '.pdf', '.jpg', $photourl );
		}
	}
	elseif ( wppa_switch( 'fotomoto_on' ) && ! wppa_is_stereo( $id ) ) {
		$photourl = wppa_get_hires_url( $id );
	}
	elseif ( wppa_use_thumb_file( $id, $style_a['width'], $style_a['height'] ) && ! wppa_is_stereo( $id ) ) {
		$photourl = wppa_get_thumb_url( $id, true, '', $style_a['width'], $style_a['height'] );
	}
	else {
		$photourl = wppa_get_photo_url( $id, true, '', $style_a['width'], $style_a['height'] );
	}

	// Find iptc data
	$iptc = ( wppa_switch( 'show_iptc' ) && ! wppa( 'is_slideonly' ) && ! wppa( 'is_filmonly' ) ) ? wppa_iptc_html( $id ) : '';

	// Find EXIF data
	$exif = ( wppa_switch( 'show_exif' ) && ! wppa( 'is_slideonly' ) && ! wppa( 'is_filmonly' ) ) ? wppa_exif_html( $id ) : '';

	// Lightbox subtitle
	$doit = false;
	if ( wppa_opt( 'slideshow_linktype' ) == 'lightbox' ||
		 wppa_opt( 'slideshow_linktype' ) == 'lightboxsingle' ) $doit = true;	// For fullsize
	if ( wppa_switch( 'filmstrip' ) && wppa_opt( 'film_linktype' ) == 'lightbox' ) {	// For filmstrip?
		if ( ! wppa( 'is_slideonly' ) ) $doit = true;		// Film below fullsize
		if ( wppa( 'film_on' ) ) $doit = true;				// Film explicitly on ( slideonlyf )
	}
	if ( $doit ) {
		$lbtitle = wppa_get_lbtitle( 'slide', $id );
	}
	else $lbtitle = '';

	// Name
	if ( wppa( 'is_filmonly' ) || wppa( 'is_slideonly' ) && ! ( wppa( 'in_widget' ) && wppa( 'name_on' ) ) ) {
		$name = '';
		$fullname = '';
	}
	else {
		$t 			= wppa_get_slide_name_a( $id );
		$name 		= $t['name'];
		$fullname 	= $t['fullname'];
		if ( wppa_opt( 'art_monkey_slide' ) == 'name' ) {
			$fullname = wppa_get_download_html( $id, 'nameonly', $fullname );

			// In case of art monkey button on, do not place the medal on the button, but seperately
			if ( wppa_switch( 'art_monkey_on' ) && wppa_opt( 'art_monkey_display' ) == 'button' ) {
				$fullname .= wppa_the_medal_html( $id );
			}
		}
	}

	// Shareurl
	if ( wppa( 'is_filmonly' ) || wppa( 'is_slideonly' ) || wppa( 'in_widget' ) ) {
		$shareurl = '';
	}
	else {
		$shareurl = wppa_get_image_page_url_by_id( $id, false, wppa( 'start_album' ) );
		$shareurl = wppa_convert_to_pretty( $shareurl );
		$shareurl = str_replace( '&amp;', '&', $shareurl );
	}

	// Make photo desc, filtered
	$d = wppa_get_slide_desc( $id );
	if ( $d == '&nbsp;' ) {
		$desc = '';
	}
	else {
		$desc = '<span class="sdd-' . wppa( 'mocc' ) . '" >' . $d . '</span>';
	}

	// Edit photo and other buttons
	$editlink = '';
	$dellink = '';
	$choicelink = '';
	$infolink = '';
	$downloadlink = '';
	if ( ! wppa( 'is_filmonly' ) && ! wppa( 'is_slideonly' ) ) {
		if ( wppa_may_user_fe_edit( $id ) && wppa_opt( 'upload_edit' ) != '-none-' ) {
			$editlink = '
				<input
					type="button"
					style="float:right;margin-right:6px;cursor:pointer"
					onclick="wppaStopShow( '.wppa( 'mocc' ).' );wppaEditPhoto( '.wppa( 'mocc' ).', '.esc_js('\''.wppa_encrypt_photo($thumb['id']).'\'').' );"
					value="' . esc_attr( __( wppa_opt( 'fe_edit_button' ) ) ) . '"
				/><span></span>';
		}
		if ( wppa_may_user_fe_delete( $id ) && $thumb['album'] > 0 ) {
			$dellink = '
				<input
					id="wppa-delete-' . wppa_encrypt_photo($thumb['id']) . '"
					class="wppa-delete-button"
					type="button"
					style="float:right; margin-right:6px;cursor:pointer"
					onclick="
						wppaStopShow( ' . wppa( 'mocc' ) . ' );' .
						esc_attr( 'if ( confirm( "' . __( 'Are you sure you want to remove this photo?', 'wp-photo-album-plus' ) . '" ) )
						wppaAjaxRemovePhoto( '.wppa( 'mocc' ).', '.esc_js('\''.wppa_encrypt_photo($thumb['id']).'\'').', true );' ) .
						'"
					value="' . __( 'Delete', 'wp-photo-album-plus' ) . '"
				/>';
		}
		$choice = wppa_opt( 'admins_choice' );
		if ( current_user_can( 'wppa_admin' ) || wppa_opt( 'admins_choice_action' ) != 'album' ) {
			if ( ( wppa_user_is_admin() && $choice != 'none' ) ||
				 ( is_user_logged_in() && $choice == 'login' ) ) {

				if ( wppa_is_photo_in_zip( $thumb['id'] ) ) {
					$choicelink = '
					<input
						id="admin-choice-rem-' . wppa_encrypt_photo($thumb['id']) . '-' . wppa( 'mocc' ) . '"
						type="button"
						style="float:right;margin-right:6px;text-decoration:line-through;cursor:pointer"
						onclick="
							wppaStopShow( ' . wppa( 'mocc' ) . ' );' .
							esc_attr( 'if ( confirm( "' . __( 'Are you sure you want to remove this photo from your zipfile?', 'wp-photo-album-plus' ) . '" ) )
							wppaAjaxRemovePhotoFromZip( '.wppa( 'mocc' ).', '.esc_js('\''.wppa_encrypt_photo($thumb['id']).'\'').', false ); return false;' ) . '"
						value="' . esc_attr( __( 'MyChoice', 'wp-photo-album-plus' ) ) . '"
					/>';

				}
				elseif ( wppa_is_photo( $thumb['id'] ) ) {
					$choicelink = '
					<input
						id="admin-choice-' . wppa_encrypt_photo($thumb['id']) . '-' . wppa( 'mocc' ) . '"
						type="button"
						style="float:right;margin-right:6px;cursor:pointer"
						onclick="
							wppaStopShow( ' . wppa( 'mocc' ) . ' );' .
							esc_attr( 'if ( confirm( "' . __( 'Are you sure you want to add this photo to your selection?' ,'wp-photo-album-plus' ) . '" ) )
							wppaAjaxAddPhotoToZip( '.wppa( 'mocc' ).', '.esc_js('\''.wppa_encrypt_photo($thumb['id']).'\'').', false ); return false;' ) . '"
						value="' . esc_attr( __( 'MyChoice', 'wp-photo-album-plus' ) ) . '"
					/>';
				}
				else {
					$choicelink = '';
				}
			}
		}
		if ( wppa_switch( 'request_info' ) && is_user_logged_in() ) {
			$infolink = '
			<input
				id="request-info-' . wppa_encrypt_photo( $id ) . '-' . wppa( 'mocc' ) . '"
				type="button"
				style="float:right;margin-right:6px;cursor:pointer"
				onclick="
					wppaStopShow( ' . wppa( 'mocc' ) . ' );' .
					esc_attr( 'if ( confirm( "' . __( 'Are you sure you want to ask for info on this photo?' ,'wp-photo-album-plus' ) . '" ) )
					wppaAjaxRequestInfo( '.wppa( 'mocc' ).', '.esc_js('\''.wppa_encrypt_photo( $id ).'\'').', false ); return false;' ) . '"
				value="' . esc_attr( __( 'Request info', 'wp-photo-album-plus' ) ) . '"
			/>';
		}
		if ( wppa_opt( 'art_monkey_slide' ) == 'desc' ) {
			$downloadlink = wppa_get_download_html( $id, 'slidedescription' );
		}
	}
	if ( $editlink || $dellink || $choicelink || $infolink || $downloadlink ) {
		$desc = $editlink.$dellink.$choicelink.$infolink.$downloadlink.'<div style="clear:both"></div>'.$desc;
	}

	if ( in_array( $thumb['status'], array( 'pending', 'scheduled' ) ) ) {
		$desc .= wppa_html( esc_js( wppa_moderate_links( 'slide', $id ) ) );
	}

	// Share HTML
	$sharehtml = ( wppa( 'is_filmonly' ) || wppa( 'is_slideonly' ) ) ? '' : wppa_get_share_html( $id );

	// Og Description
	$ogdsc = '';
	if ( wppa_switch( 'facebook_comments' ) && ! wppa_in_widget() && ! wppa( 'is_filmonly' ) && ! wppa( 'is_slideonly' ) ) {
		$ogdsc = strip_shortcodes( wppa_strip_tags( wppa_html( wppa_get_photo_desc( $id ) ), 'all' ) );
		$ogdsc = esc_js( $ogdsc );
	}

	// Hires url. Use photo url in case of stereo image. The (source) hires is the double image.
	// Use the real file in case of pdf and not mobile
	if ( wppa_is_pdf( $id ) ) { // && ! wppa_is_mobile() ) {
		$hiresurl = esc_js( wppa_get_photo_url( $id ) );
	}
	elseif ( wppa_is_stereo( $id ) ) {
		$hiresurl = esc_js( wppa_get_photo_url( $id ) );
	}
	else {
		$hiresurl = esc_js( wppa_fix_poster_ext( wppa_get_hires_url( $id ), $id ) );
	}

	// Video html
	$videohtml = wppa_get_video_body( $id );

	// Audio html
	$audiohtml = wppa_get_audio_body( $id );

	// Image alt
	$image_alt = esc_js( wppa_get_imgalt( $id, true ) );

	// Poster url if video
	$poster_url = '';
	if ( wppa_is_video( $id ) ) {
		if ( is_file( wppa_get_photo_path( $id ) ) ) {
			$poster_url = wppa_get_photo_url( $id );
		}
	}

	// Filename
	$filename = esc_js( wppa_get_photo_item( $id, 'filename' ) );

	// Panorama HTML
	$panoramahtml = '';
	if ( wppa_get_photo_item( $id, 'panorama' ) != '0' ) {
		/*
		$args = ['id' 				=> $id,				// non default
				 'mocc' 			=> wppa('mocc'),	// non default
				 'width' 			=> false,
				 'height' 			=> false,
				 'haslink' 			=> false,
				 'lightbox' 		=> 0,
				 'controls' 		=> false, 			// non default
				 'autorun' 			=> '',
				 'manual' 			=> true,
				 'autorunspeed' 	=> '3',
				 'zoomsensitivity' 	=> '3',
				];
		$panoramahtml = wppa_get_spheric_pan_html( $args );
		*/

		$args = ['id' => $id,
				 'controls' => ( wppa_get_pan_control_height() > 0 ),
				 'slide' 	=> true,
				];
		$panoramahtml = wppa_get_panorama_html( $args );
	}
	elseif ( wppa_is_zoomable( $id ) ) {
		/*
		$args = ['id' 				=> '0',
				 'mocc' 			=> '0',
				 'width' 			=> false,
				 'height' 			=> false,
				 'haslink' 			=> false,
				 'lightbox' 		=> 0,
				 'controls' 		=> true,
				 'autorun' 			=> '',
				 'manual' 			=> true,
				 'autorunspeed' 	=> '3',
				 'zoomsensitivity' 	=> '3',
				];
		*/
		$args = ['id' => $id,
				 'controls' => ( wppa_get_pan_control_height() > 0 ),
				 'slide' 	=> true,
				];
		$panoramahtml = wppa_get_zoom_pan_html( $args );
	}

	// Height for optional controls under pan/zoom imagesavealpha
	$pancontrolheight = wppa_get_pan_control_height();

	// Timeout
	if ( wppa_get( 'timeout' ) ) {
		$timeout = wppa_get( 'timeout' );
	}
	elseif ( wppa( 'timeout' ) ) {
		$timeout = wppa( 'timeout' );
	}
	else {
		$timeout = wppa_opt( 'slideshow_timeout' );
	}

	// Photo aspect ratio
	if ( $thumb['panorama'] ) {
		$ratio = 2;
	}
	elseif ( $thumb['photoy'] ) { 	// No divide by zero please
		$ratio = $thumb['photox'] / $thumb['photoy'];
	}
	else {
		$ratio = 1;
	}

	// Produce final result
    $result = "'".wppa( 'mocc' )."','";
	$result .= $index."','";
	$result .= $photourl."','";
	$result .= $style_a['style']."','";
	$result .= ( $videohtml ? wppa_get_videox( $id ) : $style_a['width'] )."','";
	$result .= ( $videohtml ? wppa_get_videoy( $id ) : $style_a['height'] )."','";
	$result .= $fullname."','";
	$result .= $name."','";
	$result .= $desc."','";
	$result .= wppa_encrypt_photo( $id )."','";
	$result .= $id."','";
	$result .= $avgrat."','";
	$result .= $distext."','";
	$result .= $myrat."','";
	$result .= $url."','";
	$result .= $linkurl."','";
	$result .= $linktitle."','";
	$result .= $linktarget."','";
	$result .= $timeout."','";
	$result .= $comment."','";
	$result .= $iptc."','";
	$result .= $exif."','";
	$result .= $lbtitle."','";
	$result .= $shareurl."','";	// Used for history.pushstate()
	$result .= $sharehtml."','";	// The content of the SM ( share ) box
	$result .= $ogdsc."','";
	$result .= $hiresurl."','";
	$result .= $videohtml."','";
	$result .= $audiohtml."','";
	$result .= $wait_text."','";
	$result .= $image_alt."','";
	$result .= $poster_url."','";
	$result .= $filename."','";
	$result .= $panoramahtml."','";
	$result .= $pancontrolheight."','";
	$result .= $ratio."',";

	// Remove excessive spaces and make sure there are no linebreaks in the result that would screw up Javascript.
	$result = wppa_compress_html( $result );
	$result = str_replace( ["\r\n", "\n"], [" ", " "], $result );

	return $result;
}

function wppa_get_slide_desc( $id ) {

	$alb = wppa_get_photo_item( $id, 'album' );
	$desc = '';

	if ( ( ! wppa( 'is_slideonly' ) || wppa_is_item_displayable( $alb, 'description', 'show_full_desc' ) ) && ! wppa( 'is_filmonly' ) ) {

		$desc .= wppa_get_photo_desc( $id, array( 'doshortcodes' => wppa_switch( 'allow_foreign_shortcodes' ), 'dogeo' => true ) );	// Foreign shortcodes is handled here

		// Run wpautop on description?
		if ( wppa_opt( 'wpautop_on_desc' ) == 'wpautop' ) {
			$desc = wpautop( $desc );
		}
		elseif ( wppa_opt( 'wpautop_on_desc' ) == 'nl2br' ) {
			$desc = nl2br( $desc );
		}

		// And format
		$desc = wppa_html( esc_js( stripslashes( $desc ) ) );

		// Remove extra space created by other filters like wpautop
		if ( wppa_switch( 'allow_foreign_shortcodes' ) && wppa_switch( 'clean_pbr' ) ) {
			$desc = str_replace( array( "<p>", "</p>", "<br>", "<br/>", "<br>" ), " ", $desc );
		}
	}

	if ( ! $desc ) $desc = '&nbsp;';

	return $desc;
}

function wppa_get_slide_name_a( $id ) {

	$name 		= '';
	$fullname 	= '';
	$alb 		= wppa_get_photo_item( $id, 'album' );
	$disp 		= wppa_is_item_displayable( $alb, 'name', 'show_full_name' );
	$addmedal 	= true;
	if ( wppa_switch( 'art_monkey_on' ) && wppa_opt( 'art_monkey_display' ) == 'button' ) $addmedal = false;

	if ( $disp && ( ! wppa( 'is_slideonly' ) || wppa( 'name_on' ) ) && ! wppa( 'is_filmonly' ) ) {
		$name = esc_js( wppa_get_photo_name( $id ) );
		$fullname = wppa_get_photo_name( $id, array( 	'addowner' 	=> wppa_switch( 'show_full_owner' ),
														'addmedal' 	=> $addmedal,
														'escjs' 	=> true,
														'showname' 	=> true,
														'nobpdomain' => wppa_switch( 'art_monkey_on' ),
													) );
	}

	return array( 'name' => $name, 'fullname' => $fullname );
}

function wppa_get_distext( $discount, $myrat ) {

	if ( wppa_switch( 'dislike_show_count' ) ) {
		$distext = $discount ? esc_js( sprintf( _n( '%d dislike', '%d dislikes', $discount, 'wp-photo-album-plus' ), $discount ) ) : '';
		if ( $myrat < '0' ) {
			$distext .= ' ' . esc_js( __( 'including mine', 'wp-photo-album-plus' ) );
		}
	}
	else {
		$distext = '';
	}
	return $distext;
}

// Process a comment request
function wppa_do_comment( $id ) {
global $wpdb;
global $wppa_done;

	// Been here before?
	if ( $wppa_done ) return; // Prevent multiple
	$wppa_done = true;

	// Remember start time
	$time = time();

	// Find photo id
	$photo = wppa_get( 'photoid', '0' );

	// No photo, give up
	if ( ! $photo ) die( 'Photo id missing while processing a comment' );

	// Find commenter name
	$user = wppa_get( 'comname', 'Anonymus' );
	$user = htmlspecialchars( $user );

	// No user? give up
	if ( ! $user ) die( 'Illegal attempt to enter a comment 1' );

	// Find email address
	$email = wppa_get( 'comemail' );

	// No email see if required
	if ( ! $email ) {

		// Missing but required?
		if ( wppa_opt( 'comment_email_required' ) == 'required' ) {
			die( 'Illegal attempt to enter a comment 2' );
		}

		// If email no email and not required, use his IP
		else {
			$email = wppa_get_user();
		}
	}

	// Retrieve and filter comment
	$comment = wppa_get( 'comment', '', 'textarea' );
	$comment = trim( $comment );
	$comment = wppa_decode( $comment );
//	$comment = nl2br( $comment );
	if ( ! current_user_can( 'unfiltered_html' ) ) {
		$comment = strip_tags( $comment );
	}
	else {
		$comment = balanceTags( $comment );
	}

	$policy = wppa_opt( 'moderate_comment' );
	switch ( $policy ) {
		case 'all':
			$status = 'pending';
			break;
		case 'logout':
			$status = is_user_logged_in() ? 'approved' : 'pending';
			break;
		case '-none-':
			$status = 'approved';
			break;
		case 'wprules':
			$status = wppa_check_comment( $user, $email, $comment );
			break;
		default:
			$status = 'approved';
			break;
	}
	if ( current_user_can( 'wppa_moderate' ) ) $status = 'approved';	// Need not moderate comments issued by moderator

	// If 'comment needs vote' is on, check if the user has rated this photo,
	// if not: set status to 'pending' and display an alertbox indicating this.
	// Exception: The user can add comments to his own photos (e.g. as a reply)without the need of a rating.
	if ( wppa_switch( 'comment_need_vote' ) ) {

		if ( wppa_get_user() != wppa_get_photo_item( $photo, 'owner' ) ) {

			$bret = wppa_has_user_rated( $photo );
			if ( $bret ) {
				$status = 'approved';
			}
			else {
				wppa( 'comneedsvote', true );
				$status = 'pending';
			}
		}
	}

	// Editing a comment?
	$cedit = wppa_get( 'comid', '0' );

	// If editing, check if this is the most recent comment of this photo. (Ref security error as reported on 2-23-12-04)
	$old_entry = $wpdb->prepare( "SELECT id FROM $wpdb->wppa_comments
								  WHERE photo = %d
								  AND user = %s
								  ORDER BY timestamp DESC
								  LIMIT 1", $photo, $user );

	$old_entry_id = $wpdb->get_var( $old_entry );

	if ( $cedit && $old_entry_id != $cedit ) {
		wppa_echo( __( 'Security check failure', 'wp-photo-album-plus' ) . ' 811' );
		wppa_exit();
	}

	// Check captcha
	$wrong_captcha = false;
	if ( ( is_user_logged_in() && wppa_opt( 'comment_captcha' ) == 'all' ) ||
		 ( ! is_user_logged_in() && wppa_opt( 'comment_captcha' ) != 'none' ) )	{
		$captkey = $id;
		if ( $cedit ) $captkey = $wpdb->get_var( $wpdb->prepare( "SELECT timestamp FROM $wpdb->wppa_comments WHERE id = %d", $cedit ) );
		if ( ! wppa_check_captcha( $captkey ) ) {
			$status = 'spam';
			$wrong_captcha = true;
		}
	}

	// Process ( edited ) comment
	if ( $comment ) {
		if ( $cedit ) {

			$iret = wppa_update_comment( $cedit, ['comment' => $comment] );
			if ( $iret === false ) {
				wppa_log('err', 'Could not update comment '.$cedit);
				wppa_alert( __( 'Could not process comment update.', 'wp-photo-album-plus' ) );
				wppa_exit();
			}

			wppa( 'comment_id', $cedit );
		}
		else {

			// See if a refresh happened
			$old_entry = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_comments
										  WHERE photo = %d
										  AND user = %s
										  AND comment = %s
										  ORDER BY timestamp DESC
										  LIMIT 1", $photo, $user, $comment );

			$iret = $wpdb->query( $old_entry );

			if ( $iret ) {
				// Duplicate comment ignored
				return;
			}
			$key = wppa_create_comments_entry( array( 'photo' => $photo, 'user' => $user, 'email' => $email, 'comment' => $comment, 'status' => $status ) );
			if ( $key ) {
				wppa( 'comment_id', $key );

				if ( $policy != 'wprules' ) {
					switch( $status ) {
						case 'pending':
							wppa_log( 'Com', 'Comment {i}' . $comment . '{/i} held for moderation' );
							break;
						case 'spam':
							wppa_log( 'Com', 'Comment {i}' . $comment . '{/i} marked as spam' );
							break;
						case 'approved':
							wppa_log( 'Com', 'Comment {i}' . $comment . '{/i} added with status approved' );
							break;
						default:
							wppa_log( 'Err', 'Comment {i}' . $comment . '{/i} added with status ' . $status );
							break;
					}
				}
			}
			else {
				wppa_alert( __( 'Could not process comment.', 'wp-photo-album-plus' ) );
				wppa_exit();
			}
		}


		if ( $status == 'spam' ) {
			if ( $wrong_captcha ) {
				wppa_alert( __( 'Sorry, you gave a wrong answer.\n\nPlease try again to solve the computation.', 'wp-photo-album-plus' ) );
			}
			else {
				wppa_alert( __( 'Sorry, your comment is not accepted.', 'wp-photo-album-plus' ) );
			}
		}
		else {

			if ( $cedit ) {
				if ( wppa_switch( 'commentnotify_added' ) ) {
					wppa_alert( __( 'Comment updated', 'wp-photo-album-plus' ) );
				}
			}
			else {

				// SUCCESSFUL COMMENT, ADD POINTS to the commenter, if he is not the owner
				$photo_owner = wppa_get_photo_item( $photo, 'owner' );

				if ( $photo_owner != wppa_get_user() ) {

					wppa_add_credit_points( wppa_opt( 'cp_points_comment' ),
											__( 'Photo comment', 'wp-photo-album-plus' ),
											$photo
											);
				}

				// Add points to the owner, if no moderation
				if ( $status == 'approved' ) {
					wppa_add_credit_points( wppa_opt( 'cp_points_comment_appr' ),
											__( 'Photo comment approved', 'wp-photo-album-plus' ),
											$photo,
											'',
											$photo_owner
											);
				}

				// SEND EMAILS
				if ( $status  == 'pending' ) {
					wppa_schedule_mailinglist( 'moderatecomment', 0, $photo, $key, wppa_get( 'returnurl' ) );
				}
				if ( $status == 'approved' ) {
					wppa_schedule_mailinglist( 'commentnotify', 0, $photo, $key, wppa_get( 'returnurl' ) );
				}

				// Process any pending votes of this user for this photo if rating needs comment, do it anyway, feature may have been on but now off
				$iret = $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->wppa_rating
													   SET status = 'publish'
													   WHERE photo = %d AND user = %s", $id, wppa_get_user( 'display' ) ) );

				if ( $iret ) wppa_rate_photo( $id );	// Recalc ratings for this photo

				// Notify user
				if ( wppa_switch( 'commentnotify_added' ) ) {
					wppa_alert( __( 'Comment added', 'wp-photo-album-plus' ) );
				}
			}
		}

		wppa( 'comment_photo', $id );
		wppa( 'comment_text', $comment );

		// Clear associated caches
		wppa_clear_cache( array( 'photo' => $id, 'other' => 'C' ) );
	}
}

// Create a captcha
function wppa_make_captcha( $id ) {
	$capt = wppa_ll_captcha( $id );
	return $capt['text'];
}

// Check the comment security answer
function wppa_check_captcha( $id ) {
	$answer = wppa_get( 'captcha' );
	$capt = wppa_ll_captcha( $id );
	return $capt['ans'] == $answer;
}

// Low level captcha routine
function wppa_ll_captcha( $id ) {
	$nonce = wp_create_nonce( 'wppa_photo_comment_'.$id );
	$result['val1'] = 1 + intval( substr( $nonce, 0, 4 ), 16 ) % 12;
	$result['val2'] = 1 + intval( substr( $nonce, -4 ), 16 ) % 12;
	if ( $result['val1'] == $result['val1'] ) $result['val2'] = 1 + intval( substr( $nonce, -5, 4 ), 16 ) % 12;
	if ( $result['val1'] != 1 && $result['val2'] != 1 && $result['val1'] * $result['val2'] < 21 ) {
		$result['oper'] = 'x';
		$result['ans'] = $result['val1'] * $result['val2'];
	}
	elseif ( $result['val1'] > ( $result['val2'] + 1 ) ) {
		$result['oper'] = '-';
		$result['ans'] = $result['val1'] - $result['val2'];
	}
	else {
		$result['oper'] = '+';
		$result['ans'] = $result['val1'] + $result['val2'];
	}
	$result['text'] = sprintf( '%d %s %d = ', $result['val1'], $result['oper'], $result['val2'] );
	return $result;
}

function wppa_get_imgevents( $type = '', $id = '', $no_popup = false, $idx = '' ) {
global $wpdb;

	$result = '';
	$perc = '';
	if ( $type == 'thumb' || $type == 'film' ) {
		if ( wppa_switch( 'use_thumb_opacity' ) || wppa_use_thumb_popup() ) {

			if ( wppa_switch( 'use_thumb_opacity' ) ) {
				$perc = wppa_opt( 'thumb_opacity' );
				$result = ' onmouseout="jQuery( this ).fadeTo( 400, ' . $perc/100 . ' )" onmouseover="jQuery( this ).fadeTo( 400, 1.0 );';
			} else {
				$result = ' onmouseover="';
			}

			if ( $type == 'film' && wppa_switch( 'film_hover_goto' ) ) {
				$result .= 'wppaGotoFilmNoMove( '.wppa( 'mocc' ).', '.$idx.' );';
			}

			if ( ! $no_popup && wppa_use_thumb_popup() ) {
				if ( true ) { //wppa_opt( 'thumb_linktype' ) != 'lightbox' ) {

					$name = wppa_switch( 'popup_text_name' ) || wppa_switch( 'popup_text_owner' ) ?
								wppa_get_photo_name( $id, array( 'addowner' => wppa_switch( 'popup_text_owner' ), 'showname' => wppa_switch( 'popup_text_name' ) ) ) :
								'';
					$name = esc_js( $name );

					$desc = wppa_switch( 'popup_text_desc' ) ? wppa_get_photo_desc( $id ) : '';
					if ( wppa_switch( 'popup_text_desc_strip' ) ) $desc = wppa_strip_tags( $desc );

					// Run wpautop on description?
					if ( wppa_opt( 'wpautop_on_thumb_desc' ) == 'wpautop' ) {
						$desc = wpautop( $desc );
					}
					elseif ( wppa_opt( 'wpautop_on_thumb_desc' ) == 'nl2br' ) {
						$desc = nl2br( $desc );
					}

					$desc = esc_js( $desc );

					$rating = wppa_switch( 'popup_text_rating' ) ? wppa_get_rating_by_id( $id ) : '';
					if ( $rating && wppa_switch( 'show_rating_count' ) ) $rating .= ' ( '.wppa_get_rating_count_by_id( $id ).' )';
					$rating = esc_js( $rating );

					if ( wppa_switch( 'popup_text_ncomments' ) ) {
						$ncom = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_comments WHERE photo = %d AND status = 'approved'", $id ) );
					}
					else $ncom = '0';
					if ( $ncom ) {
						$ncom = sprintf( _n( '%d comment', '%d comments', $ncom, 'wp-photo-album-plus' ), $ncom );
					}
					else $ncom = '';
					$ncom = esc_js( $ncom );

					$x = wppa_get_imagex( $id, 'thumb' );
					$y = wppa_get_imagey( $id, 'thumb' );

					if ( $x > $y ) {
						$w = wppa_opt( 'popupsize' );
						if ( $x ) {
							$h = round( $w * $y / $x );
						}
						else {
							$h = $w;
						}
					}
					else {
						$h = wppa_opt( 'popupsize' );
						if ( $y ) {
							$w = round( $h * $x / $y );
						}
						else {
							$w = $h;
						}
					}

					if ( wppa_is_video( $id ) ) {
						$video_args = array(
												'id'			=> $id,
												'controls' 		=> false,
												'tagid' 		=> 'wppa-img-'.wppa( 'mocc' ),
												'width' 		=> $w,
												'height' 		=> $h
											 );
						if ( wppa_opt( 'thumb_linktype' ) == 'fullpopup' ) {
							$video_args['events'] = 'onclick="alert( \''.esc_attr( __( 'A video can not be printed', 'wp-photo-album-plus' ) ).'\' );"';
						}
						$videohtml = wppa_get_video_html( $video_args );
					}
					else {
						$videohtml = '';
					}

					$alb = wppa_get_photo_item( $id, 'album' );
					$result .= 'if (wppaPopUp) wppaPopUp( ' .
						wppa( 'mocc' ) .
						', this, ' .
						'\''.wppa_encrypt_photo($id) .'\''.
						', \'' .
						( wppa_is_item_displayable( $alb, 'name', 'popup_text_name' ) ? $name : '' ) .
						'\', \'' .
						( wppa_is_item_displayable( $alb, 'description', 'popup_text_desc' ) ? $desc : '' ) .
						'\', \'' .
						( wppa_is_item_displayable( $alb, 'name', 'popup_text_rating' ) ? $rating : '' ) .
						'\', \'' .
						( wppa_is_item_displayable( $alb, 'name', 'popup_text_ncomments' ) ? $ncom : '' ) .
						'\', \'' .
						esc_js( $videohtml ) .
						'\', \'' .
						$w .
						'\', \'' .
						$h .
						'\' );" ';
				}
				else {
					// Popup and lightbox on thumbs are incompatible. skip popup.
					$result .= '" ';
				}
			}
			else $result .= '" ';
		}
	}
	elseif ( $type == 'cover' ) {
		if ( wppa_switch( 'use_cover_opacity' ) ) {
			$perc = wppa_opt( 'cover_opacity' );
			$result = ' onmouseover="jQuery( this ).fadeTo( 400, 1.0 )" onmouseout="jQuery( this ).fadeTo( 400, ' . $perc/100 . ' )" ';
		}
	}
	return $result;
}

function wppa_onpage( $type, $counter, $curpage ) {

	// Pagination is off during search
	if ( is_search() ) {
		return true;
	}

	$pagesize = wppa_get_pagesize( $type );
	if ( $pagesize == '0' ) {			// Pagination off
		if ( $curpage == '1' ) return true;
		else return false;
	}
	$cnt = $counter - 1;
	$crp = $curpage - 1;
	if ( floor( $cnt / $pagesize ) == $crp ) return true;
	return false;
}

function wppa_get_pagesize( $type = '' ) {

	// Pagination is off during search
	if ( wppa( 'src' ) ) {
		return '0';
	}

	// Pagination is off when photo enumeration
	if ( wppa( 'start_photos' ) ) {
		return '0';
	}

	if ( $type == 'albums' ) return wppa_opt( 'album_page_size' );
	if ( $type == 'thumbs' ) return wppa_opt( 'thumb_page_size' );
	return '0';
}

function wppa_deep_stristr( $string, $tokens ) {
global $wppa_stree;
	$string = stripslashes( $string );
	$tokens = stripslashes( $tokens );
	// Explode tokens into search tree
	if ( !isset( $wppa_stree ) ) {
		// sanitize search token string
		$tokens = trim( $tokens );
		while ( strstr( $tokens, ', ' ) ) $tokens = str_replace( ', ', ',', $tokens );
		while ( strstr( $tokens, ' ,' ) ) $tokens = str_replace( ' ,', ',', $tokens );
		while ( strstr( $tokens, '  ' ) ) $tokens = str_replace( '  ', ' ', $tokens );
		while ( strstr( $tokens, ',,' ) ) $tokens = str_replace( ',,', ',', $tokens );
		// to level explode
		if ( strstr( $tokens, ',' ) ) {
			$wppa_stree = explode( ',', $tokens );
		}
		else {
			$wppa_stree[0] = $tokens;
		}
		// bottom level explode
		for ( $idx = 0; $idx < count( $wppa_stree ); $idx++ ) {
			if ( strstr( $wppa_stree[$idx], ' ' ) ) {
				$wppa_stree[$idx] = explode( ' ', $wppa_stree[$idx] );
			}
		}
	}
	// Check the search criteria
	foreach ( $wppa_stree as $branch ) {
		if ( is_array( $branch ) ) {
			if ( wppa_and_stristr( $string, $branch ) ) return true;
		}
		else {
			if ( stristr( $string, $branch ) ) return true;
		}
	}
	return false;
}

function wppa_and_stristr( $string, $branch ) {
	foreach ( $branch as $leaf ) {
		if ( !stristr( $string, $leaf ) ) return false;
	}
	return true;
}

function wppa_get_thumb_frame_style( $glue = false, $film = '' ) {
	$temp = wppa_get_thumb_frame_style_a( $glue, $film );
	$result = $temp['style'];
	return $result;
}

function wppa_get_thumb_frame_style_a( $glue = false, $film = '' ) {
static $wppaerrmsgxxx;
global $wppa;

	// Init
	if ( isset( $wppa['current_album'] ) && wppa( 'current_album' ) > '0' ) {
		$album = wppa_cache_album( wppa( 'current_album' ) );
	}
	else {
		$album = false;
	}

	$result = array( 'style'=> '', 'width' => '', 'height' => '' );

	// Comten alt display?
	$com_alt = wppa( 'is_comten' ) && wppa_switch( 'comten_alt_display' ) && ! wppa_in_widget() && ! $film;

	// Film, normal or alt?
	if ( $film ) {
		$tfw = wppa_opt( 'film_thumbsize' );
		$tfh = $tfw;
		if ( wppa_opt( 'film_type' ) == 'canvas' ) {
			$tfh = floor( $tfw / wppa_opt( 'film_aspect' ) );
		}
	}
	else {
		$alt = is_array( $album ) && $album['alt_thumbsize'] == 'yes' ? '_alt' : '';
		$tfw = wppa_opt( 'tf_width'.$alt );
		$tfh = wppa_opt( 'tf_height'.$alt );
	}

	// Margin
	$mgl = wppa_opt( 'tn_margin' );

	// Film in widget
	if ( $film && wppa_in_widget() ) {
		$tfw /= 2;
		$tfh /= 2;
		$mgl /= 2;
	}

	// Half margin
	$mgl2 = floor( $mgl / '2' );

	if ( ! $film && wppa_switch( 'thumb_auto' ) ) {
		$area = wppa_get_box_width() + $tfw;	// Area for n+1 thumbs
		$n_1 = floor( $area / ( $tfw + $mgl ) );
		if ( $n_1 == '0' ) {
			if ( ! $wppaerrmsgxxx ) wppa_out( 'Misconfig. thumbnail area too small. Areasize = '.wppa_get_box_width().' tfwidth = '.$tfw.' marg= '.$mgl );
			$n_1 = '1';
			$wppaerrmsgxxx = true;	// err msg given
		}
		$mgl = floor( $area / $n_1 ) - $tfw;
	}

	if ( is_numeric( $tfw ) && is_numeric( $tfh ) ) {
		$result['style'] = 'width: '.$tfw.'px; height: '.$tfh.'px; margin-left: '.$mgl.'px; margin-top: '.$mgl2.'px; margin-bottom: '.$mgl2.'px;';
		if ( $glue && wppa_switch( 'film_show_glue' ) && wppa_switch( 'slide_wrap' ) ) {
			$result['style'] .= 'padding-right:'.$mgl.'px; border-right: 2px dotted gray;';
		}
		$result['width'] = $tfw;
		$result['height'] = $tfh;
	}
	else $result['style'] = '';

	// Alt comment?
	if ( $com_alt ) {
		$w = wppa_get_container_width();
		if ( $w <= 1.0 ) {
			$w = $w * wppa_opt( 'initial_colwidth' );
		}
		$result['style'] = 'width: '.$w.'px; margin-left: 4px; margin-top: 2px; margin-bottom: 2px;';
	}

	return $result;
}

function wppa_get_container_width( $netto = false ) {
global $wppa_preview_container_width;

	// If explicitly asked by an ajax call, get It
	if ( ! $netto && defined( 'DOING_AJAX' ) ) {
		$m = wppa_get( 'occur', 0, 'int' );
		$w = wppa_get( 'cw', 0, 'int' );
		if ( wppa( 'mocc' ) == $m && $w ) {
			return $w;
		}
	}

	if ( $wppa_preview_container_width ) {
		$result = $wppa_preview_container_width;
	}
	elseif ( wppa( 'in_widget' ) ) {
		$result = wppa_opt( 'widget_width' );
	}
	elseif ( is_numeric( wppa( 'fullsize' ) ) && wppa( 'fullsize' ) > '0' ) {
		$result = wppa( 'fullsize' );
	}
	else {
		if ( wppa( 'max_width' ) ) {
			$result = wppa( 'max_width' );
		}
		else {
			$result = wppa_opt( 'initial_colwidth' ); //'640';
		}
		wppa( 'auto_colwidth', true );
	}
	if ( $netto ) {
		$result -= 12; // 2*padding
		$result -= 2 * ( wppa_opt( 'bwidth' ) ? wppa_opt( 'bwidth' ) : '0' );
	}
	return $result;
}

function wppa_get_thumbnail_area_width() {

	$result = wppa_get_container_width();
	$result -= wppa_get_thumbnail_area_delta();
	return $result;
}

function wppa_get_thumbnail_area_delta() {

	$result = 12 + 2 * ( wppa_opt( 'bwidth' ) ? wppa_opt( 'bwidth' ) : 0 );
	return $result;
}

function wppa_get_container_wrapper_style() {

	$result = '';

	// In widget?
	if ( wppa( 'in_widget' ) ) {
		$result = 'width:100%; padding:0;';
		return $result;
	}

	// Margin
	$marg = false;
	if ( is_numeric( wppa( 'fullsize' ) ) ) {
		$cw = wppa_opt( 'initial_colwidth' );
		if ( is_numeric( $cw ) ) {
			if ( $cw > ( wppa( 'fullsize' ) + 10 ) ) {
				$marg = '10px;';
			}
		}
	}

	// Clearance
	if ( ! wppa_in_widget() ) {
		if ( wppa( 'align' ) == 'left' ) {
			$result .= 'clear:left; ';
		}
		elseif ( wppa( 'align' ) == 'right' ) {
			$result .= 'clear:right; ';
		}
		else {
//			$result .= 'clear:both; ';
		}
	}

	// Width
	$ctw = wppa_get_container_width();

	// Responsive fraction
	if ( $ctw <= '1' ) {
		if ( ! $ctw ) {
			$ctw = '1';
		}
		$result .= 'max-width:' . ( $ctw * 100 ) . '%;';
	}
	// Responsive full width
	elseif ( wppa( 'auto_colwidth' ) ) {
		$result .= 'width:100%;clear:both;';

		// Responsive with maximum
		if ( wppa( 'max_width' ) ) {
			$result .= 'max-width:' . wppa( 'max_width' ) . 'px;';
		}
	}
	// Static
	else {
		$result .= 'width:' . $ctw . 'px;';
	}

	// Alignment
	if ( wppa( 'align' ) == 'left' ) {
		$result .= 'float:left;';
		if ( $marg ) $result .= 'margin-right:'.$marg;
	}
	elseif ( wppa( 'align' ) == 'center' ) $result .= 'display:block;margin-left:auto;margin-right:auto;';
	elseif ( wppa( 'align' ) == 'right' ) {
		$result .= 'float: right;';
		if ( $marg ) $result .= 'margin-left:'.$marg;
	}

	// Padding
	$result .= 'padding:0;';

	// Position
	$result .= 'position:relative;';

	return $result;
}

function wppa_get_curpage() {

	// If current occ is qstring occ
	if ( wppa( 'mocc' ) == wppa_get( 'occur', '1' ) ) {

		// page may be in qstring
		$curpage = wppa_get( 'paged', '1' );
	}
	else {

		// Page is 1
		$curpage = '1';
	}

	return $curpage;
}

function wppa_container( $action ) {
global $wppa_version;
static $auto;
global $blog_id;

	// Need no container in RSS feeds
	if ( is_feed() ) return;

	$mocc  		= wppa( 'mocc' );
	$revno 		= wppa( 'revno' );
	$prevrev 	= wppa_opt( 'prevrev' );
	$api 		= wppa( 'api_version' );

	// Open request?
	if ( $action == 'open' ) {

		// Open the container
		if ( ! defined( 'DOING_WPPA_AJAX' ) ) {

			// A modal container
			wppa_out( '
				<!-- Start container ' . $mocc . ' -->
				<div
					id="wppa-modal-container-' . $mocc . '"
					class="wppa-modal-container"
					style="position:relative;z-index:100000;"
					data-wppa="yes"
					>
				</div>'
			);

			// add wrapper
			wppa_container_wrapper( 'open' );

			wppa_out( '
				<div
					id="wppa-container-' . $mocc . '"
					style="width:100%;"
					class="wppa-container wppa-container-' . $mocc . '
						   wppa-rev-' . $revno . '
						   wppa-prevrev-' . $prevrev . '
						   wppa-theme-' . $wppa_version . '
						   wppa-api-' . $api . '"
					>' );
		}

		// Spinner for Ajax
		if ( ! wppa_in_widget() ) {

			wppa_out( wppa_get_spinner_svg_html( ['id' 	=> 'wppa-ajax-spin-' . $mocc, 'class' => 'wppa-ajax-spin'] ) );
		}

		// Nonce field check for rating security
		if ( wppa( 'mocc' ) == '1' ) {
			if ( wppa_get( 'rating' ) ) {
				$nonce = wppa_get( 'nonce' );
				$ok = wp_verify_nonce( $nonce, 'wppa-check' );
				if ( ! $ok ) {
					wp_die( '<b>' . __( 'ERROR: Illegal attempt to enter a rating.', 'wp-photo-album-plus' ) . '</b>' );
				}
			}
		}

		// Nonce field check for comment security
		if ( wppa( 'mocc' ) == '1' ) {
			if ( wppa_get( 'comment' ) ) {
				$nonce = wppa_get( 'nonce' );
				$ok = wp_verify_nonce( $nonce, 'wppa-check' );
				if ( ! $ok ) {
					wp_die( '<b>' . __( 'ERROR: Illegal attempt to enter a comment.', 'wp-photo-album-plus' ) . '</b>' );
				}
			}
		}

		wppa_out( wppa_nonce_field( 'wppa-check', 'wppa-nonce', false ) );

		if ( wppa_page( 'oneofone' ) ) wppa( 'portrait_only', true );

		// Javascript occurrence dependant stuff
		$the_js = '/* START OCCURRANCE ' . wppa( 'mocc' ) . ' */;';
			// wppa( 'auto_colwidth' ) is set by the filter or by wppa_albums in case called directly
			// script or call has precedence over option setting
			// so: if set by script or call: auto, else if set by option: auto
			$auto = false;
			$contw = wppa_get_container_width();
			if ( wppa( 'auto_colwidth' ) ) $auto = true;
			elseif ( $contw > 0 && $contw <= 1.0 ) $auto = true;

			// If size explitely given and not a fraction, it is static size
			if ( wppa_is_int( wppa( 'fullsize' ) ) && wppa( 'fullsize' ) > '1' ) {
				$auto = false;
			}

			// If an ajax request, the (start)size is given. To prevent loosing responsiveness, look at resp arg
			if ( wppa( 'ajax' ) && wppa_get( 'resp' ) ) {
				$auto = true;
			}

			if ( $auto ) {
				$the_js .= 'wppaAutoColumnWidth['.wppa( 'mocc' ).'] = true;';
				if ( $contw > 0 && $contw <= 1.0 ) {
					$the_js .= 'wppaAutoColumnFrac['.wppa( 'mocc' ).'] = '.$contw.';';
				}
				else {
					$the_js .= 'wppaAutoColumnFrac['.wppa( 'mocc' ).'] = 1.0;';
				}
				$the_js .= 'wppaColWidth['.wppa( 'mocc' ).'] = 0;';
				$the_js .= 'wppaMCRWidth['.wppa( 'mocc' ).'] = 0;';
			}
			else {
				$the_js .= 'wppaAutoColumnWidth['.wppa( 'mocc' ).'] = false;';
				$the_js .= 'wppaColWidth['.wppa( 'mocc' ).'] = '.wppa_get_container_width().';';
			}
			$the_js .= 'wppaTopMoc = Math.max(wppaTopMoc,'.wppa( 'mocc' ).');';
			if ( wppa_opt( 'thumbtype' ) == 'masonry-v' ) {
				$the_js .= 'wppaMasonryCols['.wppa( 'mocc' ).'] = '.ceil( wppa_get_container_width() / wppa_opt( 'thumbsize' ) ).';';
			} else {
				$the_js .= 'wppaMasonryCols['.wppa( 'mocc' ).'] = 0;';
			}
			if ( wppa( 'src_script' ) ) {
				$the_js .= wppa( 'src_script' );
			}
			$the_js .= 'wppaCoverImageResponsive['.wppa( 'mocc' ).'] = ' . ( wppa_switch( 'coverphoto_responsive' ) ? 'true;' : 'false;' );

			// Aspect ratio and fullsize
			if ( wppa_in_widget() == 'ss' && is_numeric( wppa( 'in_widget_frame_width' ) ) && wppa( 'in_widget_frame_width' ) > '0' ) {
				$asp = wppa( 'in_widget_frame_height' ) / wppa( 'in_widget_frame_width' );
				$fls = wppa( 'in_widget_frame_width' );
			}
			else {
				$asp = wppa_opt( 'maxheight' ) / wppa_opt( 'fullsize' );
				$fls = wppa_opt( 'fullsize' );
			}
			$asp = str_replace( ',', '.', $asp ); 	// Fix decimal comma to point
			$the_js .= 'wppaAspectRatio['.wppa( 'mocc' ).'] = '.$asp.';';
			$the_js .= 'wppaFullSize['.wppa( 'mocc' ).'] = '.$fls.';';

			// last minute change: fullvalign with border needs a height correction in slideframe
			if ( wppa_opt( 'fullimage_border_width' ) != '' && ! wppa_in_widget() ) {
				$delta = ( 1 + wppa_opt( 'fullimage_border_width' ) ) * 2;
			} else $delta = 0;
			$the_js .= 'wppaFullFrameDelta['.wppa( 'mocc' ).'] = '.$delta.';';

			// last minute change: script %%size != default colwidth
			$temp = wppa_get_container_width() - ( 2*6 + 2*36 + ( wppa_opt( 'bwidth' ) ? 2*wppa_opt( 'bwidth' ) : 0 ) );
			if ( wppa_in_widget() ) $temp = wppa_get_container_width() - ( 2*6 + 2*18 + 2*wppa_opt( 'bwidth' ) );
			$the_js .= 'wppaFilmStripLength['.wppa( 'mocc' ).'] = '.$temp.';';

			// last minute change: filmstrip sizes and related stuff. In widget: half size.
			$temp = wppa_opt( 'film_thumbsize' ) + wppa_opt( 'tn_margin' );
			if ( wppa_in_widget() ) $temp /= 2;
			$the_js .= 'wppaThumbnailPitch['.wppa( 'mocc' ).'] = '.$temp.';';
			$temp = wppa_opt( 'tn_margin' ) / 2;
			if ( wppa_in_widget() ) $temp /= 2;
			$the_js .= 'wppaFilmStripMargin['.wppa( 'mocc' ).'] = '.$temp.';';
			$temp = 2*6 + ( wppa_opt( 'bwidth' ) ? 2*wppa_opt( 'bwidth' ) : 0 );
			if ( wppa_switch( 'film_arrows' ) ) $temp += 2*42;
			if ( wppa_in_widget() ) {
				$temp = 2*6 + ( wppa_opt( 'bwidth' ) ? 2*wppa_opt( 'bwidth' ) : 0 );
				if ( wppa_switch( 'film_arrows' ) ) $temp += 2*21;
			}
			$the_js .= 'wppaFilmStripAreaDelta['.wppa( 'mocc' ).'] = '.$temp.';';
			$temp = wppa_get_preambule();
			$the_js .= 'wppaPreambule['.wppa( 'mocc' ).'] = '.$temp.';';
			if ( wppa_in_widget() ) {
				$the_js .= 'wppaIsMini['.wppa( 'mocc' ).'] = true;';
			}
			else {
				$the_js .= 'wppaIsMini['.wppa( 'mocc' ).'] = false;';
			}

			$target = false;
			if ( wppa_in_widget() == 'ss' && wppa_switch( 'sswidget_blank' ) ) $target = true;
			if ( ! wppa_in_widget() && wppa_switch( 'slideshow_blank' ) ) $target = true;
			if ( $target ) {
				$the_js .= 'wppaSlideBlank['.wppa( 'mocc' ).'] = true;';
			}
			else {
				$the_js .= 'wppaSlideBlank['.wppa( 'mocc' ).'] = false;';
			}
			// Slideshow widget always wraps around
			$the_js .= 'wppaSlideWrap['.wppa( 'mocc' ).'] = ' .
			( wppa_switch( 'slide_wrap' ) ||
			  wppa_in_widget() == 'ss' ||
			  wppa( 'is_slideonly' ) ||
			  wppa( 'is_slideonlyf' ) ?
			  'true;' : 'false;'
			);

			$the_js .= 'wppaLightBox['.wppa( 'mocc' ).'] = "xxx";';

			// If this occur is a slideshow, determine if its link is to lightbox. This may differ between normal slideshow or ss widget
			$is_slphoto = wppa( 'is_slide' ) && wppa( 'start_photo' ) && wppa( 'is_single' );
			if ( 'ss' == wppa_in_widget() || wppa_page( 'slide' ) || $is_slphoto ) {
				$ss_linktype = ( 'ss' == wppa_in_widget() ) ? wppa_opt( 'slideonly_widget_linktype' ) : wppa_opt( 'slideshow_linktype' );
				switch ( $ss_linktype ) {
					case 'file':
						$lbkey = 'file'; // gives anchor tag with rel="file"
						break;
					case 'lightbox':
					case 'lightboxsingle':
						$lbkey = 'wppa'; // gives anchor tag with rel="lightbox" or the like
						break;
					default:
						$lbkey = ''; // results in omitting the anchor tag
						break;
				}
				$the_js .= 'wppaLightBox[' . wppa( 'mocc' ) . '] = "' . $lbkey . '";';
				$the_js .= 'wppaLightboxSingle[' . wppa( 'mocc' ) . '] = ' . ( wppa_opt( 'slideshow_linktype' ) == 'lightboxsingle' ? 'true': 'false' ) . ';';
			}
			$the_js .= 'wppaSearchBoxSelItems[' . wppa( 'mocc' ) . '] = ' . ( ( wppa_switch( 'search_catbox' ) ? 1 : 0 ) + wppa_opt( 'search_selboxes' ) + 1 ) . ';';

			$the_js .= 'wppaFilmInit[' . wppa( 'mocc' ) . '] = false;';
			if ( wppa( 'is_filmonly' ) ) {
				$the_js .= '_wppaCurIdx['.wppa( 'mocc' ).'] = 0;';
			}

			$the_js .= 'wppaMaxOccur = Math.max( wppaMaxOccur, ' . wppa( 'mocc' ) . ' );';
			wppa_js( $the_js );
	}

	// Close request?
	elseif ( $action == 'close' )	{

		if ( wppa_page( 'oneofone' ) ) wppa( 'portrait_only', false );
		if ( ! wppa_in_widget() ) wppa_out( '<div style="clear:both;"></div>' );

		if ( ! defined( 'DOING_WPPA_AJAX' ) ) {

			wppa_out( '<div id="wppa-container-' . wppa( 'mocc' ) . '-end" ></div>' );

			// Scroll down to container ?
			$do_scroll = 	wppa_switch( 'non_ajax_scroll' ) && wppa_get( 'occur' ) == wppa( 'mocc' );

			if ( $do_scroll ) {
				wppa_js( 'jQuery("html, body").animate({scrollTop:jQuery("#wppa-container-' . wppa( 'mocc' ) . '").offset().top-32-' . wppa_opt( 'sticky_header_size' ) . '},1000);' );
			}

			wppa_out( '</div>' );

			// Static max in responsive? close wrapper
			wppa_container_wrapper( 'close' );
		}

		wppa_js( 'jQuery(window).trigger("resize");' );
	}

	// Unimplemented request
	else {
		wppa_out( "\n".'<span style="color:red;">Error, wppa_container() called with wrong argument: '.$action.'. Possible values: \'open\' or \'close\'</span>' );
	}
}

function wppa_container_wrapper( $key ) {
	switch( $key ) {
		case 'open':
			switch ( wppa( 'align' ) ) {
				case 'left':
					$class = 'alignleft wppa-container-wrapper';
					break;
				case 'right':
					$class = 'alignright wppa-container-wrapper';
					break;
				case 'center':
					$class = 'aligncenter wppa-container-wrapper';
					break;
				default:
					$class = 'alignnone wppa-container-wrapper';
					break;
			}
			wppa_out( '
				<div
					id="wppa-container-wrapper-' . wppa( 'mocc' ) . '"
					class="' . $class . ' ' . wppa( 'container-wrapper-class' ) . '"
					style="' . wppa_get_container_wrapper_style() . '"
					>' );
			break;
		case 'close':
			wppa_out( '</div>' );
			break;
		default:
	}
}

function wppa_album_list( $action ) {
global $cover_count;
global $cover_count_key;

	$nice 		= wppa_is_nice();
	$maxh 		= wppa_opt( 'area_size' );
	$overflow 	= 'auto';
	$mocc 		= wppa( 'mocc' );
	if ( $nice ) $overflow = 'hidden';
	$modal = defined( 'DOING_WPPA_AJAX' ) && wppa_switch( 'ajax_render_modal' );

	// Open
	if ( $action == 'open' ) {

		$cover_count = '0';
		$cover_count_key = 'l';
		wppa_out( '
			<div
				id="wppa-albumlist-' . wppa( 'mocc' ) . '"
				style="' .
				( $maxh > '1' ? 'max-height:' . $maxh . 'px;' : '' ) .
				'overflow:' . $overflow . ';' .
				'"' . '
				class="albumlist' . ( $modal ? ' wppa-modal' : '' ) . '"
				onscroll="wppaMakeLazyVisible();"
				>' );

		if ( $nice ) {
			wppa_out( '<div class="wppa-nicewrap" >' );
		}
	}

	// Close
	else {
		if ( $nice ) {
			wppa_out( '<div style="clear:both"></div></div>' );
		}

		// Close wppa-nicewrap
		wppa_out( '</div>' );

		// Activate nicescroll
		if ( $nice ) {
			wppa_js( 'jQuery(document).ready(function(){
				if ( jQuery().niceScroll )
				jQuery(".albumlist").niceScroll(".wppa-nicewrap",{' . wppa_opt( 'nicescroll_opts' ) . '});});' );
		}
	}
}

function wppa_slide_list( $action ) {

	$nice 		= wppa_is_nice();
	$maxh 		= wppa_opt( 'area_size_slide' );
	$overflow 	= 'auto';
	$mocc 		= wppa( 'mocc' );
	if ( $nice ) $overflow = 'hidden';
	$modal = defined( 'DOING_WPPA_AJAX' ) && wppa_switch( 'ajax_render_modal' );

	// Open
	if ( $action == 'open' ) {

		wppa_out( '
			<div
				id="wppa-slidelist-' . wppa( 'mocc' ) . '"
				style="' .
				( $maxh > '1' ? 'max-height:' . $maxh . 'px;' : '' ) .
				'overflow:' . $overflow . ';' .
				'"' . '
				class="slidelist' . ( $modal ? ' wppa-modal' : '' ) . '"
				>' );

		if ( $nice ) {
			wppa_out( '<div class="wppa-nicewrap" >' );
		}
	}

	// Close
	else {
		if ( $nice ) {
			wppa_out( '<div style="clear:both"></div></div>' );
		}

		// Close wppa-nicewrap
		wppa_out( '</div>' );

		// Activate nicescroll
		if ( $nice ) {
			wppa_js( 'jQuery(document).ready(function(){
				if ( jQuery().niceScroll )
				jQuery(".slidelist").niceScroll(".wppa-nicewrap",{' . wppa_opt( 'nicescroll_opts' ) . '});});' );
		}
	}
}

function wppa_thumb_list( $action ) {
global $cover_count;
global $cover_count_key;

	if ( $action == 'open' ) {
		$cover_count = '0';
		$cover_count_key = 'l';
		wppa_out( '<div id="wppa-thumblist-'.wppa( 'mocc' ).'" class="thumblist">' );
		if ( wppa( 'current_album' ) ) wppa_bump_viewcount( 'album', wppa( 'current_album' ) );
	}
	elseif ( $action == 'close' ) {
		wppa_out( '</div>' );
	}
	else {
		wppa_out( '<span style="color:red;">Error, wppa_thumblist() called with wrong argument: '.$action.'. Possible values: \'open\' or \'close\'</span>' );
	}
}

function wppa_get_npages( $type, $array ) {

	$aps = wppa_get_pagesize( 'albums' );
	$tps = wppa_get_pagesize( 'thumbs' );

	// Switch pagination off when searching
	if ( is_search() ) {
		$aps = '0';
		$tps = '0';
	}

	$arraycount = is_array( $array ) ? count( $array ) : '0';
	$result = '0';
	if ( $type == 'albums' ) {
		if ( $aps != '0' ) {
			$result = ceil( $arraycount / $aps );
		}
		elseif ( $tps != '0' ) {
			if ( $arraycount ) $result = '1';
			else $result = '0';
		}
	}
	elseif ( $type == 'thumbs' ) {
		if ( wppa( 'is_cover' ) == '1' ) {		// Cover has no thumbs: 0 pages
			$result = '0';
		}
		elseif ( ! $arraycount ) {
			$result = '0';
		}
		elseif ( $tps != '0' ) {
			$result = ceil( $arraycount / $tps );	// Pag on: compute
		}
		else {
			$result = '1';								// Pag off: all fits on 1
		}
	}
	return $result;
}

function wppa_popup() {

	wppa_out( 	'<div' .
					' id="wppa-popup-'.wppa( 'mocc' ).'"' .
					' class="wppa-popup-frame wppa-thumb-text"' .
					' style="max-width:2048px;"' .
					' onmouseout="wppaPopDown( '.wppa( 'mocc' ).' );"' .
					' >' .
				'</div>' .
				'<div style="clear:both">' .
				'</div>' );
}

function wppa_run_slidecontainer( $thumbs ) {

	$c = is_array( $thumbs ) ? count( $thumbs ) : '0';

	if ( wppa( 'is_single' ) && is_feed() ) {	// process feed for single image slideshow here, normal slideshow uses filmthumbs
		$style_a = wppa_get_fullimgstyle_a( wppa( 'start_photo' ) );
		$style   = $style_a['style'];
		$width   = $style_a['width'];
		$height  = $style_a['height'];
		$imgalt	 = wppa_get_imgalt( wppa( 'start_photo' ) );
		wppa_out( '
			<a href="' . get_permalink() . '" >
				<img
					src="' . wppa_get_photo_url( wppa( 'start_photo' ), '', $width, $height ) . '"
					style="' . $style . '"' .
					$imgalt . '
				>
			</a>' );
		return;
	}
	else {

		// Find slideshow start method
		switch ( wppa_opt( 'start_slide' ) ) {

			case 'still':
				$startindex = 0;
				break;
			case 'norate':
				$startindex = -2;
				break;
			default: // case 'run':
				$startindex = -1;
				break;
		}

		// A requested photo id overrules the method. $startid >0 is requested photo id, -1 means: no id requested
		if ( wppa( 'start_photo' ) ) $startid = wppa( 'start_photo' );
		else $startid = -1;

		// Create next ids
		$ix = 0;
		if ( $thumbs ) while ( $ix < count( $thumbs ) ) {
			if ( $ix == ( count( $thumbs )-1 ) ) $thumbs[$ix]['next_id'] = $thumbs[0]['id'];
			else $thumbs[$ix]['next_id'] = $thumbs[$ix + 1]['id'];
			$ix ++;
		}

		// Produce scripts for slides
		$index = 0;
		if ( $thumbs ) {

			$js = 'jQuery(document).ready(function(){';
			foreach ( $thumbs as $thumb ) {
				if ( wppa_switch( 'next_on_callback' ) ) {
					$js .= 'wppaStoreSlideInfo( ' . wppa_get_slide_info( $index, strval( intval( $thumb['id'] ) ), strval( intval( $thumb['next_id'] ) ) ) . ' );';
				}
				else {
					$js .= 'wppaStoreSlideInfo( ' . wppa_get_slide_info( $index, strval( intval( $thumb['id'] ) ) ) . ' );';
				}
				if ( $startid == $thumb['id'] ) $startindex = $index;	// Found the requested id, put the corresponding index in $startindex
				$index++;
			}
			$js .= '});';
			wppa_js( $js );
		}

		// How to start if slideonly
		if ( wppa( 'is_slideonly' ) ) {
			if ( wppa_switch( 'start_slideonly' ) ) {
				$startindex = -1;	// There are no navigations, so start running, overrule everything
			}
			else {
				$startindex = 0;
			}
		}

		// Vertical align
		if ( wppa( 'in_widget' ) ) {
			$ali = wppa( 'ss_widget_valign' ) ? wppa( 'ss_widget_valign' ) : $ali = 'fit';
			wppa_js( 'wppaFullValign['.wppa( 'mocc' ).'] = "'.$ali.'";' );
		}
		elseif ( wppa( 'is_slideonly' ) ) {
			wppa_js( 'wppaFullValign['.wppa( 'mocc' ).'] = "'.wppa_opt( 'fullvalign_slideonly' ).'";' );
		}
		else {
			wppa_js( 'wppaFullValign['.wppa( 'mocc' ).'] = "'.wppa_opt( 'fullvalign' ).'";' );
		}

		// Horizontal align
		wppa_js( 'wppaFullHalign['.wppa( 'mocc' ).'] = "'.wppa_opt( 'fullhalign' ).'";' );

		// Portrait only ?
		if ( ( wppa( 'in_widget' ) && wppa( 'portrait_only' ) ) || ( ! wppa( 'in_widget' ) && wppa_switch( 'slide_portrait_only' ) ) ) {
			wppa_js( 'wppaPortraitOnly['.wppa( 'mocc' ).'] = true;' );
		}

		// Start command with appropriate $startindex: -2 = at norate, -1 run from first, >=0 still at index
		wppa_js( 'jQuery(document).ready( function() { setTimeout( function(){wppaStartStop( '.wppa( 'mocc' ).', '.$startindex.' );},2) } );' );
	}
}

function wppa_is_pagination() {

	// Pagination is off during search
	if ( is_search() ) {
		return false;
	}

	if ( ( wppa_get_pagesize( 'albums' ) == '0' && wppa_get_pagesize( 'thumbs' ) == '0' ) ) return false;
	else return true;
}

function wppa_get_preambule() {

	if ( ! wppa_switch( 'slide_wrap' ) && wppa( 'in_widget' ) != 'ss' ) {
		return '0';
	}
	$result = wppa_opt( 'initial_colwidth' );
	$result = ceil( ceil( $result / wppa_opt( 'thumbsize' ) ) / 2 ) + 2;
	return $result;
}

function wppa_dummy_bar( $msg = '' ) {

	wppa_out( '<div style="margin:4px 0; text-align:center;">' . $msg . '</div>' );
}

function wppa_rating_count_by_id( $id = '' ) {

	wppa_out( wppa_get_rating_count_by_id( $id ) );
}

function wppa_rating_by_id( $id = '', $opt = '' ) {

	wppa_out( wppa_get_rating_by_id( $id, $opt ) );
}

function wppa_get_cover_width( $type, $numeric = false ) {

	$conwidth 	= wppa_get_container_width();
	$cols 		= wppa_get_cover_cols( $type );
	$ppc 		= floor( '100' / $cols );

	if ( wppa_is_mobile() ) {
		$result = 'width:100%;';
	}
	elseif( wppa_is_responsive() ) {
		$result = 'width:' . $ppc . '%;';
	}
	else {
		$result = 'width:' . floor( ( $conwidth - ( 8 * ( $cols - 1 ) ) ) / $cols ) . 'px;';
	}

	if ( $numeric ) {
		$result = str_replace( 'width:', '', $result );
		if ( strpos( $result, '%' ) ) {
			$result = str_replace( array( '%', ';'), '', $result );
			$result = $result * wppa_opt( 'initial_colwidth' ) / '100';
		}
		else {
			$result = str_replace( 'px;', '', $result );
		}
	}

	return $result;
}

function wppa_is_responsive() {

	// Assume not
	$result = false;

	// Get container width
	$ctw = wppa_get_container_width();

	// Responsive fraction ?
	if ( $ctw <= '1' ) {
		$result = true;
	}

	// Responsive full width ?
	elseif ( wppa( 'auto_colwidth' ) ) {
		$result = true;
	}

	return $result;
}

function wppa_get_text_frame_style( $photo_left, $type ) {

	if ( wppa_in_widget() ) {
		$result = '';
	}
	else {
		if ( $type == 'thumb' ) {
			$width = wppa_get_cover_width( $type, true );
			$width -= 13;	// margin
			$width -= 2; 	// border
			$width -= wppa_opt( 'smallsize' );

			if ( $photo_left ) {
				$result = 'style="width:'.$width.'px; float:right;"';
			}
			else {
				$result = 'style="width:'.$width.'px; float:left"';
			}
		}
		elseif ( $type == 'cover' ) {
			$width = wppa_get_cover_width( $type, true );
			$photo_pos = $photo_left;
			if ( wppa_switch( 'coverphoto_responsive' ) ) {
				$width = 100 - wppa_opt( 'smallsize_percentage' );
				if ( $width > 2 ) {
					$width -= 2;
				}
				switch ( $photo_pos ) {
					case 'left':
						$result = 'style="width:'.$width.'%;float:right;"';
						break;
					case 'right':
						$result = 'style="width:'.$width.'%;float:left"';
						break;
					case 'top':
					case 'bottom':
						$result = '';
						break;
					default:
				}
			}
			else {
				switch ( $photo_pos ) {
					case 'left':
						$width -= wppa_get_textframe_delta();
						$result = 'style="width:'.$width.'px; float:right;"';
						break;
					case 'right':
						$width -= wppa_get_textframe_delta();
						$result = 'style="width:'.$width.'px; float:left"';
						break;
					case 'top':
					case 'bottom':
						$result = '';
						break;
					default:
				}
			}
		}
	}
	return $result;
}

function wppa_get_textframe_delta() {

	$delta = wppa_opt( 'smallsize' );
	$delta += ( 2 * ( 7 + ( wppa_opt( 'bwidth' ) ? wppa_opt( 'bwidth' ) : 0 ) + 4 ) + 5 + 2 );	// 2 * ( padding + border + photopadding ) + margin
	return $delta;
}

function wppa_step_covercount( $type ) {
global $cover_count;
global $cover_count_key;

	$key = 'm';
	$cols = wppa_get_cover_cols( $type );
	$cover_count++;
	if ( $cover_count == $cols ) {
		$cover_count = '0'; // Row is full
		$key = 'l';
	}
	if ( $cover_count + '1' == $cols ) {
		$key = 'r';
	}
	$cover_count_key = $key;
}

function wppa_get_cover_cols( $type ) {

	$conwidth = wppa_get_container_width();

	$cols = ceil( $conwidth / wppa_opt( 'max_cover_width' ) );

	// Exceptions
	if ( wppa( 'auto_colwidth' ) ) $cols = '1';
	if ( ( $type == 'cover' ) && ( wppa( 'album_count' ) < '2' ) ) $cols = '1';
	if ( ( $type == 'thumb' ) && ( wppa( 'thumb_count' ) < '2' ) ) $cols = '1';
	return $cols;
}

function wppa_get_box_width() {

	$result = wppa_get_container_width();
	if ( $result < 1 ) {
		$result *= wppa_opt( 'initial_colwidth' );
	}
	$result -= 12;	// 2 * padding
	$result -= 2 * ( wppa_opt( 'bwidth' ) ? wppa_opt( 'bwidth' ) : 0 );
	return $result;
}

function wppa_get_box_delta() {
	$result = wppa_get_container_width();
	if ( $result < 1 ) {
		$result *= wppa_opt( 'initial_colwidth' );
	}
	$result -= wppa_get_box_width();
	return $result;
}

function wppa_force_balance_pee( $xtext ) {

	$text = $xtext;	// Make a local copy
	$done = false;
	$temp = strtolower( $text );

	// see if this chunk ends in <p> in which case we remove that instead of appending a </p>
	$len = strlen( $temp );
	if ( $len > 3 ) {
		if ( substr( $temp, $len - 3 ) == '<p>' ) {
			$text = substr( $text, 0, $len - 3 );
			$temp = strtolower( $text );
		}
	}

	$opens = substr_count( $temp, '<p' );
	$close = substr_count( $temp, '</p' );
	// append a close
	if ( $opens > $close ) {
		$text .= '</p>';
	}
	// prepend an open
	if ( $close > $opens ) {
		$text = '<p>'.$text;
	}
	return $text;
}

// The single image, s, m, or x.
function wppa_smx_photo( $id, $stype ) {

	if ( wppa_photo_exists( $id ) === '0' ) {
		wppa_log( 'Err', 'Photo does not exists ( wppa_smx_photo() ), type = ' . $stype . ', single_photo = ' . $id );
		return;
	}

	$width 	= wppa_get_container_width();
	if ( $width > 1 ) {
		$pwidth = $width;
	}
	else {
		$pwidth = $width * wppa_opt( 'initial_colwidth' );
	}

	if ( wppa_is_video( $id ) ) {
		$py 	= wppa_get_videoy( $id );
		$px 	= wppa_get_videox( $id );
	}
	else {
		$py 	= wppa_get_photoy( $id );
		$px 	= wppa_get_photox( $id );
	}
	if ( ! $px ) {
		wppa_log( 'Err', 'Unknown size of item nr ' . $id . ' in wppa_smx_photo()', true );
		return;
	}

	$pheight = round( $pwidth * $py / $px );

	// wrapper
	wppa_container_wrapper( 'open' );

	// Open the pseudo container
	// The container defines size ( fixed pixels or percent ) and position ( left, center, right or default ) of the image
	wppa_out( '
		<div
			id="wppa-container-' . wppa( 'mocc' ) . '"
			style="width:100%;"
			class="wppa-container ' . ( wppa( 'align' ) ? 'align' . wppa( 'align' ) : '' ) . ( $stype == 'm' || $stype == 'x' ? ' wp-caption' : '' ) . '"
			>' );

		// The image html
		$html 		= wppa_get_picture_html( array( 'id' 		=> $id,
													'type' 		=> $stype . 'photo',
													'class' 	=> 'size-medium wppa-' . $stype . 'photo',
													'width' 	=> $pwidth,
													'height' 	=> $pheight,
													) );

		wppa_out( $html );

		if ( $stype == 's' ) {

			// Download link
			if ( wppa_switch( 'art_monkey_single' ) ) {
				$dllink = wppa_get_download_html( $id, 'single' );
				// wppa_out( '<div class="wppa-download-text" style="margin-top:8px;text-align:center;">' . $dllink . '</div>');
				wppa_out( $dllink );
			}
		}

		// The subext if any
		if ( $stype == 'm' || $stype == 'x' ) {

			// Download link
			if ( wppa_switch( 'art_monkey_mxsingle' ) ) {
				$dllink = wppa_get_download_html( $id, 'single' );
				// wppa_out( '</div><div class="wppa-download-text" style="clear:both;text-align:center;">' . $dllink . '</div><div style="cler:both">' );
				wppa_out( $dllink );
			}

			// The description
			$desc = wppa_get_photo_desc( $id );

			// Run wpautop on description?
			if ( wppa_opt( 'wpautop_on_desc' ) == 'wpautop' ) {
				$desc = wpautop( $desc );
			}
			elseif ( wppa_opt( 'wpautop_on_desc' ) == 'nl2br' ) {
				$desc = nl2br( $desc );
			}
			wppa_out( '<p class="wp-caption-text">' . $desc . '</p>' );

			// The rating, only on xphoto when enabled in II-B7
			if ( $stype == 'x' && wppa_switch( 'rating_on' ) ) {
				wppa_out( wppa_get_rating_range_html( $id, false, 'wp-caption-text' ) );
			}

			// The share buttons on mphoto if enabled in II-C6, and on xphoto when enabled in II-C1
			if ( wppa_switch( 'share_on_mphoto' ) || $stype == 'x' ) {
				wppa_out( wppa_get_share_html( $id, 'mphoto', false, true ) );
			}

			// The commentform on xphoto when enabled in II-B10
			if ( $stype == 'x' && wppa_switch( 'show_comments' ) ) {
				wppa_out( '<div id="wppa-comments-' . wppa( 'mocc' ) . '" >' );
					$comment_allowed = ! wppa_user_is_basic() && is_user_logged_in();
					wppa_out( wppa_comment_html( $id, $comment_allowed ) );
				wppa_out( '</div>' );
				if ( wppa_switch( 'auto_open_comments' ) ) {
					wppa_js( 'jQuery(document).ready(function(){wppaOpenComments('.wppa('mocc').');});' );
				}
			}
		}

	// The ajax Spinner
	wppa_out( wppa_get_spinner_svg_html( ['id' 	=> 'wppa-ajax-spin-' . wppa( 'mocc' ), 'class' => 'wppa-ajax-spin'] ) );

	// The pseudo container
	wppa_out( '</div>' );

	// Wrapper for maximized auto
	wppa_container_wrapper( 'close' );
}

// returns aspect ratio ( w/h ), or 1 on error
function wppa_get_ratio( $id ) {

	if ( ! wppa_is_int( $id ) ) return '1';	// Not 0 to prevent divide by zero

	$temp = wppa_get_imagexy( $id );

	if ( $temp['1'] ) {
		return $temp['0'] / $temp['1'];
	}
	else {
		return '1';
	}
}

function wppa_is_photo_new( $id ) {

	// Feature enabled?
	if ( ! wppa_opt( 'max_photo_newtime' ) ) {
		return false;
	}

	$thumb = wppa_cache_photo( $id );
	if ( ! $thumb ) { 	// Photo vanished?
		return false;
	}

	$birthtime = $thumb['timestamp'];
	$timnow = time();
	$isnew = ( ( $timnow - $birthtime ) < wppa_opt( 'max_photo_newtime' ) );

	return $isnew;
}

function wppa_is_photo_modified( $id ) {

	// Feature enabled?
	if ( ! wppa_opt( 'max_photo_modtime' ) ) {
		return false;
	}

	$thumb = wppa_cache_photo( $id );
	if ( ! $thumb ) { 	// Photo vanished?
		return false;
	}

	$cretime = $thumb['timestamp'];
	$modtime = $thumb['modified'];

	// A photo is regarded NOT to be modified if the datetime modified is within 2 seconds after creation
	if ( $modtime <= ( $cretime + 2 ) ) {
		return false;
	}

	$timnow = time();
	$isnew = ( ( $timnow - $modtime ) < wppa_opt( 'max_photo_modtime' ) );

	return $isnew;
}

function wppa_is_photo_first( $id ) {
global $wpdb;
static $firsts;

	// Feature enabled?
	if ( ! wppa_switch( 'show_first' ) ) {
		return false;
	}

	// Create cache if not done
	if ( ! $firsts ) {
		$firsts = array();
	}

	// Get owner of current item
	$user = wppa_get_photo_item( $id, 'owner' );

	// Look into cache
	if ( isset( $firsts[$user] ) ) { // Tested this user before
		return $firsts[$user] == $id;
	}

	// Not in cache yet, find users first upload
	$first = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos
											  WHERE owner = %s ORDER BY timestamp LIMIT 1", $user ) );

	// Save into cache
	$firsts[$user] = $first;

	// Is it this one?
	return $first == $id;
}

function wppa_is_album_new( $id ) {
global $wpdb;
global $wppa_children;

	// Feature enabled?
	if ( ! wppa_opt( 'max_album_newtime' ) ) {
		return false;
	}

	// See if album self is new
	$album = wppa_cache_album( $id );
	$birthtime = $album['timestamp'];
	$timnow = time();
	$isnew = ( ( $timnow - $birthtime ) < wppa_opt( 'max_album_newtime' ) );

	if ( $isnew ) return true;

	// A new ( grand )child?
	if ( isset( $wppa_children[$id] ) ) {
		$children = $wppa_children[$id];
	}
	else {
		$children = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %s", $id ), ARRAY_A );
		$wppa_children[$id] = $children;
	}

	if ( $children ) {
		foreach ( $children as $child ) {
			if ( wppa_is_album_new( $child['id'] ) ) return true;	// Found one? Done!
		}
	}
	return false;
}

function wppa_is_album_modified( $id ) {
global $wpdb;
global $wppa_children;

	// Feature enabled ?
	if ( ! wppa_opt( 'max_album_modtime' ) ) {
		return false;
	}

	$album = wppa_cache_album( $id );
	$modtime = $album['modified'];
	$timnow = time();
	$isnew = ( ( $timnow - $modtime ) < wppa_opt( 'max_album_modtime' ) );

	if ( $isnew ) return true;

	// A modified ( grand )child?
	if ( isset( $wppa_children[$id] ) ) {
		$children = $wppa_children[$id];
	}
	else {
		$children = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums WHERE a_parent = %s", $id ), ARRAY_A );
		$wppa_children[$id] = $children;
	}

	if ( $children ) {
		foreach ( $children as $child ) {
			if ( wppa_is_album_modified( $child['id'] ) ) return true;	// Found one? Done!
		}
	}
	return false;
}

function wppa_get_photo_id_by_name( $xname, $album = '0' ) {
global $wpdb;
global $allphotos;

	if ( wppa_is_int( $xname ) ) {
		return $xname; // Already nemeric
	}

	$name = wppa_sanitize_album_photo_name( $xname );

	if ( wppa_is_int( $album ) ) {
		$alb = $album;
	}
	else {
		$albums = wppa_series_to_array( $album );
		if ( is_array( $albums ) ) {
			$alb = implode( " OR album = ", $albums );
		}
		else {
			$alb = wppa_get_album_id_by_name( $album );
		}
	}

	if ( $alb ) {
		$pid = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE sname = %s AND ( album = $alb ) LIMIT 1", $name ) );
	}
	else {
		$pid = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE sname = %s LIMIT 1", $name ) );
	}

	// Not found? Try crypt
	if ( ! $pid ) {
		if ( $alb ) {
			$pid = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE crypt = %s AND ( album = $alb ) LIMIT 1", $name ) );
		}
		else {
			$pid = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE crypt = %s LIMIT 1", $name ) );
		}
	}

	return $pid;
}

function wppa_get_album_id_by_name( $xname, $report_dups = false ) {
global $wpdb;
global $allalbums;

	if ( wppa_is_int( $xname ) ) {
		return $xname;	// Already numeric
	}
	if ( wppa_is_enum( $xname ) ) {
		return $xname; 	// Is enumeration
	}

	$name = wppa_sanitize_album_photo_name( $xname );

	$query = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums WHERE sname = %s", $name );
	$albs = $wpdb->get_results( $query, ARRAY_A );

	if ( $albs ) {
		if ( count( $albs ) == 1 ) {
			$aid = $albs[0]['id'];
		}
		else {
			if ( $report_dups == 'report_dups' ) {
				$aid = false;
			}
			elseif ( $report_dups == 'return_dups' ) {
				$aid = '';
				foreach ( $albs as $alb ) {
					$aid .= $alb['id'] . '.';
				}
				$aid = rtrim( $aid, '.' );
			}
			else {

				// Find the best match
				$aid = 0;
				foreach ( $albs as $alb ) {
					$aname = __( $alb['name'] );				// Possibly qTranslate translated
					$aname = str_replace( '\'', '%', $aname );	// A trick for single quotes
					$aname = str_replace( '"', '%', $aname );	// A trick for double quotes
					$aname = stripslashes( $aname );
					if ( strcasecmp( $aname, $name ) == 0 ) {
						$aid = $alb['id'];
					}
				}

				// No perfect match, take the first 'like' option
				if ( ! $aid ) {
					$aid = $albs[0]['id'];
				}
			}
		}
	}
	else {
		$aid = false;
	}

	return $aid;
}

// Perform the frontend Create album, Upload photo and Edit album
// wppa_user_upload_on must be on for any of these functions to be enabled
function wppa_user_upload() {
global $wpdb;
static $done;
global $wppa_alert;
global $wppa_upload_succes_id;

	if ( $done ) return;					// Already done
	$done = true;							// Mark as done
	$wppa_alert = '';

	// Upload possible?
	$may_upload = wppa_switch( 'user_upload_on' );
	if ( ! is_user_logged_in() ) $may_upload = false;	// Must login

	// Create album possible?
	$may_create = wppa_switch( 'user_create_on' );
	if ( ! is_user_logged_in() ) $may_create = false;					// Must login
	if ( $may_create ) {

		// Find the parent
		$parent = strval( intval( wppa_get( 'album-parent' ) ) );
		if ( ! wppa_user_is_admin() && wppa_switch( 'default_parent_always' ) ) {
			$parent = wppa_opt( 'default_parent' );
		}

		// If roles specified and i am not an admin, see if i have one
		if ( wppa_opt( 'user_create_roles' ) && ! wppa_user_is_admin() ) {

			// Allowed roles
			$allowed_roles = explode( ',', wppa_opt( 'user_create_roles' ) );

			// Current user roles
			$user = wp_get_current_user();

			if ( ! array_intersect( $allowed_roles, $user->roles ) ) {
			   $may_create = false;
			}
		}

		// Test for max children of parent
		if ( $parent > '0' ) {

			$max = wppa_get_album_item( $parent, 'max_children' );
			if ( $max == '-1' ) $may_create = false; // None alowed
			elseif ( $max ) { // See if max reached ( 0 = unlimited )
				$tc = wppa_get_treecounts_a( $parent );
				$nchild = $tc['selfalbums'];
				if ( $nchild >= $max ) $may_create = false;;
			}
		}
	}

	// Edit album possible?
	$may_edit = wppa_switch( 'user_album_edit_on' );

	// Do create
	if ( $may_create ) {
		if ( wppa_get( 'fe-create' ) ) {	// Create album
			$nonce = wppa_get( 'nonce' );
			$albumname = wppa_sanitize_file_name( trim( strip_tags( wppa_get( 'album-name', __( 'New Album', 'wp-photo-album-plus' ) ) ) ) );
			if ( ! $albumname ) {
				$albumname = __( 'New Album', 'wp-photo-album-plus' );
			}
			$ok = wp_verify_nonce( $nonce, 'wppa-album-check' );
			if ( ! $ok ) die( '<b>' . __( 'ERROR: Illegal attempt to create an album.', 'wp-photo-album-plus' ) . '</b>' );

			// Check captcha
			if ( wppa_switch( 'user_create_captcha' ) ) {

				$captkey = wppa_get_randseed( 'session' );

				if ( ! wppa_check_captcha( $captkey ) ) {
					wppa_alert( __( 'Wrong captcha, please try again', 'wp-photo-album-plus' ) );
					return;
				}
			}


			$album = wppa_create_album_entry( array( 	'name' 			=> $albumname,
														'description' 	=> strip_tags( wppa_get( 'album-desc' ) ),
														'a_parent' 		=> $parent,
														'owner' 		=> wppa_switch( 'frontend_album_public' ) ? '--- public ---' : wppa_get_user()
														 ) );
			if ( $album ) {
				if ( wppa_opt( 'fe_alert' ) == 'upcre' || wppa_opt( 'fe_alert' ) == 'all' ) {
					wppa_alert( sprintf( __( 'Album #%s created', 'wp-photo-album-plus' ), $album ) );
				}
				wppa_invalidate_treecounts( $parent );
				wppa_verify_treecounts_a( $parent );
				wppa_create_pl_htaccess();
			}
			else {
				wppa_alert( __( 'Could not create album', 'wp-photo-album-plus' ) );
			}
		}
	}

	// Do Upload
	if ( $may_upload ) {
		$upload_message = '';
		$blogged = false;
		if ( wppa_get( 'upload-album' ) ) {	// Upload photo
			$nonce = wppa_get( 'nonce' );
			$ok = wp_verify_nonce( $nonce, 'wppa-check' );
			if ( ! $ok ) {
				die( '<b>' . __( 'ERROR: Illegal attempt to upload a file.', 'wp-photo-album-plus' ) . '</b>');
			}

			$alb = wppa_get( 'upload-album' );
			$alb = strval( intval( $alb ) ); // Force numeric
			if ( ! wppa_album_exists( $alb ) ) {
				$alert = esc_js( sprintf( __( 'Album %s does not exist', 'wp-photo-album-plus' ), $alb ) );
				wppa_alert( $alert );
				return;
			}

			$uploaded_ids 	= array();
			$iret 			= true;
			$done 			= '0';
			$fail 			= '0';

			// For security reasons use wp_handle_upload to do the actual upload
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}

			foreach( $_FILES as $files ) {
				foreach ( $files['name'] as $key => $value ) {
					if ( $files['name'][ $key ] ) {
						$file = array(
							'name' 		=> basename( $files['name'][$key] ),
							'type' 		=> $files['type'][$key],
							'tmp_name' 	=> $files['tmp_name'][$key],
							'error' 	=> $files['error'][$key],
							'size' 		=> $files['size'][$key]
						);

						// Check for valid extension and mime type
						$file_is_ok = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], wppa_get_mime_types() );

						if ( ! $file_is_ok['ext'] || ! $file_is_ok['type'] ) {
							$upload_message =
							__( 'Upload failed', 'wp-photo-album-plus' ) . ' ' .
							__( 'You may not uplaod this file type', 'wp-photo-album-plus' ) .
							' (' . sanitize_file_name( $files['name'][ $key ] ) . ')';
							wppa_echo( $upload_message );
							return;
						}

						$filename = $file['name'];
						if ( $file_is_ok['proper_filename'] ) {
							$filename = $file_is_ok['proper_filename'];
						}
						$dirname = WPPA_UPLOAD_PATH . '/temp';
						if ( ! wppa_is_dir( $dirname ) ) {
							wppa_mktree( $dirname );
						}
						$pathname = $dirname . '/' . $filename;
						$bret = move_uploaded_file( $file['tmp_name'], $pathname );
						if ( $bret ) {
							wppa_log( 'fso', 'Moved file ' . $file['tmp_name'] . ' to ' . $pathname );
						}
						else {
							wppa_log( 'err', 'Could not move file ' . $file['tmp_name'] . ' to ' . $pathname );
							$upload_message =
							__( 'Upload failed', 'wp-photo-album-plus' ) . ' ' .
							__( 'Could not move this file', 'wp-photo-album-plus' ) .
							' (' . sanitize_file_name( $files['name'][ $key ] ) . ')';
							wppa_echo( $upload_message );
							return;
						}

						$my_file = array(
							'name' => $filename,
							'tmp_name' => $pathname,
						);

						// Do the housekeeping
						wppa( 'unsanitized_filename', basename( $my_file['name'] ) );
						$iret = wppa_do_fe_upload_housekeeping( $my_file, $alb );

						// Report phto id if from tinymce photo shortcode generator upload
						$wppa_upload_succes_id = $iret;
						if ( $iret ) {
							$uploaded_ids[] = $iret;
							$done++;
							wppa_set_last_album( $alb );
						}
						else {
							$fail++;
							wppa_log( 'err', $wppa_alert );
						}
					}
				}
			}

			$points = '0';
			$reload = false;

			// Init alert text with possible results from wppa_do_fe_upload_housekeeping()
			$alert 			= $wppa_alert;
			$upload_message = $alert;
			$blog_failed 	= false;
			$status 		= '';

			if ( $done ) {

				// SUCCESSFUL UPLOAD, Blog It?
				if ( current_user_can( 'edit_posts' ) && wppa_get( 'blogit' ) ) {

					$title 		= wppa_get( 'post-title' );
					if ( ! $title ) {
						$title = wppa_local_date();
					}
					$pretxt 	= wppa_get( 'blogit-pretext' );
					$posttxt 	= wppa_get( 'blogit-posttext' );
					$status 	= wppa_switch( 'blog_it_moderate' ) ? 'pending' : 'publish';

					$post_content = $pretxt;
					foreach( $uploaded_ids as $id ) {
						$post_content .= str_replace( '#id', $id, wppa_opt( 'blog_it_shortcode' ) );
					}
					$post_content .= $posttxt;

					$post = array( 'post_title' => $title, 'post_content' => $post_content, 'post_status' => $status );
					$post = sanitize_post( $post, 'db' );

					$post_id = wp_insert_post( $post );
					if ( $post_id > 0 ) {
						$blogged = true;
					}
					else {
						$blg_failed = true;
					}
				}

				// Alert text for upload
				$m = ' ' . esc_js( sprintf( _n( '%d photo successfully uploaded', '%d photos successfully uploaded', $done, 'wp-photo-album-plus' ), $done ) ) . '.';
				if ( wppa_opt( 'fe_alert' ) == 'upcre' || wppa_opt( 'fe_alert' ) == 'all' ) {
					$alert .= $m;
				}
				$upload_message .= $m;

				// ADD POINTS
				$points = wppa_opt( 'cp_points_upload' ) * $done;
				$bret = wppa_add_credit_points( $points, __( 'Photo upload' ,'wp-photo-album-plus' ) );

				// Alert text for points
				$m = ' ' . esc_js( sprintf( __( '%s points added' ,'wp-photo-album-plus' ), $points ) ) . '.';
				if ( $bret && wppa_opt( 'fe_alert' ) != '-none-' ) {
					$alert .= $m;
				}
				elseif( $bret ) {
					$upload_message .= $m;
				}

				// Alert text for blogged
				if ( $blogged or $blog_failed ) {
					if ( $status == 'pending' ) {
						$m = ' ' . __( 'Your post is awaiting moderation.', 'wp-photo-album-plus' );
					}
					else {
						$m = ' ' . __( 'Your post is published.', 'wp-photo-album-plus' );
					}
					if ( $blogged && ( wppa_opt( 'fe_alert' ) == 'blog' || wppa_opt( 'fe_alert' ) == 'all' ) ) {
						$alert .= $m;
						$upload_message .= $m;
					}

					if ( $blog_failed ) {
						$m = ' ' . __( 'Blog failed', 'wp-photo-album-plus' );
						$alert .= $m;
						$upload_message .= $m;
					}
				}
			}

			// Alert text for failed upload
			if ( $fail ) {
				if ( ! $done ) {
					$m = ' ' . __( 'Upload failed', 'wp-photo-album-plus' ) . '.';
				}
				else {
					$m = ' ' . sprintf( _n( '%d upload failed', '%d uploads failed', $fail, 'wp-photo-album-plus' ), $fail ) . '.';
				}
				$alert .= $m;
				$upload_message .= $m;
			}

			// Clean alert text
			$alert = trim( $alert );

			// Add link to blogpost
			if ( $blogged ) {
				$upload_message .=
				'<br><a style="font-size:1.25em;font-weight:bold;cursor:pointer" href="' . get_permalink( $post_id ) . '">' . __( 'Visit blog post', 'wp-photo-album-plus' ) . '</a>';
			}

			// Output
			wppa_echo( $upload_message );

			// Alert only when requested or fail
			if ( wppa_opt( 'fe_alert' ) != '-none-' || $fail ) {
				wppa_alert( $alert, $reload );
			}
		}
	}

	// Do Edit
	if ( $may_edit ) {

		if ( wppa_get( 'albumeditsubmit' ) ) {

			// Get album id
			$alb = wppa_get( 'albumeditid' );
			if ( ! $alb || ! wppa_album_exists( $alb ) ) {
				die( 'Security check failure' );
			}

			// Valid request?
			if ( ! wp_verify_nonce( wppa_get( 'albumeditnonce' ), 'wppa-nonce_'.$alb ) ) {
				die( 'Security check failure' );
			}

			// Name
			$name 			= wppa_get( 'albumeditname' );
			$name 			= trim( strip_tags( $name ) );
			if ( ! $name ) {	// Empty album name is not allowed
				$name = 'Album-#'.$alb;
			}

			// Description
			$description 	= wppa_get( 'albumeditdesc', '', 'html' );

			// Custom data
			$custom_data = wppa_unserialize( wppa_get_album_item( $alb, 'custom' ) );
			if ( ! is_array( $custom_data ) ) {
				$custom_data = array( '', '', '', '', '', '', '', '', '', '' );
			}

			$idx = '0';
			while ( $idx < '10' ) {
				$value = wppa_get( 'custom_' . $idx, '', 'html' );
				if ( $value ) {

					$custom_data[$idx] = wppa_sanitize_custom_field( $value );
				}
				$idx++;
			}
			$custom = serialize( $custom_data );

			// Update
			wppa_update_album( $alb, ['name' => $name, 'description' => $description, 'custom' => $custom] );
			wppa_create_pl_htaccess();
		}
	}
}

// Subroutine to upload one file in the frontend
function wppa_do_fe_upload_housekeeping( $file, $alb ) {
global $wpdb;
global $wppa_supported_video_extensions;
global $wppa_supported_audio_extensions;
global $wppa_alert;

	// Log upload attempt
	wppa_log( 'Upl', 'FE Upload attempt of file ' . sanitize_file_name( $file['name'] ) . ', size=' . filesize( $file['tmp_name'] ) );

	// Logged out and not allowed?
	if ( ! is_user_logged_in() ) {
		$wppa_alert .= esc_js( __( 'Illegal attempt to upload', 'wp-photo-album-plus' ) ) . '.';
		return false;
	}

	$album = wppa_cache_album( $alb );

	// Legal here?
	if ( ! wppa_allow_uploads( $alb ) || ! wppa_allow_user_uploads( $alb ) ) {
		$wppa_alert .= esc_js( __( 'Max uploads reached', 'wp-photo-album-plus' ) ) . '.';
		return false;
	}

	// Find the filename
	$filename = wppa_sanitize_file_name( basename( $file['name'] ) );
	$filename = wppa_strip_ext( $filename );

	// See if this filename with any extension already exists in this album
	$id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE filename LIKE %s AND album = %s", $filename . '.%', $alb ) );

	// Addition to an av item?
	if ( $id ) {
		$is_av = wppa_get_photo_item( $id, 'ext' ) == 'xxx';
	}
	else {
		$is_av = false;
	}

	$status = wppa_opt( 'status_new' );
	if ( wppa_switch( 'upload_moderate' ) && ! current_user_can( 'wppa_admin' ) ) {
		$status = 'pending';
	}
	if ( wppa_switch( 'fe_upload_private' ) ) {
		$status = 'private';
	}

	// Status may be overruled by uploading moderator
	if ( current_user_can( 'wppa_moderate' ) ) {
		$status = wppa_get( 'user-status', $status, 'text' );
	}

	// see if audio / video and process
	if (
		// Video?
		( wppa_switch( 'enable_video' ) && wppa_switch( 'user_upload_video_on' ) && in_array( strtolower( wppa_get_ext( $file['name'] ) ), $wppa_supported_video_extensions ) ) ||
		// Audio?
		( wppa_switch( 'enable_audio' ) && wppa_switch( 'user_upload_audio_on' ) && in_array( strtolower( wppa_get_ext( $file['name'] ) ), $wppa_supported_audio_extensions ) )
		) {

		$is_av = true;

		// Find the name
		if ( wppa_get( 'user-name' ) ) {
			$name = wppa_get( 'user-name' );
		}
		else {
			$name = $file['name'];
		}
		$name = wppa_sanitize_photo_name( $name );

		$filename .= '.xxx';

		// update entry
		if ( $id ) {
			wppa_update_photo( $id, ['ext' => 'xxx', 'filename' => $filename] );
		}

		// Add new entry
		if ( ! $id ) {

			$desc = wppa_get( 'user-desc', '', 'html' );
			if ( ! $desc && wppa_switch( 'apply_newphoto_desc_user' ) ) {
				$desc = wppa_opt( 'newphoto_description' );
			}
			$id = wppa_create_photo_entry( array( 	'album' 		=> $alb,
													'filename' 		=> $filename,
													'ext' 			=> 'xxx',
													'name' 			=> $name,
													'status' 		=> $status,
													'description' 	=> $desc,
												) );

			if ( ! $id ) {
				$wppa_alert .= esc_js( __( 'Could not insert media into db.', 'wp-photo-album-plus' ) );
				return false;
			}
		}

		// Housekeeping
		wppa_update_album( $alb );
		wppa_verify_treecounts_a( $alb );
		wppa_flush_upldr_cache( 'photoid', $id );

		// Add video filetype
		$ext 		= strtolower( wppa_get_ext( $file['name'] ) );
		$newpath 	= wppa_strip_ext( wppa_get_photo_path( $id, false ) ).'.'.$ext;

		wppa_copy( $file['tmp_name'], $newpath );

		// If it is a mp4, try to find the width and height
		$videox = 0;
		$videoy = 0;

		// Fix possible framsize if video
		wppa_fix_video_metadata( $id, 'av_add_front' );

		// Repair name if not standard
		if ( ! wppa_get( 'user-name' ) ) {
			wppa( 'unsanitized_filename', $file['name'] );
			wppa_set_default_name( $id, $file['name'] );
		}

		// tags
		wppa_fe_add_tags( $id );

		// custom
		wppa_fe_add_custom( $id );

		// Custom fields defaults
		wppa_set_default_custom( $id );

		// Done!
		return $id;
	}

	// If not already an existing audio / video; Forget the id from a previously found item with the same filename.
	if ( ! $is_av ) {
		$id = false;
	}

	// Is it a pdf and can we do pdfs?
	if ( strtolower( wppa_get_ext( $file['name'] ) ) == 'pdf' && wppa_switch( 'enable_pdf' ) && wppa_can_magick() ) {
		$ext = 'pdf';
	}

	// Is it an image?
	else {

		$imgsize = getimagesize( $file['tmp_name'] );
		if ( ! is_array( $imgsize ) ) {
			$wppa_alert .= esc_js( __( 'Uploaded file is not an image', 'wp-photo-album-plus' ) ) . '.';
			return false;
		}

		// Is it a supported image filetype?
		if ( $imgsize[2] != IMAGETYPE_GIF && $imgsize[2] != IMAGETYPE_JPEG && $imgsize[2] != IMAGETYPE_PNG && $imgsize[2] != 18 ) {
			$wppa_alert .= esc_js( sprintf( __( 'Only gif, jpg and png image files are supported. Returned info = %s.', 'wp-photo-album-plus' ), serialize( $imgsize ) ), false, false );
			return false;
		}

		// Is it not too small?
		$ms = wppa_opt( 'upload_frontend_minsize' );
		if ( $ms ) {	// Min size configured
			if ( $imgsize[0] < $ms && $imgsize[1] < $ms ) {
				$wppa_alert .= esc_js( sprintf( __( 'Uploaded file is smaller than the allowed minimum of %d pixels.', 'wp-photo-album-plus' ), $ms ) );
				return false;
			}
		}

		// Is it not too big?
		$ms = wppa_opt( 'upload_frontend_maxsize' );
		if ( $ms ) {	// Max size configured
			if ( $imgsize[0] > $ms || $imgsize[1] > $ms ) {
				$wppa_alert .= esc_js( sprintf( __( 'Uploaded file is larger than the allowed maximum of %d pixels.', 'wp-photo-album-plus' ), $ms ) );
				return false;
			}
		}

		// Check for already exists
		if ( wppa_switch( 'void_dups' ) ) {
			if ( wppa_is_file_duplicate_photo( wppa_sanitize_file_name( $file['name'] ), $alb ) ) {
				$wppa_alert .= esc_js( sprintf( __( 'Uploaded file %s already exists in this album.', 'wp-photo-album-plus' ), wppa_sanitize_file_name( $file['name'] ) ) );
				return false;
			}
		}

		// Find extension from mimetype
		switch( $imgsize[2] ) { 	// mime type
			case 1: $ext = 'gif'; break;
			case 2: $ext = 'jpg'; break;
			case 3: $ext = 'png'; break;
			case 18: $ext = 'webp'; break;
			default: $ext = ''; break;
		}
	}

	// Did the user supply a photoname?
	if ( wppa_get( 'user-name' ) ) {
		$name = wppa_get( 'user-name' );
	}
	else {
		$name = $file['name'];
	}

	// Sanitize input
	$name 		= wppa_sanitize_photo_name( $name );
	$desc 		= balanceTags( wppa_get( 'user-desc' ), true );

	// If BlogIt! and no descrption given, use name field - this is for the shortcode used: typ"mphoto"
	if ( ! $desc && wppa_get( 'blogit' ) ) {
		$desc = 'w#name';
	}

	// Misc data
	$linktarget = '_self';
	$filename 	= wppa_sanitize_file_name( $file['name'] );

	// Create new entry if this is not a posterfile
	if ( ! $is_av ) {
		$id = wppa_create_photo_entry( array( 'album' => $alb, 'ext' => $ext, 'name' => $name, 'description' => $desc, 'status' => $status, 'filename' => $filename, ) );
	}

	if ( ! $id ) {
		$wppa_alert .= esc_js( __( 'Could not insert photo into db.', 'wp-photo-album-plus' ) );
		return false;
	}
	else {
		wppa_save_source( $file['tmp_name'], $filename, $alb );
		wppa_make_o1_source( $id );
		wppa_update_album( $alb );
		wppa_invalidate_treecounts( $alb );
		wppa_verify_treecounts_a( $alb );
		wppa_flush_upldr_cache( 'photoid', $id );
	}
	$source_file = $file['tmp_name'];
	$o1_path = wppa_get_o1_source_path( $id );
	$s_path = wppa_get_source_path( $id );
	if ( is_file( $o1_path ) ) {
		$source_file = $o1_path;
	}
	elseif ( is_file( $s_path ) ) {
		$source_file = $s_path;
	}
	if ( wppa_make_the_photo_files( $source_file, $id, $ext, ! wppa_switch( 'watermark_thumbs' ) ) ) {

		// Repair photoname if not standard
		if ( ! wppa_get( 'user-name' ) ) {
			wppa_set_default_name( $id );
		}

		// Custom data
		wppa_fe_add_custom( $id );

		// Add tags
		wppa_fe_add_tags( $id );

		// and add watermark ( optionally ) to fullsize image only
		wppa_add_watermark( $id );

		// Also to thumbnail?
		if ( wppa_switch( 'watermark_thumbs' ) ) {
			wppa_create_thumbnail( $id );	// create new thumb
		}

		// Is it a default coverimage?
		wppa_check_coverimage( $id );

		// If mp4, try ro find the framesize
		wppa_fix_video_metadata( $id, 'single_front' );

		// Mail
		if ( wppa_get_photo_item( $id, 'status' ) == 'pending' ) {
			wppa_schedule_mailinglist( 'moderatephoto', 0, $id );
		}
		else {
			wppa_schedule_mailinglist( 'feuploadnotify', 0, $id, 0, '', 0, 300 );
		}

		// Do pdf postprocessing
		wppa_pdf_postprocess( $id );

		// Default tags and custom
		wppa_set_default_tags( $id );
		wppa_set_default_custom( $id );

		return $id;
	}

	return false;
}

function wppa_fe_add_tags( $id ) {

	// Default tags
	wppa_set_default_tags( $id );

	// Custom tags
	$tags = wppa_get_photo_item( $id, 'tags' );
	$oldt = $tags;
	for ( $i = '1'; $i < '4'; $i++ ) {
		$dt = wppa_get( 'user-tags-' . $i, null, 'arraytxt' );
		if ( $dt ) {	// A (multi) selection out of 4 selectionboxes of existing tags
			$tags .= ',' . implode( ',', $dt );
		}
	}
	if ( wppa_get( 'new-tags' ) ) {	// New tags

		$tags .= ',' . wppa_get( 'new-tags' );
	}

	$tags = urldecode( $tags );
	$tags = wppa_sanitize_tags( str_replace( array( '\'', '"' ), ',', wppa_filter_iptc( wppa_filter_exif( $tags, $id ), $id ) ) );

	if ( $tags != $oldt ) {					// Added tag(s)
		wppa_update_photo( $id, ['tags' => $tags] );
	}

	// Tags
	if ( $tags ) {
		wppa_clear_taglist();			// Forces recreation
	}
}

function wppa_fe_add_custom( $id ) {

	if ( wppa_switch( 'fe_custom_fields' ) ) {
		$custom_data = array( '', '', '', '', '', '', '', '', '', '' );
		for ( $i = '0'; $i < '10' ; $i++ ) {
			$cd = wppa_get( 'wppa-user-custom-' . $i, '', 'html' );
			if ( $cd ) {
				$custom_data[$i] = strip_tags( $cd );
			}
		}
		wppa_update_photo( $id, ['custom' => serialize( $custom_data )] );
	}
}

function wppa_normalize_quotes( $xtext ) {

	$text = html_entity_decode( $xtext );
	$result = '';
	while ( $text ) {
		$char = substr( $text, 0, 1 );
		$text = substr( $text, 1 );
		switch ( $char ) {
			case '`':	// grave
			case '�':	// acute
				$result .= "'";
				break;
			case '�':	// double grave
			case '�':	// double acute
				$result .= '"';
				break;
			case '&':
				if ( substr( $text, 0, 5 ) == '#039;' ) {	// quote
					$result .= "'";
					$text = substr( $text, 5 );
				}
				elseif ( substr( $text, 0, 5 ) == '#034;' ) {	// double quote
					$result .= "'";
					$text = substr( $text, 5 );
				}
				elseif ( substr( $text, 0, 6 ) == '#8216;' || substr( $text, 0, 6 ) == '#8217;' ) {	// grave || acute
					$result .= "'";
					$text = substr( $text, 6 );
				}
				elseif ( substr( $text, 0, 6 ) == '#8220;' || substr( $text, 0, 6 ) == '#8221;' ) {	// double grave || double acute
					$result .= '"';
					$text = substr( $text, 6 );
				}
				break;
			default:
				$result .= $char;
				break;
		}
	}
	return $result;
}

// Find the search results. For use in a page template to show the search results. See ./theme/search.php
function wppa_have_photos( $xwidth = '0' ) {

	if ( ! is_search() ) return false;
	$width = $xwidth ? $xwidth : '';

	wppa( 'is_combinedsearch', true );

	wppa( 'searchresults', wppa_albums( '', '', $width ) );
	wppa( 'any', strlen( wppa( 'searchresults' ) ) > 0 );
	return wppa( 'any' );
}

// Display the searchresults. For use in a page template to show the search results. See ./theme/search.php
function wppa_the_photos() {

	if ( wppa( 'any' ) ) wppa_echo( wppa( 'searchresults' ) );
}

// Decide if a thumbnail photo file can be used for a requested display
function wppa_use_thumb_file( $id, $width = '0', $height = '0' ) {

	if ( ! wppa_switch( 'use_thumbs_if_fit' ) ) return false;
	if ( $width <= 1.0 && $height <= 1.0 ) return false;	// should give at least one dimension and not when fractional

	$file = wppa_get_thumb_path( $id );
	if ( file_exists( $file ) ) {
		$size = wppa_get_imagexy( $id, 'thumb' );
	}
	else return false;

	if ( ! is_array( $size ) ) return false;
	if ( $width > 0 && $size[0] < $width ) return false;
	if ( $height > 0 && $size[1] < $height ) return false;

	return true;
}

// Compute time to wait for time limited uploads
function wppa_time_to_wait_html( $album, $user = false ) {
global $wpdb;

	if ( ! $album && ! $user ) return '0';

	if ( $user ) {
		$limits = wppa_get_user_upload_limits();
	}
	else {
		$limits = $wpdb->get_var( $wpdb->prepare( "SELECT upload_limit FROM $wpdb->wppa_albums WHERE id = %s", $album ) );
	}
	$temp = explode( '/', $limits );
	$limit_max  = isset( $temp[0] ) ? $temp[0] : '0';
	$limit_time = isset( $temp[1] ) ? $temp[1] : '0';

	$result = '';

	if ( ! $limit_max || ! $limit_time ) return $result;

	if ( $user ) {
		$owner = wppa_get_user( 'login' );
		$last_upload_time = $wpdb->get_var( $wpdb->prepare( "SELECT timestamp FROM $wpdb->wppa_photos WHERE owner = %s ORDER BY timestamp DESC LIMIT 1", $owner ) );
	}
	else {
		$last_upload_time = $wpdb->get_var( $wpdb->prepare( "SELECT timestamp FROM $wpdb->wppa_photos WHERE album = %s ORDER BY timestamp DESC LIMIT 1", $album ) );
	}
	$timnow = time();

	// For simplicity: a year is 364 days = 52 weeks, we skip the months
	$seconds = array( 'min' => '60', 'hour' => '3600', 'day' => '86400', 'week' => '604800', 'month' => '2592000', 'year' => '31449600' );
	$deltatim = $last_upload_time + $limit_time - $timnow;

	$temp    = $deltatim;
	$weeks   = floor( $temp / $seconds['week'] );
	$temp    = $temp % $seconds['week'];
	$days    = floor( $temp / $seconds['day'] );
	$temp    = $temp % $seconds['day'];
	$hours   = floor( $temp / $seconds['hour'] );
	$temp    = $temp % $seconds['hour'];
	$mins    = floor( $temp / $seconds['min'] );
	$secs    = $temp % $seconds['min'];

	$switch = ( $weeks > '0' );

	$string = __( 'You can upload after', 'wp-photo-album-plus' ).' ';

	if ( $weeks || $switch ) {
		$string .= sprintf( _n( '%d week', '%d weeks', $weeks, 'wp-photo-album-plus' ), $weeks ).', ';
		$switch = true;
	}
	if ( $days  || $switch ) {
		$string .= sprintf( _n( '%d day', '%d days', $days, 'wp-photo-album-plus' ), $days ).', ';
		$switch = true;
	}
	if ( $hours || $switch ) {
		$string .= sprintf( _n( '%d hour', '%d hours', $hours, 'wp-photo-album-plus' ), $hours ).', ';
		$switch = true;
	}
	if ( $mins  || $switch ) {
		$string .= sprintf( _n( '%d minute', '%d minutes', $mins, 'wp-photo-album-plus' ), $mins ).', ';
		$switch = true;
	}
	if ( $switch ) {
		$string .= sprintf( _n( '%d second', '%d seconds', $secs, 'wp-photo-album-plus' ), $secs );
	}
	$string .= '.';
	$result = '<span style="font-size:9px;"> '.$string.'</span>';
	return $result;
}

// Get the title to be used for lightbox links == text under the lightbox image
function wppa_get_lbtitle( $type, $id ) {

	$thumb 	= wppa_cache_photo( $id );
	$alb 	= $thumb['album'];

	$do_download = wppa_switch( 'art_monkey_on' ) && wppa_switch( 'art_monkey_lightbox' );
	if ( $type == 'xphoto' ) $type = 'mphoto';

	$do_name 	= wppa_is_item_displayable( $alb, 'name', 'ovl_name' );
	$do_desc 	= wppa_is_item_displayable( $alb, 'description', 'ovl_desc' );
	$do_rating 	= wppa_is_item_displayable( $alb, 'rating', 'ovl_rating' );

	$do_sm 			= wppa_switch( 'share_on_lightbox' );

	$dl_name = wppa_is_pdf( $id ) ? wppa_get_photo_item( $id, 'filename' ) : wppa_get_photo_name( $id, array( 	'addowner' 		=> wppa_switch( 'ovl_add_owner' ),
																												'showname' 		=> wppa_switch( 'ovl_name' ),
																												'nobpdomain' 	=> wppa_opt( 'art_monkey_display' ) == 'button' && $do_download,
																											) );

	$result = '';
	if ( $do_download ) {
		$result .= wppa_get_download_html( $id, 'lightbox', __( 'Download', 'wp-photo-album-plus' ) . ' ' . wppa_strip_ext( $thumb['filename'] ) );
	}
	else {
		if ( $do_name ) {
			$result .= wppa_get_photo_name( $id, array( 'addowner' => wppa_switch( 'ovl_add_owner' ), 'showname' => wppa_switch( 'ovl_name' ) ) );
		}
	}
	if ( $do_name && $do_desc ) $result .= '<br>';
	if ( $do_desc ) $result .= wppa_get_photo_desc( $thumb['id'] );
	if ( ( $do_name || $do_desc ) && $do_sm ) $result .= '<br>';

	if ( $do_rating ) {
		if ( wppa_opt( 'rating_max' ) != '1' && wppa_opt( 'rating_display_type' ) == 'graphic' ) {
			$result .= wppa_get_rating_range_html( $id, true );
		}
		elseif ( wppa_opt( 'rating_display_type' ) == 'likes' ) {
			$result .= wppa_get_slide_rating_vote_only( 'always', $id, 'is_lightbox' );
		}
	}

	if ( $do_sm ) $result .= wppa_get_share_html( $id, 'lightbox' );

	$result = esc_attr( $result );
	return $result;
}

function wppa_zoom_in( $id ) {

	if ( $id === false ) return '';

	if ( wppa_switch( 'show_zoomin' ) ) {
		if ( wppa_opt( 'magnifier' ) ) {
			return __( 'Zoom in', 'wp-photo-album-plus' );
		}
		else {
			return esc_attr( stripslashes( wppa_get_photo_name( $id ) ) );
		}
	}
	else return '';
}

// Test if rating is one per period and period not expired yet
function wppa_get_rating_wait_text( $id ) {
global $wpdb;

	if ( is_user_logged_in() ) {
		$userid = wppa_get_user_id();
		$my_youngest_rating_dtm = $wpdb->get_var( $wpdb->prepare( "SELECT timestamp FROM $wpdb->wppa_rating WHERE photo = %d AND userid = %d ORDER BY timestamp DESC LIMIT 1", $id, $userid ) );
	}
	else {
		$userip = wppa_get_user_ip();
		$my_youngest_rating_dtm = $wpdb->get_var( $wpdb->prepare( "SELECT timestamp FROM $wpdb->wppa_rating WHERE photo = %d AND ip = %s ORDER BY timestamp DESC LIMIT 1", $id, $userip ) );
	}

	if ( ! $my_youngest_rating_dtm ) return ''; 	// Not votes yet

	$period = wppa_opt( 'rating_dayly' );
	$wait_text = '';
	if ( $period ) {
		$time_to_wait = $my_youngest_rating_dtm + $period - time();
		if ( $time_to_wait > 0 ) {
			$t = $time_to_wait;
			$d = floor( $t / (24*3600) );
			$t = $t % (24*3600);
			$h = floor( $t / 3600 );
			$t = $t % 3600;
			$m = floor( $t / 60 );
			$t = $t % 60;
			$s = $t;
			if ( $time_to_wait > (24*3600) ) {
				$wait_text = sprintf( __( 'You can vote again after %s days, %s hours, %s minutes and %s seconds', 'wp-photo-album-plus' ), $d, $h, $m, $s );
			}
			elseif ( $time_to_wait > 3600 ) {
				$wait_text = sprintf( __( 'You can vote again after %s hours, %s minutes and %s seconds', 'wp-photo-album-plus' ), $h, $m, $s );
			}
			else {
				$wait_text = sprintf( __( 'You can vote again after %s minutes and %s seconds', 'wp-photo-album-plus' ), $m, $s );
			}
		}
	}
	return $wait_text;
}

// Get comment status according to wp discussion rules
// Returns 'approved', 'pending' or 'spam'
function wppa_check_comment( $user, $email, $comment ) {
global $wpdb;

	// Some other required data
	$user_ip 	= $_SERVER["REMOTE_ADDR"];
	$ser_agent 	= $_SERVER["HTTP_USER_AGENT"];

    // Check for the number of external links if a max allowed number is set.
    if ( $max_links = wppa_get_option( 'comment_max_links' ) ) {
        $num_links = preg_match_all( '/<a [^>]*href/i', $comment, $out );

        // Filters the number of links found in a comment.
        $num_links = apply_filters( 'comment_max_links_url', $num_links, '', $comment );


        // If the number of links in the comment exceeds the allowed amount, fail the check
        if ( $num_links >= $max_links ) {
			wppa_log( 'Com', 'Comment {i}' . $comment . '{/i} held for moderation due to too many links' );
            return 'pending';
		}
    }

    $mod_keys = trim( wppa_get_option( 'moderation_keys' ) );

    // If moderation 'keys' (keywords) are set, process them.
    if ( ! empty( $mod_keys ) ) {
        $words = explode( "\n", $mod_keys );

        foreach ( (array) $words as $word) {
            $word = trim($word);

            // Skip empty lines.
            if ( empty( $word ) )
                continue;

            // Do some escaping magic so that '#' (number of) characters in the spam
            // words don't break things:
            $word = preg_quote( $word, '#' );

            // Check the comment fields for moderation keywords. If any are found,
            // fail the check
            $pattern = "#$word#i";
            if ( preg_match( $pattern, $user ) ||
				 preg_match( $pattern, $email ) ||
				 preg_match( $pattern, $comment ) ||
				 preg_match( $pattern, $user_ip ) ||
				 preg_match( $pattern, $user_agent ) ) {
				wppa_log( 'Com', 'Comment {i}' . $comment . '{/i} held for moderation due to moderation key found' );
				return 'pending';
			}
        }
    }

    $blacklist_keys = trim( wppa_get_option( 'blacklist_keys' ) );

    // If blacklist 'keys' (keywords) are set, process them.
    if ( ! empty( $blacklist_keys ) ) {
        $words = explode( "\n", $blacklist_keys );

        foreach ( (array) $words as $word) {
            $word = trim($word);

            // Skip empty lines.
            if ( empty( $word ) )
                continue;

            // Do some escaping magic so that '#' (number of) characters in the spam
            // words don't break things:
            $word = preg_quote( $word, '#' );

            // Check the comment fields for moderation keywords. If any are found,
            // fail the check for the given field by returning false.
            $pattern = "#$word#i";
            if ( preg_match( $pattern, $user ) ||
				 preg_match( $pattern, $email ) ||
				 preg_match( $pattern, $comment ) ||
				 preg_match( $pattern, $user_ip ) ||
				 preg_match( $pattern, $user_agent ) ) {
				wppa_log( 'Com', 'Comment {i}' . $comment . '{/i} marked as spam due to blacklist (1)' );
				return 'spam';
			}
        }
    }

    /*
     * Check if the option to approve comments by previously-approved authors is enabled.
     *
     * If it is enabled, check whether the comment author has a previously-approved comment,
     * as well as whether there are any moderation keywords (if set) present in the author
     * email address. If both checks pass, return true. Otherwise, return false.
     */
    if ( 1 == wppa_get_option( 'comment_whitelist' ) ) {
        if ( $user != '' && $email != '' ) {
            $comment_user = wppa_get_user_by( 'email', wp_unslash( $email ) );
            if ( ! empty( $comment_user->ID ) ) {
                $ok_to_comment =
					$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->comments WHERE user_id = %d AND comment_approved = '1'", $comment_user->ID ) ) +
					$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_comments WHERE user = %s AND status = 'approved'", $user ) );
            } else {
                $ok_to_comment =
					$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_author = %s AND comment_author_email = %s and comment_approved = '1' LIMIT 1", $user, $email ) ) +
					$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_comments WHERE email = %s AND status = 'approved'", $email ) );
            }
            if ( ( $ok_to_comment >= 1 ) && ( empty( $mod_keys ) || false === strpos( $email, $mod_keys ) ) && ( empty( $blacklist_keys ) || false === strpos( $email, $blacklist_keys ) ) ) {
				wppa_log( 'Com', 'Comment {i}' . $comment . '{/i} approved due to whitelist' );
				return 'approved';
			}
            elseif ( ! empty( $blacklist_keys ) && strpos( $email, $blacklist_keys ) ) {
				wppa_log( 'Com', 'Comment {i}' . $comment . '{/i} marked as spam due to blacklist (2)' );
				return 'spam';
			}
			else {
				wppa_log( 'Com', 'Comment {i}' . $comment . '{/i} held for moderation due to not yet whitelisted' );
                return 'pending';
			}
        }
		else {
			wppa_log( 'Com', 'Comment {i}' . $comment . '{/i} held for moderation (2)' );
            return 'pending';
        }
    }

	wppa_log( 'Com', 'Comment {i}' . $comment . '{/i} approved (2)' );

    return 'approved';
}

function wppa_occur_timer( $key = '', $title = '', $cached = false, $delay = false ) {
static $queries;
static $time;
global $wppa_no_timer;
global $wppa_current_shortcode;

	// Do they want us?
	if ( $wppa_no_timer ) return '';

	switch( $key ) {
		case 'init':
			$queries = get_num_queries();
			$time = microtime( true );
			break;

		case 'show':
			$nqueries = get_num_queries() - $queries;
			$ntime = microtime( true ) - $time;
			$result = "\n" .
				'<!-- End ' . $wppa_current_shortcode . ' ' . $title . ' ' .
				sprintf( '%d queries in %3.1f ms. at %s. Max mem: %6.2f Mb.',
					$nqueries,
					$ntime * 1000,
					wppa_local_date( wppa_get_option( 'time_format' ) ),
					memory_get_peak_usage(true)/(1024*1024)) .
				( $cached ? ' (cached) ' : ' ' ) .
				( $delay ? ' (delayed) ' : ' ' ) .
				'-->';
				wppa_log( 'tim', trim( $result, "\n<>" ) );
			return $result;
			break;

		default:
			wppa_log( 'err', 'Unimplemented key in wppa_occur_timer (' . $key . ')' );
			break;
	}
}

// Get the download url for a photo without version and hires if configured
function wppa_download_url( $id ) {

	wppa( 'no_ver', true );
	$result = wppa_switch( 'art_monkey_source' ) ? wppa_get_hires_url( $id ) : wppa_get_photo_url( $id );
	wppa( 'no_ver', false );

	return $result;
}

// Translate virtual album to runtime paramaters
function wppa_virt_album_to_runparms( $album ) {
global $wpdb;

	// We start with arg $albumm, so clear start_album first
	wppa( 'start_album', '' );

	if ( $album ) {

		// Album an int?
		if ( ! wppa_is_int( $album ) ) {

			// Album a keyword?
			if ( substr( $album, 0, 1 ) == '#' ) {
				wppa( 'is_virtual', true );
				$keyword = $album;
				if ( strpos( $keyword, ',' ) ) $keyword = substr( $keyword, 0, strpos( $keyword, ',' ) );
				switch ( $keyword ) {
					case '#last':				// Last upload
						$id = wppa_get_youngest_album_id();
						if ( wppa( 'is_cover' ) ) {	// To make sure the ordering sequence is ok.
							$temp = explode( ',', $album );
							if ( isset( $temp['1'] ) ) wppa( 'last_albums_parent', $temp['1'] );
							else wppa( 'last_albums_parent', '0' );
							if ( isset( $temp['2'] ) ) wppa( 'last_albums', $temp['2'] );
							else wppa( 'last_albums', false );
						}
						else {		// Ordering seq is not important, convert to album enum
							$temp = explode( ',', $album );
							if ( isset( $temp['1'] ) ) $parent = wppa_album_name_to_number( $temp['1'] );
							else $parent = '0';
							if ( $parent === false ) {
								return false;
							}
							if ( isset( $temp['2'] ) ) $limit = $temp['2'];
							else $limit = false;
							if ( $limit ) {
								if ( $parent ) {
									if ( $limit ) {
										$q = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
															  WHERE a_parent = %s
															  ORDER BY timestamp DESC
															  LIMIT %d", $parent, $limit );
									}
									else {
										$q = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
															  WHERE a_parent = %s
															  ORDER BY timestamp DESC", $parent );
									}
								}
								else {
									if ( $limit ) {
										$q = $wpdb->prepare( "SELECT * FROM $wpdb->wppa_albums
															  ORDER BY timestamp DESC
															  LIMIT %d", $limit );
									}
									else {
										$q = "SELECT * FROM $wpdb->wppa_albums
											  ORDER BY timestamp DESC";
									}
								}
								$albs = $wpdb->get_results( $q, ARRAY_A );
								wppa_cache_album( 'add', $albs );
								if ( is_array( $albs ) ) foreach ( array_keys( $albs ) as $key ) $albs[$key] = $albs[$key]['id'];
								$id = implode( '.', $albs );
							}
						}
						break;
					case '#topten':
						$temp = explode( ',', $album );
						$id = isset( $temp[1] ) ? $temp[1] : '0';
						$cnt = wppa_opt( 'topten_count' );
						if ( isset( $temp[2] ) ) {
							if ( $temp[2] > 0 ) {
								$cnt = $temp[2];
							}
						}
						wppa( 'topten_count', $cnt );
						wppa( 'is_topten', true );
						if ( wppa( 'is_cover' ) ) {
							return false;
						}
						if ( isset( $temp[3] ) ) {
							if ( $temp[3] == 'medals' ) {
								wppa( 'medals_only', true );
							}
						}
						break;
					case '#lasten':
						$temp = explode( ',', $album );
						$id = isset( $temp[1] ) ? $temp[1] : '0';
						wppa( 'lasten_count', isset( $temp[2] ) ? $temp[2] : wppa_opt( 'lasten_count' ) );
						wppa( 'is_lasten', true );

						// Limit to owner?
						if ( isset( $temp[3] ) ) {
							wppa( 'is_upldr', $temp[3] );
						}

						if ( wppa( 'is_cover' ) ) {
							return false;
						}
						break;
					case '#comten':
						$temp = explode( ',', $album );
						$id = isset( $temp[1] ) ? $temp[1] : '0';
						wppa( 'comten_count', isset( $temp[2] ) ? $temp[2] : wppa_opt( 'comten_count' ) );
						wppa( 'is_comten', true );
						if ( wppa( 'is_cover' ) ) {
							return false;
						}
						break;
					case '#featen':
						$temp = explode( ',', $album );
						$id = isset( $temp[1] ) ? $temp[1] : '0';
						wppa( 'featen_count', isset( $temp[2] ) ? $temp[2] : wppa_opt( 'featen_count' ) );
						wppa( 'is_featen', true );
						if ( wppa( 'is_cover' ) ) {
							return false;
						}
						break;
					case '#related':
						$temp = explode( ',', $album );
						$type = isset( $temp[1] ) ? $temp[1] : 'tags';	// tags is default type
						wppa( 'related_count', isset( $temp[2] ) ? $temp[2] : wppa_opt( 'related_count' ) );
						wppa( 'is_related', $type );

						$data = wppa_get_related_data();
						if ( $type == 'tags' ) {
							wppa( 'is_tag', $data );
						}
						if ( $type == 'desc' ) {
							wppa( 'src', true );
							wppa( 'searchstring', str_replace( ';', ',', $data ) );
							wppa( 'photos_only', true );
						}
						wppa( 'photos_only', true );
						$id = '0';
						break;
					case '#tags':

						// See if they did not use the #cat / #tags combination in wrong sequence order
						$seppos = strpos( $album, '|' );
						if ( $seppos !== false ) {
							wppa_out( 'Syntax error in shortcode attribute album=. Expected: album="#cat,...|#tags,...", seen: album="' . $album . '"' );
							wppa_reset_occurrance();
							return;
						}
						wppa( 'is_tag', wppa_sanitize_tags( substr( $album, 6 ), true, true ) );
						$id = '0';
						wppa( 'photos_only', true );
						break;
					case '#cat':

						// See if the #cat,cat|#tags,tag special case has been used
						$seppos = strpos( $album, '|' );
						if ( $seppos !== false ) {

							// Yes, process the second part, the #tags clause
							if ( substr( $album, $seppos, 7 ) != '|#tags,' ) {
								wppa_out( 'Syntax error in shortcode attribute album=. Expected: album="#cat,...|#tags,...", seen: album="' . $album . '"' );
								wppa_reset_occurrance();
								return false; // Forget this occurrance
							}
							wppa( 'is_tag', wppa_sanitize_tags( substr( $album, $seppos + 7 ), true ) );
							wppa( 'photos_only', true );
							wppa( 'start_album', substr( $album, 0, $seppos ) );
						}

						$cats = substr( $album, 5 );
						$cats = trim( wppa_sanitize_tags( $cats, true ), ',;' );

						wppa( 'is_cat', $cats );

						if ( ! $cats ) {
							wppa_out( 'Missing cat #cat album spec: ' . $album );
							wppa_reset_occurrance();
							return false;	// Forget this occurrance
						}

						// Get all albums and cache its data
						$albs = $wpdb->get_results( "SELECT * FROM $wpdb->wppa_albums", ARRAY_A );
						wppa_cache_album( 'add', $albs );

						// $cats is not empty. If it contains a , all cats must be met ( AND case )
						// It may contain a ; any cat must be met

						// AND case
						if ( strpos( $cats, ',' ) !== false ) {

							// Do not accept a mix of , and ; Convert ; to ,
							$cats = str_replace( ';', ',', $cats );
							$cats = explode( ',', $cats );
							$id = '';

							if ( $albs ) foreach ( $albs as $alb ) {
								$albcats = explode( ',', $alb['cats'] );

								// Assume in
								$in = true;

								// Test all required cats against album cats array
								foreach( $cats as $cat ) {
									if ( ! in_array( $cat, $albcats, true ) ) {
										$in = false;
									}
								}

								if ( $in && wppa_is_int( $alb['id'] ) ) {
									$id .= $alb['id'].'.';
								}
							}
						}

						// OR case
						else {
							$cats = explode( ';', $cats );
							$id = '';

							if ( $albs ) foreach ( $albs as $alb ) {
								$albcats = explode( ',', $alb['cats'] );

								// Assume out
								$in = false;

								// Test all possible cats against album cats array
								foreach( $cats as $cat ) {
									if ( in_array( $cat, $albcats, true ) ) {
										$in = true;
									}
								}

								if ( $in && wppa_is_int( $alb['id'] ) ) {
									$id .= $alb['id'].'.';
								}
							}
						}

						// Remove possible trailing dot
						$id = rtrim( $id, '.' );

						// Nothing found?
						if ( ! $id ) {
							$id = '-9';
						}

						// Add children?
						if ( wppa_switch( 'cats_inherit' ) ) {
							$id = wppa_alb_to_enum_children( $id );
						}
						break;
					case '#owner':
						$temp = explode( ',', $album );
						$owner = isset( $temp[1] ) ? $temp[1] : '';
						if ( $owner == '#me' ) {
							if ( is_user_logged_in() ) $owner = wppa_get_user();
							else {	// User not logged in, ignore shortcode
								return false;
							}
						}
						if ( ! $owner ) {
							wppa_out( 'Missing owner in #owner album spec: ' . $album );
							return false;
						}
						$parent = isset( $temp[2] ) ? wppa_album_name_to_number( $temp[2] ) : '0';
						if ( $parent === false ) return;
						if ( ! $parent ) $parent = '-1.0';
						if ( $parent ) {	// Valid parent spec
							$parent_arr = explode( '.', wppa_expand_enum( $parent ) );

							// Clean parent array from non existing albums
							$allalbs = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_albums" );
							$parent_arr = array_intersect( array_merge( array('0','-1'), $allalbs ), $parent_arr );
							$parent = implode( '.', $parent_arr );
							$id = wppa_alb_to_enum_children( $parent );

							// Verify all albums are owned by $owner and are directly under a parent album
							$id = wppa_expand_enum( $id );
							$albs = explode( '.', $id );
							if ( $albs ) foreach( array_keys( $albs ) as $idx ) {
								if (
									( wppa_get_album_item( $albs[$idx], 'owner' ) != $owner ) ||
									( ! in_array( wppa_get_album_item( $albs[$idx], 'a_parent' ), $parent_arr ) )
									) {
									unset( $albs[$idx] );
								}
							}
							$id = implode ( '.', $albs );

							if ( ! $id ) {
								$id = '-9';	// Force nothing found
							}
						}
						wppa( 'is_owner', $owner );
						break;
					case '#upldr':
						$temp = explode( ',', $album );
						$owner = isset( $temp[1] ) ? $temp[1] : '';
						if ( $owner == '#me' ) {
							if ( is_user_logged_in() ) $owner = wppa_get_user();
							else {	// User not logged in, ignore shortcode
								return false;
							}
						}
						if ( ! $owner ) {
							wppa_out( 'Missing owner in #upldr album spec: ' . $album );
							return false;
						}
						$parent = isset( $temp[2] ) ? wppa_album_name_to_number( $temp[2] ) : '0';
						if ( $parent === false ) return;	// parent specified but not a valid value
						if ( $parent ) {	// Valid parent spec
							$id = wppa_alb_to_enum_children( wppa_expand_enum( $parent ) );
							if ( ! $id ) {
								return false;
							}
						}
						else {				// No parent spec
							$id = '0';
						}
						wppa( 'is_upldr', $owner );
						wppa( 'photos_only', true );
						break;
					case '#potdhis':
						wppa( 'is_potdhis', true );
						wppa( 'photos_only', true );
						wppa( 'start_album', '' );
						break;
					case '#all':
						$id = '-2';
						break;
					default:
						wppa_out( 'Unrecognized album keyword found: ' . $album );
						return false;
				}
				wppa( 'start_album', $id );
			}

			// Album not int and not a #keyword
			else {

				// See if the album id is a name
				if ( substr( $album, 0, 1 ) == '$' ) {
					$album = wppa_album_name_to_number( $album );
				}

				// Or a crypt
				if ( ! wppa_is_enum( $album ) ) {
					$album = wppa_decode_album( $album, false );
				}

				// Or a valid enum
				if ( wppa_is_enum( $album ) ) {

						wppa( 'start_album', $album );
						return true;
				}

				// If no enum, album must be numeric
				elseif ( ! is_numeric( $album ) ) {
					return wppa_stx_err( 'Unrecognized Album identification found: ' . $album );
				}

				// Album must exist
				elseif ( $album > '0' ) {	// -2 is #all
					if ( ! wppa_album_exists( $album ) ) {
						return wppa_stx_err( 'Album does not exist: ' . $album );
					}
				}

				wppa( 'start_album', $album );

			}
		}

		// Album is an int
		else {
			wppa( 'start_album', $album );
		}
	}

	// Album empty, maybe parent given
	else {

		$p = wppa( 'last_albums_parent' );
		if ( ! wppa_is_int( $p && ! wppa_is_enum( $p )  ) ) {

			$p = wppa_decode_album( $p, false );
			wppa( 'last_albums_parent', $p );
		}
	}

	return true;
}

function wppa_virt_photo_to_runparms( $photo ) {

	if ( $photo && ! is_numeric( $photo ) ) {

		if ( substr( $photo, 0, 1 ) == '#' ) {		// Keyword
			switch ( substr( $photo, 0, 5 ) ) {
				case '#potd':				// Photo of the day
					$t = wppa_get_potd();
					if ( is_array( $t ) ) {
						$id = $t['id'];
						wppa( 'start_photo', $id );
					}
					else {
						wppa_out( 'Photo of the day not found' );
						return false;
					}
					break;
				case '#last':				// Last upload
					$t = explode( ',', $photo );

					// Last from album??
					if ( isset( $t[1] ) && is_numeric( $t[1] ) ) {
						$id = wppa_get_youngest_photo_id( $t[1] );
					}
					// Last from album by album="" shortcode arg?
					elseif ( wppa( 'start_album' ) ) {
						$id = wppa_get_youngest_photo_id( wppa( 'start_album' ) );
					}
					// No, last from system
					else {
						$id = wppa_get_youngest_photo_id();
					}
					wppa( 'start_photo', $id );
					break;
				default:
					wppa_out( 'Unrecognized photo keyword found: ' . $photo );
					wppa_reset_occurrance();
					return;	// Forget this occurrance
			}
			wppa( 'single_photo', $id );
		}

		// See if the photo id is a name and convert it if possible
		if ( substr( $photo, 0, 1 ) == '$' ) {		// Name preceeded by $
			$photo = substr( $photo, 1 );

			$id = wppa_get_photo_id_by_name( $photo );

			if ( $id > '0' ) {
				wppa( 'single_photo', $id );
			}
			else {
				wppa_out( 'Photo name not found: ' . $photo );
				return false;
			}
		}
	}

	// Numeric
	else {
		wppa( 'single_photo', $photo );
	}

	return true;
}

// Add a page id to the album/photo info as used by
function wppa_add_usedby( $xid, $page, $where ) {
global $wpdb;

	// Check where
	if ( $where == 'photo' ) {
		$table = WPPA_PHOTOS;
	}
	elseif ( $where == 'album' ) {
		$table = WPPA_ALBUMS;
	}
	else {
		wppa_log( 'dbg', 'Wrong where in wppa_add_usedby()' );
		return;
	}

	// Single or enum?
	if ( wppa_is_posint( $xid ) ) {
		$ids = [$xid];
	}
	elseif ( wppa_is_enum( $xid ) ) {
		$ids = explode( '.', wppa_expand_enum( $xid ) );
	}

	// Do them all
	foreach( $ids as $id ) {

		// Get current data
		if ( $where == 'photo' ) {
			$value = wppa_get_photo_item( $id, 'usedby' );
		}
		elseif ( $where == 'album' ) {
			$value = wppa_get_album_item( $id, 'usedby' );
		}
		if ( $value === false ) continue; // Item does not exist

		// Make array
		$value_a = explode( '.', trim( $value, '.' ) );
		$value_a[] = strval( intval( $page ) );
		sort( $value_a );
		foreach( array_keys( $value_a ) as $key ) {
			if ( ! $value_a[$key] ) {
				unset( $value_a[$key] );
			}
		}
		$value_a = array_unique( $value_a );

		// Make string
		$result = '.' . implode( '.', $value_a ) . '.';

		// If modified: save
		if ( $result != $value ) {
			$query = $wpdb->prepare( "UPDATE $table SET usedby = %s WHERE id = %s", $result, $id );
			wppa_log( 'db', $query );
			$bret = $wpdb->query( $query );
			if ( ! $bret ) {
				wppa_log( 'err', 'Update usedby failed. Query was ' . $query . ' old value = ' . $value . ', new value = ' . $result );
			}
		}
	}
}

// Remove all page ids from album/photo info for post $ID
function wppa_remove_usedby( $ID ) {
global $wpdb;

	if ( ! wppa_is_posint( $ID ) ) {
		wppa_log( 'err', 'Illegal id in wppa_remove_usedby()' );
		return;
	}

	$wild = '%';
	$find = '.' . strval( $ID ) . '.';
	$like = $wild . $wpdb->esc_like( $find ) . $wild;

	$tables = [WPPA_ALBUMS, WPPA_PHOTOS];
	foreach( $tables as $table ) {
		$query = $wpdb->prepare( "SELECT id, usedby FROM $table where usedby LIKE %s", $like );
		wppa_log( 'db', $query );
		$items = $wpdb->get_results( $query, ARRAY_A );
		foreach( $items as $item ) {
			$value = $item['usedby'];
			$result = str_replace( '.'.$ID.'.', '.', $value );
			if ( $result == '.' ) $result = '';
			$query = $wpdb->prepare( "UPDATE $table SET usedby = %s WHERE id = %s", $result, $item['id'] );
			wppa_log( 'db', $query );
			$wpdb->query( $query );
		}
	}
}

function wppa_get_pan_control_height() {
	switch ( wppa_opt( 'panorama_control' ) ) {

		case 'all':
			$pancontrolheight = ( wppa( 'in_widget' ) ? wppa_opt( 'nav_icon_size_panorama' ) / 2 + 4: wppa_opt( 'nav_icon_size_panorama' ) );
			break;
		case 'mobile':
			if ( wppa_is_mobile() ) {
				$pancontrolheight = ( wppa( 'in_widget' ) ? wppa_opt( 'nav_icon_size_panorama' ) / 2 + 4: wppa_opt( 'nav_icon_size_panorama' ) );
			}
			else {
				$pancontrolheight = '0';
			}
			break;
		default: 	// case 'none':
			$pancontrolheight = '0';
			break;
	}
	return $pancontrolheight;
}
