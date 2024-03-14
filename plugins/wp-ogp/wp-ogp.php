<?php
/*
Plugin Name: WP-OGP
Plugin URI: http://www.millerswebsite.co.uk/2011/01/23/wordpress-plugin-wp-ogp/
Description: This is a plugin to add Open Graph Protocol Data to the metadata of your WordPress blog.
Version: 1.0.5
Author: David Miller
Contributor: Joe Crawford
Author URI: http://www.millerswebsite.co.uk
License: GPL2
*/
// http://opengraphprotocol.org/

/*  This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * These are initially blank, to have a like button, at least one of these must be set
 */
 
$ogpt_settings = array(
	'fb:admins' => '',
	'fb:appid' => '',
);

define('OGPT_DEFAULT_TYPE', 'website');
define('OGPT_SETTINGS_KEY_FB_APPID', 'wpogp-fb:appid');
define('OGPT_SETTINGS_KEY_FB_ADMINS', 'wpogp-fb:admins');

$wpogp_keys = array(
	OGPT_SETTINGS_KEY_FB_APPID => 'A Facebook Platform application ID that administers this site.',
	OGPT_SETTINGS_KEY_FB_ADMINS => 'A comma-separated list of Facebook user IDs that administers this site. You can find your user id by visiting <a href="http://apps.facebook.com/what-is-my-user-id/" target="_blank">http://apps.facebook.com/what-is-my-user-id/</a>',
);

add_theme_support( 'post-thumbnails' );

function get_the_post_thumbnail_src($img)
{
  return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';
}

function wpogp_plugin_menu() {
	add_submenu_page('options-general.php', 'WP-OGP', 'WP-OGP', 'manage_options', 'WP-OGP', 'wpogp_plugin_options');

}

function wpogp_plugin_options() {

	global $wpogp_keys;
	echo '<div class="wrap">';
	echo '<form method="post" action="options.php">';
	echo '<input type="hidden" name="action" value="update" />';
	echo '<input type="hidden" name="page_options" value="'.implode(',',array_keys($wpogp_keys)).'" />';
	echo wp_nonce_field('update-options');
	echo '<p>To include the Facebook "like" code on your page, you must first include values for the below items. Your Facebook User ID is a number. You may specify multiple user IDs if you like.</p>';
	echo '<table class="form-table">';
	foreach ($wpogp_keys as $key => $desc) {
		echo '<tr valign="top">';
		echo '<th scope="row">';
		echo array_pop(explode('-',$key));
		echo '</th>';
		echo '<td><input type="text" name="';
		echo $key;
		echo '" value="';
		echo get_option($key);
		echo '" size="30"/><br />';
		echo $desc; 
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
	echo '<p class="submit">';
	echo '<input type="submit" class="button-primary" value="Save Changes" />';
	echo '</p>';
	echo '</form>';
	echo '</div>';
}


function load_wpogp_settings() {
	global $ogpt_settings;
	$ogpt_settings['fb:appid']  = get_option(OGPT_SETTINGS_KEY_FB_APPID);
	$ogpt_settings['fb:admins'] = get_option(OGPT_SETTINGS_KEY_FB_ADMINS);
}

function wpogp_plugin_path() {
	return get_option('siteurl') .'/wp-content/plugins/' . basename(dirname(__FILE__));
}

function wpogp_image_url_default() {
	// default image associated is in the plugin directory named "default.png"
	return wpogp_plugin_path() . '/default.jpg';
}

function wpogp_image_url() {
	global $post;

	$image = get_the_post_thumbnail_src(get_the_post_thumbnail($post->ID));

	if ( empty($image) )
		{ return wpogp_image_url_default();}
	else
		{ return $image; }

}

function wpogp_set_data() {
	global $wp_query;
	load_wpogp_settings();
	$data = array();
	if (is_home()) :
		$data['og:title'] = get_bloginfo('name');
		$data['og:type'] = OGPT_DEFAULT_TYPE;
		$data['og:image'] = wpogp_image_url_default(); 
		$data['image_src'] = wpogp_image_url_default();  
		$data['og:url'] = get_bloginfo('url');
		$data['og:site_name'] = get_bloginfo('name');
	elseif (is_single() || is_page()):
		$data['og:title'] = get_the_title();
		$data['og:type'] = 'article';
		$data['og:image'] = wpogp_image_url(); 
		$data['image_src'] = wpogp_image_url(); 
		$data['og:url'] = get_permalink();
		$data['og:site_name'] = get_bloginfo('name');
	else:
		$data['og:title'] = get_bloginfo('name');
		$data['og:type'] = 'article';
		$data['og:image'] = wpogp_image_url();  
		$data['image_src'] = wpogp_image_url();  
		$data['og:url'] = get_bloginfo('url');
		$data['og:site_name'] = get_bloginfo('name');
	endif;
	
	global $ogpt_settings;
	
	foreach($ogpt_settings as $key => $value) {
		if ($value!='') {
			$data[$key] = $value;
		}
	}
	return $data;
}

function wpogp_add_head() {
	$data = wpogp_set_data();
	echo get_wpogp_headers($data);
}

function get_wpogp_headers($data) {
	if (!count($data)) {
		return;
	}
	$out = array();
	$out[] = "\n<!-- BEGIN: WP-OGP by http://www.millerswebsite.co.uk Version: 1.0.5  -->";
	foreach ($data as $property => $content) {
		if ($content != '') {
			$out[] = get_wpogp_tag($property, $content);
		} else {
			$out[] = "<!--{$property} value was blank-->";
		}
	}
	return implode("\n", $out);
}

function get_wpogp_tag($property, $content) {
	return "<meta property=\"{$property}\" content=\"".htmlentities($content, ENT_NOQUOTES, 'UTF-8')."\" />";
}
function wpogp_add_head_desc() {
	$default_blog_desc = ''; // default description (setting overrides blog tagline)
	$post_desc_length  = 75; // description length in # words for post/Page
	$post_use_excerpt  = 1; // 0 (zero) to force content as description for post/Page
	$custom_desc_key   = 'description'; // custom field key; if used, overrides excerpt/content

	global $cat, $cache_categories, $wp_query, $wp_version;
	if(is_single() || is_page()) {
		$post = $wp_query->post;
		$post_custom = get_post_custom($post->ID);
		$custom_desc_value = $post_custom["$custom_desc_key"][0];

		if($custom_desc_value) {
			$text = $custom_desc_value;
		} elseif($post_use_excerpt && !empty($post->post_excerpt)) {
			$text = $post->post_excerpt;
		} else {
			$text = $post->post_content;
		}
		$text = str_replace(array("\r\n", "\r", "\n", "  "), " ", $text);
		$text = str_replace(array("\""), "", $text);
		$text = trim(strip_tags($text));
		$text = explode(' ', $text);
		if(count($text) > $post_desc_length) {
			$l = $post_desc_length;
			$ellipsis = '...';
		} else {
			$l = count($text);
			$ellipsis = '';
		}
		$description = '';
		for ($i=0; $i<$l; $i++)
			$description .= $text[$i] . ' ';

		$description .= $ellipsis;
	} elseif(is_category()) {
		$category = $wp_query->get_queried_object();
		$description = trim(strip_tags($category->category_description));
	} else {
		$description = (empty($default_blog_desc)) ? trim(strip_tags(get_bloginfo('description'))) : $default_blog_desc;
	}

	if($description) {
		echo "\n<meta property=\"og:description\" content=\"".htmlentities($description, ENT_NOQUOTES, 'UTF-8')."\" />\n";
		echo "<!-- END: WP-OGP by http://www.millerswebsite.co.uk Version: 1.0.5 -->\n";
	}
}


add_action('wp_head', 'wpogp_add_head');
add_action('wp_head', 'wpogp_add_head_desc');
add_action('admin_menu', 'wpogp_plugin_menu');


?>