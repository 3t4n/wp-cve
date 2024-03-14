<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

if ( ! class_exists( 'WFFN_Data' ) ) {
	class WFFN_Data extends WFFN_Session_Handler {

		private static $ins = null;
		protected $cache = array();
		private $page_id = false;
		private $page_link = false;
		private $order_id = false;
		private $order = false;
		private $page_layout = false;
		private $page_layout_info = false;
		private $options = null;

		public function __construct() {
			add_action( 'wffn_global_settings', array( $this, 'sanitize_scripts' ), 10 );

			/**
			 * As we have extended the class 'WFOCU_Session_Handler', We have to create a construct over there and not using native register method.
			 */
			parent::__construct();

		}

		public static function get_instance() {
			if ( self::$ins === null ) {
				self::$ins = new self;
			}

			return self::$ins;
		}


		/**
		 * @return array|false|mixed|object|string
		 */
		public function get_current_step() {
			return $this->get( 'current_step', false );
		}


		public function sanitize_scripts( $options ) {

			if ( $options && ( isset( $options['scripts'] ) && '' !== $options['scripts'] ) ) {
				$options['scripts'] = stripslashes_deep( $options['scripts'] );
			}

			if ( $options && ( isset( $options['scripts_head'] ) && '' !== $options['scripts_head'] ) ) {
				$options['scripts_head'] = stripslashes_deep( $options['scripts_head'] );
			}

			return $options;
		}

		/**
		 * Find the next url to open in the funnel
		 *
		 * @param $current_step_id array Id to take into account to search for the next link
		 *
		 * @return bool|false|string
		 */
		public function get_next_url( $current_step_id ) {

			$get_funnel            = $this->get_session_funnel();
			$get_next_step         = $this->get_next_step( $get_funnel, $current_step_id );
            if (false === $get_next_step) {
                return false;
            }
			$get_next_step['type'] = isset( $get_next_step['type'] ) ? $get_next_step['type'] : '';
			$get_step_object       = WFFN_Core()->steps->get_integration_object( $get_next_step['type'] );

			if ( ! empty( $get_step_object ) && $get_step_object->supports( 'next_link' ) ) {

				$properties = $get_step_object->populate_data_properties( $get_next_step, $get_funnel->get_id() );

				$id = $get_next_step['id'];

				if ( $get_step_object->is_disabled( $get_step_object->get_enitity_data( $properties['_data'], 'status' ) ) ) {

					return $this->get_next_url( $id );
				}

				return $get_step_object->get_url( $id );
			}

			return false;
		}


		/**
		 * @return WFFN_Funnel|null
		 */
		public function get_session_funnel() {

			return $this->get( 'funnel', false );

		}

		/**
		 * Loop over the current funnel running and compare the steps against the current one
		 * Find out if the next step available & return
		 *
		 * @param $funnel WFFN_Funnel
		 * @param $current_step
		 *
		 * @return array|bool
		 */
		public function get_next_step( $funnel, $current_step ) {
			$current_step = apply_filters( 'wffn_maybe_get_ab_control', $current_step );
			$next_step    = $funnel->get_next_step_id( absint( $current_step ) );
			if ( is_array( $next_step ) ) {
				return $next_step;
			}

			return false;
		}

	}

	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'data', 'WFFN_Data' );
	}
}
