<?php


	$titleValue = (isset($titleValue) && !empty($titleValue)) ? $titleValue : '';
	$bodyValue = (isset($bodyValue) && !empty($bodyValue)) ? trim($bodyValue) : '';
	$urlValue = (isset($urlValue) && !empty($urlValue)) ? $urlValue : '';
	$topicList = isset($topicList) ? $topicList : array();
	$customTopicsClass = empty($topicList) ? ' disabled ' : '';

	$mimeErrorClass = '';
	if (isset($imageId) && !empty($imageId)) {
		$imageId = (int)$imageId;
		$imageUrl = wp_get_attachment_image_url($imageId, 'full') ?: '';

		$acceptedMimeTypes = ['image/jpeg', 'image/png'];
		$imageURI = wp_get_original_image_path($imageId) ?: '';
		$mime = mime_content_type($imageURI);
		if (!in_array($mime, $acceptedMimeTypes)) {
			$imageUrl = '';
			$mimeErrorClass = 'iwp-error';
		}

		$imageBackgroundImage = "background-image: url('{$imageUrl}')";
		$imageClass = '';
		if (empty($imageUrl)) {
			// Si algunos de los parámetros está vacío, se eliminan todos como si no existiesen la imagen
			$imageId = '';
			$imageUrl = '';
			$imageBackgroundImage = '';
			$imageClass = ' iwp-empty ';
		}
	} else {
		$imageId = '';
		$imageBackgroundImage = '';
		$imageClass = ' iwp-empty ';
	}

	$sendPushLabel = __('Send Push notification on publish', 'iwp-text-domain');
	$titleLabel = __('Title', 'iwp-text-domain');
	$bodyLabel = __('Body', 'iwp-text-domain');
	$urlLabel = __('Landing URL', 'iwp-text-domain');
	$urlTip = __('Web where the user lands&#10;when clicking the notification.', 'iwp-text-domain');
	$imageLabel = __('Notification image', 'iwp-text-domain');
	$imageSelectLabel = strtoupper(__('Select an image', 'iwp-text-domain'));
	$topicsLabel = __('Audience', 'iwp-text-domain');
	$topicsAllLabel = __('Send to all', 'iwp-text-domain');
	$topicsCustomLabel = __('Select audience', 'iwp-text-domain');
	$imageChangeLabel = strtoupper(__('Change image', 'iwp-text-domain'));
	$imageDeleteLabel = __('Delete image', 'iwp-text-domain');

	$tipIcon  = IWP_ADMIN_URL . 'images/question-icon.svg';
	$errorIcon  = IWP_ADMIN_URL . 'images/exclamation-red-icon.svg';
	$errorIconHtml = "<img src='{$errorIcon}' alt=''>";
	$topicError = $errorIconHtml . __('You cannot select your audience because you do not have any interest groups created', 'iwp-text-domain');

	$acceptedImagesLabel = __('Accepted image types: png, jpg', 'iwp-text-domain');
?>
<div id="iwpWidgetContainer" class="iwp-widget-container">
	<label class="iwp-widget-input-inline-group iwp-widget-input-checkbox iwp-first-row">
		<input id="iwpWidgetSend" name="iwpWidgetSend" class="iwp-widget-topics-list-item-value" type="checkbox">
		<i class="iwp-checkbox"></i>
		<div class="iwp-widget-input-checkbox-label"><?php echo($sendPushLabel); ?></div>
	</label>
	<div id="iwpWidgetForm" class="iwp-hide">
		<label class="iwp-widget-group">
			<div class="iwp-widget-input-label"><?php echo($titleLabel); ?></div>
			<input id="iwpWidgetTitle" name="iwpWidgetTitle" class="iwp-widget-input-text" type="text" value="<?php echo($titleValue); ?>">
		</label>
		<label class="iwp-widget-group">
			<div class="iwp-widget-input-label"><?php echo($bodyLabel); ?></div>
			<textarea id="iwpWidgetBody" name="iwpWidgetBody" class="iwp-widget-input-text"><?php echo($bodyValue); ?></textarea>
		</label>
		<label class="iwp-widget-group">
			<div class="iwp-widget-input-label"><?php echo($urlLabel); ?></div>
			<div class="iwp-widget-input-tip">
				<input id="iwpWidgetUrl" name="iwpWidgetUrl" class="iwp-widget-input-text" type="text" value="<?php echo($urlValue); ?>">
				<img src="<?php echo($tipIcon); ?>" class="iwp-widget-input-tip-image" alt="" title="<?php echo($urlTip); ?>">
			</div>
		</label>
		<div class="iwp-widget-group">
			<input id="iwpWidgetImageId" name="iwpWidgetImageId" type="hidden" value="<?php echo($imageId); ?>">
			<div class="iwp-widget-input-label"><?php echo($imageLabel); ?></div>
			<label id="iwpWidgetImageSelect" class="iwp-widget-image-container <?php echo($imageClass); ?>"
				   style="<?php echo($imageBackgroundImage); ?>" data-empty="<?php echo($imageSelectLabel); ?>"></label>
			<div id="iwpWidgetImageButtons" class="iwp-widget-image-buttons <?php echo($imageClass); ?>">
				<button id="iwpWidgetImageEdit" class="iwp-btn iwp-btn-blue" type="button"><?php echo($imageChangeLabel); ?></button>
				<button id="iwpWidgetImageDelete" class="iwp-btn iwp-btn-transparent" type="button"><?php echo($imageDeleteLabel); ?></button>
			</div>
			<div id="iwpWidgetImageMimes" class="iwp-widget-image-mimes <?php echo($mimeErrorClass); ?>"><?php echo($acceptedImagesLabel); ?></div>
		</div>
		<div class="iwp-widget-group iwp-last-row">
			<div class="iwp-widget-input-label"><?php echo($topicsLabel); ?></div>
			<div class="iwp-widget-input-radio-container">
				<label class="iwp-widget-input-inline-group">
					<input name="iwpWidgetTopics" class="iwp-widget-input-radio" type="radio" value="0" checked>
					<div class="iwp-widget-input-radio-label"><?php echo($topicsAllLabel); ?></div>
				</label>
				<label class="iwp-widget-input-inline-group <?php echo($customTopicsClass); ?>">
					<input name="iwpWidgetTopics" class="iwp-widget-input-radio" type="radio" value="1">
					<div class="iwp-widget-input-radio-label"><?php echo($topicsCustomLabel); ?></div>
				</label>
				<?php if (!empty($customTopicsClass)) {
					?>
					<div class="iwp-widget-tiny-error"><?php echo($topicError); ?></div>
					<?php
				} ?>
				<div id="iwpWidgetTopicsList" class="iwp-widget-topics-list-container">
					<div class="iwp-widget-topics-list">
						<?php foreach ($topicList as $topic) { ?>
							<label class="iwp-widget-input-inline-group iwp-widget-input-checkbox">
								<input class="iwp-widget-topics-list-item-value" name="iwpWidgetTopicElements[]" type="checkbox" value="<?php echo($topic->getCode()); ?>">
								<i class="iwp-checkbox"></i>
								<div class="iwp-widget-input-checkbox-label" title="<?php echo($topic->getName()); ?>">
									<?php echo($topic->getName()); ?>
								</div>
							</label>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
