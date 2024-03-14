<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?>        <div id="caja_seguro" class="col-12 col-md-6">
            <div class="col-5">                        
                <input type="checkbox" class="form-control" id="seguro" value="1" name="seguro">
                <label for="seguro"><?=__('Indemnización');?></label>
            </div>
            <div class="col-5" id="div_cantidad_seguro" style="display:none">
                <div class="input-group">
                    <input type="text" class="form-control" id="cantidad_seguro" name="cantidad_seguro" value="0"/>
                    <div class="input-group-append">
                        <span class="input-group-text" id="addon-basico-eur2">€</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
