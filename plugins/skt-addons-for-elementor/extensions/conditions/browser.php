<?php

namespace Skt_Addons_Elementor\Elementor\Extension\Conditions;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Browser
 * contain all element of browser condition
 * @package Skt_Addons_Elementor\Elementor\Extension\Conditions
 */
class Browser extends Condition {

	/**
	 * Get Condition Key
	 *
	 * @return string|void
	 */
	public function get_key_name () {
		return 'browser';
	}

	/**
	 * Get Condition Title
	 *
	 * @return string|void
	 */
	public function get_title () {
		return __( 'Browser', 'skt-addons-elementor' );
	}

	/**
	 * Get Repeater Control Field Value
	 *
	 * @param array $condition
	 * @return array|void
	 */
	public function get_repeater_control ( array $condition ) {
		return [
			'label' => $this->get_title(),
			'show_label' => false,
			'type' => Controls_Manager::SELECT,
			'default' => 'chrome',
			'label_block' => true,
			'options' 		=> [
				'opera'			=> __( 'Opera', 'skt-addons-elementor' ),
				'edge'			=> __( 'Edge', 'skt-addons-elementor' ),
				'chrome'		=> __( 'Google Chrome', 'skt-addons-elementor' ),
				'safari'		=> __( 'Safari', 'skt-addons-elementor' ),
				'firefox'		=> __( 'Mozilla Firefox', 'skt-addons-elementor' ),
				'ie'			=> __( 'Internet Explorer', 'skt-addons-elementor' ),
				'others'			=> __( 'Others', 'skt-addons-elementor' ),
			],
			'condition' => $condition,
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
		$user_agent = sktaddonselementorextra_get_browser_name( $_SERVER['HTTP_USER_AGENT'] );
		//if $user_agent and $value is equal it return true
		return sktaddonselementorextra_compare( $user_agent, $value, $operator );
	}
}