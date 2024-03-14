<?php

namespace Element_Ready\Widgets\infotext_box;
use \Element_Ready\Base\Controls\Widget_Control\Element_ready_common_control as Content_Style;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Element_Ready_Infotext_Box_Widget extends Widget_Base {

    use Content_Style;

    public function get_name() {
        return 'Element_Ready_Infotext_Box_Widget';
    }
    
    public function get_title() {
        return esc_html__( 'ER Info Text Box', 'element-ready-lite' );
    }

    public function get_icon() {
        return 'eicon-info-circle-o';
    }

    public function get_categories() {
        return [ 'element-ready-addons' ];
    }

    public function get_style_depends() {
        wp_register_style( 'eready-info-text-box' , ELEMENT_READY_ROOT_CSS. 'widgets/info-text.css' );
        return [ 'eready-info-text-box' ];
    }

    public function get_keywords() {
        return [ 'Info Text Box', 'Box', 'Info' ];
    }


    public function element_ready_infotext_box_style(){

        return apply_filters( 'element_ready_infotext_style_presets', [
            'infotex_box__style__1' => esc_html__( 'Style One', 'element-ready-lite' ),
            'custom'                => esc_html__( 'Custom Style', 'element-ready-lite' ),
        ]);
    }

    protected function register_controls() {
        /*--------------------------
            CONTENT SECTION
        ---------------------------*/
        $this->start_controls_section(
            'infob_box_content_section',
            [
                'label' => esc_html__( 'Infobox Content & Style', 'element-ready-lite' ),
            ]
        );
            $this->add_control(
                'info_box_style',
                [
                    'label'   => esc_html__( 'Info Textbox Style', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'infotex_box__style__1',
                    'options' => $this->element_ready_infotext_box_style(),
                ]
            );
            $this->add_control(
                'title', [
                    'label'       => esc_html__( 'Header Title', 'element-ready-lite' ),
                    'type'        => Controls_Manager::TEXT,
                    'default'     => esc_html__( 'My Title' , 'element-ready-lite' ),
                    'label_block' => true,
                    'separator'   => 'before',
                ]
            );
            $this->add_control(
                'info_content', [
                    'label'      => esc_html__( 'Info Content', 'element-ready-lite' ),
                    'type'       => Controls_Manager::WYSIWYG,
                    'label_block' => true,
                    'separator'   => 'before',
                ]
            );
            
        $this->end_controls_section();
        /*--------------------------
            CONTENT SECTION END
        ---------------------------*/

        /*--------------------------
            AREA STYLE
        ---------------------------*/
        $this->start_controls_section(
            'wrapper_style_section',
            [
                'label' => esc_html__( 'Infobox Wrapper', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $icon_opt = apply_filters( 'element_ready_infotext_wrap_pro_message', $this->pro_message('wrap_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_infotext_wrap_styles', $this );

        $this->end_controls_section();
        /*----------------------------
            AREA STYLE END
        -----------------------------*/

        /*----------------------------
            HEADER TITLE
        -----------------------------*/
        $this->start_controls_section(
            'header_title_style_section',
            [
                'label' => esc_html__( 'Header Title', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => 'header_title_typography',
                    'selector' => '{{WRAPPER}} .infotext__header__title h3',
                ]
            );
            $this->add_control(
                'header_title_color',
                [
                    'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .infotext__header__title h3' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Background:: get_type(),
                [
                    'name'     => 'header_title_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .infotext__header__title h3',
                ]
            );

            $icon_opt = apply_filters( 'element_ready_infotext_header_pro_message', $this->pro_message('header_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_infotext_header_styles', $this );


        $this->end_controls_section();
        /*----------------------------
            HEADER TITLE END
        -----------------------------*/

        /*------------------------
			BOX STYLE
        -------------------------*/
        $this->start_controls_section(
            'box_style_section',
            [
                'label' => esc_html__( 'Details Box', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->start_controls_tabs( 'box_style_tabs' );
                $this->start_controls_tab(
                    'box_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'     => 'box_typography',
                            'selector' => '{{WRAPPER}} .single__infotext__box',
                        ]
                    );
                    $this->add_control(
                        'box_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .single__infotext__box' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'box_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single__infotext__box',
                        ]
                    );

                    $icon_opt = apply_filters( 'element_ready_infotext_box_pro_message', $this->pro_message('box_pro_messagte'), false );
                    $this->run_controls( $icon_opt );
                    do_action( 'element_ready_infotext_box_styles', $this );

                $this->end_controls_tab();
                $this->start_controls_tab(
                    'box_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );
                    $this->add_control(
                        'box_hover_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .single__infotext__box:hover' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'box_hover_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single__infotext__box:hover',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'box_hover_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .single__infotext__box:hover',
                        ]
                    );
                    $this->add_responsive_control(
                        'box_hover_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .single__infotext__box:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'after',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*-------------------------
			BOX STYLE END
        --------------------------*/
    }

    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'element_ready_infotext_box_attr', 'class', 'element__ready__info__box__wrap' );
        $this->add_render_attribute( 'element_ready_infotext_box_attr', 'class', esc_attr($settings[ 'info_box_style' ]) );
        
        ?>
            <div <?php echo $this->get_render_attribute_string('element_ready_infotext_box_attr'); ?> >
            <?php if( !empty( $settings['title'] ) ): ?>
                <div class = "infotext__header__title">
                    <h3><?php echo esc_html( $settings['title'] ); ?></h3>
                </div>
            <?php endif; ?>
            <?php if( !empty( $settings['info_content'] ) ): ?>
                <div class="single__infotext__box">
                    <?php echo wp_kses_post( wpautop( $settings[ 'info_content' ] ) ); ?>
                </div>
            <?php endif; ?>
            </div>
        <?php
    }
}