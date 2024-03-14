<?php
/**
 * Nouvello Extented WC API Controller Class
 *
 * @package    Nouvello WeManage Worker
 * @subpackage Core
 * @author     Nouvello Studio
 * @copyright  (c) Copyright by Nouvello Studio
 * @since      1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( ! class_exists( 'Nouvello_Wemanage_Worker_Api_WC_Ext_Controller' ) ) :

	/**
	 * Extented WC API Controller Class.
	 *
	 * @since 1.0
	 */
	final class Nouvello_Wemanage_Worker_Api_WC_Ext_Controller {

		/**
		 * You can extend this class with
		 * WP_REST_Controller / WC_REST_Controller / WC_REST_Products_V2_Controller / WC_REST_CRUD_Controller etc.
		 * Found in packages/woocommerce-rest-api/src/Controllers/
		 */

		/**
		 * The api name space.
		 *
		 * @access protected
		 * @var string
		 */
		protected $namespace = 'wc/v3';

		/**
		 * The api end point route.
		 *
		 * @access protected
		 * @var string
		 */
		protected $rest_base = 'custom';

		/**
		 * Constructor
		 */
		public function __construct() {
			add_filter( 'woocommerce_rest_api_get_rest_namespaces', array( $this, 'ns_wc_api_ext_controller' ) );
		}

		/**
		 * Enable WC API Extension.
		 *
		 * @param  [type] $controllers passed by reference.
		 * @return [type]              [description]
		 */
		public function ns_wc_api_ext_controller( $controllers ) {
			$controllers['wc/v3']['custom'] = 'Nouvello_Wemanage_Worker_Api_WC_Ext_Controller';
			return $controllers;
		}

		/**
		 * Register protected route.
		 */
		public function register_routes() {
			register_rest_route(
				$this->namespace,
				'/' . $this->rest_base,
				array(
					'methods' => 'GET',
					'callback' => array( nouvello_wemanage_worker()->wc_api_functions, 'get_example_data' ),
					'permission_callback' => function( $request ) {
						return is_user_logged_in();
					},
				)
			);

			register_rest_route(
				$this->namespace,
				'categories-hierarchy',
				array(
					'methods'  => 'GET',
					'callback' => array( nouvello_wemanage_worker()->wc_api_functions, 'nouvello_wp_get_categories_hierarchy' ),
					'permission_callback' => function( $request ) {
						return is_user_logged_in();
					},
				)
			);

			register_rest_route(
				$this->namespace,
				'category-html-and-tags-obj',
				array(
					'methods'  => 'GET',
					'callback' => array( nouvello_wemanage_worker()->wc_api_functions, 'nouvello_get_category_html_and_tags_obj' ),
					'permission_callback' => function( $request ) {
						return is_user_logged_in();
					},
				)
			);

			register_rest_route(
				$this->namespace,
				'category-html-and-tags-obj-w-attr-terms-and-currency',
				array(
					'methods'  => 'GET',
					'callback' => array( nouvello_wemanage_worker()->wc_api_functions, 'nouvello_get_category_html_and_tags_obj_w_attr_terms_and_currency' ),
					'permission_callback' => function( $request ) {
						return is_user_logged_in();
					},
				)
			);

			register_rest_route(
				$this->namespace,
				'attributes-with-terms',
				array(
					'methods'  => 'GET',
					'callback' => array( nouvello_wemanage_worker()->wc_api_functions, 'nouvello_get_attributes_with_terms' ),
					'permission_callback' => function( $request ) {
						return is_user_logged_in();
					},
				)
			);

			register_rest_route(
				$this->namespace,
				'shop-stats',
				array(
					'methods'  => 'GET',
					'callback' => array( nouvello_wemanage_worker()->wc_api_functions, 'nouvello_get_shop_stats' ),
					'permission_callback' => function( $request ) {
						return is_user_logged_in();
					},
				)
			);

			register_rest_route(
				$this->namespace,
				'shop-info',
				array(
					'methods'  => 'GET',
					'callback' => array( nouvello_wemanage_worker()->wc_api_functions, 'nouvello_get_shop_info' ),
					'permission_callback' => function( $request ) {
						return is_user_logged_in();
					},
				)
			);

			register_rest_route(
				$this->namespace,
				'email-order',
				array(
					'methods'  => 'GET',
					'callback' => array( nouvello_wemanage_worker()->wc_api_functions, 'nouvello_email_order' ),
					'permission_callback' => function( $request ) {
						return is_user_logged_in();
					},
				)
			);

			register_rest_route(
				$this->namespace,
				'upload-csv-file',
				array(
					'methods'  => 'POST',
					'callback' => array( nouvello_wemanage_worker()->wc_api_functions, 'nouvello_upload_csv_file' ),
					'permission_callback' => function( $request ) {
						return is_user_logged_in();
					},
				)
			);

			register_rest_route(
				$this->namespace,
				'import-csv-data',
				array(
					'methods'  => 'POST',
					'callback' => array( nouvello_wemanage_worker()->wc_api_functions, 'nouvello_import_csv_data' ),
					'permission_callback' => function( $request ) {
						return is_user_logged_in();
					},
				)
			);

		}

	} // end of class

endif; // end if class exist.
