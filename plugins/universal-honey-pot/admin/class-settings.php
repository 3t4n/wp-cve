<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://webdeclic.com
 * @since      1.0.0
 *
 * @package    Universal_Honey_Pot
 * @subpackage Universal_Honey_Pot/admin
 */
class Universal_Honey_Pot_Settings {

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
	 * add_settings_menu
	 *
	 * @return void
	 */
	public function add_settings_menu() {
		add_submenu_page( 
			'options-general.php',
			__('Universal Honey Pot Settings', 'universal-honey-pot'),
			__('Universal Honey Pot', 'universal-honey-pot'),
			'manage_options',
			'universal-honey-pot-settings',
			array( $this, 'render_settings_page' )
		);
	}
	
	/**
	 * render_settings_page
	 *
	 * @return void
	 */
	public function render_settings_page() {
		$assets_data = include( UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'public/assets/build/page-settings.asset.php' );
        $version = $assets_data['version'] ?? $this->version;

		wp_enqueue_script( 'universal-honey-pot-settings', UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/assets/build/page-settings.js', array(), $version, true );
		wp_enqueue_style( 'universal-honey-pot-settings', UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/assets/build/page-settings.css', array(), $version );
		
		require_once UNIVERSAL_HONEY_POT_PLUGIN_PATH . 'admin/templates/page-settings.php';
	}
	
	/**
	 * redirect_to_settings_page
	 *
	 * @return void
	 */
	public function redirect_to_settings_page($plugin){
		if( $plugin != 'universal-honey-pot/universal-honey-pot.php' ) return;
		wp_redirect( admin_url( 'options-general.php?page=universal-honey-pot-settings' ) );
		exit;
	}
	
}