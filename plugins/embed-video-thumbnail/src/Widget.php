<?php

namespace Ikana\EmbedVideoThumbnail;

class Widget extends \WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'ikevt_widget',
            __('Embed Video Thumbnail Widget', IKANAWEB_EVT_TEXT_DOMAIN),
            [
                'description' => __('Replace embed video by thumbnail', IKANAWEB_EVT_TEXT_DOMAIN),
            ]
        );
    }

    public function widget($args, $instance)
    {
        $video = apply_filters('ikevt_video_to_thumbnail', $instance['url']);

        echo $args['before_widget'];
        if (!empty($video)) {
            echo $video;
        }
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $url = '';
        if (isset($instance['url'])) {
            $url = $instance['url'];
        } ?>
        <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Video url :'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>"
                   name="<?php echo $this->get_field_name('url'); ?>" type="text"
                   value="<?php echo esc_attr($url); ?>"/>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['url'] = !empty($new_instance['url']) ? $new_instance['url'] : '';

        return $instance;
    }
}
