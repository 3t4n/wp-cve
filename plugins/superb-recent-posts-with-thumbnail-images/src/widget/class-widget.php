<?php

namespace SuperbRecentPosts\Widget;

if (! defined('WPINC')) {
    die;
}

class SRPWidget extends \WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'spbrposts_widget',
            __('Superb Recent Posts', 'superbrecentposts'),
            array( 'description' => __("Display your site's most recent posts superbly with or without thumbnails.", 'superbrecentposts'), )
        );
    }
          
    // Widget Frontend
    public function widget($args, $instance)
    {
        wp_enqueue_style('spbrposts-frontend-css');
        new WidgetFrontend($args, $instance);
    }
                  
    // Widget Backend
    public function form($instance)
    {
        new WidgetAdmin($this, $instance);
    }
              
    // Widget Update
    public function update($new_instance, $old_instance)
    {
        return WidgetUpdate::GetUpdate($new_instance, $old_instance);
    }
}
