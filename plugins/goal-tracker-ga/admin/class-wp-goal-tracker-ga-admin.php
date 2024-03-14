<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wpgoaltracker.com/
 * @since      1.0.0
 *
 * @package    Wp_Goal_Tracker_Ga
 * @subpackage Wp_Goal_Tracker_Ga/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Goal_Tracker_Ga
 * @subpackage Wp_Goal_Tracker_Ga/admin
 * @author     yuvalo <support@wpgoaltracker.com>
 */
class Wp_Goal_Tracker_Ga_Admin
{
    /**
     * The ID of this plugin.
     * Used on slug of plugin menu.
     * Used on Root Div ID for React too.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * The Rest route namespace.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $namespace    The Rest route namespace.
     */
    private  $namespace = 'wp-goal-tracker-ga-setting-api/' ;
    /**
     * The rest version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $rest_version    The rest version of this plugin..
     */
    private  $rest_version = 'v1' ;
    /**
     * The capabilities required to manage this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $rest_version    The rest version of this plugin..
     */
    private  $required_options = 'manage_options' ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name       The name of this plugin.
     * @param      string $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->required_options = 'manage_options';
    }
    
    /**
     * Add Admin Page Menu page.
     *
     * @since    1.0.0
     */
    public function add_admin_menu()
    {
        add_menu_page(
            esc_html__( 'Goal Tracker', 'wp-goal-tracker-ga' ),
            esc_html__( 'Goal Tracker', 'wp-goal-tracker-ga' ),
            $this->required_options,
            $this->plugin_name,
            array( $this, 'add_setting_root_div' ),
            'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48c3ZnIGlkPSJhIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBkPSJtMjQzLjU1LDE4My43NWMtMTA5LjUyLDE2Ljc1LTE2MC4wNiw4MC4zNC0xNTUuMDQsMTUzLjU0LDUuODUsODUuMTcsOTMuNjYsMTM2LjIxLDIwNiwxMTQuMzMsMTI1LjM5LTI0LjQzLDE0Ni40LTExMS4wMiwxMzcuODEtMTY2LjAyLTguNTktNTUtNzMuNjItMTE5LjQ2LTE4OC43Ny0xMDEuODVabS02Ny44NSwyMjMuOTZjLTkuODMtMi40My0xNi40MS05LjgzLTE5LjU4LTE4LjY4LTEwLjg0LTMxLjM5LTE3LjAyLTY1LjUzLTEzLjc3LTk4Ljk0LDIuODQtMjUuMjYsMzcuOC0zMi41NCw1MC42OS0xMS4wOSwxOS41NywyNi42MiwzOS4wNCwxMzguNzYtMTcuMzQsMTI4LjcxWm0yMDAuNi01NC4xN2MtNS42MSwyNC4yOS00MC44MSwyNi44Ny01MC4xMiw0LjE2LTEyLjAzLTMxLjUzLTE4LjAyLTY2LjQ3LTE0LjkyLTEwMC4zNiwyLjc4LTI0Ljc5LDM3LjEtMzEuOTQsNDkuNzUtMTAuODksMTkuMDYsMzIuNiwyMy4wNyw3MC41NSwxNS4zLDEwNy4wOFoiIGZpbGw9IiNmY2ZjZmMiLz48cGF0aCBkPSJtNTEwLjA4LDI1MS41OWMtNC4zNS0yMi42OS0yMy4zOS0zOC40Ni0zOC42MS0zOC45LTEzLjM4LTI5LjAzLTMzLjg2LTU0LTYwLjc3LTczLjY4LTQ2LjM5LTMzLjktMTEyLjEzLTUxLjEyLTE3MS45MS00NS44NmwtOC43Mi04MC43MmMtLjc2LTYuOTctNy4wMS0xMi4wNS0xNC0xMS4yNy00LjU4LjUtOC4yOSwzLjM5LTEwLjExLDcuMjgtMTAuOTMsMy4yOS0xMzAuNiwzNC43MS0xMzAuNDMsNDYuOTUuMTUsMTAuOCwxMTYuMDIsMTcuOTYsMTM1LjY2LDE4Ljg4bDIuNDMsMjIuNTFjLTY4Ljg5LDE0LjA4LTE3OS45Niw2OS40Ni0xODguNTgsMTkyLjM3LTE0LjQ0LDUuMjMtMjcuMjksMjcuMDktMjMuMzIsNTAuMzIsMy42OCwyMS41NiwyMC42NSwzNy4yLDM1LjM1LDM5LjQ0LDEyLjU1LDM0LjE0LDM0LjM2LDYzLjEsNjQuNTQsODUuMiw0NS41OSwzMy40LDEwMy4zNSw0Ni43NywxNTIuNjgsNDYuNzcsMTYuODgsMCwzMi43Ni0xLjU3LDQ2Ljg0LTQuNDMsOTQuMjUtMTkuMTYsMTg3Ljc0LTkwLjYsMTg4LjctMjA0Ljk5LDEzLjQ0LTYuNzMsMjQuNDktMjcuODYsMjAuMjYtNDkuOVptLTIxMi43NSwyMzYuMjFjLTUxLjEyLDEwLjQtMTI3Ljk1LDIuMzUtMTg0LjQ2LTM5LjA0LTI3LjU2LTIwLjE5LTQ2Ljk5LTQ1Ljg3LTU4LjEzLTc2LjY2LDYuMjYtOC43Niw3LjA1LTI0LjY1LDQuMDMtNDIuMzUtMi42Ny0xNS42MS03LjY1LTI4LjkyLTE0LjgzLTM2LjEsNi40NS0xMTIuODQsMTA3Ljg1LTE2NC4yMiwxNzEuNzUtMTc3Ljc5bDEuMDEsOS4zNmMuNjYsNi4wOSw1LjUxLDEwLjcxLDExLjM4LDExLjI4Ljg1LjA4LDEuNzMuMDgsMi42Mi0uMDEsNi45OC0uNzUsMTIuMDItNy4wMiwxMS4yNy0xNGwtMS4xMS0xMC4zMmM1NS4yNC00Ljg0LDExNS44NywxMC45OSwxNTguNjEsNDIuMjQsMjUuMTMsMTguMzcsNDMuNjksNDEuMTQsNTUuNiw2Ny45Ni00Ljc2LDkuMTktNC45MSwyMy45MS0xLjgsNDAuMTMsMy4yNiwxNi45Nyw5LjIyLDMxLjEzLDE3LjYyLDM3LjU0LjYxLDExOS4zMy0xMDUuODYsMTc0LjAxLTE3My41NCwxODcuNzdaIiBmaWxsPSIjZmNmY2ZjIi8+PC9zdmc+'
        );
    }
    
    /**
     * Add Root Div For React.
     *
     * @since    1.0.0
     */
    public function add_setting_root_div()
    {
        $primaryTab = 'Settings';
        $general_settings = wp_goal_tracker_ga_get_options( 'generalSettings' );
        if ( $general_settings['measurementID'] != '' && $general_settings['noSnippet'] == false ) {
            $primaryTab = 'Tracker';
        }
        echo  '<main data-component="plugin-root" class="py-6 pl-0 pr-4" id="' . esc_attr( $this->plugin_name ) . '" data-primary-tab="' . esc_attr( $primaryTab ) . '"></main>' ;
        echo  '<style>
					#wpcontent {
						background: #c3c1ca !important;
					}
					</style>' ;
    }
    
    /**
     * Register the CSS/JavaScript Resources for the admin area.
     *
     * Use Condition to Load it Only When it is Necessary
     *
     * @since    1.0.0
     */
    public function enqueue_resources()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Goal_Tracker_Ga_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Goal_Tracker_Ga_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        $screen = get_current_screen();
        $admin_scripts_bases = array( 'toplevel_page_' . $this->plugin_name );
        if ( !(isset( $screen->base ) && in_array( $screen->base, $admin_scripts_bases )) ) {
            return;
        }
        $dependency = array(
            'lodash',
            'wp-api-fetch',
            'wp-i18n',
            'wp-components',
            'wp-element'
        );
        $static_path = "apps/basic/build/";
        wp_enqueue_script(
            $this->plugin_name,
            WP_CUSTOM_EVENTS_TRACKER_URL . $static_path . 'index.js',
            $dependency,
            $this->version,
            true
        );
        wp_enqueue_style(
            $this->plugin_name,
            WP_CUSTOM_EVENTS_TRACKER_URL . $static_path . 'index.css',
            array( 'wp-components' ),
            $this->version
        );
        global  $wp_roles ;
        $roles = $wp_roles->roles;
        $role_names = array();
        foreach ( $roles as $role_id => $role ) {
            array_push( $role_names, array(
                'id'   => $role_id,
                'name' => $role['name'],
            ) );
        }
        $localize = array(
            'version'            => $this->version,
            'root_id'            => $this->plugin_name,
            'rest'               => array(
            'namespace' => $this->namespace,
            'version'   => $this->rest_version,
        ),
            'wp_roles'           => $role_names,
            'current_user_roles' => wp_get_current_user()->roles,
            'upgradeUrl'         => gtg_fs()->get_upgrade_url(),
        );
        wp_set_script_translations( $this->plugin_name, $this->plugin_name );
        wp_localize_script( $this->plugin_name, 'wpGoalTrackerGa', $localize );
    }
    
    /**
     * Register REST API route.
     *
     * @since    1.0.0
     */
    public function api_init()
    {
        $namespace = $this->namespace . $this->rest_version;
        register_rest_route( $namespace, '/get_general_settings', array( array(
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => array( $this, 'get_general_settings' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/set_general_settings', array( array(
            'methods'             => \WP_REST_Server::EDITABLE,
            'callback'            => array( $this, 'set_general_settings' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/hide_gs_tutorial_section', array( array(
            'methods'             => \WP_REST_Server::EDITABLE,
            'callback'            => array( $this, 'hide_gs_tutorial_section' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/get_video_settings', array( array(
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => array( $this, 'get_video_settings' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/set_video_settings', array( array(
            'methods'             => \WP_REST_Server::EDITABLE,
            'callback'            => array( $this, 'set_video_settings' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/get_ecommerce_tracking_settings', array( array(
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => array( $this, 'get_ecommerce_tracking_settings' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/set_ecommerce_tracking_settings', array( array(
            'methods'             => \WP_REST_Server::EDITABLE,
            'callback'            => array( $this, 'set_ecommerce_tracking_settings' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/get_form_tracking_settings', array( array(
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => array( $this, 'get_form_tracking_settings' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/set_form_tracking_settings', array( array(
            'methods'             => \WP_REST_Server::EDITABLE,
            'callback'            => array( $this, 'set_form_tracking_settings' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/get_events', array( array(
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => array( $this, 'get_events' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/set_event', array( array(
            'methods'             => \WP_REST_Server::EDITABLE,
            'callback'            => array( $this, 'set_event' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/delete_event', array( array(
            'methods'             => \WP_REST_Server::DELETABLE,
            'callback'            => array( $this, 'delete_event' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/get_entire_config', array( array(
            'methods'             => \WP_REST_Server::READABLE,
            'callback'            => array( $this, 'get_entire_config' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
        register_rest_route( $namespace, '/set_entire_config', array( array(
            'methods'             => \WP_REST_Server::EDITABLE,
            'callback'            => array( $this, 'set_entire_config' ),
            'permission_callback' => function () {
            return current_user_can( $this->required_options );
        },
        ) ) );
    }
    
    /**
     * Set Plugin General Settings Tutorial Section visibility
     *
     * @since 1.0.5
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response success.
     */
    public function hide_gs_tutorial_section( \WP_REST_Request $request )
    {
        $params = $request->get_params();
        if ( isset( $params['hideGeneralSettingsTutorial'] ) && $params['hideGeneralSettingsTutorial'] == true ) {
            wp_goal_tracker_ga_set_general_settings_tutorial( true );
        }
        return rest_ensure_response( wp_goal_tracker_ga_get_options( 'generalSettings' ) );
    }
    
    /**
     * Set Plugin General Settings.
     *
     * @since 1.0.2
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Plugin General Settings.
     */
    public function set_general_settings( \WP_REST_Request $request )
    {
        $params = $request->get_params();
        
        if ( isset( $params['generalSettings'] ) ) {
            // If Pro - Update roles with permission to modify plugin options
            
            if ( gtg_fs()->is__premium_only() && isset( $params['generalSettings']['permittedRoles'] ) ) {
                $new_roles = $params['generalSettings']['permittedRoles'];
                $old_roles = wp_goal_tracker_ga_get_options( 'generalSettings' )['permittedRoles'];
                // Remove old permitted roles assign new ones
                Wp_Goal_Tracker_Ga::remove_caps( $old_roles );
                Wp_Goal_Tracker_Ga::add_caps( $new_roles );
            }
            
            wp_goal_tracker_ga_set_general_settings( $params['generalSettings'] );
        }
        
        return rest_ensure_response( wp_goal_tracker_ga_get_options( 'generalSettings' ) );
    }
    
    /**
     * Get General Settings
     *
     * @since 1.0.2
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Plugin General Settings.
     */
    public function get_general_settings( \WP_REST_Request $request )
    {
        $default_options = wp_goal_tracker_ga_default_options();
        $hide_general_settings_tutorial = wp_goal_tracker_ga_get_options( 'hideGeneralSettingsTutorial' );
        $general_settings = array_merge( $default_options["generalSettings"], wp_goal_tracker_ga_get_options( 'generalSettings' ) );
        $general_settings['hideGeneralSettingsTutorial'] = $hide_general_settings_tutorial;
        return rest_ensure_response( $general_settings );
    }
    
    /**
     * Set Video Settings.
     *
     * @since 1.0.2
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Plugin Video Settings.
     */
    public function set_video_settings( \WP_REST_Request $request )
    {
        $params = $request->get_params();
        if ( isset( $params['videoSettings'] ) ) {
            //wp_goal_tracker_ga_delete_options();
            wp_goal_tracker_ga_set_video_settings( $params['videoSettings'] );
        }
        return rest_ensure_response( wp_goal_tracker_ga_get_options( 'videoSettings' ) );
    }
    
    /**
     * Get Video settings
     *
     * @since 1.0.2
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Plugin Video Settings.
     */
    public function get_video_settings( \WP_REST_Request $request )
    {
        return rest_ensure_response( wp_goal_tracker_ga_get_options( 'videoSettings' ) );
    }
    
    /**
     * Set Form Tracking Settings.
     *
     * @since 1.0.10
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Plugin Video Settings.
     */
    public function set_form_tracking_settings( \WP_REST_Request $request )
    {
        $params = $request->get_params();
        if ( isset( $params['formTrackingSettings'] ) ) {
            //wp_goal_tracker_ga_delete_options();
            wp_goal_tracker_ga_set_form_tracking_settings( $params['formTrackingSettings'] );
        }
        return rest_ensure_response( wp_goal_tracker_ga_get_options( 'formTrackingSettings' ) );
    }
    
    /**
     * Get Form Tracking settings
     *
     * @since 1.0.10
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Plugin Video Settings.
     */
    public function get_form_tracking_settings( \WP_REST_Request $request )
    {
        return rest_ensure_response( wp_goal_tracker_ga_get_options( 'formTrackingSettings' ) );
    }
    
    /**
     * Set Ecommerce Tracking Settings.
     *
     * @since 1.0.17
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Plugin Video Settings.
     */
    public function set_ecommerce_tracking_settings( \WP_REST_Request $request )
    {
        $params = $request->get_params();
        if ( isset( $params['ecommerceTrackingSettings'] ) ) {
            //wp_goal_tracker_ga_delete_options();
            wp_goal_tracker_ga_set_ecommerce_tracking_settings( $params['ecommerceTrackingSettings'] );
        }
        return rest_ensure_response( wp_goal_tracker_ga_get_options( 'ecommerceTrackingSettings' ) );
    }
    
    /**
     * Get Ecommerce Tracking settings
     *
     * @since 1.0.17
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Plugin Video Settings.
     */
    public function get_ecommerce_tracking_settings( \WP_REST_Request $request )
    {
        return rest_ensure_response( wp_goal_tracker_ga_get_options( 'ecommerceTrackingSettings' ) );
    }
    
    /**
     * Set Click Config.
     *
     * @since 1.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Configuration.
     */
    public function set_event( \WP_REST_Request $request )
    {
        $params = $request->get_params();
        
        if ( isset( $params['type'] ) && isset( $params['config'] ) ) {
            $ID = ( isset( $params['ID'] ) ? $params['ID'] : 0 );
            $type = $params['type'];
            $config = $params['config'];
            $result = wp_goal_tracker_ga_set_config( $type, $ID, $config );
            // Check if the result is a WP_Error and return it if so
            if ( is_wp_error( $result ) ) {
                return $result;
            }
            $update_cache_result = wp_goal_tracker_ga_update_cache_settings();
            if ( is_wp_error( $update_cache_result ) ) {
                return $update_cache_result;
            }
            return rest_ensure_response( wp_goal_tracker_ga_get_config( $type, $ID ) );
        } else {
            // Return an error if required parameters are missing
            return new WP_Error( 'missing_parameters', 'Missing required parameters.', [
                'status' => 400,
            ] );
        }
    
    }
    
    /**
     * Delete Click Custom Event.
     *
     * @since 1.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Configuration.
     */
    public function delete_event( \WP_REST_Request $request )
    {
        $params = $request->get_params();
        // error_log(var_dump($params));
        $ID = ( isset( $params['id'] ) ? $params['id'] : 0 );
        $del = wp_goal_tracker_ga_delete_event( $ID );
        wp_goal_tracker_ga_update_cache_settings();
        return rest_ensure_response( $del );
    }
    
    /**
     * Get Click Config
     *
     * @since 1.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Click Configuration.
     */
    public function get_events( \WP_REST_Request $request )
    {
        $params = $request->get_params();
        
        if ( isset( $params['type'] ) ) {
            $type = $params['type'];
            wp_goal_tracker_ga_get_config( $type, '' );
        }
        
        return rest_ensure_response( wp_goal_tracker_ga_get_config( $type ) );
    }
    
    /**
     * Get Entire Config
     *
     * @since 1.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Entire Configuration.
     */
    public function get_entire_config( \WP_REST_Request $request )
    {
        $default_options = wp_goal_tracker_ga_default_options();
        $options = array_merge( $default_options, wp_goal_tracker_ga_get_options() );
        return rest_ensure_response( $options );
    }
    
    /**
     * Set Entire Config.
     *
     * @since 1.0.0
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return array|WP_REST_Response Configuration.
     */
    public function set_entire_config( \WP_REST_Request $request )
    {
        $params = $request->get_params();
        
        if ( isset( $params['config'] ) ) {
            $config = $params['config'];
            $status = wp_goal_tracker_ga_set_entire_config( $config );
            wp_goal_tracker_ga_update_cache_settings();
        }
        
        
        if ( $status == false ) {
            return "{update : false}";
        } else {
            return rest_ensure_response( wp_goal_tracker_ga_get_options() );
        }
    
    }
    
    public function gtga_show_review_notice()
    {
        // Check if the user has already dismissed the notice
        
        if ( !get_option( 'gtga_review_notice_dismissed' ) ) {
            // Check if the plugin has been activated for 7 days
            $activation_timestamp = get_option( 'gtga_activation_timestamp' );
            $current_timestamp = time();
            if ( $current_timestamp - $activation_timestamp >= 604800 ) {
                ?>

<div class="notice notice-info is-dismissible" id="gtga-review-notice">
  <p>
    We'd be super grateful if you could help us spread the word about Goal Tracker and <a style="font-weight: bold;"
      target="_blank" href="https://wordpress.org/support/plugin/goal-tracker-ga/reviews/#new-post">Give it a ★★★★★
      Rating
    </a> on WordPress?

  </p>
</div>
<script>
jQuery(document).ready(function($) {
  $(document).on('click', '#gtga-review-notice .notice-dismiss', function() {
    $.ajax({
      url: ajaxurl,
      data: {
        action: 'gtga_dismiss_review_notice'
      }
    });
  });
});
</script>
<?php 
            }
        }
    
    }
    
    public function gtga_dismiss_review_notice()
    {
        update_option( 'gtga_review_notice_dismissed', true );
    }

}