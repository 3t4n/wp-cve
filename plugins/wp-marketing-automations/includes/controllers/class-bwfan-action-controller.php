<?php
#[AllowDynamicProperties]
class BWFAN_Action_Controller extends BWFAN_Base_Step_Controller {

	/** @var BWFAN_Action $action_ins */
	private $action_ins = null;

	/** @var BWFAN_Event $event_ins */
	private $event_ins = null;

	/** Sidebar data of node */
	private $step_action_data = array();

	private $integration = null;

	public function populate_step_data( $db_step = array() ) {
		if ( parent::populate_step_data( $db_step ) ) {
			if ( isset( $this->step_data['sidebarData'] ) ) {
				$this->step_action_data = $this->step_data['sidebarData'];
			}

			return $this->init_action_instance();
		}

		return false;
	}

	private function init_action_instance() {
		if ( ! is_array( $this->action_data ) || ! isset( $this->action_data['action'] ) ) {
			return false;
		}

		$action_slug      = $this->action_data['action'];
		$this->action_ins = BWFAN_Core()->integration->get_action( $action_slug );

		return $this->action_ins instanceof BWFAN_Action;
	}

	public function __get( $key ) {
		if ( 'call' === $key ) {
			return 'wfco_' . $this->action_ins->get_slug();
		}
	}

	public function execute_action() {
		if ( ! $this->action_ins instanceof BWFAN_Action ) {
			return array(
				'status'  => BWFAN_Action::$RESPONSE_FAILED,
				'message' => __( 'Action object not defined', 'wp-marketing-automations' ),
			);
		}

		$connector_data = $this->set_connectors_data();
		if ( ! empty( $connector_data ) ) {
			$this->step_action_data['connector_data'] = $connector_data;
		}

		if ( empty( $this->automation_id ) ) {
			return array(
				'status'  => BWFAN_Action::$RESPONSE_FAILED,
				'message' => __( 'Automation ID is not provided', 'wp-marketing-automations' ),
			);
		}

		$this->set_merge_tags_data();

		/** Add Automation ID to Unsubscribe Link */
		$this->action_ins->automation_id = $this->automation_id;
		$this->action_ins->parse_unsubscribe_link();
		$this->automation_data['step_id']       = $this->step_id;
		$this->automation_data['automation_id'] = $this->automation_id;

		/** Generate Processed data and set it to action's instance  */
		$processed_data = $this->action_ins->make_v2_data( $this->automation_data, $this->step_action_data );

		/** Fallback if not available */
		if ( ! isset( $processed_data['automation_id'] ) ) {
			$processed_data['automation_id'] = $this->automation_id;
		}
		if ( ! isset( $processed_data['step_id'] ) ) {
			$processed_data['step_id'] = $this->step_id;
		}
		if ( ! isset( $processed_data['current_language'] ) ) {
			$processed_data['current_language'] = isset( $this->automation_data['global'] ) && isset( $this->automation_data['global']['language'] ) ? $this->automation_data['global']['language'] : '';
		}

		$processed_data['automation_contact_id'] = $this->automation_contact_id;

		/** Add automation and track id in Abandoned restore link */
		add_filter( 'bwfan_abandoned_cart_restore_link', array( $this, 'add_automation_id_track_link_in_restore_url' ) );

		try {
			/** Process the action and return [status & message] */
			$result = $this->process( $processed_data );
		} catch ( Error $e ) {
			return $this->action_failed( $e->getMessage() );
		}

		if ( ! is_array( $result ) || ( isset( $result['status'] ) && BWFAN_Action::$RESPONSE_FAILED === $result['status'] ) ) {
			$message = is_array( $result ) ? $result['message'] : __( 'Unknown Error Occurred!', 'wp-marketing-automations' );

			return $this->action_failed( $message );
		}

		return $result;
	}

	/** Set Connector Data, if available */
	private function set_connectors_data() {
		$integration       = $this->action_ins->get_integration_type();
		$this->integration = BWFAN_Core()->integration->get_integration( $integration );
		if ( ! $this->integration instanceof BWFAN_Integration || ! $this->integration->need_connector() ) {
			return false;
		}

		$connector = $this->integration->get_connector_slug();

		WFCO_Common::get_connectors_data();
		$global_settings = WFCO_Common::$connectors_saved_data;
		if ( empty( $connector ) || ! isset( $global_settings[ $connector ] ) ) {
			return false;
		}

		$this->integration->set_settings( $global_settings[ $connector ] );

		return $global_settings[ $connector ];
	}

	public function set_merge_tags_data() {
		if ( empty( $this->automation_data ) ) {
			return false;
		}

		BWFAN_Merge_Tag_Loader::reset_data();
		BWFAN_Merge_Tag_Loader::set_data( $this->automation_data['global'] );
		$this->set_user_language();
	}

	/** Set User Language in Merge Tags */
	private function set_user_language() {
		$this->maybe_populate_event_instance();

		/** Set language for decode */
		if ( ! $this->event_ins->support_lang ) {
			return false;
		}

		$language = BWFAN_Merge_Tag_Loader::get_data( 'language' );
		if ( empty( $language ) ) {
			$language = array(
				'language' => $this->event_ins->get_language_from_event( $this->automation_data['global'] ),
			);

			BWFAN_Merge_Tag_Loader::set_data( $language );
		}
	}

	/** populate event instance if available */
	private function maybe_populate_event_instance() {
		if ( $this->event_ins instanceof BWFAN_Event ) {
			return true;
		}

		if ( ! is_array( $this->automation_data ) || ! isset( $this->automation_data['event_data'] ) ) {
			return false;
		}

		$event_data = $this->automation_data['event_data'];
		if ( ! is_array( $event_data ) || ! isset( $event_data['event_slug'] ) ) {
			return false;
		}

		$event           = $event_data['event_slug'];
		$this->event_ins = BWFAN_Core()->sources->get_event( $event );

		return true;
	}

	public function process( $processed_data ) {
		/** If connector related action */
		if ( ! empty( $this->integration ) && $this->integration->need_connector() ) {
			$load_connector = WFCO_Load_Connectors::get_instance();
			$call_class     = $load_connector->get_call( $this->call );
			if ( is_null( $call_class ) ) {
				return __( 'Call class not found!', 'wp-marketing-automations' );
			}
			$call_class->set_data( $processed_data );
			$result = $call_class->process();
			$result = $this->integration->handle_response( $result, $this->connector, $this->call );
			$result = $this->action_ins->handle_response_v2( $result );

			return $result;
		}

		/** Direct action execution */
		$this->action_ins->reset_data();
		$this->action_ins->set_data( $processed_data );

		return $this->action_ins->process_v2();
	}

	/**
	 * If attempts possible return execution time or failed status
	 *
	 * @param $message
	 *
	 * @return array
	 */
	private function action_failed( $message ) {
		$this->attempts ++;
		$attempt_limit = $this->action_ins->get_action_retry_data();

		if ( ! is_array( $attempt_limit ) || ( count( $attempt_limit ) < $this->attempts ) || ! isset( $attempt_limit[ $this->attempts - 1 ] ) ) {
			return array(
				'status'  => BWFAN_Action::$RESPONSE_FAILED,
				'message' => $message,
			);
		}

		return array(
			'status'  => BWFAN_Action::$RESPONSE_REATTEMPT,
			'message' => $message,
			'e_time'  => time() + ( $attempt_limit[ $this->attempts - 1 ] ), // for attempt 2 pass 2nd node element in array
		);
	}

	public function add_automation_id_track_link_in_restore_url( $restore_url ) {
		$restore_url = add_query_arg( array(
			'automation-id' => $this->automation_id,
		), $restore_url );

		return $restore_url;
	}
}
