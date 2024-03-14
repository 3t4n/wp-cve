<?php

namespace Element_Ready\section_control;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

/***** 
 * Cookie Consent
 *******/
class Element_Ready_page_cookie
{

    private static $instance = null;

    private function __construct()
    {

        add_action('init', function () {
            add_action('elementor/element/wp-page/document_settings/after_section_end', [$this, 'after_section_end'], 15, 2);
        });
    }
    public static function getInstance()
    {

        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function after_section_end(\Elementor\Core\DocumentTypes\Page $page, $args)
    {

        $page->start_controls_section(
            'header_section',
            [
                'label' => esc_html__('Cookie Consent', 'element-ready-lite'),
                'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
            ]
        );

        $page->add_control(
            'eready_cookie_consent_enable',
            [
                'label' => esc_html__('Enable', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'no',
                'show_label' => true,
                'options' => [
                    'yes'  => esc_html__('Yes', 'element-ready-lite'),
                    'no' => esc_html__('No', 'element-ready-lite'),


                ],
            ]
        );

        $page->add_control(
            'eready_cookie_consent_title',
            [
                'label'       => esc_html__('Title', 'element-ready-lite'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__('Explore more', 'element-ready-lite'),
                'label_block' => true,

            ]
        );

        $page->add_control(
            'eready_cookie_consent_message',
            [
                'label'       => esc_html__('Message', 'element-ready-lite'),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default'     => esc_html__('This website uses cookies to ensure you get the best experience on our website.', 'element-ready-lite'),
                'label_block' => true,

            ]
        );

        $page->add_control(
            'eready_cookie_more_info_lavel',
            [
                'label'       => esc_html__('More Info Label', 'element-ready-lite'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__('More information', 'element-ready-lite'),
                'label_block' => true,

            ]
        );

        $page->add_control(
            'eready_cookie_url',
            [
                'label'       => esc_html__('Url', 'element-ready-lite'),
                'type'        => \Elementor\Controls_Manager::URL,
                'label_block' => true,

            ]
        );

        $page->add_control(
            'eready_cookie_lavel',
            [
                'label'       => esc_html__('accept Btn Label', 'element-ready-lite'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__('Accept Cookie', 'element-ready-lite'),
                'label_block' => true,

            ]
        );

        $page->add_control(
            'eready_cookie_advancedlavel',
            [
                'label'       => esc_html__('advanced Btn Label', 'element-ready-lite'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__('Customise Cookies', 'element-ready-lite'),
                'label_block' => true,

            ]
        );

        // Align
        $page->add_responsive_control(
            'element_ready_button_warp_align',
            [
                'label'   => esc_html__('Alignment', 'element-ready-lite'),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justify', 'element-ready-lite'),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .element-ready-cookie-btn' => 'text-align: {{VALUE}};',
                ],
                'default' => '',
            ]
        );

        $page->add_control(
            'eready_cookie_consent_unchecked',
            [
                'label' => esc_html__('Unchecked', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'no',
                'show_label' => true,
                'options' => [
                    'yes'  => esc_html__('Yes', 'element-ready-lite'),
                    'no' => esc_html__('No', 'element-ready-lite'),


                ],
            ]
        );

        $page->add_control(
            'eready_cookie_consent_expire',
            [
                'label'       => esc_html__('Expire', 'element-ready-lite'),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'default'     => 60,
                'description' => esc_html__('Select tume type from below option', 'element-ready-lite'),
                'label_block' => false,

            ]
        );

        $page->add_control(
            'eready_cookie_consent_expire_time_type',
            [
                'label' => esc_html__('Time Type', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'sec',
                'show_label' => true,
                'options' => [

                    'day'  => esc_html__('Days', 'element-ready-lite'),
                    'min'  => esc_html__('Minutes', 'element-ready-lite'),
                    'hour' => esc_html__('Hour', 'element-ready-lite'),
                    'sec'  => esc_html__('Seconds', 'element-ready-lite'),

                ],
            ]
        );

        $page->add_control(
            'eready_cookie_consent_delay',
            [
                'label'       => esc_html__('Delay', 'element-ready-lite'),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'default'     => 2000,
                'description' => esc_html__('In second'),
                'label_block' => false,

            ]
        );

        $page->end_controls_section();

        $page->start_controls_section(
            'cookie_consent_title_style_section',
            [
                'label' => esc_html__('Cookie Consent Title', 'element-ready-lite'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $page->add_control(
            'eready_cookie_consent_title_color',
            [
                'label' => esc_html__('Title Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #gdpr-cookie-message h4' => 'color: {{VALUE}}',
                ],
            ]
        );

        $page->add_control(
            'eready_cookie_consent_hoiver_title_color',
            [
                'label' => esc_html__('Hover Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #gdpr-cookie-message h4:hover' => 'color: {{VALUE}}',
                ],
            ]
        );


        $page->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eready_cookie_consent_title_typography',
                'selector' => '{{WRAPPER}} #gdpr-cookie-message h4',
            ]
        );

        $page->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eready_cookie_consent_button_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} #gdpr-cookie-message h4',
            ]
        );

        $page->add_responsive_control(
            'eready_cookie_consent_title_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-message h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $page->add_responsive_control(
            'eready_cookie_consent_title_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-message h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $page->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eready_cookie_consent_title_shadow',
                'selector' => '{{WRAPPER}} #gdpr-cookie-message h4',
            ]
        );

        $page->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'title_hover_button_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} #gdpr-cookie-message h4',
            ]
        );


        $page->end_controls_section();

        $page->start_controls_section(
            'cookie_consent_mesg_style_section',
            [
                'label' => esc_html__('Cookie Message', 'element-ready-lite'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $page->add_control(
            'eready_cookie_consent_mnss_color',
            [
                'label' => esc_html__('Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #gdpr-cookie-message p' => 'color: {{VALUE}}',
                ],
            ]
        );

        $page->add_control(
            'eready_cookie_consent_hoiver_messgae_color',
            [
                'label' => esc_html__('Hover Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #gdpr-cookie-message p:hover' => 'color: {{VALUE}}',
                ],
            ]
        );


        $page->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eready_cookie_consent_mesge_typography',
                'selector' => '{{WRAPPER}} #gdpr-cookie-message p:first-child',
            ]
        );

        $page->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eready_cookie_consent_messgte_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} #gdpr-cookie-message p:first-child',
            ]
        );

        $page->add_responsive_control(
            'eready_cookie_consent_smfg_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-message p:first-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $page->add_responsive_control(
            'eready_cookie_consent_msg_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-message p:first-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $page->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eready_cookie_consent_message_shadow',
                'selector' => '{{WRAPPER}} #gdpr-cookie-message p:first-child',
            ]
        );

        $page->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'title_hover_message_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} #gdpr-cookie-message p:first-child',
            ]
        );


        $page->end_controls_section();

        $page->start_controls_section(
            'cookie_consent_accept_button_style_section',
            [
                'label' => esc_html__('Cookie Accept button', 'element-ready-lite'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $page->add_control(
            'eready_cookie_consent_accept_button_color',
            [
                'label' => esc_html__('Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  #gdpr-cookie-accept' => 'color: {{VALUE}}',
                ],
            ]
        );

        $page->add_control(
            'eready_cookie_consent_hoiver_acc_button_color',
            [
                'label' => esc_html__('Hover Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  #gdpr-cookie-accept:hover' => 'color: {{VALUE}}',
                ],
            ]
        );


        $page->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eready_cookie_consent_accept_btn_typography',
                'selector' => '{{WRAPPER}} #gdpr-cookie-accept',
            ]
        );

        $page->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eready_cookie_consent_accept_btn_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} #gdpr-cookie-accept',
            ]
        );

        $page->add_responsive_control(
            'eready_cookie_consent_accept_btn_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-accept' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $page->add_responsive_control(
            'eready_cookie_consent_accept_btn_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-accept' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $page->add_responsive_control(
            'eready_cookie_consent_accept_btn_border_radious',
            [
                'label'      => esc_html__('Border Radius', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-accept' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $page->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eready_cookie_consent_accept_btn_shadow',
                'selector' => '{{WRAPPER}} #gdpr-cookie-accept',
            ]
        );

        $page->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'title__accept_button_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} #gdpr-cookie-accept',
            ]
        );

        // Align
        $page->add_responsive_control(
            'accept_button_warp_align',
            [
                'label'   => esc_html__('Alignment', 'element-ready-lite'),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],

                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'none' => [
                        'title' => esc_html__('Justify', 'element-ready-lite'),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} #gdpr-cookie-accept' => 'float: {{VALUE}};',
                ],
                'default' => '',
            ]
        );


        $page->end_controls_section();

        $page->start_controls_section(
            'cookie_consent_adv_button_style_section',
            [
                'label' => esc_html__('Advanced button', 'element-ready-lite'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $page->add_control(
            'eready_cookie_consentadvanced_btn_enable',
            [
                'label' => esc_html__('Enable', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'show_label' => true,
                'options' => [
                    'block'  => esc_html__('Yes', 'element-ready-lite'),
                    'none' => esc_html__('No', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} #gdpr-cookie-advanced' => 'display: {{VALUE}}',
                ],
            ]
        );

        $page->add_control(
            'eready_cookie_consent_adv_button_color',
            [
                'label' => esc_html__('Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #gdpr-cookie-advanced' => 'color: {{VALUE}}',
                ],
            ]
        );

        $page->add_control(
            'eready_cookie_consent_hoiver_adv_button_color',
            [
                'label' => esc_html__('Hover Color', 'elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #gdpr-cookie-advanced:hover' => 'color: {{VALUE}}',
                ],
            ]
        );


        $page->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eready_cookie_consent_adv_btn_typography',
                'selector' => '{{WRAPPER}} #gdpr-cookie-advanced',
            ]
        );

        $page->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eready_cookie_consent_adv_btn_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} #gdpr-cookie-advanced',
            ]
        );

        $page->add_responsive_control(
            'eready_cookie_consent_adv_btn_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-advanced' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $page->add_responsive_control(
            'eready_cookie_consent_adv_btn_border_radious',
            [
                'label'      => esc_html__('Border Radius', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-advanced' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $page->add_responsive_control(
            'eready_cookie_consent_adv_btn_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-advanced' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $page->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eready_cookie_consent_adv_btn_shadow',
                'selector' => '{{WRAPPER}} #gdpr-cookie-advanced',
            ]
        );

        $page->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'title__accept_advanced_button_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} #gdpr-cookie-advanced',
            ]
        );

        // Align
        $page->add_responsive_control(
            'adv_button_warp_align',
            [
                'label'   => esc_html__('Alignment', 'element-ready-lite'),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'eicon-text-align-left',
                    ],

                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'none' => [
                        'title' => esc_html__('Justify', 'element-ready-lite'),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} #gdpr-cookie-advanced' => 'float: {{VALUE}};',
                ],
                'default' => '',
            ]
        );


        $page->end_controls_section();

        $page->start_controls_section(
            'cookie_consent_main__section',
            [
                'label' => esc_html__('Cookie Consent Wrapper', 'element-ready-lite'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );



        $page->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eready_cookie_consent_sec_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} #gdpr-cookie-message',
            ]
        );

        $page->add_responsive_control(
            'eready_cookie_consent_sec_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $page->add_responsive_control(
            'eready_cookie_consent_sec_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $page->add_control(
            'eready_cookie_consent_sec_radius',
            [
                'label'      => esc_html__('Border Radius', 'element-ready-lite'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} #gdpr-cookie-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $page->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eready_cookie_consent_sec_shadow',
                'selector' => '{{WRAPPER}} #gdpr-cookie-message',
            ]
        );

        $page->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'     => 'element_ready_cookiewraer_button_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient', 'video'],
                'selector' => '{{WRAPPER}} #gdpr-cookie-message',
            ]
        );

        $page->add_responsive_control(
            'eready_cookie_box_image_width',
            [
                'label'      => esc_html__('Width', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,

                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1600,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} #gdpr-cookie-message' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $page->add_responsive_control(
            'eready_cookie_box_image_height',
            [
                'label'      => esc_html__('Height', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,

                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1600,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],

                ],

                'selectors' => [
                    '{{WRAPPER}} #gdpr-cookie-message' => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $page->end_controls_section();
    }
}

if (element_ready_get_modules_option('cookie')) {
    Element_Ready_page_cookie::getInstance();
}
