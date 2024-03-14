<?php // Don't access this directly, please
if (!defined('ABSPATH')) exit;


/*
 *
 * Controllo ID azienda e se manca aggiunta di default della prima azienda presente
 *
 *
 */

if (get_option('wfic_id_azienda') == null ) {



/**
 *
 * Form for API Value API UID and API KEY
 *
 */

$url = "https://api-v2.fattureincloud.it/user/companies";

####################################

include plugin_dir_path(__FILE__) . '/retrive_data.php';

$json = json_decode($result, true);


if (!empty($json['error'])) { 

    error_log("connessione non attiva, attivo la procedura di riconnessione");

    include plugin_dir_path(__FILE__) . '/attiva_connessione.php';

    return;

}    


//print_r($json);
//echo "<pre>";
$id_azienda_default = ($json['data'])['companies']['0']['id'];
//echo "</pre>";

update_option('wfic_id_azienda', sanitize_text_field($id_azienda_default));



}

$url = "https://api-v2.fattureincloud.it/user/companies";

####################################

include plugin_dir_path(__FILE__) . '/retrive_data.php';

$json = json_decode($result, true);

if (is_array($json)) {

    if (!empty($json['error'])) { 

        include plugin_dir_path(__FILE__) . '/connetti.php';

        //echo "loading";
        
        ?>

        <!--
            <script>
                       
            location.reload();
        
            </script>
            -->

  <?php

               
    } else { 


        echo "<form method=\"POST\">";
        
        echo wp_nonce_field(); 

#########################################################################################

        if (empty(get_option('wfic_id_azienda'))) {
        
            echo "<p><span style=\"font-size:1.8em;\"> &#9888; </span>Selezionare l'Azienda e cliccare sul tasto 'Salva Azienda'" ; 
     
            } else {
    
                echo "<p> Azienda selezionata ID ". get_option('wfic_id_azienda'); 
    
            }


###########################################################################################

        echo '<select name="wfic_id_azienda">';
        

        ############################################################################

        foreach ($json as $value) {
    
            if (is_array($value)) {

                foreach ($value as $value2) {

                    if (is_array($value2)) {

                    $count = 0;

                        foreach ($value2 as $value3) {

                        //$count = $count + 1;

                       // print "Nome Azienda <b>".($value3)['name']."</b> ID Azienda <b>".$value3['id']."</b><hr>" ;
                        
                        echo '<option value="' . ($value3['id']) . '">' 
                        . ($value3)['name']. '</option>';
                       



                        if (get_option('wfic_id_azienda') == $value3['id']) {
                            echo '<option value="' . get_option('wfic_id_azienda') . '" selected>'. ($value3)['name'].' Azienda Selezionata</option>';

                        }


                        }

                    }

                }
    
            }

        }

        echo '</select>';
        ?>    <input type="submit" value="<?php echo __( 'Save Company', 'woo-fattureincloud' );?>" class="button button-primary button-large"
        onclick="window.location='admin.php?page=woo-fattureincloud&tab=impostazioni#setting-error-settings_updated';">
    </p>


        <?php


    } 



    echo "</form>";
    
    

    

}

############################################################################
#
# Verifica presenza e aggiunta Conto di Saldo
#
############################################################################


$company_ID =   get_option('wfic_id_azienda');

$wfic_token = get_option('wfic_api_key_fattureincloud');

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


#######################################################################


function in_array_conti_wfic($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_conti_wfic($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

#######################################################################


$result_wfic_conti_diff = [];

$conti_saldo_woocomm_wfic = array('Paypal','Stripe','Bonifico Bancario','Pagamento alla Consegna','Assegno','Gratis','Credit card / debit card','altro');



$conti_su_fic[] = "";



if (!empty($result_payment_list_fic['error']['message'])) {

    echo "<h3><span style=\"font-size:1.4em;float:left\"> &#9888; </span><b>Azienda Selezionata su Fattureincloud.it " .$result_payment_list_fic['error']['message']."</b></h3>";
    
    return;

}

foreach($result_payment_list_fic['data'] as $result) {

    $conti_su_fic[] = $result['name'];

}



if (!empty($conti_su_fic)) { 

$diff_wfic = array_diff($conti_saldo_woocomm_wfic , $conti_su_fic);

} 

if (!empty($diff_wfic)) { 

echo "<span style=\"font-size:1.4em;float:left\"> &#9888; </span>
<a href=\"https://secure.fattureincloud.it/settings-paymentaccounts\">Conti di saldo</a> di <b>WooCommerce</b> NON presenti in <b>Fattureincloud.it</b> : <br>";



foreach ($diff_wfic as $result_wfic_conti) {

    echo $result_wfic_conti." | " ;

    $result_wfic_conti_diff[] = $result_wfic_conti;

}

echo "<br>";

}


if (array_filter($conti_su_fic)) { 

echo "<span style=\"font-size:1.2em;\">  &#9210; </span><a href=\"https://secure.fattureincloud.it/settings-paymentaccounts\">Conti di saldo</a> presenti su <b>Fattureincloud.it</b>: <br>";


foreach($conti_su_fic as $nome_conto_su_wfic) {

    echo $nome_conto_su_wfic ." | ";
    
}
}


#############################################################################################################
##############################################################################################################

if (!empty($result_wfic_conti_diff) )


{ 



function crea_conti_wfic() {

    
      include_once plugin_dir_path(__FILE__) . '/crea_conti_saldo.php';
}

if(array_key_exists('crea_conti_wfic', $_POST)) {
    crea_conti_wfic();
}

       
?> 
<br>
<form method="post">
    <input type="submit" name="crea_conti_wfic"
        class="button" value="Crea Conti di Saldo di WooCommerce su fattureincloud.it" />
</form>
<br>
<?php




echo '<form method="post" action="">';

foreach($result_payment_list_fic['data'] as $result) {

    $conti_su_fic[] = $result['name'];

    $conti_e_id_fic [$result['id']] =  $result['name'];

####################################################################################################

    if (strtolower($result['name']) == "stripe" && $result['name'] !== "Stripe" ) { 

        $wfic_name_conto_tosend = "Stripe";

        //echo $result['name'] ." || ";
        //echo $result['id'];

        ?>
        <span style="font-size:1.4em;float:left"> &#9888; </span> <input type="submit" name="action" value="Correggi Stripe"/>
        <input type="hidden" id ="id"  name="id" value="<?php echo $result['id']; ?>"/>
        <?php  

if (isset($_POST['id'])) {
        
    $data_pre_conto_wfic = array ("data" => array(

        "id" => $_POST['id'],
        "name" => $wfic_name_conto_tosend

        ));

            
        $put_url = "https://api-v2.fattureincloud.it/c/".$company_ID."/settings/payment_accounts/".$_POST['id'];

        $data_to_put_wfic_postfields = json_encode($data_pre_conto_wfic);

        include plugin_dir_path(__FILE__) . '/put_data.php';
        
        header("Refresh:1");
        
        echo "...sending";
 
}

echo '</form>';

break;

####################################################################################################

}  elseif (strtolower($result['name']) == "paypal" && $result['name'] !== "Paypal" ) {

        $wfic_name_conto_tosend = "Paypal";

        ?>
        <span style="font-size:1.4em;float:left"> &#9888; </span> <input type="submit" name="action" value="Correggi Paypal"/>
        <input type="hidden" id ="id"  name="id" value="<?php echo $result['id']; ?>"/>
        <?php 

if (isset($_POST['id'])) {
        
    $data_pre_conto_wfic = array ("data" => array(

        "id" => $_POST['id'],
        "name" => $wfic_name_conto_tosend

        ));

            
        $put_url = "https://api-v2.fattureincloud.it/c/".$company_ID."/settings/payment_accounts/".$_POST['id'];

        $data_to_put_wfic_postfields = json_encode($data_pre_conto_wfic);

        include plugin_dir_path(__FILE__) . '/put_data.php';
        
        header("Refresh:1");
        
        echo "...sending";
 
}

echo '</form>';

break;

    }
####################################################################################################

    elseif (strtolower($result['name']) == "bonifico bancario" && $result['name'] !== "Bonifico Bancario" ) {

        $wfic_name_conto_tosend = "Bonifico Bancario";

        ?>
        <span style="font-size:1.4em;float:left"> &#9888; </span> <input type="submit" name="action" value="Correggi Bonifico Bancario"/>
        <input type="hidden" id ="id"  name="id" value="<?php echo $result['id']; ?>"/>
        <?php 

if (isset($_POST['id'])) {
        
    $data_pre_conto_wfic = array ("data" => array(

        "id" => $_POST['id'],
        "name" => $wfic_name_conto_tosend

        ));

            
        $put_url = "https://api-v2.fattureincloud.it/c/".$company_ID."/settings/payment_accounts/".$_POST['id'];

        $data_to_put_wfic_postfields = json_encode($data_pre_conto_wfic);

        include plugin_dir_path(__FILE__) . '/put_data.php';
        
        header("Refresh:1");
        
        echo "...sending";
 
}

echo '</form>';

break;

    }

    ####################################################################################################

    elseif (strtolower($result['name']) == "pagamento alla consegna" && $result['name'] !== "Pagamento alla Consegna" ) {

        $wfic_name_conto_tosend = "Pagamento alla Consegna";

        ?>
        <span style="font-size:1.4em;float:left"> &#9888; </span> <input type="submit" name="action" value="Correggi Pagamento alla Consegna"/>
        <input type="hidden" id ="id"  name="id" value="<?php echo $result['id']; ?>"/>
        <?php 
if (isset($_POST['id'])) {
        
    $data_pre_conto_wfic = array ("data" => array(

        "id" => $_POST['id'],
        "name" => $wfic_name_conto_tosend

        ));

            
        $put_url = "https://api-v2.fattureincloud.it/c/".$company_ID."/settings/payment_accounts/".$_POST['id'];

        $data_to_put_wfic_postfields = json_encode($data_pre_conto_wfic);

        include plugin_dir_path(__FILE__) . '/put_data.php';
        
        header("Refresh:1");
        
        echo "...sending";
 
}

echo '</form>';
   
break;

    }
 ####################################################################################################

    elseif (strtolower($result['name']) == "assegno" && $result['name'] !== "Assegno" ) {

        $wfic_name_conto_tosend = "Assegno";

        ?>
       <span style="font-size:1.4em;float:left"> &#9888; </span> <input type="submit" name="action" value="Correggi Assegno"/>
        <input type="hidden" id ="id"  name="id" value="<?php echo $result['id']; ?>"/>
        <?php 

if (isset($_POST['id'])) {
        
    $data_pre_conto_wfic = array ("data" => array(

        "id" => $_POST['id'],
        "name" => $wfic_name_conto_tosend

        ));

            
        $put_url = "https://api-v2.fattureincloud.it/c/".$company_ID."/settings/payment_accounts/".$_POST['id'];

        $data_to_put_wfic_postfields = json_encode($data_pre_conto_wfic);

        include plugin_dir_path(__FILE__) . '/put_data.php';
        
        header("Refresh:1");
        
        echo "...sending";
 
}

echo '</form>';

break;

    }
####################################################################################################

    elseif (strtolower($result['name']) == "gratis" && $result['name'] !== "Gratis" ) {

        $wfic_name_conto_tosend = "Gratis";

        ?>
       <span style="font-size:1.4em;float:left"> &#9888; </span>  <input type="submit" name="action" value="Correggi Gratis"/>
        <input type="hidden" id ="id" name="id" value="<?php echo $result['id']; ?>"/>
        <?php 

if (isset($_POST['id'])) {
        
    $data_pre_conto_wfic = array ("data" => array(

        "id" => $_POST['id'],
        "name" => $wfic_name_conto_tosend

        ));

            
        $put_url = "https://api-v2.fattureincloud.it/c/".$company_ID."/settings/payment_accounts/".$_POST['id'];

        $data_to_put_wfic_postfields = json_encode($data_pre_conto_wfic);

        include plugin_dir_path(__FILE__) . '/put_data.php';
        
        header("Refresh:1");
        
        echo "...sending";
 
}

echo '</form>';
       
break;

    }
####################################################################################################

    elseif (strtolower($result['name']) == "altro" && $result['name'] !== "altro" ) {

        $wfic_name_conto_tosend = "altro";

        ?>
       <span style="font-size:1.4em;float:left"> &#9888; </span>  <input type="submit" name="action" value="Correggi altro"/>
        <input type="hidden" id ="id"  name="id" value="<?php echo $result['id']; ?>"/>
        <?php 

        if (isset($_POST['id'])) {
        
    $data_pre_conto_wfic = array ("data" => array(

        "id" => $_POST['id'],
        "name" => $wfic_name_conto_tosend

        ));

            
        $put_url = "https://api-v2.fattureincloud.it/c/".$company_ID."/settings/payment_accounts/".$_POST['id'];

        $data_to_put_wfic_postfields = json_encode($data_pre_conto_wfic);

        include plugin_dir_path(__FILE__) . '/put_data.php';
        
        header("Refresh:1");
        
        echo "...sending";
 
}

echo '</form>';

break;

    }
    


}


}





############################################################################
#
# Verifica presenza e aggiunta Metodi di Pagamento
#
############################################################################


$ch_list_metod = curl_init();

curl_setopt($ch_list_metod, CURLOPT_URL, 'https://api-v2.fattureincloud.it/c/'.$company_ID.'/settings/payment_methods');
curl_setopt($ch_list_metod, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch_list_metod, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array(
    "Authorization: Bearer ".$wfic_token."",
    "Content-Type: application/json",
 );

curl_setopt($ch_list_metod, CURLOPT_HTTPHEADER, $headers);

$result_pay_met = curl_exec($ch_list_metod);
if (curl_errno($ch_list_metod)) {
    echo 'Error:' . curl_error($ch_list_metod);
}
curl_close($ch_list_metod);

$result_payment_methods_fic = json_decode($result_pay_met, true);


#######################################################################

function in_array_metodi_wfic($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_metodi_wfic($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

#######################################################################


$result_wfic_metodi_diff = [];

$metodi_pag_woocomm_wfic = array('Paypal','Stripe','Bonifico Bancario','Pagamento alla Consegna','Assegno','Gratis','Credit card / debit card','altro');


$metodi_su_fic[] ="";

foreach($result_payment_methods_fic['data'] as $result) {

    $metodi_su_fic[] = $result['name'];

    $metodo_predef_wfic = $result['is_default'];

    if ($metodo_predef_wfic == 1) { echo "<hr><p><span style=\"font-size:1.4em;float:left\"> &#9888; </span> 
        Rimuovere la spunta <b>PREDEFINITO</b> dal <a href=\"https://secure.fattureincloud.it/settings-paymentmethods\">Metodo di Pagamento <b>" . $result['name']."</a></b></p>";
    echo "<p>Se non viene rimossa la stella <span style=\"font-size:1.4em;\"> &#11088 </span> dal metodo di pagamento <b>PREDEFINITO</b> i metodi di pagamento non possono essere creati cliccando il tasto sottostante</p>";
    
    }

}

if (!empty($metodi_su_fic)) { 

$diff_wfic_m = array_diff($metodi_pag_woocomm_wfic , $metodi_su_fic);

} 


if (!empty($diff_wfic_m)) { 

echo "<hr><span style=\"font-size:1.4em;float:left\"> &#9888; </span><a href=\"https://secure.fattureincloud.it/settings-paymentmethods\">Metodi di Pagamento</a> di <b>WooCommerce</b> NON presenti in <b>Fattureincloud.it</b> : <br>";

}

foreach ($diff_wfic_m as $result_wfic_metodi) {

    echo $result_wfic_metodi." | " ;

    $result_wfic_metodi_diff[] = $result_wfic_metodi;

}

if (array_filter($metodi_su_fic)) { 

echo "<br><br><span style=\"font-size:1.2em;\">  &#9210; </span> <a href=\"https://secure.fattureincloud.it/settings-paymentmethods\">Metodi di Pagamento</a> presenti su <b>Fattureincloud.it</b>: <br>";


foreach($metodi_su_fic as $nome_metodo_su_wfic) {

    echo $nome_metodo_su_wfic ." | ";
    
} 

}





if (!empty($result_wfic_metodi_diff) )


{ 


function crea_metodi_wfic() {
    
    include_once plugin_dir_path(__FILE__) . '/crea_metodo_pagamento.php';
}

if(array_key_exists('crea_metodi_wfic', $_POST)) {
    crea_metodi_wfic();
}

       
?> 
<p>

<form method="post">
    <input type="submit" name="crea_metodi_wfic"
        class="button" value="Crea Metodi di Pagamento di WooCommerce su fattureincloud.it" />
    
</form>
</p>

<?php 



} 



############################################################################
#
# FINE     Verifica Conti di Saldo e  Metodi di Pagamento
#
############################################################################




##########################################################
#
#   Inizio impostazioni
#
###############################################################

echo "<form method=\"POST\">";

echo wp_nonce_field(); 

?>



<table border="0" cellpadding="12" cellspacing="9" class="form-table">

   <!-- #################### -->
   <tr>
   <td style="background-color:#288CCC;color:white;">
<?php
        echo __('La finalizzazione della <b>Fattura Elettronica</b> si esegue su Fattureincloud.it ', 'woo-fattureincloud'); 
?>
<a style="color:white" href="http://bit.ly/2EEAOK4"> 📺 VIDEO</a>
        </td>

   </tr>


</table>



<table border="0" style="max-width:880px;" cellpadding="12" cellspacing="16">


<!-- ################################################################# -->
<tr>

<td id="cella_numero_imp">1</td>

    <td bgcolor="white" width="33.3333%">
        
    <span class="dashicons dashicons-format-aside"></span> 

    <label for="fattureincloud_send_choice">
                <?php echo __('Abilita la creazione <b>manuale</b> di <br>', 'woo-fattureincloud');
                ?>
            </label>



    <input type="radio" id="fatturaelettronica_send_choice" name="fattureincloud_send_choice" value="fatturaelettronica"

        <?php
        if ('fatturaelettronica' == get_option('fattureincloud_send_choice')) {
            echo 'checked';
        } else {
            echo ''; 
        }
        ?> >

        <label for="contactChoice0">
            <?php echo __('FATTURA ELETTRONICA', 'woo-fattureincloud'); ?>
        </label>                

<br>

    <input type="radio" id="fattura_send_choice" name="fattureincloud_send_choice" value="fattura"

        <?php

        if ('fattura' == get_option('fattureincloud_send_choice')) {
            echo 'checked';
        } else {
            echo ''; 
        }
        ?> >
        
        <label for="contactChoice1">
            <?php echo __('FATTURA', 'woo-fattureincloud'); ?>
        </label>

<br>

    <input type="radio" id="ricevuta_disabled" name="fattureincloud_send_choice" value="ricevuta" disabled='disabled'>


        
        <label for="contactChoice1">
            <?php echo __('RICEVUTA*', 'woo-fattureincloud'); ?>
        </label>







    </td>

<!--################################################################################# -->

<td id="cella_numero_imp">2</td>

    <td bgcolor="white" width="33.3333%"> 

    <span class="dashicons dashicons-update"></span>
    <label for="fattureincloud_auto_save">
        <?php echo __('Quando l\'ordine è <b>Completato**</b> abilita la creazione in <b>Automatico</b> su Fattureincloud di <br>', 'woo-fattureincloud');
        ?>
    </label>

<br>

    <input type="radio" id="fatturaelettronica_auto_save" name="fattureincloud_auto_save" value="fatturaelettronica" disabled='disabled'>

    <label for="contactChoice0">
        <?php echo __('FATTURA ELETTRONICA ***', 'woo-fattureincloud'); ?>
    </label>
  
<br>    


    <input type="radio" id="ricevuta_fattureincloud_auto_save" name="fattureincloud_auto_save" value="ricevuta" disabled='disabled'>

    <label for="contactChoice2">
        <?php echo __('RICEVUTA ***', 'woo-fattureincloud'); ?>
    </label>
    
    
<br>
    
    <input type="radio" id="fattura_fattureincloud_auto_save" name="fattureincloud_auto_save" value="fattura"

        <?php

        if ('fattura' == get_option('fattureincloud_auto_save')) {
            echo 'checked';
        } else {
            echo ''; 
        }
        ?> >
    <label for="contactChoice1">
        <?php echo __('FATTURA', 'woo-fattureincloud'); ?>
    </label>


<br>


    <input type="radio" id="nulla" name="fattureincloud_auto_save" value= "nulla"

        <?php

        if ('nulla' == get_option('fattureincloud_auto_save')) {
            echo 'checked';
        } else {
            echo ''; 
        }

        ?>>

    <label for="contactChoice3">
        <?php echo __('NULLA', 'woo-fattureincloud'); ?>
    </label>

    </td>

<!-- ########################################################################################### -->

    <td id="cella_numero_imp">3</td>

    <td bgcolor="white" width="33.3333%"> 

    <span class="dashicons dashicons-cart"></span><b>[Obbligatorio non opzionale]</b>
    <label for="fattureincloud_paid"><?php echo __( 'Enable the creation of a <b>paid invoice</ b>', 'woo-fattureincloud' );?></label>
        
        <input type="hidden" name="fattureincloud_paid" value="0" />
        <input type="checkbox" name="fattureincloud_paid" id="fattureincloud_paid" value="1" onclick="return false" checked
        <?php
        if (1 == get_option('fattureincloud_paid')) {
            echo 'checked';
        } else {
            echo '';
        }
        
        ?>>
    </td>

</tr>

<!-- ################################################################# -->

<tr>
<td id="cella_numero_imp">.</td>
<td style="background-color:#208CB3;color:white;a{color:#FFFFFF;}" width="33.3333%" align="center">
<?php echo __('*<b>RICEVUTA</b> solo nella', 'woo-fattureincloud'); ?>
<br><b><a style="color:white;" href="https://woofatture.com/shop/">Versione Premium</a></b>

</td>

<td id="cella_numero_imp">.</td>

<td style="background-color:#208CB3;color:white;link:white;" width="33.3333%">


<?php echo __('**oltre a <b>Completato</b> nella
<a style="color:white;" href="https://woofatture.com/shop/">
<b>Versione  Premium</b></a> è possibile impostare la creazione automatica 
quando l\'ordine si trova in modalità <br>
<b>IN LAVORAZIONE</b> oppure <br>
<b>IN SOSPESO</b>', 'woo-fattureincloud'); ?>

<?php echo __('<hr>***<b>FATTURA ELETTRONICA</b> e ***<b>RICEVUTA</b><br>automatica solo nella', 'woo-fattureincloud'); ?>
<br><b><a style="color:white;" href="https://woofatture.com/shop/">Versione Premium</a></b>

</td>

<td id="cella_numero_imp">4</td>

<td bgcolor="#e5e5e5" width="33.3333%" align="center">

<span class="dashicons dashicons-list-view"></span><br>

<label for="activate_customer_receipt">
            <?php echo __('Activate <br><b>tax receipt no invoice</b><br> choice in checkout', 'woo-fattureincloud');
            ?></label><br>

            <input type="hidden" name="activate_customer_receipt" value="0" />
            <input type="checkbox" name="activate_customer_receipt" id="activate_customer_receipt" value="1" 
            <?php
            if (1 == get_option('activate_customer_receipt') ) {
                echo 'checked';
            } else {
                echo '';
            }

            ?>>



</td>


    </tr>

<!-- ################################################################# -->


<tr>

<td id="cella_numero_imp">5</td>
<td bgcolor="#e5e5e5" width="33.3333%">

<span class="dashicons dashicons-id-alt"></span>

<label for="update_customer_registry">
            <?php echo __('Update the customer registry with the <b>personal data of the invoice</b> 
            Since API 2.0 introduction this value is <b>required</b>', 'woo-fattureincloud');
            ?></label>

            <!-- <input type="hidden" name="update_customer_registry" value="0" /> -->
            <input type="checkbox" name="update_customer_registry" id="update_customer_registry" onclick="return false" checked 
            <?php
            /*
            if (1 == get_option('update_customer_registry') ) {
                echo 'checked';
            } else {
                echo '';
            }
            */

            ?>>
</td>

<td id="cella_numero_imp">6</td>

<td bgcolor="#e5e5e5" width="33.3333%">
<span class="dashicons dashicons-media-text"></span>
<label for="show_short_descr"><?php echo __('show in the invoice <b>short description</b> product', 'woo-fattureincloud');
            ?></label>

            <input type="hidden" name="show_short_descr" value="0" />
            <input type="checkbox" name="show_short_descr" id="show_short_descr" value="1"
            <?php
            if (1 == get_option('show_short_descr') ) {
                echo 'checked';
            } else {
                echo '';
            }

            ?>>
</td>

<td id="cella_numero_imp">7</td>

<td bgcolor="#e5e5e5" width="33.3333%">

<span class="dashicons dashicons-list-view"></span>

<label for="fattureincloud_partiva_codfisc">
        <?php echo __(
            '<b>[Obbligatorio non opzionale]</b> Attiva le voci nel checkout di <b>Partita Iva</b>, 
            <b>Codice Fiscale</b> (Solo se l\'indirizzo è italiano),<br> 
            <b>PEC</b> (per Fattura Elettronica), <b>Codice Destinatario</b> (per Fattura Elettronica)', 'woo-fattureincloud'
        );
        ?></label>

    <input type="hidden" name="fattureincloud_partiva_codfisc" value="1" />
    <input type="checkbox" name="fattureincloud_partiva_codfisc" id="fattureincloud_partiva_codfisc" value="1" onclick="return false" checked 
    <?php
/*    if (1 == get_option('fattureincloud_partiva_codfisc') ) {
        echo 'checked';
        
    } else {
        echo '';
    } 
*/
    ?>>
</td>
</tr>


<!-- ################################################################# -->

<tr>
    <td align="right" colspan="6">
   
    <input type="submit" value="<?php echo __( 'Save Settings', 'woo-fattureincloud' );?>" class="button button-primary button-large"
    onclick="window.location='admin.php?page=woo-fattureincloud&tab=impostazioni#setting-error-settings_updated';">

</form>

</td>
</tr>

<!-- ################################################################# -->

<tr>
    <td colspan="6">

    Si consiglia di <b>verificare con molta attenzione</b> <br>che i dati per la Fattura Elettronica inviati a Fatureincloud siano corretti, <br>
        Gli autori del plugin declinano ogni responsabilità<br> per eventuali errori o mancanze nella generazione della Fattura Elettronica<br><br>

        I codici del tipo di pagamento (prelevati dallo SDI) con cui viene pre-compilata<br>
         la Fattura Elettronica sono :<br>
        <i>
         <ol>
            <li>    <b>MP08</b> carta di pagamento (carta di credito e PayPal)</li>
            <li>    <b>MP02</b> assegno bancario</li>
            <li>    <b>MP05</b> bonifico bancario</li>
            <li>    <b>MP01</b> contanti (pagamento in contrassegno)</li>
        </ol>    
        </i>
         se è stato utilizzato un <b>altro tipo di pagamento</b> è necessario modificarlo <b>direttamente su Fattureincloud.it</b> 


    </td>

</tr>




</table>



<a name="fic_premium"></a>
<p>Compra la <b><a href="https://woofatture.com/shop/">Versione Premium</a></b>! </p>




<div id="promo_premium">
</div>




<table class="wp-block-table"bgcolor="#FFF" cellspacing="0" cellpadding="10" class="form-table">
<tbody>
    <tr>
        <td>&nbsp;</td>
        <td>Versione<br> Gratuita </td>
        <td><b> Versione<br> Premium</b></td>
        <td> <b>Versione Premium</b><br>
        Aliquote Iva 0%
        </td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">iva 22%</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt" style="color: green;"></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt" style="color: green; "></span></td>
        <td rowspan="27" style="padding:30px;vertical-align: top;" valign="top"><p>Nella versione <b>Premium</b> è possibile impostare<br>
        <b>44</b> tipologie specifiche di <b>Aliquota Iva = 0%</b><br>
        aggiungendola col rispettivo nome <b>Zero Rate</b> + <b>numero</b> in <br>
        <i>WooCommerce > Impostazioni > Imposta > Aliquote addizionali</i><br>
        maggiori informazioni nella <a href="https://woofatture.com/docs/42-tipi-specifici-di-iva-0/" target="blank">documentazione</a>
        </p>

<li><strong>Zero Rate 7</strong> = Regime dei minimi</li>
<li><strong>Zero Rate 9</strong> = Fuori campo IVA</li>
<li><strong>Zero Rate 10</strong> = Oper. non soggetta, art.7 ter</li>
<li><strong>Zero Rate 11</strong> = Inversione contabile, art.7 ter</li>
<li><strong>Zero Rate 12</strong> = Non Imponibile</li>
<li><strong>Zero Rate 13</strong> = Non Imp. Art.8</li>
<li><strong>Zero Rate 14</strong> = Non Imp. Art.9 1C</li>
<li><strong>Zero Rate 15</strong> = Non Imp. Art.14 Legge 537/93</li>
<li><strong>Zero Rate 16</strong> = Non Imp. Art.41 D.P.R. 331/93</li>
<li><strong>Zero Rate 17</strong> = Non Imp. Art.72, D.P.R. 633/72</li>
<li><strong>Zero Rate 18</strong> = Non Imp. Art.74 quotidiani/libri</li>
<li><strong>Zero Rate 19</strong> = Escluso Art.10</li>
<li><strong>Zero Rate 20</strong> = Escluso Art.13 5C DPR 633/72</li>
<li><strong>Zero Rate 21</strong> = Escluso Art.15</li>
<li><strong>Zero Rate 22</strong> = Rev. charge art.17</li>
<li><strong>Zero Rate 23</strong> = Escluso Art.74 ter D.P.R. 633/72</li>
<li><strong>Zero Rate 24</strong> = Escluso Art.10 comma 1</li>
<li><strong>Zero Rate 25</strong> = Escluso Art.10 comma 20</li>
<li><strong>Zero Rate 26</strong> = Non Imp. Art.9</li>
<li><strong>Zero Rate 27</strong> = Escluso Art.10 n.27 D.P.R 633/72</li>
<li><strong>Zero Rate 30</strong> = Regime del margine art.36 41/95</li>
<li><strong>Zero Rate 31</strong> = Escluso Art.3 comma 4 D.P.R 633/72</li>
<li><strong>Zero Rate 32</strong> = Escluso Art.15/1c D.P.R 633/72</li>
<li><strong>Zero Rate 33</strong> = Non imp. Art.8/c D.P.R. 633/72</li>
<li><strong>Zero Rate 34</strong> = Non Imp. Art.7 ter”</li>
<li><strong>Zero Rate 35</strong> = Escluso Art.7 D.P.R 633/72</li>
<li><strong>Zero Rate 37</strong> = Escluso Art.10 comma 9</li>
<li><strong>Zero Rate 38</strong> = Non imp. Art.7 quater DPR 633/72</li>
<li><strong>Zero Rate 39</strong> = Non Imp. Art.8 comma 1A</li>
<li><strong>Zero Rate 42</strong> = Non Imp. Art.2 comma 4 D.P.R 633/72</li>
<li><strong>Zero Rate 43</strong> = Non Imp. Art.18 633/72</li>
<li><strong>Zero Rate 44</strong> = Fuori Campo IVA Art.7 ter D.P.R 633/72</li>
<li><strong>Zero Rate 45</strong> = Non Imp. Art.10 n.18 DPR 633/72</li>
<li><strong>Zero Rate 46</strong> = Esente Art.10 DPR 633/72</li>
<li><strong>Zero Rate 47</strong> = Non imp. art.1 L. 244/2008</li>
<li><strong>Zero Rate 48</strong> = Non imp. art.40 D.L. 427/93</li>
<li><strong>Zero Rate 49</strong> = Non imp. art.41 D.L. 427/93</li>
<li><strong>Zero Rate 50</strong> = Non imp. art.71 DPR 633/72</li>
<li><strong>Zero Rate 51</strong> = Non imp. art.8 DPR 633/72</li>
<li><strong>Zero Rate 52</strong> = Non imp. art.9 DPR 633/72</li>
<li><strong>Zero Rate 53</strong> = Regime minimi 2015</li>
<li><strong>Zero Rate 66</strong> = Contribuenti forfettari</li>


(i salti nella numerazione non sono un errore) 
        
        
        </td>
    </tr>
    <tr>
        <td style="text-align: center"> iva 0%</td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt" style="color: green; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">iva 24%</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-dismiss" style="color: red; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">iva 23%</td>
        <td style="text-align: center"><span class="dashicons dashicons-dismiss" style="color: red; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">iva 21%</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-dismiss" style="color: red; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">iva 20%</td>
        <td style="text-align: center"><span class="dashicons dashicons-dismiss" style="color: red; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">iva 10%</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-dismiss" style="color: red; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">iva 4%</td>
        <td style="text-align: center"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">iva 5%</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">iva 8%</td>
        <td style="text-align: center"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">Selezione ultimi 10 ordini</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">Ricerca ordine</td>
        <td style="text-align: center"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">Crea Fattura</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">Crea Fattura Elettronica</td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">Crea Ricevuta</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">Invia Fattura via email</td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">Creazione Automatica Fattura</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">Creazione Automatica<br>Fattura Elettronica </td>
        <td style="text-align: center"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">Creazione Automatica<br>Ricevuta</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">Invia Ricevuta via email</td>
        <td style="text-align: center"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">Invia Fattura Elettronica<br>(copia cortesia) via email</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">Aggiungere voce CF e pIva</td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">Invio Email Automatico</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">Iva 0% personalizzabile</td>
        <td style="text-align: center"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">Sezionali</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">Codice Fiscale obbligatorio<br> opzionale</td>
        <td style="text-align: center"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">Descrizione completa prodotto</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center">Disabilita creazione documento<br>se importo ordine uguale a zero</td>
        <td style="text-align: center"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
    <tr>
        <td style="text-align: center;background-color: #e3e3e3;">Abilita differenti aliquote<br> nei prodotti variabili</td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-dismiss aligncenter" style="color: red; "></span></td>
        <td style="text-align: center;background-color: #e3e3e3;"><span class="dashicons dashicons-yes-alt aligncenter" style="color: green; "></span></td>
    </tr>
</tbody>
</table>