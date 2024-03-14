<?php
namespace BetterWidgets\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly  


		
/**
 * @since 1.0.8
 */
class Better_Fancy extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.8
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-fancy';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.8
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Better Fancy', 'better_plg' );
	}

	//script depend
	public function get_script_depends() { return [ 'swiper','wow','isotope','youtubepopup-js','bootstrap-js','splitting','parallaxie','simpleParallax','justifiedgallery','scrollit','jquery.twentytwenty','counterup','better-el-addons']; }

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.8
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-slideshow';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.8
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
	 * @since 1.0.8
	 *
	 * @access protected
	 */
	protected function _register_controls() {
	
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Fancy Settings', 'better-el-addons' ),
			]
		);
		
		$this->add_control(
			'better_fancy_style',
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
		$this->add_control(
            'bg_image',
            [
                'label' => __( 'Bg Image', 'better-el-addons' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
							'url' => Utils::get_placeholder_image_src(),
				],
            ]
        );
		$this->add_control(
            't_image',
            [
                'label' => __( 'Top Image', 'better-el-addons' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
							'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'better_fancy_style' => array('1')
				],
            ]
        );
		$this->add_control(
            'b_image',
            [
                'label' => __( 'Bottom Image', 'better-el-addons' ),
                'type' => Controls_Manager::MEDIA,
				'default' => [
							'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'better_fancy_style' => array('1')
				],
            ]
        );
		$this->add_control(
			'title',
			[
				'label' => __( 'Title','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Insert your title..',
				'default' => 'Title here',
			]
		);
		$this->add_control(
            'title2',
            [
                'label' => __( 'Title 2', 'better-el-addons'),
                'type' => Controls_Manager::TEXT,
				'default' => __( '21', 'better-el-addons' ),
				'label_block' => true,
				'condition' => [
					'better_fancy_style' => array('2')
				],
            ]
        );
		
		$this->add_control(
			'subtitle',
			[
				'label' => __( 'Subtitle','better-el-addons' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'Leave it blank if you don\'t want to use this subtitle',
				'default' => 'Sub-title here',
				'condition' => [
					'better_fancy_style' => array('1')
				],
			]
		);

		$this->add_control(
            'text1',
            [
                'label' => __( 'Fancy subtitle 1', 'better-el-addons'),
                'type' => Controls_Manager::TEXT,
				'default' => __( 'Since', 'better-el-addons' ),
				'label_block' => true,
				'condition' => [
					'better_fancy_style' => array('2')
				],
            ]
        );
		$this->add_control(
            'fancy_symbol1',
            [
                'label' => __( 'Fancy Symbol 1', 'better-el-addons'),
                'type' => Controls_Manager::TEXT,
				'default' => __( 'K', 'better-el-addons' ),
				'label_block' => true,
				'condition' => [
					'better_fancy_style' => array('2')
				],
            ]
        );
		$this->add_control(
            'text2',
            [
                'label' => __( 'Fancy subtitle 2', 'better-el-addons'),
                'type' => Controls_Manager::TEXT,
				'default' => __( '1999', 'better-el-addons' ),
				'label_block' => true,
				'condition' => [
					'better_fancy_style' => array('2')
				],
            ]
        );
		$this->add_control(
            'fancy_symbol2',
            [
                'label' => __( 'Fancy Symbol 2', 'better-el-addons'),
                'type' => Controls_Manager::TEXT,
				'default' => __( 'K', 'better-el-addons' ),
				'label_block' => true,
				'condition' => [
					'better_fancy_style' => array('2')
				],
            ]
        );
		
		$this->end_controls_section();


		// start of the Style tab section
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Content Style', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Main Color
		$this->add_control(
			'better_fancy_color',
			[
				'label' => esc_html__( 'Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .better-fancy .ab-exp .years-exp' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'better_fancy_style' => array('1')
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_fancy_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-fancy .item h6',
				'condition' => [
					'better_fancy_style' => array('2')
				]
			]
		);

		$this->add_control(
			'better_fancy_box1_title_color',
			[
				'label' => esc_html__( 'First Box Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .better-fancy .item:first-of-type h6' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_fancy_style' => '2'
				]
			]
		);

		$this->add_control(
			'better_fancy_box2_title_color',
			[
				'label' => esc_html__( 'Second Box Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .better-fancy .item h6' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_fancy_style' => '2'
				]
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'better_fancy_sub_title_typography',
				'label' => esc_html__( 'Sub Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .better-fancy .item h4',
				'condition' => [
					'better_fancy_style' => array('2')
				]
			]
		);

		$this->add_control(
			'better_fancy_box1_sub_title_color',
			[
				'label' => esc_html__( 'First Box Sub Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .better-fancy .item:first-of-type h4' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_fancy_style' => '2'
				]
			]
		);

		$this->add_control(
			'better_fancy_box2_sub_title_color',
			[
				'label' => esc_html__( 'Second Box Sub Title Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .better-fancy .item h4' => 'color: {{VALUE}}',
				],
				'condition' => [
					'better_fancy_style' => '2'
				]
			]
		);

		$this->add_control(
			'better_fancy_box1_color',
			[
				'label' => esc_html__( 'First Box Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .better-fancy .stauts .item:first-of-type' => 'background: {{VALUE}}',
				],
				'condition' => [
					'better_fancy_style' => '2'
				]
			]
		);

		$this->add_control(
			'better_fancy_box2_color',
			[
				'label' => esc_html__( 'Second Box Color', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .better-fancy .stauts .item' => 'background: {{VALUE}}',
				],
				'condition' => [
					'better_fancy_style' => '2'
				]
			]
		);

		$this->end_controls_section();
		// end of the Content tab section
		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.8
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings(); 
		$style = $settings['better_fancy_style'];	
		require( 'styles/style'.$style.'.php' );
 
		}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.8
	 *
	 * @access protected
	 */
	protected function content_template() {
		
		
	}
}


