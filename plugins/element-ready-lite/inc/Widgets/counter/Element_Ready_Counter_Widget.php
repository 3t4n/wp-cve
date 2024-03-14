<?php

namespace Element_Ready\Widgets\counter;
use \Element_Ready\Base\Controls\Widget_Control\Element_ready_common_control as Content_Style;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor counter widget.
 *
 * Elementor widget that displays stats and numbers in an escalating manner.
 *
 * @since 1.0.0
 */
class Element_Ready_Counter_Widget extends Widget_Base {

	use Content_Style;

	/**
	 * Get widget name.
	 *
	 * Retrieve counter widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'counter';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve counter widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'ER Counter', 'element-ready-lite' );
	}
    
        
	public function get_categories() {
		return [ 'element-ready-addons' ];
	}

    public function get_keywords() {
        return [ 'counter up', 'counter','counter box' ];
    }

	/**
	 * Get widget icon.
	 *
	 * Retrieve counter widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-counter';
	}

	public function get_style_depends() {

        wp_register_style( 'eready-counter' , ELEMENT_READY_ROOT_CSS. 'widgets/counter.css' );
        return [ 'eready-counter' ];
    }

	/**
	 * Retrieve the list of scripts the counter widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'jquery-numerator' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	/*public function get_keywords() {
		return [ 'counter' ];
	}*/

	/**
	 * Register counter widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		/*---------------------------
			CONTENT SECTION
		----------------------------*/
		$this->start_controls_section(
			'section_counter',
			[
				'label' => esc_html__( 'Counter', 'element-ready-lite' ),
			]
		);
        
			$this->add_control(
				'show_icon',
				[
					'label'     => esc_html__( 'Show Icon ?', 'element-ready-lite' ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'no',
					'label_on'  => esc_html__( 'Show', 'element-ready-lite' ),
					'label_off' => esc_html__( 'Hide', 'element-ready-lite' ),
				]
			);
	        $this->add_control(
	            'counter_icon_type',
	            [
	                'label'   => esc_html__('Icon Type','element-ready-lite'),
	                'type'    => Controls_Manager::CHOOSE,
	                'options' => [                    
	                    'icon' =>[
	                        'title' => esc_html__('SVG / Font Icon','element-ready-lite'),
	                        'icon'  => 'fa fa-info',
	                    ],
	                    'img' =>[
	                        'title' => esc_html__('Image','element-ready-lite'),
	                        'icon'  => 'fa fa-picture-o',
	                    ]
	                ],
	                'default' => 'icon',
	                'condition' => [
	                    'show_icon' => 'yes',
	                ]
	            ]
	        );
	        $this->add_control(
	            'icon_image',
	            [
	                'label'   => esc_html__('Image Icon','element-ready-lite'),
	                'type'    => Controls_Manager::MEDIA,
	                'condition' => [
	                    'counter_icon_type' => 'img',
	                    'show_icon' => 'yes',
	                ]
	            ]
	        );
	        $this->add_group_control(
	            Group_Control_Image_Size::get_type(),
	            [
	                'name'      => 'icon_imagesize',
	                'default'   => 'thumbnail',
	                'separator' => 'none',
	                'condition' => [
	                    'counter_icon_type' => 'img',
	                    'show_icon' => 'yes',
	                ]
	            ]
	        );
	        $this->add_control(
	            'icon_font',
	            [
	                'label'     => esc_html__('SVG / Font Icon','element-ready-lite'),
	                'type'      => Controls_Manager::ICONS,
					'label_block' => true,
	                'default' => [
						'value' => 'fas fa-star',
						'library' => 'solid',
					],
	                'condition' => [
	                    'counter_icon_type' => 'icon',
	                    'show_icon' => 'yes',
	                ]
	            ]
	        );
			$this->add_control(
				'starting_number',
				[
					'label'   => esc_html__( 'Starting Number', 'element-ready-lite' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 0,
				]
			);
			$this->add_control(
				'ending_number',
				[
					'label'   => esc_html__( 'Ending Number', 'element-ready-lite' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 100,
				]
			);
			$this->add_control(
				'prefix',
				[
					'label'       => esc_html__( 'Number Prefix', 'element-ready-lite' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => 1,
				]
			);
			$this->add_control(
				'suffix',
				[
					'label'       => esc_html__( 'Number Suffix', 'element-ready-lite' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => esc_html__( 'Plus', 'element-ready-lite' ),
				]
			);
			$this->add_control(
				'duration',
				[
					'label'   => esc_html__( 'Animation Duration', 'element-ready-lite' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 2000,
					'min'     => 100,
					'step'    => 100,
				]
			);
			$this->add_control(
				'thousand_separator',
				[
					'label'     => esc_html__( 'Thousand Separator', 'element-ready-lite' ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => 'yes',
					'label_on'  => esc_html__( 'Show', 'element-ready-lite' ),
					'label_off' => esc_html__( 'Hide', 'element-ready-lite' ),
				]
			);
			$this->add_control(
				'thousand_separator_char',
				[
					'label'     => esc_html__( 'Separator', 'element-ready-lite' ),
					'type'      => Controls_Manager::SELECT,
					'condition' => [
						'thousand_separator' => 'yes',
					],
					'options' => [
						''  => 'Default',
						'.' => 'Dot',
						' ' => 'Space',
					],
				]
			);
			$this->add_control(
				'title',
				[
					'label'       => esc_html__( 'Title', 'element-ready-lite' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => esc_html__( 'Cool Number', 'element-ready-lite' ),
					'placeholder' => esc_html__( 'Cool Number', 'element-ready-lite' ),
				]
			);
			$this->add_control(
				'view',
				[
					'label'   => esc_html__( 'View', 'element-ready-lite' ),
					'type'    => Controls_Manager::HIDDEN,
					'default' => 'traditional',
				]
			);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_custom_shapes',
			[
				'label' => esc_html__( 'Custom Shape', 'element-ready-lite' ),
				
			]
		);

			$this->add_responsive_control(
				'custom_shapes_enable',
				[
					'label' => esc_html__( 'Enable?', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'position: relative;'  => esc_html__( 'Yes', 'element-ready-lite' ),
						'' => esc_html__( 'No', 'element-ready-lite' ),
					],
					'selectors' => [
						'{{WRAPPER}} .counter__icon' => '{{VALUE}}',
						'{{WRAPPER}} .counter__icon::after' => 'position: absolute; content: ""; z-index: -1;height: 100%;width: 94%;background-size: contain;background-repeat: no-repeat;'
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'custom_shapes__background',
					'label' => esc_html__( 'Background', 'element-ready-lite' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .counter__icon::after',
					'condition' => [
						'custom_shapes_enable' => [
							'position: relative;'
						]
					]
				]
			);

			$this->add_responsive_control(
				'ustom_shapes_width',
				[
					'label' => esc_html__( 'Width', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'condition' => [
						'custom_shapes_enable' => [
							'position: relative;'
						]
					],
					'selectors' => [
						'{{WRAPPER}} .single__counter .counter__icon::after' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
	
			$this->add_responsive_control(
				'ustom_shapes_height',
				[
					'label' => esc_html__( 'Height', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'condition' => [
						'custom_shapes_enable' => [
							'position: relative;'
						]
					],
					'selectors' => [
						'{{WRAPPER}} .single__counter .counter__icon::after' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ustom_shapes_left',
				[
					'label' => esc_html__( 'Left', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => -500,
							'max' => 1000,
							'step' => 5,
						],
						'%' => [
							'min' => -50,
							'max' => 100,
						],
					],
					'condition' => [
						'custom_shapes_enable' => [
							'position: relative;'
						]
					],
					'selectors' => [
						'{{WRAPPER}} .single__counter .counter__icon::after' => 'left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'ustom_shapes_bottom',
				[
					'label' => esc_html__( 'Bottom', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => -500,
							'max' => 1000,
							'step' => 5,
						],
						'%' => [
							'min' => -50,
							'max' => 100,
						],
					],
					'condition' => [
						'custom_shapes_enable' => [
							'position: relative;'
						]
					],
					'selectors' => [
						'{{WRAPPER}} .single__counter .counter__icon::after' => 'bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'custom_shapes_z_index',
				[
					'label' => esc_html__( 'Z-index', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px'],
					'range' => [
						'px' => [
							'min' => -500,
							'max' => 1000,
							'step' => 1,
						],
						
					],
					'condition' => [
						'custom_shapes_enable' => [
							'position: relative;'
						]
					],
					'selectors' => [
						'{{WRAPPER}} .single__counter .counter__icon::after' => 'z-index: {{SIZE}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'custom_shapes_z_border',
					'label' => esc_html__( 'Border', 'element-ready-lite' ),
					'condition' => [
						'custom_shapes_enable' => [
							'position: relative;'
						]
					],
					'selector' => '{{WRAPPER}} .single__counter .counter__icon::after',
				]
			);

			$this->add_responsive_control(
				'custom_shapes_border_radious',
				[
					'label' => esc_html__( 'Border Radius', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' , '%' ],
					'range' => [
						'px' => [
							'min' => -500,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => -50,
							'max' => 100,
						],
					],
					'condition' => [
						'custom_shapes_enable' => [
							'position: relative;'
						]
					],
					'selectors' => [
						'{{WRAPPER}} .single__counter .counter__icon::after' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);


		$this->end_controls_section();

		/*---------------------------
			CONTENT SECTION END
		----------------------------*/
        
        /*---------------------------
			STYLE SECTION
        -----------------------------*/
        $this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon', 'element-ready-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'icon_color',
				[
					'label'  => esc_html__( 'Icon Color', 'element-ready-lite' ),
					'type'   => Controls_Manager::COLOR,
	                'selectors' => [
						'{{WRAPPER}} .counter__icon' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Background:: get_type(),
				[
					'name'     => 'icon_background',
					'label'    => esc_html__( 'Background', 'element-ready-lite' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .counter__icon',
				]
			);

			$this->add_group_control(
				Group_Control_Typography:: get_type(),
				[
					'name'     => 'typography_icon',
					'selector' => '{{WRAPPER}} .counter__icon',
				]
			);

            $icon_opt = apply_filters( 'element_ready_counter_icon_pro_message', $this->pro_message('icon_pro_messagte'), false );
            $this->run_controls( $icon_opt );
			do_action( 'element_ready_counter_icon_styles', $this );

			$this->add_control(
			    'icon_hover_hidding',
			    [
			        'label'     => esc_html__( 'Icon Hover', 'element-ready-lite' ),
			        'type'      => Controls_Manager::HEADING,
			        'separator' => 'before',
			    ]
			);

			$this->add_control(
			'icon_hover_hr',
			    [
			        'type' => Controls_Manager::DIVIDER,
			    ]
			);

			$this->add_control(
				'icon_hover_color',
				[
					'label'  => esc_html__( 'Icon Hover Color', 'element-ready-lite' ),
					'type'   => Controls_Manager::COLOR,
	                'selectors' => [
						'{{WRAPPER}} .single__counter:hover .counter__icon' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Background:: get_type(),
				[
					'name'     => 'icon_hover_background',
					'label'    => esc_html__( 'Background', 'element-ready-lite' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .single__counter:hover .counter__icon',
				]
			);

		$this->end_controls_section();
		/*---------------------------
			ICON STYLE END
		-----------------------------*/

		/*----------------------------
			COUNTE NUMBER STYLE
		-----------------------------*/
		$this->start_controls_section(
			'section_number',
			[
				'label' => esc_html__( 'Number', 'element-ready-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'number_color',
				[
					'label'  => esc_html__( 'Color', 'element-ready-lite' ),
					'type'   => Controls_Manager::COLOR,
	                'selectors' => [
						'{{WRAPPER}} .counter__number__wrapper' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'number_hover_color',
				[
					'label'  => esc_html__( 'Counter Hover Color', 'element-ready-lite' ),
					'type'   => Controls_Manager::COLOR,
	                'selectors' => [
						'{{WRAPPER}} .single__counter:hover .counter__number__wrapper' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography:: get_type(),
				[
					'name'     => 'typography_number',
					'selector' => '{{WRAPPER}} .counter__number__wrapper',
				]
			);

            $icon_opt = apply_filters( 'element_ready_counter_number_pro_message', $this->pro_message('number_pro_messagte'), false );
            $this->run_controls( $icon_opt );
			do_action( 'element_ready_counter_number_styles', $this );

		$this->end_controls_section();
		/*---------------------------
			COUNTER NUMBER STYLE END
		-----------------------------*/

		/*---------------------------
			TITLE STYLE
		----------------------------*/
		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'element-ready-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'title_color',
				[
					'label'  => esc_html__( 'Text Color', 'element-ready-lite' ),
					'type'   => Controls_Manager::COLOR,
	                'selectors' => [
						'{{WRAPPER}} .counter__title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'title_hover_color',
				[
					'label'  => esc_html__( 'Title Hover Color', 'element-ready-lite' ),
					'type'   => Controls_Manager::COLOR,
	                'selectors' => [
						'{{WRAPPER}} .single__counter:hover .counter__title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography:: get_type(),
				[
					'name'     => 'typography_title',
					'selector' => '{{WRAPPER}} .counter__title',
				]
			);
	        $this->add_responsive_control(
	            'title_margin',
	            [
	                'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
	                'type'       => Controls_Manager::DIMENSIONS,
	                'size_units' => [ 'px', '%', 'em' ],
	                'selectors'  => [
	                    '{{WRAPPER}} .counter__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ],                
	                'default' => [
	                    'top'      => '0',
	                    'right'    => '0',
	                    'bottom'   => '0',
	                    'left'     => '0',
	                    'isLinked' => false
	                ],
	                'separator' => 'before',
	            ]
	        );
		$this->end_controls_section();
		/*---------------------------
			TITLE STYLE END
		----------------------------*/

		/*----------------------------
			BOX STYLE
		-----------------------------*/
		$this->start_controls_section(
			'box_style_section',
			[
				'label' => esc_html__( 'Box', 'element-ready-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$icon_opt = apply_filters( 'element_ready_counter_box_pro_message', $this->pro_message('box_pro_messagte'), false );
			$this->run_controls( $icon_opt );
			do_action( 'element_ready_counter_box_styles', $this );

		$this->end_controls_section();
		/*----------------------------
			BOX STYLE END
		-----------------------------*/
	}

	/**
	 * Render counter widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'counter', [
			'class'         => 'elementor-counter-number',
			'data-duration' => $settings['duration'],
			'data-to-value' => $settings['ending_number'],
		] );


        if( $settings['counter_icon_type'] == 'icon' && !empty($settings['icon_font'])  ){
            $counter_icon = sprintf('<div class="counter__icon">%s</div>' , element_ready_render_icons( $settings['icon_font'] ));
        }elseif( $settings['counter_icon_type'] == 'img' ){
			$counter_image = Group_Control_Image_Size::get_attachment_image_html( $settings, 'icon_imagesize', 'icon_image' );		
            $counter_icon = wp_kses_post( sprintf('<div class="counter__icon">%s</div>', $counter_image) );
        }else{
        	$counter_icon = '';
        }
		if ( ! empty( $settings['thousand_separator'] ) ) {
			$delimiter = empty( $settings['thousand_separator_char'] ) ? ',' : $settings['thousand_separator_char'];
			$this->add_render_attribute( 'counter', 'data-delimiter', $delimiter );
		}

		?>
        <div class="single__counter">
            <?php echo $counter_icon; ?>
            <h3 class="counter__number__wrapper">
                <span class="counter__number__prefix"><?php echo esc_html( $settings[ 'prefix' ] ); ?></span>
                <span <?php echo $this->get_render_attribute_string( 'counter' ); ?>><?php echo wp_kses_post( $settings[ 'starting_number' ] ); ?></span>
                <span class="counter__number__suffix"><?php echo esc_html( $settings[ 'suffix' ] ); ?></span>
            </h3>
            <?php if ( $settings['title'] ) : ?>
            	<div class="counter__title"><?php echo esc_html( $settings[ 'title' ] ); ?></div>
            <?php endif; ?>
        </div>
	<?php 
	}
}