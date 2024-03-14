<?php
/**
 * Post List widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

defined( 'ABSPATH' ) || die();

use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Skt_Addons_Elementor\Elementor\Controls\Select2;

class Post_List extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title () {
		return __( 'Post List', 'skt-addons-elementor' );
	}

	public function get_custom_help_url () {
		return '#';
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_icon () {
		return 'skti skti-post-list';
	}

	public function get_keywords () {
		return [ 'posts', 'post', 'post-list', 'list', 'news' ];
	}

	/**
	 * Get a list of All Post Types
	 *
	 * @return array
	 */
	public function get_post_types () {
		$post_types = skt_addons_elementor_get_post_types( [],[ 'elementor_library', 'attachment' ] );
		return $post_types;
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls () {
		$this->__post_list_content_controls();
		$this->__settings_content_controls();
	}

	protected function __post_list_content_controls () {

		$this->start_controls_section(
			'_section_post_list',
			[
				'label' => __( 'List', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_type',
			[
				'label' => __( 'Source', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_post_types(),
				'default' => key( $this->get_post_types() ),
			]
		);

		$this->add_control(
			'show_post_by',
			[
				'label' => __( 'Show post by:', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'recent',
				'options' => [
					'recent' => __( 'Recent Post', 'skt-addons-elementor' ),
					'selected' => __( 'Selected Post', 'skt-addons-elementor' ),
				],

			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Item Limit', 'skt-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'dynamic' => [ 'active' => true ],
				'condition' => [
					'show_post_by' => [ 'recent' ]
				]
			]
		);

		$repeater = [];

		foreach ( $this->get_post_types() as $key => $value ) {

			$repeater[$key] = new Repeater();

			$repeater[$key]->add_control(
				'title',
				[
					'label' => __( 'Title', 'skt-addons-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'placeholder' => __( 'Customize Title', 'skt-addons-elementor' ),
					'dynamic' => [
						'active' => true,
					],
				]
			);

			$repeater[$key]->add_control(
				'post_id',
				[
					'label' => __( 'Select ', 'skt-addons-elementor' ) . $value,
					'label_block' => true,
					'type' => Select2::TYPE,
					'multiple' => false,
					'placeholder' => 'Search ' . $value,
					'dynamic_params' => [
						'object_type' => 'post',
						'post_type'   => $key,
					],
				]
			);

			$this->add_control(
				'selected_list_' . $key,
				[
					'label' => '',
					'type' => Controls_Manager::REPEATER,
					'fields' => $repeater[$key]->get_controls(),
					'title_field' => '{{ title }}',
					'condition' => [
						'show_post_by' => 'selected',
						'post_type' => $key
					],
				]
			);
		}

		$this->end_controls_section();
	}

	protected function __settings_content_controls () {

		$this->start_controls_section(
			'_section_settings',
			[
				'label' => __( 'Settings', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'Layout', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'default' => 'list',
				'options' => [
					'list' => [
						'title' => __( 'List', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => __( 'Inline', 'skt-addons-elementor' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'feature_image',
			[
				'label' => __( 'Featured Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'feature_image_pos',
			[
				'label' => __( 'Image Position', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' => __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
				],
				'style_transfer' => true,
				'condition' => [
					'feature_image' => 'yes'
				],
				'selectors_dictionary' => [
					'left' => 'flex-direction: row',
					'top' => 'flex-direction: column',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-post-list .skt-post-list-item a' => '{{VALUE}};',
					'{{WRAPPER}} .skt-post-list-item a img' => 'margin-right: 0px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'post_image',
				'default' => 'thumbnail',
				'exclude' => [
					'custom'
				],
				'condition' => [
					'feature_image' => 'yes'
				]
			]
		);

		$this->add_control(
			'list_icon',
			[
				'label' => __( 'List Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'feature_image!' => 'yes'
				]
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'far fa-check-circle',
					'library' => 'reguler'
				],
				'condition' => [
					'list_icon' => 'yes',
					'feature_image!' => 'yes'
				]
			]
		);

		$this->add_control(
			'content',
			[
				'label' => __( 'Show Content', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'meta',
			[
				'label' => __( 'Show Meta', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'author_meta',
			[
				'label' => __( 'Author', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'meta' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_icon',
			[
				'label' => __( 'Author Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'far fa-user',
					'library' => 'reguler',
				],
				'condition' => [
					'meta' => 'yes',
					'author_meta' => 'yes',
				]
			]
		);

		$this->add_control(
			'date_meta',
			[
				'label' => __( 'Date', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'meta' => 'yes',
				]
			]
		);

		$this->add_control(
			'date_icon',
			[
				'label' => __( 'Date Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'far fa-calendar-check',
					'library' => 'reguler',
				],
				'condition' => [
					'meta' => 'yes',
					'date_meta' => 'yes',
				]
			]
		);

		$this->add_control(
			'category_meta',
			[
				'label' => __( 'Category', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'meta' => 'yes',
					'post_type' => 'post',
				]
			]
		);

		$this->add_control(
			'category_icon',
			[
				'label' => __( 'Category Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'far fa-folder-open',
					'library' => 'reguler',
				],
				'condition' => [
					'meta' => 'yes',
					'category_meta' => 'yes',
					'post_type' => 'post',
				]
			]
		);

		$this->add_control(
			'meta_position',
			[
				'label' => __( 'Meta Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'top' => __( 'Top', 'skt-addons-elementor' ),
					'bottom' => __( 'Bottom', 'skt-addons-elementor' ),
				],
				'condition' => [
					'meta' => 'yes',
				]
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'h1' => [
						'title' => __( 'H1', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h1'
					],
					'h2' => [
						'title' => __( 'H2', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h2'
					],
					'h3' => [
						'title' => __( 'H3', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h3'
					],
					'h4' => [
						'title' => __( 'H4', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h4'
					],
					'h5' => [
						'title' => __( 'H5', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h5'
					],
					'h6' => [
						'title' => __( 'H6', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h6'
					]
				],
				'default' => 'h2',
				'toggle' => false,
			]
		);

		$this->add_control(
			'item_align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => true,
				'selectors_dictionary' => [
					'left' => 'justify-content: flex-start',
					'center' => 'justify-content: center',
					'right' => 'justify-content: flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-post-list .skt-post-list-item a' => '{{VALUE}};'
				],
				'condition' => [
					'view' => 'list',
					'feature_image_pos' => 'left',
				]
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls () {
		$this->__post_list_style_controls();
		$this->__title_style_controls();
		$this->__icon_image_style_controls();
		$this->__excerpt_style_controls();
		$this->__meta_style_controls();
	}

	protected function __post_list_style_controls () {

		$this->start_controls_section(
			'_section_post_list_style',
			[
				'label' => __( 'List', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'list_item_common',
			[
				'label' => __( 'Common', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'list_item_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-post-list .skt-post-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-post-list .skt-post-list-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'list_item_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-post-list .skt-post-list-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'list_item_box_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-post-list .skt-post-list-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'list_item_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-post-list .skt-post-list-item',
			]
		);

		$this->add_responsive_control(
			'list_item_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-post-list .skt-post-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'advance_style',
			[
				'label' => __( 'Advance Style', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'skt-addons-elementor' ),
				'label_off' => __( 'Off', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_responsive_control(
			'list_item_first',
			[
				'label' => __( 'First Item', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_first_child_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-post-list .skt-post-list-item:first-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'list_item_first_child_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-post-list .skt-post-list-item:first-child',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_last',
			[
				'label' => __( 'Last Item', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_last_child_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-post-list .skt-post-list-item:last-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'list_item_last_child_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-post-list .skt-post-list-item:last-child',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __title_style_controls () {

		$this->start_controls_section(
			'_section_post_list_title_style',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .skt-post-list-title',
			]
		);

		$this->start_controls_tabs( 'title_tabs' );
		$this->start_controls_tab(
			'title_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-post-list-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'title_hvr_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-post-list .skt-post-list-item a:hover .skt-post-list-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __icon_image_style_controls () {

		$this->start_controls_section(
			'_section_list_icon_feature_iamge_style',
			[
				'label' => __( 'Icon & Feature Image', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'feature_image',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'list_icon',
							'operator' => '==',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} span.skt-post-list-icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'feature_image!' => 'yes',
					'list_icon' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Font Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} span.skt-post-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image!' => 'yes',
					'list_icon' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'icon_line_height',
			[
				'label' => __( 'Line Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} span.skt-post-list-icon' => 'line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image!' => 'yes',
					'list_icon' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Image Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-post-list-item a img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_boder',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-post-list-item a img',
				'condition' => [
					'feature_image' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'image_boder_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-post-list-item a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'feature_image' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'icon_margin_right',
			[
				'label' => __( 'Margin Right', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => '15',
				],
				'selectors' => [
					'{{WRAPPER}} span.skt-post-list-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-post-list-item a img' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image_pos' => 'left'
				],
			]
		);

		$this->add_responsive_control(
			'feature_margin_bottom',
			[
				'label' => __( 'Margin Bottom', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => '15',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-post-list-item a img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'feature_image_pos' => 'top'
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __excerpt_style_controls () {

		$this->start_controls_section(
			'_section_list_excerpt_style',
			[
				'label' => __( 'Content', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .skt-post-list-excerpt p',
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .skt-post-list-excerpt p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_space',
			[
				'label' => __( 'Space Top', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-post-list-excerpt' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __meta_style_controls () {

		$this->start_controls_section(
			'_section_list_meta_style',
			[
				'label' => __( 'Meta', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .skt-post-list-meta-wrap span',
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .skt-post-list-meta-wrap span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_space',
			[
				'label' => __( 'Space Between', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-post-list-meta-wrap span' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-post-list-meta-wrap span:last-child' => 'margin-right: 0;',
				],
			]
		);

		$this->add_responsive_control(
			'meta_box_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-post-list-meta-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'meta_icon_heading',
			[
				'label' => __( 'Meta Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_icon_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .skt-post-list-meta-wrap span i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_icon_space',
			[
				'label' => __( 'Space Between', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-post-list-meta-wrap span i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render () {

		$settings = $this->get_settings_for_display();

		if ( ! $settings['post_type'] ){
			return;
		}

		$args = [
			'post_status'      => 'publish',
			'post_type'        => $settings['post_type'],
			'suppress_filters' => false,
		];

		if ( 'recent' === $settings['show_post_by'] ) {
			$args['posts_per_page'] = $settings['posts_per_page'];
		}

		$customize_title = [];
		$ids = [];

		if ( 'selected' === $settings['show_post_by'] ) {
			$args['posts_per_page'] = -1;
			$lists = $settings['selected_list_' . $settings['post_type']];

			if ( ! empty( $lists ) ) {
				foreach ( $lists as $index => $value ) {
					//trim function to remove extra space before post ID
					if( is_array($value['post_id']) ){
						$post_id = ! empty($value['post_id'][0]) ? trim($value['post_id'][0]) : '';
					}else{
						$post_id = ! empty($value['post_id']) ? trim($value['post_id']) : '';
					}
					$ids[] = $post_id;
					if ( $value['title'] ) $customize_title[$post_id] = $value['title'];
				}
			}

			$args['post__in'] = (array) $ids;
			$args['orderby'] = 'post__in';
		}

		if ( 'selected' === $settings['show_post_by'] && empty( $ids ) ) {
			$posts = [];
		} else {
			$posts = get_posts( $args );
		}

		$this->add_render_attribute( 'wrapper', 'class', [ 'skt-post-list-wrapper' ] );
		$this->add_render_attribute( 'wrapper-inner', 'class', [ 'skt-post-list' ] );
		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'wrapper-inner', 'class', [ 'skt-post-list-inline' ] );
		}
		$this->add_render_attribute( 'item', 'class', [ 'skt-post-list-item' ] );

		if ( count( $posts ) !== 0 ) :?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<ul <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?> >
					<?php foreach ( $posts as $post ): ?>
						<li <?php $this->print_render_attribute_string( 'item' ); ?>>
							<a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>">
								<?php if ( 'yes' === $settings['feature_image'] ):
									echo wp_kses_post(get_the_post_thumbnail( $post->ID, $settings['post_image_size'] ));
								elseif ( 'yes' === $settings['list_icon'] && $settings['icon'] ) :
									echo wp_kses_post('<span class="skt-post-list-icon">');
									Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
									echo wp_kses_post('</span>');
								endif; ?>
								<div class="skt-post-list-content">
									<?php
									$title = $post->post_title;
									if ( 'selected' === $settings['show_post_by'] && array_key_exists( $post->ID, $customize_title ) ) {
										$title = $customize_title[$post->ID];
									}
									if ( 'top' !== $settings['meta_position'] && $title ) {
										printf( '<%1$s %2$s>%3$s</%1$s>',
											skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ),
											'class="skt-post-list-title"',
											esc_html( $title )
										);
									}
									?>
									<?php if ( 'yes' === $settings['meta'] ): ?>
										<div class="skt-post-list-meta-wrap">

											<?php if ( 'yes' === $settings['author_meta'] ):
												?>
												<span class="skt-post-list-author">
												<?php if ( $settings['author_icon'] ):
													Icons_Manager::render_icon( $settings['author_icon'], [ 'aria-hidden' => 'true' ] );
												endif;
												echo esc_html( get_the_author_meta( 'display_name', $post->post_author ) ); ?>
												</span>
											<?php endif; ?>

											<?php if ( 'yes' === $settings['date_meta'] ): ?>
												<span class="skt-post-list-date">
													<?php if ( $settings['date_icon'] ):
														Icons_Manager::render_icon( $settings['date_icon'], [ 'aria-hidden' => 'true' ] );
													endif;
													echo esc_html(get_the_date( "d M Y", $post->ID ));
													?>
												</span>
											<?php endif; ?>

											<?php if ( 'post' === $settings['post_type'] && 'yes' === $settings['category_meta'] ):
												$categories = get_the_category( $post->ID );
												?>
												<span class="skt-post-list-category">
												<?php if ( $settings['category_icon'] ):
													Icons_Manager::render_icon( $settings['category_icon'], [ 'aria-hidden' => 'true' ] );
												endif;
												echo esc_html( $categories[0]->name ); ?>
												</span>
											<?php endif; ?>

										</div>
									<?php endif; ?>
									<?php
									if ( 'top' === $settings['meta_position'] && $title ) {
										printf( '<%1$s %2$s>%3$s</%1$s>',
											skt_addons_elementor_escape_tags( $settings['title_tag'] ),
											'class="skt-post-list-title"',
											esc_html( $title )
										);
									}
									?>
									<?php if ( 'yes' === $settings['content'] ): ?>
										<div class="skt-post-list-excerpt">
											<?php
												if ( 'post' !== $settings['post_type'] && has_excerpt($post->ID) ) {
													printf('<p>%1$s</p>',
														wp_trim_words(get_the_excerpt($post->ID))
													);
												}else{
													printf('<p>%1$s</p>',
														wp_trim_words(get_the_content(null,false,$post->ID), 25, '.')
													);
												}
											?>
										</div>
									<?php endif; ?>
								</div>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
		else:
			printf( '%1$s %2$s %3$s',
				__( 'No ', 'skt-addons-elementor' ),
				esc_html( $settings['post_type'] ),
				__( 'Found', 'skt-addons-elementor' )
			);
		endif;
	}
}