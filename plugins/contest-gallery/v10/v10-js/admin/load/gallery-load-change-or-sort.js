cgJsClassAdmin.gallery.load.changeViewByControl = function ($,$cgStep,$cgStart,isFormSubmit,isInput,isRealFormSubmit,$selected,isFromSortingFiles) {

        $('#chooseAll').prop('checked',false);

        if($cgStep){
            $('#cgStepValue').val($cgStep.attr('data-cg-step-value'));
        }
        //console.log($('#cgStepsNavigationTop .cg_step.cg_step_selected').first().attr('data-cg-start'));
        //console.log($('#cgStepsNavigationTop .cg_step.cg_step_selected'));

        if($cgStart){
            $('#cgStartValue').val($cgStart.attr('data-cg-start'));
        }else{
            if(!isInput){
                $('#cgStartValue').val($('#cgStepsNavigationTop .cg_step.cg_step_selected').first().attr('data-cg-start'));
            }
        }

        var $cgGallerySubmit = $('#cgGallerySubmit').addClass('cg_hide');
        var $cgSortable = $('#cgSortable').addClass('cg_hide');
        var $cgGalleryLoader = $('#cgGalleryLoader').removeClass('cg_hide');
        var $cgStepsNavigationTop = $('#cgStepsNavigationTop').addClass('cg_hide');
        var $cgStepsNavigationBottom = $('#cgStepsNavigationBottom').addClass('cg_hide');


        var form = document.getElementById('cgGalleryForm');
        var $form = $(form);
        $form.find('.cg_disabled_send').prop('disabled',true).removeClass('cg_input_vars_count');
        console.log('how many fields send:');
        console.log($form.find('.cg_input_vars_count').length);
        var formPostData = new FormData(form);

        // !IMPORTANT!!!! Do not remove otherwise recursion error! needs to check first time new backend ajax version 10.9.9 null null is instaled
        formPostData.append('cgBackendHash',$('#cgBackendHash').val());

        if(isRealFormSubmit){
            formPostData.append('cgGalleryFormSubmit', true);
            formPostData.append('cgIsRealFormSubmit', true);
        }

        var gid = $('#cgBackendGalleryId').val();

        // BG is for backend gallery
        localStorage.setItem('cgStart_BG_'+gid, $('#cgStartValue').val());
        localStorage.setItem('cgStep_BG_'+gid, $('#cgStepValue').val());

        if($selected){
            if($selected.closest('#cgOrderSelectCustomFields').length || $selected.closest('#cgOrderSelectFurtherFields').length){
                localStorage.setItem('cgOrder_BG_'+gid, 'custom');
            }else{
                localStorage.setItem('cgOrder_BG_'+gid, $('#cgOrderSelect').val());
            }
        }else{
            localStorage.setItem('cgOrder_BG_'+gid, $('#cgOrderSelect').val());
        }

        localStorage.setItem('cgSearch_BG_'+gid, $('#cgSearchInput').val());

        if(isFormSubmit){
            var scrollTop = $('#cgGalleryForm').offset().top-50;
            $(window).scrollTop(scrollTop);
        }
        //  console.trace();

        // AJAX Call - Submit Form
        cgJsClassAdmin.gallery.functions.requests.push($.ajax({
            url: 'admin-ajax.php',
            method: 'post',
            data: formPostData,
            dataType: null,
            contentType: false,
            processData: false
        }).done(function(response) {

            if(cgJsClassAdmin.index.functions.isInvalidNonce($,response)){
                return;
            }

            // to go sure remove it on every load
            $('#cgDeleteOriginalImageSourceAlso').remove();

            if(response=='newversion'){

                var $cg_main_container = jQuery('#cg_main_container');
                cgJsClassAdmin.index.functions.cgMainContainerEmpty($cg_main_container);
                cgJsClassAdmin.index.functions.newVersionReload();
                return;
            }

            if(isFormSubmit){
                $('#cgGalleryFormSubmit').prop('disabled',false);
                cgJsClassAdmin.gallery.functions.setAndAppearBackendGalleryDynamicMessage('Changes saved',true);
            }

            if(cgJsClassAdmin.gallery.functions.missingRights($,response)){return;}
            cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();
            cgJsClassAdmin.gallery.vars.selectChanged = false;
            cgJsClassAdmin.gallery.vars.inputsChanged = false;

            var htmlDom = new DOMParser().parseFromString(response, 'text/html');

            var DOM_cgSortable = htmlDom.getElementById('cgSortable');
            var DOM_cgGallerySubmit = htmlDom.getElementById('cgGallerySubmit');
            var DOM_cgStepsNavigationTop = htmlDom.getElementById('cgStepsNavigationTop');
            var DOM_cgStepsNavigationBottom = htmlDom.getElementById('cgStepsNavigationBottom');
            var $cgCatWidgetTable = $(htmlDom.getElementById('cgCatWidgetTable'));
            $('#cgCatWidgetTable').replaceWith($cgCatWidgetTable);

            $cgGalleryLoader.addClass('cg_hide');

            $cgStepsNavigationTop.get(0).replaceWith(DOM_cgStepsNavigationTop);
            $(DOM_cgStepsNavigationTop).removeClass('cg_hide');
            $cgSortable.get(0).replaceWith(DOM_cgSortable);
            $(DOM_cgSortable).removeClass('cg_hide');

            var isNoImagesFound = false;

            // new cg sortable has to taken for search!!!
            if($(DOM_cgSortable).find('.cg_backend_info_container').length>=1){
                $cgGallerySubmit.replaceWith(DOM_cgGallerySubmit);
                $(DOM_cgGallerySubmit).removeClass('cg_hide');
                $('#cgViewControl').removeClass('cg_hide');
                $cgSortable.find('#cgNoImagesFound').addClass('cg_hide');
            }else{
                isNoImagesFound = true;
                $(DOM_cgSortable).find('#cgNoImagesFound').removeClass('cg_hide');
                //$(DOM_cgSortable).addClass('cg_hide');
                $('#cgGallerySubmit').addClass('cg_hide');
            }

            if($cgStepsNavigationBottom.length){
                $cgStepsNavigationBottom.get(0).replaceWith(DOM_cgStepsNavigationBottom);
                $(DOM_cgStepsNavigationBottom).removeClass('cg_hide');
            }

            cgJsClassAdmin.gallery.functions.markSearchedValueFields($);

            if(isFormSubmit){
                $("#cg_changes_saved").fadeOut(4000);
            }else{
                $("#cg_changes_saved").remove();
            }

            $('#cgStepsChanged').prop('disabled',true);

            cgJsClassAdmin.gallery.functions.sortableInit($);
            cgJsClassAdmin.gallery.functions.markSortedByCustomFields($);
            cgJsClassAdmin.gallery.functions.initDateTimePicker($);

            if(isNoImagesFound){
                cgJsClassAdmin.gallery.functions.checkIfFurtherImagesAvailable($);
            }

            if(isFromSortingFiles){
                cgJsClassAdmin.gallery.functions.setAndAppearBackendGalleryDynamicMessage('Entries sorted',true);
            }

            cgJsClassAdmin.index.functions.setCgNonce($);

        }).fail(function(xhr, status, error) {

        }).always(function() {

        }));


};
