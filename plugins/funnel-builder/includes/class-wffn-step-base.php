<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * This class will be extended by all all single step(like upstroke, aero etc) to register different steps
 * Class WFFN_Step
 */
if ( ! class_exists( 'WFFN_Step_Base' ) ) {
	#[AllowDynamicProperties]

 abstract class WFFN_Step_Base {

		public $slug = '';
		public $supports = [];
		public $id;
		public $funnel_id = 0;
		public $list_priority;

		/**
		 * WFFN_Step_Base constructor.
		 *
		 * @param string $id
		 */
		public function __construct( $id = '' ) {
			$this->id = $id;
		}

		/**
		 * Get Step's title, overriden by individual step to provide title like (UpStroke, Aero etc)
		 * @return string
		 */
		public function get_title() {
			return '';
		}

		/**
		 * @param $steps
		 *
		 * @return array
		 */
		public function get_step_data() {
			return array();
		}

		/**
		 * @param $funnel_id
		 * @param $posted_data
		 *
		 * @return stdClass
		 */
		public function add_step( $funnel_id, $posted_data ) {
			$step_id = isset( $posted_data['id'] ) ? $posted_data['id'] : 0;

			if ( $step_id > 0 ) {
				$posted_data['_data']         = new stdClass();
				$posted_data['_data']->title  = $this->get_entity_title( $step_id );
				$posted_data['_data']->edit   = $this->get_entity_edit_link( $step_id );
				$posted_data['_data']->view   = $this->get_entity_view_link( $step_id );
				$posted_data['_data']->status = $this->get_entity_status( $step_id );
			}

			$data           = new stdClass();
			$data->type     = $this->slug;
			$data->id       = $step_id;
			$data->_data    = isset( $posted_data['_data'] ) ? $posted_data['_data'] : new stdClass();
			$data->supports = $this->get_supports();
			$data->tags     = $this->get_entity_tags( $step_id, $funnel_id );
			$data->substeps = [];
			$funnel         = WFFN_Core()->admin->get_funnel( $funnel_id );
			$this->update_funnel_meta_in_step( $step_id, $funnel_id );

			$data->funnel_id = $funnel->add_step( $this->slug, $step_id, 0 );

			return $data;
		}

		/**
		 * @param $step_id
		 *
		 * @return mixed
		 */
		public function get_entity_title( $step_id ) {
			$title = $step_id;
			if ( $step_id > 0 && get_post( $step_id ) instanceof WP_Post ) {
				$title = WFFN_Core()->admin->maybe_empty_title( get_the_title( $step_id ) );
			}

			return $title;
		}

		/**
		 * @param $step_id
		 *
		 * @return mixed
		 */
		public function get_entity_edit_link( $step_id ) {
			$link = 'javascript:void(0);';
			return $link;
		}

		/**
		 * @param $step_id
		 *
		 * @return mixed
		 */
		public function get_entity_view_link( $step_id ) {
			$link = 'javascript:void(0);';
			if ( $step_id > 0 && get_post( $step_id ) instanceof WP_Post ) {
				$link = esc_url( get_the_permalink( $step_id ) );
				$type = get_post_type( $step_id );
				if ( 'wfacp_checkout' === $type ) {
					if ( empty( WFACP_Common::get_page_product( $step_id ) ) ) {
						$link = add_query_arg( [ 'wfacp_preview' => true ], $link );
						$link = str_replace( "#038;", "&", $link );
					}
				}
			}

			return $link;
		}

		/**
		 * @param $step_id
		 *
		 * @return mixed
		 */
		public function get_entity_status( $step_id ) {
			$post_status = '';
			if ( $step_id > 0 && get_post( $step_id ) instanceof WP_Post ) {
				$post_status = get_post_status( $step_id );
			}

			return ( 'publish' === $post_status ) ? 1 : '0';
		}

		/**
		 * @return array
		 */
		public function get_supports() {
			return [];
		}

		public function get_entity_tags( $step_id, $funnel_id ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			return array();
		}

		/**
		 * @param $step_id
		 * @param $funnel_id
		 */
		public function update_funnel_meta_in_step( $step_id, $funnel_id ) {
			update_post_meta( $step_id, '_bwf_in_funnel', $funnel_id );
		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param $substep
		 * @param $posted_data
		 *
		 * @return stdClass
		 */
		public function add_substep( $funnel_id, $step_id, $substep, $posted_data ) {
			$data       = new stdClass();
			$data->type = $substep;
			$substep_id = isset( $posted_data['id'] ) ? $posted_data['id'] : 0;

			if ( $substep_id > 0 ) {
				$data->_data         = isset( $posted_data['_data'] ) ? $posted_data['_data'] : new stdClass();
				$data->_data->title  = $this->get_entity_title( $substep_id );
				$data->_data->edit   = $this->get_entity_edit_link( $substep_id );
				$data->_data->view   = $this->get_entity_view_link( $substep_id );
				$data->_data->status = $this->get_entity_status( $substep_id );
			}

			$funnel = WFFN_Core()->admin->get_funnel( $funnel_id );
			$this->update_funnel_meta_in_step( $substep_id, $funnel_id );

			$resutl          = $funnel->add_substep( $step_id, $substep_id, $substep );
			$type            = isset( $resutl['type'] ) ? $resutl['type'] : '';
			$get_type_object = WFFN_Core()->steps->get_integration_object( $type );

			if ( $get_type_object instanceof WFFN_Step ) {
				$data->tags = $get_type_object->get_entity_tags( $step_id, $funnel_id );
			}

			$data->id = $substep_id;

			return $data;
		}

		/**
		 * @param $funnel_id
		 * @param $substep
		 * @param $posted_data
		 *
		 * @return stdClass
		 */
		public function add_native_store_substep( $funnel_id, $substep, $posted_data ) {
			$data       = new stdClass();
			$data->type = $substep;
			$substep_id = isset( $posted_data['id'] ) ? $posted_data['id'] : 0;

			if ( $substep_id > 0 ) {
				$data->_data         = isset( $posted_data['_data'] ) ? $posted_data['_data'] : new stdClass();
				$data->_data->title  = $this->get_entity_title( $substep_id );
				$data->_data->edit   = $this->get_entity_edit_link( $substep_id );
				$data->_data->view   = $this->get_entity_view_link( $substep_id );
				$data->_data->status = $this->get_entity_status( $substep_id );
				$data->tags          = [];
			}

			$this->update_funnel_meta_in_step( $substep_id, $funnel_id );
			$data->id = $substep_id;

			return $data;
		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param $posted_data
		 *
		 * @return stdClass
		 */
		public function duplicate_step( $funnel_id, $step_id, $posted_data ) {
			$duplicated_step_id = isset( $posted_data['id'] ) ? $posted_data['id'] : 0;
			$design             = isset( $posted_data['existing'] ) ? $posted_data['existing'] : '';
			$existing_chosen    = ( 'true' === $design && isset( $posted_data['design_name'] ) && is_array( $posted_data['design_name'] ) && isset( $posted_data['design_name']['id'] ) ) ? $posted_data['design_name']['id'] : 0;

			if ( $duplicated_step_id > 0 ) {

				$posted_data['_data']         = new stdClass();
				$posted_data['_data']->title  = $this->get_entity_title( $duplicated_step_id );
				$posted_data['_data']->edit   = $this->get_entity_edit_link( $duplicated_step_id );
				$posted_data['_data']->view   = $this->get_entity_view_link( $duplicated_step_id );
				$posted_data['_data']->status = $this->get_entity_status( $duplicated_step_id );
			}

			$data           = new stdClass();
			$data->type     = $this->slug;
			$data->id       = $duplicated_step_id;
			$data->_data    = isset( $posted_data['_data'] ) ? $posted_data['_data'] : new stdClass();
			$data->supports = $this->get_supports();
			$data->tags     = [];
			$data->substeps = [];

			$funnel = WFFN_Core()->admin->get_funnel( $funnel_id );
			$this->update_funnel_meta_in_step( $duplicated_step_id, $funnel_id );

			$original_id = isset( $posted_data['original_id'] ) ? $posted_data['original_id'] : 0;

			$data->funnel_id = $funnel->add_step( $this->slug, $duplicated_step_id, $original_id );

			if ( $duplicated_step_id > 0 && $existing_chosen < 1 ) {

				if ( isset( $posted_data['duplicate_funnel_id'] ) && $posted_data['duplicate_funnel_id'] !== '' ) {
					$funnel_id = array( 'funnel_id' => $funnel_id, 'duplicate_funnel_id' => $posted_data['duplicate_funnel_id'] );
				}
				$duplicated_substeps = $this->maybe_duplicate_substeps( $funnel_id, $step_id, $duplicated_step_id );
				$data->substeps      = $duplicated_substeps;
			}

			return $data;
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
			$duplicated_data       = isset( $duplicated_substeps[ $subtype ][ $substep_key ] ) ? $duplicated_substeps[ $subtype ][ $substep_key ] : array();
			$duplicated_substep_id = isset( $duplicated_data['id'] ) ? $duplicated_data['id'] : 0;

			if ( $duplicated_substep_id > 0 ) {
				$_data = isset( $duplicated_data['_data'] ) ? $duplicated_data['_data'] : new stdClass();
				$data  = new stdClass();

				$data->id            = $duplicated_substep_id;
				$data->_data         = $_data;
				$data->_data->title  = $this->get_entity_title( $duplicated_substep_id );
				$data->_data->edit   = $this->get_entity_edit_link( $duplicated_substep_id );
				$data->_data->view   = $this->get_entity_view_link( $duplicated_substep_id );
				$data->_data->status = $this->get_entity_status( $duplicated_substep_id );

				$funnel = WFFN_Core()->admin->get_funnel( $funnel_id );
				$this->update_funnel_meta_in_step( $duplicated_substep_id, $funnel_id );

				$funnel->add_substep( $duplicate_step_id, $duplicated_substep_id, $subtype );

				$duplicated_substeps[ $subtype ][ $substep_key ] = $data;
			}

			return $duplicated_substeps;
		}

		/**
		 * @param $funnel_id
		 * @param $duplicate_step_id
		 * @param $subtype
		 * @param $substep_id
		 * @param $substep_key
		 * @param $duplicated_substeps
		 *
		 * @return mixed
		 */
		public function duplicate_store_checkout_substep( $funnel_id, $duplicate_step_id, $subtype, $substep_id, $substep_key = 0, $duplicated_substeps = [] ) {
			$duplicated_data       = isset( $duplicated_substeps[ $subtype ][ $substep_key ] ) ? $duplicated_substeps[ $subtype ][ $substep_key ] : array();
			$duplicated_substep_id = isset( $duplicated_data['id'] ) ? $duplicated_data['id'] : 0;

			if ( $duplicated_substep_id > 0 ) {
				$_data = isset( $duplicated_data['_data'] ) ? $duplicated_data['_data'] : new stdClass();
				$data  = new stdClass();

				$data->id            = $duplicated_substep_id;
				$data->_data         = $_data;
				$data->_data->title  = $this->get_entity_title( $duplicated_substep_id );
				$data->_data->edit   = $this->get_entity_edit_link( $duplicated_substep_id );
				$data->_data->view   = $this->get_entity_view_link( $duplicated_substep_id );
				$data->_data->status = $this->get_entity_status( $duplicated_substep_id );
				$this->update_funnel_meta_in_step( $duplicated_substep_id, $funnel_id );

				$duplicated_substeps[ $subtype ][ $substep_key ] = $data;
			}

			return $duplicated_substeps;
		}


		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param $delete_substeps
		 *
		 * @return mixed
		 */
		public function delete_step( $funnel_id, $step_id, $delete_substeps = true ) {
			$funnel = WFFN_Core()->admin->get_funnel( $funnel_id );

			if ( true === $delete_substeps ) {
				$substeps = $this->get_substeps( $funnel_id, $step_id );
				foreach ( ( is_array( $substeps ) && count( $substeps ) > 0 ) ? $substeps : array() as $subtype => $substep_ids ) {
					$this->delete_substeps( $subtype, $substep_ids );
				}
			}

			$delete = '';
			if ( ! is_null( get_post( $step_id ) ) ) {
				$delete = wp_delete_post( $step_id );
			}

			return empty( $delete ) ? 0 : $funnel->delete_step( $funnel_id, $step_id );
		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param array $subtypes
		 *
		 * @return array
		 */
		public function get_substeps( $funnel_id, $step_id, $subtypes = array() ) {
			$substeps = array();
			$funnel   = WFFN_Core()->admin->get_funnel( $funnel_id );
			$steps    = $funnel->get_steps();
			if ( count( $steps ) === 0 ) {
				return $substeps;
			}
			$search = array_search( absint( $step_id ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );

			if ( is_int( $search ) ) {
				$step = $steps[ $search ];
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
			}

			return $substeps;
		}

		/**
		 * @param $subtype
		 * @param $substep_ids
		 */
		public function delete_substeps( $subtype, $substep_ids ) {
			foreach ( ( is_array( $substep_ids ) && count( $substep_ids ) > 0 ) ? $substep_ids : array() as $substep_id ) {
				if ( ! is_null( get_post( $substep_id ) ) ) {
					wp_delete_post( $substep_id );
				}
			}
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
			if ( ! is_null( get_post( $substep_id ) ) ) {
				wp_delete_post( $substep_id );
			}

			$funnel = WFFN_Core()->admin->get_funnel( $funnel_id );

			$tags = [];

			$deleted_result = $funnel->delete_substep( $funnel_id, $step_id, $substep_id, $substep );
			if ( is_array( $deleted_result ) && count( $deleted_result ) > 0 ) {
				$type            = isset( $deleted_result['type'] ) ? $deleted_result['type'] : '';
				$get_type_object = WFFN_Core()->steps->get_integration_object( $type );

				if ( $get_type_object instanceof WFFN_Step ) {
					$tags = $get_type_object->get_entity_tags( $step_id, $funnel_id );
				}
			}

			return $tags;
		}

		/**
		 * @param $step_id
		 * @param $new_status
		 *
		 * @return bool
		 */
		public function switch_status( $step_id, $new_status ) {
			$switched = false;
			if ( $step_id > 0 ) {
				$updated_id = 0;
				$get_post   = get_post( $step_id );
				if ( ! is_null( $get_post ) ) {
					$post_status = get_post_status( $step_id );
					$newstatus   = empty( $new_status ) ? 'draft' : 'publish';
					if ( $newstatus !== $post_status ) {
						$updated_id = wp_update_post( array(
							'ID'          => $step_id,
							'post_status' => $newstatus,
						) );
					}
				}
				if ( intval( $step_id ) === intval( $updated_id ) ) {
					$switched = true;
				}
			}

			return $switched;
		}

		/**
		 * @param $term
		 *
		 * @return array
		 */
		public function get_step_designs( $term ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			return array();
		}

		/**
		 * @param $term
		 *
		 * @return array
		 */
		public function get_substep_designs( $term ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			return array();
		}


		/**
		 * @param $feature
		 *
		 * @return bool
		 */
		public function supports( $feature ) {
			return in_array( $feature, $this->get_supports(), true );
		}

		public function claim_environment( $environment ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			return false;
		}

		/**
		 * @param $environment
		 *
		 * @return bool|WFFN_Funnel
		 */
		public function get_funnel_to_run( $environment ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			return false;
		}

		public function should_register() {
			return true;
		}

		public function get_enitity_data( $step_data, $key ) {
			return array_key_exists( $key, $step_data ) ? $step_data[ $key ] : false;
		}

		/**
		 * @param $step
		 *
		 * @return mixed
		 */
		public function populate_data_properties( $step, $funnel_id ) {
			$step['supports'] = $this->get_supports();
			$step['tags']     = $this->get_entity_tags( $step['id'], $funnel_id );

			$step['_data']           = [];
			$step['_data']['view']   = $this->get_entity_view_link( $step['id'] );
			$step['_data']['title']  = $this->get_entity_title( $step['id'] );
			$step['_data']['status'] = $this->get_entity_status( $step['id'] );
			$step['_data']['edit']   = $this->get_entity_edit_link( $step['id'] );


			$substeps = $this->populate_substep_data_properties( $step );

			$step['substeps'] = ! empty( $substeps ) ? $substeps : [];

			return $step;
		}


		/**
		 * @param $step_id
		 *
		 * @return mixed
		 */
		public function get_entity_description( $step_id ) {
			$desc = '';
			if ( $step_id > 0 && get_post( $step_id ) instanceof WP_Post ) {
				$desc = get_post_field( 'post_content', $step_id );
			}

			return $desc;
		}

		/**
		 * @param $substeps
		 *
		 * @return array
		 */
		public function populate_substep_data_properties( $substeps ) {
			if ( isset( $substeps['substeps'] ) ) {
				$substeps = $substeps['substeps'];
			}

			$substeps_data = array();
			if ( is_array( $substeps ) && count( $substeps ) > 0 ) {
				foreach ( $substeps as $substep_slug => $substep_arr ) {
					$get_substep = WFFN_Core()->substeps->get_integration_object( $substep_slug );
					if ( is_array( $substep_arr ) && count( $substep_arr ) > 0 && $get_substep instanceof WFFN_Substep ) {
						$substeps_data[ $substep_slug ] = $get_substep->populate_substeps_data_properties( $substep_arr );
					}
				}
			}

			return $substeps_data;
		}

		/**
		 * @param int $id
		 *
		 * @return false|string
		 */
		public function get_url( $id = 0 ) {
			return get_permalink( $id );
		}

		/**
		 * @param $existing_args
		 *
		 * @return mixed
		 */
		public function exclude_from_query( $existing_args ) {
			if ( isset( $existing_args['get_existing'] ) && true === $existing_args['get_existing'] ) {
				unset( $existing_args['get_existing'] );

				return $existing_args;
			}
			if ( isset( $existing_args['meta_query'] ) && is_array( $existing_args['meta_query'] ) && count( $existing_args['meta_query'] ) > 0 ) {
				array_push( $existing_args['meta_query'], array(
					'key'     => '_bwf_in_funnel',
					'compare' => 'NOT EXISTS',
					'value'   => '',
				) );
			} else {
				$existing_args['meta_query'] = array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => '_bwf_in_funnel',
						'compare' => 'NOT EXISTS',
						'value'   => '',
					),
				);
			}

			return $existing_args;
		}

		/**
		 * @param $status
		 *
		 * @return bool
		 */
		public function is_disabled( $status ) {
			if ( 1 === absint( $status ) ) {
				return false;
			}

			return true;
		}

		public function mark_step_viewed() {

		}

		public function mark_step_converted( $step_data ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

		}

		/**
		 * @param $step
		 *
		 * @return array
		 */
		public function get_export_data( $step ) {
			$post_content = '';
			$post         = get_post( $step['id'] );
			if ( $post instanceof WP_Post ) {
				$post_content = $post->post_content;
			}

			return array(
				'type'         => $this->slug,
				'status'       => $this->get_entity_status( $step['id'] ),
				'title'        => $this->get_entity_title( $step['id'] ),
				'meta'         => $this->_get_export_metadata( $step ),
				'post_content' => $post_content,
			);
		}

		/**
		 * @param $step
		 *
		 * @return array
		 */
		public function _get_export_metadata( $step ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			return array();
		}


		public function copy_metadata( $id, $metadata, $excluded = [] ) {
			global $wpdb;
			if ( ! empty( $metadata ) ) {
				$sql_query_selects = [];

				foreach ( $metadata as $key => $meta_val ) {

					$meta_key = $key;

					if ( in_array( $meta_key, $excluded, true ) ) {
						continue;
					}
					/**
					 * Good to remove slashes before adding
					 */
					if ( is_serialized( $meta_val, false ) ) {
						$meta_value = $meta_val;
					} else {
						$meta_value = maybe_serialize( $meta_val );
					}

					$meta_key   = esc_sql( $meta_key );
					$meta_value = esc_sql( $meta_value );

					$sql_query_selects[] = "($id, '$meta_key', '$meta_value')";
				}

				$sql_query_meta_val = implode( ',', $sql_query_selects );
				$wpdb->query( $wpdb->prepare( 'INSERT INTO %1$s (post_id, meta_key, meta_value) VALUES ' . $sql_query_meta_val, $wpdb->postmeta ) );//phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder,WordPress.DB.PreparedSQL.NotPrepared

			}
		}

		/**
		 * @param $id
		 *
		 * @return bool
		 */
		public function has_import_scheduled( $id ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			return false;
		}

	}
}
