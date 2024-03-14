<?php 
/*
	Plugin Name: Head Meta Data
	Plugin URI: https://perishablepress.com/head-metadata-plus/
	Description: Adds a custom set of &lt;meta&gt; tags to the &lt;head&gt; section of all posts &amp; pages.
	Tags: meta, metadata, header, author, publisher
	Author: Jeff Starr
	Author URI: https://plugin-planet.com/
	Donate link: https://monzillamedia.com/donate.html
	Contributors: specialk
	Requires at least: 4.6
	Tested up to: 6.5
	Stable tag: 20240301
	Version:    20240301
	Requires PHP: 5.6.20
	Text Domain: head-meta-data
	Domain Path: /languages
	License: GPL v2 or later
*/

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 
	2 of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	with this program. If not, visit: https://www.gnu.org/licenses/
	
	Copyright 2024 Monzilla Media. All rights reserved.
*/

if (!defined('ABSPATH')) die();

$hmd_wp_vers = '4.6';
$hmd_version = '20240301';
$hmd_plugin  = esc_html__('Head Meta Data', 'head-meta-data');
$hmd_options = get_option('hmd_options');
$hmd_path    = plugin_basename(__FILE__); // head-meta-data/head-meta-data.php
$hmd_homeurl = 'https://perishablepress.com/head-metadata-plus/';

function hmd_i18n_init() {
	
	global $hmd_path;
	
	$hmd_path = $hmd_path ? dirname($hmd_path) : null;
	
	if ($hmd_path) load_plugin_textdomain('head-meta-data', false, $hmd_path .'/languages/');
	
}
add_action('init', 'hmd_i18n_init');

function hmd_require_wp_version() {
	
	global $hmd_path, $hmd_plugin, $hmd_wp_vers;
	
	if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
		
		$wp_version = get_bloginfo('version');
		
		if (version_compare($wp_version, $hmd_wp_vers, '<')) {
			
			if (is_plugin_active($hmd_path)) {
				
				deactivate_plugins($hmd_path);
				
				$msg  = '<strong>' . $hmd_plugin . '</strong> ' . esc_html__('requires WordPress ', 'head-meta-data') . $hmd_wp_vers . esc_html__(' or higher, and has been deactivated!', 'head-meta-data') . '<br />';
				$msg .= esc_html__('Please return to the', 'head-meta-data') . ' <a href="' . admin_url() . '">' . esc_html__('WordPress Admin area', 'head-meta-data') . '</a> ' . esc_html__('to upgrade WordPress and try again.', 'head-meta-data');
				
				wp_die($msg);
				
			}
			
		}
		
	}
	
}
add_action('admin_init', 'hmd_require_wp_version');

function head_meta_data() {
	
	echo hmd_display_content();
	
}
add_action('wp_head', 'head_meta_data');

function hmd_shortcode() {
	
	$get_meta_data = hmd_display_content();
	
	$the_meta_data = str_replace(array('>', '<'), array('&gt;','&lt;'), $get_meta_data);
	
	return $the_meta_data;
	
}
add_shortcode('head_meta_data','hmd_shortcode');

function hmd_disable_default() {
	
	if (!is_singular()) return;
	
	global $post;
	
	return get_post_meta($post->ID, 'hmd_disable_default', true);
	
}

function hmd_display_custom() {
	
	if (!is_singular()) return;
	
	global $post;
	
	$custom = '';
	
	$value = get_post_meta($post->ID, 'head-meta-data', false);
	
	if (is_array($value)) {
		
		foreach ($value as $v) {
			
			$custom .= $v . "\n";
			
		}
		
	}
	
	return $custom;
	
}

function hmd_display_content() {
	
	global $hmd_options;
	
	$hmd_output = '';
	
	$hmd_enable = isset($hmd_options['hmd_enable']) ? $hmd_options['hmd_enable'] : false; 
	$hmd_format = isset($hmd_options['hmd_format']) ? $hmd_options['hmd_format'] : false;
	
	$close_tag = ($hmd_format == false) ? '" />' . "\n" : $close_tag = '">' . "\n";
	
	if ($hmd_enable == true && !hmd_disable_default()) {
		
		if (isset($hmd_options['hmd_charset'])    && $hmd_options['hmd_charset']    !== '') $hmd_output  = "\t\t" .'<meta charset="'                       . $hmd_options['hmd_charset']    . $close_tag;
		if (isset($hmd_options['hmd_abstract'])   && $hmd_options['hmd_abstract']   !== '') $hmd_output .= "\t\t" .'<meta name="abstract" content="'       . $hmd_options['hmd_abstract']   . $close_tag;
		if (isset($hmd_options['hmd_author'])     && $hmd_options['hmd_author']     !== '') $hmd_output .= "\t\t" .'<meta name="author" content="'         . $hmd_options['hmd_author']     . $close_tag;
		if (isset($hmd_options['hmd_classify'])   && $hmd_options['hmd_classify']   !== '') $hmd_output .= "\t\t" .'<meta name="classification" content="' . $hmd_options['hmd_classify']   . $close_tag;
		if (isset($hmd_options['hmd_copyright'])  && $hmd_options['hmd_copyright']  !== '') $hmd_output .= "\t\t" .'<meta name="copyright" content="'      . $hmd_options['hmd_copyright']  . $close_tag;
		if (isset($hmd_options['hmd_designer'])   && $hmd_options['hmd_designer']   !== '') $hmd_output .= "\t\t" .'<meta name="designer" content="'       . $hmd_options['hmd_designer']   . $close_tag;
		if (isset($hmd_options['hmd_distribute']) && $hmd_options['hmd_distribute'] !== '') $hmd_output .= "\t\t" .'<meta name="distribution" content="'   . $hmd_options['hmd_distribute'] . $close_tag;
		if (isset($hmd_options['hmd_language'])   && $hmd_options['hmd_language']   !== '') $hmd_output .= "\t\t" .'<meta name="language" content="'       . $hmd_options['hmd_language']   . $close_tag;
		if (isset($hmd_options['hmd_publisher'])  && $hmd_options['hmd_publisher']  !== '') $hmd_output .= "\t\t" .'<meta name="publisher" content="'      . $hmd_options['hmd_publisher']  . $close_tag;
		if (isset($hmd_options['hmd_rating'])     && $hmd_options['hmd_rating']     !== '') $hmd_output .= "\t\t" .'<meta name="rating" content="'         . $hmd_options['hmd_rating']     . $close_tag;
		if (isset($hmd_options['hmd_resource'])   && $hmd_options['hmd_resource']   !== '') $hmd_output .= "\t\t" .'<meta name="resource-type" content="'  . $hmd_options['hmd_resource']   . $close_tag;
		if (isset($hmd_options['hmd_revisit'])    && $hmd_options['hmd_revisit']    !== '') $hmd_output .= "\t\t" .'<meta name="revisit-after" content="'  . $hmd_options['hmd_revisit']    . $close_tag;
		if (isset($hmd_options['hmd_subject'])    && $hmd_options['hmd_subject']    !== '') $hmd_output .= "\t\t" .'<meta name="subject" content="'        . $hmd_options['hmd_subject']    . $close_tag;
		if (isset($hmd_options['hmd_template'])   && $hmd_options['hmd_template']   !== '') $hmd_output .= "\t\t" .'<meta name="template" content="'       . $hmd_options['hmd_template']   . $close_tag;
		if (isset($hmd_options['hmd_robots'])     && $hmd_options['hmd_robots']     !== '') $hmd_output .= "\t\t" .'<meta name="robots" content="'         . $hmd_options['hmd_robots']     . $close_tag;
	}
	
	$hmd_output .= hmd_display_custom();
	
	return $hmd_output;
	
}

function hmd_custom_shortcode() {
	
	global $hmd_options;
	
	if ($hmd_options['hmd_custom'] !== '') {
		
		$get_custom_data = $hmd_options['hmd_custom'];
		
		$the_custom_data = "\t\t" . str_replace(array('>', '<'), array('&gt;','&lt;'), $get_custom_data);
		
		return $the_custom_data;
		
	}
	
}
add_shortcode('hmd_custom','hmd_custom_shortcode');

function hmd_custom_content() {
	
	global $hmd_options, $post;
	
	if (empty($post)) return;
	
	$post_id   = $post->ID;
	$author_id = $post->post_author;
	
	$custom = isset($hmd_options['hmd_custom']) ? $hmd_options['hmd_custom'] : '';
	
	$format = apply_filters('hmd_date_format', 'Y-m-d');
	
	if (is_singular()) {
		
		$post_excerpt = get_the_excerpt($post_id); // fyi: https://bit.ly/30uneVr
		$post_date    = get_the_modified_date($format);
		$post_author  = get_the_author_meta('display_name', $author_id);
		$post_title   = get_the_title($post_id);
		$post_cats    = get_the_category($post_id);
		$post_tags    = get_the_tags($post_id);
		
	} else {
		
		$post_excerpt = get_bloginfo('description');
		$post_date    = hmd_latest_post_date($format);
		$post_author  = get_bloginfo('name');
		$post_title   = get_bloginfo('description');
		$post_cats    = get_the_terms($post_id, 'category');
		$post_tags    = get_the_terms($post_id, 'post_tag');
		
	}
	
	if ($post_excerpt && !is_wp_error($post_excerpt)) {
		
		$length = apply_filters('hmd_excerpt_length', 160);
		$post_excerpt = wp_strip_all_tags($post_excerpt, true);
		$post_excerpt = substr($post_excerpt, 0, $length);
		
	} else {
		
		$post_excerpt = get_bloginfo('description');
		
	}
	
	if ($post_cats && !is_wp_error($post_cats)) {
		
		$names = wp_list_pluck($post_cats, 'name');
		$post_cats = implode(', ', $names);
		
	} else {
		
		$post_cats = get_bloginfo('name');
		
	}
	
	if ($post_tags && !is_wp_error($post_tags)) {
		
		$names = wp_list_pluck($post_tags, 'name');
		$post_tags = implode(', ', $names);
		
	} else {
		
		$post_tags = get_bloginfo('name');
		
	}
	
	$patterns = array();
	$patterns[0] = "/\[hmd_tab\]/";
	$patterns[1] = "/\[hmd_post_excerpt\]/";
	$patterns[2] = "/\[hmd_post_date\]/";
	$patterns[3] = "/\[hmd_post_author\]/";
	$patterns[4] = "/\[hmd_post_title\]/";
	$patterns[5] = "/\[hmd_post_cats\]/";
	$patterns[6] = "/\[hmd_post_tags\]/";
	$patterns[7] = "/\[hmd_year\]/";
	
	$replacements = array();
	$replacements[0] = "\t";
	$replacements[1] = $post_excerpt;
	$replacements[2] = $post_date;
	$replacements[3] = $post_author;
	$replacements[4] = $post_title;
	$replacements[5] = $post_cats;
	$replacements[6] = $post_tags;
	$replacements[7] = date('Y');
	
	$custom = preg_replace($patterns, $replacements, $custom);
	
	echo "\t\t" . $custom . "\n";
	
}
add_action('wp_head', 'hmd_custom_content');

function hmd_latest_post_date($format) {
	
	$args = array(
		'posts_per_page' => 1,
		'orderby'        => 'date',
		'post_status'    => 'publish',
	);
	$posts = get_posts($args);
	
	$updated = '';
	
	foreach ($posts as $post) {
		
		setup_postdata($post);
		
		$updated = get_the_modified_date($format);
		
	}
	
	wp_reset_postdata();
	
	return $updated;
	
}

function hmd_plugin_action_links($links, $file) {
	global $hmd_path;
	if ($file === $hmd_path && (current_user_can('manage_options'))) {
		$hmd_links = '<a href="'. admin_url('options-general.php?page=head-meta-data') .'">'. esc_html__('Settings', 'head-meta-data') .'</a>';
		array_unshift($links, $hmd_links);
	}
	return $links;
}
add_filter ('plugin_action_links', 'hmd_plugin_action_links', 10, 2);

function add_hmd_links($links, $file) {
	global $hmd_path;
	if ($file === $hmd_path) {
		
		$home_href  = 'https://perishablepress.com/head-metadata-plus/';
		$home_title = esc_attr__('Plugin Homepage', 'head-meta-data');
		$home_text  = esc_html__('Homepage', 'head-meta-data');
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $home_href .'" title="'. $home_title .'">'. $home_text .'</a>';
		
		$href  = 'https://wordpress.org/support/plugin/head-meta-data/reviews/?rate=5#new-post';
		$title = esc_html__('Give us a 5-star rating at WordPress.org', 'head-meta-data');
		$text  = esc_html__('Rate this plugin', 'head-meta-data') .'&nbsp;&raquo;';
		
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $href .'" title="'. $title .'">'. $text .'</a>';
		
	}
	return $links;
}
add_filter('plugin_row_meta', 'add_hmd_links', 10, 2);

function hmd_footer_text($text) {
	
	$screen_id = hmd_get_current_screen_id();
	
	$ids = array('settings_page_head-meta-data/head-meta-data');
	
	if ($screen_id && apply_filters('head_meta_data_admin_footer_text', in_array($screen_id, $ids))) {
		
		$text = __('Like this plugin? Give it a', 'head-meta-data');
		
		$text .= ' <a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/head-meta-data/reviews/?rate=5#new-post">';
		
		$text .= __('★★★★★ rating&nbsp;&raquo;', 'head-meta-data') .'</a>';
		
	}
	
	return $text;
	
}
add_filter('admin_footer_text', 'hmd_footer_text', 10, 1);

function hmd_get_current_screen_id() {
	
	if (!function_exists('get_current_screen')) require_once ABSPATH .'/wp-admin/includes/screen.php';
	
	$screen = get_current_screen();
	
	if ($screen && property_exists($screen, 'id')) return $screen->id;
	
	return false;
	
}

function hmd_delete_plugin_options() {
	delete_option('hmd_options');
}
if (isset($hmd_options['default_options']) && $hmd_options['default_options'] == 1) {
	register_uninstall_hook (__FILE__, 'hmd_delete_plugin_options');
}

function hmd_add_defaults() {
	// meta subject
	$args = array('orderby'=>'name', 'order'=>'ASC');
	$categories = get_categories($args);
	$num_cats = count($categories);
	$subjects = '';
	$i = 0;
	foreach ($categories as $category) { 
		$subjects .= $category->name;
		if (++$i !== $num_cats) {
			$subjects .= ', ';
		}
	}
	// name, description, language
	$site_name = get_bloginfo('name');
	$site_desc = get_bloginfo('description');
	$site_lang = get_bloginfo('language');
	// template and designer
	$get_theme = wp_get_theme();
	$the_theme = $get_theme->Name;
	$designer  = $get_theme->display('Author', FALSE);;
	// author name
	$user_info = get_userdata(1);
	if ($user_info == true) {
		$admin_name = $user_info->user_login;
	} else {
		$admin_name = 'Perishable';
	}
	$tmp = get_option('hmd_options');
	
	if ((isset($tmp['default_options']) && $tmp['default_options'] == '1') || (!is_array($tmp))) {
		$arr = array(
			'default_options' => 0,
			'hmd_charset'     => 'utf-8',
			'hmd_abstract'    => $site_desc,
			'hmd_author'      => $admin_name,
			'hmd_classify'    => $subjects,
			'hmd_copyright'   => 'Copyright ' . $site_name . ' - All rights Reserved.',
			'hmd_designer'    => $designer,
			'hmd_distribute'  => 'Global',
			'hmd_language'    => $site_lang,
			'hmd_publisher'   => $site_name,
			'hmd_rating'      => 'General',
			'hmd_resource'    => 'Document',
			'hmd_revisit'     => '3',
			'hmd_subject'     => $subjects,
			'hmd_template'    => $the_theme,
			'hmd_enable'      => 1,
			'hmd_custom'      => '<meta name="example" content="custom: [hmd_post_date]">',
			'hmd_format'      => 1,
			'hmd_robots'      => 'index,follow',
		);
		update_option('hmd_options', $arr);
	}
}
register_activation_hook(__FILE__, 'hmd_add_defaults');

function hmd_init() {
	register_setting('hmd_plugin_options', 'hmd_options', 'hmd_validate_options');
}
add_action ('admin_init', 'hmd_init');

function hmd_validate_options($input) {
	
	if (!isset($input['default_options'])) $input['default_options'] = null;
	$input['default_options'] = ($input['default_options'] == 1 ? 1 : 0);
	
	$input['hmd_charset']    = esc_attr($input['hmd_charset']);
	$input['hmd_abstract']   = esc_attr($input['hmd_abstract']);
	$input['hmd_author']     = esc_attr($input['hmd_author']);
	$input['hmd_classify']   = esc_attr($input['hmd_classify']);
	$input['hmd_copyright']  = esc_attr($input['hmd_copyright']);
	$input['hmd_designer']   = esc_attr($input['hmd_designer']);
	$input['hmd_distribute'] = esc_attr($input['hmd_distribute']);
	$input['hmd_language']   = esc_attr($input['hmd_language']);
	$input['hmd_publisher']  = esc_attr($input['hmd_publisher']);
	$input['hmd_rating']     = esc_attr($input['hmd_rating']);
	$input['hmd_resource']   = esc_attr($input['hmd_resource']);
	$input['hmd_revisit']    = esc_attr($input['hmd_revisit']);
	$input['hmd_subject']    = esc_attr($input['hmd_subject']);
	$input['hmd_template']   = esc_attr($input['hmd_template']);
	$input['hmd_robots']     = esc_attr($input['hmd_robots']);
	
	if (!isset($input['hmd_enable'])) $input['hmd_enable'] = null;
	$input['hmd_enable'] = ($input['hmd_enable'] == 1 ? 1 : 0);

	// dealing with kses
	global $allowedposttags;
	$default_allowedposttags = $allowedposttags; 
	$allowed_atts = array(
		'align'=>array(), 'class'=>array(), 'id'=>array(), 'dir'=>array(), 'lang'=>array(), 'style'=>array(), 'label'=>array(), 'url'=>array(), 
		'xml:lang'=>array(), 'src'=>array(), 'alt'=>array(), 'name'=>array(), 'content'=>array(), 'http-equiv'=>array(), 'profile'=>array(), 
		'href'=>array(), 'property'=>array(), 'title'=>array(), 'rel'=>array(), 'type'=>array(), 'charset'=>array(), 'media'=>array(), 'rev'=>array(),
		);
	$allowedposttags['strong'] = $allowed_atts;
	$allowedposttags['script'] = $allowed_atts;
	$allowedposttags['style'] = $allowed_atts;
	$allowedposttags['small'] = $allowed_atts;
	$allowedposttags['span'] = $allowed_atts;
	$allowedposttags['meta'] = $allowed_atts;
	$allowedposttags['item'] = $allowed_atts;
	$allowedposttags['base'] = $allowed_atts;
	$allowedposttags['link'] = $allowed_atts;
	$allowedposttags['abbr'] = $allowed_atts;
	$allowedposttags['code'] = $allowed_atts;
	$allowedposttags['div'] = $allowed_atts;
	$allowedposttags['img'] = $allowed_atts;
	$allowedposttags['h1'] = $allowed_atts;
	$allowedposttags['h2'] = $allowed_atts;
	$allowedposttags['h3'] = $allowed_atts;
	$allowedposttags['h4'] = $allowed_atts;
	$allowedposttags['h5'] = $allowed_atts;
	$allowedposttags['ol'] = $allowed_atts;
	$allowedposttags['ul'] = $allowed_atts;
	$allowedposttags['li'] = $allowed_atts;
	$allowedposttags['em'] = $allowed_atts;
	$allowedposttags['p'] = $allowed_atts;
	$allowedposttags['a'] = $allowed_atts;

	$input['hmd_custom'] = wp_kses($input['hmd_custom'], $allowedposttags);

	if (!isset($input['hmd_format'])) $input['hmd_format'] = null;
	$input['hmd_format'] = ($input['hmd_format'] == 1 ? 1 : 0);

	$allowedposttags = $default_allowedposttags;

	return $input;
}

function hmd_add_options_page() {
	global $hmd_plugin;
	// add_options_page($page_title, $menu_title, $capability, $menu_slug, $callback, $position)
	add_options_page($hmd_plugin, $hmd_plugin, 'manage_options', 'head-meta-data', 'hmd_render_form');
}
add_action ('admin_menu', 'hmd_add_options_page');

function hmd_render_form() {
	global $hmd_plugin, $hmd_options, $hmd_homeurl, $hmd_version; ?>

	<style type="text/css">
		#mm-plugin-options .mm-panel-overview {
			box-sizing: border-box; width: 100%; overflow: hidden; position: relative; padding: 0 15px 15px 140px; 
			background-image: url(<?php echo plugins_url(); ?>/head-meta-data/hmd-logo.jpg);
			background-repeat: no-repeat; background-position: 15px 0; background-size: 120px 88px;
			}
		.mm-panel-overview .main { width: 100%; }
		.mm-panel-overview .info { 
			position: absolute; width: 30%; bottom: 0; right: 0;
			background-color: #FDF2D2; background-image: linear-gradient(to right, #FDF2D2, #fff);
			}
		@media (max-width: 880px) {
			.mm-panel-overview .main { width: 60%; }
		}
		@media (max-width: 680px) {
			.mm-panel-overview .main, .mm-panel-overview .info { width: 100%; float: none; position: static; }
			.mm-panel-overview .info { padding: 5px 15px 5px 0; }
		}
		
		#mm-plugin-options .mm-panel-toggle { margin: 5px 0; }
		#mm-plugin-options .mm-credit-info { margin: -10px 0 10px 5px; font-size: 12px; }
		#mm-plugin-options .button-primary { margin: 0 0 15px 15px; }
		
		#mm-plugin-options #setting-error-settings_updated { margin: 5px 0 15px 0; }
		#mm-plugin-options #setting-error-settings_updated p { margin: 7px 0 6px 0; }
		
		#mm-plugin-options .mm-table-wrap { margin: 15px; }
		#mm-plugin-options .mm-table-wrap td { padding: 5px 10px; vertical-align: middle; }
		#mm-plugin-options .mm-table-wrap .mm-table { padding: 10px 0; }
		#mm-plugin-options .mm-table-wrap .widefat th { padding: 10px 15px; vertical-align: middle; }
		#mm-plugin-options .mm-table-wrap .widefat td { padding: 10px; vertical-align: middle; }
		
		#mm-plugin-options h1 small { line-height: 12px; font-size: 12px; color: #bbb; }
		#mm-plugin-options h2 { margin: 0; padding: 12px 0 12px 15px; font-size: 16px; cursor: pointer; }
		#mm-plugin-options h3 { margin: 20px 15px; font-size: 14px; }
		.wrap form p { margin-left: 15px; }
		#mm-plugin-options ul { margin: 15px 15px 20px 40px; line-height: 16px; }
		#mm-plugin-options li { margin: 8px 0; list-style-type: disc; }
		
		#mm-plugin-options textarea { width: 80%; }
		#mm-plugin-options input[type=text] { width: 60%; }
		#mm-plugin-options input[type=checkbox] { margin-top: -3px; }
		#mm-plugin-options .mm-radio-inputs { margin: 5px 0; }
		#mm-plugin-options .mm-code { 
			display: inline-block; margin: 0 1px; padding: 3px; direction: ltr; unicode-bidi: embed;
			color: #333; background-color: #eaeaea; background-color: rgba(0,0,0,0.07);
			font-size: 13px; font-family: Consolas, Monaco, monospace;
			}
		#mm-plugin-options .mm-item-caption { margin: 3px 0 0 3px; line-height: 17px; font-size: 12px; color: #777; }
		#mm-plugin-options .mm-item-caption code { margin: 0; padding: 3px; font-size: 12px; background: #f2f2f2; background-color: rgba(0,0,0,0.05); }
		#mm-plugin-options .mm-item-caption-nomargin { margin: 0; }
		#mm-plugin-options textarea + .mm-item-caption { margin: 0 0 0 3px; }
		
		#mm-plugin-options .mm-code-example { margin: 10px 0 20px 0; }
		#mm-plugin-options .mm-code-example div { margin-left: 15px; }
		#mm-plugin-options .mm-code-example pre { 
			box-sizing: border-box; width: 90%; overflow: auto; margin: 10px 20px; padding: 10px; 
			border: 1px solid #efefef; background-color: #fffeee; 
			}
		
		.wp-admin .notice code { line-height: 1; font-size: 12px; }
		.wp-admin .hmd-dismiss-notice { float: right; }
		
		@media (max-width: 1000px) {
			#mm-plugin-options input[type=text] { width: 80%; }
			#mm-plugin-options textarea { width: 90%; }
		}
		@media (max-width: 782px) {
			#mm-plugin-options .mm-radio-inputs { margin: 10px 0; }
		}
		@media (max-width: 600px) {
			#mm-plugin-options input[type=text], 
			#mm-plugin-options textarea { width: 98%; }
		}
		@media (max-width: 1100px) {
			.wp-admin .hmd-dismiss-notice { float: none; }
		}
	</style>

	<div id="mm-plugin-options" class="wrap">
		<h1><?php echo $hmd_plugin; ?> <small><?php echo 'v'. $hmd_version; ?></small></h1>
		<div class="mm-panel-toggle"><a href="<?php echo admin_url('options-general.php?page=head-meta-data'); ?>"><?php esc_html_e('Toggle all panels', 'head-meta-data'); ?></a></div>

		<form method="post" action="options.php">
			<?php $hmd_options = get_option('hmd_options'); settings_fields('hmd_plugin_options'); ?>

			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					
					<div id="mm-panel-overview" class="postbox">
						<h2><?php esc_html_e('Overview', 'head-meta-data'); ?></h2>
						<div class="toggle<?php if (isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<div class="mm-panel-overview">
								<div class="main">
									<p>
										<strong><?php echo $hmd_plugin; ?></strong> <?php esc_html_e('(HMD) adds a custom set of', 'head-meta-data'); ?> 
										<code>&lt;meta&gt;</code> <?php esc_html_e('tags to the', 'head-meta-data'); ?> 
										<code>&lt;head&gt;</code> <?php esc_html_e('section of all posts and pages.', 'head-meta-data'); ?>
									</p>
									<ul>
										<li><a id="mm-panel-primary-link" href="#mm-panel-primary"><?php esc_html_e('Plugin Settings', 'head-meta-data'); ?></a></li>
										<li><a id="mm-panel-secondary-link" href="#mm-panel-secondary"><?php esc_html_e('Live Preview', 'head-meta-data'); ?></a></li>
										<li><a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/head-meta-data/"><?php esc_html_e('Plugin Homepage', 'head-meta-data'); ?></a></li>
									</ul>
									<p>
										<?php esc_html_e('If you like this plugin, please', 'head-meta-data'); ?> 
										<a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/head-meta-data/reviews/?rate=5#new-post" title="<?php esc_attr_e('THANK YOU for your support!', 'head-meta-data'); ?>"><?php esc_html_e('give it a 5-star rating', 'head-meta-data'); ?>&nbsp;&raquo;</a>
									</p>
								</div>
								<div class="info">
									<p>🔥 <?php esc_html_e('Pro version coming soon!', 'head-meta-data'); ?></p>
								</div>
							</div>
						</div>
					</div>
					
					<div id="mm-panel-primary" class="postbox">
						<h2><?php esc_html_e('Plugin Settings', 'head-meta-data'); ?></h2>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<h3><?php esc_html_e('Meta Tags', 'head-meta-data'); ?></h3>
							<p>
								<?php esc_html_e('Here you may define your', 'head-meta-data'); ?> <code>&lt;meta&gt;</code> <?php esc_html_e('tags. To disable any tag, leave it blank.', 'head-meta-data'); ?> 
								<?php esc_html_e('To add more tags, visit the option &ldquo;Custom Content&rdquo; below.', 'head-meta-data'); ?> 
							</p>
							<div class="mm-table-wrap">
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label for="hmd_options[hmd_enable]"><?php esc_html_e('Enable tags', 'head-meta-data'); ?></label></th>
										<td><input type="checkbox" name="hmd_options[hmd_enable]" value="1" <?php if (isset($hmd_options['hmd_enable'])) checked('1', $hmd_options['hmd_enable']); ?>> 
										<span><?php esc_html_e('Enable meta tags', 'head-meta-data'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_format]"><?php esc_html_e('HTML format', 'head-meta-data'); ?></label></th>
										<td><input type="checkbox" name="hmd_options[hmd_format]" value="1" <?php if (isset($hmd_options['hmd_format'])) checked('1', $hmd_options['hmd_format']); ?>> 
										<span><?php esc_html_e('Check box for HTML format (default), or leave unchecked for XHTML format', 'head-meta-data'); ?></span></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_charset]"><?php esc_html_e('Meta Charset', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_charset]" value="<?php if (isset($hmd_options['hmd_charset'])) echo esc_attr($hmd_options['hmd_charset']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Specify the character encoding for your web pages (default: utf-8)', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_abstract]"><?php esc_html_e('Meta abstract', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_abstract]" value="<?php if (isset($hmd_options['hmd_abstract'])) echo esc_attr($hmd_options['hmd_abstract']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Summarize your site&rsquo;s content in a short sentence', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_author]"><?php esc_html_e('Meta author', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_author]" value="<?php if (isset($hmd_options['hmd_author'])) echo esc_attr($hmd_options['hmd_author']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Indicate your site&rsquo;s author(s)', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_classify]"><?php esc_html_e('Meta classification', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_classify]" value="<?php if (isset($hmd_options['hmd_classify'])) echo esc_attr($hmd_options['hmd_classify']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Classify your site, examples: Shopping, Movies, Food', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_copyright]"><?php esc_html_e('Meta copyright', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_copyright]" value="<?php if (isset($hmd_options['hmd_copyright'])) echo esc_attr($hmd_options['hmd_copyright']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Indicate your site&rsquo;s copyright information', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_designer]"><?php esc_html_e('Meta designer', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_designer]" value="<?php if (isset($hmd_options['hmd_designer'])) echo esc_attr($hmd_options['hmd_designer']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Indicate your site&rsquo;s designer/developer', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_distribute]"><?php esc_html_e('Meta distribution', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_distribute]" value="<?php if (isset($hmd_options['hmd_distribute'])) echo esc_attr($hmd_options['hmd_distribute']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Indicate your site&rsquo;s distribution level, examples: Global, Regional, Local', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_language]"><?php esc_html_e('Meta language', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_language]" value="<?php if (isset($hmd_options['hmd_language'])) echo esc_attr($hmd_options['hmd_language']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Indicate your site&rsquo;s primary language, examples: EN-US, EN, FR', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_publisher]"><?php esc_html_e('Meta publisher', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_publisher]" value="<?php if (isset($hmd_options['hmd_publisher'])) echo esc_attr($hmd_options['hmd_publisher']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Indicate your site&rsquo;s publisher', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_rating]"><?php esc_html_e('Meta rating', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_rating]" value="<?php if (isset($hmd_options['hmd_rating'])) echo esc_attr($hmd_options['hmd_rating']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Indicate the rating of your site&rsquo;s content, examples: General, Mature, Restricted', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_resource]"><?php esc_html_e('Meta resource-type', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_resource]" value="<?php if (isset($hmd_options['hmd_resource'])) echo esc_attr($hmd_options['hmd_resource']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Indicate your site&rsquo;s primary resource type, examples: Document', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_revisit]"><?php esc_html_e('Meta revisit-after', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_revisit]" value="<?php if (isset($hmd_options['hmd_revisit'])) echo esc_attr($hmd_options['hmd_revisit']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Frequency (in days) for search engines to revisit your site for re-indexing, examples: 1, 2, 3', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_subject]"><?php esc_html_e('Meta subject', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_subject]" value="<?php if (isset($hmd_options['hmd_subject'])) echo esc_attr($hmd_options['hmd_subject']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Indicate your site&rsquo;s primary subject(s), examples: Photography, Sports, Pancakes', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_template]"><?php esc_html_e('Meta template', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_template]" value="<?php if (isset($hmd_options['hmd_template'])) echo esc_attr($hmd_options['hmd_template']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Indicate any template used by your site, example: Awesome WordPress Theme', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label for="hmd_options[hmd_robots]"><?php esc_html_e('Meta Robots', 'head-meta-data'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="hmd_options[hmd_robots]" value="<?php if (isset($hmd_options['hmd_robots'])) echo esc_attr($hmd_options['hmd_robots']); ?>">
										<div class="mm-item-caption"><?php esc_html_e('Indicate any robots directives for your site, example: index,follow', 'head-meta-data'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label><?php esc_html_e('More tags', 'head-meta-data'); ?></label></th>
										<td>
											<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/support/#contact" title="<?php esc_html_e('Contact the developer via contact form', 'head-meta-data'); ?>">
											<?php esc_html_e('Suggest more meta tags', 'head-meta-data'); ?>&nbsp;&raquo;</a>
										</td>
									</tr>
								</table>
							</div>
							<h3><?php esc_html_e('Custom Content', 'head-meta-data'); ?></h3>
							<p>
								<?php esc_html_e('Here you may define any custom content that should be included in the head section of your pages. You also can add custom tags to specific posts and pages. Check the', 'head-meta-data'); ?> 
								<a target="_blank" rel="noopener noreferrer" href="https://wordpress.org/plugins/head-meta-data/#installation"><?php esc_html_e('Installation docs', 'head-meta-data'); ?></a> <?php esc_html_e('(under &ldquo;Custom meta tags&rdquo;) for more details.', 'head-meta-data'); ?>
							</p>
							<p>
								<?php esc_html_e('In the next setting, you can include any of the following shortcodes to display dynamic information:', 'head-meta-data'); ?> 
								<span class="mm-code">[hmd_post_excerpt]</span>, <span class="mm-code">[hmd_post_date]</span>, <span class="mm-code">[hmd_post_author]</span>, <span class="mm-code">[hmd_post_title]</span>, 
								<span class="mm-code">[hmd_post_cats]</span>, <span class="mm-code">[hmd_post_tags]</span>, <span class="mm-code">[hmd_year]</span>, <span class="mm-code">[hmd_tab]</span> = tab/space.
							</p>
							<div class="mm-table-wrap">
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label for="hmd_options[hmd_custom]"><?php esc_html_e('Custom content', 'head-meta-data'); ?></label></th>
										<td>
											<textarea class="large-text code" type="textarea" rows="7" cols="55" name="hmd_options[hmd_custom]"><?php if (isset($hmd_options['hmd_custom'])) echo esc_textarea($hmd_options['hmd_custom']); ?></textarea>
											<div class="mm-item-caption">
												<?php esc_html_e('Optional tags/markup for the', 'head-meta-data'); ?> <code>&lt;head&gt;</code> 
												<?php esc_html_e('section (leave blank to disable). For more ideas, check out', 'head-meta-data'); ?> 
												<a target="_blank" rel="noopener noreferrer" href="https://perishablepress.com/xhtml-document-header-resource/"><?php esc_html_e('this article at Perishable Press', 'head-meta-data'); ?>&nbsp;&raquo;</a>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'head-meta-data'); ?>">
						</div>
					</div>
					
					<div id="mm-panel-secondary" class="postbox">
						<h2><?php esc_html_e('Live Preview', 'head-meta-data'); ?></h2>
						<div class="toggle default-hidden">
							<p><?php esc_html_e('Here is a preview of your meta tags and custom content. Note that any special characters will be encoded in the actual page markup.', 'head-meta-data'); ?></p>
							<div class="mm-code-example">
								
								<h3><?php esc_html_e('Meta tags', 'head-meta-data'); ?></h3>
								<p><?php esc_html_e('When meta tags are enabled, the following code will be added to the head section of your web pages.', 'head-meta-data'); ?></p>
								<pre><?php echo do_shortcode('[head_meta_data]', 'head-meta-data'); ?></pre>
								
								<h3><?php esc_html_e('Custom content', 'head-meta-data'); ?></h3>
								<p><?php esc_html_e('Visit your front-end pages to view any dynamic shortcode output.', 'head-meta-data'); ?></p>
								<pre><?php echo do_shortcode('[hmd_custom]', 'head-meta-data'); ?></pre>
								
								<h3><?php esc_html_e('More infos', 'head-meta-data'); ?></h3>
								<ul>
									<li>
										<?php esc_html_e('For more information on document headers:', 'head-meta-data'); ?> 
										<a target="_blank" rel="noopener noreferrer" href="https://m0n.co/c" title="<?php esc_attr_e('XHTML Document Header Resource', 'head-meta-data'); ?>">https://m0n.co/c</a>
									</li>
									<li>
										<?php esc_html_e('And more specifically the section on meta tags:', 'head-meta-data'); ?> 
										<a target="_blank" rel="noopener noreferrer" href="https://m0n.co/d" title="<?php esc_attr_e('XHTML Document Header Resource: meta tags', 'head-meta-data'); ?>">https://m0n.co/d</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					
					<div id="mm-restore-settings" class="postbox">
						<h2><?php esc_html_e('Restore Defaults', 'head-meta-data'); ?></h2>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<p>
								<input name="hmd_options[default_options]" type="checkbox" value="1" id="mm_restore_defaults" <?php if (isset($hmd_options['default_options'])) { checked('1', $hmd_options['default_options']); } ?>> 
								<?php esc_html_e('Restore default options upon plugin deactivation/reactivation.', 'head-meta-data'); ?>
							</p>
							<p>
								<span class="mm-item-caption mm-item-caption-nomargin">
									<strong><?php esc_html_e('Tip:', 'head-meta-data'); ?></strong> 
									<?php esc_html_e('leave this option unchecked to remember your settings. Or, to go ahead and restore all default options, check the box, save your settings, and then deactivate/reactivate the plugin.', 'head-meta-data'); ?>
								</span>
							</p>
							<input type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'head-meta-data'); ?>">
						</div>
					</div>
					
					<div id="mm-panel-current" class="postbox">
						<h2><?php esc_html_e('WP Resources', 'head-meta-data'); ?></h2>
						<div class="toggle">
							<?php require_once('support-panel.php'); ?>
						</div>
					</div>
					
				</div>
			</div>
			
			<div class="mm-credit-info">
				<a target="_blank" rel="noopener noreferrer" href="<?php echo esc_url($hmd_homeurl); ?>" title="<?php esc_attr_e('Plugin Homepage', 'head-meta-data'); ?>"><?php echo esc_html($hmd_plugin); ?></a> <?php esc_html_e('by', 'head-meta-data'); ?> 
				<a target="_blank" rel="noopener noreferrer" href="https://twitter.com/perishable" title="<?php esc_attr_e('Jeff Starr on Twitter', 'head-meta-data'); ?>">Jeff Starr</a> @ 
				<a target="_blank" rel="noopener noreferrer" href="https://monzillamedia.com/" title="<?php esc_attr_e('Obsessive Web Design &amp; Development', 'head-meta-data'); ?>">Monzilla Media</a>
			</div>
			
		</form>
	</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
			// toggle panels
			jQuery('.default-hidden').hide();
			jQuery('.mm-panel-toggle a').click(function(){
				jQuery('.toggle').slideToggle(300);
				return false;
			});
			jQuery('h2').click(function(){
				jQuery(this).next().slideToggle(300);
			});
			jQuery('#mm-panel-primary-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-panel-primary .toggle').slideToggle(300);
				return true;
			});
			jQuery('#mm-panel-secondary-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-panel-secondary .toggle').slideToggle(300);
				return true;
			});
			// prevent accidents
			if(!jQuery("#mm_restore_defaults").is(":checked")){
				jQuery('#mm_restore_defaults').click(function(event){
					var r = confirm("<?php esc_html_e('Are you sure you want to restore all default options?', 'head-meta-data'); ?>");
					if (r == true){  
						jQuery("#mm_restore_defaults").attr('checked', true);
					} else {
						jQuery("#mm_restore_defaults").attr('checked', false);
					}
				});
			}
		});
	</script>

<?php }

function hmd_admin_notice() {
	
	if (hmd_get_current_screen_id() === 'settings_page_head-meta-data') {
		
		if (!hmd_check_date_expired() && !hmd_dismiss_notice_check()) {
			
			?>
			
			<div class="notice notice-success">
				<p>
					<strong><?php esc_html_e('Go Pro!', 'head-meta-data'); ?></strong> 
					<?php esc_html_e('Save 30% on our', 'head-meta-data'); ?> 
					<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/"><?php esc_html_e('Pro WordPress plugins', 'head-meta-data'); ?></a> 
					<?php esc_html_e('and', 'head-meta-data'); ?> 
					<a target="_blank" rel="noopener noreferrer" href="https://books.perishablepress.com/"><?php esc_html_e('books', 'head-meta-data'); ?></a>. 
					<?php esc_html_e('Apply code', 'head-meta-data'); ?> <code>PLANET24</code> <?php esc_html_e('at checkout. Sale ends 5/25/24.', 'head-meta-data'); ?> 
					<?php echo hmd_dismiss_notice_link(); ?>
				</p>
			</div>
			
			<?php
			
		}
		
	}
	
}
add_action('admin_notices', 'hmd_admin_notice');

function hmd_dismiss_notice_activate() {
	
	delete_option('head-meta-data-dismiss-notice');
	
}
register_activation_hook(__FILE__, 'hmd_dismiss_notice_activate');

function hmd_dismiss_notice_version() {
	
	global $hmd_wp_vers;
	
	$version_current = $hmd_wp_vers;
	
	$version_previous = get_option('head-meta-data-dismiss-notice');
	
	$version_previous = ($version_previous) ? $version_previous : $version_current;
	
	if (version_compare($version_current, $version_previous, '>')) {
		
		delete_option('head-meta-data-dismiss-notice');
		
	}
	
}
add_action('admin_init', 'hmd_dismiss_notice_version');

function hmd_dismiss_notice_check() {
	
	$check = get_option('head-meta-data-dismiss-notice');
	
	return ($check) ? true : false;
	
}

function hmd_dismiss_notice_save() {
	
	if (isset($_GET['dismiss-notice-verify']) && wp_verify_nonce($_GET['dismiss-notice-verify'], 'hmd_dismiss_notice')) {
		
		if (!current_user_can('manage_options')) exit;
		
		global $hmd_wp_vers;
		
		$result = update_option('head-meta-data-dismiss-notice', $hmd_wp_vers, false);
		
		$result = $result ? 'true' : 'false';
		
		$location = admin_url('options-general.php?page=head-meta-data&dismiss-notice='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}
add_action('admin_init', 'hmd_dismiss_notice_save');

function hmd_dismiss_notice_link() {
	
	$nonce = wp_create_nonce('hmd_dismiss_notice');
	
	$href  = add_query_arg(array('dismiss-notice-verify' => $nonce), admin_url('options-general.php?page=head-meta-data'));
	
	$label = esc_html__('Dismiss', 'head-meta-data');
	
	echo '<a class="hmd-dismiss-notice" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}

function hmd_check_date_expired() {
	
	$expires = apply_filters('hmd_check_date_expired', '2024-05-25');
	
	return (new DateTime() > new DateTime($expires)) ? true : false;
	
}
