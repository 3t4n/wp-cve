<?php
if (!defined('WPINC')) {
    die('Closed');
}
//if (defined('REGMAGIC_ADDON'))
    //include_once(RM_ADDON_ADMIN_DIR . 'views/template_rm_submissions_manager.php');
//else {
wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script('script_rm_moment');
wp_enqueue_script('script_rm_daterangepicker');
wp_enqueue_style('style_rm_daterangepicker');

$tag_array = explode(',', (string)$data->filter->filters['filter_tags']);
if(!empty($data->filter->filters['rm_fromdate'])) {
    $start_date = date("m/d/Y", strtotime($data->filter->filters['rm_fromdate']));
} else {
    $start_date = "";
}
if(!empty($data->filter->filters['rm_dateupto'])) {
    $end_date = date("m/d/Y", strtotime($data->filter->filters['rm_dateupto']));
} else {
    $end_date = "";
}
?>
<div class="wrap">
    <div class="rmagic rmagic-inbox-manager rmagic-wide">
        <h1 class="wp-heading-inline rm-mb-0">
            Inbox<?php if(defined('REGMAGIC_ADDON') && !empty($data->form_id)) { ?><a id="rm_submission_report" class="rm_submission_report add-new-h2" onclick="jQuery.rm_do_action('rm_submission_manager_form', 'rm_submission_export')" href="javascript:void(0)">Export All</a><?php } ?>
        </h1>
        <!-------Content area Starts----->
        
        <div class="rm-wp-page-info rm-mb-2 rm-position-absolute"><a target="_blank" href="https://registrationmagic.com/productivity-driven-user-registration-submissions-inbox/">Documentation</a></div>

        <div class="rmnotice-row">
            <div class="rmnotice">
                You can set logo and text for submission PDFs in <a target="_blank" href="<?php echo admin_url('admin.php?page=rm_options_general'); ?>">Global Settings</a>.
            </div>
        </div>
        
        <?php
        if(count($data->forms) === 0) { ?>
        <div class="rmnotice-container">
            <div class="rmnotice">
                <?php echo wp_kses_post((string)RM_UI_Strings::get('MSG_NO_FORM_SUB_MAN')); ?>
            </div>
            </div>
            <?php } elseif ($data->submissions || $data->filter->filters['rm_interval'] != 'all' || $data->filter->searched) { ?>
                <?php if(defined('REGMAGIC_ADDON')) { ?>
                <ul class="subsubsub">
                    <li class="all"><a href="javascript:void(0)" onclick="rm_load_all_subs();" aria-current="page" <?php if (!in_array('Read', $tag_array) && !in_array('Unread', $tag_array)) echo "class=current"; ?>>All <?php if($data->filter->total_entries['read'] != 0 && $data->filter->total_entries['unread'] != 0) { ?><span class="count"><?php echo esc_html("({$data->filter->total_entries['all']})"); ?></span><?php } ?></a></li>
                    <?php if($data->filter->total_entries['read'] != 0) { ?>
                    <li class="read">|<a href="javascript:void(0)" onclick="rm_load_read_subs();" <?php if (in_array('Read', $tag_array)) echo "class=current"; ?>>  Read <?php echo esc_html("({$data->filter->total_entries['read']})"); ?></a> </li>
                    <?php } ?>
                    <?php if($data->filter->total_entries['unread'] != 0) { ?>
                    <li class="unread">|<a href="javascript:void(0)" onclick="rm_load_unread_subs();" <?php if (in_array('Unread', $tag_array)) echo "class=current"; ?>> Unread <?php echo esc_html("({$data->filter->total_entries['unread']})"); ?></a></li>
                    <?php } ?>
               
                </ul>
                <?php } ?>
                <div class="rm-table">
                        <?php if($data->filter->pagination->total_pages) { ?>
                        <div class="alignright">
                        <div class="rm-di-flex rm-box-center">
                            <span class="rm-white-space"><?php _e('Results per page', 'custom-registration-form-builder-with-submission-manager'); ?> &rarr;</span>
                            <select class="rm-pager-toggle" onchange="set_inbox_entry_depth(this);">
                                <option value="10" <?php echo $data->entries_per_page == 10 ? esc_attr('selected') : ''; ?>>Page 1-10</option>
                                <option value="20" <?php echo $data->entries_per_page == 20 ? esc_attr('selected') : ''; ?>>Page 1-20</option>
                                <option value="30" <?php echo $data->entries_per_page == 30 ? esc_attr('selected') : ''; ?>>Page 1-30</option>
                                <option value="40" <?php echo $data->entries_per_page == 40 ? esc_attr('selected') : ''; ?>>Page 1-40</option>
                                <option value="50" <?php echo $data->entries_per_page == 50 ? esc_attr('selected') : ''; ?>>Page 1-50</option>
                            </select>
                        </div>
                        </div>
                        <?php } ?>
                            <input type="hidden" name="page" value="" id="rm_submission_manage">
                            <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
                            <input type="hidden" name="rm_form_id" value="<?php echo esc_attr($data->filter->form_id); ?>" id="rm_form_id_input_field" />
                                <div class="tablenav top rm-tablenav-top">
                                    <div class="alignleft actions bulkactions">
                                        <label for="bulk-action-selector-top" class="screen-reader-text"><?php esc_html_e('Select bulk action', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                        <select id="bulk-action-selector-top">
                                            <option value="-1"><?php esc_html_e('Bulk actions', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                            <option value="delete"><?php esc_html_e('Delete', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                            <?php if(defined('REGMAGIC_ADDON')) { ?>
                                            <option value="mark-read"><?php esc_html_e('Mark Read', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                            <option value="mark-unread"><?php esc_html_e('Mark Unread', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                            <?php if(!empty($data->form_id)) { ?>
                                            <option value="export"><?php esc_html_e('Export', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                            <?php } } ?>
                                        </select>
                                        <input type="submit" id="rm-bulk-action-top" class="button action" value="Apply" onclick="rm_apply_bulk_action(this);">
                                    </div>
                                    <!--
                                    <div class="alignleft actions">
                                        <select id="rm_submissions-all-date">
                                            <option value="all"><?php _e('All Dates', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                            <option value="today" <?php if($data->filter->filters['rm_interval'] == "today") echo "selected"; ?>><?php echo wp_kses_post((string)RM_UI_Strings::get("LABEL_TODAY")); ?></option>
                                            <option value="week" <?php if($data->filter->filters['rm_interval'] == "week") echo "selected"; ?>><?php echo wp_kses_post((string)RM_UI_Strings::get("LABEL_THIS_WEEK")); ?></option>
                                            <option value="month" <?php if ($data->filter->filters['rm_interval'] == "month") echo "selected"; ?>><?php echo wp_kses_post((string)RM_UI_Strings::get("LABEL_THIS_MONTH")); ?></option>
                                            <option value="year" <?php if ($data->filter->filters['rm_interval'] == "year") echo "selected"; ?>><?php echo wp_kses_post((string)RM_UI_Strings::get("LABEL_THIS_YEAR")); ?></option>
                                            <option value="custom" <?php if ($data->filter->filters['rm_interval'] == "custom") echo "selected"; ?>><?php echo wp_kses_post((string)RM_UI_Strings::get("LABEL_PERIOD")); ?></option>
                                        </select>
                                        <input type="submit" id="doaction" class="button action" value="Filter" onclick="rm_apply_date_filter();">
                                    </div>
                                    -->
                                    <div class="alignleft actions rm-mb-2">
                                          <input type="text" id="rm_submission_date_range" placeholder="<?php if(!empty($start_date) && !empty($end_date)) { echo esc_attr("{$start_date}-{$end_date}"); } else { _e('All Dates','custom-registration-form-builder-with-submission-manager'); } ?>" value="<?php if(!empty($start_date) && !empty($end_date)) { echo esc_attr("{$start_date}-{$end_date}"); } ?>"/>
                                          <input type="submit" id="doaction" class="button action" value="Filter" onclick="rm_apply_date_filter();">
                                          
                                    </div>
                                    
                                    
                                    <div class="alignleft actions rm-mb-2">
                                        <select id="rm_submissions-all-forms">
                                            <option value="all">All Forms</option>
                                            <?php foreach($data->forms as $form_id => $form_name) { ?>
                                            <option value="<?php echo esc_attr($form_id); ?>"<?php echo ($data->filter->get_form() == $form_id) ? esc_attr(" selected") : ""; ?>><?php echo esc_html($form_name); ?></option>
                                            <?php } ?>
                                        </select>
                                        <button type="submit" id="rm-apply-form-filter" class="button action" onclick="rm_apply_form_filter();"><i class='fa fa-spinner fa-spin ' style="display: none"></i> <?php _e('Filter', 'custom-registration-form-builder-with-submission-manager'); ?></button>
                                    </div>
                                    <div class="alignleft actions rm-position-relative rm-mb-2">
                                        <?php //if(defined('REGMAGIC_ADDON') || $data->filter->get_form()) { ?>
                                        <input type="button" id="rm-advanced-filters" class="button action" value="Advanced Filters">
                                        <?php //} ?>
                                        <?php if($data->is_adv_filter_active) { ?>
                                        <span class="rm-advanced-filter-notification rm-position-absolute"></span>
                                        <?php } ?>
                                    </div>
                                    <?php if(defined('REGMAGIC_ADDON')) {
                                        if(!$data->is_filter_active) { ?>
                                    <div class="alignleft actions rm-mb-2">
                                        <input type="submit" id="doaction" class="button action" value="Mark All read" onclick="jQuery.rm_do_action('rm_submission_manager_form', 'rm_submission_mark_all_read')">
                                    </div>
                                    <?php }
                                        if($data->is_filter_active) { ?>
                                    <div class="alignleft actions">
                                        <input type="submit" id="rm-save-filter-action" class="button action" value="Save Filters">
                                    </div>
                                    <?php } ?>
                                    <?php } ?>
                                    <?php if($data->filter->pagination->total_pages) { ?>
                                    <div class="tablenav-pages"><span class="displaying-num"><?php echo sprintf(__('%s items', 'custom-registration-form-builder-with-submission-manager'), $data->filter->total_entries['all']); ?></span>
                                        <span class="pagination-links">
                                            <?php if($data->filter->pagination->curr_page == 1) { ?>
                                            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                                            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                                            <?php } else { ?>
                                            <a class="first-page button" href="javascript:void(0)" onclick="rm_load_prev_page('first');"><span class="screen-reader-text">First page</span><span class="" aria-hidden="true">«</span></a>
                                            <a class="prev-page button" href="javascript:void(0)" onclick="rm_load_prev_page('prev');"><span class="screen-reader-text">Previous page</span><span class="" aria-hidden="true">‹</span></a>
                                            <?php } ?>
                                            <span class="paging-input">
                                                <label for="current-page-selector" class="screen-reader-text">Current Page</label>
                                                <input class="current-page" id="current-page-selector" type="text" value="<?php echo esc_attr($data->filter->pagination->curr_page); ?>" size="1" aria-describedby="table-paging">
                                                <span class="tablenav-paging-text"> of <span class="total-pages"><?php echo esc_html($data->filter->pagination->total_pages); ?></span></span>
                                            </span>
                                            <?php if($data->filter->pagination->curr_page >= $data->filter->pagination->total_pages) { ?>
                                            <span class="screen-reader-text">Next page</span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                                            <span class="screen-reader-text">Last page</span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span>
                                            <?php } else { ?>
                                            <a class="next-page button" href="javascript:void(0)" onclick="rm_load_next_page('next');"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
                                            <a class="last-page button" href="javascript:void(0)" onclick="rm_load_next_page('last');"><span class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a></span>
                                            <?php } ?>
                                    </div>
                                    <?php } ?>
                                </div>
                                    <?php if($data->is_filter_active) { ?>
                                    <div class="rmnotice-container rm-box-w-100 rm-di-block">
                                        <div class="rmnotice rm-my-3">
                                            <?php echo sprintf(__('These are filtered results. To view all submissions, <a href="%s">reset filter</a>', 'custom-registration-form-builder-with-submission-manager'),admin_url().'admin.php?page=rm_submission_manage'); ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                <table class="rm_inbox_table wp-list-table widefat striped table-view-list rm-position-relative">
                                    <thead>
                                        <tr>
                                            <td scope="col" scope="col" class="manage-column check-column">
                                                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                                                <input class="rm_checkbox_group" onclick="rm_submission_selection_toggle(this)" type="checkbox" name="rm_select_all"></td>
                                            <th scope="col" scope="col" class="manage-column column-primary">Submission</th>
                                            <?php if(empty($data->form_id)) { ?>
                                            <th scope="col" scope="col" class="manage-column"><span class="title">Form</span> <span class="sorting-indicator"></span></th>
                                            <?php } else {
                                                $field_names = array();
                                                $i = $j = 0;
                                                for($i = 0; $j < 4; $i++):
                                                    if ((isset($data->fields[$i]->field_type) && !in_array($data->fields[$i]->field_type, RM_Utilities::submission_manager_excluded_fields())) || !isset($data->fields[$i]->field_type))
                                                    {
                                                        $label = isset($data->fields[$i]->field_label) ? $data->fields[$i]->field_label : null; ?>
                                                        <th><?php echo esc_html($label); ?></th>
                                                        <?php
                                                        $field_names[$j] = isset($data->fields[$i]->field_id) ? $data->fields[$i]->field_id : null;
                                                        $j++;
                                                    }
                                                endfor;
                                            } ?>
                                            <th scope="col" scope="col" class="manage-column sorted <?php echo esc_attr(strtolower($data->filter->filters['sort_order'])); ?>"><a href="javascript:void(0)" onclick="rm_toggle_sort_order();"><span class="title">Received On</span> <span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span></a></th>
                                            <th scope="col" scope="col" class="manage-column rm-text-center"><span class="material-icons"> attach_file </span></th>
                                            <th scope="col" scope="col" class="manage-column rm-text-center"><span class="material-icons"> note </span></th>
                                            <th scope="col" scope="col" class="manage-column rm-text-center"><span class="material-icons"> email </span></th>
                                            <th scope="col" scope="col" class="manage-column rm-text-center">Status</th>
                                            <th scope="col" scope="col" class="manage-column rm-text-center">Payment</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        if (is_array($data->submissions) || is_object($data->submissions)) {
                                            foreach ($data->submissions as $submission) {
                                                $sub_model = new RM_Submissions();
                                                $sub_model->load_from_db($submission->submission_id);
                                                $sub_data = $sub_model->get_data();
                                                $form = new RM_Forms();
                                                $form->load_from_db($submission->form_id);
                                                $form_options = $form->get_form_options();
                                                $form_options->ordered_form_pages = empty($form_options->ordered_form_pages) ? array(0 => 0) : $form_options->ordered_form_pages;
                                                $form_options->form_pages = empty($form_options->form_pages) ? array(0 => 'Page 1') : $form_options->form_pages;
                                                $form_fields = RM_DBManager::get_rows_fields_by_form_id($submission->form_id);
                                                $attachs = 0;
                                                $notes = RM_DBManager::get('NOTES', array('submission_id' => $submission->submission_id), array('%d'), 'results', 0, 99999, '*', null, true);
                                                $notes_nt = 0;
                                                $notes_ms = 0;
                                                $cstatuses = RM_DBManager::get_custom_statuses($submission->submission_id,$submission->form_id);
                                                $payment_status = $sub_model->get_payment_status();
                                                if(!empty($notes)) {
                                                    foreach($notes as $note) {
                                                        $note_options = maybe_unserialize($note->note_options);
                                                        if(!isset($note_options->type) || $note_options->type == 'note' || $note_options->type == 'notification')
                                                            $notes_nt++;
                                                        else
                                                            $notes_ms++;
                                                    }
                                                }
                                                foreach($sub_data as $sdata) {
                                                    if($sdata->type == 'File' || $sdata->type == 'Image') {
                                                        $attachs++;
                                                    }
                                                }
                                        ?>
                                        <tr <?php if($sub_model->is_blocked_ip($sub_model->get_submission_ip()) || $sub_model->is_blocked_email($submission->user_email)) echo "class='rm-user-blocked'"; ?>>
                                            <th scope="row" class="check-column"><input class="rm_checkbox_group rm_sub_checkbox"  type="checkbox" name="rm_selected[]" value="<?php echo esc_attr($submission->submission_id); ?>"></th>
                                            <td class="title column-title has-row-actions column-primary"><strong><a class="row-title" target="_blank" href="<?php echo "?page=rm_submission_view&rm_submission_id=".esc_attr($submission->submission_id); ?>" <?php if($sub_model->is_blocked_ip($sub_model->get_submission_ip()) || $sub_model->is_blocked_email($submission->user_email)) echo "class='rm-text-danger'"; ?>><?php echo esc_html($submission->user_email); ?></a>
                                            <?php if(defined('REGMAGIC_ADDON') && $sub_model->is_read == 0) { echo " — "; ?><span class="post-state">Unread</span><span class="post-state"></span><?php } ?>
                                            </strong>
                                                <div class="row-actions">
                                                    <span class="rm-single-view"><span aria-label="Submission View"><a target="_blank" href="<?php echo "?page=rm_submission_view&rm_submission_id=".esc_attr($submission->submission_id); ?>"><?php echo __('View','custom-registration-form-builder-with-submission-manager') ?></span> </a>| </span>
                                                    <span class="rm-form-quick-view inline"><button type="button" class="button-link editinline rm-quick-view-button" aria-label="Quick View" aria-expanded="false" data-submission-id="rm-submission-quick-view-<?php echo esc_attr($submission->submission_id); ?>">Quick View</button> | </span>
                                                    <?php if(defined('REGMAGIC_ADDON')) { ?>
                                                    <?php if($sub_model->is_blocked_ip($sub_model->get_submission_ip())) { ?>
                                                    <span class="rm-form-unblock-ip inline">
                                                        <button type="button" class="button-link editinline" aria-label="Unblock IP" aria-expanded="false" onclick="rm_unblock_ip('<?php echo esc_attr($sub_model->get_submission_ip()); ?>');">Unblock IP</button> | </span>
                                                    <?php } else { ?>
                                                    <span class="rm-form-block-ip inline">
                                                        <button type="button" class="button-link editinline" aria-label="Block IP" aria-expanded="false" onclick="rm_block_ip('<?php echo esc_attr($sub_model->get_submission_ip()); ?>');">Block IP</button> | </span>
                                                    <?php } ?>
                                                    <?php if($sub_model->is_blocked_email($submission->user_email)) { ?>
                                                    <span class="rm-form-unblock-email inline">
                                                    <button type="button" class="button-link editinline" aria-label="Unblock Email" aria-expanded="false" onclick="rm_unblock_email('<?php echo esc_attr($submission->user_email); ?>');">Unblock&nbsp;Email</button> | </span>
                                                    <?php } else { ?>
                                                    <span class="rm-form-block-email inline">
                                                    <button type="button" class="button-link editinline" aria-label="Block Email" aria-expanded="false" onclick="rm_block_email('<?php echo esc_attr($submission->user_email); ?>');">Block&nbsp;Email</button> | </span>
                                                    <?php } ?>
                                                    <span class="rm-form-submission-pdf inline">
                                                        <a target="_blank" href="<?php echo admin_url('admin-ajax.php?rm_submission_id='.$submission->submission_id.'&action=rm_print_pdf&rm_sec_nonce='.wp_create_nonce('rm_ajax_secure')); ?>">
                                                        <button type="button" class="button-link editinline" aria-label="Generate Form Submission into PDF" aria-expanded="false">PDF</button>
                                                        </a> | </span>
                                                    <?php if($sub_model->is_read == 1) { ?>
                                                        <span class="rm-form-submission-unread inline"><button type="button" class="button-link editinline" aria-label="Mark Submission Unread" aria-expanded="false" onclick="rm_mark_as_unread([<?php echo esc_attr($submission->submission_id); ?>],'unread');">Unread</button> | </span>
                                                    <?php } else { ?>
                                                        <span class="rm-form-submission-read inline"><button type="button" class="button-link editinline" aria-label="Submission Read" aria-expanded="false" onclick="rm_mark_as_unread([<?php echo esc_attr($submission->submission_id); ?>],'read');">Read</button> | </span>
                                                    <?php }
                                                    } ?>
                                                    <span class="rm-form-submission-delete trash"><a href="javascript:void(0)" class="submitdelete" aria-label="Delete Submission" onclick="rm_remove_submissions([<?php echo esc_attr($submission->submission_id); ?>]);">Delete</a> </span>
                                                </div>
                                            </td>
                                            <?php if(empty($data->form_id)) { ?>
                                            <td>
                                                <a href="<?php echo admin_url('admin.php?page=rm_submission_manage&rm_form_id='.$submission->form_id); ?>"><?php echo esc_html($form->get_form_name()); ?></a>
                                            </td>
                                            <?php } else {
                                                for ($i = 0; $i < 4; $i++):
                                                    $value = null;
                                                    $type=null;

                                                    if (is_array($sub_data) || is_object($sub_data))
                                                        foreach ($sub_data as $key => $s_data)
                                                            if ($key == $field_names[$i]) {
                                                                $type =  isset($s_data->type)?$s_data->type:'';
                                                                $meta =  isset($s_data->meta)?$s_data->meta:'';
                                                                if($type=='Checkbox' || $type == 'Select' || $type == 'Radio')
                                                                    $value = RM_Utilities::get_lable_for_option($key, $s_data->value);
                                                                else
                                                                    $value = $s_data->value;
                                                            }
                                            ?>
                                            <td>
                                            <?php if(is_array($value))
                                                $value = implode(', ', $value);
                                                $additional_fields = apply_filters('rm_additional_fields', array());
                                                if(in_array($type, $additional_fields)){
                                                    echo esc_html(do_action('rm_additional_fields_data',$type, $value));
                                                } elseif($type=='Rating') {
                                                    $r_sub = array('value' => $value,
                                                        'readonly' => 1,
                                                        'star_width' => 16,
                                                        'max_stars' => 5,
                                                        'star_face' => 'star',
                                                        'star_color' => 'FBC326'
                                                    );
                                                    if(isset($meta) && is_object($meta)) {
                                                        if(isset($meta->max_stars))
                                                            $r_sub['max_stars'] = $meta->max_stars;
                                                        if(isset($meta->star_face))
                                                            $r_sub['star_face'] = $meta->star_face;
                                                        if(isset($meta->star_color))
                                                            $r_sub['star_color'] = $meta->star_color;
                                                    }
                                                    $rf = new Element_Rating("", "", $r_sub);
                                                    $rf->render();
                                                } else {
                                                    if(function_exists('mb_strimwidth'))
                                                        echo esc_html(mb_strimwidth((string)$value, 0, 70, "..."));
                                                    else
                                                        echo esc_html($value);
                                                } ?>
                                            </td>
                                            <?php endfor; ?>
                                            <?php } ?>
                                            <td><?php echo esc_html(RM_Utilities::localize_time($submission->submitted_on)); ?></td>
                                            <td class='rm-text-center'><?php echo esc_html($attachs); ?><a href="javascript:void(0)" class="rm-download-attachment"><span class="material-icons">download</span></a></td>
                                            <td class='rm-text-center'><?php echo esc_html($notes_nt); ?> </td>
                                            <td class='rm-text-center'><?php echo esc_html($notes_ms); ?></td>
                                          
                                            <td class='rm-text-center'>
                                                <?php if(!empty($cstatuses)) { 
                                                    foreach($cstatuses as $cstatus) { ?>
                                                        <div class="rm-has-cs-inbox" style="background-color: #<?php echo esc_attr($form_options->custom_status[$cstatus->status_index]['color']); ?>">
                                                            <div class="rm-has-cs-tooltip"><?php echo esc_html($form_options->custom_status[$cstatus->status_index]['label']); ?></div>
                                                        </div><?php 
                                                    } 
                                                } else { ?>
                                                <span aria-hidden="true">—</span>
                                                <?php }?>
                                             
                                            </td>
                                            <td class='rm-text-center'>
                                            <?php if($payment_status=='canceled' || $payment_status=='Canceled' || $payment_status==strtolower(__( 'Canceled', 'custom-registration-form-builder-with-submission-manager' ))) { ?>
                                                <span class="rm-submission-status-badge rm-status-canceled">
                                                    <?php _e( 'Canceled', 'custom-registration-form-builder-with-submission-manager' ); ?>
                                                </span>
                                            <?php } elseif($payment_status=='refunded' || $payment_status=='Refunded' || $payment_status==strtolower(__( 'Refunded', 'custom-registration-form-builder-with-submission-manager' ))) { ?>
                                                <span class="rm-submission-status-badge rm-status-refunded">
                                                    <?php _e( 'Refunded', 'custom-registration-form-builder-with-submission-manager' ); ?> 
                                                </span>
                                            <?php } elseif($payment_status == 'pending' || $payment_status == 'Pending' || $payment_status==strtolower(__( 'Pending', 'custom-registration-form-builder-with-submission-manager' ))) { ?>
                                                <span class="rm-submission-status-badge rm-status-pending">
                                                    <?php _e( 'Pending', 'custom-registration-form-builder-with-submission-manager' ); ?>
                                                </span>
                                            <?php } elseif(in_array($payment_status,array('completed','Completed','succeeded','Succeeded',strtolower(__( 'Completed', 'custom-registration-form-builder-with-submission-manager' ))))) { ?>
                                                <span class="rm-submission-status-badge rm-status-confirmed">
                                                    <?php _e( 'Completed', 'custom-registration-form-builder-with-submission-manager' ); ?></span>
                                                </span>
                                            <?php  
                                            } else{
                                                echo '<span aria-hidden="true">—</span>';
                                            } ?>
                                            </td>
                                        </tr>
                                    
                                            <div id="rm-submission-quick-view-<?php echo esc_attr($submission->submission_id); ?>" class="rm-submission-quick-view rm-box-modal-view" style="display:none">
                                                <div class="rm-box-modal-overlay rm-modal-overlay-fade-in" data-submission-id="rm-submission-quick-view-<?php echo esc_attr($submission->submission_id); ?>"></div>
                                                    <div class="popup-content rm-box-modal-wrap rm-modal-lg rm-modal-out">
                                                        
                                                        <div class="rm-quick-view-modal-body rm-modal-body">
                                                            <div class="rm-box-modal-titlebar rm-bg-white rm-border  rm-box-w-100 rm-p-3 rm-position-relative">
                                                                            <a href="javascript:void(0)" class="rm-modal-close close-popup" data-submission-id="rm-submission-quick-view-<?php echo esc_attr($submission->submission_id); ?>" ><span class="material-icons"> close </span></a>
                                                                            <div class="rm-d-flex rm-align-items-center">
                                                                                <div class="rm-user-profile rm-mr-3">
                                                                                    <?php echo get_avatar($submission->user_email, 250); ?>
                                                                                </div>
                                                                                <div class="rm-user-details rm-ml-2">
                                                                                    <div class="rm-user-form-name rm-fs-5 rm-lh-sm rm-fw-bold rm-text-dark rm-pb-1"><?php echo esc_html($form->get_form_name()); ?></div>
                                                                                    <div class="rm-user-form-meta-info rm-lh-sm rm-fw-bold rm-text-small rm-pb-1"><?php echo esc_html($submission->user_email); ?> <span class="rm-fw-bold"><?php if(email_exists($submission->user_email)) { echo "<a class='rm-text-muted rm-text-small' href='".admin_url()."admin.php?page=rm_user_view&user_id=".get_user_by('email',$submission->user_email)->ID."' target='_blank'>".sprintf(__('User %s','custom-registration-form-builder-with-submission-manager'),"#".get_user_by('email',$submission->user_email)->ID)."</a>"; } else { _e('Unregistered User', 'custom-registration-form-builder-with-submission-manager'); } ?></span></div>
                                                                                    <div class="rm-user-form-meta-info rm-lh-sm rm-text-small rm-pb-1"><?php echo esc_html(RM_Utilities::localize_time($submission->submitted_on)); ?> <span class="rm-ml-1 rm-text-muted">#<?php echo esc_html($submission->submission_id); ?> </span> <span class="rm-ml-1 rm-text-muted"><?php echo esc_html($sub_model->get_submission_ip()); ?></span> <span class="rm-ml-1 rm-text-muted"><?php echo esc_html($sub_model->get_submission_browser()); ?></span></div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="rm-user-status-wrap rm-d-flex rm-mt-2">
                                                                                <?php if(!empty($cstatuses)) { foreach($cstatuses as $cstatus) { ?>
                                                                                <div class="rm-has-user-status-inbox rm-px-2 rm-py-1 rm-mr-2 rm-rounded-1 rm-text-white" style="background-color: #<?php echo esc_attr($form_options->custom_status[$cstatus->status_index]['color']); ?>">
                                                                                    <span class="rm-has-cs-tooltip"><?php echo esc_html($form_options->custom_status[$cstatus->status_index]['label']); ?></span>
                                                                                </div>
                                                                                <?php } } ?>
                                                                            </div>
                                                                        </div> 
                                                            <div class="rm-modal-content-wrap rm-box-wrap" style="background-color: #f3f6f9;">
                                                                <div class="rm-box-row rm-pt-3">
                                                                    <?php if(defined('REGMAGIC_ADDON') && count($form_options->ordered_form_pages) > 1) { ?>
                                                                    <div class="rm-content-area rm-box-col-9">
                                                                    <?php } else { ?>
                                                                    <div class="rm-content-area rm-box-col-12">
                                                                    <?php } 
                                                                    $rm_custom_form_rand_num = '';
                                                                    ?>
                                                                        <div class="rm-page-wrapper">
                                                                            <?php foreach($form_options->ordered_form_pages as $form_page) { 
                                                                                $rm_custom_page_id = $submission->submission_id.'-page-'.$form_page; ?>
                                                                            <div class="rm-form-page rm-bg-white rm-border rm-p-3 rm-mr-2 rm-mb-3 rm-rounded-1" id="<?php echo esc_html( $rm_custom_page_id ); ?>">
                                                                                <h1 class="rm-text-muted rm-text-small rm-text-center rm-fw-bold rm-text-uppercase"><?php echo esc_html($form_options->form_pages[$form_page]); ?></h1>
                                                                                <?php foreach($form_fields as $form_field) { 
                                                                                    if(isset($sub_data[absint($form_field->field_id)]) && $form_field->page_no == $form_page+1) { ?>
                                                                                    <div class="rm-box-row rm-mb-3">
                                                                                    <div class="rm-box-col-12">
                                                                                        <div class="rm-submission-lable rm-fw-bold "><?php echo esc_html($sub_data[absint($form_field->field_id)]->label); ?></div>
                                                                                        <div class="rm-submission-value">
                                                                                        <?php
                                                                                        $sub_val = $sub_data[absint($form_field->field_id)]->value;
                                                                                        //if submitted data is array print it in more than one row.
                                                                                        if (is_array($sub_val)) {
                                                                                            //If submitted data is a file.
                                                                                            if (isset($sub_val['rm_field_type']) && $sub_val['rm_field_type'] == 'File') {
                                                                                                unset($sub_val['rm_field_type']);
                                                                                                foreach ($sub_val as $sub) {
                                                                                                    $att_path = get_attached_file($sub);
                                                                                                    $att_url = wp_get_attachment_url($sub);
                                                                                                    ?>
                                                                                                    <div class="rm-submission-attachment-wrap rm-di-flex rm-align-items-center rm-mt-2">
                                                                                                    <div class="rm-submission-attachment rm-border rm-di-flex rm-align-items-center rm-rounded-1 rm-p-2 rm-mr-2">
                                                                                                        <?php echo wp_get_attachment_link($sub, 'thumbnail', false, true, false); ?>
                                                                                                        <div class="rm-submission-attachment-field"><?php echo esc_html(basename($att_path)); ?></div>
                                                                                                    </div>
                                                                                                    <div class="rm-submission-attachment-field rm-lh-0"><a href="<?php echo esc_url($att_url); ?>" download><span class="material-icons rm-text-primary"> file_download </span></a></div>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                }
                                                                                            } elseif (isset($sub_val['rm_field_type']) && $sub_val['rm_field_type'] == 'Address') {
                                                                                                //$sub = $sub_val['original'] . '<br/>';
                                                                                                $sub = '';
                                                                                                if (count($sub_val) === 8) {
                                                                                                    $sub .= '<b>'.__('Street Address','custom-registration-form-builder-with-submission-manager').'</b> : ' . $sub_val['st_number'] . ', ' . $sub_val['st_route'] . '<br/>';
                                                                                                    $sub .= '<b>'.__('City','custom-registration-form-builder-with-submission-manager').'</b> : ' . $sub_val['city'] . '<br/>';
                                                                                                    $sub .= '<b>'.__('State','custom-registration-form-builder-with-submission-manager').'</b> : ' . $sub_val['state'] . '<br/>';
                                                                                                    $sub .= '<b>'.__('Zip Code','custom-registration-form-builder-with-submission-manager').'</b> : ' . $sub_val['zip'] . '<br/>';
                                                                                                    $sub .= '<b>'.__('Country','custom-registration-form-builder-with-submission-manager').'</b> : ' . $sub_val['country'];
                                                                                                }
                                                                                                echo wp_kses_post((string)$sub);
                                                                                            }  elseif ($sub_data[absint($form_field->field_id)]->type == 'Time') {                                  
                                                                                                //echo esc_html($sub_data['time']).", ".__("Timezone",'custom-registration-form-builder-with-submission-manager').": ".esc_html($sub_val['timezone']);
                                                                                                echo esc_html(date('h:i a', strtotime($sub_val['time'])));
                                                                                            } elseif ($sub_data[absint($form_field->field_id)]->type == 'Checkbox') {   
                                                                                                echo esc_html(implode(', ',RM_Utilities::get_lable_for_option($form_field->field_id, $sub_val)));
                                                                                            }
                                                                                            //If submitted data is a Star Rating.
                                                                                            else {
                                                                                                $field_data = implode(', ', $sub_val);
                                                                                                if($sub_data[absint($form_field->field_id)]->type=="Repeatable"):
                                                                                                    $field_data = '<pre>'.implode('<hr> ', $sub_val).'</pre>';
                                                                                                endif;
                                                                                                echo wp_kses_post((string)$field_data);
                                                                                            }
                                                                                        } else {
                                                                                            $additional_fields = apply_filters('rm_additional_fields', array());
                                                                                            if(in_array($sub_data[absint($form_field->field_id)]->type, $additional_fields)){
                                                                                                echo do_action('rm_additional_fields_data',$sub_data[absint($form_field->field_id)]->type, $sub_data);
                                                                                            }
                                                                                            elseif($sub_data[absint($form_field->field_id)]->type == 'Rating')
                                                                                            {
                                                                                                echo '<div class="rateit" id="rateit5" data-rateit-min="0" data-rateit-max="5" data-rateit-value="'.esc_attr($sub_data[absint($form_field->field_id)]->value).'" data-rateit-ispreset="true" data-rateit-readonly="true"></div>';
                                                                                            }
                                                                                            elseif ($sub_data[absint($form_field->field_id)]->type == 'Radio' || $sub_data[absint($form_field->field_id)]->type == 'Select') {   
                                                                                                echo esc_html(RM_Utilities::get_lable_for_option($form_field->field_id, $sub_val));
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                            echo wp_kses_post((string)nl2br($sub_val));
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <?php }
                                                                                } ?>
                                                                            </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php if(defined('REGMAGIC_ADDON') && count($form_options->ordered_form_pages) > 1) { ?>
                                                                    <div class="right-menu-area rm-box-col-3">
                                                                        <div class="rm-form-nav-menu rm-bg-white rm-overflow-hidden rm-border rm-rounded-1">
                                                                        <?php foreach($form_options->ordered_form_pages as $form_page) { 
                                                                            $rm_custom_page_id = $submission->submission_id.'-page-'.$form_page; ?>
                                                                        <a href="#<?php echo esc_html( $rm_custom_page_id ); ?>" data-page="<?php echo esc_html( $rm_custom_page_id ); ?>" class="rm-text-truncate"><?php echo esc_html($form_options->form_pages[$form_page]); ?></a>
                                                                        <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                    <?php } elseif($data->has_submissions && empty($data->filter->pagination->total_pages)) { ?>
                                                <tr><td colspan="12"><?php _e('No submissions match your filter criteria', 'custom-registration-form-builder-with-submission-manager'); ?></td></tr>
                                    <?php } elseif(!$data->has_submissions) { ?>
                                        <tr>
                                            <td colspan="12"> <?php _e('There are no submissions to display yet', 'custom-registration-form-builder-with-submission-manager'); ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td scope="col"  class="manage-column check-column">
                                                <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                                                <input class="rm_checkbox_group" onclick="rm_submission_selection_toggle(this)" type="checkbox" name="rm_select_all"></td>
                                            <th scope="col" class="manage-column column-primary">Submission</th>
                                            <?php if(empty($data->form_id)) { ?>
                                            <th scope="col" scope="col" class="manage-column"><span class="title">Form</span> <span class="sorting-indicator"></span></th>
                                            <?php } else {
                                                $field_names = array();
                                                $i = $j = 0;
                                                for($i = 0; $j < 4; $i++):
                                                    if ((isset($data->fields[$i]->field_type) && !in_array($data->fields[$i]->field_type, RM_Utilities::submission_manager_excluded_fields())) || !isset($data->fields[$i]->field_type))
                                                    {
                                                        $label = isset($data->fields[$i]->field_label) ? $data->fields[$i]->field_label : null; ?>
                                                        <th><?php echo esc_html($label); ?></th>
                                                        <?php
                                                        $field_names[$j] = isset($data->fields[$i]->field_id) ? $data->fields[$i]->field_id : null;
                                                        $j++;
                                                    }
                                                endfor;
                                            } ?>
                                            <th scope="col" class="manage-column sorted <?php echo esc_attr(strtolower($data->filter->filters['sort_order'])); ?>"><a href="javascript:void(0)" onclick="rm_toggle_sort_order();"><span class="title">Received On</span> <span class="sorting-indicators"><span class="sorting-indicator asc" aria-hidden="true"></span><span class="sorting-indicator desc" aria-hidden="true"></span></span></a></th>
                                            <th scope="col" class="manage-column rm-text-center"><span class="material-icons"> attach_file </span></th>
                                            <th scope="col" class="manage-column rm-text-center"><span class="material-icons"> note </span></th>
                                            <th scope="col" class="manage-column rm-text-center"><span class="material-icons"> email </span></th>
                                            <th scope="col" class="manage-column rm-text-center">Status</th>
                                            <th scope="col" class="manage-column rm-text-center">Payment</th>
                                        </tr> 
                                    </tfoot>
                                </table>
                                    
                               <?php if($data->has_submissions) { ?>     
                            
                            <div class="tablenav bottom">
                                   <div class="alignleft actions bulkactions">
                                        <label for="bulk-action-selector-bottom" class="screen-reader-text"><?php esc_html_e('Select bulk action', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                        <select id="bulk-action-selector-bottom">
                                            <option value="-1"><?php esc_html_e('Bulk actions', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                            <option value="delete"><?php esc_html_e('Delete', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                            <?php if(defined('REGMAGIC_ADDON')) { ?>
                                            <option value="mark-read"><?php esc_html_e('Mark Read', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                            <option value="mark-unread"><?php esc_html_e('Mark Unread', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                            <?php if(!empty($data->form_id)) { ?>
                                            <option value="export"><?php esc_html_e('Export', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                            <?php } } ?>
                                        </select>
                                        <input type="submit" id="rm-bulk-action-bottom" class="button action" value="Apply" onclick="rm_apply_bulk_action(this);">
                                    </div>
                                    <?php if($data->filter->pagination->total_pages) { ?>
                                    <div class="tablenav-pages"><span class="displaying-num"><?php echo sprintf(__('%s items', 'custom-registration-form-builder-with-submission-manager'), $data->filter->total_entries['all']); ?></span>
                                        <span class="pagination-links">
                                            <?php if($data->filter->pagination->curr_page == 1) { ?>
                                            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                                            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                                            <?php } else { ?>
                                            <a class="first-page button" href="javascript:void(0)" onclick="rm_load_prev_page('first');"><span class="screen-reader-text">First page</span><span class="" aria-hidden="true">«</span></a>
                                            <a class="prev-page button" href="javascript:void(0)" onclick="rm_load_prev_page('prev');"><span class="screen-reader-text">Previous page</span><span class="" aria-hidden="true">‹</span></a>
                                            <?php } ?>
                                            <span class="paging-input">
                                                <label for="current-page-selector" class="screen-reader-text">Current Page</label>
                                                <input class="current-page" id="current-page-selector" type="text" value="<?php echo esc_attr($data->filter->pagination->curr_page); ?>" size="1" aria-describedby="table-paging">
                                                <span class="tablenav-paging-text"> of <span class="total-pages"><?php echo esc_attr($data->filter->pagination->total_pages); ?></span></span>
                                            </span>
                                            <?php if($data->filter->pagination->curr_page >= $data->filter->pagination->total_pages) { ?>
                                            <span class="screen-reader-text">Next page</span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                                            <span class="screen-reader-text">Last page</span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span>
                                            <?php } else { ?>
                                            <a class="next-page button" href="javascript:void(0)" onclick="rm_load_next_page('next');"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
                                            <a class="last-page button" href="javascript:void(0)" onclick="rm_load_next_page('last');"><span class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a></span>
                                            <?php } ?>
                                    </div>
                                    <?php } ?>
                            </div>
                             <?php } else { ?>
                                    <div></div>
                                     <?php } ?>
                                </div>
                                    
                                    
                                    
                                <?php } ?>
        
        <!---Left Slide Pannel-->
        
        <div id="rm-slide-pannel" class="rm-slide-pannel rm-border rm-shadow">
            <div class="rm-slide-pannel-close rm-text-end rm-position-absolute"><span class="material-icons"> close </span></div>
            <div class="rm-slide-pannel-overlay" style="display:none"></div>
            <ul class="rm-nav-pills rm-mx-0 rm-p-0 rm-mb-3 rm-advanced-filter-tabs rm-border-bottom" role="tablist">
                <li class="rm-tab-item rm-m-0 rm-position-relative" id="rm-advanced-filter-tab-action" role="presentation">
                    <a href="javascript:void(0)" data-tag="rm-advanced-filter-tab" class="rm-tab-link rm-text-dark rm-text-decoration-none rm-rounded-0 rm-tab-active">Advanced Filters</a>
                </li>
                <?php if(defined('REGMAGIC_ADDON')) { ?>
                <li class="rm-tab-item rm-m-0 rm-position-relative" id="rm-saved-filter-tab-action" role="presentation">
                    <a href="javascript:void(0)" data-tag="rm-saved-filter-tab" class="rm-tab-link rm-text-dark rm-text-decoration-none rm-rounded-0">Saved Filters</a>
                </li>
                <?php } ?>
            </ul>
            <div id="rm-tab-container" class="rm-box-w-100">
                <div class="rm-tab-content" id="rm-advanced-filter-tab" role="tabpanel" >
                    <?php if(!defined('REGMAGIC_ADDON') && empty($data->filter->form_id)) { ?>
                    <div class="rm-my-3 rm-alert rm-alert-warning rm-bg-opacity-50">
                        <?php _e('Please filter submissions by a form first. Currently, the form filter is set to ', 'custom-registration-form-builder-with-submission-manager'); ?>
                        <i><?php _e('All Forms.', 'custom-registration-form-builder-with-submission-manager'); ?></i>
                    </div>
                    <?php } ?>
                    <form id="rm-subs-filter-form" method="get" action="<?php echo admin_url('admin.php'); ?>" onsubmit="rm_apply_filters();">
                        <input type="hidden" name="page" value="rm_submission_manage">
                        <input type="hidden" name="rm_form_id" value="<?php echo esc_attr($data->filter->form_id); ?>">
                        <input type="hidden" name="rm_interval" value="<?php echo esc_attr($data->filter->filters['rm_interval']); ?>" <?php if($data->filter->filters['rm_interval'] == 'all') echo 'disabled'; ?>>
                        <input type="hidden" name="rm_fromdate" value="<?php echo esc_attr($data->filter->filters['rm_fromdate']); ?>" <?php if(empty($data->filter->filters['rm_fromdate'])) echo 'disabled'; ?>>
                        <input type="hidden" name="rm_dateupto" value="<?php echo esc_attr($data->filter->filters['rm_dateupto']); ?>" <?php if(empty($data->filter->filters['rm_dateupto'])) echo 'disabled'; ?>>
                        <?php if (!empty($data->fields)) { ?>
                            <div class="rm-box-row rm-mb-3 rmp-items-end">
                                <div class="rm-box-col-12">
                                    <label class="rm-form-label"><?php _e('Field Search', 'custom-registration-form-builder-with-submission-manager'); ?> <span class="rm-ml-2" style="visibility:hidden"  ><a href="javascript:void(0)">Clear</a></span></label>
                                    <select name="rm_field_to_search" class="rm-form-control rm-rounded-bottom-left-0 rm-rounded-bottom-right-0">
                                    <?php
                                    foreach ($data->fields as $f) {
                                        if (!in_array($f->field_type, RM_Utilities::submission_manager_excluded_fields())) {
                                            ?>
                                            <option value="<?php echo esc_attr($f->field_id); ?>" <?php if ($data->filter->filters['rm_field_to_search'] === $f->field_id) echo esc_attr("selected"); ?>><?php echo esc_html($f->field_label); ?></option>
                                        <?php }
                                    }
                                    ?>
                                     </select>
                                      <div style="margin-top: -1px;">
                                        <input type="text" name="rm_value_to_search" class="rm-form-control rm-rounded-top-left-0 rm-rounded-top-right-0" value="<?php echo esc_attr($data->filter->filters['rm_value_to_search']); ?>">
                                      </div>
                                       <input type="hidden" name="rm_search_initiated" value="yes">
                                </div>
                            </div>
                            <div class="rm-box-row rm-mb-3 rm-items-end">
                                <div class="rm-box-col-12">
                              <?php $form = new RM_Forms();
                               $form->load_from_db($data->filter->form_id);
                               $form_options = $form->get_form_options(); ?>
                                <label class="rm-form-label"><?php _e('Status Filter', 'custom-registration-form-builder-with-submission-manager'); ?><span class="rm-ml-2" style="visibility:hidden"><a href="javascript:void(0)">Clear</a></span></label>
                                <select id="rm_custom_status_filter" class="rm-form-control" size="5" multiple="multiple">
                                    <option value=""><?php _e('Select Custom Status', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                                    <?php
                                    if (!empty($form_options->custom_status)) {
                                        $search_cs = array();
                                        if (isset($_GET['custom_status_ind']) && $_GET['custom_status_ind'] != '') {
                                            $search_cs = explode(',', sanitize_text_field((string)$_GET['custom_status_ind']));
                                        }
                                        foreach ($form_options->custom_status as $key => $value) {
                                            if (in_array($key, $search_cs)) {
                                                ?>
                                                <option value="<?php echo esc_attr($key); ?>" selected><?php echo esc_html($value['label']); ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value['label']); ?></option>
                                                    <?php
                                                    }
                                                }
                                            }
                                            ?>
                                </select>
                                <input type="hidden" name="custom_status_ind" value="">
                                </div>
                            </div>
                                <?php } ?>
                                  <?php if (defined('REGMAGIC_ADDON')) { ?>
                            <div class="rm-box-row rm-mb-3 rm-items-end">
                                <div class="rm-box-col-12">
                                    <label class="rm-form-label"><?php _e('Properties Filter', 'custom-registration-form-builder-with-submission-manager'); ?>
                                        <span class="rm-ml-2"  style="visibility:hidden"><a href="javascript:void(0)">Clear</a></span>
                                    </label>
                                <div>
                                    <ul class="rm-text-start">
                                        <li class="rm-form-check">
                                            <input class="rm-form-check-input rm_tag_filter_checkbox" type="checkbox" id="rm-filter-has-note" value="Has Note"<?php if (in_array('Has Note', $tag_array)) echo esc_attr(" checked"); ?>>
                                            <label class="rm-form-check-label" for="rm-filter-has-note"><?php _e('Has Note', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                        </li>
                                        <li class="rm-form-check">
                                            <input class="rm-form-check-input rm_tag_filter_checkbox" type="checkbox" id="rm-filter-user-blocked" value="Blocked"<?php if (in_array('Blocked', $tag_array)) echo esc_attr(" checked"); ?>>
                                            <label class="rm-form-check-label" for="rm-filter-user-blocked"><?php _e('User Blocked', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                        </li>

                                        <li class="rm-form-check">
                                            <input class="rm-form-check-input rm_tag_filter_checkbox" type="checkbox" class="rm_tag_filter_checkbox" id="rm-filter-attachment" value="Attachment"<?php if (in_array('Attachment', $tag_array)) echo esc_attr(" checked"); ?>>
                                            <label class="rm-form-check-label" for="rm-filter-attachment"><?php _e('Attachment', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                        </li>
                                        <li class="rm-form-check">
                                            <input class="rm-form-check-input rm_tag_filter_checkbox" type="checkbox" id="rm-filter-no-attachment" value="No Attachment"<?php if (in_array('No Attachment', $tag_array)) echo esc_attr(" checked"); ?>>
                                            <label class="rm-form-check-label" for="rm-filter-no-attachments"><?php _e('No Attachment', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                        </li>

                                        <li class="rm-form-check">
                                            <input class="rm-form-check-input rm_tag_filter_checkbox" type="checkbox" id="rm-filter-payment-received" value="Payment Received"<?php if (in_array('Payment Received', $tag_array)) echo esc_attr(" checked"); ?>>
                                            <label class="rm-form-check-label" for="rm-filter-payment-received"><?php _e('Payment Received', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                        </li>

                                        <li class="rm-form-check">
                                            <input class="rm-form-check-input rm_tag_filter_checkbox" type="checkbox" id="rm-filter-payment-pending" value="Payment Pending"<?php if (in_array('Payment Pending', $tag_array)) echo esc_attr(" checked"); ?>>
                                            <label class="rm-form-check-label" for="rm-filter-payment-pending"><?php _e('Payment Pending', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                        </li>

                                        <li class="rm-form-check">
                                            <input class="rm-form-check-input rm_tag_filter_checkbox" type="checkbox" id="rm-offline-payment-pending" value="Pending Offline Payments"<?php if (in_array('Pending Offline Payments', $tag_array)) echo esc_attr(" checked"); ?>>
                                            <label class="rm-form-check-label" for="rm-offline-payment-pending"><?php _e('Offline Pending Payment', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                        </li>

                                        <li class="rm-form-check" style="display:none">
                                            <input class="rm-form-check-input rm_tag_filter_checkbox" type="checkbox" id="rm-filter-read" value="Read"<?php if (in_array('Read', $tag_array)) echo esc_attr(" checked"); ?>>
                                            <label class="rm-form-check-label" for="rm-filter-read"><?php _e('Read', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                        </li>

                                        <li class="rm-form-check" style="display:none">
                                            <input class="rm-form-check-input rm_tag_filter_checkbox" type="checkbox" id="rm-filter-unread" value="Unread"<?php if (in_array('Unread', $tag_array)) echo esc_attr(" checked"); ?>>
                                            <label class="rm-form-check-label" for="rm-filter-unread"><?php _e('Unread', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                                        </li>
                                    </ul>
                                    <input type="hidden" name="filter_tags" value="">
                                </div>
                                </div>
                            </div>
                              <?php } ?>
                              <input type="hidden" name="sort_order" value="<?php echo esc_attr($data->filter->filters['sort_order']); ?>">
                              <input type="hidden" name="rm_reqpage" value="1">
                            <div class="rm-filter-btn rm-box-row rm-mt-3 rm-text-end">
                            <div class="rm-box-col-12">
                                
                            <input type="submit" class="button button-primary rm-box-w-100 rm-mb-2 rm-py-1 <?php if(!defined('REGMAGIC_ADDON') && empty($data->filter->form_id)) { ?> rm-btn-disabled <?php } ?>" value="<?php _e('Filter', 'custom-registration-form-builder-with-submission-manager'); ?>">
                            <button type="button" class="button rm-box-w-100 rm-py-1" onclick="rm_clear_all_filters();"><?php _e('Clear', 'custom-registration-form-builder-with-submission-manager'); ?></a></button>   
                         
                            </div>
                        </div>
                    </form>
                    <?php if(!defined('REGMAGIC_ADDON') && !empty($data->filter->form_id)) { ?>
                    <a href="<?php echo admin_url('admin.php?page=rm_support_premium_page'); ?>" class="rm-box-premium-inbox-pannel rm-text-center rm-position-absolute rm-py-2 rm-bg-white rm-box-w-100">
                        <div class="rm-box-premium"><span class="material-icons"> workspace_premium </span> Premium</div>
                        <div class="rm-box-premium-text rm-text-small rm-button-text-color">Get additional property filter by upgrading to Premium.</div>
                    </a>
                    <?php } ?>
                </div>
                <div class="rm-tab-content rm-item-hide" id="rm-saved-filter-tab" role="tabpanel">

                    <div class="rm-box-row rm-mb-3 rm-items-end">
                        <div class="rm-box-col-12">
                            <label class="rm-form-label"><?php _e('Save search as filter ', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                            <input type="text" name="rm_save_filter" class="rm-form-control" value="" placeholder="Save Filter">
                                <?php
                                $criteria = explode("?", (string)$_SERVER['REQUEST_URI']);
                                $criteria[1] = explode("&rm_reqpage", (string)$criteria[1]);
                                $gopts = new RM_Options;
                                $custom_filters = $gopts->get_value_of('rm_submission_filters');
                                $custom_filters = maybe_unserialize($custom_filters);
                                ?>
                        <div class="rm-saved-filter-action rm-mt-2 rm-text-end"><button class="button" onclick="add_filter('<?php echo esc_attr($criteria[1][0]); ?>');">Save </button></div>
                        </div>
                    </div>
                        <?php if (!empty($custom_filters)) { ?>
                        <div class="rm-box-row rm-mb-3 rm-items-end">
                             <div class="rm-box-col-12">
                           
                            <input type="text" name="rm_search_filters" class="rm-form-control" value="" placeholder="Search Filters">
                             </div>
                        </div>
                        <div class="rm-saved-filter-wrap ">
                            <ul class="rm-mx-0 rm-px-0" id="rm-saved-filters-sorting">
                                <?php foreach ($custom_filters as $filter_name => $filter_url) { ?>
                                <li class="rm-cursor-move rm-mx-0 rm-px-0 rm-apply-filter-row" data-filter-name="<?php echo esc_attr($filter_name); ?>" data-filter-url="<?php echo esc_attr($filter_url); ?>">
                                    <div class="rm-apply-filter-move rm-box-row rm-align-items-center">
                                        <div class="rm-box-col-6">
                                            <div class="rm-saved-filter-name rm-di-flex rm-align-items-center rm-box-w-100">
                                                <span class="rm-lh-0 ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="15px" viewBox="0 0 24 24" width="15px" fill="#2271B1"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M11 18c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm-2-8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm6 4c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
                                                </span><span class="rm-saved-filter rm-text-truncate"><?php echo esc_html($filter_name); ?></span>
                                            </div>
                                        </div>
                                        <div class="rm-box-col-6">
                                            <div class="rm-apply-filter-action" style="display: none;">
                                                <div class="rm-apply-filter-btn rm-d-flex rm-justify-content-between">
                                                <button class="button rm-mr-2" onclick="apply_filter('<?php echo admin_url() . 'admin.php?' . $filter_url; ?>')">Apply </button>
                                                <button class="button rm-btn-delete" onclick="delete_filter('<?php echo esc_attr($filter_url); ?>')">Delete </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php } ?>
                </div>
            </div>
        </div>
            <form method="post" action="" name="rm_submission_manage" id="rm_submission_manager_form">
                <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
                <input type="hidden" name="rm_form_id" value="<?php echo esc_attr($data->filter->form_id); ?>">
            </form> 
        </div>
</div>
<pre class='rm-pre-wrapper-for-script-tags'>
    <script>
        jQuery(document).ready( function($) {
            $('#rm-advanced-filters, .rm-pannel-close-bt, .rm-slide-pannel-close,.rm-slide-pannel-overlay').click( function() {
                $('#rm-slide-pannel').toggleClass('rm-pannel-show');
            });
            
            jQuery('#rm-save-filter-action').click( function() {
                jQuery('#rm-slide-pannel').toggleClass('rm-pannel-show');
                jQuery('#rm-advanced-filter-tab').addClass('rm-item-hide').removeClass('rm-item-show');
                 jQuery('#rm-saved-filter-tab').removeClass('rm-item-hide').addClass('rm-item-show');
                
                jQuery('#rm-advanced-filter-tab-action a').removeClass('rm-tab-active');
                jQuery('#rm-saved-filter-tab-action a').addClass('rm-tab-active');
                
            });
                          
            // Tabmenu 
            $( document ).on( 'click', '.rm-tab-item a', function(){
                $( '.rm-tab-item a' ).removeClass( 'rm-tab-active' );
                $(this).addClass('rm-tab-active');
                var tagid = $(this).data('tag');
                $( '.rm-tab-content' ).removeClass( 'rm-item-show' ).addClass( 'rm-item-hide' );
                $( '#'+tagid ).addClass( 'rm-item-show' ).removeClass( 'rm-item-hide' );
            });

            $('input#current-page-selector').keypress(function (e) {
                var key = e.which;
                if(key == 13)  // the enter key code
                {
                    if($(this).val() == '' || isNaN($(this).val()) || $(this).val() < 1) {
                        $('input[name=rm_reqpage]').val('1');
                    } else {
                        $('input[name=rm_reqpage]').val($(this).val());
                    }
                    $('input[name=rm_search_initiated]').prop("disabled", true);
                    $('form#rm-subs-filter-form').submit();
                }
            });
            
            $("input[name=rm_search_filters]").keyup(function(){
                let val = $.trim($(this).val());
                if(val == '') {
                    $('span.rm-saved-filter').each(function() {
                        $(this).parents('li').show();
                        $('span.rm-lh-0').show();
                    });
                } else {
                    $('span.rm-saved-filter').each(function() {
                        if($(this).text().includes(val)) {
                            $(this).parents('li').show();
                        } else {
                            $(this).parents('li').hide();
                        }
                        $('span.rm-lh-0').hide();
                    });
                }
            });

            $('#rm-saved-filters-sorting').sortable({
                axis: 'y',
                opacity: 0.7,
                handle: '.rm-lh-0',
                update: function(event, ui) {
                    var filterNames = [];
                    var filterURLs = [];
                    $('li.rm-apply-filter-row').each(function() {
                        filterNames.push($(this).data('filter-name'));
                        filterURLs.push($(this).data('filter-url'));
                    });
                    var data = {
                        'action': 'rm_sort_saved_filters',
                        'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                        'names': filterNames,
                        'urls': filterURLs,
                    };

                    $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                        if(response.success)
                            console.log('Sorting complete');
                    });
                }
            });
        });

        function rm_remove_submissions(sub_ids) {
            var answer = confirm("<?php _e("This action cannot be reversed. Are you sure you want to proceed ahead?", 'custom-registration-form-builder-with-submission-manager'); ?>");
            if(answer) {
                var data = {
                    'action': 'rm_delete_submissions',
                    'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                    'sub_ids': sub_ids,
                };
                jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                    if(response.success)
                        location.reload();
                });
            }
        }

        function rm_export_submissions(sub_ids) {
            window.location.href = '<?php echo admin_url("admin-ajax.php?form_id={$data->filter->form_id}&sub_ids="); ?>'+sub_ids.toString()+'&action=rm_export_submissions&rm_sec_nonce=<?php echo wp_create_nonce('rm_ajax_secure'); ?>';
        }

        function set_inbox_entry_depth(element) {
            var selectedVal = jQuery(element).find('option').filter(':selected').val();
            var postData = {'action' : 'rm_set_inbox_entry_depth', 'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>', 'value' : selectedVal};
            jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', postData, function(response) {
                if(response.success) {
                    location.reload();
                }
            });
        }

        function rm_block_ip(ip) {
            var postData = {'action' : 'rm_block_ip', 'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>', 'user_ip' : ip};
            jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', postData, function(response) {
                if(response.success) {
                    location.reload();
                }
            });
        }

        function rm_unblock_ip(ip) {
            var postData = {'action' : 'rm_unblock_ip', 'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>', 'user_ip' : ip};
            jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', postData, function(response) {
                if(response.success) {
                    location.reload();
                }
            });
        }

        function rm_block_email(email) {
            var postData = {'action' : 'rm_block_email', 'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>', 'user_email' : email};
            jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', postData, function(response) {
                if(response.success) {
                    location.reload();
                }
            });
        }

        function rm_unblock_email(email) {
            var postData = {'action' : 'rm_unblock_email', 'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>', 'user_email' : email};
            jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', postData, function(response) {
                if(response.success) {
                    location.reload();
                }
            });
        }

        function rm_apply_bulk_action(obj) {
            if(obj.id == 'rm-bulk-action-top')
                var selectedAct = jQuery('select#bulk-action-selector-top').children("option:selected").val();
            else
                var selectedAct = jQuery('select#bulk-action-selector-bottom').children("option:selected").val();
            selectedSubs = [];
            jQuery('input.rm_sub_checkbox:checked').each(function() {
                selectedSubs.push(jQuery(this).val());
            });
            if(selectedAct == 'delete') {
                rm_remove_submissions(selectedSubs);
            }
            if(selectedAct == 'mark-read') {
                rm_mark_as_unread(selectedSubs,'read');
            }
            if(selectedAct == 'mark-unread') {
                rm_mark_as_unread(selectedSubs,'unread');
            }
            if(selectedAct == 'export') {
                rm_export_submissions(selectedSubs);
            }
        }

        function rm_apply_date_filter() {
            //jQuery('input[name=rm_interval]').val(jQuery('select#rm_submissions-all-date option:selected').val());
            if(jQuery('input#rm_submission_date_range').val() == '' || jQuery('input[name=rm_interval]').val() != 'custom') {
                jQuery('input[name=rm_interval]').val('all');
                jQuery('input[name=rm_interval]').prop('disabled', true);
                jQuery('input[name=rm_fromdate]').prop('disabled', true);
                jQuery('input[name=rm_dateupto]').prop('disabled', true);
                jQuery('form#rm-subs-filter-form').submit();
            } else {
                jQuery('input[name=rm_interval]').prop('disabled', false);
                jQuery('input[name=rm_fromdate]').prop('disabled', false);
                jQuery('input[name=rm_dateupto]').prop('disabled', false);
                jQuery('form#rm-subs-filter-form').submit();
            }
        }

        function rm_apply_form_filter() {
            var selectedForm = jQuery('select#rm_submissions-all-forms').children("option:selected").val();
            //Disable send button to prevent multiple send requests.
            jQuery("#rm-apply-form-filter").prop('disabled', true);
            jQuery("#rm-apply-form-filter").prop('disabled', true);
            jQuery("#rm-apply-form-filter i").show();
            var data = {
                'action': 'rm_save_default_inbox_form',
                'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                'form': selectedForm
            };
            jQuery.post(ajaxurl, data, function(response) {
                if(response.success) {
                    if(selectedForm == 'all') {
                        window.location.href = '<?php echo admin_url('admin.php?page=rm_submission_manage'); ?>';
                    } else {
                        window.location.href = '<?php echo admin_url('admin.php?page=rm_submission_manage&rm_form_id='); ?>'+selectedForm;
                    }
                }
                
                jQuery("#rm-apply-form-filter i").hide();
            });
        }

        function rm_toggle_sort_order() {
            <?php if($data->filter->filters['sort_order'] == 'ASC') { ?>
            jQuery('input[name=sort_order]').val('DESC');
            <?php } else { ?>
            jQuery('input[name=sort_order]').val('ASC');
            <?php } ?>
            jQuery('form#rm-subs-filter-form').submit();

        }

        function rm_load_read_subs() {
            jQuery('input#rm-filter-unread').prop("checked", false);
            jQuery('input#rm-filter-read').prop("checked", true);
            jQuery('form#rm-subs-filter-form').submit();
        }

        function rm_load_unread_subs() {
            jQuery('input#rm-filter-read').prop("checked", false);
            jQuery('input#rm-filter-unread').prop("checked", true);
            jQuery('form#rm-subs-filter-form').submit();
        }

        function rm_load_all_subs() {
            jQuery('input#rm-filter-read').prop("checked", false);
            jQuery('input#rm-filter-unread').prop("checked", false);
            jQuery('form#rm-subs-filter-form').submit();
        }

        function rm_load_next_page(next) {
            if(next == 'next') {
                jQuery('input[name=rm_reqpage]').val('<?php echo esc_html($data->filter->pagination->curr_page + 1); ?>');
            }
            if(next == 'last') {
                jQuery('input[name=rm_reqpage]').val('<?php echo esc_html($data->filter->pagination->total_pages); ?>');
            }
            jQuery('input[name=rm_search_initiated]').prop("disabled", true);
            jQuery('form#rm-subs-filter-form').submit();
        }

        function rm_load_prev_page(prev) {
            if(prev == 'prev') {
                jQuery('input[name=rm_reqpage]').val('<?php echo esc_html($data->filter->pagination->curr_page - 1); ?>');
            }
            if(prev == 'first') {
                jQuery('input[name=rm_reqpage]').val('1');
            }
            jQuery('input[name=rm_search_initiated]').prop("disabled", true);
            jQuery('form#rm-subs-filter-form').submit();
        }

        function add_filter(url) {
            var name =jQuery("input[name=rm_save_filter]").val();
            if(name == '')
                alert('Please provide filter name');
            else {
                var data = {
                    'action': 'rm_add_filter',
                    'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                    'name': name,
                    'url': url,
                };
                
                jQuery.post(ajaxurl, data, function(response) {
                    if(response == 'NAME_EXIST')
                        alert("<?php _e('Filter already exists! Please try with a different name.','custom-registration-form-builder-with-submission-manager') ?>");
                    else if(response == 'URL_EXIST')
                        alert("<?php _e('This Search is already saved, please try with different search criteria.','custom-registration-form-builder-with-submission-manager') ?>");
                    else
                        location.reload();
                });
            }
        }

        function apply_filter(url) {
            if(url != '' && url != null) {
                window.location =  url;
            }
        }

        function delete_filter(url) {
            if(url != ''  && url != null) {
                var data = {
                    'action': 'rm_delete_filter',
                    'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                    'url': url
                };
                jQuery.post(ajaxurl, data, function(response) {
                    alert(response);
                    location.reload();
                });
            }
        }

        function rm_mark_as_unread(sub_ids, view) {
            var data = {
                'action': 'rm_mark_submission_unread',
                'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                'sub_ids': sub_ids,
                'view': view,
            };
            jQuery.post(ajaxurl, data, function(response) {
                if(response == 'success')
                    location.reload();
            });
        }

        function rm_clear_all_filters() {
            window.location.href = '<?php echo admin_url('admin.php?page=rm_submission_manage'); ?>';
        }

        function rm_apply_filters() {
            //Check Form ID
            if(jQuery.trim(jQuery('input[name=rm_form_id]').val()) == '') {
                jQuery('input[name=rm_form_id]').prop('disabled', true);
            }
            //Check Field Value
            if(jQuery.trim(jQuery('input[name=rm_value_to_search]').val()) == '') {
                jQuery('select[name=rm_field_to_search]').prop('disabled', true);
                jQuery('input[name=rm_value_to_search]').prop('disabled', true);
            }
            //Custom Status
            status_val = '';
            var status_arr = jQuery('#rm_custom_status_filter').val();
            if(status_arr && status_arr.length > 0) {
                status_val = status_arr.join(',');
                jQuery('input[name=custom_status_ind]').val(status_val);
            } else {
                jQuery('input[name=custom_status_ind]').prop('disabled', true);
            }
            //Tags
            tags_val = '';
            var tags_arr = [];
            jQuery("input.rm_tag_filter_checkbox:checked").each(function() {
                console.log(jQuery(this).val());
                tags_arr.push(jQuery(this).val());
            });
            if(tags_arr && tags_arr.length > 0) {
                tags_val = tags_arr.join(',');
                jQuery('input[name=filter_tags]').val(tags_val);
            } else {
                jQuery('input[name=filter_tags]').prop('disabled', true);
            }
        }
        
         jQuery(document).ready(function () {
         jQuery('.rm-quick-view-button').click(function () {
         var modalId = jQuery(this).data('submission-id');
         jQuery('#' + modalId).toggle();
         jQuery('#' + modalId).addClass("rm-form-popup-show").removeClass("rm-form-popup-hide");
        jQuery('#' + modalId).children('.rm-box-modal-overlay').removeClass('rm-modal-overlay-fade-in').addClass('rm-modal-overlay-fade-out');
        jQuery('#' + modalId).children('.rm-box-modal-wrap').removeClass('rm-modal-out').addClass('rm-modal-in');
        jQuery('#' + modalId).children('.rm-box-modal-wrap').removeClass('rm-modal-out').addClass('rm-modal-in');
         
         });
         
        jQuery('.rm-box-modal-overlay, .rm-modal-close').click(function () {
         var modalId = jQuery(this).data('submission-id');
         jQuery('#' + modalId).toggle();
        jQuery('#' + modalId).children('.rm-box-modal-overlay').removeClass('rm-modal-overlay-fade-out').addClass('rm-modal-overlay-fade-in');
        jQuery('#' + modalId).children('.rm-box-modal-wrap').removeClass('rm-modal-in').addClass('rm-modal-out');
         jQuery('#' + modalId).addClass("rm-form-popup-hide").removeClass("rm-form-popup-show");
         
          });
         

        });
        
        //Date Functions
        
           jQuery(function() {
            var start = moment(<?php if(!empty($start_date)){echo "'".esc_html($start_date)."'";}?>);
            var end = moment(<?php if(!empty($end_date)){echo "'".esc_html($end_date)."'";}?>);
            function cb(start, end) {
                jQuery('#rm_submission_date_range').val(start.format('MM/DD/YYYY') + '-' + end.format('MM/DD/YYYY'));
                jQuery('input[name=rm_interval]').val('custom');
                jQuery('input[name=rm_fromdate]').val(start.format('YYYY-MM-DD'));
                jQuery('input[name=rm_dateupto]').val(end.format('YYYY-MM-DD'));
            }

            jQuery('#rm_submission_date_range').daterangepicker({
                startDate: start,
                endDate: end,
                maxDate: new Date(),
                ranges: {
                   'Today': [moment(), moment()],
                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                   'This Month': [moment().startOf('month'), moment().endOf('month')],
                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: { cancelLabel: 'Clear' }  
            }, cb);
            jQuery('#rm_submission_date_range').on('cancel.daterangepicker', function(ev, picker) {
                //do something, like clearing an input
                jQuery('#rm_submission_date_range').val('');
                jQuery('.rm-user-clear-date').show();
              });
            jQuery('#rm_submission_date_range').on('apply.daterangepicker', function(ev, picker) {
                jQuery(this).val(picker.startDate.format('MM/DD/YYYY') + '-' + picker.endDate.format('MM/DD/YYYY'));
                jQuery('input[name=rm_interval]').val('custom');
                jQuery('input[name=rm_fromdate]').val(picker.startDate.format('YYYY-MM-DD'));
                jQuery('input[name=rm_dateupto]').val(picker.endDate.format('YYYY-MM-DD'));
                jQuery('.rm-user-clear-date').show();
            });  
            cb(start, end);
            <?php if(empty($rm_interval)){?>
                jQuery('#rm_submission_date_range').val('');
            <?php } ?>
        });
        
        
    </script>
</pre>
    
    <style>
        a.rm-download-attachment span{
            font-size: 18px;
            line-height: 28px;
            margin-left: 4px;
        }
        
        a.rm-download-attachment{
            display: none;
        }
    </style>
<?php //} ?>