<?php

require_once(plugin_dir_path(__FILE__) . '../SeoPilotClient.php');

class SeoPilot_Widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'seopilot',            // Base ID
            'SeoPilot.pl',        // Name
            ['description' => __('SeoPilot.pl Widget', 'seopilot'),] // Args
        );
    }

    public function widget($args, $instance)
    {
        echo SeoPilot::$client->build_links($instance['count'] ?: false, $instance['orientation'] ?: null);
    }

    public function form($instance)
    {
        if (isset($instance['count'])) {
            $count = $instance['count'];
        } else {
            $count = 3;
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('count'); ?>"
                    name="<?php echo $this->get_field_name('count'); ?>">
                <option value="1"<?php echo $count == '1' ? ' selected' : '' ?>>1</option>
                <option value="2"<?php echo $count == '2' ? ' selected' : '' ?>>2</option>
                <option value="3"<?php echo $count == '3' ? ' selected' : '' ?>>3</option>
            </select>
        </p>
        <?php


        if (isset($instance['orientation'])) {
            $orientation = $instance['orientation'];
        } else {
            $orientation = 's';
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('orientation'); ?>"><?php _e('Block Orientation:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('orientation'); ?>"
                    name="<?php echo $this->get_field_name('orientation'); ?>">
                <option value="v"<?php echo $orientation == 'v' ? ' selected' : '' ?>>Vertical</option>
                <option value="h"<?php echo $orientation == 'h' ? ' selected' : '' ?>>Horizontal</option>
                <option value="s"<?php echo $orientation == 's' ? ' selected' : '' ?>>Site setting</option>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['count'] = (!empty($new_instance['count'])) ? strip_tags($new_instance['count']) : '';
        $instance['orientation'] = (!empty($new_instance['orientation'])) ? strip_tags($new_instance['orientation']) : '';
        return $instance;
    }
}
