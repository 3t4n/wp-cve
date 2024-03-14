<div class="wrap about-wrap directorypress-admin-wrap">
	<?php DirectoryPress_Admin_Panel::listing_dashboard_header(); ?>
	<div class="directorypress-plugins directorypress-theme-browser-wrap">
		<div class="theme-browser rendered">
			<div class="directorypress-box">
				<div class="directorypress-box-head">
					<?php echo sprintf(__('Change package of listing "%s"', 'DIRECTORYPRESS'), $listing->title()); ?>
				</div>
				<div class="directorypress-box-content wp-clearfix">
					<p><?php _e('The package of listing will be changed. You may upgrade or downgrade the package. If new package has an option of limited active period - expiration date of listing will be reassigned automatically.', 'DIRECTORYPRESS'); ?></p>

					<form action="<?php echo admin_url('options.php?page=directorypress_upgrade&listing_id=' . esc_attr($listing->post->ID) . '&upgrade_action=upgrade&referer=' . urlencode($referer)); ?>" method="POST">
						<?php if ($action == 'show'): ?>
						<h3><?php _e('Choose new package', 'DIRECTORYPRESS'); ?></h3>
						<?php foreach ($packages->packages_array AS $package): ?>
						<?php if ($listing->package->id != $package->id && (!isset($listing->package->upgrade_meta[$package->id]) || !$listing->package->upgrade_meta[$package->id]['disabled'] || (current_user_can('editor') || current_user_can('administrator')))): ?>
						<p>
							<label><input type="radio" name="new_package_id" value="<?php echo esc_attr($package->id); ?>" /> <?php echo apply_filters('directorypress_package_upgrade_option', $package->name, $listing->package, $package); ?></label>
						</p>
						<?php endif; ?>
						<?php endforeach; ?>

						<input type="submit" value="<?php esc_attr_e('Change package', 'DIRECTORYPRESS'); ?>" class="button button-primary" id="submit" name="submit">
						&nbsp;&nbsp;&nbsp;
						<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php _e('Cancel', 'DIRECTORYPRESS'); ?></a>
						<?php elseif ($action == 'upgrade'): ?>
						<a href="<?php echo esc_url($referer); ?>" class="button button-primary"><?php _e('Go back ', 'DIRECTORYPRESS'); ?></a>
						<?php endif; ?>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
