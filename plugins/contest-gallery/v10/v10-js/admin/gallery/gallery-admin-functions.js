cgJsClassAdmin.gallery.functions = cgJsClassAdmin.gallery.functions || {};
cgJsClassAdmin.gallery.functions = {
    requests: [],
    abortRequest: function(){

        for(var index in cgJsClassAdmin.gallery.functions.requests){
            cgJsClassAdmin.gallery.functions.requests[index].abort();
            delete cgJsClassAdmin.gallery.functions.requests[index];
        }

    },
    load: function($,isAddImages,$formLinkObject,isImagesAdded){
        if(isAddImages){
            $('#cg_uploading_gif').hide();
            $('#cg_uploading_div').hide();
            $('#cgSortable').remove();
            $('#cgStepsNavigationTop').remove();
        }

       if(!document.getElementById('cgGalleryForm')){
            return;
        }

//    $('#cgViewControl').removeClass('cg_hide');
        $('.cg_steps_navigation').removeClass('cg_hide');
        $('#cgSortable').removeClass('cg_hide');

        cgJsClassAdmin.gallery.vars.setStarOnStarOffSrc();

// Add icon

        $( "#cg_server_power_info" ).hover(function() {
            //alert(3);
            $('#cg_answerPNG').toggle();
            $(this).css('cursor','pointer');
        });

        $( "#cg_adding_images_info" ).hover(function() {
            //alert(3);
            $('#cg_adding_images_answer').toggle();
            $(this).css('cursor','pointer');
        });

//Check if the current URL contains '#'

        // Verstecken weiterer Boxen

        $('.mehr').hide();
        $('.clickBack').hide();

        // moved from gallery_admin --- ENDE


        $('#chooseAll').prop('checked',false);

        if($formLinkObject){

            if($formLinkObject.hasClass('cg_load_backend_create_gallery')){

                $('#cgGalleryLoader').addClass('cg_hide');
                $('#cgSortable').addClass('cg_hide');
                $('#cgGallerySubmit').addClass('cg_hide');
                $(".cg-created-new-gallery").fadeOut(8000);
                $(".cg-created-new-gallery-br").fadeOut(8000);
                return;
            }
        }

        $('#cgGalleryLoader').removeClass('cg_hide');
        $('#cgViewControl').removeClass('cg_hide');


        $('#cgSortable').remove();// can be removed here on load
        $('#cgGallerySubmit').remove();// can be removed here on load
        $('#cgStepsNavigationBottom').remove();// can be removed here on load

        var gid = $('#cgBackendGalleryId').val();

        // to go simply sure that nothing will be deleted!!!
        $('#cgGalleryForm').find('.cg_delete').remove();

        // BG is for backend gallery
        var cgStart_BG = localStorage.getItem('cgStart_BG_'+gid);
        // check types also here because can be 0
        if(cgStart_BG || cgStart_BG===0 || cgStart_BG==='0'){$('#cgStartValue').val(cgStart_BG)}
        var cgStep_BG = localStorage.getItem('cgStep_BG_'+gid);
        if(cgStep_BG){$('#cgStepValue').val(cgStep_BG)}
        var cgOrder_BG = localStorage.getItem('cgOrder_BG_'+gid);

        // fallback to go sure if empty or old order options are activated
        if(!cgOrder_BG){
            cgOrder_BG = 'custom';
        }else if(cgOrder_BG=='rating_desc_average' || cgOrder_BG=='rating_asc_average' || cgOrder_BG=='rating_desc_average_with_manip' || cgOrder_BG=='rating_asc_average_with_manip'){
            cgOrder_BG = 'custom';
        }

        // check if generally available as option. If not date desc as fallback.
        // switching from one to multiple stars and the other way round might cause not existing order if order other kind of rating was selected
        if(!$('#cgOrderSelect option[value='+cgOrder_BG+']').length){
            cgOrder_BG = 'custom';
        }

        if(cgOrder_BG){
            if(cgOrder_BG.indexOf('_average')>-1 && $('#cgAllowRating').val()!=1){
                if(cgOrder_BG){$('#cgOrderValue').val('custom');}
            }else{
                if(cgOrder_BG){$('#cgOrderValue').val(cgOrder_BG);}
            }
        }else{
            if(cgOrder_BG){$('#cgOrderValue').val(cgOrder_BG);}
        }

        var cgSearch_BG = localStorage.getItem('cgSearch_BG_'+gid);
        if(cgSearch_BG){$('#cgSearchInput').val(cgSearch_BG)}

        var form = document.getElementById('cgGalleryForm');
        var formPostData = new FormData(form);

        // !IMPORTANT!!!! Do not remove otherwise recursion error! Needs to check first time new backend ajax version 10.9.9 null null is instaled
        formPostData.append('cgBackendHash',$('#cgBackendHash').val());
        formPostData.append('action', 'post_contest_gallery_action_ajax');

        // AJAX Call - Load when site load
        cgJsClassAdmin.gallery.functions.requests.push($.ajax({
            url: 'admin-ajax.php',
            method: 'post',
            data: formPostData,
            dataType: null,
            contentType: false,
            processData: false
        }).done(function(response) {

            // to go sure remove it on every load
            $('#cgDeleteOriginalImageSourceAlso').remove();
            if(cgJsClassAdmin.gallery.functions.missingRights($,response)){return;}

            cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();

            cgJsClassAdmin.gallery.vars.selectChanged = false;
            cgJsClassAdmin.gallery.vars.inputsChanged = false;

            $('#cgGalleryLoader').addClass('cg_hide');

            var htmlDom = new DOMParser().parseFromString(response, 'text/html');
            var $cgSortable = $(htmlDom.getElementById('cgSortable'));
            var $cgGallerySubmit = $(htmlDom.getElementById('cgGallerySubmit'));
            $('#cgStepsNavigationTop').remove(); // remove existing cgStepsNavigationTop first
            var $cgStepsNavigationTop = $(htmlDom.getElementById('cgStepsNavigationTop'));
            $cgStepsNavigationTop.removeClass('cg_hide');
            var $cgStepsNavigationBottom = $(htmlDom.getElementById('cgStepsNavigationBottom'));
            var cg_files_count_total = $(htmlDom.getElementById('cg_files_count_total')).val();

            if(cg_files_count_total>1){
                var cg_files_count_total = $('#cg_sort_files_form_button').removeClass('cg_hide');
            }else{
                var cg_files_count_total = $('#cg_sort_files_form_button').addClass('cg_hide');
            }

            $cgStepsNavigationTop.insertAfter($('#cgGalleryLoader'));
            $cgSortable.insertAfter($cgStepsNavigationTop);

            var isNoImagesFound = false;

            if($cgSortable.find('.cg_backend_info_container').length>=1){
                $cgGallerySubmit.insertAfter($cgSortable);
                $('#cgViewControl').removeClass('cg_hide');
                $cgSortable.find('#cgNoImagesFound').addClass('cg_hide');
            }else{
                isNoImagesFound = true;
                $cgSortable.find('#cgNoImagesFound').removeClass('cg_hide');
                //$cgSortable.addClass('cg_hide');
                $('#cgGallerySubmit').addClass('cg_hide');
            }

            $cgStepsNavigationBottom.insertAfter($cgGallerySubmit);

            cgJsClassAdmin.gallery.functions.markSearchedValueFields($);

            $('#cgStepsChanged').prop('disabled',true);

            cgJsClassAdmin.gallery.functions.sortableInit($);
            cgJsClassAdmin.gallery.functions.markSortedByCustomFields($);
            cgJsClassAdmin.gallery.functions.initDateTimePicker($);

            if(isNoImagesFound){
                cgJsClassAdmin.gallery.functions.checkIfFurtherImagesAvailable($);
            }

            if(isAddImages){
                if(isImagesAdded){// have to be done here, isAddImages condition has to be valid, so bottom condition not executed!
                    var $changesSaved = $('#cgSortable').find('#cg_changes_saved');
                    if($changesSaved.length){
                        $changesSaved.remove();
                    }
                    $("<p id='cg_changes_saved' style='font-size:18px;'><strong>Files added</strong></p>").prependTo('#cgSortable');
                    $("#cg_changes_saved").fadeOut(4000);
                    jQuery('html, body').animate({
                        scrollTop: jQuery('#cg_changes_saved').offset().top - 150+'px'
                    }, 0, function () {
                    });
                }else{
                    var $changesSaved = $('#cgSortable').find('#cg_changes_saved');
                    if($changesSaved.length){
                        $changesSaved.remove();
                    }
                }
            }else{
                if($formLinkObject){

                    if($formLinkObject.hasClass('cg_load_backend_submit_form_submit') && !$formLinkObject.hasClass('cg_reset_all_informed')){
                        if(!$('#cgSortable').find('#cg_changes_saved').length){
                            cgJsClassAdmin.gallery.functions.setAndAppearBackendGalleryDynamicMessage('Changes saved',true);
                            //   $("<p id='cg_changes_saved' style='font-size:18px;'><strong>Changes saved</strong></p>").prependTo('#cgSortable');
                        }
                   //     $("#cg_changes_saved").fadeOut(4000);
                    }
                    if($formLinkObject.hasClass('cg_reset_all_informed')){
                        cgJsClassAdmin.gallery.functions.setAndAppearBackendGalleryDynamicMessage('All informed users are reseted<br>They will be informed again if entry will be activated again<br>Entry has to be deactivated before');
                    }

                }
            }

        }).fail(function(xhr, status, error) {

        }).always(function() {

        }));

    },
    setAndAppearBackendGalleryDynamicMessage: function (message,isNoActionMessage,classToAdd,isBackendActionContainer){
        var $cgBackendBackgroundDrop = jQuery('#cgBackendBackgroundDrop');

        if($cgBackendBackgroundDrop.hasClass('cg_hide') && !isNoActionMessage){
            $cgBackendBackgroundDrop.removeClass('cg_hide').addClass('cg_active');
            var $cgBackendGalleryDynamicMessage = jQuery('#cgBackendGalleryDynamicMessage');
            if(isBackendActionContainer){
                $cgBackendGalleryDynamicMessage.addClass('cg_backend_action_container');
            }else{
                $cgBackendGalleryDynamicMessage.removeClass('cg_backend_action_container');
            }
            $cgBackendGalleryDynamicMessage.removeClass('cg_hide_slow cg_no_action_message cg_hide').find('.cg_notification_message_dynamic_content').html(message);
            if(classToAdd){
                $cgBackendGalleryDynamicMessage.addClass(classToAdd);
            }
        }

        if(isNoActionMessage){
            //   $cgBackendBackgroundDrop.removeClass('cg_hide_slow_1_sec cg_hide');
            var $cgBackendGalleryDynamicMessage = jQuery('#cgBackendGalleryDynamicMessage');
            if(isBackendActionContainer){
                $cgBackendGalleryDynamicMessage.addClass('cg_backend_action_container');
            }else{
                $cgBackendGalleryDynamicMessage.removeClass('cg_backend_action_container');
            }
            $cgBackendGalleryDynamicMessage.addClass('cg_no_action_message').removeClass('cg_hide_slow cg_hide').find('.cg_notification_message_dynamic_content').html(message);
            setTimeout(function (){
                //$cgBackendBackgroundDrop.addClass('cg_hide_slow_1_sec');
                $cgBackendGalleryDynamicMessage.addClass('cg_hide_slow');
            },1000);
        }

    },
    markSearchedValueFields: function ($) {

        var $cgSearchInput = $('#cgSearchInput');
        var cgSearchInputValue = $cgSearchInput.val().trim();
        if(cgSearchInputValue){

            $('#cgSearchInputClose').removeClass('cg_hide');
            $('#cgSearchInputButton').removeClass('cg_hide');
            var cgSearchedValueHiddenFieldsSelector = '#cgSortable .cg_wp_user_display_name,#cgSortable .cg_wp_user_email,#cgSortable .cg_wp_user_nicename,#cgSortable .cg_wp_user_login,' +
                '#cgSortable .cg_wp_post_content, #cgSortable .cg_wp_post_name, #cgSortable .cg_wp_post_title, #cgSortable .cg_image_id,#cgSortable .cg_cookie_id_or_ip';
            var $inputFieldsWithValue = $(cgJsClassAdmin.gallery.vars.cgChangedAndSearchedValueSelector).filter(function () {return $(this).val().toLowerCase().indexOf(cgSearchInputValue.toLowerCase()) != -1; });
            var $inputHiddenFieldsWithValue = $(cgSearchedValueHiddenFieldsSelector).filter(function () {
                    return $(this).val().toLowerCase().indexOf(cgSearchInputValue.toLowerCase()) != -1;
            });
            $cgSearchInput.addClass('cg_searched_value');
            $inputHiddenFieldsWithValue.closest('.cg_backend_info_container').addClass('cg_searched_value');
            $inputFieldsWithValue.addClass('cg_searched_value');

            var $cgCategorySelects = $('#cgSortable .cg_category_select option:selected').filter(function () { return $(this).html().toLowerCase() === cgSearchInputValue.toLowerCase(); }).closest('.cg_category_select');
            $cgCategorySelects.addClass('cg_searched_value');

            $('#cgStepsNavigationTop .cg_step, #cgStepsNavigationBottom .cg_step').not('.cg_step_selected').addClass('cg_searched_value');
        }

        var $cgOrderSelect = $('#cgOrderSelect');
        var cgOrderSelectValue = $cgOrderSelect.val();
        if(cgOrderSelectValue!='custom'){
            $cgOrderSelect.addClass('cg_searched_value');
        }else{
            $cgOrderSelect.removeClass('cg_searched_value');
        }

        if(cgOrderSelectValue=='comments_desc'){
            $('.cg_image_action_comments').addClass('cg_searched_value');
        }else{
            $('.cg_image_action_comments').removeClass('cg_searched_value');
        }

        $('#cgSortable .cg-exif-text').each(function (){
            if(cgSearchInputValue.trim()!=''){
                if($(this).text().toLowerCase().indexOf(cgSearchInputValue.toLowerCase()) != -1){
                    $(this).addClass('cg_searched_value');
                }
            }
        });

    },
    missingRights: function($,response){
        if(response.indexOf('MISSINGRIGHTS')>=0){
            response = response.split('MISSINGRIGHTS')[1];
            var htmlDom = $.parseHTML(response);
            $(htmlDom).insertAfter(jQuery('#cgViewControl'));
            $('#cgGalleryLoader').addClass('cg_hide');
            return true;
        }else{
            return false;
        }
    },
    checkIfFurtherImagesAvailable: function($){

        if($('#cgStepsNavigationTop .cg_step').length){// happens when images in last step were deleted!!!
            $('#cgStepsNavigationTop .cg_step:last-child').click();
        }

    },
    initDateTimePicker: function($) {

        $(".cg_input_date_class").datepicker({
            beforeShow: function(input, inst) {
                $('#ui-datepicker-div').addClass('cg_admin_images_area_form');
                //$('#ui-datepicker-div').addClass($('#cg_fe_controls_style_user_upload_form_shortcode').val()); no style check in the moment
                $('#ui-datepicker-div.cg_upload_form_container .ui-datepicker-next').attr('title','');
            },
            changeMonth: true,
            changeYear: true,
            monthNames: ["01","02","03","04","05","06","07","08","09","10","11","12"],
            monthNamesShort: ["01","02","03","04","05","06","07","08","09","10","11","12"],
            yearRange: "-100:+100",
            //   option: {dateFormat:"dd.mm.yy"}
        });

        $( ".cg_input_date_class" ).each(function () {
            var cgDateFormat =  $(this).closest('.cg_image_title_container').find('.cg_date_format').val().toLowerCase().replace('yyyy','yy');
            var value = $( this ).val();
            // have to be done in extra row here
            $( this ).datepicker("option", "dateFormat", cgDateFormat);
            $( this ).val(value);// value has to be set again after format is set!
        });

        $( "#ui-datepicker-div" ).hide();

    },
    sortableInit: function($) {

        return;

        //Sortieren der Galerie

        var $i = 0;

        var rowid = [];

        if($i==0){

            $( ".cgSortableDiv" ).each(function( i ) {

                var rowidValue =  $(this).find('.rowId').val();


                rowid.push(rowidValue);

            });

            $i++;

        }
        $(function() {
            $( "#cgSortable" ).sortable({cursor: "move",handle:'.cg_drag_area',placeholder: "ui-state-highlight",
                stop: function( event, ui ) {

                    if(document.readyState === "complete"){

                        var v = 0;

                        $( ".cgSortableDiv" ).each(function( i ) {


                            $(this).find('.rowId').val(rowid[v]).addClass('cg_value_changed').prop('disabled',false);
                            v++;

                        });

                        v = 0;

                    }

                },
                start: function( event, ui){

                    var $element = $(ui.item);

                    $element.closest('#cgSortable').find('.ui-state-highlight').addClass($element.get(0).classList.value).html($element.html());

                }
            });
        });

    },
    searchInputButtonClick: function (){

        cgJsClassAdmin.gallery.functions.abortRequest();

        // to go simply sure that nothing will be deleted!!!
        jQuery('#cgGalleryForm').find('.cg_delete').remove();

        cgJsClassAdmin.gallery.load.changeViewByControl(jQuery, null, null, null, true);

    },
    markSortedByCustomFields: function ($){

        $('#cgSortable .cg_short_text').removeClass('cg_by_search_sorted');
        $('#cgSortable .cg_category_select').removeClass('cg_by_search_sorted');
        $('#cgSortable .cg_for_id_wp_username_by_search_sort').removeClass('cg_by_search_sorted');

        var $cgOrderSelectCustomFieldsSelectedInput = $('#cgOrderSelectCustomFields option:selected,#cgOrderSelectFurtherFields option:selected');

        if($cgOrderSelectCustomFieldsSelectedInput.length){
            $('#cgSortable .'+$cgOrderSelectCustomFieldsSelectedInput.attr('data-cg-input-fields-class')).addClass('cg_by_search_sorted');
        }

    },
    cgRotateSameHeightDivImage: function ($){
        if($('#cgImgThumbContainerMain').length){
            $('#cgSortable .cg_short_text').removeClass('cg_by_search_sorted');
            $('#cgSortable .cg_category_select').removeClass('cg_by_search_sorted');
            $('#cgSortable .cg_for_id_wp_username_by_search_sort').removeClass('cg_by_search_sorted');

            var $cgOrderSelectCustomFieldsSelectedInput = $('#cgOrderSelectCustomFields option:selected,#cgOrderSelectFurtherFields option:selected');

            if($cgOrderSelectCustomFieldsSelectedInput.length){
                $('#cgSortable .'+$cgOrderSelectCustomFieldsSelectedInput.attr('data-cg-input-fields-class')).addClass('cg_by_search_sorted');
            }
        }

    },
    cgRotateOnLoad: function ($){
        if($('#cgImgSource').height()>=$('#cgImgSource').width()){
            //console.log(0);
            $('#cgImgSourceContainerMain').height($('#cgImgSource').height());
        }
        else{//console.log(1);
            $('#cgImgSourceContainerMain').height($('#cgImgSource').width());
        }
        if($('#cgImgThumb').height()>=$('#cgImgThumb').width()){//console.log(2);
            $('#cgImgThumbContainerMain').height($('#cgImgThumb').height());
        }
        else{//console.log(3);
            $('#cgImgThumbContainerMain').height($('#cgImgThumb').width());
        }
    },
    countTotalVisibleActivatedImagesCountForCategories: function () {

        var totalVisibleActivatedImagesCount = 0;

        jQuery('.cg-categories-check').each(function () {
            if (jQuery(this).prop('checked')) {
                totalVisibleActivatedImagesCount = totalVisibleActivatedImagesCount + parseInt(jQuery(this).attr('data-cg-images-in-category-count'));
            }
        });

        return totalVisibleActivatedImagesCount;

    },
    setMultipleFileForPostFromBackendInfoContainer: function ($cg_backend_info_container,isRealIdSource){
        var data = {
            WpUpload:$cg_backend_info_container.attr('data-cg-wp-upload'),
            post_title:$cg_backend_info_container.attr('data-cg-post_title'),
            post_name:$cg_backend_info_container.attr('data-cg-post_name'),
            post_content:$cg_backend_info_container.attr('data-cg-post_content'),
            post_excerpt:$cg_backend_info_container.attr('data-cg-post_excerpt'),
            post_mime_type:$cg_backend_info_container.attr('data-cg-post_mime_type'),
            medium:$cg_backend_info_container.attr('data-cg-url-image-medium'),
            large:$cg_backend_info_container.attr('data-cg-url-image-large'),
            full:$cg_backend_info_container.attr('data-cg-original-source'),
            guid:$cg_backend_info_container.attr('data-cg-original-source'),
            type:$cg_backend_info_container.attr('data-cg-type'),// WordPress type
            NamePic:$cg_backend_info_container.attr('data-cg-post_name'),
            ImgType:$cg_backend_info_container.attr('data-cg-type-short'),
            Width:$cg_backend_info_container.attr('data-cg-file-width'),
            Height:$cg_backend_info_container.attr('data-cg-file-height'),
            rThumb:$cg_backend_info_container.find('.cg_rThumb').val(),
            Exif:$cg_backend_info_container.attr('data-cg-exif'),
            ExifParsed:(this.Exif) ? (JSON.parse(this.Exif)) : '',
            IsExifDataChecked: true
        }
        if(isRealIdSource){data.isRealIdSource = true;}
        return data;
    },
    setSimpleDataRealIdSource: function (realId,$cg_backend_info_container){

        //if(Object.keys(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId]).length>1){
            var dataWithSimpleRealIdSource = {};
            for(var order in cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId]){
                if(!cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId].hasOwnProperty(order)){
                    break;
                }
                dataWithSimpleRealIdSource[order] = {};
                if(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order].isRealIdSource){
                    var data = {
                        isRealIdSource: true,
                        WpUpload: cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order].WpUpload
                    };
                    dataWithSimpleRealIdSource[order] = data;
                }else{
                    dataWithSimpleRealIdSource[order] = cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][order];
                    if(dataWithSimpleRealIdSource[order].ExifParsed){
                        delete dataWithSimpleRealIdSource[order].ExifParsed;
                    }
                }
            }
            console.log('dataWithSimpleRealIdSource');
            console.log(dataWithSimpleRealIdSource);
            var dataJSONstring = JSON.stringify(dataWithSimpleRealIdSource);
/*        }else if(Object.keys(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId]).length==1){
            cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1].isRealIdSource = true;
            console.log('data only one left with reald id source then');
            console.log(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId]);
            var dataJSONstring = JSON.stringify(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]);
        }*/
        $cg_backend_info_container.find('.cg_multiple_files_for_post').val(dataJSONstring).removeClass('cg_disabled_send');
        $cg_backend_info_container.find('.cg_backend_save_changes').removeClass('cg_hide');
    },
    resortMultipleFiles: function (realId){
        console.log('stop sortable');
        console.log(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId]);
        var $cg_backend_info_container = cgJsClassAdmin.gallery.vars.$cg_backend_info_container;

        var order = 1;
        var newOrderedData = {};
        jQuery("#cgMultipleFilesForPostContainer .cg_preview_files_container .cg_backend_image_full_size_target_container_position").each(function (){
            newOrderedData[order] = cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][jQuery(this).text()];
            jQuery(this).text(order);
            order++;
        });
        cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId] = newOrderedData;
        cgJsClassAdmin.gallery.functions.setSimpleDataRealIdSource(realId,$cg_backend_info_container);

        $cg_backend_info_container.find('.cg_backend_image').removeClass('cg_hide');

        $cg_backend_info_container.find('.cg_backend_image_full_size_target > a').attr('href','url('+cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['guid']+') no-repeat center');
        var $cg_backend_image_full_size_target = $cg_backend_info_container.find('.cg_backend_image_full_size_target');
        $cg_backend_image_full_size_target.attr({
            'data-file-type':cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['ImgType'],
            'data-name-pic':cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['NamePic'],
            'data-original-src':cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['guid']
        });
        $cg_backend_image_full_size_target.empty();
        console.log('sortable');
        console.log(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId]);

        if(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['type']=='image'){
            $cg_backend_image_full_size_target.append('<a href="'+cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['guid']+'" target="_blank" title="Show full size" alt="Show full size">\n' +
                '<div class="cg'+cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['rThumb']+'degree cg_backend_image" style="background: url('+cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['large']+') no-repeat center"></div></a>');
            $cg_backend_info_container.find('.cg_rotate_image_backend').removeClass('cg_hide');
        }else if(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['type']=='video'){
            $cg_backend_image_full_size_target.append('<a href="'+cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['guid']+'" target="_blank" title="Show file" alt="Show file">\n' +
                '<video width="160" height="106">' +
                    '<source src="'+cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['guid']+'#t=0.001" type="video/mp4"/>' +
                    '<source src="'+cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['guid']+'#t=0.001" type="video/'+cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['ImgType']+'"/>' +
                '</video></a>');
            $cg_backend_info_container.find('.cg_rotate_image_backend,.cg_backend_rotate_css_based').addClass('cg_hide');
        }else { // then alternative file type
            $cg_backend_image_full_size_target.append('<a href="'+cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['guid']+'" target="_blank" title="Show file" alt="Show file">\n' +
                '<div class="cg_backend_image_full_size_target_'+cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['ImgType']+' cg_backend_image_full_size_target_alternative_file_type" data-cg-file-type="'+cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1]['ImgType']+'"></div></a>');
            $cg_backend_info_container.find('.cg_rotate_image_backend,.cg_backend_rotate_css_based').addClass('cg_hide');
        }

        var $cg_sortable_div = $cg_backend_info_container.closest('.cg_sortable_div');
        $cg_sortable_div.find('.cg-center-image-exif-data-not-checked').addClass('cg_hide');

        if(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1].Exif && cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1].IsExifDataChecked){
            var data = cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1];
            if(typeof data.Exif == 'string'){
                data.Exif = JSON.parse(data.Exif);
            }
            $cg_sortable_div.find('.cg_exif_data_container').removeClass('cg_hide');
            $cg_sortable_div.find('.cg-exif').addClass('cg_hide');
            $cg_sortable_div.find('.cg-center-image-exif-no-data').addClass('cg_hide');
            if(data.Exif.DateTimeOriginal){
                $cg_sortable_div.find('.cg-exif-date-time-original').removeClass('cg_hide');
                $cg_sortable_div.find('.cg-exif-date-time-original-text').text(data.Exif.DateTimeOriginal.split(' ')[0].replaceAll(':','-'));
            }
            if(data.Exif.MakeAndModel){
                $cg_sortable_div.find('.cg-exif-model').removeClass('cg_hide');
                $cg_sortable_div.find('.cg-exif-model-text').text(data.Exif.MakeAndModel);
            }else if(data.Exif.Model){
                $cg_sortable_div.find('.cg-exif-model').removeClass('cg_hide');
                $cg_sortable_div.find('.cg-exif-model-text').text(data.Exif.Model);
            }
            if(data.Exif.ApertureFNumber){
                $cg_sortable_div.find('.cg-exif-aperturefnumber').removeClass('cg_hide');
                $cg_sortable_div.find('.cg-exif-aperturefnumber-text').text(data.Exif.ApertureFNumber);
            }
            if(data.Exif.ExposureTime){
                $cg_sortable_div.find('.cg-exif-exposuretime').removeClass('cg_hide');
                $cg_sortable_div.find('.cg-exif-exposuretime-text').text(data.Exif.ExposureTime);
            }
            if(data.Exif.ISOSpeedRatings){
                $cg_sortable_div.find('.cg-exif-isospeedratings').removeClass('cg_hide');
                $cg_sortable_div.find('.cg-exif-isospeedratings-text').text(data.Exif.ISOSpeedRatings);
            }
            if(data.Exif.FocalLength){
                $cg_sortable_div.find('.cg-exif-focallength').removeClass('cg_hide');
                $cg_sortable_div.find('.cg-exif-focallength-text').text(data.Exif.FocalLength);
            }
        }else if(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1].IsExifDataChecked && cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1].type=='image'){
            $cg_sortable_div.find('.cg_exif_data_container').removeClass('cg_hide');
            $cg_sortable_div.find('.cg-exif').addClass('cg_hide');
            $cg_sortable_div.find('.cg-center-image-exif-no-data').removeClass('cg_hide');
        }else if(!cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1].IsExifDataChecked && cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId][1].type=='image'){
            $cg_sortable_div.find('.cg_exif_data_container').removeClass('cg_hide');
            $cg_sortable_div.find('.cg-exif').addClass('cg_hide');
            $cg_sortable_div.find('.cg-center-image-exif-no-data').addClass('cg_hide');
            $cg_sortable_div.find('.cg-center-image-exif-data-not-checked').removeClass('cg_hide');
        }else{
            $cg_sortable_div.find('.cg_exif_data_container').addClass('cg_hide');
        }

    },
    hideCgBackendBackgroundDrop: function (isHideSlow){
        jQuery('.cg_backend_action_container').addClass('cg_hide');
        jQuery('#cg_main_container').removeClass('cg_active cg_pointer_events_none');
        var $cgBackendGalleryDynamicMessage = jQuery('#cgBackendGalleryDynamicMessage');
        $cgBackendGalleryDynamicMessage.removeClass('cgMediaAssignProcess cg_overflow_y_scroll');// remove potential set classes before
        if($cgBackendGalleryDynamicMessage.hasClass('cg_no_action_message')){
            if(isHideSlow){
                //#toDo
                //jQuery('#cgBackendBackgroundDrop').addClass('cg_hide_slow_1_sec').removeClass('cg_active cg_pointer_events_none');
                jQuery('#cgBackendBackgroundDrop').addClass('cg_hide').removeClass('cg_active cg_pointer_events_none');
            }else{
                jQuery('#cgBackendBackgroundDrop').addClass('cg_hide').removeClass('cg_active cg_pointer_events_none');
            }
        }else{
            if(isHideSlow){
                //#toDo
                //jQuery('#cgBackendBackgroundDrop,.cg_background_drop_content').addClass('cg_hide_slow_1_sec').removeClass('cg_active cg_pointer_events_none');
                jQuery('#cgBackendBackgroundDrop,.cg_background_drop_content').addClass('cg_hide').removeClass('cg_active cg_pointer_events_none');
            }else{
                jQuery('#cgBackendBackgroundDrop,.cg_background_drop_content').addClass('cg_hide').removeClass('cg_active cg_pointer_events_none');
            }
        }
        jQuery('body').removeClass('cg_no_scroll');
    },
    showCgBackendBackgroundDrop: function (isAllowScroll){
        // console.trace();
        jQuery('#cgBackendBackgroundDrop').removeClass('cg_hide').addClass('cg_active');
        if(!isAllowScroll){
            jQuery('body').addClass('cg_no_scroll');
        }
    },
    hideMultipleFilesContainerForPost: function ($){
        $('#cgMultipleFilesForPostContainer').addClass('cg_hide');
        cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();
    },
    hideShowAddAdditionalFilesLabel: function ($cg_preview_files_container,realId,$cg_backend_info_container){

        if(!$cg_preview_files_container.find('.cg_backend_image_add_files_label').length){
                $cg_preview_files_container.append('<div class="cg_hover_effect cg_backend_image_add_files_label cg_hide"></div>');
        }

        if(location.search.indexOf('page=contest-gallery-pro')>-1 && Object.keys(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId]).length<10){
            $cg_preview_files_container.find('.cg_backend_image_add_files_label').removeClass('cg_hide');
        }else{
            if(location.search.indexOf('page=contest-gallery-pro')==-1 && Object.keys(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId]).length<3){
                $cg_preview_files_container.find('.cg_backend_image_add_files_label').removeClass('cg_hide');
            }
        }

        if(Object.keys(cgJsClassAdmin.gallery.vars.multipleFilesForPost[realId]).length==1){
            $cg_backend_info_container.find('.cg_manage_multiple_files_for_post, .cg_manage_multiple_files_for_post_prev').addClass('cg_hide');
        }

    }
};
