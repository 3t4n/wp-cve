<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?>
<?php
$requiere_impresora = '';
$permite_reembolso ='';
if($agencia_precio['requiere_impresora'] == 1) {
    $requiere_impresora = '<span class="dashicons dashicons-editor-table"></span><span>&nbsp;'.__('Requiere impresora').'</span>';
}
if($agencia_precio['permite_reembolsos'] == 1) {
    $permite_reembolso = '<span class="dashicons dashicons-money"></span><span>&nbsp;'.__('Permite reembolso').'</span>';
}
echo '<tr>';
        echo '<td><img width="100" src ="http://www.' . $GLOBALS['api_server'] . '/' . $agencia_precio['imagen_agencia'] . '"></td>';
        echo '<td class="align-middle">' . $agencia_precio['nombre_agencia'] . '</td>';
        echo '<td class="align-middle">'.$requiere_impresora.'</td>';
        echo '<td class="align-middle">'.$permite_reembolso.'</td>';
        echo '<td class="align-middle">' . $tiempo_servicio . '</td>';
        echo '<td class="align-middle"><span class ="importe_envio_listado">' . 
            number_format($importe_sin_iva, 2) . '</span> €</td>';
        echo '<td class="align-middle"><a href="?page=' .
            esc_html($_REQUEST['page']) . 
            '&action=preparar_crear_envio&nwp=' . 
            $pedido->get_id() . '&ag=' . 
            $agencia_precio['id_agencia'] . 
            '&xs=' . $importe . 
            '&pcr=' . 
            $agencia_precio['porcentaje_contrareembolso'] . 
            '&ps=' . $agencia_precio['porcentaje_seguro'] . 
            $enlace_bultos_defecto. '&dr='.
            esc_html($_GET['dr']).
            '&per='.$agencia_precio['permite_reembolsos'].
            '&pp='.$agencia_precio['servicio_recogida'].
            '&pnp='.$agencia_precio['permite_entregar_delegacion'].
            '&pr='.$agencia_precio['permite_reembolsos'].'&micr='.
            $agencia_precio['minimo_reembolso'].
            '&mxcr='.$agencia_precio['maximo_reembolso'].
            '&mxcs='.$agencia_precio['maximo_seguro'].
            '&tc='.$agencia_precio['tipo_cliente'].
            '&ie='.$agencia_precio['iva_exento'].
            '&iv='.$agencia_precio['iva'].
            '&sr='.$agencia_precio['servicio_recogida'].
            '&amo='.$agencia_precio['agencia_mapa_origen'].
            '&idm='.$agencia_precio['id_agencia_madre'].
            '&amd='.$agencia_precio['agencia_mapa_destino'].
            '"><button class="btn btn-primary">Crear Envío</button></a></td>';
        echo '</tr>';