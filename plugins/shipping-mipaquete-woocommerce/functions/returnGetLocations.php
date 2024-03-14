<?php
function returnGetLocations($shippingCity) {
    
    $infoUserLocationCode = ReturnGetUser();
    $apikeyConfig = returnGenerateApiKey();
    $url = getUrlApi() . 'getLocations?locationCode=' . $shippingCity;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json',
        'session-tracker:a0c96ea6-b22d-4fb7-a278-850678d5429c',
        'apikey:' . $apikeyConfig,
    ));
    $resultGetLocation = curl_exec($curl);
    curl_close($curl);
    $resultGetLocationJson = json_decode($resultGetLocation, true);
    $nameCity = $resultGetLocationJson[0]['locationName'];
    $nameState = $resultGetLocationJson[0]['departmentOrStateName'];
    $locationCodeCity = $resultGetLocationJson[0]['locationCode'];

    return array($nameCity, $nameState, $locationCodeCity);
}