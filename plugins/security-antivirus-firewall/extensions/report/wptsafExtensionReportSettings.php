<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

class wptsafExtensionReportSettings extends wptsafSettings{

	protected $frequencyRunning;

	public function __construct(wptsafAbstractExtension $extension){
		$this->frequencyRunning = array(
			'24H' => __('per 24 hour', 'wptsaf_security'),
			'1W' => __('per week', 'wptsaf_security'),
			'1M' => __('per month', 'wptsaf_security'),
		);

		$this->optionKey = WPTSAF_OPTION_KEY_PREFIX . 'report_settings';
		$this->defaultOptions = array(
			'is_enabled' => false,
			'frequency_running' => '24H',
			'time_running' => array(
				'h' => 23,
				'm' => 0
			),
			'letter_title' => __('S.A.F. - wpTools Security Report ', 'wptsaf_security'),
		);

		parent::__construct($extension);
	}

	public function getFrequencyRunning(){
		return $this->frequencyRunning;
	}
}
