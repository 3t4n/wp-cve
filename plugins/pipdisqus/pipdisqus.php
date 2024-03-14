<?php
/*
Plugin Name: pipDisqus
Plugin URI: https://www.pipdig.co/
Description: Remove the default WordPress comments features and replace with Disqus.
Author: pipdig
Author URI: https://www.pipdig.co/
Version: 1.6
Text Domain: pipdisqus
*/

if (!defined('ABSPATH')) die;

function pipdisqus_admin_notice() {
	$options = get_option('pipdisqus_settings');
	if (!current_user_can('manage_options') || !empty($options['disqus_shortname'])) {
		return;
	}
	?>
	<div class="notice notice-warning is-dismissible">
		<h2>Howdy!</h2>
		<p>The pipDisqus plugin is active, but you have not yet setup your Disqus <a href="https://help.disqus.com/customer/portal/articles/466208" rel="noopener" target="_blank">Shortname</a>.</p>
		<p>You can do that on <a href="<?php echo admin_url('options-general.php?page=pipdisqus'); ?>">this page</a>.</p>
	</div>
	<?php
}
add_action('admin_notices', 'pipdisqus_admin_notice');


// replace comments section
function pipdisqus_comments_template() {
	$options = get_option('pipdisqus_settings');
	if (empty($options['disqus_shortname'])) {
		return;
	}
	global $post;
	if ( !(is_singular() && (have_comments() || 'open' == $post->comment_status)) ) {
		return;
	}
	return dirname(__FILE__).'/comments_template.php';
}
add_filter('comments_template', 'pipdisqus_comments_template');


// add count script to footer
function pipdisqus_count_script() {
	
	// add the following to your theme's functions/php file to disable the comment count script - add_filter('pipdisqus_show_counter', '__return_false');
	$show_counter = apply_filters('pipdisqus_show_counter', true);
	if (!$show_counter) {
		return;
	}
	
	if (get_theme_mod('hide_comments_link')) {
		return;
	}
	
	$options = get_option('pipdisqus_settings');
	if (empty($options['disqus_shortname'])) {
		return;
	}
	
	$disqus_count = 'https://'.sanitize_text_field($options['disqus_shortname']).'.disqus.com/count.js';
	?>
	<script id="dsq-count-scr" src="<?php echo $disqus_count; ?>" async defer></script>
	<?php
}
add_action('wp_footer', 'pipdisqus_count_script', 999999);


// remove rss link to default WP comments feed
add_filter('feed_links_show_comments_feed', '__return_false');



// Remove default comments link from Adminbar
function pipdisqus_remove_comments_adminbar() {
	$options = get_option('pipdisqus_settings');
	if (empty($options['disqus_shortname'])) {
		return;
	}
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'pipdisqus_remove_comments_adminbar');



// Remove comments link from main menu
function pipdisqus_remove_comments_menu(){
	$options = get_option('pipdisqus_settings');
	if (empty($options['disqus_shortname'])) {
		return;
	}
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'pipdisqus_remove_comments_menu');


// Add link to Disqus in Adminbar
function pipdisqus_adminbar($admin_bar){
	
	$options = get_option('pipdisqus_settings');
	if (!current_user_can('moderate_comments') || empty($options['disqus_shortname'])) {
		return;
	}
	
	$disqus_url = 'https://'.sanitize_text_field($options['disqus_shortname']).'.disqus.com/admin/moderate/';
	
	$admin_bar->add_menu( array(
		'id'    => 'pipdig-mod-comments',
		'title' => __('Moderate Disqus Comments', 'pipdisqus'),	
		'href'  => $disqus_url,
		'meta'  => array(
			'title' => __('Moderate Comments on Disqus', 'pipdisqus'),	
			'target' => '_blank',			
		),
	));
}
add_action('admin_bar_menu', 'pipdisqus_adminbar', 100);



// Admin Settings page
function pipdisqus_add_admin_menu() { 
	add_options_page('pipdisqus', 'pipDisqus', 'edit_posts', 'pipdisqus', 'pipdisqus_options_page');
}
add_action('admin_menu', 'pipdisqus_add_admin_menu');


function pipdisqus_settings_init() { 

	register_setting('pipdisqus_pluginPage', 'pipdisqus_settings');

	add_settings_section(
		'pipdisqus_pluginPage_section', 
		'', //section description 
		'pipdisqus_settings_section_callback', 
		'pipdisqus_pluginPage'
	);

	add_settings_field( 
		'disqus_shortname', 
		__('Disqus Shortname', 'pipdisqus').' (<a href="https://help.disqus.com/customer/portal/articles/466208" rel="noopener" target="_blank">?</a>)', 
		'disqus_shortname_render', 
		'pipdisqus_pluginPage', 
		'pipdisqus_pluginPage_section' 
	);

}
add_action('admin_init', 'pipdisqus_settings_init');


function disqus_shortname_render() { 
	$options = get_option('pipdisqus_settings');
	$disqus_shortname = '';
	if (isset($options['disqus_shortname'])) {
		$disqus_shortname = sanitize_text_field($options['disqus_shortname']);
	}
	?>
	<input type="text" name="pipdisqus_settings[disqus_shortname]" value="<?php echo $disqus_shortname; ?>">
	<?php
}


function pipdisqus_settings_section_callback() {
	?>
	<style>.wp-core-ui .notice.is-dismissible{display:none}</style>
	<?php
}


function pipdisqus_options_page() { 
	if (!current_user_can('manage_options')) {
		wp_die();
	}
	?>
	<div class="wrap">
	
	<form action='options.php' method='post'>
		
		<h1>Disqus Settings</h1>
		
		<div class="card">
		<p>To use Disqus comments on your blog posts, enter your Disqus <a href="https://help.disqus.com/customer/portal/articles/466208" target="_blank">Shortname</a> below.</p>
		<?php
			settings_fields('pipdisqus_pluginPage');
			do_settings_sections('pipdisqus_pluginPage');
			submit_button();
		?>
		<h3>Already have some WordPress comments?</h3>
		<p>This plugin does not import any old WordPress comments. To do that, please see <a href="https://help.disqus.com/customer/portal/articles/466255-importing-comments-from-wordpress#manual" target="_blank">this guide</a>.</p>
		</div>
		
	</form>

	</div>
	<?php
}
