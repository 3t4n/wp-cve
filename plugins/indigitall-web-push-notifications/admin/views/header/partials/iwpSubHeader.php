<?php
	$appName = isset($appName) ? $appName : '';
	$appKey = isset($appKey) ? $appKey : '';
?>

<div class="iwp-admin-subheader-container">
	<div class="iwp-admin-subheader-info">
		<div class="iwp-admin-subheader-info-row" id="iwpAppName">
			<span class="iwp-admin-subheader-info-row-title"><?php _e('Connected to','iwp-text-domain') ?>: </span>
			<span class="iwp-admin-subheader-info-row-data"><?php echo($appName); ?></span>
		</div>
		<div class="iwp-admin-subheader-info-row" id="iwpAppKey">
			<span class="iwp-admin-subheader-info-row-title"><?php _e('appKey','iwp-text-domain') ?>: </span>
			<span class="iwp-admin-subheader-info-row-data"><?php echo($appKey); ?></span>
		</div>
	</div>
	<div id="iwp-logout" class="iwp-admin-subheader-logout">
		<i class="iwp-logout-icon"></i>
		<span><?php _e('Disconnect','iwp-text-domain') ?></span>
	</div>
</div>
