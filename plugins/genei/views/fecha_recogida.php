<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?><?php
echo('<script>');
$datos_array['agencia_permite_recoger'] = $permite_recoger;
$datos_array['agencia_permite_no_recoger'] = $permite_no_recoger;
if ($datos_array['agencia_permite_recoger'] != 1) {
    echo('jQuery(function(){ jQuery("#div_no_recoger").show();});');
    echo('jQuery(function(){ jQuery("#boton_crear_envio").show();});');
}
if ($datos_array['agencia_permite_no_recoger'] != 1) {
    echo('jQuery(function(){
        jQuery("#no_recoger").attr("disabled",true);
        jQuery("#no_recoger").attr("checked",false);
        jQuery("#no_recoger").attr("title","Esta agencia requiere recogida");
        });');
} else {
    echo('jQuery(function(){
    jQuery("#no_recoger").attr("disabled",false);
        jQuery("#no_recoger").attr("checked",false);
        jQuery("#no_recoger").attr("title","");
       });');
}
echo('</script>');

?>
<input type="hidden" id="id_usuario" value="<?= $datos_array['id_usuario'] ?>">
<input type="hidden" id="id_agencia" value="<?= $datos_array['id_agencia'] ?>">
<input type="hidden" id="codigos_origen" value="<?= $datos_array['codigos_origen'] ?>">
<input type="hidden" id="codigos_destino" value="<?= $datos_array['codigos_destino'] ?>">
<input type="hidden" id="id_pais_salida" value="<?= $datos_array['id_pais_salida'] ?>">
<input type="hidden" id="id_pais_llegada" value="<?= $datos_array['id_pais_llegada'] ?>">
<input type="hidden" id="id_pais_llegada" value="<?= $datos_array['id_pais_llegada'] ?>">
<section id="seccion_fecha_recogida">
    <div id="div_no_recoger">    
        <input type="checkbox" id="no_recoger" name="no_recoger" value="1">
        <label for="no_recoger" class="text-left">No solicitar recogida</label>
    </div>
    <div id="establecer_fecha">
        <div class="col-12">
            <div class="row">
                <div class="col-3">
                    <div class="input-group mb-3 sel-fecha-recogida" id="sel_fechaR">
                        <input type="text" placeholder="<?=__('Fecha recogida / admisión');?>" onchange="consulta_horas_recogida('<?= $GLOBALS['api_server'] ?>');" class="form-control" aria-label="<p><?=__('Fecha recogida / admisión');?></p>" aria-describedby="addon-basico" id="fecha_recogida" name="fecha_recogida" readonly="">
                        <input name="fecha_recogida_aux" value="" id="fecha_recogida_aux" style="display: none;" type="text" class="hasDatepicker" required="">
                        <div class="input-group-append">
                            <span class="input-group-text addon-basico" id="addon-basico"><img style="position: relative; width: 30px;" src="https://www.<?= $GLOBALS['api_server'] ?>/design/ngg/20180419/img/calendario-icon.svg"></span>
                        </div>
                    </div>
                </div>
                <div class="col-9 caja_intervalo">
                    <div id="caja_desde" style="display: none;">
                        <label for="id_d_intervalo"><?=__('Desde:');?></label>
                        <select class="form-control" name="id_d_intervalo" id="id_d_intervalo" style="height:auto;"></select>
                    </div>
                    <div id="caja_hasta" style="display: none;">
                        <label for="id_h_intervalo"><?=__('Hasta:');?></label>
                        <select class="form-control" name="id_h_intervalo" id="id_h_intervalo" style="height:auto;"></select>
                    </div>
                    <p id="id_no_intervalo" style="display:none"><?=__('No existe horario de recogida');?></p>
                </div>
            </div>
            <div class="col-7">
            </div>
        </div>
        <div class="col-md-12" id="capa_entrega_mapas_correos" style="display:none">
            <div class="col-md-12">                                        
                <input type="hidden" id="oficinas_correos" value="1">
                <?=__('Elija la oficina de correos donde pasará el destinatario a recoger su envío. Recuerde que este servicio no tiene entrega a domicilio por lo que el destinatario sólo podra retirar su envío si va a buscarlo a la oficina de correos que seleccione del siguiente listado:');?>
            </div>
            <div class="col-md-12">
                <div id="map-canvas" style="width:100%;height:300px;"></div>
            </div>                                    
            <div class="col-md-12">                     
                <label for=""><?=__('Oficina Correos');?></label>
                <select id="unidad_correo" onchange="javascript:cargar_mapas(jQuery('#unidad_correo').val());"></select>
            </div>                      
        </div>                                                     
        <div class="col-md-12" id="capa_entrega_mapas_hapiick" style="display:none">
            <div class="col-md-12">                                        
                <input type="hidden" id="oficinas_hapiick" value="1">
                <?=__('Elige la taquilla inteligente Hapiick donde pasará el destinatario a recoger su envío.Recuerde que este servicio no tiene entrega a domicilio por lo que el destinatario sólo podra retirar su envío si va a buscarlo a la taquilla inteligente Hapiick que seleccione del siguiente listado:');?>
            </div>
            <div class="col-md-12">
                <div id="map-canvas" style="width:100%;height:300px;"></div>
            </div>                                    
            <div class="col-md-12">                     
                <label for=""><?=__('Oficina hapiick');?></label>
                <select id="unidad_hapiick"></select>
            </div>
            <div class="col-md-12" id="capa_entrega_mapas_mondial_relay">
                <div class="col-md-12">
                    <?=__('Le client n\'a pas choisi de Point Relais pour son envoi. Veuillez choisir le Point Relais d\'arrivée. Vous pourrez-déposer votre colis dans le Point Relais Mondial Relay de votre choix');?>
                </div>
                <div class="modal-header">
                    <h4><?=__('Carte Points Relais');?></h4>
                </div>
                <div class="modal-body" align="center">
                    <div id="Zone_Widget_destino"></div>
                </div>
            </div>                     
        </div>
    </div> 
</section>
