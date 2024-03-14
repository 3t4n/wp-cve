<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/acewebx/#content-plugins
 * @since      1.0.0
 *
 * @package    Ace_Woo_Ajax_Cart_Count
 * @subpackage Ace_Woo_Ajax_Cart_Count/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ace_Woo_Ajax_Cart_Count
 * @subpackage Ace_Woo_Ajax_Cart_Count/admin
 * @author     AceWebX Team <developer@acewebx.com>
 */
class Ace_Woo_Ajax_Cart_Count_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $pluginName    The ID of this plugin.
	 */
	private $pluginName;

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
	 * @param      string    $pluginName       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($pluginName, $version)
	{

		$this->pluginName = $pluginName;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function aceEnqueueStyles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ace_Woo_Ajax_Cart_Count_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ace_Woo_Ajax_Cart_Count_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->pluginName, plugin_dir_url(__FILE__) . 'css/ace-woo-ajax-cart-count-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function aceEnqueueScripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ace_Woo_Ajax_Cart_Count_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ace_Woo_Ajax_Cart_Count_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->pluginName, plugin_dir_url(__FILE__) . 'js/ace-woo-ajax-cart-count-admin.js', array('jquery'), $this->version, false);
	}

	function aceAjaxCartCountRegisterSettings()
	{
		register_setting('imsAjaxCartCount_optionsGroup', 'imsAjaxCartCount_optionIcon', ['default' => 'fa-shopping-cart']);
		register_setting('imsAjaxCartCount_optionsGroup', 'imsAjaxCartCount_optionColor', ['default' => '#000000']);
		register_setting('imsAjaxCartCount_optionsGroup', 'imsAjaxCartCount_optionFontSize', ['default' => '13']);
	}

	function aceAjaxCartCountOptionsPage()
	{
		add_options_page('Ace Cart Count Setting', 'Ace Cart Count', 'manage_options', 'aceAjaxCartCountSetting', array($this, 'aceAjaxCartCountSetting'));
	}

	function aceAjaxCartCountSetting()
	{
		include('partials/ace-woo-ajax-cart-count-admin-display.php');
	}

	function aceAjaxCartCountRowMeta($links, $file)
	{

		if (strpos($file, 'WooAjaxCartCount.php') !== false) {
			$new_links = array(
				'aceAjaxCartCountSetting' => '<a href="options-general.php?page=aceAjaxCartCountSetting">Settings</a>',
			);

			$links = array_merge($links, $new_links);
		}

		return $links;
	}
}
