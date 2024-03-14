<?php

namespace WP_Rplg_Google_Reviews\Includes;

use WP_Rplg_Google_Reviews\Includes\Core\Core;

class Feed_Ajax {

    private $core;
    private $view;
    private $feed_serializer;
    private $feed_deserializer;

    public function __construct(Feed_Serializer $feed_serializer, Feed_Deserializer $feed_deserializer, Core $core, View $view) {
        $this->feed_serializer = $feed_serializer;
        $this->feed_deserializer = $feed_deserializer;
        $this->core = $core;
        $this->view = $view;

        add_action('wp_ajax_grw_feed_save_ajax', array($this, 'save_ajax'));
    }

    public function save_ajax() {

        $post_id = $this->feed_serializer->save($_POST['post_id'], $_POST['title'], $_POST['content']);

        if (isset($post_id)) {
            $feed = $this->feed_deserializer->get_feed($post_id);

            $data = $this->core->get_reviews($feed, true);
            $businesses = $data['businesses'];
            $reviews = $data['reviews'];
            $options = $data['options'];

            echo $this->view->render($feed->ID, $businesses, $reviews, $options, true);
        }

        wp_die();
    }

}
