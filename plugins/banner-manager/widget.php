<?php

class banner_widget extends WP_Widget
{
    public function __construct() {
        $widget_ops = array( 
            'class_name' => 'banner_widget',
            'description' => __( "Your banners", 'banner-manager' ),
        );
        parent::__construct( 'banner_widget', 'Banner Manager', $widget_ops );
    }

    function widget($args, $instance) {
        extract( $args );
        echo $args['before_widget'];
        $category = apply_filters('widget_title', $instance['category']);
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }
        echo '<div style="text-align: center" class="widget widget-banner-manager">';
        wp_banner_manager( $category );
        echo '</div>';
        echo $args['after_widget'];
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['category'] = strip_tags($new_instance['category']);
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }

    function form($instance) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $selected_category = isset($instance['category'])? esc_attr($instance['category']) : 0;

        $categories = data::get_categories();
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'banner-manager'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
            <p>
                <label for="<?php echo $this->get_field_id('category'); ?>">
                    <?php _e('Category', 'banner-manager'); ?>
                    <?php if($categories):?>
                    <select class="widefat" name="<?php echo $this->get_field_name('category'); ?>">
                        <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>" <?php echo ($selected_category==$category->id)? 'selected="selected"' : '';?>><?php echo $category->groups; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>
                </label>
            </p>
        <?php
    }
}

// register FooWidget widget
add_action('widgets_init', create_function('', 'return register_widget("banner_widget");'));
