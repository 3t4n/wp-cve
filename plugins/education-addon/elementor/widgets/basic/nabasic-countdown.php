<?php
/*
 * Elementor Education Addon Countdown Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_countdown'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Countdown extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_countdown';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Countdown', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-countdown';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Countdown widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'countdown_labels',
			[
				'label' => esc_html__( 'Countdown Options', 'education-addon' ),
			]
		);
		$this->add_control(
			'deal_date',
			[
				'label' => esc_html__( 'Set End Date & Time', 'education-addon' ),
				'type' => Controls_Manager::DATE_TIME,
				'picker_options' => [
					'dateFormat' => 'Y-m-d H:i:S',
					'enableTime' => 'true',
					'enableSeconds' => 'true',
				],
				'placeholder' => esc_html__( 'yyyy-mm-dd hh:mm:ss', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'tc_animation',
			[
				'label' => esc_html__( 'Animation', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'smooth' => esc_html__( 'Smooth', 'education-addon' ),
					'ticks' => esc_html__( 'Ticks', 'education-addon' ),
				],
				'default' => 'smooth',
				'description' => esc_html__( 'Select your style.', 'education-addon' ),
			]
		);
		$this->add_responsive_control(
			'start_angle',
			[
				'label' => esc_html__( 'Start Angle', 'education-addon' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 360,
				'step' => 1,
				'default' => 0.01,
			]
		);
		$this->add_control(
			'direction',
			[
				'label' => esc_html__( 'Direction', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'Clockwise' => esc_html__( 'Clockwise', 'education-addon' ),
					'Counter-clockwise' => esc_html__( 'Counter Clockwise', 'education-addon' ),
				],
				'default' => 'Clockwise',
				'description' => esc_html__( 'Select your style.', 'education-addon' ),
			]
		);
		$this->add_control(
			'circ_bg_color',
			[
				'label' => esc_html__( 'Circle Background Color', 'education-addon' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f8f9fa',
			]
		);
		$this->add_responsive_control(
			'fg_width',
			[
				'label' => esc_html__( 'Foreground Circle Width', 'education-addon' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0.01,
				'max' => 10,
				'step' => 0.01,
				'default' => 0.03,
			]
		);
		$this->add_responsive_control(
			'bg_width',
			[
				'label' => esc_html__( 'Background Circle Width', 'education-addon' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0.01,
				'max' => 10,
				'step' => 0.01,
				'default' => 1,
			]
		);
		$this->add_control(
			'days_optn',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><b>Days Option</b></div>',
			]
		);
		$this->add_control(
			'day_show',
			[
				'label' => esc_html__( 'Show Days?', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'default' => 'false',
			]
		);
		$this->add_control(
			'day_text',
			[
				'label' => esc_html__( 'Days Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Days', 'education-addon' ),
			]
		);
		$this->add_control(
			'day_color',
			[
				'label' => esc_html__( 'Circle Color', 'education-addon' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffba00',
			]
		);

		$this->add_control(
			'hrs_optn',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><b>Hours Option</b></div>',
			]
		);
		$this->add_control(
			'hr_show',
			[
				'label' => esc_html__( 'Show Hours?', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->add_control(
			'hr_text',
			[
				'label' => esc_html__( 'Hours Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Hours', 'education-addon' ),
			]
		);
		$this->add_control(
			'hr_color',
			[
				'label' => esc_html__( 'Circle Color', 'education-addon' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffba00',
			]
		);

		$this->add_control(
			'mins_optn',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><b>Minutes Option</b></div>',
			]
		);
		$this->add_control(
			'min_show',
			[
				'label' => esc_html__( 'Show Minutes?', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->add_control(
			'min_text',
			[
				'label' => esc_html__( 'Minutes Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Minutes', 'education-addon' ),
			]
		);
		$this->add_control(
			'min_color',
			[
				'label' => esc_html__( 'Circle Color', 'education-addon' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffba00',
			]
		);

		$this->add_control(
			'secs_optn',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-control-raw-html elementor-panel-alert elementor-panel-alert-warning"><b>Seconds Option</b></div>',
			]
		);
		$this->add_control(
			'sec_show',
			[
				'label' => esc_html__( 'Show Seconds?', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->add_control(
			'sec_text',
			[
				'label' => esc_html__( 'Seconds Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Seconds', 'education-addon' ),
			]
		);
		$this->add_control(
			'sec_color',
			[
				'label' => esc_html__( 'Circle Color', 'education-addon' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffba00',
			]
		);
		$this->add_responsive_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Alignment', 'education-addon' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'education-addon' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'education-addon' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'education-addon' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .naedu-countdown' => 'justify-content: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Countdown widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Countdown query
		$settings = $this->get_settings_for_display();
		$deal_date = !empty( $settings['deal_date'] ) ? $settings['deal_date'] : '';
		$tc_animation = !empty( $settings['tc_animation'] ) ? $settings['tc_animation'] : '';
		$start_angle = !empty( $settings['start_angle'] ) ? $settings['start_angle'] : '';
		$direction = !empty( $settings['direction'] ) ? $settings['direction'] : '';
		$circ_bg_color = !empty( $settings['circ_bg_color'] ) ? $settings['circ_bg_color'] : '';
		$fg_width = !empty( $settings['fg_width'] ) ? $settings['fg_width'] : '';
		$bg_width = !empty( $settings['bg_width'] ) ? $settings['bg_width'] : '';

		$day_show  = ( isset( $settings['day_show'] ) && ( 'true' == $settings['day_show'] ) ) ? true : false;
		$day_text = !empty( $settings['day_text'] ) ? $settings['day_text'] : '';
		$day_color = !empty( $settings['day_color'] ) ? $settings['day_color'] : '';

		$hr_show  = ( isset( $settings['hr_show'] ) && ( 'true' == $settings['hr_show'] ) ) ? true : false;
		$hr_text = !empty( $settings['hr_text'] ) ? $settings['hr_text'] : '';
		$hr_color = !empty( $settings['hr_color'] ) ? $settings['hr_color'] : '';

		$min_show  = ( isset( $settings['min_show'] ) && ( 'true' == $settings['min_show'] ) ) ? true : false;
		$min_text = !empty( $settings['min_text'] ) ? $settings['min_text'] : '';
		$min_color = !empty( $settings['min_color'] ) ? $settings['min_color'] : '';

		$sec_show  = ( isset( $settings['sec_show'] ) && ( 'true' == $settings['sec_show'] ) ) ? true : false;
		$sec_text = !empty( $settings['sec_text'] ) ? $settings['sec_text'] : '';
		$sec_color = !empty( $settings['sec_color'] ) ? $settings['sec_color'] : '';		

		$day_text = $day_text ? esc_html($day_text) : esc_html__('Days','education-addon');
		$hr_text = $hr_text ? esc_html($hr_text) : esc_html__('Hours','education-addon');
		$min_text = $min_text ? esc_html($min_text) : esc_html__('Mins','education-addon');
		$sec_text = $sec_text ? esc_html($sec_text) : esc_html__('Secs','education-addon');

		$tc_animation = $tc_animation ? ' data-animation="'. $tc_animation .'"' : ' data-animation="smooth"';
		$start_angle = $start_angle ? ' data-angle="'. $start_angle .'"' : ' data-angle="0"';
		$direction = $direction ? ' data-direction="'. $direction .'"' : ' data-direction="Clockwise"';
		$circ_bg_color 	= $circ_bg_color ? ' data-bg-color="'. $circ_bg_color .'"' : ' data-bg-color="#f8f9fa"';
		$fg_width = $fg_width ? ' data-fg-width="'. $fg_width .'"' : ' data-fg-width="0.03"';
		$bg_width = $bg_width ? ' data-bg-width="'. $bg_width .'"' : ' data-bg-width="1"';

		$day_show = ('true' == $day_show) ? ' data-day-show="true"' : ' data-day-show="false"';
		$day_text = $day_text ? ' data-day-text="'. $day_text .'"' : ' data-day-text="Days"';
		$day_color = $day_color ? ' data-day-color="'. $day_color .'"' : ' data-day-color="#ffba00"';

		$hr_show 	= ('true' == $hr_show) ? ' data-hr-show="true"' : ' data-hr-show="false"';
		$hr_text 	= $hr_text ? ' data-hr-text="'. $hr_text .'"' : ' data-hr-text="Hours"';
		$hr_color = $hr_color ? ' data-hr-color="'. $hr_color .'"' : ' data-hr-color="#ffba00"';

		$min_show = ('true' == $min_show) ? ' data-min-show="true"' : ' data-min-show="false"';
		$min_text = $min_text ? ' data-min-text="'. $min_text .'"' : ' data-min-text="Minutes"';
		$min_color = $min_color ? ' data-min-color="'. $min_color .'"' : ' data-min-color="#ffba00"';

		$sec_show = ('true' == $sec_show) ? ' data-sec-show="true"' : ' data-sec-show="false"';
		$sec_text = $sec_text ? ' data-sec-text="'. $sec_text .'"' : ' data-sec-text="Seconds"';
		$sec_color = $sec_color ? ' data-sec-color="'. $sec_color .'"' : ' data-sec-color="#ffba00"';

		$output = '<div class="naedu-countdown countdown-style-two">';
			if ($deal_date) {
      	$output .= '<div class="naedu-timecircles" data-date="'.esc_attr($deal_date).'" '.$tc_animation.$start_angle.$direction.$circ_bg_color.$fg_width.$bg_width.$day_show.$day_text.$day_color.$hr_show.$hr_text.$hr_color.$min_show.$min_text.$min_color.$sec_show.$sec_text.$sec_color.'></div>';
      }
		$output .= '</div>';
		if ( Plugin::$instance->editor->is_edit_mode() ) : ?>
		<script type="text/javascript">
	    jQuery(document).ready(function($) {
				$('.naedu-timecircles').each( function() {
			    var $TimeCC = $(this);

			    var tc_animation = ($TimeCC.data('animation') !== undefined) ? $TimeCC.data('animation') : "smooth";
			    var tc_angle = ($TimeCC.data('angle') !== undefined) ? $TimeCC.data('angle') : 0;
			    var tc_direction = ($TimeCC.data('direction') !== undefined) ? $TimeCC.data('direction') : "Clockwise";
			    var tc_bg_color = ($TimeCC.data('bg-color') !== undefined) ? $TimeCC.data('bg-color') : "#f8f9fa";
			    var tc_fg_width = ($TimeCC.data('fg-width') !== undefined) ? $TimeCC.data('fg-width') : 0.03;
			    var tc_bg_width = ($TimeCC.data('bg-width') !== undefined) ? $TimeCC.data('bg-width') : 1;

			    var day_show = ($TimeCC.data('day-show') !== undefined) ? $TimeCC.data('day-show') : false;
			    var day_text = ($TimeCC.data('day-text') !== undefined) ? $TimeCC.data('day-text') : "Days";
			    var day_color = ($TimeCC.data('day-color') !== undefined) ? $TimeCC.data('day-color') : "#ffba00";

			    var hr_show = ($TimeCC.data('hr-show') !== undefined) ? $TimeCC.data('hr-show') : true;
			    var hr_text = ($TimeCC.data('hr-text') !== undefined) ? $TimeCC.data('hr-text') : "Hours";
			    var hr_color = ($TimeCC.data('hr-color') !== undefined) ? $TimeCC.data('hr-color') : "#ffba00";

			    var min_show = ($TimeCC.data('min-show') !== undefined) ? $TimeCC.data('min-show') : true;
			    var min_text = ($TimeCC.data('min-text') !== undefined) ? $TimeCC.data('min-text') : "Minutes";
			    var min_color = ($TimeCC.data('min-color') !== undefined) ? $TimeCC.data('min-color') : "#ffba00";

			    var sec_show = ($TimeCC.data('sec-show') !== undefined) ? $TimeCC.data('sec-show') : true;
			    var sec_text = ($TimeCC.data('sec-text') !== undefined) ? $TimeCC.data('sec-text') : "Seconds";
			    var sec_color = ($TimeCC.data('sec-color') !== undefined) ? $TimeCC.data('sec-color') : "#ffba00";

			    $TimeCC.TimeCircles ({
			      animation: tc_animation,
			      start_angle: tc_angle,
			      direction: tc_direction,
			      circle_bg_color: tc_bg_color,
			      fg_width: tc_fg_width,
			      bg_width: tc_bg_width,
			      count_past_zero: false,
			      time: {
			        Days: {
			          show:day_show,
			          text:day_text,
			          color:day_color
			        },
			        Hours: {
			          show:hr_show,
			          text:hr_text,
			          color:hr_color
			        },
			        Minutes: {
			          show:min_show,
			          text:min_text,
			          color:min_color
			        },
			        Seconds: {
			          show:sec_show,
			          text:sec_text,
			          color:sec_color
			        }
			      }
			    });
			  });
		  });
		</script>
		<?php endif;
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Countdown() );

} // enable & disable
