<?php
/*
Plugin Name: Native WP Cleaner
Plugin URI:  
Description: This plugin allows you to easily clean your HTML code from native worpress tags, like: RSS Feed link, RSD link, Generator meta, etc. Also you can disable native wordpress widgets, different functions like: XML-RPC, WLW Manifest, self ping and more. After activation see new sub-page in Settings menu.
Version:     1.0
Author:      Oleg Komarovskyi
Author URI:  https://profiles.wordpress.org/komarovski
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: native-wp-cleaner
Domain Path: /languages/
*/
defined('ABSPATH') or die('No script kiddies please!');
//Unique prefix = nwpcpcode (Native Word Press Cleaner Plugin Code)
//Localization
function nwpcpcode_init(){load_plugin_textdomain('native-wp-cleaner', false, basename(dirname(__FILE__)).'/languages/');}
add_action('init', 'nwpcpcode_init');
//Global variable
$nwpcpcode_tab = get_option('nwpcpcodesettings');
global $nwpcpcode_tab;
//Remove RSS Feed link?
if($nwpcpcode_tab['nwpcpcode_rss_feed_link_remove'] == 'Yes'){remove_action('wp_head', 'feed_links_extra', 3);}
//Remove RSD link
if($nwpcpcode_tab['nwpcpcode_rsd_link_remove'] == 'Yes'){remove_action('wp_head', 'rsd_link');}
//Remove generator meta
if($nwpcpcode_tab['nwpcpcode_meta_generator_remove'] == 'Yes'){remove_action('wp_head', 'wp_generator');}
//Remove WLW Manifest link
if($nwpcpcode_tab['nwpcpcode_wlwmanifest_link_remove'] == 'Yes'){remove_action('wp_head', 'wlwmanifest_link');}
//Disable Embeds
if($nwpcpcode_tab['nwpcpcode_disable_embeds'] == 'Yes'){
	function nwpcpcode_disable_embeds_function(){wp_deregister_script('wp-embed');}
	add_action('wp_footer', 'nwpcpcode_disable_embeds_function');
}
//Disable EMOJI
if($nwpcpcode_tab['nwpcpcode_disable_emoji_smiles'] == 'Yes'){
	function nwpcpcode_disable_emoji_smiles_function(){
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
		add_filter('tiny_mce_plugins', 'nwpcpcode_disable_emoji_smiles_tinymce_function');
	}
	add_action('init', 'nwpcpcode_disable_emoji_smiles_function');
	function nwpcpcode_disable_emoji_smiles_tinymce_function($plugins){if(is_array($plugins)){return array_diff($plugins, array('wpemoji'));}else{return array();}}
	add_filter('emoji_svg_url', '__return_false');
}
//Remove version from CSS and JS
if($nwpcpcode_tab['nwpcpcode_remove_version_css_js'] == 'Yes'){
	function nwpcpcode_remove_css_js_versions_function($src){if(strpos($src, 'ver=')) $src = remove_query_arg('ver', $src); return $src;}
	add_filter('style_loader_src', 'nwpcpcode_remove_css_js_versions_function', 9999);
	add_filter('script_loader_src', 'nwpcpcode_remove_css_js_versions_function', 9999);
}
//Disable self ping
if($nwpcpcode_tab['nwpcpcode_disable_self_ping'] == 'Yes'){
	function nwpcpcode_disable_self_ping_function(&$links){
		$home = get_option('home');
		foreach($links as $l => $link)
			if(0 === strpos($link, $home))
				unset($links[$l]);
	}
}
add_action('pre_ping', 'nwpcpcode_disable_self_ping_function');
//Disable XML RPC
if($nwpcpcode_tab['nwpcpcode_disable_xml_rpc'] == 'Yes'){
	function nwpcpcode_disable_xml_rpc_function($headers){unset($headers['X-Pingback']); return $headers;}
	add_filter('wp_headers', 'nwpcpcode_disable_xml_rpc_function');
	add_filter('xmlrpc_enabled', '__return_false');
}
//Prevent access to unnecessary files
if($nwpcpcode_tab['nwpcpcode_prevent_access_to_files'] == 'Yes'){
	if(!function_exists('insert_with_markers')){require_once ABSPATH.'/wp-admin/includes/misc.php';}
	$filename = $_SERVER['DOCUMENT_ROOT'].'/.htaccess';
	$marker = 'Prevent access to unnecessary files';
	$insertion = 'RewriteRule (?:readme|license|changelog|-sample|xmlrpc|wlwmanifest)\.(?:php|md|txt|html|xml?) - [R=404,NC,L]';
	$result = insert_with_markers($filename, $marker, $insertion);
}
if($nwpcpcode_tab['nwpcpcode_prevent_access_to_files'] == 'No'){
	if(!function_exists('insert_with_markers')){require_once ABSPATH.'/wp-admin/includes/misc.php';}
	$filename = $_SERVER['DOCUMENT_ROOT'].'/.htaccess';
	$marker = 'Prevent access to unnecessary files';
	$insertion = '';
	$result = insert_with_markers($filename, $marker, $insertion);
}
//Add honeypot on login page
if($nwpcpcode_tab['nwpcpcode_add_honeypot'] == 'Yes'){
	function nwpcpcode_add_honeypot_function(){
		echo '<p style="visibility:hidden;height:1px;"><label for="username-login">Name<br><input type="text" name="username-login" value=""/></label></p>';
		if($_POST['username-login']){echo '<meta http-equiv="refresh" content="0; url=https://facebook.com/login/"/>'; die('Success');}
	}
	add_action('login_form','nwpcpcode_add_honeypot_function');
	add_action('lostpassword_form','nwpcpcode_add_honeypot_function');
	add_action('retrievepassword_form','nwpcpcode_add_honeypot_function');
	add_action('register_form','nwpcpcode_add_honeypot_function');
}
//Revisions limit
function nwpcpcode_revisions_limit_function($revisions){
	global $nwpcpcode_tab;
	return $nwpcpcode_tab['nwpcpcode_revisions_limit'];
}
add_filter('wp_revisions_to_keep', 'nwpcpcode_revisions_limit_function');
//Disable Text widget
if($nwpcpcode_tab['nwpcpcode_disable_text_widget'] == 'Yes'){
	function nwpcpcode_disable_text_widget_function(){return unregister_widget('WP_Widget_Text');}
	add_action('widgets_init', 'nwpcpcode_disable_text_widget_function', 11);
}
//Disable Custom Menu widget
if($nwpcpcode_tab['nwpcpcode_disable_custom_menu_widget'] == 'Yes'){
	function nwpcpcode_disable_custom_menu_widget_function(){return unregister_widget('WP_Nav_Menu_Widget');}
	add_action('widgets_init', 'nwpcpcode_disable_custom_menu_widget_function', 11);
}
//Disable Image widget
if($nwpcpcode_tab['nwpcpcode_disable_image_widget'] == 'Yes'){
	function nwpcpcode_disable_image_widget_function(){return unregister_widget('WP_Widget_Media_Image');}
	add_action('widgets_init', 'nwpcpcode_disable_image_widget_function', 11);
}
//Disable Video widget
if($nwpcpcode_tab['nwpcpcode_disable_video_widget'] == 'Yes'){
	function nwpcpcode_disable_video_widget_function(){return unregister_widget('WP_Widget_Media_Video');}
	add_action('widgets_init', 'nwpcpcode_disable_video_widget_function', 11);
}
//Disable Audio widget
if($nwpcpcode_tab['nwpcpcode_disable_audio_widget'] == 'Yes'){
	function nwpcpcode_disable_audio_widget_function(){return unregister_widget('WP_Widget_Media_Audio');}
	add_action('widgets_init', 'nwpcpcode_disable_audio_widget_function', 11);
}
//Disable RSS widget
if($nwpcpcode_tab['nwpcpcode_disable_rss_widget'] == 'Yes'){
	function nwpcpcode_disable_rss_widget_function(){return unregister_widget('WP_Widget_RSS');}
	add_action('widgets_init', 'nwpcpcode_disable_rss_widget_function', 11);
}
//Disable Archives widget
if($nwpcpcode_tab['nwpcpcode_disable_archives_widget'] == 'Yes'){
	function nwpcpcode_disable_archives_widget_function(){return unregister_widget('WP_Widget_Archives');}
	add_action('widgets_init', 'nwpcpcode_disable_archives_widget_function', 11);
}
//Disable Calendar widget
if($nwpcpcode_tab['nwpcpcode_disable_calendar_widget'] == 'Yes'){
	function nwpcpcode_disable_calendar_widget_function(){return unregister_widget('WP_Widget_Calendar');}
	add_action('widgets_init', 'nwpcpcode_disable_calendar_widget_function', 11);
}
//Disable Meta widget
if($nwpcpcode_tab['nwpcpcode_disable_meta_widget'] == 'Yes'){
	function nwpcpcode_disable_meta_widget_function(){return unregister_widget('WP_Widget_Meta');}
	add_action('widgets_init', 'nwpcpcode_disable_meta_widget_function', 11);
}
//Disable Tag Cloud widget
if($nwpcpcode_tab['nwpcpcode_disable_tag_cloud_widget'] == 'Yes'){
	function nwpcpcode_disable_tag_cloud_widget_function(){return unregister_widget('WP_Widget_Tag_Cloud');}
	add_action('widgets_init', 'nwpcpcode_disable_tag_cloud_widget_function', 11);
}
//Disable Search widget
if($nwpcpcode_tab['nwpcpcode_disable_search_widget'] == 'Yes'){
	function nwpcpcode_disable_search_widget_function(){return unregister_widget('WP_Widget_Search');}
	add_action('widgets_init', 'nwpcpcode_disable_search_widget_function', 11);
}
//Disable Categories widget
if($nwpcpcode_tab['nwpcpcode_disable_categories_widget'] == 'Yes'){
	function nwpcpcode_disable_categories_widget_function(){return unregister_widget('WP_Widget_Categories');}
	add_action('widgets_init', 'nwpcpcode_disable_categories_widget_function', 11);
}
//Disable Recent Posts widget
if($nwpcpcode_tab['nwpcpcode_disable_recent_posts_widget'] == 'Yes'){
	function nwpcpcode_disable_recent_posts_widget_function(){return unregister_widget('WP_Widget_Recent_Posts');}
	add_action('widgets_init', 'nwpcpcode_disable_recent_posts_widget_function', 11);
}
//Disable Recent Comments widget
if($nwpcpcode_tab['nwpcpcode_disable_recent_comments_widget'] == 'Yes'){
	function nwpcpcode_disable_recent_comments_widget_function(){return unregister_widget('WP_Widget_Recent_Comments');}
	add_action('widgets_init', 'nwpcpcode_disable_recent_comments_widget_function', 11);
}
//Disable Pages widget
if($nwpcpcode_tab['nwpcpcode_disable_pages_widget'] == 'Yes'){
	function nwpcpcode_disable_pages_widget_function(){return unregister_widget('WP_Widget_Pages');}
	add_action('widgets_init', 'nwpcpcode_disable_pages_widget_function', 11);
}
//Disable categories
if($nwpcpcode_tab['nwpcpcode_unregister_categories'] == 'Yes'){
	function nwpcpcode_unregister_categories_function(){unregister_taxonomy_for_object_type('category', 'post');}
	add_action('init', 'nwpcpcode_unregister_categories_function');
}
//Disable tags
if($nwpcpcode_tab['nwpcpcode_unregister_tags'] == 'Yes'){
	function nwpcpcode_unregister_tags_function(){unregister_taxonomy_for_object_type('post_tag', 'post');}
	add_action('init', 'nwpcpcode_unregister_tags_function');
}
//Hide author
if($nwpcpcode_tab['nwpcpcode_hide_author'] == 'Yes'){
	function nwpcpcode_hide_author_columns($columns){unset($columns['author']); return $columns;}
	foreach(get_post_types('', 'names') as $post_type){add_filter('manage_'.$post_type.'_posts_columns' , 'nwpcpcode_hide_author_columns');}
	add_filter('manage_media_columns', 'nwpcpcode_hide_author_columns');
	function nwpcpcode_hide_author_metabox(){foreach(get_post_types('', 'names') as $post_type){remove_meta_box('authordiv', $post_type, 'normal');}}
	add_action('admin_menu', 'nwpcpcode_hide_author_metabox');
}
//Hide comments
if($nwpcpcode_tab['nwpcpcode_hide_comments'] == 'Yes'){
	function nwpcpcode_hide_comments_columns($columns){unset($columns['comments']); return $columns;}
	foreach(get_post_types('', 'names') as $post_type){add_filter('manage_'.$post_type.'_posts_columns' , 'nwpcpcode_hide_comments_columns');}
	add_filter('manage_media_columns', 'nwpcpcode_hide_comments_columns');
	function nwpcpcode_hide_comments_metabox(){foreach(get_post_types('', 'names') as $post_type){remove_meta_box('commentsdiv', $post_type, 'normal');}}
	add_action('admin_menu', 'nwpcpcode_hide_comments_metabox');
	function nwpcpcode_hide_comments_menu(){remove_menu_page('edit-comments.php');}
	add_action('admin_menu', 'nwpcpcode_hide_comments_menu');
	function nwpcpcode_hide_comments_admin_bar(){global $wp_admin_bar; $wp_admin_bar->remove_menu('comments');}
	add_action('wp_before_admin_bar_render', 'nwpcpcode_hide_comments_admin_bar');
}
//Hide discussion
if($nwpcpcode_tab['nwpcpcode_hide_discussion'] == 'Yes'){
	function nwpcpcode_hide_discussion_metabox(){foreach(get_post_types('', 'names') as $post_type){remove_meta_box('commentstatusdiv', $post_type, 'normal');}}
	add_action('admin_menu', 'nwpcpcode_hide_discussion_metabox');
	function nwpcpcode_hide_discussion_menu(){$page = remove_submenu_page('options-general.php', 'options-discussion.php');}
	add_action('admin_menu', 'nwpcpcode_hide_discussion_menu', 999);
}
//Hide slug
if($nwpcpcode_tab['nwpcpcode_hide_slug'] == 'Yes'){
	function nwpcpcode_hide_slug_metabox(){foreach(get_post_types('', 'names') as $post_type){remove_meta_box('slugdiv', $post_type, 'normal');}}
	add_action('admin_menu', 'nwpcpcode_hide_slug_metabox');
}
//Hide trackbacks
if($nwpcpcode_tab['nwpcpcode_hide_trackbacks'] == 'Yes'){
	function nwpcpcode_hide_trackbacks_metabox(){foreach(get_post_types('', 'names') as $post_type){remove_meta_box('trackbacksdiv', $post_type, 'normal');}}
	add_action('admin_menu', 'nwpcpcode_hide_trackbacks_metabox');
}
//Hide excerpt
if($nwpcpcode_tab['nwpcpcode_hide_excerpt'] == 'Yes'){
	function nwpcpcode_hide_excerpt_metabox(){foreach(get_post_types('', 'names') as $post_type){remove_meta_box('postexcerpt', $post_type, 'normal');}}
	add_action('admin_menu', 'nwpcpcode_hide_excerpt_metabox');
}
//Hide custom fields
if($nwpcpcode_tab['nwpcpcode_hide_custom_fields'] == 'Yes'){
	function nwpcpcode_hide_custom_fields_metabox(){foreach(get_post_types('', 'names') as $post_type){remove_meta_box('postcustom', $post_type, 'normal');}}
	add_action('admin_menu', 'nwpcpcode_hide_custom_fields_metabox');
}
//Hide tools
if($nwpcpcode_tab['nwpcpcode_hide_tools'] == 'Yes'){
	function nwpcpcode_hide_tools_menu(){remove_menu_page('tools.php');}
	add_action('admin_menu', 'nwpcpcode_hide_tools_menu');
}
//Hide writing
if($nwpcpcode_tab['nwpcpcode_hide_writing'] == 'Yes'){
	function nwpcpcode_hide_writing_menu(){$page = remove_submenu_page('options-general.php', 'options-writing.php');}
	add_action('admin_menu', 'nwpcpcode_hide_writing_menu', 999);
}
//Hide reading
if($nwpcpcode_tab['nwpcpcode_hide_reading'] == 'Yes'){
	function nwpcpcode_hide_reading_menu(){$page = remove_submenu_page('options-general.php', 'options-reading.php');}
	add_action('admin_menu', 'nwpcpcode_hide_reading_menu', 999);
}
//Hide media
if($nwpcpcode_tab['nwpcpcode_hide_media'] == 'Yes'){
	function nwpcpcode_hide_media_menu(){$page = remove_submenu_page('options-general.php', 'options-media.php');}
	add_action('admin_menu', 'nwpcpcode_hide_media_menu', 999);
}
//Hide permalinks
if($nwpcpcode_tab['nwpcpcode_hide_permalinks'] == 'Yes'){
	function nwpcpcode_hide_permalinks_menu(){$page = remove_submenu_page('options-general.php', 'options-permalink.php');}
	add_action('admin_menu', 'nwpcpcode_hide_permalinks_menu', 999);
}
//Hide file editors
if($nwpcpcode_tab['nwpcpcode_hide_file_editors'] == 'Yes'){
	function nwpcpcode_hide_file_editors_theme(){$page = remove_submenu_page('themes.php', 'theme-editor.php');}
	add_action('admin_menu', 'nwpcpcode_hide_file_editors_theme', 999);
	function nwpcpcode_hide_file_editors_plugin(){$page = remove_submenu_page('plugins.php', 'plugin-editor.php');}
	add_action('admin_menu', 'nwpcpcode_hide_file_editors_plugin', 999);
}
//Hide admin bar wp logo
if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_logo'] == 'Yes'){
	function nwpcpcode_hide_admin_bar_logo_item(){global $wp_admin_bar; $wp_admin_bar->remove_menu('wp-logo');}
	add_action('wp_before_admin_bar_render', 'nwpcpcode_hide_admin_bar_logo_item');
}
//Hide admin bar customize
if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_customize'] == 'Yes'){
	function nwpcpcode_hide_admin_bar_customize_item(){global $wp_admin_bar; $wp_admin_bar->remove_menu('customize');}
	add_action('wp_before_admin_bar_render', 'nwpcpcode_hide_admin_bar_customize_item');
}
//Hide admin bar new media
if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_new_media'] == 'Yes'){
	function nwpcpcode_hide_admin_bar_new_media_item(){global $wp_admin_bar; $wp_admin_bar->remove_menu('new-media');}
	add_action('wp_before_admin_bar_render', 'nwpcpcode_hide_admin_bar_new_media_item');
}
//Hide admin bar new user
if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_new_user'] == 'Yes'){
	function nwpcpcode_hide_admin_bar_new_user_item(){global $wp_admin_bar; $wp_admin_bar->remove_menu('new-user');}
	add_action('wp_before_admin_bar_render', 'nwpcpcode_hide_admin_bar_new_user_item');
}
//Hide admin bar themes
if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_themes'] == 'Yes'){
	function nwpcpcode_hide_admin_bar_themes_items(){global $wp_admin_bar; $wp_admin_bar->remove_menu('themes');}
	add_action('wp_before_admin_bar_render', 'nwpcpcode_hide_admin_bar_themes_items');
}
//Hide logo login page
if($nwpcpcode_tab['nwpcpcode_hide_logo_login_page'] == 'Yes'){
	function nwpcpcode_hide_logo_login_page_function(){echo '<style>#login h1 a, .login h1 a{display:none;}</style>';}
	add_action('login_enqueue_scripts', 'nwpcpcode_hide_logo_login_page_function');
}
//Admin Page
function nwpcpcode_page(){
global $nwpcpcode_tab;
ob_start();?>
<div class="wrap">
<form action="options.php" method="post">
	<?php settings_fields('nwpcpcodegroup');?>
	<h1><?php echo __('Native WP Cleaner settings', 'native-wp-cleaner');?></h1>
	<ol>
		<li><a href="#HTML-Code-Cleaning"><?php echo __('HTML Code Cleaning', 'native-wp-cleaner');?></a></li>
		<li><a href="#Security-Excessive-Functionality"><?php echo __('Security & Excessive Functionality', 'native-wp-cleaner');?></a></li>
		<li><a href="#Widgets-Cleaning"><?php echo __('Widgets Cleaning', 'native-wp-cleaner');?></a></li>
		<li><a href="#Admin-Panel-Cleaning"><?php echo __('Admin Panel Cleaning', 'native-wp-cleaner');?></a></li>
		<li><a href="#Admin-Bar-Cleaning"><?php echo __('Admin Bar Cleaning', 'native-wp-cleaner');?></a></li>
		<li><a href="#Login-Page-Cleaning"><?php echo __('Login Page Cleaning', 'native-wp-cleaner');?></a></li>
	</ol>
	<table class="form-table">
		<tbody>
			<tr>
				<th id="HTML-Code-Cleaning" colspan="2"><h2><?php echo __('HTML Code Cleaning', 'native-wp-cleaner');?></h2><hr></th>
			</tr>
			<tr>
				<th><?php echo __('Remove RSS Feed link', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_rss_feed_link_remove]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_rss_feed_link_remove'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_rss_feed_link_remove]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_rss_feed_link_remove'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will remove RSS Feed link from your head tag.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Remove RSD link', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_rsd_link_remove]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_rsd_link_remove'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_rsd_link_remove]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_rsd_link_remove'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will remove RSD link from your head tag.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Remove generator meta', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_meta_generator_remove]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_meta_generator_remove'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_meta_generator_remove]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_meta_generator_remove'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will remove generator meta from your head tag.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Remove WLW Manifest link', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_wlwmanifest_link_remove]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_wlwmanifest_link_remove'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_wlwmanifest_link_remove]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_wlwmanifest_link_remove'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will remove WLW Manifest link from your head tag.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Embeds', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_embeds]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_embeds'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_embeds]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_embeds'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will remove wp-embed.min.js script from your head tag and will disable embeds functionality on your site.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable EMOJI', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_emoji_smiles]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_emoji_smiles'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_emoji_smiles]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_emoji_smiles'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable EMOJI smiles on your site.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Remove CSS & JS version in links', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_remove_version_css_js]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_remove_version_css_js'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_remove_version_css_js]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_remove_version_css_js'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will remove CSS & JS version in links.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th id="Security-Excessive-Functionality" colspan="2"><h2><?php echo __('Security & Excessive Functionality', 'native-wp-cleaner');?></h2><hr></th>
			</tr>
			<tr>
				<th><?php echo __('Disable self ping', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_self_ping]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_self_ping'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_self_ping]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_self_ping'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable self ping.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable XML-RPC', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_xml_rpc]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_xml_rpc'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_xml_rpc]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_xml_rpc'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable XML-RPC functionality.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Prevent access to unnecessary files', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_prevent_access_to_files]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_prevent_access_to_files'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_prevent_access_to_files]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_prevent_access_to_files'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will prevent access to such files as: readme.html, license.txt, cahngelog.txt, xmlrpc.php, wp-config-sample.php, wlwmanifest.xml, etc </small><strong>(rule will be added to .htaccess)</strong><small>.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Add Honeypot', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_add_honeypot]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_add_honeypot'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_add_honeypot]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_add_honeypot'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will add hidden field (<a href="https://en.wikipedia.org/wiki/Honeypot_(computing)" target="_blank">Honeypot</a>) to login form.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Revisions limit', 'native-wp-cleaner');?></th>
				<td>
					<input type="number" min="0" max="19" style="width:25%;" placeholder="<?php echo __('Type preferred limit', 'native-wp-cleaner');?>" name="nwpcpcodesettings[nwpcpcode_revisions_limit]" value="<?php echo $nwpcpcode_tab['nwpcpcode_revisions_limit'];?>"/>
					<p><small><?php echo __('How much revisions you want to store in database.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th id="Widgets-Cleaning" colspan="2"><h2><?php echo __('Widgets Cleaning', 'native-wp-cleaner');?></h2><hr></th>
			</tr>
			<tr>
				<th><?php echo __('Disable Text widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_text_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_text_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_text_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_text_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Text widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Custom Menu widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_custom_menu_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_custom_menu_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_custom_menu_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_custom_menu_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Custom Menu widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Image widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_image_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_image_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_image_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_image_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Image widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Video widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_video_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_video_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_video_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_video_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Video widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Audio widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_audio_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_audio_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_audio_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_audio_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Audio widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable RSS widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_rss_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_rss_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_rss_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_rss_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable RSS widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Archives widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_archives_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_archives_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_archives_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_archives_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Archives widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Calendar widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_calendar_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_calendar_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_calendar_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_calendar_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Calendar widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Meta widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_meta_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_meta_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_meta_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_meta_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Meta widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Tag Cloud widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_tag_cloud_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_tag_cloud_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_tag_cloud_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_tag_cloud_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Tag Cloud widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Search widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_search_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_search_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_search_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_search_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Search widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Categories widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_categories_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_categories_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_categories_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_categories_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Categories widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Recent Posts widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_recent_posts_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_recent_posts_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_recent_posts_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_recent_posts_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Recent Posts widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Recent Comments widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_recent_comments_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_recent_comments_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_recent_comments_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_recent_comments_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Recent Comments widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Pages widget', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_pages_widget]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_disable_pages_widget'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_disable_pages_widget]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_disable_pages_widget'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Pages widget.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th id="Admin-Panel-Cleaning" colspan="2"><h2><?php echo __('Admin Panel Cleaning', 'native-wp-cleaner');?></h2><hr></th>
			</tr>
			<tr>
				<th><?php echo __('Disable Categories taxonomy', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_unregister_categories]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_unregister_categories'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_unregister_categories]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_unregister_categories'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Categories taxonomy.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Disable Tags taxonomy', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_unregister_tags]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_unregister_tags'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_unregister_tags]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_unregister_tags'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will disable Tags taxonomy.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Author boxes', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_author]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_author'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_author]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_author'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Author metabox and columns from all post types.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Comments boxes', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_comments]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_comments'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_comments]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_comments'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Comments metabox, columns and menu page.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Discussion boxes', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_discussion]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_discussion'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_discussion]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_discussion'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Discussion metabox and menu sub-page in settings.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Slug metabox', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_slug]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_slug'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_slug]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_slug'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Slug metabox.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Trackbacks metabox', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_trackbacks]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_trackbacks'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_trackbacks]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_trackbacks'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Trackbacks metabox.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Excerpt metabox', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_excerpt]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_excerpt'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_excerpt]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_excerpt'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Excerpt metabox.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Custom Fields metabox', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_custom_fields]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_custom_fields'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_custom_fields]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_custom_fields'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Custom Fields metabox.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Tools', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_tools]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_tools'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_tools]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_tools'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Tools menu page.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Writing', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_writing]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_writing'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_writing]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_writing'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Writing menu sub-page.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Reading', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_reading]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_reading'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_reading]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_reading'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Reading menu sub-page.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Media', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_media]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_media'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_media]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_media'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Media menu sub-page.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Permalinks', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_permalinks]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_permalinks'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_permalinks]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_permalinks'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Permalinks menu sub-page.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide File Editor', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_file_editors]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_file_editors'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_file_editors]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_file_editors'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Theme & Plugin Editor sub-pages.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th id="Admin-Bar-Cleaning" colspan="2"><h2><?php echo __('Admin Bar Cleaning', 'native-wp-cleaner');?></h2><hr></th>
			</tr>
			<tr>
				<th><?php echo __('Hide WP Logo', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_admin_bar_logo]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_logo'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_admin_bar_logo]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_logo'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide WP Logo in admin bar.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Customize', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_admin_bar_customize]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_customize'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_admin_bar_customize]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_customize'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Customize item in admin bar.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide New Media', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_admin_bar_new_media]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_new_media'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_admin_bar_new_media]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_new_media'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide New->Media item in admin bar.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide New User', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_admin_bar_new_user]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_new_user'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_admin_bar_new_user]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_new_user'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide New->User item in admin bar.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Hide Themes', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_admin_bar_themes]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_themes'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_admin_bar_themes]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_admin_bar_themes'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide Themes item in admin bar.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
			<tr>
				<th id="Login-Page-Cleaning" colspan="2"><h2><?php echo __('Login Page Cleaning', 'native-wp-cleaner');?></h2><hr></th>
			</tr>
			<tr>
				<th><?php echo __('Hide Logo', 'native-wp-cleaner');?>?</th>
				<td>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_logo_login_page]" value="Yes" <?php if($nwpcpcode_tab['nwpcpcode_hide_logo_login_page'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-cleaner');?></label><br>
					<label><input type="radio" name="nwpcpcodesettings[nwpcpcode_hide_logo_login_page]" value="No" <?php if($nwpcpcode_tab['nwpcpcode_hide_logo_login_page'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-cleaner');?></label>
					<p><small><?php echo __('This will hide WP Logo on the login page.', 'native-wp-cleaner');?></small></p>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save changes', 'native-wp-cleaner');?>"/></p>
</form>
</div>
<?php
echo ob_get_clean();
}
//Admin Menu
function nwpcpcode_tab(){
add_options_page(__('Native WP Cleaner settings', 'native-wp-cleaner'),__('Native WP Cleaner', 'native-wp-cleaner'),'manage_options','native-wp-cleaner','nwpcpcode_page');
}
add_action('admin_menu','nwpcpcode_tab');
//Register Settings
function nwpcpcode_settings(){
register_setting('nwpcpcodegroup','nwpcpcodesettings');
}
add_action('admin_init','nwpcpcode_settings');
?>