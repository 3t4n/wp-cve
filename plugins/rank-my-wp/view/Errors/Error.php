<?php
RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap-reboot');
RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap');
RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('fontawesome');
RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('global');
RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('navbar');
?>
<div id="rkmw_wrap">
    <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockToolbar')->init(); ?>
    <?php do_action('rkmw_notices'); ?>
    <div class="d-flex flex-row my-0 bg-white">
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-white pl-1 pr-1 mr-0">
            <div class="flex-grow-1 mr-2 rkmw_flex">

                <div class="col-sm-12 p-0">
                    <div class="col-sm-12 px-2 py-3 text-center" >
                        <img src="<?php echo RKMW_ASSETS_URL . 'img/settings/noconnection.jpg' ?>" style="width: 300px">
                    </div>
                    <div id="rkmw_error" class="card col-sm-12 p-0 tab-panel border-0">
                        <div class="col-sm-12 alert alert-success text-center m-0 p-3"><i class="fa fa-exclamation-triangle" style="font-size: 18px !important;"></i> <?php echo sprintf(esc_html__("There is a connection error with Rank My WP Cloud. Please check the connection and %srefresh the page%s.", RKMW_PLUGIN_NAME),'<a href="javascript:location.reload();" >','</a>')?></div>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>
