<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

if(!class_exists('RTCORE_Elementor')):

/**
 * Elementor Class
 * @since 1.0.0
 */
class RTCORE_Elementor extends RTCORE_Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        // Elementor Extend
        add_action('elementor/widgets/widgets_registered', array($this, 'widgets'), 10);
    }

    public function widgets(\Elementor\Widgets_Manager $manager)
    {
        // Post Widget
        $manager->register_widget_type(new RTCORE_Elementor_Post());

        // Testimonial Widget
        $manager->register_widget_type(new RTCORE_Elementor_Testimonial());
    }
}

endif;