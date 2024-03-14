<?php
namespace CbParallax\Admin;

use CbParallax\Includes as Includes;
use CbParallax\Admin\Menu as AdminMenu;
use CbParallax\Admin\Includes as AdminIncludes;
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
if ( ! class_exists( 'AdminIncludes\cb_parallax_theme_support' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/includes/class-theme-support.php';
}
if ( ! class_exists( 'AdminIncludes\cb_parallax_post_type_support' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/includes/class-post-type-support.php';
}
if ( ! class_exists( 'AdminIncludes\cb_parallax_localisation' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/includes/class-localisation.php';
}
if ( ! class_exists( 'MenuIncludes\cb_parallax_ajax' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/menu/includes/class-ajax.php';
}
if ( ! class_exists( 'MenuIncludes\cb_parallax_validation' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/menu/includes/class-validation.php';
}
if ( ! class_exists( 'AdminIncludes\cb_parallax_contextual_help' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/includes/class-contextual-help.php';
}
if ( ! class_exists( 'AdminIncludes\cb_parallax_meta_box' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/includes/class-meta-box.php';
}
if ( ! class_exists( 'AdminMenu\cb_parallax_menu' ) ) {
	require_once CBPARALLAX_ROOT_DIR . 'admin/menu/class-settings-page.php';
}

/**
 * The admin part of the plugin.
 *
 * @link
 * @since             0.1.0
 * @package           cb_parallax
 * @subpackage        cb_parallax/admin
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class cb_parallax_admin {
	
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
	 * Holds a list of supported post types.
	 *
	 * @var array $screen_ids
	 */
	private $screen_ids = array(
		'post',
		'page',
		'settings_page_cb-parallax'
	);
	
	/**
	 * cb_parallax_admin constructor.
	 *
	 * @param string $domain
	 * @param string $version
	 * @param MenuIncludes\cb_parallax_options $options
	 */
	public function __construct( $domain, $version, $options ) {
		
		$this->domain = $domain;
		$this->version = $version;
		$this->options = $options;
		
		$this->include_post_type_support();
		$this->include_theme_support();
		$this->include_contextual_help();
		$this->include_settings_page();
		$this->include_ajax_functionality();
	}
	
	/**
	 * Registers the methods that need to be hooked with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function add_hooks() {
		
		add_action( 'admin_init', array( $this, 'include_meta_box' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'include_script_localisation' ), 20 );
		add_action( 'plugin_row_meta', array( $this, 'filter_plugin_row_meta' ), 10, 2 );
		add_action( 'upgrader_process_complete', array($this, 'run_cb_parallax_upgrade'), 10, 2 );
	}
	
	/**
	 * Registers the stylesheets with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function enqueue_styles() {

		$screen = get_current_screen();
		
		if ( in_array( $screen->base, $this->screen_ids, true ) ) {

			wp_enqueue_style( 'jquery-ui-smoothness-theme',
				CBPARALLAX_ROOT_URL . 'vendor/jquery-ui/themes/jquery-ui.min.css',
				array(),
				'all',
				'all'
			);
			
			// Dashicons
			wp_enqueue_style( 'dashicons' );
			
			// Color Picker
			wp_enqueue_style( 'wp-color-picker' );
			
			// Fancy Select
			wp_enqueue_style( 'cb-parallax-inc-fancy-select-css',
				CBPARALLAX_ROOT_URL . 'vendor/fancy-select/fancySelect.css',
				array(),
				'all',
				'all'
			);
			
			// Metabox Display
			if( 'settings_page_cb-parallax' !== $screen->base ) {
				wp_enqueue_style( 'cb-parallax-metabox-display-css',
					CBPARALLAX_ROOT_URL . 'admin/css/metabox-display.css',
					array(),
					'all',
					'all'
				);
			}
		}
	}
	
	/**
	 * Registers the javascript files with WordPress.
	 *
	 * @since 0.9.0
	 * @return void
	 */
	public function enqueue_scripts() {
		
		$screen = get_current_screen();
		
		if ( in_array( $screen->base, $this->screen_ids, true ) ) {
			
			// Color picker.
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker-alpha',
				CBPARALLAX_ROOT_URL . 'vendor/color-picker-alpha/wp-color-picker-alpha.min.js',
				array(
					'jquery',
					'wp-color-picker'
				),
				'all',
				true
			);
			
			// Media Frame.
			wp_enqueue_script( 'media-views' );
			
			// Media upload engine.
			wp_enqueue_media();
			
			// Fancy Select.
			wp_enqueue_script( 'cb-parallax-inc-fancy-select-js',
				CBPARALLAX_ROOT_URL . 'vendor/fancy-select/fancySelect.js',
				array(
					'jquery'
				),
				'all',
				true
			);
			
			// jQuery UI Libs
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'jquery-ui-widget' );
			wp_enqueue_script( 'jquery-effects-core' );
			
			// Admin part.
			wp_enqueue_script( 'cb-parallax-settings-display-js',
				CBPARALLAX_ROOT_URL . 'admin/js/settings-display.js',
				array(
					'jquery',
					//
					'wp-color-picker',
					'media-views',
					//
					'jquery-ui-core',
					'jquery-ui-tabs',
					'jquery-ui-widget',
					'jquery-effects-core',
					//
					'cb-parallax-inc-fancy-select-js',
				),
				'all'
			);
		}
	}
	
	/**
	 * Includes the class responsible for pots type support.
	 *
	 * @return void
	 */
	private function include_post_type_support() {
		
		$post_type_support = new AdminIncludes\cb_parallax_post_type_support();
		$post_type_support->add_hooks();
	}
	
	/**
	 * Includes the class responsible for theme support.
	 *
	 * @return void
	 */
	private function include_theme_support() {
		
		$wp_support = new AdminIncludes\cb_parallax_theme_support();
		$wp_support->add_hooks();
	}
	
	/**
	 * Includes the class responsible for displaying the meta box.
	 *
	 * @return void
	 */
	public function include_meta_box() {
		
		$meta_box = new AdminIncludes\cb_parallax_meta_box( $this->domain, $this->screen_ids, $this->options );
		$meta_box->add_hooks();
	}
	
	/**
	 * Includes the class responsible for localizing the javascript file.
	 *
	 * @return void
	 */
	public function include_script_localisation() {
		
		$script_localisation = new AdminIncludes\cb_parallax_localisation( $this->domain, $this->screen_ids, $this->options );
		$script_localisation->add_hooks();
	}
	
	/**
	 * Includes the class responsible for displaying the contextual help.
	 *
	 * @return void
	 */
	private function include_contextual_help() {

		$Help_Tab = new AdminIncludes\cb_parallax_contextual_help( $this->domain );
		$Help_Tab->add_hooks();
	}
	
	/**
	 * Includes the class responsible for displaying the settings page.
	 *
	 * @return void
	 */
	private function include_settings_page() {
		
		$menu = new AdminMenu\cb_parallax_settings_page( $this->domain, $this->screen_ids, $this->options );
		$menu->add_hooks();
	}
	
	/**
	 * Includes the class responsible for receiving the ajax requests.
	 *
	 * @return void
	 */
	private function include_ajax_functionality() {
		
		$ajax = new MenuIncludes\cb_parallax_ajax( $this->domain, $this->options );
		$ajax->add_hooks();
	}
	
	/**
	 * Displays the plugin row content.
	 *
	 * @param array $meta
	 * @param string $file
	 *
	 * @return array $meta
	 */
	public function filter_plugin_row_meta( $meta, $file ) {
		
		$plugin = plugin_basename( 'cb-parallax/cb-parallax.php' );
		
		if ( $file == $plugin ) {
			$meta[] = '<a href="https://wordpress.org/support/plugin/cb-parallax" target="_blank">' . __( 'Plugin support', $this->domain ) . '</a>';
			$meta[] = '<a href="https://wordpress.org/plugins/cb-parallax" target="_blank">' . __( 'Rate plugin', $this->domain ) . '</a>';
			$meta[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XLMMS7C62S76Q" target="_blank">' . __( 'Buy me a beer!', $this->domain ) . '</a>';
		}
		
		return $meta;
	}
	
	/**
	 * Instantiates the class responsible for the options upgrades
	 * and runs it.
	 */
	public function run_cb_parallax_upgrade() {
		
		if ( ! class_exists( 'Includes\cb_parallax_upgrade' ) ) {
			require_once CBPARALLAX_ROOT_DIR . 'includes/class-cb-parallax-upgrade.php';
		}
		$upgrader = new Includes\cb_parallax_upgrade( $this->domain, $this->version );
		$upgrader->run();
	}
}
