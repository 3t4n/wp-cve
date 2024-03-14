function wppm_sign_in(){
    jQuery('#wppm_project_container').show();
    jQuery('#wppm_project_container').html(wppm_admin.loading_html);
    var data = {
      action: 'wppm_sign_in'
    }
    jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
      jQuery('#wppm_project_container').html(response_str);
    }) 
}

function wppm_task_sign_in(){
  jQuery('#wppm_task_container').show();
  jQuery('#wppm_task_container').html(wppm_admin.loading_html);
  var data = {
    action: 'wppm_sign_in'
  }
  jQuery.post(wppm_admin.ajax_url, data, function(response_str) { 
    jQuery('#wppm_task_container').html(response_str);
  }) 
}

function wppm_sign_in_frm(){
  var dataform = new FormData(jQuery('#frm_wppm_sign_in')[0]);
  jQuery.ajax({
    url: wppm_admin.ajax_url,
    type: 'POST',
    data: dataform,
    processData: false,
    contentType: false
  })
  .done(function (response) {
    if (response.error == '1') {
      jQuery('#wppm_message_login').html(response.message);
      jQuery('#wppm_message_login').attr('class','bg-danger').slideDown('fast',function(){});
      jQuery('#frm_wppm_sign_in').find('input,button').attr('disabled',false);
      jQuery('#frm_wppm_sign_in').find('#inputPassword').val('');
      setTimeout(function(){ jQuery('#wppm_message_login').slideUp('fast',function(){}); }, 3000);
    } else {
      jQuery('#wppm_message_login').html(response.message);
      jQuery('#wppm_message_login').attr('class','bg-success').slideDown('fast',function(){});
      location.reload();
    }
  });
}