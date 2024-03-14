<?php

namespace Shop_Ready\extension\header_footer\settings;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Shop_Ready\extension\header_footer\HF_Helper;

class General
{

    public function register()
    {

        add_action('woo_ready_header_footer', [$this, 'global_settings']);

    }

    public function global_settings($wr_settings)
    {

        $wr_settings->start_controls_section(
            'woo_ready_general_header_footer_settings',
            [
                'label' => esc_html__('Header Footer', 'shopready-elementor-addon'),
                'tab' => $wr_settings->get_id(),
            ]
        );

        $wr_settings->add_control(
            'wready_enable_header',
            [
                'label' => esc_html__('Header?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $templates = HF_Helper::get_templates();

        $wr_settings->add_control(
            'wooready_header_template',
            [
                'label' => esc_html__('Select Header Templates', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $templates,
                'condition' => [
                    'wready_enable_header' => ['yes']
                ]
            ]
        );

        $panel_link = add_query_arg(['post_type' => 'woo-ready-hf-tpl'], admin_url('edit.php'));

        $wr_settings->add_control(
            'woo_ready_header_footer_usage_direction_notice',
            [
                'label' => esc_html__('Important Note', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(__('<a target="_blank" href="%s">Create Template</a> Form Shop ready -> Header Footer', 'shopready-elementor-addon'), esc_url($panel_link)),
                'content_classes' => 'woo-ready-shop-page-notice',
            ]
        );

        $wr_settings->add_control(
            'wready_enable_footer',
            [
                'label' => esc_html__('Footer?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $wr_settings->add_control(
            'wooready_footer_template',
            [
                'label' => esc_html__('Select Footer Templates', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $templates,
                'condition' => [
                    'wready_enable_footer' => ['yes']
                ]
            ]
        );

        $wr_settings->end_controls_section();
    }

}