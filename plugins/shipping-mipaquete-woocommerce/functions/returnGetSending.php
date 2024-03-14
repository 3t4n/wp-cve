<?php
// get data sending allow show city en edit order
function returnGetSending($mp_code, $order) {
    $apikeyConfig = returnGenerateApiKey();
    $url = getUrlApi() . 'getSendings/1';

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = <<<DATA
    {
        "pageSize": 1,
        "mpCode": ${mp_code}
    }
    DATA;
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json',
        'session-tracker:a0c96ea6-b22d-4fb7-a278-850678d5429c',
        'apikey:' . $apikeyConfig,
    ));
    $resultGetSending = curl_exec($curl);
    curl_close($curl);
    $order = new WC_Order($order);
    
    $resultGetSendingJson = json_decode($resultGetSending, true);
    $codeMp = $resultGetSendingJson['sendings'][0]['Código mipaquete'];
    $guideNumber = $resultGetSendingJson['sendings'][0]['Número de Guía'];
    $deliveryCompany = $resultGetSendingJson['sendings'][0]['Transportadora'];
    $deliveryState = $resultGetSendingJson['sendings'][0]['Estado actual del envío'];
    $deliverySaleValue = $resultGetSendingJson['sendings'][0]['Valor de la Venta'];
    
    if (isset($resultGetSendingJson['sendings'][0]['pdfGuide'][0])) {
        $pdfGuide = $resultGetSendingJson['sendings'][0]['pdfGuide'][0];
    }
    if (isset($resultGetSendingJson['sendings'][0]['pdfGuide'][1])) {
        $pdfRelation = $resultGetSendingJson['sendings'][0]['pdfGuide'][1];
    }

    $detailsShipping = "Los detalles de tu envío son los siguientes:<br>";
    $detailsShipping .=  "Código de mipaquete es: " . $codeMp . "<br>";
    $detailsShipping .=  "La guía es: " . $guideNumber . "<br>";
    $detailsShipping .=  "La transportadora es: " . $deliveryCompany . "<br>";
    $detailsShipping .=  "Estado actual del envío: " . $deliveryState . "<br>";
    if($deliveryState =='Procesando tu envío'){
        $detailsShipping .= "<b>Tu envío aun no ha terminado de procesarse, dirígete a
        <a href='https://app.mipaquete.com/historial-envios' rel='noopener'>app.mipaquete.com/historial-envios</a><br>
        Espera un momento y descarga la guía <br></b>";
    }

    $detailsShipping .= isset($pdfGuide) ?
    "Descarga la guía  <a href='" . $pdfGuide ."' target='_blank'>aquí</a>" :
    "No se generó la guía de envío";
    $detailsShipping .= isset($pdfRelation) ?
    "<br>Descarga la relación de envío  <a href='" . $pdfRelation . "' target='_blank' rel='noopener'>aquí</a>" :
    "<br>No tiene relación de envío";
    $order->add_order_note(sprintf("<b> %s  ", $detailsShipping. "</b>" ));

}
