(function ($) {
    'use strict';

    var uploadNonce   = opalrealestate.uploadNonce;
    var fileTypeTitle = opalrealestate.fileTypeTitle;
    var ajaxURL       = opalrealestate.ajaxURL;

    function create_upload_container($container) {
        var button        = $container.data('button');
        var uploader      = new plupload.Uploader({
            browse_button : button,          // this can be an id of a DOM element or the DOM element itself
            file_data_name: 'upload_file',
            url           : opalrealestate.ajaxURL + "?action=opalrealestate_user_upload&nonce=" + uploadNonce,
            filters       : {
                mime_types        : [
                    {title: fileTypeTitle, extensions: "jpg,jpeg,gif,png"}
                ],
                max_file_size     : '10000kb',
                prevent_duplicates: true
            }
        });
        uploader.field    = $container.data('field');
        uploader.issingle = $container.data('issingle');
        uploader.init();

        if ($container.hasClass('single-upload') && $('.upload-item', $container).length >= 1) {
            $('#' + button).hide();
        }

        uploader.bind('FilesAdded', function (up, files) {
            var html  = '';
            var thumb = "";
            //   $current.removeClass('has-pending').show();
            var i     = 0;
            plupload.each(files, function (file) {
                var $i = $('<div id="holder-' + file.id + '" class="upload-item has-pending">' + '' + '</div>');
                $i.insertBefore($('#' + button))

            });

            up.refresh();
            uploader.start();
        });


        /* Run during upload */
        uploader.bind('UploadProgress', function (up, file) {
            var $current = $('#holder-' + file.id);
            if ($current) {
                if ($current.find('.upload-holder').length <= 0) {
                    $current.append('<div class="upload-holder"></div>');
                }
                $current.find('.upload-holder').css('width', file.percent + '%');
                if (file.percent == 100) {
                    //$current.find('.upload-holder').remove();
                }
            }
        });

        /* In case of error */
        uploader.bind('Error', function (up, err) {
            console.log(err);
            //  document.getElementById('errors-log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
        });


        /* If files are uploaded successfully */
        uploader.bind('FileUploaded', function (up, file, ajax_response) {
            var response = $.parseJSON(ajax_response.response);

            if (response.success) {

                var html = '<figure class="upload-thumbnail">' +
                    '<img src="' + response.url + '" alt="" />' +
                    '<a class="icon icon-delete" data-property-id="' + 0 + '"  data-attachment-id="' + response.attachment_id + '" href="javascript:;" ><i class="fa fa-trash-o"></i></a>' +
                    '<input type="hidden" class="image-id" name="' + uploader.field + '[' + response.attachment_id + ']" value="' + response.url + '"/>' +
                    '<span style="display: none;" class="icon icon-loader"><i class="fa fa-spinner fa-spin"></i></span>' +
                    '</figure>';


                $('#holder-' + file.id).html(html);

                if ($container.hasClass('single-upload') && $('.upload-item', $container).length >= 1) {
                    $('#' + button).hide();
                }
            } else {
                // log response object
                console.log(response);
            }
        });

    }

    $(document).ready(function () {
        $('.opalrealestate-user-upload .upload-container').each(function () {
            create_upload_container($(this));
        });
    });

})(jQuery);	