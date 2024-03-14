<?php

namespace Shop_Ready\extension\header_footer\settings;

use Shop_Ready\base\elementor\Document_Settings;
use Elementor\Controls_Manage;
use Elementor\Core\DocumentTypes\PageBase;
use Shop_Ready\extension\header_footer\HF_Helper;

/*
 * Page Settings
 * @since 1.0
 * Page Settings in Elementor Editor
 * 
 */
class Page_Settings extends Document_Settings
{

    const PANEL_TAB = 'woo-ready-tab';

    public function register()
    {

        add_action('elementor/init', [$this, 'add_panel_tab']);
        add_action('elementor/documents/register_controls', [$this, 'register_document_controls']);
    }

    /******** ::::::::::::::::: 
    * Page Header Footer
    * action hook elementor/element/wp-page/document_settings/after_section_end
    * @return void 
    ::::::::::::::::::::::::::::::::*/
    public function add_panel_tab()
    {
        \Elementor\Controls_Manager::add_tab(self::PANEL_TAB, SHOP_READY_ITEM_NAME);
    }

    /**::::::::::::::::: 
     * Resister additional document controls.
     * @param PageBase $document
     */
    public function register_document_controls($document)
    {
        // PageBase is the base class for documents like `post` `page` and etc.
        // In this example we check also if the document supports elements. (e.g. a Kit doesn't has elements)
        // usaage shop_ready_get_page_meta($key,get_the_id())
        if (!$document instanceof PageBase || !$document::get_property('has_elements')) {
            return;
        }

        $document->start_controls_section(
            'woo_ready_page_header_footer_section',
            [
                'label' => __('Header Footer', 'shopready-elementor-addon'),
                'tab' => self::PANEL_TAB,
            ]
        );

        $panel_link = add_query_arg(['post_type' => 'woo-ready-hf-tpl'], admin_url('edit.php'));
        $document->add_control(
            'woo_ready_header_footer_usage_direction_notice',
            [
                'label' => esc_html__('Important Note', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => sprintf(__('<a target="_blank" href="%s">Create Template</a> Form Shop ready -> Header Footer', 'shopready-elementor-addon'), esc_url($panel_link)),
                'content_classes' => 'woo-ready-shop-page-notice',
            ]
        );

        $document->add_control(
            'wready_page_header_enable',
            [
                'label' => esc_html__('Header Template Disable?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $templates = HF_Helper::get_templates();
        $document->add_control(
            'wooready_page_header_template',
            [
                'label' => esc_html__('Header Template', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $templates,
                'condition' => [
                    'wready_page_header_enable!' => ['yes']
                ]
            ]
        );

        $document->add_control(
            'wready_page_footer_enable',
            [
                'label' => esc_html__('Footer Template Disable?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'shopready-elementor-addon'),
                'label_off' => esc_html__('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $document->add_control(
            'wooready_page_footer_template',
            [
                'label' => esc_html__('Select Footer Templates', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $templates,
                'description' => esc_html__('Override Footer template ', 'shopready-elementor-addon'),
                'condition' => [
                    'wready_page_footer_enable!' => ['yes']
                ]
            ]
        );

        $document->end_controls_section();
    }


}