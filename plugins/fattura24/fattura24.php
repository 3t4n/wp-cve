<?php
/*
* Plugin Name: WooCommerce Fattura24
* Plugin URI: http://www.fattura24.com
* Author URI: http://www.fattura24.com
* Description: Create your invoices with Fattura24.com
* Version:  7.1.1
* WC tested up to: 8.5
* Text Domain: fattura24
* Domain Path: /languages
* Author: Fattura24.com
* License: GPL2
* License URI : http://www.gnu.org/licenses/gpl-2.0.html
*/

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

global $templates, $f24_pdc, $f24_sezionali;


if (! defined('F24_PLUGIN_FILE')) {
    define('F24_PLUGIN_FILE', __FILE__);
}

// File da importare anche lato frontend
$utiFiles = ['constants.php', 'behaviour.php', 'hooks.php', 'uty.php'];
foreach ($utiFiles as $file) {
    require_once __DIR__ . '/src/'. $file;
}

// HPOS compatibility
add_action('before_woocommerce_init', function(){
    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
        // compatibilità HPOS
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        // incompatibilità checkout blocks
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, false );

    }
});

if (is_admin()) {
    add_action('woocommerce_after_register_post_type', __NAMESPACE__ .'\fatt_24_get_ticket_data');
    require_once(ABSPATH . "wp-admin/includes/screen.php");
    
    if (fatt_24_isWooCommerceInstalled()) {
        require_once(plugin_dir_path(__FILE__) . '../woocommerce/woocommerce.php');
    }

    if (function_exists('get_current_screen')) {
        $screen = get_current_screen();
    }

    $filesToInclude = [
              'constants.php',
              'admin_scripts.php',
              'uty.php',
              'messages.php',
              'hooks.php',
              'settings.php',
              'tax.php',
              'app.php',
              'warning.php',
              'f24_api_info.php',
              'videoguides.php',
              'tickets.php',
              'deactivation.php'
            ];

    foreach ($filesToInclude as $file) {
        require_once __DIR__ . '/src/' . $file;
    }

    
    // codice eseguito solo lato admin

    add_action('admin_menu', function () {
        add_options_page(
            __('Settings Fattura 24', 'fattura24'),
            'Fattura24',
            'manage_options',
            FATT_24_SETTINGS_PAGE,
            __NAMESPACE__.'\fatt_24_show_settings'
        ); // schermata di impostazioni principale
        add_submenu_page(  // tab "Configurazione Tassa"
            '',
            'Settings Fattura 24',
            'Fattura24 Tax',
            'manage_options',
            'fatt-24-tax',
            __NAMESPACE__.'\fatt_24_show_tax'
        );
        add_submenu_page(// tab F24 Api Info
            '',
            'Settings Fattura 24',
            'F24 Api Info',
            'manage_options',
            'fatt-24-api-info',
            __NAMESPACE__.'\fatt_24_show_api_info'
        );
        add_submenu_page( // tab "Supporto"
            '',
            'Settings Fattura 24',
            'Support',
            'manage_options',
            'fatt-24-support',
            __NAMESPACE__.'\fatt_24_show_support'
        );
      
        add_submenu_page( // tab "Video guide"
            '',
            'Settings Fattura 24',
            'VideoGuides',
            'manage_options',
            'fatt-24-videos',
            __NAMESPACE__.'\fatt_24_show_videos'
        );
        add_submenu_page( // tab "App"
            '',
            'Settings Fattura 24',
            'Mobile App',
            'manage_options',
            'fatt-24-app',
            __NAMESPACE__.'\fatt_24_show_app'
        );       
        add_submenu_page( // tab "Attenzione"
            '',
            'Settings Fattura 24',
            'Warning',
            'manage_options',
            'fatt-24-warning',
            __NAMESPACE__.'\show_warning'
        );
    });

    add_filter('network_admin_plugin_action_links_'. plugin_basename(F24_PLUGIN_FILE), __NAMESPACE__ . '\fatt_24_settings_link');
    add_filter('plugin_action_links_'. plugin_basename(F24_PLUGIN_FILE), __NAMESPACE__ . '\fatt_24_settings_link');
    add_action('plugin_row_meta', __NAMESPACE__ .'\fatt_24_row_meta', 10, 2);
    add_action('admin_init', __NAMESPACE__.'\fatt_24_init_settings');
     // include script generazione PDF
    add_action('admin_enqueue_scripts', __NAMESPACE__ .'\fatt_24_admin_scripts');
   

    // link al sito fattura24.com, $page definisce un link interno
    function fatt_24_webase($page)
    {
        return 'http://www.fattura24.com/'.$page;
    }

    // con questo metodo includo nelle impostazioni (in tutte le tab) il logo F24. Attualmente $topic è sempre null, altrimenti verrebbe usato per l'intestazione h1
    function fatt_24_get_link_and_logo($topic = null, $ico = 'logo_orange')
    {
        echo fatt_24_div(array($topic ? fatt_24_h1($topic) : '',
             fatt_24_div(fatt_24_style(array('padding' =>'20px')), (fatt_24_img(fatt_24_attr('src', fatt_24_png('../assets/'.$ico)), array()))),
             ));
    }

    /**
     * In questo blocco creo le tabelle
     * le funzioni verranno chiamate da register_activation_hooks
     * Davide Iandoli 02.02.2021
     *
     * cfr: https://sudarmuthu.com/blog/how-to-properly-create-tables-in-wordpress-multisite-plugins/
     *
     * tabella tax
     */
    function fatt_24_create_tax_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
        $table_name = $prefix . 'fattura_tax';
        $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
            id int(11) NOT NULL AUTO_INCREMENT,
            tax_id int(11) NOT NULL DEFAULT '0',
            tax_code varchar(255) DEFAULT NULL,
            blog_id int(1) DEFAULT NULL,
            dt datetime DEFAULT NULL,
            mo_dt datetime DEFAULT NULL,
            extra_note varchar(255) DEFAULT NULL,
            PRIMARY KEY  (id)
            )$charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    // tabella log
    function fatt_24_create_log_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
        $new_table = $prefix . "f24_installation_log";
        $sql2 = "CREATE TABLE IF NOT EXISTS " .$new_table ." (
            id int(11) NOT NULL AUTO_INCREMENT,
            vers varchar(255) DEFAULT NULL,
            event_type varchar(500) DEFAULT NULL,
            event_date datetime DEFAULT NULL,
            PRIMARY KEY  (id)
            )$charset_collate;";
        dbDelta($sql2);
    }

    /**
     * Se aggiungo un nuovo sito in una rete già attiva,
     * devo aggiungere una tabella fattura_tax per gestire
     * correttamente la natura
     *
     * cfr : https://sudarmuthu.com/blog/how-to-properly-create-tables-in-wordpress-multisite-plugins/
     */

    /**
     * aggiungo messaggi di avviso lato admin
     * i metodi sono in messages.php
     */
    add_action('admin_notices', function () {

        $apiKey = get_option('fatt-24-API-key');
        $test = fatt_24_api_call('TestKey', array('apiKey' => $apiKey), FATT_24_API_SOURCE);
       
        if (is_array($test) && $test['code'] !== 200) {
            $message_displayed = $test['disp_message'];
            if ($message_displayed) {
                echo fatt_24_getMessageAPIError($test['code']);
            }
        }
        
        echo fatt_24_getAPINotSetMsg();
        echo fatt_24_getExtensionsErr();
        echo fatt_24_getMessageWooNotInstalled();
        echo fatt_24_getMessageWooFatturaInstalled();
        echo fatt_24_getMessageTaxNotEnabled();
        echo fatt_24_getMessageNoShippingRate();
        echo fatt_24_getMessageInvoiceDisabled();
        echo fatt_24_getNaturaMessages();
    });
   
} /*else {
    require_once 'src/hooks.php';
}*/

/**
 * Hook eseguiti in fase di attivazione plugin
 */
register_activation_hook(__FILE__, function () {
    global $wpdb;

    fatt_24_create_tax_table();
    fatt_24_update_tax_table();
    fatt_24_create_log_table();
    fatt_24_add_column_to_tax_table('used_for_shipping', 'INT', 1, 0);
    fatt_24_insert_installation_log('activation');

    // eventi particolari: (funzioni spostate in uty.php)
    fatt_24_update_actions();

    // con questo carico il textdomain fattura24 e gestisco le traduzioni in lingua in conformità con WPML
    function fatt_24_utilities()
    {
        load_plugin_textdomain('fattura24', false, basename(dirname(__FILE__)) . '/languages');
    }
    add_action('plugins_loaded', 'fatt_24_utilities');
});

register_deactivation_hook(__FILE__, function () {
    // aggiorno la tabella per sapere quando ho disattivato
    fatt_24_insert_installation_log('deactivation');
});

/**
 * Aggiungo il nuovo metodo per gestire la chiamata Ajax
 * che consente di registrare in wp_options il rating e la data del rating
 */
add_action('wp_ajax_hit_stars', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'rating_nonce')) {
        wp_die("page killed");
    }

    if ($_POST['value']) {
        $value = implode(' | ', $_POST['value']);
        $result = update_option('fatt-24-woo-rating', $value);
        if ($result) {
            wp_send_json(array(1, json_encode(esc_html__('Thanks for your review!', 'fattura24'))));
        }
    }
});

add_action('wp_ajax_test_key', function () {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'testkey_nonce')) {
        wp_die("page killed");
    }

    if ($_POST['apiKey']) {
        $value = $_POST['apiKey'];
        update_option('fatt-24-API-key', $value);
    }

});

// con questo metodo gestisco il download del file di log
add_action('wp_ajax_download_log', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'fatt_24_download_nonce')) {
        wp_die();
    }

    $data = sanitize_text_field($_POST['info']);
    $debugEnabled = WP_DEBUG === true || (int) get_option('fatt-24-log-enable') === 1;
    if ($debugEnabled) {
        $logFileName = fatt_24_getLogFileName();
        if ($f = @fopen($logFileName, 'a')) {
            fprintf($f, "\n%s\n", $data);
            fclose($f);
        }
        echo json_encode(array('t'=>1));
        exit();
    } else {
        echo json_encode(array('t'=>2));
        exit();
    }
});
