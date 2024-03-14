<?php
	$onBoardingTitle1 = '2x1:';
	$onBoardingTitle2 = __('Get 2 solutions in 1 free plugin','iwp-text-domain');
	$onBoardingSubtitle = __('30K Web Push devices, WhatsApp Chat and free support','iwp-text-domain');
	$whatsAppChannelIcon = IWP_ADMIN_URL . 'images/whatsApp-icon.png';
	$webPushChannelIcon = IWP_ADMIN_URL . 'images/webPush-icon.png';
	$countriesPrefixOptions = isset($countriesPrefixOptions) ? $countriesPrefixOptions : '';
	$guideLink = 'https://documentation.iurny.com/reference/wordpress-plugin';

	$doubleFactorModal = isset($doubleFactorModal) ? $doubleFactorModal : '';

	$webPushUrlValue = get_site_url();

	// Reconnect error
	$showReconnectErrorModal = isset($showReconnectErrorModal) && $showReconnectErrorModal;
	$reconnectError = __('In this new version of the plugin we have improved the security and how your session is managed. That is why we ask you to log in again. We will not ask you to log in again, promised :)', 'iwp-text-domain');
?>

<div class="iwp-admin-onBoarding-container" style="display: none;">
	<?php
		if ($showReconnectErrorModal) {
			echo("<div id='iwpReconnectErrorBox' class='iwp-admin-error-box'>{$reconnectError}</div>");
		}
	?>
	<div class="iwp-admin-onBoarding-head">
		<div class="iwp-admin-onBoarding-title">
			<span class="iwp-color-green"><?php echo($onBoardingTitle1); ?>&nbsp;</span>
			<span><?php echo($onBoardingTitle2); ?></span>
		</div>
		<div class="iwp-admin-onBoarding-subtitle"><?php echo($onBoardingSubtitle); ?></div>
	</div>
	<!-- Login step -->
	<div class="iwp-admin-onBoarding-box selected" data-step="login">
		<div class="iwp-admin-onBoarding-box-header">
			<div class="iwp-admin-onBoarding-box-header-number">1</div>
			<div class="iwp-admin-onBoarding-box-header-title"><?php _e('onBoardingStep1Title', 'iwp-text-domain'); ?></div>
		</div>
		<!-- Error label -->
		<div id="iwp-admin-error-box" class="iwp-admin-error-box iwp-hide"></div>
		<!-- Success label -->
		<div id="iwp-admin-success-box" class="iwp-admin-success-box iwp-hide"></div>
		<div class="iwp-admin-onBoarding-box-body-container">
			<div class="iwp-admin-onBoarding-box-body">
				<!-- Login View -->
				<div id="iwp-admin-onBoarding-login-view">
					<div class="iwp-admin-form-group">
						<label for="userEmail"><?php _e('Email','iwp-text-domain'); ?></label>
						<input id="userEmail" name="userEmail" class="" type="email" maxlength="100" placeholder="<?php _e('Email','iwp-text-domain'); ?>" value="">
					</div>
					<div class="iwp-admin-form-group">
						<label for="userPassword"><?php _e('Password','iwp-text-domain'); ?></label>
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
							<span><?php _e('Use custom domain','iwp-text-domain'); ?></span>
						</label>
					</div>
					<div id="customDomain" class="iwp-admin-form-group iwp-hide iwp-domain-tip-container">
						<label class="iwp-domain-tip-content iwp-family-bold" for="userDomain">console.indigitall.com</label>
						<i class="iwp-question-icon" title="<?php _e('Add your custom domain','iwp-text-domain'); ?>"></i>
						<input name="userDomain" id="userDomain" maxlength="10" type="text"  class="form-control inputDominio" placeholder="<?php _e('Custom domain','iwp-text-domain'); ?>"  />
					</div>
					<div class="iwp-admin-form-group-buttons">
						<button id="showSignUp" class="iwp-btn iwp-btn-transparent iwp-btn-signup" type="button">
							<div><?php _e('Create account','iwp-text-domain'); ?></div>
							<div class="iwp-no-credit-card"><?php _e('NO credit card required','iwp-text-domain'); ?></div>
						</button>
						<button id="loginSubmit" class="iwp-btn iwp-btn-green" type="button"><?php echo strtoupper(__('Next','iwp-text-domain')); ?></button>
					</div>
				</div>
				<!-- SignUp View -->
				<div id="iwp-admin-onBoarding-signup-view" class="iwp-hide">
					<div class="iwp-admin-onBoarding-signup-field iwp-admin-form-group">
						<label for="userNewEmail"><?php _e('Email','iwp-text-domain'); ?></label>
						<input id="userNewEmail" name="userNewEmail" class="" type="email" maxlength="100" placeholder="<?php _e('Email','iwp-text-domain'); ?>" value="">
					</div>
					<div class="iwp-admin-onBoarding-signup-field iwp-admin-form-group">
						<label for="userNewPassword"><?php _e('Password','iwp-text-domain'); ?></label>
						<div id="iwp-show-new-password-container" class="iwp-show-password-container iwp-password-is-hide">
							<input id="userNewPassword" name="userNewPassword" class="" type="password" maxlength="100" placeholder="xxxxxxxxxxx" value="">
							<i id="iwp-show-new-password" class="iwp-show-password"></i>
						</div>
					</div>
					<div class="iwp-admin-onBoarding-signup-field iwp-admin-form-group">
						<label for="userNewPasswordConfirm"><?php _e('Confirm password','iwp-text-domain'); ?></label>
						<div id="iwp-show-new-password-confirm-container" class="iwp-show-password-container iwp-password-is-hide">
							<input id="userNewPasswordConfirm" name="userNewPasswordConfirm" class="" type="password" maxlength="100" placeholder="xxxxxxxxxxx" value="">
							<i id="iwp-show-new-password-confirm" class="iwp-show-password"></i>
						</div>
					</div>
					<div class="iwp-admin-onBoarding-signup-field iwp-admin-form-group-checkbox iwp-signUp-checkbox">
						<label class="iwp-checkbox-container" for="confirmTermsCheckbox">
							<input type="checkbox" id="confirmTermsCheckbox" name="confirmTermsCheckbox" value="">
							<i class="iwp-checkbox checked"></i>
							<i class="iwp-checkbox unchecked"></i>
							<span class="iwp-signUp-checkbox-label"><?php _e("I have read and accept the <a href='https://iurny.com/en/terms-and-conditions/' target='_blank'>terms and conditions</a> and the <a href='https://iurny.com/en/privacy-policy/' target='_blank'>privacy policy</a>",'iwp-text-domain'); ?></span>
						</label>
					</div>
					<div class="iwp-admin-onBoarding-signup-field iwp-admin-form-group-checkbox iwp-signUp-checkbox">
						<label class="iwp-checkbox-container" for="confirmNewsletters">
							<input type="checkbox" id="confirmNewsletters" name="confirmNewsletters" value="">
							<i class="iwp-checkbox checked"></i>
							<i class="iwp-checkbox unchecked"></i>
							<span class="iwp-signUp-checkbox-label"><?php _e('I agree to receive commercial communications','iwp-text-domain'); ?></span>
						</label>
					</div>
					<div class="iwp-admin-form-group-buttons">
						<button id="showLogin" class="iwp-btn iwp-btn-transparent" type="button"><?php _e('I have an account','iwp-text-domain'); ?></button>
						<button id="signUpSubmit" class="iwp-btn iwp-btn-green" type="button"><?php echo(strtoupper(__('Create account','iwp-text-domain'))); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Select service step -->
	<div class="iwp-admin-onBoarding-box" data-step="service">
		<div class="iwp-admin-onBoarding-box-header">
			<div class="iwp-admin-onBoarding-box-header-number">2</div>
			<div class="iwp-admin-onBoarding-box-header-title"><?php _e('Choose your project', 'iwp-text-domain') ?></div>
		</div>
		<div class="iwp-admin-onBoarding-box-body-container">
			<div class="iwp-admin-onBoarding-box-body">
				<div class="iwp-admin-form-group">
					<label for="iwpApplicationId"><?php _e('Select Project','iwp-text-domain'); ?></label>
					<div class="iwp-custom-select">
						<select name="iwpApplicationId" id="iwpApplicationId"></select>
					</div>
					<div class="iwp-admin-form-group-buttons iwp-select-service-button">
						<button id="selectService" class="iwp-btn iwp-btn-green" type="button"><?php echo(strtoupper(__('Next','iwp-text-domain'))); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Select channels step -->
	<div class="iwp-admin-onBoarding-box" data-step="channel">
		<div class="iwp-admin-onBoarding-box-header">
			<div class="iwp-admin-onBoarding-box-header-number">3</div>
			<div class="iwp-admin-onBoarding-box-header-title"><?php _e('Choose the channels', 'iwp-text-domain'); ?></div>
		</div>
		<!-- Error label -->
		<div id="iwp-admin-step3-error-box" class="iwp-admin-error-box iwp-hide"></div>
		<div class="iwp-admin-onBoarding-box-body-container">
			<div class="iwp-admin-onBoarding-box-body">
				<div class="iwp-admin-channel-container">
					<div id="iwpWhatsAppChannel" class="iwp-admin-channel" title="WhatsApp Chat">
						<input id="iwpChannelWhatsAppActive" type="hidden" value="0">
						<img src="<?php echo($whatsAppChannelIcon); ?>" alt="WhatsApp Chat">
					</div>
					<div id="iwpWebPushChannel" class="iwp-admin-channel" title="WebPush">
						<input id="iwpChannelWebPushActive" type="hidden" value="0">
						<img src="<?php echo($webPushChannelIcon); ?>" alt="WebPush">
					</div>
				</div>
				<div class="iwp-admin-channel-data-container">
					<div id="iwpWhatsAppChannelData" class="iwp-admin-channel-data iwp-hide">
						<div class="iwp-admin-channel-data-title">WHATSAPP CHAT</div>
						<div class="iwp-admin-form-group">
							<label for="whatsAppPhone"><?php _e('Phone', 'iwp-text-domain'); ?></label>
							<div class="iwp-admin-channel-data-whatsapp">
								<div class="iwp-custom-select">
									<select id="whatsAppPhonePrefix"><?php echo($countriesPrefixOptions); ?></select>
								</div>
								<input id="whatsAppPhone" name="whatsAppPhone" class="" type="text" maxlength="50" placeholder="<?php _e('Ex. 999999999', 'iwp-text-domain'); ?>" value="">
							</div>
						</div>
					</div>
					<div id="iwpWebPushChannelData" class="iwp-admin-channel-data iwp-hide">
						<div class="iwp-admin-channel-data-title">WEB PUSH</div>
						<div class="iwp-admin-form-group">
							<label for="webPushUrl"><?php _e('Url', 'iwp-text-domain'); ?></label>
							<input id="webPushUrl" name="webPushUrl" class="" type="text" value="<?php echo($webPushUrlValue); ?>" disabled>
						</div>
					</div>
				</div>
				<div id="onBoardingStartContainer" class="iwp-admin-form-group-buttons iwp-hide">
					<button id="helpGuide" class="iwp-btn iwp-btn-transparent" type="button">
						<a class="iwp-admin-channel-link" href="<?php echo($guideLink); ?>" target="_blank"><?php _e('Help guide', 'iwp-text-domain'); ?></a>
					</button>
					<button id="onBoardingStart" class="iwp-btn iwp-btn-green" type="button"><?php echo(strtoupper(__('Get started','iwp-text-domain'))); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo($doubleFactorModal); ?>