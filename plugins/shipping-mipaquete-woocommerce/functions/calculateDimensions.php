<?php
//calculate dimensions for items in carts
function calculateDimensions($items) { // $items are elements of products
    $height = 0;
    $length = 0;
    $weight = 0;
    $width = 0;
    $weightCeil = 1;
    $totalValorization = 0;

    foreach ( $items as $item => $values ) {
        if ($values['variation_id'] != 0 && $values['product_id'] != 0) {
            $productId = $values['variation_id'];
        } elseif ($values['variation_id'] == 0 && $values['product_id'] != 0) {
            $productId = $values['product_id'];
        } elseif ($values['variation_id'] != 0 && $values['product_id'] == 0) {
            $productId = $values['variation_id'];
        } else {
            $productId = 0;
        }
        $_product = wc_get_product( $productId );
        $quantity = $values['quantity'];
        $declaredValue = 0;

        if ($_product->is_type( 'variable' )) {
            $variations = $_product->get_available_variations();
            $variationsTotalData = (int)count($variations);
            for ($i=0; $i < $variationsTotalData; $i++) {
                $height += ceil($variations[$i]['dimensions']['height'] * $quantity);
                $length = ceil($variations[$i]['dimensions']['length'] > $length ?
                $variations[$i]['dimensions']['length'] : $length);
                $weight += $variations[$i]['weight'] * $quantity;
                $width =  ceil($variations[$i]['dimensions']['width'] > $width ?
                $variations[$i]['dimensions']['width'] : $width);
                $declaredValue += $variations[$i]['display_price'];
                $totalValorization = $declaredValue > 10000 ? $declaredValue : 10000;
                
            }

            if ($weight > 1) {
                $weightCeil = ceil($weight);
                
            }
        }
        
        if (!$_product->get_weight() || !$_product->get_length()
            || !$_product->get_width() || !$_product->get_height()
            || $height > 200 || $width > 200 || $length > 200 || $weightCeil > 150 ) {
                break;
            }
            
        
        $customPriceProduct = get_post_meta($productId, '_custom_declared_value', true);
        $totalValorization += $customPriceProduct ? $customPriceProduct : $_product->get_price();

        $totalValorization = $totalValorization * $quantity;

        if ($totalValorization < 10000) {
            $totalValorization = 10000;
        }
        $height += ceil($_product->get_height() * $quantity);
        $length = ceil($_product->get_length() > $length ? $_product->get_length() : $length);
        $weight += $_product->get_weight() * $quantity ;
        $width =  ceil($_product->get_width() > $width ? $_product->get_width() : $width);
        

        if ($weight > 1) {
            $weightCeil = ceil($weight);
        }

    }
    return array(
        'height' => $height,
        'length' => $length,
        'weight' => $weightCeil,
        'width' =>  $width,
        'total_valorization' => $totalValorization
    );
}
