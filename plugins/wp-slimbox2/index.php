<?php
/*
Plugin Name: WP-Slimbox2
Plugin URI: http://transientmonkey.com/wp-slimbox2
Description: A Wordpress implementation of the Slimbox2 javascript, utilizing jQuery, originally written by Christophe Beyls. Requires WP 2.8+
Author: Greg Yingling (malcalevak)
Version: 1.1.3.1
Author URI: http://transientmonkey.com/

Copyright 2013 Transient Monkey

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

load_plugin_textdomain ('wp-slimbox2', WP_PLUGIN_DIR.'/wp-slimbox2/languages', '/wp-slimbox2/languages');

add_action('wp_print_scripts', 'wp_slimbox_scripts');
add_action('wp_print_styles', 'wp_slimbox_styles');

function wp_slimbox_initialize() {
	$options = array(
		'autoload'   => 'on',
		'overlayOpacity'   => '0.8',
		'overlayColor' => '#000000',
		'overlayFadeDuration'   => '400',
		'resizeDuration' => '400',
		'resizeEasing'   => 'swing',
		'initialWidth' => '250',
		'initialHeight'   => '250',
		'imageFadeDuration' => '400',
		'captionAnimationDuration'   => '400',
		'caption' => array('a-title','img-alt','img-title','href'),
		'url' => 'on',
		'selector' => 'div.entry-content, div.gallery, div.entry, div.post, div#page, body',
		'counterText' => __('Image {x} of {y}', 'wp-slimbox2'),
		'closeKeys'   => __('27,88,67', 'wp-slimbox2'),
		'previousKeys' => __('37,80', 'wp-slimbox2'),
		'nextKeys'   =>  __('39,78', 'wp-slimbox2')
	);
	add_option('wp_slimbox',$options);
	return $options;
}

function wp_slimbox_styles() {
	wp_register_style('slimbox2', WP_PLUGIN_URL.'/wp-slimbox2/css/slimbox2.css','','1.1','screen');
	wp_enqueue_style('slimbox2');
	if(__('LTR', 'wp-slimbox2')=='RTL') {
		wp_register_style('slimbox2-RTL', WP_PLUGIN_URL.'/wp-slimbox2/css/slimbox2-rtl.css','','1.0','screen');
		wp_enqueue_style('slimbox2-RTL');
	}
	wp_register_script('slimbox2', WP_PLUGIN_URL.'/wp-slimbox2/javascript/slimbox2.js',array('jquery'), '2.04');
	wp_register_script('slimbox2_autoload', WP_PLUGIN_URL.'/wp-slimbox2/javascript/slimbox2_autoload.js',array('slimbox2'),'1.0.4b');
	wp_register_script('jquery_easing', WP_PLUGIN_URL.'/wp-slimbox2/javascript/jquery.easing.1.3.js',array('jquery'), '1.3');
}

function wp_slimbox_scripts() {
	if (!is_admin())
	{
		$options = wp_slimbox_validate(get_option('wp_slimbox',wp_slimbox_initialize()));
		if(isset($options['maintenance'])) {
			if (isset($_REQUEST['slimbox'])) $_SESSION['slimboxC']=$_REQUEST['slimbox'];
			else if(!isset($_SESSION['slimboxC'])) $_SESSION['slimboxC']='off';
			if ($_SESSION['slimboxC'] != 'on') return;
		}
		if($options['resizeEasing'] != 'swing') wp_enqueue_script('jquery_easing');
		wp_enqueue_script('slimbox2_autoload');
		$captions = $options['caption'];
		$caption = '';
		for ($i = 0; $i<4; $i++) {
			switch ($captions[$i]) {
				case 'a-title':
					$caption .= 'el.title';
					break;
				case 'img-alt':
					$caption .= 'el.firstChild.alt';
					break;
				case 'img-title':
					$caption .= 'el.firstChild.title';
					break;
				case 'href':
					$caption .= 'el.href';
					break;
				default:
					$caption .= "' '";
			}
			$caption .= ' || ';
		}
		$caption .= 'el.href';
		wp_localize_script( 'slimbox2_autoload', 'slimbox2_options', array(
			'autoload' => (isset($options['autoload'])?true:false),
			'overlayColor' => $options['overlayColor'],
			'loop' => (isset($options['loop'])?true:false),
			'overlayOpacity' => $options['overlayOpacity'],
			'overlayFadeDuration' => $options['overlayFadeDuration'],
			'resizeDuration' => $options['resizeDuration'],
			'resizeEasing' => $options['resizeEasing'],
			'initialWidth' => $options['initialWidth'],
			'initialHeight' => $options['initialHeight'],
			'imageFadeDuration' => $options['imageFadeDuration'],
			'captionAnimationDuration' => $options['captionAnimationDuration'],
			'caption' => $caption,
			'url' => (isset($options['url'])?true:false),
			'selector' => $options['selector'],
			'counterText' => $options['counterText'],
			'closeKeys' => $options['closeKeys'],
			'previousKeys' => $options['previousKeys'],
			'nextKeys' => $options['nextKeys'],
			'prev' => WP_PLUGIN_URL.'/wp-slimbox2/images/'.__('default/prevlabel.gif', 'wp-slimbox2'),
			'next' => WP_PLUGIN_URL.'/wp-slimbox2/images/'.__('default/nextlabel.gif', 'wp-slimbox2'),
			'close' => WP_PLUGIN_URL.'/wp-slimbox2/images/'.__('default/closelabel.gif', 'wp-slimbox2'),
			'picasaweb' => (isset($options['picasaweb'])?true:false),
			'flickr' => (isset($options['flickr'])?true:false),
			'mobile' => (isset($options['mobile'])?true:false)
		));
	}
}

add_action('admin_menu', 'show_slimbox_options');
add_action('admin_init', 'slimbox_admin_init');

function show_slimbox_options() {
	$page = add_options_page('WP-Slimbox2 Options', 'WP-Slimbox2', 'edit_pages', 'slimbox2options', 'slimbox_options');
	add_action( "admin_print_scripts-$page", 'slimbox_adminhead' );
	add_action( "admin_print_styles-$page", 'slimbox_admin_styles' );
}

function slimbox_options() {
	$options = wp_slimbox_validate(get_option('wp_slimbox',wp_slimbox_initialize()));
	require('adminpage.php');
}

function slimbox_admin_init() {
	wp_register_script('load_admin', WP_PLUGIN_URL.'/wp-slimbox2/javascript/admin.js',array('farbtastic'), '1.0');
	register_setting( 'wp_slimbox_options', 'wp_slimbox', 'wp_slimbox_validate');
}

function slimbox_admin_styles() {
	wp_enqueue_style('farbtastic');
}

function slimbox_adminhead() {
	wp_enqueue_script('load_admin');
}

function wp_slimbox_validate($options) {
	$easingArray = array('swing','easeInQuad','easeOutQuad','easeInOutQuad','easeInCubic','easeOutCubic','easeInOutCubic','easeInQuart','easeOutQuart','easeInOutQuart','easeInQuint','easeOutQuint','easeInOutQuint','easeInSine','easeOutSine','easeInOutSine','easeInExpo','easeOutExpo','easeInOutExpo','easeInCirc','easeOutCirc','easeInOutCirc','easeInElastic','easeOutElastic','easeInOutElastic','easeInBack','easeOutBack','easeInOutBack','easeInBounce','easeOutBounce','easeInOutBounce');
	$overlayOpacity = array(0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1);
	$msArray = array(1,100,200,300,400,500,600,700,800,900,1000);
	$captions = array('a-title','img-alt','img-title','href','None');
	if(isset($options['autoload'])) $options['autoload'] = 'on';
	if(isset($options['loop'])) $options['loop'] = 'on';
	if(isset($options['url'])) $options['url'] = 'on';
	if(isset($options['picasaweb'])) $options['picasaweb'] = 'on';
	if(isset($options['flickr'])) $options['flickr'] = 'on';
	if(isset($options['mobile'])) $options['mobile'] = 'on';
	$options['overlayOpacity'] = (isset($options['overlayOpacity']) && in_array($options['overlayOpacity'],$overlayOpacity))? $options['overlayOpacity']:'0.8';
	$options['overlayFadeDuration'] = (isset($options['overlayFadeDuration']) && in_array($options['overlayFadeDuration'],$msArray))? $options['overlayFadeDuration']:'400';
	$options['resizeDuration'] = (isset($options['resizeDuration']) && in_array($options['resizeDuration'],$msArray))? $options['resizeDuration']:'400';
	$options['resizeEasing'] = (isset($options['resizeEasing']) && in_array($options['resizeEasing'],$easingArray))? $options['resizeEasing']:'swing';
	$options['imageFadeDuration'] = (isset($options['imageFadeDuration']) && in_array($options['imageFadeDuration'],$msArray))? $options['imageFadeDuration']:'400';
	$options['captionAnimationDuration'] = (isset($options['captionAnimationDuration']) && in_array($options['captionAnimationDuration'],$msArray))? $options['captionAnimationDuration']:'400';
	$options['initialWidth'] = (isset($options['initialWidth']) && is_int($options['initialWidth']))? $options['initialWidth']:'250';
	$options['initialHeight'] = (isset($options['initialHeight']) && is_int($options['initialHeight']))? $options['initialHeight']:'250';
	if(isset($options['caption'])) foreach ($options['caption'] as $key => $caption) $caption=(in_array($caption,$captions,true)?$caption:$captions[$key]);
	else $options['caption'] = $captions;
	$options['counterText'] = wp_filter_nohtml_kses( $options['counterText'] );
	$closekeys = explode(',',$options['closeKeys']);
	foreach($closekeys as $closeKey) $closeKey = is_int($closeKey)?$closeKey:'';
	$options['closeKeys'] = implode(',',$closekeys);
	$previousKeys = explode(',',$options['previousKeys']);
	foreach($previousKeys as $previousKey) $previousKey = is_int($previousKey)?$previousKey:'';
	$options['previousKeys'] = implode(',',$previousKeys);
	$nextKeys = explode(',',$options['nextKeys']);
	foreach($nextKeys as $nextKey) $nextKey = is_int($nextKey)?$nextKey:'';
	$options['nextKeys'] = implode(',',$nextKeys);
	$options['selector'] = isset($options['selector'])?wp_filter_nohtml_kses( $options['selector'] ):'div.entry-content, div.gallery, div.entry, div.post, div#page, body';
//finish setting defaults, check color
	return $options;
}

?>