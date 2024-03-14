<?php

namespace WP_Rplg_Google_Reviews\Includes;

use WP_Rplg_Google_Reviews\Includes\Core\Core;

class Feed_Widget extends \WP_Widget {

    public static $static_core;

    public static $static_view;

    public static $static_assets;

    public static $static_feed_deserializer;

    public static $static_feed_old;

    public function __construct() {
        parent::__construct(
            'grw_widget',
            __('Google Reviews Widget', 'widget-google-reviews'),
            array(
                'classname'   => 'google-reviews-widget',
                'description' => __('Display Google Places Reviews on your website.', 'widget-google-reviews'),
            )
        );

        $this->core              = self::$static_core;
        $this->view              = self::$static_view;
        $this->assets            = self::$static_assets;
        $this->feed_deserializer = self::$static_feed_deserializer;
        $this->feed_old          = self::$static_feed_old;
    }

    public function widget($args, $instance) {
        if (get_option('grw_active') === '0') {
            return;
        }

        if (isset($instance['place_id']) && strlen($instance['place_id']) > 0) {
            $feed = $this->feed_old->get_feed($this->id, $instance);

        } else {
            if (!isset($instance['feed_id']) || strlen($instance['feed_id']) < 1) {
                return null;
            }

            $feed = $this->feed_deserializer->get_feed($instance['feed_id']);

            if (!$feed) {
                return null;
            }
        }

        $grw_demand_assets = get_option('grw_demand_assets');
        if ($grw_demand_assets || $grw_demand_assets == 'true') {
            $this->assets->enqueue_public_styles();
            $this->assets->enqueue_public_scripts();
        }

        $data = $this->core->get_reviews($feed);

        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];
        }

        $businesses = $data['businesses'];
        $reviews = $data['reviews'];
        $options = $data['options'];
        if (count($businesses) > 0 || count($reviews) > 0) {
            echo $this->view->render($feed->ID, $businesses, $reviews, $options);
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $wp_query = new \WP_Query();
        $wp_query->query(array(
            'post_type'      => Post_Types::FEED_POST_TYPE,
            'posts_per_page' => 100,
            'no_found_rows'  => true,
        ));
        $feeds = $wp_query->posts;

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'grw'); ?>
            </label>
            <input
                type="text"
                id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                class="widefat"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                value="<?php if (isset($instance['title'])) { echo esc_attr($instance['title']); } ?>"
            >
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('feed_id')); ?>">
                <?php esc_html_e('Feed:', 'grw'); ?>
            </label>

            <select
                id="<?php echo esc_attr($this->get_field_id('feed_id')); ?>"
                name="<?php echo esc_attr($this->get_field_name('feed_id')); ?>"
                style="display:block;width:100%"
            >
                <option value="">Select Feed</option>
                <?php foreach ($feeds as $feed) : ?>
                    <option
                        value="<?php echo esc_attr($feed->ID); ?>"
                        <?php if (isset($instance['feed_id'])) { selected($feed->ID, $instance['feed_id']); } ?>
                    >
                        <?php echo esc_html('ID ' . $feed->ID . ': ' . $feed->post_title); ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title']   = sanitize_text_field($new_instance['title']);
        $instance['feed_id'] = sanitize_text_field($new_instance['feed_id']);
        return $instance;
    }
}
