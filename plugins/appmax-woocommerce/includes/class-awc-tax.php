<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Tax
{
    public function awc_distribute_tax( $products, $interest )
    {
        $tax_total = (float) AWC_Helper::awc_get_fee_total();

        $distribute_tax = round(($tax_total / count($products)), 3);

        $new_products = array_map(function($item) use ($distribute_tax) {
            $price = $item['price'] + ($distribute_tax / $item['qty']);
            $item['price'] = round($price, 3);
            return $item;
        }, $products);

        $total_sum_products = $this->awc_get_total_sum_products( $new_products );

        $discount = (float) number_format( AWC_Helper::awc_get_discount_total(), 2, '.', ',' );

        $total_cart = (float) AWC_Helper::awc_get_total_cart();

        $difference = (float) round((($total_sum_products - $discount - $interest) - $total_cart), 2);

        if ($difference == 0.00) {
            return $new_products;
        }

        uasort($new_products, function($a, $b) {
            if ($a['price'] == $b['price']) {
                return 0;
            }
            return ($a['price'] < $b['price']) ? 1 : -1;
        });

        $new_products = array_values($new_products);

        foreach ($new_products as $key => $product) {
            if ($product['qty'] == 1) {
                $new_products[$key]['price'] -= $difference;
                break;
            }
            if ($product['qty'] > 1) {
                $new_products[$key]['price'] -= $difference / $product['qty'];
                break;
            }
        }

        return $new_products;
    }

    private function awc_get_total_sum_products( $products )
    {
        return array_reduce($products, function($total, $item) {
            $total += (float) ($item['price'] * $item['qty']);
            return $total;
        });
    }
}