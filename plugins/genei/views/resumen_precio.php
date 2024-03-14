<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?><?php

if($iva_exento == 1)
{    
    $importe_base = $importe;
} else { 
   $importe_base  = $importe / (1+($iva / 100));
   
}
$importe_iva = $importe - $importe_base;
?>
<div class="wrap">
<section id="seccion_resumen_precio">
    <div class="row">
        <div class="col-12 col-md-4">
            <p class="resumen_titulo"><?=__('Precio base:');?></p>
            <p class="resumen_cuerpo"><span id="resumen_importe_base"><?=round($importe_base,2) ?></span> €</p>
        </div>
        <div class="col-12 col-md-4">
            <p class="resumen_titulo"><?=__('Comisión seguro:');?></p>
            <p class="resumen_cuerpo"><span id="resumen_comision_seguro">0</span> €</p>
        </div>
        <div class="col-12 col-md-4">
            <p class="resumen_titulo"><?=__('Comisión reembolso:');?></p>
            <p class="resumen_cuerpo"><span id="resumen_comision_reembolso">0</span> €</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-4">
            <p class="resumen_titulo"><?=__('Total base:');?></p>
            <p class="resumen_cuerpo"><span id="resumen_total_importe_base"><?= round($importe_base,2) ?></span> €</p>
        </div>
        <div class="col-12 col-md-4">
            <p class="resumen_titulo"><?=__('Importe IVA:');?></p>
            <p class="resumen_cuerpo"><span id="resumen_iva"><?= round($importe_iva,2) ?></span> €</p>
        </div>
        <div id="importe_total_caja" class="col-12 col-md-4">
            <p class="resumen_titulo"><?=__('Importe total:');?></p>
            <p class="resumen_cuerpo"><span id="resumen_total_importe"><?= round($importe,2) ?></span> €</p>
        </div>
    </div>       
</section>
</div>