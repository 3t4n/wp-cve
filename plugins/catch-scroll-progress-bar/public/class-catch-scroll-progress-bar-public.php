<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_Scroll_Progress_Bar
 * @subpackage Catch_Scroll_Progress_Bar/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Catch_Scroll_Progress_Bar
 * @subpackage Catch_Scroll_Progress_Bar/public
 * @author     Catch Plugins <www.catchplugins.com>
 */
class Catch_Scroll_Progress_Bar_Public {

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

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Catch_Progress_Bar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Catch_Progress_Bar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/catch-scroll-progress-bar-public.css', array(), $this->version, 'all' );

		$settings           = catch_progress_bar_get_options();
		$height             = $settings['bar_height'];
		$foreground_color   = $settings['foreground_color'];
		$background_color   = $settings['background_color'];
		$background_opacity = $settings['background_opacity'];
		$foreground_opacity = $settings['foreground_opacity'];
		$position           = $settings['progress_bar_position'];
		$radius             = $settings['radius'];

		if( 'top' === $position ) {
			$top    = 0;
			$bottom = 'auto';
		} else {
			$bottom = 0;
			$top    = 'auto';
		}

		$custom_css = "
			.catchProgressbar {
				height: {$height}px;
				background-color: {$background_color};
				opacity: {$background_opacity};
				top: {$top};
				bottom: {$bottom};
				border-radius: {$radius}px;
			}

			.catchProgressbar::-webkit-progress-bar { 
				background-color: transparent; 
			} 
			.catchProgressbar::-webkit-progress-value { 
				background-color: {$foreground_color};
				border-radius: {$radius}px;
				opacity: {$foreground_opacity}; 
			} 

			.catchProgressbar::-webkit-progress-bar,
			.catchProgressbar::-moz-progress-bar { 
				background-color: {$foreground_color}; 
				border-radius: {$radius}px;
				opacity: {$foreground_opacity}; 
			}
		";

		wp_add_inline_style('catch-scroll-progress-bar', $custom_css);

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Catch_Scroll_Progress_Bar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Catch_Scroll_Progress_Bar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/catch-scroll-progress-bar-public.js', array( 'jquery' ), $this->version, false );

	}
	public function show_it() {
		$settings = catch_progress_bar_get_options();
		if( isset($settings['home']) && 1==$settings['home'] && (is_front_page())){
			display_progress_bar();
		} elseif ( isset($settings['blog']) && 1==$settings['blog'] && (is_home() && !is_front_page()) ) {
			display_progress_bar();
		} elseif ( isset($settings['archive']) && 1==$settings['archive']&& (is_archive()) ) {
			display_progress_bar();
		}  elseif ( isset( $settings['single'] ) && ( is_singular() && !is_front_page() ) ) {
			$optionPostTypes = $settings['field_posttypes'];
			$currentPostType = get_post_type();
			if ( isset( $optionPostTypes[$currentPostType] ) && ( $optionPostTypes[$currentPostType] == 1 ) ) {
				display_progress_bar();
			} 
			}
	}
}

	function display_progress_bar() {
		$settings           = catch_progress_bar_get_options();
		$height             = $settings['bar_height'];
		$foreground_color   = $settings['foreground_color'];
		$background_color   = $settings['background_color'];
		$background_opacity = $settings['background_opacity'];
		$foreground_opacity = $settings['foreground_opacity'];
		$position           = $settings['progress_bar_position'];
		$radius             = $settings['radius'];

		// echo "<progress data-height={$height} data-radius={$radius} data-foreground={$foreground_color} data-background={$background_color} data-position={$position} data-background-opacity={$background_opacity} data-foreground-opacity={$foreground_opacity} class='catchProgressbar' value='0'>
		// </progress>";
		echo "<progress class='catchProgressbar' value='0'></progress>";
	}
