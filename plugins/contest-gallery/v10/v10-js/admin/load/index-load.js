cgJsClassAdmin.index.functions.cgLoadBackendAjax = function (urlString,formPostData,$formLinkObject,submitMessage,cg_picture_id_to_scroll) {

    cgJsClassAdmin.index.vars.isCreateUploadAreaLoaded = false;
    cgJsClassAdmin.index.vars.isOptionsAreaLoaded = false;
    cgJsClassAdmin.index.vars.isCreateRegistryAreaLoaded = false;
    var $ = jQuery;
    if(!$formLinkObject){
        $formLinkObject = $('<div></div>');
    }

    var version = cgJsClassAdmin.index.functions.cgGetVersionForUrlJs();

    if(urlString === '?page='+version+'/index.php'){// only set nonce at the beginning then
        var cg_nonce = $('#cg_nonce').val();
        urlString += '&cg_nonce='+cg_nonce;
    }

    cgJsClassAdmin.index.vars.$cg_main_container.addClass('cg_pointer_events_none');
    // AJAX Call - Submit Form
    $.ajax({
        url: 'admin-ajax.php'+urlString,
        method: 'post',
        data: formPostData,
        dataType: null,
        contentType: false,
        processData: false
    }).done(function(response) {

        if(cgJsClassAdmin.index.functions.isInvalidNonce($,response)){
            return;
        }

        cgJsClassAdmin.index.vars.$cg_main_container.removeClass('cg_pointer_events_none');

        cgJsClassAdmin.index.functions.noteIfIsIE();

        // cgJsClassAdmin.gallery.vars.isHashJustChanged = true;
        //console.log('urlString');
        //console.log(urlString);
        var $response = $(new DOMParser().parseFromString(response, 'text/html'));
        var cgVersionCurrent = $response.find('#cgVersion').val();

        cgJsClassAdmin.index.functions.cgSetVersionForUrlJs($response.find('#cgGetVersionForUrlJs').val());

        if(cgJsClassAdmin.index.vars.cgVersion===0){
            cgJsClassAdmin.index.vars.cgVersion = cgVersionCurrent;
        }

        if(cgJsClassAdmin.index.vars.cgVersion !== 0 && (cgJsClassAdmin.index.vars.cgVersion!=cgVersionCurrent)){

            cgJsClassAdmin.index.functions.newVersionReload();

            return;

        }else{

            // set always this as backup! Before set in indexedDB

            // since 25.12.2020, simple version check, no localStorage or IndexedDB check anymore
            //localStorage.setItem(cgJsClassAdmin.index.vars.cgVersionLocalStorageName, cgVersionCurrent);

            // IMPORTANT!!!! Has to be set everytime here!!!!!
            // since 25.12.2020, simple version check, no localStorage or IndexedDB check anymore
            //cgJsClassAdmin.index.indexeddb.setAdminData(cgVersionCurrent);

            var $cg_main_container = $('#cg_main_container');
            cgJsClassAdmin.index.functions.cgMainContainerEmpty($cg_main_container);

            if(!$formLinkObject.hasClass('cg_load_backend_copy_gallery') && submitMessage){
                cgJsClassAdmin.gallery.functions.setAndAppearBackendGalleryDynamicMessage(submitMessage,true);
            }

            cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();
            $cg_main_container.find('#cgBackendLoader').remove();

            $cg_main_container.append($response.find('body').html());// stats with html and contains body. Body content has to be inserted. Otherwise error because html can not be inserted in html.
            $('#cgGalleryLoader').addClass('cg_hide');

            if($formLinkObject.hasClass('cg_load_backend_copy_gallery')){

                if($cg_main_container.find('#cgProcessedImages').length){
                    $formLinkObject.find('.cg_copy_start').val($cg_main_container.find('#cgProcessedImages').val());
                    $formLinkObject.find('.option_id_next_gallery').val($cg_main_container.find('#cgNextIdGallery').val());
                    cgJsClassAdmin.index.functions.cgLoadBackend($formLinkObject,true,true,true,true);
                }else{
                    if($cg_main_container.find('#cgGalleryBackendDataManagement').length){
                        cgJsClassAdmin.gallery.vars.isHashJustChanged = true;
                        location.hash = '#option_id='+$cg_main_container.find('#cgNextIdGallery').val()+'&edit_gallery=true';
                        cgJsClassAdmin.gallery.load.init($,false,$formLinkObject,undefined,undefined,cg_picture_id_to_scroll);
                    }
                }

            }else{

                // setTimeout removed since 14.0.4
                setTimeout(function () {
                    //$("#cg_main_options").addClass('cg_fade_in_0_2');
                    $("#cg_main_options").removeClass('cg_hidden');
                    $("#cg_save_all_options").removeClass('cg_hidden');
                },50);

                if($cg_main_container.find('#cgGalleryBackendDataManagement').length){
                    cgJsClassAdmin.gallery.load.init($,false,$formLinkObject,undefined,undefined,cg_picture_id_to_scroll);
                }

                if($cg_main_container.find('#cg_main_options').length){
                    cgJsClassAdmin.options.functions.loadOptionsArea($,$formLinkObject,$response);
                }

                if($cg_main_container.find('#ausgabe1.cg_create_upload').length){
                    cgJsClassAdmin.createUpload.functions.load($,$formLinkObject,$response);
                }

                if($cg_main_container.find('#ausgabe1.cg_registry_form_container').length){
                    cgJsClassAdmin.createRegistry.functions.load($,$formLinkObject,$response);
                }

                if($cg_main_container.find('#cgMainMenuTable').length){
                    cgJsClassAdmin.mainMenu.functions.load($,$formLinkObject,$response);
                }

                if($cg_main_container.find('#cgImgThumbContainerMain').length){
                    $("#cg_rotate_image").addClass('cg_hidden');
                    setTimeout(function (){// is set to go sure content loaded when adding height to rotate container
                        cgJsClassAdmin.gallery.functions.cgRotateOnLoad($);
                        $("#cg_rotate_image").removeClass('cg_hidden');
                    },200);
                }

                $("#cg_changes_saved").fadeOut(4000);
                //cgJsClassAdmin.gallery.vars.cgLoadOptions($);
                //window.scrollTo(0,0);

            }

        }

        cgJsClassAdmin.index.functions.noteIfIsIE();

        cgJsClassAdmin.index.functions.resize(cgJsClassAdmin.index.vars.$wpBodyContent,cgJsClassAdmin.index.vars.$cg_main_container);

        var $cgCreatedNewGallery = $('#cgCreatedNewGallery');
        var $cgEditOptionsButton = $('#cgEditOptionsButton');
        var $cgDocumentation = $('#cgDocumentation');

        if($cgCreatedNewGallery.length){
            $cgCreatedNewGallery.get(0).scrollIntoView();
        }else if($cgEditOptionsButton.length){
            $cgEditOptionsButton.get(0).scrollIntoView();
        }else if($cgDocumentation.length){
            $cgDocumentation.get(0).scrollIntoView();
        }

       // setTimeout(function (){
            //$formLinkObject.remove();
       // },2000);

//       setTimeout(function (){
            // bind this event as next before anything else

        cgJsClassAdmin.index.functions.setCgNonce($);

    }).fail(function(xhr, status, error) {
        cgJsClassAdmin.index.functions.noteIfIsIE();
    }).always(function() {

    });
}