<?php

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Shm_Callback;
use WC_BPost_Shipping\Street\WC_BPost_Shipping_Street_Builder;

/**
 * Class WC_BPost_Shipping_SHM_Saver seves data from shm and reinject it into flow or session
 */
class WC_BPost_Shipping_SHM_Saver {
	/** @var WC_BPost_Shipping_Adapter_Shm_Callback */
	private $shm_callback_adapter;

	/** @var  WC_BPost_Shipping_Street_Builder */
	private $street_builder;
	/** @var WC_BPost_Shipping_Logger */
	private $logger;

	/**
	 * @param WC_BPost_Shipping_Adapter_Shm_Callback $shm_callback_adapter
	 * @param WC_BPost_Shipping_Street_Builder $street_builder
	 * @param WC_BPost_Shipping_Logger $logger
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Shm_Callback $shm_callback_adapter,
		WC_BPost_Shipping_Street_Builder $street_builder,
		WC_BPost_Shipping_Logger $logger
	) {
		$this->street_builder       = $street_builder;
		$this->logger               = $logger;
		$this->shm_callback_adapter = $shm_callback_adapter;
	}

	/**
	 * @return array
	 */
	public function get_data_to_post() {
		$address_lines = $this->street_builder->get_street_lines(
			$this->shm_callback_adapter->get_street(),
			$this->shm_callback_adapter->get_street_number(),
			$this->shm_callback_adapter->get_street_box()
		);

		$new_post = array(
			'status'                    => true,
			'ship_to_different_address' => 1,
			'shipping_first_name'       => $this->shm_callback_adapter->get_first_name(),
			'shipping_last_name'        => $this->shm_callback_adapter->get_last_name(),
			'shipping_company'          => $this->get_company_name(),
			'shipping_address_1'        => $address_lines[0],
			'shipping_address_2'        => $address_lines[1],
			'shipping_postcode'         => $this->shm_callback_adapter->get_postal_code(),
			'shipping_city'             => $this->shm_callback_adapter->get_city(),
			'shipping_country'          => $this->shm_callback_adapter->get_country(),
			'bpost_email'               => $this->shm_callback_adapter->get_email(),
			'bpost_phone'               => $this->shm_callback_adapter->get_phone_number(),
			'bpost_delivery_method'     => $this->shm_callback_adapter->get_delivery_method(),
			'bpost_delivery_price'      => $this->shm_callback_adapter->get_delivery_price(),
			'bpost_delivery_date'       => $this->shm_callback_adapter->get_delivery_date(),
			'bpost_postal_location'     => $this->shm_callback_adapter->get_postal_location(),
			'bpost_order_reference'     => $this->shm_callback_adapter->get_order_reference(),
			'bpost_shm_already_called'  => 'yes',
		);

		$new_post['bpost_delivery_address'] = implode( "\n",
			array_filter( array(
				$new_post['bpost_postal_location']
					?: $new_post['shipping_first_name'] . ' ' . $new_post['shipping_last_name'],
				$new_post['shipping_address_1'],
				$new_post['shipping_address_2'],
				$new_post['shipping_postcode'] . ' ' . $new_post['shipping_city'],
				$new_post['shipping_country'],
			), function ( $v ) {
				return ! empty( $v );
			} ) );

		$delivery_point_id = $this->shm_callback_adapter->get_delivery_post_point_id();
		if ( $delivery_point_id ) {
			$new_post['bpost_delivery_point_id'] = $delivery_point_id;
		}

		$state = $this->shm_callback_adapter->get_state();
		if ( $state ) {
			$new_post['shipping_state'] = $state;
		}

		$this->logger->debug( __METHOD__, $new_post );

		return $new_post;
	}

	/**
	 * @return string
	 */
	private function get_company_name() {
		$delivery_method = new WC_BPost_Shipping_Delivery_Method( $this->shm_callback_adapter->get_delivery_method() );

		return $delivery_method->get_company_name(
			$this->shm_callback_adapter->get_postal_location(),
			$this->shm_callback_adapter->get_company()
		);
	}
}
