<?php
#[AllowDynamicProperties]
abstract class BWFAN_Event {
	public $support_lang = false;
	public $log_type = 'event_triggered';
	public $global_data = array();
	public $event_data = array();
	public $message_validate_event = null;
	/** @var string Source that event belongs to */
	protected $source_type = 'wp';
	/** @var string Event Optgroup label */
	protected $optgroup_label = 'WordPress';
	/** @var int Optgroup priority */
	protected $optgroup_priority = 10;
	/** @var int Event priority */
	protected $priority = 200;
	/** @var string Event nice name */
	protected $event_name = '';
	/** @var string Event description */
	protected $event_desc = '';
	protected $excluded_actions = array();
	protected $included_actions = array();
	protected $event_saved_data = array();
	/** @var string Event slug used in array making to fetch the event object */
	protected $localize_data = array();
	protected $is_time_independent = false;
	protected $validation_passed = false;
	protected $event_actions = array();
	protected $track_automation_run = true;
	protected $need_unique_key = false;
	/** Customer supported */
	protected $customer_email_tag = '{{contact_email}}';
	/** @var array merge tag groups array supported by the event */
	protected $event_merge_tag_groups = array();
	/** @var array rule groups array supported by the event */
	protected $event_rule_groups = array();
	protected $user_selected_actions = array();
	protected $automations_for_current_event_db = array();
	protected $event_automation_id = null;
	protected $error_message = '';
	protected $automations_arr = array();
	/** v2 */
	protected $automations_v2_arr = array();
	protected $v2 = false;
	protected $is_goal = false;
	protected $goal_name = '';
	protected $goal_desc = '';
	protected $support_v1 = true;
	/** History sync properties */
	protected $page = null;
	protected $offset = null;
	protected $processed = null;

	public function is_goal() {
		return $this->is_goal;
	}

	public function get_goal_name() {
		if ( ! empty( $this->goal_name ) ) {
			return $this->goal_name;
		}

		return $this->event_name;
	}

	public function get_goal_desc() {
		if ( ! empty( $this->goal_desc ) ) {
			return $this->goal_desc;
		}

		return $this->event_desc;
	}

	public function validate_event( $task_details ) {
		$result            = array();
		$result['status']  = 1;
		$result['message'] = '';

		return $result;
	}

	public function validate_event_data_before_executing_task( $data ) {
		return true;
	}

	public function load_hooks() {
	}

	public function get_automation_event_validation() {
		return array(
			'status'  => 1,
			'message' => '',
		);
	}

	public function get_automation_event_status() {
		return array(
			'status'  => 4,
			'message' => __( 'Event has been changed in the automation', 'wp-marketing-automations' ),
		);
	}

	public function get_automation_event_success() {
		return array(
			'status'  => 1,
			'message' => '',
		);
	}

	/**
	 * show the validate checkbox in event meta fields
	 * contains text related to wc order only.
	 * Is overridable in the child event class
	 *
	 * @param $unique_slug
	 * @param $section_label
	 * @param $field_label
	 */
	public function get_validation_html( $unique_slug, $section_label, $field_label ) {
		?>
        <div class="bwfan-col-sm-12 bwfan-pl-0 bwfan_mt15">
        <label for="" class="bwfan-label-title"><?php esc_html_e( $section_label ); ?></label>
        <input type="checkbox" name="event_meta[validate_event]" id="bwfan-validate_event" value="1" class="validate_event_1 <?php esc_html_e( $unique_slug ); ?>-validate_event" {{is_validated}}/>
        <label for="bwfan-validate_event" class="bwfan-checkbox-label"><?php esc_html_e( $field_label ); ?></label>
        <div class="clearfix bwfan_field_desc"><?php echo wp_kses_post( 'This setting is useful to <u>verify time-delayed Actions</u>. For instance, you can create a follow-up Action that runs after 30 days of placing an order. That Action won\'t trigger if the above selected Order Statuses are not matched to the order.', 'wp-marketing-automations' ); ?></label>
        </div>
		<?php
	}

	/**
	 * A controller function to run automation every time an appropriate event occurs
	 * usually called by the event class just after the event hook to load all automations and run.
	 * @return array|bool
	 */
	public function run_automations() {
		BWFAN_Core()->public->load_active_automations( $this->get_slug() );

		if ( ! is_array( $this->automations_arr ) || count( $this->automations_arr ) === 0 ) {
			BWFAN_Core()->logger->log( 'Async callback: No active automations found. Event - ' . $this->get_slug(), $this->log_type );

			return false;
		}

		/** Extra checking for certain event like form events */
		$this->automations_arr = $this->validate_event_data_before_creating_task( $this->automations_arr );

		if ( ! is_array( $this->automations_arr ) || count( $this->automations_arr ) === 0 ) {
			return false;
		}

		$automation_actions = [];

		foreach ( $this->automations_arr as $automation_id => $automation_data ) {
			if ( $this->get_slug() !== $automation_data['event'] || 0 !== intval( $automation_data['requires_update'] ) ) {
				continue;
			}
			$ran_actions                          = $this->handle_single_automation_run( $automation_data, $automation_id );
			$automation_actions[ $automation_id ] = $ran_actions;
		}

		return $automation_actions;
	}

	public function get_slug() {
		return str_replace( array( 'bwfan_' ), '', sanitize_title( get_class( $this ) ) );
	}

	public function validate_event_data_before_creating_task( $automations_arr ) {
		return $automations_arr;
	}

	/**
	 * Handle execution of each automation to get all the executable tasks for the automation.
	 * Also responsible to run pre executable action function to instruct events to setup required data before execution.
	 *
	 * @param $automation_data
	 * @param $automation_id
	 *
	 * @return bool|int
	 */
	public function handle_single_automation_run( $automation_data, $automation_id ) {
		$this->event_automation_id = $automation_id;

		/** Setup the rules data */
		$this->pre_executable_actions( $automation_data );

		/** get all the actions which have passed the rules */
		$actions = $this->get_executable_actions( $automation_data );

		if ( ! isset( $actions['actions'] ) || ! is_array( $actions['actions'] ) || count( $actions['actions'] ) === 0 ) {
			BWFAN_Core()->logger->log( 'No task eligible for Automation ID - ' . $automation_id . '. Event - ' . $this->get_slug(), $this->log_type );

			return false;
		}

		$event_data = $this->get_automation_event_data( $automation_data );

		/** This only occurs when sync process is going on */
		if ( ! empty( $this->user_selected_actions ) ) {
			$final_actions = $this->filter_executable_actions( $actions['actions'] );

			try {
				$final_actions['actions'] = $this->recalculate_actions_time( $final_actions['actions'] );
			} catch ( Exception $exception ) {
				BWFAN_Core()->logger->log( 'Register task function not overrided by child class ->' . get_class( $this ), $this->log_type );
			}

			$actions['actions'] = $final_actions['actions'];
		}

		try {
			/** Register all those tasks which passed through rules or which are direct actions. The following function is present in every event class. */
			$this->register_tasks( $automation_id, $actions['actions'], $event_data );
		} catch ( Exception $exception ) {
			BWFAN_Core()->logger->log( 'Register task function not overrided by child class' . get_class( $this ), $this->log_type );
		}

		return count( $actions['actions'] );
	}

	/**
	 * Make rules data for every event.
	 *
	 * @param $automation_data
	 */
	public function pre_executable_actions( $automation_data ) {

	}

	/**
	 * Get the actions which are actually going to run.
	 *
	 * @param $automation_meta
	 *
	 * @return array
	 */
	public function get_executable_actions( $automation_meta ) {
		$this->event_actions = [];
		$ui_data             = isset( $automation_meta['uiData'] ) ? $automation_meta['uiData'] : [];

		foreach ( $ui_data as $details ) {
			$return_data = $this->get_actions_data( $details, $automation_meta );
			if ( is_array( $return_data ) && count( $return_data ) > 0 ) {
				$this->event_actions[] = $return_data;
			}
			if ( $this->validation_passed ) {
				$this->validation_passed = false;
				break;
			}
		}

		$executable_actions = $this->combine_actions();

		return $executable_actions;
	}

	/**
	 * Get those actions which satisfies the rules.
	 *
	 * @param $details
	 * @param $automation_meta
	 *
	 * @return array
	 */
	public function get_actions_data( $details, $automation_meta ) {
		$return_data = [];

		switch ( $details['id'] ) {
			case 'condition':
				$group_id         = $details['group_id'];
				$group_conditions = $automation_meta['condition'][ $group_id ];
				$is_passed        = BWFAN_Core()->rules->match_groups( $group_conditions );

				if ( $is_passed ) {
					$return_data['actions']  = $automation_meta['actions'][ $group_id ];
					$return_data['group_id'] = $group_id;
					$this->validation_passed = true;
				}
				break;
			case 'action':
				$group_id                = $details['group_id'];
				$return_data['actions']  = $automation_meta['actions'][ $group_id ];
				$return_data['group_id'] = $group_id;
				break;
			default:
				break;
		}

		return $return_data;
	}

	/**
	 * Combines all the actions of all groups whose tasks will be made.
	 * @return array
	 */
	public function combine_actions() {
		if ( ! is_array( $this->event_actions ) || 0 === count( $this->event_actions ) ) {
			return $this->event_actions;
		}

		$result      = [];
		$all_actions = [];
		foreach ( $this->event_actions as $details ) {
			$actions  = $details['actions'];
			$group_id = $details['group_id'];
			if ( ! is_array( $actions ) || empty( $actions ) ) {
				continue;
			}
			foreach ( $actions as $key1 => $action_detail ) {
				if ( empty( $action_detail['action_slug'] ) || empty( $action_detail['integration_slug'] ) ) {
					continue;
				}
				$action_detail['group_id']  = $group_id;
				$action_detail['action_id'] = $key1;
				$all_actions[]              = $action_detail;
			}
		}

		$this->event_actions = [];
		$result['actions']   = $all_actions;

		return $result;
	}

	/**
	 * Returns the current event settings set in the automation at the time of task creation.
	 *
	 * @param $value
	 *
	 * @return array
	 */
	public function get_automation_event_data( $value ) {
		return [
			'event_source'   => $value['source'],
			'event_slug'     => $value['event'],
			'validate_event' => ( isset( $value['event_meta']['validate_event'] ) ) ? 1 : 0,
		];
	}

	public function filter_executable_actions( $actions ) {
		$final_actions         = [];
		$user_selected_actions = $this->user_selected_actions;

		foreach ( $user_selected_actions as $group_actions ) {

			foreach ( $group_actions as $action_details ) {
				$action_slug = $action_details['action_slug'];

				foreach ( $actions as $act_ind => $act_det ) {
					$act_sl     = $act_det['action_slug'];
					$unique_key = $act_sl . '_' . $act_ind;

					if ( $action_slug === $act_sl ) {
						if ( ! isset( $final_actions[ $unique_key ] ) ) {
							$final_actions[ $unique_key ] = $act_det;
						}
					}
				}
			}
		}

		sort( $final_actions );

		return array(
			'actions' => $final_actions,
		);
	}

	/**
	 * @param $actions
	 *
	 * Recalculate action's execution time with respect to order date.
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function recalculate_actions_time( $actions ) {
		throw new ErrorException( 'This function `' . __FUNCTION__ . '` Must be override in child class' );
	}

	public function register_tasks( $automation_id, $actions, $event_data ) {
		throw new ErrorException( 'This function `' . __FUNCTION__ . '` Must be override in child class' );
	}

	/**
	 * @param $automation - automation with data
	 *
	 * @return false|void
	 */
	public function run_v2( $automation, $event_slug ) {
		$aid         = $automation['ID'];
		$event       = BWFAN_Core()->sources->get_event( $event_slug );
		$global_data = apply_filters( 'bwfan_modify_event_data', $event->get_event_data() );
		$global_data = BWFAN_Common::get_global_data( $global_data );
		$event_data  = $this->get_automation_event_data( $automation );

		$global_data['event_data']              = $event_data;
		$global_data['global']['automation_id'] = $aid;

		if ( empty( $global_data['global']['cid'] ) ) {
			return false;
		}

		/** Validate automation common settings like run count */
		if ( false === BWFAN_Model_Automations_V2::validation_automation_run_count( $aid, $global_data['global']['cid'], $automation ) ) {
			BWFAN_Common::log_test_data( 'Automation ID ' . $aid . ' already run on a contact ' . $global_data['global']['cid'] . '. Event - ' . $event_slug, 'contact-exist-automation', true );

			return false;
		}

		$data = [
			'cid'       => intval( $global_data['global']['cid'] ),
			'aid'       => $aid,
			'event'     => $event_slug,
			'c_date'    => current_time( 'mysql', 1 ),
			'e_time'    => current_time( 'timestamp', 1 ),
			'last_time' => current_time( 'timestamp', 1 ),
			'data'      => json_encode( $global_data )
		];

		/** In case of duplicate run, when hook runs twice. */
		$already_exists = BWFAN_Model_Automation_Contact::check_duplicate_automation_contact( $data );
		if ( $already_exists ) {
			BWFAN_Common::log_test_data( 'Automation ID ' . $data['aid'] . ' already exists with same data for contact ' . $data['cid'] . '. Event - ' . $data['event'], 'contact-duplicate-automation', true );
			BWFAN_Common::log_test_data( $global_data, 'contact-duplicate-automation', true );

			return false;
		}

		BWFAN_Model_Automation_Contact::insert( $data );
	}

	public function get_automations_data( $v = 1 ) {
		return ( 2 === $v ) ? $this->automations_v2_arr : $this->automations_arr;
	}

	public function check_if_bulk_process_executing( $should_logs_made ) {
		if ( is_array( $this->user_selected_actions ) && count( $this->user_selected_actions ) > 0 ) {
			return false;
		}

		return $should_logs_made;
	}

	/**
	 * Create tasks of the actions.
	 *
	 * @param $automation_id
	 * @param $actions
	 * @param $event_data
	 * @param $data
	 */
	public function create_tasks( $automation_id, $actions, $event_data, $data ) {
		/** Check if Autonami is in sandbox mode */
		if ( true === BWFAN_Common::is_sandbox_mode_active() ) {
			return;
		}

		$a_track_id       = $this->automations_arr[ $automation_id ]['a_track_id'];
		$total_tasks_made = [];
		/** For modify data */
		$data = apply_filters( 'bwfan_modify_event_data', $data );
		/** Get global data */
		$data = BWFAN_Common::get_global_data( $data );

		do_action( 'bwfan_before_creating_tasks', $automation_id, $actions, $event_data, $data );

		$immediate_actions = [];

		foreach ( $actions as $index => $action ) {
			$should_task_create = $this->should_task_create( $action, $data );

			if ( false === $should_task_create ) {
				continue;
			}
			// Task data can be modified before making a task
			$action  = apply_filters( 'bwfan_pre_insert_task', $action, $automation_id, $this );
			$task_id = BWFAN_Core()->tasks->insert_task( $automation_id, $action, $this );

			if ( isset( $action['data_meta'] ) ) {
				$data['data_meta'] = $action['data_meta'];
			}

			$data['event_data'] = $event_data;
			$data['data']       = ( isset( $action['data'] ) ) ? $action['data'] : array();
			$data['group_id']   = $action['group_id'];
			$data['action_id']  = $action['action_id'];
			$data               = apply_filters( 'bwfan_alter_taskdata', $data );
			$data               = apply_filters( 'bwfan_alter_taskdata_' . $this->get_slug(), $data );
			BWFAN_Core()->tasks->insert_taskmeta( $task_id, 'integration_data', $data );

			// Unique task tracking id
			$t_track_id = $a_track_id . '_' . $action['group_id'] . '_' . $action['action_id'];
			BWFAN_Core()->tasks->insert_taskmeta( $task_id, 't_track_id', $t_track_id );

			// Actions can be performed after task in inserted into db
			do_action( 'bwfan_task_created', $index, $task_id );
			do_action( 'bwfan_task_created_' . $this->get_slug(), $index, $task_id );

			$total_tasks_made[] = $task_id;

			/** Checking immediately executable actions */
			if ( ! isset( $action['time']['delay_type'] ) || 'immediately' === $action['time']['delay_type'] ) {
				$immediate_actions[] = $task_id;
			}
		}

		do_action( 'bwfan_after_creating_tasks', $automation_id, $actions, $event_data, $data, $total_tasks_made );

		/** Executing immediately executable actions */
		$this->execute_immediate_tasks( $immediate_actions );

		// Increase the automation run count and fire contact creation async call only when async process in not running
		if ( empty( $this->user_selected_actions ) ) {
			BWFAN_Core()->automations->update_automation_run_count( $automation_id );

			if ( $this->track_automation_run ) {
				// Send an async call for updating contact meta
				$this->send_async_contact_call( $automation_id );
			}
		}

		if ( count( $total_tasks_made ) > 0 ) {
			BWFAN_Core()->logger->log( 'Total ' . count( $total_tasks_made ) . ' tasks created. Event - ' . $this->get_slug() . ', Task IDs -' . implode( ', ', $total_tasks_made ), $this->log_type ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
		} else {
			BWFAN_Core()->logger->log( '0 Tasks created. Event - ' . $this->get_slug(), $this->log_type ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
		}
	}

	/**
	 * Check if task for an action should be created or not.
	 *
	 * @param $action_data
	 *
	 * @return bool
	 */
	public function should_task_create( $action_data, $data ) {
		if ( ! isset( $action_data['integration_slug'] ) ) {
			return false;
		}

		$action_instance = BWFAN_Core()->integration->get_action( $action_data['action_slug'] );

		if ( ! $action_instance instanceof BWFAN_Action ) {
			return false;
		}

		$check_action_data = $action_instance->check_required_data( $data );

		if ( false === $check_action_data ) {
			return false;
		}

		$check_language_support = $this->check_language_support( $action_data, $data );
		if ( false === $check_language_support ) {
			return false;
		}

		return true;
	}

	public function check_language_support( $action_data, $data ) {
		/** checking for language plugin **/
		if ( ! function_exists( 'icl_get_languages' ) && ! function_exists( 'pll_the_languages' ) && ! bwfan_is_translatepress_active() && function_exists( 'bwfan_is_weglot_active' ) && ! bwfan_is_weglot_active() ) {
			return true;
		}

		if ( false === $this->support_lang || ! isset( $action_data['language'] ) || ! isset( $action_data['language']['enable_lang'] ) || 1 !== absint( $action_data['language']['enable_lang'] ) && ! isset( $action_data['language']['lang'] ) || empty( $action_data['language']['lang'] ) ) {
			return true;
		}

		$selected_lang = $action_data['language']['lang'];
		$lang          = $this->get_language_from_event( $data );
		if ( $lang === $selected_lang ) {
			return true;
		}

		return false;
	}

	public function get_language_from_event( $data ) {
		$lang  = '';
		$order = isset( $data['global']['wc_order'] ) ? $data['global']['wc_order'] : '';
		if ( ! $order instanceof WC_Order && isset( $data['global']['order_id'] ) ) {
			$order = wc_get_order( $data['global']['order_id'] );
		}

		if ( ! $order instanceof WC_Order && isset( $data['global']['wc_order_id'] ) ) {
			$order = wc_get_order( $data['global']['wc_order_id'] );
		}

		if ( $order instanceof WC_Order ) {

			if ( isset( $data['global']['language'] ) && ! empty( $data['global']['language'] ) ) {
				$lang = $data['global']['language'];
			}

		} elseif ( isset( $data['global']['cart_abandoned_id'] ) ) {
			$cart_details  = BWFAN_Model_Abandonedcarts::get( $data['global']['cart_abandoned_id'] );
			$checkout_data = json_decode( $cart_details['checkout_data'], true );
			if ( isset( $checkout_data['lang'] ) ) {
				$lang = $checkout_data['lang'];
			}
		}

		return $lang;
	}

	public function execute_immediate_tasks( $task_ids ) {
		if ( empty( $task_ids ) ) {
			return;
		}

		/** set claim_id to 1 for the immediate tasks so it will not run by the action scheduler */

		global $wpdb;
		$placeholders = array_fill( 0, count( $task_ids ), '%d' );
		$placeholders = implode( ', ', $placeholders );

		$query = $wpdb->prepare( "UPDATE `{$wpdb->prefix}bwfan_tasks` SET `claim_id` = 1 WHERE `ID` IN ($placeholders)", $task_ids );
		$wpdb->query( $query );

		/** @var BWFAN_Tasks $task_ins */
		$task_ins = BWFAN_Tasks::get_instance();
		foreach ( $task_ids as $id ) {
			$task_ins->bwfan_ac_execute_task( $id );
		}
	}

	/**
	 * Send async call for updating the contact automation details
	 *
	 * @param $automation_id
	 */
	public function send_async_contact_call( $automation_id ) {
		$email = $this->get_email_event();
		if ( false === $email ) {
			BWFAN_Core()->logger->log( $this->error_message . '. Automation ID - ' . $automation_id . '. Event - ' . $this->get_slug(), $this->log_type );

			return;
		}

		$user_id   = $this->get_user_id_event();
		$url       = rest_url( '/autonami/v1/update-contact-automation' );
		$body_data = array(
			'automation_id' => $automation_id,
			'email'         => $email,
			'user_id'       => $user_id,
			'unique_key'    => get_option( 'bwfan_u_key', false ),
		);
		$args      = bwf_get_remote_rest_args( $body_data );

		wp_remote_post( $url, $args );
	}

	/**
	 * If any event has email and it does not contain order object, then following method must be overridden by child event class.
	 * Return email
	 * @return bool
	 */
	public function get_email_event() {
		$order = $this->order;
		if ( $order instanceof WC_Order ) {
			return $order->get_billing_email();
		}

		$this->error_message = __( 'Not a valid WC order object', 'wp-marketing-automations' );
		$email               = false;

		return $email;
	}

	/**
	 * If any event has user id and it does not contain order object, then following method must be overridden by child event class.
	 * Return user id
	 * @return bool
	 */
	public function get_user_id_event() {
		$order = $this->order;
		if ( $order instanceof WC_Order ) {
			return $order->get_user_id();
		}

		$this->error_message = __( 'Not a valid WC order object', 'wp-marketing-automations' );
		$user_id             = false;

		return $user_id;
	}

	/**
	 * Calculate actions time based on record date.
	 *
	 * @param $actions
	 * @param $record_date DateTime
	 *
	 * @return mixed
	 * @throws Exception
	 */

	public function calculate_actions_time( $actions, $record_date ) {
		BWFAN_Common::convert_from_gmt( $record_date ); // convert to site time
		BWFAN_Common::convert_to_gmt( $record_date );

		$record_date_timestamp = $record_date->getTimestamp();
		$current_timestamp     = current_time( 'timestamp', 1 );
		$datetime1             = new DateTime( date( 'Y-m-d H:i:s', $record_date_timestamp ) );//start time
		$datetime2             = new DateTime( date( 'Y-m-d H:i:s', $current_timestamp ) );//end time
		$interval              = $datetime1->diff( $datetime2 );
		$days_difference       = intval( $interval->format( '%a' ) );
		$hours_difference      = intval( $interval->format( '%h' ) );
		$minutes_difference    = intval( $interval->format( '%i' ) );

		foreach ( $actions as $action_index => $action_details ) {
			$delay_type = $action_details['time']['delay_type'];

			if ( 'fixed' === $delay_type && isset( $action_details['time']['fixed_date'] ) && ! empty( $action_details['time']['fixed_date'] ) ) {
				$fixed_date            = $action_details['time']['fixed_date'];
				$fixed_date_timestamp  = strtotime( $fixed_date );
				$fixed_time            = $action_details['time']['fixed_time'];
				$fixed_time_seconds    = BWFAN_Common::get_seconds_from_time_format( $fixed_time );
				$task_actual_timestamp = intval( $fixed_date_timestamp ) + intval( $fixed_time_seconds );

				if ( $task_actual_timestamp < $current_timestamp ) {
					$actions[ $action_index ]['time']['delay_type'] = 'immediately';
				}
			}

			if ( 'after_delay' === $delay_type ) {
				$time_type              = $action_details['time']['time_type'];
				$time_number            = intval( $action_details['time']['time_number'] );
				$new_time_number        = $time_number;
				$time_increament_string = '';

				if ( 'days' === $time_type ) {
					if ( $time_number > $days_difference ) {
						$new_time_number = $time_number - $days_difference;
					}

					$time_increament_string = '+' . $new_time_number . ' days';
				} elseif ( 'hours' === $time_type ) {
					if ( $time_number > $hours_difference ) {
						$new_time_number = $time_number - $hours_difference;
					}

					$time_increament_string = '+' . $new_time_number . ' hours';
				} elseif ( 'minutes' === $time_type ) {
					if ( $time_number > $minutes_difference ) {
						$new_time_number = $time_number - $minutes_difference;
					}

					$time_increament_string = '+' . $new_time_number . ' minutes';
				}

				$timestamp = strtotime( $time_increament_string, $record_date_timestamp );
				if ( $timestamp > $current_timestamp ) {
					$actions[ $action_index ]['time']['time_number'] = $new_time_number;
				} else {
					$actions[ $action_index ]['time']['delay_type'] = 'immediately';
				}
			}
		}

		return $actions;
	}

	public function get_view( $db_eventmeta_saved_value ) {

	}

	public function get_default_data() {
		//Use in every event
		$source = $this->source_type;

		$data = array(
			'source' => $source,
			'event'  => $this->get_slug(),
		);

		return $data;
	}

	/**
	 * Send the event data to endpoint for processing
	 *
	 * @param $data
	 *
	 * @return array|void|WP_Error
	 */
	public function send_async_call( $data ) {
		$should_fire_call = ( $this->get_current_event_automations() || $this->get_current_goal_automations() );
		/** In case of Form Submission, Proceed the send_async_call, to trigger the CRM's Form feed */
		$form_submit_events = BWFAN_Common::get_form_submit_events();
		$is_form_submission = is_array( $form_submit_events ) && in_array( get_class( $this ), $form_submit_events );

		if ( false === $should_fire_call && false === $is_form_submission ) {
			BWFAN_Core()->logger->log( 'No automations found for event ' . $this->get_slug(), $this->log_type ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions

			return;
		}

		if ( true === $is_form_submission ) {
			$data['is_form_submission'] = 1;
		}

		$data['unique_key'] = get_option( 'bwfan_u_key', false );
		$url                = rest_url( '/autonami/v1/events' );
		$data               = apply_filters( 'bwfan_send_async_call_data', $data );

		$args = bwf_get_remote_rest_args( $data );

		BWFAN_Common::event_advanced_logs( 'Sending data for event: ' . $this->get_slug() );
		BWFAN_Common::event_advanced_logs( 'URL: ' . $url );
		BWFAN_Common::event_advanced_logs( $args );

		$response = wp_remote_post( $url, $args );

		BWFAN_Common::event_advanced_logs( 'Event endpoint response' );
		BWFAN_Common::event_advanced_logs( $response );

		return $response;
	}

	/**
	 * Check if any active automation for current event is present
	 *
	 * @return array|bool
	 */
	public function get_current_event_automations() {
		BWFAN_Core()->public->load_active_automations( $this->get_slug() );
		BWFAN_Core()->public->load_active_v2_automations( $this->get_slug() );

		if ( ( ! is_array( $this->automations_arr ) || count( $this->automations_arr ) === 0 ) && ( ! is_array( $this->automations_v2_arr ) || count( $this->automations_v2_arr ) === 0 ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get all active automations by the goal
	 *
	 * @return array|false
	 */
	public function get_current_goal_automations() {
		if ( ! $this->is_goal() ) {
			return false;
		}

		$goal_automations = BWFAN_Model_Automations_V2::get_goal_automations( $this->get_slug() );

		return empty( $goal_automations ) ? false : $goal_automations;
	}

	public function get_contact_id_for_goal( $capture_args ) {
		if ( isset( $capture_args['contact_id'] ) && absint( $capture_args['contact_id'] ) > 0 ) {
			return absint( $capture_args['contact_id'] );
		}

		return 0;
	}

	public function get_task_view( $global_data ) {
		return '';
	}

	/**
	 * Set global data for all the merge tags which are supported by this event.
	 *
	 * @param $task_meta
	 */
	public function set_merge_tags_data( $task_meta ) {
		$wc_order_id = BWFAN_Merge_Tag_Loader::get_data( 'wc_order_id' );
		if ( empty( $wc_order_id ) || $wc_order_id !== $task_meta['global']['order_id'] ) {
			$set_data = array(
				'wc_order_id' => $task_meta['global']['order_id'],
				'email'       => $task_meta['global']['email'],
				'wc_order'    => wc_get_order( $task_meta['global']['order_id'] ),
			);
			BWFAN_Merge_Tag_Loader::set_data( $set_data );
		}
	}

	public function get_source() {
		return $this->source_type;
	}

	public function get_optgroup_label() {
		return $this->optgroup_label;
	}

	public function get_name() {
		return $this->event_name;
	}

	public function get_desc() {
		return $this->event_desc;
	}

	public function get_priority() {
		return $this->priority;
	}

	public function get_included_actions() {
		return $this->included_actions;
	}

	public function get_excluded_actions() {
		return $this->excluded_actions;
	}

	public function set_event_saved_data( $data ) {
		$this->event_saved_data = $data;
	}

	public function is_time_independent() {
		return $this->is_time_independent;
	}

	/**
	 * Return localize data of event for frontend UI
	 * @return array
	 */
	public function get_localize_data() {
		$this->localize_data = [
			'source_type'         => $this->source_type,
			'source_type_label'   => $this->optgroup_label,
			'event_name'          => $this->event_name,
			'event_desc'          => $this->event_desc,
			'slug'                => $this->get_slug(),
			'is_time_independent' => $this->is_time_independent,
			'included_actions'    => $this->included_actions,
			'excluded_actions'    => $this->excluded_actions,
			'event_saved_data'    => $this->event_saved_data,
			'support_lang'        => $this->support_lang,
			'support_v1'          => $this->support_v1,
			'customer_email_tag'  => $this->customer_email_tag,
			'available'           => 'yes',
			'need_unique_key'     => $this->need_unique_key,
		];

		return apply_filters( 'bwfan_event_' . $this->get_slug() . '_localize_data', $this->localize_data, $this );
	}

	/**
	 * Returns event data
	 *
	 * @return mixed
	 */
	public function get_event_data_for_api() {
		if ( ! $this->is_v2() ) {
			return [];
		}
		$data = $this->localize_data;

		$default_event_field = [
			[
				'id'       => 'bwfan_automation_run',
				'type'     => 'radio',
				'label'    => __( 'Runs On Contact', 'wp-marketing-automations' ),
				'options'  => [
					[
						'label' => __( 'Once', 'wp-marketing-automations' ),
						'value' => 'once'
					],
					[
						'label' => __( 'Multiple Times', 'wp-marketing-automations' ),
						'value' => 'multiple'
					]
				],
				'tip'      => "",
				"required" => false,
			],
			[
				'id'        => 'enter_automation_on_active_contact',
				'label'     => __( "Allow currently active contacts in this automation to re-enter again", 'wp-marketing-automations' ),
				'className' => 'bwf-tooglecontrol-advance',
				'type'      => 'toggle',
				'toggler'   => [
					'fields'   => [
						[
							'id'    => 'bwfan_automation_run',
							'value' => 'multiple',
						]
					],
					'relation' => 'OR',
				],
			]
		];

		$default_run_value = [
			'bwfan_automation_run' => 'once'
		];

		$fields         = method_exists( $this, 'get_fields_schema' ) ? $this->get_fields_schema() : [];
		$default_values = method_exists( $this, 'get_default_values' ) ? $this->get_default_values() : [];

		$data['fields']         = array_merge( $fields, $default_event_field );
		$data['default_values'] = array_merge( $default_values, $default_run_value );

		return $data;
	}

	public function is_v2() {
		return $this->v2;
	}

	public function get_fields_schema() {
		return array();
	}

	public function is_support_v1() {
		return $this->support_v1;
	}

	/**
	 * Get Goal schema data
	 *
	 * @return array
	 */
	public function get_goal_data_for_api() {
		if ( ! $this->is_v2() ) {
			return [];
		}
		$data = $this->localize_data;

		$default_goal_field = [
			[
				'id'       => 'bwfan_goal_run',
				'type'     => 'radio',
				'label'    => __( 'If the contact doesn\'t meet the specified goal', 'wp-marketing-automations' ),
				'options'  => [
					[
						'label' => __( 'Wait until the goal is met', 'wp-marketing-automations' ),
						'value' => 'wait'
					],
					[
						'label' => __( 'Continue anyway', 'wp-marketing-automations' ),
						'value' => 'continue'
					],
					[
						'label' => __( 'End this automation', 'wp-marketing-automations' ),
						'value' => 'end'
					]
				],
				'tip'      => "",
				"required" => false,
			]
		];

		$default_run_value = [
			'bwfan_goal_run' => 'wait'
		];
		$schema            = $this->get_goal_fields_schema();
		$schema            = empty( $schema ) ? $this->get_fields_schema() : $schema;

		$default_values = method_exists( $this, 'get_default_goal_values' ) ? $this->get_default_goal_values() : [];

		$data['fields']         = array_merge( $schema, $default_goal_field );
		$data['default_values'] = array_merge( $default_values, $default_run_value );

		return $data;
	}

	public function get_goal_fields_schema() {
		return array();
	}

	public function get_default_goal_values() {
		return array();
	}

	/**
	 * Return Available event rule group
	 * @return array
	 */
	public function get_rule_group() {
		$rule_groups = $this->event_rule_groups;
		$rule_groups = array_merge( $rule_groups, [ 'bwf_automation', 'languages' ] );

		return apply_filters( 'bwfan_event_' . $this->get_slug() . '_rules_group', $rule_groups, $this );
	}

	/**
	 * Return Available event Merge tag group
	 * @return array
	 */
	public function get_merge_tag_groups() {
		if ( true === $this->support_lang ) {
			$this->event_merge_tag_groups[] = 'language';
		}

		$this->event_merge_tag_groups[] = 'bwfan_default';

		return apply_filters( 'bwfan_event_' . $this->get_slug() . '_merge_tag_group', $this->event_merge_tag_groups, $this );
	}

	/**
	 * Return user selected actions against this event
	 * @return null
	 */
	public function get_user_selected_actions() {
		return $this->user_selected_actions;
	}

	public function set_automations_data( $data, $v = 1 ) {
		if ( empty( $data ) ) {
			return;
		}
		if ( 2 === $v ) {
			$this->automations_v2_arr = $data;

			return;
		}
		$this->automations_arr = $data;
	}

	public function make_task_data( $automation_id, $automation_data ) {

	}

	public function set_source_type( $type ) {
		$this->source_type = $type;
	}

	/** v2 Methods: START */

	public function capture_async_data() {
		throw new ErrorException( 'This function `' . __FUNCTION__ . '` Must be override in child class' );
	}

	/**
	 * Validate single v2 automation if passed to run
	 *
	 * @param $automation_data
	 *
	 * @return bool
	 */
	public function validate_v2_event_settings( $automation_data ) {
		return true;
	}

	/**
	 * Validate v2 automation before start
	 *
	 * @param $automation_contact_row
	 *
	 * @return bool
	 */
	public function validate_v2_before_start( $automation_contact_row ) {
		return true;
	}

	/**
	 * Validate automation goal settings
	 *
	 * @param $automation_data
	 *
	 * @return bool
	 */
	public function validate_goal_settings( $automation_settings, $automation_data ) {
		return false;
	}

	/**
	 * Validate automation goal settings
	 *
	 * @param $entity
	 * @param $automation_data
	 * @param $version
	 *
	 * @return bool
	 */
	public function validate_bulk_action_event_settings( $entity, $automation_data, $version ) {
		return true;
	}

	/**
	 * Run v2 automation for a contact
	 * Validate and save in the automation contact table
	 *
	 * @param $automation_id
	 * @param $automation_data
	 *
	 * @return false|int
	 * @throws Exception
	 */
	public function handle_automation_run_v2( $automation_id, $automation_data ) {
		/** If no start node found */
		if ( ! isset( $automation_data['start'] ) || 0 === intval( $automation_data['start'] ) ) {
			return false;
		}

		/** Global data */
		$global_data = $this->global_data;
		$global_data = ! is_array( $global_data ) ? [] : $global_data;
		$global_data = BWFAN_Common::get_global_data( $global_data );

		/** Event data */
		$event_data = $this->event_data;
		$event_data = ! is_array( $event_data ) ? [] : $event_data;

		$global_data['event_data'] = $event_data;

		/** If no contact ID found, log and return */
		if ( ! isset( $global_data['global'] ) || ! isset( $global_data['global']['cid'] ) || 0 === intval( $global_data['global']['cid'] ) ) {
			BWFAN_Common::log_test_data( 'No cid found for Automation ID - ' . $automation_id . '. Event - ' . $this->get_slug(), 'no-contact-id', true );
			BWFAN_Common::log_test_data( $global_data, 'no-contact-id', true );

			return false;
		}

		/** If contact is active in automation */
		$exclude_check = false;
		if ( isset( $automation_data['event_meta'] ) && isset( $automation_data['event_meta']['enter_automation_on_active_contact'] ) && 1 === absint( $automation_data['event_meta']['enter_automation_on_active_contact'] ) ) {
			$exclude_check = true;
		}

		if ( false === $exclude_check && BWFAN_Model_Automation_Contact::maybe_contact_in_automation( $global_data['global']['cid'], $automation_id ) ) {
			BWFAN_Common::log_test_data( 'Contact ' . $global_data['global']['cid'] . ' is active in the automation - ' . $automation_id . '. Event - ' . $this->get_slug(), 'contact-exist-automation', true );

			return false;
		}

		/** Validate automation common settings like run count */
		if ( false === BWFAN_Model_Automations_V2::validation_automation_run_count( $automation_id, $global_data['global']['cid'], $automation_data, $exclude_check ) ) {
			BWFAN_Common::log_test_data( 'Automation ID ' . $automation_id . ' already run on a contact ' . $global_data['global']['cid'] . '. Event - ' . $this->get_slug(), 'contact-exist-automation', true );

			return false;
		}

		/** set automation id in event global data */
		$global_data['global']['automation_id'] = $automation_id;
		$contact_id                             = intval( $global_data['global']['cid'] );

		$data = [
			'cid'       => $contact_id,
			'aid'       => $automation_id,
			'event'     => $this->get_slug(),
			'c_date'    => current_time( 'mysql', 1 ),
			'e_time'    => current_time( 'timestamp', 1 ),
			'last_time' => current_time( 'timestamp', 1 ),
			'data'      => json_encode( $global_data )
		];

		/** In case of duplicate run, when hook runs twice. */
		$already_exists = BWFAN_Model_Automation_Contact::check_duplicate_automation_contact( $data );
		if ( $already_exists ) {
			BWFAN_Common::log_test_data( 'Automation ID ' . $data['aid'] . ' already exists with same data for contact ' . $data['cid'] . '. Event - ' . $data['event'], 'contact-duplicate-automation', true );
			BWFAN_Common::log_test_data( $global_data, 'contact-duplicate-automation', true );

			return false;
		}

		BWFAN_Model_Automation_Contact::insert( $data );
		$p_key = BWFAN_Model_Automation_Contact::insert_id();

		BWFAN_Common::event_advanced_logs( 'Automation started on contact ID: ' . $contact_id );

		$this->update_automation_contact_field( $contact_id, $automation_id );

		/** Check if automation needs to run immediately */
		if ( true === apply_filters( 'bwfan_run_v2_automation_immediately', true, $automation_id, $data ) ) {
			$ins = BWFAN_Automation_V2_Contact::get_instance();
			$ins->bwfan_ac_execute_action( $p_key );
		}

		return $p_key;
	}

	public function update_automation_contact_field( $contact_id, $automation_id ) {
		if ( ! class_exists( 'BWFCRM_Contact' ) ) {
			return;
		}

		/** CRM contact object */
		$contact = new BWFCRM_Contact( $contact_id );

		if ( ! $contact->is_contact_exists() ) {
			return;
		}

		/** Get Automation ids from automation active field */
		$active_automations = $contact->get_field_by_slug( 'automation-active' );
		$active_aids        = json_decode( $active_automations, true );
		$active_aids        = ( ! empty( $active_aids ) && is_array( $active_aids ) ) ? $active_aids : array();

		$active_aids[] = (string) $automation_id;
		$active_aids   = array_unique( $active_aids );
		sort( $active_aids );
		$active_aids = wp_json_encode( $active_aids );

		/** Get automation ids from automation entered field */
		$entered_automations = $contact->get_field_by_slug( 'automation-entered' );
		$entered_aids        = json_decode( $entered_automations, true );
		$entered_aids        = ( ! empty( $entered_aids ) && is_array( $entered_aids ) ) ? $entered_aids : array();

		$entered_aids[] = (string) $automation_id;
		$entered_aids   = array_unique( $entered_aids );
		sort( $entered_aids );
		$entered_aids = wp_json_encode( $entered_aids );

		/** Set updated automation active and automation entered value */
		$contact->set_field_by_slug( 'automation-active', $active_aids );
		$contact->set_field_by_slug( 'automation-entered', $entered_aids );
		$contact->save_fields();
	}

	public function capture_v2_data( $automation_data ) {
		return $automation_data;
	}

	/**
	 * to avoid unserialize of the current class
	 */
	public function __wakeup() {
		throw new ErrorException( 'BWFAN_Core can`t converted to string' );
	}

	/**
	 * to avoid serialize of the current class
	 */
	public function __sleep() {
		throw new ErrorException( 'BWFAN_Core can`t converted to string' );
	}

	/** v2 Methods: END */

	/**
	 * Returns optgroup priority
	 */
	public function get_optgroup_priority() {
		return $this->optgroup_priority;
	}

	protected function validate_order( $data ) {
		if ( ! isset( $data['order_id'] ) ) {
			return false;
		}

		$order = wc_get_order( $data['order_id'] );
		if ( $order instanceof WC_Order ) {
			return true;
		}

		return false;
	}

	protected function validate_subscription( $data ) {
		if ( ! isset( $data['wc_subscription_id'] ) || ! function_exists( 'wcs_get_subscription' ) ) {
			return false;
		}

		$subscription = wcs_get_subscription( $data['wc_subscription_id'] );
		if ( $subscription instanceof WC_Subscription ) {
			return true;
		}

		return false;
	}

	public function is_db_normalize() {
		return false;
	}

	/**
	 * To avoid cloning of current class
	 */
	protected function __clone() {
	}
}
