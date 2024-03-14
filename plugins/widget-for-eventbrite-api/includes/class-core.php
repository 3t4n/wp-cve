<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 */
namespace WidgetForEventbriteAPI\Includes;

use  WidgetForEventbriteAPI\Admin\Admin ;
use  WidgetForEventbriteAPI\FrontEnd\FrontEnd ;
use  WidgetForEventbriteAPI\Admin\Admin_Settings ;
use  WidgetForEventbriteAPI\Admin\Admin_Setup_Wizard ;
/**
 * Class Core
 *
 * @package WidgetForEventbriteAPI\Includes
 */
class Core
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     */
    protected  $loader ;
    /**
     * The unique identifier of this plugin.
     */
    protected  $plugin_name ;
    /**
     * The current version of the plugin.
     */
    protected  $version ;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @param \Freemius $freemius Object for freemius.
     */
    public function __construct( $freemius )
    {
        global  $wfea_instance_counter ;
        $wfea_instance_counter = 0;
        $this->plugin_name = 'widget-for-eventbrite-api';
        $this->version = WIDGET_FOR_EVENTBRITE_PLUGIN_VERSION;
        $this->freemius = $freemius;
        $this->utilities = new Utilities();
    }
    
    public function run()
    {
        $this->set_locale();
        $this->settings_pages();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        // set up options
        $options = get_option( 'widget-for-eventbrite-api-settings' );
        
        if ( false === $options ) {
            $options = Admin_Settings::option_defaults( 'widget-for-eventbrite-api-settings' );
            add_option( 'widget-for-eventbrite-api-settings', $options );
        } elseif ( !isset( $options['webhook'] ) ) {
            $options['webhook'] = '';
            update_option( 'widget-for-eventbrite-api-settings', $options );
        }
        
        // upgrade options
        
        if ( $this->freemius->is_free_plan() || !empty($options) && is_array( $options ) && !isset( $options['background_api'] ) ) {
            $options['background_api'] = 0;
            update_option( 'widget-for-eventbrite-api-settings', $options );
        }
        
        
        if ( !empty($options) && is_array( $options ) && !isset( $options['key'] ) ) {
            $key = get_option( 'widget-for-eventbrite-api-settings-api' );
            
            if ( false !== $key ) {
                $options['key'] = $key['key'];
                update_option( 'widget-for-eventbrite-api-settings', $options );
                delete_option( 'widget-for-eventbrite-api-settings-api' );
            }
        
        }
    
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the WidgetForEventbriteAPIi18n class in order to set the domain and to register the hook
     * with WordPress.
     */
    private function set_locale()
    {
        add_action( 'init', function () {
            load_plugin_textdomain( 'widget-for-eventbrite-api', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
        } );
    }
    
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     */
    private function settings_pages()
    {
        $settings = new Admin_Settings( $this->get_plugin_name(), $this->get_version(), $this->freemius );
        add_action( 'admin_menu', array( $settings, 'settings_setup' ) );
        $options = get_option( 'widget-for-eventbrite-api-settings', array(
            'key' => '',
        ) );
        
        if ( empty($options['key']) ) {
            $setup = new Admin_Setup_Wizard();
            add_action( 'admin_menu', array( $setup, 'settings_setup' ) );
        }
    
    }
    
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }
    
    /**
     * Retrieve the version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
    
    /**
     *  Defining all actions that occur in the admin area.
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Admin( $this->get_plugin_name(), $this->get_version() );
        add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );
        add_action( 'admin_notices', array( $plugin_admin, 'display_admin_notice' ) );
        add_action( 'admin_init', array( $plugin_admin, 'set_options' ) );
        add_action( 'wp_ajax_wfea_dismiss_notice', array( $plugin_admin, 'wfea_dismiss_notice' ) );
        add_action( 'widgets_init', array( $plugin_admin, 'register_custom_widgets' ) );
        add_filter( 'site_status_tests', array( $plugin_admin, 'site_status_tests' ) );
    }
    
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     */
    private function define_public_hooks()
    {
        // allow iframe in post content as Eventbright uses it for videos
        add_filter(
            'wp_kses_allowed_html',
            function ( $tags, $context ) {
            if ( 'post' === $context ) {
                $tags['iframe'] = array(
                    'src'             => true,
                    'height'          => true,
                    'width'           => true,
                    'frameborder'     => true,
                    'allowfullscreen' => true,
                );
            }
            return $tags;
        },
            10,
            2
        );
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        $plugin_public = new FrontEnd( $this->get_plugin_name(), $this->get_version(), $this->utilities );
        add_action( 'init', array( $plugin_public, 'register_image_size' ) );
        add_filter(
            'jetpack_photon_skip_for_url',
            array( $plugin_public, 'jetpack_photon_skip_for_url' ),
            9,
            2
        );
        add_filter(
            'wfea_the_content',
            array( $plugin_public, 'wfea_the_content' ),
            10,
            2
        );
        add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ) );
        add_action( 'init', array( $plugin_public, 'add_shortcode' ) );
        add_action( 'wp_head', array( $plugin_public, 'wfea_generate_meta_for_social_media' ), -1 );
    }

}