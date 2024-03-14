<div id="rkmw_wrap">
    <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockToolbar')->init(); ?>
    <?php do_action('rkmw_notices'); ?>
    <div class="d-flex flex-row my-0 bg-white">
        <?php echo RKMW_Classes_ObjController::getClass('RKMW_Models_Menu')->getAdminTabs(RKMW_Classes_Helpers_Tools::getValue('tab', 'suggested'), 'rkmw_research'); ?>
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-white pl-1 pr-1 mr-0">
            <div class="flex-grow-1 mr-2 rkmw_flex">
                <?php do_action('rkmw_form_notices'); ?>

                <div class="card col-sm-12 p-0">
                    <div class="card-body p-2 bg-title rounded-top">
                        <div class="rkmw_help_question float-right"><a href="https://howto.rankmywp.com/kb/keyword-research/#history" target="_blank"><i class="fa fa-question-circle"></i></a></div>
                        <div class="rkmw_icons_content p-3 py-4">
                            <div class="rkmw_icons rkmw_history_icon m-2"></div>
                        </div>
                        <h3 class="card-title"><?php echo esc_html__("History", RKMW_PLUGIN_NAME); ?>:</h3>
                        <div class="card-title-description m-2"><?php echo esc_html__("See the Keyword Researches you made in the last 30 days", RKMW_PLUGIN_NAME); ?></div>
                    </div>
                    <div id="rkmw_history" class="card col-sm-12 p-0 tab-panel border-0">
                        <?php do_action('rkmw_subscription_notices'); ?>

                        <div class="card-body p-0">
                            <div class="col-sm-12 m-0 p-0">
                                <div class="card col-sm-12 my-4 p-0 p-1  border-0 ">
                                    <?php if (is_array($view->kr) && !empty($view->kr)) { ?>
                                        <table class="rkmw_krhistory_list table table-striped table-hover" cellpadding="0" cellspacing="0" border="0">
                                            <thead>
                                            <tr>
                                                <th scope="col"><?php echo esc_html__("Keyword", RKMW_PLUGIN_NAME) ?></th>
                                                <th scope="col" title="<?php echo esc_html__("Country", RKMW_PLUGIN_NAME) ?>"><?php echo esc_html__("Co", RKMW_PLUGIN_NAME) ?></th>
                                                <th><?php echo esc_html__("Date", RKMW_PLUGIN_NAME) ?></th>
                                                <th><?php echo esc_html__("Details", RKMW_PLUGIN_NAME) ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($view->kr as $key => $kr) {
                                                ?>
                                                <tr>
                                                    <td class="rkmw_kr_keyword" title="<?php echo esc_attr($kr->keyword) ?>" ><?php echo esc_html($kr->keyword) ?></td>
                                                    <td style="width:90px"><?php echo esc_html($kr->country) ?></td>
                                                    <td style="width:150px">
                                                        <div data-datetime="<?php echo strtotime($kr->datetime) ?>"><?php echo date(get_option('date_format'), strtotime($kr->datetime)) ?></div>
                                                    </td>
                                                    <td style="width:180px">
                                                        <button type="button" data-id="<?php echo (int)$kr->id ?>" data-destination="#history<?php echo (int)$kr->id ?>" class="rkmw_history_details btn btn-success btn-sm px-3"><?php echo esc_html__("Show All Keywords", RKMW_PLUGIN_NAME) ?></button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <div class="card-body">
                                            <h4 class="text-center"><?php echo esc_html__("Welcome to Keyword Research History", RKMW_PLUGIN_NAME); ?></h4>
                                            <h5 class="text-center"><?php echo esc_html__("See your research results and compare them over time", RKMW_PLUGIN_NAME); ?>:</h5>
                                            <div class="col-sm-12 my-4 text-center">
                                                <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'research') ?>" class="btn btn-lg btn-primary">
                                                    <i class="fa fa-plus-square-o"></i> <?php echo esc_html__("Go Find New Keywords", RKMW_PLUGIN_NAME); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rkmw_col_side sticky">
                <div class="card col-sm-12 p-0">
                    <div class="card-body f-gray-dark p-0">
                        <?php echo RKMW_Classes_ObjController::getClass('RKMW_Core_BlockKnowledgeBase')->init(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>