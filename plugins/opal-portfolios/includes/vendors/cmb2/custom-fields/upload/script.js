(function ($) {

    var uploadNonce   = opalrealestate.uploadNonce;
    var fileTypeTitle = opalrealestate.fileTypeTitle;
    var ajaxURL       = opalrealestate.ajaxURL;

    if ($("#select-images").length > 0) {
        /* initialize uploader */
        var uploader = new plupload.Uploader({
            browse_button : 'select-images',          // this can be an id of a DOM element or the DOM element itself
            file_data_name: 'property_upload_file',
            container     : 'plupload-container',
            drop_element  : 'drag-and-drop',
            url           : opalrealestate.ajaxURL + "?action=opalrealestate_upload_images&nonce=" + uploadNonce,
            filters       : {
                mime_types        : [
                    {title: fileTypeTitle, extensions: "jpg,jpeg,gif,png"}
                ],
                max_file_size     : '10000kb',
                prevent_duplicates: true
            }
        });
        uploader.init();

        uploader.bind('FilesAdded', function (up, files) {
            var html          = '';
            var propertyThumb = "";
            plupload.each(files, function (file) {
                propertyThumb += '<div id="holder-' + file.id + '" class="property-thumb">' + '' + '</div>';
            });
            document.getElementById('property-thumbs-container').innerHTML += propertyThumb;
            up.refresh();
            uploader.start();
        });


        /* Run during upload */
        uploader.bind('UploadProgress', function (up, file) {
            document.getElementById("holder-" + file.id).innerHTML = '<span>' + file.percent + "%</span>";
        });

        /* In case of error */
        uploader.bind('Error', function (up, err) {
            document.getElementById('errors-log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
        });


        /* If files are uploaded successfully */
        uploader.bind('FileUploaded', function (up, file, ajax_response) {
            var response = $.parseJSON(ajax_response.response);

            if (response.success) {

                var proppertyThumbHtml = '<div class="col-sm-3">' +
                    '<figure class="gallery-thumbnail">' +
                    '<img src="' + response.url + '" alt="" />' +
                    '<a class="icon icon-delete" data-property-id="' + 0 + '"  data-attachment-id="' + response.attachment_id + '" href="javascript:;" ><i class="fa fa-trash-o"></i></a>' +
                    '<a class="icon icon-fav icon-featured" data-property-id="' + 0 + '"  data-attachment-id="' + response.attachment_id + '" href="javascript:;" ><i class="fa fa-star-o"></i></a>' +
                    '<input type="hidden" class="propperty-image-id" name="propperty_image_ids[]" value="' + response.attachment_id + '"/>' +
                    '<span style="display: none;" class="icon icon-loader"><i class="fa fa-spinner fa-spin"></i></span>' +
                    '</figure>' +
                    '</div>';

                document.getElementById("holder-" + file.id).innerHTML = proppertyThumbHtml;

            } else {
                // log response object
                console.log(response);
            }
        });

        ////
        $("#property-thumbs-container").delegate('.icon-featured', 'click', function () {

            var $input = $('#property-thumbs-container .featured-image-id');
            var old    = $('[data-attachment-id="' + $input.val() + '"]');

            $('i', $("#property-thumbs-container .icon-featured")).attr('class', 'fa fa-star-o');
            $('i', this).attr('class', 'fa fa-star');
            $input.val($(this).data('attachment-id'));

        });
        //// 
        $('body').delegate('.gallery-thumbnail .icon-delete', 'click', function () {
            var $p = $(this).parent().parent();
            $.ajax({
                type    : "POST",
                url     : ajaxurl,
                data    : 'property_id=' + $(this).data('property-id') + '&action=opalrealestate_delete_property_image&attachment_id=' + $(this).data('attachment-id'), // serializes the form's elements.
                dataType: 'json',
                success : function (data) {
                    if (data.status == true) {
                        $p.remove();
                    }
                }
            });
        });
        ///
    }


    /*-------------------------------------------------------------------
     *  initialize uploader
     * ------------------------------------------------------------------*/
    if ($("#select-profile-image").length) {
        var uploader = new plupload.Uploader({
            browse_button  : 'select-profile-image',          // this can be an id of a DOM element or the DOM element itself
            file_data_name : 'property_upload_file',
            container      : 'plupload-container',
            multi_selection: false,
            url            : ajaxURL + "?action=opalrealestate_upload_user_avatar&nonce=" + uploadNonce,
            filters        : {
                mime_types        : [
                    {title: fileTypeTitle, extensions: "jpg,jpeg,gif,png"}
                ],
                max_file_size     : '2000kb',
                prevent_duplicates: true
            }
        });
        uploader.init();


        /* Run after adding file */
        uploader.bind('FilesAdded', function (up, files) {
            var html         = '';
            var profileThumb = "";
            plupload.each(files, function (file) {
                profileThumb += '<div id="holder-' + file.id + '" class="profile-thumb">' + '' + '</div>';
            });
            document.getElementById('user-profile-img').innerHTML = profileThumb;
            up.refresh();
            uploader.start();
        });


        /* Run during upload */
        uploader.bind('UploadProgress', function (up, file) {
            document.getElementById("holder-" + file.id).innerHTML = '<span>' + file.percent + "%</span>";
        });


        /* In case of error */
        uploader.bind('Error', function (up, err) {
            document.getElementById('errors-log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
        });


        /* If files are uploaded successfully */
        uploader.bind('FileUploaded', function (up, file, ajax_response) {
            var response = $.parseJSON(ajax_response.response);

            console.log(response, 1);
            if (response.success) {

                var profileThumbHTML = '<img src="' + response.url + '" alt="" />' +
                    '<input type="hidden" class="profile-picture-id" id="profile-picture-id" name="profile-picture-id" value="' + response.attachment_id + '"/>';

                document.getElementById("holder-" + file.id).innerHTML = profileThumbHTML;

            } else {
                // log response object
                console.log(response);
            }
        });

        $('#remove-profile-image').click(function (event) {
            event.preventDefault();
            document.getElementById('user-profile-img').innerHTML = '<div class="profile-thumb"></div>';
        });
    }
    /// //// // / // /    
})(jQuery);