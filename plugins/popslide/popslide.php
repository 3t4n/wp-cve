<?php
/*
Plugin Name: Popslide
Description: Best popup slider plugin
Author: Kuba Mikita
Author URI: https://www.wpart.pl/
Version: 3.0
License: GPL2
Text Domain: popslide
*/

/*

    Copyright (C) 2014  Kuba Mikita  hello@underdev.it

    This program is free software; you can redistribute it and/or modify
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

if ( class_exists( 'POPSLIDE' ) ) :

	function popslide_pro_admin_notice() {
		echo '<div id="error" class="notice-info notice"><p>' . __( 'Please deactivate and remove the Popslide plugin when you are using Popslide PRO', 'popslide' ) . '</p></div>';
	}

	add_action( 'admin_notices', 'popslide_pro_admin_notice' );
	add_action( 'network_admin_notices', 'popslide_pro_admin_notice' );

else :

// General
define('POPSLIDE_DEBUG', false);
define('POPSLIDE_VERSION', '2.9');
define('POPSLIDE', plugin_dir_url(__FILE__));
define('POPSLIDE_DIR', plugin_dir_path(__FILE__));

// Assets
define('POPSLIDE_IMAGES', POPSLIDE.'assets/images/');
define('POPSLIDE_IMAGES_DIR', POPSLIDE_DIR.'assets/images/');
define('POPSLIDE_JS', POPSLIDE.'assets/js/');
define('POPSLIDE_JS_DIR', POPSLIDE_DIR.'assets/js/');
define('POPSLIDE_CSS', POPSLIDE.'assets/css/');
define('POPSLIDE_CSS_DIR', POPSLIDE_DIR.'assets/css/');

// Utils
define('POPSLIDE_INC_DIR', POPSLIDE_DIR.'inc/');
define('POPSLIDE_SHORTCODES_DIR', POPSLIDE_DIR.'inc/shortcodes/');
define('POPSLIDE_TEMPLATES_DIR', POPSLIDE_DIR.'templates/');

/**
 * popslide main class
 */
class POPSLIDE {

	public $settings = array();

	public $page_hook;

	public function __construct() {

		global $display_popslide;
		$display_popslide = false;

		add_action('plugins_loaded', array($this, 'load_textdomain'));

		register_activation_hook(__FILE__, array('POPSLIDE', 'activation'));
		register_uninstall_hook(__FILE__, array('POPSLIDE', 'uninstall'));

	}

	/**
	 * Gets all plugin settings
	 * @return array settings
	 */
	public function get_settings() {

		if ( !empty($this->settings) ) 
			return $this->settings;

		$this->settings = get_option('popslide_settings');

		$this->settings = self::merge_defaults($this->settings, self::defaults());

		$this->settings = json_decode(json_encode($this->settings));

		return $this->settings;

	}

	/**
	 * Merge the settings array with defaults
	 * @param  array | string &$a array
	 * @param  array          $b  array
	 * @return array     		  merged array
	 */
	public static function merge_defaults( &$a, $b ) {

		if ( is_object( $a ) )
			$a = get_object_vars( $a );
		elseif ( is_array( $a ) )
			$a =& $a;
		else
			wp_parse_str( $args, $a );

		$b = (array) $b;
		$r = $b;

		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $r[ $k ] ) ) {
				$r[ $k ] = self::merge_defaults( $v, $r[ $k ] );
			} else {
				$r[ $k ] = $v;
			}
		}

		return $r;
	}

	/**
	 * Makes backend and frontend instances
	 * @return void
	 */
	public function make_instances() {

		require_once('backend.php');
		require_once('frontend.php');

		$this->back = new POPSLIDE_BACK();
		$this->front = new POPSLIDE_FRONT();

	}

	/**
	 * On plugin activation
	 * @return void
	 */
	public function load_textdomain() {

		load_plugin_textdomain('popslide', false, dirname(plugin_basename(__FILE__ )).'/langs/');

	}

	/**
	 * On plugin activation
	 * @return void
	 */
	static function activation() {

		if(get_option('popslide_settings') !== false)
			return false;

		update_option('popslide_settings', self::defaults());

	}

	/**
	 * On plugin uninstall
	 * @return void
	 */
	static function uninstall() {

		delete_option('popslide_settings');

	}

	/**
	 * Return default settnigs
	 * @return array default settings
	 */
	static function defaults() {

		return array(
			'status' => 'false',
			'demo' => 'false',
			'mobile' => 'false',
			'cookie' => array(
				'active' => 'true',
				'days' => '30',
				'custom_target' => '',
				'custom_target_close' => 'false',
				'name' => 'popslide_prevent_display'
			),
			'after' => array(
				'hits' => '1',
				'rule' => 'and',
				'seconds' => '10'
			),
			'content' => '',
			'bg_color' => '#f1f1f1',
			'font_color' => '#333333',
			'position' => 'top',
			'close_button' => array(
				'position' => 'top_right',
				'font_size' => '40',
				'color' => '#666666'
			),
			'align' => 'left',
			'width' => array(
				'value' => '100',
				'unit' => '%'
			),
			// 'display' => 'fixed',
			'padding' => array(
				'top' => array(
					'value' => '20',
					'unit' => 'px'
				),
				'right' => array(
					'value' => '20',
					'unit' => 'px'
				),
				'bottom' => array(
					'value' => '20',
					'unit' => 'px'
				),
				'left' => array(
					'value' => '20',
					'unit' => 'px'
				),
			),
			'animation' => array(
				'type' => 'linear',
				'duration' => '300'
			),
			'custom_css' => array(
				'class' => '',
				'status' => 'false',
				'css' => ''
			)
		);

	}

}

global $popslide;

$popslide = new POPSLIDE();
$popslide->make_instances();

endif;
