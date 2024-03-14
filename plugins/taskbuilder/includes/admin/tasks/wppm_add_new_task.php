<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}
global $current_user,$wpdb,$wppmfunction;
$proj_id = isset($_POST['proj_id']) ? intval(sanitize_text_field($_POST['proj_id'])) : 0 ;
$priorities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_task_priorities ORDER BY name" );
if($current_user->has_cap('manage_options')){
    $query = ( "SELECT * FROM {$wpdb->prefix}wppm_project ORDER BY project_name" );
    $where = "";
}else{
    $query = ("SELECT Proj.*
              FROM {$wpdb->prefix}wppm_project AS Proj
              Left join {$wpdb->prefix}wppm_project_meta proj_meta ON Proj.id = proj_meta.project_id");
    $where =  " where (FIND_IN_SET($current_user->ID,Proj.users)) OR (Proj.id = proj_meta.project_id AND proj_meta.meta_key='public_project' AND proj_meta.meta_value=1) Group by Proj.id ORDER BY project_name";
}
$query = apply_filters("wppm_projects_list_in_create_task_query",$query);
$where = apply_filters("wppm_projects_list_in_create_task_query_where",$where);
$query.= $where;
$projects = $wpdb->get_results($query);
$start_date="";
$end_date="";
$wppm_task_list_view = get_option('wppm_default_task_list_view');
$wppm_default_task_date = get_option('wppm_default_task_date');
?>
<form class='wppm_add_new_task wppm_bootstrap' onsubmit="return wppm_create_task();" id="wppm_add_new_task" method="post">
    <div class="wppm_headers row">
        <div class="col-sm-12">
            <span class="wppm-heading-inline">
                <?php echo esc_html_e('Create Task','taskbuilder');?>
            </span>
            <?php if($proj_id==0){ 
                    $style = "display:inline;";
                    $style = apply_filters('wppm_add_new_task_btn_style',$style);
                    ?>
                    <span onclick="wppm_add_new_task()" class="wppm_add_new_task_btn" title="Create Task" style=<?php echo esc_attr($style);?>><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/plus.svg'); ?>" alt="plus-icon"></span>
            <?php } ?>
            <?php if($proj_id==0) { 
                  $tstyle = "display:inline;";
                  $tstyle = apply_filters('wppm_task_list_btn_style',$tstyle);
                ?>
                <span class="wppm-task-list-btn" title="Task List" onclick="wppm_get_task_list()" style="<?php echo esc_attr($tstyle);?>"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/list.svg'); ?>" alt="list-icon"></span>
            <?php } else { ?>
                <span class="wppm-task-list-btn" id="wppm_task_list_btn" title="Task List" onclick="wppm_get_project_tasks(<?php echo esc_attr($proj_id)?>)"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/list.svg'); ?>" alt="list-icon"></span>
            <?php } ?>
        </div>
    </div>
    <div id='wppm_task_frm_field_container'>
        <div class="row">
            <div class="wppm_task_frm_fields col-sm-6">
                <span class="wppm_form_field_label"><b>
                <?php echo esc_html_e('Task Name','taskbuilder');?>
                    </b>
                    <span style="color:#ff0000;">*</span>
                </span><br>
                <input name="name" class="form-control" id="wppm_task_name" type="text" value="" size="40" aria-required="true"><br>
            </div>
            <div class="wppm_task_frm_fields wppm_project_autocomplete_container col-sm-6">
                <span class="wppm_form_field_label"><b>
                    <?php echo esc_html_e('Project','taskbuilder');?>
                    </b>
                </span><br>
                <select searchable="search here" onchange="wppm_select_project()" class="form-control" size="40" name="wppm_task_project" id="wppm_task_project">
                    <?php 
                        if(!empty($projects)){
                            foreach($projects as $proj) { 
                                ?>
                                <option value="<?php echo esc_attr($proj->id)?>"><?php echo esc_html_e($proj->project_name,'taskbuilder');?></option>
                        <?php } 
                        } ?>
                </select>
            </div>
        </div>
        <?php if($wppm_default_task_date==1){ ?>
            <div class="row">
                <div class="wppm_task_frm_fields col-sm-6">
                    <span class="wppm_form_field_label"><b>
                        <?php echo esc_html_e('Start date','taskbuilder');?>
                        </b>
                    </span><br>
                    <input name="wppm_task_start_date" class="form-control" id="wppm_task_start_date" type="text" value="<?php echo $start_date = apply_filters('wppm_add_new_task_start_date',esc_attr($start_date));?>" size="40" aria-required="true"><br>
                </div>
                <div class="wppm_task_frm_fields col-sm-6">
                    <span class="wppm_form_field_label"><b>
                        <?php echo esc_html_e('End date','taskbuilder');?>
                        </b>
                    </span><br>
                    <input name="wppm_task_end_date" id="wppm_task_end_date" class="form-control" type="text" value="<?php echo $end_date= apply_filters('wppm_add_new_task_end_date', esc_attr($end_date));?>" size="40" aria-required="true"><br>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="wppm_task_frm_fields col-sm-12">
                <span class="wppm_form_field_label"><b>
                    <?php echo esc_html_e('Description','taskbuilder');?>
                    </b>
                </span><br>
                <textarea id="wppm_task_description" name="wppm_task_description">
                </textarea>
                <br>
            </div>
        </div>
        <div class="row">
            <div class="wppm_task_frm_fields col-sm-12">
                <span class="wppm_form_field_label"><b>
                    <?php echo esc_html_e('Priority','taskbuilder');?>
                    </b>
                </span><br>
                <select id="wppm_create_task_priority" name="wppm_create_task_priority" class="form-control">
                    <option value=""></option><?php 
                    if(!empty($priorities)){
                        foreach ($priorities as $priority){
                            echo '<option value="'.esc_attr($priority->id).'">'.esc_html(stripcslashes($priority->name)).'</option>';
                        }
                    }
                    ?>
                </select><br><br>
            </div>
        </div>
        <div class="row">
            <div class="wppm_task_frm_fields col-sm-12">
                <span class="wppm_form_field_label"><b>
                    <?php echo esc_html_e('Task Members','taskbuilder');?>
                    </b>
                </span><br>
                <input class="form-control wppm_assign_task_users ui-autocomplete-input" name="wppm_assign_task_users" type="text" autocomplete="off" placeholder="<?php echo esc_attr__('Search User...','taskbuilder');?>">
                <br>
        </div>
        <div id="wppm_display_task_users" class="row">
            <div class="col-sm-12">
                <ui class="wppm_filter_display_users_container"></ui>
            </div>
        </div>
        <?php do_action('wppm_after_task_fields_in_new_task_form');?>
        <div class="row">
            <div class="wppm_frm_submit col-sm-12">
                <button type="submit" class="wppm-submit-btn" id="wppm-submit-task-btn"><?php echo esc_html_e('Add Task','taskbuilder');?></button>
                <?php if($proj_id==0){ ?>
                    <button type="button" class="wppm_reset_btn" id="wppm_submit_task_reset_btn" onclick="wppm_add_new_task()"><?php echo esc_html_e('Reset form','taskbuilder');?></button>
                <?php } else {?>
                    <button type="button" class="wppm_reset_btn" id="wppm_submit_task_reset_btn" onclick="wppm_create_project_task(<?php echo esc_attr($proj_id) ?>)"><?php echo esc_html_e('Reset form','taskbuilder');?></button>
                <?php } ?>
                <input type="hidden" name="action" value="wppm_create_task" />
            </div>
        </div>
    </div>
</form>
<script>
jQuery( document ).ready( function( jQuery ) {
    wppm_select_project();
    jQuery("#wppm_task_start_date").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });
    jQuery("#wppm_task_end_date").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i"
        //minDate: startdate
    });
    tinymce.remove();
    tinymce.init({ 
    selector:'#wppm_task_description',
    body_id: 'wppm_task_description',
    directionality : '<?php //echo 'rtl'; ?>',
    menubar: false,
    statusbar: false,
    height : '200',
    width  : '100%', 
    plugins: [
        'lists link image directionality paste'
    ],
    image_advtab: true,
    toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
    branding: false,
    autoresize_bottom_margin: 20,
    browser_spellcheck : true,
    relative_urls : false,
    remove_script_host : false,
    paste_as_text: true,
    convert_urls : true,
    setup: function (editor) {
    }
    });
    jQuery("input[name='wppm_assign_task_users']").keypress(function(e) {
        //Enter key
        if (e.which == 13) {
            return false;
        }
    });
    jQuery( ".wppm_assign_task_users" ).autocomplete({
        minLength: 1,
        appendTo: jQuery('.wppm_assign_task_users').parent(),
        source: function( request, response ) {
            var term = request.term;
            request = {
                action: 'wppm_filter_autocomplete',
                term : term,
                field : 'task_users_by_project_name',
                proj_id : jQuery('#wppm_task_project').val()
            }
            jQuery.getJSON( wppm_admin.ajax_url, request, function( data, status, xhr ) {
                response(data);
            });
        },
        select: function (event, ui) {
            var html_str = '<li class="wppm_filter_display_element" style="list-style: none">'
                                +'<div class="flex-container">'
                                    +'<div class="wppm_filter_display_text">'
                                        +ui.item.label
                                        +'<input type="hidden" name="user_names[]" value="'+ui.item.user_id+'">'
                                    +'</div>'
                                    +'<div class="wppm_filter_display_remove" onclick="wppm_remove_user(this);"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/cross.svg'); ?>" alt="delete"></div>'
                                +'</div>'
                            +'</li>';
            jQuery('#wppm_display_task_users .wppm_filter_display_users_container').append(html_str);
            jQuery(this).val(''); return false;
        }
    }).focus(function() {
        jQuery(this).autocomplete("search", "");
    });
});

jQuery('#wppm_task_project option:eq(0)').prop('selected',true);
jQuery('#wppm_task_project').select2();
jQuery("#wppm_add_new_task").on("submit", function (e) {
    e.preventDefault();
    location.reload();
});

function wppm_remove_user(e){
  jQuery(e).parent().parent().remove();
}

function wppm_select_project(){
  jQuery('.wppm_filter_display_element').hide();
  <?php do_action('wppm_display_cf_by_project') ?>
}

function wppm_create_task(){
    if(!jQuery('#wppm_task_name').val()){
        alert("<?php _e('Task title is required','taskbuilder')?>");
        return false;
    }
    <?php do_action('wppm_create_ticket_js_function'); ?>
    var dataform = new FormData(jQuery('#wppm_add_new_task')[0]);
    jQuery('#wppm_task_container').html(wppm_admin.loading_html);
    var description = tinyMCE.get('wppm_task_description').getContent().trim();
	dataform.append('wppm_task_description', description);
    <?php do_action('wppm_create_task_dataform'); ?>
    jQuery.ajax({
        url: wppm_admin.ajax_url,
        type: 'POST',
        data: dataform,
        processData: false,
        contentType: false
    }) .done(function (response_str) {
    <?php if($wppm_task_list_view==1){ ?>
            wppm_get_task_list();
    <?php } elseif($wppm_task_list_view==0){ ?>
            wppm_display_grid_view();
    <?php } ?>
    });
}
</script>