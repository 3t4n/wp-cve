<?php

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Factory;
use WC_BPost_Shipping\Assets\WC_BPost_Shipping_Assets_Management;
use WC_BPost_Shipping\Controller\WC_BPost_Shipping_Controller_Base;
use WC_BPost_Shipping\Locale\WC_BPost_Shipping_Locale_Locale;
use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Base;
use WC_BPost_Shipping\Street\WC_BPost_Shipping_Street_Formatter;

/**
 * Class WC_BPost_Shipping_Order_Details_Controller adds a 'bpost shipping details' block on last page (order received)
 * It gets meta send at previous steps and displays.
 */
class WC_BPost_Shipping_Order_Details_Controller extends WC_BPost_Shipping_Controller_Base {

	const MAP_PROVIDER_GOOGLE = 'google';
	const MAP_PROVIDER_GEO6   = 'geo6';

	/** @var WC_BPost_Shipping_Meta_Handler */
	private $meta_handler;
	/** @var WC_Order */
	private $order;
	/** @var WC_BPost_Shipping_Assets_Management */
	private $assets_management;
	/** @var WC_BPost_Shipping_Options_Base */
	private $options;

	/**
	 * WC_BPost_Shipping_Order_Details_Controller constructor.
	 *
	 * @param WC_BPost_Shipping_Assets_Management $assets_management
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param WC_BPost_Shipping_Assets_Management $assets_management
	 * @param WC_BPost_Shipping_Meta_Handler $meta_handler
	 * @param WC_BPost_Shipping_Options_Base $options
	 * @param WC_Order $order
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter,
		WC_BPost_Shipping_Assets_Management $assets_management,
		WC_BPost_Shipping_Meta_Handler $meta_handler,
		WC_BPost_Shipping_Options_Base $options,
		WC_Order $order
	) {
		parent::__construct( $adapter );
		$this->meta_handler      = $meta_handler;
		$this->order             = $order;
		$this->assets_management = $assets_management;
		$this->options           = $options;
	}


	public function load_template() {
		$this->get_template( 'order-details.php', $this->get_template_data() );
	}

	/**
	 * @return array
	 */
	private function get_template_data() {
		$template_data = array(
			'bpost_meta'       => $this->meta_handler->get_translated_bpost_meta(),
			'has_shipping_map' => false,
		);

		if ( $this->meta_handler->get_delivery_point_id_value() ) {
			return array_merge( $template_data, $this->get_geo6_template_data() );
		} else {
			return array_merge( $template_data, $this->get_google_template_data() );
		}

	}

	/**
	 * @return array
	 */
	private function get_geo6_template_data() {
		if ( ! $this->meta_handler->get_delivery_point_type() ) {
			$api_factory   = new WC_BPost_Shipping_Api_Factory(
				$this->options,
				\WC_BPost_Shipping\WC_Bpost_Shipping_Container::get_logger()
			);
			$order_updater = new WC_BPost_Shipping_Order_Updater(
				$this->order,
				array( 'bpost_delivery_point_id' => $this->meta_handler->get_delivery_point_id_value() ),
				$api_factory->get_geo6_search()
			);
			$order_updater->update_bpost_point_type();
		}

		$locale = new WC_BPost_Shipping_Locale_Locale( $this->adapter );

		$url = sprintf(
			'https://pudo.bpost.be/Locator?Function=page&Country=BE&Partner=999999&Id=%s&Type=%s&Language=%s',
			$this->meta_handler->get_delivery_point_id_value(),
			$this->meta_handler->get_delivery_point_type(),
			$locale->get_language()
		);

		return array(
			'has_shipping_map' => true,
			'map_provider'     => self::MAP_PROVIDER_GEO6,
			'geo6_map_url'     => $url,
		);
	}

	/**
	 * @return array
	 */
	private function get_google_template_data() {
		$google_api_key = $this->options->get_gmaps_api_key();

		if ( empty( $google_api_key ) ) {
			// Without API key, we cannot build the map (has_shipping_map = false)
			return array();
		}

		$street_formatter = new WC_BPost_Shipping_Street_Formatter( $this->order->get_address( 'shipping' ) );

		$this->assets_management->order_receive_page(
			array(
				'gmaps_api_key' => $google_api_key,
				'address'       => $street_formatter->get_gmaps_address(),
				'signed_in'     => 'true',
			)
		);

		return array(
			'has_shipping_map' => true,
			'map_provider'     => self::MAP_PROVIDER_GOOGLE,
		);

	}

}
