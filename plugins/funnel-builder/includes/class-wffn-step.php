<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will be extended by all all single step(like upstroke, aero etc) to register different steps
 * Class WFFN_Step
 */
if ( ! class_exists( 'WFFN_Step' ) ) {
	#[AllowDynamicProperties]

 abstract class WFFN_Step extends WFFN_Step_Base {

		public $slug = '';
		public $substeps = [];
		public $id;
		public $list_priority;
		public $step_group;
		public $step_type;

		public function set_type( $type = '' ) {
			$this->step_type = $type;
		}

		/**
		 * WFFN_Step constructor.
		 *
		 * @param string $id
		 */
		public function __construct( $id = '' ) {
			$this->id = $id;
		}

		/**
		 * @return array|void
		 */
		public function get_supports() {
			return [ 'drag' ];
		}

		/**
		 * @param $funnel
		 * @param $step_id
		 * @param $duplicate_step_id
		 *
		 * @return array|mixed
		 */
		public function maybe_duplicate_substeps( $funnel, $step_id, $duplicate_step_id ) {
			$funnel_id = $funnel;
			if ( is_array( $funnel ) && isset( $funnel['duplicate_funnel_id'] ) ) {
				$funnel_id = $funnel['funnel_id'];
				$substeps  = $this->get_substeps( $funnel['duplicate_funnel_id'], $step_id );
			} else {
				$substeps = $this->get_substeps( $funnel_id, $step_id );
			}

			$duplicated_substeps = array();
			foreach ( ( is_array( $substeps ) && count( $substeps ) > 0 ) ? $substeps : array() as $subtype => $substep_ids ) {
				$duplicated_substeps = $this->duplicate_substeps( $funnel_id, $step_id, $duplicate_step_id, $subtype, $substep_ids, $duplicated_substeps );
			}

			return $duplicated_substeps;
		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param $duplicate_step_id
		 * @param $subtype
		 * @param $substep_ids
		 * @param $duplicated_substeps
		 *
		 * @return mixed
		 */
		public function duplicate_substeps( $funnel_id, $step_id, $duplicate_step_id, $subtype, $substep_ids, $duplicated_substeps ) {
			foreach ( ( is_array( $substep_ids ) && count( $substep_ids ) > 0 ) ? $substep_ids : array() as $substep_key => $substep_id ) {
				$duplicated_substeps = $this->duplicate_single_substep( $funnel_id, $step_id, $duplicate_step_id, $subtype, $substep_id, $substep_key, $duplicated_substeps );
			}

			return $duplicated_substeps;
		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param $duplicate_step_id
		 * @param $subtype
		 * @param $substep_id
		 * @param $substep_key
		 * @param $duplicated_substeps
		 *
		 * @return mixed
		 */
		public function duplicate_single_substep( $funnel_id, $step_id, $duplicate_step_id, $subtype, $substep_id, $substep_key = 0, $duplicated_substeps = [] ) {
			$get_substep = WFFN_Core()->substeps->get_integration_object( $subtype );
			if ( $get_substep instanceof WFFN_Substep ) {
				$duplicated_substeps = $get_substep->duplicate_single_substep( $funnel_id, $step_id, $duplicate_step_id, $subtype, $substep_id, $substep_key, $duplicated_substeps );
			}

			return $duplicated_substeps;
		}

		public function _process_import( $funnel_id, $step_data ) { //phpcs:ignore
		}

		public function maybe_ecomm_events( $events ) { //phpcs:ignore  VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

		}

	}
}
