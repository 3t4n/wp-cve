<?php

require plugin_dir_path(__FILE__) . '/send_to_fattureincloud.php';

require plugin_dir_path(__FILE__) . '/error_cerca.php';


//if (empty($response_value['error'])) {



?>

<!--
        <div id="message" class="notice notice-success is-dismissible">

        <p><b>Creazione Riuscita!</b></p>
        </div>


-->
<?php



//} elseif (!empty($valore_paese_iso)) {

        if (!empty($valore_paese_iso)) {

?>
        <div id="message" class="notice notice-error is-dismissible">
        <p><b>Creazione non Riuscita: Paese del cliente non disponibile</b></p>
        </div>

   

<?php

} elseif (!empty($valore_iva)) {

?>
        <div id="message" class="notice notice-error is-dismissible">
        <p><b>Creazione non Riuscita</b>: Tipo Iva non abilitato, verifica che lo sia nella <b>
        <a href="https://woofatture.com/shop/woocommerce-fattureincloud-plugin-premium-1-year-updates/">
        Versione Premium</a></b></p>
        </div>

  
<?php

} elseif (!empty($valore_api_uid)) {

?>
        <div id="message" class="notice notice-error is-dismissible">
        <p><b>Creazione non Riuscita: Api Key mancanti o errate</b></p>
        </div>

   

<?php

} else { 

?>
   
   <!--
   <div id="message" class="notice notice-error is-dismissible">
        <p><b>Creazione Documento non Riuscita: 
-->

<?php

//        print($response_value['error']['message']);
?>
<!--
        <br>Per verificare che le Impostazioni siano giuste 
        <a href="admin.php?page=woo-fattureincloud&tab=impostazioni">clicca qui</a><br>
        Per maggiori informazioni <a href="https://woofatture.com/documentazione/">
        clicca qui</a></b></p>
        </div>
        -->
 

<?php

}