<p>
	<label for="<?php echo esc_attr($widget->get_field_id('title')); ?>"><?php _e('Title:'); ?></label> 
	<input class="widefat" id="<?php echo esc_attr($widget->get_field_id('title')); ?>" name="<?php echo esc_attr($widget->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
</p>
