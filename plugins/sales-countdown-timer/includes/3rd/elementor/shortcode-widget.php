<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class SALES_COUNTDOWN_TIMER_Elementor_Widget extends Elementor\Widget_Base {

	public static $slug = 'visct-elementor-reviews-widget';

	public function get_name() {
		return 'sales-countdown-timer';
	}

	public function get_title() {
		return esc_html__( 'Sales Countdown Timer', 'sales-countdown-timer' );
	}

	public function get_icon() {
		return 'fas fa-clock';
	}

	public function get_categories() {
		return [ 'wordpress' ];
	}

	protected function _register_controls() {
		$settings      = new SALES_COUNTDOWN_TIMER_Data();
		$ids           = $settings->get_id();
		$available_ids = array();
		if ( $ids && is_array( $ids ) && ! empty( $ids ) ) {
			foreach ( $ids as $k => $id ) {
				$available_ids[ $id ] = $settings->get_names()[ $k ] ?? '';
			}
		}
		$this->start_controls_section(
			'general',
			[
				'label' => esc_html__( 'General', 'sales-countdown-timer' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'profile_id',
			[
				'label'   => esc_html__( 'Countdown Profile', 'sales-countdown-timer' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'salescountdowntimer',
				'options' => $available_ids,
			]
		);
		$this->add_control(
			'sale_from',
			[
				'label'   => esc_html__( 'From', 'sales-countdown-timer' ),
				'type'    => \Elementor\Controls_Manager::DATE_TIME,
				'default' => date( "Y-m-d H:i", current_time( 'timestamp' ) ),
			]
		);
		$this->add_control(
			'sale_to',
			[
				'label'   => esc_html__( 'To', 'sales-countdown-timer' ),
				'type'    => \Elementor\Controls_Manager::DATE_TIME,
				'default' => date( "Y-m-d H:i", current_time( 'timestamp' ) + 30 * 86400 ),
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'design',
			[
				'label' => esc_html__( 'Design', 'sales-countdown-timer' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'message',
			[
				'label'       => esc_html__( 'Message', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => 'Hurry Up! Offer ends in {countdown_timer}',
				'description' => esc_html__( 'The countdown timer will not show if message does not include {countdown_timer}', 'sales-countdown-timer' ),
			]
		);
		$this->add_control(
			'time_separator',
			[
				'label'       => esc_html__( 'Time separator', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'blank',
				'options'     => [
					'blank' => esc_html__( 'Blank', 'sales-countdown-timer' ),
					'colon' => esc_html__( 'Colon(:)', 'sales-countdown-timer' ),
					'comma' => esc_html__( 'Comma(,)', 'sales-countdown-timer' ),
					'dot'   => esc_html__( 'Dot(.)', 'sales-countdown-timer' ),
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'datetime_format',
			[
				'label'       => esc_html__( 'Datetime format style', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '1',
				'options'     => [
					'1' => esc_html__( '01 days 02 hrs 03 mins 04 secs', 'sales-countdown-timer' ),
					'2' => esc_html__( '01 days 02 hours 03 minutes 04 seconds', 'sales-countdown-timer' ),
					'3' => esc_html__( '01:02:03:04', 'sales-countdown-timer' ),
					'4' => esc_html__( '01d:02h:03m:04s', 'sales-countdown-timer' ),
				],
				'label_block' => true
			]
		);
		$this->end_controls_section();

	}

	protected function register_controls() {
		$this->_register_controls();
	}

	protected function render() {
		$settings  = $this->get_settings_for_display();
		$shortcode = $this->visct_get_shortcode( $settings );
		$shortcode = do_shortcode( shortcode_unautop( $shortcode ) );
		echo $shortcode;
	}

	public function render_plain_content() {
		$settings  = $this->get_settings_for_display();
		$shortcode = $this->visct_get_shortcode( $settings );
		echo wp_kses_post( $shortcode );
	}

	private function visct_get_shortcode( $settings ) {
		$sale_from      = $settings['sale_from'] ?? '';
		$sale_from      = $sale_from ? explode( ' ', $sale_from ) : array();
		$sale_from_date = $sale_from[0] ?? '';
		$sale_from_time = $sale_from[1] ?? '';
		$sale_to        = $settings['sale_to'] ?? '';
		$sale_to        = $sale_from ? explode( ' ', $sale_to ) : array();
		$sale_to_date   = $sale_to[0] ?? '';
		$sale_to_time   = $sale_to[1] ?? '';
		$shortcode      = "[sales_countdown_timer id='{$settings['profile_id']}' active='1'
		 sale_from_date='{$sale_from_date}' sale_from_time='{$sale_from_time}' sale_to_date='{$sale_to_date}' sale_to_time='{$sale_to_time}' 
		 message='{$settings['message']}' time_separator='{$settings['time_separator']}' datetime_format='{$settings['datetime_format']}']";

		return $shortcode;
	}
}