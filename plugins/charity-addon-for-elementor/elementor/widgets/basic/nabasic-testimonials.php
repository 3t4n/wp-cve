<?php
/*
 * Elementor Charity Addon for Elementor Testimonials Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( !is_plugin_active( 'charity-addon-for-elementor-pro/charity-addon-for-elementor-pro.php' ) ) {

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Charity_Elementor_Addon_Testimonials extends Widget_Base{

		/**
		 * Retrieve the widget name.
		*/
		public function get_name(){
			return 'nacharity_basic_testimonials';
		}

		/**
		 * Retrieve the widget title.
		*/
		public function get_title(){
			return esc_html__( 'Testimonials', 'charity-addon-for-elementor' );
		}

		/**
		 * Retrieve the widget icon.
		*/
		public function get_icon() {
			return 'eicon-testimonial';
		}

		/**
		 * Retrieve the list of categories the widget belongs to.
		*/
		public function get_categories() {
			return ['nacharity-basic-category'];
		}

		/**
		 * Register Charity Addon for Elementor Testimonials widget controls.
		 * Adds different input fields to allow the user to change and customize the widget settings.
		*/
		protected function _register_controls(){

			$this->start_controls_section(
				'section_testimonials',
				[
					'label' => __( 'Testimonials Item', 'charity-addon-for-elementor' ),
				]
			);

			$this->add_control(
				'center_item',
				[
					'label' => esc_html__( 'Need All Center?', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
					'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
					'return_value' => 'true',
				]
			);
			$this->add_responsive_control(
				'info_position',
				[
					'label' => esc_html__( 'Info Position', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'top' => [
							'title' => esc_html__( 'Top', 'charity-addon-for-elementor' ),
							'icon' => 'fa fa-arrow-circle-up',
						],
						'bottom' => [
							'title' => esc_html__( 'Bottom', 'charity-addon-for-elementor' ),
							'icon' => 'fa fa-arrow-circle-down',
						],
					],
					'default' => 'bottom',
				]
			);
			$this->add_control(
				'testimonials_icon',
				[
					'label' => esc_html__( 'Select Quote Icon', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::ICON,
					'options' => NACEP_Controls_Helper_Output::get_include_icons(),
					'frontend_available' => true,
					'default' => 'fa fa-quote-left',
				]
			);
			$this->add_control(
				'testimonials_image',
				[
					'label' => esc_html__( 'Upload Image', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::MEDIA,
					'frontend_available' => true,
					'description' => esc_html__( 'Set your image.', 'charity-addon-for-elementor'),
				]
			);
			$this->add_control(
				'testimonials_title',
				[
					'label' => esc_html__( 'Name', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'Cathrine Wagner', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->add_control(
				'testimonials_title_link',
				[
					'label' => esc_html__( 'Name Link', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::URL,
					'placeholder' => 'https://your-link.com',
					'default' => [
						'url' => '',
					],
					'label_block' => true,
				]
			);
			$this->add_control(
				'testimonials_designation',
				[
					'label' => esc_html__( 'Designation Text', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => esc_html__( 'General Manager', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type title text here', 'charity-addon-for-elementor' ),
					'label_block' => true,
				]
			);
			$this->add_control(
				'testimonials_content',
				[
					'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
					'default' => esc_html__( 'A man of means then along come to they got nothin but their jeans now were up in the big leagues.', 'charity-addon-for-elementor' ),
					'placeholder' => esc_html__( 'Type your content here', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::TEXTAREA,
					'label_block' => true,
				]
			);

			$this->add_responsive_control(
				'section_alignment',
				[
					'label' => esc_html__( 'Alignment', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'charity-addon-for-elementor' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'charity-addon-for-elementor' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'charity-addon-for-elementor' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'left',
					'selectors' => [
						'{{WRAPPER}} .nacep-testimonial-item' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();// end: Section

			// Section
				$this->start_controls_section(
					'sectn_style',
					[
						'label' => esc_html__( 'Section', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'section_margin',
					[
						'label' => __( 'Margin', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'section_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->start_controls_tabs( 'secn_style' );
					$this->start_controls_tab(
						'secn_normal',
						[
							'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'secn_bg_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-testimonial-item' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'secn_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-testimonial-item',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'secn_box_shadow',
							'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-testimonial-item',
						]
					);
					$this->end_controls_tab();  // end:Normal tab

					$this->start_controls_tab(
						'secn_hover',
						[
							'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
						]
					);
					$this->add_control(
						'secn_bg_hover_color',
						[
							'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-testimonial-item.nacep-hover' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name' => 'secn_hover_border',
							'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-testimonial-item.nacep-hover',
						]
					);
					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name' => 'secn_hover_box_shadow',
							'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
							'selector' => '{{WRAPPER}} .nacep-testimonial-item.nacep-hover',
						]
					);
					$this->end_controls_tab();  // end:Hover tab
				$this->end_controls_tabs(); // end tabs

				$this->end_controls_section();// end: Section

			// Quote Icon
				$this->start_controls_section(
					'section_icon_style',
					[
						'label' => esc_html__( 'Quote Icon', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'icon_color',
					[
						'label' => esc_html__( 'Icon Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-icon i' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_bgcolor',
					[
						'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-icon' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_border_radius',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'icon_size',
					[
						'label' => esc_html__( 'Icon Size', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 500,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'icon_lheight',
					[
						'label' => esc_html__( 'Icon width & Height', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 500,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-icon i' => 'line-height: {{SIZE}}{{UNIT}};',
							'{{WRAPPER}} .nacep-testimonial-item .nacep-icon' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'icon_position',
					[
						'label' => esc_html__( 'Iocn Position', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SELECT,
						'options' => [
							'unset' => esc_html__( 'Default', 'charity-addon-for-elementor' ),
							'absolute' => esc_html__( 'Absolute', 'charity-addon-for-elementor' ),
						],
						'default' => 'unset',
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-icon' => 'position: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_left',
					[
						'label' => esc_html__( 'Icon Left', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 1000,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-icon' => 'left: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
							'icon_position' => array('absolute'),
						],
					]
				);
				$this->add_control(
					'icon_right',
					[
						'label' => esc_html__( 'Icon Right', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 1000,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-icon' => 'right: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
							'icon_position' => array('absolute'),
						],
					]
				);
				$this->add_control(
					'icon_top',
					[
						'label' => esc_html__( 'Icon Top', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 1000,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-icon' => 'top: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
							'icon_position' => array('absolute'),
						],
					]
				);
				$this->add_control(
					'icon_bottom',
					[
						'label' => esc_html__( 'Icon Bottom', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => -1000,
								'max' => 1000,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-icon' => 'bottom: {{SIZE}}{{UNIT}};',
						],
						'condition' => [
							'icon_position' => array('absolute'),
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Image
				$this->start_controls_section(
					'section_image_style',
					[
						'label' => esc_html__( 'Image', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
				$this->add_control(
					'image_padding',
					[
						'label' => __( 'Padding', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'image_border_radius',
					[
						'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%', 'em' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'image_border',
						'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-testimonial-item .nacep-image img',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'image_box_shadow',
						'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .nacep-testimonial-item .nacep-image img',
					]
				);
				$this->add_control(
					'image_width',
					[
						'label' => esc_html__( 'Image width', 'charity-addon-for-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 500,
								'step' => 1,
							],
						],
						'size_units' => [ 'px' ],
						'selectors' => [
							'{{WRAPPER}} .nacep-testimonial-item .nacep-image img' => 'max-width: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->end_controls_section();// end: Section

			// Title
				$this->start_controls_section(
					'section_title_style',
					[
						'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
							'name' => 'sastestimonial_title_typography',
							'selector' => '{{WRAPPER}} .nacep-testimonial-item h4',
						]
					);
					$this->start_controls_tabs( 'testimonials_title_style' );
						$this->start_controls_tab(
							'title_normal',
							[
								'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
							]
						);
						$this->add_control(
							'title_color',
							[
								'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
								'type' => Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .nacep-testimonial-item h4, {{WRAPPER}} .nacep-testimonial-item h4 a' => 'color: {{VALUE}};',
								],
							]
						);
						$this->end_controls_tab();  // end:Normal tab

						$this->start_controls_tab(
							'title_hover',
							[
								'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
							]
						);
						$this->add_control(
							'title_hov_color',
							[
								'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
								'type' => Controls_Manager::COLOR,
								'selectors' => [
									'{{WRAPPER}} .nacep-testimonial-item h4 a:hover' => 'color: {{VALUE}};',
								],
							]
						);
						$this->end_controls_tab();  // end:Hover tab
					$this->end_controls_tabs(); // end tabs
				$this->end_controls_section();// end: Section

			// Designation
				$this->start_controls_section(
					'section_subtitle_style',
					[
						'label' => esc_html__( 'Designation', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
							'name' => 'subtitle_typography',
							'selector' => '{{WRAPPER}} .nacep-testimonial-item h5',
						]
					);
					$this->add_control(
						'subtitle_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-testimonial-item h5' => 'color: {{VALUE}};',
							],
						]
					);
				$this->end_controls_section();// end: Section

			// Content
				$this->start_controls_section(
					'section_content_style',
					[
						'label' => esc_html__( 'Content', 'charity-addon-for-elementor' ),
						'tab' => Controls_Manager::TAB_STYLE,
					]
				);
					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'label' => esc_html__( 'Typography', 'charity-addon-for-elementor' ),
							'name' => 'content_typography',
							'selector' => '{{WRAPPER}} .nacep-testimonial-item p',
						]
					);
					$this->add_control(
						'content_color',
						[
							'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .nacep-testimonial-item p' => 'color: {{VALUE}};',
							],
						]
					);
				$this->end_controls_section();// end: Section

		}

		/**
		 * Render Testimonials widget output on the frontend.
		 * Written in PHP and used to generate the final HTML.
		*/
		protected function render() {
			// Testimonials query
			$settings = $this->get_settings_for_display();
			$center_item = !empty( $settings['center_item'] ) ? $settings['center_item'] : '';
			$info_position = !empty( $settings['info_position'] ) ? $settings['info_position'] : '';
			$testimonials_image = !empty( $settings['testimonials_image']['id'] ) ? $settings['testimonials_image']['id'] : '';
			$testimonials_icon = !empty( $settings['testimonials_icon'] ) ? $settings['testimonials_icon'] : '';
			$testimonials_title = !empty( $settings['testimonials_title'] ) ? $settings['testimonials_title'] : '';

			$testimonials_title_link = !empty( $settings['testimonials_title_link'] ) ? $settings['testimonials_title_link'] : '';
			$link_url = !empty( $testimonials_title_link['url'] ) ? esc_url($testimonials_title_link['url']) : '';
			$link_external = !empty( $testimonials_title_link['is_external'] ) ? 'target="_blank"' : '';
			$link_nofollow = !empty( $testimonials_title_link['nofollow'] ) ? 'rel="nofollow"' : '';
			$link_attr = !empty( $testimonials_title_link['url'] ) ?  $link_external.' '.$link_nofollow : '';

			$testimonials_designation = !empty( $settings['testimonials_designation'] ) ? $settings['testimonials_designation'] : '';
			$testimonials_content = !empty( $settings['testimonials_content'] ) ? $settings['testimonials_content'] : '';

			if ($info_position === 'top') {
			  $style_cls = ' info-top';
			} else {
			  $style_cls = '';
			}

			if ($center_item) {
			  $center_cls = ' center-item';
			} else {
			  $center_cls = '';
			}

		  $title_link = !empty( $link_url ) ? '<a href="'.esc_url($link_url).'" '.$link_attr.'>'.esc_html($testimonials_title).'</a>' : esc_html($testimonials_title);

			$title = $testimonials_title ? '<h4 class="customer-name">'.$title_link.'</h4>' : '';
			$designation = $testimonials_designation ? '<h5 class="customer-designation">'.esc_html($testimonials_designation).'</h5>' : '';
			$content = $testimonials_content ? '<p>'.esc_html($testimonials_content).'</p>' : '';

			if ($info_position === 'top') {
			  $top_content = '';
			  $bottom_content = $content;
			} else {
			  $top_content = $content;
			  $bottom_content = '';
			}

			$image_url = wp_get_attachment_url( $testimonials_image );
			$testimonials_image = $image_url ? '<div class="nacep-image"><img src="'.esc_url($image_url).'" alt="'.esc_attr($testimonials_title).'"></div>' : '';
			$testimonials_icon = $testimonials_icon ? '<div class="nacep-icon"><i class="'.esc_attr($testimonials_icon).'"></i></div>' : '';

			$output = '<div class="nacep-testimonial-item'.$style_cls.$center_cls.'">
			              '.$testimonials_icon.$top_content.'
			              <div class="customer-info">
			                '.$testimonials_image.'
			                <div class="customer-inner-info">
			                  '.$title.$designation.'
			                </div>
			              </div>
			              '.$bottom_content.'
			            </div>';

			echo $output;

		}

	}
	Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Testimonials() );
}
