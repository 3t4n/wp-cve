<?php

// Don't access this directly, please

if (! defined('ABSPATH')) {
    exit;
}

if ( is_admin() ) {


/*
 *
 * Controllo ID azienda e mostra messaggio se manca
 *
 *
 */


/* 
if (get_option('wfic_id_azienda') == null ) {




        $type = 'warning';
        $message = __('Azienda ID mancante, verificare di aver selezionato l\'azienda e salvato le  <a href="admin.php?page=woo-fattureincloud&tab=impostazioni"> impostazioni</a>', 'woo-fattureincloud');
        add_settings_error('woo-fattureincloud-premium', esc_attr('settings_updated'), $message, $type);
        settings_errors('woo-fattureincloud');



}
*/





/*
 *
 * Controllo mancato invio automatico Fattura al cambio di stato Completato e mostra messaggio di errore
 *
 *
 */


if (get_option('fattureincloud_autosent_id_fallito')!='') {

?>
    <div id="message" class="notice notice-error">
    <p><b>Invio automatico ordine n <?php echo get_option('fattureincloud_autosent_id_fallito'); ?>  non Riuscito  <a href="https://woofatture.com/documentazione/">HELP</a></b><br>

<?php 

if (0 == get_option('fattureincloud_paid')) {

    echo  __('To send <b>automatically</b> invoices must be activated <b>paid invoice</b>', 'woo-fattureincloud');
}


?>

    <form method="POST">
    <input type="hidden" name="delete_autosave_fattureincloud" />
    <input type="submit" value="Cancella" class="button button-small ">
    </form>

    </p>
    </div>
    <?php 
} elseif ( get_option('fattureincloud_autosent_id_successo')!='') {


     ?>
    <div id="message" class="notice notice-success">
    <p><b>Invio automatico ordine n <?php echo get_option('fattureincloud_autosent_id_successo'); ?>  Riuscito!</a></b><br>

    <form method="POST">
    <input type="hidden" name="delete_autosave_fattureincloud_successo" />
    <input type="submit" value="Cancella" class="button button-small ">
    </form>

    </p>
    </div>
    <?php




}



// Code displayed before the tabs (outside)
// Tabs
?>
<!--<div id="top_fattureincloud"></div> -->

<div class="wrap woocommerce">
<h1>

<?php 



//echo __( 'WooCommerce Fattureincloud', 'woo-fattureincloud' );

$plugin_data = get_plugin_data(plugin_dir_path(__FILE__) .'../woo-fattureincloud.php', true, true);
$plugin_version = $plugin_data['Version'];
 
//if ( is_admin() ) {

echo __(
        'WooCommerce Fattureincloud '
        .$plugin_version, 'woo-fattureincloud'
    ); 
    
   


?>
</h1>
<h2>
<h2><a href="https://woofatture.com/documentazione/">Prima di utilizzare il plugin LEGGERE INTERAMENTE la Documentazione</a></h2>    

<?php

$tab = (! empty($_GET['tab'])) ? esc_attr($_GET['tab']) : 'ordine';
page_tabs($tab);

if ($tab == 'ordine') {

    include_once  plugin_dir_path(__FILE__) . 'ordine.php';

    // add the code you want to be displayed in the first tab ###
} elseif ($tab == 'fatture') {

    include_once plugin_dir_path(__FILE__) . 'fatture.php';

} elseif ($tab == 'connetti') {

    include_once plugin_dir_path(__FILE__) . 'connetti.php';

} else {
    // add the code you want to be displayed in the second tab

    include_once plugin_dir_path(__FILE__) . 'impostazioni.php';

} 

// Code after the tabs (outside)

}

?>