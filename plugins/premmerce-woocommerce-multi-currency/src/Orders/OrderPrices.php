<?php namespace Premmerce\WoocommerceMulticurrency\Orders;

use Premmerce\WoocommerceMulticurrency\Frontend\UserCurrencyHandler;
use Premmerce\WoocommerceMulticurrency\Frontend\UserPricesHandler;
use Premmerce\WoocommerceMulticurrency\Model\Model;

/**
 * Class OrderPriceFormatter
 * @package Premmerce\WoocommerceMulticurrency\Orders
 *
 */
class OrderPrices
{
    /**
     * @var array
     */
    private $priceArgsForOrderDisplay = array();

    /**
     * @var string
     */
    private $priceSymbolForOrderDisplay = '';

    /**
     * @var UserCurrencyHandler $userCurrencyHandler
     */
    private $userCurrencyHandler;

    /**
     * @var UserPricesHandler
     */
    private $userPricesHandler;

    /**
     * @var Model
     */
    private $model;

    /**
     * OrderPriceFormatter constructor.
     * @param Model $model
     * @param UserCurrencyHandler $userCurrencyHandler
     * @param UserPricesHandler $userPricesHandler
     */
    public function __construct(Model $model, UserCurrencyHandler $userCurrencyHandler, UserPricesHandler $userPricesHandler)
    {
        $this->model = $model;
        $this->userCurrencyHandler = $userCurrencyHandler;
        $this->userPricesHandler = $userPricesHandler;

        /**
         * Re-format order prices lines like subtotal, total, prices etc.
         *
         */
        add_filter('woocommerce_order_formatted_line_subtotal', array($this, 'formatOrderLineSubtotal'), 10, 4);
        add_filter('woocommerce_get_order_item_totals', array($this, 'formatOrderItemTotals'), 10, 3);
        add_filter('woocommerce_get_formatted_order_total', array($this, 'formatOrderTotals'), 10, 4);


        add_filter('woocommerce_admin_order_item_headers', array($this, 'replaceCurrencyParams'));


        //Write order currency id to DB
        add_action('woocommerce_new_order', array($this, 'addOrderCurrencyId'));

        add_action('woocommerce_new_order_item', array($this, 'adjustOrderItemPrice'), 10, 3);
    }

    /**
     * @param           $formattedTotal
     * @param \WC_Order $order
     * @param string $taxDisplay
     * @param bool $displayRefunded
     *
     * @return string
     */
    public function formatOrderTotals($formattedTotal, \WC_Order $order, $taxDisplay, $displayRefunded)
    {
        $args = array(
            'tax_display' => $taxDisplay,
            'display_refunded' => $displayRefunded
        );

        return $this->formatOrderPriceLines($formattedTotal, $order, $args);
    }

    /**
     * @param array $formattedFields
     * @param \WC_Order $order
     * @param string $taxDisplay
     *
     * @return array
     */
    public function formatOrderItemTotals($formattedFields, \WC_Order $order, $taxDisplay)
    {
        $args['tax_display'] = $taxDisplay;
        return $this->formatOrderPriceLines($formattedFields, $order, $args);
    }

    /**
     * @param array $subtotal
     * @param \WC_Order_Item $item
     * @param \WC_Order $order
     *
     * @return array
     */
    public function formatOrderLineSubtotal($subtotal, \WC_Order_Item $item, \WC_Order $order)
    {
        $args['item'] = $item;
        return $this->formatOrderPriceLines($subtotal, $order, $args);
    }

    /**
     * @param $orderId
     */
    public function addOrderCurrencyId($orderId)
    {
        $order = wc_get_order($orderId);
        $this->model->setOrderCurrencyId($order, $this->userCurrencyHandler->getUserCurrencyId());
    }

    /**
     * Re-format all order displayable prices to set actual order currency symbol and formatting
     *
     * @param array $formattedFields
     * @param \WC_Order $order
     * @param array $args
     *
     * @return mixed
     *
     *
     * Fixes price formatting and currency symbol on my-account/orders, my-account/view-order, checkout/order-received and
     * other pages where orders displayed on frontend.
     *
     * Replace this if better way will be found.
     */
    public function formatOrderPriceLines($formattedFields, \WC_Order $order, $args)
    {
        $currentFilter = current_filter();

        //if order hasn't currency_id or currency was deleted, we can't do anything
        $orderCurrencyId = $this->model->getOrderCurrencyId($order->get_id());


        if (!isset($this->model->getCurrencies()[$orderCurrencyId])) {
            return $formattedFields;
        }


        //Set filters to format order currency prices in the right way
        $orderCurrencyData = $this->model->getCurrencies()[$orderCurrencyId];
        $this->priceArgsForOrderDisplay = array(
            'currency' => $orderCurrencyData['code'],
            'decimal_separator' => $orderCurrencyData['decimal_separator'],
            'thousand_separator' => $orderCurrencyData['thousand_separator'],
            'decimals' => $orderCurrencyData['decimals_num'],
            'price_format' => $this->model->getPriceFormat($orderCurrencyId),
        );

        $this->priceSymbolForOrderDisplay = $orderCurrencyData['symbol'];

        add_filter('wc_price_args', array($this, 'replacePriceArgsForOrderDisplay'));
        add_filter('woocommerce_currency_symbol', array($this, 'replaceCurrencySymbolForOrderDisplay'));


        //Remove current filter to prevent endless loop and call corresponded method
        switch ($currentFilter) {
            case 'woocommerce_order_formatted_line_subtotal':
                $function = 'formatOrderLineSubtotal';
                break;
            case 'woocommerce_get_order_item_totals':
                $function = 'formatOrderItemTotals';
                break;
            default:
                $function = 'formatOrderTotals';
        }

        remove_filter($currentFilter, array($this, $function));

        if ('woocommerce_order_formatted_line_subtotal' === $currentFilter) {
            $formattedFields = $order->get_formatted_line_subtotal($args['item']);
            $argsNum = 4;
        } elseif ('woocommerce_get_order_item_totals' === $currentFilter) {
            $formattedFields = $order->get_order_item_totals($args['tax_display']);
            $argsNum = 3;
        } else {
            $formattedFields = $order->get_formatted_order_total($args['tax_display'], $args['display_refunded']);
            $argsNum = 4;
        };


        //Remove price format filters because they shouldn't affect any other price
        remove_filter('wc_price_args', array($this, 'replacePriceArgsForOrderDisplay'));
        remove_filter('woocommerce_currency_symbol', array($this, 'replaceCurrencySymbolForOrderDisplay'));


        //Set current filter again for the next order or order field
        add_filter($currentFilter, array($this, $function), 10, $argsNum);


        return $formattedFields;
    }

    /**
     * Replace price args before format order info
     *
     * @param array $args Original args
     *
     * @return array
     */
    public function replacePriceArgsForOrderDisplay($args)
    {
        return wp_parse_args($this->priceArgsForOrderDisplay, $args);
    }

    /**
     * Replace price currency symbol before format order info
     *
     * @return mixed
     */
    public function replaceCurrencySymbolForOrderDisplay()
    {
        return $this->priceSymbolForOrderDisplay;
    }

    /**
     * @param \WC_Order $order
     */
    public function replaceCurrencyParams(\WC_Order $order)
    {
        $orderCurrencyId = $this->model->getOrderCurrencyId($order->get_id());
        $orderCurrencyData = $this->model->getCurrencyById($orderCurrencyId);

        if (!$orderCurrencyData) {
            return;
        }

        add_filter('woocommerce_currency_symbol', function () use ($orderCurrencyData) {
            return $orderCurrencyData['symbol'];
        });


        add_filter('wc_get_price_thousand_separator', function () use ($orderCurrencyData) {
            return $orderCurrencyData['thousand_separator'];
        });


        add_filter('wc_get_price_decimal_separator', function () use ($orderCurrencyData) {
            return $orderCurrencyData['decimal_separator'];
        });


        add_filter('woocommerce_price_format', function () use ($orderCurrencyId) {
            return $this->model->getPriceFormat($orderCurrencyId);
        });


        add_filter('wc_get_price_decimals', function () use ($orderCurrencyData) {
            return $orderCurrencyData['decimals_num'];
        });
    }

    /**
     * Convert order item price if order item is product.
     *
     * @param int       $itemId
     * @param object    $item
     * @param int       $orderId
     */
    public function adjustOrderItemPrice($itemId, $item, $orderId)
    {
        if ($item instanceof \WC_Order_Item_Product) {
            $this->convertOrderItemPrice($item, $orderId);
        }
    }

    /**
     * Convert order item price to order currency.
     *
     * For some reason Woocommerce doesn't recalculate order totals when adding new order item. That's why we don't do this too.
     *
     * @param \WC_Order_Item_Product $item
     * @param $orderId
     */
    private function convertOrderItemPrice(\WC_Order_Item_Product $item, $orderId)
    {
        if ($this->userPricesHandler->isFiltersActive()) {
            return;
        }

        $orderCurrencyId = $this->model->getOrderCurrencyId($orderId);

        if (!$orderCurrencyId || !$this->model->currencyExists($orderCurrencyId)) {
            return;
        }

        $order = wc_get_order($orderId);
        $product = $item->get_product();
        if (!$product) {
            return;
        }


        $productCurrency = $this->model->getProductCurrency($product);
        $priceType = $this->userPricesHandler->isProductOnSale($product->get_id()) ? 'sale' : 'regular';

        $newProductPrice = $this->userPricesHandler->calculatePriceInUsersCurrency(
            $this->model->getProductPriceInProductCurrency($product, $priceType),
            $productCurrency,
            $orderCurrencyId
        );

        $product->set_price($newProductPrice);

        $args = array(
            'qty' => $item->get_quantity()
        );

        $order->update_product($item, $product, $args);

        $item->calculate_taxes();
    }
}
