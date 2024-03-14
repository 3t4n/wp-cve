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

class wptsafExtensionNetworkMonitorValidator{

	public function __construct(){
		add_action('wptsaf_security_init_validator', array($this, 'initValidator'));
	}


	public function initValidator(wptsafValidator $validator){
		$validator->addValidation('network_monitor_lock_duration', array($this, 'validationLockDuration'));
	}


	public function validationLockDuration($value){
		if ('' === $value) {
			return __('The field is required', 'wptsaf_security');
		}
		if (!is_int($value)) {
			return __('The value has to be integer', 'wptsaf_security');
		}
		if (0 > $value) {
			return __("The value has to be 0 or great then 0", 'wptsaf_security');
		}

		return null;
	}
}
