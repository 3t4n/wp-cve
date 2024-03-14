<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Sync\Jobs;

use Exception;
use GoDaddy\WooCommerce\Poynt\API\GatewayAPI;
use GoDaddy\WooCommerce\Poynt\Helpers\ArrayHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\MoneyHelper;
use GoDaddy\WooCommerce\Poynt\Helpers\PoyntHelper;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;
use WC_Order;
use WC_Order_Item_Product;

/**
 * Push Orders Job.
 *
 * @since 1.3.0
 */
class PushOrdersJob
{
    /** @var string pickup mode */
    const PICKUP_MODE = 'PICKUP';

    /** @var string delivery mode */
    const DELIVERY_MODE = 'DELIVERY';

    /** @var string pickup_instore item */
    const PICKUP_ITEM = 'PICKUP_INSTORE';

    /** @var string ship_to item */
    const SHIP_ITEM = 'SHIP_TO';

    /** @var string Local Delivery shipping method id */
    const LOCAL_DELIVERY_METHOD = 'gdp_local_delivery';

    /** @var WC_Order instance */
    protected $order;

    /**
     * Push Poynt order constructor.
     *
     * @since 1.3.0
     *
     * @param WC_Order $order instance
     * @throws Exception
     */
    public function __construct(WC_Order $order)
    {
        $this->order = $order;
        $this->pushOrderToPoynt();
    }

    /**
     * Push order using the Poynt API.
     *
     * @since 1.3.0
     *
     * @throws Exception
     * @return void
     */
    protected function pushOrderToPoynt()
    {
        $businessId = PoyntHelper::getBusinessId();
        $appId = PoyntHelper::getAppId();
        $privateKey = PoyntHelper::getPrivateKey();
        $environment = PoyntHelper::getEnvironment();

        // ensure we have the minimum requirements to be connected to the API
        if (! $businessId || ! $appId || ! $privateKey) {
            return;
        }

        $api = new GatewayAPI($appId, $businessId, $privateKey, $environment);

        $body = $this->buildCreateOrderBody();

        try {
            $response = $api->pushNewOrder($body);

            if ($poyntOrderId = $response->getOrderId()) {
                $this->order->update_meta_data('_wc_poynt_order_remoteId', $poyntOrderId);
                $this->order->save_meta_data();
            } else {
                poynt_for_woocommerce()->log('Failed to get the Poynt Order ID for WC Order #'.$this->order->get_id());
            }
        } catch (Framework\SV_WC_API_Exception $e) {
            poynt_for_woocommerce()->log('Could not send the create order request to Poynt for WC Order #'.$this->order->get_id());
        }
    }

    /**
     * Created request body for Poynt Create Order request.
     *
     * @since 1.3.0
     *
     * @return array<string, mixed> request body to create an order
     * @throws Exception
     */
    protected function buildCreateOrderBody() : array
    {
        $isLocalDelivery = $this->order->has_shipping_method(static::LOCAL_DELIVERY_METHOD);

        $body = [
            'items'          => $this->buildOrderBodyLineItems($this->order->get_items('line_item'), $isLocalDelivery),
            'orderShipments' => [
                [
                    'deliveryMode' => $isLocalDelivery ? static::DELIVERY_MODE : static::PICKUP_MODE,
                    'status'       => 'NONE',
                    'shipmentType' => 'FULFILLMENT',
                    'address'      => $isLocalDelivery ? $this->buildShippingAddress($this->order) : null,
                ],
            ],
            'amounts' => [
                'subTotal'      => MoneyHelper::convertDecimalToCents($this->order->get_subtotal()),
                'currency'      => $this->order->get_currency(),
                'taxTotal'      => MoneyHelper::convertDecimalToCents($this->order->get_total_tax()),
                'feeTotal'      => MoneyHelper::convertDecimalToCents($this->getFeeTotalWithShipping()),
                'discountTotal' => MoneyHelper::convertDecimalToCents($this->getDiscountTotal()),
            ],
            'context' => [
                'source'     => 'WEB',
                'businessId' => PoyntHelper::getBusinessId(),
            ],
            'statuses' => [
                'status' => 'OPENED',
            ],
            'accepted'    => true,
            'orderNumber' => $this->order->get_order_number(),
            'notes'       => $this->buildOrderNotes(),
            'customer'    => [
                'emails' => [
                    'PERSONAL' => [
                        'emailAddress' => $this->order->get_billing_email(),
                    ],
                ],
                'firstName' => $this->order->get_billing_first_name(),
                'lastName'  => $this->order->get_billing_last_name(),
                'phones'    => [
                    'MOBILE' => [
                        'localPhoneNumber' => $this->order->get_billing_phone(),
                    ],
                ],
            ],
        ];

        // For GoDaddy Payment orders, the order ID is generated when creating
        // the transaction and passed to the order create request here
        $poyntOrderId = $this->order->get_meta('_wc_poynt_order_remoteId');
        if ($poyntOrderId) {
            $body['id'] = $poyntOrderId;
        }

        return $body;
    }

    /**
     * Gets the full amount of all fees in this order including shipping fees.
     *
     * This method is necessary given that Fee Amount doesn't include shipping fees.
     *
     * @since 1.3.0
     *
     * @return int|float
     */
    protected function getFeeTotalWithShipping() : float
    {
        return
            $this->getFeeItemsTotal()
            + ($this->order->get_shipping_total() ?? 0);
    }

    /**
     * Gets the total amount of all the fees items by type in the given order.
     *
     * Returns the sum of positive and negative fee items in this order.
     *
     * @since 1.3.0
     *
     * @param bool $hasNegativeFee should return all the fee items with negative values. Default is false.
     * @return float|int
     */
    protected function getFeeItemsTotalByType($hasNegativeFee = false) : float
    {
        $sum = 0;

        foreach (ArrayHelper::wrap($this->order->get_fees()) as $item) {
            $amount = $item->get_total();

            if ($hasNegativeFee && ($amount < 0)) {
                $sum += abs($amount);
            } elseif (! $hasNegativeFee && ($amount > 0)) {
                $sum += $amount;
            }
        }

        return $sum;
    }

    /**
     * Returns the total amount of negative-value fees items in this order.
     *
     * @since 1.3.0
     *
     * @return float|int
     */
    protected function getNegativeFeeItemsTotal() : float
    {
        return $this->getFeeItemsTotalByType(true);
    }

    /**
     * Returns the total amount of positive-value fees items in this order.
     *
     * @since 1.3.0
     *
     * @return float|int
     */
    protected function getFeeItemsTotal() : float
    {
        return $this->getFeeItemsTotalByType(false);
    }

    /**
     * Returns the total amount of discounts in this order.
     *
     * @snce 1.3.0
     *
     * @return float|int
     */
    protected function getDiscountTotal() : float
    {
        $discountAmount = $this->order->get_discount_total();

        return -1 * ($this->getNegativeFeeItemsTotal() + $discountAmount);
    }

    /**
     * Build Poynt Order line items object.
     *
     * @since 1.3.0
     *
     * @param WC_Order_Item_Product[] $lineItems
     * @param bool $isLocalDelivery
     * @throws Exception
     * @return array
     */
    protected function buildOrderBodyLineItems(array $lineItems, bool $isLocalDelivery) : array
    {
        $poyntLineItems = [];

        foreach ($lineItems as $item) {
            $product = $item->get_product();

            $poyntLineItems[] = [
                'status'                 => 'ORDERED',
                'fulfillmentInstruction' => $isLocalDelivery ? static::SHIP_ITEM : static::PICKUP_ITEM,
                'name'                   => $item->get_name(),
                'clientNotes'            => $this->buildItemClientNotes($item),
                'unitOfMeasure'          => 'EACH',
                'sku'                    => $product->get_sku(),
                'unitPrice'              => MoneyHelper::convertDecimalToCents($item->get_subtotal() / $item->get_quantity()),
                'tax'                    => MoneyHelper::convertDecimalToCents((float) $item->get_subtotal_tax()),
                'quantity'               => $item->get_quantity(),
            ];
        }

        return $poyntLineItems;
    }

    /**
     * Builds order items client notes for adding attributes/variations.
     *
     * @since 1.3.0
     *
     * @param WC_Order_Item_Product $item
     * @throws Exception
     * @return string
     */
    protected function buildItemClientNotes(WC_Order_Item_Product $item) : string
    {
        $notes = '';

        foreach ($item->get_formatted_meta_data() as $meta) {
            // use wp_strip_all_tags() to strip out the <p> tags that get_formatted_meta_data() annoyingly injects
            $notes .= "\n{$meta->display_key}: ".wp_strip_all_tags($meta->display_value);
        }

        return trim($notes);
    }

    /**
     * Builds order shipping address for Local Delivery type orders.
     *
     * @since 1.3.0
     *
     * @throws Exception
     * @return array<string, string>|null
     */
    protected function buildShippingAddress()
    {
        return [
            'city'        => $this->order->get_shipping_city(),
            'countryCode' => $this->order->get_shipping_country(),
            'line1'       => $this->order->get_shipping_address_1(),
            'line2'       => $this->order->get_shipping_address_2(),
            'postalCode'  => $this->order->get_shipping_postcode(),
            'territory'   => $this->order->get_shipping_state(),
        ];
    }

    /**
     * Gets the order notes, appending LPP instructions, if applicable.
     *
     * @since 1.3.0
     *
     * @return string
     */
    protected function buildOrderNotes() : string
    {
        $notes = $this->order->get_customer_note() ?? '';

        if (function_exists('wc_local_pickup_plus')) {
            $pickupData = wc_local_pickup_plus()->get_orders_instance()->get_order_pickup_data($this->order->get_id());

            if (! empty($pickupData)) {
                $template = 'emails/plain/order-pickup-details.php';
                ob_start();
                wc_get_template($template, [
                    'order'           => $this->order,
                    'pickup_data'     => $pickupData,
                    'shipping_method' => wc_local_pickup_plus_shipping_method(),
                    'sent_to_admin'   => false,
                ], '', wc_local_pickup_plus()->get_plugin_path().'/templates/');

                $notes .= str_replace('&times;', 'x', ob_get_clean());
            }
        }

        return $notes;
    }
}
