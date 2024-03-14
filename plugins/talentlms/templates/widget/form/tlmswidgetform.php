<p>
    <label for="<?php echo esc_attr($titleId); ?>"><?php esc_attr_e('Title:', 'talentlms'); ?></label>
    <input class="widefat"
           id="<?php echo esc_attr($titleId); ?>"
           name="<?php echo esc_attr($titleName); ?>"
           type="text"
           value="<?php echo esc_attr($title); ?>">
</p>
