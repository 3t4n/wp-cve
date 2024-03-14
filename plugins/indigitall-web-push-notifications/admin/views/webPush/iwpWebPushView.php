<?php
	$subPageHtml = isset($subPageHtml) ? $subPageHtml : '';
	$mainMenuHtml = isset($mainMenuHtml) ? $mainMenuHtml : '';
	$topicModal = isset($topicModal) ? $topicModal : '';

	$status = isset($status) ? $status : '';
	$statusValue = ($status === 'enabled') ? '1' : '0';
	$enabled = strtoupper(__('Activated', 'iwp-text-domain'));
	$disabled = strtoupper(__('Deactivated', 'iwp-text-domain'));
	$webPushTitle = __('webPushTitle', 'iwp-text-domain');
?>

<div class="iwp-admin-webPush">
	<div class="iwp-admin-webPush-header">
		<div class="iwp-admin-webPush-title"><?php echo($webPushTitle); ?></div>
		<div class="iwp-admin-webPush-status">
			<div id="iwpWebPushStatusSwitch" class="iwp-admin-switch-container <?php echo($status); ?>">
				<input type="hidden" class="iwp-admin-switch-value" value="<?php echo($statusValue); ?>">
				<div class="iwp-admin-switch">
					<div class="iwp-admin-switch-ball"></div>
				</div>
				<div class="iwp-admin-switch-label">
					<div class="iwp-admin-switch-label-disabled"><?php echo($disabled); ?></div>
					<div class="iwp-admin-switch-label-enabled"><?php echo($enabled); ?></div>
				</div>
			</div>
		</div>
	</div>
	<div id="iwpAdminWebPushContent" class="iwp-admin-logged-content iwp-admin-webPush-content">
		<?php echo($mainMenuHtml); ?>

		<?php echo($subPageHtml); ?>
	</div>
</div>
<?php echo($topicModal); ?>