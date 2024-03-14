<?php
/*
 * Elementor Medical Addon for Elementor Gallery Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( !is_plugin_active( 'medical-addon-for-elementor-pro/medical-addon-for-elementor-pro.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Medical_Elementor_Addon_Gallery extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'namedical_basic_gallery';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Gallery', 'medical-addon-for-elementor' );
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
			return ['namedical-basic-category'];
		}

		/**
		 * Register Medical_Elementor_Addon Gallery widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function register_controls(){

			$this->start_controls_section(
				'section_filter',
				[
					'label' => esc_html__( 'Filter Options', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'need_filter',
				[
					'label' => esc_html__( 'Need Filter?', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'medical-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'medical-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'gallery_all_filter',
				[
					'label' => esc_html__( 'Filter All Text', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Filter Text', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
				]
			);
			$this->add_control(
				'GalleryFilter_groups',
				[
					'label' => esc_html__( 'Filters', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Filter Alignment', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Dot Position', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'top' => [
							'title' => esc_html__( 'Top', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-arrow-circle-up',
						],
						'left' => [
							'title' => esc_html__( 'Left', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-arrow-circle-left',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-arrow-circle-right',
						],
						'bottom' => [
							'title' => esc_html__( 'Bottom', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Gallery Options', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'gallery_item',
				[
					'label' => __( 'Default Items', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'none' 			=> esc_html__( 'Default', 'medical-addon-for-elementor' ),
						'two' 			=> esc_html__( 'Two', 'medical-addon-for-elementor' ),
						'three' 			=> esc_html__( 'Three', 'medical-addon-for-elementor' ),
						'four' 			=> esc_html__( 'Four', 'medical-addon-for-elementor' ),
					],
					'default' => 'none',
				]
			);
			$this->add_responsive_control(
				'icon_position',
				[
					'label' => esc_html__( 'Icon Position', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'multiple' => true,
					'options' => [
						'top' => [
							'title' => esc_html__( 'Top', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-arrow-circle-up',
						],
						'middle' => [
							'title' => esc_html__( 'Middle', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-circle',
						],
						'bottom' => [
							'title' => esc_html__( 'Bottom', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-arrow-circle-down',
						],
					],
					'default' => 'top',
				]
			);
			$this->add_responsive_control(
				'icon_alignment',
				[
					'label' => esc_html__( 'Icon Alignment', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'right',
				]
			);
			$repeater = new Repeater();
			$repeater->add_control(
				'filter_cat',
				[
					'label' => esc_html__( 'Filter Categories', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXTAREA,
					'label_block' => true,
					'placeholder' => 'one, two, three...',
					'description' => __( 'Enter your categories with comma(, ) separated. Dont use any special characters. <br><strong>EX: one, two, three...</strong>', 'medical-addon-for-elementor'),
				]
			);
			$repeater->add_control(
				'gallery_col',
				[
					'label' => __( 'Gallery Column', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'none' 					 => esc_html__( 'None', 'medical-addon-for-elementor' ),
						'one-half' 			 => esc_html__( 'One Half', 'medical-addon-for-elementor' ),
						'one-half-two'   => esc_html__( 'One Half Two', 'medical-addon-for-elementor' ),
						'one-third'  		 => esc_html__( 'One Third', 'medical-addon-for-elementor' ),
						'one-third-two'  => esc_html__( 'One Third Two', 'medical-addon-for-elementor' ),
						'one-fourth' 		 => esc_html__( 'One Fourth', 'medical-addon-for-elementor' ),
						'one-fourth-two' => esc_html__( 'One Fourth Two', 'medical-addon-for-elementor' ),
						'full-width' 		 => esc_html__( 'Full Width', 'medical-addon-for-elementor' ),
					],
					'default' => 'none',
				]
			);
			$repeater->add_control(
				'info_style',
				[
					'label' => __( 'Info Style', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'one' 			=> esc_html__( 'Inside', 'medical-addon-for-elementor' ),
						'two' 			=> esc_html__( 'Outside', 'medical-addon-for-elementor' ),
					],
					'default' => 'one',
				]
			);
			$repeater->add_control(
				'need_hover',
				[
					'label' => esc_html__( 'Need Image Hover?', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'medical-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'medical-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$repeater->add_control(
				'need_popup',
				[
					'label' => esc_html__( 'Need Icon On Hover?', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'medical-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'medical-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$repeater->add_control(
				'popup_icon',
				[
					'label' => esc_html__( 'Popup Icon', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::ICON,
					'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
					'default' => 'fa fa-search',
					'condition' => [
						'need_popup' => 'true',
					],
				]
			);
			$repeater->add_control(
				'pop_icon_style',
				[
					'label' => __( 'Icon Style', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'one' 			=> esc_html__( 'Image Popup', 'medical-addon-for-elementor' ),
						'two' 			=> esc_html__( 'Custom Link', 'medical-addon-for-elementor' ),
					],
					'default' => 'one',
					'condition' => [
						'need_popup' => 'true',
					],
				]
			);
			$repeater->add_control(
				'icon_link',
				[
					'label' => esc_html__( 'Icon Link', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::URL,
					'placeholder' => 'https://your-link.com',
					'default' => [
						'url' => '',
					],
					'label_block' => true,
					'condition' => [
						'pop_icon_style' => 'two',
					],
				]
			);
			$repeater->add_control(
				'gallery_image',
				[
					'label' => esc_html__( 'Image', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Image Link', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'default' => esc_html__( 'People First', 'medical-addon-for-elementor' ),
				]
			);
			$repeater->add_control(
				'gallery_link',
				[
					'label' => esc_html__( 'Title Link', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Sub Title', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::TEXTAREA,
					'label_block' => true,
				]
			);
			$repeater->add_control(
				'img_max_height',
				[
					'label' => esc_html__( 'Image Height', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Gallery', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::REPEATER,
					'default' => [
						[
							'gallery_title' => esc_html__( 'People First', 'medical-addon-for-elementor' ),
						],

					],
					'fields' => $repeater->get_controls(),
					'title_field' => '{{{ gallery_title }}}',
				]
			);
			$this->add_responsive_control(
				'info_position',
				[
					'label' => esc_html__( 'Info Position', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'multiple' => true,
					'options' => [
						'top' => [
							'title' => esc_html__( 'Top', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-arrow-circle-up',
						],
						'none' => [
							'title' => esc_html__( 'Middle', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-circle',
						],
						'bottom' => [
							'title' => esc_html__( 'Bottom', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-arrow-circle-down',
						],
					],
					'default' => 'none',
				]
			);
			$this->add_responsive_control(
				'info_alignment',
				[
					'label' => esc_html__( 'Info Alignment', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'medical-addon-for-elementor' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Filter', 'medical-addon-for-elementor' ),
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
					'label' => __( 'Filter Spacing', 'medical-addon-for-elementor' ),
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
					'label' => __( 'Dot Border Radius', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Dot Width', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Dot Height', 'medical-addon-for-elementor' ),
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
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'filter_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
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
						'label' => esc_html__( 'Active', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'filter_active_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .masonry-filters ul li a.active, {{WRAPPER}} .masonry-filters ul li a:hover' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'filter_active_border_color',
					[
						'label' => esc_html__( 'Dot Color', 'medical-addon-for-elementor' ),
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
					'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-gallery-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .namep-gallery-item .namep-image, {{WRAPPER}} .namep-gallery-item .namep-image:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					],
				]
			);
			$this->add_responsive_control(
				'info_padding',
				[
					'label' => __( 'Info Spacing', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .gallery-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'info_bg_color',
				[
					'label' => esc_html__( 'Info Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .style-two .gallery-info' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->start_controls_tabs( 'secn_style' );
				$this->start_controls_tab(
					'secn_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-gallery-item',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-gallery-item',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'secn_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'secn_bg_hover_color',
					[
						'label' => esc_html__( 'Overlay Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-gallery-item .namep-image:after' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'secn_hover_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-gallery-item.namep-hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'secn_hover_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-gallery-item.namep-hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs

			$this->end_controls_section();// end: Section

			// Icon
			$this->start_controls_section(
				'section_icon_style',
				[
					'label' => esc_html__( 'Icon', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'need_popup' => 'true',
					],
				]
			);
			$this->add_control(
				'icon_border_radius',
				[
					'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-popup a.pp-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_width',
				[
					'label' => esc_html__( 'Icon Width/Height', 'medical-addon-for-elementor' ),
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
						'{{WRAPPER}} .namep-popup a.pp-icon' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_size',
				[
					'label' => esc_html__( 'Icon Size', 'medical-addon-for-elementor' ),
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
						'{{WRAPPER}} .namep-popup a.pp-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'icon_style' );
				$this->start_controls_tab(
					'ico_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'icon_color',
					[
						'label' => esc_html__( 'Icon Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-popup a.pp-icon' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_bgcolor',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-popup a.pp-icon' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'ico_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'icon_hover_color',
					[
						'label' => esc_html__( 'Icon Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-popup a.pp-icon:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_hover_bgcolor',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-popup a.pp-icon:hover' => 'background-color: {{VALUE}};',
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
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sasstp_title_typography',
					'selector' => '{{WRAPPER}} .gallery-info h2',
				]
			);
			$this->start_controls_tabs( 'title_style' );
				$this->start_controls_tab(
					'title_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
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
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_hover_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .gallery-info h2 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

			$this->start_controls_section(
				'section_text_subtitle_style',
				[
					'label' => esc_html__( 'Sub Title', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'gallery_subtitle_typography',
					'selector' => '{{WRAPPER}} .gallery-subtitle',
				]
			);
			$this->add_control(
				'gallery_subtitle_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .gallery-subtitle' => 'color: {{VALUE}};',
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
			$info_position = !empty( $settings['info_position'] ) ? $settings['info_position'] : '';
			$icon_position = !empty( $settings['icon_position'] ) ? $settings['icon_position'] : '';
			$icon_alignment = !empty( $settings['icon_alignment'] ) ? $settings['icon_alignment'] : '';
			$gallery_item = !empty( $settings['gallery_item'] ) ? $settings['gallery_item'] : '';

			$gallery_all_filter = $gallery_all_filter ? $gallery_all_filter : esc_html( 'All', 'medical-addon-for-elementor' );

			if ($dot_position === 'top'){
			  $dot_cls = ' dot-top';
			} elseif ($dot_position === 'left'){
			  $dot_cls = ' dot-left';
			} elseif ($dot_position === 'right'){
			  $dot_cls = ' dot-right';
			} else {
			  $dot_cls = '';
			}

			if ($info_position === 'top'){
			  $info_cls = ' info-top';
			} elseif ($info_position === 'bottom'){
			  $info_cls = ' info-bottom';
			} else {
			  $info_cls = '';
			}

			if ($icon_position === 'bottom') {
			  $icon_cls = ' icon-bottom';
			} elseif ($icon_position === 'top') {
			  $icon_cls = ' icon-top';
			} else {
			  $icon_cls = '';
			}

			if ($icon_alignment === 'left'){
			  $iconalign_cls = ' iconalign-left';
			} elseif ($icon_alignment === 'center'){
			  $iconalign_cls = ' iconalign-center';
			} else {
			  $iconalign_cls = '';
			}

			if ($gallery_item === 'three'){
			  $items_cls = ' data-items="3"';
			} elseif ($gallery_item === 'four'){
			  $items_cls = ' data-items="4"';
			} else {
			  $items_cls = '';
			}

		  $output = '<div class="namep-gallery"><div class="masonry-wrap">';
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
			$output .= '<div class="namep-masonry"'.$items_cls.'>';
			// Group Param Output
			if ( is_array( $GalleryItems_groups ) && !empty( $GalleryItems_groups ) ){
			  foreach ( $GalleryItems_groups as $each_value ) {

					$height = $each_value['img_max_height']['size'] ? $each_value['img_max_height']['size'] : '';
					$unit = $each_value['img_max_height']['unit'] ? $each_value['img_max_height']['unit'] : '';
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

					$info_style = !empty( $each_value['info_style'] ) ? $each_value['info_style'] : '';
					$need_hover = !empty( $each_value['need_hover'] ) ? $each_value['need_hover'] : '';
					$need_popup = !empty( $each_value['need_popup'] ) ? $each_value['need_popup'] : '';
					$popup_icon = !empty( $each_value['popup_icon'] ) ? $each_value['popup_icon'] : '';
					$pop_icon_style = !empty( $each_value['pop_icon_style'] ) ? $each_value['pop_icon_style'] : '';
					$icon_link = !empty( $each_value['icon_link']['url'] ) ? $each_value['icon_link']['url'] : '';
					$icon_link_external = !empty( $each_value['icon_link']['is_external'] ) ? 'target="_blank"' : '';
					$icon_link_nofollow = !empty( $each_value['icon_link']['nofollow'] ) ? 'rel="nofollow"' : '';
					$icon_link_attr = !empty( $icon_link ) ?  $icon_link_external.' '.$icon_link_nofollow : '';

					if ($info_style === 'two') {
						$style_class = ' style-two';
					} else {
						$style_class = ' style-one';
					}

					if ($need_hover) {
						$hover_class = ' zoom-image';
					} else {
						$hover_class = '';
					}

					if ($need_popup) {
						$popup_class = ' namep-popup';
					} else {
						$popup_class = '';
					}

					$image_url = wp_get_attachment_url( $gallery_image );

					$subtitle = $gallery_subtitle ? '<span class="gallery-subtitle">'.esc_html($gallery_subtitle).'</span>' : '';

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

					$image = $image_url ? '<div class="namep-image'.esc_attr($popup_class).'"'.$max_height.'>'.$link_image.$icon_popup.'</div>' : '';

					$category = $filter_cat ? ' data-category="'. str_replace(', ', " ", strtolower($filter_cat)) .'"' : '';
					$category_class = $filter_cat ? ' '.str_replace(', ', " ", strtolower($filter_cat)) : '';

				  $output .= '<div class="masonry-item'.$category_class.' '.$gallery_col.'"'.$category.'>
				  							<div class="namep-gallery-item'.$hover_class.$info_cls.$style_class.$icon_cls.$iconalign_cls.'">
				  								'.$image.'
				  								<div class="gallery-info">'.$title.$subtitle.'</div>
				  							</div>
			  							</div>';
			  }
			}

			$output .= '</div></div></div>';

			echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Gallery() );
}
