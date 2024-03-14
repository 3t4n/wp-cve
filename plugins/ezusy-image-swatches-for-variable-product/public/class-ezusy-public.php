<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.ezusy.com
 * @since      1.0.0
 *
 * @package    Ezusy
 * @subpackage Ezusy/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ezusy
 * @subpackage Ezusy/public
 * @author     Nam Nguyen <vniteam@gmail.com>
 */
class Ezusy_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->init();

	}
	
	
	public function init(){
		include EZUSY_DIR_PATH . 'includes' . DS . 'functions.php';
	}
	
	public function enqueue_styles() {

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

	}

	public function disable_ajax( ) {	
		return 50;
	}	

}
