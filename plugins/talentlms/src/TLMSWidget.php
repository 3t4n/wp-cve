<?php
/**
 * @package talentlms-wordpress
 */

namespace TalentlmsIntegration;

use TalentlmsIntegration\Services\PluginService;
use WP_Widget;

class TLMSWidget extends WP_Widget implements PluginService
{
    protected $_version = '1.0.0';

    public function __construct()
    {
        parent::__construct(
            'Tlms_widget', // Base ID
            'TalentLMS Widget', // Name
            array(
                'description' => esc_html__('A TalentLMS Widget', 'talentlms'),
            ) // Args
        );
        $this->enqueue_widget_assets();
    }

    public function register(): void
    {
        add_action(
            'widgets_init',
            static function () {
                register_widget(TLMSWidget::class);
            }
        );
    }

    public function widget($args, $instance): void
    {
        echo $args['before_widget'];
        if (! empty($instance['title'])) {
            echo $args['before_title']
                . apply_filters('widget_title', $instance['title'])
                . $args['after_title'];
        }
        $courses = Utils::tlms_selectCourses();
        require_once TLMS_BASEPATH . '/templates/widget/tlmswidget.php';
        echo $args['after_widget'];
    }

    public function form($instance): string
    {
        $title     = ! empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Our Courses', 'talentlms');
        $titleId   = strtolower($this->get_field_id('title'));
        $titleName = strtolower($this->get_field_name('title'));
        return TLMS_BASEPATH . '/templates/widget/form/tlmswidgetform.php';
    }

    public function update($new_instance, $old_instance): array
    {
        $instance          = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);

        return $instance;
    }

    public function enqueue_widget_assets(): void
    {
        wp_register_style(
            'tlms-widget',
            TLMS_BASEURL . 'assets/css/talentlms-widget.css',
            false,
            $this->_version
        );

        wp_enqueue_style('tlms-widget');
    }
}
