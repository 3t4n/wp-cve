var cgJsClassAdmin = cgJsClassAdmin || {};
cgJsClassAdmin.createRegistry = {};

jQuery(document).ready(function ($) {

    $(document).on('click', '#ausgabe1.cg_registry_form_container .cg_recaptcha_icon', function () {
        $(this).closest('.formField').find('.cg_reca_key').val($('#cgRecaptchaKey').val());
    });

    $(document).on('click', "#ausgabe1.cg_registry_form_container .cg-active input[type=\"checkbox\"] ", function () {
        if ($(this).prop('checked') == true) {
            $(this).closest('.formField').addClass('cg_disable');
        }
        else {
            $(this).closest('.formField').removeClass('cg_disable');
        }
    });

// Allow only to press numbers as keys in input boxes

    //called when key is pressed in textbox
    $(document).on('keypress', "#ausgabe1.cg_registry_form_container .Max_Char, .Min_Char", function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            //$("#cg_options_errmsg").html("Only numbers are allowed").show().fadeOut("slow");
            return false;
        }
    });

// Allow only to press numbers as keys in input boxes --- END

    $(document).on('click', '#ausgabe1.cg_registry_form_container .Use_as_URL', function (e) {
        //	$(".cg_info_show_gallery").click(function(){

        if ($(this).is(":checked") && isChecked == 1) {
            cgJsClassAdmin.createRegistry.vars.isChecked = 0;
        }


        if (cgJsClassAdmin.createRegistry.vars.isChecked == 1) {


            $(this).prop("checked", false);
            cgJsClassAdmin.createRegistry.vars.isChecked = 0;
        }


        else {
            $(".Use_as_URL").each(function () {

                $(".Use_as_URL").prop("checked", false);

            });

            $(this).prop("checked", true);


            cgJsClassAdmin.createRegistry.vars.isChecked = 1;


        }


    });


    // Use as url for images --- ENDE


    $(document).on('click', '#ausgabe1.cg_registry_form_container .cg_info_show_gallery', function (e) {
        //	$(".cg_info_show_gallery").click(function(){


        if ($(this).is(":checked") && cgJsClassAdmin.createRegistry.vars.isChecked == 1) {
            cgJsClassAdmin.createRegistry.vars.isChecked = 0;
        }


        if (cgJsClassAdmin.createRegistry.vars.isChecked == 1) {


            $(this).prop("checked", false);
            cgJsClassAdmin.createRegistry.vars.isChecked = 0;
        }


        else {
            $(".cg_info_show_gallery").each(function () {

                $(".cg_info_show_gallery").prop("checked", false);

            });

            $(this).prop("checked", true);


            cgJsClassAdmin.createRegistry.vars.isChecked = 1;


        }

    });

    // Show info in gallery -- ENDE

    $(document).on('click', '#ausgabe1.cg_registry_form_container .cg_remove:not(.cg_remove_new)', function (e) {

        var fieldContainerId = $(this).closest(".formField").attr('id');
        var idToDelete = $(this).attr('data-cg-id');

        var confirmText = "Delete field? All Contest Gallery user information connected to this field will be deleted.";
        if($(this).closest('.formField').find('.cg_Field_WP_FN').length){
            confirmText = "Delete field? WordPress first names of users registered over this form will be not deleted. Only this field will be removed from form.";
        }
        if($(this).closest('.formField').find('.cg_Field_WP_LN').length){
            confirmText = "Delete field? WordPress last names of users registered over this form will be not deleted. Only this field will be removed from form.";
        }
        if($(this).closest('.formField').find('.cg_Field_Profile_Image_Type').length){
            confirmText = "Delete field? Profile images of users registered over this form will be not deleted. Only this field will be removed from form. Profile images will be not editable for logged in users anymore. You can also hide this field if you want only to make this field invisible in the form.";
        }

        if ($(this).closest('.formField').find('.cg_Field_Robot_Type').length || $(this).closest('.formField').find('.cg_Field_HTML_Type').length) {
            confirmText = "Delete field?";
        }

        if (confirm(confirmText)) {
            cgJsClassAdmin.createRegistry.functions.fDeleteFieldAndData($, fieldContainerId, idToDelete);
            return true;
        } else {
            return false;
        }

    });

    $(document).on('click', '#ausgabe1.cg_registry_form_container .cg_remove_new', function (e) {
        $(this).closest('.formField').remove();
    });


// Delete field and Data --- ENDE

    $(document).on('click', "#ausgabe1.cg_registry_form_container .cg_view_options_row.cg_view_options_row_title.cg_view_options_row_collapse", function () {
        var $formField = $(this).closest('.formField');
        $formField.find('.cg_view_options_row:not(.cg_view_options_row_title)').addClass('cg_hide');
        $formField.find('.cg_view_options_row_title').addClass('cg_border_bottom_thin_solid_default_color cg_view_options_row_uncollapse').removeClass('cg_view_options_row_collapse').attr('title','Uncollapse');
        $formField.find('.cg_view_options_row_marker').removeClass('cg_hide').find('.cg_view_options_row_marker_content').text($formField.find('.cg_view_option_input_field_title').val());
    });

    $(document).on('click', "#ausgabe1.cg_registry_form_container .cg_view_options_row.cg_view_options_row_title.cg_view_options_row_uncollapse", function () {
        var $formField = $(this).closest('.formField');
        $formField.find('.cg_view_options_row:not(.cg_view_options_row_title)').removeClass('cg_hide');
        $formField.find('.cg_view_options_row_title').removeClass('cg_border_bottom_thin_solid_default_color cg_view_options_row_uncollapse').addClass('cg_view_options_row_collapse').attr('title','Collapse');
        $formField.find('.cg_view_options_row_marker').addClass('cg_hide');
    });

    $(document).on('click', "#cgRegFormSelect #cgCollapse", function () {
        if($(this).hasClass('cg_uncollapsed')){
            $(this).addClass('cg_collapsed').removeClass('cg_uncollapsed').text('Uncollapse all');
            cgJsClassAdmin.createRegistry.functions.collapseFields();
        }else if($(this).hasClass('cg_collapsed')){
            $(this).addClass('cg_uncollapsed').removeClass('cg_collapsed').text('Collapse all');
            cgJsClassAdmin.createRegistry.functions.uncollapseFields();
        }
    });

    $(document).on('change', "#dauswahl.cg_registry_dauswahl", function () {

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
            '<textarea class="cg-wp-editor-template Field_Content" id="new-html-'+newFieldCounter+'" name="Field_Content[0]" ></textarea></div>'));
        $newField.find('.cg_remove').addClass('cg_remove_new');
        $newField.find('.Field_Id').remove();
        if($('#cgPlace').val()=='place-top'){
            $("#ausgabe1.cg_registry_form_container").prepend($newField);
        }else {
            $("#ausgabe1.cg_registry_form_container").append($newField);
        }
        if(fieldClass == 'regHtmlField' || fieldClass=='regCheckAgreementField'){
            cgJsClassAdmin.index.functions.initializeEditor('new-html-'+newFieldCounter);
        }

        // bind this event as next before anything else
        $newField.find('.cg_view_option').each(function (){
            $(this).get(0).addEventListener("click", function (e){
                cgJsClassAdmin.options.functions.cgViewOptionCheck(this,e);
            });
        });
        //cgJsClassAdmin.createRegistry.functions.goToField($);

        if($('#cgCollapse').hasClass('cg_collapsed')){
            cgJsClassAdmin.createRegistry.functions.collapseFields();
        }

        $newField.addClass('cg_blink');
        setTimeout(function (){
            $newField.removeClass('cg_blink');
        },2000);

        $newField.get(0).scrollIntoView({behavior: 'auto', block: 'start', inline: 'start'});

    }

    $(document).on('click', "#cg_create_upload_add_field.cg_registry_dauswahl", function () {

        var i = $('.formField').length;
        i++;

        if ($('#dauswahl').val() == "cb") {// Agreement field!!!

            if ($('select[name="dauswahl"] :selected').hasClass('cg-pro-false')) {
                alert('Only available in PRO version');
                return;
            }

            if ($('#ausgabe1 .regCheckAgreementField').length == 10) {
                alert("This field can be produced maximum 10 times.");
            }
            else {
                cloneAndAppendNewField('regCheckAgreementField');
            }

        }

        if ($('#dauswahl').val() == "pi") {// wpfn text field!

            if ($('select[name="dauswahl"] :selected').hasClass('cg-pro-false')) {
                alert('Only available in PRO version');
                return;
            }

            if ($('#ausgabe1 .regProfileImageField').length == 1) {
                alert("This field can be added only once.");
            }
            else {
                cloneAndAppendNewField('regProfileImageField');
            }

        }

        if ($('#dauswahl').val() == "wpfn") {// wpfn text field!

            if ($('#ausgabe1 .wpFirstNameField').length == 1) {
                alert("This field can be added only once.");
            }
            else {
                cloneAndAppendNewField('wpFirstNameField');
            }

        }

        if ($('#dauswahl').val() == "wpln") {// wpln text field!

            if ($('#ausgabe1 .wpLastNameField').length == 1) {
                alert("This field can be added only once.");
            }
            else {
                cloneAndAppendNewField('wpLastNameField');
            }

        }

        if ($('#dauswahl').val() == "nf") {// text field!

            if ($('#ausgabe1 .regInputField').length == 20) {
                alert("This field can be produced maximum 20 times.");
            }else {
                cloneAndAppendNewField('regInputField');
            }

        }


        if ($('#dauswahl').val() == "kf") {

            if ($('#ausgabe1 .regTextareaField').size() == 10) {
                alert("This field can be produced maximum 10 times.");
            }else {
                cloneAndAppendNewField('regTextareaField');
            }

        }

        if ($('#dauswahl').val() == "ht") {

            if ($('select[name="dauswahl"] :selected').hasClass('cg-pro-false')) {
                alert('Only available in PRO version');
                return;
            }

            if ($('#ausgabe1 .regHtmlField').length >= 10) {
                alert("This field can be produced maximum 10 times.");
            }
            else {
                cloneAndAppendNewField('regHtmlField');
            }

        }

        if ($('#dauswahl').val() == "se") {

            if ($('#ausgabe1 .regSelectField').length == 10) {
                alert("This field can be produced maximum 10 times.");
            }else {
                cloneAndAppendNewField('regSelectField');
            }

        }

        if ($('#dauswahl').val() == "caRo") {

            if ($('#ausgabe1 .regCaptchaRoField').length >= 1) {
                alert("This field can be produced maximum 1 time.");
            }
            else {
                cloneAndAppendNewField('regCaptchaRoField');
            }

        }

        if ($('#dauswahl').val() == "caRoRe") {

            if ($('#ausgabe1 .regCaptchaRoReField').length >= 1) {
                alert("This field can be produced maximum 1 time.");
            }
            else {
                cloneAndAppendNewField('regCaptchaRoReField');
            }

        }

        cgJsClassAdmin.createRegistry.functions.cgSortOrder($);
        cgJsClassAdmin.createRegistry.functions.cgCheckHideField($);

        setTimeout(function () {
            $('.cg_blink').removeClass('cg_blink');
        }, 2000);

    });


    /*$("#cg_create_upload_add_field").click(function(){

  alert("This option is not available in the Lite Version.");

   });*/

    $(document).on('click','#cgSaveRegistryFormNavButton',function (e) {
        //cgViewOptionCheck(this,e);
        $('#cg_create_user_form #submitForm').click();
    });


});