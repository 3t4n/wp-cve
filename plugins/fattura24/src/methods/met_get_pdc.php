<?php

namespace fattura24;

if (!defined('ABSPATH')) exit;

require_once FATT_24_CODE_ROOT . 'api/api_wrapper.php';

//lista pdc
function fatt_24_getPdc()
{
    global $f24_pdc;
    $listaNomi = array();
    $listaNomi['Nessun Pdc'] = __('None', 'fattura24');
    
    if (is_array($f24_pdc) && $f24_pdc['code'] !== 200) {
        $message_displayed = $f24_pdc['disp_message'];
        return $listaNomi;
    }
    
    $xml = simplexml_load_string(mb_convert_encoding((string) $f24_pdc, 'UTF-8', mb_list_encodings()));
    if (is_object($xml)) {
        foreach ($xml->pdc as $pdc) {
            $listaNomi[(int)$pdc->id] = str_replace('^', '.', (string)$pdc->codice) .
            ' - ' . str_replace('\'', '\\\'', (string)$pdc->descrizione);
        } // visualizza la descrizione e non l'id per migliore esperienza d'uso
    }
    //else
    return $listaNomi;
}