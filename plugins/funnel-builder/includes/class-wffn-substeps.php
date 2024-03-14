<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Handles the operations and usage of substeps in funnel
 * Class WFFN_Substeps
 */
if ( ! class_exists( 'WFFN_Substeps' ) ) {
	class WFFN_Substeps {

		/**
		 * @var null
		 */
		public static $ins = null;

		/**
		 * @var WFFN_Substeps[]
		 */
		public $substeps = array();

		/**
		 * WFFN_Substeps constructor.
		 */
		public function __construct() {
			add_action( 'wffn_loaded', array( $this, 'load_substeps' ) );
		}

		/**
		 * @return WFFN_Substeps|null
		 * @throws Exception
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * @return WFFN_Substep[]
		 */
		public function get_supported_substeps() {
			return $this->substeps;
		}

		/**
		 * @param $substep_class
		 *
		 * @return bool|WFFN_Substep
		 */
		public function get_integration_object( $substep_class ) {

			if ( isset( $this->substeps[ $substep_class ] ) ) {
				return $this->substeps[ $substep_class ];
			}

			return false;
		}

		/**
		 * @param $substep
		 *
		 * @throws Exception
		 */
		public function register( $substep ) {

			if ( ! is_subclass_of( $substep, 'WFFN_Substep' ) ) {
				throw new Exception( __( 'Must be a subclass of WFFN_Substep', 'funnel-builder' ) );
			}
			if ( empty( $substep->slug ) ) {
				throw new Exception( __( 'The type must be set', 'funnel-builder' ) );
			}
			if ( isset( $this->steps[ $substep->slug ] ) ) {
				throw new Exception( sprintf( __( 'Step type already registered: %s', 'funnel-builder' ), $substep->slug ) );
			}

			if ( false === $substep->should_register() ) {
				return;
			}
			$this->substeps[ $substep->slug ] = $substep;
		}

		/**
		 * Loading substeps files
		 */
		public function load_substeps() {
			// load all the trigger files automatically
			foreach ( glob( plugin_dir_path( WFFN_PLUGIN_FILE ) . 'substeps/*/class-*.php' ) as $substep_file_name ) {
				require_once( $substep_file_name ); //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			}
		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param array $subtypes
		 *
		 * @return array|mixed
		 */
		public function get_substeps( $funnel_id, $step_id, $subtypes = array() ) {
			$substeps = array();
			$funnel   = WFFN_Core()->admin->get_funnel( $funnel_id );
			$steps    = $funnel->get_steps();
			$search   = array_search( absint( $step_id ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );
			$step     = $steps[ $search ];

			if ( isset( $step['substeps'] ) && count( $step['substeps'] ) > 0 ) {
				$substeps = $step['substeps'];
				if ( count( $subtypes ) > 0 ) {
					foreach ( array_keys( $substeps ) as $substep ) {
						if ( ! in_array( $substep, $subtypes, true ) ) {
							unset( $substeps[ $substep ] );
						}
					}
				}
			}

			return $substeps;
		}
	}

	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'substeps', 'WFFN_Substeps' );
	}
}
