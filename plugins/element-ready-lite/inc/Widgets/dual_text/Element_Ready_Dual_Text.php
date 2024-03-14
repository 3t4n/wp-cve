<?php

namespace Element_Ready\Widgets\dual_text;
use \Element_Ready\Base\Controls\Widget_Control\Element_ready_common_control as Content_Style;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Element_Ready_Dual_Text extends Widget_Base {

    use Content_Style;

    public function get_name() {
        return 'Element_Ready_Dual_Text';
    }
    
    public function get_title() {
        return esc_html__( 'ER Dual Text', 'element-ready-lite' );
    }

    public function get_icon() {
        return 'eicon-form-vertical';
    }
    
	public function get_categories() {
		return [ 'element-ready-addons' ];
	}

    public function get_keywords() {
        return [ 'text', 'dual text' ];
    }

    static function content_layout_style(){
        return[
            '1'      => esc_html__( 'Style One', 'element-ready-lite' ),
            'custom' => esc_html__( 'Custom', 'element-ready-lite' ),
        ];
    }

    public function get_style_depends() {

        wp_register_style( 'eready-duel-text' , ELEMENT_READY_ROOT_CSS. 'widgets/duel-text.css' );
        return [ 'eready-duel-text' ];
    }
    
    protected function register_controls() {
        /*---------------------------
            CONTENT SECTION
        ----------------------------*/
        $this->start_controls_section(
            '_content_section',
            [
                'label' => esc_html__( 'Content', 'element-ready-lite' ),
            ]
        );
            $this->add_control(
                'content_layout_style',
                [
                    'label'   => esc_html__( 'Content Layout Style', 'element-ready-lite' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => self::content_layout_style(),
                ]
            );
            $this->add_control(
                'dual_text_first',
                [
                    'label'   => esc_html__( 'Dual Text First', 'element-ready-lite' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => 'First text',
                ]
            );
            $this->add_control(
                'dual_text_last',
                [
                    'label'   => esc_html__( 'Dual Text Last', 'element-ready-lite' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => 'This is last text',
                ]
            );
            $this->add_responsive_control(
                'dualtext_wrap_align',
                [
                    'label'   => esc_html__( 'Alignment', 'element-ready-lite' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'element-ready-lite' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'element-ready-lite' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'element-ready-lite' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                    ],
                    'separator' => 'before',
                    'selectors' => [
                        '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                    ],
                ]
            );
        $this->end_controls_section();
        /*---------------------------
            CONTENT SECTION END
        ----------------------------*/

        /*-----------------------
            DUAL TEXT FIRST
        -------------------------*/
        $this->start_controls_section(
            '_dualtext_first_style_section',
            [
                'label'     => esc_html__( 'First Text', 'element-ready-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'dual_text_first!' => '',
                ]
            ]
        );
            $this->add_control(
                'dualtext_first_color',
                [
                    'label'  => esc_html__( 'Color', 'element-ready-lite' ),
                    'type'   => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dual__text__first' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => 'dualtext_first_typography',
                    'label'    => esc_html__( 'Typography', 'element-ready-lite' ),
                    'selector' => '{{WRAPPER}} .dual__text__first',
                ]
            );
            $this->add_group_control(
                Group_Control_Background:: get_type(),
                [
                    'name'     => 'dualtext_first_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .dual__text__first',
                ]
            );

            $icon_opt = apply_filters( 'element_ready_dual_text_first_pro_message', $this->pro_message('first_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_dual_text_first_styles', $this );

        $this->end_controls_section();
        /*-----------------------
            DUAL TEXT FIRST END
        -------------------------*/

        /*-----------------------
            DUAL TEXT LAST
        -------------------------*/
        $this->start_controls_section(
            '_dualtext_last_style_section',
            [
                'label'     => esc_html__( 'Last Text', 'element-ready-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'dual_text_last!' => '',
                ]
            ]
        );
            $this->add_control(
                'dualtext_last_color',
                [
                    'label'  => esc_html__( 'Color', 'element-ready-lite' ),
                    'type'   => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dual__text__last' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => 'dualtext_last_typography',
                    'label'    => esc_html__( 'Typography', 'element-ready-lite' ),
                    'selector' => '{{WRAPPER}} .dual__text__last',
                ]
            );
            $this->add_group_control(
                Group_Control_Background:: get_type(),
                [
                    'name'     => 'dualtext_last_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .dual__text__last',
                ]
            );

            $icon_opt = apply_filters( 'element_ready_dual_text_last_pro_message', $this->pro_message('last_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_dual_text_last_styles', $this );


        $this->end_controls_section();
        /*-----------------------
            DUAL TEXT LAST END
        -------------------------*/

        /*---------------------------
            BOX STYLE
        ----------------------------*/
        $this->start_controls_section(
            '_style_section',
            [
                'label' => esc_html__( 'Box', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'dual_text_last!||dual_text_first!' => '',
                ]
            ]
        );
            $this->add_control(
                'dualtext_box_color',
                [
                    'label'  => esc_html__( 'Color', 'element-ready-lite' ),
                    'type'   => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .dual__text__area' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => 'dualtext_box_typography',
                    'label'    => esc_html__( 'Typography', 'element-ready-lite' ),
                    'selector' => '{{WRAPPER}} .dual__text__area',
                ]
            );
            $this->add_group_control(
                Group_Control_Background:: get_type(),
                [
                    'name'     => 'dualtext_box_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .dual__text__area',
                ]
            );

            $icon_opt = apply_filters( 'element_ready_dual_text_box_pro_message', $this->pro_message('box_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_dual_text_box_styles', $this );


        $this->end_controls_section();
        /*---------------------------
            BOX STYLE END
        ----------------------------*/
    }

    protected function render( $instance = [] ) {

        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'dual_text_wrap_attr', 'class', 'dual__text__area' );
        $this->add_render_attribute( 'dual_text_wrap_attr', 'class', 'dual__text__layout__'.esc_attr($settings['content_layout_style']) );

        ?>
        <div <?php echo $this->get_render_attribute_string('dual_text_wrap_attr'); ?>>
            <?php if( !empty( $settings['dual_text_first'] ) ): ?>
            <span class="dual__text__first"><?php echo esc_html( $settings['dual_text_first'] ); ?></span>
            <?php endif; ?>
            <?php if( !empty( $settings['dual_text_last'] ) ): ?>
            <span class="dual__text__last"><?php echo esc_html( $settings['dual_text_last'] ); ?></span>
            <?php endif; ?>
        </div>
    <?php
    }
}