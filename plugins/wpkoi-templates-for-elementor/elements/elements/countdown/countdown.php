<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Lite_WPKoi_Elements_Countdown extends Widget_Base {

	public function get_name() {
		return 'wpkoi-elements-countdown';
	}

	public function get_title() {
		return esc_html__( 'Countdown', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-countdown';
	}

   public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}
	
	
	protected function register_controls() {

		
  		$this->start_controls_section(
  			'wpkoi_elements_section_countdown_settings_general',
  			[
  				'label' => esc_html__( 'Countdown Settings', 'wpkoi-elements' )
  			]
  		);
		
		$this->add_control(
			'wpkoi_elements_countdown_due_time',
			[
				'label' => esc_html__( 'Countdown target date', 'wpkoi-elements' ),
				'type' => Controls_Manager::DATE_TIME,
				'default' => date("Y-m-d", strtotime("+ 1 day")),
				'description' => esc_html__( 'Set the target date and time', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_label_view',
			[
				'label' => esc_html__( 'Position', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'wpkoi-elements-countdown-label-block',
				'options' => [
					'wpkoi-elements-countdown-label-block' => esc_html__( 'Block', 'wpkoi-elements' ),
					'wpkoi-elements-countdown-label-inline' => esc_html__( 'Inline', 'wpkoi-elements' ),
				],
			]
		);

		$this->add_responsive_control(
			'wpkoi_elements_countdown_label_padding_left',
			[
				'label' => esc_html__( 'Left spacing', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'description' => esc_html__( 'Use when you select inline labels', 'wpkoi-elements' ),
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-label' => 'padding-left:{{SIZE}}px;',
				],
				'condition' => [
					'wpkoi_elements_countdown_label_view' => 'wpkoi-elements-countdown-label-inline',
				],
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_days',
			[
				'label' => esc_html__( 'Display days', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_days_label',
			[
				'label' => esc_html__( 'Label for days', 'wpkoi-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Days', 'wpkoi-elements' ),
				'condition' => [
					'wpkoi_elements_countdown_days' => 'yes',
				],
			]
		);
		

		$this->add_control(
			'wpkoi_elements_countdown_hours',
			[
				'label' => esc_html__( 'Display hours', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_hours_label',
			[
				'label' => esc_html__( 'Label for hours', 'wpkoi-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hours', 'wpkoi-elements' ),
				'condition' => [
					'wpkoi_elements_countdown_hours' => 'yes',
				],
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_minutes',
			[
				'label' => esc_html__( 'Display minutes', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_minutes_label',
			[
				'label' => esc_html__( 'Label for minutes', 'wpkoi-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Minutes', 'wpkoi-elements' ),
				'condition' => [
					'wpkoi_elements_countdown_minutes' => 'yes',
				],
			]
		);
			
		$this->add_control(
			'wpkoi_elements_countdown_seconds',
			[
				'label' => esc_html__( 'Display seconds', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_seconds_label',
			[
				'label' => esc_html__( 'Label for seconds', 'wpkoi-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Seconds', 'wpkoi-elements' ),
				'condition' => [
					'wpkoi_elements_countdown_seconds' => 'yes',
				],
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_separator_heading',
			[
				'label' => __( 'Countdown separator', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_separator',
			[
				'label' => esc_html__( 'Display separator', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'wpkoi-elements-countdown-show-separator',
				'default' => '',
			]
		);


		$this->end_controls_section();
		
		$this->start_controls_section(
			'wpkoi_elements_section_countdown_styles_general',
			[
				'label' => esc_html__( 'Countdown Styles', 'wpkoi-elements' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'wpkoi_elements_countdown_spacing',
			[
				'label' => esc_html__( 'Space between boxes', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-item > div' => 'margin-right:{{SIZE}}px; margin-left:{{SIZE}}px;',
					'{{WRAPPER}} .wpkoi-elements-countdown-container' => 'margin-right: -{{SIZE}}px; margin-left: -{{SIZE}}px;',
				],
			]
		);
		
		$this->add_responsive_control(
			'wpkoi_elements_countdown_container_margin_bottom',
			[
				'label' => esc_html__( 'Space below container', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-container' => 'margin-bottom:{{SIZE}}px;',
				],
			]
		);
		
		$this->add_responsive_control(
			'wpkoi_elements_countdown_box_padding',
			[
				'label' => esc_html__( 'Padding', 'wpkoi-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-item > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wpkoi_elements_countdown_box_border',
				'label' => esc_html__( 'Border', 'wpkoi-elements' ),
				'selector' => '{{WRAPPER}} .wpkoi-elements-countdown-item > div',
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_box_border_radius',
			[
				'label' => esc_html__( 'Border radius', 'wpkoi-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-item > div' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'wpkoi_elements_section_countdown_styles_content',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'wpkoi-elements' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_box_bg_heading',
			[
				'label' => __( 'Element Background', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		
		$this->add_control(
			'wpkoi_elements_countdown_background',
			[
				'label' => esc_html__( 'Element background color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#111111',
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-item > div' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'wpkoi_elements_countdown_background_day',
			[
				'label' => esc_html__( 'Unique background for days', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,

				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-item .wpkoi-elements-countdown-days' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'wpkoi_elements_countdown_background_hours',
			[
				'label' => esc_html__( 'Unique background for hours', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-item .wpkoi-elements-countdown-hours' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'wpkoi_elements_countdown_background_minutes',
			[
				'label' => esc_html__( 'Unique background for minutes', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-item .wpkoi-elements-countdown-minutes' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'wpkoi_elements_countdown_background_seconds',
			[
				'label' => esc_html__( 'Unique background for seconds', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-item .wpkoi-elements-countdown-seconds' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_digits_heading',
			[
				'label' => __( 'Countdown digits', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_digits_color',
			[
				'label' => esc_html__( 'Digits color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-digits' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'wpkoi_elements_countdown_digit_typography',
				'selector' => '{{WRAPPER}} .wpkoi-elements-countdown-digits',
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_label_heading',
			[
				'label' => __( 'Countdown labels', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_label_color',
			[
				'label' => esc_html__( 'Label color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-label' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'wpkoi_elements_countdown_label_typography',
				'selector' => '{{WRAPPER}} .wpkoi-elements-countdown-label',
			]
		);	

		$this->add_control(
			'wpkoi_elements_countdown_separator_c_heading',
			[
				'label' => __( 'Separator', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'wpkoi_elements_countdown_separator' => 'wpkoi-elements-countdown-show-separator',
				],
			]
		);

		$this->add_control(
			'wpkoi_elements_countdown_separator_color',
			[
				'label' => esc_html__( 'Separator color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'wpkoi_elements_countdown_separator' => 'wpkoi-elements-countdown-show-separator',
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-countdown-digits::after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'wpkoi_elements_countdown_separator_typography',
				'selector' => '{{WRAPPER}} .wpkoi-elements-countdown-digits::after',
				'condition' => [
					'wpkoi_elements_countdown_separator' => 'wpkoi-elements-countdown-show-separator',
				],
			]
		);	


		$this->end_controls_section();
		

	}


	protected function render( ) {
		
      $settings = $this->get_settings();
		
		$get_due_date =  esc_attr($settings['wpkoi_elements_countdown_due_time']);
		$due_date = date("M d Y G:i:s", strtotime($get_due_date));
	?>

	<div class="wpkoi-elements-countdown-wrapper">
		<div class="wpkoi-elements-countdown-container <?php echo esc_attr($settings['wpkoi_elements_countdown_label_view'] ); ?> <?php echo esc_attr($settings['wpkoi_elements_countdown_separator'] ); ?>">		
			<ul id="wpkoi-elements-countdown-<?php echo esc_attr($this->get_id()); ?>" class="wpkoi-elements-countdown-items" data-date="<?php echo esc_attr($due_date) ; ?>">
			    <?php if ( ! empty( $settings['wpkoi_elements_countdown_days'] ) ) : ?><li class="wpkoi-elements-countdown-item"><div class="wpkoi-elements-countdown-days"><span data-days class="wpkoi-elements-countdown-digits">00</span><?php if ( ! empty( $settings['wpkoi_elements_countdown_days_label'] ) ) : ?><span class="wpkoi-elements-countdown-label"><?php echo esc_attr($settings['wpkoi_elements_countdown_days_label'] ); ?></span><?php endif; ?></div></li><?php endif; ?>
			    <?php if ( ! empty( $settings['wpkoi_elements_countdown_hours'] ) ) : ?><li class="wpkoi-elements-countdown-item"><div class="wpkoi-elements-countdown-hours"><span data-hours class="wpkoi-elements-countdown-digits">00</span><?php if ( ! empty( $settings['wpkoi_elements_countdown_hours_label'] ) ) : ?><span class="wpkoi-elements-countdown-label"><?php echo esc_attr($settings['wpkoi_elements_countdown_hours_label'] ); ?></span><?php endif; ?></div></li><?php endif; ?>
			   <?php if ( ! empty( $settings['wpkoi_elements_countdown_minutes'] ) ) : ?><li class="wpkoi-elements-countdown-item"><div class="wpkoi-elements-countdown-minutes"><span data-minutes class="wpkoi-elements-countdown-digits">00</span><?php if ( ! empty( $settings['wpkoi_elements_countdown_minutes_label'] ) ) : ?><span class="wpkoi-elements-countdown-label"><?php echo esc_attr($settings['wpkoi_elements_countdown_minutes_label'] ); ?></span><?php endif; ?></div></li><?php endif; ?>
			   <?php if ( ! empty( $settings['wpkoi_elements_countdown_seconds'] ) ) : ?><li class="wpkoi-elements-countdown-item"><div class="wpkoi-elements-countdown-seconds"><span data-seconds class="wpkoi-elements-countdown-digits">00</span><?php if ( ! empty( $settings['wpkoi_elements_countdown_seconds_label'] ) ) : ?><span class="wpkoi-elements-countdown-label"><?php echo esc_attr($settings['wpkoi_elements_countdown_seconds_label'] ); ?></span><?php endif; ?></div></li><?php endif; ?>
			</ul>
			<div class="clearfix"></div>
		</div>
	</div>


	<script type="text/javascript">
	jQuery(document).ready(function($) {
		'use strict';
		$("#wpkoi-elements-countdown-<?php echo esc_attr($this->get_id()); ?>").countdown();
	});
	</script>
	
	<?php
	
	}
	
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script('wpkoi_elements_elementor-countdown-js',WPKOI_ELEMENTS_LITE_URL.'elements/countdown/assets/countdown.min.js', [ 'elementor-frontend', 'jquery' ],'1.0', true);
	}

	public function get_script_depends() {
		return [ 'wpkoi_elements_elementor-countdown-js' ];
	}

	protected function content_template() {}
}


Plugin::instance()->widgets_manager->register( new Widget_Lite_WPKoi_Elements_Countdown() );