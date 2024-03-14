<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly <div class="wrap">    
?><?php

/**
 * 2019 Genei Gestión Logística
 *
 * GENEI S.L.
 *
 *
 * NOTICE OF LICENSE
 *
 *  @author    Carlos Tornadijo Genei S.L.
 *  @copyright 2019 Genei S.L.
 *  @license   Commercial
 *  @version 1.0.1
 */
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Grupoimpultec_orders_list extends WP_List_Table {

    function __construct() {

        global $status, $page;
        parent::__construct(array(
            'singular' => __('pedido', 'mylisttable'),
            'plural' => __('pedidos', 'mylisttable'),
            'ajax' => false
        ));

        add_action('admin_head', array(&$this, 'admin_header'));
    }

    function obtener_credenciales_servicio($credencial) {
        return get_option($credencial);
    }

    function obtener_primera_orden() {
        return get_option('grupoimpultec_first_order');
    }

    function admin_header() {
        $page = ( isset($_GET['page']) ) ? esc_attr(sanitize_text_field($_GET['page'])) : false;
        if ('grupoimpultec' != $page) {
            return;
        }
    }

    function no_items() {
        _e('No se han encontrado pedidos de Woocommerce.');
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'numero_pedido_wp':
            case 'codigo_nombre_app':
            case 'usuario_wp':
            case 'nombre_estado':
            case 'estado':
            case 'seguimiento':
            case 'cajas':
                return $item[$column_name];
                break;
            default:
                return print_r($item, true);
                break;
        }
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'numero_pedido_wp' => array('numero_pedido_wp', false),
            'usuario_wp' => array('usuario_wp', false),
            'codigo_nombre_app' => array('codigo_nombre_app', false),
            'nombre_estado' => array('nombre_estado', false),
            'estado' => array('estado', false),
            'seguimiento' => array('seguimiento', false),
            'cajas' => array('cajas', false)
        );
        return $sortable_columns;
    }

    function get_columns() {
        $columns = array(
            'numero_pedido_wp' => __('Pedido WP'),
            'usuario_wp' => __('Usuario'),
            'codigo_nombre_app' => __('Código ' . $GLOBALS['nombre_app'], 'mylisttable'),
            'nombre_estado' => __('Estado'),
            'seguimiento' => __('Seguimiento'),
            'cajas' => __('Cajas')
        );
        return $columns;
    }

    function usort_reorder($a, $b) {
        $orderby = (!empty($_GET['orderby']) ) ? sanitize_text_field($_GET['orderby']) : 'numero_pedido_wp';
        $order = (!empty($_GET['order']) ) ? sanitize_text_field($_GET['order']) : 'asc';
        $result = strcmp($a[$orderby], $b[$orderby]);
        return ( $order === 'desc' ) ? $result : -$result;
    }

    function column_numero_pedido_wp($item) {
        if ($item['codigo_nombre_app'] == '') {
            include('views/configuracion_bultos.php');
            $actions = array(
                'tarifas' => sprintf('<a class="href_tarifas btn btn-primary" href="?page=%s&action=%s&pedido=%s" id="href_tarifas_' . $item['numero_pedido_wp'] . '">' . __('Mostrar tarifas') . '</a>', esc_html($_REQUEST['page']), 'tarifas', $item['numero_pedido_wp']),
            );
            return sprintf('%1$s %2$s', '<strong>#' . $item['numero_pedido_wp'] . '</strong>', $this->row_actions($actions));
        } else {
            if (in_array($item['estado'], array(1, 3, 10, 80))) {
                $actions = array(
                    'etiquetas' => sprintf('<a target="_blank" href="?page=%s&action=%s&pedido=%s&codigo_envio=%s">Imprimir Etiqueta</a>', esc_html($_REQUEST['page']), 'etiquetas', $item['numero_pedido_wp'], $item['codigo_nombre_app']),
                    'etiquetas_zebra' => sprintf('<a target="_blank" href="?page=%s&action=%s&pedido=%s&codigo_envio=%s&zebra=1">' . __('Imprimir Etiqueta Zebra') . '</a>', esc_html($_REQUEST['page']), 'etiquetas_zebra', $item['numero_pedido_wp'], $item['codigo_nombre_app']),
                );

                return sprintf('%1$s %2$s', '<strong>#' . $item['numero_pedido_wp'] . '</strong>', $this->row_actions($actions));
            } else {
                $actions = array();
                return sprintf('%1$s %2$s', '<strong>#' . $item['numero_pedido_wp'] . '</strong>', $this->row_actions($actions));
            }
        }
    }

    function prepare_items($search = '') {

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $datos = $this->crear_lista_pedidos($search);
        //usort($datos, array(&$this, 'usort_reorder'));
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_items = count($datos);
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page
        ));

        $this->items = array_slice($datos, ($current_page - 1) * $per_page, $per_page);
    }

    function crear_lista_pedidos($search) {
        global $myListTable;
        $datos_array = array();
        $datos_array['usuario_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_usuario_servicio');
        $datos_array['password_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_password_servicio');
        $datos_array['fecha_primer_pedido'] = $myListTable->obtener_primera_orden('grupoimpultec_first_order');
        if (!($datos_array['fecha_primer_pedido'] > 0)) {
            $datos_array['fecha_primer_pedido'] = 30;
        }
        $datos_array['servicio'] = $GLOBALS['servicio'];
        $datos_array['id_usuario'] = grupoimpultec_getUserId($datos_array);
        $array_envios_servicios_externos = json_decode(
                grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
            'password_servicio' => $datos_array['password_servicio'],
            'id_usuario' => $datos_array['id_usuario'],
            'servicio' => $datos_array['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_lista_envios_api_usuario'), true
        );

        $contador_pedidos = 1;
        $array_pedidos = array();
        $array_solicitud = array(
            'limit' => 200,
            'order_date' => date('Y-m-d', strtotime('-' . $datos_array['fecha_primer_pedido'] . ' days')) . '...' . date('Y-m-d'),
            'orderby' => 'date',
            'order' => 'DESC',
            'type' => 'shop_order');
        if (get_option('grupoimpultec_show_only_completed') == 1) {
            $array_solicitud['status'] = 'completed';
        }
        if ($search != '') {
            $array_solicitud['billing_first_name'] = $search;
            $array_solicitud['get_shipping_address_1'] = $search;
        }
        foreach (wc_get_orders($array_solicitud) as $pedido) {
            $data_pedido = $pedido->get_data();

            $array_pedido = array();
            $array_pedido['ID'] = $contador_pedidos;
            $array_pedido['numero_pedido_wp'] = $pedido->get_id();
            $shipping_address = $pedido->get_address('shipping');
            $billing_address = $pedido->get_address('billing');
            if (strlen($shipping_address['address_1']) < 5) {
                $shipping_address = $pedido->get_address('billing');
            }
            $array_pedido['cajas'] = '';
            $datos_array['array_bultos'] = grupoimpultec_obtener_datos_bulto_pedido($pedido)['array_bultos'];
            if (get_option('grupoimpultec_tipo_calculo_precio_p') == 2) {
                $bultos_calculados_por_caja = grupoimpultec_calcular_nuevos_bultos($datos_array['array_bultos']);
                if (array_key_exists('1', $bultos_calculados_por_caja) && $bultos_calculados_por_caja[1]['peso'] == 50000) {
                    $array_pedido['cajas'] = __('El pedido no cabe en las cajas establecidas');
                } else if (array_key_exists('1', $bultos_calculados_por_caja)) {
                    $array_pedido['cajas'] = count($bultos_calculados_por_caja);
                }
            }

            $array_pedido['usuario_wp'] = $shipping_address['first_name'] . ' ' . $shipping_address['last_name'] . ', ' . $shipping_address['address_1'] . ' ' . $shipping_address['address_2'] . ' - ' . $shipping_address['postcode'] . ' - ' . $shipping_address['city'] . ' (' . $shipping_address['country'] . ')';
            $key = 'WP_' . $datos_array['id_usuario'] . '_' . $pedido->get_id();

            if (array_key_exists($key, $array_envios_servicios_externos)) {
                $array_pedido['seguimiento'] = $array_envios_servicios_externos[$key]['seguimiento'];
                $array_pedido['codigo_nombre_app'] = $array_envios_servicios_externos[$key]['codigo_envio'];
                $array_pedido['nombre_estado'] = $array_envios_servicios_externos[$key]['nombre_estado'];
                $array_pedido['estado'] = $array_envios_servicios_externos[$key]['estado'];
                $array_pedido['cajas'] = '';
            } else {
                $array_pedido['seguimiento'] = '';
                $array_pedido['codigo_nombre_app'] = '';
                $array_pedido['nombre_estado'] = '';
                $array_pedido['estado'] = 0;
            }

            $contador_pedidos++;
            array_push($array_pedidos, $array_pedido);
        }

        return $array_pedidos;
    }

}

function grupoimpultec_my_add_menu_items() {
    $hook_load = add_menu_page(__('Pedidos Woocommerce finalizados') . ' ', $GLOBALS['nombre_app'] . ' ', 'activate_plugins', 'grupoimpultec', 'grupoimpultec_my_render_list_page', 'dashicons-schedule');
    add_action("load-$hook_load", 'grupoimpultec_add_options');
    add_action('tarifas', 'tarifas');
    add_action('etiquetas', 'etiquetas');
    add_action('etiquetas_zebra', 'etiquetas_zebra');
}

function grupoimpultec_add_options() {
    global $myListTable;
    $option = 'per_page';
    $args = array(
        'label' => __('Pedidos'),
        'default' => 10,
        'option' => 'pedidos_per_page'
    );
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_jquery_ui_style');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_jquery');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_bootstrap_style');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_bootstrap_js');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_own_style');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_own_js');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_create_ship_js');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_moment_js');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_horas_recogida_js');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_reembolso_js');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_seguro_js');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_fin_envio_js');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_switch_iva_js');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_envios_finalizados_js');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_google_maps_js');
    add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_maps_js');


    add_screen_option($option, $args);
    $myListTable = new Grupoimpultec_orders_list();
}

function grupoimpultec_my_render_list_page() {
    global $myListTable;
    $datos_array['usuario_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_password_servicio');
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $datos_array['id_usuario'] = grupoimpultec_getUserId($datos_array);
    $array_ultima_version = grupoimpultec_getLastVersion($datos_array);
    $saldo = grupoimpultec_getBalance($datos_array);

    $credito = json_decode(
                    grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
                'password_servicio' => $datos_array['password_servicio'],
                'id_usuario' => $datos_array['id_usuario'],
                'servicio' => $datos_array['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/usuario_a_credito'), true
            )['usuario_a_credito'];
    if ($credito == 1) {
        $saldo_o_credito = __('Consumo mes');
    } else {
        $saldo_o_credito = __('Saldo') . ' ' . $GLOBALS['nombre_app'];
    }
    $direcciones_remitente = json_decode(
            grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
        'password_servicio' => $datos_array['password_servicio'],
        'id_usuario' => $datos_array['id_usuario'],
        'servicio' => $datos_array['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_listado_direcciones_parcial_usuario'), true
    );



    include('views/cabecera_general.php');
    include('views/seleccion_remitente.php');
    if (!isset($_GET['action']) && !isset($_POST['action'])) {
        include('views/tabla_envios_finalizados.php');
    }
    if (isset($_POST['action']) && $_POST['action'] == 'crear_envio') {
        $numero_pedido_wp = sanitize_text_field($_POST['numero_pedido_wp']);
        $id_agencia = sanitize_text_field($_POST['id_agencia']);
        $importe = sanitize_text_field($_POST['importe']);
        $array_bultos_defecto = array();
        $numero_bultos_defecto = 0;
        if (isset($_GET['numero_bultos_defecto'])) {
            $numero_bultos_defecto = sanitize_text_field($_GET['numero_bultos_defecto']);
            if ($numero_bultos_defecto > 0) {
                for ($i = 1; $i <= $numero_bultos_defecto; $i++) {
                    $array_bultos_defecto[$i] = array();
                    $array_bultos_defecto[$i]['peso'] = sanitize_text_field($_GET['peso_bulto_defecto_' . $i]);
                    $array_bultos_defecto[$i]['alto'] = sanitize_text_field($_GET['alto_bulto_defecto_' . $i]);
                    $array_bultos_defecto[$i]['ancho'] = sanitize_text_field($_GET['ancho_bulto_defecto_' . $i]);
                    $array_bultos_defecto[$i]['largo'] = sanitize_text_field($_GET['largo_bulto_defecto_' . $i]);
                }
            }
        }
        grupoimpultec_crear_envio($numero_pedido_wp, $id_agencia, $importe, $numero_bultos_defecto, $array_bultos_defecto);
    } else {
        if (isset($_GET['action']) && isset($_GET['pedido']) && $_GET['action'] == 'tarifas') {
            $numero_pedido_wp = sanitize_text_field($_GET['pedido']);
            $direccion_remitente = 0;
            $numero_bultos_defecto = 0;
            $array_bultos_defecto = array();
            if (isset($_GET['check_bultos_defecto']) && $_GET['check_bultos_defecto'] == 1) {
                $numero_bultos_defecto = sanitize_text_field($_GET['numero_bultos_defecto']);
                for ($i = 1; $i <= $numero_bultos_defecto; $i++) {
                    $array_bultos_defecto[$i] = array();
                    $array_bultos_defecto[$i]['peso'] = sanitize_text_field($_GET['peso_bulto_defecto_' . $i]);
                    $array_bultos_defecto[$i]['alto'] = sanitize_text_field($_GET['alto_bulto_defecto_' . $i]);
                    $array_bultos_defecto[$i]['ancho'] = sanitize_text_field($_GET['ancho_bulto_defecto_' . $i]);
                    $array_bultos_defecto[$i]['largo'] = sanitize_text_field($_GET['largo_bulto_defecto_' . $i]);
                }
            }
            $direccion_remitente = sanitize_text_field($_GET['dr']);
            $numero_pedido_wp = sanitize_text_field($_GET['pedido']);
            grupoimpultec_listar_tarifas($numero_pedido_wp, $numero_bultos_defecto, $array_bultos_defecto, $direccion_remitente);
        }
        if (isset($_GET['action']) && isset($_GET['codigo_envio']) && ($_GET['action'] == 'etiquetas' || $_GET['action'] == 'etiquetas_zebra')) {
            if (isset($_GET['zebra'])) {
                $zebra = sanitize_text_field($_GET['zebra']);
            } else {
                $zebra = 0;
            }
            grupoimpultec_mostrar_etiquetas(sanitize_text_field($_GET['codigo_envio']), $zebra);
        }
        if (isset($_GET['action']) && $_GET['action'] == 'preparar_crear_envio') {
            $numero_pedido_wp = sanitize_text_field($_GET['nwp']);
            $id_agencia = sanitize_text_field($_GET['ag']);
            $importe = sanitize_text_field($_GET['xs']);
            $porcentaje_reembolso = sanitize_text_field($_GET['pcr']);
            $porcentaje_seguro = sanitize_text_field($_GET['ps']);
            $permite_reembolsos = sanitize_text_field($_GET['per']);
            $permite_recoger = sanitize_text_field($_GET['pp']);
            $permite_no_recoger = sanitize_text_field($_GET['pnp']);
            $maxima_cantidad_reembolso = sanitize_text_field($_GET['mxcr']);
            $minima_cantidad_reembolso = sanitize_text_field($_GET['micr']);
            $maxima_cantidad_seguro = sanitize_text_field($_GET['mxcs']);
            $tipo_cliente = sanitize_text_field($_GET['tc']);
            $iva_exento = sanitize_text_field($_GET['ie']);
            $iva = sanitize_text_field($_GET['iv']);
            $servicio_recogida = sanitize_text_field($_GET['sr']);
            $id_agencia_madre = sanitize_text_field($_GET['idm']);
            $agencia_mapa_origen = sanitize_text_field($_GET['amo']);
            $agencia_mapa_destino = sanitize_text_field($_GET['amd']);
            $numero_bultos_defecto = 0;
            $peso_bultos_defecto = 0;
            $alto_bultos_defecto = 0;
            $ancho_bultos_defecto = 0;
            $largo_bultos_defecto = 0;
            $direccion_remitente = 1;
            $array_bultos_defecto = array();
            if (isset($_GET['numero_bultos_defecto'])) {
                $numero_bultos_defecto = sanitize_text_field($_GET['numero_bultos_defecto']);
                for ($i = 1; $i <= $numero_bultos_defecto; $i++) {
                    $array_bultos_defecto[$i] = array();
                    $array_bultos_defecto[$i]['peso'] = sanitize_text_field($_GET['peso_bulto_defecto_' . $i]);
                    $array_bultos_defecto[$i]['alto'] = sanitize_text_field($_GET['alto_bulto_defecto_' . $i]);
                    $array_bultos_defecto[$i]['ancho'] = sanitize_text_field($_GET['ancho_bulto_defecto_' . $i]);
                    $array_bultos_defecto[$i]['largo'] = sanitize_text_field($_GET['largo_bulto_defecto_' . $i]);
                }
            }
            if (isset($_GET['peso_bultos_defecto'])) {
                $peso_bultos_defecto = sanitize_text_field($_GET['peso_bultos_defecto']);
            }
            if (isset($_GET['alto_bultos_defecto'])) {
                $alto_bultos_defecto = sanitize_text_field($_GET['alto_bultos_defecto']);
            }
            if (isset($_GET['ancho_bultos_defecto'])) {
                $ancho_bultos_defecto = sanitize_text_field($_GET['ancho_bultos_defecto']);
            }
            if (isset($_GET['largo_bultos_defecto'])) {
                $largo_bultos_defecto = sanitize_text_field($_GET['largo_bultos_defecto']);
            }
            if (isset($_GET['dr'])) {
                $direccion_remitente = sanitize_text_field($_GET['dr']);
            }
            grupoimpultec_preparar_crear_envio($numero_pedido_wp, $id_agencia, $importe, $porcentaje_reembolso, $porcentaje_seguro, $numero_bultos_defecto, $array_bultos_defecto, $direccion_remitente, $permite_reembolsos, $permite_recoger, $permite_no_recoger, $maxima_cantidad_reembolso, $minima_cantidad_reembolso, $maxima_cantidad_seguro, $tipo_cliente, $iva_exento, $iva, $servicio_recogida, $id_agencia_madre, $agencia_mapa_origen, $agencia_mapa_destino);
        }
    }
}

function grupoimpultec_crear_envio($numero_pedido_wp, $id_agencia, $importe, $numero_bultos_defecto, $array_bultos_defecto) {
    global $myListTable;
    global $woocommerce;
    $pedido = wc_get_order($numero_pedido_wp);
    $shipping_address = $pedido->get_address('shipping');
    $billing_address = $pedido->get_address('billing');
    if (strlen($shipping_address['address_1']) < 5) {
        $shipping_address = $pedido->get_address('billing');
    }
    $datos_array['usuario_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_password_servicio');
    $datos_array['id_usuario'] = sanitize_text_field($_POST['id_usuario']);
    $datos_array['id_pais_salida'] = sanitize_text_field($_POST['id_pais_salida']);
    $datos_array['id_pais_llegada'] = sanitize_text_field($_POST['id_pais_llegada']);
    $datos_array['id_direccion'] = sanitize_text_field($_POST['direccion_remitente']);
    $datos_array['servicio_recogida'] = sanitize_text_field($_POST['servicio_recogida']);
    $array_direccion_remitente = json_decode(
            grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
        'password_servicio' => $datos_array['password_servicio'],
        'id_usuario' => $datos_array['id_usuario'],
        'id_direccion' => $datos_array['id_direccion'],
        'servicio' => $GLOBALS['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_datos_direccion'), true
    );
    $datos_array['poblacion_salida'] = $array_direccion_remitente['poblacion'];
    $datos_array['pais_salida'] = $array_direccion_remitente['id_pais'];
    $datos_array['pais_llegada'] = json_decode(
                    grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
                'password_servicio' => $datos_array['password_servicio'],
                'id_usuario' => $datos_array['id_usuario'],
                'id_pais' => $datos_array['id_pais_llegada'],
                'servicio' => $GLOBALS['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_nombre_pais'), true
            )['nombre_pais'];
    $datos_array['id_pais'] = $array_direccion_remitente['id_pais'];
    $datos_array['iso_pais_salida'] = json_decode(
                    grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
                'password_servicio' => $datos_array['password_servicio'],
                'id_usuario' => $datos_array['id_usuario'],
                'id_pais' => $datos_array['id_pais_salida'],
                'servicio' => $GLOBALS['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_iso_pais'), true
            )['iso_pais'];
    $datos_array['iso_pais_llegada'] = $shipping_address['country'];
    $datos_array['tipo_envio'] = 19;
    $datos_array['codigos_origen'] = $array_direccion_remitente['codigo_postal'];
    $datos_array['codigos_destino'] = $shipping_address['postcode'];
    $datos_array['provincia_salida'] = json_decode(
                    grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
                'password_servicio' => $datos_array['password_servicio'],
                'id_usuario' => $datos_array['id_usuario'],
                'codigo_postal' => $datos_array['codigos_origen'],
                'id_pais' => $datos_array['id_pais_salida'],
                'servicio' => $GLOBALS['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_nombre_provincia'), true
            )['nombre_provincia'];
    $datos_array['provincia_llegada'] = json_decode(
                    grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
                'password_servicio' => $datos_array['password_servicio'],
                'id_usuario' => $datos_array['id_usuario'],
                'codigo_postal' => $datos_array['codigos_destino'],
                'id_pais' => $datos_array['id_pais_salida'],
                'servicio' => $GLOBALS['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_nombre_provincia'), true
            )['nombre_provincia'];
    $datos_array['pais_salida'] = $array_direccion_remitente['nombre_pais'];
    $datos_array['pais_llegada'] = json_decode(
                    grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
                'password_servicio' => $datos_array['password_servicio'],
                'id_usuario' => $datos_array['id_usuario'],
                'id_pais' => $datos_array['id_pais_llegada'],
                'servicio' => $GLOBALS['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_nombre_pais'), true
            )['nombre_pais'];


    $datos_array['poblacion_llegada'] = $shipping_address['city'];
    $datos_array['id_agencia'] = sanitize_text_field($_POST['id_agencia']);
    $datos_array['ag'][$id_agencia]['importe'] = $importe;
    $datos_array['ag'][$id_agencia]['importe_dto_promo'] = NULL;
    $datos_array['ag'][$id_agencia]['importe_dto'] = 0;
    if (isset($_POST['reembolso']) && $_POST['reembolso'] == 1 && $_POST['cantidad_reembolso'] > 0) {
        $datos_array['contrareembolso'] = 1;
        $datos_array['cantidad_reembolso'] = sanitize_text_field($_POST['cantidad_reembolso']);
    }

    $datos_array['num_cuenta'] = 0;
    if (isset($_POST['seguro']) && $_POST['seguro'] == 1 && $_POST['cantidad_seguro'] > 0) {
        $datos_array['seguro'] = 1;
        $datos_array['cantidad_seguro'] = sanitize_text_field($_POST['cantidad_seguro']);
    }

    $datos_array['porcentaje_seguro'] = 0;
    $datos_array['importe_seguro'] = 0;
    $datos_array['direccion_salida'] = $array_direccion_remitente['direccion'];
    $datos_array['direccion_llegada'] = $shipping_address['address_1'] . ' ' . $shipping_address['address_2'];
    $datos_array['estado'] = 6;
    $datos_array['fecha_hora_creacion'] = date('Y-m-d H:i:s');
    if (isset($_POST['no_recoger']) && $_POST['no_recoger'] == 1) {
        $datos_array['recoger_tienda'] = 1;
    } else {
        $datos_array['recoger_tienda'] = 0;
    }
    if ($datos_array['servicio_recogida'] != 1) {
        $datos_array['recoger_tienda'] = 1;
        $datos_array['fecha_recogida_aux'] = explode('/', date('d/m/Y'));
        $datos_array['hora_recogida_desde'] = '09:00';
        $datos_array['hora_recogida_hasta'] = '14:00';
    } else {
        $datos_array['fecha_recogida_aux'] = explode('/', sanitize_text_field($_POST['fecha_recogida']));
        if (isset($_POST['id_d_intervalo'])) {
            $datos_array['hora_recogida_desde'] = sanitize_text_field($_POST['id_d_intervalo']);
        }
        if (isset($_POST['id_h_intervalo'])) {
            $datos_array['hora_recogida_hasta'] = sanitize_text_field($_POST['id_h_intervalo']);
        }
    }
    $datos_array['fecha_recogida_aux'] = $datos_array['fecha_recogida_aux'][2] . '-' . $datos_array['fecha_recogida_aux'][1] . '-' . $datos_array['fecha_recogida_aux'][0];
    $datos_array['fecha_recogida'] = $datos_array['fecha_recogida_aux'];

    $datos_array['valor_mercancia'] = 0;
    $datos_array['valor_mercancia_correos'] = 0;
    $datos_array['id_promo'] = NULL;
    $datos_array['nombre_salida'] = $array_direccion_remitente['nombre'];
    $datos_array['nombre_llegada'] = $shipping_address['first_name'] . ' ' . $shipping_address['last_name'];
    $datos_array['telefono_salida'] = $array_direccion_remitente['telefono'];
    $datos_array['telefono_llegada'] = $billing_address['phone'];
    $user = $pedido->get_user();
    if ($datos_array['telefono_llegada'] == '') {
        $datos_array['telefono_llegada'] = $array_direccion_remitente['telefono'];
    }
    if (!array_key_exists('email', $shipping_address)) {
        $shipping_address['email'] = $user->user_email;
    }
    $datos_array['email_salida'] = get_bloginfo('admin_email');
    $datos_array['email_llegada'] = $shipping_address['email'];
    $datos_array['datos_factura_otros'] = '';
    $datos_array['contenido_envio'] = '';
    $datos_array['dropshipping'] = 0;
    $datos_array['forma_pago_envio'] = 4;
    $datos_array['codigo_envio_servicio'] = 'WP_' . $datos_array['id_usuario'] . '_' . $numero_pedido_wp;
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $datos_array['mercancia_aduana'] = array();
    $datos_array['cn'] = $GLOBALS['plugin_cn_version'];
    if (isset($_POST['entrega_oficina_destino']) && $_POST['entrega_oficina_destino'] == 1 && isset($_POST['select_oficinas_destino']) && $_POST['select_oficinas_destino'] != '') {
        $datos_array['select_oficinas_destino'] = sanitize_text_field($_POST['select_oficinas_destino']);
        $datos_array['bring_correos_express'] = sanitize_text_field($_POST['entrega_oficina_destino']);
    }
    if ($numero_bultos_defecto > 0) {
        $datos_array['array_bultos'] = $array_bultos_defecto;
        array_unshift($datos_array['array_bultos'], array(0 => array()));
    } else {
        $datos_array_insertar = grupoimpultec_obtener_datos_bulto_pedido($pedido);
        $datos_array['array_bultos'] = $datos_array_insertar['array_bultos'];
        $datos_array['mercancia_aduana'] = $datos_array_insertar['mercancia_aduana'];
    }

    foreach ($datos_array['mercancia_aduana'] as $key => $value) {
        if ($key == 0) {
            $continue;
        }
        $datos_array['array_bultos'][$key]['contenido'] = $value['contenido'];
        $datos_array['array_bultos'][$key]['valor'] = $value['valor'];
        $datos_array['array_bultos'][$key]['taric'] = $value['taric'];
    }
    if (get_option('grupoimpultec_tipo_calculo_precio_p') == 2) {
        $datos_array['array_bultos'] = grupoimpultec_calcular_nuevos_bultos($datos_array['array_bultos']);
    }
    if (!array_key_exists(0, $datos_array['array_bultos'])) {
        array_unshift($datos_array['array_bultos'], array());
    }
    $respuesta = json_decode(
            grupoimpultec_curlJson($datos_array, 'http://www.' . $GLOBALS['api_server'] . '/json_interface/crear_envio'), true
    );
    if ($respuesta['resultado'] != '1') {
        $resultado_texto_fin_envio = __('Error al crear el envío');
    } else {
        $resultado_texto_fin_envio = __('Envío creado correctamente');
    }
    include('views/fin_envio.php');
}

function grupoimpultec_obtener_datos_bulto_pedido($pedido) {
    $items = $pedido->get_items();
    $datos_array = array();
    if (get_option('grupoimpultec_tipo_calculo_precio_p') == 2) {
        $datos_array = grupoimpultec_iteraciones_con_calculo_precio($datos_array, $items);
    } else {
        $datos_array = grupoimpultec_iteraciones_sin_calculo_precio($datos_array, $items);
    }
    $datos_array['array_bultos'] = grupoimpultec_convertir_medidas($datos_array['array_bultos']);
    return $datos_array;
}

function grupoimpultec_iteraciones_sin_calculo_precio($datos_array, $items) {
    $contador_bultos = 1;
    foreach ($items as $item) {
        $product = $item->get_product();
        if (!is_object($product)) {
            $datos_array['array_bultos'][$contador_bultos]['peso'] = 1;
            $datos_array['array_bultos'][$contador_bultos]['alto'] = 1;
            $datos_array['array_bultos'][$contador_bultos]['largo'] = 1;
            $datos_array['array_bultos'][$contador_bultos]['ancho'] = 1;
        } else {
            if ($product->get_weight() > 0) {
                $datos_array['array_bultos'][$contador_bultos]['peso'] = $product->get_weight() * $item['qty'];
            } else {
                $datos_array['array_bultos'][$contador_bultos]['peso'] = 1;
            }
            if ($product->get_height() > 0) {
                $datos_array['array_bultos'][$contador_bultos]['alto'] = $product->get_height() * pow($item['qty'], 1 / 3);
            } else {
                $datos_array['array_bultos'][$contador_bultos]['alto'] = 1;
            }
            if ($product->get_length() > 0) {
                $datos_array['array_bultos'][$contador_bultos]['largo'] = $product->get_length() * pow($item['qty'], 1 / 3);
            } else {
                $datos_array['array_bultos'][$contador_bultos]['largo'] = 1;
            }
            if ($product->get_width() > 0) {
                $datos_array['array_bultos'][$contador_bultos]['ancho'] = $product->get_width() * pow($item['qty'], 1 / 3);
            } else {
                $datos_array['array_bultos'][$contador_bultos]['ancho'] = 1;
            }
            if (isset($_POST['mercancia_aduana_contenido_' . $contador_bultos])) {
                $datos_array['mercancia_aduana'][$contador_bultos]['contenido'] = sanitize_text_field($_POST['mercancia_aduana_contenido_' . $contador_bultos]);
            }
            if (isset($_POST['mercancia_aduana_valor_' . $contador_bultos])) {
                $datos_array['mercancia_aduana'][$contador_bultos]['valor'] = sanitize_text_field($_POST['mercancia_aduana_valor_' . $contador_bultos]);
            }
            if (isset($_POST['mercancia_aduana_taric_' . $contador_bultos])) {
                $datos_array['mercancia_aduana'][$contador_bultos]['taric'] = sanitize_text_field($_POST['mercancia_aduana_taric_' . $contador_bultos]);
            }
        }
        $contador_bultos++;
    }
    return $datos_array;
}

function grupoimpultec_iteraciones_con_calculo_precio($datos_array, $items) {
    $contador_bultos = 1;
    foreach ($items as $item) {
        $product = $item->get_product();
        for ($i = 1; $i <= $item['qty']; $i++) {
            if (method_exists($product, 'get_weight')) {
                $datos_array['array_bultos'][$contador_bultos]['peso'] = $product->get_weight();
            } else {
                $datos_array['array_bultos'][$contador_bultos]['peso'] = 1;
            }
            if (method_exists($product, 'get_height')) {
                $datos_array['array_bultos'][$contador_bultos]['alto'] = $product->get_height();
            } else {
                $datos_array['array_bultos'][$contador_bultos]['alto'] = 1;
            }
            if (method_exists($product, 'get_width')) {
                $datos_array['array_bultos'][$contador_bultos]['ancho'] = $product->get_width();
            } else {
                $datos_array['array_bultos'][$contador_bultos]['ancho'] = 1;
            }
            if (method_exists($product, 'get_length')) {
                $datos_array['array_bultos'][$contador_bultos]['largo'] = $product->get_length();
            } else {
                $datos_array['array_bultos'][$contador_bultos]['largo'] = 1;
            }

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
            if (isset($_POST['mercancia_aduana_contenido_' . $contador_bultos])) {
                $datos_array['mercancia_aduana'][$contador_bultos]['contenido'] = sanitize_text_field($_POST['mercancia_aduana_contenido_' . $contador_bultos]);
            }
            if (isset($_POST['mercancia_aduana_valor_' . $contador_bultos])) {
                $datos_array['mercancia_aduana'][$contador_bultos]['valor'] = sanitize_text_field($_POST['mercancia_aduana_valor_' . $contador_bultos]);
            }
            if (isset($_POST['mercancia_aduana_taric_' . $contador_bultos])) {
                $datos_array['mercancia_aduana'][$contador_bultos]['taric'] = sanitize_text_field($_POST['mercancia_aduana_taric_' . $contador_bultos]);
            }
            $contador_bultos++;
        }
    }
    return $datos_array;
}

function grupoimpultec_preparar_crear_envio($numero_pedido_wp, $id_agencia, $importe, $porcentaje_reembolso, $porcentaje_seguro, $numero_bultos_defecto, $array_bultos_defecto, $direccion_remitente, $permite_reembolsos, $permite_recoger, $permite_no_recoger, $maxima_cantidad_reembolso, $minima_cantidad_reembolso, $maxima_cantidad_seguro, $tipo_cliente, $iva_exento, $iva, $servicio_recogida, $id_agencia_madre, $agencia_mapa_origen, $agencia_mapa_destino) {

    global $myListTable;
    global $woocommerce;
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/agencia_permite_dropshipping';
    $datos_array['usuario_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_password_servicio');
    $datos_array['api_key_google_maps'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_api_key_google_maps');
    $datos_array['id_usuario'] = grupoimpultec_getUserId($datos_array);
    $datos_array['id_agencia'] = $id_agencia;
    $datos_array['id_agencia_madre'] = $id_agencia_madre;
    $datos_array['servicio_recogida'] = $servicio_recogida;
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $pedido = wc_get_order($numero_pedido_wp);
    $shipping_address = $pedido->get_address('shipping');
    $billing_address = $pedido->get_address('billing');
    if (strlen($shipping_address['address_1']) < 5) {
        $shipping_address = $pedido->get_address('billing');
    }
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_nombre_agencia';
    $datos_array['nombre_agencia'] = json_decode(grupoimpultec_curlJson($datos_array, $url), true);
    $datos_array['id_direccion'] = $direccion_remitente;
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_datos_direccion';
    $array_direccion_remitente = json_decode(grupoimpultec_curlJson($datos_array, $url), true);
    $datos_array['codigos_origen'] = $array_direccion_remitente['codigo_postal'];
    $datos_array['id_pais'] = $array_direccion_remitente['id_pais'];
    $datos_array['id_pais_salida'] = $array_direccion_remitente['id_pais'];
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_iso_pais';
    $datos_array['iso_pais_salida'] = json_decode(grupoimpultec_curlJson($datos_array, $url), true)['iso_pais'];
    if (strlen($datos_array['iso_pais_salida']) != 2) {
        switch ($GLOBALS['id_pais_api']) {
            case 1:
                $datos_array['iso_pais_salida'] = 'ES';
                break;
            case 7:
                $datos_array['iso_pais_salida'] = 'FR';
                break;
        }
    }

    $datos_array['id_pais'] = '';
    $datos_array['codigos_destino'] = $shipping_address['postcode'];
    $datos_array['iso_pais_llegada'] = $shipping_address['country'];
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_id_pais_llegada';
    $datos_array['id_pais_llegada'] = json_decode(grupoimpultec_curlJson($datos_array, $url), true)['id_pais_llegada'];
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/usuario_cif_intracomunitario';
    $usuario_cif_intracomunitario = json_decode(grupoimpultec_curlJson($datos_array, $url), true)['usuario_cif_intracomunitario'];
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_zona_pais_cp';
    $datos_array['id_pais'] = $datos_array['id_pais_salida'];
    $datos_array['codigo_postal'] = $datos_array['codigos_origen'];
    $id_zona_salida = json_decode(grupoimpultec_curlJson($datos_array, $url), true)['id_zona'];
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_zona_pais_cp';
    $datos_array['id_pais'] = $datos_array['id_pais_llegada'];
    $datos_array['codigo_postal'] = $datos_array['codigos_destino'];
    $id_zona_llegada = json_decode(grupoimpultec_curlJson($datos_array, $url), true)['id_zona'];
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_imagen_agencia_jpg';
    $imagen_agencia = json_decode(grupoimpultec_curlJson($datos_array, $url), true);
    $iva = grupoimpultec_getIva($datos_array);
    unset($datos_array['id_pais']);
    unset($datos_array['codigo_postal']);
    unset($datos_array['id_zona']);
    unset($datos_array['tipo_usuario']);
    include('views/cabecera_creacion_envio.php');
    echo('<form method="post" id="formulario_creacion_envio">');
    $datos_array['id_agencia_madre'] = $id_agencia_madre;
    if ($agencia_mapa_destino == 1 || $id_agencia_madre == 1) {
        $oficina_destino_seleccionada_por_cliente = '0';
        $oficina_destino_seleccionada_por_cliente = pedido_tiene_oficina_destino($numero_pedido_wp);
        include('views/mapa_destino.php');
        echo('<script>');
        echo('php_vars_maps_js.api_server = "' . $GLOBALS['api_server'] . '";' .
        'php_vars_maps_js.id_agencia = "' . $datos_array['id_agencia'] . '";' .
        'php_vars_maps_js.id_agencia_madre = "' . $id_agencia_madre . '";' .
        'php_vars_maps_js.codigo_postal_oficina = "' . $datos_array['codigos_destino'] . '";' .
        'php_vars_maps_js.select_oficinas_destino = "select_oficinas_destino";' .
        'php_vars_maps_js.map_oficinas_destino = "map_oficinas_destino";');
        echo('popular_mapa(php_vars_maps_js.api_server,php_vars_maps_js.id_agencia, php_vars_maps_js.id_agencia_madre, php_vars_maps_js.codigo_postal_oficina, php_vars_maps_js.select_oficinas_destino, php_vars_maps_js.map_oficinas_destino);');
        echo('</script>');

        if ($oficina_destino_seleccionada_por_cliente != '0') {
            echo(__('Este pedido se ha realizado indicando una oficina de destino con código: ' . $oficina_destino_seleccionada_por_cliente));
            echo('<script>');
            echo('establecer_oficina_seleccionada_cliente("' . $oficina_destino_seleccionada_por_cliente . '");');
            echo('</script>');
        }
        if (chrono_tiene_entrega_destino($numero_pedido_wp)) {
            echo('<script>');
            echo('establecer_entrega_destino();');
            echo('</script>');
        }
    }

    if ($permite_recoger == 1) {

        include('views/fecha_recogida.php');
    }
    include('views/datos_mercancia.php');
    if ($permite_reembolsos == 1) {
        include('views/reembolso.php');
    }
    include('views/seguro.php');
    if ($id_agencia_madre == 2) {
        $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_categorias_correos';
        $datos_array['rs_categorias_correos'] = json_decode(grupoimpultec_curlJson($datos_array, $url), true)['rs_categorias_correos'];
        include('views/categorias_correos.php');
    }
    include('views/datos_envio.php');
    include('views/bultos_envio.php');
    $contador_bultos = 1;
    if ($numero_bultos_defecto > 0) {
        for ($i = 1; $i <= $numero_bultos_defecto; $i++) {
            $datos_array['array_bultos'][$contador_bultos]['peso'] = $array_bultos_defecto[$contador_bultos]['peso'];
            $datos_array['array_bultos'][$contador_bultos]['alto'] = $array_bultos_defecto[$contador_bultos]['alto'];
            $datos_array['array_bultos'][$contador_bultos]['largo'] = $array_bultos_defecto[$contador_bultos]['largo'];
            $datos_array['array_bultos'][$contador_bultos]['ancho'] = $array_bultos_defecto[$contador_bultos]['ancho'];
            $datos_array['array_bultos'][$contador_bultos]['cantidad'] = 1;
            $datos_array['array_bultos'][$contador_bultos]['descripcion'] = 'producto por defecto';
            $contador_bultos++;
        }
    } else {
        $items = $pedido->get_items();
        if (get_option('grupoimpultec_tipo_calculo_precio_p') == 2) {
            $datos_array = grupoimpultec_iteraciones_con_calculo_precio($datos_array, $items);
        } else {
            $datos_array = grupoimpultec_iteraciones_sin_calculo_precio($datos_array, $items);
        }
        if (!array_key_exists(0, $datos_array)) {
            array_unshift($datos_array['array_bultos'], array());
        }
        $datos_array['array_bultos_original'] = $datos_array['array_bultos'];
        $datos_array['array_bultos'] = grupoimpultec_convertir_medidas($datos_array['array_bultos']);
        if (get_option('grupoimpultec_tipo_calculo_precio_p') == 2) {
            unset($datos_array['array_bultos'][0]);
            $datos_array['array_bultos'] = grupoimpultec_calcular_nuevos_bultos($datos_array['array_bultos']);
            unset($datos_array['array_bultos'][0]);
            array_unshift($datos_array['array_bultos'], array());
            //die("datos_array['array_bultos_original'] es ".var_export($datos_array['array_bultos_original'],true)."<p>datos_array['array_bultos'] es ".var_export($datos_array['array_bultos'],true));        
        }
    }
    unset($datos_array['array_bultos_original'][0]);
    if (get_option('grupoimpultec_tipo_calculo_precio_p') == 2 && !($numero_bultos_defecto > 0)) {
        echo (__('Listado de bultos originales') . ':<p>');
        $contador_bultos_originales = 1;
        if (array_key_exists('array_bultos_original', $datos_array)) {
            foreach ($datos_array['array_bultos_original'] as $bulto_original) {
                echo(__('Bulto') . ' ') . $contador_bultos_originales . ': ';
                echo (__('Peso') . ': ') . number_format($bulto_original['peso'], 2) . ' cm. ';
                echo (__('Ancho') . ': ') . number_format($bulto_original['ancho'], 2) . ' cm. ';
                echo (__('Alto') . ': ') . number_format($bulto_original['alto'], 2) . ' cm. ';
                echo (__('Largo') . ': ') . number_format($bulto_original['largo'], 2) . ' cm.<p>';
                $contador_bultos_originales++;
            }
        }
    }
    $contador_bultos = 1;
    unset($datos_array['array_bultos'][0]);
    foreach ($datos_array['array_bultos'] as $bulto) {
        if (!array_key_exists('cantidad', $bulto)) {
            $bulto['cantidad'] = 1;
        }
        if (!array_key_exists('descripcion', $bulto)) {
            $bulto['descripcion'] = '';
        }
        include('views/bulto_envio.php');
        $contador_bultos++;
    }
    array_unshift($datos_array['array_bultos'], array());
    include('views/fin_bultos_envio.php');
    include('views/resumen_precio.php');
    include('views/crear_envio.php');
}

function pedido_tiene_oficina_destino($numero_pedido_wp) {
    $pedido = wc_get_order($numero_pedido_wp);
    $grupoimpultec_select_oficinas_destino = $pedido->get_meta('grupoimpultec_select_oficinas_destino');
    if ($grupoimpultec_select_oficinas_destino != '') {
        return($grupoimpultec_select_oficinas_destino);
    }
    return '0';
}

function chrono_tiene_entrega_destino($numero_pedido_wp) {
    $pedido = wc_get_order($numero_pedido_wp);
    $grupoimpultec_id_agencia = $pedido->get_meta('grupoimpultec_id_agencia');
    $grupoimpultec_select_oficinas_destino = $pedido->get_meta('grupoimpultec_select_oficinas_destino');
    if ($grupoimpultec_select_oficinas_destino != '' && $grupoimpultec_id_agencia == 2) {
        return true;
    }
    return false;
}

function grupoimpultec_mostrar_etiquetas($codigo_envio, $zebra = 0) {
    global $myListTable;
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_etiquetas_envio';
    $datos_array['usuario_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_password_servicio');
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $datos_array['codigo_envio'] = $codigo_envio;
    $datos_array['zebra'] = $zebra;
    $datos_array['borrar_previo'] = 1;
    $datos_array['id_usuario'] = grupoimpultec_getUserId($datos_array);
    $etiqueta = json_decode(grupoimpultec_curlJson($datos_array, $url), true)['url_etiqueta'];
    if ($etiqueta != '') {
        header('Location: https://www.' . $GLOBALS['api_server'] . '/recursos/etiquetas/' . $etiqueta);
    }
}

function grupoimpultec_listar_tarifas($numero_pedido, $numero_bultos_defecto, $array_bultos_defecto, $direccion_remitente) {
    global $myListTable;
    $datos_array['usuario_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_password_servicio');
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $datos_array['id_usuario'] = grupoimpultec_getUserId($datos_array);
    $datos_array['id_direccion'] = $direccion_remitente;
    $array_direccion_remitente = json_decode(
            grupoimpultec_curlJson(array('usuario_servicio' => $datos_array['usuario_servicio'],
        'password_servicio' => $datos_array['password_servicio'],
        'id_usuario' => $datos_array['id_usuario'],
        'id_direccion' => $datos_array['id_direccion'],
        'servicio' => $datos_array['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_datos_direccion'), true
    );
    $peso_kgs = 0;
    $peso_volumetrico = 0;
    $pedido = wc_get_order($numero_pedido);
    if (sizeof($pedido->get_items()) > 0) {
        $contador_bultos = 1;
        $datos_array['array_bultos'][0] = array();
        if ($numero_bultos_defecto == 0) {
            $items = $pedido->get_items();
            if (get_option('grupoimpultec_tipo_calculo_precio_p') == 2) {
                $datos_array = grupoimpultec_iteraciones_con_calculo_precio($datos_array, $items);
            } else {
                $datos_array = grupoimpultec_iteraciones_sin_calculo_precio($datos_array, $items);
            }
            $datos_array['array_bultos'] = grupoimpultec_convertir_medidas($datos_array['array_bultos']);
            if (get_option('grupoimpultec_tipo_calculo_precio_p') == 2) {

                unset($datos_array['array_bultos'][0]);
                $datos_array['array_bultos_original'] = $datos_array['array_bultos'];
                $datos_array['array_bultos'] = grupoimpultec_calcular_nuevos_bultos($datos_array['array_bultos']);
                unset($datos_array['array_bultos'][0]);
                array_unshift($datos_array['array_bultos'], array());
            }
        } else {
            array_unshift($array_bultos_defecto, array());
            $datos_array['array_bultos'] = $array_bultos_defecto;
            //die("datos_array es ".var_export($array_bultos_defecto,true));
        }
    }
    $shipping_address = $pedido->get_address('shipping');
    $billing_address = $pedido->get_address('billing');
    if (strlen($shipping_address['address_1']) < 5) {
        $shipping_address = $pedido->get_address('billing');
    }
    if (!array_key_exists(0, $datos_array['array_bultos'])) {
        array_unshift($datos_array['array_bultos'], array());
    }
    $user = $pedido->get_user();
    $datos_array['codigos_origen'] = $array_direccion_remitente['codigo_postal'];
    $datos_array['poblacion_salida'] = $array_direccion_remitente['poblacion'];
    $datos_array['direccion_salida'] = $array_direccion_remitente['direccion'];
    $datos_array['email_salida'] = $array_direccion_remitente['mail'];
    $datos_array['nombre_salida'] = $array_direccion_remitente['nombre'];
    $datos_array['telefono_salida'] = $array_direccion_remitente['telefono'];
    $datos_array['id_pais_salida'] = $array_direccion_remitente['id_pais'];
    $datos_array['id_pais'] = $array_direccion_remitente['id_pais'];
    $datos_array['iso_pais_salida'] = json_decode(grupoimpultec_curlJson($datos_array, 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_iso_pais'), true)['iso_pais'];
    unset($datos_array['id_pais']);
    $datos_array['recoger_tienda'] = 0;
    $datos_array['codigos_destino'] = $shipping_address['postcode'];
    $datos_array['poblacion_llegada'] = $shipping_address['city'];
    $datos_array['direccion_llegada'] = $shipping_address['address_1'] . ' ' . $shipping_address['address_2'];
    $datos_array['nombre_llegada'] = $shipping_address['first_name'] . ' ' . $shipping_address['last_name'];
    $datos_array['telefono_llegada'] = $billing_address['phone'];
    $user = $pedido->get_user();
    if (!$datos_array['telefono_llegada'] == '') {
        $datos_array['telefono_llegada'] = $array_direccion_remitente['telefono'];
    }
    if (!array_key_exists('email', $shipping_address)) {
        $shipping_address['email'] = $user->user_email;
    }
    $datos_array['email_llegada'] = $shipping_address['email'];
    $datos_array['iso_pais_llegada'] = $shipping_address['country'];
    $datos_array['dni_llegada'] = '00000000t';
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_listado_agencias_precios';
    $datos_array['api_key_google_maps'] = $myListTable->obtener_credenciales_servicio('grupoimpultec_api_key_google_maps');
    $listado_agencias_precios = json_decode(grupoimpultec_curlJson($datos_array, $url), true)['datos_agencia2'];
    $informacion_listado_agencias_precios = json_decode(grupoimpultec_curlJson($datos_array, $url), true)['datos_vista'];
    if ($informacion_listado_agencias_precios['iva_exento'] != 1 && $informacion_listado_agencias_precios['ck_cif_intracomunitario'] != 1) {
        $iva_exento = 0;
    } else {
        $iva_exento = 1;
    }

    $iva = grupoimpultec_getIva($datos_array);
    if ($listado_agencias_precios != null) {
        foreach ($listado_agencias_precios as $key => $row) {
            $servicio_horas[$key] = $row['servicio_horas'];
            $servicio[$key] = $row['servicio'];
            $importes[$key] = $row['importe'];
            $estrellas[$key] = $row['estrellas'];
            $valoraciones[$key] = $row['num_valoraciones'];
            if (array_key_exists("oferta_flash", $row)) {
                $oferta_flash[$key] = $row['oferta_flash'];
            } else {
                $oferta_flash[$key] = $row['oferta_flash'] = 0;
            }
        }
        array_multisort($oferta_flash, SORT_DESC, $importes, SORT_ASC, $servicio_horas, SORT_ASC, $listado_agencias_precios);
    }
    include('views/cabecera_tabla.php');
    if (count($listado_agencias_precios) == 0) {
        echo(__('No se han encontrado tarifas para las especificaciones de este pedido. Compruebe las medidas y los pesos del mismo.') . '<p>');
        echo('<p>');
        $contador_bultos_originales = 1;
        $peso_total = 0;
        if (count($datos_array['array_bultos_original']) > 0) {
            foreach ($datos_array['array_bultos_original'] as $bulto_original) {
                echo(__('Bulto') . ' ') . $contador_bultos_originales . ':<p>';
                echo (__('Ancho') . ': ') . number_format($bulto_original['ancho'], 2) . ' cm.<p>';
                echo (__('Alto') . ': ') . number_format($bulto_original['alto'], 2) . ' cm.<p>';
                echo (__('Largo') . ': ') . number_format($bulto_original['largo'], 2) . ' cm.<p>';
                echo (__('Peso') . ': ') . number_format($bulto_original['peso'], 2) . ' kg.<p>';
                $contador_bultos_originales++;
                $peso_total = $peso_total + $bulto_original['peso'];
            }
        }
        echo(__('No es posible empaquetar en cajas de las siguientes características') . ':<p>');
        echo (__('Ancho') . ': ') . grupoimpultec_obtener_medidas_caja()['width'] . ' cm.<p>';
        echo (__('Alto') . ': ') . grupoimpultec_obtener_medidas_caja()['height'] . ' cm.<p>';
        echo (__('Largo') . ': ') . grupoimpultec_obtener_medidas_caja()['length'] . ' cm.<p>';
        echo (__('Peso máximo') . ': ') . get_option('grupoimpultec_max_weigth_box') . ' kg.<p>';
        if (get_option('grupoimpultec_max_weigth_box') > 50 && $peso_total > 50) {
            echo (__('Ha utilizado un peso máximo para su caja personalizada de más de 50 Kgs, y la suma de los bultos del envío también superan ese valor.</br>'));
            echo(__('Existen agencias que no puedan calcular precios para bultos de más de 50 Kgs.</br>'));
            echo(__('Pruebe a reducir el peso máximo admitido por su caja personalizada.'));
        }
    } else {
        foreach ($listado_agencias_precios as $agencia_precio) {
            if ($agencia_precio['importe_dto_promo'] > 0) {
                $importe = $agencia_precio['importe_dto_promo'];
            } else {
                $importe = $agencia_precio['importe'];
            }
            if ($iva_exento != 1) {
                $importe_sin_iva = $importe / (1 + ($iva / 100));
            } else {
                $importe_sin_iva = $importe;
            }
            switch ($agencia_precio['servicio_horas']) {
                case '12':
                case '13':
                case '14':
                case '24':
                    $tiempo_servicio = '24h.';
                    break;
                case '48':
                    $tiempo_servicio = '24/48h.';
                    break;
                case '72':
                    $tiempo_servicio = '48/72h.';
                    break;
                case '96':
                    $tiempo_servicio = '2-3 días.';
                    break;
                case '120':
                    $tiempo_servicio = '3-4 días.';
                    break;
                case '144':
                    $tiempo_servicio = '4-5 días.';
                    break;
                default:
                    $tiempo_servicio = '24/48h.';
                    break;
            }
            if ($numero_bultos_defecto > 0) {
                $enlace_bultos_defecto = '';
                if (array_key_exists('array_bultos', $datos_array) && count($datos_array['array_bultos']) > 0) {
                    foreach ($datos_array['array_bultos'] as $key => $value) {
                        if ($key == 0) {
                            continue;
                        }
                        $enlace_bultos_defecto .= '&peso_bulto_defecto_' . $key . '=' . $value['peso'] .
                                '&alto_bulto_defecto_' . $key . '=' . $value['alto'] .
                                '&ancho_bulto_defecto_' . $key . '=' . $value['ancho'] .
                                '&largo_bulto_defecto_' . $key . '=' . $value['largo'];
                    }
                }
                $enlace_bultos_defecto .= '&numero_bultos_defecto=' . $numero_bultos_defecto;
            } else {
                $enlace_bultos_defecto = '';
            }
            include('views/iteracion_resultados.php');
        }
    }
    echo '</tbody>';
    echo '</table>';
}

function grupoimpultec_convertir_medidas($array_bultos) {
    $weight_unit = get_option('woocommerce_weight_unit');
    $dimension_unit = get_option('woocommerce_dimension_unit');
    $contador_bultos = 1;
    foreach ($array_bultos as $bulto) {
        switch ($weight_unit) {
            case 'g':
                $array_bultos[$contador_bultos]['peso'] = $array_bultos[$contador_bultos]['peso'] / 1000;
                break;
            case 'lbs':
                $array_bultos[$contador_bultos]['peso'] = $array_bultos[$contador_bultos]['peso'] / 2.205;
                break;
            case 'oz':
                $array_bultos[$contador_bultos]['peso'] = $array_bultos[$contador_bultos]['peso'] / 35.274;
                break;
        }
        switch ($dimension_unit) {
            case 'm':
                $array_bultos[$contador_bultos]['largo'] = $array_bultos[$contador_bultos]['largo'] * 100;
                $array_bultos[$contador_bultos]['ancho'] = $array_bultos[$contador_bultos]['ancho'] * 100;
                $array_bultos[$contador_bultos]['alto'] = $array_bultos[$contador_bultos]['alto'] * 100;
                break;
            case 'mm':
                $array_bultos[$contador_bultos]['largo'] = $array_bultos[$contador_bultos]['largo'] / 10;
                $array_bultos[$contador_bultos]['ancho'] = $array_bultos[$contador_bultos]['ancho'] / 10;
                $array_bultos[$contador_bultos]['alto'] = $array_bultos[$contador_bultos]['alto'] / 10;
                break;
            case 'in':
                $array_bultos[$contador_bultos]['largo'] = $array_bultos[$contador_bultos]['largo'] * 2.54;
                $array_bultos[$contador_bultos]['ancho'] = $array_bultos[$contador_bultos]['ancho'] * 2.54;
                $array_bultos[$contador_bultos]['alto'] = $array_bultos[$contador_bultos]['alto'] * 2.54;
                break;
            case 'oz':
                $array_bultos[$contador_bultos]['largo'] = $array_bultos[$contador_bultos]['largo'] * 91.44;
                $array_bultos[$contador_bultos]['ancho'] = $array_bultos[$contador_bultos]['ancho'] * 91.44;
                $array_bultos[$contador_bultos]['alto'] = $array_bultos[$contador_bultos]['alto'] * 91.44;
                break;
        }
        $contador_bultos++;
    }
    return $array_bultos;
}

function grupoimpultec_enqueue_jquery() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
}

function grupoimpultec_enqueue_jquery_ui_style() {
    wp_enqueue_style('jquery_ui', plugins_url('css/jquery-ui.css', __FILE__)
    );
}

function grupoimpultec_enqueue_bootstrap_style() {
    wp_enqueue_style('bootstrap_css', plugins_url('css/bootstrap.min.css', __FILE__), array(), '4.1.3'
    );
}

function grupoimpultec_enqueue_bootstrap_js() {
    wp_enqueue_script('bootstrap_js', plugins_url('js/bootstrap.min.js', __FILE__), array(), '4.1.3', true);
}

function grupoimpultec_enqueue_own_style() {
    wp_enqueue_style('style_css', plugins_url('css/style.css', __FILE__)
    );
}

function grupoimpultec_enqueue_own_js() {
    wp_enqueue_script('js_js', plugins_url('js/js.js', __FILE__)
    );
}

function grupoimpultec_enqueue_seguro_js() {
    wp_enqueue_script('seguro_js', plugins_url('js/seguro.js', __FILE__)
    );
}

function grupoimpultec_enqueue_create_ship_js() {
    wp_enqueue_script('crear_envio_js', plugins_url('js/crear_envio.js', __FILE__)
    );
}

function grupoimpultec_enqueue_moment_js() {
    wp_enqueue_script('moment_js', plugins_url('js/moment.js', __FILE__)
    );
}

function grupoimpultec_enqueue_horas_recogida_js() {
    wp_enqueue_script('horas_recogida_js', plugins_url('js/horas_recogida.js', __FILE__)
    );
}

function grupoimpultec_enqueue_reembolso_js() {
    wp_enqueue_script('reembolso_js', plugins_url('js/reembolso.js', __FILE__)
    );
}

function grupoimpultec_enqueue_fin_envio_js() {
    wp_enqueue_script('fin_envio_js', plugins_url('js/modal_fin_envio.js', __FILE__)
    );
}

function grupoimpultec_enqueue_google_maps_js() {
    wp_enqueue_script('google_maps_js', 'https://maps.google.com/maps/api/js?key=' . get_option('grupoimpultec_api_key_google_maps'), __FILE__);
}

function grupoimpultec_enqueue_maps_js() {
    wp_enqueue_script('mapas_js', plugins_url('js/inicializar_mapa.js', __FILE__)
    );
}

function grupoimpultec_enqueue_switch_iva_js() {
    wp_enqueue_script('switch_iva_js', plugins_url('js/switch_iva.js', __FILE__)
    );
}

function grupoimpultec_enqueue_envios_finalizados_js() {
    wp_enqueue_script('envios_finalizados_js', plugins_url('js/envios_finalizados.js', __FILE__)
    );
}
