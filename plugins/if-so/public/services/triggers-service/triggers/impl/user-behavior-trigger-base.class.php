<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');

abstract class UserBehaviorTriggerBase extends TriggerBase {
	public function __construct() {
		parent::__construct('User-Behavior');
	}

	protected function is_valid($trigger_data) {
		$rule = $trigger_data->get_rule();
		return ( !empty( $rule['User-Behavior'] ) );
	}
}