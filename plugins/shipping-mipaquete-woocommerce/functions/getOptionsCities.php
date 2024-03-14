<?php
function getCitiesOption(){
    $apiKey = returnGenerateApiKey();
    $url = getUrlApi() . 'getLocations';
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $headers = array(
        "session-tracker: a0c96ea6-b22d-4fb7-a278-850678d5429c",
        "apikey:" . $apiKey,
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $resultGetLocations = curl_exec($curl);
    curl_close($curl);
    $resultGetLocationsJson = json_decode($resultGetLocations, true);
    $options = array();
    foreach ( $resultGetLocationsJson as $result ) {
        $options[0] = "SELECCIONE LA CIUDAD";
        $options[$result['locationCode']] = $result['locationName'] . "/" . $result['departmentOrStateName'];
        
    }
    return $options;
}
