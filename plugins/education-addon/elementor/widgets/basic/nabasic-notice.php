<?php
/*
 * Elementor Education Addon Notice Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_notice'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Notice extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_notice';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Notice', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-warning';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Profile widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_notice',
			[
				'label' => __( 'Notice', 'education-addon' ),
			]
		);
		$this->add_control(
			'notice_title',
			[
				'label' => esc_html__( 'Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get 20% Discount Today!', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'education-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type details text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_notice_btn',
			[
				'label' => __( 'Button', 'education-addon' ),
			]
		);		
		$this->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Read More', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'btn_link',
			[
				'label' => esc_html__( 'Button Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '#0',
				],
				'label_block' => true,
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
				'section_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-notice' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-notice' => 'background: {{VALUE}};',
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
						'{{WRAPPER}} .naedu-notice' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-notice',
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
						'{{WRAPPER}} .naedu-notice-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .naedu-notice-title',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-notice-title' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Content', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .naedu-notice-content',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-notice-content' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Button
			$this->start_controls_section(
				'section_contact_style',
				[
					'label' => esc_html__( 'Button', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'btn_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-notice-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'btn_padding',
				[
					'label' => __( 'Button Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-notice-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'btn_typography',
					'selector' => '{{WRAPPER}} .naedu-notice-btn',
				]
			);
			$this->start_controls_tabs( 'btn_style' );
				$this->start_controls_tab(
					'btn_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'btn_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-notice-btn' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-notice-btn' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-notice-btn',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-notice-btn',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'btn_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'btn_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-notice-btn:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-notice-btn:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'btn_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-notice-btn:hover',
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'btn_hover_shadow',
						'label' => esc_html__( 'Button Shadow', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-notice-btn:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render notice widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$notice_title = !empty( $settings['notice_title'] ) ? $settings['notice_title'] : '';
		$content = !empty( $settings['content'] ) ? $settings['content'] : '';

		$btn_text = !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
		$btn_link = !empty( $settings['btn_link']['url'] ) ? esc_url($settings['btn_link']['url']) : '';
		$btn_link_external = !empty( $btn_link['is_external'] ) ? 'target="_blank"' : '';
		$btn_link_nofollow = !empty( $btn_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$btn_link_attr = !empty( $btn_link['url'] ) ?  $btn_link_external.' '.$btn_link_nofollow : '';
		
		$title = $notice_title ? '<div class="naedu-notice-title">'.$notice_title.'</div>' : '';
		$content = $content ? '<div class="naedu-notice-content">'.$content.'</div>' : '';
		
		$content = '<div class="naedu-notice-content-wrapper">'.$title . $content.'</div>';

		$btn_text = $btn_text ? $btn_text : '';
		$btn_link = $btn_text ? '<a class="naedu-notice-btn" href="'.esc_url($btn_link).'" '.$btn_link_attr.'>'.$btn_text.'</a>' : '';
		$btn = '<div class="naedu-notice-btn-wrapper">'.$btn_link.'</div>';

		?>
		<div class="naedu-notice">
			<?php echo $content . $btn; ?>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Notice() );
} // enable & disable
