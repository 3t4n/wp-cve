<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Funnel entity class
 * Class WFFN_Funnel
 */
if ( ! class_exists( 'WFFN_Funnel' ) ) {
	#[AllowDynamicProperties]
	class WFFN_Funnel {
		private static $ins = null;

		/**
		 * @var $id
		 */
		public $id = 0;
		public $title = '';
		public $desc = '';
		public $date_added = null;
		public $steps = [];
		public $slug = '';

		/**
		 * WFFN_Funnel constructor..
		 * @since  1.0.0
		 */
		public function __construct( $id = 0 ) {
			$this->id   = $id;
			$this->slug = WFFN_Common::get_funnel_slug();

			if ( $this->id > 0 ) {
				$data = WFFN_Core()->get_dB()->get( $this->id );

				if ( ! empty( $data ) && is_array( $data ) ) {
					foreach ( $data as $col => $value ) {
						if ( 'steps' === $col ) {

							$stepsdb = json_decode( $value, true );
							if ( ! isset( $stepsdb[0]['id'] ) ) {
								$this->steps = [];
								continue;
							}
							$this->steps = $stepsdb;
						} else {
							$this->$col = $value;
						}
					}
				} else {
					$this->id = 0;
				}
			}
		}

		/**
		 * @return WFFN_Funnel|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function get_title() {
			return $this->title;
		}

		public function set_title( $title ) {
			$this->title = $title;
		}

		public function set_global() {
			$this->is_global = 1;
		}

		public function get_delete_data() {
			return array(
				'type'      => $this->slug,
				'is_funnel' => true,
				'title'     => __( 'Are you sure you want to delete this funnel?', 'funnel-builder' ),
				'subtitle'  => __( 'All the steps in this funnel will also be deleted.', 'funnel-builder' ),
			);
		}

		public function get_duplicate_data() {
			return array(
				'type'     => $this->slug,
				'title'    => __( 'Are you sure you want to duplicate this funnel?', 'funnel-builder' ),
				'subtitle' => __( 'All the steps in this funnel will also be duplicated.', 'funnel-builder' ),
			);
		}



		/**
		 * @param $funnel_data
		 *
		 * @return mixed
		 */
		public function add_funnel( $funnel_data ) {
			if ( isset( $funnel_data['title'] ) ) {
				$this->set_title( $funnel_data['title'] );
			}
			if ( isset( $funnel_data['desc'] ) ) {
				$this->set_desc( $funnel_data['desc'] );
			}

			$this->set_date_added( current_time( 'mysql' ) );

			return $this->save();
		}

		/**
		 * Create/update Funnel using given or set funnel data
		 *
		 * @param $data
		 *
		 * @return mixed
		 */
		public function save( $data = array() ) {
			if ( count( $data ) > 0 ) {
				foreach ( $data as $col => $value ) {
					$this->$col = $value;
				}
			}
			$funnel_data               = array();
			$funnel_data['title']      = $this->get_title();
			$funnel_data['desc']       = $this->get_desc();
			$funnel_data['date_added'] = $this->get_date_added();
			$funnel_data['steps']      = wp_json_encode( $this->get_steps() );

			$funnel_id = $this->get_id();

			if ( $funnel_id > 0 ) {
				$updated = WFFN_Core()->get_dB()->update( $funnel_data, array( 'id' => $funnel_id ) );
				do_action( 'wffn_funnel_update', $funnel_id, $funnel_data );
				WFFN_Core()->get_dB()->clear_cache();
				WFFN_Core()->logger->log( 'Funnel ID #' . $funnel_id . ': ' . print_r( wp_debug_backtrace_summary(), true ), 'wffn', true );//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_wp_debug_backtrace_summary,WordPress.PHP.DevelopmentFunctions.error_log_print_r

				return false === $updated ? $updated : $funnel_id;
			}

			WFFN_Core()->get_dB()->insert( $funnel_data );
			$funnel_id = WFFN_Core()->get_dB()->insert_id();
			WFFN_Core()->get_dB()->update_meta( $funnel_id, '_version', WFFN_VERSION );
			WFFN_Core()->get_dB()->clear_cache();

			return $funnel_id;
		}

		public function get_desc() {
			return $this->desc;
		}

		public function set_desc( $desc ) {
			return $this->desc = $desc;
		}

		public function get_date_added() {
			return $this->date_added;
		}

		public function get_last_update_date() {
			return WFFN_Core()->get_dB()->get_meta( $this->id, '_last_updated_on' );
		}


		public function set_date_added( $date_added ) {
			return $this->date_added = $date_added;
		}

		public function get_steps( $populated = false ) {
			return ( true === $populated ) ? $this->prepare_steps( $this->steps ) : $this->steps;
		}

		public function get_step_count() {
			return count( $this->steps );
		}

		public function set_steps( $steps ) {
			$this->steps = $steps;
		}

		/**
		 * @param $steps
		 *
		 * @return array
		 */
		public function prepare_steps( $steps ) {
			$get_all_registered_steps = WFFN_Core()->steps->get_supported_steps();
			$result_steps             = array();
			foreach ( $steps as $key => &$step ) {
				/**
				 * IF we do not found the current step in our registered steps, then remove this step
				 */
				if ( ! in_array( $step['type'], array_keys( $get_all_registered_steps ), true ) ) {
					unset( $steps[ $key ] );
					continue;
				}
				/**
				 * if admin side actions then we need to initiate all data
				 */
				$get_object     = WFFN_Core()->steps->get_integration_object( $step['type'] );
				$result_steps[] = $get_object->populate_data_properties( $step, $this->get_id() );
			}

			return $result_steps;
		}

		public function get_id() {
			return $this->id;
		}

		public function set_id( $id ) {
			$this->id = empty( $id ) ? $this->id : $id;
		}


		/**
		 * @return bool
		 */
		public function delete() {
			$funnel_id = $this->get_id();
			$deleted   = false;
			if ( $funnel_id > 0 ) {
				$funnel_steps = $this->get_steps();
				foreach ( $funnel_steps as $funnel_step ) {
					$type    = isset( $funnel_step['type'] ) ? $funnel_step['type'] : '';
					$step_id = isset( $funnel_step['id'] ) ? $funnel_step['id'] : 0;
					if ( ! empty( $type ) ) {
						$get_step = WFFN_Core()->steps->get_integration_object( $type );
						if ( 0 < $step_id && $get_step instanceof WFFN_Step ) {
							$get_step->delete_step( $funnel_id, $step_id );
						} elseif ( 0 <= $step_id ) {
							$this->delete_step( $funnel_id, $step_id );

							if ( get_post( $step_id ) instanceof WP_Post ) {
								wp_delete_post( $step_id );
							}
						}
					}
				}
				if ( 0 === count( $this->get_steps() ) ) {
					$deleted = true;
					WFFN_Core()->get_dB()->delete( $funnel_id );
					WFFN_Core()->get_dB()->clear_cache();
				} else {
					WFFN_Core()->logger->log( 'Something wrong funnel not deleted #' . $funnel_id, 'wffn', true ); // phpcs:ignore

				}
			}

			return $deleted;
		}

		/**
		 * @param $type
		 * @param $step_id
		 * @param $original_id
		 *
		 * @return false|int|mixed
		 */
		public function add_step( $type, $step_id, $original_id ) {
			$steps          = $this->get_steps();
			$store_checkout = ( 'wc_checkout' === $type ) && ( absint( $this->get_id() ) === WFFN_Common::get_store_checkout_id() ) ? true : false;
			if ( $original_id > 0 ) {
				$position = array_search( absint( $original_id ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );
				if ( false !== $position ) {
					array_splice( $steps, ( $position + 1 ), 0, array( array( 'type' => $type, 'id' => $step_id ) ) );
				} else {
					if ( true === $store_checkout ) {
						array_unshift( $steps, array(
							'type' => $type,
							'id'   => $step_id,
						) );
					} else {
						array_push( $steps, array(
							'type' => $type,
							'id'   => $step_id,
						) );
					}
				}
			} else {
				if ( true === $store_checkout ) {
					array_unshift( $steps, array(
						'type' => $type,
						'id'   => $step_id,
					) );
				} else {
					array_push( $steps, array(
						'type' => $type,
						'id'   => $step_id,
					) );
				}
			}

			$this->set_steps( $steps );

			return $this->save( array() );
		}

		/**
		 * @param $step_id
		 * @param $canvas
		 *
		 * @return void
		 */
		public function maybe_update_canvas( $step_id, $canvas ) {
			$step_id = absint( $step_id );

			if ( 0 === $step_id || ! is_array( $canvas ) ) {
				return;
			}

			$steps = $this->get_steps();
			/**
			 * Update created substep position
			 */
			if ( ! empty( $canvas['parent_id'] ) && ! empty( $canvas['insert_after'] ) ) {
				/**
				 * find parent step position
				 */
				$parent_position = array_search( absint( $canvas['parent_id'] ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );

				if ( false !== $parent_position ) {
					/**
					 * update bump step position
					 */
					if ( 'wc_order_bump' === $canvas['type'] && ! empty( $steps[ $parent_position ]['substeps'] ) ) {
						$substeps = $steps[ $parent_position ]['substeps'][ $canvas['type'] ];

						$current_step_position = array_search( $step_id, $substeps, true );
						$insert_after_position = array_search( absint( $canvas['insert_after'] ), $substeps, true );
						if ( false !== $insert_after_position ) {
							array_splice( $substeps, $insert_after_position + 1, 0, $step_id );
							if ( isset( $substeps[ $current_step_position + 1 ] ) ) {
								unset( $substeps[ $current_step_position + 1 ] );
							}
						}

						$steps[ $parent_position ]['substeps'][ $canvas['type'] ] = $substeps;

					}
				}
			} else if ( ! empty( $canvas['insert_after'] ) || 0 === $canvas['insert_after'] ) {

				/**
				 * update step position
				 * find insert after step position
				 */
				$current_step_position = array_search( $step_id, array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );
				/**
				 * set insert after for set sibling steps
				 */
				if ( 'start_group' !== $canvas['mode'] && false !== $current_step_position ) {
					$steps[ $current_step_position ]['insert_after'] = absint( $canvas['insert_after'] );
				}

				$is_store_funnel = absint( $this->get_id() ) === WFFN_Common::get_store_checkout_id();

				$insert_after_position = ( 'start' === $canvas['insert_after'] ) || ( $is_store_funnel && 0 === absint( $canvas['insert_after'] ) ) ? -1 : array_search( absint( $canvas['insert_after'] ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );
				if ( false !== $insert_after_position && false !== $current_step_position ) {
					array_splice( $steps, $insert_after_position + 1, 0, array( $steps[ $current_step_position ] ) );

					/**
					 * handle store checkout steps
					 */
					if ( $is_store_funnel && 'wc_checkout' === $steps[ $current_step_position ]['type']  ) {
						unset( $steps[ $current_step_position ] );
					}elseif ( isset( $steps[ $current_step_position + 1 ] ) ) {
						unset( $steps[ $current_step_position + 1 ] );
					}
				}
			}

			$this->set_steps( $steps );

			$this->save( array() );
		}

		/**
		 * @param $step_id
		 * @param $substep_id
		 * @param $type
		 *
		 * @return mixed
		 */
		public function add_substep( $step_id, $substep_id, $type ) {
			$steps = $this->get_steps();

			$search    = array_search( absint( $step_id ), array_map( 'absint', wp_list_pluck( $steps, 'id' ) ), true );
			$sub_steps = ( isset( $steps[ $search ] ) && isset( $steps[ $search ]['substeps'] ) ) ? $steps[ $search ]['substeps'] : $steps[ $search ]['substeps'] = array();

			$sub_step   = isset( $sub_steps[ $type ] ) ? $sub_steps[ $type ] : array();

			array_push( $sub_step, absint( $substep_id ) );

			$steps[ $search ]['substeps'][ $type ] = $sub_step;

			$this->set_steps( $steps );

			return array( 'funnel_id' => $this->save( array() ), 'type' => $type );

		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param $type
		 *
		 * @return mixed
		 */
		public function delete_step( $funnel_id, $step_id ) {
			$steps  = $this->get_steps();
			$search = array_search( intval( $step_id ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );
			if ( $search === false ) {
				return;
			}
			unset( $steps[ $search ] );
			$steps = array_values( $steps );
			$this->set_steps( $steps );

			return $this->save( array() );

		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param $substep_id
		 * @param $substep
		 *
		 * @return mixed
		 */
		public function delete_substep( $funnel_id, $step_id, $substep_id, $substep ) {
			$steps       = $this->get_steps();
			$search      = array_search( intval( $step_id ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );
			$substep_ids = array_map( 'intval', $steps[ $search ]['substeps'][ $substep ] );

			$substep_search = array_search( intval( $substep_id ), $substep_ids, true );
			unset( $substep_ids[ $substep_search ] );
			$substep_ids = array_values( $substep_ids );
			if ( ! empty( $substep_ids ) ) {
				$steps[ $search ]['substeps'][ $substep ] = $substep_ids;
			} else {
				unset( $steps[ $search ]['substeps'][ $substep ] );
			}

			$this->set_steps( $steps );

			$deleted = $this->save( array() );

			return array( 'success' => true, 'deleted' => $deleted, 'type' => $steps[ $search ]['type'] );

		}

		/**
		 * @param $steps
		 *
		 * @return mixed
		 */
		public function reposition_steps( $steps ) {
			if ( ! is_array( $steps ) || count( $steps ) === 0 ) {
				return;
			}
			$result_steps = array();
			$current_step = $this->get_steps();

			foreach ( $steps as $step ) {
				$data = array(
					'type' => $step['type'],
					'id'   => $step['id'],
				);

				/**
				 * handle canvas mode sibling case
				 */
				if ( is_array( $current_step ) && count( $current_step ) > 0 ) {
					foreach ( $current_step as $c_step ) {
						if ( isset( $c_step['insert_after'] ) && ( absint( $c_step['id'] ) === absint( $step['id'] ) ) ) {
							$data['insert_after'] = $c_step['insert_after'];
						}
					}
				}

				$get_substeps = WFFN_Core()->substeps->get_substeps( $this->get_id(), $step['id'] );
				if ( is_array( $get_substeps ) && count( $get_substeps ) > 0 ) {
					$data['substeps'] = $get_substeps;
				} else {
					$data['substeps'] = [];
				}
				$result_steps[] = $data;

			}

			$this->set_steps( $result_steps );

			return $this->save( array() );

		}

		/**
		 * @param $step_id
		 * @param $type
		 * @param $substeps
		 *
		 * @return mixed
		 */
		public function reposition_substeps( $step_id, $type, $substeps ) {
			$index                                      = $this->get_step_index( $step_id );
			$this->steps[ $index ]['substeps'][ $type ] = array_map( 'absint', $substeps );

			return $this->save( array() );
		}

		/**
		 * @param $step_id
		 *
		 * @return false|int|string
		 */
		public function get_step_index( $step_id ) {
			$get_steps = wp_list_pluck( $this->steps, 'id' );

			return array_search( absint( $step_id ), array_map( 'absint', $get_steps ), true );

		}

		/**
		 * @param $data
		 *
		 * @return mixed
		 */
		public function update( $data ) {
			$funnel_id = isset( $data['funnel_id'] ) ? $data['funnel_id'] : $this->get_id();
			if ( $funnel_id > 0 && isset( $data['title'] ) && ! empty( $data['title'] ) ) {
				$this->set_title( $data['title'] );
				if ( isset( $data['desc'] ) ) {
					$this->set_desc( $data['desc'] );
				}

				return $this->save( array() );
			}
		}

		/**
		 * @return false|int
		 */
		public function get_start_date( $filter_data ) {
			$range = isset( $filter_data['range'] ) ? $filter_data['range'] : '';

			$date_added = '';
			if ( empty( $range ) || 'all' === $range ) {
				$date_added = strtotime( $this->get_date_added() );
			} else {
				switch ( $range ) {
					case '7' :
						$date_added = strtotime( '-6 days', strtotime( 'midnight', current_time( 'timestamp' ) ) );
						break;
					case '15' :
						$date_added = strtotime( '-14 days', strtotime( 'midnight', current_time( 'timestamp' ) ) );
						break;
					case '30' :
						$date_added = strtotime( '-29 days', strtotime( 'midnight', current_time( 'timestamp' ) ) );
						break;
					case 'custom':
						$date_added = strtotime( $filter_data['start_date'] );
						break;
				}
			}

			return empty( $date_added ) ? strtotime( current_time( 'mysql' ) ) : $date_added;
		}

		/**
		 * @param $filter_data
		 *
		 * @return false|int
		 */
		public function get_end_date( $filter_data ) {
			$range    = isset( $filter_data['range'] ) ? $filter_data['range'] : '';
			$end_date = isset( $filter_data['end_date'] ) ? $filter_data['end_date'] : '';
			if ( 'custom' === $range && ! empty( $end_date ) ) {
				return strtotime( '+1 days', strtotime( 'midnight', strtotime( $end_date ) ) );
			}

			return strtotime( current_time( 'mysql' ) );
		}

		public function get_view_link() {
			$steps = $this->get_steps();
			if ( is_array( $steps ) && count( $steps ) > 0 ) {
				foreach ( $steps as $step ) {
					$getstep_type = WFFN_Core()->steps->get_integration_object( $step['type'] );
					if ( $getstep_type instanceof WFFN_Step ) {
						$data_ = $getstep_type->populate_data_properties( $step, $this->get_id() );

						return $data_['_data']['view'];
					}
				}
			}

			return false;

		}

		/**
		 * @return array
		 */
		public function default_settings() {
			return array(
				'override_tracking_ids' => false,
				'fb_pixel_key'          => '',
				'ga_key'                => '',
				'gad_key'               => '',
				'gad_conversion_label'  => '',
				'pint_key'              => '',
				'tiktok_pixel'          => '',
				'snapchat_pixel'        => '',
			);
		}

		public function get_settings() {
			$db_options = WFFN_Core()->get_dB()->get_meta( $this->get_id(), '_settings' );
			$db_options = isset( $db_options ) ? $db_options : array();
			$db_options = ( ! empty( $db_options ) && is_array( $db_options ) ) ? $db_options : array();

			return wp_parse_args( $db_options, $this->default_settings() );
		}


		/**
		 * Check if funnel has a native checkout page or not
		 *
		 * @param boolean $validate_published whether we need to check checkout status too while looping, usually passed as false for the backend operations.
		 *
		 * @return bool
		 */
		public function is_funnel_has_native_checkout( $validate_published = true ) {
			$steps = $this->get_steps();
			if ( empty( $steps ) || ! is_array( $steps ) ) {
				return true;
			}

			/**
			 * iterate over the funnel step and if we found a checkout step with published status then return false
			 */
			foreach ( $steps as $step ) {
				if ( isset( $step['type'] ) && $step['type'] === 'wc_checkout' && ( $validate_published === false || 'publish' === get_post_status( $step['id'] ) ) ) {
					return false;

				}
			}


			return true;
		}


		public function get_status() {
			$funnel_status = WFFN_Core()->get_dB()->get_meta( $this->id, 'status' );
			$status        = ! empty( $funnel_status ) && true === wffn_string_to_bool( $funnel_status ) ? __( 'Published', 'funnel-builder' ) : __( 'Draft', 'funnel-builder' );

			return $status;
		}

		/**
		 * @param $steps
		 * @param $frontend
		 *
		 * @return array
		 */
		public function get_group_steps( $is_frontend = false ) {
			$prepare_data = [
				'groups'     => array(),
				'steps_list' => array()
			];

			$funnel_id = $this->id;

			$steps = ( false === $is_frontend ) ? ( array ) $this->get_steps( true ) : ( array ) $this->get_steps();
			/**
			 * handle case for store checkout if native checkout set
			 */
			if ( false === $is_frontend && is_array( $steps ) && absint( $funnel_id ) === WFFN_Common::get_store_checkout_id() ) {
				if ( false === in_array( 'wc_checkout', wp_list_pluck( $steps, 'type' ), true ) ) {
					$sub_steps     = WFFN_Common::get_store_checkout_global_substeps( $funnel_id );
					$sub_step_data = [];
					$get_substep   = WFFN_Core()->substeps->get_integration_object( 'wc_order_bump' );
					if ( $get_substep instanceof WFFN_Substep ) {
						$sub_step_data = $get_substep->populate_substep_data_properties( $sub_steps );
					}
					$native_checkout = array(
						'id'       => 0,
						'type'     => WFFN_Common::store_native_checkout_slug(),
						'substeps' => $sub_step_data,
					);
					array_unshift( $steps, $native_checkout );
				}

			}

			if ( count( $steps ) > 0 ) {
				$current_type      = '';
				$is_checkout_group = false;
				foreach ( $steps as $step ) {

					if ( in_array( $step['type'], array( 'wc_native', 'wc_checkout' ), true ) ) {
						$is_checkout_group = true;
					}

					/**
					 * 1 -> IF -> merge all step type which is not comes after checkout if group already exists
					 * 2 -> If -> Create new group if not exists
					 * 3 -> Else If -> Merge all checkout, upsell and thankyou in same sibling
					 */
					$invalid_step = ( ( false === $is_frontend ) && $is_checkout_group && in_array( $step['type'], array( 'landing', 'optin', 'optin_ty' ), true ) );
					if ( $invalid_step ) {
						if ( isset( $prepare_data['groups'] ) ) {
							$last_key = false;
							foreach ( $prepare_data['groups'] as $key => $type ) {
								if ( $type['type'] === $step['type'] ) {
									$last_key = $key;
								}
							}

							if ( false !== $last_key ) {
								$get_prepare_data                               = $this->prepare_group_step_data( $prepare_data, $step, $is_frontend );
								$prepare_data                                   = $get_prepare_data['prepare_data'];
								$canvas_step                                    = $get_prepare_data['canvas_step'];
								$prepare_data['groups'][ $last_key ]['steps'][] = $is_frontend ? $canvas_step['id'] : $canvas_step;
							}
						}
					}

					if ( ! $invalid_step && ( ! isset( $step['insert_after'] ) || $step['type'] !== $current_type ) ) {
						$group            = [
							'type'  => $step['type'],
							'steps' => [],
						];
						$get_prepare_data = $this->prepare_group_step_data( $prepare_data, $step, $is_frontend );
						$prepare_data     = $get_prepare_data['prepare_data'];
						$canvas_step      = $get_prepare_data['canvas_step'];
						$group['steps'][] = $is_frontend ? $canvas_step['id'] : $canvas_step;

						/**
						 * Merge upsell thankyou and checkout steps in same group
						 */
						if ( isset( $prepare_data['groups'] ) && in_array( $step['type'], array( 'wc_upsells', 'wc_native', 'wc_checkout', 'wc_thankyou' ), true ) ) {
							$get_group_key = array_search( $step['type'], wp_list_pluck( $prepare_data['groups'], 'type' ), true );
							if ( false !== $get_group_key ) {
								$prepare_data['groups'][ $get_group_key ]['steps'][] = $is_frontend ? $canvas_step['id'] : $canvas_step;
							} else {
								$prepare_data['groups'][] = $group;
							}
						} else {
							$prepare_data['groups'][] = $group;
						}

						$current_type = $step['type'];

					} elseif ( ! $invalid_step ) {
						if ( isset( $prepare_data['groups'] ) ) {
							$last_group                                       = array_key_last( $prepare_data['groups'] );
							$get_prepare_data                                 = $this->prepare_group_step_data( $prepare_data, $step, $is_frontend );
							$prepare_data                                     = $get_prepare_data['prepare_data'];
							$canvas_step                                      = $get_prepare_data['canvas_step'];
							$prepare_data['groups'][ $last_group ]['steps'][] = $is_frontend ? $canvas_step['id'] : $canvas_step;
						}
					}

				}

			}

			return $prepare_data;

		}

		/**
		 * @param $current_step_id
		 *
		 *  get next setup id for open step on frontend
		 *
		 * @return false|mixed
		 */
		public function get_next_step_id( $current_step_id = 0 ) {

			$group_steps = $this->get_group_steps( true );
			$is_checkout_group = false;
			if ( is_array( $group_steps ) && isset( $group_steps['groups'] ) && count( $group_steps['groups'] ) > 0 ) {
				foreach ( $group_steps['groups'] as $key => $step ) {
					if ( in_array( $step['type'], array( 'wc_native', 'wc_checkout' ), true ) ) {
						$is_checkout_group = true;
					}
					/**
					 * find current step id in each step group
					 */
					$get_key = array_search( $current_step_id, $step['steps'] );

					if ( false !== $get_key ) {
						/**
						 * return false if current_step_id found but next group not exists
						 */


						$invalid_step = ( $is_checkout_group && in_array( $step['type'], array( 'landing', 'optin', 'optin_ty' ), true ) );

						if ( ! isset( $group_steps['groups'][ $key + 1 ] ) || $invalid_step ) {
							/**
							 * handle invalid step next link
							 * if invalid step in case open on frontend
							 * In this case create next link from funnel step list not groups
							 */
							$get_steps = $this->get_steps();
							foreach ( $get_steps as $lk => $l_step ) {
								if ( ( isset( $get_steps[ $lk + 1 ] ) ) ) {
									if ( absint( $l_step['id'] ) === absint( $current_step_id ) && $step['type'] !== $get_steps[ $lk + 1 ]['type'] ) {
										return $get_steps[ $lk + 1 ];
									}
								}
							}

							return false;
						}

						/**
						 * return next group first step id
						 */
						if ( isset( $group_steps['groups'][ $key + 1 ]['steps'] ) && isset( $group_steps['groups'][ $key + 1 ]['steps'][0] ) ) {
							return array(
								'type' => $group_steps['groups'][ $key + 1 ]['type'],
								'id'   => $group_steps['groups'][ $key + 1 ]['steps'][0]
							);

						}
					}
				}
			}



			return false;
		}

		public function prepare_group_step_data( $prepare_data, $step, $is_frontend = false ) {
			$canvas_step = array(
				'id'   => $step['id'],
				'type' => $step['type']
			);

			if ( false === $is_frontend ) {
				$list_step                                 = WFFN_REST_Funnel_Canvas::get_instance()->map_list_step( $step );
				$prepare_data['steps_list'][ $step['id'] ] = $list_step;

				if ( in_array( $step['type'], array( 'wc_upsells', 'wc_native', 'wc_checkout' ), true ) ) {
					if ( is_array( $step['substeps'] ) && count( $step['substeps'] ) > 0 ) {
						if ( isset( $step['substeps']['wc_order_bump'] ) ) {
							foreach ( $step['substeps']['wc_order_bump'] as $bump ) {
								$checkout_id                               = ( 'wc_native' === $step['type'] ) ? 0 : absint( $step['id'] );
								$canvas_step['substeps'][]                 = [
									'id'   => $bump['id'],
									'type' => 'wc_order_bump'
								];
								$bump['type']                              = 'wc_order_bump';
								$bump['checkout_id']                       = $checkout_id;
								$prepare_data['steps_list'][ $bump['id'] ] = WFFN_REST_Funnel_Canvas::get_instance()->map_list_step( $bump );
							}
						}

						if ( isset( $step['substeps']['offer'] ) ) {

							foreach ( $step['substeps']['offer'] as $key => $offer ) {
								$canvas_step['substeps'][] = [
									'id'   => $offer['id'],
									'type' => 'offer'
								];
								$offer['type']             = 'offer';
								if ( false === $is_frontend ) {
									$prepare_data['steps_list'][ $offer['id'] ] = WFFN_REST_Funnel_Canvas::get_instance()->map_list_step( $offer );
									$offer_settings                             = get_post_meta( $offer['id'], '_wfocu_setting', true );
									/**
									 * get offer accept and reject id
									 */
									if ( ! empty( $offer_settings ) && is_object( $offer_settings ) ) {
										if ( empty( $offer_settings->settings ) ) {
											$offer_settings->settings = (object) $offer_settings->settings;
										}

										$accepted_id = ( isset( $offer_settings->settings ) && isset( $offer_settings->settings->jump_on_accepted ) && isset( $offer_settings->settings->jump_to_offer_on_accepted ) && true === $offer_settings->settings->jump_on_accepted ) ? $offer_settings->settings->jump_to_offer_on_accepted : 'automatic';
										$rejected_id = ( isset( $offer_settings->settings ) && isset( $offer_settings->settings->jump_on_rejected ) && isset( $offer_settings->settings->jump_to_offer_on_rejected ) && true === $offer_settings->settings->jump_on_rejected ) ? $offer_settings->settings->jump_to_offer_on_rejected : 'automatic';

									} else {
										$accepted_id = 'automatic';
										$rejected_id = 'automatic';

									}

									/**
									 * handle case when offer flow next offer
									 * return next offer id for handle ui nodes
									 */
									if ( ( 'automatic' === $accepted_id || 'automatic' === $rejected_id ) && isset( $step['substeps']['offer'][ $key + 1 ] ) ) {
										$accepted_id = ( 'automatic' === $accepted_id ) ? absint( $step['substeps']['offer'][ $key + 1 ]['id'] ) : absint( $accepted_id );
										$rejected_id = ( 'automatic' === $rejected_id ) ? absint( $step['substeps']['offer'][ $key + 1 ]['id'] ) : absint( $rejected_id );
									}

									if ( 'terminate' === $accepted_id || 'automatic' === $accepted_id ) {
										$accepted_id = 0;
									}
									if ( 'terminate' === $rejected_id || 'automatic' === $rejected_id ) {
										$rejected_id = 0;
									}

									$offer_data           = WFFN_REST_Funnel_Canvas::get_instance()->map_list_step( $offer );
									$offer_data['accept'] = $accepted_id;
									$offer_data['reject'] = $rejected_id;


									$prepare_data['steps_list'][ $offer['id'] ] = $offer_data;
								}
							}
						}

					}

				}
			}



			return [
				'canvas_step'  => $canvas_step,
				'prepare_data' => $prepare_data,
			];

		}




	}
}
