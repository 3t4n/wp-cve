<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Store_Checkout
 *
 * * @extends WFFN_REST_Store_Checkout
 */
if ( ! class_exists( 'WFFN_REST_Store_Checkout' ) ) {
	#[AllowDynamicProperties]
	class WFFN_REST_Store_Checkout extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'store-checkout';
		protected $rest_base_id = 'store-checkout/(?P<funnel_id>[\d]+)/';

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
		 * Register the routes for store checkout.
		 */
		public function register_routes() {
			register_rest_route( $this->namespace, '/' . $this->rest_base, array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_store_checkout' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array_merge( $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ), $this->get_create_funnels_collection() ),
				)
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/duplicate/(?P<funnel_id>[\d]+)', array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_clone_checkout' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array_merge( $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ), $this->get_create_funnels_collection() ),
				)
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/import_template', array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'import_template' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'funnel_id' => array(
							'description'       => __( 'Unique funnel id.', 'funnel-builder' ),
							'type'              => 'integer',
							'validate_callback' => 'rest_validate_request_arg',
							'default'           => 0,
						),
						'title'     => array(
							'description'       => __( 'Funnel name.', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'template'  => array(
							'description'       => __( 'template slug identifier', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'builder'   => array(
							'description'       => __( 'template group identifier', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'steps'     => array(
							'description'       => __( 'steps', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'sanitize_callback' => array( $this, 'sanitize_custom' ),
						),
					),
				),
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base_id, array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_store_checkout' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_store_checkout' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base_id . 'status', array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_status' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/export/', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'export_funnels' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						'ids' => array(
							'description'       => __( 'Funnel id', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/import/', array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'import_funnels' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => []
				),
			) );

			/*** Store checkout steps ****/
			register_rest_route( $this->namespace, '/' . $this->rest_base_id . 'step/(?P<step_id>[\d]+)', array(
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
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_store_step' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => $this->get_delete_steps_collection()
				),

				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			/*** sub steps ***/
			register_rest_route( $this->namespace, '/' . $this->rest_base_id . 'step/(?P<step_id>[\d]+)/substeps', array(
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
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_substep' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array_merge( $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ), $this->get_create_substeps_collection() ),
				),
			) );
			register_rest_route( $this->namespace, '/' . $this->rest_base_id . 'step/(?P<step_id>[\d]+)/substeps/(?P<substep_id>[\d]+)', array(
				'args' => array(
					'funnel_id'  => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
					'step_id'    => array(
						'description' => __( 'Current step id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
					'substep_id' => array(
						'description' => __( 'Current substep id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_substep' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'type' => array(
							'description' => __( 'Step type', 'funnel-builder' ),
							'type'        => 'string',
							'required'    => true,
						),
					)
				),
			) );


		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		public function get_store_checkout( WP_REST_Request $request ) {
			$funnel_id                    = $request->get_param( 'funnel_id' );
			$funnel                       = new WFFN_Funnel( $funnel_id );

			if ( $funnel->get_id() === 0 ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}
			BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );

			$steps = $funnel->get_steps( true );

			if ( is_array( $steps ) && count( $steps ) > 0 ) {
				if ( false === in_array( 'wc_checkout', wp_list_pluck( $steps, 'type' ), true ) ) {
					$sub_steps     = WFFN_Common::get_store_checkout_global_substeps( $funnel_id );
					$sub_step_data = [];
					if ( is_array( $sub_steps ) && count( $sub_steps ) > 0 ) {
						$get_substep = WFFN_Core()->substeps->get_integration_object( 'wc_order_bump' );
						if ( $get_substep instanceof WFFN_Substep ) {
							$sub_step_data = $get_substep->populate_substep_data_properties( $sub_steps );
						}
					}
					$native_checkout = array(
						'id'       => 0,
						'type'     => WFFN_Common::store_native_checkout_slug(),
						'substeps' => $sub_step_data,
					);
					array_unshift( $steps, $native_checkout );
				}
				$steps = wffn_rest_api_helpers()->add_step_edit_details( $steps );
				$steps = apply_filters( 'wffn_rest_get_funnel_steps', $steps, $funnel );

			} else {
				$steps         = [];
				$sub_steps     = WFFN_Common::get_store_checkout_global_substeps( $funnel_id );
				$sub_step_data = [];
				if ( is_array( $sub_steps ) && count( $sub_steps ) > 0 ) {
					$get_substep = WFFN_Core()->substeps->get_integration_object( 'wc_order_bump' );
					if ( $get_substep instanceof WFFN_Substep ) {
						$sub_step_data = $get_substep->populate_substep_data_properties( $sub_steps );
					}
				}
				$native_checkout = array(
					'id'       => 0,
					'type'     => WFFN_Common::store_native_checkout_slug(),
					'substeps' => $sub_step_data,
				);
				array_unshift( $steps, $native_checkout );
				$steps = wffn_rest_api_helpers()->add_step_edit_details( $steps );
				$steps = apply_filters( 'wffn_rest_get_funnel_steps', $steps, $funnel );
			}

			$funnel_status = ( 0 === (int) WFFN_Core()->get_dB()->get_meta( $funnel_id, 'status' ) ) ?  'Draft' :  'Published';
			$return        = array(
				'id'          => $funnel->get_id(),
				'title'       => $funnel->get_title(),
				'description' => $funnel->get_desc(),
				'link'        => $funnel->get_view_link(),
				'status'      => $funnel_status,
				'steps'       => $steps,

			);

			return rest_ensure_response( $return );
		}

		public function create_store_checkout( $request ) {

			$resp        = array(
				'status' => false,
				'msg'    => __( 'Funnel creation failed', 'funnel-builder' )
			);
			$funnel_id   = 0;
			$posted_data = array();

			$posted_data['funnel_id']   = isset( $request['funnel_id'] ) ? $request['funnel_id'] : 0;
			$posted_data['funnel_name'] = ( isset( $request['funnel_name'] ) && ! empty( $request['funnel_name'] ) ) ? $request['funnel_name'] : '';
			$default_step               = isset( $request['default_step'] ) ? $request['default_step'] : '';
			$builder                    = isset( $request['builder'] ) ? $request['builder'] : '';
			$template                   = isset( $request['template'] ) ? $request['template'] : '';


			do_action( 'wffn_load_api_import_class' );

			if ( ! function_exists( 'media_handle_sideload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			if ( $posted_data['funnel_id'] === 0 && $posted_data['funnel_name'] !== '' ) {
				$funnel_name = ! empty( $posted_data['funnel_name'] ) ? $posted_data['funnel_name'] : __( '(no title)', 'funnel-builder' );
				$funnel      = WFFN_Core()->admin->get_funnel( $posted_data['funnel_id'] );

				if ( $funnel instanceof WFFN_Funnel ) {
					if ( $funnel->get_id() === 0 ) {
						$funnel_id = $funnel->add_funnel( array(
							'title'  => $funnel_name,
							'desc'   => '',
							'status' => 1,
						) );

						if ( $funnel_id > 0 ) {
							$funnel->id = $funnel_id;
						}
					}
				}

				if ( wffn_is_valid_funnel( $funnel ) ) {

					if ( $funnel_id > 0 ) {
						WFFN_Common::update_store_checkout_meta( $funnel_id );
						if ( defined( 'ICL_LANGUAGE_CODE' ) && 'all' !== ICL_LANGUAGE_CODE ) {
							WFFN_Core()->get_dB()->update_meta( $funnel_id, '_lang', ICL_LANGUAGE_CODE );
						}
						$redirect_link = WFFN_Common::get_funnel_edit_link( $funnel_id );

						$resp['status']        = true;
						$resp['funnel']        = $funnel;
						$resp['redirect_link'] = $redirect_link;
						$resp['msg']           = __( 'Funnel create successfully', 'funnel-builder' );

						if ( ( 'yes' === $default_step ) && ! empty( $builder ) && ! empty( $template ) ) {
							$default_steps          = [ 'wc_checkout', 'wc_thankyou' ];
							$step_data              = [];
							$step_data['funnel_id'] = $funnel_id;
							$step_data['builder']   = $builder;
							$step_data['template']  = $template;
							foreach ( $default_steps as $item ) {
								$step_data['type'] = $item;
								$this->create_default_step( $step_data );
							}
						}

					} else {
						$resp['msg'] = __( 'Sorry, we are unable to create funnel due to some technical difficulties. Please contact support', 'funnel-builder' );
					}
				}
			}
			$resp['setup'] = WFFN_REST_Setup::get_instance()->get_status_responses( false );

			return $resp;

		}

		public function create_clone_checkout( $request ) {
			$resp             = array(
				'status' => false,
				'msg'    => __( 'Funnel creation failed', 'funnel-builder' )
			);
			$funnel_id        = $request['funnel_id'];
			$rest_funnel      = WFFN_REST_Funnels::get_instance();
			$duplicate_funnel = $rest_funnel->duplicate_funnel( [ 'funnel_id' => $funnel_id, 'is_clone' => true, 'is_store_checkout' => true ], true );
			if ( isset( $duplicate_funnel['status'] ) && true === $duplicate_funnel['status'] ) {
				$new_funnel_id = $duplicate_funnel['funnel_id'];
				WFFN_Common::update_store_checkout_meta( $new_funnel_id );
				if ( defined( 'ICL_LANGUAGE_CODE' ) && 'all' !== ICL_LANGUAGE_CODE ) {
					WFFN_Core()->get_dB()->update_meta( $new_funnel_id, '_lang', ICL_LANGUAGE_CODE );
				}
				$resp['funnel_id'] = $new_funnel_id;
				$resp['status']    = true;
			} else {
				$resp['msg'] = __( 'Sorry, we are unable to create funnel due to some technical difficulties. Please contact support', 'funnel-builder' );
			}

			return $resp;
		}

		public function import_template( WP_REST_Request $request ) {
			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$funnel_id   = $request->get_param( 'funnel_id' );
			$funnel_name = $request->get_param( 'title' );
			$template    = $request->get_param( 'template' );
			$builder     = $request->get_param( 'builder' );
			$steps       = $request->get_param( 'steps' );

			$resp = array(
				'status' => false
			);

			if ( ! function_exists( 'wfacp_is_woocommerce_active' ) || ! wfacp_is_woocommerce_active() ) {
				$resp['msg'] = __( "Funnel Builder needs WooCommerce to import store checkout funnels.", 'funnel-builder' );

				return rest_ensure_response( $resp );
			}

			if ( ! empty( $builder ) ) {
				$builder_status = WFFN_Core()->page_builders->builder_status( $builder );

				if ( isset( $builder_status['builders_options']['status'] ) && ! empty( $builder_status['builders_options']['status'] ) && 'activated' !== $builder_status['builders_options']['status'] ) {
					return rest_ensure_response( $builder_status );
				}
			}

			if ( empty( $funnel_id ) && $funnel_name !== '' ) {
				$funnel_name = ! empty( $funnel_name ) ? $funnel_name : __( '(no title)', 'funnel-builder' );
				$funnel      = WFFN_Core()->admin->get_funnel( $funnel_id );
				if ( $funnel instanceof WFFN_Funnel ) {
					if ( $funnel->get_id() === 0 ) {
						$funnel_id = $funnel->add_funnel( array(
							'title'  => $funnel_name,
							'desc'   => '',
							'status' => 1,
						) );

						if ( $funnel_id > 0 ) {
							$funnel->id = $funnel_id;
						}
					}
				}

				if ( wffn_is_valid_funnel( $funnel ) ) {

					if ( $funnel_id > 0 ) {
						WFFN_Common::update_store_checkout_meta( $funnel_id );
						if ( defined( 'ICL_LANGUAGE_CODE' ) && 'all' !== ICL_LANGUAGE_CODE ) {
							WFFN_Core()->get_dB()->update_meta( $funnel_id, '_lang', ICL_LANGUAGE_CODE );
						}
					} else {
						$resp['msg'] = __( 'Sorry, we are unable to create funnel due to some technical difficulties. Please contact support', 'funnel-builder' );

						return rest_ensure_response( $resp );
					}
				}
			}

			if ( 0 === absint( $funnel_id ) ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}

			if ( ! empty( $template ) && ! empty( $builder ) ) {
				$funnel_data = WFFN_Core()->remote_importer->get_remote_template( 'funnel', $template, $builder, $steps );
				if ( is_array( $funnel_data ) && isset( $funnel_data['error'] ) ) {
					$resp['msg'] = $funnel_data['error'];

					return rest_ensure_response( $resp );
				}

				/**
				 * Lets do the data import which will first create the steps and their respective entities
				 */
				$funnel_data[0]['id'] = $funnel_id;

				if ( ! function_exists( 'post_exists' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/post.php' );
				}

				do_action( 'wffn_load_api_import_class' );
				WFFN_Core()->import->import_from_json_data( $funnel_data );

				update_option( '_wffn_scheduled_funnel_id', $funnel_id );
				BWF_Logger::get_instance()->log( sprintf( 'Background template importer for funnel id %d is started', $funnel_id ), 'wffn_template_import' );
				WFFN_Core()->admin->wffn_maybe_run_templates_importer();

				WFFN_Common::override_store_checkout_option( $funnel_id );

				/**
				 * return success
				 */
				$resp['status']    = true;
				$resp['funnel_id'] = $funnel_id;
				$resp['msg']       = __( 'Success', 'funnel-builder' );

			}
			$resp['setup'] = WFFN_REST_Setup::get_instance()->get_status_responses( false );
			return rest_ensure_response( $resp );
		}

		public function delete_store_checkout( $request ) {

			$result = [
				'status'  => false,
				'message' => __( 'Something went wrong. Please try again', 'funnel-builder' )
			];

			$funnel_id = isset( $request['funnel_id'] ) ? $request['funnel_id'] : 0;

			if ( empty( $funnel_id ) || absint( $funnel_id ) === 0 ) {
				return rest_ensure_response( $result );
			}

			$funnel = WFFN_Core()->admin->get_funnel( $funnel_id );

			$deleted = $funnel->delete();
			if ( ! $deleted ) {
				return rest_ensure_response( $result );
			}

			$this->delete_global_funnel_data();

			$result = [
				'status' => true,
				'setup'  => WFFN_REST_Setup::get_instance()->get_status_responses( false ),
			];

			return rest_ensure_response( $result );
		}

		public function export_funnels( WP_REST_Request $request ) {

			$result = [
				'status'  => false,
				'message' => __( 'Something went wrong. Please try again', 'funnel-builder' )
			];

			do_action( 'wffn_load_api_export_class' );
			$items   = [];
			$funnels = [];

			$ids = $request->get_param( 'ids' );
			$ids = ( isset( $ids ) && $ids !== '' ) ? explode( ',', $ids ) : '';

			if ( is_array( $ids ) && count( $ids ) > 0 ) {
				foreach ( $ids as $funnel_id ) {
					$funnel = WFFN_Core()->admin->get_funnel( (int) $funnel_id );
					if ( $funnel instanceof WFFN_Funnel ) {
						$items[] = array(
							'id'         => $funnel->get_id(),
							'title'      => $funnel->get_title(),
							'desc'       => $funnel->get_desc(),
							'date_added' => $funnel->get_date_added(),
							'steps'      => $funnel->get_steps( true ),
							'__funnel'   => $funnel,
						);
					}
				}
				$funnels['items'] = $items;
			}

			if ( ! isset( $funnels['items'] ) || count( $funnels['items'] ) === 0 ) {
				return rest_ensure_response( $result );
			}

			$funnels_to_export = [];

			foreach ( $funnels['items'] as $key => $funnel ) {
				$funnels_to_export[ $key ] = [];
				/**
				 * var WFFN_Funnel $get_funnel
				 */
				$get_funnel                         = $funnel['__funnel'];
				$funnels_to_export[ $key ]['title'] = $get_funnel->get_title();
				$funnels_to_export[ $key ]['steps'] = [];

				$steps = $get_funnel->get_steps( true );

				if ( false === in_array( 'wc_checkout', wp_list_pluck( $steps, 'type' ), true ) ) {
					$bumps = WFFN_Common::get_store_checkout_global_substeps( $funnel['id'] );
					if ( is_array( $bumps ) && count( $bumps ) > 0 ) {
						$native_checkout = array(
							'id'       => 0,
							'type'     => WFFN_Common::store_native_checkout_slug(),
							'substeps' => $bumps,
						);
						array_unshift( $steps, $native_checkout );
					}

				}

				foreach ( $steps as $k => $step ) {
					$get_object = WFFN_Core()->steps->get_integration_object( $step['type'] );
					if ( isset( $step['type'] ) && WFFN_Common::store_native_checkout_slug() === $step['type'] ) {
						$step_export_data = $this->maybe_have_substeps_export( $step );
					} else {
						$step_export_data = $get_object->get_export_data( $step );
					}

					$funnels_to_export[ $key ]['steps'][ $k ] = $step_export_data;
				}
			}
			$funnels_to_export = apply_filters( 'wffn_export_data', $funnels_to_export );

			nocache_headers();

			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=wffn-funnels-export-' . gmdate( 'm-d-Y' ) . '.json' );
			header( 'Expires: 0' );

			echo wp_json_encode( $funnels_to_export );
			exit;
		}

		public function import_funnels( WP_REST_Request $request ) {
			$result = [
				'status' => false,
			];

			$files = $request->get_file_params();

			do_action( 'wffn_load_api_import_class' );

			if ( ! function_exists( 'post_exists' ) ) {
				require_once ABSPATH . 'wp-admin/includes/post.php';
			}

			if ( ! function_exists( 'media_handle_sideload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			if ( empty( $files ) ) {
				$result['message'] = __( 'Import File missing.', 'funnel-builder' );

				return $result;
			}

			if ( ! isset( $files['files']['name'] ) ) {
				$result['message'] = __( 'File name not valid.', 'funnel-builder' );

				return $result;
			}
			if ( ! isset( $files['files']['tmp_name'] ) ) {
				$result['message'] = __( 'File type not valid.', 'funnel-builder' );

				return $result;
			}

			$filename  = wffn_clean( $files['files']['name'] );
			$file_info = explode( '.', $filename );
			$extension = end( $file_info );

			if ( 'json' !== $extension ) {
				$result['message'] = __( 'Please upload a valid .json file', 'funnel-builder' );

				return $result;
			}

			$file = wffn_clean( $files['files']['tmp_name'] );

			if ( empty( $file ) ) {
				$result['message'] = __( 'Please upload a file to import', 'funnel-builder' );

				return $result;
			}

			// Retrieve the settings from the file and convert the JSON object to an array.
			$funnels = json_decode( file_get_contents( $file ), true ); //phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown

			if ( true === WFFN_Core()->import->validate_json( $funnels ) ) {
				$override_existing = $request->get_param( 'override_existing' );
				$override_existing = ! empty( $override_existing ) ? $override_existing : '';
				$id                = $request->get_param( 'id' );
				$id                = ! empty( $id ) ? $id : 0;

				if ( 'yes' === $override_existing && $id > 0 ) {
					$args              = [];
					$args['funnel_id'] = $id;
					$this->delete_store_checkout( $args );
				}

				$funnel_id = WFFN_Core()->import->import_store_checkout_json_data( $funnels );
				if ( absint( $funnel_id ) > 0 ) {
					WFFN_Common::update_store_checkout_meta( $funnel_id );
					WFFN_Common::override_store_checkout_option( $funnel_id );
					$result = [
						'status'    => true,
						'funnel_id' => $funnel_id,
					];
				}
			} else {
				$result = [
					'message' => __( 'Error: Invalid File Format. Please contact support.', 'funnel-builder' ),
					'status'  => false,
				];
			}

			$result['setup'] = WFFN_REST_Setup::get_instance()->get_status_responses( false );
			return rest_ensure_response( $result );
		}

		public function maybe_have_substeps_export( $step ) {
			$sub_steps = [];
			if ( isset( $step['substeps'] ) && ! empty( $step['substeps'] ) ) {
				foreach ( $step['substeps'] as $key => $substeps ) {
					$sub_steps[ $key ]  = [];
					$get_substep_object = WFFN_Core()->substeps->get_integration_object( $key );
					if ( ! empty( $get_substep_object ) ) {
						foreach ( $substeps as $substep ) {
							$get_step            = [];
							$get_step['id']      = $substep;
							$sub_steps[ $key ][] = $get_substep_object->_get_export_metadata( $get_step );

						}
					}
				}
			}

			$step['substeps'] = $sub_steps;

			return $step;
		}


		public function update_status( WP_REST_Request $request ) {
			$resp      = array(
				'status'  => false,
				'result'  => '',
				'message' => __( 'Something went wrong. Please try again', 'funnel-builder' )
			);
			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel_id = ! empty( $funnel_id ) ? $funnel_id : 0;

			if ( $funnel_id > 0 ) {
				$get_status    = WFFN_Core()->get_dB()->get_meta( $funnel_id, 'status' );
				$funnel_status = wffn_string_to_bool( $get_status ) ? false : true;
				WFFN_Core()->get_dB()->update_meta( $funnel_id, 'status', $funnel_status );
				$resp = array(
					'status' => true,
					'result' => $funnel_status,
				);
			}

			return rest_ensure_response( $resp );
		}

		public function delete_store_step( $request ) {

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
			$funnel = new WFFN_Funnel( $funnel_id );
			if ( $funnel_id > 0 && ! empty( $type ) ) {
				$get_step = WFFN_Core()->steps->get_integration_object( $type );
				if ( $get_step instanceof WFFN_Step ) {

					$bumps           = [];
					$delete_substeps = false;
					$checkout        = 0;
					if ( 'wc_checkout' === $type ) {
						$steps = $funnel->get_steps();
						if ( is_array( $steps ) && count( $steps ) > 0 ) {
							foreach ( $steps as $step ) {
								if ( 'wc_checkout' === $step['type'] ) {
									if ( isset( $step['substeps'] ) && is_array( $step['substeps'] ) && count( $step['substeps'] ) > 0 ) {
										$bumps = $step['substeps'];
									}

									$checkout ++;
								}
							}

							/*
							 * delete checkout with order bump if multiple checkout exists
							 */
							if ( $checkout > 1 ) {
								$delete_substeps = true;
							}
						}


					}

					$deleted = $get_step->delete_step( $funnel_id, $step_id, $delete_substeps );


					/*
					 * update sub-steps if one single checkout exists
					 */
					if ( $deleted > 0 && 'wc_checkout' === $type && $checkout <= 1 ) {
						WFFN_Common::update_substeps_store_checkout_meta( $funnel_id, $bumps );
						$prepare_data                  = [];
						$prepare_data['steps_list']    = [];
						$prepare_data['steps_list'][0] = WFFN_REST_Funnel_Canvas::get_instance()->map_list_step( [ 'type' => 'wc_native' ] );

						if ( ! empty( $bumps ) ) {

							$substeps_final = [];

							foreach ( $bumps['wc_order_bump'] as $substep ) {
								$substeps_final[]                       = [
									'id'   => $substep,
									'type' => 'wc_order_bump'
								];
								$bump_object                            = WFFN_Core()->substeps->get_integration_object( 'wc_order_bump' );
								$bump_data                              = $bump_object->populate_substeps_data_properties( array( $substep ) );
								$bump_data[0]['type']                   = 'wc_order_bump';
								$prepare_data['steps_list'][ $substep ] = WFFN_REST_Funnel_Canvas::get_instance()->map_list_step( $bump_data[0] );
							}


						}
						$prepare_data['groups'][0]  = [ 'type' => 'wc_native', 'id' => 0, 'substeps' => isset( $substeps_final ) ? $substeps_final : [] ];
						$prepare_data['steps_list'] = apply_filters( 'wffn_rest_get_funnel_steps', $prepare_data['steps_list'], false );
						$resp                       = array_merge( $resp, $prepare_data );
					}
					$resp['status'] = ( $deleted > 0 ) ? true : false;
				}
			}

			$resp['count_data'] = array(
				'steps' => $funnel->get_step_count(),
			);


			$resp['setup'] = WFFN_REST_Setup::get_instance()->get_status_responses( false );

			return $resp;
		}

		public function create_default_step( $request ) {
			$funnel_id = isset( $request['funnel_id'] ) ? $request['funnel_id'] : 0;
			$type      = isset( $request['type'] ) ? $request['type'] : '';
			$builder   = isset( $request['builder'] ) ? $request['builder'] : '';
			$template  = isset( $request['template'] ) ? str_replace( '_funnel', '', $request['template'] ) : '';
			$title     = $type === 'wc_checkout' ? __( 'Checkout', 'funnel-builder' ) : __( 'Thank you Page', 'funnel-builder' );
			$resp      = [];
			if ( ! empty( $builder ) && ( 'gutenberg_1' !== $template && 'wfocu-gutenberg-empty' !== $template ) ) {
				$builder_status = WFFN_Core()->page_builders->builder_status( $builder );

				if ( isset( $builder_status['builders_options']['status'] ) && ! empty( $builder_status['builders_options']['status'] ) && 'activated' !== $builder_status['builders_options']['status'] ) {
					return rest_ensure_response( $builder_status );
				}
			}

			$posted_data              = array();
			$posted_data['funnel_id'] = $funnel_id;
			$posted_data['type']      = $type;

			if ( $funnel_id > 0 && ! empty( $type ) ) {

				if ( $type === 'wc_checkout' || $type === 'wc_upsells' || $type === 'wc_thankyou' ) {
					if ( ( function_exists( 'wfocu_is_woocommerce_active' ) && ! wfocu_is_woocommerce_active() ) || ( function_exists( 'wfacp_is_woocommerce_active' ) && ! wfacp_is_woocommerce_active() ) ) {
						$resp['msg'] = __( "Funnel Builder needs WooCommerce to run this step.", 'funnel-builder' );

						return $resp;
					}
				}

				$get_step = WFFN_Core()->steps->get_integration_object( $type );
				if ( $get_step instanceof WFFN_Step ) {
					$posted_data['title'] = $title;
					$data                 = $get_step->add_step( $funnel_id, $posted_data );
					if ( ! empty( $data ) && $data->id > 0 ) {

						if ( $builder !== '' && $template !== '' ) {
							$step_args = [
								'id'       => $data->id,
								'builder'  => $builder,
								'template' => $template
							];
							if ( $type === 'wc_checkout' ) {
								WFFN_Common::override_store_checkout_option( $funnel_id );
								$this->import_wc_template( $step_args );
							}
							if ( $type === 'wc_thankyou' ) {
								$this->import_ty_template( $step_args );
							}
						}
					}
				}
			}
		}

		public function import_ty_template( $args ) {
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
			}
		}

		public function import_wc_template( $args ) {
			$builder  = isset( $args['builder'] ) ? sanitize_text_field( $args['builder'] ) : '';
			$template = isset( $args['template'] ) ? sanitize_text_field( $args['template'] ) : '';
			$id       = isset( $args['id'] ) ? sanitize_text_field( $args['id'] ) : '';
			$is_multi = isset( $args['is_multi'] ) ? $args['is_multi'] : '';

			if ( 'wp_editor' === $builder ) {
				$builder  = 'embed_forms';
				$template = 'embed_forms_1';
			}

			WFACP_Core()->template_loader->add_default_template( true );
			$result = WFACP_Core()->importer->import( $id, $builder, $template, $is_multi );

			if ( isset( $result['error'] ) ) {
				return;
			}

			if ( isset( $result['status'] ) && true === $result['status'] ) {

				$update_design = [
					'selected'        => $template,
					'selected_type'   => $builder,
					'template_active' => 'yes'
				];

				WFACP_Common::update_page_design( $id, $update_design );

			}
		}

		public function create_substep( $request ) {

			$resp = array(
				'status' => false,
			);

			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel_id = ! empty( $funnel_id ) ? $funnel_id : 0;

			$step_id      = $request->get_param( 'step_id' );
			$step_id      = ! empty( $step_id ) ? $step_id : 0;
			$type         = isset( $request['type'] ) ? $request['type'] : '';
			$title        = isset( $request['title'] ) ? $request['title'] : __( 'New Sub Step', 'funnel-builder' );
			$design       = isset( $request['design'] ) ? $request['design'] : '';
			$duplicate_id = isset( $request['duplicate_id'] ) ? $request['duplicate_id'] : 0;
			$inherit_id   = isset( $request['inherit_from'] ) ? $request['inherit_from'] : 0;
			$canvas_data  = isset( $request['canvas'] ) ? $request['canvas'] : [];

			$posted_data              = array();
			$posted_data['funnel_id'] = $funnel_id;
			$posted_data['step_id']   = $step_id;
			$posted_data['type']      = $type;
			$posted_data['title']     = $title;
			$data_package             = [];

			if ( is_array( $canvas_data ) && count( $canvas_data ) > 0 ) {
				$canvas_data['type']      = $type;
				$canvas_data['parent_id'] = $step_id;

			}


			BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );

			if ( $funnel_id > 0 && ! empty( $type ) ) {

				$native_checkout = absint( $step_id ) > 0 ? false : true;


				$get_substep = WFFN_Core()->substeps->get_integration_object( $type );
				if ( $get_substep instanceof WFFN_Substep ) {

					if ( true === $native_checkout ) {
						if ( $inherit_id > 0 && '' !== $title ) {
							$posted_data['design']            = $design;
							$posted_data['design_name']['id'] = $inherit_id;
							$posted_data['existing']          = 'true';
							$data                             = $get_substep->add_native_store_substep( $funnel_id, $type, $posted_data );

						} else if ( $duplicate_id > 0 ) {
							$data = $get_substep->duplicate_store_checkout_substep( $funnel_id, '', $type, $duplicate_id, 0, array() );
						} else {
							$data = $get_substep->add_native_store_substep( $funnel_id, $type, $posted_data );
						}

						if ( ! empty( $data ) ) {
							if ( is_array( $data ) ) {
								$bump_id      = $data[ $type ][0]->id;
								$data_package = $get_substep->populate_data_properties( [ 'id' => $data[ $type ][0]->id ], $funnel_id );
								$resp['data'] = $data_package;
							} else {
								$bump_id      = $data->id;
								$data_package = $get_substep->populate_data_properties( [ 'id' => $data->id, 'substeps' => array( $data->type => array( $data->id ) ) ], $funnel_id );
								$resp['data'] = $data_package;
							}

							$global_bumps = WFFN_Common::get_store_checkout_global_substeps( $funnel_id );

							if ( is_array( $global_bumps ) && count( $global_bumps ) > 0 ) {
								/*
								 * Set new insert bump position for canvas mode
								 */
								if ( ! empty( $canvas_data ) ) {
									$insert_after_position = array_search( absint( $canvas_data['insert_after'] ), $global_bumps['wc_order_bump'], true );
									if ( false !== $insert_after_position ) {
										array_splice( $global_bumps['wc_order_bump'], $insert_after_position + 1, 0, $bump_id );
									} else {
										array_push( $global_bumps['wc_order_bump'], $bump_id );
									}
								} else {
									array_push( $global_bumps['wc_order_bump'], $bump_id );
								}
							} else {
								$global_bumps                    = [];
								$global_bumps['wc_order_bump'][] = $bump_id;
							}

							WFFN_Common::update_substeps_store_checkout_meta( $funnel_id, $global_bumps );
							$resp['status'] = true;
						}
					} else {
						if ( $duplicate_id > 0 ) {
							/**
							 * Case of existing order bump delete operation
							 */
							$data         = $get_substep->duplicate_single_substep( $funnel_id, '', $step_id, $type, $duplicate_id, 0, array() );
							$data_package = $get_substep->populate_substeps_data_properties( [ $data[ $type ][0]->id ] )[0];
							$resp['data'] = $data_package;


						} else {
							if ( $inherit_id > 0 && '' !== $title ) {
								/**
								 * Case of inherited from the existing
								 */
								$posted_data['design']            = $design;
								$posted_data['design_name']['id'] = $inherit_id;
								$posted_data['existing']          = 'true';
							}
							/**
							 * Case of new order bump
							 */
							$posted_data['title']                      = $title;
							$data                                      = $get_substep->add_substep( $funnel_id, $step_id, $type, $posted_data );
							$data_package                              = $get_substep->populate_substeps_data_properties( [ $data->id ] )[0];
							$resp['data']                              = $data_package;
							$resp['data']['substeps']                  = [];
							$resp['data']['substeps']['wc_order_bump'] = [ $data_package ];

						}

						if ( ! empty( $data ) ) {
							$resp['status'] = true;
						}
					}

				}
			}

			if ( 'offer' !== $type && ! empty( $data_package ) && ! empty( $canvas_data ) && true === $resp['status'] ) {
				$funnel = new WFFN_Funnel( $funnel_id );

				if ( isset( $data_package['substeps'] ) && isset( $data_package['substeps']['wc_order_bump'] ) && count( $data_package['substeps']['wc_order_bump'] ) > 0 ) {
					$data_package = $data_package['substeps']['wc_order_bump'][0];
				}
				$data_package['type'] = $type;
				$resp['data']         = WFFN_REST_Funnel_Canvas::get_instance()->maybe_canvas_substeps_mode( $canvas_data, $data_package, $funnel );
			}

			return $resp;

		}


		public function delete_substep( $request ) {

			$resp = array(
				'status' => false,
			);

			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel_id = ! empty( $funnel_id ) ? $funnel_id : 0;
			$step_id   = $request->get_param( 'step_id' );
			$step_id   = ! empty( $step_id ) ? $step_id : 0;

			$substep_id = $request->get_param( 'substep_id' );
			$substep_id = ! empty( $substep_id ) ? $substep_id : 0;

			$type = isset( $request['type'] ) ? $request['type'] : '';

			if ( $funnel_id === 0 || $substep_id === 0 ) {
				return $resp;
			}

			if ( $funnel_id > 0 && ! empty( $type ) && $step_id > 0 ) {
				$get_substep = WFFN_Core()->substeps->get_integration_object( $type );
				if ( $get_substep instanceof WFFN_Substep ) {
					$deleted        = $get_substep->delete_substep( $funnel_id, $step_id, $substep_id, $type );
					$resp['status'] = ( $deleted > 0 ) ? true : false;
				}
			} else {
				if ( ! is_null( get_post( $substep_id ) ) ) {
					$deleted = wp_delete_post( $substep_id );
					if ( $deleted ) {
						$global_bumps = WFFN_Common::get_store_checkout_global_substeps( $funnel_id );
						if ( is_array( $global_bumps ) && count( $global_bumps ) > 0 ) {
							foreach ( $global_bumps['wc_order_bump'] as $key => &$bump ) {
								if ( absint( $substep_id ) === absint( $bump ) ) {
									unset( $global_bumps['wc_order_bump'][ $key ] );
								}
							}
							WFFN_Common::update_substeps_store_checkout_meta( $funnel_id, $global_bumps );

						}
						$resp['status'] = true;
					}
				}
			}
			$resp['setup'] = WFFN_REST_Setup::get_instance()->get_status_responses( false );

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

		public function get_create_funnels_collection() {
			$params                   = array();
			$params['template_group'] = array(
				'description'       => __( 'Choose template group.', 'funnel-builder' ),
				'type'              => 'string',
				'enum'              => array( 'gutenberg', 'elementor', 'divi', 'custom' ),
				'sanitize_callback' => 'sanitize_key',
				'validate_callback' => 'rest_validate_request_arg',

			);
			$params['template_type']  = array(
				'description'       => __( 'Choose template type.', 'funnel-builder' ),
				'type'              => 'string',
				'enum'              => array( 'all', 'sales', 'optin' ),
				'sanitize_callback' => 'sanitize_key',
				'validate_callback' => 'rest_validate_request_arg',

			);
			$params['template']       = array(
				'description' => __( 'Choose template.', 'funnel-builder' ),
				'type'        => 'string',

			);
			$params['title']          = array(
				'description' => __( 'Funnel name.', 'funnel-builder' ),
				'type'        => 'string',

			);
			$params['funnel_id']      = array(
				'description' => __( 'Funnel id.', 'funnel-builder' ),
				'type'        => 'integer',
				'default'     => 0,
			);
			$params['step_type']      = array(
				'description'       => __( 'Step type', 'funnel-builder' ),
				'type'              => 'string',
				'validate_callback' => 'rest_validate_request_arg',
				'sanitize_callback' => array( $this, 'sanitize_custom' ),
			);
			$params['default_step']   = array(
				'description' => __( 'Create default steps', 'funnel-builder' ),
				'type'        => 'string',
			);

			return apply_filters( 'wffn_rest_create_funnels_collection', $params );
		}

		public function get_create_substeps_collection() {
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

			return apply_filters( 'wffn_rest_create_substeps_collection', $params );
		}


		public function delete_global_funnel_data() {
			delete_option( '_bwf_global_funnel' );
		}

		public function sanitize_custom( $data ) {

			return json_decode( $data, true );
		}

	}


	if ( ! function_exists( 'wffn_rest_store_checkout' ) ) {

		function wffn_rest_store_checkout() {  //@codingStandardsIgnoreLine
			return WFFN_REST_Store_Checkout::get_instance();
		}
	}

	wffn_rest_store_checkout();
}
