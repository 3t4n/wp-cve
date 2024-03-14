jQuery(document).ready(function ($) {
    "use strict";
    /**
     *
     * @type object
     */
    var notice_timeout;

    /**
     * @type object
     */
    var translation_strings = wpsf_backend_obj.translation_strings;

    /**
     * Generates required notice
     *
     * @param {string} info_text
     * @param {string} info_type
     *
     */
    function wpsf_generate_info(info_text, info_type) {
        clearTimeout(notice_timeout);
        switch (info_type) {
            case 'error':
                var info_html = '<p class="wpsf-error">' + info_text + '</p>';
                break;
            case 'info':
                var info_html = '<p class="wpsf-info">' + info_text + '</p>';
                break;
            case 'ajax':
                var info_html = '<p class="wpsf-ajax"><img src="' + wpsf_backend_obj.plugin_url + 'images/ajax-loader.gif" class="wpsf-ajax-loader"/>' + info_text + '</p>';
            default:
                break;

        }
        $('.wpsf-form-message').html(info_html).show();
        if (info_type != 'ajax') {
            notice_timeout = setTimeout(function () {
                $('.wpsf-form-message').slideUp(1000);
            }, 5000);
        }

    }

    /**
     * Performs clipboard copy action
     * 
     * @param {object} element
     * @returns null
     */
    function wpsf_copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    function wpsf_title_to_alias(str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();

        // remove accents, swap ñ for n, etc
        var from = "àáäâèéëêìíïîòóöôùúüûñç·/,:;";
        var to = "aaaaeeeeiiiioooouuuunc------";
        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 _]/g, '') // remove invalid chars
                .replace(/\s+/g, '_') // collapse whitespace and replace by _
                .replace(/_+/g, '_'); // collapse dashes

        return str;
    }

    /**
     * Settings section show hide
     */
    $('body').on('click', '.wpsf-nav-item', function () {
        var tab = $(this).data('tab');
        $('.wpsf-nav-item').removeClass('wpsf-active-nav');
        $(this).addClass('wpsf-active-nav');
        $('.wpsf-settings-each-section').hide();
        $('.wpsf-settings-each-section[data-tab="' + tab + '"]').show();

    });

    /**
     * Form components slide toggle
     */

    $('body').on('click', '.wpsf-component-head h4', function () {
        $(this).closest('.wpsf-form-each-component').find('.wpsf-component-body').slideToggle();
        if ($(this).next('.dashicons').hasClass('dashicons-arrow-down')) {
            $(this).next('.dashicons').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
        } else {
            $(this).next('.dashicons').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
        }
    });

    /**
     * Open Media Uploader
     */
    $('body').on('click', '.wpsf-file-uploader', function () {
        var selector = $(this);

        var image = wp.media({
            title: translation_strings.upload_button_text,
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
                .on('select', function (e) {
                    // This will return the selected image from the Media Uploader, the result is an object
                    var uploaded_image = image.state().get('selection').first();
                    console.log(uploaded_image.toJSON());
                    // We convert uploaded_image to a JSON object to make accessing it easier
                    // Output to the console uploaded_image
                    var image_url = uploaded_image.toJSON().url;
                    var image_id = uploaded_image.toJSON().id;
                    // Let's assign the url value to the input field
                    selector.parent().find('input[type="text"]').val(image_url);
                    selector.parent().find('input[type="hidden"]').val(image_id);
                    if (selector.parent().find('.wpsf-image-preview').length > 0) {
                        selector.parent().find('.wpsf-image-preview').html('<img src="' + uploaded_image.toJSON().sizes.thumbnail.url + '"/>');
                    }
                });
    });

    /**
     * Shortcode generation through alias
     */
    $('body').on('keyup', '.wpsf-alias-field', function () {
        var alias = $(this).val();
        var shortcode = '[wp_subscription_forms alias="' + alias + '"]';
        $('.wpsf-shortcode-preview').html(shortcode);
    });



    /**
     * Subscription Form Settings submission
     * 
     */
    $('body').on('submit', '.wpsf-subscription-form', function (e) {
        e.preventDefault();
        var selector = $(this);
        var form_data = $(this).serialize();
        var form_action = ($(this).data('form-action')) ? $(this).data('form-action') : 'wpsf_form_save_action';
        $.ajax({
            type: 'post',
            url: wpsf_backend_obj.ajax_url,
            data: {
                action: form_action,
                _wpnonce: wpsf_backend_obj.ajax_nonce,
                form_data: form_data
            },
            beforeSend: function (xhr) {
                wpsf_generate_info(translation_strings.ajax_message, 'ajax');
            },
            success: function (res) {
                res = $.parseJSON(res);
                if (res.status == 200) {
                    wpsf_generate_info(res.message, 'info');
                    if (res.redirect_url) {
                        window.location = res.redirect_url;
                        exit;
                    }
                } else {
                    wpsf_generate_info(res.message, 'error');
                }
            }
        });
    });

    /**
     * Subscription Form Delete
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-form-delete', function () {
        if (confirm(translation_strings.delete_form_confirm)) {
            var selector = $(this);
            var form_id = $(this).data('form-id');
            $.ajax({
                type: 'post',
                url: wpsf_backend_obj.ajax_url,
                data: {
                    action: 'wpsf_form_delete_action',
                    form_id: form_id,
                    _wpnonce: wpsf_backend_obj.ajax_nonce,
                },
                beforeSend: function (xhr) {
                    wpsf_generate_info(translation_strings.ajax_message, 'ajax');
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        wpsf_generate_info(res.message, 'info');
                        selector.closest('tr').fadeOut(500, function () {
                            $(this).remove();
                        });
                    } else {
                        wpsf_generate_info(res.message, 'error');
                    }
                }
            });
        }
    });
    /**
     * Subscription Form Copy
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-form-copy', function () {
        if (confirm(translation_strings.copy_form_confirm)) {
            var selector = $(this);
            var form_id = $(this).data('form-id');
            $.ajax({
                type: 'post',
                url: wpsf_backend_obj.ajax_url,
                data: {
                    action: 'wpsf_form_copy_action',
                    form_id: form_id,
                    _wpnonce: wpsf_backend_obj.ajax_nonce,
                },
                beforeSend: function (xhr) {
                    wpsf_generate_info(translation_strings.ajax_message, 'ajax');
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        wpsf_generate_info(res.message, 'info');
                        wpsf_generate_info(res.message, 'info');
                        if (res.redirect_url) {
                            window.location = res.redirect_url;
                            exit;
                        }
                    } else {
                        wpsf_generate_info(res.message, 'error');
                    }
                }
            });
        }
    });

    /**
     * Subscriber alias filter
     * 
     * @since 1.0.0
     */
    $('body').on('change', '.wpsf-export-alias-trigger', function () {
        $(this).closest('form').submit();
    });

    /**
     * Shortcode clipboard copy
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-clipboard-copy', function () {
        var copy_element = $(this).parent().find('.wpsf-shortcode-preview').select();
        wpsf_copyToClipboard(copy_element);
        wpsf_generate_info(translation_strings.clipboad_copy_message, 'info');
    });

    /**
     * Show hide toggle
     * 
     * @since 1.0.0
     */
    $('body').on('change', '.wpsf-toggle-trigger', function () {

        var toggle_ref = $(this).val();
        var toggle_class = $(this).data('toggle-class');
        $('.' + toggle_class).hide();
        $('.' + toggle_class + '[data-toggle-ref="' + toggle_ref + '"]').show();

    });

    /**
     * Color Picker Initialization
     * 
     * @since 1.0.0
     */
    $('.wpsf-color-picker').wpColorPicker();

    $('.wpsf-field input[type="checkbox"]').each(function () {
        if (!$(this).parent().hasClass('wpsf-checkbox-toggle') && !$(this).hasClass('wpsf-disable-checkbox-toggle')) {
            var input_name = $(this).attr('name');
            $(this).parent().addClass('wpsf-checkbox-toggle');
            $('<label></label>').insertAfter($(this));
        }
    });


    /**
     * Form save trigger 
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-form-save-trigger', function () {
        $('.wpsf-subscription-form').submit();
    });

    /**
     * Mailchimp connect trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-mailchimp-connect', function () {
        $('.wpsf-mailchimp-api-response').slideUp(500);
        var mailchimp_api_key = $('.wpsf-mailchimp-api-key').val();
        var api_pattern = /^[0-9a-zA-Z*]{32}-[a-z]{2}[0-9]{1,2}$/;
        if (api_pattern.test(mailchimp_api_key)) {
            $.ajax({
                type: 'post',
                url: wpsf_backend_obj.ajax_url,
                data: {
                    action: 'wpsf_mailchimp_connect_action',
                    api_key: mailchimp_api_key,
                    _wpnonce: wpsf_backend_obj.ajax_nonce,
                },
                beforeSend: function (xhr) {
                    wpsf_generate_info(translation_strings.ajax_message, 'ajax');
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        wpsf_generate_info(res.message, 'info');
                        wpsf_generate_info(res.message, 'info');
                        var list_template = wp.template('mc-lists');
                        $('.wpsf-mailchimp-lists-wrap').html(list_template(res.api_response));
                        $('.wpsf-mailchimp-lists-input').val(res.api_raw_response);
                        $('.wpsf-mailchimp-status-flag').val(1);
                        $('.wpsf-mailchimp-status').html(translation_strings.mc_connect);
                        $('.wpsf-mailchimp-status').removeClass('wpsf-mc-disconnected').addClass('wpsf-mc-connected');
                    } else {
                        wpsf_generate_info(res.message, 'error');
                        $('.wpsf-mailchimp-lists-wrap').html('');
                        $('.wpsf-mailchimp-lists-input').val('');
                        $('.wpsf-mailchimp-status-flag').val(0);
                        $('.wpsf-mailchimp-status').html(translation_strings.mc_disconnect);
                        $('.wpsf-mailchimp-status').removeClass('wpsf-mc-connected').addClass('wpsf-mc-disconnected');
                        if (res.api_response) {
                            $('.wpsf-mailchimp-api-response').html(JSON.stringify(res.api_response)).show();
                        }
                    }
                }
            });
        } else {
            wpsf_generate_info(translation_strings.invalid_api_key, 'error');
        }
    });

    /**
     * Resets mailchimp connection trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-mailchimp-reset', function () {
        if (confirm(translation_strings.mc_reset)) {
            $('.wpsf-mailchimp-lists-wrap').html('');
            $('.wpsf-mailchimp-lists-input').val('');
            $('.wpsf-mailchimp-status-flag').val(0);
            $('.wpsf-mailchimp-status').html(translation_strings.mc_disconnect);
            $('.wpsf-mailchimp-status').removeClass('wpsf-mc-connected').addClass('wpsf-mc-disconnected');
        }
    });

    $('body').on('click', '.wpsf-checkbox-toggle-trigger', function () {
        var toggle_class = $(this).data('toggle-class');
        var toggle_type = ($(this).data('toggle-type')) ? $(this).data('toggle-type') : 'on';
        switch (toggle_type) {
            case 'on':
                if ($(this).is(':checked')) {
                    $('.' + toggle_class).show();
                } else {
                    $('.' + toggle_class).hide();
                }
                break;
            case 'off':
                if ($(this).is(':checked')) {
                    $('.' + toggle_class).hide();
                } else {
                    $('.' + toggle_class).show();

                }
                break;
        }

    });

    /**
     * Log clear trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-clear-log-trigger', function () {
        $(this).parent().find('textarea').val('');
    });

    /**
     * Constant Contact connect trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-constant_contact-connect', function () {
        $('.wpsf-constant_contact-api-response').slideUp(500);
        var cc_api_key = $('.wpsf-constant_contact-api-key').val();
        var cc_access_token = $('.wpsf-constant_contact-access-token').val();
        $.ajax({
            type: 'post',
            url: wpsf_backend_obj.ajax_url,
            data: {
                action: 'wpsf_constant_contact_connect_action',
                api_key: cc_api_key,
                access_token: cc_access_token,
                _wpnonce: wpsf_backend_obj.ajax_nonce,
            },
            beforeSend: function (xhr) {
                wpsf_generate_info(translation_strings.ajax_message, 'ajax');
            },
            success: function (res) {
                res = $.parseJSON(res);
                if (res.status == 200) {
                    wpsf_generate_info(res.message, 'info');
                    wpsf_generate_info(res.message, 'info');
                    var list_template = wp.template('mc-lists');
                    $('.wpsf-constant_contact-lists-wrap').html(list_template(res.api_response));
                    $('.wpsf-constant_contact-lists-input').val(res.api_raw_response);
                    $('.wpsf-constant_contact-status-flag').val(1);
                    $('.wpsf-constant_contact-status').html(translation_strings.mc_connect);
                    $('.wpsf-constant_contact-status').removeClass('wpsf-cc-disconnected').addClass('wpsf-cc-connected');
                } else {
                    wpsf_generate_info(res.message, 'error');
                    $('.wpsf-constant_contact-lists-wrap').html('');
                    $('.wpsf-constant_contact-lists-input').val('');
                    $('.wpsf-constant_contact-status-flag').val(0);
                    $('.wpsf-constant_contact-status').html(translation_strings.mc_disconnect);
                    $('.wpsf-constant_contact-status').removeClass('wpsf-cc-connected').addClass('wpsf-cc-disconnected');
                    if (res.api_response) {
                        $('.wpsf-constant_contact-api-response').html(JSON.stringify(res.api_response)).show();
                    }
                }
            }
        });

    });

    /**
     * Resets constant contact connection trigger
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-constant_contact-reset', function () {
        if (confirm(translation_strings.cc_reset)) {
            $('.wpsf-constant_contact-lists-wrap').html('');
            $('.wpsf-constant_contact-lists-input').val('');
            $('.wpsf-constant_contact-status-flag').val(0);
            $('.wpsf-constant_contact-status').html(translation_strings.mc_disconnect);
            $('.wpsf-constant_contact-status').removeClass('wpsf-cc-connected').addClass('wpsf-cc-disconnected');
        }
    });

    /**
     * Deletes subscriber
     * 
     * @since 1.0.0
     */
    /**
     * Subscription Form Delete
     * 
     * @since 1.0.0
     */
    $('body').on('click', '.wpsf-subscriber-delete', function () {
        if (confirm(translation_strings.delete_subscriber_confirm)) {
            var selector = $(this);
            var subscriber_id = $(this).data('subscriber-id');
            $.ajax({
                type: 'post',
                url: wpsf_backend_obj.ajax_url,
                data: {
                    action: 'wpsf_subscriber_delete_action',
                    subscriber_id: subscriber_id,
                    _wpnonce: wpsf_backend_obj.ajax_nonce,
                },
                beforeSend: function (xhr) {
                    wpsf_generate_info(translation_strings.ajax_message, 'ajax');
                },
                success: function (res) {
                    res = $.parseJSON(res);
                    if (res.status == 200) {
                        wpsf_generate_info(res.message, 'info');
                        selector.closest('tr').fadeOut(500, function () {
                            $(this).remove();
                        });
                    } else {
                        wpsf_generate_info(res.message, 'error');
                    }
                }
            });
        }
    });

    $('body').on('keyup', 'input[name="form_title"]', function () {
        var form_title = $(this).val();
        var form_alias = wpsf_title_to_alias(form_title);
        if ($('input[name="form_alias"]').attr('readonly') != 'readonly') {
            $('input[name="form_alias"]').val(form_alias);

        }
    });

    $('body').on('keyup', 'input[name="form_alias"]', function () {
        var form_alias = $(this).val();
        form_alias = wpsf_title_to_alias(form_alias);

        if ($(this).attr('readonly') != 'readonly') {
            $(this).val(form_alias);

        }
    });

    $('body').on('click', '.wpsf-alias-force-edit', function () {
        $(this).parent().find('input[type="text"]').removeAttr('readonly');
    });
    
    $('body').on('click','.wpsf-domain-add-trigger',function(){
       var domain_template = wp.template('email-domain');
       $('.wpsf-unallowed-email-domains-list').append(domain_template());
    });
    
    $('body').on('click','.wpsf-remove-domain-trigger',function(){
       $(this).parent().remove(); 
    });


});


