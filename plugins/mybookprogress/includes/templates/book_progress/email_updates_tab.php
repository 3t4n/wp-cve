<div class="mbp-book-tab-inner mbp-email-updates-tab">
	<div class="mbp-setting mbp-setting-row mbp-email-updates-email-setting">
		<div class="mbp-setting-number">1.</div>
		<div class="mbp-setting-label"><?php _e('Email address', 'mybookprogress'); ?>:</div>
		<div class="mbp-setting-value">
			<input type="text" class="mbp-email-updates-email" spellcheck="false" data-default="<?php echo(wp_get_current_user()->user_email); ?>">
			<div class="mbp-setting-feedback"><div class="mbp-setting-feedback-icon"></div></div>
			<div class="mbp-email-updates-test-email-button"><?php _e('Send test email', 'mybookprogress'); ?></div>
		</div>
	</div>
	<div class="mbp-setting mbp-setting-row mbp-email-updates-period-setting">
		<div class="mbp-setting-number">2.</div>
		<div class="mbp-setting-label"><?php _e('Update frequency', 'mybookprogress'); ?>:</div>
		<div class="mbp-setting-value"><div class="mbp-email-updates-periods"></div></div>
	</div>
</div>
<div class="mbp-disabled-message"><div class="mbp-disabled-message-inner">
	<?php _e('Upgrade your MyBookProgress to get these features!', 'mybookprogress'); ?><br>
	<div class="mbp-button-container mbp-upsell-button-container">
		<div class="mbp-upsell-button"><?php _e('Lets Do It!', 'mybookprogress'); ?></div>
	</div>
</div></div>