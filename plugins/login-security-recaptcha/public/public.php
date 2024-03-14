<?php
defined( 'ABSPATH' ) || die();

require_once STLSR_PLUGIN_DIR_PATH . 'public/inc/class-stlsr-language.php';
require_once STLSR_PLUGIN_DIR_PATH . 'public/inc/class-stlsr-captcha.php';

add_action( 'init', array( 'STLSR_Language', 'load_translation' ) );

add_action( 'login_form', array( 'STLSR_Captcha', 'login_form_captcha' ) );

add_filter( 'wp_authenticate_user', array( 'STLSR_Captcha', 'login_verify_captcha' ), 10, 2 );

add_action( 'lostpassword_form', array( 'STLSR_Captcha', 'lostpassword_form_captcha' ) );

add_action( 'lostpassword_post', array( 'STLSR_Captcha', 'lostpassword_verify_captcha' ) );

add_action( 'register_form', array( 'STLSR_Captcha', 'register_form_captcha' ) );

add_filter( 'registration_errors', array( 'STLSR_Captcha', 'register_verify_captcha' ), 10, 3 );

add_action( 'comment_form_after_fields', array( 'STLSR_Captcha', 'comment_form_captcha' ) );

add_action( 'comment_form_logged_in_after', array( 'STLSR_Captcha', 'comment_form_captcha' ) );

add_filter( 'preprocess_comment', array( 'STLSR_Captcha', 'comment_verify_captcha' ) );
