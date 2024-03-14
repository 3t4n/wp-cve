<?php

/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com> 
 */
namespace fattura24;

if (!defined('ABSPATH')) exit;

require_once FATT_24_CODE_ROOT . 'api/api_wrapper.php';

/**
 * Restituisce la chiamata API per l'elenco modelli
 */
function fatt_24_get_templates() {
    return fatt_24_api_call('GetTemplate', array(), FATT_24_API_SOURCE);
}