<?php
#[AllowDynamicProperties]
class BWFAN_Traversal_Controller {
	public $current_node_id = 0;

	/** For processing of conditional controller */
	public $automation_id = 0;
	public $contact_id = 0;
	public $trail_id = 0;

	/** Conditional traversal Result */
	public $conditional_result_node_id = null;

	protected $steps = array();
	protected $links = array();

	/** Logging */
	protected $step_log = null;

	public $automation_contact_ins = null;

	/**
	 * Set steps in the traverser
	 *
	 * @param array $db_steps
	 *
	 * @return void
	 */
	public function set_steps( $db_steps ) {
		if ( ! is_array( $db_steps ) || empty( $db_steps ) ) {
			return;
		}

		foreach ( $db_steps as $step ) {
			$this->steps[ $step['id'] ] = $step;
		}
	}

	/**
	 * Set links in the traverser
	 *
	 * @param array $db_links
	 *
	 * @return void
	 */
	public function set_links( $db_links ) {
		if ( ! is_array( $db_links ) || empty( $db_links ) ) {
			return;
		}

		foreach ( $db_links as $link ) {
			if ( empty( $link ) || ! isset( $link['source'] ) || ! isset( $link['target'] ) ) {
				continue;
			}
			$this->links[ $link['source'] ] = $link['target'];
		}
	}

	/**
	 * Get current step id
	 *
	 * @return false|mixed
	 */
	public function get_current_step_id() {
		if ( empty( $this->steps ) || empty( $this->current_node_id ) || ! isset( $this->steps[ $this->current_node_id ] ) || ! isset( $this->steps[ $this->current_node_id ]['stepId'] ) ) {
			return false;
		}

		return $this->steps[ $this->current_node_id ]['stepId'];
	}

	/**
	 * Check if the current step is an end step
	 *
	 * @return bool
	 */
	public function is_end() {
		return $this->current_node_id === 'end';
	}

	/**
	 * @param array $step_ids Goal step IDs
	 * @param $goal_ins BWFAN_Goal_Controller
	 * @param array $automation_contact
	 *
	 * @return array|false
	 */
	public function try_traverse_to_goal( $step_ids, $goal_ins, $automation_contact ) {
		if ( empty( $this->current_node_id ) || empty( $step_ids ) ) {
			return false;
		}

		$goal_ins->goal_steps = $this->get_step_nodes( $step_ids );

		$run              = 0;
		$goal_found_nodes = [];
		while ( $run < count( $this->steps ) ) {
			$run ++;
			$current_step = $this->steps[ $this->current_node_id ];
			$this->log( 'current step', 'goal-check' );
			$this->log( [ 'id' => $current_step['id'], 'type' => $current_step['type'] ], 'goal-check' );

			switch ( $current_step['type'] ) {
				case 'action':
				case 'wait':
					$this->traverse_to_next_step();
					$this->log( 'traversed to next action/ wait step', 'goal-check' );
					break;
				case 'conditional':
					$this->process_goal_conditional( $goal_ins, $automation_contact );
					$this->traverse_to_next_step();
					$this->log( 'traversed to next conditional step', 'goal-check' );
					break;
				case 'jump':
					$this->process_jump( $goal_ins, $automation_contact );
					/** No need to traverse to next step as traversing already from process_jump function */
					$this->log( 'traversed to next jump step', 'goal-check' );
					break;
				case 'end':
					$this->log( 'end step', 'goal-check' );
					$run = count( $this->steps );
					break;
				case 'benchmark':
					if ( in_array( $current_step['id'], $goal_ins->goal_steps ) ) {
						$goal_found_nodes[] = $current_step['id'];
						$this->log( 'goal step found during traverse. goal step id: ' . $current_step['id'], 'goal-check' );
					}
					$this->traverse_to_next_step();
					$this->log( 'traversed to next step from goal step', 'goal-check' );
					break;
				default:
					break;
			}
		}

		return $goal_found_nodes;
	}

	/**
	 * Return current step data by step id
	 *
	 * @param int $step_id
	 *
	 * @return false|mixed
	 */
	public function get_step_by_step_id( $step_id ) {
		if ( empty( $step_id ) ) {
			return false;
		}

		foreach ( $this->steps as $step ) {
			if ( isset( $step['stepId'] ) && ( absint( $step['stepId'] ) === absint( $step_id ) ) ) {
				return $step;
			}
		}

		return false;
	}

	/**
	 * Get node ids from step ids
	 *
	 * @param $step_ids
	 *
	 * @return array|false
	 */
	public function get_step_nodes( $step_ids ) {
		if ( empty( $step_ids ) ) {
			return false;
		}
		$nodes = [];
		foreach ( $this->steps as $step ) {
			if ( 'benchmark' !== $step['type'] ) {
				continue;
			}
			if ( isset( $step['stepId'] ) && in_array( $step['stepId'], $step_ids ) ) {
				$nodes[ $step['stepId'] ] = $step['id'];
			}
		}

		return empty( $nodes ) ? false : $nodes;

	}

	/**
	 * Traverse to next step node
	 *
	 * @return bool
	 */
	public function traverse_to_next_step() {
		$current_step = $this->get_current_step();
		$this->log( $this->trail_id . ' - current step id: ' . $current_step['stepId'] . ' and node id: ' . $current_step['id'] . ' and type: ' . $current_step['type'] );

		if ( empty( $current_step ) || empty( $this->links ) ) {
			return false;
		}

		if ( 'conditional' === $current_step['type'] ) {
			$next_step = $this->traverse_conditional_step();
			if ( empty( $next_step ) ) {
				return false;
			}

			$this->current_node_id = $next_step;
			$this->log( $this->trail_id . ' - traverse to next step from conditional step. New node id: ' . $this->current_node_id );

			return true;
		}

		if ( ! isset( $this->links[ $this->current_node_id ] ) ) {
			return false;
		}

		$this->current_node_id = $this->links[ $this->current_node_id ];
		$this->log( $this->trail_id . ' - traverse to next step. New node id: ' . $this->current_node_id );

		return true;
	}

	/**
	 * Get current step data
	 *
	 * @return false|mixed
	 */
	public function get_current_step() {
		return ! empty( $this->steps ) && ! empty( $this->current_node_id ) && isset( $this->steps[ $this->current_node_id ] ) ? $this->steps[ $this->current_node_id ] : false;
	}

	/**
	 * Return step id after processing conditional step result
	 *
	 * @return false|mixed
	 */
	public function traverse_conditional_step() {
		$conditional_node_id = $this->conditional_result_node_id;
		if ( isset( $this->links[ $conditional_node_id ] ) ) {
			return $this->links[ $conditional_node_id ];
		}

		/** Maybe check if set in the database */
		if ( $this->automation_contact_ins instanceof BWFAN_Automation_Controller ) {
			$data = $this->automation_contact_ins->automation_contact_data;
			if ( isset( $data['node_id'] ) && isset( $this->links[ $data['node_id'] ] ) ) {
				/** Removing saved node id from the DB */
				$this->automation_contact_ins->update_conditional_step_result();

				return $this->links[ $data['node_id'] ];
			}
		}

		/** If somehow conditional step is deleted from table but its step node id exists in links then return target step of NO node */
		if ( isset( $this->links[ $this->links[ $this->current_node_id ] ] ) ) {
			return $this->links[ $this->links[ $this->current_node_id ] ];
		}

		BWFAN_Common::log_test_data( 'not able to traverse to condition next step ' . $this->current_node_id, 'traverse_conditional_failed' );
		BWFAN_Common::log_test_data( 'conditional_node_id: ' . $conditional_node_id, 'traverse_conditional_failed' );
		BWFAN_Common::log_test_data( $this->links, 'traverse_conditional_failed' );

		return false;
	}

	/**
	 * Process conditional step data for goal only
	 *
	 * @param $goal_ins BWFAN_Goal_Controller
	 * @param array $automation_contact automation contact row
	 *
	 * @return void
	 */
	public function process_goal_conditional( $goal_ins, $automation_contact ) {
		$current_step = $this->get_current_step();

		$ins                = new BWFAN_Conditional_Controller();
		$ins->contact_id    = $goal_ins->contact_id;
		$ins->automation_id = $goal_ins->automation_id;
		$ins->step_id       = absint( $current_step['stepId'] );
		$ins->populate_step_data( $current_step );
		$ins->populate_automation_contact_data( $automation_contact );

		$result = $ins->is_match();

		$this->conditional_result_node_id = $current_step['id'] . ( ( true === $result ) ? 'yes' : 'no' );
	}

	/**
	 * Process jump step
	 *
	 * @param $goal_ins
	 * @param $automation_contact
	 *
	 * @return void
	 */
	public function process_jump( $goal_ins, $automation_contact ) {
		$current_step = $this->get_current_step();

		$ins          = new BWFAN_Jump_Controller();
		$ins->step_id = absint( $current_step['stepId'] );
		$ins->populate_step_data( $current_step );

		$jump_step_id = $ins->get_jump_step_id();

		if ( empty( $jump_step_id ) ) {
			return;
		}

		/** Set current node to jump step */
		$this->set_node_id_from_step_id( $jump_step_id );
	}

	/**
	 * Maybe traverse back to valid step
	 *
	 * @return void
	 */
	public function maybe_traverse_back() {
		global $wpdb;

		/** @var BWFAN_Automation_Controller $ins */
		$ins = $this->automation_contact_ins;

		$step_id = $ins->step_id;
		$query   = $wpdb->prepare( "SELECT `ID` FROM `{$wpdb->prefix}bwfan_automation_contact_trail` WHERE `tid` = %s AND `sid` = %d LIMIT 0,1;", $ins->automation_contact['trail'], $step_id );
		$result  = $wpdb->get_var( $query );

		$valid_step_id = 0;
		if ( ! empty( $result ) ) {
			$query  = $wpdb->prepare( "SELECT t.`sid` FROM `{$wpdb->prefix}bwfan_automation_contact_trail` as t INNER JOIN `{$wpdb->prefix}bwfan_automation_step` as s ON s.`ID` = t.`sid` WHERE t.`tid` = %s AND t.`ID` < %d AND s.`status` IN (%d, %d) ORDER BY t.ID DESC LIMIT 0,1;", $ins->automation_contact['trail'], $result, 0, 1 );
			$result = $wpdb->get_var( $query );
			if ( ! empty( $result ) ) {
				$valid_step_id = strval( $result );
			}
		}
		if ( empty( $valid_step_id ) ) {
			/** Step not found, start from the first node */
			$ins->step_id = $ins->automation['start'];
			$this->set_node_id_from_step_id( $ins->step_id );

			$ins->automation_contact['last'] = 0;

			return;
		}

		$this->set_node_id_from_step_id( $valid_step_id );
	}

	/**
	 * Set step node ID from the step ID
	 *
	 * @param int $step_id
	 *
	 * @return bool
	 */
	public function set_node_id_from_step_id( $step_id ) {
		$step = $this->get_step_by_step_id( $step_id );
		if ( empty( $step ) || ! isset( $step['id'] ) ) {
			return false;
		}

		$this->current_node_id = $step['id'];

		return true;
	}

	/**
	 * Special log function for step execution
	 *
	 * @param $log
	 *
	 * @return void
	 */
	public function log( $log, $name = 'step-id' ) {
		if ( empty( $log ) ) {
			return;
		}
		if ( false === $this->step_log ) {
			return;
		}
		if ( is_null( $this->step_log ) && false === apply_filters( 'bwfan_allow_automation_step_logging', false ) ) {
			$this->step_log = false;

			return;
		}
		$this->step_log = true;

		BWFAN_Common::log_test_data( $log, $name, true );
	}
}
