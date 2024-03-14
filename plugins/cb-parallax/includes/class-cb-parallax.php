<?php
namespace CbParallax\Includes;

use CbParallax\Pub as Pub;
use CbParallax\Admin as Admin;
use CbParallax\Admin\Menu\Includes as MenuIncludes;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Require dependencies.
 */
if ( ! class_exists( 'Includes\cb_parallax_i18n' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'includes/class-i18n.php';
}
if ( ! class_exists( 'Admin\cb_parallax_admin' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/class-admin.php';
}
if ( ! class_exists( 'Pub\cb_parallax_public' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'public/class-public.php';
}
if ( ! class_exists( 'MenuIncludes\cb_parallax_options' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/menu/includes/class-options.php';
}

/**
 * The core class of the plugin.
 *
 * @link
 * @since             0.1.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax {
	
	/**
	 * The unique identifier of the plugin.
	 *
	 * @var string $name
	 * @since    0.1.0
	 * @access   private
	 */
	private $name;
	
	/**
	 * The domain of the plugin.
	 *
	 * @var string $domain
	 * @since    0.1.0
	 * @access   private
	 */
	private $domain;
	
	/**
	 * The current version of the plugin.
	 *
	 * @var string $version
	 * @since    0.1.0
	 * @access   private
	 */
	private $version;
	
	/**
	 * The reference to the options class.
	 *
	 * @since  0.6.0
	 * @access private
	 * @var    MenuIncludes\cb_parallax_options $options
	 */
	private $options;
	
	/**
	 * Holds the object that references to the class responsible for
	 * handling the user-defined options.
	 *
	 * @return void
	 */
	private function set_options_instance() {
		
		$this->options = new MenuIncludes\cb_parallax_options( $this->domain );
	}
	
	/**
	 * cb_parallax constructor.
	 */
	public function __construct() {
		
		$this->name = 'cb-parallax';
		$this->domain = $this->name;
		$this->version = '0.9.0';
		
		$this->set_options_instance();
		$this->include_plugin_text_domain();
	}
	
	/**
	 * Calls the functions that register the initial hooks with WordPress
	 * for both the admin area and the public area.
	 *
	 * @return void
	 */
	public function run() {
		
		$this->run_admin();
		$this->run_public();
	}
	
	/**
	 * Instantiates the class responsible for the language localisation features
	 * and calls the method that adds the hooks to WordPress.
	 *
	 * @return void
	 */
	private function include_plugin_text_domain() {
		
		$i18n = new cb_parallax_i18n( $this->domain );
		$i18n->add_hooks();
	}
	
	/**
	 * Runs the admin part of the plugin,
	 * if we're on the admin part of the website.
	 *
	 * @return void
	 */
	private function run_admin() {
		
		if ( ! is_admin() ) {
			return;
		}
		
		$admin = new Admin\cb_parallax_admin( $this->domain, $this->version, $this->options );
		$admin->add_hooks();
	}
	
	/**
	 * Runs the public part of the plugin,
	 * if we're on the public part of the website.
	 *
	 * @return void
	 */
	private function run_public() {
		
		if ( is_admin() ) {
			return;
		}
		
		$public = new Pub\cb_parallax_public( $this->domain, $this->options );
		$public->add_hooks();
	}
	
}
