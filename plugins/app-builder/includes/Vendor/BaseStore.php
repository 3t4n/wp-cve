<?php


/**
 * class BaseStore
 *
 * @link       https://appcheap.io
 * @since      2.5.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Vendor;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_REST_Response;
use WP_REST_Server;

class BaseStore extends StorePermission {
	public function __construct() {
		$this->namespace = APP_BUILDER_REST_BASE . '/v1';
		$this->rest_base = 'vendors';
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace, '/' . $this->rest_base, [
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_stores' ],
					'args'                => $this->get_collection_params(),
					'permission_callback' => '__return_true',
				],
			]
		);

		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/categories', [
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_store_categories' ],
					'args'                => $this->get_collection_params(),
					'permission_callback' => '__return_true',
				],
			]
		);

		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/sales-by-product', [
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_sales_by_product' ],
					'permission_callback' => array( $this, 'vendor_permissions_check' ),
				],
			]
		);

		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/store-analytics', [
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_store_analytics' ],
					'permission_callback' => array( $this, 'vendor_permissions_check' ),
				],
			]
		);

		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/reports-sales-by-date', [
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_reports_sales_by_date' ],
					'permission_callback' => array( $this, 'vendor_permissions_check' ),
				],
			]
		);

		register_rest_route( $this->namespace, '/' . $this->rest_base . '/settings', [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_settings' ),
				'permission_callback' => array( $this, 'vendor_permissions_check' ),
			],
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'set_settings' ),
				'permission_callback' => array( $this, 'vendor_permissions_check' ),
			]
		] );

		register_rest_route( $this->namespace, '/' . $this->rest_base . '/profile', [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_profile' ),
				'permission_callback' => array( $this, 'vendor_permissions_check' ),
			],
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'set_profile' ),
				'permission_callback' => array( $this, 'vendor_permissions_check' ),
			]
		] );

		register_rest_route( $this->namespace, '/' . $this->rest_base . '/sales-stats', [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_sales_stats' ),
				'permission_callback' => array( $this, 'vendor_permissions_check' ),
				'args'                => $this->get_collection_params(),
			],
		] );

		/**
		 * Store review
		 */
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/reviews', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_store_reviews' ),
				'permission_callback' => array( $this, 'get_review_permissions_check' ),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );

		register_rest_route( $this->namespace, '/' . $this->rest_base . '/reviews/(?P<id>[\d]+)/', array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the object.', 'wcfm-marketplace-rest-api' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'set_store_review_status' ),
					'permission_callback' => array( $this, 'review_manage_permissions_check' ),
				)
			)
		);

		/**
		 * Get count notification
		 */
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/count-notifications', [
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_notification' ),
					'permission_callback' => array( $this, 'vendor_permissions_check' ),
				]
			]
		);

		/**
		 * Message
		 */
		/**
		 * Get count notification
		 */
		register_rest_route( $this->namespace, '/' . $this->rest_base . '/messages-mark-read', [
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'messages_mark_read' ),
					'permission_callback' => array( $this, 'mark_read_message' ),
				]
			]
		);

		register_rest_route( $this->namespace, '/' . $this->rest_base . '/messages-delete', [
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'messages_delete' ),
					'permission_callback' => array( $this, 'mark_read_message' ),
				]
			]
		);
	}

	/**
	 * Get stores
	 *
	 * @param $request
	 *
	 * @return object|WP_Error|WP_REST_Response
	 * @since 2.5.0
	 *
	 */
	public function get_stores( $request ) {
		return rest_ensure_response( [] );
	}

	/**
	 * Get store categories
	 *
	 * @param $request
	 *
	 * @return object|WP_Error|WP_REST_Response
	 * @since 2.5.0
	 *
	 */
	public function get_store_categories( $request ) {
		return rest_ensure_response( [] );
	}

	/**
	 * @param $request
	 *
	 * @return WP_Error|\WP_HTTP_Response|WP_REST_Response
	 */
	public function get_sales_by_product( $request ) {
		return rest_ensure_response( array(
			"labels" => [],
			"datas"  => []
		) );
	}

	/**
	 * @param $request
	 *
	 * @return WP_Error|\WP_HTTP_Response|WP_REST_Response
	 */
	public function get_store_analytics( $request ) {
		return rest_ensure_response( array(
			"labels" => [],
			"datas"  => []
		) );
	}

	/**
	 * @param $request
	 *
	 * @return WP_Error|\WP_HTTP_Response|WP_REST_Response
	 */
	public function get_reports_sales_by_date( $request ) {

		$empty = array(
			"labels" => [],
			"datas"  => []
		);

		return rest_ensure_response( array(
			'order_counts'            => $empty,
			'order_item_counts'       => $empty,
			'tax_amounts'             => $empty,
			'shipping_amounts'        => $empty,
			'total_earned_commission' => $empty,
			'total_paid_commission'   => $empty,
			'total_gross_sales'       => $empty,
			'total_refund'            => $empty,
		) );
	}

	/**
	 *
	 * Get store settings
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_settings( $request ) {
		return rest_ensure_response( array() );
	}

	/**
	 *
	 * Set store settings
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function set_settings( $request ) {
		return rest_ensure_response( array() );
	}

	/**
	 *
	 * Get store profile
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_profile( $request ) {
		return rest_ensure_response( array() );
	}

	/**
	 *
	 * Set store profile
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function set_profile( $request ) {
		return rest_ensure_response( array() );
	}

	/**
	 *
	 * Get sales stats
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function get_sales_stats( $request ) {
		return rest_ensure_response( array() );
	}

	/**
	 *
	 * Get reviews
	 *
	 * @param $request
	 *
	 * @return WP_Error|\WP_HTTP_Response|WP_REST_Response
	 */
	public function get_store_reviews( $request ) {
		return rest_ensure_response( array() );
	}

	/**
	 *
	 * Update review status
	 *
	 * @param $request
	 *
	 * @return WP_Error|\WP_HTTP_Response|WP_REST_Response
	 */
	public function set_store_review_status( $request ) {
		return rest_ensure_response( array( 'success' => false ) );
	}

	/**
	 *
	 * Get notifications
	 *
	 * @param $request
	 *
	 * @return WP_Error|\WP_HTTP_Response|WP_REST_Response
	 */
	public function get_notification( $request ) {
		return rest_ensure_response( array(
			"notice"  => 0,
			"message" => 0,
			"enquiry" => 0,
		) );
	}

	/**
	 * Handle Message mark as Read
	 *
	 * @since 1.0.0
	 */
	public function messages_mark_read( $request ) {
		return rest_ensure_response( array( 'status' => false ) );
	}

	/**
	 * Handle Message Delete
	 *
	 * @since 1.0.0
	 */
	function messages_delete( $request ) {
		return rest_ensure_response( array( 'status' => false ) );
	}

	public function get_attachment( $attachment_id, $size = 'full' ) {
		if ( is_numeric( $attachment_id ) && $attachment_id > 0 ) {
			$attachment = wp_get_attachment_image_src( $attachment_id, $size );

			return current( $attachment );
		}

		return '';
	}
}
