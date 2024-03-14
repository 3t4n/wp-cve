<?php
	require_once IWP_ADMIN_PATH . 'models/iwpWebPushModel.php';

	$previewBackgroundImage = IWP_ADMIN_URL . 'views/webPush/images/mobile-background-image.png';
	$chromeIcon = IWP_ADMIN_URL . 'images/chrome-icon.svg';
	$editIcon = IWP_ADMIN_URL . 'images/edit-icon.svg';
	$deleteIcon = IWP_ADMIN_URL . 'images/delete-icon.svg';

	$webPushModel 	= (isset($webPushModel) && $webPushModel->isWelcomePush()) ? $webPushModel : new iwpWebPushModel();
	$isNew			= (is_null($webPushModel->getCampaignId()));
	$title 			= $webPushModel->getTitle();
	$body 			= $webPushModel->getBody();
	$url 			= $webPushModel->getUrl();
	$enabled 		= $webPushModel->isEnabled();
	$imageId		= !is_null($webPushModel->getImageId()) ? $webPushModel->getImageId() : '';
	$imageUrl		= ($webPushModel->getImageUrl() !== '') ? "url('{$webPushModel->getImageUrl()}')" : '';
	$imageName		= (!empty($imageUrl)) ? basename($imageUrl) : '';
	$imageSize		= (!empty($imageId)) ? size_format(filesize(get_attached_file($imageId)), 0) : iwpAdminUtils::get_remote_filesize($webPushModel->getImageUrl());

	$hasImage = !empty($imageUrl);
	$isServerImage = ($hasImage && empty($imageId));

	$buttonCreateClass = $isNew ? '' : 'iwp-hide';
	$buttonUpdateClass = !$isNew ? '' : 'iwp-hide';
	$buttonDisableClass = (!$isNew && $enabled) ? '' : 'iwp-hide';
	$buttonEnableClass = (!$isNew && !$enabled) ? '' : 'iwp-hide';

	$mainTitle 				= __('Send a <b>welcome message</b> for your new subscribers', 'iwp-text-domain');
	$mainSubtitle 			= __('Set up the message that the users will be receive when they accept notification permission', 'iwp-text-domain');
	$fieldTitleLabel 		= __('Title', 'iwp-text-domain');
	$fieldTitlePlaceHolder 	= __('Ex. Welcome to iurny', 'iwp-text-domain');
	$fieldBodyLabel 		= __('Body', 'iwp-text-domain');
	$fieldBodyPlaceHolder 	= __('Ex. This weekend we have...', 'iwp-text-domain');
	$fieldImageLabel 		= __('Multimedia', 'iwp-text-domain');
	$fieldImageUploadLabel	= strtoupper(__('Upload an image', 'iwp-text-domain'));
	$fieldUrlLabel 			= __('Url', 'iwp-text-domain');
	$fieldUrlPlaceHolder 	= __('Ex. https://iurny.com', 'iwp-text-domain');
	$buttonDisable 			= __('Disable', 'iwp-text-domain');
	$buttonEnable 			= __('Enable', 'iwp-text-domain');
	$buttonUpdate 			= __('Update', 'iwp-text-domain');
	$buttonCreate 			= __('Save & Activate', 'iwp-text-domain');
	$previewHeader 			= __('Chrome · Now', 'iwp-text-domain');
	$serverImageLabel		= __('Server image', 'iwp-text-domain');
?>
<div class="iwp-admin-webPush-welcome">
	<div class="iwp-admin-webPush-welcome-form">
		<!-- Error label -->
		<div id="iwp-admin-error-box" class="iwp-admin-error-box iwp-hide"></div>
		<!-- Success label -->
		<div id="iwp-admin-success-box" class="iwp-admin-success-box iwp-hide"></div>

		<div class="iwp-admin-webPush-welcome-form-title"><?php echo($mainTitle); ?></div>
		<div class="iwp-admin-webPush-welcome-form-subtitle"><?php echo($mainSubtitle); ?></div>

		<label class="iwp-admin-webPush-welcome-form-label" for="iwpAdminWebPushWelcomeTitle">
			<span class="iwp-admin-webPush-welcome-form-label-text"><?php echo($fieldTitleLabel); ?></span>
			<input id="iwpAdminWebPushWelcomeTitle" name="iwpAdminWebPushWelcomeTitle"
				   class="iwp-admin-webPush-welcome-form-label-input" type="text"
				   value="<?php echo($title); ?>" placeholder="<?php echo($fieldTitlePlaceHolder); ?>">
		</label>

		<label class="iwp-admin-webPush-welcome-form-label" for="iwpAdminWebPushWelcomeBody">
			<span class="iwp-admin-webPush-welcome-form-label-text"><?php echo($fieldBodyLabel); ?></span>
			<textarea id="iwpAdminWebPushWelcomeBody"
					  name="iwpAdminWebPushWelcomeBody" class="iwp-admin-webPush-welcome-form-label-textarea"
					  placeholder="<?php echo($fieldBodyPlaceHolder); ?>"><?php echo($body); ?></textarea>
		</label>

		<div class="iwp-admin-webPush-welcome-form-label">
			<span class="iwp-admin-webPush-welcome-form-label-text"><?php echo($fieldImageLabel); ?></span>
			<div class="iwp-admin-webPush-welcome-form-label-image">
				<div id="iwpAdminWebPushWelcomeAddImage"
					 class="iwp-admin-webPush-welcome-form-label-image-empty <?php echo($hasImage ? 'iwp-hide' : ''); ?>">
					<?php echo($fieldImageUploadLabel); ?>
				</div>

				<div id="iwpAdminWebPushWelcomePreviewImage"
					 class="iwp-admin-webPush-welcome-form-label-image-content <?php echo(!$hasImage ? 'iwp-hide' : ''); ?>">
					<input type="hidden" id="iwpAdminWebPushWelcomeImageId" value="<?php echo($imageId); ?>">

					<div class="iwp-admin-webPush-welcome-form-label-image-preview"
						 style="background-image: <?php echo($imageUrl); ?>"></div>

					<div class="iwp-admin-webPush-welcome-form-label-image-info">
						<div class="iwp-admin-webPush-welcome-form-label-image-info-name">
							<?php if ($isServerImage) {
								echo($serverImageLabel);
							} else if(!empty($imageName)) {
								echo($imageName);
							} ?>
						</div>
						<div class="iwp-admin-webPush-welcome-form-label-image-info-size">
							<?php if (!empty($imageSize)) { echo($imageSize); } ?>
						</div>
					</div>

					<div id="iwpAdminWebPushWelcomeEditImage"
						 class="iwp-admin-webPush-welcome-form-label-image-edit">
						<img src="<?php echo($editIcon); ?>" alt="" class="<?php echo($isServerImage ? 'iwp-hide' : ''); ?>">
					</div>

					<div id="iwpAdminWebPushWelcomeRemoveImage"
						 class="iwp-admin-webPush-welcome-form-label-image-delete">
						<img src="<?php echo($deleteIcon); ?>" alt="">
					</div>
				</div>
			</div>
		</div>

		<label class="iwp-admin-webPush-welcome-form-label" for="iwpAdminWebPushWelcomeUrl">
			<span class="iwp-admin-webPush-welcome-form-label-text"><?php echo($fieldUrlLabel); ?></span>
			<input id="iwpAdminWebPushWelcomeUrl" name="iwpAdminWebPushWelcomeUrl"
				   class="iwp-admin-webPush-welcome-form-label-input" type="text" value="<?php echo($url); ?>"
				   placeholder="<?php echo($fieldUrlPlaceHolder); ?>">
		</label>

		<div id="iwpAdminWebPushWelcomeFormButtons" class="iwp-admin-webPush-welcome-form-button">
			<button id="iwpAdminWebPushWelcomeCreate" class="iwp-btn iwp-btn-green <?php echo($buttonCreateClass); ?>"
					type="button"><?php echo($buttonCreate); ?></button>
			<button id="iwpAdminWebPushWelcomeDisable" class="iwp-btn iwp-btn-red <?php echo($buttonDisableClass); ?>"
					type="button"><?php echo($buttonDisable); ?></button>
			<button id="iwpAdminWebPushWelcomeEnable" class="iwp-btn iwp-btn-green <?php echo($buttonEnableClass); ?>"
					type="button"><?php echo($buttonEnable); ?></button>
			<button id="iwpAdminWebPushWelcomeUpdate" class="iwp-btn iwp-btn-blue  <?php echo($buttonUpdateClass); ?>"
					type="button"><?php echo($buttonUpdate); ?></button>
		</div>
	</div>
	<div id="iwpAdminWebPushWelcomePreview" class="iwp-admin-webPush-welcome-preview">
		<img src="<?php echo($previewBackgroundImage); ?>" alt="">
		<div class="iwp-admin-webPush-welcome-preview-info">
			<div class="iwp-admin-webPush-welcome-preview-info-header">
				<img src="<?php echo($chromeIcon); ?>" alt="">
				<?php echo($previewHeader); ?>
			</div>
			<a href="#" target="_blank" class="iwp-admin-webPush-welcome-preview-info-url">
				<div class="iwp-admin-webPush-welcome-preview-info-image"></div>
				<div class="iwp-admin-webPush-welcome-preview-info-title"></div>
				<div class="iwp-admin-webPush-welcome-preview-info-body"></div>
			</a>
		</div>
	</div>
</div>