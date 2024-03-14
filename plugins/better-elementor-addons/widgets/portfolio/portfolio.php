<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world. 
 *
 * @since 1.0.0
 */
class Better_Portfolio extends Widget_Base {

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
		return 'better-portfolio';
	}
	//script depend
	public function get_script_depends() { return ['imagesloaded-pkgd','better-portfolio','swiper','wow','isotope','youtubepopup-js','bootstrap-js','splitting','parallaxie','simpleParallax','justifiedgallery','jquery.twentytwenty','better-el-addons','elementor-hello-world']; }

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
		return __( 'Better Portfolio', 'better-el-addons' );
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
		return 'fa fa-clone';
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
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_portfolio_style',
			[
				'label' => __( 'Portfolio style', 'bim_plg' ),
			]
		);
		
		$this->add_control(
			'better_portfolio_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
					'3' => __( 'Style 3', 'better-el-addons' ),
					'4' => __( 'Style 4', 'better-el-addons' ),
					'5' => __( 'Style 5', 'better-el-addons' ),
					'6' => __( 'Style 6', 'better-el-addons' ),
					'7' => __( 'Style 7', 'better-el-addons' ),
					'8' => __( 'Style 8', 'better-el-addons' ),
				],
				'default' => '1',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_category_content',
			[
				'label' => __( 'Portfolio Category Settings', 'bim_plg' ),
				'condition' => [
					'better_portfolio_style' => array('4','6','7')
				],
			]
		);

		$this->add_control(
			'portfolio6_categories_show',
			[
				'label' => __( 'Portfolio Categories Show', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'your-plugin' ),
				'label_off' => __( 'Hide', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'better_portfolio_style' => array('6','7'),
				],
			]
		);

		$this->add_control(
			'portfolio_categories',
			[
				'label' => __( 'Portfolio Categories', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'better_portfolio_style' => array('4','6','7'),
					'portfolio6_categories_show' => 'yes'
				],
				'default' => [
					[
						'item_category_title' => 'Brand',
						'item_category_slug' => 'brand',
					],
					[
						'item_category_title' => 'Web',
						'item_category_slug' => 'web',
					],
					[
						'item_category_title' => 'Graphic',
						'item_category_slug' => 'graphic',
					],
				],
				'fields' => [ 
					[
						'name' => 'item_category_title',
						'label' => __( 'Category Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Web', 'better-el-addons'  ),
					],
					
					[
						'name' => 'item_category_slug',
						'label' => __( 'Category Slug', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'web', 'better-el-addons'  ),
					],
				],
				'title_field' => '{{ item_category_title }}',
			]
		);

		$this->end_controls_section();

		// start of the Content tab section
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Portfolio Settings', 'bim_plg' ),
			]
		);

		$this->add_responsive_control( 
			'port_title_display',
			[
				'label' => __( 'Title Display', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'block' => __( 'Show', 'better-el-addons' ),
					'none' => __( 'Hide', 'better-el-addons' ),
				],
				'default' => 'block',
				'condition' => [
					'better_portfolio_style' => array('7')
				],
			]
		);
		$this->add_control(
			'port_title',
			[
				'label' => __( 'Title','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' =>'Works',
				'condition' => [
					'better_portfolio_style' => array('7','8')
				],
			]
		);
	
		$this->add_control(
			'portfolio_one',
			[
				'label' => __( 'Portfolio one', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'better_portfolio_style' => array('1')
				],
				'default' => [
					[
						'title' => 'Main title',
						'subtitle' => 'Sub title',
						'link' => '#',
						'linktext' => 'View more',
					],
					[
						'title' => 'Main title',
						'subtitle' => 'Sub title',
						'link' => '#',
						'linktext' => 'View more',
					],
					[
						'title' => 'Main title',
						'subtitle' => 'Sub title',
						'link' => '#',
						'linktext' => 'View more',
					],
					[
						'title' => 'Main title',
						'subtitle' => 'Sub title',
						'link' => '#',
						'linktext' => 'View more',
					],
				],
				'fields' => [ 
					[
						'name' => 'title',
						'label' => __( 'Main Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Main Title', 'better-el-addons'  ),
					],
					
					[
						'name' => 'subtitle',
						'label' => __( 'Sub title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Sub title', 'better-el-addons'  ),
					],
					[
						'name' => 'link',
						'label' => __( 'Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => 'Leave link url',
					],
					[
						'name' => 'linktext',
						'label' => __( 'View more', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'View more', 'better-el-addons'  ),
					],
					[
						'name' => 'image',
						'label' => __( 'Client Image', 'bim_plg' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'title_field' => '{{ title }}',
			]
		);

		$this->add_control(
			'portfolio_items',
			[
				'label' => __( 'Portfolio Items', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'better_portfolio_style' => array('2','3','5','8')
				],
				'default' => [
					[
						'item_title' => 'Main title',
						'item_subtitle' => 'Sub title',
						'item_link' => '#0',
					],
					[
						'item_title' => 'Main title',
						'item_subtitle' => 'Sub title',
						'item_link' => '#0',
					],
					[
						'item_title' => 'Main title',
						'item_subtitle' => 'Sub title',
						'item_link' => '#0',
					],
					[
						'item_title' => 'Main title',
						'item_subtitle' => 'Sub title',
						'item_link' => '#0',
					],
				],
				'fields' => [ 
					[
						'name' => 'item_title',
						'label' => __( 'Main Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Main Title', 'better-el-addons'  ),
					],
					[
						'name' => 'item_cat',
						'label' => __( 'Item Category', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Sub title', 'better-el-addons'  ),
					],
					[
						'name' => 'item_cat_2',
						'label' => __( 'Item Category 2', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Sub title', 'better-el-addons'  ),
					],
					[
						'name' => 'item_link',
						'label' => __( 'Item Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => 'Leave link url',
                    ],
                    [
						'name' => 'item_cat_link',
						'label' => __( 'Item Category Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => 'Leave link url',
                    ],
					[
						'name' => 'item_cat_link_2',
						'label' => __( 'Item Category Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => 'Leave link url',
                    ],
                    [
						'name' => 'item_image',
						'label' => __( 'Item Image', 'bim_plg' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'title_field' => '{{ item_title }}',
			]
		);

		$this->add_control(
			'portfolio4_items',
			[
				'label' => __( 'Portfolio Items', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'better_portfolio_style' => array('4')
				],
				'default' => [
					[
						'portfolio4_item_title' => 'Main title',
						'portfolio4_item_subtitle' => 'Sub title',
						'portfolio4_item_link' => '#',
					],
					[
						'portfolio4_item_title' => 'Main title',
						'portfolio4_item_subtitle' => 'Sub title',
						'portfolio4_item_link' => '#',
					],
					[
						'portfolio4_item_title' => 'Main title',
						'portfolio4_item_subtitle' => 'Sub title',
						'portfolio4_item_link' => '#',
					],
					[
						'portfolio4_item_title' => 'Main title',
						'portfolio4_item_subtitle' => 'Sub title',
						'portfolio4_item_link' => '#',
					],
				],
				'fields' => [ 
					[
						'name' => 'portfolio4_item_category_slug_call',
						'label' => __( 'Category', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => esc_html__( 'Enter category slug to put item on it', 'better-el-addons'  ),
					],
					[
						'name' => 'portfolio4_item_title',
						'label' => __( 'Main Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Main Title', 'better-el-addons'  ),
					],
					
					[
						'name' => 'portfolio4_item_cat',
						'label' => __( 'Item Category', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Sub title', 'better-el-addons'  ),
					],
					[
						'name' => 'portfolio4_item_link',
						'label' => __( 'Item Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => 'Leave link url',
                    ],
                    [
						'name' => 'portfolio4_item_image',
						'label' => __( 'Item Image', 'better-el-addons' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'title_field' => '{{ portfolio4_item_title }}',
			]
		);

		$this->add_control(
			'portfolio6_info_style',
			[
				'label' => __( 'Portfolio Info Mode', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
				],
				'default' => '1',
				'condition' => [
					'better_portfolio_style' => array('6'),
				],
			]
		);

		$this->add_control(
			'portfolio6_items',
			[
				'label' => __( 'Portfolio Items', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'better_portfolio_style' => array('6','7')
				],
				'default' => [
					[
						'portfolio6_item_title' => 'Main title',
						'portfolio6_item_subtitle' => 'Sub title',
						'portfolio6_item_link' => '#0',
					],
					[
						'portfolio6_item_title' => 'Main title',
						'portfolio6_item_subtitle' => 'Sub title',
						'portfolio6_item_link' => '#0',
					],
					[
						'portfolio6_item_title' => 'Main title',
						'portfolio6_item_subtitle' => 'Sub title',
						'portfolio6_item_link' => '#0',
					],
					[
						'portfolio6_item_title' => 'Main title',
						'portfolio6_item_subtitle' => 'Sub title',
						'portfolio6_item_link' => '#0',
					],
				],
				'fields' => [ 
					[
						'name' => 'portfolio6_item_category_slug_call',
						'label' => __( 'Category', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => esc_html__( 'Enter category slug to put item on it', 'better-el-addons'  ),
					],
					[
						'name' => 'portfolio6_item_title',
						'label' => __( 'Main Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Main Title', 'better-el-addons'  ),
					],
					
					[
						'name' => 'portfolio6_item_cat',
						'label' => __( 'Item Category', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Sub title', 'better-el-addons'  ),
					],
					[
						'name' => 'portfolio6_item_cat_link',
						'label' => __( 'Category Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => 'Leave link url',
					],
					[
						'name' => 'portfolio6_item_cat2',
						'label' => __( 'Item Category 2', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Sub title', 'better-el-addons'  ),
					],
					[
						'name' => 'portfolio6_item_cat_link2',
						'label' => __( 'Category Link 2', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => 'Leave link url',
					],
					[
						'name' => 'portfolio6_item_link',
						'label' => __( 'Item Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'placeholder' => 'Leave link url',
                    ],
                    [
						'name' => 'portfolio6_item_image',
						'label' => __( 'Item Image', 'bim_plg' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'title_field' => '{{ portfolio6_item_title }}',
			]
		);

		$this->add_control(
			'info_vis',
			[
				'label' => __( 'Show Info Without Hover', 'better-el-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'better-el-addons' ),
				'label_off' => __( 'No', 'better-el-addons' ),
				'return_value' => 'yes',
				'condition' => [
					'better_portfolio_style' => array('7')
				],
			]
		);

		$this->add_responsive_control( 
			'filter_position',
			[
				'label' => __( 'Filter', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'aboveheading' => __( 'Above Heading', 'better-el-addons' ),
					'underheading' => __( 'Under Heading', 'better-el-addons' ),
				],
				'default' => 'aboveheading',
				'condition' => [
					'better_portfolio_style' => array('7')
				],
			]
		);

		$this->add_control(
			'better_portfolio4_columns',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'6' => __( '2 Columns', 'better-el-addons' ),
					'4' => __( '3 Columns', 'better-el-addons' ),
				],
				'default' => '6',
				'condition' => [
					'better_portfolio_style' => array('4','6','7')
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_image_box_main_title_typography',
				'label' => esc_html__( 'title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-portfolio .item .info h5',
				'condition' => [
					'better_portfolio_style' => array('1')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_image_box_title_typography',
				'label' => esc_html__( 'Subtitle Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-portfolio .item .info h6',
				'condition' => [
					'better_portfolio_style' => array('1')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_portfolio_item_title_typography',
				'label' => esc_html__( 'Item Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-portfolio.style-2 .content .cont h4, {{WRAPPER}} .better-portfolio.style-3 .swiper-slide .caption h1 span',
				'condition' => [
					'better_portfolio_style' => array('2','3')
				],
			]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_portfolio_section_title_typography',
				'label' => esc_html__( 'Section Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-portfolio .section-head h3',
				'condition' => [
					'better_portfolio_style' => array('7','8')
				],
			]
        );

		$this->add_control(
			'better_portfolio_section_title_color',
			[
				'label' => esc_html__( 'Section Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-portfolio .section-head h3' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_portfolio_style' => array('7','8')
				],
			]
		);
        
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_portfolio_item_cat_typography',
				'label' => esc_html__( 'Item Category Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-portfolio .gallery .items span a, {{WRAPPER}} .better-portfolio .cont span a, {{WRAPPER}} .better-portfolio.style-2 .content .cont h6, {{WRAPPER}} .better-portfolio.style-3 .swiper-slide .caption .tag, {{WRAPPER}} .better-portfolio .gallery .items .item span a',
				'condition' => [
					'better_portfolio_style' => array('2','3','6','7','8')
				],
			]
        );

		$this->add_control(
			'better_portfolio_item_cat_color',
			[
				'label' => esc_html__( 'Item Category Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-portfolio.style-2 .content .cont h6 a' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-portfolio .gallery .items .item span a' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-portfolio .gallery .items span a' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-portfolio .cont span a' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_portfolio_style' => array('2','6','7','8')
				],
			]
		);
		
		$this->add_control(
			'better_portfolio_process_bar_color',
			[
				'label' => esc_html__( 'Process Bar Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-portfolio.style-2.light .swiper-pagination-progressbar .swiper-pagination-progressbar-fill' => 'background: {{VALUE}}',
				],
				'condition' => [
					'better_portfolio_style' => array('2')
				],
			]
		);
		$this->add_control(
			'better_portfolio_item_nav_color',
			[
				'label' => esc_html__( 'Item Category Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-portfolio.style-2.light .swiper-nav-ctrl' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-portfolio.style-2.light .swiper-nav-ctrl .arrow:before' => 'background: {{VALUE}}',
				'{{WRAPPER}} .better-portfolio.style-2.light .swiper-nav-ctrl .arrow:after' => 'background: {{VALUE}}',
				'{{WRAPPER}} .better-portfolio.style-2.light .activeslide' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-portfolio.style-2.light .totalslide' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_portfolio_style' => array('2')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_portfolio4_item_title_typography',
				'label' => esc_html__( 'Item Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-portfolio .cont h5, {{WRAPPER}} .better-portfolio .gallery .items .overlay-info h5, {{WRAPPER}} .better-portfolio.style-6 .gallery .items h6',
				'condition' => [
					'better_portfolio_style' => array('4','6','7','8')
				],
			]
		);

		$this->add_control(
			'better_portfolio4_item_title_color',
			[
				'label' => esc_html__( 'Item Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .better-portfolio.gutter .gallery .items .overlay-info h5' => 'color: {{VALUE}}',
					'{{WRAPPER}} .better-portfolio .gallery .items h6' => 'color: {{VALUE}}',
					'{{WRAPPER}} .better-portfolio .gallery .items h5' => 'color: {{VALUE}}',
					'{{WRAPPER}} .better-portfolio .cont h5' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_portfolio_style' => array('4','6','7','8')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_portfolio4_item_subtitle_typography',
				'label' => esc_html__( 'Item Sub-Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-portfolio.gutter .gallery .items .overlay-info p',
				'condition' => [
					'better_portfolio_style' => array('4')
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_portfolio4_cat_typography',
				'label' => esc_html__( 'Category Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-portfolio .filtering span, {{WRAPPER}} .better-portfolio.style-6 .gallery .items span a',
				'condition' => [
					'better_portfolio_style' => array('4','6','7')
				],
			]
		);
		
		$this->add_control(
			'better_portfolio_process_cat_color',
			[
				'label' => esc_html__( 'Category Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-portfolio .filtering span' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-portfolio.style-6 .filtering span' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_portfolio_style' => array('4','6','7')
				],
			]
		);

		$this->add_control(
			'better_portfolio_process_cat_active_color',
			[
				'label' => esc_html__( 'Active Category Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-portfolio .filtering span.active' => 'color: {{VALUE}}',
				'{{WRAPPER}} .better-portfolio.style-6 .filtering span.active' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_portfolio_style' => array('4','6','7')
				],
			]
		);

		$this->add_control(
			'better_portfolio_filters_background_color',
			[
				'label' => esc_html__( 'Filters Background Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-portfolio .filtering .filter' => 'background: {{VALUE}}',
				'{{WRAPPER}} .better-portfolio.style-4.gutter .filtering' => 'background: {{VALUE}}',
				'{{WRAPPER}} .better-portfolio.style-6 .filtering .filter' => 'background: {{VALUE}}',
				],
				'condition' => [
					'better_portfolio_style' => array('4','6','7')
				],
			]
		);

		$this->add_responsive_control(
			'better_portfolio_items_padding',
			[
				'label' => __( 'Padding', 'better-el-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .better-portfolio .gallery .items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .better-portfolio.style-1 .item .info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'better_portfolio_filters_alignment',
			[
				'label' => esc_html__( 'Filters Alignment', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'better-el-addons' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'better-el-addons' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'better-el-addons' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => 'right',
				'selectors' => [
					'{{WRAPPER}} .better-portfolio.style-6 .filtering' => 'text-align-last: {{VALUE}}',
				],
				'condition' => [
					'better_portfolio_style' => array('6')
				],
			]
		);

		$this->end_controls_section();
		

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		// get our input from the widget settings.
		// $settings = $this->get_settings_for_display();
		// $better_portfolio_image = $settings['image']['url'];
		// $better_portfolio_title = $settings['title'];
		// $better_portfolio_desg = $settings['subtitle']; 
		
		$style = $settings['better_portfolio_style'];	
		require( 'styles/style'.$style.'.php' );

	}
}