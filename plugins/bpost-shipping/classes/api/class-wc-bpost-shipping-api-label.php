<?php

namespace WC_BPost_Shipping\Api;


use Bpost\BpostApiClient\Bpost\Label;
use Bpost\BpostApiClient\Bpost\Order\Box;
use Bpost\BpostApiClient\BpostException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostCurlException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidResponseException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidSelectionException;
use Bpost\BpostApiClient\Exception\BpostLogicException\BpostInvalidValueException;
use Bpost\BpostApiClient\Exception\XmlException\BpostXmlNoReferenceFoundException;
use WC_BPost_Shipping\Label\Exception\WC_BPost_Shipping_Label_Exception_Not_Found;
use WC_BPost_Shipping\Label\Exception\WC_BPost_Shipping_Label_Exception_Too_Much_Found;

class WC_BPost_Shipping_Api_Label {

	/** @var WC_BPost_Shipping_Api_Connector */
	private $connector;
	/** @var \WC_BPost_Shipping_Logger */
	private $logger;

	public function __construct( WC_BPost_Shipping_Api_Connector $connector, \WC_BPost_Shipping_Logger $logger ) {
		$this->connector = $connector;
		$this->logger    = $logger;
	}

	/**
	 * @param string $order_reference
	 * @param string $format A6 or A4 (please check consts)
	 * @param bool $with_return_labels
	 *
	 * @return Label
	 * @throws BpostCurlException
	 * @throws BpostInvalidResponseException
	 * @throws BpostInvalidSelectionException
	 * @throws BpostXmlNoReferenceFoundException
	 * @throws WC_BPost_Shipping_Label_Exception_Not_Found
	 */
	public function get_label( $order_reference, $format, $with_return_labels ) {
		$labels = array();
		try {
			$labels = $this->connector->createLabelForOrder( $order_reference, $format, $with_return_labels, true );
		} catch ( BpostException $ex ) {
			$this->logger->log_exception( $ex );
		}
		if ( 1 !== count( $labels ) ) {
			$labels = $this->get_already_printed_labels( $order_reference, $format, $with_return_labels );
			switch ( count( $labels ) ) {
				case 0:
					throw new WC_BPost_Shipping_Label_Exception_Not_Found(
						sprintf(
							bpost__( 'Label not found for reference=%s, format=%s, with_return_label=%d.' ),
							$order_reference,
							$format,
							$with_return_labels
						)
					);
				case 1:
					break;
				default:
					throw new WC_BPost_Shipping_Label_Exception_Too_Much_Found(
						sprintf(
							bpost__( '%d labels found for reference=%s, format=%s, with_return_label=%d.' ),
							count( $labels ),
							$order_reference,
							$format,
							$with_return_labels
						)
					);
			}
		}

		return $labels[0];
	}

	/**
	 * @param array $order_references
	 * @param $format
	 *
	 * @return Label[]
	 * @throws BpostInvalidValueException
	 * @throws BpostCurlException
	 * @throws BpostInvalidResponseException
	 * @throws BpostInvalidSelectionException
	 */
	public function get_labels( array $order_references, $format ) {
		return $this->connector->createLabelInBulkForOrders( $order_references, $format, false, true );
	}

	/**
	 * @param string $order_reference
	 *
	 * @return string[]
	 * @throws BpostCurlException
	 * @throws BpostInvalidResponseException
	 * @throws BpostInvalidSelectionException
	 * @throws BpostXmlNoReferenceFoundException
	 */
	public function get_barcodes( $order_reference ) {
		try {
			return array_map(
				function ( Box $box ) {
					return $box->getBarcode();
				},
				$this->connector->fetchOrder( $order_reference )->getBoxes()
			);
		} catch ( BpostInvalidResponseException $e ) {
			$this->logger->error( 'Invalid API response: ' . $e->getMessage() );

			return array();
		}
	}

	/**
	 * @param string $order_reference
	 * @param string $format
	 * @param bool $with_return_labels
	 *
	 * @return Label[]
	 * @throws BpostCurlException
	 * @throws BpostInvalidResponseException
	 * @throws BpostInvalidSelectionException
	 * @throws BpostXmlNoReferenceFoundException
	 */
	private function get_already_printed_labels( $order_reference, $format, $with_return_labels ) {
		$barcodes = $this->get_barcodes( $order_reference );

		$labels = array();
		try {
			// $withReturnLabels provided by createLabelForBox doesn't work.
			// It's hardcoded to false to prevent any dream
			$labels = $this->connector->createLabelForBox( $barcodes[0], $format, $with_return_labels, true );
		} catch ( BpostException $ex ) {
			$this->logger->log_exception( $ex );
		}

		return $labels;
	}
}
