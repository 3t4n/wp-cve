<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?><section id="seccion_reembolso_seguro">
    <div class="row">
        <div id="caja_reembolso" class="col-12 col-md-6">
            <div class="col-5">                        
                <input type="checkbox" class="form-control" id="reembolso" value="1" name="reembolso">
                <label for="reembolso"><?=__('Reembolso');?></label>
            </div>
            <div class="col-5" id="div_cantidad_reembolso" style="display:none">
                <div class="input-group">
                    <input type="text" class="form-control" id="cantidad_reembolso" name="cantidad_reembolso" value="0"/>
                    <div class="input-group-append">
                        <span class="input-group-text" id="addon-basico-eur2">€</span>
                    </div>
                </div>
            </div>
        </div>