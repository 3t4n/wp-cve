<?php
/* wppa-styles.php
/* Package: wp-photo-album-plus
/*
/* Various style computation routines
/* Version 8.2.04.005
/*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

// Create dynamic css
function wppa_create_wppa_dynamic_css() {

	$the_css = '
.wppa-box {	' .
	( wppa_opt( 'bwidth' ) > '0' ? 'border-style: solid; border-width:' . wppa_opt( 'bwidth' ) . 'px;' : '' ) .
	( wppa_opt( 'bradius' ) > '0' ? 'border-radius:' . wppa_opt( 'bradius' ) . 'px; -moz-border-radius:' . wppa_opt( 'bradius' ) . 'px;' : '' ) .
	( wppa_opt( 'box_spacing' ) ? 'margin-bottom:' . wppa_opt( 'box_spacing' ) . 'px;' : '' ) .
	( wppa_opt( 'bgcolor' ) ? 'background-color:' . wppa_opt( 'bgcolor' ) . ';' : '' ) .
	( wppa_opt( 'bcolor' ) ? 'border-color:' . wppa_opt( 'bcolor' ) . ';' : '' ) .
' }
.wppa-mini-box { ' .
	( wppa_opt( 'bwidth' ) > '0' ? 'border-style: solid; border-width:' . floor( ( wppa_opt( 'bwidth' ) + 2 ) / 3 ) . 'px;' : '' ) .
	( wppa_opt( 'bradius' ) > '0' ? 'border-radius:' . floor( ( wppa_opt( 'bradius' ) + 2 ) / 3 ) . 'px;' : '' ) .
	( wppa_opt( 'bcolor' ) ? 'border-color:' . wppa_opt( 'bcolor' ) . ';' : '' ) .
' }
.wppa-cover-box { ' .
	( wppa_opt( 'cover_minheight' ) ? 'min-height:' . wppa_opt( 'cover_minheight' ) . 'px;' : '' ) .
' }
.wppa-cover-text-frame { ' .
	( wppa_opt( 'head_and_text_frame_height' ) ? 'min-height:' . wppa_opt( 'head_and_text_frame_height' ) . 'px;' : '' ) .
' }
.wppa-box-text { ' .
	( wppa_opt( 'fontcolor_box' ) ? 'color:' . wppa_opt( 'fontcolor_box' ) . ';' : '' ) .
' }
.wppa-box-text, .wppa-box-text-nocolor { ' .
	( wppa_opt( 'fontfamily_box' ) ? 'font-family:' . wppa_opt( 'fontfamily_box' ) . ';' : '' ) .
	( wppa_opt( 'fontsize_box' ) ? 'font-size:' . wppa_opt( 'fontsize_box' ) . 'px;' : '' ) .
	( wppa_opt( 'fontweight_box' ) ? 'font-weight:' . wppa_opt( 'fontweight_box' ) . ';' : '' ) .
' }
.wppa-thumb-text { ' .
	( wppa_opt( 'fontfamily_thumb' ) ? 'font-family:' . wppa_opt( 'fontfamily_thumb' ) . ';' : '' ) .
	( wppa_opt( 'fontsize_thumb' ) ? 'font-size:' . wppa_opt( 'fontsize_thumb' ) . 'px; line-height:' . floor( wppa_opt( 'fontsize_thumb' ) * 1.29 ) . 'px;' : '' ) .
	( wppa_opt( 'fontcolor_thumb' ) ? 'color:' . wppa_opt( 'fontcolor_thumb' ) . ';' : '' ) .
	( wppa_opt( 'fontweight_thumb' ) ? 'font-weight:' . wppa_opt( 'fontweight_thumb' ) . ';' : '' ) .
' }
.wppa-nav-text { ' .
	( wppa_opt( 'fontfamily_nav' ) ? 'font-family:' . wppa_opt( 'fontfamily_nav' ) . ';' : '' ) .
	( wppa_opt( 'fontsize_nav' ) ? 'font-size:' . wppa_opt( 'fontsize_nav' ) . 'px;' : '' ) .
	( wppa_opt( 'fontcolor_nav' ) ? 'color:' . wppa_opt( 'fontcolor_nav' ) . ';' : '' ) .
	( wppa_opt( 'fontweight_nav' ) ? 'font-weight:' . wppa_opt( 'fontweight_nav' ) . ';' : '' ) .
' }
.wppa-img { ' .
	( wppa_opt( 'bgcolor_img' ) ? 'background-color:' . wppa_opt( 'bgcolor_img' ) . ';' : '' ) .
' }
.wppa-title { ' .
	( wppa_opt( 'fontfamily_title' ) ? 'font-family:' . wppa_opt( 'fontfamily_title' ) . ';' : '' ) .
	( wppa_opt( 'fontsize_title' ) ? 'font-size:' . wppa_opt( 'fontsize_title' ) . 'px;' : '' ) .
	( wppa_opt( 'fontcolor_title' ) ? 'color:' . wppa_opt( 'fontcolor_title' ) . ';' : '' ) .
	( wppa_opt( 'fontweight_title' ) ? 'font-weight:' . wppa_opt( 'fontweight_title' ) . ';' : '' ) .
' }
.wppa-fulldesc { ' .
	( wppa_opt( 'fontfamily_fulldesc' ) ? 'font-family:' . wppa_opt( 'fontfamily_fulldesc' ) . ';' : '' ) .
	( wppa_opt( 'fontsize_fulldesc' ) ? 'font-size:' . wppa_opt( 'fontsize_fulldesc' ) . 'px;' : '' ) .
	( wppa_opt( 'fontcolor_fulldesc' ) ? 'color:' . wppa_opt( 'fontcolor_fulldesc' ) . ';' : '' ) .
	( wppa_opt( 'fontweight_fulldesc' ) ? 'font-weight:' . wppa_opt( 'fontweight_fulldesc' ) . ';' : '' ) .
' }
.wppa-fulltitle { ' .
	( wppa_opt( 'fontfamily_fulltitle' ) ? 'font-family:' . wppa_opt( 'fontfamily_fulltitle' ) . ';' : '' ) .
	( wppa_opt( 'fontsize_fulltitle' ) ? 'font-size:' . wppa_opt( 'fontsize_fulltitle' ) . 'px;' : '' ) .
	( wppa_opt( 'fontcolor_fulltitle' ) ? 'color:' . wppa_opt( 'fontcolor_fulltitle' ) . ';' : '' ) .
	( wppa_opt( 'fontweight_fulltitle' ) ? 'font-weight:' . wppa_opt( 'fontweight_fulltitle' ) . ';' : '' ) .
' }';

	// Add miscellaneous styles
	if ( ! wppa_switch( 'show_pname' ) ) {
		$the_css .= '
.bc-pname { display:none; }';
	}

	return $the_css;

}

// get full img style
function wppa_get_fullimgstyle( $id ) {

	$temp = wppa_get_fullimgstyle_a( $id );

	if ( is_array( $temp ) ) {
		return $temp['style'];
	}
	else {
		return '';
	}
}

// get full img style - array output
function wppa_get_fullimgstyle_a( $id ) {

	if ( ! is_numeric( wppa( 'fullsize' ) ) || wppa( 'fullsize' ) <= '1' ) {
		wppa( 'fullsize', wppa_opt( 'fullsize' ) );
	}

	wppa( 'enlarge', wppa_switch( 'enlarge' ) );

	return wppa_get_imgstyle_a( $id, wppa_get_photo_path( $id ), wppa( 'fullsize' ), 'optional', 'fullsize' );
}

// Image style array output
function wppa_get_imgstyle_a( $id, $file, $xmax_size, $xvalign = '', $type = '' ) {

	$result = Array(
					'style' 		=> '',
					'width' 		=> '',
					'height' 		=> '',
					'cursor' 		=> '',
					'margin-top' 	=> '',
					'margin-bottom' => ''
					 );	// Init

	wppa_cache_photo( $id );

	if ( ! $id ) return $result;						// no image: no dimensions
	if ( $file == '' ) return $result;					// no image: no dimensions

	if ( strpos( $file, '/wppa/thumbs/' ) ) {
		$image_attr = wppa_get_imagexy( $id, 'thumb' );
	}
	else {
		$image_attr = wppa_get_imagexy( $id, 'photo' );
	}

	if (
			! $image_attr ||
			! isset( $image_attr['0'] ) ||
			! $image_attr['0'] ||
			! isset( $image_attr['1'] ) ||
			! $image_attr['1'] ) {

		// File is corrupt
		wppa_log( 'War', 'Please check file ' . $file . ' it is corrupted.' );
		return $result;
	}

	// Adjust for 'border'
	if ( $type == 'fullsize' && ! wppa_in_widget() ) {
		switch ( wppa_opt( 'fullimage_border_width' ) ) {
			case '':
				$max_size = $xmax_size;
				break;
			case '0':
				$max_size = $xmax_size - '2';
				break;
			default:
				$max_size = $xmax_size - '2' - 2 * wppa_opt( 'fullimage_border_width' );
			}
	}
	else $max_size = $xmax_size;

	$ratioref = wppa_opt( 'maxheight' ) / wppa_opt( 'fullsize' );
	$max_height = round( $max_size * $ratioref );

	if ( $type == 'fullsize' ) {
		if ( wppa( 'portrait_only' ) ) {
			$width = $max_size;
			$height = round( $width * $image_attr[1] / $image_attr[0] );
		}
		else {
			if ( wppa_is_wider( $image_attr[0], $image_attr[1] ) ) {
				$width = $max_size;
				$height = round( $width * $image_attr[1] / $image_attr[0] );
			}
			else {
				$height = round( $ratioref * $max_size );
				$width = round( $height * $image_attr[0] / $image_attr[1] );
			}
			if ( $image_attr[0] < $width && $image_attr[1] < $height ) {
				if ( ! wppa( 'enlarge' ) ) {
					$width = $image_attr[0];
					$height = $image_attr[1];
				}
			}
		}
	}
	else {
		if ( $type == 'cover' &&
			wppa_switch( 'coversize_is_height' ) &&
			( wppa_opt( 'coverphoto_pos') == 'top' || wppa_opt( 'coverphoto_pos') == 'bottom' )
			 ) {
				$height = $max_size;
				$width = round( $max_size * $image_attr[0] / $image_attr[1] );
		}
		else {
			if ( wppa_is_landscape( $image_attr ) ) {
				$width = $max_size;
				$height = round( $max_size * $image_attr[1] / $image_attr[0] );
			}
			else {
				$height = $max_size;
				$width = round( $max_size * $image_attr[0] / $image_attr[1] );
			}
		}
	}

	switch ( $type ) {
		case 'cover':
			if ( wppa_opt( 'bcolor_img' ) != '' ) { 		// There is a border color given
				$result['style'] .= 'border:1px solid ' . wppa_opt( 'bcolor_img' ) . ';';
			}
			else {											// No border color: no border
				$result['style'] .= 'border-width:0px;';
			}
			if ( wppa_switch( 'coverphoto_responsive' ) ) {

				// Landscape
				if ( $width >= $height ) {
					$result['style'] .= 'max-width:100%;';
				}
				else {
					$result['style'] .= 'max-width:'.(100*$width/$height).'%;';
				}
			}
			else {
				$result['style'] .= 'width:' . $width . 'px;height:' . $height . 'px;';
			}
			if ( wppa_switch( 'use_cover_opacity' ) && ! is_feed() ) {
				$opac = wppa_opt( 'cover_opacity' );
				$result['style'] .= 'opacity:' . $opac/100 . ';';
			}
			if ( wppa_opt( 'coverimg_linktype' ) == 'lightbox' ) {
				$result['cursor'] =
					'cursor:' . wppa_wait() . ';';
			}

			$result['style'] .= 'display:inline;';
			break;

		case 'thumb':		// Normal
		case 'ttthumb':		// Topten
		case 'comthumb':	// Comment widget
		case 'fthumb':		// Filmthumb
		case 'twthumb':		// Thumbnail widget
		case 'ltthumb':		// Lasten widget
		case 'albthumb':	// Album widget
			if ( $type == 'thumb' && wppa_get( 'hilite' ) && wppa_get( 'hilite' ) == $id ) {
				$result['style'] .= 'border:3px solid orange;box-sizing:border-box;';
			}
			else {
				$result['style'] .= 'border-width:0px;';
			}
			$result['style'] .= 'width:' . $width . 'px;height:' . $height . 'px;';
			if ( $xvalign == 'optional' ) $valign = wppa_opt( 'valign' );
			else $valign = $xvalign;
			if ( $valign != 'default' ) {	// Center horizontally
				$delta = floor( ( $max_size - $width ) / 2 );
				if ( is_numeric( $valign ) ) $delta += $valign;
				if ( $delta < '0' ) {
					$delta = '0';
				}
				if ( $delta > '0' ) {
					$result['style'] .= 'margin-left:' . $delta . 'px;margin-right:' . $delta . 'px;';
				}
			}

			switch ( $valign ) {
				case 'top':
					$delta = $max_size - $height;
					if ( $delta < '0' ) $delta = '0';
					$result['style'] .= 'margin-bottom: ' . $delta . 'px;';
					$result['margin-bottom'] = $delta;
					break;
				case 'center':
					$delta = round( ( $max_size - $height ) / 2 );
					if ( $delta < '0' ) $delta = '0';
					$result['style'] .= 'margin-top: ' . $delta . 'px;margin-bottom:' . $delta . 'px;';
					$result['margin-top'] = $delta;
					$result['margin-bottom'] = $delta;
					break;
				case 'bottom':
					$delta = $max_size - $height;
					if ( $delta < '0' ) $delta = '0';
					$result['style'] .= 'margin-top: ' . $delta . 'px;';
					$result['margin-top'] = $delta;
					break;
				default:
					if ( is_numeric( $valign ) ) {
						$delta = $valign;
						$result['style'] .= 'margin-top: ' . $delta . 'px;';
						$result['style'] .= 'margin-bottom: ' . $delta . 'px;';
						$result['margin-top'] = $delta;
						$result['margin-bottom'] = $delta;
					}
			}
			if ( wppa_switch( 'use_thumb_opacity' ) && ! is_feed() ) {
				$opac = wppa_opt( 'thumb_opacity' );
				$result['style'] .=
					'opacity:' . $opac/100 . ';';
			}

			// Cursor
			switch ( $type ) {
				case 'thumb':		// Normal
					$linktyp = wppa_opt( 'thumb_linktype' );
					break;
				case 'ttthumb':		// Topten	v
					$linktyp = wppa_opt( 'topten_widget_linktype' );
					break;
				case 'comthumb':	// Comment widget	v
					$linktyp = wppa_opt( 'comment_widget_linktype' );
					break;
				case 'fthumb':		// Filmthumb
					$linktyp = wppa_opt( 'film_linktype' );
					break;
				case 'twthumb':		// Thumbnail widget	v
					$linktyp = wppa_opt( 'thumbnail_widget_linktype' );
					break;
				case 'ltthumb':		// Lasten widget	v
					$linktyp = wppa_opt( 'lasten_widget_linktype' );
					break;
				case 'albthumb':	// Album widget
					$linktyp = wppa_opt( 'album_widget_linktype' );
					break;
				default:
					$linktyp = '';
					break;
			}
			if ( $linktyp == 'none' ) {
				$result['cursor'] = 'cursor:default;';
			}
			elseif ( $linktyp == 'lightbox' ) {
//				$result['cursor'] = 'cursor:wait;';
			}
			else {
				$result['cursor'] = 'cursor:pointer;';
			}

			break;
		case 'fullsize':
			if ( wppa( 'auto_colwidth' ) ) {

				// These sizes fit within the rectangle define by Slideshow -> I -> Items 1 and 2
				// times 2 for responsive themes,
				// and are supplied for ver 4 browsers as they have undefined natural sizes.
				$result['style'] .= 'max-width:' . ( $width * 2 ) . 'px;';
				$result['style'] .= 'max-height:' . ( $height * 2 ) . 'px;';
			}
			else {

				// These sizes fit within the rectangle define by Slideshow -> I -> Items 1 and 2
				// and are supplied for ver 4 browsers as they have undefined natural sizes.
				$result['style'] .= 'max-width:' . $width . 'px;';
				$result['style'] .= 'max-height:' . $height . 'px;';

				$result['style'] .= 'width:' . $width . 'px;';
				$result['style'] .= 'height:' . $height . 'px;';
			}

			if ( wppa( 'is_slideonly' ) == '1' ) {
				if ( wppa( 'ss_widget_valign' ) != '' ) $valign = wppa( 'ss_widget_valign' );
				else $valign = 'fit';
			}
			elseif ( $xvalign == 'optional' ) {
				$valign = wppa_opt( 'fullvalign' );
			}
			else {
				$valign = $xvalign;
			}

			// Margin
			if ( $valign != 'default' ) {
				$m_left 	= '0';
				$m_right 	= '0';
				$m_top 		= '0';
				$m_bottom 	= '0';

				// Center horizontally
				$delta = round( ( $max_size - $width ) / 2 );
				if ( $delta < '0' ) $delta = '0';
				if ( wppa( 'auto_colwidth' ) ) {
					$m_left 	= 'auto';
					$m_right 	= 'auto';
				}
				else {
					$m_left 	= $delta;
					$m_right 	= '0';
				}

				// Position vertically
				if ( wppa_in_widget() == 'ss' && wppa( 'in_widget_frame_height' ) > '0' ) {
					$max_height = wppa( 'in_widget_frame_height' );
				}
				$delta = '0';
				if ( ! wppa( 'auto_colwidth' ) && ! wppa_page( 'oneofone' ) ) {
					switch ( $valign ) {
						case 'center':
							$delta = round( ( $max_height - $height ) / 2 );
							if ( $delta < '0' ) $delta = '0';
							break;
						case 'bottom':
							$delta = $max_height - $height;
							if ( $delta < '0' ) $delta = '0';
							break;
						default: //	case 'top': case 'fit':
							$delta = '0';
							break;
					}
				}
				$m_top = $delta;

				$result['style'] .= wppa_combine_style( 'margin', $m_top, $m_left, $m_right, $m_bottom );
			}

			// Border and padding
			if ( ! wppa_in_widget() ) switch ( wppa_opt( 'fullimage_border_width' ) ) {
				case '':
					break;
				case '0':
					$result['style'] .= 'border:1px solid ' . wppa_opt( 'bcolor_fullimg' ) . ';';
					break;
				default:
					$result['style'] .= 'border: 1px solid ' . wppa_opt( 'bcolor_fullimg' ) . ';';
					$result['style'] .= 'background-color:' . wppa_opt( 'bgcolor_fullimg' ) . ';';
					$result['style'] .= 'padding:' . wppa_opt( 'fullimage_border_width' ) . 'px;';

					// If we do round corners...
					if ( wppa_opt( 'bradius' ) > '0' ) {	// then also here
						$result['style'] .= 'border-radius:' .
							wppa_opt( 'fullimage_border_width' ) . 'px;';
					}
			}

			break;

		default:
			wppa_out( 'Error wrong "$type" argument: ' . $type . ' in wppa_get_imgstyle_a' );
	}
	$result['width'] = $width;
	$result['height'] = $height;
//	$result['style'] = rtrim( $result['style'], ';' );
	return $result;
}


function wppa_get_text_medal_color_style( $type, $bw ='1' ) {

	$darks = array(
					'red' 		=> '#BB0000',
					'orange' 	=> '#BB8400',
					'yellow' 	=> '#BBBB00',
					'green' 	=> '#00BB00',
					'blue' 		=> '#0000BB',
					'purple' 	=> '#800080',
					'black'		=> '#333333',
				);
	$lites = array(
					'red' 		=> '#FF7777',
					'orange' 	=> '#FFCC77',
					'yellow' 	=> '#FFFF00',
					'green' 	=> '#77FF77',
					'blue' 		=> '#7777FF',
					'purple' 	=> '#FF00FF',
					'black' 	=> '#999999',
				);

	$dark = $darks[ wppa_opt( $type.'_label_color' ) ];
	$lite = $lites[ wppa_opt( $type.'_label_color' ) ];

	$result =	'background-color:' . $dark . ';' .
				'background:linear-gradient(' . $dark . ', ' . $lite . ');' .
				'border-color:' . $dark . ';' .
				'box-shadow:'.$bw.'px '.$bw.'px '.$bw.'px ' . $dark . ';' .
				'color:#FFFFFF;';

	return $result;
}
