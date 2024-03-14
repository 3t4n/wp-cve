<?php
namespace BetterWidgets\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Utils;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 


		
/**
 * @since 1.0.1
 */
class Better_Testimonial_Carousel extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-testimonial';
	}

	//script depend
	public function get_script_depends() { return [ 'better-slick-js','better-slick','better-lib','better-testimonial','better-el-addons']; }

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Better Testimonials', 'better_plg' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-blockquote';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.1
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.1
	 *
	 * @access protected
	 */
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Testimonial Settings', 'better_plg' ),
			]
		);
		$this->add_control(
			'better_testimonial_style',
			[
				'label' => __( 'Style', 'bim_plg' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style1' => __( 'Style 1', 'bim_plg' ),
					'style2' => __( 'Style 2', 'bim_plg' ),
					'style3' => __( 'Style 3', 'bim_plg' ),
					'style4' => __( 'Style 4', 'bim_plg' ),
					'style5' => __( 'Style 5', 'bim_plg' ),
					'style6' => __( 'Style 6', 'bim_plg' ),
					'style7' => __( 'Style 7', 'bim_plg' ),
					'style8' => __( 'Style 8', 'bim_plg' ),
				],
				'default' => 'style1',
			]
		);

		$this->add_control(
			'dark_mode',
			[
				'label' => esc_html__( 'Dark Background', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'better-el-addons' ),
				'label_off' => esc_html__( 'No', 'better-el-addons' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'better_testimonial_style' => array('style3')
				],
			]
		);

		$this->add_control(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your title', 'better-el-addons' ),
				'default' => esc_html__('What People Says.', 'better-el-addons' ),
				'condition' => [
					'better_testimonial_style' => array('style3')
				],
			]
        );

		$this->add_control(
			'section_subtitle',
			[
				'label' => esc_html__( 'Sub-Title Text', 'better-el-addons' ),
				'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your sub-title', 'better-el-addons' ),
				'default' => esc_html__('Testimonials', 'better-el-addons' ),
				'condition' => [
					'better_testimonial_style' => array('style3')
				],
			]
		);
	
		$this->add_control(
			'testi_list',
			[
				'label' => __( 'Testimonial List', 'better_plg' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'title' => 'Testimonial Name',
						'position' => 'Testimonial Position',
						'text' => 'Testimonial Text',
						'rate' => '3.5',
					],
					[
						'title' => 'Testimonial Name',
						'position' => 'Testimonial Position',
						'text' => 'Testimonial Text',
						'rate' => '3.5',
					],
					[
						'title' => 'Testimonial Name',
						'position' => 'Testimonial Position',
						'text' => 'Testimonial Text',
						'rate' => '3.5',
					],
				],
				'fields' => [
					[
						'name' => 'number',
						'label' => __( 'Testimonial Number', 'better_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Testimonial Number..', 'better_plg' ),
					],
					[
						'name' => 'title',
						'label' => __( 'Testimonial Name', 'better_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Testimonial Name..', 'better_plg' ),
					],
					[
						'name' => 'position',
						'label' => __( 'Testimonial Position', 'better_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Testimonial Position..', 'better_plg' ),
					],
					[
						'name' => 'image',
						'label' => __( 'Client Image', 'better_plg' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'name' => 'text',
						'label' => __( 'Testimonial Text', 'better_plg' ),
						'type' => Controls_Manager::TEXTAREA,
						'label_block' => true,
						'placeholder' => __( 'Testimonial Text..', 'better_plg' ),
					],
					[
						'name' => 'rate',
						'label' => __( 'Testimonial Rate', 'better_plg' ),
						'type' => Controls_Manager::NUMBER,
						'label_block' => true,
					],
				],
				'title_field' => '{{ title }}',
			]
		);
		$this->end_controls_section();
		
		$this->start_controls_section(
			'item_settting',
			[
				'label' => __( 'Item Setting','better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_testimonial_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_control(
			'item_bg_color',
			[
				'label' => __( 'Background Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-testimonial.style-1 .slick-slide' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'title_settting',
			[
				'label' => __( 'Text Setting','better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_testimonial_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-testimonial .testi-text' => 'color: {{VALUE}};',
				],
				'condition' => [
					'better_testimonial_style' => ['style1','style1']
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => __( 'Typography', 'better_plg' ),
				'selector'  => '{{WRAPPER}} .better-testimonial .testi-text',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'name_settings',
			[
				'label' => __( 'Name Setting','better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_testimonial_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_control(
			'name_color',
			[
				'label' => __( 'Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-testimonial h3' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'name_typography',
				'label'     => __( 'Name Typography', 'better_plg' ),
				'selector'  => '{{WRAPPER}} .better-testimonial h3',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'post_settting',
			[
				'label' => __( 'Position Setting','better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_testimonial_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_control(
			'post_color',
			[
				'label' => __( 'Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-testimonial .testi-from' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'post_typography',
				'label'     => __( 'Typography', 'better_plg' ),
				'selector'  => '{{WRAPPER}} .better-testimonial .testi-from',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'quote_settting',
			[
				'label' => __( 'Quote Setting','better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_testimonial_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_control(
			'quote_color',
			[
				'label' => __( 'Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-testimonial .quote-icon' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'quotebg_color',
			[
				'label' => __( 'Background Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-testimonial .quote-icon' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'quote_radius',
			[
				'label' => __( 'Border Radius', 'better_plg' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-testimonial .fa' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'border_settting',
			[
				'label' => __( 'Border Setting','better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_testimonial_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_control(
			'border_color',
			[
				'label' => __( 'Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-testimonial .slick-slide' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'better_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-testimonial .slick-slide' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		
		$this->end_controls_section();

		$this->start_controls_section(
			'rating_settting',
			[
				'label' => __( 'Rating Setting','better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_testimonial_style' => array('style1','style2')
				],
			]
		);
		
		$this->add_control(
			'rating_bg_color',
			[
				'label' => __( 'Rating Color', 'better_plg' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-testimonial.style-1 .rating-icon' => 'color: {{VALUE}};',
				]
			]
		);
		
		
		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_testimonial_style' => array('style3','style4','style5')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'testi_background',
				'label' => __( 'Background', 'better-el-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .better-testimonial',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'testi_box_background',
				'label' => __( 'Box Background', 'better-el-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .better-testimonial .box',
			]
		);

		// Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-heading.style-2 h3',
			]
		);

		$this->add_control(
			'better_title_color',
			[
				'label' => esc_html__( 'Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-heading.style-2 h3' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
			]
		);

		// Sub-Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_sub_title_typography',
				'label' => esc_html__( 'Sub-Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-heading.style-2 h6',
			]
		);

		$this->add_control(
			'better_sub_title_color',
			[
				'label' => esc_html__( 'Sub-Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-heading.style-2 h6' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_testimonial_name_typography',
				'label' => esc_html__( 'Testimonial Name Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-testimonial.style-3 .item .info .author-name,{{WRAPPER}} .better-testimonial .item .info .author-name, {{WRAPPER}} .better-testimonial.style-6 h6',
			]
        );

		$this->add_control(
			'better_testimonial_name_color',
			[
				'label' => esc_html__( 'Testimonial Name Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-testimonial.style-3 .item .info .author-name,{{WRAPPER}} .better-testimonial .item .info .author-name, {{WRAPPER}} .better-testimonial.style-6 h6' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
			]
		);
        
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_testimonial_position_typography',
				'label' => esc_html__( 'Testimonial Position Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-testimonial.style-3 .item .info .author-details,{{WRAPPER}} .better-testimonial .item .info .author-details',
			]
        );

		$this->add_control(
			'better_testimonial_position_color',
			[
				'label' => esc_html__( 'Testimonial position Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-testimonial.style-3 .item .info .author-details,{{WRAPPER}} .better-testimonial .item .info .author-details' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
			]
		);
        
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_testimonial_text_typography',
				'label' => esc_html__( 'Testimonial Text Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-testimonial.style-3 .item p,{{WRAPPER}} .better-testimonial .item p',
			]
		);

		$this->add_control(
			'better_testimonial_text_color',
			[
				'label' => esc_html__( 'Testimonial Text Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-testimonial.style-3 .item p,{{WRAPPER}} .better-testimonial .item p' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
			]
		);

        $this->add_control(
			'better_testimonial_active_dot_color',
			[
				'label' => esc_html__( 'Testimonial Active Dot Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-testimonial.style-3 .slick-dots li.slick-active' => 'background: {{VALUE}}',
				'{{WRAPPER}} .better-testimonial .slick-dots li.slick-active' => 'background: {{VALUE}}',
                ],
                'separator' => 'before',
			]
        );

        $this->add_control(
			'better_testimonial_img_border_color',
			[
				'label' => esc_html__( 'Testimonial Image Border Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-testimonial.style-3 .item .info .img' => 'border-color: {{VALUE}}',
				'{{WRAPPER}} .better-testimonial .item .info .img' => 'border-color: {{VALUE}}',
				],
			]
        );

        $this->add_control(
			'better_testimonial_qoute_icon_background_color',
			[
				'label' => esc_html__( 'Qoute Icon Background Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-testimonial.style-3 .box .qoute-icon' => 'background: {{VALUE}}',
				],
			]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'style6_section',
			[
				'label' => __( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_testimonial_style' => array('style6','style7','style8')
				],
			]
		);

		// Name Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'style6_better_name_typography',
				'label' => esc_html__( 'Name Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-testimonial .item h6, {{WRAPPER}} .better-testimonial .item h6.author-details',
			]
		);

		// Name Background Color 
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'style6_better_name_background',
				'label' => esc_html__( 'Name Background Color', 'better-el-addons' ),
				'types' => ['gradient'],
				'type' => \Elementor\Controls_Manager::COLOR,
				'selector' => '{{WRAPPER}} .better-testimonial .item h6',
				'separator' => 'after',
				'condition' => [
					'better_testimonial_style' => 'style6'
				],
			]
        );

		$this->add_control(
			'style6_better_name_color',
			[
				'label' => esc_html__( 'Name Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-testimonial .item h6.author-name' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-testimonial .item h6' => 'color: {{VALUE}}',
                ],
				'condition' => [
					'better_testimonial_style' => array('style7','style8')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'style6_better_position_typography',
				'label' => esc_html__( 'Position Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-testimonial .item span.author-details, {{WRAPPER}} .better-testimonial .item span',
				'condition' => [
					'better_testimonial_style' => array('style7','style8')
				],
			]
        );

		$this->add_control(
			'style6_better_position_color',
			[
				'label' => esc_html__( 'Position Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-testimonial .item span.author-details' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-testimonial .item span' => 'color: {{VALUE}}',
                ],
				'condition' => [
					'better_testimonial_style' => array('style7','style8')
				],
			]
		);

		// Name Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'style6_better_text_typography',
				'label' => esc_html__( 'Text Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .better-testimonial .item p',
			]
		);

		$this->add_control(
			'style6_better_text_color',
			[
				'label' => esc_html__( 'Text Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-testimonial .item p' => 'color: {{VALUE}}',
                ],
			]
		);

		$this->add_control(
			'style6_better_testimonial_active_dot_color',
			[
				'label' => esc_html__( 'Testimonial Active Dot Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-testimonial .slick-dots li.slick-active' => 'background: {{VALUE}}',
				'{{WRAPPER}} .better-testimonial .slick-dots li' => 'border-color: {{VALUE}}',
                ],
				'condition' => [
					'better_testimonial_style' => 'style7'
				],
			]
        );

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.1
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings(); 

		$style = $settings['better_testimonial_style'];
		require( 'styles/'.$style.'.php' );	 
 
		}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.1
	 *
	 * @access protected
	 */
	protected function content_template() {
		
		
	}
}


