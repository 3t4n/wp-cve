<?php

namespace WPRuby_CAA\Core\App\Frontend;

use WPRuby_CAA\Core\App\Backend\Endpoints\Endpoints_Factory;
use WPRuby_CAA\Core\Constants;

class App_Page {

	protected static $_instance = null;

	/**
	 * @return self
	 */
	public static function get_instance()
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct()
	{
		add_action( 'admin_menu', [$this, 'admin_menu'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_admin_scripts'] );

	}

	public function enqueue_admin_scripts($hook)
	{
		if (isset($_GET['page']) && strtolower($_GET['page']) === 'controlled-admin-access') {
			wp_enqueue_script('wpruby-caa-app', plugin_dir_url( __FILE__ ) . 'app/dist/app.js', [], Constants::UTIL_CURRENT_VERSION, true);
			wp_enqueue_style('wpruby-caa-css', plugin_dir_url( __FILE__ ) . 'app/dist/css/app.css', [], Constants::UTIL_CURRENT_VERSION);

		}
	}

	public function admin_menu()
	{
		add_menu_page(
			__( 'Controlled Admin Access', 'controlled-admin-access' ),
			__( 'Controlled Access', 'controlled-admin-access' ),
			'manage_options',
			'controlled-admin-access',
			[$this, 'my_admin_page_contents'],
			'dashicons-lock',
			3
		);
	}

	public function my_admin_page_contents()
	{
		$nonce = Endpoints_Factory::get_endpoints_nonce();
		include_once "views/app.php";
	}

}
