<?php
#[AllowDynamicProperties]
class BWFAN_Conditional_Controller extends BWFAN_Base_Step_Controller {
	protected $rules = array();

	public function populate_step_data( $db_step = array() ) {
		if ( parent::populate_step_data( $db_step ) ) {
			return $this->populate_filters();
		}

		return false;
	}

	protected function populate_filters() {
		if ( empty( $this->step_data ) ) {
			return false;
		}

		$this->rules = $this->step_data['sidebarData'];
	}

	public function is_match() {
		if ( empty( $this->rules ) || empty( $this->automation_data ) ) {
			return false;
		}

		foreach ( $this->rules as $rule_set ) {
			if ( ! is_array( $rule_set ) || empty( $rule_set ) ) {
				continue;
			}

			$rule_set_passed = true;
			foreach ( $rule_set as $rule ) {
				if ( ! is_array( $rule ) || ! isset( $rule['filter'] ) ) {
					continue;
				}

				$rule_data = $rule;
				$rule      = BWFAN_Core()->rules->get_rule( $rule['filter'] );
				if ( ! $rule instanceof BWFAN_Rule_Base || ! $rule->is_match_v2( $this->automation_data, $rule_data ) ) {
					$rule_set_passed = false;
					break;
				}
			}

			if ( $rule_set_passed ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get retry options
	 *
	 * @return array
	 */
	public function get_retry_data() {
		return array(
			HOUR_IN_SECONDS, // 1 hr
			6 * HOUR_IN_SECONDS, // 6 hrs
			18 * HOUR_IN_SECONDS, // 18 hrs
		);
	}
}
