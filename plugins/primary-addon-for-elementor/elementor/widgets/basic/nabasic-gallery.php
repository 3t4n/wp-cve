<?php
/*
 * Elementor Primary Addon for Elementor Gallery Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'pafe_bw_settings' )['napafe_gallery'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Primary_Addon_Gallery extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'prim_basic_gallery';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Gallery', 'primary-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-photo-library';
	}

	/**
	 * Retrieve the gallery of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['prim-basic-category'];
	}

	/**
	 * Register Primary_Addon Gallery widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_filter',
			[
				'label' => esc_html__( 'Filter Options', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'need_filter',
			[
				'label' => esc_html__( 'Need Filter?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$this->add_control(
			'gallery_all_filter',
			[
				'label' => esc_html__( 'Filter All Text', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'need_filter' => 'true',
				],
			]
		);
		$repeaterOne = new Repeater();
		$repeaterOne->add_control(
			'gallery_filter',
			[
				'label' => esc_html__( 'Filter Text', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$this->add_control(
			'GalleryFilter_groups',
			[
				'label' => esc_html__( 'Filters', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeaterOne->get_controls(),
				'title_field' => '{{{ gallery_filter }}}',
				'condition' => [
					'need_filter' => 'true',
				],
			]
		);
		$this->add_responsive_control(
			'filter_alignment',
			[
				'label' => esc_html__( 'Filter Alignment', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .masonry-filters' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'need_filter' => 'true',
				],
			]
		);
		$this->add_responsive_control(
			'dot_position',
			[
				'label' => esc_html__( 'Dot Position', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-up',
					],
					'left' => [
						'title' => esc_html__( 'Left', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-right',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-down',
					],
				],
				'default' => 'bottom',
				'condition' => [
					'need_filter' => 'true',
				],
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_gallery',
			[
				'label' => esc_html__( 'Gallery Options', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'gallery_item',
			[
				'label' => __( 'Default Items', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' 			=> esc_html__( 'Default', 'primary-addon-for-elementor' ),
					'two' 			=> esc_html__( 'Two', 'primary-addon-for-elementor' ),
					'three' 			=> esc_html__( 'Three', 'primary-addon-for-elementor' ),
					'four' 			=> esc_html__( 'Four', 'primary-addon-for-elementor' ),
				],
				'default' => 'none',
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'gallery_style',
			[
				'label' => __( 'Gallery Style', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' 					 => esc_html__( 'Style One', 'primary-addon-for-elementor' ),
					'two' 			 => esc_html__( 'Style Two', 'primary-addon-for-elementor' ),
				],
				'default' => 'one',
			]
		);
		$repeater->add_control(
			'filter_cat',
			[
				'label' => esc_html__( 'Filter Categories', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'placeholder' => 'one, two, three...',
				'description' => __( 'Enter your categories with comma(, ) separated. Dont use any special characters. <br><strong>EX: one, two, three...</strong>', 'primary-addon-for-elementor'),
			]
		);
		$repeater->add_control(
			'gallery_col',
			[
				'label' => __( 'Gallery Column', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' 					 => esc_html__( 'None', 'primary-addon-for-elementor' ),
					'one-half' 			 => esc_html__( 'One Half', 'primary-addon-for-elementor' ),
					'one-half-two'   => esc_html__( 'One Half Two', 'primary-addon-for-elementor' ),
					'one-third'  		 => esc_html__( 'One Third', 'primary-addon-for-elementor' ),
					'one-third-two'  => esc_html__( 'One Third Two', 'primary-addon-for-elementor' ),
					'one-fourth' 		 => esc_html__( 'One Fourth', 'primary-addon-for-elementor' ),
					'one-fourth-two' => esc_html__( 'One Fourth Two', 'primary-addon-for-elementor' ),
					'full-width' 		 => esc_html__( 'Full Width', 'primary-addon-for-elementor' ),
				],
				'default' => 'none',
			]
		);
		$repeater->add_control(
			'need_hover',
			[
				'label' => esc_html__( 'Need Image Hover?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$repeater->add_control(
			'need_popup',
			[
				'label' => esc_html__( 'Need Icon On Hover?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'condition' => [
					'gallery_style!' => 'two',
				],
			]
		);
		$repeater->add_control(
			'popup_icon',
			[
				'label' => esc_html__( 'Popup Icon', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAPAE_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-search',
				'condition' => [
					'need_popup' => 'true',
					'gallery_style!' => 'two',
				],
			]
		);
		$repeater->add_control(
			'pop_icon_style',
			[
				'label' => __( 'Icon Style', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' 			=> esc_html__( 'Image Popup', 'primary-addon-for-elementor' ),
					'two' 			=> esc_html__( 'Custom Link', 'primary-addon-for-elementor' ),
				],
				'default' => 'one',
				'condition' => [
					'need_popup' => 'true',
					'gallery_style!' => 'two',
				],
			]
		);
		$repeater->add_control(
			'icon_link',
			[
				'label' => esc_html__( 'Icon Link', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
				'condition' => [
					'pop_icon_style' => 'two',
					'gallery_style!' => 'two',
				],
			]
		);
		$repeater->add_control(
			'gallery_image',
			[
				'label' => esc_html__( 'Image', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'image_link',
			[
				'label' => esc_html__( 'Image Link', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'gallery_title',
			[
				'label' => esc_html__( 'Title', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Premium Food Recipe', 'primary-addon-for-elementor' ),
			]
		);
		$repeater->add_control(
			'gallery_link',
			[
				'label' => esc_html__( 'Title Link', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'gallery_subtitle',
			[
				'label' => esc_html__( 'Content', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'img_max_height',
			[
				'label' => esc_html__( 'Image Height', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
			]
		);
		$this->add_control(
			'GalleryItems_groups',
			[
				'label' => esc_html__( 'Gallery', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'gallery_title' => esc_html__( 'People First', 'primary-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ gallery_title }}}',
			]
		);
		$this->add_responsive_control(
			'info_alignment',
			[
				'label' => esc_html__( 'Info Alignment', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .gallery-info' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Style
		// Filter
			$this->start_controls_section(
				'section_filter_style',
				[
					'label' => esc_html__( 'Filter', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'frontend_available' => true,
					'condition' => [
						'need_filter' => 'true',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'filter_typography',
					'selector' => '{{WRAPPER}} .masonry-filters ul li a',
				]
			);
			$this->add_responsive_control(
				'filter_padding',
				[
					'label' => __( 'Filter Spacing', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .masonry-filters ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'dot_radius',
				[
					'label' => __( 'Dot Border Radius', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .masonry-filters ul li a:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'dot_width',
				[
					'label' => esc_html__( 'Dot Width', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .masonry-filters ul li a:after' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'dot_height',
				[
					'label' => esc_html__( 'Dot Height', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .masonry-filters ul li a:after' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'filter_style' );
				$this->start_controls_tab(
					'filter_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'filter_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .masonry-filters ul li a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'filter_active',
					[
						'label' => esc_html__( 'Active', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'filter_active_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .masonry-filters ul li a.active, {{WRAPPER}} .masonry-filters ul li a:hover' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'filter_active_border_color',
					[
						'label' => esc_html__( 'Dot Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .masonry-filters ul li a:after' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Active tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-gallery-item, {{WRAPPER}} .napae-gallery-item .napae-image, {{WRAPPER}} .napae-gallery-item .napae-image img, {{WRAPPER}} .gallery-info-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Section Spacing', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} ..napae-gallery-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'info_padding',
				[
					'label' => __( 'Info Spacing', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .gallery-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'scn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-gallery-item' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'lay_color',
				[
					'label' => esc_html__( 'Icon Overlay Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-gallery-item .napae-image.hav-popup:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'info_bg_color',
				[
					'label' => esc_html__( 'Info Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .gallery-info-wrap .gallery-info' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->start_controls_tabs( 'secn_style' );
				$this->start_controls_tab(
					'secn_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .napae-gallery-item',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .napae-gallery-item',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'secn_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_hover_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .napae-gallery-item.napae-hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_hover_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .napae-gallery-item.napae-hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs

			$this->end_controls_section();// end: Section

		// Icon
			$this->start_controls_section(
				'section_icon_style',
				[
					'label' => esc_html__( 'Icon', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'need_popup' => 'true',
					],
				]
			);
			$this->add_control(
				'icon_border_radius',
				[
					'label' => __( 'Border Radius', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-popup a.pp-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_width',
				[
					'label' => esc_html__( 'Icon Width/Height', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-popup a.pp-icon' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_size',
				[
					'label' => esc_html__( 'Icon Size', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 500,
							'step' => 1,
						],
					],
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-popup a.pp-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'icon_style' );
				$this->start_controls_tab(
					'ico_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'icon_color',
					[
						'label' => esc_html__( 'Icon Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-popup a.pp-icon' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_bgcolor',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-popup a.pp-icon' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'ico_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'icon_hover_color',
					[
						'label' => esc_html__( 'Icon Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-popup a.pp-icon:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_hover_bgcolor',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-popup a.pp-icon:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
					'name' => 'sasstp_title_typography',
					'selector' => '{{WRAPPER}} .gallery-info h2',
				]
			);
			$this->start_controls_tabs( 'title_style' );
				$this->start_controls_tab(
					'title_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .gallery-info h2, {{WRAPPER}} .gallery-info h2 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'title_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_hover_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .gallery-info h2 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_text_subtitle_style',
				[
					'label' => esc_html__( 'Content', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'gallery_subtitle_typography',
					'selector' => '{{WRAPPER}} .gallery-info p',
				]
			);
			$this->add_control(
				'gallery_subtitle_color',
				[
					'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .gallery-info p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Gallery widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$GalleryFilter_groups = !empty( $settings['GalleryFilter_groups'] ) ? $settings['GalleryFilter_groups'] : [];
		$GalleryItems_groups = !empty( $settings['GalleryItems_groups'] ) ? $settings['GalleryItems_groups'] : [];
		$need_filter = !empty( $settings['need_filter'] ) ? $settings['need_filter'] : '';
		$dot_position = !empty( $settings['dot_position'] ) ? $settings['dot_position'] : '';
		$gallery_all_filter = !empty( $settings['gallery_all_filter'] ) ? $settings['gallery_all_filter'] : [];
		$gallery_item = !empty( $settings['gallery_item'] ) ? $settings['gallery_item'] : '';

		$gallery_all_filter = $gallery_all_filter ? $gallery_all_filter : esc_html( 'All', 'primary-addon-for-elementor' );

		if ($dot_position === 'top'){
		  $dot_cls = ' dot-top';
		} elseif ($dot_position === 'left'){
		  $dot_cls = ' dot-left';
		} elseif ($dot_position === 'right'){
		  $dot_cls = ' dot-right';
		} else {
		  $dot_cls = '';
		}

		if ($gallery_item === 'three'){
		  $items_cls = ' data-items="3"';
		} elseif ($gallery_item === 'four'){
		  $items_cls = ' data-items="4"';
		} else {
		  $items_cls = '';
		}

	  $output = '<div class="napae-gallery"><div class="masonry-wrap">';
	  if ($need_filter) {
		$output .= '<div class="masonry-filters'.esc_attr($dot_cls).'">
			            <ul>
			              <li><a href="javascript:void(0);" data-filter="*" class="active">'.ucwords($gallery_all_filter).'</a></li>';

									  // Group Param Output
										if ( is_array( $GalleryFilter_groups ) && !empty( $GalleryFilter_groups ) ){
										  foreach ( $GalleryFilter_groups as $each_filter ) {

												$gallery_filter = $each_filter['gallery_filter'] ? $each_filter['gallery_filter'] : '';
												$filter = $gallery_filter ? '<li><a href="javascript:void(0);" data-filter=".'. preg_replace('/\s+/', "", strtolower($gallery_filter)) .'">'.ucwords($gallery_filter).'</a></li>' : '';
											  $output .= $filter;
										  }
										}
			$output .= '</ul>
	          	</div>';
	  }
		$output .= '<div class="napae-masonry"'.$items_cls.'>';
		// Group Param Output
		if ( is_array( $GalleryItems_groups ) && !empty( $GalleryItems_groups ) ){
		  foreach ( $GalleryItems_groups as $each_value ) {

				$height = $each_value['img_max_height']['size'] ? $each_value['img_max_height']['size'] : '';
				$unit = $each_value['img_max_height']['unit'] ? $each_value['img_max_height']['unit'] : '';
				$gallery_style = $each_value['gallery_style'] ? $each_value['gallery_style'] : '';
				$filter_cat = $each_value['filter_cat'] ? $each_value['filter_cat'] : '';
				$gallery_col = !empty( $each_value['gallery_col'] ) ? $each_value['gallery_col'] : [];
				$gallery_image = !empty( $each_value['gallery_image']['id'] ) ? $each_value['gallery_image']['id'] : '';
				$image_link = !empty( $each_value['image_link']['url'] ) ? $each_value['image_link']['url'] : '';
				$image_link_external = !empty( $each_value['image_link']['is_external'] ) ? 'target="_blank"' : '';
				$image_link_nofollow = !empty( $each_value['image_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$image_link_attr = !empty( $image_link ) ?  $image_link_external.' '.$image_link_nofollow : '';

				$gallery_title = $each_value['gallery_title'] ? $each_value['gallery_title'] : '';
				$gallery_link = !empty( $each_value['gallery_link']['url'] ) ? $each_value['gallery_link']['url'] : '';
				$gallery_link_external = !empty( $each_value['gallery_link']['is_external'] ) ? 'target="_blank"' : '';
				$gallery_link_nofollow = !empty( $each_value['gallery_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$gallery_link_attr = !empty( $gallery_link ) ?  $gallery_link_external.' '.$gallery_link_nofollow : '';

				$gallery_subtitle = $each_value['gallery_subtitle'] ? $each_value['gallery_subtitle'] : '';

				$need_hover = !empty( $each_value['need_hover'] ) ? $each_value['need_hover'] : '';
				$need_popup = !empty( $each_value['need_popup'] ) ? $each_value['need_popup'] : '';
				$popup_icon = !empty( $each_value['popup_icon'] ) ? $each_value['popup_icon'] : '';
				$pop_icon_style = !empty( $each_value['pop_icon_style'] ) ? $each_value['pop_icon_style'] : '';
				$icon_link = !empty( $each_value['icon_link']['url'] ) ? $each_value['icon_link']['url'] : '';
				$icon_link_external = !empty( $each_value['icon_link']['is_external'] ) ? 'target="_blank"' : '';
				$icon_link_nofollow = !empty( $each_value['icon_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$icon_link_attr = !empty( $icon_link ) ?  $icon_link_external.' '.$icon_link_nofollow : '';

				if ($gallery_style === 'two') {
					$style_class = ' gal-style';
				} else {
					$style_class = '';
				}

				if ($need_hover) {
					$hover_class = ' zoom-image';
				} else {
					$hover_class = '';
				}

				if ($need_popup) {
					if ($pop_icon_style === 'two') {
						$popup_class = ' hav-popup';
					} else {
						$popup_class = ' hav-popup napae-popup';
					}
				} else {
					$popup_class = '';
				}

				$image_url = wp_get_attachment_url( $gallery_image );

				$subtitle = $gallery_subtitle ? '<p>'.esc_html($gallery_subtitle).'</p>' : '';

				$link = $gallery_link ? '<a href="'.esc_url($gallery_link).'" '.$gallery_link_attr.'>'.esc_html($gallery_title).'</a>' : $gallery_title;
				$title = $gallery_title ? '<h2 class="gallery-title">'.$link.'</h2>' : '';

				$icon = $popup_icon ? '<i class="'.esc_attr($popup_icon).'" aria-hidden="true"></i>' : '';

				$image_pop = ($need_popup && $image_url) ? '<a href="'. esc_url($image_url) .'" class="pp-icon">'.$icon.'</a>' : '';
				$icon_link = $icon_link ? '<a href="'.esc_url($icon_link).'" '.$icon_link_attr.' class="pp-icon">'.$icon.'</a>' : '';

				if ($pop_icon_style === 'two') {
					$icon_popup = $icon_link;
				} else {
					$icon_popup = $image_pop;
				}

				$link_image = $image_link ? '<a href="'.esc_url($image_link).'" '.$image_link_attr.'><img src="'.esc_url($image_url).'" alt="'.esc_attr($gallery_title).'"></a>' : '<img src="'.esc_url($image_url).'" alt="'.esc_attr($gallery_title).'">';

				$max_height = $height ? ' style="max-height: '.$height.$unit.';"' : '';

				$image = $image_url ? '<div class="napae-image'.esc_attr($popup_class).'"'.$max_height.'>'.$link_image.$icon_popup.'</div>' : '';

				$category = $filter_cat ? ' data-category="'. str_replace(', ', " ", strtolower($filter_cat)) .'"' : '';
				$category_class = $filter_cat ? ' '.str_replace(', ', " ", strtolower($filter_cat)) : '';

			  $output .= '<div class="masonry-item'.$category_class.' '.$gallery_col.'"'.$category.'>
			  							<div class="napae-gallery-item'.$hover_class.$style_class.'">';
			  							if ($gallery_style === 'two') {$output .= '<div class="gallery-info-wrap">';}
		  									$output .= $image.'
			  								<div class="gallery-info">'.$title.$subtitle.'</div>';
			  							if ($gallery_style === 'two') {$output .= '</div>';}
					$output .= '</div>
		  							</div>';
		  }
		}

		$output .= '</div></div></div>';

		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Primary_Addon_Gallery() );

} // enable & disable
