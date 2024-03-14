jQuery('#wpforms-builder').on('wpformsBeforeSave', function($builder) {
  groups_data = [];
  form_id =   jQuery("#wpforms-builder-form").data("id");
  jQuery(".sg_email_marketing_groups").find(".item").each( function (index, item) { 
    groups_data.push( item.dataset.value )
  })
  jQuery.ajax({
    url: ajaxurl + '?action=sg_email_marketing_wpforms_save_post',
    method: 'POST',
    data: {
      'sg_email_marketing_groups' : JSON.stringify( groups_data ),
      'form_id' : form_id
    }
  });
});