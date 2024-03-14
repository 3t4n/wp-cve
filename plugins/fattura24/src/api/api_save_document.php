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
/**
* Funzione che ne chiama altre per gestire le varie azioni di salvataggio documento in F24
* @param $order_id
* @param $docType
*/

//require_once FATT_24_CODE_ROOT . 'api_call.php';
//require_once __DIR__ . '/api_wrapper.php';

$filesToInclude = [
    'api_call.php',
    'api/api_wrapper.php'
];

foreach ($filesToInclude as $file) {
    require_once FATT_24_CODE_ROOT . $file;
}

/**
 * Edit del 2.12.2021 : aggiunto il parametro $creationTime
 * lo passo a fatt_24_document_exists per verificare che non ci sia un documento dello stesso tipo
 * già creato in Fattura24 negli ultimi 5 minuti
 */
function fatt_24_store_fattura24_doc($order, $docType, $creationTime)
{
    /**
     * Davide Iandoli 17.09.2021 - verifico che non ci siano errori nell'xml
     * prima di fare la chiamata, in caso positivo manipolo la stringa per mandare un xml errato
     */
    $order_id = $order->get_id();
    $order_to_xml_result = fatt_24_order_to_XML($order, $docType);

    // messaggi di errore
    $document_error = $order_to_xml_result['fatt_24_error']['error'];
    $err_message = strip_tags($order_to_xml_result['fatt_24_error']['message']);
    $xml = $order_to_xml_result['xml'];

    if ($document_error) {
        $documentElement = ['<Document>', '</Document>'];
        $documentErrorElement = ['<DocumentError>', '</DocumentError>'];
        $newXml1 = str_replace($documentElement, $documentErrorElement, $xml);
        $docErrorPos = strpos($newXml1, $documentErrorElement[0]) + strlen($documentErrorElement[0]);
        //cfr https://stackoverflow.com/questions/8251426/insert-string-at-specified-position
            $xml = substr_replace($newXml1, $err_message, $docErrorPos, 0); // inserisce il ErrorMsg dopo DocumentError
    }

   
    /**
     * Il parametro idRequest evita la creazione di documenti duplicati a fronte della stessa richiesta;
     * in un ambiente di test potrei trovarmi a dover fare una richiesta con lo stesso id
     * esempio: reset totale dell'ambiente (e azzeramento del numero degli ordini). 
     * Per questo motivo mando il parametro vuoto se è attivo il Debug di fattura24
     * Davide Iandoli 25.11.2022
     */

    /**
     * Davide Iandoli edit del 5.09.2023: ora idRequest è univoco anche in network di siti WP
     * nota: inserisco tutto in un try/catch perché la funzione is_multisite() è stata inserita in WP 3.0.0
     * e la funzione get_current_blog_id in WP 3.1.0
     */ 
    $blog_id = 1;
    
    try{
        if (is_multisite()) {
            $blog_id = get_current_blog_id();
        } 
    } catch (Exception $e) {
        // non faccio nulla
    }

    $idRequest = (int) get_option('fatt-24-log-enable') === 1 ? '': $docType . $order_id . '_' . $blog_id;
    //fatt_24_trace('id richiesta :', $idRequest);
       
    /*
     * se so che c'è un errore non faccio partire la chiamata: 
     * in questo modo posso reinnescarla con lo stesso idRequest senza problemi
     */ 
    if (!$document_error) {
        $res = fatt_24_api_call('SaveDocument', array('xml' => $xml, 'idRequest' => $idRequest), FATT_24_API_SOURCE);
    }
    
    fatt_24_trace('Store Fattura24 Doc', $order_id, $docType, $xml, $res); // registro tutto nel tracciato
    $status = fatt_24_get_order_status($order); // leggo lo stato dell'ordine
    $pdfPath = implode('', fatt_24_build_pdf_path($docType, $order_id));

    if (!is_array($res) && !$document_error) { // ho un array solo se la risposta del server è diversa da 200
            $ans = simplexml_load_string($res); //risposta chiamata API

            /** se $document_error è vero passo nell'else in questo modo il pulsante crea documento
             * sarà visibile nello stato degli ordini
             */
        if (is_object($ans)) {
            if ((int) $ans->returnCode == -4) {
                $message = __('Processing data in Fattura24, retry later', 'fattura24');
                fatt_24_order_status_set_error($status, sprintf(__('%s - order %d', 'fattura24'), $message, $order_id));
            } else {
                fatt_24_order_status_set_doc_data($status, (int)$ans->returnCode, (string)$ans->description, (int)$ans->docId, $docType, (string)$ans->docNumber, $pdfPath);
            }
            /**
             * qui definisco un nuovo post_meta per semplificare la nuova funzione filtro
             * viene creato solo se la chiamata ha esito positivo.
             * Davide Iandoli 18.08.2020
             */
            if ($docType === 'C') {
                $wpPostMeta = 'fatt24_order_docId';
                $value = (int)$ans->docId; // con esito positivo ci metto il docId
            } else {
                $wpPostMeta = 'fatt24_invoice_docId';
                $value = (int)$ans->docId;
            }
            $order->update_meta_data($wpPostMeta, $value);
            $rc = true;

        } else {
            $message = (isset($err_message) && !empty($err_message)) ? $err_message : 'Unknown error occurred';
            fatt_24_order_status_set_error($status, sprintf(__('%s - order %d', 'fattura24'), $message, $order_id));
            $rc = false;
        }
        //fatt_24_store_order_status($order_id, $status);
        //return $rc;
    } else {
        /**
         * Errori client => 400 - 499 (es.: errore 404)
         * errori server => 500 - 599 (es.: errore 504 timeout)
         */
        $conn_err_regex = '/[4|5]\d{2}/';
        if (isset($res['code']) && preg_match($conn_err_regex, $res['code'])) {
            $err_message = sprintf(__('Fattura24 API error connection code %s', 'fattura24'), $res['code']);
        }

        // qui ci passo quando definisco <DocumentError> nell'xml, e riporto il contenuto del tag <ErrorMsg>
        $message = (isset($err_message) && !empty($err_message)) ? $err_message : 'Unknown error occurred';
        fatt_24_order_status_set_error($status, sprintf(__('%s - order %d', 'fattura24'), $message, $order_id));
        $rc = false;
    }
    $order->save_meta_data();
    fatt_24_store_order_status($order, $status);
    return $rc;
}
