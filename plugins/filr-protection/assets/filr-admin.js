jQuery(document).ready(function ($) {

    // enable fileuploader plugin
    $('input[name="file-upload"]').fileuploader({
        changeInput: '<div class="fileuploader-input">' +
            '<div class="fileuploader-input-inner">' +
            '<div class="fileuploader-icon-main"></div>' +
            '<h3 class="fileuploader-input-caption"><span>${captions.feedback}</span></h3>' +
            '<p>${captions.or}</p>' +
            '<button type="button" class="fileuploader-input-button"><span>Browse files</span></button>' +
            '</div>' +
            '</div>',
        theme: 'dragdrop',
        upload: {
            url: ajax.ajax_url,
            data: { 'action': 'upload_file', 'post_id': ajax.post_id, 'nonce' : ajax.uploader_nonce },
            type: 'POST',
            enctype: 'multipart/form-data',
            start: true,
            synchron: true,
            beforeSend: '',
            onSuccess: function (result, item) {
                result = JSON.parse(result);

                // replace download url.
                $('.filr-download-link code' ).text(result.download_link);

                var data = {};

                // get data
                if (result)
                    data = result;
                else
                    data.hasWarnings = true;

                // if success
                if (data.isSuccess && data.files[0]) {
                    item.name = data.files[0].name;
                    item.html.find('.column-title > div:first-child').text(data.files[0].name).attr('title', data.files[0].name);
                }

                // if warnings
                if (data.hasWarnings) {
                    for (var warning in data.warnings) {
                        alert(data.warnings[warning]);
                    }

                    item.html.removeClass('upload-successful').addClass('upload-failed');
                    // go out from success function by calling onError function
                    // in this case we have a animation there
                    // you can also response in PHP with 404
                    return this.onError ? this.onError(item) : null;
                }

                item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');
                setTimeout(function () {
                    item.html.find('.progress-bar2').fadeOut(400);
                }, 400);
            },
            onError: function (item) {
                var progressBar = item.html.find('.progress-bar2');

                if (progressBar.length) {
                    progressBar.find('span').html(0 + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
                    item.html.find('.progress-bar2').fadeOut(400);
                }

                item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
                    '<button type="button" class="fileuploader-action fileuploader-action-retry" title="Retry"><i class="fileuploader-icon-retry"></i></button>'
                ) : null;
            },
            onProgress: function (data, item) {
                var progressBar = item.html.find('.progress-bar2');

                if (progressBar.length > 0) {
                    progressBar.show();
                    progressBar.find('span').html(data.percentage + "%");
                    progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");
                }
            },
            onComplete: null
        },
        onRemove: function (item) {
            $.ajax({
                type: "post",
                dataType: "json",
                url: ajax.ajax_url,
                data: { 'action': 'delete_file', 'post_id': ajax.post_id, 'filename': item.name, 'nonce' : ajax.uploader_nonce },
            });
        },
        captions: $.extend(true, {}, $.fn.fileuploader.languages['en'], {
            feedback: ajax.translations[0],
            feedback2: ajax.translations[0],
            drop: ajax.translations[0],
            or: ajax.translations[1],
            button: ajax.translations[2],
        }),
    });

    // if theres existing data append to list
    data = ajax.file_data;

    $(data.files).each(function () {
        var preview = '<div class="column-thumbnail"><div class="fileuploader-item-image fileuploader-no-thumbnail"><div style="background-color:#7200e5" class="fileuploader-item-icon"><i>' + this.extension + '</i></div></div><span class="fileuploader-action-popup"></span></div>';
        $('.fileuploader-items-list').append('<li class="fileuploader-item" data-file-name="' + this.name + '"><div class="columns">' + preview + '<div class="column-title"><div title="' + this.name + '">' + this.old_name + '</div><span>' + this.size2 + '</span></div><div class="column-actions"><button type="button" class="fileuploader-action fileuploader-action-remove" title="Delete" data-file-name="' + this.name + '"><i class="fileuploader-icon-remove"></i></button></div></div></li>');
    });

    // on click remove file from server.
    $('.fileuploader-action-remove').on('click', function () {
        var filename = $(this).attr('data-file-name');

        $.ajax({
            type: "post",
            dataType: "json",
            url: ajax.ajax_url,
            data: { 'action': 'delete_file', 'post_id': ajax.post_id, 'filename': filename, 'nonce' : ajax.uploader_nonce },
            success: function (response) {
                if (response.delete === true) {
                    $('.fileuploader-items-list').find("[data-file-name='" + filename + "']").remove();
                }
                // replace download url.
                $('.filr-download-link code' ).text(response.download_link);
            }
        });
    });

    /* premium indicator */
    $("input.premium").attr('disabled', 'disabled');
    var prem = $("input.premium").parent();
    $(prem).append('<span class="pro">PRO</span>');

    /* premium indicator */
    $("select.premium").attr('disabled', 'disabled');
    var prem = $("select.premium").parent();
    $(prem).append('<span class="pro">PRO</span>');
});