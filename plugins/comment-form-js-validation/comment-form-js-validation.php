<?php
/*
Plugin Name: Comment Form Js Validation
Plugin URI:  https://wordpress.org/plugins/comment-form-js-validation/
Description: This plugin use for wordpress comments form js validation to the comment form.
Version:     1.2
Author:      Navnit Viradiya
Author URI:  https://profiles.wordpress.org/navnitviradiya13
License: 
License URI:
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Comment Form js Validation Main Class
 */
class Nv_Comment_Form_Js_Validation
{
	/**
     * Holds the values to be used in the fields callbacks
     */
    public $nv_cfjv_setting;
    public $nv_cfjv_captcha_setting;


	function __construct()
	{
		$this->nv_cfjv_define_constants(); //Define Constants
		
		$this->nv_cfjv_setting = (get_option( 'nv_comment_form_jv' )) ? get_option( 'nv_comment_form_jv' ) : array();
		$this->nv_cfjv_captcha_setting	= (get_option( 'nv_comment_form_jv_captch' )) ? get_option( 'nv_comment_form_jv_captch' ) : array();
		$this->nv_cfjv_setting = array_merge($this->nv_cfjv_setting, $this->nv_cfjv_captcha_setting);

		$this->nv_cfjv_includes();

		add_action( 'wp_enqueue_scripts', array( $this, 'nv_wp_enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'nv_enqueue_scripts' ) );
		add_filter( 'plugin_action_links_' .NV_CFJV_BASE_NAME, array( $this, 'nv_cfjv_setting_link' ) );
	}

	public function nv_cfjv_define_constants(){

		//define( 'NV_CFJV_VERSION', '1.2' );

		if ( !defined( 'NV_CFJV_BASE_NAME' ) ) { 
			define('NV_CFJV_BASE_NAME', plugin_basename( __FILE__ )); 
		}

		if ( !defined( 'NV_CFJV_DIR_PATH' ) ) { 
			define('NV_CFJV_DIR_PATH', plugin_dir_path( __FILE__ ));
		}

		if ( !defined( 'NV_CFJV_DIR_URL' ) ) { 
			define('NV_CFJV_DIR_URL', plugin_dir_url( __FILE__ ));
		}

		if ( !defined( 'NV_CFJV_RECAPTCHA_SITE' ) ) {
			define( 'NV_CFJV_RECAPTCHA_SITE',    'https://www.google.com/recaptcha/intro/index.html' );
		}

		if ( !defined( 'NV_CFJV_RECAPTCHA_SHOW' ) ) {
			define( 'NV_CFJV_RECAPTCHA_SHOW', 	'https://www.google.com/recaptcha/api.js?' );
		}

		if ( !defined( 'NV_CFJV_RECAPTCHA_VERIFY' ) ) {
			define( 'NV_CFJV_RECAPTCHA_VERIFY',  'https://www.google.com/recaptcha/api/siteverify?' );
		}

		if ( !defined( 'NV_CFJV_RECAPTCHA_DOCS' ) ) {
			define( 'NV_CFJV_RECAPTCHA_DOCS',    'https://developers.google.com/recaptcha/' );
		}
	}

	public function nv_cfjv_setting_link ( $links ) {
		$mylinks = array(
			'<a href="' . admin_url( 'options-general.php?page=comment-form-jv-setting' ) . '">Settings</a>',
		);
		return array_merge( $links, $mylinks );
	}

	/**
	* Include files
	*/
	public function nv_cfjv_includes(){
		
		if(is_admin()){
			/* Setting */
			require_once(NV_CFJV_DIR_PATH.'includes/admin/class-comment-form-js-validation-admin.php');
			$CommentFormJsValidationSettingObj = new CommentFormJsValidationSetting();
		}
			
		/* Captcha */
		require_once(NV_CFJV_DIR_PATH.'includes/class-comment-form-js-validation-captcha.php');
		$Nv_Comment_Form_Js_Validation_Captcha_Obj = new Nv_Comment_Form_Js_Validation_Captcha($this->nv_cfjv_setting);
		
	}


	public function nv_enqueue_scripts() {
		if(is_single() && comments_open() ) {

			$nv_cfjv_setting 			= $this->nv_cfjv_setting;
			$nv_comment_form_jv_array	= array();

			wp_enqueue_script( 'nv-jquery-validate', NV_CFJV_DIR_URL.'includes/public/js/jquery.validate.min.js', array('jquery'), false, true);
			wp_enqueue_script( 'nv-validation', NV_CFJV_DIR_URL.'includes/public/js/nv-validation.js', array('jquery'), false, true);


			if(isset($nv_cfjv_setting['comment_comment_msg']) && $nv_cfjv_setting['comment_comment_msg'] != ''){
				$nv_comment_form_jv_array['comment_comment_msg'] = __($nv_cfjv_setting['comment_comment_msg']); 
			} else {
				$nv_comment_form_jv_array['comment_comment_msg'] = __('Please enter your comment.');
				$nv_cfjv_setting['comment_comment_msg'] = __('Please enter your comment.');
			}

			if(isset($nv_cfjv_setting['comment_name_msg']) && $nv_cfjv_setting['comment_name_msg'] != ''){
				$nv_comment_form_jv_array['comment_name_msg'] = __($nv_cfjv_setting['comment_name_msg']); 
			} else {
				$nv_comment_form_jv_array['comment_name_msg'] = __('Please enter your name.');
				$nv_cfjv_setting['comment_name_msg'] = __('Please enter your name.');
			}

			if(isset($nv_cfjv_setting['comment_email_msg']) && $nv_cfjv_setting['comment_email_msg'] != ''){
				$nv_comment_form_jv_array['comment_email_msg'] = __($nv_cfjv_setting['comment_email_msg']); 
			} else {
				$nv_comment_form_jv_array['comment_email_msg'] = __('Please enter your email address.');
				$nv_cfjv_setting['comment_email_msg'] = __('Please enter your email address.');
			}
			
			// Localize the script with message
			$nv_comment_form_jv_array = array_merge($nv_comment_form_jv_array, $nv_cfjv_setting);
			wp_localize_script( 'nv-validation', 'cfjv_obj', $nv_comment_form_jv_array );

			// reCAPTCHA Google script
			wp_register_script ( 'cfjv-recaptcha-call', NV_CFJV_RECAPTCHA_SHOW . "onload=nvcfjvOnloadCallback&render=explicit", array('jquery'), false, true );
			wp_enqueue_script  ( 'cfjv-recaptcha-call' );
		}
	}


	public function nv_wp_enqueue_styles(){
		if(is_single() && comments_open() ) {
			wp_enqueue_style( 'nv-validation-style',  NV_CFJV_DIR_URL.'includes/public/css/nv-validation.css');
			wp_enqueue_style( 'nv-validation-style' );
		}
	}
}
$Nv_Comment_Form_Js_Validation_Obj = new Nv_Comment_Form_Js_Validation();
?>
