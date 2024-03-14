<?php

namespace fattura24;

if (!defined('ABSPATH')) exit;

require_once FATT_24_CODE_ROOT . 'api/api_wrapper.php';

//lista templates
function fatt_24_getTemplate($isOrder)
{
    global $templates;
    $listaNomi = array();
    $listaNomi['Predefinito'] = __('Default', 'fattura24');
    if (is_array($templates) && $templates['code'] !== 200) {
        $message_displayed = $templates['disp_message'];
        return $listaNomi;
    }
    
    $xml = simplexml_load_string(mb_convert_encoding((string) $templates, 'UTF-8', mb_list_encodings()));
    if (is_object($xml)) {
        $listaModelli = $isOrder ?  $xml->modelloOrdine : $xml->modelloFattura;
        foreach ($listaModelli as $modello) {
            $listaNomi[(int)$modello->id] = str_replace('\'', '\\\'', (string)$modello->descrizione) . " (ID: " . (int)$modello->id . ")";
        }
    }
    //else
    return $listaNomi;
}