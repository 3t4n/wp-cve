<?php
namespace IfSo\PublicFace\Services\TriggersService\Triggers;
require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once( IFSO_PLUGIN_SERVICES_BASE_DIR . 'device-detection-service/device-detection-service.class.php' );
use IfSo\PublicFace\Services\DeviceDetectionService;
class DeviceTrigger extends TriggerBase {
	public function __construct() {
		parent::__construct('Device');
	}
	protected function is_valid($trigger_data) {
		$rule = $trigger_data->get_rule();
		return isset( $rule["user-behavior-device-mobile"] );
	}
	public function handle($trigger_data) {
		$rule = $trigger_data->get_rule();
		$content = $trigger_data->get_content();
		$is_mobile = DeviceDetectionService\DeviceDetectionService::get_instance()->is_mobile();
		$is_tablet = DeviceDetectionService\DeviceDetectionService::get_instance()->is_tablet();
		if ( $rule["user-behavior-device-mobile"] && $is_mobile ) {
			return $content;
		} else if ( $rule["user-behavior-device-tablet"] && $is_tablet ) {
			return $content;
		} else if ( $rule["user-behavior-device-desktop"] && 
					(!$is_mobile && !$is_tablet ) ) {
			return $content;
		}
		return false;
	}
}