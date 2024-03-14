<div class="mbp-linkback-button">
	<div class="mbp-linkback-button-inner">
		<div class="mbp-linkback-heart">
			<div class="mbp-linkback-heart-whole"></div>
			<div class="mbp-linkback-heart-right"></div>
			<div class="mbp-linkback-heart-left"></div>
		</div>
		<div class="mbp-linkback-button-content">
			<input type="checkbox" class="mbp-enable-linkback" <?php checked(mbp_get_setting('enable_linkback'), true); ?> >
			<div class="mbp-linkback-messages">
				<div class="mbp-linkback-message mbp-linkback-message-disable"><?php _e('Awww...', 'mybookprogress'); ?> :(</div>
				<div class="mbp-linkback-message mbp-linkback-message-disabled"><?php _e('Share the love! Link back to Author Media.', 'mybookprogress'); ?></div>
				<div class="mbp-linkback-message mbp-linkback-message-enable"><?php _e('Thank you!', 'mybookprogress'); ?></div>
				<div class="mbp-linkback-message mbp-linkback-message-enabled"><?php _e('You are linking back to Author Media.', 'mybookprogress'); ?></div>
			</div>
		</div>
	</div>
</div>