<?php
namespace BetterWidgets\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;


/**
 * Elementor heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Better_Animated_Heading extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve heading widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-animated-heading';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve heading widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Better Animated Heading', 'elementor-hello-world' );
	}
	
	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'better-animated-headline' ];
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve heading widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-t-letter';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the heading widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'heading', 'title', 'text' ];
	}

	/**
	 * Register heading widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
			]
		);

        $this->add_control(
			'title_list',
			[
				'label' => __( 'Title List', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
                'default' => [
					[
						'title' => __( 'Add Your Heading Text Here', 'better-el-addons' ),
					],
					[
						'title' => __( 'Add Your Heading Text Here', 'better-el-addons' ),
					],
					[
						'title' => __( 'Add Your Heading Text Here', 'better-el-addons' ),
					],
				],
				'fields' => [
					[
						'name' => 'title',
						'label' => __( 'Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Enter your title', 'better-el-addons' ),
						'default' => __( 'Add Your Heading Text Here' ,  'better-el-addons'  ),
					],
                ],
				'title_field' => '{{{ title }}}',
            ]
        );

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your title', 'better-el-addons' ),
				'default' => esc_html__( 'Add Your Heading Text Here', 'better-el-addons' ),
			]
		);

		$this->add_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'better-el-addons' ),
					'small' => esc_html__( 'Small', 'better-el-addons' ),
					'medium' => esc_html__( 'Medium', 'better-el-addons' ),
					'large' => esc_html__( 'Large', 'better-el-addons' ),
					'xl' => esc_html__( 'XL', 'better-el-addons' ),
					'xxl' => esc_html__( 'XXL', 'better-el-addons' ),
				],
			]
		);

		$this->add_control(
			'header_size',
			[
				'label' => esc_html__( 'HTML Tag', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'better-el-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'better-el-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'better-el-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'better-el-addons' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'better-el-addons' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' => esc_html__( 'View', 'better-el-addons' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color_type',
			[
				'label' => esc_html__( 'Color type', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => 'Solid',
					'gradient' => 'Gradient',
					'stroke' => 'Stroke',
				],
				'default' => 'solid',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'title_color_type' => 'solid'
				]
			]
		);

		$this->add_control(
            'title_gradient_bg_color1',
            [
                'label' => _x( 'First Color', 'Background Control', 'better-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'title' => _x( 'First Color', 'Background Control', 'better-el-addons' ),
                'render_type' => 'ui',
                'condition' => [
                    'title_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );


        $this->add_control(
            'title_gradient_bg_color1_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'better-el-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'title_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'title_gradient_bg_color2',
            [
                'label' => _x( 'Second Color', 'Background Control', 'better-el-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2295b',
                'render_type' => 'ui',
                'condition' => [
                    'title_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'title_gradient_bg_color2_stop', 
            [
                'label' => _x( 'Location', 'Background Control', 'better-el-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'render_type' => 'ui',
                'condition' => [
                    'title_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'title_gradient_type', 
            [
                'label' => _x( 'Type', 'Background Control', 'better-el-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => _x( 'Linear', 'Background Control', 'better-el-addons' ),
                    'radial' => _x( 'Radial', 'Background Control', 'better-el-addons' ),
                ],
                'default' => 'linear',
                'render_type' => 'ui',
                'condition' => [
                    'title_color_type' => [ 'gradient'],
                ],
                'of_type' => 'gradient',
            ]
        );

        $this->add_control(
            'title_gradient_angle', 
            [
                'label' => _x( 'Angle', 'Background Control', 'better-el-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 180,
                ],
                'range' => [
                    'deg' => [
                        'step' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => 'background: linear-gradient({{SIZE}}{{UNIT}}, {{title_gradient_bg_color1.VALUE}} {{title_gradient_bg_color1_stop.SIZE}}{{title_gradient_bg_color1_stop.UNIT}},{{title_gradient_bg_color2.VALUE}} {{title_gradient_bg_color2_stop.SIZE}}{{title_gradient_bg_color2_stop.UNIT}}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],

                'condition' => [
                    'title_color_type' => [ 'gradient'],
                    'title_gradient_type' => 'linear',
                ],
                'of_type' => 'gradient',
            ]
        );

		$this->add_control(
			'title_stroke_color',
			[
				'label' => esc_html__( 'Text Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => '-webkit-text-stroke-color: {{VALUE}};',
				],
				'condition' => [
					'title_color_type' => [ 'stroke'],
				]
			]
		);

		$this->add_control(
            'title_stroke_size', 
            [
                'label' => _x( 'Angle', 'Background Control', 'better-el-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}}; color: transparent;',
                ],

                'condition' => [
                    'title_color_type' => [ 'stroke'],
                ],
            ]
        );

		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .elementor-heading-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .elementor-heading-title',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'better-el-addons' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hue' => 'Hue',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
	    $settings = $this->get_settings_for_display();

	    $this->add_render_attribute( 'title', 'class', 'elementor-heading-title' );

	    if ( ! empty( $settings['size'] ) ) {
	        $this->add_render_attribute( 'title', 'class', 'elementor-size-' . esc_attr($settings['size']) );
	    }
	    
	    $count = 0;
	    echo '<div class="better-animated-headline cd-headline clip"><div class="cd-words-wrapper">';
	    foreach ( $settings['title_list'] as $index => $item ) :

	        if ( $count == '0' ) {
	            $this->add_render_attribute( 'title', 'class', 'is-visible' );
	        } else {
	            $this->remove_render_attribute( 'title', 'class', 'is-visible' );
	            $this->add_render_attribute( 'title', 'class', 'is-hidden' );
	        }

	        $this->add_inline_editing_attributes( 'title' );

	        $title = $item['title'];

	        $title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $settings['header_size'] ), $this->get_render_attribute_string( 'title' ), esc_html($title) );

	        echo $title_html;
	        $count++;
	    endforeach;
	    echo '</div></div>';
	}


	/**
	 * Render heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {

	}
}
