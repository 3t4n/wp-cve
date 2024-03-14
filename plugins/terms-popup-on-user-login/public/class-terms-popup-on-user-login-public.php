<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.lehelmatyus.com
 * @since      1.0.0
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/public
 * @author     Lehel Matyus <contact@lehelmatyus.com>
 */
class Terms_Popup_On_User_Login_Public {

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
	private $cache;
	private $popup_type;
	private $woo_public_modal;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->cache = $this->version;
		// $this->cache = strval(round(time()) % 1000);

		$this->popup_type = new TPUL_Popup_Type();
		$this->woo_public_modal = $this->popup_type->is_woo_public_modal();
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
		 * defined in terms_popup_on_user_login_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The terms_popup_on_user_login_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// Front-End Library
		$visibility_manager = new TPUL_Moddal_Visibility_Manager();
		if ($visibility_manager->should_modal_render()) {
			wp_enqueue_style($this->plugin_name . "-micromodal", plugin_dir_url(__FILE__) . 'library/micromodal/micromodal.css', array(), $this->cache, 'all');
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/terms-popup-on-user-login-public.css', array(), $this->cache, 'all');
		}
	}
	public function enqueue_styles_in_footer() {

		// if (is_user_logged_in() || $this->woo_public_modal) {
		$visibility_manager = new TPUL_Moddal_Visibility_Manager();
		if ($visibility_manager->should_modal_render()) {
			$options = get_option('tpul_settings_term_modal_options');
			if (!empty($options['terms_modal_asset_placement']) && $options['terms_modal_asset_placement'] == "styles_in_footer") {
				echo '<link rel="stylesheet" id="terms-popup-on-user-login-micromodal-css-footer" href="/wp-content/plugins/terms-popup-on-user-login/public/library/micromodal/micromodal.css?ver=1.0.54" type="text/css" media="all">';
				echo '<link rel="stylesheet" id="terms-popup-on-user-login-css-footer" href="/wp-content/plugins/terms-popup-on-user-login/public/css/terms-popup-on-user-login-public.css?ver=1.0.54" type="text/css" media="all">';
			}
		}
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
		 * defined in terms_popup_on_user_login_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The terms_popup_on_user_login_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		// if user is logged in or is public modal
		$visibility_manager = new TPUL_Moddal_Visibility_Manager();
		if ($visibility_manager->should_modal_render()) {

			wp_register_script($this->plugin_name . '-micromodal-poly', plugin_dir_url(__FILE__) . 'library/micromodal/micromodal-polyfill.js', array('jquery'), $this->cache, true);
			wp_register_script($this->plugin_name . '-micromodal', plugin_dir_url(__FILE__) . 'library/micromodal/micromodal-0.4.0.min.js', array('jquery', $this->plugin_name . '-micromodal-poly'), $this->cache, true);
			wp_enqueue_script($this->plugin_name . "-micromodal");

			wp_register_script($this->plugin_name . '-cookie', plugin_dir_url(__FILE__) . 'library/cookie/js.cookie.min.js', array('jquery', $this->plugin_name . '-micromodal'), $this->cache, true);
			wp_enqueue_script($this->plugin_name . '-cookie');

			wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/terms-popup-on-user-login-public.js', array('jquery', $this->plugin_name . '-micromodal', 'wp-api-request'), $this->cache, true);
			wp_enqueue_script($this->plugin_name);

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
