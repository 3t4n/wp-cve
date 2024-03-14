<?php
//reurn info user for createSending
function returnGetUser() {
    $apikeyConfig = returnGenerateApiKey();
    $url = getUrlApi() . 'getUser';

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json',
        'session-tracker:a0c96ea6-b22d-4fb7-a278-850678d5429c',
        'apikey:' . $apikeyConfig,
    ));
    $resultGetUser = curl_exec($curl);
    curl_close($curl);
    $resultGetUserJson = json_decode($resultGetUser, true);
    $name = $resultGetUserJson['businessName'];
    $address = $resultGetUserJson['address'];
    $locationCode = $resultGetUserJson['locationCode'];
    $email = $resultGetUserJson['email'];
    $cellPhone = $resultGetUserJson['cellPhone'];
    $documentNumber = $resultGetUserJson['documentNumber'];
    $documentType = $resultGetUserJson['documentType'];
    if ($resultGetUserJson['clientType'] == "SaaS" ) {
        $clientType = $resultGetUserJson['clientType'];
    } elseif (empty($resultGetUserJson)) {
        $clientType = '';
    } else {
        $clientType = 'Usuario Mipaquete';
    }
    $dataUser = array($name, $address, $locationCode, $email, $cellPhone, $documentNumber, $documentType, $clientType);
    return apply_filters( 'shipping_mipaquete_data_user', $dataUser);
}