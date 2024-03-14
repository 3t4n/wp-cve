<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once('user-behavior-trigger-base.class.php');

class UserBehaviorLoggedOutTrigger extends UserBehaviorTriggerBase {
	protected function is_valid($trigger_data) {
		if ( !parent::is_valid( $trigger_data ) )
			return false;

		$rule = $trigger_data->get_rule();
		$user_behavior = $rule['User-Behavior'];

		return $user_behavior == 'LoggedOut';
	}

	public function handle($trigger_data) {
		$is_user_logged_in = is_user_logged_in(); // WP function

		if ( !$is_user_logged_in ) {
			return $trigger_data->get_content();
		}

		return false;
	}
}