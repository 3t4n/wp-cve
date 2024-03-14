<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_Importer
 * Handles All the methods about page builder activities
 */
if ( ! class_exists( 'WFFN_Importer' ) ) {
	#[AllowDynamicProperties]

class WFFN_Importer {

		private static $ins = null;
		private $funnel = null;
		private $installed_plugins = null;

		public function __construct() {
		}

		/**
		 * @return WFFN_Importer|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function validate_json( $funnels ) {
			if ( is_array( $funnels ) && count( $funnels ) > 0 ) {
				foreach ( $funnels as $funnel ) {
					if ( isset( $funnel['steps'] ) && is_array( $funnel['steps'] ) && count( $funnel['steps'] ) > 0 ) {
						return true;
					}

				}
			}

			return false;
		}

		public function import_from_json_data( $funnels ) {
			$imported_funnels = [];
			if ( $funnels ) {

				foreach ( $funnels as $funnel ) {
					$funnel_id = isset( $funnel['id'] ) ? $funnel['id'] : 0;
					if ( $funnel_id < 1 ) {
						$funnel_title = $funnel['title'] . ' Copy';
						// Create post object.
						$new_funnel_args = apply_filters( 'wffn_funnel_importer_args', array(
							'title'  => $funnel_title,
							'status' => 1,
						) );

						// Insert the post into the database.
						$funnel_obj = new WFFN_Funnel( $funnel_id );
						if ( $funnel_obj instanceof WFFN_Funnel ) {
							$funnel_id = $funnel_obj->add_funnel( $new_funnel_args );
						}
						do_action( 'wffn_funnel_imported', $funnel_id, $new_funnel_args, $funnels );
					}

					if ( $funnel['steps'] && $funnel_id > 0 ) {
						foreach ( $funnel['steps'] as $step ) {
							$get_type_object = WFFN_Core()->steps->get_integration_object( $step['type'] );
							if ( $get_type_object instanceof WFFN_Step ) {
								$get_type_object->_process_import( $funnel_id, $step );
							}
						}
					}

					array_push( $imported_funnels, $funnel_id );
				}
			}

			return $imported_funnels;
		}

		public function import_store_checkout_json_data( $funnels ) {
			$funnel_id = 0;
			if ( $funnels ) {

				foreach ( $funnels as $funnel ) {
					$funnel_id = isset( $funnel['id'] ) ? $funnel['id'] : 0;
					if ( $funnel_id < 1 ) {
						$funnel_title = $funnel['title'] . ' Copy';
						// Create post object.
						$new_funnel_args = apply_filters( 'wffn_funnel_importer_args', array(
							'title'  => $funnel_title,
							'status' => 1,
						) );

						// Insert the post into the database.
						$funnel_obj = new WFFN_Funnel( $funnel_id );
						if ( $funnel_obj instanceof WFFN_Funnel ) {
							$funnel_id = $funnel_obj->add_funnel( $new_funnel_args );
						}
						do_action( 'wffn_funnel_imported', $funnel_id, $new_funnel_args, $funnels );
					}

					if ( $funnel['steps'] && $funnel_id > 0 ) {
						foreach ( $funnel['steps'] as $step ) {
							if ( in_array( $step['type'], array( 'landing', 'optin', 'optin_ty' ), true ) ) {
								continue;
							}
							if ( WFFN_Common::store_native_checkout_slug() === $step['type'] ) {
								if ( is_array( $step['substeps'] ) && count( $step['substeps'] ) > 0 ) {
									$global_substeps = [];
									foreach ( $step['substeps'] as $key => $substep ) {
										$get_substep_object = WFFN_Core()->substeps->get_integration_object( $key );
										if ( ! empty( $get_substep_object ) ) {
											foreach ( $substep as $substep_single ) {
												$imported_substep_id                = $get_substep_object->_process_import( $substep_single );
												$global_substeps['wc_order_bump'][] = $imported_substep_id;
											}
										}
									}
									WFFN_Common::update_substeps_store_checkout_meta( $funnel_id, $global_substeps );
								}
							} else {
								$get_type_object = WFFN_Core()->steps->get_integration_object( $step['type'] );
								if ( $get_type_object instanceof WFFN_Step ) {
									$get_type_object->_process_import( $funnel_id, $step );
								}
							}
						}
					}
				}
			}

			return $funnel_id;
		}

	}

	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'import', 'WFFN_Importer' );
	}
}
