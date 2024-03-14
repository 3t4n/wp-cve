<?php
/**
 * Main admin class
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH Custom Login
 * @version 1.0.2
 */

if ( !defined( 'YITH_LOGIN' ) ) { exit; } // Exit if accessed directly

global $yith_login_options;
$yith_login_options = array(
    //tab general
    'general' => array(
        'label' => __('General', 'yith-custom-login'),
        'sections' => array(
            'general' => array(
                'title' =>  __('General Settings', 'yith-custom-login'),
                'description' => '',
                'fields' => array(
                    'yith_login_enable' => array(
                        'title' => __('Enable Custom Login', 'yith-custom-login'),
                        'description' => __( 'Enabling this, you will be able to have a custom style for the login page.', 'yith-custom-login' ),
                        'type' => 'checkbox',
                        'std' => true
                    ),

                    'yith_login_mascotte' => array(
                        'title' => __( 'Show Mascotte image', 'yith-custom-login' ),
                        'description' => __( 'Say if you want to show the mascotte image near the login form.', 'yith-custom-login' ),
                        'type' => 'checkbox',
                        'std' => true
                    ),

                    'yith_login_mascotte_url' => array(
                        'title' => __( 'Mascotte image URL', 'yith-custom-login' ),
                        'description' => __( 'Set the URL for the mascote image near the login form, if you set to show it in the above option.', 'yith-custom-login' ),
                        'type' => 'upload',
                        'std' => YITH_LOGIN_URL . 'assets/images/mascotte.png'
                    ),

                    'yith_login_custom_style' => array(
                        'title' => 'Custom CSS',
                        'description' => 'Custom CSS Style',
                        'type' => 'textarea',
                        'std' => ''
                    ),
                )
            ),
        )
    ),

    //tab background
    'background' => array(
        'label' => __('Background', 'yith-custom-login'),
        'sections' => array(
            'background' => array(
                'title' =>  __('Background Settings', 'yith-custom-login'),
                'description' => __('Customize the background of the page', 'yith-custom-login'),
                'fields' => array(
                    'yith_login_background_image' => array(
                        'title' =>  __('Background image', 'yith-custom-login'),
                        'description' => __('Upload or choose from your media library the background image', 'yith-custom-login'),
                        'type' => 'upload',
                        'std' => YITH_LOGIN_URL . 'assets/images/bg-pattern.png',
                    ),
                    'yith_login_background_color' => array(
                        'title' =>  __('Background Color', 'yith-custom-login'),
                        'description' => __('Choose a background color', 'yith-custom-login'),
                        'type' => 'colorpicker',
                        'std' => '',
                    ),
                    'yith_login_background_repeat' => array(
                        'title' =>  __('Background Repeat', 'yith-custom-login'),
                        'description' => __( 'Select the repeat mode for the background image.', 'yith-custom-login' ),
                        'type' => 'select',
                        'std' => apply_filters( 'yith_login_background_repeat_std', 'repeat' ),
                        'options' => array(
                            'repeat' => __( 'Repeat', 'yith-custom-login' ),
                            'repeat-x' => __( 'Repeat Horizontally', 'yith-custom-login' ),
                            'repeat-y' => __( 'Repeat Vertically', 'yith-custom-login' ),
                            'no-repeat' => __( 'No Repeat', 'yith-custom-login' ),
                        )
                    ),
                    'yith_login_background_position' => array(
                        'title' =>  __('Background Position', 'yith-custom-login'),
                        'description' =>  __( 'Select the position for the background image.', 'yith-custom-login' ),
                        'type' => 'select',
                        'options' => array(
                            'center' => __( 'Center', 'yith-custom-login' ),
                            'top left' => __( 'Top left', 'yith-custom-login' ),
                            'top center' => __( 'Top center', 'yith-custom-login' ),
                            'top right' => __( 'Top right', 'yith-custom-login' ),
                            'bottom left' => __( 'Bottom left', 'yith-custom-login' ),
                            'bottom center' => __( 'Bottom center', 'yith-custom-login' ),
                            'bottom right' => __( 'Bottom right', 'yith-custom-login' ),
                        ),
                        'std' => apply_filters( 'yith_login_background_position_std', 'top left' )
                    ),
                    'yith_login_background_attachment' => array(
                        'title' =>  __('Background Attachment', 'yith-custom-login'),
                        'description' => __( 'Select the attachment for the background image.', 'yith-custom-login' ),
                        'type' => 'select',
                        'options' => array(
                            'scroll' => __( 'Scroll', 'yith-custom-login' ),
                            'fixed' => __( 'Fixed', 'yith-custom-login' ),
                        ),
                        'std' => apply_filters( 'yith_login_background_attachment_std', 'scroll' )
                    )
                )
            )
        )
    ),

    //tab logo
    'logo' => array(
        'label' => __('Logo', 'yith-custom-login'),
        'sections' => array(
            'logo' => array(
                'title' =>  __('Logo Settings', 'yith-custom-login'),
                'description' => __('Customize the logo', 'yith-custom-login'),
                'fields' => array(
                    'yith_login_logo_image' => array(
                        'title' =>  __('Logo image', 'yith-custom-login'),
                        'description' => __('Upload or choose from your media library the logo image', 'yith-custom-login'),
                        'type' => 'upload',
                        'std' => YITH_LOGIN_URL . 'assets/images/logo.png',
                    ),
                    'yith_login_logo_color' => array(
                        'title' =>  __('Background Color', 'yith-custom-login'),
                        'description' => __('Choose a background color for the logo', 'yith-custom-login'),
                        'type' => 'colorpicker',
                        'std' => '',
                    ),
                    'yith_login_logo_width' => array(
                        'title' =>  __('Logo width', 'yith-custom-login'),
                        'description' => __('The width of logo', 'yith-custom-login'),
                        'type' => 'number',
                        'std' => 196,
                    ),
                    'yith_login_logo_height' => array(
                        'title' =>  __('Logo height', 'yith-custom-login'),
                        'description' => __('The height of logo', 'yith-custom-login'),
                        'type' => 'number',
                        'std' => 53,
                    )
                )
            )
        )
    ),

    //tab container
    'form' => array(
        'label' => __('Form', 'yith-custom-login'),
        'sections' => array(
            'settings' => array(
                'title' =>  __('Settings', 'yith-custom-login'),
                'description' => __('Customize the form', 'yith-custom-login'),
                'fields' => array(
                    'yith_login_container_width' => array(
                        'title' =>  __('Container Width', 'yith-custom-login'),
                        'description' => __('The width in pixels of the login container', 'yith-custom-login'),
                        'type' => 'number',
                        'std' => 275,
                        'step' => 1,
                        'min' => 320,
                        'max' => 800
                    ),
                    'yith_login_username_label' => array(
                        'title' =>  __('Username placeholder', 'yith-custom-login'),
                        'description' => __('The placeholder for the username field', 'yith-custom-login'),
                        'type' => 'text',
                        'std' => 'Username *'
                    ),
                    'yith_login_password_label' => array(
                        'title' =>  __('Password placeholder', 'yith-custom-login'),
                        'description' => __('The placeholder for the password field', 'yith-custom-login'),
                        'type' => 'text',
                        'std' => 'Password *'
                    )
                )
            ),
            'typography' => array(
                'title' =>  __('Typography', 'yith-custom-login'),
                'description' => '',
                'fields' => array(
                    'yith_login_typo_input' => array(
                        'title' =>  __('Text of fields', 'yith-custom-login'),
                        'description' => __('The text typography for the username and password fields.', 'yith-custom-login'),
                        'type' => 'typography',
                        'std' => array(
                            'size' => 12,
                            'unit' => 'px',
                            'family' => 'verdana',
                            'style' => 'regular',
                            'color' => '#b4b4b4',
                        ),
                    ),
                    'yith_login_typo_text' => array(
                        'title' =>  __('General text', 'yith-custom-login'),
                        'description' => __('Set typography options for other texts (as Remember me).', 'yith-custom-login'),
                        'type' => 'typography',
                        'std' => array(
                            'size' => 12,
                            'unit' => 'px',
                            'family' => 'verdana',
                            'style' => 'regular',
                            'color' => '#999999',
                        ),
                    ),
                    'yith_login_typo_submit' => array(
                        'title' =>  __('Submit button text', 'yith-custom-login'),
                        'description' => __('Set typography options for the text inside submit button.', 'yith-custom-login'),
                        'type' => 'typography',
                        'std' => array(
                            'size' => 15,
                            'unit' => 'px',
                            'family' => 'verdana',
                            'style' => 'extra-bold',
                            'color' => '#ffffff',
                        ),
                    ),
                    'yith_login_typo_submit_hover' => array(
                        'title' =>  __('Submit button text, on hover', 'yith-custom-login'),
                        'description' => __('Set typography options for the text inside submit button, when mouse hover.', 'yith-custom-login'),
                        'type' => 'colorpicker',
                        'std' => '#ffffff',
                    ),
                )
            ),
            'colors' => array(
                'title' =>  __('Colors', 'yith-custom-login'),
                'description' => '',
                'fields' => array(
                    'yith_login_color_input' => array(
                        'title' =>  __('Fields background', 'yith-custom-login'),
                        'description' => __('The background color of the fields username and password.', 'yith-custom-login'),
                        'type' => 'colorpicker',
                        'std' => '#f2f2f2',
                    ),
                    'yith_login_border_input' => array(
                        'title' =>  __('Fields border color', 'yith-custom-login'),
                        'description' => __('The border color of the fields username and password.', 'yith-custom-login'),
                        'type' => 'colorpicker',
                        'std' => '#b4b4b4',
                    ),
                    'yith_login_color_input_focus' => array(
                        'title' =>  __('Fields background, on focus', 'yith-custom-login'),
                        'description' => __('The background color of the fields username and password, on focus.', 'yith-custom-login'),
                        'type' => 'colorpicker',
                        'std' => '#ffffff',
                    ),
                    'yith_login_border_input_focus' => array(
                        'title' =>  __('Fields border color, on focus', 'yith-custom-login'),
                        'description' => __('The border color of the fields username and password, on focus.', 'yith-custom-login'),
                        'type' => 'colorpicker',
                        'std' => '#8a8a8a',
                    ),
                    'yith_login_submit_color' => array(
                        'title' =>  __('Background color of submit ', 'yith-custom-login'),
                        'description' => __('The background color of submit button.', 'yith-custom-login'),
                        'type' => 'colorpicker',
                        'std' => '#617291',
                    ),
                    'yith_login_submit_border_color' => array(
                        'title' =>  __('Border color of submit ', 'yith-custom-login'),
                        'description' => __('The border color of submit button.', 'yith-custom-login'),
                        'type' => 'colorpicker',
                        'std' => '#3c5072',
                    ),
                    'yith_login_submit_color_hover' => array(
                        'title' =>  __('Background color of submit, on hover', 'yith-custom-login'),
                        'description' => __('The background color of submit button, on mouseover.', 'yith-custom-login'),
                        'type' => 'colorpicker',
                        'std' => '#3c5072',
                    ),
                    'yith_login_submit_border_color_hover' => array(
                        'title' =>  __('Border color of submit, on hover', 'yith-custom-login'),
                        'description' => __('The border color of submit button, on mouseover.', 'yith-custom-login'),
                        'type' => 'colorpicker',
                        'std' => '#3c5072',
                    ),
                )
            )
        )
    )
);
