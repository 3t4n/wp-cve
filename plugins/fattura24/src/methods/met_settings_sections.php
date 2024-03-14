<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * File in cui creo le impostazioni del modulo
 * usato in settings.php
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

require_once FATT_24_CODE_ROOT. 'settings_uty.php';

 /* Intestazione */
function fatt_24_info()
{
    $sect_key = array(
        'section_header'    => fatt_24_headers_style()['open'] . __('Info', 'fattura24') . fatt_24_headers_style()['close'], // new header
        'section_callback'  => null,
        'section_id'        =>FATT_24_OPT_SECT_ID,
        'fields'            =>array(
            FATT_24_OPT_PLUGIN_VERSION=>array(
                'type'  => 'label',
                'label' => __('Plugin version', 'fattura24'),
                'text'  => FATT_24_PLUGIN_DATA['Version'],
                'desc' => fatt_24_getVersionCheckMessage()
            ),
            FATT_24_OPT_API_KEY=>array(
                'type'  => 'text_cmd', // cambiare in password_cmd per utilizzare la nuova logica
                'size'  => '40',
                'label' => __('API Key', 'fattura24'),
                'cmd_id' => FATT_24_OPT_API_VERIFICATION,
                'cmd_text' => esc_html__('Save and test key', 'fattura24'),
                                               
            ),
            FATT_24_API_MESSAGE=>array( // qui appare il messaggio che restituisce l'esito della verifica API
                'type' => 'label',
                'label' => '',
                'text' => fatt_24_getApiInputMessage(),
            ),
            FATT_24_WOO_RATING=>array(
                'type' => 'hidden',
                'label' => '',
                'default' => 0,
            ),
        )
    );
    return $sect_key;
}

/* Sezione Rubrica */
function fatt_24_addrbook()
{
    $sect_addrbook = array(
        'section_header'    => fatt_24_headers_style()['open'] .__('Address Book', 'fattura24') . fatt_24_headers_style()['close'],
        'section_callback'  => null,
        'section_id'        => FATT_24_ABK_SECT_ID,
        'fields'            => array(
            FATT_24_ABK_SAVE_CUST_DATA=>array(
                'type'  => 'bool',
                'default' => true,
                'label' => __('Save Customer', 'fattura24'),
                'desc'  => __(' Enable saving Customer data on fattura24 address book', 'fattura24'),
                'help'  => __('Flag this box to save Customer data in Fattura24 address book', 'fattura24')
            ),
            
            FATT_24_ADD_VAT_FIELD=>array(
                'type' => 'text',
                'size' => '40',
                'default' => fatt_24_getVatFieldFrom(),
                'label' => __('Vat Number field', 'fattura24'),
                'desc' => __('Plugin from which we pick customer VAT Number', 'fattura24'),
                'help' => __('By default Fattura24 adds its own VAT field; it will not be added if you installed a suitable plugin', 'fattura24'),
                'readonly' => 'true',
            ),
            // rendere visibili i campi fiscali
            FATT_24_TOGGLE_BILLING_FIELDS=>array(
                'type'  => 'bool',
                'default' => '0',
                'label' => __('Display fiscal fields', 'fattura24'),
                'desc'  => __(' Display additional billing fields', 'fattura24'),
                'help'  => __('By flagging this box the fields needed to issue an invoice will be always displayed in checkout page, otherwise they will be displayed only if in checkout customer would receive an invoice', 'fattura24')
            ),
            FATT_24_ABK_FISCODE_REQ=>array(
                'type'  => 'bool',
                'label' => __('Fiscal Code required', 'fattura24'),
                'desc'  => __(' Fiscal Code required', 'fattura24'),
                'help'  => __('Flag this box to make the Fiscal Code input field required if customer chose Italy as billing country', 'fattura24')
            ),
            FATT_24_ABK_VATCODE_REQ=>array(
                'type'  => 'bool',
                'label' => __('Vat Code required', 'fattura24'),
                'desc'  => __(' Vat Code required', 'fattura24'),
                'help'  => __('Flag this box to make the Vat Code input field required', 'fattura24'),
            )
        )
    );
    return $sect_addrbook;
}

/* Sezione Ordini */
function fatt_24_orders()
{
    $old_order_option = is_null(fatt_24_get_old_options(FATT_24_ORD_CREATE))? '0': '1';
   
    $sect_orders = array(
        'section_header'    => fatt_24_headers_style()['open'] .__('Orders', 'fattura24') . fatt_24_headers_style()['close'],
        'section_callback'  => null,
        'section_id'        => FATT_24_ORD_SECT_ID,
        'fields'            => array(
            FATT_24_ORD_CREATE=>array(
                'type'  => 'select',
                'default' => $old_order_option,
                'options' => array(__('Never', 'fattura24'), __('On purchased order in shop', 'fattura24'), __('On selected order status', 'fattura24')),
                'label' => __('Create order', 'fattura24'),
                'desc'  => __(' Enable order creation in Fattura24', 'fattura24'),
                'help'  => __('Choose the option for order creation in Fattura24', 'fattura24')
            ),
            FATT_24_ORD_ZERO_TOT_ENABLE=>array(
                'type' => 'bool',
                'default' => false,
                'label' => __('Enable order creation when order total is zero', 'fattura24'),
                'desc' => __('Enable order creation when order total is zero', 'fattura24'),
                'help' => __('By default order creation when total is zero is disabled. Flag this box to enable it', 'fattura24')
            ),
            FATT_24_ORD_ADD_DESCRIPTION=>array(
                'type' => 'select',
                'default' => '0',
                'options' => array(__('Default', 'fattura24'), __(' Add short description', 'fattura24'), __(' Add description', 'fattura24')),
                'label' => __('Product name text', 'fattura24'),
                'desc'  => __(' Select the text you want to append to product name', 'fattura24'),
                'help'  => __(' Choose the option you want to append description or short description to product name', 'fattura24'),
            ),
            FATT_24_ORD_ENABLE_PDF_DOWNLOAD => array(
                'type'    => 'bool',
                'default' => '0',
                'label'   => __('Enable download of pdf file', 'fattura24'),
                'desc' => __('By default download of PDF copy is disabled. Click to enable', 'fattura24'),
                'help' => __('By default download of PDF copy is disabled. Click to enable', 'fattura24')
            ),
            FATT_24_ORD_SEND=>array(
                'type'  => 'bool',
                'label' => __('Send email', 'fattura24'),
                'desc'  => __(' Email a PDF copy of Fattura24 order from F24 server', 'fattura24'),
                'help'  => __('Flag this box to email a PDF copy of Fattura24 order', 'fattura24')
            )
        )
    );
    return $sect_orders;
}

/* Sezione Fattura */
function fatt_24_invoices()
{
    $sect_invoices = array(
        'section_header'    => fatt_24_headers_style()['open'] .__('Invoices', 'fattura24') . fatt_24_headers_style()['close'],
        'section_callback'  => null,
        'section_id'        => FATT_24_INV_SECT_ID,
        'fields'            => array(
            FATT_24_INV_CREATE=>array(
                'type'  => 'select',
                'default' => '0',
                'options' => fatt_24_getInvoiceOptionsNew(), // mi prendo le opzioni dalla funzione
                'label' => __('Document types allowed', 'fattura24'),
                'desc'  => __(' Enable document creation in Fattura24', 'fattura24'),
                'help'  => __('The document on Fattura24 will be created according to customers\' choice in checkout page', 'fattura24')
            ),
            FATT_24_INV_DISABLE_RECEIPTS=>array(
                'type'  => 'bool',
                'label' => __('Create always and only invoices', 'fattura24'),
                'desc'  => __(' Create an invoice instead of a receipt even in lack of customer VAT code', 'fattura24'),
                'help'  => __('Flag this box to enable the creation of an invoice instead of a receipt even in lack of customer VAT code', 'fattura24')
            ),
            FATT_24_INV_ZERO_TOT_ENABLE=>array(
                'type' => 'bool',
                'default' => false,
                'label' => __('Enable invoice creation when order total is zero', 'fattura24'),
                'desc' => __('Enable invoice creation when order total is zero', 'fattura24'),
                'help' => __('By default order creation when total is zero is disabled. Flag this box to enable it', 'fattura24')
            ),
            FATT_24_INV_ADD_DESCRIPTION=>array(
                'type' => 'select',
                'default' => '0',
                'options' => array(__('Default', 'fattura24'), __(' Add short description', 'fattura24'), __(' Add description', 'fattura24')),
                'label' => __('Product name text', 'fattura24'),
                'desc'  => __(' Select the text you want to append to product name', 'fattura24'),
                'help'  => __(' Choose the option you want to append description or short description to product name', 'fattura24'),
            ),
            FATT_24_ORD_STATUS_SELECT=>array( // aggiunta selezione degli stati dell'ordine
                'type'    => 'select',
                'default' => '0',
                'options' => array(__('Completed', 'fattura24'),__('Processing', 'fattura24'),__('None', 'fattura24')),
                'label'   => __('Order status', 'fattura24'),
                'help'    => __('Choose order status for automatic invoice creation', 'fattura24'),
                'desc'    => __('Invoice will be created as soon as the order will be put in the selected status', 'fattura24')
            ),
            FATT_24_INV_OBJECT=>array( // aggiunto campo causale del documento
                'type'    => 'text_cmd',
                'default' => 'Ordine E-Commerce (N)', // causale di default, altrimenti prende il dato immesso nel campo
                'label'   => __('Purpose of payment', 'fattura24'),
                'help'    => __('Put (N) in any place to add order number in document object', 'fattura24'),
                'desc'    => __('Put (N) in any place to add order number in document object', 'fattura24'),
                'cmd_id' => FATT_24_INV_DEFAULT_OBJECT, // aggiungo un tasto per l'oggetto predefinito
                'cmd_text' => esc_html__('Default', 'fattura24'),
            ),
            FATT_24_INV_ENABLE_PDF_DOWNLOAD => array(
                'type'    => 'bool',
                'default' => '1',
                'label'   => __('Enable download of pdf file', 'fattura24'),
                'desc' => __('By default download of PDF copy is enabled. Click to disable', 'fattura24'),
                'help' => __('By default download of PDF copy is enabled. Click to disable', 'fattura24')
            ),
            FATT_24_INV_SEND=>array(
                'type'  => 'bool',
                'label' => __('Send email', 'fattura24'),
                'desc'  => __(' Send PDF copy to customer by email when order status is completed (only for NON-Electronic docs)', 'fattura24'),
                'help'  => __('Enable automatic sending of invoice from Fattura24 server to customer', 'fattura24')
            ),
            FATT_24_INV_WHEN_PAID=>array(
                'type'  => 'select', // cambiato in menu a discesa
                'label' => __('Status Paid', 'fattura24'),
                'default' => '0',
                'options' => array(__('Never', 'fattura24'),__('Always', 'fattura24'), __('E-payments (e.g.: Paypal)', 'fattura24')),
                'desc'  => __(' Create invoice in Paid status', 'fattura24'),
                'help'  => __('Choose a condition to create a document in Paid status', 'fattura24')
            ),
            FATT_24_BOLLO_VIRTUALE_FE=>array(
                'type' => 'select',
                'default' => '0',
                'label' => __('Electronic Invoices virtual stamp', 'fattura24'),
                'options' => array(__('Never', 'fattura24'), __('VAT exempt fiscal regime and order total > 77,47 Euros', 'fattura24')),
                'desc' => __('I will pay the virtual stamp for the customer', 'fattura24'),
                'help' =>__('I will pay the virtual stamp for the customer', 'fattura24')

            )
        )
    );
    return $sect_invoices;
}

/* Sezione personalizzazione modelli, sezionali, pdc, attributi prodotto */
function fatt_24_optional_settings()
{
    $sect_optional_settings = array(
        'section_header'    => fatt_24_headers_style()['open'] .__('Optional settings', 'fattura24') . fatt_24_headers_style()['close'],
        'section_callback'  => null,
        'section_id'        => FATT_24_OPTIONAL_SETTINGS,
        'fields'            => array(
            FATT_24_ADD_PROD_VAR=>array(
                'type' => 'select',
                'default' => '1',
                'label' => __('Add attributes or variations to product text', 'fattura24'),
                'options' => array(__('Nothing', 'fattura24'), __('All product attributes', 'fattura24'), __('Only variations', 'fattura24')),
                'help' => __('Choose if you want to add all attributes, only variations or nothing to product text', 'fattura24'),
                'desc' => __('By default the text of all attributes is added to product name. Click to change', 'fattura24')
            ),
            FATT_24_INV_SEZIONALE_RICEVUTA=>array(
                'type'  => 'select',
                'default' => '0',
                'options' => fatt_24_getSezionale(3),
                'label' => __('Receipts issue number', 'fattura24'),
                'desc'  => __(' Select receipts issue number', 'fattura24'),
                'help'  => __('Select receipts issue number from the list of active issue numbers in Fattura24. To display this list save your Api Key in WordPress', 'fattura24')
            ),
            FATT_24_INV_SEZIONALE_FATTURA=>array(
                'type'  => 'select',
                'default' => '0',
                'options' => fatt_24_getSezionale(1),
                'label' => __('Invoices issue number', 'fattura24'),
                'desc'  => __(' Select invoices issue number', 'fattura24'),
                'help'  => __('Select invoices issue number from the list of active issue numbers in Fattura24. To display this list save your Api Key in WordPress', 'fattura24')
            ),
            FATT_24_INV_SEZIONALE_FATTURA_ELETTRONICA=>array(
                'type'  => 'select',
                'default' => '0',
                'options' => fatt_24_getSezionale(11),
                'label' => __('Electronic invoices issue number', 'fattura24'),
                'desc'  => __(' Select electronic invoices issue number', 'fattura24'),
                'help'  => __('Select electronic invoices issue number from the list of active issue numbers in Fattura24. To display this list save your Api Key in WordPress', 'fattura24')
            ),
            FATT_24_ORD_TEMPLATE=>array(
                'type'  => 'select',
                'default' => '0',
                'options' => fatt_24_getTemplate(true),
                'label' => __('Order template', 'fattura24'),
                'desc'  => __(' Select the template to create the PDF copy of an order', 'fattura24'),
                'help'  => __('Select the template you want to use to create a PDF copy of an order: this will be used in lack of a shipping address. Notice: to display the list save your Api Key in WooCommerce', 'fattura24')
            ),
            FATT_24_ORD_TEMPLATE_DEST=>array(
                'type'  => 'select',
                'default' => '0',
                'options' => fatt_24_getTemplate(true),
                'label' => __('Order template with destination', 'fattura24'),
                'desc'  => __(' Select the template to create PDF copy of an order which contains a shipping address', 'fattura24'),
                'help'  => __('Select the template you want to use to create PDF copy of an order: this will be used for orders containing a shipping address. Notice: to display the list save your Api Key in WooCommerce', 'fattura24')
            ),
            FATT_24_INV_TEMPLATE=>array(
                'type'  => 'select',
                'default' => '0',
                'options' => fatt_24_getTemplate(false),
                'label' => __('Invoice template', 'fattura24'),
                'desc'  => __(' Select the template to create the PDF copy of an invoice', 'fattura24'),
                'help'  => __('Select the template you want to use to create the PDF copy of an invoice: this will be used in lack of a shipping address. Notice: to display the list save your Api Key in WooCommerce', 'fattura24')
            ),
            FATT_24_INV_TEMPLATE_DEST=>array(
                'type'  => 'select',
                'default' => '0',
                'options' => fatt_24_getTemplate(false),
                'label' => __('Invoice template with destination', 'fattura24'),
                'desc'  => __(' Select PDF template to create the PDF copy of an invoice', 'fattura24'),
                'help'  => __('Select the template you want to use to create the PDF copy of an invoice: this will be used for orders containing a shipping address. Notice: to display the list save your Api Key in WooCommerce', 'fattura24')
            ),
            FATT_24_INV_PDC=>array(
                'type'  => 'select',
                'default' => '0',
                'options' => fatt_24_getPdc(),
                'label' => __('Chart of accounts', 'fattura24'),
                'desc'  => __(' Select the chart of accounts that will be associated with the items of the documents', 'fattura24'),
                'help'  => __('Select the chart of accounts that will be associated with the items of the invoice from your Fattura24 economic accounts. To display the list save your Api Key in WooCommerce', 'fattura24')
            ),
            FATT_24_INV_PDC_SHIPPING=>array(
                'type'  => 'select',
                'default' => '0',
                'options' => fatt_24_getPdc(),
                'label' => __('Chart of accounts for shipping', 'fattura24'),
                'desc'  => __(' Select the chart of accounts that  will be associated with the shipping line of the documents', 'fattura24'),
                'help'  => __('Select the chart of accounts that will be associated with the shipping line of the invoice from your Fattura24 economic accounts. To display the list save your Api Key in WooCommerce', 'fattura24')
            ),
            FATT_24_INV_PDC_FEES=>array(
                'type'  => 'select',
                'default' => '0',
                'options' => fatt_24_getPdc(),
                'label' => __('Chart of accounts for fees', 'fattura24'),
                'desc'  => __(' Select the chart of accounts that  will be associated with the fees line of the documents', 'fattura24'),
                'help'  => __('Select the chart of accounts that will be associated with the fees line of the invoice from your Fattura24 economic accounts. To display the list save your Api Key in WooCommerce', 'fattura24')
            ),
        )
    );

    return $sect_optional_settings;

}



/* Sezione log */
function fatt_24_advanced()
{
     $sect_logs = array(
        'section_header'    => fatt_24_headers_style()['open'] .__('Advanced', 'fattura24') . fatt_24_headers_style()['close'],
        'section_callback'  => null,
        'section_id'        => FATT_24_ADVANCED,
        'fields'            => array(
            /*FATT_24_API_ENDPOINT=>array(
                'type' => 'text',
                'size' => '40',
                'label' => __('Fattura24 API base url', 'fattura24'),
                'readonly' => 'false',
            ),*/
            FATT_24_LOG_ENABLE=>array(
                'type' => 'bool',
                'default' => false,
                'label' => __('Debug', 'fattura24'),
                'help' => __('Click this box to enable debug logging', 'fattura24'),
                'desc' => __('Enable', 'fattura24')
            ),
            FATT_24_LOG_DOWNLOAD=>array(
                'type'  => 'button',
                'label' => __('Download Log file', 'fattura24'),
                'text' => __('Download', 'fattura24'),
                'help'  => __('Warning: Fattura24 log file may be used for debugging, so WP debug mode must be active. Download and send Fattura24 log file to info@fattura24.com when you need help in troubleshooting', 'fattura24')
            ),
        )
    );
      
    return $sect_logs;
}

/** 
 * Elenco delle impostazioni per il plugin Fattura24
 * utilizzato come dato in caso di spedizione ticket
 */
function fatt_24_get_settings() {
    $result = [];
    $addr_book = array_keys(fatt_24_addrbook()['fields']);
    $orders = array_keys(fatt_24_orders()['fields']);
    $invoices = array_keys(fatt_24_invoices()['fields']);

    foreach ($addr_book as $sec2) {
        $result[$sec2] = get_option($sec2);
    }

    foreach ($orders as $sec3) {
        $result[$sec3] = get_option($sec3);
    }
    
    foreach ($invoices as $sec4) {
        $result[$sec4] = get_option($sec4);
    }
    return $result;
}
