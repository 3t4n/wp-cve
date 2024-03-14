<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:20 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Data;

use BeeketingConnect_beeketing_woocommerce\Common\Constants as CommonConstants;
use BeeketingConnect_beeketing_woocommerce\Common\Data\CommonHelper;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Exception\EntityNotFoundException;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Manager\OrderManagerAbstract;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Count;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Customer;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Order;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\OrderItem;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Helper;

class OrderManager extends OrderManagerAbstract
{

    private static $defaultOrderStatus = array('wc-pending', 'wc-processing', 'wc-on-hold');
    private static $cartTokens = array();
    private static $orderCustomers = array();
    private static $preLoaded = false;

    /**
     * @var ResourceManager
     */
    private $resourceManager;

    /**
     * OrderManager constructor.
     * @param ResourceManager $resourceManager
     */
    public function __construct(ResourceManager $resourceManager)
    {
        $this->resourceManager = $resourceManager;
    }

    /**
     * Get order
     * @param $arg
     * @return Order
     * @throws \Exception
     */
    public function get($arg)
    {
        $id = $arg['resource_id'];
        $order = wc_get_order($id);

        if ($order) {
            return $this->formatOrder($order);
        }

        throw new EntityNotFoundException(sprintf('Cant get order id %s', $id));
    }

    /**
     * Get many orders
     * @param $arg
     * @return array
     */
    public function getMany($arg)
    {
        $page = $arg['page'];
        $limit = $arg['limit'];
        $status = $arg['status'];

        // Get order status
        if ($status) {
            if ($status == 'any') {
                $orderStatus = array_keys(wc_get_order_statuses());
            } else {
                $orderStatus = $status;
            }
        } else {
            $orderStatus = self::$defaultOrderStatus;
        }

        $args = array(
            'fields' => 'ids',
            'post_type' => 'shop_order',
            'post_status' => $orderStatus,
            'posts_per_page' => $limit,
            'offset' => ($page - 1) * $limit,
        );

        $result = new \WP_Query($args);

        // Traverse all terms
        $orders = array();
        if (!is_wp_error($result)) {
            // Get cart tokens
            global $wpdb;
            $cartTokenResults = $wpdb->get_results(
                "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '" . CommonConstants::CART_TOKEN_KEY . "'"
            );
            foreach ($cartTokenResults as $cartTokenResult) {
                self::$cartTokens[$cartTokenResult->post_id] = $cartTokenResult->meta_value;
            }

            // Mark pre loaded data
            self::$preLoaded = true;

            if ($result->have_posts()) {
                // Get all order objects
                // wc_get_orders since wc 2.6.0
                if (version_compare(WOOCOMMERCE_VERSION, '2.6', '>=')) {
                    $wc_orders = wc_get_orders(array(
                        'include' => $result->posts,
                        'limit' => $limit,
                    ));
                    $wcOrdersObject = array();
                    $orderCustomerIds = array();
                    foreach ($wc_orders as $wc_order) {
                        $order_id = Helper::isWc3() ? $wc_order->get_id() : $wc_order->id;
                        $wcOrdersObject[$order_id] = $wc_order;

                        if ($wc_order->get_user()) {
                            $orderCustomerIds[] = $wc_order->get_user_id();
                        }
                    }

                    if ($orderCustomerIds) {
                        $orderCustomerIds = array_unique($orderCustomerIds);
                        self::$orderCustomers = $this->resourceManager->customerManager->getMany(array(
                            'ids' => $orderCustomerIds,
                            'page' => false,
                            'limit' => false,
                        ));
                    }
                }

                // Format each order
                foreach ($result->posts as $id) {
                    $order = isset($wcOrdersObject[$id]) ? $wcOrdersObject[$id] : wc_get_order($id);
                    $orders[] = $this->formatOrder($order);
                }
            }
        }

        return $orders;
    }

    /**
     * Count order by status
     * @param $arg
     * @return Count
     */
    public function count($arg)
    {
        $status = $arg['status'];

        // Get order status
        if ($status) {
            if ($status == 'any') {
                $orderStatus = array_keys(wc_get_order_statuses());
            } else {
                $orderStatus = $status;
            }
        } else {
            $orderStatus = self::$defaultOrderStatus;
        }

        $args = array(
            'fields' => 'ids',
            'post_type' => 'shop_order',
            'post_status' => $orderStatus,
            'posts_per_page' => -1,
        );

        $orders = new \WP_Query($args);

        if (!is_wp_error($orders)) {
            $count = $orders->post_count;
        } else {
            $count = 0;
        }

        return new Count($count);
    }

    /**
     * Format order
     *
     * @param \WC_Order $orderObj
     * @return Order
     */
    private function formatOrder($orderObj)
    {
        $currency = $totalTax = $totalDiscount = '';
        if (Helper::isWc3()) {
            $orderId = $orderObj->get_id();
            $orderEmail = $orderObj->get_billing_email();
            $orderDate = $orderObj->get_date_created();
            $orderDateModified = $orderObj->get_date_modified();
            $currency = $orderObj->get_currency();
            $totalTax = (float)$orderObj->get_total_tax();
            $totalDiscount = $orderObj->get_discount_total();
        } else {
            $orderId = $orderObj->id;
            $orderEmail = $orderObj->billing_email;
            $orderDate = $orderObj->order_date;
            $orderDateModified = $orderObj->modified_date;
        }

        if (self::$preLoaded) {
            $cartToken = isset(self::$cartTokens[$orderId]) ? self::$cartTokens[$orderId] : '';
        } else {
            $cartToken = get_post_meta($orderId, CommonConstants::CART_TOKEN_KEY, true);
        }

        $order = new Order();
        $order->id = $orderId;
        $order->email = $orderEmail;
        $order->financial_status = $orderObj->get_status();
        $order->fulfillment_status = '';
        $order->line_items = array();
        $order->cart_token = $cartToken;
        $order->currency = $currency;
        $order->name = '';
        $order->total_tax = $totalTax;
        $order->total_discounts = (float)$totalDiscount;
        $order->total_price = (float)$orderObj->get_total();
        $order->subtotal_price = (float)$orderObj->get_subtotal();
        $order->total_line_items_price = $orderObj->get_subtotal();
        $order->processed_at = CommonHelper::formatDate($orderDate);
        $order->cancelled_at = $orderObj->get_status() == 'cancelled'
            ? CommonHelper::formatDate($orderDateModified) : '';
        $order->note_attributes = array();
        $order->source_name = '';

        // Add contact info
        $orderCustomer = new Customer();
        if (Helper::isWc3()) {
            $orderCustomer->email = $orderObj->get_billing_email();
            $orderCustomer->first_name = $orderObj->get_billing_first_name();
            $orderCustomer->last_name = $orderObj->get_billing_last_name();
            $orderCustomer->address1 = $orderObj->get_billing_address_1();
            $orderCustomer->address2 = $orderObj->get_billing_address_2();
            $orderCustomer->city = $orderObj->get_billing_city();
            $orderCustomer->company = $orderObj->get_billing_company();
            $orderCustomer->province = $orderObj->get_billing_state();
            $orderCustomer->zip = $orderObj->get_billing_postcode();
            $orderCustomer->country = $orderObj->get_billing_country();
        } else {
            $orderCustomer->email = $orderObj->billing_email;
            $orderCustomer->first_name = $orderObj->billing_first_name;
            $orderCustomer->last_name = $orderObj->billing_last_name;
            $orderCustomer->address1 = $orderObj->billing_address_1;
            $orderCustomer->address2 = $orderObj->billing_address_2;
            $orderCustomer->city = $orderObj->billing_city;
            $orderCustomer->company = $orderObj->billing_company;
            $orderCustomer->province = $orderObj->billing_state;
            $orderCustomer->zip = $orderObj->billing_postcode;
            $orderCustomer->country = $orderObj->billing_country;
        }

        $overrideFields = array(
            'first_name',
            'last_name',
            'address1',
            'address2',
            'city',
            'province',
            'zip',
            'country'
        );
        $orderCustomer->country_code = '';
        $orderCustomer->signed_up_at = CommonHelper::formatDate($orderDate);
        $orderCustomer->accepts_marketing = true;
        $orderCustomer->verified_email = false;
        $orderCustomer->orders_count = 1;
        $orderCustomer->total_spent = floatval($orderObj->get_total());

        // If order belongs to an user
        if ($orderObj->get_user()) {
            if (isset(self::$orderCustomers[$orderObj->get_user_id()])) {
                $contact = self::$orderCustomers[$orderObj->get_user_id()];
            } else {
                try {
                    $contact = $this->resourceManager->customerManager->get(array('resource_id' => $orderObj->get_user_id()));
                } catch (\Exception $e) {
                    // In case not found
                    $contact = null;
                }
            }

            if ($contact) {
                if ($contact->email === $orderCustomer->email) {
                    foreach ($overrideFields as $field) {
                        $contact->{$field} = $orderCustomer->{$field};
                    }
                }
                $order->customer = $contact;
            }
        } else {
            $order->customer = $orderCustomer;
        }

        $order->billing_address['first_name'] = $orderObj->get_billing_first_name();
        $order->billing_address['last_name']  = $orderObj->get_billing_last_name();
        $order->billing_address['address1']   = $orderObj->get_billing_address_1();
        $order->billing_address['address2']   = $orderObj->get_billing_address_2();
        $order->billing_address['city']       = $orderObj->get_billing_city();
        $order->billing_address['zip']        = $orderObj->get_billing_postcode();
        $order->billing_address['country']    = $orderObj->get_billing_country();

        // Add line items
        /**
         * @var int $itemId
         * @var \WC_Order_Item_Product $item
         */
        foreach ($orderObj->get_items() as $itemId => $item) {
            if (Helper::isWc3()) {
                $product = $item->get_product();
                $variant_id = $item->get_variation_id();
                $product_id = $item->get_product_id();
                $tax = (float)$item->get_total_tax();
                $quantity = $item->get_quantity();
            } else {
                $product = $orderObj->get_product_from_item($item);
                $variant_id = isset($product->variation_id) ? $product->variation_id : null;
                $product_id = $product->id;
                $tax = $item['line_tax'];
                $quantity = $item->qty;
            }

            $productSku = null;
            // Check if the product exists.
            if (is_object($product)) {
                $productSku = $product->get_sku();
            }

            // Price
            $price = (float)$orderObj->get_item_total($item, false, false);

            $item = new OrderItem();
            $item->id = $itemId;
            $item->title = $item->name;
            $item->price = $price;
            $item->line_price = $price;
            $item->sku = $productSku;
            $item->requires_shipping = '';
            $item->taxable = $tax;
            $item->product_id = $product_id;
            $item->variant_id = $variant_id ?: $product_id;
            $item->vendor = '';
            $item->name = $product ? $product->get_title() : '';
            $item->fulfillable_quantity = wc_stock_amount($quantity);
            $item->fulfillment_service = '';
            $item->fulfillment_status = '';
            $order->line_items[] = $item;
        }

        return $order;
    }

}