<?php

if (!defined('WPINC')) {
    die;
}
if (empty(get_option('grupoimpultec_agencias_personalizadas')) || is_admin()) {
    return;
}

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    trait funcionesGrupoImpultec_Shipping_Methods {

        public function construct_adicional() {
            $this->id = $this->agencia;
            global $myListTable;
            global $array_agencias_con_mapas;
            $array_agencias_con_mapas = array();
            $datos_array['usuario_servicio'] = get_option('grupoimpultec_usuario_servicio');
            $datos_array['password_servicio'] = get_option('grupoimpultec_password_servicio');
            $datos_array['servicio'] = $GLOBALS['servicio'];
            $datos_array['id_usuario'] = grupoimpultec_getUserId($datos_array);
            $this->nombre_agencia = json_decode(
                    grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
                'password_servicio' => $datos_array['password_servicio'],
                'id_usuario' => $datos_array['id_usuario'],
                'id_agencia' => $this->agencia,
                'servicio' => $datos_array['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_nombre_agencia'), true
            );
            $this->method_title = __($GLOBALS['nombre_app'] . ' - ' . $this->nombre_agencia, strtolower($GLOBALS['nombre_app']) . '_' . $this->agencia);
            $this->method_description = __('Método personalizado de envío para ' . $GLOBALS['nombre_app'] . $this->nombre_agencia, strtolower($GLOBALS['nombre_app']) . $this->agencia);
            $this->availability = 'including';
            $this->countries = array('ES', 'PT', 'DE', 'FR', 'BE', 'LU', 'NL', 'AT', 'IT', 'DK', 'CH', 'MC', 'CA', 'US', 'CN', 'NZ', 'BG', 'CZ', 'SK', 'SI', 'EE', 'HU', 'LV', 'LT', 'PL', 'RO', 'FI', 'GR', 'SE', 'GG', 'LI', 'NO', 'IT', 'GB', 'GB', 'GB', 'GB', 'IE', 'JE', 'CU', 'CO', 'MT', 'JP', 'AU', 'KH', 'HK', 'ID', 'LA', 'MO', 'MY', 'MX', 'PH', 'SG', 'KR', 'TW', 'TH', 'VN', 'BD', 'BT', 'BN', 'IN', 'IL', 'KW', 'MM', 'NP', 'OM', 'BR', 'PK', 'QA', 'LK', 'TN', 'AE', 'YE', 'AI', 'AG', 'AW', 'BS', 'BB', 'BZ', 'BM', 'BO', 'KY', 'CL', 'CR', 'DM', 'DO', 'SV', 'GD', 'GT', 'HT', 'HN', 'JM', 'MS', 'NI', 'PA', 'PE', 'PR', 'ZA', 'KN', 'LC', 'VC', 'SR', 'TT', 'TC', 'AO', 'AM', 'AZ', 'BJ', 'BW', 'BF', 'BI', 'CM', 'CV', 'RC', 'TD', 'CG', 'CK', 'DJ', 'GQ', 'ER', 'ET', 'FJ', 'PF', 'GA', 'GM', 'GE', 'GH', 'GU', 'GW', 'KZ', 'KE', 'KI', 'KG', 'AL', 'HR', 'CY', 'GL', 'IS', 'GI', 'AD', 'MK', 'MD', 'ME', 'RS', 'TR', 'DZ', 'EG', 'IQ', 'BH', 'BT', 'JO', 'LB', 'LY', 'MA', 'PS', 'SA', 'TN', 'PT', 'PT', 'BA', 'FO', 'AR', 'EC', 'PY', 'UY', 'UG', 'TV', 'CW', 'BL', 'KP', 'VI', 'NA', 'NE', 'NG', 'WS', 'AS', 'SC', 'SL', 'SO', 'TG', 'TO', 'VU', 'ZM', 'TJ', 'LR', 'ZW', 'TH', 'SD', 'SZ', 'SN', 'RW', 'AF', 'CI', 'IR', 'MG', 'MW', 'MV', 'MN', 'MZ', 'NC', 'UZ', 'TM', 'SB', 'MU', 'MR', 'UN', 'NR', 'AN', 'LS', 'ML', 'ST', 'PG', 'GN', 'KR', 'VT', 'IM', 'MF', 'CX', 'NF', 'CC', 'MP', 'YT', 'FM', 'CW', 'PW', 'SX', 'SS', 'TL', 'WF', 'IM', 'PT', 'GR', 'AX', 'RE');
            $this->init();
            $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
            $this->title = isset($this->settings['title']) ? $this->settings['title'] : __($this->nombre_agencia, strtolower($GLOBALS['nombre_app']) . '_' . $this->agencia);
        }

        function init() {
            $this->init_settings();
        }

        public function calculate_shipping($package = array()) {
            if (empty(get_option('grupoimpultec_agencias_personalizadas'))) {
                return;
            }
            $weight = 0;
            $width = 0;
            $height = 0;
            $length = 0;
            $cost = 0;
            $country = $package["destination"]["country"];
            $contador_bultos = 1;
            foreach ($package['contents'] as $item_id => $values) {
                $product = $values['data'];
                if (get_option('grupoimpultec_tipo_calculo_precio_p') == 2) {
                    for ($i = 1; $i <= $values['quantity']; $i++) {
                        $datos_array['array_bultos'][$contador_bultos]['peso'] = $product->get_weight();
                        $datos_array['array_bultos'][$contador_bultos]['alto'] = $product->get_height();
                        $datos_array['array_bultos'][$contador_bultos]['ancho'] = $product->get_width();
                        $datos_array['array_bultos'][$contador_bultos]['largo'] = $product->get_length();
                        if ($datos_array['array_bultos'][$contador_bultos]['peso'] <= 0) {
                            $datos_array['array_bultos'][$contador_bultos]['peso'] = 1;
                        }
                        if ($datos_array['array_bultos'][$contador_bultos]['alto'] <= 0) {
                            $datos_array['array_bultos'][$contador_bultos]['alto'] = 1;
                        }
                        if ($datos_array['array_bultos'][$contador_bultos]['ancho'] <= 0) {
                            $datos_array['array_bultos'][$contador_bultos]['ancho'] = 1;
                        }
                        if ($datos_array['array_bultos'][$contador_bultos]['largo'] <= 0) {
                            $datos_array['array_bultos'][$contador_bultos]['largo'] = 1;
                        }
                        $contador_bultos++;
                    }
                } else {
                    if ($product->get_weight() > 0) {
                        $datos_array['array_bultos'][$contador_bultos]['peso'] = $product->get_weight() * $values['quantity'];
                    } else {
                        $datos_array['array_bultos'][$contador_bultos]['peso'] = 1;
                    }
                    if ($product->get_height() > 0) {
                        $datos_array['array_bultos'][$contador_bultos]['alto'] = $product->get_height() * pow($values['quantity'], 1 / 3);
                    } else {
                        $datos_array['array_bultos'][$contador_bultos]['alto'] = 1;
                    }
                    if ($product->get_length() > 0) {
                        $datos_array['array_bultos'][$contador_bultos]['largo'] = $product->get_length() * pow($values['quantity'], 1 / 3);
                    } else {
                        $datos_array['array_bultos'][$contador_bultos]['largo'] = 1;
                    }
                    if ($product->get_width() > 0) {
                        $datos_array['array_bultos'][$contador_bultos]['ancho'] = $product->get_width() * pow($values['quantity'], 1 / 3);
                    } else {
                        $datos_array['array_bultos'][$contador_bultos]['ancho'] = 1;
                    }
                    $contador_bultos++;
                }
            }

            $datos_array['array_bultos'] = grupoimpultec_convertir_medidas($datos_array['array_bultos']);

            $datos_array['usuario_servicio'] = get_option('grupoimpultec_usuario_servicio');
            $datos_array['password_servicio'] = get_option('grupoimpultec_password_servicio');
            $datos_array['id_usuario'] = grupoimpultec_getUserId($datos_array);
            $datos_array['direccion_predeterminada'] = get_option('grupoimpultec_direccion_predeterminada');
            $array_direccion_remitente = json_decode(
                    grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
                'password_servicio' => $datos_array['password_servicio'],
                'id_usuario' => $datos_array['id_usuario'],
                'id_direccion' => $datos_array['direccion_predeterminada'],
                'servicio' => $GLOBALS['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_datos_direccion'), true
            );
            $datos_array['id_direccion'] = $array_direccion_remitente['id_direccion'];
            $datos_array['codigos_origen'] = $array_direccion_remitente['codigo_postal'];
            $datos_array['poblacion_salida'] = strtoupper($array_direccion_remitente['poblacion']);
            $datos_array['iso_pais_salida'] = json_decode(
                            grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
                        'password_servicio' => $datos_array['password_servicio'],
                        'id_usuario' => $datos_array['id_usuario'],
                        'id_pais' => $array_direccion_remitente['id_pais'],
                        'servicio' => $GLOBALS['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_iso_pais'), true
                    )['iso_pais'];
            $datos_array['codigos_destino'] = $package['destination']['postcode'];
            $datos_array['poblacion_llegada'] = strtoupper($package['destination']['city']);
            $datos_array['iso_pais_llegada'] = $package['destination']['country'];
            $datos_array['servicio'] = 'wordpress';
            global $woocommerce;
            $items = $woocommerce->cart->get_cart();
            $datos_array['array_bultos'] = grupoimpultec_obtener_array_bultos($items);
            if (!array_key_exists(0, $datos_array['array_bultos'])) {
                array_unshift($datos_array['array_bultos'], array());
            }
            $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_listado_agencias_precios';
            $listado_agencias_precios = json_decode(grupoimpultec_curlJson($datos_array, $url), true)['datos_agencia2'];
            if (count($listado_agencias_precios) == 0) {
                return;
            }
            if (array_key_exists($this->id, $listado_agencias_precios)) {
                $array_precio_agencia = $listado_agencias_precios[$this->id];
                $rate = array(
                    'id' => $this->id,
                    'label' => $this->title,
                    'cost' => number_format($array_precio_agencia['importe'], 2)
                );
                $this->add_rate($rate);
            } else
                return;
        }

    }

}

function grupoimpultec_obtener_array_bultos($items) {
    $contador = 1;
    $datos_array['array_bultos'] = array();
    $peso_anterior = 0;
    foreach ($items as $item => $values) {
        $product = wc_get_product($values['data']->get_id());
        $datos_array['array_bultos'][$contador]['peso'] = 0;
        if ($product->get_weight() > 0) {
            $datos_array['array_bultos'][$contador]['peso'] = $product->get_weight();
        }
        if (!($datos_array['array_bultos'][$contador]['peso'] > 0)) {
            $datos_array['array_bultos'][$contador]['peso'] = 1;
        }
        $datos_array['array_bultos'][$contador]['largo'] = 0;
        if ($product->get_length() > 0) {
            $datos_array['array_bultos'][$contador]['largo'] = $product->get_length();
        }
        if (!($datos_array['array_bultos'][$contador]['largo'] > 0)) {
            $datos_array['array_bultos'][$contador]['largo'] = 1;
        }

        $datos_array['array_bultos'][$contador]['ancho'] = 0;
        if ($product->get_width() > 0) {
            $datos_array['array_bultos'][$contador]['ancho'] = $product->get_width();
        }
        if (!($datos_array['array_bultos'][$contador]['ancho'] > 0)) {
            $datos_array['array_bultos'][$contador]['ancho'] = 1;
        }
        $datos_array['array_bultos'][$contador]['alto'] = 0;
        if ($product->get_height() > 0) {
            $datos_array['array_bultos'][$contador]['alto'] = $product->get_height();
        }
        if (!($datos_array['array_bultos'][$contador]['alto'] > 0)) {
            $datos_array['array_bultos'][$contador]['alto'] = 1;
        }
        $datos_array['array_bultos'][$contador] = obtener_unidades_peso_medidas($datos_array['array_bultos'][$contador], $values);

        $bultos_añadidos = 0;
        for ($i = 2; $i <= $values['quantity']; $i++) {
            $datos_array['array_bultos'][$contador + ($i - 1)] = $datos_array['array_bultos'][$contador];
            $bultos_añadidos++;
        }
        $contador = $contador + $bultos_añadidos + 1;
    }


    foreach ($datos_array['array_bultos'] as $clave => $fila) {
        $peso[$clave] = $fila['peso'];
    }
    $grupoimpultec_tipo_calculo_precio_p = get_option('grupoimpultec_tipo_calculo_precio_p');
    if ($grupoimpultec_tipo_calculo_precio_p == 2) {
        array_multisort($peso, SORT_DESC, $datos_array['array_bultos']);
        $datos_array['array_bultos'] = array_combine(range(1, count($datos_array['array_bultos'])), array_values($datos_array['array_bultos']));
        $datos_array['array_bultos'] = grupoimpultec_calcular_nuevos_bultos($datos_array['array_bultos']);
        array_unshift($datos_array['array_bultos'], array());
    }
    return $datos_array['array_bultos'];
}



function grupoimpultec_calcular_nuevos_bultos($array_bultos) {
    $array_nuevo_bultos_final = array();
    $array_bultos = grupoimpultec_traducir_bultos($array_bultos);
    $medidas_caja = grupoimpultec_obtener_medidas_caja();
    if (!grupoimpultec_peso_bulto_adecuado_caja($array_bultos) || !grupoimpultec_medidas_bultos_adecuado_caja($array_bultos, $medidas_caja)) {
        return (array('0' => array(), '1' => array('peso' => 50000, 'ancho' => 1, 'alto' => 1, 'largo' => 1)));
    }
    set_time_limit(10);
    $i = 1;
    $array_nuevo_bultos = array();
    $numero_bultos = count($array_bultos);
    $array_bultos_temporal = array();
    //echo("array_bultos es " . var_export($array_bultos, true) . "<p>medidas_caja " . var_export($medidas_caja, true));
    while (count($array_bultos) > 0 && $i <= $numero_bultos) {
        $bulto_resultante = grupoimpultec_laff_pack($array_bultos, null, 'dimensiones');
        if (array_key_exists(0, $bulto_resultante)) {
            $bulto_resultante = $bulto_resultante[0];
        }
        $bulto_resultante['peso'] = obtener_peso_bultos($array_bultos);
        if (!grupoimpultec_bulto_entra_en_caja($bulto_resultante, $medidas_caja) || !grupoimpultec_peso_total_bultos_adecuado_caja($array_bultos)) {
            //echo("el bulto " . var_export($bulto_resultante, true) . " no entra en caja o su peso es incorrecto<p>dentro de array_bultos_temporal aun hay: " . var_export($array_bultos_temporal, true) . " e i es " . $i . "<p>");
            array_push($array_bultos_temporal, reset($array_bultos));
            array_shift($array_bultos);

            //$array_nuevo_bultos[$i]=$medidas_caja;
            //$array_nuevo_bultos[$i]['peso'] = reset($array_bultos)['peso'];
        } else {
            $array_nuevo_bultos[$i] = $medidas_caja;
            $array_nuevo_bultos[$i]['peso'] = $bulto_resultante['peso'];
            $array_bultos = $array_bultos_temporal;
            $array_bultos_temporal = array();
            //echo("el bulto " . var_export($bulto_resultante, true) . " si entra en caja y su peso es correcto<p>dentro de array_bultos aun hay: " . var_export($array_bultos, true) . " e i es " . $i . "<p>");
            $i++;
        }
    }

    //die("<p>array_nuevo_bultos es " . var_export($array_nuevo_bultos, true) . " i es " . $i);
    $grupoimpultec_tipo_calculo_precio_p = get_option('grupoimpultec_tipo_calculo_precio_p');
    $contador = 1;
    foreach ($array_nuevo_bultos as $bulto) {
        if ($grupoimpultec_tipo_calculo_precio_p == 2) {
            $array_nuevo_bultos_final[$contador]['peso'] = $bulto['peso'];
            $array_nuevo_bultos_final[$contador]['ancho'] = grupoimpultec_obtener_medidas_caja()['width'];
            $array_nuevo_bultos_final[$contador]['alto'] = grupoimpultec_obtener_medidas_caja()['height'];
            $array_nuevo_bultos_final[$contador]['largo'] = grupoimpultec_obtener_medidas_caja()['length'];
        } else {
            $array_nuevo_bultos_final[$contador]['peso'] = $bulto['peso'];
            $array_nuevo_bultos_final[$contador]['ancho'] = $bulto['width'];
            $array_nuevo_bultos_final[$contador]['alto'] = $bulto['height'];
            $array_nuevo_bultos_final[$contador]['largo'] = $bulto['length'];
        }
        $contador++;
    }



    return $array_nuevo_bultos_final;
}

function grupoimpultec_traducir_bultos($array_bultos) {
    $array_bultos_traducido = array();
    foreach ($array_bultos as $bulto) {
        $bulto_traducido['peso'] = $bulto['peso'];
        $bulto_traducido['width'] = $bulto['ancho'];
        $bulto_traducido['height'] = $bulto['alto'];
        $bulto_traducido['length'] = $bulto['largo'];
        array_push($array_bultos_traducido, $bulto_traducido);
    }
    return $array_bultos_traducido;
}

function grupoimpultec_peso_total_bultos_adecuado_caja($array_bultos) {
    $peso_total = 0;
    foreach ($array_bultos as $bulto) {
        $peso_total += $bulto['peso'];
    }    
    if ($peso_total > get_option('grupoimpultec_max_weigth_box')) {
        return false;
    }
    return true;
}

function grupoimpultec_peso_bulto_adecuado_caja($array_bultos) {
    $peso_total = 0;
    foreach ($array_bultos as $bulto) {
        if ($bulto['peso'] > get_option('grupoimpultec_max_weigth_box')) {
            return false;
        }
    }

    return true;
}

function grupoimpultec_medidas_bultos_adecuado_caja($array_bultos, $medidas_caja) {
    foreach ($array_bultos as $bulto) {
        if (!grupoimpultec_bulto_entra_en_caja($bulto, $medidas_caja)) {
            return false;
        }
    }
    return true;
}

function obtener_peso_bultos($array_bultos) {
    $suma_pesos = 0;
    foreach ($array_bultos as $bulto) {
        $suma_pesos += $bulto['peso'];
    }
    return $suma_pesos;
}

function grupoimpultec_obtener_medidas_caja() {
    
        $array_caja = array('length' => get_option('grupoimpultec_length_box'), 'width' => get_option('grupoimpultec_width_box'), 'height' => get_option('grupoimpultec_height_box'));    
    return $array_caja;
}

function grupoimpultec_bulto_entra_en_caja($bultos_a_encajar) {
    $array_caja = grupoimpultec_obtener_medidas_caja();
    return grupoimpultec_laff_pack($bultos_a_encajar, $array_caja, 'encajar');
}

function grupoimpultec_laff_pack($array_bultos, $contenedor, $metodo) {
    require_once(plugin_dir_path(__FILE__) . 'laff-pack.php');
    switch ($metodo) {
        case 'encajar':
            //echo("<p>Entra en encajar " . var_export($array_bultos, true) . "<p>");
            $laff = new Grupoimpultec_Packer();
            return $laff->_box_fits($array_bultos, $contenedor);
            break;
        case 'dimensiones':
            //echo("<p>Entra en dimensiones " . var_export($array_bultos, true) . "<p>");
            if (count($array_bultos) == 1) {
                return $array_bultos;
            }

            $laff = new Grupoimpultec_Packer($array_bultos, $contenedor);
            $laff->pack();
            return $laff->get_container_dimensions();
            break;
    }
}

function obtener_unidades_peso_medidas($bulto_entrada, $values) {
    $bulto_salida = array();
    $weight_unit = get_option('woocommerce_weight_unit');
    $dimension_unit = get_option('woocommerce_dimension_unit');
    switch ($weight_unit) {
        case 'g':
            $bulto_salida['peso'] = $bulto_entrada['peso'] / 1000;
            break;
        case 'lbs':
            $bulto_salida['peso'] = $bulto_entrada['peso'] / 2.205;
            break;
        case 'oz':
            $bulto_salida['peso'] = $bulto_entrada['peso'] / 35.274;
            break;
    }
    switch ($dimension_unit) {
        case 'm':
            $bulto_salida['largo'] = $bulto_entrada['largo'] * 100;
            $bulto_salida['ancho'] = $bulto_entrada['ancho'] * 100;
            $bulto_salida['alto'] = $bulto_entrada['alto'] * 100;
            break;
        case 'mm':
            $bulto_salida['largo'] = $bulto_entrada['largo'] / 10;
            $bulto_salida['ancho'] = $bulto_entrada['ancho'] / 10;
            $bulto_salida['alto'] = $bulto_entrada['alto'] / 10;
            break;
        case 'in':
            $bulto_salida['largo'] = $bulto_entrada['largo'] * 2.54;
            $bulto_salida['ancho'] = $bulto_entrada['ancho'] * 2.54;
            $bulto_salida['alto'] = $bulto_entrada['alto'] * 2.54;
            break;
        case 'oz':
            $bulto_salida['largo'] = $bulto_entrada['largo'] * 91.44;
            $bulto_salida['ancho'] = $bulto_entrada['ancho'] * 91.44;
            $bulto_salida['alto'] = $bulto_entrada['alto'] * 91.44;
            break;
    }
    $bulto_salida['largo'] = $bulto_entrada['largo'];
    $bulto_salida['ancho'] = $bulto_entrada['ancho'];
    $bulto_salida['alto'] = $bulto_entrada['alto'];
    $bulto_salida['peso'] = $bulto_entrada['peso'];

    return $bulto_salida;
}

function grupoimpultec_shipping_method() {
    if (empty(get_option('grupoimpultec_agencias_personalizadas'))) {
        return;
    }
    $datos_array['usuario_servicio'] = get_option('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = get_option('grupoimpultec_password_servicio');
    $datos_array['id_agencia'] = wc_get_chosen_shipping_method_ids()[0];
    foreach (get_option('grupoimpultec_agencias_personalizadas') as $item => $value) {
        $funcion_dinamica = 'class GrupoImpultec_Shipping_Methods_' . $value . ' extends WC_Shipping_Method {
            use funcionesGrupoImpultec_Shipping_Methods;
            public function __construct() {
                $this->agencia = ' . $value . ';                    
                $this->construct_adicional();
            }
        }';
        eval($funcion_dinamica);
    }
}
add_action('wp_enqueue_scripts', 'grupoimpultec_enqueue_jquery_ui_style');
add_action('wp_enqueue_scripts', 'grupoimpultec_enqueue_jquery');
add_action('wp_enqueue_scripts', 'grupoimpultec_enqueue_google_maps_js');
add_action('woocommerce_after_shipping_calculator', 'after_shipping_calculator', 20, 1);
add_action('woocommerce_shipping_init', 'grupoimpultec_shipping_method');
add_filter('woocommerce_shipping_methods', 'add_grupoimpultec_shipping_method');
add_action('wp_enqueue_scripts', 'grupoimpultec_enqueue_maps_js');
add_action('woocommerce_before_checkout_shipping_form', 'before_checkout_shipping_form');
add_action('woocommerce_checkout_create_order', 'checkout_create_order', 10, 2);

function after_shipping_calculator() {     
    $datos_array['usuario_servicio'] = get_option('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = get_option('grupoimpultec_password_servicio');
    $datos_array['id_agencia'] = wc_get_chosen_shipping_method_ids()[0];
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $array_agencias_con_mapas = json_decode(
                    grupoimpultec_curlJson(array(
                'usuario_servicio' => $datos_array['usuario_servicio'],
                'password_servicio' => $datos_array['password_servicio'],
                'servicio' => $GLOBALS['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/agencias_con_mapa'), true
            )['agencias_con_mapa'];

    $agencia_mapa_destino = false;    
    foreach ($array_agencias_con_mapas as $array_mapas_agencia) {
        if (wc_get_chosen_shipping_method_ids()[0] == $array_mapas_agencia['id_agencia']) {
            if ($array_mapas_agencia['mapa_destino'] == 1) {
                $agencia_mapa_destino = true;
                $id_agencia_madre = $array_mapas_agencia['id_agencia_madre'];
            }
        }
    }echo('<script>grupoimpultec_destruir_ls("select_oficinas_destino");grupoimpultec_destruir_ls("entrega_oficina_destino");</script>');
    if ($agencia_mapa_destino) {
        global $woocommerce;
        $woocommerce->customer->get_shipping_postcode();
        $datos_array['codigos_destino'] = $woocommerce->customer->get_shipping_postcode();
        $datos_array['id_agencia_madre'] = $id_agencia_madre;
        include('views/mapa_destino.php');
        echo('<input type="hidden" name="grupoimpultec_id_agencia" id="grupoimpultec_id_agencia" value="' . wc_get_chosen_shipping_method_ids()[0] . '">');
        echo('<script>');
        echo('php_vars_maps_js.api_server = "' . $GLOBALS['api_server'] . '";' .
        'php_vars_maps_js.id_agencia = "' . wc_get_chosen_shipping_method_ids()[0] . '";' .
        'php_vars_maps_js.id_agencia_madre = "' . $id_agencia_madre . '";' .
        'php_vars_maps_js.codigo_postal_oficina = "' . $datos_array['codigos_destino'] . '";' .
        'php_vars_maps_js.select_oficinas_destino = "select_oficinas_destino";' .
        'php_vars_maps_js.map_oficinas_destino = "map_oficinas_destino";' .
        'popular_mapa(php_vars_maps_js.api_server,php_vars_maps_js.id_agencia, php_vars_maps_js.id_agencia_madre, php_vars_maps_js.codigo_postal_oficina, php_vars_maps_js.select_oficinas_destino, php_vars_maps_js.map_oficinas_destino);' .
        '</script>');
    }
}

function before_checkout_shipping_form() {
    echo('<input type="hidden" name="grupoimpultec_select_oficinas_destino" id="grupoimpultec_select_oficinas_destino">');
    echo('<input type="hidden" name="grupoimpultec_entrega_oficina_destino" id="grupoimpultec_entrega_oficina_destino">');
    echo('<script>');
    echo('jQuery("#grupoimpultec_select_oficinas_destino").val(grupoimpultec_obtener_ls("select_oficinas_destino"));');
    echo('jQuery("#grupoimpultec_entrega_oficina_destino").val(grupoimpultec_obtener_ls("entrega_oficina_destino"));');
    echo('</script>');
}

function checkout_create_order($order, $data) {
    if (isset($_POST['grupoimpultec_select_oficinas_destino']) && !empty($_POST['grupoimpultec_select_oficinas_destino'])) {
        $order->update_meta_data('grupoimpultec_select_oficinas_destino', sanitize_text_field($_POST['grupoimpultec_select_oficinas_destino']));
    }
    if (isset($_POST['grupoimpultec_id_agencia']) && !empty($_POST['grupoimpultec_id_agencia']))
        $order->update_meta_data('grupoimpultec_id_agencia', sanitize_text_field($_POST['grupoimpultec_id_agencia']));
}

function add_grupoimpultec_shipping_method() {
    if (empty(get_option('grupoimpultec_agencias_personalizadas'))) {
        return;
    }
    foreach (get_option('grupoimpultec_agencias_personalizadas') as $item => $value) {
        $methods[] = 'GrupoImpultec_Shipping_Methods_' . $value;
    }
    return $methods;
}
