jQuery(document).ready(function($){

    $(document).on('focusout',"#nickname",function (e) {

        var errorMessage = $('#cg_language_ThisNicknameAlreadyExists').val();
        var $errorMessageContainer = $('<p class="cg_input_error" id="cg_input_error_ThisNicknameAlreadyExists">'+errorMessage+'</p>');

        var $form = $('#your-profile');
        $form.find('input[name="action"]').prop('disabled',true);// all not required other action posts will be removed this way
        $form.find('#post_cg_check_nickname_edit_profile').prop('disabled',false);
        $form.find('#cg_input_error_ThisNicknameAlreadyExists').remove();

        var form = document.getElementById('your-profile');
        var formPostData = new FormData(form);

        $.ajax({
            url: 'admin-ajax.php',
            method: 'post',
            data: formPostData,
            dataType: null,
            contentType: false,
            processData: false
        }).done(function (response) {


            $form.find('input[name="action"]').prop('disabled',false);
            $form.find('#post_cg_check_nickname_edit_profile').prop('disabled',true);

            if(response.indexOf('nickname-exists') > - 1){
                $form.find('#submit').prop('disabled',true);
                $errorMessageContainer.insertAfter($form.find('#nickname'));
            }else{
                $form.find('#submit').prop('disabled',false);
            }

            return;

        }).fail(function (xhr, status, error) {

            $form.find('input[name="action"]').prop('disabled',false);
            $form.find('#post_cg_check_nickname_edit_profile').prop('disabled',true);
            $form.find('#submit').prop('disabled',false);

            return;

        }).always(function () {

        });

    });

    $(document).on('click',"#cgShowExistingProfileImage",function (e) {
        $(this).addClass('cg_hide');
        $('.cg_input_image_upload_file_preview_img_existing').removeClass('cg_hide');
        $('.cg_input_image_upload_file_preview_img:not(.cg_input_image_upload_file_preview_img_existing)').addClass('cg_hide');
        $('#cg_input_image_upload_file_preview').removeClass('cg_hide');
        $('#cg_input_image_upload_file_to_delete_button').removeClass('cg_hide');
        $('#cg_input_error_ChooseYourImage').remove();
        $('#submit').prop('disabled',false);
        $('#cg_input_image_upload_file_to_delete_wp_id').prop('disabled',true);
        $('#cg_input_image_upload_file').val('').parent().find('.cg_input_error').remove();
    });

    var cg_input_image_upload_file_changed = false;

    $(document).on('click',"#cg_input_image_upload_file_to_delete_button",function (e) {
        cg_input_image_upload_file_changed = true;
        $('#cg_input_image_upload_file_to_delete_button').addClass('cg_hide');
        
        var $cg_input_image_upload_file_preview_img_existing = $('.cg_input_image_upload_file_preview_img_existing');
        if($cg_input_image_upload_file_preview_img_existing.length){
            $('#cgShowExistingProfileImage').removeClass('cg_hide');
        }
        $cg_input_image_upload_file_preview_img_existing.addClass('cg_hide');
        $('#cg_input_image_upload_file_to_delete_wp_id').prop('disabled',false);
        $('#cg_input_image_upload_file_preview_img').remove();
        $('#cg_input_image_upload_file_preview').addClass('cg_hide');
        $('#cg_input_image_upload_file').val('');
    });

    $(document).on('change',"#cg_input_image_upload_file",function (e) {
        cg_input_image_upload_file_changed = true;

         $('#cg_input_image_upload_file_to_delete_wp_id').prop('disabled',false);

        var $cg_input_image_upload_file_preview = $('#cg_input_image_upload_file_preview');

        $(this).parent().find('.cg_input_error').remove();

        var $form = $(this).closest('form');
        $form.find('#submit').prop('disabled',false);

        if($(this).get(0).files.length){

            // file validation here first
            var hasProfileImageError = false;

            var filename = $( this ).val().split('\\').pop();

            var $cgShowExistingProfileImage = $('#cgShowExistingProfileImage');

            if($('#cg_input_image_upload_file_required').length && filename == 0){
                var errorMessage = $('#cg_language_ChooseYourImage').val();
                var $errorMessageContainer = $('<p class="cg_input_error" id="cg_input_error_ChooseYourImage">'+errorMessage+'</p>');
                if($cgShowExistingProfileImage.length){
                    $errorMessageContainer.insertAfter($cgShowExistingProfileImage);
                }else{
                    $errorMessageContainer.insertAfter($( this ));
                }
                hasProfileImageError = true;
            }else{
                var fileType = $( this ).get(0).files[0].type;
                var fileTypeEndingString = filename.split('.')[filename.split('.').length - 1].toLowerCase();
                var allowedFileEndings = ['jpg', 'jpeg', 'gif', 'png'];

                if ((fileType != 'image/jpeg' && fileType != 'image/png' && fileType != 'image/gif') || allowedFileEndings.indexOf(fileTypeEndingString) == -1) {
                    var errorMessage = $('#cg_language_ThisFileTypeIsNotAllowed').val();
                    var $errorMessageContainer = $('<p class="cg_input_error" id="cg_input_error_ThisFileTypeIsNotAllowed">'+errorMessage+'</p>');
                    if($cgShowExistingProfileImage.length){
                        $errorMessageContainer.insertAfter($cgShowExistingProfileImage);
                    }else{
                        $errorMessageContainer.insertAfter($( this ));
                    }
                    hasProfileImageError = true;
                }

                var sizePic = $( this ).get(0).files[0].size;
                //Umwandeln in MegaByte
                sizePic = sizePic / 1000000;

                if (sizePic>2) {
                    var errorMessage = $('#cg_language_TheFileYouChoosedIsToBigMaxAllowedSize').val();
                    var $errorMessageContainer = $('<p class="cg_input_error" id="cg_input_error_TheFileYouChoosedIsToBigMaxAllowedSize">'+errorMessage+': 2MB</p>');
                    if($cgShowExistingProfileImage.length){
                        $errorMessageContainer.insertAfter($cgShowExistingProfileImage);
                    }else{
                        $errorMessageContainer.insertAfter($( this ));
                    }
                    hasProfileImageError = true;
                }
                // file validation here first --- END

                $cg_input_image_upload_file_preview.find('.cg_input_image_upload_file_preview_img_existing').addClass('cg_hide');
                $cgShowExistingProfileImage.removeClass('cg_hide');// cg_hide will be removed if field exists, can be done generally on change
                $('#cg_input_image_upload_file_to_delete_button').addClass('cg_hide');// can be generally added if exists

                if(hasProfileImageError){
                    $form.find('#submit').prop('disabled',true);
                }else{
                    var file = $(this).get(0).files[0];
                    var fileReaderBase64 = new FileReader(file);
                    fileReaderBase64.readAsDataURL(file);
                    $cg_input_image_upload_file_preview.find('.cg_input_image_upload_file_preview_img:not(.cg_input_image_upload_file_preview_img_existing)').remove();
                    $cg_input_image_upload_file_preview.append(
                        $(
                            '<div id="cg_input_image_upload_file_preview_skeleton_box">' +
                            '</div>'
                        )
                    ).removeClass('cg_hide');
                    cgPreviewOnload(fileReaderBase64,$cg_input_image_upload_file_preview);
                }
            }

        }else{
            if($cg_input_image_upload_file_preview.find('.cg_input_image_upload_file_preview_img_existing').length){
                $cg_input_image_upload_file_preview.removeClass('cg_hide');
                $cg_input_image_upload_file_preview.find('.cg_input_image_upload_file_preview_img:not(.cg_input_image_upload_file_preview_img_existing)').addClass('cg_hide');
                $cg_input_image_upload_file_preview.find('.cg_input_image_upload_file_preview_img_existing').removeClass('cg_hide');
            }else{
                $cg_input_image_upload_file_preview.addClass('cg_hide');
            }
        }

    });

    var cgPreviewOnload = function (fileReaderBase64,$cg_input_image_upload_file_preview) {
        fileReaderBase64.onload = function () {
            var base64url = this.result;
            setTimeout(function () {
                $cg_input_image_upload_file_preview.find('#cg_input_image_upload_file_preview_skeleton_box').remove();
                $cg_input_image_upload_file_preview.append(
                    jQuery('<div class="cg_input_image_upload_file_preview_img" />').css({
                        'background': 'url("' + base64url + '") no-repeat center center',
                        'display': 'none'
                    })
                );
                $cg_input_image_upload_file_preview.find('.cg_input_image_upload_file_preview_img:not(.cg_input_image_upload_file_preview_img_existing)').fadeIn();
                $('#cg_input_image_upload_file_to_delete_button').removeClass('cg_hide');
            }, 1000);
        };
    };

    // not required to use this logic in the moment
   /* if(localStorage.getItem('cg_input_image_upload_file')=='changed'){

        var $cg_input_image_upload_file_preview = $('#cg_input_image_upload_file_preview');

        $cg_input_image_upload_file_preview.append(
                $(
                    '<div id="cg_input_image_upload_file_preview_skeleton_box">' +
                    '</div>'
                )
        ).removeClass('cg_hide');

            var i = 0;
            var interval = setInterval(function() {
                i = i+1;
                if(localStorage.getItem('cg_input_image_upload_file_src')){
                    $cg_input_image_upload_file_preview.find('#cg_input_image_upload_file_preview_skeleton_box').remove();
                    $cg_input_image_upload_file_preview.append(
                        jQuery('<div id="cg_input_image_upload_file_preview_img" />').css({
                            'background': 'url("' + localStorage.getItem('cg_input_image_upload_file_src') + '") no-repeat center center',
                            'display': 'none'
                        })
                    );
                    $cg_input_image_upload_file_preview.find('#cg_input_image_upload_file_preview_img').fadeIn();
                    localStorage.removeItem('cg_input_image_upload_file');
                    localStorage.removeItem('cg_input_image_upload_file_src')
                    clearInterval(interval);
                }else if(i>=10){
                    $cg_input_image_upload_file_preview.find('#cg_input_image_upload_file_preview_skeleton_box').remove();
                    localStorage.removeItem('cg_input_image_upload_file');
                    localStorage.removeItem('cg_input_image_upload_file_src');
                    clearInterval(interval);
                }
            },1000);
    }if(localStorage.getItem('cg_input_image_upload_file')=='deleted'){
        localStorage.removeItem('cg_input_image_upload_file');
        localStorage.removeItem('cg_input_image_upload_file_src');
    }*/

    $(document).on('change',".cg_input_field_required",function (e) {
        $(this).parent().find('.cg_input_error').remove();
        if($(this).val().trim()==''){
            var errorMessage = $('#cg_language_required').val();
            var $errorMessageContainer = $('<p class="cg_input_error" id="cg_input_error_required">'+errorMessage+'</p>');
            $errorMessageContainer.insertAfter($(this));
        }

        var $form = $('#your-profile');
        if(!$form.find('.cg_input_error:visible').length){
            $('#submit').prop('disabled',false);
        }

    });

    $(document).on('submit',"#your-profile",function (e) {

        var $form = $('#your-profile');
        $form.find('.cg_input_error').remove();

        var hasError = false;

        // has to be done here already
        // remove not required action post, only one is always allowed
        $form.find('#post_cg_check_nickname_edit_profile').remove();

        $form.find('.cg_input_field_required').each(function (){
            if($(this).val().trim()==''){
                var errorMessage = $('#cg_language_required').val();
                var $errorMessageContainer = $('<p class="cg_input_error" id="cg_input_error_required">'+errorMessage+'</p>');
                $errorMessageContainer.insertAfter($(this));
                hasError = true;
            }
        });

        if($('#cg_input_image_upload_file_required').length && ($('.cg_input_image_upload_file_preview_img_existing').hasClass('cg_hide') ||  !$('.cg_input_image_upload_file_preview_img_existing').length)){
            var $cg_input_image_upload_file = $( '#cg_input_image_upload_file' );
            var filename = $cg_input_image_upload_file.val().split('\\').pop();
            if(filename == 0){
                var errorMessage = $('#cg_language_ChooseYourImage').val();
                var $errorMessageContainer = $('<p class="cg_input_error" id="cg_input_error_ChooseYourImage">'+errorMessage+'</p>');
                if($('#cgShowExistingProfileImage').length){
                    $errorMessageContainer.insertAfter($('#cgShowExistingProfileImage'));
                }else{
                    $errorMessageContainer.insertAfter($( '#cg_input_image_upload_file' ));
                }
                hasError = true;
            }
        }

        if(hasError){
            e.preventDefault();
            $form.find('#submit').prop('disabled',true);
            return;
        }

        if(cg_input_image_upload_file_changed){
            if($form.find('#cg_input_image_upload_file').val().length){
             //   localStorage.setItem('cg_input_image_upload_file','changed');
            }else{
         //       localStorage.setItem('cg_input_image_upload_file','deleted');
            }

            $form.find('input[name="action"]').prop('disabled',true);
            $form.find('#post_cg_backend_image_upload').prop('disabled',false);

            var form = document.getElementById('your-profile');
            var formPostData = new FormData(form);

            $.ajax({
                url: 'admin-ajax.php',
                method: 'post',
                data: formPostData,
                dataType: null,
                contentType: false,
                processData: false,
                async: false,	// Important! Operation has to be done not asynchronous!
            }).done(function (response) {

           //     e.preventDefault();
                cgPrepareForSubmitProfileImageDependant($form);
                $(this).find('input[name="action"]').prop('disabled',false);// not forget to do this, might be disable after check!

/*                var parser = new DOMParser();
                var parsedHtml = parser.parseFromString(response, 'text/html');
                parsedHtml = jQuery(parsedHtml);

                var $scriptDataCgProcessing = parsedHtml.find('script[data-cg-processing="true"]');

                // else, then login data must be uncorrect
                if($scriptDataCgProcessing.length){
                    e.preventDefault();
                    $scriptDataCgProcessing.each(function () {
                        var script = jQuery(this).html();
                        eval(script);
                    });
                }*/

            }).fail(function (xhr, status, error) {
                debugger
             //   e.preventDefault();
                cgPrepareForSubmitProfileImageDependant($form);
            }).always(function () {

            });
        }else{
            cgPrepareForSubmitProfileImageDependant($form);
            //localStorage.removeItem('cg_input_image_upload_file');
        }

    });

    function cgPrepareForSubmitProfileImageDependant($form){
        $form.find('input[name="action"]').prop('disabled',false);// not forget to do this, might be disable after check!
        $form.find('#post_cg_backend_image_upload').remove();
        $form.find('#cg_input_image_upload_file').removeAttr('name').prop('disabled',true);// only disabled true because if true remove then visible
    }

});