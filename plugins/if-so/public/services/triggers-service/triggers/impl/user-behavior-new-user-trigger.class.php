<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once('user-behavior-trigger-base.class.php');

class UserBehaviorNewUserTrigger extends UserBehaviorTriggerBase {
	protected function is_valid($trigger_data) {
		if ( !parent::is_valid( $trigger_data ) )
			return false;

		$rule = $trigger_data->get_rule();
		$user_behavior = $rule['User-Behavior'];

		return $user_behavior == 'NewUser';
	}

	public function handle($trigger_data) {
		$content = $trigger_data->get_content();

		$cookie_name = 'ifso_visit_counts';
		// TODO move to another service
		$is_new_user = (!isset( $_COOKIE[$cookie_name] )) ||
                        (isset( $_COOKIE[$cookie_name] ) && ($_COOKIE[$cookie_name] == '' || (int)$_COOKIE[$cookie_name]===0));

		if ( $is_new_user ) {
			return $content;
		}

		return false;
	}
}