<?php
/*
Plugin Name: Wordpress Gallery Plugin
Plugin URI: http://www.snilesh.com/?p=1242
Description: Wordpress plugin for adding a customizable gallery shortcode which you can use in posts and pages.
Version: 1.4
Author: Nilesh Shiragave
Tags: gallery,album,image gallery,wordpress gallery,gallery shortcode,wp gallery,image slideshow,image slider,image scroller,wordpress slideshow

Author URI: http://www.snilesh.com
*/

/*  Copyright 2011  snilesh.com  (email : snilesh.com@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Check Current Wordpress Version */
global $wp_version;	
$plugin_name="Wordpress Gallery Plugin";
$exit_msg=$plugin_name.' requires WordPress 3.0 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';

if (version_compare($wp_version,"3.0","<"))
{
	exit ($exit_msg);
}

/* LOAD PLUGIN LANGUAGE FILES */
load_plugin_textdomain('ns_wp_gallery_plugin',false,'wordpress-gallery-plugin/languages');

if (!defined('WP_CONTENT_URL')) {
	$content_url=content_url();
	define('WP_CONTENT_URL', $content_url);
}


define('NS_WPG_ADMIN_PATH',admin_url());
define('NS_WPG_PATH',WP_CONTENT_URL.'/plugins/wordpress-gallery-plugin/');
define('NS_WPG_FOLDER_PATH',dirname(__FILE__));


$ns_wpg_default_settings=array(
'effect'=>'random',
'animSpeed'=>'500',
'pauseTime'=>'3000',
'directionNav'=>'true',
'directionNavHide'=>'true',
'controlNav'=>'true',
'keyboardNav'=>'true',
'pauseOnHover'=>'true',
'border_color'=>'000000',
'border_size'=>'5px',
'width'=>'500',
'height'=>'300',
'lightbox'=>'true',
'thumb_border_color'=>'000000',
'thumb_border_color_mover'=>'999999',
'thumb_border_size'=>'5px',
'thumb_width'=>'125',
'thumb_height'=>'125',
'prow'=>'4',
'thumb_lightbox'=>'true'
);


/* Function to Call when Plugin get activated */
function ns_wpg_activate_function()
{
	global $ns_wpg_default_settings;
	$default_settings = get_option('ns_wpg_options');
	$default_settings= wp_parse_args($default_settings, $ns_wpg_default_settings);
	add_option('ns_wpg_options',$default_settings);
	
}

/* Function to Call when Plugin get deactivated */
function ns_wpg_deactivate_function()
{
	delete_option('ns_wpg_options');
}

register_activation_hook( __FILE__, 'ns_wpg_activate_function' );
register_deactivation_hook( __FILE__, 'ns_wpg_deactivate_function' );
$var=get_option('ns_wpg_options');


/* ADD Shortcode */
add_shortcode('wpg', 'ns_wpg_gallery_shortcode');

$ns_wpg_options=get_option('ns_wpg_options');

$wpg_id=0;

function ns_wpg_gallery_shortcode($atts)
{
	global $post;
	global $ns_wpg_options;
	global $wpg_id;
	
	$firstimg = TRUE; //to avoid displaying all images while they are being loaded 

	extract(shortcode_atts(array(
		'id' => $post->ID,
		'width' => $ns_wpg_options['width'],
		'height' => $ns_wpg_options['height'],
		'effect' => $ns_wpg_options['effect'],
		'title' => $ns_wpg_options['title'],
		'exclude' => FALSE,
		'border_color' => $ns_wpg_options['border_color'],
		'border_size' => $ns_wpg_options['border_size'],
		'link'=>'true',
		'align'=>'left',
		'slices' =>'15', // For slice animations
        'boxCols'=>'8', // For box animations
        'boxRows'=>'4', // For box animations
        'animSpeed'=>$ns_wpg_options['animSpeed'], // Slide transition speed
        'pauseTime'=>$ns_wpg_options['pauseTime'], // How long each slide will show
        'startSlide'=>'0', // Set starting Slide (0 index)
        'directionNav'=>$ns_wpg_options['directionNav'], // Next & Prev navigation
        'directionNavHide'=>$ns_wpg_options['directionNavHide'], // Only show on hover
        'controlNav'=>$ns_wpg_options['controlNav'], // 1,2,3... navigation
        'controlNavThumbs'=>'false', // Use thumbnails for Control Nav
        'controlNavThumbsFromRel'=>'false', // Use image rel for thumbs
        'keyboardNav'=>$ns_wpg_options['keyboardNav'], // Use left & right arrows
        'pauseOnHover'=>$ns_wpg_options['pauseOnHover'], // Stop animation while hovering
        'manualAdvance'=>'false', // Force manual transitions
        'captionOpacity'=>'0.8', // Universal caption opacity
        'prevText'=>'Prev', // Prev directionNav text
        'nextText'=>'Next', // Next directionNav text
        'navPosition'=>$ns_wpg_options['navPosition'] // Next directionNav text
	), $atts));
	$alignclass="";
	if($align=='left')
	{
		$alignclass=' wpgalignleft';
	}
	elseif($align=='right')
	{
		$alignclass=' wpgalignright';
	}
	elseif($align='center')
	{
		$alignclass=' wpgaligncenter';
	}
	else
	{
		$alignclass=' wpgaligncenter';
	}
	$slider = "<div id='wpgslider{$post->ID}_{$wpg_id}' class='wpgslider-container".$alignclass."'>";
	$slider .="<div id='wpgslider{$post->ID}_{$wpg_id}_slider' class='nivoSlider'>";
	$args = array(
		'post_type' => 'attachment',
		'post_parent' => $id,
		'numberposts' => -1,
		'exclude' => $exclude,
		'orderby' => 'menu_order', 'order' => 'ASC'
		); 
	$images = get_posts($args);
	if($images){
		foreach ( $images as $image ) {		
			$caption = $image->post_excerpt;
	
			$description = $image->post_content;
			//if($description == '') $description = $image->$post_title;
	
			$image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);
			$img = wp_get_attachment_image_src($image->ID, $size);

			$website_url =get_bloginfo('url');
			$image_array= explode($website_url, $img[0]);
			$image_path=NS_WPG_PATH. "timthumb.php?src={$image_array[1]}&a=t&w=" . $width . "&h=" . $height . "&q=100";
			
			
			if($link == 'true') $slider .= "<a href='{$img[0]}' title='$image->post_title' class='wpg_slide_image' >";
			$slider .= "<img src='{$image_path}' alt='{$image->post_title}'   />";
			if($link == 'true')  $slider .= "</a>";
			//if($title == 'true') $slider .= "<span><h4>{$image->post_title}</h4></span>";
			
		}
		$slider .= "</div></div>";
		$slider.='<script type="text/javascript">';
		$slider .=" var jqu = jQuery.noConflict(); ";
		$slider .="	jqu(document).ready(function() { ";
		
		$slider.='jqu("#wpgslider'.$post->ID.'_'.$wpg_id.'_slider").nivoSlider({ ';
	    $slider.="\n effect:'".$effect."' \n, ";// Specify sets like: 'fold,fade,sliceDown'
        $slider.="slices:".$slices.", \n";// For slice animations
        $slider.="boxCols: ".$boxCols.", \n";// For box animations
        $slider.="boxRows: ".$boxRows.", \n";// For box animations
        $slider.="animSpeed:".$animSpeed.", \n"; // Slide transition speed
        $slider.="pauseTime:".$pauseTime.", \n"; // How long each slide will show
        $slider.="startSlide:".$startSlide.", \n"; // Set starting Slide (0 index)
        $slider.="directionNav:".$directionNav.", \n"; // Next & Prev navigation
        $slider.="directionNavHide:".$directionNavHide.", \n"; // Only show on hover
        $slider.="controlNav:".$controlNav.", \n";// 1,2,3... navigation
        $slider.="controlNavThumbs:".$controlNavThumbs.", \n";// Use thumbnails for Control Nav
        $slider.="keyboardNav:".$keyboardNav.", \n"; // Use left & right arrows
        $slider.="pauseOnHover:".$pauseOnHover.", \n"; // Stop animation while hovering
        $slider.="manualAdvance:".$manualAdvance.", \n"; // Force manual transitions
        $slider.="captionOpacity:".$captionOpacity.", \n";// Universal caption opacity
        $slider.="prevText: '".$prevText."', \n"; // Prev directionNav text
        $slider.="nextText: '".$nextText."' \n";// Next directionNav text
		$slider.="}); \n";
		// CHECK LIGHT BOX STATUS
		$slider.= 'jqu("#wpgslider'.$post->ID.'_'.$wpg_id.' a.wpg_slide_image").colorbox({transition:"fade"});';

		$slider.='});</script>';

		$slider.='<style type="text/css">';
		$slider.='#wpgslider'.$post->ID.'_'.$wpg_id.'_slider { ';
		$slider.='width:'.$width.'px; height:'.$height.'px; border:'.$border_size.' solid #'.$border_color.'; ';
		$slider .='}'; //overflow:hidden;
		if($navPosition=='topleft')
		{
		$slider .='#wpgslider'.$post->ID.'_'.$wpg_id.'_slider .nivo-controlNav { ';
		$slider .='left:0;top:0; margin-top:0px; margin-botom:0px;';
		$slider .='}';
		}
		elseif($navPosition=='topright')
		{
		$slider .='#wpgslider'.$post->ID.'_'.$wpg_id.'_slider .nivo-controlNav { ';
		$slider .='right:0;top:0; margin-top:0px; margin-botom:0px;';
		$slider .='}';
		}
		elseif($navPosition=='bottomleft')
		{
		$slider .='#wpgslider'.$post->ID.'_'.$wpg_id.'_slider .nivo-controlNav { ';
		$slider .='left:0;bottom:0; margin-top:0px; margin-botom:0px;';
		$slider .='}';
		}
		elseif($navPosition=='bottomright')
		{
		$slider .='#wpgslider'.$post->ID.'_'.$wpg_id.'_slider .nivo-controlNav { ';
		$slider .='right:0;bottom:0; margin-top:0px; margin-botom:0px;';
		$slider .='}';
		}
		elseif($navPosition=='topcenter')
		{
		$slider .='#wpgslider'.$post->ID.'_'.$wpg_id.'_slider .nivo-controlNav { ';
		$slider .='left:0;top:0; margin-top:0px; margin-botom:0px;';
		$slider .='}';
		}
		elseif($navPosition=='bottomcenter')
		{
		$slider .='#wpgslider'.$post->ID.'_'.$wpg_id.'_slider .nivo-controlNav { ';
		$slider .='left:0;top:0; margin-top:0px; margin-botom:0px;';
		$slider .='}';
		}
		else
		{
		}



		
		$slider.='</style>';
		$wpg_id=$wpg_id+1;
		return stripslashes($slider);
	}
	else
	{
		return "<p><strong>No Images in Gallery. <small>Upload Images First</small></strong></p>";
	}
}

function ns_wpg_print_scripts() {
	global $WPSocialSettings;
    wp_enqueue_script ('jquery');
	if(!is_admin())
	{
	wp_enqueue_script('nivoslider',NS_WPG_PATH.'js/jquery.nivo.slider.pack.js',array('jquery'));
	wp_enqueue_script('colorbox',NS_WPG_PATH.'lightbox/colorbox/jquery.colorbox-min.js',array('jquery'));
	}
	else
	{
	wp_enqueue_script('colorpicker1',NS_WPG_PATH.'js/colorpicker.js',array('jquery'));
	wp_enqueue_script('wpg_admin_scripts',NS_WPG_PATH.'js/admin_js.js',array('jquery'));
	}

}
add_action('wp_print_scripts', 'ns_wpg_print_scripts');
add_action('wp_print_styles', 'ns_wpg_print_styles');
function ns_wpg_print_styles()
{
	if(!is_admin())
	{
	wp_enqueue_style( 'wpgallery', NS_WPG_PATH.'css/wp_gallery.css');
	wp_enqueue_style( 'nivoslider', NS_WPG_PATH.'css/nivo-slider.css');
	wp_enqueue_style( 'colorbox', NS_WPG_PATH.'lightbox/colorbox/colorbox.css');
	}
	else
	{
		wp_enqueue_style( 'colorpicker', NS_WPG_PATH.'css/colorpicker.css');	
	}

}

/* Administrator menus */
/* Add Administrator Menus */
function ns_wpg_admin_menu()
{
	$level = 'level_7';
	add_menu_page('WP Gallery', 'WP Gallery', $level, __FILE__, 'ns_wpg_options_page',NS_WPG_PATH.'images/icon.png');
	add_submenu_page(__FILE__, 'Help &amp; Support', 'Help &amp; Support', $level, 'ns_wpg_support_page','ns_wpg_support_page');
}
add_action('admin_menu','ns_wpg_admin_menu');	


function ns_wpg_options_page()
{
include_once dirname(__FILE__).'/includes/options.php';
}

function ns_wpg_support_page()
{
include_once dirname(__FILE__).'/includes/help_support.php';
}


/* ALL SHORTCODES STARTS HERE */
add_shortcode('wpg_thumb', 'ns_wpg_thumbnails_shortcode');
function ns_wpg_thumbnails_shortcode($atts)
{
	global $post;
	global $ns_wpg_options;
	global $wpg_id;
	
	extract(shortcode_atts(array(
		'id' => $post->ID,
		'width'=>$ns_wpg_options['thumb_width'],
		'height'=>$ns_wpg_options['thumb_height'],
		'border_color' => $ns_wpg_options['thumb_border_color'],
		'border_mover' => $ns_wpg_options['thumb_border_color_mover'],
		'border_size' => $ns_wpg_options['thumb_border_size'],
		'link'=>'true',
		'lightbox'=>$ns_wpg_options['thumb_lightbox'],
		'exclude'=>false,
		'perrow'=>$ns_wpg_options['prow']
	), $atts));
	$slider = "<div id='wpg_thumb_gallery{$post->ID}_{$wpg_id}' class='wpg-thumb-container'>";	
	$args = array(
		'post_type' => 'attachment',
		'post_parent' => $id,
		'numberposts' => -1,
		'exclude' => $exclude,
		'orderby' => 'menu_order', 'order' => 'ASC'
		); 
	$images = get_posts($args);
	$count=0;
	if($images){
		foreach ( $images as $image ) {		
			$count++;
			$caption = $image->post_excerpt;	
			$description = $image->post_content;
			//if($description == '') $description = $image->$post_title;
	
			$image_alt = get_post_meta($image->ID,'_wp_attachment_image_alt', true);
			if($count==$perrow)
			{
				$class=" class='last_thumb' ";
			}
			else
			{
				$class='';
			}
			$plugin_url=content_url().'/plugins/wordpress-gallery-plugin';
			
			$plugin_folder=NS_WPG_PATH.'timthumb.php';
			$img = wp_get_attachment_image_src($image->ID, $size);			
			$website_url=get_bloginfo('url');
			
			$image_array= explode($website_url, $img[0]);
			
			if($link == 'true') $slider .= "<a href='{$img[0]}' rel='wpg_thumb_gallery{$post->ID}_{$wpg_id}_rel' title='$image->post_title'>";
			$slider .= "<img src='".$plugin_folder."?src=".$image_array[1]."&a=t&h=".$height."&w=".$width."&zc=1' alt='{$image->post_title}' ".$class." />";
			if($link == 'true')  $slider .= "</a>";				
			if($count==$perrow)
			{
				$slider.='<div class="clear"></div>';
				$count=0;
			}
	}
	}
	$slider .='</div>';
	$style = "<style type='text/css'>";
		$style .= "#content img{max-width: none;}";
		$style .= "#wpg_thumb_gallery{$post->ID}_{$wpg_id} img {width: {$width}px; height: {$height}px; border: {$border_size} solid #{$border_color}; overflow:hidden; float:left; margin:0px 15px 15px 0px;} ";
		$style .= "#wpg_thumb_gallery{$post->ID}_{$wpg_id} img:hover {border-color: #{$border_mover};} ";
		$style .= "#wpg_thumb_gallery{$post->ID}_{$wpg_id} img.last_thumb {margin-right:0px;} ";

	$style .= "</style>";
	$script = "<script type='text/javascript'>";
	$script .= "jQuery(document).ready(";
	$script .= "	function() {";
		$script .= "	jQuery('#wpg_thumb_gallery{$post->ID}_{$wpg_id} a').colorbox({";
		$script.="transition:'elastic', width:'90%', height:'90%'";
		$script .= "		});";
	$script .= "});";
	$script .= "</script>";
	
	$slider .= $style;
	$slider .= $script;
	$slider .='';

	return $slider;
	wp_reset_query();
}

/* LOAD COMMON FUNCTIONS WHICH ARE USED IN ALL MY PLUGINS */
include_once dirname(__FILE__).'/includes/common_functions.php';
//echo $plugin_folder=content_url().'/plugins/wordpress-gallery-plugin';

if(!get_option('ns_wpg_options')) {
  add_action('admin_notices', 'wpst_plugin_options_presents', 12);
}
function wpst_plugin_options_presents() {
	$wp_gallery=admin_url().'admin.php?page=wordpress-gallery-plugin/wp-gallery-plugin.php';    
       echo '
          <div id="update_wpst" style="border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;background:#feb1b1;border:1px solid #fe9090;color:#820101;font-size:10px;font-weight:bold;height:auto;margin:35px 15px 0 0;overflow:hidden;padding:4px 10px 6px;">
            <div style="margin:2px 10px 0 0;float:left;line-height:18px;padding-left:22px;">';
	   echo _e('NOTICE: Wordpress Gallery Plugin Options Not Present... Please visit the Wp Gallery Options Page and save your settings', 'ns_wp_gallery_plugin');
	   echo _e(' <a href="'.$wp_gallery.'" title="Wordpress Gallery Plugin">WP Gallery Options</a>');
	   echo '</div></div>';     
}
?>