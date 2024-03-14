<?php

namespace S2WPImporter\Process;

use S2WPImporter\VariationsLog;
use WC_Order;
use WC_Order_Refund;

class Order extends AbstractRecord implements IRecord
{
    /**
     * @var $item = [
     *     "id" => 3828313227430,
     *     "admin_graphql_api_id" => "gid://shopify/Order/3828313227430",
     *     "app_id" => 1608003,
     *     "browser_ip" => null,
     *     "buyer_accepts_marketing" => false,
     *     "cancel_reason" => null,
     *     "cancelled_at" => null,
     *     "cart_token" => null,
     *     "checkout_id" => null,
     *     "checkout_token" => null,
     *     "closed_at" => null,
     *     "confirmed" => true,
     *     "contact_email" => "egnition_sample_19@egnition.com",
     *     "created_at" => "2021-05-12T13:45:34+03:00",
     *     "currency" => "USD",
     *     "current_subtotal_price" => "0.30",
     *     "current_subtotal_price_set" => [
     *         "shop_money" => [
     *             "amount" => "0.30",
     *             "currency_code" => "USD",
     *         ],
     *         "presentment_money" => [
     *             "amount" => "0.30",
     *             "currency_code" => "USD",
     *         ],
     *     ],
     *     "current_total_discounts" => "0.00",
     *     "current_total_discounts_set" => [
     *         "shop_money" => [
     *             "amount" => "0.00",
     *             "currency_code" => "USD",
     *         ],
     *         "presentment_money" => [
     *             "amount" => "0.00",
     *             "currency_code" => "USD",
     *         ],
     *     ],
     *     "current_total_duties_set" => null,
     *     "current_total_price" => "0.30",
     *     "current_total_price_set" => [
     *         "shop_money" => [
     *             "amount" => "0.30",
     *             "currency_code" => "USD",
     *         ],
     *         "presentment_money" => [
     *             "amount" => "0.30",
     *             "currency_code" => "USD",
     *         ],
     *     ],
     *     "current_total_tax" => "0.00",
     *     "current_total_tax_set" => [
     *         "shop_money" => [
     *             "amount" => "0.00",
     *             "currency_code" => "USD",
     *         ],
     *         "presentment_money" => [
     *             "amount" => "0.00",
     *             "currency_code" => "USD",
     *         ],
     *     ],
     *     "customer_locale" => null,
     *     "device_id" => null,
     *     "discount_codes" => [],
     *     "email" => "egnition_sample_19@egnition.com",
     *     "financial_status" => "pending",
     *     "fulfillment_status" => null,
     *     "gateway" => "",
     *     "landing_site" => null,
     *     "landing_site_ref" => null,
     *     "location_id" => null,
     *     "name" => "#1020",
     *     "note" => null,
     *     "note_attributes" => [],
     *     "number" => 20,
     *     "order_number" => 1020,
     *     "order_status_url" =>
     *     "https://s2wp.myshopify.com/56789336230/orders/fb680121a30a6d89e0486d9489ad235d/authenticate?key=cf82b48f070b7561d51f51230f02f3d3",
     *     "original_total_duties_set" => null,
     *     "payment_gateway_names" => [],
     *     "phone" => null,
     *     "presentment_currency" => "USD",
     *     "processed_at" => "2021-05-12T13:45:34+03:00",
     *     "processing_method" => "",
     *     "reference" => null,
     *     "referring_site" => null,
     *     "source_identifier" => null,
     *     "source_name" => "1608003",
     *     "source_url" => null,
     *     "subtotal_price" => "0.30",
     *     "subtotal_price_set" => [
     *         "shop_money" => [
     *             "amount" => "0.30",
     *             "currency_code" => "USD",
     *         ],
     *         "presentment_money" => [
     *             "amount" => "0.30",
     *             "currency_code" => "USD",
     *         ],
     *     ],
     *     "tags" => "egnition-sample-data",
     *     "tax_lines" => [],
     *     "taxes_included" => false,
     *     "test" => false,
     *     "token" => "fb680121a30a6d89e0486d9489ad235d",
     *     "total_discounts" => "0.00",
     *     "total_discounts_set" => [
     *         "shop_money" => [
     *             "amount" => "0.00",
     *             "currency_code" => "USD",
     *         ],
     *         "presentment_money" => [
     *             "amount" => "0.00",
     *             "currency_code" => "USD",
     *         ],
     *     ],
     *     "total_line_items_price" => "0.30",
     *     "total_line_items_price_set" => [
     *         "shop_money" => [
     *             "amount" => "0.30",
     *             "currency_code" => "USD",
     *         ],
     *         "presentment_money" => [
     *             "amount" => "0.30",
     *             "currency_code" => "USD",
     *         ],
     *     ],
     *     "total_outstanding" => "0.30",
     *     "total_price" => "0.30",
     *     "total_price_set" => [
     *         "shop_money" => [
     *             "amount" => "0.30",
     *             "currency_code" => "USD",
     *         ],
     *         "presentment_money" => [
     *             "amount" => "0.30",
     *             "currency_code" => "USD",
     *         ],
     *     ],
     *     "total_price_usd" => "0.02",
     *     "total_shipping_price_set" => [
     *         "shop_money" => [
     *             "amount" => "0.00",
     *             "currency_code" => "USD",
     *         ],
     *         "presentment_money" => [
     *             "amount" => "0.00",
     *             "currency_code" => "USD",
     *         ],
     *     ],
     *     "total_tax" => "0.00",
     *     "total_tax_set" => [
     *         "shop_money" => [
     *             "amount" => "0.00",
     *             "currency_code" => "USD",
     *         ],
     *         "presentment_money" => [
     *             "amount" => "0.00",
     *             "currency_code" => "USD",
     *         ],
     *     ],
     *     "total_tip_received" => "0.00",
     *     "total_weight" => 0,
     *     "updated_at" => "2021-05-12T13:45:35+03:00",
     *     "user_id" => null,
     *     "billing_address" => [
     *         "first_name" => "Cedric",
     *         "address1" => "1768 Fusce St.",
     *         "phone" => "+256312345648",
     *         "city" => "Burlington",
     *         "zip" => "39244",
     *         "province" => "Vermont",
     *         "country" => "Uganda",
     *         "last_name" => "Cochran",
     *         "address2" => null,
     *         "company" => null,
     *         "latitude" => null,
     *         "longitude" => null,
     *         "name" => "Cedric Cochran",
     *         "country_code" => "UG",
     *         "province_code" => null,
     *     ],
     *     "customer" => [
     *         "id" => 5275122368678,
     *         "email" => "egnition_sample_19@egnition.com",
     *         "accepts_marketing" => false,
     *         "created_at" => "2021-05-12T13:40:31+03:00",
     *         "updated_at" => "2021-05-12T13:45:34+03:00",
     *         "first_name" => "Cedric",
     *         "last_name" => "Cochran",
     *         "orders_count" => 2,
     *         "state" => "disabled",
     *         "total_spent" => "0.30",
     *         "last_order_id" => 3828313227430,
     *         "note" => null,
     *         "verified_email" => true,
     *         "multipass_identifier" => null,
     *         "tax_exempt" => false,
     *         "phone" => "+256312345648",
     *         "tags" => "egnition-sample-data, VIP",
     *         "last_order_name" => "#1020",
     *         "currency" => "USD",
     *         "accepts_marketing_updated_at" => "2021-05-12T13:40:31+03:00",
     *         "marketing_opt_in_level" => null,
     *         "tax_exemptions" => [],
     *         "admin_graphql_api_id" => "gid://shopify/Customer/5275122368678",
     *         "default_address" => [
     *             "id" => 6447112880294,
     *             "customer_id" => 5275122368678,
     *             "first_name" => "Cedric",
     *             "last_name" => "Cochran",
     *             "company" => null,
     *             "address1" => "1768 Fusce St.",
     *             "address2" => null,
     *             "city" => "Burlington",
     *             "province" => "Vermont",
     *             "country" => "Uganda",
     *             "zip" => "39244",
     *             "phone" => "+256312345648",
     *             "name" => "Cedric Cochran",
     *             "province_code" => null,
     *             "country_code" => "UG",
     *             "country_name" => "Uganda",
     *             "default" => true,
     *         ],
     *     ],
     *     "discount_applications" => [],
     *     "fulfillments" => [],
     *     "line_items" => [
     *         [
     *             "id" => 9932399378598,
     *             "admin_graphql_api_id" => "gid://shopify/LineItem/9932399378598",
     *             "fulfillable_quantity" => 1,
     *             "fulfillment_service" => "manual",
     *             "fulfillment_status" => null,
     *             "gift_card" => false,
     *             "grams" => 0,
     *             "name" => "DR MARTENS | 1460Z DMC 8-EYE BOOT | CHERRY SMOOTH - 4 / red",
     *             "price" => "0.10",
     *             "price_set" => [
     *                 "shop_money" => [
     *                     "amount" => "0.10",
     *                     "currency_code" => "USD",
     *                 ],
     *                 "presentment_money" => [
     *                     "amount" => "0.10",
     *                     "currency_code" => "USD",
     *                 ],
     *             ],
     *             "product_exists" => true,
     *             "product_id" => 6765124124838,
     *             "properties" => [],
     *             "quantity" => 1,
     *             "requires_shipping" => true,
     *             "sku" => "DM-03-red-4",
     *             "taxable" => true,
     *             "title" => "DR MARTENS | 1460Z DMC 8-EYE BOOT | CHERRY SMOOTH",
     *             "total_discount" => "0.00",
     *             "total_discount_set" => [
     *                 "shop_money" => [
     *                     "amount" => "0.00",
     *                     "currency_code" => "USD",
     *                 ],
     *                 "presentment_money" => [
     *                     "amount" => "0.00",
     *                     "currency_code" => "USD",
     *                 ],
     *             ],
     *             "variant_id" => 40057719292070,
     *             "variant_inventory_management" => "shopify",
     *             "variant_title" => "4 / red",
     *             "vendor" => "DR MARTENS",
     *             "tax_lines" => [],
     *             "duties" => [],
     *             "discount_allocations" => [],
     *         ],
     *         [
     *             "id" => 9932399411366,
     *             "admin_graphql_api_id" => "gid://shopify/LineItem/9932399411366",
     *             "fulfillable_quantity" => 1,
     *             "fulfillment_service" => "manual",
     *             "fulfillment_status" => null,
     *             "gift_card" => false,
     *             "grams" => 0,
     *             "name" => "VANS | SH-8 HI - 4 / black",
     *             "price" => "0.10",
     *             "price_set" => [
     *                 "shop_money" => [
     *                     "amount" => "0.10",
     *                     "currency_code" => "USD",
     *                 ],
     *                 "presentment_money" => [
     *                     "amount" => "0.10",
     *                     "currency_code" => "USD",
     *                 ],
     *             ],
     *             "product_exists" => true,
     *             "product_id" => 6765122093222,
     *             "properties" => [],
     *             "quantity" => 1,
     *             "requires_shipping" => true,
     *             "sku" => "VN-07-black-4",
     *             "taxable" => true,
     *             "title" => "VANS | SH-8 HI",
     *             "total_discount" => "0.00",
     *             "total_discount_set" => [
     *                 "shop_money" => [
     *                     "amount" => "0.00",
     *                     "currency_code" => "USD",
     *                 ],
     *                 "presentment_money" => [
     *                     "amount" => "0.00",
     *                     "currency_code" => "USD",
     *                 ],
     *             ],
     *             "variant_id" => 40057714278566,
     *             "variant_inventory_management" => "shopify",
     *             "variant_title" => "4 / black",
     *             "vendor" => "VANS",
     *             "tax_lines" => [],
     *             "duties" => [],
     *             "discount_allocations" => [],
     *         ],
     *         [
     *             "id" => 9932399444134,
     *             "admin_graphql_api_id" => "gid://shopify/LineItem/9932399444134",
     *             "fulfillable_quantity" => 1,
     *             "fulfillment_service" => "manual",
     *             "fulfillment_status" => null,
     *             "gift_card" => false,
     *             "grams" => 0,
     *             "name" => "VANS APPAREL AND ACCESSORIES | CLASSIC SUPER NO SHOW SOCKS 3 PACK WHITE - 9.5-13 /
     *     white",
     *             "price" => "0.10",
     *             "price_set" => [
     *                 "shop_money" => [
     *                     "amount" => "0.10",
     *                     "currency_code" => "USD",
     *                 ],
     *                 "presentment_money" => [
     *                     "amount" => "0.10",
     *                     "currency_code" => "USD",
     *                 ],
     *             ],
     *             "product_exists" => true,
     *             "product_id" => 6765125730470,
     *             "properties" => [],
     *             "quantity" => 1,
     *             "requires_shipping" => true,
     *             "sku" => "VN-10-white-9.5-13",
     *             "taxable" => true,
     *             "title" => "VANS APPAREL AND ACCESSORIES | CLASSIC SUPER NO SHOW SOCKS 3 PACK WHITE",
     *             "total_discount" => "0.00",
     *             "total_discount_set" => [
     *                 "shop_money" => [
     *                     "amount" => "0.00",
     *                     "currency_code" => "USD",
     *                 ],
     *                 "presentment_money" => [
     *                     "amount" => "0.00",
     *                     "currency_code" => "USD",
     *                 ],
     *             ],
     *             "variant_id" => 40057722863782,
     *             "variant_inventory_management" => "shopify",
     *             "variant_title" => "9.5-13 / white",
     *             "vendor" => "VANS",
     *             "tax_lines" => [],
     *             "duties" => [],
     *             "discount_allocations" => [],
     *         ],
     *     ],
     *     "refunds" => [],
     *     "shipping_address" => [
     *         "first_name" => "Cedric",
     *         "address1" => "1768 Fusce St.",
     *         "phone" => "+256312345648",
     *         "city" => "Burlington",
     *         "zip" => "39244",
     *         "province" => "Vermont",
     *         "country" => "Uganda",
     *         "last_name" => "Cochran",
     *         "address2" => null,
     *         "company" => null,
     *         "latitude" => null,
     *         "longitude" => null,
     *         "name" => "Cedric Cochran",
     *         "country_code" => "UG",
     *         "province_code" => null,
     *     ],
     *     "shipping_lines" => [],
     * ]
     */
    protected $item;

    /**
     * @var WC_Order
     */
    protected $order;

    const WC_STATUSES = [
        'pending',
        'processing',
        'on-hold',
        'completed',
        'cancelled',
        'refunded',
        'failed',
    ];

    const STATUSES_MAP = [
        'pending' => 'pending', // The payments are pending. Payment might fail in this state. Check again to confirm whether the payments have been paid successfully.
        'authorized' => 'on-hold', // The payments have been authorized.
        'partially_paid' => 'on-hold', // The order has been partially paid.
        'paid' => 'processing',// The payments have been paid.
        'partially_refunded' => 'refunded', // The payments have been partially refunded.
        'refunded' => 'refunded', // The payments have been refunded.
        'voided' => 'cancelled', // The payments have been voided.
    ];

    /**
     * Order constructor.
     *
     * @param                          $item = $this->item
     * @param WC_Order                 $order
     */
    public function __construct($item, $order)
    {
        $this->item = $item;
        $this->order = $order;
    }

    public function parse()
    {
        $o = $this->order;
        $i = $this->item;

        try {
            $billingEmail = $i['email'] ?? ($i['contact_email'] ?? null);

            if (!empty($billingEmail)) {
                $o->set_billing_email(sanitize_email($billingEmail));
            }

            $this->setStatus();

            if (!empty($i['currency'])) {
                $o->set_currency(sanitize_text_field($i['currency']));
            }

            $o->set_prices_include_tax(!empty($i['taxes_included']));

            $o->set_date_created(sanitize_text_field($i['created_at']));
            $o->set_date_modified(sanitize_text_field($i['created_at']));

            // TODO: Set customer ID based on the previously imported data (customers)
            // $o->set_customer_id(0);

            $o->set_total(
                wc_format_decimal(!empty($i['current_subtotal_price']) ? $i['current_subtotal_price'] : 0)
            );

            $this->setAddress('billing');
            $this->setAddress('shipping');

            if (!empty($i['customer']['email'])) {
                $user = get_user_by('email', $i['customer']['email']);

                if ($user instanceof \WP_User && !empty($user->ID)) {
                    $o->set_customer_id((int)$user->ID);
                }
            }

            // Some useful records for future use
            $o->set_created_via('Shopify to WordPress Importer');
            $o->update_meta_data('shopifytowp_item_data', $i);
        }
        catch (\WC_Data_Exception $exception) {
            $this->addError($exception->getMessage() . ' || ' . $exception->getTraceAsString());
        }
    }

    public function save()
    {
        return $this->order->save();
    }

    public function afterSave($orderId)
    {
        if (empty($orderId)) {
            return;
        }

        // Normal Order Modifications
        // ----------------------------------------------------------------------------
        $normalOrder = new WC_Order($orderId);
        $normalOrder->add_order_note(
            sprintf(
            /* translators: %s original Shopify order number. */
                __('Imported from Shopify where the order number was #%1$s', 'import-shopify-to-wp'),
                $this->item['order_number']
            )
        );

        // Mark as completed if needed is fulfilled on Shopify
        // ----------------------------------------------------------------------------
        if ($this->item['financial_status'] === 'paid' && $this->item['fulfillment_status'] === 'fulfilled') {
            $normalOrder->set_status('completed');
        }

        $normalOrder->save();

        if (!empty($this->item['line_items']) && !empty($normalOrder->get_id())) {
            foreach ($this->item['line_items'] as $lineItem) {
                $newVariationId = (new VariationsLog())->getNewId((int)$lineItem['variant_id']);
                $variation = new \WC_Product_Variation($newVariationId);

//                $this->addSoftError("+VID: ". $variation->get_id() . " +VPID:". $variation->get_parent_id());

                if (!empty($variation->get_id()) && !empty($variation->get_parent_id())) {
//                    $this->addSoftError("VID: ". $variation->get_id() . " VPID:". $variation->get_parent_id());
                    try {
                        $lineProduct = new \WC_Order_Item_Product();
                        $lineProduct->set_order_id($normalOrder->get_id()); // Order ID this item belongs to.
                        $lineProduct->set_product_id($variation->get_parent_id()); // Main product ID
                        $lineProduct->set_variation_id($variation->get_id()); // Variation ID. In Shopify all products have variations

                        $lineProduct->set_name(sanitize_text_field($lineItem['name']));

                        $lineProduct->set_quantity((int)$lineItem['quantity']);
                        $lineProduct->set_subtotal(wc_format_decimal((float)$lineItem['price']));
                        $lineProduct->set_total(wc_format_decimal((float)$lineItem['price'] - (float)$lineItem['total_discount']));

                        $oid = $lineProduct->save();
//                        $this->addSoftError("OID: ". $oid);
                    }
                    catch (\WC_Data_Exception $exception) {
                        $this->addError($exception->getMessage());
                    }
                }
            }
        }

        // Refunded Order Modifications
        // ----------------------------------------------------------------------------
        if (!empty($this->item['refunds']) || $this->item['financial_status'] === 'refunded') {
            $refundedOrder = new WC_Order_Refund($orderId);

            try {
                $refundedOrder->set_reason(
                    !empty($i['refunds'][0]['note']) ? sanitize_text_field($i['refunds'][0]['note']) : __('Unknown refund reason', 'import-shopify-to-wp')
                );

                $refundedOrder->save();
            }
            catch (\WC_Data_Exception $e) {
                $this->addError($e->getMessage());
            }
        }
    }

    /*
    -------------------------------------------------------------------------------
    Internal
    -------------------------------------------------------------------------------
    */
    protected function setStatus()
    {
        if (isset(self::STATUSES_MAP[$this->item['financial_status']])) {
            $this->order->set_status(self::STATUSES_MAP[$this->item['financial_status']]);
        }
    }

    protected function setAddress($type)
    {
        $this->order->{"set_{$type}_first_name"}(sanitize_text_field($this->item["{$type}_address"]['first_name']) ?? null);
        $this->order->{"set_{$type}_last_name"}(sanitize_text_field($this->item["{$type}_address"]['last_name']) ?? null);

        $this->order->{"set_{$type}_company"}(sanitize_text_field($this->item["{$type}_address"]['company']) ?? null);

        $this->order->{"set_{$type}_address_1"}(sanitize_text_field($this->item["{$type}_address"]['address1']) ?? null);
        $this->order->{"set_{$type}_address_2"}(sanitize_text_field($this->item["{$type}_address"]['address2']) ?? null);

        $this->order->{"set_{$type}_country"}(sanitize_text_field($this->item["{$type}_address"]['country_code']) ?? null);
        $this->order->{"set_{$type}_state"}(sanitize_text_field($this->item["{$type}_address"]['province']) ?? null);
        $this->order->{"set_{$type}_city"}(sanitize_text_field($this->item["{$type}_address"]['city']) ?? null);
        $this->order->{"set_{$type}_postcode"}(sanitize_text_field($this->item["{$type}_address"]['zip']) ?? null);

        if ($type === 'billing') {
            $this->order->{"set_{$type}_phone"}(sanitize_text_field($this->item["{$type}_address"]['phone']) ?? null);

            $billingEmail = $this->item["{$type}_address"]['email'] ?? ($this->item['contact_email'] ?? null);

            if (!empty($billingEmail)) {
                $this->order->{"set_{$type}_email"}(sanitize_email($billingEmail));
            }
        }
    }

}
