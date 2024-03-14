<?php

namespace Element_Ready\Widgets\copyright_text;
use \Element_Ready\Base\Controls\Widget_Control\Element_ready_common_control as Content_Style;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Element_Ready_Copyright_Text extends Widget_Base {

    use Content_Style;
    public function get_name() {
        return 'Element_Ready_Copyright_Text';
    }
    
    public function get_title() {
        return esc_html__( 'ER Copyright Text', 'element-ready-lite' );
    }

    public function get_icon() {
        return 'eicon-lock';
    }
    
	public function get_categories() {

		return [ 'element-ready-addons' ];
	}

    public function get_keywords() {
        
        return [ 'copyright' ];
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
            $author_name = wp_get_theme()->get( 'Author' );
            $author_link = wp_get_theme()->get( 'AuthorURI' );
            $this->add_control(
                'copyright_text',
                [
                    'label'       => esc_html__( 'Copyright Text', 'element-ready-lite' ),
                    'type'        => Controls_Manager::WYSIWYG,
                    'default'     => sprintf('Copyright {COPYRIGHT} %s {YEAR} All Right Reserved', '<a href="'. $author_link .'">'. $author_name .'</a>' ),
                    'description' => sprintf( esc_html__( 'Set the footer copyright text. Use %s for showing year dianamicly and use %s or %s for getting dianamicly copyright sign.', 'element-ready-lite' ),'<mark>{YEAR}</mark>','<mark>&copy;</mark>','<mark>{COPYRIGHT}</mark>' ),
                ]
            );
            $this->add_responsive_control(
                '_content_wrap_align',
                [
                    'label'   => esc_html__( 'Alignment', 'element-ready-lite' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'element-ready-lite' ),
                            'icon'  => 'fa fa-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'element-ready-lite' ),
                            'icon'  => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'element-ready-lite' ),
                            'icon'  => 'fa fa-align-right',
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
            COPYRIGHT LINK STYLE
        -------------------------*/
        $this->start_controls_section(
            '_link_style_section',
            [
                'label'     => esc_html__( 'Links', 'element-ready-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'copyright_text!' => '',
                ]
            ]
        );
            $icon_opt = apply_filters( 'element_ready_copyright_link_pro_message', $this->pro_message('link_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_copyright_link_styles', $this );

        $this->end_controls_section();
        /*-----------------------
            COPYRIGHT LINK STYLE END
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
                    'copyright_text!' => '',
                ]
            ]
        );
            $this->add_control(
                'copyright_text_color',
                [
                    'label'  => esc_html__( 'Color', 'element-ready-lite' ),
                    'type'   => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .copyright__text__area' => 'color: {{VALUE}}',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => 'copyright_text_typography',
                    'label'    => esc_html__( 'Typography', 'element-ready-lite' ),
                    'selector' => '{{WRAPPER}} .copyright__text__area',
                ]
            );

            $icon_opt = apply_filters( 'element_ready_copyright_box_pro_message', $this->pro_message('box_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_copyright_box_styles', $this );
        $this->end_controls_section();
        /*---------------------------
            BOX STYLE END
        ----------------------------*/
    }

    protected function render( $instance = [] ) {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( 'copyright_text_wrap_attr', 'class', 'copyright__text__area' );
        ?>
        <div <?php echo $this->get_render_attribute_string('copyright_text_wrap_attr'); ?>>
            <?php if( !empty( $settings['copyright_text'] ) ): ?>
                <?php
                    $copyright_text  = str_replace( [ '{COPYRIGHT}', '{YEAR}' ], [ '&copy;', date( 'Y' ) ], $settings['copyright_text'] );
                    echo wp_kses( $copyright_text, wp_kses_allowed_html( 'post' ) );
                ?>
            <?php endif; ?>
        </div>
    <?php
    }
}