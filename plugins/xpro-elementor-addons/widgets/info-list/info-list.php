<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use XproElementorAddons\Control\Xpro_Elementor_Group_Control_Foreground;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.t
 *
 * @since 1.0.0
 */
class Info_List extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-infolist';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Info List', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-info-list xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'infolist', 'iconlist', 'icon', 'list' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_infolist',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'          => __( 'Layout', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::CHOOSE,
				'default'        => 'vertical',
				'options'        => array(
					'vertical'   => array(
						'title' => __( 'Vertical', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'horizontal' => array(
						'title' => __( 'Horizontal', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'style_transfer' => true,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'media_type',
			array(
				'label'       => __( 'Media Type', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'none'   => array(
						'title' => __( 'None', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ban',
					),
					'icon'   => array(
						'title' => __( 'Icon', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-star-o',
					),
					'image'  => array(
						'title' => __( 'Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-image',
					),
					'custom' => array(
						'title' => __( 'Custom', 'xpro-elementor-addons' ),
						'icon'  => ' eicon-font',
					),
				),
				'default'     => 'icon',
				'toggle'      => false,
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'     => __( 'Icon', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-fingerprint',
					'library' => 'solid',
				),
				'condition' => array(
					'media_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label'     => __( 'Image', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'media_type' => 'image',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'default'   => 'thumbnail',
				'exclude'   => array(
					'custom',
				),
				'condition' => array(
					'media_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'custom',
			array(
				'label'       => __( 'Custom', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( '01', 'xpro-elementor-addons' ),
				'label_block' => false,
				'condition'   => array(
					'media_type' => 'custom',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'media_icon_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'default' => __( 'Default', 'xpro-elementor-addons' ),
					'custom'  => __( 'Custom', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'media_type' => array( 'icon', 'custom' ),
				),
			)
		);

		$repeater->start_controls_tabs(
			'media_tabs',
			array(
				'condition' => array(
					'media_type'       => array( 'icon', 'custom' ),
					'media_icon_color' => array( 'custom' ),
				),
			)
		);

		$repeater->start_controls_tab(
			'media_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'media_color',
			array(
				'label'     => __( 'Content Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-infolist-media-type-icon,{{WRAPPER}} {{CURRENT_ITEM}} .xpro-infolist-media-type-custom' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-infolist-media-type-icon > svg'                                                         => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'media_type'       => array( 'icon', 'custom' ),
					'media_icon_color' => array( 'custom' ),
				),
			)
		);

		$repeater->add_control(
			'media_bgcolor',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-infolist-media' => 'background: {{VALUE}}',
				),
				'condition' => array(
					'media_type'       => array( 'icon', 'custom' ),
					'media_icon_color' => array( 'custom' ),
				),
			)
		);

		$repeater->add_control(
			'media_boder_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-infolist-media' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'media_type'       => array( 'icon', 'custom' ),
					'media_icon_color' => array( 'custom' ),
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'media_hover_tab',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'media_hcolor',
			array(
				'label'     => __( 'Content Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.xpro-infolist-item:hover .xpro-infolist-media-type-icon,{{WRAPPER}} {{CURRENT_ITEM}}.xpro-infolist-item:hover .xpro-infolist-media-type-custom' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}}.xpro-infolist-item:hover .xpro-infolist-media-type-icon > svg'                                                                                  => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'media_type'       => array( 'icon', 'custom' ),
					'media_icon_color' => array( 'custom' ),
				),
			)
		);

		$repeater->add_control(
			'media_hbgcolor',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.xpro-infolist-item:hover .xpro-infolist-media' => 'background: {{VALUE}}',
				),
				'condition' => array(
					'media_type'       => array( 'icon', 'custom' ),
					'media_icon_color' => array( 'custom' ),
				),
			)
		);

		$repeater->add_control(
			'media_boder_hcolor',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.xpro-infolist-item:hover .xpro-infolist-media' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'media_type'       => array( 'icon', 'custom' ),
					'media_icon_color' => array( 'custom' ),
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$repeater->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'List Title Here', 'xpro-elementor-addons' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'title_tag',
			array(
				'label'     => esc_html__( 'Title HTML Tag', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
				'default'   => 'h2',
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'       => __( 'Description', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'List description here', 'xpro-elementor-addons' ),
				'placeholder' => __( 'Type your description here', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => __( 'https://your-link.com', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'item',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'show_label'  => false,
				'title_field' => sprintf(
				/* translators: %s: Title */
					__( 'Item: %1$s', 'xpro-elementor-addons' ),
					'{{title}}'
				),
				'default'     => array(
					array(
						'icon'  => array(
							'value'   => 'fas fa-check',
							'library' => 'fa-solid',
						),
						'title' => __( 'List Title 1', 'xpro-elementor-addons' ),
					),
					array(
						'icon'  => array(
							'value'   => 'fas fa-check',
							'library' => 'fa-solid',
						),
						'title' => __( 'List Title 2', 'xpro-elementor-addons' ),
					),
					array(
						'icon'  => array(
							'value'   => 'fas fa-check',
							'library' => 'fa-solid',
						),
						'title' => __( 'List Title 3', 'xpro-elementor-addons' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_infolist_style',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'list_align',
			array(
				'label'        => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'toggle'       => false,
				'default'      => 'left',
				'prefix_class' => 'elementor%s-align-',
			)
		);

		$this->add_responsive_control(
			'list_item_per_row',
			array(
				'label'          => __( 'Item Per Row', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'render_type'    => 'template',
				'range'          => array(
					'px' => array(
						'min'  => 2,
						'max'  => 6,
						'step' => 1,
					),
				),
				'default'        => array(
					'size' => 3,
				),
				'tablet_default' => array(
					'size' => 2,
				),
				'mobile_default' => array(
					'size' => 1,
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro-infolist-layout-horizontal .xpro-infolist-item' => '--xpro-grid-item:{{SIZE}}',
				),
				'condition'      => array(
					'layout' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'list_item_space',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-layout-horizontal .xpro-infolist-item' => 'width:calc(100%/var(--xpro-grid-item) - {{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .xpro-infolist-layout-horizontal' => 'column-gap: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'list_item_space_bottom',
			array(
				'label'     => __( 'Space Bottom', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-infolist-layout-vertical'                         => 'row-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-infolist-layout-horizontal'                       => 'row-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-infolist-media-type-icon::before'                 => '--xpro-speparator-line: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'list_item_box_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-item',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'list_item_bg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-item',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'list_item_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-item',
			)
		);

		$this->add_responsive_control(
			'list_item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_item_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_infolist_media_style',
			array(
				'label' => __( 'Media', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'media_valign',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Top', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => __( 'Middle', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => __( 'Bottom', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .xpro-infolist-item,{{WRAPPER}} .xpro-infolist-item > a' => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'media_tabs' );

		$this->start_controls_tab(
			'media_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'media_item_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-infolist-media-type-icon,{{WRAPPER}} .xpro-infolist-media-type-custom' => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-infolist-media-type-icon > svg'                                        => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'media_item_bg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-media',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'media_hover_tab',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'media_item_hcolor',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-infolist-item:hover .xpro-infolist-media-type-icon,{{WRAPPER}} .xpro-infolist-item:hover .xpro-infolist-media-type-custom' => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-infolist-item:hover .xpro-infolist-media-type-icon > svg'                                                                  => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'media_item_hbg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-item:hover .xpro-infolist-media',
			)
		);

		$this->add_control(
			'media_item_hborder',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-infolist-item:hover .xpro-infolist-media ' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'media_item_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'media_item_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-media',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'media_item_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-media',
			)
		);

		$this->add_responsive_control(
			'media_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'media_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'media_icon_heading',
			array(
				'label'     => __( 'Icon', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'media_icon_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-media-type-icon'       => 'font-size: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-infolist-media-type-icon > svg' => 'width: {{SIZE}}{{UNIT}}; height:auto;',
				),
				'default'    => array(
					'size' => 12,
				),
			)
		);

		$this->add_responsive_control(
			'media_icon_bgsize',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-media-type-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
				'default'    => array(
					'size' => 25,
				),
			)
		);

		$this->add_control(
			'media_icon_separator',
			array(
				'label'        => __( 'Separator', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'block',
				'selectors'    => array(
					'{{WRAPPER}} .xpro-infolist-media-type-icon::before' => 'display:{{VALUE}};',
				),
				'condition'    => array(
					'layout' => array( 'vertical' ),
				),
			)
		);

		$this->add_control(
			'media_icon_separator_style',
			array(
				'label'     => __( 'Separator Style', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => __( 'Solid', 'xpro-elementor-addons' ),
					'dashed' => __( 'Dashed', 'xpro-elementor-addons' ),
					'dotted' => __( 'Dotted', 'xpro-elementor-addons' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-infolist-media-type-icon::before' => 'border-left-style: {{VALUE}};',
				),
				'condition' => array(
					'layout'               => array( 'vertical' ),
					'media_icon_separator' => array( 'block' ),
				),
			)
		);

		$this->add_responsive_control(
			'media_icon_separator_width',
			array(
				'label'      => __( 'Separator Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 2,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-media-type-icon::before' => 'border-left-width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout'               => array( 'vertical' ),
					'media_icon_separator' => array( 'block' ),
				),
			)
		);

		$this->add_responsive_control(
			'media_icon_separator_height',
			array(
				'label'      => __( 'Separator Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-media-type-icon::before' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout'               => array( 'vertical' ),
					'media_icon_separator' => array( 'block' ),
				),
			)
		);

		$this->add_control(
			'media_icon_separator_color',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-infolist-media-type-icon::before' => 'border-left-color: {{VALUE}}',
				),
				'condition' => array(
					'layout'               => array( 'vertical' ),
					'media_icon_separator' => array( 'block' ),
				),
			)
		);

		$this->add_control(
			'media_image_heading',
			array(
				'label'     => __( 'Image', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'media_image_size',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-media-type-image img' => 'width: {{SIZE}}{{UNIT}};',
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'          => __( 'Height', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'vh' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro-infolist-media-type-image img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'object-fit',
			array(
				'label'     => __( 'Object Fit', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''        => __( 'Default', 'xpro-elementor-addons' ),
					'fill'    => __( 'Fill', 'xpro-elementor-addons' ),
					'cover'   => __( 'Cover', 'xpro-elementor-addons' ),
					'contain' => __( 'Contain', 'xpro-elementor-addons' ),
				),
				'default'   => '',
				'condition' => array(
					'image_height[size]!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-infolist-media-type-image img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'media_custom_heading',
			array(
				'label'     => __( 'Custom', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'media_custom_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-media-type-custom',
			)
		);

		$this->add_responsive_control(
			'media_custom_bg_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-media-type-custom' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'media_custom_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-media-type-custom' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_infolist_title_style',
			array(
				'label' => __( 'Title', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-title',
			)
		);

		$this->start_controls_tabs( '_tabs_title' );

		// Start Info List Title Color And Hover
		$this->start_controls_tab(
			'_tab_title_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Xpro_Elementor_Group_Control_Foreground::get_type(),
			array(
				'name'     => 'title_gradient',
				'label'    => __( 'Title Color', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-title',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_title_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Xpro_Elementor_Group_Control_Foreground::get_type(),
			array(
				'name'     => 'title_hover_gradient',
				'label'    => __( 'Title Color', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-item:hover .xpro-infolist-title',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		// End Info List Title Color And Hover

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_infolist_desc_style',
			array(
				'label' => __( 'Description', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-desc',
			)
		);

		// Start Info List Description Text Color And Hover
		$this->start_controls_tabs( '_tabs_description' );

		$this->start_controls_tab(
			'_tab__normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Xpro_Elementor_Group_Control_Foreground::get_type(),
			array(
				'name'     => 'description_gradient',
				'label'    => __( 'Description Color', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-desc',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_description_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Xpro_Elementor_Group_Control_Foreground::get_type(),
			array(
				'name'     => 'description_hover_gradient',
				'label'    => __( 'Description Color', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .xpro-infolist-item:hover .xpro-infolist-desc',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		// End Info List Description Text Color And Hover

		$this->add_responsive_control(
			'desc_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-infolist-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'info-list/layout/frontend.php';
	}
}
