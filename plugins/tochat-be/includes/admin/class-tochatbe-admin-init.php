<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Admin_Init {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 20 );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_plugin_settings' ), 20 );

        add_action( 'init', array( $this, 'redirect_to_just_whatsapp_icon_tab' ) );
        add_action( 'admin_init', array( $this, 'redirect_to_add_agent_if_no_agent_added' ) );

    }

    public function admin_enqueue_scripts( $hook ) {
        if ( 'tochat-be_page_to-chat-be-whatsapp_appearence' === $hook
        || 'tochat-be_page_to-chat-be-whatsapp_settings' === $hook
        || 'tochat-be_page_to-chat-be-whatsapp_shortcode' === $hook
        || 'tochat-be_page_to-chat-be-whatsapp_gdpr' === $hook
        || 'tochat-be_page_to-chat-be-whatsapp_click-log' === $hook
        || 'tochatbe_agent' === get_post_type()
        || 'shop_order' === get_post_type()
        || 'index.php' === $hook ) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );

            wp_enqueue_style( 'jquery.timepicker', TOCHATBE_PLUGIN_URL . 'assets/css/jquery.timepicker.min.css', array(), '1.3.5' );
            wp_enqueue_script( 'jquery.timepicker', TOCHATBE_PLUGIN_URL . 'assets/js/jquery.timepicker.min.js', array(), '1.3.5', true );

            wp_enqueue_style( 'select2', TOCHATBE_PLUGIN_URL . 'assets/css/select2.min.css', array(), '4.0.12' );
            wp_enqueue_script( 'select2', TOCHATBE_PLUGIN_URL . 'assets/js/select2.min.js', array(), '4.0.12', true );

            wp_enqueue_script( 'admin-tochatbe', TOCHATBE_PLUGIN_URL . 'assets/js/admin-tochatbe-script.js', array(), TOCHATBE_PLUGIN_URL, true );
            wp_enqueue_style( 'admin-tochatbe', TOCHATBE_PLUGIN_URL . 'assets/css/admin-tochatbe-style.css', array(), TOCHATBE_PLUGIN_URL );
            wp_localize_script( 'admin-tochatbe', 'tochatbeAdmin', array(
                'ajax_url'       => admin_url( 'admin-ajax.php' ),
                'security_token' => wp_create_nonce( 'tochatbe_admin_security_token' ),
            ) );
        }

    }

    public function admin_menu() {
        add_menu_page( 
            'TOCHAT.BE', 
            'TOCHAT.BE', 
            'manage_options', 
            'to-chat-be-whatsapp', 
            '', 
            'dashicons-format-chat' 
        );
        add_submenu_page( 
            'to-chat-be-whatsapp', 
            'Appearence', 
            'Appearence', 
            'manage_options', 
            'to-chat-be-whatsapp_appearence', 
            array( $this, 'admin_appearance_settings' ) 
        );
        add_submenu_page( 
            'to-chat-be-whatsapp', 
            'Settings', 
            'Settings', 
            'manage_options', 
            'to-chat-be-whatsapp_settings', 
            array( $this, 'admin_basic_settings' ) 
        );
        add_submenu_page( 
            'to-chat-be-whatsapp', 
            'Shortcode', 
            'Shortcode', 
            'manage_options', 
            'to-chat-be-whatsapp_shortcode', 
            array( $this, 'admin_shortcode_page' ) 
        );
        add_submenu_page( 
            'to-chat-be-whatsapp', 
            'GDPR', 
            'GDPR', 
            'manage_options', 
            'to-chat-be-whatsapp_gdpr', 
            array( $this, 'admin_gdpr_settings' ) 
        );
        add_submenu_page( 
            'to-chat-be-whatsapp', 
            'Google Analytics', 
            'Google Analytics', 
            'manage_options', 
            'to-chat-be-google_analytics', 
            array( $this, 'admin_google_analytics' ) 
        );
        add_submenu_page( 
            'to-chat-be-whatsapp', 
            'Facebook Analytics', 
            'Facebook Analytics', 
            'manage_options', 
            'to-chat-be-facebook_analytics', 
            array( $this, 'admin_facebook_analytics' ) 
        );
        add_submenu_page( 
            'to-chat-be-whatsapp', 
            'Click Log', 
            'Click Log', 
            'manage_options', 
            'to-chat-be-whatsapp_click-log', 
            array( $this, 'admin_click_log_page' ) 
        );
    }

    public function redirect_to_just_whatsapp_icon_tab() {
        if ( 'yes' === tochatbe_just_whatsapp_icon_option( 'status' ) ) {
            if ( isset( $_GET['post_type'] ) && 'tochatbe_agent' == $_GET['post_type'] ) {
                wp_safe_redirect( admin_url( 'admin.php?page=to-chat-be-whatsapp_settings&tab=just_whatsapp_icon' ) );
                exit;
            }
        }
    }

    public function admin_appearance_settings() {
        require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/html-appearance-settings.php';
    }

    public function admin_basic_settings() {
        require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/html-basic-settings.php';
    }

    public function admin_gdpr_settings() {
        require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/html-gdpr-settings.php';
    }

    public function admin_shortcode_page() {
        require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/html-shortcode-page.php';
    }
    
    public function admin_google_analytics() {
        require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/html-google-analytics-page.php';
    }
    
    public function admin_facebook_analytics() {
        require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/html-facebook-analytics-page.php';
    }

    public function admin_click_log_page() {
        require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/html-click-log.php';
    }

    public function register_plugin_settings() {
        register_setting( 'tochatbe-appearance-settings', 'tochatbe_appearance_settings', array( $this, 'sanitize_appearance_settings' ) );
        register_setting( 'tochatbe-basic-settings', 'tochatbe_basic_settings', array( $this, 'sanitize_basic_settings' ) );
        register_setting( 'tochatbe-gdpr-settings', 'tochatbe_gdpr_settings', array( $this, 'sanitize_gdpr_settings' ) );
        register_setting( 'tochatbe-type-and-chat-settings', 'tochatbe_type_and_chat_settings', array( $this, 'sanitize_type_and_chat_settings' ) );
        register_setting( 'tochatbe-just-whatsapp-icon-settings', 'tochatbe_just_whatsapp_icon_settings', array( $this, 'sanitize_just_whatsapp_icon_settings' ) );
        register_setting( 'tochatbe-google-analytics-settings', 'tochatbe_google_analytics_settings', array( $this, 'sanitize_google_analytics_settings' ) );
        register_setting( 'tochatbe-facebook-analytics-settings', 'tochatbe_facebook_analytics_settings', array( $this, 'sanitize_facebook_analytics_settings' ) );
        register_setting( 'tochatbe-woo-order-button-settings', 'tochatbe_woo_order_button_settings', array( $this, 'sanitize_woo_order_button_settings' ) );
    }

    public function sanitize_appearance_settings( $input ) {
        $input['background_color']           = sanitize_hex_color( $input['background_color'] );
        $input['text_color']                 = sanitize_hex_color( $input['text_color'] );
        $input['about_message']              = sanitize_textarea_field( $input['about_message'] );
        $input['triiger_btn_text']           = sanitize_text_field( $input['triiger_btn_text'] );
        
        return $input;
    }
    
    public function sanitize_basic_settings( $input ) {
        $input['location']             = sanitize_text_field( $input['location'] );
        $input['on_mobile']            = isset( $input['on_mobile'] ) ? 'yes' : 'no';
        $input['on_desktop']           = isset( $input['on_desktop'] ) ? 'yes' : 'no';
        $input['auto_popup_status']    = isset( $input['auto_popup_status'] ) ? 'yes' : 'no';
        $input['auto_popup_delay']     = intval( $input['auto_popup_delay'] );
        $input['filter_by_pages']   = array(
            'on_all_pages'  => isset( $input['filter_by_pages']['on_all_pages'] ) ? 'yes' : 'no',
            'on_front_page' => isset( $input['filter_by_pages']['on_front_page'] ) ? 'yes' : 'no',
            'include_pages' => isset( $input['filter_by_pages']['include_pages'] ) ? $input['filter_by_pages']['include_pages'] : array(),
            'exclude_pages' => isset( $input['filter_by_pages']['exclude_pages'] ) ? $input['filter_by_pages']['exclude_pages'] : array(),
        );
        $input['schedule']          = array(
            'monday'    => array(
                'status' => isset( $input['schedule']['monday']['status'] ) ? 'yes' : 'no',
                'start'  => esc_html( $input['schedule']['monday']['start'] ),
                'end'    => esc_html( $input['schedule']['monday']['end'] ),
            ),
            'tuesday'   => array(
                'status' => isset( $input['schedule']['tuesday']['status'] ) ? 'yes' : 'no',
                'start'  => esc_html( $input['schedule']['tuesday']['start'] ),
                'end'    => esc_html( $input['schedule']['tuesday']['end'] ),
            ),
            'wednesday' => array(
                'status' => isset( $input['schedule']['wednesday']['status'] ) ? 'yes' : 'no',
                'start'  => esc_html( $input['schedule']['wednesday']['start'] ),
                'end'    => esc_html( $input['schedule']['wednesday']['end'] ),
            ),
            'thursday'  => array(
                'status' => isset( $input['schedule']['thursday']['status'] ) ? 'yes' : 'no',
                'start'  => esc_html( $input['schedule']['thursday']['start'] ),
                'end'    => esc_html( $input['schedule']['thursday']['end'] ),
            ),
            'friday'    => array(
                'status' => isset( $input['schedule']['friday']['status'] ) ? 'yes' : 'no',
                'start'  => esc_html( $input['schedule']['friday']['start'] ),
                'end'    => esc_html( $input['schedule']['friday']['end'] ),
            ),
            'saturday'  => array(
                'status' => isset( $input['schedule']['saturday']['status'] ) ? 'yes' : 'no',
                'start'  => esc_html( $input['schedule']['saturday']['start'] ),
                'end'    => esc_html( $input['schedule']['saturday']['end'] ),
            ),
            'sunday'    => array(
                'status' => isset( $input['schedule']['sunday']['status'] ) ? 'yes' : 'no',
                'start'  => esc_html( $input['schedule']['sunday']['start'] ),
                'end'    => esc_html( $input['schedule']['sunday']['end'] ),
            ),
        );        
        $input['filter_everywhere'] = isset( $input['filter_everywhere'] ) ? 'yes' : 'no';
        $input['custom_css']           = wp_kses_post( $input['custom_css'] );
        
        return $input;
    }
    
    public function sanitize_gdpr_settings( $input ) {
        $input['status']        = isset( $input['status'] ) ? 'yes' : 'no';
        $input['message']       = sanitize_textarea_field( $input['message'] );
        $input['privacy_page']  = intval( $input['privacy_page'] );
        
        return $input;
    }
    
    public function sanitize_type_and_chat_settings( $input ) {
        $input['type_and_chat']        = isset( $input['type_and_chat'] ) ? 'yes' : 'no';
        $input['type_and_chat_number'] = sanitize_text_field( $input['type_and_chat_number'] );
        $input['type_and_chat_placeholder']  = sanitize_text_field( $input['type_and_chat_placeholder'] );

        return $input;
    }

    public function sanitize_just_whatsapp_icon_settings( $input ) {
        $input['status']    = isset( $input['status'] ) ? 'yes' : 'no';
        $input['number']    = sanitize_text_field( $input['number'] );
        $input['icon_link'] = esc_url_raw( $input['icon_link'] );
        
        return $input;
    }
    
    public function sanitize_google_analytics_settings( $input ) {
        $input['status']    = isset( $input['status'] ) ? 'yes' : 'no';
        $input['category']  = sanitize_text_field( $input['category'] );
        $input['action']    = sanitize_text_field( $input['action'] );
        $input['label']     = sanitize_text_field( $input['label'] );

        return $input;
    }
    
    public function sanitize_facebook_analytics_settings( $input ) {
        $input['status']    = isset( $input['status'] ) ? 'yes' : 'no';
        $input['name']      = sanitize_text_field( $input['name'] );
        $input['label']     = sanitize_text_field( $input['label'] );

        return $input;
    }

    public function sanitize_woo_order_button_settings( $input ) {
        $input['status']                       = isset( $input['status'] ) ? 'yes' : 'no';
        $input['pre_message_processing_order'] = sanitize_textarea_field( $input['pre_message_processing_order'] );
        $input['pre_message_canceled_order']   = sanitize_textarea_field( $input['pre_message_canceled_order'] );
        $input['pre_message_completed_order']  = sanitize_textarea_field( $input['pre_message_completed_order'] );

        $woo_statuses = tochatbe_get_woo_order_statuses();

        if ( $woo_statuses ) {
            foreach ( $woo_statuses as $status => $label ) {
                $status = str_replace( 'wc-', '', $status );
                $status = str_replace( '-', '_', $status );

                $input['pre_message_' . $status . '_order'] = sanitize_textarea_field( $input['pre_message_' . $status . '_order'] );
            }
        }
        
        return $input;
    }

    public function redirect_to_add_agent_if_no_agent_added() {
        global $pagenow;

        if ( 'edit.php' !== $pagenow || ! isset( $_GET['post_type'] ) || 'tochatbe_agent' !== $_GET['post_type'] ) {
            return;
        }

        $agents = get_posts( array(
            'post_type'      => 'tochatbe_agent',
            'posts_per_page' => -1,
        ) );

        if ( $agents ) {
            return;
        }

        wp_safe_redirect( admin_url( 'post-new.php?post_type=tochatbe_agent' ) );
        exit;
    } 
}

new TOCHATBE_Admin_Init;