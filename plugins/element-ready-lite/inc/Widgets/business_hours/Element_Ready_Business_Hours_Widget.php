<?php
namespace Element_Ready\Widgets\business_hours;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
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
use \Element_Ready\Base\Controls\Widget_Control\Element_ready_common_control as Content_Style;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Element_Ready_Business_Hours_Widget extends Widget_Base {

    use Content_Style;

    public function get_name() {
        return 'Element_Ready_Business_Hours_Widget';
    }
    
    public function get_title() {
        return esc_html__( 'ER Business Hours', 'element-ready-lite' );
    }

    public function get_icon() {
        return 'eicon-clock-o';
    }

    public function get_categories() {
        return [ 'element-ready-addons' ];
    }

    public function get_style_depends() {

        wp_register_style( 'eready-bussiness-hour' , ELEMENT_READY_ROOT_CSS. 'widgets/business-hour.css' );
        return [ 'eready-bussiness-hour' ];
      }

    public function get_keywords() {
        return [ 'time', 'hours', 'business hours', 'office time' ];
    }

    public function element_ready_infobox_style(){

        return apply_filters( 'element_ready_business_hour_style_presets', [
            'element__ready__business__hour__style__1' => esc_html__( 'Style One', 'element-ready-lite' ),
        ]);
    }

    protected function register_controls() {
        /*--------------------------
            CONTENT SECTION
        ---------------------------*/
        $this->start_controls_section(
            'infob_box_content_section',
            [
                'label' => esc_html__( 'Business Hours Content & Style', 'element-ready-lite' ),
            ]
        );
            $this->add_control(
                'info_box_style',
                [
                    'label'   => esc_html__( 'Business Hours Style', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'element__ready__business__hour__style__1',
                    'options' => $this->element_ready_infobox_style(),
                ]
            );
            $this->add_control(
                'title', [
                    'label'       => esc_html__( 'Header Title', 'element-ready-lite' ),
                    'type'        => Controls_Manager::TEXT,
                    'default'     => esc_html__( 'Office Time' , 'element-ready-lite' ),
                    'label_block' => true,
                    'separator'   => 'before',
                ]
            );

            $this->add_control(
                'separator_type',
                [
                    'label' => esc_html__( 'Separator?', 'element-ready-lite' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'text' => [
                            'title' => esc_html__( 'Text', 'element-ready-lite' ),
                            'icon' => 'eicon-t-letter-bold',
                        ],
                        'icon' => [
                            'title' => esc_html__( 'Icon', 'element-ready-lite' ),
                            'icon' => 'eicon-star',
                        ],
                        'img' => [
                            'title' => esc_html__( 'None', 'element-ready-lite' ),
                            'icon' => 'eicon-image',
                        ],
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'separator_text', [
                    'label'       => esc_html__( 'Separator Text', 'element-ready-lite' ),
                    'type'        => Controls_Manager::TEXT,
                    'default'     => esc_html__( ':' , 'element-ready-lite' ),
                    'label_block' => true,
                    'separator'   => 'before',
                    'condition' => [
                        'separator_type' => 'text',
                    ],
                ]
            );
            $this->add_control(
                'separator_icon',
                [
                    'label'     => esc_html__( 'Separator Icons', 'element-ready-lite' ),
                    'type'      => Controls_Manager::ICONS,
                    'label_block' => true,
                    'default'   => [
                        'default' => 'fa fa-check',
                        'library' => 'solid',
                    ],
                    'separator'   => 'before',
                    'condition' => [
                        'separator_type' => 'icon',
                    ],
                ]
            );
            $this->add_control(
                'separator_image',
                [
                    'label'   => esc_html__( 'Separator Image', 'element-ready-lite' ),
                    'type'    => Controls_Manager::MEDIA,
                    'default' => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'separator'   => 'before',
                    'condition' => [
                        'separator_type' => 'img',
                    ],
                ]
            );


            $repeater = new Repeater();
            $repeater->start_controls_tabs(
                'element_ready_list_tabs'
            );
            $repeater->start_controls_tab(
                'list_content_tab',
                [
                    'label' => esc_html__( 'Content', 'element-ready-lite' ),
                ]
            );
                $repeater->add_control(
                    'list_title', [
                        'label'       => esc_html__( 'Day Name', 'element-ready-lite' ),
                        'type'        => Controls_Manager::TEXT,
                        'label_block' => true,
                        'separator'   => 'before',
                    ]
                );
                $repeater->add_control(
                    'list_content', [
                        'label'      => esc_html__( 'Opening Time', 'element-ready-lite' ),
                        'type'       => Controls_Manager::TEXT,
                        'label_block' => true,
                        'separator'   => 'before',
                    ]
                );
            $repeater->end_controls_tab();
            $repeater->start_controls_tab(
                'list_style_tab',
                [
                    'label' => esc_html__( 'Style', 'element-ready-lite' ),
                ]
            );
                $repeater->add_control(
                    'current_item_heading',
                    [
                        'label'     => esc_html__( 'Current Item Style', 'element-ready-lite' ),
                        'type'      => Controls_Manager::HEADING,
                    ]
                );
                $repeater->add_control(
                    'current_item_title_color',
                    [
                        'label'     => esc_html__( 'Day Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}} .business__hour__day' => 'color: {{VALUE}}'
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_separator_color',
                    [
                        'label'     => esc_html__( 'Separator Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}} .business__hour__separator' => 'color: {{VALUE}}'
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_color',
                    [
                        'label'     => esc_html__( 'Time Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}}' => 'color: {{VALUE}}'
                        ],
                    ]
                );
                $repeater->add_group_control(
                    Group_Control_Background:: get_type(),
                    [
                        'name'      => 'current_item_background',
                        'label'     => esc_html__( 'Background', 'element-ready-lite' ),
                        'types'     => [ 'classic', 'gradient' ],
                        'separator' => 'before',
                        'selector'  => '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}}',
                    ]
                );
                $repeater->add_group_control(
                    Group_Control_Border:: get_type(),
                    [
                        'name'      => 'current_item_border',
                        'label'     => esc_html__( 'Border', 'element-ready-lite' ),
                        'separator' => 'before',
                        'selector'  => '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}}',
                    ]
                );
                $repeater->add_responsive_control(
                    'wrapper_padding',
                    [
                        'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors'  => [
                            '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
                $repeater->add_responsive_control(
                    'wrapper_margin',
                    [
                        'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                        'type'       => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors'  => [
                            '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
            $repeater->end_controls_tab();
            $repeater->start_controls_tab(
                'list_style_hover_tab',
                [
                    'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                ]
            );
                $repeater->add_control(
                    'current_item_hover_heading',
                    [
                        'label'     => esc_html__( 'Current Item Hover Style', 'element-ready-lite' ),
                        'type'      => Controls_Manager::HEADING,
                    ]
                );
                $repeater->add_control(
                    'current_item_hover_title_color',
                    [
                        'label'     => esc_html__( 'Day Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}}:hover .business__hour__day' => 'color: {{VALUE}}'
                        ],
                    ]
                );

                $repeater->add_control(
                    'current_item_hover_separator_color',
                    [
                        'label'     => esc_html__( 'Separator Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}}:hover .business__hour__separator' => 'color: {{VALUE}}'
                        ],
                    ]
                );
                $repeater->add_control(
                    'current_item_hover_color',
                    [
                        'label'     => esc_html__( 'Time Color', 'element-ready-lite' ),
                        'type'      => Controls_Manager::COLOR,
                        'separator' => 'before',
                        'selectors' => [
                            '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}}:hover' => 'color: {{VALUE}}'
                        ],
                    ]
                );
                $repeater->add_group_control(
                    Group_Control_Background:: get_type(),
                    [
                        'name'      => 'current_item_hover_background',
                        'label'     => esc_html__( 'Background', 'element-ready-lite' ),
                        'types'     => [ 'classic', 'gradient' ],
                        'separator' => 'before',
                        'selector'  => '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}}:hover',
                    ]
                );
                $repeater->add_group_control(
                    Group_Control_Border:: get_type(),
                    [
                        'name'      => 'current_item_hover_border',
                        'label'     => esc_html__( 'Border', 'element-ready-lite' ),
                        'separator' => 'before',
                        'selector'  => '{{WRAPPER}} .single__business__hours{{CURRENT_ITEM}}:hover',
                    ]
                );
            $repeater->end_controls_tab();
            $repeater->end_controls_tabs();
            $this->add_control(
                'content_list',
                [
                    'label'   => esc_html__( 'Add Business Hours', 'element-ready-lite' ),
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  => $repeater->get_controls(),
                    'default' => [
                        [
                            'list_title' => esc_html__( 'Saturday', 'element-ready-lite' ),
                            'list_content' => esc_html__( '10:00AM - 07:00PM', 'element-ready-lite' ),
                        ],
                        [
                            'list_title' => esc_html__( 'Sunday', 'element-ready-lite' ),
                            'list_content' => esc_html__( 'Closed', 'element-ready-lite' ),
                        ],
                        [
                            'list_title' => esc_html__( 'Monday', 'element-ready-lite' ),
                            'list_content' => esc_html__( '10:00AM - 07:00PM', 'element-ready-lite' ),
                        ],
                        [
                            'list_title' => esc_html__( 'Tuesday', 'element-ready-lite' ),
                            'list_content' => esc_html__( '10:00AM - 07:00PM', 'element-ready-lite' ),
                        ],
                        [
                            'list_title' => esc_html__( 'Wednesday', 'element-ready-lite' ),
                            'list_content' => esc_html__( '10:00AM - 07:00PM', 'element-ready-lite' ),
                        ],
                        [
                            'list_title' => esc_html__( 'Thursday', 'element-ready-lite' ),
                            'list_content' => esc_html__( '10:00AM - 07:00PM', 'element-ready-lite' ),
                        ],
                        [
                            'list_title' => esc_html__( 'Friday', 'element-ready-lite' ),
                            'list_content' => esc_html__( '10:00AM - 07:00PM', 'element-ready-lite' ),
                        ],
                    ],
                    'title_field' => '{{{ list_title }}}',
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
                'label' => esc_html__( 'Wrapper', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $icon_opt = apply_filters( 'element_ready_business_hour_wrap_pro_message', $this->pro_message('wrap_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_business_hour_wrap_styles', $this );

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
                    'selector' => '{{WRAPPER}} .business__hour__header__title h3',
                ]
            );
            $this->add_control(
                'header_title_color',
                [
                    'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .business__hour__header__title h3' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Background:: get_type(),
                [
                    'name'     => 'header_title_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .business__hour__header__title h3',
                ]
            );

            $icon_opt = apply_filters( 'element_ready_business_hour_title_pro_message', $this->pro_message('title_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_business_hour_title_styles', $this );

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
                'label' => esc_html__( 'Single Day Item', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $icon_opt = apply_filters( 'element_ready_business_hour_day_pro_message', $this->pro_message('day_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_business_hour_day_styles', $this );

        $this->end_controls_section();
        /*-------------------------
			BOX STYLE END
        --------------------------*/
    }

    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'element_ready_info_box_attr', 'class', 'element__ready__info__box__wrap' );
        $this->add_render_attribute( 'element_ready_info_box_attr', 'class', esc_attr( $settings[ 'info_box_style' ] ) );

        ?>
            <div <?php echo $this->get_render_attribute_string( 'element_ready_info_box_attr' ); ?> >
                <?php if( !empty( $settings['title'] ) ): ?>
                    <div class="business__hour__header__title" >
                        <h3><?php echo esc_html( $settings['title'] ); ?></h3>
                    </div>
                <?php endif; ?>
                <?php if( !empty( $settings['content_list'] ) ): ?>
                    <div class = "business__hours__list">
                        <?php foreach ( $settings['content_list'] as $content ): ?>
                            <?php
                                $separator = $list_title = $list_content = '';
                                if ( !empty( $content['list_title'] ) ) {
                                    $list_title = $content['list_title'];
                                }
                                if ( !empty( $content['list_content'] ) ) {
                                    $list_content = $content['list_content'];
                                }
                            ?>
                            <div class="single__business__hours elementor-repeater-item-<?php echo esc_attr($content['_id']); ?>">
                                <?php if ( !empty( $list_title || $list_content ) ) :?>

                                    <?php if( $list_title ) : ?>
                                        <div class="business__hour__day"><?php echo esc_html( $list_title ); ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if( $settings['separator_text'] || $settings['separator_icon']  || $settings['separator_image']  ) : ?>
                                        <div class="business__hour__separator">
                                        <?php 
                                            if ( 'text' == $settings['separator_type'] && $settings['separator_text'] ) {
                                                echo esc_html($settings['separator_text']);
                                            }elseif ( 'icon' == $settings['separator_type'] && $settings['separator_icon'] ) {
                                                Icons_Manager::render_icon( $settings['separator_icon'] );
                                            }elseif ( 'img' == $settings['separator_type'] && $settings['separator_image'] ) {
                                                echo wp_kses_post( wp_get_attachment_image( $settings['separator_image']['id'], 'thumbnail' ) );
                                            }
                                        ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if( $list_content ) : ?>
                                        <div class="business__hour__time"><?php echo esc_html( $list_content ); ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php
    }
}