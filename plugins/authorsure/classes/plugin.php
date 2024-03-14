<?php
class authorsure_plugin {

    private static $jetpack_photon_enabled = false;

	public static function get_jetpack_photon_enabled() {
		return self::$jetpack_photon_enabled;
	}

	public static function set_jetpack_photon_enabled() {
		self::$jetpack_photon_enabled = true;
	}

	public static function plugins_loaded() {
		add_action('jetpack_module_loaded_photon', array(__CLASS__,'set_jetpack_photon_enabled') );
	}

	public static function admin_init() {
		$dir = dirname(__FILE__) . '/';	
		require_once($dir . 'options.php');
		require_once($dir . 'admin.php');
		require_once($dir . 'post.php');
		require_once($dir . 'profile.php');
		require_once($dir . 'archive.php');
		authorsure_options::init();	
		authorsure_admin::init();	
		authorsure_post::init();	
		authorsure_profile::init();	
		authorsure_archive::init();	
	}
	
	public static function init() {
		$dir = dirname(__FILE__) . '/';
		require_once($dir . 'options.php');
		require_once($dir . 'public.php');
		authorsure_options::init();	
		authorsure::init();	
	}
}
