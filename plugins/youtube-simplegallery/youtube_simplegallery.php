<?php
/*
Plugin Name: YouTube SimpleGallery
Plugin URI: http://wpwizard.net/plugins/youtube-simplegallery/
Description: A YouTube Gallery Plugin, that lets you add a gallery of videos to any Page or Post. <a href="options-general.php?page=youtube-gallery-options&tab=usage">How to use</a>.
Author: Stian Andreassen
Author URI: http://www.wpwizard.net
Version: 2.0.6
*/

global $youtube_gallery_verson, $youtube_gallery_count, $youtube_gallery_ID, $youtube_gallery_url;
$youtube_gallery_version = '2.0.6';
$youtube_gallery_count = 0;
$youtube_gallery_ID = 0;
$youtube_gallery_url = plugins_url('/', __FILE__);

// ADD SETTINGS LINK ON PLUGIN PAGE
function youtubegallery_settings_link($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	 
	if ($file == $this_plugin){
		$settings_link = '<a href="options-general.php?page=youtube-gallery-options">'.__('Settings').'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'youtubegallery_settings_link', 10, 2 );


// SHOW OPTIONS
include('inc/options.php');

// ADMIN NOTICES
include('inc/notices.php');


// GET ONLY URL IF AUTO EMBED IS ON
function yotube_gallery_getAttribute($attrib, $tag){
  //get attribute from html tag
  $re = '/'.$attrib.'=["\']?([^"\' ]*)["\' ]/is';
  preg_match($re, $tag, $match);
  if($match){
    return urldecode($match[1]);
  }else {
    return false;
  }
}

// GET YOUTUBE VIDEO ID
function yotube_gallery_getYouTubeIdFromURL($url) {
	$pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
	preg_match($pattern, $url, $matches);
	return isset($matches[1]) ? $matches[1] : false;
}

// GET YOUTUBE VIDEO DATA 
function yotube_gallery_getYouTubeDataFromID($vID) {
	$videodata = wp_remote_fopen('http://gdata.youtube.com/feeds/api/videos/'.trim($vID));
	if($videodata=='Video not found') {
		return 'error';
	}
	else {
		$videodata = new SimpleXMLElement($videodata);
		return $videodata;
	}
}

// GET VIMEO VIDEO DATA 
function yotube_gallery_getVimeoDataFromID($vID) {
	$videodata = wp_remote_fopen('http://vimeo.com/api/v2/video/'.trim($vID).'.json');
	if($videodata==$vID.' not found.') {
		return 'error';
	}
	else {
		$videodata = reset(json_decode($videodata, true));
		return $videodata;
	}
}

// SHORTCODE FOR GALLERY
include('inc/shortcode.php');

// MULTI WIDGETS
include('inc/admin-widgets.php');

// ON INSTALL PLUGIN
function youtubegallery_install(){
	add_option('youtube_gallery_option', 
		array(
			'thumbwidth' => '135',
			'cols' => '4',
			'autotitles' => 'fetch',
			'title' => 'below',
			'pb' => 'usepb',
			'jq' => 'usejq',
			'css' => 'usecss',
			'titlecss' => "text-align: center;\nfont-size: 1em;\nfont-style: italic;",

			'width' => '640',
			'height' => '370',
			'start' => 'autoplay',
			'hd' => 'usehd',
			'related' => 'dontshow',
			'thickbox' => 'thickbox',
		)
	);
}

// ON UNINSTALL PLUGIN
function youtubegallery_uninstall(){
	delete_option('youtube_gallery_option');
}

// ADD SCRIPTS TO ADMIN
function yotube_gallery_load_custom_wp_admin() {
global $youtube_gallery_url;
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('youtube_simplegallery', $youtube_gallery_url.'youtube_simplegallery.js', null, null, true);
	wp_enqueue_style('youtube_simplegallery_admin', $youtube_gallery_url.'admin.css');
}
add_action( 'admin_enqueue_scripts', 'yotube_gallery_load_custom_wp_admin' );

// LOAD CSS IN FRONTEND
function youtube_gallery_load_css() {
	global $youtube_gallery_url;
	$youtubeoptions = get_option('youtube_gallery_option');
	// USE CSS?
	if($youtubeoptions['css'] == 'usecss')
		wp_enqueue_style('youtube-simplegallery', $youtube_gallery_url.'youtube_simplegallery.css');
}
add_action( 'wp_enqueue_scripts', 'youtube_gallery_load_css' );

// INIT
function yotube_gallery_init() {
	session_start();
}
add_action('init', 'yotube_gallery_init');

// SESSION FOR DYNAMIC STYLES, OUTPUT IN FOOTER
function youtube_gallery_shortcode_styles() {
	echo '<style type="text/css">'."\r\n";
	foreach($_SESSION as $key => $val):
		if(strstr($key, 'youtube_gallery_'))
			echo $val."\r\n";
	endforeach;
	echo '</style>'."\r\n";
	session_destroy();
}
add_action('wp_footer', 'youtube_gallery_shortcode_styles');

// HOOK IT UP TO WORDPRESS
register_activation_hook(__FILE__,'youtubegallery_install');	
register_deactivation_hook(__FILE__,'youtubegallery_uninstall');
add_filter("plugin_action_links_$plugin", 'youtubegallery_settings_link' ); 

?>
