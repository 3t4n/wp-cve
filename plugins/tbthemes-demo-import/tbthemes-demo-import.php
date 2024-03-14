<?php
/*
Plugin Name: TBThemes Demo Import
Plugin URI: https://wordpress.org/plugins/tbthemes-demo-import/
Description: Demo content import tool for TheBootStrapThemes.
Author: thebootstrapthemes
Author URI:  https://thebootstrapthemes.com/
Version: 2.0.4
Text Domain: tbthemes-demo-import
License: GPL version 2 or later
*/


define( 'DEMO_CONTENT_URL', trailingslashit ( plugins_url('', __FILE__) ) );
define( 'DEMO_CONTENT_PATH', trailingslashit( plugin_dir_path( __FILE__) ) );


class TBThemes_Demo_Content {
    public $dir;
    public $url;
    private static $git_repo = 'bootstrapthemes/bootstrapthemes-demos';
    public $dashboard;
    public $progress;
    static $_instance;

    static function get_instance(){
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance ;
    }

    function __construct(){
        require_once DEMO_CONTENT_PATH.'includes/class-dashboard.php';
        require_once DEMO_CONTENT_PATH.'includes/class-progress.php';
        $this->dashboard = new TBThemes_Demo_Content_Dashboard();
        $this->progress = new TBThemes_Demo_Content_Progress();
        if ( is_admin() ) {
            add_action('init', array($this, 'export'));
        }

    }

    static function get_github_repo() {
        return self::$git_repo;
    }

    static function php_support() {
        return version_compare( PHP_VERSION,  '5.6.20', '>=' );
    }

    /**
     * Handles a side-loaded file in the same way as an uploaded file is handled by media_handle_upload().
     *
     * @param array  $file_array Array similar to a `$_FILES` upload array.
     * @param int    $post_id    The post ID the media is associated with.
     * @param string $desc       Optional. Description of the side-loaded file. Default null.
     * @param array  $post_data  Optional. Post data to override. Default empty array.
     * @return int|object The ID of the attachment or a WP_Error on failure.
     */
    static function media_handle_sideload( $file_array, $post_id, $desc = null, $post_data = array(), $save_attachment = true ) {
        $overrides = array(
            'test_form'=>false,
            'test_type'=>false
        );

        $time = current_time( 'mysql' );
        if ( $post = get_post( $post_id ) ) {
            if ( substr( $post->post_date, 0, 4 ) > 0 )
                $time = $post->post_date;
        }

        $file = wp_handle_sideload( $file_array, $overrides, $time );
        if ( isset($file['error']) )
            return new WP_Error( 'upload_error', $file['error'] );

        $url = $file['url'];
        $type = $file['type'];
        $file = $file['file'];
        $title = preg_replace('/\.[^.]+$/', '', basename($file));
        $content = '';

        if ( $save_attachment ) {
            if (isset($desc)) {
                $title = $desc;
            }

            // Construct the attachment array.
            $attachment = array_merge(array(
                'post_mime_type' => $type,
                'guid' => $url,
                'post_parent' => $post_id,
                'post_title' => $title,
                'post_content' => $content,
            ), $post_data);

            // This should never be set as it would then overwrite an existing attachment.
            unset($attachment['ID']);

            // Save the attachment metadata
            $id = wp_insert_attachment($attachment, $file, $post_id);

            return $id;
        } else {
            return $file;
        }
    }

    /**
     * Available widgets
     *
     * Gather site's widgets into array with ID base, name, etc.
     * Used by export and import functions.
     *
     * @global array $wp_registered_widget_updates
     * @return array Widget information
     */
    static function get_available_widgets() {
        global $wp_registered_widget_controls;

        $widget_controls = $wp_registered_widget_controls;

        $available_widgets = array();

        foreach ( $widget_controls as $widget ) {
            if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes

                $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
                $available_widgets[$widget['id_base']]['name'] = $widget['name'];

            }

        }

        // Sidebar widgets

        return $available_widgets;

    }


    static function get_update_keys() {

        $key = 'demo_contents_customizer_keys';
        $theme_slug = get_option( 'stylesheet' );
        $data = get_option( $key );
        if ( ! is_array( $data ) ) {
            $data = array();
        }
        if ( isset( $data[ $theme_slug ] ) ){
            return $data[ $theme_slug ];
        }

        $r = wp_remote_post( admin_url( 'customize.php' ), array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'cookies' => array(
                    SECURE_AUTH_COOKIE => $_COOKIE[ SECURE_AUTH_COOKIE ],
                    AUTH_COOKIE => $_COOKIE[ AUTH_COOKIE ],
                    LOGGED_IN_COOKIE => $_COOKIE[ LOGGED_IN_COOKIE ],
                )
            )
        );

        if ( is_wp_error( $r ) ) {
            return false;
        } else {
            global $wpdb;

            $row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $key ) );
            $notoptions = wp_cache_get( 'notoptions', 'options' );
            // Has to be get_row instead of get_var because of funkiness with 0, false, null values
            if ( is_object( $row ) ) {
                $value = $row->option_value;
                $data = apply_filters( 'option_' . $key, maybe_unserialize( $value ), $key );
                wp_cache_add( $key, $value, 'options' );
            } else { // option does not exist, so we must cache its non-existence
                if ( ! is_array( $notoptions ) ) {
                    $notoptions = array();
                }
                $notoptions[$key] = true;
                wp_cache_set( 'notoptions', $notoptions, 'options' );

                /** This filter is documented in wp-includes/option.php */
                $data = apply_filters( 'default_option_' . $key, '', $key );
            }

            if ( ! is_array( $data ) ) {
                $data = array();
            }

            if ( isset( $data[ $theme_slug ] ) ) {
                return $data[ $theme_slug ];
            }
        }

        return false;
    }

    /**
     * Generate Widgets export data
     *
     * @return string Export file contents
     */
    static function generate_widgets_export_data() {
        // Get all available widgets site supports
        $available_widgets = self::get_available_widgets();

        // Get all widget instances for each widget
        $widget_instances = array();
        foreach ( $available_widgets as $widget_data ) {

            // Get all instances for this ID base
            $instances = get_option( 'widget_' . $widget_data['id_base'] );

            // Have instances
            if ( ! empty( $instances ) ) {

                // Loop instances
                foreach ( $instances as $instance_id => $instance_data ) {

                    // Key is ID (not _multiwidget)
                    if ( is_numeric( $instance_id ) ) {
                        $unique_instance_id = $widget_data['id_base'] . '-' . $instance_id;
                        $widget_instances[$unique_instance_id] = $instance_data;
                    }

                }

            }

        }

        // Gather sidebars with their widget instances
        $sidebars_widgets = get_option( 'sidebars_widgets' ); // get sidebars and their unique widgets IDs
        $sidebars_widget_instances = array();
        foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

            // Skip inactive widgets
            if ( 'wp_inactive_widgets' == $sidebar_id ) {
                continue;
            }

            // Skip if no data or not an array (array_version)
            if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
                continue;
            }

            // Loop widget IDs for this sidebar
            foreach ( $widget_ids as $widget_id ) {

                // Is there an instance for this widget ID?
                if ( isset( $widget_instances[$widget_id] ) ) {
                    // Add to array
                    $sidebars_widget_instances[$sidebar_id][$widget_id] = $widget_instances[$widget_id];

                }

            }

        }

        return $sidebars_widget_instances;

    }

     static function get_widgets_config_fields( $config ) {
        $_config = array();
        foreach ( $config as $k => $f ) {
            switch ( $f['type'] ) {
                case 'list_cat':
                    $_config[ $f['name'] ] = array(
                        'type' => 'term',
                        'tax' =>  'category'
                    );
                    break;
                case 'group':
                    foreach ( $f['fields'] as $_k => $_f ) {
                        if ( $_f['type'] == 'source' ) {
                            if ( isset( $_f['source']['post_type'] ) ) {
                                $_config[ $_f['name'] ] = 'post';
                            } else {
                                $_config[ $_f['name'] ] = array(
                                    'type' => 'term',
                                    'tax' => $_f['source']['tax']
                                );
                            }
                        } else {
                            $_config[ $_f['name'] ] = $_f['type'];
                        }
                    }

                    $_config[ $f['name'] ] = 'repeater';
                    break;
                default:
                    if ( $f['type'] == 'source' ) {
                        if ( isset( $f['source']['post_type'] ) ) {
                            $_config[ $f['name'] ] = 'post';
                        } else {
                            $_config[ $f['name'] ] = array(
                                'type' => 'term',
                                'tax' => $f['source']['tax']
                            );
                        }
                    } else {
                        $_config[ $f['name'] ] = $f['type'];
                    }
                    break;
            }

        }

        return $_config;
    }


    static function get_widgets_config() {
        global $wp_registered_widget_controls;

        $widget_instances = array();

        foreach ( $wp_registered_widget_controls as $widget_id => $widget ) {
            $base_id = isset($widget['id_base']) ? $widget['id_base'] : null;
            if (!empty($base_id) && !isset($widget_instances[$base_id])) {
                $widget_instances[$base_id] = '';
            }
        }
        global $wp_widget_factory;

        foreach ( $wp_widget_factory->widgets  as $class_name => $object ) {
            $config = array();
            if( method_exists( $object,'config' ) ) {
                $config = $object->config();
            }
            // get_configs
            if( method_exists( $object,'get_configs' ) ) {
                $config = $object->get_configs();
            }

            $widget_instances[$object->id_base] = self::get_widgets_config_fields( $config );
        }

        return $widget_instances;
    }

    /*
     * Generates JSON configuration for export
     */
    static function generate_config() {
        $nav_menu_locations = get_theme_mod( 'nav_menu_locations' );
        // Just update the customizer keys

        $regen_keys = self::get_update_keys();

        $config = array(
            'home_url' => home_url('/'),
            'menus' => $nav_menu_locations,
            'pages' => array(
                'page_on_front'  => get_option( 'page_on_front' ),
                'page_for_posts' => get_option( 'page_for_posts' ),
            ),
            'options' => array(
                'show_on_front' => get_option( 'show_on_front' )
            ),
            'theme_mods' => get_theme_mods(),
            'widgets'       => self::generate_widgets_export_data(),
            'widgets_config' => self::get_widgets_config(),
            'customizer_keys' => $regen_keys
        );

        return json_encode( $config );
    }

    /*
     * Main caller for exporting config.json file
     */
    function export() {
        if ( ! isset( $_REQUEST['export_demo_content'] ) ) {
            return ;
        }
        if ( ! current_user_can( 'export' ) ) {
            return ;
        }

        ob_start();
        ob_end_clean();
        ob_flush();

        /**
         * Filters the export filename.
         *
         * @since 4.4.0
         *
         * @param string $wp_filename The name of the file for download.
         * @param string $sitename    The site name.
         * @param string $date        Today's date, formatted.
         */
        $filename = 'config.json';

        header( 'Content-Description: File Transfer' );
        header( 'Content-Disposition: attachment; filename=' . $filename );
        header( 'Content-Type: application/xml; charset=' . get_option( 'blog_charset' ), true );

        echo TBThemes_Demo_Content::generate_config();
        die();
    }

    /**
     * Check if an item exists out there in the "ether".
     *
     * @param string $url - preferably a fully qualified URL
     * @return boolean - true if it is out there somewhere
     */
    static function url_exists($url) {
        if (($url == '') || ($url == null)) {
            return false;
        }

        if ( strpos( $url, home_url() ) !== false ) {
            $args = array(
                'method' => 'GET',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'cookies' => array(
                    SECURE_AUTH_COOKIE => isset($_COOKIE[SECURE_AUTH_COOKIE]) ? $_COOKIE[SECURE_AUTH_COOKIE] : null,
                    AUTH_COOKIE => isset($_COOKIE[AUTH_COOKIE]) ? $_COOKIE[AUTH_COOKIE] : null,
                    LOGGED_IN_COOKIE => isset($_COOKIE[LOGGED_IN_COOKIE]) ? $_COOKIE[LOGGED_IN_COOKIE] : null,
                )
            );
        } else {
            $args = array();
        }

        $response = wp_remote_get( $url, $args);
        $code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );
        $body = wp_unslash( $body );

        if ( strpos( $body, 'id="error-page"' ) !== false ) {
            return false;
        }
        $accepted_status_codes = array( 200, 301 );
        if ( ! is_wp_error( $response ) && in_array( wp_remote_retrieve_response_code( $response ), $accepted_status_codes ) ) {
            return true;
        }
        return false;
    }

    /**
     * Downloads a URL to a local temporary file using the WordPress HTTP API
     *
     * @return bool
     */
    static function download_file( $url, $name = '', $save_attachment = true ) {
        if ( ! $url || empty ( $url ) ) {
            return false;
        }
        // These files need to be included as dependencies when on the front end.
        require_once (ABSPATH . 'wp-admin/includes/image.php');
        require_once (ABSPATH . 'wp-admin/includes/file.php');
        require_once (ABSPATH . 'wp-admin/includes/media.php');
        $file_array = array();
        // Download file to temp location.
        $file_array['tmp_name'] = download_url( $url );

        // If error storing temporarily, return the error.
        if ( empty( $file_array['tmp_name'] ) || is_wp_error( $file_array['tmp_name'] ) ) {
            return false;
        }

        if ( $name ) {
            $file_array['name'] = $name;
        } else {
            $file_array['name'] = basename( $url );
        }
        // Do the validation and storage stuff.
        $file_path_or_id = self::media_handle_sideload( $file_array, 0, null, array(), $save_attachment );

        // If error storing permanently, unlink.
        if ( is_wp_error( $file_path_or_id ) ) {
            @unlink( $file_array['tmp_name'] );
            return false;
        }
        return $file_path_or_id;
    }

}

if ( is_admin() ) {
    function tbthemes_demo_content__init() {
        new TBThemes_Demo_Content();
    }
    add_action( 'plugins_loaded', 'tbthemes_demo_content__init' );
}



/**
 * Redirect to Demo Import tab of TheBootStrapThemes Dashboard page
 *
 * @param $plugin
 * @param bool|false $network_wide
 */
function tbthemes_demo_content_importer_plugin_activate( $plugin, $network_wide = false ) {
    if ( ! $network_wide &&  $plugin == plugin_basename( __FILE__ ) ) {

        $template_slug = get_option('template');
        if ( strpos( $template_slug, '-pro' ) !== false ) {
            $template_slug = str_replace( '-pro', '', $template_slug );
        }

        $url = add_query_arg(
            array(
                'page' => $template_slug,
                'tab' => 'demo_import',
            ),
            admin_url('themes.php')
        );

        // Check url exists
        if ( TBThemes_Demo_Content::url_exists( $url ) ) {
            wp_redirect($url);
            die();
        }
    }
}
add_action( 'activated_plugin', 'tbthemes_demo_content_importer_plugin_activate', 90, 2 );


// Support Upload XML file
function tbthemes_demo_content_custom_upload_xml($mimes) {
    if ( current_user_can( 'upload_files' ) ) {
    $mimes = array_merge($mimes, array(
        'xml' => 'application/xml',
        'json' => 'application/json'
    ));
    }
    return $mimes;
}
add_filter('upload_mimes', 'tbthemes_demo_content_custom_upload_xml');