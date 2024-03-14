<?php
/**
 * Plugin Name.
 *
 * @package   SearchTerms-Admin
 * @author    Julian Magnone <julianmagnone@gmail.com>
 * @license   GPL-2.0+
 * @link      http://magn.com
 * @copyright 2013
 */

/**
 * SearchTerms_Admin.
 *
 * @package SearchTerms_Admin
 * @author  Julian Magnone <julianmagnone@gmail.com>
 */
class SearchTerms_Admin {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   0.1.0
	 *
	 * @var     string
	 */
	protected $version = '0.1.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'searchterms-admin';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		// add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_filter( 'TODO', array( $this, 'filter_method_name' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * TODO:
		 *
		 * Change 'Page Title' to the title of your plugin admin page
		 * Change 'Menu Text' to the text for menu item for the plugin settings page
		 * Change 'plugin-name' to the name of your plugin
		 */
		$this->plugin_screen_hook_suffix = add_plugins_page(
			__( 'Page Title', $this->plugin_slug ),
			__( 'Menu Text', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}
	
	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function admin_init() {
		
		$post_types = get_post_types();
		foreach($post_types as $type)
		//foreach (array('post','page') as $type) 
		{
			add_meta_box('searchterms_admin_metabox', 'SearchTerms', array( $this, 'metabox_setup' ), $type, 'normal', 'high');
		}
		//add_action('save_post','my_meta_save');
	}
	
	function metabox_setup()
	{
		global $wpdb;
		global $post;
	  
		// using an underscore, prevents the meta variable
		// from showing up in the custom fields section
		//$meta = get_post_meta($post->ID,'_my_meta',TRUE);
	  
		// instead of writing HTML here, lets do an include
		//include(MY_THEME_FOLDER . '/custom/searchterms-metabox.php');
		//echo '<textarea id="post-message" name="post_message" placeholder="' . __( 'Enter your post message here. HTML accepted.', 'post-message' ) . '">' . esc_textarea( get_post_meta( $post->ID, 'post_message', true ) ) . '</textarea>';
		
		$post_id = $post->ID;
		
		$table_name = $wpdb->prefix.'stt2_meta';
		
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			echo 'SEO SearchTerms Tagging 2 is not installed.';			
			return FALSE;
		}    
		
		$myrows = $wpdb->get_results( "SELECT post_id, meta_value, meta_count, last_modified FROM {$table_name} WHERE post_id = {$post_id} ORDER BY meta_count DESC" );
		if (!empty($myrows))
		{
			$keywords = array();
			
			foreach($myrows as $row)
			{
				$in_content = !(stristr($post->post_content, $row->meta_value) === FALSE);
				$txt_in_content = ($in_content ? '<img src="'.plugin_dir_url( __FILE__ ).'img/yes.png" title="In content" align="absmiddle" />' : '<img src="'.plugin_dir_url( __FILE__ ).'img/cross.png" title="Not in content"  align="absmiddle" />');

				$keywords[] = array('meta_value' => $row->meta_value, 'meta_count' => $row->meta_count, 'in_content' => $in_content, 'txt_in_content' => $txt_in_content);
			}
			
			echo '<ol>';
			foreach($keywords as $keyword)
			{
				echo "<li>{$keyword['meta_value']} ({$keyword['meta_count']}) {$keyword['txt_in_content']}</li>";
			}
			echo '</ol>';
		} else {
			echo '<p>No search terms were found for this post.</p>';
		}
	  
		// create a custom nonce for submit verification later
		//wp_nonce_field( plugin_basename( __FILE__ ), 'post_message_nonce' );
		echo '<input type="hidden" name="searchterms-admin_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
	}
	

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}

}