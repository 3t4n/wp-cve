<?php
namespace Skt_Addons_Elementor\Elementor\Extension\Conditions;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Day
 * contain all element of day condition
 * @package Skt_Addons_Elementor\Elementor\Extension\Conditions
 */
class Day  extends Condition {

	/**
	 * Get Condition Key
	 *
	 * @return string|void
	 */
	public function get_key_name() {
		return 'day';
	}

	/**
	 * Get Condition Title
	 *
	 * @return string|void
	 */
	public function get_title() {
		return __( 'Day', 'skt-addons-elementor' );
	}

	/**
	 * Get Repeater Control Field Value
	 *
	 * @param array $condition
	 * @return array|void
	 */
	public function get_repeater_control(array $condition) {
		return[
			'label' 		=> $this->get_title(),
			'show_label' 	=> false,
			'type' => Controls_Manager::SELECT,
			'default' => 'monday',
			'label_block' => true,
			'options' => [
				'monday'    => __( 'Monday', 'skt-addons-elementor' ),
				'tuesday'   => __( 'Tuesday', 'skt-addons-elementor' ),
				'wednesday' => __( 'Wednesday', 'skt-addons-elementor' ),
				'thursday'  => __( 'Thursday', 'skt-addons-elementor' ),
				'friday'    => __( 'Friday', 'skt-addons-elementor' ),
				'saturday'  => __( 'Saturday', 'skt-addons-elementor' ),
				'sunday'    => __( 'Sunday', 'skt-addons-elementor' ),
			],
			'condition'	=> $condition,
		];
	}

	/**
	 * Compare Condition value
	 *
	 * @param $settings
	 * @param $operator
	 * @param $value
	 * @return bool|void
	 */
	public function compare_value ( $settings, $operator, $value ) {

		$today = sktaddonselementorextra_get_server_time('l');
		if( 'local' === $settings['_skt_addons_elementor_time_zone'] ){
			$today = sktaddonselementorextra_get_local_time('l');
		}

		return sktaddonselementorextra_compare( strtolower($today), $value, $operator );
	}
}