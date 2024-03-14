<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * File di gestione delle chiamate API
 * conversione dei dati dell'ordine in formato xml.
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

$filesToInclude = [
    'tax.php',
    'uty.php',
    'constants.php',
    'messages.php',
    'methods/met_get_file.php',
    'methods/met_save_document.php',
    'methods/met_get_templates.php',
    'methods/met_get_numerators.php',
    'methods/met_get_pdc.php',
    'methods/met_tax.php',
    'api/api_save_document.php',
];

foreach ($filesToInclude as $file) {
    require_once FATT_24_CODE_ROOT . $file;
}

// Questa funzione estrae i dati dall'ordine woocommerce e li inserisce nell'xml per le api.
function fatt_24_order_to_XML($order, $docType)
{
    $invType = fatt_24_get_invoice_doctype();
    $order_id = $order->get_id();
    $order_data = $order->get_data();
    $currency = $order->get_currency();
    $order_number = trim(str_replace('#', '', $order->get_order_number()));
    $datamodifica = fatt_24_now();
    $user = $order->get_user();
    //$weight_unit = fatt_24_get_store_measurements()['weight_unit'];
    $user_id = $order->get_user_id(); // serve l'user_id per gestire correttamente meglio i dati fiscali dell'utente in caso di ordine inserito da admin (9.01.2020)

    $fatt_24_error = false;
    $fatt_24_err_msg = '';
    
    /**
     * Codice per permettere di intervenire su che documento deve essere generato per un determinato ordine.
     * Avvio il salvataggio dell'output in un buffer con ob_start()
     * ob_get_clean() chiude ed elimina il buffer, salvando il valore del buffer in una stringa
     * Intervengo solo se il buffer ritorna qualcosa (!empty) ed il valore ritornato è uguale almeno ad un valore accettato da docType
     * Reference: https://wordpress.org/support/topic/how-to-get-data-from-a-do_action/ & https://wordpress.stackexchange.com/questions/327652/check-if-do-action-in-wordpress-returns-any-result
    */
    ob_start();
    do_action(__NAMESPACE__.'\fatt_24_order_to_XML_doc_type_hook', $order);
    $fatt_24_order_to_XML_doc_type_hook = ob_get_clean();
    // Se il buffer non è vuoto significa che è stata eseguita l'azione 'fatt_24_order_to_XML_document_type_hook' da qualche add_action che si è agganciato ed ha generato un output
    if (!empty($fatt_24_order_to_XML_doc_type_hook) && in_array($fatt_24_order_to_XML_doc_type_hook, array(FATT_24_DT_FATTURA_ELETTRONICA, FATT_24_DT_FATTURA, FATT_24_DT_FATTURA_FORCED, FATT_24_DT_RICEVUTA, FATT_24_DT_ORDINE))) {
        $docType = $fatt_24_order_to_XML_doc_type_hook;
        // Se la stringa ritornata dal buffer è diversa da FATT_24_DT_FATTURA_ELETTRONICA setto $fattEl a false, così non viene assegnato il nome/ragione sociale alla partita iva
        if ($docType != FATT_24_DT_FATTURA_ELETTRONICA) {
            $fattEl = false;
        }
    }

    $billing_address = $order->get_formatted_billing_address();
    $shipping_address = $order->get_formatted_shipping_address();
    $Email = $order->get_billing_email();
    if (!$Email) {
        if ($user) {
            $Email = $user->user_email;
        }
    }

    $CellPhone = $order->get_billing_phone();
    $Address = apply_filters(FATT_24_DOC_ADDRESS, $order);
    $BillingCountry = $order->get_billing_country(); // aggiunto indirizzo di fatturazione per controllo campi SDI e PEC (14.02.2019)
    $Postcode = fatt_24_make_strings(
        array($order->get_billing_postcode()),
        array($order->get_shipping_postcode())
    );
    $City = fatt_24_make_strings(
        array($order->get_billing_city()),
        array($order->get_shipping_city())
    );
    $Province = $BillingCountry == 'IT' ? fatt_24_make_strings(
        array($order->get_billing_state()),
        array($order->get_shipping_state())
    ) : '';
    $Country = WC()->countries->countries[fatt_24_make_strings(
        array($order->get_billing_country()),
        array($order->get_shipping_country())
    )];


    $f24BillingFields = fatt_24_get_billing_fields($order);
    $FiscalCode = $BillingCountry == 'IT' ? $f24BillingFields['CodFisc'] : '';
    $VatCode = fatt_24_clean_vat_number($BillingCountry, $f24BillingFields['PartIva']);

    $showCheckbox = fatt_24_show_billing_checkbox();
    if ($showCheckbox) {
        $cb_value = $order->get_meta('_billing_checkbox', true);
        $order->update_meta_data('_billing_checkbox', $cb_value);
    }
    /**
    * Controllo Codice SDI: se $BillingCountry != 'IT' => XXXXXXX
    * Se vuoto e $BillingCountry == 'IT' => 0000000
    * Altrimenti si prende l'input - 06.03.2019
    */
    $Recipientcode = $f24BillingFields['SdiCode'];
    if ($BillingCountry != 'IT') {
        $Recipientcode = 'XXXXXXX';
    } elseif (empty($Recipientcode)) {
        $Recipientcode = '0000000';
    }

    $Pecaddress = $f24BillingFields['PecEmail'];
    //Mi interessa solo il nome di fatturazione; passo una stringa vuota come secondo argomento perchÃ© la funzione chiede due parametri
    $Name = fatt_24_make_strings(array($order->get_billing_company()), ''); 

    if (empty($Name)) {
        $Name = fatt_24_make_strings(
            array($order->get_billing_first_name(),
                                           $order->get_billing_last_name()),
            array($order->get_shipping_first_name(), $order->get_shipping_last_name())
        );
    }

    if (!class_exists('XMLWriter')) {
        fatt_24_trace('Non posso andare avanti! Non è installata XMLWriter!');
        return $order;
    }
    /**
     * Forzo la compilazione del campo P. IVA anche se vuoto
     * solo se il tipo di documento che devo creare è FE
     * edit del 10.11.2021 coerente con la nuova select
     * che consente di scegliere il tipo di documento in base al paese
     * di fatturazione
     */
    
    $xml = new \XMLWriter();
    if (!$xml->openMemory()) {
        throw new \Exception(__('Cannot openMemory', 'fattura24'));
    }

    $xml->startDocument('1.0', 'UTF-8');
    $xml->setIndent(2);
    $xml->startElement('Fattura24');
    $xml->startElement('Document');
    $xml->writeElement('Currency', $currency);

    $customerData = array(
        'Name' => $Name,
        'Address' => fatt_24_field($Address, FATT_24_API_FIELD_MAX_indirizzo),
        'Postcode' => fatt_24_field($Postcode, FATT_24_API_FIELD_MAX_cap),
        'City' => fatt_24_field($City, FATT_24_API_FIELD_MAX_citta),
        'Province' => fatt_24_field($Province, FATT_24_API_FIELD_MAX_provincia),
        'Country' => $order->get_billing_country(), // passa il valore del paese di fatturazione Davide Iandoli 14.02.2019
        'CellPhone' => $CellPhone,
        'FiscalCode' => $FiscalCode,
        'VatCode' => $VatCode,
        'Email' => $Email,
    );

    $customerData = apply_filters(FATT_24_CUSTOMER_USER_DATA, $customerData);
    //$customerData['Name'] = ''; //error scenario

    if (empty($customerData['Name'])) {
        $err_message = __('Error : customer name should not be empty!', 'fattura24');
        fatt_24_trace($err_message);
        $fatt_24_error = true;
        $fatt_24_err_msg = '<ErrorMsg>' . $err_message . '</ErrorMsg>';
    }

    $DeliveryName = $order->get_shipping_company();
    if (empty($DeliveryName)) {
        $DeliveryName = trim($order->get_shipping_first_name() . " " . $order->get_shipping_last_name());
    }
    $DeliveryAddress = trim(trim($order->get_shipping_address_1()) . " " . trim($order->get_shipping_address_2()));
    $DeliveryCountry = $order->get_shipping_country();
    $DeliveryPostcode = $order->get_shipping_postcode();
    $DeliveryCity = $order->get_shipping_city();
    $DeliveryProvince = $DeliveryCountry == 'IT' ? $order->get_shipping_state() : '';


    $customerDeliveryData = array(
        'Name' => $DeliveryName,
        'Address' => $DeliveryAddress,
        'Postcode' => $DeliveryPostcode,
        'City' => $DeliveryCity,
        'Province' => $DeliveryProvince,
        'Country' => $DeliveryCountry,
    );

    if (!empty($Pecaddress)) {
        $xml->writeElement('FeCustomerPec', $Pecaddress);
    }

    $xml->writeElement('FeDestinationCode', $Recipientcode);

    $DocumentType = '';

    if ($docType == FATT_24_DT_ORDINE) {
        $DocumentType = FATT_24_DT_ORDINE;
        $SendEmail = fatt_24_get_flag_lit(FATT_24_ORD_SEND);

        if (empty($customerDeliveryData['Address'])) {
            $template = get_option(FATT_24_ORD_TEMPLATE);
        } else {
            $template = get_option(FATT_24_ORD_TEMPLATE_DEST);
        }
    } else {
        $fatt_24_inv_object = sanitize_text_field(get_option("fatt-24-inv-object")); // Causale personalizzata dal campo opzioni
        $fatt_24_send_object = str_replace("(N)", $order_number, $fatt_24_inv_object); // Sostituisco (N) con il numero d'ordine
        $xml->startElement('Object');
        $xml->writeCdata($fatt_24_send_object);
        $xml->endElement();
        //$xml->writeElement('Object', $fatt_24_send_object);
        $fatt_24_paid_status = (int) get_option("fatt-24-inv-create-when-paid"); // qui mi prendo il valore del menu a tendina per lo stato "Pagato"
        $fatt_24_set_paid_status = $fatt_24_paid_status == 1 ? 'true' : 'false'; // variabile inizializzata a true o false in base alla scelta ("Sempre" => true);
        $customerRequiredInvoice = $order->get_meta('_billing_checkbox', true) == 1;

        if ($invType == FATT_24_DT_FATTURA || $invType == FATT_24_DT_RICEVUTA) {
            if ($invType == FATT_24_DT_RICEVUTA) {
                $DocumentType = FATT_24_DT_RICEVUTA;
            } elseif (fatt_24_get_flag_lit(FATT_24_INV_DISABLE_RECEIPTS) == "true" || $customerRequiredInvoice) {
                $DocumentType = FATT_24_DT_FATTURA_FORCED;
            } else {
                $DocumentType = $VatCode ? FATT_24_DT_FATTURA : FATT_24_DT_RICEVUTA;
            }
        } elseif ($invType == FATT_24_DT_FATTURA_ELETTRONICA) {

            // in ogni caso la casella di controllo dà il risultato definitivo sul tipo di documento
            if (fatt_24_get_flag_lit(FATT_24_INV_DISABLE_RECEIPTS) == "true" || $customerRequiredInvoice) {
                $DocumentType = FATT_24_DT_FATTURA_ELETTRONICA;
            } else {
                $DocumentType = fatt_24_get_resulting_doc_type($BillingCountry, $VatCode);
            }

            $forceVatCode = empty($VatCode) && $DocumentType == 'FE' && $BillingCountry !== 'IT';
            if ($forceVatCode) {
                $customerData['VatCode'] = fatt_24_strip_illegal_chars($Name);
            }
        }

        if ($DocumentType == FATT_24_DT_RICEVUTA) {
            $numerator = get_option(FATT_24_INV_SEZIONALE_RICEVUTA);
        } else if ($DocumentType == FATT_24_DT_FATTURA_ELETTRONICA) {
            $numerator = get_option(FATT_24_INV_SEZIONALE_FATTURA_ELETTRONICA);
        } else {
            $numerator = get_option(FATT_24_INV_SEZIONALE_FATTURA);
        }

        if ($numerator !== 'Predefinito') {
            $xml->writeElement('IdNumerator', $numerator);
        }
        $SendEmail = fatt_24_get_flag_lit(FATT_24_INV_SEND);


        if (empty($customerDeliveryData['Address'])) {
            $template = get_option(FATT_24_INV_TEMPLATE);
        } else {
            $template = get_option(FATT_24_INV_TEMPLATE_DEST);
        }

        $docIdOrderF24 = fatt_24_is_recorded_on_f24_postmeta($order, FATT_24_DT_ORDINE);
        $f24OrderId = $order->get_meta('fatt24_order_docId', true);

        if ($docIdOrderF24 || $f24OrderId) {
            // forzo il tipo stringa; altrimenti non viene aggiunto il tag 'F24OrderId'
            // cfr: https://www.php.net/manual/en/xmlwriter.writeelement.php
            if (empty($f24OrderId)) {
                $f24OrderId = !isset($docIdOrderF24['docId']) ? '' : (string) $docIdOrderF24['docId'];
            }
            $xml->writeElement('F24OrderId', $f24OrderId);
        };
    }

    $xml->writeElement('DocumentType', $DocumentType);

    // per la FE l'invio della copia è sempre disabilitato
    if ($DocumentType === FATT_24_DT_FATTURA_ELETTRONICA) {
        $SendEmail = 'false';
    }

    $xml->writeElement('SendEmail', $SendEmail);


    if ($template !== 'Predefinito') {
        $idTemplate = trim($template);
        $xml->writeElement('IdTemplate', $idTemplate);
    }


    foreach ($customerData as $k => $v) {
        if (!empty($v)) {
            $xml->startElement('Customer' . $k);
            $xml->writeCdata($v);
            $xml->endElement();
        }
    }

    foreach ($customerDeliveryData as $k => $v) {
        if (!empty($v)) {
            $xml->startElement('Delivery' . $k);
            $xml->writeCdata($v);
            $xml->endElement();
        }
    }

    $payment_method = (string) $order->get_payment_method();

    /**
     * Tramite $payment_method_needle gestisco i pagamenti elettronici e lo stato "pagato"
     * cercando nel metodo utilizzato la stringa descrittiva (es.: paypal) [20.01.2020]
     */
    if (strpos(strtolower($payment_method), 'paypal') !== false
        || strpos(strtolower($payment_method), 'ppcp') !== false) {// converto le stringhe in minuscole con strtolower()
        $payment_method_needle = 'paypal';
    } elseif (strpos(strtolower($payment_method), 'braintree') !== false) {
        $payment_method_needle = 'braintree';
    } elseif (strpos(strtolower($payment_method), 'stripe') !== false) {
        $payment_method_needle = 'stripe';
    } elseif (strpos(strtolower($payment_method), 'ppay') !== false) {
        $payment_method_needle = 'postepay';
    } elseif (strpos(strtolower($payment_method), 'payplug') !== false) {
        $payment_method_needle = 'payplug';
    } elseif (strpos(strtolower($payment_method), 'pay') !== false
            || strpos(strtolower($payment_method), 'wallet') !== false
            || strpos(strtolower($payment_method), 'accountfunds') !== false) { //altri pagamenti elettronici, aggiunti il 02.04.2020
        $payment_method_needle = 'pagamento smart o con carta';
    } else {
        $payment_method_needle = '';
    }
    
    $payment_method_title = $order->get_payment_method_title();

    // pagamento a mezzo bonifico
    if ($payment_method == 'bacs') {
        $PaymentMethodName = 'Bonifico'; // nome predefinito
        $fepaymentcode = 'MP05';
        $xml->writeElement('FePaymentCode', $fepaymentcode); // passo il codice solo se il doc è FE
        $bacs_accounts1 = get_option('woocommerce_bacs_accounts'); //cerca i dati in WooCommerce bacs accounts

        if (!empty($bacs_accounts1)) {
            foreach ($bacs_accounts1 as $bacs_account1) {
                $PaymentMethodName = $bacs_account1['bank_name']; // nome della banca
                $xml->writeElement('PaymentMethodDescription', $bacs_account1['iban']); // iban
            }
        }
        
        /**
        * Blocco spostato qui per impostare il tag PaymentMethodName anche nel caso i dati in Woocommerce siano vuoti
        */       

        if (!empty($payment_method_title) && empty($PaymentMethodName)) {
            $PaymentMethodName = $payment_method_title. '- '.$payment_method;
        } elseif (empty($payment_method_title)) {
            $PaymentMethodName = $payment_method;
        }
        $xml->startElement('PaymentMethodName');
        $xml->writeCdata($PaymentMethodName);
        $xml->endElement();

    // pagamento con assegno
    } elseif ($payment_method == 'cheque') {
        $PaymentMethodName = 'Assegno'; // nome predefinito
        $fepaymentcode = 'MP02';
        $xml->writeElement('FePaymentCode', $fepaymentcode);

        if (!empty($payment_method_title)) {
            $PaymentMethodName = $payment_method_title. '- '.$payment_method;
        }
        $xml->startElement('PaymentMethodName');
        $xml->writeCdata($PaymentMethodName);
        $xml->endElement();
        $xml->startElement('PaymentMethodDescription');
        $xml->writeCdata($PaymentMethodName);
        $xml->endElement();

    // pagamento in contanti
    } elseif (strpos(strtolower($payment_method), 'cod') !== false) {
        $PaymentMethodName = 'Contrassegno';
        $fepaymentcode = 'MP01';
        if (!empty($payment_method_title)) {
            $PaymentMethodName = $payment_method_title. '- '.$payment_method;
            $PaymentMethodDescription = 'Pagamento alla consegna';
        }

        $xml->writeElement('FePaymentCode', $fepaymentcode);
        $xml->startElement('PaymentMethodName');
        $xml->writeCdata($PaymentMethodName);
        $xml->endElement();

        $xml->startElement('PaymentMethodDescription');
        $xml->writeCdata($PaymentMethodDescription);
        $xml->endElement();

    // pagamenti elettronici (default) => uso la variabile $payment_method_needle
    } else {
        $fepaymentcode = 'MP08';
        if (!empty($payment_method_title)) {
            $PaymentMethodName = empty(ucfirst($payment_method_needle))? 'Pagamento con carta' : ucfirst($payment_method_needle); // voglio la prima lettera maiuscola
            $PaymentMethodDescription = $payment_method_title;
        } else {
            $PaymentMethodName = empty($payment_method) ? 'Pagamento con carta' : $payment_method;
            $PaymentMethodDescription = 'Pagamento con cart';
        }

   
        $xml->writeElement('FePaymentCode', $fepaymentcode);
        $xml->startElement('PaymentMethodName');
        $xml->writeCdata($PaymentMethodName);
        $xml->endElement();
        $xml->startElement('PaymentMethodDescription');
        $xml->writeCdata($PaymentMethodDescription);
        $xml->endElement();

    }

    if ($docType == FATT_24_DT_ORDINE) {
        $xml->writeElement('Number', $order->get_order_number());
    }

    // Estraggo i totali generali dell'ordine da woocommerce
    $order_shipping_total = $order->get_shipping_total(); //totale di spedizione
    $order_shipping_tax = $order->get_shipping_tax(); // tassa applicata alla spedizione
    $order_total_discount = $order->get_discount_total(); // totale sconto, lo sottraggo al totale e al subtotale
    $order_total_tax = $order->get_total_tax();
    $order_total = $order->get_total();
    $totals = array('total' => $order_total,
                    'total_tax' => $order_total_tax,
                    'total_discount' => $order_total_discount,
                    'total_shipping' => $order_shipping_total,
                    'total_shipping_tax' => $order_shipping_tax);
    fatt_24_trace('totali :', $totals);                

    $TotalWithoutTax = fatt_24_fixnum($order->get_total() - $order_total_tax, 2);
    $VatAmount = fatt_24_fixnum($order_total_tax, 2);
    $Total = fatt_24_fixnum($order_total, 2);

    $xml->writeElement('TotalWithoutTax', fatt_24_fixnum($TotalWithoutTax, 2));
    $xml->writeElement('VatAmount', fatt_24_fixnum($VatAmount, 2));
    $xml->writeElement('Total', fatt_24_fixnum($Total, 2));

    $fe_applyVirtualStamp = fatt_24_applyVirtualStamp($invType, $Total); // applico il bollo virtuale?

    if ($fe_applyVirtualStamp == "V") {
        $xml->writeElement('FeVirtualStamp', $fe_applyVirtualStamp);
    }

    $FootNotes = '';
    $xml->writeElement('FootNotes', $FootNotes);

    // tag riportati solo in fattura
    if ($docType != FATT_24_DT_ORDINE) {
        $xml->startElement('Payments');
        $xml->startElement('Payment');
        $xml->writeElement('Date', fatt_24_now('Y-m-d'));
        $xml->writeElement('Amount', fatt_24_fixnum($Total, 2));

        // Sovrascrivo la variabile $fatt_24_set_paid_status solo se ho scelto "Pagamenti Elettronici"
        if ($fatt_24_paid_status == 2) {
            if (!empty($payment_method_needle)) {
                $fatt_24_set_paid_status = 'true';
            } // se rientra nei pagamenti elettronici è true
            else {
                $fatt_24_set_paid_status = 'false';
            }
        }	// altrimenti è false

        $xml->writeElement('Paid', $fatt_24_set_paid_status);
        $xml->endElement(); // tag xml Payment
        $xml->endElement(); // tag xml Payments
    }


    $xml->startElement('Rows');
    //fatt_24_get_flag_lit(FATT_24_INV_CREATE);

    $pdc = get_option(FATT_24_INV_PDC);
    $idPdc = '';
    if (!empty($pdc) && $pdc != 'Nessun Pdc') {
        $idPdc = $pdc;
    }

    $fatt24_coupons = fatt_24_coupon_array($order);
    $fixedCartVatRates = [];
    $order_items_data = [];
 
    // CICLO delle LINEE-PRODOTTO: creo un array con tutti gli items del'ordine
    foreach ((array) $order->get_items() as $item_key => $item_value) {
        $productPriceIncludesTaxes = $order_data['prices_include_tax'];
        $item_data = $item_value->get_data();
        $item_meta_data = $item_value->get_meta_data();
        $product_id = $item_value->get_product_id();
        $product_cat_id = '';
        
        // cfr https://stackoverflow.com/questions/37740743/how-to-get-categories-from-an-order-at-checkout-in-woocommerce
        $terms = (array) get_the_terms($product_id, 'product_cat');
        $is_iterable = fatt_24_is_iterable($terms);

        if ($is_iterable) {
            foreach ($terms as $val) {
                $product_cat_id = $val->term_id;
            }
        }

        $product = wc_get_product($product_id);

        if ($product) {
            $productData = $product->get_data();
            $productName = fatt_24_appendToName($docType, $productData, sanitize_text_field($item_data['name']));
            $sku = $product->get_sku();
        } else {
            $productData = '';
            $productName = sanitize_text_field($item_data['name']);
            $sku = '';
        }
       
        $productUM = apply_filters('fatt_24_product_um', 'pz', $product_id);
        $product_type = $item_value->get_type();
        $variation = '';
        $add_variation_option = get_option('fatt-24-add-product-variation');
        $var_attr = '';
        //$var_attr = get_option('fatt-24-add-product-variation')== '1' ? fatt_24_get_attr_names($item_meta_data) : '';
        if ($item_data['variation_id'] !== 0) {
            // cfr.: https://wordpress.stackexchange.com/questions/97176/get-product-id-from-order-id-in-woocommerce
            // $item_data->get_variation_id();
            $variation = $item_data['variation_id'];
            $var_data = new \WC_Product_Variation($variation);
            // se è definito il codice del prodotto genitore inserisco anche quello
            $sku .= !empty($sku) ?
                    ' - '. $var_data->get_sku() :
                    $var_data->get_sku(); // aggiungo lo sku della variante
            
            // Qui decido cosa aggiungere al testo del prodotto in base all'opzione scelta        
             switch ($add_variation_option) {
                case '0':
                    $var_attr = '';
                    break;
                case '1':
                    $var_attr = fatt_24_get_attr_names($item_meta_data);
                    break;
                case '2':
                    $var_attr = implode(', ', $var_data->get_variation_attributes());
                    break;
                default:
                    $var_attr = '';
                    break;            
            }    
        }

        $wcTaxEnabled = fatt_24_wc_calc_tax_enabled();

        $usedRate = $item_data['taxes']['total'];
        $rateId = key($usedRate);
        $rate = (float)\WC_Tax::get_rate_percent($rateId);
        $rateDescription = $wcTaxEnabled ? \WC_Tax::get_rate_label($rateId) : 'IVA ' . $rate . '%';
        $ratesArray[] = ['id' => $rateId, 'rate' => $rate, 'description' => $rateDescription];

        /**
         * Qui ottengo aliquota e descrizione in caso di ordine da admin
         * Uso array filter perché possono esistere più aliquote
         * ma solo quella popolata con un valore è quella utilizzata dal prodotto
         * e la chiave dell'array è l'id della tassa
         * Se il calcolo delle tasse non è abilitato, forzo la descrizione - edit del 13.05.2021
         */
        if (count($usedRate) > 1) {
            $usedRate = array_filter($item_data['taxes']['total']);
            $rateId = key($usedRate);
            $rate = (float)\WC_Tax::get_rate_percent($rateId);
            $rateDescription = $wcTaxEnabled ? \WC_Tax::get_rate_label($rateId) : 'IVA ' . $rate . '%';
        }

        if (count($usedRate) > 1) {
            $err_message = __('Error:  multiple vat codes in one product !', 'fattura24');
            fatt_24_trace($err_message, $usedRate);
            $fatt_24_error = true;
            $fatt_24_err_msg = '<ErrorMsg>' .$err_message . '</ErrorMsg>';
        }

        // riporto la natura solo su aliquote 0%
        $productVatNatura = (int) $rate == 0 ? fatt_24_getNatureColumn($rateId) : '';
        if ((int) $rate == 0 && empty($productVatNatura) && $DocumentType === FATT_24_DT_FATTURA_ELETTRONICA) {
            $err_message = __('Error : each zero rate in electronic invoice should have a Natura code ', 'fattura24');
            fatt_24_trace($err_message);
            $fatt_24_error = true;
            $fatt_24_err_msg = '<ErrorMsg>'. $err_message . '</ErrorMsg>';
        }

        /**
        * Mi calcolo il prezzo così perché in diversi scenari
        * l'attributo può essere disabilitato (importo di una ricarica)
        * oppure specifico per una variante prodotto
        * l'operatore ternario mi serve ad evitare divisioni per zero
        */

        // non voglio divisioni per zero
        $price = $item_data['quantity'] == 0 ? 
            0 : fatt_24_fixnum($item_data['subtotal'] / $item_data['quantity'], 8);

        // fine codice modifica prezzo
        
        /* *** GESTIONE SCONTI *** */
        // Pre-imposto i valori degli sconti eventulmente presenti sulla linea
        $product_discount_val = $item_data['subtotal'] - $item_data['total'];
        $discount_percent = 0; // sconto percentuale        
        $discountPercAmount = 0; // importo (in Euro) dello sconto percentuale
        $fixedDiscountValue = 0; // sconto fisso per la linea prodotto            

        if ($product_discount_val > 0) {
            // C'è almeno uno sconto: veririco quali sconti ci sono sulla linea.
            $coupon_percent = fatt_24_percent_coupons($fatt24_coupons);
            $coupon_fixed = fatt_24_fixed_coupons($fatt24_coupons);
            $nCoupons = count($fatt24_coupons); // tutti i coupons;
            $coupon_description = 'Sconto ';
            
            // Ciclo gli sconti PERCENT (in percentuale)
            foreach ($coupon_percent as $key => $val) {
                    // casi in cui il prodotto è incluso nello sconto perc.
                    $case1 = empty($coupon_percent[$key]['product_ids']) && empty($coupon_percent[$key]['product_categories']);
                    $case2 = in_array($product_id, $coupon_percent[$key]['product_ids']);
                    $case3 = in_array($variation, $coupon_percent[$key]['product_ids']);
                    $case4 = in_array($product_cat_id, $coupon_percent[$key]['product_categories']);

                    /*  
                    * WooCommerce quando la categoria del prodotto non è nell'elenco delle categorie del coupon (sez. restrizioni), 
                    * rimuove il coupon dal carrello anche se il l'id del prodotto è nell'elenco dei prodotti a cui si applicherebbe il coupon
                    */

                    $productIncluded = $case1 || $case2 || $case3 || $case4;
                    $coupon_description .= "\n". $coupon_percent[$key]['code'];
                    $productName .= "\n". $coupon_percent[$key]['code']; // aggiungo la descrizione degli sconti a percentuale

                    if ($productIncluded) {
                        $discount_percent += (float) $coupon_percent[$key]['amount'];
                    }
                    
                    // cfr. ticket DT 16612;
                    if ((float) $item_data['subtotal'] > 0.00 && (float) $item_data['total'] == 0.00) {
                        $discount_percent = 100;
                    }
            }

            // Estraggo l'importo dello sconto fisso presente sulla riga.
            // Per farlo, uso il valore "subtotal" passato da WooCommerce (pari a prezzo unitario x quantità).
            // Decimali accettati: 8
            $discountPercAmount = (float) fatt_24_fixnum(($item_data['subtotal'] / 100 * $discount_percent), 8);
           

            if ($discount_percent > 0) {
                $fixedDiscountValue = $product_discount_val - $discountPercAmount;
            } else {
                $fixedDiscountValue = $product_discount_val;
            }
       
            /*
            * Controllo se c'è un importo residuo di sconto e nel caso in cui non sia stato già applicato in percentuale
            * alla riga prodotto devo aggiungere una riga con importo negativo (e sommare gli importi raggruppandoli per aliquota IVA)
            * Sulla base del ticket DT 18074 ora controllo solo se c'è un importo residuo, in precedenza controllavo anche se la
            * lista di coupon a importo fisso era vuota o la variabile $otherDiscountApplied è vera. Nel caso del ticket DT 18074 i due
            * controlli davano come risultato false, ed essendoci altre due variabili (con valore true) in AND il risultato finale era comunque false
            * perciò non si creava la riga prodotto con importo negativo pur essendoci un importo residuo.
            * Davide Iandoli 28.12.2023
            */

            // Con questa booleana gestisco anche gli sconti inseriti lato admin
            //$otherDiscountApplied = empty($coupon_fixed) && empty($coupon_percent) && $fixedDiscountValue > 0;
            //if ((!empty($coupon_fixed) || $otherDiscountApplied) && $fixedDiscountValue > 0 && $fixedDiscountValue > 0.1) {
            $addFixedDiscountRow = $fixedDiscountValue > 0 && $fixedDiscountValue > 0.1;  
            
            if ($addFixedDiscountRow) {
                //fatt_24_trace('entro nell\'array di coupon fissi');
                // Estraggo la descrizione dello sconto fisso
                foreach ($coupon_fixed as $key => $val) {
                    $coupon_description .= "\n" .$coupon_fixed[$key]['code'];
                }

                /* Inserisco i dati nell'array delle aliquote IVA, sommando l'importo della linea sconto solo se ho la stessa aliquota */
                if (!array_key_exists((int) $rate, $fixedCartVatRates)) {
                    $fixedCartVatRates[$rate] = array('vat_description' => $rateDescription,
                                         'natura' => $productVatNatura,
                                         'amount' => $fixedDiscountValue,
                                         'coupon_description' => $coupon_description);
                } else {
                    $fixedCartVatRates[$rate]['amount'] += $fixedDiscountValue;
                }
            }
            fatt_24_trace('valore di fixed cart vat rates :', $fixedCartVatRates);
        }

        /* In questo array ho tutti i dati che mi servono, sotto la chiave data raggruppo item_data */
        $order_items_data[] = array('product_id' => $product_id,
                                    'sku' => $sku,
                                    'name' => $productName,
                                    'product_type' => $product_type,
                                    'product_um' => $productUM,
                                    'product_cat_id' => $product_cat_id,
                                    'variation' => $variation,
                                    'variation_attr' => $var_attr,
                                    'prices_include_tax' => $productPriceIncludesTaxes,
                                    'price' => $price,
                                    'discount' => $discount_percent,
                                    'tax_rate' => $rate,
                                    'tax_description' => $rateDescription,
                                    'tax_id' => $rateId,
                                    'natura' => $productVatNatura,
                                    'data' => $item_data);
    }

    fatt_24_trace('order items data 1 :', $order_items_data);

    /* 
    *  Aggiungo all'XML le RIGHE-PRODOTTO.
    *  Se c'è uno sconto percentuale, popolo il tag Discounts con il valore corretto.
    *  Nelle righe prodotto lo sconto percentuale può essere zero
    */
    fatt_24_ProductRows($xml, $order_items_data, $idPdc); 

    /* Se ho uno o più sconti FIXED a importo fisso, riempio il relativo array, raggruppando gli sconti fissi per aliquota IVA */
    if (!empty($fixedCartVatRates)) {
        fatt_24_addFixedDiscountRows($xml, $order_items_data, $idPdc, $fixedCartVatRates);
    }

    // Popolo l'array codici natura in 'Configurazione tassa'
    $zero_shipping_tax_natura = fatt_24_get_zero_shipping_tax_natura();
    fatt_24_trace('zero shipping natura :', $zero_shipping_tax_natura);

    // Dati di spedizione
    foreach ((array) $order->get_items('shipping') as $item_id => $shipping_item_obj) {
        $shipping_item_data = $shipping_item_obj->get_data();
        fatt_24_trace('Dati di spedizione :', $shipping_item_data); // cosa c'è nell'oggetto?

        $shipping_data_name         = $shipping_item_data['name'];
        $shipping_data_method_title = $shipping_item_data['method_title'];
        $shipping_data_method_id    = $shipping_item_data['method_id'];
        $shipping_data_instance_id  = $shipping_item_data['instance_id'];
        $shipping_data_total        = $shipping_item_data['total'];
        $shipping_data_total_tax    = $shipping_item_data['total_tax'];
        $shipping_data_taxes        = $shipping_item_data['taxes'];
        $shipping_data_taxes_total  = $shipping_data_taxes['total'];

        $wcTaxEnabled = fatt_24_wc_calc_tax_enabled(); // calcolo tasse Woo abilitato ?
        $freeShipping = $shipping_data_method_id == 'free_shipping' || empty($shipping_data_method_id); // spedizione gratuita ?
        $shipping_total_tax_is_zero = (float) $shipping_data_total_tax == 0.00; // total tax spedizione => 0 ?
        $shippingRateId = key($shipping_data_taxes_total);
        $shipping_vatNature = '';

        /** 
         * Uso come chiave $shippingRateId, quindi se è valorizzato in qualche modo 
         * non devo fare altri controlli
         */

        if (count($shipping_data_taxes_total) > 1) {
            $shipping_data_taxes_total = array_filter($shipping_data_taxes['total']);
            $shippingRateId = key($shipping_data_taxes_total);
        }

        if (count($shipping_data_taxes_total) > 1) {
            $err_message = __('Error:  multiple vat codes in shipping :', 'fattura24'); 
            fatt_24_trace($err_message, $shipping_data_taxes_total);
            $fatt_24_error = true;
            $fatt_24_err_msg = '<ErrorMsg>' .$err_message . '</ErrorMsg>';
        }
        
        /* Caso 1 : il calcolo delle tasse in WooCommerce è "NON abilitato" */
        if (!$wcTaxEnabled && !$shippingRateId) {
            if ($zero_shipping_tax_natura) {
                $shippingRateId = $zero_shipping_tax_natura->tax_id;
                $shipping_vatNature = $zero_shipping_tax_natura->tax_code;
            } else if (!isset($shippingRateId) && 
                       !$zero_shipping_tax_natura && 
                       $DocumentType === FATT_24_DT_FATTURA_ELETTRONICA) 
            {
                // tassa zero, FE e niente Natura => blocco creazione doc. con errore evidenziato
                $err_message = __('Error : each zero rate in electronic invoice should have a Natura code ', 'fattura24');
                fatt_24_trace($err_message);
                $fatt_24_error = true;
                $fatt_24_err_msg = '<ErrorMsg>' .$err_message  .'</ErrorMsg>';
            }   
        }

        /* Caso 2 : total tax uguale a zero e metodo di spedizione !== 'spedizione gratuita' */
        if ($shipping_total_tax_is_zero && !$freeShipping && !$shippingRateId) {
            if ($zero_shipping_tax_natura) {
                $shippingRateId = $zero_shipping_tax_natura->tax_id;
                $shipping_vatNature = $zero_shipping_tax_natura->tax_code;
            } else {
                $shippingVat = max(array_column($ratesArray, 'rate'));
                $shippingRateKey = array_search($shippingVat, array_column($ratesArray, 'rate'));
                $shippingRateId = $ratesArray[$shippingRateKey]['id'];
            }    
        }

        /*
        * Caso 3: spedizione gratuita e calcolo tasse abilitato (in questo caso, WooCommerce passa la tassa allo 0%).
        * Regolo il caso in cui nel pannello di amministrazione non ci siano configurate tasse allo 0% (perciò non può esserci nemmeno una natura di esenzione configurata): 
        * - in questo caso, se $zero_shipping_tax_natura è vuoto, utilizzo per la spedizione gratuita (a zero Euro) l'aliquota più alta tra quelle usate nell'ordine.
        */
        if ($freeShipping && $wcTaxEnabled && !$shippingRateId) {
            if ($zero_shipping_tax_natura) {
                $shippingRateId = $zero_shipping_tax_natura->tax_id;
                $shipping_vatNature = $zero_shipping_tax_natura->tax_code;
            } else {
                $shippingVat = max(array_column($ratesArray, 'rate'));
                $shippingRateKey = array_search($shippingVat, array_column($ratesArray, 'rate'));
                $shippingRateId = $ratesArray[$shippingRateKey]['id'];
            }
        }        

        /* Controlli terminati: definisco rate e description sulla base dell'id */
        $shippingVat = $shippingRateId ? (float) \WC_Tax::get_rate_percent($shippingRateId): (float) 0; 
        $shippingDescription = $shippingRateId ? \WC_Tax::get_rate_label($shippingRateId) : 'IVA ' . $shippingVat . '%';
        if ($shippingRateId && $zero_shipping_tax_natura) {
            $shipping_vatNature = $zero_shipping_tax_natura->tax_code;
        }

        fatt_24_trace('shipping rate and natura :', ['rate_id' => $shippingRateId, 'rate' => $shippingVat, 'natura' => $shipping_vatNature]);
        // Se si verifica questa situazione (condizioni: aliquota zero, niente natura e tipo di doc => FE), mando in errore. 
        if (!$shippingRateId && 
            (int) $shippingVat == 0 && 
            empty($shipping_vatNature) && 
            $DocumentType === FATT_24_DT_FATTURA_ELETTRONICA) 
        {
            $err_message = __('Error : each zero rate in electronic invoice should have a Natura code ', 'fattura24');
            fatt_24_trace($err_message);
            $fatt_24_error = true;
            $fatt_24_err_msg = '<ErrorMsg>' .$err_message  .'</ErrorMsg>';
        }

        $idPdcShipping = get_option('fatt-24-inv-pdc-shipping');

        // Creo i tag xml per i dati di spedizione
        $xml->startElement('Row');
        $xml->startElement('Description');
        $xml->writeCdata($shipping_data_name);
        $xml->endElement();
        $xml->writeElement('Qty', 1);
        $xml->writeElement('Price', $shipping_data_total);
        $xml->writeElement('VatCode', $shippingVat);
        $xml->startElement('VatDescription');
        $xml->writeCdata($shippingDescription);
        $xml->endElement();
        //$xml->writeElement('VatDescription', $shippingDescription);
        if ((int)$shippingVat == 0) { // con aliquota a zero riporta la natura
            $xml->writeElement('FeVatNature', $shipping_vatNature);
        }

        if (!empty($idPdcShipping)) {
            $xml->writeElement('IdPdc', $idPdcShipping);
        }
        $xml->endElement();
    }

    // Gestione delle commissioni - qui aggiungo le righe all'xml
    foreach ((array) $order->get_items('fee') as $fee_id => $fee_item) {
        $fee_item_data = $fee_item->get_data();
        fatt_24_trace(' fee item data :', $fee_item_data); // sempre meglio sapere cosa contiene l'oggetto

        $fee_item_name = $fee_item_data['name'];
        $fee_item_total = $fee_item_data['total'];
        $fee_item_tax_zero_rate = $fee_item_data['total_tax'] == 0; // restituisce true o false

        // gestione aliquote per le commissioni
        if (fatt_24_wc_calc_tax_enabled()) {
            $usedFeeRate = $fee_item_data['taxes']['total'];
            $feeRateId = key($usedFeeRate);
            $fee_vat = (float) \WC_Tax::get_rate_percent($feeRateId);
            $fee_vatDescription = \WC_Tax::get_rate_label($feeRateId);
        }

        // gestione della Natura per le aliquote
        if ($fee_item_tax_zero_rate && !isset($fee_vat)) {
            $fee_vat = 0;
            if (!isset($fee_vatDescription)) {
                $fee_vatDescription = 'IVA ' . $fee_vat . '%';
            }
        }

        $fee_vatNature = fatt_24_getNatureColumn($tax_data['rate_id']);

        if ((int) $fee_vat == 0 && empty($fee_vatNature) && $DocumentType === FATT_24_DT_FATTURA_ELETTRONICA) {
            $err_message = __('Error : each zero rate in electronic invoice should have a Natura code ', 'fattura24');
            fatt_24_trace($err_message);
            $fatt_24_error = true;
            $fatt_24_err_msg = '<ErrorMsg>' .$err_message . '</ErrorMsg>';
        }
        
        $idPdcFees = get_option('fatt-24-inv-pdc-fees');

        // tag xml per le commissioni
        $xml->startElement('Row');
        $xml->startElement('Description');
        $xml->writeCdata($fee_item_name);
        $xml->endElement();
        $xml->writeElement('Qty', 1);
        $xml->writeElement('Price', $fee_item_total);
        $xml->writeElement('VatCode', $fee_vat);
        $xml->startElement('VatDescription');
        $xml->writeCdata($fee_vatDescription);
        $xml->endElement();

        if ((int)$fee_vat == 0) { // con aliquota a zero riporta la natura
            $xml->writeElement('FeVatNature', $fee_vatNature);
        }

        if (!empty($idPdcFees)) {
            $xml->writeElement('IdPdc', $idPdcFees);
        }

        $xml->endElement();
    }

    $xml->endElement(); // tag xml Rows
    $xml->endElement(); // tag xml Document
    $xml->endElement(); // tag xml Fattura24
    $xml->endDocument();

    return array(
                 'fatt_24_error' => ['error' => $fatt_24_error, 'message' => $fatt_24_err_msg],
                 'xml' => $xml->outputMemory(true)
                );
}

/* Funzione che si occupa di creare le righe prodotto del documento: il tag Discounts è popolato con il valore che ho creato e memorizzato prima */
function fatt_24_ProductRows($xml, $order_items_data, $idPdc)
{
   
    foreach ($order_items_data as $item_key => $item_value) {
        $item_name = $order_items_data[$item_key]['name'];
        $var_attr = $order_items_data[$item_key]['variation_attr'];
    
        if (!empty($var_attr)) {
            $needle = strpos($item_name, '- '.$var_attr);
            $new_name = $needle ? substr($order_items_data[$item_key]['name'], 0, $needle) : $order_items_data[$item_key]['name'];
            $item_name = $new_name . ': ' . $var_attr;
        } else {
            $item_name = $order_items_data[$item_key]['name'];
        }

        //$item_name = $order_items_data[$item_key]['data']['name'];
        $sku = $order_items_data[$item_key]['sku'];
        $productUM = $order_items_data[$item_key]['product_um'];
        $qty = $order_items_data[$item_key]['data']['quantity'];
        $price = $order_items_data[$item_key]['price'];
        $discountValue = $order_items_data[$item_key]['discount'];

        $taxId = $order_items_data[$item_key]['tax_id'];
        $vatRate = $order_items_data[$item_key]['tax_rate'];
        $vatDescription = $order_items_data[$item_key]['tax_description'];
        $vatNature = $order_items_data[$item_key]['natura'];
        //fatt_24_trace('rate id :', $taxId);
        //fatt_24_trace('vat rate :',$vatRate);

        $xml->startElement('Row');
        $xml->startElement('Description');
        $xml->writeCdata($item_name);
        $xml->endElement();
        //$xml->writeElement('Description', $item_name);
        $xml->writeElement('Code', $sku);
        $xml->writeElement('Qty', $qty);
        $xml->writeElement('Um', $productUM);
        $xml->writeElement('Price', $price);
        $xml->writeElement('Discounts', $discountValue);
        $xml->writeElement('VatCode', $vatRate);
        $xml->startElement('VatDescription');
        $xml->writeCdata($vatDescription);
        $xml->endElement();
        //$xml->writeElement('VatDescription', $vatDescription);
        if ((int)$vatRate == 0) { // con aliquota a zero riporta la natura
            $xml->writeElement('FeVatNature', $vatNature);
        }

        if (!empty($idPdc)) {
            $xml->writeElement('IdPdc', $idPdc);
        }
        $xml->endElement(); // Row	*/
    }
}

/* In caso di presenza di sconti a importo fisso, aggiungo righe-sconto (in negativo, con il segno meno) raggruppandole per aliquota IVA: per farlo, uso l'array creato man mano all'interno del ciclo-righe.  */
function fatt_24_addFixedDiscountRows($xml, $order_items_data, $idPdc, $fixedCartVatRates)
{
    foreach ($fixedCartVatRates as $key => $val) {

        $codeRowDescription = $fixedCartVatRates[$key]['coupon_description'];
        $codeVatRate = $key;
        $codeVatDescription = $fixedCartVatRates[$key]['vat_description'];
        $codeVatNatura = $fixedCartVatRates[$key]['natura'];
        $codeDiscountAmount = $fixedCartVatRates[$key]['amount'];

        $xml->startElement('Row');
        $xml->startElement('Description');
        $xml->writeCdata($codeRowDescription);
        $xml->endElement();
        //$xml->writeElement('Description', $codeRowDescription);
        $xml->writeElement('Qty', '1');
        $xml->writeElement('Price', -$codeDiscountAmount);
        $xml->writeElement('VatCode', $codeVatRate);
        $xml->startElement('VatDescription');
        $xml->writeCdata($codeVatDescription);
        $xml->endElement();
        //$xml->writeElement('VatDescription', $codeVatDescription);

        if ((int)$codeVatRate == 0) { // con aliquota a zero riporta la natura
            $xml->writeElement('FeVatNature', $codeVatNatura);
        }

        if (!empty($idPdc)) {
            $xml->writeElement('IdPdc', $idPdc);
        }
        $xml->endElement(); // Row
    }
}
