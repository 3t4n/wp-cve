<?php

namespace Element_Ready\Widgets\themes;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elements_Ready_Theme_Today_Date extends Widget_Base {

	use \Elementor\Element_Ready_Common_Style;
    use \Elementor\Element_ready_common_content;
    use \Elementor\Element_Ready_Box_Style;

	public function get_name() {
		return 'Elements_Ready_Theme_Today_Date';
	}

	public function get_title() {
		return esc_html__( 'ER Today Date', 'element-ready-lite' );
	}

	public function get_script_depends() {

		return[
			'element-ready-core',
		];
	}

	public function get_style_depends() {

		wp_register_style( 'eready-current-date' , ELEMENT_READY_ROOT_CSS.'widgets/today-date.min.css' );
		
		return[
			'eready-current-date'
		];
	}


	public function get_icon() {
		return 'eicon-date';
	}

	public function get_categories() {
		return array('element-ready-addons');
	}

    public function get_keywords() {
        return [ 'today', 'date', 'today date', 'er date' ];
    }

	protected function register_controls() {

	
		$this->start_controls_section(
			'section_Settings',
			[
				'label' => esc_html__( 'Settings', 'element-ready-lite' ),
			]
		);

			$this->add_control(
				'more_date_time_options',
				[
					'label' => esc_html__( 'Time', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
		    
			$this->add_control(
				'timer_show',
				[
					'label' => esc_html__( 'Real Time ?', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'element-ready-lite' ),
					'label_off' => esc_html__( 'No', 'element-ready-lite' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'timer_amp_pm_show',
				[
					'label' => esc_html__( 'AMP/PM?', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'element-ready-lite' ),
					'label_off' => esc_html__( 'No', 'element-ready-lite' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'timer_second_show',
				[
					'label' => esc_html__( 'Second?', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'element-ready-lite' ),
					'label_off' => esc_html__( 'No', 'element-ready-lite' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'more_date_options',
				[
					'label' => esc_html__( 'Date', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_control(
				'override',
				[
					'label' => esc_html__( 'Override Date Format', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'element-ready-lite' ),
					'label_off' => esc_html__( 'No', 'element-ready-lite' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);

			$this->add_control(
				'date_format',
				[
					'label' => esc_html__( 'Date Format', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'F j, Y g:i a',
					'options' => [
						'F j, Y g:i a'     => esc_html__( 'F j, Y g:i a', 'element-ready-lite' ),
						'F j, Y '          => esc_html__( 'F j, Y ', 'element-ready-lite' ),
						'F, Y'             => esc_html__( 'F, Y', 'element-ready-lite' ),
						'g:i a'            => esc_html__( 'g:i a', 'element-ready-lite' ),
						'g:i:s a'          => esc_html__( 'g:i:s a', 'element-ready-lite' ),
						'l, F jS, Y'       => esc_html__( 'l, F jS, Y', 'element-ready-lite' ),
						'Y/m/d \a\t g:i A' => esc_html__( 'Y/m/d \a\t g:i A', 'element-ready-lite' ),
						'Y/m/d \a\t g:ia'  => esc_html__( 'Y/m/d \a\t g:ia', 'element-ready-lite' ),
						'Y/m/d g:i:s A'    => esc_html__( 'Y/m/d g:i:s A', 'element-ready-lite' ),
						'Y/m/d'            => esc_html__( 'Y/m/d', 'element-ready-lite' )
					],
					
				]
			);

			$this->add_control(
				'date_custom_date_format',
				[
					'label'       => esc_html__( 'Custom Date Format', 'element-ready-lite' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'default'     => 'F j, Y',
					'placeholder' => esc_html__( 'F j, Y g:i:s a', 'element-ready-lite' ),
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_date_time_section',
			[
				'label' => esc_html__( 'Time Style', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'_time_text_color',
				[
					'label' => esc_html__( 'Color', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .er-clock' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'time_content_typography',
					'selector' => '{{WRAPPER}} .er-clock',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'time_timetext_shadow',
					'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .er-clock',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'time_flex_border_wrapper_border',
					'label' => esc_html__( 'Border', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .er-clock',
				]
			);

			$this->add_control(
				'timeclock_wrapper_padding',
				[
					'label' => esc_html__( 'Padding', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .er-clock' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'am_pm_more_options',
				[
					'label' => esc_html__( 'Additional AM /PM', 'textdomain' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'apm_pm_time_text_color',
				[
					'label' => esc_html__( 'AM/PM Color', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .er-clock span' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'ampm_time_content_typography',
					'selector' => '{{WRAPPER}} .er-clock span',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'ampm_timetext_shadow',
					'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .er-clock span',
				]
			);

			$this->add_responsive_control(
				'amp_position_left',
				[
					'label' => esc_html__( 'Position Left', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => -200,
							'max' => 200,
							'step' => 1,
						],
						'%' => [
							'min' => -100,
							'max' => 100,
						],
					],
					
					'selectors' => [
						'{{WRAPPER}} .er-clock-date-wrapper .er-clock span' => 'left: {{SIZE}}{{UNIT}};',
					],
			
				]
			);

			$this->add_responsive_control(
				'amp_position_top',
				[
					'label' => esc_html__( 'Position Top', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => -200,
							'max' => 200,
							'step' => 1,
						],
						'%' => [
							'min' => -100,
							'max' => 100,
						],
					],
					
					'selectors' => [
						'{{WRAPPER}} .er-clock-date-wrapper .er-clock span' => 'top: {{SIZE}}{{UNIT}};',
					],
			
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_date_section',
			[
				'label' => esc_html__( 'Date Style', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'text_color',
				[
					'label' => esc_html__( 'Text Color', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .er-date' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .er-date',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'date_text_shadow',
					'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .er-date',
				]
			);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_date_wrapper_section',
			[
				'label' => esc_html__( 'Wrapper', 'element-ready-lite' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'flex_width_gap',
				[
					'label' => esc_html__( 'Gap', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 10,
					],
					'selectors' => [
						'{{WRAPPER}} .er-clock-date-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'timer_show' => ['yes']
					]

				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'flex_width_wrapper_background',
					'label' => esc_html__( 'Background', 'element-ready-lite' ),
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .er-clock-date-wrapper',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'flex_width_wrapper_border',
					'label' => esc_html__( 'Border', 'element-ready-lite' ),
					'selector' => '{{WRAPPER}} .er-clock-date-wrapper',
				]
			);

			$this->add_control(
				'flex_width_wrapper_padding',
				[
					'label' => esc_html__( 'Padding', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .er-clock-date-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'_section_align_section_e__flex_align',
				[
					'label' => esc_html__( 'Alignment', 'element-ready-lite' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [

						'flex-start'    => esc_html__( 'Left', 'element-ready-lite' ),
						'flex-end'      => esc_html__( 'Right', 'element-ready-lite' ),
						'center'        => esc_html__( 'Center', 'element-ready-lite' ),
						''              => esc_html__( 'inherit', 'element-ready-lite' ),
					],

					'selectors' => [
						'{{WRAPPER}} .er-clock-date-wrapper' => 'align-items: {{VALUE}};'
				],
				]
				
			);

		$this->end_controls_section();

	}
	
	protected function render() {

		$settings          = $this->get_settings_for_display();
		$date_format       = $settings['date_format'];
		$timer_show        = $settings['timer_show'];
		$amp_pm            = $settings['timer_amp_pm_show'];
		$timer_second_show = $settings['timer_second_show'];
		if($settings[ 'override' ] == 'yes' && $settings[ 'date_custom_date_format' ] !=''){
			$date_format = $settings[ 'date_custom_date_format' ];
		}
        
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode() ? 'yes' : 'no';
      ?>
	   
		<div 
			data-is_editor="<?php echo esc_attr($is_editor); ?>" 
			data-second="<?php echo esc_attr($timer_second_show); ?>" 
			data-ampm="<?php echo esc_attr($amp_pm); ?>" 
			data-enable="<?php echo esc_attr($timer_show); ?>" 
			class="er-clock-date-wrapper">
			<?php if($timer_show == 'yes'): ?>
				<div class="er-clock"></div>
			<?php endif; ?>
			<div class="er-date"> <?php echo wp_kses_post( wp_date( $date_format ) ); ?> </div>
		</div>
		
		
	  <?php
	}	
}
