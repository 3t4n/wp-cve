<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Handles the operations and usage of steps in funnel
 * Class WFFN_Steps
 */
if ( ! class_exists( 'WFFN_Steps' ) ) {
	class WFFN_Steps {

		/**
		 * @var null
		 */
		public static $ins = null;

		/**
		 * @var WFFN_Steps[]
		 */
		public $steps = array();

		/**
		 * Step classes prefix
		 * @var string
		 */
		public $class_prefix = 'WFFN_Step_';

		const STEP_GROUP_SALES = 'sales';
		const STEP_GROUP_WC = 'wc';

		/**
		 * WFFN_Steps constructor.
		 */
		public function __construct() {
			add_action( 'wffn_loaded', array( $this, 'load_steps' ) );
		}

		/**
		 * @return WFFN_Steps|null
		 * @throws Exception
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * @return WFFN_Step[]
		 */
		public function get_supported_steps() {
			return $this->steps;
		}

		/**
		 * @param $step_class
		 *
		 * @return false|WFFN_Step
		 */
		public function get_integration_object( $step_class ) {

			if ( isset( $this->steps[ $step_class ] ) ) {
				return $this->steps[ $step_class ];
			}

			return false;
		}

		/**
		 * @param $step WFFN_Step
		 *
		 * @throws Exception
		 */
		public function register( $step ) {
			if ( ! is_subclass_of( $step, 'WFFN_Step' ) ) {
				throw new Exception( __( 'Must be a subclass of WFFN_Step', 'funnel-builder' ) );
			}
			if ( empty( $step->slug ) ) {
				throw new Exception( __( 'The type must be set', 'funnel-builder' ) );
			}
			if ( isset( $this->steps[ $step->slug ] ) ) {
				throw new Exception( sprintf( __( 'Step type already registered: %s', 'funnel-builder' ), $step->slug ) );
			}

			if ( false === $step->should_register() ) {
				return;
			}
			$this->steps[ $step->slug ] = $step;
		}

		/**
		 * Includes steps files
		 *
		 */
		public function load_steps() {
			// load all the trigger files automatically
			foreach ( glob( plugin_dir_path( WFFN_PLUGIN_FILE ) . 'steps/*/class-*.php' ) as $steps_file_name ) {
				require_once( $steps_file_name ); //phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			}
		}

		public function get_step_groups() {
			return array(
				self::STEP_GROUP_SALES => array( 'title' => __( 'Sales', 'funnel-builder' ) ),
				self::STEP_GROUP_WC    => array( 'title' => __( 'WooCommerce', 'funnel-builder' ) ),
			);
		}
	}

	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'steps', 'WFFN_Steps' );
	}
}

