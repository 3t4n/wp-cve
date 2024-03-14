<?php
#[AllowDynamicProperties]
class BWFAN_Automation_Controller {
	/** Step Statuses */
	public static $STATUS_ACTIVE = 1;
	public static $STATUS_FAILED = 2;
	public static $STATUS_PAUSED = 3;
	public static $STATUS_WAITING = 4;
	public static $STATUS_TERMINATE = 5;
	public static $STATUS_RETRY = 6;
	/** Step types */
	public static $TYPE_WAIT = 1;
	public static $TYPE_ACTION = 2;
	public static $TYPE_GOAL = 3;
	public static $TYPE_CONDITIONAL = 4;
	public static $TYPE_EXIT = 5;
	public static $TYPE_JUMP = 6;
	/** Automation DB Row */
	public $automation = array();
	/** Automation Contact DB Row */
	public $automation_contact = array();
	public $automation_contact_data = array();
	public $trail_id = '';
	public $automation_id = 0;
	public $contact_id = 0;
	public $attempts = 0;
	public $event_slug = '';
	/** Step */
	public $step_id = 0;
	public $last_step_id = 0;
	public $current_step = array();
	public $type = 0;
	public $status = 1;
	/** Time Properties of Automation Contact */
	public $e_time = 0;
	public $c_date = '';

	/** Start Time */
	public $start_time = 0;
	public $run_duration = 15;

	/** Traversal Controller */
	/** @var BWFAN_Traversal_Controller traverse_ins */
	public $traverse_ins = null;

	/** Execution stop action props */
	public $should_end_automation = false;
	public $end_current_process = false;

	/** Caching vars */
	protected $cached_automation_data = array();
	protected $cached_automation_step_data = array();

	public $skip_step_id = 0;

	public function __construct() {
		$this->run_duration = apply_filters( 'bwfan_automation_v2_run_duration', $this->run_duration );
	}

	/**
	 * Set automation contact DB row
	 *
	 * @param $aContact
	 *
	 * @return bool
	 */
	public function set_automation_data( $aContact ) {
		if ( ! is_array( $aContact ) || ! isset( $aContact['data'] ) ) {
			return false;
		}

		$this->automation_contact      = $aContact;
		$this->automation_contact_data = ! empty( $aContact['data'] ) ? json_decode( $aContact['data'], true ) : array();

		$this->attempts      = absint( $aContact['attempts'] );
		$this->last_step_id  = absint( $aContact['last'] );
		$this->contact_id    = absint( $aContact['cid'] );
		$this->automation_id = absint( $aContact['aid'] );
		$this->status        = absint( $aContact['status'] );
		$this->e_time        = absint( $aContact['e_time'] );
		$this->c_date        = $aContact['c_date'];
		$this->event_slug    = $aContact['event'];

		/** @todo check automation status is active or not */

		$this->trail_id = ! empty( $aContact['trail'] ) ? $aContact['trail'] : md5( $aContact['ID'] . $aContact['cid'] . $aContact['c_date'] );

		/** Validate event before start */
		$event = BWFAN_Core()->sources->get_event( $this->event_slug );
		if ( $event instanceof BWFAN_Event && false === $event->validate_v2_before_start( $aContact ) ) {
			if ( empty( $aContact['trail'] ) ) {
				BWFAN_Model_Automation_Contact::delete( $aContact['ID'] );
			} else {
				$this->move_to_completed();
			}

			$this->end_current_process = true;

			return false;
		}

		if ( empty( $aContact['trail'] ) ) {
			$this->automation_contact['trail'] = $this->trail_id;

			/** First time update trail when empty */
			BWFAN_Model_Automation_Contact::update( array(
				'trail' => $this->trail_id,
			), array(
				'ID' => $aContact['ID'],
			) );
		}

		return true;
	}

	/**
	 * Start automation contact execution
	 *
	 * @return void
	 */
	public function start() {
		if ( true === $this->end_current_process ) {
			return;
		}

		$this->setup_automation();

		/** If current node is end node */
		if ( $this->traverse_ins->is_end() ) {
			$this->end_current_process = true;
			$this->move_to_completed();

			return;
		}

		/** If no next id then return */
		if ( 0 === absint( $this->step_id ) ) {
			$this->traverse_ins->log( $this->automation_contact['trail'] . ' - No next step id for automation id: ' . $this->automation_id . ' and contact id: ' . $this->contact_id );

			return;
		}

		while ( ( ( time() - $this->start_time ) < $this->run_duration ) && ( false === $this->end_current_process ) ) {
			$this->setup_current_step();

			/** If current process needs to END */
			if ( true === $this->end_current_process ) {
				return;
			}

			$this->process_current_step();

			/** If current process needs to END */
			if ( true === $this->end_current_process ) {
				return;
			}

			$this->setup_next_step();

			/** If current node is end node */
			if ( $this->traverse_ins->is_end() ) {
				$this->end_current_process = true;
				$this->move_to_completed();

				return;
			}
		}
	}

	/**
	 * Setup automation and step
	 * Update old step status if required
	 *
	 * @return void
	 */
	public function setup_automation() {
		$this->start_time = time();

		/** Fetch Automation DB Row */
		$this->automation = $this->get_automation_data( $this->automation_id );
		if ( ! is_array( $this->automation ) || ! isset( $this->automation['ID'] ) ) {
			$this->traverse_ins->log( 'Unable to get automation data for ID: ' . $this->automation_id );

			return;
		}

		/** Setup Traverse Controller */
		$this->setup_traverser();

		/** Set current step id. If last 0 then pick the start node */
		$this->step_id = ( 0 === absint( $this->automation_contact['last'] ) ) ? $this->automation['start'] : $this->automation_contact['last'];
		$this->traverse_ins->log( $this->automation_contact['trail'] . ' - automation last run step id: ' . $this->automation_contact['last'] . ' and step id: ' . $this->step_id );

		/** Setup automation contact instance */
		$this->traverse_ins->automation_contact_ins = $this;

		/** Set current node id */
		$valid = $this->traverse_ins->set_node_id_from_step_id( $this->step_id );

		/** If node not exists i.e. deleted or draft then traverse back to active executed step */
		if ( false === $valid ) {
			$this->traverse_ins->maybe_traverse_back();
		}

		/** maybe update current step trail status */
		$this->maybe_update_trail_status();

		/** Set to next step if last step run found */
		if ( absint( $this->automation_contact['last'] ) > 0 && ( self::$STATUS_ACTIVE === absint( $this->automation_contact['status'] ) ) ) {
			$this->traverse_ins->traverse_to_next_step();
		}

		/** Set next step id for execution */
		$step_id = $this->traverse_ins->get_current_step_id();
		if ( ! empty( $step_id ) ) {
			$this->step_id = $step_id;
			$this->traverse_ins->log( $this->automation_contact['trail'] . ' - current step to process, id: ' . $this->step_id . '. setup_automation func end.' );
		}
	}

	/**
	 * Get automation data by automation id
	 *
	 * @param $aid
	 *
	 * @return array|mixed|object|void|null
	 */
	public function get_automation_data( $aid ) {
		/** If data cached once, return */
		if ( isset( $this->cached_automation_data[ $aid ] ) ) {
			return $this->cached_automation_data[ $aid ];
		}

		$this->cached_automation_data[ $aid ] = BWFAN_Model_Automations::get_automation_with_data( $aid );

		return $this->cached_automation_data[ $aid ];
	}

	/**
	 * Initialize traverse controller and set data
	 *
	 * @return void
	 */
	public function setup_traverser() {
		$this->traverse_ins = new BWFAN_Traversal_Controller();
		$this->traverse_ins->set_steps( $this->automation['meta']['steps'] );
		$this->traverse_ins->set_links( $this->automation['meta']['links'] );

		$this->traverse_ins->automation_id = $this->automation_id;
		$this->traverse_ins->contact_id    = $this->contact_id;
		$this->traverse_ins->trail_id      = $this->trail_id;
	}

	/**
	 * Update step trail status to success
	 * For wait and goal type
	 *
	 * @return void
	 */
	public function maybe_update_trail_status() {
		if ( false === ( 0 < absint( $this->automation_contact['last'] ) ) ) {
			return;
		}
		BWFAN_Model_Automation_Contact_Trail::update_all_step_trail_status_complete( $this->trail_id );
	}

	/**
	 * Setup current step data
	 *
	 * @return void
	 */
	public function setup_current_step() {
		$this->traverse_ins->log( $this->automation_contact['trail'] . ' - ' . __FUNCTION__ . ': step id: ' . $this->step_id );

		$this->current_step = $this->get_automation_step_data( $this->step_id );

		/** If step data doesn't exists i.e. deleted from the DB somehow then traverse to next step */
		if ( empty( $this->current_step ) ) {
			/** Step exists in the automation but data not present in the step table */
			$this->setup_next_step();
			$this->current_step = $this->get_automation_step_data( $this->step_id );
		}

		if ( ! is_array( $this->current_step ) || ! isset( $this->current_step['ID'] ) ) {
			$this->end_current_process = true;

			return;
		}

		$this->type = absint( $this->current_step['type'] );
	}

	/**
	 * Get automation step data by step id
	 *
	 * @param $step_id
	 *
	 * @return array|mixed|object|void|null
	 */
	public function get_automation_step_data( $step_id ) {
		/** If data cached once, return */
		if ( isset( $this->cached_automation_step_data[ $step_id ] ) ) {
			return $this->cached_automation_step_data[ $step_id ];
		}

		$this->cached_automation_step_data[ $step_id ] = BWFAN_Model_Automation_Step::get_step_data_by_id( $step_id );

		return $this->cached_automation_step_data[ $step_id ];
	}

	/**
	 * Process current step and set trail
	 *
	 * @return void
	 */
	public function process_current_step() {

		if ( $this->e_time > time() ) {
			return;
		}

		/** @todo Status check */

		/** End automation for a contact if current step is the END */
		if ( true === $this->traverse_ins->is_end() ) {
			$this->end_current_process = true;
			$this->move_to_completed();

			return;
		}

		if ( empty( $this->type ) ) {
			$this->end_current_process = true;

			return;
		}

		/** Execute Steps */
		switch ( $this->type ) {
			case self::$TYPE_ACTION:
				$this->process_action();
				break;
			case self::$TYPE_WAIT:
				$this->process_wait();
				break;
			case self::$TYPE_CONDITIONAL:
				$this->process_conditional();
				break;
			case self::$TYPE_GOAL:
				$this->process_goal();
				break;
			case self::$TYPE_JUMP:
				$this->process_jump();
				break;
			case self::$TYPE_EXIT:
				$this->process_exit();
				break;
		}

		/** Special handling */
		if ( true === BWFAN_Common::$end_v2_current_contact_automation ) {
			$this->end_current_process = true;

			BWFAN_Common::$end_v2_current_contact_automation = false;
		}

		/** Check if automation needs to END for a contact */
		if ( true === $this->should_end_automation ) {
			$this->end_current_process = true;
			$this->move_to_completed();

			return;
		}

		$this->last_step_id = $this->step_id;

		/**
		 * For `wait` and `jump` step only
		 * If skip_step_id found then set it as last_step_id
		 * Set status to retry so that it can re-run on the same last executed step which will be skip_step_id i.e. jump step
		 */
		if ( in_array( $this->type, [ self::$TYPE_WAIT, self::$TYPE_JUMP ], true ) && intval( $this->skip_step_id ) > 0 ) {
			$this->last_step_id = $this->skip_step_id;
			$this->status       = self::$STATUS_RETRY;
		}

		/** Update automation contact DB row */
		$this->update_automation_contact();
	}

	/**
	 * Move automation contact row to complete table
	 *
	 * @return void
	 */
	public function move_to_completed() {
		/** Unset if conditional result node id present */
		if ( isset( $this->automation_contact_data['node_id'] ) ) {
			unset( $this->automation_contact_data['node_id'] );
		}

		BWFAN_Model_Automation_Complete_Contact::insert( array(
			'cid'    => $this->contact_id,
			'aid'    => $this->automation_id,
			'event'  => $this->event_slug,
			's_date' => $this->c_date,
			'c_date' => current_time( 'mysql', 1 ),
			'data'   => json_encode( $this->automation_contact_data ),
			'trail'  => $this->trail_id,
		) );

		/** Delete the row from automation contact table */
		BWFAN_Model_Automation_Contact::delete( $this->automation_contact['ID'] );

		/** Update status as success for any step trail where status was waiting */
		BWFAN_Model_Automation_Contact_Trail::update_all_step_trail_status_complete( $this->trail_id );

		/**Update automation contact fields */
		BWFAN_Common::update_automation_contact_fields( $this->contact_id, $this->automation_id );
	}

	/**
	 * Process action step
	 *
	 * @return void
	 */
	public function process_action() {
		$ins = new BWFAN_Action_Controller();
		$ins->populate_automation_contact_data( $this->automation_contact );
		$ins->populate_step_data( $this->current_step );

		$result = $ins->execute_action();
		if ( $result instanceof WP_Error ) {
			$message = $result->get_error_message();
			$result  = array(
				'message' => $message,
				'status'  => BWFAN_Action::$RESPONSE_FAILED,
			);
		}

		$this->status = BWFAN_Automation_Controller::$STATUS_ACTIVE;

		if ( ! is_array( $result ) || ! isset( $result['status'] ) ) {
			$this->traverse_ins->log( $this->automation_contact['trail'] . ' - Unable to process action for step ID: ' . $this->step_id . ', contact: ' . $this->contact_id . ', automation: ' . $this->automation_id );

			$this->e_time   = current_time( 'timestamp', 1 ) + ( 5 * MINUTE_IN_SECONDS );
			$this->attempts = 0;

			$this->end_current_process = true;

			return;
		}

		/** Action skipped */
		if ( $result['status'] === BWFAN_Action::$RESPONSE_SKIPPED ) {
			$this->set_trail_item( [ 'msg' => $result['message'] ], 4 );

			$this->attempts = 0;

			return;
		}

		/** Action successfully executed */
		if ( $result['status'] === BWFAN_Action::$RESPONSE_SUCCESS ) {
			$this->set_trail_item();

			$this->attempts = 0;

			return;
		}

		/** Action failed, reattempt possible */
		if ( $result['status'] === BWFAN_Action::$RESPONSE_REATTEMPT ) {
			$this->set_trail_item( [ 'error_msg' => $result['message'] ], 3 );

			$this->e_time = time() + ( 5 * MINUTE_IN_SECONDS );
			if ( isset( $result['e_time'] ) && absint( $result['e_time'] ) > time() ) {
				$this->e_time = absint( $result['e_time'] );
			}

			$this->attempts ++;
			$this->status = self::$STATUS_RETRY;

			$this->end_current_process = true;

			return;
		}

		$this->set_trail_item( [ 'error_msg' => $result['message'] ], 3 );

		/** Action permanently failed to execute */
		$this->status = self::$STATUS_FAILED;
		$this->e_time = current_time( 'timestamp', 1 );

		$this->end_current_process = true;
	}

	/**
	 * Insert row in contact step trail table
	 *
	 * @param $message
	 * @param $status
	 *
	 * @return void
	 */
	public function set_trail_item( $message = '', $status = 1 ) {
		$arr = array(
			'tid'    => $this->trail_id,
			'cid'    => $this->contact_id,
			'aid'    => $this->automation_id,
			'sid'    => $this->step_id,
			'c_time' => time(),
			'status' => $status,
		);
		if ( ! empty( $message ) ) {
			$arr['data'] = json_encode( $message );
		}

		/** Check if trail already found then delete */
		BWFAN_Model_Automation_Contact_Trail::delete_if_trail_exists( $arr );

		BWFAN_Model_Automation_Contact_Trail::insert( $arr );
	}

	/**
	 * Process wait step
	 *
	 * @return void
	 */
	public function process_wait() {
		$ins = new BWFAN_Delay_Controller();
		$ins->populate_automation_contact_data( $this->automation_contact );
		$ins->populate_step_data( $this->current_step );

		$this->attempts = 0;

		$time = $ins->get_time();
		if ( empty( $time ) ) {
			BWFAN_Common::log_test_data( 'Wait Step: Unable to get the wait time, step ID: ' . $this->step_id . ', automation ID: ' . $this->automation_id, 'process-wait-error' );
			$this->set_trail_item( [ 'msg' => 'Unable to get wait step wait time' ], 3 );
			$this->status = self::$STATUS_FAILED;

			$this->end_current_process = true;

			return;
		}

		/** If execution time has passed skip to the step */
		$step_data        = $ins->step_data;
		$enable_step_skip = isset( $step_data['sidebarData']['data']['enable_step_skip'] ) ? $step_data['sidebarData']['data']['enable_step_skip'] : 0;
		if ( current_time( 'timestamp', 1 ) > $time && ! empty( $enable_step_skip ) ) {
			$this->skip_step_id = isset( $step_data['sidebarData']['data']['skip_to_step']['step'] ) ? $step_data['sidebarData']['data']['skip_to_step']['step'] : 0;
			$is_step_active     = BWFAN_Model_Automation_Step::is_step_active( intval( $this->skip_step_id ) );
			if ( empty( $is_step_active ) && 'end' !== $this->skip_step_id ) {
				$this->end_current_process = true;
				$this->skip_step_id        = 0;
				$this->set_trail_item( [ 'msg' => 'Jump step deleted' ], 3 );

				$this->status   = BWFAN_Automation_Controller::$STATUS_FAILED;
				$this->attempts = 0;

				return;
			}
			BWFAN_Common::log_test_data( 'Wait Step: Skipped as time passed, step ID: ' . $this->step_id . ', automation ID: ' . $this->automation_id . '. Next Step ID: ' . $this->skip_step_id, 'process-wait-skipped' );
			if ( 'end' === $this->skip_step_id ) {
				$this->should_end_automation = true;
			}
			$this->set_trail_item( [ 'msg' => 'Skipped as time passed' ], 2 );
		} else {
			$this->set_trail_item( '', 2 );
		}

		$this->e_time = $time;
		$this->status = BWFAN_Automation_Controller::$STATUS_ACTIVE;

		$this->end_current_process = true;
	}

	/**
	 * Process conditional step
	 *
	 * @return void
	 */
	public function process_conditional() {
		/** If pro is not active */
		if ( false === bwfan_is_autonami_pro_active() ) {
			$this->set_trail_item( [ 'msg' => 'This is a pro feature' ], 3 );

			return;
		}

		$current_step = $this->traverse_ins->get_current_step();

		$ins                = new BWFAN_Conditional_Controller();
		$ins->contact_id    = $this->automation_contact['cid'];
		$ins->automation_id = $this->automation_contact['aid'];
		$ins->step_id       = absint( $current_step['stepId'] );

		$ins->populate_step_data( $this->current_step );
		$ins->populate_automation_contact_data( $this->automation_contact );

		try {
			$result                                         = $ins->is_match();
			$this->traverse_ins->conditional_result_node_id = $current_step['id'] . ( ( true === $result ) ? 'yes' : 'no' );

			$this->update_conditional_step_result( $this->traverse_ins->conditional_result_node_id );
			$result = ( true === $result ) ? '1' : '0';
			$this->set_trail_item( [ 'msg' => $result ] );

			$this->status = BWFAN_Automation_Controller::$STATUS_ACTIVE;;
			$this->attempts = 0;

			return;
		} catch ( Error $e ) {
			$result       = $e->getMessage();
			$trail_status = BWFAN_Action::$RESPONSE_REATTEMPT;
		}

		$attempt_limit = $ins->get_retry_data();
		/** If attempt limit exceed then mark failed */
		if ( ! is_array( $attempt_limit ) || ( count( $attempt_limit ) <= $this->attempts ) ) {
			$trail_status = BWFAN_Action::$RESPONSE_FAILED;
		}

		/** Condition failed, reattempt possible */
		if ( $trail_status === BWFAN_Action::$RESPONSE_REATTEMPT ) {
			$this->set_trail_item( [ 'msg' => $result ], $trail_status );
			$this->attempts ++;
			$time                      = isset( $attempt_limit[ $this->attempts - 1 ] ) ? $attempt_limit[ $this->attempts - 1 ] : 5 * MINUTE_IN_SECONDS;
			$this->e_time              = time() + $time;
			$this->status              = self::$STATUS_RETRY;
			$this->end_current_process = true;

			return;
		}

		$this->set_trail_item( [ 'msg' => $result ], 3 );

		/** Action permanently failed to execute */
		$this->status = self::$STATUS_FAILED;
		$this->e_time = current_time( 'timestamp', 1 );

		$this->end_current_process = true;
	}

	/**
	 * Process goal step
	 *
	 * @return void
	 */
	public function process_goal() {
		/** Pro is not active */
		if ( false === bwfan_is_autonami_pro_active() ) {
			$this->set_trail_item( [ 'msg' => 'This is a pro feature' ] );

			return;
		}

		$current_step = $this->traverse_ins->get_current_step();

		$ins          = new BWFAN_Goal_Controller();
		$ins->step_id = absint( $current_step['stepId'] );

		$ins->populate_step_data( $this->current_step );

		$result = $ins->traverse_setting;
		$this->traverse_ins->log( $this->automation_contact['trail'] . ' - process_goal result: ' . $result );

		/** Traverse End */
		if ( $result === BWFAN_Goal_Controller::$TRAVERSE_SETTING_END ) {
			$this->set_trail_item( [ 'msg' => $result ] );
			$this->should_end_automation = true;

			return;
		}

		/** Traverse Wait */
		if ( $result === BWFAN_Goal_Controller::$TRAVERSE_SETTING_WAIT ) {
			$this->set_trail_item( [ 'msg' => $result ], 2 );
			$this->status   = self::$STATUS_WAITING;
			$this->attempts = 0;

			$this->end_current_process = true;

			return;
		}

		/** Traverse Continue */
		$this->set_trail_item( [ 'msg' => $result ] );

		$this->status   = BWFAN_Automation_Controller::$STATUS_ACTIVE;
		$this->attempts = 0;
	}

	/**
	 * Process jump step
	 *
	 * @return void
	 */
	public function process_jump() {
		/** Pro is not active */
		if ( false === bwfan_is_autonami_pro_active() ) {
			$this->set_trail_item( [ 'msg' => 'This is a pro feature' ] );

			return;
		}

		$current_step = $this->traverse_ins->get_current_step();

		$ins          = new BWFAN_Jump_Controller();
		$ins->step_id = absint( $current_step['stepId'] );

		$ins->populate_step_data( $this->current_step );
		$this->skip_step_id = $ins->get_jump_step_id();

		/** If jump step deleted then end automation */
		$skip_step_data = BWFAN_Model_Automation_Step::is_step_active( $this->skip_step_id );
		if ( empty( $skip_step_data ) && 'end' !== $this->skip_step_id ) {
			$this->end_current_process = true;
			$this->set_trail_item( [ 'msg' => 'Jump step deleted' ], 3 );

			$this->status   = BWFAN_Automation_Controller::$STATUS_FAILED;
			$this->attempts = 0;

			return;
		}

		if ( 'end' === $this->skip_step_id ) {
			$this->should_end_automation = true;
		}
		$jump_step_name = $ins->get_jump_step_name();

		/** Traverse Continue */
		$this->set_trail_item( [ 'msg' => 'Jumped to ' . $jump_step_name ] );

		$this->status   = BWFAN_Automation_Controller::$STATUS_ACTIVE;
		$this->attempts = 0;

		$this->end_current_process = true;
	}

	/**
	 * Process exit step
	 *
	 * @return void
	 */
	public function process_exit() {
		/** Pro is not active */
		if ( false === bwfan_is_autonami_pro_active() ) {
			$this->set_trail_item( [ 'msg' => 'This is a pro feature' ], 3 );

			return;
		}

		$this->should_end_automation = true;

		$this->set_trail_item();
	}

	/**
	 * Update automation contact row
	 *
	 * @return void
	 */
	public function update_automation_contact() {
		if ( absint( $this->attempts ) > 0 && self::$STATUS_RETRY !== $this->status ) {
			$this->attempts = 0;
		}

		BWFAN_Model_Automation_Contact::update( array(
			'last'      => $this->last_step_id,
			'attempts'  => $this->attempts,
			'status'    => $this->status,
			'e_time'    => $this->e_time,
			'last_time' => current_time( 'timestamp', 1 ),
		), array(
			'ID' => $this->automation_contact['ID'],
		) );
	}

	/**
	 * Update conditional step result data in the database
	 *
	 * @param $node_id
	 *
	 * @return void
	 */
	public function update_conditional_step_result( $node_id = '' ) {
		$data = $this->automation_contact_data;

		if ( empty( $node_id ) ) {
			if ( isset( $data['node_id'] ) ) {
				unset( $data['node_id'] );
			}
		} else {
			$data['node_id'] = $node_id;
		}

		/** Set automation contact data back */
		$this->automation_contact_data = $data;

		BWFAN_Model_Automation_Contact::update( array(
			'data' => wp_json_encode( $data )
		), array(
			'ID' => $this->automation_contact['ID'],
		) );
	}

	/**
	 * Traverse to next step
	 *
	 * @return void
	 */
	public function setup_next_step() {
		$next = $this->traverse_ins->traverse_to_next_step();

		/** If current node is end node */
		if ( $this->traverse_ins->is_end() ) {
			return;
		}

		if ( false === $next ) {
			$this->end_current_process = true;
			$this->update_execution_time();
			BWFAN_Common::log_test_data( __FUNCTION__ . ' - Unable to traverse to next step, step ID: ' . $this->step_id . ', automation ID: ' . $this->automation_id, 'traverse-error' );

			return;
		}

		$next_step_id = $this->traverse_ins->get_current_step_id();
		if ( empty( $next_step_id ) ) {
			$this->end_current_process = true;
			$this->update_execution_time();
			BWFAN_Common::log_test_data( __FUNCTION__ . ' - No current step id found', 'traverse-error' );

			return;
		}

		$this->step_id = $next_step_id;
	}

	/**
	 * Update execution time of automation contact by 1 min default
	 *
	 * @param $time
	 *
	 * @return void
	 */
	protected function update_execution_time( $time = 60 ) {
		if ( empty( $this->automation_contact['ID'] ) ) {
			return;
		}
		$time = current_time( 'timestamp', 1 ) + absint( $time );
		BWFAN_Model_Automation_Contact::update( array(
			'e_time' => $time,
		), array(
			'ID' => $this->automation_contact['ID'],
		) );

	}
}
