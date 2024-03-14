<?php

class Youzify_Membership_Styling {

    public $styles_data;
    
    function __construct() {

        add_action( 'wp_enqueue_scripts', array( $this, 'custom_styling' ) );

    }

    /**
     * Styling Data.
     */
    function styles_data() {

        // Spacing Styles

        $this->styles_data[] = array(
            'id'        =>  'youzify_membership_forms_margin_top',
            'selector'  =>  '.youzify-membership-page-box',
            'property'  =>  'margin-top',
            'unit'      =>  'px'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_membership_forms_margin_bottom',
            'selector'  =>  '.youzify-membership-page-box',
            'property'  =>  'margin-bottom',
            'unit'      =>  'px'
        );

        // Registration Page .

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_title_color',
            'selector'  =>  '.youzify-membership-signup-page .form-cover-title,.youzify-membership-signup-page .form-title h2',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_subtitle_color',
            'selector'  =>  '.youzify-membership-signup-page .form-title span',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_cover_title_bg_color',
            'selector'  =>  '.youzify-membership-signup-page .form-cover-title',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_label_color',
            'selector'  =>  '.youzify-membership-signup-page .youzify-membership-form-item label,#youzify_membership_signup_form fieldset legend,#youzify_membership_signup_form label',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_inputs_txt_color',
            'selector'  =>  ".youzify-membership-signup-page .youzify-membership-form-item input:not([type='checkbox']),#youzify_membership_signup_form input",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_inputs_bg_color',
            'selector'  =>  '.youzify-membership-signup-page .youzify-membership-form-item .youzify-membership-field-content, #youzify_membership_signup_form input',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_inputs_border_color',
            'selector'  =>  '.youzify-membership-signup-page .youzify-membership-form-item .youzify-membership-field-content, .youzify-membership #youzify_membership_signup_form input',
            'property'  =>  'border-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_fields_icons_color',
            'selector'  =>  '.youzify-membership-signup-page .youzify-membership-form-item .youzify-membership-field-icon',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_fields_icons_bg_color',
            'selector'  =>  '.youzify-membership-signup-page .youzify-membership-form-item .youzify-membership-field-icon',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_placeholder_color',
            'selector'  =>  ".youzify-membership-signup-page input::-webkit-input-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_placeholder_color',
            'selector'  =>  ".youzify-membership-signup-page input::-moz-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_placeholder_color',
            'selector'  =>  ".youzify-membership-signup-page input::-ms-input-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_submit_bg_color',
            'selector'  =>  '.youzify-membership-signup-page .youzify-membership-action-item button',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_submit_txt_color',
            'selector'  =>  '.youzify-membership-signup-page .youzify-membership-action-item button',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_loginbutton_bg_color',
            'selector'  =>  '.youzify-membership-signup-page .youzify-membership-link-button',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_signup_loginbutton_txt_color',
            'selector'  =>  '.youzify-membership-signup-page .youzify-membership-link-button',
            'property'  =>  'color'
        );

        // Login Page .

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_title_color',
            'selector'  =>  '.youzify-membership-login-page .form-cover-title,.youzify-membership-login-page .form-title h2',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_subtitle_color',
            'selector'  =>  '.youzify-membership-login-page .form-title span',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_cover_title_bg_color',
            'selector'  =>  '.youzify-membership-login-page .form-cover-title',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_label_color',
            'selector'  =>  '.youzify-membership-login-page .youzify-membership-form-item label',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_inputs_txt_color',
            'selector'  =>  ".youzify-membership-login-page .youzify-membership-form-item input:not([type='checkbox'])",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_inputs_bg_color',
            'selector'  =>  ".youzify-membership-login-page .youzify-membership-form-item .youzify-membership-field-content",
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_inputs_border_color',
            'selector'  =>  ".youzify-membership-login-page .youzify-membership-form-item .youzify-membership-field-content",
            'property'  =>  'border-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_fields_icons_color',
            'selector'  =>  ".youzify-membership-login-page .youzify-membership-form-item .youzify-membership-field-icon",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_fields_icons_bg_color',
            'selector'  =>  ".youzify-membership-login-page .youzify-membership-form-item .youzify-membership-field-icon",
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_placeholder_color',
            'selector'  =>  ".youzify-membership-login-page input::-webkit-input-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_placeholder_color',
            'selector'  =>  ".youzify-membership-login-page input::-moz-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_placeholder_color',
            'selector'  =>  ".youzify-membership-login-page input::-ms-input-placeholder",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_lostpswd_color',
            'selector'  =>  ".youzify-membership-login-page .youzify-membership-forgot-password",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_remember_color',
            'selector'  =>  ".youzify-membership-login-page .youzify-membership-remember-me label",
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_checkbox_border_color',
            'selector'  =>  ".youzify-membership-login-page .youzify-membership-remember-me .youzify_membership_field_indication",
            'property'  =>  'border-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_checkbox_icon_color',
            'selector'  =>  ".youzify-membership-login-page .youzify-membership-remember-me .youzify_membership_checkbox_field .youzify_membership_field_indication:after",
            'property'  =>  'border-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_submit_bg_color',
            'selector'  =>  '.youzify-membership-login-page .youzify-membership-action-item button',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_submit_txt_color',
            'selector'  =>  '.youzify-membership-login-page .youzify-membership-action-item button',
            'property'  =>  'color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_regbutton_bg_color',
            'selector'  =>  '.youzify-membership-login-page .youzify-membership-link-button',
            'property'  =>  'background-color'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_regbutton_txt_color',
            'selector'  =>  '.youzify-membership-login-page .youzify-membership-link-button',
            'property'  =>  'color'
        );

        // Widgets Spaces

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_wg_margin_top',
            'selector'  =>  '.youzify-membership-login-widget',
            'property'  =>  'margin-top',
            'unit'      =>  'px'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_login_wg_margin_bottom',
            'selector'  =>  '.youzify-membership-login-widget',
            'property'  =>  'margin-bottom',
            'unit'      =>  'px'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_register_wg_margin_top',
            'selector'  =>  '.youzify-membership-register-widget',
            'property'  =>  'margin-top',
            'unit'      =>  'px'
        );

        $this->styles_data[] = array(
            'id'        =>  'youzify_register_wg_margin_bottom',
            'selector'  =>  '.youzify-membership-register-widget',
            'property'  =>  'margin-bottom',
            'unit'      =>  'px'
        );

        return $this->styles_data;
    }

    /**
     * Custom Styling.
     */
    function custom_styling() {

        if ( is_user_logged_in() ) {
            return;
        }

        // Custom Styling File.
        wp_enqueue_style( 'youzify-membership-customStyle', YOUZIFY_ADMIN_ASSETS . 'css/custom-script.css' );

        // Print Styles
        foreach ( $this->styles_data() as $key ) {

            // Get Data.
            $selector = $key['selector'];
            $property = $key['property'];

            $option = youzify_option( $key['id'] );
            $option = isset( $option['color'] ) ? $option['color'] : $option;
            if ( empty( $key['type'] ) && ! empty( $option ) ) {
                $unit = isset( $key['unit'] ) ? $key['unit'] : null;
                $custom_css = "
                    $selector {
                	$property: $option$unit !important;
                    }";
                wp_add_inline_style( 'youzify-membership-customStyle', $custom_css );
            }
        }
    }

}