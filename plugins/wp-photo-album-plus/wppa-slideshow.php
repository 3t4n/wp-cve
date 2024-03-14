<?php
/* wppa-slideshow.php
* Package: wp-photo-album-plus
*
* Contains all the slideshow high level functions
*
* Version 8.6.01.002
*
*/

if ( ! defined( 'ABSPATH' ) ) die( "Can't load this file directly" );

function wppa_the_slideshow( $thumbs ) {

	$thumbs = wppa_prepare_slideshow_pagination( $thumbs );

	if ( wppa_opt( 'pagelink_pos' ) == 'top' || wppa_opt( 'pagelink_pos' ) == 'both' ) wppa_slide_page_links();

	if ( wppa_switch( 'split_namedesc' ) ) {
		$indexes = explode( ',', wppa_opt( 'slide_order_split' ) );
		$i = '0';
		while ( $i < '12' ) {
			switch ( $indexes[$i] ) {
				case '0':
					wppa_start_stop('optional');			// The 'Slower | start/stop | Faster' bar
					break;
				case '1':
					wppa_slide_frame( $thumbs );			// The photo / slide
					break;
				case '2':
					wppa_slide_name_box('optional');		// Show name in a box.
					break;
				case '3':
					wppa_slide_desc_box('optional');		// Show description in a box.
					break;
				case '4':
					wppa_slide_custom('optional');			// Custom box
					break;
				case '5':
					wppa_slide_rating('optional');			// Rating box
					break;
				case '6':
					wppa_slide_filmstrip( $thumbs );		// Show Filmstrip
					break;
				case '7':
					wppa_browsebar('optional');				// The 'Previous photo | Photo n of m | Next photo' bar
					break;
				case '8':
					wppa_comments('optional');				// The Comments box
					break;
				case '9':
					wppa_iptc('optional');					// The IPTC box
					break;
				case '10':
					wppa_exif('optional');					// The EXIF box
					break;
				case '11':
					wppa_share('optional');					// The Share box
					break;
				default:
					break;
			}
			$i++;
		}
	}
	else {
		$indexes = explode( ',', wppa_opt( 'slide_order' ) );
		$i = '0';
		while ( $i < '11' ) {
			switch ( $indexes[$i] ) {
				case '0':
					wppa_start_stop('optional');			// The 'Slower | start/stop | Faster' bar
					break;
				case '1':
					wppa_slide_frame( $thumbs );			// The photo / slide
					break;
				case '2':
					wppa_slide_name_desc('optional');		// Show name and description in a box.
					break;
				case '3':
					wppa_slide_custom('optional');			// Custom box
					break;
				case '4':
					wppa_slide_rating('optional');			// Rating box
					break;
				case '5':
					wppa_slide_filmstrip( $thumbs );		// Show Filmstrip
					break;
				case '6':
					wppa_browsebar('optional');				// The 'Previous photo | Photo n of m | Next photo' bar
					break;
				case '7':
					wppa_comments('optional');				// The Comments box
					break;
				case '8':
					wppa_iptc('optional');					// The IPTC box
					break;
				case '9':
					wppa_exif('optional');					// The EXIF box
					break;
				case '10':
					wppa_share('optional');					// The Share box
					break;
				default:
					break;
			}
			$i++;
		}
	}
	if ( wppa_opt( 'pagelink_pos' ) == 'bottom' || wppa_opt( 'pagelink_pos' ) == 'both' ) wppa_slide_page_links();

	wppa_run_slidecontainer( $thumbs );	// Fill in the photo array and display it.
}

// Prepares the pagination and returns the thumbs for the current page
function wppa_prepare_slideshow_pagination( $thumbs ) {
global $thumbs_ids;
global $previous_page_last_id;

	// Init: no pagination
	wppa( 'ss_pag', false );

	// Any items?
	if ( ! $thumbs ) return $thumbs;

	// Not on search
	if ( wppa( 'src' ) ) return $thumbs;

	$mocc = wppa( 'mocc' );

	// If not numeric startphoto, convert it
	if ( wppa( 'start_photo' ) && ! wppa_is_int( wppa( 'start_photo' ) ) ) {
		global $wpdb;
		$s = wppa( 'start_photo' );
		$id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $wpdb->wppa_photos WHERE sname = %s OR crypt = %s LIMIT 1", $s, $s ) );
		wppa( 'start_photo', $id );
	}

	// Not on photo enumeration
	if ( wppa( 'start_photos' ) ) return $thumbs;

	// Save thumb ids of full selection
	$thumbs_ids = array();
	if ( $thumbs ) foreach ( $thumbs as $t ) {
		$thumbs_ids[] = $t['id'];
	}

	// See if slideonly max is appliccable
	if ( wppa( 'is_slideonly' ) || wppa( 'is_slideonlyf' ) ) {
		if ( wppa_opt( 'slideonly_max' ) ) {
			$pagsiz = wppa_opt( 'slideonly_max' );
		}
		else {
			return $thumbs;
		}
	}

	// Not slideonly
	else {

		// Page size defined?
		if ( ! wppa_opt( 'slideshow_pagesize' ) ) return $thumbs;

		// Not in a widget!
		if ( wppa_in_widget() ) return $thumbs;

		// Fits in one page?
		$pagsiz = wppa_opt( 'slideshow_pagesize' );
		if ( count( $thumbs ) <= $pagsiz ) return $thumbs;
	}

	// Pagination on and required
	wppa( 'ss_pag', true );
	$nslides = count( $thumbs );
	wppa( 'npages', ceil( $nslides / $pagsiz ) );

	// Assume page = 1
	wppa( 'curpage', '1' );

	// If a page is requested, find it
	$pagreq = wppa_get( 'paged', '0' );
	if ( $pagreq ) {
		wppa( 'curpage', $pagreq );
	}

	// If a photo requested, find the page where its on
	elseif ( wppa( 'start_photo' ) ) {
		foreach ( array_keys( $thumbs ) as $key ) {
			if ( $thumbs[$key]['id'] == wppa( 'start_photo' ) ) {
				wppa( 'curpage', floor( $key / $pagsiz ) + '1' );
			}
		}
	}

	// Extract the part of the thumbs for the current page
	$offset = ( wppa( 'curpage' ) - '1' ) * wppa_opt( 'slideshow_pagesize' );
	wppa_js( 'wppaSlideOffset[' . $mocc . '] = ' . $offset . ';' );

	$skips = ( wppa( 'curpage' ) - '1' ) * $pagsiz;

	$thumbs = array_slice( $thumbs, $skips, $pagsiz );

	$previous_page_last_id = '0';
	if ( $skips && isset( $thumbs_ids[$skips - 1] ) ) {
		$previous_page_last_id = $thumbs_ids[$skips - 1];
	}
	else {
		$previous_page_last_id = $thumbs_ids[count($thumbs_ids) - 1];
	}

	return $thumbs;
}

function wppa_slide_page_links() {

	if ( ! wppa( 'ss_pag' ) ) return;	// No pagination
	if ( wppa( 'is_slideonly' ) || wppa( 'is_slideonlyf' ) ) return; // Not on slideonly

	wppa_page_links( wppa( 'npages' ), wppa( 'curpage' ), true );

}

function wppa_get_navigation_type() {
	switch( wppa_opt( 'navigation_type' ) ) {
		case 'icons':
			return 'icons';
			break;
		case 'iconsmobile':
			if ( wppa_is_mobile() ) {
				return 'icons';
			}
			else {
				return 'text';
			}
		case 'text':
			return 'text';
		default:
			return 'icons';
	}
}
function wppa_start_stop( $opt = '' ) {
	if ( wppa_get_navigation_type() == 'icons' ) {
		wppa_start_stop_icons( $opt );
	}
	else {
		wppa_start_stop_text( $opt );
	}
}
function wppa_start_stop_icons( $opt = '' ) {

	if ( is_feed() ) return;	// Not in a feed

	// A single image slideshow needs no navigation
	if ( wppa( 'is_single' ) ) return;
	if ( wppa( 'is_filmonly' ) && ! wppa_switch( 'show_startstop_filmonly' ) ) return;
	if ( wppa( 'is_filmonly' ) && wppa_switch( 'show_startstop_filmonly' ) ) {
		$filmonlynav = true;
	}
	else {
		$filmonlynav = false;
	}
	$Filmonlynavcontin = $filmonlynav && wppa_switch ( 'filmonly_continuous' );

	$show = false;
	if ( $opt != 'optional' ) $show = true;
	if ( wppa_switch( 'show_startstop_navigation' ) && ! wppa( 'is_slideonly' ) ) $show = true;
	if ( $filmonlynav ) $show = true;

	if ( ! $show ) {
		return;
	}

	$iconsize = wppa_icon_size( '1.5em;' );
	$mocc = wppa( 'mocc' );

	$result = '
	<div
		id="prevnext1-' . $mocc . '"
		class="wppa-box wppa-nav wppa-nav-text"
		style="text-align:center;line-height:0;"
		>
		<span
			id="speed0-' . $mocc . '"
			class="wppa-nav-text speed0"
			style="display:inline-block;"
			title="' . __( 'Slower', 'wp-photo-album-plus' ) . '"' .
			( $Filmonlynavcontin ?
				' onclick="jQuery(document).ready(function(){wppaAnimationSpeed*=1.25;})";' :
				' onclick="wppaSpeed(' . $mocc . ', false); return false;"'
			) . '
			>' .
			wppa_get_svghtml( 'Snail', $iconsize ) . '
		</span>&nbsp;
		<span
			id="startstop-'.$mocc.'"
			class="wppa-nav-text startstop"
			style="display:inline-block;"
			title="' . __( 'Start / stop slideshow', 'wp-photo-album-plus' ) . '"
			onclick="wppaStartStop(' . $mocc . ', -1); return false;"
			>' .
			wppa_get_svghtml( 'Play-Button', $iconsize ) . '
		</span>&nbsp;
		<span
			id="speed1-' . $mocc . '"
			class="wppa-nav-text speed1"
			style="display:inline-block;"
			title="' . __( 'Faster', 'wp-photo-album-plus' ) . '"' .
			( $Filmonlynavcontin ?
				' onclick="jQuery(document).ready(function(){wppaAnimationSpeed*=0.8;})";' :
				' onclick="wppaSpeed(' . $mocc . ', true); return false;"'
			) . '
			>' .
			wppa_get_svghtml( 'Eagle-1', $iconsize ) . '
		</span>';

		// Renew link on slidonly?
		if ( wppa( 'is_filmonly' ) && wppa_switch( 'show_renew_filmonly' ) ) {
			$ajax_url = wppa_get_ajaxlink();
			$result .= '
			<span
				id="renew-' . $mocc . '"
				class="wppa-nav-text renew"
				style="float:right;cursor:pointer"
				title="' . esc_attr( __('Renew', 'wp-photo-album-plus' ) ) . '"
				onclick="_wppaStop(' . $mocc . ');wppaDoAjaxRender(' . $mocc . ', \'' . $ajax_url .
							'wppa-slideonly=1&amp;wppa-filmonly=1&amp;wppa-album=' . wppa( 'start_album' ) . '&amp;wppa-occur=' . $mocc . '\', \'\' )"
				>' .
				wppa_get_svghtml( 'Redo', $iconsize ) . '
			</span>';
		}

	$result .= '</div>';

	wppa_out( $result );
}

function wppa_start_stop_text( $opt = '' ) {

	if ( is_feed() ) return;	// Not in a feed

	// A single image slideshow needs no navigation
	if ( wppa( 'is_single' ) ) return;
	if ( wppa( 'is_filmonly' ) && ! wppa_switch( 'show_startstop_filmonly' ) ) return;
	if ( wppa( 'is_filmonly' ) && wppa_switch( 'show_startstop_filmonly' ) ) {
		$filmonlynav = true;
	}
	else {
		$filmonlynav = false;
	}
	$Filmonlynavcontin = $filmonlynav && wppa_switch ( 'filmonly_continuous' );

	$show = false;
	if ( $opt != 'optional' ) $show = true;
	if ( wppa_switch( 'show_startstop_navigation' ) && ! wppa( 'is_slideonly' ) ) $show = true;
	if ( $filmonlynav ) $show = true;

	if ( ! $show ) {
		return;
	}

	$mocc = wppa( 'mocc' );

	$result = '
	<div
		id="prevnext1-' . $mocc . '"
		class="wppa-box wppa-nav wppa-nav-text"
		style="text-align:center;"
		>
		<a
			id="speed0-' . $mocc . '"
			class="wppa-nav-text speed0"' .
			( $Filmonlynavcontin ?
				' onclick="jQuery(document).ready(function(){wppaAnimationSpeed*=1.25;})";' :
				' onclick="wppaSpeed(' . $mocc . ', false); return false;"'
			) . '
			>' .
			__( 'Slower', 'wp-photo-album-plus' ) . '
		</a>
		|
		<a
			id="startstop-' . $mocc . '"
			class="wppa-nav-text startstop"
			onclick="wppaStartStop(' . $mocc . ', -1); return false;">' .
			__( 'Start', 'wp-photo-album-plus' ) . '
		</a>
		|
		<a
			id="speed1-' . $mocc . '"
			class="wppa-nav-text speed1"' .
			( $Filmonlynavcontin ?
				' onclick="jQuery(document).ready(function(){wppaAnimationSpeed*=0.8;})";' :
				' onclick="wppaSpeed('.$mocc.', true); return false;"'
			) . '
			>' .
			__( 'Faster', 'wp-photo-album-plus' ) . '
		</a>';

		// Renew link on slidonly?
		if ( wppa( 'is_filmonly' ) && wppa_switch( 'show_renew_filmonly' ) ) {
			$ajax_url = wppa_get_ajaxlink();
			$result .= '
			<a
				id="renew-' . $mocc . '"
				class="wppa-nav-text renew"
				style="float:right;cursor:pointer"
				onclick="_wppaStop(' . $mocc . ');wppaDoAjaxRender(' . $mocc . ', \'' . $ajax_url .
							'wppa-slideonly=1&amp;wppa-filmonly=1&amp;wppa-album=' . wppa( 'start_album' ) . '&amp;wppa-occur=' . $mocc . '\', \'\' )"
				>' .
				__( 'Renew', 'wp-photo-album-plus' ) . '
			</a>';
		}

	$result .= '</div>';

	wppa_out( $result );
}

function wppa_slide_frame( $thumbs ) {

	if ( is_feed() ) return;
	if ( wppa( 'is_filmonly' ) ) return;

	$mocc = wppa( 'mocc' );

	if ( wppa_switch( 'slide_pause' ) ) {
		$pause = 	' onmouseover="wppaSlidePause[' . $mocc . '] = \'' . __( 'Paused', 'wp-photo-album-plus' ) . '\'"' .
					' onmouseout="wppaSlidePause[' . $mocc . '] = false"' .
					' ontouchstart="wppaSlidePause[' . $mocc . '] = \'' . __( 'Paused', 'wp-photo-album-plus' ) . '\'"' .
					' ontouchend="wppaSlidePause[' . $mocc . '] = false"';
	}
	else $pause = '';

	// There are still users who turn off javascript...
	wppa_out( '
	<noscript style="text-align:center">
		<span style="color:red">' .
			__( 'To see the full size images, you need to enable javascript in your browser.', 'wp-photo-album-plus' ) . '
		</span>
	</noscript>' );

	$mcw = wppa_get_container_width();
	$icw = wppa_opt( 'initial_colwidth' );
	$wwd = wppa_opt( 'widget_width' );
	if ( $mcw < 1 ) $mcw *= $icw;
	$height = wppa( 'in_widget' ) ? $wwd : ( ( wppa_opt( 'maxheight' ) / wppa_opt( 'fullsize' ) ) * $mcw );
//	wppa_log('dbg', "Initial height for $mocc is $height, width = $mcw");

	$style = 'overflow:hidden;height:' . $height . 'px;margin-bottom:' . wppa_opt( 'box_spacing' ) . 'px;';
	switch ( wppa( 'align' ) ) {
		case 'left':
			$style .= 'margin-right:auto;';
			break;
		case 'right':
			$style .= 'margin-left:auto;';
			break;
//		case 'center':
		default:
			$style .= 'margin-right:auto;margin-left:auto;';
			break;
	}

	wppa_out( '
	<div
		id="slide_frame-' . $mocc . '"' .
		$pause . '
		class="slide-frame"
		style="' . $style . '"
		>' );

	$auto = wppa( 'auto_colwidth' );

	wppa_out( '
	<div
		id="theslide0-' . $mocc . '"
		class="theslide theslide-' . $mocc . '"
		style="' .
			( $auto ? 'width:100%;' : 'width:' . wppa( 'slideframewidth' )  . 'px;' ) . '
			margin:auto;"
		>
	</div>
	<div
		id="theslide1-' . $mocc . '"
		class="theslide theslide-' . $mocc . '"
		style="' .
			( $auto ? 'width:100%;' : 'width:' . wppa( 'slideframewidth' )  . 'px;' ) . '
			margin:auto;"
		>
	</div>' );

	// The Spinner image
	wppa_out( wppa_get_spinner_svg_html( array( 	'id' 		=> 'wppa-slide-spin-' . wppa( 'mocc' ),
													'position' 	=> 'absolute',
													'display' 	=> 'inline',
													'z-index' 	=> '99',
													'size' 		=> ( wppa( 'in_widget' ) ? '60' : '120' ),
													) ) );

	if ( ! wppa_page( 'oneofone' ) ) {

		// Big browsing buttons enabled ?
		if ( ( wppa_switch( 'show_bbb' ) && ! wppa_in_widget() ) ||
			 ( wppa_switch( 'show_bbb_widget' ) && wppa_in_widget() ) ) {

			wppa_out( '
			<img
				id="bbb-' . $mocc . '-l"
				class="wppa-bbb wppa-bbb-l bbb-' . $mocc . '"
				src="' . wppa_get_imgdir() . 'bbbl.png"
				alt="bbbl"
				onmouseover="wppaBbb(' . $mocc . ',\'l\',\'show\')"
				onmouseout="wppaBbb(' . $mocc . ',\'l\',\'hide\')"
				onclick="wppaBbb(' . $mocc . ',\'l\',\'click\')"
			/>
			<img
				id="bbb-' . $mocc . '-r"
				class="wppa-bbb wppa-bbb-r bbb-' . $mocc . '"
				src="' . wppa_get_imgdir() . 'bbbr.png"
				alt="bbbr"
				onmouseover="wppaBbb(' . $mocc . ',\'r\',\'show\')"
				onmouseout="wppaBbb(' . $mocc . ',\'r\',\'hide\')"
				onclick="wppaBbb(' . $mocc . ',\'r\',\'click\')"
			/>' );
		}

		// Ugly browse buttons ?
		if ( ( wppa_switch( 'show_ubb' ) && ! wppa_in_widget() ) ||
			 ( wppa_switch( 'show_ubb_widget' ) && wppa_in_widget() ) ) {

			$iconsize = wppa_icon_size( '48px;', 1 );
			$margin = wppa_icon_size( '48px;', 1, 0.5 );
			wppa_out( 	'
			<div
				id="ubb-l-' . $mocc . '"
				class="wppa-ubb ubb ubb-l ubb-' . $mocc . '"
				style="
					margin-top:-' . $margin . '
					left:0;
					width:' . $iconsize . ';"
				onmouseover="wppaUbb(' . $mocc . ',\'l\',\'show\')"
				ontouchstart="wppaUbb(' . $mocc . ',\'l\',\'show\')"
				onmouseout="wppaUbb(' . $mocc . ',\'l\',\'hide\')"
				ontouchend="wppaUbb(' . $mocc . ',\'l\',\'hide\')"
				onclick="wppaUbb(' . $mocc . ',\'l\',\'click\')"
				>' .
				wppa_get_svghtml( 'Prev-Button', $iconsize, false, true ) . '
			</div>
			<div
				id="ubb-r-' . $mocc . '"
				class="wppa-ubb ubb ubb-r ubb-' . $mocc . '"
				style="
					margin-top:-' . $margin . '
					right:0;
					width:' . $iconsize . ';"
				onmouseover="wppaUbb(' . $mocc . ',\'r\',\'show\')"
				ontouchstart="wppaUbb(' . $mocc . ',\'r\',\'show\')"
				onmouseout="wppaUbb(' . $mocc . ',\'r\',\'hide\')"
				ontouchend="wppaUbb(' . $mocc . ',\'r\',\'hide\')"
				onclick="wppaUbb(' . $mocc . ',\'r\',\'click\')"
				>' .
				wppa_get_svghtml( 'Next-Button', $iconsize, false, true ) . '
			</div>' );
		}
	}

	wppa_startstop_icons();
	wppa_numberbar( $thumbs );

	wppa_out( '</div>' );
}

function wppa_slide_name_desc( $key = 'optional' ) {

	$do_it = false;
	if ( $key != 'optional' ) $do_it = true;
	if ( wppa( 'is_slideonly' ) ) {
		if ( wppa( 'name_on') ) $do_it = true;
		if ( wppa( 'desc_on') ) $do_it = true;
	}
	else {
		if ( wppa_is_item_displayable( wppa( 'start_album' ), 'description', 'show_full_desc' ) ) $do_it = true;
		if ( wppa_is_item_displayable( wppa( 'start_album' ), 'name', 'show_full_name' ) || wppa_switch( 'show_full_owner') ) $do_it = true;
	}
	if ( $do_it ) {
		wppa_out( 	'
		<div
			id="namedesc-' . wppa( 'mocc' ) . '"
			class="wppa-box wppa-name-desc"
			>' );

			if ( wppa_switch( 'swap_namedesc') ) {
				wppa_slide_name($key);			// The name of the photo
				wppa_slide_description($key);		// The description of the photo
			}
			else {
				wppa_slide_description($key);		// The description of the photo
				wppa_slide_name($key);			// The name of the photo
			}

		wppa_out( '
		</div>' );
	}
}

function wppa_slide_name_box( $key = 'optional' ) {

	$do_it = false;
	if ( $key != 'optional' ) $do_it = true;
	if ( wppa( 'is_slideonly' ) ) {
		if ( wppa( 'name_on' ) ) $do_it = true;
	}
	else {
		if ( wppa_is_item_displayable( wppa( 'start_album' ), 'name', 'show_full_name' ) || wppa_switch( 'show_full_owner' ) ) $do_it = true;
	}
	if ( $do_it ) {
		wppa_out( '
		<div
			id="namebox-' . wppa( 'mocc' ) . '"
			class="wppa-box wppa-name-desc"
			>' );
			wppa_slide_name( $key );			// The name of the photo
		wppa_out( '
		</div>' );
	}
}

function wppa_slide_desc_box( $key = 'optional' ) {

	$do_it = false;
	if ( $key != 'optional' ) $do_it = true;
	if ( wppa( 'is_slideonly' ) ) {
		if ( wppa( 'desc_on' ) ) $do_it = true;
	}
	else {
		if ( wppa_is_item_displayable( wppa( 'start_album' ), 'description', 'show_full_desc', 'slide_desc_box' ) ) $do_it = true;
	}

	if ( $do_it ) {
		wppa_out( '
		<div
			id="descbox-' . wppa( 'mocc' ) . '"
			class="wppa-box wppa-name-desc"
			>' );
			wppa_slide_description( $key );		// The description of the photo
		wppa_out( '
		</div>' );
	}
}

function wppa_slide_name( $opt = '' ) {

	if ( wppa( 'is_slideonly' ) ) {
		if ( wppa( 'name_on' ) ) $doit = true;
		else $doit = false;
	}
	else {
		if ( $opt == 'optional' ) {
			if ( wppa_is_item_displayable( wppa( 'start_album' ), 'name', 'show_full_name' ) || wppa_switch( 'show_full_owner' ) ) $doit = true;
			else $doit = false;
		}
		else $doit = true;
	}
	if ( $opt == 'description' ) $doit = false;

	if ( $doit ) {
		wppa_out( '
		<div
			id="imagetitle-' . wppa( 'mocc' ) . '"
			class="wppa-fulltitle imagetitle"
			style="padding:3px;width:100%"
			>
		</div>' );
	}
}

function wppa_slide_description( $opt = '' ) {

	if ( wppa( 'is_slideonly' ) ) {
		if ( wppa( 'desc_on' ) ) $doit = true;
		else $doit = false;
	}
	else {
		if ( $opt == 'optional' ) {
			if ( wppa_is_item_displayable( wppa( 'start_album' ), 'description', 'show_full_desc' ) ) $doit = true;
			else $doit = false;
		}
		else $doit = true;
	}
	if ( $opt == 'name' ) $doit = false;

	if ( $doit ) {
		wppa_out( '
		<div
			id="imagedesc-' . wppa( 'mocc' ) . '"
			class="wppa-fulldesc imagedesc"
			style="
				padding:3px;
				width:100%;
				text-align:' . wppa_opt( 'fulldesc_align' ) . '"
			>
		</div>' );
	}
}

function wppa_slide_custom( $opt = '' ) {

	if ( $opt == 'optional' && ! wppa_switch( 'custom_on' ) ) return;
	if ( wppa( 'is_slideonly' ) ) return;	/* Not when slideonly */
	if ( is_feed() ) return;

	$content = __( stripslashes( wppa_opt( 'custom_content' ) ) );

	// w#albdesc
	if ( wppa_is_int( wppa( 'start_album' ) ) && wppa( 'start_album' ) > '0' ) {
		$content = str_replace( 'w#albdesc', wppa_get_album_desc( wppa( 'start_album' ) ), $content );
	}
	else {
		$content = str_replace( 'w#albdesc', '', $content );
	}

	// w#fotomoto
	$f_on_this = false;
	if ( function_exists( 'fotomoto_page_enabled' ) ) {
		$f_on_this = ! wppa( 'in_widget' ) && fotomoto_page_enabled( wppa_get_the_ID() );
	}
	if ( wppa_switch( 'fotomoto_on' ) && $f_on_this ) {
		$content = str_replace( 'w#fotomoto',
			'<div
				id="wppa-fotomoto-container-' . wppa( 'mocc' ) . '"
				class="wppa-fotomoto-container"
				>
			</div>
			<div
				id="wppa-fotomoto-checkout-' . wppa( 'mocc' ) . '"
				class="wppa-fotomoto-checkout FotomotoToolbarClass"
				style="float:right; clear:none;"
				>
				<ul
					class="FotomotoBar"
					style="list-style:none outside none;"
					>
					<li>
						<a onclick="FOTOMOTO.API.checkout(); return false;">' .
							__('Checkout', 'wp-photo-album-plus' ) . '
						</a>
					</li>
				</ul>
			</div>
			<div style="clear:both;"></div>',
			$content );
	}
	else {
		$content = str_replace( 'w#fotomoto', '', $content );
	}

	wppa_out( 	'
	<div
		id="wppa-custom-' . wppa( 'mocc' ) . '"
		class="wppa-box wppa-custom"
		>' .
		$content . '
	</div>' );
}

function wppa_slide_rating( $opt = '' ) {

	if ( wppa_opt( 'rating_max' ) == '1' ) {
		wppa_slide_rating_vote_only( $opt );
	}
	else {
		wppa_slide_rating_range( $opt );
	}
}

function wppa_slide_rating_vote_only( $opt, $id = '0', $is_lightbox = false ) {

	wppa_out( wppa_get_slide_rating_vote_only( $opt, $id, $is_lightbox ) );
}

function wppa_get_slide_rating_vote_only( $opt, $id = '0', $is_lightbox = false ) {

	if ( ! $is_lightbox ) {
		if ( $opt == 'optional' && ! wppa_is_item_displayable( wppa( 'start_album' ), 'rating', 'rating_on' ) ) return;
		if ( wppa( 'is_slideonly' ) ) return '';	/* Not when slideonly */
		if ( is_feed() ) return '';
	}

	$result = '';

	// Open the voting box
	if ( ! $is_lightbox ) {
		$result .= '
		<div
			id="wppa-rating-' . wppa( 'mocc' ) . '"
			class="wppa-box wppa-nav wppa-nav-text"
			style="text-align:center;"
			>';
	}

	// Likes
	if ( wppa_opt( 'rating_display_type' ) == 'likes' ) {

		// Logged in
		if ( is_user_logged_in() ) {
			$fs = '16';
			$pad = '4';
			if ( $id ) {
				$liketitle 	= wppa_get_like_title_a( $id );
				$my 		= $liketitle['mine'];
				$title 		= $liketitle['title'];
				$display 	= $liketitle['display'];
			}
			else {
				$my 		= '';
				$title 		= '';
				$display 	= '';

			}
			$result .=	'
			<div
				id="wppa-like-imgdiv-' . wppa( 'mocc' ) . '"
				style="display:inline"
				>
				<img' .
					( $is_lightbox ? ' id="wppa-like-0"' : ' id="wppa-like-' . wppa( 'mocc' ) . '"' ) .
					( $my ? ' src="' . wppa_get_imgdir() . 'thumbdown.png"' : ' src="' . wppa_get_imgdir() . 'thumbup.png"' ) .
					( $my ? ' alt="down"' : ' alt="up"' ) . '
					style="height:' . $fs . 'px;margin:0 0 -3px 0;padding:0 ' . $pad . 'px;box-shadow:none;display:inline;"
					class="no-shadow"' .
					( $title ? ' title="' . esc_attr( $title ) . '"' : '' ) . '
					onmouseover="jQuery(this).stop().fadeTo(100, 1.0)"
					onmouseout="jQuery(this).stop().fadeTo(100, wppaStarOpacity)"' .
					( $is_lightbox ? ' onclick="wppaOvlRateIt(\''.wppa_encrypt_photo($id).'\', 1, 0 )"' : ' onclick="wppaRateIt( ' . wppa( 'mocc' ) . ', 1);"' ) . '
					onload="jQuery(this).trigger(\'onmouseout\');"
				>
			</div>';

			if ( wppa_switch( 'show_avg_rating' ) ) {
				$result .= 	'
				<span' .
					( $is_lightbox ? ' id="wppa-liketext-0"' : ' id="wppa-liketext-' . wppa( 'mocc' ) . '"' ) . '
					style="cursor:default;"
					>' .
					$display . '
				</span>';
			}
		}
		else {
			if ( wppa_switch( 'login_links' ) ) {
				$result .= sprintf(__( 'You must <a href="%s">login</a> to vote', 'wp-photo-album-plus' ), wppa_opt( 'login_url' ) );
			}
			else {
				$result .= __( 'You must login to vote', 'wp-photo-album-plus' );
			}
		}
	}

	else {

		// Logged in
		if ( is_user_logged_in() ) {
			$cnt = '0';
			if ( wppa_switch( 'show_avg_rating' ) ) {
				$result .= sprintf( __( 'Number of votes: <span id="wppa-vote-count-%s" >%s</span>&nbsp;', 'wp-photo-album-plus' ), wppa( 'mocc' ), $cnt );
			}
			$result .= 	'
			<input
				id="wppa-vote-button-' . wppa( 'mocc' ) . '"
				class="wppa-vote-button"
				style="margin:0;"
				type="button"
				onclick="wppa' . ( $is_lightbox ? 'Ovl' : '' ) . 'RateIt(' . wppa( 'mocc' ) . ', 1)"
				value="' . wppa_opt( 'vote_button_text' ) . '"
				/>';
		}

		// Must login to vote
		else {
			if ( wppa_switch( 'login_links' ) ) {
				$result .= sprintf( __( 'You must <a href="%s">login</a> to vote' , 'wp-photo-album-plus' ), wppa_opt( 'login_url' ) );
			}
			else {
				$result .= __( 'You must login to vote' , 'wp-photo-album-plus' );
			}
		}
	}

	// Close the voting box
	if ( ! $is_lightbox ) {
		$result .= '</div>';
	}

	return $result;
}

function wppa_slide_rating_range( $opt ) {

	// Not on unreal start album
	if ( wppa( 'start_album' ) < '1' ) return '';

	// Not on a slideonly
	if ( wppa( 'is_slideonly' ) ) {
		return '';
	}

	// On a slide: depending on slide visibility settings
	if ( $opt == 'optional' && ! wppa_is_item_displayable( wppa( 'start_album' ), 'rating', 'rating_on' ) ) {
		return '';
	}

	$result = wppa_get_rating_range_html();
	wppa_out( $result );
}

function wppa_get_rating_range_html( $id = 0, $is_lightbox = false, $class = '' ) {
global $wpdb;

	// Not on a feed
	if ( is_feed() ) return '';

	// On lightbox: only if in visibility settings set.
	if ( $is_lightbox ) {
		if ( ! wppa_switch( 'ovl_rating' ) ) {
			return '';
		}
	}

	if ( $id ) {
		$wait_text = wppa_get_rating_wait_text( $id );
		if ( $wait_text ) {
			if ( $is_lightbox ) {
				return '';
			}
			return '<span class="'.$class.'" style="color:red" >'.$wait_text.'</span>';
		}
		if ( wppa_get_photo_item( $id, 'owner' ) == wppa_get_user() && ! wppa_switch( 'allow_owner_votes' ) ) {
			if ( $is_lightbox ) {
				return '';
			}
			return '<span class="'.$class.'" >' . __( 'Sorry, you can not rate your own photos' , 'wp-photo-album-plus' ) . '</span>';
		}
		$mylast = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM '.WPPA_RATING.' WHERE photo = %s AND user = %s ORDER BY id DESC LIMIT 1', $id, wppa_get_user() ), ARRAY_A );
		if ( $mylast && ! wppa_switch( 'rating_change' ) && ! wppa_switch( 'rating_multi' ) ) {
			if ( $is_lightbox ) {
				return '';
			}
			return '<span class="'.$class.'" >' . __( 'Sorry, you can rate a photo only once', 'wp-photo-album-plus' ) . '</span>';
		}
	}

	// Mphoto, xphoto and lightbox use a different js function than slideshow.
	// In slideshow the id is not known and retrieved from _wppaCurIdx[mocc].
	// There is also a difference in css.
	$idorlb = $id || $is_lightbox;

	// If on xphoto, reload after
	$reload = ( wppa( 'is_xphoto' ) ? 'true' : 'false' );

	$result = '';

	$fs = wppa_opt( 'fontsize_nav' );
	if ( $fs ) $fs += 3; else $fs = '15';	// iconsize = fontsize+3, Default to 15
	$dh = $fs + '6';
	$size = 'font-size:' . ( $fs - 3 ) . 'px;';

	// Open the rating box
	$result .= 	'
	<div
		id="wppa-rating-' . wppa( 'mocc' ) . '"
		class="wppa-rating-numeric-box ' . ( $idorlb ? $class : 'wppa-box wppa-nav wppa-nav-text' ) . '"
		style="' . $size . 'text-align:center;"
		> ';

	// Graphic display ?
	if ( wppa_opt( 'rating_display_type' ) == 'graphic' ) {
		if ( wppa_opt( 'rating_max' ) == '5' ) {
			$r['1'] = __( 'very low', 'wp-photo-album-plus' );
			$r['2'] = __( 'low', 'wp-photo-album-plus' );
			$r['3'] = __( 'average', 'wp-photo-album-plus' );
			$r['4'] = __( 'high', 'wp-photo-album-plus' );
			$r['5'] = __( 'very high', 'wp-photo-album-plus' );
		}
		else for ( $i = '1'; $i <= '10'; $i++ ) $r[$i] = $i;

		$style = 'height:' . wppa_icon_size( $fs.'px;', 2 ) . ' margin:0 0 -3px 0; padding:0; box-shadow:none; display:inline;background-color:transparent;';
		$icon = 'star.ico';

		$avgrat_label = ( wppa_opt( 'initial_colwidth' ) < wppa_opt( 'mini_treshold' ) ? __( 'Avg.', 'wp-photo-album-plus' ) : __( 'Average&nbsp;rating', 'wp-photo-album-plus' ) );
		$myrat_label  = ( wppa_opt( 'initial_colwidth' ) < wppa_opt( 'mini_treshold' ) ? __( 'Mine', 'wp-photo-album-plus' ) :  __( 'My&nbsp;rating', 'wp-photo-album-plus' ) );

		// Display avg rating
		if ( wppa_switch( 'show_avg_rating' ) ) {

			if ( $id ) {
				$avgrat = wppa_get_rating_by_id( $id, 'nolabel' );
				$opac = array();
				$i = '1';
				while ( $i <= wppa_opt( 'rating_max' ) ) {
					if ( $avgrat >= $i ) {
						$opac[$i] = 'opacity:1;';
					}
					else if ( $avgrat <= ( $i - '1') ) {
						$opac[$i] = 'opacity:0.2;';
					}
					else {
						$opac[$i] = 'opacity:'.(0.2 + 0.8 * ($avgrat-$i+'1'));
					}
					$i++;
				}
			}
			$result .= 	'<span'.
							' id="wppa-avg-rat-' . wppa( 'mocc' ) . '"' .
							' class="wppa-rating-label"' .
							' >' .
							$avgrat_label .
						'</span>&nbsp;';

			$i = '1';
			while ( $i <= wppa_opt( 'rating_max' ) ) {
				$result .= 	'<img' .
								' id="wppa-avg-' . wppa( 'mocc' ) . '-' . $i . '"' .
								' class="wppa-rating-star wppa-avg-' . wppa( 'mocc' ) . '-' . $i . ' wppa-avg-'.wppa( 'mocc' ).' no-shadow"' .
								' style="' .
									$style .
									( $id ? $opac[$i] : '' ) .
									'"' .
								' src="' . wppa_get_imgdir() . $icon . '"' .
								' alt=" ' . $i . '"' .
								' title="'.__('Average&nbsp;rating', 'wp-photo-album-plus' ).': '.$r[$i].'"' .
							' />';
				$i++;
			}
		}

		$result .= 	'<img' .
						' id="wppa-filler-'.wppa( 'mocc' ).'"' .
						' src="'.wppa_get_imgdir().'transp.png"' .
						' alt="f"' .
						' style="width:'.wppa_opt( 'ratspacing').'px; height:15px; box-shadow:none; padding:0; margin:0; border:none;"' .
					' />';

		// Display my rating
		// Logged in
		if ( ! wppa_user_is_basic() && is_user_logged_in() ) {

			// Rating on 2 lines?
			if ( wppa_switch( 'show_avg_mine_2' ) && wppa_switch( 'show_avg_rating' ) ) {
				$result .= '<br>';
			}

			// Text left if no avg rating OR on 2 lines
			if ( ! wppa_switch( 'show_avg_rating' ) || wppa_switch( 'show_avg_mine_2' ) ) {
				$result .= 	'<span' .
								' id="wppa-my-rat-'.wppa( 'mocc' ).'" ' .
								' class="wppa-rating-label"' .
								'>' .
								$myrat_label .
							'</span>&nbsp;';
			}

			// Show dislike icon?
			$pad = round( ( wppa_opt( 'ratspacing' ) - $fs ) / 2 );
			if ( $pad < 5 ) $pad = '5';
			if ( wppa_opt( 'dislike_mail_every' ) ) {

				$confirm = 	esc_attr( str_replace( '"', "'", __('Are you sure you want to mark this image as inappropriate?', 'wp-photo-album-plus' ) ) );
				$result .= 	'<img' .
								' id="wppa-dislike-'.wppa( 'mocc' ).'"' .
								' title="'.__('Click this if you do NOT like this image!', 'wp-photo-album-plus' ).'"' .
								' src="'.wppa_get_imgdir().'thumbdown.png"' .
								' alt="d"' .
								' style="height:'.wppa_icon_size( $fs.'px;', 2 ).'; margin:0 0 -3px 0; padding:0 '.$pad.'px; box-shadow:none; display:inline;opacity:'.(wppa_opt('star_opacity')/100).'"' .
								' class="wppa-rating-thumb  no-shadow"' .
								' onmouseover="jQuery(this).stop().fadeTo(100, 1.0)"' .
								' onmouseout="jQuery(this).stop().fadeTo(100, wppaStarOpacity)"' .
								' onclick="';
									if ( $idorlb ) {
										$result .= 'if (confirm(\'' . $confirm . '\')) { wppaOvlRateIt( \'' . wppa_encrypt_photo($id) . '\', -1, ' . ( $id ? wppa('mocc') : '0' ) . ' ); }';
									}
									else {
										$result .= 'if (confirm(\'' . $confirm . '\')) { wppaRateIt( ' . wppa( 'mocc' ) . ', -1); }';
									}
				$result .= 		'"' .
							' />';

				if ( $idorlb ) {
					$mylast = wppa_get_my_last_vote( $id );

					if ( $mylast == '-1' ) {
						wppa_js( 'jQuery(\'#wppa-dislike-'.wppa( 'mocc' ).'\').css(\'display\'. \'none\');' );
					}
				}

				if ( wppa_switch( 'dislike_show_count' ) ) {
					$result .= 	'<span' .
									' id="wppa-discount-' . wppa( 'mocc' ) . '"' .
									' style="cursor:default"' .
									' title="' . __('Number of people who marked this photo as inappropriate', 'wp-photo-album-plus' ) . '"' .
									' >' .
								'</span>';
				}
			}

			// Display the my rating stars
			if ( $id ) {
				$myavgrat = wppa_get_my_rating_by_id( $id, 'nolabel' );
				$opac = array();
				$i = '1';
				while ( $i <= wppa_opt( 'rating_max' ) ) {
					if ( $myavgrat >= $i ) {
						$opac[$i] = 'opacity:1;';
					}
					else if ( $myavgrat <= ( $i - '1') ) {
						$opac[$i] = 'opacity:0.2;';
					}
					else {
						$opac[$i] = 'opacity:'.(0.2 + 0.8 * ($myavgrat-$i+'1'));
					}
					$i++;
				}
			}

			$i = '1';
			while ( $i <= wppa_opt( 'rating_max' ) ) {
				$result .= 	'<img' .
								' id="wppa-rate-' . wppa( 'mocc' ) . '-' . $i . '"' .
								' class="wppa-rating-star  wppa-rate-' . wppa( 'mocc' ) . '-' . $i . ' wppa-rate-'.wppa( 'mocc' ).' no-shadow"' .
								' style="' .
									$style .
									( $id ? $opac[$i] : '' ) .
									'"' .
								' src="'.wppa_get_imgdir().$icon.'"' .
								' alt="'.$i.'"' .
								' title="'.__('My&nbsp;rating', 'wp-photo-album-plus' ).': '.$r[$i].'"' .

								// Follow and leave are different for slideshw and lightbox et al
								( $id ?
								' onmouseover="wppaOvlFollowMe('.wppa( 'mocc' ).', '.$i.', '.$myavgrat.' )"' .
								' onmouseout="wppaOvlLeaveMe('.wppa( 'mocc' ).', '.$i.', '.$myavgrat.' )"' :
								' onmouseover="wppaFollowMe('.wppa( 'mocc' ).', '.$i.')"' .
								' onmouseout="wppaLeaveMe('.wppa( 'mocc' ).', '.$i.')"' ) .

								( $idorlb ? ' onclick="wppaOvlRateIt(\''.wppa_encrypt_photo($id).'\', '.$i.', ' . ( $id ? wppa('mocc') : '0' ) . ', ' . $reload . ' )"' :
										' onclick="wppaRateIt('.wppa( 'mocc' ).', '.$i.')"' ) .
							' />';
				$i++;
			}

			// Text right if avg rating diaplayed AND not on two lines
			if ( wppa_switch( 'show_avg_rating' ) && ! wppa_switch( 'show_avg_mine_2' ) ) {
				$result .= 	'&nbsp;' .
								'<span' .
									' id="wppa-my-rat-'.wppa( 'mocc' ).'" ' .
									' class="wppa-rating-label"' .
									'>' .
									$myrat_label .
								'</span>';
			}
		}
		elseif ( wppa_user_is_basic() ) {
			$result .= __( 'You must upgrade your membership to enter a comment', 'wp-photo-album-plus' );
		}
		else {
			if ( wppa_switch( 'login_links' ) ) {
				$result .= sprintf(__( 'You must <a href="%s">login</a> to vote' , 'wp-photo-album-plus' ), wppa_opt( 'login_url' ) );
			}
			else {
				$result .= __( 'You must login to vote' , 'wp-photo-album-plus' );
			}
		}
	}

	// display_type = numeric?
	elseif ( wppa_opt( 'rating_display_type' ) == 'numeric' ) {

		// Display avg rating
		if ( wppa_switch( 'show_avg_rating' ) ) {
			$result .= 	__('Average&nbsp;rating', 'wp-photo-album-plus' ).':&nbsp;' .
						'<span id="wppa-numrate-avg-'.wppa( 'mocc' ).'"></span>' .
						' &bull;';
		}

		// Display my rating
		// Logged in
		if ( is_user_logged_in() ) {

			// Show dislike icon?
			$pad = round( ( wppa_opt( 'ratspacing' ) - $fs ) / 2 );
			if ( $pad < 5 ) $pad = '5';
			if ( wppa_opt( 'dislike_mail_every') ) {

				$result .=	'<div' .
								' id="wppa-dislike-imgdiv-'.wppa( 'mocc' ).'"' .
								' style="display:inline"' .
								' >';

				$confirm = 	esc_attr( str_replace( '"', "'", __('Are you sure you want to mark this image as inappropriate?', 'wp-photo-album-plus' ) ) );
				$result .= 		'<img' .
									' id="wppa-dislike-'.wppa( 'mocc' ).'"' .
									' title="'.__('Click this if you do NOT like this image!', 'wp-photo-album-plus' ).'"' .
									' src="'.wppa_get_imgdir().'thumbdown.png"' .
									' alt="d"' .
									' style="height:'.wppa_icon_size( $fs.'px;', 2 ).' margin:0 0 -3px 0; padding:0 '.$pad.'px; box-shadow:none; display:inline;"' .
									' class="no-shadow"' .
									' onmouseover="jQuery(this).stop().fadeTo(100, 1.0)"' .
									' onmouseout="jQuery(this).stop().fadeTo(100, wppaStarOpacity)"' .
									' onclick="';
										if ( $idorlb ) {
											$result .= 'if (confirm(\'' . $confirm . '\')) { wppaOvlRateIt( \'' . wppa_encrypt_photo($id) . '\', -1, ' . ( $id ? wppa('mocc') : '0' ) . ' ); }';
										}
										else {
											$result .= 'if (confirm(\'' . $confirm . '\')) { wppaRateIt( ' . wppa( 'mocc' ) . ', -1); }';
										}
				$result .= 			'"' .
								' />';

				$result .= 		'</div>';

				if ( wppa_switch( 'dislike_show_count') ) {

					$result .= 	'<span' .
									' id="wppa-discount-'.wppa( 'mocc' ).'"' .
									' style="cursor:default"' .
									' title="'.__('Number of people who marked this photo as inappropriate', 'wp-photo-album-plus' ).'"' .
									' >' .
								'</span>';
				}
			}

			$result .= ' <span class="wppa-my-rat-' . wppa( 'mocc' ) . '" >'.__('My&nbsp;rating:', 'wp-photo-album-plus' ).'</span>';
			$result .= '<span id="wppa-numrate-mine-' . wppa( 'mocc' ) . '" ></span>';
		}
		else {
			if ( wppa_switch( 'login_links' ) ) {
				$result .= sprintf(__( 'You must <a href="%s">login</a> to vote', 'wp-photo-album-plus' ), wppa_opt( 'login_url' ) );
			}
			else {
				$result .= __( 'You must login to vote', 'wp-photo-album-plus' );
			}
		}
	}

	// Close rating box
	$result .= '</div>';

	return $result;
}

function wppa_slide_filmstrip( $thumbs ) {

	// A single image slideshow needs no navigation
	if ( wppa( 'is_single' ) ) return;

	$do_it = false;											// Init
	if ( is_feed() ) $do_it = true;							// feed -> do it to indicate that there is a slideshow
	if ( wppa_switch( 'filmstrip' ) ) {						// option on
		if ( ! wppa( 'is_slideonly' ) ) $do_it = true;		// always except slideonly
		if ( wppa( 'film_on' ) ) $do_it = true;				// explicitly turned on
	}

	if ( ! $do_it ) return;									// Don't do it
	if ( ! $thumbs || count( $thumbs ) < 1 ) return; 		// No items

	$preambule = wppa_get_preambule();

	$width 		= ( wppa_opt( 'film_thumbsize' ) + wppa_opt( 'tn_margin' ) ) * ( count( $thumbs ) + 2 * $preambule );
	$width 		+= wppa_opt( 'tn_margin' ) + 100;
	$height 	= wppa_opt( 'film_thumbsize' ) + wppa_opt( 'tn_margin' );
	if ( wppa_opt( 'film_type' ) == 'canvas' ) {
		$height = floor( wppa_opt( 'film_thumbsize' ) / wppa_opt( 'film_aspect' ) ) + wppa_opt( 'tn_margin' );
	}
	$height1 	= wppa_opt( 'film_thumbsize' );
	$topmarg 	= $height / 2 - 12;
	$marg 		= wppa_switch( 'film_arrows' ) ? '42' : '0';
	$fs 		= '24';
	$fw 		= '42';

	if ( wppa_in_widget() ) {
		$width 		/= 2;
		$topmarg 	/= 2;
		$height 	/= 2;
		$height1 	/= 2;
		$marg 		= wppa_switch( 'film_arrows' ) ? '21' : '0';
		$fs 		= '12';
		$fw 		= '21';
	}

	$conw = wppa_get_container_width();
	if ( $conw < 1 ) $conw *= 640;
	$w = $conw - ( 2*6 + 2*$marg + ( wppa_opt( 'bwidth' ) ? 2*wppa_opt( 'bwidth' ) : 0 ) ); /* 2*padding + 2*arrows + 2*border */
	if ( wppa_in_widget() ) $w = $conw - ( 2*6 + 2*$marg + 2*wppa_opt( 'bwidth' ) ); /* 2*padding + 2*arrow + 2*border */
	$IE6 = 'width: '.$w.'px;';
	$pagsiz = round( $w / ( wppa_opt( 'film_thumbsize' ) + wppa_opt( 'tn_margin' ) ) );
	if ( wppa_in_widget() ) $pagsiz = round( $w / ( wppa_opt( 'film_thumbsize' ) / 2 + wppa_opt( 'tn_margin' ) / 2 ) );

	wppa_js( 'wppaFilmPageSize[' . wppa( 'mocc' ) . '] = ' . $pagsiz . ';' );

	if ( is_feed() ) {
		wppa_out( '<div>' );
	}
	else {

		$iconsize = wppa_icon_size( $fs . 'px;' );
		wppa_out( '
		<div
			class="wppa-box wppa-nav wppa-filmstrip-box"
			style="text-align:center;height:'.$height.'px;"
			>' );

		if ( wppa_switch( 'film_arrows' ) ) {
			wppa_out( '
			<div
				class="wppa-fs-arrow-cont-'.wppa( 'mocc' ).'"
				style="float:left; text-align:left; cursor:pointer; margin-top:'.$topmarg.'px; width: '.$fw.'px; font-size: '.$fs.'px;"
				>
				<span
					class="wppa-first-'.wppa( 'mocc' ).' wppa-arrow"
					style="display:inline-block;"
					id="first-film-arrow-'.wppa( 'mocc' ).'"
					onclick="wppaFirst('.wppa( 'mocc' ).');"
					title="'.__('First', 'wp-photo-album-plus' ).'"
					>' .
					wppa_get_svghtml( 'Backward-Button', $iconsize, false, false ) . '
				</span>
			</div>
			<div
				class="wppa-fs-arrow-cont-'.wppa( 'mocc' ).'"
				style="float:right; text-align:right; cursor:pointer; margin-top:'.$topmarg.'px; width: '.$fw.'px; font-size: '.$fs.'px;"
				>
				<span
					class="wppa-last-'.wppa( 'mocc' ).' wppa-arrow"
					style="display:inline-block;"
					id="last-film-arrow-'.wppa( 'mocc' ).'"
					onclick="wppaLast('.wppa( 'mocc' ).');"
					title="'.__('Last', 'wp-photo-album-plus' ).'"
					>' .
					wppa_get_svghtml( 'Forward-Button', $iconsize, false, false ) . '
				</span>
			</div>' );
		}
		wppa_out( '
		<div
			id="filmwindow-'.wppa( 'mocc' ).'"
			class="filmwindow"
			style="'.$IE6.' position:absolute !important; display: block; height:'.$height.'px; margin: 0 0 0 '.$marg.'px; overflow:hidden"
			>
			<div
				id="wppa-filmstrip-'.wppa( 'mocc' ).'"
				class="wppa-filmstrip"
				style="height:'.$height1.'px; width:'.$width.'px; max-width:'.$width.'px;margin-left: -100px;margin-bottom:4px;"
				>' );
	}

	$cnt 	= count( $thumbs );
	$start 	= $cnt - $preambule;
	$end 	= $cnt;
	$idx 	= $start;

	// Preambule
	while ( $idx < $end ) {
		$glue 	= $cnt == ( $idx + 1 ) ? true : false;
		$ix 	= $idx;
		while ( $ix < 0 ) {
			$ix += $cnt;
		}
		if ( isset( $thumbs[$ix] ) ) {
			$thumb = $thumbs[$ix];
			wppa_do_filmthumb( $thumb['id'], $ix, 'pre', $glue );
		}
		$idx++;
	}

	// Real thumbs
	$idx = 0;
	foreach ( $thumbs as $tt ) {
		$thumb = $tt;
		$glue = $cnt == ( $idx + 1 ) ? true : false;
		wppa_do_filmthumb( $thumb['id'], $idx, '', $glue );
		$idx++;
	}

	// Postambule
	$start = '0';
	$end = $preambule;
	$idx = $start;
	while ( $idx < $end ) {
		$ix = $idx;
		while ( $ix >= $cnt ) $ix -= $cnt;
		if ( isset( $thumbs[$ix] ) ) {
			$thumb = $thumbs[$ix];
			wppa_do_filmthumb( $thumb['id'], $ix, 'post' );
		}
		$idx++;
	}

	if ( is_feed() ) {
		wppa_out( '</div>' );
	}
	else {
				wppa_out( '
				</div>
				<div style="clear:both"></div>
			</div>
		</div>' );
	}
}

function wppa_startstop_icons() {

	// Do they need us?
	if ( ! wppa_switch( 'show_start_stop_icons' ) ) {
		return;
	}

	$iconsize = wppa_icon_size( '48px;', 1 );
	$margin = wppa_icon_size( '48px;', 1, 0.5 );

	// Create and output the html
	wppa_out( 	'<div' .
					' id="wppa-startstop-icon-' . wppa( 'mocc' ) . '"' .
					' style="' .
						'position:absolute;' .
						'left:50%;' .
						'margin-left:-' . $margin .
						'top:50%;' .
						'margin-top:-' . $margin .
						'z-index:90;' .
						'width:' . $iconsize .
						'opacity:0.8;' .
						'cursor:pointer;' .
						'box-shadow:none;' .
						'"' .
					' onmouseover="jQuery(this).stop().fadeTo(200,0.8);"' .
					' ontouchstart="jQuery(this).stop().fadeTo(200,0.8);"' .
					' onmouseout="jQuery(this).stop().fadeTo(200,0);"' .
					' ontouchend="jQuery(this).stop().fadeTo(200,0);"' .
					' onclick="wppaStartStop( ' . wppa( 'mocc' ) . ', -1 );"' .
//					' onload="jQuery(this).stop().fadeTo(1000,0);"' .
					' >' .
					wppa_get_svghtml( 'Play-Button', $iconsize, false, true, '0', '5', '50', '50' ) .
				'</div>'
			);
	wppa_js( 'jQuery(document).ready(jQuery(\'#wppa-startstop-icon-' . wppa( 'mocc' ) . '\').stop().fadeTo(2500,0));' );
}

function wppa_numberbar( $thumbs ) {

	// A single image slideshow needs no navigation
	if ( wppa( 'is_single' ) ) return;

	if ( is_feed() ) return;

    $do_it = false;
    if ( wppa_switch( 'show_slideshownumbar') && ! wppa( 'is_slideonly' ) ) $do_it = true;
	if ( wppa( 'numbar_on' ) ) $do_it = true;
	if( ! $do_it ) return;

	// get the data
//	$thumbs = wppa_get_photos();
	if ( empty( $thumbs ) ) return;

	// get the sizes
	$size_given = is_numeric( wppa_opt( 'fontsize_numbar' ) );
	if ( $size_given ) {
		$size = wppa_opt( 'fontsize_numbar' );
		if ( wppa_in_widget() ) $size /= 2;
	}
	else {
		$size = wppa_in_widget() ? '9' : '12';
	}
	if ( $size < '9') $size = '9';
	$size_2  = floor( $size / 2) ;
	$size_4  = floor( $size_2 / 2 );
	$size_32 = floor( $size * 3 / 2 );

	// make the numbar style
	$style = 'position:absolute; bottom:'.$size.'px; right:0; margin-right:'.$size_2.'px; ';

	// start the numbar
	wppa_out( '<div class="wppa-numberbar" style="'.$style.'">' );
		$numid = 0;

		// make the elementstyles
		$style = 	'display:block;' .
					'float:left;' .
					'padding:0 ' .
					$size_4 . 'px;' .
					'margin-right:' . $size_2 . 'px;' .
					'font-weight:' . wppa_opt( 'fontweight_numbar' ) . ';';
		if ( wppa_opt( 'fontfamily_numbar' ) ) {
			$style .= 'font-family:' . wppa_opt( 'fontfamily_numbar' ) .';';
		}
		if ( wppa_opt( 'fontcolor_numbar' ) ) {
			$style .= 'color:' . wppa_opt( 'fontcolor_numbar' ) . ';';
		}
		if ( $size_given ) {
			$style .= 'font-size:' . $size . 'px;line-height:' . $size_32 . 'px;';
		}

		$style_active = $style;

		if ( wppa_opt( 'bgcolor_numbar' ) ) {
			$style .= 'background-color:' . wppa_opt( 'bgcolor_numbar' ) . ';';
		}
		if ( wppa_opt( 'bgcolor_numbar_active' ) ) {
			$style_active .= 'background-color:' . wppa_opt( 'bgcolor_numbar_active' ) . ';';
		}
		if ( wppa_opt( 'bcolor_numbar' ) ) {
			$style .= 'border:1px solid ' . wppa_opt( 'bcolor_numbar' ) . ';';
		}
		if ( wppa_opt( 'bcolor_numbar_active' ) ) {
			$style_active .= 'border:1px solid ' . wppa_opt( 'bcolor_numbar_active' ) . ';';
		}

		$count = count( $thumbs );

		// do the numbers
		foreach ( $thumbs as $tt ) {
			$title = sprintf( __( 'Photo %s of %s', 'wp-photo-album-plus' ), $numid + '1', $count );
			wppa_out( 	'<a' .
							' id="wppa-numbar-'.wppa( 'mocc' ).'-'.$numid.'"' .
							' title="'.$title.'"' .
							' ' . ($numid == 0 ? ' class="wppa-numbar-current" ' : '') .
							' style="' . ($numid == 0 ? $style_active : $style) . '"' .
							' onclick="wppaGotoKeepState('.wppa( 'mocc' ).',' . $numid . ');return false;"' .
							' >' .
							( $numid + 1 ) .
						'</a>'
					);
					if ( $numid == 0 ) {
						wppa_out( '<span
									  id="wppa-nbar-' . wppa( 'mocc' ) . '-lodots"
									  style="float:left;
											 display:none;
											 background-color:transparent;
											 margin-right:6px;
											 font-weight:bold;"
									  >...</span>' );
					}
					if ( $numid == $count - 2 ) {
						wppa_out( '<span
									  id="wppa-nbar-' . wppa( 'mocc' ) . '-hidots"
									  style="float:left;
											 display:none;
											 background-color:transparent;
											 margin-right:6px;
											 font-weight:bold;"
									  >...</span>' );
					}
			$numid++;
		}
	wppa_out( '</div>' );
}

function wppa_browsebar( $opt = '' ) {
	if ( wppa_get_navigation_type() == 'icons' ) {
		wppa_browsebar_icons( $opt );
	}
	else {
		wppa_browsebar_text( $opt );
	}
}
function wppa_browsebar_icons( $opt = '' ) {

	// A single image slideshow needs no navigation
	if ( wppa( 'is_single' ) ) return;

	if ( is_feed() ) return;

	$do_it = false;
	if ( $opt != 'optional' ) $do_it = true;
	if ( ! wppa( 'is_slideonly' ) && wppa_switch( 'show_browse_navigation' ) ) $do_it = true;
	if ( wppa( 'is_slideonly' ) && wppa( 'browse_on' ) ) $do_it = true;

	if ( $do_it ) {
		$iconsize = wppa_icon_size( '1.5em;' );
		wppa_out( 	'<div' .
						' id="prevnext2-' . wppa( 'mocc' ) . '"' .
						' class="wppa-box wppa-nav wppa-nav-text"' .
						' style="text-align:center;"' .
						' >' .
						'<span' .
							' id="prev-arrow-' . wppa( 'mocc' ) . '"' .
							' class="wppa-prev-' . wppa( 'mocc' ) . ' wppa-nav-text arrow-' . wppa( 'mocc' ) . '"' .
							' style="float:left;text-align:left;cursor:pointer"' .
							' title="' . __( 'Previous', 'wp-photo-album-plus' ) . '"' .
							' onclick="wppaPrev(' . wppa( 'mocc' ) . ')"' .
							' >' .
							wppa_get_svghtml( 'Prev-Button', $iconsize ) .
						'</span>' .
						'<span' .
							' id="next-arrow-' . wppa( 'mocc' ) . '"' .
							' class="wppa-next-' . wppa( 'mocc' ) . ' wppa-nav-text arrow-' . wppa( 'mocc' ) . '"' .
							' style="float:right;text-align:right;cursor:pointer"' .
							' title="' . __( 'Next', 'wp-photo-album-plus' ) . '"' .
							' onclick="wppaNext(' . wppa( 'mocc' ) . ')"' .
							' >' .
							wppa_get_svghtml( 'Next-Button', $iconsize ) .
						'</span>' .
						'<span' .
							' id="counter-'.wppa( 'mocc' ).'"' .
							' class="wppa-nav-text wppa-black"' .
							' style="text-align:center;cursor:pointer"' .
							' onclick="wppaStartStop('.wppa( 'mocc' ).', -1);"' .
							' title="'.__('Click to start/stop', 'wp-photo-album-plus' ).'"' .
							' >&nbsp;' .
						'</span>' .
						'<div style="clear:both"></div>' .
					'</div>'
				);
	}
}

function wppa_browsebar_text( $opt = '' ) {

	// A single image slideshow needs no navigation
	if ( wppa( 'is_single' ) ) return;

	if ( is_feed() ) return;

	$do_it = false;
	if ( $opt != 'optional' ) $do_it = true;
	if ( ! wppa( 'is_slideonly' ) && wppa_switch( 'show_browse_navigation' ) ) $do_it = true;
	if ( wppa( 'is_slideonly' ) && wppa( 'browse_on' ) ) $do_it = true;

	if ( $do_it ) {
		wppa_out( 	'<div' .
						' id="prevnext2-'.wppa( 'mocc' ).'"' .
						' class="wppa-box wppa-nav wppa-nav-text"' .
						' style="text-align: center;"' .
						' >' .
						'<a' .
							' id="prev-arrow-'.wppa( 'mocc' ).'"' .
							' class="wppa-prev-'.wppa( 'mocc' ).' wppa-nav-text arrow-'.wppa( 'mocc' ).'"' .
							' style="float:left; text-align:left; cursor:pointer"' .
							' onclick="wppaPrev('.wppa( 'mocc' ).')"' .
							' title="' . __( 'Previous item', 'wp-photo-album-plus' ) . '"' .
							' >' .
						'</a>' .
						'<a' .
							' id="next-arrow-'.wppa( 'mocc' ).'"' .
							' class="wppa-next-'.wppa( 'mocc' ).' wppa-nav-text arrow-'.wppa( 'mocc' ).'"' .
							' style="float:right; text-align:right; cursor:pointer"' .
							' onclick="wppaNext('.wppa( 'mocc' ).')"' .
							' title="' . __( 'Next item', 'wp-photo-album-plus' ) . '"' .
							' >' .
						'</a>' .
						'<span' .
							' id="counter-'.wppa( 'mocc' ).'"' .
							' class="wppa-nav-text wppa-black"' .
							' style="text-align:center; cursor:pointer"' .
							' onclick="wppaStartStop('.wppa( 'mocc' ).', -1);"' .
							' title="'.__('Click to start/stop', 'wp-photo-album-plus' ).'"' .
							' >&nbsp;' .
						'</span>' .
					'</div>'
				);
	}
}

function wppa_comments( $opt = '' ) {

	if ( is_feed() ) {
		if ( wppa_switch( 'show_comments' ) ) {
			wppa_dummy_bar( __( '- - - Comments box activated - - -', 'wp-photo-album-plus' ) );
			return;
		}
	}

	$do_it = false;
	if ( $opt != 'optional' ) $do_it = true;
	if ( ! wppa( 'is_slideonly' ) && wppa_is_item_displayable( wppa( 'start_album' ), 'comments', 'show_comments' ) && ! wppa_in_widget() ) $do_it = true;

	if ( $do_it ) {
		wppa_out( 	'<div' .
						' id="wppa-comments-'.wppa( 'mocc' ).'"' .
						' class="wppa-box wppa-comments"' .
						' style="text-align: center;"' .
						' >' .
					'</div>'
				);
	}
}

function wppa_iptc( $opt = '' ) {

	if ( is_feed() ) {
		if ( wppa_switch( 'show_iptc' ) ) {
			wppa_dummy_bar( __( '- - - IPTC box activated - - -', 'wp-photo-album-plus' ) );
		}
		return;
	}

	$do_it = false;
	if ( $opt != 'optional' ) $do_it = true;
	if ( ! wppa( 'is_slideonly' ) && wppa_switch( 'show_iptc' ) && ! wppa_in_widget() ) $do_it = true;

	if ( $do_it ) {
		wppa_out( 	'<div' .
						' id="iptc-'.wppa( 'mocc' ).'"' .
						' class="wppa-box wppa-box-text wppa-iptc"' .
						' style="text-align: center;"' .
						' >' .
					'</div>'
				);
	}
}

function wppa_exif( $opt = '' ) {

	if ( is_feed() ) {
		if ( wppa_switch( 'show_exif' ) ) {
			wppa_dummy_bar( __( '- - - EXIF box activated - - -', 'wp-photo-album-plus' ) );
		}
		return;
	}

	$do_it = false;
	if ( $opt != 'optional' ) $do_it = true;
	if ( ! wppa( 'is_slideonly' ) && wppa_switch( 'show_exif' ) && ! wppa_in_widget() ) $do_it = true;

	if ( $do_it ) {
		wppa_out( 	'<div' .
						' id="exif-'.wppa( 'mocc' ).'"' .
						' class="wppa-box wppa-box-text wppa-exif"' .
						' style="text-align: center;"' .
						' >' .
					'</div>'
				);
	}
}

function wppa_share( $opt = '' ) {

	if ( is_feed() ) {
		return;
	}

	$do_it = false;
	if ( $opt != 'optional' ) $do_it = true;
	if ( ! wppa( 'is_slideonly' ) ) {
		if ( wppa_switch( 'share_on') && ! wppa_in_widget() ) $do_it = true;
		if ( wppa_switch( 'share_on_widget') && wppa_in_widget() ) $do_it = true;
	}

	if ( $do_it ) {
		wppa_out( 	'<div' .
						' id="wppa-share-'.wppa( 'mocc' ).'"' .
						' class="wppa-box wppa-box-text wppa-share"' .
						' style="text-align: center;"' .
						' >' .
					'</div>'
				);
	}
}

function wppa_errorbox( $text ) {

	wppa_out( 	'<div' .
					' id="error-'.wppa( 'mocc' ).'"' .
					' class="wppa-box wppa-box-text wppa-nav wppa-errorbox"' .
					' style="text-align: center;"' .
					' >' .
					$text .
				'</div>'
			);
}