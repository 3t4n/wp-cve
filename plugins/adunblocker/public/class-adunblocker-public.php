<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://digitalapps.com
 * @since      1.0.0
 *
 * @package    AdUnblocker
 * @subpackage AdUnblocker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    AdUnblocker
 * @subpackage AdUnblocker/public
 * @author     Digital Apps <support@digitalapps.com>
 */
class AdUnblocker_Public {

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
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $file_name;
    private $settings;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->settings = $this->get_options_data();

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in AdUnblocker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The AdUnblocker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if( array_key_exists( $this->plugin_name . '-file-name', $this->settings ) &&
            array_key_exists( $this->plugin_name . '-status', $this->settings )) {

                if ( $this->settings[$this->plugin_name . '-status'] == 'y' ) {
                    $wp_upload_dir = wp_upload_dir();
                    wp_enqueue_style( $this->plugin_name, $wp_upload_dir['baseurl'] . '/' . $this->settings[$this->plugin_name . '-file-name'] . '.css', array(), $this->version, 'all' );
                }

        }

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in AdUnblocker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The AdUnblocker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if( array_key_exists( $this->plugin_name . '-file-name', $this->settings ) &&
            array_key_exists( $this->plugin_name . '-status', $this->settings ) ) {

                if ( $this->settings[$this->plugin_name . '-status'] == 'y' ) {
                    $wp_upload_dir = wp_upload_dir();
                    wp_enqueue_script( $this->plugin_name, $wp_upload_dir['baseurl'] . '/' . $this->settings[$this->plugin_name . '-file-name'] . '.js', array(), $this->version, false );
                }
        }

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function localize_script() {

        $nonces = apply_filters( 'daau_nonces', array(
            'get_plugin_data'       => wp_create_nonce( 'get-plugin-data' )
        ) );

        $plugin_data = [];
        $exclude = ['adunblocker-file-name', 'adunblocker-version'];
        foreach ( $this->settings as $key => $value) {
            if( in_array( $key, $exclude ) ) {
                continue;
            }
            $plugin_data[str_replace( 'adunblocker-', '', $key )] = $value;
        }

        $data = apply_filters( 'daau_data', array(
            'this_url'              => esc_html( addslashes( home_url() ) ) . '/wp-admin/admin-ajax.php',
            'nonces'                => $nonces,
            'ui'                    => $plugin_data
        ) );

        // wp_localize_script( $handle, $name, $data );
        wp_localize_script(
            $this->plugin_name,
            $this->settings[$this->plugin_name . '-file-name'],
            $data
        );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function ajax_request_callback() {

        $this->check_ajax_referer( 'get-plugin-data' );
        wp_send_json( $this->settings );
        end_ajax();

    }

    /**
     * @param mixed $return Value to be returned as response.
     *
     * @return null
     */
    function end_ajax( $return = false ) {

        $return = apply_filters( 'daau_before_response', $return );
        echo ( false === $return ) ? '' : $return;
        exit;

    }

    function check_ajax_referer( $action ) {

        $result = check_ajax_referer( $action, 'nonce', false );

        if ( false === $result ) {
            $return = array( 'daau_error' => 1, 'body' => sprintf( __( 'Invalid nonce for: %s', 'adunblocker' ), $action ) );
            $this->end_ajax( json_encode( $return ) );
        }

    }

    public function get_options_data() {

        $settings = array();
        $settings = get_option( $this->plugin_name . '-options' );

        if( ! empty( $settings ) ) {
            $settings[$this->plugin_name . '-content'] = wpautop( $settings[$this->plugin_name . '-content'] );
        }

        return $settings;

    }

    /**
     * Returns the absolute path to the root of the website.
     *
     * @return string
     */
    function get_absolute_root_file_path() {

        static $absolute_path;

        if ( ! empty( $absolute_path ) ) {
            return $absolute_path;
        }

        $absolute_path = rtrim( ABSPATH, '\\/' );
        $site_url      = rtrim( site_url( '', 'http' ), '\\/' );
        $home_url      = rtrim( home_url( '', 'http' ), '\\/' );

        if ( $site_url != $home_url ) {
            $difference = str_replace( $home_url, '', $site_url );
            if ( strpos( $absolute_path, $difference ) !== false ) {
                $absolute_path = rtrim( substr( $absolute_path, 0, - strlen( $difference ) ), '\\/' );
            }
        }

        return $absolute_path;
    }

    /**
     * Get the domain for the current site.
     *
     * @return string
     */
    function get_domain_current_site() {
        if ( ! is_multisite() ) {
            return '';
        }

        $current_site = get_current_site();

        return $current_site->domain;
    }

    /**
     * Check if css and js has been generated for the frontend, if not attempt to create them
     *
     * @since           1.1.13
     */
    public function regenerate_files() {

        $wp_upload_dir = wp_upload_dir();
        $file_name      = $this->settings[$this->plugin_name . '-file-name'];

        if( empty( $file_name ) ) {
            $file_name = AdUnblocker::get_random_string();
            $this->settings[$this->plugin_name . '-file-name'] = $file_name;
            
            add_option( $this->plugin_name . '-options', $this->settings );
        }
        

        $css_file = plugin_dir_path( __DIR__ ) . '/public/css/adunblocker-public.css';
        $js_file  = plugin_dir_path( __DIR__ ) . '/public/js/adunblocker-public.js';

        $new_css_file =  $wp_upload_dir['basedir'] . '/' . $file_name . '.css';
        $new_js_file  =  $wp_upload_dir['basedir'] . '/' . $file_name . '.js';

        if( ! file_exists( $new_css_file ) || ! file_exists( $new_js_file ) ) {
            try {
                if ( file_exists( $css_file ) && ! file_exists( $new_css_file ) ) {
                    copy( $css_file, $new_css_file );
                }
    
                if ( file_exists( $js_file ) && ! file_exists( $new_js_file ) ) {
                    copy( $js_file, $new_js_file );
                }
    
                $contents = '';
                $string_to_replace = "da-adunblocker";
    
                if ( file_exists( $new_css_file ) ) {
                    $contents = file_get_contents( $new_css_file );
                    $contents = str_replace( $string_to_replace, $file_name, $contents );
                }
                file_put_contents( $new_css_file, $contents );
                
                $string_to_replace = "daau_app";
                if ( file_exists( $new_js_file ) ) {
                    $contents = file_get_contents( $new_js_file );
                    $contents = str_replace( $string_to_replace, $file_name, $contents );
                }
                file_put_contents( $new_js_file, $contents );
                
            } catch( Exception $e ) {
    
                // return json_encode( ['response' => ['status' => 'error', 'message' => $e->getMessage()]] );
                // $result = $this->end_ajax( json_encode( ['response' => ['status' => 'error', 'message' => $e->getMessage()]] ) );
    
            }
        }

        // return json_encode( ['response' => ['status' => 'success', 'message' => 'New files have been generated successfully.']] );
        // $result = $this->end_ajax( json_encode( ['response' => ['status' => 'success', 'message' => 'New files have been generated successfully.']] ) );  
    }

    /**
     * Check if running a current version. If new version detected, delete old files.
     *
     * @since           1.0.13
     */
    public function check_version() {

        if( $this->settings[$this->plugin_name . '-version'] != DAAU_PLUGIN_VERSION ) {
            $this->delete_old_files();
            $this->sync_settings();
        }
    }

        /**
     * When new version - delete old css and js.
     *
     * @since           1.1.1
     */
    public function delete_old_files() {
        $wp_upload_dir = wp_upload_dir();

        if ( file_exists( $wp_upload_dir['basedir'] . '/' . $this->settings[$this->plugin_name . '-file-name'] . '.css' ) ) {
            unlink( $wp_upload_dir['basedir'] . '/' . $this->settings[$this->plugin_name . '-file-name'] . '.css' );
        }

        if ( file_exists( $wp_upload_dir['basedir'] . '/' . $this->settings[$this->plugin_name . '-file-name'] . '.js' ) ) {
            unlink( $wp_upload_dir['basedir'] . '/' . $this->settings[$this->plugin_name . '-file-name'] . '.js' );
        }

        $this->settings[$this->plugin_name . '-version'] = DAAU_PLUGIN_VERSION;
        update_option( $this->plugin_name . '-options', $this->settings );
    }

    /**
     * When new version introduces new settings. Init them with defaults.
     *
     * @since           1.1.1
     */
    public function sync_settings() {
        $defaults = AdUnblocker::get_defaults();
        
        if ( ! empty( $this->settings ) ) {
            foreach ( $defaults as $key => $value ) {
                if( ! array_key_exists( $key, $this->settings ) ) {
                    $this->settings[$key] = $value;
                }
            }
        } else {
            $this->settings = $defaults;
        }        

        update_option( $this->plugin_name . '-options', $this->settings );
    }
}
