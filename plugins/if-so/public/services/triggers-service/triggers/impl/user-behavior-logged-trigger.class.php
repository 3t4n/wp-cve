<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once('user-behavior-trigger-base.class.php');

class UserBehaviorLoggedTrigger extends UserBehaviorTriggerBase {
	protected function is_valid($trigger_data) {
		if ( !parent::is_valid( $trigger_data ) )
			return false;

		$rule = $trigger_data->get_rule();
		$user_behavior = $rule['User-Behavior'];

		return $user_behavior == 'Logged';
	}

	public function handle($trigger_data) {
		$rule = $trigger_data->get_rule();
		$content = $trigger_data->get_content();

		$logged_in_out = $rule['user-behavior-logged'];
		$is_user_logged_in = is_user_logged_in(); // WP function

		if ($logged_in_out == "logged-in" && $is_user_logged_in) {
			return $content;
		} else if ($logged_in_out == "logged-out" && !$is_user_logged_in) {
			return $content;
		}

		return false;
	}
}