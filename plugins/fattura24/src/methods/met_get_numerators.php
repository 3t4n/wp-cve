<?php

namespace fattura24;

if (!defined('ABSPATH')) exit;

require_once FATT_24_CODE_ROOT . 'api/api_wrapper.php';

// lista sezionali in F24
function fatt_24_getSezionale($idTipoDocumento)
{
    global $f24_sezionali;
    $listaNomi = array();
    // questa variabile la uso se fallisce la chiamata API (esempio);
    $listaNomi['Predefinito'] = __('Default', 'fattura24');

    if (is_array($f24_sezionali) && $f24_sezionali['code'] !== 200) {
        $message_displayed = $f24_sezionali['disp_message'];
        return $listaNomi;
    }
    $xml = simplexml_load_string(mb_convert_encoding((string) $f24_sezionali, 'UTF-8', mb_list_encodings()));
    //fatt_24_trace('xml :', $xml);
    if (is_object($xml)) {
        // se ottengo la lista dei sezionali non mi serve - Davide Iandoli 6.06.2022
        if (!empty($xml)) {
            unset($listaNomi['Predefinito']);
        }
        foreach ($xml->sezionale as $sezionale) {
            foreach ($sezionale->doc as $doc) {
                //fatt_24_trace('risultato :', $doc);
                if ((int)$doc->id == $idTipoDocumento && (int)$doc->stato == 1) {
                    $listaNomi[(int)$sezionale->id] = (string)$sezionale->anteprima;
                } else if ((int)$doc->id == $idTipoDocumento && (int)$doc->stato == 2) {
                    $listaNomi[(int)$sezionale->id] = (string)$sezionale->anteprima . __(' (Default)', 'fattura24');
                }
                 // visualizza l'anteprima e non l'id per migliore esperienza d'uso
            }
        }
    }
    //else
    return $listaNomi;
}