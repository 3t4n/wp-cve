<h2>
	<?php echo apply_filters('directorypress_raiseup_option', sprintf(__('Raise up listing "%s"', 'DIRECTORYPRESS'), $listing->title()), $listing); ?>
</h2>

<p><?php _e('Listing will be raised up to the top of all lists, those ordered by date.', 'DIRECTORYPRESS'); ?></p>
<p><?php _e('Note, that listing will not stick on top, so new listings and other listings, those were raised up later, will place higher.', 'DIRECTORYPRESS'); ?></p>

<?php do_action('directorypress_raise_up_html', $listing); ?>

<?php if ($action == 'show'): ?>
<a href="<?php echo admin_url('options.php?page=directorypress_raise_up&listing_id=' . esc_attr($listing->post->ID) . '&raiseup_action=raiseup&referer=' . urlencode($referer)); ?>" class="button button-primary"><?php _e('Raise up', 'DIRECTORYPRESS'); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php _e('Cancel', 'DIRECTORYPRESS'); ?></a>
<?php elseif ($action == 'raiseup'): ?>
<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php _e('Go back ', 'DIRECTORYPRESS'); ?></a>
<?php endif; ?>

