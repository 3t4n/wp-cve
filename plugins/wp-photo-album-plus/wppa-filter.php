<?php
/* wppa-filter.php
* Package: wp-photo-album-plus
*
* get the albums via shortcode handler
* Version: 8.6.04.008
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Declare action hook actions
add_action('init', 'wppa_do_filter');

function wppa_do_filter() {

	add_filter( 'the_content', 'wppa_add_shortcode_to_post' );
}

// Add a specific shortcode at the end of a post in runtime
// The content filter must be executed ( normal priority 10 )
// before shortcode processing ( normal at priority 11 )
// for this to work
function wppa_add_shortcode_to_post( $post ) {

	$new_post = $post;
	if ( ! wppa( 'ajax' ) && wppa_switch( 'add_shortcode_to_post' ) ) {
		$id = wppa_get_the_ID();
		$p = get_post( $id, ARRAY_A );
		if ( $p['post_type'] == 'post' ) $new_post .= wppa_opt( 'shortcode_to_add' );
	}
	return $new_post;
}

// Shortcode [wppa_div style="{style specs}"][/wppa_div]
function wppa_shortcode_div( $xatts, $content = '' ) {
static $seqno;
global $wppa_current_shortcode;
global $wppa_current_shortcode_atts;

	// Init
	$wppa_current_shortcode_atts = $xatts;
	$wppa_current_shortcode = wppa_get_shortcode( 'wppa_div', $xatts );

	if ( ! $seqno ) $seqno = 0;
	$seqno++;

	$atts = shortcode_atts( array(
		'class' 		=> '',
//		'nicescroll' 	=> '',
		'height' 		=> '',
		'max-height' 	=> '',
//		'overflow' 		=> '',
		), $xatts );

	// Use nicescroller?
	$nice = wppa_is_nice();
	if ( $nice ) {
		$style = 'clear:both;position:relative;overflow:hidden;';
	}
	else {
		$style = 'clear:both;position:relative;overflow:auto;';
	}

	// Class
	$class = trim( $atts['class'] . ' wppa-div' );

	// Height
	$height = $atts['height'];
	if ( wppa_is_int( $height ) ) {
		$style .= 'height:' . $height . 'px;';
	}
	else {
		$style .= 'height:' . $height . ';';
	}

	// Max-height
	$data_maxheight = '';
	$max_height = $atts['max-height'];
	if ( $max_height ) {
		if ( $max_height > 1 ) {
			$style .= 'max-height:' . strval( intval( $max_height ) ) . 'px;';
		}
		else {
			$class .= ' wppa-autodiv';
			$data_maxheight = ' data-max-height="' . esc_attr( $max_height ) . '"';
		}
	}

	// Open the div
	$result = '
	<div
		id="wppa-div-' . $seqno . '"
		style="' . esc_attr( $style ) . '"
		class="' . esc_attr( $class ) . '" ' .
		$data_maxheight . '
		>' .

		// Open nice wrapper
		( $nice ? '<div class="wppa-divnicewrap" >' : '' ) .

			// Content
			do_shortcode( $content ) .

		// Close nice wrapper
		( $nice ? '</div>': '' ) .

	// Close the div
	'</div>';

	// The nivescroller js
	if ( $nice  ) {
		$the_js = 'jQuery(document).ready(function(){
			if ( jQuery().niceScroll )
			jQuery("#wppa-div-' . $seqno . '").niceScroll(".wppa-divnicewrap",{' . wppa_opt( 'nicescroll_opts' ) . '});});';
		wppa_add_inline_script( 'wppa', $the_js, true );
	}

	return $result;
}

add_shortcode( 'wppa_div', 'wppa_shortcode_div' );

// The shortcode handler
function wppa_shortcodes( $xatts ) {
global $wppa;
global $wppa_postid;
global $wppa_version;
global $wppa_revno;
global $wppa_no_timer;
global $wpdb;
global $wppa_current_shortcode;
global $wppa_current_shortcode_no_delay;
global $wppa_current_shortcode_atts;
global $albums_used;
global $photos_used;
global $other_deps;

	// Init
	wppa_reset_occurrance();
	$wppa_current_shortcode_atts 	= (array) $xatts;
	$wppa_no_timer 					= false;
	$is_calendar 					= false;

	// Save shortcode
	$wppa_current_shortcode 			= wppa_get_shortcode( 'wppa', $xatts );
	$wppa_current_shortcode_no_delay 	= wppa_get_shortcode( 'wppa', $xatts, true );

	// Preprocess and completize atts
	$atts = wppa_shortcode_atts( (array) $xatts );

	// If false returned, wo do not do anything (because of login required and not logged in etc). Housekeeping done
	if ( false === $atts ) {
		return '';
	}

	// Interprete $atts into $wppa
	$wppa['cache'] 		= $atts['cache'];
	$wppa['in_widget'] 	= $atts['widget'];
	$wppa['landscape'] 	= $atts['landscape'];
	$wppa['portrait'] 	= $atts['portrait'];
	$wppa['anon'] 		= $atts['anon'];
	$wppa['meonly'] 	= $atts['meonly'];
	$wppa['container-wrapper-class'] = $atts['class'];

	// Assume all toplevel albums used when generic
	if ( $wppa['cache'] && $atts['type'] == 'generic' ) {
		$temp = $wpdb->get_col( "SELECT id FROM $wpdb->wppa_albums WHERE a_parent = 0 ORDER BY id" );
		$albums_used = implode( '.', $temp );
	}

	// Find occur
	if ( wppa_get_the_ID() != $wppa_postid && ! $wppa['in_widget'] ) {		// New post
		$wppa['mocc'] = '0';						// Init this occurance
		$wppa['fullsize'] = '';						// Reset at each post
		$wppa_postid = wppa_get_the_ID();			// Remember the post id
	}
	if ( $wppa['in_widget'] && $wppa['mocc'] < 200 ) {
		$wppa['mocc'] += 200;
	}
	if ( $wppa['in_widget'] ) {
		$wppa['mocc'] ++;
	}
	elseif ( $atts['mocc'] ) {
		$wppa['mocc'] = $atts['mocc'] - 1;
	}

	// If parent given, overwrite album by children
	if ( $atts['parent'] !== '' ) {
		$temp = explode( ',', $atts['parent'] );

		// Test for virtual album
		if ( is_array( $temp ) && count( $temp ) > 1 && wppa_is_enum( $temp[1] ) ) {
			$temp[1] = wppa_alb_to_enum_children( $temp[1] );
			$atts['album'] = implode( ',', $temp );
		}
		elseif ( wppa_is_enum( $atts['parent'] ) ) {
			$atts['album'] = wppa_alb_to_enum_children( $atts['parent'] );
		}
		else {
			wppa_bump_mocc();
			$err = '<span style="color:red">[Error: Syntax error or unsupported parent specification: ' . $atts['parent'] . ']</span>';
			return $err;
		}
	}

	// Find type
	$type = $atts['type'];

	// Delay?
	$void_delay_types = array( 'upload',
							   'tagcloud',
							   'multitag',
							   'bestof',
							   'superview',
							   'search',
							   'supersearch',
							   'choice',
							   'stereo',
							   'grid',
							   'landing',
							   'calendar',
							   'notify',
							   );
	$void_delay_albums = array( '#me',
								'#upldr',
								'#owner',
								'#potd',
								'#tags',
								);

	switch( wppa_opt( 'delay_overrule' ) ) {

		case 'always':
			$atts['delay'] = 'yes';
			if ( in_array( $type, $void_delay_types ) || in_array( $type, ['photo', 'mphoto', 'xphoto'] ) ) {
				$atts['delay'] = '';
			}

			foreach( $void_delay_albums as $va ) {
				if ( strpos( $atts['album'], $va ) !== false ) {
					$atts['delay'] = '';
				}
			}
			break;
		case 'never':
			$atts['delay'] = '';
			break;
		default:
			if ( $atts['delay'] ) {
				if ( in_array( $type, $void_delay_types ) ) {
					wppa_you_can_not( 'delay', $type );
					$atts['delay'] = false;
				}
				foreach( $void_delay_albums as $va ) {
					if ( strpos( $atts['album'], $va ) !== false ) {
						wppa_you_can_not( 'delay', $va );
						$atts['delay'] = false;
					}
				}
			}
			break;
	}

	// If querystring present, delay will cause looping
	if ( wppa_get( 'occur', '0' ) ) {
		$atts['delay'] = false;
	}

	// If mobile and ignore size align, ignore it
	if ( wppa_is_mobile() && wppa_switch( 'mobile_ignore_sa' ) ) {
		$atts['size'] = 'auto';
		$atts['align'] = '';
	}

	// If album="#tags,#me", translate #me to displayname
	if ( substr( $atts['album'], 0, 9 ) == '#tags,#me' ) {
		if ( is_user_logged_in() ) {
			$unam = wppa_get_user( 'display' );
		}
		else {
			$unam = '';
		}
		$atts['album'] = str_replace( '#me', $unam, $atts['album'] );
	}

	// If album="#posttitle", convert to id, if not found, create it
	if ( $atts['album'] == '#posttitle' ) {
		$post_id = get_the_ID();
		if ( $post_id ) {
			$the_post = get_post( $post_id );
			$the_name = $the_post->post_title;
			$owner_id = $the_post->post_author;
			$albs = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_albums WHERE name = %s", $the_name ) );
			if ( $albs ) {
				$atts['album'] = implode( '.', $albs );
			}
			else {
				switch( wppa_opt( 'wppa_posttitle_owner' ) ) {
					case '--- postauthor ---':
						$usr = get_user_by( 'ID', $owner_id );
						$owner = $usr->user_login;
						break;
					case '--- public ---':
						$owner = '--- public ---';
						break;
					default:
						// Should never get here
						wppa_log( 'err', 'Unimplemented #posttitle album owner: '.wppa_opt( 'wppa_posttitle_owner' ));
						return "Error. Unimplemented album owner indicator: ".wppa_opt( 'wppa_posttitle_owner' );
				}

				$alb = wppa_create_album_entry( ['name' 		=> $the_name,
												 'description' 	=> __( 'Automatically created for post', 'wp-photo-album-plus' ) . ' ' . $the_name,
												 'owner' 		=> $owner,
												 ] );
				if ( $alb ) {
					$atts['album'] = $alb;
				}
				else {
					wppa_log( 'err', 'Could not create album for post ' . $the_name );
					return "Error. Could not create album for this post";
				}
			}
		}
	}

	// Displatch on type
	switch ( $type ) {
		case 'version':
			return $wppa_version;
			break;
		case 'dbversion':
			return $wppa_revno;
			break;
		case 'landing':
			$wppa['is_landing'] = '1';
			break;
		case 'generic':
			break;
		case 'cover':
			$wppa['start_album'] = $atts['album'];
			$wppa['is_cover'] = '1';
			$wppa['albums_only'] = true;
			break;
		case 'album':
		case 'content':
			$wppa['start_album'] = $atts['album'];
			break;
		case 'thumbs':
			$wppa['start_album'] = $atts['album'];
			$wppa['photos_only'] = true;
			break;
		case 'covers':
			$wppa['start_album'] = $atts['album'];
			$wppa['albums_only'] = true;
			break;
		case 'slide':
			$wppa['start_album'] = $atts['album'];
			$wppa['is_slide'] = '1';
			$wppa['start_photo'] = $atts['photo'];
			if ( $atts['button'] ) {
				$wppa['is_button'] = esc_attr( __( $atts['button'] ) );
			}
			if ( $atts['timeout'] ) {
				$wppa['timeout'] = strval( intval ( $atts['timeout'] ) );
			}
			break;
		case 'slideonly':
			$wppa['start_album'] = $atts['album'];
			$wppa['is_slideonly'] = '1';
			$wppa['start_photo'] = $atts['photo'];
			if ( $atts['timeout'] ) {
				$wppa['timeout'] = strval( intval ( $atts['timeout'] ) );
			}
			break;
		case 'slideonlyf':
			$wppa['start_album'] = $atts['album'];
			$wppa['is_slideonly'] = '1';
			$wppa['is_slideonlyf'] = '1';
			$wppa['film_on'] = '1';
			$wppa['start_photo'] = $atts['photo'];
			if ( $atts['timeout'] ) {
				$wppa['timeout'] = strval( intval ( $atts['timeout'] ) );
			}
			break;
		case 'slidef':
			$wppa['start_album'] = $atts['album'];
			$wppa['is_slide'] = '1';
			$wppa['film_on'] = '1';
			$wppa['is_slideonly'] = '1';
			$wppa['is_filmonly'] = '1';
			$wppa['start_photo'] = $atts['photo'];
			if ( $atts['timeout'] ) {
				$wppa['timeout'] = strval( intval ( $atts['timeout'] ) );
			}
			break;
		case 'filmonly':
			$wppa['start_album'] = $atts['album'];
			$wppa['is_slideonly'] = '1';
			$wppa['is_filmonly'] = '1';
			$wppa['film_on'] = '1';
			$wppa['start_photo'] = $atts['photo'];
			if ( $atts['timeout'] ) {
				$wppa['timeout'] = strval( intval ( $atts['timeout'] ) );
			}
			break;
		case 'photo':
		case 'sphoto':
			$wppa['single_photo'] = $atts['photo'];
			$wppa['start_photo'] = $atts['photo'];
			$wppa['start_album'] = $atts['album'];
			break;
		case 'mphoto':
			$wppa['single_photo'] = $atts['photo'];
			$wppa['start_photo'] = $atts['photo'];
			$wppa['start_album'] = $atts['album'];
			$wppa['is_mphoto'] = '1';
			break;
		case 'xphoto':
			$wppa['single_photo'] = $atts['photo'];
			$wppa['start_photo'] = $atts['photo'];
			$wppa['start_album'] = $atts['album'];
			$wppa['is_xphoto'] = '1';
			break;
		case 'slphoto':
			$wppa['is_slide'] = '1';
			$wppa['single_photo'] = $atts['photo'];
			$wppa['start_photo'] = $atts['photo'];
			$wppa['start_album'] = $atts['album'];
			$wppa['is_single'] = '1';
			break;
		case 'audio':
			$wppa['is_audioonly'] = '1';
			$wppa['audio_item'] = $atts['audio'];
			$wppa['audio_album'] = $atts['album'];
			$wppa['audio_poster'] = $atts['poster'];
			break;
		case 'autopage':
			$wppa['is_autopage'] = '1';
			break;
		case 'upload':
			if ( $atts['parent'] ) {
				$wppa['start_album'] = wppa_alb_to_enum_children( $atts['parent'] );
			}
			else {
				$wppa['start_album'] = $atts['album'];
			}
			if ( ! $wppa['start_album'] ) {
				$wppa['start_album'] = '0';
			}
			$wppa['is_upload'] = true;
			break;
		case 'multitag':
			$wppa['taglist'] = wppa_sanitize_tags($atts['taglist']);
			$wppa['is_multitagbox'] = true;
			if ( $atts['cols'] ) {
				$cols = explode( ',', $atts['cols'] );
				$col = $cols[0];
				if ( isset( $cols[1] ) && wppa_is_mobile() ) {
					$col = $cols[1];
				}
				if ( ! wppa_is_int( $col ) || $col < '1' ) $col = '2'; // On error use default
				$wppa['tagcols'] = $col;
			}
			break;
		case 'tagcloud':
			$wppa['taglist'] = wppa_sanitize_tags($atts['taglist']);
			$wppa['is_tagcloudbox'] = true;
			break;
		case 'bestof':
			$wppa['bestof'] = true;
			$wppa['bestof_args'] = $atts;
			$photos_used = '*';
			$other_deps = 'R';
			break;
		case 'superview':
			$wppa['is_superviewbox'] = true;
			$wppa['start_album'] = $atts['album'];
			break;
		case 'search':
			$wppa['is_searchbox'] = true;
			$wppa['may_sub'] = $atts['sub'];
			if ( $atts['root'] ) {
				if ( substr( $atts['root'], 0, 1 ) == '#' ) {
					$wppa['forceroot'] = strval( intval( substr( $atts['root'], 1 ) ) );
				}
				else {
					$wppa['may_root'] = $atts['root'];
				}
			}
			$wppa['landingpage'] = $atts['landing'];
			break;
		case 'supersearch':
			$wppa['is_supersearch'] = true;
			break;
		case 'calendar':
			$is_calendar = true;
			$wppa['is_calendar'] = true;
			$wppa['calendar'] = 'timestamp';
			if ( in_array( $atts['calendar'], array( 'exifdtm', 'timestamp', 'modified', 'realexifdtm', 'realtimestamp', 'realmodified' ) ) ) {
				$wppa['calendar'] = $atts['calendar'];
			}
//			if ( $atts['delay'] && substr( $atts['calendar'], 0, 4 ) != 'real' ) {
//				wppa_you_can_not( 'delay', $type . ' ' . $atts['calendar'] );
//				$atts['delay'] = '';
//			}
			$wppa['reverse'] 		= $atts['reverse'];
			$wppa['start_album'] 	= $atts['album'];
			if ( $atts['parent'] ) {
				$wppa['start_album'] = wppa_alb_to_enum_children( $atts['parent'] );
			}
			$wppa['year'] = strval( intval( $atts['year'] ) );
			$wppa['month'] = strval( intval( $atts['month'] ) );
			break;
		case 'stereo':
			$wppa['is_stereobox'] = true;
			break;
		case 'url':
			$wppa['is_url'] = true;
			$wppa['single_photo'] = $atts['photo'];
			$wppa_no_timer = true;
			break;
		case 'choice':
			$wppa['is_admins_choice'] = true;
			$wppa['admins_choice_users'] = $atts['admin'];
			break;
		case 'acount':
		case 'pcount':
			$alb = $atts['album'];
			if ( ! $alb || $alb < '1' ) {
				$err = '
				<span style="color:red">
				Error in shortcode spec for type="' . $atts['type'] . '":
				either attribute album="" or parent="" should supply a positive integer or enumeration
				</span>';
				return $err;
			}

			$parent_given = $atts['parent'] != '';
			if ( $parent_given ) {
				$albs = explode( '.', wppa_expand_enum( $atts['parent'] ) );
			}
			else {
				$albs = explode( '.', wppa_expand_enum( $atts['album'] ) );
			}

			$total = 0;
			foreach( $albs as $alb ) {

				$counts = wppa_get_treecounts_a( $alb );

				if ( is_array( $counts ) ) {

					// Parent given
					if ( $parent_given ) {

						// Albums requested
						if ( $type == 'acount' ) {
							$total += $counts['treealbums'];
						}

						// Photos requested
						else {
							$total += $counts['treephotos'];
						}
					}

					// Album given
					else {

						// Albums requested
						if ( $type == 'acount' ) {
							$total += $counts['selfalbums'];
						}

						// Photos requested
						else {
							$total += $counts['selfphotos'];
						}
					}
				}
			}
			return $total;
			break;
		case 'share':
			$result = wppa_get_share_page_html();
			return $result;
			break;
		case 'lastupdate':
			$album = $atts['album'] ? $atts['album'] : '0';
			if ( $album ) {
				$timestamp = $wpdb->get_var( $wpdb->prepare( "SELECT timestamp FROM $wpdb->wppa_photos WHERE album = %d ORDER BY timestamp DESC LIMIT 1", $album ) );
			}
			else {
				$timestamp = $wpdb->get_var( "SELECT timestamp FROM $wpdb->wppa_photos ORDER BY timestamp DESC LIMIT 1" );
			}
			if ( $timestamp ) {
				$result = wppa_local_date( wppa_get_option( 'date_format' ), $timestamp );
				return $result;
			}
			else {
				return ( __( 'Unavailable', 'wp-photo-album-plus' ) );
			}
			break;
		case 'contest':
			$album = $atts['album'] ? $atts['album'] : '0';
			if ( strpos( $album, '..' ) !== false ) {
				$album = wppa_expand_enum( $album );
			}
			if ( ! $album ) {
				$err = '
				<span style="color:red">
				Error in shortcode spec for type="contest":
				Missing or invalid attribute album="<album id>"<br>
				album=' . $atts['album'] . ', parent=' . $atts['parent'] . '</span>';
				return $err;
			}
			$wppa['start_album'] = $atts['album'];
			$wppa['is_contest'] = true;
			break;
		case 'grid':
			$wppa['is_grid'] = true;
			$wppa['photos_only'] = true;
			if ( $atts['photos'] ) {
				$wppa['start_photos'] = $atts['photos'];
			}
			elseif ( $atts['album'] ) {
				$wppa['start_album'] = $atts['album'];
			}
			if ( isset( $atts['cols'] ) ) {
				$cols = explode( ',', $atts['cols'] );
				$col = $cols[0];
				if ( isset( $cols[1] ) && wppa_is_mobile() ) {
					$col = $cols[1];
				}
				if ( ! wppa_is_int( $col ) || $col < '1' ) $col = '2'; // On error use default
				$wppa['gridcols'] = $col;
			}
			break;
		case 'notify':
			$wppa['is_notify'] = true;
			break;

		default:
			wppa_log( 'Err', 'Invalid type: ' . htmlentities( $atts['type'] ) . ' in wppa shortcode ' . $wppa_current_shortcode );
			return '';
	}

	// Count (internally to wppa_albums)

	// Find size. Assume default responsive
	if ( $atts['size'] == 'auto' ) {
		$wppa['auto_colwidth'] = true;
		$wppa['fullsize'] = '';
		$wppa['max_width'] = '';
	}
	elseif ( $atts['size'] && is_numeric( $atts['size'] ) && $atts['size'] < 1.0 ) {
		$wppa['auto_colwidth'] = true;
		$wppa['fullsize'] = $atts['size'];
	}
	elseif ( substr( $atts['size'], 0, 4 ) == 'auto' ) {
		$wppa['auto_colwidth'] = true;
		$wppa['fullsize'] = '';
		$wppa['max_width'] = substr( $atts['size'], 5 );
	}
	else {
		$wppa['auto_colwidth'] = false;
		$wppa['fullsize'] = $atts['size'];
	}

	// Find align
	$wppa['align'] = $atts['align'];

	// Delay
	if ( $atts['delay'] ) {
		if ( substr( $atts['delay'], 0, 3 ) == 'yes' || substr( $atts['delay'], 0, 4 ) == 'text' || substr( $atts['delay'], 0, 6 ) == 'button' ) {
			$wppa['delay'] = $atts['delay'];
		}
	}

	// Can not delay when in ajax
	if ( defined( 'DOING_AJAX' ) ) {
		$wppa['delay'] = '';
	}

	// Remember albums and photos used if caching.
	// This is used to know what caches must be cleared in cas a photo or album gets changed.
	if ( $wppa['cache'] ) {
		$albums_used = wppa_expand_enum( $wppa['start_album'] );
		$photos_used = $atts['photos'] ? wppa_expand_enum( $atts['photos'] ) : $atts['photo'];
	}
	else {
		$albums_used = '';
		$photos_used = '';
		$other_deps  = '';
	}

	// Ready to render
	$result =  wppa_albums();						// Get the HTML

	// Calendar needs an extra container
	if ( $is_calendar ) {
		wppa_reset_occurrance();
		wppa_bump_mocc(); //$wppa['mocc'] += '1';
		wppa_container( 'open' );
		wppa_container( 'close' );
		$result .= $wppa['out'];
	}

	// Relative urls?
	$result = wppa_make_relative( $result );

	// Compress
	$result = wppa_compress_html( $result );

	// Done
	return $result;
}

// Preprocess wppa shortcode atts
function wppa_shortcode_atts( $xatts ) {
global $wppa_set_used;
global $wppa_opt;
global $wppa_forced_mocc;
global $wppa_is_caching;

	$atts = [
		'type'  	=> 'generic',
		'album' 	=> '',
		'photo' 	=> '',
		'photos' 	=> '',
		'size'		=> 'auto',
		'align'		=> '',
		'taglist'	=> '',
		'cols'		=> '',
		'sub' 		=> '',
		'root' 		=> '',
		'calendar' 	=> '',
		'reverse' 	=> '',
		'landing' 	=> '',
		'admin' 	=> '',
		'parent' 	=> '',
		'timeout' 	=> '',
		'button' 	=> '',
		'delay' 	=> '',
		'year' 		=> '',
		'month' 	=> '',
		'cache' 	=> '',
		'login' 	=> '',
		'widget' 	=> '',
		'landscape' => '',
		'portrait' 	=> '',
		'audio' 	=> '',
		'poster' 	=> '',
		'timeout' 	=> '',
		'mocc' 		=> '',
		'set' 		=> '',
		'anon' 		=> '',
		'meonly' 	=> '',
		'class' 	=> '',
	];

	// Shortcode attributes that do not need a value. Convert them to 'attr => 1'
	$no_s = ['0', 'no', 'off'];
	if ( in_array( 'landscape', $xatts ) 	|| ( isset( $xatts['landscape'] ) 	&& ! in_array( $xatts['landscape'], $no_s ) ) )	$xatts['landscape'] = '1'; 	else $xatts['landscape'] = '0';
	if ( in_array( 'portrait', $xatts ) 	|| ( isset( $xatts['portrait'] ) 	&& ! in_array( $xatts['portrait'], $no_s ) ) )	$xatts['portrait'] = '1'; 	else $xatts['portrait'] = '0';
	if ( in_array( 'cache', $xatts ) 		|| ( isset( $xatts['cache'] ) 		&& ! in_array( $xatts['cache'], $no_s ) ) )		$xatts['cache'] = '1'; 		else $xatts['cache'] = '0';
	if ( in_array( 'anon', $xatts ) 		|| ( isset( $xatts['anon'] ) 		&& ! in_array( $xatts['anon'], $no_s ) ) ) 		$xatts['anon'] = '1'; 		else $xatts['anon'] = '0';
	if ( in_array( 'meonly', $xatts ) 		|| ( isset( $xatts['meonly'] ) 		&& ! in_array( $xatts['meonly'], $no_s ) ) ) 	$xatts['meonly'] = '1'; 	else $xatts['meonly'] = '0';
	if ( in_array( 'delay', $xatts ) ) $xatts['delay'] = 'yes';

	// Login requested?
	if ( in_array( 'login', (array) $xatts ) ) {
		if ( ! is_user_logged_in() ) {
			wppa_bump_mocc();
			return false; // Signal give up
		}
	}
	if ( isset( $xatts['login'] ) ) {
		if ( $xatts['login'] == 'admin' && ! wppa_user_is_admin() ) {
			wppa_bump_mocc();
			return false;
		}
		if ( $xatts['login'] == 'yes' && ! is_user_logged_in() ) {
			wppa_bump_mocc();
			return false;
		}
	}

	// Sanitize input
	foreach ( array_keys( $xatts ) as $key ) {
		$xatts[$key] = strip_tags( $xatts[$key] ); // NOT htmlspecialchars because of album="$cat,René" has allowed funny chars
		$xatts[$key] = str_replace( ['%23', 'QUOTE', 'APOS'], ['#', '', "'"], $xatts[$key] ); // Fix for Gutenberg previews
	}
	if ( isset( $xatts['size'] ) && $xatts['size'] == '1' ) {
		$xatts['size'] = 'auto';
	}
	if ( isset( $xatts['class'] ) && $xatts['class'] != '' ) {
		$xatts['class'] = wppa_sanitize_text( $xatts['class'] );
	}

	// Caching?
	switch( wppa_opt( 'cache_overrule' ) ) {
		case 'always':
			$xatts['cache'] = '1';
			break;
		case 'never':
			$xatts['cache'] = '0';
			break;
		default:
	}

	// Void caching types
	if ( isset( $xatts['type'] ) ) {
		$void_cache_types = array( 'landing',
								   'upload',
								   'search',
								   'calendar'
								   );
		if ( in_array( $xatts['type'], $void_cache_types ) ) {
			$xatts['cache'] = '';
		}
	}

	// Void caching virtual albums / photos
	if ( isset( $xatts['album'] ) ) {
		if ( strpos( $xatts['album'], '#me' ) !== false ) $xatts['cache'] = '0';
		if ( strpos( $xatts['album'], '#owner' ) !== false ) $xatts['cache'] = '0';
		if ( strpos( $xatts['album'], '#upldr' ) !== false ) $xatts['cache'] = '0';
	}
	if ( isset( $xatts['photo'] ) ) {
		if ( strpos( $xatts['photo'], '#potd' ) !== false ) $xatts['cache'] = '0';
	}

	// Make globally known 'we are building a cache file'
	if ( strpos( $_SERVER['REQUEST_URI'], 'wp-admin/widgets.php' ) !== false ) $xatts['cache'] = '';
	if ( strpos( $_SERVER['REQUEST_URI'], 'wp-json/wp/v2/widget-types' ) !== false ) $xatts['cache'] = '';
	$wppa_is_caching = $xatts['cache'];

	// Update usedby fields
	$page = get_the_ID();

	if ( isset( $xatts['photo'] ) ) {
		if ( wppa_is_enum( $xatts['photo']  ) ) {
			wppa_add_usedby( $xatts['photo'], $page, 'photo' );
		}
	}
	if ( isset( $xatts['album'] ) ) {
		if ( wppa_is_enum( $xatts['album']  ) ) {
			wppa_add_usedby( $xatts['album'], $page, 'album' );
		}
	}

	// Fix timeout
	if ( isset( $xatts['timeout'] ) ) {
		$xatts['timeout'] = strval( 1000 * intval( $xatts['timeout'] ) );
	}

	// Sanitize mocc, must be 100 <= mocc < 200 or ''
	if ( isset( $xatts['mocc'] ) ) {
		$m = $xatts['mocc'];
		if ( 100 <= $m && $m < 200 ) {
			$xatts['mocc'] = $m;
		}
		else $xatts['mocc'] = '';
		$wppa_forced_mocc = $xatts['mocc'];
	}
	else {
		$xatts['mocc'] = '';
		$wppa_forced_mocc = 0;
	}

	// Reset possible sets in previous sc
	if ( $wppa_set_used ) {
		wppa_initialize_runtime( true, false );
	}

	// Set used this time?
	if ( isset( $xatts['set'] ) ) {
		$wppa_set_used = true;
		$opts_arr = explode( ',', $xatts['set'] );
		foreach( $opts_arr as $opt ) {
			$t = explode( ':', $opt );
			if ( count( $t ) == 2 ) {
				$name = $t[0];
				$value = $t[1];
				wppa_proc_set( ['name' => $name, 'value' => $value] );
			}
		}
	}

	$atts = wp_parse_args( $xatts, $atts );

	return $atts;
}

// Declare the shortcode handler
add_shortcode( 'wppa', 'wppa_shortcodes' );

// The runtime modifiable settings are processed by the wppa_set shortcode
function wppa_set_shortcodes( $xatts, $content = '' ) {
global $wppa;
global $wppa_opt;
global $wppa_runtime_settings;

	if ( ! $wppa_runtime_settings ) {
		$wppa_runtime_settings = array();
	}

	$atts = shortcode_atts( array(
		'name' 		=> '',
		'value' 	=> ''
	), $xatts );

	// Reset?
	if ( ! $atts['name'] ) {
		$wppa_opt = false;
		wppa_initialize_runtime();
		wppa_reset_occurrance();
		$wppa_runtime_settings = array();
		return;
	}

	// Process item $atts
	wppa_proc_set( $atts );
}

// Enable wppa_set shortcode handler
add_shortcode( 'wppa_set', 'wppa_set_shortcodes' );

// Process [name => ..., value => ...] array for runtime setting change
function wppa_proc_set( $item ) {
global $wppa_opt;
global $wppa_runtime_settings;

	// Are we enabled?
	if ( ! wppa_switch( 'enable_shortcode_wppa_set' ) ) {
		wppa_log( 'war', 'Runtime modifyable settings not enabled (wppa_proc_set)' );
		return;
	}

	// Find out if it is an option or a runtime setting
	$is_opt = false;
	$name = $item['name'];
	if ( isset( $wppa_opt[$name] ) ) {
		$is_opt = true;
	}
	elseif ( isset( $wppa_opt['wppa_' . $name] ) ) {
		$is_opt = true;
		$name = 'wppa_' . $name;
	}

	// Option?
	if ( $is_opt ) {
		$wppa_opt[$name] = $item['value'];
	}

	// Runtime setting
	else {
		$wppa_runtime_settings[$name] = $item['value'];
	}
}

// Enable simple photo shortcode handler
add_shortcode( 'photo', 'wppa_photo_shortcodes' );

function wppa_photo_shortcodes( $xatts ) {
global $wppa;
global $wppa_postid;
global $wpdb;
static $seed;
global $wppa_current_shortcode;
global $wppa_current_shortcode_atts;
global $photos_used;

	if ( ! wppa_switch( 'photo_shortcode_enabled' ) ) {
		wppa_log( 'war', 'Photo shortcode must be enabled before you can use it' );
		return;
	}

	// Init
	wppa_reset_occurrance();
	$wppa_current_shortcode_atts = $xatts;
	$wppa_current_shortcode = wppa_get_shortcode( 'photo', $xatts );

	// Get and validate photo id
	if ( isset( $xatts[0] ) ) {
		$photo = $xatts[0];
		if ( is_numeric( $photo ) && ! wppa_photo_exists( $photo ) ) {
			return sprintf( __( 'Photo %d does not exist', 'wp-photo-album-plus' ), $photo );
		}
	}
	else {
		return __( 'Missing photo id', 'wp-photo-album-plus' );
	}

	// Are we in a widget?
	if ( isset( $xatts['widget'] ) ) {
		$wppa['in_widget'] = $xatts['widget'];
	}

	// Find occur
	if ( wppa_get_the_ID() != $wppa_postid && ! $wppa['in_widget'] ) {		// New post
		$wppa['mocc'] = '0';					// Init this occurance
		$wppa['fullsize'] = '';					// Reset at each post
		$wppa_postid = wppa_get_the_ID();			// Remember the post id
	}
	if ( $wppa['in_widget'] && $wppa['mocc'] < 100 ) {
		$wppa['mocc'] += 100;
	}
	if ( $wppa['in_widget'] ) {
		$wppa['mocc'] ++;
	}

	// Random photo?
	if ( $wppa_postid && $photo == 'random' ) {

		if ( ! $seed ) {
			$seed = time();
		}
		$seed = floor( $seed * 0.9 );

		if ( wppa_opt( 'photo_shortcode_random_albums' ) != '-2' ) {
			$albs  = str_replace( '.', ',', wppa_expand_enum( wppa_opt( 'photo_shortcode_random_albums' ) ) );
			$photo = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos
													  WHERE album IN (" . $albs . ")
													  ORDER BY RAND(%d)
													  LIMIT 1", $seed ) );
		}
		else {
			$photo = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos
													  ORDER BY RAND(%d)
													  LIMIT 1", $seed ) );
		}
		if ( $photo ) {
			if ( wppa_switch( 'photo_shortcode_random_fixed' ) ) {
				$post_content = $wpdb->get_var( $wpdb->prepare( "SELECT post_content
																 FROM $wpdb->posts
																 WHERE ID = %d", $wppa_postid ) );
				if ( wppa_switch( 'photo_shortcode_random_fixed_html' ) ) {
					$post_content = preg_replace( '/\[photo random\]/', do_shortcode('[photo '.$photo.']'), $post_content, 1, $done );
				}
				else {
					$post_content = preg_replace( '/\[photo random\]/', '[photo '.$photo.']', $post_content, 1, $done );
				}
				$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts
											   SET post_content = %s
											   WHERE ID = %d", $post_content, $wppa_postid ) );
			}
		}
		else {
			return __( 'No random photo found', 'wp-photo-album-plus' );
		}
	}

	// Get configuration settings
	$type 	= wppa_opt( 'photo_shortcode_type' ); // 'xphoto';
	$size 	= wppa_opt( 'photo_shortcode_size' ); // '350';
	$align 	= wppa_opt( 'photo_shortcode_align' ); //'left';

	switch ( $type ) {
		case 'photo':
		case 'sphoto':
			$wppa['single_photo'] 	= $photo;
			$wppa['start_photo'] 	= $photo;
			break;
		case 'mphoto':
			$wppa['single_photo'] 	= $photo;
			$wppa['start_photo'] 	= $photo;
			$wppa['is_mphoto'] 		= '1';
			break;
		case 'xphoto':
			$wppa['single_photo'] 	= $photo;
			$wppa['start_photo'] 	= $photo;
			$wppa['is_xphoto'] 		= '1';
			break;
		case 'slphoto':
			$wppa['is_slide'] 		= '1';
			$wppa['single_photo'] 	= $photo;
			$wppa['start_photo'] 	= $photo;
			$wppa['is_single'] 		= '1';
			break;
		default:
			wppa_log( 'err', "Unimplemented photo_shortcode_type: $type in wppa_photo_shortcodes()" );
			break;
	}

	// Process size
	if ( $size && is_numeric( $size ) && $size < 1.0 ) {
		$wppa['auto_colwidth'] 		= true;
		$wppa['fullsize'] 			= $size;
	}
	elseif ( substr( $size, 0, 4 ) == 'auto' ) {
		$wppa['auto_colwidth'] 		= true;
		$wppa['fullsize'] 			= '';
		$wppa['max_width'] 			= substr( $size, 5 );
	}
	else {
		$wppa['auto_colwidth'] 		= false;
		$wppa['fullsize'] 			= $size;
	}

	// Find align
	$wppa['align'] = $align;

	// Cache?
	if ( isset( $xatts['cache'] ) ) {
		$photos_used = $photo;
		$wppa['cache'] = ! in_array( $xatts['cache'], array( '', '0', 'off', 'no' ) );
	}

	// Delay
	if ( isset( $xatts['delay'] ) ) {
		wppa_you_can_not( 'delay', 'single image' );
		$xatts['delay'] = '';
	}

	// Update used BY
	if ( wppa_is_int( $photo ) ) {
		wppa_add_usedby( $photo, get_the_ID(), 'photo' );
	}

	return wppa_albums();
}

// Yuo can not cache/delay a type xxx shortocde
function wppa_you_can_not( $xaction, $xtype, $useless = true ) {
	$action = __( $xaction, 'wp-photo-album-plus' );
	$type   = __( $xtype, 'wp-photo-album-plus' );
	/* translators: Example: You can not delay a single image shortcode display */
	$result = sprintf( __( 'You can not %1s a %2s shortcode display.', 'wp-photo-album-plus' ), $action, $type ) .
			  ( $useless ? ' ' . __( 'It is useless anyway.', 'wp-photo-album-plus' ) : '' );
	wppa_log( 'dbg', $result );
	return $result;
}

// This function is no longer needed in 8.0
function wppa_insert_shortcode_output( $result ) {
	wppa_log( 'err', 'wppa_insert_shortcode_output() is deprecated and no longer needed' );
	return $result;
}
