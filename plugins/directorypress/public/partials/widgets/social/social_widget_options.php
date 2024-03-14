<p>
	<label for="<?php echo esc_attr($widget->get_field_id('title')); ?>"><?php _e('Title:'); ?></label> 
	<input class="widefat" id="<?php echo esc_attr($widget->get_field_id('title')); ?>" name="<?php echo esc_attr($widget->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
</p>
<p>
	<label for="<?php echo esc_attr($widget->get_field_id('facebook')); ?>"><?php _e('Facebook URL:'); ?></label>
	<input class="widefat" id="<?php echo esc_attr($widget->get_field_id('facebook')); ?>" name="<?php echo esc_attr($widget->get_field_name('facebook')); ?>" type="text" value="<?php echo esc_url($instance['facebook']); ?>" />
</p>
<p>
	<input id="<?php echo esc_attr($widget->get_field_id('is_facebook')); ?>" name="<?php echo esc_attr($widget->get_field_name('is_facebook')); ?>" type="checkbox" value="1" <?php checked($instance['is_facebook'], 1, true); ?> />
	<label for="<?php echo esc_attr($widget->get_field_id('is_facebook')); ?>"><?php _e('Show Facebook Button', 'DIRECTORYPRESS'); ?></label>
</p>
<p>
	<label for="<?php echo esc_attr($widget->get_field_id('twitter')); ?>"><?php _e('Twitter URL:'); ?></label>
	<input class="widefat" id="<?php echo esc_attr($widget->get_field_id('twitter')); ?>" name="<?php echo esc_attr($widget->get_field_name('twitter')); ?>" type="text" value="<?php echo esc_url($instance['twitter']); ?>" />
</p>
<p>
	<input id="<?php echo esc_attr($widget->get_field_id('is_twitter')); ?>" name="<?php echo esc_attr($widget->get_field_name('is_twitter')); ?>" type="checkbox" value="1" <?php checked($instance['is_twitter'], 1, true); ?> />
	<label for="<?php echo esc_attr($widget->get_field_id('is_twitter')); ?>"><?php _e('Show Twitter Button', 'DIRECTORYPRESS'); ?></label>
</p>
<p>
	<label for="<?php echo esc_attr($widget->get_field_id('linkedin')); ?>"><?php _e('LinkedIn URL:'); ?></label>
	<input class="widefat" id="<?php echo esc_attr($widget->get_field_id('linkedin')); ?>" name="<?php echo esc_attr($widget->get_field_name('linkedin')); ?>" type="text" value="<?php echo esc_url($instance['linkedin']); ?>" />
</p>
<p>
	<input id="<?php echo esc_attr($widget->get_field_id('is_linkedin')); ?>" name="<?php echo esc_attr($widget->get_field_name('is_linkedin')); ?>" type="checkbox" value="1" <?php checked($instance['is_linkedin'], 1, true); ?> />
	<label for="<?php echo esc_attr($widget->get_field_id('is_linkedin')); ?>"><?php _e('Show LinkedIn Button', 'DIRECTORYPRESS'); ?></label>
</p>
<p>
	<label for="<?php echo esc_attr($widget->get_field_id('youtube')); ?>"><?php _e('YouTube URL:'); ?></label>
	<input class="widefat" id="<?php echo esc_attr($widget->get_field_id('youtube')); ?>" name="<?php echo esc_attr($widget->get_field_name('youtube')); ?>" type="text" value="<?php echo esc_url($instance['youtube']); ?>" />
</p>
<p>
	<input id="<?php echo esc_attr($widget->get_field_id('is_youtube')); ?>" name="<?php echo esc_attr($widget->get_field_name('is_youtube')); ?>" type="checkbox" value="1" <?php checked($instance['is_youtube'], 1, true); ?> />
	<label for="<?php echo esc_attr($widget->get_field_id('is_youtube')); ?>"><?php _e('Show YouTube Button', 'DIRECTORYPRESS'); ?></label>
</p>
<p>
	<label for="<?php echo esc_attr($widget->get_field_id('rss')); ?>"><?php _e('RSS URL:'); ?></label>
	<input class="widefat" id="<?php echo esc_attr($widget->get_field_id('rss')); ?>" name="<?php echo esc_attr($widget->get_field_name('rss')); ?>" type="text" value="<?php echo esc_url($instance['rss']); ?>" />
</p>
<p>
	<input id="<?php echo esc_attr($widget->get_field_id('is_rss')); ?>" name="<?php echo esc_attr($widget->get_field_name('is_rss')); ?>" type="checkbox" value="1" <?php checked($instance['is_rss'], 1, true); ?> />
	<label for="<?php echo esc_attr($widget->get_field_id('is_rss')); ?>"><?php _e('Show RSS Button', 'DIRECTORYPRESS'); ?></label>
</p>
<p>
	<input id="<?php echo esc_attr($widget->get_field_name('visibility')); ?>" name="<?php echo esc_attr($widget->get_field_name('visibility')); ?>" type="checkbox" value="1" <?php checked($instance['visibility'], 1, true); ?> />
	<label for="<?php echo esc_attr($widget->get_field_id('visibility')); ?>"><?php _e('Show only on directory pages'); ?></label> 
</p>