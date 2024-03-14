<?php
namespace CbParallax\Admin\Menu;

use CbParallax\Admin\Includes as AdminIncludes;
use CbParallax\Admin\Menu\Includes as MenuIncludes;
use CbParallax\Admin\Partials as Partials;
use WP_Post;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Partials\cb_parallax_settings_page_display' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/partials/class-settings-display.php';
}
if ( ! class_exists( 'AdminIncludes\cb_parallax_localisation' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/includes/class-localisation.php';
}

/**
 * The class responsible for the admin menu.
 *
 * @link
 * @since             0.6.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/admin/menu
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax_settings_page {
	
	/**
	 * The domain of the plugin.
	 *
	 * @var string $domain
	 * @since    0.1.0
	 * @access   private
	 */
	private $domain;
	
	/**
	 * Holds this page's screen id.
	 *
	 * @var string $screen_id
	 */
	private $screen_id = 'settings_page_cb-parallax';
	
	/**
	 * Holds a list of supported post types.
	 *
	 * @var array $screen_ids
	 */
	private $screen_ids;
	
	/**
	 * The reference to the options class.
	 *
	 * @since  0.6.0
	 * @access private
	 * @var    MenuIncludes\cb_parallax_options $options
	 */
	private $options;
	
	/**
	 * cb_parallax_admin constructor.
	 *
	 * @param string $domain
	 * @param array $screen_ids
	 * @param MenuIncludes\cb_parallax_options $options
	 */
	public function __construct( $domain, $screen_ids, $options ) {
		
		$this->domain = $domain;
		$this->screen_ids = $screen_ids;
		$this->options = $options;
		$this->add_hooks();
		$this->init();
	}
	
	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function add_hooks() {
		
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ), 1000 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'include_menu_localisation' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ), 20 );
		add_filter( 'admin_body_class', array( $this, 'add_body_class' ), 10, 1 );
	}
	
	/**
	 * Adds the actions for registering the stylesheets and javascript files with WordPress,
	 * if we're on a white-listed screen.
	 */
	private function init() {
		
		if ( in_array( $this->screen_id, $this->screen_ids, true ) ) {
			
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ), 1000 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
		}
	}
	
	/**
	 * Registers the stylesheets with WordPress.
	 *
	 * @param string $hook_suffix
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function enqueue_styles( $hook_suffix ) {
		
		if ( isset( $hook_suffix ) && $hook_suffix === $this->screen_id ) {
			
			// Color picker.
			wp_enqueue_style( 'wp-color-picker' );
			
			// Fancy Select
			wp_enqueue_style( 'cb-parallax-inc-fancy-select-css',
				CBPARALLAX_ROOT_URL . 'vendor/fancy-select/fancySelect.css',
				array(),
				'all',
				'all'
			);
			
			// Menu
			wp_enqueue_style( 'cb-parallax-settings-display-css', CBPARALLAX_ROOT_URL . 'admin/menu/css/settings-display.css', array(), 'all', 'all' );
		}
	}
	
	/**
	 * Registers the javascript files with WordPress.
	 *
	 * @param string $hook_suffix
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function enqueue_scripts( $hook_suffix ) {
		
		if ( isset( $hook_suffix ) && $hook_suffix === $this->screen_id ) {
			
			// Dashicons
			wp_enqueue_style( 'dashicons' );
			
			// Color picker.
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker-alpha', CBPARALLAX_ROOT_URL . 'vendor/color-picker-alpha/wp-color-picker-alpha.min.js', array(
				'jquery',
				'wp-color-picker'
			), 'all', true );
			
			// Media Frame.
			wp_enqueue_script( 'media-views' );
			
			// Media upload engine.
			wp_enqueue_media();
			
			// Fancy Select.
			wp_enqueue_script( 'cb-parallax-inc-fancy-select-js', CBPARALLAX_ROOT_URL . 'vendor/fancy-select/fancySelect.js', array( 'jquery' ), 'all', true );
			
			// Admin part.
			wp_enqueue_script( 'cb-parallax-settings-display-js', CBPARALLAX_ROOT_URL . 'admin/js/settings-display.js', array(
				'jquery',
				'wp-color-picker',
				'media-views',
				'cb-parallax-inc-fancy-select-js',
			), 'all', true );
		}
	}
	
	/**
	 * Adds a css class to the body tag.
	 *
	 * @param string $classes
	 *
	 * @return string $classes
	 */
	public function add_body_class( $classes ) {
		
		$classes .= 'cb-parallax-settings-page ';
		
		return $classes;
	}
	
	/**
	 * Includes the class responsible for localizing the javascript file for the menu.
	 *
	 * @param string $hook_suffix
	 */
	public function include_menu_localisation( $hook_suffix ) {
		
		if ( isset( $hook_suffix ) && $hook_suffix === $this->screen_id ) {
			
			$script_localisation = new AdminIncludes\cb_parallax_localisation( $this->domain, $this->screen_ids, $this->options );
			$script_localisation->add_hooks();
		}
	}
	
	/**
	 * Calls the function that registers the options page with WordPress.
	 *
	 * @return void
	 */
	public function add_options_page() {
		
		add_options_page(
			__( 'cbParallax Settings Page', $this->domain ),
			'cbParallax',
			'manage_options',
			'cb-parallax.php',
			array( $this, 'settings_display' )
		);
	}
	
	/**
	 * Retrieves the user-defined optuons and orchestrates the functions that display the form and the settings fields.
	 *
	 * @echo
	 */
	public function settings_display() {
		
		/**
		 * @var WP_Post $post
		 */
		global $post;
		
		$allowed_image_options = $this->options->get_image_options_whitelist();
		
		$display = new Partials\cb_parallax_settings_display( $this->domain, $this->options, $allowed_image_options );
		
		$stored_options = get_option( 'cb_parallax_options' );
		$attachment_id = isset( $stored_options['cb_parallax_attachment_id'] ) ? $stored_options['cb_parallax_attachment_id'] : false;
		
		// Get image meta data
		$image = null;
		if ( false !== $attachment_id ) {
			$image = wp_get_attachment_image_src( absint( $attachment_id ), 'full' );
		}
		// Get the image url
		$url = isset( $image[0] ) ? $image[0] : '';
		
		/**
		 * If the image was not found, we bail early.
		 */
		if ( false === $this->options->is_image_in_media_library( $post ) ) {
			$attachment_id = '';
			$url = '';
		}
		
		// The settings form
		$nonce = wp_create_nonce( 'cb_parallax_manage_options_nonce' );
		echo '<form id="cb_parallax_settings_form" data-nonce="' . $nonce . '" method="POST" data-form="" data-postid="">';
		echo $display->get_hidden_fields_display( $attachment_id, $url );
		$settings = $this->options->get_options_arguments();
		foreach ( $settings as $option_key => $args ) {
			
			$value = isset( $stored_options[ $option_key ] ) ? $stored_options[ $option_key ] : '';
			
			switch ( $args['input_type'] ) {
				
				case( $args['input_type'] == 'checkbox' );
					
					echo $display->get_checkbox_display( $value, $args );
					if ( 'cb_parallax_parallax_enabled' === $option_key ) {
						echo $display->get_settings_title( 'plugin' );
					}
					break;
				
				case( $args['input_type'] == 'color' );
					
					echo $display->get_color_picker_field( $value, $args );
					break;
				
				case( $args['input_type'] == 'select' );
					
					echo $display->get_select_field( $value, $args );
					break;
				
				case( $args['input_type'] == 'media' );
					
					echo $display->get_form_title();
					echo $display->get_media_button_display();
					echo $display->get_background_image_display( $url, 'plugin' );
					break;
			}
		}
		echo '<input id="cb_parallax_form_submit" type="submit" value="' . __( 'Save' ) . '" class="button button-primary button-large" />';
		echo '<input id="cb_parallax_form_reset" type="submit" value="' . __( 'Reset' ) . '" class="button button-secondary button-large" />';
		echo '<form>';
	}
	
}
