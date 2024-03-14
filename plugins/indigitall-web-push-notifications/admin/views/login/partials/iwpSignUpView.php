<?php
	$headerLabel = __('Sign up free', 'iwp-text-domain');
	$emailLabel = __('Email','iwp-text-domain');
	$passwordLabel = __('Password','iwp-text-domain');
	$confirmPasswordLabel = __('Confirm password','iwp-text-domain');
	$termsLabel = __("I have read and accept the <a href='https://iurny.com/en/terms-and-conditions/' target='_blank'>terms and conditions</a> and the <a href='https://iurny.com/en/privacy-policy/' target='_blank'>privacy policy</a>",'iwp-text-domain');
	$communicationsLabel = __('I agree to receive commercial communications','iwp-text-domain');
	$goToLogin = __('I have an account','iwp-text-domain');
	$signUpLabel = __('Create account','iwp-text-domain');
?>

<div id="iwpAdminSignUp" class="iwp-admin-modalLogin-content iwp-admin-sign-up-container">
	<div id="iwp-admin-sign-up-info-box" class="iwp-admin-error-box iwp-admin-default-box"></div>
	<div class="iwp-admin-modalLogin-header">
		<?php echo($headerLabel); ?>
		<i id="iwpAdminLoginModalCloseSignUp" class="iwp-close-icon"></i>
	</div>
	<div class="iwp-admin-modalLogin-body">
		<div class="iwp-admin-form-group">
			<label for="userNewEmail"><?php echo($emailLabel); ?></label>
			<input id="userNewEmail" name="userNewEmail" class="" type="email" maxlength="100" placeholder="<?php echo($emailLabel); ?>" value="">
		</div>
		<div class="iwp-admin-form-group">
			<label for="userNewPassword"><?php echo($passwordLabel); ?></label>
			<div id="iwp-show-new-password-container" class="iwp-show-password-container iwp-password-is-hide">
				<input id="userNewPassword" name="userNewPassword" class="" type="password" maxlength="100" placeholder="xxxxxxxxxxx" value="">
				<i id="iwp-show-new-password" class="iwp-show-password"></i>
			</div>
		</div>
		<div class="iwp-admin-form-group">
			<label for="userNewPasswordConfirm"><?php echo($confirmPasswordLabel); ?></label>
			<div id="iwp-show-new-password-confirm-container" class="iwp-show-password-container iwp-password-is-hide">
				<input id="userNewPasswordConfirm" name="userNewPasswordConfirm" class="" type="password" maxlength="100" placeholder="xxxxxxxxxxx" value="">
				<i id="iwp-show-new-password-confirm" class="iwp-show-password"></i>
			</div>
		</div>
		<div class="iwp-admin-form-group-checkbox iwp-signUp-checkbox">
			<label class="iwp-checkbox-container" for="confirmTermsCheckbox">
				<input type="checkbox" id="confirmTermsCheckbox" name="confirmTermsCheckbox" value="">
				<i class="iwp-checkbox checked"></i>
				<i class="iwp-checkbox unchecked"></i>
				<span class="iwp-signUp-checkbox-label"><?php echo($termsLabel); ?></span>
			</label>
		</div>
		<div class="iwp-admin-form-group-checkbox iwp-signUp-checkbox">
			<label class="iwp-checkbox-container" for="confirmNewsletters">
				<input type="checkbox" id="confirmNewsletters" name="confirmNewsletters" value="">
				<i class="iwp-checkbox checked"></i>
				<i class="iwp-checkbox unchecked"></i>
				<span class="iwp-signUp-checkbox-label"><?php echo($communicationsLabel); ?></span>
			</label>
		</div>
	</div>
	<div class="iwp-admin-modalLogin-footer">
		<div class="iwp-admin-form-group-buttons">
			<button id="showLogin" class="iwp-btn iwp-btn-transparent" type="button"><?php echo($goToLogin); ?></button>
			<button id="signUpSubmit" class="iwp-btn iwp-btn-green" type="button"><?php echo($signUpLabel); ?></button>
		</div>
	</div>
</div>
