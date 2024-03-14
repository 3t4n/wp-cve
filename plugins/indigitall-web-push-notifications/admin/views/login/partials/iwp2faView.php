<?php
	$headerLabel = __('Two Factor autentification','iwp-text-domain');
	$descriptionLabel = __('An email with the verification code has been sent to you','iwp-text-domain');
	$verificationCodeLabel = __('Verification code','iwp-text-domain');
	$verificationDescriptionLabel = __('Verification code will be valid for ','iwp-text-domain');
	$newCodeLabel = __('Obtain new code','iwp-text-domain');
	$checkCodeLabel = __('Check the code','iwp-text-domain');
?>
<div id="iwpAdmin2FA" class="iwp-admin-modalLogin-content iwp-admin-2fa-container">
	<div id="iwp-admin-2fa-info-box" class="iwp-admin-info-box iwp-admin-default-box"></div>
	<div id="iwp-admin-2fa-error-box" class="iwp-admin-error-box iwp-admin-default-box"></div>
	<div class="iwp-admin-modalLogin-header">
		<?php echo($headerLabel); ?>
		<i id="iwpAdminLoginModalClose2FA" class="iwp-close-icon"></i>
	</div>
	<div class="iwp-admin-modalLogin-body">
		<div class="iwp-modal-body-subtitle"><?php echo($descriptionLabel); ?></div>
		<div class="iwp-admin-form-group">
			<label for="2FaCode"><?php echo($verificationCodeLabel); ?></label>
			<input id="2FaCode" name="2FaCode" class="" type="text" maxlength="10" placeholder="xxxxxx" value="">
			<div class="iwp-modal-body-tiny-tip">
				<?php echo($verificationDescriptionLabel); ?>
				<span id="iwp2FaCounter">00:05:00</span>
			</div>
		</div>
	</div>
	<div class="iwp-admin-modalLogin-footer">
		<div class="iwp-admin-form-group-buttons">
			<button id="iwp2FaRenewCode" class="iwp-btn iwp-btn-transparent" type="button"><?php echo($newCodeLabel); ?></button>
			<button id="iwp2FaSubmit" class="iwp-btn iwp-btn-green" type="button"><?php echo($checkCodeLabel); ?></button>
		</div>
	</div>
</div>
