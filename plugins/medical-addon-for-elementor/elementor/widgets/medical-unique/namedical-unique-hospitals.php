<?php
/*
 * Elementor Medical Addon for Elementor Hospitals Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Unique_Hospitals extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_unique_hospitals';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Hospitals', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-welcome';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-unique-category'];
	}

	/**
	 * Register Medical Addon for Elementor Hospitals widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_hospitals',
			[
				'label' => __( 'Hospitals Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'hospitals_image',
			[
				'label' => esc_html__( 'Background Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your image.', 'medical-addon-for-elementor'),
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'hospitals_title',
			[
				'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Al Khor Hospital', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'title_link',
			[
				'label' => esc_html__( 'Title Link', 'medical-elementor-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'hospitals_content',
			[
				'label' => esc_html__( 'Content', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'default' => esc_html__( 'A general hospital providing healthcare services to the growing population in the northern region of Qatar.​​', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'more_text',
			[
				'label' => esc_html__( 'More Title', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'View Details', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'more_icon',
			[
				'label' => esc_html__( 'More Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-arrow-right',
			]
		);
		$repeater->add_control(
			'more_link',
			[
				'label' => esc_html__( 'More Link', 'medical-elementor-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'hospitals_groups',
			[
				'label' => esc_html__( 'Hospitals Items', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'hospitals_title' => esc_html__( 'Pediatrics', 'medical-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ hospitals_title }}}',
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'hospitals_section_padding',
				[
					'label' => __( 'Section Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-hospital-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'overlay_color',
				[
					'label' => esc_html__( 'Overlay Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-hospitals-wrap.namep-overlay:before' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-hospital-item' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_hov_color',
				[
					'label' => esc_html__( 'Background Hover Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-hospital-item.namep-hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'secn_bdr_color',
				[
					'label' => esc_html__( 'Border Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-hospitals-wrap [class*="nich-col-"]' => 'border-color: {{VALUE}};',
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
						'{{WRAPPER}} .namep-hospital-item h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sastool_title_typography',
					'selector' => '{{WRAPPER}} .namep-hospital-item h4',
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
							'{{WRAPPER}} .namep-hospital-item h4, {{WRAPPER}} .namep-hospital-item h4 a' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .namep-hospital-item h4 a:hover' => 'color: {{VALUE}};',
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
				'cont_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-hospital-item p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'sastool_content_typography',
					'selector' => '{{WRAPPER}} .namep-hospital-item p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-hospital-item p' => 'color: {{VALUE}};',
					],
				]
			);
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
		$hospitals = $this->get_settings_for_display( 'hospitals_groups' );
		$hospitals_image = !empty( $settings['hospitals_image']['id'] ) ? $settings['hospitals_image']['id'] : '';
		$image_url = wp_get_attachment_url( $hospitals_image );
		$image = $image_url ? ' style="background-image: url('.esc_url($image_url).');"' : '';

		$output = '<div class="namep-hospitals-wrap namep-overlay"'.$image.'><div class="nich-row">';
		// Group Param Output
		foreach ( $hospitals as $each_logo ) {
			$hospitals_title = !empty( $each_logo['hospitals_title'] ) ? $each_logo['hospitals_title'] : '';
			$title_link = !empty( $each_logo['title_link']['url'] ) ? esc_url($each_logo['title_link']['url']) : '';
			$title_link_external = !empty( $title_link['is_external'] ) ? 'target="_blank"' : '';
			$title_link_nofollow = !empty( $title_link['nofollow'] ) ? 'rel="nofollow"' : '';
			$title_link_attr = !empty( $title_link['url'] ) ?  $title_link_external.' '.$title_link_nofollow : '';
			$hospitals_content = !empty( $each_logo['hospitals_content'] ) ? $each_logo['hospitals_content'] : '';

			$more_text = !empty( $each_logo['more_text'] ) ? $each_logo['more_text'] : '';
			$more_icon = !empty( $each_logo['more_icon'] ) ? $each_logo['more_icon'] : '';
			$more_link = !empty( $each_logo['more_link']['url'] ) ? esc_url($each_logo['more_link']['url']) : '';
			$more_link_external = !empty( $more_link['is_external'] ) ? 'target="_blank"' : '';
			$more_link_nofollow = !empty( $more_link['nofollow'] ) ? 'rel="nofollow"' : '';
			$more_link_attr = !empty( $more_link['url'] ) ?  $more_link_external.' '.$more_link_nofollow : '';

	  	$title_link = !empty( $title_link ) ? '<a href="'.esc_url($title_link).'" '.$title_link_attr.'>'.esc_html($hospitals_title).'</a>' : esc_html($hospitals_title);
	  	$title = !empty( $hospitals_title ) ? '<h4 class="namep-hospital-title">'.$hospitals_title.'</h4>' : '';
			$content = $hospitals_content ? '<p>'.esc_html($hospitals_content).'</p>' : '';
	  	$more_icon = $more_icon ? ' <i class="'.esc_attr($more_icon).'"></i>' : '';
	  	$more_link = !empty( $more_link ) ? '<a href="'.esc_url($more_link).'" class="namep-link" '.$more_link_attr.'><span>'.$more_text.'</span>'.$more_icon.'</a>' : '';

  		$output .= '<div class="nich-col-lg-4 nich-col-md-6">
  									<div class="namep-hospital-item">'.$title.$content.$more_link.'</div>
			            </div>';
		}
		$output .= '</div></div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Unique_Hospitals() );
