<h2>
	<?php _e('Change package of listings', 'DIRECTORYPRESS'); ?>
</h2>

<p><?php _e('The package of listings will be changed. You may upgrade or downgrade the package. If new package has an option of limited active period - expiration date of listing will be recalculated automatically.', 'DIRECTORYPRESS'); ?></p>

<form action="<?php echo admin_url('options.php?page=directorypress_upgrade_bulk&listings_ids=' . implode(',', $listings_ids) . '&upgrade_action=upgrade&referer=' . urlencode($referer)); ?>" method="POST">
	<?php if ($action == 'show'): ?>
	<h3><?php _e('Change package of following listings', 'DIRECTORYPRESS'); ?></h3>
	<ul>
	<?php foreach ($listings_ids AS $listing_id): ?>
	<?php $listing = new directorypress_listing; ?>
	<?php $listing->directorypress_init_lpost_listing($listing_id); ?>
	<li><?php echo esc_html($listing->title()); ?></li>
	<?php endforeach; ?>
	</ul>

	<h3><?php _e('Choose new package', 'DIRECTORYPRESS'); ?></h3>
	<?php foreach ($packages->packages_array AS $package): ?>
	<p>
		<label><input type="radio" name="new_package_id" value="<?php echo esc_attr($package->id); ?>" /> <?php echo apply_filters('directorypress_package_upgrade_option', $package->name, $listing->package, $package); ?></label>
	</p>
	<?php endforeach; ?>

	<input type="submit" value="<?php esc_attr_e('Change package', 'DIRECTORYPRESS'); ?>" class="button button-primary" id="submit" name="submit">
	&nbsp;&nbsp;&nbsp;
	<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php _e('Cancel', 'DIRECTORYPRESS'); ?></a>
	<?php elseif ($action == 'upgrade'): ?>
	<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php _e('Go back ', 'DIRECTORYPRESS'); ?></a>
	<?php endif; ?>
</form>

