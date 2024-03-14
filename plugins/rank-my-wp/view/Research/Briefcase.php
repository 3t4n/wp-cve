<div id="rkmw_wrap">
    <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockToolbar')->init(); ?>
    <?php do_action('rkmw_notices'); ?>
    <div class="d-flex flex-row my-0 bg-white">
        <?php echo RKMW_Classes_ObjController::getClass('RKMW_Models_Menu')->getAdminTabs(RKMW_Classes_Helpers_Tools::getValue('tab', 'briefcase'), 'rkmw_research'); ?>
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-white pl-1 pr-1 mr-0">
            <div class="flex-grow-1 mr-2 rkmw_flex">
                <?php do_action('rkmw_form_notices'); ?>

                <div class="card col-sm-12 p-0">
                    <div class="card-body p-2 bg-title rounded-top">
                        <div class="rkmw_help_question float-right">
                            <a href="https://howto.rankmywp.com/kb/keyword-research/#briefcase" target="_blank"><i class="fa fa-question-circle"></i></a>
                        </div>
                        <div class="rkmw_icons_content p-3 py-4">
                            <div class="rkmw_icons rkmw_briefcase_icon m-2"></div>
                        </div>
                        <h3 class="card-title"><?php echo esc_html__("Keywords Briefcase", RKMW_PLUGIN_NAME); ?>:</h3>
                        <div class="card-title-description m-2"><?php echo esc_html__("Keywords Briefcase is essential to managing your SEO Strategy. With Keywords Briefcase you'll find the best opportunities for keywords you're using in the Awareness Stage, Decision Stage and other stages you may plan for your Customer's Journey.", RKMW_PLUGIN_NAME); ?></div>
                    </div>
                    <div id="rkmw_briefcase" class="card col-sm-12 p-0 tab-panel border-0">
                        <?php do_action('rkmw_subscription_notices'); ?>

                        <?php if (isset($view->keywords) && !empty($view->keywords)) { ?>
                            <div class="row px-3">
                                <form method="get" class="form-inline col-sm-12">
                                    <input type="hidden" name="page" value="<?php echo RKMW_Classes_Helpers_Tools::getValue('page') ?>">
                                    <input type="hidden" name="tab" value="<?php echo RKMW_Classes_Helpers_Tools::getValue('tab') ?>">
                                    <div class="col-sm-3 p-0">
                                        <h3 class="card-title text-dark p-2"><?php echo esc_html__("Labels", RKMW_PLUGIN_NAME); ?>:</h3>
                                    </div>
                                    <div class="col-sm-9 p-0 py-2">
                                        <div class="d-flex flex-row justify-content-end p-0 m-0">
                                            <input type="search" class="d-inline-block align-middle col-sm-7 p-2 mr-2" id="post-search-input" autofocus name="skeyword" value="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword(RKMW_Classes_Helpers_Tools::getValue('skeyword')) ?>"/>
                                            <input type="submit" class="btn btn-primary" value="<?php echo esc_html__("Search Keyword", RKMW_PLUGIN_NAME) ?>"/>
                                            <?php if (RKMW_Classes_Helpers_Tools::getIsset('skeyword') || RKMW_Classes_Helpers_Tools::getIsset('slabel')) { ?>
                                                <button type="button" class="btn btn-info ml-1 p-v-xs" onclick="location.href = '<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'briefcase') ?>';" style="cursor: pointer"><?php echo esc_html__("Show All", RKMW_PLUGIN_NAME) ?></button>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="rkmw_filter_label p-2">
                                        <?php if (isset($view->labels) && !empty($view->labels)) {
                                            $keyword_labels = RKMW_Classes_Helpers_Tools::getValue('slabel', array());
                                            foreach ($view->labels as $label) {
                                                ?>
                                                <input type="checkbox" name="slabel[]" onclick="form.submit();" id="search_checkbox_<?php echo (int)$label->id ?>" style="display: none;" value="<?php echo (int)$label->id ?>" <?php echo(in_array((int)$label->id, (array)$keyword_labels) ? 'checked' : '') ?> />
                                                <label for="search_checkbox_<?php echo (int)$label->id ?>" class="rkmw_circle_label fa <?php echo(in_array((int)$label->id, (array)$keyword_labels) ? 'rkmw_active' : '') ?>" data-id="<?php echo (int)$label->id ?>" style="background-color: <?php echo esc_attr($label->color) ?>" title="<?php echo esc_attr($label->name) ?>"><?php echo esc_html($label->name) ?></label>
                                                <?php

                                            }
                                        } ?>
                                    </div>
                                </form>
                            </div>
                        <?php } ?>
                        <div class="card-body p-0">
                            <div class="col-sm-12 m-0 p-0">

                                <div class="card col-sm-12 my-4 mx-0 p-0 border-0">
                                    <?php if (isset($view->keywords) && !empty($view->keywords)) { ?>
                                        <div class="col p-1">
                                            <select name="rkmw_bulk_action" class="rkmw_bulk_action">
                                                <option value=""><?php echo esc_html__("Bulk Actions", RKMW_PLUGIN_NAME) ?></option>
                                                <option value="rkmw_ajax_briefcase_bulk_doserp"><?php echo esc_html__("Send to Rankings", RKMW_PLUGIN_NAME); ?></option>
                                                <option value="rkmw_ajax_briefcase_bulk_label"><?php echo esc_html__("Assign Label", RKMW_PLUGIN_NAME); ?></option>
                                                <option value="rkmw_ajax_briefcase_bulk_delete" data-confirm="<?php echo esc_html__("Ar you sure you want to delete the keywords?", RKMW_PLUGIN_NAME) ?>"><?php echo esc_html__("Delete") ?></option>
                                            </select>
                                            <button class="rkmw_bulk_submit btn btn-sm btn-success"><?php echo esc_html__("Apply"); ?></button>

                                            <div id="rkmw_label_manage_popup_bulk" tabindex="-1" class="rkmw_label_manage_popup modal" role="dialog">
                                                <div class="modal-dialog" style="width: 600px;">
                                                    <div class="modal-content bg-light">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title"><?php echo sprintf(esc_html__("Select Labels for: %s", RKMW_PLUGIN_NAME), esc_html__("selected keywords", RKMW_PLUGIN_NAME)); ?></h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body" style="min-height: 50px; display: table; margin: 10px 20px 10px 20px;">
                                                            <div class="pb-2 mx-2 small text-black-50"><?php echo esc_html__("By assigning these labels, you will reset the other labels you assigned for each keyword individually.", RKMW_PLUGIN_NAME); ?></div>
                                                            <?php if (isset($view->labels) && !empty($view->labels)) {
                                                                foreach ($view->labels as $label) {
                                                                    ?>
                                                                    <input type="checkbox" name="rkmw_labels[]" class="rkmw_bulk_labels" id="popup_checkbox_bulk_<?php echo (int)$label->id ?>" style="display: none;" value="<?php echo (int)$label->id ?>"/>
                                                                    <label for="popup_checkbox_bulk_<?php echo (int)$label->id ?>" class="rkmw_checkbox_label fa" style="background-color: <?php echo esc_attr($label->color) ?>" title="<?php echo esc_attr($label->name) ?>"><?php echo esc_html($label->name) ?></label>
                                                                    <?php
                                                                }
                                                            } else { ?>
                                                                <a class="btn btn-warning" href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'labels') ?>"><?php echo esc_html__("Add new Label", RKMW_PLUGIN_NAME); ?></a>
                                                            <?php } ?>
                                                        </div>
                                                        <?php if (isset($view->labels) && !empty($view->labels)) { ?>
                                                            <div class="modal-footer">
                                                                <button class="rkmw_bulk_submit btn-modal btn btn-success"><?php echo esc_html__("Save Labels", RKMW_PLUGIN_NAME); ?></button>
                                                            </div>
                                                        <?php } ?>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="p-1">
                                            <table class="table table-striped table-hover mx-0 p-0 ">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th><?php echo esc_html__("Keyword", RKMW_PLUGIN_NAME) ?></th>
                                                    <th>
                                                        <?php
                                                        if ($view->checkin->subscription_serpcheck) {
                                                            echo esc_html__("Rank", RKMW_PLUGIN_NAME);
                                                        } else {
                                                            echo esc_html__("Avg Rank", RKMW_PLUGIN_NAME);
                                                        }
                                                        ?>
                                                    </th>
                                                    <th><?php echo esc_html__("Search Volume", RKMW_PLUGIN_NAME) ?></th>
                                                    <th><?php echo esc_html__("Research", RKMW_PLUGIN_NAME) ?></th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach ($view->keywords as $key => $row) {
                                                    $row->rank = false;
                                                    if (!empty($view->rankkeywords)) {
                                                        foreach ($view->rankkeywords as $rankkeyword) {
                                                            if (strtolower($rankkeyword->keyword) == strtolower($row->keyword)) {
                                                                if ($view->checkin->subscription_serpcheck) {
                                                                    if ((int)$rankkeyword->rank > 0) {
                                                                        $row->rank = $rankkeyword->rank;
                                                                    }
                                                                } elseif ((int)$rankkeyword->average_position > 0) {
                                                                    $row->rank = $rankkeyword->average_position;
                                                                }
                                                            }
                                                        }
                                                    }

                                                    ?>
                                                    <tr id="rkmw_row_<?php echo (int)$row->id ?>">
                                                        <td style="width: 10px;">
                                                            <?php if (current_user_can('rkmw_manage_settings')) { ?>
                                                                <input type="checkbox" name="rkmw_edit[]" class="rkmw_bulk_input" value="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>"/>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="width: 500px;">
                                                            <?php if (!empty($row->labels)) {
                                                                foreach ($row->labels as $label) {
                                                                    ?>
                                                                    <span class="rkmw_circle_label fa" style="background-color: <?php echo esc_attr($label->color) ?>" data-id="<?php echo (int)$label->lid ?>" title="<?php echo esc_attr($label->name) ?>"></span>
                                                                    <?php
                                                                }
                                                            } ?>

                                                            <span style="display: block; clear: left; float: left;"><?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?></span>
                                                        </td>
                                                        <td style="width: 130px;">
                                                            <?php if (!$row->rank) { ?>
                                                                <?php if (isset($row->do_serp) && !$row->do_serp) { ?>
                                                                    <button class="rkmw_research_doserp btn btn-sm btn-link text-black-50 p-0 m-0 text-nowrap" data-value="999" data-success="<?php echo esc_html__("Check Rankings", RKMW_PLUGIN_NAME) ?>"  data-link="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings', 'rankings', array('strict=1', 'skeyword=' . RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword))) ?>" data-keyword="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>">
                                                                        <?php echo esc_html__("Send to Rankings", RKMW_PLUGIN_NAME) ?>
                                                                    </button>
                                                                <?php } elseif ($view->checkin->subscription_serpcheck) { ?>
                                                                    <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings', 'rankings', array('strict=1', 'skeyword=' . RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword))) ?>" data-value="998" style="font-weight: bold;font-size: 15px;"><?php echo esc_html__("Not indexed", RKMW_PLUGIN_NAME) ?></a>
                                                                <?php } else { ?>
                                                                    <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings', 'rankings', array('strict=1', 'skeyword=' . RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword))) ?>" data-value="998" style="font-weight: bold;font-size: 15px;"><?php echo esc_html__("GSC", RKMW_PLUGIN_NAME) ?></a>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings', 'rankings', array('strict=1', 'skeyword=' . RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword))) ?>" data-value="<?php echo (int)$row->rank ?>" style="font-weight: bold;font-size: 15px;"><?php echo (int)$row->rank ?></a>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="width: 140px;">
                                                            <?php if (isset($row->research->sv)) {
                                                                echo($row->research->sv->absolute <> '' ? '<span data-value="' . (int)$row->research->sv->absolute . '">' . ((isset($row->research->sv->absolute) && is_numeric($row->research->sv->absolute)) ? number_format($row->research->sv->absolute, 0, '.', ',') : $row->research->sv->absolute) . '</span>' : 0);
                                                            } else {
                                                                echo '<span data-value="0">' . "-" . '</span>';
                                                            } ?>
                                                        </td>
                                                        <td style="width: 160px;">
                                                            <?php if (isset($row->research->rank->value)) { ?>
                                                                <button data-value="<?php echo esc_attr($row->research->rank->value) ?>" onclick="jQuery('#rkmw_kr_research<?php echo (int)$key ?>').modal('show');" class="small btn btn-success btn-sm" style="cursor: pointer; width: 120px"><?php echo esc_html__("keyword info", RKMW_PLUGIN_NAME) ?></button>
                                                                <div class="progress" style="max-width: 120px; max-height: 3px">
                                                                    <?php
                                                                    $progress_color = 'danger';
                                                                    switch ($row->research->rank->value) {
                                                                        case ($row->research->rank->value < 4):
                                                                            $progress_color = 'danger';
                                                                            break;
                                                                        case ($row->research->rank->value < 6):
                                                                            $progress_color = 'warning';
                                                                            break;
                                                                        case ($row->research->rank->value < 8):
                                                                            $progress_color = 'info';
                                                                            break;
                                                                        case ($row->research->rank->value <= 10):
                                                                            $progress_color = 'success';
                                                                            break;
                                                                    }
                                                                    ?>
                                                                    <div class="progress-bar bg-<?php echo esc_attr($progress_color); ?>" role="progressbar" style="width: <?php echo((int)$row->research->rank->value * 10) ?>%" aria-valuenow="<?php echo (int)$row->research->rank->value ?>" aria-valuemin="0" aria-valuemax="10"></div>
                                                                </div>
                                                            <?php } else { ?>
                                                                <button data-value="0" style="cursor: pointer;" class="btn btn-sm btn-light bg-transparent"><?php echo esc_html__("No research data", RKMW_PLUGIN_NAME) ?></button>
                                                            <?php } ?>
                                                        </td>

                                                        <td class="px-0 py-2" style="width: 20px">
                                                            <div class="rkmw_sm_menu">
                                                                <div class="sm_icon_button sm_icon_options">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </div>
                                                                <div class="rkmw_sm_dropdown">
                                                                    <ul class="p-2 m-0 text-left">
                                                                        <li class="border-bottom m-0 p-1 py-2" data-keyword="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>">
                                                                            <i class="rkmw_icons_small rkmw_sla_icon"></i>
                                                                            <span onclick="jQuery(this).rkmw_copyKeyword()"><?php echo esc_html__("Copy Keyword", RKMW_PLUGIN_NAME) ?></span>
                                                                        </li>
                                                                        <?php if (current_user_can('rkmw_manage_settings')) { ?>
                                                                            <?php if (isset($row->do_serp) && !$row->do_serp) { ?>
                                                                                <li class="rkmw_research_doserp border-bottom m-0 p-1 py-2" data-success="<?php echo esc_html__("Check Rankings", RKMW_PLUGIN_NAME) ?>" data-link="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_rankings', 'rankings', array('strict=1', 'skeyword=' . RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword))) ?>" data-keyword="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>">
                                                                                    <i class="rkmw_icons_small rkmw_ranks_icon"></i>
                                                                                    <span><?php echo esc_html__("Send to Rankings", RKMW_PLUGIN_NAME) ?></span>
                                                                                </li>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                        <li class="border-bottom m-0 p-1 py-2">
                                                                            <i class="rkmw_icons_small rkmw_kr_icon"></i>
                                                                            <?php if ($row->research == '') { ?>
                                                                                <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'research', array('keyword=' . RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword, 'url'))) ?>" class="sq-nav-link"><?php echo esc_html__("Do a research", RKMW_PLUGIN_NAME) ?></a>
                                                                            <?php } else { ?>
                                                                                <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'research', array('keyword=' . RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword, 'url'))) ?>" class="sq-nav-link"><?php echo esc_html__("Refresh Research", RKMW_PLUGIN_NAME) ?></a>
                                                                            <?php } ?>
                                                                        </li>
                                                                        <li class="border-bottom m-0 p-1 py-2">
                                                                            <i class="rkmw_icons_small rkmw_labels_icon"></i>
                                                                            <span onclick="jQuery('#rkmw_label_manage_popup<?php echo (int)$key ?>').modal('show')"><?php echo esc_html__("Assign Label", RKMW_PLUGIN_NAME); ?></span>
                                                                        </li>
                                                                        <?php if (current_user_can('rkmw_manage_settings')) { ?>
                                                                            <li class="rkmw_delete m-0 p-1 py-2" data-id="<?php echo (int)$row->id ?>" data-keyword="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>">
                                                                                <i class="rkmw_icons_small fa fa-trash-o"></i>
                                                                                <?php echo esc_html__("Delete Keyword", RKMW_PLUGIN_NAME) ?>
                                                                            </li>
                                                                        <?php } ?>

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>

                                                </tbody>
                                            </table>
                                        </div>

                                        <?php foreach ($view->keywords as $key => $row) { ?>

                                            <?php if ($row->count > 0 && isset($row->posts) && !empty($row->posts)) { ?>
                                                <div id="rkmw_kr_posts<?php echo (int)$key; ?>" tabindex="-1" class="rkmw_kr_posts modal" role="dialog">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content bg-light">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title"><?php echo esc_html__("Optimized with", RKMW_PLUGIN_NAME); ?>:
                                                                    <strong><?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?></strong>
                                                                    <span style="font-weight: bold; font-size: 110%"></span>
                                                                </h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body" style="min-height: 90px;">
                                                                <ul class="col-sm-12" style="list-style: initial">
                                                                    <?php
                                                                    foreach ($row->posts as $post_id => $permalink) { ?>
                                                                        <li class="row py-2 border-bottom">
                                                                            <a href="<?php echo get_edit_post_link($post_id, false); ?>" target="_blank"><?php echo (string)$permalink ?></a>
                                                                        </li>
                                                                    <?php } ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div id="rkmw_kr_research<?php echo (int)$key; ?>" tabindex="-1" class="rkmw_kr_research modal" role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content bg-light">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title"><?php echo esc_html__("Keyword", RKMW_PLUGIN_NAME); ?>:
                                                                <strong><?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?></strong>
                                                                <span style="font-weight: bold; font-size: 110%"></span>
                                                            </h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body" style="min-height: 90px;">
                                                            <ul class="col-sm-12">
                                                                <?php if (!isset($row->country)) $row->country = ''; ?>
                                                                <li class="row py-3 border-bottom">
                                                                    <div class="col-sm-4"><?php echo esc_html__("Country", RKMW_PLUGIN_NAME) ?>:</div>
                                                                    <div class="col-sm-6"><?php echo esc_html($row->country) ?></div>
                                                                </li>
                                                                <?php if (isset($row->research->sc)) { ?>
                                                                    <li class="row py-3 border-bottom">
                                                                        <div class="col-sm-4"><?php echo esc_html__("Competition", RKMW_PLUGIN_NAME) ?>:</div>
                                                                        <div class="col-sm-6" style="color: <?php echo esc_attr($row->research->sc->color) ?>"><?php echo($row->research->sc->text <> '' ? esc_html($row->research->sc->text) : '-') ?></div>
                                                                    </li>
                                                                <?php } ?>
                                                                <?php if (isset($row->research->sv)) { ?>
                                                                    <li class="row py-3 border-bottom">
                                                                        <div class="col-sm-4"><?php echo esc_html__("Search Volume", RKMW_PLUGIN_NAME) ?>:</div>
                                                                        <div class="col-sm-6" style="color: <?php echo esc_attr($row->research->sv->color) ?>"><?php echo((isset($row->research->sv->absolute) && is_numeric($row->research->sv->absolute)) ? number_format($row->research->sv->absolute, 0, '.', ',') : esc_attr($row->research->sv->absolute)) ?></div>
                                                                    </li>
                                                                <?php } ?>
                                                                <?php if (isset($row->research->tw)) { ?>
                                                                    <li class="row py-3 border-bottom">
                                                                        <div class="col-sm-4"><?php echo esc_html__("Recent discussions", RKMW_PLUGIN_NAME) ?>:</div>
                                                                        <div class="col-sm-6" style="color: <?php echo esc_attr($row->research->tw->color) ?>"><?php echo($row->research->tw->text <> '' ? esc_html($row->research->tw->text) : '-') ?></div>
                                                                    </li>
                                                                <?php } ?>
                                                                <?php if (isset($row->research->td)) { ?>
                                                                    <li class="row py-3">
                                                                        <div class="col-sm-4"><?php echo esc_html__("Trending", RKMW_PLUGIN_NAME) ?>:</div>
                                                                        <div class="col-sm-6" style="color: <?php echo esc_attr($row->research->td->color) ?>">
                                                                            <?php if (isset($row->research->td->absolute) && is_array($row->research->td->absolute) && !empty($row->research->td->absolute)) {
                                                                                $last = 0.1;
                                                                                $datachar = [];
                                                                                foreach ($row->research->td->absolute as $td) {
                                                                                    if ((float)$td > 0) {
                                                                                        $datachar[] = $td;
                                                                                        $last = $td;
                                                                                    } else {
                                                                                        $datachar[] = $last;
                                                                                    }
                                                                                }
                                                                                if (!empty($datachar)) {
                                                                                    $row->research->td->absolute = array_splice($datachar, -7);
                                                                                }
                                                                            } else {
                                                                                $row->research->td->absolute = [0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1];
                                                                            }
                                                                            ?>
                                                                            <div style="width: 60px;height: 30px;">
                                                                                <canvas id="rkmw_trend<?php echo (int)$key; ?>" class="rkmw_trend" data-values="<?php echo join(',', (array)$row->research->td->absolute) ?>"></canvas>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div id="rkmw_label_manage_popup<?php echo (int)$key ?>" tabindex="-1" class="rkmw_label_manage_popup modal" role="dialog">
                                                <div class="modal-dialog" style="width: 600px;">
                                                    <div class="modal-content bg-light">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title"><?php echo sprintf(esc_html__("Select Labels for: %s", RKMW_PLUGIN_NAME), '<strong style="font-size: 115%">' . RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) . '</strong>'); ?></h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body" style="min-height: 50px; display: table; margin: 10px 20px 10px 20px;">
                                                            <?php if (isset($view->labels) && !empty($view->labels)) {

                                                                $keyword_labels = array();
                                                                if (!empty($row->labels)) {
                                                                    foreach ($row->labels as $label) {
                                                                        $keyword_labels[] = $label->lid;
                                                                    }
                                                                }

                                                                foreach ($view->labels as $label) {
                                                                    ?>
                                                                    <input type="checkbox" name="rkmw_labels" id="popup_checkbox_<?php echo (int)$key ?>_<?php echo (int)$label->id ?>" style="display: none;" value="<?php echo (int)$label->id ?>" <?php echo(in_array((int)$label->id, $keyword_labels) ? 'checked' : '') ?> />
                                                                    <label for="popup_checkbox_<?php echo (int)$key ?>_<?php echo (int)$label->id ?>" class="rkmw_checkbox_label fa <?php echo(in_array((int)$label->id, $keyword_labels) ? 'rkmw_active' : '') ?>" style="background-color: <?php echo esc_attr($label->color) ?>" title="<?php echo esc_attr($label->name) ?>"><?php echo esc_html($label->name) ?></label>
                                                                    <?php
                                                                }

                                                            } else { ?>

                                                                <a class="btn btn-warning" href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'labels') ?>"><?php echo esc_html__("Add new Label", RKMW_PLUGIN_NAME); ?></a>

                                                            <?php } ?>
                                                        </div>
                                                        <?php if (isset($view->labels) && !empty($view->labels)) { ?>
                                                            <div class="modal-footer">
                                                                <button data-keyword="<?php echo RKMW_Classes_Helpers_Sanitize::escapeKeyword($row->keyword) ?>" class="rkmw_save_keyword_labels btn btn-success"><?php echo esc_html__("Save Labels", RKMW_PLUGIN_NAME); ?></button>
                                                            </div>
                                                        <?php } ?>

                                                    </div>
                                                </div>

                                            </div>
                                        <?php } ?>
                                    <?php } elseif (RKMW_Classes_Helpers_Tools::getIsset('skeyword') || RKMW_Classes_Helpers_Tools::getIsset('slabel')) { ?>
                                        <div class="card-body">
                                            <h3 class="text-center"><?php echo esc_html($view->error); ?></h3>
                                        </div>
                                    <?php } else { ?>

                                        <div class="card-body">
                                            <h4 class="text-center"><?php echo esc_html__("Welcome to Keywords Briefcase", RKMW_PLUGIN_NAME); ?></h4>
                                            <div class="col-sm-12 m-2 text-center">
                                                <a href="<?php echo RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'research') ?>" class="btn btn-lg btn-primary">
                                                    <i class="fa fa-plus-square-o"></i> <?php echo esc_html__("Go Find New Keywords", RKMW_PLUGIN_NAME); ?>
                                                </a>

                                                <div class="col-sm-12 mt-5 mx-2">
                                                    <h5 class="text-left my-3 text-info"><?php echo esc_html__("Tips: How to add Keywords in Briefcase?", RKMW_PLUGIN_NAME); ?></h5>
                                                    <ul>
                                                        <li class="text-left" style="font-size: 15px;"><?php echo sprintf(esc_html__("From %sKeyword Research%s send keywords to Keywords Briefcase.", RKMW_PLUGIN_NAME), '<a href="' . RKMW_Classes_Helpers_Tools::getAdminUrl('rkmw_research', 'research') . '" >', '</a>'); ?></li>
                                                        <li class="text-left" style="font-size: 15px;"><?php echo esc_html__("If you already have a list of keywords, Import the keywords usign the below button.", RKMW_PLUGIN_NAME); ?></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (current_user_can('rkmw_manage_settings')) { ?>
                                        <div class="col-sm-12 row py-2 mx-0 my-3 mt-4 pt-4 border-bottom-0 border-top">
                                            <div class="col-sm-8 p-0 pr-3">
                                                <div class="font-weight-bold"><?php echo esc_html__("Backup/Restore Briefcase Keywords", RKMW_PLUGIN_NAME); ?>:</div>
                                                <div class="small text-black-50"><?php echo esc_html__("Keep your briefcase keywords safe in case you change your domain or reinstall the plugin", RKMW_PLUGIN_NAME); ?></div>
                                                <div class="small text-black-50"><?php echo sprintf(esc_html__("%sLearn how to import keywords into briefcase%s", RKMW_PLUGIN_NAME), '<a href="https://howto.rankmywp.com/kb/keyword-research/#briefcase_backup_keywords" target="_blank">', '</a>'); ?></div>
                                            </div>
                                            <div class="col-sm-4 p-0 text-center">
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_briefcase_backup', 'rkmw_nonce'); ?>
                                                    <input type="hidden" name="action" value="rkmw_briefcase_backup"/>
                                                    <button type="submit" class="btn rounded-0 btn-success my-1 px-2 mx-2 noloading" style="min-width: 175px"><?php echo esc_html__("Download Keywords", RKMW_PLUGIN_NAME); ?></button>
                                                </form>
                                                <div>
                                                    <button type="button" class="btn rounded-0 btn-success my-1 px-2 mx-2" style="min-width: 175px" onclick="jQuery('.rkmw_briefcase_restore_dialog').modal('show')" data-dismiss="modal"><?php echo esc_html__("Import Keywords", RKMW_PLUGIN_NAME); ?></button>
                                                </div>
                                                <div class="rkmw_briefcase_restore_dialog modal" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content bg-light">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title"><?php echo esc_html__("Restore Briefcase Keywords", RKMW_PLUGIN_NAME); ?></h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form name="import" action="" method="post" enctype="multipart/form-data">
                                                                    <div class="col-sm-12 row py-2 mx-0 my-3">
                                                                        <div class="col-sm-4 p-0 pr-3">
                                                                            <div class="font-weight-bold"><?php echo esc_html__("Restore Keywords", RKMW_PLUGIN_NAME); ?>:</div>
                                                                            <div class="small text-black-50"><?php echo esc_html__("Upload the file with the saved keywords from Keywords Briefcase.", RKMW_PLUGIN_NAME); ?></div>
                                                                        </div>
                                                                        <div class="col-sm-8 p-0 input-group">
                                                                            <div class="col-sm-8 form-group m-0 p-0 my-2">
                                                                                <input type="file" class="form-control-file" name="rkmw_upload_file">
                                                                            </div>
                                                                            <div class="col-sm-4 form-group m-0 p-0 my-2">
                                                                                <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_briefcase_restore', 'rkmw_nonce'); ?>
                                                                                <input type="hidden" name="action" value="rkmw_briefcase_restore"/>
                                                                                <button type="submit" class="btn rounded-0 btn-success btn-sm px-3 mx-2"><?php echo esc_html__("Upload", RKMW_PLUGIN_NAME); ?></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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