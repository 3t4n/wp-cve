<?php
/* wppa-picture.php
* Package: wp-photo-album-plus
*
* Make the picture html
* Version 8.5.03.006
*
*/


// This function creates the html for the picture. May be photo, video, audio or photo with audio.
// The size will always be set to 100% width, so the calling wrapper div should take care of sizing.
// This function can be used for both resposive and static displays.
//
// Minimum requirements for input args:
//
// - id, The photo id ( numeric photo db table id )
// - type, Any one of the supported display types: sphoto, mphoto, xphoto, ( to be extended )
//
// Optional args:
//
// - class: Any css class specification.
//
// Returns: The html, or false on error.
// In case of error a red debug message will be printed directly to the output stream.
//
// Additional action: viewcount is bumped by this function if the displayed image is not a thumbnail sized one.
//
function wppa_get_picture_html( $args ) {

	// Init
	$defaults 	= array( 	'id' 		=> '0',
							'type' 		=> '',
							'class' 	=> '',
							'width' 	=> false,
							'height' 	=> false,
						);
	$args 		= wp_parse_args( $args, $defaults );

	$id 		= strval( intval( $args['id'] ) );

	// Does the photo exist?
	if ( ! wppa_photo_exists( $id ) ) {
		wppa_log( 'Err', 'Photo ' . $id . ' does not exist in call to wppa_get_picture_html(). Type = ' . $args['type'] );
		return false;
	}

	// May the current user see this photo?
	if ( ! wppa_is_photo_visible( $id ) ) {
		return '';
	}

	$type 		= $args['type'];
	$class 		= $args['class'];
	$mocc 		= wppa( 'mocc' );
	$width 		= $args['width'];

	if ( wppa_is_pdf( $id ) ) {
		$class = trim( $class . ' smxpdf-' . $mocc );
		$is_pdf = true;
	}
	else {
		$is_pdf = false;
	}

	// Check existance of required args
	foreach( array( 'id', 'type' ) as $item ) {
		if ( ! $args[$item] ) {
			wppa_log( 'Err', 'Missing ' . $item . ' in call to wppa_get_picture_html()' );
			return false;
		}
	}

	// Check validity of args
	$types = array(	'sphoto', 		// Single image with optional border like slideshow border
					'mphoto',		// Media type like single image. Caption should be provided in wrappping div
					'xphoto',		// Like xphoto with extended features
					'cover', 		// Album cover image
					'thumb',		// Normal tumbnail
					'ttthumb',		// Topten
					'comthumb',		// Comment widget
					'fthumb',		// Filmthumb
					'twthumb',		// Thumbnail widget
					'ltthumb',		// Lasten widget
					'albthumb',		// Album widget
					);

	if ( ! in_array( $type, $types ) ) {
		wppa_log( 'Err', 'Unimplemented type ' . $type . ' in call to wppa_get_picture_html()' );
		return false;
	}

	// Get other data
	$link 		= wppa_get_imglnk_a( $type, $id );
	$isthumb 	= strpos( $type, 'thumb' ) !== false;
	if ( $isthumb ) {
		$file = wppa_get_thumb_path( $id );
	}
	elseif ( wppa_is_zoomable( $id ) ) {
		$file = wppa_get_o1_source_path( $id );
		if ( ! wppa_is_file( $file ) ) {
			$file = wppa_get_source_path( $id );
			if ( ! wppa_is_file( $file ) ) {
				$file = wppa_get_photo_path( $id );
			}
		}
	}
	else {
		$file = wppa_get_photo_path( $id );
	}

	if ( $args['width'] && $args['height'] ) {
		$href 	= $isthumb ? wppa_get_thumb_url( $id, true, '', $args['width'], $args['height'] ) :
		wppa_get_photo_url( $id, true, '', $args['width'], $args['height'] );
	}
	else {
		$href 	= $isthumb ? wppa_get_thumb_url( $id ) : wppa_get_photo_url( $id );
	}
	if ( $is_pdf || ( wppa_is_zoomable( $id ) && ! $isthumb ) ) {
		$href = wppa_get_hires_url( $id );
	}

	$autocol 	= wppa( 'auto_colwidth' ) || ( wppa( 'fullsize' ) > 0 && wppa( 'fullsize' ) <= 1.0 );
	$title 		= $link ? esc_attr( $link['title'] ) : esc_attr( stripslashes( wppa_get_photo_name( $id ) ) );
	$alt 		= wppa_get_imgalt( $id );

	// Find image style
	switch ( $type ) {
		case 'sphoto':
			$style = 'width:100%;margin:0;';
			if ( ! wppa_in_widget() ) {
				switch ( wppa_opt( 'fullimage_border_width' ) ) {
					case '':
						$style .= 	'padding:0;' .
									'border:none;';
						break;
					case '0':
						$style .= 	'padding:0;' .
									'border:1px solid ' . wppa_opt( 'bcolor_fullimg' ) . ';' .
									'box-sizing:border-box;';
						break;
					default:
						$style .= 	'padding:' . ( wppa_opt( 'fullimage_border_width' ) - '1' ) . 'px;' .
									'border:1px solid ' . wppa_opt( 'bcolor_fullimg' ) . ';' .
									'box-sizing:border-box;' .
									'background-color:' . wppa_opt( 'bgcolor_fullimg' ) . ';';

						// If we do round corners...
						if ( wppa_opt( 'bradius' ) > '0' ) {

							// then also here
							$style .= 'border-radius:' . wppa_opt( 'fullimage_border_width' ) . 'px;';
						}
				}
			}
			break;
		case 'mphoto':
		case 'xphoto':
			$style = 'width:100%;margin:0;padding:0;border:none;';
			break;
		default:
			wppa_log( 'Err', 'Style for type ' . $type . ' is not implemented yet in wppa_get_picture_html()' );
			return false;
	}

	if ( wppa_has_audio( $id ) ) {
		$style .= 'min-height:' . wppa_get_audio_control_height() . 'px;';
	}

	if ( $link && $link['is_lightbox'] ) {
		$title = wppa_zoom_in( $id );
	}

	// Create the html. To prevent mis-alignment of the audio control bar or to escape from the <a> tag for the pan controlbar
	// we wrap it in a div with zero fontsize and lineheight.
	$result = '<div style="font-size:0;line-height:0">';

	if ( $is_pdf && $mocc ) {
		wppa_js( 'wppaAutoColumnWidth[' . $mocc . '] = true;wppaAutoColumnFrac[' . $mocc . ']=1;wppaTopMoc=Math.max(wppaTopMoc,' . $mocc . ');' );
	}

	// The link
	if ( $link ) {

		// Link is lightbox
		if ( $link['is_lightbox'] ) {
			$lbtitle 	= wppa_get_lbtitle( $type, $id );
			$videobody 	= esc_attr( wppa_get_video_body( $id ) );
			$audiobody 	= esc_attr( wppa_get_audio_body( $id ) );
			$videox 	= wppa_get_videox( $id );
			$videoy 	= wppa_get_videoy( $id );

			if ( $type == 'photo' || $type == 'mphoto' || $type == 'xphoto' ) {
				$setname = '['.$type.']';
			}
			else {
				$setname = '';
			}

			$result .=
			'<a' .
				' data-id="' . wppa_encrypt_photo( $id ) . '"' .
				' href="' . $link['url'] . '"' .
				( $lbtitle ? ' ' . 'data-lbtitle' . '="'.esc_attr($lbtitle).'"' : '' ) .
				( wppa_is_zoomable( $id ) ? ' data-pantype="zoom"' : '' ) .
				( $videobody ? ' data-videohtml="' . $videobody . '"' : '' ) .
				( $audiobody ? ' data-audiohtml="' . $audiobody . '"' : '' ) .
				( $is_pdf ? ' data-pdfhtml="' . esc_attr( wppa_get_pdf_html( $id ) ) .'"' : '' ) .
				( $videox ? ' data-videonatwidth="' . $videox . '"' : '' ) .
				( $videoy ? ' data-videonatheight="' . $videoy . '"' : '' ) .
				' data-rel="wppa'.$setname.'"' .
				wppa_get_lb_panorama_full_html( $id ) .
				( $link['target'] ? ' target="' . $link['target'] . '"' : '' ) .
				' class="thumb-img"' .
				' id="a-' . $id . '-' . $mocc . '"' .
				' data-alt="' . esc_attr( wppa_get_imgalt( $id, true ) ) . '"' .
				' style="cursor:' . wppa_wait() . ';"' .
				' onclick="return false;"' .
			' >';
		}

		// Link is NOT lightbox
		else {
			$result .= '
			<a
				ontouchstart="wppaStartTime();"
				ontouchend="wppaTapLink(\'' . $id . '\',\'' . $link['url'] . '\');"
				onclick="_bumpClickCount( \'' . $id . '\' );window.open(\'' . $link['url'] . '\', \'' . $link['target'] . '\' )"
				title="' . $link['title'] . '"
				class="thumb-img"
				id="a-' . $id . '-' . $mocc . '"
				style="cursor:pointer"
			>';
		}
	}

	// The image
	// Panorama? Only if browser supports html5
	if ( wppa_is_panorama( $id ) && wppa_browser_can_html5() ) {
		$result .= wppa_get_panorama_html( array( 	'id' 		=> $id,
													'width' 	=> $args['width'],
													'height'	=> $args['height'],
													'haslink' 	=> $link,
												)
										);
	}

	// Video?
	elseif ( wppa_is_video( $id ) ) {
		$result .=
		wppa_get_video_html( array( 'id' 		=> $id,
									'controls' 	=> ! $link,
									'style' 	=> $style,
									'class' 	=> $class,
									)
							);
	}

	// Pdf?
	elseif ( $is_pdf ) {
		$result .= '
		<iframe
			id="pdf-' . $id . '-' . $mocc . '"
			src="' . $href . '" ' .
			wppa_get_imgalt( $id ) .
			( $class ? ' class="' . $class . '" ' : '' ) .
			( $title ? ' title="' . $title . '" ' : '' ) . '
			style="' . $style . '"
		>
		</iframe>';
	}

	// No video, just a photo
	else {

		// Zoom?
		if ( wppa_is_zoomable( $id ) ) {

			$result .=
			wppa_get_zoom_pan_html( array(
				'mocc' 				=> $mocc,
				'id' 				=> $id,
				'controls' 			=> ( wppa_opt( 'panorama_control' ) == 'all' ) || ( wppa_opt( 'panorama_control' ) == 'mobile' && wppa_is_mobile() ),
				'manual' 			=> true,
				'zoomsensitivity' 	=> wppa_opt( 'panorama_wheel_sensitivity' ),
				'haslink' 			=> $link,
				'width' 			=> $width,

			) );
		}
		else {
			$imgid = 'ph-'.$id.'-'.$mocc;

			$result .=
			'<img' .
				' id="' . $imgid . '"' .
				' ' . ( wppa_lazy() ? 'data-' : '' ) . 'src="' . $href . '"' .
				' ' . wppa_get_imgalt( $id ) .
				( $class ? ' class="' . $class . '" ' : '' ) .
				( $title ? ' title="' . $title . '" ' : '' ) .
				' style="' . $style . '"' .
				' alt="' . $id . '"' .
			' />';
		}
	}

	// Close the link
	if ( $link ) {
		$result .= '</a>';
	}

	// Add audio?			sphoto
	if ( wppa_has_audio( $id ) ) {

		$result .= '<div style="position:relative;z-index:11">';

		// Find style for audio controls
		switch ( $type ) {
			case 'sphoto':
				$pad = ( wppa_opt( 'fullimage_border_width' ) === '' ) ? 0 : wppa_opt( 'fullimage_border_width' );
				$bot = ( wppa_opt( 'fullimage_border_width' ) === '' ) ? 0 : wppa_opt( 'fullimage_border_width' );
				$style = 	'margin:0;' .
							'padding:0 ' . $pad . 'px;' .
							'bottom:' . $bot .'px;';
				$class = 	'size-medium wppa-sphoto wppa-sphoto-' . wppa( 'mocc' );
				break;
			case 'mphoto':
			case 'xphoto':
				$style = 	'margin:0;' .
							'padding:0;' .
							'bottom:0;' .
							'left:0;';
				$class = 	'size-medium wppa-' . $type . ' wppa-' . $type . '-' . wppa( 'mocc' );
				break;
			default:
				$style = 	'margin:0;' .
							'padding:0;';
				$class = 	'';
		}

		// Get the html for audio
		$result .= wppa_get_audio_html( array(	'id' 		=> 	$id,
												'cursor' 	=> 	'cursor:pointer;',
												'style' 	=> 	$style .
												'position:absolute;' .
												'box-sizing:border-box;' .
												'width:100%;' .
												'border:none;' .
												'height:' . wppa_get_audio_control_height() . 'px;' .
												'border-radius:0px;',
												'class' 	=> 	$class,
											)
									);
		$result .= '</div>';
	}

	$result .= '</div>';

	// Update statistics
	if ( ! wppa_in_widget() ) {
		wppa_bump_viewcount( 'photo', $id );
	}

	// Done !
	return $result;
}

// Get full html for a lightbox pan image, e.g. ' data-panorama="'..."' for use in lightbox anchor link
function wppa_get_lb_panorama_full_html( $id ) {

	$html = wppa_get_lb_panorama_html( $id );
	if ( $html ) {
		$result = ' data-panorama="' . esc_attr( $html ) . '"';

		switch ( wppa_get_photo_item( $id, 'panorama' ) ) {
			case '0': // none
				if ( wppa_is_zoomable( $id ) ) {
					$result .= ' data-pantype="zoom"';
				}
				break;
			case '1': // spheric
				$result .= ' data-pantype="spheric"';
				break;
			case '2': // flat
				$result .= ' data-pantype="flat"';
				break;
			default:
				break;
		}

		return $result;
	}
	else {
		return '';
	}
}

// Get the html for a lightbox pan image
function wppa_get_lb_panorama_html( $id ) {

	return wppa_get_panorama_html( array( 'id' => $id, 'lightbox' 	=> true, ) );
}

// Get the html for a pan image
function wppa_get_panorama_html( $args ) {

	// If no id given, quit
	if ( ! isset( $args['id'] ) ) return;

	if ( ! isset($args['controls']) ) $args['controls'] = ( wppa_opt( 'panorama_control' ) == 'all' ) || ( wppa_opt( 'panorama_control' ) == 'mobile' && wppa_is_mobile() );
	$args['manual'] = wppa_opt( 'panorama_manual' ) == 'all' ? true : false;
	$args['autorun'] = wppa_opt( 'panorama_autorun' ) == 'none' ? '' : wppa_opt( 'panorama_autorun' );
	$args['autorunspeed'] = wppa_opt( 'panorama_autorun_speed' );
	$args['zoomsensitivity'] = wppa_opt( 'panorama_wheel_sensitivity' );
	if ( wppa( 'in_widget' ) ) {
		$args['width'] = wppa_opt( 'widget_width' );
	}

	if ( wppa_is_zoomable( $args['id'] ) ) {
		$result = wppa_get_zoom_pan_html( $args );
	}

	else {
		switch( wppa_is_panorama( $args['id'] ) ) {

			case '1':
				$result = wppa_get_spheric_pan_html( $args );

				// Save we have a spheric panorama on board for loading THREE.js
				wppa( 'has_panorama', true );
				break;
			case '2':
				$result = wppa_get_flat_pan_html( $args );
				break;
			default:
				$result = '';
		}
	}

	return $result;
}

// Spheric 360deg pan
function wppa_get_spheric_pan_html( $args ) {

	// Init
	$defaults 	= array( 	'id' 				=> '0',
							'mocc' 				=> '0',
							'width' 			=> false,
							'height' 			=> false,
							'haslink' 			=> false,
							'lightbox' 			=> 0,
							'controls' 			=> true,
							'autorun' 			=> '',
							'manual' 			=> true,
							'autorunspeed' 		=> '3',
							'zoomsensitivity' 	=> '3',
							'slide' 			=> false,
						);

	$args 				= wp_parse_args( $args, $defaults );
	$id 				= strval( intval ( $args['id'] ) );

	if ( wppa_is_mobile() ) {
		$url = esc_url( wppa_get_photo_url( $id ) );
		$ratio = wppa_get_photox( $id ) / wppa_get_photoy( $id );
	}
	else {
		$url = esc_url( wppa_get_hires_url( $id ) );
		$ratio = wppa_get_source_ratio( $id );
	}
	if ( ! $ratio ) $ratio = 2;

	$mocc 				= $args['mocc'] ? $args['mocc'] : wppa( 'mocc' );
	$width 				= $args['width'] ? $args['width'] : wppa_get_container_width();
	$height 			= $width / 2; // $args['height'] ? $args['height'] : round( $width / $ratio );
	$haslink 			= $args['haslink'];
	$lightbox 			= $args['lightbox'];
	$icsiz 				= ( $lightbox ? wppa_opt( 'nav_icon_size_lightbox' ) : wppa_opt( 'nav_icon_size_panorama' ) );
	$iconsize 			= ( wppa( 'in_widget' ) ? $icsiz / 2 : $icsiz ) . 'px;';
	$controls 			= $args['controls'];
	$autorun 			= $args['autorun'];
	$manual 			= $args['manual'];
	$autorunspeed 		= $args['autorunspeed'];
	$zoomsensitivity 	= $args['zoomsensitivity'];
	$slide 				= $args['slide'];

	$result = '';

	if ( $lightbox ) {
		$result .= $id . '.';
	}
	if ( $haslink ) {
		$result .= '</a>';
	}
	if ( $lightbox ) {
		$result .= '<div id="wppa-ovl-sphericpan-container" >';
	}
	if ( $lightbox ) {
		$mocc = '0';
	}

	$result .=
		'<div
			id="wppa-pan-div-' . $mocc . '"
			class="wppa-pan-div wppa-pan-div-' . $mocc . '"
			style="' . ( $controls ? 'margin-bottom:4px;' : '' ) . ( $manual ? 'cursor:grab;': '' ) . 'line-height:0;"
			>
		</div>';

	if ( $controls ) {
		$result .=
		'<div
			id="wppa-pctl-div-' . $mocc . '"
			class="wppa-pctl-div wppa-pctl-div-' . $mocc . ' ' . ( $lightbox ? 'wppa-pctl-div-lb' : '' ) . '"
			style="text-align:center;' . ( $lightbox || $slide ? 'display:none;' : 'margin-bottom:4px;' ) . '"
			onclick="return false;"
			>';
			if ( $lightbox ) {
				if ( wppa_ovl_big_browse() ) {
					$result .= '
					<span
						id="wppa-pctl-prev-' . $mocc . '"
						class="wppa-pan-prevnext sixty"
						style="position:fixed;top:50%;left:0;width:60px !important;height:60px !important;margin-top:-30px;"
						onclick="wppaOvlShowPrev()"
						>' .
						wppa_get_svghtml( 'Prev-Button-Big', '60px', true, false, 0, 0, 0, 0 ) .
					'</span>';
				}
				if ( wppa_ovl_small_browse() ) {
					$result .= '
					<span
						id="wppa-pctl-prev-' . $mocc . '"
						class="wppa-pan-prevnext"
						style="margin:0 2px 0 0;float:left;display:inline-block;"
						onclick="wppaOvlShowPrev()"
						>' .
						wppa_get_svghtml( 'Prev-Button', $iconsize, $lightbox ) .
					'</span>';
				}
				$result .= '
				<span
					id="wppa-ovl-start-btn"
					style="margin:0 2px;float:left;display:none;"
					title="' . esc_attr( __( 'Start', 'wp-photo-album-plus' ) ) . '"
					onclick="wppaOvlStartStop()"
					>' .
					wppa_get_svghtml( 'Play-Button', $iconsize, $lightbox ) .
				'</span>
				<span
					id="wppa-ovl-stop-btn"
					style="margin:0 2px;float:left;display:none;"
					title="' . esc_attr( __( 'Stop', 'wp-photo-album-plus' ) ) . '"
					onclick="wppaOvlStartStop()"
					>' .
					wppa_get_svghtml( 'Pause-Button', $iconsize, $lightbox ) .
				'</span>';
			}

			$result .=
			'<span
				id="wppa-pctl-left-' . $mocc . '"
				style="margin:0 2px;display:inline-block;"
				>' .
				wppa_get_svghtml( 'Left-4', $iconsize, $lightbox ) .
			'</span>
			<span
				id="wppa-pctl-right-' . $mocc . '"
				style="margin:0 2px;display:inline-block;"
				>' .
				wppa_get_svghtml( 'Right-4', $iconsize, $lightbox ) .
			'</span>
			<span
				id="wppa-pctl-up-' . $mocc . '"
				style="margin:0 2px;display:inline-block;"
				>' .
			wppa_get_svghtml( 'Up-4', $iconsize, $lightbox ) .
			'</span>
			<span
				id="wppa-pctl-down-' . $mocc . '"
				style="margin:0 2px;display:inline-block;"
				>' .
				wppa_get_svghtml( 'Down-4', $iconsize, $lightbox ) .
			'</span>
			<span
				id="wppa-pctl-zoomin-' . $mocc . '"
				style="margin:0 2px;display:inline-block;"
				>' .
				wppa_get_svghtml( 'ZoomIn', $iconsize, $lightbox ) .
			'</span>
			<span
				id="wppa-pctl-zoomout-' . $mocc . '"
				style="margin:0 2px;display:inline-block;"
				>' .
				wppa_get_svghtml( 'ZoomOut', $iconsize, $lightbox ) .
			'</span>';

			// If lightbox: next button and fullscreen buttons
			if ( $lightbox ) {
				if ( wppa_ovl_big_browse() ) {
					$result .= '
					<span
						id="wppa-pctl-next-' . $mocc . '"
						class="wppa-pan-prevnext sixty"
						style="position:fixed;top:50%;right:0;width:60px !important;height:60px !important;margin-top:-30px;"
						onclick="wppaOvlShowNext()"
						>' .
						wppa_get_svghtml( 'Next-Button-Big', '60px', true, false, 0, 0, 0, 0 ) .
					'</span>';
				}
				if ( wppa_ovl_small_browse() ) {
					$result .=
					'<span
						id="wppa-pctl-next-' . $mocc . '"
						class="wppa-pan-prevnext"
						style="margin:0 0 0 2px;float:right;display:inline-block;"
						title="' . esc_attr( __( 'Next image', 'wp-photo-album-plus' ) ) . '"
						onclick="wppaOvlShowNext()"
						>' .
						wppa_get_svghtml( 'Next-Button', $iconsize, $lightbox ) .
					'</span>';
				}
				if ( wppa_ovl_big_browse() ) {
					$result .= '
					<span
						id="wppa-exit-btn-2"
						class="sixty"
						style="position:fixed;top:7px;right:7px;width:60px;height:60px;"
						title="' . esc_attr( __( 'Exit', 'wp-photo-album-plus' ) ) . '"
						onclick="wppaOvlHide()"
						>' .
						wppa_get_svghtml( 'Exit-Big', '60px', true, false, 0, 0, 0, 0 ) .
					'</span>';
				}
				if ( wppa_ovl_small_browse() ) {
				$result .= '
					<span
						id="wppa-exit-btn-2"
						class=""
						style="margin:0 2px;float:right;display:inline-block;"
						title="' . esc_attr( __( 'Exit', 'wp-photo-album-plus' ) ) . '"
						onclick="wppaOvlHide()"
						>' .
						wppa_get_svghtml( 'Exit-2', $iconsize, $lightbox ) .
					'</span>';
				}

				// Fs buttons
				if ( wppa_show_fs_2() ) {
					$result .=
					'<span
						id="wppa-fulls-btn-2"
						class="wppa-fulls-btn"
						style="margin:0 2px;float:right;display:none;"
						title="' . esc_attr( __( 'Enter fullscreen', 'wp-photo-album-plus' ) ) . '"
						onclick="wppaFsOn()"
						>' .
						wppa_get_svghtml( 'Full-Screen-2', $iconsize, $lightbox ) .
					'</span>' .
					'<span
						id="wppa-exit-fulls-btn-2"
						class="wppa-exit-fulls-btn"
						style="margin:0 2px;float:right;display:none;"
						title="' . esc_attr( __( 'Leave fullscreen', 'wp-photo-album-plus' ) ) . '"
						onclick="wppaFsOff()"
						>' .
						wppa_get_svghtml( 'Exit-Full-Screen-2', $iconsize, $lightbox ) .
					'</span>';
				}
			}

		$result .=
		'</div>';
	}

	if ( $lightbox ) {
		$result .= '</div>';
	}

	if ( $autorun == 'right' ) {
		$dX = 0.05 * $autorunspeed / 3;
		$run = 'true';
	}
	elseif ( $autorun == 'left' ) {
		$dX = -0.05 * $autorunspeed / 3;
		$run = 'true';
	}
	else {
		$dX = 0;
		$run = 'false';
	}

	$onload = '
	jQuery(document).ready(function(){
		var data' . $mocc . ' =
			{	mocc				:' . $mocc . ',
				id					:' . $id . ',
				uId 				:0,
				isLightbox			:' . ( $lightbox ? 'true' : 'false' ) . ',
				height				:' . $height . ',
				width				:' . $width . ',
				url 				:\'' . $url . '\',
				abort 				:false,
				autorun 			:' . $run . ',
				dX 					:' . $dX . ',
				dY 					:0,
				fov 				:' . wppa_opt( 'panorama_fov' ) . ',
				zoomsensitivity		:' . $zoomsensitivity . ',
				borderWidth			:' . wppa_opt( 'ovl_border_width' ) . ',
				controls			:' . ( $controls ? 'true' : 'false' ) . ',
				initialized			:false,
				icsize				:' . ( $lightbox ? wppa_opt( 'nav_icon_size_lightbox' ) : wppa_opt( 'nav_icon_size_panorama' ) ) . ',
				backgroundColor		:\'' . wppa_opt( 'ovl_theme' ) . '\',
				borderRadius		:' . wppa_opt( 'ovl_border_radius' ) . ',
				padding				:' . wppa_opt( 'ovl_border_width' ) . ',
				enableManual 		:' . ( $manual ? 'true' : 'false' ) . ',
				vtime 				:0,
				slide 				:' . ( $slide ? 'true' : 'false' ) . ',
				pancontrolheight 	:' . wppa_get_pan_control_height() . '
			};
		wppaDoSphericPan(' . $mocc . ', data' . $mocc . ');
		});';

	$result .= '
	<img
		id="wppa-' . $mocc . '-' . $id . '"
		src="' . esc_url( $url ) . '"
		style="display:none"
		onload="' . esc_attr( $onload ) . '" />';

	if ( $haslink ) {
		$result .= '<a>';
	}

	$result = wppa_pan_min( $result );

	return $result;
}

// Non 360 flat pan
function wppa_get_flat_pan_html( $args ) {

	// Init
	$defaults 	= array( 	'id' 				=> '0',
							'mocc' 				=> '0',
							'width' 			=> false,
							'height' 			=> false,
							'haslink' 			=> false,
							'lightbox' 			=> 0,
							'controls' 			=> true,
							'autorun' 			=> '',
							'manual' 			=> true,
							'autorunspeed' 		=> '3',
							'zoomsensitivity' 	=> '3',
							'slide' 			=> false,
							);

	$args 				= wp_parse_args( $args, $defaults );

	$id 				= strval( intval ( $args['id'] ) );
	$mocc 				= $args['mocc'] ? $args['mocc'] : wppa( 'mocc' );
	$itemid 			= $mocc . '-' . $id;
	$width 				= $args['width'] ? $args['width'] : wppa_get_container_width();
	$height 			= $args['height'] ? $args['height'] : round( $width * wppa_get_photoy( $id ) / wppa_get_photox( $id ) );
	$haslink 			= $args['haslink'];
	$lightbox 			= $args['lightbox'];
	if ( $lightbox ) $mocc = '0';
	$icsiz 				= ( $lightbox ? wppa_opt( 'nav_icon_size_lightbox' ) : wppa_opt( 'nav_icon_size_panorama' ) );
	$iconsize 			= ( wppa( 'in_widget' ) ? $icsiz / 2 : $icsiz ) . 'px;';
	$controls 			= $args['controls'];
	$autorun 			= $args['autorun'];
	$manual 			= $args['manual'];
	$autorunspeed 		= $args['autorunspeed'];
	$zoomsensitivity 	= $args['zoomsensitivity'];
	$slide 				= $args['slide'];

	switch ( $autorun ) {
		case 'right':
			 $deltaX = $autorunspeed / 3;
			 break;
		case 'left':
			 $deltaX = - $autorunspeed / 3;
			 break;
		default:
			 $deltaX = '0';
	}

	$url 	= esc_url( wppa_is_mobile() ? wppa_get_photo_url( $id ) : wppa_get_hires_url( $id ) );

	$result = '';

	if ( $lightbox ) {
		$result .= $id . '.';
	}

	if ( $haslink ) {
		$result .= '</a>';
	}

	// The overall container
	if ( $lightbox ) {
		$result .= '<div id="wppa-ovl-flatpan-container" >';
	}

	// The canvas container
	$result .=
	'<div
		id="wppa-pan-div-' . $mocc . '"
		class="wppa-pan-div wppa-pan-div-' . $mocc . '"
		style="' . ( $controls ? 'margin-bottom:4px;' : '' ) . 'line-height:0;"
		>' .

		// The actual drawing area
		'<canvas
			id="wppa-pan-canvas-' . $mocc . '"
			style="background-color:transparent;' . ( $manual ? 'cursor:grab;' : '' ) . '"
			width="' . $width . '"
			height="' . ( $width / 2 ) . '"' .
			( $lightbox ? '
				ontouchstart="wppaTouchStart( event, \'wppa-pan-canvas-' . $mocc . '\', -1 );"
				ontouchend="wppaTouchEnd( event );"
				ontouchmove="wppaTouchMove( event );"
				ontouchcancel="wppaTouchCancel( event );"' : ''
			) . '
			>
		</canvas>';

		// The preview image
		if ( ! $slide ) $result .=
		'<canvas
			id="wppa-pan-prev-canvas-' . $mocc . '"
			style="margin-top:4px;background-color:transparent;"
			width="' . $width . '"
			height="' . $height . '"
			>
		</canvas>';

	// Close canvas container
	$result .=
	'</div>';

	// The controlbar
	if ( $controls ) {
		$result .=
		'<div
			id="wppa-pctl-div-' . $mocc . '"
			class="wppa-pctl-div wppa-pctl-div-' . $mocc . ' ' . ( $lightbox ? 'wppa-pctl-div-lb' : '' ) . '"
			style="text-align:center;' . ( $lightbox || $slide ? 'display:none;' : 'margin:4px 0;' ) . '"
			onclick="return false;"
			>';

		// If lightbox: prev button
		if ( $lightbox ) {
			if ( wppa_ovl_big_browse() ) {
				$result .= '
				<span
					id="wppa-pctl-prev-' . $mocc . '"
					class="wppa-pan-prevnext sixty"
					style="position:fixed;top:50%;left:0;width:60px !important;height:60px !important;margin-top:-30px;"
					onclick="wppaOvlShowPrev()"
					>' .
					wppa_get_svghtml( 'Prev-Button-Big', '60px', true, false, 0, 0, 0, 0 ) .
				'</span>';
			}
			if ( wppa_ovl_small_browse() ) {
				$result .=
				'<span
					id="wppa-pctl-prev-' . $mocc . '"
					class="wppa-pan-prevnext"
					style="margin:0 2px 0 0;float:left;display:inline-block;"
					onclick="wppaOvlShowPrev()"
					>' .
					wppa_get_svghtml( 'Prev-Button', $iconsize, $lightbox ) .
				'</span>';
			}
			$result .= '
			<span
				id="wppa-ovl-start-btn"
				style="margin:0 2px;float:left;display:none;"
				title="' . esc_attr( __( 'Start', 'wp-photo-album-plus' ) ) . '"
				onclick="wppaOvlStartStop()"
				>' .
				wppa_get_svghtml( 'Play-Button', $iconsize, $lightbox ) .
			'</span>
			<span
				id="wppa-ovl-stop-btn"
				style="margin:0 2px;float:left;display:none;"
				title="' . esc_attr( __( 'Stop', 'wp-photo-album-plus' ) ) . '"
				onclick="wppaOvlStartStop()"
				>' .
				wppa_get_svghtml( 'Pause-Button', $iconsize, $lightbox ) .
			'</span>';
		}

		// The nav buttons
		$result .=
		'<span
			id="wppa-pctl-left-' . $mocc . '"
			style="margin:0 2px;display:inline-block;"
			>' .
			wppa_get_svghtml( 'Left-4', $iconsize, $lightbox ) .
		'</span>
		<span
			id="wppa-pctl-right-' . $mocc . '"
			style="margin:0 2px;display:inline-block;"
			>' .
			wppa_get_svghtml( 'Right-4', $iconsize, $lightbox ) .
		'</span>
		<span
			id="wppa-pctl-up-' . $mocc . '"
			style="margin:0 2px;display:inline-block;"
			>' .
			wppa_get_svghtml( 'Up-4', $iconsize, $lightbox ) .
		'</span>
		<span
			id="wppa-pctl-down-' . $mocc . '"
			style="margin:0 2px;display:inline-block;"
			>' .
			wppa_get_svghtml( 'Down-4', $iconsize, $lightbox ) .
		'</span>
		<span
			id="wppa-pctl-zoomin-' . $mocc . '"
			style="margin:0 2px;display:inline-block;"
			>' .
			wppa_get_svghtml( 'ZoomIn', $iconsize, $lightbox ) .
		'</span>
		<span
			id="wppa-pctl-zoomout-' . $mocc . '"
			style="margin:0 2px;display:inline-block;"
			>' .
			wppa_get_svghtml( 'ZoomOut', $iconsize, $lightbox ) .
		'</span>';

		// If lightbox: next button
		if ( $lightbox ) {
			if ( wppa_ovl_big_browse() ) {
				$result .= '
				<span
					id="wppa-pctl-next-' . $mocc . '"
					class="wppa-pan-prevnext sixty"
					style="position:fixed;top:50%;right:0;width:60px !important;height:60px !important;margin-top:-30px;"
					onclick="wppaOvlShowNext()"
					>' .
					wppa_get_svghtml( 'Next-Button-Big', '60px', true, false, 0, 0, 0, 0 ) .
				'</span>';
			}
			if ( wppa_ovl_small_browse() ) {
				$result .=
				'<span
					id="wppa-pctl-next-' . $mocc . '"
					class="wppa-pan-prevnext"
					style="margin:0 0 0 2px;float:right;display:inline-block;"
					onclick="wppaOvlShowNext()"
					>' .
					wppa_get_svghtml( 'Next-Button', $iconsize, $lightbox ) .
				'</span>';
			}
			if ( wppa_ovl_big_browse() ) {
				$result .= '
				<span
					id="wppa-exit-btn-2"
					class="sixty"
					style="position:fixed;top:7px;right:7px;width:60px;height:60px;"
					title="' . esc_attr( __( 'Exit', 'wp-photo-album-plus' ) ) . '"
					onclick="wppaOvlHide()"
					>' .
					wppa_get_svghtml( 'Exit-Big', '60px', true, false, 0, 0, 0, 0 ) .
				'</span>';
			}
			if ( wppa_ovl_small_browse() ) {
				$result .= '
				<span
					id="wppa-exit-btn-2"
					class=""
					style="margin:0 2px;float:right;display:inline-block;"
					title="' . esc_attr( __( 'Exit', 'wp-photo-album-plus' ) ) . '"
					onclick="wppaOvlHide()"
					>' .
					wppa_get_svghtml( 'Exit-2', $iconsize, $lightbox ) .
				'</span>';
			}

			// Fs buttons
			if ( wppa_show_fs_2() ) {
				$result .=
				'<span
					id="wppa-fulls-btn-2"
					class="wppa-fulls-btn"
					style="margin:0 2px;float:right;display:none;"
					title="' . esc_attr( __( 'Enter fullscreen', 'wp-photo-album-plus' ) ) . '"
					onclick="wppaFsOn()"
					>' .
					wppa_get_svghtml( 'Full-Screen-2', $iconsize, $lightbox ) .
				'</span>' .
				'<span
					id="wppa-exit-fulls-btn-2"
					class="wppa-exit-fulls-btn"
					style="margin:0 2px;float:right;display:none;"
					title="' . esc_attr( __( 'Leave fullscreen', 'wp-photo-album-plus' ) ) . '"
					onclick="wppaFsOff()"
					>' .
					wppa_get_svghtml( 'Exit-Full-Screen-2', $iconsize, $lightbox ) .
				'</span>';
			}
		}
	}

	// End controlbar
	$result .=
	'</div>';

	// Add placeholder for image
	$onload =
	'jQuery(document).ready(function(){
		var data' . $mocc . ' =
		{	mocc				:' . $mocc . ',
			id					:' . $id . ',
			itemId				:\'' . $itemid . '\',
			isLightbox			:' . ( $lightbox ? 'true' : 'false' ) . ',
			url 				:\'' . $url . '\',
			abort 				:false,
			zoomsensitivity		:' . $zoomsensitivity . ',
			borderWidth			:' . wppa_opt( 'ovl_border_width' ) . ',
			controls			:' . ( $controls ? 'true' : 'false' ) . ',
			height				:' . $height . ',
			width				:' . $width . ',
			icsize				:' . ( $lightbox ? wppa_opt( 'nav_icon_size_lightbox' ) : wppa_opt( 'nav_icon_size_panorama' ) ) . ',
			backgroundColor		:\'' . wppa_opt( 'ovl_theme' ) . '\',
			borderRadius		:' . wppa_opt( 'ovl_border_radius' ) . ',
			padding				:' . wppa_opt( 'ovl_border_width' ) . ',
			deltaX 				:' . $deltaX . ',
			autorun 			:' . ( $autorun ? 'true' : 'false' ) . ',
			manual 				:' . ( $manual ? 'true' : 'false' ) . ',
			slide 				:' . ( $slide ? 'true' : 'false' ) . ',
			pancontrolheight 	:' . wppa_get_pan_control_height() . '
		};
		jQuery( \'#wppa-spinner-'.$mocc.'\' ).hide();
		wppaDoFlatPanorama(' . $mocc . ', data' . $mocc . ');
	});';

	$result .= '
	<img
		id="wppa-' . $itemid . '"
		src="' . esc_url( $url ) . '"
		style="display:none"
		onload="' . esc_attr( $onload ) . '" />';

	// wppa-ovl-flatpan-container
	if ( $lightbox ) {
		$result .=
		'</div>';
	}

	if ( $haslink ) {
		$result .= '<a>';
	}

	return wppa_pan_min( $result );
}

// Just zoomable/pannable
function wppa_get_zoom_pan_html( $args ) {

	// Init
	$defaults 	= array( 	'id' 				=> '0',
							'mocc' 				=> '0',
							'width' 			=> false,
							'height' 			=> false,
							'haslink' 			=> false,
							'lightbox' 			=> 0,
							'controls' 			=> true,
							'autorun' 			=> '',
							'manual' 			=> true,
							'autorunspeed' 		=> '3',
							'zoomsensitivity' 	=> '3',
							'slide' 			=> false,
							);

	$args 				= wp_parse_args( $args, $defaults );

	$id 				= strval( intval ( $args['id'] ) );
	$mocc 				= $args['mocc'] ? $args['mocc'] : wppa( 'mocc' );
	$itemid 			= $mocc . '-' . $id;
	$width 				= $args['width'] ? $args['width'] : wppa_get_container_width();
	$height 			= $args['height'] ? $args['height'] : round( $width * wppa_get_photoy( $id ) / wppa_get_photox( $id ) );
	$haslink 			= $args['haslink'];
	$lightbox 			= $args['lightbox'];
	$icsiz 				= ( $lightbox ? wppa_opt( 'nav_icon_size_lightbox' ) : wppa_opt( 'nav_icon_size_panorama' ) );
	$iconsize 			= ( wppa( 'in_widget' ) ? $icsiz / 2 : $icsiz ) . 'px;';
	$controls 			= $args['controls'];
	$zoomsensitivity 	= $args['zoomsensitivity'];
	$url 				= esc_url( wppa_is_mobile() ? wppa_get_photo_url( $id ) : wppa_get_hires_url( $id ) );
	$manual 			= $args['manual'];
	$slide 				= $args['slide'];

	// Fake mooc = 0 for lightbox
	if ( $lightbox ) {
		$mocc = '0';
		$itemid = '0-' . $id;
	}

	$result = '';

	// Lightbox starts with 'PhotoID.'
	if ( $lightbox ) {
		$result .=
		$id . '.';
	}

	// Close possible link
	if ( $haslink ) {
		$result .=
		'</a>';
	}

	// The overall container
	if ( $lightbox ) {
		$result .=
		'<div
			id="wppa-ovl-zoom-container"
			style="background-color:' . wppa_opt( 'ovl_theme' ) . ';"
			>';
	}

		// The canvas container
		$result .=
		'<div
			id="wppa-pan-div-' . $itemid . '"
			class="wppa-pan-div wppa-pan-div-' . $mocc . '"
			style="' . ( $controls ? 'margin-bottom:4px;' : '' ) . 'line-height:0;"' .  // . ( $width && ! $lightbox ? 'width:'.$width.'px;' : '' ) . '"
	'		>';

			// The spinner image
			if ( $lightbox || $slide ) {
			}
			else {
//				$result .= wppa_get_spinner_svg_html( ['id' => 'wppa-spinner-'.$mocc, 'display' => ''] );
			}

			// The actual drawing area
			$result .=
			'<canvas
				id="wppa-pan-canvas-' . $itemid . '"
				style="' . ( $manual ? 'cursor:grab;' : '' ) . ';background-color:transparent ' . '" ' .//( $lightbox ? '' : 'width:' . $width . 'px;' ) . '"
'			/>

		</div>';

		// The controlbar
		if ( $controls ) {

			// Open controlbar
			$result .=
			'<div
				id="wppa-pctl-div-' . $mocc . '"
				class="wppa-pctl-div wppa-pctl-div-' . $itemid . ' wppa-pctl-div-' . $mocc . ' ' . ( $lightbox ? 'wppa-pctl-div-lb' : '' ) . '"
				style="text-align:center;' . ( $lightbox | $slide ? 'display:none;' : 'margin-bottom:4px;' ) . '"
				onclick="return false;"
				>';

				// If lightbox: prev button, start / stop buttons
				if ( $lightbox ) {
					if ( wppa_ovl_big_browse() ) {
						$result .= '
						<span
							id="wppa-pctl-prev-' . $itemid . '"
							class="wppa-pan-prevnext sixty"
							style="position:fixed;top:50%;left:0;width:60px !important;height:60px !important;margin-top:-30px;"
							onclick="wppaOvlShowPrev()"
							>' .
							wppa_get_svghtml( 'Prev-Button-Big', '60px', true, false, 0, 0, 0, 0 ) .
						'</span>';
					}
					if ( wppa_ovl_small_browse() ) {
						$result .=
						'<span
							id="wppa-pctl-prev-' . $itemid . '"
							class="wppa-pan-prevnext"
							style="margin:0 2px 0 0;float:left;display:inline-block;"
							title="' . esc_attr( __( 'Previous image', 'wp-photo-album-plus' ) ) . '"
							onclick="wppaOvlShowPrev()"
							>' .
							wppa_get_svghtml( 'Prev-Button', $iconsize, $lightbox ) .
						'</span>';
					}
					$result .= '
					<span
						id="wppa-ovl-start-btn"
						style="margin:0 2px;float:left;display:none;"
						title="' . esc_attr( __( 'Start', 'wp-photo-album-plus' ) ) . '"
						onclick="wppaOvlStartStop()"
						>' .
						wppa_get_svghtml( 'Play-Button', $iconsize, $lightbox ) .
					'</span>' .
					'<span
						id="wppa-ovl-stop-btn"
						style="margin:0 2px;float:left;display:none;"
						title="' . esc_attr( __( 'Stop', 'wp-photo-album-plus' ) ) . '"
						onclick="wppaOvlStartStop()"
						>' .
						wppa_get_svghtml( 'Pause-Button', $iconsize, $lightbox ) .
					'</span>';
				}

				// Always The nav buttons
				$result .=
				'<span
					id="wppa-pctl-left-' . $itemid . '"
					style="margin:0 2px;display:inline-block;"
					title="' . esc_attr( __( 'Camera left', 'wp-photo-album-plus' ) ) . '"
					data-mocc="' . $mocc . '"
					>' .
					wppa_get_svghtml( 'Left-4', $iconsize, $lightbox ) .
				'</span>' .
				'<span
					id="wppa-pctl-right-' . $itemid . '"
					style="margin:0 2px;display:inline-block;"
					title="' . esc_attr( __( 'Camera right', 'wp-photo-album-plus' ) ) . '"
					data-mocc="' . $mocc . '"
					>' .
					wppa_get_svghtml( 'Right-4', $iconsize, $lightbox ) .
				'</span>' .
				'<span
					id="wppa-pctl-up-' . $itemid . '"
					style="margin:0 2px;display:inline-block;"
					title="' . esc_attr( __( 'Camera up', 'wp-photo-album-plus' ) ) . '"
					data-mocc="' . $mocc . '"
					>' .
					wppa_get_svghtml( 'Up-4', $iconsize, $lightbox ) .
				'</span>' .
				'<span
					id="wppa-pctl-down-' . $itemid . '"
					style="margin:0 2px;display:inline-block;"
					title="' . esc_attr( __( 'Camera down', 'wp-photo-album-plus' ) ) . '"
					data-mocc="' . $mocc . '"
					>' .
					wppa_get_svghtml( 'Down-4', $iconsize, $lightbox ) .
				'</span>' .
				'<span
					id="wppa-pctl-zoomin-' . $itemid . '"
					style="margin:0 2px;display:inline-block;"
					title="' . esc_attr( __( 'Zoom in', 'wp-photo-album-plus' ) ) . '"
					data-mocc="' . $mocc . '"
					>' .
					wppa_get_svghtml( 'ZoomIn', $iconsize, $lightbox ) .
				'</span>' .
				'<span
					id="wppa-pctl-zoomout-' . $itemid . '"
					style="margin:0 2px;display:inline-block;"
					title="' . esc_attr( __( 'Zoom out', 'wp-photo-album-plus' ) ) . '"
					data-mocc="' . $mocc . '"
					>' .
					wppa_get_svghtml( 'ZoomOut', $iconsize, $lightbox ) .
				'</span>';

				// If lightbox: next button and fullscreen buttons
				if ( $lightbox ) {
					if ( wppa_ovl_big_browse() ) {
						$result .= '
						<span
							id="wppa-pctl-next-' . $itemid . '"
							class="wppa-pan-prevnext sixty"
							style="position:fixed;top:50%;right:0;width:60px !important;height:60px !important;margin-top:-30px;"
							onclick="wppaOvlShowNext()"
							>' .
							wppa_get_svghtml( 'Next-Button-Big', '60px', true, false, 0, 0, 0, 0 ) .
						'</span>';
					}
					if ( wppa_ovl_small_browse() ) {
					$result .=
						'<span
							id="wppa-pctl-next-' . $itemid . '"
							class="wppa-pan-prevnext"
							style="margin:0 0 0 2px;float:right;display:inline-block;"
							title="' . esc_attr( __( 'Next image', 'wp-photo-album-plus' ) ) . '"
							onclick="wppaOvlShowNext()"
							>' .
							wppa_get_svghtml( 'Next-Button', $iconsize, $lightbox ) .
						'</span>';
					}
					if ( wppa_ovl_big_browse() ) {
						$result .= '
						<span
							id="wppa-exit-btn-2"
							class="sixty"
							style="position:fixed;top:7px;right:7px;width:60px;height:60px;"
							title="' . esc_attr( __( 'Exit', 'wp-photo-album-plus' ) ) . '"
							onclick="wppaOvlHide()"
							>' .
							wppa_get_svghtml( 'Exit-Big', '60px', true, false, 0, 0, 0, 0 ) .
						'</span>';
					}
					if ( wppa_ovl_small_browse() ) {
						$result .= '
						<span
							id="wppa-exit-btn-2"
							class=""
							style="margin:0 2px;float:right;display:inline-block;"
							title="' . esc_attr( __( 'Exit', 'wp-photo-album-plus' ) ) . '"
							onclick="wppaOvlHide()"
							>' .
							wppa_get_svghtml( 'Exit-2', $iconsize, $lightbox ) .
						'</span>';
					}

					// Fs buttons
					if ( wppa_show_fs_2() ) {
						$result .=
						'<span
							id="wppa-fulls-btn-2"
							class="wppa-fulls-btn""
							style="margin:0 2px;float:right;display:none;"
							title="' . esc_attr( __( 'Enter fullscreen', 'wp-photo-album-plus' ) ) . '"
							onclick="wppaFsOn()"
							>' .
							wppa_get_svghtml( 'Full-Screen-2', $iconsize, $lightbox ) .
						'</span>' .
						'<span
							id="wppa-exit-fulls-btn-2"
							class="wppa-exit-fulls-btn"
							style="margin:0 2px;float:right;display:none;"
							title="' . esc_attr( __( 'Leave fullscreen', 'wp-photo-album-plus' ) ) . '"
							onclick="wppaFsOff()"
							>' .
							wppa_get_svghtml( 'Exit-Full-Screen-2', $iconsize, $lightbox ) .
						'</span>';
					}
				}

			// End controlbar
			$result .=
			'</div>';
		}

		// Add placeholder for image
		$onload =
		'jQuery(document).ready(function(){
			var data' . $mocc . ' =
			{	mocc				:' . $mocc . ',
				id					:' . $id . ',
				itemId				:\'' . $itemid . '\',
				isLightbox			:' . ( $lightbox ? 'true' : 'false' ) . ',
				url 				:\'' . $url . '\',
				abort 				:false,
				zoomsensitivity		:' . $zoomsensitivity . ',
				borderWidth			:' . wppa_opt( 'ovl_border_width' ) . ',
				controls			:' . ( $controls ? 'true' : 'false' ) . ',
				height				:wppaGetContainerWidth(' . $mocc . ') * '.$height.' / '.$width.', ' . // ' . $height . ',
'				width				:wppaGetContainerWidth(' . $mocc . '), ' . // . $width . ',
'				initialized			:false,
				icsize				:' . ( $lightbox ? wppa_opt( 'nav_icon_size_lightbox' ) : wppa_opt( 'nav_icon_size_panorama' ) ) . ',
				backgroundColor		:\'' . wppa_opt( 'ovl_theme' ) . '\',
				borderRadius		:' . wppa_opt( 'ovl_border_radius' ) . ',
				padding				:' . wppa_opt( 'ovl_border_width' ) . ',
				slide 				:' . ( $slide ? 'true' : 'false' ) . ',
				pancontrolheight 	:' . wppa_get_pan_control_height() . '
			};
			jQuery( \'#wppa-spinner-'.$mocc.'\' ).hide();
			wppaDoZoomPan(' . $mocc . ', data' . $mocc . ');
		});';

		$result .= '
		<img
			id="wppa-' . $itemid . '"
			src="' . esc_url( $url ) . '"
			style="display:none"
			onload="' . esc_attr( $onload ) . '" />';

	// wppa-ovl-zoom-container
	if ( $lightbox ) {
		$result .=
		'</div>';
	}

	if ( $haslink ) {
		$result .= '<a>';
	}

	return wppa_pan_min( $result );
}

// Minimize inline mixed html / js code
function wppa_pan_min( $result ) {

	// Remove tabs
	$result = str_replace( "\t", '', $result );

	// Remove newlines
	$result = str_replace( array( "\r\n", "\n\r", "\n", "\r" ), ' ', $result );

	// Trim operators
	$result = str_replace( array( ' = ',' + ',' * ',' / ' ), array( '=','+','*','/' ), $result );

	// Replace multiple spaces by one
	$olen = 0;
	$nlen = strlen( $result );
	do {
		$olen = $nlen;
		$result = str_replace( '  ', ' ', $result );
		$nlen = strlen( $result );
	} while ( $nlen != $olen );

	// Trim , ; and !
	$result = str_replace( array( ', ', '; ', '! ' ), array( ',', ';', '!' ), $result );

	// Trim braces
	$result = str_replace( array(  ' ) ', ') ', ' )' ), ')', $result );
	$result = str_replace( array(  ' ( ', '( ', ' (' ), '(', $result );

	// Remove space between html tags
	$result = str_replace( '> <', '><', $result );

	return $result;
}

function wppa_show_fs_2() {

	$doit = false;
	if ( wppa_opt( 'fs_policy' ) == 'global' && wppa_ovl_big_browse() ) $doit = true;
	if ( wppa_opt( 'fs_policy' ) == 'lightbox' ) $doit = true;
	if ( wppa_is_ipad() || wppa_is_safari() ) $doit = false;
	return $doit;
}