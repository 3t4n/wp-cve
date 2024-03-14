<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?><div class="wrap">
    <div class="col-12 caja_bultos_defecto" id="caja_bultos_defecto_<?= $item['numero_pedido_wp'] ?>">    
        <div class="row">
            <div class="col-12">
                <input type="checkbox" class="form-control check_bultos_defecto" id="chkdf_<?= $item['numero_pedido_wp'] ?>" value="1" name="check_bultos_defecto<?= $item['numero_pedido_wp'] ?>">
                <label for="check_bultos_defecto"><?=__('Establecer bultos manualmente');?></label>
            </div>
        </div>
        <div class="row bultos_defecto" id="bultos_defecto_<?= $item['numero_pedido_wp'] ?>">
        </div>
    </div>
</div>



