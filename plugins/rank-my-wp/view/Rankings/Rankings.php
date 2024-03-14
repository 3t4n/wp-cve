<?php
$date_format = get_option('date_format');
$time_format = get_option('time_format');
$timezone = (int)get_option('gmt_offset');
$connect = json_decode(wp_json_encode(RKMW_Classes_Helpers_Tools::getOption('connect')));

$view->checkin->subscription_serpcheck = (isset($view->checkin->subscription_serpcheck) ? $view->checkin->subscription_serpcheck : 0);
$days_back = (int)RKMW_Classes_Helpers_Tools::getValue('days_back', 30);
echo (string)$view->getScripts();
?>
<div id="rkmw_wrap">
    <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockToolbar')->init(); ?>
    <?php do_action('rkmw_notices'); ?>
    <div class="d-flex flex-row my-0 bg-white">
        <?php
        if (!current_user_can('rkmw_manage_rankings')) {
            echo '<div class="col-sm-12 alert alert-success text-center m-0 p-3">' . esc_html__("You do not have permission to access this page. You need RKMW Admin role.", RKMW_PLUGIN_NAME) . '</div>';
            return;
        }
        ?>
        <?php echo RKMW_Classes_ObjController::getClass('RKMW_Models_Menu')->getAdminTabs(RKMW_Classes_Helpers_Tools::getValue('tab', 'rankings'), 'rkmw_rankings'); ?>
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-white pl-1 pr-1 mr-0">
            <div class="flex-grow-1 mr-2 rkmw_flex">
                <?php do_action('rkmw_form_notices'); ?>

                <div class="card col-sm-12 p-0">
                    <div class="card-body p-2 bg-title rounded-top">
                        <div class="rkmw_icons_content p-3 py-4">
                            <div class="rkmw_icons rkmw_rankings_icon m-2"></div>
                        </div>
                        <h3 class="card-title"><?php echo esc_html__("Google Rankings", RKMW_PLUGIN_NAME); ?>:</h3>
                        <?php if ($view->checkin->subscription_serpcheck) { ?>
                            <div class="card-title-description m-2"><?php echo esc_html__("It's a fully functional SEO Ranking Tool that helps you find the true position of your website in Google for any keyword and any country you want", RKMW_PLUGIN_NAME); ?></div>
                        <?php } else { ?>
                            <div class="card-title-description m-2"><?php echo esc_html__("Get the Google Search Console average possitions, clicks and impressions for all organic keywords of your website.", RKMW_PLUGIN_NAME); ?></div>
                        <?php } ?>
                    </div>


                    <div id="rkmw_ranks" class="card col-sm-12 p-0 tab-panel border-0">
                        <?php do_action('rkmw_subscription_notices'); ?>

                        <div class="row col-sm-12 my-4 mx-0 text-center">
                            <div class="my-0 mx-auto justify-content-center text-center">
                                <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings', 'gscsync') ?>" class="btn btn-info"><?php echo esc_html__("Synchronize Keywords with Google Search Console", RKMW_PLUGIN_NAME); ?></a>
                            </div>
                        </div>

                        <?php if (RKMW_Classes_Helpers_Tools::getIsset('schanges') ||
                            RKMW_Classes_Helpers_Tools::getIsset('ranked') ||
                            RKMW_Classes_Helpers_Tools::getIsset('rank') ||
                            RKMW_Classes_Helpers_Tools::getIsset('skeyword') ||
                            RKMW_Classes_Helpers_Tools::getIsset('type') ||
                            RKMW_Classes_Helpers_Tools::getValue('skeyword', '')
                        ) { ?>
                            <div class="text-right col-sm-12 p-0 px-2 my-2">
                                <div class="rkmw_serp_settings_button mx-1 my-0">
                                    <button type="button" class="btn btn-info p-v-xs" onclick="location.href = '<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings') ?>';" style="cursor: pointer"><?php echo esc_html__("Show All", RKMW_PLUGIN_NAME) ?></button>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (isset($view->ranks) && !empty($view->ranks)) { ?>
                            <?php if ($view->checkin->subscription_serpcheck) { ?>
                                <?php if (isset($view->info) && !empty($view->info)) { ?>
                                    <?php if (!RKMW_Classes_Helpers_Tools::getValue('skeyword', false)) { ?>
                                        <div class="rkmw_stats row px-2 py-0 m-0 ">
                                            <div class="card col-sm p-0 m-1 bg-white shadow-sm">
                                                <?php
                                                if (isset($view->info->average) && !empty($view->info->average)) {
                                                    $today_average = end($view->info->average);
                                                    $today_average = number_format((int)$today_average[1], 2, '.', ',');
                                                    reset($view->info->average);
                                                } else {
                                                    $today_average = '0';
                                                }

                                                if (isset($view->info->average) && count((array)$view->info->average) > 1) {
                                                    foreach ($view->info->average as $key => $average) {
                                                        if ($key > 0 && !empty($view->info->average[$key])) {
                                                            $view->info->average[$key][0] = date('m/d/Y', strtotime($view->info->average[$key][0]));
                                                            $view->info->average[$key][1] = (float)$view->info->average[$key][1];
                                                            if ($view->info->average[$key][1] == 0) {
                                                                $view->info->average[$key][1] = 100;
                                                            }
                                                        }
                                                        $average[1] = (int)$average[1];
                                                    }

                                                }
                                                ?>
                                                <div class="card-content overflow-hidden m-0">
                                                    <div class="media align-items-stretch">
                                                        <div class="media-body p-3">
                                                            <div class="col-sm-12 row">
                                                                <div class="col-sm-6 border-right">
                                                                    <h5>
                                                                        <a href="<?php echo esc_url(add_query_arg(array('ranked' => 1), RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings'))) ?>" data-toggle="tooltip" title="<?php echo esc_html__("Only show ranked articles", RKMW_PLUGIN_NAME) ?>">
                                                                            <i class="fa fa-line-chart pull-left mt-1" aria-hidden="true"></i>
                                                                            <?php echo($today_average == 0 ? 100 : number_format($today_average, 2, '.', ',')) ?>
                                                                        </a></h5>
                                                                    <span class="small"><?php echo esc_html__("Today Avg. Ranking", RKMW_PLUGIN_NAME); ?></span>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <h5>
                                                                        <a href="<?php echo esc_url(add_query_arg(array('schanges' => 1), RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings'))) ?>" data-toggle="tooltip" title="<?php echo esc_html__("Only show SERP changes", RKMW_PLUGIN_NAME) ?>">
                                                                            <i class="fa fa-arrows-v pull-left mt-1" aria-hidden="true"></i>
                                                                            <?php
                                                                            $changes = 0;
                                                                            $topten = 0;
                                                                            $positive_changes = 0;
                                                                            if (!empty($view->ranks))
                                                                                foreach ($view->ranks as $key => $row) {
                                                                                    if ($row->change <> 0) {
                                                                                        $changes++;
                                                                                        if ($row->change < 0) {
                                                                                            $positive_changes++;
                                                                                        }
                                                                                    }
                                                                                    if ((int)$row->rank > 0 && (int)$row->rank <= 10) {
                                                                                        $topten++;
                                                                                    }
                                                                                }
                                                                            echo (int)$changes;
                                                                            ?>
                                                                        </a>
                                                                    </h5>
                                                                    <span class="small"><?php echo esc_html__("Today SERP Changes", RKMW_PLUGIN_NAME); ?></span>
                                                                </div>
                                                            </div>

                                                            <div class="media-right py-3 media-middle ">
                                                                <div class="col-sm-12 px-0">
                                                                    <?php if (isset($view->info->average) && count((array)$view->info->average) > 1) { ?>
                                                                        <div id="rkmw_chart" class="rkmw_chart no-p" style="width:95%; height: 90px;"></div>
                                                                        <script>
                                                                            if (typeof google !== 'undefined') {
                                                                                google.setOnLoadCallback(function () {
                                                                                    var rkmw_chart_val = drawChart("rkmw_chart", <?php echo wp_json_encode($view->info->average)?> , true);
                                                                                });
                                                                            }
                                                                        </script>

                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card col-sm p-0 m-1 bg-white shadow-sm">
                                                <div class="card-content  overflow-hidden m-0">
                                                    <div class="media align-items-stretch">
                                                        <div class="media-body p-3" style="min-height: 187px;">
                                                            <h5><?php echo esc_html__("Progress & Achievements", RKMW_PLUGIN_NAME) ?></h5>
                                                            <span class="small"><?php echo sprintf(esc_html__("the latest %s days Google Rankings evolution", RKMW_PLUGIN_NAME), $days_back); ?></span>


                                                            <div class="media-right py-3 media-middle ">
                                                                <?php if ($topten > 0) { ?>
                                                                    <h6 class="col-sm-12 px-0 text-success" style="line-height: 25px;font-size: 14px;">
                                                                        <i class="fa fa-arrow-up" style="font-size: 9px !important;margin: 0 5px;vertical-align: middle;"></i><?php echo sprintf(esc_html__("%s keyword ranked in TOP 10", RKMW_PLUGIN_NAME), '<strong>' . $topten . '</strong>'); ?>
                                                                    </h6>
                                                                <?php } ?>
                                                                <?php if ($positive_changes > 0) { ?>
                                                                    <h6 class="col-sm-12 px-0 text-success" style="line-height: 25px;font-size: 14px;">
                                                                        <i class="fa fa-arrow-up" style="font-size: 9px !important;margin: 0 5px;vertical-align: middle;"></i><?php echo sprintf(esc_html__("%s keyword ranked better today", RKMW_PLUGIN_NAME), '<strong>' . $positive_changes . '</strong>'); ?>
                                                                    </h6>
                                                                <?php } ?>
                                                                <?php if (isset($view->info->average) && !empty($view->info->average)) {
                                                                    $average_changes = 0;
                                                                    //if there is a history in ranking for this keyword
                                                                    //get first date minus last date to see the average improvment
                                                                    if (isset($view->info->average[1][1]) && isset($view->info->average[(count($view->info->average) - 1)][1])) {
                                                                        $average_changes = $view->info->average[1][1] - $view->info->average[(count($view->info->average) - 1)][1];
                                                                    }
                                                                    if ($average_changes > 0) { ?>
                                                                        <h6 class="col-sm-12 px-0 text-success" style="line-height: 25px;font-size: 14px;">
                                                                            <i class="fa fa-arrow-up" style="font-size: 9px !important;margin: 0 5px;vertical-align: middle;"></i><?php echo sprintf(esc_html__("Ranks improved with an average of %s in the last 7 days.", RKMW_PLUGIN_NAME), '<strong>' . $average_changes . '</strong>'); ?>
                                                                        </h6>
                                                                    <?php }
                                                                } ?>
                                                                <?php if ($topten == 0 && $positive_changes == 0 && $average_changes == 0) { ?>
                                                                    <h4 class="col-sm-12 px-0 text-info"><?php echo esc_html__("No progress found yet", RKMW_PLUGIN_NAME) ?></h4>
                                                                <?php } else { ?>
                                                                    <a class="btn btn-sm btn-success" href="https://twitter.com/intent/tweet?text=<?php echo urlencode('I love the ranking results I get for my Pages with Rank My WP plugin for #WordPress. @RankMyWp #SERP') ?>">Share Your Success</a>
                                                                <?php } ?>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            <div class="card col-sm-12 py-3 px-0 m-0 border-0">

                                <div class="col p-1">
                                    <select name="rkmw_bulk_action" class="rkmw_bulk_action">
                                        <option value=""><?php echo esc_html__("Bulk Actions", RKMW_PLUGIN_NAME) ?></option>
                                        <option value="rkmw_ajax_rank_bulk_delete" data-confirm="<?php echo esc_html__("Ar you sure you want to delete the keyword?", RKMW_PLUGIN_NAME) ?>"><?php echo esc_html__("Delete") ?></option>
                                        <?php if ($view->checkin->subscription_serpcheck) { ?>
                                            <option value="rkmw_ajax_rank_bulk_refresh"><?php echo esc_html__("Refresh Serp", RKMW_PLUGIN_NAME) ?></option>
                                        <?php } ?>
                                    </select>
                                    <button class="rkmw_bulk_submit btn btn-sm btn-success"><?php echo esc_html__("Apply"); ?></button>
                                </div>

                                <div class="p-1">
                                    <table class="table table-striped table-hover table-ranks">
                                        <thead>
                                        <tr>
                                            <th style="width: 10px;"></th>
                                            <th><?php echo esc_html__("Keyword", RKMW_PLUGIN_NAME) ?></th>
                                            <th><?php echo esc_html__("Path", RKMW_PLUGIN_NAME) ?></th>
                                            <?php if ($view->checkin->subscription_serpcheck) { ?>
                                                <th><?php echo esc_html__("Rank", RKMW_PLUGIN_NAME) ?></th>
                                                <th><?php echo esc_html__("Best", RKMW_PLUGIN_NAME) ?></th>
                                            <?php } else { ?>
                                                <th><?php echo esc_html__("Avg Rank", RKMW_PLUGIN_NAME) ?></th>
                                            <?php } ?>
                                            <th><?php echo esc_html__("Details", RKMW_PLUGIN_NAME) ?></th>

                                            <th class="no-sort" style="width: 2%;"></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        foreach ($view->ranks as $key => $row) {
                                            if (RKMW_Classes_Helpers_Tools::getIsset('schanges') && (!isset($row->change) || (isset($row->change) && !$row->change))) {
                                                continue;
                                            }
                                            if (RKMW_Classes_Helpers_Tools::getIsset('ranked') && (!isset($row->rank) || (isset($row->rank) && !$row->rank))) {
                                                continue;
                                            }
                                            if (RKMW_Classes_Helpers_Tools::getIsset('strict')) {
                                                if (RKMW_Classes_Helpers_Tools::getIsset('skeyword') && (strtolower(RKMW_Classes_Helpers_Tools::getValue('skeyword')) <> strtolower($row->keyword))) {
                                                    continue;
                                                }
                                            }
                                            ?>

                                            <tr>
                                                <td style="width: 10px;">
                                                    <input type="checkbox" name="rkmw_edit[]" class="rkmw_bulk_input" value="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>"/>
                                                </td>
                                                <td>
                                                    <span><?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?></span>
                                                </td>
                                                <?php if (!$row->permalink && !$view->checkin->subscription_serpcheck) { ?>
                                                    <td style="color: #919aa2; font-style: italic">
                                                        <?php echo esc_html__("Google Search Console has no data for this keyword", RKMW_PLUGIN_NAME) ?>
                                                        <br/>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings', 'gscsync') ?>" class="btn btn-sm btn-info"><?php echo esc_html__("Sync Keywords", RKMW_PLUGIN_NAME); ?></a>
                                                    </td>
                                                <?php } else { ?>
                                                    <td>
                                                        <?php
                                                        $path = parse_url($row->permalink, PHP_URL_PATH);
                                                        $path = ($path <> '') ? $path : '/';
                                                        ?>
                                                        <a href="<?php echo esc_url($row->permalink) ?>" target="_blank"><?php echo urldecode($path) ?></a>
                                                    </td>
                                                    <?php if ($view->checkin->subscription_serpcheck) { ?>
                                                        <td>
                                                            <?php
                                                            echo(!$row->rank ? '<span style="font-size: 13px">' . esc_html__("Not indexed", RKMW_PLUGIN_NAME) . '</span>' : (int)$row->rank);
                                                            if (isset($row->change)) {
                                                                echo(($row->change) ? sprintf('<span class="badge badge-' . ($row->change < 0 ? 'success' : 'danger') . ' mx-2"><i class="fa fa-sort-%s"></i><span> </span><span>%s</span></span>', ($row->change < 0 ? 'up' : 'down'), $row->change) : '');
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php echo((int)$row->best > 0 ? (int)$row->best : "-"); ?>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td title="<?php echo esc_html__("Google Search Console has no data for this keyword", RKMW_PLUGIN_NAME) ?>">
                                                            <?php echo($row->average_position <= 0 ? esc_html__("GSC", RKMW_PLUGIN_NAME) : number_format($row->average_position, 1, '.', ',')); ?>
                                                        </td>
                                                    <?php } ?>
                                                    <td>
                                                        <button onclick="jQuery('#rkmw_ranking_modal<?php echo (int)$key ?>').modal('show');" class="small btn btn-success btn-sm" style="cursor: pointer; width: 120px"><?php echo esc_html__("rank details", RKMW_PLUGIN_NAME) ?></button>
                                                    </td>
                                                <?php } ?>

                                                <td>
                                                    <div class="rkmw_sm_menu">
                                                        <div class="sm_icon_button sm_icon_options">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </div>
                                                        <div class="rkmw_sm_dropdown">
                                                            <ul class="p-2 m-0 text-left">
                                                                <?php if ($view->checkin->subscription_serpcheck) { ?>
                                                                    <li class="border-bottom m-0 p-1 py-2">
                                                                        <form method="post" class="p-0 m-0">
                                                                            <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_serp_refresh_post', 'rkmw_nonce'); ?>
                                                                            <input type="hidden" name="action" value="rkmw_serp_refresh_post"/>
                                                                            <input type="hidden" name="keyword" value="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>"/>
                                                                            <i class="rkmw_icons_small fa fa-refresh" style="padding: 2px"></i>
                                                                            <button type="submit" class="btn btn-sm bg-transparent p-0 m-0">
                                                                                <?php echo esc_html__("Check Ranking again", RKMW_PLUGIN_NAME) ?>
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                <?php } ?>

                                                                <li class="m-0 p-1 py-2">
                                                                    <form method="post" class="p-0 m-0">
                                                                        <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_serp_delete_keyword', 'rkmw_nonce'); ?>
                                                                        <input type="hidden" name="action" value="rkmw_serp_delete_keyword"/>
                                                                        <input type="hidden" name="keyword" value="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>"/>
                                                                        <i class="rkmw_icons_small fa fa-trash-o" style="padding: 2px"></i>
                                                                        <button type="submit" class="btn btn-sm bg-transparent p-0 m-0">
                                                                            <?php echo esc_html__("Remove Keyword", RKMW_PLUGIN_NAME) ?>
                                                                        </button>
                                                                    </form>
                                                                </li>
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
                                </div>
                                <?php
                                foreach ($view->ranks as $key => $row) {
                                    if (RKMW_Classes_Helpers_Tools::getIsset('schanges') && (!isset($row->change) || (isset($row->change) && !$row->change))) {
                                        continue;
                                    }
                                    if (RKMW_Classes_Helpers_Tools::getIsset('ranked') && (!isset($row->rank) || (isset($row->rank) && !$row->rank))) {
                                        continue;
                                    }
                                    ?>
                                    <div id="rkmw_ranking_modal<?php echo (int)$key; ?>" tabindex="-1" class="rkmw_ranking_modal modal" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content bg-light">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><?php echo esc_html__("Keyword", RKMW_PLUGIN_NAME); ?>: <?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>
                                                        <span style="font-weight: bold; font-size: 110%"></span>
                                                    </h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body pt-0" style="min-height: 90px;">
                                                    <ul class="col-sm-12">
                                                        <li class="row py-2 border-bottom">
                                                            <div class="col-sm-12">
                                                                <strong><a href="<?php echo esc_url($row->permalink) ?>" target="_blank"><?php echo urldecode($row->permalink) ?></a></strong>
                                                            </div>
                                                        </li>

                                                        <li class="row py-2 border-bottom">
                                                            <div class="col-sm-6"><?php echo esc_html__("Impressions", RKMW_PLUGIN_NAME) ?>:</div>
                                                            <div class="col-sm-6">
                                                                <strong><?php echo number_format($row->impressions, 0, '.', ',') ?></strong>
                                                            </div>
                                                        </li>
                                                        <li class="row py-2 border-bottom">
                                                            <div class="col-sm-6"><?php echo esc_html__("Clicks", RKMW_PLUGIN_NAME) ?>:</div>
                                                            <div class="col-sm-6">
                                                                <strong><?php echo number_format($row->clicks, 0, '.', ',') ?></strong>
                                                            </div>
                                                        </li>

                                                        <li class="row py-2 border-bottom">
                                                            <div class="col-sm-6"><?php echo esc_html__("Optimized with SLA", RKMW_PLUGIN_NAME) ?>:</div>
                                                            <div class="col-sm-6">
                                                                <strong><?php echo((int)$row->optimized > 0 ? (int)$row->optimized . '%' : 'N/A') ?></strong>
                                                            </div>
                                                        </li>

                                                        <?php if ($view->checkin->subscription_serpcheck) { ?>
                                                            <li class="row py-3 border-bottom">
                                                                <div class="col-sm-6"><?php echo esc_html__("Social Shares", RKMW_PLUGIN_NAME) ?>:</div>
                                                                <div class="col-sm-6">
                                                                    <?php
                                                                    echo "<strong>" . number_format((int)$row->facebook, 0, '.', ',') . "</strong>" . ' ' . esc_html__("Facebook Shares", RKMW_PLUGIN_NAME) . "<br />";
                                                                    echo "<strong>" . number_format((int)$row->reddit, 0, '.', ',') . "</strong>" . ' ' . esc_html__("Reddit Shares", RKMW_PLUGIN_NAME) . "<br />";
                                                                    echo "<strong>" . number_format((int)$row->pinterest, 0, '.', ',') . "</strong>" . ' ' . esc_html__("Pinterest Pins", RKMW_PLUGIN_NAME) . "<br />";
                                                                    ?>
                                                                </div>
                                                            </li>
                                                        <?php } ?>

                                                        <li class="row py-2 border-bottom">
                                                            <div class="col-sm-6"><?php echo esc_html__("Country", RKMW_PLUGIN_NAME) ?>:</div>
                                                            <div class="col-sm-6">
                                                                <strong><?php echo esc_html($row->country) ?></strong>
                                                            </div>
                                                        </li>

                                                        <?php if (isset($row->datetime)) { ?>
                                                            <li class="row py-2 border-bottom-0">
                                                                <div class="col-sm-6"><?php echo esc_html__("Date", RKMW_PLUGIN_NAME) ?>:</div>
                                                                <div class="col-sm-6">
                                                                    <strong><?php echo date(get_option('date_format'), strtotime($row->datetime)) ?></strong>
                                                                </div>
                                                            </li>
                                                        <?php } ?>

                                                        <li class="small text-center"><?php echo esc_html__("Note! The clicks and impressions data is taken from Google Search Console for the last 90 days for the current URL.", RKMW_PLUGIN_NAME) ?></li>

                                                    </ul>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>

                            </div>
                        <?php } elseif (RKMW_Classes_Helpers_Tools::getIsset('skeyword') || RKMW_Classes_Helpers_Tools::getIsset('slabel')) { ?>
                            <div class="card-body">
                                <h3 class="text-center"><?php echo esc_html__("No ranking found.", RKMW_PLUGIN_NAME); ?></h3>
                            </div>
                        <?php } elseif (!RKMW_Classes_Error::isError()) { ?>
                            <div class="card-body">
                                <h4 class="text-center"><?php echo esc_html__("Welcome to Google Rankings", RKMW_PLUGIN_NAME); ?></h4>
                                <div class="col-sm-12 m-2 text-center">
                                    <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'briefcase') ?>" class="btn btn-lg btn-primary">
                                        <i class="fa fa-plus-square-o"></i> <?php echo esc_html__("Add keywords from Briefcase", RKMW_PLUGIN_NAME); ?>
                                    </a>

                                    <div class="col-sm-12 mt-5 mx-2">
                                        <h5 class="text-left my-3 text-info"><?php echo esc_html__("Tips: How to add Keywords in Rankings?", RKMW_PLUGIN_NAME); ?></h5>
                                        <ul>
                                            <li class="text-left" style="font-size: 15px;"><?php echo sprintf(esc_html__("From %sKeywords Briefcase%s you can send keywords to SERP Checker to track the ranking evolution.", RKMW_PLUGIN_NAME), '<a href="' . RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'briefcase') . '" >', '</a>'); ?></li>
                                            <li class="text-left" style="font-size: 15px;"><?php echo sprintf(esc_html__("Connect with %sGoogle Search Console%s to synchronize the keywords for which your website is ranking.", RKMW_PLUGIN_NAME), '<strong>', '</strong>'); ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="card-body">
                                <div class="col-sm-12 px-2 py-3 text-center">
                                    <img src="<?php echo RKMW_ASSETS_URL . 'img/settings/noconnection.jpg' ?>" style="width: 300px">
                                </div>
                                <div class="col-sm-12 m-2 text-center">
                                    <div class="col-sm-12 alert alert-success text-center m-0 p-3">
                                        <i class="fa fa-exclamation-triangle" style="font-size: 18px !important;"></i> <?php echo sprintf(esc_html__("There is a connection error with Rank My WP Cloud. Please check the connection and %srefresh the page%s.", RKMW_PLUGIN_NAME), '<a href="javascript:location.reload();" >', '</a>') ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
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
