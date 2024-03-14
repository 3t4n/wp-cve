<?php
namespace Login_With_AJAX;
use Login_With_AJAX\TwoFA\Account;
use LoginWithAjax, WP_User, WP_Error;

/*
 * This is the first version of this add-on, some structural things may change here as we make refinements.
 * Therefore, please be mindful about potential breaking changes if you're using our hooks/filters here or in verification methods.
 * Please get in touch if you're extending this add-on, so we're aware of any potential use-cases to consider.
 *
 * Things to do:
 * Add timeout
 * Add 'remember' button for devices
 * Add custom email template editor
 */

class TwoFA {
	
	/**
	 * Array of 2FA methods available, keys are shortname for the method, value is the static class itself
	 * @var \Login_With_AJAX\TwoFA\Method\Method[]
	 */
	public static $methods = array();
	/**
	 * If set to true before lwa_authenticate is fired, 2FA will be forced for all users, regardless of setup or grace period.
	 * @var bool
	 */
	public static $force_authentication = false;
	/**
	 * If authentication is required and other tests have passed then proceed to request authentication.
	 * @var bool
	 */
	public static $authentication_required = false;
	/**
	 * Seconds for authentication requests to time out.
	 * @var int
	 */
	public static $authentication_timeout = 600;
	/**
	 * Stores logged in cookie value for use in 2FA
	 * @var string
	 */
	public static $logged_in_cookie;
	
	public static function init(){
		// Admin
		if( is_admin() ){
			include_once('2FA-admin.php');
			// detect if is options page for further down
			if( !empty($_REQUEST['page']) && $_REQUEST['page'] === 'login-with-ajax' ) {
				$options_page = true;
			}
		}
		if( empty(LoginWithAjax::$data['2FA']['enabled']) && empty($options_page) ) return null;
		// include built-in methods
		include_once('2FA-account.php');
		include('2FA-method.php');
		include('2FA-method-transport.php');
		include('2FA-method-code.php');
		include('2FA-totp.php');
		include('2FA-email.php');
		include('2FA-backup.php');
		// output actions, only if enabled and not in settings page
		add_action('lwa_register_scripts', array( static::class, 'register_scripts_and_styles') );
		add_action('lwa_login_form', array( static::class, 'enqueue'));
		add_action('lwa_enqueue', array( static::class, 'lwa_enqueue') );
		add_action('login_enqueue_scripts', array( static::class, 'enqueue_wp_login'));
		add_action('login_footer', array( static::class, 'footer'));
		// woocommerce enqueue
		add_action('woocommerce_login_form', array( static::class, 'enqueue'));
		// login hooks to check if authentication needed
		add_filter('lwa_authenticate', array( static::class, 'authenticate'), 99999, 1); //allow other LWA things to authenticate and trigger 2FA
		add_filter('lwa_login', array( static::class, 'login_response'), 99999, 2); //allow other LWA things to authenticate and trigger 2FA
		// trigger validation
		add_filter('lwa_ajax_2FA', array( static::class, 'ajax'), 99999, 1);
		// trigger loaded
		do_action('lwa_2FA_loaded');
	}
	
	public static function register_scripts_and_styles(){
		//Enqueue scripts - Only one script enqueued here.... theme CSS takes priority, then default JS
		$filename = defined('WP_DEBUG') && WP_DEBUG ? '2FA' : '2FA.min';
		$path = plugin_dir_url(__FILE__). $filename;
		wp_register_style("login-with-ajax-2FA", $path . '.css', array('login-with-ajax'), LOGIN_WITH_AJAX_VERSION);
		wp_register_script("login-with-ajax-2FA", $path . '.js', array('login-with-ajax'), LOGIN_WITH_AJAX_VERSION);
	}
	
	public static function enqueue(){
		add_action('wp_footer', array( static::class, 'footer'));
	}
	
	public static function lwa_enqueue(){
		wp_enqueue_script('login-with-ajax-2FA');
		wp_enqueue_style('login-with-ajax-2FA');
	}
	
	public static function enqueue_wp_login(){
		wp_enqueue_script('login-with-ajax-2FA');
		wp_enqueue_style('login-with-ajax-2FA');
		LoginWithAjax::localize_js();
	}
	
	/**
	 * @param \Login_With_AJAX\TwoFA\Method\Method|string $method
	 * @return bool
	 */
	public static function register_method( $method ){
		if( class_exists($method) ) {
			static::$methods[ $method::$method ] = $method;
			if( !$method::$authentication_timeout ) {
				$method::$authentication_timeout = static::$authentication_timeout;
			}
			return true;
		}
		return false;
	}
	
	
	/**
	 * @param WP_User $user
	 * @return \Login_With_AJAX\TwoFA\Method\Method[]
	 */
	public static function get_available_methods( $user = null ){
		$methods = array();
		foreach( static::$methods as $type => $method ) {
			if( $method::is_available( $user ) ) {
				$methods[$type] = $method;
			}
		}
		return $methods;
	}
	
	/**
	 * @param WP_User $user
	 * @return \Login_With_AJAX\TwoFA\Method\Method[]
	 */
	public static function get_ready_methods( $user = null ){
		$methods = array();
		foreach( static::$methods as $type => $method ) {
			if( $method::is_ready( $user ) ) {
				$methods[$type] = $method;
			}
		}
		return $methods;
	}
	
	/**
	 * Checks if user has at least one method that can be used for 2FA.
	 *
	 * @param WP_User $user
	 * @return bool
	 */
	public static function has_ready_method( $user ) {
		$methods = static::get_ready_methods( $user );
		return !empty($methods);
	}
	
	/**
	 * Checks if user must set up a 2FA method now or if they still have a grace period to sign up.
	 * @param $user
	 *
	 * @return mixed|null
	 */
	public static function is_setup_mandatory ( $user ) {
		$is_setup_needed = !static::has_ready_method( $user );
		$required_ts = static::get_setup_required_time( $user );
		$setup_mandatory = $is_setup_needed && $required_ts !== false && $required_ts < time();
		return apply_filters('lwa_2FA_is_setup_mandatory', $setup_mandatory, $required_ts, $is_setup_needed);
	}
	
	public static function get_setup_required_time( $user ) {
		$timestamp = false;
		if( !empty(LoginWithAjax::$data['2FA']['grace_mode']) ) {
			$grace_mode = LoginWithAjax::$data['2FA']['grace_mode'];
			$grace_date = LoginWithAjax::$data['2FA']['grace_date'];
			$grace_days = !empty(LoginWithAjax::$data['2FA']['grace_days']) && LoginWithAjax::$data['2FA']['grace_days'] > 0 ? absint(LoginWithAjax::$data['2FA']['grace_days']) : 0;
			$datetime = date_create( $grace_date, wp_timezone() );
			$datetime->add( new \DateInterval('P' . $grace_days . 'D') );
			if( $grace_mode == 1 ) {
				// user has to set up 2FA after cutoff date
				$timestamp = $datetime->getTimestamp();
			} elseif ( $grace_mode == 2 ) {
				// user has grace period from registration date, if later than grace_date
				$signup_date = get_user_meta( $user->ID, 'user_registered', true );
				$signup_datetime = date_create( $signup_date, wp_timezone() );
				$signup_datetime->add( new \DateInterval('P' . $grace_days . 'D') );
				$timestamp = $signup_datetime->getTimestamp();
			}
		}
		return $timestamp;
	}

	public static function authenticate( $user ){
		if( $user instanceof WP_User ) {
			$ready_methods = static::get_ready_methods( $user ); // this is to trigger the filter for is_setup_mandatory(
			$setup_mandatory = static::is_setup_mandatory( $user );
			$show_setup = !empty(LoginWithAjax::$data['2FA']['setup_show']);
			// if user has any 2FA methods set up, or setup is mandatory (grace period is over), we'll proceed to check if we need to request 2FA
			if( !empty($ready_methods) || $setup_mandatory || $show_setup ) {
				if( !empty($ready_methods) ) {
					// user has 2FA set up, so now we check if 2FA is actually required to log in
					$TwoFA = !empty( LoginWithAjax::$data['2FA'] ) ? LoginWithAjax::$data['2FA'] : array();
					if ( !empty( $TwoFA['when'] ) ) {
						if ( $TwoFA['when'] === '1' ) {
							$days = !empty( $TwoFA['days'] ) ? $TwoFA['days'] : 0;
							static::$force_authentication = true;
							// find the device and see if this is within days of allowed time without re-authentication, if not just require 2FA
							if ( !empty( $_COOKIE['lwa-2FA-id'] ) ) {
								$meta = LoginWithAjax::get_user_meta( $user->ID, '2FA[verification]' );
								if ( !empty( $meta['devices'][ $_COOKIE['lwa-2FA-id'] ] ) && $meta['devices'][ $_COOKIE['lwa-2FA-id'] ] > ( time() - ( DAY_IN_SECONDS * $days ) ) ) {
									static::$force_authentication = false;
								}
							}
						} elseif ( $TwoFA['when'] === '2' ) {
							static::$force_authentication = true;
						}
					}
				} elseif( $setup_mandatory ) {
					// no ready methods, force 2FA authentication (i.e. setup) if setup is mandatory
					static::$force_authentication = true;
				}
				// if authentication is required, check that we have an authentication method to either request from them, or one to make them set up
				$methods = static::get_available_methods( $user );
				if( static::$force_authentication || $show_setup ) {
					if( !empty($methods) ) {
						static::$authentication_required = $user;
						if ( static::$force_authentication ) {
							// user cannot proceed without verifying an enabled method, or setting up 2FA, cut-off the login now
							$user = new WP_Error( 'lwa_2FA_required', esc_html__( 'Further authentication required to log in.', 'login-with-ajax-pro' ) );
						} else {
							// user can proceed with login, but we'll need to trigger 2FA setup further on
							// add filter so that current page load will have working nonces
							add_action('set_logged_in_cookie', array( static::class, 'set_logged_in_cookie'), 999, 4);
						}
						// set a footer action in case we're not doing AJAX or using another login form
						add_action('wp_footer', array( static::class, 'authenticate_footer'));
					} elseif ( $setup_mandatory ) {
						// edge - user has no methods available to them, but setup is mandatory so user cannot proceed further.
						$user = new WP_Error( 'lwa_2FA_required', esc_html__( 'Account requires 2FA setup, but no methods are available for you, please contact an administrator to unlock your account.', 'login-with-ajax-pro' ) );
					}
				}
				
			}
		}
		return $user;
	}
	
	/**
	 * Intercepts set_logged_in_cookie and logs user in for current instance, so that generated nonces in response are valid for subsequent requests in the same page load.
	 * @param string $logged_in_cookie
	 * @return void
	 */
	public static function set_logged_in_cookie( $logged_in_cookie, $expire, $expiration, $user_id ){
		wp_set_current_user( $user_id );
		$_COOKIE[LOGGED_IN_COOKIE] = $logged_in_cookie;
	}
	
	/**
	 * @param array $response
	 * @param WP_User|WP_Error|mixed $loginResult
	 * @return array
	 */
	public static function login_response( $response, $loginResult ){
		if( static::$authentication_required !== false && static::$authentication_required instanceof WP_User ){
			$user = static::$authentication_required; /* @var WP_User $user */
			$ready_methods = static::get_ready_methods( $user );
			// prepare response to trigger modal
			$response['result'] = true; // we'll say it's true as this isn't really a complete fail, they logged in correctly
			$response['message'] = esc_html__('Please proceed with additional login verfication steps.', 'login-with-ajax-pro');
			$response['skip'] = true;
			// show setup or request 2FA
			if( !empty($ready_methods) ) {
				// get 2FA settings
				$TwoFA = LoginWithAjax::$data['2FA'];
				// add 2FA, prepare request in user record
				$uuid = wp_generate_uuid4();
				// add user meta - note that in the future we'll add methods when user requests a specific method to verify with
				$user_meta = LoginWithAjax::get_user_meta( $user->ID, '2FA[verification]' );
				if( !empty($user_meta) ) {
					if( empty( $user_meta['id']) ) $user_meta['id'] = $uuid;
					$user_meta['ts'] = time(); // reset time regardless
					if( empty($user_meta['methods']) ) $user_meta['methods'] = array();
				}else{
					$user_meta = array(
						'id' => $uuid,
						'ts' => time(),
						'methods' => array(),
					);
				}
				$meta = apply_filters('lwa_2FA_usermeta', $user_meta , $user);
				LoginWithAjax::update_user_meta( $user->ID, '2FA[verification]', $meta ); // overwrite any previous one
				// prepare response to trigger 2FA UI validation
				$methods = array();
				$default_method = false;
				foreach( $ready_methods as $method ) {
					// if method is of 'direct' verification type, we can already 'request' it so there's no delay on user side since they initiate everything
					$methods[ $method::$method ] = array();
					// add select text for options
					$methods[ $method::$method ] = $method::get_request_args($user);
					if( $methods[ $method::$method ]['result'] === false && $methods[ $method::$method ]['error_type'] === 'resend' ) {
						// this is due to a previously active session, therefore instead of an error, just output object to prevent resend on first selection to persist resend time
						$methods[ $method::$method ]['result'] = true;
						$methods[ $method::$method ]['error'] = '';
						$methods[ $method::$method ]['requested'] = true;
					}
					// if is default method (and available) set it as so
					if( $method::$method === $TwoFA['default'] ) {
						$default_method = $method::$method;
					}
				}
				$response['TwoFA'] = array(
					'id' => $user_meta['id'],
					'methods' => $methods,
					'user' => $_REQUEST['log'], // as submitted to avoid exposing extra info
					'nonce' => wp_create_nonce('2FA-'.$user_meta['id']),
					'timeout_time' => time() + static::$authentication_timeout,
					'timeout_error' => esc_html__('Your verification session has expired, please log in again.', 'login-with-ajax-pro'),
				);
				// in the event there's a default method, we'll include it in the response
				if( count($response['TwoFA']['methods']) === 1 ){
					$default_method = key($response['TwoFA']['methods']);
				} elseif ( !empty( $response['TwoFA']['methods'][$TwoFA['default']]) ) {
					$default_method = $TwoFA['default'];
				}
				if( $default_method ) {
					$method = static::$methods[$default_method];
					$response['TwoFA']['method'] = $default_method;
					// check if default method is 'requested', otherwise we need to request it and overwrite the argument
					if( !$methods[$default_method]['requested'] ) {
						$response['TwoFA']['methods'][$default_method] = $method::request( $user );
					}
				}
			} else {
				// If we got here, authenticate forced us to show a setup page to set up 2FA, optional or not, determined by either $TwoFA['setup_show'] || static::is_setup_mandatory( $user )
				// provide ajax link for setting up a new method, let user proceed with login
				$response['TwoFA']['setup_url'] = add_query_arg( array(
					'action' => 'lwa_2FA_setup',
					'log' => $_REQUEST['log'],
					'nonce' => wp_create_nonce('2FA-setup-'.$user->ID),
				), admin_url('admin-ajax.php') );
				$response['skip'] = true; // let JS handle statusElement after loading setup form
			}
			$response = apply_filters('lwa_2FA_login_response', $response, $user);
		}
		return $response;
	}
	
	public static function ajax( $response ){
		if( !empty($_REQUEST['2FA_request']) && !empty($_REQUEST['2FA']) ){
			$method = sanitize_key($_REQUEST['2FA']);
			if( !empty($_REQUEST['log']) && !empty($_REQUEST['2FA_id']) && wp_verify_nonce($_REQUEST['nonce'], '2FA-'.$_REQUEST['2FA_id']) ){
				$user = is_email($_REQUEST['log']) ? get_user_by('email', $_REQUEST['log']) : get_user_by('login', $_REQUEST['log']);
				if( $user instanceof WP_User ){
					$user_meta = LoginWithAjax::get_user_meta( $user->ID, '2FA[verification]' );
					if( !empty($user_meta['id']) && $user_meta['id'] === $_REQUEST['2FA_id'] ){
						// trigger it
						return static::request($method, $user);
					} // else : id/nonce doesn't match or not supplied
				} // else : username doesn't exist
			} // else : will return with an error
			$response = array( 'result' => false, 'error' => 'Missing data to process 2FA request.', 'message' => '', 'type' => '', );
			return LoginWithAjax::json_encode($response);
		}
		return static::verify( $response );
	}
	
	/**
	 * Verifies 2FA verification request. Responds with an LWA AJAX response. If 'restart' is set to true, it is expected to send error to the original login form for user to start again.
	 * @param array $response
	 * @return array
	 */
	public static function verify( $response ){
		$response['result'] = false; // guilty until proven innocent
		$response['2FA'] = 'request';
		$response['action'] = '2FA_verify';
		$admin_error = esc_html__('Please try again or contact an administrator if this issue persists.', 'login-with-ajax-pro');
		if( !empty($_REQUEST['log']) && !empty($_REQUEST['pwd']) && !empty($_REQUEST['2FA_id']) && !empty($_REQUEST['2FA']) ) {
			// first, get the usern
			$user = is_email($_REQUEST['log']) ? get_user_by('email', $_REQUEST['log']) : get_user_by('login', $_REQUEST['log']);
			if( $user instanceof WP_User ){
				// now get meta and compare codes
				$user_meta = LoginWithAjax::get_user_meta( $user->ID, '2FA[verification]' );
				if( !empty($user_meta['id']) && $user_meta['id'] === $_REQUEST['2FA_id'] ){
					// verify the timestamp to make sure we're within time
					if( $user_meta['ts'] > (time() - static::$authentication_timeout ) ){
						$response['error'] = esc_html__('Could not validate your verification method due to an unknown error.', 'login-with-ajax-pro') . ' ' . $admin_error;
						$response = apply_filters('lwa_2FA_verify_'.sanitize_key($_REQUEST['2FA']), $response, $user);
						if( $response['result'] === true ){
							// verified, log user in and proceed
							remove_all_filters('lwa_authenticate'); // prevent other authentication methods from blocking the 2FA success
							$response = LoginWithAjax::login(); // circumvent and get the regular login response as if a regular login was submitted
							$response['verified_TwoFA'] = true;
							if( empty($response['message']) ) {
								$response['message'] = esc_html__('Verification complete! Logging you in now...', 'login-with-ajax-pro');
							}
							// wrap things up... update and potentially delete the request session
							if( !empty(LoginWithAjax::$data['2FA']['when']) && LoginWithAjax::$data['2FA']['when'] === '1' ){
								// allow user to login without re-authenticating 2FA, set the cookie, clean up and save
								$uuid = wp_generate_uuid4();
								$days = !empty(LoginWithAjax::$data['2FA']['days']) ? LoginWithAjax::$data['2FA']['days']:0;
								$expiry = time() + DAY_IN_SECONDS * $days;
								setcookie('lwa-2FA-id', $uuid, $expiry , COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
								// first generate new id for device and clean up old timed-out devices
								if( empty($user_meta['devices']) ) $user_meta['devices'] = array();
								foreach( $user_meta['devices'] as $device_id => $device_ts ){
									if( (time() - (DAY_IN_SECONDS * $days)) > $device_ts ){
										unset($user_meta['devices'][$device_id]);
									}
								}
								$user_meta['devices'][$uuid] = time();
								unset( $user_meta['ts'], $user_meta['methods'], $user_meta['id'] );
								LoginWithAjax::update_user_meta( $user->ID, '2FA[verification]', $user_meta);
							}else{
								LoginWithAjax::delete_user_meta( $user->ID, '2FA[verification]');
							}
							do_action('lwa_2FA_authenticated', $user);
						}
					}else{
						$response['error'] = esc_html__('Your verification session has expired, please log in again.', 'login-with-ajax-pro');
						$response['restart'] = true;
					}
					// we're here, now pass it on for method verfiication
				}else{
					$response['error'] = esc_html__('Could not verify credentials.', 'login-with-ajax-pro') . ' ' . $admin_error;
					$response['restart'] = true;
				}
			}else{
				$response['error'] = esc_html__('Could not verify credentials.', 'login-with-ajax-pro') . ' ' . $admin_error;
				$response['restart'] = true;
			}
		} else {
			$response['error'] = esc_html__('Could not verify code, no valid ID provided.', 'login-with-ajax-pro') . ' ' . $admin_error;
			$response['restart'] = true;
		}
		return $response;
	}
	
	public static function request( $method, $user ){
		$response = array( 'result' => false, 'error' => '', 'message' => '', 'type' => $method, '2FA' => 'request', );
		if ( !empty(static::$methods[$method]) ) {
			$method = static::$methods[$method];
			$user_meta = LoginWithAjax::get_user_meta( $user->ID, '2FA[verification]' );
			// verify the timestamp to make sure we're within time
			if ( $user_meta['ts'] > ( time() - static::$authentication_timeout ) ) {
				$response = $method::request( $user );
			} else {
				$response['error'] = esc_html__( 'Your verification session has expired, please log in again.', 'login-with-ajax-pro' );
				$response['restart'] = true;
			}
		}
		return $response;
	}
	
	public static function footer(){
		if( did_action('lwa_2FA_form_footer') ) return; //only one modal needed
		$cancelled_msg = esc_html__('Verification process cancelled, please try again.', 'login-with-ajax-pro');
		$wp_login = did_action('login_head') && TwoFA::$authentication_required ? 1:0;
		?>
		<?php do_action('lwa_2FA_form_before'); ?>
		<div class="lwa-modal-overlay lwa-2FA-modal" id="lwa-2FA-modal" data-cancel-message="<?php echo esc_html($cancelled_msg); ?>" data-prevent-close="1" data-is-wp-login="<?php echo $wp_login; ?>">
			<div class="lwa-modal-popup">
				<?php include(LoginWithAjax::locate_template('2FA/modal.php')); ?>
			</div><!-- modal -->
		</div>
		<?php if( $wp_login ) : ?>
		<script type="application/json" id="lwa-login-json-response"><?php echo json_encode(TwoFA::login_response( array(), static::$authentication_required )); ?></script>
		<?php endif; ?>
		<?php do_action('lwa_2FA_form_after'); ?>
		<?php
	}
	
	public static function authenticate_footer(){
		// output some LWA script to trigger the 2FA modal as if user had logged in with given payload
		if( empty($_REQUEST['log']) ) {
			$_REQUEST['log'] = $_REQUEST['username'];
		}
		if( empty($_REQUEST['pwd']) ) {
			$_REQUEST['pwd'] = $_REQUEST['password'];
		}
		if( empty($_REQUEST['rememberme']) ) {
			$_REQUEST['rememberme'] = 0;
		}
		?>
		<form class="hidden lwa-form" id="lwa_2FA_footer_form">
			<span id="lwa_2FA_footer_form_status"></span>
			<input type="hidden" name="log" value="<?php echo esc_attr($_REQUEST['log']); ?>">
			<input type="hidden" name="pwd" value="<?php echo esc_attr($_REQUEST['pwd']); ?>">
			<input type="hidden" name="rememberme" value="<?php echo esc_attr($_REQUEST['rememberme']); ?>">
		</form>
		<script>
			document.addEventListener('lwa_2FA_loaded', function(){
				let response = <?php echo json_encode(TwoFA::login_response( array(), static::$authentication_required )); ?>;
				document.dispatchEvent( new CustomEvent('lwa_submit_login', {detail: {response: response, form: document.getElementById('lwa_2FA_footer_form'), statusElement: document.getElementById('lwa_2FA_footer_form_status')}}) );
				document.getElementById('lwa-2FA-modal').classList.add('active');
			});
		</script>
		<?php
	}
	
}
TwoFA::init();