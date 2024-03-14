jQuery(document).ready(function ($) {
    "use strict";
    /**
     * Form Submission
     * 
     * @since 1.0.0
     */
    $('body').on('submit', '.wpsf-subscription-form', function (e) {
        e.preventDefault();
        var selector = $(this);
        var form_alias = $(this).data('form-alias');
        var form_data = $(this).serialize();
        $.ajax({
            type: 'post',
            url: wpsf_frontend_obj.ajax_url,
            data: {
                form_data: form_data,
                _wpnonce: wpsf_frontend_obj.ajax_nonce,
                action: 'wpsf_form_process_action',
                form_alias: form_alias
            },
            beforeSend: function (xhr) {
                selector.find('.wpsf-form-message').slideUp(500);
                selector.find('.wpsf-form-loader-wraper').show();
            },
            success: function (res) {
                selector.find('.wpsf-form-loader-wraper').hide();
                res = $.parseJSON(res);
                if (res.status == 200) {
                    selector[0].reset();
                    selector.find('.wpsf-form-message').removeClass('wpsf-error').addClass('wpsf-success').html(res.message).slideDown(500);
                } else {
                    selector.find('.wpsf-form-message').removeClass('wpsf-success').addClass('wpsf-error').html(res.message).slideDown(500);
                }
            }
        });
    });

    /**
     * Popup Trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-popup-trigger', function () {
        var temp_popup_html = $(this).next('.wpsf-popup-innerwrap').clone();
        $('.wpsf-temp-popup-wrapper').append(temp_popup_html);
        $('.wpsf-temp-popup-wrapper .wpsf-popup-innerwrap').fadeIn(500);
    });
    /**
     * Popup Trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-popup-close, .wpsf-overlay', function () {
        $(this).closest('.wpsf-popup-innerwrap').fadeOut(500,function(){
            $('.wpsf-temp-popup-wrapper').html('');
        });
        
    });

    /**
     * Delay popup display trigger
     * 
     * @since 1.0.0
     */
    if ($('.wpsf-delay-popup').length > 0) {
        var delay = $('.wpsf-delay-popup').data('delay');
        if (delay == 0 || delay == '') {
            $('.wpsf-delay-popup').fadeIn(500);
        } else {
            setTimeout(function () {
                $('.wpsf-delay-popup').fadeIn(500);
            }, delay * 1000);
        }
    }
    /**
     * Prevents popup from being closed
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-form-wrap', function (e) {
        e.stopPropagation();
    });

});