<?php
#[AllowDynamicProperties]
abstract class BWFAN_Base_Step_Controller {
	public $automation_data = array();
	public $step_data = array();
	public $action_data = array();

	public $automation_contact_id = 0;
	public $automation_id = 0;
	public $contact_id = 0;
	public $step_id = 0;

	public $attempts = 0;
	public $max_attempts = 0;
	public $reattempt_time = 0;

	public function __construct() {
		$this->max_attempts   = apply_filters( 'bwfan_automation_contact_max_attempts', 3 );
		$this->reattempt_time = apply_filters( 'bwfan_automation_contact_reattempt_time', 30 );
	}

	public function populate_automation_contact_data( $db_aContact = array() ) {
		if ( ! empty( $db_aContact ) && is_array( $db_aContact ) && isset( $db_aContact['ID'] ) && isset( $db_aContact['data'] ) ) {
			$aContact                    = $db_aContact;
			$this->automation_data       = json_decode( $aContact['data'], true );
			$this->attempts              = absint( $aContact['attempts'] );
			$this->contact_id            = absint( $aContact['cid'] );
			$this->automation_id         = absint( $aContact['aid'] );
			$this->automation_contact_id = absint( $aContact['ID'] );

			return true;
		}

		if ( empty( $this->contact_id ) || empty( $this->automation_id ) ) {
			return false;
		}

		$aContact = BWFAN_Model_Automation_Contact::get_automation_contact( $this->automation_id, $this->contact_id );
		if ( ! is_array( $aContact ) || ! isset( $aContact['data'] ) ) {
			return false;
		}
		$this->automation_contact_id = absint( $aContact['ID'] );
		$this->automation_data       = json_decode( $aContact['data'], true );
		$this->attempts              = absint( $aContact['attempts'] );

		return true;
	}

	public function populate_step_data( $db_step = array() ) {
		$step = $db_step;
		if ( is_array( $db_step ) && isset( $db_step['ID'] ) && isset( $db_step['data'] ) ) {
			$this->step_data = json_decode( $db_step['data'], true );
			$this->step_id   = absint( $db_step['ID'] );
		}

		if ( empty( $this->step_id ) ) {
			return false;
		}

		if ( empty( $this->step_data ) ) {
			$step = BWFAN_Model_Automation_Step::get_step_data_by_id( $this->step_id );
			if ( ! is_array( $step ) || ! isset( $step['data'] ) ) {
				return false;
			}

			$this->step_data = json_decode( $step['data'], true );
		}

		if ( isset( $step['action'] ) && ! empty( $step['action'] ) ) {
			$this->action_data = json_decode( $step['action'], true );
		}

		return true;
	}
}
