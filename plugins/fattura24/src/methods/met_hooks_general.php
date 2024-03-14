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

function fatt_24_CF_flag()
{
    $result = get_option("fatt-24-abk-fiscode-req") == 1 ? true : false;
    return $result;
}

function fatt_24_woo_checkout_hooked()
{
    return has_action('woocommerce_checkout_fields') == true;
}

function fatt_24_handle_checkout_hooks()
{
    $resultHook = [array('hook' => 'woocommerce_checkout_fields', 'action' => 'fatt_24_billing_checkout_fields'),
                   array('hook' => 'woocommerce_edit_account_form', 'action' => 'fatt_24_add_fieldset_to_my_account')];

    return $resultHook;
}

function fatt_24_array_key_last($array)
{
    $key = null;
    if (is_array($array)) {
        end($array);
        $key = key($array);
    }
    return $key;
}

// con questi metodi gestisco l'aggiornamento dei dati fiscali nell'account utente e ne consento il salvataggio
function fatt_24_user_vatcode($user_id)
{
    if (empty($user_id)) {
        return "";
    } else {
        return get_the_author_meta('billing_vatcode', $user_id);
    }
}

function fatt_24_user_fiscalcode($user_id)
{
    if (empty($user_id)) {
        return "";
    }
    return get_the_author_meta('billing_fiscalcode', $user_id);
}

function fatt_24_user_recipientcode($user_id)
{
    if (empty($user_id)) {
        return "";
    }
    return get_the_author_meta('billing_recipientcode', $user_id);
}

function fatt_24_user_pecaddress($user_id)
{
    if (empty($user_id)) {
        return "";
    }
    return get_the_author_meta('billing_pecaddress', $user_id);
}

function fatt_24_checkout_order_processed($order_id)
{
    static $updated, $addedColumn, $updated_default_fe_issue_number, $billing_cb_updated;
    $updated = fatt_24_get_installation_log('triggered update actions');
    $addedColumn = fatt_24_get_installation_log('added used_for_shipping column to tax table');
    $updated_default_fe_issue_number = fatt_24_get_installation_log('updated fe issue number default option');

    if (!$updated) {
       fatt_24_update_actions();
       fatt_24_insert_installation_log('triggered update actions');
       $updated = true;
    }

    if (!$addedColumn) {
        fatt_24_add_column_to_tax_table('used_for_shipping', 'INT', 1, 0);
        fatt_24_insert_installation_log('added used_for_shipping column to tax table');
        $addedColumn = true;
    }

    if (!$updated_default_fe_issue_number) {
        fatt_24_default_sezionale_fe();
        fatt_24_insert_installation_log('updated fe issue number default option');
        $updated_default_fe_issue_number = true;
    }


    $billing_cb_updated = fatt_24_get_installation_log('updated billing cb postmeta');
    if (!$billing_cb_updated) {
        fatt_24_update_actions();
        $billing_cb_updated = true;
    }

    $order = new \WC_Order($order_id);
    $isZeroOrderEnabled = (int) get_option('fatt-24-ord-zero-tot-enable') == 1;
    $isOrderTotalZero = (float) $order->get_total() == 0.00;
    $isOrderEnabled = (int) get_option(FATT_24_ORD_CREATE) == 1;

    if (($isOrderEnabled && !$isOrderTotalZero)  || ($isOrderEnabled && $isZeroOrderEnabled)) {
        fatt_24_process_order($order);
        //fatt_24_download_PDF_order($order_id);
    }
}

function fatt_24_manage_admin_order($order_id)
{
    static $updated, $addedColumn, $updated_default_fe_issue_number, $billing_cb_updated;
    $updated = fatt_24_get_installation_log('triggered update actions');
    $addedColumn = fatt_24_get_installation_log('added used_for_shipping column to tax table');
    $updated_default_fe_issue_number = fatt_24_get_installation_log('updated fe issue number default option');

    if (!$updated) {
       fatt_24_update_actions();
       fatt_24_insert_installation_log('triggered update actions');
       $updated = true;
    }

    if (!$addedColumn) {
        fatt_24_add_column_to_tax_table('used_for_shipping', 'INT', 1, 0);
        fatt_24_insert_installation_log('added used_for_shipping column to tax table');
        $addedColumn = true;
    }

    if (!$updated_default_fe_issue_number) {
        fatt_24_default_sezionale_fe();
        fatt_24_insert_installation_log('updated fe issue number default option');
        $updated_default_fe_issue_number = true;
    }

    $billing_cb_updated = fatt_24_get_installation_log('updated billing cb postmeta');
    if (!$billing_cb_updated) {
        fatt_24_update_actions();
        $billing_cb_updated = true;
    }

    /**
     * Con questo blocco di codice punto a evitare che vengano creati ordini
     * in uno degli stati elencati nell'array. Aggiunta l'opzione 'Mai'
     *
     * Fix del 21.12.2020: aggiunto controllo sul tipo di post
     * non voglio interferire sull'aggiunta di prodotti, coupon etc.
     */
    $disabledOrder = get_option(FATT_24_ORD_CREATE) == 0;
    $orderStatusChosen = get_option(FATT_24_ORD_CREATE) == 2;
    $selectedStatusOption = get_option(FATT_24_ORD_STATUS_SELECT);
    $postTypesArray = ['shop_order', 'shop_subscription'];
    $postType = get_post_type($order_id);
    $postStatus = get_post_status($order_id);
    $postStatusArray = ['wc-cancelled', 'wcm-cancelled', 'wc-pending-cancel', 'trash'];
    $postTypeAllowed = in_array($postType, $postTypesArray) && !in_array($postStatus, $postStatusArray);

    if ($disabledOrder || !$postTypeAllowed) {
        return;
    }

    $order = new \WC_Order($order_id);
    $order_data = $order->get_data();
    $order_status = $order_data['status'];
    $isOrderStatusSelected = ($selectedStatusOption == 0 && $order_status == 'completed') || ($selectedStatusOption == 1 && $order_status == 'processing');

    if ($orderStatusChosen && !$isOrderStatusSelected) {
        return;
    }

    if (!did_action('woocommerce_checkout_order_processed') && $postTypeAllowed && fatt_24_validate_order($order_id)) {
        fatt_24_process_order($order);
        //fatt_24_download_PDF_order($order_id);
    }
}

//cfr.: https://stackoverflow.com/questions/54347823/get-last-old-order-status-before-updated-status-in-woocommerce
function fatt_24_order_status_changed($order_id, $status_from, $status_to, $order)
{
    if ($order->get_meta('_old_status')) {
        $order->update_meta_data('_old_status', $status_from);
    } else {
        $order->update_meta_data('_old_status', 'pending');
    }
    $order->save_meta_data();
}

/**
 * Questo metodo viene chiamato quando lo status dell'ordine in WooCommerce
 * viene cambiato (manualmente o automaticamente: ad esempio da plugin come stripe)
 * cfr. ticket n.: 4294 DT
 * 
 * 
 * @param mixed $order_id
 * @return void
 */
function fatt_24_trigger_invoice_on_order_status($order_id)
{
  
    $order = new \WC_Order($order_id);
    
    if (0 === $order_id) {
        fatt_24_trace('Potenziale errore: l\'ordine potrebbe non essere stato salvato perché l\'id è zero !');
    }
     
     /**  
     * $order_old_status  mi serve per evitare di ricreare un doc già creato 
     * se un ordine viene rimesso in completato o processing in un secondo momento
     */
    $order_old_status = $order->get_meta('_old_status', true);
    $triggerOrderCreation = get_option(FATT_24_ORD_CREATE) == '2';

    /**
    * Codice modificato il 18.5.2021
    * Crea ordine e/o fattura / ricevuta anche con totale a zero se è contrassegnata l'opzione
    * oppure se il totale ordine NON è zero (predefinito)
    */

    $isZeroInvoiceEnabled = (int) get_option('fatt-24-inv-zero-tot-enable') == 1;
    $isZeroOrderEnabled = (int) get_option('fatt-24-ord-zero-tot-enable') == 1;
    $isOrderTotalZero = (float) $order->get_total() == 0.00;
    
    /**
     * Davide Iandoli 05.01.2023 
     *  valori della select status dell'ordine : 0 => completato, 1 => in lavorazione, 2 => Nessuno
     * Devo creare un documento sempre tranne se:
     * - la creazione della fattura è disabilitata (controllo: nella funz. fatt_24_process_fattura qui sotto);
     * - l'opzione selezionata in status dell'ordine è 'Nessuno' (creazione solo tramite pulsante);
     * (controllo in hooks.php righe 109 e seguenti prima di questa funzione);
     * Controlli successivi: 
     * - è già stata creato un documento ($order_old_status == 'completed') NB: lo stesso controllo è fatto dopo in order_status.php riga 376; 
     * - è già stata creato un documento ($order_old_status == 'processing' e l'opzione crea documento selezionata 
     *  in Stato dell'ordine = 'In lavorazione');
     * - se il totale documento è a zero e l'utente non ha selezionato l'opzione apposita;
     */

     // casi in cui non devo creare la fattura
     $caseInvoice1 = '0' == get_option("fatt-24-inv-create");
     $caseInvoice2 = $order_old_status == 'completed';
     $caseInvoice3 = $order_old_status == 'processing' && '1' == get_option('fatt-24-ord-status-select');
     $caseInvoice4 = $isOrderTotalZero && $isZeroInvoiceEnabled == false;
  

    $createInvoice = true;
    if ($caseInvoice1 || $caseInvoice2 || $caseInvoice3 || $caseInvoice4) {
        $createInvoice = false;
    }
    /*if ('0' == get_option("fatt-24-inv-create") ) {
        $createInvoice = false;
    } elseif ($order_old_status == 'completed') {
        $createInvoice = false;
    } elseif ($order_old_status == 'processing') {
        if ('1' == get_option('fatt-24-ord-status-select')) {
            $createInvoice = false;
        }
    } elseif ($isOrderTotalZero) {
        if ($isZeroInvoiceEnabled == false) {
            $createInvoice = false;
        }
    }*/


   $createOrder = true;

   // casi in cui non devo creare l'ordine
   $caseOrder1 = !$triggerOrderCreation;
   $caseOrder2 = $order_old_status == 'completed';
   $caseOrder3 = $isOrderTotalZero && !$isZeroOrderEnabled;

   if ($caseOrder1 || $caseOrder2 || $caseOrder3) {
      $createOrder = false;
   }

    /*if ($triggerOrderCreation == true) {
        $createAlsoOrder = $createInvoice ? true : false;
        if ($order_old_status == 'completed') {
            $createAlsoOrder = false;
        } elseif ($isOrderTotalZero && !$isZeroOrderEnabled) {
            $createAlsoOrder = false;
        }
    }*/
    $createBoth = $createOrder && $createInvoice;

    if ($createBoth) {
        $createOrder = false;
        $createInvoice = false;
        fatt_24_process_order($order);
        fatt_24_process_fattura($order);
    }


    if ($createOrder) {
        fatt_24_process_order($order);
    }


    if ($createInvoice) {
        fatt_24_process_fattura($order);
    }
   // fine edit del 05.01.2023

}

/**
 * Mostra i link nella sezione 'il mio account'
 *
 */
function fatt_24_display_PDF($actions, $order)
{
    $invType = fatt_24_get_invoice_doctype();
    $order_id = $order->get_id();
    $order_status = fatt_24_get_order_status($order);
    $orderLink = isset(fatt_24_get_pdf_link($order_status, 'C')['pdfPath'])
                 ? fatt_24_get_pdf_link($order_status, 'C')['pdfPath'] : '';
    $invLink = isset(fatt_24_get_pdf_link($order_status, $invType)['pdfPath'])
                 ? fatt_24_get_pdf_link($order_status, $invType)['pdfPath'] : '' ;
    $orderPDFUrl = fatt_24_order_PDF_url($orderLink);
    $invPDFUrl = fatt_24_order_PDF_url($invLink);
    $viewPdfOrder = isset($orderLink) && file_exists($orderLink);
    $viewPdfInvoice = isset($invLink) && file_exists($invLink);

    if ($viewPdfOrder) {
        $actions['orderPdfView'] = array(
            'url' => $orderPDFUrl,
            'name' => __('PDF Order', 'fattura24'),
        );
    }

    if ($viewPdfInvoice) {
        $actions['invoicePdfView'] = array(
            'url' => $invPDFUrl,
            'name' => __('PDF Invoice', 'fattura24'),
        );
    }

    return $actions;
}

function fatt_24_action_after_account_orders_js()
{
    $addJs = 'f24_my_account_actions';
    wp_enqueue_script($addJs, fatt_24_url('/js/myaccount/'. $addJs . '.js'), array());
}

function fatt_24_scripts_and_styles()
{
    wp_register_style('fattura24', fatt_24_url('/css/style.css'), [], '1.0.0');
    wp_enqueue_style('fattura24');
}

function fatt_24_get_order_hooks()
{
    $hook_list = FATT_24_HPOS_ENABLED ? 
        [
         'woocommerce_new_order', 
         'manage_woocommerce_page_wc-orders_columns',
         'manage_woocommerce_page_wc-orders_custom_column',
         'woocommerce_order_list_table_restrict_manage_orders', 
        ] 
        : 
        [
         'wp_insert_post',
         'manage_edit-shop_order_columns',
         'manage_shop_order_posts_custom_column',
         'restrict_manage_posts',
        ];
    
    
    $order_hooks = [
        'admin_order' => $hook_list[0],
        'add_custom_columns' => $hook_list[1],
        'manage_custom_column' => $hook_list[2],
        'restrict_orders' => $hook_list[3],
    ];

    return $order_hooks;
}

