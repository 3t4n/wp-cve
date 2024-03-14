<?php
//createSending
function sendingMipaquete() {
		global $product;
        $infoUserSender = ReturnGetUser();
        $apik = returnGenerateApiKey();
        $idPost = get_the_ID();
        $order = new WC_Order($idPost);
        $orderItems = $order->get_items('shipping');
        foreach($orderItems as $itemsShipping){
            $shippingMethodId = $itemsShipping['method_title'] ;
        }
        $orderData = $order->get_data(); // The Order data
        //sender//////
        $orderName = $infoUserSender[0];
        $orderPhone = $infoUserSender[4];
        $orderEmail = $infoUserSender[3];
        $orderAddress = $infoUserSender[1];
        $orderNit = $infoUserSender[5];
        $orderNitType = $infoUserSender[6];
        $orderLocationCode = $infoUserSender[2];
        $orderClientType = $infoUserSender[7];
        //sender///////
        //receiver//////////
        $receiverName = $orderData['shipping']['first_name'] . " " . $orderData['shipping']['last_name'];
        $receiverPhone = str_replace(' ', '', $orderData['billing']['phone']);
        $receiverEmail = $orderData['billing']['email'];
        $receiverAddress = $orderData['shipping']['address_1'] . " " . $orderData['shipping']['address_2'];
        $receiverNit = "123";
        $receiverNitType = "CC";
        $receiverLocationCode = $orderData['shipping']['city'];
        $receiverNote = $order->get_customer_order_notes() ?
        $order->get_customer_order_notes() : $order->get_customer_note();
        if (empty($receiverNote)){
            $receiverNote = "N/A";
        }
        $valueCollection = 0;
        //receiver/////////
        if ( $order->has_status('mi-paquete') ) {
            $url = getUrlApi() . 'createSending';
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $headers = array(
            "session-tracker: a0c96ea6-b22d-4fb7-a278-850678d5429c",
            "apikey:" .$apik,
            "Content-Type: application/json",
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            ///////////////// product order //////////////////
            $itemsOrder = $order->get_items();
			foreach ( $itemsOrder as $itemProduct){
				$product = wc_get_product($itemProduct->get_product_id());
   				$productSku[] = $product->get_sku();
                foreach($productSku as $value ){
                    $desSku = $value;
                }
				$productQuantity = $itemProduct->get_quantity();
				$productTotalSku  = home_url();
                if(empty($desSku)) {
                    $description = "NA";
                }
                else {
                    $description .= " /" . $desSku . "- Cantidad: " . $productQuantity;
                }
			}
            $calculateDimensions = calculateDimensions($itemsOrder);
            $getDeliveryCompanyIdSending = getDeliveryCompanyId($itemsOrder,
            $receiverLocationCode,
            $totalSales,
            $apik,
            $shippingMethodId);
            $deliveryCompanyId = $getDeliveryCompanyIdSending['deliveryCompanyId'];
            $width = $calculateDimensions['width'];
            $height = $calculateDimensions['height'];
            $length = $calculateDimensions['length'];
            $weight = $calculateDimensions['weight'];
            $totalValorization = $calculateDimensions['total_valorization'];
            ///////////////// end product order //////////////
            /////// block validations//////////////////////////
            if ( $shippingMethodId === 'mipaquete.com envío contraentrega'
            && $order->payment_method == 'cod'
            && $deliveryCompanyId == '5fceb46c8229797cb139a7aa') {
                $paymentType = 101;
                $totalSales = $order->get_total();
                $valueCollection = $totalSales;
            }
            if ( $shippingMethodId === 'mipaquete.com envío contraentrega'
            && $order->payment_method == 'cod'
            && $deliveryCompanyId == '6080a75ef08a770ddd9724fd') {
                $paymentType = 101;
                $totalSales = $order->get_total();
                $valueCollection = $totalSales;
            }
            if ($shippingMethodId == 'mipaquete.com envío contraentrega'
            && $order->payment_method == 'cod'
            && $deliveryCompanyId != '5fceb46c8229797cb139a7aa'
            && $deliveryCompanyId != '6080a75ef08a770ddd9724fd') {
                $paymentType = 102;
                $totalSales = $order->get_total();
                $valueCollection = $totalSales;
            }
            if ($orderClientType == 'SaaS' && $orderClientType != null) {
                $paymentType = 105;
                $totalSales = 0;
                $valueCollection = 0;
            }
            if ($shippingMethodId != 'mipaquete.com envío contraentrega'
            && $order->payment_method != 'cod') {
                $paymentType = 101;
                $totalSales= 0;
                $valueCollection = 0;
            }
            if (get_option('mpq_pickup') == 1) {
                $requestPickup = 'false';
            }
            else{
                $requestPickup = 'true';
            }
            ///////////// end block validations/////////////////////
            $data = <<<DATA
            {
                "sender": {
                "name": "${orderName}",
                "surname": "N/A",
                "cellPhone": "${orderPhone}",
                "prefix": "+57",
                "email": "${orderEmail}",
                "pickupAddress": "${orderAddress}",
                "nit": "${orderNit}",
                "nitType": "${orderNitType}"
            },
            "receiver": {
                "name": "${receiverName}",
                "surname": "N/A",
                "email": "${receiverEmail}",
                "prefix": "+57",
                "cellPhone": "${receiverPhone}",
                "destinationAddress": "${receiverAddress}",
                "nit": "${receiverNit}",
                "nitType": "${receiverNitType}"
            },
            "productInformation": {
                "quantity": 1,
                "width": ${width},
                "large": ${length},
                "height": ${height},
                "weight": ${weight},
                "forbiddenProduct": true,
                "productReference": "${productTotalSku}",
                "declaredValue": ${totalValorization}
            },
            "locate": {
                "originDaneCode": "${orderLocationCode}",
                "destinyDaneCode": "${receiverLocationCode}"
            },
                "channel": "WOOCOMMERCEV2",
                "deliveryCompany": "${deliveryCompanyId}",
                "description": "${description}",
                "comments": "${receiverNote}",
                "paymentType": ${paymentType},
                "valueCollection": ${valueCollection},
                "requestPickup": ${requestPickup} ,
                "adminTransactionData": {
                    "saleValue": ${totalSales}
                }
            }
            DATA;
            echo $data;
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            $resp = curl_exec($curl);
            $resultDataOrder = json_decode($resp, true);
            if ($resultDataOrder['message'] == 'Envio generado correctamente') {
                sleep(5);
                returnGetSending($resultDataOrder['mpCode'], $idPost);
            }
            elseif($resultDataOrder['message']['detail'] == 'there was an error in cash service') {
                $order->add_order_note(sprintf("Error <b style='color: red;'> No cuentas con saldo suficiente para realizar envíos, 
                por favor recarga tu cuenta y realiza nuevamente el envío. Código del error %s", $resultDataOrder['message']['code'] . $data . "</b> Ingresa a tu perfil haciendo clic <a href='https://app.mipaquete.com/ingreso' target='_blank' rel='noopener'>aquí</a> y haz tu recarga" ));
            }
            elseif(isset($resultDataOrder['message']['detail']['message'])){
                $order->add_order_note(sprintf("Hubo un error al generar el envío. <b style='color: red;'> Código del error  %s", $resultDataOrder['message']['code'] . " - " . $resultDataOrder['message']['detail']['message']  . $data ." <br></b> 
                Comunícate a soporte@mipaquete.com con este código y te ayudaremos a solucionarlo, porfavor adjunta los pantallazos de la configuración del plugin, zonas de envío y de los productos que se encuentran en esta orden (medidas y peso). " ));
            }
            else {
                $order->add_order_note(sprintf("Hubo un error al generar el envío. <b style='color: red;'> Código del error  %s", $resultDataOrder['message']['code'] . "-" . $resultDataOrder['message']['detail']  . $data . " <br></b> 
                Comunícate a soporte@mipaquete.com con este código y te ayudaremos a solucionarlo, porfavor adjunta los pantallazos de la configuración del plugin, zonas de envío y de los productos que se encuentran en esta orden (medidas y peso). " ));
            }
        }
    }
    add_action( 'woocommerce_order_status_changed' , 'sendingMipaquete');
