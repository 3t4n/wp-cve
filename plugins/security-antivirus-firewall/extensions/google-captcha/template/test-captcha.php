<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

$id = md5(mt_rand());
?>
<div class="row" id="<?php echo $id; ?>">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>
					<?php echo $extensionTitle . ': ' . __('Test Public Key', 'wptsaf_security'); ?>
				</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<iframe class="iframe_captcha" src="" frameborder="0" width="320px"></iframe>

				<div class="alert alert-info">
					<?php _e('Pay attention! Test only public key.', 'wptsaf_security'); ?>
				</div>
				
				<div class="ln_solid"></div>
				<div class="form-group">
					<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
						<button class="btn btn-default pull-right btn-popup-close">
							<?php _e('Close', 'wptsaf_security'); ?>
						</button>

						<button class="activate_captcha btn btn-success pull-right" type="submit" >
							<?php _e('Activate', 'wptsaf_security'); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	(function ($) {
		var $row = $('#<?php echo $id; ?>'),
			$iframeSrc = '<?php echo $domain; ?>/wp-admin/admin-ajax.php?'
				+ 'action=wptsaf_security&extension=google-captcha&method=captcha&args[key]=<?php echo $key; ?>'
				+ '&nonce=' + wptsafSecurity.ajaxNonce;

		$('.iframe_captcha', $row).attr('src', $iframeSrc);

		$('.activate_captcha', $row).click(function () {
			var $activate = $('#wptsaf-popup-form #is_active');

			$activate.attr('data-verify', true);
			$activate.trigger('click');
			$('.btn-popup-close', $row).trigger('click');
		});
	})(jQuery);
</script>
