jQuery(document).ready(function() {
    jQuery('.pa-receive-notification button[name="pa-rec-notf-yes"]').click(function(){
        jQuery.post(
            pa_ajax.ajax_url, 
            {
                'action': 'associate_pushalert',
                'pa_receive_notification_nonce_field': jQuery('input[name="pushalert_action_nonce_field"]').val(),
                'user_action': 'yes'
            }, 
            function(response){
                //console.log('The server responded: ' + response);
                jQuery('input[name=pa-dashboard-enable-notification]').attr('checked', 'checked');
            }
        );
        jQuery('.pa-receive-notification').remove();
    });
    
    jQuery('.pa-receive-notification button[name="pa-rec-notf-no"]').click(function(){
        jQuery.post(
            pa_ajax.ajax_url, 
            {
                'action': 'associate_pushalert',
                'pa_receive_notification_nonce_field': jQuery('input[name="pushalert_action_nonce_field"]').val(),
                'user_action': 'no'
            }, 
            function(response){
                //console.log('The server responded: ' + response);
            }
        );
        jQuery('.pa-receive-notification').remove();
    });
    
    jQuery('input[name=pa-dashboard-enable-notification]').change(function(){        
        var enabled = '';
        if(jQuery('input[name=pa-dashboard-enable-notification]').is(':checked')){
            enabled = 'yes';
            if(PushAlertCo.subs_id==""){
                PushAlertCo.forceSubscribe();
                return;
            }
        }
        else{
            enabled = 'delete';
            if(PushAlertCo.subs_id==""){
                return;
            }
        }

        jQuery.post(
            pa_ajax.ajax_url, 
            {
                'action': 'associate_pushalert',
                'pa_receive_notification_nonce_field': jQuery('input[name="pushalert_action_nonce_field"]').val(),
                'user_action': enabled
            }, 
            function(response){
                //console.log('The server responded: ' + response);
            }
        );
    });
});

(pushalertbyiw = window.pushalertbyiw || Array()).push(['onSuccess', PACallbackOnSuccess]);
function PACallbackOnSuccess(result) {
    if(!result.alreadySubscribed){
        jQuery.post(
            pa_ajax.ajax_url, 
            {
                'action': 'associate_pushalert',
                'pa_receive_notification_nonce_field': jQuery('input[name="pushalert_action_nonce_field"]').val(),
                'user_action': 'yes',
                'action_from': 'pushalert'
            }, 
            function(response){
                //console.log('The server responded: ' + response);
                jQuery('input[name=pa-dashboard-enable-notification]').attr('checked', 'checked');
            }
        );
    }
}

(pushalertbyiw = window.pushalertbyiw || Array()).push(['onFailure', PACallbackOnFailure]);
function PACallbackOnFailure(result) {
    if(result.now){
        jQuery.post(
            pa_ajax.ajax_url, 
            {
                'action': 'associate_pushalert',
                'pa_receive_notification_nonce_field': jQuery('input[name="pushalert_action_nonce_field"]').val(),
                'user_action': 'delete',
                'action_from': 'pushalert'
            }, 
            function(response){
                //console.log('The server responded: ' + response);
                jQuery('input[name=pa-dashboard-enable-notification]').removeAttr('checked');
            }
        );
    }
}