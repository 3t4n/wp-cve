<?php

namespace WunderAuto\Behaviours;

use WC_Product;
use WunderAuto\Loader;

/**
 * Class FrontEnd
 */
class FrontEnd
{
    /**
     * Main entry point
     *
     * @param Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        //$loader->addAction('init', $this, 'handle');
        $loader->addAction('wp_loaded', $this, 'handle');
    }

    /**
     * Handle WunderAutomation actions
     *
     * @handle init
     *
     * @return void
     */
    public function handle()
    {
        $action = sanitize_key($this->getParam('wa_action'));
        if (strlen($action) === 0) {
            return;
        }

        switch (($action)) {
            case 'reorder':
                $this->reOrder();
                break;
        }
    }

    /**
     * Handle reorder action
     *
     * @return void
     */
    private function reOrder()
    {
        $orderKey = sanitize_text_field($this->getParam('order_key'));
        if (strlen($orderKey) === 0) {
            return;
        }

        $orderId = wc_get_order_id_by_order_key($orderKey);
        if ($orderId === 0) {
            wc_add_notice(__('Previous order not found.', 'wunderauto'));
            return;
        }

        $order = wc_get_order($orderId);
        assert($order instanceof \WC_Order);

        if (apply_filters('woocommerce_empty_cart_when_order_again', true)) {
            WC()->cart->empty_cart();
        }

        $orderItems = $order->get_items();
        foreach ($orderItems as $item) {
            if (!$item instanceof \WC_Order_Item_Product) {
                continue;
            }

            $product = $item->get_product();
            if (!$product instanceof WC_Product) {
                continue;
            }

            $quantity     = $item->get_quantity();
            $variationId  = $item->get_variation_id();
            $variations   = [];
            $cartItemData = apply_filters('woocommerce_order_again_cart_item_data', [], $item, $order);

            // Prevent reordering variable products if no selected variation.
            if (!$variationId && $product->is_type('variable')) {
                continue;
            }

            foreach ($item->get_meta_data() as $meta) {
                if (taxonomy_is_product_attribute($meta->key)) {
                    $term                   = get_term_by('slug', $meta->value, $meta->key);
                    $variations[$meta->key] = $term instanceof \WP_Term ? $term->name : $meta->value;
                } elseif (meta_is_product_attribute($meta->key, $meta->value, $product->get_id())) {
                    $variations[$meta->key] = $meta->value;
                }
            }

            $isValid = apply_filters(
                'woocommerce_add_to_cart_validation',
                true,
                $product->get_id(),
                $quantity,
                $variationId,
                $variations,
                $cartItemData
            );
            if (!$isValid) {
                continue;
            }
            WC()->cart->add_to_cart($product->get_id(), $quantity, $variationId, $variations, $cartItemData);
        }

        do_action('woocommerce_ordered_again', $order->get_id());

        $cartItemsCount          = count(WC()->cart->get_cart());
        $originalOrderItemsCount = count($orderItems);

        if ($originalOrderItemsCount > $cartItemsCount) {
            wc_add_notice(
                sprintf(
                    _n(
                        '%d item from your previous order is currently unavailable and could not be added to ' .
                        'your cart.',
                        '%d items from your previous order are currently unavailable and could not be added ' .
                        'to your cart.',
                        $originalOrderItemsCount - $cartItemsCount,
                        'wunderauto'
                    ),
                    $originalOrderItemsCount - $cartItemsCount
                ),
                'error'
            );
        }

        if ($cartItemsCount > 0) {
            wc_add_notice(__('Your cart has been filled with the items from your previous order.', 'wunderauto'));
        }

        $this->safeRedirect(wc_get_cart_url(), ['order_key']);
    }

    /**
     * Extract REQUEST parameter and return empty string if key is not set
     *
     * @param string $name
     *
     * @return string
     */
    private function getParam($name)
    {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
    }

    /**
     * Redirect to a new URL without $args
     *
     * @param string             $url
     * @param array<int, string> $remove
     *
     * @return void
     */
    private function safeRedirect($url, $remove = [])
    {
        $remove[] = 'wa-action';

        $newArgs = array_filter(
            $_GET,
            function ($key) use ($remove) {
                return in_array($key, $remove);
            },
            ARRAY_FILTER_USE_KEY
        );

        wp_safe_redirect(add_query_arg($newArgs, $url));
        exit;
    }
}
