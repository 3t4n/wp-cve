<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * helpers per costruire le schermata di impostazioni del plugin
 *
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

$filesToInclude = [
    'constants.php',
    'uty.php'
  ];

foreach ($filesToInclude as $file) {
    require_once FATT_24_CODE_ROOT . $file;
}


// gestisco la pagina delle impostazioni del plugin
function fatt_24_setup_settings_page($page_id, $group_id, $sections)
{
    //sezioni
    foreach ($sections as $sect) {
        add_settings_section(
            $sect['section_id'],
            $sect['section_header'],
            $sect['section_callback'],
            $page_id
        );
        // campi della sezione
        foreach ($sect['fields'] as $id => $def) {
            $args = array('id' => $id);
            if (isset($def['help'])) {
                $args['help'] = fatt_24_array_string($def['help'], "\n");
            }

            if (isset($def['readonly'])) {
                $args['readonly'] = $def['readonly'];
            }

            foreach (array('type', 'desc', 'text', 'size', 'default', 'cmd_id', 'cmd_text', 'cmd_help', 'func', 'class', 'options') as $arg) {
                if (isset($def[$arg])) {
                    $args[$arg] = $def[$arg];
                }
            }

            add_settings_field(
                $id,
                $def['label'],
                __NAMESPACE__ .'\setting_field_'.$def['type'].'_callback',
                $page_id,
                $sect['section_id'],
                $args
            );
            register_setting($group_id, $id);
        }
    }
}

// spunta verde
function fatt_24_ok_icon() {
    return fatt_24_span(array('class' => 'dashicons dashicons-yes', 'style' => 'color: green;'), array());
}

// x rossa
function fatt_24_ko_icon() {
    return fatt_24_span(array('class' => 'dashicons dashicons-no', 'style' => 'color: red;'), array());
}

// visualizza un'icona di help scelta tra le dashicon di WP
function fatt_24_helpico($text)
{
    return fatt_24_span(array('title'=>$text, 'class'=>'dashicons dashicons-editor-help', 'style'=>'margin-left:5px'), array());
}

// visualizza l'icona di un megafono dalle dashicon di WP
function fatt_24_notice()
{
    return fatt_24_span(array('class'=>'dashicons dashicons-megaphone'), array());
}

// icona simil youtube
function fatt_24_video_icon()
{
    return fatt_24_span(array('class' => 'dashicons dashicons-video-alt3', 'style' => 'color:red; margin-top:2px;'), array());
}

function fatt_24_setting_field_output($widget, $help, $desc)
{
    echo apply_filters(FATT_24_LAYOUT_OPTION, array('widget' => $widget, 'help' => $help, 'desc' => $desc));
}

// widget checkbox
function fatt_24_widget_bool($id, $class)
{
    return fatt_24_input(array(
        'name'  => $id,
        'id'    => $id,
        'type'  => 'checkbox',
        'value' => '1',
        'class' => $class,
        checked(1, get_option($id), false)
    ));
}
// chiamata associata alla checkbox
function setting_field_bool_callback(array $args)
{
    extract(shortcode_atts(array(
        'id'    => null,
        'desc'  => null,
        'help'  => null,
        'default' => null
    ), $args));

    if ($default !== null) {
        global $wpdb;
        $c = $wpdb->get_results("select * from $wpdb->options where option_name='$id'");
        if ($c === array()) {
            update_option($id, $default);
        }
    }

    $widget = fatt_24_widget_bool($id, 'code');
    fatt_24_setting_field_output($widget, $help, $desc);
}

// widget radio
function fatt_24_widget_radio($id, $class, $options)
{
    $widget = '';
    foreach ($options as $k => $v) {
        $ref = sprintf('%s-%s', $id, $k);
        $widget .=
        fatt_24_label(array('for'=>$ref), $v) .
        fatt_24_input(array('name'=>$id, 'id'=>$ref, 'type'=>'radio', 'value'=>$k, 'class'=>$class, 0=>checked($k, get_option($id), false)));
    }
    return $widget;
}

// chiamata associata al widget radio
function setting_field_radio_callback(array $args)
{
    extract(shortcode_atts(array(
        'id'        => null,
        'desc'      => null,
        'help'      => null,
        'options'   => array(),
        'class'     => null
    ), $args));
    $widget = fatt_24_widget_radio($id, $class, $options);
    fatt_24_setting_field_output($widget, $help, $desc);
}

// widget tendina: restituisce le opzioni del menu
function fatt_24_widget_select($id, $class, $current, $options)
{
    return fatt_24_select(
        array('name'=>$id, 'id'=>$id, 'class'=>$class),
        fatt_24_array_map_kv(function ($k, $v) use ($current) {
            return fatt_24_option(array('value' => $k, selected($k, $current, false)), $v);
        }, $options)
    );
}

// chiamata associata al menu a tendina
function setting_field_select_callback(array $args)
{
    extract(shortcode_atts(array(
        'id'        => null,
        'class'     => null,
        'options'   => array(),
        'desc'      => null,
        'help'      => null,
    ), $args));

    $current = get_option($id);
    $widget = fatt_24_widget_select($id, $class, $current, $options);
    fatt_24_setting_field_output($widget, $help, $desc);
}

// campo di input testo
function setting_field_text_callback(array $args)
{
    extract(shortcode_atts(array(
        'id'    => null,
        'desc'  => null,
        'help'  => null,
        'class' => null,
        'size'  => 32,
        'default' => null, 
        'readonly' => false,
    ), $args));

    $value = get_option($id);
    if (empty($value)) {
        $value = $default;
    }

    $props = array(
        'type'  => 'text',
        'name'  => $id,
        'id'    => $id,
        'value' => $value,
        'class' => $class,
        'size'  => $size 
    );

    if ($readonly == 'true') {
        $props['style'] =  'border: 0px; margin-left: -5px;';
        $props['readonly'] = true;
    }

    fatt_24_setting_field_output(
        fatt_24_input($props), 
        $help, $desc
    );
}

// gestione pulsante
function setting_field_button_callback(array $args)
{
    extract(shortcode_atts(array(
        'id'    => null,
        'desc'  => null,
        'help'  => null,
        'text'  => null
    ), $args));

    fatt_24_setting_field_output(
        fatt_24_input(array(
            'type'  => 'button',
            'name'  => $id,
            'id'    => $id,
            'style' => 'border: 0px; background-color: #00b500; color: #fff;',
            'class' => 'button',
            'value' => $text
        )),
        $help,
        $desc
    );
}

// chiamata associata all'etichetta
function setting_field_label_callback(array $args)
{
    extract(shortcode_atts(array(
        'id'    => null,
        'desc'  => null,
        'help'  => null,
        'text'  => null,
    ), $args));
    fatt_24_setting_field_output(fatt_24_label(fatt_24_id($id), $text), $help, $desc);
}

/**
 *  Aggiunto metodo per gestione input hidden
 */
function setting_field_hidden_callback(array $args)
{
    extract(shortcode_atts(array(
        'id'    => null,
        'desc'  => null,
        'help'  => null,
        'class' => 'hidden',
        'size'  => 32,
        'default' => null
    ), $args));

    $value = get_option($id);
    if (empty($value)) {
        $value = $default;
    }
    fatt_24_setting_field_output(
        fatt_24_input(array(
            'type'  => 'hidden',
            'name'  => $id,
            'id'    => $id,
            'value' => $value,
            'class' => $class,
            'size'  => $size,
        )),
        $help,
        $desc
    );
}
/**
 * Nuova funzione campo pw: aggiunge un icona di occhio collegata a un listener
 * in settings.php 497-504, commentata per un futuro sviluppo
 * Davide Iandoli 07.04.2020
 */
/*
function setting_field_password_cmd_callback(array $args) {
    extract(shortcode_atts(array(
        'id'        => null,
        'desc'      => null,
        'help'      => null,
        'size'      => 32,
        'class'     => 'code',
        'default'   => null,
        'cmd_id'    => null,
        'cmd_text'  => null,
        'cmd_help'  => null
    ),  $args));

    $widget = fatt_24_span(array(
        fatt_24_input(array(
            'type'  => 'password',
            'name'  => $id,
            'id'    => $id,
            'value' => get_option($id, $default),
            'class' => $class,
            'size'  => $size
        )),
        fatt_24_span(array(
             'id' => 'fatt-24-visibility',
             'style' => 'margin-left: -33px; padding: 5px;', // align icon inside input field
             'class' =>'dashicons dashicons-visibility'
        ), ''),
        fatt_24_input(array(
            'type'  => 'button',
            'id'    => $cmd_id,
            'class' => 'code',
            'value' => $cmd_text,
               'title' => $cmd_help
        ))
    ));
    fatt_24_setting_field_output($widget, $help, $desc);
}*/

// chiamata associata al pulsante
function setting_field_text_cmd_callback(array $args)
{
    extract(shortcode_atts(array(
        'id'        => null,
        'desc'      => null,
        'help'      => null,
        'size'      => 32,
        'class'     => 'code',
        'default'   => null,
        'cmd_id'    => null,
        'cmd_text'  => null,
        'cmd_help'  => null
    ), $args));

    $widget = fatt_24_span(array(
        fatt_24_input(array(
            'type'  => 'text',
            'name'  => $id,
            'id'    => $id,
            'value' => get_option($id, $default),
            'class' => $class,
            'size'  => $size
        )),
        fatt_24_input(array(
            'type'  => 'button',
            'id'    => $cmd_id,
            'style' => 'border: 0px; background-color: #00b500; color: #fff;',
            'class' => 'button',
            'value' => $cmd_text,
            'title' => $cmd_help
        ))
    ));
    fatt_24_setting_field_output($widget, $help, $desc);
}

// gestione tabelle
function setting_field_table_callback(array $args)
{
    extract(shortcode_atts(array(
        'id'    => null,
        'desc'  => null,
        'help'  => null,
        'func'  => null,
    ), $args));
    assert('$id != null');
    fatt_24_setting_field_output(fatt_24_label(id($id), call_user_func($func)), $help, $desc);
}



//aggiorna l'opzione Crea documento fiscale
function fatt_24_update_invoice_options()
{
    $oldOption = get_option('fatt-24-inv-create');
    $result = '0'; //disabilitata

    if ($oldOption == FATT_24_DT_FATTURA || $oldOption == '1') {
        $result = '1';
    } elseif ($oldOption == FATT_24_DT_FATTURA_ELETTRONICA || $oldOption == '2') {
        $result = '2';
    } elseif ($oldOption == FATT_24_DT_RICEVUTA || $oldOption == '5') {
        $result = '5';
    }
    update_option('fatt-24-inv-create', $result);
}

/**
 * Se non ho già impostato un'opzione per il sez. personalizzato FE
 * controllo l'opzione selezionata per i sezionali Fattura (versioni precedenti del plugin) 
 * e li confronto con la lista dei sezionali FE. Se il risultato del confronto è positivo
 * allora aggiorno l'opzione in modo che il valore di default sia lo stesso selezionato in precedenza
 */
function fatt_24_default_sezionale_fe() {
    $sez_fatt_el = get_option(FATT_24_INV_SEZIONALE_FATTURA_ELETTRONICA);
    if (!$sez_fatt_el) {
        $sez_fatt_el = get_option(FATT_24_INV_SEZIONALE_FATTURA);
        $sez_fe_options = fatt_24_getSezionale(11);
        if (array_key_exists((int) $sez_fatt_el, $sez_fe_options)) {
           update_option(FATT_24_INV_SEZIONALE_FATTURA_ELETTRONICA, $sez_fatt_el);
        }
    }
}