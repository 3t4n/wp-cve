cgJsClassAdmin.createRegistry.vars = {
    isChecked: 0,
    cgRecaptchaIconUrl: null,
    cgDragIcon: null,
    countChildren: 0
};

cgJsClassAdmin.createRegistry.functions = {
    load: function ($, $formLinkObject, $response) {

        cgJsClassAdmin.index.vars.isCreateRegistryAreaLoaded = true;

        cgJsClassAdmin.index.functions.setEditors($, $response.find('#ausgabe1.cg_registry_form_container .cg-wp-editor-template'));

        cgJsClassAdmin.createRegistry.functions.cgCheckHideField($);

        cgJsClassAdmin.createRegistry.vars.cgDragIcon = $("#cgDragIcon").val();

        cgJsClassAdmin.createRegistry.vars.cgRecaptchaIconUrl = $("#cgRecaptchaIconUrl").val();

        $("#ausgabe1.cg_registry_form_container .cg-active input[type=\"checkbox\"]").each(function () {
            if ($(this).prop('checked') == true) {
                $(this).closest('.formField').addClass('cg_disable');
            }
            else {
                $(this).closest('.formField').removeClass('cg_disable');
            }
        });

        $("#cg_changes_saved").fadeOut(3000);

        if(location.hash.indexOf('cgGoToPasswordInputField') >= 0 || location.search.indexOf('cgGoToPasswordInputField') >= 0){

            cgJsClassAdmin.index.functions.correctBrowserHistoryState();

            var $cg_password_input_field = $('#cg_password_input_field');

            jQuery('html, body').animate({
                scrollTop: $cg_password_input_field.offset().top - 300+'px'
            }, 0, function () {
            });

            $cg_password_input_field.addClass('cg_blink');

            setTimeout(function (){
                $cg_password_input_field.removeClass('cg_blink');
            },2000);

        }

        $(function () {

            $("#ausgabe1.cg_registry_form_container").sortable({
                handle: ".cg_drag_area",
                cursor: "move",
                placeholder: "ui-state-highlight",
                scrollSpeed: 5,
                start: function (event, ui) {

                    var $element = ui.item;

                    $element.css('height','unset');
                    $element.find('.cg_view_options_row:not(.cg_view_options_row_title)').addClass('cg_hide');
                    $element.find('.cg_view_options_row_title').addClass('cg_border_bottom_thin_solid_default_color');
                    $element.closest('.formField').find('.cg_view_options_row_marker').removeClass('cg_hide').find('.cg_view_options_row_marker_content').text($element.closest('.formField').find('.cg_view_option_input_field_title').val());
                    $element.css('height',$element.height()+'px');
                    // condition for html fields. Deactivate by start first and reinitinalize by stop again later
                    var $cgWpEditorContainer = $element.find('.cg-wp-editor-container');
                    if ($cgWpEditorContainer.length) {

                        if(cgJsClassAdmin.index.vars.wpVersion>=cgJsClassAdmin.index.vars.wpVersionForTinyMCE){
                            tinymce.EditorManager.execCommand('mceRemoveEditor', true, $cgWpEditorContainer.attr('data-wp-editor-id'));
                        }

                    }
                    // condition for html fields. Deactivate by start first and reinitinalize by stop again later --- END
                    var $placeholder =  ui.placeholder;
                    $placeholder.height($element.height()).addClass($element.get(0).classList.value).html($element.html());

                },
                stop: function (event, ui) {

                    var $element = $(ui.item);
                    $element.css('height','');
                    if($('#cgCollapse').hasClass('cg_uncollapsed')){
                        $element.find('.cg_view_options_row:not(.cg_view_options_row_title)').removeClass('cg_hide');
                        $element.find('.cg_view_options_row_title').removeClass('cg_border_bottom_thin_solid_default_color');
                        $element.closest('.formField').find('.cg_view_options_row_marker').addClass('cg_hide');
                    }
                    // condition for html fields. Reinitialize after deactivating when start
                    var $cgWpEditorContainer = $element.find('.cg-wp-editor-container');
                    if ($cgWpEditorContainer.length) {
                        if(cgJsClassAdmin.index.vars.wpVersion>=cgJsClassAdmin.index.vars.wpVersionForTinyMCE){
                            tinymce.EditorManager.execCommand('mceAddEditor', true, $cgWpEditorContainer.attr('data-wp-editor-id'));
                        }
                    }
                    // condition for html fields. Reinitialize after deactivating when start--- END

                    // if (document.readyState === "complete") {
                    setTimeout(function () {
                        cgJsClassAdmin.createRegistry.functions.cgSortOrder($);
                        cgJsClassAdmin.createRegistry.functions.cgCheckHideField($);
                    },10);

                    // }

                }
            });


        });

        // Use as url for images

        cgJsClassAdmin.createRegistry.vars.isChecked = 0;

        $("#ausgabe1.cg_registry_form_container .Use_as_URL").each(function () {

            if ($(this).is(":checked")) {
                isChecked = 1;
            }

        });

        // Show info in gallery

        cgJsClassAdmin.createRegistry.vars.isChecked = 0;

        $("#ausgabe1.cg_registry_form_container .cg_info_show_gallery").each(function () {

            if ($(this).is(":checked")) {
                isChecked = 1;
            }

        });

        setTimeout(function () {
            // !IMPORTANT: Do it here when document ready!
            $('#ausgabe1.cg_registry_form_container .switch-tmce:visible').click();// !IMPORTANT: click only unvisible otherwise breaks functionality of further elements
        },10);

        $('#ausgabe1 .formField').each(function () {
            if($(this).find('.cg_view_option_hide_upload_field .cg_view_option_checkbox input').prop('checked')){
                $(this).addClass('cg_form_field_disabled');
                $(this).find('.cg_view_option:not(.cg_view_option_not_disable,.cg_view_option_hide_upload_field )').addClass('cg_disabled');
            }else{
                $(this).removeClass('cg_form_field_disabled');
                $(this).find('.cg_view_option:not(.cg_view_option_not_disable,.cg_view_option_hide_upload_field )').removeClass('cg_disabled');
            }
        });

        cgJsClassAdmin.options.vars.cg_registry_form_container_offset = $('#ausgabe1.cg_registry_form_container').offset().top;
        cgJsClassAdmin.options.vars.$cgRegFormSelect = $('#cgRegFormSelect');
        cgJsClassAdmin.options.vars.$wpadminbar = $('#wpadminbar');
        cgJsClassAdmin.options.vars.$cg_registry_form_container = $('#ausgabe1.cg_registry_form_container');
        cgJsClassAdmin.options.vars.wpadminbarHeight  = cgJsClassAdmin.options.vars.$wpadminbar.height();

    },
    collapseFields: function () {
        var $cg_registry_form_container = cgJsClassAdmin.options.vars.$cg_registry_form_container;
        $cg_registry_form_container.find('.formField .cg_view_options_row:not(.cg_view_options_row_title)').addClass('cg_hide');
        $cg_registry_form_container.find('.formField .cg_view_options_row_title').addClass('cg_border_bottom_thin_solid_default_color cg_view_options_row_uncollapse').removeClass('cg_view_options_row_collapse').attr('title','Uncollapse');
        $cg_registry_form_container.find('.cg_view_option_input_field_title').each(function (){
            jQuery(this).closest('.formField').find('.cg_view_options_row_marker').removeClass('cg_hide').find('.cg_view_options_row_marker_content').text(jQuery(this).val());
        });
    },
    uncollapseFields: function () {
        var $cg_registry_form_container = cgJsClassAdmin.options.vars.$cg_registry_form_container;
        $cg_registry_form_container.find('.formField .cg_view_options_row:not(.cg_view_options_row_title)').removeClass('cg_hide');
        $cg_registry_form_container.find('.formField .cg_view_options_row_title').removeClass('cg_border_bottom_thin_solid_default_color cg_view_options_row_uncollapse').addClass('cg_view_options_row_collapse').attr('title','Collapse');
        $cg_registry_form_container.find('.formField .cg_view_options_row_marker').addClass('cg_hide');
    },
    cgSortOrder: function ($) {

        var v = 0;

        $("#ausgabe1.cg_registry_form_container .formField").each(function (i) {

            v++;

            $(this).find('.Field_Type').attr("name", "Field_Type[" + v + "]");
            $(this).find('.Field_Order').attr("name", "Field_Order[" + v + "]");
            $(this).find('.Field_Name').attr("name", "Field_Name[" + v + "]");
            $(this).find('.Field_Id').attr("name", "Field_Id[" + v + "]");
            $(this).find('.Field_Content').attr("name", "Field_Content[" + v + "]");
            $(this).find('.Min_Char').attr("name", "Min_Char[" + v + "]");
            $(this).find('.Max_Char').attr("name", "Max_Char[" + v + "]");
            $(this).find('.necessary-check').attr("name", "Necessary[" + v + "]");
            $(this).find('.necessary-hide').attr("name", "Hide[" + v + "]");

        });

    },
    fDeleteFieldAndData: function ($, fieldContainerId, idToDelete) {

        $("#" + fieldContainerId + "").remove();
        $("#ausgabe1.cg_registry_form_container").append("<input type='hidden' name='deleteFieldnumber' value=" + idToDelete + ">");

        this.cgSortOrder($);

        $('#submitForm').click();

    },
    cgCheckHideField: function ($) {

        if ($('#ausgabe1.cg_registry_form_container .cg-active input[type="checkbox"]').length >= 1) {
            $('#ausgabe1.cg_registry_form_container .cg-active input[type="checkbox"]').each(function (index) {

                var order = index + 1;
                $(this).attr('name', 'hide[' + order + ']');

            });
        }

    },
    goToField: function ($) {
        $("html, body").animate({ scrollTop: $('#dauswahl').offset().top }, 0);
    }
};