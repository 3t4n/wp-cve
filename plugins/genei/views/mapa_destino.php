<?php
if (!defined('ABSPATH'))
    exit;
?>
<div id="div_activar_desactivar_mapa_entrega" style="display:none">
    <input type="checkbox" id="entrega_oficina_destino" name="entrega_oficina_destino" value="1">
    <label for="entrega_oficina_destino" class="text-left"><?= __('Entrega en oficina destino'); ?></label>
</div>
<div id="div_map_oficinas_destino" class="card mb-3" style="max-width: 100%;">
    <div class="card-body" id="div_map_oficinas_destino">
        <input type="hidden" id="oficinas_correos" name="oficinas_correos" value="1">
        <div class="infocard-imp">
            <i class="ion-alert-circled"></i>
            <p><?= __('Elija la oficina donde pasará el destinatario a recoger su envío. Recuerde que este servicio no tiene entrega a domicilio por lo que el destinatario sólo podría retirar su envío si va a buscarlo a la oficina siguiente'); ?></p>
        </div>
        <div id="map_oficinas_destino" style="width:100%;height:320px;">
        </div>
    </div>
    <div>
        <p><?= __('Oficina de destino seleccionada'); ?>:</p>
    </div>
    <div id="div_select_oficinas_destino" class="form-group col-12">
        <select class="form-control" id="select_oficinas_destino" name="select_oficinas_destino">
        </select>
    </div>
</div>