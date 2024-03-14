<?php

/**
 * Plugin Name: Face Authentication Login
 * Plugin URI: https://wpfacelogin.com/
 * Description: Enable login by face for WordPress
 * Author: WP Face Login
 * Version: 0.0.5
 * Author URI: https://wpfacelogin.com/
 * Text Domain: wp-face-login
 */

namespace DataPeen\FaceAuth;


define('TWO_FACTOR_DIR', plugin_dir_path(__FILE__));

include_once 'vendor/autoload.php';
include_once 'includes/bc/Options_Form.php';
include_once 'includes/bc/Options.php';
include_once 'includes/bc/Static_UI.php';
include_once 'includes/bc/Option_Names.php';
include_once 'includes/bc/Core.php';
include_once 'includes/bc/Config.php';
include_once 'includes/LoginPage.php';
include_once 'includes/Authenticator.php';
include_once 'includes/helpers/CodeMail.php';
include_once 'ui/Main.php';
include_once 'includes/helpers/UserOptions.php';
include_once 'includes/helpers/Flog.php';
include_once 'includes/helpers/Helpers.php';
include_once 'includes/EmailVerification.php';

use DataPeen\FaceAuth\Options_Form;
use DataPeen\FaceAuth\Options;
use DataPeen\FaceAuth\EmailVerification;
use DataPeen\FaceAuth\Flog;
use DataPeen\FaceAuth\Core as Core;
use DataPeen\FaceAuth\Config as Config;
use DataPeen\FaceAuth\UI\Main as Main;


use DataPeen\FaceAuth\LoginPage;

use GuzzleHttp\Client;

/**
 * Class Initiator
 * @package DataPeen\FaceAuth
 */
class Initiator {

	public function __construct()
	{
		//register the action to handle options form submit
		add_action('wp_ajax_'. Options_Form::AJAX_SAVE_FORM, array('DataPeen\FaceAuth\Options_Form', 'save_form_options'));

		//add menu, if not available
		add_action('admin_menu', array($this, 'add_to_menu'));


		//enqueue js and css
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin'));

		//only enqueue on ssl page and when site is verified
		//check if the site is authenticated
		if (is_ssl() && Helpers::is_site_verified())
			add_action('login_enqueue_scripts', array($this, 'enqueue_login_page'));

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );


		add_action('wp_ajax_datapeen_face_factor_recognize_face', array($this, 'face_factor_recognize'));
		add_action('wp_ajax_nopriv_datapeen_face_factor_recognize_face', array($this, 'face_factor_recognize'));


		add_action('wp_ajax_datapeen_face_factor_verify_authenticator_pin', array($this, 'face_factor_verify_authenticator_pin' ));
		add_action('wp_ajax_nopriv_datapeen_face_factor_verify_authenticator_pin', array($this, 'face_factor_verify_authenticator_pin'));

		add_action('wp_ajax_datapeen_face_factor_verify_email_pin', array($this, 'face_factor_verify_email_pin' ));
		add_action('wp_ajax_nopriv_datapeen_face_factor_verify_email_pin', array($this, 'face_factor_verify_email_pin'));

		add_filter('login_body_class', array($this, 'add_login_class'));

		//verify auth code by user input to enable authenticator
		add_action('wp_ajax_verify_google_authenticator_method', array($this, 'verify_google_authenticator_method'));


		//verify token
		add_action('wp_ajax_datapeen_face_factor_verify_token', array($this, 'face_factor_verify_token'));

        //append the verify token to site, if available and if the site is not verified
        add_action('wp_head', array($this, 'face_factor_add_token_to_head'));

        add_action('wp_ajax_datapeen_ff_add_new_face', array($this, 'datapeen_ff_add_new_face'));
        add_action('wp_ajax_datapeen_ff_remove_face', array($this, 'datapeen_ff_remove_face'));

        add_action('wp_ajax_datapeen_ff_get_faces', array($this, 'datapeen_ff_get_faces'));

	}


	public function datapeen_ff_get_faces()
	{

		$common_option = Options::get_the_only_option(Config::COMMON_OPTION_NAME);

		$site_verified  = Helpers::is_site_verified();

		//If site is not verified, stop. No face to get on an non-verified site
		if (!$site_verified)
		{
			wp_send_json(array(
				'status' => 'failed',
				'data' => array(
					'reason' => __('Site is not verified')
				)
			));

			die();
		}

		$user_login = wp_get_current_user()->user_login;


		$token = $common_option->get_string(Option_Names::SECRET_TOKEN, '');

		$client = new Client([
			// Base URI is used with relative requests
			'base_uri' => Config::GET_FACES_URL,
			// You can set any number of default request options.
			'timeout'  => 20.0,
		]);
		$response = $client->request('POST', Config::GET_FACES_URL, [
			'form_params' => [
				'secret_token' => $token,
				'wp_username' => $user_login
			]
		]);

		$body = $response->getBody()->getContents();


		$response_data = json_decode($body);

		if ($response_data->data->code != "00000200")
		{
//			Helpers::deauthorize_site();
			wp_send_json(array(
				"status" => "Failed",
				"data" => array(
					"images" => null,
					"reason" => "Your token is invalid. Maybe your site was removed? " .  $response_data->data->reason,
					"code" => $response_data->data->code
				)
			));
		}






		wp_send_json($response_data);

		die();


	}

	public function datapeen_ff_remove_face()
	{
		//image_id is a string (uuid) passed by datapeen.com
		$image_id = sanitize_text_field($_POST['image_id']);
		$user_login = wp_get_current_user()->user_login;

		$common_option = Options::get_the_only_option(Config::COMMON_OPTION_NAME);
		$token = $common_option->get_string(Option_Names::SECRET_TOKEN, '');

		$client = new Client([
			// Base URI is used with relative requests
			'base_uri' => Config::REMOVE_FACE_URL,
			// You can set any number of default request options.
			'timeout'  => 20.0,
		]);
		$response = $client->request('POST', Config::REMOVE_FACE_URL, [
			'form_params' => [
				'secret_token' => $token,
				'wp_username' => $user_login,
				'face_image_id' => $image_id
			]
		]);

		$body = $response->getBody()->getContents();

		/**
		 * {
		 *      status: 'success',
		 *      data: {
		 *              message: 'image removed'
		 *      }
		 * }
		 */
		wp_send_json($body);

		die();


	}

	public function datapeen_ff_add_new_face()
	{
		$image = sanitize_text_field($_POST['face_image']);

		$file_name =  wp_generate_uuid4() . '.jpg';

		$image_base64 = str_replace("data:image/jpeg;base64,", "", $image);
        
		$user_login = wp_get_current_user()->user_login;

        $image_base64_decode = base64_decode($image_base64);

		$common_option = Options::get_the_only_option(Config::COMMON_OPTION_NAME);
		$token = $common_option->get_string(Option_Names::SECRET_TOKEN, '');

		$client = new Client([
			// Base URI is used with relative requests
			'base_uri' => Config::ADD_FACE_URL,
			// You can set any number of default request options.
			'timeout'  => 20.0,
		]);

		$response = $client->request('POST', Config::ADD_FACE_URL, [

			'multipart' => [
				[
					'name'     => 'face_image',
					'contents' => $image_base64_decode,
					'filename' => $file_name
				],
				[
					'name'     => 'secret_token',
					'contents' => $token
				],
				[
					'name'     => 'wp_username',
					'contents' => $user_login
				]
			]
		]);


		$body = $response->getBody()->getContents();
		wp_send_json($body);

	}

	/**
	 * This function add a meta tag to the head of the site.
	 * The meta content contains the token to verify that the user actually owns the site
	 *
	 */
    public function face_factor_add_token_to_head()
    {
        $token = get_transient(Config::TRANSIENT_TOKEN);

        if ($token !== false )
        {
            echo sprintf('<meta name="datapeen-face-auth-verify-token" content="%1$s">', $token);

        }


    }

	/**
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function face_factor_verify_token()
	{
		//Only site admin can verify token
		if (!current_user_can('manage_options'))
		{
			wp_send_json(array(
				'status' => 'FAILED',
				'message' => 'You do not have rights to perform this'
			));

			die();
		}

		$client = new Client();

		$key = sanitize_text_field($_POST['verify_token']);

        /**
         * save the verify token to common options so it can be added to a meta tag in head. The token should be only available in a very short time
         *
         */

        set_transient(Config::TRANSIENT_TOKEN, $key, 300);



		$response = $client->request('POST', Config::SITE_VERIFY_URL, [
			'form_params' => [
				'verify_token' => $key,
				'site_url' => get_site_url()

			]
		]);


		$data = json_decode($response->getBody()->getContents());


		if ($data->status=== 'success')
		{
			$secret_token = $data->data->secret_token;

			$common_option = Options::get_the_only_option(Config::COMMON_OPTION_NAME);
			$common_option->set(Option_Names::SECRET_TOKEN, $secret_token);
			$common_option->set(Option_Names::SECRET_TOKEN_VERIFIED, true);

			$data = array(
				'status' => 'success',
				'message' => 'Site verified!'
			);

		} else
		{
			Helpers::deauthorize_site();

			$data = array(
				'status' => 'failed',
				'message' => __('Key not verified')
			);
		}
		wp_send_json($data);
		die();




	}

	public function verify_google_authenticator_method()
	{
		$auth_code = sanitize_text_field($_POST['auth_code']);
		$user_id = intval($_POST['user_id']);

		$option_name = Config::OPTION_NAME . wp_get_current_user()->user_login;
		//update authenticator verifycation result to not sohwing the number field
		$user_option = Options::get_the_only_option($option_name);

		$result = GoogleAuthenticator::verify_code($auth_code, $user_id);
		if ($result)
		{
			$data = array(
				'status' => 'OK',
				'message' => 'Authenticator code has been successfully verified!'
			);
			$user_option->set(Option_Names::AUTHENTICATOR_VERIFIED, true);
		} else {
			$data = array(
				'status' => 'FAILED',
				'message' => __('Your code is not valid. Please try again')
			);
			$user_option->set(Option_Names::AUTHENTICATOR_VERIFIED, false);
		}

		wp_send_json($data);

	}

	public function add_login_class($classes)
	{
		$classes[] = 'datapeen-face-factor';

		return $classes;
	}


	public function face_factor_verify_email_pin()
	{
		session_start();
		$pin = (strval(trim(($_POST['pin']))));

		$user = get_user_by('login', $_SESSION[Option_Names::SESSION_USERNAME]);

		$result = ($pin == get_transient($_SESSION[Option_Names::SESSION_KEY] . '__email'));

		//delete the transient if verification is success
		if ($result)
			delete_transient($_SESSION[Option_Names::SESSION_KEY] . '__email');

		$data = $this->log_user_in($result, $user);
		wp_send_json($data);
		die();
	}


	/**
	 * This function generates HTML code of step 3, step that logging in user enter verification code
	 * the function wp_get_current_user cannot be used here since there isn't any user logged in
	 *
	 * Instead, get user name from session since it was set in the previous step when verifying face
	 */
	private function generate_step_pin_entering_html($user)
	{
		$html = '';
		$user_options = UserOptions::get_option($user);
		$available_methods = $user_options->get_array(Option_Names::PIN_METHODS);

		if (count($available_methods) > 1)
		{
			$html .= '<p>Please select a method to verify your PIN</p>';

			$select = '<select class="dp-ff-select">';

			foreach ($available_methods as $method)
			{
				$select .= sprintf('<option value="%1$s">%2$s</option>', $method, ucwords(str_replace("_", " ", $method)));
			}

			$select .= '</select>';
		}


		$pin_entering = '';
		$counter = 0;
		foreach ($available_methods as $method)
		{
			//hide subsequent PIN method. Those only are displayed when the select changes to their method
			if ($counter > 0)
				$pin_entering .=sprintf('<div class="dp-ff-pin-input dp-ff-pin-%1$s " style="display: none;">', $method);
			else
				$pin_entering .=sprintf('<div class="dp-ff-pin-input dp-ff-pin-%1$s ">', $method);

			$pin_entering .= sprintf('<p>Enter the PIN you received below (via %1$s):</p>', ucwords(str_replace("_", " ", $method)));
			$pin_entering .= sprintf('<p><input class="input pin-input" data-method="%2$s" type="number" id="pin-%1$s" placeholder="PIN"></p>', $method, $method);

			$pin_entering .='</div>';
			$counter++;
		}


		$html .= $select . $pin_entering . '<button class="button-primary button" id="verify-pin-button">Verify PIN</button>';

		return $html;




	}

	public function face_factor_verify_authenticator_pin()
	{
		session_start();
		$pin = (strval(trim(($_POST['pin']))));

		if (!isset($_SESSION[Option_Names::SESSION_USERNAME]))
		{
			wp_send_json(array(
				'status' => 'FAILED',
				'message' => 'Invalid user'
			));

			die();
		}

		$user = get_user_by('login', $_SESSION[Option_Names::SESSION_USERNAME]);

		if ($user == false)
		{
			wp_send_json(array(
				'status' => 'FAILED',
				'message' => 'Invalid user 2'
			));

			die();
		}

		//perform PIN verification here
		$verification_result = GoogleAuthenticator::verify_code($pin, $user->ID);


		$data = $this->log_user_in($verification_result, $user);



		wp_send_json($data);

		die();
	}


	private function log_user_in($verification_result, $user)
	{
		if ($verification_result)
		{
			wp_set_current_user( $user->ID, $_SESSION[Option_Names::SESSION_USERNAME] );
			wp_set_auth_cookie( $user->ID , true);
			do_action( 'wp_login', $_SESSION[Option_Names::SESSION_USERNAME], $user );
			$data['ID'] = $user->ID;

			//if PIN OK, log the user in
			$data = array(
				'status' => 'OK',
				'URL' => get_dashboard_url()
			);

		} else
		{
			$data = array(
				'status' => 'FAILED',
				'message' => 'Invalid code'
			);

		}

		return $data;
	}

	/**
	 *
	 */
	public function face_factor_recognize()
	{
		//base64 image post from user camera

		$image = sanitize_text_field($_POST['face']);

		//get secret token
//		$secret_token = Options::get_the_only_option(Config::COMMON_OPTION_NAME)->get_string(Option_Names::SECRET_TOKEN);


		//create a session key to send along to server
		session_start();


		if (!isset($_SESSION[Option_Names::SESSION_KEY]))
			$_SESSION[Option_Names::SESSION_KEY] = wp_generate_uuid4();

		$session_key = $_SESSION[Option_Names::SESSION_KEY];

		$common_option = Options::get_the_only_option(Config::COMMON_OPTION_NAME);
		$secret_token = $common_option->get_string(Option_Names::SECRET_TOKEN, '');

		$file_name =  wp_generate_uuid4() . '.jpg';



		$image_base64 = str_replace("data:image/jpeg;base64,", "", $image);


		$image_base64_decode = base64_decode(trim($image_base64));


		try{
			set_time_limit(60);
			$client = new Client([
				// Base URI is used with relative requests
				'base_uri' => Config::FACE_RECOGNIZE_URL,
				// You can set any number of default request options.
				'timeout'  => 20.0,
			]);
			$res = $client->request('POST', Config::FACE_RECOGNIZE_URL, [

				'multipart' => [
					[
						'name'     => 'face_image',
						'contents' => $image_base64_decode,
						'filename' => $file_name
					],
					[
						'name'     => 'session_id',
						'contents' => $session_key
					],
					[
						'name'     => 'secret_token',
						'contents' => $secret_token
					]
				],
			]);

			$body = json_decode($res->getBody()->getContents());

			$username = $body->data->username;

			/**
			 * unset $body->data->username to hide username from user (avoid impersonation)
			 * Delete face file
			 *
             */

			unset($body->data->username);
			//verify if username exists
			$user = get_user_by('login', $username);

			if ($user)
			{
				/**
				 * Store username in $_SESSION to use in PIN verification
				 */

				//check if the email method is enabled then send the email with code to user
				try{
					if (Helpers::is_email_enabled($user))
					{
						Flog::write('email is enabled');
						//set the transient
						//when sending email, save the code to transient
						$code = EmailVerification::generateCode();
						set_transient($_SESSION[Option_Names::SESSION_KEY] . '__email' , $code, 300);

						EmailVerification::sendMail($user, $code);
					}
				} catch (\Exception $ex)
				{
					$body->data->email = 'Cannot send email to your address. Please check your email sending function';
					$body->data->email_error = $ex->getMessage();
					Flog::write($body->data->email_error = $ex->getMessage());
				}

				$_SESSION[Option_Names::SESSION_USERNAME] = $username;
				$body->data->full_name = $user->first_name . ' ' . $user->last_name;
				$body->data->html = $this->generate_step_pin_entering_html($user);
				wp_send_json($body);
			} else
			{
				$error_code = $body->data->code;
				$reason = __("Error recognizing your face");

				switch ($error_code)
				{
					case "10000404":
						$reason = __("Face image is not recognizable. Please try again.");
						break;
					case "10000403":
						$reason = __("Your face image does not match any in the database. Please try again");
						break;
					default:
						break;

				}
				wp_send_json(
					array(
						'status' => 'failed',
						'data' => array(
							'reason' => $reason
						)
					)
				);

			}




		} catch (\Exception $e)
		{
			wp_send_json(array(
				'status' => 'failed',
				'data' => array(
					'reason' => $e->getMessage()
				)
			));

			die();
		}



	}


	public function action_links($links)
	{
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page='. Config::SLUG ) . '">' . __( 'Get started', Config::TEXT_DOMAIN ) . '</a>';
		$custom_links[] = '<a target="_blank" href="https://datapeen.com/contact/">' . __( 'Supports', Config::TEXT_DOMAIN ) . '</a>';
		return array_merge( $custom_links, $links );
	}

	/**
	 * Register and enqueue frontend styles and scripts
	 *
	 *
	 */
	public function enqueue_login_page()
	{
		wp_register_style(Config::SLUG . '-frontend-style', plugins_url('bundle/css/frontend.css', __FILE__));
		wp_register_style(Config::SLUG . '-dashicon', includes_url() . 'css/dashicons.min.css', __FILE__);

		wp_enqueue_style(Config::SLUG . '-frontend-style');
		wp_enqueue_style(Config::SLUG . '-dashicon');

		wp_register_script(Config::SLUG . '-frontend-script', plugins_url('bundle/js/frontend-bundle.js', __FILE__), array('jquery'));

		wp_localize_script(Config::SLUG . '-frontend-script', 'face_factor', array('ajaxurl' => admin_url('admin-ajax.php')));

		wp_enqueue_script(Config::SLUG . '-frontend-script');
	}


	public function enqueue_admin()
	{


		$current_screen = get_current_screen();


		if (stripos($current_screen->base, Config::SLUG))
		{
			wp_enqueue_media();
			wp_register_style(Config::SLUG . '-backend-style', plugins_url('bundle/css/backend.css', __FILE__));
			wp_register_style(Config::SLUG . '-backend-fa-style', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css');

			wp_enqueue_style(Config::SLUG . '-backend-fa-style');
			wp_enqueue_style(Config::SLUG . '-backend-style');

			wp_register_script(Config::SLUG . '-backend-script', plugins_url('bundle/js/backend-bundle.js', __FILE__));

			wp_enqueue_script(Config::SLUG . '-backend-script');
		}


	}

	public function add_to_menu()
	{
		(new Core())->admin_menu();
		//add sub menu page here


		add_menu_page(
			Config::NAME,
			Config::MENU_NAME,
			'manage_options',
			Config::SLUG,
			array($this,'plugin_ui'),
			'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAxZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQwIDc5LjE2MDQ1MSwgMjAxNy8wNS8wNi0wMTowODoyMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RDUyNEEyN0VEM0I1MTFFOUI2RjJCQUM4QkZBRTY0MjQiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RDUyNEEyN0REM0I1MTFFOUI2RjJCQUM4QkZBRTY0MjQiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTggTWFjaW50b3NoIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9IkM5RjBCRkE1QzU0RkY3MUIxMzdCMEExOEE2NEQ3OTk2IiBzdFJlZjpkb2N1bWVudElEPSJDOUYwQkZBNUM1NEZGNzFCMTM3QjBBMThBNjRENzk5NiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PiVqTPwAAAP3SURBVHjazJRbaFxVFIa/fS4zmckkM5ncmza9JQEbMlWrhSJtU/FCCjXG6lt9UfTBh4pFqE++RovQB0EtSLGClWIq9qIRS6EoVIKpYNImWorNrbk0t8nMZOYk55y93XMSFHzwqQ+uvffhcNbZ//73Wv9aQinFgzSDB2wWt+DPaLrlu8LQ22FDFCwMTyAozqKJYKy/i39vF3jKD0vXkvs3bH6/PME9i9Zx7kykdx27MvRadYlF2DBwLQtfr4jnYUmJEmugxet4psmKZRPytU/7lfY4SnA+SW+TUa4BmSJq2n6Thq8K+8wmkjRTYHN2gV+T1Ti+QU12KeAzVRanKqRILd1nuEzTMeJsymYwRZRwyPUhra+spxCmEEoyq0E7R27xeO81pnMGXc0Jzu7bx2/ltQHLPekpDvX+wPhEnq6NMa6076G3oYW6jBNEbz0pdSgz6i6URti7OEH7Z9/w3tUMX4pmXKeezrPniduKWsvjxZ4L9Fwa58OFWv4I7eDQucs8Nj/BYjSqMWKaYZUGzIchd/PgajRMy8IMw7Mu/RuayaQXeOPHCZyFAg1engYny9xkhu/jWymxJcf7JhkYydKaW8Qt0bzyNw/iJTSgc/T3UPbzVytycHlLG5v2N/GBM0jb5AjdDJF96lGGQ3EGI5U4Tz7MSfs2qakxupf6qTnQwreb2kjmV7EXTx8j/U6fhUpHlG9S7kvmlc2pF7p4vvkG7XNLjG7byMXtKUozOaTO5ul9T9NZX0P76CSF2iSftO5iSYus2vUQKg8yFxFqZaT6+vTVM2/+kuiIm5Jl22IxVoZZWw6FVapGJrGKetEV5eoLzW1rAC0dNZshkctqaUmk9n+Uyny1o/HZVyxCZbMysvdTwUBH0RHW+qovZJFfDyBq4oimWmQ6H4jYSkSo+WkQlV/B2NmIkj6+UPgyBLH9pzBMrQ3uoryCbQj7n+qojKF+voN3shcRjyI2ViAaK0Gzd09cQg2MIZKx4F8VkDeQIqcBxorisTWOWsfST61HtZTHfP0Aqvsi7tEzGB07wVfIizcwWuowjzyBms+uAepQyKDBqECLGtAPAi6EETg8/d3QQjU0S/Pdw8iePvxrw2ui3b0d8/DuoKhVelnHUu8pluPfHUsWASP4/ooxs5zVAgbH0lcjjD/j4pVowR7pRDira/VcYqMWNVDB1WSqUSsZvWZ1UiKanww6l3XXT1JhONdfTjUfTyZD97fOn3vLdAZTnq5TVRzFw2PrwZWqeH5gpj/PSml73+3K5z6OzBcqy70t/ZOaiDVmupQJRl+qbzzRWp2AypQJpc9A2fR/Nj6Zrif0yAVlP/TFPUuD+2Ey6AT+7zv2XwIMANaAp73bT334AAAAAElFTkSuQmCC'
		);

	}

	public function plugin_ui()
	{
		Main::ui();
	}
}

LoginPage::init();
new Initiator();

