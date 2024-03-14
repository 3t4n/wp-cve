<div class="directorypress-fields-group group-style-<?php echo esc_attr($fields_group->group_style); ?>">
	<?php if (!$fields_group->on_tab): ?>
	<div class="directorypress-fields-group-caption"><?php echo esc_html($fields_group->name); ?></div>
	<?php endif; ?>
	<?php if (!$fields_group->hide_anonymous || is_user_logged_in()): ?>
		<?php foreach ($fields_group->fields_array AS $field): ?>
			<?php if ((!$is_single || ($is_single && $field->on_listing_page)) && $field->is_field_not_empty($listing)): ?>
				<?php $field->display_output($listing); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php elseif ($fields_group->hide_anonymous && !is_user_logged_in()): ?>
		<?php printf(__('You must be <a href="%s">logged in</a> to see this info', 'DIRECTORYPRESS'), wp_login_url(get_permalink($listing->post->ID))); ?>
	<?php endif; ?>
</div>