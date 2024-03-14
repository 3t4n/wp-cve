<div id="rkmw_wrap">
    <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockToolbar')->init(); ?>
    <?php do_action('rkmw_notices'); ?>
    <div class="d-flex flex-row my-0 bg-white">
        <?php echo RKMW_Classes_ObjController::getClass('RKMW_Models_Menu')->getAdminTabs(RKMW_Classes_Helpers_Tools::getValue('tab', 'rkmw_rankings'), 'rkmw_rankings'); ?>
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-white pl-1 pr-1 mr-0">
            <div class="flex-grow-1 mr-2 rkmw_flex">
                <?php do_action('rkmw_form_notices'); ?>
                <div class="form-group my-4 col-sm-10 offset-1">
                    <?php echo $view->getView('Connect/GoogleSearchConsole'); ?>
                </div>
                <div class="card col-sm-12 p-0">
                    <div class="card-body p-2 bg-title rounded-top">
                        <div class="rkmw_icons_content p-3 py-4">
                            <div class="rkmw_icons rkmw_rankings_icon m-2"></div>
                        </div>
                        <h3 class="card-title"><?php echo esc_html__("Google Search Console Keywords Sync", RKMW_PLUGIN_NAME); ?>:</h3>
                        <div class="card-title-description m-2"><?php echo esc_html__("See the trending keywords suitable for your website's future topics. We check for new keywords weekly based on your latest researches.", RKMW_PLUGIN_NAME); ?></div>
                    </div>
                    <div id="rkmw_keywords" class="card col-sm-12 p-0 tab-panel border-0">
                        <div class="alert alert-success text-center">
                            <?php echo esc_html__("This is the list of keywords you have in Google Search Console. Information for the last 90 days. You can add keywords that you find relevant to your Briefcase and to the Rankings section.", RKMW_PLUGIN_NAME); ?>
                        </div>


                        <div class="card-body p-0">
                            <div class="col-sm-12 m-0 p-0">
                                <div class="card col-sm-12 my-4 p-0 p-1 border-0 ">
                                    <?php if (is_array($view->suggested) && !empty($view->suggested)) { ?>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th style="width: 30%;"><?php echo esc_html__("Keyword", RKMW_PLUGIN_NAME) ?></th>
                                                <th scope="col" title="<?php echo esc_html__("Clicks", RKMW_PLUGIN_NAME) ?>"><?php echo esc_html__("Clicks", RKMW_PLUGIN_NAME) ?></th>
                                                <th scope="col" title="<?php echo esc_html__("Impressions", RKMW_PLUGIN_NAME) ?>"><?php echo esc_html__("Impressions", RKMW_PLUGIN_NAME) ?></th>
                                                <th scope="col" title="<?php echo esc_html__("Click-Through Rate", RKMW_PLUGIN_NAME) ?>"><?php echo esc_html__("CTR", RKMW_PLUGIN_NAME) ?></th>
                                                <th scope="col" title="<?php echo esc_html__("Average Position", RKMW_PLUGIN_NAME) ?>"><?php echo esc_html__("AVG Position", RKMW_PLUGIN_NAME) ?></th>
                                                <th style="width: 20px;"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($view->suggested as $key => $row) {
                                                $in_ranking = false;
                                                if (!empty($view->keywords))
                                                    foreach ($view->keywords as $krow) {
                                                        if (trim(strtolower($krow->keyword)) == trim(strtolower($row->keywords))) {
                                                            if($krow->do_serp){
                                                                $in_ranking = true;
                                                            }
                                                            break;
                                                        }
                                                    }

                                                ?>
                                                <tr class="<?php echo($in_ranking ? 'bg-briefcase' : '') ?>">
                                                    <td style="width: 280px;">
                                                        <span style="display: block; clear: left; float: left;"><?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keywords) ?></span>
                                                    </td>
                                                    <td>
                                                        <span style="display: block; clear: left; float: left;"><?php echo number_format($row->clicks, 0, '.', ',') ?></span>
                                                    </td>
                                                    <td>
                                                        <span style="display: block; clear: left; float: left;"><?php echo number_format($row->impressions, 0, '.', ',') ?></span>
                                                    </td>
                                                    <td>
                                                        <span style="display: block; clear: left; float: left;"><?php echo number_format($row->ctr, 2, '.', ',') ?></span>
                                                    </td>
                                                    <td>
                                                        <span style="display: block; clear: left; float: left;"><?php echo number_format($row->position, 1, '.', ',') ?></span>
                                                    </td>
                                                    <td class="px-0 py-2" style="width: 20px">
                                                        <div class="rkmw_sm_menu">
                                                            <div class="sm_icon_button sm_icon_options">
                                                                <i class="fa fa-ellipsis-v"></i>
                                                            </div>
                                                            <div class="rkmw_sm_dropdown">
                                                                <ul class="text-left p-2 m-0 ">
                                                                    <?php if ($in_ranking) { ?>
                                                                        <li class="bg-briefcase m-0 p-1 py-2 text-black-50">
                                                                            <i class="rkmw_icons_small rkmw_briefcase_icon"></i>
                                                                            <?php echo esc_html__("Already in Rankings", RKMW_PLUGIN_NAME); ?>
                                                                        </li>
                                                                    <?php } else { ?>
                                                                        <li class="rkmw_research_add_briefcase m-0 p-1 py-2" data-hidden="0" data-doserp="1" data-keyword="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keywords) ?>">
                                                                            <i class="rkmw_icons_small rkmw_briefcase_icon"></i>
                                                                            <?php echo esc_html__("Add to Rankings", RKMW_PLUGIN_NAME); ?>
                                                                        </li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>

                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <div class="card-body">
                                            <h4 class="text-center"><?php echo esc_html__("Welcome to Google Search Console Keywords Sync", RKMW_PLUGIN_NAME); ?></h4>

                                            <div class="col-sm-12 mt-5 mx-2">
                                                <h5 class="text-left my-3 text-info"><?php echo esc_html__("Tips: Which Keyword Should I Choose?", RKMW_PLUGIN_NAME); ?></h5>
                                                <ul>
                                                    <li class="text-left" style="font-size: 15px;"><?php echo sprintf(esc_html__("From %sKeywords Briefcase%s you can send keywords to Rank Checker to track the SERP evolution.", RKMW_PLUGIN_NAME), '<a href="' . RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'briefcase') . '" >', '</a>'); ?></li>
                                                </ul>
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
                    <?php echo RKMW_Classes_ObjController::getClass('RKMW_Core_BlockKnowledgeBase')->init(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
