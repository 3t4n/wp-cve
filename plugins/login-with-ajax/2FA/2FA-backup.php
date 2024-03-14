<?php
namespace Login_With_AJAX\TwoFA\Method;

use Login_With_AJAX\QRCode;
use WP_User;

class Backup extends Method {
	
	public static $method = 'backup';
	public static $authentication_resend = false;
	
	public static $needs_setup = true;
	public static $svg_icon = '<svg version="1.1" id="Icons" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve" fill="#333333" stroke="#333333"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <style type="text/css"> .st0{fill:none;stroke:#333333;stroke-width:1.1199999999999999;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;} .st1{fill:none;stroke:#333333;stroke-width:1.1199999999999999;stroke-linejoin:round;stroke-miterlimit:10;} </style> <path class="st0" d="M20,17h-8c-1.1,0-2-0.9-2-2v-4c0-1.1,0.9-2,2-2h8c1.1,0,2,0.9,2,2v4C22,16.1,21.1,17,20,17z"></path> <path class="st0" d="M20,9h-8V5c0-2.2,1.8-4,4-4h0c2.2,0,4,1.8,4,4V9z"></path> <line class="st0" x1="16" y1="12" x2="16" y2="14"></line> <line class="st0" x1="16" y1="22" x2="16" y2="25"></line> <line class="st0" x1="13.1" y1="24.1" x2="16" y2="25"></line> <line class="st0" x1="14.2" y1="27.4" x2="16" y2="25"></line> <line class="st0" x1="17.8" y1="27.4" x2="16" y2="25"></line> <line class="st0" x1="18.9" y1="24.1" x2="16" y2="25"></line> <line class="st0" x1="26" y1="22" x2="26" y2="25"></line> <line class="st0" x1="23.1" y1="24.1" x2="26" y2="25"></line> <line class="st0" x1="24.2" y1="27.4" x2="26" y2="25"></line> <line class="st0" x1="27.8" y1="27.4" x2="26" y2="25"></line> <line class="st0" x1="28.9" y1="24.1" x2="26" y2="25"></line> <line class="st0" x1="6" y1="22" x2="6" y2="25"></line> <line class="st0" x1="3.1" y1="24.1" x2="6" y2="25"></line> <line class="st0" x1="4.2" y1="27.4" x2="6" y2="25"></line> <line class="st0" x1="7.8" y1="27.4" x2="6" y2="25"></line> <line class="st0" x1="8.9" y1="24.1" x2="6" y2="25"></line> </g></svg>';
	
	public static $js_init = false;
	
	public static function init ()  {
		parent::init();
		add_action('lwa_2FA_form_after', array( static::class, 'setup_js' ) );
	}
	
	public static function get_name () {
		return esc_html__('Backup Codes', 'login-with-ajax');
	}
	
	/**
	 * @return string
	 */
	public static function get_text_select( $user ){
		return esc_html__('Enter a backup code.', 'login-with-ajax-pro');
	}
	
	public static function get_text_request ( $user ) {
		return esc_html__('Enter a one-time use backup code provided to you previously.', 'login-with-ajax');
	}
	
	/**
	 * Overriden to add filter to child function.
	 * @param $user
	 *
	 * @return bool|mixed|null
	 */
	public static function is_ready( $user ) {
		$ready = parent::is_ready( $user );
		return apply_filters('lwa_2FA_method_backup_is_ready', $ready, $user);
	}
	
	/**
	 * Check that user has saved secret in user meta.
	 * @param $user
	 * @return bool|mixed|null
	 */
	public static function is_setup( $user ) {
		$setup = false;
		if( $user ) {
			$setup = !empty( static::get_codes( $user ) );
		}
		return apply_filters('lwa_2FA_method_backup_is_setup', $setup, $user);
	}
	
	/**
	 * Assumes that $_REQUEST['2FA_code'] presence, general timeout etc. is checked before firing this filter.
	 * @param array $response
	 * @param WP_User $user
	 * @return array
	 */
	public static function verify( $response, $user ){
		if( !empty($_REQUEST['2FA_code']) && preg_match('/^[0-9A-Za-z]{10}$/', $_REQUEST['2FA_code']) ) {
			$codes = self::get_codes( $user );
			if( isset($codes[$_REQUEST['2FA_code']]) ) {
				// check if code was used already
				if( $codes[$_REQUEST['2FA_code']] > 0 ) {
					$response['result'] = false;
					$response['error'] = esc_html__('Backup code already used, please try another.', 'login-with-ajax-pro');
				} else {
					$response['result'] = true;
					$methods = \LoginWithAjax::get_user_meta( $user->ID, '2FA[methods]' );
					$methods[ static::$method ]['codes'][$_REQUEST['2FA_code']] = time(); // mark code as used
					\LoginWithAjax::update_user_meta( $user->ID, '2FA[methods]', $methods );
				}
			} else {
				$response['result'] = false;
				$response['error'] = esc_html__('Invalid backup code, please try again.', 'login-with-ajax-pro');
			}
		} else {
			$response['result'] = false;
			$response['error'] = esc_html__('Incorrect verification code, please try again.', 'login-with-ajax-pro');
		}
		return $response;
	}
	
	public static function get_codes( $user ) {
		if( $user instanceof WP_User ) {
			$user_meta = \LoginWithAjax::get_user_meta( $user->ID, '2FA' );
			if ( !empty( $user_meta['methods'][ static::$method ]['codes'] ) ) {
				return $user_meta['methods'][ static::$method ]['codes'];
			}
		}
		return array();
	}
	
	public static function generate_codes( $user ) {
		$codes = array();
		for( $i = 0; $i < 10; $i++ ) {
			$code = substr( sha1(wp_generate_uuid4()), 0, 10 );
			$codes[$code] = 0;
		}
		$methods = \LoginWithAjax::get_user_meta( $user->ID, '2FA' );
		if( !is_array($methods) ) $methods = array(); // init if doesn't exist
		$methods['methods'][ static::$method ] = array(
			'enabled' => true,
			'codes' => $codes,
			'verified' => time(),
		);
		if ( \LoginWithAjax::update_user_meta( $user->ID, '2FA', $methods ) ) {
			return $codes;
		}
		return false;
	}
	
	public static function get_form_fields( $user = null ) {
		ob_start();
		?>
		<div class="lwa-2FA-code-input-wrap">
			<div class="lwa-2FA-code-input">
				<input type="text" name="2FA_code" inputmode="numeric" autocomplete="one-time-code" class="lwa-2FA-data-code" placeholder="<?php esc_html_e('Backup Code', 'login-with-ajax-pro'); ?>" style="">
				<button type="submit" class="button-primary u-full-width"><?php esc_html_e('Verify', 'login-with-ajax-pro'); ?></button>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	
	/* Setup Function */
	
	public static function get_setup_description () {
		return esc_html__('Use generated backup codes generated during setup.', 'login-with-ajax');
	}
	
	public static function get_setup_status_ready_text ( $user ) {
		$codes = static::get_codes( $user );
		$used = 0;
		foreach ( $codes as $code => $time ) {
			if( $time > 0 ) $used++;
		}
		return sprintf( esc_html__('Backup codes are available (%1$d out of %2$d used).', 'login-with-ajax'), $used, count($codes) );
	}
	
	public static function setup ( $user, $single = false ) {
		if( !$single ) {
			echo '<h4>'. esc_html__('Recovery Options', 'login-with-ajax-pro') .'</h4>';
		}
		parent::setup( $user, $single);
	}
	
	public static function get_setup_form ( $user ) {
		$codes = self::get_codes( $user );
		?>
		<div class="lwa-2FA-method-new-codes hidden">
			<p><strong><?php esc_html_e('Here are your new backup codes, copy them somewhere safe for future use.', 'login-with-ajax'); ?></strong></p>
			<div id="lwa-2FA-method-backup-codes"></div>
		</div>
		<div class="lwa-2FA-method-verification">
			<?php if( !empty($codes) ) : ?>
				<p><strong><?php esc_html_e('Backup codes are not displayed for security purposes, you can regenerate your backup codes again if necessary.', 'login-with-ajax'); ?></strong></p>
			<?php else: ?>
				<p><strong><?php esc_html_e('Generate new backup codes, they will be displayed here once for you to copy, and will then remain hidden until regenerated.', 'login-with-ajax'); ?></strong></p>
			<?php endif; ?>
			<div class="setup-verify-form backup-codes-generate">
				<input data-name="nonce"  type="hidden" value="<?php echo wp_create_nonce('2FA-setup-verify-' . static::$method . '-' . $user->ID); ?>" class="setup-verify-field">
				<input data-name="log"    type="hidden" value="<?php echo esc_attr($user->user_login); ?>" class="setup-verify-field">
				<button class="setup-verify-button setup-backup-codes-generate" data-txt="<?php esc_attr_e('Generating Codes...', 'login-with-ajax'); ?>"><?php esc_html_e('Generate Backup Codes', 'login-with-ajax'); ?></button>
				<?php echo static::get_setup_status_reset_button(); ?>
			</div>
			<mark class="error"></mark>
		</div>
		<?php
		static::setup_js();
	}
	
	public static function setup_js() {
		if( static::$js_init ) return;
		?>
		<script>
			// listen to lwa_2FA_setup_verified event
			document.addEventListener('DOMContentLoaded', function(){
				document.addEventListener('click', function( e ){
					if( e.target.matches('button.setup-backup-codes-generate') ) {
						document.getElementById('lwa-2FA-method-backup-codes').parentElement.classList.add('hidden');
					}
				});
				// listen for verified hook, display codes if verified
				document.addEventListener('lwa_2FA_setup_verified', function( e ){
					if ( e.detail.method === 'backup' && e.detail.response.result ) {
						// generate a div structure of codes, one div for each code
						let codes = e.detail.response.codes;
						let codes_div = document.getElementById('lwa-2FA-method-backup-codes');
						codes_div.closest('.lwa-2FA-method').querySelector('footer').appendChild(codes_div.parentElement);
						codes_div.innerHTML = '';
						for( let code in codes ) {
							let code_div = document.createElement('div');
							code_div.innerHTML = code;
							codes_div.appendChild(code_div);
						}
						codes_div.parentElement.classList.remove('hidden');
					}
				});

			});
		</script>
		<?php
		static::$js_init = true;
	}
	
	
	public static function setup_verify( $user ) {
		// check secret against supplied code in $_REQUEST, if authentication is valid, save secret to user meta and confirm verification setup, otherwise return error
		$meta = \LoginWithAjax::get_user_meta( $user->ID, '2FA' );
		if( !is_array($meta) ) $meta = array(); // init if doesn't exist
		$codes = self::generate_codes( $user );
		if ( $codes !== false ) {
			return array(
				'result'  => true,
				'message' => static::get_setup_status_ready_text($user),
				'status' => static::get_setup_status( $user ),
				'verification_method ' => static::$verification,
				'codes' => $codes,
			);
		}
		return array(
			'result' => false,
			'error' => esc_html__('No new codes generated, please try again.', 'login-with-ajax-pro'),
		);
	}
}
add_action('lwa_2FA_loaded', '\Login_With_AJAX\TwoFA\Method\Backup::init');