<?php

namespace fattura24;

/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * Descrizione: gestisce messaggi di avviso ed errore lato admin
 *
 */

if (!defined('ABSPATH')) {
    exit;
}

// serve per getNatureColumn
require_once FATT_24_CODE_ROOT . 'methods/met_save_document.php';

/**
 * Funzione per i messaggi, usata anche da settings.php
 * @param $message, $type, $dismissible
 *
 * type predefinito = 'success' => evidenziato in verde
 * 'warning' => evidenziato in giallo
 * 'error' => evidenziato in rosso
 *
 * $closeIcon => true => mostra la 'x' per chiudere il messaggio
 *
 * mostra messaggi di successo, warning, errore con icona per chiuderli
 * Davide Iandoli 18.01.2021
 */
// mostra il messaggio in una div apposita
function fatt_24_getMessageHtml($message, $type = 'success', $closeIcon = false)
{
    $dismissible = $closeIcon ? ' is-dismissible' : '';
    $plugin_name = FATT_24_PLUGIN_DATA['Name'];
    return  '<div id="setting-error-settings_updated" class="notice notice-' .
             $type . $dismissible .'"><p><strong>' .$plugin_name . ' - ' . $message . '</strong></p></div>';
}

/**
 * Messaggio di cortesia visualizzato dopo l'invio di un ticket dal form
 * nella sezione 'Support'. Appare a fianco al pulsante Invia
 */
function fatt_24_get_result_message($message = '', $type = 'success') {
    $color = $type == 'success' ? 'green' : 'red';
    $style = 'margin-top:-26px; margin-left: 201px; font-size:14px; color:' . $color . ';';
    if (!empty($message)) {
        return sprintf('<div style="%s">%s</div>', $style, $message);
    } 
    return '';
}

/**
 * Messaggio WooCommerce non installato o non attivo
 */
function fatt_24_getMessageWooNotInstalled()
{
    $isWooCommerceInstalled = fatt_24_isWooCommerceInstalled();
    $message = !$isWooCommerceInstalled ? __('This plugin requires WooCommerce installed', 'fattura24') : '';

    $returnedMessage = '';
    if (!empty($message)) {
        $returnedMessage = fatt_24_getMessageHtml($message, 'error', true);
    }

    return $returnedMessage;
}

/**
 * Messaggio WooCommerce Fattura24 installato o attivo
 */

function fatt_24_getMessageWooFatturaInstalled()
{
    $isWooFatturaInstalled = fatt_24_isWooFatturaInstalled();
    $message = $isWooFatturaInstalled ? __('To use this official plugin please deactivate or remove WooCommerce Fattura24', 'fattura24') : '';

    $returnedMessage = '';
    if (!empty($message)) {
        $returnedMessage = fatt_24_getMessageHtml($message, 'error', true);
    }

    return $returnedMessage;
}

/**
 * Calcolo tasse non abilitato in WooCommerce
 */
function fatt_24_getMessageTaxNotEnabled()
{
    if (!fatt_24_wc_calc_tax_enabled()) {
        return fatt_24_getMessageHtml(
            __('To use Fattura24 correctly you have set up taxes in WooCommerce!', 'fattura24'),
            'error',
            true
        );
    }
}

/**
 * Messaggio di nessuna aliquota configurata per la spedizione
 * se la creazione dell'ordine è disabilitata non lo visualizzo;
 * se non è FE restituisce un 'warning',
 * altrimenti in errore
 */
function fatt_24_getMessageNoShippingRate()
{
    if (!fatt_24_shippingTaxDisabled()) {
        $vatNatura = fatt_24_getNatureColumn();
        $disabledOrder = get_option(FATT_24_ORD_CREATE) == '0';
        $displayMessage = !fatt_24_existingShippingTaxes() && empty($vatNatura) && !$disabledOrder;
        $fattEl = fatt_24_get_invoice_doctype() === FATT_24_DT_FATTURA_ELETTRONICA;
        $type = $fattEl ? 'error' : 'warning';
        if ($displayMessage) {
            return fatt_24_getMessageHtml(
                __('You have to set up at least one tax rate for shipping', 'fattura24'),
                $type,
                true
            );
        }
    }
}

/**
 *  Messaggi natura IVA
 */
function fatt_24_getNaturaMessages()
{
    // definizioni
    $oldNaturaArray = ['N2', 'N3', 'N6'];
    $vatNaturaRecords = fatt_24_get_natura_records();
    $errorNaturaCode = '';
    $isInvoiceDisabled = get_option('fatt-24-inv-create') == '0';
    foreach ($vatNaturaRecords as $record) {
        if (in_array($record->tax_code, $oldNaturaArray) && !$errorNaturaCode) {
            $errorNaturaCode = $record->tax_code;
        }
    }
    
    $zeroRates = fatt_24_getZeroRates();
    $zeroRatesCount = !empty($zeroRates);
    $emptyVatNatura = empty($vatNaturaRecords);
    $fattEl = fatt_24_get_invoice_doctype() === FATT_24_DT_FATTURA_ELETTRONICA;
    $message = '';
    $addLink = ' | ' . '<a href="'. admin_url('admin.php?page=fatt-24-tax').'">' 
    . __('Update settings here', 'fattura24') . '</a>';
    $type = 'error';
    
    if ($emptyVatNatura) {
        if ($fattEl && $zeroRatesCount) {
            $message = __('You have to set up Natura for each zero tax rate', 'fattura24'). $addLink;
        } elseif ($zeroRatesCount) {
            $message = __('You have to set up Natura for each zero tax rate in Electronic Invoices', 'fattura24') . $addLink;
            $type = 'warning';
        }
    } elseif ($errorNaturaCode) {
            $type = $fattEl ? 'error' : 'warning';
            $message = sprintf(__('The code %s is no more allowed for invoices issued since 01/01/2021', 'fattura24'),
                       $errorNaturaCode) . $addLink;
    }
 
    $resultMessage = '';
    if (!empty($message)) {
        $resultMessage .= fatt_24_getMessageHtml($message, $type, true);
    }

    if (fatt_24_isWooCommerceInstalled() && !$isInvoiceDisabled) {
        return $resultMessage;
    }
}

/**
 * Creazione fattura disabilitata
 */
function fatt_24_getMessageInvoiceDisabled()
{
    if (get_option(FATT_24_INV_CREATE) == '0') {
        $message = __('Warning: invoice creation is disabled! Check if this is your desired configuration', 'fattura24');
    }

    $resultMessage = '';
    if (!empty($message)) {
        $resultMessage .= fatt_24_getMessageHtml($message, 'warning');
    }
    return $resultMessage;
}

/**
 *  Messaggio di errore connessione API
 */
function fatt_24_getMessageAPIError($response_code)
{
    $message = sprintf(__('WARNING: connection to Fattura24 API failed, error code %d; please contact our technical service', 'fattura24'), $response_code);
    return fatt_24_getMessageHtml($message, 'error');
}


function fatt_24_getAPINotSetMsg()
{
    $resultMessage = '';
    $apiKey = get_option('fatt-24-API-key');
    
    if (empty($apiKey)) {
        $resultMessage .= fatt_24_getMessageHtml(fatt_24_getApiTestMsg()[0], 'warning', true);
    }
    
    return $resultMessage;
}

/**
 * Messaggio sotto la casella di input della chiave API
 * lo lascio invariato perché  per usare la chiave occorre che sia salvata
 * nelle impostazioni
 */
function fatt_24_getApiInputMessage()
{
    return fatt_24_strong(__('Warning: ', 'fattura24')) .
           __('Enter your API key and save settings before clicking on verify button', 'fattura24');
}

function fatt_24_getApiTestMsg() {
    
    $messages = [
        __('API key not set!', 'fattura24'), 
        __('API key should be long 32 chars!', 'fattura24'), 
        __('Test account!', 'fattura24'), 
        __('Generic error, write us at assistenza@fattura24.com!', 'fattura24'), 
        __('To use this plugin you need to subscribe a Business or Enterprise plan!', 'fattura24'),
        __('API key is not valid!', 'fattura24'),
        __('API key verified!', 'fattura24'),
        __('Total calls in last 24h: ', 'fattura24')
    ];

    return $messages;
}


function fatt_24_getExpirationMsg() {
    /** messaggi fissi */
    $base_messages = [
        __('Subscription expires at: ', 'fattura24'),
        __('Subscription expired at: ', 'fattura24'),
        __('Renew now', 'fattura24')
    ];
  
    /** messaggi con variabile */
    $days = fatt_24_getDays();
    $differentDays = [];
    /**
     * gestione del plurale con la traduzione 
     * cfr.: https://developer.wordpress.org/reference/functions/_n/
     */

    foreach ($days as $day) {
            array_push($differentDays, 
            sprintf(_n(
                        'Subscription expires in %d day', 
                        'Subscription expires in %d days',
                        $day, 'fattura24')
                    , $day)
        );
    }

    /** 
     * Aggiungo i messaggi con variabile all'array dei messaggi fissi
     * perché quelli con variabile contengono già il dominio per
     * la traduzione
     */
    $result = array_merge($base_messages, $differentDays);

    return $result;
}

/*  quanti giorni possono mancare alla scadenza ?
* da 0 a 30 */
function fatt_24_getDays() {
    $days = array();
    for ($i = 0; $i < 31; $i++) {
        array_push($days, $i);
    }
    return $days;
}

/**
 * messaggi di errore legati a chiamate ajax
 * inseriti qui per gestione corretta della traduzione
 * Davide Iandoli 01.02.2023
 */
function fatt_24_get_ajax_messages()
{
    return array(
        'apiText' => fatt_24_getApiInputMessage(),
        'apiTestMsg' => fatt_24_getApiTestMsg(),
        'planExpiration' => fatt_24_getExpirationMsg(),
        'updating' => __('Update in progress...', 'fattura24')
    );
}

/**
 * Messaggio di errore nell'elenco degli ordini
 * metodo usato in methods/met_hooks_general.php
 */
function fatt_24_order_status_message($type, $tooltip)
{
    $background_color = '#ffbc00';
    $label_style = 'margin-left: 1px; margin-top: 1px; margin-right: 6px; font-size:25px; overflow:visible; border: 1px solid #ffffff00;';
    $icon = 'dashicons dashicons-info';

    if ($type == 'error') {
        $background_color = '#ff0000';
        $icon = 'dashicons dashicons-warning';
    }
   
    $label = sprintf('<span style="%s" class="%s"></span>', $label_style, $icon);
    $mark_style = 'margin-top: 0.75px; margin-left:5px; margin-bottom:0.25px; background-color:' . $background_color .'; color:#fff; border-radius:100%;';
    $mark = sprintf('<mark style="%s" class="order-status tips" data-tip="%s">', $mark_style, $tooltip) . $label . '</mark><br />';
    return $mark;
}

/** 
 * Metodo con cui controllo se ci sono estensioni disabilitate
 * o non installate. Cfr. ticket n.: 2205 Desktale
 */
function fatt_24_getExtensionsErr() {
    $list = fatt_24_get_required_extensions();
   
    if (!empty($list)) {
        $message = sprintf(__('These required extensions are not enabled or not installed: %s', 'fattura24'), $list);
        return fatt_24_getMessageHtml($message, 'error');
    }
    return '';
}
