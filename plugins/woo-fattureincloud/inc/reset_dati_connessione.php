<?php

delete_option('wfic_device_code');

delete_option('wfic_api_key_fattureincloud');

delete_option('wfic_refresh_token');

delete_option('wfic_id_azienda');


if ( (empty(get_option('wfic_device_code'))) &&
 (empty(get_option('wfic_api_key_fattureincloud'))) && 
 (empty(get_option('wfic_refresh_token'))) &&
 empty(get_option('wfic_id_azienda'))) {

echo "<b><h3>RESETTING...</h3></b>";

/*echo "<p><b>I dati della connessione precedente</b> sono stati <b>correttamente rimossi</b><br>
 è possibile creare una <b><a href='admin.php?page=woo-fattureincloud&tab=connetti' class='button' >Nuova connessione</a></b></p>";
*/
}

