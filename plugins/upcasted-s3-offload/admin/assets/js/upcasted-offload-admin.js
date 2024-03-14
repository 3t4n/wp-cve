(function ($) {
    'use strict';

    function ajax_call(data, success, error) {
        jQuery.ajax({
            url: upcasted_offload_s3_params.ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                success(response);
            },
            error: function (response) {
                error(response);
            }
        });
    }

    function close_upcasted_modal() {
        const modal = $('.upcasted-modal');
        $('.upcasted-modal-content').on('click', function () {
            event.stopPropagation();
        });
        modal.on('click', function () {
            modal.addClass('hidden');
        });
        $('.upcasted-close-modal-button').on('click', function () {
            modal.addClass('hidden');
        });
    }

    function on_region_change() {
        $("#upcasted_offload_select_region").change(function () {
            var value = $(this).children(':selected').val();
            $("#upcasted_offload_region").focus().val(value);
        });
    }

    function trigger_upcasted_save_settings() {
        $('#upcasted-save-settings, #change-current-bucket').on('click', function () {
            const access_key_id = $("input[name='upcasted_s3_offload_access_key_id']");
            const secret_access_key = $("input[name='upcasted_s3_offload_secret_access_key']");
            const region = $("input[name='upcasted_offload_region']");
            const custom_endpoint = $("input[name='upcasted_custom_endpoint']");

            $('.upcasted-missing-mandatory-fields').remove();
            check_mandatory_field(access_key_id);
            check_mandatory_field(secret_access_key);
            if ('' === custom_endpoint.val()) {
                check_mandatory_field(region);
            }
            init_modal();
            if (0 === $('.upcasted-missing-mandatory-fields').size()) {
                if ($(this).attr('id') !== 'change-current-bucket') {
                    change_bucket_event(access_key_id, secret_access_key, region, custom_endpoint);
                } else {
                    if (confirm('!WARNING: Changing your bucket can break your file delivery because you can only serve files from one bucket. Are you sure that you want to change the bucket?')) {
                        change_bucket_event(access_key_id, secret_access_key, region, custom_endpoint);
                    } else {
                        $('.upcasted-tools-container ').removeClass('hidden');
                    }
                }
            }

        });
    }

    function change_bucket_event(access_key_id, secret_access_key, region, custom_endpoint) {
        ajax_call({
            'action': 'upcasted_offload_connect',
            'access_key_id': access_key_id.val(),
            'secret_access_key': secret_access_key.val(),
            'region': region.val(),
            'custom_endpoint': custom_endpoint.val()
        }, function (response) {
            if (Array.isArray(response)) {
                $('#select-bucket-modal').removeClass('hidden');
                $('.upcasted-modal-result').removeClass('hidden');
                $.each(response, function (index, value) {
                    $('.upcasted-buckets-list').append($("<option />").text(value));
                });
            } else {
                display_modal_error(response);
            }
        }, function (response) {
            display_modal_error(response);
        });
    }

    function trigger_upcasted_save_bucket() {
        $('#upcasted-save-bucket').on('click', function () {
            const bucket = $("select[name='upcasted_s3_offload_bucket']");
            check_mandatory_field(bucket);
            if (0 === $('.upcasted-missing-mandatory-fields').size()) {
                select_bucket_event(bucket.val());
            }
        });
    }

    function select_bucket_event(bucket) {

        const access_key_id = $("input[name='upcasted_s3_offload_access_key_id']");
        const secret_access_key = $("input[name='upcasted_s3_offload_secret_access_key']");
        const included_filetypes = $("select[name='upcasted_s3_offload_included_filetypes']");
        const remove_file_from_local = $("select[name='upcasted-delete-local-file'] option:selected");
        const remove_file_from_s3 = $("select[name='upcasted-delete-s3-file'] option:selected");
        const region = $("input[name='upcasted_offload_region']");
        const custom_endpoint = $("input[name='upcasted_custom_endpoint']");

        ajax_call({
            'action': 'upcasted_init',
            'access_key_id': access_key_id.val(),
            'secret_access_key': secret_access_key.val(),
            'custom_endpoint': custom_endpoint.val(),
            'region': region.val(),
            'bucket': bucket,
            'included_filetypes': included_filetypes.val(),
            'remove_file_from_local': remove_file_from_local.val(),
            'remove_file_from_s3': remove_file_from_s3.val()
        }, function (response) {
            $('.upcasted-tools-container').removeClass('hidden');
            $('.upcasted-modal').addClass('hidden');
            remove_active_buttons();
            $('.upcasted-current-bucket span strong').text(bucket);
        }, function (response) {
            display_modal_error(response);
        })
    }

    function trigger_upcasted_create_bucket() {
        $('#upcasted-create-bucket').on('click', function () {
            const bucket_name = $("input[name='upcasted_created_bucket']").val();
            if ('' !== bucket_name) {
                ajax_call({'action': 'upcasted_create_bucket', 'bucket_name': bucket_name},
                    function (response) {
                        if ('Success' != response) {
                            display_modal_error(response);
                        } else {
                            select_bucket_event(bucket_name);
                        }
                    }, function (response) {
                        display_modal_error(response);
                    });
            }
        });
    }

    function display_error(message) {
        const error_div = $('.upcasted-tools-error');
        error_div.removeClass('hidden');
        error_div.text(message);
    }

    function remove_error() {
        $('.upcasted-tools-error').addClass('hidden');
    }

    function display_modal_error(response) {
        $('#select-bucket-modal').removeClass('hidden');
        const error = $('.upcasted-modal-error');
        error.removeClass('hidden');
        error.html(undefined !== response.responseText ? response.responseText : response);
        $('.upcasted-tools-container').addClass('hidden');
    }

    function check_mandatory_field(field) {
        if ('' === field.val()) {
            $('<div class="upcasted-missing-mandatory-fields">Missing mandatory field</div>').insertAfter(field);
        }
    }

    function init_modal() {
        $('#select-bucket-modal').addClass('hidden');
        $('.upcasted-modal-result').addClass('hidden');
        $('.upcasted-modal-error').addClass('hidden');
        $('.upcasted-tools-container').addClass('hidden')
        $('.upcasted-buckets-list option').remove();
    }

    function remove_active_buttons() {
        $('#local-to-s3-button').removeClass('upcasted-active-button');
        $('#s3-to-local-button').removeClass('upcasted-active-button');
    }

    

    $(document).ready(function () {
        trigger_upcasted_create_bucket();
        trigger_upcasted_save_bucket();
        on_region_change();
        close_upcasted_modal();
        trigger_upcasted_save_settings();
        
    });

})
(jQuery);
