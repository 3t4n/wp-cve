<?php

namespace fattura24;

if (!defined('ABSPATH')) exit;

require_once FATT_24_CODE_ROOT . 'api/api_wrapper.php';


/**
 * Restituisce la chiamata API per l'elenco pdc
 */
function fatt_24_get_pdc() {
    return fatt_24_api_call('GetPdc', array(), FATT_24_API_SOURCE);
}