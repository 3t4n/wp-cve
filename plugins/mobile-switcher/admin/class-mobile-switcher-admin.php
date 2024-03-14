<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mobile_Switcher
 * @subpackage Mobile_Switcher/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mobile_Switcher
 * @subpackage Mobile_Switcher/admin
 * @author     Timur Khamitov <timurkhamitov@mail.ru>
 */
class Mobile_Switcher_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $mobile_switcher    The ID of this plugin.
     */
    private $mobile_switcher;

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
     * @param      string    $mobile_switcher       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $mobile_switcher, $version )
    {

        $this->mobile_switcher = $mobile_switcher;
        $this->version = $version;
    }

    /**
     * Register callback function for render plugin settings page
     * 
     * @since 1.0.0
     */
    public function add_admin_settings()
    {
        add_menu_page( 'Mobile Switcher | Settings', 'Mobile Switcher', 8, 'mobile_switcher_settings', array( $this, 'display_settings' ), '', 5 );
    }

    /**
     * Display and save plugin settings
     * 
     * @since 1.0.0
     */
    public function display_settings()
    {
        if ( !empty( $_POST ) ) {
            $enabled = sanitize_text_field( $_POST['enabled'] );
            $desktop = sanitize_text_field( $_POST['desktop'] );
            $mobile = sanitize_text_field( $_POST['mobile'] );
            $tablet = sanitize_text_field( $_POST['tablet'] );

            if ( $enabled == 'on' ) {
                update_option( 'mobile_switcher_enabled', TRUE );
            } else {
                update_option( 'mobile_switcher_enabled', FALSE );
            }
            update_option( 'ms_desktop_template', $desktop );
            update_option( 'ms_mobile_template', $mobile );
            update_option( 'ms_tablet_template', $tablet );
            $this->ms_message( __( 'Options saved!', 'mobile-switcher' ) );
        }
        $themes = wp_get_themes();

        $enabled = get_option( 'mobile_switcher_enabled' );

        $desktop = get_option( 'ms_desktop_template' );
        $mobile = get_option( 'ms_mobile_template' );
        $tablet = get_option( 'ms_tablet_template' );

        include_once MOBILE_SWITCHER_PATH . 'admin/partials/plugin-mobile-switcher-display.php';
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mobile_Switcher_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mobile_Switcher_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style( $this->mobile_switcher, plugin_dir_url( __FILE__ ) . 'css/mobile-switcher-admin.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mobile_Switcher_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mobile_Switcher_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script( $this->mobile_switcher, plugin_dir_url( __FILE__ ) . 'js/mobile-switcher-admin.js', array( 'jquery' ), $this->version, false );
    }

    /**
     * Main template filter
     * 
     * @since    1.0.0
     */
    public function mobile_switcher_template( $template )
    {

        return $template;
    }

    /**
     * Main stylesheet filter
     * 
     * @since    1.0.0
     */
    public function mobile_switcher_stylesheet( $stylesheet )
    {
        return $stylesheet;
    }

    /**
     * Function to show WP styled message
     * 
     * @param type $message
     * @param type $errormsg
     */
    public function ms_message( $message, $errormsg = false )
    {
        if ( $errormsg ) {
            echo '<div id="message" class="error">';
        } else {
            echo '<div id="message" class="updated fade">';
        }
        echo "<p><strong>$message</strong></p></div>";
    }

}
