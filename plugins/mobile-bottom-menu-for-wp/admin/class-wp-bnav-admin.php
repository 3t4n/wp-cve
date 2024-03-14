<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://boomdevs.com
 * @since      1.0.0
 *
 * @package    Wp_Bnav
 * @subpackage Wp_Bnav/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Bnav
 * @subpackage Wp_Bnav/admin
 * @author     BOOM DEVS <contact@boomdevs.com>
 */
class Wp_Bnav_Admin {

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
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Bnav_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bnav_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-bnav-admin.css', array(), $this->version, 'all' );

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
		 * defined in Wp_Bnav_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bnav_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-bnav-admin.js', array( 'jquery' ), $this->version, false );

        // Localize script
        wp_localize_script( $this->plugin_name, 'wp_bnav_messages', array(
            'skin_change_confirmation_alert' => __( 'This is an irreversible action, Do you really want to import this skin?', 'wp-bnav' ),
            'skin_change_alert' => __( 'You have successfully imported a skin.', 'wp-bnav' ),
        ) );

        wp_localize_script( $this->plugin_name, 'wp_bnav', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'action' => 'set_premade_skin',
                'nonce' => wp_create_nonce( 'set_premade_skin' ),
            )
        );

	}

	    /**
     * Custom links for pro buttons
     *
     * @param $actions
     * @return array
     */
    function wp_bnav_add_action_plugin( $actions ) {

        if (WP_BNAV_Utils::isProActivated()) {
            $settinglinks = array(
                '<a class="wp_bnav_setting_button" href="'.esc_url(admin_url('/customize.php')).'">' . __( 'Settings', 'wp-bnav' ) . '</a>',
            );

        }else{
            $settinglinks = array(
                '<a class="wp_bnav_setting_button" href="'.esc_url(admin_url('/admin.php?page=wp-bnav-settings#tab=general-settings')).'">' . __( 'Settings', 'wp-bnav' ) . '</a>',
            );
            $pro_link = array(
                '<a class="wp_bnav_pro_button" target="_blank" href="'.esc_url('https://boomdevs.com/products/wp-mobile-bottom-menu/#price').'">' . __( 'Go Pro', 'wp-bnav' ) . '</a>',
            );
        }

        if (WP_BNAV_Utils::isProActivated()) {
            $actions = array_merge( $actions, $settinglinks); 
        }else{
            $actions = array_merge( $actions, $settinglinks, $pro_link );
        }

        return $actions;
    }

}
