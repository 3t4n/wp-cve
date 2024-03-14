<?php
namespace Enteraddons\Widgets\Photo_Stack;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Enteraddons elementor Photo Stack widget.
 *
 * @since 1.0
 */

class Photo_Stack extends Widget_Base {
    
	public function get_name() {
		return 'enteraddons-photo-stack';
	}

	public function get_title() {
		return esc_html__( 'Photo Stack', 'enteraddons' );
	}

	public function get_icon() {
		return 'entera entera-photo-stack';
	}

	public function get_categories() {
		return ['enteraddons-elements-category'];
	}

	protected function register_controls() {

        // ----------------------------------------  Photo Stack content ------------------------------
        $this->start_controls_section(
            'enteraddons_photo_stack_content',
            [
                'label' => esc_html__( 'Content', 'enteraddons' ),
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'image',
            [
                'label'   => esc_html__('Image', 'enteraddons'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $repeater->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'enteraddons' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => 'https://example.com',
				'dynamic' => [
					'active' => true,
				]
			]
		);

        $repeater->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__( 'Image Width', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '60',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-stack-item{{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'image_height',
            [
                'label' => esc_html__( 'Image Height', 'enteraddons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-stack-item{{CURRENT_ITEM}} img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );   
        $repeater->add_responsive_control(
            'image_offset_y',
            [
                'label'      => esc_html__('Offset Y', 'enteraddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 5,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ea-photo-stack-item{{CURRENT_ITEM}}'=> 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'image_offset_x',
            [
                'label'      => esc_html__('Offset X', 'enteraddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 5,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ea-photo-stack-item{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'image_z_index',
            [
                'label'     => esc_html__('Z-Index', 'enteraddons'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => -100,
                'max'       => 100,
                'step'      => 1,
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-stack-item{{CURRENT_ITEM}}' => 'z-index: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_list',
            [
                'show_label'  => true,
                'label'       => esc_html__('Items', 'enteraddons'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default'     => [
                    [
                        'image' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],

                        'image_width' => [
                            'size'  => 200,
                            'unit' => 'px',
                        ],
                        'image_height' => [
                            'size'  => 200,
                            'unit' => 'px',
                        ],
                        'image_offset_y' => [
                            'size' => 0,
                            'unit' => 'px',
                        ],
                        'image_offset_x' => [
                            'size' => 35,
                            'unit' => 'px',
                        ],
                    ],
                    [
                        'image'=> [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                        
                        'image_width' => [
                            'size'  => 300,
                            'unit' => 'px',
                        ],
                        'image_height' => [
                            'size'  => 300,
                            'unit' => 'px',
                        ],
                        'image_offset_y' => [
                            'size' => 250,
                            'unit' => 'px',
                        ],
                        'image_offset_x' => [
                            'size' => 0,
                            'unit' => 'px',
                        ],
                    ],
                    [
                        'image' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                        
                        'image_width' => [
                            'size'  => 400,
                            'unit' => 'px',
                        ],
                        'image_height' => [
                            'size'  => 400,
                            'unit' => 'px',
                        ],
                        'image_offset_y' => [
                            'size' => 100,
                            'unit' => 'px',
                        ],
                        'image_offset_x' => [
                            'size' => 180,
                            'unit' => 'px',
                        ],
                    ],
                ],
                
            ]
        );

        $this->end_controls_section();  

        // ---------------------------------------- Photo Stack  Settings ------------------------------
        $this->start_controls_section(
            'enteraddons_photo_stack_settings',
            [
                'label' => esc_html__( 'Photo Stack Settings', 'enteraddons' ),
            ]
        );

        $this->add_control(
            'image_animation',
            [
                'label'     => esc_html__('Animation', 'enteraddons'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    ''                    => esc_html__('None', 'enteraddons'),
                    'ea-bounce-sm'        => esc_html__('Bounce Small', 'enteraddons'),
                    'ea-bounce-md'        => esc_html__('Bounce Medium', 'enteraddons'),
                    'ea-bounce-lg'        => esc_html__('Bounce Large', 'enteraddons'),
                    'ea-fade'             => esc_html__('Fade', 'enteraddons'),
                    'ea-rotating'         => esc_html__('Rotating', 'enteraddons'),
                    'ea-rotating-inverse' => esc_html__('Rotating inverse', 'enteraddons'),
                    'ea-scale-sm'         => esc_html__('Scale Small', 'enteraddons'),
                    'ea-scale-md'         => esc_html__('Scale Medium', 'enteraddons'),
                    'ea-scale-lg'         => esc_html__('Scale Large', 'enteraddons'),
                ],
                'default'   => 'ea-bounce-sm',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label'     => esc_html__('Hover Animation', 'enteraddons'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'             => esc_html__('None', 'enteraddons'),
                    'fly-sm'           => esc_html__('Fly Small', 'enteraddons'),
                    'fly'              => esc_html__('Fly Medium', 'enteraddons'),
                    'fly-lg'           => esc_html__('Fly Large', 'enteraddons'),
                    'scale-sm'         => esc_html__('Scale Small', 'enteraddons'),
                    'scale'            => esc_html__('Scale Medium', 'enteraddons'),
                    'scale-lg'         => esc_html__('Scale Large', 'enteraddons'),
                    'scale-inverse-sm' => esc_html__('Scale Inverse Small', 'enteraddons'),
                    'scale-inverse'    => esc_html__('Scale Inverse Medium', 'enteraddons'),
                    'scale-inverse-lg' => esc_html__('Scale Inverse Large', 'enteraddons'),
                ],
                'default'   => 'scale-sm',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'animation_speed',
            [
                'label'       => esc_html__('Animation speed', 'enteraddons'),
                'description' => esc_html__('Please set your animation speed in seconds. Default value is 6s.', 'enteraddons'),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 0,
                'max'         => 100,
                'step'        => 1,
                'default'     => 6,
                'selectors'   => [
                    '{{WRAPPER}} .ea-photo-stack-wrap' => '--animation_speed:{{SIZE}}s',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_container_align',
            [
                'label'     => esc_html__('Alignment', 'enteraddons'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'enteraddons'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'enteraddons'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'enteraddons'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'toggle'    => true,
                'default'   => 'center',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section(); // End Photo Stack content settings

        /**
         * Style Tab
         * ------------------------------ Photo Stack Wrapper  Style Settings ------------------------------
         *
         */
        $this->start_controls_section(
            'enteraddons_photo_stack_wrapper_settings', [
                'label' => esc_html__( 'Warapper Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_container_width',
            [
                'label'          => esc_html__('Width', 'enteraddons'),
                'type'           => Controls_Manager::SLIDER,
                'size_units'     => ['px', '%'],
                'range'          => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 2000,
                    ],
                ],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors'      => [
                    '{{WRAPPER}} .ea-photo-stack-wrap' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_container_height',
            [
                'label'      => esc_html__('Minimum Height', 'enteraddons'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 600,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ea-photo-stack-wrap' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label' => esc_html__( 'Margin', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-stack-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label' => esc_html__( 'Padding', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-stack-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_border',
                'label' => esc_html__( 'Border', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-photo-stack-wrap',
            ]
        );
        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'enteraddons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .ea-photo-stack-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-photo-stack-wrap',
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wrapper_background',
                'label' => esc_html__( 'Background', 'enteraddons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .ea-photo-stack-wrap',
            ]
        );
        $this->end_controls_section();
         /**
         * Style Tab
         * ------------------------------ Photo Stack  Style Settings ------------------------------
         *
         */

        $this->start_controls_section(
            'enteraddons_photo_stack_style_settings', [
                'label' => esc_html__( 'Photo Stack Style', 'enteraddons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'border',
                'label'     => esc_html__( 'Border', 'enteraddons' ),
                'selector'  => '{{WRAPPER}} .ea-photo-stack-item img',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'enteraddons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'top'    => 5,
                    'right'  => 5,
                    'bottom' => 5,
                    'left'   => 5,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ea-photo-stack-item'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-photo-stack-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('tabs_hover_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__( 'Normal', 'enteraddons' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'img_box_shadow',
                'label'    => esc_html__( 'Box Shadow', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-photo-stack-item img',

            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__( 'Hover', 'enteraddons' ),
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'img_box_shadow_hover',
                'label'    => esc_html__( 'Box Shadow Hover', 'enteraddons' ),
                'selector' => '{{WRAPPER}} .ea-photo-stack-item img:hover',

            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
	}

	protected function render() {

        // get settings
        $settings = $this->get_settings_for_display();

        // template render
        $obj = new Photo_Stack_Template();
        $obj::setDisplaySettings( $settings );
        $obj->renderTemplate();

    }
    
    public function get_style_depends() {
        return [ 'enteraddons-global-style'];
    }


}
