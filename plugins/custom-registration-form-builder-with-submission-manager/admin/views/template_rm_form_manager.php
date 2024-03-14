<?php
if (!defined('WPINC')) {
    die('Closed');
}
if($data->old_view == false || $data->old_view == 0) {
    include_once(RM_ADMIN_DIR . 'views/template_rm_form_manager_old.php');
} else {
$file = $_SERVER["SCRIPT_NAME"];
$file_break = Explode('/', (string)$file);
$pfile = $file_break[count($file_break) - 1];
/**
 * @internal Template File [Form Manager]
 *
 * This file renders the form manager page of the plugin which shows all the forms
 * to manage delete edit or manage
 */

global $rm_env_requirements;
global $regmagic_errors;

$url_params = $data->url_params;
if($data->descending == true) {
    $url_params['rm_descending'] = 1;
} else {
    $url_params['rm_descending'] = 0;
}
//wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );

 //Check errors
 RM_Utilities::fatal_errors();
 if(is_array($regmagic_errors)){
     foreach($regmagic_errors as $err)
    {
       //Display only non - fatal errors
       if($err->should_cont)
           echo '<div class="shortcode_notification ext_na_error_notice"><p class="rm-notice-para">'.wp_kses_post($err->msg).'</p></div>';
    }
 }
 
 ?> 
   <!-- 
    <div class="rm-upgrade-notice-info is-dismissible" style="display:none"><?php esc_html_e('For more features, consider upgrading to ', 'custom-registration-form-builder-with-submission-manager'); ?><a href="https://registrationmagic.com/comparison/?utm_source=rm_plugin&utm_medium=landing_strip&utm_campaign=premium_upgrade" target="_blank"><?php esc_html_e('RegistrationMagic Premium', 'custom-registration-form-builder-with-submission-manager'); ?></a>.

         <button class="button-link rm-promo-notice-dismiss">&times; <span class="screen-reader-text"><?php esc_html_e('Dismiss notice', 'custom-registration-form-builder-with-submission-manager'); ?></span></button>
     </div>
    -->

 <div class="rmagic rmagic-wide rmmagic-all-forms rm-custom-list-table">  
     
<div class="wrap">
    
<h1 class="wp-heading-inline"><?php esc_html_e('All Forms', 'custom-registration-form-builder-with-submission-manager'); ?></h1>
<a href="#rm_add_new_form_popup" onclick="CallModalBox(this)" class="page-title-action"><?php esc_html_e('Add New Form', 'custom-registration-form-builder-with-submission-manager');?></a>

        <div class="alignright rm-mb-2 rm-position-relative" >
            <div class="rm-display-items-selector rm-di-flex rm-box-center">
                <span class="rm-white-space"><?php esc_html_e('Results per page', 'custom-registration-form-builder-with-submission-manager'); ?> &rarr;</span>
                <select class="rm-pager-toggle" onchange="set_forms_entry_depth(this);">
                    <option value="10" <?php echo $data->items_per_page == 10 ? 'selected' : ''; ?>><?php esc_html_e('Page 1-10', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                    <option value="20" <?php echo $data->items_per_page == 20 ? 'selected' : ''; ?>><?php esc_html_e('Page 1-20', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                    <option value="30" <?php echo $data->items_per_page == 30 ? 'selected' : ''; ?>><?php esc_html_e('Page 1-30', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                    <option value="40" <?php echo $data->items_per_page == 40 ? 'selected' : ''; ?>><?php esc_html_e('Page 1-40', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                    <option value="50" <?php echo $data->items_per_page == 50 ? 'selected' : ''; ?>><?php esc_html_e('Page 1-50', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                </select>
            </div>
        </div>
<hr class="wp-header-end">
 

<h2 class="screen-reader-text"><?php esc_html_e('Filter pages list', 'custom-registration-form-builder-with-submission-manager'); ?></h2>

 
 <!---Top Filter--->

<ul class="subsubsub">
    <li class="all"><a class="all<?php if(empty($data->form_filter)) echo ' current'; ?>" href="<?php echo esc_url(admin_url("admin.php?page=rm_form_manage&rm_form_search=".(string)$data->search_term)); ?>" aria-current="page"><?php esc_html_e('All', 'custom-registration-form-builder-with-submission-manager'); ?> <span class="count">(<?php echo esc_html($data->total_forms); ?>)</span></a> |</li>
    <li class="active"><a class="<?php if($data->form_filter == 'registration') echo 'current'; ?>" href="<?php echo esc_url(admin_url("admin.php?page=rm_form_manage&rm_form_filter=registration&rm_form_search=".(string)$data->search_term)); ?>"><?php esc_html_e('Creates User Account', 'custom-registration-form-builder-with-submission-manager'); ?> <span class="count">(<?php echo esc_html($data->reg_forms); ?>)</span></a>
    <?php if(defined('REGMAGIC_ADDON')) { ?>
        |</li><li class="pending"><a class="<?php if($data->form_filter == 'multi_page') echo 'current'; ?>" href="<?php echo esc_url(admin_url("admin.php?page=rm_form_manage&rm_form_filter=multi_page&rm_form_search=".(string)$data->search_term)); ?>"><?php esc_html_e('Multi-Page', 'custom-registration-form-builder-with-submission-manager'); ?> <span class="count">(<?php echo esc_html($data->multi_page_forms); ?>)</span></a></li>
    <?php } ?>
</ul>

<!---Top Filter Ends--->
 
<!----Top Filters--->
 <!--
        <div class="alignright rm-mb-2 rm-position-relative" >
            <div class="rm-display-items-selector rm-di-flex rm-box-center rm-position-absolute">
                <span class="rm-white-space"><?php esc_html_e('Results per page', 'custom-registration-form-builder-with-submission-manager'); ?> &rarr;</span>
                <select class="rm-pager-toggle" onchange="set_forms_entry_depth(this);">
                    <option value="10" <?php echo $data->items_per_page == 10 ? 'selected' : ''; ?>><?php esc_html_e('Page 1-10', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                    <option value="20" <?php echo $data->items_per_page == 20 ? 'selected' : ''; ?>><?php esc_html_e('Page 1-20', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                    <option value="30" <?php echo $data->items_per_page == 30 ? 'selected' : ''; ?>><?php esc_html_e('Page 1-30', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                    <option value="40" <?php echo $data->items_per_page == 40 ? 'selected' : ''; ?>><?php esc_html_e('Page 1-40', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                    <option value="50" <?php echo $data->items_per_page == 50 ? 'selected' : ''; ?>><?php esc_html_e('Page 1-50', 'custom-registration-form-builder-with-submission-manager'); ?></option>
                </select>
            </div>
        </div>

-->
<!--Search Box-->

<form action="<?php echo esc_url(admin_url("admin.php")); ?>" method="get">
    <p class="search-box">
    <input type="hidden" name="page" value="rm_form_manage">
	<label class="screen-reader-text" for="post-search-input"><?php esc_html_e('Search Forms:', 'custom-registration-form-builder-with-submission-manager'); ?></label>
	<input type="search" id="post-search-input" name="rm_form_search" value="<?php echo esc_attr((string)$data->search_term); ?>">
    <input type="submit" id="search-submit" class="button rm-search-btn" value="Search Forms">
    </p>
</form>



<!---Search Box Ends-->



<div class="tablenav top">
    <div class="alignleft actions bulkactions">
        <label for="bulk-action-selector-top" class="screen-reader-text"><?php esc_html_e('Select bulk action', 'custom-registration-form-builder-with-submission-manager'); ?></label>
        <select id="bulk-action-selector-top">
            <option value="-1"><?php esc_html_e('Bulk actions', 'custom-registration-form-builder-with-submission-manager'); ?></option>
            <option value="export"><?php esc_html_e('Export', 'custom-registration-form-builder-with-submission-manager'); ?></option>
            <option value="delete"><?php esc_html_e('Delete', 'custom-registration-form-builder-with-submission-manager'); ?></option>
        </select>
        <input type="button" id="rm-form-bulk-action-top" onclick="rm_apply_bulk_action(this)" class="button action" value="<?php esc_html_e('Apply', 'custom-registration-form-builder-with-submission-manager'); ?>">
    </div>
    <div class="alignleft actions">
     <a href="admin.php?page=rm_form_import"  class="button action"><?php echo wp_kses_post((string)RM_UI_Strings::get('LABEL_IMPORT')); ?></a>
    </div>
    <div class="alignleft actions rm-mt-1">
     <a  href="https://registrationmagic.com/create-wordpress-registration-page-starter-guide" target="_blank"  class=" action"><?php esc_html_e('Starter Guide', 'custom-registration-form-builder-with-submission-manager'); ?></a>
    </div>
    <form action="<?php echo esc_url(admin_url("admin.php")); ?>" method="get" id="rm_pagination_input_form">
    <input type="hidden" name="page" value="rm_form_manage">
    <input type="hidden" name="rm_form_filter" value="<?php echo esc_attr($data->url_params["rm_form_filter"]); ?>">
    <input type="hidden" name="rm_sortby" value="<?php echo esc_attr($data->url_params["rm_sortby"]); ?>">
    <input type="hidden" name="rm_descending" value="<?php echo esc_attr($data->url_params["rm_descending"]); ?>">
    <input type="hidden" name="rm_form_search" value="<?php echo esc_attr($data->url_params["rm_form_search"]); ?>">
    <?php if($data->total_pages) { ?>
    <div class="tablenav-pages"><span class="displaying-num"><?php echo wp_kses_post((string)sprintf(esc_html__('%s items', 'custom-registration-form-builder-with-submission-manager'), $data->filtered_count)); ?></span>
        <span class="pagination-links">
            <?php if($data->curr_page == 1) { ?>
            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
            <?php } else { $url_params['rm_reqpage'] = 1; ?>
            <a class="first-page button" href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>"><span class="screen-reader-text"><?php esc_html_e('First page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span class="" aria-hidden="true">«</span></a>
            <?php $url_params['rm_reqpage'] = $data->curr_page - 1; ?>
            <a class="prev-page button" href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>"><span class="screen-reader-text"><?php esc_html_e('Previous page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span class="" aria-hidden="true">‹</span></a>
            <?php } ?>
            <span class="paging-input">
                <label for="current-page-selector" class="screen-reader-text"><?php esc_html_e('Current Page', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                <input class="current-page" id="rm_reqpage_top" name="rm_reqpage" type="text" value="<?php echo esc_attr($data->curr_page); ?>" size="1" aria-describedby="table-paging">
                <span class="tablenav-paging-text"> of <span class="total-pages"><?php echo esc_html($data->total_pages); ?></span></span>
            </span>
            <?php if($data->curr_page >= $data->total_pages) { ?>
            <span class="screen-reader-text"><?php esc_html_e('Next page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
            <span class="screen-reader-text"><?php esc_html_e('Last page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span>
            <?php } else { ?>
            <?php $url_params['rm_reqpage'] = $data->curr_page + 1; ?>
            <a class="next-page button" href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>"><span class="screen-reader-text"><?php esc_html_e('Next page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span aria-hidden="true">›</span></a>
            <?php $url_params['rm_reqpage'] = $data->total_pages; ?>
            <a class="last-page button" href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>"><span class="screen-reader-text"><?php esc_html_e('Last page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span aria-hidden="true">»</span></a></span>
            <?php } ?>
    </div>
    <?php } ?>
</div>

    <table class="rm-all-forms-table wp-list-table widefat striped table-view-list rm-position-relative" id="rm-card-area">
            <thead>
                <tr>
                    <td class="manage-column column-cb check-column">
                        <input class="rm_checkbox_group" id="cb-select-all-1" type="checkbox">
                    </td>
                    <?php if($data->sort_by == 'form_name') {
                        if($data->descending == true) {
                            $url_params['rm_descending'] = 0;
                            ?>
                            <th scope="col" id="rm-form-name" class="manage-column column-name column-primary sorted desc" aria-sort="descending" abbr="Form Name">
                        <?php } else {
                            $url_params['rm_descending'] = 1;
                            ?>
                            <th scope="col" id="rm-form-name" class="manage-column column-name column-primary sorted asc" aria-sort="ascending" abbr="Form Name">
                        <?php }
                    } else {
                        $url_params['rm_descending'] = 1;
                        ?>
                    <th scope="col" id="rm-form-name" class="manage-column column-name column-primary sortable desc" aria-sort="ascending" abbr="Form Name">
                        <?php } $url_params['rm_sortby'] = 'form_name'; $url_params['rm_reqpage'] = 1; ?>
                        <a href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>" class="#"><span> <?php esc_html_e('Form Name', 'custom-registration-form-builder-with-submission-manager'); ?></span>
                            <span class="sorting-indicators">
                                <span class="sorting-indicator asc" aria-hidden="true"></span>
                                <span class="sorting-indicator desc" aria-hidden="true"></span>
                            </span>
                        </a>
                    </th>
                    <th scope="col" id="rm-form-shortcode" class="manage-column column-shortcode"><?php esc_html_e('Shortcode', 'custom-registration-form-builder-with-submission-manager'); ?></th>
                    <?php if($data->sort_by == 'form_submissions') {
                        if($data->descending == true) {
                            $url_params['rm_descending'] = 0;
                            ?>
                            <th scope="col" id="rm-form-submission" class="manage-column column-name column-primary sorted desc rm-text-center" aria-sort="descending" abbr="Submissions">
                        <?php } else {
                            $url_params['rm_descending'] = 1;
                            ?>
                            <th scope="col" id="rm-form-submission" class="manage-column column-name column-primary sorted asc rm-text-center" aria-sort="ascending" abbr="Submissions">
                        <?php }
                    } else {
                        $url_params['rm_descending'] = 1;
                        ?>
                    <th scope="col" id="rm-form-submission" class="manage-column column-name column-primary sortable desc rm-text-center" aria-sort="ascending" abbr="Submissions">
                    <?php } $url_params['rm_sortby'] = 'form_submissions'; $url_params['rm_reqpage'] = 1; ?>
                        <a href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>" class="rm-text-center rm-di-flex"><span> <?php esc_html_e('Submissions', 'custom-registration-form-builder-with-submission-manager'); ?></span>
                            <span class="sorting-indicators">
                                <span class="sorting-indicator asc" aria-hidden="true"></span>
                                <span class="sorting-indicator desc" aria-hidden="true"></span>
                            </span>
                        </a>
                    </th>
                    <th scope="col" id="rm-form-limit" class="manage-column column-limit rm-text-center"><?php esc_html_e('Limit', 'custom-registration-form-builder-with-submission-manager'); ?></th>
                    <th scope="col" id="rm-form-attachments" class="manage-column column-attachments rm-text-center"><?php esc_html_e('Attachments', 'custom-registration-form-builder-with-submission-rm-text-centermanager'); ?></th>
                    <th scope="col" id="rm-form-recent" class="manage-column column-recent"><?php esc_html_e('Recent', 'custom-registration-form-builder-with-submission-manager'); ?></th>
                    <?php if($data->sort_by == 'created_on') {
                        if($data->descending == true) {
                            $url_params['rm_descending'] = 0;
                            ?>
                            <th scope="col" id="rm-form-created-date" class="manage-column column-name column-primary sorted desc" aria-sort="descending" abbr="Created on">
                        <?php } else {
                            $url_params['rm_descending'] = 1;
                            ?>
                            <th scope="col" id="rm-form-created-date" class="manage-column column-name column-primary sorted asc" aria-sort="ascending" abbr="Created on">
                        <?php }
                    } else {
                        $url_params['rm_descending'] = 1;
                        ?>
                    <th scope="col" id="rm-form-created-date" class="manage-column column-name column-primary sortable desc" aria-sort="ascending" abbr="Created on">
                        <?php } $url_params['rm_sortby'] = 'created_on'; $url_params['rm_reqpage'] = 1; ?>
                        <a href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>" class="#"><span> <?php esc_html_e('Created on', 'custom-registration-form-builder-with-submission-manager'); ?></span>
                            <span class="sorting-indicators">
                                <span class="sorting-indicator asc" aria-hidden="true"></span>
                                <span class="sorting-indicator desc" aria-hidden="true"></span>
                            </span>
                        </a>
                    </th>
                    <th scope="col" id="rm-form-badge-icons" class="manage-column column-recent"><?php esc_html_e('     ', 'custom-registration-form-builder-with-submission-manager'); ?></th>
                </tr>
            </thead>
        
        <tbody>
            <?php if(empty($data->search_term) && empty($data->form_filter) && $data->curr_page == 1) { ?>
            <tr>
                <th scope="row" class="check-column"><input  type="checkbox" class="rm_checkbox_group rm-login-checkbox" disabled></th>
                <td class="has-row-actions column-primary" data-colname="Form Name">
                        <div class="rm-di-flex rm-align-items-center" >
                            <span class="material-icons rm-fs-7 rm-mr-1">push_pin</span>
                            <strong>
                                <a href="#">
                                  <?php esc_html_e('Login Form', 'custom-registration-form-builder-with-submission-manager'); ?>
                                </a>
                            </strong>
                            </div>
                    <div class="row-actions">
                        <span class="rm-fields-link"><a href="admin.php?page=rm_login_field_manage" aria-label="<?php esc_html_e('Fields', 'custom-registration-form-builder-with-submission-manager'); ?>"><?php esc_html_e('Fields', 'custom-registration-form-builder-with-submission-manager'); ?></a> | </span>
                        <span class="rm-login-dashboard"><a href="admin.php?page=rm_login_sett_manage" aria-label="<?php esc_html_e('Dashboard', 'custom-registration-form-builder-with-submission-manager'); ?>"> <?php esc_html_e('Dashboard', 'custom-registration-form-builder-with-submission-manager'); ?></a>  </span>
                       <!-- <span class="view"> <?php add_thickbox(); ?><a id="rm_form_preview_action" class="thickbox rm_form_preview_btn rm_fd_link" href="<?php echo esc_url(add_query_arg(array('form_prev' => '1','form_type' => 'login', 'TB_iframe' => 'true', 'width' => '900', 'height' => '600'), get_permalink(get_site_option('rm_option_front_sub_page_id')))); ?>" aria-label="<?php esc_html_e('Preview', 'custom-registration-form-builder-with-submission-manager'); ?>"> <?php esc_html_e('Preview', 'custom-registration-form-builder-with-submission-manager'); ?></a></span>-->
                    </div>
                    <button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e('Show more details', 'custom-registration-form-builder-with-submission-manager'); ?></span></button>
                        
                    </td>
                <td data-colname="<?php esc_html_e('Login Shortcode', 'custom-registration-form-builder-with-submission-manager'); ?>">[RM_Login]</td>
                <td class="rm-text-center">—</td>
                <td class="rm-text-center">—</td>
                <td class="rm-text-center">—</td>
                <td>—</td>
                <td>—</td>
                <td>
                    <div class="rm-form-badges-wrap rm-border-accent-color rm-border-opacity-50 rm-border-primary rm-border rm-rounded-1 rm-di-flex rm-bg-white">
                        <span class="rm-form-badge rm-lh-normal rm-ps-1 rm-p-1 rm-border-accent-color rm-border-right rm-border-primary rm-di-flex rm-align-items-center rm-position-relative rm-tooltips"><a href="<?php echo esc_url(admin_url('admin.php?page=rm_login_analytics')); ?>" class="rm-lh-0 rm-form-badge-link"><span class="material-icons rm-form-badge-icon">leaderboard</span></a><span class="rm-tooltip-wrap" > <span class="rm-tooltip-arrow"></span><?php esc_html_e('Login Analytics', 'custom-registration-form-builder-with-submission-manager'); ?></span></span>
                        <span class="rm-form-badge rm-lh-normal rm-ps-1 rm-p-1 rm-border-accent-color rm-border-right rm-border-primary rm-di-flex rm-align-items-center rm-position-relative rm-tooltips"><a href="<?php echo esc_url(admin_url('admin.php?page=rm_login_val_sec')); ?>" class="rm-lh-0 rm-form-badge-link"><span class="material-icons rm-form-badge-icon">shield</span></a><span class="rm-tooltip-wrap" > <span class="rm-tooltip-arrow"></span><?php esc_html_e('Validation & Security', 'custom-registration-form-builder-with-submission-manager'); ?></span></span>
                        <span class="rm-form-badge rm-lh-normal rm-ps-1 rm-p-1 rm-border-accent-color rm-border-right rm-border-primary rm-di-flex rm-align-items-center rm-position-relative rm-tooltips"><a href="<?php echo esc_url(admin_url('admin.php?page=rm_login_recovery')); ?>" class="rm-lh-0 rm-form-badge-link"><span class="material-icons rm-fs-6">key</span></a><span class="rm-tooltip-wrap" > <span class="rm-tooltip-arrow"></span><?php esc_html_e('Password Recovery', 'custom-registration-form-builder-with-submission-manager'); ?></span></span>
                        <span class="rm-form-badge rm-lh-normal rm-ps-1 rm-p-1 rm-border-accent-color rm-border-right-0 rm-border-primary rm-di-flex rm-align-items-center rm-position-relative rm-tooltips"><a href="<?php echo esc_url(admin_url('admin.php?page=rm_login_sett_redirections')); ?>" class="rm-lh-0 rm-form-badge-link"><span class="material-icons rm-fs-6">airline_stops</span></a><span class="rm-tooltip-wrap" > <span class="rm-tooltip-arrow"></span><?php esc_html_e('Redirections', 'custom-registration-form-builder-with-submission-manager'); ?></span></span>
                    </div> 
                </td>
            </tr>
            <?php } ?>
        <?php
        $last_form_id= 0;
        if ((is_array($data->data) || is_object($data->data)) && !empty($data->data)) {
            foreach ($data->data as $index=>$entry)
            {
                if(!empty($entry->expiry_details) && $entry->expiry_details->state == 'not_expired' && $entry->expiry_details->criteria != 'date')
                   $subcount_display = $entry->expiry_details->remaining_subs;// $subcount_display = $entry->count.'/'.$entry->expiry_details->sub_limit;
                else
                    $subcount_display = null;//$entry->count;
                
                //Check if form is one of the sample forms.
                $ex_form_card_class = '';
                $sample_data = get_site_option('rm_option_inserted_sample_data', null);
                if(isset($sample_data->forms) && is_array($sample_data->forms)):
                    foreach($sample_data->forms as $sample_form):
                        if($entry->form_id == $sample_form->form_id):
                            $ex_form_card_class = ($sample_form->form_type == RM_REG_FORM)? 'rm-sample-reg-form-card' : 'rm-sample-contact-form-card';                            
                        endif;
                    endforeach;
                endif;                
                    
                if($index==0){
                    $last_form_id= $entry->form_id;
                }
                
                //Check if it is a newly added form
                if($data->new_added_form == $entry->form_id || (isset($_GET['last_form_id']) && $_GET['last_form_id']<$entry->form_id))
                    $ex_form_card_class .= " rm_new_added_form";
                ?>
                <tr id="<?php echo esc_attr($entry->form_id); ?>" class="rmcard rm-card-tour  <?php echo esc_attr($ex_form_card_class); ?>">
                <th scope="row" class="check-column"><input class="rm_checkbox_group rm_form_select_check" type="checkbox" name="rm_selected_forms[]" value="<?php echo esc_attr($entry->form_id); ?>"/></th>
                <td class="title column-title has-row-actions column-primary rm-form-name-col" data-colname="Form Name">
                    <strong>
                        <a href="admin.php?page=rm_field_manage&rm_form_id=<?php echo esc_attr($entry->form_id); ?>" class="row-title rm-row-truncate">
                    <?php echo esc_html($entry->form_name); ?>
                    </a>
                        <span>
                        <?php
                        if(!empty($entry->expiry_details) && $entry->expiry_details->state == 'expired')
                            echo ' — ' . esc_html__('Limit Reached', 'custom-registration-form-builder-with-submission-manager');
                        ?>
                        </span>
                    </strong>
                    <div class="row-actions">
                        <span class="edit"><a href="admin.php?page=rm_field_manage&rm_form_id=<?php echo esc_attr($entry->form_id); ?>"><?php esc_html_e('Fields', 'custom-registration-form-builder-with-submission-manager'); ?></a> | </span>
                        <span class="view"><a href="admin.php?page=rm_form_sett_manage&rm_form_id=<?php echo esc_attr($entry->form_id); ?>" aria-label="Dashboard"> <?php esc_html_e('Dashboard', 'custom-registration-form-builder-with-submission-manager'); ?></a> | </span>
                        <span class="delete"><a class="submitdelete" onclick="delete_form(<?php echo esc_attr($entry->form_id); ?>)" href="javascript:void(0)"><?php esc_html_e('Delete', 'custom-registration-form-builder-with-submission-manager'); ?></a> | </span>
                        <span class="view"><a id="rm_form_preview_action" class="thickbox rm_form_preview_btn rm_fd_link" href="<?php echo esc_url(add_query_arg(array('form_prev' => '1','form_id' => $entry->form_id, 'TB_iframe' => 'true', 'width' => '900', 'height' => '600'), get_permalink(get_site_option('rm_option_front_sub_page_id')))); ?>" aria-label="<?php esc_html_e('Preview', 'custom-registration-form-builder-with-submission-manager'); ?>"> <?php esc_html_e('Preview', 'custom-registration-form-builder-with-submission-manager'); ?></a> | </span>
                        <span class="dublicate"><a class="submitduplicate" onclick="duplicate_form(<?php echo esc_attr($entry->form_id); ?>)" href="javascript:void(0)"><?php esc_html_e('Duplicate', 'custom-registration-form-builder-with-submission-manager'); ?></a> | </span>
                        <span class="resetpassword"><a class="resetpassword" onclick="export_form(<?php echo esc_attr($entry->form_id); ?>)" href="javascript:void(0)"><?php esc_html_e('Export', 'custom-registration-form-builder-with-submission-manager'); ?></a></span>
                    </div>
                    <button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e('Show more details', 'custom-registration-form-builder-with-submission-manager'); ?></span></button>
                </td>
                <td  data-colname="<?php esc_html_e('Shortcode', 'custom-registration-form-builder-with-submission-manager'); ?>"><div class="rm-shortcode-tour" id="rm-shortcode-<?php echo esc_attr($entry->form_id); ?>">[RM_Form id='<?php echo esc_html($entry->form_id); ?>']</div></td>
                
                <td class="rm-text-center" data-colname="<?php esc_html_e('Submissions', 'custom-registration-form-builder-with-submission-manager'); ?>">      
                <?php if($entry->count > 0) { ?>
                    <div class='rm-unread-box rm-text-center'>
                        <a href="?page=rm_submission_manage&rm_form_id=<?php echo esc_attr($entry->form_id); ?>&rm_interval=<?php echo esc_attr($data->submission_type); ?>"><?php echo esc_html($entry->count); ?></a>
                    </div>
                <?php } else { echo "—"; } ?>
                </td>
                <td class="rm-form-expiry-info rm-text-small rm-text-center" data-colname="<?php esc_html_e('Limit', 'custom-registration-form-builder-with-submission-manager'); ?>">
                    <?php
                    if(!empty($entry->expiry_details) && $entry->expiry_details->state == 'expired') {
                        if(($entry->expiry_details->criteria == 'subs' || $entry->expiry_details->criteria == 'both') && absint($entry->expiry_details->sub_limit) > 0) {
                            $remaining_subs = absint($entry->expiry_details->sub_limit) - absint($entry->count);
                            $bar_width = (absint($entry->count)/absint($entry->expiry_details->sub_limit))*100;
                            echo "<div class='rm-form-expiry-info'><div class='rm-text-center'><span class='rm-total-limits-remaining'>".esc_html(absint($entry->expiry_details->sub_limit)-$remaining_subs)."</span>/</span class='rm-total-limits'>".absint($entry->expiry_details->sub_limit)."</span></div><div class='rm-limit-counter'><span style='width:".esc_attr($bar_width)."%' class='limit-counter-progress rm-form-limit-expired'></span></div>";
                        }
                        if(($entry->expiry_details->criteria == 'date' || $entry->expiry_details->criteria == 'both') && $entry->expiry_details->remaining_days == 0) {
                            echo "<div class='rm-form-expiry-info'><div class='rm-form-limit-days-remains rm-text-center rm-fw-bold rm-text-small rm-my-1'>".sprintf(esc_html__('Expired on %s', 'custom-registration-form-builder-with-submission-manager'),gmdate('d M Y', strtotime($entry->expiry_details->date_limit)))."</div></div>";
                        }
                    } else if(!empty($entry->expiry_details) && $entry->expiry_details->state == 'not_expired') {
                        if($entry->expiry_details->criteria == 'date') {
                            if($entry->expiry_details->remaining_days < 26) {
                                echo "<div class='rm-form-expiry-info'><div class='rm-form-limit-days-remains rm-text-center rm-fw-bold rm-text-small rm-my-1'><span></span> ".wp_kses_post((string)sprintf(RM_UI_Strings::get('LABEL_FORM_EXPIRES_IN'),$entry->expiry_details->remaining_days))."</div></div></div>";
                            } else {
                                $exp_date = gmdate('d M Y', strtotime($entry->expiry_details->date_limit));
                                echo "<div class='rm-form-expiry-info'><div class='rm-form-limit-days-remains rm-text-center rm-fw-bold rm-text-small rm-my-1'>".wp_kses_post((string)RM_UI_Strings::get('LABEL_FORM_EXPIRES_ON')." ".$exp_date)."</div></div>";
                            }
                        } else if($entry->expiry_details->criteria == 'subs') {
                            $remaining_subs = absint($entry->expiry_details->sub_limit) - absint($entry->count);
                            if(absint($entry->expiry_details->sub_limit) > 0) {
                                $bar_width = ((absint($entry->expiry_details->sub_limit)-$remaining_subs)/absint($entry->expiry_details->sub_limit))*100;
                            } else {
                                $bar_width = 100;
                            }
                            echo "<div class='rm-form-expiry-info'><div class='rm-text-center'><span class='rm-total-limits-remaining'>".esc_html(absint($entry->expiry_details->sub_limit)-$remaining_subs)."</span>/</span class='rm-total-limits'>".absint($entry->expiry_details->sub_limit)."</span></div><div class='rm-limit-counter'><span style='width:".esc_attr($bar_width)."%' class='limit-counter-progress rm-form-limit-in-progress'></span></div>";
                        } else if($entry->expiry_details->criteria == 'both') {
                            $remaining_subs = absint($entry->expiry_details->sub_limit) - absint($entry->count);
                            if(absint($entry->expiry_details->sub_limit) > 0) {
                                $bar_width = ((absint($entry->expiry_details->sub_limit)-$remaining_subs)/absint($entry->expiry_details->sub_limit))*100;
                            } else {
                                $bar_width = 100;
                            }
                            echo "<div class='rm-form-expiry-info'><div class='rm-text-center'><span class='rm-total-limits-remaining'>".esc_html(absint($entry->expiry_details->sub_limit)-$remaining_subs)."</span>/</span class='rm-total-limits'>".absint($entry->expiry_details->sub_limit)."</span></div><div class='rm-limit-counter'><span style='width:".esc_attr($bar_width)."%' class='limit-counter-progress rm-form-limit-in-progress'></span></div> <div class='rm-form-limit-days-remains rm-text-center rm-fw-bold rm-text-small rm-my-1'>".wp_kses_post((string)sprintf(RM_UI_Strings::get('LABEL_FORM_EXPIRES_IN'),$entry->expiry_details->remaining_days))."</div></div>";
                        } else if($entry->expiry_details->criteria == 'status' && defined('REGMAGIC_ADDON')) {
                            echo "<div class='rm-form-expiry-info'><div class='rm-form-limit-days-remains rm-text-center rm-fw-bold rm-text-small rm-my-1'>".esc_html($entry->expiry_details->status)."</div></div>";
                        }
                    } else { echo "—"; }
                    ?>
                </td>
                <td class="rm-text-center" data-colname="<?php esc_html_e('Attachments', 'custom-registration-form-builder-with-submission-manager'); ?>"><a href="?page=rm_attachment_manage&rm_form_id=<?php echo esc_attr($entry->form_id); ?>"><?php echo empty($entry->form_attachments) ? '0' : esc_html(count($entry->form_attachments)); ?></a></td>
                <td class="rm-last-submission-avatar" data-colname="<?php esc_html_e('Recent', 'custom-registration-form-builder-with-submission-manager'); ?>">
                   <?php
                   if ($entry->count > 0) {
                    foreach ($entry->submissions as $submission) {
                        echo "<div class='rm-tooltips rm-di-flex'>". wp_kses_post((string)$submission->gravatar).'<span class="rm-tooltip-wrap"><span class="rm-tooltip-arrow"></span>'.esc_html(RM_Utilities::localize_time($submission->submitted_on)).'</span></div>';
                        //echo '<div class="rm-box-card-item"><span>sss</span></div>';
                        }
                    } else
                        echo '—';
                    ?>
                </td>
                <td data-colname="<?php esc_html_e('Created on', 'custom-registration-form-builder-with-submission-manager'); ?>"><?php echo wp_kses((string)RM_Utilities::localize_time_short($entry->created_on, null, false, false, true),RM_Utilities::expanded_allowed_tags()); ?></td>
                    <td >
                    <div class="rm-form-badges-wrap rm-border-accent-color rm-border-opacity-50 rm-border-primary rm-border rm-rounded-1 rm-di-flex rm-bg-white">
                        <span class="rm-form-badge rm-lh-normal rm-ps-1 rm-p-1 rm-border-accent-color rm-border-right rm-border-primary rm-di-flex rm-align-items-center rm-position-relative rm-tooltips" aria-label="Form Analytics"><a href="?page=rm_analytics_show_form&rm_form_id=<?php echo esc_attr($entry->form_id); ?>" class="rm-lh-0 rm-form-badge-link"><span class="material-icons rm-form-badge-icon">leaderboard</span></a><span class="rm-tooltip-wrap" > <span class="rm-tooltip-arrow"></span><?php esc_html_e('Form Analytics', 'custom-registration-form-builder-with-submission-manager'); ?></span></span>
                        <span class="rm-form-badge rm-lh-normal rm-ps-1 rm-p-1 rm-border-accent-color rm-border-right rm-border-primary rm-di-flex rm-align-items-center m-position-relative rm-tooltips" aria-label="From Custom Status"><a href="?page=rm_form_manage_cstatus&rm_form_id=<?php echo esc_attr($entry->form_id); ?>" class="rm-lh-0 rm-form-badge-link"><span class="material-icons rm-form-badge-icon">label</span></a><span class="rm-tooltip-wrap" > <span class="rm-tooltip-arrow"></span><?php esc_html_e('Custom Status', 'custom-registration-form-builder-with-submission-manager'); ?></span></span>
                        <span class="rm-form-badge rm-lh-normal rm-ps-1 rm-p-1 rm-border-accent-color rm-border-right rm-border-primary rm-di-flex rm-align-items-center m-position-relative rm-tooltips" aria-label="Automation"><a href="?page=rm_ex_chronos_manage_tasks&rm_form_id=<?php echo esc_attr($entry->form_id); ?>" class="rm-lh-0 rm-form-badge-link"><span class="material-icons rm-fs-6">bolt</span></a><span class="rm-tooltip-wrap" > <span class="rm-tooltip-arrow"></span><?php esc_html_e('Automation', 'custom-registration-form-builder-with-submission-manager'); ?></span></span>
                        <!--<span class="rm-form-badge rm-lh-normal rm-ps-1 rm-p-1 rm-border-accent-color rm-border-right rm-border-primary rm-di-flex rm-align-items-center m-position-relative rm-tooltips" aria-label="Close"><a onclick="duplicate_form(<?php echo esc_attr($entry->form_id); ?>)" href="javascript:void(0)" class="rm-lh-0 rm-form-badge-link"><span class="material-icons rm-fs-6" >content_copy</span></a><span class="rm-tooltip-wrap" > <span class="rm-tooltip-arrow"></span><?php esc_html_e('Duplicate', 'custom-registration-form-builder-with-submission-manager'); ?></span></span>-->
                        <?php if(defined('REGMAGIC_ADDON')) { ?>
                        <span class="rm-form-badge rm-lh-normal rm-ps-1 rm-p-1 rm-inline-block rm-tooltips" aria-label="Close"><a onclick="rm_open_dial(<?php echo esc_attr($entry->form_id); ?>)" href="javascript:void(0)" class="rm-lh-0 rm-form-badge-link"><span class="material-icons rm-fs-6" >html</span></a><span class="rm-tooltip-wrap" > <span class="rm-tooltip-arrow"></span><?php esc_html_e('Form embed code', 'custom-registration-form-builder-with-submission-manager'); ?></span></span>
                        <?php } ?>
                    </div> 
                    </td>
            </tr>

   
                <?php
            }
         } else {
            echo "<tr><td colspan='9'>" . esc_html__('No forms found', 'custom-registration-form-builder-with-submission-manager') . "</h4></td></tr>";
         }
        ?>
            
            
        </tbody>
        <tfoot>
      <tr>
                    <td class="manage-column column-cb check-column">
                        <input class="rm_checkbox_group" id="cb-select-all-1" type="checkbox">
                    </td>
                    <?php if($data->sort_by == 'form_name') {
                        if($data->descending == true) {
                            $url_params['rm_descending'] = 0;
                            ?>
                            <th scope="col" id="rm-form-name" class="manage-column column-name column-primary sorted desc" aria-sort="descending" abbr="Form Name">
                        <?php } else {
                            $url_params['rm_descending'] = 1;
                            ?>
                            <th scope="col" id="rm-form-name" class="manage-column column-name column-primary sorted asc" aria-sort="ascending" abbr="Form Name">
                        <?php }
                    } else {
                        $url_params['rm_descending'] = 1;
                        ?>
                    <th scope="col" id="rm-form-name" class="manage-column column-name column-primary sortable desc" aria-sort="ascending" abbr="Form Name">
                        <?php } $url_params['rm_sortby'] = 'form_name'; $url_params['rm_reqpage'] = 1; ?>
                        <a href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>" class="#"><span> <?php esc_html_e('Form Name', 'custom-registration-form-builder-with-submission-manager'); ?></span>
                            <span class="sorting-indicators">
                                <span class="sorting-indicator asc" aria-hidden="true"></span>
                                <span class="sorting-indicator desc" aria-hidden="true"></span>
                            </span>
                        </a>
                    </th>
                    <th scope="col" id="rm-form-shortcode" class="manage-column column-shortcode"><?php esc_html_e('Shortcode', 'custom-registration-form-builder-with-submission-manager'); ?></th>
                    <?php if($data->sort_by == 'form_submissions') {
                        if($data->descending == true) {
                            $url_params['rm_descending'] = 0;
                            ?>
                            <th scope="col" id="rm-form-submission" class="manage-column column-name column-primary sorted desc rm-text-center" aria-sort="descending" abbr="Submissions">
                        <?php } else {
                            $url_params['rm_descending'] = 1;
                            ?>
                            <th scope="col" id="rm-form-submission" class="manage-column column-name column-primary sorted asc rm-text-center" aria-sort="ascending" abbr="Submissions">
                        <?php }
                    } else {
                        $url_params['rm_descending'] = 1;
                        ?>
                    <th scope="col" id="rm-form-submission" class="manage-column column-name column-primary sortable desc rm-text-center" aria-sort="ascending" abbr="Submissions">
                    <?php } $url_params['rm_sortby'] = 'form_submissions'; $url_params['rm_reqpage'] = 1; ?>
                        <a href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>" class="rm-text-center rm-di-flex"><span> <?php esc_html_e('Submissions', 'custom-registration-form-builder-with-submission-manager'); ?></span>
                            <span class="sorting-indicators">
                                <span class="sorting-indicator asc" aria-hidden="true"></span>
                                <span class="sorting-indicator desc" aria-hidden="true"></span>
                            </span>
                        </a>
                    </th>
                    <th scope="col" id="rm-form-limit" class="manage-column column-limit rm-text-center"><?php esc_html_e('Limit', 'custom-registration-form-builder-with-submission-manager'); ?></th>
                    <th scope="col" id="rm-form-attachments" class="manage-column column-attachments rm-text-center"><?php esc_html_e('Attachments', 'custom-registration-form-builder-with-submission-rm-text-centermanager'); ?></th>
                    <th scope="col" id="rm-form-recent" class="manage-column column-recent"><?php esc_html_e('Recent', 'custom-registration-form-builder-with-submission-manager'); ?></th>
                    <?php if($data->sort_by == 'created_on') {
                        if($data->descending == true) {
                            $url_params['rm_descending'] = 0;
                            ?>
                            <th scope="col" id="rm-form-created-date" class="manage-column column-name column-primary sorted desc" aria-sort="descending" abbr="Created on">
                        <?php } else {
                            $url_params['rm_descending'] = 1;
                            ?>
                            <th scope="col" id="rm-form-created-date" class="manage-column column-name column-primary sorted asc" aria-sort="ascending" abbr="Created on">
                        <?php }
                    } else {
                        $url_params['rm_descending'] = 1;
                        ?>
                    <th scope="col" id="rm-form-created-date" class="manage-column column-name column-primary sortable desc" aria-sort="ascending" abbr="Created on">
                        <?php } $url_params['rm_sortby'] = 'created_on'; $url_params['rm_reqpage'] = 1; ?>
                        <a href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>" class="#"><span> <?php esc_html_e('Created on', 'custom-registration-form-builder-with-submission-manager'); ?></span>
                            <span class="sorting-indicators">
                                <span class="sorting-indicator asc" aria-hidden="true"></span>
                                <span class="sorting-indicator desc" aria-hidden="true"></span>
                            </span>
                        </a>
                    </th>
                    <th scope="col" id="rm-form-badge-icons" class="manage-column column-recent"><?php esc_html_e('     ', 'custom-registration-form-builder-with-submission-manager'); ?></th>
                </tr>

          
        </tfoot>
        
    </table>

    <div class="tablenav bottom">
    <div class="alignleft actions bulkactions">
        <label for="bulk-action-selector-bottom" class="screen-reader-text"><?php esc_html_e('Select bulk action', 'custom-registration-form-builder-with-submission-manager'); ?></label>
        <select id="bulk-action-selector-bottom">
            <option value="-1"><?php esc_html_e('Bulk actions', 'custom-registration-form-builder-with-submission-manager'); ?></option>
            <option value="export"><?php esc_html_e('Export', 'custom-registration-form-builder-with-submission-manager'); ?></option>
            <option value="delete"><?php esc_html_e('Delete', 'custom-registration-form-builder-with-submission-manager'); ?></option>
        </select>
        <input type="button" id="rm-form-bulk-action-bottom" onclick="rm_apply_bulk_action(this)" class="button action" value="Apply">
        <?php if(defined('REGMAGIC_ADDON')) {
            if(version_compare(RM_ADDON_PLUGIN_VERSION, '5.3.0.0') >= 0) { ?>
            <div class="rm-rollback-link rm-mt-2 rm-text-underline"><a href="javascript:void(0)" onclick="rm_forms_roll_back()" class="rm-text-decoration-underline"><?php esc_html_e('Switch to the legacy', 'custom-registration-form-builder-with-submission-manager'); ?> <i> <?php esc_html_e('All Forms', 'custom-registration-form-builder-with-submission-manager'); ?></i> <?php esc_html_e('view', 'custom-registration-form-builder-with-submission-manager'); ?></a></div>
            <?php }
        } else { ?>
            <div class="rm-rollback-link rm-mt-2 rm-text-underline"><a href="javascript:void(0)" onclick="rm_forms_roll_back()" class="rm-text-decoration-underline"><?php esc_html_e('Switch to the legacy', 'custom-registration-form-builder-with-submission-manager'); ?> <i> <?php esc_html_e('All Forms', 'custom-registration-form-builder-with-submission-manager'); ?></i> <?php esc_html_e('view', 'custom-registration-form-builder-with-submission-manager'); ?></a></div>
        <?php } ?>
    </div>
        

    <?php
    if($data->total_pages) {
        $url_params['rm_descending'] = $data->descending ? 1 : 0;
        $url_params['rm_sortby'] = empty($data->sort_by) ? null : $data->sort_by;
        ?>
    <div class="tablenav-pages"><span class="displaying-num"><?php echo wp_kses_post((string)sprintf(esc_html__('%s items', 'custom-registration-form-builder-with-submission-manager'), $data->filtered_count)); ?></span>
        <span class="pagination-links">
            <?php if($data->curr_page == 1) { ?>
            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
            <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
            <?php } else { ?>
            <?php $url_params['rm_reqpage'] = 1; ?>
            <a class="first-page button" href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>"><span class="screen-reader-text"><?php esc_html_e('First page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span class="" aria-hidden="true">«</span></a>
            <?php $url_params['rm_reqpage'] = $data->curr_page - 1; ?>
            <a class="prev-page button" href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>"><span class="screen-reader-text"><?php esc_html_e('Previous page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span class="" aria-hidden="true">‹</span></a>
            <?php } ?>
            <span class="paging-input">
                <label for="current-page-selector" class="screen-reader-text"><?php esc_html_e('Current Page', 'custom-registration-form-builder-with-submission-manager'); ?></label>
                <input class="current-page" type="text" id="rm_reqpage_bottom" name="rm_reqpage" value="<?php echo esc_attr($data->curr_page); ?>" size="1" aria-describedby="table-paging">
                <span class="tablenav-paging-text"> of <span class="total-pages"><?php echo esc_html($data->total_pages); ?></span></span>
            </span>
            <?php if($data->curr_page >= $data->total_pages) { ?>
            <span class="screen-reader-text"><?php esc_html_e('Next page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
            <span class="screen-reader-text"><?php esc_html_e('Last page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span></span>
            <?php } else { ?>
            <?php $url_params['rm_reqpage'] = $data->curr_page + 1; ?>
            <a class="next-page button" href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>"><span class="screen-reader-text"><?php esc_html_e('Next page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span aria-hidden="true">›</span></a>
            <?php $url_params['rm_reqpage'] = $data->total_pages; ?>
            <a class="last-page button" href="<?php echo esc_url(add_query_arg($url_params, admin_url("admin.php"))); ?>"><span class="screen-reader-text"><?php esc_html_e('Last page', 'custom-registration-form-builder-with-submission-manager'); ?></span><span aria-hidden="true">»</span></a></span>
            <?php } ?>
    </div>
    <?php } ?>
    </form>
    </div>

<div class="rm-footer-promotion ">
    <?php $support_url = defined('REGMAGIC_ADDON') ? 'https://registrationmagic.com/help-support/' : 'https://wordpress.org/support/plugin/custom-registration-form-builder-with-submission-manager/'; ?>
    <div class="rm-text-center"> <a href="<?php echo esc_url($support_url); ?>" target="_blank" class="rm-text-decoration-none"> <?php esc_html_e('Create Support Ticket', 'custom-registration-form-builder-with-submission-manager'); ?> <span class="dashicons dashicons-external rm-fs-6"></span></a></div>
</div>

    <?php $new_form_pop_up_style = (isset($_GET['create_new_form'])) ? 'style="display:block"' : 'style="display:none"';?>
    <!-- Add New Form popup -->
    <div id="rm_add_new_form_popup" class="rm-modal-view" <?php echo wp_kses_post((string)$new_form_pop_up_style);?>>
        <div class="rm-modal-overlay rm-form-popup-overlay-fade-in"></div>

        <div class="rm_add_new_form_wrap rm-create-new-from rm-form-popup-out">
            
            <div class="rm-box-row rm-box-center rm-box-secondary-bg">
                    <div class="rm-box-col-12 rm-box-white-bg rm-form-box">                       
                        <div class="rm-modal-titlebar rm-new-form-popup-header">
                                <div class="rm-modal-title">
                                    <?php esc_html_e('Add New Form', 'custom-registration-form-builder-with-submission-manager'); ?>
                                </div>
                            <div class="rm-modal-subtitle">
                                <a href="<?php echo esc_url(admin_url("admin.php?page=rm_form_setup")); ?>" title=" <?php esc_html_e('Form Template', 'custom-registration-form-builder-with-submission-manager'); ?>" class="rm-text-small">
                                <?php esc_html_e('Or choose from a template.', 'custom-registration-form-builder-with-submission-manager'); ?>
                               </a>
                            </div>
                            
                                <span class="rm-modal-close material-icons">close</span>
                            </div>
                        <div class="rm-modal-container">
                            <?php require RM_ADMIN_DIR . 'views/template_rm_new_form_exerpt.php'; ?>
                        </div>
                    </div> 
            </div>

        </div>
    </div>
    <!-- End: Add New Form popup -->
    
    <!-- Form Template popup -->
    
    <div id="rm_add_new_form_popup_template" class="rm-modal-view" <?php echo wp_kses_post((string)$new_form_pop_up_style);?>>
        <div class="rm-modal-overlay rm-form-popup-overlay-fade-in"></div>

        <div class="rm_add_new_form_wrap rm-create-new-from rm-form-popup-out">
            <div class="rm-modal-titlebar rm-form-template-popup-header">
                <div class="rm-modal-title">
                    <img src="<?php echo esc_url(RM_BASE_URL);?>images/rm-logo-icon.svg"><?php esc_html_e('Select Your Registration Form Template','custom-registration-form-builder-with-submission-manager'); ?>
                    <span class="rm-form-template-subtitle" style="display:none;"> <?php esc_html_e('All templates can be modified after selection. You can add, remove or edit form fields, customize emails and fine tune settings.','custom-registration-form-builder-with-submission-manager'); ?></span>
                </div>
                <span  class="rm-modal-close">&times;</span>
            </div>
            <div class="rm-modal-container">                
            <?php require RM_ADMIN_DIR.'views/template_rm_new_form_templates.php'; ?>
            </div>
        </div>
    </div>
    
    
     <!-- End: Form Template popup -->
     
    <!-- Form Publish Pop-up -->
    
    <div id="rm_form_publish_popup" class="rm-modal-view" style="display: none;">
        <div class="rm-modal-overlay"></div>
        <div class="rm-modal-wrap rm-publish-form-popup">

            <div class="rm-modal-titlebar rm-new-form-popup-header">
                <div class="rm-modal-title">
                <?php esc_html_e('Publish','custom-registration-form-builder-with-submission-manager'); ?>
                </div>
                <span class="rm-modal-close">&times;</span>
            </div>
            <div class="rm-modal-container">
                <?php $form_id_to_publish = isset($entry->form_id) ? $entry->form_id : 1; ?>
                <?php include_once RM_ADMIN_DIR . 'views/template_rm_formflow_publish.php'; ?>
            </div>
        </div>

    </div>
    
        <!-- End Form Publish Pop-up -->
    
        <div id="rm_embed_code_dialog" style="display:none"><textarea readonly="readonly" id="rm_embed_code" onclick="jQuery(this).focus().select()"></textarea><div id="rm_embed_warning"><?php echo RM_UI_Strings::get('EMBED_CODE_INFO') ?></div><img class="rm-close" src="<?php echo esc_url(plugin_dir_url(dirname(dirname(__FILE__))) . 'images/close-rm.png'); ?>" onclick="jQuery('#rm_embed_code_dialog').fadeOut()"></div>
        
</div>
</div>
<form name="rm_form_manager" id="rm_form_manager_operartionbar" class="rm_static_forms" method="post" action="">
    <input type="hidden" name="rm_slug" value="" id="rm_slug_input_field">
    <input type="hidden" name="rm_selected" value="">
    <?php wp_nonce_field('rm_form_manager_template'); ?>
    <input type="hidden" name="req_source" value="form_manager">
</form>
<pre class="rm-pre-wrapper-for-script-tags">
    <script type="text/javascript">
        jQuery(document).ready(function(){
           //Configure joyride
           //If autostart is false, call again "jQuery("#rm-form-man-joytips").joyride()" to start the tour.
            <?php if (false && $data->autostart_tour): ?>
            /*jQuery("#rm-form-man-joytips").joyride({tipLocation: 'top',
                autoStart: true,
                postRideCallback: rm_joyride_tour_taken});*/
            <?php else: ?>
                jQuery("#rm-form-man-joytips").joyride({tipLocation: 'top',
                    autoStart: false,
                    postRideCallback: rm_joyride_tour_taken});
            <?php endif; ?>

            var pagination_input_top = document.getElementById("rm_reqpage_top");
            var pagination_input_bottom = document.getElementById("rm_reqpage_bottom");
            pagination_input_top.addEventListener("keydown", function(event) {
                if(event.key === "Enter") {
                    event.preventDefault();
                    pagination_input_bottom.value = this.value;
                    document.getElementById("rm_pagination_input_form").submit();
                }
            });
            pagination_input_bottom.addEventListener("keydown", function(event) {
                if(event.key === "Enter") {
                    event.preventDefault();
                    pagination_input_top.value = this.value;
                    document.getElementById("rm_pagination_input_form").submit();
                }
            });
        });

        function set_forms_entry_depth(element) {
            var selectedVal = jQuery(element).find('option').filter(':selected').val();
            var postData = {'action' : 'rm_set_forms_entry_depth', 'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>', 'value' : selectedVal};
            jQuery.post('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', postData, function(response) {
                if(response.success) {
                    location.reload();
                }
            });
        }

        function rm_forms_roll_back() {
            var postData = {'action' : 'rm_forms_view_roll_back', 'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>', 'value': <?php echo absint($data->old_view); ?>};
            jQuery.post('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', postData, function(response) {
                if(response.success) {
                    window.location.href = "<?php echo esc_url(admin_url("admin.php?page=rm_form_manage")); ?>";
                }
            });
        }
             
        function rm_setup_thickbox_dimensions(_prev_href) {
            /* Seemingly hackish way to configure WP Thickbox's dimension according to user display size without using CSS, but hey, it works.*/
            var $prev_link = jQuery("#rm_form_preview_action");
            var prev_href = _prev_href || $prev_link.attr("href");
            var index = prev_href.indexOf("&width=900&height=600"),
                prev_href_base = prev_href.substr(0,index),
                cx = window.innerWidth*95/100,
                cy = window.innerHeight*95/100;

            var new_href = prev_href_base+"&width="+cx+"&height="+cy;
            jQuery(".rm_form_preview_btn").each(function(){
                jQuery(this).attr("href", new_href);
            });
        }

        function rm_init_submit_field() {
            jQuery(".rm_field_btn").on("keydown", function(e){
                if(e.keyCode === 13 || e.keyCode === 27) {
                    jQuery(this).blur();
                    window.getSelection().removeAllRanges();
                } 
            })

            var last_label;

            jQuery(".rm_field_btn").on("focus", function(e){
                    var temp = jQuery(this).text().trim();
                    if(temp.length)
                        last_label = temp;
            })

            jQuery(".rm_field_btn").on("blur", function(e){
                    var temp = jQuery(this).text().trim();
                    if(temp.length <= 0)
                        jQuery(this).text(last_label);
                    else
                        rm_update_submit_field();
            })

            jQuery("input[name='rm_field_submit_field_align']").change(function(e){
                    var $btn_container = jQuery(".rm-field-submit-field-btn-container");
                    $btn_container.removeClass("rm-field-btn-align-left rm-field-btn-align-center rm-field-btn-align-right");
                    $btn_container.addClass("rm-field-btn-align-"+jQuery(this).val());
                    rm_update_submit_field();
            })
        }
   
        function delete_form(form_id) {
            confirmation = confirm('<?php echo wp_kses_post((string)RM_UI_Strings::get('ALERT_DELETE_FORM')); ?>');
            if(confirmation) {
                jQuery(".rm_form_select_check").addClass("rm-delete-item");
                jQuery('input.rm_checkbox_group[value='+form_id+']').prop("checked", true);
                jQuery.rm_do_action('rm_form_manager_operartionbar', 'rm_form_remove');
            }
        }

        function duplicate_form(form_id) {
            jQuery(".rm_form_select_check").addClass("rm-delete-item");
            jQuery('input.rm_checkbox_group[value='+form_id+']').prop("checked", true);
            jQuery.rm_do_action('rm_form_manager_operartionbar', 'rm_form_duplicate');
        }

        function export_form(form_id) {
            jQuery(".rm_form_select_check").addClass("rm-delete-item");
            jQuery('input.rm_checkbox_group[value='+form_id+']').prop("checked", true);
            jQuery.rm_do_action('rm_form_manager_operartionbar', 'rm_form_export');
            jQuery('input.rm_checkbox_group[value='+form_id+']').prop("checked", false);
            jQuery(".rm_form_select_check").removeClass("rm-delete-item");
        }

        function copy_shortcode(form_id) {
            var shortcode = jQuery("div#rm-shortcode-"+form_id).text();

            navigator.clipboard.writeText(shortcode);

            alert("Copied the shortcode: " + shortcode);
        }

        function rm_apply_bulk_action(obj) {
            if(obj.id == 'rm-bulk-action-top')
                var option = jQuery("select#bulk-action-selector-top").val();
            else
                var option = jQuery("select#bulk-action-selector-bottom").val();
            var form_ids = [];

            jQuery("input.rm_form_select_check:checked").each(function (e) {
                form_ids.push(jQuery(this).val());
            });

            if(form_ids.length <= 0) {
                alert('Please select one or more forms before applying bulk action');
                return false;
            }

            if(option == 'export') {
                jQuery.rm_do_action('rm_form_manager_operartionbar', 'rm_form_export');
            } else if (option == 'delete') {
                jQuery.rm_do_action_with_alert('<?php echo wp_kses_post((string)RM_UI_Strings::get('ALERT_DELETE_FORM')); ?>', 'rm_form_manager_operartionbar', 'rm_form_remove');
            }
        }

   function rm_start_joyride(){
       jQuery("#rm-form-man-joytips").joyride();
    }
    
        function rm_joyride_tour_taken(){
            var data = {
                'action': 'joyride_tour_update',
                'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>',
                'tour_id': 'form_manager_tour',
                'state': 'taken'
    		};

            jQuery.post(ajaxurl, data, function(response) {});
        }
    
    function rm_open_dial(form_id){
        jQuery('textarea#rm_embed_code').html('<?php echo wp_kses_post((string)RM_UI_Strings::get('MSG_BUY_PRO_GOLD_EMBED')); ?>');
        jQuery('#rm_embed_code_dialog').fadeIn(100);
    }
    jQuery(document).mouseup(function (e) {
        var container = jQuery("#rm_embed_code_dialog,.rm_form_card_settings_dialog");
        if (!container.is(e.target) // if the target of the click isn't the container... 
                && container.has(e.target).length === 0) // ... nor a descendant of the container 
        {
            container.hide();
        }
    });
    
    function  rm_on_form_selection_change() {    
        var selected_forms = jQuery("input.rm_checkbox:checked");
        if(selected_forms.length > 0) {   
            jQuery("#rm-ob-export a").html( jQuery("#rm-ob-export").data("rmlocalstrselected") + ' <span class="rm-export-count">(' + selected_forms.length +')</span>');
            jQuery("#rm-ob-delete").removeClass("rm_deactivated");   
            jQuery("#rm-ob-duplicate").removeClass("rm_deactivated");
        } else {
            jQuery("#rm-ob-export a").html(jQuery("#rm-ob-export").data("rmlocalstrall"));
            jQuery("#rm-ob-delete").addClass("rm_deactivated");
             jQuery("#rm-ob-duplicate").addClass("rm_deactivated");
            
        }  
    }
    
    function make_me_a_star(e){
        var form_id = jQuery(e).attr('id').slice(8);
        var variable_id="#rm-star_"+form_id;
        
        if(jQuery(variable_id).hasClass( "rm_def_form_star" ))
        {
             var data = {
			'action': 'unset_default_form',
            'rm_ajaxnonce': '<?php echo wp_create_nonce('rm_formflow'); ?>',
			'rm_def_form_id': form_id
		};
            jQuery.post(ajaxurl, data, function(response) {
                jQuery(variable_id).removeClass( "rm_def_form_star" );
                jQuery(variable_id).addClass( "rm_not_def_form_star" );
                
            });
            return false;
        }
      
        //toggle();
        if(typeof form_id != 'undefined' && !jQuery(e).hasClass('rm_def_form_star')){
        
        var ajaxnonce = '<?php echo wp_create_nonce('rm_formflow'); ?>';
        var data = {
			'action': 'set_default_form',
            'rm_ajaxnonce':ajaxnonce,
			'rm_def_form_id': form_id
		};

        jQuery.post(ajaxurl, data, function(response) {
                        var old_form = jQuery('.rm_def_form_star');
			old_form.removeClass('rm_def_form_star');
                        old_form.addClass('rm_not_def_form_star');
                        
                        var curr_form = jQuery('#rm-star_'+form_id);
                        curr_form.removeClass('rm_not_def_form_star');
                        curr_form.addClass('rm_def_form_star');
		});
            }
    }
    
    function rm_show_form_sett_dialog(form_id){
        jQuery("#rm_settings_dailog_"+form_id).show();
    }
      
jQuery("#rm_rateit_banner").bind('rated', function (event, value) { 
        if(value<=3)
        {
            
             jQuery("#rm-rate-popup-wrap").fadeOut();  
             jQuery("#wordpress_review").fadeOut(100);  
             jQuery("#feedback_message").fadeIn(100);  
             jQuery('#feedback_message').removeClass('rm-blur');
             jQuery('#feedback_message').addClass('rm-hop');
             handle_review_banner_click('rating',value);
        }
        else
        {
             jQuery("#rm-rate-popup-wrap").fadeOut();  
             jQuery("#feedback_message").fadeOut();  
             jQuery("#wordpress_review").fadeIn(100);
             jQuery('#wordpress_review').removeClass('rm-blur');
             jQuery('#wordpress_review').addClass('rm-hop');
             handle_review_banner_click('rating',value);
        }
    
    
    });
    
    function save_fb_subscribe_action()
    {
            window.open("https://www.facebook.com/registrationmagic", '_blank');
        jQuery.ajax({
            url:ajaxurl,
            type:'post',
            data:{action:'rm_fb_subscribe_action','rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>'},
            success:function(data)
            {               
               jQuery('#fb_sub_footer').hide();
            }
        });
    }
    
        function CallModalBox(ele) {
          jQuery(jQuery(ele).attr('href')).toggle().find("input[type='text']").focus();
          if(jQuery(ele).attr('href')=='#rm_add_new_form_popup' || jQuery(ele).attr('href')=='#rm_add_new_form_popup_template'){
             jQuery('.rmagic #rm_add_new_form_popup.rm-modal-view').addClass('rm-form-popup-show').removeClass('rm-form-popup-hide');  
            jQuery('.rmagic .rm_add_new_form_wrap.rm-create-new-from').removeClass('rm-form-popup-out');
            jQuery('.rmagic .rm_add_new_form_wrap.rm-create-new-from').addClass('rm-form-popup-in');
            
            jQuery('.rm-modal-overlay').removeClass('rm-form-popup-overlay-fade-out');
            jQuery('.rm-modal-overlay').addClass('rm-form-popup-overlay-fade-in');
          }
      }
    
      jQuery(document).ready(function () {
          jQuery('.rm-modal-close, .rm-modal-overlay').click(function () {
              setTimeout(function(){
                  //jQuery(this).parents('.rm-modal-view').hide();
                  jQuery('.rm-modal-view').hide();
                
              }, 400);
              
            jQuery('.rmagic #rm_add_new_form_popup.rm-modal-view').removeClass('rm-form-popup-show').addClass('rm-form-popup-hide'); 
              
          });
          

            jQuery('.rmagic .rm-create-new-from .rm-new-form-popup-header .rm-modal-close, #rm_add_new_form_popup .rm-modal-overlay, #rm_add_new_form_popup_template .rm-modal-overlay').on('click', function(){
            jQuery('.rmagic .rm_add_new_form_wrap.rm-create-new-from').removeClass('rm-form-popup-in');
            jQuery('.rmagic .rm_add_new_form_wrap.rm-create-new-from').addClass('rm-form-popup-out');
            
            jQuery('.rm-modal-overlay').removeClass('rm-form-popup-overlay-fade-in');
            jQuery('.rm-modal-overlay').addClass('rm-form-popup-overlay-fade-out');
          });
          
      });
    
    function recursive_import(form_id) {
        var id = form_id;
        var ajaxnonce = '<?php echo wp_create_nonce('rm_import_first'); ?>';
        var data = {
            'action': 'import_first',
            'rm_ajaxnonce': ajaxnonce,
            'form_id': id
        };
        jQuery.post(ajaxurl, data, function (response) {
            if (response == 0)
            {
               _getEl("progressBar").value = Math.round(100);
                _getEl("status").innerHTML = '<?php esc_html_e('Import Successfully Completed', 'custom-registration-form-builder-with-submission-manager'); ?>';
                setTimeout(function(){
                     new_url= "<?php echo esc_url(admin_url('admin.php?')); ?>" + update_current_url_with_param("last_form_id","<?php echo esc_html($last_form_id); ?>");
                     window.location= new_url;
                },3000)
            } else {

                //jQuery("#rm_import_progress").append("(Imported)</br></br>Importing RM Form--" + response + "");

                recursive_import(response);
            }
        });
    }
    
    function start_import(){
        jQuery("#rm_import_errors").html();
         var ajaxnonce = '<?php echo wp_create_nonce('rm_import_first'); ?>';
        var data = {
            'action': 'import_first',
            'rm_ajaxnonce': ajaxnonce
        };
        jQuery.post(ajaxurl, data, function (response) {
            if (response == 0)
            {
                _getEl("progressBar").value = Math.round(100);
                _getEl("status").innerHTML = '<?php esc_html_e('Import Successfully Completed', 'custom-registration-form-builder-with-submission-manager') ?>';
                setTimeout(function(){
                     new_url= "<?php echo esc_url(admin_url('admin.php?')); ?>" + update_current_url_with_param("last_form_id","<?php echo esc_html($last_form_id); ?>");
                     window.location= new_url;
                },3000)
              
            } else if (response === "INVALID_FILE") {
                jQuery("#rm_import_errors").html('');
                jQuery("#rm_import_errors").append("<div class='rm_import_error'><?php esc_html_e('Invalid RegistrationMagic template file. Please upload valid template file with XML extension.', 'custom-registration-form-builder-with-submission-manager') ?></div>");
                jQuery("#progressBar,#status").hide();
            } else {
                var pre = parseInt(response) - 1;
                recursive_import(response);
            }

        });
    }
    
    /* Upload Handler */
    function _getEl(el) {
     return document.getElementById(el);
    }
    
    function check_file_extension(obj){
        var file = obj.files[0];
        if(file && file.type!="text/xml"){
            jQuery("#rm_import_errors").html("<div class='rm_import_error'><?php esc_html_e('Invalid RegistrationMagic template file. Please upload valid template file with XML extension.', 'custom-registration-form-builder-with-submission-manager'); ?>");
            obj.value='';
        }
    }
    var rm_file_ajax=null;    
    function uploadFile() {
      var file = _getEl("xml_file").files[0];
      if(!file){
           jQuery("#rm_import_errors").html("<div class='rm_import_error'><?php esc_html_e('Please select  a file.', 'custom-registration-form-builder-with-submission-manager'); ?></div>");
           return;
      }
      jQuery("#rm_import_errors").html('');
      var formdata = new FormData();
      var ajaxnonce = '<?php echo wp_create_nonce('rm_admin_upload_template'); ?>';
      formdata.append("action", "rm_admin_upload_template");
      formdata.append("file", file);
      formdata.append("rm_ajaxnonce", ajaxnonce);
      rm_file_ajax = new XMLHttpRequest();
      rm_file_ajax.upload.addEventListener("progress", progressHandler, false);
      rm_file_ajax.addEventListener("load", completeHandler, false);
      rm_file_ajax.addEventListener("error", errorHandler, false);
      rm_file_ajax.addEventListener("abort", abortHandler, false);
      rm_file_ajax.open("POST", "<?php echo esc_url(admin_url('admin-ajax.php')); ?>");
      jQuery("#progressBar,#status").show();
      rm_file_ajax.send(formdata);
    }

    function progressHandler(event) {
      var percent = (event.loaded / event.total) * 50;
      _getEl("progressBar").value = Math.round(percent);
      _getEl("status").innerHTML = "<?php esc_html_e('File upload is in progress...', 'custom-registration-form-builder-with-submission-manager') ?>";
    }

    function completeHandler(event) {
       var percent = 50;
      _getEl("progressBar").value = Math.round(percent);
      _getEl("status").innerHTML = "<?php esc_html_e('Form Import is in progress....', 'custom-registration-form-builder-with-submission-manager') ?>";
      start_import();
    }

    function errorHandler(event) {
      _getEl("status").innerHTML = "<?php esc_html_e('Upload Failed', 'custom-registration-form-builder-with-submission-manager') ?>";
    }

    function abortHandler(event) {
      _getEl("status").innerHTML = "<?php esc_html_e('Upload Aborted', 'custom-registration-form-builder-with-submission-manager') ?>";
    }     
    
    function cancel_file_upload(){
       // rm_file_ajax.abort();
        location.reload();
    }
    
    <?php if(empty(get_option('rm_dismiss_upgrade_notice', false))) { ?>
    jQuery(document).ready(function($){
        var rmUpgradeNotice = $( '.rm-upgrade-notice-info' );

        $( '#wpbody-content' ).prepend( rmUpgradeNotice );
        rmUpgradeNotice.delay( 1000 ).slideDown();
        
        $(".rm-upgrade-notice-info .rm-promo-notice-dismiss").click(function(){
            $(".rm-upgrade-notice-info").slideUp();
            
            var dismiss_data = {
                'action': 'rm_dismiss_upgrade_notice',
                'rm_sec_nonce': '<?php echo wp_create_nonce('rm_ajax_secure'); ?>'
            };
            
            jQuery.post(ajaxurl, dismiss_data, function(response) {});
        });
    });
    <?php } ?>



    function rm_open_dial(form_id) {
          jQuery('textarea#rm_embed_code').html('<iframe class="regmagic_embed" width="500" height="500" src="<?php echo esc_url(admin_url('admin-ajax.php?action=registrationmagic_embedform&form_id=')); ?>' + form_id + '"></iframe>');
          jQuery('#rm_embed_code_dialog').fadeIn(100);
      }
      jQuery(document).mouseup(function (e) {
          var container = jQuery("#rm_embed_code_dialog,.rm_form_card_settings_dialog");
          if (!container.is(e.target) // if the target of the click isn't the container... 
                  && container.has(e.target).length === 0) // ... nor a descendant of the container 
          {
              container.hide();
          }
      });
   
  </script></pre>
    
    <style>
        .rmagic input[type=checkbox].rm_checkbox_group.rm-delete-item:checked{
            border: 1px solid #8c8f94 !important;
        }
        .rmagic input[type=checkbox].rm_checkbox_group.rm-delete-item:checked:before{
            content: "" !important;
          
        }
    </style>
<?php } ?>