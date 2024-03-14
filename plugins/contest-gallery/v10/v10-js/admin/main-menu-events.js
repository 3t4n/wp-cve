cgJsClassAdmin.general.time.init();

jQuery(document).ready(function ($) {

    $(document).on('click','body.cg_upload_modal_opened',function (e) {

        if($(e.target).closest('#cgCopyMessageContainer').length==1){
            return;
        }else{
            $('body').removeClass('cg_upload_modal_opened');
            $('#cgCopyMessageContainer').addClass('cg_hide');
        }
    });

    $(document).on('click','#cgCopyMessageClose',function (e) {
        $('body').removeClass('cg_upload_modal_opened');
        $('#cgCopyMessageContainer').addClass('cg_hide');
        cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();
    });

    $(document).on('click','.cg_copy_submit',function (e) {

        if($(this).hasClass('cg_submitted')){return;}
        e.preventDefault();

        cgJsClassAdmin.mainMenu.vars.formLinkObject = $(this).closest('form');

        var $cgCopyMessageContainer = $('#cgCopyMessageContainer');
        var $table_gallery_info = $(this).closest('.table_gallery_info');
        var left = $('.table_gallery_info').width()/2-$cgCopyMessageContainer.width()/2+$('.table_gallery_info').offset().left;

        $cgCopyMessageContainer.css('left',left+'px');
        $cgCopyMessageContainer.removeClass('cg_hide');
        // otherwise instant initiating and off click
        $('body').addClass('cg_upload_modal_opened');
        $('#cgCopyMessageSubmit').attr('data-cg-copy-id',$(this).attr('data-cg-copy-id'));
        $('#cgCopyMessageContentHeader').text('Copy gallery ID '+$(this).attr('data-cg-copy-id')+' ?');

        // only everything is possible to copy for old versions!
        if(parseInt($(this).attr('data-cg-version-to-copy'))<10){
            $cgCopyMessageContainer.find('.cg_copy_type_options_container,.cg_copy_type_options_and_images_container').addClass('cg_hide');
            $cgCopyMessageContainer.find('#cg_copy_type_all').prop('checked',true);
        }else{
            $cgCopyMessageContainer.find('.cg_copy_type_options_container,.cg_copy_type_options_and_images_container').removeClass('cg_hide');
        }

        $cgCopyMessageContainer.find('.cg-copy-prev-7-text').remove();

        if($table_gallery_info.find('.cg-copy-prev-7-text').length){

            $table_gallery_info.find('.cg-copy-prev-7-text').clone().removeClass('cg_hide').insertBefore($cgCopyMessageContainer.find('#cgCopyMessageSubmitContainer'));

        }else{
            $cgCopyMessageContainer.find('.cg-copy-prev-7-text').remove();
        }

        if($(this).attr('data-cg-copy-fb-on')==1){
            $('#cg_copy_type_all_fb_hint').removeClass('cg_hide');
        }else{
            $('#cg_copy_type_all_fb_hint').addClass('cg_hide');
        }

        if($(this).attr('data-cg-copy-for-v14-explanation')==1){
            $('#cg_copy_for_v14_explanation').removeClass('cg_hide');
        }else{
            $('#cg_copy_for_v14_explanation').addClass('cg_hide');
        }

        cgJsClassAdmin.gallery.functions.showCgBackendBackgroundDrop();

    });

    $(document).on('click','#cgCopyMessageSubmit',function (e) {
        e.preventDefault(e);
        var $form = $('#cgCopySubmit'+$(this).attr('data-cg-copy-id')).closest('form');
        var $cg_copy_type = $('.cg_copy_type:checked').clone().addClass('cg_hide');
        $form.prepend($cg_copy_type);
        $('#cgCopyMessageContainer').addClass('cg_hide');
        $('#mainTable').addClass('cg_hide');
        $('#cgCopyInProgressOnSubmit').removeClass('cg_hide');
        cgJsClassAdmin.index.functions.cgLoadBackend(cgJsClassAdmin.mainMenu.vars.formLinkObject.clone(),true);
    });

    $(document).on('click','#cgPurchaseLinkAndProVersionKeyEnterButton',function (e) {
        e.preventDefault(e);
        var $cgPurchaseLinkAndProVersionKeyEnter = $('#cgPurchaseLinkAndProVersionKeyEnter');
        if($cgPurchaseLinkAndProVersionKeyEnter.hasClass('cg_hide')){
            $cgPurchaseLinkAndProVersionKeyEnter.removeClass('cg_hide');
            $('#cgSwitchKeyToAnotherDomainFormContainer').addClass('cg_hide');
        }else{
            $cgPurchaseLinkAndProVersionKeyEnter.addClass('cg_hide');
        }
    });

    $(document).on('click','#cgSwitchKeyToAnotherDomainFormButton',function (e) {
        e.preventDefault(e);
        var $cgSwitchKeyToAnotherDomainFormContainer = $('#cgSwitchKeyToAnotherDomainFormContainer');
        if($cgSwitchKeyToAnotherDomainFormContainer.hasClass('cg_hide')){
            $cgSwitchKeyToAnotherDomainFormContainer.removeClass('cg_hide');
            $('#cgPurchaseLinkAndProVersionKeyEnter').addClass('cg_hide');
        }else{
            $cgSwitchKeyToAnotherDomainFormContainer.addClass('cg_hide');
        }
    });

    $(document).on('click','#cgSubmitDomainSwitch',function (e) {
        var $cgSubmitDomainSwitchCheck = $('#cgSubmitDomainSwitchCheck');
        if(!$cgSubmitDomainSwitchCheck.prop('checked')){
            e.preventDefault(e);
            $('#cgSubmitDomainSwitchCheckError').removeClass('cg_hide');
        }
    });

    $(document).on('click','#cgSubmitDomainSwitchCheck',function (e) {
        if($(this).prop('checked')){
            $('#cgSubmitDomainSwitchCheckError').addClass('cg_hide');
        } else{
            $('#cgSubmitDomainSwitchCheckError').removeClass('cg_hide');
        }
    });

});