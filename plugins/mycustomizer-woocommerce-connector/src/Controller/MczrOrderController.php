<?php

namespace MyCustomizer\WooCommerce\Connector\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Factory\MczrFactory;
use MyCustomizer\WooCommerce\Connector\Libs\MczrConnect;
use MyCustomizer\WooCommerce\Connector\Libs\MczrSettings;

MczrAccess::isAuthorized();

/*
 * Dispatch webhooks
 */

class MczrOrderController {

	public function __construct() {
		$this->request  = Request::createFromGlobals();
		$this->response = new Response();
		$this->settings = new MczrSettings();
		$this->mczr     = new MczrConnect();
		$this->factory  = new MczrFactory();
		$this->twig     = $this->factory->getTwig();
	}

	public function init() {
		add_action( 'woocommerce_order_status_changed', array( $this, 'onOrderStatusChanged' ), 99, 3 );
		//add_action( 'woocommerce_order_edit_product', array( $this, 'onOrderStatusChanged' ), 99, 3);
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'onOrderCreated' ), 99, 3 );
		add_action( 'woocommerce_order_item_meta_start', array( $this, 'displayMczrOrderItems' ), 10, 3 );
	}

	private function extractMeta( $metas, $key = 'mczr', $keyName = 'designId' ) {
		if ( empty( $metas ) ) {
			return false;
		}

		foreach ( $metas as $meta ) {
			if (  $meta->get_data()['key'] != $key ) {
				continue;
			}

			// wildcard * returns all
			if ( '*' == $keyName ) {
				return $meta->get_data()['value'];
			}

			// ...or a specific value
			if ( isset( $meta->get_data()['value'][ $keyName ] ) && ! empty( $meta->get_data()['value'][ $keyName ] ) ) {
				return $meta->get_data()['value'][ $keyName ];
			}
		}

		return false;
	}

	public function getKickflipOrders( $fromDate ) {
		$ordersArgs = array(
			'date_modified' => ">=$fromDate"
		);
		$orders = wc_get_orders( $ordersArgs );
		$kickflipOrders = array();

		foreach ( $orders as $order ) {
			$kickflipItems = array();
			$items = $order->get_items();

			foreach ( $items as $item ) {
				$metas     = $item->get_meta_data();
				$productId = $item->get_data()['product_id'];
				$product   = \wc_get_product( $productId );

				if ( ! $product->is_type( 'mczr' ) ) {
					continue;
				}

				$metasMczr   = $this->extractMeta( $metas, 'mczr', '*' );
				$props       = array(
					'design'   => $metasMczr['designId'],
					'quantity' => $item->get_data()['quantity'],
					'eCommerceItemId' => $item->get_data()['id'],
				);

				if ( $item->get_subtotal() != $item->get_total() && $item->get_total() < $item->get_subtotal() ) {
					$props['discounts'][] = array(
						'amount' => $item->get_subtotal() - $item->get_total()
					);
				}
				$kickflipItems[] = $props;
			}

			if ( ! $kickflipItems ) {
				continue;
			}

			$orderId = $order->get_id();
			$kickflipStatusData = $this->getMczrStatus( $order->get_status() );
			$kickflipOrders[] = array(
				'eCommerceOrderId'    => $orderId,
				'eCommerceOrderName'  => "Order #$orderId",
				'store'               => $this->settings->get( 'shopId' ),
				'items'               => $kickflipItems,
				'currency'            => $order->get_currency(),
				'status'              => $kickflipStatusData['status'],
				'paymentStatus'       => $kickflipStatusData['paymentStatus'],
				'date'                => $order->get_date_created()->date('Y-m-d H:i:s')
			);
		}

		return $kickflipOrders;
	}

	public function onOrderCreated( $orderId ) {
		$order     = new \WC_Order( $orderId );
		$items     = $order->get_items();
		$mczrItems = array();

		// Check if order includes at least one customizable item
		foreach ( $items as $item ) {
			$metas     = $item->get_meta_data();
			$productId = $item->get_data()['product_id'];
			$product   = \wc_get_product( $productId );

			if ( ! $product->is_type( 'mczr' ) ) {
				continue;
			}

			$metasMczr   = $this->extractMeta( $metas, 'mczr', '*' );
			$props       = array(
				'design'   => $metasMczr['designId'],
				'quantity' => $item->get_data()['quantity'],
				'eCommerceItemId' => $item->get_data()['id'],
			);

			if ( $item->get_subtotal() != $item->get_total() && $item->get_total() < $item->get_subtotal() ) {
				$props['discounts'][] = array(
					'amount' => $item->get_subtotal() - $item->get_total()
				);
			}
			$mczrItems[] = $props;
		}

		// No mczr product, webhook will not fired
		if ( count( $mczrItems ) <= 0 ) {
			return;
		}

		// Fire webhook
		$path                          = "/brands/{$this->settings->get('brand')}/orders";
		$content                       = array();
		$content['eCommerceOrderId']   = $orderId;
		$content['eCommerceOrderName'] = "Order #$orderId";
		$content['store']              = $this->settings->get( 'shopId' );
		$content['items']              = $mczrItems;
		$content['currency']           = $order->get_currency();
		$content['date']               = $order->get_date_created()->date('Y-m-d H:i:s');
		$this->mczr->post( $path, $content );
		return;
	}

	public function onOrderStatusChanged( $orderId, $old_status, $newStatus ) {
		$order     = new \WC_Order( $orderId );
		$items     = $order->get_items();
		$mczrItems = array();

		// Check if order includes at least one customizable item
		foreach ( $items as $item ) {
			$metas     = $item->get_meta_data();
			$productId = $item->get_data()['product_id'];
			$product   = \wc_get_product( $productId );
			if ( 'boolean' == gettype( $product ) ) {
				continue;
			}
			if ( ! $product->is_type( 'mczr' ) ) :
				continue;
			endif;
			$metasMczr   = $this->extractMeta( $metas, 'mczr', '*' );
			$props       = array(
				'design'   => $metasMczr['designId'],
				'quantity' => $item->get_data()['quantity'],
				'eCommerceItemId' => $item->get_data()['id'],
			);
			if ( $item->get_subtotal() != $item->get_total() && $item->get_total() < $item->get_subtotal() ) {
				$props['discounts'][] = array(
					'amount' => $item->get_subtotal() - $item->get_total()
				);
			}
			$mczrItems[] = $props;
		}

		// No mczr product, webhook will not fired
		if ( count( $mczrItems ) <= 0 ) {
			return;
		}

		$path               = "/brands/{$this->settings->get('brand')}/orders/update";
		$content            = array();
		$content['payload'] = $this->getMczrStatus( $newStatus );
		$content['payload']['items'] = $mczrItems;
		$content['query']   = array(
			'eCommerceOrderId' => $orderId,
			'store'            => $this->settings->get( 'shopId' ),
		);
		$posted             = $this->mczr->post( $path, $content );
		return;
	}

	private function getMczrStatus( $shopOrderStatus ) {
		$return = array();
		switch ( $shopOrderStatus ) {
			case 'on-hold':
				$return['status'] = 'pending';
				break;
			case 'pending':
			case 'failed':
				$return['status']        = 'pending';
				$return['paymentStatus'] = 'unpaid';
				break;
			case 'processing':
				$return['status']        = 'pending';
				$return['paymentStatus'] = 'paid';
				break;
			case 'cancelled':
				$return['status'] = 'cancelled';
				break;
			case 'completed':
				$return['status']        = 'fulfilled';
				$return['paymentStatus'] = 'paid';
				break;
			case 'refunded':
				$return['status']        = 'cancelled';
				$return['paymentStatus'] = 'refunded';
				break;
			default:
				$return['status']        = 'pending';
				$return['paymentStatus'] = 'unpaid';
		}

		return $return;
	}

	function displayMczrOrderItems( $item_id, $item, $order ) {
		if ( $item->is_type('line_item') ) {
			$mczrMetas = wc_get_order_item_meta($item_id, 'mczr');

			if ( isset($mczrMetas) && ! empty($mczrMetas) ) {
				if (isset($mczrMetas['image'])) {
					printf( '<div><img src="' . $mczrMetas['image'] . '" width="250px" height="auto"/></div>' );
				}
				if (isset($mczrMetas['summary_v2'])) {
					foreach ($mczrMetas['summary_v2'] as $mczrMetaId => $mczrMeta) {
						$value = is_array($mczrMetas['summary_v2'][$mczrMetaId]['value']) ? implode(', ', $mczrMetas['summary_v2'][$mczrMetaId]['value']) : $mczrMetas['summary_v2'][$mczrMetaId]['value'];
						printf( '<div>' . __("{$mczrMetas["summary_v2"][$mczrMetaId]["key"]}: {$value}", 'woocommerce') . '</div>' );
					}
				}
			}
		}
	}


}
