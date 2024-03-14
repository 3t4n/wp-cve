<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Funnels
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Funnels' ) ) {
	#[AllowDynamicProperties]
	class WFFN_REST_Funnels extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'funnels';
		protected $rest_base_id = 'funnels/(?P<funnel_id>[\d]+)/';

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
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_all_funnels' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => array(
						'offset' => array(
							'description'       => __( 'Offset', 'funnel-builder' ),
							'type'              => 'integer',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'limit'  => array(
							'description'       => __( 'Limit', 'funnel-builder' ),
							'type'              => 'integer',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'status' => array(
							'description'       => __( 'Funnel status', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						's'      => array(
							'description'       => __( 'Search funnel', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_funnel' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array_merge( $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ), $this->get_create_funnels_collection() ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_funnel' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'id' => array(
							'description'       => __( 'Delete funnels', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
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
					'callback'            => array( $this, 'get_funnel' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_funnel' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'funnel_id'   => array(
							'description'       => __( 'Funnel ID', 'funnel-builder' ),
							'type'              => 'integer',
							'required'          => true,
							'validate_callback' => 'rest_validate_request_arg',
						),
						'title'       => array(
							'description'       => __( 'title', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'description' => array(
							'description'       => __( 'description', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'steps'       => array(
							'description'       => __( 'steps', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
							'sanitize_callback' => array( $this, 'sanitize_custom' ),
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_funnel' ),
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

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/duplicate/(?P<funnel_id>[\d]+)', array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'duplicate_funnel' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
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

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<funnel_id>[\d]+)/import-status', array(
				'args' => array(
					'funnel_id' => array(
						'description' => __( 'Unique funnel id.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'funnel_import_status' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),

				'schema' => array( $this, 'get_public_item_schema' ),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/get-templates/', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_templates' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => []
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/activate-plugin', array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'activate_plugin' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'status' => array(
							'description'       => __( 'Check plugin status', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'slug'   => array(
							'description'       => __( 'Check plugin slug', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
						'init'   => array(
							'description'       => __( 'Check builder status', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			) );


			register_rest_route( $this->namespace, '/' . $this->rest_base_id . 'publish-all/', array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'publish_all_steps' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => array(
						'funnel_id' => array(
							'description'       => __( 'Funnel ID', 'funnel-builder' ),
							'type'              => 'integer',
							'required'          => true,
							'validate_callback' => 'rest_validate_request_arg',
						),
						'steps'     => array(
							'description'       => __( 'Funnel Steps', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					),
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/fix-tables/', array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'fix_tables' ),
					'permission_callback' => array( $this, 'get_write_api_permission_check' ),
					'args'                => []
				),
			) );

			register_rest_route( 'funnelkit-app', '/migrate-conversion/', array(
				'args'                => [],
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'conversion_migrator_run' ),
				'permission_callback' => array( $this, 'get_write_api_permission_check' ),
			) );

		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_write_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'write' );
		}

		public function get_funnel( WP_REST_Request $request ) {
			$funnel_id = $request->get_param( 'funnel_id' );
			if ( empty( $funnel_id ) ) {
				$funnel_id = isset( $request['id'] ) ? (int) $request['id'] : 0;
			}

			$funnel_data = $this->get_funnel_data( $funnel_id, true );

			if ( $funnel_data === 0 ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}

			return rest_ensure_response( $funnel_data );
		}

		public function get_funnel_data( $funnel_id, $need_step_data = false ) {

			$funnel = new WFFN_Funnel( $funnel_id );

			if ( 0 === $funnel->get_id() ) {
				return 0;
			}

			BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );

			$return = array(
				'id'          => $funnel->get_id(),
				'title'       => $funnel->get_title(),
				'description' => $funnel->get_desc(),
				'count_data'  => array(
					'steps' => $funnel->get_step_count(),
				),
			);

			if ( true === $need_step_data ) {
				$steps = $funnel->get_steps( $need_step_data );
				if ( is_array( $steps ) && count( $steps ) > 0 ) {

					$steps = wffn_rest_api_helpers()->add_step_edit_details( $steps );

					$steps = apply_filters( 'wffn_rest_get_funnel_steps', $steps, $funnel );
					/**
					 *
					 */
					$upsell_step = WFFN_Core()->steps->get_integration_object( 'wc_upsells' );
					if ( $upsell_step instanceof WFFN_Step && method_exists( $upsell_step, 'maybe_migrate_downsells' ) ) {
						WFFN_Core()->steps->get_integration_object( 'wc_upsells' )->maybe_migrate_downsells( $steps );

					}

					$return['steps'] = $steps;
				} else {
					$return['steps'] = [];
				}
			}

			return $return;

		}

		public function create_funnel( $request ) {

			$resp                       = array(
				'status' => false,
				'msg'    => __( 'Funnel creation failed', 'funnel-builder' )
			);
			$funnel_id                  = 0;
			$posted_data                = array();
			$posted_data['funnel_id']   = isset( $request['funnel_id'] ) ? $request['funnel_id'] : 0;
			$posted_data['funnel_name'] = ( isset( $request['funnel_name'] ) && ! empty( $request['funnel_name'] ) ) ? $request['funnel_name'] : '';
			$new_steps                  = ( isset( $request['funnel_steps'] ) && ! empty( $request['funnel_steps'] ) ) ? $request['funnel_steps'] : '';
			$builder                    = ( isset( $request['builder'] ) && ! empty( $request['builder'] ) ) ? $request['builder'] : 'elementor';

			do_action( 'wffn_load_api_import_class' );

			$new_steps = explode( ',', $new_steps );


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
						if ( defined( 'ICL_LANGUAGE_CODE' ) && 'all' !== ICL_LANGUAGE_CODE ) {
							WFFN_Core()->get_dB()->update_meta( $funnel_id, '_lang', ICL_LANGUAGE_CODE );
						}

						$redirect_link = WFFN_Common::get_funnel_edit_link( $funnel_id );
						do_action( 'wffn_funnel_created', $funnel_id, $new_steps );
						$scratch_templates = $this->get_scratch_templates( $funnel_id, $new_steps );
						if ( ! empty( $new_steps ) ) {
							$step_instance = WFFN_REST_Steps::get_instance();
							foreach ( $scratch_templates as $template ) {
								$step_instance->create_step( $template );
							}
						}


						$resp['status']        = true;
						$resp['funnel']        = $funnel;
						$resp['redirect_link'] = $redirect_link;
						$resp['msg']           = __( 'Funnel create successfully 6333', 'funnel-builder' );

					} else {
						$resp['msg'] = __( 'Sorry, we are unable to create funnel due to some technical difficulties. Please contact support', 'funnel-builder' );
					}
				}
			}
			$resp['setup'] = WFFN_REST_Setup::get_instance()->get_status_responses( false );

			return $resp;

		}

		public function get_all_funnels( WP_REST_Request $request ) {
			$result = [
				'status'  => false,
				'message' => __( 'No funnels found', 'funnel-builder' )
			];

			$args             = [];
			$offset           = $request->get_param( 'offset' );
			$status           = $request->get_param( 'status' );
			$limit            = $request->get_param( 'limit' );
			$search           = $request->get_param( 's' );
			$filters          = $request->get_param( 'filters' );
			$search_filter    = $request->get_param( 'search_filter' );
			$need_draft_count = $request->get_param( 'need_draft_count' );
			$need_steps_data  = $request->get_param( 'need_steps_data' );

			if ( isset( $offset ) ) {
				$args['offset'] = $offset;
			}
			if ( isset( $limit ) ) {
				$args['limit'] = $limit;
			}
			if ( isset( $status ) ) {
				$args['status'] = $status;
			}
			if ( isset( $search ) ) {
				$args['s'] = $search;
			}
			if ( isset( $filters ) ) {
				$args['filters'] = $filters;
			}
			if ( isset( $filters ) ) {
				$args['filters'] = $filters;
			}
			if ( ! empty( $need_steps_data ) ) {
				$args['need_steps_data'] = 'yes';
			}
			/**
			 * parameter use for search funnel in filter screen
			 * and change api response base on this
			 */
			if ( isset( $search_filter ) ) {
				if ( isset( $args['s'] ) && empty( $args['s'] ) ) {
					$args['limit'] = 5;
				}
				$args['search_filter'] = $search_filter;
			} else {
				if ( isset( $need_draft_count ) ) {
					$args['need_draft_count'] = $need_draft_count;
				}
				$args['meta'] = array( 'key' => '_is_global', 'compare' => 'NOT_EXISTS' );
			}

			$args['context'] = 'listing';
			$funnels         = WFFN_Core()->admin->get_funnels( $args );


			if ( is_array( $funnels ) && isset( $search_filter ) ) {
				return rest_ensure_response( $funnels );
			}


			if ( is_array( $funnels ) && isset( $funnels['items'] ) && $funnels['items'] > 0 ) {
				$result           = $funnels;
				$result['status'] = true;

				if ( isset( $offset ) ) {
					$result['offset'] = $offset;
				}
				if ( isset( $limit ) ) {
					$result['limit'] = $limit;
				}
			}

			return rest_ensure_response( $result );
		}

		public function export_funnels( WP_REST_Request $request ) {

			$result = [
				'status'  => false,
				'message' => __( 'Something went wrong. Please try again', 'funnel-builder' )
			];

			do_action( 'wffn_load_api_export_class' );
			$funnels = [ 'items' => [] ];
			$ids     = $request->get_param( 'ids' );
			$ids     = ! is_null( $ids ) ? explode( ',', $ids ) : [];

			if ( ! empty( $ids ) ) {
				foreach ( $ids as $funnel_id ) {
					$funnel = WFFN_Core()->admin->get_funnel( (int) $funnel_id );
					if ( ! $funnel instanceof WFFN_Funnel ) {
						continue;
					}
					$funnels['items'][] = array(
						'id'         => $funnel->get_id(),
						'title'      => $funnel->get_title(),
						'desc'       => $funnel->get_desc(),
						'date_added' => $funnel->get_date_added(),
						'steps'      => $funnel->get_steps( true ),
						'__funnel'   => $funnel,
					);
				}
			} else {
				$funnels = WFFN_Core()->admin->get_funnels();
			}

			if ( ! isset( $funnels['items'] ) || empty( $funnels['items'] ) ) {
				return rest_ensure_response( $result );
			}

			$funnels_to_export = [];
			foreach ( $funnels['items'] as $key => $funnel ) {
				/**
				 * @var $get_funnel WFFN_Funnel
				 */

				$get_funnel                = $funnel['__funnel'];
				$funnels_to_export[ $key ] = [ 'title' => $get_funnel->get_title(), 'steps' => [] ];
				$steps                     = $get_funnel->get_steps( true );

				foreach ( $steps as $k => $step ) {
					$funnels_to_export[ $key ]['steps'][ $k ] = WFFN_Core()->steps->get_integration_object( $step['type'] )->get_export_data( $step );
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
				WFFN_Core()->import->import_from_json_data( $funnels );

				$result = [
					'setup'  => WFFN_REST_Setup::get_instance()->get_status_responses( false ),
					'status' => true,
				];
			} else {
				$result = [
					'message' => __( 'Error: Invalid File Format. Please contact support.', 'funnel-builder' ),
					'status'  => false,
				];
			}


			return rest_ensure_response( $result );
		}

		public function duplicate_funnel( $request, $return = false ) {
			$resp = array(
				'status'    => false,
				'funnel_id' => 0,
			);

			$funnel_id                   = $request['funnel_id'];
			$is_clone                    = $request['is_clone'] ?? false;
			$is_store_checkout_duplicate = $request['is_store_checkout'] ?? false;
			if ( is_null( $funnel_id ) ) {
				return rest_ensure_response( $resp );
			}

			$new_funnel = WFFN_Core()->admin->get_funnel();
			$funnel     = WFFN_Core()->admin->get_funnel( $funnel_id );

			if ( ! $new_funnel instanceof WFFN_Funnel ) {
				return true === $return ? $resp : rest_ensure_response( $resp );
			}

			$title_postfix = ( false === $is_clone ) ? ' - ' . __( 'Copy' ) : '';
			$new_funnel_id = $new_funnel->add_funnel( array(
				'title'  => $funnel->get_title() . $title_postfix,
				'desc'   => $funnel->get_desc(),
				'status' => 1,
			) );

			do_action( 'wffn_duplicate_funnel', $new_funnel, $funnel );

			if ( $new_funnel_id > 0 ) {
				if ( false !== $is_store_checkout_duplicate ) {
					$funnel->steps = array_filter( $funnel->steps, function ( $item ) {
						return ! in_array( $item['type'], [ 'landing', 'optin', 'optin_ty' ], true );
					} );
				}
				if ( isset( $funnel->steps ) && is_array( $funnel->steps ) ) {
					foreach ( $funnel->steps as $steps ) {
						$type        = $steps['type'];
						$step_id     = $steps['id'];
						$posted_data = array( 'duplicate_funnel_id' => $funnel_id );

						BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $new_funnel_id );
						if ( ! empty( $type ) ) {
							$get_step = WFFN_Core()->steps->get_integration_object( $type );
							if ( $get_step instanceof WFFN_Step ) {
								$posted_data['original_id']     = $step_id;
								$posted_data['step_id']         = $step_id;
								$posted_data['_data']           = [];
								$posted_data['_data']['title']  = $get_step->get_entity_title( $step_id );
								$posted_data['_data']['desc']   = $get_step->get_entity_description( $step_id );
								$posted_data['_data']['status'] = $get_step->get_entity_status( $step_id );
								$posted_data['_data']['edit']   = $get_step->get_entity_edit_link( $step_id );
								$posted_data['_data']['view']   = $get_step->get_entity_view_link( $step_id );
								$get_step->duplicate_step( $new_funnel_id, $step_id, $posted_data );
							}
						}
					}
				}

				$excluded_meta = array( '_is_global', '_last_updated_on' );
				$all_meta      = WFFN_Core()->get_dB()->get_meta( $funnel_id );
				if ( is_array( $all_meta ) && count( $all_meta ) > 0 ) {
					foreach ( $all_meta as $key => $meta ) {
						if ( in_array( $key, $excluded_meta, true ) ) {
							continue;
						}
						WFFN_Core()->get_dB()->update_meta( $new_funnel_id, $key, maybe_unserialize( $meta[0] ) );
					}
				}
				$resp['funnel_id'] = $new_funnel_id;
				$resp['status']    = true;
			}


			return true === $return ? $resp : rest_ensure_response( $resp );
		}

		public function publish_all_steps( $request ) {
			$result = [
				'status'  => false,
				'message' => __( 'Something went wrong. Please try again', 'funnel-builder' )
			];

			$id = $request->get_param( 'funnel_id' );
			if ( is_null( $id ) ) {
				return rest_ensure_response( $result );
			}
			$steps = $request->get_param( 'steps' );
			$steps = json_decode( $steps, 'true' );
			if ( ! is_array( $steps ) || empty( $steps ) ) {
				return rest_ensure_response( $result );
			}

			foreach ( $steps as $index => $step ) {
				$step_type = $step['type'];
				if ( 'wc_checkout' === $step_type ) {
					WFACP_Common::save_publish_checkout_pages_in_transient();
				}
				$is_updated = wp_update_post( [ 'ID' => $step['id'], 'post_status' => 'publish' ] );
				if ( ! $is_updated instanceof WP_Error ) {
					$steps[ $index ]['status'] = 'updated';
				}
			}
			$result = [
				'status' => true,
				'steps'  => $steps
			];

			return rest_ensure_response( $result );

		}

		public function delete_funnel( $request ) {
			$result = [
				'status'  => false,
				'message' => __( 'Something went wrong. Please try again', 'funnel-builder' )
			];

			$ids = $request->get_param( 'id' );
			if ( is_null( $ids ) ) {
				return rest_ensure_response( $result );
			}
			$funnel_ids = explode( ',', $ids );
			foreach ( $funnel_ids as $funnel_id ) {
				$funnel  = WFFN_Core()->admin->get_funnel( $funnel_id );
				$deleted = $funnel->delete();
				if ( ! $deleted ) {
					return rest_ensure_response( $result );
				}
			}

			$result = [
				'status' => true,
				'setup'  => WFFN_REST_Setup::get_instance()->get_status_responses( false ),
			];

			return rest_ensure_response( $result );
		}

		public function update_funnel( WP_REST_Request $request ) {

			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel    = new WFFN_Funnel( $funnel_id );

			if ( $funnel->get_id() === 0 ) {
				return new WP_Error( 'woofunnels_rest_funnel_not_exists', __( 'Invalid funnel ID.', 'funnel-builder' ), array( 'status' => 404 ) );
			}

			$title = $request->get_param( 'title' );
			if ( $title ) {
				$funnel->set_title( $title );
			}

			$description = $request->get_param( 'description' );
			if ( ! empty( $description ) ) {
				$funnel->set_desc( $description );
			} else {
				$funnel->set_desc( '' );
			}
			BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );

			/**
			 * Handle steps reordering
			 */
			$steps = $request->get_param( 'steps' );
			if ( $steps ) {
				$native_key = array_search( WFFN_Common::store_native_checkout_slug(), wp_list_pluck( $steps, 'type' ), true );
				if ( false !== $native_key ) {
					unset( $steps[ $native_key ] );
				}
				$funnel->reposition_steps( $steps );
			}
			$funnel->save();
			$return = array(
				'id'          => $funnel->get_id(),
				'title'       => $funnel->get_title(),
				'description' => $funnel->get_desc(),
				'link'        => $funnel->get_view_link(),
				'steps'       => $funnel->get_steps( true )
			);

			return rest_ensure_response( $return );
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

			$resp = [];

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

		public function funnel_import_status( WP_REST_Request $request ) {

			$resp = array(
				'status' => false,
			);

			if ( ! function_exists( 'post_exists' ) ) {
				require_once ABSPATH . 'wp-admin/includes/post.php';
			}

			if ( ! function_exists( 'media_handle_sideload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			$funnel_id = $request->get_param( 'funnel_id' );
			$funnel_id = ! empty( $funnel_id ) ? $funnel_id : 0;

			if ( $funnel_id === 0 ) {
				return $resp;
			}

			$funnel_id_db = get_option( '_wffn_scheduled_funnel_id', 0 );
			if ( $funnel_id_db > 0 ) {
				BWF_Logger::get_instance()->log( sprintf( 'Background template importer for funnel id %d is started in get_import_status', $funnel_id ), 'wffn_template_import' );
				WFFN_Core()->admin->wffn_updater->trigger();
				$resp['success'] = false;
			} else {


				$funnel = new WFFN_Funnel( $funnel_id );


				if ( ! wffn_is_valid_funnel( $funnel ) ) {
					$resp['success'] = false;

					return $resp;

				}
				$funnel_steps      = $funnel->get_steps();
				$has_any_scheduled = 0;
				foreach ( $funnel_steps as $funnel_step ) {
					$get_object = WFFN_Core()->steps->get_integration_object( $funnel_step['type'] );
					if ( ! empty( $get_object ) ) {
						$has_scheduled = $get_object->has_import_scheduled( $funnel_step['id'] );
						if ( is_array( $has_scheduled ) ) {
							$has_any_scheduled ++;

						}
					}
				}

				if ( 0 < $has_any_scheduled ) {
					$resp['success'] = false;
				} else {
					$redirect_url = WFFN_Common::get_funnel_edit_link( $funnel_id );

					$resp['status']   = true;
					$resp['redirect'] = $redirect_url;
				}


			}


			return $resp;
		}

		public function get_all_builders() {
			return array(
				'funnel'      => [
					'elementor' => 'Elementor',
					'gutenberg' => 'Block Editor',
					'divi'      => 'Divi',
					'oxy'       => 'Oxygen',
					'wp_editor' => __( 'Other', 'funnel-builder' ),
				],
				'landing'     => [
					'elementor' => 'Elementor',
					'gutenberg' => 'Block Editor',
					'divi'      => 'Divi',
					'oxy'       => 'Oxygen',
					'wp_editor' => __( 'Other', 'funnel-builder' ),
				],
				'optin'       => [
					'elementor' => 'Elementor',
					'gutenberg' => 'Block Editor',
					'divi'      => 'Divi',
					'oxy'       => 'Oxygen',
					'wp_editor' => __( 'Other (Using Shortcodes)', 'funnel-builder' ),
				],
				'optin_ty'    => [
					'elementor' => 'Elementor',
					'gutenberg' => 'Block Editor',
					'divi'      => 'Divi',
					'oxy'       => 'Oxygen',
					'wp_editor' => __( 'Other (Using Shortcodes)', 'funnel-builder' ),
				],
				'wc_thankyou' => [
					'elementor' => 'Elementor',
					'gutenberg' => 'Block Editor',
					'divi'      => 'Divi',
					'oxy'       => 'Oxygen',
					'wp_editor' => __( 'Other (Using Shortcodes)', 'funnel-builder' ),
				],
				'wfob'        => [
					'elementor'  => 'Elementor',
					'gutenberg'  => 'Block Editor',
					'divi'       => 'Divi',
					'oxy'        => 'Oxygen',
					'customizer' => 'Customizer', //pre_built
					'wp_editor'  => __( 'Other (Using Shortcodes)', 'funnel-builder' ),
				],
				'wc_checkout' => [
					'elementor'  => 'Elementor',
					'gutenberg'  => 'Block Editor',
					'divi'       => 'Divi',
					'oxy'        => 'Oxygen',
					'customizer' => 'Customizer', //pre_built
					'wp_editor'  => __( 'Other (Using Shortcodes)', 'funnel-builder' ),
				],
				'upsell'      => [
					'elementor'  => 'Elementor',
					'gutenberg'  => 'Block Editor',
					'divi'       => 'Divi',
					'oxy'        => 'Oxygen',
					'customizer' => 'Customizer',
					'wp_editor'  => __( 'Other (Using Shortcodes)', 'funnel-builder' ),
				]
			);
		}

		public function get_templates() {
			$resp = array();

			$resp['all_builder'] = $this->get_all_builders();

			$resp['sub_filter_group'] = array(
				'funnel'      => [
					'all'   => __( 'All', 'funnel-builder' ),
					'sales' => __( 'Sales Funnels', 'funnel-builder' ),
					'optin' => __( 'Optin Funnels', 'funnel-builder' )
				],
				'landing'     => [
					'all' => __( 'All', 'funnel-builder' )
				],
				'optin'       => [
					'inline' => __( 'Inline', 'funnel-builder' ),
					'popup'  => __( 'Popup', 'funnel-builder' )
				],
				'wc_thankyou' => [
					'all' => __( 'All', 'funnel-builder' )
				],
				'wc_checkout' => [
					'1' => __( 'One Step', 'funnel-builder' ),
					'2' => __( 'Two Step', 'funnel-builder' ),
					'3' => __( 'Three Step', 'funnel-builder' )
				],
				'upsell'      => [
					'all' => __( 'All', 'funnel-builder' )
				]
			);

			do_action( 'wffn_rest_before_get_templates' );
			$general_settings        = BWF_Admin_General_Settings::get_instance();
			$default_builder         = $general_settings->get_option( 'default_selected_builder' );
			$resp['default_builder'] = ( ! empty( $default_builder ) ) ? $default_builder : 'elementor';

			$templates = WooFunnels_Dashboard::get_all_templates();
			$json_data = isset( $templates['funnel'] ) ? $templates['funnel'] : [];

			if ( empty( $json_data ) || isset( $json_data['divi']['divi_funnel_1']['import_button_text'] ) ) {
				$templates = WooFunnels_Dashboard::get_all_templates( true );
				$json_data = isset( $templates['funnel'] ) ? $templates['funnel'] : [];
			}

			foreach ( $templates as $_t => $_template ) {
				$wp_editor                     = [
					'wp_editor_1' => [
						'type'               => 'view',
						'import'             => 'no',
						'show_import_popup'  => 'no',
						'import_button_text' => 'import',
						'slug'               => 'wp_editor_1',
						'build_from_scratch' => true
					]
				];
				$templates[ $_t ]['wp_editor'] = $wp_editor;

				if ( 'wc_checkout' === $_t ) {
					foreach ( $_template as $tk => $tmpl ) {
						foreach ( $tmpl as $tpk => $_tmpl ) {
							if ( ! empty( $_tmpl['no_steps'] ) ) {
								$templates[ $_t ][ $tk ][ $tpk ]['type'] = 'wc_checkout';
							}
						}
					}
				}

				if ( 'upsell' === $_t ) {
					foreach ( $_template as $tk => $tmpl ) {

						foreach ( $tmpl as $tpk => $_tmpl ) {
							if ( empty( $_tmpl['slug'] ) ) {
								// Save key as Slug
								$_tmpl['slug']                           = $tpk;
								$templates[ $_t ][ $tk ][ $tpk ]['slug'] = $tpk;
							}
						}
					}
				}


			}


			$templates['funnel'] = $json_data;
			if ( is_array( $templates ) && count( $templates ) > 0 ) {
				$templates = $this->add_default_template_list( $templates );
				// Add wp_editor_1 to slug
				$templates['landing']['wp_editor']['wp_editor_1']['slug'] = 'wp_editor_1';
				$resp['templates']                                        = apply_filters( 'wffn_rest_get_templates', $templates );

				$resp['templates']['upsell']['wp_editor']                       = [];
				$resp['templates']['upsell']['wp_editor']['wfocu-custom-empty'] = [ 'name' => '', 'slug' => 'wfocu-custom-empty', 'build_from_scratch' => true ];

			}

			return $resp;
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

			return apply_filters( 'wffn_rest_create_funnels_collection', $params );
		}

		public function sanitize_custom( $data ) {

			return json_decode( $data, true );
		}


		public function activate_plugin( $request ) {

			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$resp = array(
				'status' => false,
				'msg'    => __( 'No builder found', 'funnel-builder' )
			);

			$plugin_init   = $request->get_param( 'init' );
			$plugin_slug   = $request->get_param( 'slug' );
			$plugin_status = $request->get_param( 'status' );

			$plugin_init   = isset( $plugin_init ) ? $plugin_init : '';
			$plugin_slug   = isset( $plugin_slug ) ? $plugin_slug : '';
			$plugin_status = isset( $plugin_status ) ? $plugin_status : '';

			if ( $plugin_init === '' ) {
				return rest_ensure_response( $resp );
			}

			if ( 'current' === $plugin_status ) {
				$plugin_active = WFFN_Common::get_plugin_status( $plugin_init );

				$resp = array(
					'success'       => true,
					'plugin_status' => $plugin_active,
					'init'          => $plugin_init,
				);

				if ( 'wp-marketing-automations/wp-marketing-automations.php' === $plugin_init && $plugin_active === 'activated' ) {
					$woocommerce_active = $new_order_automation = $cart_abandoned_automation = $any_automation = false;
					if ( ( function_exists( 'wfocu_is_woocommerce_active' ) && wfocu_is_woocommerce_active() ) || ( function_exists( 'wfacp_is_woocommerce_active' ) && wfacp_is_woocommerce_active() ) ) {
						$woocommerce_active = true;
					}
					$automation_status = $this->check_for_automation_exists();
					if ( in_array( 'wc_new_order', $automation_status, true ) ) {
						$new_order_automation = true;
					}
					if ( in_array( 'ab_cart_abandoned', $automation_status, true ) ) {
						$cart_abandoned_automation = true;
					}

					$first_automation_id = BWFAN_Model_Automations::get_first_automation_id();
					if ( intval( $first_automation_id ) > 0 ) {
						$any_automation = true;
					}

					$resp['new_order_automation']      = $new_order_automation;
					$resp['cart_abandoned_automation'] = $cart_abandoned_automation;
					$resp['woocommerce_status']        = $woocommerce_active;
					$resp['any_automation']            = $any_automation;
				}

				return rest_ensure_response( $resp );
			}

			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			if ( $plugin_status === 'install' && $plugin_slug !== '' ) {

				$install_plugin = WFFN_Common::install_plugin( $plugin_slug );
				if ( isset( $install_plugin['status'] ) && $install_plugin['status'] === false ) {
					return rest_ensure_response( $install_plugin );
				}
			}

			$activate = activate_plugin( $plugin_init, '', false, true );

			if ( is_wp_error( $activate ) ) {
				$resp = array(
					'success' => false,
					'message' => $activate->get_error_message(),
					'init'    => $plugin_init,
				);
			} else {
				$resp = array(
					'success' => true,
					'init'    => $plugin_init,
				);
			}

			return rest_ensure_response( $resp );
		}

		/** Checks for automation active status */
		public function check_for_automation_exists() {
			global $wpdb;
			$result       = $wpdb->get_results( $wpdb->prepare( 'SELECT `event` FROM %1$s WHERE `event` IN ("wc_new_order", "ab_cart_abandoned") GROUP BY `event`', $wpdb->prefix . "bwfan_automations" ), ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder,WordPress.DB.PreparedSQL.NotPrepared
			$active_event = [];
			foreach ( $result as $event ) {
				$active_event[] = $event['event'];
			}

			return $active_event;
		}


		/**
		 *Add wp editor template in template list
		 *
		 *
		 * @param $templates
		 *
		 * @return array
		 */
		public function add_default_template_list( $templates ) {
			$default_template = array(
				'wp_editor_1' => array(
					'template_active'    => 'no',
					'build_from_scratch' => true,
					'name'               => __( 'Start from scratch', 'funnel-builder' )
				)
			);

			if ( isset( $templates['landing'] ) ) {
				$templates['landing']['wp_editor'] = $default_template;
			}
			if ( isset( $templates['optin'] ) ) {
				$templates['landing']['wp_editor'] = $default_template;
			}
			if ( isset( $templates['optin_ty'] ) ) {
				$templates['landing']['wp_editor'] = $default_template;
			}
			if ( isset( $templates['wc_thankyou'] ) ) {
				$templates['landing']['wp_editor'] = $default_template;
			}


			if ( isset( $templates['upsell']['customizer'] ) ) {
				$all_customizer = $templates['upsell']['customizer'];
				$templates['upsell']['customizer'] = [];
				$templates['upsell']['customizer']['customizer-empty'] = [ 'name' => '', 'slug' => 'customizer-empty', 'build_from_scratch' => true, 'allow_new' => false ];
				$templates['upsell']['customizer'] = array_merge( $templates['upsell']['customizer'],$all_customizer );
			}



			return $templates;
		}

		public function get_scratch_templates( $funnel_id, $steps ) {
			if ( empty( $steps ) ) {
				return [];
			}
			$data = array(
				"landing"     => array(

					"funnel_id" => $funnel_id,
					"type"      => "landing",
					"title"     => __( 'Landing', 'funnel-builder' ),
				),
				"wc_checkout" => array(
					"funnel_id" => $funnel_id,
					"type"      => "wc_checkout",
					"title"     => __( 'Checkout', 'funnel-builder' ),
				),
				"upsell"      => array(
					"funnel_id"   => $funnel_id,
					"type"        => "upsell",
					"title"       => __( 'Upsell', 'funnel-builder' ),
					"offer_title" => __( 'Offer', 'funnel-builder' )
				),
				"wc_thankyou" => array(
					"funnel_id" => $funnel_id,
					"type"      => "wc_thankyou",
					"title"     => __( 'Thankyou', 'funnel-builder' ),
				),
				"optin"       => array(
					"funnel_id" => $funnel_id,
					"type"      => "optin",
					"title"     => __( 'Optin', 'funnel-builder' ),
				),
				"optin_ty"    => array(
					"funnel_id" => $funnel_id,
					"type"      => "optin_ty",
					"title"     => __( 'Optin Confirmation', 'funnel-builder' ),
				)
			);

			return array_filter( $data, function ( $key ) use ( $steps ) {
				return in_array( $key, $steps, true );
			}, ARRAY_FILTER_USE_KEY );

		}

		/**
		 * Delete All DB option then run table create command
		 * @return WP_REST_Response|WP_Error
		 */
		public function fix_tables() {

			delete_option( '_wfocu_db_version' );
			delete_option( '_wffn_db_version' );
			delete_option( '_wfopp_db_version' );
			delete_option( '_bwf_db_version' );
			delete_option( 'wfco_as_table_created_v2' );
			delete_option( 'wfco_v1_0' );
			delete_option( 'wfob_db_ver_3_0' );
			delete_option( 'wfacp_dynamic_update' );
			delete_option( 'wfacp_db_update' );
			delete_option( 'wfacp_db_ver_2_0' );
			delete_option( '_bwf_db_table_list' );

			return rest_ensure_response( [ 'status' => true ] );
		}

		public function conversion_migrator_run() {
			if ( ! class_exists( 'WFFN_Conversion_Tracking_Migrator' ) ) {
				return rest_ensure_response( [ 'success' => false ] );
			}

			wffn_conversion_tracking_migrator()->push_to_queue( 'wffn_run_conversion_migrator' );
			wffn_conversion_tracking_migrator()->set_upgrade_state( 2 );
			wffn_conversion_tracking_migrator()->dispatch();
			wffn_conversion_tracking_migrator()->save();

			return rest_ensure_response( [ 'success' => true ] );

		}


	}


	if ( ! function_exists( 'wffn_rest_funnels' ) ) {

		function wffn_rest_funnels() {  //@codingStandardsIgnoreLine
			return WFFN_REST_Funnels::get_instance();
		}
	}

	wffn_rest_funnels();
}
