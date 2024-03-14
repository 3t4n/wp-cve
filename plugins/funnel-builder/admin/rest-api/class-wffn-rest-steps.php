<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Steps
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Steps' ) ) {
	#[AllowDynamicProperties]
	class WFFN_REST_Steps extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'funnels/(?P<funnel_id>[\d]+)/steps';

		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		public static function get_instance() {
			if ( null === self::$_instance ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/**
		 * Register the routes for taxes.
		 */
		public function register_routes() {

			register_rest_route( $this->namespace, '/' . $this->rest_base, array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_step' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array_merge( $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ), $this->get_create_steps_collection() ),
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<step_id>[\d]+)', array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
					'step_id'   => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),

				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_step' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array_merge( $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ), $this->get_update_steps_collection() )
				),

				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_step' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => $this->get_delete_steps_collection()
				),

				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			register_rest_route( $this->namespace, '/funnels/step/search', array(

				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'search_entity' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						's'          => array(
							'description'       => __( 'search term', 'funnel-builder' ),
							'type'              => 'string',
							'required'          => false,
							'validate_callback' => 'rest_validate_request_arg',
						),
						'type'       => array(
							'description'       => __( 'Type of step', 'funnel-builder' ),
							'type'              => 'string',
							'required'          => true,
							'validate_callback' => 'rest_validate_request_arg',
						),
						'is_substep' => array(
							'description'       => __( 'if the query is for substep', 'funnel-builder' ),
							'default'           => false,
							'type'              => 'boolean',
							'sanitize_callback' => 'wffn_string_to_bool',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );

		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		public function create_step( $request, $return = false ) {

			$resp = array(
				'status' => false,
				'data'   => new stdClass(),
			);

			$funnel_id    = $request['funnel_id'];
			$funnel_id    = ! empty( $funnel_id ) ? $funnel_id : 0;
			$type         = isset( $request['type'] ) ? $request['type'] : '';
			$title        = isset( $request['title'] ) ? $request['title'] : __( 'New Step', 'funnel-builder' );
			$design       = isset( $request['design'] ) ? $request['design'] : '';
			$duplicate_id = isset( $request['duplicate_id'] ) ? $request['duplicate_id'] : 0;
			$inherit_id   = isset( $request['inherit_from'] ) ? $request['inherit_from'] : 0;
			$builder      = isset( $request['builder'] ) ? $request['builder'] : '';
			$template     = isset( $request['template'] ) ? $request['template'] : '';
			$canvas_data  = isset( $request['canvas'] ) ? $request['canvas'] : '';

			$template = isset( $request['template'] ) ? $request['template'] : '';

			if ( ! empty( $builder ) && ( 'gutenberg_1' !== $template && 'wfocu-gutenberg-empty' !== $template ) ) {
				$builder_status = WFFN_Core()->page_builders->builder_status( $builder );

				if ( isset( $builder_status['builders_options']['status'] ) && ! empty( $builder_status['builders_options']['status'] ) && 'activated' !== $builder_status['builders_options']['status'] ) {
					return true === $return ? $builder_status : rest_ensure_response( $builder_status );
				}
			}

			$type = ( $type === 'upsell' ) ? 'wc_upsells' : $type;

			$posted_data              = array();
			$posted_data['funnel_id'] = $funnel_id;
			$posted_data['type']      = $type;

			BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );

			if ( ! function_exists( 'media_handle_sideload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			if ( $funnel_id > 0 && ! empty( $type ) ) {

				if ( $type === 'wc_checkout' || $type === 'wc_upsells' || $type === 'wc_thankyou' ) {
					if ( ( function_exists( 'wfocu_is_woocommerce_active' ) && ! wfocu_is_woocommerce_active() ) || ( function_exists( 'wfacp_is_woocommerce_active' ) && ! wfacp_is_woocommerce_active() ) ) {
						$resp['msg'] = __( "Funnel Builder needs WooCommerce to run this step.", 'funnel-builder' );

						return true === $return ? $resp : rest_ensure_response( $resp );
					}
				}

				$get_step = WFFN_Core()->steps->get_integration_object( $type );
				if ( $get_step instanceof WFFN_Step ) {
					if ( $inherit_id > 0 && '' !== $title ) {
						$posted_data['title']             = $title;
						$posted_data['design']            = $design;
						$posted_data['design_name']['id'] = $inherit_id;
						$posted_data['existing']          = 'true';
						$posted_data['_data']             = [];
						$posted_data['_data']['desc']     = $get_step->get_entity_description( $inherit_id );
						$data                             = $get_step->duplicate_step( $funnel_id, $inherit_id, $posted_data );

					} elseif ( $duplicate_id > 0 ) {
						$posted_data['original_id']     = $duplicate_id;
						$posted_data['step_id']         = $duplicate_id;
						$posted_data['_data']           = [];
						$posted_data['_data']['title']  = $get_step->get_entity_title( $duplicate_id );
						$posted_data['_data']['desc']   = $get_step->get_entity_description( $duplicate_id );
						$posted_data['_data']['status'] = $get_step->get_entity_status( $duplicate_id );
						$posted_data['_data']['edit']   = $get_step->get_entity_edit_link( $duplicate_id );
						$posted_data['_data']['view']   = $get_step->get_entity_view_link( $duplicate_id );
						$data                           = $get_step->duplicate_step( $funnel_id, $duplicate_id, $posted_data );


					} else {
						$posted_data['title'] = $title;

						if ( $type === 'wc_upsells' ) {
							if ( isset( $request['offer_inherit'] ) ) {
								$posted_data['offer_inherit'] = $request['offer_inherit'];
							}
							if ( isset( $request['offer_title'] ) ) {
								$posted_data['offer_title'] = $request['offer_title'];
							}
						}
						$data = $get_step->add_step( $funnel_id, $posted_data );

						if ( ! empty( $data ) && $data->id > 0 ) {

							if ( $builder !== '' && $template !== '' ) {
								$step_args = [
									'id'       => $data->id,
									'builder'  => $builder,
									'template' => $template
								];

								if ( ! empty( $canvas_data ) ) {
									$this->import_step( $step_args, $type, $funnel_id, $request );
								} else {
									return $this->import_step( $step_args, $type, $funnel_id, $request );
								}
							}
						}
					}

					$step_data = array();

					if ( ! empty( $data ) ) {
						$funnel = new WFFN_Funnel( $funnel_id );
						if ( $type === 'wc_checkout' && ! empty( $data ) && $data->id > 0 ) {
							$is_store = WFFN_Common::override_store_checkout_option( $funnel_id );

							/**
							 * recreate funnel object for get store funnel bump
							 */
							if ( true === $is_store ) {
								$funnel = new WFFN_Funnel( $funnel_id );
							}
						}
						$steps = $funnel->get_steps();
						foreach ( $steps as $step ) {
							if ( $data->id === $step['id'] ) {
								$step_data = $get_step->populate_data_properties( $step, $funnel_id );

								break;
							}
						}
						$step_data['id']   = $data->id;
						$step_data['type'] = $data->type;
						/**
						 * prepare data for canvas screen
						 */
						if ( ! empty( $canvas_data ) ) {
							$step_data = $this->maybe_canvas_mode( $canvas_data, $step_data, $funnel, $step_data );
						}
						$resp['data'] = $step_data;

						$resp['status'] = true;
					}

				}
			}
			$funnel = new WFFN_Funnel( $funnel_id );

			$resp['count_data'] = array(
				'steps' => $funnel->get_step_count(),
			);

			return $resp;

		}

		/**
		 * @param $canvas_data
		 * @param $new_step
		 * @param WFFN_Funnel $funnel
		 * @param $step_object
		 *
		 * @return array
		 */
		public function maybe_canvas_mode( $canvas_data, $new_step, $funnel, $step_object ) {
			$step_data = [];
			if ( ! empty( $canvas_data ) ) {
				if ( ! empty( $funnel ) ) {
					$funnel->maybe_update_canvas( $new_step['id'], $canvas_data );
				}
				$prepare_data               = [];
				$prepare_data['steps_list'] = [];
				$prepare_data['groups']     = [];
				if ( 'wc_upsells' === $new_step['type'] ) {
					$get_object  = WFFN_Core()->steps->get_integration_object( 'wc_upsells' );
					$upsell_step = $get_object->populate_data_properties( array( 'type' => 'wc_upsells', 'id' => $new_step['id'] ), 0 );

					$prepare_data['groups'][0]                     = [ 'type' => 'wc_upsells', 'id' => $new_step['id'], 'substeps' => [] ];
					$prepare_data['steps_list'][ $new_step['id'] ] = WFFN_REST_Funnel_Canvas::get_instance()->map_list_step( $upsell_step );
					if ( isset( $upsell_step['substeps']['offer'] ) ) {

						foreach ( $upsell_step['substeps']['offer'] as $key => $offer ) {
							$prepare_data['groups'][0]['substeps'][] = [
								'id'   => $offer['id'],
								'type' => 'offer'
							];
							$offer['type']                           = 'offer';

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
							if ( ( 'automatic' === $accepted_id || 'automatic' === $rejected_id ) && isset( $upsell_step['substeps']['offer'][ $key + 1 ] ) ) {
								$accepted_id = ( 'automatic' === $accepted_id ) ? absint( $upsell_step['substeps']['offer'][ $key + 1 ]['id'] ) : absint( $accepted_id );
								$rejected_id = ( 'automatic' === $rejected_id ) ? absint( $upsell_step['substeps']['offer'][ $key + 1 ]['id'] ) : absint( $rejected_id );
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

					$prepare_data['steps_list'] = wffn_rest_api_helpers()->add_step_edit_details( $prepare_data['steps_list'] );
					$prepare_data['steps_list'] = apply_filters( 'wffn_rest_get_funnel_steps', $prepare_data['steps_list'], false );
					$offer                      = $prepare_data;

					return $offer;
				}

				$prepare_data['steps_list'][ $new_step['id'] ] = WFFN_REST_Funnel_Canvas::get_instance()->map_list_step( $new_step );
				$substeps_final                                = [];
				if ( isset( $step_object['substeps'] ) ) {
					foreach ( $step_object['substeps'] as $key => $substeps ) {
						foreach ( $substeps as $substep ) {
							$substeps_final[] = [
								'id'   => $substep['id'],
								'type' => $key
							];
						}

					}
				}
				$prepare_data['groups'][0]  = [ 'type' => $new_step['type'], 'id' => $new_step['id'], 'substeps' => $substeps_final ];
				$prepare_data['steps_list'] = wffn_rest_api_helpers()->add_step_edit_details( $prepare_data['steps_list'] );
				$prepare_data['steps_list'] = apply_filters( 'wffn_rest_get_funnel_steps', $prepare_data['steps_list'], false );

				return $prepare_data;
			}

			return $step_data;
		}

		public function import_step( $step_args, $type, $funnel_id, $request ) {


			if ( $type === 'landing' ) {
				return $this->import_lp_template( $step_args );
			}

			if ( $type === 'optin' ) {
				return $this->import_op_template( $step_args );
			}

			if ( $type === 'optin_ty' ) {
				return $this->import_oty_template( $step_args );
			}

			if ( $type === 'wc_thankyou' ) {
				return $this->import_ty_template( $step_args );
			}

			if ( $type === 'wc_checkout' ) {
				WFFN_Common::override_store_checkout_option( $funnel_id );

				return $this->import_wc_template( $step_args );
			}

			if ( $type === 'wc_upsells' && ! isset( $request['offer_inherit'] ) ) {
				return $this->import_upsell_template( $step_args );
			}
		}

		public function import_lp_template( $args ) {
			$resp     = [
				'status' => false,
				'msg'    => __( 'Importing of template failed', 'funnel-builder' ),
			];
			$builder  = isset( $args['builder'] ) ? sanitize_text_field( $args['builder'] ) : '';
			$template = isset( $args['template'] ) ? sanitize_text_field( $args['template'] ) : '';
			$id       = isset( $args['id'] ) ? sanitize_text_field( $args['id'] ) : '';

			if ( WFFN_Core()->importer->is_empty_template( $builder, $template, 'landing' ) ) {
				$result = array( 'success' => true );

			} else {
				$result = WFFN_Core()->importer->import_remote( $id, $builder, $template, 'landing' );

			}

			if ( true === $result['success'] ) {

				$update_design = [
					'selected'      => $template,
					'selected_type' => $builder
				];

				do_action( 'wffn_design_saved', $id, $builder, 'landing' );

				WFFN_Core()->landing_pages->update_page_design( $id, $update_design );
				do_action( 'wflp_page_design_updated', $id, $update_design );

				$resp['status'] = true;
				$resp['msg']    = __( 'Importing of template finished' . __FUNCTION__, 'funnel-builder' );
			} else {
				$resp['error'] = $result['error'];
			}

			return $resp;
		}

		public function import_op_template( $args ) {
			$resp     = [
				'status' => false,
				'msg'    => __( 'Importing of template failed', 'funnel-builder' ),
			];
			$builder  = isset( $args['builder'] ) ? sanitize_text_field( $args['builder'] ) : '';
			$template = isset( $args['template'] ) ? sanitize_text_field( $args['template'] ) : '';
			$id       = isset( $args['id'] ) ? sanitize_text_field( $args['id'] ) : '';

			if ( WFFN_Core()->importer->is_empty_template( $builder, $template, 'optin' ) ) {
				$result = array( 'success' => true );

			} else {
				$result = WFFN_Core()->importer->import_remote( $id, $builder, $template, 'optin' );

			}

			if ( true === $result['success'] ) {

				$update_design = [
					'selected'      => $template,
					'selected_type' => $builder
				];
				do_action( 'wffn_design_saved', $id, $builder, 'optin' );

				WFOPP_Core()->optin_pages->update_page_design( $id, $update_design );
				do_action( 'wfop_page_design_updated', $id, $update_design );

				$resp['status'] = true;
				$resp['msg']    = __( 'Importing of template finished' . __FUNCTION__, 'funnel-builder' );
			} else {
				$resp['error'] = $result['error'];
			}

			return $resp;
		}

		public function import_oty_template( $args ) {
			$resp     = [
				'status' => false,
				'msg'    => __( 'Importing of template failed', 'funnel-builder' ),
			];
			$builder  = isset( $args['builder'] ) ? sanitize_text_field( $args['builder'] ) : '';
			$template = isset( $args['template'] ) ? sanitize_text_field( $args['template'] ) : '';
			$id       = isset( $args['id'] ) ? sanitize_text_field( $args['id'] ) : '';

			if ( WFFN_Core()->importer->is_empty_template( $builder, $template, 'optin_ty' ) ) {
				$result = array( 'success' => true );

			} else {
				$result = WFFN_Core()->importer->import_remote( $id, $builder, $template, 'optin_ty' );

			}

			if ( true === $result['success'] ) {

				$update_design = [
					'selected'      => $template,
					'selected_type' => $builder
				];
				do_action( 'wffn_design_saved', $id, $builder, 'optin_ty' );

				WFOPP_Core()->optin_ty_pages->update_page_design( $id, $update_design );
				do_action( 'wfoty_page_design_updated', $id, $update_design );

				$resp['status'] = true;
				$resp['msg']    = __( 'Importing of template finished' . __FUNCTION__, 'funnel-builder' );
			} else {
				$resp['error'] = $result['error'];
			}

			return $resp;
		}

		public function import_ty_template( $args ) {
			$resp     = [
				'status' => false,
				'msg'    => __( 'Importing of template failed', 'funnel-builder' ),
			];
			$builder  = isset( $args['builder'] ) ? sanitize_text_field( $args['builder'] ) : '';
			$template = isset( $args['template'] ) ? sanitize_text_field( $args['template'] ) : '';
			$id       = isset( $args['id'] ) ? sanitize_text_field( $args['id'] ) : '';

			if ( WFFN_Core()->importer->is_empty_template( $builder, $template, 'wc_thankyou' ) ) {
				$result = array( 'success' => true );

			} else {
				$result = WFFN_Core()->importer->import_remote( $id, $builder, $template, 'wc_thankyou' );

			}

			if ( true === $result['success'] ) {

				$update_design = [
					'selected'      => $template,
					'selected_type' => $builder
				];
				do_action( 'wffn_design_saved', $id, $builder, 'wc_thankyou' );

				WFFN_Core()->thank_you_pages->update_page_design( $id, $update_design );
				do_action( 'wfty_page_design_updated', $id, $update_design );

				$resp['status'] = true;
				$resp['msg']    = __( 'Importing of template finished' . __FUNCTION__, 'funnel-builder' );
			} else {
				$resp['error'] = $result['error'];
			}

			return $resp;
		}

		public function import_wc_template( $args ) {

			$resp     = [
				'status' => false,
				'msg'    => __( 'Importing of template failed', 'funnel-builder' ),
			];
			$builder  = isset( $args['builder'] ) ? sanitize_text_field( $args['builder'] ) : '';
			$template = isset( $args['template'] ) ? sanitize_text_field( $args['template'] ) : '';
			$id       = isset( $args['id'] ) ? sanitize_text_field( $args['id'] ) : '';
			$is_multi = isset( $args['is_multi'] ) ? $args['is_multi'] : '';

			WFACP_Core()->template_loader->add_default_template( true );
			$result = WFACP_Core()->importer->import( $id, $builder, $template, $is_multi );

			if ( isset( $result['error'] ) ) {
				$resp['msg'] = $result['error'];
			}

			if ( isset( $result['status'] ) && true === $result['status'] ) {

				$update_design = [
					'selected'        => $template,
					'selected_type'   => $builder,
					'template_active' => 'yes'
				];

				WFACP_Common::update_page_design( $id, $update_design );

				$resp['status'] = true;
				$resp['msg']    = __( 'Importing of template finished' . __FUNCTION__, 'funnel-builder' );
			} else {
				$resp['error'] = $result['error'];
			}

			return $resp;

		}

		public function import_upsell_template( $args ) {

			$resp        = [
				'status' => false,
				'msg'    => __( 'Importing of template failed', 'funnel-builder' ),
			];
			$builder     = isset( $args['builder'] ) ? sanitize_text_field( $args['builder'] ) : '';
			$template    = isset( $args['template'] ) ? sanitize_text_field( $args['template'] ) : '';
			$id          = isset( $args['id'] ) ? sanitize_text_field( $args['id'] ) : '';
			$funnel_step = get_post_meta( $id, '_funnel_steps', true );

			$offer = isset( $funnel_step[0]['id'] ) ? $funnel_step[0]['id'] : 0;
			$meta  = get_post_meta( $offer, '_wfocu_setting', true );

			if ( ! class_exists( 'WFOCU_Core' ) ) {
				return $resp;
			}
			if ( is_object( $meta ) ) {
				$meta->template       = $template;
				$meta->template_group = $builder;

				$result = WFOCU_Core()->importer->maybe_import_data( $builder, $template, $offer, $meta );
				if ( is_string( $result ) ) {
					$resp['status'] = false;
					$resp['msg']    = $result;
				} else {
					WFOCU_Common::update_offer( $offer, $meta );

					if ( '' !== $id ) {
						WFOCU_Common::update_funnel_time( $id );
					}

					$resp['status'] = true;
					$resp['msg']    = __( 'Importing of template finished' . __FUNCTION__, 'funnel-builder' );
				}

			}

			return $resp;

		}


		public function get_create_steps_collection() {
			$params                 = array();
			$params['type']         = array(
				'description' => __( 'Step type.', 'funnel-builder' ),
				'type'        => 'string',
				'required'    => true,

			);
			$params['title']        = array(
				'description' => __( 'Step name.', 'funnel-builder' ),
				'type'        => 'string',

			);
			$params['design']       = array(
				'description' => __( 'Step Design.', 'funnel-builder' ),
				'type'        => 'string',
				'default'     => 'scratch'
			);
			$params['inherit_from'] = array(
				'description' => __( 'Inherit Step.', 'funnel-builder' ),
				'type'        => 'integer',
				'default'     => 0,
			);
			$params['duplicate_id'] = array(
				'description' => __( 'Duplicate Step.', 'funnel-builder' ),
				'type'        => 'integer',
				'default'     => 0,
			);

			return apply_filters( 'wffn_rest_create_steps_collection', $params );
		}


		public function update_step( $request ) {

			$resp = array(
				'status'   => false,
				'switched' => 0,
			);

			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel_id = ! empty( $funnel_id ) ? $funnel_id : 0;

			$step_id    = $request->get_param( 'step_id' );
			$step_id    = ! empty( $step_id ) ? $step_id : 0;
			$type       = isset( $request['type'] ) ? $request['type'] : '';
			$new_status = isset( $request['new_status'] ) ? $request['new_status'] : 0;
			BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );
			if ( $funnel_id === 0 || $step_id === 0 ) {
				return $resp;
			}

			if ( $funnel_id > 0 && ! empty( $type ) ) {
				$get_step = WFFN_Core()->steps->get_integration_object( $type );
				if ( $get_step instanceof WFFN_Step ) {
					$switched         = $get_step->switch_status( $step_id, $new_status );
					$resp['status']   = true;
					$resp['switched'] = $switched;
				}
			}
			$funnel             = new WFFN_Funnel( $funnel_id );
			$resp['count_data'] = array(
				'steps' => $funnel->get_step_count(),
			);

			return $resp;
		}

		public function get_update_steps_collection() {
			$params               = array();
			$params['type']       = array(
				'description' => __( 'Step type.', 'funnel-builder' ),
				'type'        => 'string',
				'required'    => true,
			);
			$params['new_status'] = array(
				'description' => __( 'Set step status.', 'funnel-builder' ),
				'type'        => 'integer',
				'default'     => 0,

			);

			return apply_filters( 'wffn_rest_update_steps_collection', $params );
		}

		public static function delete_step( $request ) {

			$resp = array(
				'status' => false,
			);

			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel_id = ! empty( $funnel_id ) ? $funnel_id : 0;

			$step_id = $request->get_param( 'step_id' );
			$step_id = ! empty( $step_id ) ? $step_id : 0;
			$type    = isset( $request['type'] ) ? $request['type'] : '';

			if ( $funnel_id === 0 || $step_id === 0 ) {
				return $resp;
			}

			if ( $funnel_id > 0 && ! empty( $type ) ) {
				$get_step = WFFN_Core()->steps->get_integration_object( $type );
				if ( $get_step instanceof WFFN_Step ) {
					$deleted        = $get_step->delete_step( $funnel_id, $step_id );
					$resp['status'] = ( $deleted > 0 ) ? true : false;

				}
			}
			$funnel             = new WFFN_Funnel( $funnel_id );
			$resp['count_data'] = array(
				'steps' => $funnel->get_step_count(),
			);
			$resp['setup']      = WFFN_REST_Setup::get_instance()->get_status_responses( false );

			return $resp;
		}

		public function get_delete_steps_collection() {
			$params         = array();
			$params['type'] = array(
				'description' => __( 'Step type.', 'funnel-builder' ),
				'type'        => 'string',
				'required'    => true,
			);

			return apply_filters( 'wffn_rest_delete_steps_collection', $params );
		}

		/**
		 * @param WP_REST_Request $request
		 *
		 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
		 */
		public function search_entity( WP_REST_Request $request ) {
			$search     = $request->get_param( 's' );
			$type       = $request->get_param( 'type' );
			$is_substep = $request->get_param( 'is_substep' );
			$funnel_id  = $request->get_param( 'funnel_id' );
			$funnel_id  = ( empty( $funnel_id ) || 'undefined' === $funnel_id ) ? 0 : $funnel_id;

			if ( true === $is_substep ) {
				if ( 'offer' === $type ) {
					$get_step = WFFN_Core()->steps->get_integration_object( 'wc_upsells' );
					$get_step->set_type( $type );
					$designs = $get_step->get_substep_designs( $search, $funnel_id );
				} else {

					$get_substep = WFFN_Core()->substeps->get_integration_object( $type );
					$designs     = $get_substep->get_substep_designs( $search, $funnel_id );
				}


			} else {
				$get_step = WFFN_Core()->steps->get_integration_object( $type );
				$designs  = $get_step->get_step_designs( $search, $funnel_id );
			}

			/**
			 * if we have funnel ID then we are only passing single funnel data here
			 */
			if ( 0 !== $funnel_id ) {
				if ( isset( $designs[0] ) ) {
					$designs = $designs[0]['steps'];
				}
			}

			return rest_ensure_response( $designs );
		}

	}

	if ( ! function_exists( 'wffn_rest_steps' ) ) {

		function wffn_rest_steps() {  //@codingStandardsIgnoreLine
			return WFFN_REST_Steps::get_instance();
		}
	}

	wffn_rest_steps();
}