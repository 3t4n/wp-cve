<?php
	$notifications = isset($notifications) ? $notifications : null;
	$location = isset($location) ? $location : null;
?>

<div class="iwp-admin-webPush-config">
	<?php if (!is_null($notifications)) { ?>
		<div class="iwp-admin-webPush-config-item">
			<div class="iwp-admin-webPush-config-item-title"><?php echo($notifications['title']); ?></div>
			<label id="<?php echo($notifications['id']); ?>" class="iwp-checkbox-container iwp-admin-webPush-config-item-status" for="webPushNotifications">
				<input type="checkbox" id="webPushNotifications" name="webPushNotifications" <?php echo($notifications['checked']); ?>>
				<i class="iwp-checkbox checked"></i>
				<i class="iwp-checkbox unchecked"></i>
				<span class="iwp-admin-webPush-config-item-status-label"><?php echo($notifications['label']); ?></span>
			</label>
		</div>
	<?php } ?>
	<?php if (!is_null($location)) { ?>
		<div class="iwp-admin-webPush-config-item">
			<div class="iwp-admin-webPush-config-item-title"><?php echo($location['title']); ?></div>
			<label id="<?php echo($location['id']); ?>" class="iwp-checkbox-container iwp-admin-webPush-config-item-status" for="webPushLocation">
				<input type="checkbox" id="webPushLocation" name="webPushLocation" <?php echo($location['checked']); ?>>
				<i class="iwp-checkbox checked"></i>
				<i class="iwp-checkbox unchecked"></i>
				<span class="iwp-admin-webPush-config-item-status-label"><?php echo($location['label']); ?></span>
			</label>
		</div>
	<?php } ?>
</div>