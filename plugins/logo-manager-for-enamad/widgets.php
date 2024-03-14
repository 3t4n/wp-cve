<?php

/**
 * enable widget
 * @since 0.1
 */
class enamad_widget extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array('classname' => 'enamad_widget', 'description' => 'نماد الکترونیکی');
        parent::__construct('enamad_widget', 'نماد الکترونیکی', $widget_ops);
    }

    function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : 'نماد اعتماد الکترونیکی';
        ?>
        <p>
            عنوان ابزارک ::: <input type="text" name="<?php echo $this->get_field_name('title'); ?>"
                                    id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>">
        </p>
        <?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    function widget($args, $instance)
    {
        $settings = get_option('enamad_logo');

        if ($settings['enamad-view-method'] == 'front-page' && !is_front_page()) {
            return;
        }

        extract($args, EXTR_SKIP);
        $title = empty($instance['title']) ? 'نماد الکترونیکی' : apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        echo $before_title . $title . $after_title;
        $print_output = true;
        $is_widget = true;
        enamad_logo_html(array(
            'print_output' => $print_output,
            'is_widget' => $is_widget,
            '_enamad_code_type' => 'enamad'
        ));
        echo $after_widget;
    }

}

add_action('widgets_init', 'enamad_register_widget');
function enamad_register_widget()
{
    return register_widget("enamad_widget");
}

/**
 * @since 0.6
 */
class enamad_shamed_widget extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array('classname' => 'enamad_shamed_widget', 'description' => 'نماد شامد');
        parent::__construct('enamad_shamed_widget', 'نماد شامد', $widget_ops);
    }

    function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : 'نماد شامد';
        ?>
        <p>
            عنوان ابزارک ::: <input type="text" name="<?php echo $this->get_field_name('title'); ?>"
                                    id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>">
        </p>
        <?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    function widget($args, $instance)
    {
        $settings = get_option('enamad_logo');

        if ($settings['enamad-view-method'] == 'front-page' && !is_front_page()) {
            return;
        }

        extract($args, EXTR_SKIP);
        $title = empty($instance['title']) ? 'نماد شامد' : apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        echo $before_title . $title . $after_title;
        $print_output = true;
        $is_widget = true;
        enamad_logo_html(array(
            'print_output' => $print_output,
            'is_widget' => $is_widget,
            '_enamad_code_type' => 'shamed'
        ));
        echo $after_widget;
    }

}

add_action('widgets_init', 'enamad_shamed_register_widget');
function enamad_shamed_register_widget()
{
    return register_widget("enamad_shamed_widget");
}

/**
 * @since 0.6
 */
class enamad_custom_widget extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array('classname' => 'enamad_custom_widget', 'description' => 'نماد دلخواه');
        parent::__construct('enamad_custom_widget', 'نماد دلخواه', $widget_ops);
    }

    function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : 'نماد دلخواه';
        ?>
        <p>
            عنوان ابزارک ::: <input type="text" name="<?php echo $this->get_field_name('title'); ?>"
                                    id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo $title; ?>">
        </p>
        <?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    function widget($args, $instance)
    {
        $settings = get_option('enamad_logo');

        if ($settings['enamad-view-method'] == 'front-page' && !is_front_page()) {
            return;
        }

        extract($args, EXTR_SKIP);
        $title = empty($instance['title']) ? 'نماد دلخواه' : apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        echo $before_title . $title . $after_title;
        $print_output = true;
        $is_widget = true;
        enamad_logo_html(array(
            'print_output' => $print_output,
            'is_widget' => $is_widget,
            '_enamad_code_type' => 'custom'
        ));
        echo $after_widget;
    }

}

add_action('widgets_init', 'enamad_custom_register_widget');
function enamad_custom_register_widget()
{
    return register_widget("enamad_custom_widget");
}


/**
 * ٰVisual composre widget/Element
 * @since 0.6
 */

if (class_exists('WPBakeryShortCode')) {
    $namads = [
        ['name' => 'namad', 'title' => 'نماد اعتماد', 'logo' => '', 'desc' => 'نمایش نماد اعتماد'],
        ['name' => 'shamed', 'title' => 'نماد شامد', 'logo' => '', 'desc' => 'نمایش نماد شامد'],
        ['name' => 'custom', 'title' => 'نماد سفارشی', 'logo' => '', 'desc' => 'نمایش نماد دلخواه'],
    ];

    class enamad_visual_composer_widget extends WPBakeryShortCode
    {
        public $name, $title, $desc;

        // Element Init
        function __construct($name, $title, $desc)
        {
            $this->name = isset($name) ? $name : 'enamad';
            $this->title = isset($title) ? $title : '';
            $this->desc = isset($desc) ? $desc : '';

            add_action('init', array($this, 'vc_enamad_mapping'));
            add_shortcode('vc_enamad_' . $this->name, array($this, 'vc_enamad_html'));
        }

        // Element Mapping
        public function vc_enamad_mapping()
        {

            // Stop all if VC is not enabled
            if (!defined('WPB_VC_VERSION')) {
                return;
            }

            // Map the block with vc_map()
            vc_map(
                array(
                    'name' => $this->title,
                    'base' => 'vc_enamad_' . $this->name,
                    'description' => $this->desc,
                    'category' => 'ای نماد',
                    'icon' => _enamadlogo_PATH . '/enamad-icon.png',
                    'params' => array(

                        array(
                            'type' => 'textfield',
                            'holder' => 'h3',
                            'class' => 'title-class',
                            'heading' => $this->title,
                            'param_name' => 'title',
                            'value' => $this->title,
                            'description' => $this->desc . ' | <a href="' . admin_url('options-general.php?page=enamadlogo-options') . '" target="_blank">لینک تنظیمات کد</a>',
                            'admin_label' => false,
                            'weight' => 0,
                            'group' => 'Custom Group',
                        ),


                    ),
                )
            );

        }


        // Element HTML
        public function vc_enamad_html($atts)
        {

            // Params extraction
            extract(
                shortcode_atts(
                    array(
                        'title' => '',
                    ),
                    $atts
                )
            );

            // Fill $html var with data
            $print_output = false;
            $is_widget = true;
            $html = enamad_logo_html(array(
                'print_output' => $print_output,
                'is_widget' => $is_widget,
                '_enamad_code_type' => $this->name
            ));

            $html = '
				<div class="vc-infobox-wrap">
				
					<h2 class="vc-infobox-title">' . $title . '</h2>
					
					<div class="vc-infobox-text">' . $html . '</div>
				
				</div>';

            return $html;

        }
    }

    foreach ($namads as $namad) {
        new enamad_visual_composer_widget($namad['name'], $namad['title'], $namad['desc']);
    }


}

