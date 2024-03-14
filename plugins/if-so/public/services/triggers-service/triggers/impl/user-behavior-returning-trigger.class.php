<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once('user-behavior-trigger-base.class.php');

class UserBehaviorReturningTrigger extends UserBehaviorTriggerBase {
	protected function is_valid($trigger_data) {
		if ( !parent::is_valid( $trigger_data ) )
			return false;

		$rule = $trigger_data->get_rule();
		$user_behavior = $rule['User-Behavior'];

		return $user_behavior == 'Returning';
	}

	public function handle($trigger_data) {
		$rule = $trigger_data->get_rule();

		$num_of_returns = 0;

		if ($rule['user-behavior-returning'] == "custom") {
			$num_of_returns = intval($rule['user-behavior-retn-custom']);
		} else {
			$returns_options = array( "first-visit" => 1,
				 					  "second-visit" => 2,
							 		  "three-visit" => 3 );

			$num_of_returns = $returns_options[$rule['user-behavior-returning']];
		}

		$num_of_visits = 0;
		if ( isset($_COOKIE['ifso_visit_counts']) )
			$num_of_visits = $_COOKIE['ifso_visit_counts']; // TODO move to another service

		if ( $num_of_visits >= $num_of_returns ) {
			return $trigger_data->get_content();
		}

		return false;
	}
}