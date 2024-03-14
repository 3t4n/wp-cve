<?php

namespace IfSo\PublicFace\Services\TriggersService\Filters\Hooks;

require_once( plugin_dir_path ( __DIR__ ) . 'hook.interface.php');
require_once( IFSO_PLUGIN_SERVICES_BASE_DIR . 'recurrence-service/recurrence-service.class.php' );

use IfSo\PublicFace\Services\RecurrenceService;

class RecurrenceHook implements IHook {
	public function apply($text, $rule_data) {
        if($rule_data->get_rendering_recurrence_version()!==null) return;
		$recurrence_service = RecurrenceService\RecurrenceService::get_instance();
		if ($recurrence_service->is_rule_in_recurrence($rule_data->get_rule())) {
			$recurrence_service->handle($rule_data); // Apply recurrence
		}
	}
}