<?php
/**
 * Ajax
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp;

use Dropp\Actions\Get_Shipping_Instance_Data_Action;
use Dropp\Data\Price_Info_Data;
use Dropp\Utility\Admin_Notice_Utility;
use WC_Order_Item_Shipping;
use WC_Cache_Helper;
use Exception;
use Dropp\Models\Dropp_Consignment;
use Dropp\Models\Dropp_PDF;

/**
 * Ajax
 */
class Ajax {
	/**
	 * Setup
	 */
	public static function setup(): void {
		add_action( 'wp_ajax_dropp_set_location', __CLASS__ . '::set_location' );
		add_action( 'wp_ajax_nopriv_dropp_set_location', __CLASS__ . '::set_location' );
		add_action( 'wp_ajax_dropp_booking', __CLASS__ . '::dropp_booking' );
		add_action( 'wp_ajax_dropp_status_update', __CLASS__ . '::dropp_status_update' );
		add_action( 'wp_ajax_dropp_cancel', __CLASS__ . '::dropp_cancel' );
		add_action( 'wp_ajax_dropp_update', __CLASS__ . '::dropp_update' );
		add_action( 'wp_ajax_dropp_pdf', __CLASS__ . '::dropp_pdf' );
		add_action( 'wp_ajax_dropp_pdf_single', __CLASS__ . '::dropp_pdf_single' );
		add_action( 'wp_ajax_dropp_pdf_merge', __CLASS__ . '::dropp_pdf_merge' );
		add_action( 'wp_ajax_dropp_get_pdf_list', __CLASS__ . '::dropp_get_pdf_list' );
		add_action( 'wp_ajax_dropp_add_extra_pdf', __CLASS__ . '::dropp_add_extra_pdf' );
		add_action( 'wp_ajax_dropp_delete_extra_pdf', __CLASS__ . '::dropp_delete_extra_pdf' );
		add_action( 'wp_ajax_dropp_get_instance_prices', __CLASS__ . '::dropp_get_instance_prices' );
		add_action( 'wp_ajax_dropp_dismiss_admin_notice', __CLASS__ . '::dropp_dismiss_admin_notice' );
	}

	/**
	 * Dropp Set Location
	 */
	public static function set_location(): void {
		$location_id = filter_input( INPUT_POST, 'location_id', FILTER_DEFAULT );
		$instance_id = filter_input( INPUT_POST, 'instance_id', FILTER_DEFAULT );
		if ( empty( $location_id ) || empty( $instance_id ) ) {
			wp_send_json(
				[
					'status'      => 'error',
					'message'     => __( 'Required field, location ID, instance ID or index was empty', 'dropp-for-woocommerce' ),
					'errors'      => '',
				]
			);
			die;
		}

		$old_location = WC()->session->get( 'dropp_session_location' );
		if ( empty( $old_location ) || $old_location['id'] !== $location_id ) {
			// Invalidate the shipping rate transient.
			WC_Cache_Helper::get_transient_version( 'shipping', true );
			// Save the new location to session.
			WC()->session->set(
				'dropp_session_location',
				[
					'id'        => $location_id,
					'name'      => filter_input( INPUT_POST, 'location_name', FILTER_DEFAULT ),
					'address'   => filter_input( INPUT_POST, 'location_address', FILTER_DEFAULT ),
					'pricetype' => filter_input( INPUT_POST, 'location_pricetype', FILTER_DEFAULT ),
				]
			);
		}

		wp_send_json(
			[
				'status'      => 'success',
				'message'     => __( 'Saved location ID', 'dropp-for-woocommerce' ),
				'label'       => '',
				'errors'      => '',
			]
		);
	}

	/**
	 * Dropp booking
	 */
	public static function nonce_verification( $method = 'post' ): void {
		if ( 'post' === $method ) {
			$nonce = filter_input( INPUT_POST, 'dropp_nonce', FILTER_DEFAULT );
		} else {
			$nonce = filter_input( INPUT_GET, 'dropp_nonce', FILTER_DEFAULT );
		}
		if ( ! wp_verify_nonce( $nonce, 'dropp' ) ) {
			wp_send_json(
				[
					'status'      => 'error',
					'message'     => __( 'Nonce verification failed. Please reload the page and try again.', 'dropp-for-woocommerce' ),
					'errors'      => '',
				]
			);
		}
	}

	/**
	 * Dropp booking
	 * @throws Exception
	 */
	public static function dropp_booking(): void {
		self::nonce_verification();
		$order_item_id   = filter_input( INPUT_POST, 'order_item_id', FILTER_DEFAULT );
		$shipping_item   = new WC_Order_Item_Shipping( $order_item_id );
		$instance_id     = $shipping_item->get_instance_id();
		$shipping_method = new Shipping_Method\Dropp( $instance_id ?: 0 );
		$consignment_id  = filter_input( INPUT_POST, 'consignment_id', FILTER_DEFAULT );

		$params = [
			'comment'      => filter_input( INPUT_POST, 'comment', FILTER_DEFAULT ),
			'day_delivery' => filter_input( INPUT_POST, 'day_delivery', FILTER_DEFAULT ),
			'location_id'  => filter_input( INPUT_POST, 'location_id', FILTER_DEFAULT ),
			'customer'     => filter_input( INPUT_POST, 'customer', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ),
			'products'     => filter_input( INPUT_POST, 'products', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ),
			'value'        => $shipping_item->get_order()->get_total(),
		];


		if ( empty( $consignment_id ) ) {
			$consignment = new Dropp_Consignment();
			$create_params =  [
				'shipping_item_id' => $order_item_id,
				'test'             => $shipping_method->test_mode,
				'debug'            => $shipping_method->debug_mode,
				'mynto_id'         => $shipping_item->get_meta('mynto_id'),
			];
			$consignment->fill(
				array_merge(
					$params,
					$create_params
				)
			);
		} else {
			$consignment               = Dropp_Consignment::find( $consignment_id );
			$consignment->comment      = $params['comment'];
			$consignment->day_delivery = ( filter_var( $params['day_delivery'], FILTER_VALIDATE_BOOLEAN ) ? 1 : 0 );
			$consignment->location_id  = $params['location_id'];
			$consignment->set_customer( $params['customer'] );
			$consignment->set_products( $params['products'] );
		}
		$dropp_order_id = $consignment->dropp_order_id;

		if ( empty( $consignment_id ) ) {
			// Save the new order.
			$consignment->save();
		}

		if ( ! $consignment->check_weight() ) {
			$consignment->status         = 'overweight';
			$consignment->status_message = sprintf(
				__( 'Cannot book the order because it\'s over the weight limit of %d Kg', 'dropp-for-woocommerce' ),
				$consignment->get_shipping_method()->weight_limit ?? 10
			);
			$consignment->save();
			wp_send_json(
				[
					'status'      => 'error',
					'consignment' => $consignment->to_array( false ),
					'message'     => $consignment->status_message,
					'errors'      => $consignment->errors,
				]
			);
			die;
		}

		try {
			if ( empty( $dropp_order_id ) ) {
				$consignment->remote_post()->save();
				$consignment->maybe_update_order_status();
			} else {
				$consignment->remote_patch()->save();
			}
		} catch ( \Exception $e ) {
			if ( empty( $dropp_order_id ) ) {
				// New orders should get an error status.
				$consignment->status = 'error';
				// Existing order should not change.
			}
			$consignment->save();

			wp_send_json(
				[
					'status'      => 'error',
					'consignment' => $consignment->to_array( false ),
					'message'     => $e->getMessage(),
					'errors'      => $consignment->errors,
				]
			);
		}

		wp_send_json(
			[
				'status'      => 'success',
				'consignment' => $consignment->to_array( false ),
				'message'     => __( 'Booked! Re-loading page...', 'dropp-for-woocommerce' ),
				'errors'      => [],
			]
		);
	}

	/**
	 * Dropp status update
	 */
	public static function dropp_status_update(): void {
		$consignment_id = filter_input( INPUT_GET, 'consignment_id', FILTER_DEFAULT );
		$consignment    = Dropp_Consignment::find( $consignment_id );
		try {
			$api       = new API();
			$api->test = $consignment->test;

			// Search the API.
			$remote_consignment = Dropp_Consignment::remote_find(
				$consignment->shipping_item_id,
				$consignment->dropp_order_id
			);
			if ( $remote_consignment->status != $consignment->status ) {
				$consignment->status = $remote_consignment->status;
				$consignment->save();
			}
		} catch ( \Exception $e ) {
			wp_send_json(
				[
					'status'      => 'error',
					'consignment' => $consignment->to_array( false ),
					'message'     => $e->getMessage(),
					'errors'      => $consignment->errors,
				]
			);
		}
		wp_send_json(
			[
				'status'      => 'success',
				'consignment' => $consignment->to_array( false ),
				'message'     => '',
				'errors'      => [],
			]
		);
	}

	/**
	 * Dropp cancel booking
	 */
	public static function dropp_cancel(): void {
		self::nonce_verification( 'get' );
		$consignment_id = filter_input( INPUT_GET, 'consignment_id', FILTER_DEFAULT );
		$consignment    = Dropp_Consignment::find( $consignment_id );
		try {
			$consignment->remote_delete();
			$consignment->save();
		} catch ( \Exception $e ) {
			wp_send_json(
				[
					'status'      => 'error',
					'consignment' => $consignment->to_array( false ),
					'message'     => $e->getMessage(),
					'errors'      => $consignment->errors,
				]
			);
		}
		wp_send_json(
			[
				'status'      => 'success',
				'consignment' => $consignment->to_array( false ),
				'message'     => '',
				'errors'      => [],
			]
		);
	}

	/**
	 * Dropp pdf
	 */
	public static function dropp_pdf(): void {
		$consignment_id = filter_input( INPUT_GET, 'consignment_id', FILTER_DEFAULT );
		self::dropp_pdf_consignment( $consignment_id );
	}

	/**
	 * Dropp pdf single
	 * @throws Exception
	 */
	public static function dropp_pdf_single(): void {
		$consignment_id = filter_input( INPUT_GET, 'consignment_id', FILTER_DEFAULT );
		$barcode        = filter_input( INPUT_GET, 'barcode', FILTER_DEFAULT );
		$consignment    = Dropp_Consignment::find( $consignment_id );
		if ( empty( $consignment ) || null === $consignment->id ) {
			throw new Exception( 'Could not find consignment' );
		}
		$pdf = new Dropp_PDF( $consignment, $barcode );
		header( 'Content-type: application/pdf' );
		echo $pdf->get_content();
	}

	/**
	 * Dropp pdf single
	 *
	 * Renders a single pdf and kills further execuion.
	 *
	 * @param string|int $consignment_id Consignment ID.
	 *
	 * @throws Exception
	 */
	protected static function dropp_pdf_consignment( $consignment_id ): void {
		$collection = Dropp_PDF_Collection::from_consignment( $consignment_id );
		try {
			$content = $collection->get_content();
		} catch ( Exception $e ) {
			wp_send_json(
				[
					'status'  => 'error',
					'message' => $e->getMessage(),
					'errors'  => [],
				]
			);
			die;
		}
		header( 'Content-type: application/pdf' );
		echo $content;
		die;
	}

	/**
	 * Dropp pdf merge
	 *
	 * Renders merged result of multiple consignment ID's
	 *
	 * @throws Exception Exception.
	 */
	public static function dropp_pdf_merge() {
		$consignment_ids = filter_input( INPUT_GET, 'consignment_ids', FILTER_DEFAULT );
		$consignment_ids = explode( ',', $consignment_ids );
		$consignment_ids = array_map( 'trim', $consignment_ids );

		if ( empty( $consignment_ids ) ) {
			wp_send_json(
				[
					'status'  => 'error',
					'message' => 'Missing consignment ids.',
				]
			);
			die;
		}

		$uploads_dir = Dropp_PDF::get_dir();
		if ( $uploads_dir['error'] ) {
			wp_send_json(
				[
					'status'  => 'error',
					'message' => $uploads_dir['error'],
				]
			);
			die;
		}

		$consignment_ids = array_unique( $consignment_ids );
		if ( 1 === count( $consignment_ids ) ) {
			// No need to merge 1 pdf.
			self::dropp_pdf_consignment( reset( $consignment_ids ) );
			return;
		}

		// Grab pdf's and save them.
		$collection = new Dropp_PDF_Collection();
		try {
			foreach ( $consignment_ids as $consignment_id ) {
				$consignment = Dropp_Consignment::find( $consignment_id );
				if ( null === $consignment->dropp_order_id ) {
					throw new Exception( __( 'Could not find consignment:', 'dropp-for-woocommerce' ) . ' ' . $consignment_id );
				}
				if ( in_array( $consignment->status, ['cancelled', 'error', 'ready'] ) ) {
					continue;
				}
				$collection->merge( Dropp_PDF_Collection::from_consignment( $consignment ) );
			}

			$content = $collection->get_content();
		} catch ( Exception $e ) {
			wp_send_json(
				[
					'status'  => 'error',
					'message' => $e->getMessage(),
				]
			);
			die;
		}
		header( 'Content-type: application/pdf' );
		echo $content;
		die;
	}

	/**
	 * Dropp get pdf list
	 */
	public static function dropp_get_pdf_list() {
		$consignment_id = filter_input( INPUT_GET, 'consignment_id', FILTER_DEFAULT );
		self::json_pdf_list( $consignment_id, 'list' );
	}

	/**
	 * Dropp add extra pdf
	 */
	public static function dropp_add_extra_pdf() {
		$consignment_id = filter_input( INPUT_GET, 'consignment_id', FILTER_DEFAULT );
		self::json_pdf_list( $consignment_id, 'add_extra' );
	}

	/**
	 * Dropp delete extra pdf
	 */
	public static function dropp_delete_extra_pdf() {
		$consignment_id = filter_input( INPUT_GET, 'consignment_id', FILTER_DEFAULT );
		$barcode        = filter_input( INPUT_GET, 'barcode', FILTER_DEFAULT );
		self::json_pdf_list( $consignment_id, 'delete_extra', $barcode );
	}

	public static function dropp_get_instance_prices(): void
	{
		$instance_id = filter_input( INPUT_GET, 'instance_id', FILTER_DEFAULT );
		$shipping_instance = (new Get_Shipping_Instance_Data_Action)($instance_id);

		Price_Info_Data::flush_cache();
		$prices          = Price_Info_Data::get_instance()->get($shipping_instance->shipping_method->get_code());
		wp_send_json_success($prices);
	}

	public static function dropp_dismiss_admin_notice(): void
	{
		$notice_code = filter_input( INPUT_POST, 'notice_code', FILTER_DEFAULT );
		if (is_null($notice_code)) {
			wp_die(
				'Notice code is required',
				'Notice code is required',
				['response' => 400]
			);
		}
		Admin_Notice_Utility::get($notice_code)->dismiss();
		Admin_Notice_Utility::update();
	}

	/**
	 * JSON PDF List
	 *
	 * @param integer $consignment_id Consignment ID.
	 * @param string $method Dropp_PDF method.
	 * @param boolean|string $barcode (optional) Barcode to delete.
	 *
	 * @throws Exception
	 */
	protected static function json_pdf_list( int $consignment_id, string $method, $barcode = false ) {
		$consignment = Dropp_Consignment::find( $consignment_id );
		$api         = new API( $consignment->get_shipping_method() );
		$api->test   = $consignment->test;

		$endpoint = "orders/extrabyorder/{$consignment->dropp_order_id}/";
		if ( 'add_extra' == $method ) {
			$endpoint = "orders/addextra/{$consignment->dropp_order_id}/";
		}
		if ( 'delete_extra' == $method ) {
			$endpoint = "orders/deleteextraorder/{$consignment->dropp_order_id}/{$barcode}/";
		}

		$result = $api->get( $endpoint );
		$list   = $result['extraOrders'];
		$pdfs   = [];
		foreach ( $list as $extra_pdf ) {
			$pdfs[] = [
				'label' => $extra_pdf['barcode'] . '.pdf',
				'barcode' => $extra_pdf['barcode'],
			];
		}
		wp_send_json(
			array_merge(
				[
					[
						'label' => "{$consignment->barcode}.pdf",
					],
				],
				$pdfs
			)
		);
	}
}
