<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?><!-- MODAL FIN_ENVIO -->
<div class="modal fade modal_fin_envio" id="modal_fin_envio" name="modal_fin_envio" tabindex="-1" role="dialog" aria-labelledby="modal_fin_envio" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $resultado_texto_fin_envio ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="ion-close-round"></i>
                </button>
            </div> <!-- fin modal header -->
            <div class="modal-body" id="modal_fin_envio_body" style="width:500px;max-height:500px;overflow-y: scroll;">
                <?= $respuesta['resultado_text'] ?>
            </div><!-- fin modal body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Cerrar');?></button>
            </div><!-- fin modal footer -->
        </div><!-- fin modal content -->
    </div><!-- fin modal dialog -->
</div><!-- fin modal -->