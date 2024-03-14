jQuery(document).ready(function() {
    if(jQuery('#publish').attr('name')==="publish" && jQuery('#pushalert_notification_enable').length>0){
        jQuery('#publish').click(function() {
            if(jQuery('#pushalert_notification_enable').is(":checked")){
                if(jQuery('#pushalert_notification_title').val()==="" || jQuery('#pushalert_notification_message').val()===""){
                    alert("PushAlert: Notification title and message cannot be empty!");
                    return false;
                }
            }
        });
    }

    jQuery('#pa_copy_title').click(function() {
        if(jQuery("input[name=post_title]").length>0){
            jQuery("#pushalert_notification_message").val(jQuery("input[name=post_title]").val());
        }
        else if(jQuery(".wp-block-post-title").length>0){
            jQuery("#pushalert_notification_message").val(jQuery(".wp-block-post-title").text());
        }
        else{
            jQuery("#pushalert_notification_message").val(jQuery("#post-title-0").val());
        }
    });
});
