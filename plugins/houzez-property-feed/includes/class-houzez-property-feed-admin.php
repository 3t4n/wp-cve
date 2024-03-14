<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Houzez Property Feed Admin Functions
 */
class Houzez_Property_Feed_Admin {

	public function __construct() {

		add_action( 'admin_notices', array( $this, 'admin_error_notices') );

        add_action( 'admin_init', array( $this, 'admin_redirects' ) );

        add_filter( 'houzez_admin_sub_menus', array( $this, 'add_houzez_property_feed_menu_item'), 10, 2 );

        add_filter( "plugin_action_links_" . plugin_basename( HOUZEZ_PROPERTY_FEED_PLUGIN_FILE ), array( $this, 'plugin_add_settings_link' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ), 5 );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 5 );
	}

    /**
     * Output error messages when necessary
     */
    public function admin_error_notices() 
    {            
        global $wpdb;
        
        $error = '';    
        $uploads_dir = wp_upload_dir();
        if( $uploads_dir['error'] === FALSE )
        {
            $uploads_dir_import = $uploads_dir['basedir'] . '/houzez_property_feed_import/';
            
            if ( ! @file_exists($uploads_dir_import) )
            {
                if ( ! @mkdir($uploads_dir_import) )
                {
                    $error = 'Unable to create subdirectory in uploads folder for use by Houzez Property Feed plugin. Please ensure the <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank" title="WordPress Codex - Changing File Permissions">correct permissions</a> are set.';
                }
            }
            else
            {
                if ( ! @is_writeable($uploads_dir_import) )
                {
                    $error = 'The uploads folder is not currently writeable and will need to be before properties can be imported. Please ensure the <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank" title="WordPress Codex - Changing File Permissions">correct permissions</a> are set.';
                }
            }

            $uploads_dir_export = $uploads_dir['basedir'] . '/houzez_property_feed_export/';
            
            if ( ! @file_exists($uploads_dir_export) )
            {
                if ( ! @mkdir($uploads_dir_export) )
                {
                    $error = 'Unable to create subdirectory in uploads folder for use by Houzez Property Feed plugin. Please ensure the <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank" title="WordPress Codex - Changing File Permissions">correct permissions</a> are set.';
                }
            }
            else
            {
                if ( ! @is_writeable($uploads_dir_export) )
                {
                    $error = 'The uploads folder is not currently writeable and will need to be before properties can be exported. Please ensure the <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank" title="WordPress Codex - Changing File Permissions">correct permissions</a> are set.';
                }
            }
        }
        else
        {
            $error = 'An error occured whilst trying to create the uploads folder. Please ensure the <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank" title="WordPress Codex - Changing File Permissions">correct permissions</a> are set. '.$uploads_dir['error'];
        }

        if ( !function_exists('houzez_option') )
        { 
            $error = 'The Houzez theme must be active to use the Houzez Property Feed plugin';
        }
        
        if( $error != '' )
        {
            echo '<div class="error"><p><strong>' . $error . '</strong></p></div>';
        }
    }

    /**
     * Handle redirects to import page after install.
     */
    public function admin_redirects()
    {
        // Setup wizard redirect
        if ( get_transient( '_houzez_property_feed_activation_redirect' ) ) 
        {
            delete_transient( '_houzez_property_feed_activation_redirect' );

            // Don't do redirect if part of multisite, doing batch-activate, or if no permission
            if ( is_network_admin() || isset( $_GET['activate-multi'] ) || ! current_user_can( 'manage_options' ) ) {
                return;
            }

            if ( function_exists('houzez_option') )
            {   
                wp_safe_redirect( admin_url( 'admin.php?page=houzez-property-feed-import' ) );
                exit;
            }
        }
    }

    public function add_houzez_property_feed_menu_item( $submenus, $num )
    {
        $submenus['houzez_import_properties'] = array(
            'houzez_dashboard',
            esc_html__( 'Import Properties', 'houzezpropertyfeed' ),
            esc_html__( 'Import Properties', 'houzezpropertyfeed' ),
            'manage_options',
            'houzez-property-feed-import',
            array( $this, 'admin_page_import' )
        );

        $submenus['houzez_export_properties'] = array(
            'houzez_dashboard',
            esc_html__( 'Export Properties', 'houzezpropertyfeed' ),
            esc_html__( 'Export Properties', 'houzezpropertyfeed' ),
            'manage_options',
            'houzez-property-feed-export',
            array( $this, 'admin_page_export' )
        );

        return $submenus;
    }

    public function plugin_add_settings_link( $links )
    {
        $settings_link = '<a href="' . admin_url('admin.php?page=houzez-property-feed-import') . '">' . __( 'Manage Imports', 'houzezpropertyfeed' ) . '</a>';
        array_push( $links, $settings_link );

        $settings_link = '<a href="' . admin_url('admin.php?page=houzez-property-feed-export') . '">' . __( 'Manage Exports', 'houzezpropertyfeed' ) . '</a>';
        array_push( $links, $settings_link );

        $docs_link = '<a href="https://houzezpropertyfeed.com/documentation/" target="_blank">' . __( 'Docs', 'houzezpropertyfeed' ) . '</a>';
        array_push( $links, $docs_link );

        if ( !class_exists( 'Houzez_Property_Feed_Pro' ) )
        {
            $pro_link = '<a href="https://houzezpropertyfeed.com/#pricing" target="_blank" style="font-weight:700; color:#93003c">' . __( 'Upgrade to PRO', 'houzezpropertyfeed' ) . '</a>';
            array_push( $links, $pro_link );
        }

        return $links;
    }

    public function admin_page_import() 
    {
        global $wpdb, $post;

        $tabs = array(
            '' => __( 'Automatic Imports', 'houzezpropertyfeed' ),
            'logs' => __( 'Logs', 'houzezpropertyfeed' ),
            'settings' => __( 'Settings', 'houzezpropertyfeed' ),
        );

        if ( !class_exists('Houzez_Property_Feed_Pro') ) 
        {
            $tabs['pro'] = __( 'PRO Features', 'houzezpropertyfeed' );
        }
        else
        {
            $tabs['license'] = __( 'License', 'houzezpropertyfeed' );
        }

        $active_tab = ( isset($_GET['tab']) && !empty(sanitize_text_field($_GET['tab'])) ) ? sanitize_text_field($_GET['tab']) : '';

        $options = get_option( 'houzez_property_feed' , array() );

        include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-header.php' );

        switch ( $active_tab )
        {
            case "logs":
            {
                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-primary-nav-import.php' );

                if ( isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'view' )
                {
                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/class-houzez-property-feed-admin-logs-view-import-table.php' );

                    $logs_view_table = new Houzez_Property_Feed_Admin_Logs_View_Import_Table();
                    $logs_view_table->prepare_items();

                    $previous_instance = false;
                    $next_instance = false;

                    $logs = $wpdb->get_results( 
                        "
                        SELECT * 
                        FROM " . $wpdb->prefix . "houzez_property_feed_logs_instance
                        INNER JOIN 
                            " . $wpdb->prefix . "houzez_property_feed_logs_instance_log ON  " . $wpdb->prefix . "houzez_property_feed_logs_instance.id = " . $wpdb->prefix . "houzez_property_feed_logs_instance_log.instance_id
                        WHERE 
                            " . ( ( isset($_GET['import_id']) && !empty((int)$_GET['import_id']) ) ? " import_id = '" . (int)$_GET['import_id'] . "' AND " : "" ) . "
                            instance_id < '" . (int)$_GET['log_id'] . "'
                        GROUP BY " . $wpdb->prefix . "houzez_property_feed_logs_instance.id
                        ORDER BY start_date DESC
                        LIMIT 1
                        "
                    );

                    if ( $logs )
                    {
                        foreach ( $logs as $log ) 
                        {
                            $previous_instance = $log->instance_id;
                        }
                    }

                    $logs = $wpdb->get_results( 
                        "
                        SELECT * 
                        FROM " . $wpdb->prefix . "houzez_property_feed_logs_instance
                        INNER JOIN 
                            " . $wpdb->prefix . "houzez_property_feed_logs_instance_log ON  " . $wpdb->prefix . "houzez_property_feed_logs_instance.id = " . $wpdb->prefix . "houzez_property_feed_logs_instance_log.instance_id
                        WHERE 
                             " . ( ( isset($_GET['import_id']) && !empty((int)$_GET['import_id']) ) ? " import_id = '" . (int)$_GET['import_id'] . "' AND " : "" ) . "
                            instance_id > '" . (int)$_GET['log_id'] . "'
                        GROUP BY " . $wpdb->prefix . "houzez_property_feed_logs_instance.id
                        ORDER BY start_date ASC
                        LIMIT 1
                        "
                    );

                    if ( $logs )
                    {
                        foreach ( $logs as $log ) 
                        {
                            $next_instance = $log->instance_id;
                        }
                    }

                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-logs-view-import.php' );
                }
                else
                {
                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/class-houzez-property-feed-admin-logs-import-table.php' );

                    $logs_table = new Houzez_Property_Feed_Admin_Logs_Import_Table();
                    $logs_table->prepare_items();

                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-logs-import.php' );
                }

                break;
            }
            case "settings":
            {
                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-primary-nav-import.php' );
                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-settings-import.php' );
                break;
            }
            case "license":
            {
                $license_key_status = apply_filters( 'houzez_property_feed_pro_status', array() );

                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-primary-nav-import.php' );
                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-license.php' );
                break;
            }
            case "pro":
            {
                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-primary-nav-import.php' );

                $features = $this->get_pro_features();

                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-pro.php' );
                break;
            }
            default:
            {
                $active_tab = ( isset($_GET['action']) && !empty(sanitize_text_field($_GET['action'])) ) ? sanitize_text_field($_GET['action']) : '';

                if ( $active_tab == 'addimport' || $active_tab == 'editimport' )
                {
                    $import_id = ( isset($_GET['import_id']) && !empty(sanitize_text_field($_GET['import_id'])) ) ? (int)$_GET['import_id'] : false;

                    $frequencies = get_houzez_property_feed_import_frequencies();

                    $import_settings = array();
                    if ( $active_tab == 'editimport' )
                    {
                        $imports = ( isset($options['imports']) && is_array($options['imports']) && !empty($options['imports']) ) ? $options['imports'] : array();
                        if ( isset($imports[$import_id]) )
                        {
                            $import_settings = $imports[$import_id];

                            // ensure frequency is not a PRO one if PRO not enabled
                            if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true )
                            {
                                if ( isset($frequencies[$import_settings['frequency']]['pro']) && $frequencies[$import_settings['frequency']]['pro'] === true )
                                {
                                    $import_settings['frequency'] = 'daily';
                                }
                            }
                        }
                    }

                    $houzez_ptype_settings = get_option('houzez_ptype_settings', array() );

                    // get WP users / authors
                    $wp_users = array();

                    $users = get_users( array( 'orderby' => 'name' ) );
                    foreach ( $users as $user ) 
                    {
                        $wp_users[$user->ID] = $user->display_name;
                    }

                    // get agents
                    $houzez_agents = array();

                    if ( !isset($houzez_ptype_settings['houzez_agents_post']) || ( isset($houzez_ptype_settings['houzez_agents_post']) && $houzez_ptype_settings['houzez_agents_post'] != 'disabled' ) )
                    {
                        $args = array(
                            'post_type' => 'houzez_agent',
                            'nopaging' => true
                        );

                        $agent_query = new WP_Query( $args );

                        if ( $agent_query->have_posts() )
                        {
                            while ( $agent_query->have_posts() )
                            {
                                $agent_query->the_post();

                                $houzez_agents[get_the_ID()] = get_the_title();
                            }
                        }
                        wp_reset_postdata();
                    }

                    // get agencies
                    $houzez_agencies = array();

                    if ( !isset($houzez_ptype_settings['houzez_agencies_post']) || ( isset($houzez_ptype_settings['houzez_agencies_post']) && $houzez_ptype_settings['houzez_agencies_post'] != 'disabled' ) )
                    {
                        $args = array(
                            'post_type' => 'houzez_agency',
                            'nopaging' => true
                        );

                        $agency_query = new WP_Query( $args );

                        if ( $agency_query->have_posts() )
                        {
                            while ( $agency_query->have_posts() )
                            {
                                $agency_query->the_post();

                                $houzez_agencies[get_the_ID()] = get_the_title();
                            }
                        }
                        wp_reset_postdata();
                    }

                    $formats = get_houzez_property_feed_import_formats();

                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-import-settings.php' );
                }
                else
                {
                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-primary-nav-import.php' );

                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/class-houzez-property-feed-admin-automatic-imports-table.php' );

                    $automatic_imports_table = new Houzez_Property_Feed_Admin_Automatic_Imports_Table();
                    $automatic_imports_table->prepare_items();

                    $run_now_button = false;
                    $imports = ( isset($options['imports']) && is_array($options['imports']) && !empty($options['imports']) ) ? $options['imports'] : array();
                    foreach ( $imports as $import_id => $import_settings )
                    {
                        if ( !isset($import_settings['running']) || ( isset($import_settings['running']) && $import_settings['running'] !== true ) )
                        {
                            continue;
                        }

                        if ( isset($import_settings['deleted']) && $import_settings['deleted'] === true )
                        {
                            continue;
                        }

                        $run_now_button = true;
                        continue;
                    }

                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-automatic-imports.php' );
                }
            }
        }

        include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-footer.php' );
    }

    public function admin_page_export() 
    {
        global $wpdb, $post;

        $tabs = array(
            '' => __( 'Automatic Exports', 'houzezpropertyfeed' ),
            'logs' => __( 'Logs', 'houzezpropertyfeed' ),
            'settings' => __( 'Settings', 'houzezpropertyfeed' ),
        );

        if ( !class_exists('Houzez_Property_Feed_Pro') ) 
        {
            $tabs['pro'] = __( 'PRO Features', 'houzezpropertyfeed' );
        }
        else
        {
            $tabs['license'] = __( 'License', 'houzezpropertyfeed' );
        }

        $active_tab = ( isset($_GET['tab']) && !empty(sanitize_text_field($_GET['tab'])) ) ? sanitize_text_field($_GET['tab']) : '';

        $options = get_option( 'houzez_property_feed' , array() );

        include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-header.php' );

        switch ( $active_tab )
        {
            case "logs":
            {
                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-primary-nav-export.php' );

                if ( isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'view' )
                {
                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/class-houzez-property-feed-admin-logs-view-export-table.php' );

                    $logs_view_table = new Houzez_Property_Feed_Admin_Logs_View_Export_Table();
                    $logs_view_table->prepare_items();

                    $previous_instance = false;
                    $next_instance = false;

                    $logs = $wpdb->get_results( 
                        "
                        SELECT * 
                        FROM " . $wpdb->prefix . "houzez_property_feed_export_logs_instance
                        INNER JOIN 
                            " . $wpdb->prefix . "houzez_property_feed_export_logs_instance_log ON  " . $wpdb->prefix . "houzez_property_feed_export_logs_instance.id = " . $wpdb->prefix . "houzez_property_feed_export_logs_instance_log.instance_id
                        WHERE 
                            " . ( ( isset($_GET['export_id']) && !empty((int)$_GET['export_id']) ) ? " export_id = '" . (int)$_GET['export_id'] . "' AND " : "" ) . "
                            instance_id < '" . (int)$_GET['log_id'] . "'
                        GROUP BY " . $wpdb->prefix . "houzez_property_feed_export_logs_instance.id
                        ORDER BY start_date DESC
                        LIMIT 1
                        "
                    );

                    if ( $logs )
                    {
                        foreach ( $logs as $log ) 
                        {
                            $previous_instance = $log->instance_id;
                        }
                    }

                    $logs = $wpdb->get_results( 
                        "
                        SELECT * 
                        FROM " . $wpdb->prefix . "houzez_property_feed_export_logs_instance
                        INNER JOIN 
                            " . $wpdb->prefix . "houzez_property_feed_export_logs_instance_log ON  " . $wpdb->prefix . "houzez_property_feed_export_logs_instance.id = " . $wpdb->prefix . "houzez_property_feed_export_logs_instance_log.instance_id
                        WHERE 
                             " . ( ( isset($_GET['export_id']) && !empty((int)$_GET['export_id']) ) ? " export_id = '" . (int)$_GET['export_id'] . "' AND " : "" ) . "
                            instance_id > '" . (int)$_GET['log_id'] . "'
                        GROUP BY " . $wpdb->prefix . "houzez_property_feed_export_logs_instance.id
                        ORDER BY start_date ASC
                        LIMIT 1
                        "
                    );

                    if ( $logs )
                    {
                        foreach ( $logs as $log ) 
                        {
                            $next_instance = $log->instance_id;
                        }
                    }

                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-logs-view-export.php' );
                }
                else
                {
                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/class-houzez-property-feed-admin-logs-export-table.php' );

                    $logs_table = new Houzez_Property_Feed_Admin_Logs_Export_Table();
                    $logs_table->prepare_items();

                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-logs-export.php' );
                }

                break;
            }
            case "license":
            {
                $license_key_status = apply_filters( 'houzez_property_feed_pro_status', array() );

                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-primary-nav-export.php' );
                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-license.php' );
                break;
            }
            case "settings":
            {
                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-primary-nav-export.php' );
                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-settings-export.php' );
                break;
            }
            case "pro":
            {
                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-primary-nav-export.php' );

                $features = $this->get_pro_features();

                include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-pro.php' );
                break;
            }
            default:
            {
                $active_tab = ( isset($_GET['action']) && !empty(sanitize_text_field($_GET['action'])) ) ? sanitize_text_field($_GET['action']) : '';

                if ( $active_tab == 'addexport' || $active_tab == 'editexport' )
                {
                    $export_id = ( isset($_GET['export_id']) && !empty(sanitize_text_field($_GET['export_id'])) ) ? (int)$_GET['export_id'] : false;

                    $export_settings = array();
                    if ( $active_tab == 'editexport' )
                    {
                        $exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();
                        if ( isset($exports[$export_id]) )
                        {
                            $export_settings = $exports[$export_id];
                        }
                    }

                    $formats = get_houzez_property_feed_export_formats();

                    $frequencies = get_houzez_property_feed_export_frequencies();

                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-export-settings.php' );
                }
                else
                {
                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-primary-nav-export.php' );

                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/class-houzez-property-feed-admin-automatic-exports-table.php' );

                    $automatic_exports_table = new Houzez_Property_Feed_Admin_Automatic_Exports_Table();
                    $automatic_exports_table->prepare_items();

                    $run_now_button = false;
                    $exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();
                    
                    // remove any non-cron formats
                    foreach ( $exports as $export_id => $export_settings  )
                    {
                        $format = get_format_from_export_id( $export_id );
                        if ( isset($format['method']) && ( $format['method'] == 'cron' || $format['method'] == 'url' ) )
                        {

                        }
                        else
                        {
                            unset($exports[$export_id]);
                        }
                    }

                    foreach ( $exports as $export_id => $export_settings )
                    {
                        if ( !isset($export_settings['running']) || ( isset($export_settings['running']) && $export_settings['running'] !== true ) )
                        {
                            continue;
                        }

                        if ( isset($export_settings['deleted']) && $export_settings['deleted'] === true )
                        {
                            continue;
                        }

                        $run_now_button = true;
                        continue;
                    }

                    include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-automatic-exports.php' );
                }
            }
        }

        include( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/views/admin-settings-footer.php' );
    }

    private function get_pro_features()
    {
        return array(
            array(
                'icon' => 'dashicons dashicons-admin-multisite',
                'title' => 'Import unlimited properties',
                'description' => 'Remove the 25 property limit and import/export unlimited properties.'
            ),
            array(
                'icon' => 'dashicons dashicons-clock',
                'title' => 'Imports ran more frequently',
                'description' => 'Choose from daily, twice daily, hourly or every 15 minutes meaning your properties import quicker.'
            ),
            array(
                'icon' => 'dashicons dashicons-admin-comments',
                'title' => 'Priority support',
                'description' => 'Our UK based friendly support team are on hand to answer any of your questions and assist with setting up feeds.'
            ),
            array(
                'icon' => 'dashicons dashicons-database-import',
                'title' => 'Multiple imports and exports',
                'description' => 'Have multiple simultaneous imports and exports running at once. Useful if importing from or exporting to multiple sources.'
            ),
            array(
                'icon' => 'dashicons dashicons-email',
                'title' => 'Email reports',
                'description' => 'Get a report emailed to you each time an import has finished running.'
            ),
            array(
                'icon' => 'dashicons dashicons-database-add',
                'title' => 'Store logs for longer',
                'description' => 'We\'ll store logs for up to seven days making debugging any issues much easier.'
            ),
            array(
                'icon' => 'dashicons dashicons-admin-media',
                'title' => 'Save disk space over time',
                'description' => 'Choose to delete property media when a property comes off of the market to save on disk space.'
            ),
            array(
                'icon' => 'dashicons dashicons-database',
                'title' => 'Download and process media separately',
                'description' => 'Allow imports to complete quicker by opting to process media in a separate background queue.'
            ),
            array(
                'icon' => 'dashicons dashicons-yes',
                'title' => 'Select which properties are exported',
                'description' => 'Get control over which properties are sent to which portals.'
            ),
        );
    }

    /**
     * Enqueue styles
     */
    public function admin_styles() 
    {
        global $wp_scripts;

        if ( isset($_GET['page']) && ( sanitize_text_field($_GET['page']) == 'houzez-property-feed-import' || sanitize_text_field($_GET['page']) == 'houzez-property-feed-export' ) ) 
        {
            wp_enqueue_style( 'houzez_property_feed_admin_styles', untrailingslashit( plugins_url( '/', HOUZEZ_PROPERTY_FEED_PLUGIN_FILE ) ) . '/assets/css/admin.css', array(), HOUZEZ_PROPERTY_FEED_VERSION );
            wp_enqueue_style( 'select2_styles', untrailingslashit( plugins_url( '/', HOUZEZ_PROPERTY_FEED_PLUGIN_FILE ) ) . '/assets/css/select2.min.css', array(), '4.0.13' );
        }
    }


    /**
     * Enqueue scripts
     */
    public function admin_scripts() 
    {
        global $wp_query, $post, $tabs;

        $statuses = array();
        $property_types = array();

        if ( isset($_GET['page']) && ( sanitize_text_field($_GET['page']) == 'houzez-property-feed-import' || sanitize_text_field($_GET['page']) == 'houzez-property-feed-export' ) ) 
        {
            // scripts used throughout import and export
            wp_register_script( 'select2', untrailingslashit( plugins_url( '/', HOUZEZ_PROPERTY_FEED_PLUGIN_FILE ) ) . '/assets/js/select2.min.js', array( 'jquery' ), '4.0.13' );
            wp_enqueue_script( 'select2' );
        }

        if ( isset($_GET['page']) && sanitize_text_field($_GET['page']) == 'houzez-property-feed-import' ) 
        {
            $terms = get_terms( array(
                'taxonomy'   => 'property_status',
                'hide_empty' => false,
            ) );

            if ( is_array($terms) && !empty($terms) )
            {
                foreach ( $terms as $term )
                {
                    $statuses[$term->term_id] = $term->name;
                }
            }

            $terms = get_terms( array(
                'taxonomy'   => 'property_type',
                'hide_empty' => false,
            ) );

            if ( is_array($terms) && !empty($terms) )
            {
                foreach ( $terms as $term )
                {
                    $property_types[$term->term_id] = $term->name;
                }
            }

            // enqueue draggable/droppable for XML field mapping
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'jquery-ui-droppable' );

            wp_register_script( 'houzez_property_feed_admin_import_script', untrailingslashit( plugins_url( '/', HOUZEZ_PROPERTY_FEED_PLUGIN_FILE ) ) . '/assets/js/admin-import.js', array( 'jquery' ), HOUZEZ_PROPERTY_FEED_VERSION );

            $formats = get_houzez_property_feed_import_formats();

            $import_id = ( isset($_GET['import_id']) && !empty(sanitize_text_field($_GET['import_id'])) ) ? (int)$_GET['import_id'] : false;

            $import_settings = array();

            if ( $import_id !== false )
            {
                $options = get_option( 'houzez_property_feed' , array() );

                $imports = ( isset($options['imports']) && is_array($options['imports']) && !empty($options['imports']) ) ? $options['imports'] : array();
                if ( isset($imports[$import_id]) )
                {
                    $import_settings = $imports[$import_id];
                }
            }

            // get fields referenced in import format file to show warning if field also gets mapped in 'Field Mapping' section
            $format_fields_imported_by_default = array();
            foreach ( $formats as $key => $format )
            {
                if ( !isset($formats[$key]['houzez_fields_imported_by_default']) )
                {
                    $formats[$key]['houzez_fields_imported_by_default'] = array();
                }

                $format_file = dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/includes/import-formats/class-houzez-property-feed-format-' . $key . '.php';
                $format_file = str_replace( array( "_local", "_remote" ), "", $format_file);
                $format_file = str_replace( "_", "-", $format_file);
                if ( file_exists($format_file) )
                {
                    $import_file_contents = file_get_contents($format_file);

                    if ( !empty($import_file_contents) )
                    {
                        // get all 'fave_X' fields already imported in format
                        preg_match_all('/\'fave_(.*?)[\']+/', $import_file_contents, $matches);
                        if ( isset($matches[1]) && is_array($matches[1]) && !empty($matches[1]) )
                        {
                            foreach ( $matches[1] as $match )
                            {
                                $formats[$key]['houzez_fields_imported_by_default'][] = 'fave_' . $match;
                            }
                        }

                        // get all taxonomies already mapped
                        preg_match_all('/wp_set_object_terms\((.*?)[\)]+[;]+/', $import_file_contents, $matches);
                        if ( isset($matches[1]) && is_array($matches[1]) && !empty($matches[1]) )
                        {
                            foreach ( $matches[1] as $match )
                            {
                                $explode_set_object_terms = explode(",", $match);
                                if ( count($explode_set_object_terms) >= 3 )
                                {
                                    $taxonomy = str_replace(array('"', "'"), "", trim($explode_set_object_terms[2]));
                                    $formats[$key]['houzez_fields_imported_by_default'][] = trim($taxonomy);
                                }
                            }
                        }
                    }
                    else
                    {
                        //echo 'File ' . $format_file . ' contents empty';
                    }
                }
                else
                {
                    //echo 'File ' . $format_file . ' not found';
                }

                if ( $key != 'xml' && $key != 'csv' )
                {
                    $formats[$key]['houzez_fields_imported_by_default'][] = 'post_title';
                    $formats[$key]['houzez_fields_imported_by_default'][] = 'post_excerpt';
                    $formats[$key]['houzez_fields_imported_by_default'][] = 'post_content';
                }

                $formats[$key]['houzez_fields_imported_by_default'] = array_unique($formats[$key]['houzez_fields_imported_by_default']);
                $formats[$key]['houzez_fields_imported_by_default'] = array_filter($formats[$key]['houzez_fields_imported_by_default']);
            }

            wp_localize_script( 'houzez_property_feed_admin_import_script', 'hpf_admin_object', array( 
                'formats' => $formats,
                'import_settings' => $import_settings,
                'statuses' => $statuses,
                'property_types' => $property_types,
                'houzez_fields_for_field_mapping' => get_houzez_fields_for_field_mapping(),
                'ajax_nonce' => wp_create_nonce("hpf_ajax_nonce"),
            ) );

            wp_enqueue_script( 'houzez_property_feed_admin_import_script' );
        }

        if ( isset($_GET['page']) && sanitize_text_field($_GET['page']) == 'houzez-property-feed-export' ) 
        {
            wp_register_script( 'houzez_property_feed_admin_export_script', untrailingslashit( plugins_url( '/', HOUZEZ_PROPERTY_FEED_PLUGIN_FILE ) ) . '/assets/js/admin-export.js', array( 'jquery' ), HOUZEZ_PROPERTY_FEED_VERSION );

            $formats = get_houzez_property_feed_export_formats();

            $export_id = ( isset($_GET['export_id']) && !empty(sanitize_text_field($_GET['export_id'])) ) ? (int)$_GET['export_id'] : false;

            $export_settings = array();

            if ( $export_id !== false )
            {
                $options = get_option( 'houzez_property_feed' , array() );

                $exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();
                if ( isset($exports[$export_id]) )
                {
                    $export_settings = $exports[$export_id];
                }
            }

            wp_localize_script( 'houzez_property_feed_admin_export_script', 'hpf_admin_object', array( 
                'formats' => $formats,
                'export_settings' => $export_settings,
            ) );

            wp_enqueue_script( 'houzez_property_feed_admin_export_script' );
        }
    }

}

new Houzez_Property_Feed_Admin();