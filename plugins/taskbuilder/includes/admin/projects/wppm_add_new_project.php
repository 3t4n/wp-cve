<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpdb,$wppmfunction,$current_user;
$wppm_users_role = get_option('wppm_user_role');
$wppm_project_time = get_option('wppm_project_time');
$wppm_default_project_date = get_option('wppm_default_project_date');
$wppm_public_projects_permission = get_option('wppm_public_projects_permission');
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wppm_project_categories ORDER BY name" );
?>
<form class='wppm_add_new_project wppm_bootstrap' onsubmit="return wppm_create_project();" id="wppm_add_new_project" method="post">
    <div class="wppm_headers row">
        <div class="col-sm-12">
            <span class="wppm-heading-inline" style="margin-left:15px;">
                <?php echo esc_html_e('Create Project','taskbuilder');?>
            </span>
            <?php if($current_user->has_cap('manage_options')){ ?>
                    <span class="wppm-add-new-btn btn-primary" id="wppm_add_new_proj" onclick="wppm_add_new_project()" ><span style="margin-right:5px;"></span><span><?php echo esc_html_e('+Create Project','taskbuilder');?></span></span>
            <?php } ?>
            <span class="wppm-add-new-btn btn-primary" id="wppm_project_list" onclick="wppm_get_project_list()" ><span style="margin-right:5px;"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/list-symbol.svg'); ?>" alt="list-symbol"></span><span><?php echo esc_html_e('Project List','taskbuilder');?></span></span>
        </div>
    </div>
    <div id='wppm_frm_field_container'>
        <div class="row">
            <div class="wppm_project_frm_fields col-sm-12">
                <span class="wppm_form_field_label"><b><?php echo esc_html_e('Name','taskbuilder'); ?></b></span>
                    <span style="color:#ff0000;">*</span>
                <br>
                <input name="name" class="form-control" id="wppm_project_name" type="text" size="40" aria-required="true"><br>
            </div>
        </div>
        <?php if($wppm_default_project_date==1){ ?>
                <div class="row">
                    <div class="wppm_project_frm_fields col-sm-6">
                        <span class="wppm_form_field_label"><b>
                            <?php echo esc_html_e('Start date','taskbuilder'); ?>
                            </b>
                        </span><br>
                        <input name="wppm_start_date" class="form-control" id="wppm_start_date" type="text" value="" size="40" aria-required="true"><br>
                    </div>
                    <div class="wppm_project_frm_fields col-sm-6">
                        <span class="wppm_form_field_label"><b>
                            <?php echo esc_html_e('End date','taskbuilder'); ?>
                            </b>
                        </span><br>
                        <input name="wppm_end_date" id="wppm_end_date" class="form-control" type="text" value="" size="40" aria-required="true"><br>
                    </div>
                </div>
        <?php } ?>
        <div class="row">
            <div class="wppm_project_frm_fields col-sm-12">
                <span class="wppm_form_field_label"><b>
                    <b>
                    <?php echo esc_html_e('Description','taskbuilder'); ?>
                    </b>
                </span><br>
                <textarea id="wppm_proj_description" name="wppm_proj_description">
                </textarea>
                <br>
            </div>
        </div>
        <div class="row">
           <div class="wppm_project_frm_fields col-sm-12">
                <span class="wppm_form_field_label"><b>
                    <b>
                    <?php echo esc_html_e('Category','taskbuilder'); ?>
                    </b>
                </span><br>
                <select id="wppm_create_project_category" name="wppm_create_project_category" class="form-control">
                    <option value=""></option><?php 
                    if(!empty($categories)){
                        foreach ($categories as $category){
                            echo '<option value="'.esc_attr($category->id).'">'.esc_html(stripcslashes($category->name)).'</option>';
                        }
                    }
                    ?>
                </select><br><br>
            </div>
        </div>
        <div class="row">
            <div class="wppm_project_frm_fields col-sm-12">
                <span class="wppm_form_field_label"><b>
                    <b>
                    <?php echo esc_html_e('Assign User','taskbuilder'); ?>
                    </b>
                </span><br>
                <input class="form-control wppm_assign_users ui-autocomplete-input" name="wppm_assigned_user" type="text" autocomplete="off" placeholder= "<?php echo esc_attr__('Search User...','taskbuilder');?>">
                <br>
        </div>
        <div id="wppm_display_users" class="row">
            <div class="col-sm-12">
                <ui class="wppm_filter_display_users_container"></ui>
            </div>
        </div>
        <?php if( $wppm_public_projects_permission==1) { ?>
        <div id="wppm_public_projects_permission" class="row">
            <div class="col-sm-12">
                <span class="wppm_form_field_label"><b>
                    <input type="checkbox" name="wppm_public_project"" />
                    <label id="wppm_public_project_label"><?php echo esc_html_e('Make Project Public','taskbuilder'); ?></label>
                    </b>
                </span><br>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="wppm_frm_submit col-sm-12">
                <button type="submit" class="wppm-submit-btn" id="wppm-submit-proj-btn"><?php echo esc_html_e('Add Project','taskbuilder'); ?></button>
                <button type="button" class="wppm_reset_btn" id="wppm_submit_proj_reset_btn" onclick="wppm_add_new_project()"><?php echo esc_html_e('Reset Form','taskbuilder') ?></button>
                <input type="hidden" name="action" value="wppm_create_project" />
            </div>
        </div>
    </div>
</form>
<script>
jQuery( document ).ready( function( jQuery ) {
    jQuery("#wppm_start_date").flatpickr({
        enableTime: <?php echo ($wppm_project_time == 1) ? 'true' : 'false' ?>,
        dateFormat: "Y-m-d <?php echo ($wppm_project_time == 1) ? 'H:i': '' ?>"
    });
    jQuery("#wppm_end_date").flatpickr({
        enableTime: <?php echo ($wppm_project_time==1) ? 'true' : 'false' ?>,
        dateFormat: "Y-m-d <?php echo ($wppm_project_time==1) ? 'H:i': '' ?>"
    });
    tinymce.remove();
    tinymce.init({ 
    selector:'#wppm_proj_description',
    body_id: 'wppm_proj_description',
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
    paste_as_text: true,
    remove_script_host : false,
    convert_urls : true,
    setup: function (editor) {
    }
    });
    jQuery("input[name='wppm_assigned_user']").keypress(function(e) {
        //Enter key
        if (e.which == 13) {
            return false;
        }
    });
    jQuery( ".wppm_assign_users" ).autocomplete({
        minLength: 1,
        appendTo: jQuery('.wppm_assign_users').parent(),
        source: function( request, response ) {
            var term = request.term;
            request = {
                action: 'wppm_filter_autocomplete',
                term : term,
                field : 'users_name',
            }
            jQuery.getJSON( wppm_admin.ajax_url, request, function( data, status, xhr ) {
                response(data);
            });
        },
        select: function (event, ui) {
            var html_str = '<div id="wppm_user_container_'+ui.item.user_id+'" class="row wppm_user_display_container">'
								+'<div class="flex-container col-sm-3">'
									+'<span class="wppm_filter_display_text">'
										+ui.item.label
										+'<input type="hidden" name="user_names[]" value="'+ui.item.user_id+'">'
									+'</span>'
								+'</div>'
								+'<div class="col-sm-3 wppm_user_role">'
										+'<select size="sm" class="form-control" id="wppm_select_user_role_'+ui.item.user_id+'" name="wppm_select_user_role_'+ui.item.user_id+'">'+
										<?php 
                                            if(!empty($wppm_users_role)){
                                                foreach($wppm_users_role as $key=>$role){
                                                    foreach($role as $k=>$val){
                                                        ?>'<option value="<?php echo esc_attr($key) ?>"><?php echo esc_html($role['label']) ?></option>'+<?php
                                                    }
                                                }
                                            }
										?>
										'</select>'
								+'</div>'
								+'<div class="col-sm-3 wppm_delete_user_icon">'
									+'<span onclick="wppm_remove_user_from_project('+ui.item.user_id+');"><img src="<?php echo esc_url( WPPM_PLUGIN_URL . 'asset/images/trash.svg'); ?>" alt="delete"></span>'
								+'</div>'	
							+'</div>';
			jQuery('#wppm_display_users .wppm_filter_display_users_container').append(html_str);
			jQuery(this).val(''); return false;
        }
    }).focus(function() {
        jQuery(this).autocomplete("search", "");
    });
});

function wppm_remove_user(e){
    jQuery(e).parent().parent().remove();
}

function wppm_create_project(){
  if(!jQuery('#wppm_project_name').val()){
    alert("<?php _e('Project title is required','taskbuilder')?>");
    return false;
  }
  var dataform = new FormData(jQuery('#wppm_add_new_project')[0]);
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  var description = tinyMCE.get('wppm_proj_description').getContent().trim();
  <?php echo do_action('wppm_create_project_dataform');?>
  dataform.append('wppm_proj_description', description);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    wppm_get_project_list();
  });
}
</script>