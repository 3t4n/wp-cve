<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpglob.com/
 * @since      1.0.0
 *
 * @package    Auto_Scroll_For_Reading
 * @subpackage Auto_Scroll_For_Reading/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Auto_Scroll_For_Reading
 * @subpackage Auto_Scroll_For_Reading/admin
 * @author     WP Glob <info@wpglob.com>
 */
class Auto_Scroll_For_Reading_Admin {

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
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook_suffix) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Auto_Scroll_For_Reading_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Auto_Scroll_For_Reading_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');

		if (false !== strpos($hook_suffix, "plugins.php")){
            wp_enqueue_style( $this->plugin_name . '-sweetalert-css', AUTO_SCROLL_FOR_READING_ADMIN_URL . '/css/auto-scroll-for-reading-sweetalert2.min.css', array(), $this->version, 'all');
        }

		if (false === strpos($hook_suffix, $this->plugin_name))
			return;
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/auto-scroll-for-reading-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-font-awesome-min', plugin_dir_url( __FILE__ ) . 'css/auto-scroll-for-reading-font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-font-awesome', plugin_dir_url( __FILE__ ) . 'css/auto-scroll-for-reading-font-awesome.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-settings', plugin_dir_url( __FILE__ ) . 'css/auto-scroll-for-reading-admin-settings.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook_suffix) {

		if (false !== strpos($hook_suffix, "plugins.php")){
            wp_enqueue_script( $this->plugin_name . '-sweetalert-js', AUTO_SCROLL_FOR_READING_ADMIN_URL . '/js/auto-scroll-for-reading-sweetalert2.all.min.js', array('jquery'), $this->version, true );
            wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array( 'jquery' ), $this->version, true );
            wp_localize_script( $this->plugin_name . '-admin', 'AutoSrollForReading', array( 
            	'ajaxUrl' => admin_url( 'admin-ajax.php' )
            ) );
        }

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Auto_Scroll_For_Reading_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Auto_Scroll_For_Reading_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if (false === strpos($hook_suffix, $this->plugin_name))
			return;
		
		wp_enqueue_script('jquery');
		wp_enqueue_script( $this->plugin_name . "-popper", plugin_dir_url(__FILE__) . 'js/popper.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . "-bootstrap", plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/auto-scroll-for-reading-admin.js', array( 'jquery' , 'wp-color-picker'), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-settings', plugin_dir_url( __FILE__ ) . 'js/auto-scroll-for-reading-admin-settings.js', array( 'jquery' ), $this->version, false );
	}

	public function add_plugin_admin_menu(){

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        
        $this->capability = "manage_options";
                
        add_menu_page(
            'Auto Scroll', 
            'Auto Scroll',
            $this->capability,
            $this->plugin_name,
			array($this, 'display_plugin_settings_page'),
            AUTO_SCROLL_FOR_READING_ADMIN_URL . '/images/icons/auto-scroll-for-reading-logo.png',
            '6.21'
        );
    }

	public function add_plugin_general_settings_submenu(){
        $hook_settings = add_submenu_page( 
			$this->plugin_name,
            __('General Settings', $this->plugin_name),
            __('General Settings', $this->plugin_name),
            'manage_options',
            $this->plugin_name,
            array($this, 'display_plugin_settings_page') 
        );
		$this->settings_obj = new Auto_Scroll_Settings_Actions($this->plugin_name);
    }

	public function display_plugin_settings_page(){        
        include_once('partials/settings/auto-scroll-for-reading-settings-display.php');
    }

	public function deactivate_plugin_option(){
        $request_value = $_REQUEST['upgrade_plugin'];
        $upgrade_option = get_option( 'wpb_auto_scroll_upgrade_plugin', '' );
        if($upgrade_option === ''){
            add_option( 'wpb_auto_scroll_upgrade_plugin', $request_value );
        }else{
            update_option( 'wpb_auto_scroll_upgrade_plugin', $request_value );
        }
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode( array( 'option' => get_option( 'wpb_auto_scroll_upgrade_plugin', '' ) ) );
        wp_die();
    }
}
