<?php 
/*
 Plugin Name: TS Search Slug
 Plugin URI: https://www.spiess-informatik.de/wordpress-plugins/
 License: GPLv3 (license.txt)
 Description: Search for Slug in Admin Post/Page Overview and add Slug Column to Post/Page Overview
 Author: Tobias Spiess
 Author URI: https://www.spiess-informatik.de
 Version: 1.0.3
 Text-Domain: tsinf_search_plugin_textdomain
 Domain Path: /languages
*/

/**
 * Load Plugin Translations
 */
function tsinf_search_slug_load_plugin_textdomain() {
	load_plugin_textdomain( 'tsinf_search_plugin_textdomain', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'tsinf_search_slug_load_plugin_textdomain' );

if(!defined('TS_Search_Slug'))
{
	class TS_Search_Slug
	{
		function __construct()
		{
			add_action('admin_menu', array('TS_Search_Slug', 'add_options_page'));
			add_action('admin_init', array('TS_Search_Slug', 'establish_options_page'));
			
			add_filter('manage_post_posts_columns', array('TS_Search_Slug', 'append_admin_columns'));
			add_action('manage_post_posts_custom_column',  array('TS_Search_Slug', 'append_admin_columns_data'), 10, 2);
			add_filter('manage_edit-post_sortable_columns', array('TS_Search_Slug', 'make_custom_columns_sortable'));
			
			add_filter('manage_page_posts_columns', array('TS_Search_Slug', 'append_admin_columns'));
			add_action('manage_page_posts_custom_column',  array('TS_Search_Slug', 'append_admin_columns_data'), 10, 2);
			add_filter('manage_edit-page_sortable_columns', array('TS_Search_Slug', 'make_custom_columns_sortable'));
			
			$all_options = get_option('ts_search_slug_settings');
			$activated_cpts = array();
			if(isset($all_options['post_types']))
			{
				$activated_cpts = $all_options['post_types'];
			}
			
			if(is_array($activated_cpts) && count($activated_cpts) > 0)
			{
				foreach($activated_cpts as $post_type)
				{
					add_filter('manage_' . $post_type . '_posts_columns', array('TS_Search_Slug', 'append_admin_columns'));
					add_action('manage_' . $post_type . '_posts_custom_column',  array('TS_Search_Slug', 'append_admin_columns_data'), 10, 2);
					add_filter('manage_edit-' . $post_type . '_sortable_columns', array('TS_Search_Slug', 'make_custom_columns_sortable'));
				}
			}
			
			add_filter('posts_where' , array('TS_Search_Slug', 'posts_where'), 10, 2);
		}
		
		public static function add_options_page() {
			add_submenu_page(
				'options-general.php',
				'TS Search Slug',
				'TS Search Slug',
				'manage_options',
				'ts_search_slug',
				array('TS_Search_Slug', 'render_main_page')
			);
		}
		
		public static function render_main_page() {
			if ( !current_user_can( 'edit_posts' ) )  {
				wp_die( __('You do not have sufficient permissions to access this page.', 'tsinf_customstyle_plugin_textdomain'));
			}
				
			?>
			<h1><?php _e('TS Search Slug', 'tsinf_search_plugin_textdomain'); ?></h1>
			
			<form method="POST" action="options.php">
			<?php 
			settings_fields('ts_search_slug');
			do_settings_sections('ts_search_slug');
			submit_button();
			?>
			</form>
			<?php
		}
		
		public static function establish_options_page()
		{
			add_settings_section(
					'ts_search_slug_plugin_main_section',
					__('Allgemein', 'tsinf_search_plugin_textdomain'),
					array('TS_Search_Slug', 'render_options_page_main_section'),
					'ts_search_slug'
					);
				
			add_settings_field(
					'ts_search_slug_post_types',
					__('Enable Post Slug Functionality for Custom Post Types', 'tsinf_search_plugin_textdomain'),
					array('TS_Search_Slug', 'render_options_page_field_post_types'),
					'ts_search_slug',
					'ts_search_slug_plugin_main_section'
				);
			
			register_setting('ts_search_slug', 'ts_search_slug_settings', 'post_types');
		}
		
		public static function render_options_page_main_section()
		{
			
		}
		
		public static function render_options_page_field_post_types()
		{
			$args = array(
				'public'   => true,
				'_builtin' => false,
				'show_ui' => true
			);
			$post_types = get_post_types($args, 'object');
			if(is_array($post_types) && count($post_types) > 0)
			{
				?>
				<div class="activate_features_for_cpt_wrap">
				<?php
				$all_options = get_option('ts_search_slug_settings');
				$activated_cpts = array();
				if(is_array($all_options) && isset($all_options['post_types']))
				{
					$activated_cpts = $all_options['post_types'];
				}
				
				foreach($post_types as $post_type)
				{
					$checked = "";
					if(in_array($post_type->name, $activated_cpts))
					{
						$checked = " checked='checked' ";
					}
			?>
				<div class="tsinf_search_slug_line">
					<input type="checkbox" name="ts_search_slug_settings[post_types][]" <?php echo $checked; ?> value="<?php echo $post_type->name; ?>" />
					<span class="label"><?php echo $post_type->label; ?> (<?php echo $post_type->name; ?>)</span>
				</div>
			<?php
				}
				?>
				</div>
				<?php 
			}
		}
		
		/**
		 * Append Admin Columns to WordPress Backend Posts Overview
		 * @param array $columns
		 * @return array
		 */
		public static function append_admin_columns($columns) {
			$columns['ts_search_slug_post_name'] = __('Postname/Slug', 'tsinf_search_plugin_textdomain');
		
			return $columns;
		}
		
		/**
		 * Fill additional Admin columns in WordPress Backend Posts Overview
		 * @param string $column
		 * @param int $post_id
		 */
		public static function append_admin_columns_data($column, $post_id) {
			
			switch ( $column ) {
		
				case 'ts_search_slug_post_name':
					echo basename(get_permalink());
					break;
		
				
		
			}
		}
		
		/**
		 * Make additional course status column in WordPress Backend Posts Overview sortable
		 * @param array $columns
		 * @return array
		 */
		public static function make_custom_columns_sortable($columns) {
			$columns['ts_search_slug_post_name'] = 'post_name';
		
			return $columns;
		}
		
		/**
		 * Modify Admin Post Overview WHERE-Part of SQL
		 * @param string $where
		 * @return string
		 */
		public static function posts_where($where, $wp_query) {
			global $pagenow;
			
			$post_type = '';
			if(isset($_GET['post_type']))
			{
				$post_type = htmlspecialchars(strip_tags($_GET['post_type']));
			}
			
			$all_options = get_option('ts_search_slug_settings');
			$activated_cpts = array();
			if(is_array($all_options) && isset($all_options['post_types']))
			{
				$activated_cpts = $all_options['post_types'];
			}
			
			if(is_admin() && 'edit.php' === $pagenow && isset($_GET['s']) && is_string($_GET['s']) && strlen($_GET['s']) && ($post_type === 'post' || $post_type === 'page' || in_array($post_type, $activated_cpts)))
			{
				global $wpdb;
				
				$like = '%' . $wpdb->esc_like($_GET['s']) . '%';
				$like_term = $wpdb->prepare("({$wpdb->posts}.post_name LIKE %s)", $like);
				
				$like_search_pattern = $wpdb->prepare("({$wpdb->posts}.post_title LIKE %s)", $like);
				$like_search_replace = " " . $like_search_pattern . " OR " . $like_term . " ";
				
				$where = str_replace($like_search_pattern, $like_search_replace, $where);
			}
			
			return $where;
		}
	}
	
	
	new TS_Search_Slug();
}
?>