<?php

/**
 * The modal
 *
 * @link       www.lehelmatyus.com/
 * @since      1.0.0
 *
 * @package    Perfecto_Portfolio
 * @subpackage Perfecto_Portfolio/admin
 */

use WpLHLAdminUi\LicenseKeys\LicenseKeyHandler;

defined('ABSPATH') || exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Perfecto_Portfolio
 * @subpackage Perfecto_Portfolio/admin
 * @author     Lehel Matyus <contact@lehelmatyus.com>
 */
class Terms_Popup_On_User_Login_Modal {

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

    // private $default_modal_type = 'modal-accept-terms';

    // private $modal_type;

    /**
     * Asset Load options
     *
     * @since    1.0.0
     * @access   private
     * @var      Array    $advanced_options    Option saved at the settings page
     */
    private $generals_options;

    private $modal_options;
    private $terms_options_data;

    private $classes_to_add_to_body = '';
    // private $should_popup_print;

    private $display_options;
    private $terms_display_options_data;

    private $woo_options;

    private $popup_type;

    private $license_is_active;

    public $is_designated_test_user;

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
        $this->is_designated_test_user = false;
        // $this->should_popup_print = false;

        /**
         * Get Options on what to show if anything
         */
        $gen_options = new TPUL_General_Options();
        $this->generals_options = $gen_options->get_options();

        /**
         * Get Options on what to show if anything
         */
        $this->modal_options = new TPUL_Modal_Options();
        $this->terms_options_data = $this->modal_options->get_options();

        /**
         * Get Options for display settings
         */
        $this->display_options = new TPUL_Display_Options();
        $this->terms_display_options_data = $this->display_options->get_options();

        /**
         * Get Options for WooCommerce Popup Options
         */
        $this->woo_options = new TPUL_Woo_Options();
        // $this->terms_woo_options_data = $this->woo_options->get_options();

        /**
         * Get popup type
         */
        $this->popup_type = new TPUL_Popup_Type();

        /**
         * Get Woo Connector
         */

        /**
         * License Key handler
         */
        $license_key_handler = new LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());;
        $this->license_is_active = $license_key_handler->is_active();

        /**
         * Body class init
         */
        $this->classes_to_add_to_body = $this->__get_body_class_to_add();


        // $this->should_popup_print
    }

    public function default_terms_modal_woo_options() {
        $defaults = array(
            'terms_modal_woo_display_on'                =>   'product_pages',
            'terms_modal_woo_display_user_type'           =>    'anonymous_and_logged_in',
            'terms_modal_woo_log_out_user'                =>    false,
            'until_browser_is_closed'                    =>    "until_browser_is_closed",
        );
        return $defaults;
    }

    /**
     * Adds modal to page
     * Containes core logic when and what type of popup to add
     */

    public function add_modal_to_footer() {
        $modal_visibility_manager = new TPUL_Moddal_Visibility_Manager();
        if ($modal_visibility_manager->should_modal_render()) {
            $this->insert_modal_must_accept_terms();
        }
    }

    public function insert_modal_must_accept_terms() {

        // cookie settings only used for anonymous users for woo type popup
        // is cookie setting is turned on, modal will check 
        // if anonymous user has already accepted the modal
        // and will not throw woo modal for anonymous users is they already have a cokkie
        // to prevent modal popup for every product visit
        $data_check_cookie = "data-checkcookie='true'";
        if (
            !is_user_logged_in() &&
            $this->popup_type->is_woo_modal() &&
            !$this->woo_options->should_save_cookie()
        ) {
            $data_check_cookie = "data-checkcookie='false'";
        }

        // Chek if Accept button should show immediately
        $disable_accept_until_scroll = 'disabled-by-default';
        if (!empty($this->terms_options_data['terms_modal_accept_enable'])) {
            $disable_accept_until_scroll = 'enabled-by-default';
        }

        if (!empty(($this->terms_options_data['terms_modal_agreed_text']))) {
            // echo $this->terms_options_data['terms_modal_agreed_text'];exit;
        }

        $terms_modal_width = "";
        if (!empty(($this->terms_display_options_data['terms_modal_width']))) {
            $terms_modal_width = $this->terms_display_options_data['terms_modal_width'];
        }

        $terms_modal_height = "";
        if (!empty(($this->terms_display_options_data['terms_modal_height']))) {
            $terms_modal_height = $this->terms_display_options_data['terms_modal_height'];
        }

        // Button Sizes
        $acc_btn_size = "";
        if (!empty(($this->terms_display_options_data['terms_modal_acc_btn_size']))) {
            $acc_btn_size = $this->terms_display_options_data['terms_modal_acc_btn_size'];
        }

        $dec_btn_size = "";
        if (!empty(($this->terms_display_options_data['terms_modal_dec_btn_size']))) {
            $dec_btn_size = $this->terms_display_options_data['terms_modal_dec_btn_size'];
        }

        // Accept Buton Color
        $acc_btn_color_style = "";
        if (!empty(($this->terms_display_options_data['terms_modal_acc_btn_color']))) {
            $acc_btn_color = $this->terms_display_options_data['terms_modal_acc_btn_color'];
            $acc_btn_color_style = ".modal__btn_accept { background-color: " . $acc_btn_color . "}";
        }

        $acc_btn_txt_color_style = "";
        if (!empty(($this->terms_display_options_data['terms_modal_acc_btn_txt_color']))) {
            $acc_btn_txt_color = $this->terms_display_options_data['terms_modal_acc_btn_txt_color'];
            $acc_btn_txt_color_style = ".modal__btn_accept { color: " . $acc_btn_txt_color . "}";
        }

        // Decline Buton Color
        $dec_btn_color_style = "";
        if (!empty(($this->terms_display_options_data['terms_modal_dec_btn_color']))) {
            $dec_btn_color = $this->terms_display_options_data['terms_modal_dec_btn_color'];
            $dec_btn_color_style = ".modal__close { background-color: " . $dec_btn_color . "}";
        }

        $dec_btn_txt_color_style = "";
        if (!empty(($this->terms_display_options_data['terms_modal_dec_btn_txt_color']))) {
            $dec_btn_txt_color = $this->terms_display_options_data['terms_modal_dec_btn_txt_color'];
            $dec_btn_txt_color_style = ".modal__close { color: " . $dec_btn_txt_color . "}";
        }

        // Font size of content
        $content_font_size = '';
        if (!empty(($this->terms_options_data['terms_modal_font_size'])) && is_numeric($this->terms_options_data['terms_modal_font_size'])) {
            $content_font_size = "
                .modal__terms_wrapper {
                    font-size: " . $this->terms_options_data['terms_modal_font_size'] . "px;
                }

            }";
        }

        echo "<style>
                {$acc_btn_color_style}
                {$acc_btn_txt_color_style}
                {$dec_btn_color_style}
                {$dec_btn_txt_color_style}
                {$content_font_size}
              ";
        echo '</style>';


        echo "<div class='ftlp_modal modal micromodal-slide' id='modal-accept-terms' aria-hidden='true' {$data_check_cookie}>";
        echo '<div class="modal__overlay" tabindex="-1" >';
        echo "<div class='modal__container {$terms_modal_width}' role='dialog' aria-modal='true' aria-labelledby='modal-accept-terms-title'>";

        if (!empty(($this->terms_options_data['terms_modal_title']))) {
            echo '<header class="modal__header">';
            echo '<h2 class="modal__title" id="modal-accept-terms-title">';
            echo $this->terms_options_data['terms_modal_title'];
            echo '</h2>';
            echo '</header>';
        }

        echo '<main class="modal__content" id="modal-accept-terms-content">';

        if (!empty(($this->terms_options_data['terms_modal_subtitle']))) {
            echo '<p class="modal__subtitle_wrapper">';
            echo $this->terms_options_data['terms_modal_subtitle'];
            echo '</p>';
        }

        echo "<div class='modal__terms_wrapper {$terms_modal_height}'>";

        echo '<div class="modal__terms__inner">';

        if ((!empty($this->terms_options_data['terms_modal_pageid'])) && $this->license_is_active) {

            $post_id = $this->terms_options_data['terms_modal_pageid'];
            $post_content = get_post($post_id);
            $content = $post_content->post_content;
            echo do_shortcode(wpautop(wp_kses_post($content)));
        } elseif (!empty($this->terms_options_data['terms_modal_content'])) {
            $content = $this->terms_options_data['terms_modal_content'];
            echo wpautop(wp_kses_post($content));
        }

        echo '</div>';

        echo '</div>';

        echo '<div id="tpul-modal-btn-decline-wait" class="modal__logginout_wrapper hide" data-redirectUrl="' . $this->terms_options_data['terms_modal_decline_redirect'] . '">';
        if (!empty(($this->terms_options_data['terms_modal_logout_text']))) {
            echo $this->terms_options_data['terms_modal_logout_text'];
        }
        echo '</div>';

        echo '<div class="modal__accepting_wrapper hide">';
        if (!empty(($this->terms_options_data['terms_modal_agreed_text']))) {
            echo $this->terms_options_data['terms_modal_agreed_text'];
        }
        echo '</div>';

        echo '<div class="modal__loader_wrapper hide">';
        echo '<div class="ftlp_loader"></div>';
        echo '</div>';

        echo '</main>';

        echo '<footer class="modal__footer">';
        // if modal on user login

        if ($this->popup_type->is_login_modal()) {

            $cancel_btn_classes = [
                "modal__btn",
                "modal__close",
                "modal__close_login",
                "modal__close--{$dec_btn_size}"
            ];
            $cancel_btn_classes = implode(" ", $cancel_btn_classes);
            $accept_btn_classes = [
                "modal__btn",
                "modal_accept",
                "modal_accept_login",
                "modal__btn_accept",
                "modal__btn-primary",
                "modal__btn_accept--{$acc_btn_size}",
                $disable_accept_until_scroll
            ];
            $accept_btn_classes = implode(" ", $accept_btn_classes);

            echo "<button id='tpul-modal-btn-cancel' class='{$cancel_btn_classes}' data-micromodal-close1 aria-label='Close this dialog window' data-redirectUrl='" . $this->terms_options_data['terms_modal_decline_redirect'] . "'>" . $this->terms_options_data['terms_modal_decline_button'] . '</button>';
            echo "<button id='tpul-modal-btn-accept' class='{$accept_btn_classes}' >" . $this->terms_options_data['terms_modal_accept_button'] . '</button>';
        }

        // if modal for woocommerce
        if ($this->popup_type->is_woo_modal()) {

            // Check if cancel should log out
            $data_logout = "data-logout='do-not-logout'";
            if ($this->woo_options->should_logout()) {
                $data_logout = "data-logout='logout'";
            }

            $data_decline_url = "data-declineredirect='/'";
            if ($this->modal_options->get_decline_redirect_url()) {
                $decline_url = $this->modal_options->get_decline_redirect_url();
                $data_decline_url = "data-declineredirect='{$decline_url}'";
            }

            // $data_accept_url = "data-acceptredirect=''";
            // if ($this->modal_options->get_accept_redirect_url()) {
            //     $accept_url = $this->modal_options->get_accept_redirect_url();
            //     $data_accept_url = "data-acceptredirect='{$accept_url}'";
            // }

            $data_save_cookie = "data-savecookie='false'";
            if ($this->woo_options->should_save_cookie()) {
                $data_save_cookie = "data-savecookie='true'";
            }

            $data_is_logged_in = "data-isloggedin='false'";
            if (is_user_logged_in()) {
                // logged in users will be registered in db for their accept
                $data_is_logged_in = "data-isloggedin='true'";
            }

            $data_attribtes = [
                $data_logout, $data_decline_url, //$data_accept_url, 
                $data_save_cookie, $data_is_logged_in
            ];
            $data_attribtes = implode(" ", $data_attribtes);

            $cancel_btn_classes = [
                "modal__btn",
                "modal__close",
                "modal__close_woo",
                "modal__close--{$dec_btn_size}"
            ];
            $cancel_btn_classes = implode(" ", $cancel_btn_classes);
            $accept_btn_classes = [
                "modal__btn",
                "modal_accept",
                "modal_accept_woo",
                "modal__btn_accept",
                "modal__btn-primary",
                "modal__btn_accept--{$acc_btn_size}",
                $disable_accept_until_scroll
            ];
            $accept_btn_classes = implode(" ", $accept_btn_classes);

            echo "<button id='tpul-modal-btn-cancel' class='{$cancel_btn_classes}' {$data_attribtes} data-micromodal-close1 aria-label='Close this dialog window' data-redirectUrl='" . $this->terms_options_data['terms_modal_decline_redirect'] . "'>" . $this->terms_options_data['terms_modal_decline_button'] . '</button>';
            echo "<button id='tpul-modal-btn-accept' class='{$accept_btn_classes}' {$data_attribtes} >" . $this->terms_options_data['terms_modal_accept_button'] . '</button>';
        }

        echo '</footer>';

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    function add_slug_body_class($classes) {
        $modal_visibility_manager = new TPUL_Moddal_Visibility_Manager();
        if ($modal_visibility_manager->should_modal_render()) {
            $classes[] = $this->classes_to_add_to_body;
        }
        return $classes;
    }

    function location_tracking_body_class($classes) {

        $gen_options = new TPUL_Modal_Options();
        if ($gen_options->get_track_location()) {
            $classes[] = 'tpulGeoLocationTrackingEnabled';
        }
        return $classes;
    }

    /**
     * Clear users acceptance for this session
     */
    function user_logout_clear_acceptance_for_this_session() {
        $user  = wp_get_current_user();
        $user_id   = (int) $user->ID;
        $user_state_manager = new TPUL_User_State($user_id);
        $user_state_manager->clear_acceptance_for_this_session();
    }

    /**
     * Returns the class-name we need to put on the body;
     */


    function __get_body_class_to_add() {
        // v_dump($this->generals_options['modal_to_show']);

        switch ($this->generals_options['modal_to_show']) {

            case 'terms_and_conditions_modal':
                return 'terms-popup-on-user-login-accept-terms';
                break;

            case 'terms_and_conditions_modal_test':
                return 'terms-popup-on-user-login-accept-terms-testmode';
                break;

            case 'terms_and_conditions_modal_woo':
                return 'terms-popup-on-user-login-accept-terms';
                break;

            default:
                # code...
                break;
        }

        return 'terms-popup-on-user-login-none';
    }
}
