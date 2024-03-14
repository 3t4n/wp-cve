<?php
/**
 * Enable Support
 *
 * @package Enable Support Modal
 */

use LassoLite\Classes\Enum;
use LassoLite\Classes\Setting;

$settings = Setting::get_settings();
$email_support = ! empty( $settings[Enum::EMAIL_SUPPORT] ) ? $settings[Enum::EMAIL_SUPPORT] : get_option( 'admin_email' );
$is_subscribe_setting = $settings[Enum::IS_SUBSCRIBE] ?? '';
$is_subscribe_setting_checked = 'true' === $is_subscribe_setting || empty( $settings[Enum::IS_SUBSCRIBE] ) ? 'checked' : '';
?>
<div class="modal fade" id="enable-support" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content shadow p-5 rounded text-center">
			<form method="post" onsubmit="event.preventDefault()">
				<div id="enable-support-wrapper">
					<h2>Enable Lasso Support</h2>
					<p class="mb-0">We want to help you in any way that we can.</p>
					<p>What is the best email to reach you at?</p>
					<div class="form-group">
						<input type="text" name="email" id="email" required class="form-control" value="<?php echo $email_support; ?>" placeholder="Email">
						<p class="js-error text-danger mt-1 mb-1"></p>
					</div>
					<div class="form-check mb-3">
						<input id="subscribe" <?php echo $is_subscribe_setting_checked; ?> type="checkbox">
						<label class="form-check-label" for="subscribe">
							<small>Subscribe and learn affiliate marketing in just 3 minutes per week</small>
							<span></span>
						</label>
					</div>
					<div class="text-center">
						<p><button id="btn-save-support" class="btn">Enable Support</button></p>
						<div class="clearfix"></div>
						<div class="clearfix"></div>
						<small class="mt-2 dismiss">No thanks, I don't want support</small>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>