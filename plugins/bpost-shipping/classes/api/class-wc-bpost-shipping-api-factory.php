<?php
namespace WC_BPost_Shipping\Api;

use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Base;
use WC_BPost_Shipping_Logger;

/**
 * Class WC_BPost_Shipping_Product_Configuration_Factory creates an instance of WC_BPost_Shipping_Product_Configuration ^^
 */
class WC_BPost_Shipping_Api_Factory {

	/** @var WC_BPost_Shipping_Options_Base */
	private $bpost_options;
	/** @var WC_BPost_Shipping_Logger */
	private $logger;

	/**
	 * WC_BPost_Shipping_Product_Configuration_Factory constructor.
	 *
	 * @param WC_BPost_Shipping_Options_Base $options
	 * @param WC_BPost_Shipping_Logger $logger
	 */
	public function __construct( WC_BPost_Shipping_Options_Base $options, WC_BPost_Shipping_Logger $logger ) {
		$this->bpost_options = $options;
		$this->logger        = $logger;
	}

	/**
	 * @return WC_BPost_Shipping_Api_Product_Configuration
	 */
	public function get_product_configuration() {
		return new WC_BPost_Shipping_Api_Product_Configuration( $this->get_api_connector(), $this->logger );
	}

	/**
	 * @return WC_BPost_Shipping_Api_Label
	 */
	public function get_label() {
		return new WC_BPost_Shipping_Api_Label( $this->get_api_connector(), $this->logger );
	}

	/**
	 * @return WC_BPost_Shipping_Api_Geo6_Search
	 */
	public function get_geo6_search() {
		return new WC_BPost_Shipping_Api_Geo6_Search( $this->get_api_geo6_connector() );
	}


	/**
	 * @return WC_BPost_Shipping_Api_Connector
	 */
	public function get_api_connector() {
		$connector = new WC_BPost_Shipping_Api_Connector(
			$this->bpost_options->get_account_id(),
			$this->bpost_options->get_passphrase(),
			$this->bpost_options->get_api_url()
		);

		$connector->setLogger( $this->logger );

		return $connector;
	}

	/**
	 * @return WC_BPost_Shipping_Api_Status
	 */
	public function get_api_status() {
		return new WC_BPost_Shipping_Api_Status( $this->get_api_connector(), $this->logger );
	}

	/**
	 * @return WC_BPost_Shipping_Api_Geo6_Connector
	 */
	public function get_api_geo6_connector() {
		$connector = new WC_BPost_Shipping_Api_Geo6_Connector( '999999', 'A001' );

		return $connector;
	}
}
