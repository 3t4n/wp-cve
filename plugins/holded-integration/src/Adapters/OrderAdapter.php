<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Adapters;

use Holded\SDK\DTOs\Order\Item;
use Holded\Woocommerce\DTOs\Order\Order;
use Holded\Woocommerce\Services\ShopService;

class OrderAdapter
{
    public static function fromWoocommerceToDTO(\WC_Order $woocommerceOrder): Order
    {
        $order = new Order();
        $order->holdedId = $woocommerceOrder->get_meta('_holdedwc_invoice_id');
        $order->marketplace = ShopService::getProviderName();
        $order->siteUrl = ShopService::getShopUrl();
        $order->contact_code = $woocommerceOrder->get_meta('_billing_nif');
        $order->contact_name = $woocommerceOrder->get_billing_first_name().' '.$woocommerceOrder->get_billing_last_name().' '.$woocommerceOrder->get_billing_company();
        $order->contact_email = $woocommerceOrder->get_billing_email();
        $order->contact_phone = $woocommerceOrder->get_billing_phone();
        $order->contact_address = $woocommerceOrder->get_billing_address_1().','.$woocommerceOrder->get_billing_address_2();
        $order->contact_city = $woocommerceOrder->get_billing_city();
        $order->contact_cp = $woocommerceOrder->get_billing_postcode();
        $order->contact_province = '';
        $order->contact_provincecode = $woocommerceOrder->get_billing_state();
        $order->contact_country = WC()->countries->get_countries()[$woocommerceOrder->get_billing_country()];
        $order->contact_countrycode = $woocommerceOrder->get_billing_country();
        $order->desc = '';
        $order->date = strtotime($woocommerceOrder->get_date_completed() ? $woocommerceOrder->get_date_completed()->date_i18n() : $woocommerceOrder->get_date_created()->date_i18n());
        $order->datestart = strtotime($woocommerceOrder->get_date_created()->date_i18n());
        $order->notes = $woocommerceOrder->get_customer_note();
        $order->saleschannel = null;
        $order->language = self::getLanguage($woocommerceOrder);
        $order->pmtype = null;
        $order->shipping_contact_code = $woocommerceOrder->get_meta('_shipping_nif');
        $order->shipping_name = $woocommerceOrder->get_shipping_first_name().' '.$woocommerceOrder->get_shipping_last_name().' '.$woocommerceOrder->get_shipping_company();
        $order->shipping_phone = $woocommerceOrder->get_shipping_phone();
        $order->shipping_email = $woocommerceOrder->get_meta('_shipping_email');
        $order->shipping_ad = $woocommerceOrder->get_shipping_address_1().','.$woocommerceOrder->get_shipping_address_2();
        $order->shipping_cp = $woocommerceOrder->get_shipping_postcode();
        $order->shipping_ci = $woocommerceOrder->get_shipping_city();
        $order->shipping_pr = '';
        $order->shipping_co = $woocommerceOrder->get_shipping_country();
        $order->taxesEnabled = self::isTaxEnabled() ? 1 : 0;
        $order->priceWithTaxesIncluded = self::pricesIncludeTax() ? 1 : 0;
        $order->customer = $woocommerceOrder->get_user_id();
        $orderStatus = $woocommerceOrder->get_status();
        $order->orderStatus = strpos($orderStatus, 'wc-') === 0 ? $orderStatus : 'wc-'.$orderStatus;
        $order->orderId = $woocommerceOrder->get_id();

        $order->orderNumber = $woocommerceOrder->get_order_number();
        $dateCreated = $woocommerceOrder->get_date_created();
        $order->orderDate = $dateCreated ? $dateCreated->getTimestamp() : null;

        $order->store = get_bloginfo('name', 'display');
        $order->totalPaid = $woocommerceOrder->get_total();

        $order->woocommerceTaxes = json_encode($woocommerceOrder->get_tax_totals());
        $order->woocommerceSummaryTaxes = json_encode($woocommerceOrder->get_items_tax_classes());
        $order->woocommerceUrl = ShopService::getShopUrl();

        $order->currency = strtolower($woocommerceOrder->get_currency());

        $paymentMethod = $woocommerceOrder->get_payment_method();
        switch ($paymentMethod) {
            case 'cod':
                $order->notes .= ' Paid by cash';
                break;
            case 'cheque':
                $order->notes .= ' Paid by check';
                break;
            case 'paypal':
                $order->notes .= ' Paid by paypal';
                break;
            case 'bacs':
                $order->notes .= ' Paid by bank transfer';
                break;
            default:
                $order->notes .= ' Paid by '.(string) $paymentMethod;
                break;
        }

        $order->paymentMethod = $paymentMethod;
        $order->items = self::getOrderItems($woocommerceOrder);

        return $order;
    }

    private static function getLanguage(\WC_Order $woocommerceOrder): string
    {
        $billingCountry = $woocommerceOrder->get_billing_country();

        if (in_array($billingCountry, ['ES', 'FR'])) {
            return strtolower($billingCountry);
        }

        return 'en';
    }

    /**
     * @return Item[]
     */
    private static function getOrderItems(\WC_Order $woocommerceOrder): array
    {
        $itemsArray = [];

        $orderedItems = $woocommerceOrder->get_items();
        if (!empty($orderedItems)) {
            $itemsArray = array_merge($itemsArray, self::processItems($orderedItems, $woocommerceOrder));
        }

        $shippingItems = $woocommerceOrder->get_items('shipping');
        if (!empty($shippingItems)) {
            $itemsArray = array_merge($itemsArray, self::processShippingItems($shippingItems));
        }

        $feeItems = $woocommerceOrder->get_items('fee');
        if (!empty($feeItems)) {
            $itemsArray = array_merge($itemsArray, self::processFeeItems($feeItems));
        }

        return $itemsArray;
    }

    /**
     * @param mixed[] $items
     *
     * @return Item[]
     */
    private static function processItems(array $items, \WC_Order $woocommerceOrder): array
    {
        $processedItems = [];
        $tax = new \WC_TAX();

        $address = self::getCustomerAddressFromOrder($woocommerceOrder);

        foreach ($items as $item) {
            $product = wc_get_product($item['variation_id'] ?: $item['product_id']);
            $sku = self::isAValidProduct($product) ? $product->get_sku() : null;

            $vatPercentage = 0;
            if ((float) $item['line_total']) {
                $vatPercentage = round(((float) $item['line_tax'] * 100) / ((float) $item['line_total']), 2);
            }
            $productCost = ((float) $item['line_subtotal']) / ((float) $item['qty']);

            $taxes = [];

            if (self::isAValidProduct($product) && $product->is_taxable()) {
                $address['tax_class'] = $item['tax_class'];
                $lineRates = $tax->find_rates($address);
                if (!is_array($lineRates)) {
                    $lineRates = [];
                }
                foreach ($lineRates as $lineRate) {
                    $taxes[] = round((float) $lineRate['rate'], 2);
                }

                if (!count($taxes) && $item['total_tax']) {
                    $taxes[] = (float) $vatPercentage;
                }
            }

            $processedItem = new Item();
            $processedItem->name = $item['name'];
            $processedItem->desc = '';
            $processedItem->units = (float) $item['qty'];
            $processedItem->subtotal = (float) $productCost;
            $processedItem->tax = (float) $vatPercentage; // Legacy, just in case
            $processedItem->sku = $sku ?? '';
            $processedItem->stock = self::isAValidProduct($product) ? $product->get_stock_quantity() : 0;
            $processedItem->taxes = $taxes;

            $processedItems[] = $processedItem;

            if ((float) $item['line_subtotal'] != (float) $item['line_total']) {
                $discountItem = new Item();
                $discountItem->name = 'Descuento';
                $discountItem->desc = '';
                $discountItem->units = 1;
                $discountItem->subtotal = -((float) $item['line_subtotal'] - (float) $item['line_total']);
                $discountItem->tax = $processedItem->tax; // Legacy, just in case
                $discountItem->k = 'discount';
                $discountItem->taxes = $taxes;

                $processedItems[] = $discountItem;
            }
        }

        return $processedItems;
    }

    /**
     * @param mixed[] $items
     *
     * @return Item[]
     */
    private static function processShippingItems(array $items): array
    {
        $processedItems = [];

        foreach ($items as $item) {
            $total = floatval($item['cost']);

            $taxPer = 0;
            if (is_serialized($item['taxes'])) {
                $tax = maybe_unserialize($item['taxes']);

                if (count($tax)) {
                    if ($tax && array_key_exists(1, $tax)) {
                        $tax = $tax[1];
                    }
                }

                if (isset($tax) && is_numeric($tax)) {
                    $taxPer = round((($tax * 100) / $total), 4);
                }
            } elseif (is_array($item['taxes']) && isset($item['taxes']['total'])) {
                $totalTax = 0;
                foreach ($item['taxes']['total'] as $taxShipping) {
                    $totalTax += (float) $taxShipping;
                }

                if ($total) {
                    $taxPer = ($totalTax * 100) / $total;
                }
            }

            $processedItem = new Item();
            $processedItem->name = $item['name'];
            $processedItem->desc = '';
            $processedItem->units = 1;
            $processedItem->subtotal = floatval($total);
            $processedItem->tax = floatval($taxPer);
            $processedItem->k = 'shipping';
            $processedItem->taxes = [$processedItem->tax];

            $processedItems[] = $processedItem;
        }

        return $processedItems;
    }

    /**
     * @param mixed[] $items
     *
     * @return Item[]
     */
    private static function processFeeItems(array $items): array
    {
        $processedItems = [];

        foreach ($items as $item) {
            $total = $item['total'];
            $totalTax = $item['total_tax'];

            $taxFee = 0;
            if ($total) {
                $taxFee = round(($totalTax * 100) / $total);
            }

            $processedItem = new Item();
            $processedItem->name = $item['name'];
            $processedItem->desc = '';
            $processedItem->units = 1;
            $processedItem->subtotal = floatval($total);
            $processedItem->tax = floatval($taxFee);
            $processedItem->k = 'refund';
            $processedItem->taxes = [$processedItem->tax];

            $processedItems[] = $processedItem;
        }

        return $processedItems;
    }

    private static function isTaxEnabled(): bool
    {
        return apply_filters('wc_tax_enabled', get_option('woocommerce_calc_taxes') === 'yes');
    }

    private static function pricesIncludeTax(): bool
    {
        return wc_tax_enabled() && get_option('woocommerce_prices_include_tax') === 'yes';
    }

    /**
     * @param \WC_Product|false|null $product
     */
    private static function isAValidProduct($product): bool
    {
        return $product instanceof \WC_Product;
    }

    /**
     * @return mixed[]
     */
    private static function getCustomerAddressFromOrder(\WC_Order $woocommerceOrder): array
    {
        if (!empty($woocommerceOrder->get_shipping_country())) {
            return [
                'country'   => $woocommerceOrder->get_shipping_country(),
                'state'     => $woocommerceOrder->get_shipping_state(),
                'city'      => $woocommerceOrder->get_shipping_city(),
                'postcode'  => $woocommerceOrder->get_shipping_postcode(),
            ];
        }

        if (!empty($woocommerceOrder->get_billing_country())) {
            return [
                'country'   => $woocommerceOrder->get_billing_country(),
                'state'     => $woocommerceOrder->get_billing_state(),
                'city'      => $woocommerceOrder->get_billing_city(),
                'postcode'  => $woocommerceOrder->get_billing_postcode(),
            ];
        }

        return [];
    }
}
