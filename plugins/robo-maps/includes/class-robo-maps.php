<?php

/*  
 * Robo Maps            http://robosoft.co/wordpress-google-maps
 * Version:             1.0.6 - 19837
 * Author:              Robosoft
 * Author URI:          http://robosoft.co
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Date:                Thu, 18 May 2017 11:11:10 GMT
 */

class Robo_Maps {

	protected $loader;
	protected $robo_maps;
	protected $version;

	public function __construct() {

		$this->robo_maps = 'robo-maps';
		$this->version = ROBO_MAPS_VERSION;
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-robo-maps-loader.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-robo-maps-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-robo-maps-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-robo-maps-public.php';

		$this->loader = new Robo_Maps_Loader();

	}

	private function set_locale() {

		$plugin_i18n = new Robo_Maps_i18n();
		$plugin_i18n->set_domain( $this->get_robo_maps() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

			$plugin_admin = new Robo_Maps_Admin( $this->get_robo_maps(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			$this->loader->add_action( 'admin_menu', $plugin_admin, 'setup_menu' );
			$this->loader->add_action( 'media_buttons', $plugin_admin, 'setup_button', 15 );

	}

	private function define_public_hooks() {

			$plugin_public = new Robo_Maps_Public( $this->get_robo_maps(), $this->get_version() );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

			$this->loader->add_shortcode( 'showmap', $plugin_public, 'render_showmap' );

	}


	public function run() {
		$this->loader->run();
	}


	public function get_robo_maps() {
		return $this->robo_maps;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
