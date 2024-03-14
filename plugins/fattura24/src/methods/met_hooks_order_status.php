<?php

/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * metodi utilizzati da hooks.php
 * per l'aggiunta la gestione e la visualizzazione
 * di colonne aggiuntive nella lista degli ordini lato admin
 *
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

/**
* Aggiungo due nuove colonne per controllare da admin (nel dettaglio ordine)
* se le chiamate per gli ordini e per le fatture hanno avuto esito positivo
*/
function fatt_24_add_admin_columns($columns)
{
    $new_columns = is_array($columns) ? $columns : array();
    $fatt_24_order_enable = fatt_24_get_flag(FATT_24_ORD_CREATE); // valore checkbox crea ordine
    $invType = fatt_24_get_invoice_doctype();
    $showCheckBox = fatt_24_show_billing_checkbox();
    $colView = $invType !== '0';
    $new_columns['fatt24-customer-billing-country'] = __('Customer billing country', 'fattura24');
    
    if ($showCheckBox) {
        $new_columns['fatt24-customer-required-invoice'] = __('Invoice required', 'fattura24');
    }
    // visualizzo le colonne Fattura24 solo se la creazione delle fatture è abilitata
    if ($fatt_24_order_enable == 1) {
        $new_columns['fatt24-order-status'] = __('F24 Order', 'fattura24');
    } // colonna stato ordini, invisibile se la casella non è contrassegnata da spunta
    if ($colView) {
        $new_columns['fatt24-invoice-status'] = __('F24 Invoice', 'fattura24');
    } // colonna stato fattura

    return $new_columns;
}

/**
* Con questo blocco di codice riporto il docNumber anche se cambio l'opzione selezionata nel menu
* 'Crea documento fiscale' - Davide Iandoli 11.05.2020
* Aggiunti controlli per visualizzare la colonna se è abilitata la creazione di doc
* con totale ordine uguale a zero - Davide Iandoli 18.05.2021
*/
function fatt_24_add_admin_data_after_order_details($order)
{
    $context = 'order_detail';
    $order_id = $order->get_id();
    $invType = fatt_24_get_invoice_doctype();
    $orderDisabled = get_option('fatt-24-ord-enable-create') == '0';
    $invoiceDisabled = $invType == '0';
    $isZeroOrderEnabled = get_option('fatt-24-ord-zero-tot-enable') == '1';
    $isZeroInvoiceEnabled = get_option('fatt-24-inv-zero-tot-enable') == '1';
    $isOrderTotalZero = (float) $order->get_total() == 0.00;
    $case1 = !$orderDisabled && !$isOrderTotalZero || !$orderDisabled && $isZeroOrderEnabled;
    $case2 = !$invoiceDisabled && !$isOrderTotalZero || !$invoiceDisabled && $isZeroInvoiceEnabled;
    $colView = $case1 || $case2;
    $order_status = fatt_24_get_order_status($order);
    /** 
     * Se non trovo il dato del campo fatt-24-order-invoice-status nel nuovo db interrogo quello vecchio e aggiorno i dati in wc_orders_meta
     * blocco inserito nel dettaglio dell'ordine perché in questo modo faccio una sola query per volta
     * inserirlo nella lista degli ordini avrebbe potenzialmente creato un blocco (aggiornamento di x record nel db)
     * Davide Iandoli 22.01.2024
     */ 

    if (empty($order_status)) {
        $old_order_status = fatt_24_get_old_order_status($order);
        fatt_24_store_order_status($order, $old_order_status);
        $order_status = fatt_24_get_order_status($order);
    }
    $orderResult = fatt_24_is_recorded_on_f24_postmeta($order, FATT_24_DT_ORDINE);
    $OrderDocId = $order->get_meta('fatt24_order_docId', true);
    if (empty($OrderDocId)) {
        $OrderDocId = !isset($orderResult['docId']) ? '' : $orderResult['docId'];
    }
    $ordDocNumber = isset($orderResult['docNumber']) ? $orderResult['docNumber'] : '';    
    $invResult = fatt_24_is_recorded_on_f24_postmeta($order, $invType);
    $invDocId = $order->get_meta('fatt24_invoice_docId', true);
    $err_message = fatt_24_get_error_message($order); 


    if (empty($invDocId)) {
        $invDocId = !isset($invResult['docId']) ? '' : $invResult['docId'];
    }
    $invDocNumber = isset($invResult['docNumber']) ? $invResult['docNumber'] : '' ;
    $orderLink = isset(fatt_24_get_pdf_link($order_status, 'C')['pdfPath']) 
                  && file_exists(fatt_24_get_pdf_link($order_status, 'C')['pdfPath']) ? fatt_24_get_pdf_link($order_status, 'C')['pdfPath'] : '';
    $invLink = isset(fatt_24_get_pdf_link($order_status, $invType)['pdfPath']) 
                  && file_exists(fatt_24_get_pdf_link($order_status, $invType)['pdfPath'])? fatt_24_get_pdf_link($order_status, $invType)['pdfPath'] : '' ;

    $boxElement = [];
    /**
     * Gli elementi con numero finale 1 sono relativi agli ordini
     * quelli con numero 2 a fatture / ricevute.
     * In questo modo vengono visualizzati nell'ordine che desidero
     */
    $elementsToRender = array(
        'buttonDoc1' => false,
        'buttonDoc2' => false,
        'iconsDoc1' => false,
        'iconsDoc2' => false,
        'enablePdf1' => false,
        'enablePdf2' => false,
        'docNumber2' => false
    );

    if (!$OrderDocId && $case1) {
        $elementsToRender['buttonDoc1'] = true;
    } else if ($OrderDocId && empty($orderLink) && fatt_24_get_flag(FATT_24_ORD_ENABLE_PDF_DOWNLOAD) && !$err_message) {
        $elementsToRender['docNumber1'] = !empty($ordDocNumber) ? true : false;
        $elementsToRender['enablePdf1'] = true;
    } else if ($OrderDocId && empty($orderLink) && fatt_24_get_flag(FATT_24_ORD_ENABLE_PDF_DOWNLOAD) && $err_message) {  
        $elementsToRender['docNumber1'] = !empty($ordDocNumber) ? true : false;  
    } else if ($OrderDocId && !empty($orderLink) && fatt_24_get_flag(FATT_24_ORD_ENABLE_PDF_DOWNLOAD)) {
        $elementsToRender['iconsDoc1'] = true;
    } else if ($OrderDocId && !empty($ordDocNumber)) {
        $elementsToRender['docNumber1'] = true;
    }

    if (!$invDocId && $case2) {
        $elementsToRender['buttonDoc2'] = true;
    } else if ($invDocId && empty($invLink) && fatt_24_get_flag(FATT_24_INV_ENABLE_PDF_DOWNLOAD) && !$err_message) {
        $elementsToRender['docNumber2'] = !empty($invDocNumber) ? true : false;
        $elementsToRender['enablePdf2'] = true;
    } else if ($invDocId && !empty($invLink) && fatt_24_get_flag(FATT_24_INV_ENABLE_PDF_DOWNLOAD) && $err_message)  {
        $elementsToRender['docNumber2'] = !empty($invDocNumber) ? true : false;
        $elementsToRender['iconsDoc2'] = true;
    } else if ($invDocId && !empty($invDocNumber)) {
        $elementsToRender['docNumber2'] = true;
    }

    if ($colView) {
        $boxElement[] = fatt_24_div(fatt_24_h3(fatt_24_style(array('padding-bottom' => '10px')),__('Status in Fattura24', 'fattura24')));
    }

    if ($elementsToRender['buttonDoc1']) {
        $boxElement[] = fatt_24_div(fatt_24_pdf_upload($order_id, 'C', __('Create order', 'fattura24'))) . fatt_24_p('');
    }

    if ($elementsToRender['iconsDoc1']) {
        $boxElement[] = fatt_24_div(fatt_24_pdf_actions_order($order_id)). fatt_24_p(__('Pdf order', 'fattura24'));
    }

    if ($elementsToRender['enablePdf1']) {
        $boxElement[] = fatt_24_div(fatt_24_download_actions('C', $order_id, $context) . fatt_24_p(__('Download order PDF file', 'fattura24')));
    }

    if ($elementsToRender['buttonDoc2']) {
        $boxElement[] = fatt_24_div(fatt_24_pdf_upload($order_id, $invType, __('Create invoice', 'fattura24')));
    }

    if ($elementsToRender['iconsDoc2']) {
        $boxElement[] = fatt_24_div(fatt_24_pdf_actions_invoice($order_id)) . fatt_24_p(__('Pdf Invoice', 'fattura24'));
    }

    if ($elementsToRender['enablePdf2']) {
        $boxElement[] = fatt_24_div(fatt_24_download_actions($invType, $order_id, $context) . fatt_24_p(__('Download invoice PDF file', 'fattura24')));
    }

    if ($elementsToRender['docNumber2']) {
        $boxElement[] = fatt_24_div(__('n .', 'fattura24') . ' ' . $invDocNumber); 
    }
   
    // fine codice aggiunto in questo metodo il 25.03.2020
    if ($colView) { // disable Fattura24 custom fields if invoice creation is disabled
        echo fatt_24_div(fatt_24_klass('form-field form-field-wide'), $boxElement);
    } // codice modificato il 25.03.2020
}

function fatt_24_manage_shop_post_columns($column, $post_id)
{
    $order = new \WC_Order($post_id); // dati dell'ordine WooCommerce
    $order_id = $order->get_id();
    $woo_order_status = $order->get_status(); // stato dell'ordine in Woocommerce
    $invType = fatt_24_get_invoice_doctype();
    $order_status = fatt_24_get_order_status($order); // aggiungo controllo sullo stato dell'ordine
  
    $err_message = fatt_24_get_error_message($order);  

    if ($column == 'fatt24-customer-billing-country') {
        echo fatt_24_div(array('style' => 'text-align: center;'), $order->get_billing_country());
    }
    
    if ($column == 'fatt24-customer-required-invoice') {
        $icon = '1' == $order->get_meta('_billing_checkbox', true) ? fatt_24_ok_icon() : '';
        echo $icon;
    }
    // colonna stato ordine in F24
    /**
    * Con $OrderResult controllo lo stato dell'ordine prendendomi il docId
    * La funzione fatt_24_is_recorded_on_f24_postmeta usa i metodi fatt_24_get_order_status
    * e get_meta per recuperare le info, perciò non fa chiamate API
    * se il totale dell'ordine è 0 nessun documento è stato creato, perciò non devo vedere il pulsante
    * controllo se in f24 l'ordine è stato già creato e se esiste $order_status['description']
    */

    if ($column == 'fatt24-order-status') {
        $isZeroOrderEnabled = get_option('fatt-24-ord-zero-tot-enable') == 1;
        $isOrderTotalZero = (float) $order->get_total() == 0.00;
        $orderResult = fatt_24_is_recorded_on_f24_postmeta($order, FATT_24_DT_ORDINE);
        $OrderDocId = $order->get_meta('fatt24_order_docId', true);
        $ordDocNumber = is_array($orderResult) && isset($orderResult['docNumber']) ? $orderResult['docNumber'] : '';
        
        if (empty($OrderDocId)) {
            $OrderDocId = !isset($orderResult['docId']) ? '' : $orderResult['docId'];
        }
       
        $orderLink = isset(fatt_24_get_pdf_link($order_status, 'C')['pdfPath'])
                     && file_exists(fatt_24_get_pdf_link($order_status, 'C')['pdfPath']);

        
        $renderButton = false;
        $renderIcons = false;
        $reEnablePDF = false;
        $renderDocNumber = false;

        if (!$OrderDocId) {
            $renderButton = true;
        } else if ($OrderDocId && !$orderLink && fatt_24_get_flag(FATT_24_ORD_ENABLE_PDF_DOWNLOAD) && !$err_message) {
            $renderDocNumber = !empty($ordDocNumber) ? true : false;
            $reEnablePDF = true;
        } else if ($OrderDocId && !$orderLink && fatt_24_get_flag(FATT_24_ORD_ENABLE_PDF_DOWNLOAD) && $err_message){  
            $renderDocNumber = !empty($ordDocNumber) ? true : false;
        } else if ($OrderDocId && $orderLink && fatt_24_get_flag(FATT_24_ORD_ENABLE_PDF_DOWNLOAD)) {
            $renderDocNumber = !empty($ordDocNumber) ? true : false;
            $renderIcons = true;
        } else if ($OrderDocId && !empty($ordDocNumber)) {
            $renderDocNumber = true;
        }

        echo '<div style="display: flex; flex-direction: row; align-items: center;">';

        if ($renderButton) {
            echo fatt_24_render_button_message($order_id, 'C');
        }

        if ($renderIcons) {
            echo fatt_24_pdf_actions_order($order_id);
        }

        if ($reEnablePDF) {
            echo fatt_24_download_actions('C', $order_id);
        }
        echo '</div >';

        if ($renderDocNumber) {
            echo '<p style="margin-top: 5px; padding: 5px;">' . 'n. ' . $ordDocNumber . '</p>';
        }
       
    }
    // colonna stato fattura in F24
    /**
    * Con questo blocco di codice riporto il docNumber anche se cambio l'opzione selezionata nel menu
    * 'Crea documento fiscale' - Davide Iandoli 11.05.2020
    */
    if ($column == 'fatt24-invoice-status') {
       
        $invType = fatt_24_get_invoice_doctype();
        $isZeroInvoiceEnabled = (int) get_option('fatt-24-inv-zero-tot-enable') == 1;
        $isOrderTotalZero = (float) $order->get_total() == 0.00;
        $invResult = fatt_24_is_recorded_on_f24_postmeta($order, $invType);
        $invDocId = $order->get_meta('fatt24_invoice_docId', true);
        

        if (empty($invDocId)) {
            $invDocId = !isset($invResult['docId']) ? '' : $invResult['docId'];
        }
        $invDocNumber = is_array($invResult) && isset($invResult['docNumber']) ? $invResult['docNumber'] : ''; // riporto anche il docNumber
        $invLink = isset(fatt_24_get_pdf_link($order_status, $invType)['pdfPath'])
                   && file_exists(fatt_24_get_pdf_link($order_status, $invType)['pdfPath']);
        
        $renderButton = false;
        $renderIcons = false;
        $reEnablePDF = false;
        $renderDocNumber = false;

        echo '<div style="display: flex; flex-direction: row; justify-content: center;">';

        if (!$isZeroInvoiceEnabled && $isOrderTotalZero) {
            //non faccio nulla
        }    
        
        if (!$invDocId) {
            $renderButton = true;
        } else if ($invDocId && !$invLink && fatt_24_get_flag(FATT_24_INV_ENABLE_PDF_DOWNLOAD) && !$err_message) {
            $renderDocNumber = !empty($invDocNumber) ? true : false;
            $reEnablePDF = true;
        } else if ($invDocId && !$invLink && fatt_24_get_flag(FATT_24_INV_ENABLE_PDF_DOWNLOAD) && $err_message) {
            $renderDocNumber = !empty($invDocNumber) ? true : false;
        } else if ($invDocId && $invLink && fatt_24_get_flag(FATT_24_INV_ENABLE_PDF_DOWNLOAD)) {
            $renderDocNumber = !empty($invDocNumber) ? true : false;
            $renderIcons = true;
        } else if ($invDocId && !empty($invDocNumber)) {
            $renderDocNumber = true;
        }
        
        if ($renderButton) {
            echo fatt_24_render_button_message($order_id, $invType);
        }

        if ($renderIcons) {
            echo fatt_24_pdf_actions_invoice($order_id, $invType);
        }

        if ($reEnablePDF) {
            echo fatt_24_download_actions($invType, $order_id);
        }

        echo '</div>';

        if ($renderDocNumber) {
            echo '<p style="margin-top:10px; text-align: center;">' . __('n. ', 'fattura24') . $invDocNumber . '</p>';
        }
      
    } 
    
    ?>
    <?php
}

function fatt_24_render_button_message($order_id, $docType)
{
    $order = wc_get_order($order_id);
    $type = strpos(strtolower(fatt_24_get_error_message($order)), 'error') !== false ? 'error' : 'notice';
    $tooltip = !empty(fatt_24_get_error_message($order)) ? fatt_24_get_error_message($order) : __('Document not created in Fattura24', 'fattura24');
    $html = '<div style="display: flex; flex-direction: row;">';
    $html .= fatt_24_pdf_upload($order_id, $docType);
    $html .= fatt_24_order_status_message($type, $tooltip);
    $html .= '</div>';
    return $html;
}

/**
 *  Con questo metodo aggiungo un filtro all'elenco degli ordini
 *  l'idea è quella di trovare più rapidamente  gli ordini per cui non è stato
 *  creato automaticamente il documento nel gestionale F24
 *  cfr: https://stackoverflow.com/questions/55451123/woocommerce-filter-admin-order-list-based-on-added-custom-header-field
 */
function fatt_24_restrict_posts()
{
    $screen = get_current_screen();
    $screen_id = $screen ? $screen->id : '';
    $allowed_screen_ids = ['edit-shop_order', 'woocommerce_page_wc-orders'];

    // non visualizzo i filtri per ordini cestinati
    if (in_array($screen_id, $allowed_screen_ids)) {
        
        // ottengo l'elenco dei paesi da WooCommerce
        $wc_countries = new \WC_Countries();
        $countries = $wc_countries->get_countries();
        
        echo '<select name="fatt24_billing_countries">
        <option value="">'. __('Filter by billing country', 'fattura24') . '</option>';
        $current_v = isset($_GET['fatt24_billing_countries']) ? $_GET['fatt24_billing_countries'] : '';
        foreach ($countries as $code => $label) {
            printf(
                '<option value ="%s"%s>%s</option>',
                $code,
                $code == $current_v ? ' selected="selected"' : '',
                $label
            );
        }
        echo '</select>';

        $showCheckbox = fatt_24_show_billing_checkbox();
      
        if ($showCheckbox) {
            $customer_required_invoice = array(
                '1' => __('Yes', 'fattura24'),
                '0' => __('No', 'fattura24')
            );

            echo '<select name="fatt24_customer_required_invoice">
            <option value="-1">' . __('Invoice required', 'fattura24') . '</option>';
            $current_v = isset($_GET['fatt24_customer_required_invoice']) ? $_GET['fatt24_customer_required_invoice'] : '-1';
            foreach ($customer_required_invoice as $key => $val) {
                printf(
                    '<option value="%s"%s>%s</option>',
                    $key,
                    $key == $current_v ? ' selected="selected"' : '',
                    $val
                );
            }

            echo '</select>';
        }

        $values_order = array(
            __('Order NOT saved in F24', 'fattura24') => 'ONS',
            __('Order saved in F24', 'fattura24') => 'OS',

        );

        $values_invoice = array(
            __('Invoice NOT Saved in F24', 'fattura24') => 'INS',
            __('Invoice saved in F24', 'fattura24') => 'IS'
        );
        echo '<select name="fatt24_order_docId">
        <option value="">' . __('Filter by F24 order status', 'fattura24') . '</option>';
        $current_v = isset($_GET['fatt24_order_docId']) ? $_GET['fatt24_order_docId'] : '';
        foreach ($values_order as $label => $val) {
            printf(
                '<option value ="%s"%s>%s</option>',
                $val,
                $val == $current_v ? ' selected="selected"' : '',
                $label
            );
        }
        echo '</select>
        <select name="fatt24_invoice_docId">
        <option value="">'. __('Filter by F24 invoice status', 'fattura24') . '</option>';

        $current_v = isset($_GET['fatt24_invoice_docId']) ? $_GET['fatt24_invoice_docId'] : '';
        foreach ($values_invoice as $label => $val) {
            printf(
                '<option value ="%s"%s>%s</option>',
                $val,
                $val == $current_v ? ' selected="selected"' : '',
                $label
            );
        }
        echo '</select>';
    }
}

/*function fatt_24_custom_filters($query) {
    if (function_exists('get_current_screen')) {
        $screen = get_current_screen();
    }

    $screen_id = $screen ? $screen->id : '';
    $allowed_screen_ids = ['edit-shop_order', 'woocommerce_page_wc-orders'];

  
    $query_vars = FATT_24_HPOS_ENABLED ?  array() : (array) $query->get('meta_query');

    if (in_array($screen_id, $allowed_screen_ids)) {
       
        if (isset($_GET['fatt24_billing_countries']) && $_GET['fatt24_billing_countries'] != '') {
            if (FATT_24_HPOS_ENABLED) {
                $query['billing_country'] = esc_attr($_GET['fatt24_billing_countries']);
            } else {
                $query_vars[] = array(
                    'key' => '_billing_country',
                    'value' => esc_attr($_GET['fatt24_billing_countries']),
                    'compare' => '='
                );
            }
        }

        $showCheckbox = fatt_24_show_billing_checkbox();

        if ($showCheckbox) {
            if (isset($_GET['fatt24_customer_required_invoice']) && $_GET['fatt24_customer_required_invoice'] != '-1') {
                $query_vars[] = array(
                    'key' => '_billing_checkbox',
                    'value' => esc_attr($_GET['fatt24_customer_required_invoice']),
                    'compare' => '='
                );
            }
        } 

        // controllo prima entrambi i filtri, ed entrambi i post_meta
        if (isset($_GET['fatt24_order_docId']) && $_GET['fatt24_order_docId'] != '' && isset($_GET['fatt24_invoice_docId']) && $_GET['fatt24_invoice_docId'] != '') {
            $query_vars[] = array(
                'relation' => 'AND',
                    array(
                            'key' => 'fatt24_order_docId',
                            'value' => esc_attr($_GET['fatt24_order_docId']),
                            'compare' => esc_attr($_GET['fatt24_order_docId']) === 'ONS' ? 'NOT EXISTS' : 'EXISTS',
                    ),
                    array(
                            'key' => 'fatt24_invoice_docId',
                            'value' => esc_attr($_GET['fatt24_invoice_docId']),
                            'compare' => esc_attr($_GET['fatt24_invoice_docId']) === 'INS' ? 'NOT EXISTS' : 'EXISTS',
                    ),
                );
            // filtro ordini f24
        } elseif (isset($_GET['fatt24_order_docId']) && $_GET['fatt24_order_docId'] != '') {
            $query_vars[] =
                array(
                       'key' => 'fatt24_order_docId',
                       'value' => esc_attr($_GET['fatt24_order_docId']),
                       'compare' => esc_attr($_GET['fatt24_order_docId']) === 'ONS' ? 'NOT EXISTS' : 'EXISTS',
                );
        
                // filtro fatture f24
        } elseif (isset($_GET['fatt24_invoice_docId']) && $_GET['fatt24_invoice_docId'] != '') {
            $query_vars[] =
                array(
                       'key' => 'fatt24_invoice_docId',
                       'value' => esc_attr($_GET['fatt24_invoice_docId']),
                       'compare' => esc_attr($_GET['fatt24_invoice_docId']) === 'INS' ? 'NOT EXISTS' : 'EXISTS',
                );
        }

        if (FATT_24_HPOS_ENABLED) {
            $query_vars['meta_query'] = array_merge($query_vars['meta_query'] ?? array(), $query_vars);
            return $query_vars;
        } else {
            $query->set('meta_query', $query_vars);
        }
       
    }
}*/
/*
/**
 * Con questo metodo effettuo la query per i post_meta fatt24_order_docId e fatt24_invoice_docId
 * utilizzo wp_meta_query, sapendo che i post_meta non vengono creati in caso di esito negativo della chiamata API
 * Davide Iandoli 18.08.2020
 *
 * - edit del 02.03.2022 - cfr ticket n.: 71811
 */
function fatt_24_order_query($query)
{
    global $pagenow;
    
    if ($query->is_admin && $pagenow == 'edit.php') {
        // se ci sono altri filtri impostati, devo gestirli! cfr ticket n. 71811
        $meta_key_query = (array) $query->get('meta_query');

        /**
         * Aggiunto filtro per paese, la ricerca avviene utilizzando
         * come chiave il postmeta _billing_country aggiunto da WooCommerce
         */
        if (isset($_GET['fatt24_billing_countries']) && $_GET['fatt24_billing_countries'] != '') {
            $meta_key_query[] = array(
                'key' => '_billing_country',
                'value' => esc_attr($_GET['fatt24_billing_countries']),
                'compare' => '='
            );
        }

        $showCheckbox = fatt_24_show_billing_checkbox();

        if ($showCheckbox) {
            if (isset($_GET['fatt24_customer_required_invoice']) && $_GET['fatt24_customer_required_invoice'] != '-1') {
                $meta_key_query[] = array(
                    'key' => '_billing_checkbox',
                    'value' => esc_attr($_GET['fatt24_customer_required_invoice']),
                    'compare' => '='
                );
            }
        }

        // controllo prima entrambi i filtri, ed entrambi i post_meta
        if (isset($_GET['fatt24_order_docId']) && $_GET['fatt24_order_docId'] != '' && isset($_GET['fatt24_invoice_docId']) && $_GET['fatt24_invoice_docId'] != '') {
            $meta_key_query[] = array(
                'relation' => 'AND',
                    array(
                        'key' => 'fatt24_order_docId',
                        'value' => esc_attr($_GET['fatt24_order_docId']),
                        'compare' => esc_attr($_GET['fatt24_order_docId']) === 'ONS' ? 'NOT EXISTS' : 'NOT LIKE',
                    ),
                    array(
                        'key' => 'fatt24_invoice_docId',
                        'value' => esc_attr($_GET['fatt24_invoice_docId']),
                        'compare' => esc_attr($_GET['fatt24_invoice_docId']) === 'INS' ? 'NOT EXISTS' : 'NOT LIKE',
                    ),
            );
        // filtro ordini f24
        } elseif (isset($_GET['fatt24_order_docId']) && $_GET['fatt24_order_docId'] != '') {
            $meta_key_query[] =
                array(
                    'key' => 'fatt24_order_docId',
                    'value' => esc_attr($_GET['fatt24_order_docId']),
                    'compare' => esc_attr($_GET['fatt24_order_docId']) === 'ONS' ? 'NOT EXISTS' : 'NOT LIKE',
                );

        // filtro fatture f24
        } elseif (isset($_GET['fatt24_invoice_docId']) && $_GET['fatt24_invoice_docId'] != '') {
            $meta_key_query[] =
                array(
                    'key' => 'fatt24_invoice_docId',
                    'value' => esc_attr($_GET['fatt24_invoice_docId']),
                    'compare' => esc_attr($_GET['fatt24_invoice_docId']) === 'INS' ? 'NOT EXISTS' : 'NOT LIKE',
                );
        }
        $query->set('meta_query', $meta_key_query);
    }
}

function fatt_24_custom_query_vars($query_vars) {

    $f24_query_vars = [];
    
    // il campo billing_country non va messo nell'array $query_vars['meta_query']
    if (isset($_GET['fatt24_billing_countries']) && $_GET['fatt24_billing_countries'] != '') {
        $query_vars['billing_country'] = esc_attr($_GET['fatt24_billing_countries']);
    }    

    $showCheckbox = fatt_24_show_billing_checkbox();

    if ($showCheckbox) {
        if (isset($_GET['fatt24_customer_required_invoice']) && $_GET['fatt24_customer_required_invoice'] != '-1') {
            $f24_query_vars[] = array(
                'key' => '_billing_checkbox',
                'value' => esc_attr($_GET['fatt24_customer_required_invoice']),
                'compare' => '='
            );
        }
    }

    if (isset($_GET['fatt24_order_docId']) && $_GET['fatt24_order_docId'] != '' && isset($_GET['fatt24_invoice_docId']) && $_GET['fatt24_invoice_docId'] != '') {
        $f24_query_vars[] = array(
            'relation' => 'AND',
                array(
                    'key' => 'fatt24_order_docId',
                    'compare' => esc_attr($_GET['fatt24_order_docId']) === 'ONS' ? 'NOT EXISTS' : 'EXISTS',
                ),
                array(
                    'key' => 'fatt24_invoice_docId',
                    'compare' => esc_attr($_GET['fatt24_invoice_docId']) === 'INS' ? 'NOT EXISTS' : 'EXISTS',
                ),
        );
    } elseif (isset($_GET['fatt24_order_docId']) && $_GET['fatt24_order_docId'] != '') {
        $f24_query_vars[] =
            array(
                'key' => 'fatt24_order_docId',
                'compare' => esc_attr($_GET['fatt24_order_docId']) === 'ONS' ? 'NOT EXISTS' :'EXISTS',
            );

    // filtro fatture f24
    } elseif (isset($_GET['fatt24_invoice_docId']) && $_GET['fatt24_invoice_docId'] != '') {
        $f24_query_vars[] =
            array(
                'key' => 'fatt24_invoice_docId',
                'compare' => esc_attr($_GET['fatt24_invoice_docId']) === 'INS' ? 'NOT EXISTS' : 'EXISTS',
            );
    }    

    $query_vars['meta_query'] = array_merge($query_vars['meta_query'] ?? array(), $f24_query_vars);
    return $query_vars;

}