<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Better_Price extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'price-plan';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'BETTER Price Plan', 'elementor-hello-world' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
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
		return [ 'elementor-hello-world' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'price_style_section',
			[
				'label' => esc_html__( 'Price Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'better_price_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
					'3' => __( 'Style 3', 'better-el-addons' ),
				],
				'default' => '1',
			]
		);

		$this->add_control(
			'better_price_mode_style',
			[
				'label' => __( 'Mode', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
				],
				'default' => '1',
				'condition' => [
					'better_price_style' => array('3')
				],
			]
		);

		$this->end_controls_section();

		// start of the Content tab section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Price Title
		$this->add_control(
			'better_price_box_title',
			[
				'label' => esc_html__( 'Price Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Standard' ),
			]
		);

		// Price Amount
		$this->add_control(
			'better_price_box_amount',
			[
				'label' => esc_html__( 'Price Amount', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( '$35' ),
			]
		);

		// Price Plan
		$this->add_control(
			'better_price_box_plan',
			[
				'label' => esc_html__( 'Price Plan', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Month' ),
			]
		);

		// Features List
		$this->add_control(
			'better_price_box_features_list',
			[
				'label' => esc_html__( 'Features List', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => [
					[
						'name' => 'better_price_box_features',
						'label' => esc_html__( 'Features Title', 'better-el-addons' ),
						'type' => \Elementor\Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Add New Feature' , 'better-el-addons' ),
					],
				],
				'default' => [
					[
						'text' => esc_html__( 'List Item #1', 'better-el-addons' ),
					],
					[
						'text' => esc_html__( 'List Item #2', 'better-el-addons' ),
					],
					[
						'text' => esc_html__( 'List Item #3', 'better-el-addons' ),
					],
				],
				'title_field' => '{{{ better_price_box_features }}}',
				'condition' => [
					'better_price_style' => array('1')
				],
			]
		);

		$this->add_control(
			'better_price2_box_features',
			[
				'label' => esc_html__( 'Features Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default' => esc_html__( 'Add New Feature' , 'better-el-addons' ),
				'condition' => [
					'better_price_style' => array('2','3')
				],
			]
		);
		
		$this->add_control(
			'better_popular_plan',
			[
				'label' => esc_html__( 'Popular plan', 'genesis-core' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'genesis-core' ),
				'label_off' => esc_html__( 'Hide', 'genesis-core' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'better_price_style' => array('2','3')
				],
			]
		);

		// Price Plan Button Text
		$this->add_control(
			'better_price_box_button_text',
			[
				'label' => esc_html__( 'Button Text', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Click me', 'better-el-addons' ),
			]
		);

		// Price Plan Button Link
		$this->add_control(
			'better_price_box_button_link', 
			[
				'label' => __( 'Button Link', 'better-el-addons' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'label_block' => true,
				'default'       => [
					'url'   => '#',
				],
			]
		);

		$this->end_controls_section();
		// end of the Content tab section

		// start of the Style tab section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_price_style' => array('1')
				],
			]
		);

		// Price Plan Title Options
		$this->add_control(
			'better_price_box_title_options',
			[
				'label' => esc_html__( 'Price Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Price Plan Title Color 
		$this->add_control(
			'better_price_box_title_color',
			[
				'label' => esc_html__( 'Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#333',
				'selectors' => [
				'{{WRAPPER}} .better-price.style-1 .price-title h4' => 'color: {{VALUE}}',
				],
			]
		);
		
		// Price Plan Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_price_box_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-price.style-1 .price-title h4',
			]
		);

		// Price Plan Amount Options
		$this->add_control(
			'better_price_box_amount_options',
			[
				'label' => esc_html__( 'Price Amount', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Price Plan Amount Color
		$this->add_control(
			'better_price_box_amount_color',
			[
				'label' => esc_html__( 'Amount Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#333',
				'selectors' => [
				'{{WRAPPER}} .better-price.style-1 .price-tag h2' => 'color: {{VALUE}}',
				],
			]
		);
		
		// Price Plan Amount Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_price_box_amount_typography',
				'label' => esc_html__( 'Amount Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-price.style-1 .price-tag h2',
			]
		);

		// Price Plan Amount Background
		$this->add_control(
			'better_price_box_amount_background',
			[
				'label' => esc_html__( 'Amount Background', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#fafafa',
				'selectors' => [
				'{{WRAPPER}} .better-price.style-1 .price-tag' => 'background-color: {{VALUE}}',
				],
			]
		);

		// Price Plan Options
		$this->add_control(
			'better_price_box_plan_options',
			[
				'label' => esc_html__( 'Price Plan', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Price Plan Color
		$this->add_control(
			'better_price_box_plan_color',
			[
				'label' => esc_html__( 'Amount Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#333',
				'selectors' => [
				'{{WRAPPER}} .better-price.style-1 .price-tag h2 span' => 'color: {{VALUE}}',
				],
			]
		);
		
		// Price Plan Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_price_box_plan_typography',
				'label' => esc_html__( 'Amount Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-price.style-1 .price-tag h2 span',
			]
		);

		// Price Plan Features Options
		$this->add_control(
			'better_price_box_features_options',
			[
				'label' => esc_html__( 'Price Features', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Price Plan Features Color
		$this->add_control(
			'better_price_box_features_color',
			[
				'label' => esc_html__( 'Features Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#333',
				'selectors' => [
				'{{WRAPPER}} .better-price.style-1 .price-item ul li' => 'color: {{VALUE}}',
				],
			]
		);
		
		// Price Plan Features Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_price_box_features_typography',
				'label' => esc_html__( 'Features Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-price.style-1 .price-item ul li',
			]
		);		

		// Price Plan Button Options
		$this->add_control(
			'better_price_box_button_options',
			[
				'label' => esc_html__( 'Price Button', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Price Plan Button Color
		$this->add_control(
			'better_price_box_button_color',
			[
				'label' => esc_html__( 'Text Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#fff',
				'selectors' => [
				'{{WRAPPER}} .better-price.style-1 a' => 'color: {{VALUE}}',
				],
			]
		);

		// Price Plan Button Background
		$this->add_control(
			'better_price_box_button_background',
			[
				'label' => esc_html__( 'Background Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
				'type' => \Elementor\Core\Schemes\Color::get_type(),
				'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			],
				'default' => '#f96152',
				'selectors' => [
				'{{WRAPPER}} .better-price.style-1 a' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		// Price Plan Button Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'abetter_price_box_button_typography',
				'label' => esc_html__( 'Button Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-price.style-1 a',
			]
		);

		$this->end_controls_section();

		// start of the Style tab section
		$this->start_controls_section(
			'style2_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'better_price_style' => array('2','3')
				],
			]
		);

		$this->add_control(
			'better_price2_box_background',
			[
				'label' => esc_html__( 'Background Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-price .item, {{WRAPPER}} .better-price .item.active' => 'background: {{VALUE}}',
                ],
			]
		);

		$this->add_control(
			'better_price2_popular_box_background',
			[
				'label' => esc_html__( 'popular Background Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-price .item.active:after' => 'background: {{VALUE}}',
                ],
                'separator' => 'after',
			]
		);
		
		// Price Plan Title Options
		$this->add_control(
			'better_price2_box_title_options',
			[
				'label' => esc_html__( 'Price Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		// Price Plan Title Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_price2_box_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-price.style-2 .item .type h5, {{WRAPPER}} .better-price.style-3 .item .title h5',
			]
		);

		$this->add_control(
			'better_price2_box_title_color',
			[
				'label' => esc_html__( 'Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-price.style-2 .item .type h5, {{WRAPPER}} .better-price.style-3 .item .title h5' => 'color: {{VALUE}}',
                ],
			]
		);

		// Price Plan Amount Options
		$this->add_control(
			'better_price2_box_amount_options',
			[
				'label' => esc_html__( 'Price Amount', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		// Price Plan Amount Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_price2_box_amount_typography',
				'label' => esc_html__( 'Price Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-price.style-2 .item .amount h2, {{WRAPPER}} .better-price.style-3 .item .amount h2',
			]
		);

		$this->add_control(
			'better_price2_box_amount_color',
			[
				'label' => esc_html__( 'Price Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-price.style-2 .item .amount h2, {{WRAPPER}} .better-price.style-3 .item .amount h2' => 'color: {{VALUE}}',
                ],
			]
		);

		// Price Plan Options
		$this->add_control(
			'better_price2_box_plan_options',
			[
				'label' => esc_html__( 'Price Plan', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		// Price Plan Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_price2_box_plan_typography',
				'label' => esc_html__( 'Period Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-price.style-2 .item .amount h2 i, {{WRAPPER}} .better-price.style-3 .item .amount h6',
			]
		);

		$this->add_control(
			'better_price2_box_plan_color',
			[
				'label' => esc_html__( 'Period Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-price.style-2 .item .amount h2 i, {{WRAPPER}} .better-price.style-3 .item .amount h6' => 'color: {{VALUE}}',
                ],
			]
		);

		// Price Plan Features Options
		$this->add_control(
			'better_price2_box_features_options',
			[
				'label' => esc_html__( 'Price Features', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		// Price Plan Features Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_price2_box_features_typography',
				'label' => esc_html__( 'Features Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-price.style-2 .item .feat p, {{WRAPPER}} .better-price.style-3 .item .cont',
			]
		);	
		
		$this->add_control(
			'better_price2_box_features_color',
			[
				'label' => esc_html__( 'Features Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-price.style-2 .item .feat p, {{WRAPPER}} .better-price.style-3 .item .cont' => 'color: {{VALUE}}',
                ],
			]
		);

		// Price Plan Button Options
		$this->add_control(
			'better_price2_box_button_options',
			[
				'label' => esc_html__( 'Price Button', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		// Price Plan Button Typography
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_price2_box_button_typography',
				'label' => esc_html__( 'Button Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .btn-curve.btn-lit span, {{WRAPPER}} .better-price .order a',
			]
		);

		$this->add_control(
			'better_price2_box_button_background',
			[
				'label' => esc_html__( 'Button Background Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .btn-curve.btn-lit, {{WRAPPER}} .better-price .order a.better-btn-pricing, {{WRAPPER}} .better-price .order a.better-btn-architec' => 'background: {{VALUE}}',
                ],
			]
		);

		$this->add_control(
			'better_price2_box_button_color',
			[
				'label' => esc_html__( 'Button text Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .btn-curve.btn-lit, {{WRAPPER}} .better-price .order a.better-btn-pricing, {{WRAPPER}} .better-price .order a.better-btn-architec' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
			]
		);

		$this->add_control(
			'better_price2_box_button_background_hover',
			[
				'label' => esc_html__( 'Button Background Color Hover', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .btn-curve.btn-lit:hover, {{WRAPPER}} .better-price .order a.better-btn-pricing:hover, {{WRAPPER}} .better-price .order a.better-btn-architec:hover, {{WRAPPER}} .better-btn-curve.btn-wit:after' => 'background: {{VALUE}}',
				'{{WRAPPER}} .better-btn-curve.btn-wit:hover' => 'border-color: {{VALUE}}',
                ],
			]
		);

		$this->add_control(
			'better_price2_box_button_color_hover',
			[
				'label' => esc_html__( 'Button text Color Hover', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .btn-curve.btn-lit:hover, {{WRAPPER}} .better-price .order a.better-btn-pricing:hover, {{WRAPPER}} .better-price .order a.better-btn-architec:hover, {{WRAPPER}} .better-price .order .better-btn-curve.btn-lit:hover span' => 'color: {{VALUE}}',
                ],
                'separator' => 'after',
			]
		);

		$this->end_controls_section();
		// end of the Style tab section
	}

	/**
	 * Render about us widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();
		$better_price_box_title = $settings['better_price_box_title'];
		$better_price_box_amount = $settings['better_price_box_amount'];
		$better_price_box_plan = $settings['better_price_box_plan'];
		$better_price_box_button_text = $settings['better_price_box_button_text'];
		$better_price_box_button_link =  $settings['better_price_box_button_link']['url'];
	   
		$style = $settings['better_price_style'];	
		require( 'styles/style'.$style.'.php' );

	}
}