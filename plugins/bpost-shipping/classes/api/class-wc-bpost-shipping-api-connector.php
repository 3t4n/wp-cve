<?php

namespace WC_BPost_Shipping\Api;

//TODO don't extend bpost && use an instance. Make bridge to avoid any direct call to parent (isolation principle)
use Bpost\BpostApiClient\Bpost;
use Bpost\BpostApiClient\BpostException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostCurlException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidResponseException;
use Bpost\BpostApiClient\Exception\BpostApiResponseException\BpostInvalidSelectionException;

class WC_BPost_Shipping_Api_Connector extends Bpost {

	/**
	 * @return Bpost\ProductConfiguration
	 */
	private $product_config;

	/**
	 * @return bool
	 */
	public function is_online() {
		try {
			$this->fetchProductConfig();

			return true;
		} catch ( BpostException $exception ) {
			return false;
		}
	}

	/**
	 * @throws BpostCurlException
	 * @throws BpostInvalidResponseException
	 * @throws BpostInvalidSelectionException
	 * @return Bpost\ProductConfiguration
	 */
	public function fetchProductConfig() {
		if ( ! $this->product_config ) {
			$this->product_config = parent::fetchProductConfig();
		}

		return $this->product_config;
	}
}
