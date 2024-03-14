<?php
/*
 * Elementor Medical Addon for Elementor Action Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Unique_Action extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_unique_action';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Action', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-unique-category'];
	}

	/**
	 * Register Medical Addon for Elementor Action widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_action',
			[
				'label' => __( 'Action Options', 'medical-addon-for-elementor' ),
			]
		);
		
		$repeater = new Repeater();
		$repeater->add_control(
			'action_image',
			[
				'label' => esc_html__( 'Action Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'medical-addon-for-elementor'),
			]
		);
		$repeater->add_control(
			'list_text',
			[
				'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Medical Students', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'text_link',
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
			'list_content',
			[
				'label' => esc_html__( 'List Content', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Offer seamless patient experience - from booking home collections to receiving reports and payments.', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'toggle_align',
			[
				'label' => esc_html__( 'Toggle Align?', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'medical-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'medical-addon-for-elementor' ),
				'return_value' => 'true',
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
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'section_box_style',
				[
					'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'section_margin',
				[
					'label' => __( 'Margin', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-action-item-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-action-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-action-item-wrap' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-action-item-wrap',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'section_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-action-item-wrap',
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
						'{{WRAPPER}} .namep-action-info h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sastool_title_typography',
					'selector' => '{{WRAPPER}} .namep-action-info h4',
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
							'{{WRAPPER}} .namep-action-info h4, {{WRAPPER}} .namep-action-info h4 a' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .namep-action-info h4 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Content', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-action-info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sastool_content_typography',
					'selector' => '{{WRAPPER}} .namep-action-info p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-action-info p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';
		
		$output = '<div class="namep-action-wrap">';
								if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ) {
								  foreach ( $listItems_groups as $each_list ) {
								  	$action_image = !empty( $each_list['action_image']['id'] ) ? $each_list['action_image']['id'] : '';
										$image_url = wp_get_attachment_url( $action_image );
								  	$list_text = !empty( $each_list['list_text'] ) ? $each_list['list_text'] : '';
								  	$text_link = !empty( $each_list['text_link']['url'] ) ? $each_list['text_link']['url'] : '';
										$text_link_external = !empty( $each_list['text_link']['is_external'] ) ? 'target="_blank"' : '';
										$text_link_nofollow = !empty( $each_list['text_link']['nofollow'] ) ? 'rel="nofollow"' : '';
										$text_link_attr = !empty( $text_link ) ?  $text_link_external.' '.$text_link_nofollow : '';
								  	$list_content = !empty( $each_list['list_content'] ) ? $each_list['list_content'] : '';
										$toggle_align = !empty( $each_list['toggle_align'] ) ? $each_list['toggle_align'] : '';

								  	if ($toggle_align) {
											$toggle_cls = ' action-toggle';
										} else {
											$toggle_cls = '';
										}

								  	$list_title_link = $text_link ? '<a href="'.esc_url($text_link).'" '.$text_link_attr.'>'. esc_html($list_text) .'</a>' : esc_html($list_text);
										$list_title = !empty( $list_text ) ? '<h4 class="namep-action-title">'.$list_title_link.'</h4>' : '';
										$list_content = !empty( $list_content ) ? '<p>'.$list_content.'</p>' : '';
										$action_image = $image_url ? '<div class="namep-action-item"><div class="namep-image"><img src="'.esc_url($image_url).'" alt="'.esc_attr($list_text).'"></div></div>' : '';

                  	$output .= '<div class="namep-action-item-wrap'.$toggle_cls.'">
										              '.$action_image.'<div class="namep-action-info">'.$list_title.$list_content.'</div>
										            </div>';
	                }
	              }
    $output .= '</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Unique_Action() );
