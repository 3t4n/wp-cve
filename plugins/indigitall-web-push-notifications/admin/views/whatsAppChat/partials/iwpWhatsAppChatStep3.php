<?php
	require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatModel.php';
	require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatWindowModel.php';
	require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatQrModel.php';

	$chatHeaderValue 	 = (isset($chatHeaderValue) && !empty($chatHeaderValue)) ? $chatHeaderValue : __('iurny.com', 'iwp-text-domain');
	$chatBodyValue 	 	 = (isset($chatBodyValue) && !empty($chatBodyValue)) ? $chatBodyValue : __("Welcome to iurny's chat", 'iwp-text-domain');
	$themeColor 		 = (isset($themeColor) && !empty($themeColor)) ? $themeColor : iwpWhatsAppChatWindowModel::DEFAULT_COLOR;
	$chatButtonTextValue = (isset($chatButtonTextValue) && !empty($chatButtonTextValue)) ? $chatButtonTextValue : __('Open chat', 'iwp-text-domain');

	$chatType = (isset($chatType) && !empty($chatType)) ? $chatType : iwpWhatsAppChatModel::CHAT_TYPE_QR;
	$chatTypeValues = array(
		iwpWhatsAppChatModel::CHAT_TYPE_DISABLE => array(
			'checked' 		 => ($chatType === iwpWhatsAppChatModel::CHAT_TYPE_DISABLE) ? ' checked ' : '',
			'class' 		 => ($chatType === iwpWhatsAppChatModel::CHAT_TYPE_DISABLE) ? ' iwp-hide ' : '',
		),
		iwpWhatsAppChatModel::CHAT_TYPE_QR => array(
			'checked' 		 => ($chatType === iwpWhatsAppChatModel::CHAT_TYPE_QR) ? ' checked ' : '',
			'class' 		 => ($chatType === iwpWhatsAppChatModel::CHAT_TYPE_QR) ? '' : ' iwp-hide ',
			'containerClass' => ($chatType === iwpWhatsAppChatModel::CHAT_TYPE_QR) ? ' iwp-qr-container ' : '',
		),
		iwpWhatsAppChatModel::CHAT_TYPE_CUSTOMIZED => array(
			'checked' 		 => ($chatType === iwpWhatsAppChatModel::CHAT_TYPE_CUSTOMIZED) ? ' checked ' : '',
			'class' 		 => ($chatType === iwpWhatsAppChatModel::CHAT_TYPE_CUSTOMIZED) ? '' : ' iwp-hide ',
		),
	);

	$qrHeader = (isset($qrHeader) && !empty($qrHeader)) ? $qrHeader : '';
	$qrText   = (isset($qrText) && !empty($qrText)) ? $qrText : '';
	$qrColor  = (isset($qrColor) && !empty($qrColor)) ? $qrColor : iwpWhatsAppChatQrModel::DEFAULT_COLOR;

	$buttonIcon 			= (isset($buttonIcon) && !empty($buttonIcon)) ? $buttonIcon : 'send';
	$buttonIconStatusNone 		= ($buttonIcon === 'none') ? ' checked ' : '';
	$buttonIconStatusSend 		= ($buttonIcon === 'send') ? ' checked ' : '';
	$buttonIconStatusChat 		= ($buttonIcon === 'chat') ? ' checked ' : '';
	$buttonIconStatusQuestion 	= ($buttonIcon === 'question') ? ' checked ' : '';
	$buttonIconStatusWhatsApp 	= ($buttonIcon === 'whatsApp') ? ' checked ' : '';

	$chatSleep 		  = (isset($chatSleep) && is_int($chatSleep)) ? $chatSleep : 20;

	$buttonIconTimes 		= IWP_ADMIN_URL . 'images/times-icon.svg';
	$buttonIconSend 		= IWP_ADMIN_URL . 'images/send-icon.svg';
	$buttonIconChat 		= IWP_ADMIN_URL . 'images/chat-icon.svg';
	$buttonIconQuestion		= IWP_ADMIN_URL . 'images/question-bubble-icon.svg';
	$buttonIconWhatsApp 	= IWP_ADMIN_URL . 'images/whatsApp-icon.svg';
	$iconQuestionTip		= IWP_ADMIN_URL . 'images/question-icon.svg';

//	$step3Label 				= __('Step 3:', 'iwp-text-domain');
	$step3Label 				= __('', 'iwp-text-domain');
//	$step3Title 				= __('Chat Settings', 'iwp-text-domain');
	$step3Title 				= __('Chat customization', 'iwp-text-domain');
	$chatHeaderLabel			= __('Header', 'iwp-text-domain');
	$chatHeaderPlaceholder		= __('Ex. iurny.com', 'iwp-text-domain');
	$chatWelcomeLabel			= __('Chat text', 'iwp-text-domain');
	$chatWelcomePlaceholder		= __("Ex. Welcome to iurny's chat", 'iwp-text-domain');
	$themeColorLabel			= __('Chat Color', 'iwp-text-domain');
	$chatButtonTextLabel		= __('Button Text', 'iwp-text-domain');
	$chatButtonTextPlaceholder	= __('Ex. Open chat', 'iwp-text-domain');
	$buttonImageLabel			= __('Button Icon', 'iwp-text-domain');
	$buttonSleepLabel			= __('Chat delay', 'iwp-text-domain');
	$iconSleepSeconds 			= __('wait seconds', 'iwp-text-domain');
	$buttonSleepTip 			= __('After the icon is shown, seconds to wait until the chat opens automatically.<br><b>Special value: -1</b> (The chat will not open automatically)', 'iwp-text-domain');

	$chatTypeDisableLabel 		= __('Disable chat (open WhatsApp directly)', 'iwp-text-domain');
	$chatTypeQrLabel 			= __('Show QR code (recommended option)', 'iwp-text-domain');
	$chatTypeQrTip1 			= __('On mobile devices, the QR code will be displayed.', 'iwp-text-domain');
	$chatTypeQrTip2 			= __('On computers, WhatsApp will open directly.', 'iwp-text-domain');
	$chatTypeQrTip 				= "- $chatTypeQrTip1<br><br>- $chatTypeQrTip2";
	$chatTypeCustomizedLabel 	= __('Customized', 'iwp-text-domain');
	$chatQrHeaderLabel 			= __('Header', 'iwp-text-domain');
	$chatQrHeaderPlaceholder 	= __('Ex. iurny.com', 'iwp-text-domain');
	$chatQrTextLabel 			= __('Text', 'iwp-text-domain');
	$chatQrTextPlaceholder	 	= __('Scan this code to contact us through WhatsApp', 'iwp-text-domain');
	$chatQrEmpty			 	= __('Incorrect phone number', 'iwp-text-domain');
	$chatQrColorLabel			= __('Chat Color', 'iwp-text-domain');

	$sleepEmpty = __('Enter a valid number', 'iwp-text-domain');
	$errorIcon  = IWP_ADMIN_URL . 'images/exclamation-red-icon.svg';
	$errorIconHtml = "<img src='{$errorIcon}' alt=''>";
?>
<div class="iwp-admin-whatsAppChat-step">
	<div class="iwp-admin-whatsAppChat-left-col">
		<div class="iwp-admin-whatsAppChat-step-title">
			<span class="iwp-admin-whatsAppChat-step-title-number"><?php echo($step3Label); ?></span>
			<span class="iwp-admin-whatsAppChat-step-title-text"><?php echo($step3Title); ?></span>
		</div>
		<div class="iwp-admin-form-group">
			<label class="iwp-admin-form-group-inline iwp-admin-chat-options iwp-admin-chat-options-tip">
				<input type="radio" name="adminWhChatType"
					<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_QR]['checked']); ?>
					   value="<?php echo(iwpWhatsAppChatModel::CHAT_TYPE_QR); ?>">
				<div class="iwp-admin-whatsAppChat-inline-label"><?php echo($chatTypeQrLabel); ?></div>
				<i class="iwp-admin-chat-options-tip-content iwp-question-icon"></i>
				<div class="iwp-admin-chat-options-tip-label"><?php echo($chatTypeQrTip); ?></div>
			</label>
			<label class="iwp-admin-form-group-inline iwp-admin-chat-options">
				<input type="radio" name="adminWhChatType"
						<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_DISABLE]['checked']); ?>
					   	value="<?php echo(iwpWhatsAppChatModel::CHAT_TYPE_DISABLE); ?>">
				<div class="iwp-admin-whatsAppChat-inline-label"><?php echo($chatTypeDisableLabel); ?></div>
			</label>
			<label class="iwp-admin-form-group-inline iwp-admin-chat-options">
				<input type="radio" name="adminWhChatType"
						<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_CUSTOMIZED]['checked']); ?>
					   	value="<?php echo(iwpWhatsAppChatModel::CHAT_TYPE_CUSTOMIZED); ?>">
				<div class="iwp-admin-whatsAppChat-inline-label"><?php echo($chatTypeCustomizedLabel); ?></div>
			</label>
		</div>
		<div id="iwpAdminWhatsappQrForm" class="<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_QR]['class']); ?>">
			<div class="iwp-admin-form-group">
				<label for="adminWhQrHeader"><?php echo($chatQrHeaderLabel); ?></label>
				<input id="adminWhQrHeader" class="" type="text"
					   placeholder="<?php echo($chatQrHeaderPlaceholder); ?>"
					   value="<?php echo($qrHeader); ?>">
			</div>
			<div class="iwp-admin-form-group">
				<label for="adminWhQrText"><?php echo($chatQrTextLabel); ?></label>
				<input id="adminWhQrText" class="" type="text"
					   placeholder="<?php echo($chatQrTextPlaceholder); ?>"
					   value="<?php echo($qrText); ?>">
			</div>
			<div class="iwp-admin-form-group">
				<label for="adminWhQrColor"><?php echo($chatQrColorLabel); ?></label>
				<input name="adminWhQrColor" id="adminWhQrColor" type="color" class="iwp-admin-whatsAppChat-color"
					   value="<?php echo($qrColor); ?>" data-default-color="<?php echo(iwpWhatsAppChatQrModel::DEFAULT_COLOR); ?>"/>
			</div>
		</div>
		<div id="iwpAdminWhatsappCustomizedForm" class="<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_CUSTOMIZED]['class']); ?>">
			<div class="iwp-admin-form-group">
				<label for="adminWhChatHeader"><?php echo($chatHeaderLabel); ?></label>
				<input id="adminWhChatHeader" class="" type="text"
					   placeholder="<?php echo($chatHeaderPlaceholder); ?>"
					   value="<?php echo($chatHeaderValue); ?>">
			</div>
			<div class="iwp-admin-form-group">
				<label for="adminWhChatWelcome"><?php echo($chatWelcomeLabel); ?></label>
				<input id="adminWhChatWelcome" class="" type="text"
					   placeholder="<?php echo($chatWelcomePlaceholder); ?>"
					   value="<?php echo($chatBodyValue); ?>">
			</div>
			<div class="iwp-admin-form-group">
				<label for="adminWhThemeColor"><?php echo($themeColorLabel); ?></label>
				<input name="adminWhThemeColor" id="adminWhThemeColor" type="color" class="iwp-admin-whatsAppChat-color"
					   value="<?php echo($themeColor); ?>" data-default-color="<?php echo(iwpWhatsAppChatWindowModel::DEFAULT_COLOR); ?>"/>
			</div>
			<div class="iwp-admin-form-group">
				<label for="adminWhChatButtonText"><?php echo($chatButtonTextLabel); ?></label>
				<input id="adminWhChatButtonText" class="" type="text"
					   placeholder="<?php echo($chatButtonTextPlaceholder); ?>"
					   value="<?php echo($chatButtonTextValue); ?>">
			</div>
			<div class="iwp-admin-form-group iwp-admin-form-group-icon" id="adminWhButtonType">
				<label><?php echo($buttonImageLabel); ?></label>
				<div class="iwp-admin-whatsAppChat-icon-container">
					<label class="iwp-admin-whatsAppChat-icon">
						<div class="iwp-admin-whatsAppChat-icon-background">
							<img src="<?php echo($buttonIconTimes); ?>" alt="">
						</div>
						<input type="radio" name="adminWhChatButtonImage"
							   value="none" <?php echo($buttonIconStatusNone); ?>
							   data-img="<?php echo($buttonIconTimes); ?>">
					</label>
					<label class="iwp-admin-whatsAppChat-icon">
						<div class="iwp-admin-whatsAppChat-icon-background">
							<img src="<?php echo($buttonIconSend); ?>" alt="">
						</div>
						<input type="radio" name="adminWhChatButtonImage"
							   value="send" <?php echo($buttonIconStatusSend); ?>
							   data-img="<?php echo($buttonIconSend); ?>">
					</label>
					<label class="iwp-admin-whatsAppChat-icon">
						<div class="iwp-admin-whatsAppChat-icon-background">
							<img src="<?php echo($buttonIconChat); ?>" alt="">
						</div>
						<input type="radio" name="adminWhChatButtonImage"
							   value="chat" <?php echo($buttonIconStatusChat); ?>
							   data-img="<?php echo($buttonIconChat); ?>">
					</label>
					<label class="iwp-admin-whatsAppChat-icon">
						<div class="iwp-admin-whatsAppChat-icon-background">
							<img src="<?php echo($buttonIconQuestion); ?>" alt="">
						</div>
						<input type="radio" name="adminWhChatButtonImage"
							   value="question" <?php echo($buttonIconStatusQuestion); ?>
							   data-img="<?php echo($buttonIconQuestion); ?>">
					</label>
					<label class="iwp-admin-whatsAppChat-icon">
						<div class="iwp-admin-whatsAppChat-icon-background">
							<img src="<?php echo($buttonIconWhatsApp); ?>" alt="">
						</div>
						<input type="radio" name="adminWhChatButtonImage"
							   value="whatsApp" <?php echo($buttonIconStatusWhatsApp); ?>
							   data-img="<?php echo($buttonIconWhatsApp); ?>">
					</label>
				</div>
			</div>
			<div class="iwp-admin-form-group">
				<label for="adminWhChatSleep"><?php echo($buttonSleepLabel); ?></label>
				<div class="iwp-admin-input-with-after">
					<input id="adminWhChatSleep" class="iwp-admin-whatsAppChat-sleep-input" maxlength="5" type="text"
						   value="<?php echo($chatSleep); ?>" placeholder="20">
					<div class="iwp-admin-input-with-after-content">
						<div class="iwp-admin-input-with-after-content-text iwp-family-bold"><?php echo($iconSleepSeconds); ?></div>
						<img src="<?php echo($iconQuestionTip); ?>" alt="" class="iwp-admin-input-with-after-content-tip"
							 tooltip="<?php echo($buttonSleepTip); ?>">
					</div>
				</div>
				<div id="adminWhChatSleepError" class="iwp-admin-whatsAppChat-tiny-error iwp-hide"><?php echo($errorIconHtml.$sleepEmpty); ?></div>
			</div>
		</div>
	</div>
	<div class="iwp-admin-whatsAppChat-right-col">
		<div id="iwpAdminWhatsappPreviewContainer" class="<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_DISABLE]['class']); ?>
			iwp-admin-whatsAppChat-chat-preview-container">
			<div id="iwpAdminWhatsappPreviewHeader" class="iwp-admin-whatsAppChat-chat-preview-header">
				<div id="iwpAdminWhatsappPreviewQrHeaderText" class="iwp-admin-whatsAppChat-chat-preview-header-text
					<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_QR]['class']); ?>"></div>
				<div id="iwpAdminWhatsappPreviewHeaderText" class="iwp-admin-whatsAppChat-chat-preview-header-text
					<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_CUSTOMIZED]['class']); ?>"></div>
				<div class="iwp-admin-whatsAppChat-chat-preview-header-close">&times;</div>
			</div>
			<div id="iwpAdminWhatsappPreviewBody" class="iwp-admin-whatsAppChat-chat-preview-body">
				<div id="iwpAdminWhatsappPreviewQrTitle" class="<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_QR]['class']); ?>
					iwp-admin-whatsAppChat-chat-qr-title"></div>
				<div id="iwpAdminWhatsappPreviewMessageContainer" class="<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_QR]['containerClass']); ?>
					iwp-admin-whatsAppChat-chat-preview-body-message">
					<div id="iwpAdminWhatsappPreviewQr" class="iwp-admin-whatsAppChat-chat-type-qr
						<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_QR]['class']); ?>
						iwp-admin-whatsAppChat-chat-preview-body-message-box">
						<div id="iwp-QR-code" class="iwp-admin-whatsAppChat-chat-qr-code" data-empty="<?php echo($chatQrEmpty); ?>"></div>
					</div>
					<div id="iwpAdminWhatsappPreviewMessage" class="
					<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_CUSTOMIZED]['class']); ?> iwp-admin-whatsAppChat-chat-preview-body-message-box"></div>
				</div>
				<div id="iwpAdminWhatsappPreviewIcon" class="
					<?php echo($chatTypeValues[iwpWhatsAppChatModel::CHAT_TYPE_CUSTOMIZED]['class']); ?> iwp-admin-whatsAppChat-chat-preview-body-icon">
					<div id="iwpAdminWhatsappPreviewButtonText" class="iwp-admin-whatsAppChat-chat-preview-body-text"></div>
					<img src="" alt="">
				</div>
			</div>
		</div>
	</div>
</div>
