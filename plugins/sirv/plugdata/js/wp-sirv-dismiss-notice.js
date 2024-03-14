jQuery(function($){
    $(document).ready(function(){
        $('.notice-dismiss').on('click', function(){
            const notice_id = $(this).closest('.sirv-admin-notice').attr('data-sirv-notice-id');
            const dismiss_type = $(this).closest('.sirv-admin-notice').attr('data-sirv-dismiss-type');
            const custom_time = $(this).closest('.sirv-admin-notice').attr('data-sirv-custom-time') || 0;

            if(!!notice_id){
                $.post(ajaxurl,{
                    action: 'sirv_dismiss_notice',
                    _ajax_nonce: sirv_dismiss_ajax_object.ajaxnonce,
                    notice_id : notice_id,
                    dismiss_type: dismiss_type,
                    custom_time: custom_time,
                }).done(function(response){
                    //debug
                    //console.log(response);

                }).fail(function(jqXHR, status, error){
                    console.error("Error during ajax request: " + error);
                });
            }
        });
    }); //domready end
});
