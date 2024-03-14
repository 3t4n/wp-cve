<?php

class BWFAN_API_Update_Automation_Step extends BWFAN_API_Base {
	public static $ins;
	public $total_count = 0;

	public function __construct() {
		parent::__construct();
		$this->method       = WP_REST_Server::EDITABLE;
		$this->route        = '/automation/(?P<automation_id>[\\d]+)/step/(?P<step_id>[\\d]+)';
		$this->request_args = array(
			'automation_id' => array(
				'description' => __( 'Automation ID to retrieve', 'wp-marketing-automations-crm' ),
				'type'        => 'integer',
			),
			'step_id'       => array(
				'description' => __( 'Step ID to update', 'wp-marketing-automations-crm' ),
				'type'        => 'integer',
			),
		);
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function process_api_call() {
		$automation_id = $this->get_sanitized_arg( 'automation_id', 'text_field' );
		$step_id       = $this->get_sanitized_arg( 'step_id', 'text_field' );
		$arg_data      = $this->args;
		/** Initiate automation object */
		$automation_obj = BWFAN_Automation_V2::get_instance( $automation_id );

		/** Check for automation exists */
		if ( ! empty( $automation_obj->error ) ) {
			return $this->error_response( [ "Step Not Updated" ], $automation_obj->error );
		}

		// Update step data
		$data          = $steps = $links = [];
		$count         = 0;
		$optionUpdated = false;

		/** Action table data */
		if ( isset( $arg_data['data'] ) && ! empty( $arg_data['data'] ) ) {
			$data = $arg_data['data'];
		}

		/** Step data */
		if ( isset( $arg_data['steps'] ) && ! empty( $arg_data['steps'] ) ) {
			$steps = $arg_data['steps'];
		}

		/** Link data */
		if ( isset( $arg_data['links'] ) && ! empty( $arg_data['links'] ) ) {
			$links = $arg_data['links'];
		}

		/** Node count */
		if ( isset( $arg_data['count'] ) && intval( $arg_data['count'] ) > 0 ) {
			$count = intval( $arg_data['count'] );
		}

		/** Update automation data */
		if ( ! empty( $steps ) && ! empty( $links ) ) {
			$automation_obj->update_automation_meta_data( [], $steps, $links, $count );
		}

		/** Benchmark Option Updated data */
		if ( isset( $arg_data['optionUpdated'] ) && ! empty( $arg_data['optionUpdated'] ) ) {
			$optionUpdated = $arg_data['optionUpdated'];
		}

		$update_status = 1;

		/** Update status */
		if ( isset( $arg_data['updateStatus'] ) && ! empty( $arg_data['updateStatus'] ) ) {
			$update_status = intval( $arg_data['updateStatus'] );
		}

		$step_data      = BWFAN_Model_Automation_Step::get_step_data_by_id( $step_id );
		$action         = isset( $step_data['action'] ) ? json_decode( $step_data['action'], true ) : [];
		$action_name    = isset( $action['action'] ) ? $action['action'] : '';
		$benchmark_name = isset( $action['benchmark'] ) ? $action['benchmark'] : '';

		if ( in_array( $action_name, [ 'crm_add_to_list', 'crm_add_tag' ] ) ) {
			/** Create List/tags if not exists */
			$terms = [];
			if ( class_exists( 'BWFCRM_Term_Type' ) ) {
				$term_type = BWFCRM_Term_Type::$TAG;
				$terms     = isset( $data['data']['sidebarData']['tags'] ) ? $data['data']['sidebarData']['tags'] : [];
				if ( empty( $terms ) && 'crm_add_to_list' === $action_name ) {
					$term_type = BWFCRM_Term_Type::$LIST;
					$terms     = isset( $data['data']['sidebarData']['list_id'] ) ? $data['data']['sidebarData']['list_id'] : [];
				}
			}

			$terms_with_mergetags = [];
			foreach ( $terms as $index => $term ) {
				if ( 0 !== absint( $term['id'] ) || false === strpos( $term['name'], '}}' ) ) {
					continue;
				}
				unset( $terms[ $index ] );
				$terms_with_mergetags[] = $term;
			}

			if ( is_array( $terms ) && ! empty( $terms ) && method_exists( 'BWFAN_PRO_Common', 'get_or_create_terms' ) ) {
				$terms = BWFAN_PRO_Common::get_or_create_terms( $terms, $term_type );
			}

			$terms = array_merge( $terms, $terms_with_mergetags );

			/** Checking action is Added to List and Add Tag */
			if ( 'crm_add_to_list' === $action_name && ! empty( $terms ) ) {
				$data['data']['sidebarData']['list_id'] = $terms;
			} elseif ( 'crm_add_tag' === $action_name && ! empty( $terms ) ) {
				$data['data']['sidebarData']['tags'] = $terms;
			}
		}

		/** If no setting in action */
		if ( empty( $action_name ) && isset( $data['action'] ) && isset( $data['action']['action'] ) ) {
			$action_name   = $data['action']['action'];
			$action        = BWFAN_Core()->integration->get_action( $action_name );
			$fields_schema = $action instanceof BWFAN_Action ? $action->get_fields_schema() : null;
			if ( ! is_null( $fields_schema ) && empty( $fields_schema ) ) {
				$data['data']  = [ 'sidebarData' => [] ];
				$update_status = 1;
			}
		}

		/** update step data */
		$result = $automation_obj->update_automation_step_data( $step_id, $data, $update_status, $optionUpdated );
		if ( $result ) {
			$return_val = [];
			if ( ! empty( $action_name ) ) {
				$action = ( $action instanceof BWFAN_Action ) ? $action : BWFAN_Core()->integration->get_action( $action_name );
				if ( method_exists( $action, 'get_desc_text' ) && isset( $data['data'] ) && isset( $data['data']['sidebarData'] ) ) {
					$return_val['desc_text'] = $action->get_desc_text( $data['data']['sidebarData'] );
				}
			}
			if ( ! empty( $benchmark_name ) ) {
				$benchmark = BWFAN_Core()->sources->get_event( $benchmark_name );
				if ( method_exists( $benchmark, 'get_desc_text' ) && isset( $data['data'] ) && isset( $data['data']['sidebarData'] ) ) {
					$return_val['desc_text'] = $benchmark->get_desc_text( $data['data']['sidebarData'] );
				}
			}
			$this->response_code = 200;

			return $this->success_response( $return_val, __( 'Step Updated', 'wp-marketing-automations' ) );
		} else {
			return $this->error_response( [ "Step Not Updated" ], 'Unable to update action data' );
		}
	}
}

BWFAN_API_Loader::register( 'BWFAN_API_Update_Automation_Step' );