<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Force_Reinstall
 * @subpackage Force_Reinstall/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Force_Reinstall
 * @subpackage Force_Reinstall/admin
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Force_Reinstall_Admin
{

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
	 * @return void
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function enqueue_styles()
	{
		wp_register_style($this->plugin_name . "-rate", plugin_dir_url(__FILE__) . 'css/force-reinstall-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @return void
	 */
	public function enqueue_scripts()
	{

		wp_register_script($this->plugin_name . "-rate", plugin_dir_url(__FILE__) . 'js/force-reinstall-rate.js', array('jquery'), $this->version, false);
		wp_localize_script($this->plugin_name . "-rate", "force_reinstall", array(
			"ajax_url" => admin_url('admin-ajax.php'),
			"name" => $this->plugin_name
		));
	}

	/**
	 * Add plugins page actions
	 * 
	 * @since 1.0.0
	 * @version 1.0.1
	 * @param array $actions
	 * @param string $plugin_file
	 * @param array $plugin_data
	 * @return array
	 */
	function plugin_action_links($actions, $plugin_file, $plugin_data)
	{

		if ((isset($plugin_data["url"]) && strlen($plugin_data["url"]) != 0) &&  (isset($plugin_data["package"]) && strlen($plugin_data["package"]) != 0)) {

			//target same page with plugin name to keep arguments
			$arguments = 				array(
				$this->plugin_name => $plugin_file,
				$this->plugin_name . "-target" => "plugin"
			);

			$target = force_reinstall_target_self($arguments);

			$link =  sprintf(
				'<a href="%s">%s</a>',
				esc_attr($target),
				__('Refresh Plugin', $this->plugin_name)
			);

			array_push($actions, $link);
		}

		return $actions;
	}

	/**
	 * Handle requested action
	 * 
	 * @since 1.0.0
	 * @version 1.0.1
	 * @return void
	 */
	function handle_action()
	{

		if (wp_verify_nonce(filter_input(INPUT_GET, $this->plugin_name . "-nonce"), $this->plugin_name)) {

			switch (filter_input(INPUT_GET, $this->plugin_name . "-target")) {
				case "plugin":
				case "theme":

					$target =	filter_input(INPUT_GET, $this->plugin_name);

					if ($target) {

						$this->_force_update($target);

						//remove our args and redirect
						wp_redirect(remove_query_arg(array("action", $this->plugin_name . "-nonce", $this->plugin_name, $this->plugin_name . "-target"), $_SERVER['REQUEST_URI']));
						exit;
					}
					break;
				default:
			}
		}
	}

	/**
	 * Force an update on requested item
	 *
	 * @since 1.0.0
	 * @version 1.0.1
	 * @param string $target
	 * @return void
	 */
	private function _force_update($target)
	{
		/**
		 * Modify Site transient
		 */
		$updates =  get_site_transient('update_' . get_current_screen()->base);

		//check if we don't already need an update or can be...
		if (isset($updates->no_update[$target])) {

			$item = $updates->no_update[$target];

			//remove from no_updates
			unset($updates->no_update[$target]);

			//add to updates
			$updates->response[$target] = $item;

			//stall update checking
			$updates->last_checked = time();

			//save
			set_site_transient('update_' . get_current_screen()->base, $updates);
		}
	}

	/**
	 * Add Plugins bulk action
	 *
	 * @since 1.0.0
	 * @version 1.0.1
	 * @param array $actions
	 * @return array
	 */
	public function add_plugins_bulk_action($actions)
	{

		global $wp_version;

		//https://developer.wordpress.org/reference/hooks/handle_bulk_actions-screen/
		if (version_compare($wp_version, "4.7", ">=")) {
			$actions[$this->plugin_name] = __('Refresh Plugin', $this->plugin_name);
		}
		return $actions;
	}

	/**
	 * Force update on plugins
	 *
	 * @since 1.0.0
	 * @version 1.0.1
	 * @param string $redirect
	 * @param string $action
	 * @param array $plugins
	 * @return string
	 */
	public function plugins_bulk_update($redirect, $action, $plugins)
	{

		if ($action == $this->plugin_name) {

			foreach ($plugins as $plugin) {
				$this->_force_update($plugin);
			}
		}

		return admin_url("update-core.php");
	}

	/**
	 * Show rating request
	 *
	 * @since 1.0.0
	 * @version 1.0.1
	 * @return void
	 */
	public function show_rating()
	{
		/**
		 * Request Rating
		 */
		if (boolval(get_transient($this->plugin_name . "-rate")) === false) {
			wp_enqueue_script($this->plugin_name . "-rate");
			wp_enqueue_style($this->plugin_name . "-rate");

			include plugin_dir_path(__FILE__) . "partials/force-reinstall-admin-rating.php";
		}
	}
}