<?php
/**
 * Initial File for Firebase Authentication plugin.
 *
 * @link              https://miniorange.com
 * @since             1.0.0
 * @package           Firebase_Authentication
 *
 * @wordpress-plugin
 * Plugin Name:       Firebase Authentication
 * Plugin URI:        firebase-authentication
 * Description:       This plugin allows login into WordPress using Firebase as Identity provider.
 * Version:           1.6.5
 * Author:            miniOrange
 * Author URI:        https://miniorange.com
 * License:           MIT/Expat
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MO_FIREBASE_AUTHENTICATION_VERSION', '1.6.5' );
define( 'MO_FIREBASE_AUTHENTICATION_DIR', plugin_dir_path( __FILE__ ) );
define( 'MO_FIREBASE_AUTHENTICATION_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mo-firebase-authentication-deactivator.php
 */
function mo_firebase_deactivate_firebase_authentication() {
	require_once MO_FIREBASE_AUTHENTICATION_DIR . 'includes' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-deactivator.php';
	MO_Firebase_Authentication_Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, 'mo_firebase_deactivate_firebase_authentication' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require MO_FIREBASE_AUTHENTICATION_DIR . 'includes' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication.php';
require_once 'class-mo-firebase-config.php';
require 'views' . DIRECTORY_SEPARATOR . 'feedback-form.php';
require 'class-mo-firebase-contact-us.php';
require 'admin' . DIRECTORY_SEPARATOR . 'class-mo-firebase-customer.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function mo_firebase_run_firebase_authentication() {

	$plugin = new MO_Firebase_Authentication();
	$plugin->run();

}
mo_firebase_run_firebase_authentication();

/**
 * Check if the customer key exists.
 */
function mo_firebase_authentication_is_customer_registered() {
	$email        = get_option( 'mo_firebase_authentication_admin_email' );
	$customer_key = get_option( 'mo_firebase_authentication_admin_customer_key' );
	if ( ! $email || ! $customer_key || ! is_numeric( trim( $customer_key ) ) ) {
		return 0;
	} else {
		return 1;
	}
}
/**
 * Check is license key verified
 */
function mo_firebase_authentication_is_clv() {
	$license_key = get_option( 'mo_firebase_authentication_lk' );
	$isverified  = get_option( 'mo_firebase_authentication_lv' );
	if ( $isverified ) {
		$isverified = mo_firebase_authentication_decrypt( $isverified );
	}

	if ( ! empty( $license_key ) && 'true' === $isverified ) {
		return 1;
	}
	return 0;
}
/**
 * Encryption for license key
 *
 * @param string $str .
 */
function mo_firebase_authentication_encrypt( $str ) {
	$pass = get_option( 'mo_firebase_authentication_customer_token' );
	$pass = str_split( str_pad( '', strlen( $str ), $pass, STR_PAD_RIGHT ) );
	$stra = str_split( $str );
	foreach ( $stra as $k => $v ) {
		$tmp        = ord( $v ) + ord( $pass[ $k ] );
		$stra[ $k ] = chr( $tmp > 255 ? ( $tmp - 256 ) : $tmp );
	}
	return base64_encode( join( '', $stra ) ); //phpcs:ignore -- ignoring DiscouragedPHPFunctions warning as this line of code is used for a valid code consisting license key encryption.
}
/**
 * Decryption for license key
 *
 * @param string $str .
 */
function mo_firebase_authentication_decrypt( $str ) {
	$str  = base64_decode( $str ); //phpcs:ignore -- ignoring DiscouragedPHPFunctions warning as this line of code is used for a valid code consisting license key ncryption.
	$pass = get_option( 'mo_firebase_authentication_customer_token' );
	$pass = str_split( str_pad( '', strlen( $str ), $pass, STR_PAD_RIGHT ) );
	$stra = str_split( $str );
	foreach ( $stra as $k => $v ) {
		$tmp        = ord( $v ) - ord( $pass[ $k ] );
		$stra[ $k ] = chr( $tmp < 0 ? ( $tmp + 256 ) : $tmp );
	}
	return join( '', $stra );
}

/**
 * Firebase Authentication Main Class
 */
class Miniorange_Firebase_Authentication {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'post_register' ) );
		add_action( 'admin_init', array( $this, 'mo_firebase_auth_admin_forms_handler' ) );
		if ( 1 === (int) get_option( 'mo_enable_firebase_auth' ) ) {
			if ( strpos( ( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ), '/wp-json' ) === false ) {
				remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
				remove_filter( 'authenticate', 'wp_authenticate_email_password', 20, 3 );
				add_filter( 'authenticate', array( $this, 'mo_firebase_auth' ), 0, 3 );
			}
		}
		remove_action( 'admin_notices', array( $this, 'mo_firebase_auth_success_message' ) );
		remove_action( 'admin_notices', array( $this, 'mo_firebase_auth_error_message' ) );
		add_action( 'admin_footer', array( $this, 'mo_firebase_auth_feedback_request' ) );
		update_option( 'mo_fb_host_name', 'https://login.xecurify.com' );
	}

	/**
	 * Save details after customer registration
	 */
	public function post_register() {
		if ( isset( $_POST['verify_user'] ) && isset( $_REQUEST['page'] ) && sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) === 'mo_firebase_authentication' && wp_verify_nonce( isset( $_REQUEST['mo_firebase_auth_config_field'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['mo_firebase_auth_config_field'] ) ) : '', 'mo_firebase_auth_config_form' ) ) {

			if ( current_user_can( 'administrator' ) ) {
				update_option( 'mo_firebase_auth_disable_wordpress_login', isset( $_POST['disable_wordpress_login'] ) ? (int) filter_var( wp_unslash( $_POST['disable_wordpress_login'] ), FILTER_SANITIZE_NUMBER_INT ) : 0 );

				update_option( 'mo_firebase_auth_enable_admin_wp_login', isset( $_POST['mo_firebase_auth_enable_admin_wp_login'] ) ? (int) filter_var( wp_unslash( $_POST['mo_firebase_auth_enable_admin_wp_login'] ), FILTER_SANITIZE_NUMBER_INT ) : 0 );

				$project_id = isset( $_POST['projectid'] ) ? sanitize_text_field( wp_unslash( $_POST['projectid'] ) ) : '';
				update_option( 'mo_firebase_auth_project_id', $project_id );

				$api_key = isset( $_POST['apikey'] ) ? sanitize_text_field( wp_unslash( $_POST['apikey'] ) ) : '';
				update_option( 'mo_firebase_auth_api_key', $api_key );

				$this->mo_firebase_auth_store_certificates();
				update_option( 'mo_firebase_auth_message', 'Configurations saved successfully. Please <a href="' . admin_url( 'admin.php?page=mo_firebase_authentication&tab=config#test_authentication' ) . '">Test Authentication</a> before trying to Login.' );
				$this->mo_firebase_auth_show_success_message();
			}
		}
	}
	/**
	 * Store certificates.
	 */
	public function mo_firebase_auth_store_certificates() {
		$response = wp_remote_get( 'https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com' );
		if ( is_array( $response ) ) {
			$header = $response['headers']; // array of http header lines.
			$body   = $response['body']; // use the content.

			$split_result = explode( ':', $body );
			$count        = count( $split_result );
			$kid1         = substr( $split_result[0], 5, 40 );
			$s            = explode( ',', $split_result[1] );
			$c1           = substr( $s[0], 2, 1158 );
			$c1           = str_replace( '\n', '', $c1 );
			update_option( 'mo_firebase_auth_kid1', $kid1 );
			update_option( 'mo_firebase_auth_cert1', $c1 );
			if ( 3 === $count ) {
				$kid2  = substr( $s[1], 4, 40 );
				$c2    = explode( '}', $split_result[2] );
				$c2[0] = substr( $c2[0], 2, 1158 );
				$c2[0] = str_replace( '\n', '', $c2[0] );
				update_option( 'mo_firebase_auth_kid2', $kid2 );
				update_option( 'mo_firebase_auth_cert2', $c2[0] );
			} elseif ( $count > 3 ) {
				$kid2  = substr( $s[1], 4, 40 );
				$s2    = explode( ',', $split_result[2] );
				$c2    = substr( $s2[0], 2, 1158 );
				$kid3  = substr( $s2[1], 4, 40 );
				$c3    = explode( '}', $split_result[3] );
				$c3[0] = substr( $c3[0], 2, 1158 );
				$c2    = str_replace( '\n', '', $c2 );
				update_option( 'mo_firebase_auth_kid2', $kid2 );
				update_option( 'mo_firebase_auth_cert2', $c2 );
				$c3[0] = str_replace( '\n', '', $c3[0] );
				update_option( 'mo_firebase_auth_kid3', $kid3 );
				update_option( 'mo_firebase_auth_cert3', $c3[0] );
			}
		} else {
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				echo 'Something went wrong: ' . esc_attr( $error_message );
				exit();
			}
		}
	}
	/**
	 * Handler function
	 *
	 * @param array  $errors .
	 * @param string $redirect_to .
	 */
	public function mo_fb_clear_wp_login_errors( $errors, $redirect_to ) {
		return new WP_Error();
	}

	/**
	 * Firebase Authentication Login Handler
	 *
	 * @param WP_User/WP_Error $default_user .
	 * @param string           $username .
	 * @param string           $password .
	 */
	public function mo_firebase_auth( $default_user, $username, $password ) {

		if ( 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : '' ) ) {
			add_filter( 'wp_login_errors', array( $this, 'mo_fb_clear_wp_login_errors' ), 0, 2 );
			return $default_user;
		}

		if ( empty( $username ) || empty( $password ) ) {

			$error = new WP_Error();
			// create new error object and add errors to it.

			if ( empty( $username ) ) { // No email.
				$error->add( 'empty_username', __( '<strong>ERROR</strong>: Email field is empty.' ) );
			} elseif ( empty( $password ) ) { // No password.
				$error->add( 'empty_password', __( '<strong>ERROR</strong>: Password field is empty.' ) );
			}
			return $error;
		}

		$mo_firebase_config_obj = new Mo_Firebase_Config();
		$fb_user                = $mo_firebase_config_obj->mo_firebase_authenticate_call( $username, $password );
		$fb_user                = json_decode( $fb_user, true );

		if ( isset( $fb_user['idToken'] ) ) {
			$response = $mo_firebase_config_obj->mo_fb_login_user( $fb_user['idToken'] );
		} else {

			$error_message = $fb_user['error']['message'];

			if ( 'INVALID_EMAIL' === $error_message || 'EMAIL_NOT_FOUND' === $error_message ) {
				if ( '0' === get_option( 'mo_firebase_auth_disable_wordpress_login' ) ) {
					$user = get_user_by( 'login', $username );
					if ( ! $user ) {
						$user = get_user_by( 'email', $username );
					}
					if ( $user && wp_check_password( $password, $user->data->user_pass, $user->ID ) ) {
						return $user;
					}
				} elseif ( get_option( 'mo_firebase_auth_enable_admin_wp_login' ) ) {
					$user = get_user_by( 'login', $username );
					if ( ! $user ) {
							$user = get_user_by( 'email', $username );
					}
					if ( $user && $this->is_administrator_user( $user ) ) {

						if ( wp_check_password( $password, $user->data->user_pass, $user->ID ) ) {
							return $user;
						}
					}
				}
			} else {
				$error = new WP_Error();
				if ( 'INVALID_PASSWORD' === $error_message ) {
					$error_message = 'The password is invalid or the user does not have a password.';
				}
				$error_message = '<strong>ERROR</strong>: ' . $error_message;
				$error->add( 'firebase_error', __( $error_message ) ); //phpcs:ignore -- Ignoring as it expects a single string literal and not a string variable.
				return $error;
			}
		}
		return $default_user;
	}

	/**
	 * Admin dashboard messages
	 */
	public function mo_firebase_auth_success_message() {
		$message      = "<div class='error'><p>" . get_option( 'mo_firebase_auth_message' ) . '</p></div>';
		$allowed_tags = array(
			'div' => array(
				'class' => array(),
			),
			'a'   => array(
				'href' => array(),
			),
		);
		echo wp_kses( $message, $allowed_tags );
	}

	/**
	 * Admin dashboard messages
	 */
	public function mo_firebase_auth_error_message() {
		$message      = "<div class='updated'><p>" . get_option( 'mo_firebase_auth_message' ) . '</p></div>';
		$allowed_tags = array(
			'div' => array(
				'class' => array(),
			),
			'a'   => array(
				'href' => array(),
			),
		);
		echo wp_kses( $message, $allowed_tags );
	}

	/**
	 * Check for admin user
	 *
	 * @param WP_User $user .
	 */
	public function is_administrator_user( $user ) {
		$user_role = ( $user->roles );
		if ( ! is_null( $user_role ) && in_array( 'administrator', $user_role, true ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Admin dashboard messages
	 */
	private function mo_firebase_auth_show_success_message() {
		remove_action( 'admin_notices', array( $this, 'mo_firebase_auth_success_message' ) );
		add_action( 'admin_notices', array( $this, 'mo_firebase_auth_error_message' ) );
	}
	/**
	 * Admin dashboard messages
	 */
	private function mo_firebase_auth_show_error_message() {
		remove_action( 'admin_notices', array( $this, 'mo_firebase_auth_error_message' ) );
		add_action( 'admin_notices', array( $this, 'mo_firebase_auth_success_message' ) );
	}
	/**
	 * Admin dashboard feedback form on deactivation
	 */
	public function mo_firebase_auth_feedback_request() {
		mo_firebase_auth_display_feedback_form();
	}

	/**
	 * Function to check the validations
	 *
	 * @param string $value .
	 */
	private function mo_firebase_authentication_check_empty_or_null( $value ) {
		if ( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}
	/**
	 * Function for backend of admin forms such as contact us, feedback, login, etc.
	 */
	public function mo_firebase_auth_admin_forms_handler() {

		if ( isset( $_POST['option'] ) ) {

			if ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo_firebase_authentication_change_email' && isset( $_REQUEST['mo_firebase_authentication_change_email_form_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_firebase_authentication_change_email_form_nonce'] ) ), 'mo_firebase_authentication_change_email_form' ) ) {
				// Adding back button.
				update_option( 'mo_firebase_authentication_verify_customer', '' );
				update_option( 'mo_firebase_authentication_registration_status', '' );
				update_option( 'mo_firebase_authentication_new_registration', 'true' );
			}

			if ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'change_miniorange' && isset( $_REQUEST['change_miniorange_form_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['change_miniorange_form_nonce'] ) ), 'change_miniorange_form' ) ) {
				require_once MO_FIREBASE_AUTHENTICATION_DIR . 'includes' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-deactivator.php';
				MO_Firebase_Authentication_Deactivator::deactivate();
				return;
			}

			if ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo_firebase_authentication_register_customer' && isset( $_REQUEST['mo_fb_register_form_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_fb_register_form_nonce'] ) ), 'mo_fb_register_form' ) ) { // register the admin to miniOrange
				// validation and sanitization.
				$email            = '';
				$phone            = '';
				$password         = isset( $_POST['password'] ) ? stripslashes( $_POST['password'] ) : ''; //phpcs:ignore -- Ignoring sanitization for password input in case of special characters.
				$confirm_password = isset( $_POST['confirmPassword'] ) ? stripslashes( $_POST['confirmPassword'] ) : ''; //phpcs:ignore -- Ignoring sanitization for password input in case of special characters.
				$fname            = '';
				$lname            = '';
				$company          = '';
				if ( ! ( isset( $_POST['email'] ) && isset( $_POST['password'] ) && isset( $_POST['confirmPassword'] ) ) ) {
					update_option( 'mo_firebase_auth_message', 'All the fields are required. Please enter valid entries.' );
					$this->mo_firebase_auth_show_error_message();
					return;
				} elseif ( strlen( $password ) < 8 || strlen( $confirm_password ) < 8 ) {
					update_option( 'mo_firebase_auth_message', 'Choose a password with minimum length 8.' );
					$this->mo_firebase_auth_show_error_message();
					return;
				} else {
					$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
					$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
					$fname   = isset( $_POST['fname'] ) ? sanitize_text_field( wp_unslash( $_POST['fname'] ) ) : '';
					$lname   = isset( $_POST['lname'] ) ? sanitize_text_field( wp_unslash( $_POST['lname'] ) ) : '';
					$company = isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '';
				}

				update_option( 'mo_firebase_authentication_admin_email', $email );
				update_option( 'mo_firebase_authentication_admin_phone', $phone );
				update_option( 'mo_firebase_authentication_admin_fname', $fname );
				update_option( 'mo_firebase_authentication_admin_lname', $lname );
				update_option( 'mo_firebase_authentication_admin_company', $company );

				if ( 0 === strcmp( $password, $confirm_password ) ) {
					update_option( 'password', $password );
					$customer = new MO_Firebase_Customer();
					$email    = get_option( 'mo_firebase_authentication_admin_email' );
					$content  = json_decode( $customer->check_customer(), true );

					if ( 0 === strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND' ) ) {
						$response = json_decode( $customer->create_customer(), true );
						if ( strcasecmp( $response['status'], 'SUCCESS' ) !== 0 ) {
							update_option( 'mo_firebase_auth_message', 'Failed to create customer. Try again.' );
							$this->mo_firebase_auth_show_error_message();
						} else {
							update_option( 'mo_firebase_auth_message', 'Your registration is successful. Please login.' );
							$this->mo_firebase_auth_show_success_message();
						}
					} elseif ( 0 === strcasecmp( $content['status'], 'SUCCESS' ) ) {
						update_option( 'mo_firebase_auth_message', 'Account already exist. Please Login.' );
						$this->mo_firebase_auth_show_error_message();
					} else {
						update_option( 'mo_firebase_auth_message', $content['status'] );
						$this->mo_firebase_auth_show_success_message();
					}
				} else {
					update_option( 'mo_firebase_auth_message', 'Passwords do not match.' );
					delete_option( 'mo_firebase_authentication_verify_customer' );
					$this->mo_firebase_auth_show_error_message();
				}
			} if ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo_firebase_authentication_goto_login' && isset( $_REQUEST['mo_firebase_authentication_goto_login_form_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mo_firebase_authentication_goto_login_form_field'] ) ), 'mo_firebase_authentication_goto_login_form' ) ) {
				delete_option( 'mo_firebase_authentication_new_registration' );
				update_option( 'mo_firebase_authentication_verify_customer', 'true' );

			} if ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo_enable_firebase_auth' && wp_verify_nonce( ( isset( $_REQUEST['mo_firebase_auth_enable_field'] ) ? sanitize_key( wp_unslash( $_REQUEST['mo_firebase_auth_enable_field'] ) ) : '' ), 'mo_firebase_auth_enable_form' ) ) {
				update_option( 'mo_enable_firebase_auth', isset( $_POST['mo_enable_firebase_auth'] ) ? (int) filter_var( wp_unslash( $_POST['mo_enable_firebase_auth'] ), FILTER_SANITIZE_NUMBER_INT ) : 0 );

			} elseif ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo_firebase_auth_contact_us' && isset( $_REQUEST['mo_firebase_auth_contact_us_field'] ) && wp_verify_nonce( ( isset( $_REQUEST['mo_firebase_auth_contact_us_field'] ) ? sanitize_key( wp_unslash( $_REQUEST['mo_firebase_auth_contact_us_field'] ) ) : '' ), 'mo_firebase_auth_contact_us_form' ) ) {
				$email = isset( $_POST['mo_firebase_auth_contact_us_email'] ) ? sanitize_email( wp_unslash( $_POST['mo_firebase_auth_contact_us_email'] ) ) : '';
				$phone = isset( $_POST['mo_firebase_auth_contact_us_phone'] ) ? '+ ' . preg_replace( '/[^0-9]/', '', sanitize_text_field( wp_unslash( $_POST['mo_firebase_auth_contact_us_phone'] ) ) ) : '';
				$query = isset( $_POST['mo_firebase_auth_contact_us_query'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mo_firebase_auth_contact_us_query'] ) ) : '';
				if ( $this->mo_firebase_authentication_check_empty_or_null( $email ) || $this->mo_firebase_authentication_check_empty_or_null( $query ) ) {
					echo '<br><b style=color:red>Please fill up Email and Query fields to submit your query.</b>';
				} else {
					$contact_us = new MO_Firebase_contact_us();
					$submited   = $contact_us->mo_firebase_auth_contact_us( $email, $phone, $query );
					if ( false === $submited ) {
						update_option( 'mo_firebase_auth_message', 'Your query could not be submitted. Please try again.' );
						$this->mo_firebase_auth_show_error_message();
					} else {
						update_option( 'mo_firebase_auth_message', 'Thanks for getting in touch! We shall get back to you shortly.' );
						$this->mo_firebase_auth_show_success_message();
					}
				}
			} elseif ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo_firebase_authentication_verify_customer' && isset( $_REQUEST['mo_fb_login_form_nonce'] ) && wp_verify_nonce( ( isset( $_REQUEST['mo_fb_login_form_nonce'] ) ? sanitize_key( wp_unslash( $_REQUEST['mo_fb_login_form_nonce'] ) ) : '' ), 'mo_fb_login_form' ) ) {// register the admin to miniOrange
				// validation and sanitization.
				$email    = '';
				$password = '';
				if ( ! ( isset( $_POST['email'] ) && isset( $_POST['password'] ) ) ) {
					update_option( 'mo_firebase_auth_message', 'All the fields are required. Please enter valid entries.' );
					$this->mo_firebase_auth_show_error_message();
					return;
				} else {
					$email    = sanitize_email( wp_unslash( $_POST['email'] ) );
					$password = stripslashes( wp_unslash( $_POST['password'] ) ); //phpcs:ignore -- Ignoring sanitization for password input in case of special characters.
				}

				update_option( 'mo_firebase_authentication_admin_email', $email );
				update_option( 'password', $password );
				$customer     = new MO_Firebase_Customer();
				$content      = $customer->mo_firebase_auth_get_customer_key();
				$customer_key = json_decode( $content, true );
				if ( json_last_error() === JSON_ERROR_NONE ) {
					update_option( 'mo_firebase_authentication_admin_customer_key', $customer_key['id'] );
					update_option( 'mo_firebase_authentication_admin_api_key', $customer_key['apiKey'] );
					update_option( 'mo_firebase_authentication_customer_token', $customer_key['token'] );
					if ( isset( $customer_key['phone'] ) ) {
						update_option( 'mo_firebase_authentication_admin_phone', $customer_key['phone'] );
					}
					delete_option( 'password' );
					update_option( 'mo_firebase_auth_message', 'Customer retrieved successfully' );
					delete_option( 'mo_firebase_authentication_verify_customer' );
					$this->mo_firebase_auth_show_success_message();
				} else {
					update_option( 'mo_firebase_auth_message', 'Invalid username or password. Please try again.' );
					$this->mo_firebase_auth_show_error_message();
				}
			} elseif ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo_firebase_auth_skip_feedback' && isset( $_REQUEST['mo_firebase_auth_skip_feedback_form_nonce'] ) && wp_verify_nonce( ( isset( $_REQUEST['mo_firebase_auth_skip_feedback_form_nonce'] ) ? sanitize_key( wp_unslash( $_REQUEST['mo_firebase_auth_skip_feedback_form_nonce'] ) ) : '' ), 'mo_firebase_auth_skip_feedback_form' ) ) {
				deactivate_plugins( __FILE__ );
				update_option( 'mo_firebase_auth_message', 'Plugin deactivated successfully' );
				$this->mo_firebase_auth_show_success_message();

			} elseif ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo_firebase_auth_feedback' && isset( $_REQUEST['mo_firebase_auth_feedback_field'] ) && wp_verify_nonce( ( isset( $_REQUEST['mo_firebase_auth_feedback_field'] ) ? sanitize_key( wp_unslash( $_REQUEST['mo_firebase_auth_feedback_field'] ) ) : '' ), 'mo_firebase_auth_feedback_form' ) ) {
				$user                      = wp_get_current_user();
				$message                   = 'Plugin Deactivated:';
				$deactivate_reason         = array_key_exists( 'deactivate_reason_radio', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['deactivate_reason_radio'] ) ) : false;
				$deactivate_reason_message = array_key_exists( 'query_feedback', $_POST ) ? sanitize_textarea_field( wp_unslash( $_POST['query_feedback'] ) ) : false;
				if ( $deactivate_reason ) {
					$message .= $deactivate_reason;
					if ( isset( $deactivate_reason_message ) ) {
						$message .= ':' . $deactivate_reason_message;
					}

					$email      = $user->user_email;
					$contact_us = new MO_Firebase_contact_us();
					$submited   = json_decode( $contact_us->mo_firebase_auth_send_email_alert( $email, $message, 'Feedback: WordPress Firebase Authentication' ), true );
					deactivate_plugins( __FILE__ );
					update_option( 'mo_firebase_auth_message', 'Thank you for the feedback.' );
					$this->mo_firebase_auth_show_success_message();

				} else {
					update_option( 'mo_firebase_auth_message', 'Please Select one of the reasons ,if your reason is not mentioned please select Other Reasons' );
					$this->mo_firebase_auth_show_error_message();
				}
			} elseif ( sanitize_text_field( wp_unslash( $_POST['option'] ) ) === 'mo_fb_demo_request_form' && isset( $_REQUEST['mo_fb_demo_request_field'] ) && wp_verify_nonce( ( isset( $_REQUEST['mo_fb_demo_request_field'] ) ? sanitize_key( wp_unslash( $_REQUEST['mo_fb_demo_request_field'] ) ) : '' ), 'mo_fb_demo_request_form' ) ) {

				if ( current_user_can( 'administrator' ) ) {
					$email           = isset( $_POST['mo_auto_create_demosite_email'] ) ? sanitize_email( wp_unslash( $_POST['mo_auto_create_demosite_email'] ) ) : '';
					$demo_plan       = isset( $_POST['mo_auto_create_demosite_demo_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_auto_create_demosite_demo_plan'] ) ) : ' ';
					$firestore_check = isset( $_POST['mo_auto_create_demosite_firestore_integrator_check'] ) ? sanitize_text_field( wp_unslash( $_POST['mo_auto_create_demosite_firestore_integrator_check'] ) ) : '';
					$query           = isset( $_POST['mo_auto_create_demosite_usecase'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mo_auto_create_demosite_usecase'] ) ) : '';

					if ( $this->mo_firebase_authentication_check_empty_or_null( $email ) || $this->mo_firebase_authentication_check_empty_or_null( $demo_plan ) || $this->mo_firebase_authentication_check_empty_or_null( $query ) ) {
						update_option( 'message', 'Please fill up Usecase, Email field and Requested demo plan to submit your query.' );
						$$this->mo_firebase_auth_show_error_message();
					} else {
						$url = 'https://demo.miniorange.com/wpoauthsso/';

						$headers = array(
							'Content-Type' => 'application/x-www-form-urlencoded',
							'charset'      => 'UTF - 8',
						);
						$args    = array(
							'method'      => 'POST',
							'body'        => array(
								'option' => 'mo_auto_create_demosite',
								'mo_auto_create_demosite_email' => $email,
								'mo_auto_create_demosite_usecase' => $query,
								'mo_auto_create_demosite_demo_plan' => $demo_plan,
								'mo_auto_create_demosite_firestore_integrator_check' => $firestore_check,
							),
							'timeout'     => '20',
							'redirection' => '5',
							'httpversion' => '1.0',
							'blocking'    => true,
							'headers'     => $headers,
						);

						$response = wp_remote_post( $url, $args );
						if ( is_wp_error( $response ) ) {
							$error_message = $response->get_error_message();

							echo 'Something went wrong: ' . esc_attr( $error_message );
							exit();
						}
						$output = wp_remote_retrieve_body( $response );
						$output = json_decode( $output );
						if ( is_null( $output ) ) {
							update_option( 'mo_firebase_auth_message', 'We were unable to setup the demo for you. Please try again or reach out to us at <a href="mailto:oauthsupport@xecurify.com">oauthsupport@xecurify.com</a>.' );
							$this->mo_firebase_auth_show_success_message();
						} else {
							if ( 'SUCCESS' === $output->status ) {
								update_option( 'mo_firebase_auth_message', $output->message );
								$this->mo_firebase_auth_show_success_message();
							} else {
								update_option( 'mo_firebase_auth_message', $output->message );
								$this->mo_firebase_auth_show_error_message();
							}
						}
					}
				}
			}
		}
	}

}

$mo_firebase_authentication_obj = new Miniorange_Firebase_Authentication();
