<?php

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

function fatt_24_used_for_shipping_icon($param) {
    if ('1' == $param) {
       //$icon = fatt_24_span(array('class' => 'dashicons dashicons-yes', 'style' => 'color: green;'), array());
       $icon = fatt_24_ok_icon();
    } else {
       $icon = fatt_24_ko_icon();
       //$icon = fatt_24_span(array('class' => 'dashicons dashicons-no', 'style' => 'color: red;'), array());
    }
    return $icon;
}

/**
 * Lista codici natura aggiornati
 */
function fatt_24_getNaturaOptions()
{
    $html = __("<select name='tax_code' class='postform'>", 'fattura24');
    $html .= __("<option value='Scegli' > Choose an item... </option>", 'fattura24');
    $html .= __("<optgroup label='N1'>", 'fattura24');
    $html .= __("<option value='N1'> N1 - escluse ex art.15 </option>", 'fattura24');
    $html .= __("</optgroup>", 'fattura24');
    $html .= __("<optgroup label='N2'>", 'fattura24');
    $html .= __("<option value='N2' disabled> N2 - non soggette </option>", 'fattura24');
    $html .= __("<option value='N2.1'> N2.1 - non soggette ad IVA ai sensi degli artt. da 7 a 7-septies del DPR 633/72 </option>", 'fattura24');
    $html .= __("<option value='N2.2'> N2.2 - non soggette - altri casi </option>", 'fattura24');
    $html .= __("</optgroup>", 'fattura24');
    $html .= __("<optgroup label='N3'>", 'fattura24');
    $html .= __("<option value='N3' disabled> N3 - non imponibili </option>", 'fattura24');
    $html .= __("<option value='N3.1'> N3.1 - non imponibili - esportazioni </option>", 'fattura24');
    $html .= __("<option value='N3.2'> N3.2 - non imponibili  - cessioni intracomunitarie </option>", 'fattura24');
    $html .= __("<option value='N3.3'> N3.3 - non imponibili  - cessioni verso San Marino </option>", 'fattura24');
    $html .= __("<option value='N3.4'> N3.4 - non imponibili  - operazioni assimilate alle cessioni all'esportazione </option>", 'fattura24');
    $html .= __("<option value='N3.5'> N3.5 - non imponibili  - a seguito di dichiarazioni d'intento </option>", 'fattura24');
    $html .= __("<option value='N3.6'> N3.6 - non imponibili - altre operazioni che non concorrono alla formazione del plafond </option>", 'fattura24');
    $html .= __("</optgroup>", 'fattura24');
    $html .= __("<optgroup label='N4'>", 'fattura24');
    $html .= __("<option value='N4'> N4 - esenti </option>", 'fattura24');
    $html .= __("</optgroup>", 'fattura24');
    $html .= __("<optgroup label='N5'>", 'fattura24');
    $html .= __("<option value='N5'> N5 - regime del margine / Iva non esposta in fattura </option>", 'fattura24');
    $html .= __("</optgroup>", 'fattura24');
    $html .= __("<optgroup label='N6'>", 'fattura24');
    $html .= __("<option value='N6' disabled> N6 - inversione contabile </option>", 'fattura24');
    $html .= __("<option value='N6.1'> N6.1 - inversione contabile - cessione di rottami e altri materiali di recupero </option>", 'fattura24');
    $html .= __("<option value='N6.2'> N6.2 - inversione contabile - cessione di oro e argento puro </option>", 'fattura24');
    $html .= __("<option value='N6.3'> N6.3 - inversione contabile - subappalto nel settore edile </option>", 'fattura24');
    $html .= __("<option value='N6.4'> N6.4 - inversione contabile - cessione di fabbricati - cessione di fabbricati </option>", 'fattura24');
    $html .= __("<option value='N6.5'> N6.5 - inversione contabile - cessione di telefoni cellulari </option>", 'fattura24');
    $html .= __("<option value='N6.6'> N6.6 - inversione contabile - cessione di prodotti elettronici </option>", 'fattura24');
    $html .= __("<option value='N6.7'> N6.7 - inversione contabile - prestazioni comparto edile e settori connessi </option>", 'fattura24');
    $html .= __("<option value='N6.8'> N6.8 - inversione contabile - operazioni settore energetico </option>", 'fattura24');
    $html .= __("<option value='N6.9'> N6.9 - inversione contabile - altri casi </option>", 'fattura24');
    $html .= __("</optgroup>", 'fattura24');
    $html .= __("<optgroup label='N7'>", 'fattura24');
    $html .= __("<option value='N7'> N7 - IVA assolta in altro stato UE </option>", 'fattura24');
    $html .= __("</optgroup>", 'fattura24');
    $html .= __("</select>", 'fattura24');

    return $html;
}

function fatt_24_get_natura_records($tax_id = null) {
    global $wpdb;
    $prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
    $blog_id = is_multisite() ? get_current_blog_id() : 1;
    $table_name = $prefix . 'fattura_tax';
    if (isset($tax_id)) {
        $sql = "SELECT tax_id, tax_code, used_for_shipping FROM $table_name WHERE tax_id = ".$tax_id ." AND blog_id = ".$blog_id;
    } else {
        $sql = "SELECT tax_id, tax_code, used_for_shipping FROM $table_name WHERE blog_id = ". $blog_id; 
    }

    $result = $wpdb->get_results($sql);
    return $result;
}

function fatt_24_get_used_for_shipping_id($param) {
    $key = array_search('1', array_column($param, 'used_for_shipping'));
    $tax_id = false !== $key ? $param[$key]->tax_id : 0;
    return $tax_id;
}

function fatt_24_get_zero_shipping_tax_natura()
{
    $resultArray = [];
    $natura_records = fatt_24_get_natura_records();
    
    if (!empty($natura_records)) {
        $key = array_search(1, array_column($natura_records, 'used_for_shipping'));
        $resultArray = $key ? $natura_records[$key] : $natura_records[0];
    }

    return $resultArray;
}