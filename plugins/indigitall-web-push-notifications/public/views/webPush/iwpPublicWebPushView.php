<?php
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpAdminUtils.php';
	$color		= (isset($color) && !empty($color)) ? $color : "#0f3b7a";
	$bgColor = iwpAdminUtils::hexToRgba($color, '0.12');
	$isLight = iwpAdminUtils::hexColorIsLight($color);
	$textColor = $isLight ? "#0f3b7a" : "#000000";
	$buttonColor = $isLight ? "#0f3b7a" : "#ffffff";

	/* Traducciones */
	$title 		 = __('What interests you?','iwp-text-domain');
	$description = __('Select the <b>topics of interest</b> on which <br>you want to receive news:','iwp-text-domain');
	$saveButton  = strtoupper(__('Save','iwp-text-domain'));
	$tip 		 = __('From now on you will receive notifications from our website based on this selection, according to your tastes and interests.', 'iwp-text-domain');
?>
<style>
	.iwp-public-topics-modal-list-item.selected {
		color: <?php echo($textColor); ?> !important;
		background-color: <?php echo($bgColor); ?> !important;
		border-color: <?php echo($bgColor); ?> !important;
	}
	.iwp-public-topics-modal-list-item.selected .iwp-public-topics-modal-list-item-check {
		background-color: <?php echo($color); ?> !important;
	}
	.iwp-public-topics-modal-btn {
		color: <?php echo($buttonColor); ?> !important;
		background-color: <?php echo($color); ?> !important;
	}
</style>
<!-- Modal -->
<div id="topicsModal" class="iwp-public-topics-modal-backdrop iwp-hide">
	<div class="iwp-public-topics-modal">
		<div class="iwp-public-topics-modal-header">
			<i id="iwpPublicTopicsModalClose" class="iwp-public-topics-modal-close-icon"></i>
		</div>
		<div class="iwp-public-topics-modal-body">
			<div class="iwp-public-topics-modal-body-title"><?php echo($title); ?></div>
			<div class="iwp-public-topics-modal-body-description"><?php echo($description); ?></div>
			<ul class="iwp-public-topics-modal-list" id="topicsUl"></ul>
		</div>
		<div class="iwp-public-topics-modal-footer">
			<button id="sendTopics" class="iwp-public-topics-modal-btn" type="button"><?php echo($saveButton); ?></button>
			<div class="iwp-public-topics-modal-footer-tip"><?php echo($tip); ?></div>
		</div>
	</div>
</div>