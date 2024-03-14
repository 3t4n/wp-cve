<?php
/*
 * Elementor Education Addon Appointment Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_appointment'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Appointment extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_appointment';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Appointment', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-calendar';
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
			'section_appointment',
			[
				'label' => __( 'Appointment', 'education-addon' ),
			]
		);
		$this->add_control(
			'appointment_icon',
			[
				'label' => esc_html__( 'Icon', 'education-addon' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEDU_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fas fa-quote-left',
			]
		);		
		$this->add_control(
			'appointment_title',
			[
				'label' => esc_html__( 'Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'WORKING HOURS', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Monday - Friday', 'education-addon' ),
				'placeholder' => esc_html__( 'Type text here', 'education-addon' ),
			]
		);
		$repeater->add_control(
			'time',
			[
				'label' => esc_html__( 'Time', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '10:00 - 2:00', 'education-addon' ),
				'placeholder' => esc_html__( 'Type time here', 'education-addon' ),
			]
		);
		$this->add_control(
			'appointment_times',
			[
				'label' => esc_html__( 'Times', 'education-addon' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => esc_html__( 'Monday - Friday', 'education-addon' ),
                        'time' => '10:00 - 12:00',
                    ],
                    [
                        'title' => esc_html__( 'Satarday', 'education-addon' ),
                        'time' => '1:00 - 3:00',
                    ],
                    [
                        'title' => esc_html__( 'Sunday', 'education-addon' ),
                        'time' => '4:00 - 5:00',
                    ],
                ],
			]
		);		
		$this->end_controls_section();// end: Section
		
		$this->start_controls_section(
			'contact_texts',
			[
				'label' => esc_html__( 'Contact Text', 'education-addon' ),
			]
		);
		$this->add_control(
			'call_text',
			[
				'label' => esc_html__( 'Call Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Call me at: ', 'education-addon' ),
				'placeholder' => esc_html__( 'Type text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'phone_text',
			[
				'label' => esc_html__( 'Phone No.', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '356 - 190 - 8668', 'education-addon' ),
				'placeholder' => esc_html__( 'Type phone text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'phone_link',
			[
				'label' => esc_html__( 'Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'contact_details',
			[
				'label' => esc_html__( 'Contact Details', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'or book online by clicking below', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->end_controls_section();// end: Section
		
		$this->start_controls_section(
			'contact_links',
			[
				'label' => esc_html__( 'Contact Links', 'education-addon' ),
			]
		);
		$this->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'BOOK AN APPOINTMENT', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'btn_link',
			[
				'label' => esc_html__( 'Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '#0',
				],
				'label_block' => true,
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
						'{{WRAPPER}} .naedu-appointment' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'section_color',
				[
					'label' => esc_html__( 'Color Pattern', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-appointment-icon i, {{WRAPPER}} .naedu-appointment-phone-text a, {{WRAPPER}} .naedu-appointment-phone-text, {{WRAPPER}} .naedu-appointment-phone-text a:hover, {{WRAPPER}} .naedu-appointment-button a, {{WRAPPER}} .naedu-appointment-button a:hover' => 'color: {{VALUE}};',
						'{{WRAPPER}} .naedu-appointment, {{WRAPPER}} .naedu-appointment-icon, {{WRAPPER}} .naedu-appointment-button' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .naedu-appointment-details:after' => 'background: {{VALUE}};',
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
						'{{WRAPPER}} .naedu-appointment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-appointment, {{WRAPPER}} .naedu-appointment-icon, {{WRAPPER}} .naedu-appointment-button',
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
						'{{WRAPPER}} .naedu-appointment-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .naedu-appointment-title',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-appointment-title' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Phone txt
			$this->start_controls_section(
				'section_phn_txt_style',
				[
					'label' => esc_html__( 'Phone Text', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'phn_txt_typography',
					'selector' => '{{WRAPPER}} .naedu-appointment-details, {{WRAPPER}} .naedu-appointment-call-text',
				]
			);
			$this->add_control(
				'phn_txt_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-appointment-details, {{WRAPPER}} .naedu-appointment-call-text' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Phone No
			$this->start_controls_section(
				'section_phn_no_style',
				[
					'label' => esc_html__( 'Phone No.', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'phn_no_typography',
					'selector' => '{{WRAPPER}} .naedu-appointment-phone-text, {{WRAPPER}} .naedu-appointment-phone-text a, {{WRAPPER}} .naedu-appointment-phone-text a:hover',
				]
			);
			$this->add_control(
				'phn_no_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-appointment-phone-text, {{WRAPPER}} .naedu-appointment-phone-text a, {{WRAPPER}} .naedu-appointment-phone-text a:hover' => 'color: {{VALUE}};',
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
					'selector' => '{{WRAPPER}} .naedu-appointment-item',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-appointment-item' => 'color: {{VALUE}};',
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
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'contact_typography',
					'selector' => '{{WRAPPER}} .naedu-appointment-button a, {{WRAPPER}} .naedu-appointment-button a:hover',
				]
			);
			$this->add_control(
				'contact_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-appointment-button a, {{WRAPPER}} .naedu-appointment-button a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Icon
			$this->start_controls_section(
				'section_sicon_style',
				[
					'label' => esc_html__( 'Icon', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'sicon_size',
				[
					'label' => esc_html__( 'Icon Size', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 60,
							'step' => 1,
						],
						'%' => [
							'min' => 20,
							'max' => 90,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-appointment-icon i' => 'font-size:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'sicon_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-appointment-icon i' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render appointment widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		
		$settings = $this->get_settings_for_display();
		
		$appointment_icon = !empty( $settings['appointment_icon'] ) ? $settings['appointment_icon'] : '';
		$appointment_title = !empty( $settings['appointment_title'] ) ? $settings['appointment_title'] : '';

		$call_text = !empty( $settings['call_text'] ) ? $settings['call_text'] : '';
		$phone_text = !empty( $settings['phone_text'] ) ? $settings['phone_text'] : '';
		$phone_link = !empty( $settings['phone_link']['url'] ) ? esc_url($settings['phone_link']['url']) : '';
		$phone_link_external = !empty( $phone_link['is_external'] ) ? 'target="_blank"' : '';
		$phone_link_nofollow = !empty( $phone_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$phone_link_attr = !empty( $phone_link['url'] ) ?  $phone_link_external.' '.$phone_link_nofollow : '';
		$contact_details = !empty( $settings['contact_details'] ) ? $settings['contact_details'] : '';

		$btn_text = !empty( $settings['btn_text'] ) ? $settings['btn_text'] : '';
		$btn_link = !empty( $settings['btn_link']['url'] ) ? esc_url($settings['btn_link']['url']) : '';
		$btn_link_external = !empty( $btn_link['is_external'] ) ? 'target="_blank"' : '';
		$btn_link_nofollow = !empty( $btn_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$btn_link_attr = !empty( $btn_link['url'] ) ?  $btn_link_external.' '.$btn_link_nofollow : '';

		$appointment_times = !empty( $settings['appointment_times'] ) ? $settings['appointment_times'] : '';

		$icon = $appointment_icon ? '<div class="naedu-appointment-icon"><i class="'.$appointment_icon.'"></i></div>' : '';
		$title = $appointment_title ? '<div class="naedu-appointment-title">'.$appointment_title.'</div>' : '';

		$call_text = $call_text ? '<span class="naedu-appointment-call-text">'.$call_text.'</span>' : '';
		$phone_text = $phone_text ? '<span class="naedu-appointment-phone-text">'.$phone_text.'</span>' : '';
		$phone_link = $phone_link ? '<div class="naedu-appointment-call-text-wrapper">' . $call_text .'<a href="'.esc_url($phone_link).'" '.$phone_link_attr.'>' . $phone_text . '</a></div>' : '<div class="naedu-appointment-call-text-wrapper">' . $call_text . $phone_text .'</a></div>';
		$phone = $phone_text ? $phone_link : '';
		$details = $contact_details ? '<div class="naedu-appointment-details">'.$contact_details.'</div>' : '';

		$btn_text = $btn_text ? $btn_text : '';
		$btn_link = $btn_link ? '<div class="naedu-appointment-button"><a href="'.esc_url($btn_link).'" '.$btn_link_attr.'>'.$btn_text.'</a></div>' : '<div class="naedu-appointment-button">'.$btn_text.'</div>';
		$btn = $btn_text ? $btn_link : '';

		$times = '';
		if ( is_array( $appointment_times ) && !empty( $appointment_times ) ){
			$times .= '<div class="naedu-appointment-items">';
			foreach($appointment_times as $item){
				$times .= '<div class="naedu-appointment-item">';
					$times .= '<div class="naedu-appointment-item-left">'.$item['title'].'</div>';
					$times .= '<div class="naedu-appointment-item-right">'.$item['time'].'</div>';
				$times .= '</div>';
			}
			$times .= '</div>';
		}

		?>
		<div class="naedu-appointment">
			<?php echo $icon . $phone . $details . $title . $times . $btn; ?>
		</div>
		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Appointment() );
} // enable & disable
