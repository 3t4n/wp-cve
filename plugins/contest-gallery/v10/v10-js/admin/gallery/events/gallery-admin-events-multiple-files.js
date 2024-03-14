jQuery(document).ready(function ($) {

    $(document).on('click','#cgMultipleFilesForPostContainer .cg_backend_image_full_size_target_container_rotate',function () {
        var realId = $(this).attr('data-cg-real-id');
        var order = $(this).closest('.cg_backend_image_full_size_target_container').find('.cg_backend_image_full_size_target_container_position').text();
        var $cg_backend_info_container = cgJsClassAdmin.gallery.vars.$cg_backend_info_container;
        var $cg_backend_image_full_size_target = $(this).closest('.cg_backend_image_full_size_target_container').find('.cg_backend_image_full_size_target');
        var isAdditionalFileChanged = false;
        if(!$(this).attr('data-cg-rThumb') || $(this).attr('data-cg-rThumb')==0){
            $(this).attr('data-cg-rThumb',0) ;
            $cg_backend_image_full_size_target.removeClass('cg180degree  cg270degree').addClass('cg90degree');
            $(this).attr('data-cg-rThumb',90);
            cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order]['rThumb'] = 90;
            if(!cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order]['isRealIdSource']){isAdditionalFileChanged=true;}
        } else if($(this).attr('data-cg-rThumb')==90){
            $cg_backend_image_full_size_target.removeClass('cg90degree  cg270degree').addClass('cg180degree');
            $(this).attr('data-cg-rThumb',180);
            cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order]['rThumb'] = 180;
            if(!cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order]['isRealIdSource']){isAdditionalFileChanged=true;}
        } else if($(this).attr('data-cg-rThumb')==180){
            $cg_backend_image_full_size_target.removeClass('cg90degree  cg180degree').addClass('cg270degree');
            $(this).attr('data-cg-rThumb',270);
            cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order]['rThumb'] = 270;
            if(!cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order]['isRealIdSource']){isAdditionalFileChanged=true;}
        } else if($(this).attr('data-cg-rThumb')==270){
            $cg_backend_image_full_size_target.removeClass('cg90degree cg180degree cg270degree');
            cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order]['rThumb'] = 0;
            if(!cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order]['isRealIdSource']){isAdditionalFileChanged=true;}
            $(this).attr('data-cg-rThumb',0);
        }
        cgJsClassAdmin.gallery.vars.$cg_backend_info_container.find('.cg_backend_save_changes,.cg_backend_rotate_css_based').removeClass('cg_hide');
        if(isAdditionalFileChanged){
            cgJsClassAdmin.gallery.functions.setSimpleDataRealIdSource(realId,cgJsClassAdmin.gallery.vars.$cg_backend_info_container);
        }else{
            cgJsClassAdmin.gallery.vars.$cg_backend_info_container.find('.cg_rThumb').val($(this).attr('data-cg-rThumb')).removeClass('cg_disabled_send');
        }

        if(order==1){
            cgJsClassAdmin.gallery.vars.$cg_backend_info_container.find('.cg_backend_image').removeClass('cg0degree cg90degree cg180degree  cg270degree').addClass('cg'+$(this).attr('data-cg-rThumb')+'degree');
        }

    });

    $(document).on('click','#cgMultipleFilesForPostContainer .cg_backend_image_full_size_target_container_remove',function () {
        var realId = $(this).attr('data-cg-real-id');
        var $cg_preview_files_container = $(this).closest('.cg_preview_files_container');
        var $cg_backend_info_container = cgJsClassAdmin.gallery.vars.$cg_backend_info_container;
        var $cgMultipleFilesForPostContainer = $('#cgMultipleFilesForPostContainer');
        var order = $(this).closest('.cg_backend_image_full_size_target_container').find('.cg_backend_image_full_size_target_container_position').text();
        $(this).closest('.cg_backend_image_full_size_target_container').remove();

        var order = 1;
        var newOrderedData = {};
        var $cgMultipleFilesForPostContainer = $('#cgMultipleFilesForPostContainer');
        var isRealIdSourceDeleted = true;
        $cgMultipleFilesForPostContainer.find('.cg_backend_image_full_size_target_container .cg_backend_image_full_size_target_container_position').each(function (){
            if(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][$(this).text()].isRealIdSource){isRealIdSourceDeleted=false;}
            newOrderedData[order] = cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][$(this).text()];
            $(this).text(order);
            order++;
        });

        cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId] = newOrderedData;
        if(isRealIdSourceDeleted){
            if(cgJsClassAdmin.gallery.vars.realIdSourcesDeleted.indexOf(realId)==-1){
                cgJsClassAdmin.gallery.vars.realIdSourcesDeleted.push(realId);
            }
        }
        cgJsClassAdmin.gallery.functions.resortMultipleFiles(realId);

        console.log('new data');
        console.log(newOrderedData);

        if($cgMultipleFilesForPostContainer.find('.cg_backend_image_full_size_target_container').length>1){
            $cg_backend_info_container.find('.cg_manage_multiple_files_for_post').text('+'+($cgMultipleFilesForPostContainer.find('.cg_backend_image_full_size_target_container').length-1));
        }

        if($cgMultipleFilesForPostContainer.find('.cg_backend_image_full_size_target_container').length==1){
            $cgMultipleFilesForPostContainer.find('.cg_backend_image_full_size_target_container_remove').remove();
/*            $cgMultipleFilesForPostContainer.find('.cg_message_close').click();
            $cgMultipleFilesForPostContainer.find('.cg_backend_image_full_size_target_container_remove').addClass('cg_hide');
            $cg_backend_info_container.find('.cg_manage_multiple_files_for_post, .cg_manage_multiple_files_for_post_prev').addClass('cg_hide');*/
        }

        //  can be done so or so
        $cg_backend_info_container.find('.cg_add_multiple_files_to_post, .cg_add_multiple_files_to_post_prev').removeClass('cg_hide');

        cgJsClassAdmin.gallery.functions.hideShowAddAdditionalFilesLabel($cg_preview_files_container,realId,$cg_backend_info_container);

    });

    var $cg_backend_info_container;

    $(document).on('click','#cgSortable .cg_manage_multiple_files_for_post',function () {

        $cg_backend_info_container = $(this).closest('.cg_backend_info_container');
        cgJsClassAdmin.gallery.vars.$cg_backend_info_container = $cg_backend_info_container;
        cgJsClassAdmin.gallery.functions.showCgBackendBackgroundDrop();
        var realId = $cg_backend_info_container.attr('data-cg-real-id');
        var cg_multiple_files_for_post = $cg_backend_info_container.find('.cg_multiple_files_for_post').val();
        cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId] = JSON.parse(cg_multiple_files_for_post);
        console.log('cg_manage_multiple_files_for_post first time');
        console.log(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId] );

        var $cgMultipleFilesForPostContainer = $('#cgMultipleFilesForPostContainer');
        var $cgMultipleFilesForPostContainerFadeBackground = $('#cgMultipleFilesForPostContainerFadeBackground');
        $cgMultipleFilesForPostContainer.removeClass('cg_hide');
        $cgMultipleFilesForPostContainerFadeBackground.removeClass('cg_hide').addClass('cg_active');

        var $cg_preview_files_container = $cgMultipleFilesForPostContainer.find('.cg_preview_files_container');
        $cg_preview_files_container.empty();

        // body only!
        jQuery('body').addClass('cg_no_scroll');

        for(var order in cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId]){

            if(!cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId].hasOwnProperty(order)){
                break;
            }

            if(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order].isRealIdSource){
                cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order] = cgJsClassAdmin.gallery.functions.setMultipleFileForPostFromBackendInfoContainer($cg_backend_info_container,realId);
            }

            var data = cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order];
            /*data.ExifParsed = (data.Exif) ? (JSON.parse(data.Exif)) : '';*/

            var $divElement = $('<div class="cg_backend_image_full_size_target_container">' +
                '<div class="cg_hover_effect cg_backend_image_full_size_target_container_rotate cg_hide" data-cg-real-id="'+realId+'"></div>'+
                '<div class="cg_backend_image_full_size_target_container_drag"></div>'+
                '<div class="cg_hover_effect cg_backend_image_full_size_target_container_remove" data-cg-real-id="'+realId+'"></div>'+
                '<div class="cg_backend_image_full_size_target_container_position">'+order+'</div>'+
                '<div class="cg_backend_image_full_size_target cg_backend_image_full_size_target_container_'+data.ImgType+'" ' +
                'data-file-type="'+data.ImgType+'" data-file-name="'+data.name+'" data-original-src="'+data.guid+'"></div>' +
                '</div>');

            if(data.WpUploadRemoved){
                debugger
                $divElement.addClass('cg_backend_image_full_size_target_container_empty');
            }
            if(data.type=='image'){
                var rThumb = cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order]['rThumb'];
                $divElement.find('.cg_backend_image_full_size_target').css('background','url("'+data.large+'") center center no-repeat').addClass('cg'+rThumb+'degree').attr('data-file-name');
                $divElement.find('.cg_backend_image_full_size_target_container_rotate').removeClass('cg_hide').attr('data-cg-rThumb',rThumb);
                $divElement.find('.cg_backend_image_full_size_target').wrap('<a href="'+data.guid+'" target="_blank" ></a>');
            }else if(data.type=='video'){
                $divElement.addClass('cg_backend_image_full_size_target_container_video cg_backend_image_full_size_target_container_video_'+data.ImgType);
                $divElement.find('.cg_backend_image_full_size_target').addClass('cg_backend_image_full_size_target_video');
                $divElement.append($('<div class="cg_video_container"><video width="160" >' +
                    '<source src="'+data.guid+'#t=0.001" type="video/mp4"/>' +
                    '<source src="'+data.guid+'#t=0.001" type="video/'+data.ImgType+'"/>' +
                    '</video></div>'));
            }else{
                $divElement.addClass('cg_backend_image_full_size_target_container_alternative_file_type cg_backend_image_full_size_target_container_'+data.ImgType);
                $divElement.append('<div class="cg_backend_image_full_size_target_name" >'+data.NamePic+'</div>');
                $divElement.find('.cg_backend_image_full_size_target').wrap('<a href="'+data.guid+'" target="_blank" ></a>');
            }
            $cg_preview_files_container.append($divElement);
        }

        console.log('cg_manage_multiple_files_for_post ready time');
        console.log(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId]);

        $("#cgMultipleFilesForPostContainer .cg_preview_files_container").sortable({
            items: ".cg_backend_image_full_size_target_container:not(.cg_backend_image_add_files_label)",
            handle: ".cg_backend_image_full_size_target_container_drag",
            cursor: "move",
            placeholder: "ui-state-highlight",
            start: function (event, ui) {
                var $element = $(ui.item);
                $element.closest('#cgMultipleFilesForPostContainer').find('.ui-state-highlight').addClass($element.get(0).classList.value).html($element.html());
            },
            stop: function () {
                setTimeout(function () {
                    cgJsClassAdmin.gallery.functions.resortMultipleFiles(realId);
                },10);
            }
        });

        cgJsClassAdmin.gallery.functions.hideShowAddAdditionalFilesLabel($cg_preview_files_container,realId,$cg_backend_info_container);

    });

    $(document).on('click','#cgMultipleFilesForPostContainer .cg_backend_image_add_files_label',function () {
        $cg_backend_info_container.find('.cg_add_multiple_files_to_post').click();
        cgJsClassAdmin.gallery.functions.hideMultipleFilesContainerForPost($);
    });

});