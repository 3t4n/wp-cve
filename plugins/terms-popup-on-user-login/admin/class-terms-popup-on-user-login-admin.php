<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.lehelmatyus.com
 * @since      1.0.0
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/admin
 * @author     Lehel Matyus <contact@lehelmatyus.com>
 */
class Terms_Popup_On_User_Login_Admin {

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
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
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
		 * defined in terms_popup_on_user_login_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The terms_popup_on_user_login_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/terms-popup-on-user-login-admin.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . "lhl-admin", plugin_dir_url(__FILE__) . '../vendor/lehelmatyus/wp-lhl-admin-ui/css/wp-lhl-admin-ui.css', array(), '1.0.8', 'all');
		// $lhlAdmin = new WpLHLAdminUi();
		// $lhlAdmin->wp_enqueue_style();

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		// var_dump(esc_url_raw(rest_url())); exit;

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in terms_popup_on_user_login_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The terms_popup_on_user_login_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ($hook == 'profile.php' || $hook == 'user-edit.php') {
			wp_enqueue_script($this->plugin_name . "-profile", plugin_dir_url(__FILE__) . 'js/terms-popup-on-user-login-admin-profile.js', array('jquery'), $this->version, false);
			/**
			 * Pass an OBJ to our Script
			 */
			wp_localize_script($this->plugin_name . "-profile", 'tpulApiSettings', array(
				'root' => esc_url_raw(rest_url()),
				'tpul_nonce' => wp_create_nonce('wp_rest')
			));
		}

		if ($hook == 'settings_page_terms_popup_on_user_login_options') {

			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/terms-popup-on-user-login-admin.js', array('jquery', 'wp-color-picker'), $this->version, false);
			wp_enqueue_script($this->plugin_name . "-charts", plugin_dir_url(__FILE__) . 'js/chart.js', array(), $this->version, false);


			/**
			 * Pass an OBJ to our Script
			 */
			wp_localize_script($this->plugin_name, 'tpulApiSettings', array(
				'root' => esc_url_raw(rest_url()),
				'tpul_nonce' => wp_create_nonce('wp_rest')
			));
		}
	}
}
