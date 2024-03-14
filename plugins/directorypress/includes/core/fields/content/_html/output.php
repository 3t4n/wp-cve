<?php if (!empty($listing->post->post_content)): ?>
<div class="directorypress-field-item directorypress-field-type-<?php echo esc_attr($field->type); ?> clearfix">
	<?php if ($field->icon_image || !$field->is_hide_name): ?>
	<span class="field-label">
		<?php if ($field->icon_image): ?>
		<span class="directorypress-field-icon fa fa-lg <?php echo esc_attr($field->icon_image); ?>"></span>
		<?php endif; ?>
		<?php if (!$field->is_hide_name): ?>
		<span class="directorypress-field-title"><?php echo esc_html($field->name); ?>:</span>
		<?php endif; ?>
	</span>
	<?php endif; ?>
	<div class="field-content directorypress-field-description" itemprop="description">
		<?php add_filter('the_content', 'wpautop'); ?>
		<?php the_content(); ?>
		<?php remove_filter('the_content', 'wpautop'); ?>
	</div>
</div>
<?php endif; ?>