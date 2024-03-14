<?php if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

class Kemail_Widget extends WP_Widget
{
    public $KEMAIL_WPFORM_API = null;

    function __construct()
    {
        parent::__construct(

        // Base ID of your widget
            'ke_wpform_widget',

            // Widget name will appear in UI
            __('KIRIM.EMAIL Widget', 'ke_widget_domain'),

            // Widget description
            array('description' => __('Widget for KIRIM.EMAIL form ', 'ke_widget_domain'),)
        );

        $this->KEMAIL_WPFORM_API = new Kemail_Api();
    }

    function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
        $get_url = $instance['url'];
        $with_name = $instance['with_name'];
        $use_ajax = $instance['use_ajax'];
        if ($use_ajax != null && $use_ajax) {
            wp_register_script('kirimemail-ajax-form', get_asset('js/kirimemail-ajax-form.js'), 'jQuery', Kirimemail_Wordpress_Form::KIRIMEMAIL_PLUGIN_VERSION, true);
            wp_enqueue_script('kirimemail-ajax-form');
        }
        echo $before_widget;
        echo load_view('widget', [
            'title' => $title,
            'before_title' => $before_title,
            'after_title' => $after_title,
            'get_url' => $get_url,
            'with_name' => $with_name,
        ], true);
        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['url'] = (!empty($new_instance['url'])) ? strip_tags($new_instance['url']) : '';
        $instance['with_name'] = (!empty($new_instance['with_name'])) ? strip_tags($new_instance['with_name']) : '';
        $instance['use_ajax'] = (!empty($new_instance['use_ajax'])) ? strip_tags($new_instance['use_ajax']) : '';
        return $instance;
    }

    function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Subscribe Here', 'ke_widget_domain');
        }
        if (isset($instance['with_name'])) {
            $with_name = $instance['with_name'];
        }
        if (isset($instance['use_ajax'])) {
            $use_ajax = $instance['use_ajax'];
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('use_ajax'); ?>"><?php _e('Use Ajax:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('use_ajax'); ?>"
                    name="<?php echo $this->get_field_name('use_ajax'); ?>">
                <option value="0" <?php if (isset($use_ajax) && !$use_ajax)
                    echo 'selected' ?> > No
                </option>
                <option value="1" <?php if (isset($use_ajax) && $use_ajax)
                    echo 'selected' ?> >Yes
                </option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('with_name'); ?>"><?php _e('With Name:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('with_name'); ?>"
                    name="<?php echo $this->get_field_name('with_name'); ?>">
                <option value="0" <?php if (isset($with_name) && !$with_name)
                    echo 'selected' ?> > No
                </option>
                <option value="1" <?php if (isset($with_name) && $with_name)
                    echo 'selected' ?> >Yes
                </option>
            </select>
        </p>
        <p>
            <label for="shortcode-dropdown"><?php _e('List Form:'); ?></label>
            <select name="<?php echo $this->get_field_name('url'); ?>" id="shortcode-dropdown" class="widefat">
                <?php $this->KEMAIL_WPFORM_API->get_form_widget(esc_attr($instance['url'])); ?>
            </select>
        </p>
        <?php

    }

    public static function register()
    {
        function ke_load_widget()
        {
            register_widget('Kemail_Widget');
        }

        add_action('widgets_init', 'ke_load_widget');
    }
}
