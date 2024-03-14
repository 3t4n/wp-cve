<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com> 
 *
 * definizione costanti utilizzate dal plugin
 * 
 */

namespace fattura24;



if (!defined('ABSPATH')) exit;


// generali
define('FATT_24_PLUGIN_DATA',            get_file_data(plugin_dir_path(__FILE__).'../fattura24.php',
                                                            array('Name' => 'Plugin Name',
                                                                  'Version' => 'Version')));

define('FATT_24_CODE_ROOT',              __DIR__ .'/'); // la home directory è 'src'
define('FATT_24_SETTINGS_PAGE',         'fatt-24-settings');
define('FATT_24_SETTINGS_GROUP',        'fatt-24-group');
define('FATT_24_LAYOUT_OPTION',         'fatt-24-layout-option');
define('FATT_24_WOO_RATING',            'fatt-24-woo-rating');
define('FATT_24_OPT_SECT_ID',           'fatt-24-API-sect-id');
define('FATT_24_OPT_API_KEY',           'fatt-24-API-key');
define('FATT_24_OPT_API_VERIFICATION',  'fatt-24-API-verification');
define('FATT_24_OPT_PLUGIN_VERSION',    'fatt-24-plugin-version');
define('FATT_24_HPOS_ENABLED',          'yes' === get_option('woocommerce_custom_orders_table_enabled'));


// API
define('FATT_24_API_SOURCE',             'F24-Woo '. FATT_24_PLUGIN_DATA['Version']); // nuovo parametro source delle chiamate API
define('FATT_24_API_MESSAGE',            'fatt-24-api-message'); // costante per l'esito verifica API
define('FATT_24_API_ROOT',               'https://www.app.fattura24.com/api/v0.3'); 
define('FATT_24_API_ENDPOINT',           'fatt-24-api-endpoint');
define('FATT_24_API_FIELD_MAX_indirizzo',   60);
define('FATT_24_API_FIELD_MAX_citta',       50);
define('FATT_24_API_FIELD_MAX_provincia',   2);
define('FATT_24_API_FIELD_MAX_cap',         10);
define('FATT_24_API_FIELD_MAX_paese',       50);

// caselle di controllo
define('FATT_24_ABK_SECT_ID',           'fatt-24-addrbook-sect-id');
define('FATT_24_ABK_SAVE_CUST_DATA',    'fatt-24-abk-save-cust-data');
define('FATT_24_ABK_FISCODE_REQ',       'fatt-24-abk-fiscode-req');
define('FATT_24_ABK_VATCODE_REQ',       'fatt-24-abk-vatcode-req');
define('FATT_24_TOGGLE_BILLING_FIELDS', 'fatt-24-toggle-billing-fields');
define('FATT_24_ADD_VAT_FIELD',         'fatt-24-add-vat-field');

// cliente
define('FATT_24_CUSTOMER_USER_DATA',        'fatt-24-customer-user-data');
define('FATT_24_CUSTOMER_USE_VAT',          'fatt-24-customer-use-vat');
define('FATT_24_CUSTOMER_USE_CF',           'fatt-24-customer-use-cf');
define('FATT_24_CUSTOMER_USE_PEC_ADDRESS',   'fatt-24-customer-use-pec-address');
define('FATT_24_CUSTOMER_USE_RECIPIENTCODE', 'fatt-24-customer-use-recipientcode');

// ordine
define('FATT_24_ORD_SECT_ID',           'fatt-24-ord-sect-id');
define('FATT_24_ORD_CREATE',            'fatt-24-ord-enable-create');
define('FATT_24_ORD_DOWNLOAD',          'fatt-24-ord-download-pdf');
define('FATT_24_ORD_SEND',              'fatt-24-ord-send-pdf');
define('FATT_24_ORD_STATUS_SELECT',     'fatt-24-ord-status-select'); // aggiunge possibilità di scegliere lo stato dell'ordine per la creazione delle fatture
define('FATT_24_ORD_TEMPLATE',          'fatt-24-ord-template');
define('FATT_24_ORD_TEMPLATE_DEST',     'fatt-24-ord-template-dest');
define('FATT_24_ORD_ZERO_TOT_ENABLE',   'fatt-24-ord-zero-tot-enable'); // abilita creazione ordini tot a zero
define('FATT_24_ORD_ADD_DESCRIPTION',   'fatt-24-ord-add-description');
define('FATT_24_ORD_ENABLE_PDF_DOWNLOAD',    'fatt-24-ord-enable-pdf-download');

// dati fiscali e stato ordine
define('FATT_24_ORDER_INVOICE_STATUS',      'fatt-24-order-invoice-status'); // stato dell'ordine e della fattura
define('FATT_24_ORDER_GET_PEC_ADDRESS',     'fatt-24-order-get-pec-address'); // campi fiscali aggiuntivi
define('FATT_24_ORDER_GET_RECIPIENTCODE',   'fatt-24-order-get-recipientcode');
define('FATT_24_ORDER_GET_VAT',             'fatt-24-order-get-vat');
define('FATT_24_ORDER_GET_CF',              'fatt-24-order-get-cf'); // define ('FATT_24_ORDER_GET_CF', 'fatt-24-order-use-cf')

// fattura
define('FATT_24_INV_SECT_ID',           'fatt-24-inv-sect-id');
define('FATT_24_INV_CREATE',            'fatt-24-inv-create');
define('FATT_24_INV_ZERO_TOT_ENABLE',   'fatt-24-inv-zero-tot-enable'); // abilita creazione fatture tot a zero
define('FATT_24_INV_OBJECT',            'fatt-24-inv-object'); //nuovo campo per la gestione dell'oggetto personalizzato
define('FATT_24_INV_DEFAULT_OBJECT',    'fatt-24-inv-default-object'); // oggetto del documento predefinito 
define('FATT_24_INV_DOWNLOAD',          'fatt-24-inv-download-pdf');
define('FATT_24_INV_SEND',              'fatt-24-inv-send-pdf');
define('FATT_24_INV_ADD_DESCRIPTION',   'fatt-24-inv-add-description');
define('FATT_24_INV_WHEN_PAID',         'fatt-24-inv-create-when-paid');
define('FATT_24_INV_DISABLE_RECEIPTS',  'fatt-24-inv-disable-receipts');
define('FATT_24_INV_TEMPLATE',          'fatt-24-inv-template');
define('FATT_24_INV_TEMPLATE_DEST',     'fatt-24-inv-template-dest');
define('FATT_24_INV_PDC',               'fatt-24-inv-pdc');
define('FATT_24_INV_PDC_SHIPPING',      'fatt-24-inv-pdc-shipping');
define('FATT_24_INV_PDC_FEES',          'fatt-24-inv-pdc-fees');
define('FATT_24_INV_SEZIONALE_RICEVUTA','fatt-24-inv-sezionale-ricevuta');
define('FATT_24_INV_SEZIONALE_FATTURA', 'fatt-24-inv-sezionale-fattura');
define('FATT_24_INV_SEZIONALE_FATTURA_ELETTRONICA', 'fatt-24-inv-sezionale-fattura-elettronica');
define('FATT_24_INV_ENABLE_PDF_DOWNLOAD',    'fatt-24-inv-enable-pdf-download');
define('FATT_24_BOLLO_VIRTUALE_FE',     'fatt-24-bollo-virtuale-fe');

// stato fattura e PDF
define('FATT_24_INVSTA_NONE', 0);
define('INVSTA_STORED_FAILED', 2);
define('FATT_24_INVSTA_PDF_AVAIL_LOCAL', 3);
define('FATT_24_INVSTA_PDF_AVAIL_SERVER', 4);

// doc
define('FATT_24_DOCS_FOLDER',               'fattura24/pdf');
define('FATT_24_DOC_PDF_FILENAME',          'fatt-24-doc-pdf-filename');
define('FATT_24_DOC_STORE_FILE',            'fatt-24-doc-store-file');
define('FATT_24_DOC_FOOTNOTES',             'fatt-24-doc-footnotes');
define('FATT_24_DOC_PRODUCT_CODE',          'fatt-24-doc-product_code');
define('FATT_24_DOC_ADDRESS',               'fatt-24-doc-address');

// custom
define('FATT_24_OPTIONAL_SETTINGS',           'fatt-24-opt-sect-id');


// log
define('FATT_24_ADVANCED',           'fatt-24-adv-sect-id');
define('FATT_24_ADD_PROD_VAR',         'fatt-24-add-product-variation');
define('FATT_24_LOG_ENABLE',            'fatt-24-log-enable');
define('FATT_24_LOG_DOWNLOAD',          'fatt-24-log-download');

// tipi di documento
define('FATT_24_DT_FATTURA',    'I');
define('FATT_24_DT_FATTURA_ELETTRONICA', 'FE');
define('FATT_24_DT_RICEVUTA',   'R');
define('FATT_24_DT_ORDINE',     'C');
define('FATT_24_DT_FATTURA_FORCED', 'I-force');

// nonces per sicurezza chiamate Ajax
define('FATT_24_API_VERIF_NONCE',       '@ fattura24 @ API verification');
define('FATT_24_ORDER_ACTIONS_NONCE',   '@ fattura24 @ order actions');
