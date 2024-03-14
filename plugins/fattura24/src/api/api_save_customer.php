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

require_once FATT_24_CODE_ROOT . 'api/api_wrapper.php';

// salva contatto in rubrica f24
function fatt_24_SaveCustomer($order_id)
{
    $order = new \WC_Order($order_id);
    //fatt_24_trace('fatt_24_SaveCustomer', $order);
    $xml = new \XMLWriter();
    if (!$xml->openMemory()) {
        throw new \Exception(__('Cannot openMemory', 'fattura24'));
    }
    $xml->startDocument('1.0', 'UTF-8');
    $xml->setIndent(2);
    $xml->startElement('Fattura24');
    $xml->startElement('Document');
    $user = $order->get_user();
    $billing_address = $order->get_formatted_billing_address();
    $shipping_address = $order->get_formatted_shipping_address();
    $Email = $order->get_billing_email();
    if (!$Email) {
        if ($user) {
            $Email = $user->user_email;
        }
    }

    $CellPhone = $order->get_billing_phone();
    $Address    = apply_filters(FATT_24_DOC_ADDRESS, $order);
    $BillingCountry = fatt_24_make_strings(array($order->get_billing_country()), array());
    $Postcode   = fatt_24_make_strings(
        array($order->get_billing_postcode()),
        array($order->get_shipping_postcode())
    );
    $City       = fatt_24_make_strings(
        array($order->get_billing_city()),
        array($order->get_shipping_city())
    );
    $Province   = $BillingCountry == 'IT' ? fatt_24_make_strings(
        array($order->get_billing_state()),
        array($order->get_shipping_state())
    ) : '';

    $Country    = WC()->countries->countries[fatt_24_make_strings(array($order->get_billing_country()), array($order->get_shipping_country()))];

    $f24BillingFields = fatt_24_get_billing_fields($order);
    $FiscalCode = $f24BillingFields['CodFisc'];
    $VatCode = $f24BillingFields['PartIva'];
    $Recipientcode = $f24BillingFields['SdiCode'];
    $Pecaddress = $f24BillingFields['PecEmail'];
    $Name   = fatt_24_make_strings(
        array($order->get_billing_company()),
        array($order->get_shipping_company())
    );
    if (empty($Name)) {
        $Name   = fatt_24_make_strings(
            array($order->get_billing_first_name(),
                                              $order->get_billing_last_name()),
            array($order->get_shipping_first_name(), $order->get_shipping_last_name())
        );
    }

    // dati contatto
    $field = function ($v, $max) {
        return substr($v, 0, $max);
    };

    $customerData = array(
        'Name'      => $Name,
        'Address'   => $field($Address, FATT_24_API_FIELD_MAX_indirizzo),
        'Postcode'  => $field($Postcode, FATT_24_API_FIELD_MAX_cap),
        'City'      => $field($City, FATT_24_API_FIELD_MAX_citta),
        'Province'  => $field($Province, FATT_24_API_FIELD_MAX_provincia),
        'Country'   => $field($Country, FATT_24_API_FIELD_MAX_paese),
        'CellPhone' => $CellPhone,
        'FiscalCode'=> $FiscalCode,
        'VatCode'   => $VatCode,
        'Email'     => $Email,
        'FeDestinationCode' => $Recipientcode,
        'PEC' => $Pecaddress,
    );
    $customerData = apply_filters(FATT_24_CUSTOMER_USER_DATA, $customerData);

    foreach ($customerData as $k => $v) {
        if ($k!=="Recipientcode" && $k!=="PEC") {
            $xml->writeElement('Customer' . $k, $v);
        }
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
        'Name'      => $DeliveryName,
        'Address'   => $DeliveryAddress,
        'Postcode'  => $DeliveryPostcode,
        'City'      => $DeliveryCity,
        'Province'  => $DeliveryProvince,
        'Country'   => $DeliveryCountry,
    );

    foreach ($customerDeliveryData as $k => $v) {
        $xml->writeElement('Delivery'.$k, $v);
    }

    $xml->endElement(); // tag xml Document
    $xml->endElement(); // tag xml Fattura24
    $xml->endDocument();

    // salvo il contatto solo se non creo alcun tipo di documento
    if (!isset($docType)) {
        $res = fatt_24_api_call('SaveCustomer', array('xml' => $xml->outputMemory(true)), FATT_24_API_SOURCE);
        $ans = simplexml_load_string($res);
        if (is_object($ans)) {
            fatt_24_order_status_set_doc_data($status, (int)$ans->returnCode, (string)$ans->description, (int)$ans->docId, DT_ANAG, '');
            $rc = true;
        } else {
            fatt_24_order_status_set_error($status, sprintf(__('Unknown error occurred while uploading order %d', 'fattura24'), $order_id));
            $rc = false;
        }
        fatt_24_store_order_status($order, $status);
        return $rc;
    }
}
