<?php
namespace Login_With_AJAX\TwoFA\Method;

use Login_With_AJAX\TwoFA, LoginWithAjax, WP_User;
	
class Method {
	/**
	 * @var string The type of authentication method this class defines.
	 */
	public static $method = 'method';
	/**
	 * @var string Method used to authorize, e.g. 'direct' would be for challenges such as TOTP or HOTP, 'code' is for email, SMS etc. and 'authorize' could be via a messaging app
	 */
	public static $verification = 'direct';
	/**
	 * @var int Seconds until a new code or auth method must be regernated
	 */
	public static $authentication_timeout;
	/**
	 * @var int Seconds to wait in between triggering authentication, such as sending codes
	 */
	public static $authentication_resend = 60;
	/**
	 * @var bool Does the user need to set up this method in some way before it can be used? If not, then set to false and it will start working when activated.
	 */
	public static $needs_setup = false;
	
	public static $svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#000" stroke-width=".00024" viewBox="0 0 24 24"><path fill="#545454" fill-rule="evenodd" d="m12 3.73169 7.5 1.66667V12.75c0 2.8871-1.9581 6.2472-7.2395 8.2033L12 21.0498l-.2605-.0965C6.45811 18.9972 4.5 15.6371 4.5 12.75V5.39836L12 3.73169ZM6 6.60161V12.75c0 2.0745 1.3659 4.8981 6 6.6979 4.6341-1.7998 6-4.6234 6-6.6979V6.60161l-6-1.33333-6 1.33333Z" clip-rule="evenodd"/></svg>';
	
	public static function init() {
		add_filter('lwa_2FA_request_'.static::$method,  array( static::class, 'request' ), 10, 3);
		add_action('lwa_2FA_verify_'.static::$method,  array( static::class, 'verify' ), 10, 2);
		TwoFA::register_method( static::class );
	}
	
	public static function get_name() {
		return ucfirst( static::$method );
	}
	
	/**
	 * Checks if method is enabled for use and available to this specific user. This does not necessarily mean that the method has been set up by the user and is ready to be used.
	 *
	 * @param $user
	 * @return bool
	 */
	final public static function is_available( $user = null ) {
		$is_installed = static::is_installed();
		$registered = !empty(LoginWithAjax::$data['2FA']['methods'][static::$method]);
		$generally_available = $is_installed && $registered;
		// TODO check if method is available to user based on specific requirements (role etc.)
		return apply_filters('lwa_2FA_method_is_available', $generally_available, $user);
	}
	
	/**
	 * Checks if the method is registered, installed and ready for setup by a user, or if a user is supplied it also checks that the user has correctly set it up and enabled the method for use.
	 *
	 * If a child class overrides this method and does NOT call the parent method, it should also apply the base method filter to ensure compatibility with other features.
	 *
	 * @param WP_User $user
	 * @return bool
	 */
	public static function is_ready( $user ) {
		$ready = static::is_available( $user ) && static::is_installed() && static::is_enabled($user) && static::is_setup_complete($user);
		return apply_filters('lwa_2FA_method_is_ready', $ready, $user);
	}
	
	/**
	 * Checks if method is enabled for this specific user. This does not necessarily mean that the method has been set up by the user and is ready to be used.
	 *
	 * If a child class overrides this method and does NOT call the parent method, it should also apply the base method filter to ensure compatibility with other features.
	 *
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	public static function is_enabled ( $user ) {
		$user_meta = LoginWithAjax::get_user_meta( $user->ID, '2FA' );
		$enabled = !empty($user_meta['methods'][static::$method]['enabled']);
		return apply_filters('lwa_2FA_method_is_enabled', $enabled, $user);
	}
	
	/**
	 * Checks that the required information by a user (phone number, secret for TOTP, etc.) is correctly set up. This does not confirm that the information is verified, for that you can use is_setup_complete()
	 *
	 * If a child class overrides this method and does NOT call the parent method, it should also apply the base method filter to ensure compatibility with other features.
	 *
	 * @param $user
	 *
	 * @param WP_User $user
	 *
	 * @return bool
	 */
	public static function is_setup( $user ) {
		return apply_filters('lwa_2FA_method_is_setup', true, $user);
	}
	
	/**
	 * Checks that the user information is setup AND verified, for example, a phone number or email could be verified additionally via SMS code challenge.
	 * Without a transport, this function should just produce the same result as is_setup()
	 *
	 * @param $user
	 *
	 * @return boolean|null
	 */
	public static function is_setup_complete( $user ) {
		return apply_filters('lwa_2FA_method_is_setup_complete', static::is_setup($user), $user);
	}
	
	/**
	 * Gets a status string for the method, if the method requires verification via is_setup_complete() then methods will return a 'waiting' status, otherwise a 'complete' or 'incomplete' value will be returned.
	 * @param $user
	 *
	 * @return mixed|null
	 */
	public static function get_setup_status( $user ) {
		$setup = static::is_setup( $user );
		$complete = static::is_setup_complete( $user );
		$status = $setup && $complete ? 'complete' : 'not-started';
		if( $setup && $complete === false ) $status = 'waiting-' . static::$verification; // not null, which means inomplete
		return apply_filters('lwa_2FA_method_get_setup_status', $status, $user);
	}
	
	/**
	 * Checks if method is installed by admins (such as valid API for service) and can be used.
	 *
	 * If a child class overrides this method and does NOT call the parent method, it should also apply the base method filter to ensure compatibility with other features.
	 *
	 * @return bool
	 */
	public static function is_installed() {
		return apply_filters('lwa_2FA_method_is_installed', true);
	}
	
	/**
	 * @param $field
	 * @param $default
	 *
	 * @return mixed|null
	 */
	public static function get_data( $field = null, $default = null ){
		if( !empty(LoginWithAjax::$data['2FA'][static::$method]) ) {
			$data = LoginWithAjax::$data['2FA'][static::$method];
			if( $field && isset($data[$field]) ) {
				return $data[$field];
			}
			return $data;
		}
		return $default;
	}
	
	/**
	 * @return string
	 */
	public static function get_text_select( $user ){
		return sprintf( esc_html__('Authenticate via %s', 'login-with-ajax-pro'), static::get_name() );
	}
	
	public static function get_text_request( $user ) {
		return false;
	}
	
	/**
	 * Returns initial request arguments for this method. If the method requires some sort of step requuring processing to send a code or any other logic (SMS, API, etc)
	 * This function should provide basic info required for the 2FA interface to initiate a request when this method is selected.
	 * Methods that do not require any processing to send a code (such as TOTP) should instead provide a direct request result, i.e. all the information needed for the
	 * 2FA inerface to display the verification UI.
	 * @param $user
	 *
	 * @return array
	 */
	public static function get_request_args( $user = null ) {
		$request_args = self::request( $user );
		if( static::$verification !== 'direct' ) {
			$request_args['result'] = false;
			$request_args['requested'] = false;
		}
		return $request_args;
	}
	
	/**
	 * @param WP_User $user User account related to this request.
	 * @param bool $resend  If this is a resend request, set to true.
	 *
	 * @return array
	 */
	public static function request( $user ){
		$response = array( 'result' => true, 'error' => '', 'message' => '', 'type' => static::$method, '2FA' => 'request', 'requested' => true, 'resent' => false, 'resend' => false, 'verification' => static::$verification, 'error_type' => null );
		if( $user instanceof WP_User ){
			if ( static::$authentication_resend ) {
				// verify if resend timestamp has passed, if not then decline request
				$meta = LoginWithAjax::get_user_meta( $user->ID, '2FA[verification]' );
				if( !empty($meta['methods'][static::$method]['ts']) ) { // user has an active session
					$resend_ts = $meta['methods'][static::$method]['ts'];
					if( $resend_ts + static::$authentication_resend > time() ) {
						$response['result'] = false;
						$response['error_type'] = 'resend';
						$response['error'] = esc_html__('Please allow %s before requesting a new code.', 'login-with-ajax');
						$response['error'] = sprintf( $response['error'], human_time_diff( $resend_ts + static::$authentication_resend, time() ) );
					} else {
						$resend_ts = false;
						$response['resent'] = true; // assumes this goes through, overriding method should set false on error
					}
				}
				$response['resend'] = empty($resend_ts) ? static::$authentication_resend + time() : static::$authentication_resend + $resend_ts; // overriding functions should reset this timer
			}
			$response['text'] = array(
				'form' => static::get_text_request( $user ),
				'select' => static::get_text_select( $user ),
			);
			if ( static::$authentication_timeout > 0 ) {
				$response['timeout'] = static::$authentication_timeout + time();
				$response['text']['timeout'] = esc_html__('Your verification time has expired, please try again.', 'login-with-ajax-pro');
			}
		} else {
			$response['result'] = false;
			$response['error'] = 'Invalid user data supplied, cannot proceed with request, contact support for assistance if problem persists.'; // not transltaed, edge case
		}
		return $response;
	}
	
	/**
	 * Verifies the response provided depending on transport method used.
	 * @param array $response
	 * @param WP_User $user
	 * @return array
	 */
	public static function verify( $response, $user ){
		// no valid method
		$response['result'] = false;
		$response['restart'] = true;
		return $response;
	}
	
	/**
	 * Output method for a given user
	 * @param $user
	 *
	 * @return false|string
	 */
	public static function get_form( $user = null ) {
		ob_start();
		?>
		<form action="<?php echo LoginWithAjax::get_login_url(); ?>" method="post" class="lwa-2FA-method lwa-2FA-method-<?php echo esc_attr(static::$method); ?>" data-method="<?php echo esc_attr(static::$method); ?>"  style="<?php if( static::$svg_icon ) echo "--2FA-icon: url('data:image/svg+xml;base64," . base64_encode(static::$svg_icon) . "');"; ?>">
			<div class="lwa-2FA-message"></div>
			<div class="lwa-2FA-formdata">
				<?php
				if( did_action('login_head') && TwoFA::$authentication_required ){
					foreach( $_REQUEST as $k => $v ){
						echo '<input type="hidden" name="'.$k.'" value="'.esc_attr($v).'">';
					}
				}
				?>
			</div>
			<input type="hidden" name="2FA_id" class="lwa-2FA-data-id">
			<input type="hidden" name="2FA" value="<?php echo esc_attr(static::$method); ?>" class="lwa-2FA-data-type">
			<input type="hidden" name="login-with-ajax" value="2FA">
			<?php echo static::get_form_fields( $user ); ?>
			<?php /* if( $TwoFA['when'] === '1' && !empty($TwoFA['days']) ): //for future consideration ?>
			<p>
				<label style="font-weight:normal !important;">
					<input type="checkbox" name="2FA_remember" value="1" class="lwa-rememberme" checked>
					<?php esc_html_e('Remember this device', 'login-with-ajax-pro'); ?>
				</label>
			</p>
			<?php endif; */ ?>
		</form>
		<?php
		return ob_get_clean();
	}
	
	public static function get_form_fields() {
		return '';
	}
	
	// Utilities
	public static function mask($str, $first = 3, $last = 2) {
		$len = strlen($str);
		$toShow = $first + $last;
		return substr($str, 0, $len <= $toShow ? 0 : $first).str_repeat("*", $len - ($len <= $toShow ? 0 : $toShow)).substr($str, $len - $last, $len <= $toShow ? 0 : $last);
	}
	
	public static function user_profile( $user ) {
		?>
		<div class="lwa-2FA-method">
			<div class="lwa-2FA-method-title"><?php echo static::get_name(); ?></div>
			<div class="lwa-2FA-method-content">
				<?php if( static::is_available( $user ) ) : ?>
				<a href="#" class="lwa-2FA-trigger button button-primary"><?php esc_html_e('Activate', 'login-with-ajax'); ?></a>
				<?php else : ?>
				<a href="#" class="lwa-2FA-trigger button button-secondary"><?php esc_html_e('Deactivate', 'login-with-ajax'); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Verifies a $_REQUEST to ensure the 2FA method can be used for given user, and should store any relevant meta as well.
	 * Return can either be a boolean or alternatively a response array with the following keys:
	 *  result: boolean, true if verification was successful
	 *  error: string, error message if verification was unsuccessful
	 *  reset: string, reset button text if verification was successful and a reset button text is needed
	 *  message: string, success message if verification was successful
	 *
	 * @param WP_User $user
	 *
	 * @return false|array
	 */
	public static function setup_verify( $user ) {
		return false;
	}
	
	/**
	 * Saves settings for enabling/disabling a 2FA method. Must return settings for further saving.
	 * @param array $settings
	 * @param WP_User $user
	 *
	 * @return array
	 */
	public static function setup_save( $settings, $user ) {
		if( empty($settings['enabled']) ) {
			// clear the settings
			return array( 'enabled' => false );
		}
		return $settings;
	}
	
	public static function get_setup_form( $user ) {
		return '';
	}
	
	public static function get_setup_text() {
		return static::get_name();
	}
	
	public static function get_setup_description() {
		return sprintf( esc_html__('Enable %s for your account.', 'login-with-ajax'), static::get_name() );
	}
	
	public static function get_setup_status_ready_text( $user ) {
		if( static::is_setup_complete( $user ) ) {
			return sprintf( esc_html__('%s is active for your account.', 'login-with-ajax'), static::get_name() );
		}
		return '';
	}
	
	public static function get_setup_status_reset_button() {
		ob_start();
		$text = esc_html__( 'Modify', 'login-with-ajax' );
		?>
		<button type="button" class="lwa-2FA-method-setup-reset" data-cancel-txt="<?php esc_html_e('Cancel', 'login-with-ajax'); ?>" data-modify-txt="<?php echo $text; ?>"><?php echo $text; ?></button>
		<?php
		return ob_get_clean();
	}
	
	public static function get_setup_status_invalid_text() {
		return sprintf( esc_html__('%s is not set up correctly.', 'login-with-ajax'), static::get_name() );
	}
	
	public static function setup ( $user, $single = false ) {
		// determine if this method is enabled
		$enabled = static::is_enabled( $user );
		$classes = array();
		if( $enabled ) $classes[] = 'enabled';
		if( $single ) $classes[] = 'lwa-2FA-method-required';
		?>
		<div class="lwa-2FA-method <?php echo esc_attr(implode(' ', $classes)); ?>" data-status="<?php echo esc_attr(static::get_setup_status($user)); ?>" data-method="<?php echo esc_attr(static::$method); ?>" data-verification="<?php echo static::$verification; ?>" data-needs-setup="<?php echo static::$needs_setup ? 1 : 0 ?>" style="<?php if( static::$svg_icon ) echo "--2FA-icon: url('data:image/svg+xml;base64," . base64_encode(static::$svg_icon) . "');"; ?>">
			<input type="hidden" name="lwa_2FA[]" value="1">
			<header>
				<?php static::setup_header($user, !$single ); ?>
			</header>
			<?php if ( static::$needs_setup ) : ?>
				<footer class="lwa-2FA-method-content">
					<?php static::setup_footer( $user ); ?>
				</footer>
			<?php endif; ?>
		</div>
		<?php
	}
	
	public static function setup_header( $user, $show_switch = true ) {
		$enabled = static::is_enabled( $user );
		?>
		<div class="lwa-2FA-method-title">
			<div>
				<?php echo esc_html( static::get_setup_text() ); ?>
			</div>
			<?php if( $show_switch ): ?>
				<label class="lwa-switch">
					<span class="label"><?php echo esc_html( static::get_setup_text() ); ?></span>
					<input type="checkbox" class="lwa-2FA-method-enable" name="lwa_2FA[<?php echo esc_attr(static::$method); ?>]" value="1" id="lwa-2FA-method-<?php echo esc_attr(static::$method); ?>" <?php if ( $enabled ) echo 'checked';?>>
					<span class="lwa-switch-slider"></span>
				</label>
			<?php else : ?>
				<input type="hidden" name="lwa_2FA[<?php echo esc_attr(static::$method); ?>]" value="1">
			<?php endif; ?>
		</div>
		<div class="lwa-2FA-method-desc">
			<?php echo esc_html( static::get_setup_description() ); ?>
		</div>
		<?php
	}
	
	public static function setup_footer( $user ) {
		?>
		<div class="lwa-2FA-method-status">
			<mark>
				<?php echo static::get_setup_status_ready_text($user); ?>
			</mark>
			<?php echo static::get_setup_status_reset_button(); ?>
		</div>
		<div class="lwa-2FA-method-setup">
			<?php static::get_setup_form( $user ); ?>
		</div>
		<?php
	}
}