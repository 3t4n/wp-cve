<?php

namespace OXI_FLIP_BOX_PLUGINS\Modules;

class Widget extends \WP_Widget {

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        echo \OXI_FLIP_BOX_PLUGINS\Classes\Bootstrap::instance()->shortcode_render($title, 'user');
        echo $args['after_widget'];
    }

    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = esc_html__('1', 'image-hover-effects-ultimate-visual-composer');
        }
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html('Style ID:'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

    function __construct() {
        parent::__construct(
                'oxi_flip_box_widget', esc_html__('Flipbox - Awesomes Flip Boxes Image Overlay', 'image-hover-effects-ultimate-visual-composer'), array('description' => esc_html__('Flipbox - Awesomes Flip Boxes Image Overlay', 'image-hover-effects-ultimate-visual-composer'),)
        );
    }

    public function flip_register_flipwidget() {
        register_widget($this);
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

}
