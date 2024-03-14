<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_Scroll_Progress_Bar
 * @subpackage Catch_Scroll_Progress_Bar/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Catch_Scroll_Progress_Bar
 * @subpackage Catch_Scroll_Progress_Bar/admin
 * @author     Catch Plugins <www.catchplugins.com>
 */
class Catch_Scroll_Progress_Bar_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		

		if( isset( $_GET['page'] ) && 'catch-scroll-progress-bar' == $_GET['page'] ) {
			wp_enqueue_style( $this->plugin_name. '-display-dashboard', plugin_dir_url( __FILE__ ) . 'css/catch-scroll-progress-bar-admin.css', array(), $this->version, 'all' );
              }
           
		}
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		if ( isset( $_GET['page'] ) && 'catch-scroll-progress-bar' == $_GET['page'] ) {
			wp_enqueue_script( 'matchHeight', plugin_dir_url( __FILE__ ) . 'js/jquery-matchHeight.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/catch-scroll-progress-bar-admin.js', array( 'jquery', 'matchHeight','jquery-ui-tooltip' ), $this->version, false );
			 wp_enqueue_script( 'catch-scroll-progress-bar-color-picker', plugin_dir_url( __FILE__ ) . 'js/wp-color-picker.js', array( 'wp-color-picker', 'jquery' ), $this->version, false );
		}

	}

	public function add_plugin_settings_menu() {
		add_menu_page(
			esc_html__( 'Catch Scroll Progress Bar ', 'catch-scroll-progress-bar' ), // $page_title.
			esc_html__( 'Catch Scroll Progress Bar ', 'catch-scroll-progress-bar' ), // $menu_title.
			'manage_options', // $capability.
			'catch-scroll-progress-bar', // $menu_slug.
			array( $this, 'settings_page' ), // $callback_function.
			'dashicons-editor-alignleft', // $icon_url.
			'99.01564' // $position.
		);
		add_submenu_page(
				'catch-scroll-progress-bar', // $parent_slug.
				esc_html__( 'Catch Scroll Progress Bar', 'catch-scroll-progress-bar' ), // $page_title.
				esc_html__( 'Settings', 'catch-scroll-progress-bar' ), // $menu_title.
				'manage_options', // $capability.
				'catch-scroll-progress-bar', // $menu_slug.
				array( $this,'settings_page' ) // $callback_function.
			);
	}

	/**
	 * Catch Scroll Progress Bar: action_links
	 * Catch Scroll Progress Bar Settings Link function callback
	 *
	 * @param array $links Link url.
	 *
	 * @param array $file File name.
	 */
	public function action_links( $links, $file ) {
		if ( $file === $this->plugin_name . '/' . $this->plugin_name . '.php' ) {
			$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=catch-scroll-progress-bar' ) ) . '">' . esc_html__( 'Settings', 'catch-scroll-progress-bar' ) . '</a>';

			array_unshift( $links, $settings_link );
		}
		return $links;
	}
	

	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'catch-scroll-progress-bar' ) );
			}
			require plugin_dir_path( __FILE__ ) . 'partials/catch-scroll-progress-bar-admin-display.php';
		}
		public function register_settings() {
			register_setting(
				'catch-scroll-progress-bar-group',
				'catch_progress_bar_options',
				array( $this, 'sanitize_callback' )
			);
	}

	public function sanitize_callback( $input ) {
		if ( isset( $input['reset'] ) && $input['reset'] ) {
			//If reset, restore defaults
			return catch_progress_bar_default_options();
		}

		// Verify the nonce before proceeding.
		if (  
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			|| 
			(
				! isset( $_POST['catch_progress_bar_nonce'] ) 
				|| 
				! wp_verify_nonce( $_POST['catch_progress_bar_nonce'], CATCH_SCROLL_PROGRESS_BAR_BASENAME ) 
			) 
			|| 
				! check_admin_referer( CATCH_SCROLL_PROGRESS_BAR_BASENAME, 'catch_progress_bar_nonce' ) 
			) {

			echo esc_html__( 'Sorry, your nonce did not verify.', 'catch-scroll-progress-bar' );
			exit;

		} else {

			if ( null !== $input ) {

				$input['reset']   = ( isset( $input['reset'] ) && '1' == $input['reset'] ) ? '1' : '0';
				$input['home']    = ( isset( $input['home'] ) && '1' == $input['home'] ) ? 1 : 0;
				$input['blog']    = ( isset( $input['blog'] ) && '1' == $input['blog'] ) ? 1 : 0;
				$input['archive'] = ( isset( $input['archive'] ) && '1' == $input['archive'] ) ? 1 : 0;
				$input['single']  = ( isset( $input['single'] ) && '1' == $input['single'] ) ? 1 : 0;
				
				$post_types       = get_post_types( array( 'public' => true ), 'objects' );
			    foreach( $post_types as $type => $obj ) {
				    $input['field_posttypes'][$type] = ( isset( $input['field_posttypes'][$type] ) && '1' == $input['field_posttypes'][$type] ) ? 1 : 0;
				}
			     
				if ( isset( $input['radius'] ) ) {
					$input['radius']             = sanitize_text_field( $input['radius'] );
				}
				if ( isset( $input['bar_height'] ) ) {
					$input['bar_height']         = sanitize_text_field( $input['bar_height'] );
				}
				if ( isset( $input['background_opacity'] ) ) {
					$input['background_opacity'] = floatval( $input['background_opacity'] );
				}
				if ( isset( $input['foreground_opacity'] ) ) {
					$input['foreground_opacity'] = floatval( $input['foreground_opacity'] );
				}
				if ( isset( $input['background_color'] ) && $input['background_color'] ) {
					$input['background_color']   = sanitize_hex_color( $input['background_color'] );
				}
				if ( isset( $input['foreground_color'] ) && $input['foreground_color'] ) {
					$input['foreground_color']   = sanitize_hex_color( $input['foreground_color'] );
				}
				return $input;

			}
		}
	}

	function add_plugin_meta_links( $meta_fields, $file ){
		if( CATCH_SCROLL_PROGRESS_BAR_BASENAME == $file ) {
			$meta_fields[] = "<a href='https://catchplugins.com/support-forum/forum/catch-scroll-progress-bar/' target='_blank'>Support Forum</a>";
			$meta_fields[] = "<a href='https://wordpress.org/support/plugin/catch-scroll-progress-bar/reviews#new-post' target='_blank' title='Rate'>
			        <i class='ct-rate-stars'>"
			  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			  . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
			  . "</i></a>";

			$stars_color = "#ffb900";

			echo "<style>"
				. ".ct-rate-stars{display:inline-block;color:" . $stars_color . ";position:relative;top:3px;}"
				. ".ct-rate-stars svg{fill:" . $stars_color . ";}"
				. ".ct-rate-stars svg:hover{fill:" . $stars_color . "}"
				. ".ct-rate-stars svg:hover ~ svg{fill:none;}"
				. "</style>";
		}

		return $meta_fields;
	}
}
