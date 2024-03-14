<?php
/* wppa-utils.php
* Package: wp-photo-album-plus
*
* Contains low-level utility routines
* Version: 8.6.04.008
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

global $wppa_supported_photo_extensions;
$wppa_supported_photo_extensions = array( 'jpg', 'jpeg', 'png', 'gif', 'webp' );

global $wppa_supported_document_extensions;
$wppa_supported_document_extensions = array( 'pdf' );

// Get url in wppa dir
function wppa_url( $arg ) {
	return WPPA_URL . '/' . $arg;
}

// get url of thumb
function wppa_get_thumb_url( $id, $fix_poster_ext = true, $system = 'flat', $x = '0', $y = '0', $use_thumbfile = false ) {
global $blog_id;

	// Does photo exist?
	$thumb = wppa_cache_photo( $id );
	if ( ! $thumb ) return '';

	// Set owner if required
	wppa_set_owner_to_name( $id );

	$thumb = wppa_cache_photo( $id );

	// If thumbratio not default, do not use the cdn version
	if ( wppa_opt( 'thumb_aspect' ) != '0:0:none' ) {
		$use_thumbfile = true;
	}

	// If 360 or flat pano, do not use the cdn version
	if ( wppa_get_photo_item( $id, 'panorama' ) ) {
		$use_thumbfile = true;
	}

	// If in the cloud...
	$is_old = wppa_too_old_for_cloud( $id );
	if ( wppa_cdn( 'front' ) &&
		! wppa_is_multi( $id ) &&
		! $is_old &&
		! wppa_is_stereo( $id ) &&
		! wppa_is_pdf( $id ) &&
		! $use_thumbfile ) {

		// Only when size is given !! To prevent download of the fullsize image
		if ( $x && $y ) {

			switch ( wppa_cdn( 'front' ) ) {
				case 'local':
					$url = wppa_cdn_url( $id, $x, $y );
					if ( $url ) {
						return $url;
					}
					break;

				case 'cloudinary':
					$transform	= explode( ':', wppa_opt( 'thumb_aspect' ) );
					$t 			= 'limit';
					if ( $transform['2'] == 'clip' ) $t = 'fill';
					if ( $transform['2'] == 'padd' ) $t = 'pad,b_black';
					$q 			= wppa_opt( 'jpeg_quality' );
					$sizespec 	= ( $x && $y ) ? 'w_'.$x.',h_'.$y.',c_'.$t.',q_'.$q.'/' : '';
					$prefix 	= ( is_multisite() && ! WPPA_MULTISITE_GLOBAL ) ? $blog_id.'-' : '';
					$s = is_ssl() ? 's' : '';
					$url = 'http'.$s.'://res.cloudinary.com/'.wppa_get_option('wppa_cdn_cloud_name').'/image/upload/'.$sizespec.$prefix.$thumb['id'].'.'.$thumb['ext'];
					return $url;
					break;

				default:
					break;
			}
		}
	}

	// If the thumbnail file does not exist, try to make it
	if ( ! wppa_is_file( wppa_get_thumb_path( $id ) ) ) {
		wppa_create_thumbnail( $id );
	}

	if ( wppa_get_option('wppa_file_system') == 'flat' ) $system = 'flat';	// Have been converted, ignore argument
	if ( wppa_get_option('wppa_file_system') == 'tree' ) $system = 'tree';	// Have been converted, ignore argument

	if ( $system == 'tree' ) {
		$result = WPPA_UPLOAD_URL . '/thumbs/' . wppa_expand_id( $thumb['id'] ) . '.' . $thumb['ext'];
	}
	else {
		$result = WPPA_UPLOAD_URL . '/thumbs/' . $thumb['id'] . '.' . $thumb['ext'];
	}

	if ( $fix_poster_ext ) {
		$result = wppa_fix_poster_ext( $result, $thumb['id'] );
	}

	$result .= '?ver=' . wppa_get_option( 'wppa_thumb_version', '1' );

	return $result;
}

// Bump thumbnail version number
function wppa_bump_thumb_rev() {
	wppa_update_option('wppa_thumb_version', wppa_get_option('wppa_thumb_version', '1') + '1');
}

// get path of thumb
function wppa_get_thumb_path( $id, $fix_poster_ext = true, $system = 'flat' ) {

	$thumb = wppa_cache_photo( $id );
	if ( ! $thumb ) {
		return false;
	}

	if ( wppa_get_option( 'wppa_file_system' ) == 'flat' ) $system = 'flat';	// Has been converted, ignore argument
	if ( wppa_get_option( 'wppa_file_system' ) == 'tree' ) $system = 'tree';	// Has been converted, ignore argument

	if ( $system == 'tree' ) {
		$result = WPPA_UPLOAD_PATH.'/thumbs/'.wppa_expand_id($thumb['id'], true, false).'.'.$thumb['ext'];
	}
	else {
		$result = WPPA_UPLOAD_PATH.'/thumbs/'.$thumb['id'].'.'.$thumb['ext'];
	}

	if ( $fix_poster_ext ) {
		$result = wppa_fix_poster_ext( $result, $thumb['id'] );
	}

	return $result;
}

// get url of a full sized image
function wppa_get_photo_url( $id, $fix_poster_ext = true, $system = 'flat', $x = '0', $y = '0' ) {
global $blog_id;
global $wppa_supported_stereo_types;

	// Does photo exist?
	$thumb = wppa_cache_photo( $id );
	if ( ! $thumb ) return '';

 	// Set owner if required
	wppa_set_owner_to_name( $id );

	// Must re-get cached thumb
	$thumb = wppa_cache_photo( $id );

	// Feed returns thumb
	if ( is_feed() ) {
		return wppa_get_thumb_url( $id, true, $system );
	}

	// If in the cloud...
	$for_sm = wppa( 'for_sm' ); 				// Social media do not accept cloud images
	$is_old = wppa_too_old_for_cloud( $id );
	if ( wppa_cdn( 'front' ) && ! wppa_is_multi( $id ) && ! $is_old && ! wppa_is_stereo( $id ) && ! $for_sm && ! $thumb['magickstack'] && ! wppa_is_panorama( $id ) && ! wppa_is_pdf( $id ) ) {
		if ( $x && $y ) {		// Only when size is given
			switch ( wppa_cdn( 'front' ) ) {
				case 'local':
					$url = wppa_cdn_url( $id, $x, $y );
					if ( $url ) {
						return $url;
					}
					break;

				case 'cloudinary':
					$x = round($x);
					$y = round($y);
					$prefix 	= ( is_multisite() && ! WPPA_MULTISITE_GLOBAL ) ? $blog_id.'-' : '';
					$t 			= wppa_switch( 'enlarge') ? 'fit' : 'limit';
					$q 			= wppa_opt( 'jpeg_quality' );
					$sizespec 	= ( $x && $y ) ? 'w_'.$x.',h_'.$y.',c_'.$t.',q_'.$q.'/' : '';
					$s = is_ssl() ? 's' : '';
					$url = 'http'.$s.'://res.cloudinary.com/'.wppa_get_option('wppa_cdn_cloud_name').'/image/upload/'.$sizespec.$prefix.$thumb['id'].'.'.$thumb['ext'];
					return $url;
					break;

				default:
					break;
			}
		}
	}

	// Stereo?
	if ( wppa_is_stereo( $id ) ) {

		// Get type from cookie
		$st = wppa_get_cookie( 'stereotype', '_flat' );

		// Get glass from cookie
		$sg = ( wppa_get( 'stereoglass' ) == 'greenmagenta' ? 'gm' : 'rc' );

		// Create the file if not present
		if ( ! is_file( wppa_get_stereo_path( $id, $st, $sg ) ) ) {
			wppa_create_stereo_image( $id, $st, $sg );
		}

		// Build the url
		if ( $st == '_flat' ) {
			$url = WPPA_UPLOAD_URL . '/stereo/' . $id . '-' . $st . '.jpg' . '?ver=' . wppa_get_option( 'wppa_photo_version', '1' );
		}
		else {
			$url = WPPA_UPLOAD_URL . '/stereo/' . $id . '-' . $st . '-' . $sg . '.jpg' . '?ver=' . wppa_get_option( 'wppa_photo_version', '1' );
		}

		// Done
		return $url;
	}

	if ( wppa_get_option('wppa_file_system') == 'flat' ) $system = 'flat';	// Have been converted, ignore argument
	if ( wppa_get_option('wppa_file_system') == 'tree' ) $system = 'tree';	// Have been converted, ignore argument

	if ( $system == 'tree' ) {
		$result = WPPA_UPLOAD_URL . '/' . wppa_expand_id( $thumb['id'] ) . '.' . $thumb['ext'];
	}
	else {
		$result = WPPA_UPLOAD_URL . '/' . $thumb['id'] . '.' . $thumb['ext'];
	}

	if ( $fix_poster_ext ) {
		$result = wppa_fix_poster_ext( $result, $thumb['id'] );
	}

	// Social media do not like querystrings
	if ( ! wppa( 'no_ver' ) ) {
		$result .= '?ver=' . wppa_get_option( 'wppa_photo_version', '1' );
	}

	return $result;
}

// Bump Fullsize photo version number
function wppa_bump_photo_rev() {
	wppa_update_option('wppa_photo_version', wppa_get_option('wppa_photo_version', '1') + '1');
}

// Bump Download counter
function wppa_bump_dlcount( $id ) {
	$c = wppa_get_photo_item( $id, 'dlcount' );
	$c++;
	wppa_update_photo( $id, ['dlcount' => $c] );
}

// get path of a full sized image
function wppa_get_photo_path( $id, $fix_poster_ext = true, $system = 'flat' ) {

	$thumb = wppa_cache_photo( $id );
	if ( ! $thumb ) {
		return false;
	}

	if ( wppa_get_option( 'wppa_file_system' ) == 'flat' ) $system = 'flat';	// Have been converted, ignore argument
	if ( wppa_get_option( 'wppa_file_system' ) == 'tree' ) $system = 'tree';	// Have been converted, ignore argument

	if ( $system == 'tree' ) {
		$result = WPPA_UPLOAD_PATH . '/' . wppa_expand_id( $thumb['id'], false, true ) . '.' . $thumb['ext'];
	}
	else {
		$result = WPPA_UPLOAD_PATH . '/' . $thumb['id'] . '.' . $thumb['ext'];
	}
	if ( $fix_poster_ext ) {
		$result = wppa_fix_poster_ext( $result, $thumb['id'] );
	}
	return $result;
}

// Expand id to subdir chain for new file structure
function wppa_expand_id( $xid, $makepaththumb = false, $makepathphoto = false ) {

	$result = '';
	$id = $xid;
	$len = strlen( $id );
	while ( $len > '2' ) {
		$result .= substr( $id, '0', '2' ) . '/';
		$id = substr( $id, '2' );
		$len = strlen( $id );
		if ( $makepathphoto ) {
			$path = WPPA_UPLOAD_PATH . '/' . $result;
			if ( ! wppa_is_dir( $path ) ) wppa_mktree( $path );
		}
		if ( $makepaththumb ) {
			$path = WPPA_UPLOAD_PATH . '/thumbs/' . $result;
			if ( ! wppa_is_dir( $path ) ) wppa_mktree( $path );
		}
	}
	$result .= $id;
	return $result;
}

// Makes the html for the geo support for current theme and adds it to $wppa['geo']
function wppa_do_geo( $id, $location ) {
global $wppa;

	// Feature enabled?
	if ( ! wppa_switch( 'save_gpx' ) ) return;

	$temp 	= explode( '/', $location );
	$lat 	= $temp['2'];
	$lon 	= $temp['3'];

	$type 	= wppa_opt( 'gpx_implementation' );

	// Switch on implementation type
	switch ( $type ) {
		case 'external-plugin':
			$geo = str_replace( 'w#lon', $lon, str_replace( 'w#lat', $lat, wppa_opt( 'gpx_shortcode' ) ) );
			$geo = str_replace( 'w#ip', wppa_get_user_ip(), $geo );
			$geo = str_replace( 'w#gmapikey', wppa_opt( 'map_apikey' ), $geo );

			$geo = do_shortcode( $geo );
			$wppa['geo'] .= '<div id="geodiv-' . wppa( 'mocc' ) . '-' . $id . '" style="display:none;">' . $geo . '</div>';
			break;
		case 'wppa-plus-embedded':
			$the_js = '';
			if ( $wppa['geo'] == '' ) { 	// First
				$wppa['geo'] = '
				<div id="map-canvas-' . wppa( 'mocc' ).'"
					style="height:' . wppa_opt( 'map_height' ) . 'px; width:100%; padding:0; margin:0; font-size: 10px">
				</div>';
				$the_js .= '
					_wppaLat[' . wppa( 'mocc' ) . '] = [];
					_wppaLon[' . wppa( 'mocc' ) . '] = [];';
			}	// End first

			$the_js .= '
				_wppaLat[' . wppa( 'mocc' ) . '][' . $id . '] = ' . $lat . ';
				_wppaLon[' . wppa( 'mocc' ) . '][' . $id . '] = ' . $lon . ';';

			wppa_js( $the_js );
			break;	// End native
		case 'none':
			break;
		default:
			wppa_log( 'err', 'Unimplemented gpx_implementation: ' . $type . ' in wppa_do_geo()' );
			break;
	}
}

// See if an album is in a separate tree
function wppa_is_separate( $id ) {

	if ( $id == '' ) return false;
	if ( ! wppa_is_int( $id ) ) return false;
	if ( $id == '-1' ) return true;
	if ( $id < '1' ) return false;
	$alb = wppa_get_parentalbumid( $id );

	return wppa_is_separate( $alb );
}

// Get the albums parent
function wppa_get_parentalbumid( $id ) {

	if ( ! wppa_is_int( $id ) || $id < '1' ) return '0';

	$album = wppa_cache_album( $id );
	if ( $album === false ) return '-9'; // Parent died, i am an orphan

	return $album['a_parent'];
}

function wppa_html( $str ) {
// It is assumed that the raw data contains html.
// Make sure it is decoded

	$result = html_entity_decode( $str );
	return $result;
}


// get a photos album id
function wppa_get_album_id_by_photo_id( $id ) {

	if ( ! wppa_is_int($id) || $id < '1' ) return '';
	$thumb = wppa_cache_photo($id);
	return $thumb['album'];
}

function wppa_get_rating_count_by_id($id) {

	if ( ! wppa_is_int($id) || $id < '1' ) return '';
	$thumb = wppa_cache_photo($id);
	return $thumb['rating_count'];
}

function wppa_get_rating_by_id($id, $opt = '') {
global $wpdb;

	if ( ! wppa_is_int($id) || $id < '1' ) return '';
	$thumb = wppa_cache_photo( $id );
	$rating = $thumb['mean_rating'];
	if ( $rating ) {
		$i = wppa_opt( 'rating_prec' );
		$j = $i + '1';
		$val = sprintf('%'.$j.'.'.$i.'f', $rating);
		if ($opt == 'nolabel') $result = $val;
		else $result = sprintf(__('Rating: %s', 'wp-photo-album-plus' ), $val);
	}
	else $result = '';
	return $result;
}

function wppa_get_rating_total_by_id($id) {
global $wpdb;

	if ( ! wppa_is_int($id) || $id < '1' ) return '';

	$result = $wpdb->get_var( $wpdb->prepare( "SELECT SUM(value) FROM $wpdb->wppa_rating WHERE photo = %d", $id ) );
	return $result;
}

function wppa_get_my_rating_by_id($id, $opt = '') {
global $wpdb;

	if ( ! wppa_is_int($id) || $id < '1' ) return '';

	$my_ratings = $wpdb->get_results( $wpdb->prepare( "SELECT value FROM $wpdb->wppa_rating WHERE photo = %d AND user = %s", $id, wppa_get_user() ), ARRAY_A );
	if ( $my_ratings ) {
		$rating = 0;
		foreach ( $my_ratings as $r ) {
			$rating += $r['value'];
		}
		$rating /= count( $my_ratings );
	}
	else {
		$rating = '0';
	}
	if ( $rating ) {
		$i = wppa_opt( 'rating_prec' );
		$j = $i + '1';
		$val = sprintf('%'.$j.'.'.$i.'f', $rating);
		if ($opt == 'nolabel') $result = $val;
		else $result = sprintf(__('Rating: %s', 'wp-photo-album-plus' ), $val);
	}
	else $result = '0';
	return $result;
}

function wppa_switch( $xkey ) {
global $wppa_opt;

	// Are we initialized?
	if ( ! isset( $wppa_opt[$xkey] ) ) {
		if ( ! defined( 'WPPA_UPLOAD' ) ) {
//			wppa_dump( 'wppa_switch('.$xkey.') requested before init constants' );
		}
		else {
			wppa_initialize_runtime();
		}
	}

	// Old style?
	if ( substr( $xkey, 0, 5 ) == 'wppa_' ) {
		wppa_log( 'dbg', $xkey . ' used as old style switch' );
		$key = $xkey;
	}
	else {
		$key = 'wppa_' . $xkey;
	}

	if ( isset( $wppa_opt[$key] ) ) {
		if ( $wppa_opt[$key] == 'yes' ) return true;
		elseif ( $wppa_opt[$key] == 'no' ) return false;
		else wppa_log( 'dbg', '$wppa_opt['.$key.'] is not a yes/no setting' );
		return $wppa_opt[$key]; // Return the right value afterall
	}

	wppa_log( 'dbg', '$wppa_opt['.$key.'] is not a setting' );

	return false;
}

function wppa_opt( $xkey ) {
global $wppa_opt;

	// Are we initialized?
	if ( ! isset( $wppa_opt[$xkey] ) ) {
		if ( ! defined( 'WPPA_UPLOAD' ) ) {
//			wppa_dump( 'wppa_opt('.$xkey.') requested before init constants' );
		}
		else {
			wppa_initialize_runtime();
		}
	}

	// Old style?
	if ( substr( $xkey, 0, 5 ) == 'wppa_' ) {
		wppa_log( 'dbg', $xkey . ' used as old style option' );
		$key = $xkey;
	}
	else {
		$key = 'wppa_' . $xkey;
	}

	if ( isset( $wppa_opt[$key] ) ) {
		if ( $wppa_opt[$key] == 'yes' || $wppa_opt[$key] == 'no' ) {
			wppa_log( 'dbg', '$wppa_opt['.$key.'] is a yes/no setting, not a value' );
			return ( $wppa_opt[$key] == 'yes' ); // Return the right value afterall
		}
		return trim( $wppa_opt[$key] );
	}

	wppa_log( 'dbg', '$wppa_opt['.$key.'] is not a setting' );

	return false;
}

// Getter / setter of runtime parameter
function wppa( $key, $newval = 'nil' ) {
global $wppa;

	// Array defined?
	if ( ! is_array( $wppa ) ) {
		wppa_reset_occurrance();
	}

	// Invalid key?
	if ( ! isset( $wppa[$key] ) ) {

		// If index not exists: add it and report error if its not wppa-ajax and return false.
		if ( ! in_array( $key, array_keys( $wppa ) ) ) {
			if ( $key != 'wppa-ajax' ) {
				wppa_log( 'err', '$wppa[\'' . $key . '\'] is not defined in reset_occurrance' );
			}
			$wppa[$key] = false;
			return false;
		}

		// Exists but NULL, Not fatal
		else {
			wppa_log( 'war', '$wppa[\'' . $key . '\'] has value NULL' );

			// NULL is illegal, replace it by false, to prevent many equal errormessages
			$wppa[$key] = false;
		}
	}

	// Existing key, Get old value
	$oldval = $wppa[$key];

	// New value supplied?
	if ( $newval !== 'nil' ) {
		$wppa[$key] = $newval;
	}

	// If mocc requested and in_widget, add 100 (fixes caching conflicts)
//	if ( $key == 'mocc' && $wppa['in_widget'] ) {
//		$oldval += 100;
//	}

	return $oldval;
}

// Add (concat) value to runtime parameter
function wppa_add( $key, $newval ) {
global $wppa;

	// Array defined?
	if ( empty( $wppa ) ) {
		wppa_reset_occurrance();
	}

	// Valid key?
	if ( isset( $wppa[$key] ) ) {

		// Get old value
		$oldval = $wppa[$key];

		// Add new value
		$wppa[$key] .= $newval;
	}

	// Invalid key
	else {
		wppa_log( 'err', '$wppa[\''.$key.'\'] is not defined' );
		return false;
	}

	return $oldval;
}

function wppa_display_root( $id ) {
	$all = __('All albums', 'wp-photo-album-plus' );
	if ( ! $id || $id == '-2' ) return $all;
	$album = wppa_cache_album( $id );
	if ( ! $album ) return '';
	$albums = array();
	$albums[] = $album;
	$albums = wppa_add_paths( $albums );
	return $albums[0]['name'];
}

function wppa_add_paths( $albums ) {

	if ( is_array( $albums ) ) foreach ( array_keys( $albums ) as $index ) {
		$tempid = $albums[$index]['id'];
		$albums[$index]['name'] = __( stripslashes( $albums[$index]['name'] ) );	// Translate name
		while ( $tempid > '0' ) {
			$tempid = wppa_get_parentalbumid($tempid);
			if ( $tempid > '0' ) {
				$albums[$index]['name'] = wppa_get_album_name($tempid).' &gt; '.$albums[$index]['name'];
			}
			elseif ( $tempid == '-1' ) $albums[$index]['name'] = '-s- '.$albums[$index]['name'];
		}
	}
	return $albums;
}

// Sort an array on a column, keeping the indexes
function wppa_array_sort( $array, $on, $order = SORT_ASC ) {

    $new_array = array();
    $sortable_array = array();

    if ( count( $array ) > 0 ) {
        foreach ( $array as $k => $v ) {
            if ( is_array( $v ) ) {
                foreach ( $v as $k2 => $v2 ) {
                    if ( $k2 == $on ) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ( $order ) {
            case SORT_DESC:
                arsort( $sortable_array );
            break;
            default: 	// case SORT_ASC:
                asort( $sortable_array );
            break;
       }

        foreach ( $sortable_array as $k => $v ) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function wppa_get_taglist( $translate = false ) {

	// Get the taglist if exists
	$result = WPPA_MULTISITE_GLOBAL ? get_site_option( 'wppa_taglist', 'nil' ) : wppa_get_option( 'wppa_taglist', 'nil' );

	// If absent, create it. Will get the extracted version
	if ( $result == 'nil' ) {
		$result = wppa_create_taglist(); 	// Is already extracted
	}

	// Not just created, extract the ids, if any
	else {
		if ( is_array( $result ) ) foreach ( array_keys( $result ) as $tag ) {
			$result[$tag]['ids'] = wppa_index_string_to_array( $result[$tag]['ids'] );
		}
	}

	// Translate keys ?
	if ( is_array( $result ) && $translate ) {
		$translated = array();
		foreach ( $result as $item ) {

			$translated[ ucfirst( __( $item['tag'] ) ) ] = $item;
		}
		$result = $translated;
		ksort( $result );
	}

	$keys = array_keys( $result );
	if ( count( $result ) == 1 && $keys[0] == '' ) {
		$result = array();
	}
	return $result;
}

function wppa_clear_taglist() {

	$result = WPPA_MULTISITE_GLOBAL ? update_site_option( 'wppa_taglist', 'nil' ) : wppa_update_option( 'wppa_taglist', 'nil' );
	$result = WPPA_MULTISITE_GLOBAL ? get_site_option( 'wppa_taglist', 'nil' ) : wppa_get_option( 'wppa_taglist', 'nil' );
	if ( $result != 'nil' ) {
		wppa_log( 'war', 'Could not clear taglist' ) ;
	}
}

function wppa_create_taglist() {
global $wpdb;

	// Init
	$time 	= time();
	$total 	= 0;

	// Exclude seps?
	$sep = str_replace( '.', ',', wppa_expand_enum( wppa_alb_to_enum_children( '-1' ) ) );
	if ( wppa_switch( 'excl_sep' ) && $sep ) {
		$alb_clause = "album > 0 AND album NOT IN (" . $sep . ")";
	}
	else {
		$alb_clause = "album > 0";
	}

	// Get the existing tags raw
	$raw_tagcol = $wpdb->get_col( "SELECT DISTINCT tags FROM $wpdb->wppa_photos
								   WHERE status NOT IN ('pending','scheduled')
								   AND $alb_clause
								   AND tags <> ''" );
	$raw_tags 	= implode( ',', $raw_tagcol ) . wppa_opt( 'minimum_tags' );
	$san_tags 	= trim( wppa_sanitize_tags( $raw_tags ), ',' );
	$tag_arr 	= explode( ',', $san_tags );

	// Process all existing tags
	if ( count( $tag_arr ) ) {
		$result = array();
		foreach( $tag_arr as $tag )  {
			$result[$tag]['tag'] = $tag;
			$result[$tag]['ids'] = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos
																	WHERE status NOT IN ('pending','scheduled')
																	AND $alb_clause
																	AND tags LIKE %s", '%' . str_replace( "'", "\'", ',' . $wpdb->esc_like( $tag ) . ',' ) . '%' ) );
			$result[$tag]['count'] = count( $result[$tag]['ids'] );
			$total += $result[$tag]['count'];
		}
	}
	else {
		$result = false;
	}

	// If any tags found, calculate fractions
	$tosave = array();
	if ( is_array( $result ) ) {
		foreach ( array_keys( $result ) as $key ) {
			$result[$key]['fraction'] = $total ? sprintf( '%4.2f', $result[$key]['count'] / $total ) : '0.00';
		}
		$tosave = $result;

		// Convert the arrays to compressed enumerations
		foreach ( array_keys( $tosave ) as $key ) {
			$tosave[$key]['ids'] = wppa_index_array_to_string( $tosave[$key]['ids'] );
		}
	}

	// Save the new taglist
	$bret = WPPA_MULTISITE_GLOBAL ? update_site_option( 'wppa_taglist', $tosave ) : wppa_update_option( 'wppa_taglist', $tosave );

	$dtime = time() - $time;
	$mem = memory_get_peak_usage( true );
	wppa_log( 'dbg', "Creating taglist took $dtime seconds and $mem bytes memory" );

	// And return the result
	return $result;
}

function wppa_get_catlist() {

	$result = WPPA_MULTISITE_GLOBAL ? get_site_option( 'wppa_catlist', 'nil' ) : wppa_get_option( 'wppa_catlist', 'nil' );
	if ( $result == 'nil' ) {
		$result = wppa_create_catlist();
	}
	else {
		foreach ( array_keys($result) as $cat ) {
			$result[$cat]['ids'] = wppa_index_string_to_array($result[$cat]['ids']);
		}
	}
	if ( ! is_array( $result ) ) {
		return array();
	}
	$keys = array_keys( $result );
	if ( count( $result ) == 1 && $keys[0] == '' ) {
		$result = array();
	}
	return $result;
}

function wppa_clear_catlist() {

	$result = WPPA_MULTISITE_GLOBAL ? update_site_option( 'wppa_catlist', 'nil' ) : wppa_update_option( 'wppa_catlist', 'nil' );
	$result = WPPA_MULTISITE_GLOBAL ? get_site_option( 'wppa_catlist', 'nil' ) : wppa_get_option( 'wppa_catlist', 'nil' );
	if ( $result != 'nil' ) {
		wppa_log( 'war', 'Could not clear catlist' ) ;
	}
}

function wppa_create_catlist() {
global $wpdb;

	$result = array();
	$total = '0';
	$albums = $wpdb->get_results("SELECT id, cats FROM $wpdb->wppa_albums WHERE cats <> ''", ARRAY_A);
	if ( $albums ) foreach ( $albums as $album ) {
		$cats = explode(',', $album['cats']);
		if ( $cats ) foreach ( $cats as $cat ) {
			if ( $cat ) {
				if ( ! isset($result[$cat]) ) {	// A new cat
					$result[$cat]['cat'] = $cat;
					$result[$cat]['count'] = '1';
					$result[$cat]['ids'][] = $album['id'];
				}
				else {							// An existing cat
					$result[$cat]['count']++;
					$result[$cat]['ids'][] = $album['id'];
				}
			}
			$total++;
		}
	}
	$tosave = array();
	if ( is_array($result) ) {
		foreach ( array_keys($result) as $key ) {
			$result[$key]['fraction'] = sprintf('%4.2f', $result[$key]['count'] / $total);
		}
		$result = wppa_array_sort($result, 'cat');
		$tosave = $result;
		foreach ( array_keys($tosave) as $key ) {
			$tosave[$key]['ids'] = wppa_index_array_to_string($tosave[$key]['ids']);
		}
	}
	$bret = WPPA_MULTISITE_GLOBAL ? update_site_option( 'wppa_catlist', $tosave ) : wppa_update_option( 'wppa_catlist', $tosave );
	return $result;
}

function wppa_update_option( $option, $value ) {
global $wppa_opt;
global $all_wppa_options;

	// Get all options if not yet done
	if ( $all_wppa_options == NULL ) {
		$all_wppa_options = wp_load_alloptions();
	}

	// Update the option
	update_option( $option, $value );

	// Update the local cache
	$wppa_opt[$option] = $value;

	// Update the all optins local cache
	if ( is_array( $value ) ) {
		$all_wppa_options[$option] = serialize( $value );
	}
	else {
		$all_wppa_options[$option] = $value;
	}
}

function wppa_album_exists( $id ) {
global $wpdb;
static $existing_albums;

	// Can only test album ids
	if ( ! wppa_is_int( $id ) ) {
		return false;
	}

	// If existing albums cache not filled yet, fill it.
	if ( ! $existing_albums ) {
		$existing_albums = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_albums" );
	}

	// If in cache, exists = true
	if ( in_array( $id, $existing_albums ) ) {
		return true;
	}

	// Maybe just created
	$is_new = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE id = %s", $id ) );
	if ( $is_new ) {

		// Add to cache
		$existing_albums[] = $is_new;
	}
	return $is_new;
}

function wppa_photo_exists( $id ) {
global $wpdb;

	return $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE id = %s", $id ) );
}

function wppa_albumphoto_exists($alb, $photo) {
global $wpdb;
	return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE album = %s AND filename = %s", $alb, $photo));
}

function wppa_dislike_check($photo) {
global $wpdb;

	$count = $wpdb->get_var($wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_rating WHERE photo = %s AND value = -1", $photo ));

	if ( wppa_opt( 'dislike_mail_every' ) > '0') {		// Feature enabled?
		if ( $count % wppa_opt( 'dislike_mail_every' ) == '0' ) {	// Mail the admin
			$to        = get_bloginfo('admin_email');
			$subj 	   = __('Notification of inappropriate image', 'wp-photo-album-plus' );
			$cont['0'] = sprintf(__('Photo %s has been marked as inappropriate by %s different visitors.', 'wp-photo-album-plus' ), $photo, $count);
			$cont['1'] = '<a href="'.get_admin_url().'admin.php?page=wppa_admin_menu&tab=pmod&photo='.$photo.'" >'.__('Manage photo', 'wp-photo-album-plus' ).'</a>';
			wppa_send_mail( array( 'to' 	=> $to,
								   'subj' 	=> $subj,
								   'cont' 	=> $cont,
								   'photo' 	=> $photo,
								   ));
		}
	}

	if ( wppa_opt( 'dislike_set_pending' ) > '0') {		// Feature enabled?
		if ( $count == wppa_opt( 'dislike_set_pending' ) ) {
			wppa_update_photo( $photo, ['status' => 'pending'] );
			$to        = get_bloginfo('admin_email');
			$subj 	   = __('Notification of inappropriate image', 'wp-photo-album-plus' );
			$cont['0'] = sprintf(__('Photo %s has been marked as inappropriate by %s different visitors.', 'wp-photo-album-plus' ), $photo, $count);
			$cont['0'] .= "\n".__('The status has been changed to \'pending\'.', 'wp-photo-album-plus' );
			$cont['1'] = '<a href="'.get_admin_url().'admin.php?page=wppa_admin_menu&tab=pmod&photo='.$photo.'" >'.__('Manage photo', 'wp-photo-album-plus' ).'</a>';
			wppa_send_mail( array( 'to' 	=> $to,
								   'subj' 	=> $subj,
								   'cont' 	=> $cont,
								   'photo' 	=> $photo,
								   ));
		}
	}

	if ( wppa_opt( 'dislike_delete' ) > '0') {			// Feature enabled?
		if ( $count == wppa_opt( 'dislike_delete' ) ) {
			$to        = get_bloginfo('admin_email');
			$subj 	   = __('Notification of inappropriate image', 'wp-photo-album-plus' );
			$cont['0'] = sprintf(__('Photo %s has been marked as inappropriate by %s different visitors.', 'wp-photo-album-plus' ), $photo, $count);
			$cont['0'] .= "\n".__('It has been deleted.', 'wp-photo-album-plus' );
			$cont['1'] = '';//<a href="'.get_admin_url().'admin.php?page=wppa_admin_menu&tab=pmod&photo='.$photo.'" >'.__('Manage photo').'</a>';
			wppa_send_mail( array( 'to' 	=> $to,
								   'subj' 	=> $subj,
								   'cont' 	=> $cont,
								   'photo' 	=> $photo,
								   ));
			wppa_delete_photo($photo);
		}
	}
}


// Get number of dislikes for a given photo id
function wppa_dislike_get( $id ) {
global $wpdb;

	$count = $wpdb->get_var( $wpdb->prepare( 	"SELECT COUNT(*) " .
												"FROM $wpdb->wppa_rating " .
												"WHERE photo = %s " .
												"AND value = -1",
												$id
											)
							);
	return $count;
}

// Get number of pending ratings for a given photo id
function wppa_pendrat_get( $id ) {
global $wpdb;

	$count = $wpdb->get_var( $wpdb->prepare( 	"SELECT COUNT(*) " .
												"FROM $wpdb->wppa_rating " .
												"WHERE photo = %s AND " .
												"status = 'pending'",
												$id
											)
							);
	return $count;
}



function wppa_get_imgalt( $id, $lb = false ) {

	// Get photo data
	$thumb = wppa_cache_photo( $id );

	// Get raw image alt data
	switch ( wppa_opt( 'alt_type' ) ) {
		case 'fullname':
			$result = wppa_get_photo_name( $id );
			break;
		case 'namenoext':
			$result = wppa_strip_ext( wppa_get_photo_name( $id ) );
			break;
		case 'custom':
			$result = $thumb['alt'];
			break;
		default:
			$result = $id;
			break;
	}

	// Default if empty result
	if ( ! $result ) {
		$result = '0';
	}

	// Format for use in lightbox or direct use html
	if ( $lb ) {
		$result = esc_attr( str_replace( '"', "'", $result ) );
	}
	else {
		$result = ' alt="' . esc_attr( $result ) . '" ';
	}

	return $result;
}


function wppa_is_time_up( $count = '', $margin = 5 ) {
global $wppa_endtime;

	if ( ! $wppa_endtime ) {
		wppa_log( 'err', 'Zero endtime, set to 55 secs after now' );
		$wppa_endtime = time() + 60;
	}

	// Time up?
	if ( $wppa_endtime > ( time() + $margin ) ) {
		return false;	// No
	}

	// Time is up. If cron return silently true
	if ( wppa_is_cron() ) {
		return true;
	}

	// Not cron, leave a message optionally and retrun true
	if ( $count ) {
		wppa_alert( sprintf( __( 'Time out after processing %s items. Please restart this operation', 'wp-photo-album-plus' ), $count ) );
	}
	return true;
}

function wppa_is_memory_up() {
	return memory_get_usage() > ( 0.9 * wppa_memry_limit() );
}

function wppa_time_left( $margin = 5 ) {
global $wppa_endtime;
	return $wppa_endtime - time() - $margin;
}

function wppa_nl_to_txt($text) {
	return str_replace("\n", "\\n", $text);
}
function wppa_txt_to_nl($text) {
	return str_replace('\n', "\n", $text);
}

// Check query arg on tags, return value if valid
function wppa_vfy_arg( $arg, $txt = false ) {
	if ( wppa_get( $arg ) ) {
		if ( $txt ) {	// Text is allowed, but without tags
			$reason = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? ': ' . $arg . ' contains tags.' : '';
			if ( wppa_get( $arg ) != strip_tags( wppa_get( $arg ) ) ) {
				wp_die( 'Security check failue ' . $reason );
			}
			return wppa_get( $arg );
		}
		else {
			$reason = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? ': ' . $arg . ' is not numeric, its '.wppa_get( $arg ) : '';
			$value = wppa_get( $arg );

			if ( ! is_numeric( $value ) ) {
				wp_die( 'Security check failue ' . $reason );
			}
			return $value;
		}
	}
	else {
		return '';
	}
}

// Strip tags with content
function wppa_strip_tags($text, $key = '') {

	if ($key == 'all') {
		$text = preg_replace(	array	(	'@<a[^>]*?>.*?</a>@siu',				// unescaped <a> tag
											'@&lt;a[^>]*?&gt;.*?&lt;/a&gt;@siu',	// escaped <a> tag
											'@<table[^>]*?>.*?</table>@siu',
											'@<style[^>]*?>.*?</style>@siu',
											'@<div[^>]*?>.*?</div>@siu'
										),
								array	( ' ', ' ', ' ', ' ', ' '
										),
								$text );
		$text = str_replace(array('<br/>', '<br>'), ' ', $text);
		$text = strip_tags($text);
	}
	elseif ( $key == 'script' ) {
		$text = preg_replace('@<script[^>]*?>.*?</script>@siu', ' ', $text );
	}
	elseif ( $key == 'div' ) {
		$text = preg_replace('@<div[^>]*?>.*?</div>@siu', ' ', $text );
	}
	elseif ( $key == 'script&style' || $key == 'style&script' ) {
		$text = preg_replace(	array	(	'@<script[^>]*?>.*?</script>@siu',
											'@<style[^>]*?>.*?</style>@siu'
										),
								array	( ' ', ' '
										),
								$text );
	}
	else {
		$text = preg_replace(	array	(	'@<a[^>]*?>.*?</a>@siu',				// unescaped <a> tag
											'@&lt;a[^>]*?&gt;.*?&lt;/a&gt;@siu'		// escaped <a> tag
										),
								array	( ' ', ' '
										),
								$text );
	}
	return trim($text);
}

// set last album
function wppa_set_last_album( $id = '' ) {

	if ( wppa_is_int( $id ) ) {
		wppa_update_option( 'wppa_last_album_used-' . wppa_get_user( 'login' ), $id );
	}
}

// get last album
function wppa_get_last_album() {

	$album = wppa_get_option( 'wppa_last_album_used-' . wppa_get_user( 'login' ), '0' );
	if ( ! wppa_album_exists( $album ) ) {
		$album = false;
	}
    return $album;
}

// Combine margin or padding style
function wppa_combine_style($type, $top = '0', $left = '0', $right = '0', $bottom = '0') {

	$result = $type.':';			// Either 'margin:' or 'padding:'
	if ( $left == $right ) {
		if ( $top == $bottom ) {
			if ( $top == $left ) {	// All the same: one size fits all
				$result .= $top;
				if ( is_numeric($top) && $top > '0' ) $result .= 'px';
			}
			else {					// Top=Bot and Lft=Rht: two sizes
				$result .= $top;
				if ( is_numeric($top) && $top > '0' ) $result .= 'px '; else $result .= ' ';
				$result .= $left;
				if ( is_numeric($left) && $left > '0' ) $result .= 'px';
			}
		}
		else {						// Top, Lft=Rht, Bot: 3 sizes
			$result .= $top;
			if ( is_numeric($top) && $top > '0' ) $result .= 'px '; else $result .= ' ';
			$result .= $left;
			if ( is_numeric($left) && $left > '0' ) $result .= 'px '; else $result .= ' ';
			$result .= $bottom;
			if ( is_numeric($bottom) && $bottom > '0' ) $result .= 'px';
		}
	}
	else {							// Top, Rht, Bot, Lft: 4 sizes
		$result .= $top;
		if ( is_numeric($top) && $top > '0' ) $result .= 'px '; else $result .= ' ';
		$result .= $right;
		if ( is_numeric($right) && $right > '0' ) $result .= 'px '; else $result .= ' ';
		$result .= $bottom;
		if ( is_numeric($bottom) && $bottom > '0' ) $result .= 'px '; else $result .= ' ';
		$result .= $left;
		if ( is_numeric($left) && $left > '0' ) $result .= 'px';
	}
	$result .= ';';
	return $result;
}

// A temp routine to fix an old bug
function wppa_fix_source_extensions() {
global $wpdb;

	$start_time = time();
	$end = $start_time + '15';
	$count = '0';
	$start = wppa_get_option('wppa_sourcefile_fix_start', '0');
	if ( $start == '-1' ) return; // Done!

	$photos = $wpdb->get_results( 	"SELECT id, album, name, filename" .
										" FROM $wpdb->wppa_photos" .
										" WHERE filename <> ''  AND filename <> name AND id > " . $start .
										" ORDER BY id", ARRAY_A
								);
	if ( $photos ) {
		foreach ( $photos as $data ) {
			$faulty_sourcefile_name = wppa_opt( 'source_dir' ).'/album-'.$data['album'].'/'.preg_replace('/\.[^.]*$/', '', $data['filename']);
			if ( is_file($faulty_sourcefile_name) ) {
				$proper_sourcefile_name = wppa_opt( 'source_dir' ).'/album-'.$data['album'].'/'.$data['filename'];
				wppa_rename($faulty_sourcefile_name, $proper_sourcefile_name);
				$count++;
			}
			if ( time() > $end ) {
				wppa_ok_message( 'Fixed ' . $count . ' faulty sourcefile names.' .
									' Last was ' . $data['id'] . '.' .
									' Not finished yet. I will continue fixing next time you enter this page. Sorry for the inconvenience.'
								);

				wppa_update_option('wppa_sourcefile_fix_start', $data['id']);
				return;
			}
		}
	}
	wppa_echo( __( sprintf( '%d source file extensions repaired', 'wp-photo-album-plus' ), $count ) );
	wppa_update_option('wppa_sourcefile_fix_start', '-1');
}

// Delete a photo and all its attrs by id
function wppa_delete_photo( $photo, $immediate = false ) {
global $wppa_supported_audio_extensions;
global $wppa_supported_video_extensions;
global $wpdb;


	// Sanitize arg
	$photo = strval( intval( $photo ) );

	// Get data
	$photoinfo = wppa_cache_photo( $photo );

	// Photo gone? nothing to do
	if ( ! $photoinfo ) {
		return;
	}

	// If still in use, refuse deletion
	$in_use = $photoinfo['usedby'] ? explode( '.', trim( $photoinfo['usedby'], '.' ) ) : false;

	if ( is_array( $in_use ) ) {

		$post = get_post( $in_use[0] );
		if ( defined( 'DOING_AJAX' ) ) {
			wppa_echo( 'ER||0||' . '<span style="color:#ff0000">' . esc_html__( 'Could not delete photo', 'wp-photo-album-plus' ) . '</span>||' .
						__( 'Photo is still in use in post/page', 'wp-photo-album-plus' ) . ' ' . $post->post_title );
			wppa_exit();
		}
		else {
			wppa_error_message( __( 'Photo is still in use in post/page', 'wp-photo-album-plus' ) . ' ' . $post->post_title );
			return false;
		}
	}

	// Get album
	$album = $photoinfo['album'];

	// Really delete only as cron job
	if ( ! wppa_is_cron() && ! $immediate ) {
		if ( $album > '0' ) {
			$newalb = - ( $album + '9' );
			wppa_update_photo( $photo, ['album' => $newalb, 'modified' => time()] );
			wppa_mark_treecounts( $album );
			wppa_clear_cache( ['photo' => $photo] );
			wppa_schedule_cleanup( 'now' );
			wppa_clear_taglist();
		}
		return;
	}

	// Restore orig album #
	$album = - ( $album + '9' );

	// Delete multimedia files
	if ( wppa_is_multi( $photo ) ) {
		$mmfile = wppa_strip_ext( wppa_get_photo_path( $photo, false ) );
		$allsup = array_merge( $wppa_supported_audio_extensions, $wppa_supported_video_extensions );
		foreach( $allsup as $mmext ) {
			if ( is_file( $mmfile . '.' . $mmext ) ) {
				wppa_unlink( $mmfile . '.' . $mmext );
			}
		}
	}

	// If still a photo with the same name exists in the original album, do not delete the source
	$still_exists = $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(*) FROM $wpdb->wppa_photos
		 WHERE ( filename = %s OR filename = %s )
		 AND album = %s",
		$photoinfo['filename'],
		wppa_strip_ext( $photoinfo['filename'] ) . '.xxx', 	// May be a multimedia iten
		$album
		) );

	if ( ! $still_exists ) {

		// Delete sourcefile
		wppa_delete_source( $photoinfo['filename'], $album );
	}

	// Delete fullsize image
	$file = wppa_get_photo_path( $photo );
	if ( is_file( $file ) ) wppa_unlink( $file );

	// Delete thumbnail image
	wppa_delete_thumb( $photo );
//	$file = wppa_get_thumb_path( $photo );
//	if ( is_file( $file ) ) wppa_unlink( $file );

	// Delete index
	wppa_index_update('photo', $photo);

	// Delete db entries
	wppa_del_row( WPPA_PHOTOS, 'id', $photo );
	wppa_del_row( WPPA_RATING, 'photo', $photo );
	wppa_del_row( WPPA_COMMENTS, 'photo', $photo );
	wppa_del_row( WPPA_IPTC, 'photo', $photo );
	wppa_del_row( WPPA_EXIF, 'photo', $photo );

	wppa_invalidate_treecounts( $album );
	wppa_flush_upldr_cache( 'photoid', $photo );

	// Clear taglist to trigger recreata
	wppa_clear_taglist();

	// Delete from cloud
	if ( wppa_cdn( 'admin' ) == 'cloudinary' ) {
		wppa_delete_from_cloudinary( $photo );
	}
	elseif ( wppa_cdn( 'admin' ) == 'local' ) {
		wppa_cdn_delete( $photo );
	}

	// Delete caches: photo related and non-wppa
	wppa_clear_cache( ['photo' => $photo] );
}

// Delete thumbnail immediate
function wppa_delete_thumb( $id ) {

	if ( wppa_get_option( 'wppa_file_system' ) == 'flat' ) $system = 'flat';	// Has been converted, ignore argument
	if ( wppa_get_option( 'wppa_file_system' ) == 'tree' ) $system = 'tree';	// Has been converted, ignore argument

	$ext = wppa_get_photo_item( $id, 'ext' );

	if ( $system == 'tree' ) {
		$path = WPPA_UPLOAD_PATH.'/thumbs/'.wppa_expand_id( $id ).'.'.$ext;
	}
	else {
		$path = WPPA_UPLOAD_PATH.'/thumbs/'.$id.'.'.$ext;
	}

	if ( wppa_is_file( $path ) ) {
		wppa_unlink( $path );
	}

	if ( wppa_is_wppa_tree( $path ) ) {
		wppa_try_del_tree( $path );
	}
}
function wppa_try_del_tree( $path ) {
	$dir = dirname( $path );
	if ( basename( $dir ) == 'thumbs' ) return;

	$dirs 	= wppa_glob( $dir . '/*', WPPA_ONLYDIRS );
	$files 	= wppa_glob( $dir . '/*', WPPA_ONLYFILES );

	if ( count( $dirs ) == 0 && count( $files ) == 0 ) {
		wppa_rmdir( $dir, 'when_empty' );
		wppa_try_del_tree( $dir );
	}
}

function wppa_is_wppa_tree( $file ) {

	$temp = explode( '/uploads/wppa/', $file );
	if ( count( $temp ) === 2 ) {
		$temp[1] = wppa_expand_id( wppa_strip_ext( $temp[1] ) ) . '.' . wppa_get_ext( $temp[1] );
		$newf = implode( '/wppa/', $temp );
		wppa( 'is_wppa_tree', ( $newf != $file ) );
	}
	else {
		wppa( 'is_wppa_tree', false );
	}
	return wppa( 'is_wppa_tree' );
}

function wppa_compress_tree_path( $path ) {

	$result = $path;
	$temp = explode( '/wppa/', $path );
	if ( count( $temp ) == '2' ) {
		$temp[1] = str_replace( '/', '', $temp[1] );
		$result = implode( '/wppa/', $temp );
	}
	return $result;
}

function wppa_expand_tree_path( $path ) {

	$result = $path;
	$temp = explode( '/wppa/', $path );
	if ( count( $temp ) == '2' ) {
		$temp[1] = wppa_expand_id( wppa_strip_ext( $temp[1] ) ) . '.' . wppa_get_ext( $temp[1] );
		$result = implode( '/wppa/', $temp );
	}
	return $result;
}

// Undelete photo
function wppa_undelete_photo( $photo, $echo ) {
global $wpdb;

	$album = $wpdb->get_var( $wpdb->prepare( "SELECT album FROM $wpdb->wppa_photos
											  WHERE id = %d", $photo ) );

	$album_exists = true;
	if ( wppa_is_int( $album ) && $album < '-9' ) {

		$real_alb = - ( $album + '9' );

		// Check if album exists, otherwise recreate it
		if ( ! wppa_album_exists( $real_alb ) ) {

			$iret =
			wppa_create_album_entry(
				array( 	'id' => $real_alb,
						'a_parent' 		=> '-1',
						'name' 			=> __( 'Recovered deleted album', 'wp-photo-album-plus' ),
						'description' 	=> __( 'Automatically created album when a deleted photo was recovered and the original album did no longer exist', 'wp-photo-album-plus' ),
						'owner' 		=> wppa_switch( 'backend_album_public' ) ? '--- public ---' : wppa_get_user()
					)
				);

			if ( $iret ) {
				$real_alb = $iret;	// May be different id
			}
			else {
				$album_exists = false;
			}
		}

		if ( $album_exists ) {
			wppa_update_photo( $photo, ['album' => $real_alb, 'modified' => time()] );
			wppa_invalidate_treecounts( - ( $album + '9' ) );
			if ( $echo ) wppa_echo( '||1||<span style="color:red" >'.sprintf( __( 'Photo %s has been undeleted and placed in album %d', 'wp-photo-album-plus' ), $photo, $real_alb ).'</span>' );
		}
		else {
			if ( $echo ) wppa_echo( '||1||<span style="color:red" >'.sprintf( __( 'Could not undelete photo %s' , 'wp-photo-album-plus' ), $photo ).'</span>' );
		}
	}
	wppa_clear_cache( ['force' => true] );
	wppa_clear_taglist();
}

function wppa_sanitize_cats( $value ) {
	return wppa_sanitize_tags( $value );
}
function wppa_sanitize_tags( $value, $keepsemi = false, $keephash = false ) {

	// Sanitize
	$value = sanitize_text_field( $value );
//	$value = strip_tags( $value );					// Security

	$value = str_replace( 	array( 					// Remove funny chars
									'"',
							//		'\'',
									'\\',
									'@',
									'?',
									'|',
								 ),
							'',
							$value
						);
	if ( ! $keephash ) {
		$value = str_replace( '#', '', $value );
	}

	$value = stripslashes($value);					// ...

	// Find separator
	$sep = ',';										// Default seperator
	if ( $keepsemi ) {								// ';' allowed
		if ( strpos($value, ';') !== false ) {		// and found at least one ';'
			$value = str_replace(',', ';', $value);	// convert all separators to ';'
			$sep = ';';
		}											// ... a mix is not permitted
	}
	else {
		$value = str_replace(';', ',', $value);		// Convert all seps to default separator ','
	}

	$temp = explode( $sep, $value );
	if ( is_array($temp) ) {

		// Trim
		foreach ( array_keys( $temp ) as $idx ) {
			$temp[$idx] = trim( $temp[$idx] );
		}

		// Capitalize single words within tags
		// Can not use wppa_switch because its used in wppa_get()
//		if ( wppa_switch( 'capitalize_tags' ) ) {
		if ( wppa_get_option( 'wppa_capitalize_tags', 'yes' ) == 'yes' ) {
			foreach ( array_keys($temp) as $idx ) {
				if ( strlen( $temp[$idx] ) > '1' ) {
					$words = explode( ' ', $temp[$idx] );
					foreach( array_keys($words) as $i ) {
						$words[$i] = ucfirst( strtolower( $words[$i] ) );
					}
					$temp[$idx] = implode(' ', $words);
				}
			}
		}

		// Capitalize exif tags
		foreach ( array_keys( $temp ) as $idx ) {
			if ( substr( $temp[$idx], 0, 2 ) == 'E#' ) {
				$temp[$idx] = strtoupper( $temp[$idx] );
			}
		}

		// Capitalize GPX and HD tags
		foreach ( array_keys( $temp ) as $idx ) {
			if ( in_array( $temp[$idx], array( 'Gpx', 'Hd' ) ) ) {
				$temp[$idx] = strtoupper( $temp[$idx] );
			}
		}

		// Sort
		asort( $temp );

		// Remove dups and recombine
		$value = '';
		$first = true;
		$previdx = '';
		foreach ( array_keys($temp) as $idx ) {
			if ( strlen( $temp[$idx] ) > '1' ) {

				// Remove duplicates
				if ( $temp[$idx] ) {
					if ( $first ) {
						$first = false;
						$value .= $temp[$idx];
						$previdx = $idx;
					}
					elseif ( $temp[$idx] !=  $temp[$previdx] ) {
						$value .= $sep.$temp[$idx];
						$previdx = $idx;
					}
				}
			}
		}
	}

	if ( $sep == ',' && $value != '' ) {
		$value = $sep . $value . $sep;
	}
	return $value;
}

// Sanitize nice scroll options
// Must be a valid js object content
//
// Defaults:
//
// cursorwidth:'8px',
// cursoropacitymin:0.4,
// cursorcolor:'#777777',
// cursorborder:'none',
// cursorborderradius:'6px',
// autohidemode:'leave',
// nativeparentscrolling:false,
// preservenativescrolling:false,
// bouncescroll:false,
// smoothscroll:true,
// cursorborder:'2px solid transparent',
// horizrailenabled:false,
function wppa_sanitize_nso( $nso ) {

	$result = array();
	$nso = str_replace( "'", '', $nso );
	$nso = str_replace( "\n", '', $nso );
	$nso_arr = explode( ',', $nso );
	foreach ( $nso_arr as $item ) {
		$n_v = explode( ':', $item );
		if ( count( $n_v ) == 2 ) {
			$name  	= trim( $n_v[0] );
			$value 	= trim( $n_v[1] );
			if ( in_array( $value, ['true','false'] ) || is_numeric( $value ) ) {
				$result[] = $name . ':' . $value . ",\n";
			}
			else {
				$result[] = $name . ':' . "'" . $value . "',\n";
			}
		}
	}
	return implode( '', $result );
}

// Does the same as wppa_index_string_to_array() but with format validation and error reporting
function wppa_series_to_array($xtxt) {
	if ( is_array( $xtxt ) ) return false;
	$txt = str_replace(' ', '', $xtxt);					// Remove spaces
	if ( strpos($txt, '.') === false ) return false;	// Not an enum/series, the only legal way to return false
	if ( strpos($txt, '...') !== false ) {
		return wppa_stx_err('Max 2 successive dots allowed. '.$txt);
	}
	if ( substr($txt, 0, 1) == '.' ) {
		return wppa_stx_err('Missing starting number. '.$txt);
	}
	if ( substr($txt, -1) == '.' ) {
		return wppa_stx_err('Missing ending number. '.$txt);
	}
	$t = str_replace(array('.','0','1','2','3','4','5','6','7','8','9'), '',$txt);
	if ( $t ) {
		return wppa_stx_err('Illegal character(s): "'.$t.'" found. '.$txt);
	}

	// Trim leading '0.'
	if ( substr( $txt, 0, 2 ) == '0.' ) {
		$txt = substr( $txt, 2 );
	}

	$temp = explode('.', $txt);
	$tempcopy = $temp;

	foreach ( array_keys($temp) as $i ) {
		if ( ! $temp[$i] ) { 							// found a '..'
			if ( $temp[$i-'1'] >= $temp[$i+'1'] ) {
				return wppa_stx_err('Start > end. '.$txt);
			}
			for ( $j=$temp[$i-'1']+'1'; $j<$temp[$i+'1']; $j++ ) {
				$tempcopy[] = $j;
			}
		}
		else {
			if ( ! is_numeric($temp[$i] ) ) {
				return wppa_stx_err('A enum or range token must be a number. '.$txt);
			}
		}
	}
	$result = $tempcopy;
	foreach ( array_keys($result) as $i ) {
		if ( ! $result[$i] ) unset($result[$i]);
	}
	return $result;
}
function wppa_stx_err( $msg ) {
global $wppa;

	$fullmsg = __( 'Syntax error in album specification.', 'wp-photo-album-plus' ) . ' ' . $msg;
	if ( strpos( $wppa['out'], $fullmsg ) === false ) {
		wppa_out( $fullmsg );
		wppa_log( 'err', $msg );
	}

	return $fullmsg;
}


function wppa_get_og_desc( $id, $short = false ) {

	if ( $short ) {
		$result = 	strip_shortcodes( wppa_strip_tags( wppa_html( wppa_get_photo_desc( $id ) ), 'all' ) );
		if ( ! $result ) {
			$result = str_replace( '&amp;', __( 'and', 'wp-photo-album-plus' ), get_bloginfo( 'name' ) );
		}
	}
	else {
		$result = 	sprintf( __('See this image on %s', 'wp-photo-album-plus' ), str_replace( '&amp;', __( 'and', 'wp-photo-album-plus' ), get_bloginfo( 'name' ) ) ) .
					': ' .
					strip_shortcodes( wppa_strip_tags( wppa_html( wppa_get_photo_desc( $id ) ), 'all' ) );
	}

	$result = 	apply_filters( 'wppa_get_og_desc', $result );

	return $result;
}

// There is no php routine to test if a string var is an integer, like '3': yes, and '3.7' and '3..7': no.
// is_numeric('3.7') returns true
// intval('3..7') == '3..7' returns true
// is_int('3') returns false
// so we make it ourselves
function wppa_is_int( $var ) {
	if ( is_array( $var ) ) {
		return false;
	}
	return ( strval(intval($var)) == strval($var) );
}

function wppa_is_posint( $var ) {
	return wppa_is_int( $var ) && $var > '0';
}

function wppa_is_notnegint( $var ) {
	return wppa_is_int( $var ) && $var >= '0';
}

// return true if $var only contains digits and points
function wppa_is_enum( $var ) {
	return '' === str_replace( array( '0','1','2','3','4','5','6','7','8','9','.' ), '', $var );
}

// Log a wppa message.
// We use wppa_get_option() here to prevent wppa_switch() to generate messages itsself.
// Also, we do not use the wppa filesystem function wrappers, to prevent recursive error logging
function wppa_log( $xtype, $msg ) {
global $wppa_session;
global $wppa_log_file;
global $wppa_current_shortcode;
static $busy;
static $last_msg;
static $last_type;
static $repeat_count;

	// Do not log during plugin activation or update
	if ( strpos( $_SERVER['REQUEST_URI'], '/wp-admin/plugins.php' ) !== false ) {
		return;
	}
	if ( strpos( $_SERVER['REQUEST_URI'], '/wp-admin/update-core.php' ) !== false ) {
		return;
	}

	// Do not log logdisplays being updated
	if ( wppa_get( 'slug' ) == 'wppa_list_errorlog' ) {
		return;
	}

	// Do not log when file is not known yet
	if ( ! $wppa_log_file ) {
		return;
	}

	// Sanitize message
	$msg = strip_tags( $msg );
	$msg = wppa_nl2sp( $msg );
	$msg = htmlspecialchars( $msg );
	$msg = str_replace( 'style=&quot;color:darkred&quot;', 'style="color:darkred"', $msg );

	// Test for recursive logging
	if ( $busy ) {
		wppa_update_option( 'wppa_recursive_log', $xtype . ' ' . $msg );
		return;
	}
	$busy = true;

	// Init
	$err = false;

	// Sanitize type
	$type = strtolower( $xtype );

	// Extended loggibg enabled?
	if ( ! in_array( $type, ['err', 'war'] ) && wppa_get_option( 'wppa_enable_ext_logging' ) != 'yes' ) {
		$busy = false;
		return;
	}

	$trace = false;
	$url   = false;

	switch ( $type ) {

		case 'err':
			if ( wppa_get_option( 'wppa_log_errors' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= '{span style="color:red;" }Err{/span}';
			$trace 	= wppa_get_option( 'wppa_log_errors_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_errors_url' ) == 'yes';
			break;
		case 'war':
			if ( wppa_get_option( 'wppa_log_warnings' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= '{span style="color:orange;" }War{/span}';
			$trace 	= wppa_get_option( 'wppa_log_warnings_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_warnings_url' ) == 'yes';
			break;
		case 'cron':
			if ( wppa_get_option( 'wppa_log_cron' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= '{span style="color:blue;" }Cron{/span}';
			$trace 	= wppa_get_option( 'wppa_log_cron_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_cron_url' ) == 'yes';
			break;
		case 'ajax':
			if ( wppa_get_option( 'wppa_log_ajax' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= '{span style="color:blue;" }Ajax{/span}';
			$trace 	= wppa_get_option( 'wppa_log_ajax_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_ajax_url' ) == 'yes';
			break;
		case 'com':
			if ( wppa_get_option( 'wppa_log_comments' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type  = '{span style="color:cyan;" }Com{/span}';
			$trace 	= wppa_get_option( 'wppa_log_comments_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_comments_url' ) == 'yes';
			break;
		case 'fso':
			if ( wppa_get_option( 'wppa_log_fso' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= '{span style="color:blue;" }Fso{/span}';
			$trace 	= wppa_get_option( 'wppa_log_fso_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_fso_url' ) == 'yes';
			break;
		case 'dbg':
			if ( wppa_get_option( 'wppa_log_debug' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= '{span style="color:gray;" }Dbg{/span}';
			$trace 	= wppa_get_option( 'wppa_log_debug_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_debug_url' ) == 'yes';
			break;
		case 'db':
			if ( wppa_get_option( 'wppa_log_database' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= '{span style="color:green;" }DB{/span}';
			$trace 	= wppa_get_option( 'wppa_log_database_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_database_url' ) == 'yes';
			break;
		case 'eml':
			if ( wppa_get_option( 'wppa_log_email' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type  = '{span style="color:blue;" }Eml{/span}';
			$trace 	= wppa_get_option( 'wppa_log_email_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_email_url' ) == 'yes';
			break;
		case 'tim':
			if ( wppa_get_option( 'wppa_log_tim' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= '{span style="color:darkgreen;" }Tim{/span}';
			$trace 	= wppa_get_option( 'wppa_log_tim_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_tim_url' ) == 'yes';
			break;
		case 'idx':
			if ( wppa_get_option( 'wppa_log_idx' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= '{span style="color:darkblue;" }Idx{/span}';
			$trace 	= wppa_get_option( 'wppa_log_idx_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_idx_url' ) == 'yes';
			break;
		case 'obs':
			if ( wppa_get_option( 'wppa_log_obs' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= 'Obs';
			$trace 	= wppa_get_option( 'wppa_log_obs_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_obs_url' ) == 'yes';
			break;
		case 'upl':
			if ( wppa_get_option( 'wppa_log_upl' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= 'Upl';
			$trace 	= wppa_get_option( 'wppa_log_upl_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_upl_url' ) == 'yes';
			break;
		case 'cli':
			if ( wppa_get_option( 'wppa_log_cli' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= '{span style="color:red;" }Client{/span}';
			$trace 	= wppa_get_option( 'wppa_log_cli_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_cli_url' ) == 'yes';
			break;
		default: // case 'misc':
			if ( wppa_get_option( 'wppa_log_misc' ) == 'no' ) {
				$busy = false;
				return;
			}
			$type 	= 'Misc';
			$trace 	= wppa_get_option( 'wppa_log_misc_stack' ) == 'yes';
			$url 	= wppa_get_option( 'wppa_log_misc_url' ) == 'yes';
			break;
	}

	// Get existing log if it exists
	if ( $wppa_log_file && wppa_is_file( $wppa_log_file, false ) ) {

		// Check for max size
		if ( wppa_filesize( $wppa_log_file ) > 1024 * 1024 ) {
			wppa_unlink( $wppa_log_file );
			$contents = array();
		}

		// Not too big, still exists
		else {

			$contents = wppa_get_contents_array( $wppa_log_file, false ); // Do not log error on read

			if ( is_array( $contents ) ) {

				// See if max size exceeded, if so, remove 10 items at the start
				if ( count( $contents ) > 1000 ) {
					$contents = array_slice( $contents, 10 );
				}
			}
			else {
				$contents = array();
			}
		}
	}
	else {
		$contents = array();
	}

	// Get stacktrace 5 levels
	if ( $trace ) {

		$data = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 5 );
		$traceline = ' Stack: ';
		if ( is_array( $data ) ) {
			$i = 1;
			while ( $i < count( $data ) ) {
				$traceline .= ( $i > 1 ? '&lt;- ' : '' ) .
				( isset( $data[$i]['file'] ) ? basename( $data[$i]['file'] ) . ':' : '' ) .
				( isset( $data[$i]['line'] ) ? $data[$i]['line'] . ' ' : '' ) .
				( isset( $data[$i]['function'] ) ? $data[$i]['function'] . '() ' : '' );
				$i++;
			}
		}
	}
	else {
		$traceline = '';
	}

	// Write log message
	if ( $err && $wppa_current_shortcode ) {
		$msg .= ' related shortcode: ' . $wppa_current_shortcode;
	}
	$msg .= $traceline;
	array_push( $contents, '{b}'.$type.'{/b}: on:'.wppa_local_date( 'd.m.Y H:i:s', time()).': '.wppa_get_user().' ('.getmypid().'): '.$msg. "\n" );

	// Log url
	if ( $url ) {
		array_push( $contents, '{b}url{/b}: '.str_replace(home_url(),'...',$_SERVER['REQUEST_URI'])."\n" );
//		if ( count( $_POST ) ) {
//			array_push( $contents, '{b}post{/b}: '.str_replace('\\','',var_export($_POST,true))."\n" );
//		}
	}

	// Done
	$txt = implode( '', $contents );
	if ( $wppa_log_file ) {
		wppa_put_contents( $wppa_log_file, $txt, false );
	}
	else {
		wppa_update_option( 'wppa_last_error', $txt );
	}
	$busy = false;
}

function wppa_is_landscape($img_attr) {
	return ($img_attr[0] > $img_attr[1]);
}

function wppa_get_the_id() {

	$id = '0';
	if ( wppa( 'ajax' ) ) {
		if ( wppa_get( 'page_id' ) ) $id = wppa_get( 'page_id' );
		elseif ( wppa_get( 'p' ) ) $id = wppa_get( 'p' );
		elseif ( wppa_get( 'fromp' ) ) $id = wppa_get( 'fromp' );
	}
	if ( ! $id ) {
		$id = get_the_ID();
	}
	return $id;
}


function wppa_get_artmonkey_size_a( $photo ) {

	$data = wppa_cache_photo( $photo );
	if ( $data ) {
		if ( wppa_switch( 'art_monkey_source' ) ) {
			if ( is_file( wppa_get_source_path( $photo ) ) ) {
				$source = wppa_get_source_path( $photo );
			}
			else {
				$source = wppa_get_photo_path( $photo );
			}
		}
		else {
			$source = wppa_get_photo_path( $photo );
		}
		$imgattr = @ getimagesize( $source );
		if ( is_array( $imgattr ) ) {
			$fs = wppa_get_filesize( $source );
			$result = array( 'x' => $imgattr['0'], 'y' => $imgattr['1'], 's' => $fs );
			return $result;
		}
	}
	return false;
}

function wppa_get_filesize( $file ) {

	if ( is_file( $file ) ) {
		$fs = wppa_filesize( $file );

		if ( $fs > 1024*1024 ) {
			$fs = sprintf('%4.2f Mb', $fs/(1024*1024));
		}
		else {
			$fs = sprintf('%4.2f Kb', $fs/1024);
		}
		return $fs;
	}

	return false;
}


function wppa_get_the_landing_page( $slug, $title ) {

	// Do we use the page ? Some have no type, so use get_option()
	// These types need no page: 'none', 'file', 'lightbox', 'lightboxsingle', 'fullpopup'
	$linktype = get_option( str_replace( 'linkpage', 'linktype', 'wppa_' . $slug ), 'dummy' );
	if ( in_array( $linktype, wppa( 'links_no_page' ) ) ) {
		return '';
	}

	// Yes we need the page
	$page = wppa_opt( $slug );

	// If not on the same page and no page defined or page vanished...
	if ( $page != '-1' && ( ! $page || ! wppa_page_exists( $page ) ) ) {

		// Create one
		$page = wppa_create_page( $title );

		// Remember the page
		wppa_update_option( 'wppa_' . $slug, $page );
		wppa_opt( $slug, $page );
	}
	return $page;
}

function wppa_get_the_auto_page( $photo ) {

	if ( ! $photo ) return '0';					// No photo id, no page
	if ( ! wppa_is_int( $photo ) ) return '0';	// $photo not numeric

	$thumb = wppa_cache_photo( $photo );		// Get photo info

	// Page exists ?
	if ( wppa_page_exists( $thumb['page_id'] ) ) {
		return $thumb['page_id'];
	}

	// Create new page
	$page = wppa_create_page( $thumb['name'], '[wppa type="autopage"]' );

	// Store with photo data
	wppa_update_photo( $photo, ['page_id' => $page] );

	// Update cache
	$thumb['page_id'] = $page;

	return $page;
}

function wppa_remove_the_auto_page( $photo ) {

	if ( ! $photo ) return '0';					// No photo id, no page
	if ( ! wppa_is_int( $photo ) ) return '0';	// $photo not numeric

	$thumb = wppa_cache_photo( $photo );		// Get photo info

	// Page exists ?
	if ( wppa_page_exists( $thumb['page_id'] ) ) {
		wp_delete_post( $thumb['page_id'], true );
		wppa_update_photo( $photo, ['page_id' => '0'] );
	}
}

function wppa_create_page( $title, $shortcode = '[wppa type="landing"]' ) {

	$my_page = array(
				'post_title'    => $title,
				'post_content'  => $shortcode,
				'post_status'   => 'publish',
				'post_type'	  	=> 'page'
			);

	$page = wp_insert_post( $my_page );
	return $page;
}

// Check if a published page exists
function wppa_page_exists( $id ) {
global $wpdb;
//static $pages_exist;

	// Check on valid input
	if ( ! $id ) return false;

	// Already found existing or non existing?
//	if ( isset( $pages_exist[$id] ) ) {
//		return $pages_exist[$id];
//	}

	// Do a query
	$iret = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM " .
											$wpdb->posts . " " .
											"WHERE post_type = 'page' " .
											"AND post_status = 'publish' " .
											"AND ID = %s", $id ) );

	// Save result
//	$pages_exist[$id] = ( $iret > 0 );

	return $iret; //$pages_exist[$id];
}

function wppa_get_album_owner( $id ) {

	$album = wppa_cache_album( $id );
	return $album['owner'];
}

function wppa_get_photo_owner( $id ) {

	$thumb = wppa_cache_photo( $id );
	return $thumb['owner'];
}

function wppa_cdn( $side ) {

	// What did we specify in the settings page?
	$cdn = wppa_opt( 'cdn_service' );

	// Check for fully configured and active
	switch ( $cdn ) {
		case 'local':
			break;

		case 'cloudinary':
		case 'cloudinarymaintenance':
			if ( wppa_opt( 'cdn_cloud_name' ) && wppa_opt( 'cdn_api_key' ) && wppa_opt( 'cdn_api_secret' ) ) {
				if ( $side == 'admin' ) {		// Admin: always return cloudinary
					$cdn = 'cloudinary';
				}
				elseif ( $side == 'front' ) {	// Front: NOT if in maintenance
					if ( $cdn == 'cloudinarymaintenance' ) {
						$cdn = false;
					}
				}
				else {
					wppa_log( 'dbg', 'Wrong arg:'.$side.' in wppa_cdn()' );
					$cdn = false;
				}
			}
			else {
				wppa_log( 'err', 'Incomplete configuration of Cloudinary' );
				$cdn = false;	// Incomplete configuration
			}
			break;

		default:
			$cdn = false;

	}

	return $cdn;
}

function wppa_get_source_path( $id ) {
global $blog_id;
global $wppa_supported_photo_extensions;

	// Source files can have uppercase extensions.
	$temp = array();
	foreach( $wppa_supported_photo_extensions as $ext ) {
		$temp[] = strtoupper( $ext );
	}
	$supext = array_merge( $wppa_supported_photo_extensions, $temp );

	$thumb = wppa_cache_photo( $id );

	// Item present?
	if ( ! $thumb ) {
		return '';
	}

	$album = $thumb['album'];

	// Trashed?
	if ( $album < '0' ) {
		$album = - ( $album + '9' );
	}

	$multi = is_multisite();
	if ( $multi && ! WPPA_MULTISITE_GLOBAL ) {
		$blog = '/blog-'.$blog_id;
	}
	else {
		$blog = '';
	}
	$source_path = wppa_opt( 'source_dir' ).$blog.'/album-'.$album.'/'.$thumb['filename'];
	if ( wppa_is_multi( $id ) ) {
		$path = wppa_strip_ext( $source_path );
		foreach ( $supext as $ext ) {
			$source = $path . '.' . $ext;
			if ( is_file( $source ) ) {
				return $source;
			}
		}
	}

	return $source_path;
}

// Get url of photo with highest available resolution.
// Not for display ( need not to download fast ) but for external services like Fotomoto
function wppa_get_hires_url( $id ) {

/*
	// video or audio? return the poster url
	if ( wppa_is_video( $id ) || wppa_has_audio( $id ) ) {
		$url = wppa_get_photo_url( $id );
		$temp = explode( '?', $url );
		$url = $temp['0'];
		return $url;
	}
*/
/*
	// Try CDN
	if ( wppa_cdn( 'front' ) && ! wppa_too_old_for_cloud( $id ) && ! wppa_is_panorama( $id ) && ! wppa_is_pdf( $id ) ) {
		switch ( wppa_cdn( 'front' ) ) {
			case 'cloudinary':
				$url = wppa_get_cloudinary_url( $id );
				break;
			default:
				$url = '';
		}
		if ( $url ) return $url;
	}
*/
	// Try the orientation corrected source url
	$source_path = wppa_get_o1_source_path( $id );
	if ( is_file( $source_path ) ) {

		// The source file is only http reacheable when it is down from wp-content
		if ( strpos( $source_path, WPPA_CONTENT_PATH ) !== false ) {
			return str_replace( WPPA_CONTENT_PATH, WPPA_CONTENT_URL, $source_path );
		}
	}

	// Try the source url
	$source_path = wppa_get_source_path( $id );
	if ( is_file( $source_path ) ) {

		// The source file is only http reacheable when it is down from ABSPATH
		if ( strpos( $source_path, WPPA_CONTENT_PATH ) !== false ) {
			return str_replace( WPPA_CONTENT_PATH, WPPA_CONTENT_URL, $source_path );
		}
	}

	// Try CDN
	if ( wppa_cdn( 'front' ) && ! wppa_too_old_for_cloud( $id ) && ! wppa_is_panorama( $id ) && ! wppa_is_pdf( $id ) ) {
		switch ( wppa_cdn( 'front' ) ) {
			case 'cloudinary':
				$url = wppa_get_cloudinary_url( $id );
				break;
			default:
				$url = '';
		}
		if ( $url ) return $url;
	}

	// The medium res url
	$hires_url = wppa_get_photo_url( $id );
	$temp = explode( '?', $hires_url );
	return $temp['0'];
}
function wppa_get_lores_url( $id ) {
	$lores_url = wppa_get_photo_url( $id );
	$temp = explode( '?', $lores_url );
	$lores_url = $temp['0'];
	return $lores_url;
}
function wppa_get_tnres_url( $id ) {
	$tnres_url = wppa_get_thumb_url( $id );
	$temp = explode( '?', $tnres_url );
	$tnres_url = $temp['0'];
	return $tnres_url;
}

// Get permalink to photo source file
function wppa_get_source_pl( $id ) {

	// Init
	$result = '';

	// Item not deleted and in an existing album?
	$alb = wppa_get_photo_item( $id, 'album' );
	if ( wppa_album_exists( $alb ) ) {
		return $result;
	}

	// If feature is enabled
	if ( wppa_opt( 'pl_dirname' ) ) {
		$source_path = wppa_fix_poster_ext( wppa_get_source_path( $id ), $id );
		if ( is_file( $source_path ) ) {
			$result = 	content_url() . '/' . 						// http://www.mysite.com/wp-content/
						wppa_opt( 'pl_dirname' ) . '/' .			// wppa-pl/
						wppa_get_album_name_for_pl( $alb ) .
						'/' . basename( $source_path );					// My-Photo.jpg
		}
		$result = str_replace( ' ', '%20', $result );
	}

	return $result;
}

function wppa_get_source_dir() {
global $blog_id;

	$multi = is_multisite();

	if ( $multi && ! WPPA_MULTISITE_GLOBAL ) {
		$blog = '/blog-'.$blog_id;
	}
	else {
		$blog = '';
	}
	$source_dir = wppa_opt( 'source_dir' ).$blog;

	return $source_dir;
}

function wppa_get_source_album_dir( $alb ) {
global $blog_id;

	$multi = is_multisite();

	if ( $multi && ! WPPA_MULTISITE_GLOBAL ) {
		$blog = '/blog-'.$blog_id;
	}
	else {
		$blog = '';
	}
	$source_album_dir = wppa_opt( 'source_dir' ).$blog.'/album-'.$alb;
	if ( ! wppa_is_dir( $source_album_dir ) ) {
		wppa_mkdir( $source_album_dir );
	}

	return $source_album_dir;
}


function wppa_set_default_name( $id, $filename_raw = '' ) {
global $wpdb;

	if ( ! $id || ! wppa_is_int( $id ) ) {
		wppa_log( 'err', 'Missing id in wppa_set_default_name()' );
		return;
	}

	wppa_cache_photo( 'invalidate', $id );
	$thumb = wppa_cache_photo( $id );

	$method 	= wppa_opt( 'newphoto_name_method' );
	$name 		= $thumb['filename']; 	// The default default
	$filename 	= $thumb['filename'];

	if ( ! $filename_raw ) {
		$filename_raw = wppa( 'unsanitized_filename' );
	}
	if ( ! $filename_raw ) {
		$filename_raw = $filename;
	}

	switch ( $method ) {
		case 'none':
			$name = '';
			break;
		case 'filename':
			if ( $filename_raw ) {
				$name = wppa_sanitize_photo_name( $filename_raw );
			}
			break;
		case 'noext':
			if ( $filename_raw ) {
				$name = wppa_sanitize_photo_name( $filename_raw );
			}
			$name = preg_replace('/\.[^.]*$/', '', $name);
			break;
		case 'noextspace':
			if ( $filename_raw ) {
				$name = wppa_sanitize_photo_name( $filename_raw );
			}
			$name = preg_replace('/\.[^.]*$/', '', $name);
			$name = str_replace( '-', ' ', $name );
			break;
		case '2#005':
			$tag = '2#005';
			$name = $wpdb->get_var( $wpdb->prepare( "SELECT description FROM $wpdb->wppa_iptc
													 WHERE photo = %d
													 AND tag = %s", $id, $tag ) );
			break;
		case '2#120':
			$tag = '2#120';
			$name = $wpdb->get_var( $wpdb->prepare( "SELECT description FROM $wpdb->wppa_iptc
													 WHERE photo = %d
													 AND tag = %s", $id, $tag ) );
			break;
		case 'Photo w#id':
			$name = __( 'Photo w#id', 'wp-photo-album-plus' );
			break;
		default:
			$name = '';
			break;
	}
	if ( ( $name ) || $method == 'none' ) {	// Update name
		wppa_update_photo( $id, ['name' => $name] );
	}

	// In case owner must be set to name.
	wppa_set_owner_to_name( $id );
}

function wppa_set_default_tags( $id ) {
global $wpdb;

	$thumb 	= wppa_cache_photo( $id );
	if ( ! $thumb ) return;
	$album 	= wppa_cache_album( $thumb['album'] );
	if ( ! $album ) return;
	$tags 	= wppa_sanitize_tags( str_replace( array( '\'', '"'), ',', wppa_filter_iptc( wppa_filter_exif( $album['default_tags'], $id ), $id ) ) );

	if ( wppa_switch( 'ipc025_to_tags' ) ) {
		$keywords = $wpdb->get_col( "SELECT description FROM $wpdb->wppa_iptc WHERE tag = '2#025' AND photo = $id" );
		if ( $keywords ) {
			foreach( $keywords as $word ) {
				$tags .= ',' . $word;
			}
			$tags = wppa_sanitize_tags( $tags );
		}
	}

	if ( $tags ) {
		wppa_update_photo( $id, ['tags' => $tags] );
	}
}

function wppa_set_default_custom( $id, $force = false ) {

	if ( ! wppa_switch( 'custom_fields' ) ) {
		return;
	}
	$custom = wppa_get_photo_item( $id, 'custom' );
	if ( $custom ) {
		$custom = wppa_unserialize( $custom );
	}
	else {
		$custom = array( '', '', '', '', '', '', '', '', '', '' );
	}
	$any = false;
	$i = 0;
	while ( $i < 10 ) {
		$data = $custom[$i];
		if ( ! $data || $force ) {
			$data = wppa_opt( 'custom_default_' . $i, '' );
			$new_data = wppa_filter_iptc( $data, $id );
			$new_data = wppa_filter_exif( $new_data, $id );
			$new_data = trim( $new_data, ', ' );
			if ( $new_data != $data ) {
				$custom[$i] = $new_data;
				$any = true;
			}
		}
		$i++;
	}
	if ( $any ) {
		wppa_update_photo( $id, ['custom' => serialize( $custom )] );
	}
}

function wppa_test_for_medal( $id ) {
global $wpdb;

	$thumb = wppa_cache_photo( $id );
	$status = $thumb['status'];

	if ( wppa_opt( 'medal_bronze_when' ) || wppa_opt( 'medal_silver_when' ) || wppa_opt( 'medal_gold_when' ) ) {
		$max_score = wppa_opt( 'rating_max' );

		$max_ratings = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_rating
														WHERE photo = %d AND value = %s
														AND status = %s", $id, $max_score, 'publish' ) );

		if ( $max_ratings >= wppa_opt( 'medal_gold_when' ) ) $status = 'gold';
		elseif ( $max_ratings >= wppa_opt( 'medal_silver_when' ) ) $status = 'silver';
		elseif ( $max_ratings >= wppa_opt( 'medal_bronze_when' ) ) $status = 'bronze';
	}

	if ( $status != $thumb['status'] ) {
		$thumb['status'] = $status;
		wppa_update_photo( $id, ['status' => $status] );
	}
}

function wppa_get_the_bestof( $count, $period, $sortby, $what ) {
global $wpdb;

	// Phase 1, find the period we are talking about
	// find $start and $end
	switch ( $period ) {
		case 'lastweek':
			$start 	= wppa_get_timestamp( 'lastweekstart' );
			$end   	= wppa_get_timestamp( 'lastweekend' );
			break;
		case 'thisweek':
			$start 	= wppa_get_timestamp( 'thisweekstart' );
			$end   	= wppa_get_timestamp( 'thisweekend' );
			break;
		case 'lastmonth':
			$start 	= wppa_get_timestamp( 'lastmonthstart' );
			$end 	= wppa_get_timestamp( 'lastmonthend' );
			break;
		case 'thismonth':
			$start 	= wppa_get_timestamp( 'thismonthstart' );
			$end 	= wppa_get_timestamp( 'thismonthend' );
			break;
		case 'lastyear':
			$start 	= wppa_get_timestamp( 'lastyearstart' );
			$end 	= wppa_get_timestamp( 'lastyearend' );
			break;
		case 'thisyear':
			$start 	= wppa_get_timestamp( 'thisyearstart' );
			$end 	= wppa_get_timestamp( 'thisyearend' );
			break;
		default:
			return 'Unimplemented period: '.$period;
	}

	// Phase 2, get the ratings of the period
	// find $ratings, ordered by photo id
	$ratings 	= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_rating
													   WHERE timestamp >= %s
													   AND timestamp < %s
													   ORDER BY photo", $start, $end ), ARRAY_A );

	// Strip the ratings of non visible items
	$new_ratings = array();
	foreach( $ratings as $rating ) {
		if ( wppa_is_photo_visible( $rating['photo'] ) ) {
			$new_ratings[] = $rating;
		}
	}
	$ratings = $new_ratings;

	// Phase 3, set up an array with data we need
	// There are two methods: photo oriented and owner oriented, depending on

	// Each element reflects a photo ( key = photo id ) and is an array with items: maxratings, meanrating, ratings, totvalue.
	$ratmax	= wppa_opt( 'rating_max' );
	$data 	= array();
	foreach ( $ratings as $rating ) {
		$key = $rating['photo'];
		if ( ! isset( $data[$key] ) ) {
			$data[$key] = array();
			$data[$key]['ratingcount'] 		= '1';
			$data[$key]['maxratingcount'] 	= $rating['value'] == $ratmax ? '1' : '0';
			$data[$key]['totvalue'] 		= $rating['value'];
		}
		else {
			$data[$key]['ratingcount'] 		+= '1';
			$data[$key]['maxratingcount'] 	+= $rating['value'] == $ratmax ? '1' : '0';
			$data[$key]['totvalue'] 		+= $rating['value'];
		}
	}

	foreach ( array_keys( $data ) as $key ) {
		$thumb = wppa_cache_photo( $key );
		$data[$key]['meanrating'] = $data[$key]['totvalue'] / $data[$key]['ratingcount'];
		$user = wppa_get_user_by( 'login', sanitize_user( $thumb['owner'] ) );
		if ( $user ) {
			$data[$key]['user'] = $user->display_name;
		}
		else { // user deleted
			$data[$key]['user'] = $thumb['owner'];
		}
		$data[$key]['owner'] = $thumb['owner'];
	}

	// Now we split into search for photos and search for owners

	if ( $what == 'photo' ) {

		// Pase 4, sort to the required sequence
		$data = wppa_array_sort( $data, $sortby, SORT_DESC );

	}
	else { 	// $what == 'owner'

		// Phase 4, combine all photos of the same owner
		wppa_array_sort( $data, 'user' );
		$temp = $data;
		$data = array();
		foreach ( array_keys( $temp ) as $key ) {
			if ( ! isset( $data[$temp[$key]['user']] ) ) {
				$data[$temp[$key]['user']]['photos'] 			= '1';
				$data[$temp[$key]['user']]['ratingcount'] 		= $temp[$key]['ratingcount'];
				$data[$temp[$key]['user']]['maxratingcount'] 	= $temp[$key]['maxratingcount'];
				$data[$temp[$key]['user']]['totvalue'] 			= $temp[$key]['totvalue'];
				$data[$temp[$key]['user']]['owner'] 			= $temp[$key]['owner'];
			}
			else {
				$data[$temp[$key]['user']]['photos'] 			+= '1';
				$data[$temp[$key]['user']]['ratingcount'] 		+= $temp[$key]['ratingcount'];
				$data[$temp[$key]['user']]['maxratingcount'] 	+= $temp[$key]['maxratingcount'];
				$data[$temp[$key]['user']]['totvalue'] 			+= $temp[$key]['totvalue'];
			}
		}
		foreach ( array_keys( $data ) as $key ) {
			$data[$key]['meanrating'] = $data[$key]['totvalue'] / $data[$key]['ratingcount'];
		}
		$data = wppa_array_sort( $data, $sortby, SORT_DESC );
	}

	// Phase 5, truncate to the desired length
	$c = '0';
	foreach ( array_keys( $data ) as $key ) {
		$c += '1';
		if ( $c > $count ) unset ( $data[$key] );
	}

	// Phase 6, return the result
	if ( count( $data ) ) {
		return $data;
	}
	else {
		return 	__('There are no ratings between', 'wp-photo-album-plus' ) .
				'<br>' .
				wppa_local_date( 'F j, Y, H:i s', $start ) .
				' ' . __('and', 'wp-photo-album-plus' ) .
				'<br>' .
				wppa_local_date( 'F j, Y, H:i s', $end ) .
				'.';
	}
}

// Retrieve the number of sub albums ( if any )
function wppa_has_children( $alb ) {
global $wpdb;
static $childcounts;

	// See if done this alb earlier
	if ( isset( $childcounts[$alb] ) ) {
		$result = $childcounts[$alb];
	}
	else {
		$result = $wpdb->get_var( $wpdb->prepare( 	"SELECT COUNT(*) " .
													"FROM $wpdb->wppa_albums " .
													"WHERE a_parent = %s", $alb) );

		// Save result
		$childcounts[$alb] = $result;
	}

	return $result;
}

// Get an enumeration of all the (grand)children of some album spec.
// Album spec may be a number or an enumeration
function wppa_alb_to_enum_children( $xparents ) {

	$parents = wppa_expand_enum( $xparents );

	if ( strpos( $parents, '.' ) !== false ) {
		$albums = explode( '.', $parents );
	}
	else {
		$albums = array( $parents );
	}
	$result = '';
	foreach( $albums as $alb ) {
		if ( ! $alb ) $alb = '0';
		$result .= _wppa_alb_to_enum_children( $alb );
		$result = trim( $result, '.' ).'.';
	}
	$result = str_replace( '..', '.', $result );
	$result = trim( $result, '.' );

	return $result;
}

function _wppa_alb_to_enum_children( $alb ) {
global $wpdb;
static $child_list;

	// Init
	$result = '';

	// Init child list
	if ( ! $child_list ) {
		$child_list = wppa_get_option( 'wppa_child_list', array() );
	}

	// Done this one before?
	if ( isset( $child_list[$alb] ) ) {
		return wppa_expand_enum( $child_list[$alb] );
	}

	// Get the data
	if ( $alb > '0' ) {
		$result = $alb;
	}
	$children = $wpdb->get_results( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums
													 WHERE a_parent = %s", $alb ), ARRAY_A );
	if ( $children ) foreach ( $children as $child ) {
		$result .= '.' . _wppa_alb_to_enum_children( $child['id'] );
		$result = trim( $result, '.' );
	}

	// Sequentialize
	if ( strpos( $result, '.' ) !== false ) {
		$r = explode( '.', $result );
		sort( $r );
		$result = implode( '.', $r );
	}

	// Store in cache
	$child_list[$alb] = wppa_compress_enum( $result );
	ksort( $child_list );
	wppa_update_option( 'wppa_child_list', $child_list );

	// Return requested data
	return $result;
}

// Remove from childlist
function wppa_childlist_remove( $alb ) {

	$any = false;

	$child_list = wppa_get_option( 'wppa_child_list', array() );
	foreach( array_keys( $child_list ) as $key ) {
		$line = '.' . wppa_expand_enum( $child_list[$key] ) . '.';
		if ( $key == $alb || strpos( $line, '.'.$alb.'.' ) !== false  ) {

			unset( $child_list[$key] );
			$any = true;
		}
	}
	if ( $any ) {
		wppa_update_option( 'wppa_child_list', $child_list );
	}
}

function wppa_compress_enum( $enum ) {
	$result = $enum;
	if ( strpos( $enum, '.' ) !== false ) {
		$result = explode( '.', $enum );
		sort( $result, SORT_NUMERIC );
		$old = '-99';
		foreach ( array_keys( $result ) as $key ) { 	// Remove dups
			if ( $result[$key] == $old ) unset ( $result[$key] );
			else $old = $result[$key];
		}
		$result = wppa_index_array_to_string( $result );
		$result = str_replace( ',', '.', $result );
	}
	$result = trim( $result, '.' );
	return $result;
}

function wppa_expand_enum( $enum ) {
	$result = $enum;
	$result = str_replace( '.', ',', $result );
	$result = str_replace( ',,', '..', $result );
	$result = wppa_index_string_to_array( $result );
	$result = implode( '.', $result );
	return $result;
}

// Compute avg rating and count and put it in photo data
function wppa_rate_photo( $id ) {
global $wpdb;

	// Likes only?
	if ( wppa_opt( 'rating_display_type' ) == 'likes' ) {

		// Get rating(like)count
		$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*)
												  FROM $wpdb->wppa_rating
												  WHERE photo = %d
												  AND status = 'publish'", $id ) );

		// Update photo
		wppa_update_photo( $id, ['rating_count' => $count, 'mean_rating' => '0'] );
	}
	else {

		// Get all ratings for this photo
		$ratings = $wpdb->get_results( $wpdb->prepare( "SELECT value
														FROM $wpdb->wppa_rating
														WHERE photo = %d
														AND status = 'publish'", $id ), ARRAY_A );

		// Init
		$the_value = '0';
		$the_count = '0';

		// Compute mean value and count
		if ( $ratings ) {

			foreach ( $ratings as $rating ) {

				if ( $rating['value'] == '-1' ) {
					$the_value += wppa_opt( 'dislike_value' );
				}
				else {
					$the_value += $rating['value'];
				}

				$the_count++;
			}
		}
		if ( $the_count ) $the_value /= $the_count;
		if ( wppa_opt( 'rating_max' ) == '1' ) $the_value = '0';
		if ( $the_value == '10' ) $the_value = '9.9999999';	// mean_rating is a text field. for sort order reasons we make 10 into 9.99999

		// Update photo
		wppa_update_photo( $id, ['mean_rating' => $the_value, 'rating_count' => $the_count] );

		// Set status to a medaltype if appiliccable
		wppa_test_for_medal( $id );
	}
}

function wppa_strip_ext( $file ) {

	// First strip possible version ( e.g. ?ver=4711 )
	$qmpos = strpos( $file, '?' );
	if ( $qmpos !== false ) {
		$file = substr( $file, 0, $qmpos );
	}
	$strlen = strlen( $file );
	$dotpos = strrpos( $file, '.' );
	if ( $dotpos > ( $strlen - 6 ) ) {
		$result = substr( $file, 0, $dotpos );
	}
	else {
		$result = $file;
	}

	return $result; // preg_replace('/\.[^.]*$/', '', $file);
}

function wppa_get_ext( $file ) {

	// First strip possible version ( e.g. ?ver=4711 )
	$qmpos = strpos( $file, '?' );
	if ( $qmpos !== false ) {
		$file = substr( $file, 0, $qmpos );
	}
	$strlen = strlen( $file );
	$dotpos = strrpos( $file, '.' );
	if ( $dotpos > ( $strlen - 6 ) ) {
		$result = substr( $file, $dotpos + 1 );
	}
	else {
		$result = '';
	}

	return $result; // str_replace( wppa_strip_ext( $file ).'.', '', $file );
}

function wppa_encode_uri_component( $xstr ) {
	$str = $xstr;
	$illegal = array( '?', '&', '#', '/', '"', "'", ' ' );
	foreach ( $illegal as $char ) {
		$str = str_replace( $char, sprintf( '%%%X', ord($char) ), $str );
	}
	return $str;
}

function wppa_decode_uri_component( $xstr ) {
	$str = $xstr;
	$illegal = array( '?', '&', '#', '/', '"', "'", ' ' );
	foreach ( $illegal as $char ) {
		$str = str_replace( sprintf( '%%%X', ord($char) ), $char, $str );
		$str = str_replace( sprintf( '%%%x', ord($char) ), $char, $str );
	}
	return $str;
}

function wppa_force_numeric_else( $value, $default ) {
	if ( ! $value ) return $value;
	if ( ! wppa_is_int( $value ) ) return $default;
	return $value;
}

// Same as wp sanitize_file_name, except that it can be used for a pathname also.
// If a pathname: only the basename of the path is sanitized.
function wppa_sanitize_file_name( $file, $check_length = true ) {

	// Any sanitize required?
	if ( ! ( wppa_switch( 'remove_accents' ) || wppa_switch( 'sanitize_import' ) ) ) {
		return $file;
	}

	// Make sure its utf8
	if ( ! seems_utf8( $file ) ) {
		$file = utf8_encode( $file );
	}

	// Only accemts?
	if ( wppa_switch( 'remove_accents' ) ) {
		$file = remove_accents( $file );
	}

	// No furher sanitize?
	if ( ! wppa_switch( 'sanitize_import' ) ) {
		return $file;
	}

	$temp 	= explode( '/', $file );
	$cnt 	= count( $temp );
	$temp[$cnt - 1] = sanitize_file_name( $temp[$cnt - 1] );
	$maxlen = wppa_opt( 'max_filename_length' );
	if ( $maxlen && $check_length ) {
		if ( strpos( $temp[$cnt - 1], '.' ) !== false ) {
			$name = wppa_strip_ext( $temp[$cnt - 1] );
			$ext = str_replace( $name.'.', '', $temp[$cnt - 1] );
			if ( strlen( $name ) > $maxlen ) {
				$name = substr( $name, 0, $maxlen );
				$temp[$cnt - 1] = $name.'.'.$ext;
			}
		}
		else {
			if ( strlen( $temp[$cnt - 1] ) > $maxlen ) {
				$temp[$cnt - 1] = substr( $temp[$cnt - 1], 0, $maxlen );
			}
		}
	}
	$file 	= implode( '/', $temp );
	$file 	= trim ( $file );
	return $file;
}

// Create a html safe photo name from a filename. May be a pathname
function wppa_sanitize_photo_name( $file ) {
	$result = htmlspecialchars( strip_tags( stripslashes( basename( $file ) ) ) );
	$maxlen = wppa_opt( 'max_photoname_length' );
	if ( $maxlen && strlen( $result ) > $maxlen ) {
		$result = wppa_strip_ext( $result ); // First remove any possible file-extension
		if ( strlen( $result ) > $maxlen ) {
			$result = substr( $result, 0, $maxlen );	// Truncate
		}
	}
	return $result;
}

// Get meta keywords of a photo
function wppa_get_keywords( $id ) {
static $wppa_void_keywords;

	if ( ! $id ) return '';

	if ( empty ( $wppa_void_keywords ) ) {
		$wppa_void_keywords	= array( 	__('Not Defined', 'wp-photo-album-plus' ),
										__('Manual', 'wp-photo-album-plus' ),
										__('Program AE', 'wp-photo-album-plus' ),
										__('Aperture-priority AE', 'wp-photo-album-plus' ),
										__('Shutter speed priority AE', 'wp-photo-album-plus' ),
										__('Creative (Slow speed)', 'wp-photo-album-plus' ),
										__('Action (High speed)', 'wp-photo-album-plus' ),
										__('Portrait', 'wp-photo-album-plus' ),
										__('Landscape', 'wp-photo-album-plus' ),
										__('Bulb', 'wp-photo-album-plus' ),
										__('Average', 'wp-photo-album-plus' ),
										__('Center-weighted average', 'wp-photo-album-plus' ),
										__('Spot', 'wp-photo-album-plus' ),
										__('Multi-spot', 'wp-photo-album-plus' ),
										__('Multi-segment', 'wp-photo-album-plus' ),
										__('Partial', 'wp-photo-album-plus' ),
										__('Other', 'wp-photo-album-plus' ),
										__('No Flash', 'wp-photo-album-plus' ),
										__('Fired', 'wp-photo-album-plus' ),
										__('Fired, Return not detected', 'wp-photo-album-plus' ),
										__('Fired, Return detected', 'wp-photo-album-plus' ),
										__('On, Did not fire', 'wp-photo-album-plus' ),
										__('On, Fired', 'wp-photo-album-plus' ),
										__('On, Return not detected', 'wp-photo-album-plus' ),
										__('On, Return detected', 'wp-photo-album-plus' ),
										__('Off, Did not fire', 'wp-photo-album-plus' ),
										__('Off, Did not fire, Return not detected', 'wp-photo-album-plus' ),
										__('Auto, Did not fire', 'wp-photo-album-plus' ),
										__('Auto, Fired', 'wp-photo-album-plus' ),
										__('Auto, Fired, Return not detected', 'wp-photo-album-plus' ),
										__('Auto, Fired, Return detected', 'wp-photo-album-plus' ),
										__('No flash function', 'wp-photo-album-plus' ),
										__('Off, No flash function', 'wp-photo-album-plus' ),
										__('Fired, Red-eye reduction', 'wp-photo-album-plus' ),
										__('Fired, Red-eye reduction, Return not detected', 'wp-photo-album-plus' ),
										__('Fired, Red-eye reduction, Return detected', 'wp-photo-album-plus' ),
										__('On, Red-eye reduction', 'wp-photo-album-plus' ),
										__('Red-eye reduction, Return not detected', 'wp-photo-album-plus' ),
										__('On, Red-eye reduction, Return detected', 'wp-photo-album-plus' ),
										__('Off, Red-eye reduction', 'wp-photo-album-plus' ),
										__('Auto, Did not fire, Red-eye reduction', 'wp-photo-album-plus' ),
										__('Auto, Fired, Red-eye reduction', 'wp-photo-album-plus' ),
										__('Auto, Fired, Red-eye reduction, Return not detected', 'wp-photo-album-plus' ),
										__('Auto, Fired, Red-eye reduction, Return detected', 'wp-photo-album-plus' ),
										'album', 'albums', 'content', 'http',
										'source', 'wp', 'uploads', 'thumbs',
										'wp-content', 'wppa-source',
										'border', 'important', 'label', 'padding',
										'segment', 'shutter', 'style', 'table',
										'times', 'value', 'views', 'wppa-label',
										'wppa-value', 'weighted', 'wppa-pl',
										'datetime', 'exposureprogram', 'focallength', 'isospeedratings', 'meteringmode', 'model', 'photographer',
										str_replace( '/', '', site_url() )
									);

		// make a string
		$temp = implode( ',', $wppa_void_keywords );

		// Downcase
		$temp = strtolower( $temp );

		// Remove spaces and funny chars
		$temp = str_replace( array( ' ', '-', '"', "'", '\\', '>', '<', ',', ':', ';', '!', '?', '=', '_', '[', ']', '(', ')', '{', '}' ), ',', $temp );
		$temp = str_replace( ',,', ',', $temp );

		// Make array
		$wppa_void_keywords = explode( ',', $temp );

		// Sort array
		sort( $wppa_void_keywords );

		// Remove dups
		$start = 0;
		foreach ( array_keys( $wppa_void_keywords ) as $key ) {
			if ( $key > 0 ) {
				if ( $wppa_void_keywords[$key] == $wppa_void_keywords[$start] ) {
					unset ( $wppa_void_keywords[$key] );
				}
				else {
					$start = $key;
				}
			}
		}
	}

	$text 	= wppa_get_photo_name( $id )  .' ' . wppa_get_photo_desc( $id );
	$text 	= str_replace( array( '/', '-' ), ' ', $text );
	$words 	= wppa_index_raw_to_words( $text );
	foreach ( array_keys( $words ) as $key ) {
		if ( 	wppa_is_int( $words[$key] ) ||
				in_array( $words[$key], $wppa_void_keywords ) ||
				strlen( $words[$key] ) < 5 ) {
			unset ( $words[$key] );
		}
	}
	$result = implode( ', ', $words );
	return $result;
}

function wppa_is_orig ( $path ) {
	$file = basename( $path );
	$file = wppa_strip_ext( $file );
	$temp = explode( '-', $file );
	if ( ! is_array( $temp ) ) return true;
	$temp = $temp[ count( $temp ) -1 ];
	$temp = explode( 'x', $temp );
	if ( ! is_array( $temp ) ) return true;
	if ( count( $temp ) != 2 ) return true;
	if ( ! wppa_is_int( $temp[0] ) ) return true;
	if ( ! wppa_is_int( $temp[1] ) ) return true;
	return false;
}

function wppa_browser_can_html5() {

	if ( ! isset( $_SERVER["HTTP_USER_AGENT"] ) ) return false;

	$is_opera 	= strpos( $_SERVER["HTTP_USER_AGENT"], 'OPR' );
	$is_ie 		= strpos( $_SERVER["HTTP_USER_AGENT"], 'Trident' );
	$is_safari 	= strpos( $_SERVER["HTTP_USER_AGENT"], 'Safari' );
	$is_firefox = strpos( $_SERVER["HTTP_USER_AGENT"], 'Firefox' );

	if ( $is_opera ) 	return true;
	if ( $is_safari ) 	return true;
	if ( $is_firefox ) 	return true;

	if ( $is_ie ) {
		$tri_pos = strpos( $_SERVER["HTTP_USER_AGENT"], 'Trident/' );
		$tri_ver = substr( $_SERVER["HTTP_USER_AGENT"], $tri_pos+8, 3 );
		if ( $tri_ver >= 6.0 ) return true; // IE 10 or later
	}

	return false;
}

function wppa_get_comten_ids( $max_count = 0, $albums = array() ) {
global $wpdb;

	// Validate args
	if ( ! wppa_is_posint( $max_count ) ) {
		$max_count = 0;
	}
	foreach( array_keys( $albums ) as $key ) {
		if ( ! wppa_is_posint( $albums[$key] ) ) {
			unset( $albums[$key] );
		}
	}

	// Find real maxcount
	if ( ! $max_count ) {
		$max_count = wppa_opt( 'comten_count' );
	}

	// Find raw commented photo ids
	$photo_ids = $wpdb->get_col( $wpdb->prepare( "SELECT photo FROM $wpdb->wppa_comments
												  WHERE status = 'approved'
												  ORDER BY timestamp DESC LIMIT %d", 100 * $max_count ) );

	$result = array();

	// Get unique photo ids in possibly supplied albums
	if ( is_array( $photo_ids ) ) {
		foreach( $photo_ids as $ph ) {
			if ( empty( $albums ) || in_array( wppa_get_photo_item( $ph, 'album' ), $albums ) ) {
				if ( ! in_array( $ph, $result ) ) {
					$result[] = $ph;
				}
			}
		}
	}

	// Remove void photo items
	$result = wppa_strip_void_photos( $result );

	// Clip to max count
	if ( count( $result ) > $max_count ) {
		$result = array_slice( $result, 0, $max_count );
	}

	return $result;
}

// Filter for Plugin CM Tooltip Glossary
function wppa_filter_glossary( $desc ) {
static $wppa_cmt;

	// Do we need this?
	if ( wppa_switch( 'use_CMTooltipGlossary' ) && class_exists( 'CMTooltipGlossaryFrontend' ) ) {

		// Class initialized?
		if ( empty( $wppa_cmt ) ) {
			$wppa_cmt = new CMTooltipGlossaryFrontend;
		}

		// Do we already start with a <p> ?
		$start_p = ( strpos( $desc, '<p' ) === 0 );

		// remove newlines, glossary converts them to <br>
		$desc = str_replace( array( "\n", "\r", "\t" ), '', $desc );
		$desc = $wppa_cmt->cmtt_glossary_parse( $desc, true );

		// Remove <p> and </p> that CMTG added around
		if ( ! $start_p ) {
			if ( substr( $desc, 0, 3 ) == '<p>' ) {
				$desc = substr( $desc, 3 );
			}
			if ( substr( $desc, -4 ) == '</p>' ) {
				$desc = substr( $desc, 0, strlen( $desc ) - 4 );
			}
		}
	}

	return $desc;
}

// Convert file extension to lowercase
function wppa_down_ext( $file ) {
	if ( strpos( $file, '.' ) === false ) return $file;	// no . found
	$dotpos = strrpos( $file, '.' );
	$file = substr( $file, 0, $dotpos ) . strtolower( substr( $file, $dotpos ) );
	return $file;
}

// See of a photo db entry is a multimedia entry
function wppa_is_multi( $id ) {

	if ( ! $id ) return false;			// No id

	$ext = wppa_get_photo_item( $id, 'ext' );
	return ( $ext == 'xxx' );
}

// If it just a photo?
function wppa_is_photo( $id ) {

	if ( ! $id ) return false;

	if ( wppa_is_multi( $id ) ) return false;
	if ( wppa_is_pdf( $id ) ) return false;

	return true;
}

// Does the mm item has a poster image?
function  wppa_has_poster( $id ) {

	if ( ! $id ) return false;

	if ( ! wppa_is_multi( $id ) ) return false;

	$file = wppa_get_photo_path( $id );
	if ( wppa_is_file( $file ) && basename( $file ) != 'audiostub.jpg' ) {

		return true;
	}
	return false;
}

// Is it a zoomable photo?
function wppa_is_zoomable( $id ) {

	if ( ! $id ) return false;

	if ( ! wppa_is_photo( $id ) ) return false;
	if ( wppa_is_panorama( $id ) ) return false;

	$album_zoom = wppa_get_album_item( wppa_get_photo_item( $id, 'album' ), 'zoomable' );

	if ( $album_zoom ) { // can be 'on', 'off', '' (=default: see global setting 'zoom_on' )
		return $album_zoom == 'on';
	}
	return ( wppa_switch( 'zoom_on' ) );
}

function wppa_fix_poster_ext( $fileorurl, $id ) {

	// Has it extension .xxx ?
	if ( substr( $fileorurl, -4 ) != '.xxx' &&
		 strpos( $fileorurl, '.xxx?ver' ) === false &&
		 substr( $fileorurl, -4 ) != '.pdf' &&
		 strpos( $fileorurl, '.pdf?ver' ) === false ) {
		return $fileorurl;
	}

	// Is it a pdf?
	if ( wppa_is_pdf( $id ) ) {

		// Url ?
		if ( strpos( $fileorurl, 'http://' ) !== false || strpos( $fileorurl, 'https://' ) !== false ) {
			return WPPA_UPLOAD_URL . '/'. 'documentstub.png';
		}

		// File
		else {
			$source_poster = str_replace( '.pdf', '.jpg', $fileorurl );
			if ( wppa_is_file( $source_poster ) ) {
				return $source_poster;
			}
			$source_poster = str_replace( '.pdf', '.png', $fileorurl );
			if ( wppa_is_file( $source_poster ) ) {
				return $source_poster;
			}
			else {
				return WPPA_UPLOAD_PATH . '/' . 'documentstub.png';
			}
		}
	}

	else {

		// Get available ext
		$poster_ext = wppa_geter_ext( $id );

		// If found, replace extension to ext of existing file
		if ( $poster_ext ) {
			return str_replace( '.xxx', '.'.$poster_ext, $fileorurl );
		}

		// Not found. If audio, return audiostub file or url
		if ( wppa_has_audio( $id ) ) {

			// Url ?
			if ( strpos( $fileorurl, 'http://' ) !== false || strpos( $fileorurl, 'https://' ) !== false ) {
				if ( wppa_switch( 'use_audiostub' ) ) {
					return WPPA_UPLOAD_URL . '/'. 'audiostub.jpg';
				}
				else {
					return WPPA_UPLOAD_URL . '/'. 'transparent.png';
				}
			}

			// File
			else {
				if ( wppa_switch( 'use_audiostub' ) ) {
					return WPPA_UPLOAD_PATH . '/' . 'audiostub.jpg';
				}
				else {
					return WPPA_UPLOAD_PATH . '/' . 'transparent.png';
				}
			}
		}

		// Not found. Is Video, return as jpg
		return str_replace( '.xxx', '.jpg', $fileorurl );
	}
}

function wppa_geter_ext( $id ) {
global $wppa_supported_photo_extensions;

	// Init
	$path 		= wppa_get_photo_path( $id, false );
	$raw_path 	= wppa_strip_ext( $path );

	// Find existing photofiles
	foreach ( $wppa_supported_photo_extensions as $ext ) {
		if ( is_file( $raw_path.'.'.$ext ) ) {
			return $ext;	// Found !
		}
	}

	// Not found.
	return false;
}

// Like wp sanitize_text_field, but also removes chars 0x00..0x07
function wppa_sanitize_text( $txt ) {
	$result = sanitize_text_field( $txt );
	$result = str_replace( array(chr(0), chr(1), chr(2), chr(3),chr(4), chr(5), chr(6), chr(7) ), '', $result );
	$result = trim( $result );
	return $result;
}

function wppa_is_mobile() {
	$result = false;
	$detect = new wppa_mobile_detect();
	if ( $detect->isMobile() ) {
		$result = true;
	}
	return $result;
}

function wppa_is_ipad() {
	$result = false;
	$detect = new wppa_mobile_detect();
	if ( $detect->isDevice( 'ipad' ) ) {
		$result = true;
	}
	return $result;
}

function wppa_is_iphoneoripad() {
	$result = false;
	$detect = new wppa_mobile_detect();
	if ( $detect->isDevice( 'iphone' ) || $detect->isDevice( 'ipad' ) ) {
		$result = true;
	}
	return $result;
}

function wppa_is_phone() {

	$result = false;
	if ( wppa_is_mobile() && !  wppa_is_ipad() ) {
		$result = true;
	}
	return $result;
}

function wppa_is_chrome() {
	return ( get_browser_name() == 'Chrome' );
}

function wppa_is_firefox() {
	return ( get_browser_name() == 'Firefox' );
}

function wppa_is_edge() {
	return ( get_browser_name() == 'Edge' );
}

function wppa_is_ie() {
	return ( get_browser_name() == 'Internet Explorer' );
}

function wppa_is_safari() {
	return ( get_browser_name() == 'Safari' );
}

function wppa_is_opera() {
	return ( get_browser_name() == 'Opera' );
}

function get_browser_name() {
	$user_agent = isset ( $_SERVER["HTTP_USER_AGENT"] ) ? $_SERVER["HTTP_USER_AGENT"] : '';
	if ( $user_agent ) {
		if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
		elseif (strpos($user_agent, 'Edge')) return 'Edge';
		elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
		elseif (strpos($user_agent, 'Safari')) return 'Safari';
		elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
		elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
    }
    return 'Other';
}

// Like wp_nonce_field
// To prevent duplicate id's, we externally add an id number ( e.g. album ) and internally the mocc number.
function wppa_nonce_field( $action = -1, $name = "_wpnonce", $referer = true, $wppa_id = '0' ) {

	$name = esc_attr( $name );
	$nonce_field = 	'<input' .
						' type="hidden"' .
						' id="' . $name . '-' . $wppa_id . '-' . wppa( 'mocc' ) . '"' .
						' name="' . $name . '"' .
						' value="' . wp_create_nonce( $action ) . '"' .
						' />';

	if ( $referer ) {
		$nonce_field .= wp_referer_field( false );
	}

	return $nonce_field;
}

// Like convert_smilies, but directe rendered to <img> tag to avoid performance bottleneck for emoji's when ajax on firefox
function wppa_convert_smilies( $text ) {
static $smilies;

	// Initialize
	if ( ! is_array( $smilies ) ) {
		$smilies = array(	";-)" 		=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f609.png" />',
							":|" 		=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f610.png" />',
							":x" 		=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f621.png" />',
							":twisted:" => '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f608.png" />',
							":shock:" 	=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f62f.png" />',
							":razz:" 	=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f61b.png" />',
							":oops:" 	=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f633.png" />',
							":o" 		=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f62e.png" />',
							":lol:" 	=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f606.png" />',
							":idea:" 	=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f4a1.png" />',
							":grin:" 	=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f600.png" />',
							":evil:" 	=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f47f.png" />',
							":cry:" 	=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f625.png" />',
							":cool:" 	=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f60e.png" />',
							":arrow:" 	=> '<img class="emoji" draggable="false" alt="?" src="http://s.w.org/images/core/emoji/72x72/27a1.png" />',
							":???:" 	=> '<img class="emoji" draggable="false" alt="??" src="http://s.w.org/images/core/emoji/72x72/1f615.png" />',
							":?:" 		=> '<img class="emoji" draggable="false" alt="?" src="http://s.w.org/images/core/emoji/72x72/2753.png" />',
							":!:" 		=> '<img class="emoji" draggable="false" alt="?" src="http://s.w.org/images/core/emoji/72x72/2757.png" />'
		);
	}

	// Perform
	$result = $text;
	foreach ( array_keys( $smilies ) as $key ) {
		$result = str_replace( $key, $smilies[$key], $result );
	}

	// Convert non-emoji's
	$result = convert_smilies( $result );

	// SSL?
	if ( is_ssl() ) {
		$result = str_replace( 'http://', 'https://', $result );
	}

	// Done
	return $result;
}

function wppa_is_virtual() {

	if ( wppa( 'is_virtual' ) ) return true;
	if ( wppa( 'is_topten' ) ) return true;
	if ( wppa( 'is_lasten' ) ) return true;
	if ( wppa( 'is_featen' ) ) return true;
	if ( wppa( 'is_comten' ) ) return true;
	if ( wppa( 'is_tag' ) ) return true;
	if ( wppa( 'is_related' ) ) return true;
	if ( wppa( 'is_upldr' ) ) return true;
	if ( wppa( 'is_cat' ) ) return true;
	if ( wppa( 'is_supersearch' ) ) return true;
	if ( wppa( 'src' ) ) return true;
	if ( wppa( 'supersearch' ) ) return true;
	if ( wppa( 'searchstring' ) ) return true;
	if ( wppa( 'calendar' ) ) return true;
	if ( wppa_get( 'vt' ) ) return true;
	if ( wppa( 'is_potdhis' ) ) return true;

	return false;
}

function wppa_too_old_for_cloud( $id ) {

	$thumb = wppa_cache_photo( $id );

	$is_old = wppa_cdn( 'admin' ) && wppa_opt( 'max_cloud_life' ) && ( time() > ( $thumb['timestamp'] + wppa_opt( 'max_cloud_life' ) ) );

	return $is_old;
}

// Test if we are in a widget
// Returns wppa widget type if in a wppa widget
// Else: return true if in a widget, false if not in a widget
function wppa_in_widget() {

	if ( wppa( 'in_widget' ) ) {
		return wppa( 'in_widget' );
	}
	$stack = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
	if ( is_array( $stack ) ) foreach( $stack as $item ) {
		if ( isset( $item['class'] ) && $item['class'] == 'WP_Widget' ) {
			return true;
		}
	}
	return false;
}

function wppa_bump_mocc( $id = '' ) {
global $wppa;
static $occs_used;
global $wppa_forced_mocc;

$oldmocc = $wppa['mocc'];

	// Init static
	if ( ! is_array( $occs_used ) ) {
		$occs_used = array();
	}

	// Force mocc?
	if ( $id ) {

		// for widgets
		if ( $wppa['in_widget'] ) {
			$t = explode( '-', $id );
			$i = $t[count($t) - 1];
			$wppa['mocc'] = 100 + intval($i);
		}
		elseif ( wppa_is_int( $id ) ) {
			$wppa['mocc'] = $id;
		}
	}

	// Just inc
	else {
		$wppa['mocc'] += 1;
	}

	// Avoid dups - especially for widgets when one uses elementor
	if ( $wppa['in_widget'] ) {
		while ( in_array( $wppa['mocc'], $occs_used ) ) {
			$wppa['mocc'] += 1;
		}
	}

	if ( $wppa_forced_mocc ) {
		$wppa['mocc'] = $wppa_forced_mocc;
	}
	$occs_used[] = $wppa['mocc'];

$newmocc = $wppa['mocc'];
// wppa_log( 'misc', "Mocc bumped from $oldmocc to $newmocc" );
}

// This is a nice simple function
function wppa_out( $txt ) {
global $wppa;

	$wppa['out'] .= $txt;
}

function wppa_exit() {
	wppa_session_end();
	exit;
}

function wppa_sanitize_custom_field( $txt ) {

	if ( ! current_user_can( 'unfiltered_html' ) ) {
		$result = strip_tags( $txt );
	}
	else {
		$result = balanceTags( $txt );
	}
	return $result;
}

// See if a photo is in our admins choice zip
function  wppa_is_photo_in_zip( $id ) {

	// We do not zip pdfs
	if ( wppa_is_pdf( $id ) ) return false;

	// Verify existance of zips dir
	$zipsdir = WPPA_UPLOAD_PATH.'/zips/';
	if ( ! wppa_is_dir( $zipsdir ) ) return false;

	// Compose the users zip filename
	$zipfile = $zipsdir.wppa_get_user().'.zip';

	// Check file existance
	if ( ! is_file( $zipfile ) ) {
		return false;
	}

	// Find the photo data
	$data = wppa_cache_photo( $id );
	$photo_file = wppa_fix_poster_ext( $data['filename'], $id );

	// Open zip
	$wppa_zip = new ZipArchive;
	$wppa_zip->open( $zipfile );
	if ( ! $wppa_zip ) {

		// Failed to open zip
		return false;
	}

	// Look photo up in zip
	for( $i = 0; $i < $wppa_zip->numFiles; $i++ ) {
		$stat = $wppa_zip->statIndex( $i );
		$file_name = $stat['name'];
		if ( $file_name == $photo_file ) {

			// Found
			$wppa_zip->close();
			return true;
		}
	}

	// Not found
	$wppa_zip->close();
	return false;
}

// Convert querystring to get and request vars
function wppa_convert_uri_to_get( $uri ) {

	// Make local copy of argument
	$temp = $uri;

	// See if a ? is in the string
	if ( strpos( $uri, '?' ) !== false ) {

		// Trim up to and including ?
		$temp = substr( $uri, strpos( $uri, '?' ) + 1 );
	}

	// explode uri
	$arr = explode( '&', $temp );

	// If args exist, process them
	if ( !empty( $arr ) ) {
		foreach( $arr as $item ) {
			$arg = explode( '=', $item );
			if ( ! isset( $arg[1] ) ) {
				$arg[1] = null;
			}
			else {
				$arg[1] = urldecode( $arg[1] );
			}
			$_GET[$arg[0]] = $arg[1];
			$_REQUEST[$arg[0]] = $arg[1];
		}
	}
}

// Set owner to login name if photo name is user display_name
// Return true if owner changed, return 0 if already set, return false if not a username
function wppa_set_owner_to_name( $id ) {
global $wpdb;
static $usercache;

	// Feature enabled?
	if ( wppa_switch( 'owner_to_name' ) ) {

		// Get photo data.
		$p = wppa_cache_photo( $id );

		// Find user of whose display name equals photoname
		if ( isset( $usercache[$p['name']] ) ) {
			$user = $usercache[$p['name']];
		}
		else {
			$user = $wpdb->get_var( $wpdb->prepare( "SELECT user_login FROM ".$wpdb->users." WHERE display_name = %s", $p['name'] ) );
			if ( $user ) {
				$usercache[$p['name']] = $user;
			}
			else {
				$usercache[$p['name']] = false;	// NULL is equal to ! isset() !!!
			}
		}
		if ( $user ) {

			if ( $p['owner'] != $user ) {
				wppa_update_photo( $id, ['owner' => $user] );
				return true;
			}
			else {
				return '0';
			}
		}
	}

	return false;
}

// Get my last vote for a certain photo
function wppa_get_my_last_vote( $id ) {
global $wpdb;

	if ( is_user_logged_in() ) {
		$result = $wpdb->get_var( $wpdb->prepare(  "SELECT value FROM $wpdb->wppa_rating
													WHERE photo = %d
													AND userid = %d
													ORDER BY id DESC
													LIMIT 1",
													$id,
													wppa_get_user_id()
												) );
	}
	else {
		$result = $wpdb->get_var( $wpdb->prepare(  "SELECT value FROM $wpdb->wppa_rating
													WHERE photo = %d
													AND ip = %s
													ORDER BY id DESC
													LIMIT 1",
													$id,
													wppa_get_user_ip()
												) );
	}
	return $result;
}

// Get an svg image html
// @1: string: Name of the .svg file without extension
// @2: string: CSS height or empty, no ; required
// @3: bool: True if for lightbox. Use lightbox colors
// @4: bool: if true: add border
// @5: string: border radius in %: none
// @6: string: border radius in %: light
// @7: string: border radius in %: medium
// @8: string: border radius in %: heavy
function wppa_get_svghtml( $name, $height = false, $lightbox = false, $border = false, $none = '0', $light = '10', $medium = '20', $heavy = '50' ) {

	// Find the colors
	if ( $lightbox ) {
		$fillcolor 	= wppa_opt( 'ovl_svg_color' );
		$bgcolor 	= wppa_opt( 'ovl_svg_bg_color' );
	}
	else {
		$fillcolor 	= wppa_opt( 'svg_color' );
		$bgcolor 	= wppa_opt( 'svg_bg_color' );
	}
	if ( $fillcolor == '' ) $fillcolor = 'transparent';
	if ( $bgcolor == '' ) $bgcolor = 'transparent';

	// Find the border radius
	switch( wppa_opt( 'icon_corner_style' ) ) {
		case 'light':
			$bradius = $light;
			break;
		case 'medium':
			$bradius = $medium;
			break;
		case 'heavy':
			$bradius = $heavy;
			break;
		default:
			$bradius = $none;
			break;
	}

	// Open svg tag
	$result = '
	<svg
		version="1.1"
		x="0px"
		y="0px"
		viewBox="0 0 30 30"
		style="' .
			( $height ? 'height:' . $height . ';' : '' ) . '
			fill:' . $fillcolor . ';
			background-color:' . $bgcolor . ';
			text-decoration:none !important;
			vertical-align:middle' .
			( $bradius ? ';border-radius:' . $bradius . '%' : '' ) .
			( $border ? ';border:2px solid ' . $bgcolor . ';box-sizing:content-box' : '' ) . '"
		xml:space="preserve"
		>';

		// Open g tag
		$result .= '
		<g>';

		switch ( $name ) {

			case 'Next-Button':
				$result .= 	'<path' .
								' d="M30,0H0V30H30V0z M20,20.5' .
									'c0,0.3-0.2,0.5-0.5,0.5S19,20.8,19,20.5v-4.2l-8.3,4.6c-0.1,0-0.2,0.1-0.2,0.1c-0.1,0-0.2,0-0.3-0.1c-0.2-0.1-0.2-0.3-0.2-0.4v-11' .
									'c0-0.2,0.1-0.4,0.3-0.4c0.2-0.1,0.4-0.1,0.5,0l8.2,5.5V9.5C19,9.2,19.2,9,19.5,9S20,9.2,20,9.5V20.5z"' .
							' />';
				break;
			case 'Next-Button-Big':
				$result .= 	'<line x1="8" y1="2" x2="21.75" y2="15.75" stroke="'.$fillcolor.'" stroke-width="2.14" />
							<line x1="21.75" y1="14.25" x2="8" y2="28" stroke="'.$fillcolor.'" stroke-width="2.14" />';
							// '<path' .
							//	' d="M8,29.5c-0.1,0-0.3,0-0.4-0.1c-0.2-0.2-0.2-0.5,0-0.7L21.3,15L7.6,1.4c-0.2-0.2-0.2-0.5,0-0.7s0.5-0.2,0.7,0l14,14' .
							//	'c0.2,0.2,0.2,0.5,0,0.7l-14,14C8.3,29.5,8.1,29.5,8,29.5z"/>';
				break;
			case 'Prev-Button':
				$result .= 	'<path' .
								' d="M30,0H0V30H30V0z M20,20.5c0,0.2-0.1,0.4-0.3,0.4c-0.1,0-0.2,0.1-0.2,0.1c-0.1,0-0.2,0-0.3-0.1L11,15.4v5.1c0,0.3-0.2,0.5-0.5,0.5S10,20.8,10,20.5v-11' .
								'C10,9.2,10.2,9,10.5,9S11,9.2,11,9.5v4.2l8.3-4.6c0.2-0.1,0.3-0.1,0.5,0S20,9.3,20,9.5V20.5z"' .
							' />';
				break;
			case 'Prev-Button-Big':
				$result .= 	'<line x1="22" y1="2" x2="8.25" y2="15.75" stroke="'.$fillcolor.'" stroke-width="2.14" />
							<line x1="8.25" y1="14.25" x2="22" y2="28" stroke="'.$fillcolor.'" stroke-width="2.14" />';

				/*'<path' .
								' d="M22,29.5c-0.1,0-0.3,0-0.4-0.1l-14-14c-0.2-0.2-0.2-0.5,0-0.7l14-14c0.2-0.2,0.5-0.2,0.7,0s0.2,0.5,0,0.7L8.7,15l13.6,13.6' .
								' c0.2,0.2,0.2,0.5,0,0.7C22.3,29.5,22.1,29.5,22,29.5z"/>';
								*/
				break;
			case 'Backward-Button':
				$result .= 	'<path' .
								' d="M30,0H0V30H30V0z M23,20.5' .
									'c0,0.2-0.1,0.3-0.2,0.4c-0.2,0.1-0.3,0.1-0.5,0L16,17.4v3.1c0,0.2-0.1,0.4-0.3,0.4c-0.1,0-0.1,0.1-0.2,0.1c-0.1,0-0.2,0-0.3-0.1' .
									'l-8-6C7.1,14.8,7,14.6,7,14.5c0-0.2,0.1-0.3,0.2-0.4l8-5c0.2-0.1,0.3-0.1,0.5,0C15.9,9.2,16,9.3,16,9.5v3.1l6.3-3.6' .
									'c0.2-0.1,0.3-0.1,0.5,0C22.9,9.2,23,9.3,23,9.5V20.5z"' .
							' />';
				break;
			case 'Forward-Button':
				$result .= 	'<path' .
								' d="M30,0H0V30H30V0z' .
									'M22.8,15.9l-8,5c-0.2,0.1-0.3,0.1-0.5,0c-0.2-0.1-0.3-0.3-0.3-0.4v-3.1l-6.3,3.6C7.7,21,7.6,21,7.5,21c-0.1,0-0.2,0-0.3-0.1' .
									'C7.1,20.8,7,20.7,7,20.5v-11c0-0.2,0.1-0.3,0.2-0.4C7.4,9,7.6,9,7.7,9.1l6.3,3.6V9.5c0-0.2,0.1-0.4,0.3-0.4c0.2-0.1,0.4-0.1,0.5,0' .
									'l8,6c0.1,0.1,0.2,0.3,0.2,0.4C23,15.7,22.9,15.8,22.8,15.9z"' .
							' />';
				break;
			case 'Pause-Button':
				$result .= 	'<path' .
								' d="M30,0H0V30H30V0z M14,20.5' .
									'c0,0.3-0.2,0.5-0.5,0.5h-4C9.2,21,9,20.8,9,20.5v-11C9,9.2,9.2,9,9.5,9h4C13.8,9,14,9.2,14,9.5V20.5z M21,20.5' .
									'c0,0.3-0.2,0.5-0.5,0.5h-4c-0.3,0-0.5-0.2-0.5-0.5v-11C16,9.2,16.2,9,16.5,9h4C20.8,9,21,9.2,21,9.5V20.5z"' .
							' />';
				break;
			case 'Play-Button':
				$result .= 	'<path' .
								' d="M30,0H0V30H30V0z' .
									'M19.8,14.9l-8,5C11.7,20,11.6,20,11.5,20c-0.1,0-0.2,0-0.2-0.1c-0.2-0.1-0.3-0.3-0.3-0.4v-9c0-0.2,0.1-0.3,0.2-0.4' .
									'c0.1-0.1,0.3-0.1,0.5,0l8,4c0.2,0.1,0.3,0.2,0.3,0.4C20,14.7,19.9,14.8,19.8,14.9z"' .
							' />';
				break;
			case 'Stop-Button':
				$result .= 	'<path' .
								' d="M30,0H0V30H30V0z M21,20.5' .
									'c0,0.3-0.2,0.5-0.5,0.5h-11C9.2,21,9,20.8,9,20.5v-11C9,9.2,9.2,9,9.5,9h11C20.8,9,21,9.2,21,9.5V20.5z"' .
							'/>';
				break;
			case 'Eagle-1':
				$result .= 	'<path' .
								' d="M29.9,19.2c-0.1-0.1-0.2-0.2-0.4-0.2c-3.7,0-6.2-0.6-7.6-1.1c-0.1,0-0.1,0.1-0.2,0.1c-1.2,1.2-4,2.6-4.6,2.9' .
									'c-0.1,0-0.1,0.1-0.2,0.1c-0.2,0-0.4-0.1-0.4-0.3c-0.1-0.2,0-0.5,0.2-0.7c0.3-0.2,2.9-1.4,4.1-2.5l0,0c0.1-0.1,0.1-0.1,0.2-0.2' .
									'c0.7-0.7,2.5-0.5,3.3-0.3c0,0,0.1,0,0.1,0c0.2,0.1,0.4,0,0.5-0.2c0,0,0,0,0,0c0,0,0,0,0,0c0.1-0.2,0.1-0.3,0.2-0.5c0,0,0-0.1,0-0.1' .
									'c0-0.1,0.1-0.3,0.1-0.4c0,0,0-0.1,0-0.1c0-0.1,0-0.3,0-0.4c0,0,0-0.1,0-0.1c0-0.1-0.1-0.3-0.2-0.4c0,0,0,0,0-0.1' .
									'c-0.1-0.1-0.1-0.2-0.2-0.2c0,0-0.1-0.1-0.1-0.1c-0.1,0-0.1-0.1-0.2-0.1c0,0-0.1-0.1-0.1-0.1c-0.1,0-0.1-0.1-0.2-0.1' .
									'c-0.1,0-0.1-0.1-0.2-0.1c-0.1,0-0.1-0.1-0.2-0.1c0,0-0.1,0-0.1-0.1c-0.1,0-0.2-0.1-0.2-0.1c0,0-0.1,0-0.1,0c-0.4-0.1-0.7-0.2-1-0.2' .
									'c0-0.1-0.1-0.2-0.1-0.3c-0.1-0.2-0.2-0.3-0.3-0.5c-0.2-0.2-0.4-0.3-0.6-0.4C21,12.1,20.6,12,20,12c-0.3,0-0.6,0-0.8,0.1' .
									'c-0.1,0-0.1,0-0.2,0c-0.2,0-0.5,0.1-0.7,0.1c0,0-0.1,0-0.1,0c-0.2,0.1-0.5,0.1-0.7,0.2c0,0,0,0,0,0c-1.2,0.5-2.2,1.2-3,1.8' .
									'c-0.5,0.3-0.9,0.6-1.2,0.8c-0.2,0.1-0.5,0-0.7-0.2c-0.1-0.3,0-0.5,0.2-0.7c0.2-0.1,0.6-0.4,0.9-0.6c-1.6-0.6-4-2-4-5.4' .
									'c0-4.1,1.9-5.6,3.2-6.6c0.3-0.2,0.6-0.4,0.8-0.7C14,0.7,14,0.5,14,0.3S13.7,0,13.5,0C10.1,0,8.1,2,7,3.5v-1C7,2.3,6.9,2.1,6.7,2' .
									'C6.5,2,6.3,2,6.1,2.1C4.5,3.8,3.9,5.4,3.7,6.8L3.4,6.3C3.4,6.1,3.2,6,3.1,6S2.8,6,2.6,6.1C1.8,7,1.3,8,1.3,9c0,0.5,0.1,1,0.3,1.4' .
									'l-1-0.4c-0.2-0.1-0.3,0-0.5,0.1C0.1,10.2,0,10.3,0,10.5c0,2.7,0.5,4.4,1.4,5.2c0.1,0.1,0.2,0.1,0.3,0.2C1.4,16.4,1,17.4,1,18.5' .
									'c0,1.5,2.6,2.5,4.5,3c-1,0.4-2,1-2,2c0,0.5-1.6,1.2-3.1,1.5c-0.2,0-0.3,0.2-0.4,0.4c-0.1,0.2,0,0.4,0.2,0.5C0.4,26,4.9,30,8.5,30' .
									'C8.8,30,9,29.8,9,29.5c0-3.1,3.5-5,4.5-5.4c0.6,0.3,2,0.9,5,0.9c1.9,0,2.9-0.3,3.2-1l1.6,0.9c0.1,0,0.2,0.1,0.3,0.1' .
									'c3.4,0,4.3-1.1,4.4-1.2c0.1-0.2,0.1-0.5-0.1-0.6l-0.8-0.8c2.1-0.6,2.9-2.6,2.9-2.7C30,19.5,30,19.3,29.9,19.2z M20.5,14' .
									'c0.3,0,0.5,0.2,0.5,0.5S20.8,15,20.5,15S20,14.8,20,14.5S20.2,14,20.5,14z"' .
							' />';
				break;
			case 'Snail':
				$result .= 	'<path' .
								' d="M28.5,16.3L30,9.1c0.1-0.3-0.1-0.5-0.4-0.6c-0.3-0.1-0.5,0.1-0.6,0.4L27.6,16c0,0-0.1,0-0.1,0L27,10c0-0.3-0.3-0.5-0.5-0.5' .
									'C26.2,9.5,26,9.8,26,10l0.5,6.1c-0.4,0.1-0.7,0.2-1.1,0.3l0,0c-1.4,2-4.8,4.1-6.9,4.1c-1.9,0-3.8-0.1-5.2-1.1' .
									'c-0.1-0.1-0.2-0.2-0.2-0.4c0-0.1,0-0.3,0.2-0.4l1.2-1.1c1.5-1.9,1.6-4.7,1.6-5.5c0-1.8-1.2-5.5-5-5.5c-3.7,0-5,2.7-5,5' .
									'c0,2.7,2.1,3,3,3c1.5,0,3-1.3,3-2.5c0-1.1-0.4-1.5-1.5-1.5C9.4,10.5,9,10.9,9,12c0,0.3-0.2,0.5-0.5,0.5S8,12.3,8,12' .
									'c0-1.6,0.9-2.5,2.5-2.5c1.7,0,2.5,0.8,2.5,2.5c0,1.8-1.9,3.5-4,3.5c-1.9,0-4-1.1-4-4c0-3,1.9-6,6-6c4.1,0,6,3.8,6,6.5' .
									'c0,1.1-0.2,4-1.8,6.1l-0.8,0.7c1.2,0.5,2.6,0.6,4.1,0.6c1.8,0,5.2-2.3,6.2-3.9l0,0c0.3-0.7,0.3-1.6,0.3-2.7c0-0.3,0-0.5,0-0.8' .
									'c0-2-3-9.5-12-9.5C4.8,2.5,1,7.9,1,13c0,3,1.3,5.3,3.8,6.5c-0.5,0.4-1.4,1.1-2.6,1.6C0.1,21.8,0,24.9,0,25c0,0.2,0.1,0.4,0.3,0.4' .
									'c0.2,0.1,0.4,0.1,0.5,0c0,0,1.3-0.9,4.1-0.9c1.6,0,2.6,0.6,3.6,1c0.7,0.4,1.3,0.7,2.1,0.7c0.5,0,0.6,0.1,0.8,0.4' .
									'c0.3,0.4,0.6,0.8,1.7,0.8c1,0,1.4-0.3,1.8-0.6c0.3-0.2,0.6-0.4,1.2-0.4c0.6,0,0.9,0.2,1.3,0.4c0.4,0.3,1,0.6,1.9,0.6' .
									'c1.4,0,1.6-1,1.8-1.6c0.1-0.4,0.2-0.8,0.4-1c0.2-0.2,0.4-0.1,1,0.1c0.6,0.2,1.4,0.6,2.1-0.1c0.6-0.6,0.7-1.1,0.8-1.5' .
									'c0.1-0.4,0.1-0.6,0.5-1c0.6-0.5,2-0.1,2.4,0.1c0.2,0.1,0.5,0,0.6-0.2c0-0.1,1.1-1.7,1.1-3.3C30,17.8,29.4,16.8,28.5,16.3z"' .
							' />';
				break;
			case 'Exit':
				$result .= 	'<path d="M30 24.398l-8.406-8.398 8.406-8.398-5.602-5.602-8.398 8.402-8.402-8.402-5.598 5.602 8.398 8.398-8.398 8.398 5.598 5.602 8.402-8.402 8.398 8.402z"></path>';
				break;
			case 'Exit-2':
				$result .= '<path' .
								' d="M30,0H0V30H30V0z ' .
								'M9 4 L15 10 L21 4 L26 9 L20 15 L26 21 L21 26 L15 20 L9 26 L4 21 L10 15 L4 9Z' .
								'"' .
							' />';
				break;
			case 'Exit-Big':
				$result .= '<line x1="4" y1="4" x2="26" y2="26" stroke="'.$fillcolor.'" stroke-width="2.14" />
							<line x1="4" y1="26" x2="26" y2="4" stroke="'.$fillcolor.'" stroke-width="2.14" />';
				break;
			case 'Full-Screen':
				$result .= 	'<path d="M27.414 24.586l-4.586-4.586-2.828 2.828 4.586 4.586-4.586 4.586h12v-12zM12 0h-12v12l4.586-4.586 4.543 4.539 2.828-2.828-4.543-4.539zM12 22.828l-2.828-2.828-4.586 4.586-4.586-4.586v12h12l-4.586-4.586zM32 0h-12l4.586 4.586-4.543 4.539 2.828 2.828 4.543-4.539 4.586 4.586z"></path>';
				break;
			case 'Full-Screen-2':
				$result .= '<path' .
								' d="M30,0H0V30H30V0z ' .
								'M4 4 L12 4 L10 6 L14 10 L10 14 L6 10 L4 12Z' .
								'M18 4 L26 4 L26 12 L24 10 L20 14 L16 10 L20 6Z' .
								'M26 26 L18 26 L20 24 L16 20 L20 16 L24 20 L26 18Z' .
								'M4 26 L4 18 L6 20 L10 16 L14 20 L10 24 L12 26Z' .
								'"' .
							' />';

				break;
			case 'Exit-Full-Screen':
				$result .= 	'<path d="M24.586 27.414l4.586 4.586 2.828-2.828-4.586-4.586 4.586-4.586h-12v12zM0 12h12v-12l-4.586 4.586-4.539-4.543-2.828 2.828 4.539 4.543zM0 29.172l2.828 2.828 4.586-4.586 4.586 4.586v-12h-12l4.586 4.586zM20 12h12l-4.586-4.586 4.547-4.543-2.828-2.828-4.547 4.543-4.586-4.586z"></path>';
				break;
			case 'Exit-Full-Screen-2':
				$result .= '<path' .
								' d="M30,0H0V30H30V0z ' .
								'M17 17 L25 17 L23 19 L27 23 L23 27 L19 23 L17 25Z' .
								'M5 17 L13 17 L13 25 L11 23 L7 27 L3 23 L7 19Z' .
								'M13 13 L5 13 L7 11 L3 7 L7 3 L11 7 L13 5Z' .
								'M17 13 L17 5 L19 7 L23 3 L27 7 L23 11 L25 13Z' .
								'"' .
							' />';
				break;
			case 'Content-View':
				$result .= 	'<path' .
								' d="M21.5,25.5h4c0.276,0,0.5-0.224,0.5-0.5s-0.224-0.5-0.5-0.5h-4c-0.276,0-0.5,0.224-0.5,0.5S21.224,25.5,21.5,25.5z' .
									'M21.5,18.5h4c0.276,0,0.5-0.224,0.5-0.5s-0.224-0.5-0.5-0.5h-4c-0.276,0-0.5,0.224-0.5,0.5S21.224,18.5,21.5,18.5z M21.5,23.5h4' .
									'c0.276,0,0.5-0.224,0.5-0.5s-0.224-0.5-0.5-0.5h-4c-0.276,0-0.5,0.224-0.5,0.5S21.224,23.5,21.5,23.5z M21.5,16.5h4' .
									'c0.276,0,0.5-0.224,0.5-0.5s-0.224-0.5-0.5-0.5h-4c-0.276,0-0.5,0.224-0.5,0.5S21.224,16.5,21.5,16.5z M21.5,11.5h4' .
									'c0.276,0,0.5-0.224,0.5-0.5s-0.224-0.5-0.5-0.5h-4c-0.276,0-0.5,0.224-0.5,0.5S21.224,11.5,21.5,11.5z M26.864,0.5H3.136' .
									'C1.407,0.5,0,1.866,0,3.545v22.91C0,28.134,1.407,29.5,3.136,29.5h23.728c1.729,0,3.136-1.366,3.136-3.045V3.545' .
									'C30,1.866,28.593,0.5,26.864,0.5z M9.5,2.5C9.776,2.5,10,2.724,10,3S9.776,3.5,9.5,3.5S9,3.276,9,3S9.224,2.5,9.5,2.5z M6.5,2.5' .
									'C6.776,2.5,7,2.724,7,3S6.776,3.5,6.5,3.5S6,3.276,6,3S6.224,2.5,6.5,2.5z M3.5,2.5C3.776,2.5,4,2.724,4,3S3.776,3.5,3.5,3.5' .
									'S3,3.276,3,3S3.224,2.5,3.5,2.5z M29,26.455c0,1.128-0.958,2.045-2.136,2.045H3.136C1.958,28.5,1,27.583,1,26.455V5.5h28V26.455z' .
									'M21.5,9.5h4C25.776,9.5,26,9.276,26,9s-0.224-0.5-0.5-0.5h-4C21.224,8.5,21,8.724,21,9S21.224,9.5,21.5,9.5z M4.5,25.5h2' .
									'C6.776,25.5,7,25.276,7,25v-2c0-0.276-0.224-0.5-0.5-0.5h-2C4.224,22.5,4,22.724,4,23v2C4,25.276,4.224,25.5,4.5,25.5z M17.5,11.5' .
									'h2c0.276,0,0.5-0.224,0.5-0.5V9c0-0.276-0.224-0.5-0.5-0.5h-2C17.224,8.5,17,8.724,17,9v2C17,11.276,17.224,11.5,17.5,11.5z' .
									'M8.5,25.5h4c0.276,0,0.5-0.224,0.5-0.5s-0.224-0.5-0.5-0.5h-4C8.224,24.5,8,24.724,8,25S8.224,25.5,8.5,25.5z M8.5,18.5h4' .
									'c0.276,0,0.5-0.224,0.5-0.5s-0.224-0.5-0.5-0.5h-4C8.224,17.5,8,17.724,8,18S8.224,18.5,8.5,18.5z M8.5,23.5h4' .
									'c0.276,0,0.5-0.224,0.5-0.5s-0.224-0.5-0.5-0.5h-4C8.224,22.5,8,22.724,8,23S8.224,23.5,8.5,23.5z M4.5,11.5h2' .
									'C6.776,11.5,7,11.276,7,11V9c0-0.276-0.224-0.5-0.5-0.5h-2C4.224,8.5,4,8.724,4,9v2C4,11.276,4.224,11.5,4.5,11.5z M4.5,18.5h2' .
									'C6.776,18.5,7,18.276,7,18v-2c0-0.276-0.224-0.5-0.5-0.5h-2C4.224,15.5,4,15.724,4,16v2C4,18.276,4.224,18.5,4.5,18.5z M17.5,25.5' .
									'h2c0.276,0,0.5-0.224,0.5-0.5v-2c0-0.276-0.224-0.5-0.5-0.5h-2c-0.276,0-0.5,0.224-0.5,0.5v2C17,25.276,17.224,25.5,17.5,25.5z' .
									'M17.5,18.5h2c0.276,0,0.5-0.224,0.5-0.5v-2c0-0.276-0.224-0.5-0.5-0.5h-2c-0.276,0-0.5,0.224-0.5,0.5v2' .
									'C17,18.276,17.224,18.5,17.5,18.5z M8.5,16.5h4c0.276,0,0.5-0.224,0.5-0.5s-0.224-0.5-0.5-0.5h-4C8.224,15.5,8,15.724,8,16' .
									'S8.224,16.5,8.5,16.5z M8.5,9.5h4C12.776,9.5,13,9.276,13,9s-0.224-0.5-0.5-0.5h-4C8.224,8.5,8,8.724,8,9S8.224,9.5,8.5,9.5z' .
									'M8.5,11.5h4c0.276,0,0.5-0.224,0.5-0.5s-0.224-0.5-0.5-0.5h-4C8.224,10.5,8,10.724,8,11S8.224,11.5,8.5,11.5z"' .
							' />';
				break;
			case 'Left-4':
				$result .= '<path' .
								' d="M30,0H0V30H30V0z' .
									'M24.5,19c0,0.3-0.2,0.5-0.5,0.5h-9.5V24' .
									'c0,0.2-0.1,0.4-0.3,0.5c-0.1,0-0.1,0-0.2,0c-0.1,0-0.3-0.1-0.4-0.1l-9-9c-0.2-0.2-0.2-0.5,0-0.7l9-9c0.1-0.1,0.4-0.2,0.5-0.1' .
									'c0.2,0.1,0.3,0.3,0.3,0.5v4.5H24c0.3,0,0.5,0.2,0.5,0.5V19z"' .
							'/>';
				break;
			case 'Right-4':
				$result .= '<path' .
								' d="M30,0H0V30H30V0z' .
									'M5.5,11c0-0.3,0.2-0.5,0.5-0.5h9.5V6' .
									'c0-0.2,0.1-0.4,0.3-0.5c0.1,0,0.1,0,0.2,0c0.1,0,0.3,0.1,0.4,0.1l9,9c0.2,0.2,0.2,0.5,0,0.7l-9,9c-0.1,0.1-0.4,0.2-0.5,0.1' .
									'c-0.2-0.1-0.3-0.3-0.3-0.5v-4.5H6c-0.3,0-0.5-0.2-0.5-0.5V11z"' .
							'/>';
				break;
			case 'Up-4':
				$result .= '<path' .
								' d="M30,0H0V30H30V0z' .
									'M11,24.5c-0.3,0-0.5-0.2-0.5-0.5v-9.5H6' .
									'c-0.2,0-0.4-0.1-0.5-0.3c0-0.1,0-0.1,0-0.2c0-0.1,0.1-0.3,0.1-0.4l9-9c0.2-0.2,0.5-0.2,0.7,0l9,9c0.1,0.1,0.2,0.4,0.1,0.5' .
									'c-0.1,0.2-0.3,0.3-0.5,0.3h-4.5V24c0,0.3-0.2,0.5-0.5,0.5H11z"' .
							'/>';
				break;
			case 'Down-4':
				$result .= '<path' .
								' d="M30,0H0V30H30V0z' .
									'M19,5.5c0.3,0,0.5,0.2,0.5,0.5v9.5H24' .
									'c0.2,0,0.4,0.1,0.5,0.3c0,0.1,0,0.1,0,0.2c0,0.1-0.1,0.3-0.1,0.4l-9,9c-0.2,0.2-0.5,0.2-0.7,0l-9-9c-0.1-0.1-0.2-0.4-0.1-0.5' .
									'c0.1-0.2,0.3-0.3,0.5-0.3h4.5V6c0-0.3,0.2-0.5,0.5-0.5H19z"' .
							'/>';
				break;
			case 'ZoomIn':
				$result .= '<path' .
								' d="M30,0H0V30H30V0z' .
								'M5.5,11h5.5v-5.5h8v5.5h5.5v8h-5.5v5.5h-8v-5.5h-5.5z' .
									'"' .
							'/>';
				break;
			case 'ZoomOut':
				$result .= '<path' .
								' d="M30,0H0V30H30V0z' .
								'M5.5,11h19v8h-19z' .
									'"' .
							'/>';
				break;
			case 'Redo':
				$result .= '<path d="M29.8,12.6l-14-9c-0.2-0.1-0.4-0.1-0.5,0C15.1,3.7,15,3.8,15,4v5.5C5,9.8,0.1,19.4,0,26l0,0c0,0,0,0,0,0l0,0c0,0,0,0,0,0.1
								v0v0l0,0c0,0.2,0.2,0.4,0.5,0.4c0,0,0,0,0,0C0.8,26.5,1,26.3,1,26c0.1-1.5,6.7-8.2,14-8.5V23c0,0.2,0.1,0.4,0.3,0.4
								c0.2,0.1,0.4,0.1,0.5,0l14-10c0.1-0.1,0.2-0.3,0.2-0.4S29.9,12.7,29.8,12.6z"/>';
				break;
			default:
				$result .= '<path d="M30,0H0V30H30V0z" />';
				break;
		}

		$result .= 		'</g>' .
					'</svg>';

		if ( is_ssl() ) {
			$result = str_replace( 'http://', 'https://', $result );
		}

		return $result;
}

function wppa_get_mime_type( $id ) {

	$ext = strtolower( wppa_get_photo_item( $id, 'ext' ) );
	if ( $ext == 'xxx' ) {
		$ext = wppa_geter_ext( $id );
	}

	switch ( $ext ) {
		case 'jpg':
		case 'jpeg':
			$result = 'image/jpeg';
			break;
		case 'png':
			$result = 'image/png';
			break;
		case 'gif':
			$result = 'image/gif';
			break;
		default:
			$result = '';
	}

	return $result;
}

// Test if a given url is to a photo file, possibly adjust url extension
function wppa_is_url_a_photo( &$url, $save = true ) {
global $wppa_supported_photo_extensions;
global $wppa_session;

	// Init
	$result 	= true;
	$ext 		= wppa_get_ext( $url );
	$urlnoext 	= wppa_strip_ext( $url );

	// Try all supported extensions
	foreach( $wppa_supported_photo_extensions as $ext ) {

		$url = $urlnoext . '.' . $ext;

		// Use wp HTTP API to retrieve the photo
		$response = wp_remote_get( $url );
		$result   = wp_remote_retrieve_body( $response );
		$httpcode = wp_remote_retrieve_response_code( $response );

		// Done, save image optionally
		if ( $httpcode == 200 ) {

			if ( $save ) {

				$path = WPPA_DEPOT_PATH . '/' . basename( wppa_compress_tree_path( $url ) );
				wppa_put_contents( $path, $result );
			}
			return $path;
		}
	}

	// No
	return false;
}

function wppa_get_like_title_a( $id ) {
global $wpdb;

	$me 	= wppa_get_user();
	$likes 	= wppa_get_photo_item( $id, 'rating_count');
	$mylike = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_rating WHERE photo = %d AND user = %s", $id, $me ) );

	if ( $mylike ) {
		if ( $likes > 1 ) {
			$text = sprintf( _n( 'You and %d other person like this', 'You and %d other people like this', $likes - 1 ), $likes - 1 );
		}
		else {
			$text = __( 'You are the first one who likes this', 'wp-photo-album-plus' );
		}
		$text .= "\n"
 . __( 'Click again if you do no longer like this', 'wp-photo-album-plus' );
	}
	else {
		if ( $likes ) {
			$text = sprintf( _n( '%d person likes this', '%d people like this', $likes, 'wp-photo-album-plus' ), $likes );
		}
		else {
			$text = __( 'Be the first one to like this', 'wp-photo-album-plus' );
		}
	}
	$result['title']  	= $text;
	$result['mine']  	= $mylike;
	$result['total'] 	= $likes;
	$result['display'] 	= sprintf( _n( '%d like', '%d likes', $likes ), $likes );

	return $result;
}

// Returns available memory in bytes
function wppa_memry_limit() {

	// get memory limit
	$memory_limit = 0;
	$memory_limini = wppa_convert_bytes( ini_get( 'memory_limit' ) );
	$memory_limcfg = wppa_convert_bytes( get_cfg_var( 'memory_limit' ) );

	// find the smallest not being zero
	if ( $memory_limini && $memory_limcfg ) $memory_limit = min( $memory_limini, $memory_limcfg );
	elseif ( $memory_limini ) $memory_limit = $memory_limini;
	else $memory_limit = $memory_limcfg;

	// No data, return 64MB
	if ( ! $memory_limit ) {
		return 64 * 1024 * 1024;
	}

	return $memory_limit;
}

// Create qr code cache and return its url
function wppa_create_qrcode_cache( $url, $size = '80' ) {

	$qrsrc = 	'http' . ( is_ssl() ? 's' : '' ) . '://api.qrserver.com/v1/create-qr-code/' .
				'?format=svg' .
				'&size=' . strval( intval( $size ) ) . 'x' . strval( intval( $size ) ) .
				'&color=' . trim( wppa_opt( 'qr_color' ), '#' ) .
				'&bgcolor=' . trim( wppa_opt( 'qr_bgcolor' ), '#' ) .
				'&data=' . urlencode( wppa_convert_to_pretty( $url ) );

	// Anything to do here?
	if ( ! wppa_switch( 'qr_cache' ) ) {
		return $qrsrc;
	}

	// Make sure we have .../uploads/wppa/qr
	if ( ! wppa_is_dir( WPPA_UPLOAD_PATH . '/qr' ) ) {
		wppa_mkdir( WPPA_UPLOAD_PATH . '/qr' );
	}

	// In cache already?
	$key = md5( $qrsrc );
	$qr_image_path = WPPA_UPLOAD_PATH . '/qr/' . $key . '.svg';
	$qr_image_url  = WPPA_UPLOAD_URL . '/qr/' . $key . '.svg';

	if ( is_file( $qr_image_path ) ) {

		// Bump cache found counter
		wppa_update_option( 'wppa_qr_cache_hits', wppa_get_option( 'wppa_qr_cache_hits', 0 ) + 1 );
		return $qr_image_url;
	}

	// Bump cache miss counter
	wppa_update_option( 'wppa_qr_cache_miss', wppa_get_option( 'wppa_qr_cache_miss', 0 ) + 1 );

	// Cleanup qr cache
	$maxfiles = wppa_opt( 'qr_max' ); 	// 0 is unlimited
	if ( $maxfiles ) {
		if ( wppa_is_dir( WPPA_UPLOAD_PATH . '/qr' ) ) {
			$qrs = wppa_glob( WPPA_UPLOAD_PATH . '/qr/*.svg' );
			if ( ! empty( $qrs ) ) {
				$count = count( $qrs );
				if ( $count > $maxfiles ) {

					// delete all files older than 60 seconds
					foreach( $qrs as $qr ) {
						if ( wppa_filetime( $qr ) < ( time() - 60 ) ) {
							wppa_unlink( $qr );
						}
					}
				}
			}
		}
	}

	// Catch the qr image
	$response = wp_remote_get( $qrsrc );
	$contents = wp_remote_retrieve_body( $response );
	$httpcode = wp_remote_retrieve_response_code( $response );

	// On success, save the image and return the url to the image
	if ( $httpcode == 200 ) {

		wppa_put_contents( $qr_image_path, $contents );
		return $qr_image_url;
	}

	// Failed, return qr source url
	return $qrsrc;
}


function wppa_use_svg( $is_admin = false ) {
	if ( wppa_is_ie() ) {
		return false;
	}
	if ( ! $is_admin && wppa_opt( 'icon_corner_style' ) == 'gif' ) {
		return false;
	}
	return true;
}


function wppa_get_spinner_svg_html( $xargs = array() ) {

	$defaults = array(
					'id' 		=> 'wppa-spinner',
					'class' 	=> 'wppa-spinner',
					'size' 		=> '120',
					'position' 	=> 'fixed',
					'lightbox' 	=> false,
					'display' 	=> 'none',
					'left' 		=> '50%',
					'top' 		=> '50%',
					'margin' 	=> false,
					'z-index' 	=> '',
					'style' 	=> '',
					);

	$args 	= wp_parse_args( $xargs, $defaults );
	if ( $args['margin'] === false ) $args['margin'] = $args['size'] / 2;
	$width  = $args['size'];
	$height = $args['size'];
	$type 	= wppa_opt( 'spinner_shape' );
	$corner = wppa_opt( 'icon_corner_style' );
	$fgcol 	= $args['lightbox'] ? wppa_opt( 'ovl_svg_color' ) : wppa_opt( 'svg_color' );
	$bgcol 	= $args['lightbox'] ? wppa_opt( 'ovl_svg_bg_color' ) : wppa_opt( 'svg_bg_color' );
	$stcol 	= $fgcol;
	$zindex = $args['z-index'];
	$display = $args['display'];
	$style 	= $args['style'];

	// .svg requested and possible?
	if ( wppa_use_svg() ) {
		switch ( $type ) {

			case 'audio':
				$viewbox = '0 0 55 80';
				$width = round( $args['size'] * 55 / 80 );
				break;

			case 'ball-triangle':
				$viewbox = '0 0 57 57';
				break;

			case 'bars':
				$viewbox = '0 0 135 140';
				$width = round( $args['size'] * 135 / 140 );
				break;

			case 'circles':
				$viewbox = '0 0 135 135';
				$stcol = '';
				break;

			case 'grid':
				$viewbox = '0 0 105 105';
				$stcol = '';
				break;

			case 'hearts':
				$viewbox = '0 0 140 64';
				$height = round( $args['size'] * 64 / 140 );
				break;

			case 'puff':
				$viewbox = '0 0 44 44';
				break;

			case 'rings':
				$viewbox = '0 0 45 45';
				break;

			case 'spinning-circles':
				$viewbox = '0 0 58 58';
				break;

			case 'oval':
			case 'tail-spin':
				$viewbox = '0 0 38 38';
				break;

			case 'three-dots':
				$viewbox = '0 0 120 30';
				$height = round( $args['size'] / 4 );
				break;

			default:
				$viewbox = '0 0 100 100';
				$stcol = '';
				break;
		}

		switch ( $corner ) {

			case 'light':
				$bradius = round( $args['size'] / 10 );
				break;
			case 'medium':
				$bradius = round( $args['size'] / 5 );
				break;
			case 'heavy':
				$bradius = round( $args['size'] / 2 );
				break;
			default: // case 'gif': case 'none':
				$bradius = '0';
				break;
		}

		if ( ! $style ) {
			$style = '
				width:' . $width . 'px;
				height:' . $height . 'px;
				position:' . $args['position'] . ';
				top:' . $args['top'] . ';
				margin-top:-' . $args['margin'] . 'px;
				left:' . $args['left'] . ';
				margin-left:-' . $args['margin'] . 'px;
				opacity:1;
				display:' . $display . ';
				fill:' . $fgcol . ';
				background-color:' . $bgcol . ';
				border-radius:' . $bradius .'px;' .
				( $zindex ? 'z-index:' . $zindex . ';' : '' );
		}

		$result =
			'<svg' .
				' id="' . $args['id'] . '"' .
				' class="' . $args['class'] . ' uil-default"' .
				' width="' . $width . 'px"' .
				' height="' . $height . 'px"' .
				' viewBox="' . $viewbox . '"' .
				' preserveAspectRatio="xMidYMid"' .
				' stroke="' . $stcol . '"' .
				' style="' . $style . '">';

		switch ( $type ) {

			case 'audio':
				$result .=
					'<g transform="matrix(1 0 0 -1 0 80)">
        <rect width="10" height="20" rx="3">
            <animate attributeName="height"
                 begin="0s" dur="4.3s"
                 values="20;45;57;80;64;32;66;45;64;23;66;13;64;56;34;34;2;23;76;79;20" calcMode="linear"
                 repeatCount="indefinite" />
        </rect>
        <rect x="15" width="10" height="80" rx="3">
            <animate attributeName="height"
                 begin="0s" dur="2s"
                 values="80;55;33;5;75;23;73;33;12;14;60;80" calcMode="linear"
                 repeatCount="indefinite" />
        </rect>
        <rect x="30" width="10" height="50" rx="3">
            <animate attributeName="height"
                 begin="0s" dur="1.4s"
                 values="50;34;78;23;56;23;34;76;80;54;21;50" calcMode="linear"
                 repeatCount="indefinite" />
        </rect>
        <rect x="45" width="10" height="30" rx="3">
            <animate attributeName="height"
                 begin="0s" dur="2s"
                 values="30;45;13;80;56;72;45;76;34;23;67;30" calcMode="linear"
                 repeatCount="indefinite" />
        </rect>
    </g>';
				break;

			case 'ball-triangle':
				$result .=
					'<g fill="none" fill-rule="evenodd">
						<g transform="translate(1 1)" stroke-width="2">
							<circle cx="5" cy="50" r="5">
								<animate attributeName="cy"
									 begin="0s" dur="2.2s"
									 values="50;5;50;50"
									 calcMode="linear"
									 repeatCount="indefinite" />
								<animate attributeName="cx"
									 begin="0s" dur="2.2s"
									 values="5;27;49;5"
									 calcMode="linear"
									 repeatCount="indefinite" />
							</circle>
							<circle cx="27" cy="5" r="5">
								<animate attributeName="cy"
									 begin="0s" dur="2.2s"
									 from="5" to="5"
									 values="5;50;50;5"
									 calcMode="linear"
									 repeatCount="indefinite" />
								<animate attributeName="cx"
									 begin="0s" dur="2.2s"
									 from="27" to="27"
									 values="27;49;5;27"
									 calcMode="linear"
									 repeatCount="indefinite" />
							</circle>
							<circle cx="49" cy="50" r="5">
								<animate attributeName="cy"
									 begin="0s" dur="2.2s"
									 values="50;50;5;50"
									 calcMode="linear"
									 repeatCount="indefinite" />
								<animate attributeName="cx"
									 from="49" to="49"
									 begin="0s" dur="2.2s"
									 values="49;5;27;49"
									 calcMode="linear"
									 repeatCount="indefinite" />
							</circle>
						</g>
					</g>';
				break;

			case 'bars':
				$result .=
					'<rect y="10" width="15" height="120" rx="6">
						<animate attributeName="height"
							 begin="0.5s" dur="1s"
							 values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
							 repeatCount="indefinite" />
						<animate attributeName="y"
							 begin="0.5s" dur="1s"
							 values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear"
							 repeatCount="indefinite" />
					</rect>
					<rect x="30" y="10" width="15" height="120" rx="6">
						<animate attributeName="height"
							 begin="0.25s" dur="1s"
							 values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
							 repeatCount="indefinite" />
						<animate attributeName="y"
							 begin="0.25s" dur="1s"
							 values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear"
							 repeatCount="indefinite" />
					</rect>
					<rect x="60" width="15" height="140" rx="6">
						<animate attributeName="height"
							 begin="0s" dur="1s"
							 values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
							 repeatCount="indefinite" />
						<animate attributeName="y"
							 begin="0s" dur="1s"
							 values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear"
							 repeatCount="indefinite" />
					</rect>
					<rect x="90" y="10" width="15" height="120" rx="6">
						<animate attributeName="height"
							 begin="0.25s" dur="1s"
							 values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
							 repeatCount="indefinite" />
						<animate attributeName="y"
							 begin="0.25s" dur="1s"
							 values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear"
							 repeatCount="indefinite" />
					</rect>
					<rect x="120" y="10" width="15" height="120" rx="6">
						<animate attributeName="height"
							 begin="0.5s" dur="1s"
							 values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
							 repeatCount="indefinite" />
						<animate attributeName="y"
							 begin="0.5s" dur="1s"
							 values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear"
							 repeatCount="indefinite" />
					</rect>';
				break;

			case 'circles':
				$result .=
					'<path d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z">
						<animateTransform
							attributeName="transform"
							type="rotate"
							from="0 67 67"
							to="-360 67 67"
							dur="2.5s"
							repeatCount="indefinite"/>
					</path>
					<path d="M28.19 40.31c6.627 0 12-5.374 12-12 0-6.628-5.373-12-12-12-6.628 0-12 5.372-12 12 0 6.626 5.372 12 12 12zm30.72-19.825c4.686 4.687 12.284 4.687 16.97 0 4.686-4.686 4.686-12.284 0-16.97-4.686-4.687-12.284-4.687-16.97 0-4.687 4.686-4.687 12.284 0 16.97zm35.74 7.705c0 6.627 5.37 12 12 12 6.626 0 12-5.373 12-12 0-6.628-5.374-12-12-12-6.63 0-12 5.372-12 12zm19.822 30.72c-4.686 4.686-4.686 12.284 0 16.97 4.687 4.686 12.285 4.686 16.97 0 4.687-4.686 4.687-12.284 0-16.97-4.685-4.687-12.283-4.687-16.97 0zm-7.704 35.74c-6.627 0-12 5.37-12 12 0 6.626 5.373 12 12 12s12-5.374 12-12c0-6.63-5.373-12-12-12zm-30.72 19.822c-4.686-4.686-12.284-4.686-16.97 0-4.686 4.687-4.686 12.285 0 16.97 4.686 4.687 12.284 4.687 16.97 0 4.687-4.685 4.687-12.283 0-16.97zm-35.74-7.704c0-6.627-5.372-12-12-12-6.626 0-12 5.373-12 12s5.374 12 12 12c6.628 0 12-5.373 12-12zm-19.823-30.72c4.687-4.686 4.687-12.284 0-16.97-4.686-4.686-12.284-4.686-16.97 0-4.687 4.686-4.687 12.284 0 16.97 4.686 4.687 12.284 4.687 16.97 0z">
						<animateTransform
							attributeName="transform"
							type="rotate"
							from="0 67 67"
							to="360 67 67"
							dur="8s"
							repeatCount="indefinite"/>
					</path>';
				break;

			case 'grid':
				$result .=
					'<circle cx="12.5" cy="12.5" r="12.5">
						<animate attributeName="fill-opacity"
						 begin="0s" dur="1s"
						 values="1;.2;1" calcMode="linear"
						 repeatCount="indefinite" />
					</circle>
					<circle cx="12.5" cy="52.5" r="12.5" fill-opacity=".5">
						<animate attributeName="fill-opacity"
						 begin="100ms" dur="1s"
						 values="1;.2;1" calcMode="linear"
						 repeatCount="indefinite" />
					</circle>
					<circle cx="52.5" cy="12.5" r="12.5">
						<animate attributeName="fill-opacity"
						 begin="300ms" dur="1s"
						 values="1;.2;1" calcMode="linear"
						 repeatCount="indefinite" />
					</circle>
					<circle cx="52.5" cy="52.5" r="12.5">
						<animate attributeName="fill-opacity"
						 begin="600ms" dur="1s"
						 values="1;.2;1" calcMode="linear"
						 repeatCount="indefinite" />
					</circle>
					<circle cx="92.5" cy="12.5" r="12.5">
						<animate attributeName="fill-opacity"
						 begin="800ms" dur="1s"
						 values="1;.2;1" calcMode="linear"
						 repeatCount="indefinite" />
					</circle>
					<circle cx="92.5" cy="52.5" r="12.5">
						<animate attributeName="fill-opacity"
						 begin="400ms" dur="1s"
						 values="1;.2;1" calcMode="linear"
						 repeatCount="indefinite" />
					</circle>
					<circle cx="12.5" cy="92.5" r="12.5">
						<animate attributeName="fill-opacity"
						 begin="700ms" dur="1s"
						 values="1;.2;1" calcMode="linear"
						 repeatCount="indefinite" />
					</circle>
					<circle cx="52.5" cy="92.5" r="12.5">
						<animate attributeName="fill-opacity"
						 begin="500ms" dur="1s"
						 values="1;.2;1" calcMode="linear"
						 repeatCount="indefinite" />
					</circle>
					<circle cx="92.5" cy="92.5" r="12.5">
						<animate attributeName="fill-opacity"
						 begin="200ms" dur="1s"
						 values="1;.2;1" calcMode="linear"
						 repeatCount="indefinite" />
					</circle>';
				break;

			case 'hearts':
				$result .=
					'<path d="M30.262 57.02L7.195 40.723c-5.84-3.976-7.56-12.06-3.842-18.063 3.715-6 11.467-7.65 17.306-3.68l4.52 3.76 2.6-5.274c3.717-6.002 11.47-7.65 17.305-3.68 5.84 3.97 7.56 12.054 3.842 18.062L34.49 56.118c-.897 1.512-2.793 1.915-4.228.9z" fill-opacity=".5">
						<animate attributeName="fill-opacity"
							 begin="0s" dur="1.4s"
							 values="0.5;1;0.5"
							 calcMode="linear"
							 repeatCount="indefinite" />
					</path>
					<path d="M105.512 56.12l-14.44-24.272c-3.716-6.008-1.996-14.093 3.843-18.062 5.835-3.97 13.588-2.322 17.306 3.68l2.6 5.274 4.52-3.76c5.84-3.97 13.592-2.32 17.307 3.68 3.718 6.003 1.998 14.088-3.842 18.064L109.74 57.02c-1.434 1.014-3.33.61-4.228-.9z" fill-opacity=".5">
						<animate attributeName="fill-opacity"
							 begin="0.7s" dur="1.4s"
							 values="0.5;1;0.5"
							 calcMode="linear"
							 repeatCount="indefinite" />
					</path>
					<path d="M67.408 57.834l-23.01-24.98c-5.864-6.15-5.864-16.108 0-22.248 5.86-6.14 15.37-6.14 21.234 0L70 16.168l4.368-5.562c5.863-6.14 15.375-6.14 21.235 0 5.863 6.14 5.863 16.098 0 22.247l-23.007 24.98c-1.43 1.556-3.757 1.556-5.188 0z" />';
				break;

			case 'oval':
				$result .=
					'<g fill="none" fill-rule="evenodd">
						<g transform="translate(1 1)" stroke-width="2">
							<circle stroke-opacity=".3" cx="18" cy="18" r="18"/>
							<path d="M36 18c0-9.94-8.06-18-18-18">
								<animateTransform
									attributeName="transform"
									type="rotate"
									from="0 18 18"
									to="360 18 18"
									dur="1s"
									repeatCount="indefinite"/>
							</path>
						</g>
					</g>';
				break;

			case 'puff':
				$result .=
					'<g fill="none" fill-rule="evenodd" stroke-width="2">
						<circle cx="22" cy="22" r="1">
							<animate attributeName="r"
								begin="0s" dur="1.8s"
								values="1; 20"
								calcMode="spline"
								keyTimes="0; 1"
								keySplines="0.165, 0.84, 0.44, 1"
								repeatCount="indefinite" />
							<animate attributeName="stroke-opacity"
								begin="0s" dur="1.8s"
								values="1; 0"
								calcMode="spline"
								keyTimes="0; 1"
								keySplines="0.3, 0.61, 0.355, 1"
								repeatCount="indefinite" />
						</circle>
						<circle cx="22" cy="22" r="1">
							<animate attributeName="r"
								begin="-0.9s" dur="1.8s"
								values="1; 20"
								calcMode="spline"
								keyTimes="0; 1"
								keySplines="0.165, 0.84, 0.44, 1"
								repeatCount="indefinite" />
							<animate attributeName="stroke-opacity"
								begin="-0.9s" dur="1.8s"
								values="1; 0"
								calcMode="spline"
								keyTimes="0; 1"
								keySplines="0.3, 0.61, 0.355, 1"
								repeatCount="indefinite" />
						</circle>
					</g>';
				break;

			case 'rings':
				$result .=
					'<g fill="none" fill-rule="evenodd" transform="translate(1 1)" stroke-width="2">
						<circle cx="22" cy="22" r="6" stroke-opacity="0">
							<animate attributeName="r"
								 begin="1.5s" dur="3s"
								 values="6;22"
								 calcMode="linear"
								 repeatCount="indefinite" />
							<animate attributeName="stroke-opacity"
								 begin="1.5s" dur="3s"
								 values="1;0" calcMode="linear"
								 repeatCount="indefinite" />
							<animate attributeName="stroke-width"
								 begin="1.5s" dur="3s"
								 values="2;0" calcMode="linear"
								 repeatCount="indefinite" />
						</circle>
						<circle cx="22" cy="22" r="6" stroke-opacity="0">
							<animate attributeName="r"
								 begin="3s" dur="3s"
								 values="6;22"
								 calcMode="linear"
								 repeatCount="indefinite" />
							<animate attributeName="stroke-opacity"
								 begin="3s" dur="3s"
								 values="1;0" calcMode="linear"
								 repeatCount="indefinite" />
							<animate attributeName="stroke-width"
								 begin="3s" dur="3s"
								 values="2;0" calcMode="linear"
								 repeatCount="indefinite" />
						</circle>
						<circle cx="22" cy="22" r="8">
							<animate attributeName="r"
								 begin="0s" dur="1.5s"
								 values="6;1;2;3;4;5;6"
								 calcMode="linear"
								 repeatCount="indefinite" />
						</circle>
					</g>';
				break;

			case 'spinning-circles':
				$result .=
					'<g fill="none" fill-rule="evenodd">
						<g transform="translate(2 1)" stroke="' . $stcol . '" stroke-width="1.5">
							<circle cx="42.601" cy="11.462" r="5" fill-opacity="1" fill="' . $fgcol . '">
								<animate attributeName="fill-opacity"
									 begin="0s" dur="1.3s"
									 values="1;0;0;0;0;0;0;0" calcMode="linear"
									 repeatCount="indefinite" />
							</circle>
							<circle cx="49.063" cy="27.063" r="5" fill-opacity="0" fill="' . $fgcol . '">
								<animate attributeName="fill-opacity"
									 begin="0s" dur="1.3s"
									 values="0;1;0;0;0;0;0;0" calcMode="linear"
									 repeatCount="indefinite" />
							</circle>
							<circle cx="42.601" cy="42.663" r="5" fill-opacity="0" fill="' . $fgcol . '">
								<animate attributeName="fill-opacity"
									 begin="0s" dur="1.3s"
									 values="0;0;1;0;0;0;0;0" calcMode="linear"
									 repeatCount="indefinite" />
							</circle>
							<circle cx="27" cy="49.125" r="5" fill-opacity="0" fill="' . $fgcol . '">
								<animate attributeName="fill-opacity"
									 begin="0s" dur="1.3s"
									 values="0;0;0;1;0;0;0;0" calcMode="linear"
									 repeatCount="indefinite" />
							</circle>
							<circle cx="11.399" cy="42.663" r="5" fill-opacity="0" fill="' . $fgcol . '">
								<animate attributeName="fill-opacity"
									 begin="0s" dur="1.3s"
									 values="0;0;0;0;1;0;0;0" calcMode="linear"
									 repeatCount="indefinite" />
							</circle>
							<circle cx="4.938" cy="27.063" r="5" fill-opacity="0" fill="' . $fgcol . '">
								<animate attributeName="fill-opacity"
									 begin="0s" dur="1.3s"
									 values="0;0;0;0;0;1;0;0" calcMode="linear"
									 repeatCount="indefinite" />
							</circle>
							<circle cx="11.399" cy="11.462" r="5" fill-opacity="0" fill="' . $fgcol . '">
								<animate attributeName="fill-opacity"
									 begin="0s" dur="1.3s"
									 values="0;0;0;0;0;0;1;0" calcMode="linear"
									 repeatCount="indefinite" />
							</circle>
							<circle cx="27" cy="5" r="5" fill-opacity="0" fill="' . $fgcol . '">
								<animate attributeName="fill-opacity"
									 begin="0s" dur="1.3s"
									 values="0;0;0;0;0;0;0;1" calcMode="linear"
									 repeatCount="indefinite" />
							</circle>
						</g>
					</g>';
				break;

			case 'tail-spin':
				$result .=
					'<defs>' .
						'<linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">' .
							'<stop stop-color="#000" stop-opacity="0" offset="0%"/>' .
							'<stop stop-color="#000" stop-opacity=".631" offset="63.146%"/>' .
							'<stop stop-color="#000" offset="100%"/>' .
						'</linearGradient>' .
					'</defs>' .
					'<g fill="none" fill-rule="evenodd">' .
						'<g transform="translate(1 1)">' .
							'<path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="' . $fgcol . '" stroke-width="2">' .
								'<animateTransform' .
									' attributeName="transform"' .
									' type="rotate"' .
									' from="0 18 18"' .
									' to="360 18 18"' .
									' dur="1.25s"' .
									' repeatCount="indefinite" />' .
							'</path>' .
							'<circle fill="' . $fgcol . '" cx="36" cy="18" r="1">' .
								'<animateTransform' .
									' attributeName="transform"' .
									' type="rotate"' .
									' from="0 18 18"' .
									' to="360 18 18"' .
									' dur="1.25s"' .
									' repeatCount="indefinite" />' .
							'</circle>' .
						'</g>' .
					'</g>';
				break;

			case 'three-dots':
				$result .=
					'<circle cx="15" cy="15" r="15">
						<animate attributeName="r" from="15" to="15"
								 begin="0s" dur="0.8s"
								 values="15;9;15" calcMode="linear"
								 repeatCount="indefinite" />
						<animate attributeName="fill-opacity" from="1" to="1"
								 begin="0s" dur="0.8s"
								 values="1;.5;1" calcMode="linear"
								 repeatCount="indefinite" />
					</circle>
					<circle cx="60" cy="15" r="9" fill-opacity="0.3">
						<animate attributeName="r" from="9" to="9"
								 begin="0s" dur="0.8s"
								 values="9;15;9" calcMode="linear"
								 repeatCount="indefinite" />
						<animate attributeName="fill-opacity" from="0.5" to="0.5"
								 begin="0s" dur="0.8s"
								 values=".5;1;.5" calcMode="linear"
								 repeatCount="indefinite" />
					</circle>
					<circle cx="105" cy="15" r="15">
						<animate attributeName="r" from="15" to="15"
								 begin="0s" dur="0.8s"
								 values="15;9;15" calcMode="linear"
								 repeatCount="indefinite" />
						<animate attributeName="fill-opacity" from="1" to="1"
								 begin="0s" dur="0.8s"
								 values="1;.5;1" calcMode="linear"
								 repeatCount="indefinite" />
					</circle>';
				break;

			default:
				$result .=
					'<rect x="0" y="0" width="100" height="100" fill="none" class="bk" >' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(0 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(22.5 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0.09375s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(45 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0.1875s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(67.5 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0.28125s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(90 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0.375s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(112.5 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0.46875s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(135 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0.5625s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(157.5 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0.65625s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(180 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0.75s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(202.5 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0.84375s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(225 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0.9375s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(247.5 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="1.03125s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(270 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="1.125s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(292.5 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="1.21875s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(315 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="1.3125s" repeatCount="indefinite"/>' .
					'</rect>' .
					'<rect class="wppa-ajaxspin"  x="47" y="40" width="6" height="20" rx="3" ry="3" transform="rotate(337.5 50 50) translate(0 -32)">' .
						'<animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="1.40625s" repeatCount="indefinite"/>' .
					'</rect>';
				break;

		}
		$result .= '</svg>';
	}

	// No .svg possible / requested, default to .gif
	else {
		$result =
			'<img' .
				' id="' . $args['id'] . '"' .
				' src="' . wppa_get_imgdir() . 'loader.gif"' .
				' class="' . $args['class'] . ' wppa-ajax-spin"' .
				' alt="spinner"' .
				' style="' .
					'width:' . $args['size'] . 'px;' .
					'height:' . $args['size'] . 'px;' .
					'position:' . $args['position'] . ';' .
					'top:50%;' .
					'margin-top:-' . $args['margin'] . 'px;' .
					'left:50%;' .
					'margin-left:-' . $args['margin'] . 'px;' .
					'z-index:200100;' .
					'opacity:1;' .
					'display:' . $args['display'] . ';' .
					'background-color:' . $bgcol . ';' .
					'box-shadow:none;' .
					'"' .
			' />';
	}

	if ( is_ssl() ) {
		$result = str_replace( 'http://', 'https://', $result );
	}
	return $result;
}

// Are we on a windows platform?
function wppa_is_windows() {

	// Windows uses \ instead of /, so if no / in ABSPATH, we are on a windows platform
	return strpos( ABSPATH, '/' ) === false;
}

// If it is a pdf, do postprocessing
function wppa_pdf_postprocess( $id ) {

	// If pdf...
	if ( wppa( 'is_pdf' ) ) {
		$filename = wppa_get_photo_item( $id, 'filename' );
		$filename = str_replace( '.jpg', '.pdf', $filename );
		wppa_update_photo( $id, ['filename' => $filename] );
	}

	// Reset switch
	wppa( 'is_pdf', false );
}

// Has the system 'many' albums?
function wppa_has_many_albums() {
global $wpdb;
static $n_albums;

	// Max specified? If not, return false
	if ( ! wppa_opt( 'photo_admin_max_albums' ) ) {
		return false;
	}

	// Find total number of albums, if not done before
	if ( ! $n_albums ) {
		$n_albums = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_albums" );
	}

	// Decide if many
	if ( $n_albums > wppa_opt( 'photo_admin_max_albums' ) ) {
		return true;
	}
	return false;
}

// Return false if user is logged in, upload roles are specified and i do not have one of them
function wppa_check_user_upload_role() {

	// Not logged in, ok
	if ( ! is_user_logged_in() ) {
		return true;
	}

	// No roles specified: ok
	if ( ! wppa_opt( 'user_opload_roles' ) ) {
		return true;
	}

	// Roles specified
	$roles = explode( ',', wppa_opt( 'user_opload_roles' ) );
	foreach ( $roles as $role ) {
		if ( current_user_can( $role ) ) {
			return true;
		}
	}

	// No matching role
	return false;
}

// Return false if user is logged in, comment roles are specified and i do not have one of them
function wppa_check_user_comment_role() {

	// Not logged in, ok
	if ( ! is_user_logged_in() ) {
		return true;
	}

	// No roles specified: ok
	if ( ! wppa_opt( 'user_comment_roles' ) ) {
		return true;
	}

	// Roles specified
	$roles = explode( ',', wppa_opt( 'user_comment_roles' ) );
	foreach ( $roles as $role ) {
		if ( current_user_can( $role ) ) {
			return true;
		}
	}

	// No matching role
	return false;
}

// Like wp_parse_args (args is array only), but it replaces NULL array elements also with the defaults.
function wppa_parse_args( $args, $defaults ) {

	// Remove NULL elements from $args
	$r = (array) $args;

	foreach( array_keys( $r ) as $key ) {

		// This looks funny, but:
		// a NULL element is regarded as being not set,
		// but it would not be overwritten by the default value in the merge
		if ( ! isset( $r[$key] ) ) {
			unset( $r[$key] );
		}
	}

	// Do the merge
	if ( is_array( $defaults ) ) {
		$r = array_merge( $defaults, $r );
	}

	return $r;
}

function wppa_is_divisible( $t, $n ) {

	if ( ! is_numeric( $t ) || ! is_numeric( $n ) ) {
		return false;
	}
	else {
		return ( round( $t / $n ) == ( $t / $n ) );
	}
}

function wppa_dump( $txt = '' ) {

	if ( ! is_writable( dirname( __FILE__ ) ) ) return;

	// Init
	$file = dirname( __FILE__ ) . '/wppa-dump.txt';

	$who = wppa_get_user( 'login' );
	$when = wppa_local_date( 'd.m.Y H:i:s', time());

	if ( $txt ) {
		if ( wppa_is_file( $file ) ) {
			$txt = wppa_get_contents( $file ) . "\n" . /* $who . ' ' . $when . ' ' . */ $txt;
		}
		wppa_put_contents( $file, $txt );
	}
	else {
		wppa_unlink( $file );
	}
}

function wppa_is_pdf( $id ) {
	return ( strtolower( wppa_get_ext( wppa_get_photo_item( $id, 'filename' ) ) ) == 'pdf' );
}

function wppa_get_pdf_html( $id ) {

	// May the current user see this document?
	if ( ! wppa_is_photo_visible( $id ) ) {
		$result = '';
	}

	if ( wppa_is_mobile() || ! wppa_is_pdf( $id ) ) {
		$result = '';
	}
	else {
		$result = 'src="' . esc_attr( wppa_get_hires_url( $id ) ) . '"';
	}
	return $result;
}

// If wppa embedded lightbox, show wait cursor prior to lightbox init. when generic lightbox, show pointer cursor
function wppa_wait() {
	$result = 'wait';
	return $result;
}

// Get navigation symbol size
function wppa_icon_size( $default = '', $type = 0, $factor = 1 ) {

	switch ( $type ) {
		case 1:
			$opt = wppa_opt( 'nav_icon_size_slide' );
			break;
		case 2:
			$opt = wppa_opt( 'icon_size_rating' );
			break;
		default:
			$opt = wppa_opt( 'nav_icon_size' );
			break;
	}

	if ( $opt === 'default' ) {
		$units = strpos( $default, 'em' ) !== false ? 'em' : 'px';
		$opt = rtrim( $default, 'pxem;' );
	}
	else {
		$units = 'px';
	}

	$opt *= $factor;
	$result = ( wppa_in_widget() ? $opt / '2' : $opt ) . $units . ';';

	return $result;
}

// See if a photo is a panorama
function wppa_is_panorama( $id ) {

	$result = wppa_get_photo_item( $id, 'panorama' );
	if ( $result == '1' && ! wppa_switch( 'enable_panorama' ) ) {
		$result = '';
	}
	return $result;
}

// Rename all files inside a tree to their sanitized name (recursive)
function wppa_rename_files_sanitized( $root ) {

	// Get the filesystem objects
	$my_import_files = wppa_glob( $root . '/*' );

	clearstatcache();

	// If files
	if ( is_array( $my_import_files ) ) {

		foreach( $my_import_files as $path ) {

			// See if path is utf8 encoded
			if ( ! seems_utf8( $path ) ) {
				$path = utf8_encode( $path );
			}
			$file = basename( $path );

			// Remove really impossible chars
			$file = str_replace( '%', 'pct', $file );

			// Sanitize path, at least utf8 converted and extension downcased
			if ( wppa_switch( 'sanitize_import' ) ) {
				$new_path = dirname( $path ) . '/' . wppa_down_ext( sanitize_file_name( $file ) );
			}
			else {
				$new_path = dirname( $path ) . '/' . wppa_down_ext( $file );
			}

			// Process files
			if ( wppa_is_file( $path ) ) {

				if ( $new_path != $path ) {
					wppa_rename( $path, $new_path );
				}
			}

			// Process directories
			elseif ( wppa_is_dir( $path ) ) {

				if ( $new_path != $path ) {
				@	wppa_rename( $path, $new_path );
					wppa_log( 'fso', 'Sanitized import folder ' . $path . ' to ' . $new_path );
				}

				// Recursively one level deeper
				wppa_rename_files_sanitized( $path );
			}
		}
	}
}

function wppa_sanitize_album_photo_name( $xname ) {

	$special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", "%", "+", chr(0));
	$name = str_replace( $special_chars, '', $xname );

	if ( wppa_switch( 'remove_accents' ) ) {
		$name = remove_accents( $name );
	}
	$name = sanitize_file_name( $name );

	return $name;
}

function wppa_nl2sp( $text ) {
	$result = str_replace( array( "\r\n","\n" ), ' ', $text );
	return $result;
}

// Thumbnail aspect (for real calendar)
function wppa_get_thumb_aspect() {

	$aspect = 1;
	if ( wppa_opt( 'thumb_aspect' ) != '0:0:none' ) {
		$t = explode( ':', wppa_opt( 'thumb_aspect' ) );
		$aspect = $t[0] / $t[1];
	}
	elseif ( wppa_opt( 'resize_to' ) != '-1' && wppa_opt( 'resize_to' ) != '0' ) {
		$t = explode( 'x', wppa_opt( 'resize_to' ) );
		$aspect = $t[1] / $t[0];
	}
	else {
		$aspect = wppa_opt( 'maxheight' ) / wppa_opt( 'fullsize' );
	}

	return $aspect;
}

// Wrapper around wppa_get_option, but checks the settings first
function wppa_get_option( $name, $default = null ) {
global $wppa_defaults;
global $all_wppa_options;

	// If the option is a setting, use the default for the setting as the default
	if ( isset( $wppa_defaults[$name] ) ) {
		$default = $wppa_defaults[$name];
	}

	// Get all options if not yet done
	if ( $all_wppa_options == NULL ) {
		$all_wppa_options = wp_load_alloptions();
	}

	if ( isset( $all_wppa_options[$name] ) ) {
		$result = $all_wppa_options[$name];
		if ( is_serialized( $result ) ) {
			$result = unserialize( $result );
		}
	}
	else {
		$result = $default;
	}

	// If an array is expected and its not an array, return an empty array
	if ( is_array( $default ) && ! is_array( $result ) ) {
		$result = array();
	}

	return $result;
}

// Compress html
function wppa_compress_html( $txt, $keeplinebreaks = false ) {

	if ( ! $txt ) return '';

	$result = $txt;

	// Keep linebreaks when a textarea tag is seen
	if ( strpos( $txt, '<textarea' ) !== false ) {
		$keeplinebreaks = true;
	}

	// Remove linebreaks
	if ( ! $keeplinebreaks ) {
		$result = str_replace( ["\r\n", "\n"], [" ", " "], $txt );
	}

	// Change tabs into spaces
	$result = str_replace( "\t", " ", $result );

	// Change multiple spaces into one
	$L0 = strlen( $result );
	$result = str_replace( "  ", " ", $result );
	$L1 = strlen( $result );
	while ( $L0 != $L1 ) {
		$L0 = $L1;
		$result = str_replace( "  ", " ", $result );
		$L1 = strlen( $result );
	}

	// Now do the other comprssions
	$from = 	array( 	"> <", "<ul></ul>", "<!--",   '" >', '&amp;', 'style=" ', '"/>',  ' style=""', 'px;;', '/>' );
	$to = 		array(	"><",  "",          "\n<!--", '">',  '&',     'style="',  '" />', ' ',          'px;', '>'  );

	$result = str_replace( $from, $to, $result );

	return $result;
}

// Find albums nesting level
function wppa_get_nesting_level( $id ) {
static $level;

	$level = _wppa_get_nesting_level( $id );

	return $level;
}
function _wppa_get_nesting_level( $id ) {

	if ( ! $id ) return '0';
	$alb = wppa_cache_album( $id );
	if ( is_array( $alb ) && $alb['a_parent'] > '0' ) {
		return _wppa_get_nesting_level( $alb['a_parent'] ) + '1';
	}
	else {
		return '0';
	}
}

// Can we do magick?
function wppa_can_magick() {
	return ( wppa_opt( 'image_magick' ) && wppa_opt( 'image_magick' ) != 'none' );
}

// Get my first granted album
function wppa_my_get_first_grant_album() {
global $wpdb;

	// Granted albums available?
	if ( wppa_switch( 'grant_an_album' ) ) {
		$parents = wppa_opt( 'grant_parent' );
		if ( is_array( $parents ) ) {
			$parent = $parents[0];
		}
		else {
			$parent = $parents;
		}
		$album = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE owner = %s AND a_parent = %d LIMIT 1", wppa_get_user(), $parent ) );
	}

	// No, take the first if any
	$album = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE owner = %s ORDER BY id LIMIT 1", wppa_get_user() ) );


	return $album;
}

// Construct current shortcode
function wppa_get_shortcode( $key, $atts, $no_delay = false ) {

	// Init
	$result = '';

	if ( $key && is_array( $atts ) ) {

		$result = '[' . $key;

		foreach ( array_keys( $atts ) as $key ) {
			if ( ! $no_delay || ( $key != 'delay' && $atts[$key] != 'delay' ) ) {
				if ( is_numeric( $key ) ) {
					$result .= ' ' . $atts[$key];
				}
				else {
					$result .= ' ' . $key . '="' . $atts[$key] . '"';
				}
			}
		}

		$result .= ']';
	}

	return $result;
}

// Do we do lazy loading?
function wppa_lazy() {

	$setting = wppa_opt( 'lazy' );
	if ( $setting == 'all' ) {
		return true;
	}
	if ( $setting == 'none' ) {
		return false;
	}
	if ( wppa_is_mobile() ) {
		return ( $setting == 'mobile' );
	}
	else {
		return ( $setting == 'pc' );
	}
}

// Are we building a cache file?
function wppa_is_caching() {
global $wppa_is_caching;

	return $wppa_is_caching;

	if ( wppa_in_widget() ) {
		return wppa( 'cache' );
	}

	return wppa_test_for_caching( true );
}

// Make sure text is utf8 encoded
function wppa_utf8( $string ) {
	if ( ! seems_utf8( $string ) ) {
		$string = utf8_encode( $string );
	}
	return $string;
}

// Use thumbnail popup?
function wppa_use_thumb_popup() {

	switch ( wppa_opt( 'thumb_popup' ) ) {
		case 'all':
			return true;
			break;
		case 'pc':
			return ! wppa_is_mobile();
			break;
		default: // none
			return false;
	}
}

// Use nicescroller here?
function wppa_is_nice( $where = '' ) {

	if ( wppa_is_mobile() && ! wppa_switch( 'nice_mobile' ) ) {
		return false;
	}

	switch ( $where ) {
		case 'window':

			// Prevent propagation does not work on mobile when moving
			return ( ! wppa_is_mobile() && wppa_switch( 'nicescroll_window' ) );
			break;
		default:
			return wppa_switch( 'nicescroll' );
			break;
	}
}

// Force browser to output immedialtely
function wppa_force_output() {

	ob_start();
	wppa_echo( '<span style="display:none">' );
	for ( $i=0;$i<21;$i++ ) wppa_echo('....................................................................................................');
	wppa_echo( '</span>' );
	ob_end_flush();
}

// Find out if the current user can see the shortcode generator icons in editors
function wppa_show_scgens() {

	$roles = get_option( 'wppa_show_scgens', '' );

	// Default?
	if ( ! $roles ) {
		return true;
	}

	// Convert allowed roles to array
	$roles = explode( ',', $roles );
	foreach( $roles as $role ) {
		if ( current_user_can( $role ) ) {

			// Yes we can
			return true;
		}
	}

	// No we can't
	return false;
}

// See if big browse buttons on lightbox
function wppa_ovl_big_browse() {

	if ( wppa_switch( 'ovl_big_browse' ) ) {
		return true;
	}
	else {
		return false;
	}
}

// See if small browse buttons on lightbox
function wppa_ovl_small_browse() {

	if ( wppa_switch( 'ovl_small_browse' ) ) {
		return true;
	}
	elseif ( ! wppa_ovl_big_browse() ) {
		return true;
	}
	else {
		return false;
	}
}

// Find logtype for background processes
function wppa_logtype( $slug ) {

	// Find log type
	if ( wppa_is_cron() ) {
		$logtype = 'cron';
	}
	else {
		$logtype = 'obs';
	}

	// If its an index proc and index logging is active, log as inx
	if ( in_array( $slug, ['wppa_remake_index_albums', 'wppa_remake_index_photos', 'wppa_cleanup_index'] ) && wppa_switch( 'log_idx' ) ) {
		$logtype = 'idx';
	}

	return $logtype;
}

// Get email subscriptions boxes body
function wppa_get_email_subscription_body() {

	$body = '';
	$uid = wppa_get_user_id();

	// New album
	if ( wppa_switch( 'newalbumnotify' ) ) {
		$in_list = wppa_am_i_in_mailinglist( 'newalbumnotify' );
		$body .= '
		<div style="clear:both;display:block;min-height:2em;">
			<input
				id="wppa-newalbum-notify"
				class="wppa-notify-check"
				type="checkbox"
				style="float:left;margin-top:2px;"  ' .
				( $in_list ? 'checked="checked" ' : ' ' ) .
				'onchange="wppaAjaxNotify(this,\'newalbumnotify\','.$uid.');"
			/>	<label
				for="wppa-newalbum-notify"
				class="wppa-notify-label"
				style="padding-left:4px;width:90%;"
				> ' .
				__( 'A new album is created', 'wp-photo-album-plus' ) . '
			</label>
		</div>';
	}

	// Only if fe upload notify is activated
	if ( wppa_switch( 'feuploadnotify' ) ) {
		$in_list = wppa_am_i_in_mailinglist( 'feuploadnotify' );
		$body .= '
		<div style="clear:both;display:block;min-height:2em;">
			<input
				id="wppa-feupload-notify"
				class="wppa-notify-check"
				type="checkbox"
				style="float:left;margin-top:2px;"  ' .
				( $in_list ? 'checked="checked" ' : ' ' ) .
				'onchange="wppaAjaxNotify(this,\'feuploadnotify\','.$uid.');"
			/>	<label
				for="wppa-feupload-notify"
				class="wppa-notify-label"
				style="padding-left:4px;width:90%;"
				> ' .
				__( 'A new photo is uploaded', 'wp-photo-album-plus' ) . '
			</label>
		</div>';
	}

	// Only if upload moderation is activated
	if ( wppa_switch( 'upload_moderate' ) ) {

		// Photo approved
		if ( wppa_switch( 'photoapproved' ) ) {
			$in_list = wppa_am_i_in_mailinglist( 'photoapproved' );
			$body .= '
			<div style="clear:both;display:block;min-height:2em;">
				<input
					id="wppa-photoapproved-notify"
					class="wppa-notify-check"
					type="checkbox"
					style="float:left;margin-top:2px;"  ' .
					( $in_list ? 'checked="checked" ' : ' ' ) .
					'onchange="wppaAjaxNotify(this,\'photoapproved\','.$uid.');"
				/>
				<label
					for="wppa-photoapproved-notify"
					class="wppa-notify-label"
					style="padding-left:4px;width:90%;"
					> ' .
					__( 'My photo is approved', 'wp-photo-album-plus' ) . '
				</label>
			</div>';
		}
	}

	// Only if comments sytem is activated
	if ( wppa_switch( 'show_comments' ) ) {

		// A comment is given
		if ( wppa_switch( 'commentnotify' ) ) {
			$in_list = wppa_am_i_in_mailinglist( 'commentnotify' );
							$body .= '
			<div style="clear:both;display:block;min-height:2em;">
				<input
					id="wppa-comment-notify"
					class="wppa-notify-check"
					type="checkbox"
					style="float:left;margin-top:2px;"  ' .
					( $in_list ? 'checked="checked" ' : ' ' ) .
					'onchange="wppaAjaxNotify(this,\'commentnotify\','.$uid.');"
				/> <label
					for="wppa-comment-notify"
					class="wppa-notify-label"
					style="padding-left:4px;width:90%;"
					> ' .
					( wppa_switch( 'commentnotify_limit' ) ?
						__( 'A comment on my photo is given', 'wp-photo-album-plus' ) :
						__( 'A comment on any photo is given', 'wp-photo-album-plus' ) ) . '
				</label>
			</div>';
		}

		// Comment approved (to photo owner and commenter)
		if ( wppa_switch( 'commentapproved' ) ) {
			$in_list = wppa_am_i_in_mailinglist( 'commentapproved' );
			$body .= '
			<div style="clear:both;display:block;min-height:2em;">
				<input
					id="wppa-commentapproved-notify"
					class="wppa-notify-check"
					type="checkbox"
					style="float:left;margin-top:2px;"  ' .
					( $in_list ? 'checked="checked" ' : ' ' ) .
					'onchange="wppaAjaxNotify(this,\'commentapproved\','.$uid.');"
				/> <label
					for="wppa-commentapproved-notify"
					class="wppa-notify-label"
					style="padding-left:4px;width:90%;"
					> ' .
					__( 'A comment on my photo is approved or my comment is approved', 'wp-photo-album-plus' ) . '
				</label>
			</div>';
		}

		// Commented previous (to photo owner)
		if ( wppa_switch( 'commentprevious' ) ) {
			$in_list = wppa_am_i_in_mailinglist( 'commentprevious' );
			$body .= '
			<div style="clear:both;display:block;min-height:2em;">
				<input
					id="wppa-commentprevious-notify"
					class="wppa-notify-check"
					type="checkbox"
					style="float:left;margin-top:2px;"  ' .
					( $in_list ? 'checked="checked" ' : ' ' ) .
					'onchange="wppaAjaxNotify(this,\'commentprevious\','.$uid.');"
				/>
				<label
					for="wppa-commentprevious-notify"
					class="wppa-notify-label"
					style="padding-left:4px;width:90%;"
					> ' .
					__( 'A comment is given on a photo that i commented before', 'wp-photo-album-plus' ) . '
				</label>
			</div>';
		}
	}

	// Only show to moderators
	if ( current_user_can( 'wppa_moderate' ) ) {

		// Only if photo modaration activated
		if ( wppa_switch( 'upload_moderate' ) ) {
			$in_list = wppa_am_i_in_mailinglist( 'moderatephoto' );
			$body .= '
			<div style="clear:both;display:block;min-height:2em;">
				<input
					id="wppa-upload-moderate"
					class="wppa-notify-check"
					type="checkbox"
					style="float:left;margin-top:2px;"  ' .
					( $in_list ? 'checked="checked" ' : ' ' ) .
					'onchange="wppaAjaxNotify(this,\'moderatephoto\','.$uid.');"
				/>
				<label
					for="wppa-upload-moderate"
					class="wppa-notify-label"
					style="padding-left:4px;width:90%;"
					> ' .
					__( 'A photo needs moderation', 'wp-photo-album-plus' ) . '
				</label>
			</div>';
		}

		// Only if comment moderation activated
		if ( wppa_opt( 'moderate_comment' ) != '-none-' ) {
			$in_list = wppa_am_i_in_mailinglist( 'moderatecomment' );
			$body .= '
			<div style="clear:both;display:block;min-height:2em;">
				<input
					id="wppa-upload-moderate-comment"
					class="wppa-notify-check"
					type="checkbox"
					style="float:left;margin-top:2px;"  ' .
					( $in_list ? 'checked="checked" ' : ' ' ) .
					'onchange="wppaAjaxNotify(this,\'moderatecomment\','.$uid.');"
				/>
				<label
					for="wppa-upload-moderate-comment"
					class="wppa-notify-label"
					style="padding-left:4px;width:90%;"
					> ' .
					__( 'A comment needs moderation', 'wp-photo-album-plus' ) . '
				</label>
			</div>';
		}
	}

	// Only for admin
	if ( wppa_user_is( 'administrator' ) ) {
		$in_list = wppa_am_i_in_mailinglist( 'subscribenotify' );
		$body .= '
		<div style="clear:both;display:block;min-height:2em;">
			<input
				id="wppa-subscribe"
				class="wppa-notify-check"
				type="checkbox"
				style="float:left;margin-top:2px;"  ' .
				( $in_list ? 'checked="checked" ' : ' ' ) .
				'onchange="wppaAjaxNotify(this,\'subscribenotify\','.$uid.');"
			/>
			<label
				for="wppa-subscribe"
				class="wppa-notify-label"
				style="padding-left:4px;width:90%;"
				> ' .
				__( 'A user subscribes/unsubscribes on/from a mailinglist', 'wp-photo-album-plus' ) . '
			</label>
		</div>';
	}

	return $body;
}

// Make a nameslug from a name
function wppa_name_slug( $name ) {

	$slug = stripslashes( strtolower( wppa_sanitize_album_photo_name( wppa_strip_tags( $name ) ) ) );
	return $slug;
}

// Get the path to the true media item, highest resolution if image, most likely first
function wppa_get_media_data( $id ) {

	// Init
	$result 	= ['path' => '', 'ext' => '', 'mime' => ''];
	$media 		= wppa_cache_photo( $id );
	$audioexts 	= wppa_has_audio( $id );
	if ( $audioexts ) $audioexts = array_keys( $audioexts );
	$videoexts 	= wppa_is_video( $id );
	if ( $videoexts ) $videoexts = array_keys( $videoexts );
	wppa( 'no_ver', true );

	// is it a pdf?
	if ( wppa_is_pdf( $id ) ) {
		$result['path'] 	= str_replace( WPPA_UPLOAD_URL, WPPA_UPLOAD_PATH, wppa_get_hires_url( $id ) );
		$result['ext'] 		= 'pdf';
		$result['mime'] 	= 'application/pdf';
		return $result;
	}

	// is it a video?
	if ( $videoexts ) {
		$path 				= wppa_get_photo_path( $id, false );
		$raw_path 			= wppa_strip_ext( $path );
		$result['path'] 	= $raw_path . '.' . $videoexts[0];
		$result['ext'] 		= $videoexts[0];
		$result['mime'] 	= 'video/' . $result['ext'];
		return $result;
	}

	// has it audio?
	if ( $audioexts ) {
		$path 				= wppa_get_photo_path( $id, false );
		$raw_path 			= wppa_strip_ext( $path );
		$result['path']  	= $raw_path . '.' . $audioexts[0];
		$result['ext'] 		= $audioexts[0];
		$result['mime'] 	= 'audio/' . $result['ext'];
		return $result;
	}

	// is it a photo?
	if ( wppa_is_photo( $id ) ) {
		$result['path'] 	= str_replace( WPPA_UPLOAD_URL, WPPA_UPLOAD_PATH, wppa_get_hires_url( $id ) );
		$result['ext'] 		= wppa_get_ext( $result['path'] );
		$result['mime'] 	= 'image/' . $result['ext'];
		return $result;
	}

	// unsupported filetype
	wppa_log( 'err', 'Item ' . $id . ' is of an unsupported media type' );
	return $result;

}


function wppa_is_ip_address( $maybe_ip ) {
	if ( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $maybe_ip ) ) {
		return 4;
	}

	if ( false !== strpos( $maybe_ip, ':' ) && preg_match( '/^(((?=.*(::))(?!.*\3.+\3))\3?|([\dA-F]{1,4}(\3|:\b|$)|\2))(?4){5}((?4){2}|(((2[0-4]|1\d|[1-9])?\d|25[0-5])\.?\b){4})$/i', trim( $maybe_ip, ' []' ) ) ) {
		return 6;
	}

	return false;
}

function wppa_is_photo_deleted( $id ) {

	$photo = wppa_cache_photo( $id );
	if ( ! $photo ) {
		return true; 	// Deleted long ago
	}
	if ( $photo['album'] < '1' ) {
		return true; 	// Deleted pending removal
	}
	return false; 		// Still there
}

function wppa_is_anon() {

	if ( wppa( 'anon' ) ) return true;
	if ( wppa_get( 'occur' ) == wppa( 'mocc' ) && wppa_get( 'anon', '0', 'int' ) ) return true;
	return false;
}

function wppa_is_meonly() {

	if ( wppa( 'meonly' ) ) return true;
	if ( wppa_get( 'occur' ) == wppa( 'mocc' ) && wppa_get( 'meonly', '0', 'int' ) ) return true;
	return false;
}

// The following 2 routines make an advanced diagnostic tool to find the source of unexpected output.
// Usage:
//
// Place wppa_ob_start( '<the current function name>' ); at the beginning of any function that you suspect to produce unexpected output
// Place wppa_ob_end(); just before every return; in the same function.
// Make sure you activated advanced logging and log type miscalleneous.
// If the function outputs bogus, it will be catched and logged.
function wppa_ob_start( $function ) {
global $wppa_ob_stack;

	if ( ! is_array( $wppa_ob_stack ) ) {
		$wppa_ob_stack = array();
	}
	$stack_count = count(  $wppa_ob_stack );

	$wppa_ob_stack[] = $function;
	ob_start();
}

function wppa_ob_end() {
global $wppa_ob_stack;
global $last_message;

	if ( ! is_array( $wppa_ob_stack ) ) {
		$wppa_ob_stack = array();
		$last_message = '';
	}
	$stack_count = count(  $wppa_ob_stack );
	$garbage = ob_get_contents();
	if ( $garbage ) {
		$message = 'Unexpected output detected in {b}' . implode( ' -> ', $wppa_ob_stack ) . '.{/b} Content: ' . $garbage;
		if ( $message != $last_message ) {
			wppa_log( 'misc', $message );
			$last_message = $message;
		}
	}

	$wppa_ob_stack = array_slice( $wppa_ob_stack, 0 , $stack_count - 1 );
	ob_end_clean();
}

// All supported mimetypes
function wppa_get_mime_types() {

	$mime_types = array(
		// Image formats.
		'jpg|jpeg|jpe'                 => 'image/jpeg',
		'gif'                          => 'image/gif',
		'png'                          => 'image/png',
		'webp'                         => 'image/webp',
		// Video formats.
		'mp4|m4v'                      => 'video/mp4',
		'ogv'                          => 'video/ogg',
		'webm'                         => 'video/webm',
		// Audio formats.
		'mp3|m4a|m4b'                  => 'audio/mpeg',
		'wav'                          => 'audio/wav',
		'ogg|oga'                      => 'audio/ogg',
		// Misc application formats.
		'pdf'                          => 'application/pdf',
		'zip'                          => 'application/zip',
	);
	return $mime_types;
}

// Get item type
function wppa_get_type( $id, $translate = false ) {

	// audio
	if ( wppa_has_audio( $id ) ) {
		if ( $translate ) {
			return __( 'audio', 'wp-photo-album-plus' );
		}
		else {
			return 'audio';
		}
	}

	// Video
	if ( wppa_is_video( $id ) ) {
		if ( $translate ) {
			return __( 'video', 'wp-photo-album-plus' );
		}
		else {
			return 'video';
		}
	}

	// Document
	if ( wppa_is_pdf( $id ) ) {
		if ( $translate ) {
			return __( 'document', 'wp-photo-album-plus' );
		}
		else {
			return 'document';
		}
	}

	// default
	if ( $translate ) {
		return __( 'photo', 'wp-photo-album-plus' );
	}
	else {
		return 'photo';
	}
}

