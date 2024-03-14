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
class Better_About extends Widget_Base {

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
		return 'better-about';
	}
	
	//script depend
	public function get_script_depends() { return [ 'swiper','wow','isotope','youtubepopup-js','bootstrap-js','splitting','parallaxie','simpleParallax','justifiedgallery','better-el-addons']; }

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
		return __( 'Better About', 'better-el-addons' );
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
		return 'eicon-image-before-after';
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
		// start of the Content tab section

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Section Style', 'bim_plg' ),
			]
		);

		$this->add_control(
			'better_about_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1' => __( 'Style 1', 'better-el-addons' ),
					'2' => __( 'Style 2', 'better-el-addons' ),
				],
				'default' => '1',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'About Settings', 'bim_plg' ),
			]
		);

		$this->add_control(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter your title', 'better-el-addons' ),
				'default' => esc_html__('We Make Creative Solutions', 'better-el-addons' ),
			]
        );

		$this->add_control(
			'section_subtitle',
			[
				'label' => esc_html__( 'Sub-Title Text', 'better-el-addons' ),
				'type' => Controls_Manager::WYSIWYG,
                'placeholder' => esc_html__( 'Enter your sub-title', 'better-el-addons' ),
                'default' => esc_html__('Quisque massa ipsum, luctus at tempus eleifend congue quis
				lectus. Morbi bibendum nisl id
				porttitor ultrices odio elit vestibulum metus, ac semper velit quam sed nulla aenean eu
				hendreritt.', 'better-el-addons' )
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'better-el-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => esc_url(\Elementor\Utils::get_placeholder_image_src()),
				],
				'condition' => [
					'better_about_style' => array('2')
				],
			]
        );

		$this->add_control(
			'section_number',
			[
				'label' => esc_html__( 'Number', 'better-el-addons' ),
				'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter Number', 'better-el-addons' ),
				'default' => esc_html__('25', 'better-el-addons' ),
				'condition' => [
					'better_about_style' => array('1')
				],
			]
		);
		
		$this->add_control(
			'section_text',
			[
				'label' => esc_html__( 'Text', 'better-el-addons' ),
				'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Enter your text', 'better-el-addons' ),
				'default' => esc_html__('years Of Experiences', 'better-el-addons' ),
				'condition' => [
					'better_about_style' => array('1')
				],
			]
        );

		$this->add_control(
			'number_image',
			[
				'label' => esc_html__( 'Choose Image', 'better-el-addons' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => esc_url(\Elementor\Utils::get_placeholder_image_src()),
				],
				'condition' => [
					'better_about_style' => array('1')
				],
			]
        );
		
		$this->add_control(
			'images_list',
			[
				'label' => __( 'Slider List', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'condition' => [
					'better_about_style' => array('1')
				],
				'default' => [
					[
						'title' => __( 'Slider Heading Title', 'better-el-addons' ),
						'number' => __( '01', 'better-el-addons' ),
					],
					[
						'title' => __( 'Slider Heading Title', 'better-el-addons' ),
						'number' => __( '02', 'better-el-addons' ),
					],
					[
						'title' => __( 'Slider Heading Title', 'better-el-addons' ),
						'number' => __( '03', 'better-el-addons' ),
					],
				],
				'fields' => [
					[
						'name' => 'title',
						'label' => __( 'Slider Heading Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Insert your slider heading title here..', 'better-el-addons' ),
						'default' => __( 'Slider Heading Title' ,  'better-el-addons'  ),
					],
					[
						'name' => 'number',
						'label' => __( 'Slider Subtitle', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Insert your slider subtitle here..', 'better-el-addons' ),
						'default' => __( 'Slider Subtitle' ,  'better-el-addons'  ),
					],
					[
						'name' => 'item_image',
						'label' => __( 'Slider Image', 'better-el-addons' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'name' => 'column',
						'label' => esc_html__( 'Grid Column', 'better-el-addons' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'6' => esc_html__( 'Two Column', 'better-el-addons' ),
							'3' => esc_html__( 'Four Column', 'better-el-addons' ),
						],
						'default' => '3'
					],

				],
				'title_field' => '{{{ title }}}',
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

		// Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-about .main-tit h2,{{WRAPPER}} .better-about .img-wrapper .title h3',
			]
		);

		// Sub-Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_sub_title_typography',
				'label' => esc_html__( 'Sub-Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-about .content p,{{WRAPPER}} .better-about .cont h4',
			]
		);

		$this->add_control(
			'better_title_color',
			[
				'label' => esc_html__( 'Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-about .img-wrapper .title h3' => '-webkit-text-stroke-color: {{VALUE}}',
				'{{WRAPPER}} .better-about.style-1 .main-tit h2' => 'color: {{VALUE}}',
                ],
				'condition' => [
					'better_about_style' => array('1','2')
				],
			]
        );

		$this->add_control(
			'better_sub_title_main_color',
			[
				'label' => esc_html__( 'Sub-Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-about .cont h4' => 'color: {{VALUE}}',
                ],
				'condition' => [
					'better_about_style' => array('2')
				],
			]
        );

        $this->add_control(
			'better_sub_title_color',
			[
				'label' => esc_html__( 'Sub-Title Accent Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-about .cont h4 .stroke' => '-webkit-text-stroke-color: {{VALUE}}',
                ],
				'condition' => [
					'better_about_style' => array('2')
				],
			]
        );

		// Sub-Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_text_typography',
				'label' => esc_html__( 'Text Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-about .content .exp h5',
				'condition' => [
					'better_about_style' => array('1')
				],
			]
		);

		$this->add_control(
			'better_text_color',
			[
				'label' => esc_html__( 'Text Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
				'{{WRAPPER}} .better-about.style-1 .content .exp h5' => 'color: {{VALUE}}',
                ],
				'condition' => [
					'better_about_style' => array('1')
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

		$settings = $this->get_settings_for_display();

		$style = $settings['better_about_style'];	
		require( 'styles/style'.$style.'.php' );
		
	}
}