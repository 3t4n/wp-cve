<?php
/**
 * Created by PhpStorm.
 * Date: 6/6/18
 * Time: 4:10 PM
 */
namespace Hfd\Woocommerce\Helper;

use Hfd\Woocommerce\Container;

class Hfd
{
    const STATUS_SEND_SUCCESS = 1;

    const STATUS_SEND_ERROR = 2;

    public function sendOrders($orderIds)
    {
        if (!is_array($orderIds)) {
            $orderIds = array($orderIds);
        }

        $errors = [];
        $count = 0;

        /* @var \Hfd\Woocommerce\Helper\Hfd\Api $api */
        $api = Container::create('Hfd\Woocommerce\Helper\Hfd\Api');
        foreach ($orderIds as $orderId) {
            $order = wc_get_order($orderId);
            if (!$order) {
                continue;
            }
			do_action( 'hfd_before_order_sync', $order );
            $syncFlag = $order->get_meta('hfd_sync_flag');
            if ($syncFlag == self::STATUS_SEND_SUCCESS) {
                $errors[] = sprintf(__('Sync to HFD-------Already Synched with HFD-order ID: %s', 'hfd-integration'), $order->get_id());
                continue;
            }

            if (!$this->validateShippingMethod($order)) {
                $errors[] = sprintf(__('Sync to HFD-------order ID %s not allow sync to HFD', 'hfd-integration'), $order->get_id());
                continue;
            }

            $result = $api->syncOrder($order);

            if (!$result['error']) {
                $count++;
                $order->add_meta_data('hfd_sync_flag', \Hfd\Woocommerce\Helper\Hfd::STATUS_SEND_SUCCESS, true);
				$hfd_ship_number = apply_filters( 'hfd_ship_number', $result['number'] );
				$hfd_rand_number = apply_filters( 'hfd_rand_number', $result['rand_number'] );
				
                $order->add_meta_data( 'hfd_ship_number', $hfd_ship_number );
                $order->add_meta_data( 'hfd_rand_number', $hfd_rand_number );
                $order->add_order_note( sprintf( 'Sync to HFD successful, ship_create_num: %s', $hfd_ship_number ) );
            } else {
                $order->add_meta_data('hfd_sync_flag', \Hfd\Woocommerce\Helper\Hfd::STATUS_SEND_ERROR, true);
                $order->add_order_note(sprintf('Sync HFD fail, ship_create_error: %s', $result['message']));
                $errors[] = sprintf(__('Sync to HFD-------FAIL with order ID: %s. Message: %s', 'hfd-integration'), $order->get_id(), $result['message']);
            }

            $order->save_meta_data();
			do_action( 'hfd_after_save_metadata', $order, $result );
        }

        return array(
            'errors'        => $errors,
            'count'         => $count
        );
    }

    /**
     * @param \WC_Order $order
     * @return bool
     */
    public function validateShippingMethod($order)
    {
        $setting = Container::get('Hfd\Woocommerce\Setting');
        $allowShippingMethods = $setting->get('betanet_epost_hfd_shipping_method');
        $shippingMethods = $order->get_shipping_methods();

        $isAllowed = false;
        foreach ($allowShippingMethods as $allowShippingMethod) {
            /* @var \WC_Order_Item_Shipping $shippingMethod */
            foreach ($shippingMethods as $shippingMethod) {
                if (substr($shippingMethod->get_method_id(), 0, strlen($allowShippingMethod)) == $allowShippingMethod) {
                    $isAllowed = true;
                    break;
                }
            }

            if ($isAllowed) {
                break;
            }
        }

        return $isAllowed;
    }
}