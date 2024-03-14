<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo isset($title) ? $title : ''; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('placeholder'); ?>"><?php _e('Placeholder:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('placeholder'); ?>" name="<?php echo $this->get_field_name('placeholder'); ?>" type="text" value="<?php echo isset($placeholder) ? $placeholder : 'Search For...'; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('button_text'); ?>"><?php _e('Button Text:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo isset($button_text) ? $button_text : 'Search'; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('search_archive_tpl'); ?>"><?php _e('Search Archive Filename:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('search_archive_tpl'); ?>" name="<?php echo $this->get_field_name('search_archive_tpl'); ?>" type="text" value="<?php echo isset($search_archive_tpl) ? $search_archive_tpl : 'search.php'; ?>" />
</p>
<p>
    <label><?php _e('Search in Post Types:'); ?></label>

    <?php
    foreach( $post_types as $post_type ) {
        ?>
        <br>
        <input type="checkbox" name="<?php echo $this->get_field_name('in_post_type'); ?>[]" id="<?php echo esc_attr($post_type->name); ?>" value="<?php echo esc_attr($post_type->name); ?>" <?php echo ( in_array( $post_type->name, $in_post_type ) ) ? 'checked="checked"' : null; ?>>
        <label for="<?php echo esc_attr($post_type->name); ?>"><?php echo esc_attr($post_type->label); ?></label>
    <?php
    }
    ?>
</p>
<p>
    <label for="<?php echo $this->get_field_id('user_selectable'); ?>"><?php _e('User can select Post Type:'); ?></label>&nbsp;
    <input class="widefat" id="<?php echo $this->get_field_id('user_selectable'); ?>" name="<?php echo $this->get_field_name('user_selectable'); ?>" type="checkbox" value="1" <?php echo ( isset( $user_selectable ) && $user_selectable ) ? 'checked="checked"' : null; ?>>
</p>