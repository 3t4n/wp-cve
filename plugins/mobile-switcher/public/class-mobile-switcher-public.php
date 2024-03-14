<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mobile_Switcher
 * @subpackage Mobile_Switcher/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mobile_Switcher
 * @subpackage Mobile_Switcher/public
 * @author     Timur Khamitov <timurkhamitov@mail.ru>
 */
class Mobile_Switcher_Public
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
     * Current user agent
     * 
     * @since  1.0.0
     * @access private
     * @var    string $user_agent The current user agent
     */
    private $user_agent;

    /**
     * Current user device
     * 
     * @since  1.0.0
     * @access private
     * @var    string $device The current user device
     */
    private $device;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $mobile_switcher       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $mobile_switcher, $version )
    {

        $this->mobile_switcher = $mobile_switcher;
        $this->version = $version;
        $this->device = $this->detect_user_device();
    }

    /**
     * Getting user device
     * 
     * @since 1.0.0
     * @return string
     */
    public function detect_user_device()
    {
        require_once MOBILE_SWITCHER_PATH . 'includes/lib/Mobile_Detect.php';
        $detect = new Mobile_Detect;
        if ( $detect->isTablet() ) {
            return 'tablet';
        } elseif ( $detect->isMobile() ) {
            return 'mobile';
        } else {
            return 'desktop';
        }
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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
        wp_enqueue_style( $this->mobile_switcher, plugin_dir_url( __FILE__ ) . 'css/mobile-switcher-public.css', array(), $this->version, 'all' );
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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
        wp_enqueue_script( $this->mobile_switcher, plugin_dir_url( __FILE__ ) . 'js/mobile-switcher-public.js', array( 'jquery' ), $this->version, false );
    }

    /**
     * Main template filter
     * 
     * @since    1.0.0
     */
    public function mobile_switcher_template( $template )
    {
        $enabled = get_option( 'mobile_switcher_enabled' );
        if ( $enabled ) {
            $option = 'ms_' . $this->device . '_template';
            $template = get_option( $option );
        }
        
        return $template;
    }

    /**
     * Main stylesheet filter
     * 
     * @since    1.0.0
     */
    public function mobile_switcher_stylesheet( $stylesheet )
    {
        $enabled = get_option( 'mobile_switcher_enabled' );
        if ( $enabled ) {
            $option = 'ms_' . $this->device . '_template';
            $stylesheet = get_option( $option );
        }
        return $stylesheet;
    }

}
