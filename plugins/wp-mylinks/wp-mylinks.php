<?php

/**
 *
 * @link              https://walterpinem.me/
 * @since             1.0.0
 * @package           Wp_Mylinks
 * @copyright  		  Copyright (c) 2020, Walter Pinem, Seni Berpikir
 * 
 * @wordpress-plugin
 * Plugin Name:       WP MyLinks
 * Plugin URI:        https://walterpinem.me/projects/introducing-wp-mylinks/
 * Description:       Easily build your own micro landing page showing all the links you want to share to engage your audience. Use your own brand, link it anywhere.
 * Version:           1.0.6
 * Author:            Walter Pinem
 * Author URI:        https://walterpinem.me/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-mylinks
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 */
define('WP_MYLINKS_VERSION', '1.0.6');
define('WP_MYLINKS_PREFIX', 'mylinks_');
function mylinks_prefix($key)
{
	return 'mylinks_' . $key;
}
// Define the mylinks_collection function
function mylinks_collection($string)
{
	return 'mylinks_collection_' . $string;
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-mylinks-activator.php
 * Commented since version 1.0.5
 */
// function activate_wp_mylinks() {
// 	require_once plugin_dir_path(__FILE__) . 'includes/class-wp-mylinks-activator.php';
//     if ( ! get_option( 'wp_mylinks_flush_rewrite_rules_flag' ) ) {
//         add_option( 'wp_mylinks_flush_rewrite_rules_flag', true );
//     }
// 	Wp_Mylinks_Activator::activate();
// }
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-mylinks-deactivator.php
 */
function deactivate_wp_mylinks()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-wp-mylinks-deactivator.php';
	Wp_Mylinks_Deactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_wp_mylinks');
/**
 * The core plugin classes that are used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wp-mylinks.php';
require plugin_dir_path(__FILE__) . 'admin/partials/wp-mylinks-admin-settings.php';
require plugin_dir_path(__FILE__) . 'includes/class-wp-mylinks-post-type.php';
require plugin_dir_path(__FILE__) . 'admin/partials/wp-mylinks-links-collection.php';

/* Load base template for the landing page */
add_filter('single_template', 'wp_mylinks_template');
function wp_mylinks_template($single)
{
	global $post;
	/* Checks for single template by post type */
	if ($post->post_type == 'mylink') {
		if (file_exists(plugin_dir_path(__FILE__) . 'public/partials/wp-mylinks-base-template.php')) {
			return plugin_dir_path(__FILE__) . 'public/partials/wp-mylinks-base-template.php';
		}
	}
	return $single;
}
/**
 * Register the main public CSS
 */
function wp_mylinks_register_style()
{
	wp_register_style('mylinks-public-css', plugin_dir_url(__FILE__) . 'public/css/wp-mylinks-public.min.css');
	wp_register_style('mylinks-youtube-css', plugin_dir_url(__FILE__) . 'public/css/wp-mylinks-youtube.min.css');
}
add_action('init', 'wp_mylinks_register_style');
/**
 * Register the main public JS
 */
function wp_mylinks_register_script()
{
	wp_register_script('mylinks-public-js', plugin_dir_url(__FILE__) . 'public/js/wp-mylinks-public.js');
}
add_action('init', 'wp_mylinks_register_script');
/**
 * Including the CMB2 init.php file
 */
if (file_exists(plugin_dir_path(__FILE__) . 'includes/cmb2/init.php')) {
	require_once plugin_dir_path(__FILE__) . 'includes/cmb2/init.php';
}
/**
 * Get mylink
 *
 */
function wp_mylinks_is_queried()
{
	return ('mylink' === get_post_type());
}
function wp_mylinks_collection_is_queried()
{
	return ('mylinks-collection' === get_post_type());
}
/**
 * Check if Yoast SEO / Premium is active
 *
 */
function wp_mylinks_isYoastActive()
{
	$active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
	foreach ($active_plugins as $plugin) {
		if (strpos($plugin, 'wp-seo')) {
			return true;
		}
	}
	return false;
}
/**
 * Track Visited MyLinks Page(s)
 *
 */
if (!function_exists('wp_mylinks_track_mylink_page')) :
	/**
	 * Get the view counts
	 */
	function wp_mylinks_track_mylink_page($postID)
	{
		$count_key = 'wp_mylinks_count_visits';
		$count = get_post_meta($postID, $count_key, true);
		if ($count == '') {
			$count = 1;
			delete_post_meta($postID, $count_key);
			add_post_meta($postID, $count_key, '1');
		} else {
			$count++;
			update_post_meta($postID, $count_key, $count);
		}
	}
endif;
/**
 * Add setting link plugin page
 *
 */
function wp_mylinks_settings_link($links_array, $plugin_file_name)
{
	if (strpos($plugin_file_name, basename(__FILE__))) {
		array_unshift($links_array, '<a href="edit.php?post_type=mylink&page=welcome">Settings</a>');
	}
	return $links_array;
}
add_filter('plugin_action_links', 'wp_mylinks_settings_link', 10, 2);
/**
 * Callback function for theme options meta box
 *
 */
function wp_mylinks_theme_callback()
{
	// return a standard options array
	return array(
		'none'     		=> __('None', 'wp-mylinks'),
		'default'      	=> __('Default', 'wp-mylinks'),
		'merbabu'		=> __('Merbabu', 'wp-mylinks'),
		'cikuray' 		=> __('Cikuray', 'wp-mylinks'),
		'ciremai' 		=> __('Ciremai', 'wp-mylinks'),
		'slamet'  		=> __('Slamet', 'wp-mylinks'),
		'papandayan' 	=> __('Papandayan', 'wp-mylinks'),
		'sindoro' 		=> __('Sindoro', 'wp-mylinks'),
		'krakatau' 		=> __('Krakatau', 'wp-mylinks'),
		'bromo' 		=> __('Bromo', 'wp-mylinks'),
		'prau' 			=> __('Prau', 'wp-mylinks'),
		'polos' 		=> __('Polos', 'wp-mylinks'),
		'datar' 		=> __('Datar', 'wp-mylinks'),
		'pastel' 		=> __('Pastel', 'wp-mylinks'),
		'kopi-hitam' 	=> __('Kopi Hitam', 'wp-mylinks'),
		'kopi-susu' 	=> __('Kopi Susu', 'wp-mylinks'),
		'klepon'        => __('Klepon Viral', 'wp-mylinks'),
	);
}
/**
 * Callback function for CMB2 sanization
 *
 * @since 1.0.2
 */
function wp_mylinks_sanitization_func($original_value, $args, $cmb2_field)
{
	return $original_value; // Unsanitized value.
}
/**
 * Enable MyLinks to be the front page
 *
 * @since 1.0.1
 */
add_action('admin_head-options-reading.php', 'wp_mylinks_front_page_dropdown');
add_action('pre_get_posts', 'wp_mylinks_show_front_page');
function wp_mylinks_front_page_dropdown()
{
	add_filter('get_pages', 'wp_mylinks_enable_front_page');
}
function wp_mylinks_enable_front_page($r)
{
	$args = array(
		'post_type' => 'mylink'
	);
	$stacks = get_posts($args);
	$r = array_merge($r, $stacks);
	return $r;
}
/**
 * Show MyLinks to be the front page
 *
 */
function wp_mylinks_show_front_page($query)
{
	global $wp_query;
	if ('' == isset($query->query_vars['post_type']) && 0 != $query->query_vars['page_id'])
		$query->query_vars['post_type'] = array('page', 'mylink');
}
add_action('pre_get_posts', 'wp_mylinks_show_front_page');
/**
 * Use dedicated MyLinks template on front page
 *
 */
add_filter('template_include', 'wp_mylinks_front_page_template', 1);
function wp_mylinks_front_page_template($template_path)
{
	if (get_post_type() == 'mylink') {
		if (is_front_page()) {
			// checks if the file exists in the theme first,
			// otherwise serve the file from the plugin
			if ($single_template = plugin_dir_path(__FILE__) . 'public/partials/wp-mylinks-base-template.php') {
				$template_path = $single_template;
			}
		}
	}
	return $template_path;
}
/**
 * Searchable MyLink Links Collections
 * 
 * @since 1.0.6
 * 
 */
function iweb_get_cmb2_post_options($field)
{
	$args = wp_parse_args($field->args['wp_query_args'], array(
		'post_type'   => array('page', 'post', 'mylinks-collection'),
		'post_status' => 'publish',
		'posts_per_page' => 10,
		'orderby' => 'title',
		'order' => 'ASC',
		'fields' => 'ids',
	));
	$posts = new WP_Query($args);
	$post_options = array();
	if ($posts->have_posts()) {
		$posts_with_hierarchy = iweb_build_post_tree($posts->posts);
		foreach ($posts_with_hierarchy as $post_data) {
			$post_id = $post_data['ID'];
			$post_title = $post_data['post_title'];
			$post_type = get_post_type($post_id);
			$post_url = '';
			if ($post_type === 'mylinks-collection') {
				$post_url = get_post_meta($post_id, mylinks_collection('link_collection'), true);
			} else {
				$post_url = get_permalink($post_id);
			}
			$post_options[$post_id] = $post_title . ' - ' . $post_url;
		}
	}
	return $post_options;
}

function iweb_modify_select_url_options($args, $field, $object_type, $object_id)
{
	if ($field->args['id'] === 'select_url') {
		foreach ($field->args['options'] as $option_value => &$option_args) {
			if (preg_match('/ - (?<url>.+)/', $option_args, $matches)) {
				$field->options[$option_value]['attributes']['data-url'] = $matches['url'];
			}
		}
	}
}

function iweb_build_post_tree($post_ids, $parent_id = 0, $level = 0)
{
	$branch = array();
	foreach ($post_ids as $post_id) {
		$post_parent = wp_get_post_parent_id($post_id);
		if ($parent_id == $post_parent) {
			$post_title = get_the_title($post_id);
			$post_title = str_repeat('&mdash; ', $level) . $post_title;
			$branch[] = array(
				'ID' => $post_id,
				'post_title' => $post_title
			);
			$children = iweb_build_post_tree($post_ids, $post_id, $level + 1);
			if (!empty($children)) {
				$branch = array_merge($branch, $children);
			}
		}
	}
	return $branch;
}

function iweb_custom_pw_select_render_row($field_output, $field_args, $field)
{
	$field_output .= '<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#' . $field_args['_id'] . '").on("change", function() {
				var selectedOption = $(this).find(":selected");
				var dataUrl = selectedOption.data("url");
				if (dataUrl) {
					$(this).siblings(".cmb2-metabox-description").find("a").attr("href", dataUrl);
				}
			});
		});
	</script>';

	return $field_output;
}
add_filter('cmb2_render_row_cb', 'iweb_custom_pw_select_render_row', 10, 3);

/**
 * Function to get all of the social media data
 * 
 * @since 1.0.6
 * 
 */
function wp_mylinks_get_social_meta($platform)
{
	$url = get_post_meta(get_the_ID(), mylinks_prefix("{$platform}-url"), true);
	$icon = get_post_meta(get_the_ID(), mylinks_prefix("{$platform}-icon"), true);
	return [$url, $icon];
}

/**
 * Function to dequeue other plugins' scripts and styles
 * from being injected into the MyLink page
 * 
 * @since 1.0.6
 * 
 */
function wp_mylinks_dequeue_others()
{
	global $post;
	// Check if it's the targeted MyLink post type and if it's a single post view
	if (is_singular('mylink') && isset($post->post_type) && 'mylink' == $post->post_type) {
		// Dequeue all scripts except from wp-mylinks
		global $wp_scripts;
		foreach ($wp_scripts->queue as $handle) {
			$script_src = $wp_scripts->registered[$handle]->src;
			if (strpos($script_src, '/wp-mylinks/') === false) {
				wp_dequeue_script($handle);
			}
		}
		// Dequeue all styles except from wp-mylinks
		global $wp_styles;
		foreach ($wp_styles->queue as $handle) {
			$style_src = $wp_styles->registered[$handle]->src;
			if (strpos($style_src, '/wp-mylinks/') === false) {
				wp_dequeue_style($handle);
			}
		}
	}
}
$dequeue = get_option('wp_mylinks_dequeue');
if ($dequeue === "yes") {
	add_action('wp_print_scripts', 'wp_mylinks_dequeue_others', 100);
	add_action('wp_print_styles', 'wp_mylinks_dequeue_others', 100);
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_mylinks()
{
	$plugin = new Wp_Mylinks();
	$plugin->run();
}
run_wp_mylinks();
