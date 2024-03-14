<?php

namespace luckywp\tableOfContents\plugin;

use luckywp\tableOfContents\admin\widgets\widget\Widget;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\ArrayHelper;
use WP_Widget;

class WpWidget extends WP_Widget
{

    const ID_BASE = 'lpwtoc_widget';

    /**
     * Конструктор
     */
    public function __construct()
    {
        parent::__construct(
            self::ID_BASE,
            esc_html__('Table of Contents', 'luckywp-table-of-contents'),
            [
                'description' => esc_html__('Creates a table of contents for your posts and pages.', 'luckywp-table-of-contents'),
            ]
        );
    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        $attrs = Shortcode::attrsFromJson(ArrayHelper::getValue($instance, 'config', ''));
        $attrs = apply_filters('lwptoc_widget_attrs', $attrs, $this);
        $html = do_shortcode(Core::$plugin->shortcode->make($attrs));
        if ($html) {
            echo $args['before_widget'] . $html . $args['after_widget'];
        }
    }

    /**
     * @param array $instance The widget options
     */
    public function form($instance)
    {
        echo Widget::widget([
            'id' => $this->id,
            'inputName' => $this->get_field_name('config'),
            'value' => ArrayHelper::getValue($instance, 'config', ''),
            'instance' => $instance,
        ]);
    }

    /**
     * @param array $newInstance
     * @param array $oldInstance
     * @return array
     */
    public function update($newInstance, $oldInstance)
    {
        do_action('lwptoc_widget_attrs_update', $newInstance, $oldInstance, $this);
        return ['config' => ArrayHelper::getValue($newInstance, 'config', '')];
    }
}
