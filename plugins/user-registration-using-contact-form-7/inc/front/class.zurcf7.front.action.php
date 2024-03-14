<?php
/**
 * ZURCF7_Front_Action Class
 *
 * Handles the Frontend Actions.
 *
 * @package WordPress
 * @subpackage Plugin name
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ZURCF7_Front_Action' ) ){

	/**
	 *  The ZURCF7_Front_Action Class
	 */
	class ZURCF7_Front_Action {

		function __construct()  {

			add_action( 'init', array( $this, 'action__init' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'action__wp_enqueue_scripts' ) );

			//insert user before mail sent
			add_action('wpcf7_before_send_mail', array( $this, 'zurcf7_before_sent_mail'), 10,3);

			add_filter( 'wpcf7_feedback_response',    array( $this, 'filter__zurcf7_ajax_json_echo'   ), 20, 2 );

		}

		/*
		   ###     ######  ######## ####  #######  ##    ##  ######
		  ## ##   ##    ##    ##     ##  ##     ## ###   ## ##    ##
		 ##   ##  ##          ##     ##  ##     ## ####  ## ##
		##     ## ##          ##     ##  ##     ## ## ## ##  ######
		######### ##          ##     ##  ##     ## ##  ####       ##
		##     ## ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##     ##  ######     ##    ####  #######  ##    ##  ######
		*/


		/**
		 * Action: init
		 *
		 * - Start session
		 *
		 * @method action__init
		 *
		 */
		function action__init() {
			if ( !isset( $_SESSION ) || session_status() == PHP_SESSION_NONE ) {
				session_start();
			}
		}


		/**
		 * Action: wp_enqueue_scripts
		 *
		 * - enqueue script in front side
		 *
		 */
		function action__wp_enqueue_scripts() {
			wp_enqueue_script( ZURCF7_PREFIX . '_front_js', ZURCF7_URL . 'assets/js/front.min.js', array( 'jquery-core' ), ZURCF7_VERSION );

			// Localize the script with new data
			$zurcf7_successurl_field = get_option( 'zurcf7_successurl_field');

			$translation_array = array(
				'reg_form_id' => (get_option( 'zurcf7_formid')) ? get_option( 'zurcf7_formid') : "",
				'reg_form_redirect' => ($zurcf7_successurl_field) ? get_the_permalink($zurcf7_successurl_field) : "",
			);
			wp_localize_script( ZURCF7_PREFIX . '_front_js', 'cf7forms_data', $translation_array );
		}


		/*
		######## ##     ## ##    ##  ######  ######## ####  #######  ##    ##  ######
		##       ##     ## ###   ## ##    ##    ##     ##  ##     ## ###   ## ##    ##
		##       ##     ## ####  ## ##          ##     ##  ##     ## ####  ## ##
		######   ##     ## ## ## ## ##          ##     ##  ##     ## ## ## ##  ######
		##       ##     ## ##  #### ##          ##     ##  ##     ## ##  ####       ##
		##       ##     ## ##   ### ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##        #######  ##    ##  ######     ##    ####  #######  ##    ##  ######
		*/


		/**
		 * Action: CF7 before send email
		 *
		 * @method zurcf7_before_sent_mail
		 *
		 * @param  object $contact_form WPCF7_ContactForm::get_instance()
		 * @param  bool   $abort
		 * @param  object $contact_form WPCF7_Submission class
		 *
		 */
		function zurcf7_before_sent_mail( $contact_form, &$abort, $object ) {

			$submission = WPCF7_Submission::get_instance();

			//get form details
			$form_id = $contact_form->id();
			$form_title = $contact_form->title();
			$form_instance = WPCF7_ContactForm::get_instance($form_id);

			//get fields data from the database
			$zurcf7_formid = (get_option( 'zurcf7_formid')) ? get_option( 'zurcf7_formid') : "";
			$zurcf7_email_field = (get_option( 'zurcf7_email_field')) ? get_option( 'zurcf7_email_field') : "";
			$zurcf7_username_field = (get_option( 'zurcf7_username_field')) ? get_option( 'zurcf7_username_field') : "";
			$zurcf7_userrole_field = (get_option( 'zurcf7_userrole_field')) ? get_option( 'zurcf7_userrole_field') : "";


			$user_pwd = '';
			//Registration form check
			if ( $submission && ($zurcf7_formid == $form_id)) {
				$data= $submission->get_posted_data();

				foreach ($data as $key => $d) {
					//check for email field
					if($key === $zurcf7_email_field){
						$user_email = $d;
					}

					//check for username field
					if($key === $zurcf7_username_field){
						$user_fname = $d;
					}

				}

				if(filter_var($user_email, FILTER_VALIDATE_EMAIL)){
					if(!empty(trim($user_email))){
						//check for email existanse from WP method
						if(false == email_exists( $user_email )){
							if(empty($user_pwd)){
								$user_pwd = wp_generate_password( $length = 12, $include_standard_special_chars = true );
							}

							//insert the user if email is not exists
							$userdata = array(
								'user_login'	=>  wp_slash($user_fname),
								'user_pass'		=>  $user_pwd,
								'user_email'	=>  wp_slash($user_email),
								'role'			=>	$zurcf7_userrole_field,
							);
							$user_id = wp_insert_user( $userdata );


							if ( is_wp_error( $user_id  ) ) {
								//if there is any error abort the current process
								$abort = true;
								$object->set_response($user_id->get_error_message(),'zeal-user-reg-cf7');
								$this->zurcf7_custom_logs($user_id->get_error_message());
							}else{

								$zurcf7_successurl_field = get_option( 'zurcf7_successurl_field');
								$zurcf7_enable_sent_login_url = get_option( 'zurcf7_enable_sent_login_url');
								if(!empty($zurcf7_enable_sent_login_url)) {
									$login_url = !empty($zurcf7_successurl_field) ? get_the_permalink($zurcf7_successurl_field) : wp_login_url();
									// Email login details to user
									$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
									$message = "Welcome! Your login details are as follows:" . "\r\n";
									$message .= sprintf(__('Username: %s'), $user_email) . "\r\n";
									$message .= sprintf(__('Password: %s'), $user_pwd) . "\r\n";
									$message .= $login_url . "\r\n";
									wp_mail($user_email, sprintf(__('[%s] Your username and password'), $blogname), $message);
								}


								//create post from email id
								$zur_post_id = $this->zurcf7_insert_post_title($submission, $user_id);

								add_post_meta( $zur_post_id, ZURCF7_META_PREFIX.'form_id', $form_id, true);
								add_post_meta( $zur_post_id, ZURCF7_META_PREFIX.'form_title', $form_title, true);
								add_post_meta( $zur_post_id, ZURCF7_META_PREFIX.'user_login', wp_slash($user_fname), true);
								add_post_meta( $zur_post_id, ZURCF7_META_PREFIX.'user_pass', wp_hash_password($user_pwd), true);
								add_post_meta( $zur_post_id, ZURCF7_META_PREFIX.'user_email', wp_slash($user_email), true);
								add_post_meta( $zur_post_id, ZURCF7_META_PREFIX.'role', $zurcf7_userrole_field, true);
								add_post_meta( $zur_post_id, ZURCF7_META_PREFIX.'user_status', 1, true);
								
								//ACF field add_post_meta
								if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) || is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
									$this->zurcf7_acf_save_user_meta($user_id,$zur_post_id,$form_id,$data);
								}
								$_SESSION[ ZURCF7_META_PREFIX . 'user_registered' . $form_id ] = "User registered successfully.";
								$this->zurcf7_custom_logs("User created successfully. Email ID:".$user_email);
							}
						}else{
							//Email id already exists
							$abort = true;
							$object->set_response("This user already exists. Please enter another email.",'zeal-user-reg-cf7');
							$this->zurcf7_custom_logs("This user already exists. Please enter another email");
						}
					}
				}else{
					//Email id already exists
					$abort = true;
					$object->set_response("Please enter valid email. Or contact administrator for the same.",'zeal-user-reg-cf7');
					$this->zurcf7_custom_logs("Invalid email :".$user_email);
				}
			}
			//check for skip email
			add_filter( 'wpcf7_skip_mail', array( $this, 'zurcf7_filter__wpcf7_skip_mail' ), 20 );
			return $contact_form;
		}

		/**
		 * [zurcf7_filter__wpcf7_skip_mail Skip Mail]
		 * @param  [type] $bool [description]
		 * @return [type]       [description]
		 */
		function zurcf7_filter__wpcf7_skip_mail( $bool ) {
			$zurcf7_skipcf7_email = (get_option( 'zurcf7_skipcf7_email')) ? get_option( 'zurcf7_skipcf7_email') : "";
			if($zurcf7_skipcf7_email == 1){
				return true;
			}
		}

		/**
		 * [zurcf7_insert_post_title Insert post]
		 * @param  [array] $form [Form data]
		 * @return [int]       [postid]
		 */
		function zurcf7_insert_post_title($form, $userid){

			$data = $form->get_posted_data();
			$current_form_id = WPCF7_ContactForm::get_current();

			$contactform = WPCF7_ContactForm::get_instance( $current_form_id->id() );
			$form_fields = $contactform->scan_form_tags();

			$title_count = 0;
			foreach ($form_fields as $key) {
				if($key['basetype'] == 'email' && $title_count == 0){
					$title = $key['name'];
					$title_count = 1;
				}
			}
			$final_post_title = $data[$title];
			$zurcf_post_id = wp_insert_post( array (
				'post_type'      => ZURCF7_POST_TYPE,
				'post_title'     => $final_post_title, // email
				'post_status'    => 'publish',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
				'post_author'    => $userid,
			) );
			$this->zurcf7_custom_logs("Post successfully created:".$zurcf_post_id);
			return $zurcf_post_id;
		}


		/**
		 * Filter: Modify the contact form 7 response.
		 *
		 * @method filter__zurcf7_ajax_json_echo
		 *
		 * @param  array $response
		 * @param  array $result
		 *
		 * @return array
		 */
		function filter__zurcf7_ajax_json_echo( $response, $result ) {

			if (
				array_key_exists( 'contact_form_id' , $result )
				&& array_key_exists( 'status' , $result )
				&& !empty( $result[ 'contact_form_id' ] )
				&& !empty( $_SESSION[ ZURCF7_META_PREFIX . 'user_registered' . $result[ 'contact_form_id' ] ] )
				&& $result[ 'status' ] == 'mail_sent'
			) {
				$redirection_url = isset( $_SESSION[ ZURCF7_META_PREFIX . 'user_registered' . $result[ 'contact_form_id' ] ] ) ? $_SESSION[ ZURCF7_META_PREFIX . 'user_registered' . $result[ 'contact_form_id' ] ] : '';
				$response[ 'redirection_url' ] = $redirection_url;
				$response[ 'message' ] = __( 'You are registered successfully.', 'zeal-user-reg-cf7' );
				unset( $_SESSION[ ZURCF7_META_PREFIX . 'user_registered' . $result[ 'contact_form_id' ] ] );
			}
			return $response;
		}


		/**
		 * [zurcf7_custom_logs Custom Log.]
		 * @param  [string] $message [Error Log Message]
		 * @return [string]          [description]
		 */
		function zurcf7_custom_logs($message) {
			$zurcf7_debug_mode_status = (get_option( 'zurcf7_debug_mode_status')) ? get_option( 'zurcf7_debug_mode_status') : "";
			if($zurcf7_debug_mode_status){


				if(is_array($message)) {
					$message = json_encode($message);
				}

				$upload = wp_upload_dir();
				$log_filename = $upload['basedir']."/zurcf7-log";
				if (!file_exists($log_filename))
				{
					// create directory/folder uploads.
					mkdir($log_filename, 0777, true);
				}
				$log_file_data = $log_filename.'/zurcf7-log-' . date('d-M-Y') . '.log';

				file_put_contents($log_file_data, $message . "\n", FILE_APPEND);
			}
		}

		/**
		 * ACF Field Mapping Save Data
		 * @param  [type] $bool [Boolean]
		 * @return [type] $bool [Boolean]
		 */
		function zurcf7_acf_save_user_meta($user_id, $post_id,$form_id, $formdata) {
			if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) || is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
				$returnfieldarr_user = zurcf7_ACF_filter_array_function();
				if(!empty($returnfieldarr_user)){
					foreach ($returnfieldarr_user['response'] as $value) { 
						$field_name = $value['field_name'];
						$acf_field_name = (get_option($field_name)) ? get_option($field_name) : get_option($field_name,"");
						foreach ($formdata as $key => $form_values) {
							if($key === $acf_field_name){
								update_user_meta( $user_id, $field_name, $form_values);
								update_post_meta( $post_id, $field_name,$form_values);
							}
						}			
					}
				}
			}
		}
	}

	add_action( 'plugins_loaded', function() {
		ZURCF7()->front->action = new ZURCF7_Front_Action;
	} );
}
