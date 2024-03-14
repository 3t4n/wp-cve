<div id="rkmw_wrap" class="rkmw_overview">
    <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockToolbar')->init(); ?>
    <?php do_action('rkmw_form_notices'); ?>
    <?php if (RKMW_Classes_Helpers_Tools::getOption('api') == '') { ?>
        <div class="row my-0 bg-white col-sm-12 p-2 m-0">
            <div class="rkmw_flex col mx-0 my-2 pl-0 pr-3">
                <div class="mx-auto">
                    <div class="bg-title col-sm-10 mx-auto card-body my-3 p-2 offset-sm-2 rounded-top" style="min-width: 600px;">
                        <div class="col-sm-12 text-center m-2 p-0 e-connect">
                            <div class="mt-3 mb-4 mx-auto e-connect-link">
                                <div class="p-0 mx-2 float-left" style="width:48px;">
                                    <div class="rkmw_wordpress_icon m-0 p-0" style="width: 48px; height: 48px;"></div>
                                </div>
                                <div class="p-0 mx-2 float-right" style="width:48px;">
                                    <div class="rkmw_plugin_icon m-0 p-0" style="width: 40px; height: 48px;"></div>
                                </div>
                            </div>
                            <h4 class="card-title"><?php echo esc_html__("Request an API Key from Rank My WP Cloud", RKMW_PLUGIN_NAME); ?></h4>
                            <div class="text-info"><?php echo sprintf(esc_html__("If you don't have an API Key, you can request a free one.", RKMW_PLUGIN_NAME), '<br/>') ?></div>
                        </div>

                        <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockLogin')->init(); ?>
                    </div>
                </div>
            </div>

            <div class="rkmw_col col rkmw_col_side ">
                <div class="card col-sm-12 p-0">
                    <?php echo RKMW_Classes_ObjController::getClass('RKMW_Core_BlockKnowledgeBase')->init(); ?>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="row my-0 bg-white col-sm-12 p-2 m-0">

            <div class="rkmw_flex col mx-0 pl-0 pr-3">
                <?php
                if (RKMW_Classes_Helpers_Tools::getMenuVisible('research/research')) {
                    RKMW_Classes_ObjController::getClass('RKMW_Core_BlockResearch')->init();
                }
                ?>
                <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockFeatures')->init(); ?>
            </div>


            <div class="rkmw_col rkmw_col_side ">
                <div class="card col-sm-12 p-0 my-1">
                    <?php if (RKMW_Classes_Helpers_Tools::getMenuVisible('account_info') && current_user_can('manage_options')) { ?>
                        <div class="rkmw_account_info" style="min-height: 20px;"></div>
                    <?php } ?>
                </div>
                <div class="card col-sm-12 p-0 my-1">
                    <div class="my-3 py-3">
                        <div class="col-sm-12 row m-0">
                            <div class="checker col-sm-12 row m-0 p-0 text-center">
                                <div class="col-sm-12 my-2 p-0">
                                    <a href="https://wordpress.org/support/view/plugin-reviews/rank-my-wp#postform" target="_blank">
                                        <img src="<?php echo RKMW_ASSETS_URL . 'img/5stars.png' ?>">
                                    </a>
                                </div>
                                <div class="col-sm-12 my-2 p-0">
                                    <a href="https://wordpress.org/support/view/plugin-reviews/rank-my-wp#postform" target="_blank" class="font-weight-bold" style="font-size: 16px;">
                                        <?php echo esc_html__("Show us if you like Rank My WP", RKMW_PLUGIN_NAME) ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card col-sm-12 p-0 my-1">
                    <?php echo RKMW_Classes_ObjController::getClass('RKMW_Core_BlockKnowledgeBase')->init(); ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
