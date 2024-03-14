<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly <div class="wrap">    
?>
<?php
add_action('admin_init', 'grupoimpultec_comprobacion_wc_activo');
add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_multiselect_js');
add_action('admin_enqueue_scripts', 'grupoimpultec_enqueue_configuracion_js');

function grupoimpultec_comprobacion_wc_activo() {

    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        if (function_exists('add_settings_error')) {
            add_settings_error(
                    'grupoimpultec_config_main', '', $GLOBALS['nombre_app'] . ': ' . __('No se ha encontrado activo el plugin WooCommerce'), 'notice'
            );
        }
        return;
    }
}

add_filter("plugin_action_links_" . strtolower($GLOBALS['nombre_app']), 'grupoimpultec_plugin_add_settings_link');

function grupoimpultec_plugin_add_settings_link($links) {
    $settings_link = array('<a href="options-general.php?page=' . strtolower($GLOBALS['nombre_app']) . '">' . __('Ajustes') . '</a>');
    return array_merge($links, $settings_link);
}

add_action('admin_init', 'grupoimpultec_configuracion_seccion_general');

function grupoimpultec_configuracion_seccion_general() {
    $page = ( isset($_GET['page']) ) ? esc_attr(sanitize_text_field($_GET['page'])) : false;

    if ($page == strtolower($GLOBALS['nombre_app']) && !grupoimpultec_login($GLOBALS['api_server'], get_option('grupoimpultec_usuario_servicio'), get_option('grupoimpultec_password_servicio'))) {

        add_settings_error(
                'grupoimpultec_config_main', 'login_error', $GLOBALS['nombre_app'] . ': ' . __('Usuario / password incorrectos')
        );

        $iframe_url = 'https://www.' . $GLOBALS['api_server'] . '/modals_ajax/modal_inicio_externo';

        echo('<div class="wrap">
        <iframe id="grupoimpultec_iframe_'.$GLOBALS['nombre_app'].'" src="' . $iframe_url . '"
        width="100%" height="200" scrolling="auto"></iframe>
        </div>');
    } else {
        add_settings_section(
                'grupoimpultec_config_balance', '', 'grupoimpultec_config_balance_callback', 'plugin'
        );
        add_settings_error(
                'grupoimpultec_config_main', 'login_error', $GLOBALS['nombre_app'] . ': ' . __('Usuario / password OK'), 'notice'
        );

        if (!(get_option('grupoimpultec_first_order') > 0)) {
            update_option('grupoimpultec_first_order', 30);
        }
        if ((!get_option('grupoimpultec_max_weigth_box') > 0)) {
            update_option('grupoimpultec_max_weigth_box', 40);
        }
        if (get_option('grupoimpultec_tipo_calculo_precio_p') != 1 && get_option('grupoimpultec_tipo_calculo_precio_p') != 2) {
            update_option('grupoimpultec_tipo_calculo_precio_p', 1);
        }


        add_settings_field(
                'grupoimpultec_first_order', __('Número de días atrás en la búsqueda de pedidos'), 'grupoimpultec_input_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_first_order', 'number')
        );
        add_settings_field(
                'grupoimpultec_api_key_google_maps', __('API google Maps'), 'grupoimpultec_input_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_api_key_google_maps', 'text')
        );

        add_settings_field(
                'grupoimpultec_direccion_predeterminada', __('Dirección predeterminada'), 'grupoimpultec_select_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_direccion_predeterminada', 'select')
        );

        add_settings_field(
                'grupoimpultec_select_agencias_callback', __('Agencias Personalizadas'), 'grupoimpultec_select_agencias_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_agencias_personalizadas', 'select')
        );

        add_settings_field(
                'grupoimpultec_tipo_calculo_precio_p_callback', __('Cálculo de bultos requeridos en el carrito'), 'grupoimpultec_tipo_calculo_precio_p_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_tipo_calculo_precio_p', 'radio')
        );


        if ((!get_option('grupoimpultec_width_box') > 0)) {
            update_option('grupoimpultec_width_box', 10);
        }
        if ((!get_option('grupoimpultec_height_box') > 0)) {
            update_option('grupoimpultec_height_box', 10);
        }
        if ((!get_option('grupoimpultec_length_box') > 0)) {
            update_option('grupoimpultec_length_box', 10);
        }
        if ((!get_option('grupoimpultec_max_weigth_box') > 0)) {
            update_option('grupoimpultec_max_weigth_box', 1);
        }





        add_settings_field(
                'grupoimpultec_select_cajas_personalizadas_callback', __('Caja personalizada seleccionada'), 'grupoimpultec_select_cajas_personalizadas_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_select_cajas_personalizadas', 'select')
        );

        add_settings_field(
                'grupoimpultec_id_box', __('id'), 'grupoimpultec_input_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_id_box', 'number', 'class' => 'hidden')
        );

        add_settings_field(
                'grupoimpultec_name_box', __('nombre'), 'grupoimpultec_input_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_name_box', 'text')
        );

        add_settings_field(
                'grupoimpultec_width_box', __('Ancho'), 'grupoimpultec_input_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_width_box', 'number')
        );
        add_settings_field(
                'grupoimpultec_height_box', __('Alto'), 'grupoimpultec_input_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_height_box', 'number')
        );
        add_settings_field(
                'grupoimpultec_length_box', __('Largo'), 'grupoimpultec_input_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_length_box', 'number')
        );

        add_settings_field(
                'grupoimpultec_max_weigth_box', __('Máximo de kgs por bulto'), 'grupoimpultec_input_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_max_weigth_box', 'number')
        );
        add_settings_field(
                'grupoimpultec_show_only_completed', __('Mostrar sólo los pedidos completados'), 'grupoimpultec_checkbox_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_show_only_completed', 'checkbox')
        );


        register_setting('grupoimpultec_options', 'grupoimpultec_api_key_google_maps');
        register_setting('grupoimpultec_options', 'grupoimpultec_first_order');
        register_setting('grupoimpultec_options', 'grupoimpultec_direccion_predeterminada');
        register_setting('grupoimpultec_options', 'grupoimpultec_agencias_personalizadas');
        register_setting('grupoimpultec_options', 'grupoimpultec_max_weigth_box');
        register_setting('grupoimpultec_options', 'grupoimpultec_width_box');
        register_setting('grupoimpultec_options', 'grupoimpultec_height_box');
        register_setting('grupoimpultec_options', 'grupoimpultec_length_box');
        register_setting('grupoimpultec_options', 'grupoimpultec_name_box');
        register_setting('grupoimpultec_options', 'grupoimpultec_id_box');
        register_setting('grupoimpultec_options', 'grupoimpultec_show_only_completed');
        register_setting('grupoimpultec_options', 'grupoimpultec_tipo_calculo_precio_p');
        if (array_key_exists('grupoimpultec_id_box', $_POST) && array_key_exists('grupoimpultec_tipo_calculo_precio_p', $_POST) && $_POST['grupoimpultec_id_box'] > 0 && $_POST['grupoimpultec_tipo_calculo_precio_p'] == 2) {
            grupoimpultec_actualizar_valor_caja_personalizada();
        } else {
            
        }
    }
    add_settings_section(
            'grupoimpultec_config_main', 'Opciones', 'grupoimpultec_config_main_options_callback', 'plugin'
    );
    add_settings_field(
            'grupoimpultec_usuario_servicio', __('Usuario API'), 'grupoimpultec_input_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_usuario_servicio', 'text')
    );
    add_settings_field(
            'grupoimpultec_password_servicio', __('Password API'), 'grupoimpultec_input_type_callback', 'plugin', 'grupoimpultec_config_main', array('grupoimpultec_password_servicio', 'password')
    );    
    register_setting('grupoimpultec_options', 'grupoimpultec_usuario_servicio');
    register_setting('grupoimpultec_options', 'grupoimpultec_password_servicio');
    update_option('grupoimpultec_autenticado', '0');
    update_option('grupoimpultec_id_usuario', '');
    update_option('grupoimpultec_iva', '');
    update_option('grupoimpultec_last_version', '');
}

function grupoimpultec_tipo_calculo_precio_p_callback() {
    if (get_option('grupoimpultec_tipo_calculo_precio_p') == 1) {
        $selected_tipo_calculo_precio_p = ' checked ';
    } else {
        $selected_tipo_calculo_precio_p = '';
    }
    echo('<input type="radio" ' . $selected_tipo_calculo_precio_p . 'name="grupoimpultec_tipo_calculo_precio_p" id="grupoimpultec_tipo_calculo_precio_p_1" value="1">' . __('Calcular automáticamente 1 bulto por referencia de producto.')) . '</br>';
    if (get_option('grupoimpultec_tipo_calculo_precio_p') == 2) {
        $selected_tipo_calculo_precio_p = ' checked ';
    } else {
        $selected_tipo_calculo_precio_p = '';
    }

    echo('<input type="radio" ' . $selected_tipo_calculo_precio_p . 'name="grupoimpultec_tipo_calculo_precio_p" id="grupoimpultec_tipo_calculo_precio_p_2" value="2">' . __('Agrupar productos en el mismo bulto hasta un máximo de Kgs y medidas.'));
}

function grupoimpultec_config_balance_callback() { // Section Callback
    $datos_array['usuario_servicio'] = get_option('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = get_option('grupoimpultec_password_servicio');
    $id_usuario = grupoimpultec_getUserId($datos_array);
    $saldo = grupoimpultec_getBalance($datos_array);
}

function grupoimpultec_config_main_options_callback() { // Section Callback    
    echo '<p>' . __('Por favor, introduzca las credenciales API de su cuenta') .
    __('(son diferentes a las del acceso a su área de usuario en la web de ') . $GLOBALS['nombre_app'] . ').<br/>' .
    __('Puede localizarlas en el apartado configuración -> Credenciales API en su área de usuario en la web de ') . $GLOBALS['nombre_app'] .
    '</br><a href = "https://www.' . $GLOBALS['api_server'] . '/usuarios/configuracion">https://www.' . $GLOBALS['api_server'] . '/usuarios/configuracion</a></p>' .
    '</br>' .
    __('API Google Maps: Si no va a utilizar los servicios de entrega directa en oficinas, no necesita rellenar este campo') . '<br/>' .
    __('En caso contrario necesita obtener una clave API de Google Maps para poder visualizar mapas, visite la página de la Plataforma de Google Maps: ') . '<br/>' .
    __('<a href="https://cloud.google.com/maps-platform/" target="_blank">https://cloud.google.com/maps-platform/</a> y haz clic en Comenzar.<br/>') .
    __('Seleccione el producto Maps para obtener las API que se necesitan para la sección Mapa de temas gratuitos') . '<br/>' .
    __('Haz clic en Empezar, selecciona "Maps" y "Continue"') . '<br/>' .
    __('En el paso Seleccionar un proyecto se le pide asociar un nombre con tu uso de las API de Google. Cree un nuevo nombre o selecciona un proyecto existente.') . '<br/>' .
    __('Después de aceptar los términos del servicio, haga clic en Siguiente.') .
    __('Cree una cuenta de facturación con la plataforma de Google Maps.') . ' <br/>' .
    __('Una cuenta de facturación es un requisito en la nueva plataforma de Google Maps. ') . '<br/>' .
    __('Para más información, consulte la documentación de precios y facturación de la plataforma de Google Maps.') . '<br/>' .
    __('Después de habilitar la plataforma de Google Maps, copie su nueva clave API de Google Maps en su portapapeles.') . '<br/>';
}

function grupoimpultec_input_type_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    echo '<input type="' . esc_html($args[1]) . '" id="' . esc_html($args[0]) . '" name="' . esc_html($args[0]) . '" value="' . $option . '" />';
}

function grupoimpultec_checkbox_type_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    $checked_txt = '';
    if ($option == 1) {
        $checked_txt = ' checked ';
    }
    echo '<input ' . $checked_txt . ' type="' . esc_html($args[1]) . '" id="' . esc_html($args[0]) . '" name="' . esc_html($args[0]) . '" value="1" />';
}

function grupoimpultec_select_type_callback($args) {
    $grupoimpultec_direccion_predeterminada = get_option('grupoimpultec_direccion_predeterminada');
    $datos_array['usuario_servicio'] = get_option('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = get_option('grupoimpultec_password_servicio');
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $direcciones_remitente = json_decode(
            grupoimpultec_curlJson(array(
        'usuario_servicio' => $datos_array['usuario_servicio'],
        'password_servicio' => $datos_array['password_servicio'],
        'id_usuario' => grupoimpultec_getUserId($datos_array),
        'servicio' => $datos_array['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_listado_direcciones_parcial_usuario'), true
    );
    echo('<select id="grupoimpultec_direccion_predeterminada" name="grupoimpultec_direccion_predeterminada">');
    foreach ($direcciones_remitente as $direccion_remitente) {
        if ($grupoimpultec_direccion_predeterminada == $direccion_remitente['id_direccion']) {
            $selected = ' selected ';
        } else {
            $selected = '';
        }
        echo('<option value="' . $direccion_remitente['id_direccion'] . '"' . $selected . '>' . $direccion_remitente['nombre'] . ' - ' . $direccion_remitente['direccion'] . ' - ' . $direccion_remitente['poblacion'] . ' - ' . $direccion_remitente['codigo_postal'] . ' - ' . $direccion_remitente['nombre_pais'] . '</option>');
    }
    echo('</select>');
}

function grupoimpultec_select_agencias_callback($args) {
    $grupoimpultec_agencias_personalizadas = get_option('grupoimpultec_agencias_personalizadas');
    $datos_array['usuario_servicio'] = get_option('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = get_option('grupoimpultec_password_servicio');
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $agencias_personalizadas_obtenidas = json_decode(
            grupoimpultec_curlJson(array(
        'usuario_servicio' => $datos_array['usuario_servicio'],
        'password_servicio' => $datos_array['password_servicio'],
        'id_usuario' => grupoimpultec_getUserId($datos_array),
        'servicio' => $datos_array['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_lista_agencias_disponibles'), true
    );
    echo('<div class="row">');
    echo(__('Las agencias personalizadas permiten ofrecer, de forma automática, el mejor precio de nuestra plataforma directamente a sus clientes cuando finalicen la compra.'));
    echo('</div>');
    echo('<select multiple id="grupoimpultec_agencias_personalizadas" name="grupoimpultec_agencias_personalizadas[]" style="height:150px;margin-top:10px;">');
    foreach ($agencias_personalizadas_obtenidas as $item => $value) {
        if (in_array($item, $grupoimpultec_agencias_personalizadas)) {
            $selected = ' selected ';
        } else {
            $selected = '';
        }
        echo('<option value="' . trim($item) . '"' . $selected . '>' . $value . '</option>');
    }
    echo('</select>');
    echo('<div class="row" style="margin-top:10px;">');
    if (!empty(get_option('grupoimpultec_agencias_personalizadas'))) {
        foreach ($grupoimpultec_agencias_personalizadas as $item => $value) {
            echo($agencias_personalizadas_obtenidas[$value]) . "<p>";
        }
    } else {
        echo(__('Actualmente no está utilizando ninguna agencia personalizada.'));
    }
    echo('</div>');
}

function grupoimpultec_select_cajas_personalizadas_callback($args) {
    if (get_option('grupoimpultec_cajas_personalizadas') != null) {
        $grupoimpultec_cajas_personalizadas = get_option('grupoimpultec_cajas_personalizadas');
    } else {
        $grupoimpultec_cajas_personalizadas = array();
    }

    $datos_array['usuario_servicio'] = get_option('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = get_option('grupoimpultec_password_servicio');
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $cajas_personalizadas_obtenidas = json_decode(
            grupoimpultec_curlJson(array(
        'usuario_servicio' => $datos_array['usuario_servicio'],
        'password_servicio' => $datos_array['password_servicio'],
        'id_usuario' => grupoimpultec_getUserId($datos_array),
        'servicio' => $datos_array['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_lista_cajas_personalizadas'), true
    );
    echo('<select id="grupoimpultec_cajas_personalizadas" name="grupoimpultec_cajas_personalizadas[]">');
    foreach ($cajas_personalizadas_obtenidas as $item => $value) {
        if (get_option('grupoimpultec_id_box') == $value['id']) {
            $selected = ' selected ';
        } else {
            $selected = '';
        }
        echo('<option data-ancho="' . $value['ancho'] . '" data-name="' . $value['nombre'] . '" data-id="' . $value['id'] . '" data-largo="' . $value['largo'] . '" data-alto="' . $value['alto'] . '" data-peso="' . $value['peso'] . '" value="' . $value['id'] . '"' . ' ' . $selected . '>' . trim($value['nombre'] . ' - ' . $value['alto'] . 'x' . $value['ancho'] . 'x' . $value['largo'] . 'cm, ' . $value['peso']) . 'kg.</option>');
    }
    echo('</select>');
    echo('<div class="row" style="margin-top:10px;">');
    echo(__('Puede crear cajas personalizadas desde: ') . '<a target="_blank" href="' . 'https://www.' . $GLOBALS['api_server'] . '/usuarios/paquete_predefinido' . '">https://www.' . $GLOBALS['api_server'] . '/usuarios/paquete_predefinido' . '</a>');
    echo('</div>');
}

function grupoimpultec_actualizar_valor_caja_personalizada() {
    $datos_array['usuario_servicio'] = get_option('grupoimpultec_usuario_servicio');
    $datos_array['password_servicio'] = get_option('grupoimpultec_password_servicio');
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $salida = json_decode(
            grupoimpultec_curlJson(array(
        'usuario_servicio' => $datos_array['usuario_servicio'],
        'password_servicio' => $datos_array['password_servicio'],
        'id_usuario' => grupoimpultec_getUserId($datos_array),
        'id' => esc_attr(sanitize_text_field($_POST['grupoimpultec_id_box'])),
        'nombre' => esc_attr(sanitize_text_field($_POST['grupoimpultec_name_box'])),
        'peso' => esc_attr(sanitize_text_field($_POST['grupoimpultec_max_weigth_box'])),
        'alto' => esc_attr(sanitize_text_field($_POST['grupoimpultec_height_box'])),
        'ancho' => esc_attr(sanitize_text_field($_POST['grupoimpultec_width_box'])),
        'largo' => esc_attr(sanitize_text_field($_POST['grupoimpultec_length_box'])),
        'servicio' => $datos_array['servicio']), 'http://www.' . $GLOBALS['api_server'] . '/json_interface/actualizar_caja_personalizada'), true
    );
}

function grupoimpultec_login($api_server, $usuario_servicio, $password_servicio) {    
    if(get_option('grupoimpultec_autenticado') == 1) {        
        return true;
    } else {
        
    }
    //die("dsfdfs");
    $url = 'http://www.' . $api_server . '/json_interface/autenticacion';
    $datos_array['usuario_servicio'] = $usuario_servicio;
    $datos_array['password_servicio'] = $password_servicio;
    $datos_array['servicio'] = $GLOBALS['servicio'];    
    if (json_decode(grupoimpultec_curlJson($datos_array, $url), true)) {
        update_option('grupoimpultec_autenticado', '1');
        return true;
    } 
}

function grupoimpultec_curlJson($datos_array, $url) {

    $datos_json_post = json_encode($datos_array);
    $datos_array = array();
    $respuesta = wp_remote_post($url, array(
        'headers' => array('Content-Type' => 'application/json'),
        'body' => $datos_json_post,
        'timeout' => 30
    ));
    $response_body = wp_remote_retrieve_body($respuesta);
    if (get_option('grupoimpultec_api_key_google_maps') == 'test') {
        echo('respuesta curl: ' . var_export(wp_remote_retrieve_response_code($respuesta), true) . '<p>');
    }
    return $response_body;
}

function grupoimpultec_getUserId($datos_array) {
    if(get_option('grupoimpultec_id_usuario') != '') {
        return get_option('grupoimpultec_id_usuario');
    }        
    $datos_array['cn'] = $GLOBALS['plugin_version'];
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_id_usuario';
    $salida = grupoimpultec_curlJson($datos_array, $url);
    $salida = json_decode($salida, true);
    update_option('grupoimpultec_id_usuario',  $salida['id_usuario']);
    return $salida['id_usuario'];
}

function grupoimpultec_getBalance($datos_array) {
    $datos_array['cn'] = $GLOBALS['plugin_version'];
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $datos_array['id_usuario'] = grupoimpultec_getUserId($datos_array);
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_saldo';
    $salida = grupoimpultec_curlJson($datos_array, $url);
    $salida = json_decode($salida, true);
    return $salida['saldo'];
}

function grupoimpultec_getIva($datos_array) {
    if(get_option('grupoimpultec_iva') != '') {
        return get_option('grupoimpultec_iva');
    }
    $datos_array['cn'] = $GLOBALS['plugin_version'];
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $datos_array['id_usuario'] = grupoimpultec_getUserId($datos_array);
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_iva';
    $salida = grupoimpultec_curlJson($datos_array, $url);
    $salida = json_decode($salida, true);
    update_option('grupoimpultec_iva',  $salida['iva']);    
    return $salida['iva'];
}

function grupoimpultec_getlastVersion($datos_array) {
    if(get_option('grupoimpultec_last_version') != '') {
        return get_option('grupoimpultec_last_version');
    }
    $datos_array['cn'] = $GLOBALS['plugin_version'];
    $datos_array['servicio'] = $GLOBALS['servicio'];
    $datos_array['id_usuario'] = grupoimpultec_getUserId($datos_array);
    $url = 'http://www.' . $GLOBALS['api_server'] . '/json_interface/obtener_ultima_version_servicios_externos';
    $salida = grupoimpultec_curlJson($datos_array, $url);
    $salida = json_decode($salida, true);
     update_option('grupoimpultec_last_version',  $salida);        
    return $salida;
}

function grupoimpultec_plugin_admin_add_page() {
    add_options_page('Opciones ' . $GLOBALS['nombre_app'], 'Opciones ' . $GLOBALS['nombre_app'], 'manage_options', strtolower($GLOBALS['nombre_app']), 'grupoimpultec_options_page');
}

function grupoimpultec_options_page() {
    ?>
    <div>        
        <?php echo '<h2><img src="' . plugins_url('img/navbar-logo.svg', __FILE__) . '" width=200"></h2>'; ?>
        <form action="options.php" method="post">
            <?php settings_fields('grupoimpultec_options'); ?>
            <?php do_settings_sections('plugin'); ?> 
            <?php submit_button() ?>

        </form></div> 
    <?php
}

function grupoimpultec_enqueue_multiselect_js() {
    wp_enqueue_script('multiselect_js', plugins_url('js/multiselect.js', __FILE__)
    );
}

function grupoimpultec_enqueue_configuracion_js() {
    wp_enqueue_script('configuracion_js', plugins_url('js/configuracion.js', __FILE__)
    );
}
