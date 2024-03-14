<?php
	require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatModel.php';
	require_once IWP_PUBLIC_PATH . 'models/iwpWhatsAppChatWindowModel.php';

	$whatsAppChatModel = isset($whatsAppChatModel) ? $whatsAppChatModel : new iwpWhatsAppChatModel();
	if (!$whatsAppChatModel->isEnabled()) {
		return true;
	}

	$chatType = $whatsAppChatModel->getChatType();
	$chatType = !empty($chatType) ? $chatType : 'disable';

	$icon = $whatsAppChatModel->getIcon();
	$window = $whatsAppChatModel->getWindow();
	$qr = $whatsAppChatModel->getQr();
	$welcome = urlencode($whatsAppChatModel->getWelcomeMessage());
	$whatsAppChatUrl = base64_encode("https://wa.me/{$whatsAppChatModel->getPhone()}?text=$welcome");
	$poweredByLogo = IWP_PUBLIC_URL . 'views/whatsAppChat/images/logo.svg';
	$poweredByLink = 'https://iurny.com';

	// Definimos las variables según el tipo de chat definido
	switch ($chatType) {
		case iwpWhatsAppChatModel::CHAT_TYPE_CUSTOMIZED:
			$iconUrl = '';
			$color = $window->getColor();
			$header = $window->getHeader();
			$sleep = $window->getSleep();
			$body = $window->getBody();
			break;
		case iwpWhatsAppChatModel::CHAT_TYPE_QR:
			$iconUrl = '';
			$color = $qr->getColor();
			$header = $qr->getHeader();
			$sleep = '-1';
			$body = '<div id="iwpPublicQrCode" class="iwp-public-whatsAppChat-chat-qr-code"></div>';
			break;
		case iwpWhatsAppChatModel::CHAT_TYPE_DISABLE:
		default:
			$iconUrl = $whatsAppChatUrl;
			$color = '';
			$header = '';
			$sleep = '-1';
			$body = '';
	}
?>

<div id="iwpPublicWhatsAppChat" class="iwp-public-whatsAppChat <?php echo($icon->getPosition()); ?>" data-type="<?php echo($chatType); ?>">
	<div id="iwpPublicWhatsAppChatIcon" class="iwp-public-whatsAppChat-icon-body <?php echo($icon->getBubble()); ?>"
		 data-sleep="<?php echo($icon->getSleep()); ?>" data-url="<?php echo($iconUrl); ?>">
		<div class="iwp-public-whatsAppChat-icon-body-message">
			<div class="iwp-public-whatsAppChat-icon-body-message-box"><?php echo($icon->getBubbleText()); ?></div>
		</div>
		<div class="iwp-public-whatsAppChat-icon-body-icon" style="background-color: <?php echo($icon->getColor()); ?>">
			<img src="<?php echo($icon->getImageUrl()); ?>" alt="">
		</div>
	</div>

	<div id="iwpPublicWhatsAppWindow" class="iwp-public-whatsAppChat-chat-container"
		 data-sleep="<?php echo($sleep); ?>" data-color="<?php echo($color); ?>">
		<div class="iwp-public-whatsAppChat-chat-header">
			<div class="iwp-public-whatsAppChat-chat-header-text"><?php echo($header); ?></div>
			<div class="iwp-public-whatsAppChat-chat-header-close">&times;</div>
		</div>
		<div class="iwp-public-whatsAppChat-chat-body">
			<div class="iwp-public-whatsAppChat-chat-qr-title"><?php echo($qr->getText()); ?></div>
			<div class="iwp-public-whatsAppChat-chat-body-message">
				<div class="iwp-public-whatsAppChat-chat-body-message-box"><?php echo($body); ?></div>
			</div>
			<div class="poweredBy">
				<a href="<?php echo($poweredByLink); ?>" target="_blank">
					<img class="poweredByImg" src="<?php echo($poweredByLogo); ?>" alt="Powered by iurny">
				</a>
			</div>

			<div class="iwp-public-whatsAppChat-chat-body-icon" data-url="<?php echo($whatsAppChatUrl); ?>">
				<div class="iwp-public-whatsAppChat-chat-body-text"><?php echo($window->getButtonText()); ?></div>
				<img src="<?php echo($window->getButtonImage()); ?>" alt="">
			</div>
		</div>
	</div>
</div>
