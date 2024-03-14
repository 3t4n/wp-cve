<?php
/**
 * Plugin Name: Mapplic Lite
 * Plugin URI: http://www.mapplic.com/
 * Description: Turn simple images and vector graphics into high quality, responsive and fully interactive custom maps.
 * Version: 1.0.1
 * Author: sekler
 * Author URI: https://1.envato.market/R5Nv
 */

if (!class_exists('Mapplic')) :

class Mapplic {
	public static $version = '1.0.1';
	public $admin;

	public function __construct() {
		// Actions
		add_action('init', array($this, 'localize'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles'));

		// Filters
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_action_link'));

		// Create shortcode
		add_shortcode('mapplic', array($this, 'shortcode'));

		// Admin
		if (is_admin()) {
			include('admin/admin.php');
			$this->admin = new MapplicAdmin();
			register_activation_hook(__FILE__, array('MapplicAdmin', 'activation')); // activation
		}
	}

	public function add_action_link($links) {
		$newlink = array('<a href="' . admin_url('edit.php?post_type=mapplic_map' ) . '">' . __('Map List', 'mapplic') . '</a>');
		return array_merge($links, $newlink);
	}

	public function enqueue_scripts_styles() {
		// Styles
		wp_register_style('mapplic-style', plugins_url('core/mapplic.css', __FILE__), array(), Mapplic::$version);

		// Scripts
		wp_register_script('mousewheel', plugins_url('js/jquery.mousewheel.js', __FILE__), false, null);
		wp_register_script('mapplic-script', plugins_url('core/mapplic.js', __FILE__), array('jquery', 'mousewheel'), Mapplic::$version);
		$mapplic_localization = array(
			'more' => __('More', 'mapplic'),
			'search' => __('Search', 'mapplic'),
			'notfound' => __('Nothing found. Please try a different search.', 'mapplic'),
			'iconfile' => plugins_url('core/images/icons.svg', __FILE__)
		);
		wp_localize_script('mapplic-script', 'mapplic_localization', $mapplic_localization);
	}

	public function localize() {
		load_plugin_textdomain('mapplic', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	public function shortcode($atts) {
		extract(shortcode_atts(array(
			'id' => false,
			'h' => false,
			'class' => false, 
			'landmark' => false
		), $atts, 'mapplic'));

		$post = get_post($id);
		if (!$post || !$id) return __('Error: map with the specified ID doesn\'t exist!', 'mapplic');

		$data = $post->post_content;
		
		$output = '<div id="mapplic-id' . $id . '" data-mapdata="' . htmlentities($data, ENT_QUOTES, 'UTF-8') . '"';
		if ($class) $output .= ' class="' . $class . '"';
		if ($landmark) $output .= ' data-landmark="' . $landmark . '"';
		if ($h) $output .= ' data-height="' . $h . '"';
		$output .= '></div>';

		wp_enqueue_style('mapplic-style');
		wp_enqueue_script('mapplic-script');
		do_action('mapplic_enqueue');

		return $output;
	}
}

endif;

function mapplic() {
	global $mapplic;
	if (!isset($mapplic)) $mapplic = new Mapplic();
	return $mapplic;
}
mapplic();

?>