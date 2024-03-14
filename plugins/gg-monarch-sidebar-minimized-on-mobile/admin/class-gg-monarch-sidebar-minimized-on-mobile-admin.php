<?php
use MSMoMDP\Std\Core\Arr;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://deeppresentation.com
 * @since      1.0.0
 *
 * @package    GG_Monarch_Sidebar_Minimized_On_Mobile
 * @subpackage GG_Monarch_Sidebar_Minimized_On_Mobile/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    GG_Monarch_Sidebar_Minimized_On_Mobile
 * @subpackage GG_Monarch_Sidebar_Minimized_On_Mobile/admin
 * @author     Tomas Groulik <tomas.groulik@gmail.com>
 */
class GG_Monarch_Sidebar_Minimized_On_Mobile_Admin {

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
    
    protected $enquier;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $enquier ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->enquier = $enquier;
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        $this->enquier->enqueue( 'main', "monarchSidebarMinAdminStyle", [ 'css' => true, 'media' => 'all' ] );
	}

    public function enqueue_scripts()
    {
        $assets = $this->enquier->enqueue('main', 'monarchSidebarMinAdmin', ['in_footer' => true] );
        $entry_point = array_pop($assets['js']);
        $config = [
            'siteUrl' => get_site_url(),
            'dpDebugEn' =>  GG_MONARCH_SIDEBAR_MINIMIZED_ON_MOBILE_DP_DEBUG_EN,
            'nonces' => [
                'adminator_nonce' => wp_create_nonce('adminator_nonce'),
                'wp_rest' => wp_create_nonce('wp_rest')
            ]
        ];
        wp_localize_script($entry_point['handle'], 'monarch_admin_config', $config);
    }

}
