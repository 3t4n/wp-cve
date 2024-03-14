<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * aggancia gli hooks di WooCommerce
 * le funzioni di servizio sono in methods/met_hooks_fields.php
 * (per la gestione dei campi fiscali)
 * e in methods/met_hooks.general.php
 * (funzioni generali + gestione ordini)
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

$filesToInclude = [
    'methods/met_hooks_general.php',
    'methods/met_hooks_order_status.php',
    'methods/met_hooks_fields.php',
    'methods/met_save_document.php',
    'behaviour.php',
    'uty.php',
    'order_status.php',
    'messages.php'
];

foreach ($filesToInclude as $file) {
    require_once FATT_24_CODE_ROOT . $file;
}

// cfr. righe 177 e seguenti
$current_admin_url = admin_url(sprintf('%s', basename($_SERVER['REQUEST_URI'])));
$is_order_list = false !== strpos($current_admin_url, 'order');

// gestisco le azioni dell'ordine => utilizzato per le azioni di creazione PDF ordine e fattura
function fatt_24_order_action($order)
{
    $order_id = $order->get_id();
    $order_old_status = $order->get_meta('_old_status', true);
    $transient_id = 'order_action-'.$order_id;

    if (get_transient($transient_id) === false) {
        set_transient($transient_id, true, 60);

        /**
        * Codice modificato il 18.5.2021
        * Crea l'ordine con totale a zero se è contrassegnata l'opzione
        * oppure se il totale ordine NON è zero
        */

        $isZeroOrderEnabled = (int) get_option('fatt-24-ord-zero-tot-enable') == 1;
        $isOrderTotalZero = (float) $order->get_total() == 0.00;
        //fatt_24_trace('zero order enabled :', $isZeroOrderEnabled);
        //fatt_24_trace('is order total zero :', $isOrderTotalZero);

        if ($isZeroOrderEnabled || !$isOrderTotalZero) {
            if (!in_array($order_old_status, ['processing','completed'])) {
                fatt_24_process_order($order);
            }
        }
        fatt_24_process_customer($order_id);
        delete_transient($transient_id);
    } else {
        fatt_24_trace('transient blocked', $transient_id);
    }
}

/**
 * Funzione di convalida dell'ordine:
 * Aggancia un ordine creato da admin
 * Cerco i dati nell'ordine -> sez. billing
 * Per gestire anche i casi in cui le anagrafiche dei clienti
 * vengano create manualmente da admin - Davide Iandoli 08.09.2020
 */
function fatt_24_validate_order($order_id)
{
    $order = wc_get_order($order_id);
    $order_data = $order->get_data();
    $order_status = $order_data['status'];
    $dati_utente_billing = $order_data['billing'];
    foreach ($dati_utente_billing as $k => $v) {
        if (empty($v)) {
            $result[] = '';
        } else {
            $result[] = $v;
        }
    }

    $trashed = $order_status == 'trash'; // non voglio ordini cestinati
    //cfr.: https://stackoverflow.com/questions/6339704/how-do-i-check-if-all-keys-in-an-array-have-empty-values-in-php
    $emptyResult = !array_filter($result); // non voglio ordini senza dati di fatturazione
    $dati_utente = get_user_meta($order->get_user_id());
    if ($dati_utente || !$emptyResult && !$trashed) {
        return true;
    } else {
        return false;
    }
}
$order_hooks = fatt_24_get_order_hooks();

// aggiorno metadati utente alla cancellazione
add_action('delete_user', __NAMESPACE__.'\fatt_24_delete_user');

// creazione ordine
add_action('woocommerce_checkout_order_processed', __NAMESPACE__ .'\fatt_24_checkout_order_processed'); // checkout
add_action($order_hooks['admin_order'], __NAMESPACE__ . '\fatt_24_manage_admin_order'); // admin
add_action('woocommerce_order_status_changed', __NAMESPACE__ .'\fatt_24_order_status_changed', 10, 4);


// con questa variabile consento di scegliere lo status dell'ordine per creare una fattura
$fatt_24_hook = (int) get_option('fatt-24-ord-status-select');  //Qui mi prendo le impostazioni dal menu a discesa

if ($fatt_24_hook == 0) {
    $fatt_24_action = 'woocommerce_order_status_completed'; // Ordine in stato completato
} elseif ($fatt_24_hook == 1) {
    $fatt_24_action = 'woocommerce_order_status_processing'; // Ordine in lavorazione
} else {
    $fatt_24_action = '';
}

// si attiva l'hook in base alla selezione - Davide Iandoli 03.06.2019
add_action($fatt_24_action, __NAMESPACE__ . '\fatt_24_trigger_invoice_on_order_status');


if (!has_action('woocommerce_checkout_fields')) {
    add_filter('woocommerce_billing_fields', __NAMESPACE__ . '\fatt_24_billing_fields');
}

$fatt24BillingHooks = fatt_24_handle_checkout_hooks();
foreach ($fatt24BillingHooks as $hook) {
    add_action($hook['hook'], __NAMESPACE__ . '\\'. $hook['action']);
}

add_action('admin_footer-plugins.php', __NAMESPACE__ .'\fatt_24_deactivation_form');

//add_filter('woocommerce_checkout_fields', __NAMESPACE__ . '\fatt_24_checkout_fields_filter', 10, 1);
add_action('woocommerce_after_checkout_validation', __NAMESPACE__ . '\fatt_24_checkout_fields_validation', 10, 2);

// con questo hook aggiungo i campi personalizzati nell'ordine woocommerce attraverso il metodo update_post_meta
add_action('woocommerce_checkout_create_order', __NAMESPACE__ .'\fatt_24_checkout_meta', 10, 2);

// con questo hook registro i dati dei campi fiscali nell'account utente woocommerce, attraverso il metodo update_user_meta
add_action('woocommerce_created_customer', __NAMESPACE__. '\fatt_24_created_customer');

//con questo hook aggiungo gestico i campi fiscali aggiuntivi usando i metadati del cliente
add_filter('woocommerce_customer_meta_fields', __NAMESPACE__ . '\fatt_24_customer_meta_fields');

// salvo i campi dei dati fiscali nell'account utente
add_action('woocommerce_save_account_details', __NAMESPACE__ . '\fatt_24_save_account_details');

// con questo hook rendo i campi aggiuntivi visibili nell'ordine woocommerce
add_action('woocommerce_admin_order_data_after_billing_address', __NAMESPACE__ . '\fatt_24_add_fields_to_admin_order', 10, 1);

// aggiorno il contenuto dei campi fiscali nel dettaglio dell'ordine
add_action('woocommerce_process_shop_order_meta', __NAMESPACE__ . '\fatt_24_process_order_meta', 45, 2); 

// hook per aggiungere il box 'Fattura24' con le relative azioni
add_action('woocommerce_admin_order_data_after_order_details', __NAMESPACE__ . '\fatt_24_add_admin_data_after_order_details', 10, 1);

// con questo hook aggiungo le colonne personalizzate di F24 nel dettaglio dell'ordine
add_filter($order_hooks['add_custom_columns'], __NAMESPACE__ . '\fatt_24_add_admin_columns');
add_action($order_hooks['manage_custom_column'], __NAMESPACE__ . '\fatt_24_manage_shop_post_columns', 10, 2);

/**
 * In seguito all'introduzione di HPOS il filtro manage_shop_order_posts_custom_column
 * non sempre viene agganciato; per questo motivo aggiungo un'azione all'hook
 * 'manage_posts_custom_column'; a questo punto devo controllare di essere nella lista degli ordini e lo faccio tramite $is_order_list:
 * l'hook infatti verrebbe eseguito anche nella lista dei coupon, ma in quel contesto la funzione fatt_23_manage_shop_post_column
 * causerebbe un errore fatale.
 * Davide Iandoli 4.12.2023
 */
if ($is_order_list && !has_action('manage_shop_order_posts_custom_column')) {
    add_action('manage_posts_custom_column', __NAMESPACE__ . '\fatt_24_manage_shop_post_columns', 10, 2);
}

// https://stackoverflow.com/questions/77366037/filtering-orders-list-in-woocommerce-with-hpos
add_action($order_hooks['restrict_orders'], __NAMESPACE__ . '\fatt_24_restrict_posts');

if (!FATT_24_HPOS_ENABLED) {
    add_action('pre_get_posts', __NAMESPACE__ . '\fatt_24_order_query');
} else {
    /** hook solo per HPOS */
    add_filter('woocommerce_shop_order_list_table_prepare_items_query_args', __NAMESPACE__ . '\fatt_24_custom_query_vars');

}    

// visualizza PDF
add_filter('woocommerce_my_account_my_orders_actions', __NAMESPACE__ . '\fatt_24_display_PDF', 10, 2);
add_action('woocommerce_after_checkout_form', __NAMESPACE__.'\fatt_24_manage_checkout');
add_action('woocommerce_after_account_orders', __NAMESPACE__.'\fatt_24_action_after_account_orders_js');

/*
* Aggiungo fogli di stile utilizzabili anche nel frontend del sito
* cfr.: https://stackoverflow.com/questions/3760222/how-to-include-css-and-jquery-in-my-wordpress-plugin
*/
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\fatt_24_scripts_and_styles');
