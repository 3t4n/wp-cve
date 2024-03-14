<?php
/** 
 * Don't access this directly, please 
*/

if (!defined('ABSPATH')) exit;

include plugin_dir_path(__FILE__) . '/check_conn.php';

$api_uid = get_option('wfic_id_azienda');
$api_key = get_option('wfic_api_key_fattureincloud');


if (empty($id_ordine_scelto)) { 

    $id_ordine_scelto = $order_id;

} else {

    $order_id = $id_ordine_scelto;
}

error_log("Ordine selezionato => ".$id_ordine_scelto );

$order = wc_get_order($id_ordine_scelto);
$order_note = $order->get_customer_note();
$order_data = $order->get_data(); // The Order data
$order_shipping_total = $order_data['shipping_total'];
$order_shipping_tax = $order_data['shipping_tax'];

$order_note = $order->get_customer_note();
$order_billing_first_name = $order_data['billing']['first_name'];
$order_billing_last_name = $order_data['billing']['last_name'];
$order_billing_company = $order_data['billing']['company'];
$order_billing_address_1 = $order_data['billing']['address_1'];
$order_billing_address_2 = $order_data['billing']['address_2'];
$order_billing_city = $order_data['billing']['city'];
$order_billing_state = $order_data['billing']['state'];
$order_billing_postcode = $order_data['billing']['postcode'];
$order_billing_country = $order_data['billing']['country'];
$order_billing_email = $order_data['billing']['email'];
$order_billing_phone = $order_data['billing']['phone'];
$order_billing_method = $order_data['payment_method_title'];
$order_billing_payment_method = $order_data['payment_method'];

$order_billing_codfis = $order->get_meta('_billing_cod_fisc');
$order_billing_partiva = $order->get_meta('_billing_partita_iva');
$order_billing_emailpec = $order->get_meta('_billing_pec_email');
$order_billing_coddest = $order->get_meta('_billing_codice_destinatario');

//#######################################################################################################################
/*   compatibilità col plugin woo-piva-codice-fiscale-e-fattura-pdf-per-italia  DOPO API 2 disabilitato*/
//#######################################################################################################################

$id_ordine_scelto = $order_id;

/*
if (get_post_meta($id_ordine_scelto, '_billing_piva', true) || get_post_meta($id_ordine_scelto, '_billing_cf', true) 
    || get_post_meta($id_ordine_scelto, '_billing_pa_code', true) || get_post_meta($id_ordine_scelto, '_billing_pec', true)
) {

    $order_billing_partiva = get_post_meta($id_ordine_scelto, '_billing_piva', true);
    $order_billing_codfis = get_post_meta($id_ordine_scelto, '_billing_cf', true);
    $order_billing_coddest = get_post_meta($id_ordine_scelto, '_billing_pa_code', true);
    $order_billing_emailpec = get_post_meta($id_ordine_scelto, '_billing_pec', true);

    if (empty($order_billing_coddest) && empty($order_billing_emailpec)) {
        $order_billing_coddest = "0000000";

        if ($order_billing_country !== 'IT') {
            $order_billing_emailpec = "";
            $order_billing_coddest = "XXXXXXX";
            $order_billing_postcode = "00000";
        }

    }


//########################################################################################################################    
    
} */ 

if ( !empty($order_billing_partiva ) || !empty($order_billing_codfis) || !empty($order_billing_emailpec) || !empty($order_billing_coddest)
    ) 
 {
    /*
    $order_billing_partiva = get_post_meta($id_ordine_scelto, '_billing_partita_iva', true);
    $order_billing_codfis = get_post_meta($id_ordine_scelto, '_billing_cod_fisc', true);
    $order_billing_emailpec = get_post_meta($id_ordine_scelto, '_billing_pec_email', true);
    $order_billing_coddest = get_post_meta($id_ordine_scelto, '_billing_codice_destinatario', true);
    */

    if ($order_billing_country !== 'IT') {
        
        $order_billing_emailpec = "";
        $order_billing_coddest = "XXXXXXX";
        $order_billing_postcode = "00000";
        if (empty($order_billing_partiva)) {
            
            $order_billing_partiva = $order_billing_codfis;
            $order_billing_codfis = "";
        
        } else {
            
            $order_billing_codfis = "";
        }
        
    
    }


    if (empty($order_billing_coddest) && empty($order_billing_emailpec)) {
        $order_billing_coddest = "0000000";

        if ($order_billing_country !== 'IT') {
            $order_billing_emailpec = "";
            $order_billing_coddest = "XXXXXXX";
            $order_billing_postcode = "00000";
        }

    }


} else {

    $order_billing_partiva ="";
    $order_billing_codfis = "";
    $order_billing_emailpec = "";
    $order_billing_coddest = "0000000";

}



//####################################################################################################################

//error_log("SHIPPING TAX =>" .$order_shipping_tax);

$lista_articoli = array();


$spedizione_lorda = $order_data['shipping_total'] + $order_shipping_tax ;

$spedizione_netta = $spedizione_lorda - $order_shipping_tax;


$codice_iva = '';


foreach ($order->get_items() as $item_key => $item_values):

    $item_data = $item_values->get_data();

    $line_total = $item_data['total'];

    if ($item_data['variation_id'] > 0) { 
    
        $product_id = $item_values->get_variation_id(); // the Product id

    } else {

        $product_id = $item_values->get_product_id(); // the Variable Product id
    }
    
    $wc_product = $item_values->get_product(); // the WC_Product object
    
    /* Access Order Items data properties (in an array of values) */
    
    $item_data = $item_values->get_data();
    $_product = wc_get_product($product_id);

    //$tax_rates = WC_Tax::get_rates($_product->get_tax_class());
    $tax_rates = WC_Tax::get_base_tax_rates($_product->get_tax_class(true));

    //$tax_rate = reset($tax_rates);
    
    //##########################################################

    if (!empty($tax_rates)) {
   
        $tax_rate = reset($tax_rates);

        
        if ($tax_rate['rate'] == 22) {

            $codice_iva = 0;


        } elseif ($tax_rate['rate'] == 0) {

            $codice_iva = 6; 

 /*
            if ($item_data['tax_class'] === 'zero-rate-n1') {

                $codice_iva = 21;

            } elseif ($item_data['tax_class'] === 'zero-rate-n2') {

                $codice_iva = 10;

            } elseif ($item_data['tax_class'] === 'zero-rate-n3') {

                $codice_iva = 12;

            } elseif ($item_data['tax_class'] === 'zero-rate-n4') {

                $codice_iva = 46;

            } elseif ($item_data['tax_class'] === 'zero-rate-n5') {

                $codice_iva = 30;

            } elseif ($item_data['tax_class'] === 'zero-rate-n6') {

                $codice_iva = 11;

            } elseif ($item_data['tax_class'] === 'zero-rate-n7') {

                $codice_iva = 16;
*/


            } else { 
                
                $codice_iva = 6;  
            
            }

/*
        } elseif ($tax_rate['rate'] == 4) {

            $codice_iva = '';

        } elseif ($tax_rate['rate'] == 10) {

            $codice_iva = '';

        }

        */

    } elseif (empty($tax_rates)) {

        $codice_iva = 6;
    }


//##########################################################            
                      





    //##########################################################à

    $prezzo_singolo_prodotto = ((round($item_data['total'], 2)+$item_data['total_tax']) / $item_data['quantity']);
    $prezzo_netto_singolo_prodotto = (round($item_data['total'], 2) / $item_data['quantity']);
    
    $ivatosiono = true;

    //######################################################

    $mostra_brevedesc = '';

    if (1 == get_option('show_short_descr') ) {

        $mostra_brevedesc = $wc_product->get_short_description();

    }

##########################################################
    
#############################################################

    $lista_articoli_api2[] = array(
    
        "name" => $item_data['name'],
        "code" => $wc_product->get_sku(),
        "description" => $mostra_brevedesc ,
        "qty" => $item_data['quantity'],
        /*"cod_iva" => $codice_iva, */
        "net_price" => $prezzo_netto_singolo_prodotto,
        "gross_price" => $prezzo_singolo_prodotto,
        "vat" => array (
            "id" => $codice_iva
            )
    );

    //###########################################################

    if ('paypal' == $order_billing_payment_method) {

        $payment_method_fic = 'MP08';

    } elseif ('stripe' == $order_billing_payment_method) {

        $payment_method_fic = 'MP08';

    } elseif ('bacs' == $order_billing_payment_method) {

        $payment_method_fic = 'MP05';

    } elseif ('cheque' == $order_billing_payment_method) {

        $payment_method_fic = 'MP02';

    } elseif ('cod' == $order_billing_payment_method) {

        $payment_method_fic = 'MP01';

    } else {

        $payment_method_fic = 'MP01';
    }


endforeach;

//#########################################
/*
foreach( $order->get_items('fee') as $item_id => $item_fee ){


    // The fee name
    $fee_name = $item_fee->get_name();

    // The fee total amount
    $fee_total = round($item_fee->get_total() , 2, PHP_ROUND_HALF_UP);    

    // The fee total tax amount
    $fee_total_tax = $item_fee->get_total_tax();

    if ($fee_total_tax > 0) {

        $cod_fee_iva = 0;

    } else {

        $cod_fee_iva = 6;
    }


    $total_fee_fic = round($fee_total + $fee_total_tax , 2, PHP_ROUND_HALF_UP);   
    
    if (($fee_name !== "Rivalsa INPS") && ($fee_name !== "Cassa Previdenza")) {

    $lista_articoli_api2[] 
        
        =   array(

            "name" => $fee_name,
            "qty" => 1,
            "net_price" => $fee_total,
            "gross_price" => $total_fee_fic,
            "vat" => array (
                "id" => $cod_fee_iva
                )


        );

    }
}
*/
/*
echo "<pre>";
print_r($lista_articoli);
echo "</pre>";
*/
//###########################################


if ($order_data['shipping_total'] > 0) {

    if ($order_data['shipping_tax'] == 0) {

        if ($tax_class == 'zero-rate-22') {
            $codice_iva = 22;
        
        } else {
            $codice_iva = 6; 
        }

        //$cod_shipping_iva = 6;
        
    }        


    $lista_articoli_api2[] 
        
        =   array(

            "name" => "Spese di Spedizione",
            "qty" => 1,
            //"cod_iva" => 0,
            "net_price" => $spedizione_netta,
            "gross_price" => $spedizione_lorda,
            "vat" => array (
                "id" => $codice_iva 
                )
            


        );

}


//################################################

//print_r($lista_articoli);

if (isset($_POST['woo-datepicker'])) { 

    /*$data_di_scadenza;*/
    $data_di_scadenza = $_POST['woo-datepicker'];
    
    
} else {

    $data_di_scadenza = date("Y-m-d");

}

//###################################################

if (1 == get_option('fattureincloud_paid')) {

    $fattureincloud_invoice_paid = $order_data['payment_method'];
    $mostra_info_pagamento = true;
    $data_saldo = $order_data['date_created']->date("Y-m-d");
    $data_scadenza = date("Y-m-d");

} elseif (0 == get_option('fattureincloud_paid')) {

    $fattureincloud_invoice_paid = 'not';
    $mostra_info_pagamento = false;
    $data_saldo = 'not';
    $data_scadenza = $data_di_scadenza;

}



#####################################################

if (!empty($order_billing_company)) {

    $wfic_name_tosend = $order_billing_company." / ".$order_billing_first_name . " " . $order_billing_last_name;

} else {

    $wfic_name_tosend = $order_billing_first_name . " " . $order_billing_last_name;

}

###########################################################

/*
$fattureincloud_request = array(

    "api_uid" => $api_uid,
    "api_key" => $api_key,
    "nome" => $wfic_name_tosend ,
    "indirizzo_via" => $order_billing_address_1,
    "indirizzo_cap" => $order_billing_postcode,
    "indirizzo_citta" => $order_billing_city,
    "indirizzo_provincia" => $order_billing_state,
    "paese_iso" => $order_billing_country,
    "prezzi_ivati" => $ivatosiono ,
    "piva" => $order_billing_partiva,
    "cf" => $order_billing_codfis,
    "salva_anagrafica" => $salva_ononsalva,
    "numero" => $sezionale_woofatture,
    "data" => $data_documento,
    "marca_bollo" => $woofic_marca_bollo,
    "oggetto_visibile" => "Ordine numero ".$id_ordine_scelto,
    "note" => $order_note,
    "mostra_info_pagamento" => $mostra_info_pagamento,
    "metodo_pagamento" => $order_billing_method,
    "lista_articoli" => $lista_articoli,
    "lista_pagamenti" => array(
        array(
        "data_scadenza" => $data_scadenza,
        "importo" => 'auto',
        "metodo" => $fattureincloud_invoice_paid,
        "data_saldo" => $data_saldo ,

        )
    ),
    "PA" => $fatturaelettronica_fic,
    "PA_tipo_cliente" => 'B2B',
    "PA_data" => $data_scadenza,
    "PA_pec" => $order_billing_emailpec,
    "PA_codice" => $order_billing_coddest,
    "PA_modalita_pagamento" => $payment_method_fic,

    "extra_anagrafica" => array(
        "mail" => $order_billing_email,
        "tel" => $order_billing_phone
    )
);

*/
############################################################################

$company_ID =   $api_uid;

$wfic_token = $api_key;

###############################################################################

$ch_list_conti = curl_init();

curl_setopt($ch_list_conti, CURLOPT_URL, 'https://api-v2.fattureincloud.it/c/'.$company_ID.'/settings/payment_accounts');
curl_setopt($ch_list_conti, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch_list_conti, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array(
    "Authorization: Bearer ".$wfic_token."",
    "Content-Type: application/json",
 );

curl_setopt($ch_list_conti, CURLOPT_HTTPHEADER, $headers);

$result_pay_list = curl_exec($ch_list_conti);
if (curl_errno($ch_list_conti)) {
    echo 'Error:' . curl_error($ch_list_conti);
}
curl_close($ch_list_conti);

$result_payment_list_fic = json_decode($result_pay_list, true);

error_log("CONTO di saldo => ".$order_billing_method);
error_log("METODO di pagamento => ".$order_billing_payment_method);


foreach ($result_payment_list_fic as $vals_list_pay) { 

    foreach ($vals_list_pay as $vals_list_pay_id) {
/*
        error_log('conti =>');
        error_log(print_r($vals_list_pay_id, true));
*/
     
       

              

        
        //$elenco_list_pay = $vals_list_pay_id['name']." => ".$vals_list_pay_id['id'];

        //error_log($elenco_list_pay);




        $wfic_conti_saldo_gestiti = array("paypal", "ppcp-gateway","ppec-paypal","bacs","stripe","cheque","cod","");

        if ((!in_array($order_billing_payment_method, $wfic_conti_saldo_gestiti)) && $vals_list_pay_id['name'] == "altro") {

            $payment_list_woo_fic_id = array("id" => $vals_list_pay_id['id']);
    
        } elseif ($order_billing_payment_method == "paypal" && $vals_list_pay_id['name'] == "Paypal") {
            
            $payment_list_woo_fic_id = array("id" => $vals_list_pay_id['id']);

        } elseif ($order_billing_payment_method == "ppcp-gateway" && $vals_list_pay_id['name'] == "Paypal") {

            $payment_list_woo_fic_id = array("id" => $vals_list_pay_id['id']);

        } elseif ($order_billing_payment_method == "ppec-paypal" && $vals_list_pay_id['name'] == "Paypal") {

            $payment_list_woo_fic_id = array("id" => $vals_list_pay_id['id']);

        } elseif ($order_billing_payment_method == "woocommerce_payments" && $vals_list_pay_id['name']=== "Credit card / debit card") {
            
            $payment_list_woo_fic_order = $vals_list_pay_id['id'];

        } elseif ($order_billing_payment_method == "bacs" && $vals_list_pay_id['name'] == "Bonifico Bancario") {

            $payment_list_woo_fic_id = array("id" => $vals_list_pay_id['id']);

        } elseif ($order_billing_payment_method == "stripe" && $vals_list_pay_id['name'] == "Stripe") {
            
            $payment_list_woo_fic_id = array("id" => $vals_list_pay_id['id']);

        } elseif ($order_billing_payment_method == "cheque" && $vals_list_pay_id['name'] == "Assegno") {
            
            $payment_list_woo_fic_id = array("id" => $vals_list_pay_id['id']);

        } elseif ($order_billing_payment_method == "cod" && $vals_list_pay_id['name'] == "Pagamento alla Consegna") {
            
            $payment_list_woo_fic_id = array("id" => $vals_list_pay_id['id']);

        } elseif ($order_billing_payment_method == "" && $vals_list_pay_id['name'] == "gratuito") { 

            $payment_list_woo_fic_id = array("id" => $vals_list_pay_id['id']);

        } elseif ($order_billing_payment_method == "" && $vals_list_pay_id['name'] == "Gratuito") { 

            $payment_list_woo_fic_id = array("id" => $vals_list_pay_id['id']);

        } 
        
    }

}


if (!empty($new_conto_saldo))  { 
$type = 'success';
$message = __('Conto di Saldo '.$new_conto_saldo.' aggiunto', 'woo-fattureincloud');
add_settings_error('woo-fattureincloud', esc_attr('settings_updated'), $message, $type);
settings_errors('woo-fattureincloud');
}


###############################################################################
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api-v2.fattureincloud.it/c/'.$company_ID.'/settings/payment_methods');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array(
    "Authorization: Bearer ".$wfic_token."",
    "Content-Type: application/json",
 );

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result_pay_met = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$result_payment_methods_fic = json_decode($result_pay_met, true);


//print_r($result_payment_methods_fic);
//echo "Metodi<br>";


foreach ($result_payment_methods_fic as $vals_met_pay) {


    foreach ($vals_met_pay as $vals_met_pay_id) {

  /*    
  error_log('metodi =>');
  error_log(print_r($vals_met_pay_id, true));
  */



        

        if ($order_billing_payment_method == "bacs" && $vals_met_pay_id['name']=== "Bonifico Bancario") {
            
            
            $payment_method_woo_fic_order = $vals_met_pay_id['id'];

            $payment_method_fic_ei_code = 'MP05';

        
        } elseif ($order_billing_payment_method == "paypal" && $vals_met_pay_id['name']=== "Paypal") {
            
        

            $payment_method_woo_fic_order = $vals_met_pay_id['id'];

            $payment_method_fic_ei_code = 'MP08';


        } elseif ($order_billing_payment_method == "ppcp-gateway" && $vals_met_pay_id['name']=== "Paypal") {
            
        

            $payment_method_woo_fic_order = $vals_met_pay_id['id'];

            $payment_method_fic_ei_code = 'MP08';

        } elseif ($order_billing_payment_method == "ppec-paypal" && $vals_met_pay_id['name']=== "Paypal") {
            
        

            $payment_method_woo_fic_order = $vals_met_pay_id['id'];

            $payment_method_fic_ei_code = 'MP08';


        } elseif ($order_billing_payment_method == "woocommerce_payments" && $vals_met_pay_id['name']=== "Credit card / debit card") {
            
        

            $payment_method_woo_fic_order = $vals_met_pay_id['id'];

            $payment_method_fic_ei_code = 'MP08';

        } elseif ($order_billing_payment_method == "stripe" && $vals_met_pay_id['name']=== "Stripe") {
            
        

            $payment_method_woo_fic_order = $vals_met_pay_id['id'];

            $payment_method_fic_ei_code = 'MP08';

        } elseif ($order_billing_payment_method == "cheque" && $vals_met_pay_id['name']=== "Assegno") {
            
        

            $payment_method_woo_fic_order = $vals_met_pay_id['id'];

            $payment_method_fic_ei_code = 'MP05';

        } elseif ($order_billing_payment_method == "cod" && $vals_met_pay_id['name']=== "Pagamento alla Consegna") {
            
        

            $payment_method_woo_fic_order = $vals_met_pay_id['id'];

            $payment_method_fic_ei_code = 'MP01';

        } elseif ($order_billing_payment_method == "" && $vals_met_pay_id['name']=== "gratuito") {
            
        

            $payment_method_woo_fic_order = $vals_met_pay_id['id'];

            $payment_method_fic_ei_code = 'MP01';

            
        } elseif ($order_billing_payment_method == "" && $vals_met_pay_id['name']=== "Gratuito") {
            
        

            $payment_method_woo_fic_order = $vals_met_pay_id['id'];

            $payment_method_fic_ei_code = 'MP01';

            
        } elseif (($order_billing_payment_method !== "bacs") && ($order_billing_payment_method !== "paypal") &&
        
        ($order_billing_payment_method !== "ppcp-gateway") && ($order_billing_payment_method !== "ppec-paypal") &&

        ($order_billing_payment_method !== "stripe") && ($order_billing_payment_method !== "cheque") &&
        
        ($order_billing_payment_method !== "cod") && ($order_billing_payment_method !== "") 
        
        && $vals_met_pay_id['name']=== "altro") {

            $payment_method_woo_fic_order = $vals_met_pay_id['id'];

            $payment_method_fic_ei_code = 'MP08';
        
        
        }


    }

}


#################################################################################################
#                   ANAGRAFICA CLIENTE
#################################################################################################



#####################################################
# ELENCO CLienti da Fattureincloud.it
#####################################################

//$url = "https://api-v2.fattureincloud.it/c/".$company_ID."/entities/clients?fieldset=detailed";

$wfic_condizioni = [];
if($order_billing_codfis) $condizioni[] = "tax_code = '$order_billing_codfis'";
if($order_billing_partiva) $condizioni[] = "vat_number = '$order_billing_partiva'";
if($order_billing_email) $condizioni[] = "email = '$order_billing_email'";

$wfic_query_cerca_clienti = join(" OR ", $condizioni);


$params = array(
    
    'q' => $wfic_query_cerca_clienti
    
  );
  
  $query = http_build_query($params);

$url = "https://api-v2.fattureincloud.it/c/".$company_ID."/entities/clients?fieldset=detailed&".$query;

//$url = "https://api-v2.fattureincloud.it/c/".$company_ID."/entities/clients?fieldset=detailed";

error_log("URL Search >>>".$url);


include plugin_dir_path(__FILE__) . '/retrive_data.php';

$json_lista_clienti = json_decode($result, true);

if (!is_array($json_lista_clienti)) {  error_log("Lista clienti non caricata");  }

//error_log("Lista clienti => ".print_r($json_lista_clienti, true));


####################################################################################################################

if ( count($json_lista_clienti['data']) > 0 ) {


error_log("clienti già presenti su fattureincloud.it, vedo se necessario aggiornare");

error_log( $order_billing_email." || " . $order_billing_codfis ." ||".$order_billing_partiva  );

/*
    echo "<pre>";
    print_r($json_lista_clienti);
    echo "</pre>";
*/


    foreach ($json_lista_clienti as $value) {

        if (is_array($value)) {

            //foreach ($value as $value2) {
                
            //error_log(print_r($value, true));

            //error_log($value[0]['id']);


            $found_vat = (array_search($order_billing_partiva , array_column($value, 'vat_number') )) ;

            //error_log($found_vat." => P.Iva cliente già presente");
           
            if (($order_billing_partiva > 0 ) && (is_int($found_vat))) { 

            
            error_log("(".$found_vat. ") P.Iva cliente già presente => ".$value[$found_vat]['vat_number']);
            
            $id_cliente_daup = $value[$found_vat]['id'] ;
            
            error_log("ID cliente da aggiornare => ".$id_cliente_daup);
            
            }

            $found_cf = (array_search($order_billing_codfis , array_column($value, 'tax_code') )) ;

            //error_log("CF già presente => " .$found_cf);

            if (is_int($found_cf)) { 

            error_log("(".$found_cf. ") CF già presente =>".$value[$found_cf]['tax_code']);
                            
            $id_cliente_daup = $value[$found_cf]['id'] ;
            
            error_log("ID cliente da aggiornare => ".$id_cliente_daup);
            
            }
                      
 
            $found_email = (array_search($order_billing_email , array_column($value, 'email') )) ;

            //error_log($found_email. " => email cliente già presente");

           if (is_int($found_email)) { 

            error_log("(".$found_email. ") email cliente già presente  => ".$value[$found_email]['email']);
            $id_cliente_daup = $value[$found_email]['id'] ;
            error_log("ID cliente da aggiornare => ".$id_cliente_daup);

            }


       

##################################################################
#           Aggiorna cliente se già presente
##################################################################

                if (isset($id_cliente_daup)) {  
                
 /*
                $id_cliente = ($value[$found_email]['id']);
                $nome_cliente = ($value2['name']);
                $codice_fiscale_fic = ($value2['tax_code']);
                $partita_iva_fic = ($value2['vat_number']);
                $email_cliente_fic = ($value2['email']);

                $found = array_search($order_billing_email , $value);
                
             */


                    $data_pre_cliente = array ("data" => array(

                        "id" => $id_cliente_daup,
                        "name" => $wfic_name_tosend,
                        "country_iso" => $order_billing_country,
                        "first_name" => $order_billing_first_name,
                        "last_name" => $order_billing_last_name,
                        "vat_number" => $order_billing_partiva,
                        "tax_code" => $order_billing_codfis,
                        "address_street" => $order_billing_address_1,
                        "address_postal_code" => $order_billing_postcode,  
                        "address_city" => $order_billing_city,
                        "address_province" => $order_billing_state,
                        "email" => $order_billing_email,
                        "certified_email" => "$order_billing_emailpec",
                        "phone" => $order_billing_phone,
                        "ei_code" => $order_billing_coddest
                        ));

                        //print_r($data_pre_cliente['data'])." data pre [data]";

                        $entity_result = $data_pre_cliente['data'];

                        $put_url = "https://api-v2.fattureincloud.it/c/".$company_ID."/entities/clients/".$id_cliente_daup;

                        //error_log("PUT URL ".$put_url);

                        $data_to_put_wfic_postfields = json_encode($data_pre_cliente);

                        include plugin_dir_path(__FILE__) . '/put_data.php';

                        //error_log($id_cliente = $value2['id']);

                        include plugin_dir_path(__FILE__) . '/invio_documento.php';

                break;

                    

                    

                } else { 

                    

                 //   echo "STO CREANDO<br>";

                    ###############################################################
                    #       Crea il cliente se non è già presente
                    ###############################################################
                    
                                    $wfic_datatosend_url = "https://api-v2.fattureincloud.it/c/".$company_ID."/entities/clients";
                    
                                    $data_pre_cliente_nuovo = array ("data" => array(
                                
                                        "name" => $wfic_name_tosend,
                                        "country_iso" => $order_billing_country,
                                        "first_name" => $order_billing_first_name,
                                        "last_name" => $order_billing_last_name,
                                        "vat_number" => $order_billing_partiva,
                                        "tax_code" => $order_billing_codfis,
                                        "address_street" => $order_billing_address_1,
                                        "address_postal_code" => $order_billing_postcode,  
                                        "address_city" => $order_billing_city,
                                        "address_province" => $order_billing_state,
                                        "email" => $order_billing_email,
                                        "certified_email" => "$order_billing_emailpec",
                                        "phone" => $order_billing_phone,
                                        "ei_code" => $order_billing_coddest
                                      
                                    )
                                    );
                                
                                    //print_r($data_pre_cliente_nuovo['data'])." data pre [data]";

                                    $entity_result = $data_pre_cliente_nuovo['data'];
                                
                                    $data_tosend_wfic_postfields = json_encode($data_pre_cliente_nuovo);
                                
                                    include plugin_dir_path(__FILE__) . '/send_data.php';
                                /*
                                    echo "<hr>";
                                    print_r($response_value);
                                    echo "<hr>";
                                */
                                
                                    $id_cliente = $response_value['data']['id'];

                                    error_log("Nuovo cliente creato in anagrafica con ID => ".$id_cliente);                                    
                                    
                                    $entity_result['id'] = $id_cliente;

                                    include plugin_dir_path(__FILE__) . '/invio_documento.php';
                                    
                                    break;


##################################################################################





###################################################################à                    




                }


            }
            
        }

    

} else { 





###################################################################################
  
//    echo "CREO PRIMO CLIENTE";

################################################################
# Crea il PRIMO cliente se in fattureincloud.it non ce ne sono
################################################################

    $wfic_datatosend_url = "https://api-v2.fattureincloud.it/c/".$company_ID."/entities/clients";

    $data_pre = array ("data" => array(

        "name" => $wfic_name_tosend,
        "country_iso" => $order_billing_country,
        "first_name" => $order_billing_first_name,
        "last_name" => $order_billing_last_name,
        "vat_number" => $order_billing_partiva,
        "tax_code" => $order_billing_codfis,
        "address_street" => $order_billing_address_1,
        "address_postal_code" => $order_billing_postcode,  
        "address_city" => $order_billing_city,
        "address_province" => $order_billing_state,
        "email" => $order_billing_email,
        "certified_email" => "$order_billing_emailpec",
        "phone" => $order_billing_phone,
        "ei_code" => $order_billing_coddest,
      
        /*
        'default_vat' => 
        array (
          'id' => '',
          'value' => '',
          'description' => '',
          'is_disabled' => false,
        ), */
    )
    );

    $entity_result = $data_pre['data'];

    $data_tosend_wfic_postfields = json_encode($data_pre);

    include plugin_dir_path(__FILE__) . '/send_data.php';

 //   echo "<hr>";
 //   print_r($response_value);
 //   echo "<hr>";


    $id_cliente = $response_value['data']['id'];

    error_log("creato nuovo cliente con ID => ".$id_cliente );

    $entity_result['id'] = $id_cliente;
    $entity_result['name'] = $wfic_name_tosend;

    //echo $id_cliente . " ID CLIENTE";

include plugin_dir_path(__FILE__) . '/invio_documento.php';

}


