<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * gestisce i metodi e il comportamento del plugin
 *
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

$filesToInclude = [
    'uty.php',
    'constants.php',
    'methods/met_settings_pages.php'
];

foreach ($filesToInclude as $file) {
    require_once FATT_24_CODE_ROOT . $file;
}


if (!is_admin()) {
    require_once ABSPATH.'wp-admin/includes/file.php';
}

// filtro che restituisce  il file del documento in formato PDF
add_filter(FATT_24_DOC_PDF_FILENAME, function ($args) {
    /**
    * $doc_ajax_param è la variabile con cui passo al comando ajax f24_pdfcmd.js
    * il tipo di documento selezionato dall'utente, anche se fosse FE - Davide Iandoli 28.06.2019
    */
    $doc_ajax_param = $args['doc_ajax_param'];
    $order_id = $args['order_id'];
    $timestamp = fatt_24_now('YmdHi');
    return sprintf('doc-%s-%s-%s.pdf', $timestamp, $doc_ajax_param, $order_id);
});

// prendo la p. iva dai metadati dell'ordine, tramite il campo personalizzato
add_filter(FATT_24_ORDER_GET_VAT, function ($order) {
    $vat_postmeta = fatt_24_get_vat_postmeta();
    return $order->get_meta($vat_postmeta, true);
});

// la restituisco per assegnarla a una variabile e codificarla in xml
function fatt_24_order_p_iva($order)
{
    return apply_filters(FATT_24_ORDER_GET_VAT, $order);
}

// da qui in poi il codice segue la stessa logica delle righe 32 e seguenti per tutti gli altri campi fiscali
add_filter(FATT_24_ORDER_GET_CF, function ($order) {
    return $order->get_meta('_billing_fiscalcode', true);
});


function fatt_24_get_vat_postmeta() {
    $result = '_billing_vatcode'; // valore di default
    $activeAddons = array_column(fatt_24_get_plugin_info(), 'name');
    // fix del 9.05.2023, ticket DT n.: 9384 - il nome del plugin non era corretto
    $suitableAddons = ['EU/UK VAT for WooCommerce', 'WooCommerce EU VAT Number' ]; // array di plugin compatibili
    foreach ($suitableAddons as $addon) {
        if (in_array($addon, $activeAddons)) {
            switch ($addon) {
                case $suitableAddons[0] :
                    $result = '_billing_eu_vat_number';
                    break;
                case $suitableAddons[1] :
                    $result = '_billing_vat_number';
                    break;
                default:
                    break;    
            }
        }
    }    
    return $result;
}


function fatt_24_order_c_fis($order)
{
    return apply_filters(FATT_24_ORDER_GET_CF, $order);
}

function fatt_24_customer_use_vat()
{
    return apply_filters(FATT_24_CUSTOMER_USE_VAT, null);
}

add_filter(FATT_24_CUSTOMER_USE_VAT, function () {
    return true;
});

function fatt_24_customer_use_cf()
{
    return apply_filters(FATT_24_CUSTOMER_USE_CF, null);
}

add_filter(FATT_24_CUSTOMER_USE_CF, function () {
    return true;
});

add_filter(FATT_24_ORDER_GET_PEC_ADDRESS, function ($order) {
    return $order->get_meta('_billing_pecaddress', true);
});

function fatt_24_order_pec_address($order)
{
    return apply_filters(FATT_24_ORDER_GET_PEC_ADDRESS, $order);
}

add_filter(FATT_24_ORDER_GET_RECIPIENTCODE, function ($order) {
    return $order->get_meta('_billing_recipientcode', true);
});

function fatt_24_order_recipientcode($order)
{
    return apply_filters(FATT_24_ORDER_GET_RECIPIENTCODE, $order);
}

function fatt_24_customer_use_recipientcode()
{
    return apply_filters(FATT_24_CUSTOMER_USE_RECIPIENTCODE, null);
}

add_filter(FATT_24_CUSTOMER_USE_RECIPIENTCODE, function () {
    return true;
});

function fatt_24_order_pecaddress($order)
{
    return apply_filters(FATT_24_ORDER_GET_PEC_ADDRESS, $order);
}

function fatt_24_customer_use_pecaddress()
{
    return apply_filters(FATT_24_CUSTOMER_USE_PEC_ADDRESS, null);
}

add_filter(FATT_24_CUSTOMER_USE_PEC_ADDRESS, function () {
    return true;
});

/**
 * Edit Davide iandoli 02.02.2022
 * cfr ticket n.: 68340
 */
function fatt_24_get_url_from_file($file)
{
    $fullPath = strpos($file, '/wp-content');
    $relPath = substr($file, $fullPath);
    return site_url(). $relPath;
}

/**
* $doc_ajax_param è la variabile con cui passo al comando ajax f24_pdfcmd.js
* il tipo di documento selezionato dall'utente - Davide Iandoli 28.06.2019
*/
// con questo metodo ottengo l'indirizzo dal nome del file PDF
function fatt_24_PDF_filename($doc_ajax_param, $order_id)
{
    return apply_filters(FATT_24_DOC_PDF_FILENAME, array('doc_ajax_param' => $doc_ajax_param, 'order_id' => $order_id));
    //return apply_filters(FATT_24_DOC_PDF_FILENAME, compact('doc_ajax_param', 'order_id'));
}

// imposto i permessi del file
function fatt_24_set_file_permissions($new_file)
{
    $stat = @stat(dirname($new_file));
    $perms = $stat['mode'] & 0007777;
    $perms = $perms & 0000666;
    @chmod($new_file, $perms);
}

// Note a piè pagina della fattura
function fatt_24_doc_footnotes($order)
{
    $FootNotes = sprintf(__('order num. %d', 'fattura24'), $order->get_id());
    if ($order->get_customer_note()) {
        return $FootNotes;
    }
}

add_filter(FATT_24_DOC_FOOTNOTES, function ($order) {
    return fatt_24_doc_footnotes($order);
});

// indirizzi
add_filter(FATT_24_DOC_ADDRESS, function ($order) {
    return fatt_24_make_strings(
        array($order->get_billing_address_1(), $order->get_billing_address_2()),
        array($order->get_shipping_address_1(), $order->get_shipping_address_2())
    );
});

// widget schermata impostazioni
add_filter(FATT_24_LAYOUT_OPTION, function ($args) {
    extract($args);
    $rc = $widget;
    if ($help) {
        $rc .= fatt_24_helpico($help);
    }
    if ($desc) {
        $rc .= $desc;
    }
    return $rc;
});

/**
 * Con questo metodo ottengo le impostazioni
 * della tabella fattura_tax
 */
function fatt_24_get_tax_configuration()
{
    global $wpdb;
    $prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
    $table_name = $prefix . 'fattura_tax';
    $query = $wpdb->get_results("SELECT * from ". $table_name);
    $jsonResults = json_encode($query);
    return $jsonResults;
}

/**
 *  Con questo metodo aggiorno i record
 */
function fatt_24_update_tax_configuration()
{
    global $wpdb;
    $prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
    $blog_ids = 1;

    if (is_multisite() && is_plugin_active_for_network('fattura24/fattura24.php')) {
        $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    }

    $table_name = $prefix . 'fattura_tax';
    $oldTaxSettings = json_decode(fatt_24_get_tax_configuration(), true);

    foreach ($oldTaxSettings as $key => $val) {
        if (is_null($val['blog_id'])) {
            $wpdb->update(
                $table_name,
                array('blog_id' => is_array($blog_ids) ? $blog_ids[$key] : $blog_ids),
                array('id' => $val['id'])
            );
        }
    }
}

/**
 * Con questo metodo leggo i dati dalla nuova tabella
 * cercando solo l'evento che mi interessa
 * @param $event_type
 * Davide Iandoli 02.09.2020
 * se non trovo l'evento la query è null
 */
function fatt_24_get_installation_log($event_type)
{
    global $wpdb;
    $prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
    $table_name = $prefix . 'f24_installation_log';
    $query = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where event_type=%s", $event_type));
    if (count($query) > 0) {
        $result = array_shift($query);
        if (is_object($result)) {
            $resultArray = get_object_vars($result);
            return $resultArray['event_type'];
        }
    }
}

/**
 * Con questo metodo inserisco i dati nella nuova tabella
 * Davide Iandoli 21.08.2020
 * @param $event
 */
function fatt_24_insert_installation_log($event)
{
    global $wpdb;
    $prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
    $plugin_version = FATT_24_PLUGIN_DATA['Version'];
    $table_name = $prefix . 'f24_installation_log';
    $wpdb->insert(
        $table_name,
        array(
            'id' => '',
            'vers' => $plugin_version,
            'event_type' => $event,
            'event_date' => fatt_24_now()
        )
    );
}
/**
 * Con questo metodo mi ripropongo di recuperare
 * le vecchie opzioni del plugin e usarle in caso di aggiornamento
 * il parametro può essere un'opzione singola oppure un array
 * Davide Iandoli 24.11.2020
 */
function fatt_24_get_old_options($option)
{
    if (is_array($option)) {
        foreach ($option as $field) {
            return array('field ' => $field,
                         'value ' => get_option($field));
        }
    } else {
        return get_option($option);
    }
}

/**
 * Con questo metodo filtro tutti i post meta degli ordini precedenti
 * e costruisco un array di risultati che uso per aggiornare solo i vecchi ordini
 * nell'array inserisco anche l'id dell'ordine
 * funzione attualmente inutilizzata: sarà agganciata all'hook di attivazione del plugin
 * e chiamata solo dopo aver fatto il controllo della versione
 */
function fatt_24_update_old_postmeta()
{
    global $wpdb;

    foreach ($wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key=%s", 'fatt-24-order-invoice-status')) as $val) {
        $order_id = $val->post_id;
        $order = wc_get_order($order_id);
        $result = fatt_24_get_order_status($order);
        foreach ($result as $v) {
            // se è presente 'history' devo aggiornare due post_meta
            if (isset($result['history'])) {
                $docType1 = fatt_24_peek($result['history'], 'docType');
                $docType2 = fatt_24_peek($result, 'docType');
                $docId1 = fatt_24_peek($result['history'], 'docId');
                $docId2 = fatt_24_peek($result, 'docId');
                $wpPostMeta1 = $docType1 == 'C' ? 'fatt24_order_docId' : 'fatt24_invoice_docId';
                $wpPostMeta2 = $docType2 == 'C' ? 'fatt24_order_docId' : 'fatt24_invoice_docId';
                if (!metadata_exists('post', $order_id, $wpPostMeta1) || !metadata_exists('post', $order_id, $wpPostMeta2)) {
                    update_post_meta($order_id, $wpPostMeta1, $docId1);
                    update_post_meta($order_id, $wpPostMeta2, $docId2);
                }
            } elseif (!isset($result['history'])) {
                $docType = fatt_24_peek($result, 'docType');
                $docId = fatt_24_peek($result, 'docId');
                $wpPostMeta = $docType == 'C' ? 'fatt24_order_docId' : 'fatt24_invoice_docId';
                if (!metadata_exists('post', $order_id, $wpPostMeta)) {
                    update_post_meta($order_id, $wpPostMeta, $docId);
                }
            }
        }
    }
}

/** 
 * Mi prendo tutti gli ordini che hanno il postmeta fatt-24-order-invoice-status 
 * in questo modo aggiorno anche gli ordini vecchi
 * */
function fatt_24_update_old_billing_cb_postmeta()
{
    global $wpdb;

    foreach ($wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_key=%s", 'fatt-24-order-invoice-status')) as $val) {
        $order_id = $val->post_id;
        $order = wc_get_order($order_id);
        fatt_24_update_billing_cb_postmeta($order);
    }
}


function fatt_24_update_billing_cb_postmeta($order) {
    $cb_value = $order->get_meta('_billing_checkbox');
    if (!$cb_value) {
        $cb_value = '0';
    }
    $order->add_meta_data('_billing_checkbox', $cb_value);
}