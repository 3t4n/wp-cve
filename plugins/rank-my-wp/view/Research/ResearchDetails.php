<?php
if (!empty($view->kr)) {
    //For teh saved country
    if (isset($_COOKIE['rkmw_country'])) {
        $view->country = sanitize_text_field($_COOKIE['rkmw_country']);
    }

    foreach ($view->kr as $nr => $row) {
        if (!isset($row->keyword)) continue;

        $in_briefcase = false;
        if (!empty($view->keywords)) {
            foreach ($view->keywords as $krow) {
                if (trim(strtolower($krow->keyword)) == trim(strtolower($row->keyword))) {
                    $in_briefcase = true;
                    break;
                }
            }
        }
        ?>
        <tr class="<?php echo($in_briefcase ? 'bg-briefcase' : '') ?> <?php echo($row->initial ? 'bg-selected' : '') ?>">
            <td style="width: 33%;"><?php echo(isset($row->keyword) ? RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) : '') ?></td>
            <td style="width: 1%;"><?php echo(isset($view->country) ? esc_html($view->country) : 'com') ?></td>
            <td style="width: 20%; color: <?php echo esc_attr($row->stats->sc->color) ?>"><?php echo(isset($row->stats->sc->text) ? '<span data-value="' . esc_attr($row->stats->sc->value) . '">' . esc_html($row->stats->sc->text) . '</span>' : '') ?></td>
            <td style="width: 13%; color: <?php echo esc_attr($row->stats->sv->color) ?>"><?php echo(isset($row->stats->sv) ? '<span data-value="' . (int)$row->stats->sv->absolute . '">' . (is_numeric($row->stats->sv->absolute) ? number_format($row->stats->sv->absolute, 0, '.', ',') . '</span>' : esc_html($row->stats->sv->absolute)) : '') ?></td>
            <td style="width: 15%; color: <?php echo esc_attr($row->stats->tw->color) ?>"><?php echo(isset($row->stats->tw) ? '<span data-value="' . esc_attr($row->stats->tw->value) . '">' . esc_html($row->stats->tw->text) . '</span>' : '') ?></td>
            <td style="width: 12%; color: <?php echo esc_attr($row->stats->td->color) ?>">
                <?php if (isset($row->stats->td)) { ?>
                    <div style="width: 60px; height: 30px;">
                        <canvas class="rkmw_trend" data-values=" <?php echo join(',', (array)$row->stats->td->absolute) ?>"></canvas>
                    </div>
                <?php } ?>
            </td>
            <td class="px-0" style="width: 24px;">
                <div class="rkmw_sm_menu">
                    <div class="sm_icon_button sm_icon_options">
                        <i class="fa fa-ellipsis-v"></i>
                    </div>
                    <div class="rkmw_sm_dropdown">
                        <ul class="p-2 m-0 text-left">
                            <?php
                            $edit_link = RKMW_Classes_Helpers_Tools::getAdminUrl('/post-new.php?keyword=' .  RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword, 'url'));
                            if ($view->post_id) {
                                $edit_link = RKMW_Classes_Helpers_Tools::getAdminUrl('post.php?post=' . (int)$view->post_id . '&action=edit&keyword=' . RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword, 'url'));
                            }
                            ?>
                            <li class="border-bottom m-0 p-1 py-2 noloading" data-keyword="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>">
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
    <?php }
} ?>