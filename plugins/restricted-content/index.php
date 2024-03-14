<?php

/**
 * Plugin Name: Restrict - membership, site, content and user access restrictions for WordPress
 * Plugin URI: https://restrict.io/
 * Description: Easily restrict access to the content on your website to logged in users, members with a specific role or capability, to it's author, Tickera users, WooCommerce or Easy Digital Downloads members who made any purchases or purchased a specific item.
 * Author: Restrict
 * Author URI: https://restrict.io/
 * Version: 2.2.6
 * Text Domain: rsc
 * Domain Path: languages
 * Copyright 2020 Tickera (https://tickera.com/)
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !function_exists( 'restrict_fs' ) ) {
    /**
     * Create a helper function for easy SDK access.
     *
     * @return Freemius
     * @throws Freemius_Exception
     */
    function restrict_fs()
    {
        global  $restrict_fs ;
        
        if ( !isset( $restrict_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_6013_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_6013_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $restrict_fs = fs_dynamic_init( array(
                'id'             => '6013',
                'slug'           => 'restricted-content',
                'premium_slug'   => 'restricted-content-pro',
                'type'           => 'plugin',
                'public_key'     => 'pk_850e333579e4ba2ac0eb27a9f33a6',
                'is_premium'     => false,
                'premium_suffix' => 'PRO',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 7,
                'is_require_payment' => true,
            ),
                'menu'           => array(
                'slug'    => 'restricted_content_settings',
                'contact' => true,
                'support' => false,
                'pricing' => true,
                'account' => true,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $restrict_fs;
    }
    
    // Init Freemius.
    restrict_fs();
    // Signal that SDK was initiated.
    do_action( 'restrict_fs_loaded' );
}


if ( !class_exists( 'Restricted_Content' ) ) {
    class Restricted_Content
    {
        var  $version = '2.2.6' ;
        var  $title = 'Restrict' ;
        var  $name = 'rsc' ;
        var  $dir_name = '' ;
        var  $location = 'plugins' ;
        var  $plugin_dir = '' ;
        var  $plugin_url = '' ;
        /**
         * Collection of Woocommerce Settings
         * Initialize in init_vars function
         *
         * @var array
         * @since 2.2.6
         */
        static  $woocommerce_settings = array() ;
        function __construct()
        {
            $this->set_plugin_dir();
            $this->init_vars();
            add_action( 'plugins_loaded', array( $this, 'localization' ), 9 );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_header' ) );
            add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
            add_action( 'save_post', array( $this, 'save_metabox_values' ) );
            add_filter( 'the_content', array( $this, 'maybe_block_content' ) );
            add_filter( 'rsc_the_content', array( $this, 'maybe_block_content' ) );
            add_filter(
                'plugin_action_links_' . plugin_basename( __FILE__ ),
                array( $this, 'plugin_action_links' ),
                10,
                2
            );
            add_shortcode( 'RSC', array( $this, 'rsc_shortcode' ) );
            add_action( 'admin_menu', array( $this, 'rc_add_admin_menu' ) );
            add_filter(
                'first_rc_menu_handler',
                array( $this, 'first_rc_menu_handler' ),
                10,
                1
            );
            add_action( 'admin_enqueue_scripts', array( $this, 'rsc_admin_header' ) );
            $this->name = apply_filters( 'rsc_plugin_name', $this->name );
            // Load general settings class
            require_once $this->plugin_dir . 'includes/classes/class-settings-general.php';
            require_once $this->plugin_dir . 'includes/classes/class-fields.php';
            require_once $this->plugin_dir . 'includes/admin-functions.php';
            require_once $this->plugin_dir . 'includes/freeaddons/comments.php';
            require_once $this->plugin_dir . 'includes/freeaddons/woocommerce-shop-page.php';
            require_once $this->plugin_dir . 'includes/freeaddons/siteorigin-integration.php';
            require_once $this->plugin_dir . 'includes/freeaddons/simple-urls.php';
        }
        
        function set_plugin_dir()
        {
            $dir = plugin_basename( __FILE__ );
            $this->dir_name = str_replace( array( '/index.php', '\\index.php' ), '', $dir );
        }
        
        /**
         * setup proper directories
         * @return void [type]
         */
        function init_vars()
        {
            
            if ( defined( 'WP_PLUGIN_URL' ) && defined( 'WP_PLUGIN_DIR' ) && file_exists( WP_PLUGIN_DIR . '/' . $this->dir_name . '/' . basename( __FILE__ ) ) ) {
                $this->location = 'subfolder-plugins';
                $this->plugin_dir = WP_PLUGIN_DIR . '/' . $this->dir_name . '/';
                $this->plugin_url = plugins_url( '/', __FILE__ );
            } elseif ( defined( 'WP_PLUGIN_URL' ) && defined( 'WP_PLUGIN_DIR' ) && file_exists( WP_PLUGIN_DIR . '/' . basename( __FILE__ ) ) ) {
                $this->location = 'plugins';
                $this->plugin_dir = WP_PLUGIN_DIR . '/';
                $this->plugin_url = plugins_url( '/', __FILE__ );
            } elseif ( is_multisite() && defined( 'WPMU_PLUGIN_URL' ) && defined( 'WPMU_PLUGIN_DIR' ) && file_exists( WPMU_PLUGIN_DIR . '/' . basename( __FILE__ ) ) ) {
                $this->location = 'mu-plugins';
                $this->plugin_dir = WPMU_PLUGIN_DIR;
                $this->plugin_url = WPMU_PLUGIN_URL;
            } else {
                wp_die( sprintf( __( 'There was an issue determining where %s is installed. Please reinstall it.', 'rsc' ), $this->title ) );
            }
            
            self::$woocommerce_settings['hpos'] = get_option( 'woocommerce_custom_orders_table_enabled' );
        }
        
        /**
         * Add link to Settings page on the plugins screen
         *
         * @param $links
         * @param $file
         * @return mixed [type]
         */
        function plugin_action_links( $links, $file )
        {
            $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=restricted_content_settings' ) ) . '">' . __( 'Settings', 'rsc' ) . '</a>';
            array_unshift( $links, $settings_link );
            return $links;
        }
        
        /**
         * Plugin localization
         *
         * @return void [type]
         */
        function localization()
        {
            
            if ( $this->location == 'mu-plugins' ) {
                load_muplugin_textdomain( 'rsc', 'languages/' );
            } elseif ( $this->location == 'subfolder-plugins' ) {
                load_plugin_textdomain( 'rsc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
            } elseif ( $this->location == 'plugins' ) {
                load_plugin_textdomain( 'rsc', false, 'languages/' );
            }
            
            $temp_locales = explode( '_', get_locale() );
            $this->language = ( $temp_locales[0] ? $temp_locales[0] : 'en' );
        }
        
        /**
         * Blocks POST content if needed
         * Calls RSC shortcode to check if the block is needed (check rsc_shortcode method)
         *
         * @param  [type] $content content of a post
         * @return mixed|string|void [type]
         */
        function maybe_block_content( $content )
        {
            global  $post ;
            // Make sure that we restrict the content only on the front-end
            
            if ( !is_admin() ) {
                $rsc_skip_check = apply_filters( 'rsc_skip_check', false );
                if ( $rsc_skip_check == true ) {
                    return $content;
                }
                
                if ( isset( $post ) ) {
                    $content = apply_filters( 'rsc_maybe_block_content_post_before', $content, $post );
                    $rsc_content_availability = get_post_meta( $post->ID, '_rsc_content_availability', true );
                    if ( empty($rsc_content_availability) ) {
                        $rsc_content_availability = 'everyone';
                    }
                    $rsc_content_availability = apply_filters( 'rsc_content_availability', $rsc_content_availability, $post->ID );
                    
                    if ( $rsc_content_availability !== 'everyone' ) {
                        // Content shouldn't be available to everyone so we need to restrict it
                        $message = do_shortcode( '[RSC id="' . $post->ID . '" type="' . $rsc_content_availability . '"]' );
                        if ( $message ) {
                            $content = $message;
                        }
                    }
                    
                    $content = apply_filters( 'rsc_maybe_block_content_post_after', $content, $post );
                }
            
            }
            
            return $content;
        }
        
        /**
         * Get a user role from current user
         *
         * @return bool|string [type]
         */
        public static function get_current_user_role()
        {
            
            if ( is_user_logged_in() ) {
                global  $current_user ;
                return $current_user->roles[0];
            }
            
            return false;
        }
        
        public static function admin_settings()
        {
            require_once plugin_dir_path( __FILE__ ) . 'includes/settings/settings.php';
        }
        
        function first_rc_menu_handler( $handler )
        {
            $handler = 'admin.php';
            return $handler;
        }
        
        public static function rsc_get_message( $type, $additional_arg = false )
        {
            $rsc_settings = get_option( 'rsc_settings' );
            switch ( $type ) {
                case 'logged_in':
                    // Only logged in users should have access to the content
                    return apply_filters( 'rsc_logged_in_message', ( isset( $rsc_settings['logged_in_message'] ) ? esc_html( $rsc_settings['logged_in_message'] ) : esc_html( __( 'You must log in to view this content', 'rsc' ) ) ) );
                    break;
                case 'role':
                    return $user_role_message = apply_filters( 'rsc_role_message', ( isset( $rsc_settings['user_role_message'] ) ? esc_html( $rsc_settings['user_role_message'] ) : esc_html( __( 'You don\'t have required permissions to view this content.', 'rsc' ) ) ) );
                    break;
                case 'capability':
                    return apply_filters( 'rsc_capability_message', ( isset( $rsc_settings['capability_message'] ) ? esc_html( $rsc_settings['capability_message'] ) : esc_html( __( 'You don\'t have required permissions to view this content.', 'rsc' ) ) ) );
                    break;
                case 'author':
                    // Only author of the post and the administrator should have access to the content
                    return apply_filters( 'rsc_author_message', ( isset( $rsc_settings['author_message'] ) ? esc_html( $rsc_settings['author_message'] ) : esc_html( __( 'This content is available only to it\'s author.', 'rsc' ) ) ) );
                    break;
                case 'tickera_anything':
                    return apply_filters( 'rsc_tickera_any_ticket_type_message', ( isset( $rsc_settings['tickera_any_ticket_type_message'] ) ? esc_html( $rsc_settings['tickera_any_ticket_type_message'] ) : esc_html( __( 'This content is restricted to the attendees only. Please purchase ticket(s) in order to access this content.', 'rsc' ) ) ) );
                    break;
                case 'tickera_event':
                    $rsc_tickera_users_event = $additional_arg;
                    $message = apply_filters( 'rsc_tickera_specific_event_message', ( isset( $rsc_settings['tickera_specific_event_message'] ) ? esc_html( $rsc_settings['tickera_specific_event_message'] ) : esc_html( __( 'Only attendees who purchased ticket(s) for following event(s): [rsc_tc_event] can access this content.', 'rsc' ) ) ) );
                    // Show event titles only if [rsc_tc_event] is used
                    
                    if ( preg_match( '/[rsc_tc_event]/', $message ) ) {
                        $events_titles = array();
                        foreach ( $rsc_tickera_users_event as $rsc_tickera_users_event_key => $rsc_tickera_users_event_value ) {
                            $events_titles[] = get_the_title( $rsc_tickera_users_event_value );
                        }
                        $message = str_replace( '[rsc_tc_event]', implode( ', ', $events_titles ), $message );
                    }
                    
                    // Show event titles with links only if [rsc_tc_event_links] is used
                    
                    if ( preg_match( '/[rsc_tc_event_links]/', $message ) ) {
                        $events_titles_links = array();
                        foreach ( $rsc_tickera_users_event as $rsc_tickera_users_event_key => $rsc_tickera_users_event_value ) {
                            $events_titles_links[] = '<a href="' . get_permalink( (int) $rsc_tickera_users_event_value ) . '">' . get_the_title( $rsc_tickera_users_event_value ) . '</a>';
                        }
                        $message = str_replace( '[rsc_tc_event_links]', implode( ', ', $events_titles_links ), $message );
                    }
                    
                    return $message;
                    break;
                case 'tickera_ticket_type':
                    $rsc_tickera_users_ticket_type = $additional_arg;
                    $message = apply_filters( 'rsc_tickera_specific_ticket_type_message', ( isset( $rsc_settings['tickera_specific_ticket_type_message'] ) ? esc_html( $rsc_settings['tickera_specific_ticket_type_message'] ) : esc_html( __( 'Only attendees who purchased following ticket type(s): [rsc_tc_ticket_type] can access this content.', 'rsc' ) ) ) );
                    // Show event titles only if [rsc_tc_event] is used
                    
                    if ( preg_match( '/[rsc_tc_ticket_type]/', $message ) ) {
                        $ticket_types_titles = array();
                        foreach ( $rsc_tickera_users_ticket_type as $rsc_tickera_users_ticket_type_key => $rsc_tickera_users_ticket_type_value ) {
                            
                            if ( apply_filters( 'rsc_append_event_title_to_ticket_types_placeholder', true ) == true ) {
                                $event_id = Restricted_Content::get_meta_value(
                                    $rsc_tickera_users_ticket_type_value,
                                    'event_name',
                                    true,
                                    'post'
                                );
                                if ( empty($event_id) ) {
                                    $event_id = Restricted_Content::get_meta_value(
                                        $rsc_tickera_users_ticket_type_value,
                                        '_event_name',
                                        true,
                                        'post'
                                    );
                                }
                                $event_title = apply_filters(
                                    'rsc_event_title_ticket_types_placeholder',
                                    ' (' . get_the_title( $event_id ) . ' ' . __( 'event', 'rsc' ) . ')',
                                    $event_id,
                                    $rsc_tickera_users_ticket_type_value
                                );
                            } else {
                                $event_title = '';
                            }
                            
                            $ticket_types_titles[] = apply_filters( 'rsc_ticket_type_title_placeholder', get_the_title( $rsc_tickera_users_ticket_type_value ) . $event_title, $rsc_tickera_users_ticket_type_value );
                        }
                        $message = str_replace( '[rsc_tc_ticket_type]', implode( ', ', $ticket_types_titles ), $message );
                    }
                    
                    return $message;
                    break;
                case 'woo_anything':
                    $message = apply_filters( 'rsc_woo_any_product_message', ( isset( $rsc_settings['woo_any_product_message'] ) ? esc_html( $rsc_settings['woo_any_product_message'] ) : esc_html( __( 'This content is restricted to the clients only. Please purchase any product in order to access this content.', 'rsc' ) ) ) );
                    return $message;
                    break;
                case 'woo_product':
                    $rsc_woo_users_product = $additional_arg;
                    $message = apply_filters( 'rsc_woo_specific_product_message', ( isset( $rsc_settings['woo_specific_product_message'] ) ? esc_html( $rsc_settings['woo_specific_product_message'] ) : esc_html( __( 'Only clients who purchased following product(s): [rsc_woo_product] can access this content.', 'rsc' ) ) ) );
                    // Show product titles only if [rsc_woo_product] is used
                    
                    if ( preg_match( '/[rsc_woo_product]/', $message ) ) {
                        $product_titles = array();
                        foreach ( $rsc_woo_users_product as $rsc_woo_users_product_key => $rsc_woo_users_product_value ) {
                            $product_titles[] = apply_filters( 'rsc_woo_product_title_title_placeholder', get_the_title( $rsc_woo_users_product_value ), $rsc_woo_users_product_value );
                        }
                        $message = str_replace( '[rsc_woo_product]', implode( ', ', $product_titles ), $message );
                    }
                    
                    // Show product title and links only if [rsc_woo_product_links] is used
                    
                    if ( preg_match( '/[rsc_woo_product_links]/', $message ) ) {
                        $product_titles_links = array();
                        foreach ( $rsc_woo_users_product as $rsc_woo_users_product_key => $rsc_woo_users_product_value ) {
                            $product_titles_links[] = '<a href="' . get_permalink( (int) $rsc_woo_users_product_value ) . '">' . apply_filters( 'rsc_woo_product_title_title_placeholder', get_the_title( $rsc_woo_users_product_value ), $rsc_woo_users_product_value ) . '</a>';
                        }
                        $message = str_replace( '[rsc_woo_product_links]', implode( ', ', $product_titles_links ), $message );
                    }
                    
                    return $message;
                    break;
                case 'woo_product_limited':
                    $rsc_woo_users_product = $additional_arg;
                    $message = apply_filters( 'rsc_woo_specific_product_limited_message', ( isset( $rsc_settings['woo_specific_product_limited_message'] ) ? esc_html( $rsc_settings['woo_specific_product_limited_message'] ) : esc_html( __( 'The access to this content is invalid or has expired. Please (re)purchase one of the following product(s): [rsc_woo_product_links] in order to get access to this content.', 'rsc' ) ) ) );
                    // Show product titles only if [rsc_woo_product] is used
                    
                    if ( preg_match( '/[rsc_woo_product]/', $message ) ) {
                        $product_titles = array();
                        foreach ( $rsc_woo_users_product as $rsc_woo_users_product_key => $rsc_woo_users_product_value ) {
                            $product_titles[] = apply_filters( 'rsc_woo_product_title_title_placeholder', get_the_title( $rsc_woo_users_product_value ), $rsc_woo_users_product_value );
                        }
                        $message = str_replace( '[rsc_woo_product]', implode( ', ', $product_titles ), $message );
                    }
                    
                    // Show product title and links only if [rsc_woo_product_links] is used
                    
                    if ( preg_match( '/[rsc_woo_product_links]/', $message ) ) {
                        $product_titles_links = array();
                        foreach ( $rsc_woo_users_product as $rsc_woo_users_product_key => $rsc_woo_users_product_value ) {
                            $product_titles_links[] = '<a href="' . get_permalink( (int) $rsc_woo_users_product_value ) . '">' . apply_filters( 'rsc_woo_product_title_title_placeholder', get_the_title( $rsc_woo_users_product_value ), $rsc_woo_users_product_value ) . '</a>';
                        }
                        $message = str_replace( '[rsc_woo_product_links]', implode( ', ', $product_titles_links ), $message );
                    }
                    
                    return $message;
                    break;
                case 'edd_anything':
                    $message = apply_filters( 'rsc_edd_any_product_message', ( isset( $rsc_settings['edd_any_product_message'] ) ? esc_html( $rsc_settings['edd_any_product_message'] ) : esc_html( __( 'This content is restricted to the clients only. Please purchase any product in order to access this content.', 'rsc' ) ) ) );
                    return $message;
                    break;
                case 'edd_product':
                    $rsc_edd_users_product = $additional_arg;
                    $message = apply_filters( 'rsc_edd_specific_product_message', ( isset( $rsc_settings['edd_specific_product_message'] ) ? esc_html( $rsc_settings['edd_specific_product_message'] ) : esc_html( __( 'Only clients who purchased following product(s): [rsc_edd_product] can access this content.', 'rsc' ) ) ) );
                    // Show product titles only if [rsc_edd_product] is used
                    
                    if ( preg_match( '/[rsc_edd_product]/', $message ) ) {
                        $product_titles = array();
                        foreach ( $rsc_edd_users_product as $rsc_edd_users_product_key => $rsc_edd_users_product_value ) {
                            $product_titles[] = apply_filters( 'rsc_edd_product_title_title_placeholder', get_the_title( $rsc_edd_users_product_value ), $rsc_edd_users_product_value );
                        }
                        $message = str_replace( '[rsc_edd_product]', implode( ', ', $product_titles ), $message );
                    }
                    
                    // Show product title and links only if [rsc_edd_product_links] is used
                    
                    if ( preg_match( '/[rsc_edd_product_links]/', $message ) ) {
                        $product_titles_links = array();
                        foreach ( $rsc_edd_users_product as $rsc_edd_users_product_key => $rsc_edd_users_product_value ) {
                            $product_titles_links[] = '<a href="' . get_permalink( (int) $rsc_edd_users_product_value ) . '">' . apply_filters( 'rsc_edd_product_title_title_placeholder', get_the_title( $rsc_edd_users_product_value ), $rsc_edd_users_product_value ) . '</a>';
                        }
                        $message = str_replace( '[rsc_edd_product_links]', implode( ', ', $product_titles_links ), $message );
                    }
                    
                    return $message;
                    break;
                case 'edd_product_limited':
                    $rsc_edd_users_product = $additional_arg;
                    $message = apply_filters( 'rsc_edd_specific_product_limited_message', ( isset( $rsc_settings['edd_specific_product_limited_message'] ) ? esc_html( $rsc_settings['edd_specific_product_limited_message'] ) : esc_html( __( 'The access to this content is invalid or has expired. Please (re)purchase one of the following product(s): [rsc_edd_product_links] in order to get access to this content.', 'rsc' ) ) ) );
                    // Show product titles only if [rsc_edd_product] is used
                    
                    if ( preg_match( '/[rsc_edd_product]/', $message ) ) {
                        $product_titles = array();
                        foreach ( $rsc_edd_users_product as $rsc_edd_users_product_key => $rsc_edd_users_product_value ) {
                            $product_titles[] = apply_filters( 'rsc_edd_product_title_title_placeholder', get_the_title( $rsc_edd_users_product_value ), $rsc_edd_users_product_value );
                        }
                        $message = str_replace( '[rsc_edd_product]', implode( ', ', $product_titles ), $message );
                    }
                    
                    // Show product title and links only if [rsc_edd_product_links] is used
                    
                    if ( preg_match( '/[rsc_edd_product_links]/', $message ) ) {
                        $product_titles_links = array();
                        foreach ( $rsc_edd_users_product as $rsc_edd_users_product_key => $rsc_edd_users_product_value ) {
                            $product_titles_links[] = '<a href="' . get_permalink( (int) $rsc_edd_users_product_value ) . '">' . apply_filters( 'rsc_edd_product_title_title_placeholder', get_the_title( $rsc_edd_users_product_value ), $rsc_edd_users_product_value ) . '</a>';
                        }
                        $message = str_replace( '[rsc_edd_product_links]', implode( ', ', $product_titles_links ), $message );
                    }
                    
                    return $message;
                    break;
            }
        }
        
        /**
         * Restriction shortcode
         * Shows different messages based on restriction rule set
         *
         * @param  [type] $atts [description]
         * @return string [type]       [description]
         */
        function rsc_shortcode( $atts )
        {
            extract( shortcode_atts( array(
                'id'     => false,
                'cat_id' => false,
                'type'   => 'everyone',
            ), $atts ) );
            $widget = false;
            $widget_instance = false;
            $message = false;
            $allowed_to_admins_capability = apply_filters( 'rsc_allowed_to_admins_capability', 'manage_options' );
            
            if ( ($id || $cat_id) && ($type !== 'everyone' && !current_user_can( $allowed_to_admins_capability )) ) {
                $rsc_settings = get_option( 'rsc_settings' );
                
                if ( $cat_id ) {
                    $id = $cat_id;
                    $metabox_type = 'taxonomy';
                    $value_array = get_term_meta( $id );
                    $value_array['is_category'] = true;
                } else {
                    $metabox_type = 'post';
                    $value_array = apply_filters( 'rsc_get_post_value_array', get_post_meta( $id ), $id );
                    $value_array['is_category'] = false;
                }
                
                $value_array['id'] = $id;
                $altered_value = ( isset( $value_array['altered_value'] ) && $value_array['altered_value'] == true ? true : false );
                $can_access = Restricted_Content::can_access( $value_array );
                $type = ( isset( $value_array['_rsc_content_availability'] ) ? Restricted_Content::fix_value( $value_array['_rsc_content_availability'] ) : 'everyone' );
                switch ( $type ) {
                    case 'logged_in':
                        // Only logged in users should have access to the content
                        $message = ( !$can_access ? Restricted_Content::rsc_get_message( $type ) : false );
                        break;
                    case 'role':
                        // Only specific user roles should have access to the content
                        $message = ( $can_access ? false : Restricted_Content::rsc_get_message( $type ) );
                        break;
                    case 'capability':
                        // Only users with specific capability should have access to the content
                        $message = ( !$can_access ? Restricted_Content::rsc_get_message( $type ) : false );
                        break;
                    case 'author':
                        // Content is available only to it's author and the administrators
                        $message = ( !$can_access ? Restricted_Content::rsc_get_message( $type ) : false );
                        break;
                    case 'tickera':
                        // Only Tickera users should have access to the content
                        $rsc_tickera_users = ( $altered_value ? $value_array['_rsc_tickera_users'] : Restricted_Content::get_meta_value(
                            $id,
                            '_rsc_tickera_users',
                            true,
                            $metabox_type,
                            $widget,
                            $widget_instance
                        ) );
                        switch ( $rsc_tickera_users ) {
                            case 'anything':
                                // At least one purchase of Tickera ticket is required for accessing the content
                                $message = ( $can_access ? false : Restricted_Content::rsc_get_message( $type . '_' . $rsc_tickera_users ) );
                                // tickera_anything
                                break;
                            case 'event':
                                // A purchase of at least one Tickera ticket type for a specific event is required to access the content
                                $rsc_tickera_users_event = ( $altered_value ? $value_array['_rsc_tickera_users_event'] : Restricted_Content::get_meta_value(
                                    $id,
                                    '_rsc_tickera_users_event',
                                    true,
                                    $metabox_type,
                                    $widget,
                                    $widget_instance
                                ) );
                                $message = ( $can_access ? false : Restricted_Content::rsc_get_message( $type . '_' . $rsc_tickera_users, $rsc_tickera_users_event ) );
                                break;
                            case 'ticket_type':
                                // A purchase of a specific ticket type is required for accessing the content
                                $rsc_tickera_users_ticket_type = ( $altered_value ? $value_array['_rsc_tickera_users_ticket_type'] : Restricted_Content::get_meta_value(
                                    $id,
                                    '_rsc_tickera_users_ticket_type',
                                    true,
                                    $metabox_type,
                                    $widget,
                                    $widget_instance
                                ) );
                                $message = ( $can_access ? false : Restricted_Content::rsc_get_message( $type . '_' . $rsc_tickera_users, $rsc_tickera_users_ticket_type ) );
                                break;
                        }
                        break;
                    case 'woo':
                        // Only WooCommerce users should have access to the content
                        $rsc_woo_users = ( $altered_value ? $value_array['_rsc_woo_users'] : Restricted_Content::get_meta_value(
                            $id,
                            '_rsc_woo_users',
                            true,
                            $metabox_type,
                            $widget,
                            $widget_instance
                        ) );
                        switch ( $rsc_woo_users ) {
                            case 'anything':
                                // At least one purchase of any product is required for accessing the content
                                $message = ( $can_access ? false : Restricted_Content::rsc_get_message( $type . '_' . $rsc_woo_users ) );
                                break;
                            case 'product':
                                // A purchase of a specific product is required for accessing the content
                                $rsc_woo_users_product = ( $altered_value ? $value_array['_rsc_woo_users_product'] : Restricted_Content::get_meta_value(
                                    $id,
                                    '_rsc_woo_users_product',
                                    true,
                                    $metabox_type,
                                    $widget,
                                    $widget_instance
                                ) );
                                
                                if ( $can_access ) {
                                    $message = false;
                                } else {
                                    $rsc_woo_users_time = $value_array['_rsc_woo_users_time'];
                                    if ( is_array( $rsc_woo_users_time ) ) {
                                        $rsc_woo_users_time = $rsc_woo_users_time[0];
                                    }
                                    if ( is_array( $rsc_woo_users_time[0] ) ) {
                                        $rsc_woo_users_time = $rsc_woo_users_time[0];
                                    }
                                    
                                    if ( empty($rsc_woo_users_time) || !isset( $rsc_woo_users_time ) || $rsc_woo_users_time == 'indefinitely' ) {
                                        // open-ended access
                                        $message = Restricted_Content::rsc_get_message( $type . '_' . $rsc_woo_users, $rsc_woo_users_product );
                                    } else {
                                        $message = Restricted_Content::rsc_get_message( $type . '_' . $rsc_woo_users . '_limited', $rsc_woo_users_product );
                                        // Access is expired
                                    }
                                
                                }
                                
                                break;
                        }
                        break;
                    case 'edd':
                        // Only Easy Digital Downloads users should have access to the content
                        $rsc_edd_users = ( $altered_value ? $value_array['_rsc_edd_users'] : Restricted_Content::get_meta_value(
                            $id,
                            '_rsc_edd_users',
                            true,
                            $metabox_type,
                            $widget,
                            $widget_instance
                        ) );
                        switch ( $rsc_edd_users ) {
                            case 'anything':
                                // At least one purchase of any product is required for accessing the content
                                $message = ( $can_access ? false : Restricted_Content::rsc_get_message( $type . '_' . $rsc_edd_users ) );
                                break;
                            case 'product':
                                // A purchase of a specific product is required for accessing the content
                                $rsc_edd_users_product = ( $altered_value ? $value_array['_rsc_edd_users_product'] : Restricted_Content::get_meta_value(
                                    $id,
                                    '_rsc_edd_users_product',
                                    true,
                                    $metabox_type,
                                    $widget,
                                    $widget_instance
                                ) );
                                
                                if ( $can_access ) {
                                    $message = false;
                                } else {
                                    $rsc_edd_users_time = $value_array['_rsc_edd_users_time'];
                                    if ( is_array( $rsc_edd_users_time ) ) {
                                        $rsc_edd_users_time = $rsc_edd_users_time[0];
                                    }
                                    if ( is_array( $rsc_edd_users_time[0] ) ) {
                                        $rsc_edd_users_time = $rsc_edd_users_time[0];
                                    }
                                    
                                    if ( empty($rsc_edd_users_time) || !isset( $rsc_edd_users_time ) || $rsc_edd_users_time == 'indefinitely' ) {
                                        //open-ended access
                                        $message = Restricted_Content::rsc_get_message( $type . '_' . $rsc_edd_users, $rsc_edd_users_product );
                                    } else {
                                        $message = Restricted_Content::rsc_get_message( $type . '_' . $rsc_edd_users . '_limited', $rsc_edd_users_product );
                                        //access is expired
                                    }
                                
                                }
                                
                                break;
                        }
                        break;
                    default:
                        $message = false;
                }
            }
            
            if ( $message !== false && empty($message) ) {
                $message = ' ';
            }
            return ( !$message ? html_entity_decode( $message ) : '<div class="rsc_message">' . html_entity_decode( stripslashes( $message ) ) . '</div>' );
            //false means that user CAN access the content, otherwise a message will be shown (a reason why user can access content or who can access the content)
        }
        
        /**
         * Fixed value of a meta value since sometimes is a string
         * and sometimes first element of an array
         *
         * @param  [type] $value [description]
         * @return array|mixed [type][description]
         */
        public static function fix_value( $value )
        {
            return ( is_array( $value ) && isset( $value[0] ) ? $value[0] : $value );
        }
        
        public static function maybe_unserialize( $value )
        {
            $data = @unserialize( $value );
            return ( $data !== false ? $data : $value );
        }
        
        /**
         * The "main" method - determine if the access is allowed or not based on restrictions / rules set
         *
         * @param  [type] $value_array [description]
         * @return bool [type][description]
         */
        public static function can_access( $value_array )
        {
            $rsc_skip_check = apply_filters( 'rsc_skip_check', false );
            if ( $rsc_skip_check ) {
                return true;
            }
            
            if ( isset( $value_array['id'] ) && (!isset( $value_array['is_category'] ) || isset( $value_array['is_category'] ) && $value_array['is_category'] == false) ) {
                $id = $value_array['id'];
                $value_array = apply_filters( 'rsc_get_post_value_array', get_post_meta( $id ), $id );
            }
            
            $type = ( isset( $value_array['_rsc_content_availability'] ) ? Restricted_Content::fix_value( $value_array['_rsc_content_availability'] ) : 'everyone' );
            $rsc_settings = get_option( 'rsc_settings' );
            switch ( $type ) {
                case 'logged_in':
                    // Only logged in users should have access to the content
                    return ( !is_user_logged_in() ? false : true );
                    break;
                case 'logged_out':
                    // Only logged out users / visitors - useful only for widgets and similar things
                    return ( is_user_logged_in() ? false : true );
                    break;
                case 'author':
                    // Only authors (and administrators) can access the content
                    $current_user_id = get_current_user_id();
                    
                    if ( isset( $value_array['id'] ) ) {
                        $post_author_id = get_post_field( 'post_author', (int) $value_array['id'] );
                        return ( $post_author_id == $current_user_id ? true : false );
                    } else {
                        return false;
                    }
                    
                    break;
                case 'role':
                    // Only specific user roles should have access to the content
                    $current_user_role = Restricted_Content::get_current_user_role();
                    
                    if ( $current_user_role ) {
                        $rsc_user_role = $value_array['_rsc_user_role'];
                        foreach ( $rsc_user_role as $key => $value ) {
                            $rsc_user_role[$key] = Restricted_Content::maybe_unserialize( $value );
                        }
                        if ( is_array( $rsc_user_role[0] ) ) {
                            $rsc_user_role = $rsc_user_role[0];
                        }
                        return ( is_array( $rsc_user_role ) && in_array( $current_user_role, $rsc_user_role ) ? true : false );
                    } else {
                        return false;
                    }
                    
                    break;
                case 'capability':
                    // Only users with specific capability should have access to the content
                    $required_capability = Restricted_Content::fix_value( $value_array['_rsc_capability'] );
                    return ( !current_user_can( $required_capability ) ? false : true );
                    break;
                case 'tickera':
                    // Only Tickera users should have access to the content
                    $rsc_tickera_users = Restricted_Content::fix_value( $value_array['_rsc_tickera_users'] );
                    switch ( $rsc_tickera_users ) {
                        case 'anything':
                            // At least one purchase of Tickera ticket is required for accessing the content
                            return ( Restricted_Content::get_tickera_paid_user_orders_count() > 0 ? true : false );
                            break;
                        case 'event':
                            // A purchase of at least one Tickera ticket type for a specific event is required to access the content
                            $rsc_tickera_users_event = $value_array['_rsc_tickera_users_event'];
                            foreach ( $rsc_tickera_users_event as $key => $value ) {
                                $rsc_tickera_users_event[$key] = Restricted_Content::maybe_unserialize( $value );
                            }
                            if ( is_array( $rsc_tickera_users_event[0] ) ) {
                                $rsc_tickera_users_event = $rsc_tickera_users_event[0];
                            }
                            return ( Restricted_Content::get_tickera_paid_user_orders_count( $rsc_tickera_users_event ) > 0 ? true : false );
                            break;
                        case 'ticket_type':
                            // A purchase of a specific ticket type is required for accessing the content
                            $rsc_tickera_users_ticket_type = $value_array['_rsc_tickera_users_ticket_type'];
                            foreach ( $rsc_tickera_users_ticket_type as $key => $value ) {
                                $rsc_tickera_users_ticket_type[$key] = Restricted_Content::maybe_unserialize( $value );
                            }
                            if ( is_array( $rsc_tickera_users_ticket_type[0] ) ) {
                                $rsc_tickera_users_ticket_type = $rsc_tickera_users_ticket_type[0];
                            }
                            return ( Restricted_Content::get_tickera_paid_user_orders_count( false, $rsc_tickera_users_ticket_type ) > 0 ? true : false );
                            break;
                    }
                    break;
                case 'woo':
                    // Only WooCommerce users should have access to the content
                    $rsc_woo_users = Restricted_Content::fix_value( $value_array['_rsc_woo_users'] );
                    switch ( $rsc_woo_users ) {
                        case 'anything':
                            // At least one purchase of any product is required for accessing the content
                            return ( Restricted_Content::get_woo_paid_user_orders_count() > 0 ? true : false );
                            break;
                        case 'product':
                            // A purchase of a specific product is required for accessing the content
                            $rsc_woo_users_product = $value_array['_rsc_woo_users_product'];
                            // isset($value_array['_rsc_woo_users_product']) ? $value_array['_rsc_woo_users_product'] : false;
                            /*if(!isset($rsc_woo_users_product) || $rsc_woo_users_product == false){//!!! products are not set so we need to allow access to this item <------- !!!
                              return true;
                              }*/
                            foreach ( $rsc_woo_users_product as $key => $value ) {
                                $rsc_woo_users_product[$key] = Restricted_Content::maybe_unserialize( $value );
                            }
                            if ( is_array( $rsc_woo_users_product[0] ) ) {
                                $rsc_woo_users_product = $rsc_woo_users_product[0];
                            }
                            $rsc_woo_users_time = $value_array['_rsc_woo_users_time'];
                            if ( is_array( $rsc_woo_users_time[0] ) ) {
                                $rsc_woo_users_time = $rsc_woo_users_time[0];
                            }
                            if ( is_array( $rsc_woo_users_time ) ) {
                                $rsc_woo_users_time = $rsc_woo_users_time[0];
                            }
                            
                            if ( empty($rsc_woo_users_time) || !isset( $rsc_woo_users_time ) || $rsc_woo_users_time == 'indefinitely' ) {
                                //open-ended access / indefinitely
                                return ( Restricted_Content::get_woo_paid_user_orders_count( false, $rsc_woo_users_product ) > 0 ? true : false );
                            } else {
                                // Limited
                                
                                if ( is_array( $rsc_woo_users_product ) ) {
                                    $products_ids = $rsc_woo_users_product;
                                } else {
                                    $products_ids[] = $rsc_woo_users_product;
                                }
                                
                                $time = array(
                                    'days'    => $value_array['_rsc_woo_users_time_days'],
                                    'hours'   => $value_array['_rsc_woo_users_time_hours'],
                                    'minutes' => $value_array['_rsc_woo_users_time_minutes'],
                                );
                                if ( is_array( $time['days'] ) ) {
                                    $time['days'] = $time['days'][0];
                                }
                                if ( is_array( $time['hours'] ) ) {
                                    $time['hours'] = $time['hours'][0];
                                }
                                if ( is_array( $time['minutes'] ) ) {
                                    $time['minutes'] = $time['minutes'][0];
                                }
                                return ( Restricted_Content::has_limited_woo_purchases( get_current_user_id(), $products_ids, $time ) ? true : false );
                            }
                            
                            return false;
                            break;
                    }
                    break;
                case 'edd':
                    // Only Easy Digital Downloads users should have access to the content
                    $rsc_edd_users = Restricted_Content::fix_value( $value_array['_rsc_edd_users'] );
                    switch ( $rsc_edd_users ) {
                        case 'anything':
                            // At least one purchase of any product is required for accessing the content
                            $user_id = get_current_user_id();
                            
                            if ( !function_exists( 'edd_has_purchases' ) ) {
                                return true;
                                // Allow access since EDD is inac
                            }
                            
                            return ( edd_has_purchases( $user_id ) ? true : false );
                            break;
                        case 'product':
                            // A purchase of a specific product is required for accessing the content
                            $rsc_edd_users_product = $value_array['_rsc_edd_users_product'];
                            foreach ( $rsc_edd_users_product as $key => $value ) {
                                $rsc_edd_users_product[$key] = Restricted_Content::maybe_unserialize( $value );
                            }
                            if ( is_array( $rsc_edd_users_product[0] ) ) {
                                $rsc_edd_users_product = $rsc_edd_users_product[0];
                            }
                            $rsc_edd_users_time = $value_array['_rsc_edd_users_time'];
                            if ( is_array( $rsc_edd_users_time[0] ) ) {
                                $rsc_edd_users_time = $rsc_edd_users_time[0];
                            }
                            if ( is_array( $rsc_edd_users_time ) ) {
                                $rsc_edd_users_time = $rsc_edd_users_time[0];
                            }
                            
                            if ( !function_exists( 'edd_has_user_purchased' ) ) {
                                return true;
                                // Allow access since EDD is inactive
                            }
                            
                            
                            if ( empty($rsc_edd_users_time) || !isset( $rsc_edd_users_time ) || $rsc_edd_users_time == 'indefinitely' ) {
                                //open-ended access / indefinitely
                                $user_id = get_current_user_id();
                                return edd_has_user_purchased( $user_id, $rsc_edd_users_product );
                                //!!!! check if needs to be an array! $rsc_edd_users_product
                            } else {
                                // Limited
                                
                                if ( is_array( $rsc_edd_users_product ) ) {
                                    $products_ids = $rsc_edd_users_product;
                                } else {
                                    $products_ids[] = $rsc_edd_users_product;
                                }
                                
                                $time = array(
                                    'days'    => $value_array['_rsc_edd_users_time_days'],
                                    'hours'   => $value_array['_rsc_edd_users_time_hours'],
                                    'minutes' => $value_array['_rsc_edd_users_time_minutes'],
                                );
                                if ( is_array( $time['days'] ) ) {
                                    $time['days'] = $time['days'][0];
                                }
                                if ( is_array( $time['hours'] ) ) {
                                    $time['hours'] = $time['hours'][0];
                                }
                                if ( is_array( $time['minutes'] ) ) {
                                    $time['minutes'] = $time['minutes'][0];
                                }
                                
                                if ( !function_exists( 'edd_has_user_purchased' ) ) {
                                    return true;
                                    // Allow access since EDD is inactive
                                }
                                
                                return ( Restricted_Content::rsc_edd_has_user_purchased( get_current_user_id(), $products_ids, $time ) ? true : false );
                            }
                            
                            return false;
                            break;
                    }
                    break;
                default:
                    return true;
            }
        }
        
        /**
         * Admin settings
         */
        function rsc_admin_header()
        {
            wp_enqueue_style(
                $this->name . '-chosen',
                $this->plugin_url . 'css/chosen.min.css',
                array(),
                $this->version
            );
            wp_enqueue_script(
                $this->name . '-chosen',
                $this->plugin_url . 'js/chosen.jquery.min.js',
                array( $this->name . '-admin' ),
                false,
                false
            );
            
            if ( isset( $_GET['page'] ) && $_GET['page'] == 'restricted_content_settings' ) {
                wp_enqueue_script(
                    'rsc-sticky',
                    $this->plugin_url . 'js/jquery.sticky.js',
                    array( 'jquery' ),
                    $this->version
                );
                wp_localize_script( $this->name . '-admin', 'rsc_vars', array(
                    'tc_check_page' => __( $_GET['page'] ),
                ) );
            }
        
        }
        
        /**
         * Add plugin admin menu items
         */
        function rc_add_admin_menu()
        {
            global  $first_rsc_menu_handler ;
            $plugin_admin_menu_items = array(
                'settings' => __( 'Settings', 'rsc' ),
            );
            add_menu_page(
                $this->title,
                $this->title,
                'manage_options',
                'restricted_content_settings',
                'Restricted_Content::admin_settings',
                'dashicons-restrict',
                6
            );
            $plugin_admin_menu_items = apply_filters( 'rc_plugin_admin_menu_items', $plugin_admin_menu_items );
            // Add the sub menu items
            $number_of_sub_menu_items = 0;
            $first_rsc_menu_handler = '';
            foreach ( $plugin_admin_menu_items as $handler => $value ) {
                
                if ( $number_of_sub_menu_items == 0 ) {
                    $first_rsc_menu_handler = apply_filters( 'first_rc_menu_handler', $this->name . '_' . $handler );
                    do_action( $this->name . '_add_menu_items_up' );
                } else {
                    $capability = ( $handler == 'addons' ? 'manage_options' : 'manage_' . $handler . '_cap' );
                    add_submenu_page(
                        $first_rsc_menu_handler,
                        $value,
                        $value,
                        $capability,
                        $this->name . '_' . $handler,
                        $this->name . '_' . $handler . '_admin'
                    );
                    do_action( $this->name . '_add_menu_items_after_' . $handler );
                }
                
                $number_of_sub_menu_items++;
            }
            do_action( $this->name . '_add_menu_items_down' );
        }
        
        public static function get_product_purchased_last_date( $user_id, $product_id )
        {
            global  $wpdb ;
            
            if ( isset( self::$woocommerce_settings['hpos'] ) && 'no' == self::$woocommerce_settings['hpos'] ) {
                return $wpdb->get_var( $wpdb->prepare( "\r\n                    SELECT p.post_date FROM {$wpdb->prefix}posts p\r\n                    INNER JOIN {$wpdb->prefix}postmeta pm ON p.ID = pm.post_id\r\n                    INNER JOIN {$wpdb->prefix}woocommerce_order_items oi ON oi.order_id = p.ID\r\n                    INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim ON oi.order_item_id = oim.order_item_id\r\n                    WHERE p.post_type='shop_order' AND p.post_status IN ('wc-completed', 'wc-processing')\r\n                    AND ( pm.meta_key = '_customer_user' AND pm.meta_value = '%d' )\r\n                    AND oim.meta_key IN ('_product_id','_variation_id') AND oim.meta_value = '%d'\r\n                    ORDER BY p.ID DESC LIMIT 1\r\n                    ", $user_id, $product_id ) );
            } else {
                return $wpdb->get_var( $wpdb->prepare( "\r\n                    SELECT wo.id FROM wp_wc_orders wo\r\n                    INNER JOIN {$wpdb->prefix}wc_orders_meta wom ON wo.id = wom.order_id\r\n                    INNER JOIN {$wpdb->prefix}woocommerce_order_items oi ON oi.order_id = wo.id\r\n                    INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim ON oi.order_item_id = oim.order_item_id\r\n                    WHERE wo.status IN ('wc-completed', 'wc-processing' )\r\n                    AND wo.customer_id = '%d'\r\n                    AND oim.meta_key IN ('_product_id','_variation_id') AND oim.meta_value = '%d'\r\n                    ORDER BY wo.id DESC LIMIT 1\r\n                    ", $user_id, $product_id ) );
            }
        
        }
        
        public static function has_limited_woo_purchases( $user_id, $products_ids = array(), $time = array(
            'days'    => 0,
            'hours'   => 0,
            'minutes' => 0,
        ) )
        {
            $valid_limited_purchases = 0;
            foreach ( $products_ids as $product_id ) {
                $product_purchased_last_date = Restricted_Content::get_product_purchased_last_date( (int) $user_id, (int) $product_id );
                
                if ( !is_null( $product_purchased_last_date ) ) {
                    $days_selected = ( isset( $time['days'] ) ? (int) $time['days'] : 0 );
                    $hours_selected = ( isset( $time['hours'] ) ? (int) $time['hours'] : 0 );
                    $minutes_selected = ( isset( $time['minutes'] ) ? (int) $time['minutes'] : 0 );
                    $total_seconds = $days_selected * 24 * 60 * 60 + $hours_selected * 60 * 60 + $minutes_selected * 60;
                    $order_limit_timestamp = strtotime( $product_purchased_last_date ) + $total_seconds;
                    $current_site_timestamp = current_time( 'timestamp', false );
                    
                    if ( $order_limit_timestamp > $current_site_timestamp ) {
                        $valid_limited_purchases++;
                    } else {
                        // The purchase is invalid
                    }
                
                }
            
            }
            return ( $valid_limited_purchases > 0 ? true : false );
        }
        
        public static function rsc_edd_has_user_purchased( $user_id, $downloads, $time = array(
            'days'    => 0,
            'hours'   => 0,
            'minutes' => 0,
        ) )
        {
            if ( empty($user_id) ) {
                return false;
            }
            $valid_limited_purchases = 0;
            $days_selected = ( isset( $time['days'] ) ? (int) $time['days'] : 0 );
            $hours_selected = ( isset( $time['hours'] ) ? (int) $time['hours'] : 0 );
            $minutes_selected = ( isset( $time['minutes'] ) ? (int) $time['minutes'] : 0 );
            $total_seconds = $days_selected * 24 * 60 * 60 + $hours_selected * 60 * 60 + $minutes_selected * 60;
            $current_site_timestamp = current_time( 'timestamp', false );
            $users_purchases = edd_get_users_purchases( $user_id );
            $return = false;
            if ( !is_array( $downloads ) ) {
                $downloads = array( $downloads );
            }
            if ( $users_purchases ) {
                foreach ( $users_purchases as $purchase ) {
                    $payment = new EDD_Payment( $purchase->ID );
                    $purchased_files = $payment->cart_details;
                    
                    if ( isset( $payment->completed_date ) ) {
                        if ( is_array( $purchased_files ) ) {
                            foreach ( $purchased_files as $download ) {
                                
                                if ( in_array( $download['id'], $downloads ) ) {
                                    $order_limit_timestamp = strtotime( $payment->completed_date ) + $total_seconds;
                                    
                                    if ( $order_limit_timestamp > $current_site_timestamp ) {
                                        $valid_limited_purchases++;
                                    } else {
                                        // The purchase is invalid
                                    }
                                
                                }
                            
                            }
                        }
                    } else {
                        // Do nothing, it doesn't have a date of payment completion
                    }
                
                }
            }
            return ( $valid_limited_purchases > 0 ? true : false );
        }
        
        public static function get_woo_paid_user_orders_count( $event_id = false, $product_id = false )
        {
            global  $wpdb ;
            $user_id = get_current_user_id();
            if ( $user_id == 0 ) {
                return 0;
            }
            
            if ( !$event_id && !$product_id ) {
                // Overall paid orders
                $paid_orders_count = $wpdb->get_var( "SELECT COUNT(p.ID) FROM {$wpdb->posts} p, {$wpdb->postmeta} pm1, {$wpdb->postmeta} pm2 " . "                                         WHERE p.ID = pm1.post_id AND p.ID = pm2.post_id" . "                                         AND (p.post_status = 'wc-completed' OR p.post_status = 'wc-processing') " . "                                         AND p.post_type = 'shop_order'" . "                                         AND pm1.meta_key = '_customer_user' AND pm2.meta_value = '" . (int) $user_id . "'" );
                return (int) $paid_orders_count;
            }
            
            
            if ( !$event_id && $product_id ) {
                // Paid orders for specific ticket type
                $current_user = wp_get_current_user();
                $user_email = $current_user->user_email;
                if ( is_array( $product_id ) ) {
                    // Ticket type id is actually a list of ids / array (so we need to build a bit complicated query)
                    
                    if ( count( $product_id ) > 1 ) {
                        foreach ( $product_id as $product_id_key => $product_id_value ) {
                            if ( wc_customer_bought_product( $user_email, $user_id, $product_id_value ) ) {
                                return 1;
                            }
                        }
                        return 0;
                    } else {
                        // Array contains only one element / ticket type id
                        if ( wc_customer_bought_product( $user_email, $user_id, $product_id[0] ) ) {
                            return 1;
                        }
                    }
                
                }
                return 0;
            }
        
        }
        
        /**
         * Retrieves count of paid orders
         * Overall, for a specific event, for a specific ticket type
         *
         * @param bool $event_id
         * @param bool $ticket_type_id
         * @return type
         * @global type $wpdb
         */
        public static function get_tickera_paid_user_orders_count( $event_id = false, $ticket_type_id = false )
        {
            global  $wpdb ;
            $user_id = get_current_user_id();
            if ( $user_id == 0 ) {
                return 0;
            }
            
            if ( !$event_id && !$ticket_type_id ) {
                // Overall paid orders
                
                if ( apply_filters( 'tc_is_woo', false ) == true ) {
                    // Tickera is in the Bridge mode
                    $paid_orders_count = $wpdb->get_var( "SELECT COUNT(p.ID) FROM {$wpdb->posts} p, {$wpdb->postmeta} pm1, {$wpdb->postmeta} pm2 " . "                                         WHERE p.ID = pm1.post_id AND p.ID = pm2.post_id" . "                                         AND (p.post_status = 'wc-completed' OR p.post_status = 'wc-processing') " . "                                         AND p.post_type = 'shop_order'" . "                                         AND pm1.meta_key = '_customer_user'" . "                                         AND pm1.meta_value = {$user_id}" . "                                         AND pm2.meta_key = 'tc_cart_info'" );
                } else {
                    $paid_orders_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_author = %d AND post_status = 'order_paid' AND post_type = 'tc_orders'", $user_id ) );
                }
                
                return $paid_orders_count;
            }
            
            
            if ( !$event_id && $ticket_type_id ) {
                // Paid orders for specific ticket type
                $ticket_type_id_query_part = '';
                
                if ( is_array( $ticket_type_id ) ) {
                    // Ticket type id is actually a list of ids / array (so we need to build a bit complicated query)
                    
                    if ( count( $ticket_type_id ) > 1 ) {
                        $ticket_type_ids_count = count( $ticket_type_id );
                        $ticket_type_id_query_part .= ' AND (';
                        $foreach_count = 1;
                        $extension = '';
                        foreach ( $ticket_type_id as $ticket_type_id_key => $ticket_type_id_value ) {
                            
                            if ( $ticket_type_ids_count == $foreach_count ) {
                                $extension = '';
                            } else {
                                $extension = ' OR ';
                            }
                            
                            $ticket_type_id_query_part .= " pm.meta_value  LIKE '%i:" . (int) $ticket_type_id_value . ";%' {$extension}";
                            $foreach_count++;
                        }
                        $ticket_type_id_query_part .= ') ';
                    } else {
                        // Array contains only one element / ticket type id
                        $ticket_type_id_query_part = " AND pm.meta_value LIKE '%i:" . (int) $ticket_type_id[0] . ";%'";
                    }
                
                } else {
                    // Argument is an integer (only one ticket type id)
                    $ticket_type_id_query_part = " AND pm.meta_value LIKE '%i:" . (int) $ticket_type_id . ";%'";
                }
                
                
                if ( apply_filters( 'tc_is_woo', false ) == false ) {
                    $paid_orders_count = $wpdb->get_var( "SELECT COUNT(p.ID) FROM {$wpdb->posts} p, {$wpdb->postmeta} pm\r\n                        WHERE p.ID = pm.post_id\r\n                        AND p.post_author = {$user_id}\r\n                        AND p.post_status = 'order_paid'\r\n                        AND p.post_type = 'tc_orders'\r\n                        AND pm.meta_key = 'tc_cart_contents'\r\n                        {$ticket_type_id_query_part}" );
                    return $paid_orders_count;
                } else {
                    // Query for the Bridge for WooCommerce
                    $paid_orders_count = $wpdb->get_var( "SELECT COUNT(p.ID) FROM {$wpdb->posts} p, {$wpdb->postmeta} pm, {$wpdb->postmeta} pm2\r\n                        WHERE p.ID = pm.post_id\r\n                        AND p.ID = pm2.post_id\r\n\r\n                        AND pm2.meta_key = '_customer_user'\r\n                        AND pm2.meta_value = {$user_id}\r\n\r\n                        AND (p.post_status = 'wc-completed' OR p.post_status = 'wc-processing')\r\n\r\n                        AND p.post_type = 'shop_order'\r\n                        AND pm.meta_key = 'tc_cart_contents'\r\n                        {$ticket_type_id_query_part}" );
                    return $paid_orders_count;
                }
            
            }
            
            
            if ( apply_filters( 'tc_is_woo', false ) == false ) {
                // This check doesn't work with the Bridge for WooCommerce because it would be very expensive task for the database server
                
                if ( $event_id && !$ticket_type_id ) {
                    // Paid orders for specific event
                    $event_id_query_part = '';
                    
                    if ( is_array( $event_id ) ) {
                        // Event id is actually a list of ids / array (so we need to build a bit complicated query)
                        
                        if ( count( $event_id ) > 1 ) {
                            $event_ids_count = count( $event_id );
                            $event_id_query_part .= ' AND (';
                            $foreach_count = 1;
                            $extension = '';
                            foreach ( $event_id as $event_id_key => $event_id_value ) {
                                $extension = ( $event_ids_count == $foreach_count ? '' : ' OR ' );
                                $event_id_query_part .= " pm.meta_value  LIKE '%\"" . (int) $event_id_value . "\"%' {$extension}";
                                $foreach_count++;
                            }
                            $event_id_query_part .= ') ';
                        } else {
                            // Array contains only one element / event id
                            $event_id_query_part = " AND pm.meta_value LIKE '%\"" . (int) $event_id[0] . "\"%'";
                        }
                    
                    } else {
                        // Argument is an integer (only one event id)
                        $event_id_query_part = " AND pm.meta_value LIKE '%\"" . (int) $event_id . "\"%'";
                    }
                    
                    $paid_orders_count = $wpdb->get_var( "SELECT COUNT(p.ID) FROM {$wpdb->posts} p, {$wpdb->postmeta} pm\r\n                        WHERE p.ID = pm.post_id\r\n                        AND p.post_author = {$user_id}\r\n                        AND p.post_status = 'order_paid'\r\n                        AND p.post_type = 'tc_orders'\r\n                        AND pm.meta_key = 'tc_parent_event'\r\n                        {$event_id_query_part}" );
                }
            
            } else {
                $paid_orders_count = 0;
            }
            
            return (int) $paid_orders_count;
        }
        
        function rsc_show_tabs( $tab )
        {
            do_action( 'rc_show_page_tab_' . $tab );
            require_once $this->plugin_dir . 'includes/settings/settings-general.php';
        }
        
        public static function get_tickera_user_orders()
        {
            $user_id = get_current_user_id();
            $args = array(
                'author'         => $user_id,
                'posts_per_page' => -1,
                'post_type'      => 'tc_orders',
                'post_status'    => 'order_paid',
            );
            return get_posts( $args );
        }
        
        /**
         * Call admin scripts and styles
         *
         * @global type $wp_version
         * @global type $post_type
         */
        function admin_header()
        {
            global  $wp_version, $post_type ;
            // Fix for Tickera builder editor button (because it can't work with multiple WP Editors)
            if ( isset( $_GET['page'] ) && $_GET['page'] == 'restricted_content_settings' ) {
                echo  '<style>.tc-shortcode-builder-button{ display: none !important; }</style>' ;
            }
            // wp_enqueue_script($this->name . '-font-awesome', 'https://use.fontawesome.com/bec919b88b.js', array(), $this->version);
            wp_enqueue_style(
                $this->name . '-admin',
                $this->plugin_url . 'css/admin.css',
                array(),
                $this->version
            );
            wp_enqueue_style(
                $this->name . '-admin-jquery-ui',
                '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css',
                array(),
                $this->version
            );
            wp_enqueue_style(
                $this->name . '-chosen',
                $this->plugin_url . 'css/chosen.min.css',
                array(),
                $this->version
            );
            wp_enqueue_script(
                $this->name . '-admin',
                $this->plugin_url . 'js/admin.js',
                array( 'jquery', 'jquery-ui-tooltip', 'jquery-ui-core' ),
                $this->version,
                false
            );
            wp_localize_script( $this->name . '-admin', 'rsc_vars', array(
                'ajaxUrl' => apply_filters( 'rsc_ajaxurl', admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ) ),
            ) );
            wp_enqueue_script(
                $this->name . '-chosen',
                $this->plugin_url . 'js/chosen.jquery.min.js',
                array( $this->name . '-admin' ),
                false,
                false
            );
            wp_enqueue_style( 'rsc-roboto', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' );
            wp_register_style( 'restrict_dashicons', $this->plugin_url . '/css/restrict.css' );
            wp_enqueue_style( 'restrict_dashicons' );
        }
        
        /**
         * Save metabox values on post save
         *
         * @param type $post_id
         */
        function save_metabox_values( $post_id )
        {
            $metas = array();
            foreach ( $_POST as $field_name => $field_value ) {
                if ( preg_match( '/_rsc_post_meta/', $field_name ) ) {
                    $metas[sanitize_key( str_replace( '_rsc_post_meta', '', $field_name ) )] = rsc_sanitize_string( $field_value );
                }
                $metas = apply_filters( 'rsc_post_metas', $metas );
                if ( isset( $metas ) ) {
                    foreach ( $metas as $key => $value ) {
                        update_post_meta( $post_id, sanitize_key( $key ), rsc_sanitize_string( $value ) );
                    }
                }
            }
        }
        
        /**
         * Adds metabox for content availability
         */
        function add_metabox()
        {
            global  $post_type ;
            $is_comment = ( isset( $_GET['action'] ) && $_GET['action'] == 'editcomment' ? true : false );
            $rsc_skip_post_types = rsc_skip_post_types();
            //do not show restricted content meta fields for post types in the array
            
            if ( !in_array( $post_type, $rsc_skip_post_types ) && !$is_comment ) {
                $can_check_global_post_types = false;
                
                if ( $can_check_global_post_types == true ) {
                    $rsc_settings = get_option( 'rsc_settings' );
                    
                    if ( isset( $rsc_settings['post_type_' . $post_type . '_restricted'] ) && $rsc_settings['post_type_' . $post_type . '_restricted'] == 'yes' ) {
                        add_meta_box(
                            'rsc_metabox',
                            __( 'Content Restrictions', 'rsc' ),
                            array( $this, 'show_global_message_metabox' ),
                            null,
                            'normal',
                            'low'
                        );
                    } else {
                        add_meta_box(
                            'rsc_metabox',
                            __( 'Content Available To', 'rsc' ),
                            'Restricted_Content::show_metabox',
                            null,
                            'normal',
                            'low'
                        );
                    }
                
                } else {
                    add_meta_box(
                        'rsc_metabox',
                        __( 'Content Available To', 'rsc' ),
                        'Restricted_Content::show_metabox',
                        null,
                        'normal',
                        'low'
                    );
                }
            
            }
        
        }
        
        public static function fix_menu_item_name( $name = '' )
        {
            
            if ( strpos( $name, '[]' ) !== false ) {
                return "[" . str_replace( "[]", "", $name ) . "][]";
            } else {
                return "[" . $name . "]";
            }
        
        }
        
        public static function get_menu_item_field_name( $name, $widget, $widget_instance )
        {
            return "menu_item[" . $widget_instance . "]" . Restricted_Content::fix_menu_item_name( $name );
        }
        
        public static function get_post_type_field_name( $name, $widget, $widget_instance )
        {
            return "rsc_settings[" . $widget . "][" . $widget_instance . "]" . Restricted_Content::fix_menu_item_name( $name );
        }
        
        public static function get_field_name( $name = '', $widget = false, $widget_instance = false )
        {
            if ( $widget !== false ) {
                
                if ( method_exists( $widget, 'get_field_name' ) ) {
                    $name = $widget->get_field_name( $name );
                } else {
                    
                    if ( $widget == 'post_type' ) {
                        $name = Restricted_Content::get_post_type_field_name( $name, $widget, $widget_instance );
                    } else {
                        $name = Restricted_Content::get_menu_item_field_name( $name, $widget, $widget_instance );
                    }
                
                }
            
            }
            return $name;
        }
        
        public static function get_meta_value(
            $id = false,
            $key = '',
            $single = true,
            $metabox_type = 'post',
            $widget = false,
            $widget_instance = false
        )
        {
            if ( $metabox_type == 'widget' ) {
                
                if ( is_array( $widget_instance ) || is_object( $widget_instance ) ) {
                    //widget
                    return ( isset( $widget_instance[$key] ) ? $widget_instance[$key] : '' );
                } else {
                    // Menu item
                    return get_post_meta( $id, $key, $single );
                }
            
            }
            
            if ( $metabox_type == 'post_type' ) {
                $rsc_settings = get_option( 'rsc_settings', false );
                return ( isset( $rsc_settings[$metabox_type][$widget_instance][$key . '_rsc_post_meta'] ) ? $rsc_settings[$metabox_type][$widget_instance][$key . '_rsc_post_meta'] : '' );
            }
            
            if ( !is_string( $metabox_type ) ) {
                $metabox_type = 'post';
            }
            if ( isset( $_GET['tag_ID'] ) ) {
                $metabox_type = 'taxonomy';
            }
            if ( $metabox_type == 'post' ) {
                return get_post_meta( $id, $key, $single );
            }
            if ( $metabox_type == 'taxonomy' ) {
                return get_term_meta( $id, $key, $single );
            }
        }
        
        /**
         * Get all restriction options
         *
         * @return type
         * @global type $tc
         */
        public static function get_restriction_options( $metabox_type, $widget = false, $widget_instance = false )
        {
            $restriction_options = array(
                'everyone'   => array( __( 'Everyone', 'rsc' ), false ),
                'logged_in'  => array( __( 'Logged in users', 'rsc' ), false ),
                'role'       => array( __( 'Users with specific role', 'rsc' ), array( 'Restricted_Content::get_sub_metabox', array(
                'role',
                $metabox_type,
                $widget,
                $widget_instance
            ) ) ),
                'capability' => array( __( 'Users with specific capability', 'rsc' ), array( 'Restricted_Content::get_sub_metabox', array(
                'capability',
                $metabox_type,
                $widget,
                $widget_instance
            ) ) ),
                'author'     => array( __( 'Author', 'rsc' ), array( 'Restricted_Content::get_sub_metabox', array(
                'author',
                $metabox_type,
                $widget,
                $widget_instance
            ) ) ),
            );
            global  $pagenow ;
            
            if ( $metabox_type == 'post' || isset( $_GET['post'] ) || isset( $_GET['post_type'] ) || isset( $pagenow ) && $pagenow == 'post-new.php' || isset( $_GET['tab'] ) && $_GET['tab'] == 'post_types' ) {
                // It's post / page / custom post type so we'll keep Author
            } else {
                unset( $restriction_options['author'] );
            }
            
            
            if ( class_exists( 'TC' ) ) {
                global  $tc ;
                $restriction_options['tickera'] = array( sprintf( __( '%s Users', 'rsc' ), $tc->title ), array( 'Restricted_Content::get_sub_metabox', array(
                    'tickera',
                    $metabox_type,
                    $widget,
                    $widget_instance
                ) ) );
            }
            
            if ( class_exists( 'WooCommerce' ) ) {
                $restriction_options['woo'] = array( __( 'WooCommerce Users', 'rsc' ), array( 'Restricted_Content::get_sub_metabox', array(
                    'woo',
                    $metabox_type,
                    $widget,
                    $widget_instance
                ) ) );
            }
            if ( class_exists( 'Easy_Digital_Downloads' ) ) {
                $restriction_options['edd'] = array( __( 'Easy Digital Downloads Users', 'rsc' ), array( 'Restricted_Content::get_sub_metabox', array(
                    'edd',
                    $metabox_type,
                    $widget,
                    $widget_instance
                ) ) );
            }
            return apply_filters(
                'rsc_restriction_options',
                $restriction_options,
                $widget,
                $widget_instance
            );
        }
        
        function show_global_message_metabox( $post, $metabox_type = 'post' )
        {
            echo  sprintf( __( 'The content is restricted by the global rules set %shere%s' ), '<a target="_blank" href="' . esc_url( admin_url( 'admin.php?page=restricted_content_settings&tab=post_types' ) ) . '">', '</a>' ) ;
        }
        
        /**
         * Shows metabox
         *
         * @param type $post
         */
        public static function show_metabox(
            $post,
            $metabox_type = 'post',
            $widget = false,
            $widget_instance = false
        )
        {
            //possible values: 'post' or 'taxonomy'
            $is_menu_item = false;
            $restriction_options = Restricted_Content::get_restriction_options( $metabox_type, $widget, $widget_instance );
            $sub_metaboxes_functions = array();
            
            if ( is_array( $metabox_type ) || is_string( $metabox_type ) && $metabox_type == 'post' ) {
                $id = $post->ID;
                $metabox_type = 'post';
            } else {
                $id = ( isset( $_GET['tag_ID'] ) ? $_GET['tag_ID'] : false );
            }
            
            
            if ( $id === false ) {
                // For menu item
                $id = (int) $widget_instance;
                $is_menu_item = true;
            }
            
            $rsc_content_availability = '';
            
            if ( isset( $post ) || $is_menu_item ) {
                $rsc_content_availability = Restricted_Content::get_meta_value(
                    $id,
                    '_rsc_content_availability',
                    true,
                    $metabox_type,
                    $widget,
                    $widget_instance
                );
                if ( empty($rsc_content_availability) ) {
                    $rsc_content_availability = 'everyone';
                }
            }
            
            $restriction_options_select = '<select name="' . Restricted_Content::get_field_name( '_rsc_content_availability_rsc_post_meta', $widget, $widget_instance ) . '" class="rsc_content_availability">';
            foreach ( $restriction_options as $restriction_option_key => $restriction_option_values ) {
                $selected = ( $rsc_content_availability == $restriction_option_key ? 'selected' : '' );
                $restriction_options_select .= '<option value="' . esc_attr( $restriction_option_key ) . '" ' . $selected . '>' . $restriction_option_values[0] . '</option>';
                if ( is_array( $restriction_option_values[1] ) && $restriction_option_values[1][0] ) {
                    $sub_metaboxes_functions[] = array( $restriction_option_values[1][0], $restriction_option_values[1][1] );
                }
            }
            $restriction_options_select .= '</select>';
            echo  rsc_esc_html( $restriction_options_select ) ;
            foreach ( $sub_metaboxes_functions as $sub_metaboxes_function_key => $sub_metaboxes_function_args ) {
                Restricted_Content::execute_function( $sub_metaboxes_function_args[0], $sub_metaboxes_function_args[1] );
            }
        }
        
        /**
         * Gets content for sub metaboxes
         *
         * @param bool $type $type
         * @param string $metabox_type
         * @param bool $widget
         * @param bool $widget_instance
         * @return array|void
         * @global type $post
         * @global type $tc
         */
        public static function get_sub_metabox(
            $type = false,
            $metabox_type = 'post',
            $widget = false,
            $widget_instance = false
        )
        {
            if ( !$type ) {
                return;
            }
            global  $post ;
            $return = false;
            
            if ( is_array( $metabox_type ) || is_string( $metabox_type ) && $metabox_type == 'post' ) {
                $metabox_type == 'post';
                $id = $post->ID;
            } else {
                $id = ( isset( $_GET['tag_ID'] ) ? $_GET['tag_ID'] : false );
            }
            
            
            if ( $id === false ) {
                // For menu item
                $id = (int) $widget_instance;
                $is_menu_item = true;
            }
            
            
            if ( $metabox_type == 'elementor' ) {
                $id = false;
                $return = true;
            }
            
            
            if ( !$return ) {
                ?>
                <div class="rsc_sub_metabox rsc_sub_metabox_<?php 
                echo  esc_attr( $type ) ;
                ?> rsc_hide">
            <?php 
            }
            
            switch ( $type ) {
                case 'role':
                    
                    if ( isset( $id ) ) {
                        $rsc_user_role = Restricted_Content::get_meta_value(
                            $id,
                            '_rsc_user_role',
                            true,
                            $metabox_type,
                            $widget,
                            $widget_instance
                        );
                        $rsc_user_role_selected = ( empty($rsc_user_role) ? 'administrator' : $rsc_user_role );
                    }
                    
                    $editable_roles = array_reverse( get_editable_roles() );
                    
                    if ( !$return ) {
                        ?>
                        <label><?php 
                        _e( 'Select a User Role', 'rsc' );
                        ?></label>
                        <select name="<?php 
                        echo  esc_attr( Restricted_Content::get_field_name( '_rsc_user_role_rsc_post_meta[]', $widget, $widget_instance ) ) ;
                        ?>" multiple="true">
                            <?php 
                        foreach ( $editable_roles as $role => $details ) {
                            $name = translate_user_role( $details['name'] );
                            ?>
                                <option <?php 
                            echo  ( isset( $rsc_user_role_selected ) && is_array( $rsc_user_role_selected ) && in_array( $role, $rsc_user_role_selected ) ? 'selected' : '' ) ;
                            ?> value="<?php 
                            echo  esc_attr( $role ) ;
                            ?>"><?php 
                            echo  rsc_esc_html( $name ) ;
                            ?></option><?php 
                        }
                        ?>
                        </select>
                        <?php 
                    } else {
                        $control_roles = array();
                        foreach ( $editable_roles as $role => $details ) {
                            $name = translate_user_role( $details['name'] );
                            $control_roles[$role] = $name;
                        }
                        return array(
                            '_rsc_user_role_rsc_post_meta' => array(
                            'type'        => 'SELECT2',
                            'label'       => __( 'Select a User Role', 'rsc' ),
                            'options'     => $control_roles,
                            'default'     => [],
                            'multiple'    => true,
                            'label_block' => true,
                            'condition'   => array(
                            '_rsc_content_availability_rsc_post_meta' => 'role',
                        ),
                        ),
                        );
                    }
                    
                    break;
                case 'capability':
                    
                    if ( !$return ) {
                        $rsc_capability_rsc = Restricted_Content::get_meta_value(
                            $id,
                            '_rsc_capability',
                            true,
                            $metabox_type,
                            $widget,
                            $widget_instance
                        );
                        ?>
                        <label><?php 
                        _e( 'User Capability', 'rsc' );
                        ?></label>
                        <input type="text" name="<?php 
                        echo  esc_attr( Restricted_Content::get_field_name( '_rsc_capability_rsc_post_meta', $widget, $widget_instance ) ) ;
                        ?>" value="<?php 
                        echo  ( isset( $rsc_capability_rsc ) ? esc_attr( $rsc_capability_rsc ) : '' ) ;
                        ?>" placeholder="manage_options"/>
                        <?php 
                    } else {
                        return array(
                            '_rsc_capability_rsc_post_meta' => array(
                            'type'        => 'TEXT',
                            'label'       => __( 'User Capability', 'rsc' ),
                            'label_block' => true,
                            'placeholder' => 'manage_options',
                            'condition'   => array(
                            '_rsc_content_availability_rsc_post_meta' => 'capability',
                        ),
                        ),
                        );
                    }
                    
                    break;
                case 'tickera':
                    global  $tc ;
                    $rsc_tickera_users = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_tickera_users',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    if ( !isset( $rsc_tickera_users ) || empty($rsc_tickera_users) ) {
                        $rsc_tickera_users = 'anything';
                    }
                    ?>
                    <label><?php 
                    _e( 'Who Purchased', 'rsc' );
                    ?></label>
                    <input type="radio" name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_tickera_users_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>" class="rsc_tickera_radio" value="anything" <?php 
                    checked( $rsc_tickera_users, 'anything', true );
                    ?> /> <?php 
                    _e( 'Any ticket type', 'rsc' );
                    ?>
                    <br/>
                    <?php 
                    
                    if ( apply_filters( 'tc_is_woo', false ) == false ) {
                        /* Tickera is in the Bridge mode */
                        ?>
                        <input type="radio" name="<?php 
                        echo  esc_attr( Restricted_Content::get_field_name( '_rsc_tickera_users_rsc_post_meta', $widget, $widget_instance ) ) ;
                        ?>" class="rsc_tickera_radio" value="event" <?php 
                        checked( $rsc_tickera_users, 'event', true );
                        ?> /> <?php 
                        _e( 'Any ticket type for a specific event', 'rsc' );
                        ?><br/>
                    <?php 
                    }
                    
                    ?>
                    <input type="radio" name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_tickera_users_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>" class="rsc_tickera_radio" value="ticket_type" <?php 
                    checked( $rsc_tickera_users, 'ticket_type', true );
                    ?> /> <?php 
                    _e( 'Specific ticket type', 'rsc' );
                    ?><br/>
                    <div class="rsc_sub_sub rsc_tickera_event rsc_sub_hide rsc_sub_sub_metabox_event">
                        <select name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_tickera_users_event_rsc_post_meta[]', $widget, $widget_instance ) ) ;
                    ?>" multiple>
                            <?php 
                    $rsc_tickera_users_event = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_tickera_users_event',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    if ( !isset( $rsc_tickera_users_event ) || empty($rsc_tickera_users_event) ) {
                        $rsc_tickera_users_event = '';
                    }
                    $rsc_events = get_posts( array(
                        'post_type'      => 'tc_events',
                        'posts_per_page' => -1,
                    ) );
                    foreach ( $rsc_events as $event ) {
                        ?>
                                <option value="<?php 
                        echo  (int) $event->ID ;
                        ?>" <?php 
                        echo  ( is_array( $rsc_tickera_users_event ) && in_array( $event->ID, $rsc_tickera_users_event ) ? 'selected' : '' ) ;
                        ?>><?php 
                        echo  rsc_esc_html( $event->post_title ) ;
                        ?></option>
                            <?php 
                    }
                    ?>
                        </select>
                    </div>
                    <div class="rsc_sub_sub rsc_tickera_ticket_type rsc_sub_hide rsc_sub_sub_metabox_ticket_type">
                        <select name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_tickera_users_ticket_type_rsc_post_meta[]', $widget, $widget_instance ) ) ;
                    ?>" multiple>
                            <?php 
                    $rsc_tickera_users_ticket_type = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_tickera_users_ticket_type',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    if ( !isset( $rsc_tickera_users_ticket_type ) || empty($rsc_tickera_users_ticket_type) ) {
                        $rsc_tickera_users_ticket_type = '';
                    }
                    
                    if ( apply_filters( 'tc_is_woo', false ) == false ) {
                        // Tickera is in the Bridge mode
                        $rsc_ticket_types = get_posts( array(
                            'post_type'      => 'tc_tickets',
                            'posts_per_page' => -1,
                        ) );
                    } else {
                        $rsc_ticket_types = get_posts( array(
                            'post_type'      => 'product',
                            'posts_per_page' => -1,
                            'meta_key'       => '_event_name',
                        ) );
                    }
                    
                    foreach ( $rsc_ticket_types as $ticket_type ) {
                        $event_id = get_post_meta( $ticket_type->ID, apply_filters( 'tc_event_name_field_name', 'event_name' ), true );
                        $event_title = get_the_title( $event_id );
                        if ( empty($event_title) ) {
                            $event_title = sprintf( __( 'Event ID: %s', 'rsc' ), $event_id );
                        }
                        ?>
                                <option value="<?php 
                        echo  (int) $ticket_type->ID ;
                        ?>" <?php 
                        echo  ( is_array( $rsc_tickera_users_ticket_type ) && in_array( $ticket_type->ID, $rsc_tickera_users_ticket_type ) ? 'selected' : '' ) ;
                        ?>><?php 
                        echo  rsc_esc_html( $ticket_type->post_title . ' (' . $event_title . ')' . ' (#' . (int) $ticket_type->ID . ')' ) ;
                        ?></option><?php 
                    }
                    ?>
                        </select>
                    </div>
                    <?php 
                    break;
                case 'woo':
                    global  $tc ;
                    $rsc_woo_users = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_woo_users',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    $rsc_woo_users_time = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_woo_users_time',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    $days_selected = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_woo_users_time_days',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    $hours_selected = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_woo_users_time_hours',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    $minutes_selected = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_woo_users_time_minutes',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    if ( !isset( $rsc_woo_users ) || empty($rsc_woo_users) ) {
                        $rsc_woo_users = 'anything';
                    }
                    if ( !isset( $rsc_woo_users_time ) || empty($rsc_woo_users_time) ) {
                        $rsc_woo_users_time = 'indefinitely';
                    }
                    ?>
                    <label><?php 
                    _e( 'Who Purchased', 'rsc' );
                    ?></label>
                    <input type="radio" name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_woo_users_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>" class="rsc_woo_radio" value="anything" <?php 
                    checked( $rsc_woo_users, 'anything', true );
                    ?> /> <?php 
                    _e( 'Any product', 'rsc' );
                    ?><br/>
                    <input type="radio" name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_woo_users_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>" class="rsc_woo_radio" value="product" <?php 
                    checked( $rsc_woo_users, 'product', true );
                    ?> /> <?php 
                    _e( 'Specific product', 'rsc' );
                    ?><br/>
                    <div class="rsc_sub_sub rsc_woo_product rsc_sub_hide rsc_sub_sub_metabox_product">
                        <select name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_woo_users_product_rsc_post_meta[]', $widget, $widget_instance ) ) ;
                    ?>" multiple>
                            <?php 
                    $rsc_woo_users_product = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_woo_users_product',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    if ( !isset( $rsc_woo_users_product ) || empty($rsc_woo_users_product) ) {
                        $rsc_woo_users_product = '';
                    }
                    $woo_products = get_posts( array(
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                    ) );
                    foreach ( $woo_products as $product ) {
                        ?>
                                <option value="<?php 
                        echo  (int) $product->ID ;
                        ?>" <?php 
                        echo  ( is_array( $rsc_woo_users_product ) && in_array( $product->ID, $rsc_woo_users_product ) ? 'selected' : '' ) ;
                        ?>><?php 
                        echo  rsc_esc_html( $product->post_title . ' (#' . (int) $product->ID . ')' ) ;
                        ?></option>
                            <?php 
                    }
                    ?>
                        </select>
                        <br/><br/>
                        <label><?php 
                    _e( 'Duration', 'rsc' );
                    ?></label>
                        <input type="radio" name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_woo_users_time_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>" class="rsc_woo_time_radio" value="indefinitely" <?php 
                    checked( $rsc_woo_users_time, 'indefinitely', true );
                    ?> /> <?php 
                    _e( 'Indefinitely', 'rsc' );
                    ?><br/>
                        <input type="radio" name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_woo_users_time_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>" class="rsc_woo_time_radio" value="limited" <?php 
                    checked( $rsc_woo_users_time, 'limited', true );
                    ?> /> <?php 
                    _e( 'Limited time after purchase', 'rsc' );
                    ?><br/>
                        <div class="rsc_woo_times">
                            <label>
                                <?php 
                    _e( 'Days:', 'tc' );
                    ?><br/>
                                <select name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_woo_users_time_days_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>">
                                    <?php 
                    for ( $day = apply_filters( 'rsc_woo_time_day_min', 0 ) ;  $day <= apply_filters( 'rsc_woo_time_day_max', 365 ) ;  $day++ ) {
                        ?>
                                        <option value="<?php 
                        echo  esc_attr( $day ) ;
                        ?>" <?php 
                        selected( $day, $days_selected, true );
                        ?>><?php 
                        echo  rsc_esc_html( $day ) ;
                        ?></option><?php 
                    }
                    ?>
                                </select>
                            </label>
                            <label>
                                <?php 
                    _e( 'Hours:', 'tc' );
                    ?><br/>
                                <select name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_woo_users_time_hours_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>">
                                    <?php 
                    for ( $hour = apply_filters( 'rsc_woo_time_hour_min', 0 ) ;  $hour <= apply_filters( 'rsc_woo_time_hour_max', 24 ) ;  $hour++ ) {
                        ?>
                                        <option value="<?php 
                        echo  esc_attr( $hour ) ;
                        ?>" <?php 
                        selected( $hour, $hours_selected, true );
                        ?>><?php 
                        echo  rsc_esc_html( $hour ) ;
                        ?></option><?php 
                    }
                    ?>
                                </select>
                            </label>
                            <label>
                                <?php 
                    _e( 'Minutes:', 'tc' );
                    ?><br/>
                                <select name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_woo_users_time_minutes_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>">
                                    <?php 
                    for ( $minute = apply_filters( 'rsc_woo_time_minute_min', 0 ) ;  $minute <= apply_filters( 'rsc_woo_time_minute_', 60 ) ;  $minute++ ) {
                        ?>
                                        <option value="<?php 
                        echo  esc_attr( $minute ) ;
                        ?>" <?php 
                        selected( $minute, $minutes_selected, true );
                        ?>><?php 
                        echo  rsc_esc_html( $minute ) ;
                        ?></option><?php 
                    }
                    ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <?php 
                    break;
                case 'edd':
                    global  $tc ;
                    $rsc_edd_users = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_edd_users',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    $rsc_edd_users_time = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_edd_users_time',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    $days_selected = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_edd_users_time_days',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    $hours_selected = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_edd_users_time_hours',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    $minutes_selected = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_edd_users_time_minutes',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    if ( !isset( $rsc_edd_users ) || empty($rsc_edd_users) ) {
                        $rsc_edd_users = 'anything';
                    }
                    if ( !isset( $rsc_edd_users_time ) || empty($rsc_edd_users_time) ) {
                        $rsc_edd_users_time = 'indefinitely';
                    }
                    ?>
                    <label><?php 
                    _e( 'Who Purchased', 'rsc' );
                    ?></label>
                    <input type="radio" name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_edd_users_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>" class="rsc_edd_radio" value="anything" <?php 
                    checked( $rsc_edd_users, 'anything', true );
                    ?> /> <?php 
                    _e( 'Any product', 'rsc' );
                    ?><br/>
                    <input type="radio" name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_edd_users_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>" class="rsc_edd_radio" value="product" <?php 
                    checked( $rsc_edd_users, 'product', true );
                    ?> /> <?php 
                    _e( 'Specific product', 'rsc' );
                    ?><br/>
                    <div class="rsc_sub_sub rsc_edd_product rsc_sub_hide rsc_sub_sub_metabox_product">
                        <select name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_edd_users_product_rsc_post_meta[]', $widget, $widget_instance ) ) ;
                    ?>" multiple>
                            <?php 
                    $rsc_edd_users_product = Restricted_Content::get_meta_value(
                        $id,
                        '_rsc_edd_users_product',
                        true,
                        $metabox_type,
                        $widget,
                        $widget_instance
                    );
                    if ( !isset( $rsc_edd_users_product ) || empty($rsc_edd_users_product) ) {
                        $rsc_edd_users_product = '';
                    }
                    $edd_products = get_posts( array(
                        'post_type'      => 'download',
                        'posts_per_page' => -1,
                    ) );
                    foreach ( $edd_products as $product ) {
                        ?>
                                <option value="<?php 
                        echo  (int) $product->ID ;
                        ?>" <?php 
                        echo  ( is_array( $rsc_edd_users_product ) && in_array( $product->ID, $rsc_edd_users_product ) ? 'selected' : '' ) ;
                        ?>><?php 
                        echo  rsc_esc_html( $product->post_title ) ;
                        ?></option>
                            <?php 
                    }
                    ?>
                        </select>
                        <br/><br/>
                        <label><?php 
                    _e( 'Duration', 'rsc' );
                    ?></label>
                        <input type="radio" name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_edd_users_time_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>" class="rsc_edd_time_radio" value="indefinitely" <?php 
                    checked( $rsc_edd_users_time, 'indefinitely', true );
                    ?> /> <?php 
                    _e( 'Indefinitely', 'rsc' );
                    ?><br/>
                        <input type="radio" name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_edd_users_time_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>" class="rsc_edd_time_radio" value="limited" <?php 
                    checked( $rsc_edd_users_time, 'limited', true );
                    ?> /> <?php 
                    _e( 'Limited time after purchase', 'rsc' );
                    ?><br/>
                        <div class="rsc_edd_times">
                            <label>
                                <?php 
                    _e( 'Days:', 'tc' );
                    ?><br/>
                                <select name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_edd_users_time_days_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>">
                                    <?php 
                    for ( $day = apply_filters( 'rsc_edd_time_day_min', 0 ) ;  $day <= apply_filters( 'rsc_edd_time_day_max', 365 ) ;  $day++ ) {
                        ?>
                                        <option value="<?php 
                        echo  esc_attr( $day ) ;
                        ?>" <?php 
                        selected( $day, $days_selected, true );
                        ?>><?php 
                        echo  rsc_esc_html( $day ) ;
                        ?></option><?php 
                    }
                    ?>
                                </select>
                            </label>
                            <label>
                                <?php 
                    _e( 'Hours:', 'tc' );
                    ?><br/>
                                <select name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_edd_users_time_hours_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>">
                                    <?php 
                    for ( $hour = apply_filters( 'rsc_edd_time_hour_min', 0 ) ;  $hour <= apply_filters( 'rsc_edd_time_hour_max', 24 ) ;  $hour++ ) {
                        ?>
                                        <option value="<?php 
                        echo  esc_attr( $hour ) ;
                        ?>" <?php 
                        selected( $hour, $hours_selected, true );
                        ?>><?php 
                        echo  rsc_esc_html( $hour ) ;
                        ?></option><?php 
                    }
                    ?>
                                </select>
                            </label>
                            <label>
                                <?php 
                    _e( 'Minutes:', 'tc' );
                    ?><br/>
                                <select name="<?php 
                    echo  esc_attr( Restricted_Content::get_field_name( '_rsc_edd_users_time_minutes_rsc_post_meta', $widget, $widget_instance ) ) ;
                    ?>">
                                    <?php 
                    for ( $minute = apply_filters( 'rsc_edd_time_minute_min', 0 ) ;  $minute <= apply_filters( 'rsc_edd_time_minute_', 60 ) ;  $minute++ ) {
                        ?>
                                        <option value="<?php 
                        echo  esc_attr( $minute ) ;
                        ?>" <?php 
                        selected( $minute, $minutes_selected, true );
                        ?>><?php 
                        echo  rsc_esc_html( $minute ) ;
                        ?></option><?php 
                    }
                    ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <?php 
                    break;
            }
            if ( !$return ) {
                ?>
                </div>
                <?php 
            }
        }
        
        /**
         * Execute functions
         * Used in show_metabox method
         *
         * @param bool $function_name
         * @param array $args
         */
        public static function execute_function( $function_name = false, $args = array() )
        {
            call_user_func_array( $function_name, $args );
        }
    
    }
    $rsc = new Restricted_Content();
}
