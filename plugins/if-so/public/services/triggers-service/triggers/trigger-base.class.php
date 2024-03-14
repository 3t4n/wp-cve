<?php
namespace IfSo\PublicFace\Services\TriggersService\Triggers;
require_once( IFSO_PLUGIN_SERVICES_BASE_DIR . 'trigger-validation-service/trigger-validation-service.class.php' );
use IfSo\PublicFace\Services\TriggerValidationService;
abstract class TriggerBase {
	protected $trigger_name;
	protected function __construct($trigger_name) {
		$this->trigger_name = $trigger_name;
	}
	public function can_handle($trigger_data) {
		$rule = $trigger_data->get_rule();
		if (empty($rule['trigger_type']))
			return false;
		if ($rule['freeze-mode'] == "true")
			return false;
		if ($rule['trigger_type'] != $this->trigger_name)
			return false;
		if ( !TriggerValidationService\TriggerValidationService::get_instance()->is_valid($rule) )
			return false;
		return $this->is_valid($trigger_data);
	}
	protected function is_valid($trigger_data) {
		return true;
	}
	abstract public function handle($trigger_data);
}