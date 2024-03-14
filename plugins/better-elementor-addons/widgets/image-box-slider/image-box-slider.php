<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base; 
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.0.0
 */
class Better_Image_Box_Slider extends Widget_Base {

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
		return 'better-image-box-slider';
	}
		//script depend
	public function get_script_depends() { return [ 'better-slick','better-imgbox-slider', 'better-el-addons' ]; }

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
		return __( 'Better image slider', 'better-el-addons' );
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
		return 'eicon-image-box';
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
			'section_content',
			[
				'label' => __( 'Image-box Settings', 'better-el-addons' ),
			]
		);
		
		$this->add_control(
			'box_style',
			[
				'label' => __( 'Style', 'better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'style1' => __( 'Style 1', 'better-el-addons' ),
				],
				'default' => 'style1',
			]
		);
	
		$this->add_control(
			'imgbox_list',
			[
				'label' => __( 'Image-box List', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'title' => 'Image-box Title',
						'text' => 'Image-box Text',
					],
					[
						'title' => 'Image-box Title',
						'text' => 'Image-box Text',
					],
					[
						'title' => 'Image-box Title',
						'text' => 'Image-box Text',
					],
				],
				'fields' => [
					[
						'name' => 'title',
						'label' => __( 'Image-box Title', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Image-box Title', 'better-el-addons' ),
					],
					[
						'name' => 'price',
						'label' => __( 'Image-box Price', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Image-box Price', 'better-el-addons' ),
					],
					
					[
						'name' => 'image',
						'label' => __( 'Image', 'better-el-addons' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'name' => 'tags',
						'label' => __( 'Tags', 'better-el-addons' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'tag_1' => __( 'Tag 1', 'better-el-addons' ),
							'tag_2' => __( 'Tag 2', 'better-el-addons' ),
							'tag_3' => __( 'Tag 3', 'better-el-addons' ),
		
						],
						'default' => '',
					],
					[
						'name' => 'tag_text_1',
						'label' => __( 'Tag text', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Text', 'better-el-addons' ),
						'condition'	=> [
							'tags'	=> 'tag_1'
						]
					],
					[
						'name' => 'tag_link_1',
						'label' => __( 'Tag Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Link', 'better-el-addons' ),
						'condition'	=> [
							'tags'	=> 'tag_1'
						]
					],
					[
						'name' => 'tag_text_2',
						'label' => __( 'Tag text', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Text', 'better-el-addons' ),
						'condition'	=> [
							'tags'	=> 'tag_2'
						]
					],
					[
						'name' => 'tag_link_2',
						'label' => __( 'Tag Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Link', 'better-el-addons' ),
						'condition'	=> [
							'tags'	=> 'tag_2'
						]
					],
					[
						'name' => 'tag_text_3',
						'label' => __( 'Tag text', 'better-el-addons' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Text', 'better-el-addons' ),
						'condition'	=> [
							'tags'	=> 'tag_3'
						]
					],
					[
						'name' => 'tag_link_3',
						'label' => __( 'Tag Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Link', 'better-el-addons' ),
						'condition'	=> [
							'tags'	=> 'tag_3'
						]
					],

				],
				'title_field' => '{{ title }}',
			]
		);
		$this->add_control(
			'show_arrows',
			[
				'label' => __( 'Arrows','better-el-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'true' => __( 'Show','better-el-addons' ),
					'false' => __( 'Hide','better-el-addons' ),
				],
				'default' => 'false',
				'condition' => [
					'box_style' => array('style1')
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'title_settting',
			[
				'label' => __( 'Text Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-img-box-slider .h6' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => __( 'Typography', 'better-el-addons' ),
				'selector'  => '{{WRAPPER}} .better-img-box-slider .h6',
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'tags_settings',
			[
				'label' => __( 'Tags Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'tags_color',
			[
				'label' => __( 'Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-img-box-slider .tags a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'tags_typography',
				'label'     => __( 'Tags Typography', 'better-el-addons' ),
				'selector'  => '{{WRAPPER}} .better-img-box-slider .tags a',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'price_settting',
			[
				'label' => __( 'Price Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'price_color',
			[
				'label' => __( 'Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-img-box-slider .price' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'price_typography',
				'label'     => __( 'Typography', 'better-el-addons' ),
				'selector'  => '{{WRAPPER}} .better-img-box-slider .price',
			]
		);
		
		$this->end_controls_section();
		

		$this->start_controls_section(
			'item_settting',
			[
				'label' => __( 'Item Setting','better-el-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'border_color',
			[
				'label' => __( 'Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-img-box-slider .item' => 'border-color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'better-el-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .better-img-box-slider .item .cont' => 'background: {{VALUE}};',
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
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		
		
		$style = $settings['box_style'];

		require( 'styles/'.$style.'.php' ); 
	
		}

	/**
	 * Render the widget output in the editor. 
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {
		
		
	}
}


