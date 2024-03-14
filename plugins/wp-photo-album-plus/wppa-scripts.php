<?php
/* wppa-scripts.php
* Package: wp-photo-album-plus
*
* This file contains all functions for activating javascript
*
* Version 8.6.04.008
*/

// Place all wppa related js declarations in the header, both admin and frontend
function wppa_initialize_javascript() {
global $wppa_version;
global $wppa_lang;
global $wppa_session;
global $wpdb;

	/* Global and slideshow vars */
	$result = '
	wppaSiteUrl = "' . site_url() . '",
	wppaThumbPageSize = ' . wppa_opt( 'thumb_page_size' ) . ',
	wppaResizeEndDelay = ' . ( wppa_is_mobile() ? wppa_opt( 'resizeend_delay_mob' ) : wppa_opt( 'resizeend_delay' ) ) . ',
	wppaScrollEndDelay = ' . ( wppa_is_mobile() ? wppa_opt( 'scrollend_delay_mob' ) : wppa_opt( 'scrollend_delay' ) ) . ',
	_wppaTextDelay = ' . wppa_opt( 'animation_speed' ) . ',
	wppaEasingSlide = "' . wppa_opt( 'easing_slide' ) . '",
	wppaEasingLightbox = "' . wppa_opt( 'easing_lightbox' ) . '",
	wppaEasingPopup = "' . wppa_opt( 'easing_popup' ) . '",
	wppaUploadButtonText = "' . __( 'Browse...', 'wp-photo-album-plus' ) . '",';

	/* wppa-lightbox.js */
	$result .= '
	wppaOvlBigBrowse = ' . ( wppa_ovl_big_browse() ? 'true' : 'false' ) . ',
	wppaOvlSmallBrowse = ' . ( wppa_ovl_small_browse() ? 'true' : 'false' ) . ',';

	/* ImageMagick */
	$result .= '
	wppaImageMagickDefaultAspect = "' . wppa_opt( 'image_magick_ratio' ) . '",';

	// Find ajax url
	$method = wppa_opt( 'ajax_method' );
	$can_ajax = 'true';
	if ( wppa_is_file( dirname( __FILE__ ) . '/wppa-ajax-front.php' ) && $method == 'extern' ) {
		if ( is_admin() ) $al = site_url() . '/wp-admin/admin-ajax.php';
		else $al = WPPA_URL . '/wppa-ajax-front.php';
	}
	else switch ( $method ) {
		case 'admin':
			$al = site_url() . '/wp-admin/admin-ajax.php';
			break;
		case 'none':
			$al = site_url() . '/wp-admin/admin-ajax.php';
			if ( ! is_admin() ) {
				$can_ajax = 'false';
			}
			break;
		default: // 'normal', and possibly 'extern' when file does not exist
			if ( is_admin() ) $al = site_url() . '/wp-admin/admin-ajax.php';
			else $al = ( wppa_switch( 'ajax_home' ) ? home_url() : site_url() ) . '/wppaajax';
			break;
	}
	$ajax_url = $al;

	/* Language and config specific inits */
	$result .= '
	wppaImageDirectory = "' . wppa_get_imgdir() . '",
	wppaWppaUrl = "' . wppa_get_wppa_url() . '",
	wppaIncludeUrl = "' . trim( includes_url(), '/' ) . '",
	wppaAjaxUrl = "' . $ajax_url . '",
	wppaUploadUrl = "' . WPPA_UPLOAD_URL . '",
	wppaIsIe = ' . ( wppa_is_ie() ? 'true' : 'false' ) . ',
	wppaIsSafari = ' . ( wppa_is_safari() ? 'true' : 'false' ) . ',
	wppaSlideshowNavigationType = "' . wppa_get_navigation_type() . '",
	wppaSlideshowDefaultTimeout = ' . wppa_opt( 'slideshow_timeout' ) / 1000 . ',
	wppaAudioHeight = ' . wppa_get_audio_control_height() . ',
	wppaFilmThumbTitle = "' . ( wppa_opt( 'film_linktype' ) == 'lightbox' ? wppa_zoom_in( false ) : __( 'Double click to start/stop slideshow running', 'wp-photo-album-plus' ) ) . '",
	wppaClickToView = "' . ( wppa_opt( 'film_linktype' ) == 'lightbox' ? wppa_zoom_in( false ) : __( 'Click to view', 'wp-photo-album-plus' ) ) . '",
	wppaLang = "' . $wppa_lang . '",
	wppaVoteForMe = "' . __( wppa_opt( 'vote_button_text' ), 'wp-photo-album-plus' ) . '",
	wppaVotedForMe = "' . __( wppa_opt( 'voted_button_text' ), 'wp-photo-album-plus' ) . '",
	wppaGlobalFsIconSize = "'.wppa_opt( 'nav_icon_size_global_fs' ) . '",
	wppaFsFillcolor = "'.wppa_opt( 'fs_svg_color' ) . '",
	wppaFsBgcolor = "'.wppa_opt( 'fs_svg_bg_color' ) . '",
	wppaFsPolicy = "' .  ( is_admin() ? 'none' : wppa_opt( 'fs_policy' ) ) . '",
	wppaNiceScroll = ' . ( wppa_is_nice() ? 'true' : 'false' ) . ',
	wppaNiceScrollOpts = {' . wppa_opt( 'nicescroll_opts' ) . '},
	wppaVersion = "' . $wppa_version . '",
	wppaBackgroundColorImage = "'.wppa_opt( 'bgcolor_img' ) . '",
	wppaPopupLinkType = "'.wppa_opt( 'thumb_linktype' ) . '",
	wppaAnimationType = "'.wppa_opt( 'animation_type' ) . '",
	wppaAnimationSpeed = '.wppa_opt( 'animation_speed' ) . ',
	wppaThumbnailAreaDelta = '.wppa_get_thumbnail_area_delta() . ',
	wppaTextFrameDelta = '.wppa_get_textframe_delta() . ',
	wppaBoxDelta = '.wppa_get_box_delta() . ',
	wppaFilmShowGlue = '.( wppa_switch( 'film_show_glue' ) ? 'true' : 'false' ) . ',
	wppaMiniTreshold = '.( wppa_opt( 'mini_treshold' ) ? wppa_opt( 'mini_treshold' ) : '0' ) . ',
	wppaRatingOnce = '.( wppa_switch( 'rating_change' ) || wppa_switch( 'rating_multi' ) ? 'false' : 'true' ) . ',
	wppaHideWhenEmpty = '.( wppa_switch( 'hide_when_empty' ) ? 'true' : 'false' ) . ',
	wppaBGcolorNumbar = "'.wppa_opt( 'bgcolor_numbar' ) . '",
	wppaBcolorNumbar = "'.wppa_opt( 'bcolor_numbar' ) . '",
	wppaBGcolorNumbarActive = "'.wppa_opt( 'bgcolor_numbar_active' ) . '",
	wppaBcolorNumbarActive = "'.wppa_opt( 'bcolor_numbar_active' ) . '",
	wppaFontFamilyNumbar = "'.wppa_opt( 'fontfamily_numbar' ) . '",
	wppaFontSizeNumbar = "'.wppa_opt( 'fontsize_numbar' ) . 'px",
	wppaFontColorNumbar = "'.wppa_opt( 'fontcolor_numbar' ) . '",
	wppaFontWeightNumbar = "'.wppa_opt( 'fontweight_numbar' ) . '",
	wppaFontFamilyNumbarActive = "'.wppa_opt( 'fontfamily_numbar_active' ) . '",
	wppaFontSizeNumbarActive = "'.wppa_opt( 'fontsize_numbar_active' ) . 'px",
	wppaFontColorNumbarActive = "'.wppa_opt( 'fontcolor_numbar_active' ) . '",
	wppaFontWeightNumbarActive = "'.wppa_opt( 'fontweight_numbar_active' ) . '",
	wppaNumbarMax = "'.wppa_opt( 'numbar_max' ) . '",
	wppaNextOnCallback = '.( wppa_switch( 'next_on_callback' ) ? 'true' : 'false' ) . ',
	wppaStarOpacity = '.str_replace(',', '.',( wppa_opt( 'star_opacity' )/'100' )) . ',
	wppaEmailRequired = "'.wppa_opt( 'comment_email_required' ) . '",
	wppaSlideBorderWidth = '.wppa_fbw().',
	wppaAllowAjax = '.$can_ajax.',
	wppaThumbTargetBlank = '.( wppa_switch( 'thumb_blank' ) ? 'true' : 'false' ) . ',
	wppaRatingMax = '.wppa_opt( 'rating_max' ) . ',
	wppaRatingDisplayType = "'.wppa_opt( 'rating_display_type' ) . '",
	wppaRatingPrec = '.wppa_opt( 'rating_prec' ) . ',
	wppaStretch = '.( wppa_switch( 'enlarge' ) ? 'true' : 'false' ) . ',
	wppaMinThumbSpace = '.wppa_opt( 'tn_margin' ) . ',
	wppaThumbSpaceAuto = '.( wppa_switch( 'thumb_auto' ) ? 'true' : 'false' ) . ',
	wppaMagnifierCursor = "'.wppa_opt( 'magnifier' ) . '",
	wppaAutoOpenComments = '.( wppa_switch( 'auto_open_comments' ) ? 'true' : 'false' ) . ',
	wppaUpdateAddressLine = '.( wppa_switch( 'update_addressline' ) ? 'true' : 'false' ) . ',
	wppaSlideSwipe = '.( wppa_switch( 'slide_swipe' ) ? 'true' : 'false' ) . ',
	wppaMaxCoverWidth = '.wppa_opt( 'max_cover_width' ) . ',
	wppaSlideToFullpopup = '.( wppa_opt( 'slideshow_linktype' ) == 'fullpopup' ? 'true' : 'false' ) . ',
	wppaComAltSize = '.wppa_opt( 'comten_alt_thumbsize' ) . ',
	wppaBumpViewCount = '.( wppa_switch( 'track_viewcounts' ) ? 'true' : 'false' ) . ',
	wppaBumpClickCount = '.( wppa_switch( 'track_clickcounts' ) ? 'true' : 'false' ) . ',
	wppaShareHideWhenRunning = '.( wppa_switch( 'share_hide_when_running' ) ? 'true' : 'false' ) . ',
	wppaFotomoto = '.( wppa_switch( 'fotomoto_on' ) ? 'true' : 'false' ) . ',
	wppaFotomotoHideWhenRunning = '.( wppa_switch( 'fotomoto_hide_when_running' ) ? 'true' : 'false' ) . ',
	wppaCommentRequiredAfterVote = '.( wppa_switch( 'vote_needs_comment' ) ? 'true' : 'false' ) . ',
	wppaFotomotoMinWidth = '.wppa_opt( 'fotomoto_min_width' ) . ',
	wppaOvlHires = '.( wppa_switch( 'lb_hres' ) ? 'true' : 'false' ) . ',
	wppaSlideVideoStart = '.( wppa_switch( 'start_slide_video' ) ? 'true' : 'false' ) . ',
	wppaSlideAudioStart = '.( wppa_switch( 'start_slide_audio' ) ? 'true' : 'false' ) . ',
	wppaOvlRadius = '.wppa_opt( 'ovl_border_radius' ) . ',
	wppaOvlBorderWidth = '.wppa_opt( 'ovl_border_width' ) . ',
	wppaThemeStyles = "'.(wppa_switch( 'upload_edit_theme_css' ) ? get_stylesheet_uri() : '' ) . '",
	wppaStickyHeaderHeight = '.wppa_opt( 'sticky_header_size' ) . ',
	wppaRenderModal = ' . ( wppa_switch( 'ajax_render_modal' ) ? 'true' : 'false' ) . ',
	wppaModalQuitImg = "url(' . wppa_get_imgdir( 'smallcross-' . wppa_opt( 'ovl_theme' ) . '.gif' ) . ' )",
	wppaBoxRadius = "' . wppa_opt( 'bradius' ) . '",
	wppaModalBgColor = "' . wppa_opt( 'bgcolor_modal' ) . '",
	wppaUploadEdit = "' . wppa_opt( 'upload_edit' ) . '",
	wppaSvgFillcolor = "' . wppa_opt( 'svg_color' ) . '",
	wppaSvgBgcolor = "' . wppa_opt( 'svg_bg_color' ) . '",
	wppaOvlSvgFillcolor = "' . wppa_opt( 'ovl_svg_color' ) . '",
	wppaOvlSvgBgcolor = "' . wppa_opt( 'ovl_svg_bg_color' ) . '",
	wppaSvgCornerStyle = "' . wppa_opt( 'icon_corner_style' ) . '",
	wppaHideRightClick = ' . ( wppa_switch( 'no_rightclick' ) ? 'true' : 'false' ) . ',
	wppaGeoZoom = ' . wppa_opt( 'geo_zoom' ) . ',
	wppaLazyLoad = ' . ( wppa_lazy() ? 'true' : 'false' ) . ',
	wppaAreaMaxFrac = ' . ( wppa_opt( 'area_size' ) < 1 ? wppa_opt( 'area_size' ) : 1.0 ) . ',
	wppaAreaMaxFracSlide = ' . ( wppa_opt( 'area_size_slide' ) < 1 ? wppa_opt( 'area_size_slide' ) : 1.0 ) . ',
	wppaAreaMaxFracAudio = ' . ( wppa_opt( 'area_size_audio' ) < 1 ? wppa_opt( 'area_size_audio' ) : 1.0 ) . ',
	wppaIconSizeNormal = "' . wppa_opt( 'nav_icon_size' ) . '",
	wppaIconSizeSlide = "' . wppa_opt( 'nav_icon_size_slide' ) . '",
	wppaResponseSpeed = ' . wppa_opt( 'response_speed' ) . ',
	wppaExtendedResizeCount = ' . wppa_opt( 'extended_resize_count' ) . ',
	wppaExtendedResizeDelay = ' . wppa_opt( 'extended_resize_delay' ) . ',
	wppaCoverSpacing = ' . wppa_opt( 'cover_spacing' ) . ',
	wppaFilmonlyContinuous = ' . ( wppa_switch( 'filmonly_continuous' ) ? 'true' : 'false' ) . ',
	wppaNoAnimateOnMobile = ' . ( wppa_switch( 'no_animate_on_mobile' ) ? 'true' : 'false' ) . ',
	wppaAjaxScroll = ' . ( wppa_switch( 'ajax_scroll' ) && ! is_admin() ? 'true' : 'false' ) . ',
	wppaThumbSize = ' . wppa_opt( 'thumbsize' ) . ',
	wppaTfMargin = ' . wppa_opt( 'tn_margin' ) . ',
	wppaRequestInfoDialogText = "' . wppa_opt( 'request_info_text' ) . '",
	wppaThumbAspect = ' . wppa_thumb_asp() . ',';

	/* Lightbox vars */
	$fontsize_lightbox = wppa_opt( 'fontsize_lightbox' ) ? wppa_opt( 'fontsize_lightbox' ) : '10';
	$d = wppa_switch( 'ovl_show_counter') ? 1 : 0;
	$ovlh = wppa_opt( 'ovl_txt_lines' ) == 'auto' ? 'auto' : ((wppa_opt( 'ovl_txt_lines' ) + $d) * ($fontsize_lightbox + 2));
	$lb_global = '';
	if ( wppa_switch( 'lightbox_global' ) && ! is_admin() ) {
		if ( wppa_switch( 'lightbox_global_set' ) ) {
			$lb_global = 'wppa[single]';
		}
		else {
			$lb_global = 'wppa';
		}
	}

	$result .= '
	wppaOvlTxtHeight = "'.$ovlh.'",
	wppaOvlOpacity = '.(wppa_opt( 'ovl_opacity' )/100).',
	wppaOvlOnclickType = "'.wppa_opt( 'ovl_onclick' ).'",
	wppaOvlTheme = "'.wppa_opt( 'ovl_theme' ).'",
	wppaOvlAnimSpeed = '.wppa_opt( 'ovl_anim' ).',
	wppaOvlSlideSpeed = '.wppa_opt( 'ovl_slide' ).',
	wppaVer4WindowWidth = 800,
	wppaVer4WindowHeight = 600,
	wppaOvlShowCounter = '.( wppa_switch( 'ovl_show_counter') ? 'true' : 'false' ).',
	wppaOvlFontFamily = "'.wppa_opt( 'fontfamily_lightbox' ).'",
	wppaOvlFontSize = "'.$fontsize_lightbox.'",
	wppaOvlFontColor = "'.wppa_opt( 'fontcolor_lightbox' ).'",
	wppaOvlFontWeight = "'.wppa_opt( 'fontweight_lightbox' ).'",
	wppaOvlLineHeight = "'.wppa_opt( 'fontsize_lightbox' ).'",
	wppaOvlVideoStart = '.( wppa_switch( 'ovl_video_start' ) ? 'true' : 'false' ).',
	wppaOvlAudioStart = '.( wppa_switch( 'ovl_audio_start' ) ? 'true' : 'false' ).',
	wppaOvlShowStartStop = '.( wppa_switch( 'ovl_show_startstop' ) ? 'true' : 'false' ).',
	wppaIsMobile = '.( wppa_is_mobile() ? 'true' : 'false' ).',
	wppaIsIpad = '.( wppa_is_ipad() ? 'true' : 'false' ).',
	wppaOvlIconSize = "'.wppa_opt( 'nav_icon_size_lightbox' ).'px",
	wppaOvlBrowseOnClick = '.( wppa_switch( 'ovl_browse_on_click' ) ? 'true' : 'false' ).',
	wppaOvlGlobal = ' . ( $lb_global ? '"' . $lb_global . '"' : 'false' ) . ',
	wppaPhotoDirectory = "'.WPPA_UPLOAD_URL.'/",
	wppaThumbDirectory = "'.WPPA_UPLOAD_URL.'/thumbs/",
	wppaTempDirectory = "'.WPPA_UPLOAD_URL.'/temp/",
	wppaFontDirectory = "'.WPPA_UPLOAD_URL.'/fonts/",
	wppaOutputType = "' . wppa_opt( 'photo_shortcode_fe_type' ) . '";';

	// Tinymce photo
	if ( wppa_switch( 'photo_shortcode_enabled' ) ) {
		$id = $wpdb->get_var( "SELECT id FROM $wpdb->wppa_photos
		WHERE ext <> 'xxx'
		AND panorama = 0
		ORDER BY timestamp DESC
		LIMIT 1" );

		// Fake we are in a widget, to prevent wppa_get_picture_html() from bumping viewcount
		wppa( 'in_widget', true );

		// Any photo found?
		if ( $id ) {
			$result .= 'var
			wppaShortcodeTemplate = "' . esc_js( wppa_get_picture_html( array( 'id' => $id, 'type' => 'sphoto' ) ) ) . '";
			wppaShortcodeTemplateId = "' . $id . '.' . wppa_get_photo_item( $id, 'ext' ) . '";';
		}

		// No, nothing yet
		else {
			$result .= 'var
			wppaShortcodeTemplate = "";
			wppaShortcodeTemplateId = "";';
		}

		// Reset faked widget
		wppa( 'in_widget', false );
	}

//	$result .= 'jQuery(document).ready(function(){});';

	// The photo views cache
	if ( isset( $wppa_session['photo'] ) ) {
		foreach ( array_keys( $wppa_session['photo'] ) as $p ) {
		$result .= '
		jQuery(document).ready(function(){wppaPhotoView[' . $p . '] = true;});';
		}
	}

	// Format
	$result = wppa_compress_js( $result );

	return $result;
}

function wppa_thumb_asp() {

	$aspect = 1;
	if ( ! wppa_opt( 'thumb_aspect' ) ) {
		return $aspect;
	}
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

function wppa_fbw() {

	if ( is_numeric( wppa_opt( 'fullimage_border_width' ) ) ) {
		$fbw = wppa_opt( 'fullimage_border_width' ) + '1';
	}
	else {
		$fbw = '0';
	}
	return $fbw;
}

/* LOAD JAVASCRIPT */
add_action( 'init', 'wppa_add_javascripts' );

// This function does the actual js enqueueing
function wppa_add_javascripts() {
global $wppa_version;
global $wppa_lang;
global $wppa_opt;
// global $wppa_jquery_loaded;

	// Global array decls in the header, no depts
	$decl_file = dirname( __FILE__ ) . '/js/wppa-decls.js';
	$js_ver = date( "ymd-Gis", filemtime( $decl_file ) );
	wppa_enqueue_script( 'wppa-decls', WPPA_URL . '/js/wppa-decls.js', array(), $js_ver );




	$footer = false; // true;

//	$footer = ['in_footer' => true, 'strategy' => 'defer'];

	// The js dependancies
	$js_depts = array(	'jquery',
						'jquery-form',
						'jquery-masonry',
						'jquery-ui-dialog',
						'wp-i18n',
	);

	// First see if an 'all' file is present. This is to save http requests
	$all_file = dirname( __FILE__ ) . '/js/wppa-all.js';
	if ( wppa_is_file( $all_file ) ) {
		$js_ver = date( "ymd-Gis", filemtime( $all_file ) );
		wp_enqueue_script( 'wppa-all', WPPA_URL . '/js/wppa-all.js', $js_depts, $js_ver, $footer );

//		$wppa_jquery_loaded = true;
	}

	// No all file, do them one by one
	else {
		$js_files = array(
		'wppa-utils',
		'wppa-main',
		'wppa-slideshow',
		'wppa-ajax-front',
		'wppa-lightbox',
		'wppa-popup',
		'wppa-touch',
		'wppa-zoom',
		'wppa-spheric',
		'wppa-flatpan',
		);

		foreach ( array_keys( $js_files ) as $idx ) {
			$js_ver = date( "ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/' . $js_files[$idx] . '.js' ) );
			wp_enqueue_script( $js_files[$idx], WPPA_URL . '/js/' . $js_files[$idx] . '.js', $js_depts, $js_ver, $footer );

//			$wppa_jquery_loaded = true;
		}
	}

	// Anchor for inline scripts
	wp_enqueue_script( 'wppa', WPPA_URL . '/js/wppa.js', $js_depts, 'dummy', true );

	// Add language files
	wppa_add_js_language_files( 'wppa' );

	// Add inits
	$the_js = 'const { __ } = wp.i18n;';
	wppa_add_inline_script( 'wppa', $the_js );
	wppa_add_inline_script( 'wppa', wppa_initialize_javascript() );

	// google maps
	// Not in ajax to avoid duplicate loading
	if ( ! wppa_get( 'wppa-action' ) ) {
		if ( wppa_switch( 'save_gpx' ) && strpos( wppa_opt( 'custom_content' ), 'w#location' ) !== false ) {
			$key = wppa_opt( 'map_apikey' );
			wppa_enqueue_script( 	'wppa-geo',
			'https://maps.googleapis.com/maps/api/js?' . ( $key ? 'key=' . $key : 'v=3.exp' ),
			$js_depts,
			$wppa_version,
			$footer );
		}
	}

	// Skip this when url like 	https://.../wp-admin/post-new.php
	// or like 					https://.../wp-admin/post.php?post=2897&action=edit
	// or like 					https://.../wp-admin/post-new.php?post_type=page
	// or like 					https://.../wp-admin/edit.php?post_type=page
	// This is required, otherwise the multiple selection boxes in shortcode generators do not scroll on wheel, but the background scrolls
	$uri = $_SERVER['REQUEST_URI'];
	$void = ['wp-admin/post-new.php', 'wp-admin/post.php?', 'wp-admin/edit.php?'];
	$skip = false;
	foreach( $void as $v ) {
		if ( strpos( $uri, $v ) !== false ) $skip = true;
		if ( $skip ) continue;
	}
	if ( $skip ) {
		global $wppa;
		$wppa['wppa_nicescroll'] = 'no';
		$wppa['wppa_nicescroll_window'] = 'no';
	}
	else {

		// Nicescroller
		if ( wppa_is_nice() || wppa_is_nice( 'window' ) || wppa_switch( 'load_nicescroller' ) ) {
			$nice_url = WPPA_URL . '/vendor/nicescroll/jquery.nicescroll.min.js';
			wppa_enqueue_script( 'nicescrollr-inc-nicescroll-min-js', $nice_url, $js_depts, $wppa_version, $footer );
		}
	}

	// Easing we need, borrow it from nicescroller
	$easing_url = WPPA_URL . '/vendor/jquery-easing/jquery.easing.min.js';
	wppa_enqueue_script( 'nicescrollr-easing-min-js', $easing_url, $js_depts, $wppa_version, $footer );

	// Panorama
	if ( wppa_switch( 'enable_panorama' ) ) {
		$three_url = WPPA_URL . '/vendor/three/three.min.js';
		$ver = '122';
		wppa_enqueue_script( 'wppa-three-min-js', $three_url, $js_depts, $wppa_version, $footer );
	}

	// Window nicescroller
	if ( wppa_is_nice( 'window' ) ) {
		$the_js = '
		jQuery("document").ready(function(){
			if (jQuery().niceScroll)
			jQuery("body").niceScroll({'.wppa_opt( 'nicescroll_opts' ).'});
		});';
		wppa_add_inline_script( 'wppa', $the_js, true );
	}

	// Pinterest
	if ( ( wppa_switch( 'share_on') || wppa_switch( 'share_on_widget') ) && wppa_switch( 'share_pinterest') ) {
		wppa_enqueue_script( 'wppa-pinterest', "//assets.pinterest.com/js/pinit.js", $js_depts, $wppa_version, $footer );
	}

	// The lightbox overlay html
	$the_js = '
	jQuery("body").append(\'
	<div
		id="wppa-overlay-bg"
		style="text-align:center;display:none;position:fixed;top:0;left:0;width:100%;height:10000px;background-color:black"
		onclick="wppaOvlOnclick(event)"
		onwheel="return false;"
		onscroll="return false;">
	</div>
	<div
		id="wppa-overlay-ic"
		onwheel="return false;"
		onscroll="return false;">
	</div>
	<div
		id="wppa-overlay-pc"
		onwheel="return false;"
		onscroll="return false;">
	</div>
	<div
		id="wppa-overlay-fpc"
		onwheel="return false;"
		onscroll="return false;">
	</div>
	<div
		id="wppa-overlay-zpc"
		onwheel="return false;"
		onscroll="return false;">
	</div>
	<img
		id="wppa-pre-prev"
		style="position:fixed;left:0;top:50%;width:100px;visibility:hidden"
		class="wppa-preload wppa-ovl-preload">
	<img
		id="wppa-pre-next"
		style="position:fixed;right:0;top:50%;width:100px;visibility:hidden"
		class="wppa-preload wppa-ovl-preload">
	<img
		id="wppa-pre-curr"
		style="position:fixed;left:0;top:0;visibility:hidden"
		class="wppa-preload-curr wppa-ovl-preload">' .
		wppa_get_spinner_svg_html( array( 	'id' 		=> 'wppa-ovl-spin',
											'position' 	=> 'fixed',
											'lightbox' 	=> true,
										) ) . '\');';

	wppa_add_inline_script( 'wppa', wppa_compress_html( $the_js ) );

	// Nonce field for Ajax bump view counter from lightbox, and rating
	$the_js = '
	jQuery("body").append(\'' .
	wp_nonce_field( 'wppa-check', 'wppa-nonce', false, false ) .
	wp_nonce_field( 'wppa-qr-nonce', 'wppa-qr-nonce', false, false ) . '\')';

	wppa_add_inline_script( 'wppa', wppa_compress_html( $the_js ) );

}

add_action( 'wp_head', 'wppa_set_jq_loaded', 999 );
add_action( 'admin_head', 'wppa_set_jq_loaded', 999 );
function wppa_set_jq_loaded() {
global $wppa_jquery_loaded;
	$wppa_jquery_loaded = true;
}

// For some reason no one understands, the js language files are sometimes not loaded, so we do it here manually
function wppa_add_js_language_files( $where ) {
	$files = wppa_glob( WPPA_CONTENT_PATH . '/languages/plugins/wp-photo-album-plus-'. get_locale() . '*.json' );
	if ( count( $files ) ) {
		foreach( $files as $file ) {
			$the_js = '
( function( domain, translations ) {
	var localeData = translations.locale_data[ domain ] || translations.locale_data.messages;
	localeData[""].domain = domain;
	wp.i18n.setLocaleData( localeData, domain );
} )( "wp-photo-album-plus", ' . wppa_get_contents( $file ) . ');';
			wppa_add_inline_script( $where, $the_js );
		}
	}
}

// Add script to the page specific data. Input text should have no <script> tag
// If admin or no defer js or cached: output with tags by wppa_out()
function wppa_js( $txt, $now = false ) {
global $wppa_js_page_data;
global $wppa_script_open;
global $wppa_gutenberg_preview;

	// Do it
	if ( wppa_is_caching() || $now || $wppa_gutenberg_preview || defined( 'DOING_AJAX' ) || wppa_in_widget() ) {
/*
		if ( defined( 'DOING_AJAX' ) ) {
			wppa_out( '<script>jQuery(document).ready(function(){' . $txt . '})</script>' );
		}
		else {
			wppa_add_inline_script( 'wppa', 'jQuery(document).ready(function(){' . $txt . '})' );
		}
*/
		wppa_out( '<script>jQuery(document).ready(function(){' . $txt . '})</script>' );
		return;
	}

	if ( ! $wppa_script_open ) {
		$wppa_js_page_data = $txt;
		$wppa_script_open = true;
	}
	else {
		$wppa_js_page_data .= '
			' . $txt;
	}
}

// Output page specific script in the footer.
// This has only content when defer js is on and not admin and anything not cached
add_action( 'wp_footer', 'wppa_print_psjs' );

function wppa_print_psjs( $return = false ) {
global $wppa_js_page_data;
global $wppa_script_open;

	if ( $wppa_js_page_data ) {

		$result = wppa_compress_js( $wppa_js_page_data );

		if ( $return ) {
			return $result;
		}
		else {
			wppa_add_inline_script( 'wppa', 'jQuery(document).ready(function(){' . $result . '})' );
		}

		$wppa_js_page_data = '';
		$wppa_script_open = false;
	}

	return false;
}

// Compress javascript
function wppa_compress_js( $js ) {

	if ( ! $js ) return '';

	$js = str_replace( "\t", " ", $js );
	$js = str_replace( ["  ", "   ", "    ", "     "], " ", $js );
	$js = str_replace( ["  ", "   ", "    ", "     "], " ", $js );
	$js = str_replace( "; ", ";", $js );
	$js = str_replace( "} ", "}", $js );
	$js = str_replace( "{ ", "{", $js );
	$js = str_replace( ": ", ":", $js );
//	$js = str_replace( ", ", ",", $js ); // damages text
	$js = str_replace( " + ", "+", $js );
	$js = str_replace( " = ", "=", $js );

	$js = str_replace( "\n ", "\n", $js );
	$js = str_replace( "\n\n", "\n", $js );
	return $js;
}

