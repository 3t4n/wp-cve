<?php global $DIRECTORYPRESS_ADIMN_SETTINGS, $directorypress_object; ?>
<div id="misc-publishing-actions">
	<?php if ($directorypress_object->directorytypes->isMultiDirectory()): ?>
	<script>
		(function($) {
			"use strict";
	
			$(function() {
				$("#directory_id").on("change", function() {
					$("#publish").trigger('click');
				});
			});
		})(jQuery);
	</script>
	<div class="misc-pub-section">
		<label for="post_package"><?php _e('Directory', 'DIRECTORYPRESS'); ?>:</label>
		<select id="directory_id" name="directory_id">
			<?php foreach ($directorypress_object->directorytypes->directorypress_array_of_directorytypes AS $directorytype): ?>
			<option value="<?php echo esc_attr($directorytype->id); ?>" <?php selected($directorytype->id, $listing->directorytype->id, true); ?>><?php echo esc_html($directorytype->name); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php endif; ?>

	<div class="misc-pub-section">
		<label for="post_package"><?php _e('Listing package', 'DIRECTORYPRESS'); ?>:</label>
		<span id="post-package-display">
			<?php
			if ($listing->listing_created && $listing->package->is_upgradable())
					echo '<a href="' . admin_url('options.php?page=directorypress_upgrade&listing_id=' . esc_attr($listing->post->ID)) . '">';
			else
				echo '<b>'; ?>
			<?php echo apply_filters('directorypress_create_option', $listing->package->name, $listing); ?>
			<?php
			if ($listing->listing_created && $listing->package->is_upgradable())
				echo '</a>';
			else
				echo '</b>'; ?>
		</span>
	</div>

	<?php if ($listing->listing_created): ?>
	<div class="misc-pub-section">
		<label for="post_package"><?php _e('Listing status', 'DIRECTORYPRESS'); ?>:</label>
		<span id="post-package-display">
			<?php if ($listing->status == 'active'): ?>
			<span class="label label-success"><?php _e('active', 'DIRECTORYPRESS'); ?></span>
			<?php elseif ($listing->status == 'expired'): ?>
			<span class="label label-danger"><?php _e('expired', 'DIRECTORYPRESS'); ?></span><br />
			<a href="<?php echo admin_url('options.php?page=directorypress_renew&listing_id=' . esc_attr($listing->post->ID) ); ?>"><span class="directorypress-fa directorypress-fa-refresh directorypress-fa-lg"></span> <?php echo apply_filters('directorypress_renew_option', __('renew listing', 'DIRECTORYPRESS'), $listing); ?></a>
			<?php elseif ($listing->status == 'unpaid'): ?>
			<span class="label label-warning"><?php _e('unpaid ', 'DIRECTORYPRESS'); ?></span>
			<?php elseif ($listing->status == 'stopped'): ?>
			<span class="label label-danger"><?php _e('stopped', 'DIRECTORYPRESS'); ?></span>
			<?php endif;?>
			<?php do_action('directorypress_listing_status_option', $listing); ?>
		</span>
		<?php if (get_post_meta($listing->post->ID, '_preexpiration_notification_sent', true)): ?><br /><?php _e('Pre-expiration notification was sent', 'DIRECTORYPRESS'); ?><?php endif; ?>
	</div>
	
	<?php
	$post_type_object = get_post_type_object(DIRECTORYPRESS_POST_TYPE);
	$can_publish = current_user_can($post_type_object->cap->publish_posts);
	?>
	<?php if ($can_publish && $listing->status != 'active'): ?>
	<div class="misc-pub-section">
		<input name="directorypress_save_as_active" value="Save as Active" class="button" type="submit">
	</div>
	<?php endif; ?>

	<div class="misc-pub-section">
		<label for="post_package"><?php echo sprintf(__('Total clicks: %d', 'DIRECTORYPRESS'), (get_post_meta($directorypress_object->current_listing->post->ID, '_total_clicks', true) ? get_post_meta($directorypress_object->current_listing->post->ID, '_total_clicks', true) : 0)); ?></label>
	</div>
	<div class="misc-pub-section curtime">
		<span id="timestamp">
			<?php _e('Sorting date', 'DIRECTORYPRESS'); ?>:
			<b><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), intval($listing->order_date)); ?></b>
			<?php if ($listing->package->can_be_bumpup && $listing->status == 'active'): ?>
			<br />
			<a href="<?php echo admin_url('options.php?page=directorypress_raise_up&listing_id=' . esc_attr($listing->post->ID) ); ?>"><span class="directorypress-fa directorypress-fa-package-up directorypress-fa-lg"></span> <?php echo apply_filters('directorypress_raiseup_option', __('raise up listing', 'DIRECTORYPRESS'), $listing); ?></a>
			<?php endif; ?>
		</span>
	</div>

	<?php if ($listing->package->package_no_expiry || $listing->expiration_date): ?>
	<div class="misc-pub-section curtime">
		<span id="timestamp">
			<?php _e('Expire on', 'DIRECTORYPRESS'); ?>:
			<?php if ($listing->package->package_no_expiry): ?>
			<b><?php _e('Eternal active period', 'DIRECTORYPRESS'); ?></b>
			<?php else: ?>
			<b><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), intval($listing->expiration_date)); ?></b>
			<?php endif; ?>
		</span>
	</div>
	<?php endif; ?>
	
	<?php do_action('directorypress_listing_info_metabox_html', $listing); ?>

	<?php endif; ?>
</div>