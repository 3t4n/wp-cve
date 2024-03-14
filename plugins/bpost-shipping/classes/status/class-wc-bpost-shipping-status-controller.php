<?php
namespace WC_BPost_Shipping\Status;

use Bpost\BpostApiClient\BpostException;
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Status;
use WC_BPost_Shipping\Controller\WC_BPost_Shipping_Controller_Base;
use WC_BPost_Shipping_Meta_Handler;

/**
 * Class WC_BPost_Shipping_Status_Controller
 * @package WC_BPost_Shipping\Status
 */
class WC_BPost_Shipping_Status_Controller extends WC_BPost_Shipping_Controller_Base {
	const ORDER_REFERENCE_KEY = 'order_reference';
	const ATTACHMENT_ID_KEY   = 'attachment_id';

	private $wp_once;
	/** @var int[] */
	private $post_ids;
	/** @var WC_BPost_Shipping_Api_Status */
	private $bpost_api_status;

	/**
	 * WC_BPost_Shipping_Label_Controller constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param WC_BPost_Shipping_Api_Status $bpost_api_status
	 * @param array $external_data
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter,
		WC_BPost_Shipping_Api_Status $bpost_api_status,
		$external_data
	) {
		parent::__construct( $adapter );

		$this->wp_once          = $external_data['wp_once']; //wp_verify_nonce don't need to filter it
		$this->post_ids         = filter_var( $external_data['post_ids'], FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
		$this->bpost_api_status = $bpost_api_status;
	}


	/**
	 * @return bool
	 */
	public function verify_wp_one() {
		return (bool) wp_verify_nonce( $this->wp_once );
	}

	/**
	 * This function provides a contract to use to load a template using controller.
	 */
	public function load_template() {
		if ( ! $this->verify_wp_one() ) {
			return new \WP_Error( 'verify_wp_once', __( 'Security issue.' ) );
		}

		$bpost_statuses = array();

		foreach ( $this->post_ids as $post_id ) {
			$meta_handler = new \WC_BPost_Shipping_Meta_Handler(
				$this->adapter,
				new \WC_BPost_Shipping_Meta_Type( $this->adapter ),
				$post_id
			);

			try {
				$status = $this->bpost_api_status->get_status( $meta_handler->get_order_reference() );
				$meta_handler->set_meta(WC_BPost_Shipping_Meta_Handler::KEY_STATUS, $status );
				$bpost_statuses[ $post_id ] = $status;

			} catch ( BpostException $e ) {
				$bpost_statuses[ $post_id ] = 'UNKNOWN';
			}
		}

		header( 'Content-Type: application/json', true );

		echo json_encode( $bpost_statuses );
	}
}
