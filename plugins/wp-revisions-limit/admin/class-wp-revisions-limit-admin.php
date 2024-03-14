<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Wp_Revisions_Limit
 * @subpackage Wp_Revisions_Limit/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Revisions_Limit
 * @subpackage Wp_Revisions_Limit/admin
 * @author     Roger Rodrigo
 */
class Wp_Revisions_Limit_Admin {

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
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Holds the name of WP_POST_REVISIONS constant
	 */
	const WP_POST_REVISIONS = 'WP_POST_REVISIONS';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wp_revisions_limit       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in Wp_Revisions_Limit_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Revisions_Limit_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( isset( $_GET['page'] ) && $_GET['page'] == $this->plugin_name ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-form' );
		}

	}

	/**
	 * Initialize Plugin registering its settings
	 *
	 * @since    1.0.0
	 */
	public function init() {

		register_setting(
			'wp_revisions_limit_group', // Option group
			'revisions_limit_option', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'revisions_limit_section', // ID
			__( 'Revisions Options' ), // Title
			array( $this, 'print_section_info' ), // Callback
			$this->plugin_name // Page
		);

		add_settings_field(
			'revisions_limit', // ID
			__( 'Number of Revisions:' ), // Title 
			array( $this, 'revisions_limit_callback' ), // Callback
			$this->plugin_name, // Page
			'revisions_limit_section' // Section ID
		);   

	}

	/**
	 * Initialize Plugin registering its settings
	 *
	 * @since    1.0.0
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{

		$new_input = array();
		if( isset( $input['revisions_limit'] ) && $input['revisions_limit'] != '' ) {
			$new_input['revisions_limit'] = absint( $input['revisions_limit'] );
		}

		return $new_input;

	}

	/** 
	 * Print the Section text
	 *
	 * @since    1.0.0
	 */
	public function print_section_info() {

		print __( 'Enter the number of revisions that you want to save, 0 to disable revisions:' );

	}

	/** 
	 * Get the settings option array and print one of its values
	 *
	 * @since    1.0.0
	 */
	public function revisions_limit_callback() {

		printf(
			'<input type="text" id="revisions_limit" name="revisions_limit_option[revisions_limit]" value="%s">',
			isset( $this->options['revisions_limit'] ) ? esc_attr( $this->options['revisions_limit'] ) : ''
		);

	}

	/** 
	 * Create menu for Plugin inside Settings menu
	 *
	 * @since    1.0.0
	 */
	public function wp_revisions_limit_menu() {

		add_options_page( __( 'Revisions Limit' ), __( 'Revisions Limit' ), 'manage_options', $this->plugin_name, array( $this, 'admin_page' ) );
	
	}

	/** 
	 * Render Plugin Options Page
	 *
	 * @since    1.0.0
	 */
	public function admin_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if ( !function_exists( 'get_home_path' ) )
			require_once( plugin_dir_path( dirname( __FILE__ ) ) . '../../../wp-admin/includes/file.php' );

		$wp_config_file = trailingslashit( get_home_path() ) . 'wp-config.php';

		$contents = file_get_contents( $wp_config_file );
		$pattern = "define\(( )?'WP_POST_REVISIONS'";
		$pattern = "/^$pattern.*/m";

		if ( !preg_match_all( $pattern, $contents, $matches ) ) {
			require_once 'partials/wp-revisions-limit-admin-display.php';
		} else {
			wp_die( __( 'Constant WP_POST_REVISIONS is already defined in wp-config.php file, remove it to be able to set up a limit for your post revisions.' ) );
		}

	}

	/** 
	 * Add Seetings link on acction links
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		$settings_link = '<a href="' . esc_url( $this->get_page_url() ) . '">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;

	}

	/** 
	 * Define WP_POST_REVISIONS with value stored in Plugin Options
	 *
	 * @since    1.0.0
	 */
	public function define_post_revisions() {

		if ( !defined( self::WP_POST_REVISIONS ) ) {
			$this->load_options();
			
			if ( isset( $this->options['revisions_limit'] )
				&& $this->options['revisions_limit'] != ''
				&& is_numeric( $this->options['revisions_limit'] )
				)
			{
					define( self::WP_POST_REVISIONS, (int)$this->options['revisions_limit'] + 1 );
			}
		}
	}

	/** 
	 * Helper to load Plugin Option from DB
	 *
	 * @since    1.0.0
	 */
	public function load_options() {

		if ( !isset( $this->options ) ) {
			$this->options = get_option( 'revisions_limit_option' );
		}

		return $this->options;

	}

	/** 
	 * Helper to build Page Options URL
	 *
	 * @since    1.0.0
	 */
	private function get_page_url() {

		return admin_url( 'options-general.php?page=' . $this->plugin_name );

	}

}