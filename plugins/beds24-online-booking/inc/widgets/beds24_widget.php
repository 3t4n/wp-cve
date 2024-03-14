<?php

class Beds24_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array('classname' => 'Beds24_Widget', 'description' => 'Display a Beds24.com Widget. ');
        parent::__construct('Beds24_Widget', 'Beds24 Booking Widget', $widget_ops);
    }

    public function form($instance)
    {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'widget_html' => ''));
        $title = $instance['title'];
        $widget_html = $instance['widget_html'];
        ?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p style="font-size: 8pt; color: gray; font-style: italic;">Enter the widget shortcode. Alternatively choose the widget to display from the widget menu in your Beds24.com account and copy and paste the code here.</p>
		<p>
		<div>Beds24 Widget Code:</div>
		<textarea name="<?php echo $this->get_field_name('widget_html'); ?>" id="<?php echo $this->get_field_id('widget_html'); ?>">
		<?php echo $widget_html; ?>
		</textarea>
		</p>
		<?php
	}
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['widget_html'] = $new_instance['widget_html'];
        return $instance;
    }
    public function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);
        echo $before_widget;
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

		// WIDGET CODE GOES HERE
        echo do_shortcode($instance['widget_html']);
        echo $after_widget;
    }
}

add_action('widgets_init', 'Beds24_Widget');
function Beds24_Widget()
{
    return register_widget('Beds24_Widget');
}

?>
