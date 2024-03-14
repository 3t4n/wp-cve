<?php
#[AllowDynamicProperties]
class BWFAN_Jump_Controller extends BWFAN_Base_Step_Controller {

	private $jump_step_id = '';
	private $jump_step_name = '';

	public function populate_step_data( $db_step = array() ) {
		if ( ! parent::populate_step_data( $db_step ) || ! isset( $this->step_data['sidebarData']['jump_to']['step'] ) ) {
			return false;
		}

		$this->jump_step_id   = $this->step_data['sidebarData']['jump_to']['step'];
		$this->jump_step_name = $this->step_data['sidebarData']['jump_to']['name'];
	}

	public function get_jump_step_id() {
		return $this->jump_step_id;
	}

	public function get_jump_step_name() {
		return $this->jump_step_name;
	}
}
