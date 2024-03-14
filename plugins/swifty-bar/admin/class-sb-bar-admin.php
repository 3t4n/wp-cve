<?php

/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    sb_bar
 * @subpackage sb_bar/admin
 */
class sb_bar_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $sb_bar    The ID of this plugin.
	 */
	private $sb_bar;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $plugin_settings_tabs = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $sb_bar       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $sb_bar, $version ) {

		$this->sb_bar = $sb_bar;
		$this->version = $version;

		$this->plugin_settings_tabs['general'] = 'General';
		$this->plugin_settings_tabs['enable'] = 'Enable Modules';
		$this->plugin_settings_tabs['premium'] = 'Premium';
	}

	/**
	 * Register the Settings page.
	 *
	 * @since    1.0.0
	 */
	public function sb_bar_admin_menu() {

		 add_options_page( __('Swifty Bar', $this->sb_bar), __('Swifty Bar', $this->sb_bar), 'manage_options', $this->sb_bar, array($this, 'display_plugin_admin_page'));

	}

	/**
	 * Settings - Validates saved options
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function settings_sanitize( $input ) {

		// Initialize the new array that will hold the sanitize values
		$new_input = array();

		if(isset($input)) {
			// Loop through the input and sanitize each of the values
			foreach ( $input as $key => $val ) {

				if($key == 'post-type') { // dont sanitize array
					$new_input[ $key ] = $val;
				} else {
					$new_input[ $key ] = sanitize_text_field( $val );
				}
				
			}

		}

		return $new_input;

	} // sanitize()

	
	/**
	 * Renders Settings Tabs
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	function sb_bar_render_tabs() {
		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : 'general';

		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->sb_bar . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
		}
		echo '</h2>';
	}

	/**
	 * Plugin Settings Link on plugin page
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	function add_settings_link( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'options-general.php?page=sb_bar' ) . '">Settings</a>',
		);
		return array_merge( $links, $mylinks );
	}


	/**
	 * Callback function for the admin settings page.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page(){	

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/sb-bar-admin-display.php';
	}

}
