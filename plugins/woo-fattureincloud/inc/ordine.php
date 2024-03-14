<form id="woo-fattureincloud-preview" action="" method="POST">

<?php

/**
 * Security form
 */

wp_nonce_field();


if ( is_admin() ) {

    /*
 *
 * Controllo ID azienda e mostra messaggio se manca
 *
 *
 */

if (get_option('wfic_id_azienda') == null ) {

    header("Location: admin.php?page=woo-fattureincloud&tab=impostazioni");
/*
    $type = 'warning';
    $message = __('Azienda ID mancante, andare su <a href="admin.php?page=woo-fattureincloud&tab=impostazioni"> impostazioni</a>', 'woo-fattureincloud');
    add_settings_error('woo-fattureincloud', esc_attr('settings_updated'), $message, $type);
    settings_errors('woo-fattureincloud');

*/

}


    $url = "https://api-v2.fattureincloud.it/user/companies";

    include plugin_dir_path(__FILE__) . '/retrive_data.php';
    
    $json = json_decode($result, true);
    if (is_array($json)) {
    
        if (!empty($json['error'])) { 

            error_log("errore nella connessione con fattureincloud =>");

            error_log(print_r($json['error'] , true));
    
            include plugin_dir_path(__FILE__) . '/connetti.php';
    
            ?>
       <!--         <script>                          
                   location.reload();
                       
                </script>
    -->
      <?php

return;
    
        } else { 




?>

<!--<table border="0" style="max-width:800px" width=80% cellpadding="14" cellspacing="4"> -->

<table style="max-width:700px" class="widefat fixed" cellspacing="0">

    <tr>
    <td colspan="3" bgcolor="FFFFFF">

<?php

/**
 *
 * Select last order ID
 *
 */

function get_last_order_id() 
{

    $query = new WC_Order_Query(
        array(
        'limit' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
        'return' => 'ids',
        )
    );

    $orders = $query->get_orders();

    if (!$orders) {

        echo "<p>nessun ordine presente</p>";

        return;

    }

    return ($orders[0]);
}

    $latest_order_id = get_last_order_id(); // Last order ID

if ($latest_order_id =='') {

?>

<div id="message" class="notice notice-error is-dismissible">
<p><b>Non sono presenti Ordini WooCommerce!</b></p>
</div>


<?php

exit;
}

    $args = array(
    'post_type' => 'shop_order',
    'posts_per_page' => 10,
    'post_status' => array_keys(wc_get_order_statuses())
    );

    $args2 = array(
        'type' => 'shop_order',
        'limit' => 10,
        'status' => 'any',
        );


?>



                Ordine <select name="woo_fattureincloud_order_id">

<?php

$orders = get_posts($args);

$orders2 = wc_get_orders( $args2 );

if (get_option('woo_fattureincloud_order_id') == null) {

?>

<option value="<?php echo $latest_order_id; ?>" selected="selected">Selezionato
: <?php echo $latest_order_id; ?></option>
<?php

} else {
?>
<option value="" selected="selected">
Selezionato <?php echo get_option('woo_fattureincloud_order_id'); ?></option>

<?php
}

foreach ($orders2 as $order) {

?>

<option value="<?php echo $order->get_id(); ?>">ID ordine : <?php echo $order->get_id(); ?></option>

<?php

}
?>

</select>

</form>

<select name="woo_fattureincloud_search_order_id" id="woo_fattureincloud_orders" disabled='disabled'>

<?php

if (!empty($_POST['search_order'])) {

?>

<option value="<?php echo $_POST['search_order']; ?>" selected="selected">#ordine : <?php echo $_POST['search_order']; ?></option>

<?php

}
?>

<option value="">Cerca Ordine (solo Premium)</option>
</select>

<button type="submit" name="submit" value="" class="button button-primary">Seleziona</button></td>

</tr>

<td colspan="3" bgcolor="FFFFFF" align="right">

<?php

}

###############################################################################

if (get_option('woo_fattureincloud_order_id') == null) {

    $id_ordine_scelto = $latest_order_id;

} else {

    $id_ordine_scelto = get_option('woo_fattureincloud_order_id');

}





    $order = wc_get_order($id_ordine_scelto);
    $order_note = $order->get_customer_note();
    $order_data = $order->get_data(); // The Order data
    $order_shipping_total = $order_data['shipping_total'];
    $order_shipping_tax = $order_data['shipping_tax'];
    $order_total = $order_data['total'];
    $order_total_tax = $order_data['total_tax'];
    $fattureincloud_iva = 22;
    $ivaDivisore = 1 + ($fattureincloud_iva / 100);
    $order_total_partial = $order_total / $ivaDivisore;
    $order_total_partial = round($order_total_partial, 2);
    $totale_iva_fattureincloud = $order_total - $order_total_partial;
    $totale_esclusaiva = $order_total  - $order_total_tax;

    /* BILLING INFORMATION: */

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
/*   compatibilità col plugin woo-piva-codice-fiscale-e-fattura-pdf-per-italia  */
//#######################################################################################################################


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
        }

    }


//########################################################################################################################    

} elseif ( !empty($order_billing_partiva ) || !empty($order_billing_codfis) || !empty($order_billing_emailpec) || !empty($order_billing_coddest))

{
    /*
    $order_billing_partiva = get_post_meta($id_ordine_scelto, '_billing_partita_iva', true);
    $order_billing_codfis = get_post_meta($id_ordine_scelto, '_billing_cod_fisc', true);
    $order_billing_emailpec = get_post_meta($id_ordine_scelto, '_billing_pec_email', true);
    $order_billing_coddest = get_post_meta($id_ordine_scelto, '_billing_codice_destinatario', true);
    */

    if (empty($order_billing_coddest) && empty($order_billing_emailpec)) {
        $order_billing_coddest = "0000000";

        if ($order_billing_country !== 'IT') {
            $order_billing_emailpec = "";
            $order_billing_coddest = "XXXXXXX";
        }

    }


} else {

    $order_billing_partiva ="";
    $order_billing_codfis = "";
    $order_billing_emailpec = "";
    $order_billing_coddest = "0000000";

}


//####################################################################################################################


if ( is_admin() ) {

    echo "<b>Destinatario</b> 
          <br><b>".__('Name', 'woo-fattureincloud')."</b> ".$order_billing_first_name." ".$order_billing_last_name.
         "<br><b>".__('Company', 'woo-fattureincloud')."</b> ".$order_billing_company.
         "<br><b>".__('Address', 'woo-fattureincloud')."</b> ".$order_billing_address_1.
         "<br><b>".__('City', 'woo-fattureincloud')."</b> ".$order_billing_city.
         "<br><b>".__('State', 'woo-fattureincloud')."</b> ".$order_billing_state.
         "<br><b>".__('Postal Code', 'woo-fattureincloud')."</b> ".$order_billing_postcode.
         "<br><b>".__('Email', 'woo-fattureincloud')."</b> ".$order_billing_email.
         "<br><b>".__('Email PEC', 'woo-fattureincloud')."</b> ".$order_billing_emailpec.
         "<br><b>".__('Codice Destinatario', 'woo-fattureincloud')."</b> ".$order_billing_coddest.
         "<br><b>".__('Phone number', 'woo-fattureincloud')."</b> ".$order_billing_phone.
         "<br><b>".__('Country', 'woo-fattureincloud')."</b> ".$order_billing_country.
         "<br><b>".__('Partita Iva', 'woo-fattureincloud')."</b> ".$order_billing_partiva.
         "<br><b>".__('Codice Fiscale', 'woo-fattureincloud')."</b> ".$order_billing_codfis.
         "<br><b>".__('Billing Method', 'woo-fattureincloud')."</b> ".$order_billing_method.
         "<br><b>".__('Payment Method code', 'woo-fattureincloud')."</b> ".$order_billing_payment_method.
         "<br><b>".__('Billing Note', 'woo-fattureincloud')."</b> ".$order_note.
         
        
         /* \"<pre>\".print_r($order_data).\"</pre>\". */

        "</td></tr>

            <tr>
            <td colspan=\"3\" bgcolor=\"FFFFFF\">
                          
             <b>Elenco Prodotti</b><hr>";


}

    /* Iterating through each WC_Order_Item_Product objects*/

foreach ($order->get_items() as $item_key => $item_values) {


        /* Using WC_Order_Item methods */

        /* Item ID is directly accessible from the $item_key in the foreach loop or */

        $item_id = $item_values->get_id();

        /* Using WC_Order_Item_Product methods */

        $item_name = $item_values->get_name(); // Name of the product
        $item_type = $item_values->get_type(); // Type of the order item ("line_item")

        $product_id = $item_values->get_product_id(); // the Product id
        $wc_product = $item_values->get_product(); // the WC_Product object
        $sku = $wc_product->get_sku();
        
        if (1 == get_option('show_short_descr') ) {
                $short_description_prdct = $wc_product->get_short_description();
            } else {

                $short_description_prdct = null;
            }

        /* Access Order Items data properties (in an array of values) */
        $item_data = $item_values->get_data();
        $_product = wc_get_product($product_id);

        $product_name = $item_data['name'];
        $product_id = $item_data['product_id'];
        $variation_id = $item_data['variation_id'];
        $quantity = $item_data['quantity'];
        $tax_class = $item_data['tax_class'];
        $line_subtotal = $item_data['subtotal'];
        $line_subtotal_tax = $item_data['subtotal_tax'];
        $line_total = $item_data['total'];
        $line_total_tax = $item_data['total_tax'];
        $prezzo_singolo_prodotto = $line_total/$quantity;
        $prezzo_singolo_prodotto = $prezzo_singolo_prodotto/ $ivaDivisore;
        $prezzo_singolo_prodotto = round($prezzo_singolo_prodotto, 2);
        $item_tax_class = $item_data['tax_class'];
        $order_vat_country =  $item_data['taxes']['total'];

        /*$tax_rate = array();*/
        /*$tax_rates = WC_Tax::get_rates( $_product->get_tax_class() );*/
        $tax_rates = WC_Tax::get_base_tax_rates($_product->get_tax_class(true));

        $mostra_percentuale_tasse = WC_Tax::get_rate_percent(key($order_vat_country));

   
if ( is_admin() ) {
        

     if (!empty($tax_rates)) {
        $tax_rate = reset($tax_rates);
        echo "aliquota iva ". round($tax_rate['rate'], 0)."%<br>".$item_tax_class."<br>";

        $aliquote_possibili_fic = array('0%', '22%');
        //print $mostra_percentuale_tasse;
        
        
    if (!in_array($mostra_percentuale_tasse, $aliquote_possibili_fic)) {
            echo '<span style="color:#FF0000";> ALIQUOTA IVA '.round($tax_rate['rate'], 0).' % NON abilitata in questa versione gratuita </span><br>';

            $type = 'warning';
            $message = __('ALIQUOTA IVA '.round($tax_rate['rate'], 0).' % NON abilitata in questa versione gratuita ', 'woo-fattureincloud');
            add_settings_error('woo-fattureincloud', esc_attr('settings_updated'), $message, $type);
            settings_errors('woo-fattureincloud');


    }


    } elseif (empty($tax_rates)) {
        $tax_rate = 0;
        echo "aliquota iva ".$tax_rate."%<br>";
    }

       // $_product = reset($_product);

        echo"<b>".__('Nome Prodotto', 'woo-fattureincloud')."</b> ". $product_name."<br>";

    if (!$sku) {
        $sku = "#" ; 
    }

        echo"<b>".__('SKU', 'woo-fattureincloud')."</b> ". $sku. "<br>" ; 

        $product = wc_get_product($product_id);
        /* echo $order_data['date_created']->date('d/m/Y')."<br>";*/
        echo "<b>".__('Price', 'woo-fattureincloud')."</b> ".$product->get_price_html()."<br>

        <b>".__('Description', 'woo-fattureincloud')."</b> ".$short_description_prdct."<br>

        <b>".__('Quantity', 'woo-fattureincloud')."</b> ".$quantity."<br>".
        "<b>".__('Sub Total', 'woo-fattureincloud')."</b> €".round($line_total, 2).

    "<hr>";

}

}

/*
* TAX Shipping
*
*
*/


    /* Initializing variables*/
    $tax_items_labels   = array(); // The tax labels by $rate Ids
    $shipping_tax_label = '';      // The shipping tax label

    /* 1. Loop through order tax items*/
foreach ( $order->get_items('tax') as $tax_item ) {

    //print_r($tax_item);

###############################################################


    /* Set the tax labels by rate ID in an array*/
    $tax_items_labels[$tax_item->get_rate_id()] = $tax_item->get_label();

    /* Get the tax label used for shipping (if needed)*/
    
    if (! empty($tax_item->get_shipping_tax_total()) ) { 
        $shipping_tax_label = $tax_item->get_label();
        //$shipping_tax_rate = $tax_item->get_shipping_tax_rates();
    }

    


}

    /* 2. Loop through order line items and get the tax label*/
foreach ( $order->get_items() as $item_id => $item ) {
    $taxes = $item->get_taxes();
    /* Loop through taxes array to get the right label*/
    foreach ( $taxes['subtotal'] as $rate_id => $tax ) {
        $tax_label = $tax_items_labels[$rate_id]; // <== Here the line item tax label
        /* Test output line items tax label*/
        /* echo '<pre>Item Id: '.$item_id.' | '; print_r($tax_label); echo '</pre>';*/
    }
}

// Test output shipping tax label
//	echo '<pre>Shipping tax label: '; print_r($shipping_tax_label); echo '</pre>';

#####################################################################

if ( is_admin() ) { 


echo    "</td>
                    </tr>
                    <tr>
                        <td colspan='3' align='right' bgcolor='FFFFFF'>
                        
                                             
                        <br><br><b>".__('Numero Ordine', 'woo-fattureincloud')."</b> ". $id_ordine_scelto.
                        "<br><b>".__('Costo Spedizione', 'woo-fattureincloud')."</b> ". $order_shipping_total.
                        "<br><b>".__('Tasse Spedizione', 'woo-fattureincloud'). "</b> ". $shipping_tax_label  ." = ". $order_shipping_tax.
                        "<br><br><b>".__('Totale iva esclusa', 'woo-fattureincloud')."</b> ".  $totale_esclusaiva.
                        "<br><b>".__('Imposte', 'woo-fattureincloud')."</b> ". $order_total_tax.
                        "<br><b>".__('Totale', 'woo-fattureincloud')."</b> ". $order_total;

?>


</td>
</tr>
<tr>
    <td colspan='3' align='right'>


<?php

if (get_option('woocommerce_prices_include_tax') == 'no') {
 
    if ('fatturaelettronica' == get_option('fattureincloud_send_choice') ) {

        echo "                 

        <form method=\"POST\">";

        if (0 == get_option('fattureincloud_paid') ) {  
                echo "<p>
						  <label for=\"woo-datepicker\">"
                          . __('Data di Scadenza', 'woo-fattureincloud').
                          "</label>
				          <input type=\"text\" id=\"woo-datepicker\" class=\"woo-datepicker\" 
				          name=\"woo-datepicker\" value=\"woo-datepicker\" size=\"10\">
					  </p>";
        }

        echo "

			    <button type=\"submit\" name=\"submit_send_fe_fattureincloud\" 
				value=\"Seleziona\" class=\"button button-primary\">
				Crea la Fattura Elettronica su Fattureincloud
                </button>
                </form>";


    } elseif ('fattura' == get_option('fattureincloud_send_choice') ) {

            echo "                 

			<form method=\"POST\">";

        if (0 == get_option('fattureincloud_paid') ) {  

            echo "<p>
						  <label for=\"woo-datepicker\">"
                          . __('Data di Scadenza', 'woo-fattureincloud').
                          "</label>
				          <input type=\"text\" id=\"woo-datepicker\" class=\"woo-datepicker\" 
				          name=\"woo-datepicker\" value=\"woo-datepicker\" size=\"10\">
					  </p>";
        }
            echo "

			    <button type=\"submit\" name=\"submit_send_fattureincloud\" 
				value=\"Seleziona\" class=\"button button-primary\">
				Crea la Fattura su Fattureincloud
                </button>
                </form>";

    }


} elseif (get_option('woocommerce_prices_include_tax') == 'yes') {

?>

    <button type="submit" name="submit_send_fattureincloud" value="Seleziona" 
    class="button button-primary" disabled>Crea la Fattura su Fattureincloud</button>
    <div id="message" class="notice notice-error">
        <p><b>Per utilizzare questo plugin è necessario impostare i 
        <a href="admin.php?page=wc-settings&tab=tax">prezzi al netto dell'imposta</a> |
        <a href="https://woofatture.com/documentazione/#nettoiva">Maggiori informazioni</a></b> </p>
    </div>

 
<?php

}

}

####################################################################################


if (isset($_POST['submit_send_fattureincloud'])) {

    $fatturaelettronica_fic = "false";

    $invoice_elet_type_wfic = false;

    $data_documento = date('Y-m-d');

    include plugin_dir_path(__FILE__) . '/prepare_to_send.php';


} elseif (isset($_POST['submit_send_fe_fattureincloud'])) {

    
    $fatturaelettronica_fic = "true";

    $invoice_elet_type_wfic = true;

    $data_documento = date('Y-m-d');
 
    include plugin_dir_path(__FILE__) . '/prepare_to_send.php';

    }
}

}
?>

</td>
</tr>
</table>
</div>