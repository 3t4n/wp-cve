<?php

use WpLHLAdminUi\LicenseKeys\LicenseKeyHandler;
use WpLHLAdminUi\Forms\AdminForm;

class Terms_Popup_On_User_Login_Admin_Settings {

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
     * @var      array    $tpul_settings_general_options    The settings for the modal.
     */
    private $tpul_settings_general_options;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $tpul_settings_term_modal_options    The settings for the modal.
     */
    private $tpul_settings_term_modal_options;


    /**
     * The version of this plugin.
     *
     * @since    1.0.7
     * @access   private
     * @var      array    $tpul_settings_term_modal_display_options    The settings for the modal.
     */
    private $tpul_settings_term_modal_display_options;
    private $email_option_getter;
    private $email_options;


    private $license_key_handler;
    private $license_key_valid;
    private $suport_token;


    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->license_key_handler = new LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());
        $this->license_key_valid = $this->license_key_handler->is_active();
        $this->suport_token = $this->license_key_handler->get_support_token();
        // $this->license_key_valid = false;

        $this->email_option_getter = new TPUL_Email_Options();
        $this->email_options = $this->email_option_getter->get_options();
    }

    /**
     * This function introduces the theme options into the 'Settings' menu and into a top-level
     * 'Perfecto Portfolio' menu.
     */
    public function setup_plugin_options_menu() {
        add_submenu_page(
            'options-general.php',
            'Terms Popup On User Login Settings',                     // The title to be displayed in the browser window for this page.
            'Terms Popup On User Login',                            // The text to be displayed for this menu item
            'manage_options',                                // Which type of users can see this menu item
            'terms_popup_on_user_login_options',                    // The unique ID - that is, the slug - for this menu item
            array($this, 'render_settings_page_content')    // The name of the function to call when rendering this menu's page
        );
    }

    /**---------------------------------------------------------------------
     * Default Options
     ---------------------------------------------------------------------*/

    public function default_general_options() {
        $gen_options = new TPUL_General_Options();
        return $gen_options->default_options();
    }

    public function default_terms_modal_options() {
        $gen_options = new TPUL_Modal_Options();
        return $gen_options->default_options();
    }

    public function default_terms_modal_display_options() {
        $gen_options = new TPUL_Display_Options();
        return $gen_options->default_options();
    }

    public function default_terms_modal_woo_options() {
        $defaults = array(
            'terms_modal_woo_display_on'                =>   'product_pages',
            'terms_modal_woo_display_user_type'           =>    'anonymous_only',
            'terms_modal_woo_log_out_user'                =>    false,
            'until_browser_is_closed'                    =>    "every_time",

        );
        return $defaults;
    }

    // public function default_terms_modal_email_options() {
    //     return $this->email_option_getter->default_options();
    // }


    /**---------------------------------------------------------------------
     * Settings fields for General Options
     ---------------------------------------------------------------------*/

    /**
     * Initializes the theme's activated options
     *
     * This function is registered with the 'admin_init' hook.
     */
    public function initialize_general_options() {

        if (false == get_option('tpul_settings_general_options')) {
            $default_array = $this->default_general_options();
            update_option('tpul_settings_general_options', $default_array);
        }


        /**
         * Add Section
         */
        add_settings_section(
            'tpul_general_section',
            '<span class="dashicons dashicons-admin-network settings-page-icon"></span> ' . __('General Settings and License Key', 'terms-popup-on-user-login'),
            array($this, 'general_options_callback'),
            'tpul_settings_general_options'
        );

        /**
         * Add option to Section
         */

        add_settings_field(
            'modal_to_show',
            __('Terms Popup Behaviour', 'terms-popup-on-user-login'),
            array($this, 'dropdown_select_field_render'),
            'tpul_settings_general_options',
            'tpul_general_section'
        );

        // add_settings_field(
        //     'tpul_terms_modal_demo',
        //     __( 'Demo Terms Popup', 'terms-popup-on-user-login' ),
        //     array( $this, 'demo_terms_modal_render'),
        //     'tpul_settings_general_options',
        //     'tpul_general_section'
        // );

        add_settings_field(
            'tplu_license_key',
            __('Enter License Key', 'terms-popup-on-user-login'),
            array($this, 'tplu_license_key_render'),
            'tpul_settings_general_options',
            'tpul_general_section'
        );

        /**
         * Register Section
         */
        register_setting(
            'tpul_settings_general_options',
            'tpul_settings_general_options',
            array($this, 'validate_general_options')
        );
    }

    /**
     * The Callback to assist with extra text
     */
    public function general_options_callback() {
        // echo '<p>' . esc_html__( '', 'terms-popup-on-user-login' ) . '</p>';
    }

    /**
     * Validator Callback to assist in validation
     */
    public function validate_general_options($input) {

        // Create our array for storing the validated options
        $output = array();

        // Loop through each of the incoming options
        foreach ($input as $key => $value) {
            // Check to see if the current option has a value. If so, process it.
            if (isset($input[$key])) {
                // Strip all HTML and PHP tags and properly handle quoted strings
                $output[$key] = strip_tags(stripslashes($input[$key]));
            } // end if
        } // end foreach

        // Return the array processing any additional functions filtered by this action
        return apply_filters('validate_general_options', $output, $input);
    }

    /**---------------------------------------------------------------------
     * Settings fields for Terms Popup
     ---------------------------------------------------------------------*/

    /**
     * Initializes the theme's activated options
     *
     * This function is registered with the 'admin_init' hook.
     */
    public function initialize_terms_modal_options() {

        // delete_option('tpul_settings_term_modal_options');

        if (false == get_option('tpul_settings_term_modal_options')) {
            $default_array = $this->default_terms_modal_options();
            update_option('tpul_settings_term_modal_options', $default_array);
        }

        /**
         * Add Section
         */
        add_settings_section(
            'tpul_term_modal_section',
            '<span class="dashicons dashicons-admin-generic settings-page-icon"></span> ' . __('Labels, Content and Redirects', 'terms-popup-on-user-login'),
            array($this, 'term_modal_options_callback'),
            'tpul_settings_term_modal_options'
        );

        /**
         * Add option to Section
         */

        add_settings_field(
            'terms_modal_title',
            __('Popup title', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_title_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_subtitle',
            __('Subtitle', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_subtitle_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'delimiter_1',
            __('', 'terms-popup-on-user-login'),
            array($this, 'delimiter_accept_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_accept_button',
            __('Accept - button label', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_accept_button_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_accept_enable',
            __('Accept - button enabled by default', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_accept_enable_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_accept_redirect',
            __('Accept - redirect URL', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_accept_redirect_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_agreed_text',
            __('Accept - confirmation text', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_agreed_text_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'delimiter_2',
            __('', 'terms-popup-on-user-login'),
            array($this, 'delimiter_decline_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_decline_button',
            __('Decline - button label', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_decline_button_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_decline_redirect',
            __('Decline - redirect URL', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_decline_redirect_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_logout_text',
            __('Decline - confirmation text', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_logout_text_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'delimiter_3',
            __('', 'terms-popup-on-user-login'),
            array($this, 'delimiter_content_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_content',
            __('Content to show as terms in Popup', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_content_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_pageid',
            __('Show your own page in terms in Popup', 'terms-popup-on-user-login'),
            array($this, 'dropdown_select_field_pages_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_font_size',
            __('Font size for term text in popup', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_font_size_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'delimiter_0',
            __('', 'terms-popup-on-user-login'),
            array($this, 'delimiter_visibility_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_show_every_login',
            __('A) Show popup on every login ', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_show_every_login_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_show_every_login_for_declined',
            __('B) Show popup on every login. But, only for those who have not accepted yet.', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_show_every_login_for_declined_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_show_only_once',
            __('C) Show popup ONCE, no matter if they accepted or declined', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_show_only_once_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_decline_nologout_render',
            __('Keep users logged in', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_decline_nologout_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'delimiter_4',
            __('', 'terms-popup-on-user-login'),
            array($this, 'delimiter_role_visibility_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_for_roles',
            __('Show modal only for selected user roles', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_for_roles_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'delimiter_5',
            __('', 'terms-popup-on-user-login'),
            array($this, 'delimiter_tracking_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_track_IP',
            __('Track User IP', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_track_IP_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_track_location',
            __('Track User Location', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_track_location_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'delimiter_6',
            __('', 'terms-popup-on-user-login'),
            array($this, 'delimiter_testing_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_txt_logger',
            __('Turn On TXT Logger', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_txt_logger_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_designated_test_user',
            __('Designated Test User', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_designated_test_user_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        add_settings_field(
            'terms_modal_asset_placement',
            __('Style Asset Placement', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_asset_placement_render'),
            'tpul_settings_term_modal_options',
            'tpul_term_modal_section'
        );

        /**
         * Register Section
         */
        register_setting(
            'tpul_settings_term_modal_options',
            'tpul_settings_term_modal_options',
            array($this, 'validate_term_modal_options')
        );
    }

    /**
     * The Callback to assist with extra text
     */
    public function term_modal_options_callback() {
        echo '<p>' . __('Terms and conditions popup that will not dismiss until user clicks <b>Accept</b> or <b>Decline</b> button. <br/>You can redirect the user after either of the button clicks. User will be signed out after in case he clicked the Decline button. ', 'terms-popup-on-user-login') . '</p>';
    }


    /**
     * Validator Callback to assist in validation
     */
    public function validate_term_modal_options($input) {

        // Create our array for storing the validated options
        $output = array();

        // Loop through each of the incoming options
        foreach ($input as $key => $value) {
            // Check to see if the current option has a value. If so, process it.

            if (isset($input[$key])) {
                if ($key == 'terms_modal_for_roles') {
                    // this is multi dimensional
                    foreach ($input['terms_modal_for_roles'] as $key => $value) {
                        $output['terms_modal_for_roles'][] = strip_tags(stripslashes($input['terms_modal_for_roles'][$key]));
                    }
                } else {
                    // Strip all HTML and PHP tags and properly handle quoted strings
                    $output[$key] = strip_tags(stripslashes($input[$key]));
                }
            } // end if

        } // end foreach

        // Return the array processing any additional functions filtered by this action
        return apply_filters('validate_term_modal_options', $output, $input);
    }


    /**---------------------------------------------------------------------
     * Settings fields for Display options
     ---------------------------------------------------------------------*/

    public function initialize_terms_modal_display_options() {
        // delete_option('tpul_settings_term_modal_display_options');
        // echo "hello";

        if (false == get_option('tpul_settings_term_modal_display_options')) {
            $default_array = $this->default_terms_modal_display_options();
            update_option('tpul_settings_term_modal_display_options', $default_array);
        }

        /**
         * Add Section
         */
        add_settings_section(
            'tpul_term_modal_display_section',
            '<span class="dashicons dashicons-admin-appearance settings-page-icon"></span> ' . __('Terms Popup Display Settings', 'terms-popup-on-user-login'),
            array($this, 'term_modal_display_options_callback'),
            'tpul_settings_term_modal_display_options'
        );

        /**
         * Add options to Section
         */


        add_settings_field(
            'terms_modal_width',
            __('Popup Width', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_width_render'),
            'tpul_settings_term_modal_display_options',
            'tpul_term_modal_display_section'
        );

        add_settings_field(
            'terms_modal_height',
            __('Popup Height', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_height_render'),
            'tpul_settings_term_modal_display_options',
            'tpul_term_modal_display_section'
        );

        // Accept Button
        add_settings_field(
            'terms_modal_acc_btn_size',
            __('Accept Button Size', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_acc_btn_size_render'),
            'tpul_settings_term_modal_display_options',
            'tpul_term_modal_display_section'
        );

        add_settings_field(
            'terms_modal_acc_btn_color',
            __('Accept Button Color', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_acc_btn_color_render'),
            'tpul_settings_term_modal_display_options',
            'tpul_term_modal_display_section'
        );

        add_settings_field(
            'terms_modal_acc_btn_txt_color',
            __('Accept Button Text Color', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_acc_btn_txt_color_render'),
            'tpul_settings_term_modal_display_options',
            'tpul_term_modal_display_section'
        );

        // Decline Button
        add_settings_field(
            'terms_modal_dec_btn_size',
            __('Decline Button Size', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_dec_btn_size_render'),
            'tpul_settings_term_modal_display_options',
            'tpul_term_modal_display_section'
        );

        add_settings_field(
            'terms_modal_dec_btn_color',
            __('Decline Button Color', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_dec_btn_color_render'),
            'tpul_settings_term_modal_display_options',
            'tpul_term_modal_display_section'
        );

        add_settings_field(
            'terms_modal_dec_btn_txt_color',
            __('Decline Button Text Color', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_dec_btn_txt_color_render'),
            'tpul_settings_term_modal_display_options',
            'tpul_term_modal_display_section'
        );

        /**
         * Register Section
         */
        register_setting(
            'tpul_settings_term_modal_display_options',
            'tpul_settings_term_modal_display_options',
            array($this, 'validate_term_modal_options')
        );
    }

    /**
     * The Callback to assist with extra text
     */
    public function term_modal_display_options_callback() {
        echo '<p>' . __('Customize the size and the colors in the popup. ', 'terms-popup-on-user-login') . '</p>';
    }


    /**---------------------------------------------------------------------
     * Settings fields for Woo options
     ---------------------------------------------------------------------*/

    public function initialize_terms_modal_woo_options() {
        // delete_option('tpul_settings_term_modal_woo_options');

        if (false == get_option('tpul_settings_term_modal_woo_options')) {
            $default_array = $this->default_terms_modal_woo_options();
            update_option('tpul_settings_term_modal_woo_options', $default_array);
        }

        /**
         * Add Section
         */
        add_settings_section(
            'tpul_term_modal_woo_section',
            '<span class="dashicons dashicons-cart settings-page-icon"></span> ' . __('Terms Popup WooCommerce Settings', 'terms-popup-on-user-login'),
            array($this, 'term_modal_woo_options_callback'),
            'tpul_settings_term_modal_woo_options'
        );

        /**
         * Add options to Section
         */

        add_settings_field(
            'terms_modal_woo_display_on',
            __('Display Popup on these Pages', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_woo_display_on_render'),
            'tpul_settings_term_modal_woo_options',
            'tpul_term_modal_woo_section'
        );


        add_settings_field(
            'terms_modal_woo_display_user_type',
            __('Display Popup for', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_woo_display_user_type_render'),
            'tpul_settings_term_modal_woo_options',
            'tpul_term_modal_woo_section'
        );

        add_settings_field(
            'terms_modal_woo_popup_frequency',
            __('Popup frequency for Anonymous visitors', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_woo_popup_frequency_render'),
            'tpul_settings_term_modal_woo_options',
            'tpul_term_modal_woo_section'
        );

        add_settings_field(
            'terms_modal_woo_log_out_user',
            __('Decline button redirect', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_woo_log_out_user_render'),
            'tpul_settings_term_modal_woo_options',
            'tpul_term_modal_woo_section'
        );

        add_settings_field(
            'terms_modal_woo_log_out_user',
            __('Decline button behaviour for logged in users', 'terms-popup-on-user-login'),
            array($this, 'terms_modal_woo_log_out_user_render'),
            'tpul_settings_term_modal_woo_options',
            'tpul_term_modal_woo_section'
        );

        /**
         * Register Section
         */
        register_setting(
            'tpul_settings_term_modal_woo_options',
            'tpul_settings_term_modal_woo_options',
            array($this, 'validate_term_modal_options')
        );
    }

    /**
     * The Callback to assist with extra text
     */
    public function term_modal_woo_options_callback() {
        // echo '<p>' . __( 'Woo Commerce Integration. ', 'terms-popup-on-user-login' ) . '</p>';

        $woo_manager = new TPUL_Woo_Connector();

        if ($woo_manager->woo_enabled()) {
            _e('The WooCommerce plugin is currently <b>Eanbled</b>', 'terms-popup-on-user-login');
        } else {
            // The WooCommerce plugin is NOT enabled!
            _e('The WooCommerce plugin is currently <b>Disabled</b>', 'terms-popup-on-user-login');
        }
    }

    /**---------------------------------------------------------------------
     * Settings fields for Email options
     ---------------------------------------------------------------------*/

    public function initialize_terms_modal_email_options() {


        $option_name = $this->email_option_getter->get_option_name();
        $section_id = $this->email_option_getter->get_section_id();

        // delete_option($option_name);

        if (false == get_option($option_name)) {
            // $default_array = $this->default_terms_modal_email_options();
            $default_array =  $this->email_option_getter->default_options();
            update_option($option_name, $default_array);
        }

        /**
         * Add Section
         */
        add_settings_section(
            $section_id,
            '<span class="dashicons dashicons-email-alt"></span> ' . __('Email Notification Settings', 'terms-popup-on-user-login'),
            array($this, 'term_modal_email_options_callback'),
            $option_name
        );

        /**
         * Add options to Section
         */

        add_settings_field(
            'email_send_to_user',
            __('Send Email Notification to Users', 'terms-popup-on-user-login'),
            array($this, 'email_send_to_user_render'),
            $option_name,
            $section_id
        );

        add_settings_field(
            'email_send_to_admins',
            __('Confirmation Email to Admins', 'terms-popup-on-user-login'),
            array($this, 'email_send_to_admins_render'),
            $option_name,
            $section_id
        );

        add_settings_field(
            'email_notify_about_anonymous',
            __('Notify Admins About WooCommerce Anonymous Accepts', 'terms-popup-on-user-login'),
            array($this, 'email_notify_about_anonymous_render'),
            $option_name,
            $section_id
        );

        add_settings_field(
            'email_admin_addr',
            __('Admin Email Addresses to be Notified', 'terms-popup-on-user-login'),
            array($this, 'email_admin_addr_render'),
            $option_name,
            $section_id
        );

        add_settings_field(
            'email_subject',
            __('Email Subject', 'terms-popup-on-user-login'),
            array($this, 'email_subject_render'),
            $option_name,
            $section_id
        );

        add_settings_field(
            'email_text_content',
            __('Email Content', 'terms-popup-on-user-login'),
            array($this, 'email_text_content_render'),
            $option_name,
            $section_id
        );

        /**
         * Register Section
         */
        register_setting(
            $option_name,
            $option_name,
            array($this, 'validate_term_modal_options')
        );
    }

    /**
     * The Callback to assist with extra text
     */
    public function term_modal_email_options_callback() {
        echo '<p>' . __('Email Options. Warning, there is no guarantee of email arrival. It is best to use an SMTP plugin coupled with an email service provider for best chance of email delivery. Such as https://wordpress.org/plugins/wp-mail-smtp/ (no affiliations).', 'terms-popup-on-user-login') . '</p>';
    }

    /**---------------------------------------------------------------------
     * Settings fields for Reset Users
     ---------------------------------------------------------------------*/

    /**
     * Initializes the theme's activated options
     *
     * This function is registered with the 'admin_init' hook.
     */
    public function initialize_reset_users_options() {
        /**
         * Add Section
         */
        add_settings_section(
            'tpul_reset_users_section',
            '<span class="dashicons dashicons-admin-settings settings-page-icon"></span> ' . __('Advanced Options', 'terms-popup-on-user-login'),
            array($this, 'reset_users_options_callback'),
            'tpul_settings_reset_users_options'
        );
    }

    /**
     * The Callback to assist with extra text
     */
    public function reset_users_options_callback() {
        // echo '<p>' . __( '<b style="color: red;">WARNING! This can not be undone. Running the reset will make the popup show for every user again.</b>', 'terms-popup-on-user-login' ) . '</p>';
        // echo '<p>' . __( 'Advanced options', 'terms-popup-on-user-login' ) . '</p>';
    }


    /**---------------------------------------------------------------------
     * Render the actual page
     ---------------------------------------------------------------------*/


    function csv_log_purge_buttom() {
        $output = "<a class='button tpul_admin_icon_button tpul_log_purge_btn tpul_admin_button' style='text-align:center;' href='#' onclick='purgeLog(event)'>";
        $output .= '<span class="pd-l-8 xs-dis-none tpul_plr_5">Purge Log</span>';
        $output .= "</a>";
        echo $output;
    }

    function csv_log_download_buttom() {

        // Add new query param and reload on this link

        $output = "<a class='button tpul_admin_icon_button tpul_log_CSV_download_btn tpul_admin_button btn_generate_log' style='align-items: center; align-items: center; ' href='#'  onclick='btnGenerateLog(event)'>";
        $output .= '<span class="pd-l-8 xs-dis-none tpul_plr_5">Generate User Log CSV</span>';
        $output .= "</a>";

        $output .= "<div class='tpul_log_CSV_download_btn_wait btn_generate_log_msg hide'>";
        $output .= '<span class="">Please wait the while file is being generated . . .</span>';
        $output .= "</div>";


        $output .= "<div class='tpul_ptb_15 link_download_log_container hide'>";
        $output .= "<a class='link_download_log button tpul_admin_icon_button tpul_admin_button' href='#' target='_blank'>";
        $output .= "<span>";
        $output .= '<svg class="flex-shrink-0" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.053 2.07129H6.06398C4.51973 2.07129 3.18848 3.32304 3.18848 4.86804V12.9208C3.18848 14.5528 4.43198 15.836 6.06398 15.836H12.055C13.6 15.836 14.8525 14.4658 14.8525 12.9208V6.02829L11.053 2.07129Z" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M10.856 2.0625V4.24425C10.856 5.30925 11.7177 6.17325 12.7827 6.1755C13.7697 6.17775 14.78 6.1785 14.8482 6.174" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.73145 12.0098V7.08008" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6.60254 9.87207L8.73104 12.0103L10.8603 9.87207" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
        $output .= "</span>";
        $output .= ' Click Here to Download the Log';
        $output .= "</a>";
        $output .= "</div>";


        echo $output;
    }

    function csv_file_buttom() {

        // Add new query param and reload on this link

        $output = "<div>";
        $output .= "<a class='button tpul_admin_icon_button tpul_CSV_download_btn tpul_admin_button btn_generate_report' href='#' onclick='btnGenerateReport(event)'>";
        $output .= '<span class="pd-l-8 xs-dis-none tpul_plr_5">Generate Report CSV</span>';
        $output .= "</a>";

        $output .= "<span class='tpul_CSV_download_btn_wait hide btn_generate_report_msg tpul_ptb_5 tpul_plr_5'>";
        $output .= '<span class="">Please wait the while file is being generated . . .</span>';
        $output .= "</span>";
        $output .= "</div>";

        $output .= "<div class='tpul_ptb_15 link_download_report_container hide'>";
        $output .= "<a class='link_download_report button tpul_admin_icon_button tpul_admin_button' href='#' target='_blank'>";
        $output .= "<span>";
        $output .= '<svg class="flex-shrink-0" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.053 2.07129H6.06398C4.51973 2.07129 3.18848 3.32304 3.18848 4.86804V12.9208C3.18848 14.5528 4.43198 15.836 6.06398 15.836H12.055C13.6 15.836 14.8525 14.4658 14.8525 12.9208V6.02829L11.053 2.07129Z" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M10.856 2.0625V4.24425C10.856 5.30925 11.7177 6.17325 12.7827 6.1755C13.7697 6.17775 14.78 6.1785 14.8482 6.174" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.73145 12.0098V7.08008" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6.60254 9.87207L8.73104 12.0103L10.8603 9.87207" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
        $output .= "</span>";
        $output .= ' Click Here to Download Report';
        $output .= "</a>";
        $output .= "</div>";

        echo $output;
    }

    /**
     * Renders a simple page to display for the theme menu defined above.
     */
    public function render_settings_page_content($active_tab = '') {

        $this->tpul_settings_term_modal_options = get_option('tpul_settings_term_modal_options');
        $this->tpul_settings_general_options = get_option('tpul_settings_general_options');
        $this->tpul_settings_term_modal_display_options = get_option('tpul_settings_term_modal_display_options');

?>
        <!-- Create a header in the default WordPress 'wrap' container -->
        <div class="wrap">

            <?php

            // $gen_options = new TPUL_General_Options();
            // v_dump($gen_options->get_options());

            // $gen_options = new TPUL_Modal_Options();
            // v_dump($gen_options->get_options());

            // $gen_options = new TPUL_Display_Options();
            // v_dump($gen_options->get_options());

            // $gen_options = new TPUL_Woo_Options();
            // v_dump($gen_options->get_options());

            // $gen_options = new TPUL_License_Options();
            // v_dump($gen_options->get_options());


            ?>


            <h2><?php esc_html_e('Terms Popup On User Login Options', 'terms_popup_on_user_login'); ?></h2>

            <?php settings_errors();

            ?>

            <?php if (isset($_GET['tab'])) {

                $active_tab = sanitize_text_field($_GET['tab']);
            } else if ($active_tab == 'terms_modal_options') {
                $active_tab = 'terms_modal_options';
            } else if ($active_tab == 'reset_users_options') {
                $active_tab = 'reset_users_options';
            } else if ($active_tab == 'terms_email_options') {
                $active_tab = 'terms_email_options';
            } else {
                $active_tab = 'general_options';
            }

            ?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=terms_popup_on_user_login_options&tab=general_options" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-admin-network"></span> <?php esc_html_e('General', 'terms-popup-on-user-login'); ?></a>
                <a href="?page=terms_popup_on_user_login_options&tab=terms_modal_options" class="nav-tab <?php echo $active_tab == 'terms_modal_options' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e('Labels, Content and Redirects', 'terms-popup-on-user-login'); ?></a>
                <a href="?page=terms_popup_on_user_login_options&tab=terms_modal_display_options" class="nav-tab <?php echo $active_tab == 'terms_modal_display_options' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-admin-appearance"></span> <?php esc_html_e('Visual Display', 'terms-popup-on-user-login'); ?></a>
                <a href="?page=terms_popup_on_user_login_options&tab=terms_modal_woo_options" class="nav-tab <?php echo $active_tab == 'terms_modal_woo_options' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-cart"></span> <?php esc_html_e('WooCommerce Integration', 'terms-popup-on-user-login'); ?></a>
                <a href="?page=terms_popup_on_user_login_options&tab=terms_email_options" class="nav-tab <?php echo $active_tab == 'terms_email_options' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-email-alt"></span> <?php esc_html_e('Email Notification', 'terms-popup-on-user-login'); ?></a>
                <a href="?page=terms_popup_on_user_login_options&tab=terms_modal_analytics" class="nav-tab <?php echo $active_tab == 'terms_modal_analytics' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-search"></span> <?php esc_html_e('Analytics', 'terms-popup-on-user-login'); ?></a>
                <a href="?page=terms_popup_on_user_login_options&tab=reset_users_options" class="nav-tab <?php echo $active_tab == 'reset_users_options' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e('Advanced', 'terms-popup-on-user-login'); ?></a>
                <a href="?page=terms_popup_on_user_login_options&tab=terms_modal_support" class="nav-tab <?php echo $active_tab == 'terms_modal_support' ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-businessman"></span> <?php esc_html_e('Support', 'terms-popup-on-user-login'); ?></a>
            </h2>

            <form method="post" action="options.php" class="tpul_admin_form">
                <?php

                if ($active_tab == 'terms_modal_options') {

                ?>

                    <div class="tg-outer">
                        <?php
                        settings_fields('tpul_settings_term_modal_options');
                        do_settings_sections('tpul_settings_term_modal_options');
                        submit_button();
                        ?>
                    </div>

                <?php
                } elseif ($active_tab == 'terms_modal_display_options') {
                ?>
                    <div class="tg-outer">
                        <?php
                        settings_fields('tpul_settings_term_modal_display_options');
                        do_settings_sections('tpul_settings_term_modal_display_options');
                        submit_button();
                        ?>
                    </div>
                <?php
                } elseif ($active_tab == 'terms_modal_woo_options') {
                ?>
                    <div class="tg-outer">

                        <?php
                        settings_fields('tpul_settings_term_modal_woo_options');
                        do_settings_sections('tpul_settings_term_modal_woo_options');
                        submit_button();
                        ?>
                    </div>
                <?php
                } elseif ($active_tab == 'terms_email_options') {
                ?>
                    <div class="tg-outer">

                        <?php
                        settings_fields($this->email_option_getter->get_option_name());
                        do_settings_sections($this->email_option_getter->get_option_name());
                        submit_button();
                        ?>
                    </div>
                <?php
                } elseif ($active_tab == 'terms_modal_analytics') {
                ?>
                    <div class="tg-outer">
                        <?php
                        $analytics = new TPUL_Analytics();
                        $analytics->print_dashboard();
                        ?>
                    </div>
                <?php
                } elseif ($active_tab == 'terms_modal_support') {
                ?>
                    <div class="tg-outer">
                        <h2><span class="dashicons dashicons-businessman settings-page-icon"></span> Premium Support</h2>
                        <table class="form-table" role="presentation">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php echo __('Email'); ?></th>
                                    <td>
                                        <?php
                                        LHL_Admin_UI_TPUL::print_support_email(
                                            $this->license_key_valid
                                        )
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo __('Include Support Token in Email Subject'); ?></th>
                                    <td>
                                        <?php
                                        LHL_Admin_UI_TPUL::print_support_token(
                                            $this->license_key_valid,
                                            $this->suport_token
                                        )
                                        ?>
                                        <p class="description">
                                            To recieve premium support include support token above in your email subject.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo __('Propose a feature'); ?></th>
                                    <td>
                                        You are always wellcomed to submit ideas for new features at <a href="https://www.lehelmatyus.com/contact" target="_blank">Submit a Premium Feature Request</a>
                                        <p class="description">
                                            If Terms Pupup on User Login is almost there but not quite for your organization. <br> Submit a Feature request. You don't need license key for providing feature suggestions..
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php
                } elseif ($active_tab == 'reset_users_options') {
                ?>
                    <div class="tg-outer">
                        <?php
                        settings_fields('tpul_settings_reset_users_options');
                        do_settings_sections('tpul_settings_reset_users_options');
                        ?>

                        <table class="form-table" role="presentation">
                            <tbody>

                                <?php if (true) { ?>
                                    <tr>

                                        <th scope="row"><?php echo __('Download report of users'); ?></th>
                                        <td>

                                            <?php $license_key_active = $this->license_key_handler->is_active(); ?>

                                            <?php if ($license_key_active) : ?>
                                                <div class="tpul__alowed_box">
                                                    <p class="tpul__license_notify_text"> <?php echo esc_html__('You have an active License Key. This feature is avaialble for you.', 'terms-popup-on-user-login'); ?> </p>
                                                <?php else : ?>
                                                    <div class="tpul__restricted_box">
                                                        <p class="tpul__license_notify_text"> <?php echo esc_html__('This is a Premium Feature. You need a valid license key to activate this feature.', 'terms-popup-on-user-login'); ?> </p>
                                                    <?php endif; ?>

                                                    <p>
                                                        <?php if ($license_key_active) : ?>
                                                    <div>
                                                        <?php
                                                            $this->csv_file_buttom();
                                                        ?>
                                                    </div>
                                                <?php else : ?>
                                                    <div>
                                                        <?php
                                                            echo "<button style='align-items: center; align-items: center; display: flex; width: 100px;' disabled='disabled'>";
                                                            echo  '<svg class="flex-shrink-0" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.053 2.07129H6.06398C4.51973 2.07129 3.18848 3.32304 3.18848 4.86804V12.9208C3.18848 14.5528 4.43198 15.836 6.06398 15.836H12.055C13.6 15.836 14.8525 14.4658 14.8525 12.9208V6.02829L11.053 2.07129Z" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M10.856 2.0625V4.24425C10.856 5.30925 11.7177 6.17325 12.7827 6.1755C13.7697 6.17775 14.78 6.1785 14.8482 6.174" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.73145 12.0098V7.08008" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6.60254 9.87207L8.73104 12.0103L10.8603 9.87207" stroke="#222222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>';
                                                            echo   '<span class="pd-l-8 xs-dis-none">.CSV</span>';
                                                            echo  "</button>";
                                                        ?>
                                                    </div>
                                                <?php endif; ?>
                                                </p>

                                                <p class="description"><?php echo __('Get a report spreadsheet of your current users. Admin level users are excluded from the report. This may take a few seconds, hang thight once clicked.', 'terms-popup-on-user-login'); ?>
                                                    </div>

                                        </td>

                                    </tr>
                                <?php } ?>

                                <tr>
                                    <th scope="row"><?php echo esc_html__('Show popup again for everyone', 'terms-popup-on-user-login'); ?></th>
                                    <td>

                                        <?php $license_key_active = $this->license_key_handler->is_active(); ?>

                                        <?php if ($license_key_active) : ?>
                                            <div class="tpul__alowed_box">
                                                <p class="tpul__license_notify_text"> <?php echo esc_html__('You have an active License Key. This feature is avaialble for you.', 'terms-popup-on-user-login'); ?> </p>
                                            <?php else : ?>
                                                <div class="tpul__restricted_box">
                                                    <p class="tpul__license_notify_text"> <?php echo esc_html__('This is a Premium Feature. You need a valid license key to activate this feature.', 'terms-popup-on-user-login'); ?> </p>
                                                <?php endif; ?>

                                                <p>

                                                    <?php if ($license_key_active) : ?>
                                                        <button id="tpul__reset-all" class="tpul_admin_button tpul_script_button button" href="#" onclick="resetAllUsers(event)">Reset all users</button>
                                                    <?php else : ?>
                                                        <button id="tpul__reset-all-1" class="tpul_admin_button tpul_script_button button" href="#" disabled="disabled">Reset all users</button>
                                                    <?php endif; ?>

                                                    <span class="tpul_reset_button_msg"></span>
                                                </p>
                                                <p class="description">Use this button if you have updated your Terms and Conditions and would like to force all existing users to accept the updated Terms again.</p>
                                                <p class="description">After You click the button don't close this window while the script is running.</p>
                                                </div>
                                                <p class="description"><?php echo __('Typically you would run a reset when you update your terms and would like all your users to accept the now updated terms.', 'terms-popup-on-user-login'); ?></p>
                                                <p class="description"><?php echo __('<b style="color: red;">WARNING! This can not be undone. Running the reset will make the popup show for every user again. Forcing them to accept your terms again.</b>', 'terms-popup-on-user-login'); ?></p>

                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">Last time you ran a reset</th>
                                    <td>
                                        <div>
                                            <?php echo $this->__last_time_reset_ran(); ?>
                                        </div>
                                        <p class="description"><?php echo __('The date when you last ran the reset.'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">Turn Advanced Logging On</th>
                                    <td>
                                        <div>
                                            <?php if ($license_key_active) : ?>
                                                <div class="tpul__alowed_box">
                                                    <p class="tpul__license_notify_text"> <?php echo esc_html__('You have an active License Key. This feature is avaialble for you.', 'terms-popup-on-user-login'); ?> </p>
                                                <?php else : ?>
                                                    <div class="tpul__restricted_box">
                                                        <p class="tpul__license_notify_text"> <?php echo esc_html__('This is a Premium Feature. You need a valid license key to activate this feature.', 'terms-popup-on-user-login'); ?> </p>
                                                    <?php endif; ?>

                                                    <?php
                                                    $loggin_on = true;
                                                    $loggin_on = get_option('tpul_addv_logging');


                                                    ?>

                                                    <div class="tpul_pb_10">
                                                        <span class="tpul_log_button_msg"></span>

                                                        <?php
                                                        $indicator_class = $loggin_on ? "tpul_logging_status--on" : "tpul_logging_status--off";
                                                        ?>
                                                        <div class="tpul_logging_status tpul_pb_15 <?php echo $indicator_class; ?>">

                                                            <div class="tpul_pb_15">
                                                                Logging Status:
                                                                <span class="tpul_logging_off_indicator">
                                                                    <b>OFF</b>
                                                                    <svg height="10" width="10">
                                                                        <circle cx="5" cy="5" r="4" stroke="black" stroke-width="1" fill="red" />
                                                                    </svg>
                                                                </span>

                                                                <span class="tpul_logging_on_indicator">
                                                                    <b>ON</b>
                                                                    <svg height="10" width="10">
                                                                        <circle cx="5" cy="5" r="4" stroke="black" stroke-width="1" fill="#32CD32" />
                                                                    </svg>
                                                                </span>
                                                            </div>

                                                            <div class="">
                                                                <?php if ($license_key_active) : ?>
                                                                    <button id="tpul__advanced_log_button" class="tpul_advanced_log_button tpul_advanced_log_button--turn-off button" href="#" onclick="enableAdvancedLogging(event)"><?php echo __("Turn Off Advanced Logging", "terms-popup"); ?></button>
                                                                    <button id="tpul__advanced_log_button" class="tpul_advanced_log_button tpul_advanced_log_button--turn-on button" href="#" onclick="enableAdvancedLogging(event)"><?php echo __("Turn ON Advanced Logging", "terms-popup"); ?></button>
                                                                <?php else : ?>
                                                                    <button id="tpul__advanced_log_button-1" class="tpul_advanced_log_button button" href="#" disabled="disabled"><?php echo __("Turn on Advanced Logging", "terms-popup"); ?></button>
                                                                <?php endif; ?>
                                                            </div>

                                                            <!-- <div class="tpul_pt_15">
                                                                Log Tools:
                                                            </div> -->


                                                            <div class="tpul_csv_log_tools">

                                                                <table class="tpul_table_small advanced_log_table">
                                                                    <tr>
                                                                        <td>
                                                                            <div class="advanced_log_table__label"><b><?php _e("Logging tools", "terms-popup-on-user-login") ?>:</b>
                                                                        </td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="advanced_log_table__label"><?php _e("Download log", "terms-popup-on-user-login"); ?>:
                                                                        </td>
                                                                        <td><?php $this->csv_log_download_buttom(); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="advanced_log_table__label"><?php _e("Number of log entries", "terms-popup-on-user-login"); ?>:
                                                                        </td>
                                                                        <td>
                                                                            <?php
                                                                            if ($loggin_on) {
                                                                                $count_log_row = termspul\Tpul_DB::count_all();
                                                                                echo $count_log_row;
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>
                                                                            <div class="advanced_log_table__label"><?php _e("Delete entries older than", "terms-popup-on-user-login"); ?>:
                                                                        </td>
                                                                        <td>
                                                                            <input type="date" name="the_date" class="the_datepicker" />
                                                                            <?php $this->csv_log_purge_buttom(); ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>

                                                                <div class="tpul_log_purge_btn_msg">
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <p class="description"><?php echo __('This feature will Log every single user action on the Terms Popup. The Log will be available as a CSV'); ?>
                                                    <p class="description"><?php echo __('This should only be turned on when it\'s necesarry.'); ?>
                                                    </div>
                                                </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">System Report</th>
                                    <td>
                                        <div>
                                            PHP Version:
                                            <?php
                                            echo phpversion();
                                            ?>
                                        </div>
                                        <div>
                                            Curl Version:
                                            <?php
                                            $values = curl_version();
                                            echo $values["version"];
                                            ?>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>

                        <!--
                                <hr/>
                                <br>
                                <br>
                                <p>Use this button if you would only want to force CURRENT users to accept the updated Terms. BUT Disable the Terms and Conditions for all FUTURE users</p>
                                <p>
                                    <button id="tpul__reset-all" class="tpul_script_button button" href="#" onclick="resetAllUsers(event)"> Disable Popup for FUTURE users</button>
                                    <span class="tpul__msg hide"></span>
                                </p> -->

                    </div>
                <?php
                } else {

                    settings_fields('tpul_settings_general_options');
                    do_settings_sections('tpul_settings_general_options');
                    submit_button();
                } // end if/else
                ?>
            </form>



            <hr />
            <div class="tpul__plugin-reviews">
                <div class="tpul__plugin-reviews-purchase">
                    <b>
                        <a href="https://www.lehelmatyus.com/question/question-category/terms-popup-on-user-login" target="_blank" title="terms popup on user login review">
                            <?php echo __('Purchase a License key', 'terms-popup-on-user-login'); ?></a>
                        <?php echo __('and ejoy all features:', 'terms-popup-on-user-login'); ?>
                        <span class="dashicons dashicons-admin-network"></span>
                    </b>
                </div>
                <br />
                <div class="tpul__plugin-reviews-rate">
                    <?php echo __('If you enjoy our plugin, please give it 5 stars on WordPress it helps me a lot:', 'terms-popup-on-user-login'); ?>
                    <a href="https://wordpress.org/support/plugin/terms-popup-on-user-login/reviews/?filter=5" target="_blank" title="terms popup on user login review">Rate the plugin</a>
                </div>
                <div class="tpul__plugin-reviews-support">
                    <?php echo __('If you have any questions on how to use the plugin, feel free to ask them:', 'terms-popup-on-user-login'); ?>
                    <a href="https://lehelmatyus.com/question-category/terms-popup-on-user-login" target="_blank" title="ask a question">Support Questions</a>
                </div>
                <div class="tpul__plugin-reviews-donate">

                    <?php echo __('Donations play an important role, please consider donating:', 'terms-popup-on-user-login'); ?>
                    <a href="https://buy.stripe.com/cN2cMX1P2da1g4o288" title="support the plugin" target="_blank">Donate</a>
                </div>
            </div>



        </div><!-- /.wrap -->
    <?php

    }

    /**---------------------------------------------------------------------
     * Helper functions to generate a field
     ---------------------------------------------------------------------*/


    function dropdown_select_field_render() {

        $options = get_option('tpul_settings_general_options');
        // var_dump($options);
    ?>
        <select name='tpul_settings_general_options[modal_to_show]'>
            <option value='none' <?php selected($options['modal_to_show'], 'none'); ?>><?php _e('Don\'t show popup', 'terms-popup-on-user-login'); ?></option>
            <option value='terms_and_conditions_modal' <?php selected($options['modal_to_show'], 'terms_and_conditions_modal'); ?>><?php _e('Show popup - On user login', 'terms-popup-on-user-login'); ?></option>
            <option value='terms_and_conditions_modal_woo' <?php selected($options['modal_to_show'], 'terms_and_conditions_modal_woo'); ?>><?php _e('Show popup - WooCommerce mode', 'terms-popup-on-user-login'); ?></option>
            <option value='terms_and_conditions_modal_test' <?php selected($options['modal_to_show'], 'terms_and_conditions_modal_test'); ?>><?php _e('TEST MODE', 'terms-popup-on-user-login'); ?></option>
        </select>
        <p class="description">
            <?php echo __('Choose how would you like to use this plugin:', 'terms-popup-on-user-login'); ?>
        <ol>
            <li>
                <?php echo __('<b>Don\'t show popup</b> - Popup will not show under any condition.', 'terms-popup-on-user-login'); ?>
            </li>
            <li>
                <?php echo __('<b>Show popup on user login</b> - Popup will show immediately upon user login, and only on user login.', 'terms-popup-on-user-login'); ?>
            </li>
            <li>
                <?php echo __('<b>Show popup - WooCommerce mode</b> - Popup will only show on WooCommerce Pages. Can be shown for Anonymous visitors as well. Extra configuration needed on "WooCommerce Integration" tab.', 'terms-popup-on-user-login'); ?>
            </li>
            <li>
                <?php echo __('<b>TEST MODE</b> - Popup will show for every user on every page load for easier testing. Remember to disable test mode after done testing!', 'terms-popup-on-user-login'); ?>
            </li>
        </ol>
        </p>

    <?php
    }

    function  demo_terms_modal_render() {
    ?>
        <a id="tpul__demo_modal_btn" class="button" href="/" onclick="">Click to View Popup</a>
    <?php
    }

    function tplu_license_key_render() {

        $options = get_option('tpul_settings_general_options');

        // var_dump($this->license_key_handler);
        $license_key_active = $this->license_key_handler->is_active();

    ?>
        <p>
            <?php
            if (!$license_key_active) {
                echo __('Purchase a license key to unlock premium features in a matter of minutes. <a target="_blank" href="https://www.lehelmatyus.com/terms-popup-on-user-login">Purchase a License Key Here.</a>', 'terms-popup-on-user-login');
            }
            ?>
        </p>
        <br />

        <?php $readonly = ""; ?>
        <?php if ($license_key_active) : ?>
            <?php $readonly = "readonly"; ?>
            <!-- <div style="border: 1px solid #46b450; padding:10px; background: rgba(70, 180, 80, 0.1)"> -->
            <div class="tpul__alowed_box">
                <p class="tpul__license_notify_text">
                    <?php echo __('Your License Key is active.', 'terms-popup-on-user-login'); ?>
                    <?php if (!$this->license_key_handler->is_auto_renew()) : ?>
                        <?php echo __('Will expire in', 'terms-popup-on-user-login'); ?>
                        <b><?php echo $this->license_key_handler->expiration_in_days(); ?></b>
                        <?php echo __('days', 'terms-popup-on-user-login'); ?>.
                    <?php endif; ?>
                </p>
            <?php else : ?>
                <div class="tpul__alowed_box">
                    <p class="tpul__license_notify_text"><?php echo __('Enter License Key to unlock premium features.', 'terms-popup-on-user-login'); ?></p>
                <?php endif;
                ?>
                <div>
                    <?php if ($license_key_active) : ?>
                        <input id="tpul__deactivate-key-input" type='password' class="regular-text" name='tpul_settings_general_options[tplu_license_key]' value='Check the original email that we sent you' <?php echo $readonly; ?>>
                        <button id="tpul__deactivate-key" class="tpul_script_button button" href="#" onclick="deactivateKey(event)">Deactivate Key</button>
                        <span class="tpul_deactivate_button_loader hide"><img class="load_spinner" src="/wp-includes/images/spinner.gif" /></span>
                        <span class="tpul_deactivate_button_msg hide"></span>
                    <?php else : ?>
                        <input id="tpul__deactivate-key-input" type='text' class="regular-text" name='tpul_settings_general_options[tplu_license_key]' value='<?php echo $options['tplu_license_key']; ?>' <?php echo $readonly; ?>>
                        <button id="tpul__activate-key" class="tpul_script_button button" href="#" onclick="activateKey(event)">Activate Key</button>
                        <span class="tpul_activate_button_loader hide"><img class="load_spinner" src="/wp-includes/images/spinner.gif" /></span>
                        <span class="tpul_activate_button_msg hide"></span>

                    <?php endif; ?>

                </div>

                </div>




            <?php
        }


        function terms_modal_title_render() {
            $options = $this->tpul_settings_term_modal_options;
            ?>
                <input type='text' class="regular-text" name='tpul_settings_term_modal_options[terms_modal_title]' value='<?php echo esc_attr($options['terms_modal_title']); ?>'>
                <p class="description"> <?php echo __('Title of the Modal.', 'terms-popup-on-user-login'); ?> </p>
            <?php
        }

        function terms_modal_subtitle_render() {
            $options = $this->tpul_settings_term_modal_options;
            ?>
                <input type='text' class="regular-text" name='tpul_settings_term_modal_options[terms_modal_subtitle]' value='<?php echo esc_attr($options['terms_modal_subtitle']); ?>'>
                <p class="description"> <?php echo __('Text right below the modal title.', 'terms-popup-on-user-login'); ?> </p>
            <?php
        }

        function terms_modal_agreed_text_render() {
            $options = $this->tpul_settings_term_modal_options;
            ?>
                <input type='text' class="regular-text" name='tpul_settings_term_modal_options[terms_modal_agreed_text]' value='<?php echo esc_attr($options['terms_modal_agreed_text']); ?>'>
                <p class="description"> <?php echo __('Text that get\'s shown briefly after user clicked the accept button.', 'terms-popup-on-user-login'); ?> </p>
            <?php
        }

        function terms_modal_logout_text_render() {
            $options = $this->tpul_settings_term_modal_options;
            ?>
                <input type='text' class="regular-text" name='tpul_settings_term_modal_options[terms_modal_logout_text]' value='<?php echo esc_attr($options['terms_modal_logout_text']); ?>'>
                <p class="description"> <?php echo __('Text that get\'s shown briefly before user is logged out.', 'terms-popup-on-user-login'); ?> </p>
            <?php
        }

        function delimiter_visibility_render() {
            echo "<h3>Special Visibility Rules A), B) or C)  -- <small>Ignore for WooCommerce operation</small></h3>";
            echo  __("<p>If you go with any of these options you may also want to select <b>Keep users logged in</b></p>", 'terms-popup-on-user-login');
        }

        function delimiter_accept_render() {
            echo "<h3>Accept Button Settings</h3>";
        }

        function delimiter_decline_render() {
            echo "<h3>Decline Button Settings</h3>";
        }

        function delimiter_content_render() {
            echo "<h3>Popup Content Settings</h3>";
        }

        function delimiter_role_visibility_render() {
            echo "<h3>Role Based Visibility Settings</h3>";
        }

        function delimiter_tracking_render() {
            echo "<h3>Trackings Settings</h3>";
        }

        function delimiter_testing_render() {
            echo "<h3>Test and Debug Settings</h3>";
        }

        function delimiter_render() {
            echo "<h3></h3>";
        }

        function terms_modal_accept_button_render() {
            $options = $this->tpul_settings_term_modal_options;
            ?>
                <input type='text' class="regular-text" name='tpul_settings_term_modal_options[terms_modal_accept_button]' value='<?php echo esc_attr($options['terms_modal_accept_button']); ?>'>
                <p class="description"> <?php echo __('Button text for "Accept" button.', 'terms-popup-on-user-login'); ?> </p>
            <?php
        }

        function terms_modal_show_every_login_render() {
            $options = $this->tpul_settings_term_modal_options;
            if (empty($options['terms_modal_show_every_login'])) {
                $options['terms_modal_show_every_login'] = 0;
            }
            ?>
                <input name="tpul_settings_term_modal_options[terms_modal_show_every_login]" id="tpul_settings_term_modal_options[terms_modal_show_every_login]" type="checkbox" value="1" <?php checked('1', $options['terms_modal_show_every_login']); ?> />
                <label for="tpul_settings_term_modal_options[terms_modal_show_every_login]">Show popup on every single login both for those who accepted and those who declined at a previous login</label>

                <p class="description"> <?php echo __('If checked, all user must accept the terms every time after login even if they have accepted at previous logins. <br/>This option is often used with "<b>Keep users logged in</b>", even if they declined" - which you can find a few options below.', 'terms-popup-on-user-login'); ?> </p>
            <?php
        }
        function terms_modal_show_every_login_for_declined_render() {

            $options = $this->tpul_settings_term_modal_options;

            $license_key_valid = $this->license_key_handler->is_active();
            AdminForm::checkbox_single__active_key_required(
                $license_key_valid,
                $options,
                'tpul_settings_term_modal_options',
                'terms_modal_show_every_login_for_declined',
                __('Show popup on every single login BUT only for those who have declined at a previous login', 'terms-popup-on-user-login')
            );
            ?>
                <p class="description"> <?php echo __('If checked, users who have not accepted the temrs before will be prompted to accept at any page. <br/>This option is often used with "<b>Keep users logged in</b>", even if they declined" - which you can find a few options below.', 'terms-popup-on-user-login'); ?> </p>

            <?php

        }

        function terms_modal_accept_enable_render() {
            $options = $this->tpul_settings_term_modal_options;
            if (empty($options['terms_modal_accept_enable'])) {
                $options['terms_modal_accept_enable'] = 0;
            }
            ?>
                <input name="tpul_settings_term_modal_options[terms_modal_accept_enable]" id="tpul_settings_term_modal_options[terms_modal_accept_enable]" type="checkbox" value="1" <?php checked('1', $options['terms_modal_accept_enable']); ?> />
                <label for="tpul_settings_term_modal_options[terms_modal_accept_enable]">Allow user to accept terms without scrolling</label>

                <p class="description"> <?php echo esc_html__('In case of very short popup content no scrolling is needed. In case of long content if left unchecked, user must scroll down to the end of Terms presented in popup to be able to accept it.', 'terms-popup-on-user-login'); ?> </p>
            <?php
        }

        function terms_modal_accept_redirect_render() {
            $options = $this->tpul_settings_term_modal_options;
            ?>
                <input type='text' class="regular-text" name='tpul_settings_term_modal_options[terms_modal_accept_redirect]' value='<?php echo esc_attr($options['terms_modal_accept_redirect']); ?>'>
                <p class="description"> <?php echo esc_html__('Can redirect user uppon accpting terms. Does not redirect if left empty, will accept relative url such as "/" or "/about".', 'terms-popup-on-user-login'); ?> </p>
                <p class="description"> <b><?php echo esc_html__('When using it with WooCommerce integration it is best to leave this field empty, or users will get redirected from the product!', 'terms-popup-on-user-login'); ?> </b></p>
            <?php
        }

        function terms_modal_decline_button_render() {
            $options = $this->tpul_settings_term_modal_options;
            ?>
                <input type='text' class="regular-text" name='tpul_settings_term_modal_options[terms_modal_decline_button]' value='<?php echo esc_attr($options['terms_modal_decline_button']); ?>'>
                <p class="description"> <?php echo __('Button text for "Decline" button.', 'terms-popup-on-user-login'); ?> </p>
            <?php
        }

        function terms_modal_decline_nologout_render() {
            $options = $this->tpul_settings_term_modal_options;

            $license_key_valid = $this->license_key_handler->is_active();
            AdminForm::checkbox_single__active_key_required(
                $license_key_valid,
                $options,
                'tpul_settings_term_modal_options',
                'terms_modal_decline_nologout',
                __('Keep users logged in, even if they declined', 'terms-popup-on-user-login')
            );
            ?>
                <p class="description"> <?php echo __('This shouold be turned on if you have used any of Option A) B) or C) of special visibility rules. OR you must redirect users who Decline to a different website.', 'terms-popup-on-user-login'); ?> </p>
                <p class="description"> <?php echo __('<b>IF</b> this option is on you may want to remove the Decline button redirect "/" under <b>Decline Button Settings</b> or your users will still get redirected to the home page.', 'terms-popup-on-user-login'); ?> </p>
            <?php
        }

        function terms_modal_show_only_once_render() {
            $options = $this->tpul_settings_term_modal_options;

            $license_key_valid = $this->license_key_handler->is_active();
            AdminForm::checkbox_single__active_key_required(
                $license_key_valid,
                $options,
                'tpul_settings_term_modal_options',
                'terms_modal_show_only_once',
                __('Never show popup after any type of initial action was taken by user, no matter if popup was accepted or declined by user.', 'terms-popup-on-user-login')
            );
            ?>
                <p class="description"> <?php echo __('Popup will not show after initial accept or decline.<br/>This option is often used with "<b>Keep users logged in</b>", even if they declined" - which you can find a few options below.', 'terms-popup-on-user-login'); ?> </p>
            <?php
        }

        function terms_modal_decline_redirect_render() {
            $options = $this->tpul_settings_term_modal_options;
            ?>
                <input type='text' class="regular-text" name='tpul_settings_term_modal_options[terms_modal_decline_redirect]' value='<?php echo esc_attr($options['terms_modal_decline_redirect']); ?>'>
                <p class="description"> <?php echo __('Will accept relative url such as single slash "/" for home page or "/about" etc. <br/> For outbound links you must provide protocol such as "http://" in the URLs, example: "http://www.lehelmatyus.com".', 'terms-popup-on-user-login'); ?> </p>
            <?php
        }

        function terms_modal_designated_test_user_render() {

            $options = $this->tpul_settings_term_modal_options;
            if (empty($options['terms_modal_designated_test_user'])) {
                $options['terms_modal_designated_test_user'] = '';
            }
            $license_key_valid = $this->license_key_handler->is_active();
            $disabled = !$license_key_valid ? "disabled='disabled'" : "";

            ?>
                <?php if ($license_key_valid) : ?>
                    <div class="tpul__alowed_box">
                        <!-- <p class="tpul__license_notify_text"> <?php // echo esc_html__( 'You have an active License Key. This feature is avaialble for you.', 'terms-popup-on-user-login' ); 
                                                                    ?> </p> -->
                    <?php else : ?>
                        <div class="tpul__restricted_box">
                            <p class="tpul__license_notify_text"> <?php echo esc_html__('This is a Premium Feature. You need a valid license key to activate this feature.', 'terms-popup-on-user-login'); ?> </p>
                            <br />
                        <?php endif; ?>
                        <?php

                        ?>
                        <input type='number' class="regular-text" name='tpul_settings_term_modal_options[terms_modal_designated_test_user]' value='<?php echo esc_attr($options['terms_modal_designated_test_user']); ?>' oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" <?php echo $disabled ?>>
                        <p class="description"> <?php echo __('The User ID for which the popup will always prompt. This user will see the popup every single time.', 'terms-popup-on-user-login'); ?> </p>
                        <p class="description"> <?php echo __('This needs to be a simple number example, your user ID is: ', 'terms-popup-on-user-login');
                                                echo get_current_user_id(); ?> </p>
                        <p class="description"> <?php echo __('Designated Test user will override any other restriction, such as user roles etc. ', 'terms-popup-on-user-login'); ?> </p>
                        <p class="description"> <?php echo __('If you are not sure what this does, be sure to leave it empty!', 'terms-popup-on-user-login'); ?> </p>

                        </div>
                    <?php
                }


                function terms_modal_content_render() {
                    $options = $this->tpul_settings_term_modal_options;

                    printf(
                        '<textarea class="large-text" rows="7" name="tpul_settings_term_modal_options[terms_modal_content]" id="terms_modal_content">%s</textarea>',
                        isset($options['terms_modal_content']) ? esc_attr($options['terms_modal_content']) : ''
                    );
                    ?>
                        <p class="description"> <?php echo __('Paste in content you want to show in the popup. <br> Alternatively you can use <b>"Show your own page"</b> option below to bring in an existing Terms Page as content.', 'terms-popup-on-user-login'); ?> </p>
                    <?php
                }

                function terms_modal_font_size_render() {
                    $options = $this->tpul_settings_term_modal_options;

                    $options['terms_modal_font_size'] = empty($options['terms_modal_font_size']) ? '' : $options['terms_modal_font_size'];

                    $available_font_sizes = array(4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25);
                    $license_key_valid = $this->license_key_handler->is_active();
                    ?>

                        <?php if ($license_key_valid) : ?>
                            <div class="tpul__alowed_box">
                                <p class="tpul__license_notify_text"> <?php echo esc_html__('You have an active License Key. This feature is avaialble for you.', 'terms-popup-on-user-login'); ?> </p>
                            <?php else : ?>
                                <div class="tpul__restricted_box">
                                    <p class="tpul__license_notify_text"> <?php echo esc_html__('This is a Premium Feature. You need a valid license key to activate this feature.', 'terms-popup-on-user-login'); ?> </p>
                                <?php endif; ?>

                                <?php $disabled = !$license_key_valid ? "disabled='disabled'" : ""; ?>

                                <select name='tpul_settings_term_modal_options[terms_modal_font_size]' <?php echo $disabled ?>>
                                    <option value='' <?php selected($options['terms_modal_font_size'], ''); ?>> - Use default font size - </option>
                                    <?php

                                    foreach ($available_font_sizes as $font) {
                                        echo '<option value="' . $font . '" ' . selected($font, $options['terms_modal_font_size']) . '>' . $font . 'px</option>';
                                    }
                                    ?>
                                </select>


                                <p class="description"> <?php echo __('By default the text inside the term is set to 0.875rem. However some themes don\'t work well with rem measurements.', 'terms-popup-on-user-login'); ?> </p>
                                <p class="description"> <?php echo __('This option gives you the ability to override it with a fixed pixel size.', 'terms-popup-on-user-login'); ?> </p>

                                </div>

                            <?php

                            // printf(
                            // 	'<textarea class="large-text" rows="5" name="tpul_settings_term_modal_options[terms_modal_font_size]" id="terms_modal_font_size">%s</textarea>',
                            // 	isset( $options['terms_modal_font_size'] ) ? esc_attr( $options['terms_modal_font_size']) : ''
                            // );

                        }

                        function dropdown_select_field_pages_render() {

                            $general_options = $this->tpul_settings_general_options;
                            $options = $this->tpul_settings_term_modal_options;
                            if (!isset($options['terms_modal_pageid'])) $options['terms_modal_pageid'] = '';

                            $license_key_valid = $this->license_key_handler->is_active();
                            ?>

                                <?php if ($license_key_valid) : ?>
                                    <div class="tpul__alowed_box">
                                        <p class="tpul__license_notify_text"> <?php echo esc_html__('You have an active License Key. This feature is avaialble for you.', 'terms-popup-on-user-login'); ?> </p>
                                    <?php else : ?>
                                        <div class="tpul__restricted_box">
                                            <p class="tpul__license_notify_text"> <?php echo esc_html__('This is a Premium Feature. You need a valid license key to activate this feature.', 'terms-popup-on-user-login'); ?> </p>
                                        <?php endif; ?>

                                        <?php $disabled = !$license_key_valid ? "disabled='disabled'" : ""; ?>

                                        <select name='tpul_settings_term_modal_options[terms_modal_pageid]' <?php echo $disabled ?>>
                                            <option value='' <?php selected($options['terms_modal_pageid'], ''); ?>> - Don't use a page as popup content - </option>
                                            <?php
                                            if ($license_key_valid) {
                                                if ($pages = get_pages()) {
                                                    foreach ($pages as $page) {
                                                        echo ('<option value="' . esc_attr($page->ID) . '" ' . selected($page->ID, $options['terms_modal_pageid']) . '>' . esc_html($page->post_title) . '</option>');
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>

                                        <p> <?php echo esc_html__('Select the page that contains your terms and conditions. Will not take effect unless you have active License Key.', 'terms-popup-on-user-login'); ?> </p>

                                        <?php // wp_dropdown_pages( array( 'name' => 'russ_options[page_id]', 'selected' => $options['terms_modal_pageid'] ) ); 
                                        ?>

                                        </div>
                                        <p class="description"> <?php echo __('Selecting a page in this option will take precedence over "Content to show as terms in Popup". The content of the page will be shown instead of the text entered in the textbox above.', 'terms-popup-on-user-login'); ?> </p>

                                    <?php
                                }

                                function terms_modal_track_IP_render() {
                                    $options = $this->tpul_settings_term_modal_options;

                                    $license_key_valid = $this->license_key_handler->is_active();
                                    AdminForm::checkbox_single__active_key_required(
                                        $license_key_valid,
                                        $options,
                                        'tpul_settings_term_modal_options',
                                        'terms_modal_track_IP',
                                        __('Collect Request IP', 'terms-popup-on-user-login')
                                    );
                                    ?>

                                        <p class="description"><b><?php echo __('Must Read:', 'terms-popup-on-user-login'); ?> </b></p>
                                        <p class="description"> <?php echo __('It is best to notify your users before the popup that their IP data will be collected if you turn on this feature. Check privacy laws that apply to you, such as CCPA and GDPR, to know what you must inform your users about. By checking this checkbox, you acknowledge that you alone are responsible if you do not follow the privacy laws.', 'terms-popup-on-user-login'); ?> </p>
                                        <p class="description"> <?php echo __('IP values may not be accurate if your client is behind a proxy or if you are using a load balancer. This data can also be manipulated by the requesting client. Never solely rely on IP as identification.', 'terms-popup-on-user-login'); ?> </p>
                                        <p class="description"><b><?php echo __('By checking this checkbox, you acknowledge that it is your responsibility to follow all privacy laws, such as CCPA and GDPR.', 'terms-popup-on-user-login'); ?></b></p>

                                    <?php
                                }

                                function terms_modal_track_location_render() {
                                    $options = $this->tpul_settings_term_modal_options;

                                    $license_key_valid = $this->license_key_handler->is_active();
                                    AdminForm::checkbox_single__active_key_required(
                                        $license_key_valid,
                                        $options,
                                        'tpul_settings_term_modal_options',
                                        'terms_modal_track_location',
                                        __('Attempt latitude and longitude collection', 'terms-popup-on-user-login')
                                    );

                                    ?>
                                        <p class="description"><b><?php echo __('Must Read:', 'terms-popup-on-user-login'); ?> </b></p>
                                        <p class="description"> <?php echo __('Please be advised that Operating Systems and Browsers heavily guard against location tracking. A user must have allowed the OS to pass location to the Browser, and Browser Location must be allowed by the user for this to work. It is best to notify your users before the popup that location data is going to be collected if you turn this feature on. Check privacy laws that apply to you, such as CCPA and GDPR, to know what you must inform your users about.', 'terms-popup-on-user-login'); ?> </p>
                                        <p class="description"><b><?php echo __('By checking this checkbox, you acknowledge that it is your responsibility to follow all privacy laws, such as CCPA and GDPR.', 'terms-popup-on-user-login'); ?></b></p>
                                    <?php
                                }

                                function terms_modal_txt_logger_render() {
                                    $options = $this->tpul_settings_term_modal_options;

                                    $license_key_valid = $this->license_key_handler->is_active();
                                    AdminForm::checkbox_single__active_key_required(
                                        $license_key_valid,
                                        $options,
                                        'tpul_settings_term_modal_options',
                                        'terms_modal_txt_logger',
                                        __('Turn on every action logging', 'terms-popup-on-user-login')
                                    );

                                    ?>
                                        <p class="description"><b><?php echo __('Do not turn this on unless you were asked by the plugin support team to do so!', 'terms-popup-on-user-login'); ?></b></p>
                                        <p class="description"><?php echo __('This should only be turned on temporarely at the request of the  Terms and Conditions Popup Support team. It will generate a terms-popup-on-user-login-log/decision_log.text file in your uploads directory. This file can grow in size very quickly. You must turn it off and remove the file manually once testing is over.', 'terms-popup-on-user-login'); ?></b></p>
                                    <?php
                                }

                                function terms_modal_asset_placement_render() {

                                    $options = get_option('tpul_settings_term_modal_options');
                                    $select_options_array = [
                                        'header' => [
                                            'value' => 'styles_in_header',
                                            'label' => __('Default', 'terms-popup-on-user-login'),
                                        ],
                                        'footer' => [
                                            'value' => 'styles_in_footer',
                                            'label' => __('Styles in Footer', 'terms-popup-on-user-login'),
                                        ]
                                    ];

                                    LHL_Admin_UI_TPUL::admin_select(
                                        $options,
                                        'tpul_settings_term_modal_options',
                                        'terms_modal_asset_placement',
                                        $select_options_array,
                                        false
                                    );
                                    ?>

                                        <p class="description"> <?php echo __('Leave this as default unless the popup is not showing correctly. Some special themes or dashboards completely clear and take over the HTML < head > of your WordPress website, in those cases this could be switched to footer to make the popup show properly.', 'terms-popup-on-user-login'); ?> </p>

                                    <?php
                                }

                                function terms_modal_disable_for_new_render() {
                                    $options = $this->tpul_settings_term_modal_options;
                                    if (!isset($options['terms_modal_disable_for_new'])) $options['terms_modal_disable_for_new'] = 0;

                                    $html = '<input type="checkbox" id="terms_modal_disable_for_new" name="tpul_settings_term_modal_options[terms_modal_disable_for_new]" value="1"' . checked(1, $options['terms_modal_disable_for_new'], false) . '/>';
                                    $html .= '<label for="terms_modal_disable_for_new">Disable Popup for New users.</label>';

                                    echo $html;
                                }

                                function terms_modal_for_roles_render() {

                                    $license_key_valid = $this->license_key_handler->is_active();
                                    $is_disabled = !$license_key_valid ? "disabled='disabled'" : "";

                                    $options = $this->tpul_settings_term_modal_options;

                                    if ((!isset($options['terms_modal_for_roles'])) || (empty($options['terms_modal_for_roles']))) $options['terms_modal_for_roles'] = ['all'];
                                    $roles_checked_previously = $options['terms_modal_for_roles'];


                                    global $wp_roles;

                                    $all_roles = $wp_roles->roles;
                                    $editable_roles['all'] = array('name' => __('All logged in users', 'terms-popup-on-user-login'));
                                    $editable_roles = array_merge($editable_roles, apply_filters('editable_roles', $all_roles));

                                    ?>

                                        <?php if ($license_key_valid) : ?>
                                            <div class="tpul__alowed_box">
                                                <p class="tpul__license_notify_text"> <?php echo esc_html__('You have an active License Key. This feature is avaialble for you.', 'terms-popup-on-user-login'); ?> </p>
                                            <?php else : ?>
                                                <div class="tpul__restricted_box">
                                                    <p class="tpul__license_notify_text"> <?php echo esc_html__('This is a Premium Feature. You need a valid license key to activate this feature.', 'terms-popup-on-user-login'); ?> </p>
                                                <?php endif; ?>

                                                <?php

                                                foreach ($editable_roles as $key => $value) {
                                                    $label = $value['name'];
                                                    if (in_array($key, $roles_checked_previously)) {
                                                        $checked = true;
                                                    } else {
                                                        $checked = false;
                                                    }
                                                ?>
                                                    <div>
                                                        <input id="terms_modal_for_roles_<?php echo $key; ?>" name="tpul_settings_term_modal_options[terms_modal_for_roles][<?php echo $key; ?>]" type="checkbox" value="<?php echo $key;
                                                                                                                                                                                                                            ?>" <?php checked('1', $checked); ?> <?php echo $is_disabled; ?> />
                                                        <label for="terms_modal_for_roles_<?php echo $key; ?>"><?php echo $label; ?></label>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                                <p class="description"> <?php echo __('Select the user roles that you would like the popup to show.', 'terms-popup-on-user-login'); ?> </p>
                                                </div>

                                            <?php

                                        }


                                        public function terms_modal_width_render() {

                                            $tpul_settings_term_modal_display_options = get_option('tpul_settings_term_modal_display_options');
                                            if (empty($tpul_settings_term_modal_display_options['terms_modal_width'])) {
                                                $tpul_settings_term_modal_display_options['terms_modal_width'] = 'default';
                                            }
                                            ?>

                                                <select name='tpul_settings_term_modal_display_options[terms_modal_width]'>
                                                    <option value='default' <?php selected($tpul_settings_term_modal_display_options['terms_modal_width'], 'default'); ?>><?php _e('- Default (500px) - ', 'terms-popup-on-user-login'); ?></option>
                                                    <option value='width_800' <?php selected($tpul_settings_term_modal_display_options['terms_modal_width'], 'width_800'); ?>><?php _e('Wide (800px)', 'terms-popup-on-user-login'); ?></option>
                                                    <option value='width_1200' <?php selected($tpul_settings_term_modal_display_options['terms_modal_width'], 'width_1200'); ?>><?php _e('Widest (1200px)', 'terms-popup-on-user-login'); ?></option>
                                                </select>
                                                <p class="description"><?php echo __('Adjust the default outer width of the popup. It will automatically readjust to accomodate tablet and mobile devices', 'terms-popup-on-user-login'); ?> </p>

                                            <?php
                                        }

                                        public function terms_modal_height_render() {

                                            $tpul_settings_term_modal_display_options = get_option('tpul_settings_term_modal_display_options');
                                            if (empty($tpul_settings_term_modal_display_options['terms_modal_height'])) {
                                                $tpul_settings_term_modal_display_options['terms_modal_height'] = 'default';
                                            }
                                            ?>

                                                <select name='tpul_settings_term_modal_display_options[terms_modal_height]'>
                                                    <option value='default' <?php selected($tpul_settings_term_modal_display_options['terms_modal_height'], 'default'); ?>><?php _e('- Default - ', 'terms-popup-on-user-login'); ?></option>
                                                    <option value='tall_500' <?php selected($tpul_settings_term_modal_display_options['terms_modal_height'], 'tall_500'); ?>><?php _e('Tall', 'terms-popup-on-user-login'); ?></option>
                                                </select>
                                                <p class="description"><?php echo __('Adjust the default height of content of the popup. It will automatically readjust to accomodate tablet and mobile devices', 'terms-popup-on-user-login'); ?> </p>

                                            <?php
                                        }

                                        public function terms_modal_border_rnd_render() {
                                        }

                                        public function terms_modal_btn_border_rnd_render() {
                                        }

                                        public function terms_modal_acc_btn_size_render() {

                                            $tpul_settings_term_modal_display_options = get_option('tpul_settings_term_modal_display_options');
                                            if (empty($tpul_settings_term_modal_display_options['terms_modal_acc_btn_size'])) {
                                                $tpul_settings_term_modal_display_options['terms_modal_acc_btn_size'] = 'default';
                                            }
                                            ?>

                                                <select name='tpul_settings_term_modal_display_options[terms_modal_acc_btn_size]'>
                                                    <option value='default' <?php selected($tpul_settings_term_modal_display_options['terms_modal_acc_btn_size'], 'default'); ?>><?php _e('- Default - ', 'terms-popup-on-user-login'); ?></option>
                                                    <option value='small' <?php selected($tpul_settings_term_modal_display_options['terms_modal_acc_btn_size'], 'small'); ?>><?php _e('Small', 'terms-popup-on-user-login'); ?></option>
                                                    <option value='medium' <?php selected($tpul_settings_term_modal_display_options['terms_modal_acc_btn_size'], 'medium'); ?>><?php _e('Medium', 'terms-popup-on-user-login'); ?></option>
                                                    <option value='large' <?php selected($tpul_settings_term_modal_display_options['terms_modal_acc_btn_size'], 'large'); ?>><?php _e('Large', 'terms-popup-on-user-login'); ?></option>
                                                </select>
                                                <p class="description"><?php echo __('Adjust the Size of the Accept button. Default is the smallest.', 'terms-popup-on-user-login'); ?> </p>

                                            <?php

                                        }

                                        public function terms_modal_acc_btn_color_render() {
                                            // $options = $this->terms_modal_display_options;
                                            $options = get_option('tpul_settings_term_modal_display_options');

                                            $val = (isset($options['terms_modal_acc_btn_color'])) ? $options['terms_modal_acc_btn_color'] : '#00449e';
                                            echo '<input type="text" name="tpul_settings_term_modal_display_options[terms_modal_acc_btn_color]" value="' . $val . '" class="terms-modal-color-picker" >';
                                        }

                                        public function terms_modal_acc_btn_txt_color_render() {
                                            $options = get_option('tpul_settings_term_modal_display_options');

                                            $val = (isset($options['terms_modal_acc_btn_txt_color'])) ? $options['terms_modal_acc_btn_txt_color'] : 'white';
                                            echo '<input type="text" name="tpul_settings_term_modal_display_options[terms_modal_acc_btn_txt_color]" value="' . $val . '" class="terms-modal-color-picker" >';
                                        }

                                        public function terms_modal_dec_btn_size_render() {

                                            $tpul_settings_term_modal_display_options = get_option('tpul_settings_term_modal_display_options');
                                            if (empty($tpul_settings_term_modal_display_options['terms_modal_dec_btn_size'])) {
                                                $tpul_settings_term_modal_display_options['terms_modal_dec_btn_size'] = 'default';
                                            }
                                            ?>

                                                <select name='tpul_settings_term_modal_display_options[terms_modal_dec_btn_size]'>
                                                    <option value='default' <?php selected($tpul_settings_term_modal_display_options['terms_modal_dec_btn_size'], 'default'); ?>><?php _e('- Default - ', 'terms-popup-on-user-login'); ?></option>
                                                    <option value='small' <?php selected($tpul_settings_term_modal_display_options['terms_modal_dec_btn_size'], 'small'); ?>><?php _e('Small', 'terms-popup-on-user-login'); ?></option>
                                                    <option value='medium' <?php selected($tpul_settings_term_modal_display_options['terms_modal_dec_btn_size'], 'medium'); ?>><?php _e('Medium', 'terms-popup-on-user-login'); ?></option>
                                                    <option value='large' <?php selected($tpul_settings_term_modal_display_options['terms_modal_dec_btn_size'], 'large'); ?>><?php _e('Large', 'terms-popup-on-user-login'); ?></option>
                                                </select>
                                                <p class="description"><?php echo __('Adjust the Size of the Decline button. Default is the smallest.', 'terms-popup-on-user-login'); ?> </p>

                                            <?php
                                        }

                                        public function terms_modal_dec_btn_color_render() {
                                            $options = get_option('tpul_settings_term_modal_display_options');

                                            $val = (isset($options['terms_modal_dec_btn_color'])) ? $options['terms_modal_dec_btn_color'] : '#e6e6e6';
                                            echo '<input type="text" name="tpul_settings_term_modal_display_options[terms_modal_dec_btn_color]" value="' . $val . '" class="terms-modal-color-picker" >';
                                        }

                                        public function terms_modal_dec_btn_txt_color_render() {
                                            $options = get_option('tpul_settings_term_modal_display_options');

                                            $val = (isset($options['terms_modal_dec_btn_txt_color'])) ? $options['terms_modal_dec_btn_txt_color'] : '';
                                            echo '<input type="text" name="tpul_settings_term_modal_display_options[terms_modal_dec_btn_txt_color]" value="' . $val . '" class="terms-modal-color-picker" >';
                                        }

                                        public function terms_modal_woo_display_user_type_render() {
                                            $options = get_option('tpul_settings_term_modal_woo_options');
                                            $select_options_array = [
                                                'anonymous_only' => [
                                                    'value' => 'anonymous_only',
                                                    'label' => __('Anonymous visitors only', 'terms-popup-on-user-login'),
                                                    'with_license_key_only' => false
                                                ],
                                                'anonymous_and_logged_in' => [
                                                    'value' => 'anonymous_and_logged_in',
                                                    'label' => __('Anonymous visitors and logged in users', 'terms-popup-on-user-login'),
                                                    'with_license_key_only' => true
                                                ],
                                                'logged_in_only' => [
                                                    'value' => 'logged_in_only',
                                                    'label' => __('Logged in users only', 'terms-popup-on-user-login'),
                                                    'with_license_key_only' => true
                                                ]
                                            ];
                                            // Render Select box
                                            LHL_Admin_UI_TPUL::admin_select_active_key_required(
                                                $this->license_key_valid,
                                                $options,
                                                'tpul_settings_term_modal_woo_options',
                                                'terms_modal_woo_display_user_type',
                                                $select_options_array,
                                                true
                                            );

                                            ?>
                                                <p class="description lhl-admin-description"> <?php echo __('Select the types of users the popup should show for.', 'terms-popup-on-user-login'); ?> </p>
                                            <?php
                                        }

                                        public function terms_modal_woo_popup_frequency_render() {
                                            $options = get_option('tpul_settings_term_modal_woo_options');
                                            $select_options_array = [
                                                'every_time' => [
                                                    'value' => 'every_time',
                                                    'label' => __('Force "Accept" on every page reload', 'terms-popup-on-user-login'),
                                                    'with_license_key_only' => false,
                                                ],
                                                'until_browser_is_closed' => [
                                                    'value' => 'until_browser_is_closed',
                                                    'label' => __('Remember "Accept" answer until browser is closed', 'terms-popup-on-user-login'),
                                                    'with_license_key_only' => true,
                                                ],
                                            ];
                                            // Render Select box
                                            LHL_Admin_UI_TPUL::admin_select_active_key_required(
                                                $this->license_key_valid,
                                                $options,
                                                'tpul_settings_term_modal_woo_options',
                                                'terms_modal_woo_popup_frequency',
                                                $select_options_array,
                                                true
                                            );

                                            ?>
                                                <p class="description lhl-admin-description"> <?php echo __('Accept response for logged in users gets recorded in the database. <br> Select behaviour for Accept response for anonymous visitors here.  ', 'terms-popup-on-user-login'); ?> </p>
                                            <?php
                                        }

                                        public function terms_modal_woo_log_out_user_render() {
                                            $options = get_option('tpul_settings_term_modal_woo_options');
                                            // v_dump($options);
                                            $select_options_array = [
                                                'none' => [
                                                    'value' => '',
                                                    'label' => __('Redirect and keep user logged in', 'terms-popup-on-user-login'),
                                                    'with_license_key_only' => false
                                                ],
                                                'woo_log_out' => [
                                                    'value' => 'woo_log_out',
                                                    'label' => __('Redirect and log out user', 'terms-popup-on-user-login'),
                                                    'with_license_key_only' => true
                                                ],

                                            ];
                                            // Render Select box
                                            LHL_Admin_UI_TPUL::admin_select_active_key_required(
                                                $this->license_key_valid,
                                                $options,
                                                'tpul_settings_term_modal_woo_options',
                                                'terms_modal_woo_log_out_user',
                                                $select_options_array,
                                                true
                                            );

                                            ?>
                                                <p class="description lhl-admin-description"> <?php echo __('In the case of a logged in user declining terms and conditions on a WooCommercePage, should or should not the user be logged out after hitting the decline button.', 'terms-popup-on-user-login'); ?> </p>
                                            <?php
                                        }

                                        public function terms_modal_woo_display_on_render() {
                                            $options = get_option('tpul_settings_term_modal_woo_options');
                                            // v_dump($options);
                                            $select_options_array = [
                                                'product_pages' => [
                                                    'value' => 'product_pages',
                                                    'label' => __('All Product pages', 'terms-popup-on-user-login'),
                                                    'with_license_key_only' => false
                                                ],
                                                'product_pages_and_category' => [
                                                    'value' => 'product_pages_and_category',
                                                    'label' => __('All Product and Category pages', 'terms-popup-on-user-login'),
                                                    'with_license_key_only' => true
                                                ],
                                                'cart_page' => [
                                                    'value' => 'cart_page',
                                                    'label' => __('Cart Page', 'terms-popup-on-user-login'),
                                                    'with_license_key_only' => true
                                                ],
                                                'check_out_page' => [
                                                    'value' => 'check_out_page',
                                                    'label' => __('Check Out Page', 'terms-popup-on-user-login'),
                                                    'with_license_key_only' => true
                                                ],
                                            ];
                                            // Render Select box
                                            LHL_Admin_UI_TPUL::admin_select_active_key_required(
                                                $this->license_key_valid,
                                                $options,
                                                'tpul_settings_term_modal_woo_options',
                                                'terms_modal_woo_display_on',
                                                $select_options_array,
                                                true
                                            );

                                            ?>
                                                <p class="description lhl-admin-description"> <?php echo __('Select the types of pages the popup should be displayed on.', 'terms-popup-on-user-login'); ?> </p>
                                            <?php
                                        }

                                        function email_send_to_user_render() {
                                            $license_key_valid = $this->license_key_handler->is_active();

                                            AdminForm::checkbox_single__active_key_required(
                                                $license_key_valid,
                                                $this->email_options,
                                                $this->email_option_getter->get_option_name(),
                                                'email_send_to_user',
                                                __('Automatically send email confirmation to user', 'terms-popup-on-user-login')
                                            );
                                            ?>
                                                <p class="description"> <?php echo __('Notify users that they have accepted the terms and conditions. This has the potential to serve as proof, stored on third party email server that users have been notified of their actions.', 'terms-popup-on-user-login'); ?> </p>
                                            <?php
                                        }
                                        function email_send_to_admins_render() {
                                            $license_key_valid = $this->license_key_handler->is_active();

                                            AdminForm::checkbox_single__active_key_required(
                                                $license_key_valid,
                                                $this->email_options,
                                                $this->email_option_getter->get_option_name(),
                                                'email_send_to_admins',
                                                __('Send a copy of the email to the website admin as well', 'terms-popup-on-user-login')
                                            );
                                            ?>
                                                <p class="description"> <?php echo __('Best to have this turned on. Notify website admin about user accepting terms.', 'terms-popup-on-user-login'); ?> </p>
                                            <?php
                                        }
                                        function email_notify_about_anonymous_render() {
                                            $license_key_valid = $this->license_key_handler->is_active();

                                            AdminForm::checkbox_single__active_key_required(
                                                $license_key_valid,
                                                $this->email_options,
                                                $this->email_option_getter->get_option_name(),
                                                'email_notify_about_anonymous',
                                                __('Notify admins about anonymous users accepting your terms in Woocommerce mode', 'terms-popup-on-user-login')
                                            );
                                            ?>
                                                <p class="description"> <?php echo __('Best to have this turned on. Notify website admin about user accepting terms.', 'terms-popup-on-user-login'); ?> </p>
                                            <?php
                                        }

                                        function email_admin_addr_render() {
                                            $license_key_valid = $this->license_key_handler->is_active();

                                            AdminForm::email_input_multi(
                                                $this->email_options,
                                                $this->email_option_getter->get_option_name(),
                                                'email_admin_addr',
                                                false
                                            );
                                            ?>
                                                <p class="description"> <?php echo __('Comma separated multiple email adresses, invalid email formats will be removed. If left empty but option is turned on, it will default to website admin email: ', 'terms-popup-on-user-login'); ?>: <b><?php echo get_bloginfo('admin_email'); ?></b> </p>
                                            <?php
                                        }
                                        function email_subject_render() {

                                            AdminForm::text_input(
                                                $this->email_options,
                                                $this->email_option_getter->get_option_name(),
                                                'email_subject',
                                                false
                                            );
                                            ?>
                                                <p class="description"> <?php echo __('Subject line of email to be sent out to users. Will be trimmed to 45 characters for ebst chance of delivery.', 'terms-popup-on-user-login'); ?></p>
                                            <?php
                                        }

                                        function email_text_content_render() {

                                            AdminForm::textarea(
                                                $this->email_options,
                                                $this->email_option_getter->get_option_name(),
                                                'email_text_content',
                                                false
                                            );
                                            ?>
                                                <p class="description"> <?php echo __('Example content provided by default. Be sure to modify content, to best fit your needs. Replace  YOUR NAME /  YOUR POSITION / LINK TO PAGE etc. with real data.', 'terms-popup-on-user-login'); ?></p>
                                                <p class="description"> <?php echo __('Replacement Tokens available: ', 'terms-popup-on-user-login'); ?></p>
                                                <p class="description"> <?php echo "<b>[user-name]</b> - " . __('username of user', 'terms-popup-on-user-login'); ?></p>
                                                <p class="description"> <?php echo "<b>[website-name]</b> - " . get_bloginfo('name') . " - " . __('Your website name.', 'terms-popup-on-user-login'); ?></p>
                                                <p class="description"> <?php echo "<b>[website-url]</b> - " . get_bloginfo('url') . " - " . __('Your website URL.', 'terms-popup-on-user-login'); ?></p>

                                                <br />

                                        <?php

                                            /**
                                             * Add Sent Test Email Ajax button
                                             */

                                            $btn = [
                                                'id' => 'send_test_email',
                                                'classes_string' => implode(" ", ['button', 'button-default']),
                                                'attr_string' => implode(" ", []),
                                                'onclick_attr' => "onclick=tpul_send_test_email(event)",
                                                'data' => json_encode(
                                                    [
                                                        'reply_id' => 'hello',
                                                    ]
                                                ),
                                                'title' => __('Send Test Email', 'terms-popup-on-user-login'),
                                                'msg' => [
                                                    'classes_string' => implode(" ", ['lhl-ui-msg', 'hidden']),
                                                    'wait' => __('sending..', 'terms-popup-on-user-login'),
                                                    'success' => __('sent!', 'terms-popup-on-user-login'),
                                                    'error' => __('error', 'terms-popup-on-user-login'),
                                                ]
                                            ];

                                            $link_ =  sprintf(
                                                '<button id="%s" class="%s" %s %s data-vals="%s">%s</button>',
                                                esc_attr($btn['id']),
                                                esc_attr($btn['classes_string']),
                                                esc_attr($btn['attr_string']),
                                                esc_attr($btn['onclick_attr']),
                                                esc_attr($btn['data']),
                                                esc_attr($btn['title'])
                                            );
                                            $link_ .=  sprintf(
                                                " <span class='%s__msg %s' data-wait-msg='%s' data-success-msg='%s' data-error-msg='%s'></span>",
                                                esc_attr($btn['id']),
                                                esc_html($btn['msg']['classes_string']),
                                                esc_html($btn['msg']['wait']),
                                                esc_html($btn['msg']['success']),
                                                esc_html($btn['msg']['error']),
                                            );

                                            echo $link_;
                                        }

                                        /******************************************************************************
                                         * Helper Functions 
                                         ******************************************************************************/

                                        function __last_time_reset_ran() {
                                            $reset_info = get_option('tpul_settings_term_modal_reset_info');
                                            if (!empty($reset_info)) {
                                                if (!empty($reset_info['last_ran'])) {

                                                    $last_ran = $reset_info['last_ran'];

                                                    if (!empty($last_ran)) {
                                                        $date_format = get_option('date_format');
                                                        $time_format = get_option('time_format');
                                                        $last_ran_date = wp_date("{$date_format} {$time_format}", $last_ran);
                                                        return $last_ran_date;
                                                    }
                                                }
                                            }

                                            return "-";
                                        }
                                    }
