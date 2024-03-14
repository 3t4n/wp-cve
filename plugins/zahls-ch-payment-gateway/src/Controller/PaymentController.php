<?php

namespace ZahlsPaymentGateway\Controller;

class PaymentController
{
    public function createBasketByCart($cart) {
        $productPriceIncludesTax = ('yes' === get_option( 'woocommerce_prices_include_tax'));

        $cartItems = $cart->get_cart();
        $basket = [];

        $cartSubTotal = 0;
        foreach ($cartItems as $item) {
            $productId = $item['data']->get_id();
            $amount = $item['data']->get_sale_price() ?: $item['data']->get_price();

            // In case of subscription the sign up fee maybe should be added
            if (class_exists('\WC_Subscriptions') && \WC_Subscriptions_Product::is_subscription($productId)) {
                $amount += \WC_Subscriptions_Product::get_sign_up_fee($productId);

                // With a trial period the original price is not immediately charged
                if (\WC_Subscriptions_Product::get_trial_length($productId)) {
                    $amount -= ($item['data']->get_sale_price() ?: $item['data']->get_price());
                }
            }

            $cartSubTotal += $amount * $item['quantity'];
            $basket[] = [
                'name' => $item['data']->get_title(),
                'description' => $item['data']->get_short_description(),
                'quantity' => $item['quantity'],
                'amount' => $amount * 100,
                'sku' => $item['data']->get_sku(),
            ];
        }

        $shipping = $cart->get_shipping_total();
        $shippingTax = $cart->get_shipping_tax();
        if ($shipping || $shippingTax) {
            $shippingAmount = round($shipping + $shippingTax, 2);
            $basket[] = [
                'name' => 'Shipping',
                'quantity' => 1,
                'amount' => $shippingAmount * 100,
            ];
        }

        $discount = $cart->get_discount_total();
        $discountTax = $cart->get_discount_tax();
        if ($discount) {
            $discountAmount = $discount;
            $discountAmount += !$productPriceIncludesTax ? 0 : $discountTax;
            $basket[] = [
                'name' => 'Discount',
                'quantity' => 1,
                'amount' => $discountAmount * -100,
            ];
        }

        $fee = $cart->get_fee_total();
        $feeTax = $cart->get_fee_tax();
        if ($fee) {
            $feeAmount = $fee;
            $feeAmount += !$productPriceIncludesTax ? 0 : $feeTax;
            $basket[] = [
                'name' => 'Fee',
                'quantity' => 1,
                'amount' => $feeAmount * 100,
            ];
        }

        $taxAmount = $cart->get_cart_contents_tax();
        if ($taxAmount && !$productPriceIncludesTax) {
            $basket[] = [
                'name' => 'Tax',
                'quantity' => 1,
                'amount' => $taxAmount * 100,
            ];
        }

        // Debug basket creation
        // throw new \Exception('subtotal: ' . $cartSubTotal .'; Ship: '.$shippingAmount .'; Discount: '.$discountAmount .'; Fee: '.$feeAmount .'; Tax: '. $taxAmount);

        return $basket;
    }

    public function getBasketAmount($basket) {
        $basketAmount = 0;

        foreach ($basket as $product) {
            $amount = $product['amount'] / 100;
            $basketAmount += $product['quantity'] * $amount;
        }
        return $basketAmount;
    }

    public function createPurposeByBasket($basket) {
        $desc = [];
        foreach ($basket as $product) {
            $desc[] = implode(' ', [
                $product['name'],
                $product['quantity'],
                'x',
                number_format($product['amount'] / 100, 2, '.'),
            ]);
        }
        return implode('; ', $desc);
    }
}