<?php
/**
 * Captcha Settings
 */
function youzify_membership_captcha_settings()
{

    global $Youzify_Settings;

    // Get Captcha Url
    $captcha_url = 'https://www.google.com/recaptcha/intro/index.html';

    $Youzify_Settings->get_field(
        array(
            'title' => __('How to get your captcha keys?', 'youzify'),
            'msg_type' => 'info',
            'type' => 'msgBox',
            'id' => 'youzify_msgbox_membership_captcha',
            'msg' => sprintf(__('To get your keys visit <strong><a href="%s">The reCAPTCHA Site</a></strong> and make sure to use the Recaptcha V2 or check the documentation.', 'youzify'), $captcha_url)
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __('General Settings', 'youzify'),
            'type' => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __('Enable Captcha', 'youzify'),
            'desc' => __('Enable using the captcha', 'youzify'),
            'id' => 'youzify_enable_signup_recaptcha',
            'type' => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __('Captcha Version', 'youzify'),
            'desc' => __('Select reCaptcha version', 'youzify'),
            'id' => 'youzify_signup_recaptcha_version',
            'type' => 'select',
            'opts' => array(
                'v2' => __('reCAPTCHA v2', 'youzify '),
                'v3' => __('reCAPTCHA v3', 'youzify')
            )
        )
    );


    $Youzify_Settings->get_field(array('type' => 'closeBox'));

    $Youzify_Settings->get_field(
        array(
            'title' => __('reCAPTCHAA v2 Settings', 'youzify'),
            'type' => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __('Captcha Site Key', 'youzify'),
            'desc' => __('The reCaptcha site key', 'youzify'),
            'id' => 'youzify_signup_recaptcha_site_key',
            'type' => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __('Captcha Secret Key', 'youzify'),
            'desc' => __('The reCaptcha secret key', 'youzify'),
            'id' => 'youzify_signup_recaptcha_secret_key',
            'type' => 'text'
        )
    );

    $Youzify_Settings->get_field(array('type' => 'closeBox'));

    $Youzify_Settings->get_field(
        array(
            'title' => __('reCAPTCHA v3 Settings', 'youzify'),
            'type' => 'openBox'
        )
    );


    $Youzify_Settings->get_field(
        array(
            'title' => __('Captcha Site Key', 'youzify'),
            'desc' => __('The reCaptcha site key', 'youzify'),
            'id' => 'youzify_signup_recaptcha3_site_key',
            'type' => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __('Captcha Secret Key', 'youzify'),
            'desc' => __('The reCaptcha secret key', 'youzify'),
            'id' => 'youzify_signup_recaptcha3_secret_key',
            'type' => 'text'
        )
    );

    $Youzify_Settings->get_field(array('type' => 'closeBox'));
}