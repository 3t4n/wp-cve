<?php

/*
* Initialize podacie cisla if they are not set
*/
function tsseph_init_parcel_numbers() {

    $tsseph_options= get_option('tsseph_options');

    $parcel_numbers = array(
        //Zmluvní zákazníci
        '14' => array(
            'RozsahPodCisFrom' => (isset($tsseph_options['RozsahPodCisFrom']) ? $tsseph_options['RozsahPodCisFrom'] : ''),
            'RozsahPodCisTo' => (isset($tsseph_options['RozsahPodCisTo']) ? $tsseph_options['RozsahPodCisTo'] : ''),
            'AktualnePodCislo' => (isset($tsseph_options['AktualnePodCislo']) ? $tsseph_options['AktualnePodCislo'] : '')  
        ),
        //Express kuriér
        '8' => array(
            'RozsahPodCisFrom' => '',
            'RozsahPodCisTo' => '',
            'AktualnePodCislo' => '' 
        )        
    );

    return $parcel_numbers;
}

/*
* Get current parcel number (podacie cislo)
* 
* Note: Unfortunately there is a different logic for API and XML (do not uses modulo operation at the end)
*/
function tsseph_get_parcel_number($eph_shipping_method_id, $type) {

    $tsseph_options = get_option('tsseph_options');
    $tsseph_bonus_options = get_option('tsseph_bonus_options');

    $raw_parcel_number = (isset($tsseph_options['PodacieCisla'][$eph_shipping_method_id]['AktualnePodCislo']) ? $tsseph_options['PodacieCisla'][$eph_shipping_method_id]['AktualnePodCislo'] : '');
    $parcel_number = '';

    //Check if bonus functionality enabled
    if ($eph_shipping_method_id == '14' || ($eph_shipping_method_id == '8' && isset($tsseph_bonus_options[1450]) &&  $tsseph_bonus_options[1450]['Enabled'])) {

        if (!empty($raw_parcel_number)) {

            if ($type == 'API') {
                $parcel_number = tsseph_calculate_parcel_number($raw_parcel_number, false);
            }
            else {
                $parcel_number = $raw_parcel_number;
            }

            //Update next parcel number
            $tsseph_options['PodacieCisla'][$eph_shipping_method_id]['AktualnePodCislo'] = tsseph_calculate_parcel_number($raw_parcel_number, true);
            update_option( 'tsseph_options', $tsseph_options );
        }
    }

    return $parcel_number;
}

/*
* Calculate parcel number (ciarovy kod)
*/
function tsseph_calculate_parcel_number($parcel_number, $is_next) {
    preg_match_all('/([0-9]+|[a-zA-Z]+)/',$parcel_number,$matches);

    $number = substr((string) $matches[0][1], 0, 8);
    $modulo = ($number % 11) % 10;

    if ($is_next) {
        return $matches[0][0] .  str_pad(($number + 1), 8, '0', STR_PAD_LEFT) . $matches[0][2];
    }
    else {
        return $matches[0][0] . $number . $modulo . $matches[0][2];
    }
}