<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Equal_Height_Columns
 * @subpackage Equal_Height_Columns/public
 * @author     MIGHTYminnow, Mickey Kay, Braad Martin
 */
class Equal_Height_Columns_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The display name of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin__displau_name    The public name of this plugin.
	 */
	private $plugin_display_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Options for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $options    The options stored for this plugin.
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name            The name of this plugin.
	 * @var      string    $plugin_display_name    The public name of this plugin.
	 * @var      string    $version                The version of this plugin.
	 */
	public function __construct( $plugin_name, $plugin_display_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_display_name = $plugin_display_name;
		$this->version = $version;

		// Check whether we have at least one selector set.
		$this->options = get_option( $this->plugin_name );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/equal-height-columns-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/equal-height-columns-public.js', array( 'jquery' ), $this->version, false );

		// Allow the data we pass to our JS to be filtered.
		$options = apply_filters( 'equal_height_columns_elements', $this->options );

		// Localize options to JS if there are any.
		if ( $options ) {
			wp_localize_script( $this->plugin_name, 'equalHeightColumnElements', $options );
		}
	}

}
