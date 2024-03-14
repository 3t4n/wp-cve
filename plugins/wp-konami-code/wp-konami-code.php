<?php
/**
 * Plugin Name: WP Konami Code
 * Plugin URI: http://wordpress.org/plugins/wp-konami-code/
 * Description: Add Konami Code to WordPress website and display a special page on Konami Code execution. Availble on desktop and mobile.
 * Author: Yslo
 * Text Domain: wp-konami-code
 * Domain Path: /languages
 * Version: 1.0.2
 * Author URI: http://profiles.wordpress.org/yslo/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class WP_Konami_Code
{
	// Define version
	const VERSION = '1.0.2';

	var $wp_konami_code_options;

	function __construct()
	{
		$this->wp_konami_code_options = get_option('wp_konami_code_options');

		// Default install settings
		register_activation_hook(__FILE__, array(&$this, 'wp_konami_code_install'));

		// Languages
		load_plugin_textdomain('wp-konami-code', false, 'wp-konami-code/languages');

		add_action('init', array(&$this, 'wp_konami_code_init_action'));
		add_action('admin_init', array( &$this, 'wp_konami_code_settings_admin_init'));
	}

	function wp_konami_code_install() {
		if($this->wp_konami_code_options === false) {
			$wp_konami_code_options = array(
				'visibility_search_engine' => 1,
				'visibility_internal_search_engine' => 1,
				'version' => self::VERSION
			);

			update_option('wp_konami_code_options', $wp_konami_code_options);
		}
	}

	function wp_konami_code_init_action() {
		$options = $this->wp_konami_code_options;

		if (!is_admin()) {
			if ($options['activated'] == 1) {
				wp_register_script('konami-js',  plugins_url('/js/konami/konami.min.js', __FILE__) , array('jquery'), '1.4.2', false );
				wp_register_script('wp-konami-code', plugins_url( '/js/wp-konami-code.js', __FILE__), array('konami-js'), '1.0.0', false );

				$main_js_wp_konamy_code = $this->get_konami_url();

				wp_localize_script('wp-konami-code', 'wp_konami_code_js_local', $main_js_wp_konamy_code);

				wp_enqueue_script('konami-js');
				wp_enqueue_script('wp-konami-code');
			}

			if ($options['visibility_search_engine'] == 1 && $options['url_redirect_activated'] != 1) {
				add_action('wp_head', array(&$this, 'visibility_search_engine'));
			}

			if ($options['visibility_internal_search_engine'] == 1 && $options['url_redirect_activated'] != 1) {
				add_filter('pre_get_posts', array(&$this, 'internal_search_filter'));
			}
		}
		else {
			add_action('admin_menu', array(&$this, 'wp_konami_code_admin_menu'));
			add_filter('plugin_action_links', array(&$this, 'add_action_link'), 10, 2);
		}
	}

	function visibility_search_engine() {
		$options = $this->wp_konami_code_options;

		if (is_page($options['page'])) {
			echo '<meta name="robots" content="noindex" />';
		}
	}

	function internal_search_filter($query) {
		$options = $this->wp_konami_code_options;

		if ($query->is_search) {
			$exclude_id = $options['page'];
			$query->set('post__not_in', array($exclude_id));
		}

		return $query;
	}

	function get_konami_url() {
		$konami_url = '';
		$options = $this->wp_konami_code_options;

		if ($options['url_redirect_activated'] == 1) {
			$konami_url = array('wp_konami_code_page' => $options['url_redirect']);
		}
		else {
			$konami_url = array('wp_konami_code_page' => get_permalink($options['page']));
		}

		return $konami_url;
	}

	function wp_konami_code_admin_menu() {
		add_options_page(
			__('Konami Code'),
			__('Konami Code'),
			'edit_theme_options',
			__FILE__,
			array( &$this, 'wp_konami_code_settings_page' )
		);
	}

	function add_action_link($links, $file)
	{
		static $this_plugin;

		if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

		if ($file == $this_plugin){
			$settings_link = '<a href="options-general.php?page=' . $this_plugin . '">' . __('Settings', 'wp-konami-code') . '</a>';
			array_unshift($links, $settings_link);
		}

		return $links;
	}

	function wp_konami_code_settings_page() {
		wp_enqueue_style('wp-konami-code-settings', plugins_url( 'css/style.css', __FILE__ ), array(), self::VERSION);
		?>
		<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e('Konami Code', 'wp-konami-code'); ?></h2>
		<form action="options.php" method="post">
		<?php settings_fields('wp_konami_code_options'); ?>
		<?php do_settings_sections('wp_konami_code_options_sections'); ?>
		<?php submit_button(); ?>
		</form>
		</div>

		<?php
	}

	function wp_konami_code_settings_admin_init() {
		register_setting('wp_konami_code_options', 'wp_konami_code_options', array(&$this, 'wp_konami_code_options'));

		add_settings_section(
			'wp_konami_code_options',
			__('History', 'wp-konami-code'),
			array(&$this, 'wp_konami_code_section_text'),
			'wp_konami_code_options_sections'
		);
		add_settings_field(
			'wp_konami_code_activated',
			__('Activate Konami Code (check to enable)', 'wp-konami-code'),
			array(&$this, 'wp_konami_code_activated_input'),
			'wp_konami_code_options_sections', 'wp_konami_code_options'
		);
		add_settings_field(
			'wp_konami_code_page_redirect',
			__('<a href="edit.php?post_type=page" target="_blank">Page</a> to display after running the Konami Code', 'wp-konami-code'),
			array(&$this, 'wp_konami_code_page_redirect_input'),
			'wp_konami_code_options_sections', 'wp_konami_code_options'
		);
		add_settings_field(
			'wp_konami_visibility_search_engine',
			__('Search Engine Visibility', 'wp-konami-code'),
			array(&$this, 'wp_konami_code_visibility_search_engine_input'),
			'wp_konami_code_options_sections', 'wp_konami_code_options'
		);

		add_settings_field(
			'wp_konami_visibility_internal_search_engine',
			__('Internal Search Engine Visibility', 'wp-konami-code'),
			array(&$this, 'wp_konami_code_visibility_internal_search_engine_input'),
			'wp_konami_code_options_sections', 'wp_konami_code_options'
		);
		add_settings_field(
			'wp_konami_code_url_redirect_activated',
			__('Use URL to display after running the Konami Code instead of Page (check to enable)', 'wp-konami-code'),
			array(&$this, 'wp_konami_code_url_redirect_activated_input'),
			'wp_konami_code_options_sections', 'wp_konami_code_options'
		);
		add_settings_field(
			'wp_konami_code_url_redirect',
			__('Target URL', 'wp-konami-code'),
			array(&$this, 'wp_konami_code_url_redirect_input'),
			'wp_konami_code_options_sections', 'wp_konami_code_options'
		);
	}

	function wp_konami_code_section_text() {
		echo '<p>' . __('The Konami Code is a cheat code that appears in many Konami video games, although the code also appears in some non-Konami games.', 'wp-konami-code') . '</p>';
		echo '<p>' . __('During the title screen before the game demo begins, the player could press the following sequence of buttons on the game controller to enable the cheat:', 'wp-konami-code') . '</p>';
		echo '<img src="'.plugins_url( 'images/konami-code.png', __FILE__ ).'">';
		echo '<p>' . __('Mobile variation : Up, Up, Down, Down, Left, Right, Left, Right, Tap, Tap', 'wp-konami-code') . '</p>';
		echo '<p>' . __('The code has also found a place in popular culture as a reference to the third generation of video game consoles. The code is also present as an Easter egg on a number of websites (yours now, :-).', 'wp-konami-code') . '</p>';
		echo '<p>' . __('<a href="https://en.wikipedia.org/wiki/Konami_Code" target="_blank">See more on Wikipedia</a>', 'wp-konami-code') . '</p>';
	}

	function wp_konami_code_activated_input() {
		$options = $this->wp_konami_code_options;
		$option_value = isset($options['activated']) ? $options['activated'] : 0;
		echo '<input type="checkbox" name="wp_konami_code_options[activated]" value="1" '.checked( $option_value, 1, false ).' />';
	}

	function wp_konami_code_page_redirect_input() {
		$options = $this->wp_konami_code_options;
		$option_value = isset($options['page']) ? $options['page'] : 0;

		$arg_dropdown_pages = array(
			'selected'	=> $option_value,
			'name'		=> 'wp_konami_code_options[page]'
		);

		wp_dropdown_pages($arg_dropdown_pages);
	}

	function wp_konami_code_url_redirect_activated_input() {
		$options = $this->wp_konami_code_options;

		$option_value = isset($options['url_redirect_activated']) ? $options['url_redirect_activated'] : 0;
		echo '<input type="checkbox" name="wp_konami_code_options[url_redirect_activated]" value="1" '.checked( $option_value, 1, false ).' />';
	}

	function wp_konami_code_url_redirect_input() {
		$options = $this->wp_konami_code_options;

		$option_value = isset($options['url_redirect']) ? $options['url_redirect'] : '';
		echo '<input id="wp_konami_url_redirect" name="wp_konami_code_options[url_redirect]" type="text" value="' . $option_value . '" placeholder="' . get_site_url() .'">';
	}

	function wp_konami_code_visibility_search_engine_input() {
		$options = $this->wp_konami_code_options;

		$option_value = isset($options['visibility_search_engine']) ? $options['visibility_search_engine'] : 0;
		echo '<label>';
		echo '<input type="checkbox" name="wp_konami_code_options[visibility_search_engine]" value="1" '.checked( $option_value, 1, false ).' />';
		_e('Discourage search engines from indexing this Konami Code <a href="edit.php?post_type=page" target="_blank">page</a>.', 'wp-konami-code');
		echo '<label>';
		echo '<p class="description">' . __('It is up to search engines to honor this request.', 'wp-konami-code') . '</p>';
	}

	function wp_konami_code_visibility_internal_search_engine_input() {
		$options = $this->wp_konami_code_options;

		$option_value = isset($options['visibility_internal_search_engine']) ? $options['visibility_internal_search_engine'] : 0;
		echo '<label>';
		echo '<input type="checkbox" name="wp_konami_code_options[visibility_internal_search_engine]" value="1" '.checked( $option_value, 1, false ).' />';
		_e('Exclude this Konami Code <a href="edit.php?post_type=page" target="_blank">page</a> of internal search engine.', 'wp-konami-code');
		echo '<label>';
	}

	function wp_konami_code_options($input)	{
		$valid = array();

		$valid['activated'] = (isset($input['activated']) && filter_var($input['activated'], FILTER_VALIDATE_BOOLEAN)) ? $input['activated'] : 0;

		$valid['page'] = (isset($input['page']) && filter_var($input['page'], FILTER_VALIDATE_INT)) ? $input['page'] : 0;

		$valid['visibility_search_engine'] = (isset($input['visibility_search_engine']) && filter_var($input['visibility_search_engine'], FILTER_VALIDATE_BOOLEAN)) ? $input['visibility_search_engine'] : 0;

		$valid['visibility_internal_search_engine'] = (isset($input['visibility_internal_search_engine']) && filter_var($input['visibility_internal_search_engine'], FILTER_VALIDATE_BOOLEAN)) ? $input['visibility_internal_search_engine'] : 0;

		$valid['url_redirect_activated'] = (isset($input['url_redirect_activated']) && filter_var($input['url_redirect_activated'], FILTER_VALIDATE_BOOLEAN)) ? $input['url_redirect_activated'] : 0;

		if(filter_var($input['url_redirect'], FILTER_VALIDATE_URL)) {
			$valid['url_redirect'] = $input['url_redirect'];
		}
		else if (!empty($input['url_redirect'])) {
			$valid['url_redirect'] = get_site_url();
		}
		else {
			$valid['url_redirect'] = '';
		}

		$valid['version'] = self::VERSION;

		return $valid;
	}
}

$wp_konami_code = new WP_Konami_Code();
