<?php

/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * File di gestione delle chiamate API
 * conversione dei dati dell'ordine in formato xml
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

//require_once FATT_24_CODE_ROOT . 'api_call.php';
//require_once __DIR__ . '/api_wrapper.php';
//require_once FATT_24_CODE_ROOT .'/methods/met_get_file.php';
$filesToInclude = [
    'api/api_wrapper.php',
    'methods/met_get_file.php'
]; 

foreach ($filesToInclude as $file) {
    require_once FATT_24_CODE_ROOT . $file;
}


add_filter(FATT_24_DOC_STORE_FILE, __NAMESPACE__ . '\fatt_24_store_PDF_file', 10, 3);
// scarico il PDF in locale

function fatt_24_download_PDF_order($order)
{
    $order_id = $order->get_id();
    $docResult = fatt_24_is_recorded_on_f24_postmeta($order, 'C');
    /**
     * Se il pdf già esiste e innesco l'azione di aggiornamento
     * devo aggiornare il link nello status 
     * e cancellare il vecchio file, in questo modo
     * nella tabella degli ordini aggiorno 
     * il link per la visualizzazione
     * Davide Iandoli 04.04.2022
     */
    $status = fatt_24_get_order_status($order);
    $newFile = implode('', fatt_24_build_pdf_path('C', $order_id));
    $oldPdfPath = '';
    if (isset($status['history']['pdfPath'])) {
        $oldPdfPath = $status['history']['pdfPath'];
    } else {
        if (isset($status['pdfPath'])) {
            $oldPdfPath = $status['pdfPath'];
        }
    }     
      
    if ($newFile !== $oldPdfPath) {
        if (file_exists($oldPdfPath)) {
            unlink($oldPdfPath);
        }
        if (isset($status['history'])) {
            $status['history']['pdfPath'] = $newFile;
        } else {
            if (isset($status['pdfPath'])) {
                $status['pdfPath'] = $newFile;
            } 
        }
    }

    $docId = $order->get_meta('fatt24_order_docId', true);
    if (empty($docId)) {
        $docId = !isset($docResult['docId']) ? '' : $docResult['docId'];
    }
    $orderPdfExists = false;

    if ($docId) {
        $PDF = fatt_24_api_call('GetFile', array('docId' => $docId), FATT_24_API_SOURCE); //chiamo le API per scaricare il documento
        if (substr($PDF, 0, 4) == '%PDF') {
            $result = fatt_24_store_PDF_file($status, $order_id, $PDF, FATT_24_DT_ORDINE);
            $orderPdfExists = true;
        } else {
            $ans = simplexml_load_string($PDF);
            if ($ans) {
                fatt_24_order_status_set_error($status, !is_object($ans) ? error_get_last() : strval($ans->description));
            } else {
                fatt_24_order_status_set_error($status, sprintf(__('Unknown error occurred while downloading PDF for order %d', 'fattura24'), $order_id));
            }
            $orderPdfExists = false;
        }
        fatt_24_store_order_status($order, $status);
    }
    return $orderPdfExists;
}

function fatt_24_download_PDF_invoice($order)
{
    $order_id = $order->get_id();
    $invType = fatt_24_get_invoice_doctype();
    $invResult = fatt_24_is_recorded_on_f24_postmeta($order, $invType);
    $invDocId = $order->get_meta('fatt24_invoice_docId', true);
    if (empty($invDocId)) {
        $invDocId = !isset($invResult['docId']) ? '' : $invResult['docId'];
    }
    
    /**
     * Se il pdf già esiste e innesco l'azione di aggiornamento
     * devo aggiornare il link nello status 
     * e cancellare il vecchio file, in questo modo
     * nella tabella degli ordini aggiorno 
     * il link per la visualizzazione
     * Davide Iandoli 04.04.2022
     */
    $status = fatt_24_get_order_status($order);
    $newFile = implode('', fatt_24_build_pdf_path($invType, $order_id));
    if (isset($status['pdfPath']) && $status['pdfPath'] !== $newFile && $status['docType'] == $invType) {
        if (file_exists($status['pdfPath'])) {
            unlink($status['pdfPath']);
        }
        $status['pdfPath'] = $newFile;
    }  

    $invoicePdfExists = false;

    if ($invDocId) {
        $PDF = fatt_24_api_call('GetFile', array('docId' => $invDocId), FATT_24_API_SOURCE); //chiamo le API per scaricare il documento
        if (substr($PDF, 0, 4) == '%PDF') {
            $result = fatt_24_store_PDF_file($status, $order_id, $PDF, $invType);
            $invoicePdfExists = true;
        } else {
            $ans = simplexml_load_string($PDF);
            if ($ans) {
                fatt_24_order_status_set_error($status, !is_object($ans) ? error_get_last() : strval($ans->description));
            } else {
                fatt_24_order_status_set_error($status, sprintf(__('Unknown error occurred while downloading PDF for order %d', 'fattura24'), $order_id));
            }
            $invoicePdfExists = false;
        }
        fatt_24_store_order_status($order, $status);
    }
    return $invoicePdfExists;
}
