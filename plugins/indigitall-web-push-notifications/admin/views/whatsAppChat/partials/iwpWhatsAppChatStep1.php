<?php
	$phone = isset($phone) ? $phone : '';
	$countriesPrefixOptions = isset($countriesPrefixOptions) ? $countriesPrefixOptions : '';
//	$welcomeMessageDefault = (isset($welcomeMessage) && !empty($welcomeMessage)) ? $welcomeMessage : __('Hello, I have a question', 'iwp-text-domain');
	$welcomeMessageDefault = isset($welcomeMessage) ? $welcomeMessage : '';

	/* Traducciones */
//	$step1Label = __('Step 1:', 'iwp-text-domain');
	$step1Label = __('', 'iwp-text-domain');
	$step1Title = __('General settings', 'iwp-text-domain');
	$phoneLabel = __('Phone', 'iwp-text-domain');
	$phonePlaceholder = __('Ex. 600500600', 'iwp-text-domain');
	$welcomeMessageLabel = __('Initial message', 'iwp-text-domain');
	$welcomeMessagePlaceholder = __('Ex. Hello, I have a question', 'iwp-text-domain');

	$phoneError = __('Invalid phone number', 'iwp-text-domain');
	$phoneEmpty = __('If you want to delete the number, first disable WhatsApp Chat', 'iwp-text-domain');
	$errorIcon  = IWP_ADMIN_URL . 'images/exclamation-red-icon.svg';
	$errorIconHtml = "<img src='{$errorIcon}' alt=''>";

	$showAdvancedSettingsLabel = __('Show advanced settings', 'iwp-text-domain');
	$hideAdvancedSettingsLabel = __('Hide advanced settings', 'iwp-text-domain');
?>
<div class="iwp-admin-whatsAppChat-step">
	<div class="iwp-admin-whatsAppChat-left-col">
		<div class="iwp-admin-whatsAppChat-step-title">
			<span class="iwp-admin-whatsAppChat-step-title-number"><?php echo($step1Label); ?></span>
			<span class="iwp-admin-whatsAppChat-step-title-text"><?php echo($step1Title); ?></span>
		</div>
		<div class="iwp-admin-form-group">
			<label for="adminWhPhone"><?php echo($phoneLabel); ?></label>
			<div class="iwp-admin-channel-data-whatsapp">
				<div class="iwp-custom-select">
					<select id="adminWhPhonePrefix"><?php echo($countriesPrefixOptions); ?></select>
				</div>
				<input id="adminWhPhone" class="" type="text" maxlength="50"
					   placeholder="<?php echo($phonePlaceholder); ?>"
					   value="<?php echo($phone); ?>">
			</div>
			<div id="adminWhPhoneError" class="iwp-admin-whatsAppChat-tiny-error iwp-hide"><?php echo($errorIconHtml.$phoneError); ?></div>
			<div id="adminWhPhoneEmpty" class="iwp-admin-whatsAppChat-tiny-error iwp-hide"><?php echo($errorIconHtml.$phoneError); ?></div>
		</div>
		<div class="iwp-admin-form-group">
			<label for="adminWhChatWelcomeMessage"><?php echo($welcomeMessageLabel); ?></label>
			<input id="adminWhChatWelcomeMessage" class="" type="text" maxlength="50"
				   placeholder="<?php echo($welcomeMessagePlaceholder); ?>"
				   value="<?php echo($welcomeMessageDefault); ?>">
		</div>
		<div id="iwpWhatsAppChatShowAdvanceSettings" class="iwp-admin-whatsAppChat-show-advance-settings iwp-admin-switch-container deactivated">
			<input type="hidden" class="iwp-admin-switch-value" value="0">
			<div class="iwp-admin-switch">
				<div class="iwp-admin-switch-ball"></div>
			</div>
			<div class="iwp-admin-switch-label">
				<div class="iwp-admin-switch-label-activated"><?php echo($hideAdvancedSettingsLabel); ?></div>
				<div class="iwp-admin-switch-label-deactivated"><?php echo($showAdvancedSettingsLabel); ?></div>
			</div>
		</div>
	</div>
	<div class="iwp-admin-whatsAppChat-right-col"></div>
</div>
