<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?><?php
echo('<input type="hidden" id="page" name="page" value="'.esc_html($_REQUEST['page']).'">');
echo('<input type="hidden" id="usuario_servicio" name="usuario_servicio" value="'.$datos_array['usuario_servicio'].'">');
echo('<input type="hidden" id="password_servicio" name="password_servicio" value="'.$datos_array['password_servicio'].'">');
echo('<input type="hidden" id="servicio" name="servicio" value="'.$datos_array['servicio'].'">');
echo('<input type="hidden" id="action" name="action" value="crear_envio">');
echo('<input type="hidden" id="numero_pedido_wp" name="numero_pedido_wp" value="' . $pedido->get_id() . '">');
echo('<input type="hidden" id="id_agencia" name="id_agencia" value="' . $datos_array['id_agencia'] . '">');
echo('<input type="hidden" id="id_agencia_madre" name="id_agencia_madre" value="' . $datos_array['id_agencia_madre'] . '">');
echo('<input type="hidden" id="servicio_recogida" name="servicio_recogida" value="' . $datos_array['servicio_recogida'] . '">');
echo('<input type="hidden" id="id_usuario" name="id_usuario" value="' . $datos_array['id_usuario'] . '">');
echo('<input type="hidden" id="id_pais_salida" name="id_pais_salida" value="' . $datos_array['id_pais_salida'] . '">');
echo('<input type="hidden" id="id_pais_llegada" name="id_pais_llegada" value="' . $datos_array['id_pais_llegada'] . '">');
echo('<input type="hidden" id="importe" name="importe" value="' . $importe . '">');
echo('<input type="hidden" id="usuario_cif_intracomunitario" value="' . $usuario_cif_intracomunitario . '">');
echo('<input type="hidden" id="id_zona_salida" value="' . $id_zona_salida . '">');
echo('<input type="hidden" id="id_zona_llegada" value="' . $id_zona_llegada . '">');
echo('<input type="hidden" id="iva_exento" value="' . $iva_exento . '">');
echo('<input type="hidden" id="porcentaje_reembolso" value="' . $porcentaje_reembolso . '">');
echo('<input type="hidden" id="porcentaje_seguro" value="' . $porcentaje_seguro . '">');
echo('<input type="hidden" id="importe_base" value="' . $importe_base . '">');
echo('<input type="hidden" id="importe_iva" value="' . $importe_iva . '">');
echo('<input type="hidden" id="minimo_reembolso" value="' . $minima_cantidad_reembolso . '">');
echo('<input type="hidden" id="maxima_cantidad_reembolso" value="' . $maxima_cantidad_reembolso . '">');
echo('<input type="hidden" id="maxima_cantidad_seguro" value="' . $maxima_cantidad_seguro . '">');
echo('<input type="hidden" id="direccion_remitente" name="direccion_remitente" value="' . $direccion_remitente . '">');
echo('<button type="button" id="boton_crear_envio" class="btn btn-primary" disabled>'.__('Crear Envío').'</button>');
echo('<div id="div_error_txt" style="color:#c30000;"></div>');
echo('<script>iva = "'.$iva.'";iva_exento = "'.$iva_exento.'";</script>');
