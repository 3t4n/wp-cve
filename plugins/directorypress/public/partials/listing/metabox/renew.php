<h2>
	<?php echo apply_filters('directorypress_renew_option', __('Renew listing', 'DIRECTORYPRESS'), $listing); ?>
</h2>

<p><?php _e('Listing will be renewed and raised up to the top of all lists, those ordered by date.', 'DIRECTORYPRESS'); ?></p>

<?php do_action('directorypress_renew_html', $listing); ?>

<?php if ($action == 'show'): ?>
<a href="<?php echo admin_url('options.php?page=directorypress_renew&listing_id=' . esc_attr($listing->post->ID) . '&renew_action=renew&referer=' . urlencode($referer)); ?>" class="button button-primary"><?php _e('Renew listing', 'DIRECTORYPRESS'); ?></a>
&nbsp;&nbsp;&nbsp;
<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php _e('Cancel', 'DIRECTORYPRESS'); ?></a>
<?php elseif ($action == 'renew'): ?>
<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php _e('Go back ', 'DIRECTORYPRESS'); ?></a>
<?php endif; ?>

