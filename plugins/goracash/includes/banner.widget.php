<?php

class Goracash_Banner_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct('goracash_banner', __('Banner Goracash', 'goracash'), array(
            'description' => __('Setting up a dynamic banner', 'goracash'),
        ));
    }

    public function get_value($key, array $array, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }

    public function get_dropdown($values, $value)
    {
        $content = '';
        foreach ($values as $key => $label) {
            $content .= sprintf('<option value="%s" %s>%s</option>',
                $key,
                $key == $value ? 'selected="selected"' : '',
                $label
            );
        }
        return $content;
    }

    public function widget($args, $instance)
    {
        $uniqid = uniqid();

        $default_params = Goracash_Banner::$default_params;
        $params = array_merge($default_params, $instance);
        $params = array_intersect_key($params, $default_params);
        $params = array_filter($params);

        echo $args['before_widget'];
        echo $args['before_title'];
        echo apply_filters('widget_title', $instance['title']);
        echo $args['after_title'];

        printf("<div id='%s'><script>goracash('banner', '%s', 'auto', %s);</script></div>", $uniqid, $uniqid, json_encode($params));

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        printf('
            <p><em>%s</em></p>
            <p>
                <label for="%s">%s :</label>
                <input class="widefat" id="%s" name="%s" type="text" value="%s" />
            </p>
            <p>
                <label for="%s">%s :</label>
                <select class="widefat" id="%s" name="%s" type="text">
                    <option value="">%s</option>
                    %s
                </select>
            </p>
            <p>
                <label for="%s">%s :</label>
                <select class="widefat" id="%s" name="%s" type="text">
                    %s
                </select>
            </p>
            <p>
                <label for="%s">%s :</label>
                <select class="widefat" id="%s" name="%s" type="text">
                    <option value="">%s</option>
                    %s
                </select>
            </p>
            <p>
                <label for="%s">%s :</label>
                <select class="widefat" id="%s" name="%s" type="text">
                    <option value="">%s</option>
                    %s
                </select>
            </p>
            <p>
                <label for="%s">%s :</label>
                <input class="widefat" id="%s" name="%s" type="text" value="%s" />
            </p>
            <p>
                <label for="%s">%s :</label>
                <input class="widefat" id="%s" name="%s" type="text" value="%s" />
            </p>
            <p>
                <label for="%s">%s</label>
                <input class="widefat" id="%s" name="%s" type="text" value="%s" />
            </p>
            <p>
                <label for="%s">%s</label>
                <input class="widefat" id="%s" name="%s" type="text" value="%s" />
            </p>
            <p>
                <label for="%s">%s</label>
                <input class="widefat" id="%s" name="%s" type="text" value="%s" />
            </p>
            ',

            __('All fields are optional.', 'goracash'),
            $this->get_field_name('title'),
            __('Title', 'goracash'),
            $this->get_field_id('title'),
            $this->get_field_name('title'),
            $this->get_value('title', $instance, ''),

            $this->get_field_name('thematic'),
            __('Thematic', 'goracash'),
            $this->get_field_id('thematic'),
            $this->get_field_name('thematic'),
            __('By default', 'goracash'),
            $this->get_dropdown(Goracash_Banner::get_thematics(), $this->get_value('thematic', $instance, '')),

            $this->get_field_name('advertiser'),
            __('Advertiser', 'goracash'),
            $this->get_field_id('advertiser'),
            $this->get_field_name('advertiser'),
            $this->get_dropdown(Goracash_Banner::get_advertisers(), $this->get_value('advertiser', $instance, '')),

            $this->get_field_name('defaultLanguage'),
            __('Default language', 'goracash'),
            $this->get_field_id('defaultLanguage'),
            $this->get_field_name('defaultLanguage'),
            __('By default', 'goracash'),
            $this->get_dropdown(Goracash_Banner::get_langs(), $this->get_value('defaultLanguage', $instance, '')),

            $this->get_field_name('defaultMarket'),
            __('Default market', 'goracash'),
            $this->get_field_id('defaultMarket'),
            $this->get_field_name('defaultMarket'),
            __('By default', 'goracash'),
            $this->get_dropdown(Goracash_Banner::get_markets(), $this->get_value('defaultMarket', $instance, '')),

            $this->get_field_name('tracker'),
            __('Your tracker', 'goracash'),
            $this->get_field_id('tracker'),
            $this->get_field_name('tracker'),
            $this->get_value('tracker', $instance, ''),

            $this->get_field_name('minWidth'),
            __('Minimum width (in px)', 'goracash'),
            $this->get_field_id('minWidth'),
            $this->get_field_name('minWidth'),
            $this->get_value('minWidth', $instance, ''),

            $this->get_field_name('maxWidth'),
            __('Maximum width (in px)', 'goracash'),
            $this->get_field_id('maxWidth'),
            $this->get_field_name('maxWidth'),
            $this->get_value('maxWidth', $instance, ''),

            $this->get_field_name('minHeight'),
            __('Minimum height (in px)', 'goracash'),
            $this->get_field_id('minHeight'),
            $this->get_field_name('minHeight'),
            $this->get_value('minHeight', $instance, ''),

            $this->get_field_name('maxHeight'),
            __('Maximum height (in px)', 'goracash'),
            $this->get_field_id('maxHeight'),
            $this->get_field_name('maxHeight'),
            $this->get_value('maxHeight', $instance, '')
        );
    }

}