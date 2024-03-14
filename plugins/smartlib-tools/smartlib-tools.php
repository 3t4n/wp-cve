<?php

/*
  Plugin Name: Smartlib Tools
  Plugin URI: https://wordpress.org/plugins/smartlib-tools/
  Description: Some extra features to your theme: Portfolio, Testimonials, FAQ and Team Members Post Types.
  Version: 1.0.7
  Author: Peter Bielecki
  Author URI: https://www.netbiel.pl
  License: GPL V3
 */

/*Include all required files*/

require plugin_dir_path( __FILE__ ) . 'includes/class-user.php';
require plugin_dir_path( __FILE__ ) . 'includes/post-types.php';
require plugin_dir_path( __FILE__ ) . 'includes/metabox-integration.php';
require plugin_dir_path( __FILE__ ) . 'includes/theme-integration.php';
require plugin_dir_path( __FILE__ ) . 'includes/bootstrap-for-contact-form-7.php';

//add shortcake - check before if  plugin is installed
if( !function_exists( 'shortcode_ui_register_for_shortcode' ) ){
	require plugin_dir_path( __FILE__ ) . 'vendor/shortcode-ui/shortcode-ui.php';
}

/*Add shortcode integration*/

if (defined('SU_PLUGIN_FILE')) {
	require plugin_dir_path( __FILE__ ) . 'includes/shortcode-integration.php';
}else{
	add_action( 'admin_notices', 'smtool_lack_shortcode_notice' );
}


require plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php';


class Smartlib_Tools {
	private static $instance = null;
	public $oPostTypes;
	public $oTheme;
	private $plugin_path;
	private $plugin_url;
    private $text_domain = 'smartlib';

	/**
	 * Creates or returns an instance of this class.
	 */
	public static function get_instance() {
		// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Initializes the plugin by setting localization, hooks, filters, and administrative functions.
	 */
	private function __construct() {
		$this->plugin_path = plugin_dir_path( __FILE__ );
		$this->plugin_url  = plugin_dir_url( __FILE__ );

		define('SMARTLIB_PLUGIN_PATH', $this->plugin_path);
		define('SMARTLIB_PLUGIN_URL', $this->plugin_url);

		load_plugin_textdomain( $this->text_domain, false, 'lang' );

		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_styles' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );

		/*
		 * Theme Integration
		 */

		add_action( 'after_setup_theme', array( $this, 'theme_integration' ) );




		/*
		 * User Utils
		 */

		$this->oUser_Utils= new Smartlib_User_Utils();


		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

		$this->run_plugin();
	}

	public function get_plugin_url() {
		return $this->plugin_url;
	}

	public function get_plugin_path() {
		return $this->plugin_path;
	}

    /**
     * Place code that runs at plugin activation here.
     */
    public function activation() {

	}

    /**
     * Place code that runs at plugin deactivation here.
     */
    public function deactivation() {

	}

    /**
     * Enqueue and register JavaScript files here.
     */
    public function register_scripts() {

		wp_enqueue_script('smarttool_main_js',  $this->plugin_url . 'assets/main-smart-tools.js', array('jquery'), '0.1', true);
	}

    /**
     * Enqueue and register CSS files here.
     */
    public function register_styles() {
			/*register awesome css*/

			/*check if style have benn enqueued in the theme*/
			if(!wp_style_is( 'smartlib_font_awesome', $list = 'enqueued' )){
				wp_enqueue_style('smartlib_font_awesome',  $this->plugin_url . '/assets/font-awesome/css/font-awesome.min.css', false);
			}

		if(!wp_style_is( 'smartlib_animate', $list = 'enqueued' )){
			wp_enqueue_style('smartlib_animate',  $this->plugin_url . '/assets/animate.css', false);
		}
		wp_enqueue_style('smartlib_main_css',  $this->plugin_url . 'assets/smart-tools.css', false);

	}

    /**
     * Place code for your plugin's functionality here.
     */
    private function run_plugin() {

	}

	public function theme_integration(){

		if(defined('SMART_TEMPLATE_DIRECTORY')){

			$this->oTheme = new Smartlib_Theme_Integration();
		}
	}
}

Smartlib_Tools::get_instance();

/**
 * Function to show admin notice if Shortcodes Ultimate is not installed
 */
function smtool_lack_shortcode_notice() {
	// Check that plugin is installed
	if ( function_exists( 'shortcodes_ultimate' ) ) return;
	// If plugin isn't installed, show notice
	echo '<div class="error">'. __('For full functionality of this theme you need to install and activate plugin', 'smartlib') . ' <strong>Shortcodes Ultimate</strong>'. '<a href="' . admin_url( 'plugin-install.php?tab=search&s=shortcodes+ultimate' ) . '">' .__('Install now', 'smartlib').'</a></div>';
}

function smartlib_flush_rewrite_rules() {

	smartlib_register_post_types();

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'smartlib_flush_rewrite_rules' );
