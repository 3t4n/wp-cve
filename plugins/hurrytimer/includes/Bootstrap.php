<?php

namespace Hurrytimer;

use Hurrytimer\Dependencies\Carbon\Carbon;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http:nabillemsieh.com
 * @since      1.0.0
 *
 * @package    Hurrytimer
 * @subpackage Hurrytimer/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Hurrytimer
 * @subpackage Hurrytimer/includes
 * @author     Nabil Lemsieh <contact@nabillemsieh.com>
 */
class Bootstrap
{
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $pluginName The string used to uniquely identify this plugin.
     */
    protected $pluginName;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->version    = HURRYT_VERSION;
        $this->pluginName = 'hurrytimer';
    }

    public function run()
    {
        $this->load_macros();
        (new I18n())->load_plugin_textdomain();
        Installer::get_instance()->upgrade();
        $this->register_post_type();
        $this->define_admin_hooks();
        $this->define_front_hooks();
        $this->define_ip_logs_cleanup_hook();
    }
    function define_ip_logs_cleanup_hook(){
        add_action( IP_Log_Manager::CLEANUP_ACTION, array( new IP_Log_Manager(), 'cleanup_ip_logs' ) );
    }

    /**
     * Autoload the required dependencies for this plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    public function load_macros()
    {
        Carbon::macro( 'getBrowserTimestamp', function () {
            /** @var Carbon $this */
            return $this->getTimestamp() * 1000;
        } );

        /**
         * @var Carbon $baseDate
         */
        Carbon::macro( 'modifyWeekOfMonth', function ( $baseDate ) {
            /** @var Carbon $this */
            /** @var Carbon $baseDate */
            $date = $this->copy()->nthOfMonth( $baseDate->weekOfMonth, $baseDate->dayOfWeek );
            if ( $date ) {
                $this->nthOfMonth( $baseDate->weekOfMonth, $baseDate->dayOfWeek );
            }
            else{
                $this->nthOfMonth(4, $baseDate->dayOfWeek);
            }
            
            return $this;
        } );

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Admin(
            $this->getPluginName(),
            $this->getVersion()
        );
        add_action( 'admin_menu', [ $plugin_admin, 'menu' ] );
        add_action( 'admin_init', [ $plugin_admin, 'settingsBox' ] );

        //removeIf(pro)
        add_filter( 'add_meta_boxes', [ $plugin_admin, 'upgradeBanner' ] );
        //endRemoveIf(pro)
        add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueueStyles' ] );
        add_action( 'admin_enqueue_scripts', [ $plugin_admin, 'enqueueScripts' ] );
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_front_hooks()
    {

        if ( is_admin() && !( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
            return;
        }
        // Make sure shortcode is recognized.
        $this->register_shortcode();

        if ( hurryt_count_active_campaigns() == 0 ) {
            return;
        }

        $front = new Frontend(
            $this->getPluginName(),
            $this->getVersion()
        );

        $front->init();


    }

    /**
     * Register shortcode callback.
     */
    function register_shortcode()
    {
        $shortcode = new CampaignShortcode();
        $shortcode->init();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function getPluginName()
    {
        return $this->pluginName;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function getVersion()
    {
        return $this->version;
    }

    public function register_post_type()
    {
        $args = array(
            'label'               => __( 'All', 'hurrytimer' ),
            'labels'              => array(
                'name'               => __( 'Campaigns', 'hurrytimer' ),
                'singular_name'      => __( 'Campaign', 'hurrytimer' ),
                'add_new'            => __( 'Add Campaign', 'hurrytimer' ),
                'add_new_item'       => __( 'Add Campaign', 'hurrytimer' ),
                'edit'               => __( 'Edit', 'hurrytimer' ),
                'edit_item'          => __( 'Edit Campaign', 'hurrytimer' ),
                'new_item'           => __( 'New Campaign', 'hurrytimer' ),
                'view'               => __( 'View Campaign', 'hurrytimer' ),
                'view_item'          => __( 'View Campaign', 'hurrytimer' ),
                'search_items'       => __( 'Search Campaign', 'hurrytimer' ),
                'not_found'          => __( 'No Campaign', 'hurrytimer' ),
                'not_found_in_trash' => __(
                    'No campaign found in trash',
                    'hurrytimer'
                ),
                'parent'             => __( 'Parent Campaign', 'hurrytimer' ),
                'menu_name'          => __( 'All Campaigns', 'hurrytimer' ),
            ),
            'public'              => false,
            'show_ui'             => true,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'show_in_menu'        => 'hurrytimer',
            'hierarchical'        => false,
            'show_in_nav_menus'   => false,
            'rewrite'             => false,
            'query_var'           => false,
            'supports'            => array( 'title' ),
            'has_archive'         => false,
            'menu_positon'        => 65,
        );
        register_post_type( HURRYT_POST_TYPE, $args );
    }
}
