<?php

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

$filesToInclude = [
    'api/api_wrapper.php',
    'settings_uty.php'
];

foreach ($filesToInclude as $file) {
    require_once FATT_24_CODE_ROOT . $file;
}

//restituisce il valore di una checkbox
function fatt_24_get_flag($key)
{
    $val = get_option($key, false);
    $ret = $val ? true : false;
    return $ret;
}

// restituisce il valore di una checkbox (con procedimento literal)
function fatt_24_get_flag_lit($key)
{
    return fatt_24_get_flag($key) ? 'true' : 'false';
}

/**
* Con queste funzioni calcolo l'aliquota dal prezzo e dall'importo dell'IVA
* utilizzate solo se mi arrivano dati non congruenti (es: $vat = 0 e $total_tax > 0)
* Davide Iandoli 19.11.2019
*/

function fatt_24_vat_from_price_and_tax($PriceWithout, $VatPaid)
{
    $Total = $PriceWithout + $VatPaid;
    if ($PriceWithout != 0) {
        $Perc = ($Total - $PriceWithout) * 100.0 / $PriceWithout;
        return round($Perc, 2);
    }
    return 0;
}

function fatt_24_price_without_vat($Price, $VatPerc)
{
    $X = 100.0 / $VatPerc;
    $Y = $X + 1;
    $Parz = $Price / $Y;
    return $Price - $Parz;
}

// toglie spazi in eccesso
function fatt_24_make_strings($v1, $v2)
{
    $s = trim(implode(' ', $v1));
    if ($s == '' && !empty($v2)) {
        $s = trim(implode(' ', $v2));
    }
    return $s;
}

// arrotondamenti
function fatt_24_fixnum($n, $p)
{
    $n = round($n, $p);
    return (float) number_format($n, $p, '.', ''); // cambio metodo di arrotondamento senza separatore migliaia
};

// verifica contenuto field
function fatt_24_field($v, $max)
{
    return substr($v, 0, $max);
};
/**
 * Methods by which I append some text to product name in F24 order or invoice
 * according to the option in plugin settings
 */

function fatt_24_get_product_name_order()
{
    $selectedOption = get_option('fatt-24-ord-add-description');
    $resultOption = 'default';
    switch ($selectedOption) {
        case 0:
        default:
            break;
        case 1:
            $resultOption = 'short';
            break;
        case 2:
            $resultOption = 'long';
            break;
    }
    return $resultOption;
}

// product name in invoice
function fatt_24_get_product_name_invoice()
{
    $selectedOption = get_option('fatt-24-inv-add-description');
    $resultOption = 'default';
    switch ($selectedOption) {
        case 0:
        default:
            break;
        case 1:
            $resultOption = 'short';
            break;
        case 2:
            $resultOption = 'long';
            break;
    }
    return $resultOption;
}

// Method by which I append the text to product name
function fatt_24_appendToName($docType, $productData, $productName)
{
    $productNameOption = $docType == 'C' ?
                         fatt_24_get_product_name_order() :
                         fatt_24_get_product_name_invoice();

    $resultName = $productName;
    switch ($productNameOption) {
        case 'default':
        default:
             break;
        case 'short':
             // per il carattere a capo (\n) è meglio usare le doppie virgolette
             $resultName .= "\n" . $productData['short_description'];
             break;
        case 'long':
             $resultName .= "\n" . $productData['description'];
             break;
    }
    return $resultName;
}

/**
 *  Con questo metodo intendo ottenere i campi fiscali del cliente
 * l'idea è che se non li trovo nell'ordine, uso quelli inseriti dal cliente
 * in fase di registrazione. Li converto sempre in maiuscole
 * @param $order
 * @return array
 */

function fatt_24_get_billing_fields($order)
{
    $user = $order->get_user();
    $user_id = $order->get_user_id();

    $f24BillingFields = array('CodFisc' => strtoupper(fatt_24_order_c_fis($order)),
                              'PartIva' => strtoupper(fatt_24_order_p_iva($order)),
                              'SdiCode' => strtoupper(fatt_24_order_recipientcode($order)),
                              'PecEmail' => strtoupper(fatt_24_order_pecaddress($order))
                            );

    /**
     * Sovrascrivo i dati solo se i campi dell'ordine sono vuoti
     */
    foreach ($f24BillingFields as $key => $val) {
        switch ($key) {
            case 'CodFisc':
                if (empty($val)) {
                    $f24BillingFields['CodFisc'] = strtoupper(fatt_24_user_fiscalcode($user_id));
                }
                break;
            case 'PartIva':
                if (empty($val)) {
                    $f24BillingFields['PartIva'] = strtoupper(fatt_24_user_vatcode($user_id));
                }
                break;
            case 'SdiCode':
                if (empty($val)) {
                    $f24BillingFields['SdiCode'] = strtoupper(fatt_24_user_recipientcode($user_id));
                }
                break;
            case 'PecEmail':
                if (empty($val)) {
                    $f24BillingFields['PecEmail'] = strtoupper(fatt_24_user_pecaddress($user_id));
                }
                break;
            default:
                $f24BillingFields = array(
                                         'CodFisc' => strtoupper(fatt_24_order_c_fis($order)),
                                         'PartIva' => strtoupper(fatt_24_order_p_iva($order)),
                                         'SdiCode' => strtoupper(fatt_24_order_recipientcode($order)),
                                         'PecEmail' => strtoupper(fatt_24_order_pecaddress($order))
                                    );
        }
    }

    return $f24BillingFields;
}

/**
 * Con questo metodo rimuovo dalla stringa
 * il codice del paese di fatturazione e caratteri
 * come lo spazio, il punto, la virgola...
 * Dopo aver tolto tutti i caratteri sporchi tolgo anche il billing_country
 * dai primi due caratteri
 * Davide Iandoli 24.11.2022
 */
function fatt_24_clean_vat_number($billing_country, $vat_number)
{ 
    $toBeReplaced = [' ', '.', '-', ',', ', ' ];
    $first_step = strtoupper(str_replace( $toBeReplaced, '', $vat_number));
    $needle = substr($first_step, 0, 2);
    $result = $needle == $billing_country ? substr($first_step, 2) : $first_step;
    return $result;
}



/**
 * Rate e descrizione predefinita per le aliquote zero
 * utilizzate dalla spedizione
 */
function fatt_24_getDefaultZeroShippingRates()
{
    $shippingVat = 0;
    $shippingDescription = 'IVA '. $shippingVat . '%';

    $zeroShippingRate = ['rate' => $shippingVat,
                         'label'=> $shippingDescription];
    return $zeroShippingRate;
}

/**
 * Con questo metodo costruisco un array con tutte le aliquote
 * configurate per la spedizione
 * @param $order
 * @return array con rate e description
 */
function fatt_24_buildShippingTaxArray($order)
{
    $shippingTaxArray = [];
    $order_shipping_tax = $order->get_shipping_tax();

    foreach ($order->get_items('tax') as $item_id => $item_tax) {
        $tax_data = $item_tax->get_data();
        //fatt_24_trace('tax data :', $tax_data);
        $order_shipping_tax = $order->get_shipping_tax();

        /*
        * Quando è zero uno dei due valori
        * può essere stringa e l'altro numero
        * fix del 29.04.2021 vedi ticket n.: 61910 uservoice
        * edit del 23.06.2021 : forzo il tipo di dati in float
        */

        if ((float) $order_shipping_tax == (float) $tax_data['shipping_tax_total']) {
            $shippingTaxArray[] = [
                  'rate_id' => $tax_data['rate_id'],
                  'rate' => $tax_data['rate_percent'],
                  'label' => $tax_data['label']
                ];
        }
    }

    return $shippingTaxArray;
}

/**
 * Con questo metodo vedo se ci sono sconti nell'ordine
 * nell'ordine in WooCommerce nell'oggetto Coupon non c'è il discount_type
 * cfr: https://woocommerce.github.io/code-reference/classes/WC-Coupon.html
 * @return array personalizzato di coupons con i dati a noi necessati
 */
function fatt_24_coupon_array($order)
{
    $couponData = [];
    // get_coupon_codes è deprecato, lasciato per compatibilità con vecchie versioni woo
    $coupons = method_exists($order, 'get_coupon_codes') ?
               $coupons = $order->get_coupon_codes() :
               $coupons = $order->get_used_coupons();

    $nCoupons = count($coupons);

    $resultCouponData = [];

    if ($nCoupons > 0) {
        foreach ($coupons as $data) {
            // qui istanzio l'oggetto per ottenerne le proprietà
            $couponData[] = new \WC_Coupon($data);
        }
        fatt_24_trace('coupon data array :', $couponData);
        foreach ($couponData as $key => $val) {
            $resultCouponData[$key]['code'] = $couponData[$key]->get_code();
            $resultCouponData[$key]['type'] = $couponData[$key]->get_discount_type();
            $resultCouponData[$key]['amount'] = (float) $couponData[$key]->get_amount();
            $resultCouponData[$key]['product_ids'] = $couponData[$key]->get_product_ids();
            $resultCouponData[$key]['product_categories'] = $couponData[$key]->get_product_categories();
            $resultCouponData[$key]['excluded_product_categories'] = $couponData[$key]->get_excluded_product_categories();
        }
    }

    return $resultCouponData;
}


function fatt_24_percent_coupons($coupons)
{
    $couponList = [];
    foreach ($coupons as $data) {
        if ($data['type'] == 'percent') {
            $couponList[] = $data;
        }
    }
    return $couponList;
}

function fatt_24_fixed_coupons($coupons)
{
    $couponList = [];
    foreach ($coupons as $data) {
        if ($data['type'] !== 'percent') {
            $couponList[] = $data;
        }
    }
    return $couponList;
}

/**
 * Cerco l'aliquota tra quelle nel carrello
 * @param $needleRate => aliquota da cercare
 * @param $shippingTaxArray => array delle aliquote
 * @return array contenente aliquota utilizzata
 */
function fatt_24_usedShippingRate($needleRate, $shippingTaxArray)
{
    // cfr: https://www.php.net/manual/en/function.array-search.php
    // negli ordini admin $needleRate può essere una stringa, perciò forzo il tipo float
    $usedShippingTax = [];

    if (!$shippingTaxArray) {
        return $usedShippingTax;
    }

    $usedRate = array_search((float)$needleRate, array_column($shippingTaxArray, 'rate'));

    if ($usedRate) {
        $usedShippingTax = ['rate_id' => $shippingTaxArray[$usedRate]['rate_id'],
                            'rate' => $shippingTaxArray[$usedRate]['rate'],
                            'label' => $shippingTaxArray[$usedRate]['label']];
    }

    return $usedShippingTax;
}


/**
* Gestione Natura IVA: il @param $tax_id assume valore di default = null per gestire i casi
* in cui non riesco ad ottenerne il valore (es.: aliquota spedizione != aliquota prodotto)
*/
function fatt_24_getNatureColumn($tax_id = null)
{
    global $wpdb;
    $prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
    $blog_id = is_multisite() ? get_current_blog_id() : 1;
    $table_name = $prefix . 'fattura_tax';
    $sql = "SELECT tax_code FROM $table_name WHERE blog_id = ". $blog_id; // prima query per tax_code, vuoto solo se non impostato nel plugin
    $mycodeboj = $wpdb->get_row($sql);
    if (isset($tax_id)) { // query diversa se $tax_id != null
        $sql2 = "SELECT tax_code FROM $table_name WHERE tax_id = ".$tax_id ." AND blog_id = ".$blog_id; // dove possibile uso gli elementi dell'ordine
        $mycodeboj2 = $wpdb->get_row($sql2);
    }

    if (isset($mycodeboj2)) {
        $result_object = $mycodeboj2;
    } // se l'oggetto $mycodeboj2 non è vuoto gestisco questo
    else {
        $result_object = $mycodeboj;
    }

    $array['tax_code'] = ''; // la query dà  come risultato una stdClass, il codice da qui
    if (is_object($result_object)) { // è stato aggiunto per gestirla correttamente
        $array = get_object_vars($result_object);
    }
    $tax_code = $array['tax_code']; // Qui ottengo il risultato della colonna Natura, anche se non ho il tax_id
    return $tax_code;
}

/**
 *  Con questo metodo getisco il bollo virtuale solo per le FE
 *  e per ordini con importo > 77.47
 *  Davide Iandoli 16.04.2020
 */
function fatt_24_applyVirtualStamp($docType, $total)
{
    $f24VirtualStampOption = get_option('fatt-24-bollo-virtuale-fe');

    if ($f24VirtualStampOption == 1 && $docType == FATT_24_DT_FATTURA_ELETTRONICA && ($total > 77.47)) {
        $stamp_value = "V";
    } else {
        $stamp_value = "N";
    }

    return $stamp_value;
}

/**
 * Controllo se il paese di fatturazione è nella UE
 * tramite codice ISO (occhio alla lista)
 */
function fatt_24_isEU($country)
{
    $EUcountries = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'EL',
        'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV',
        'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK'
    ];
    return in_array($country, $EUcountries);
}

/**
 * Summary of fattura24\fatt_24_get_resulting_doc_type
 * @param string $country
 * @param string $VatCode
 * @return string $resultType
 * 
 * Davide Iandoli 11.01.2023
 * Devo creare una Fe in questi casi:
 * - l'opzione selezionata nel menu a tendina è 2
 *   - e il paese di fatturazione NON è IT;
 *   - ed è presente la spunta nella casella 'Crea sempre e solo fatture';
 *   - ed è presente la spunta nel checkbox 'Desidero ricevere una fattura' lato ;
 *   - ed il cliente ha compilato il campo p.iva
 * 
 * Attenzione: il risultato di questa funzione viene utilizzato anche
 * nel file met_hooks_fields.php righe 392 e seguenti (logica convalida campi checkout ed errori)
 */
function fatt_24_get_resulting_doc_type($country, $VatCode = null)
{
    $selectedOption = (string) get_option('fatt-24-inv-create');
    $createAlwaysInvoice = fatt_24_get_flag(FATT_24_INV_DISABLE_RECEIPTS);
    $validOption = $selectedOption != '0';
    $customerRequiredInvoice = isset($_POST['billing_checkbox']) && (int) $_POST['billing_checkbox'] == 1;
    $resultType = FATT_24_DT_RICEVUTA; // valore di default, non voglio sorprese
     /**
     * Nei casi in cui l'aggiornamento automatico delle impostazioni
     * su 'Crea documento fiscale' falliscano, meglio creare una ricevuta
     * che niente
     */
    if (!$validOption) {
        return FATT_24_DT_RICEVUTA;
    }

    if ($selectedOption == '1') {
        
            if ($createAlwaysInvoice) {
                $resultType = FATT_24_DT_FATTURA_FORCED;
            } elseif($customerRequiredInvoice) {
                $resultType = FATT_24_DT_FATTURA_FORCED;
            }  else {
                $resultType = $VatCode ? FATT_24_DT_FATTURA : FATT_24_DT_RICEVUTA;
            }
    } elseif ($selectedOption == '2') {
        
            if ($createAlwaysInvoice) {
                $resultType = FATT_24_DT_FATTURA_ELETTRONICA;
            } elseif (!$VatCode && $country == 'IT') {
                $resultType = FATT_24_DT_RICEVUTA;
            } else {
                $resultType =  FATT_24_DT_FATTURA_ELETTRONICA;
            }
    } elseif($selectedOption == '3') {
        
        if ($customerRequiredInvoice) {
            $resultType = FATT_24_DT_FATTURA_ELETTRONICA;
        } else {
            $resultType = $country == 'IT' && $customerRequiredInvoice ? FATT_24_DT_FATTURA_ELETTRONICA : FATT_24_DT_RICEVUTA;
        }
    } elseif ($selectedOption == '4') {
        
        if ($customerRequiredInvoice) {
            $resultType = FATT_24_DT_FATTURA_ELETTRONICA;
        } else {
            $resultType = $country == 'IT' && $customerRequiredInvoice ? FATT_24_DT_FATTURA_ELETTRONICA : FATT_24_DT_FATTURA_FORCED;
        }
    } elseif ($selectedOption == '5') {
        
        $resultType = FATT_24_DT_RICEVUTA;
    } elseif ($selectedOption == '6') {    
        
        if ($customerRequiredInvoice) {
            $resultType = FATT_24_DT_FATTURA_ELETTRONICA;
        } else {
            $resultType = fatt_24_isEU($country) && $customerRequiredInvoice ? FATT_24_DT_FATTURA_ELETTRONICA : FATT_24_DT_RICEVUTA;
        }    
    } elseif ($selectedOption == '7') {
        
        if ($customerRequiredInvoice) {
            $resultType = FATT_24_DT_FATTURA_ELETTRONICA;
        } else {
            $resultType = fatt_24_isEU($country) && $customerRequiredInvoice? FATT_24_DT_FATTURA_ELETTRONICA : FATT_24_DT_FATTURA_FORCED;
        }
    }

    return $resultType;
}

/**
 * Converte in caratteri latini ed elimina caratteri strani
 */
function fatt_24_strip_illegal_chars($param = '')
{
    $result = '';
    if (!empty($param)) {
        // converto tutto in carateri latini
        $latinString = iconv('UTF-8', 'ASCII//TRANSLIT', $param);
        $latinSubstr = substr($latinString, 0, 28); // max 28 caratteri
        $regex = '/[^a-zA-z0-9\s(\-.)]/';
        $result = preg_replace($regex, '', $latinSubstr);
        //$result = $latinSubstr;
    }
    return $result;
}

function fatt_24_is_iterable($var)
{
    $result = function_exists('is_iterable') ? is_iterable($var)
              : is_array($var) || $var instanceof \Traversable;

    return $result;
}

function fatt_24_get_shipping_rate_description($ratesArray, $shippingVat)
{
    foreach ($ratesArray as $val) {
        if ($val['rate'] == $shippingVat) {
            $shippingDescription = $val['description'];
        }
    }
    return $shippingDescription;
}

// cfr.: https://stackoverflow.com/questions/21994677/find-json-strings-in-a-string
/**
 * Sostituito sanitize_text_field a strip_tags (in un caso il tag <span> veniva stampato nel prodotto)
 * con array_filter evito di inserire valori vuoti separati da virgole - Davide Iandoli 31.10.2022
 * 
*/
function fatt_24_get_attr_names($item_meta_data) {
    $meta_values = [];
    foreach ($item_meta_data as $item_meta) {
        if (is_array($item_meta->get_data()['value'])) {
            continue;
        }
        $clean_data = sanitize_text_field(html_entity_decode($item_meta->get_data()['value']));
        $meta_values[] = $clean_data;
    }
    return implode(', ', array_filter($meta_values));
}

// hook con cui consento di stabilire una um diversa per ogni prodotto
add_filter('fatt_24_product_um', function($um, $product_id) {
    switch ($um) {
        case 'dimension':
            $um = get_option('woocommerce_dimension_unit');
        break;
        case 'weight':
            $um = get_option('woocommerce_weight_unit');
        break;
        default:
            $um = 'pz';   
        break;
    }
    return $um;

}, 10, 2);