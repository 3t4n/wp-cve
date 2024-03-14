<?php
/*
Plugin Name: Weaver Themes Shortcode Compatibility
Plugin URI: http://weavertheme.com
Description: Weaver Theme Compatibility - Allows you to use various Weaver II and/or Weaver Xtreme shortcodes with any theme. There is no associated Settings page for this plugin.
Author: Bruce Wampler
Author URI: http://weavertheme.com/about
Version: 1.0.4
License: GPL

GPL License: http://www.opensource.org/licenses/gpl-license.php

This program is distributed in the hope that it will be useful, but
WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/


define ('WEAVER_COMPATIBILITY_VERSION','Weaver Themes Shortcode Compatibility Plugin');
define ('WEAVER_COMPATIBILITY_VN', '1.0.4');
define ('WVR_COMPATIBILITY_MINIFY', '.min');

$cur_theme = wp_get_theme();
$parent = $cur_theme->parent(); // might be a child, so see if Weaver II is parent...
if ($parent)
    $cur_theme = $parent;

	// ========================================= >>> wvr_compat_deactivate <<< ===============================

function wvr_compat_deactivate() {	// deactivate


}
register_deactivation_hook( __FILE__, 'wvr_compat_deactivate' );

// ***************************************  NOT WEAVER II  || NOT WEAVER XTREME ******************************************
// This is the shared code for shortcodes common to Weaver II and Weaver Xtreme - needs to be first

if ( strpos($cur_theme->Name, 'Weaver Xtreme' ) === false || strpos($cur_theme->Name, 'Weaver II' ) === false
	|| strpos($cur_theme->Name, 'Aspen' ) === false) {

	function wvr_compat_ex_wphead() {	// add to head stream as a comment  :: Both Weaver II and Weaver Xtreme
    printf("\n<!-- Weaver Theme Compatibility: %s -->\n",WEAVER_COMPATIBILITY_VERSION);
}
add_action('wp_head', 'wvr_compat_ex_wphead');


function wvr_compat_plugins_loaded() {
    add_action( 'wp_enqueue_scripts', 'wvr_compat_enqueue_scripts' );
}
add_action( 'plugins_loaded', 'wvr_compat_plugins_loaded');

// ========================================= >>> wvr_compat_enqueue_scripts <<< ===============================

function wvr_compat_enqueue_scripts() {	// enqueue runtime scripts

    $at_end = true;

    wp_enqueue_script('wvrc-compat-js',
        plugins_url('/includes/wvr.compatibility' . WVR_COMPATIBILITY_MINIFY . '.js',__FILE__),array('jquery'),
        WEAVER_COMPATIBILITY_VN, $at_end);

	wp_enqueue_style('wvrc-compat-style',
		plugins_url('weaver-theme-compatibility' . WVR_COMPATIBILITY_MINIFY . '.css',__FILE__), array(), WEAVER_COMPATIBILITY_VN);
}



// ===============  [weaver_bloginfo arg='name'] ====================== :: Both Weaver II and Weaver Xtreme
function wvr_compat_sc_bloginfo($args = '') {
    extract(shortcode_atts(array(
	    'arg' => 'name'		/* styling for the header */
    ), $args));

    return esc_attr( get_bloginfo( $arg ));
}



// ===============  [weaver_header_image style='customstyle'] =================== :: Both Weaver II and Weaver Xtreme
function wvr_compat_sc_header_image($args = '') {
    extract(shortcode_atts(array(
	    'style' => '',	// STYLE
	    'h' => '',
	    'w' => ''
    ), $args));

    $width = $w ? ' width="' . $w . '"' : '';
    $height = $h ? ' height="' . $h . '"' : '';
    $st = $style ? ' style="' . $style . '"' : '';
    $hdrimg = '<img src="' . get_header_image() . '"' . $st . $width . $height
            . ' alt="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" />' ;
    return $hdrimg;
}


// ===============  [html style='customstyle'] ====================== :: Both Weaver II and Weaver Xtreme

function wvr_compat_sc_html($vals = '') {
    $tag = 'span';
    if ( isset ( $vals[0] ) )
        $tag = trim( $vals[0]);

    extract(shortcode_atts(array(
        'args' => ''
    ), $vals));
    if ($args) $args = ' ' . $args;
    return '<' . $tag . $args .  '>';
}



// ===============  [weaver_iframe src='address' height=nnn] ====================== :: Both Weaver II and Weaver Xtreme
function wvr_compat_sc_iframe($args = '') {
    extract(shortcode_atts(array(
	    'src' => '',
	    'height' => '600', /* styling for the header */
	    'percent' => 100,
	    'style' => 'border:1px;'
    ), $args));

    $sty = $style ? ' style="' . $style . '"' : '';

    if (!$src) return '<h4>No src address provided to [weaver_iframe].</h4>';
    return "\n" . '<iframe src="' . $src . '" height="' .  $height . 'px" width="' . $percent . '%"' . $sty . '></iframe>' . "\n";
}




// ===============  [weaver_site_title style='customstyle'] ====================== :: Both Weaver II and Weaver Xtreme
function wvr_compat_sc_site_title($args = '') {
	extract(shortcode_atts(array(
	    'style' => '',		/* styling for the header */
		'matchtheme' => false
    ), $args));

	$title = esc_html( get_bloginfo( 'name', 'display' ));

	$before = '';
	$after = '';

	if ( $matchtheme == 'true' || $matchtheme == 1 ) {
		$before = '<h1><a href="' .  esc_url( home_url( '/' ) ) . '" title="' . $title . '" rel="home">';
		$after = '</a></h1>';
	}

    if ($style) {
        return $before . '<span style="' . $style . '">' . $title . '</span>' . $after;
    }
    return $before . $title . $after;

}

// ===============  [weaver_site_desc/tagline style='customstyle'] ====================== :: Both Weaver II and Weaver Xtreme
function wvr_compat_sc_site_desc($args = '') {
    extract(shortcode_atts(array(
	    'style' => '',		/* styling for the header */
		'matchtheme' => false
    ), $args));

    $title = get_bloginfo( 'description' );

	$before = '';
	$after = '';

	if ( $matchtheme == 'true' || $matchtheme == 1 ) {
		$before = '<h2>';
		$after = '</h2>';
	}

    if ($style) {
        return $before . '<span style="' . $style . '">' . $title . '</span>' . $after;
    }
    return $before . $title . $after;
}

} // end of not Weaver II || not Weaver Xtreme


// ***************************************  NOT WEAVER II && NOT WEAVER XTREME ******************************************


// ========================================= >>> wvr_compat_the_footer <<< ===============================



function wvr_compat_sc_div($vals = '',$text) {
    extract(shortcode_atts(array(
        'id' => '',
        'class' => '',
        'style' => ''
    ), $vals));

    $args = '';
    if ($id) $args .= ' id="' . $id . '"';
    if ($class) $args .= ' class="' . $class . '"';
    if ($style) $args .= ' style="' . $style . '"';

    return '<div' . $args . '>' . do_shortcode($text) . '</div>';
}

add_shortcode('div', 'wvr_compat_sc_div');

function wvr_compat_sc_span($vals = '',$text) {
    extract(shortcode_atts(array(
        'id' => '',
        'class' => '',
        'style' => ''
    ), $vals));

    $args = '';
    if ($id) $args .= ' id="' . $id . '"';
    if ($class) $args .= ' class="' . $class . '"';
    if ($style) $args .= ' style="' . $style . '"';

    return '<span' . $args . '>' . do_shortcode($text) . '</span>';
}

add_shortcode('span', 'wvr_compat_sc_span');




// ***************************************  NOT WEAVER II ******************************************
if ( strpos($cur_theme->Name, 'Weaver II' ) === false && strpos($cur_theme->Name, 'Aspen' ) === false) {  // only need this if Weaver II Not installed

	/* PART 1 - Extras hooks - market, update */

	add_action( 'plugins_loaded', 'wvr_compat_wii_plugins_loaded');

function wvr_compat_wii_plugins_loaded() {
    add_action( 'wp_footer','wvr_compat_the_footer_late', 99);	// make it 12 to load late
}

function wvr_compat_the_footer_late() {
    echo "<script type='text/javascript'>jQuery('#wrapper').fitVids();</script>\n";
}


/* PART 2 - Shortcodes */

// ===============  [weaver_header_image style='customstyle'] ===================


add_shortcode('weaver_bloginfo', 'wvr_compat_sc_bloginfo');
add_shortcode('aspen_bloginfo', 'wvr_compat_sc_bloginfo');

add_shortcode('weaver_header_image', 'wvr_compat_sc_header_image');
add_shortcode('aspen_header_image', 'wvr_compat_sc_header_image');

add_shortcode('weaver_html', 'wvr_compat_sc_html');
add_shortcode('aspen_html', 'wvr_compat_sc_html');

add_shortcode('weaver_iframe', 'wvr_compat_sc_iframe');
add_shortcode('aspen_iframe', 'wvr_compat_sc_iframe');

add_shortcode('weaver_site_title', 'wvr_compat_sc_site_title');
add_shortcode('aspen_site_title', 'wvr_compat_sc_site_title');

add_shortcode('weaver_site_desc', 'wvr_compat_sc_site_desc');	// site_title for WX
add_shortcode('aspen_site_desc', 'wvr_compat_sc_site_desc');	// site_title for WX

function wvr_compat_sc_wii_vimeo($args) {
	require_once(dirname( __FILE__ ) . '/includes/vimeo.php' );
	return wvr_compat_do_vimeo($args);

}
add_shortcode('weaver_vimeo', 'wvr_compat_sc_wii_vimeo');
add_shortcode('aspen_vimeo', 'wvr_compat_sc_wii_vimeo');

function wvr_compat_sc_wii_youtube($args) {
	require_once(dirname( __FILE__ ) . '/includes/youtube.php' );
	return wvr_compat_do_youtube($args);

}
add_shortcode('weaver_youtube', 'wvr_compat_sc_wii_youtube');
add_shortcode('aspen_youtube', 'wvr_compat_sc_wii_youtube');


// -------------------------- Weaver II Only Shortcode functions --------------------------

function wvr_compat_posts_shortcode($args = '') {
    /* implement [weaver_show_posts]  */

	return '<strong>&#91;weaver_show_posts] replaced by ATW &#91;show_posts] plugin.</strong> ';
}
add_shortcode('weaver_show_posts', 'wvr_compat_posts_shortcode');	// weaver ii only

function wvr_compat_aposts_shortcode($args = '') {
    /* implement [weaver_show_posts]  */

	return '<strong>&#91;aspen_show_posts] replaced by ATW &#91;show_posts] plugin.</strong> ';
}
add_shortcode('aspen_show_posts', 'wvr_compat_aposts_shortcode');

function wvr_compat_aslider_shortcode($args = '') {
    /* implement [weaver_show_posts]  */

	return '<strong>&#91;aspen_slider] replaced by ATW &#91;show_sliders] plugin.</strong> ';
}
add_shortcode('aspen_slider', 'wvr_compat_aslider_shortcode');	// weaver ii only



// ===============  [weaver_breadcrumbs style='customstyle'] ======================
function wvr_compat_sc_breadcrumbs($args = '') {
    extract(shortcode_atts(array(
	    'style' => '',
	    'class' => 'breadcrumbs' /* styling for the header */
    ), $args));
	require_once(dirname( __FILE__ ) . '/includes/breadcrumbs.php' );
    $title = wvr_compat_breadcrumb(false, $class);

    if ($style) {
        return '<span style="' . $style . '">' . $title . '</span>';
    }
    return $title;

}
add_shortcode('weaver_breadcrumbs', 'wvr_compat_sc_breadcrumbs');
add_shortcode('aspen_breadcrumbs', 'wvr_compat_sc_breadcrumbs');



// @@@@ breadcrumb function

// ===============  [weaver_pagenav style='customstyle'] ======================
function wvr_compat_sc_pagenav($args = '') {
    extract(shortcode_atts(array(
	    'style' => '',
	    'end_size' => '1',
	    'mid_size' => '2',
	    'error_msg' => ''

    ), $args));
	require_once(dirname( __FILE__ ) . '/includes/pagenav.php' );
    $title = wvr_compat_get_paginate_archive_page_links( 'plain',$end_size,$mid_size );

    if (!$title) return $error_msg;

    if ($style) {
        return '<span style="' . $style . '">' . $title . '</span>';
    }
    return $title;
}

add_shortcode('weaver_pagenav', 'wvr_compat_sc_pagenav');
add_shortcode('aspen_pagenav', 'wvr_compat_sc_pagenav');


// ===============  [weaver_show_if_mobile style='customstyle'] ======================
function wvr_compat_sc_show_if_mobile($args = '',$text) {
    extract(shortcode_atts(array(
	    'type' => 'mobile'		// mobile, smalltablet, tablet, any
    ), $args));

    if ($type == 'touch' || $type == 'any') $type = 'mobile';
    return '<span class="wvr-show-' . $type . '">' . do_shortcode($text) . '</span>';

    return '';
}

add_shortcode('weaver_show_if_mobile', 'wvr_compat_sc_show_if_mobile');
add_shortcode('aspen_show_if_mobile', 'wvr_compat_sc_show_if_mobile');

// ===============  [weaver_show_if_logged_in] ======================
function wvr_compat_sc_show_if_logged_in($args = '',$text) {

    if (is_user_logged_in()) {
        return do_shortcode($text);
    }
    return '';
}

add_shortcode('weaver_show_if_logged_in', 'wvr_compat_sc_show_if_logged_in');
add_shortcode('aspen_show_if_logged_in', 'wvr_compat_sc_show_if_logged_in');

function wvr_compat_sc_hide_if_logged_in($args = '',$text) {

    if (!is_user_logged_in()) {
        return do_shortcode($text);
    }
    return '';
}

add_shortcode('weaver_hide_if_logged_in', 'wvr_compat_sc_hide_if_logged_in');
add_shortcode('aspen_hide_if_logged_in', 'wvr_compat_sc_hide_if_logged_in');


function wvr_compat_sc_hide_if_mobile($args = '',$text) {
    extract(shortcode_atts(array(
	    'type' => 'mobile'		// mobile, touch, tablet, any
    ), $args));

    if ($type == 'touch' || $type == 'any') $type = 'mobile';
    return '<span class="wvr-hide-' . $type . '">' . do_shortcode($text) . '</span>';
}

add_shortcode('weaver_hide_if_mobile', 'wvr_compat_sc_hide_if_mobile');
add_shortcode('aspen_hide_if_mobile', 'wvr_compat_sc_hide_if_mobile');

// ===============  [tabs] ===================
function wvr_acompat_sc_tab_group($args = '', $text ) {
	require_once(dirname( __FILE__ ) . '/includes/tabs.php' );
    return wvr_compat_do_tab_group( $args, $text, true );
}

add_shortcode('aspen_tab_group', 'wvr_acompat_sc_tab_group');

function wvr_acompat_sc_tab($args = '', $text ) {
	require_once(dirname( __FILE__ ) . '/includes/tabs.php' );
    return wvr_compat_do_tab( $args, $text, true );
}

add_shortcode('aspen_tab', 'wvr_acompat_sc_tab');

} // end of NOT WEAVER II



// ***************************************  NOT WEAVER XTREME ******************************************
if ( strpos($cur_theme->Name, 'Weaver Xtreme' ) === false) {  // only need this if Weaver Xtreme Not installed

add_shortcode('bloginfo', 'wvr_compat_sc_bloginfo');
add_shortcode('wvrx_bloginfo', 'wvr_compat_sc_bloginfo');

add_shortcode('header_image', 'wvr_compat_sc_header_image');
add_shortcode('wvrx_header_image', 'wvr_compat_sc_header_image');

add_shortcode('html', 'wvr_compat_sc_html');
add_shortcode('wvrx_html', 'wvr_compat_sc_html');

add_shortcode('iframe', 'wvr_compat_sc_iframe');
add_shortcode('wvrx_iframe', 'wvr_compat_sc_iframe');

add_shortcode('site_title', 'wvr_compat_sc_site_title');
add_shortcode('wvrx_site_title', 'wvr_compat_sc_site_title');

add_shortcode('site_tagline', 'wvr_compat_sc_site_desc');	// site_title for WX
add_shortcode('wvrx_site_tagline', 'wvr_compat_sc_site_desc');	// site_title for WX

function wvr_compat_sc_wx_vimeo($args) {
	require_once(dirname( __FILE__ ) . '/includes/vimeo.php' );
	return wvr_compat_do_vimeo($args);

}
add_shortcode('vimeo', 'wvr_compat_sc_wx_vimeo');
add_shortcode('wvrx_vimeo', 'wvr_compat_sc_wx_vimeo');

function wvr_compat_sc_wx_youtube($args) {
	require_once(dirname( __FILE__ ) . '/includes/youtube.php' );
	return wvr_compat_do_youtube($args);

}
add_shortcode('youtube', 'wvr_compat_sc_wx_youtube');
add_shortcode('wvrx_youtube', 'wvr_compat_sc_wx_youtube');



// ===============  [hide_if] ===================
function wvr_compat_sc_hide_if($args = '', $text ) {
	require_once(dirname( __FILE__ ) . '/includes/show-hide.php' );
    return wvr_compat_show_hide_if( $args, $text, false );
}
add_shortcode('hide_if', 'wvr_compat_sc_hide_if');
add_shortcode('wvrx_hide_if', 'wvr_compat_sc_hide_if');

// ===============  [show_if] ===================
function wvr_compat_sc_show_if($args = '', $text ) {
	require_once(dirname( __FILE__ ) . '/includes/show-hide.php' );
    return wvr_compat_show_hide_if( $args, $text, true );
}
add_shortcode('show_if', 'wvr_compat_sc_show_if');
add_shortcode('wvrx_show_if', 'wvr_compat_sc_show_if');

// ===============  [tabs] ===================
function wvr_compat_sc_tab_group($args = '', $text ) {
	require_once(dirname( __FILE__ ) . '/includes/tabs.php' );
    return wvr_compat_do_tab_group( $args, $text, true );
}
add_shortcode('tab_group', 'wvr_compat_sc_tab_group');
add_shortcode('wvrx_tab_group', 'wvr_compat_sc_tab_group');

function wvr_compat_sc_tab($args = '', $text ) {
	require_once(dirname( __FILE__ ) . '/includes/tabs.php' );
    return wvr_compat_do_tab( $args, $text, true );
}
add_shortcode('tab', 'wvr_compat_sc_tab');
add_shortcode('wvrx_tab', 'wvr_compat_sc_tab');
} // not Weaver Xtreme

// ===============  [box] ===================
function wvr_compat_sc_box($args = '', $text ) {
	require_once(dirname( __FILE__ ) . '/includes/box.php' );
    return wvr_compat_do_box( $args, $text, true );
}
add_shortcode('box', 'wvr_compat_sc_box');
add_shortcode('wvrx_box', 'wvr_compat_sc_box');
?>
