<?php
/*
Plugin Name: Flash Gallery
Plugin URI: http://wordpress.org/extend/plugins/flash-gallery/
Description: use [flashgallery] to turn galleries into flash image walls.
Version: 1.4.1
Author: Ulf Benjaminsson
Author URI: http://www.ulfben.com
License: GPL

The FLA sources are available in the development version: http://wordpress.org/extend/plugins/flash-gallery/download/
Documentation: http://wordpress.org/extend/plugins/flash-gallery/faq/
*/
if(!defined('WP_CONTENT_URL')){
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
}
if(!defined('WP_CONTENT_DIR')){
	define('WP_CONTENT_DIR', ABSPATH.'wp-content');
}
if(!defined('WP_PLUGIN_URL')){
	define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
}
if(!defined('WP_PLUGIN_DIR')){
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
}
define('FG_DELIMITER', '%');
define('FG_URL', WP_PLUGIN_URL.'/flash-gallery/');
define('FG_SCRIPT_URL', FG_URL.'js/');
define('FG_SWF', 'zgallery_1.4.1.swf');

function fgr_shortcode($attr){	
	global $post, $ID, $wp_query;
	if(!in_the_loop()){return '';}	
	if(isset($wp_query->query_vars['noflash']) && intval($wp_query->query_vars['noflash']) == 1){	
		$enable = (!$hidetoggle) ? '<a class="fgr-toggle" href="'.get_permalink($ID).'" title="Click to enable the awesome Flash Gallery, with full screen viewing, slideshows and more." style="font-size:smaller;display:block;text-align:right;">[Enable Flash Gallery]</a>' : '';	
		return gallery_shortcode($attr).$enable;		
	}	
	if(isset($attr['orderby'])){
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if(!$attr['orderby']){
			unset($attr['orderby']);
		}
	}
	$pid = $post->ID;	
	if(!$pid && isset($_POST['submit']) && isset($_POST['previewID'])){	
		$pid = $_POST['previewID'];
	}	
	extract(shortcode_atts(array(
		'order' => 'ASC',
		'orderby' => 'menu_order', //menu_order ID
		'id' => $pid,
		'itemtag' => 'dl',
		'icontag' => 'dt',
		'link'	=> '',
		'captiontag' => 'dd',
		'columns' => 4,
		'hidetoggle' => false, //fgr
		'allowfullscreen' => true, //fgr
		'delay' => 3, //fgr
		'color' => '0xFF0099', //fgr
		'rows' => 3, //fgr
		'usescroll' => 'true', //fgr
		'showtitles' => 'false', //fgr
		'allowdownload' => 'true', //fgr
		'height' => '450px', //fgr
		'rowmajor' => 'false', //fgr
		'animate' => 'true', //fgr
		'cats' => '', //fgr - deprecated since 1.3. Use 'albums' instead.
		'albums' => the_title('','',false), //fgr
		'size' => 'thumbnail',
		'thumbsize' => get_option('thumbnail_size_w'), //fgr - deprecated. autodetected by gallery.
		'transparent' => false,
		'background' => FG_URL.'background.jpg', //fgr
		'logo' => '', //fgr
		'scaling' => 'fit', //fgr
		'exclude' => '',
		'numberposts' => -1
	), $attr));
	$id = intval($id);	
	$exclude = explode(',',$exclude);		
	$attachments = get_children(array(
		'post_parent' => $pid, 
		'post_status' => 'inherit', 
		'post_type' => 'attachment', 
		'post_mime_type' => 'image', 
		'order' => $order, 
		'orderby' => $orderby, 
		'post__not_in' => $exclude, 
		'exclude' => implode(',',$exclude), 
		'numberposts' => $numberposts
	));		
	if(empty($attachments)){
		return '';
	}
	if(is_feed()){
		$output = "\n";
		foreach($attachments as $aID => $attachment){
			$output .= wp_get_attachment_link($aID, $size, true)."\n";
		}
		return $output;
	} 		
	$count = -1; 
	$galleryc = 0;
	$basepath = get_option('siteurl');
	foreach($attachments as $aID => $attachment){
		$s = wp_get_attachment_url($aID);
		$basepath = dirname($s);
		break;
	}	
	$gallery_id;		
	$current_album_title;
	$current_album_count;
	$albums = (isset($albums) && !empty($albums)) ? $albums : $cats;
	$width = '100%';
	if(!$allowfullscreen || strtolower($allowfullscreen) == 'false'){$allowfullscreen = 'false';}else{$allowfullscreen = 'true';}	
	$fgr = 'FG_'.$pid; 		
	$categories = explode(FG_DELIMITER, trim($albums, FG_DELIMITER));		
	$albumcount = count($categories);
	$wmode = ($transparent) ? ',"wmode": "transparent"' : '';	
	if(!isset($content)){$content = '';}
	$flashgallery = '<span class="fgr_container" id="container_'.$fgr.'">
		<span id="'.$fgr.'" class="fgr"></span>
	</span>	
	<script type="text/javascript">
	'.$fgr.'_config = { 
		"thumbsize":"'.$thumbsize.'",
		"gallerycount":"'.$albumcount.'",
		"background":"'.$background.'",
		"logourl":"'.$logo.'",
		"scaling":"'.$scaling.'",
		"rowcount":"'.$rows.'",
		"animate":"'.$animate.'",
		"delay":"'.$delay.'",
		"rowmajor":"'.$rowmajor.'",
		"basepath":"'.$basepath.'",
		"showtitles":"'.$showtitles.'",
		"usescroll":"'.$usescroll.'",
		"color":"'.$color.'",
		"allowdownload":"'.$allowdownload.'",
		"allowfullscreen":"'.$allowfullscreen.'"
	};'."\n"; 
	FG_set_current_Id_Title_Count($galleryc, $categories, $gallery_id, $current_album_title, $current_album_count, $attachments);		
	$flashgallery .= $fgr.'_config["'.$gallery_id.'"] = "'. $current_album_title.'_'.$current_album_count .'";'."\n";		
	foreach($attachments as $aID => $attachment){		
		$url = str_replace($basepath, '', wp_get_attachment_url($aID)); //original size		
		$thumb = wp_get_attachment_image_src($aID, 'thumbnail');		
		$thumb = $thumb[0];
		$thumb = substr(strrchr($thumb, '/'), 1);		
		if(($count == $current_album_count) && $albumcount > 1){		
			$galleryc++;		
			FG_set_current_Id_Title_Count($galleryc, $categories, $gallery_id, $current_album_title, $current_album_count, $attachments);
			$flashgallery .= $fgr.'_config["'.$gallery_id.'"] = "'. $current_album_title.'_'.$current_album_count .'";'."\n";
			$count = 0;
		}else{
			$count++;		 
		}		
		$flashgallery .= $fgr.'_config["'.$galleryc.'_img'.$count.'"] = "'.$url.'?'.$thumb.'";'."\n";
		/*
		[post_content] => Description
		[post_title] => Filename
		[post_excerpt] => Caption */
		$info = ($attachment->post_content) ? $attachment->post_content : $attachment->post_excerpt;	
		if($info){
			$flashgallery .= $fgr.'_config["'.$galleryc.'_txt'.$count.'"] = "'.rawurlencode($info).'";'."\n";			
		}
	}	
$flashgallery .= '	
	addLoadEvent(function(){
		try{
			swfobject.embedSWF("'.FG_URL.FG_SWF.'", "'.$fgr.'", "'.$width.'", "'.$height.'", "10",
				"'.FG_SCRIPT_URL.'expressinstall.swf'.'",'.$fgr.'_config,
				{"allowFullScreen":"'.$allowfullscreen.'"'.$wmode.',"menu":"false","allowscriptacess":"always"}, 
				{"styleclass":"fgr"});
		}catch(e){};});	
	</script>';
	$flashgallery .= (!$hidetoggle) ? '<a class="fgr-toggle" href="'.get_permalink($id).'?noflash=1" title="Having troubles with flash? Disable the Flash Gallery to browse pictures flash free." style="font-size:smaller;display:block;text-align:right;">[Disable Flash Gallery]</a>' : '';	
	global $FG_add_script;
	$FG_add_script = true;	
	return $flashgallery;
}

function FG_set_current_Id_Title_Count($galleryc, $categories, &$gallery_id, &$current_album_title, &$current_album_count, &$attachments){	
	$gallery_id = 'gallery'.$galleryc;	
	$name_and_count = explode('_', $categories[$galleryc]);
	$current_album_title = ($name_and_count[0]) ? $name_and_count[0] : the_title('','',false);	
	$current_album_title = sanitize_title($current_album_title);
	trim($current_album_title, FG_DELIMITER);
	$current_album_count = (isset($name_and_count[1]) && is_numeric($name_and_count[1])) ? $name_and_count[1] : count($attachments);
}
function fgr_register_query_var($vars){
	$vars[] = 'noflash';
	return $vars;
}
function FG_js(){
	if(!is_admin()){
		wp_register_script('addonload', FG_SCRIPT_URL.'addOnLoad.js', array(), '1');
		//wp_enqueue_script('jquery', '', array(), false, true);							
		wp_enqueue_script('addonload');
	}	
}
function FG_maybe_do_scripts(){
	global $FG_add_script; 
	if (!$FG_add_script){ return; }		
	wp_enqueue_script('jquery', '', '', '', true ); //true == in footer. since wp 2.8
	wp_enqueue_script('swfobject', '', false, '2.2', true); 
	wp_enqueue_script('swfaddress_2.3', FG_SCRIPT_URL.'swfaddress.js', array('swfobject'), '2.3', true);	
	wp_print_scripts();
}
remove_shortcode('flashgallery');
add_shortcode('flashgallery', 'fgr_shortcode');	
add_filter('query_vars', 'fgr_register_query_var');
add_action('wp_print_scripts', 'FG_js');	
add_filter('wp_footer', 'FG_maybe_do_scripts');		
?>