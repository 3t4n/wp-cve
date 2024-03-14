<?php

namespace WC_BPost_Shipping\Api;

use WC_BPost_Shipping_Logger;

/**
 * Class WC_BPost_Shipping_Api_Geo6_Search
 * @package WC_BPost_Shipping\Api
 */
class WC_BPost_Shipping_Api_Geo6_Search {

	/** @var WC_BPost_Shipping_Api_Geo6_Connector */
	private $connector;

	public function __construct(
		WC_BPost_Shipping_Api_Geo6_Connector $connector
	) {
		$this->connector = $connector;
	}

	/**
	 * @param int $point_id
	 *
	 * @return string
	 */
	public function get_point_type( $point_id ) {
		$poi = $this->connector->getServicePointDetails(
			$point_id,
			'nl',
			31 // 1+2+4+8+16
		);

		return $poi->getType();
	}

}
