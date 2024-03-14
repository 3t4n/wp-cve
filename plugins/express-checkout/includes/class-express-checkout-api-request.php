<?php

if (!defined('ABSPATH'))
    exit;

class Express_Checkout_API_Request {

    const AUTH_CAPTURE = 'Sale';
    const AUTH_ONLY = 'Authorization';

    public $parameters = array();
    public $order;

    public function __construct($api_username, $api_password, $api_signature, $api_version) {
        $this->ex_add_parameters(array(
            'USER' => $api_username,
            'PWD' => $api_password,
            'SIGNATURE' => $api_signature,
            'VERSION' => $api_version,
        ));
        $this->express_checkout_utility = new Express_Checkout_Utility(null, null);
    }

    public function ex_set_express_checkout($args) {
        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }
        $this->ex_set_method('SetExpressCheckout');
        $defaults = array(
            'use_bml' => false,
            'paypal_account_optional' => false,
            'landing_page' => 'billing',
            'page_style' => null,
            'brand_name' => null,
            'payment_action' => self::AUTH_CAPTURE,
        );
        $args = wp_parse_args($args, $defaults);
        $this->ex_add_parameters(array(
            'RETURNURL' => $args['return_url'],
            'CANCELURL' => $args['cancel_url'],
            'PAGESTYLE' => $args['page_style'],
            'BRANDNAME' => $args['brand_name'],
            'LOCALECODE' => (get_locale() != '') ? get_locale() : '',
            'HDRIMG' => $args['hdrimg'],
            'LOGOIMG' => $args['logoimg'],
            'SOLUTIONTYPE' => $args['paypal_account_optional'] ? 'Sole' : 'Mark',
            'LANDINGPAGE' => ( 'login' == $args['landing_page'] ) ? 'Login' : 'Billing',
        ));
        if ($args['use_bml']) {
            $this->ex_add_parameters(array(
                'USERSELECTEDFUNDINGSOURCE' => 'BML',
                'SOLUTIONTYPE' => 'Sole',
                'LANDINGPAGE' => 'Billing',
            ));
        }
        $i = 0;
        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }
        WC()->cart->calculate_totals();
        if ($this->ex_skip_line_items()) {
            $item_names = array();
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $quantity = absint($cart_item['quantity']);
                $item_names[] = sprintf('%1$s x %2$s', $product->get_title(), $quantity);
            }
            foreach (WC()->cart->get_fees() as $fee) {
                $item_names[] = sprintf(__('Fee - %s', 'express-checkout'), $fee->name);
            }
            $line_item_amount = $this->ex_round(WC()->cart->cart_contents_total + WC()->cart->fee_total);
            $shipping_amount = $this->ex_round(WC()->cart->shipping_total + WC()->cart->shipping_tax_total);
            $tax_amount = $this->ex_round(WC()->cart->tax_total);
            $total_amount = $this->ex_round(WC()->cart->total);
            $calculated_total = $this->ex_round($line_item_amount) + $this->ex_round($shipping_amount) + $this->ex_round($tax_amount);
            if ($total_amount !== $calculated_total) {
                $line_item_amount = $line_item_amount - ( $calculated_total - $total_amount );
            }
            $this->ex_add_line_item_parameters(array(
                'NAME' => sprintf(__('%s - Order', 'express-checkout'), get_option('blogname')),
                'DESC' => $this->ex_str_truncate(html_entity_decode(implode(', ', $item_names), ENT_QUOTES, 'UTF-8'), 127),
                'AMT' => $line_item_amount,
                    ), 0);
            $this->ex_add_payment_parameters(array(
                'AMT' => $total_amount,
                'CURRENCYCODE' => get_woocommerce_currency(),
                'ITEMAMT' => $line_item_amount,
                'SHIPPINGAMT' => $shipping_amount,
                'TAXAMT' => $tax_amount,
                'NOTIFYURL' => $args['notifyurl'],
                'PAYMENTACTION' => ( $args['payment_action'] == self::AUTH_ONLY ) ? self::AUTH_ONLY : self::AUTH_CAPTURE,
            ));
        } else {
            $calculated_total = 0;
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $quantity = absint($cart_item['quantity']);
                $item_price = $this->ex_round($cart_item['line_subtotal'] / $quantity, 2);
                $this->ex_add_line_item_parameters(array(
                    'NAME' => html_entity_decode(wc_trim_string($product->get_title() ? $product->get_title() : __('Item', 'woocommerce'), 127), ENT_NOQUOTES, 'UTF-8'),
                    'DESC' => '',
                    'AMT' => $item_price,
                    'QTY' => $quantity,
                    'ITEMURL' => $product->get_permalink(),
                        ), $i++);
                $calculated_total += $this->ex_round($item_price * $quantity);
            }
            if (WC()->cart->get_cart_discount_total() > 0) {
                $this->ex_add_line_item_parameters(array(
                    'NAME' => __('Total Discount', 'express-checkout'),
                    'QTY' => 1,
                    'AMT' => - $this->ex_round(WC()->cart->get_cart_discount_total()),
                        ), $i++);

                $calculated_total -= $this->ex_round(WC()->cart->get_cart_discount_total());
            }
            foreach (WC()->cart->get_fees() as $fee) {
                $this->ex_add_line_item_parameters(array(
                    'NAME' => __('Fee', 'express-checkout'),
                    'DESC' => $this->ex_str_truncate($fee->name, 127),
                    'AMT' => $this->ex_round($fee->amount),
                    'QTY' => 1,
                        ), $i++);
                $calculated_total += $this->ex_round($fee->amount);
            }
            $total_amount = $this->ex_round(WC()->cart->total);
            $calculated_total += $this->ex_round(WC()->cart->shipping_total) + wc_round_tax_total(WC()->cart->tax_total + WC()->cart->shipping_tax_total);
            if ($total_amount !== $calculated_total) {
                $this->ex_add_line_item_parameters(array(
                    'NAME' => __('PayPal Rounding Adjustment', 'express-checkout'),
                    'AMT' => - ( $calculated_total - $total_amount ),
                    'QTY' => 1,
                        ), $i++);
            }
            $this->ex_add_payment_parameters(array(
                'AMT' => $this->ex_round(WC()->cart->total),
                'CURRENCYCODE' => get_woocommerce_currency(),
                'ITEMAMT' => $this->ex_round(WC()->cart->cart_contents_total + WC()->cart->fee_total),
                'SHIPPINGAMT' => $this->ex_round(WC()->cart->shipping_total),
                'TAXAMT' => wc_round_tax_total(WC()->cart->tax_total + WC()->cart->shipping_tax_total),
                'NOTIFYURL' => $args['notifyurl'],
                'PAYMENTACTION' => ( $args['payment_action'] == self::AUTH_ONLY ) ? self::AUTH_ONLY : self::AUTH_CAPTURE,
            ));
        }
        $this->ex_add_parameter('MAXAMT', ceil(WC()->cart->total + ( WC()->cart->total * .5 )));
        if (is_user_logged_in()) {
            $customer_id = get_current_user_id();
            $this->ex_add_payment_parameters(array(
                'SHIPTONAME' => get_user_meta($customer_id, 'shipping_first_name', true) . ' ' . get_user_meta($customer_id, 'shipping_last_name', true),
            ));
        }
        $this->ex_add_payment_parameters(array(
            'SHIPTOSTREET' => WC()->customer->get_shipping_address(),
            'SHIPTOSTREET2' => WC()->customer->get_shipping_address_2(),
            'SHIPTOCITY' => WC()->customer->get_shipping_city(),
            'SHIPTOSTATE' => WC()->customer->get_shipping_state(),
            'SHIPTOZIP' => WC()->customer->get_shipping_postcode(),
            'SHIPTOCOUNTRYCODE' => WC()->customer->get_shipping_country(),
            'SHIPTOPHONENUM' => ( WC()->session->post_data['billing_phone'] ) ? WC()->session->post_data['billing_phone'] : WC()->session->post_data['billing_phone'],
            'NOTETEXT' => ( WC()->session->post_data['order_comments'] ) ? WC()->session->post_data['order_comments'] : WC()->session->post_data['order_comments'],
        ));
    }

    public function ex_get_express_checkout_details($token) {
        $this->ex_set_method('GetExpressCheckoutDetails');
        $this->ex_add_parameter('TOKEN', $token);
    }

    public function ex_do_payment($order) {
        $this->order = $order;
        $this->ex_set_method('DoExpressCheckoutPayment');
        $this->ex_add_parameters(array(
            'TOKEN' => WC()->session->paypal_express_checkout['token'],
            'PAYERID' => (!empty(WC()->session->paypal_express_checkout['payer_id']) ) ? WC()->session->paypal_express_checkout['payer_id'] : null,
            'BUTTONSOURCE' => 'mbjtechnolabs_SP',
            'RETURNFMFDETAILS' => 1,
        ));
        $calculated_total = 0;
        $order_subtotal = $i = 0;
        $order_items = array();
        foreach ($order->get_items() as $cart_item_key => $item) {
            $product = version_compare(WC_VERSION, '3.0', '<') ? $order->get_product_from_item($item) : $item->get_product();
            $product_sku = null;
            if (is_object($product)) {
                $product_sku = $product->get_sku();
            }
            $order_items[] = array(
                'NAME' => $this->ex_str_truncate(html_entity_decode($product->get_title(), ENT_QUOTES, 'UTF-8'), 127),
                'DESC' => '',
                'AMT' => $this->ex_round($order->get_item_subtotal($item)),
                'QTY' => (!empty($item['qty']) ) ? absint($item['qty']) : 1,
                'NUMBER' => $product_sku,
            );
            $order_subtotal += $item['line_total'];
        }
        foreach ($order->get_fees() as $fee) {
            $order_items[] = array(
                'NAME' => $this->ex_str_truncate($fee['name'], 127),
                'AMT' => $this->ex_round($fee['line_total']),
                'QTY' => 1,
            );
            $order_subtotal += $fee['line_total'];
        }
        if ($order->get_total_discount() > 0) {
            $order_items[] = array(
                'NAME' => __('Total Discount', 'express-checkout'),
                'QTY' => 1,
                'AMT' => - $this->ex_round($order->get_total_discount()),
            );
        }
        if ($this->ex_skip_line_items($order)) {
            $total_amount = $this->ex_round($order->get_total());
            $calculated_total += $this->ex_round($order_subtotal + $order->get_cart_tax()) + $this->ex_round($order->get_total_shipping() + $order->get_shipping_tax());
            if ($total_amount !== $calculated_total) {
                $order_subtotal = $order_subtotal - ( $calculated_total - $total_amount );
            }
            $item_names = array();
            foreach ($order_items as $item) {
                $item_names[] = sprintf('%1$s x %2$s', $item['NAME'], $item['QTY']);
            }
            $this->ex_add_line_item_parameters(array(
                'NAME' => sprintf(__('%s - Order', 'express-checkout'), get_option('blogname')),
                'DESC' => $this->ex_str_truncate(html_entity_decode(implode(', ', $item_names), ENT_QUOTES, 'UTF-8'), 127),
                'AMT' => $this->ex_round($order_subtotal + $order->get_cart_tax()),
                'QTY' => 1,
                    ), 0);
            $this->ex_add_payment_parameters(array(
                'AMT' => $total_amount,
                'CURRENCYCODE' => version_compare(WC_VERSION, '3.0', '<') ? $order->get_order_currency() : $order->get_currency(),
                'ITEMAMT' => $this->ex_round($order_subtotal + $order->get_cart_tax()),
                'SHIPPINGAMT' => $this->ex_round($order->get_total_shipping() + $order->get_shipping_tax()),
                'INVNUM' => $this->express_checkout_utility->express_checkout_get_option('invoice_id_prefix') . preg_replace("/[^0-9,.]/", "", $order->get_order_number()),
                'PAYMENTACTION' => $this->express_checkout_utility->express_checkout_get_option('payment_action'),
                'PAYMENTREQUESTID' => version_compare(WC_VERSION, '3.0', '<') ? $order->id : $order->get_id(),
            ));
        } else {
            foreach ($order_items as $item) {
                $this->ex_add_line_item_parameters($item, $i++);
                $calculated_total += $this->ex_round($item['AMT'] * $item['QTY']);
            }
            $calculated_total += $this->ex_round($order->get_total_shipping()) + $this->ex_round($order->get_total_tax());
            $total_amount = $this->ex_round($order->get_total());
            if ($total_amount !== $calculated_total) {
                $this->ex_add_line_item_parameters(array(
                    'NAME' => __('PayPal Rounding Adjustment', 'express-checkout'),
                    'AMT' => - ( $calculated_total - $total_amount ),
                    'QTY' => 1,
                        ), $i++);
            }
            $this->ex_add_payment_parameters(array(
                'AMT' => $total_amount,
                'CURRENCYCODE' => version_compare(WC_VERSION, '3.0', '<') ? $order->get_order_currency() : $order->get_currency(),
                'ITEMAMT' => $this->ex_round($order_subtotal),
                'SHIPPINGAMT' => $this->ex_round($order->get_total_shipping()),
                'TAXAMT' => $this->ex_round($order->get_total_tax()),
                'INVNUM' => $this->express_checkout_utility->express_checkout_get_option('invoice_id_prefix') . preg_replace("/[^0-9,.]/", "", $order->get_order_number()),
                'PAYMENTACTION' => $this->express_checkout_utility->express_checkout_get_option('payment_action'),
                'PAYMENTREQUESTID' => version_compare(WC_VERSION, '3.0', '<') ? $order->id : $order->get_id(),
            ));
        }
    }

    public function ex_add_parameter($key, $value) {
        $this->parameters[$key] = $value;
    }

    public function ex_add_parameters(array $params) {
        foreach ($params as $key => $value) {
            $this->ex_add_parameter($key, $value);
        }
    }

    public function ex_set_method($method) {
        $this->ex_add_parameter('METHOD', $method);
    }

    public function ex_add_payment_parameters(array $params) {
        foreach ($params as $key => $value) {
            $this->ex_add_parameter("PAYMENTREQUEST_0_{$key}", $value);
        }
    }

    public function ex_add_line_item_parameters(array $params, $item_count) {
        foreach ($params as $key => $value) {
            $this->ex_add_parameter("L_PAYMENTREQUEST_0_{$key}{$item_count}", $value);
        }
    }

    public function ex_skip_line_items($order = null) {
        $skip_line_items = ( 'yes' === get_option('woocommerce_calc_taxes') && 'yes' === get_option('woocommerce_prices_include_tax') );
        return apply_filters('ex_gateway_paypal_express_skip_line_items', $skip_line_items, $order);
    }

    public function ex_get_item_description($item, $product) {
        if (empty($item['item_meta'])) {
            $item_desc = WC()->cart->get_item_data($item, true);
            $item_desc = str_replace("\n", ', ', rtrim($item_desc));
        } else {
            $item_meta = new WC_Order_Item_Meta($item);
            $item_meta = $item_meta->get_formatted();
            if (!empty($item_meta)) {
                $item_desc = array();
                foreach ($item_meta as $meta) {
                    $item_desc[] = sprintf('%1$s: %2$s', $meta['label'], $meta['value']);
                }
                $item_desc = implode(', ', $item_desc);
            } else {
                $item_desc = is_callable(array($product, 'get_sku')) && $product->get_sku() ? sprintf(__('SKU: %s', 'express-checkout'), $product->get_sku()) : null;
            }
        }
        return $this->ex_str_truncate($item_desc, 127);
    }

    public function ex_to_string() {
        return http_build_query($this->ex_get_parameters());
    }

    public function ex_get_parameters() {
        $this->parameters = apply_filters('wc_gateway_paypal_express_request_params', $this->parameters, $this);
        foreach ($this->parameters as $key => $value) {
            if ('' === $value || is_null($value)) {
                unset($this->parameters[$key]);
            }
            if (false !== strpos($key, 'AMT')) {
                if (isset($this->parameters['PAYMENTREQUEST_0_CURRENCYCODE']) && 'USD' == $this->parameters['PAYMENTREQUEST_0_CURRENCYCODE'] && $value > 10000) {
                    return sprintf('%1$s amount of %2$s must be less than $10,000.00', $key, $value);
                }
                $this->parameters[$key] = number_format($value, 2, '.', '');
            }
        }
        return $this->parameters;
    }

    public function ex_get_order() {
        return $this->order;
    }

    public function ex_round($number, $precision = 2) {
        if ($order = $this->ex_get_order()) {
            $currency_code = version_compare(WC_VERSION, '3.0', '<') ? $order->get_order_currency() : $order->get_currency();
        } else {
            $currency_code = get_woocommerce_currency();
        }
        if (!$this->ex_currency_supports_decimals($currency_code)) {
            $precision = 0;
        }
        return round((float) $number, $precision);
    }

    public function ex_currency_supports_decimals($currency_code) {
        return !in_array($currency_code, array('HUF', 'JPY', 'TWD'));
    }

    public function ex_str_truncate($string, $length, $omission = '...') {
        if ($this->ex_multibyte_loaded()) {
            if (mb_strlen($string) <= $length) {
                return $string;
            }
            $length -= mb_strlen($omission);
            return mb_substr($string, 0, $length) . $omission;
        } else {
            $string = $this->ex_str_to_ascii($string);
            if (strlen($string) <= $length) {
                return $string;
            }
            $length -= strlen($omission);
            return substr($string, 0, $length) . $omission;
        }
    }

    public function ex_multibyte_loaded() {
        return extension_loaded('mbstring');
    }

    public function ex_str_to_ascii($string) {
        $ascii = iconv('UTF-8', 'ASCII//IGNORE', $string);
        return false === $ascii ? preg_replace('/[^a-zA-Z0-9]/', '', $string) : $ascii;
    }

    public function ex_do_refund($order_id, $amount, $reason) {
        $order = wc_get_order($order_id);
        $this->ex_set_method('RefundTransaction');
        $this->ex_add_parameters(array(
            'TRANSACTIONID' => $order->get_transaction_id(),
            'REFUNDTYPE' => $order->get_total() == $amount ? 'Full' : 'Partial',
            'BUTTONSOURCE' => 'mbjtechnolabs_SP',
            'AMT' => $this->ex_round($amount),
            'CURRENCYCODE' => version_compare(WC_VERSION, '3.0', '<') ? $order->get_order_currency() : $order->get_currency(),
            'NOTE' => $reason,
        ));
    }

}
