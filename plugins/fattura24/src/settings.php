<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * File di gestione delle impostazioni del plugin
 */

namespace fattura24;

global $templates, $f24_pdc, $f24_sezionali;

// con queste variabili riduco le chiamate API per i modelli e le liste sezionali
if (!defined('ABSPATH')) {
    exit;
}

$filesToInclude = [
    'settings_uty.php',
    'api/api_get_templates.php',
    'api/api_get_pdc.php',
    'api/api_get_numerators.php',
    'api/api_wrapper.php',
    'methods/met_get_templates.php',
    'methods/met_get_pdc.php',
    'methods/met_get_numerators.php',
    'methods/met_settings_pages.php',
    'methods/met_settings_sections.php',
];

foreach ($filesToInclude as $file) {
    require_once FATT_24_CODE_ROOT . $file;
}


if (is_admin()) {
    /**
    * Modifica del 26/08/2020:
    * ora il metodo ritorna l'intero array dei dati del plugin
    * per ottenere la versione => $plugin_data['Version'].
    * Modifica inserita per aggiungere il nome del plugin nei messaggi di errore
    * Davide Iandoli
    */

    // versione di woocommerce
    function fatt_24_woocommerce_version_check()
    {
        if (fatt_24_isWooCommerceInstalled()) {
            global $woocommerce;
            if (version_compare($woocommerce->version, '3.0.0', ">=")) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    // restituisce il messaggio a valle del controllo sulla versione del plugin
    function fatt_24_getVersionCheckMessage()
    {
        $pluginDate = '2020/12/09 16:00';
        $versionCheckMessage = '';
        $checkWooVersion = fatt_24_woocommerce_version_check(); // controllo la versione di woocommerce
        if (!$checkWooVersion) {
            $message = __('Warning: this plugin is not suitable for current WooCommerce version. Click ', 'fattura24');
            $message .= fatt_24_a(array('href' => 'https://www.fattura24.com/woocommerce-plugin-fatturazione', 'target' => '_blank'), __('here ', 'fattura24'));
            $message .= __('to download the correct version of the plugin', 'fattura24');
            $versionCheckMessage .= fatt_24_getMessageHtml($message, 'error', true);
        }
        return $versionCheckMessage;
    }

    // ottiene le informazioni sull'ambiente per includerle nel tracciato di log
    function fatt_24_getInfo()
    {
        global $woocommerce, $wpdb, $wp_version;
        
        return array(
            'PHP version' => PHP_VERSION,
            'MySQL version' => $wpdb->db_version(),
            'WordPress version' => $wp_version,
            'Multisite' => is_multisite() ? 'yes' : 'no',
            'Rtl' => is_rtl() ? 'yes' : 'no',
            'WP_MEMORY_LIMIT' => WP_MEMORY_LIMIT,
            'WP_MAX_MEMORY_LIMIT' => WP_MAX_MEMORY_LIMIT,
            'WP_DEBUG' => WP_DEBUG == 1 ? 'enabled' : 'disabled',
            'WP_DEBUG_LOG' => WP_DEBUG_LOG == 1 ? 'enabled' : 'disabled',
            'PHP memory_limit' => ini_get('memory_limit'),
            'WooCommerce Version' => $woocommerce->version,
            'WooCommerce Fattura24 Version' => FATT_24_PLUGIN_DATA['Version'],
            'Shop address' =>  get_permalink(wc_get_page_id('shop')),
            'Active theme' => wp_get_theme(),
            'Active plugins' => fatt_24_get_plugin_info()
        );
    }
}


// crea le sezioni della schermata di impostazioni principale e le gestisce
function fatt_24_init_settings()
{
    static $updated_settings;
    global $templates, $f24_pdc, $f24_sezionali;
    $updated_settings = fatt_24_get_installation_log('updated fe issue number default option');
    
    if (!$updated_settings) {
        fatt_24_default_sezionale_fe();
        fatt_24_insert_installation_log('updated fe issue number default option');
        $updated_settings = true;
    }

    $billing_cb_updated = fatt_24_get_installation_log('updated billing cb postmeta');
    if (!$billing_cb_updated) {
        fatt_24_update_actions();
    }

    $sect_key = fatt_24_info();
    $sect_addrbook = fatt_24_addrbook();
    $sect_orders = fatt_24_orders();
    $sect_invoices = fatt_24_invoices();
    $sect_optional_settings = fatt_24_optional_settings();
    $sect_logs = fatt_24_advanced();
    fatt_24_setup_settings_page(FATT_24_SETTINGS_PAGE, FATT_24_SETTINGS_GROUP, array($sect_key, $sect_addrbook, $sect_orders, $sect_invoices, $sect_optional_settings, $sect_logs));
}


// visualizza una specifica pagina di impostazioni
function fatt_24_show_settings()
{
    global $templates, $f24_pdc, $f24_sezionali;
    $templates = '';
    /**
    * Qui eseguo le chiamate API solo se ho la chiave nel campo di input
    * n.b.: il campo sarà sempre vuoto per le nuove installazioni
    * fix del 18.08.2020 Davide Iandoli
    */
    if (!empty(get_option(FATT_24_OPT_API_KEY))) {
        $templates = fatt_24_get_templates();
        // faccio le altre chiamate solo se il server risponde alla prima
        // se ho un array di risultati la chiamata è andata in errore
        if (!is_array($templates)) {
            $f24_pdc = fatt_24_get_pdc();
            $f24_sezionali = fatt_24_get_numerators();
        }
    }

    fatt_24_init_settings(); ?>
  
	<div class='wrap'>
    <!-- https://wordpress.stackexchange.com/questions/220650/how-to-change-the-location-of-admin-notice-in-html-without-using-javascript/220735 -->
    <h2></h2>
   
    <?php fatt_24_get_link_and_logo(__('', 'fattura24'));
    echo fatt_24_build_nav_bar(); ?>

   	<div>
        <table width="100%">
            <tr>
                <td>   
                    <div>
	                    <form method='post' action='options.php'>
     
	                    <?php
                            $hidden = is_array($templates) && $templates['code'] !== 200 ? 'hidden' : '';
    submit_button(__('Save Settings!', 'fattura24'), 'primary', 'submit_up', true, $hidden);
    settings_fields(FATT_24_SETTINGS_GROUP);
    do_settings_sections(FATT_24_SETTINGS_PAGE);
    submit_button(__('Save Settings!', 'fattura24'), 'primary', 'submit_down', true, $hidden); // different id for submit buttons?>
                        </form>
                    </div>
                </td>
                <td style="width:250px; vertical-align: top;">

  	                <?php
                           echo fatt_24_infobox('fatt-24-settings'); ?>
                </td>
            </tr>
        </table>
    </div>
	</div>
<?php
}
