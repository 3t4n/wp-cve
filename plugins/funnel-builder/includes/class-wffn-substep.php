<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will be extended by all all single substep(like ordebump) to register different substeps
 * Class WFFN_Substep
 */
if ( ! class_exists( 'WFFN_Substep' ) ) {
	#[AllowDynamicProperties]

 abstract class WFFN_Substep extends WFFN_Step_Base {

		public $slug = '';
		public $id;

		/**
		 * WFFN_Substep constructor.
		 *
		 * @param int $id
		 */
		public function __construct( $id = 0 ) {
			$this->id = $id;
		}

		/**
		 * @param $current_step
		 * @param $funnel
		 *
		 * @return array
		 */
		public function maybe_get_substeps( $current_step, $funnel ) {
			$steps = $funnel->get_steps();
			foreach ( $steps as $step ) {
				if ( absint( $step['id'] ) === absint( $current_step['id'] ) && isset( $step['substeps'] ) && isset( $step['substeps'][ $this->slug ] ) ) {

					return array_filter( $step['substeps'][ $this->slug ], function ( $k ) {
						if ( $this->is_disabled( $this->get_entity_status( $k ) ) ) {   //phpcs:ignore WordPressVIPMinimum.Variables.VariableAnalysis.UndefinedVariable
							return false;
						}

						return $k;
					} );
				}
			}

			return [];
		}

		public function get_substep_data() {
			return [];
		}
	}
}
