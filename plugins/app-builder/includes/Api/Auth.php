<?php
/**
 * class Auth
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 * @author     ngocdt
 */

namespace AppBuilder\Api;

defined( 'ABSPATH' ) || exit;

use AppBuilder\Template\Template;
use AppBuilder\Token;
use AppBuilder\Utils;
use Exception;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use AppBuilder\PublicKey;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Response;
use WP_REST_Server;
use WP_User;

class Auth extends Base {

	private Token $tokenObj;

	public function __construct() {
		parent::__construct();

		$this->tokenObj = new Token();
		add_filter( 'determine_current_user', array( $this, 'determine_current_user' ), 10, 1 );
	}

	/**
	 * Add the endpoints to the API
	 *
	 * @since 1.0.0
	 * @author ngocdt
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'login',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'login' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			$this->namespace,
			'register',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'register' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			$this->namespace,
			'register-phone-number',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'register_phone_number' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			$this->namespace,
			'current',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'current' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			$this->namespace,
			'change-password',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'change_password' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			$this->namespace,
			'lost-password',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'lost_password' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			$this->namespace,
			'forgot-password',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'forgot_password' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'user_login' => array(
						'required'    => true,
						'description' => 'User login or email address',
					),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'verify-otp-forgot-password',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'verify_otp_forgot_password' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'user_login' => array(
						'required'    => true,
						'description' => 'User login or email address',
					),
					'otp'        => array(
						'required'    => true,
						'description' => 'OTP code',
					),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'update-password',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_password' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'token'        => array(
						'required'    => true,
						'description' => 'Token',
					),
					'new_password' => array(
						'required'    => true,
						'description' => 'New password',
					),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'login-token',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'login_token' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			$this->namespace,
			'delete',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'delete' ),
				'permission_callback' => array( $this, 'logged_permissions_check' ),
			)
		);

		register_rest_route(
			$this->namespace,
			'send-otp-delete',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'send_otp_delete' ),
				'permission_callback' => array( $this, 'logged_permissions_check' ),
			)
		);
	}

	/**
	 *
	 * Prepare data
	 *
	 * @param $user
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function pre_data_response( $user, $request, $exist = false ) {

		if ( $exist ) {
			do_action( 'wp_login', $user->user_login, $user );
		}

		/**
		 * Trigger action before response API
		 */
		do_action( $this->get_txt_name( '_register_success' ), $user, $request );

		/**
		 * Support jwt-authentication-for-wp-rest-api plugin
		 */
		$data = array();
		if ( class_exists( 'Jwt_Auth' ) ) {
			$data = array(
				'user' => array(
					'id' => $user->data->ID,
				),
			);
		}

		/**
		 * Pre generate login token
		 */
		$pre_token = apply_filters(
			$this->get_txt_name( '_before_auth_response' ),
			$this->tokenObj->sign_token( $user->data->ID, $data )
		);

		/**
		 * Pre User data
		 */
		$pre_user = apply_filters( 'app_builder_prepare_userdata', $user );

		/**
		 * Pre User data
		 */
		$pre_response = apply_filters(
			$this->get_txt_name( '_pre_auth_response' ),
			array(
				'token' => $pre_token,
				'user'  => $pre_user,
			),
			$request
		);

		return rest_ensure_response( $pre_response );
	}

	/**
	 *
	 * Login Method
	 *
	 * @param $request
	 *
	 * @return array|mixed|void|WP_Error
	 * @throws FacebookSDKException
	 * @author ngocdt
	 *
	 * @since 1.0.0
	 */
	public function login( $request ) {

		$type = $request->get_param( 'type' );

		if ( $type == 'apple' ) {
			return $this->login_apple( $request );
		}

		if ( $type == 'facebook' ) {
			return $this->login_facebook( $request );
		}

		if ( $type == 'google' ) {
			return $this->login_google( $request );
		}

		if ( $type == 'phone' ) {
			return $this->login_firebase_phone_number( $request );
		}

		/**
		 * Get Username and Password
		 */
		$username = $request->get_param( 'username' );
		$password = $request->get_param( 'password' );

		$validate = apply_filters( 'app_builder_validate_form_data', true, $request, 'Login' );

		if ( is_wp_error( $validate ) ) {
			return $validate;
		}

		// try login with username and password
		$user = wp_authenticate( $username, $password );

		// Return the errors to client
		if ( is_wp_error( $user ) ) {
			return new WP_Error(
				'login_email_password_error',
				$user->get_error_message(),
				array(
					'status' => 403,
				)
			);
		}

		return $this->pre_data_response( $user, $request, true );
	}

	/**
	 * Login With Apple
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @throws Exception
	 */
	public function login_apple( $request ) {
		try {
			$identityToken = $request->get_param( 'identityToken' );
			$userIdentity  = $request->get_param( 'userIdentifier' );
			$givenName     = $request->get_param( 'givenName' );
			$familyName    = $request->get_param( 'familyName' );
			$role          = $request->get_param( 'role' );

			$tks = explode( '.', $identityToken );
			if ( count( $tks ) != 3 ) {
				return new WP_Error(
					'login_apple_error',
					__( 'Wrong number of segments', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			list( $headb64 ) = $tks;

			if ( null === ( $header = JWT::jsonDecode( JWT::urlsafeB64Decode( $headb64 ) ) ) ) {
				return new WP_Error(
					'login_apple_error',
					__( 'Invalid header encoding', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			if ( ! isset( $header->kid ) ) {
				return new WP_Error(
					'login_apple_error',
					__( '"kid" empty, unable to lookup correct key', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			$publicKeyDetails = PublicKey::getPublicKey( $header->kid );
			$publicKey        = $publicKeyDetails['publicKey'];
			$alg              = $publicKeyDetails['alg'];

			$payload = JWT::decode( $identityToken, new Key( $publicKey, $alg ) );

			if ( $payload->sub !== $userIdentity ) {
				return new WP_Error(
					'validate-user',
					__( 'User not validate', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			// User already exist in database with Email
			$user = get_user_by( 'email', $payload->email );
			if ( $user ) {
				return $this->pre_data_response( $user, $request, true );
			}

			// User already exist in database with user Identity
			$user = get_user_by( 'login', $payload->sub );
			if ( $user ) {
				return $this->pre_data_response( $user, $request, true );
			}

			// Register new user
			$userdata = array(
				'user_pass'    => wp_generate_password(),
				'user_login'   => $payload->sub,
				'user_email'   => $payload->email,
				'display_name' => $givenName,
				'first_name'   => $familyName,
				'last_name'    => $givenName,
				'role'         => $this->verify_role( $role ),
			);

			$user_id = wp_insert_user( $userdata );

			if ( is_wp_error( $user_id ) ) {
				$error_code = $user_id->get_error_code();

				return new WP_Error(
					$error_code,
					$user_id->get_error_message( $error_code ),
					array(
						'status' => 403,
					)
				);
			}

			$user = get_user_by( 'id', $user_id );

			add_user_meta( $user_id, 'app_builder_login_type', 'apple', true );

			return $this->pre_data_response( $user, $request );

		} catch ( Exception $e ) {
			return new WP_Error(
				'login_apple_error',
				$e->getMessage(),
				array(
					'status' => 403,
				)
			);
		}
	}

	/**
	 *
	 * Login Facebook
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @throws FacebookSDKException
	 */
	public function login_facebook( $request ) {
		$token = $request->get_param( 'token' );
		$role  = $request->get_param( 'role' );

		$facebook = appBuilder()->settings()->get( 'facebook' );

		if ( ! $facebook || ! $facebook['app_id'] ) {
			return new WP_Error(
				'login_facebook',
				__( 'Facebook App Id not config yet.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		if ( ! $facebook['app_secret'] ) {
			return new WP_Error(
				'login_facebook',
				__( 'Facebook App Secret not config yet.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$fb = new Facebook(
			array(
				'app_id'                => $facebook['app_id'],
				'app_secret'            => $facebook['app_secret'],
				'default_graph_version' => 'v2.10',
			// 'default_access_token' => '{access-token}', // optional
			)
		);

		try {
			// Get the \Facebook\GraphNodes\GraphUser object for the current user.
			// If you provided a 'default_access_token', the '{access-token}' is optional.
			$response = $fb->get( '/me?fields=id,first_name,last_name,name,picture,email', $token );
		} catch ( FacebookSDKException $e ) {
			// When validation fails or other local issues
			return new WP_Error(
				'login_facebook_error',
				__( 'Facebook SDK returned an error: ', 'app-builder' ) . $e->getMessage(),
				array(
					'status' => 403,
				)
			);
		}

		$me = $response->getGraphUser();

		// Email not exist
		$email = $me->getEmail();
		if ( ! $email ) {
			return new WP_Error(
				'email_not_exist',
				__( 'User not provider email', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$user = get_user_by( 'email', $email );

		// Return data if user exist in database
		if ( $user ) {
			return $this->pre_data_response( $user, $request, true );
		}

		try {
			// Will create new user
			$picture = $me->getPicture();

			$user_id = wp_insert_user(
				array(
					'user_pass'     => wp_generate_password(),
					'user_login'    => $email,
					'user_nicename' => $me->getName(),
					'user_email'    => $email,
					'display_name'  => $me->getName(),
					'first_name'    => $me->getFirstName(),
					'last_name'     => $me->getLastName(),
					'role'          => $this->verify_role( $role ),
				)
			);

			if ( is_wp_error( $user_id ) ) {
				return $user_id;
			}

			// Get user
			$user = get_user_by( 'id', $user_id );

			add_user_meta( $user_id, 'app_builder_login_type', 'facebook', true );
			add_user_meta( $user_id, 'app_builder_login_avatar', $picture, true );

			return $this->pre_data_response( $user, $request );

		} catch ( Exception $e ) {
			return new WP_Error(
				$this->get_txt_name( '_login_facebook' ),
				__( $e->getMessage(), $this->get_txt_domain() ),
				array(
					'status' => 403,
				)
			);
		}
	}

	/**
	 *
	 * Login with Google
	 *
	 * @param $request
	 *
	 * @return array|WP_Error
	 */
	public function login_google( $request ) {
		$idToken = $request->get_param( 'idToken' );
		$role    = $request->get_param( 'role' );

		$url    = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $idToken;
		$json   = Utils::get_url_content( $url );
		$result = json_decode( $json );

		if ( $result == false ) {
			return new WP_Error(
				'login_google_error',
				__( 'Get Firebase user info error!', 'app_builder' ),
				array(
					'status' => 400,
				)
			);
		}

		// Email not exist
		$email = $result->email;
		if ( ! $email ) {
			return new WP_Error(
				'login_google_error',
				__( 'User not provider email', 'app_builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$user = get_user_by( 'email', $email );

		// Response if the email already exis in database
		if ( $user ) {
			return $this->pre_data_response( $user, $request, true );
		}

		// Insert new user
		$user_id = wp_insert_user(
			array(
				'user_login'    => $result->email,
				'user_pass'     => wp_generate_password(),
				'user_nicename' => $result->name,
				'user_email'    => $result->email,
				'display_name'  => $result->name,
				'first_name'    => $result->given_name,
				'last_name'     => $result->family_name,
				'role'          => $this->verify_role( $role ),
			)
		);

		if ( is_wp_error( $user_id ) ) {
			return $user_id;
		}

		$user = get_user_by( 'id', $user_id );

		add_user_meta( $user_id, 'app_builder_login_type', 'google', true );
		add_user_meta( $user_id, 'app_builder_login_avatar', $result->picture, true );

		return $this->pre_data_response( $user, $request );
	}

	/**
	 *
	 * Login with Phone Auth Firebase
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function login_firebase_phone_number( $request ) {

		try {
			$google = appBuilder()->settings()->get( 'google' );

			if ( ! $google || ! $google['key'] ) {
				return new WP_Error(
					'login_firebase_phone_number',
					__( 'Google Api key not config yet.', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			$token = $request->get_param( 'token' );

			$url  = 'https://www.googleapis.com/identitytoolkit/v3/relyingparty/getAccountInfo?key=' . $google['key'];
			$data = array( 'idToken' => $token );

			// use key 'http' even if you send the request to https://...
			$options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query( $data ),
				),
			);

			$context = stream_context_create( $options );
			$json    = file_get_contents( $url, false, $context );
			$result  = json_decode( $json );

			if ( $result === false ) {
				$error = new WP_Error();
				$error->add( 403, __( 'Get Firebase user info error!', 'app_builder' ), array( 'status' => 400 ) );

				return $error;
			}

			/**
			 * If the user not exist in Firebase we try register in WP database
			 */
			if ( ! isset( $result->users[0]->phoneNumber ) ) {
				$request->set_param( 'phone', $result->users[0]->phoneNumber );

				return $this->register_phone_number( $request );
			}

			$phone_number = $result->users[0]->phoneNumber;

			$users = get_users(
				array(
					'meta_key'     => 'digits_phone',
					'meta_value'   => $phone_number,
					'meta_compare' => '=',
				)
			);

			/**
			 * The user not exist in the database try register
			 */
			if ( count( $users ) == 0 ) {
				$request->set_param( 'phone', $result->users[0]->phoneNumber );

				return $this->register_phone_number( $request );
			}

			/**
			 * Return user log in
			 */
			$user = $users[0];

			return $this->pre_data_response( $user, $request, true );
		} catch ( Exception $err ) {
			return new WP_Error(
				'login_firebase_phone_number',
				__( $err->getMessage(), $this->get_txt_domain() ),
				array(
					'status' => 403,
				)
			);
		}
	}

	/**
	 *
	 * Register user
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function register( $request ) {

		$user_data = array();

		$validate = apply_filters( 'app_builder_validate_form_data', true, $request, 'Register' );

		if ( is_wp_error( $validate ) ) {
			return $validate;
		}

		$enable_phone_number = $request->get_param( 'enable_phone_number' );

		/**
		 * Forward to log in with phone number
		 */
		if ( $enable_phone_number ) {
			return $this->register_phone_number( $request );
		}

		$email              = $request->get_param( 'email' );
		$user_login         = $request->get_param( 'user_login' );
		$first_name         = $request->get_param( 'first_name' );
		$last_name          = $request->get_param( 'last_name' );
		$password           = $request->get_param( 'password' );
		$subscribe          = $request->get_param( 'subscribe' );
		$agree_privacy_term = $request->get_param( 'agree_privacy_term' );
		$role               = $request->get_param( 'role' );

		if ( ! $agree_privacy_term ) {
			return new WP_Error(
				'app_builder_register_error',
				__( 'To register you need agree our term and privacy.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		// Validate first name
		if ( mb_strlen( $first_name ) < 1 ) {
			return new WP_Error(
				'app_builder_register_error',
				__( "First name isn't valid.", 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		// Validate last name
		if ( mb_strlen( $last_name ) < 1 ) {
			return new WP_Error(
				'app_builder_register_error',
				__( "Last name isn't valid.", 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$config = new Template();

		$enable_email = $config->getScreenData( 'register', 'register', 'enableEmail', true );

		if ( $enable_email ) {

			// Validate email
			if ( ! is_email( $email ) ) {
				return new WP_Error(
					'app_builder_register_error',
					__( 'The email address isn’t correct.', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			if ( email_exists( $email ) ) {
				return new WP_Error(
					'app_builder_register_error',
					__( 'The email address is already registered', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			$user_data['email'] = $email;
		}

		// Validate username
		if ( empty( $user_login ) || mb_strlen( $user_login ) < 2 ) {
			return new WP_Error(
				'app_builder_register_error',
				__( 'Username too short.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		if ( ! validate_username( $user_login ) ) {
			return new WP_Error(
				'app_builder_register_error',
				__( 'This username is invalid because it uses illegal characters. Please enter a valid username.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		if ( username_exists( $user_login ) ) {
			return new WP_Error(
				'app_builder_register_error',
				__( 'Username already exists.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		// Validate password
		if ( empty( $password ) ) {
			return new WP_Error(
				'app_builder_register_error',
				__( 'Password is required.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		if ( mb_strlen( $password ) < 6 ) {
			return new WP_Error(
				'app_builder_register_error',
				__( 'Password is too short.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$user_data = array_merge(
			$user_data,
			array(
				'user_pass'    => $password,
				'user_email'   => $email,
				'user_login'   => $user_login,
				'display_name' => "$first_name $last_name",
				'first_name'   => $first_name,
				'last_name'    => $last_name,
				'role'         => $this->verify_role( $role ),
			)
		);

		$user_id = wp_insert_user( apply_filters( 'app_builder_register_user_data', $user_data, $request ) );

		if ( is_wp_error( $user_id ) ) {
			return $user_id;
		}

		do_action( 'app_builder_after_insert_user', $user_id, $request );

		// Subscribe
		add_user_meta( $user_id, 'app_builder_subscribe', $subscribe == null ? false : $subscribe, true );

		// Agree term and privacy
		add_user_meta( $user_id, 'app_builder_agree_privacy_term', $agree_privacy_term == null ? false : $agree_privacy_term, true );

		$user = get_user_by( 'id', $user_id );

		return $this->pre_data_response( $user, $request );
	}

	/**
	 *
	 * Login with phone number
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function register_phone_number( $request ) {
		$phone              = $request->get_param( 'phone' );
		$role               = $request->get_param( 'role' );
		$subscribe          = $request->get_param( 'subscribe' );
		$agree_privacy_term = $request->get_param( 'agree_privacy_term' );

		try {
			$phoneUtil        = PhoneNumberUtil::getInstance();
			$swissNumberProto = $phoneUtil->parse( $phone );

			// Parse phone number
			$isValid = $phoneUtil->isValidNumber( $swissNumberProto );
			if ( ! $isValid ) {
				return new WP_Error(
					$this->get_txt_name( '_register_error' ),
					__( 'Your phone number not validate', 'app-builder' ),
					array(
						'status' => 403,
					)
				);
			}

			// Check phone number in database
			$digits_phone    = $phoneUtil->format( $swissNumberProto, PhoneNumberFormat::E164 );
			$digits_phone_no = $phoneUtil->format( $swissNumberProto, PhoneNumberFormat::NATIONAL );

			/**
			 * Try get users by phone number
			 */
			$users = get_users(
				array(
					'meta_key'     => 'digits_phone',
					'meta_value'   => $digits_phone,
					'meta_compare' => '=',
				)
			);

			/**
			 * Return login data if user already exist in the database
			 */
			if ( count( $users ) > 0 ) {
				return $this->pre_data_response( $users[0], $request, true );
			}

			$user_login = str_replace( '+', '', $digits_phone );

			if ( username_exists( $user_login ) ) {
				$user_login = wp_generate_uuid4();
			}

			$userdata = array(
				'user_login' => $user_login,
				'role'       => $this->verify_role( $role ),
				'user_pass'  => wp_generate_password(),
			);

			$user_id = wp_insert_user( $userdata );

			if ( is_wp_error( $user_id ) ) {
				return $user_id;
			}

			// Update phone number for user
			add_user_meta( $user_id, 'digt_countrycode', $swissNumberProto->getCountryCode(), true );
			add_user_meta( $user_id, 'digits_phone_no', str_replace( ' ', '', $digits_phone_no ), true );
			add_user_meta( $user_id, 'digits_phone', $digits_phone, true );

			// Subscribe
			add_user_meta( $user_id, 'app_builder_subscribe', $subscribe == null ? false : $subscribe, true );

			// Agree term and privacy
			add_user_meta( $user_id, 'app_builder_agree_privacy_term', $agree_privacy_term == null ? false : $agree_privacy_term, true );

			$user = get_user_by( 'id', $user_id );

			return $this->pre_data_response( $user, $request );

		} catch ( NumberParseException $e ) {
			return new WP_Error(
				$this->get_txt_name( '_register_error' ),
				$e->getMessage(),
				array( 'status' => 400 )
			);
		}
	}

	/**
	 *  Get current user login
	 *
	 * @param $request
	 *
	 * @return mixed
	 * @author ngocdt
	 *
	 * @since 1.0.0
	 */
	public function current( $request ) {
		$user = wp_get_current_user();

		if ( empty( $user ) || $user->ID == 0 ) {
			return new WP_Error(
				'no_current_login',
				__( 'User not login.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		return apply_filters( APP_BUILDER_NAME . '_current', apply_filters( 'app_builder_prepare_userdata', $user ), $request );
	}

	/**
	 *
	 * Change password
	 *
	 * @param $request
	 *
	 * @return int|WP_Error
	 */
	public function change_password( $request ) {

		$current_user = wp_get_current_user();
		if ( ! $current_user->exists() ) {
			return new WP_Error(
				'user_not_login',
				__( 'Please login first.', 'mobile-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$username     = $current_user->user_login;
		$password_old = $request->get_param( 'password_old' );
		$password_new = $request->get_param( 'password_new' );

		// try login with username and password
		$user = wp_authenticate( $username, $password_old );

		if ( is_wp_error( $user ) ) {
			$error_code = $user->get_error_code();

			return new WP_Error(
				$error_code,
				$user->get_error_message( $error_code ),
				array(
					'status' => 403,
				)
			);
		}

		wp_set_password( $password_new, $current_user->ID );

		return $current_user->ID;
	}

	/**
	 * This to determine the current user from the request’s thought header if available.
	 *
	 * @param $user_id int|bool ID if one has been determined, false otherwise.
	 *
	 * @return int|bool
	 * @author ngocdt
	 *
	 * @since 1.0.0
	 */
	public function determine_current_user( $user_id ) {

		/* Decode user if pass thought param */
		if ( isset( $_GET[ APP_BUILDER_TOKEN_PARAM_NAME ] ) ) {
			$token = $this->tokenObj->verify_token( sanitize_text_field( $_GET[ APP_BUILDER_TOKEN_PARAM_NAME ] ) );

			/* If facing any errors return current user id state*/
			if ( is_wp_error( $token ) ) {
				return $user_id;
			}

			return $token->data->user_id;
		}

		/* Run only app_builder_decode param exist */
		if ( ! isset( $_GET[ APP_BUILDER_DECODE ] ) || empty( $_GET[ APP_BUILDER_DECODE ] ) ) {
			return $user_id;
		}

		/* Decode authorization on the header to determine current user */
		$token = $this->tokenObj->verify_token();

		/* If facing any errors return current user id state*/
		if ( is_wp_error( $token ) ) {
			return $user_id;
		}

		/* Return current user id store in token */

		return $token->data->user_id;
	}

	/**
	 *
	 * Determine current user via cookie
	 *
	 * @param $user_id
	 *
	 * @return mixed
	 */
	public function cookie_determine_current_user( $user_id ) {

		if ( isset( $_COOKIE['cirilla_auth_token'] ) ) {
			/* Decode authorization on the header to determine current user */
			$token = $this->tokenObj->verify_token( $_COOKIE['cirilla_auth_token'] );

			/* If facing any errors return current user id state*/
			if ( is_wp_error( $token ) ) {
				return $user_id;
			}

			/* Return current user id store in token */

			return $token->data->user_id;

		}

		return $user_id;
	}

	/**
	 * Lost password for user
	 *
	 * @param $request
	 *
	 * @return bool|WP_Error
	 * @since 1.0.8
	 */
	public function lost_password( $request ) {
		$errors = new WP_Error();

		$user_login = $request->get_param( 'user_login' );

		if ( empty( $user_login ) || ! is_string( $user_login ) ) {
			$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Enter a username or email address.', 'mobile-builder' ) );
		} elseif ( strpos( $user_login, '@' ) ) {
			$user_data = get_user_by( 'email', trim( wp_unslash( $user_login ) ) );
			if ( empty( $user_data ) ) {
				$errors->add(
					'invalid_email',
					__( '<strong>ERROR</strong>: There is no account with that username or email address.', 'mobile-builder' )
				);
			}
		} else {
			$login     = trim( $user_login );
			$user_data = get_user_by( 'login', $login );
		}

		if ( $errors->has_errors() ) {
			return $errors;
		}

		if ( ! $user_data ) {
			$errors->add(
				'invalidcombo',
				__( '<strong>ERROR</strong>: There is no account with that username or email address.', 'mobile-builder' )
			);

			return $errors;
		}

		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		$key        = get_password_reset_key( $user_data );

		if ( is_wp_error( $key ) ) {
			return $key;
		}

		if ( is_multisite() ) {
			$site_name = get_network()->site_name;
		} else {
			/*
			 * The blogname option is escaped with esc_html on the way into the database
			 * in sanitize_option we want to reverse this for the plain text arena of emails.
			 */
			$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		$message = __( 'Someone has requested a password reset for the following account:', 'mobile-builder' ) . "\r\n\r\n";
		/* translators: %s: site name */
		$message .= sprintf( __( 'Site Name: %s', 'mobile-builder' ), $site_name ) . "\r\n\r\n";
		/* translators: %s: user login */
		$message .= sprintf( __( 'Username: %s', 'mobile-builder' ), $user_login ) . "\r\n\r\n";
		$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'mobile-builder' ) . "\r\n\r\n";
		$message .= __( 'To reset your password, visit the following address:', 'mobile-builder' ) . "\r\n\r\n";
		$message .= '<' . network_site_url(
			"wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ),
			'login'
		) . ">\r\n";

		/* translators: Password reset notification email subject. %s: Site title */
		$title = sprintf( __( '[%s] Password Reset', 'mobile-builder' ), $site_name );

		/**
		 * Filters the subject of the password reset email.
		 *
		 * @param string $title Default email title.
		 * @param string $user_login The username for the user.
		 * @param WP_User $user_data WP_User object.
		 *
		 * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
		 *
		 * @since 2.8.0
		 */
		$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

		/**
		 * Filters the message body of the password reset mail.
		 *
		 * If the filtered message is empty, the password reset email will not be sent.
		 *
		 * @param string $message Default mail message.
		 * @param string $key The activation key.
		 * @param string $user_login The username for the user.
		 * @param WP_User $user_data WP_User object.
		 *
		 * @since 2.8.0
		 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
		 */
		$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

		if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
			return new WP_Error(
				'send_email',
				__( 'Possible reason: your host may have disabled the mail() function.', 'mobile-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		return true;
	}

	/**
	 * Forgot password
	 *
	 * @param WP_REST_Request $request request.
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function forgot_password( $request ) {
		$errors = new WP_Error();

		$user_login = $request->get_param( 'user_login' );

		if ( empty( $user_login ) || ! is_string( $user_login ) ) {
			$errors->add( 'empty_username', __( 'Enter a username or email address.', 'mobile-builder' ) );
		} elseif ( strpos( $user_login, '@' ) ) {
			$user_data = get_user_by( 'email', trim( wp_unslash( $user_login ) ) );
			if ( empty( $user_data ) ) {
				$errors->add( 'invalid_email', __( 'There is no account with that username or email address.', 'mobile-builder' ) );
			}
		} else {
			$login     = trim( $user_login );
			$user_data = get_user_by( 'login', $login );
		}

		if ( $errors->has_errors() ) {
			return $errors;
		}

		if ( ! $user_data ) {
			$errors->add( 'invalidcombo', __( 'There is no account with that username or email address.', 'mobile-builder' ) );

			return $errors;
		}

		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		$key        = get_password_reset_key( $user_data );

		if ( is_wp_error( $key ) ) {
			return $key;
		}

		if ( is_multisite() ) {
			$site_name = get_network()->site_name;
		} else {
			/*
			 * The blogname option is escaped with esc_html on the way into the database
			 * in sanitize_option we want to reverse this for the plain text arena of emails.
			 */
			$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}

		// Generate OTP 6 digits.
		$key = wp_rand( 100000, 999999 );

		// Hi [User Name],
		$message = __( 'Hi', 'mobile-builder' ) . ' ' . $user_login . ',' . "\r\n\r\n";

		// We heard you're having trouble remembering your password for [App Name]. No worries, it happens to the best of us!
		$message .= __( 'We heard you\'re having trouble remembering your password for', 'mobile-builder' ) . ' ' . $site_name . '. ' . __( 'No worries, it happens to the best of us!', 'mobile-builder' ) . "\r\n\r\n";

		// Your OTP is: [6-digit OTP].
		$message .= __( 'Your OTP is:', 'mobile-builder' ) . ' ' . $key . "\r\n\r\n";

		// Please note: This OTP is confidential and should not be shared with anyone.
		$message .= __( 'Please note: This OTP is confidential and should not be shared with anyone.', 'mobile-builder' ) . "\r\n\r\n";

		// If you didn't request a password reset, please disregard this email. However, we recommend updating your password regularly to keep your account secure.
		$message .= __( 'If you didn\'t request a password reset, please disregard this email. However, we recommend updating your password regularly to keep your account secure.', 'mobile-builder' ) . "\r\n\r\n";

		// Subject: Access Your [App Name] Account again - One-Time Password Inside.
		$title = __( 'Access Your', 'mobile-builder' ) . ' ' . $site_name . ' ' . __( 'Account again - One-Time Password Inside', 'mobile-builder' );

		// Send email.
		if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
			return new WP_Error(
				'send_email',
				__( 'Possible reason: your host may have disabled the mail() function.', 'mobile-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		// Update OTP to user meta expire after 30 minutes.
		set_transient( 'app_builder_forgot_password_' . $user_data->ID, $key, 1800 );

		// Response.
		return rest_ensure_response( array( 'message' => __( 'OTP sent', 'mobile-builder' ) ) );
	}

	/**
	 * Verify OTP for forgot password.
	 *
	 * @param WP_REST_Request $request request.
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function verify_otp_forgot_password( $request ) {
		$errors = new WP_Error();

		$user_login = $request->get_param( 'user_login' );
		$otp        = $request->get_param( 'otp' );

		if ( empty( $user_login ) || ! is_string( $user_login ) ) {
			$errors->add( 'empty_username', __( 'Enter a username or email address.', 'mobile-builder' ) );
		} elseif ( strpos( $user_login, '@' ) ) {
			$user_data = get_user_by( 'email', trim( wp_unslash( $user_login ) ) );
			if ( empty( $user_data ) ) {
				$errors->add( 'invalid_email', __( 'There is no account with that username or email address.', 'mobile-builder' ) );
			}
		} else {
			$login     = trim( $user_login );
			$user_data = get_user_by( 'login', $login );
		}

		if ( $errors->has_errors() ) {
			return $errors;
		}

		if ( ! $user_data ) {
			$errors->add( 'invalidcombo', __( 'There is no account with that username or email address.', 'mobile-builder' ) );

			return $errors;
		}

		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;

		// Get OTP from user meta.
		$otp_user = get_transient( 'app_builder_forgot_password_' . $user_data->ID );

		// Check OTP.
		if ( $otp !== $otp_user ) {
			return new WP_Error(
				'invalid_otp',
				__( 'Invalid OTP', 'mobile-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$data = array(
			'user' => array(
				'id' => $user_data->ID,
			),
			'otp'  => $otp,
		);

		$token = $this->tokenObj->sign_token( $user_data->ID, $data, 1800 );

		// Response.
		return rest_ensure_response(
			array(
				'token' => $token,
			)
		);
	}

	/**
	 * Update password for forgot password.
	 *
	 * @param WP_REST_Request $request request.
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function update_password( $request ) {
		$token        = $request->get_param( 'token' );
		$new_password = $request->get_param( 'new_password' );

		// Verify token.
		$data = $this->tokenObj->verify_token( $token );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$user_id = $data->data->user_id;
		$otp     = $data->data->otp;

		// Get OTP from user meta.
		$otp_user = get_transient( 'app_builder_forgot_password_' . $user_id );

		// Check OTP.
		if ( $otp !== $otp_user ) {
			return new WP_Error(
				'invalid_otp',
				__( 'Expired or Password changed', 'mobile-builder' ),
				array(
					'status' => 403,
				)
			);
		} else {
			// Delete OTP.
			delete_transient( 'app_builder_forgot_password_' . $user_id );
		}

		// Update new password.
		wp_set_password( $new_password, $user_id );

		// Response.
		return rest_ensure_response(
			array(
				'message' => __( 'Password updated', 'mobile-builder' ),
			)
		);
	}

	/**
	 * Sent OTP delete account
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function send_otp_delete( $request ) {
		$otp  = rand( 100000, 999999 );
		$user = wp_get_current_user();

		/**
		 * Filter hook get the OTP before sent
		 */
		$otp = apply_filters( 'app_builder_delete_user_otp', $otp );

		/**
		 * Do action before send OTP
		 */
		do_action( 'app_builder_delete_user_before_send_otp', $user, $otp );

		/**
		 * Filter hook sent OTP via email?
		 */
		$sent = apply_filters( 'app_builder_delete_user_sent_email', true );

		if ( $sent ) {
			$email            = array();
			$email['to']      = $user->user_email;
			$email['subject'] = sprintf( _x( '[%s] OTP', '%s = site name', 'app-builder' ), wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) );
			$email['message'] = sprintf( _x( 'Your OTP for delete account is %d OTP is valid for 30 minutes', 'Email OTP content', 'app-builder' ), $otp, ENT_QUOTES );

			$email_data = apply_filters( 'app_builder_delete_user_otp_email', $email );

			wp_mail( $email_data['to'], $email_data['subject'], $email_data['message'] );

			/**
			 * Update OTP to user meta
			 */
			update_user_meta( $user->ID, 'app_builder_delete_user_otp', $otp );
			update_user_meta( $user->ID, 'app_builder_delete_user_otp_sent_time', time() + 1800 );
		}

		return rest_ensure_response( array( 'otp' => 'OTP sent' ) );
	}

	/**
	 *
	 * Delete user
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function delete( $request ) {
		global $wpdb;
		if ( is_admin() ) {
			return new WP_Error(
				'app_builder_delete_admin',
				__( 'The delete option is not visible to Administrators.', 'mobile-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		$user = wp_get_current_user();

		$reason   = $request->get_param( 'reason' );
		$password = $request->get_param( 'password' );
		$otp      = (int) $request->get_param( 'otp' );

		$verify = false;

		$user_otp       = (int) get_user_meta( $user->ID, 'app_builder_delete_user_otp' );
		$user_sent_time = (int) get_user_meta( $user->ID, 'app_builder_delete_user_otp_sent_time' );

		$user_otp       = (int) get_user_meta( $user->ID, 'app_builder_delete_user_otp', true );
		$user_sent_time = (int) get_user_meta( $user->ID, 'app_builder_delete_user_otp_sent_time', true );

		if ( $otp == $user_otp && $user_sent_time >= time() ) {
			$verify = true;
		}

		$verify = apply_filters( 'app_builder_delete_user_verify_otp', $verify, $otp, $user );

		if ( ! $verify ) {
			return new WP_Error(
				'app_builder_delete_user_otp',
				__( 'The OTP not validate.', 'mobile-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		/**
		 * Include required WordPress function files
		 */
		include_once ABSPATH . WPINC . '/post.php'; // wp_delete_post
		include_once ABSPATH . 'wp-admin/includes/bookmark.php'; // wp_delete_link
		include_once ABSPATH . 'wp-admin/includes/comment.php'; // wp_delete_comment
		include_once ABSPATH . 'wp-admin/includes/user.php'; // wp_delete_user, get_blogs_of_user

		/**
		 * Delete Posts
		 */
		$post_types_to_delete = array();

		foreach ( get_post_types( array(), 'objects' ) as $post_type ) {

			if ( $post_type->delete_with_user ) {

				$post_types_to_delete[] = $post_type->name;

			} elseif ( null === $post_type->delete_with_user && post_type_supports( $post_type->name, 'author' ) ) {

				$post_types_to_delete[] = $post_type->name;

			}
		}

		$post_types_to_delete = apply_filters( 'app_builder_post_types_to_delete_with_user', $post_types_to_delete, $user->ID );

		/**
		 * Get post list
		 */
		$posts_list = array();
		$posts      = $wpdb->get_results( 'SELECT `ID`, `post_title`, `post_type` FROM ' . $wpdb->posts . " WHERE `post_author`='" . $user->ID . "' AND `post_type` IN ('" . implode( "', '", $post_types_to_delete ) . "')", ARRAY_A );
		foreach ( $posts as $post ) {
			$posts_list[] = wp_specialchars_decode( $post['post_title'], ENT_QUOTES ) . "\n" . ucwords( $post['post_type'] ) . ' ' . get_permalink( $post['ID'] );
		}

		/**
		 * Delete Links
		 */
		$links_list = array();
		$links      = $wpdb->get_results( 'SELECT `link_id`, `link_url`, `link_name` FROM ' . $wpdb->links . " WHERE `link_owner`='" . $user->ID . "'", ARRAY_A );
		foreach ( $links as $link ) {
			$links_list[] = wp_specialchars_decode( $link['link_name'], ENT_QUOTES ) . "\n" . $link['link_url'];
		}

		/**
		 * Delete Comments
		 */
		$comments_list = array();

		$comments = $wpdb->get_results( 'SELECT `comment_ID` FROM ' . $wpdb->comments . " WHERE `user_id`='" . $user->ID . "'", ARRAY_A );

		foreach ( $comments as $comment ) {

			$comments_list[] = $comment['comment_ID'];

			// Delete comments if option set
			wp_delete_comment( $comment['comment_ID'] );

		}

		/**
		 * Send email deleted
		 */

		$email            = array();
		$email['to']      = get_option( 'admin_email' );
		$email['subject'] = sprintf( _x( '[%s] Deleted User Notification', '%s = site name', 'app-builder' ), wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) );
		$email['message'] =
			sprintf( _x( 'Deleted user on your site %s', '%s = site name', 'app-builder' ), wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) ) . ':' . "\n\n" .
			__( 'Username', 'app-builder' ) . ': ' . $user->user_login . "\n\n" .
			__( 'E-mail', 'app-builder' ) . ': ' . $user->user_email . "\n\n" .
			__( 'Role', 'app-builder' ) . ': ' . implode( ',', $user->roles ) . "\n\n" .
			__( 'First Name', 'app-builder' ) . ': ' . ( empty( $user->first_name ) ? __( '(empty)', 'app-builder' ) : $user->first_name ) . "\n\n" .
			__( 'Last Name', 'app-builder' ) . ': ' . ( empty( $user->last_name ) ? __( '(empty)', 'app-builder' ) : $user->last_name ) . "\n\n" .

			sprintf( __( '%d Post(s)', 'app-builder' ), count( $posts_list ) ) . "\n" .
			'----------------------------------------------------------------------' . "\n" .
			implode( "\n\n", $posts_list ) . "\n\n" .
			sprintf( __( '%d Link(s)', 'app-builder' ), count( $links_list ) ) . "\n" .
			'----------------------------------------------------------------------' . "\n" .
			implode( "\n\n", $links_list ) . "\n\n" .
			sprintf( __( '%d Comment(s)', 'app-builder' ), count( $comments_list ) );

		$email_data = apply_filters( 'app_builder_delete_user_email', $email );

		wp_mail( $email_data['to'], $email_data['subject'], $email_data['message'] );

		/**
		 * Delete user
		 */
		$status = wp_delete_user( $user->ID );

		/**
		 * Do action after delete account
		 */
		do_action( 'app_builder_end_delete_account', $user, $request, $status );

		return rest_ensure_response( array( 'delete' => $status ) );
	}

	/**
	 *
	 * Login via token header
	 *
	 * @param $request
	 */
	public function login_token( $request ) {

		$cart_key = $request->get_param( 'cart_key_restore' );
		$redirect = $request->get_param( 'redirect' );
		$class    = $request->get_param( 'class' ) ?? 'app-builder-checkout';

		if ( $redirect ) {
			$url = $redirect . '?app-builder-checkout-body-class=' . $class;
		} else {
			$url = wc_get_checkout_url() . "?cart_key_restore=$cart_key";
		}

		if ( $request->get_param( 'theme' ) ) {
			$url .= '&theme=' . $request->get_param( 'theme' );
		}

		if ( $request->get_param( 'currency' ) ) {
			$url .= '&currency=' . $request->get_param( 'currency' );
		}

		if ( $request->get_param( '_lang' ) ) {
			$url .= '&lang=' . $request->get_param( '_lang' );
		}

		if ( $request->get_param( APP_BUILDER_CHECKOUT_BODY_CLASS ) ) {
			$url .= '&' . APP_BUILDER_CHECKOUT_BODY_CLASS . '=' . $request->get_param( APP_BUILDER_CHECKOUT_BODY_CLASS );
		}

		$user_id = get_current_user_id();

		if ( $user_id > 0 ) {
			wp_set_current_user( $user_id );
			wp_set_auth_cookie( $user_id );
		}

        wp_safe_redirect( $url );
		exit;
	}

	/**
	 *
	 * Verify role before inserting to database
	 *
	 * @param $role
	 *
	 * @return string
	 */
	private function verify_role( $role ): string {
		if ( ! $role || ! in_array( $role, array( 'subscriber', 'customer', 'wcfm_vendor' ) ) ) {
			$role = get_option( 'default_role', 'subscriber' );
		}

		return $role;
	}
}
