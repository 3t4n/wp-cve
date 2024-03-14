<div class="widget-content">
    <p><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
               name="<?php echo $this->get_field_name('title'); ?>" type="text"
               value="<?php echo $instance['title']; ?>"></p>

    <p>
        <input id="<?php echo $this->get_field_id('show_on_home'); ?>"
               name="<?php echo $this->get_field_name('show_on_home'); ?>"
               type="checkbox" <?php checked($instance['show_on_home'], 'on'); ?>">
        <label for="<?php echo $this->get_field_id('show_on_home'); ?>">Show On Home?</label>
    </p>

    <p>
        <input id="<?php echo $this->get_field_id('show_on_posts'); ?>"
               name="<?php echo $this->get_field_name('show_on_posts'); ?>"
               type="checkbox" <?php checked($instance['show_on_posts'], 'on'); ?>">
        <label for="<?php echo $this->get_field_id('show_on_posts'); ?>">Show On Posts?</label>
    </p>

    <p>
        <input id="<?php echo $this->get_field_id('show_on_pages'); ?>"
               name="<?php echo $this->get_field_name('show_on_pages'); ?>"
               type="checkbox" <?php checked($instance['show_on_pages'], 'on'); ?>">
        <label for="<?php echo $this->get_field_id('show_on_pages'); ?>">Show On Pages?</label>
    </p>

</div>