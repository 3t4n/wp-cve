<?php
namespace Login_With_AJAX\TwoFA\Method;

class Method_Transport extends Method {
	
	/** @var \Login_With_AJAX\Transports\Transport */
	protected static $transport = '\Login_With_AJAX\Transports\Transport';
	
	protected static $js_inited = false;
	
	public static $authentication_timeout = 120;
	
	public static function init() {
		parent::init();
		add_action('lwa_2FA_form_after', array( static::class, 'get_setup_authorization_js' ) );
	}
	
	/**
	 * @return \Login_With_AJAX\Transports\Transport
	 */
	public static function transport () {
		return static::$transport;
	}
	
	public static function is_setup( $user ) {
		$setup_key = static::transport()::get_recipient_key();
		$setup = get_user_meta( $user->ID, $setup_key, true );
		return apply_filters('lwa_2FA_method_authorize_is_setup', $setup, $user);
	}
	
	public static function is_setup_complete( $user ) {
		$verified = !static::$needs_setup;
		if( static::$needs_setup ) {
			$is_verified = \LoginWithAjax::get_user_meta( $user->ID, static::$method .'[verified]' );
			// determine if to return null i.e. setup completion not initiated, or false/true if setup is pending
			$verified = $is_verified === null ? null : !empty($is_verified);
		}
		return apply_filters('lwa_2FA_method_authorize_is_setup_complete', $verified, $user);
	}
	
	/**
	 * @return string
	 */
	public static function get_text_select( $user ){
		$recipient = static::mask( static::transport()::get_recipient($user) );
		return sprintf( esc_html__('Authenticate via %s for %s', 'login-with-ajax-pro'), static::get_name(), $recipient );
	}
	
	public static function request ( $user ) {
		$response = parent::request( $user );
		if ( static::$authentication_timeout > 0 ) {
			$response['text']['timeout'] = esc_html__('Your verification time has expired, please resend again.', 'login-with-ajax-pro');
		}
		return $response;
	}
	
	public static function get_setup_status_ready_text( $user ) {
		// check we also have a saved username and chat ID to send authorizations to
		$status = static::get_setup_status($user);
		if( $status === 'complete' ) {
			return sprintf( esc_html__('%1$ s is active for %2$s.', 'login-with-ajax'), static::get_name(), static::transport()::get_recipient($user) );
		} elseif ( $status === 'waiting-'. static::$verification ) {
			return sprintf( esc_html__('Almost there! Check the message we sent to %s', 'login-with-ajax'), '<em>'.static::transport()::get_recipient($user).'</em>' );
		}
		return '';
	}
	
	public static function setup_save( $settings, $user ) {
		$settings = parent::setup_save( $settings, $user );
		if( empty($settings['enabled']) ) {
			// remove verified keys too, leave recipient in case it's shared
			\LoginWithAjax::delete_user_meta( $user->ID, static::transport()::$method . '[verified]' );
		}
		return $settings;
	}
	
	public static function get_setup_authorization_js(){
		if( self::$js_inited ) return;
		self::$js_inited = true;
		?>
		<script>
			// listen to lwa_2FA_setup_verified event
			document.addEventListener('DOMContentLoaded', function(){
				// add auto-verifier if this is an authorization method rather than code
				let verifier = function ( method, method_select ) {
					let data = { 'method' : method, 'action' : 'lwa_2FA_setup_verify' };
					method_select.querySelectorAll('[data-name]').forEach( function( el ) {
						data[el.getAttribute('data-name')] = el.value;
					});
					data.context = 'verify_authenticate';
					let verify_url = method_select.closest('[data-verify-url]').getAttribute('data-verify-url');
					jQuery.ajax( {
						url : verify_url,
						method : 'post',
						data : data,
						dataType : 'json',
						success : function ( response ) {
							if( response.result ) {
								if ( !response.verified ) {
									if( response.verification_method === 'authorize' ) {
										// check again in 5 seconds
										setTimeout(verifier, 5000, method, method_select);
									}
								} else {
									method_select.setAttribute( 'data-status', response.status );
									method_select.querySelector('.lwa-2FA-method-status mark').innerHTML = response.message;
								}
							}
						},
						error : function( response, status ){
							console.log('Error in AJAX 2FA setup with status %s for %o', status, response);
						},
					}, data );
				};
				// reset waiting button cancel clicks
				document.querySelectorAll('[data-verification="code"] .setup-verify-button-code-cancel').forEach( function( el ) {
					el.addEventListener('click', function (e) {
						e.preventDefault();
						this.closest('.lwa-2FA-method').removeAttribute('data-status');
					});
				});
				// listen for verified hook
				document.addEventListener('lwa_2FA_setup_verified', function( e ){
					if ( e.detail.response.result && !e.detail.response.verified ) {
						if ( e.detail.response.verification_method === 'authorize' ) {
							setTimeout(verifier, 5000, e.detail.method, e.detail.method_select);
						}
					}
				});
				
			});
		</script>
		<?php
	}
}