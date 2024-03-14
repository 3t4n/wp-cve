jQuery( document ).ready(function(){
    let wrld_notice = '';

    jQuery('.wrld-admin-notice-action').on('click', function() {
        wrld_notice     = jQuery(this).closest('.wrld-admin-notice');
        let wp_nonce    = wrld_notice.attr('wp_nonce');
        let user_action = jQuery(this).attr('id');
        let blur_index  = 'blur(1px)';
        wrld_notice.css('filter',blur_index).css('webkitFilter',blur_index).css('mozFilter',blur_index).css('oFilter',blur_index)
        .css('msFilter',blur_index);
        wrld_notice.css('cursor', 'progress');

        jQuery.ajax({
            method: "POST",
            url: wrld_modal_script_object.wp_ajax_url,
            data: {
                action: 'wrld_notice_action',
                wp_nonce:wp_nonce,
                user_action:user_action,
            },
            success: function(result) {
               jQuery(wrld_notice).hide(200);
            }, error: function(jqXHR, textStatus, ex) {
                blur_index  = 'blur(0px)';
                wrld_notice.css('filter',blur_index).css('webkitFilter',blur_index).css('mozFilter',blur_index).css('oFilter',blur_index).css('msFilter',blur_index);
            wrld_notice.css('cursor', 'none');
            }
        });
    });

    jQuery('.wrld-notification-close').on('click', function() {
        wrld_notice     = jQuery(this).closest('.wrld-admin-notice');
        let wp_nonce    = wrld_notice.attr('wp_nonce');
        let user_action = 'close';
        let blur_index  = 'blur(1px)';
        wrld_notice.css('filter',blur_index).css('webkitFilter',blur_index).css('mozFilter',blur_index).css('oFilter',blur_index)
        .css('msFilter',blur_index);
        wrld_notice.css('cursor', 'progress');
        jQuery.ajax({
            method: "POST",
            url: wrld_modal_script_object.wp_ajax_url,
            data: {
                action: 'wrld_notice_action',
                wp_nonce:wp_nonce,
                user_action:user_action,
            },
            success: function(result) {
               jQuery(wrld_notice).hide(200);
            }, error: function(jqXHR, textStatus, ex) {
                blur_index  = 'blur(0px)';
                wrld_notice.css('filter',blur_index).css('webkitFilter',blur_index).css('mozFilter',blur_index).css('oFilter',blur_index).css('msFilter',blur_index);
            wrld_notice.css('cursor', 'none');
            }
        });
    });
});