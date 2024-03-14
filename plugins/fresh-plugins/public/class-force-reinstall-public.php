<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Force_Reinstall
 * @subpackage Force_Reinstall/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Force_Reinstall
 * @subpackage Force_Reinstall/public
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Force_Reinstall_Public
{

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
	 * @return void
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function enqueue_styles()
	{
		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/force-reinstall-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function enqueue_scripts()
	{
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/force-reinstall-public.js', array( 'jquery' ), $this->version, false );

	}
}