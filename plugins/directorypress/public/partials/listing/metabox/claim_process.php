<h2>
	<?php printf(__('Approve or decline claim of listing "%s"', 'DIRECTORYPRESS'), $listing->title()); ?>
</h2>

<?php if ($action == 'show'): ?>
<p><?php printf(__('User "%s" had claimed this listing.', 'DIRECTORYPRESS'), $listing->claim->claimer->display_name); ?></p>
<?php if ($listing->claim->claimer_message): ?>
<p><?php _e('Message from claimer:', 'DIRECTORYPRESS'); ?><br /><i><?php echo esc_html($listing->claim->claimer_message); ?></i></p>
<?php endif; ?>
<p><?php _e('Claimer will receive email notification.', 'DIRECTORYPRESS'); ?></p>

<a href="<?php echo admin_url('options.php?page=directorypress_process_claim&listing_id=' . esc_attr($listing->post->ID) . '&claim_action=approve&referer=' . urlencode($referer)); ?>" class="button button-primary"><?php _e('Approve', 'DIRECTORYPRESS'); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo admin_url('options.php?page=directorypress_process_claim&listing_id=' . esc_attr($listing->post->ID) . '&claim_action=decline&referer=' . urlencode($referer)); ?>" class="button button-primary"><?php _e('Decline', 'DIRECTORYPRESS'); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php _e('Cancel', 'DIRECTORYPRESS'); ?></a>
<?php elseif ($action == 'processed'): ?>
<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php _e('Go back ', 'DIRECTORYPRESS'); ?></a>
<?php endif; ?>

