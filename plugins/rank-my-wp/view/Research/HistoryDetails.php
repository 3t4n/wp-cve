<td colspan="8">
    <div class="col-sm-12 m-0 p-0">
        <div class="card col-sm-12 my-1 mx-0 p-0 border-0 ">
            <table class="table table-striped" cellpadding="0" cellspacing="0" border="0">
                <thead>
                <tr>
                    <th ><?php echo esc_html__("Keyword", RKMW_PLUGIN_NAME) ?></th>
                    <th title="<?php echo esc_html__("Recent discussions", RKMW_PLUGIN_NAME) ?>">
                        <i class="fa fa-users"></i>
                        <?php echo esc_html__("Discussion", RKMW_PLUGIN_NAME) ?>
                    </th>
                    <th title="<?php echo esc_html__("SEO Search Volume", RKMW_PLUGIN_NAME) ?>">
                        <i class="fa fa-search"></i>
                        <?php echo esc_html__("SV", RKMW_PLUGIN_NAME) ?>
                    </th>
                    <th title="<?php echo esc_html__("Competition", RKMW_PLUGIN_NAME) ?>">
                        <i class="fa fa-comments-o"></i>
                        <?php echo esc_html__("Competition", RKMW_PLUGIN_NAME) ?>
                    </th>
                    <th title="<?php echo esc_html__("Trending", RKMW_PLUGIN_NAME) ?>">
                        <i class="fa fa-bar-chart"></i>
                        <?php echo esc_html__("Trend", RKMW_PLUGIN_NAME) ?>
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($view->kr) && isset($view->kr->keyword)) {
                    $view->kr->keyword = explode(',', $view->kr->keyword);
                    $view->kr->data = json_decode($view->kr->data);
                    if (!empty($view->kr->data))
                        foreach ($view->kr->data as $nr => $row) {
                            $in_briefcase = false;
                            if (!empty($view->keywords))
                                foreach ($view->keywords as $krow) {
                                    if (trim(strtolower($krow->keyword)) == trim(strtolower($row->keyword))) {
                                        $in_briefcase = true;
                                        break;
                                    }
                                }
                            ?>
                            <tr class="<?php echo($in_briefcase ? 'bg-briefcase' : '') ?> <?php echo($row->initial ? 'bg-selected' : '') ?>" >
                                <td nowrap="nowrap" style="width: 38%;"><?php echo esc_html($row->keyword) ?></td>
                                <?php if (!empty($row->stats)) { ?>
                                    <td nowrap="nowrap" style="width: 17%;">
                                        <span class="rkmw_top_keywords_rank" style="color:<?php echo(isset($row->stats->tw->color) ? esc_attr($row->stats->tw->color) : '#fff') ?>"><?php echo(isset($row->stats->tw->text) ? esc_html($row->stats->tw->text) : '-') ?></span>
                                    </td>
                                    <td nowrap="nowrap" style="width: 15%;">
                                        <span class="rkmw_top_keywords_rank" style="color:<?php echo(isset($row->stats->sv->color) ? esc_attr($row->stats->sv->color) : '#fff') ?>"><?php echo(isset($row->stats->sv->absolute) ? (is_numeric($row->stats->sv->absolute) ? number_format($row->stats->sv->absolute, 0, '.', ',') : esc_html($row->stats->sv->absolute)) : '-') ?></span>
                                    </td>
                                    <td nowrap="nowrap" style="width: 20%;">
                                        <span class="rkmw_top_keywords_rank" style="color:<?php echo(isset($row->stats->sc->color) ? esc_attr($row->stats->sc->color) : '#fff') ?>"><?php echo(isset($row->stats->sc->text) ? esc_html($row->stats->sc->text) : '-') ?></span>
                                    </td>
                                    <td nowrap="nowrap" style="width: 0.5%;">
                                        <div style="width: 60px;height: 30px;">
                                            <canvas class="rkmw_trend" data-values=" <?php echo join(',', (array)$row->stats->td->absolute) ?>"></canvas>
                                        </div>
                                    </td>
                                <?php } else { ?>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                <?php } ?>
                                <td class="px-0 py-2" style="width: 20px">
                                    <div class="rkmw_sm_menu">
                                        <div class="sm_icon_button sm_icon_options">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </div>
                                        <div class="rkmw_sm_dropdown">
                                            <ul class="p-2 m-0 text-left">
                                                <li class="rkmw_research_selectit border-bottom m-0 p-1 py-2 noloading" data-keyword="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>">
                                                    <i class="rkmw_icons_small rkmw_sla_icon"></i>
                                                    <span onclick="jQuery(this).rkmw_copyKeyword()"><?php echo esc_html__("Copy Keyword", RKMW_PLUGIN_NAME) ?></span>
                                                </li>
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

                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</td>