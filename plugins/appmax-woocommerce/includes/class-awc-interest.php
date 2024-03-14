<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Interest
{
    public function awc_distribute_interest( $products, $interest )
    {
        $distribute_interest = (float) round(($interest / count($products)), 3);

        return array_map(function($item) use ($distribute_interest) {
            $price = $item['price'] + ($distribute_interest / $item['qty']);
            $item['price'] = round($price, 3);
            return $item;
        }, $products);
    }
}
