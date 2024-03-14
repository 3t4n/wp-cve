<?php
	require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatIconModel.php';

	$iconPosition = isset($iconPosition) ? $iconPosition : 'r';
	$iconPositionRight = (empty($iconPosition) || $iconPosition === 'r') ? ' checked ' : '';
	$iconPositionLeft = (!empty($iconPosition) && $iconPosition === 'l') ? ' checked ' : '';
	$iconColor = (isset($iconColor) && !empty($iconColor)) ? $iconColor : iwpWhatsAppChatIconModel::DEFAULT_COLOR;

	$iconOption = (isset($iconOption) && !empty($iconOption)) ? $iconOption : iwpWhatsAppChatIconModel::ICON_OPTION_DEFAULT;
	$iconOptionValues = array(
		iwpWhatsAppChatIconModel::ICON_OPTION_DEFAULT => array(
			'checked' 		 => ($iconOption === iwpWhatsAppChatIconModel::ICON_OPTION_DEFAULT) ? ' checked ' : '',
			'class' 		 => ($iconOption === iwpWhatsAppChatIconModel::ICON_OPTION_DEFAULT) ? '' : ' iwp-hide ',
		),
		iwpWhatsAppChatIconModel::ICON_OPTION_CUSTOMIZED => array(
			'checked' 		 => ($iconOption === iwpWhatsAppChatIconModel::ICON_OPTION_CUSTOMIZED) ? ' checked ' : '',
			'class' 		 => ($iconOption === iwpWhatsAppChatIconModel::ICON_OPTION_CUSTOMIZED) ? '' : ' iwp-hide ',
		),
	);

	$iconImageId 			 = (isset($iconImageId) && !empty($iconImageId)) ? $iconImageId : false;
	$iconImage 				 = (isset($iconImage) && !empty($iconImage)) ? $iconImage : 'whatsApp';
	$iconImageWhatsApp 		 = (empty($iconImage) || $iconImage === 'whatsApp') ? ' checked ' : '';
	$iconImageSupport 		 = (!empty($iconImage) && $iconImage === 'support') ? ' checked ' : '';
	$iconImageChat 			 = (!empty($iconImage) && $iconImage === 'chat') ? ' checked ' : '';
	$iconImageQuestionBubble = (!empty($iconImage) && $iconImage === 'question-bubble') ? ' checked ' : '';
	$iconImageCustom 		 = (!empty($iconImage) && $iconImage === 'custom') ? ' checked ' : '';

	$iconTransparent = isset($iconTransparent) ? $iconTransparent : false;
	$iconTransparentChecker = $iconTransparent ? ' checked ' : '';

	$iconBalloon 	  = (isset($iconBalloon) && !empty($iconBalloon)) ? $iconBalloon : false;
//	$iconBalloonShow  = (isset($iconBalloon) && (($iconBalloon === 'show') || ($iconBalloon === false))) ? ' checked ' : '';
	$iconBalloonShow  = (isset($iconBalloon) && ($iconBalloon === 'show')) ? ' checked ' : '';
	$iconBalloonHover = (isset($iconBalloon) && ($iconBalloon === 'hover')) ? ' checked ' : '';
//	$iconBalloonText  = (isset($iconBalloonText) && !empty($iconBalloonText)) ? $iconBalloonText : __('Hi', 'iwp-text-domain');
	$iconBalloonText  = isset($iconBalloonText) ? $iconBalloonText : '';
	$iconSleep 		  = (isset($iconSleep) && is_int($iconSleep)) ? $iconSleep : 5;

	$iconWhatsApp = IWP_ADMIN_URL . 'images/whatsApp-icon.svg';
	$iconSupport = IWP_ADMIN_URL . 'images/support-icon.svg';
	$iconChat = IWP_ADMIN_URL . 'images/chat-icon.svg';
	$iconQuestion = IWP_ADMIN_URL . 'images/question-bubble-icon.svg';
	$iconUpload = $iconImageId ? wp_get_attachment_image_url($iconImageId, 'full') : IWP_ADMIN_URL . 'images/upload-icon.svg';
	$iconQuestionTip = IWP_ADMIN_URL . 'images/question-icon.svg';
	$iconUploadIsEmpty = $iconImageId ? '' : ' empty ';

	$defaultValues = base64_encode(
		json_encode(
			array(
				'position'		 => 'r',
				'color'			 => iwpWhatsAppChatIconModel::DEFAULT_COLOR,
				'icon'			 => 'whatsApp',
				'transparent' 	 => false,
				'delay'			 => '5',
				'bubble'		 => '',
				'bubbleText'	 => '',
			)
		)
	);

	$customValues = base64_encode(
		json_encode(
			array(
				'position'		 => $iconPosition,
				'color'			 => $iconColor,
				'icon'			 => $iconImage,
				'transparent' 	 => $iconTransparent,
				'delay'			 => $iconSleep,
				'bubble'		 => $iconBalloon,
				'bubbleText'	 => $iconBalloonText,
			)
		)
	);

//	$step2Label 				= __('Step 2:', 'iwp-text-domain');
	$step2Label 				= __('', 'iwp-text-domain');
//	$step2Title 				= __('Icon Settings', 'iwp-text-domain');
	$step2Title 				= __('Icon customization', 'iwp-text-domain');
	$iconPositionLabel 			= __('Position', 'iwp-text-domain');
	$iconPositionRightLabel		= __('Bottom Right', 'iwp-text-domain');
	$iconPositionLeftLabel 		= __('Bottom Left', 'iwp-text-domain');
	$iconColorLabel 			= __('Icon color', 'iwp-text-domain');
	$iconImageLabel 			= __('Choose icon', 'iwp-text-domain');
	$iconTransparentLabel 		= __('Transparent background', 'iwp-text-domain');
	$iconBalloonLabel 			= __('Chat bubble', 'iwp-text-domain');
	$iconBalloonShowLabel 		= __('Visible', 'iwp-text-domain');
	$iconBalloonHoverLabel		= __('Activate on hover', 'iwp-text-domain');
	$iconBalloonTextLabel 		= __('Bubble Text', 'iwp-text-domain');
	$iconBalloonTextPlaceholder = __('Ex, Hi!', 'iwp-text-domain');
	$iconSleepLabel 			= __('Button delay', 'iwp-text-domain');
	$iconSleepSeconds 			= __('wait seconds', 'iwp-text-domain');
	$iconSleepTip 				= __('Waiting seconds for the WhatsApp Chat icon to appear on the web page', 'iwp-text-domain');

	$iconOptionDefault		 	= __('Default icon', 'iwp-text-domain');
	$iconOptionCustomized	 	= __('Customized', 'iwp-text-domain');

	$sleepEmpty = __('Enter a valid number', 'iwp-text-domain');
	$errorIcon  = IWP_ADMIN_URL . 'images/exclamation-red-icon.svg';
	$errorIconHtml = "<img src='{$errorIcon}' alt=''>";
?>

<style>
	.iwp-admin-container .iwp-admin-whatsAppChat .iwp-admin-whatsAppChat-icon-background {
		background-color: <?php echo($iconTransparent ? 'transparent' : $iconColor); ?>;
	}
</style>

<div class="iwp-admin-whatsAppChat-step">
	<div class="iwp-admin-whatsAppChat-left-col">
		<div class="iwp-admin-whatsAppChat-step-title">
			<span class="iwp-admin-whatsAppChat-step-title-number"><?php echo($step2Label); ?></span>
			<span class="iwp-admin-whatsAppChat-step-title-text"><?php echo($step2Title); ?></span>
		</div>
		<div class="iwp-admin-form-group">
			<label class="iwp-admin-form-group-inline iwp-admin-chat-options">
				<input type="radio" name="adminWhIconOption"
					   <?php echo($iconOptionValues[iwpWhatsAppChatIconModel::ICON_OPTION_DEFAULT]['checked']); ?>
					   value="<?php echo(iwpWhatsAppChatIconModel::ICON_OPTION_DEFAULT); ?>">
				<div class="iwp-admin-whatsAppChat-inline-label"><?php echo($iconOptionDefault); ?></div>
			</label>
			<label class="iwp-admin-form-group-inline iwp-admin-chat-options">
				<input type="radio" name="adminWhIconOption"
					<?php echo($iconOptionValues[iwpWhatsAppChatIconModel::ICON_OPTION_CUSTOMIZED]['checked']); ?>
					   value="<?php echo(iwpWhatsAppChatIconModel::ICON_OPTION_CUSTOMIZED); ?>">
				<div class="iwp-admin-whatsAppChat-inline-label"><?php echo($iconOptionCustomized); ?></div>
			</label>
		</div>

		<div id="iwpAdminWhatsappIconForm"
			 class="<?php echo($iconOptionValues[iwpWhatsAppChatIconModel::ICON_OPTION_CUSTOMIZED]['class']); ?>"
			 data-default="<?php echo($defaultValues); ?>"
			 data-custom="<?php echo($customValues); ?>">
			<div class="iwp-admin-form-group">
				<label><?php echo($iconPositionLabel); ?></label>
				<div class="iwp-admin-form-group-inline">
					<label class="iwp-admin-form-group-inline">
						<input type="radio" name="adminWhPositionValue" value="l" <?php echo($iconPositionLeft); ?>>
						<div class="iwp-admin-whatsAppChat-inline-label"><?php echo($iconPositionLeftLabel); ?></div>
					</label>
					<label class="iwp-admin-form-group-inline">
						<input type="radio" name="adminWhPositionValue" value="r" <?php echo($iconPositionRight); ?>>
						<div class="iwp-admin-whatsAppChat-inline-label"><?php echo($iconPositionRightLabel); ?></div>
					</label>
				</div>
			</div>
			<div class="iwp-admin-form-group">
				<label for="adminWhIconColor"><?php echo($iconColorLabel); ?></label>
				<input name="adminWhIconColor" id="adminWhIconColor" type="color" class="iwp-admin-whatsAppChat-color"
					   value="<?php echo($iconColor); ?>" data-default-color="<?php echo(iwpWhatsAppChatIconModel::DEFAULT_COLOR); ?>" />
			</div>
			<div class="iwp-admin-form-group iwp-admin-form-group-icon" id="adminWhIconType">
				<label><?php echo($iconImageLabel); ?></label>
				<div class="iwp-admin-whatsAppChat-icon-container">
					<label class="iwp-admin-whatsAppChat-icon">
						<div class="iwp-admin-whatsAppChat-icon-background">
							<img src="<?php echo($iconWhatsApp); ?>" alt="">
						</div>
						<input type="radio" name="adminWhIconImage"
							   value="whatsApp" <?php echo($iconImageWhatsApp); ?>
							   data-img="<?php echo($iconWhatsApp); ?>">
					</label>
					<label class="iwp-admin-whatsAppChat-icon">
						<div class="iwp-admin-whatsAppChat-icon-background">
							<img src="<?php echo($iconSupport); ?>" alt="">
						</div>
						<input type="radio" name="adminWhIconImage"
							   value="support" <?php echo($iconImageSupport); ?>
							   data-img="<?php echo($iconSupport); ?>">
					</label>
					<label class="iwp-admin-whatsAppChat-icon">
						<div class="iwp-admin-whatsAppChat-icon-background">
							<img src="<?php echo($iconChat); ?>" alt="">
						</div>
						<input type="radio" name="adminWhIconImage"
							   value="chat" <?php echo($iconImageChat); ?>
							   data-img="<?php echo($iconChat); ?>">
					</label>
					<label class="iwp-admin-whatsAppChat-icon">
						<div class="iwp-admin-whatsAppChat-icon-background">
							<img src="<?php echo($iconQuestion); ?>" alt="">
						</div>
						<input type="radio" name="adminWhIconImage"
							   value="question-bubble" <?php echo($iconImageQuestionBubble); ?>
							   data-img="<?php echo($iconQuestion); ?>">
					</label>
					<label class="iwp-admin-whatsAppChat-icon">
						<div id="iwpAdminWhatsAppChatIconUpload"
							 class="iwp-admin-whatsAppChat-icon-background <?php echo($iconUploadIsEmpty); ?>">
							<input type="hidden" id="adminWhIconImageCustom" value="<?php echo($iconImageId ?: ''); ?>">
							<img src="<?php echo($iconUpload); ?>" alt="">
						</div>
						<input type="radio" name="adminWhIconImage"
							   value="custom" <?php echo($iconImageCustom); ?>
							   data-img="<?php echo($iconUpload); ?>">
					</label>
				</div>
			</div>
			<label class="iwp-checkbox-container iwp-admin-whatsAppChat-transparent">
				<input type="checkbox" id="adminWhIconTransparent"
					   value="" <?php echo($iconTransparentChecker); ?>>
				<i class="iwp-checkbox checked"></i>
				<i class="iwp-checkbox unchecked"></i>
				<span><?php echo($iconTransparentLabel); ?></span>
			</label>
			<div class="iwp-admin-form-group">
				<label for="adminWhChatIconSleep"><?php echo($iconSleepLabel); ?></label>
				<div class="iwp-admin-input-with-after">
					<input id="adminWhChatIconSleep" class="iwp-admin-whatsAppChat-sleep-input" maxlength="5" type="text"
						   value="<?php echo($iconSleep); ?>" placeholder="5">
					<div class="iwp-admin-input-with-after-content">
						<div class="iwp-admin-input-with-after-content-text iwp-family-bold"><?php echo($iconSleepSeconds); ?></div>
						<img src="<?php echo($iconQuestionTip); ?>" alt="" class="iwp-admin-input-with-after-content-tip"
							 tooltip="<?php echo($iconSleepTip); ?>">
					</div>
				</div>
				<div id="adminWhIconSleepError" class="iwp-admin-whatsAppChat-tiny-error iwp-hide"><?php echo($errorIconHtml.$sleepEmpty); ?></div>
			</div>
			<div class="iwp-admin-form-group">
				<label><?php echo($iconBalloonLabel); ?></label>
				<div class="iwp-admin-form-group-inline">
					<label class="iwp-checkbox-container">
						<input type="checkbox" id="adminWhIconBalloonShow" value="show" <?php echo($iconBalloonShow); ?>>
						<i class="iwp-checkbox checked"></i>
						<i class="iwp-checkbox unchecked"></i>
						<span><?php echo($iconBalloonShowLabel); ?></span>
					</label>
					<label class="iwp-checkbox-container">
						<input type="checkbox" id="adminWhIconBalloonHover" value="hover" <?php echo($iconBalloonHover); ?>>
						<i class="iwp-checkbox checked"></i>
						<i class="iwp-checkbox unchecked"></i>
						<span><?php echo($iconBalloonHoverLabel); ?></span>
					</label>
				</div>
			</div>
			<div class="iwp-admin-form-group">
				<label for="adminWhChatBalloonText"><?php echo($iconBalloonTextLabel); ?></label>
				<input id="adminWhChatBalloonText" class="" type="text"
					   placeholder="<?php echo($iconBalloonTextPlaceholder); ?>"
					   value="<?php echo($iconBalloonText); ?>">
			</div>
		</div>
	</div>
	<div class="iwp-admin-whatsAppChat-right-col">
		<div class="iwp-admin-whatsAppChat-icon-preview-container">
			<div class="iwp-admin-whatsAppChat-icon-preview-header">
				<div class="iwp-admin-whatsAppChat-icon-preview-header-circle"></div>
				<div class="iwp-admin-whatsAppChat-icon-preview-header-circle"></div>
				<div class="iwp-admin-whatsAppChat-icon-preview-header-circle"></div>
			</div>
			<div class="iwp-admin-whatsAppChat-icon-preview-body">
				<div class="iwp-admin-whatsAppChat-icon-preview-body-message">
					<div class="iwp-admin-whatsAppChat-icon-preview-body-message-box"></div>
				</div>
				<div class="iwp-admin-whatsAppChat-icon-preview-body-icon">
					<img src="" alt="">
				</div>
			</div>
		</div>
	</div>
</div>
