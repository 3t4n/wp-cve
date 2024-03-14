<?php
// get delivery company for itemsOrder
// itemsOrder is element in order generate
function getDeliveryCompanyId($itemsOrder, $cityDestinationOrder, $saleValue, $apiKey, $shippingMethodId ){
    global $woocommerce;
    $valueSelect = get_option( 'mpq_value_select' );
    $freeShipping = get_option( 'free_shipping' );
    $url = getUrlApi() . 'quoteShipping';
    $calculateDimensions = calculateDimensions($itemsOrder);
    $height = $calculateDimensions['height'];
    $width = $calculateDimensions['width'];
    $length = $calculateDimensions['length'];
    $weight = $calculateDimensions['weight'];
    $totalValorization = $calculateDimensions['total_valorization'];
    $cityDestination  = $cityDestinationOrder;
    $saleValueUpon = $saleValue;
    if ($shippingMethodId != 'mipaquete.com envío contraentrega') {
        $saleValueUpon === 0;
    }
    $quantityCart = 1;
    foreach ($itemsOrder as $item => $values) {
        $productId = $values['product_id'] ?? $values->get_product_id();
        $_product = wc_get_product( $productId );
        $quantity = $values['quantity'];
    }
    
    //Loop through each item from the cart
    $customer = new WC_Customer(0, true);
    $location = $customer->get_shipping_state();
    $infoUserLocationCode = ReturnGetUser();
    $data = array("originLocationCode" => "$infoUserLocationCode[2]",
    "destinyLocationCode" => "$cityDestination",
    "height" => (int)$height,
    "width" => (int)$width,
    "length" => (int)$length,
    "weight" => (int)$weight,
    "quantity" => 1,
    "declaredValue" => (int)$totalValorization,
    "saleValue" => (int)$saleValueUpon,
    );
    $dataString = json_encode($data);

    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json",
    "session-tracker:a0c96ea6-b22d-4fb7-a278-850678d5429c",
    "apikey:" .$apiKey,
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $resultData = json_decode($result, true);
    $totalData = (int)count($resultData);
    curl_close($ch);
    if (!empty($cityDestination) && $valueSelect == 1 && $freeShipping != 2) {
        array_multisort(array_column($resultData, 'shippingCost'), SORT_ASC, $resultData);
    }
    if (!empty($cityDestination) && $valueSelect == 2 && $freeShipping != 2) {
        array_multisort(array_column($resultData, 'shippingTime'), SORT_ASC, $resultData);
    }
    
    if (!empty($cityDestination) && $valueSelect == 3 && $freeShipping != 2) {
        array_multisort(array_column($resultData, 'score'), SORT_DESC, $resultData);
    }
    $orderGetDeliveryCompanyId = $resultData[0]['deliveryCompanyId'];

    return array('deliveryCompanyId' => $orderGetDeliveryCompanyId);
}