<?php defined('WPINC') || die; ?>


<p>
    <label for="<?php echo $instance->get_field_id('title'); ?>"> <?php _e('Title'); ?></label>
    <input class="widefat"
           id="<?php echo $instance->get_field_id('title'); ?>"
           name="<?php echo $instance->get_field_name('title'); ?>"
           type="text"
           value="<?php echo esc_attr($title); ?>"

    >
</p>