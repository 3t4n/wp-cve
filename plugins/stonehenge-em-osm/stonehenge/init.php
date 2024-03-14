<?php
#===============================================
# 	Required for all plugins by Stonehenge Creations.
#===============================================
if( !defined('ABSPATH') ) exit;
include_once(ABSPATH.'wp-admin/includes/plugin.php');

if( !defined('STONEHENGE') ) define('STONEHENGE', 'https://www.stonehengecreations.nl/');
if( !defined('WP_AJAX_URL') ) define('WP_AJAX_URL', admin_url('admin-ajax.php'));

include('class-core.php');
include('class-functions.php');
include('class-forms.php');
include('class-mailer.php');
include('class-pdf.php');
include('class-license.php');
include('class-updater.php');


if( !class_exists('Stonehenge_Creations') ) :
Class Stonehenge_Creations {

	private static $instance;


	#===============================================
	public function __construct() {
		add_action('admin_menu', array($this, 'create_admin_menu'), 10, 2);
		include('class-plugins.php');
	}


	#===============================================
	public function create_admin_menu() {
		$main = array(
			'name' 		=> 'Stonehenge Creations',
			'short' 	=> 'Stonehenge',
			'text' 		=> 'stonehenge-creations',
			'slug' 		=> 'stonehenge-creations',
			'capab'		=> 'manage_options',
			'parent' 	=> 'stonehenge-creations',
			'icon' 		=> 'dashicons-plugins-checked',
		);

		add_menu_page( $main['name'], $main['short'], $main['capab'], $main['slug'], array($this, 'show_main_page'), $main['icon'], 27);
		add_submenu_page( $main['slug'], $main['name'], $main['short'], $main['capab'], $main['slug'], array($this, 'show_main_page'));
		remove_submenu_page($main['slug'], $main['slug']);
		do_action('stonehenge_menu');
	}


	#===============================================
	public function show_main_page() {
		/* Nothing to display */
	}


	#===============================================
	public static function init($plugin) {
		if( !is_object(self::$instance) || self::$instance->plugin['base'] != $plugin['base'] ) {
			global $SC_Plugin;
			self::$instance = $SC_Plugin = new Stonehenge_Plugin($plugin);
		}
		self::support( $plugin );
		return self::$instance;
	}


	#===============================================
	private static function support($plugin) {
		if( stonehenge()->is_licensed && stonehenge()->is_valid && !class_exists('Stonehenge_Creations_Support') ) {
			include('class-tickets.php');
			new Stonehenge_Creations_Support($plugin);
		}
		if( !stonehenge()->is_licensed && stonehenge()->is_valid && !class_exists('Stonehenge_Creations_Forum') ) {
			include('class-forum.php');
			new Stonehenge_Creations_Forum($plugin);
		}
	}

} // End class.
endif;


#===============================================
if( !function_exists('start_stonehenge') ) {
	function start_stonehenge($plugin) {
		$start = Stonehenge_Creations::init($plugin);
		return true;
	}
}

#===============================================
if( !function_exists('stonehenge') ) {
	function stonehenge() {
		global $SC_Plugin;
		return $SC_Plugin;
	}
}
