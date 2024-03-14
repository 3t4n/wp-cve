<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * gestisce gli stati dell'ordine in WooCommerce
 *
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

$filesToInclude = [
    'constants.php',
    'uty.php',
    'api/api_save_document.php',
    'api/api_save_customer.php',
    'api/api_get_file.php',
    'methods/met_get_file.php'
];

foreach ($filesToInclude as $file) {
    require_once FATT_24_CODE_ROOT . $file;
}


// Elenco degli stati dell'ordine in WC, non cancellare: può tornare utile per futuri sviluppi
/* from https://docs.woocommerce.com/document/managing-orders/

    Pending payment – Order received (unpaid)
    Failed – Payment failed or was declined (unpaid). Note that this status may not show immediately and instead show as pending until verified (i.e., PayPal)
    Processing – Payment received and stock has been reduced- the order is awaiting fulfillment.
    Completed – Order fulfilled and complete – requires no further action
    On-Hold – Awaiting payment – stock is reduced, but you need to confirm payment
    Cancelled – Cancelled by an admin or the customer – no further action required
    Refunded – Refunded by an admin – no further action required
    Authentication required — Awaiting action by the customer to authenticate the transaction and/or complete SCA requirements.
*/

// su grandi wp_postmeta, l'aggiornamento può fallire senza avviso
// tramite questo array implemento un sistema di caching
$last_order_status = array();

// gli stati riguardano i dati trasmessi a F24, li uso per controllare le azioni crea PDF/ visualizza PDF / aggiorna PDF
function fatt_24_store_order_status($order, $status)
{
    global $last_order_status;
    $order_id = $order->get_id();
    $order->update_meta_data(FATT_24_ORDER_INVOICE_STATUS, $status);
    $order->save_meta_data();
    $last_order_status[$order_id] = $status;
}

// restituisce lo stato dell'ordine in F24
function fatt_24_get_order_status($order)
{
    global $last_order_status;
    $order_id = $order->get_id();
    if (isset($last_order_status[$order_id])) {
        return $last_order_status[$order_id];
    }

    $retv = $order->get_meta(FATT_24_ORDER_INVOICE_STATUS, true);
    return $retv;
}

/** 
 * Leggo il vecchio valore in wp_postmeta
 * per aggiornarlo in caso non sia stato registrato
 * in fase di sincronizzazione tabelle con HPOS 
 */
function fatt_24_get_old_order_status($order) {
    global $last_order_status;
    $order_id = $order->get_id();
    if (isset($last_order_status[$order_id])) {
        return $last_order_status[$order_id];
    }
    return get_post_meta($order_id, FATT_24_ORDER_INVOICE_STATUS, true);
}

// aggiunge i campi relativi allo stato dell'ordini e li restituisce aggiornati (la & nel parametro serve a questo)
function fatt_24_order_status_add_fields(&$status, $fields)
{
    $h_save = array();
    if (!is_array($status)) {
        $status = array();
    }

    foreach ($fields as $k => $v) {
        if (isset($status[$k])) {
            $h_save[$k] = $status[$k];
        }
        $status[$k] = $v;
    }

    if (!empty($h_save)) {
        if (isset($status['history'])) {
            $h_save['history'] = $status['history'];
        }
        $status['history'] = $h_save;
    }
}

// imposta i dati (sempre manipolando lo status)
function fatt_24_order_status_set_file_data(&$status, $pdfPath, $docType)
{
    if (isset($status['docType']) && $status['docType'] == $docType) {
        $status['pdfPath'] = $pdfPath;
    } elseif (isset($status['history'])) {
        fatt_24_order_status_set_file_data($status['history'], $pdfPath, $docType);
    }
}

function fatt_24_order_status_set_doc_data(&$status, $returnCode, $description, $docId, $docType, $docNumber, $pdfPath)
{ // added docNumber to wp_postmeta
        fatt_24_order_status_add_fields($status, array(
                                                    'returnCode' => $returnCode,
                                                    'description' => $description,
                                                    'docId' => $docId,
                                                    'docType' => $docType,
                                                    'docNumber' => $docNumber,
                                                    'pdfPath' => $pdfPath
                                                )
        );
        //compact('returnCode', 'description', 'docId', 'docType', 'docNumber', 'pdfPath')); // added docNumber
}

function fatt_24_order_status_set_error(&$status, $error)
{
    fatt_24_order_status_add_fields($status, array('lastErr' => fatt_24_now().' : '.$error));
}

function fatt_24_pdf_actions_order($order_id)
{
    $order = new \WC_Order($order_id);
    $isZeroOrderEnabled = (int) get_option('fatt-24-ord-zero-tot-enable') == 1;
    $isOrderTotalZero = (float) $order->get_total() == 0.00;
    $docType = 'C';
    $order_status = fatt_24_get_order_status($order);

    if ($isZeroOrderEnabled || !$isOrderTotalZero) {
        $wp_nonce = wp_create_nonce(FATT_24_ORDER_ACTIONS_NONCE);
        $pdfLink = fatt_24_get_pdf_link($order_status, $docType)['pdfPath'];
        $pdfExists = isset($pdfLink) && file_exists($pdfLink);
        $pdfLinksOrder = fatt_24_admin_actions($pdfExists);

        $cmd = function ($pdfLinksOrder, $id) use ($docType, $wp_nonce) {
            $cmd = $pdfLinksOrder['cmd'];
            $onclick = "f24_pdfcmd('$id','$cmd','$docType','$wp_nonce')"; // passo il parametro della chiamata ajax
            if ($pdfLinksOrder['enabled']) {
                return fatt_24_a(array('style' =>'text-decoration: none;', 'href'=>'#', 'onclick' => $onclick), fatt_24_update_icon());
            } // pulsante (con stile personalizzato) per creare il documento
            return $pdfLinksOrder['label'];
        };

        $pdf = function ($pdfLinksOrder, $order_id) use ($docType, $pdfLink) {
            if ($pdfLinksOrder['enabled']) {
                return fatt_24_a(array('style' =>'text-decoration: none;', 'href'=>fatt_24_order_PDF_url($pdfLink), 'target'=>'_blank'), fatt_24_pdf_icon());
            }
            return $pdfLinksOrder['label'];
        };
        $h = array(
            $pdf($pdfLinksOrder[0], $order_id),
            $cmd($pdfLinksOrder[1], $order_id)
        );
    
        $html = implode('', $h);
        return $html;
    }
}

function fatt_24_pdf_actions_invoice($order_id)
{
    $invType = fatt_24_get_invoice_doctype();
    $order = new \WC_Order($order_id);
    $isZeroInvoiceEnabled = (int) get_option('fatt-24-inv-zero-tot-enable') == 1;
    $isOrderTotalZero = (float) $order->get_total() == 0.00;
    $order_status = fatt_24_get_order_status($order);


    if ($isZeroInvoiceEnabled || !$isOrderTotalZero) {
        $wp_nonce = wp_create_nonce(FATT_24_ORDER_ACTIONS_NONCE);
        $pdfLink = fatt_24_get_pdf_link($order_status, $invType)['pdfPath'];
        $pdfExists = isset($pdfLink) && file_exists($pdfLink);
        $pdfLinksInvoice = fatt_24_admin_actions($pdfExists);
        $cmd = function ($pdfLinksInvoice, $id) use ($invType, $wp_nonce) {
            $cmd = $pdfLinksInvoice['cmd'];
            $onclick = "f24_pdfcmd('$id','$cmd','$invType','$wp_nonce')"; // passo il parametro della chiamata ajax
            if ($pdfLinksInvoice['enabled']) {
                return fatt_24_a(array('style' =>'text-decoration: none;', 'href'=>'#', 'onclick' => $onclick), fatt_24_update_icon());
            } // pulsante (con stile personalizzato) per creare il documento
            return $pdfLinksInvoice['label'];
        };

        $pdf = function ($pdfLinksInvoice, $order_id) use ($invType, $pdfLink) {
            if ($pdfLinksInvoice['enabled']) {
                return fatt_24_a(array('style' =>'text-decoration: none;', 'href'=>fatt_24_order_PDF_url($pdfLink), 'target'=>'_blank'), fatt_24_pdf_icon());
            }
            return $pdfLinksInvoice['label'];
        };
        $h = array(
            $pdf($pdfLinksInvoice[0], $order_id),
            $cmd($pdfLinksInvoice[1], $order_id)
        );

        $html = implode('', $h);
        return $html;
    }
}

function fatt_24_download_actions($docType, $order_id, $context = null) {
    $id = $docType . '-' . $order_id;
    $fontSize = '30px;';
    if ($context) {
        $fontSize = '24px;';
    }
    return fatt_24_a(array('href'=>'#'), fatt_24_download_icon($fontSize, $id));
}


add_action('wp_ajax_download_pdf', function () {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'download_pdf_file')) {
        wp_die("page killed");
    }

    $ordPdfExists = false;
    $invPdfExists = false;
    /**
     * Nella componente l'id è valorizzata con una stringa contenente il tipo di documento
     * e l'id dell'ordine WooCommerce. Esempi: C-844 => tipo documento => Ordine, id 844;
     * FE-866 => tipo documento FE, id 866. Sono i dati che invio in POST tramite ajax,
     * qui li uso per scaricarmi di nuovo il pdf di quello specifico ordine o fattura
     */
    
    $data = preg_split('/-/', $_POST['id']);
    $docType = $data[0];
    $order_id = $data[1];
    $order = wc_get_order($order_id);
    $invType = fatt_24_get_invoice_doctype();
    
    if ($docType == $invType) {
        $invPdfExists = fatt_24_download_PDF_invoice($order);
    } else {
        // se entro qui può essere solo un ordine
        $ordPdfExists = fatt_24_download_PDF_order($order);
    }

    if ($ordPdfExists || $invPdfExists) {
        wp_send_json(array(1, json_encode(esc_html__('Pdf file downloaded successfully', 'fattura24'))));
    } else {
        wp_send_json(array(0, json_encode(esc_html__('Pdf file download failed', 'fattura24'))));
    } 
});

// aggiungo i comandi ajax per gestire la creazione, visualizzazione e aggiornamento dei PDF
add_action('wp_ajax_invoice_admin_command', function () {
    $args = $_POST['args'];
    $order_id = $args['id'];
    $order = wc_get_order($order_id);
    /**
     * con $doc_ajax_param passo alla chiamata ajax il valore scelto
     * dall'utente nelle impostazioni - sezione "Crea Fattura"
     * Davide Iandoli 28.06.2019
     */
    $doc_ajax_param = $args['type'];
    check_ajax_referer(FATT_24_ORDER_ACTIONS_NONCE, 'security');
    $s = null;

    if ($args['cmd'] == 'upload') { // cmd == 'upload'
        if (fatt_24_store_fattura24_doc($order, $doc_ajax_param, fatt_24_now())) { // passo il parametro della chiamata ajax
            if ($doc_ajax_param == 'C' && get_option('fatt-24-ord-enable-pdf-download') == '1') {
                fatt_24_download_PDF_order($order);
            } else if (get_option('fatt-24-inv-enable-pdf-download') == '1') {
                fatt_24_download_PDF_invoice($order);
            }
            wp_send_json(array(1, fatt_24_pdf_upload($order_id, $doc_ajax_param, true))); // passo il parametro della chiamata ajax
        }
    } else {  // cmd == 'update'

        if ($doc_ajax_param == 'C') {
            if (get_option('fatt-24-ord-enable-pdf-download') == '1') {
                fatt_24_download_PDF_order($order);
            }
            wp_send_json(array(1, fatt_24_pdf_actions_order($order_id, $doc_ajax_param, true)));
        } else {
            if (get_option('fatt-24-inv-enable-pdf-download') == '1') {
                fatt_24_download_PDF_invoice($order);
            }
            wp_send_json(array(1, fatt_24_pdf_actions_invoice($order_id, $doc_ajax_param, true)));
        }
        
    }

    if (!$s) {
        $s = fatt_24_get_order_status($order);
    }
    $err = 'function currently not available';

    if ($s) {
        if (isset($s['lastErr'])) {
            $err = $s['lastErr'];
        } elseif (isset($s['description']) && isset($s['returnCode'])) {
            $err = $s['description'];
        }
    }
    wp_send_json(array(0, $err));
});

// creo e gestisco le azioni admin in base allo status dell'ordine

// restituisco l'indirizzo url del pdf dell'ordine
function fatt_24_order_PDF_url($link)
{
    return fatt_24_get_url_from_file($link);
}

// con questo metodo 'pesco' da un array di risultati il valore che cerco
function fatt_24_peek($v, $k, $default = null)
{
    return isset($v[$k]) ? $v[$k] : $default;
}

function fatt_24_admin_actions($pdfExists)
{
    $pdfLinks = [];
    if ($pdfExists) {
        $pdfLinks = array(
            array('label' => fatt_24_pdf_icon(), 'cmd' => 'view', 'enabled' => true ),
            array('label' => fatt_24_update_icon(), 'cmd' => 'update', 'enabled' => true)
        );
    }

    return $pdfLinks;
}

/**
 * Nuova funzione per aggiungere i pulsanti 'crea documento'
 * in modo selettivo nelle due colonne di controllo dello status
 * creata nuova funzione fatt_24_button in cui il terzo parametro
 * definisce lo stile del pulsante. Se non viene definito il parametro è null
 */
function fatt_24_pdf_upload($order_id, $doc_ajax_param, $label = '', $only_inner = false)
{
    $wp_nonce = wp_create_nonce(FATT_24_ORDER_ACTIONS_NONCE);
    $column_r = '';

    $cmd = function ($cmd, $id) use ($doc_ajax_param, $wp_nonce, $label) {
        $idParam = $doc_ajax_param == 'C' ? 'C-'.$id : 'I-'.$id;
        $label = $doc_ajax_param == 'C' ? __('Create order', 'fattura24') : __('Create invoice', 'fattura24');
        $column_r = array('label' => $label, 'cmd' => 'upload', 'enabled' => true);
        $cmd = $column_r['cmd'];
        $onclick = "f24_pdfcmd('$id','$cmd','$doc_ajax_param','$wp_nonce')"; // passo il parametro della chiamata ajax
        if ($column_r['enabled']) {
            return fatt_24_button($idParam, $onclick, $column_r['label'], 'button action fatt24');
        } // pulsante (con stile personalizzato) per creare il documento
        return $column_r['label'];
    };

    $h = array($cmd($column_r, $order_id));

    if ($only_inner) {
        $html = implode('<br>', $h);
    } else {
        $html = fatt_24_div(fatt_24_id('cmds-'.$order_id), implode('<br>', $h));
    }

    return $html;
}

/*
* raggruppo le azioni in base alle opzioni scelte. Qui sotto processo la creazione della fattura
* necessario ad evitare duplicati quando metto l'ordine in stato completato più volte
*/

function fatt_24_process_fattura($order)
{
    /**
    * con $invType passo alle funzioni fatt_24_is_recorded_on_f24_postmeta
    * e fatt_24_store_fattura24_doc il tipo di documento
    * selezionato dall'utente nelle impostazioni
    * Davide Iandoli 28.06.2019
    */
    $disabledInvoice = get_option("fatt-24-inv-create") == '0';
    $invType = fatt_24_get_invoice_doctype();
    $order_id = $order->get_id();

    if (!$disabledInvoice) { // se la creazione della fattura è disabilitata qui sotto non ci passo
        if (!fatt_24_is_recorded_on_f24_postmeta($order, $invType)) {
            // creo un documento solo se non ne è stato già creato uno dello stesso tipo dallo stesso ordine
            fatt_24_store_fattura24_doc($order, $invType, fatt_24_now());
            if ((get_option('fatt-24-inv-enable-pdf-download') == '1')) {
                fatt_24_download_PDF_invoice($order);
            }
        }
    }
}

// processo l'ordine
function fatt_24_process_order($order)
{
    $order_id = $order->get_id();
    $disabledOrder = (int) get_option(FATT_24_ORD_CREATE) == 0;
    if ($disabledOrder) {
        return;
    } elseif (!fatt_24_is_recorded_on_f24_postmeta($order, FATT_24_DT_ORDINE)) {
        fatt_24_store_fattura24_doc($order, FATT_24_DT_ORDINE, fatt_24_now());
        if (get_option('fatt-24-ord-enable-pdf-download') == '1') {
            fatt_24_download_PDF_order($order);
        }
    }
}

// processo il cliente
function fatt_24_process_customer($order_id)
{
    if (fatt_24_get_flag(FATT_24_ABK_SAVE_CUST_DATA)) {
        fatt_24_SaveCustomer($order_id);
    }
}

/*se il doc è creato in F24, ne restituisco il docId
 se il doc è una fattura ne restituisco anche il docNumber
 nota: ora restituisco $resultArray invece di $docId
 codice modificato da Davide Iandoli 25.03.2020
 utilizzata in più parti del codice, utilizzato anche l'array di risultati
*/
 function fatt_24_is_recorded_on_f24_postmeta($order, $docType)
 {
     $s = fatt_24_get_order_status($order);

     while ($s) {
         if (fatt_24_peek($s, 'docType') == $docType) {
            if ($docId = fatt_24_peek($s, 'docId')) { // esiste una chiave docId ?
                 $resultArray['docId'] = $docId;
                 //if ($docType != FATT_24_DT_ORDINE) {
                     if ($docNumber = fatt_24_peek($s, 'docNumber')) {
                         $resultArray['docNumber'] = $docNumber;
                     }
                 //}
                 return $resultArray;
             }
         }
         $s = fatt_24_peek($s, 'history');
     }
     return null;
 }

// se il PDF è disponibile ne restituisco il percorso e il $docType
function fatt_24_is_PDF_available($order_id, $docType)
{
    $order = wc_get_order($order_id);
    $s = fatt_24_get_order_status($order);

    while ($s) {
        if (fatt_24_peek($s, 'docType') == $docType && fatt_24_peek($s, 'docId')) {
            if ($pdfPath = fatt_24_peek($s, 'pdfPath')) {
                if (is_file($pdfPath)) {
                    return $pdfPath;
                }
            }
        }
        $s = fatt_24_peek($s, 'history');
    }
    return null;
}

/**
 *  Metodo con cui verifico se ci sono errori nella creazione del doc
 *  lo uso in met_hooks_general.php e li mostro nel tooltip
 * (passando il mouse nella scritta 'Notice')
 */
function fatt_24_get_error_message($order)
{
    $order_status = (array) $order->get_meta('fatt-24-order-invoice-status', true);
    $message = '';
    foreach ($order_status as $status) {
        if (isset($status['lastErr'])) {
            // mi interessa solo il messaggio di errore, quello più recente
            $message = substr($status['lastErr'], strpos($status['lastErr'], ':', 19) + 1);
            return __($message, 'fattura24');
        } else {
            break;
        }
    }
    return $message;
}
