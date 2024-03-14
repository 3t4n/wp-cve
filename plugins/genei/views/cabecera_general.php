<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?><div>
    <?php echo '<img src="' . plugins_url('../img/navbar-logo.svg', __FILE__) . '" width=200">'; ?>
</div>
<h2 class="order_list_titulo"><?=__('Lista de pedidos finalizados Woocommerce');?></h2>
<div class="wrap"><h3 id="order_list_datos_usuario">
        <span class="negrita">Usuario:</span><?= $datos_array['usuario_servicio'] ?><br />
        <span class="negrita"><?= $saldo_o_credito ?>: </span><?= number_format($saldo,2) ?> €</h3>
</div>
<div class="wrap">
    <span class="negrita"><?=__('Versión plugin instalada:');?> <?= $GLOBALS['plugin_version'] ?></span>
    <span class="negrita"><?=__('Última versión:');?> <?= $array_ultima_version['ultima_version_txt'] ?></span>
    <?php    
    if($array_ultima_version['ultima_version_cn'] > $GLOBALS['plugin_cn_version'])
    { 
        echo('<span>'.__('Versión desactualizada'). '<a href="'.admin_url('plugins.php').'">'.__('Actualizar').'</a>');
    }
    ?>
    <a href="https://www.<?= $GLOBALS['api_server'] ?>/contacto" target="_blank"><?=__('[Reportar error]');?></a>
</div>
<div class="wrap">
    <?php
    if(get_option('grupoimpultec_tipo_calculo_precio_p') ==2)
    {
        $texto_calculo_precio = __('Se están agrupando productos en el mismo bulto. ');
        
           $texto_calculo_precio. __('con caja personalizada de medidas: ');
           $texto_calculo_precio.get_option('grupoimpultec_width_box').'X'.get_option('grupoimpultec_height_box').'X'.get_option('grupoimpultec_length_box').' cm.';
           $texto_calculo_precio.='<a href="'.admin_url( 'options-general.php?page='.strtolower($GLOBALS['nombre_app']) ).'">'.(__('Caja seleccionada: ')).'</a>';
    $texto_calculo_precio.= (__('Ancho') . ': ') . grupoimpultec_obtener_medidas_caja()['width'] . ' cm. ';
        $texto_calculo_precio.= (__('Alto') . ': ') . grupoimpultec_obtener_medidas_caja()['height'] . ' cm. ';
        $texto_calculo_precio.= (__('Largo') . ': ') . grupoimpultec_obtener_medidas_caja()['length'] . ' cm. ';
        $texto_calculo_precio.= (__('Peso máximo') . ': ') . get_option('grupoimpultec_max_weigth_box') . ' kg.';        
    } else {
        $texto_calculo_precio = __('Se está calculando automáticamente 1 bulto por cada referencia de producto.');        
    }
    ?>
    <span><?=$texto_calculo_precio?></span>
        
</div>