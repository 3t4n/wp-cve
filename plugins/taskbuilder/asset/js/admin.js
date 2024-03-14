function wppm_add_new_project(){
  jQuery('#wppm_project_container').show();
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_add_new_project'
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_project_container').html(response);
  });  
}

function wppm_projects(){
  jQuery('#wppm_project_container').hide();
  jQuery('#wppm_project_list').show(); 
}

function wppm_get_users(proj_id){
  wppm_modal_open('Add Users'); 
  var data = {
    action: 'wppm_get_users',
    proj_id: proj_id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_get_general_settings(){
  jQuery('.wppm_setting_pills li').removeClass('active');
  jQuery('#wppm_settings_general').addClass('active');
  jQuery('.wppm_setting_col2').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_general_setings'
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('.wppm_setting_col2').html(response);
  });
}

 /*
 * Category Settings
 */
function wppm_get_category_settings(){
  jQuery('.wppm_setting_pills li').removeClass('active');
  jQuery('#wppm_settings_category').addClass('active');
  jQuery('.wppm_setting_col2').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_category_settings'
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('.wppm_setting_col2').html(response);
  });
}

/**
 * Delete category
 */
function wppm_delete_category(cat_id) {

  const flag = confirm(wppm_admin.confirm);
  if (!flag) return;

  var data = { action: 'wppm_delete_category', cat_id };
  jQuery.post(wppm_admin.ajax_url, data, function (res) {
    wppm_get_category_settings();
  });
}

function wppm_get_proj_status_settings(){
  jQuery('.wppm_setting_pills li').removeClass('active');
  jQuery('#wppm_settings_proj_status').addClass('active');
  jQuery('.wppm_setting_col2').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_proj_status_settings'
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('.wppm_setting_col2').html(response);
  });
}

function wppm_get_add_proj_status(){
  wppm_modal_open(wppm_admin.add_new_status); 
  var data = {
    action: 'wppm_get_add_proj_status'
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
    jQuery('#wppm_proj_status_name').focus();
  });
}

function wppm_delete_proj_status(status_id){
  const flag = confirm(wppm_admin.confirm);
  if (!flag) return;

  var data = { action: 'wppm_delete_status', status_id };
  jQuery.post(wppm_admin.ajax_url, data, function (res) {
    wppm_get_proj_status_settings();
  });
}

function wppm_set_project_users(){
  var dataform = new FormData(jQuery('#frm_get_project_users')[0]);
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  var proj_id = jQuery('#wppm_proj_id').val();
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function () {
    wppm_modal_close(); 
    wppm_open_project(proj_id);
  });
}

function wppm_set_task_users(proj_id){
  var dataform = new FormData(jQuery('#frm_get_task_users')[0]);
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var task_id = jQuery('#wppm_task_id').val();
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function () {
    if((proj_id!=0)||(proj_id!="")){
      wppm_modal_close(); 
      wppm_open_project_tasks(task_id,proj_id);
    }else if((proj_id==0)||(proj_id=="")){
      wppm_modal_close(); 
      wppm_open_task(task_id);
    }
  });
}

function wppm_get_project_list(page_no){
  jQuery('#wppm_project_container').show();
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  var dataform=new FormData(jQuery('#wppm_project_list_frm')[0]);
	dataform.append("page_no", page_no);
  dataform.append("action", 'wppm_get_project_list');
  jQuery.ajax( {
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  }) 
  .done(function( response ) {
    jQuery('#wppm_project_container').html(response);
  });
}

function wppm_add_new_task(proj_id){
  jQuery('#wppm_task_container').show();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_add_new_task',
    proj_id:proj_id
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_task_container').html(response);
  }); 
}

function wppm_get_task_list(page_no){
  jQuery('#wppm_task_container').show();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_task_list',
    page_no:page_no
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_task_container').html(response);
  }); 
}

function wppm_get_task_prioriy_settings(){
  jQuery('.wppm_setting_pills li').removeClass('active');
  jQuery('#wppm_settings_task_priorities').addClass('active');
  jQuery('.wppm_setting_col2').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_priority_settings'
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('.wppm_setting_col2').html(response);
  });
}

function wppm_add_task_priority(){
  wppm_modal_open(wppm_admin.add_new_priority); 
  var data = {
    action: 'wppm_add_task_priority'
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
    jQuery('#wppm_cat_name').focus();
  });
}

function wppm_delete_task_priority(priority_id){
  const flag = confirm(wppm_admin.confirm);
  if (!flag) return;

  var data = { action: 'wppm_delete_task_priority', priority_id };
  jQuery.post(wppm_admin.ajax_url, data, function (res) {
    wppm_get_task_prioriy_settings();
  });
}

function wppm_get_task_status_settings(){
  jQuery('.wppm_setting_pills li').removeClass('active');
  jQuery('#wppm_settings_task_status').addClass('active');
  jQuery('.wppm_setting_col2').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_task_status_settings'
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('.wppm_setting_col2').html(response);
  });
}

function wppm_get_add_task_status(){
  wppm_modal_open(wppm_admin.add_new_status); 
  var data = {
    action: 'wppm_get_add_task_status'
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
    jQuery('#wppm_cat_name').focus();
  });
}

function wppm_delete_task_status(status_id){
  const flag = confirm(wppm_admin.confirm);
  if (!flag) return;

  var data = { action: 'wppm_delete_task_status', status_id };
  jQuery.post(wppm_admin.ajax_url, data, function (res) {
    wppm_get_task_status_settings();
  });
}

function wppm_task_search_filter(){
  var task_search = jQuery("#wppm_task_search_filter").val();
  jQuery('#wppm_task_container').show();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
      action: 'wppm_get_task_list',
      task_search:task_search
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_task_container').html(response);
  });
}

function wppm_view_task_search_filter(page_no){
  var task_search = jQuery("#wppm_view_task_search_filter").val();
  jQuery('#wppm_task_container').show();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);

  var data = {
    action: 'wppm_get_task_list_card_view',
    task_search:task_search,
    page_no:page_no
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_task_container').html(response);
  });
}

function wppm_open_task(id){
  jQuery('#wppm_task_container').show();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_open_task',
    id:id
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_task_container').html(response);
  });
}

function wppm_open_project_tasks(id,proj_id){
  wppm_modal_open('Open Tasks');
  jQuery('#wppm_task_container').show();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_open_task',
    id: id,
    proj_id:proj_id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    jQuery('#wppm_popup_body').html(response_str);
    //jQuery('#wppm_task_container').html(response_str);
  });
}

function wppm_add_new_checklist(task_id,proj_id){
  if(jQuery('#wppm_checklist_label').val()==''){
    jQuery('#wppm_checklist_label').focus();
    return  false;
  }
  var data = {
    action: 'wppm_add_new_checklist',
    task_id: task_id,
    proj_id: proj_id,
    checklist_name: jQuery('#wppm_checklist_label').val()
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    if((proj_id!=0)||(proj_id!="")){
      //wppm_modal_close(); 
      wppm_open_project_tasks(task_id,proj_id);
    }else if((proj_id==0)||(proj_id=="")){
      //wppm_modal_close(); 
      wppm_open_task(task_id);
    }
  });
}

function wppm_add_new_checklist_item(checklist_id,task_id,proj_id){
  if(jQuery('#wppm_checklist_item_label_'+checklist_id).val()==''){
    jQuery('#wppm_checklist_item_label_'+checklist_id).focus();
    return  false;
  }
  var data = {
    action: 'wppm_add_new_checklist_item',
    checklist_id: checklist_id,
    proj_id:proj_id,
    label: jQuery('#wppm_checklist_item_label_'+checklist_id).val()
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    if((proj_id!=0)||(proj_id!="")){
      wppm_open_project_tasks(task_id,proj_id);
    }else if((proj_id==0)||(proj_id=="")){
      wppm_open_task(task_id);
    }
  });
}

function wppm_delete_checklist(checklist_id,task_id,proj_id){
  const flag = confirm(wppm_admin.confirm);
  if (!flag) return;
  var data = {
    action: 'wppm_delete_checklist',
    checklist_id: checklist_id
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    if((proj_id!=0)||(proj_id!="")){
      wppm_open_project_tasks(task_id,proj_id);
    }else if((proj_id==0)||(proj_id=="")){
      wppm_open_task(task_id);
    }
  });
}

function wppm_delete_checklist_item(item_id,checklist_id,proj_id,task_id){
  const flag = confirm(wppm_admin.confirm);
  if (!flag) return;
  var data = {
    action: 'wppm_remove_checklist_item',
    item_id:item_id,
    checklist_id: checklist_id
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    if((proj_id!=0)||(proj_id!="")){
      wppm_open_project_tasks(task_id,proj_id);
    }else if((proj_id==0)||(proj_id=="")){
      wppm_open_task(task_id);
    }
  });
}

function wppm_checklist_progress(checklist_id,item_id,task_id){
  var $checkboxes = jQuery('.wppm_checklist_item_'+checklist_id);
  var $progress = jQuery('#CheckProgress_'+checklist_id);
  var total = $checkboxes.length;
  var checked = jQuery('.wppm_checklist_item_'+checklist_id).filter(':checked').length;
  var progressWidth = (checked / total) * 100;
  var checked_item;
  jQuery('#CheckProgress_'+checklist_id).css('background','#5ba4cf');
  jQuery('#CheckProgress_'+checklist_id).css('width', progressWidth + '%');
  if (jQuery("#wppm_checklist_item_"+item_id).prop("checked")==true) { 
    checked_item=1;
  }else{
    checked_item=0;
  }
  var data = {
    action: 'wppm_set_checklist_progress',
    checklist_id: checklist_id,
    item_id:item_id,
    checked_item: checked_item
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    wppm_open_task(task_id);
  });
}

function wppm_open_individual_task(id){
  jQuery('#wppm_project_container').show();
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_open_task',
    id:id
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_project_container').html(response);
  });
}

function wppm_display_grid_view(){
  var task_search = jQuery("#wppm_view_task_search_filter").val();
  jQuery('#wppm_task_container').show();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
        action: 'wppm_get_task_list_card_view',
        task_search: task_search
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_task_container').html(response);
  });
}

function wppm_remove_filter(id){
  jQuery('#wppm_user_display_container_'+id).remove();
}

function wppm_remove_user_from_project(id){
  jQuery('#wppm_user_container_'+id).remove();
}

function wppm_remove_task_user_filter(id){
  jQuery('#wppm_task_user_display_container_'+id).remove();
}

function wppm_cancel_comment(){
  tinyMCE. activeEditor. setContent('');
  tinymce.remove('#wppm_task_comment');
  jQuery('#wppm_comment_btn').hide();
}

function wppm_submit_task_comment(id,proj_id){
  var dataform = new FormData(jQuery('#wppm_open_task')[0]);
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  dataform.append('action','wppm_submit_task_comment');
  var comment_body = tinyMCE.get('wppm_task_comment').getContent().trim();
  dataform.append('comment_body',comment_body);
  dataform.append('task_id',id);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    if((proj_id!=0)||(proj_id!="")){
      wppm_open_project_tasks(id,proj_id);
    }else if((proj_id==0)||(proj_id=="")){
      wppm_open_task(id);
    }
  });
}

function wppm_attachment_cancel( obj ){
  jQuery(obj).parent().remove();
}

function wppm_get_en_general_setting(){
  jQuery('.wppm_setting_pills li').removeClass('active');
  jQuery('#wppm_en_setting_general').addClass('active');
  jQuery('.wppm_setting_col2').html(wppm_admin.loading_html);
  
  var data = {
    action: 'wppm_get_en_general_setting',
  };

  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('.wppm_setting_col2').html(response);
  });
}

function wppm_set_en_general_settings(){
  jQuery('.wppm_submit_wait').show();
  var dataform = new FormData(jQuery('#wppm_en_frm_general_settings')[0]);
  
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    var response = JSON.parse(response_str);
    jQuery('.wppm_submit_wait').hide();
    if (response.sucess_status=='1') {
      jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
    }
    jQuery('#wppm_alert_success').slideDown('fast',function(){});
    setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 3000);
  });
}

function wppm_get_en_task_notifications(){
  jQuery('.wppm_setting_pills li').removeClass('active');
  jQuery('#wppm_en_ticket_notifications').addClass('active');
  jQuery('.wppm_setting_col2').html(wppm_admin.loading_html);
  
  var data = {
    action: 'wppm_get_en_task_notifications'
  };

  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('.wppm_setting_col2').html(response);
  });
}

function wppm_edit_task_status(id,proj_id){
  wppm_modal_open('Edit'); 
  var data = {
    action: 'wppm_edit_task_status',
    task_id: id,
    proj_id:proj_id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_edit_task_users(task_id,proj_id){
  wppm_modal_open('Add Users'); 
  var data = {
    action: 'wppm_get_task_users',
    task_id: task_id,
    proj_id:proj_id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_edit_task_details(id,proj_id){
  wppm_modal_open('Edit Task Details'); 
  var data = {
    action: 'wppm_edit_task_details',
    task_id: id,
    proj_id:proj_id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_set_change_task_status(task_id,proj_id){
  var dataform = new FormData(jQuery('#frm_get_task_change_status')[0]);
  wppm_modal_close();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    if((proj_id!=0)||(proj_id!="")){
      wppm_open_project_tasks(task_id,proj_id);
    }else if((proj_id==0)||(proj_id=="")){
      wppm_open_task(task_id);
    }
    
  }); 
}

function wppm_edit_task_creator(task_id,proj_id){
  wppm_modal_open('Edit Task Creator'); 
  var data = {
    action: 'wppm_edit_task_creator',
    task_id: task_id,
    proj_id: proj_id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_set_change_raised_by(task_id,proj_id){
  var dataform = new FormData(jQuery('#frm_get_task_creator')[0]);
  wppm_modal_close();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    if((proj_id!=0)||(proj_id!="")){
      wppm_open_project_tasks(task_id,proj_id);
    }else if((proj_id==0)||(proj_id=="")){
      wppm_open_task(task_id);
    }
  }); 
}

function wppm_set_change_task_details(task_id,proj_id){
  var dataform = new FormData(jQuery('#frm_get_edit_task_deatils')[0]);
  var description = tinyMCE.get('wppm_edit_task_description').getContent().trim();
  dataform.append('wppm_edit_task_description', description);
  wppm_modal_close();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    if((proj_id!=0)||(proj_id!="")){
      wppm_open_project_tasks(task_id,proj_id);
    }else if((proj_id==0)||(proj_id=="")){
      wppm_open_task(task_id);
    }
  });
}

function wppm_edit_thread(comment_id,task_id,proj_id){
  wppm_modal_open('Edit Thread'); 
  var data = {
    action: 'wppm_edit_task_thread',
    comment_id:comment_id,
    task_id: task_id,
    proj_id: proj_id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_set_edit_task_thread(task_id,proj_id){
  var dataform = new FormData(jQuery('#frm_edit_task_thread')[0]);
  var comment_body = tinyMCE.get('wppm_edit_task_thread_editor').getContent().trim();
  dataform.append('wppm_edit_task_thread', comment_body);
  wppm_modal_close();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    if((proj_id!=0)||(proj_id!="")){
      wppm_open_project_tasks(task_id,proj_id);
    }else if((proj_id==0)||(proj_id=="")){
      wppm_open_task(task_id);
    }
  }); 
}

function wppm_delete_thread(comment_id,task_id,proj_id){
  wppm_modal_open('Delete Thread'); 
  var data = {
    action: 'wppm_delete_task_thread',
    comment_id:comment_id,
    task_id: task_id,
    proj_id:proj_id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_set_delete_thread(task_id,proj_id){
  var dataform = new FormData(jQuery('#frm_delete_task_thread')[0]);
  wppm_modal_close();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    if((proj_id!=0)||(proj_id!="")){
      wppm_open_project_tasks(task_id,proj_id);
    }else if((proj_id==0)||(proj_id=="")){
      wppm_open_task(task_id);
    }
  }); 
}

function wppm_edit_proj_details(id){
  wppm_modal_open('Edit Project Details'); 
  var data = {
    action: 'wppm_edit_project_details',
    id: id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_set_change_project_details(id){
  var dataform = new FormData(jQuery('#frm_get_edit_project_deatils')[0]);
  var description = tinyMCE.get('wppm_edit_project_description').getContent().trim();
  dataform.append('wppm_edit_project_description', description);
  wppm_modal_close();
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    wppm_open_project(id);
  });
}

function wppm_edit_project_status(id){
  wppm_modal_open('Edit Project Status'); 
  var data = {
    action: 'wppm_edit_project_status',
    id: id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_set_change_project_status(id){
  var dataform = new FormData(jQuery('#frm_get_project_change_status')[0]);
  wppm_modal_close();
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    wppm_open_project(id);
  });
}

function wppm_edit_project_creator(id){
  wppm_modal_open('Edit Project Creator'); 
  var data = {
    action: 'wppm_edit_project_creator',
    id: id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_set_change_project_raised_by(id){
  var dataform = new FormData(jQuery('#frm_get_project_creator')[0]);
  wppm_modal_close();
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    wppm_open_project(id);
  });
}

function wppm_edit_project_users(id){
  wppm_modal_open('Add Users'); 
  var data = {
    action: 'wppm_get_project_users',
    id: id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_delete_project(id){
  wppm_modal_open('Delete Project'); 
  var data = {
    action: 'wppm_get_delete_project',
    id: id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_set_delete_project(){
  var dataform = new FormData(jQuery('#frm_delete_project')[0]);
  wppm_modal_close();
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
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

function wppm_get_delete_task(id){
  wppm_modal_open('Delete Task'); 
  var data = {
    action: 'wppm_get_delete_task',
    id: id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_set_delete_task(){
  var dataform = new FormData(jQuery('#frm_delete_task')[0]);
  wppm_modal_close();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    wppm_get_task_list();
  });
}

function wppm_get_edit_email_notification(id){
  jQuery('.wppm_setting_col2').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_edit_email_notification',
    id : id
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('.wppm_setting_col2').html(response);
  });
}

function wppm_get_tinymce(selector,body_id){ 
  jQuery('#visual').addClass('btn btn-primary');
  jQuery('#text').removeClass('btn btn-primary');
  jQuery('#text').addClass('btn btn-default');
  tinymce.init({ 
	  selector:'#'+selector,
	  body_id: body_id,
	  menubar: false,
		statusbar: false,
	  height : '200',
	  plugins: [
	      'lists link image directionality'
	  ],
	  image_advtab: true,
	  toolbar: 'bold italic underline blockquote | alignleft aligncenter alignright | bullist numlist | rtl | link image',
	  branding: false,
	  autoresize_bottom_margin: 20,
	  browser_spellcheck : true,
	  relative_urls : false,
	  remove_script_host : false,
	  convert_urls : true,
		setup: function (editor) {
	  }
	});
}

function wppm_get_textarea(){
  jQuery('#visual').removeClass('btn btn-primary');
  jQuery('#visual').addClass('btn btn-default');
  jQuery('#text').addClass('btn btn-primary');
  tinymce.remove();
}

function wppm_get_templates(){
  wppm_modal_open(wppm_admin.templates);
  var data = {
    action: 'wppm_get_templates'
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) {
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_set_edit_email_notification(){
  var dataform = new FormData(jQuery('#wppm_edit_email_notification_settings')[0]);
  wppm_modal_close();
  jQuery('.wppm_setting_col2').html(wppm_admin.loading_html);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    var response = JSON.parse(response_str);
    if (response.sucess_status=='1') {
      jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
    }
    jQuery('#wppm_alert_success').slideDown('fast',function(){});
    setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 3000);
    wppm_get_en_task_notifications();
  });
}

function wppm_set_general_settings(){
  jQuery('.wppm_submit_wait').show();
  var dataform = new FormData(jQuery('#wppm_frm_general_settings')[0]);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    var response = JSON.parse(response_str);
    jQuery('.wppm_submit_wait').hide();
    if (response.sucess_status=='1') {
      jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
    }
    jQuery('#wppm_alert_success').slideDown('fast',function(){});
    setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 3000);
  });
}

function wppm_get_project_tasks(id){
  wppm_modal_open('Tasks'); 
  var data = {
    action: 'wppm_get_project_tasks',
    id: id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_create_project_task(id){
  wppm_modal_open('Tasks'); 
  var data = {
    action: 'wppm_add_new_task',
    proj_id: id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    jQuery('#wppm_popup_body').html(response_str);
  });
}

function wppm_load_prev_page(prev_page_no,page_no,sort_by, order){
  if(prev_page_no!=0){
    page_no=prev_page_no-1;
		wppm_get_project_list(page_no,sort_by,order);
	}
}

function wppm_load_next_page(next_page_no,page_no,sort_by,order){
	if(next_page_no!=page_no){
    page_no = next_page_no;
	  wppm_get_project_list(page_no,sort_by,order);
	}
}

function wppm_load_prev_task_page(prev_page_no,page_no, sort_by, order){
  if(prev_page_no!=0){
    page_no=prev_page_no-1;
		wppm_get_task_list(page_no,sort_by,order);
	}
}

function wppm_load_next_task_page(next_page_no,page_no,sort_by,order){
	if(next_page_no!=page_no){
    page_no = next_page_no;
	  wppm_get_task_list(page_no,sort_by,order);
	}
}

function wppm_load_prev_task_page_card_view(prev_page_no,page_no){
  if(prev_page_no!=0){
    page_no=prev_page_no-1;
		wppm_view_task_search_filter(page_no);
	}
}

function wppm_load_next_task_page_card_view(next_page_no,page_no){
	if(next_page_no!=page_no){
    page_no = next_page_no;
	  wppm_view_task_search_filter(page_no);
	}
}

function wppm_thread_attachment_remove(obj,attachment,comment_id,task_id,proj_id){
  if( confirm(wppm_admin.confirm) ){
   var data = {
     action: 'wppm_remove_thread_attachment',
     attachment : attachment,
     comment_id : comment_id,
     task_id:task_id
   };
   jQuery.post(wppm_admin.ajax_url, data, function(response) {
      jQuery(obj).parent('td').parent('tr').remove();
      if((proj_id!=0)||(proj_id!="")){
        wppm_open_project_tasks(task_id,proj_id);
      }else if((proj_id==0)||(proj_id=="")){
        wppm_open_task(task_id);
      }
    });
  }
}

function wppm_open_project(id){
  jQuery('#wppm_project_list').hide();
  jQuery('#wppm_project_container').show();
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_open_project',
    id:id
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_project_container').html(response);
  });
}

function wppm_project_search_filter(){
  var project_search = jQuery("#wppm_project_search_filter").val();
  jQuery('#wppm_project_container').show();
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  var data = {
      action: 'wppm_get_project_list',
      project_search:project_search
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_project_container').html(response);
  });
}

function wppm_clone_task(id){
  wppm_modal_open('Clone Task'); 
  var data = {
    action: 'wppm_clone_task',
    id: id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_set_clone_task(){
  var data = {
    action: 'wppm_set_clone_task',
    task_id: jQuery("input[name=task_id]").val(),
    task_name:jQuery("input[name=task_name]").val()
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    wppm_modal_close();
    wppm_get_task_list();
  });
}

function wppm_sort_up_task_list(sort_by,order){
  jQuery('#wppm_task_container').show();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_task_list',
    sort_by:sort_by,
    order:order
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_task_container').html(response);
  }); 
}

function wppm_sort_up_project_list(sort_by,order){
  jQuery('#wppm_project_container').show();
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_project_list',
    sort_by:sort_by,
    order:order
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_project_container').html(response);
  }); 
}

function wppm_get_miscellaneous_settings(){
  jQuery('.wppm_setting_pills li').removeClass('active');
  jQuery('#wppm_settings_miscellaneous').addClass('active');
  jQuery('.wppm_setting_col2').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_advanced_settings'
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('.wppm_setting_col2').html(response);
  });
}

function wppm_set_advanced_settings(){
  jQuery('.wppm_submit_wait').show();
  var dataform = new FormData(jQuery('#wppm_frm_advanced_settings')[0]);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response_str) {
    var response = JSON.parse(response_str);
    jQuery('.wppm_submit_wait').hide();
    if (response.sucess_status=='1') {
      jQuery('#wppm_alert_success .wppm_alert_text').text(response.messege);
    }
    jQuery('#wppm_alert_success').slideDown('fast',function(){});
    setTimeout(function(){ jQuery('#wppm_alert_success').slideUp('fast',function(){}); }, 3000);
  });
}

function wppm_get_project_visibility(id){
  wppm_modal_open('Change Visibility'); 
  var data = {
    action: 'wppm_get_project_visibility',
    id: id
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    var response = JSON.parse(response_str);
    jQuery('#wppm_popup_body').html(response.body);
    jQuery('#wppm_popup_footer').html(response.footer);
  });
}

function wppm_select_project_visibility(proj_id){
  if(jQuery('input[name="wppm_project_visibility_option"]').is(':checked')){
    var project_visibility = jQuery('input[name="wppm_project_visibility_option"]:checked').val();
  }else{
    var project_visibility = 0;
  }
  var data = {
    action: 'wppm_change_project_visibility',
    project_id:proj_id,
    project_visibility:project_visibility
  };
  jQuery.post(wppm_admin.ajax_url, data, function() {
  });
}

function wppm_apply_project_filter(){
  wppm_project_filter = jQuery('#wppm_project_filter').find(":selected").val();
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_project_list',
    wppm_project_filter:wppm_project_filter
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_project_container').html(response);
  }); 
}

function wppm_apply_task_filter(){
  wppm_task_filter = jQuery('#wppm_task_filter').find(":selected").val();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_task_list',
    wppm_task_filter:wppm_task_filter
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_task_container').html(response);
  }); 
}

function wppm_tl_reset_filter(){
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_task_list',
    wppm_task_filter:'all',
    task_search:"",
    sort_by:"task_name",
    order:"ASC"
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_task_container').html(response);
  }); 
}

function wppm_apply_task_filter_grid_view(){
  wppm_task_filter = jQuery('#wppm_task_filter').find(":selected").val();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_view_project_tasks',
    wppm_task_filter:wppm_task_filter
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_task_container').html(response);
  }); 
}

function wppm_tl_reset_grid_view_filter(){
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_view_project_tasks',
    wppm_task_filter:'all',
    task_search:"",
    sort_by:"task_name",
    order:"ASC"
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_task_container').html(response);
  }); 
}

function wppm_pl_reset_filter(){
  jQuery('#wppm_project_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_get_project_list',
    wppm_project_filter:'all',
    project_search:"",
    sort_by:"project_name",
    order:"ASC"
  };
  jQuery.post(wppm_admin.ajax_url, data, function(response) {
    jQuery('#wppm_project_container').html(response);
  }); 
}