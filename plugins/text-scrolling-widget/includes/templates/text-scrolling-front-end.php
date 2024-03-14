<p>
    <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
    <input
        class="widefat"
        id="<?php echo $this->get_field_id('title'); ?>"
        name="<?php echo $this->get_field_name('title'); ?>"
        value="<?php if( isset($title) ){ echo esc_attr($title); } ?>"
        >
</p>

<p>
    <label for="<?php echo $this->get_field_id('description'); ?>">Description:</label>
    <textarea
        class="widefat"
        rows="10"
        id="<?php echo $this->get_field_id('description'); ?>"
        name="<?php echo $this->get_field_name('description'); ?>"
        ><?php if( isset($description) ) { echo esc_attr($description); } ?></textarea>
</p>