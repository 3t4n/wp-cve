<?php
namespace Login_With_AJAX\TwoFA;

use Login_With_AJAX\TwoFA;

class Admin {
	public static function init(){
		$class = get_called_class();
		add_action('lwa_settings_security', '\\'.$class.'::admin_settings', 12);
	}
	
	public static function admin_settings(){
		$lwa = get_option('lwa_data', array());
		$TwoFA = !empty($lwa['2FA']) ? $lwa['2FA']: array('enabled' => false);
		$docs_link = '<a href="https://docs.loginwithajax.com/security/2FA/" target="_blank">'. esc_html__('documentation','login-with-ajax-pro') .'</a>';
		?>
		<h3><?php esc_html_e("2-Factor Authentication (2FA)", 'login-with-ajax'); ?></h3>
		<p><em><?php echo sprintf(esc_html__("Enable 2-Factor Authentication and require users to provide a verification code once successfully logging in. For more information and recommendations please see our %s page.", 'login-with-ajax-pro'), $docs_link); ?></em></p>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label><?php esc_html_e("Enable 2FA", 'login-with-ajax-pro'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="lwa_2FA[enabled]" id="lwa_2FA_enable" value='1' <?php echo ( !empty($TwoFA['enabled']) ) ? 'checked':''; ?> >
				</td>
			</tr>
			<tbody class="lwa-settings-2FA">
				<tr valign="top">
					<th scope="row">
						<label><?php esc_html_e("When to require 2FA", 'login-with-ajax-pro'); ?></label>
					</th>
					<td>
						<?php $when = !empty($TwoFA['when']) ? $TwoFA['when']:''; ?>
						<select name="lwa_2FA[when]" id="lwa_2FA_when">
							<option value="0" <?php if( $when === '0' ) echo 'selected'; ?>><?php esc_html_e('Triggered only by other features', 'login-with-ajax-pro'); ?></option>
							<option value="1" <?php if( $when === '1' ) echo 'selected'; ?>><?php esc_html_e('Every x days', 'login-with-ajax-pro'); ?></option>
							<option value="2" <?php if( $when === '2' ) echo 'selected'; ?>><?php esc_html_e('Always', 'login-with-ajax-pro'); ?></option>
						</select>
						<p><em><?php esc_html_e("You can choose to require 2FA independently or allow other deciding factors such as Recaptcha v3 or limited login attempts to require a 2FA verification.", 'login-with-ajax-pro'); ?></em></p>
						<?php if(  !defined('LWA_PRO_VERSION') && (!defined('LWA_REMOVE_PRO_NAGS') || !LWA_REMOVE_PRO_NAGS)  ): ?>
							<p><em><?php echo sprintf(esc_html__('Other security features are available as part of our Pro plugin - %s', 'login-with-ajax'), '<a href="https://loginwithajax.com/gopro/" target="_blank">'. esc_html__('Go Pro!', 'login-with-ajax') . '</a>'); ?></em></p>
						<?php endif; ?>
					</td>
				</tr>
				<tr valign="top" class="lwa-settings-2FA-when-days">
					<th scope="row">
						<label><?php esc_html_e("Require 2FA every", 'login-with-ajax-pro'); ?></label>
					</th>
					<td>
						<input type="text" name="lwa_2FA[days]" value='<?php echo ( !empty($TwoFA['days']) ) ? esc_attr($TwoFA['days']):'30'; ?>' size='3'> <?php esc_html_e('days'); ?>
						<p><em><?php echo esc_html__("When a user successfully completes a 2FA verification, they will not be asked again for x days on that device.", 'login-with-ajax-pro'); ?></em></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label><?php esc_html_e("2FA Methods", 'login-with-ajax-pro'); ?></label>
					</th>
					<td>
						<?php foreach( TwoFA::$methods as $method ): ?>
						<label>
							<input type="checkbox" class="lwa-2FA-method" data-method="<?php echo esc_attr($method::$method); ?>" name="lwa_2FA[methods][<?php echo esc_attr($method::$method); ?>]" value='1' <?php echo ( !empty($TwoFA['methods'][$method::$method]) || count(TwoFA::$methods) === 1 ) ? 'checked':''; ?>  <?php if( count(TwoFA::$methods) === 1 ) echo 'disabled'; ?>>
							<?php if( count(TwoFA::$methods) === 1 ): ?>
								<input type="hidden" name="lwa_2FA[methods][<?php echo esc_attr($method::$method); ?>]" value='1'>
							<?php endif; ?>
							<?php echo $method::get_name(); ?>
						</label>
						<br>
						<?php endforeach; ?>
						<p><em><?php esc_html_e('Choose the 2FA methods you would like to enable for this site. If more than one option is enabled and applicable to the user, verification method can be changed during login.', 'login-with-ajax-pro'); ?></em></p>
						<?php if(  !defined('LWA_PRO_VERSION') && (!defined('LWA_REMOVE_PRO_NAGS') || !LWA_REMOVE_PRO_NAGS)  ): ?>
							<p><em><?php echo sprintf(esc_html__('Additional 2FA methods are available as part of our Pro plugin - %s', 'login-with-ajax'), '<a href="https://loginwithajax.com/gopro/" target="_blank">'. esc_html__('Go Pro!', 'login-with-ajax') . '</a>'); ?></em></p>
						<?php endif; ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label><?php esc_html_e("Default 2FA Method", 'login-with-ajax-pro'); ?></label>
					</th>
					<td>
						<select name="lwa_2FA[default]">
							<?php if( count(TwoFA::$methods) > 1 ) : ?>
							<option value='0' <?php echo ( empty($TwoFA['default']) ) ? 'selected':''; ?> ><?php esc_html_e('None', 'login-with-ajax-pro'); ?></option>
							<?php endif; ?>
							<?php foreach( TwoFA::$methods as $method ): ?>
								<option value='<?php echo esc_attr($method::$method); ?>' <?php echo ( !empty($TwoFA['methods'][$method::$method]) || count(TwoFA::$methods) === 1 ) ? 'checked':''; ?>>
									<?php echo $method::get_name(); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p><em><?php esc_html_e("This method, if enabled and available to the user, will be selected by default or will otherwise default to 'None'. If 'None' is selected the user will be asked first to select a verification method after a successful login.", 'login-with-ajax-pro'); ?></em></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label><?php esc_html_e("Show 2FA Setup on Login?", 'login-with-ajax-pro'); ?></label>
					</th>
					<td>
						<?php $TwoFA['setup_login'] = empty($TwoFA['setup_login']) && empty($TwoFA['grace_mode']) ? 1 : 0; ?>
						<select name="lwa_2FA[setup_show]">
							<option value='0' <?php selected($TwoFA['setup_login'], 0); ?> ><?php esc_html_e('No', 'login-with-ajax-pro'); ?></option>
							<option value='1' <?php selected($TwoFA['setup_login'], 1); ?> ><?php esc_html_e('Yes', 'login-with-ajax-pro'); ?></option>
						</select>
						<p><em><?php esc_html_e("If 2FA is not required, you can choose not to show the 2FA setup screen when the user logs in each time.", 'login-with-ajax-pro'); ?></em></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label><?php esc_html_e("Require 2FA Setup", 'login-with-ajax-pro'); ?></label>
					</th>
					<td>
						<?php if( empty($TwoFA['grace_mode']) ) $TwoFA['grace_mode'] = 0; ?>
						<select name="lwa_2FA[grace_mode]">
							<option value='0' <?php selected($TwoFA['grace_mode'], 0); ?> ><?php esc_html_e('No, 2FA is optional', 'login-with-ajax-pro'); ?></option>
							<option value='1' <?php selected($TwoFA['grace_mode'], 1); ?> ><?php esc_html_e('Yes, require 2FA effective from the date below', 'login-with-ajax-pro'); ?></option>
							<option value='2' <?php selected($TwoFA['grace_mode'], 2); ?> ><?php esc_html_e('Yes, require 2FA x days after registration', 'login-with-ajax-pro'); ?></option>
						</select>
						<p><em><?php esc_html_e("You can make 2FA optional, or required after a certain date. To make it effective immediately, leave the date below as today's date, otherwise you can add a future date. You can also require users to set up 2FA x days after registering, or (if user already registered a while ago) the cutoff date below, whichever date is later.", 'login-with-ajax-pro'); ?></em></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label><?php esc_html_e("Require 2FA Cut-Off", 'login-with-ajax-pro'); ?></label>
					</th>
					<td>
						<input name="lwa_2FA[grace_date]" type="date" value="<?php echo empty($TwoFA['grace_date']) ? current_datetime()->format('Y-m-d') : $TwoFA['grace_date']; ?>">
						<p><em><?php esc_html_e("If 2FA is required, users will not be able to proceed with logging in after this date.", 'login-with-ajax-pro'); ?></em></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label><?php esc_html_e("Require 2FA User Days", 'login-with-ajax-pro'); ?></label>
					</th>
					<td>
						<input name="lwa_2FA[grace_user_days]" type="text" value="<?php echo empty($TwoFA['grace_user_days']) ? 7 : absint($TwoFA['grace_user_days']); ?>">
						<p><em><?php esc_html_e("If 2FA is required x days after a user registered, then this number of days will be used relative to the date the user has registered.", 'login-with-ajax-pro'); ?></em></p>
					</td>
				</tr>
			</tbody>
			<?php do_action('lwa_settings_2FA_footer'); ?>
		</table>
		<script type="text/javascript">
			jQuery(document).ready( function($){
				$('#lwa_2FA_when').on('change', function(){
					if( $(this).val() === '1' ){
						$('.lwa-settings-2FA-when-days').show();
					}else{
						$('.lwa-settings-2FA-when-days').hide();
					}
				}).triggerHandler('change');
				$('#lwa_2FA_enable').on('click', function( event ){
					if( $(this).prop('checked') ){
						$('tr.lwa-settings-2FA-thirdparty').show();
						$('option.lwa-settings-2FA-thirdparty, input[type="checkbox"].lwa-settings-2FA-thirdparty').prop('disabled', false);
						$('.lwa-settings-2FA').show();
					}else{
						let confirm_message = '<?php echo esc_js('Disabling 2FA may disable other security features that rely on 2FA as a second layer of verification. Please revise your other security settings when disabling 2FA.', 'login-with-ajax'); ?>';
						if( confirm( confirm_message ) ) {
							$('tr.lwa-settings-2FA-thirdparty').hide();
							$('option.lwa-settings-2FA-thirdparty, input[type="checkbox"].lwa-settings-2FA-thirdparty').prop('disabled', true);
							$('.lwa-settings-2FA').hide();
						} else {
							event.preventDefault();
							return false;
						}
					}
				});
				if( !$('#lwa_2FA_enable').prop('checked') ){
					$('tr.lwa-settings-2FA-thirdparty').hide();
				}
				document.querySelectorAll('.lwa-2FA-method').forEach( function( el ) {
					el.addEventListener('change', function(){
						if( el.checked ) {
							document.querySelectorAll('.lwa-settings-2FA-' + this.getAttribute('data-method')).forEach( tbody => tbody.classList.remove('hidden') );
						} else {
							document.querySelectorAll('.lwa-settings-2FA-' + this.getAttribute('data-method')).forEach( tbody => tbody.classList.add('hidden') );
						}
					});
					el.dispatchEvent( new Event('change') );
				});
			});
		</script>
		<?php
	}
}
Admin::init();