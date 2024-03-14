<?php
	$headerLabel = __('Recover password','iwp-text-domain');
	$descriptionLabel = __('Enter your email and an email will be sent to you to reset your password','iwp-text-domain');
	$emailLabel = __('Email','iwp-text-domain');
	$customDomainLabel = __('Use custom domain','iwp-text-domain');
	$customDomainPlaceholder = __('Custom domain','iwp-text-domain');
	$customDomainTip = __('Add your custom domain','iwp-text-domain');
	$customDomainInfo = "console.indigitall.com";
	$recoverLabel = __('Recover password','iwp-text-domain');
	$goToLogin = __('Back to login','iwp-text-domain');
?>
<div id="iwpAdminRecoverPass" class="iwp-admin-modalLogin-content iwp-admin-recover-pass-container">
	<div id="iwp-admin-recover-pass-error-box" class="iwp-admin-error-box iwp-admin-default-box"></div>
	<div class="iwp-admin-modalLogin-header">
		<?php echo($headerLabel); ?>
		<i id="iwpAdminLoginModalCloseRecoverPass" class="iwp-close-icon"></i>
	</div>
	<div class="iwp-admin-modalLogin-body">
		<div class="iwp-modal-body-subtitle"><?php echo($descriptionLabel); ?></div>
		<div class="iwp-admin-form-group">
			<label for="recoverPassUserEmail"><?php echo($emailLabel); ?></label>
			<input id="recoverPassUserEmail" name="recoverPassUserEmail" class="" type="email" maxlength="100" placeholder="<?php echo($emailLabel); ?>" value="">
		</div>
		<div class="iwp-admin-form-group-checkbox">
			<label class="iwp-checkbox-container" for="recoverPassUserDomainCheckbox">
				<input type="checkbox" id="recoverPassUserDomainCheckbox" name="recoverPassUserDomainCheckbox" value="">
				<i class="iwp-checkbox checked"></i>
				<i class="iwp-checkbox unchecked"></i>
				<span><?php echo($customDomainLabel); ?></span>
			</label>
		</div>
		<div id="recoverPassCustomDomain" class="iwp-admin-form-group iwp-hide iwp-domain-tip-container">
			<label class="iwp-domain-tip-content iwp-family-bold" for="recoverPassUserDomain"><?php echo($customDomainInfo); ?></label>
			<i class="iwp-question-icon" title="<?php echo($customDomainTip); ?>"></i>
			<input name="recoverPassUserDomain" id="recoverPassUserDomain" maxlength="10" type="text"  class="form-control inputDominio" placeholder="<?php echo($customDomainPlaceholder); ?>"  />
		</div>
	</div>
	<div class="iwp-admin-modalLogin-footer">
		<div class="iwp-admin-form-group-buttons">
			<button id="backToLogin" class="iwp-btn iwp-btn-transparent" type="button"><?php echo($goToLogin); ?></button>
			<button id="iwpRecoverPassSubmit" class="iwp-btn iwp-btn-green" type="button"><?php echo($recoverLabel); ?></button>
		</div>
	</div>
</div>
