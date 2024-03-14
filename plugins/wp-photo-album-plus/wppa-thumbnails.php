<?php
/* wppa-thumbnails.php
* Package: wp-photo-album-plus
*
* Various funcions to display a thumbnail image
* Contains all possible frontend thumbnail types
*
* Version: 8.5.02.003
*
*/

// Display the standard thumbnail image
function wppa_thumb_default( $id ) {

	wppa_out( wppa_get_thumb_default( $id ) );
}

// Get the standard thumbnail image html
function wppa_get_thumb_default( $id ) {
global $wpdb;

	// Validate args
	if ( ! wppa_is_int( $id ) || $id < '0' ) {
		return '';
	}

	// Initialize
	$result = '';
	$mocc 	= wppa( 'mocc' );

	// Encrypted photo id
	$xid 	= wppa_encrypt_photo( $id );

	// Get the photo info
	$thumb 	= wppa_cache_photo( $id );
	if ( ! $thumb ) return '';

	// Get the album info
	$alb = $thumb['album'];
	if ( $alb < '1' ) return ''; // Photo deleted

	$album 	= wppa_cache_album( $alb );
	if ( ! $album ) {
		wppa_log('err', 'Photo '.$id.' has non existent album');
		return '';
	}

	global $wppa;
	if ( isset( $wppa['current_album'] ) ) {
		wppa( 'current_album', $alb );
	}

	// Get photo info
	$is_video 		= wppa_is_video( $id );
	$has_audio 		= wppa_has_audio( $id );
	$is_pdf 		= wppa_is_pdf( $id );
	$com_alt 		= wppa( 'is_comten' ) && wppa_switch( 'comten_alt_display' ) && ! wppa_in_widget();
	$frameattr_a 	= wppa_get_thumb_frame_style_a();
	$framestyle 	= $frameattr_a['style'];
	$framewidth 	= $frameattr_a['width'];
	$frameheight 	= $frameattr_a['height'];

	// Get class depending of comment alt display
	if ( $com_alt ) {
		$class = 'thumbnail-frame-comalt thumbnail-frame-comalt-'.$mocc.' thumbnail-frame-photo-'.$xid;
	}
	else {
		$class = 'thumbnail-frame thumbnail-frame-'.$mocc.' thumbnail-frame-photo-'.$xid;
	}

	// Find image attributes
	$imgsrc 			= wppa_get_thumb_path( $id );
	if ( ! wppa_is_file( $imgsrc ) ) {
		wppa_create_thumbnail( $id );
	}
	$alt 				= $album['alt_thumbsize'] == 'yes' ? '_alt' : '';
	$imgattr_a 			= wppa_get_imgstyle_a( $id, $imgsrc, wppa_opt( 'thumbsize'.$alt ), 'optional', 'thumb' );
	$imgstyle  			= $imgattr_a['style'];
	$imgwidth  			= $imgattr_a['width'];
	$imgheight 			= $imgattr_a['height'];
	$imgmargintop 		= $imgattr_a['margin-top'];
	$imgmarginbottom  	= $imgattr_a['margin-bottom'];

	// Special case for comment alt display
	if ( $com_alt ) {
		$imgwidth 	= wppa_opt( 'comten_alt_thumbsize' );
		$imgheight 	= round( $imgwidth * $imgattr_a['height'] / $imgattr_a['width'] );
		$imgstyle   .= 'float:left; margin:0 20px 8px 0;width:'.$imgwidth.'px; height:'.$imgheight.'px;';
	}

	// Cursor depends on link
	$cursor	   		= $imgattr_a['cursor'];

	// Find the required image sizes
	if ( wppa_use_thumb_popup() && wppa_is_file( $imgsrc ) ) {

		// Landscape?
		if ( $imgwidth > $imgheight ) {
			$popwidth 	= wppa_opt( 'popupsize' );
			$popheight 	= round( $popwidth * $imgheight / $imgwidth );
		}
		// Portrait
		else {
			$popheight 	= wppa_opt( 'popupsize' );
			$popwidth 	= round( $popheight * $imgwidth / $imgheight );
		}
	}
	else {
		$popwidth 	= $imgwidth;
		$popheight 	= $imgheight;
	}

	// More image attributes
	$imgurl    	= wppa_get_thumb_url( $id, true, '', $popwidth, $popheight );
	$events    	= wppa_get_imgevents( 'thumb', $id );
	$imgalt		= wppa_get_imgalt( $id );	// returns something like ' alt="Any text" '
	$title 		= esc_attr( wppa_get_photo_name( $id ) );

	// Feed ?
	if ( is_feed() ) {
		$imgattr_a 	= wppa_get_imgstyle_a( $id, $imgsrc, '100', '4', 'thumb' );
		$style 		= $imgattr_a['style'];
		$result 	.= 	'<a href="'.get_permalink().'">' .
							'<img src="'.$imgurl.'" '.$imgalt.' title="'.$title.'" style="'.$style.'" />' .
						'</a>';
		return $result;
	}

	// Open Com alt wrapper
	if ( $com_alt ) $result .= '<div>';

	// Open the thumbframe
	$result .= 	'<div' .
					' id="thumbnail_frame_'.$xid.'_'.$mocc.'"' .
					' class="'.$class.'"' .
					' style="'.$framestyle.'"' .
				' >';

	// Open the image container
	$imgcontheight = $com_alt ? $imgheight : max( $imgwidth,$imgheight );
	if ( ! is_file( $imgsrc ) && ! wppa_is_video( $id ) ) {
		$imgcontheight = 2 * wppa_get_audio_control_height();
	}
	if ( $com_alt ) $framewidth = $imgwidth + '4';
	$result .= '<div' .
					' class="wppa-tn-img-container"' .
					' style="' .
						'height:'.$imgcontheight.'px;' .
						'width:'.$framewidth.'px;' .
						( $com_alt ? 'float:left;' : '' ) .
						'overflow:visible;"' .
				'>';

	// The medals if at the top
	$medalsize = $com_alt ? 'S' : wppa_opt( 'icon_size_multimedia' );
	$result .= wppa_get_medal_html_a( array( 'id' => $id, 'size' => $medalsize, 'where' => 'top', 'thumb' => true ) );

	// The audio when no popup
	if ( wppa_switch( 'thumb_audio' ) && wppa_has_audio( $id ) && ! $com_alt ) {
		$result 	.= '<div style="position:relative;z-index:11;">';
		$is_safari 	= strpos( $_SERVER["HTTP_USER_AGENT"], 'Safari' );
		$cont_h 	= $is_safari ? 16 : 28;
		$audiotop 	= $imgattr_a['height'] + $imgattr_a['margin-top'] - $cont_h;

		if ( ! is_file( $imgsrc ) ) { // Audio without image
			$audiotop 	= wppa_get_audio_control_height();
			$imgwidth 	= wppa_opt( 'tf_width' );
			$imgheight 	= wppa_get_audio_control_height();
		}
		$result 	.= wppa_get_audio_html( array(
							'id' 		=> $id,
							'width'		=> $imgwidth,
							'height' 	=> $cont_h,
							'style' 	=> 'position:absolute;top:'.$audiotop.'px;left:'.((wppa_opt('tf_width')-$imgwidth)/2).'px;border:none;'
							));

		$result .= '</div>';
	}

	// Get the image link
	if ( wppa( 'is_topten' ) ) {
		$no_album = ! wppa( 'start_album' );
		if ( $no_album ) $tit = __( 'View the top rated photos' , 'wp-photo-album-plus' ); else $tit = esc_attr( __( stripslashes( $thumb['description'] ) ) );
		$link = wppa_get_imglnk_a( 'thumb', $id, '', $tit, '', $no_album );
	}
	else $link = wppa_get_imglnk_a( 'thumb', $id ); // voor parent uplr

	// See if ajax possible
	if ( $link ) {

		// Is link an url?
		if ( $link['is_url'] ) {
			if ( wppa_opt( 'thumb_linktype' ) == 'photo' 								// linktype must be to slideshow image
				&& wppa_opt( 'thumb_linkpage' ) == '0'									// same page/post
				&& ! wppa_switch( 'thumb_blank' )										// not on a new tab
				&& ! ( wppa_switch( 'thumb_overrule' ) && $thumb['linkurl'] )			// no ( ps overrule set AND link present )
				&& ! wppa( 'is_topten' )													// no topten selection
				&& ! wppa( 'is_lasten' )													// no lasten selection
				&& ! wppa( 'is_comten' )													// no comten selection
				&& ! wppa( 'is_featen' )
				&& ! wppa( 'is_tag' )														// no tag selection
				&& ! wppa( 'is_upldr' )														// not on uploader deisplay
				&& ! wppa( 'src' )															// no search
				&& ! wppa( 'supersearch' )													// no supersearch
				&& ! wppa( 'is_potdhis' ) 													// not on potd history
//				&& ! wppa( 'calendar' )
				&& ( wppa_is_int( wppa( 'start_album' ) ) || wppa( 'start_album' ) == '' )	// no set of albums
				 )
			{ 	// Ajax	possible

				// The a img ajax
				$p =  wppa( 'calendar' ) ? '' : $xid;

				$ajax_url = wppa_get_slideshow_url_ajax( array( 'album' => wppa( 'start_album' ),
																'photo' => $p ) );
				$href_url = wppa_get_slideshow_url( array( 'album' => wppa( 'start_album' ),
														   'photo' => $p ) );
				$onclick = "wppaDoAjaxRender( $mocc, '$ajax_url', '$href_url' );return false;";

				$result .= '
				<a
					style="position:static;"
					class="thumb-img"
					id="x-'.$xid.'-'.$mocc.'"
					href="' . esc_url($href_url) . '"
					onclick="'. esc_attr($onclick).'"
					>';

				// Video?
				if ( $is_video ) {

					$result .= wppa_get_video_html( array(
							'id'			=> $id,
							'width'			=> $imgwidth,
							'height' 		=> $imgheight,
							'controls' 		=> wppa_switch( 'thumb_video' ),
							'margin_top' 	=> '0',
							'margin_bottom' => '0',
							'tagid' 		=> 'i-'.$xid.'-'.$mocc,
							'cursor' 		=> 'cursor:pointer;',
							'events' 		=> $events,
							'title' 		=> $title,
							'preload' 		=> 'metadata',
						//	'onclick' 		=> $onclick,
							'lb' 			=> false,
							'class' 		=> '',
							'style' 		=> $imgstyle
							));
				}

				// No video				/* onclick="' . $onclick . '" */
				else {
					$result .= 	'
					<img

						id="i-' . $xid . '-'.$mocc . '" ' .
						( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
						$imgalt .
						( $title ? ' title="' . $title . '"' : '' ) . '
						style="' . $imgstyle . 'cursor:pointer"' .
						$events . '
					/>';
				}

				// Close the a img ajax
				$result .= '</a>';
			}

			// non ajax
			else {

				// The a img non ajax
				$result .= '<a style="position:static;" href="'.$link['url'].'" target="'.$link['target'].'" class="thumb-img" id="x-'.$xid.'-'.$mocc.'">';
				if ( $is_video ) {
					$result .= wppa_get_video_html( array(
							'id'			=> $id,
							'width'			=> $imgwidth,
							'height' 		=> $imgheight,
							'controls' 		=> wppa_switch( 'thumb_video' ),
							'margin_top' 	=> '0',
							'margin_bottom' => '0',
							'tagid' 		=> 'i-'.$xid.'-'.$mocc,
							'cursor' 		=> 'cursor:pointer;',
							'events' 		=> $events,
							'title' 		=> $title,
							'preload' 		=> 'metadata',
							'onclick' 		=> '',
							'lb' 			=> false,
							'class' 		=> '',
							'style' 		=> $imgstyle
							));
				}
				else {
					$result .= 	'<img' .
									' id="i-' . $xid . '-' . $mocc . '"' .
									' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '" ' . $imgalt .
									( $title ? ' title="' . $title . '"' : '' ) .
									' width="' . $imgwidth . '"' .
									' height="' . $imgheight . '"' .
									' style="' . $imgstyle . ' cursor:pointer"' .
									' ' . $events .
								' />';
				}

				// Close the img non ajax
				$result .= '</a>';
			}
		}

		// Link is not an url. link is lightbox ?
		elseif ( $link['is_lightbox'] ) {
			$title 		= wppa_get_lbtitle( 'thumb', $id );

			// The a img
			$result .= '<a href="'.$link['url'].'" target="'.$link['target'] . '"' .
						' data-id="' . wppa_encrypt_photo( $id ) . '"' .
						( $is_video ? ' data-videohtml="' . esc_attr( wppa_get_video_body( $id ) ) . '"' .
						' data-videonatwidth="'.wppa_get_videox( $id ) . '"' .
						' data-videonatheight="'.wppa_get_videoy( $id ) . '"' : '' ) .
						( $has_audio ? ' data-audiohtml="' . esc_attr( wppa_get_audio_body( $id ) ) . '"' : '' ) .
						( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $id ) ) .'"' : '' ) .
						' data-rel="wppa[occ'.$mocc.']"' .
						' ' . 'data-lbtitle' . '="'.$title.'" ' .
						wppa_get_lb_panorama_full_html( $id ) .
						' class="thumb-img" id="x-'.$xid.'-'.$mocc.'"' .
						' data-alt="' . esc_attr( wppa_get_imgalt( $id, true ) ) . '"' .
						' onclick="return false;"' .
						' style="cursor:' . wppa_wait() . ';"' .
						' >';
			if ( $is_video ) {
				$result .= wppa_get_video_html( array(
						'id'			=> $id,
						'width'			=> $imgwidth,
						'height' 		=> $imgheight,
						'controls' 		=> wppa_switch( 'thumb_video' ),
						'margin_top' 	=> '0',
						'margin_bottom' => '0',
						'tagid' 		=> 'i-'.$xid.'-'.$mocc,
						'cursor' 		=> $cursor,
						'events' 		=> $events,
						'title' 		=> wppa_zoom_in( $id ),
						'preload' 		=> 'metadata',
						'onclick' 		=> '',
						'lb' 			=> false,
						'class' 		=> '',
						'style' 		=> $imgstyle
						));
			}
			else {
				$title = wppa_zoom_in( $id );
				$result .= 	'<img' .
								' id="i-' . $xid . '-' . $mocc . '"' .
								' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
								' ' . $imgalt .
								( $title ? ' title="' . $title . '"' : '' ) . '
								style="' . $imgstyle . $cursor . '"' .
								' ' . $events .
							' />';
			}

			// Close the a img
			$result .= '</a>';
		}
		else {	// is onclick
			// The div img
			$result .= '<div onclick="'.$link['url'].'" class="thumb-img" id="x-'.$xid.'-'.$mocc.'">';
			if ( $is_video ) {
				$result .= wppa_get_video_html( array(
						'id'			=> $id,
						'width'			=> $imgwidth,
						'height' 		=> $imgheight,
						'controls' 		=> wppa_switch( 'thumb_video' ),
						'margin_top' 	=> '0',
						'margin_bottom' => '0',
						'tagid' 		=> 'i-'.$xid.'-'.$mocc,
						'cursor' 		=> 'cursor:pointer;',
						'events' 		=> $events,
						'title' 		=> $title,
						'preload' 		=> 'metadata',
						'onclick' 		=> '',
						'lb' 			=> false,
						'class' 		=> '',
						'style' 		=> $imgstyle
						));
			}
			else {
				if ( wppa_opt( 'thumb_linktype' ) == 'fullpopup' && wppa_is_photo( $id ) && wppa_switch( 'art_monkey_on' ) && ( strpos( wppa_opt( 'art_monkey_types' ), 'photo' ) !== false ) ) $pointer = 'pointer;';
				else $pointer = 'default;';
				$result .= 	'<img' .
								' id="i-' . $xid . '-' . $mocc . '"' .
								' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
								' ' . $imgalt .
								( $title ? ' title="' . $title . '"' : '' ) .
								' style="' . $imgstyle . ' cursor:' . $pointer . '"' .
								' ' . $events .
							' />';
			}
			$result .= '</div>';

			wppa_js( 'wppaPopupOnclick[' . $id . '] = "' . $link['url'] . '";' );
		}
	}
	else {	// no link
		if ( wppa_use_thumb_popup() ) {
			$result .= '<div id="x-'.$xid.'-'.$mocc.'">';
				if ( $is_video ) {
					$result .= wppa_get_video_html( array(
							'id'			=> $id,
							'width'			=> $imgwidth,
							'height' 		=> $imgheight,
							'controls' 		=> false,
							'margin_top' 	=> '0',
							'margin_bottom' => '0',
							'tagid' 		=> 'i-'.$xid.'-'.$mocc,
							'cursor' 		=> '',
							'events' 		=> $events,
							'title' 		=> $title,
							'preload' 		=> 'metadata',
							'onclick' 		=> '',
							'lb' 			=> false,
							'class' 		=> '',
							'style' 		=> $imgstyle
							));
				}
				else {
					$result .= 	'<img' .
									' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
									' ' . $imgalt .
									( $title ? ' title="' . $title . '"' : '' ) .
									' style="' . $imgstyle . '"' .
									' ' . $events .
								' />';
				}
			$result .= '</div>';
		}
		else {
			if ( $is_video ) {
				$result .= wppa_get_video_html( array(
						'id'			=> $id,
						'width'			=> $imgwidth,
						'height' 		=> $imgheight,
						'controls' 		=> wppa_switch( 'thumb_video' ),
						'margin_top' 	=> '0',
						'margin_bottom' => '0',
						'tagid' 		=> 'i-'.$id.'-'.$mocc,
						'cursor' 		=> '',
						'events' 		=> $events,
						'title' 		=> $title,
						'preload' 		=> 'metadata',
						'onclick' 		=> '',
						'lb' 			=> false,
						'class' 		=> '',
						'style' 		=> $imgstyle
						));
			}
			else {
				$result .= 	'<img' .
								' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
								' ' . $imgalt .
								( $title ? ' title="' . $title . '"' : '' ) .
								' style="' . $imgstyle . '"' .
								' ' . $events . ' />';
			}
		}
	}

	// The medals if near the bottom
	$result .= wppa_get_medal_html_a( array( 'id' => $id, 'size' => $medalsize, 'where' => 'bot', 'thumb' => true ) );

	// Close the image container
	$result .= '</div>';

	// Comten alt display?
	if ( $com_alt ) {
		$comaltwidth = wppa_get_container_width() - $imgwidth - 16 - wppa_get_thumbnail_area_delta();
		$result .= 	'<div' .
						' class="wppa-com-alt wppa-com-alt-' . $mocc . '"' .
						' style="' .
							'height:' . $imgheight . 'px;' .
							'overflow:auto;' .
							'margin: 0 0 8px 10px;' .
							'border:1px solid ' . wppa_opt( 'bcolor_alt' ) . ';' .
							'width:' . $comaltwidth . 'px;' .
							'"' .
					' >';

			$comments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_comments
															 WHERE photo = %d
															 AND status = 'approved'
															 ORDER BY timestamp DESC", $id ), ARRAY_A );
			$first = true;
			if ( $comments ) foreach ( $comments as $com ) {
				$result .= 	'<h6' .
								' style="' .
									'font-size:10px;' .
									'line-height:12px;' .
									'font-weight:bold;' .
									'padding:'.( $first ? '0' : '6px' ).' 0 0 6px;' .
									'margin:0;float:left;'.
									'"'.
								'>' .
									$com['user'] . ' ' . __( 'wrote' , 'wp-photo-album-plus' ) . ' ' . wppa_get_time_since( $com['timestamp'] ) . ':' .
							'</h6>'.
							'<p' .
								' style="' .
									'font-size:10px;' .
									'line-height:12px;' .
									'padding:0 0 0 6px;' .
									'text-align:left;' .
									'margin:0;' .
									'clear:left;' .
									'"' .
								'>' .
									html_entity_decode( convert_smilies( stripslashes( $com['comment'] ) ) ) .
							'</p>';
							$first = false;
			}
		$result .= '</div>';
	}

	// NOT comalt
	else {

		// Open the subtext container
		if ( ! $imgmarginbottom ) {
			$imgmarginbottom = '0';
		}
		$margtop = wppa_switch( 'align_thumbtext' ) ? '' : 'margin-top:'.-$imgmarginbottom.'px;';
		$subtextcontheight = $frameheight - max( $imgwidth,$imgheight );
		if ( ! wppa_switch( 'align_thumbtext' ) ) $subtextcontheight += $imgmarginbottom;
		$result.=	'<div' .
						' class="thumbnail-subtext-frame"' .
						' style="' .
							'height:'.$subtextcontheight.'px;' .
							'width:'.$framewidth.'px;' .
							'position:absolute;' .
							$margtop .
							'overflow:hidden;' .
						'" >';

		// Single button voting system
		if ( wppa_opt( 'rating_max' ) == '1' && wppa_switch( 'vote_thumb' ) ) {
			$mylast = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->wppa_rating
													   WHERE photo = %s
													   AND user = %s
													   ORDER BY id DESC
													   LIMIT 1", $id, wppa_get_user() ), ARRAY_A );

			// Likes
			if ( wppa_opt( 'rating_display_type' ) == 'likes' ) {
				$lt = wppa_get_like_title_a( $id );
				$src = $lt['mine'] ? wppa_get_imgdir() . 'thumbdown.png' : wppa_get_imgdir() . 'thumbup.png';
				$result .=	'<div' .
								' id="wppa-like-imgdiv-'.$mocc.'"' .
								' style="display:inline"' .
								' >' .

								'<img' .
									' id="wppa-like-' . $id . '-' . $mocc . '"' .
									' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $src . '"' .
									' alt="up"' .
									' title="' . esc_attr( $lt['title'] ) . '"' .
									' style="height:16px; margin:0 0 -3px 0; padding:0 4px; box-shadow:none; display:inline;"' .
									' class="no-shadow"' .
									' onmouseover="jQuery(this).stop().fadeTo(100, 1.0);"' .
									' onmouseout="jQuery(this).stop().fadeTo(100, wppaStarOpacity);"' .
									' onclick="wppaOvlRateIt( \'' . wppa_encrypt_photo( $id ) . '\', 1, ' . $mocc . ' );' . '"' .
									' onload="jQuery(this).trigger(\'onmouseout\');"' .
								' />';

				if ( wppa_switch( 'show_avg_rating' ) ) {
					$result .=
					'<span' .
						' id="wppa-liketext-' . $id . '-' . $mocc . '"' .
						' class="wppa-thumb-text"' .
						'> ' .
						$lt['display'] .
					'</span>';
				}
				$result .= '</div>';

			}

			// Button
			else {
				$buttext = $mylast ? __( wppa_opt( 'voted_button_text' ) , 'wp-photo-album-plus' ) : __( wppa_opt( 'vote_button_text' ) , 'wp-photo-album-plus' );
				$result .= 	'<input' .
								' id="wppa-vote-button-' . $mocc . '-' . $xid . '"' .
								' class="wppa-vote-button-thumb"' .
								' style="margin:0;"' .
								' type="button"' .
								' onclick="wppaVoteThumb( ' . $mocc . ', \'' . $xid . '\' )"' .
								' value="'.$buttext.'"' .
							' />';
			}
		}

		// Name
		if ( wppa_is_item_displayable( $alb, 'name', 'thumb_text_name' ) ) {
			$name = wppa_get_photo_name( $id, ['addowner' => wppa_switch( 'thumb_text_owner' ), 'isthumb' => true] );
			if ( wppa_switch( 'art_monkey_thumb' ) ) {
				$name = wppa_get_download_html( $id, 'nameonly', $name );
			}
			$result .= 	'<div' .
							' class="wppa-thumb-text"' .
							' >' .
							$name .
						'</div>';
		}

		// searching, link to album
		if ( wppa_switch( 'thumb_text_virt_album' ) && wppa_is_virtual() && wppa( 'start_album' ) != $thumb['album'] ) {
			$result .= 	'<div' .
							' class="wppa-thumb-text"' .
							' >' .
							'<a' .
								' href="' . wppa_get_album_url( array( 'album' => $thumb['album'] ) ) . '"' .
								' >' .
								'<span class="wppa-tnpar" >(</span>' .
									stripslashes( __( wppa_get_album_name( $thumb['album'] ) , 'wp-photo-album-plus' ) ) .
								'<span class="wppa-tnpar" >)</span>' .
							'</a>' .
						'</div>';
		}

		// Share
		if ( wppa_switch( 'share_on_thumbs' ) ) {
			$result .= 	'<div' .
							' class="wppa-thumb-text"' .
							' >' .
							wppa_get_share_html( $id, 'thumb' ) .
						'</div>';
		}

		// Delete and Edit links
		if ( wppa_switch( 'edit_thumb' ) ) {

			// Open the div
			$result .=
				'<div' .
					' class="wppa-thumb-text"' .
					' >';

				// The admins choice link
				if ( wppa_is_photo( $thumb['id'] ) ) {
					$choice = wppa_opt( 'admins_choice' );
					if ( current_user_can( 'wppa_admin' ) || wppa_opt( 'admins_choice_action' ) != 'album' ) {
						if ( ( wppa_user_is_admin() && $choice != 'none' ) ||
							 ( is_user_logged_in() && $choice == 'login' ) ) {

							$result .=
							'<span' .
								' id="admin-choice-' . wppa_encrypt_photo($thumb['id']) . '-' . $mocc . '"' .
								' style="color:gray;"' .
								' >';
							if ( ! wppa_is_photo_in_zip( $thumb['id'] ) ) {
								$result .=
								'<a' .
									' style="color:blue;cursor:pointer"' .
									' onclick="' .
										esc_attr( 'if ( confirm( "' . __( 'Are you sure you want to add this photo to your selection?' , 'wp-photo-album-plus' ) . '" ) ) ' .
										'wppaAjaxAddPhotoToZip( '.$mocc.', \''.wppa_encrypt_photo($thumb['id']).'\', false ); return false;' ).'"' .
									'>' .
									__( 'MyChoice' , 'wp-photo-album-plus' ) .
								'</a>';
							}
							else {
								$result .= __('Selected', 'wp-photo-album-plus' );
							}
							$result .=
							'</span>&nbsp;';
						}
					}
				}

				// The delete link
				if ( wppa_may_user_fe_delete( $id ) && $thumb['album'] > 0 ) {
					$result .=
					'<a' .
						' id="wppa-delete-' . wppa_encrypt_photo( $id ) . '"' .
						' style="color:red;cursor:pointer"' .
						' onclick="'.esc_attr( 'if ( confirm( "'.__( 'Are you sure you want to remove this photo?' , 'wp-photo-album-plus' ).'" ) ) wppaAjaxRemovePhoto( '.$mocc.', \''.$xid.'\', false ); return false;' ).'"' .
						' >' .
						__( 'Delete' , 'wp-photo-album-plus' ) .
					'</a>' .
						'&nbsp;';
				}

				// The edit link
				if ( wppa_may_user_fe_edit( $id ) ) {
					$result .=
					'<a' .
						' style="color:green;cursor:pointer"' .
						' onclick="wppaEditPhoto( '.$mocc.', \''.$xid.'\' ); return false;"' .
						' >' .
						__( 'Edit' , 'wp-photo-album-plus' ) .
					'</a>';
				}

			// Close the div
			$result .=
				'</div>';
		}

		// Description
		if ( wppa_is_item_displayable( $alb, 'description', 'thumb_text_desc' ) ||
				$thumb['status'] == 'pending' ||
				$thumb['status'] == 'scheduled' ) {
			$desc = '';
			if ( $thumb['status'] == 'pending' || $thumb['status'] == 'scheduled' ) {
				$desc .= wppa_moderate_links( 'thumb', $id );
			}
			$desc .= wppa_get_photo_desc( $id, array( 'doshortcodes' => wppa_switch( 'allow_foreign_shortcodes_thumbs' ) ) );

			// Run wpautop on description?
			if ( wppa_opt( 'wpautop_on_thumb_desc' ) == 'wpautop' ) {
				$desc = wpautop( $desc );
			}
			elseif ( wppa_opt( 'wpautop_on_thumb_desc' ) == 'nl2br' ) {
				$desc = nl2br( $desc );
			}

			$result .= 	'<div' .
							' class="wppa-thumb-text"' .
							' >' . $desc .
						'</div>';
		}

		// Rating
		if ( wppa_is_item_displayable( $alb, 'rating', 'thumb_text_rating' ) ) {
			if ( wppa_opt( 'rating_max' ) > 1 ) {
				$rating = wppa_get_rating_by_id( $id );
				if ( $rating && wppa_switch( 'show_rating_count' ) ) $rating .= ' ( '.wppa_get_rating_count_by_id( $id ).' )';
			}
			else {
				$n = wppa_get_rating_count_by_id( $id );
				$rating = sprintf( _n( '%d vote', '%d votes', $n, 'wp-photo-album-plus' ), $n );
			}
			$result .= 	'<div' .
							' class="wppa-thumb-text"' .
							' >' . $rating .
						'</div>';
		}

		// Comcount
		if ( wppa_is_item_displayable( $alb, 'comments', 'thumb_text_comcount' ) ) {
			$comcount_role = 0;
			$comcount = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->wppa_comments
														 WHERE photo = %d", $id ) );

			// Note special role?
			$role = wppa_opt( 'thumb_text_comcount_note_role' );

			if ( $comcount && $role ) {

				if ( current_user_can( $role ) ) {

					global $wp_roles;
					$roles = $wp_roles->roles;
					$role_name = translate_user_role( $roles[$role]['name'] );

					// Get the comments users
					$com_user_ids = $wpdb->get_col( $wpdb->prepare( "SELECT userid FROM $wpdb->wppa_comments
																  WHERE photo = %d", $id ) );

					// Count the comments given by users with the specified role
					foreach( $com_user_ids as $usr_id ) {
						if ( user_can( $usr_id, $role ) ) {
							$comcount_role++;
						}
					}
				}
			}
			$comcount -= $comcount_role;

			if ( $comcount + $comcount_role ) {
				$result .= 	'<div' .
								' class="wppa-thumb-text"' .
								' >';
								if ( $comcount && $comcount_role ) {
									$result .=
									sprintf( _n( '%d comment', '%d comments', $comcount, 'wp-photo-album-plus' ), $comcount ) .
									'<br/><span class="wppa-role-spec" >' . sprintf( __( 'and %d by &lt;%s&gt;', 'wp-photo-album-plus' ), $comcount_role, $role_name ) .
									'</span>';
								}
								elseif ( $comcount ) {
									$result .=
									sprintf( _n( '%d comment', '%d comments', $comcount, 'wp-photo-album-plus' ), $comcount );
								}
								else {
									$result .=
									'<span class="wppa-role-spec" >' .
									sprintf( _n( '%d comment by &lt;%s&gt;', '%d comments by &lt;%s&gt;', $comcount_role, 'wp-photo-album-plus' ), $comcount_role, $role_name ) .
									'</span>';
								}
								$result .=
							'</div>';
			}
		}

		// Viewcount
		if ( wppa_switch( 'thumb_text_viewcount' ) ) {
			$result .= 	'<div' .
							' class="wppa-thumb-text"' .
							' style="clear:both;"' .
							' >' . sprintf( _n( '%d view', '%d views', $thumb['views'], 'wp-photo-album-plus' ), $thumb['views'] ) .
						'</div>';
		}

		// Close the subtext container
		$result .= 	'</div>';

	} // if ! $com_alt

	// Close the thumbframe
	$result .= '</div>';

	if ( $com_alt ) $result .= '</div>';

	return $result;
}

// A thumb 'as cover'
function wppa_thumb_ascover( $id ) {
global $cover_count_key;
global $thlinkmsggiven;

	// Init
	$result = '';
	$mocc = wppa( 'mocc' );

	// Encrypted photo id
	$xid = wppa_encrypt_photo( $id );

	// Get the photo info
	$thumb = wppa_cache_photo( $id );

	// Get the album info
	$album = wppa_cache_album( $thumb['album'] );

	$path 		= wppa_get_thumb_path( $id );
	$imgattr_a 	= wppa_get_imgstyle_a( $id, $path, wppa_opt( 'smallsize' ), '', 'cover' );
	$events 	= is_feed() ? '' : wppa_get_imgevents( 'cover' );
	$src 		= wppa_get_thumb_url( $id, true, '', $imgattr_a['width'], $imgattr_a['height'] );
	$link 		= wppa_get_imglnk_a( 'thumb', $id );

	if ( $link ) {
		$href = $link['url'];
		$title = $link['title'];
		$target = $link['target'];
	}
	else {
		$href = '';
		$title = '';
		$target = '';
	}

	if ( ! $link['is_url'] ) {
		if ( ! $thlinkmsggiven ) wppa_lor( 'Err', 'Title link may not be an event in thumbs as covers.' );
		$href = '';
		$title = '';
		$thlinkmsggiven = true;
	}

	$mcr = wppa_opt( 'thumbtype' ) == 'ascovers-mcr' ? 'mcr-' : '';

	$photo_left = wppa_switch( 'thumbphoto_left' );
	$class_asym = 'wppa-asym-text-frame-'.$mcr.$mocc;

	$style = '';
	if ( is_feed() ) $style .= ' padding:7px;';

	$wid = wppa_get_cover_width( 'thumb' );
	$style .= 'width: '.$wid.'px;';
	if ( $cover_count_key == 'm' ) {
		$style .= 'margin-left: 8px;';
	}
	elseif ( $cover_count_key == 'r' ) {
		$style .= 'float:right;';
	}
	else {
		$style .= 'clear:both;';
	}
	wppa_step_covercount( 'thumb' );

	$result .= 	"\n" .  '<div' .
							' id="thumb-' . $xid . '-' . $mocc . '"' .
							' class="thumb wppa-box wppa-cover-box wppa-cover-box-' . $mcr . $mocc . '"' .
							' style="' . $style . '"' .
							' >';

		if ( $photo_left ) {
			$result .= wppa_the_thumbascoverphoto( $id, $src, $photo_left, $link, $imgattr_a, $events );
		}

		$textframestyle = wppa_get_text_frame_style( $photo_left, 'thumb' );

		$result .=  '<div' .
						' id="thumbtext_frame_' . $id . '_' . $mocc . '"' .
						' class="wppa-text-frame-' . $mocc . ' wppa-text-frame thumbtext-frame ' . $class_asym . '"' .
						' ' . $textframestyle .
						' >' .
						'<h2' .
							' class="wppa-title"' .
							' style="clear:none;"' .
							' >';
							if ( $link['is_lightbox'] ) {
								$result .= wppa_get_photo_name( $id );
							}
							else {
								$result .= 	'<a' .
												' href="' . $href . '"' .
												' target="' . $target . '"' .
												' title="' . $title . '"' .
												' >' . wppa_get_photo_name( $id ) .
											'</a>';
							}
			$result .= 	'</h2>';

			$desc =  wppa_get_photo_desc( $id );
			if ( in_array( $thumb['status'], array( 'pending', 'scheduled' ) ) ) $desc .= wppa_moderate_links( 'thumb', $id );

			// Run wpautop on description?
			if ( wppa_opt( 'wpautop_on_thumb_desc' ) == 'wpautop' ) {
				$desc = wpautop( $desc );
			}
			elseif ( wppa_opt( 'wpautop_on_thumb_desc' ) == 'nl2br' ) {
				$desc = nl2br( $desc );
			}


			$result .= 	'<p' .
							' class="wppa-box-text wppa-black"' .
							'" >' . $desc .
						'</p>';
		$result .= 	'</div>';

		if ( ! $photo_left ) {
			$result .= wppa_the_thumbascoverphoto( $id, $src, $photo_left, $link, $imgattr_a, $events );
		}

	$result .= 	'</div>';

	wppa_out( $result );
}

// The image for the 'thumb as cover'
function wppa_the_thumbascoverphoto( $id, $src, $photo_left, $link, $imgattr_a, $events ) {

	$result 	= '';
	$href 		= $link['url'];
	$title 		= $link['title'];
	$imgattr 	= $imgattr_a['style'];
	$imgwidth 	= $imgattr_a['width'];
	$imgheight 	= $imgattr_a['height'];
	$frmwidth 	= $imgwidth + '10';	// + 2 * 1 border + 2 * 4 padding
	$mocc 		= wppa( 'mocc' );

	if ( ! $src ) {
		return '';
	}

	// Encrypted photo id
	$xid = wppa_encrypt_photo( $id );

	if ( wppa_in_widget() ) {
		$photoframestyle = 'style="text-align:center;"';
	}
	else {
		$photoframestyle = $photo_left ? 'style="float:left; margin-right:5px;width:'.$frmwidth.'px;"' : 'style="float:right; margin-left:5px;width:'.$frmwidth.'px;"';
	}

	$result .= 	'<div'.
					' id="thumbphoto_frame_' . $xid . '_' . $mocc . '"' .
					' class="thumbphoto-frame"' .
					' ' . $photoframestyle .
					'>';

	if ( $link['is_lightbox'] ) {
		$href = wppa_get_hires_url( $id );
		$cursor = ' cursor:' . wppa_wait() . ';'; //url( ' .wppa_get_imgdir() . wppa_opt( 'magnifier' ) . ' ),pointer;';

		$result .= 	'<a' .
						' data-id="' . wppa_encrypt_photo( $id ) . '"' .
						' href="' . $href . '"' .
						' data-rel="wppa[occ' . $mocc . ']"' .
						( $title ? ' ' . 'data-lbtitle' . '="' . $title . '"' : '' ) .
						' data-alt="' . esc_attr( wppa_get_imgalt( $id, true ) ) . '"' .
						' onclick="return false;"' .
						' >';

			if ( wppa_is_video( $id ) ) {
				$result .= wppa_get_video_html( array (
							'id'			=> $id,
							'width'			=> $imgwidth,
							'height' 		=> $imgheight,
							'controls' 		=> false,
					//		'margin_top' 	=> '0',
					//		'margin_bottom' => '0',
							'tagid' 		=> 'i-'.$xid.'-'.$mocc,
					//		'cursor' 		=> '',
							'events' 		=> $events,
							'title' 		=> $title,
							'preload' 		=> 'metadata',
					//		'onclick' 		=> '',
							'lb' 			=> false,
							'class' 		=> 'image wppa-img',
							'style' 		=> $imgattr.$cursor		//$imgstyle
						) );
			}
			else {
				$result .= 	'<img' .
								' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $src . '"' .
								' ' . wppa_get_imgalt( $id ) .
								' class="image wppa-img"' .
								' style="' . $imgattr . $cursor . '"' .
								' ' . $events .
							' />';
			}
		$result .= '</a>';
	}
	elseif ( $link['is_url'] ) {

		$result .= 	'<a' .
						' href="' . $href . '"' .
						( $title ? ' title="' . $title . '"' : '' ) .
						' >';

			if ( wppa_is_video( $id ) ) {
				$result .= wppa_get_video_html( array (
							'id'			=> $id,
							'width'			=> $imgwidth,
							'height' 		=> $imgheight,
							'controls' 		=> false,
					//		'margin_top' 	=> '0',
					//		'margin_bottom' => '0',
							'tagid' 		=> 'i-'.$xid.'-'.$mocc,
					//		'cursor' 		=> '',
							'events' 		=> $events,
							'title' 		=> $title,
							'preload' 		=> 'metadata',
					//		'onclick' 		=> '',
							'lb' 			=> false,
							'class' 		=> 'image wppa-img',
							'style' 		=> $imgattr		//$imgstyle
						) );
			}
			else {
				$result .= 	'<img' .
								' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $src . '"' .
								' ' . wppa_get_imgalt( $id ) .
								' class="image wppa-img"' .
								' style="' . $imgattr . '"' .
								' ' . $events .
								' />';
			}
		$result .= 	'</a>';
	}
	else {
		if ( wppa_is_video( $id ) ) {
				$result .= wppa_get_video_html( array (
							'id'			=> $id,
							'width'			=> $imgwidth,
							'height' 		=> $imgheight,
							'controls' 		=> false,
					//		'margin_top' 	=> '0',
					//		'margin_bottom' => '0',
							'tagid' 		=> 'i-'.$xid.'-'.$mocc,
					//		'cursor' 		=> '',
							'events' 		=> $events,
							'title' 		=> $title,
							'preload' 		=> 'metadata',
							'onclick' 		=> $href,
							'lb' 			=> false,
							'class' 		=> 'image wppa-img',
							'style' 		=> $imgattr		//$imgstyle
						) );
		}
		else {
			$result .= 	'<img' .
							' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $src . '"' .
							' ' . wppa_get_imgalt( $id ) .
							' class="image wppa-img"' .
							' style="' . $imgattr . '"' .
							' ' . $events .
							' onclick="' . $href . '"' .
							' />';
		}
	}
	$result .= '</div>';

	return $result;
}

// Display the masonry thumbnail image
function wppa_thumb_masonry( $id ) {

	wppa_out( wppa_get_thumb_masonry( $id ) );
}

// Get the masonry thumbnail image html
function wppa_get_thumb_masonry( $id ) {

	// Init
	if ( ! $id ) {
		return;
	}
	$result = '';
	$mocc = wppa( 'mocc' );
	$plus = ( wppa_opt( 'thumbtype' ) == 'masonry-plus' );
	$hor  = ( wppa_opt( 'thumbtype' ) == 'masonry-h' );
	$vert = ( wppa_opt( 'thumbtype' ) == 'masonry-v' );

	// Encrypted photo id
	$xid = wppa_encrypt_photo( $id );

	$cont_width = wppa_get_container_width();
	$count_cols = ceil( $cont_width / wppa_opt( 'thumbsize' ) );

	// Get the photo info
	$thumb 	= wppa_cache_photo( $id );
	if ( ! $thumb ) return '';

	// Get the album info
	if ( $thumb['album'] < '1' ) return ''; // Photo deleted

	$album 	= wppa_cache_album( $thumb['album'] );
	if ( ! $album ) {
		wppa_log('err', 'Photo '.$id.' has non existent album');
		return '';
	}

	// Get photo info
	$is_video 		= wppa_is_video( $id );
	$has_audio 		= wppa_has_audio( $id );
	$imgsrc 		= wppa_get_thumb_path( $id );
	$is_pdf 		= wppa_is_pdf( $id );
	$alt 			= $album['alt_thumbsize'] == 'yes' ? '_alt' : '';
	$imgattr_a 		= wppa_get_imgstyle_a( $id, $imgsrc, wppa_opt( 'thumbsize'.$alt ), 'optional', 'thumb' );

	// Verical style ?
	if ( $vert ) {
		$imgwidth  		= wppa_opt( 'thumbsize' );
		$imgheight 		= $imgwidth * wppa_get_thumbratioyx( $id );
		$imgstyle  		= 'width:100%; height:auto; margin:0; position:relative;box-sizing:border-box;padding:' . ( wppa_opt( 'tn_margin' ) / 2 ) . 'px;';
		$frame_h 		= '';
	}

	// Horizontal style ?
	elseif ( $hor ) {
		$imgheight 		= wppa_opt( 'thumbsize' );
		$imgwidth 		= $imgheight * wppa_get_thumbratioxy( $id );
		$imgstyle  		= 'height:100%;width:auto;margin:0;position:relative;box-sizing:border-box;padding:' . ( wppa_opt( 'tn_margin' ) / 2 ) . 'px;';
		$frame_h 		= 'height:100%; ';
	}

	// Masonry plus
	else {
		$imgwidth  		= wppa_get_thumbx( $id );
		$imgheight 		= wppa_get_thumby( $id );
		$imgstyle  		= 'width:100%;height:auto;margin:0;position:relative;box-sizing:border-box;float:left;padding:' . ( wppa_opt( 'tn_margin' ) / 2 ) . 'px;';
		$frame_h 		= '';
	}

	// Mouseover effect?
	if ( wppa_switch( 'use_thumb_opacity' ) ) {
		$opac = wppa_opt( 'thumb_opacity' );
		$imgstyle .= 'opacity:' . $opac/100 . ';filter:alpha(opacity=' . $opac . ');';
	}

	// Cursor
	$cursor	= $imgattr_a['cursor'];

	// Popup ?
	if ( wppa_use_thumb_popup() ) {

		// Landscape?
		if ( $imgwidth > $imgheight ) {
			$popwidth 	= wppa_opt( 'popupsize' );
			$popheight 	= round( $popwidth * $imgheight / $imgwidth );
		}

		// Portrait
		else {
			$popheight 	= wppa_opt( 'popupsize' );
			$popwidth 	= round( $popheight * $imgwidth / $imgheight );
		}
	}

	// No popup
	else {
		$popwidth 	= $imgwidth;
		$popheight 	= $imgheight;
	}

	$imgurl    	= wppa_get_thumb_url( $id, true, '', $popwidth, $popheight );
	$events    	= wppa_get_imgevents( 'thumb', $id );
	if ( $plus ) {
		$events .= ' onload="jQuery(document).trigger(\'resize\');"';
	}
	$imgalt		= $plus ? '' : ' alt="'.$id.'"';
	$title 		= esc_attr( wppa_get_masonry_title( $id ) );

	// Feed ?
	if ( is_feed() ) {
		$imgattr_a = wppa_get_imgstyle_a( $id, $imgsrc, '100', '4', 'thumb' );
		$style = $imgattr_a['style'];
		$result .= '<a href="' . get_permalink() . '">' .
						'<img' .
							' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
							' ' . $imgalt .
							( $title ? ' title="' . $title . '"' : '' ) .
							' style="'.$style.'"' .
						' />' .
					'</a>';
		return;
	}

	// Get the image link
	if ( wppa( 'is_topten' ) ) {
		$no_album = ! wppa( 'start_album' );
		if ( $no_album ) $tit = __( 'View the top rated photos' , 'wp-photo-album-plus' ); else $tit = esc_attr( __( stripslashes( $thumb['description'] ) ) );
		$link = wppa_get_imglnk_a( 'thumb', $id, '', $tit, '', $no_album );
	}
	else $link = wppa_get_imglnk_a( 'thumb', $id ); // voor parent uplr

	// Open the thumbframe
	// Add class wppa-mas-h-{mocc} for ie if horizontal
	$is_ie_or_chrome = strpos( $_SERVER["HTTP_USER_AGENT"], 'Trident' ) || strpos( $_SERVER["HTTP_USER_AGENT"], 'Chrome' );
	$result .= '
				<div' .
					' id="thumbnail_frame_masonry_' . $xid . '_' . $mocc . '"' .
					( $is_ie_or_chrome && wppa_opt( 'thumbtype' ) == 'masonry-h' ? ' class="wppa-mas-h-' . $mocc . '"' : '' ) .
					' style="' .
						$frame_h .
						( ( wppa_opt( 'thumbtype' ) == 'masonry-plus' ) ?
							'width:100%;' :
							'float:left;' ) .
						'position:static;' .
						'font-size:12px;' .
						'line-height:8px;' .
						'overflow:hidden;' .
						'box-sizing:content-box;' .
					'" >';

	// The medals
	$result .= wppa_get_medal_html_a( array( 'id' => $id, 'size' => 'M', 'where' => 'top', 'thumb' => true ) );

	// See if ajax possible
	if ( $link ) {
		if ( $link['is_url'] ) {	// is url
			if ( wppa_opt( 'thumb_linktype' ) == 'photo' 							// linktype must be to slideshow image
				&& wppa_opt( 'thumb_linkpage' ) == '0'								// same page/post
				&& ! wppa_switch( 'thumb_blank' )									// not on a new tab
				&& ! ( wppa_switch( 'thumb_overrule' ) && $thumb['linkurl'] )		// no ( ps overrule set AND link present )
				&& ! wppa( 'is_topten' )													// no topten selection
				&& ! wppa( 'is_lasten' )													// no lasten selection
				&& ! wppa( 'is_comten' )													// no comten selection
				&& ! wppa( 'is_featen' )
				&& ! wppa( 'is_tag' )													// no tag selection
				&& ! wppa( 'is_upldr' )													// not on uploader deisplay
				&& ! wppa( 'src' )														// no search
				&& ! wppa( 'supersearch' )													// no supersearch
				&& ! wppa( 'is_potdhis' ) 													// not on potd history
//				&& ( wppa_is_int( wppa( 'start_album' ) ) || wppa( 'start_album' ) == '' )	// no set of albums
				 )
			{ 	// Ajax	possible

				// The a img ajax
				$p =  wppa( 'calendar') ? '' : $xid;

				$ajax_url = wppa_get_slideshow_url_ajax( array( 'album' => wppa( 'start_album' ),
																'photo' => $p ) );

				$href_url = wppa_get_slideshow_url( array( 'album' => wppa( 'start_album' ),
														   'photo' => $p ) );

				$onclick = 'wppaDoAjaxRender( ' . $mocc . ', \'' . $ajax_url . '\', \'' . $href_url . '\' ); return false;';

				$result .= '
				<a
					style="position:static;"
					class="thumb-img"
					id="x-'.$id.'-'.$mocc.'"
					href="' . $href_url . '"
					onclick="' . $onclick . '" >';

				if ( $is_video ) {
					$result .= wppa_get_video_html( array(
							'id'			=> $id,
					//		'width'			=> $imgwidth,
					//		'height' 		=> $imgheight,
							'controls' 		=> wppa_switch( 'thumb_video' ),
							'margin_top' 	=> '0',
							'margin_bottom' => '0',
							'tagid' 		=> 'i-'.$xid.'-'.$mocc,
							'cursor' 		=> 'cursor:pointer;',
							'events' 		=> $events,
							'title' 		=> $title,
							'preload' 		=> 'metadata',
					//		'onclick' 		=> $onclick,
							'lb' 			=> false,
							'class' 		=> '',
							'style' 		=> $imgstyle,
							'use_thumb' 	=> true
							));
				}
				else {
					$result .= 	'<img' .
				//					' onclick="' . $onclick . '"' .
									' id="i-' . $xid . '-' . $mocc . '"' .
									' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
									' ' . $imgalt .
									( $title ? ' title="' . $title . '"' : '' ) .
									' style="' . $imgstyle . ' cursor:pointer"' .
									' ' . $events .
								' />';
				}
				$result .= '</a>';
			}
			else { 	// non ajax
				// The a img non ajax
				$result .= '<a style="position:static;" href="'.$link['url'].'" target="'.$link['target'].'" class="thumb-img" id="x-'.$xid.'-'.$mocc.'">';
				if ( $is_video ) {
					$result .= wppa_get_video_html( array(
							'id'			=> $id,
					//		'width'			=> $imgwidth,
					//		'height' 		=> $imgheight,
							'controls' 		=> wppa_switch( 'thumb_video' ),
							'margin_top' 	=> '0',
							'margin_bottom' => '0',
							'tagid' 		=> 'i-'.$xid.'-'.$mocc,
							'cursor' 		=> 'cursor:pointer;',
							'events' 		=> $events,
							'title' 		=> $title,
							'preload' 		=> 'metadata',
							'onclick' 		=> '',
							'lb' 			=> false,
							'class' 		=> '',
							'style' 		=> $imgstyle,
							'use_thumb' 	=> true
							));
				}
				else {
					$result .= 	'<img' .
									' id="i-' . $xid . '-' . $mocc . '"' .
									' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
									' ' . $imgalt .
									( $title ? ' title="' . $title . '"' : '' ) .
									' style="' . $imgstyle . 'cursor:pointer"' .
									' ' . $events .
								' />';
				}
				$result .= '</a>';
			}
		}

		// Link is lightbox
		elseif ( $link['is_lightbox'] ) {

			// The a img
			$title 		= wppa_get_lbtitle( 'thumb', $id );
			$result .= '<a href="'.$link['url'].'"' .
						' data-id="' . wppa_encrypt_photo( $id ) . '"' .
						' target="'.$link['target'].'"' .
						( $is_video ? ' data-videohtml="' . esc_attr( wppa_get_video_body( $id ) ) . '"' .
						' data-videonatwidth="' . wppa_get_videox( $id ) . '"' .
						' data-videonatheight="' . wppa_get_videoy( $id ) . '"' : '' ) .
						( $has_audio ? ' data-audiohtml="' . esc_attr( wppa_get_audio_body( $id ) ) . '"' : '' ) .
						( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $id ) ) .'"' : '' ) .
						' data-rel="wppa[occ'.$mocc . ']"' .
						( $title ? ' ' . 'data-lbtitle' . '="' . $title . '"' : '' ) .
						wppa_get_lb_panorama_full_html( $id ) .
						' class="thumb-img"' .
						' id="x-' . $xid . '-' . $mocc . '"' .
						' data-alt="' . esc_attr( wppa_get_imgalt( $id, true ) ) . '"' .
						' >';

			// The image
			$title = wppa_zoom_in( $id );

			// Video?
			if ( $is_video ) {
				$result .= wppa_get_video_html( array(
						'id'			=> $id,
						'controls' 		=> wppa_switch( 'thumb_video' ),
						'margin_top' 	=> '0',
						'margin_bottom' => '0',
						'tagid' 		=> 'i-'.$xid.'-'.$mocc,
						'cursor' 		=> $cursor,
						'events' 		=> $events,
						'title' 		=> $title,
						'preload' 		=> 'metadata',
						'onclick' 		=> '',
						'lb' 			=> false,
						'class' 		=> '',
						'style' 		=> $imgstyle,
						'use_thumb' 	=> true
						));
			}

			// Image
			else {
				$result .= 	'<img' .
								' id="i-' . $xid . '-' . $mocc . '"' .
								' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
								' ' . $imgalt .
								( $title ? ' title="' . $title . '"' : '' ) .
								' style="' . $imgstyle . $cursor . '"' .
								' ' . $events .
							' />';
			}
			$result .= '</a>';
		}

		// is onclick
		else {

			// The div img
			$result .= '<div onclick="'.$link['url'].'" class="thumb-img" id="x-'.$id.'-'.$mocc.'" style="height:100%">';

			// Video?
			if ( $is_video ) {
				$result .= wppa_get_video_html( array(
						'id'			=> $id,
				//		'width'			=> $imgwidth,
				//		'height' 		=> $imgheight,
						'controls' 		=> wppa_switch( 'thumb_video' ),
						'margin_top' 	=> '0',
						'margin_bottom' => '0',
						'tagid' 		=> 'i-'.$xid.'-'.$mocc,
						'cursor' 		=> 'cursor:pointer;',
						'events' 		=> $events,
						'title' 		=> $title,
						'preload' 		=> 'metadata',
						'onclick' 		=> '',
						'lb' 			=> false,
						'class' 		=> '',
						'style' 		=> $imgstyle,
						'use_thumb' 	=> true
						));
			}

			// Image
			else {
				$result .= 	'<img' .
								' id="i-' . $xid . '-' . $mocc . '"' .
								' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
								' ' . $imgalt .
								( $title ? ' title="' . $title . '"' : '' ) .
								' style="' . $imgstyle . 'cursor:pointer"' .
								' ' . $events .
							' />';
			}

			$result .= '</div>';

			wppa_js( 'wppaPopupOnclick[' . $id . '] = "' . $link['url'] . '";' );
		}
	}
	else {	// no link
		if ( wppa_use_thumb_popup() ) {
			$result .= '<div id="x-'.$id.'-'.$mocc.'" style="height:100%" >';
				if ( $is_video ) {
					$result .= wppa_get_video_html( array(
							'id'			=> $id,
					//		'width'			=> $imgwidth,
					//		'height' 		=> $imgheight,
							'controls' 		=> false,
							'margin_top' 	=> '0',
							'margin_bottom' => '0',
							'tagid' 		=> 'i-'.$xid.'-'.$mocc,
							'cursor' 		=> '',
							'events' 		=> $events,
							'title' 		=> $title,
							'preload' 		=> 'metadata',
							'onclick' 		=> '',
							'lb' 			=> false,
							'class' 		=> '',
							'style' 		=> $imgstyle,
							'use_thumb' 	=> true
							));
				}
				else {
					$result .= 	'<img' .
									' id="i-'.$xid.'-'.$mocc.'"' .
									' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
									' ' . $imgalt .
									( $title ? ' title="' . $title . '"' : '' ) .
									' width="' . $imgwidth . '"' .
									' height="' . $imgheight . '"' .
									' style="' . $imgstyle . '"' .
									' ' . $events .
								' />';
				}
			$result .= '</div>';
		}
		else {
			if ( $is_video ) {
//				$result .= '<video preload="metadata" '.$imgalt.' title="'.$title.'" width="'.$imgwidth.'" height="'.$imgheight.'" style="'.$imgstyle.'" '.$events.' >'.wppa_get_video_body( $id ).'</video>';
				$result .= wppa_get_video_html( array(
						'id'			=> $id,
				//		'width'			=> $imgwidth,
				//		'height' 		=> $imgheight,
						'controls' 		=> false,
						'margin_top' 	=> '0',
						'margin_bottom' => '0',
						'tagid' 		=> 'i-'.$xid.'-'.$mocc,
						'cursor' 		=> '',
						'events' 		=> $events,
						'title' 		=> $title,
						'preload' 		=> 'metadata',
						'onclick' 		=> '',
						'lb' 			=> false,
						'class' 		=> '',
						'style' 		=> $imgstyle,
						'use_thumb' 	=> true
						));
			}
			else {
				$result .= 	'<img' .
								' id="i-'.$xid.'-'.$mocc.'"' .
								' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
								' ' . $imgalt .
								( $title ? ' title="' . $title . '"' : '' ) .
								' width="' . $imgwidth . '"' .
								' height="' . $imgheight . '"' .
								' style="' . $imgstyle . '" ' . $events .
							' />';
			}
		}
	}

				// The audio when no popup
				if ( wppa_switch( 'thumb_audio' ) && wppa_has_audio( $id ) ) {
					$result 	.= '<div style="position:relative;z-index:11;">';
				//	$is_safari 	= strpos( $_SERVER["HTTP_USER_AGENT"], 'Safari' );
				//	$cont_h 	= $is_safari ? 16 : 28;
				//	$audiotop 	= $imgattr_a['height'] + $imgattr_a['margin-top'] - $cont_h;

		//			if ( ! is_file( $imgsrc ) ) { // Audio without image
		//				$audiotop 	= wppa_get_audio_control_height();
		//				$imgwidth 	= wppa_opt( 'tf_width' );
		//				$imgheight 	= wppa_get_audio_control_height();
		//			}
					$result 	.= wppa_get_audio_html( array(
										'id' 		=> $id,
										'tagid' 	=> 'a-'.$xid.'-'.$mocc,
								//		'width'		=> $imgwidth,
								//		'height' 	=> wppa_get_audio_control_height(),
										'style' 	=> 'width:100%;position:absolute;bottom:0;margin:0;padding:'.(wppa_opt( 'tn_margin')/2).'px;left:0;border:none;z-index:10;'
										));

					$result .= '</div>';
				}


	// The medals
	$result .= wppa_get_medal_html_a( array( 'id' => $id, 'size' => 'M', 'where' => 'bot', 'thumb' => true ) );

	// Close the thumbframe
	$result .= '</div>';

	return $result;
}

function wppa_get_masonry_title( $id ) {

	$result = '';

	// Get the photo info
	$thumb 	= wppa_cache_photo( $id );
	if ( ! $thumb ) return '';

	// Get the album info
	$alb = $thumb['album'];
	if ( $alb < '1' ) return ''; // Photo deleted

	$album 	= wppa_cache_album( $alb );
	if ( ! $album ) {
		wppa_log('err', 'Photo '.$id.' has non existent album');
		return '';
	}

	// Name
	if ( wppa_is_item_displayable( $alb, 'name', 'thumb_text_name' ) ) {
		$result .= wppa_get_photo_name( $id, array( 'addowner' => wppa_switch( 'thumb_text_owner' ) ) ) . "\n";
	}

	// Description
	if ( wppa_is_item_displayable( $alb, 'description', 'thumb_text_desc' ) ||
		$thumb['status'] == 'pending' || $thumb['status'] == 'scheduled' ) {
		$result .= wppa_get_photo_desc( $id, array( 'doshortcodes' => wppa_switch( 'allow_foreign_shortcodes_thumbs' ) ) ) . "\n";
	}

	// Rating
	if ( wppa_is_item_displayable( $alb, 'rating', 'thumb_text_rating' ) ) {
		$rating = wppa_get_rating_by_id( $id );
		if ( $rating && wppa_switch( 'show_rating_count' ) ) {
			$result .= ' ( '.wppa_get_rating_count_by_id( $id ).' )' . "\n";
		}
	}

	// Viewcount
	if ( wppa_switch( 'thumb_text_viewcount' ) ) {
		$result .= sprintf( _n( '%d view', '%d views', $thumb['views'], 'wp-photo-album-plus' ), $thumb['views'] );
	}

	$result = strip_tags( rtrim( $result, "\n" ) );
	return $result;
}

// Do the widget thumb
function wppa_do_the_widget_thumb( $type, $image, $album, $display, $link, $title, $imgurl, $imgstyle_a, $imgevents ) {
global $widget_content;

	$result = wppa_get_the_widget_thumb( $type, $image, $album, $display, $link, $title, $imgurl, $imgstyle_a, $imgevents );
	$widget_content .= $result;
}

// Get the widget thumbnail html
function wppa_get_the_widget_thumb( $type, $image, $album, $display, $link, $title, $imgurl, $imgstyle_a, $imgevents ) {

	// Init
	$result = '';
	$mocc = wppa( 'mocc' );

	// Get the id
	$id = $image ? $image['id'] : '0';

	// Encrypted photo id
	$xid = wppa_encrypt_photo( $id );

	// Fix url if audio
	if ( wppa_has_audio( $id ) ) {
		$imgurl = wppa_fix_poster_ext( $imgurl, $id );
	}

	// Is it a video?
	$is_video = $id ? wppa_is_video( $id ) : false;

	// Get the video and audio bodies
	$videobody = $id ? wppa_get_video_body( $id ) : '';
	$audiobody = $id ? wppa_get_audio_body( $id ) : '';
	$is_pdf    = $id ? wppa_is_pdf( $id ) : '';

	// Open container if an image must be displayed
	if ( $display == 'thumbs' ) {
		$size = max( $imgstyle_a['width'], $imgstyle_a['height'] );
		$result .= '<div style="width:' . strval( intval( $size ) ) . 'px;height:' . strval( intval( $size ) ) . 'px;overflow:hidden">';
	}

	// The medals if on top
	if ( $display == 'thumbs' ) {
		$result .= $id ? wppa_get_medal_html_a( array( 'id' => $id, 'size' => 'S', 'where' => 'top', 'thumb' => true ) ) : '';
	}

	// Get the name
	$name = $id ? wppa_get_photo_name( $id ) : '';

	if ( $link ) {
		if ( $link['is_url'] ) {	// Is a href
			$result .= '
			<a href="' . esc_url( $link['url'] ) . '" title="' . esc_attr( $title ) . '" target="' . esc_attr( $link['target'] ) . '">';
				if ( $display == 'thumbs' ) {
					if ( $is_video ) {
						$result .= wppa_get_video_html( array(
							'id'			=> $id,
							'width'			=> $imgstyle_a['width'],
							'height' 		=> $imgstyle_a['height'],
							'controls' 		=> false,
							'margin_top' 	=> $imgstyle_a['margin-top'],
							'margin_bottom' => $imgstyle_a['margin-bottom'],
							'tagid' 		=> 'i-' . $xid . '-' . $mocc,
							'cursor' 		=> 'cursor:pointer;',
							'events' 		=> $imgevents,
							'title' 		=> $title,
						) );
					}
					else {
						$result .= 	'
						<img
							id="i-' . $xid . '-' . $mocc . '" ' .
							( $title ? ' title="' . esc_attr( $title ) . '" ' : '' ) .
							( wppa_lazy() ? 'data-' : '' ) . 'src="' . esc_url( $imgurl ) . '"
							style="' . $imgstyle_a['style'] . 'cursor:pointer" ' .
							$imgevents . ' ' .
							wppa_get_imgalt( $id ) . '
							>';
					}
				}
				else {
					$result .= $name;
				}
			$result .= "\n\t" . '</a>';
		}
		elseif ( $link['is_lightbox'] ) {
			$title 		= wppa_get_lbtitle( 'thumb', $id );
			$videohtml 	= esc_attr( $videobody );
			$audiohtml 	= esc_attr( $audiobody );
			$result .= 	'<a href="' . $link['url'] . '"' .
						' data-id="' . wppa_encrypt_photo( $id ) . '"' .
						( $videohtml ? ' data-videohtml="' . $videohtml . '"' .
							' data-videonatwidth="'.wppa_get_videox( $id ).'"' .
							' data-videonatheight="'.wppa_get_videoy( $id ).'"' : '' ) .
						( $audiohtml ? ' data-audiohtml="' . $audiohtml . '"' : '' ) .
						( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $id ) ) .'"' : '' ) .
						' data-rel="wppa[' . $type . '-' . $album . '-' . $mocc . ']"' .
						( $title ? ' ' . 'data-lbtitle' . '="' . $title . '"' : '' ) .
						wppa_get_lb_panorama_full_html( $id ) .
						' target="' . $link['target'] . '"' .
						' data-alt="' . esc_attr( wppa_get_imgalt( $id, true ) ) . '"' .
						' style="cursor:' . wppa_wait() . ';"' .
						' onclick="return false;"' .
						' >';
				$result .= "\n\t\t";
				if ( $display == 'thumbs' ) {
					$title = wppa_zoom_in( $id );
					if ( $is_video ) {
						$result .= wppa_get_video_html( array(
							'id'			=> $id,
							'width'			=> $imgstyle_a['width'],
							'height' 		=> $imgstyle_a['height'],
							'controls' 		=> false,
							'margin_top' 	=> $imgstyle_a['margin-top'],
							'margin_bottom' => $imgstyle_a['margin-bottom'],
							'tagid' 		=> 'i-' . $xid . '-' . $mocc,
						//	'cursor' 		=> $imgstyle_a['cursor'],
							'events' 		=> $imgevents,
							'title' 		=> $title
						) );
					}
					else {
						$result .= 	'<img' .
										' id="i-' . $xid . '-' . $mocc . '"' .
										( $title ? ' title="' . $title . '"' : '' ) .
										' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
										' width="' . $imgstyle_a['width'] . '"' .
										' height="' . $imgstyle_a['height'] . '"' .
										' style="' . $imgstyle_a['style'] . '"' .
										' ' . $imgevents .
										' ' . wppa_get_imgalt( $id ) .
										' />';
					}
				}
				else {
					$result .= $name;
				}
			$result .= "\n\t" . '</a>';
		}
		else { // Is an onclick unit
			$result .= "\n\t";
			if ( $display == 'thumbs' ) {
				if ( $is_video ) {
					$result .= wppa_get_video_html( array(
							'id'			=> $id,
							'width'			=> $imgstyle_a['width'],
							'height' 		=> $imgstyle_a['height'],
							'controls' 		=> false,
							'margin_top' 	=> $imgstyle_a['margin-top'],
							'margin_bottom' => $imgstyle_a['margin-bottom'],
							'tagid' 		=> 'i-' . $xid . '-' . $mocc,
							'cursor' 		=> 'cursor:pointer;',
							'events' 		=> $imgevents,
							'title' 		=> $title,
							'onclick' 		=> $link['url']
						) );
				}
				else {
					$result .= 	'<img' .
									' id="i-' . $xid . '-' . $mocc . '"' .
									( $title ? ' title="' . $title . '"' : '' ) .
									' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $imgurl . '"' .
									' width="' . $imgstyle_a['width'] . '"' .
									' height="' . $imgstyle_a['height'] . '"' .
									' style="' . $imgstyle_a['style'] . ' cursor:pointer"' .
									' ' . $imgevents .
									' onclick="' . $link['url'] . '"' .
									' ' . wppa_get_imgalt( $id ) .
									' />';
				}
			}
			else {
				$result .= 	'<a' .
								' style="cursor:pointer"' .
								' onclick="' . $link['url'] . '"' .
								' >' . $name .
							'</a>';

			}
		}
	}
	else {	// No link
		$result .= "\n\t";
		if ( $display == 'thumbs' ) {
			if ( $is_video ) {
				$result .= wppa_get_video_html( array(
						'id'			=> $id,
						'width'			=> $imgstyle_a['width'],
						'height' 		=> $imgstyle_a['height'],
						'controls' 		=> false,
						'margin_top' 	=> $imgstyle_a['margin-top'],
						'margin_bottom' => $imgstyle_a['margin-bottom'],
						'tagid' 		=> 'i-' . $xid . '-' . $mocc,
						'cursor' 		=> 'cursor:pointer;',
						'events' 		=> $imgevents,
						'title' 		=> $title
					) );
			}
			else {
				$result .= 	'<img' .
								' id="i-' . $xid . '-' . $mocc . '"' .
								( $title ? ' title="' . esc_attr( $title ) . '"' : '' ) .
								' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . esc_url( $imgurl ) . '"' .
								' width="' . $imgstyle_a['width'] . '"' .
								' height="' . $imgstyle_a['height'] . '"' .
								' style="' . $imgstyle_a['style'] . '"' .
								' ' . $imgevents .
								' ' . wppa_get_imgalt( $id ) .
								' />';
			}
		}
		else {
			$result .= $name;
		}
	}

	// The medals if at the bottom
	if ( $display == 'thumbs' ) {
		$result .= $id ? wppa_get_medal_html_a( array( 'id' => $id, 'size' => 'S', 'where' => 'bot', 'thumb' => true ) ) : '';
	}

	// Close container
	if ( $display == 'thumbs' ) {
		$result .= '</div>';
	}

	return $result;
}

// The filmstrip thumbnail image
// $idx = index in filmstrip
// $ambule = 'pre', '', or 'post'
// $glue = bool, Set to true only at thumbs where the right border should be the glue line.
function wppa_do_filmthumb( $id, $idx, $ambule = false, $glue = false ) {
static $seqno;

	if ( ! $seqno ) $seqno = 0;

	$thumb 		= wppa_cache_photo( $id );
	if ( ! $thumb ) {
		wppa_log('err', 'Missing photo info in do_filmthumb: '.$i);
		return '';
	}
	$mocc 		= wppa( 'mocc' );
	$result 	= '';
	$src 		= wppa_get_thumb_path( $thumb['id'] );
	$max_size 	= wppa_opt( 'film_thumbsize' );
	if ( wppa_in_widget() ) $max_size /= 2;
	$com_alt 	= wppa( 'is_comten' ) && wppa_switch( 'comten_alt_display' ) && ! wppa_in_widget();

	$imgattr_a 	= wppa_get_imgstyle_a( $thumb['id'], $src, $max_size, 'optional', 'fthumb' );
	$imgstyle  	= $imgattr_a['style'];
	$imgwidth  	= $imgattr_a['width'];
	$imgheight 	= $imgattr_a['height'];
	$cursor    	= $imgattr_a['cursor'];
	$url 		= wppa_get_thumb_url( $thumb['id'], true, '', $imgwidth, $imgheight, true );
	$furl 		= wppa_switch( 'lb_hres' ) ? wppa_get_hires_url( $thumb['id'] ) : wppa_get_photo_url( $thumb['id'] );
	$events 	= wppa_get_imgevents( 'film', $thumb['id'], 'nopopup', $idx );
	$thumbname 	= wppa_get_photo_name( $thumb['id'] );
	$target 	= wppa_switch( 'film_blank' ) || ( $thumb['linktarget'] == '_blank' ) ? 'target="_blank" ' : '';
	$psotitle 	= $thumb['linktitle'] ? 'title="'.esc_attr($thumb['linktitle']).'" ' : '';
	$psourl 	= wppa_switch( 'film_overrule' ) && $thumb['linkurl'] ? 'href="'.$thumb['linkurl'].'" '.$target.$psotitle : '';
	$imgalt	 	= wppa_get_imgalt( $thumb['id'] );
	$is_pdf 	= wppa_is_pdf( $id );
	$film_type  = wppa_opt( 'film_type' );
	$aspect 	= wppa_opt( 'film_aspect' );

	if ( ! $ambule ) {
		$ambule = 'film';
	}

	if ( wppa_opt( 'film_linktype' ) != 'lightbox' ) {
		$events .= ' onclick="wppaGotoKeepState( '.$mocc.', '.$idx.' )"';
		$events .= ' ondblclick="wppaStartStop( '.$mocc.', -1 )"';
	}

	// pre-ambule images transfer click to their originals on lightbox link
	elseif ( $ambule !== 'film' ) {
		$events .= ' onclick="jQuery(\'#wppa-film-' . $idx . '-' . $mocc . '\').trigger(\'click\');' .
							 'jQuery(\'#wppa-film-a-' . $idx . '-' . $mocc . '\').trigger(\'click\');"';
		$cursor = ' cursor:url( ' .wppa_get_imgdir() . wppa_opt( 'magnifier' ) . ' ),pointer;';
	}

	if ( is_feed() ) {
		if ( $ambule == 'film' ) {
			$style_a = wppa_get_imgstyle_a( $thumb['id'], $src, '100', '4', 'thumb' );
			$style = $style_a['style'];
			$result .= 	'
			<a href="' . get_permalink() . '">
				<img
					src="' . $url . '" ' .
					$imgalt . '
					title="' . $thumbname . '"
					style="' . $style . '"
				>
			</a>';
		}
	}
	else {

		$result .= 	'
		<div
			id="' . $ambule . '_wppatnf_' . wppa_encrypt_photo( $thumb['id'] ) . '_' . $mocc . '"
			class="thumbnail-frame wppa-' . $ambule . '-' . $mocc . '"
			style="' . wppa_get_thumb_frame_style( $glue, 'film' ) . '"
			>';

		if ( $psourl ) {	// True only when pso activated and data present
			$result .= '<a '. $psourl . '>';	// $psourl contains url, target and title
		}
		elseif ( wppa_opt( 'film_linktype' ) == 'lightbox' && $ambule == 'film' ) {
			$title 		= wppa_get_lbtitle( 'slide', $thumb['id'] );
			$videohtml 	= esc_attr( wppa_get_video_body( $thumb['id'] ) );
			$audiohtml 	= esc_attr( wppa_get_audio_body( $thumb['id'] ) );
			$result .= 	'<a href="' . $furl . '"' .
							' id="wppa-film-a-'.$idx.'-'.$mocc.'"' .
							' data-id="' . wppa_encrypt_photo( $thumb['id'] ) . '"' .
							( $videohtml ? ' data-videohtml="' . $videohtml . '"' .
							' data-videonatwidth="' . wppa_get_videox( $thumb['id'] ) . '"' .
							' data-videonatheight="' . wppa_get_videoy( $thumb['id'] ) . '"' : '' ) .
							( $audiohtml ? ' data-audiohtml="' . $audiohtml . '"' : '' ) .
							( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $id ) ) .'"' : '' ) .
							' data-rel="wppa[occ'.$mocc . ']"' .
							( $title ? ' ' . 'data-lbtitle' . '="' . $title . '"' : '' ) .
							wppa_get_lb_panorama_full_html( $id ) .
							' onclick="if(_wppaSSRuns[' . $mocc . ']){wppaStopShow(' . $mocc . ');document.onLbquitMocc=' . $mocc . ';document.onLbquitIdx=' . $idx . ';};return true;"' .
							'>';
		}

		// Video
		if ( wppa_is_video( $thumb['id'] ) && ! wppa_is_file( $src ) ) {

			// Video with normal type filmthumbs
			if ( $film_type == 'normal' ) {
				$result .= wppa_get_video_html( array( 	'id' 			=> $thumb['id'],
																'width' 		=> $imgattr_a['width'],
																'height' 		=> $imgattr_a['height'],
																'controls' 		=> false,
																'margin_top' 	=> $imgattr_a['margin-top'],
																'margin_bottom' => $imgattr_a['margin-bottom'],
																'cursor' 		=> $imgattr_a['cursor'],
																'events' 		=> $events,
																'tagid' 		=> 'wppa-'.$ambule.'-'.$idx.'-'.$mocc
															 )
													 );
			}

			// Video with canvas type filmthumbs
			else {
				$seqno++;
				$imgstyle 	= 'width:' . $max_size . 'px;height:' . floor( $max_size / $aspect ) . 'px;';
				$tagid 		= 'wppa-' . $ambule . '-' . $idx . '-' . $seqno . '-' . $mocc;
				$thumbx 	= $thumb['videox'];
				$thumby 	= $thumb['videoy'];
				$canvasx 	= $max_size;
				$canvasy 	= strval( floor( $max_size / $aspect ) );
				$the_js 	= "jQuery(document).ready(function(){wppaFilmThumbToCanvas('".$tagid."');});";
				$result .= '
				<canvas
					id="'.$tagid.'-canvas"
					style="' . $imgstyle . $cursor . '"
					width="'.$max_size.'"
					height="'.strval(intval($max_size / $aspect)).'"' .
					$events . '
					data-title="' . ( $psourl ? esc_attr( $thumb['linktitle'] ) : '' ) . '"
					>
				</canvas>
				<video
					id="' . $tagid . '"
					class="wppa-' . $ambule . '-' . $mocc . '"
					style="width:0;height:0;"
					onplaying="console.log(\'Playing\');' . $the_js . '"
					onplay="console.log(\'Play\');' . $the_js . '"
					onmetadataloaded="console.log(\'Metadataloaded\');' . $the_js . '"
					oncanplay="console.log(\'Canplay\');' . $the_js . '"
					onpause="console.log(\'Pause\');"
					muted autoplay
					>' .
					wppa_get_video_body( $thumb['id'] ) . '
				</video>';
			}
		}

		// Photo or video with poster
		else {

			// Photo with normal type filmthumbs
			if ( $film_type == 'normal' ) {
				$result .=  '
				<img
					id="wppa-' . $ambule . '-' . $idx . '-' . $mocc . '"
					class="wppa-' . $ambule . '-' . $mocc . '"' .
					' ' . ( ( wppa_lazy() && $ambule == 'film' && $idx > '5' ) ? 'data-' : '' ) . 'src="' . $url . '"' .
					$imgalt . '
					style="' . $imgstyle . $cursor . '"' .
					$events . '
					data-title="' . ( $psourl ? esc_attr( $thumb['linktitle'] ) : '' ) . '"
				>';
			}

			// Photo with canvas type filmthumbs
			else {
				$seqno++;
				$imgstyle 	= 'width:' . $max_size . 'px;height:' . floor( $max_size / $aspect ) . 'px;';
				$tagid 		= 'wppa-' . $ambule . '-' . $idx . '-' . $seqno . '-' . $mocc;
				$thumbx 	= wppa_get_thumbx( $id, 'force' );
				$thumby 	= wppa_get_thumby( $id, 'force' );
				$canvasx 	= $max_size;
				$canvasy 	= strval( floor( $max_size / $aspect ) );
				$the_js 	= "jQuery(document).ready(function(){wppaFilmThumbToCanvas('".$tagid."');});";
				$result .= '
				<canvas
					id="'.$tagid.'-canvas"
					style="' . $imgstyle . $cursor . '"
					width="'.$max_size.'"
					height="'.strval(intval($max_size / $aspect)).'"' .
					$events . '
					data-title="' . ( $psourl ? esc_attr( $thumb['linktitle'] ) : '' ) . '"
					>
				</canvas>
				<img
					id="' . $tagid . '"
					src="' . $url . '"
					style="position:fixed;width:'.$thumbx.'px;height:'.$thumby.'px;display:none;"
					onload="'.$the_js.'"
				>';
			}
		}

		if ( $psourl ) {	// True only when pso activated and data present
			$result .= '</a>';	// $psourl contains url, target and title
		}
		elseif ( wppa_opt( 'film_linktype' ) == 'lightbox' && $ambule == 'film' ) {
			$result .= '</a>';
		}

		$result .= '</div>';
	}

	wppa_out( $result );
}

// The medals
function wppa_get_medal_html_a( $args ) {

	// Completize args
	$args = wp_parse_args( (array) $args, array(
											'id' 	=> '0',
											'size' 	=> 'M',
											'where' => '',
											'thumb' => false,
											) );

	// Validate args
	if ( $args['id'] == '0' ) return '';													// Missing required id
	if ( ! in_array( $args['size'], array( 'S', 'M', 'L', 'XL' ) ) ) return ''; 			// Missing or not implemented size spec
	if ( ! in_array( $args['where'], array( 'top', 'bot' ) ) ) return ''; 					// Missing or not implemented where

	// Do it here?
	if ( strpos( wppa_opt( 'medal_position' ), $args['where'] ) === false ) return ''; // No

	// Get rquired photo and config data
	$id 	= $args['id'];
	$new 	= wppa_is_photo_new( $id );
	$mod 	= wppa_is_photo_modified( $id );
	$status	= wppa_get_photo_item( $id, 'status' );
	$medal 	= in_array ( $status, array( 'gold', 'silver',  'bronze' ) ) ? $status : '';
	$first 	= wppa_is_photo_first( $id );

	if ( $args['thumb'] && wppa_get_ext( wppa_get_photo_item( $id, 'filename' ) ) == 'pdf' ) {
		$mmitem = 'pdf';
	}
	elseif ( $args['thumb'] && wppa_has_audio( $id ) && ! wppa_switch( 'thumb_audio' ) ) {
		$mmitem = 'audio';
	}
	elseif ( $args['thumb'] && wppa_is_video( $id ) && ! wppa_switch( 'thumb_video' ) ) {
		$mmitem = 'video';
	}
	else {
		$mmitem = '';
	}

	// Have a medal to show?
	if ( ! $new && ! $medal && ! $mod && ! $first && ! $mmitem ) {
		return '';																			// No
	}

	// Init local vars
	$result = '';
	$color 	= wppa_opt( 'medal_color' );
	$left 	= strpos( wppa_opt( 'medal_position' ), 'left' ) !== false;
	$ctop 	= strpos( wppa_opt( 'medal_position' ), 'top' ) === false ? '-32' : '0';
	$sizes 	= array(
		'S' 	=> '16',
		'M' 	=> '20',
		'L' 	=> '24',
		'XL' 	=> '32'
		);
	$nsizes 	= array(
		'S' 	=> '14',
		'M' 	=> '16',
		'L' 	=> '20',
		'XL' 	=> '24'
		);
	$fsizes 	= array(
		'S' 	=> '9',
		'M' 	=> '10',
		'L' 	=> '14',
		'XL' 	=> '20'
		);
	$smargs = array(
		'S' 	=> '4',
		'M' 	=> '5',
		'L' 	=> '6',
		'XL' 	=> '8'
		);
	$lmargs = array(
		'S' 	=> '22',
		'M' 	=> '28',
		'L' 	=> '36',
		'XL' 	=> '48'
		);
	$tops = array(
		'S' 	=> '8',
		'M' 	=> '8',
		'L' 	=> '6',
		'XL' 	=> '0'
		);
	$ntops = array(
		'S' 	=> '10',
		'M' 	=> '10',
		'L' 	=> '8',
		'XL' 	=> '0'
		);
	$titles = array(
		'gold' 		=> __('Gold medal', 'wp-photo-album-plus' ),
		'silver' 	=> __('Silver medal', 'wp-photo-album-plus' ),
		'bronze' 	=> __('Bronze medal', 'wp-photo-album-plus' ),
		'pdf' 		=> __('Document', 'wp-photo-album-plus' ),
		'audio' 	=> __('Audio', 'wp-photo-album-plus' ),
		'video' 	=> __('Video', 'wp-photo-album-plus' ),
		);
	$size 	= $sizes[$args['size']];
	$nsize 	= $nsizes[$args['size']];
	$fsize 	= $fsizes[$args['size']];
	$smarg  = $smargs[$args['size']];
	$lmarg  = $lmargs[$args['size']];
	$top 	= $tops[$args['size']];
	$ntop 	= $ntops[$args['size']];
	$title 	= $medal ? esc_attr( $titles[$medal] ) : '';
	$mstyle = $left ? 'left:'.$smarg.'px;' : 'right:'.$smarg.'px;';
	$sstyle = $left ? 'left:'.($mmitem?$smarg+$size:$smarg).'px;' : 'right:'.($mmitem?$smarg+$size:$smarg).'px;';
	$lstyle = $left ? 'left:'.($mmitem?$lmarg+$size:$lmarg).'px;' : 'right:'.($mmitem?$lmarg+$size:$lmarg).'px;';

	// The medal container
	$result .= '<div style="position:relative;top:'.$ctop.'px;z-index:10;">';

	// The medal pdf-audio-video
	if ( in_array( $mmitem, array( 'pdf', 'audio', 'video' ) ) ) {

		switch( $mmitem ) {
			case 'pdf':   $url = WPPA_UPLOAD_URL . '/icons/' . wppa_opt( 'document_icon' ); break;
			case 'audio': $url = WPPA_UPLOAD_URL . '/icons/' . wppa_opt( 'audio_icon' ); break;
			case 'video': $url = WPPA_UPLOAD_URL . '/icons/' . wppa_opt( 'video_icon' ); break;
			default: $url = ''; 	// Should never get here
		}
		$result .= 	'<img' .
						' src="' . esc_attr( $url ) . '"' .
						' title="' . $titles[$mmitem] . '"' .
						' alt="' . $titles[$mmitem] . '"' .
						' style="' . $mstyle .
							'top:4px;' .
							'position:absolute;' .
							'border:none;' .
							'margin:0 '.($left?'2':'-2').'px;' .
							'padding:0;' .
							'box-shadow:none;' .
							'height:'  . $size . 'px;' .
							'width:' .$size . 'px;' .
							'top:' . $top . 'px;' .
							'background-color:white' .
							'"' .
					' />';
	}

	// The medal bronze-silver-gold
	if ( $medal ) {
		$result .= 	'<img' .
						' src="' . WPPA_URL . '/img/medal_' . $medal . '_' . $color .'.png"' .
						' title="' . $title . '"' .
						' alt="' . $title . '"' .
						' style="' . $sstyle .
							'top:4px;' .
							'position:absolute;' .
							'border:none;' .
							'margin:0;' .
							'padding:0;' .
							'box-shadow:none;' .
							'height:'  .$size . 'px;' .
							'top:' . $top . 'px;' .
							'"' .
					' />';
	}

	// Is there a new or modified indicator to display?
	if ( $first && wppa_switch( 'show_first' ) ) {
		$type = 'first';
		$attr = __( 'First', 'wp-photo-album-plus' );
	}
	elseif ( $new ) {
		$type = 'new';
		$attr = __( 'New', 'wp-photo-album-plus' );
	}
	elseif ( $mod ) {
		$type = 'mod';
		$attr = __( 'Modified', 'wp-photo-album-plus' );
	}
	else {
		$type = '';
		$attr = '';
	}

	// Style adjustment if only a new/modified without a real medal
	if ( ! $medal ) {
		$lstyle = $sstyle;
	}

	$do_image = ! wppa_switch( 'new_mod_label_is_text' );

	// Yes there is one to display
	if ( $type ) {
		if ( $do_image ) {
			$result .= 	'
			<img
				src="' . wppa_opt($type.'_label_url') . '"
				title="' . esc_attr( $attr ) . '"
				alt="' . esc_attr( $attr ) . '"
				class="wppa-thumbnew wppa-'.$type.'-image"
				style="' . $lstyle . '
				top:' . $ntop . 'px;
				position:absolute;
				border:none;
				margin:0;
				padding:0;
				box-shadow:none;
				height:' . $nsize . 'px;"
			/>';
		}
		else {
			$result .= 	'
			<div
				class="wppa-'.$type.'-text"
				style="' . $lstyle . '
					position:absolute;
					top:' . $ntop . 'px;
					box-sizing:border-box;
					float:' . ( $left ? 'left;' : 'right;' ) . '
					font-size:' . $fsize . 'px;
					line-height:' . $fsize . 'px;
					font-family:\'Arial Black\', Gadget, sans-serif;
					border-radius:2px;
					border-width:1px;
					border-style:solid;
					padding:1px;' .
					wppa_get_text_medal_color_style( $type ) . '"
				>
				&nbsp;' . __( wppa_opt( $type.'_label_text' ) ) . '&nbsp;
			</div>';
		}
	}

	// Close container
	$result .= '</div>';

	return $result;
}
