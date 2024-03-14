<?php
/*
 * Elementor Medical Addon for Elementor Resources Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Unique_Resources extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_unique_resource';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Resources', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-unique-category'];
	}

	/**
	 * Register Medical Addon for Elementor Resources widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_resource',
			[
				'label' => __( 'Resources Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'resource_image',
			[
				'label' => esc_html__( 'Resource Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'medical-addon-for-elementor'),
			]
		);
		$this->add_control(
			'resource_title',
			[
				'label' => esc_html__( 'Resource Title', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'For Patients', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'list_text',
			[
				'label' => esc_html__( 'List Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Medical Students', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'text_link',
			[
				'label' => esc_html__( 'Text Link', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'listItems_groups',
			[
				'label' => esc_html__( 'List', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'list_text' => esc_html__( 'Medical Students', 'medical-addon-for-elementor' ),
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ list_text }}}',
				'prevent_empty' => false,
			]
		);			
		$this->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'View All Resources', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'btn_icon',
			[
				'label' => esc_html__( 'Button Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-arrow-right',
			]
		);
		$this->add_control(
			'btn_link',
			[
				'label' => esc_html__( 'Button Link', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'toggle_align',
			[
				'label' => esc_html__( 'Toggle Align?', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'medical-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'medical-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$this->end_controls_section();// end: Section

		// Style
		// Section
			$this->start_controls_section(
				'section_box_style',
				[
					'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'section_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-resource-wrap' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'section_border_radius',
				[
					'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-resource-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-resource-wrap',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'section_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-resource-wrap',
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'title_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-resource-info h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sastool_title_typography',
					'selector' => '{{WRAPPER}} .namep-resource-info h3',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-resource-info h3' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// List
			$this->start_controls_section(
				'section_list_style',
				[
					'label' => esc_html__( 'List', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'list_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .namep-bullet-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'list_typography',
					'selector' => '{{WRAPPER}} .namep-bullet-list li',
				]
			);
			$this->start_controls_tabs( 'list_style' );
				$this->start_controls_tab(
					'list_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'list_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-bullet-list li, {{WRAPPER}} .namep-bullet-list li a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_color',
					[
						'label' => esc_html__( 'Icon Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-bullet-list li:before' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'list_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'list_hover_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-bullet-list li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Link
			$this->start_controls_section(
				'section_link_style',
				[
					'label' => esc_html__( 'Link', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'link_typography',
					'selector' => '{{WRAPPER}} .namep-link',
				]
			);
			$this->start_controls_tabs( 'link_style' );
				$this->start_controls_tab(
					'link_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'link_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-link' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'link_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'link_hover_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-link:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'link_bg_hover_color',
					[
						'label' => esc_html__( 'Line Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-link span:after' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$resource_image = !empty( $settings['resource_image']['id'] ) ? $settings['resource_image']['id'] : '';
		$image_url = wp_get_attachment_url( $resource_image );
		$resource_title = !empty( $settings['resource_title'] ) ? $settings['resource_title'] : '';
		$toggle_align = !empty( $settings['toggle_align'] ) ? $settings['toggle_align'] : '';

		$btn_text = !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
		$btn_link = !empty( $settings['btn_link']['url'] ) ? $settings['btn_link']['url'] : '';
		$btn_link_external = !empty( $settings['btn_link']['is_external'] ) ? 'target="_blank"' : '';
		$btn_link_nofollow = !empty( $settings['btn_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$btn_link_attr = !empty( $btn_link ) ?  $btn_link_external.' '.$btn_link_nofollow : '';
		$btn_icon = !empty( $settings['btn_icon'] ) ? $settings['btn_icon'] : '';
  	$btn_icon = $btn_icon ? ' <i class="'.esc_attr($btn_icon).'"></i>' : '';

		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';

  	$title = !empty( $resource_title ) ? '<h3 class="namep-resource-title">'.$resource_title.'</h3>' : '';
		$resource_image = $image_url ? '<div class="namep-image"><img src="'.esc_url($image_url).'" alt="'.esc_attr($resource_title).'"></div>' : '';
		$button = $btn_link ? '<a href="'.esc_url($btn_link).'" '.$btn_link_attr.' class="namep-link"><span>'. esc_html($btn_text) .'</span>'.$btn_icon.'</a>' : '';
		
		if ($toggle_align) {
			$f_cls = ' nich-order-1';
			$s_cls = ' nich-order-2';
		} else {
			$f_cls = '';
			$s_cls = '';
		}
		
		$output = '<div class="namep-resource-wrap">
								<div class="namep-resource-item">
			            <div class="nich-row nich-align-items-center">
			              <div class="nich-col-lg-6'.$s_cls.'">'.$resource_image.'</div>
			              <div class="nich-col-lg-6'.$f_cls.'">
			                <div class="namep-resource-info">
			                  '.$title.'
			                  <ul class="namep-bullet-list namep-bullet-style-two">';
													if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ) {
													  foreach ( $listItems_groups as $each_list ) {
													  	$list_text = !empty( $each_list['list_text'] ) ? $each_list['list_text'] : '';
													  	$text_link = !empty( $each_list['text_link']['url'] ) ? $each_list['text_link']['url'] : '';
															$text_link_external = !empty( $each_list['text_link']['is_external'] ) ? 'target="_blank"' : '';
															$text_link_nofollow = !empty( $each_list['text_link']['nofollow'] ) ? 'rel="nofollow"' : '';
															$text_link_attr = !empty( $text_link ) ?  $text_link_external.' '.$text_link_nofollow : '';

													  	$text = $text_link ? '<li><a href="'.esc_url($text_link).'" '.$text_link_attr.'>'. esc_html($list_text) .'</a></li>' : '<li>'. esc_html($list_text) .'</li>';
					                  	$output .= $text;
						                }
						              }
	          $output .= '</ul>'.$button.'
			                </div>
			              </div>
			            </div>
			          </div>
		          </div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Unique_Resources() );
