<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Countdown_Timer_Elementor_Widget extends Widget_Base {

	public function get_name() { 		//Function for get the slug of the element name.
		return 'countdown-timer-widget';
	}

	public function get_title() { 		//Function for get the name of the element.
		return __( 'Countdown Timer', 'countdown-timer-widget' );
	}

	public function get_icon() { 		//Function for get the icon of the element.
		return 'eicon-countdown';
	}
	
	public function get_categories() { 		//Function for include element into the category.
		return [ 'elpug-elements' ];
	}
	
    /* 
	 * Adding the controls fields for the countdown timer
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'elpug_section',
			[
				'label' => __( 'Countdown', TEXTDOMAIN ),
			]
		);
	    $this->add_control(
			'elpug_due_date',
			[
				'label' => __( 'Due Date', TEXTDOMAIN ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => date( 'Y-m-d H:i', strtotime( '+1 month' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				'description' => sprintf( __( 'Date set according to your timezone: %s.', TEXTDOMAIN ), Utils::get_timezone_string() ),
				
			]
		);
		$this->add_control(
			'elpug_show_days',
			[
				'label' => __( 'Days', TEXTDOMAIN ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', TEXTDOMAIN ),
				'label_off' => __( 'Hide', TEXTDOMAIN ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'elpug_show_hours',
			[
				'label' => __( 'Hours', TEXTDOMAIN ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', TEXTDOMAIN ),
				'label_off' => __( 'Hide', TEXTDOMAIN ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'elpug_show_minutes',
			[
				'label' => __( 'Minutes', TEXTDOMAIN ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', TEXTDOMAIN ),
				'label_off' => __( 'Hide', TEXTDOMAIN ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'elpug_show_seconds',
			[
				'label' => __( 'Seconds', TEXTDOMAIN ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', TEXTDOMAIN ),
				'label_off' => __( 'Hide', TEXTDOMAIN ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->end_controls_section(); 
		
		$this->start_controls_section(
			'elpug_expire_section',
			[
				'label' => __( 'Countdown Expire' , TEXTDOMAIN )
			]
		);
		
		$this->add_control(
			'elpug_expire_message',
			[
				'label'			=> __('Expire Message', TEXTDOMAIN),
				'type'			=> Controls_Manager::TEXTAREA,
				'default'		=> __('Sorry you are late!',TEXTDOMAIN),
				'condition'		=> [
					'elpug_expire_show_type' => 'message'
				]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'elpug_label_text_section',
			[
				'label' => __( 'Change Labels Text' , TEXTDOMAIN )
			]
		);
        $this->add_control(
			'elpug_change_labels',
			[
				'label' => __( 'Change Labels', TEXTDOMAIN ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', TEXTDOMAIN ),
				'label_off' => __( 'No', TEXTDOMAIN ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$this->add_control(
			'elpug_label_days',
			[
				'label' => __( 'Days', TEXTDOMAIN ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Days', TEXTDOMAIN ),
				'placeholder' => __( 'Days', TEXTDOMAIN ),
				'condition' => [
					'elpug_change_labels' => 'yes',
					'elpug_show_days' => 'yes',
				],
			]
		);
		$this->add_control(
			'elpug_label_hours',
			[
				'label' => __( 'Hours', TEXTDOMAIN ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Hours', TEXTDOMAIN ),
				'placeholder' => __( 'Hours', TEXTDOMAIN ),
				'condition' => [
					'elpug_change_labels' => 'yes',
					'elpug_show_hours' => 'yes',
				],
			]
		);
		$this->add_control(
			'elpug_label_minuts',
			[
				'label' => __( 'Minutes', TEXTDOMAIN ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Minutes', TEXTDOMAIN ),
				'placeholder' => __( 'Minutes', TEXTDOMAIN ),
				'condition' => [
					'elpug_change_labels' => 'yes',
					'elpug_show_minutes' => 'yes',
				],
			]
		);
		$this->add_control(
			'elpug_label_seconds',
			[
				'label' => __( 'Seconds', TEXTDOMAIN ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Seconds', TEXTDOMAIN ),
				'placeholder' => __( 'Seconds', TEXTDOMAIN ),
				'condition' => [
					'elpug_change_labels' => 'yes',
					'elpug_show_seconds' => 'yes',
				],
			]
		);
		$this->end_controls_section();
		
		$this->start_controls_section(   
			'elpug_style_section',
			[
				'label' => __( 'Box', TEXTDOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
            'elpug_box_align',
                [
                    'label'         => esc_html__( 'Alignment', TEXTDOMAIN ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options'       => [
                        'left'      => [
                            'title'=> esc_html__( 'Left', TEXTDOMAIN ),
                            'icon' => 'fa fa-align-left',
                            ],
                        'center'    => [
                            'title'=> esc_html__( 'Center', TEXTDOMAIN ),
                            'icon' => 'fa fa-align-center',
                            ],
                        'right'     => [
                            'title'=> esc_html__( 'Right', TEXTDOMAIN ),
                            'icon' => 'fa fa-align-right',
                            ],
                        ],
                    'toggle'        => false,
                    'default'       => 'center',
                    'selectors'     => [
                        '{{WRAPPER}} .countdown-timer-widget' => 'text-align: {{VALUE}};',
                        ],
                ]
        );
	    $this->add_control(
			'elpug_box_background_color',
			[
				'label' => __( 'Background Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				
				'selectors' => [
					'{{WRAPPER}} .elpug_countdown-items' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);
		$this->add_responsive_control(
			'elpug_box_spacing',
			[
				'label' => __( 'Box Gap', TEXTDOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .elpug_countdown-items:not(:first-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .elpug_countdown-items:not(:last-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elpug_countdown-items:not(:first-of-type)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elpug_countdown-items:not(:last-of-type)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				],
			]
		);
		$this->add_control(
			'elpug_box_width',
			[
				'label' => __( 'Box Width', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 400,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .elpug_countdown-items' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
	            'selector' => '{{WRAPPER}} .elpug_countdown-items',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'elpug_box_border_radius',
			[
				'label' => __( 'Border Radius', TEXTDOMAIN ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elpug_countdown-items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		
		$this->start_controls_section(
			'elpug_digits_style_section',
			[
				'label' => __( 'Digits', TEXTDOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'elpug_digit_background_color',
			[
				'label' => __( 'Background Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				
				'selectors' => [
					'{{WRAPPER}} .elpug_countdown-items .elpug-countdown-digits' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);
		$this->add_control(
			'elpug_digits_color',
			[
				'label' => __( 'Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elpug_countdown-items .elpug-countdown-digits' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eac_digits_typography',
				'selector' => '{{WRAPPER}} .elpug_countdown-items .elpug-countdown-digits',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);
		$this->end_controls_section();   
		
		$this->start_controls_section(
			'elpug_labels_style_section',
			[
				'label' => __( 'Labels', TEXTDOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'elpug_label_background_color',
			[
				'label' => __( 'Background Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				
				'selectors' => [
					'{{WRAPPER}} .elpug_countdown-items .elpug-countdown-label' => 'background-color: {{VALUE}};',
				],
				'separator' => 'after',
			]
		);
		$this->add_control(
			'elpug_label_color',
			[
				'label' => __( 'Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elpug_countdown-items .elpug-countdown-label' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eac_label_typography',
				'selector' => '{{WRAPPER}} .elpug-countdown-label',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);
		$this->end_controls_section();   
		
		$this->start_controls_section(
			'elpug_finish_message_style_section',
			[
				'label' => __( 'Message', TEXTDOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'elpug_message_color',
			[
				'label' => __( 'Color', TEXTDOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .finished-message' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eac_message_typography',
				'selector' => '{{WRAPPER}} .finished-message',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);
		$this->end_controls_section();  
	}
	
	/**
	 * Render countdown timer widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		
		$day = $settings['elpug_show_days'];
		$hours = $settings['elpug_show_hours'];
		$minute = $settings['elpug_show_minutes'];
		$seconds = $settings['elpug_show_seconds'];
		?>
		<div class="countdown-timer-widget">
		    <div id="countdown-timer-<?php echo esc_attr($this->get_id()); ?>" class="countdown-timer-init"></div>
			<div id="finished-message-<?php echo esc_attr($this->get_id()); ?>" class="finished-message"></div>
		</div>
		<script>
			jQuery(function(){
				jQuery('#countdown-timer-<?php echo esc_attr($this->get_id()); ?>').countdowntimer({
                    dateAndTime : "<?php echo preg_replace('/-/', '/', $settings['elpug_due_date']); ?>",
                    regexpMatchFormat: "([0-9]{1,3}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
      				regexpReplaceWith: "<?php if ($day == "yes"){?><div class='elpug_countdown-items'><span class='elpug-countdown-digits'>$1</span><span class='elpug-countdown-label'><?php echo $settings['elpug_label_days']; ?></span> </div><?php } ?><?php if ($hours == "yes"){?> <div class='elpug_countdown-items'><span class='elpug-countdown-digits'>$2 </span><span class='elpug-countdown-label'><?php echo $settings['elpug_label_hours']; ?></span></div><?php } ?><?php if ($minute == "yes"){?><div class='elpug_countdown-items'> <span class='elpug-countdown-digits'> $3 </span><span class='elpug-countdown-label'><?php echo $settings['elpug_label_minuts']; ?></span> </div><?php } ?><?php if ($seconds == "yes"){?><div class='elpug_countdown-items'><span class='elpug-countdown-digits'> $4</span><span class='elpug-countdown-label'><?php echo $settings['elpug_label_seconds']; ?></span></div><?php } ?>",
					timeUp : timeisUp,
                });
               
				function timeisUp(){
					jQuery("#finished-message-<?php echo esc_attr($this->get_id()); ?>").html( "<span><?php echo $settings['elpug_expire_message'];?></span>" );
				}				
			});
		    
        </script>
		<?php
	}

    /**
	 * Render countdown widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() { 
		 
	}	
}
Plugin::instance()->widgets_manager->register_widget_type( new Countdown_Timer_Elementor_Widget() );