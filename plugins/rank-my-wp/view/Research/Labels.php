<div id="rkmw_wrap">
    <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockToolbar')->init(); ?>
    <?php do_action('rkmw_notices'); ?>
    <div class="d-flex flex-row my-0 bg-white">
        <?php echo RKMW_Classes_ObjController::getClass('RKMW_Models_Menu')->getAdminTabs(RKMW_Classes_Helpers_Tools::getValue('tab', 'labels'), 'rkmw_research'); ?>
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-white pl-1 pr-1 mr-0">
            <div class="flex-grow-1 mr-2 rkmw_flex">
                <?php do_action('rkmw_form_notices'); ?>

                <div class="card col-sm-12 p-0">
                    <div class="card-body p-2 bg-title rounded-top">
                        <div class="rkmw_help_question float-right">
                            <a href="https://howto.rankmywp.com/kb/keyword-research/#labels" target="_blank"><i class="fa fa-question-circle"></i></a>
                        </div>
                        <div class="rkmw_icons_content p-3 py-4">
                            <div class="rkmw_icons rkmw_labels_icon m-2"></div>
                        </div>
                        <h3 class="card-title"><?php echo esc_html__("Briefcase Labels", RKMW_PLUGIN_NAME); ?>:</h3>
                        <div class="card-title-description m-2"><?php echo esc_html__("Briefcase Labels will help you sort your keywords based on your SEO strategy. Labels are like categories and you can quickly filter your keywords by one or more labels.", RKMW_PLUGIN_NAME); ?></div>
                    </div>
                    <div id="rkmw_briefcaselabels" class="card col-sm-12 p-0 tab-panel border-0">
                        <?php do_action('rkmw_subscription_notices'); ?>

                        <button class="btn btn-lg btn-warning text-white col-sm-3 ml-3" onclick="jQuery('.rkmw_add_labels_dialog').modal('show')" data-dismiss="modal">
                            <i class="fa fa-plus-square-o"></i> <?php echo esc_html__("Add new Label", RKMW_PLUGIN_NAME); ?>
                        </button>
                        <div class="rkmw_add_labels_dialog modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content bg-light">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><?php echo esc_html__("Add New Label", RKMW_PLUGIN_NAME); ?></h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="rkmw_labelname"><?php echo esc_html__("Label Name", RKMW_PLUGIN_NAME); ?></label>
                                            <input type="text" class="form-control" id="rkmw_labelname" maxlength="35"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="rkmw_labelcolor" style="display: block"><?php echo esc_html__("Label Color", RKMW_PLUGIN_NAME); ?></label>
                                            <input type="text" id="rkmw_labelcolor" value="<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF)); ?>"/>
                                        </div>


                                    </div>
                                    <div class="modal-footer" style="border-bottom: 1px solid #ddd;">
                                        <button type="button" id="rkmw_save_label" class="btn btn-success"><?php echo esc_html__("Add Label", RKMW_PLUGIN_NAME); ?></button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="rkmw_edit_label_dialog modal" tabindex="-1" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content bg-light">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><?php echo esc_html__("Edit Label", RKMW_PLUGIN_NAME); ?></h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="rkmw_labelname"><?php echo esc_html__("Label Name", RKMW_PLUGIN_NAME); ?></label>
                                            <input type="text" class="form-control" id="rkmw_labelname" maxlength="35"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="rkmw_labelcolor"><?php echo esc_html__("Label Color", RKMW_PLUGIN_NAME); ?></label>
                                            <input type="text" id="rkmw_labelcolor"/>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" id="rkmw_labelid"/>
                                        <button type="button" id="rkmw_save_label" class="btn btn-success"><?php echo esc_html__("Save Label", RKMW_PLUGIN_NAME); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="col-sm-12 m-0 p-0">
                                <div class="card col-sm-12 my-4 p-0 border-0">
                                    <?php if (is_array($view->labels) && !empty($view->labels)) { ?>
                                        <div class="col p-1">
                                            <select name="rkmw_bulk_action" class="rkmw_bulk_action">
                                                <option value=""><?php echo esc_html__("Bulk Actions", RKMW_PLUGIN_NAME) ?></option>
                                                <option value="rkmw_ajax_labels_bulk_delete" data-confirm="<?php echo esc_html__("Ar you sure you want to delete the labels?", RKMW_PLUGIN_NAME) ?>"><?php echo esc_html__("Delete") ?></option>
                                            </select>
                                            <button class="rkmw_bulk_submit btn btn-sm btn-success"><?php echo esc_html__("Apply"); ?></button>
                                        </div>

                                        <div class="p-1">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th style="width: 10px;"></th>
                                                    <th style="width: 70%;"><?php echo esc_html__("Name", RKMW_PLUGIN_NAME) ?></th>
                                                    <th scope="col" title="<?php echo esc_html__("Color", RKMW_PLUGIN_NAME) ?>"><?php echo esc_html__("Color", RKMW_PLUGIN_NAME) ?></th>
                                                    <th style="width: 20px;"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                foreach ($view->labels as $key => $row) {
                                                    ?>
                                                    <tr id="rkmw_row_<?php echo (int)$row->id ?>">
                                                        <td style="width: 10px;">
                                                            <?php if (current_user_can('rkmw_manage_settings')) { ?>
                                                                <input type="checkbox" name="rkmw_edit[]" class="rkmw_bulk_input" value="<?php echo (int)$row->id ?>"/>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="width: 50%;" class="text-left">
                                                            <?php echo esc_html($row->name) ?>
                                                        </td>
                                                        <td style="width: 50%;">
                                                            <span style="display: block; float: left; background-color:<?php echo esc_attr($row->color) ?>; width: 20px;height: 20px; margin-right: 5px;"></span><?php echo esc_attr($row->color) ?>
                                                        </td>

                                                        <td class="px-0 py-2" style="width: 20px">
                                                            <div class="rkmw_sm_menu">
                                                                <div class="sm_icon_button sm_icon_options">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </div>
                                                                <div class="rkmw_sm_dropdown">
                                                                    <ul class="text-left p-2 m-0">
                                                                        <li class="rkmw_edit_label border-bottom m-0 p-1 py-2" data-id="<?php echo (int)$row->id ?>" data-name="<?php echo esc_attr($row->name) ?>" data-color="<?php echo esc_attr($row->color) ?>">
                                                                            <i class="rkmw_icons_small rkmw_labels_icon"></i>
                                                                            <?php echo esc_html__("Edit Label", RKMW_PLUGIN_NAME) ?>
                                                                        </li>
                                                                        <?php if (current_user_can('rkmw_manage_settings')) { ?>
                                                                            <li class="rkmw_delete_label m-0 p-1 py-2" data-id="<?php echo (int)$row->id ?>">
                                                                                <i class="rkmw_icons_small fa fa-trash-o"></i>
                                                                                <?php echo esc_html__("Delete Label", RKMW_PLUGIN_NAME) ?>
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
                                        </div>
                                    <?php } else { ?>
                                        <div class="card-body">
                                            <h4 class="text-center"><?php echo esc_html__("Welcome to Briefcase Labels", RKMW_PLUGIN_NAME); ?></h4>
                                            <div class="col-sm-12 m-2 text-center">
                                                <button class="btn btn-lg btn-primary text-white col-sm-4 ml-3" onclick="jQuery('.rkmw_add_labels_dialog').modal('show')" data-dismiss="modal">
                                                    <i class="fa fa-plus-square-o"></i> <?php echo esc_html__("Add new Label", RKMW_PLUGIN_NAME); ?>
                                                </button>
                                            </div>
                                            <div class="col-sm-12 mt-5 mx-2">
                                                <h5 class="text-left my-3 text-info"><?php echo esc_html__("TIPS: How Should I Create My Labels?", RKMW_PLUGIN_NAME); ?></h5>
                                                <ul>
                                                    <li onclick="jQuery('.rkmw_add_labels_dialog').modal('show')" style="font-size: 15px;"><?php echo sprintf(esc_html__("Click on %sAdd New Label%s button, add a label name and choose a color for it.", RKMW_PLUGIN_NAME), '<strong style="cursor: pointer">', '</strong>'); ?></li>
                                                    <li style="font-size: 15px;">
                                                        <a href="https://howto.rankmywp.com/kb/keyword-research/#labels" target="_blank"><?php echo esc_html__("Read more details about Briefcase Labels", RKMW_PLUGIN_NAME); ?></a>
                                                    </li>
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