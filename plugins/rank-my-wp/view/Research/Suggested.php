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
                        <div class="rkmw_icons_content p-3 py-4">
                            <div class="rkmw_icons rkmw_suggested_icon m-2"></div>
                        </div>
                        <h3 class="card-title"><?php echo esc_html__("Suggested", RKMW_PLUGIN_NAME); ?>:</h3>
                        <div class="card-title-description m-2"><?php echo esc_html__("See the trending keywords suitable for your website's future topics. We check for new keywords weekly based on your latest researches.", RKMW_PLUGIN_NAME); ?></div>
                    </div>
                    <div id="rkmw_suggested" class="card col-sm-12 p-0 tab-panel border-0">
                        <?php do_action('rkmw_subscription_notices'); ?>

                        <div class="card-body p-0">
                            <div class="col-sm-12 m-0 p-0">
                                <div class="card col-sm-12 my-4 p-1 border-0 ">
                                    <?php if (is_array($view->suggested) && !empty($view->suggested)) { ?>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <th style="width: 30%;"><?php echo esc_html__("Keyword", RKMW_PLUGIN_NAME) ?></th>
                                                <th scope="col" title="<?php echo esc_html__("Country", RKMW_PLUGIN_NAME) ?>"><?php echo esc_html__("Co", RKMW_PLUGIN_NAME) ?></th>
                                                <th style="width: 150px;">
                                                    <i class="fa fa-users" title="<?php echo esc_html__("Competition", RKMW_PLUGIN_NAME) ?>"></i>
                                                    <?php echo esc_html__("Competition", RKMW_PLUGIN_NAME) ?>
                                                </th>
                                                <th style="width: 80px;">
                                                    <i class="fa fa-search" title="<?php echo esc_html__("SEO Search Volume", RKMW_PLUGIN_NAME) ?>"></i>
                                                    <?php echo esc_html__("SV", RKMW_PLUGIN_NAME) ?>
                                                </th>
                                                <th style="width: 135px;">
                                                    <i class="fa fa-comments-o" title="<?php echo esc_html__("Recent discussions", RKMW_PLUGIN_NAME) ?>"></i>
                                                    <?php echo esc_html__("Discussion", RKMW_PLUGIN_NAME) ?>
                                                </th>
                                                <th style="width: 100px;">
                                                    <i class="fa fa-bar-chart" title="<?php echo esc_html__("Trending", RKMW_PLUGIN_NAME) ?>"></i>
                                                    <?php echo esc_html__("Trend", RKMW_PLUGIN_NAME) ?>
                                                </th>
                                                <th style="width: 20px;"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($view->suggested as $key => $row) {
                                                $research = '';
                                                $keyword_labels = array();

                                                if ($row->data <> '') {
                                                    $research = json_decode($row->data);
                                                }

                                                $in_briefcase = false;
                                                if (!empty($view->keywords))
                                                    foreach ($view->keywords as $krow) {
                                                        if (trim(strtolower($krow->keyword)) == trim(strtolower($row->keyword))) {
                                                            $in_briefcase = true;
                                                            break;
                                                        }
                                                    }
                                                ?>
                                                <tr id="rkmw_row_<?php echo (int)$row->id ?>" class="<?php echo($in_briefcase ? 'bg-briefcase' : '') ?>">
                                                    <td style="width: 280px;">
                                                        <span style="display: block; clear: left; float: left;"><?php echo esc_html($row->keyword) ?></span>
                                                    </td>
                                                    <td>
                                                        <span style="display: block; clear: left; float: left;"><?php echo esc_html($row->country) ?></span>
                                                    </td>
                                                    <td style="width: 20%; color: <?php echo esc_attr($research->sc->color) ?>"><?php echo(isset($research->sc->text) ? '<span data-value="' . esc_attr($research->sc->value) . '">' . esc_html($research->sc->text) . '</span>' : '') ?></td>
                                                    <td style="width: 13%; color: <?php echo esc_attr($research->sv->color) ?>"><?php echo(isset($research->sv) ? '<span data-value="' . (int)$research->sv->absolute . '">' . (is_numeric($research->sv->absolute) ? number_format($research->sv->absolute, 0, '.', ',') . '</span>' : esc_html($research->sv->absolute)) : '') ?></td>
                                                    <td style="width: 15%; color: <?php echo esc_attr($research->tw->color) ?>"><?php echo(isset($research->tw) ? '<span data-value="' . esc_attr($research->tw->value) . '">' . esc_html($research->tw->text) . '</span>' : '') ?></td>
                                                    <td style="width: 100px;">
                                                        <?php if (isset($research->td)) { ?>
                                                            <?php
                                                            if (isset($research->td->absolute) && is_array($research->td->absolute) && !empty($research->td->absolute)) {
                                                                $last = 0.1;
                                                                $datachar = [];
                                                                foreach ($research->td->absolute as $td) {
                                                                    if ((float)$td > 0) {
                                                                        $datachar[] = $td;
                                                                        $last = $td;
                                                                    } else {
                                                                        $datachar[] = $last;
                                                                    }
                                                                }
                                                                if (!empty($datachar)) {
                                                                    $research->td->absolute = array_splice($datachar, -7);
                                                                }
                                                            } else {
                                                                $research->td->absolute = [0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1];
                                                            }
                                                            ?>
                                                            <div style="width: 60px;height: 30px;">
                                                                <canvas class="rkmw_trend" data-values="<?php echo join(',', $research->td->absolute) ?>"></canvas>
                                                            </div>

                                                        <?php } ?>
                                                    </td>
                                                    <td class="px-0 py-2" style="width: 20px">
                                                        <div class="rkmw_sm_menu">
                                                            <div class="sm_icon_button sm_icon_options">
                                                                <i class="fa fa-ellipsis-v"></i>
                                                            </div>
                                                            <div class="rkmw_sm_dropdown">
                                                                <ul class="text-left p-2 m-0 ">
                                                                    <?php if ($in_briefcase) { ?>
                                                                        <li class="bg-briefcase m-0 p-1 py-2 text-black-50">
                                                                            <i class="rkmw_icons_small rkmw_briefcase_icon"></i>
                                                                            <?php echo esc_html__("Already in briefcase", RKMW_PLUGIN_NAME); ?>
                                                                        </li>
                                                                    <?php } else { ?>
                                                                        <li class="rkmw_research_add_briefcase m-0 p-1 py-2" data-keyword="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>">
                                                                            <i class="rkmw_icons_small rkmw_briefcase_icon"></i>
                                                                            <?php echo esc_html__("Add to briefcase", RKMW_PLUGIN_NAME); ?>
                                                                        </li>
                                                                    <?php } ?>
                                                                    <?php if (current_user_can('rkmw_manage_settings')) { ?>
                                                                        <li class="rkmw_delete_found m-0 p-1 py-2" data-id="<?php echo (int)$row->id ?>" data-keyword="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>">
                                                                            <i class="rkmw_icons_small fa fa-trash-o"></i>
                                                                            <?php echo esc_html__("Delete Keyword", RKMW_PLUGIN_NAME) ?>
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
                                            <h4 class="text-center"><?php echo esc_html__("Welcome to Suggested Keywords", RKMW_PLUGIN_NAME); ?></h4>
                                            <h5 class="text-center"><?php echo esc_html__("Once a week, Rank My WP checks all the keywords from your Keywords Briefcase.", RKMW_PLUGIN_NAME); ?></h5>
                                            <h5 class="text-center"><?php echo esc_html__("If it finds better keywords, they will be listed here", RKMW_PLUGIN_NAME); ?></h5>
                                            <h6 class="text-center text-black-50 mt-3"><?php echo esc_html__("Until then, add keywords in Briefcase", RKMW_PLUGIN_NAME); ?>:</h6>
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
                    <?php echo RKMW_Classes_ObjController::getClass('RKMW_Core_BlockKnowledgeBase')->init(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
