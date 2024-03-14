<?php

namespace IfSo\PublicFace\Services\TriggerValidationService;

require_once(IFSO_PLUGIN_BASE_DIR . 'services/license-service/license-service.class.php');

use IfSo\Services\LicenseService;

class TriggerValidationService {
	private static $instance;

	private $free_triggers;

	private function __construct() {
		$this->free_triggers = array("Device", "User-Behavior", "Geolocation", "UserIp", "Time-Date");
	}

	public static function get_instance() {
		if ( NULL == self::$instance )
			self::$instance = new TriggerValidationService();

		return self::$instance;
	}

	public function is_valid($rule) {
		$is_license_valid = LicenseService\LicenseService::get_instance()->is_license_valid();
		$trigger_type = $rule['trigger_type'];

		if ( !$is_license_valid && 
			 !in_array($trigger_type, $this->free_triggers) ) {
			 return false;
		}
		
		if ( !$is_license_valid && 
			  $trigger_type == "User-Behavior" &&
			 !in_array($rule['User-Behavior'], array("LoggedIn", "LoggedOut", "Logged"))) {
			return false;
	}

		return true;
	}
}