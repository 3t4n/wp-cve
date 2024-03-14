<?php
class YouTube_Subs_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'apyt_widget',
            esc_html__('YouTube Subs', 'youtube-sw'),
            ['description' => esc_html__('Add your YouTube channel', 'youtube-sw')]
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        echo sprintf(
            '<div class="g-ytsubscribe" data-channelid="%s" data-layout="%s" data-count="%s"></div>',
            esc_attr($instance['channel']),
            esc_attr($instance['layout']),
            esc_attr($instance['count'])
        );

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('YouTube Subs', 'youtube-sw');
        $channel = !empty($instance['channel']) ? $instance['channel'] : esc_html__('GoogleDevelopers', 'youtube-sw');
        $layout = !empty($instance['layout']) ? $instance['layout'] : 'default';
        $count = !empty($instance['count']) ? $instance['count'] : 'default';
?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_attr_e('Title:', 'youtube-sw'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('channel')); ?>">
                <?php esc_attr_e('Channel ID:', 'youtube-sw'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('channel')); ?>" name="<?php echo esc_attr($this->get_field_name('channel')); ?>" type="text" value="<?php echo esc_attr($channel); ?>">
        </p>
        <p>
            <a href="https://apsaraaruna.com/blog/how-to-find-youtube-channel-id/" target="_blank">How to find Youtube channel ID</a>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('layout')); ?>">
                <?php esc_attr_e('Layout:', 'youtube-sw'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout')); ?>" name="<?php echo esc_attr($this->get_field_name('layout')); ?>">
                <option value="default" <?php selected($layout, 'default'); ?>>Default</option>
                <option value="full" <?php selected($layout, 'full'); ?>>Full</option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('count')); ?>">
                <?php esc_attr_e('Subscriber count:', 'youtube-sw'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('count')); ?>" name="<?php echo esc_attr($this->get_field_name('count')); ?>">
                <option value="default" <?php selected($count, 'default'); ?>>Default</option>
                <option value="hidden" <?php selected($count, 'hidden'); ?>>Hide</option>
            </select>
        </p>

<?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [
            'title' => !empty($new_instance['title']) ? strip_tags($new_instance['title']) : '',
            'channel' => !empty($new_instance['channel']) ? strip_tags($new_instance['channel']) : '',
            'layout' => !empty($new_instance['layout']) ? strip_tags($new_instance['layout']) : '',
            'count' => !empty($new_instance['count']) ? strip_tags($new_instance['count']) : ''
        ];

        return $instance;
    }
}
