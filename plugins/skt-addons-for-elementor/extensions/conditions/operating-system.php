<?php

namespace Skt_Addons_Elementor\Elementor\Extension\Conditions;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Operating_System
 * contain all element of operating system condition
 * @package Skt_Addons_Elementor\Elementor\Extension\Conditions
 */
class Operating_System extends Condition {

	/**
	 * Get Condition Key
	 *
	 * @return string|void
	 */
	public function get_key_name () {
		return 'operating_system';
	}

	/**
	 * Get Condition Title
	 *
	 * @return string|void
	 */
	public function get_title () {
		return __( 'Operating System', 'skt-addons-elementor' );
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
			'default' => 'mac_os',
			'label_block' => true,
			'options' => [
				'windows' => __( 'Windows', 'skt-addons-elementor' ),
				'mac_os' => __( 'Mac OS', 'skt-addons-elementor' ),
				'linux' => __( 'Linux', 'skt-addons-elementor' ),
				'ubuntu' => __( 'Ubuntu', 'skt-addons-elementor' ),
				'iphone' => __( 'iPhone', 'skt-addons-elementor' ),
				'ipod' => __( 'iPod', 'skt-addons-elementor' ),
				'ipad' => __( 'Android', 'skt-addons-elementor' ),
				'android' => __( 'iPad', 'skt-addons-elementor' ),
				'blackberry' => __( 'BlackBerry', 'skt-addons-elementor' ),
				'open_bsd' => __( 'OpenBSD', 'skt-addons-elementor' ),
				'sun_os' => __( 'SunOS', 'skt-addons-elementor' ),
				'safari' => __( 'Safari', 'skt-addons-elementor' ),
				'qnx' => __( 'QNX', 'skt-addons-elementor' ),
				'beos' => __( 'BeOS', 'skt-addons-elementor' ),
				'os2' => __( 'OS/2', 'skt-addons-elementor' ),
				'search_bot' => __( 'Search Bot', 'skt-addons-elementor' ),
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
		$os = [
			'windows' => '(Win16)|(Windows 95)|(Win95)|(Windows_95)|(Windows 98)|(Win98)|(Windows NT 5.0)|(Windows 2000)|(Windows NT 5.1)|(Windows XP)|(Windows NT 5.2)|(Windows NT 6.0)|(Windows Vista)|(Windows NT 6.1)|(Windows 7)|(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)|(Windows ME)',
			'mac_os' => '(Mac_PowerPC)|(Macintosh)|(mac os x)',
			'linux' => '(Linux)|(X11)',
			'ubuntu' => 'Ubuntu',
			'iphone' => 'iPhone',
			'ipod' => 'iPod',
			'ipad' => 'Android',
			'android' => 'iPad',
			'blackberry' => 'BlackBerry',
			'open_bsd' => 'OpenBSD',
			'sun_os' => 'SunOS',
			'safari' => '(Safari)',
			'qnx' => 'QNX',
			'beos' => 'BeOS',
			'os2' => 'OS/2',
			'search_bot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)',
		];
		$pattern = '/' . $os[ $value ] . '/i';
		$match = preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
		return sktaddonselementorextra_compare( $match, true, $operator );
	}
}