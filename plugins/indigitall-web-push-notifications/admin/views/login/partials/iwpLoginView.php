<?php
	$showReConfigMessage = isset($showReConfigMessage) && $showReConfigMessage;
	$reconnectError = __('In this new version of the plugin we have improved the security and how your session is managed. That is why we ask you to log in again. We will not ask you to log in again, promised :)', 'iwp-text-domain');
	$reconnectHtml = $showReConfigMessage ? "<div id='iwp-admin-login-reconnect-box' class='iwp-admin-warning-box iwp-admin-default-box'>$reconnectError</div>" : '';

	$headerLabel = __('onBoardingStep1Title', 'iwp-text-domain');
	$emailLabel = __('Email','iwp-text-domain');
	$passwordLabel = __('Password','iwp-text-domain');
	$customDomainLabel = __('Use custom domain','iwp-text-domain');
	$customDomainPlaceholder = __('Custom domain','iwp-text-domain');
	$customDomainTip = __('Add your custom domain','iwp-text-domain');
	$customDomainInfo = "console.indigitall.com";
	$goToSignUpLabel = __('Create account','iwp-text-domain');
	$noCreditCardLabel = __('NO credit card required','iwp-text-domain');
	$loginLabel = __('Login','iwp-text-domain');
	$recoverLabel = __('Recover password','iwp-text-domain');
?>

<div id="iwpAdminLogin" class="iwp-admin-modalLogin-content iwp-admin-login-container">
	<div id="iwp-admin-login-error-box" class="iwp-admin-error-box iwp-admin-default-box"></div>
	<div id="iwp-admin-login-info-box" class="iwp-admin-info-box iwp-admin-default-box"></div>
	<?php echo($reconnectHtml); ?>
	<div class="iwp-admin-modalLogin-header">
		<?php echo($headerLabel); ?>
		<i id="iwpAdminLoginModalClose" class="iwp-close-icon"></i>
	</div>
	<div class="iwp-admin-modalLogin-body">
		<div class="iwp-admin-form-group">
			<label for="userEmail"><?php echo($emailLabel); ?></label>
			<input id="userEmail" name="userEmail" class="" type="email" maxlength="100" placeholder="<?php echo($emailLabel); ?>" value="">
		</div>
		<div class="iwp-admin-form-group">
			<label for="userPassword"><?php echo($passwordLabel); ?></label>
			<div id="iwp-show-password-container" class="iwp-show-password-container iwp-password-is-hide">
				<input id="userPassword" name="userPassword" class="" type="password" maxlength="100" placeholder="xxxxxxxxxxx" value="">
				<i id="iwp-show-password" class="iwp-show-password"></i>
			</div>
		</div>
		<div class="iwp-admin-form-group-checkbox">
			<label class="iwp-checkbox-container" for="userDomainCheckbox">
				<input type="checkbox" id="userDomainCheckbox" name="userDomainCheckbox" value="">
				<i class="iwp-checkbox checked"></i>
				<i class="iwp-checkbox unchecked"></i>
				<span><?php echo($customDomainLabel); ?></span>
			</label>
		</div>
		<div id="customDomain" class="iwp-admin-form-group iwp-hide iwp-domain-tip-container">
			<label class="iwp-domain-tip-content iwp-family-bold" for="userDomain"><?php echo($customDomainInfo); ?></label>
			<i class="iwp-question-icon" title="<?php echo($customDomainTip); ?>"></i>
			<input name="userDomain" id="userDomain" maxlength="10" type="text"  class="form-control inputDominio" placeholder="<?php echo($customDomainPlaceholder); ?>"  />
		</div>
	</div>
	<div class="iwp-admin-modalLogin-footer">
		<div class="iwp-admin-form-group-buttons">
			<button id="showSignUp" class="iwp-btn iwp-btn-transparent iwp-btn-signup" type="button">
				<div><?php echo($goToSignUpLabel); ?></div>
				<div class="iwp-no-credit-card"><?php echo($noCreditCardLabel); ?></div>
			</button>
			<div class="iwp-vertical-end-buttons">
				<button id="loginSubmit" class="iwp-btn iwp-btn-green" type="button"><?php echo($loginLabel); ?></button>
				<button id="recoverPassword" class="iwp-btn iwp-btn-transparent iwp-btn-recover-pass" type="button"><?php echo($recoverLabel); ?></button>
			</div>
		</div>
	</div>
</div>