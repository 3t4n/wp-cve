<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WFFN_REST_Recipes
 *
 * * @extends WP_REST_Controller
 */
if ( ! class_exists( 'WFFN_REST_Recipes' ) ) {
	#[AllowDynamicProperties]

  class WFFN_REST_Recipes extends WP_REST_Controller {

		public static $_instance = null;

		/**
		 * Route base.
		 *
		 * @var string
		 */

		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'automation';
        protected $response_code = 200;
        protected $total_count = 0;

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

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/recipes', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_all_recipes' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base, array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_automation_data' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [],
				),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/recipe/', array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_selected_recipe' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),
					'args'                => [
						'recipe_slug' => array(
							'description'       => __( 'Recipe Slug', 'funnel-builder' ),
							'type'              => 'string',
							'validate_callback' => 'rest_validate_request_arg',
						),
					],
				),
			) );
		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function get_automation_data() {
			$count = 0;
			$version = defined( 'BWFAN_VERSION' ) ? BWFAN_VERSION : '';
			if ( class_exists( 'BWFAN_Model_Automations' ) ) {
				$count = BWFAN_Model_Automations::count_rows();
			}

			$this->response_code = 200;
            $this->total_count   = $count;

		    return $this->success_response( [ 'count' => $count, 'version' => $version ], __( 'Automation Data.', 'funnel-builder' ) );
		}

		public function get_selected_recipe( WP_REST_Request $request ) {
			$recipe_slug   = $request->get_param( 'recipe_slug' );
			if ( empty( $recipe_slug ) ) {
				return $this->error_response( __( 'Invalid / Empty automation ID provided', 'funnel-builder' ), null, 400 );
			}

			/** Fetch Recipe data */
			$recipe_data = $this->get_recipe_remotely( $recipe_slug );
			if ( empty( $recipe_data ) ) {
				return $this->error_response( __( 'Recipe not found.', 'funnel-builder' ), null, 400 );
			}

			$this->response_code = 200;

			return $this->success_response( $recipe_data, ! empty( $automation_data['message'] ) ? $automation_data['message'] : '' );
		}

		/**
		 * @param $slug recipe slug
		 *
		 * @return void
		 */
		public function get_recipe_remotely( $slug ) {
			$request = wp_remote_get( "https://app.getautonami.com/recipe/$slug" );

			if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
				return false;
			}
			$data = wp_remote_retrieve_body( $request );

			if ( isset( $data['error'] ) ) {
				return false;
			}
			return json_decode($data, true);

		}

		public function get_all_recipes( WP_REST_Request $request ) {
            $all_recipes         = WFFN_Recipe_Loader::get_recipes_array();
            $this->response_code = 200;
            $this->total_count   = is_array( $all_recipes ) ? count( $all_recipes ) : 0;

		    return $this->success_response( $all_recipes, __( 'Got all recipes.', 'funnel-builder' ) );

		}

        public function success_response( $result_array, $message = '' ) {
            $response = WFFN_Common::format_success_response( $result_array, $message, $this->response_code );
            $response['total_count'] = $this->total_count;
            return rest_ensure_response( $response );
        }

		public function error_response( $message = '', $wp_error = null, $code = 0 ) {
			if ( 0 !== absint( $code ) ) {
				$this->response_code = $code;
			}
	
			$data = array();
			if ( $wp_error instanceof WP_Error ) {
				$message = $wp_error->get_error_message();
				$data    = $wp_error->get_error_data();
			}
	
			return new WP_Error( $this->response_code, $message, array( 'status' => $this->response_code, 'error_data' => $data ) );
		}


	}


}

return WFFN_REST_Recipes::get_instance();