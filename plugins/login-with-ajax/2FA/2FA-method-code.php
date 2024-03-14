<?php
namespace Login_With_AJAX\TwoFA\Method;
use Login_With_AJAX\TwoFA, LoginWithAjax, WP_User;
	
class Method_Code extends Method_Transport {
	
	/**
	 * @var string Method used to authorize, e.g. 'code' is for 2FA, email etc. and 'authorize' could be via a messaging app
	 */
	public static $verification = 'code';
	
	/**
	 * Extending classes must override this one.
	 * @return string
	 */
	public static function get_recipient_key() {
		return 'lwa_2FA_method_code';
	}
	
	public static function get_setup_description() {
		return sprintf( esc_html__('Verification codes are sent to your %s.', 'login-with-ajax'), static::get_name() );
	}
	
	public static function request( $user ){
		$response = parent::request( $user );
		if( $response['result'] ) {
			$code = static::generate_code( $user );
			$result = static::send_code( $user, $code );
			if ( is_wp_error($result) ) { /* @var \WP_Error $result */
				$response['error'] = $result->get_error_message();
			} elseif ( !$result ) {
				$response['error'] = sprintf( esc_html__('Could not verify with %s. Please try another method.', 'login-with-ajax-pro'), static::get_name() );
			} else {
				$response['result'] = true;
				if( $response['resent'] ){
					$response['message'] = static::get_text_resend( $user );
				}else {
					$response['message'] = static::get_text_request( $user );
				}
			}
		}
		return $response;
	}
	
	public static function get_setup_status_ready_text( $user ) {
		// check we also have a saved username and chat ID to send authorizations to
		$status = static::get_setup_status($user);
		if( $status === 'complete' ) {
			return sprintf( esc_html__('%s is active for %s.', 'login-with-ajax'), static::get_name(), static::transport()::get_recipient($user) );
		} elseif ( $status === 'waiting-'. static::$verification ) {
			return sprintf( esc_html__('Almost there! Check the code we sent to %s', 'login-with-ajax'), '<em>'.static::transport()::get_recipient($user).'</em>' );
		}
		return '';
	}
	
	public static function send_code ( WP_User $user, $code ) {
		return false;
	}
	
	public static function get_text_request( $user ) {
		$recipient = static::transport()::get_recipient( $user );
		$text = '<p>'.sprintf(esc_html__('Please enter the verification code that was sent to %s.', 'login-with-ajax-pro'), '<em><strong>' . static::mask( $recipient ) . '</strong></em>').'</p>';
		return $text;
	}
	
	public static function get_text_resend( $user ) {
		$recipient = static::transport()::get_recipient( $user );
		$text = '<p>' . sprintf( esc_html__('Code resent to %s.', 'login-with-ajax-pro'), '<em><strong>' . static::mask( $recipient ) . '</strong></em>') . '</p>';
		return $text;
	}
	
	/**
	 * Returns a generated code that is stored in the user meta for verification
	 * @param $user
	 *
	 * @return false|int
	 */
	public static function generate_code( $user ){
		// generate the code & save user meta first
		$code = rand(100000,999999);
		$meta = LoginWithAjax::get_user_meta( $user->ID, '2FA[verification]' );
		if( empty($meta['methods'][static::$method]) ) {
			$meta['methods'][static::$method] = array();
		}
		$meta['methods'][static::$method]['ts'] = time();
		$meta['methods'][static::$method]['code'] = $code;
		if( LoginWithAjax::update_user_meta( $user->ID, '2FA[verification]', $meta ) ){
			return $code;
		} else {
			return false;
		}
	}
	
	/**
	 * Assumes that $_REQUEST['2FA_code'] presence, general timeout etc. is checked before firing this filter.
	 * @param array $response
	 * @param WP_User $user
	 * @return array
	 */
	public static function verify( $response, $user ){
		$user_meta = LoginWithAjax::get_user_meta( $user->ID, '2FA[verification]' );
		if ( !empty($user_meta['methods'][static::$method]['code']) ) {
			if ( $user_meta['methods'][static::$method]['ts'] + static::$authentication_timeout > time() ) {
				if( $user_meta['methods'][static::$method]['code'] == $_REQUEST['2FA_code'] ){
					// verified!
					$response['result'] = true;
				}else{
					// invalid code
					$response['error'] = esc_html__('Incorrect verification code, please try again.', 'login-with-ajax-pro');
				}
			} else {
				// timeout
				$response['error'] = esc_html__('Code expired, please resend a verification code.', 'login-with-ajax-pro');
			}
		} else {
			// no valid method
			$response['result'] = false;
			$response['restart'] = true;
		}
		return $response;
	}
	
	public static function get_form_fields( $user = null ) {
		ob_start();
		?>
		<div class="lwa-2FA-code-input-wrap">
			<div class="lwa-2FA-code-input">
				<input type="text" name="2FA_code" inputmode="numeric" autocomplete="one-time-code" class="lwa-2FA-data-code" placeholder="<?php esc_html_e('Verification Code', 'login-with-ajax-pro'); ?>" style="">
				<button type="submit" class="button-primary u-full-width"><?php esc_html_e('Verify', 'login-with-ajax-pro'); ?></button>
			</div>
			<?php if ( static::$authentication_timeout > 0 ) : ?>
			<p class="lwa-2FA-verify-expires">
				<?php echo sprintf( esc_html__('The verification code expires in %s', 'login-with-ajax-pro'), '<span class="method-countdown">-</span>' ); ?>
			</p>
			<?php endif; ?>
			<p>
				<button class="lwa-2FA-resend u-full-width">
					<?php esc_html_e('Resend Code', 'login-with-ajax-pro'); ?> <span class="lwa-2FA-resend-timer" data-countdown="<?php echo esc_attr(static::$authentication_resend); ?>"></span>
				</button>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}
}