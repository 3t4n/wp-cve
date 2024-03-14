<div class="mbp-section-header"><?php _e('MyBookTable Integration', 'mybookprogress'); ?></div>
<div class="mbp-section-content">
	<div class="mbp-setting mbp-setting-row mbp-mybooktable-social-media-link-setting">
		<div class="mbp-setting-checkbox"><input type="checkbox" class="mbp-mybooktable-social-media-link" id="mbp_mybooktable_social_media_link"></div>
		<div class="mbp-setting-label"><label for="mbp_mybooktable_social_media_link"><?php _e('Include MyBookTable book link when sharing on social media', 'mybookprogress'); ?></label></div>
	</div>
	<div class="mbp-setting mbp-setting-row mbp-mybooktable-frontend-link-setting">
		<div class="mbp-setting-checkbox"><input type="checkbox" class="mbp-mybooktable-frontend-link" id="mbp_mybooktable_frontend_link"></div>
		<div class="mbp-setting-label"><label for="mbp_mybooktable_frontend_link"><?php _e("Show MyBookTable book link next to my book's progress bar", 'mybookprogress'); ?></label></div>
	</div>
</div>
<div class="mbp-disabled-message"><div class="mbp-disabled-message-inner">
	<?php _e('Install the free MyBookTable plugin to get these features!', 'mybookprogress'); ?><br>
	<div class="mbp-button-container mbp-install-mybooktable-button-container">
		<div class="mbp-install-mybooktable-button" data-mbp-link="<?php echo(admin_url('plugin-install.php?tab=plugin-information&plugin=mybooktable')); ?>"><?php _e('Lets Do It!', 'mybookprogress'); ?></div>
	</div>
</div></div>