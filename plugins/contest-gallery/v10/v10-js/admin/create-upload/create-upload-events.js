// !!! Nicht löschen Basis klasse für cgJsClassAdmin.createUpload.tinymce
var cgJsClassAdmin = cgJsClassAdmin || {};
cgJsClassAdmin.createUpload = {};

jQuery(document).ready(function ($) {

    $(document).on('click', '#submitForm', function () {
        $('#cgFieldsToCloneAndAppend').remove();
    });

    // hide field
    $(document).on('click', '.cg_view_option_hide_upload_field', function () {
        if($(this).find('.cg_view_option_checkbox input').prop('checked')){
            $(this).closest('.formField').addClass('cg_form_field_disabled').find('.cg_view_option:not(.cg_view_option_header,.cg_view_option_hide_upload_field,.cg_disabled_watermark)').addClass('cg_disabled');
        }else{
            $(this).closest('.formField').removeClass('cg_form_field_disabled').find('.cg_view_option:not(.cg_view_option_header,.cg_view_option_hide_upload_field,.cg_disabled_watermark)').removeClass('cg_disabled');
        }
    });

    // check watermark
    $(document).on('click', '.cg_view_option_watermark', function () {
        if($(this).find('.cg_view_option_checkbox input').prop('checked')){
            $(this).closest('.formField').find('.cg_view_option_watermark_position').removeClass('cg_disabled  cg_disabled_watermark');
            // remove cg_disabled has to be done additionally because might be done after load
        }else{
            $(this).closest('.formField').find('.cg_view_option_watermark_position').addClass('cg_disabled_watermark');
        }
        var id = $(this).closest('.formField').attr('id');
        $("#cgCreateUploadSortableArea .cg_view_option_watermark").each(function () {
            var idToCompare = $(this).closest('.formField').attr('id');
            if(id != idToCompare){
                $(this).find('.cg_view_option_checkbox').removeClass("cg_view_option_checked").addClass('cg_view_option_unchecked');
                $(this).find('.cg_view_option_checkbox input').prop("checked", false);
            }
        });
        $("#cgCreateUploadSortableArea .cg_view_option_watermark_position").each(function () {
            var idToCompare = $(this).closest('.formField').attr('id');
            if(id != idToCompare){
                $(this).addClass('cg_disabled');
            }
        });
    });

    $(document).on('change', '.cg_watermark_position', function () {
        $(this).closest('.formField').find('.cg_watermark_check').val($(this).val());
    });
    // check watermark --- END

    // #cgSelectFileType
    $(document).on('change', '#cgSelectFileType', function () {

        $('#cgFileTypeIMG').val('');
        $('#cgAlternativeFileTypePDF').val('');
        $('#cgAlternativeFileTypeZIP').val('');
        $('#cgAlternativeFileTypeTXT').val('');
        $('#cgAlternativeFileTypeDOC').val('');
        $('#cgAlternativeFileTypeDOCX').val('');
        $('#cgAlternativeFileTypeXLS').val('');
        $('#cgAlternativeFileTypeXLSX').val('');
        $('#cgAlternativeFileTypeCSV').val('');
        $('#cgAlternativeFileTypeMP3').val('');
        $('#cgAlternativeFileTypeM4A').val('');
        $('#cgAlternativeFileTypeOGG').val('');
        $('#cgAlternativeFileTypeWAV').val('');
        $('#cgAlternativeFileTypeMP4').val('');
        //$('#cgAlternativeFileTypeAVI').val('');
        $('#cgAlternativeFileTypeMOV').val('');
        $('#cgAlternativeFileTypeWEBM').val('');
        $('#cgAlternativeFileTypePPT').val('');
        $('#cgAlternativeFileTypePPTX').val('');
        //$('#cgAlternativeFileTypeWMV').val('');

        if($(this).val().length==0){
            alert('At least one upload file type has to be selected');
            $('#cgSelectFileType').val('img');// select img in select
            $('#cgFileTypeIMG').val('img');
        }else{
            //   if($fileTypeKey == 'txt' OR $fileTypeKey == 'doc' OR $fileTypeKey == 'xls' OR $fileTypeKey == 'csv' OR $fileTypeKey == 'ppt'){

            // For normal version!
            if($(this).val().length >= 18 && $('#cgProFalseCheck').val()){ // if strg+a was clicked
                $('#cgSelectFileType').val(['img','txt','doc','xls','csv','ppt']);// select img in select
                $('#cgFileTypeIMG').val('img');
                $('#cgAlternativeFileTypeTXT').val('txt');
                $('#cgAlternativeFileTypeDOC').val('doc');
                $('#cgAlternativeFileTypeXLS').val('xls');
                $('#cgAlternativeFileTypeCSV').val('csv');
                $('#cgAlternativeFileTypePPT').val('ppt');
            }else{
                $(this).val().forEach(function (value){
                    if(value=='img'){
                        $('#cgFileType'+value.toUpperCase()).val(value);
                    }else{
                        $('#cgAlternativeFileType'+value.toUpperCase()).val(value);
                    }
                });
            }
        }

    });
    // #cgSelectFileType --- END

    // #cgAlternativeFilePreviewHide
    $(document).on('change', '#cgAlternativeFilePreviewHide', function () {
        if($(this).prop('checked')){
            $('#cgAlternativeFileTitle').addClass('cg_disabled_background_color_e0e0e0');
        }else{
            $('#cgAlternativeFileTitle').removeClass('cg_disabled_background_color_e0e0e0');
        }
    });
    // #cgAlternativeFilePreviewHide --- END

    $(document).on('click', '#ausgabe1.cg_create_upload .cg_recaptcha_icon', function () {
        $(this).closest('.formField').find('.cg_reca_key').val($('#cgRecaptchaKey').val());
    });

    // Allow only to press numbers as keys in input boxes

    //called when key is pressed in textbox
    $(document).on('keypress', "#ausgabe1.cg_create_upload .Max_Char, .Min_Char", function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            //$("#cg_options_errmsg").html("Only numbers are allowed").show().fadeOut("slow");
            return false;
        }
    });

// Allow only to press numbers as keys in input boxes --- END

    $(document).on('click', '#ausgabe1.cg_create_upload .cg_info_show_gallery', function (e) {
        var id = $(this).closest('.formField').attr('id');

        $("#cgCreateUploadSortableArea .cg_view_option.cg_info_show_gallery").each(function () {

            var idToCompare = $(this).closest('.formField').attr('id');

            if(id != idToCompare){
                $(this).find('.cg_view_option_checkbox').removeClass("cg_view_option_checked").addClass('cg_view_option_unchecked');
                $(this).find('.cg_view_option_checkbox input').prop("checked", false);
            }

        });
    });


    $(document).on('click', '#ausgabe1.cg_create_upload .cg_info_show_gallery_sub_title', function (e) {
        var id = $(this).closest('.formField').attr('id');

        $("#cgCreateUploadSortableArea .cg_view_option.cg_info_show_gallery_sub_title").each(function () {

            var idToCompare = $(this).closest('.formField').attr('id');

            if(id != idToCompare){
                $(this).find('.cg_view_option_checkbox').removeClass("cg_view_option_checked").addClass('cg_view_option_unchecked');
                $(this).find('.cg_view_option_checkbox input').prop("checked", false);
            }

        });
    });


    $(document).on('click', '#ausgabe1.cg_create_upload .cg_info_show_gallery_third_title', function (e) {
        var id = $(this).closest('.formField').attr('id');

        $("#cgCreateUploadSortableArea .cg_view_option.cg_info_show_gallery_third_title").each(function () {

            var idToCompare = $(this).closest('.formField').attr('id');

            if(id != idToCompare){
                $(this).find('.cg_view_option_checkbox').removeClass("cg_view_option_checked").addClass('cg_view_option_unchecked');
                $(this).find('.cg_view_option_checkbox input').prop("checked", false);
            }

        });
    });


    $(document).on('click', '#ausgabe1.cg_create_upload .cg_entry_page_title', function (e) {
        var id = $(this).closest('.formField').attr('id');

        $("#cgCreateUploadSortableArea .cg_view_option.cg_entry_page_title").each(function () {

            var idToCompare = $(this).closest('.formField').attr('id');

            if(id != idToCompare){
                $(this).find('.cg_view_option_checkbox').removeClass("cg_view_option_checked").addClass('cg_view_option_unchecked');
                $(this).find('.cg_view_option_checkbox input').prop("checked", false);
            }

        });
    });


    $(document).on('click', '#ausgabe1.cg_create_upload .cg_entry_page_description', function (e) {
        var id = $(this).closest('.formField').attr('id');

        $("#cgCreateUploadSortableArea .cg_view_option.cg_entry_page_description").each(function () {
            var idToCompare = $(this).closest('.formField').attr('id');

            if(id != idToCompare){
                $(this).find('.cg_view_option_checkbox').removeClass("cg_view_option_checked").addClass('cg_view_option_unchecked');
                $(this).find('.cg_view_option_checkbox input').prop("checked", false);
            }
        });
    });

    $(document).on('click', '#ausgabe1.cg_create_upload .cg_tag_in_gallery', function (e) {

        var id = $(this).closest('.formField').attr('id');

        $("#cgCreateUploadSortableArea .cg_view_option.cg_tag_in_gallery").each(function () {

            var idToCompare = $(this).closest('.formField').attr('id');

            if(id != idToCompare){
                $(this).find('.cg_view_option_checkbox').removeClass("cg_view_option_checked").addClass('cg_view_option_unchecked');
                $(this).find('.cg_view_option_checkbox input').prop("checked", false);
            }

        });

    });


    // Show info in gallery -- ENDE


    // Show tag in gallery -- ENDE

    $(document).on('click', "#ausgabe1.cg_create_upload .cg-active input[type=\"checkbox\"] ", function () {
        if ($(this).prop('checked') == true) {
            $(this).closest('.formField').addClass('cg_disable');
        }
        else {
            $(this).closest('.formField').removeClass('cg_disable');
        }
    });


    $(document).on('click', '#ausgabe1.cg_create_upload .cg_delete_form_field, #ausgabe1.cg_create_upload .cg_remove:not(.cg_remove_new)', function (e) {

        var fieldContainerId = $(this).closest(".formField").attr('id');
        var idToDelete = $(this).attr('data-cg-id');

        var categoryField = false;

        if ($(this).hasClass('cg_is_category_main_field')) {
            categoryField = true;
        }

        var infoDeleteText = "";

        if ($(this).closest('.htmlField').length >= 1 || $(this).closest('.captchaRoField').length >= 1 || $(this).closest('.captchaRoReField').length >= 1) {
            infoDeleteText = "";
        } else {
            if ($(this).attr('data-field-type')) {
                if ($(this).attr('data-field-type') == 'fbt') {
                    infoDeleteText = "All Contest Gallery user information connected to this field will be deleted. Facebook share button image titles will be not deleted.";
                } else if ($(this).attr('data-field-type') == 'fbd') {
                    infoDeleteText = "All Contest Gallery user information connected to this field will be deleted. Facebook share button image descriptions will be not deleted.";
                } else {
                    infoDeleteText = "All Contest Gallery user information connected to this field will be deleted.";
                }
            } else {
                infoDeleteText = "All Contest Gallery user information connected to this field will be deleted.";
            }
        }

        if (confirm("Delete field? " + infoDeleteText + "")) {
            cgJsClassAdmin.createUpload.functions.fDeleteFieldAndData($,fieldContainerId, idToDelete, categoryField);
            return true;
        } else {
            return false;
        }


    });

    $(document).on('click', '#ausgabe1.cg_create_upload .cg_delete_form_field_new,#ausgabe1.cg_create_upload .cg_remove_new', function (e) {
        $(this).closest('.formField').remove();
    });

// Delete field only --- ENDE

    $(document).on('click', "#cgCreateUploadContainer .cg_view_options_row.cg_view_options_row_title.cg_view_options_row_collapse", function () {
        var $formField = $(this).closest('.formField');
        $formField.find('.cg_view_options_row:not(.cg_view_options_row_title)').addClass('cg_hide');
        $formField.find('.cg_view_options_row_title').addClass('cg_border_bottom_thin_solid_default_color cg_view_options_row_uncollapse').removeClass('cg_view_options_row_collapse').attr('title','Uncollapse');
        $formField.find('.cg_view_options_row_marker').removeClass('cg_hide').find('.cg_view_options_row_marker_content').text($formField.find('.cg_view_option_input_field_title').val());
    });

    $(document).on('click', "#cgCreateUploadContainer .cg_view_options_row.cg_view_options_row_title.cg_view_options_row_uncollapse", function () {
        var $formField = $(this).closest('.formField');
        $formField.find('.cg_view_options_row:not(.cg_view_options_row_title)').removeClass('cg_hide');
        $formField.find('.cg_view_options_row_title').removeClass('cg_border_bottom_thin_solid_default_color cg_view_options_row_uncollapse').addClass('cg_view_options_row_collapse').attr('title','Collapse');
        $formField.find('.cg_view_options_row_marker').addClass('cg_hide');
    });

    $(document).on('click', "#cgUploadFieldsSelect #cgCollapse", function () {
        if($(this).hasClass('cg_uncollapsed')){
            $(this).addClass('cg_collapsed').removeClass('cg_uncollapsed').text('Uncollapse all');
            cgJsClassAdmin.createUpload.functions.collapseFields();
        }else if($(this).hasClass('cg_collapsed')){
            $(this).addClass('cg_uncollapsed').removeClass('cg_collapsed').text('Collapse all');
            cgJsClassAdmin.createUpload.functions.uncollapseFields();
        }
    });

    $(document).on('change', "#dauswahl.cg_upload_dauswahl", function () {
        if ($('select[name="dauswahl"] :selected').hasClass('cg-pro-false')) {
            $(this).css('background-color', '#95ff79');
            $(this).find('option').not('cg-pro-false').css('background-color', '#fff');
            $(this).find('optgroup').css('background-color', '#fff');
        } else {
            $(this).css('background-color', '#fff');
        }

    });

    var newFieldCounter = 0;

    var cloneAndAppendNewField = function (fieldClass){
        newFieldCounter++;

        var $newField = $('#cgFieldsToCloneAndAppend').find('.'+fieldClass).clone();
        $newField.attr('id','new-'+newFieldCounter);
        $newField.find('select,input,textarea').each(function (){
            if($(this).attr('name')){
                $(this).attr('name',$(this).attr('name').replace(/(?<=upload\[)(.*)(?=\]\[)/s, 'new-'+newFieldCounter));
            }
        });
        $newField.find('.cg-wp-editor-container').remove();
        $newField.find('.cg_view_option_html').append($('<div class="cg-wp-editor-container" data-wp-editor-id="new-html-'+newFieldCounter+'">' +
            '<textarea class="cg-wp-editor-template" id="new-html-'+newFieldCounter+'" name="upload[new-'+newFieldCounter+'][content]" ></textarea></div>'));
        $newField.find('.cg_remove').addClass('cg_remove_new');
        $newField.append($("<input type='hidden' name='upload[new-" + newFieldCounter + "][new]' value='on'>"));
        if($('#cgPlace').val()=='place-top'){
            $("#ausgabe1.cg_create_upload .cg_sortable_area").prepend($newField);
        }else {
            $("#ausgabe1.cg_create_upload .cg_sortable_area").append($newField);
        }
        if(fieldClass == 'htmlField' || fieldClass=='checkAgreementField'){
            cgJsClassAdmin.index.functions.initializeEditor('new-html-'+newFieldCounter);
        }


        // bind this event as next before anything else
        $newField.find('.cg_view_option').each(function (){
            $(this).get(0).addEventListener("click", function (e){
                cgJsClassAdmin.options.functions.cgViewOptionCheck(this,e);
            });
        });

        if(fieldClass == 'selectCategoriesField'){
            cgJsClassAdmin.createUpload.functions.setSortableCategoriesArena($,$newField.find(".cg_categories_arena"));
        }

        if($('#cgCollapse').hasClass('cg_collapsed')){
            cgJsClassAdmin.createUpload.functions.collapseFields();
        }

        $newField.addClass('cg_blink');
        setTimeout(function (){
            $newField.removeClass('cg_blink');
        },2000);

        $newField.get(0).scrollIntoView({behavior: 'auto', block: 'start', inline: 'start'});

        //cgJsClassAdmin.createUpload.functions.goToField($,true);

    }

    $(document).on('click', "#cg_create_upload_add_field.cg_upload_dauswahl", function () {

        var cg_info_show_slider_title = 'Show as info in single file view';
        var cg_info_show_gallery_title = 'Show as title in gallery view (only 1 allowed)';
        var cg_tag_show_gallery_title = 'Show as HTML title attribute in gallery (only 1 allowed)';

        // User Fields here

        if ($('#dauswahl').val() == "cb") {// CHECK AGREEMENT!!!!!!!

            if ($('select[name="dauswahl"] :selected').hasClass('cg-pro-false')) {
                alert('Only available in PRO version');
                return;
            }

            if ($('#cgCreateUploadSortableArea .checkAgreementField').length == 10) {
                alert("This field can be produced maximum 10 times");
            }
            else {
                cloneAndAppendNewField('checkAgreementField');
            }

        }

        // TEXT FIELD!!!!!
        if ($('#dauswahl').val() == "nf") {

            if ($('#cgCreateUploadSortableArea .inputField').length == 20) {
                alert("This field can be produced maximum 20 times");
            }
            else {
                cloneAndAppendNewField('inputField');
            }

        }

        // DATE FIELD!!!!!
        if ($('#dauswahl').val() == "dt") {

            if ($('select[name="dauswahl"] :selected').hasClass('cg-pro-false')) {
                alert('Only available in PRO version');
                return;
            }

            if ($('#cgCreateUploadSortableArea .dateTimeField').length == 10) {
                alert("This field can be produced maximum 10 times");
            }
            else {
                cloneAndAppendNewField('dateTimeField');
            }

        }

        if ($('#dauswahl').val() == "url") {



            if ($('#cgCreateUploadSortableArea .urlField').length == 20) {
                alert("This field can be produced maximum 10 times");
            }
            else {
                cloneAndAppendNewField('urlField');
            }

        }

        if ($('#dauswahl').val() == "kf") {



            if ($('#cgCreateUploadSortableArea .textareaField').length == 10) {
                alert("This field can be produced maximum 10 times");
            }else {
                cloneAndAppendNewField('textareaField');
            }

        }

        if ($('#dauswahl').val() == "se") {



            if ($('#cgCreateUploadSortableArea .selectField').length == 10) {
                alert("This field can be produced maximum 10 times");
            }else {
                cloneAndAppendNewField('selectField');
            }

        }

        if ($('#dauswahl').val() == "sec") {



            if ($('#cgCreateUploadSortableArea .selectCategoriesField').length == 1) {
                alert("This field can be produced maximum 1 time");
            }else {
                cloneAndAppendNewField('selectCategoriesField');
            }

        }

        if ($('#dauswahl').val() == "ef") {

            if ($('select[name="dauswahl"] :selected').hasClass('cg-pro-false')) {
                alert('Only available in PRO version');
                return;
            }



            if ($('#cgCreateUploadSortableArea .emailField').length == 1) {
                alert("This field can be produced only 1 time");
            }
            else {
                cloneAndAppendNewField('emailField');
            }

        }

        if ($('#dauswahl').val() == "ht") {

            if ($('select[name="dauswahl"] :selected').hasClass('cg-pro-false')) {
                alert('Only available in PRO version');
                return;
            }



            if ($('#cgCreateUploadSortableArea .htmlField').length >= 10) {
                alert("This field can be produced maximum 10 times");
            }
            else {
                cloneAndAppendNewField('htmlField');
            }

        }

        if ($('#dauswahl').val() == "caRo") {

            if ($('#cgCreateUploadSortableArea .captchaRoField').length >= 1) {
                alert("This field can be produced maximum 1 time");
            }
            else {
                cloneAndAppendNewField('captchaRoField');
            }

        }

        if ($('#dauswahl').val() == "caRoRe") {
            if ($('#cgCreateUploadSortableArea .captchaRoReField').length >= 1) {
                alert("This field can be produced maximum 1 time");
            }else {
                cloneAndAppendNewField('captchaRoReField');
            }
        }

        cgJsClassAdmin.createUpload.functions.addRightFieldOrder($);

        setTimeout(function () {
            $('.cg_blink').removeClass('cg_blink');
        }, 2000);

    });


    $(document).on('click', '#ausgabe1.cg_create_upload .cg_add_category', function () {

        var length = $(this).closest('.formField').find('.cg_categories_arena .cg_category_field_div').length;
        if (length < 1) {
            length = 1;
            var placeholder = 'Category' + length;
        }
        else if (length == 1) {
            length = 2;
            var placeholder = 'Category' + length;
        }
        else {
            length = length + 1;
            var placeholder = 'Category' + length;
        }

        var cg_categories_arena = $(this).closest('.selectCategoriesField').find('.cg_categories_arena');

        var $cg_category_field_div = $('<div class="cg_category_field_div">' +
            "<div class='cg_remove_category cg_is_category' title='Remove category' ></div>" +
            "<div class='cg_drag_area_1' ><img class='cg_drag_area_icon' src='"+$('#cgDragIcon').val()+"'></div>" +
            '<div>' +
            '<div class="cg_name_field_and_delete_button_container">' +
            '<input class="cg_category_field" placeholder="' + placeholder + '" name="cg_category[]" type="text" value="'+placeholder+'" />' +
            '</div>' +
            '</div>' +
            '</div>');

        cg_categories_arena.prepend($cg_category_field_div);
        $cg_category_field_div.addClass('cg_blink');

    });


    $(document).on('click', '#ausgabe1.cg_create_upload .cg_remove_category', function (e) {

        e.preventDefault();

        var categoryIDtoRemove = $(this).attr('data-cg-id');

        if (categoryIDtoRemove) {

            if (confirm("Delete category field? All Contest Gallery user information connected to this field will be deleted.")) {

                $(this).closest('.cg_category_field_div').remove();

                $('#ausgabe1.cg_create_upload').append("<input type='input' name='deleteCategory' value='" + categoryIDtoRemove + "'>");
                localStorage.setItem('cg_remove_category',1);
                $('#submitForm').click();

                return true;

            } else {

                return false;

            }
        }
        else {
            $(this).closest('.cg_category_field_div').remove();
        }

    });

    $(document).on('click','#cgSaveContactFormNavButton',function (e) {
        //cgViewOptionCheck(this,e);
        $('#cgCreateUploadForm #submitForm').click();
    });


});