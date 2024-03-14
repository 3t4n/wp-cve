<div class='chaport-status-box <?php echo $status_class ?>'>
	<b><?php echo __('Status:', 'chaport') ?></b>
	<span><?php echo $status_message ?></span>
</div>
<p id="chaport-paste-code">
	<?php if ($status_class != 'chaport-status-ok') {
		printf(
		__('Please fill in the field below. You can find the relevant information at <a target="_blank" href="%s">Settings -> Installation code</a> in Chaport app.', 'chaport'),
		'https://app.chaport.com/#/settings/installation_code'
		);
	} ?>
</p>
