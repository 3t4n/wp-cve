<?php

namespace FS\Test\Helper;

class FlagshipShippingWooCommerceFactory
{
    /**
     * Create simple product.
     *
     * @return WC_Product_Simple
     */
    public static function createSimpleProduct()
    {
        // Create the product
        $product = wp_insert_post(
            array(
                'post_title' => 'Dummy Product',
                'post_type' => 'product',
                'post_status' => 'publish',
            )
        );

        update_post_meta($product, '_price', '10');
        update_post_meta($product, '_regular_price', '10');
        update_post_meta($product, '_sale_price', '');
        update_post_meta($product, '_sku', 'DUMMY SKU');
        update_post_meta($product, '_manage_stock', 'no');
        update_post_meta($product, '_tax_status', 'taxable');
        update_post_meta($product, '_downloadable', 'no');
        update_post_meta($product, '_virtual', 'taxable');
        update_post_meta($product, '_visibility', 'visible');
        update_post_meta($product, '_stock_status', 'instock');

        return new \WC_Product_Simple($product);
    }

    public static function createSimpleShippingFlatRate()
    {
        $settings = array(
            'enabled' => 'yes',
            'title' => 'Flat Rate',
            'availability' => 'all',
            'countries' => '',
            'tax_status' => 'taxable',
            'cost' => '10',
        );
        update_option('woocommerce_flat_rate_settings', $settings);
        update_option('woocommerce_flat_rate', array());
    }

    public static function createSimpleOrder($total = 40)
    {
        $product = self::createSimpleProduct();
        self::createSimpleShippingFlatRate();

        $order_data = array(
            'status' => 'pending',
            'customer_id' => 1,
            'customer_note' => '',
            'total' => '',
        );

        // Required, else wc_create_order throws an exception
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $order = wc_create_order($order_data);

        // Add order products
        $order->add_product($product, 4);

        // Set billing address
        $shipping_address = array(
            'country' => 'US',
            'first_name' => 'Jeroen',
            'last_name' => 'Sormani',
            'company' => 'WooCompany',
            'address_1' => 'WooAddress',
            'address_2' => '',
            'postcode' => '123456',
            'city' => 'WooCity',
            'state' => 'NY',
            'email' => 'admin@example.org',
            'phone' => '555-32123',
        );
        $order->set_address($shipping_address, 'shipping');

        // Add shipping costs
        $shipping_taxes = \WC_Tax::calc_shipping_tax(
            '10',
            \WC_Tax::get_shipping_tax_rates()
        );

        $item = new \WC_Order_Item_Shipping();
        $item->set_props([
            'method_title' => 'Purolator - Purolator Express',
            'method_id' => 'flagship_shipping_method|Purolator|PurolatorExpress|Purolator Express|1473811200',
            'total' => wc_format_decimal(10),
            'taxes' => $shipping_taxes,
            'order_id' => $order->get_id(),
        ]);
        $item->save();

        $order->add_item($item);

        // Set totals
        $order->set_total($total);
        $order->set_discount_total(0);
        $order->set_discount_tax(0);
        $order->set_shipping_total(10);
        $order->set_shipping_tax(0);
        $order->set_cart_tax(0);

        $order->save();

        return wc_get_order($order->get_id());
    }
}
