<?php

?>
<div id="iwp2faModal" class="iwp-modal-backdrop iwp-hide">
	<div class="iwp-modal">
		<div class="iwp-modal-header">
			<i id="iwpTimes2FaModal" class="iwp-close-icon"></i>
		</div>
		<div class="iwp-modal-body">
			<div class="iwp-modal-body-title">
				<?php _e('Two Factor autentification','iwp-text-domain'); ?>
			</div>
			<div class="iwp-modal-body-subtitle">
				<?php _e('An email with the verification code has been sent to you','iwp-text-domain'); ?>
			</div>
			<div class="iwp-admin-form-group">
				<label for="2FaCode"><?php _e('Verification code','iwp-text-domain'); ?></label>
				<input id="2FaCode" name="2FaCode" class="" type="text" maxlength="10" placeholder="xxxxxx" value="">
				<div class="iwp-modal-body-tiny-tip">
					<?php _e('Verification code will be valid for ', 'iwp-text-domain'); ?>
					<span id="iwp2FaCounter">00:05:00</span>
				</div>
			</div>
		</div>
		<div id="iwp-admin-2fa-info-box" class="iwp-admin-info-box iwp-hide"></div>
		<div id="iwp-admin-2fa-error-box" class="iwp-admin-error-box iwp-hide"></div>
		<div class="iwp-modal-footer">
			<div class="iwp-admin-form-group-buttons">
				<button id="iwp2FaRenewCode" class="iwp-btn iwp-btn-transparent" type="button">
					<?php _e('Obtain new code','iwp-text-domain'); ?>
				</button>
				<button id="iwp2FaSubmit" class="iwp-btn iwp-btn-green" type="button">
					<?php _e('Next','iwp-text-domain'); ?>
				</button>
			</div>
		</div>
	</div>
</div>
