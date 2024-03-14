<?php

namespace WpifyWoo\Repositories;

use WpifyWoo\Models\PacketaOrderModel;
use WpifyWoo\Plugin;
use WpifyWoo\PostTypes\PacketaOrderPostType;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractWooOrderRepository;

/**
 * @property Plugin $plugin
 */
class PacketaOrderRepository extends AbstractWooOrderRepository {

	/**
	 * @return PacketaOrderPostType
	 */
	public function post_type(): PacketaOrderPostType {
		return $this->plugin->get_post_type( PacketaOrderPostType::class );
	}

	public function save( PacketaOrderModel $order ) {
		$custom_fields = array(
			PacketaOrderModel::FIELD_PACKETA_ID              => $order->get_packeta_id(),
			PacketaOrderModel::FIELD_PACKETA_PACKAGE_ID      => $order->get_package_id(),
			PacketaOrderModel::FIELD_PACKETA_PACKAGE_BARCODE => $order->get_barcode(),
			PacketaOrderModel::FIELD_PACKETA_DETAILS         => array(
				'name'     => $order->get_packeta_name(),
				'street'   => $order->get_packeta_street(),
				'city'     => $order->get_packeta_city(),
				'postcode' => $order->get_packeta_postcode(),
				'url'      => $order->get_packeta_url(),
			),
			PacketaOrderModel::FIELD_PACKETA_ORDER_DETAILS   => [
				'weight' => $order->get_packeta_weight() ?: $order->get_package_weight(),
			],
			PacketaOrderModel::FIELD_PACKETA_INVOICE_ID      => $order->get_packeta_invoice_id()
		);

		foreach ( $custom_fields as $key => $value ) {
			$order->get_wc_order()->update_meta_data( $key, $value );
		}

		return $order->get_wc_order()->save();
	}
}
