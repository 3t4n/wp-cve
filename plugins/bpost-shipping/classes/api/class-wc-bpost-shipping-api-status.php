<?php

namespace WC_BPost_Shipping\Api;

use Bpost\BpostApiClient\Bpost\Order\Box;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostCurlException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidResponseException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidSelectionException;
use Bpost\BpostApiClient\Exception\XmlException\BpostXmlNoReferenceFoundException;

/**
 * Class WC_BPost_Shipping_Api_Status
 * @package WC_BPost_Shipping\Api
 */
class WC_BPost_Shipping_Api_Status {

	/** @var WC_BPost_Shipping_Api_Connector */
	private $connector;
	/** @var \WC_BPost_Shipping_Logger */
	private $logger;

	/**
	 * WC_BPost_Shipping_Api_Status constructor.
	 *
	 * @param WC_BPost_Shipping_Api_Connector $connector
	 * @param \WC_BPost_Shipping_Logger $logger
	 */
	public function __construct( WC_BPost_Shipping_Api_Connector $connector, \WC_BPost_Shipping_Logger $logger ) {
		$this->connector = $connector;
		$this->logger    = $logger;
	}

	/**
	 * @param string $order_reference
	 *
	 * @return string
	 * @throws BpostInvalidResponseException
	 * @throws BpostInvalidSelectionException
	 * @throws BpostXmlNoReferenceFoundException
	 * @throws BpostCurlException
	 */
	public function get_status( $order_reference ) {
		try {
			$order = $this->connector->fetchOrder( $order_reference );
		} catch ( BpostInvalidResponseException $e ) {
			$this->logger->error( 'Invalid API response: ' . $e->getMessage() );

			return 'NOT_FOUND';
		}

		$boxes = $order->getBoxes();
		/** @var Box $box */
		$box = current( $boxes );

		return $box->getStatus();
	}
}
