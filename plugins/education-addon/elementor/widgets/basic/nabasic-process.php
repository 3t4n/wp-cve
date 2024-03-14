<?php
/*
 * Elementor Education Addon Process Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_process'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Process extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_process';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Process', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-time-line';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Process widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_process',
			[
				'label' => __( 'Process Item', 'education-addon' ),
			]
		);
		$this->add_control(
			'process_style',
			[
				'label' => esc_html__( 'Process Style', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'education-addon' ),
					'two' => esc_html__( 'Style Two', 'education-addon' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your style.', 'education-addon' ),
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'process_image',
			[
				'label' => esc_html__( 'Process Image', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'education-addon'),
			]
		);
		$repeater->add_control(
			'process_title',
			[
				'label' => esc_html__( 'Process Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Take a Lesson', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$repeater->add_control(
			'process_link',
			[
				'label' => esc_html__( 'Process Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'education-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority alteration in some form.', 'education-addon' ),
				'placeholder' => esc_html__( 'Type text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'listItems_groups',
			[
				'label' => esc_html__( 'List Items', 'education-addon' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ process_title }}}',
				'prevent_empty' => false,
				'separator' => 'before',
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-process figure' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-process figure' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_bdr_rad',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-process figure' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-process figure' => 'background-color: {{VALUE}};',
					],
				]
			);		
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-process figure',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-process figure',
				]
			);
			$this->end_controls_section();// end: Section

		// Count
			$this->start_controls_section(
				'section_count_style',
				[
					'label' => esc_html__( 'Count', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'count_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-process .naedu-image:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'count_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-process .naedu-image:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'count_size',
				[
					'label' => esc_html__( 'Count Size', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-process .naedu-image:before' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'count_typography',
					'selector' => '{{WRAPPER}} .naedu-process .naedu-image:before',
				]
			);
			$this->add_control(
				'count_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-process .naedu-image:before' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'count_bg',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-process .naedu-image:before' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'count_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-process .naedu-image:before',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'count_shadow',
					'label' => esc_html__( 'Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-process .naedu-image:before',
				]
			);		
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'title_padding',
				[
					'label' => __( 'Title Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-process h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .naedu-process h3',
				]
			);			
			$this->start_controls_tabs( 'ttl_style' );
				$this->start_controls_tab(
					'ttl_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-process h3, {{WRAPPER}} .naedu-process h3 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'ttl_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'title_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-process h3 a:hover' => 'color: {{VALUE}};',
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
					'label' => esc_html__( 'Content', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-process p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'content_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-process p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .naedu-process p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-process p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Process widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Process query
		$settings = $this->get_settings_for_display();
		$process_style = !empty( $settings['process_style'] ) ? $settings['process_style'] : '';
		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';

		if ($process_style === 'two') {
			$style_cls = ' process-style-two';
		} else {
			$style_cls = '';
		}

		$output = '<div class="naedu-process'.$style_cls.'"><div class="nich-row">';
  	// Group Param Output
		if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ) {
		  foreach ( $listItems_groups as $each_list ) {
			$process_image = !empty( $each_list['process_image']['id'] ) ? $each_list['process_image']['id'] : '';
		  $process_title = !empty( $each_list['process_title'] ) ? $each_list['process_title'] : '';
		  $process_link = !empty( $each_list['process_link']['url'] ) ? esc_url($each_list['process_link']['url']) : '';
			$process_link_external = !empty( $process_link['is_external'] ) ? 'target="_blank"' : '';
			$process_link_nofollow = !empty( $process_link['nofollow'] ) ? 'rel="nofollow"' : '';
			$process_link_attr = !empty( $process_link['url'] ) ?  $process_link_external.' '.$process_link_nofollow : '';
		  $content = !empty( $each_list['content'] ) ? $each_list['content'] : '';

		  $image_url = wp_get_attachment_url( $process_image );
			$process_image_link = $process_link ? '<a href="'.esc_url($process_link).'" '.$process_link_attr.'><img src="'.esc_url($image_url).'" alt="Image"></a>' : '<img src="'.esc_url($image_url).'" alt="Image">';
			$image = $image_url ? '<div class="naedu-image">'.$process_image_link.'</div>' : '';
			
			$process_title_link = $process_link ? '<a href="'.esc_url($process_link).'" '.$process_link_attr.'>'.$process_title.'</a>' : $process_title;
			$process_title = $process_title ? '<h3>'.$process_title_link.'</h3>' : '';
			$content = $content ? '<p>'.$content.'</p>' : '';

				if ($process_style === 'two') {
					$output .= '<div class="nich-col-md-12">
							          <figure>
							            '.$image.'
							            <figcaption>'.$process_title.$content.'</figcaption>
							          </figure>
							        </div>';
			  } else {
				  $output .= '<div class="nich-col-md-4">
							          <figure>
							            '.$image.'
							            <figcaption>'.$process_title.$content.'</figcaption>
							          </figure>
							        </div>';
				} 
			}
	  }
	  $output .= '</div></div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Process() );

} // enable & disable
