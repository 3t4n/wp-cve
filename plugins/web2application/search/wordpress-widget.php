<?php

class My_Custom_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'my_custom_widget',
            __('My Custom Widget', 'web2application'),
            array(
                'description' => __('A simple custom widget', 'web2application'),
            )
        );
    }

    public function widget($args, $instance) {
        // Output content on the frontend
        echo esc_html__('Hello, I am a custom widget!', 'web2application');
    }

    public function form($instance) {
        // Output settings form in the admin
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        // Save widget settings
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}

function register_my_custom_widget() {
    register_widget('My_Custom_Widget');
}

add_action('widgets_init', 'register_my_custom_widget');