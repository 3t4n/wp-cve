<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * Descrizione: gestisce componenti di debug, helpers per array e helpers html
 *
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Con questo metodo aggiungo una colonna al db
 * usando i parametri nella query (nome colonna, tipo dati etc.)
 */
function fatt_24_add_column_to_tax_table($column_name, $type, $len, $default = null) {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
    $table_name = $prefix . 'fattura_tax';
    // aggiungo la colonna blog_id alla tabella, se non esiste
    $columns = $wpdb->get_col("DESC {$table_name}", 0);
    $new_column_set = array_search($column_name, $columns);
    if (!$new_column_set) {
        $query = "ALTER TABLE `{$table_name}` ADD `{$column_name}` $type($len) 
                    NOT NULL DEFAULT {$default} AFTER tax_code";
        $wpdb->query($query);
    }
}

function fatt_24_update_tax_table()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
    $table_name = $prefix . 'fattura_tax';
    // aggiungo la colonna blog_id alla tabella, se non esiste
    $columns = $wpdb->get_col("DESC {$table_name}", 0);
    $blog_id_column_set = array_search('blog_id', $columns);
    if (!$blog_id_column_set) {
        $wpdb->query("ALTER TABLE " .$table_name. " ADD blog_id INT(1) AFTER tax_code");
    }
}

function fatt_24_update_actions()
{
    $optionsToUpdate = [
    'updated billing cb postmeta' ,   
    'updated old wc orders postmeta',
    'updated order creation options',
    'updated fattura24 tax settings',
    'updated fattura24 tax table',
    'updated invoice creation options',
    'updated fe issue number default option',
   ];

    foreach ($optionsToUpdate as $option) {
        $result = fatt_24_get_installation_log($option);

        if (!$result) {
            switch ($option) {
                case 'updated billing cb postmeta':
                    fatt_24_update_old_billing_cb_postmeta();
                    break;
                case 'updated old wc orders postmeta':
                    // fatt_24_update_old_postmeta(); /* con HPOS non serve più */
                    break;
                case 'updated order creation options':
                    is_null(fatt_24_get_old_options(FATT_24_ORD_CREATE)) ? '0' : '1';
                    break;
                case 'updated fattura24 tax settings':
                    fatt_24_update_tax_configuration();
                    break;
                case 'updated fattura24 tax table':
                    fatt_24_update_tax_table();
                    fatt_24_update_tax_configuration();
                    break;
                case 'updated invoice creation options':
                    fatt_24_update_invoice_options();
                    break;
                case 'updated fe issue number default option':
                    fatt_24_default_sezionale_fe();
                    break;
                default:
                    break;
           }
        }
       
        $optionUpdated = fatt_24_get_installation_log($option);
        if (!$optionUpdated) {
            fatt_24_insert_installation_log($option);
        }
    }
}

// metodo per sapere se il calcolo delle tasse è abilitato
/**
 * Davide Iandoli edit del 15.09.2021
 * Aggiunto controllo sulle aliquote già configurate in WooCommerce
 * La presenza di aliquote già configurate nel db prevale sul flag 'abilita il calcolo'
 *
 */
function fatt_24_wc_calc_tax_enabled()
{
    global $wpdb;
    $prefix = $wpdb->prefix;
    $table_name = $prefix . 'woocommerce_tax_rates';
    $sql="SELECT * from $table_name";
    $tax_rates_set = !empty($wpdb->get_results($sql));
    $tax_enabled = get_option('woocommerce_calc_taxes') === 'yes';
    // se ci sono aliquote configurate  è vero, altrimenti è vero solo se entrambe le condizioni si verificano
    $result = ($tax_rates_set ? true : $tax_enabled && $tax_rates_set) ? true : false;

    return $result;
}

function fatt_24_shippingTaxDisabled()
{
    return get_option('woocommerce_ship_to_countries') === 'disabled';
}

// WooCommerce è installato?
function fatt_24_isWooCommerceInstalled()
{
    return class_exists('WooCommerce');
}

// WooCommerce Fattura24 (Zanca) installato?
function fatt_24_isWooFatturaInstalled()
{
    return class_exists('woo_fattura24');
}

/**
 * Metodo per controllare se ci sono aliquote configurate
 * per la spedizione
 */
function fatt_24_existingShippingTaxes()
{
    global $wpdb;
    $prefix = $wpdb->prefix;
    $sql = "SELECT * from ".$prefix."woocommerce_tax_rates where tax_rate_shipping = 1";
    $shippingRates = $wpdb->get_results($sql);
    return count($shippingRates) > 0;
}

/**
 * Con questa funzione recupero dal db le aliquote 0% dal db
 * da notare che le restituisco solo se l'opzione selezionata
 * in 'crea documento fiscale' è fattura elettronica
 * utilizzo questa stessa funzione anche in settings.php
 * per visualizzare il messaggio di errore anche nella schermata principale
 * Davide Iandoli - spostata in data 17.05.2021
 *
 * Attenzione: in ambiente multisito WooCommerce aggiunge tante tabelle per quanti
 * sono i siti figli: per questo uso $wpdb->prefix: dovrò interrogare la tabella corretta
 *
 */
function fatt_24_getZeroRates()
{
    global $wpdb;
    $prefix = $wpdb->prefix;
    $blog_id = is_multisite() ? get_current_blog_id() : 1;
    $zero_rate = 0.0;
    $sql = "SELECT * from ".$prefix."woocommerce_tax_rates where tax_rate = " . $zero_rate .  " order by tax_rate_id desc"; // la query ora controlla la percentuale e non la classe"
    $tax_rate = $wpdb->get_results($sql);
    return $tax_rate;
}

// con questo metodo ottengo l'orario attuale utilizzando il fuso orario di Roma
function fatt_24_now($fmt = 'Y-m-d H:i:s', $tz = 'Europe/Rome')
{
    $timestamp = time();
    $dt = new \DateTime("now", new \DateTimeZone($tz)); //il primo param dev'essere una stringa
    $dt->setTimestamp($timestamp); //ottiene il timestamp attuale
    return $dt->format($fmt);
}

/*function fatt_24_plugin_data() {
    $plugin_data = get_file_data(plugin_dir_path(__FILE__).'../fattura24.php',
                                 array('Name' => 'Plugin Name',
                                       'Version' => 'Version'));
    return $plugin_data;
}*/
/**
 * Con questo metodo costruisco il nome del file di log - un file al giorno
 * Se non esiste lo crea e chiama la funzione che cancella i vecchi files
 * Davide Iandoli 14.09.2020
 */
function fatt_24_getLogFileName()
{
    $path = plugin_dir_path(__FILE__);
    $today = date('d_m_Y');
    $filename = $path.'f24_trace_'.$today.'.log';
    if (!file_exists($filename)) {
        fatt_24_delOldLogFiles();
    }
    return $filename;
}

/**
 * Metodo chiamato una volta al giorno
 * Elimina file più vecchi di 30 giorni,
 * compreso il vecchio trace.log
 * edit del 23.07.2020
 */
function fatt_24_delOldLogFiles()
{
    $path = plugin_dir_path(__FILE__);
    // cfr: https://www.php.net/manual/en/class.datetime.php
    $today = \DateTime::createFromFormat('d_m_Y', date('d_m_Y'));
    foreach (glob($path. '*.log') as $file) {
        $creationDay = \DateTime::createFromFormat('d_m_Y', date('d_m_Y', filectime($file)));
        $result = $creationDay->diff($today);
        $different_month = $result->m;
        $different_days = $result->d;
        if ($different_days > 30) {
            unlink($file);
        }
    }
}

function fatt_24_trace()
{
    $logfileName = fatt_24_getLogFileName();
    if ($f = @fopen($logfileName, 'a')) {
        fprintf($f, "%s: %s\n", fatt_24_now(), var_export(func_get_args(), true));
        fclose($f);
    }
}

// tracciato specifico per le funzioni
function fatt_24_ftrace()
{
    if (true) {
        $args = func_get_args();
        $file = $args[0];
        $path_parts = pathinfo($file);
        $exclude = array();

        if (array_search($path_parts['filename'], $exclude) === false) {
            if ($f = @fopen(plugin_dir_path($file).'trace.log', 'a')) {
                fprintf($f, "%s: %s\n", fatt_24_now(), var_export(array_slice($args, 1), true));
                fclose($f);
            }
        }
    }
}

// gestisco errori fatali
function fatt_24_fatal($why)
{
    fatt_24_trace('fatal', $why, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
    throw new \Exception('fatal error:'.$why);
}

// mappa chiavi array
function fatt_24_array_map_kv($f, array $a)
{
    return array_map(function ($k) use ($f, $a) {
        return $f($k, $a[$k]);
    }, array_keys($a));
}

// mappa valori array
function fatt_24_array_map_values($f, array $a)
{
    return array_values(array_map($f, $a));
}

function fatt_24_array_map_kv_values($f, array $a)
{
    return array_values(fatt_24_array_map_kv($f, $a));
}

// converte array in stringa
function fatt_24_array_string($m, $sep = '')
{
    return is_array($m) ? implode($sep, $m) : $m;
}

function fatt_24_array2string($param) {
    $result = '';
    array_walk_recursive($param, function ($v, $k) use (&$result) {
            $result .= '[' . $k . '] => ' . $v . PHP_EOL;
        }
    );
    return $result;
}

// con questi metodi costruisco i tag html
function fatt_24_attr($k, $v)
{
    return array( $k => $v );
}
function fatt_24_align($pos)
{
    return fatt_24_attr('text-align', $pos);
}
function fatt_24_klass($klass)
{
    return fatt_24_attr('class', $klass);
}
function fatt_24_title($title)
{
    return fatt_24_attr('title', $title);
}
function fatt_24_id($id)
{
    return fatt_24_attr('id', $id);
}
function fatt_24_style(array $properties)
{
    return fatt_24_attr('style', implode(';', fatt_24_array_map_kv(function ($k, $v) {
        return is_int($k) ? $v : "$k:$v";
    }, $properties)));
}

function fatt_24_attributes(array $attrs)
{
    return fatt_24_array_map_kv(function ($k, $v) {
        return is_int($k) ? $v : "$k=\"$v\"";
    }, $attrs);
}

/**
 * Costruisce tag html ben formati
 */
function fatt_24_tag($tag, $A1, $A2)
{
    if (isset($A2)) { // $A1: attributes
        if (is_array($A1)) {
            $A1 = fatt_24_array_string(fatt_24_attributes($A1), ' ');
        }
        return sprintf('<%s %s>%s</%s>', $tag, $A1, fatt_24_array_string($A2, "\n"), $tag);
    }
    return sprintf('<%s>%s</%s>', $tag, fatt_24_array_string($A1, "\n"), $tag);
}

function fatt_24_a($A1, $A2 = null)
{
    return fatt_24_tag('a', $A1, $A2);
}
function fatt_24_b($A1, $A2 = null)
{
    return fatt_24_tag('b', $A1, $A2);
}
function fatt_24_i($A1, $A2 = null)
{
    return fatt_24_tag('i', $A1, $A2);
}
function fatt_24_p($A1, $A2 = null)
{
    return fatt_24_tag('p', $A1, $A2);
}
function fatt_24_th($A1, $A2 = null)
{
    return fatt_24_tag('th', $A1, $A2);
}
function fatt_24_tr($A1, $A2 = null)
{
    return fatt_24_tag('tr', $A1, $A2);
}
function fatt_24_td($A1, $A2 = null)
{
    return fatt_24_tag('td', $A1, $A2);
}
function fatt_24_h1($A1, $A2 = null)
{
    return fatt_24_tag('h1', $A1, $A2);
}
function fatt_24_h2($A1, $A2 = null)
{
    return fatt_24_tag('h2', $A1, $A2);
}
function fatt_24_h3($A1, $A2 = null)
{
    return fatt_24_tag('h3', $A1, $A2);
} // aggiunta funzione tag h3
function fatt_24_h4($A1, $A2 = null)
{
    return fatt_24_tag('h4', $A1, $A2);
}
function fatt_24_h5($A1, $A2 = null)
{
    return fatt_24_tag('h5', $A1, $A2);
}
function fatt_24_h6($A1, $A2 = null)
{
    return fatt_24_tag('h6', $A1, $A2);
}
function fatt_24_ul($A1, $A2 = null)
{
    return fatt_24_tag('ul', $A1, $A2);
}
function fatt_24_li($A1, $A2 = null)
{
    return fatt_24_tag('li', $A1, $A2);
}
function fatt_24_div($A1, $A2 = null)
{
    return fatt_24_tag('div', $A1, $A2);
}
function fatt_24_img($A1, $A2 = null)
{
    return fatt_24_tag('img', $A1, $A2);
}
function fatt_24_pre($A1, $A2 = null)
{
    return fatt_24_tag('pre', $A1, $A2);
}
function fatt_24_span($A1, $A2 = null)
{
    return fatt_24_tag('span', $A1, $A2);
}
function fatt_24_strong($A1, $A2 = null)
{
    return fatt_24_tag('strong', $A1, $A2);
}
function fatt_24_table($A1, $A2 = null)
{
    return fatt_24_tag('table', $A1, $A2);
}
function fatt_24_thead($A1, $A2 = null)
{
    return fatt_24_tag('thead', $A1, $A2);
}
function fatt_24_tbody($A1, $A2 = null)
{
    return fatt_24_tag('tbody', $A1, $A2);
}
function fatt_24_label($A1, $A2 = null)
{
    return fatt_24_tag('label', $A1, $A2);
}
function fatt_24_select($A1, $A2 = null)
{
    return fatt_24_tag('select', $A1, $A2);
}
function fatt_24_option($A1, $A2 = null)
{
    return fatt_24_tag('option', $A1, $A2);
}
function fatt_24_optgroup($A1, $A2 = null)
{
    return fatt_24_tag('optgroup', $A1, $A2);
}


// tag html input
function fatt_24_input($A1)
{
    if (is_array($A1)) {
        $A1 = fatt_24_array_string(fatt_24_attributes($A1), ' ');
    }
    return sprintf('<input %s>', $A1);
}
// tag html radio
function fatt_24_radio($group, $value)
{
    return fatt_24_input(array('type'=>"radio", 'name'=>$group, 'value'=>$value), array());
}

// creo pulsante con una classe personalizzata (se definita)
function fatt_24_button($id, $action, $caption, $class = null)
{
    return fatt_24_tag('button', array('id' => $id, 'class'=> $class, 'onclick' => $action), $caption);
}

// crea pulsante utilizzanto il tag input
function fatt_24_btn($action, $caption)
{
    return fatt_24_div(array(
        fatt_24_input(array('id' => $action.'_btn') + array('type' => 'button', 'value' => $caption, 'class' => 'button')),
    ));
}
// associa un comando
function fatt_24_cmd($cmd, $label, $desc = null)
{
    return array( 'id' => $cmd, 'type' => 'content', 'content' => fatt_24_btn($cmd, $label), 'desc' => $desc );
}

// restituisce l'indirizzo url di un file
function fatt_24_url($resource)
{
    $url = plugins_url($resource, __FILE__);
    return $url;
}

// carica immagini png
function fatt_24_png($path)
{
    return fatt_24_url($path.'.png');
}

function fatt_24_jpg($path)
{
    return fatt_24_url($path.'.jpg');
}

/**
 * Converto le opzioni della select in docParam
 * utilizzato in api_call.php (metodo fatt_24_order_to_XML),
 * hooks.php, order_status.php, behavior.php, api_get_file.php
 */
function fatt_24_get_invoice_doctype()
{
    $fatt_24_invoice_option = get_option('fatt-24-inv-create');
    $invType = '0';

    // tipo di documento: fattura elettronica
    $isFatturaEl = in_array(
        $fatt_24_invoice_option,
        ['2', '3', '4', '6', '7', FATT_24_DT_FATTURA_ELETTRONICA]
    );

    if ($fatt_24_invoice_option == '1' || $fatt_24_invoice_option == FATT_24_DT_FATTURA) {
        $invType = FATT_24_DT_FATTURA;
    } elseif ($isFatturaEl) {
        $invType = FATT_24_DT_FATTURA_ELETTRONICA;
    } elseif ($fatt_24_invoice_option == '5' || $fatt_24_invoice_option == FATT_24_DT_RICEVUTA) {
        $invType = FATT_24_DT_RICEVUTA;
    }

    return $invType;
}
