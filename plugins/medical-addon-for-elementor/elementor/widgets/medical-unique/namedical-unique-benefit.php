<?php
/*
 * Elementor Medical Addon for Elementor Benefits Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Unique_Benefits extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_unique_benefit';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Benefits', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-info-circle-o';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-unique-category'];
	}

	/**
	 * Register Medical Addon for Elementor Benefits widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_benefit',
			[
				'label' => __( 'Benefits Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'benefit_image',
			[
				'label' => esc_html__( 'Benefits Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'medical-addon-for-elementor'),
			]
		);
		$this->add_control(
			'benefit_count',
			[
				'label' => esc_html__( 'Benefits Count', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '01', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'benefit_title',
			[
				'label' => esc_html__( 'Benefits Title', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'For Patients', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'title_link',
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
		$repeater = new Repeater();
		$repeater->add_control(
			'list_icon',
			[
				'label' => esc_html__( 'List Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'icon-basic-laptop',
			]
		);
		$repeater->add_control(
			'list_text',
			[
				'label' => esc_html__( 'List Title', 'medical-addon-for-elementor' ),
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

		// Count
			$this->start_controls_section(
				'section_count_style',
				[
					'label' => esc_html__( 'Count', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'count_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-benefit-info h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sastool_count_typography',
					'selector' => '{{WRAPPER}} .namep-benefit-info h2',
				]
			);
			$this->add_control(
				'count_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-benefit-info h2' => 'color: {{VALUE}};',
					],
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
						'{{WRAPPER}} .namep-benefit-info h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sastool_title_typography',
					'selector' => '{{WRAPPER}} .namep-benefit-info h3',
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
							'{{WRAPPER}} .namep-benefit-info h3, {{WRAPPER}} .namep-benefit-info h3 a' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .namep-benefit-info h3 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// List
			$this->start_controls_section(
				'section_box_style',
				[
					'label' => esc_html__( 'List', 'medical-addon-for-elementor' ),
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
						'{{WRAPPER}} .namep-benefit-info-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-benefit-info-item' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-benefit-info-item',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'section_box_shadow',
					'label' => esc_html__( 'Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-benefit-info-item',
				]
			);
			$this->end_controls_section();// end: Section

		// List Title
			$this->start_controls_section(
				'section_list_style',
				[
					'label' => esc_html__( 'List Title', 'medical-addon-for-elementor' ),
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
						'{{WRAPPER}} .namep-benefit-item-info h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'list_typography',
					'selector' => '{{WRAPPER}} .namep-benefit-item-info h4',
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
							'{{WRAPPER}} .namep-benefit-item-info h4, {{WRAPPER}} .namep-benefit-item-info h4 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'icon_color',
					[
						'label' => esc_html__( 'Icon Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-benefit-info-item i' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .namep-benefit-item-info h4 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// List Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'List Content', 'medical-addon-for-elementor' ),
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
						'{{WRAPPER}} .namep-benefit-info p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sastool_content_typography',
					'selector' => '{{WRAPPER}} .namep-benefit-info p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-benefit-info p' => 'color: {{VALUE}};',
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
		$benefit_image = !empty( $settings['benefit_image']['id'] ) ? $settings['benefit_image']['id'] : '';
		$image_url = wp_get_attachment_url( $benefit_image );
		$benefit_count = !empty( $settings['benefit_count'] ) ? $settings['benefit_count'] : '';
		$benefit_title = !empty( $settings['benefit_title'] ) ? $settings['benefit_title'] : '';
		$title_link = !empty( $settings['title_link']['url'] ) ? $settings['title_link']['url'] : '';
		$title_link_external = !empty( $settings['title_link']['is_external'] ) ? 'target="_blank"' : '';
		$title_link_nofollow = !empty( $settings['title_link']['nofollow'] ) ? 'rel="nofollow"' : '';
		$title_link_attr = !empty( $title_link ) ?  $title_link_external.' '.$title_link_nofollow : '';
		$toggle_align = !empty( $settings['toggle_align'] ) ? $settings['toggle_align'] : '';

		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';

		$benefit_image = $image_url ? '<div class="namep-image"><img src="'.esc_url($image_url).'" alt="'.esc_attr($benefit_title).'"></div>' : '';
  	$title_link = !empty( $title_link ) ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'.esc_html($benefit_title).'</a>' : esc_html($benefit_title);
  	$title = !empty( $benefit_title ) ? '<h3 class="namep-benefit-title">'.$title_link.'</h3>' : '';
  	$count = !empty( $benefit_count ) ? '<h2 class="namep-benefit-counter">'.$benefit_count.'</h2>' : '';

		if ($toggle_align) {
			$f_cls = ' nich-order-1';
			$s_cls = ' nich-order-2';
		} else {
			$f_cls = '';
			$s_cls = '';
		}
		
		$output = '<div class="namep-benefit-item">
			          <div class="nich-row nich-align-items-center">
			            <div class="nich-col-lg-6'.$s_cls.'">'.$benefit_image.'</div>
			            <div class="nich-col-lg-6'.$f_cls.'">
			              <div class="namep-benefit-info">
			                <div class="namep-benefit-main-title">
			                  '.$count.$title.'
			                </div>';
												if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ) {
												  foreach ( $listItems_groups as $each_list ) {
												  	$list_icon = !empty( $each_list['list_icon'] ) ? $each_list['list_icon'] : '';
												  	$list_text = !empty( $each_list['list_text'] ) ? $each_list['list_text'] : '';
												  	$text_link = !empty( $each_list['text_link']['url'] ) ? $each_list['text_link']['url'] : '';
														$text_link_external = !empty( $each_list['text_link']['is_external'] ) ? 'target="_blank"' : '';
														$text_link_nofollow = !empty( $each_list['text_link']['nofollow'] ) ? 'rel="nofollow"' : '';
														$text_link_attr = !empty( $text_link ) ?  $text_link_external.' '.$text_link_nofollow : '';
												  	$list_content = !empty( $each_list['list_content'] ) ? $each_list['list_content'] : '';

												  	$list_title_link = $text_link ? '<a href="'.esc_url($text_link).'" '.$text_link_attr.'>'. esc_html($list_text) .'</a>' : esc_html($list_text);
  													$list_title = !empty( $list_text ) ? '<h4 class="namep-benefit-info-title">'.$list_title_link.'</h4>' : '';
  													$list_content = !empty( $list_content ) ? '<p>'.$list_content.'</p>' : '';
  													$list_icon = $list_icon ? '<div class="namep-icon"><i class="'.esc_attr($list_icon).'"></i></div>' : '';

				                  	$output .= '<div class="namep-benefit-info-item">
												                  '.$list_icon.'
												                  <div class="namep-benefit-item-info">
												                    '.$list_title.$list_content.'
												                  </div>
												                </div>';
					                }
					              }
        $output .= '</div>
			            </div>
			          </div>
			        </div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Unique_Benefits() );
