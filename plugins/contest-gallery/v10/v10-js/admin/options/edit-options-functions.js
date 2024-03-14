cgJsClassAdmin.options = cgJsClassAdmin.options || {};
cgJsClassAdmin.options.vars = cgJsClassAdmin.options.vars || {};
cgJsClassAdmin.options.functions = cgJsClassAdmin.options.functions || {};

cgJsClassAdmin.options.vars = {
    $cgGoTopOptions: null,
    $cg_main_options_tab: null,
    $cg_main_options_content: null,
    $cg_view_select_objects: null,
    $wpadminbar: null,
    windowHeight: null,
    lastScrollTop: null,
    clickTime: null,
    currentCgShortcodeMultiplePicsActiveClass: 'cg_gallery',
    focusedInputField: 'cg_gallery',
    cg_view_options_row_open_file_image_style:  "<div class='cg_view_options_rows_container'>\n" +
        '<p class="cg_view_options_rows_container_title">Open file image style</p>\n'+
        "<div class='cg_view_options_row'>\n" +
        "                <div class='cg_view_option cg_view_option_full_width  cg_border_border_top_left_radius_8_px cg_border_border_top_right_radius_8_px' >\n" +
        "                    <div class='cg_view_option_title'>\n" +
        "                        <p><span class=\"cg_view_option_title_note\">Select how an entry should be opened on click in a gallery</span></p>\n" +
        "                    </div>\n" +
        "                    <div class='cg_view_option_radio_multiple'>\n" +
        "                        <div class='cg_view_option_radio_multiple_container SliderFullWindowContainer' id='SliderFullWindowContainerMessage'>\n" +
        "                            <div class='cg_view_option_radio_multiple_title'>\n" +
        "                                Full window slider\n" +
        "                            </div>\n" +
        "                            <div class='cg_view_option_radio_multiple_input'>\n" +
        "                                <input type=\"radio\" name=\"SliderFullWindow\" class=\"SliderFullWindow cg_view_option_radio_multiple_input_field\"    />\n" +
        "                            </div>\n" +
        "                        </div>\n" +
        "                        <div class='cg_view_option_radio_multiple_container BlogLookFullWindowContainer' id='BlogLookFullWindowContainerMessage'>\n" +
        "                            <div class='cg_view_option_radio_multiple_title'>\n" +
        "                                Full window blog view\n" +
        "                            </div>\n" +
        "                            <div class='cg_view_option_radio_multiple_input'>\n" +
        "                                <input type=\"radio\" name=\"BlogLookFullWindow\" class=\"BlogLookFullWindow cg_view_option_radio_multiple_input_field\"  />\n" +
        "                            </div>\n" +
        "                        </div>\n" +
        "                        <div class='cg_view_option_radio_multiple_container ForwardToWpPageEntryContainer' id='ForwardToWpPageEntryContainerMessage'>\n" +
        "                            <div class='cg_view_option_radio_multiple_title'>\n" +
        "                                Forward to entry landing page\n" +
        "                            </div>\n" +
        "                            <div class='cg_view_option_radio_multiple_input'>\n" +
        "                                <input type=\"radio\" name=\"ForwardToWpPageEntry\" class=\"ForwardToWpPageEntry cg_view_option_radio_multiple_input_field\"  />\n" +
        "                            </div>\n" +
        "                        </div>\n" +
        "                </div>\n" +
        "            </div>\n" +
        "            </div>\n" +
        "        </div>"
};

cgJsClassAdmin.options.functions = {
    setVars: function($,$formLinkObject,$response){

        cgJsClassAdmin.index.vars.isOptionsAreaLoaded = true

        cgJsClassAdmin.options.vars.$cgGoTopOptions = $('#cgGoTopOptions');
        cgJsClassAdmin.options.vars.$cg_main_options_tab = $('#cg_main_options_tab');
        cgJsClassAdmin.options.vars.$cg_main_options = $('#cg_main_options');
        cgJsClassAdmin.options.vars.$cg_main_options_content = $('#cg_main_options_content');
        cgJsClassAdmin.options.vars.$cg_view_select_objects = cgJsClassAdmin.options.vars.$cg_main_options_tab.find('.cg_view_select');
        cgJsClassAdmin.options.vars.$wpadminbar = $('#wpadminbar');
        cgJsClassAdmin.options.vars.windowHeight = $(window).height();
        cgJsClassAdmin.options.vars.lastScrollTop = 0;
        cgJsClassAdmin.options.vars.clickTime = 0;
        cgJsClassAdmin.mainMenu.vars.$cgMainMenuMainTable = null;

    },
    initOptionsClickEvents: function (isShortcodeConf) {

        if(isShortcodeConf){
            var $classElements = jQuery('#cgShortcodeIntervalConfigurationContainer .cg_view_option');
        }else{
            var $classElements = jQuery('#cg_main_options .cg_view_option');
        }

      //  var classElements = document.getElementsByClassName("cg_view_option");
        // bind this event as next before anything else
   //     for (var i = 0; i < classElements.length; i++) {
            $classElements.each(function (){
                jQuery(this).get(0).addEventListener("click", function (e){
                    cgJsClassAdmin.options.functions.cgViewOptionCheck(this,e);
                });
            });
/*            classElements[i].addEventListener("click", function (e){
                cgJsClassAdmin.options.functions.cgViewOptionCheck(this,e);
            });*/
    //    }

    },
    loadOptionsArea: function ($,$formLinkObject,$response) {

        setTimeout(function () {
            $('#ui-datepicker-div').hide();// needs to be done for some WordPress instances, dont know why
        },50);

        cgJsClassAdmin.options.functions.setVars($);

        cgJsClassAdmin.index.functions.setEditors($,$response.find('#cg_main_options_content .cg-wp-editor-template'));

        cgJsClassAdmin.options.functions.initOptionsClickEvents();

        // show hash
        //  setTimeout(function () {// timeout because of 500 options load
        if(location.hash.indexOf('cgTranslationOtherHashLink') >= 0 || location.search.indexOf('cgTranslationOtherHashLink') >= 0){

            cgJsClassAdmin.index.functions.correctBrowserHistoryState();

            var $cgTranslationOther = $('#cgTranslationOther');

            cgJsClassAdmin.index.functions.cgGoTo($cgTranslationOther);

        }

        // show hash
        //  setTimeout(function () {// timeout because of 500 options load
        if(location.hash.indexOf('cgTranslationLanguageEmail') >= 0 || location.search.indexOf('cgTranslationLanguageEmail') >= 0){

            cgJsClassAdmin.index.functions.correctBrowserHistoryState();

            var $cgTranslationOther = $('#cgTranslationLanguageEmail');

            cgJsClassAdmin.index.functions.cgGoTo($cgTranslationOther);

        }

        if(location.hash.indexOf('cgTranslationLanguagePassword') >= 0 || location.search.indexOf('cgTranslationLanguagePassword') >= 0){

            cgJsClassAdmin.index.functions.correctBrowserHistoryState();

            var $cgTranslationOther = $('#cgTranslationLanguagePassword');

            cgJsClassAdmin.index.functions.cgGoTo($cgTranslationOther);

        }

        if(location.hash.indexOf('cgActivatePostMaxMBfileContainerRow') >= 0 || location.search.indexOf('cgActivatePostMaxMBfileContainerRow') >= 0){
            cgJsClassAdmin.index.functions.correctBrowserHistoryState();
            var $cgConfigureFileSizesUploadContainer = $('#cgConfigureFileSizesUploadContainer');
            jQuery('body,html').addClass('cg_no_scroll');
            setTimeout(function (){// has to be 250 because tiny mce has to be rendered before
                jQuery('body,html').removeClass('cg_no_scroll');
                cgJsClassAdmin.index.functions.cgGoTo($cgConfigureFileSizesUploadContainer);
            },250);
        }
        if(location.hash.indexOf('cgEditGalleryNameLink') >= 0 || location.search.indexOf('cgEditGalleryNameLink') >= 0){

            cgJsClassAdmin.index.functions.correctBrowserHistoryState();

            var $cgEditGalleryNameRow = $('#cgEditGalleryNameRow');

            cgJsClassAdmin.index.functions.cgGoTo($cgEditGalleryNameRow);

        }
        //},200);
        // Only numbers allowed --- END

        // $('#cgScrollSpyContainer').scrollspy({ target: '#navbar-example2' }); <<< without bootstrap.js integrated it breaks the functionallaty!

        //  setTimeout(function () {
        //$("#cg_main_options").addClass('cg_fade_in_0_2');
        $("#cg_main_options").removeClass('cg_hidden');
        $("#cg_save_all_options").removeClass('cg_hidden');
        // },500);


        if($formLinkObject.attr('data-cg-go-to')){

            var $elementToGo = $('#'+$formLinkObject.attr('data-cg-go-to'));

            setTimeout(function () {
                $("html, body").animate({
                    scrollTop: $elementToGo.offset().top
                }, 0);
                $('#cgTranslationOther').addClass('cg_mark_green');
            },100);

        }

        // cg_datepicker_start init here!!!!!

        var dateValue = $( "#cg_datepicker_start_value_to_set" ).attr("value");

        $("#cg_datepicker_start").datepicker({
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
            setDate: dateValue,
            dateFormat: "yy-mm-dd"
            //   option: {dateFormat:"dd.mm.yy"}
        });

        /*        $( "#cg_datepicker_start" ).each(function () {

                    var cgDateFormat =  $(this).closest('.cg_image_title_container').find('.cg_date_format').val().toLowerCase().replace('yyyy','yy');
                    var value = $( this ).val();
                    // have to be done in extra row here
                    $( this ).datepicker("option", "dateFormat", cgDateFormat);
                    $( this ).val(value);// value has to be set again after format is set!

                });*/

        $( "#ui-datepicker-div" ).hide();

        // cg_datepicker_start init here!!!!! --- END

        // cg_datepicker init here!!!!!

        var dateValue = $( "#cg_datepicker_value_to_set" ).attr("value");

        $("#cg_datepicker").datepicker({
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
            setDate: dateValue,
            dateFormat: "yy-mm-dd"
            //   option: {dateFormat:"dd.mm.yy"}
        });

        /*        $( "#cg_datepicker" ).each(function () {

                    var cgDateFormat =  $(this).closest('.cg_image_title_container').find('.cg_date_format').val().toLowerCase().replace('yyyy','yy');
                    var value = $( this ).val();
                    // have to be done in extra row here
                    $( this ).datepicker("option", "dateFormat", cgDateFormat);
                    $( this ).val(value);// value has to be set again after format is set!

                });*/

        $( "#ui-datepicker-div" ).hide();


        // cg_datepicker init here!!!!! --- END


        cgJsClassAdmin.options.functions.cg_shortcode_multiple_pics_checkboxes_uncheck_checked_0($);

        cgJsClassAdmin.options.functions.cg_view_option_radio_checked_0($);

        cgJsClassAdmin.options.functions.cg_view_option_radio_multiple_input_checked_0($);

        cgJsClassAdmin.options.functions.addCheckedUncheckedClasses($);

        //cgJsClassAdmin.options.functions.cg_shortcode_multiple_pics_checkboxes($);
        cgJsClassAdmin.options.functions.cg_ContestStart($);

        cgJsClassAdmin.options.functions.cg_ContestEnd($);

        cgJsClassAdmin.options.functions.cg_ContestEndInstant($);

        cgJsClassAdmin.options.functions.cg_AllowRating($);

        cgJsClassAdmin.options.functions.cg_FbLikeOnlyShare($);

        cgJsClassAdmin.options.functions.cgCheckPreselect($);

        cgJsClassAdmin.options.functions.cg_SliderLookOnLoad($);

        cgJsClassAdmin.options.functions.cg_ShowExifOnLoad($);

        cgJsClassAdmin.options.functions.cg_AllowGalleryScriptOnLoad($);

        cgJsClassAdmin.options.functions.cg_SliderFullWindowOnLoad($);

        cgJsClassAdmin.options.functions.cg_BlogLookFullWindowOnLoad($);

        cgJsClassAdmin.options.functions.cg_ForwardToWpPageEntryOnLoad($);

        cgJsClassAdmin.options.functions.cg_FullSizeImageOutGalleryOnLoad($);

        cgJsClassAdmin.options.functions.cg_OnlyGalleryViewOnLoad($);

        cgJsClassAdmin.options.functions.cg_VotesInTime($);

        cgJsClassAdmin.options.functions.cg_MaxResCheckOnLoad($);

        cgJsClassAdmin.options.functions.modifyUnmodifyImageNamePath($);
        cgJsClassAdmin.options.functions.allowDisallowAdditionalFiles($);
        cgJsClassAdmin.options.functions.informUserUpload($);
        cgJsClassAdmin.options.functions.informUserComment($);
        cgJsClassAdmin.options.functions.informUserVote($);
        //cgJsClassAdmin.options.functions.bulkUploadChecked($);// do not need to be done because of allowDisallowAdditionalFiles already done

        cgJsClassAdmin.options.functions.cgVoteMessageSuccessActiveCheck($);
        cgJsClassAdmin.options.functions.cgVoteMessageWarningActiveCheck($);

        // Check gallery

        if($("#ScaleSizesGalery").prop( "checked" )){

//$( "#ScaleWidthGalery" ).attr("disabled",true);

            if($("#SinglePicView").prop( "checked" )){$("#ScaleWidthGalery").prop("disabled",false);}
            else{}

        }
        else{
            $( "#ScaleSizesGalery1" ).attr("disabled",true);
            $( "#ScaleSizesGalery2" ).attr("disabled",true);
            $( "#ScaleSizesGalery1" ).css({ 'background': '#e0e0e0' });
            $( "#ScaleSizesGalery2" ).css({ 'background': '#e0e0e0' });

        }

        if($("#ScaleWidthGalery").prop( "checked" )){
//$( "#ScaleSizesGalery" ).attr("disabled",true);
            $( "#ScaleSizesGalery2" ).attr("disabled",true);
            $( "#ScaleSizesGalery2" ).css({ 'background': '#e0e0e0' });

        }

        if($("#ScaleWidthGalery").prop( "checked" )){

            if($("#SinglePicView").prop( "checked" )){
                $( "#ScaleSizesGalery1" ).attr("disabled",false);
                $( "#ScaleSizesGalery1" ).css({ 'background': '#ffffff' });
            }

            else{}

        }


        // Check gallery END

        // set inputs to disabled if unchecked
        $('.cg-allow-sort-option').each(function () {

            if($(this).hasClass('cg_unchecked')){
                $(this).closest('.cg_view').find('.cg-allow-sort-input[value="'+$(this).attr('data-cg-target')+'"]').prop('disabled',true);
            }

        });


        cgJsClassAdmin.options.functions.cg_ActivatePostMaxMB($);

        cgJsClassAdmin.options.functions.cg_ActivatePostMaxMBfile($);

        cgJsClassAdmin.options.functions.cg_ActivateBulkUpload($);

        cgJsClassAdmin.options.functions.cg_allowUploadJPG($);

        cgJsClassAdmin.options.functions.cg_allowResJPG($);

        cgJsClassAdmin.options.functions.cg_allowResPNG($);

        cgJsClassAdmin.options.functions.cg_allowResGIF($);

        cgJsClassAdmin.options.functions.cg_allowResICO($);

        // simply check after res, then disabled will be definitely forced, easy way
        cgJsClassAdmin.options.functions.cg_allowUploadJPG($);
        cgJsClassAdmin.options.functions.cg_allowUploadPNG($);
        cgJsClassAdmin.options.functions.cg_allowUploadGIF($);
        cgJsClassAdmin.options.functions.cg_allowUploadICO($);

        cgJsClassAdmin.options.functions.cg_HeightLookOnLoad($);

        cgJsClassAdmin.options.functions.cg_ThumbLookOnLoad($);

        cgJsClassAdmin.options.functions.cg_RowLookOnLoad($);

        // cgJsClassAdmin.options.functions.checkInGalleryUpload($);

        cgJsClassAdmin.options.functions.cg_confirm_after_upload($);

        cgJsClassAdmin.options.functions.cg_forward_after_upload($);

        cgJsClassAdmin.options.functions.checkAfterContactSubmit($);

        cgJsClassAdmin.options.functions.cg_inform_admin_after_upload($);

        cgJsClassAdmin.options.functions.cg_CommNoteActive($);

        cgJsClassAdmin.options.functions.cg_after_login($);

        cgJsClassAdmin.options.functions.cg_after_confirm_text($);

        cgJsClassAdmin.options.functions.cg_mail_confirm_email($);

        cgJsClassAdmin.options.functions.cg_image_activation_email($);

        cgJsClassAdmin.options.functions.cg_contact_entry_user_email($);

        cgJsClassAdmin.options.functions.cg_user_recognising_method_upload($);

        cgJsClassAdmin.options.functions.cgRegUserGalleryOnlyOnLoad($);

        cgJsClassAdmin.options.functions.cg_pro_version_wp_editor_check($);

        cgJsClassAdmin.options.functions.cg_user_reocgnising_method($);

        cgJsClassAdmin.options.functions.cg_set_wpnonce($);

        cgJsClassAdmin.options.functions.cg_HideRegFormAfterLogin($);

        cgJsClassAdmin.options.functions.cg_HideRegFormAfterLoginShowTextInstead($);

        cgJsClassAdmin.options.functions.cg_LostPasswordMailActiveCheck($);

        // replace reset votes

        var reloadUrl = window.location.href;

        if (reloadUrl.indexOf("reset_votes") >= 0){
            reloadUrl = reloadUrl.replace(/reset_votes/gi,'reset_votes_done');
        }

        if (reloadUrl.indexOf("reset_users_votes") >= 0){
            reloadUrl = reloadUrl.replace(/reset_users_votes/gi,'reset_users_votes_done');
        }

        if (reloadUrl.indexOf("reset_votes2") >= 0){
            reloadUrl = reloadUrl.replace(/reset_votes2/gi,'reset_votes2_done');
        }

        if (reloadUrl.indexOf("reset_users_votes2") >= 0){
            reloadUrl = reloadUrl.replace(/reset_users_votes2/gi,'reset_users_votes2_done');
        }

        if (reloadUrl.indexOf("reset_admin_votes") >= 0){
            reloadUrl = reloadUrl.replace(/reset_admin_votes/gi,'reset_admin_votes_done');
        }

        if (reloadUrl.indexOf("reset_admin_votes2") >= 0){
            reloadUrl = reloadUrl.replace(/reset_admin_votes2/gi,'reset_admin_votes2_done');
        }

        if (reloadUrl.indexOf("&cgGoogleSignInLib=downloaded") >= 0){
            reloadUrl = reloadUrl.replace(/&cgGoogleSignInLib=downloaded/gi,'');
            setTimeout(function () {
                var $cgGoogleSignInLibDownloadMessage = $('#cgGoogleSignInLibDownloadMessage');
                $cgGoogleSignInLibDownloadMessage.empty();
                $('#cgSignInOptionsTabLink').click();
                $cgGoogleSignInLibDownloadMessage.append($('#cgGoogleSignInLibDownloadedOptionsHaveToBeConfigured').removeClass('cg_hide'));
                $cgGoogleSignInLibDownloadMessage.removeClass('cg_hide');
                $('#cgGoogleSignInLibDownloadMessageBackground').removeClass('cg_hide');
            },50);
        }

        history.replaceState(null,null,reloadUrl);

        // replace reset votes --- ENDE

        setTimeout(function () {
            // change iframe body css
            $('.cg-small-textarea-container iframe').contents().find('body').css({
                'margin':'10px',
                'width':'auto'
            });
        },1000);

        cgJsClassAdmin.options.functions.cgAllowSortCheckOnLoad($);

        cgJsClassAdmin.options.functions.cg_EnableSwitchStyleGalleryButtonOnLoad($);

        cgJsClassAdmin.options.functions.cg_EnableSwitchStyleImageViewButtonOnLoad($);

        // show last selected multiple if one star is selected just for visual reason
        if($('#AllowRating2').prop('checked')){
            var $AllowRating3 = $('#AllowRating3');
            var gid = $AllowRating3.attr('data-cg-gid');
            var optionValue =  localStorage.getItem('cg_AllowRating3_last_used_option_gallery_id_'+gid);
            if(optionValue){
                $AllowRating3.val(optionValue);
            }
        }

    },
    cgViewOptionCheck: function (element,e){
        var $ = jQuery;

        var $element = $(element);

        if(!$(element).hasClass('cg_view_option') && !$(element).closest('.cg_view_option').length){
            return;
        }

        if($(e.target).hasClass('cg-info-icon')){
            return;
        }

        if($(e.target).hasClass('cg_go_to_link')){
            return;
        }

        if($(e.target).attr('id')=='AllowRating3'){
            return;
        }

        // check and process this possibility first
        if($(e.target).hasClass('cg_view_option_radio_multiple_input_field')){
            var $eTarget = $(e.target);
            var $cg_view_option_radio_multiple = $eTarget.closest('.cg_view_option_radio_multiple');
            $cg_view_option_radio_multiple.find('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
            $cg_view_option_radio_multiple.find('.cg_view_option_radio_multiple_input input[type="radio"]').prop('checked',false).removeAttr('checked');// do removeAttr to go sure

            $eTarget.closest('.cg_view_option_radio_multiple_input').addClass('cg_view_option_checked');
            $eTarget.closest('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_unchecked');
            $eTarget.prop('checked',true);

            return;
        }

        // check and process this possibility second
        if($(e.target).hasClass('cg_view_option_radio_multiple_container') || $(e.target).closest('.cg_view_option_radio_multiple_container').length){
            var $eTarget = $(e.target);
            var $cg_view_option_radio_multiple = $eTarget.closest('.cg_view_option_radio_multiple');
            $cg_view_option_radio_multiple.find('.cg_view_option_radio_multiple_input input[type="radio"]').prop('checked',false).removeAttr('checked');
            $cg_view_option_radio_multiple.find('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');

            if($(e.target).hasClass('cg_view_option_radio_multiple_container') ){
                //$eTarget.find('input[type="radio"]').attr('checked','checked');// requires this in this case
                $eTarget.find('.cg_view_option_radio_multiple_input').addClass('cg_view_option_checked');
                $eTarget.find('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_unchecked');
                $eTarget.find('.cg_view_option_radio_multiple_input input[type="radio"]').prop('checked',true);
                return;
            }

            if($(e.target).closest('.cg_view_option_radio_multiple_container').length){
                var $cg_view_option_radio_multiple_container = $(e.target).closest('.cg_view_option_radio_multiple_container');
                $cg_view_option_radio_multiple_container.find('.cg_view_option_radio_multiple_input').addClass('cg_view_option_checked');
                $cg_view_option_radio_multiple_container.find('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_unchecked');
                $cg_view_option_radio_multiple_container.find('.cg_view_option_radio_multiple_input input[type="radio"]').prop('checked',true);
                return;
            }

        }

        if($element.find('.cg_view_option_checkbox').length){
            if($element.find('.cg_view_option_checkbox input[type="checkbox"]').prop('checked')){
                $element.find('.cg_view_option_checkbox input[type="checkbox"]').prop('checked',false);
                $element.find('.cg_view_option_checkbox').addClass('cg_view_option_unchecked');
                $element.find('.cg_view_option_checkbox').removeClass('cg_view_option_checked');
            }else{
                $element.find('.cg_view_option_checkbox input[type="checkbox"]').prop('checked',true);
                $element.find('.cg_view_option_checkbox').addClass('cg_view_option_checked');
                $element.find('.cg_view_option_checkbox').removeClass('cg_view_option_unchecked');
            }
            cgJsClassAdmin.options.functions.cgShortcodeCheckboxProcessing($element);
            return;
        }

        if($element.find('.cg_view_option_radio').length){
            if($element.find('.cg_view_option_radio input[type="radio"]').prop('checked')){
                return;// radio can not uncheck self
            }else{
                var $cg_view_options_row = $element.closest('.cg_view_options_row');
                $cg_view_options_row.find('.cg_view_option_radio input[type="radio"]').prop('checked',false);
                $cg_view_options_row.find('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
                $element.find('.cg_view_option_radio input[type="radio"]').prop('checked',true);
                $element.find('.cg_view_option_radio').addClass('cg_view_option_checked');
                $element.find('.cg_view_option_radio').removeClass('cg_view_option_unchecked');
                return;
            }
        }

        if($element.find('.cg_view_option_input').length){
            var $input = $element.find('.cg_view_option_input input');
            if($(e.target).attr('name')==$input.attr('name')){// then already focus directly in field
                return;
            }
            if(cgJsClassAdmin.options.vars.focusedInputField){
                if(typeof cgJsClassAdmin.options.vars.focusedInputField == 'object'){
                    if(cgJsClassAdmin.options.vars.focusedInputField instanceof jQuery){
                        if(cgJsClassAdmin.options.vars.focusedInputField.attr('name')==$input.attr('name')){// then already focus directly in field
                            return;
                        }
                    }
                }
            }
            var valueLength= $input.val().length;
            $input.focus();
            $input[0].setSelectionRange(valueLength, valueLength);
            return;
        }

    },
    cgShortcodeCheckboxProcessing: function ($element){

        if($element.find('.cg_shortcode_checkbox').length){
            var $cg_shortcode_checkbox = $element.find('.cg_shortcode_checkbox').not('[type="hidden"]');
            if($cg_shortcode_checkbox.prop('checked')){
                $cg_shortcode_checkbox.parent().find('.cg_shortcode_checkbox[type="hidden"]').remove();
            }else{
                var $clone = $cg_shortcode_checkbox.clone();
                $clone.attr('type','hidden').val('0');
                $clone.addClass('cg_shortcode_checkbox_clone').insertAfter($cg_shortcode_checkbox);
            }
        }

    },
    cg_shortcode_multiple_pics_checkboxes_uncheck_checked_0: function ($) {

        $("#cg_main_options_content .cg_shortcode_checkbox[checked='0']").each(function () {

            var $element = $(this);

            $element.removeAttr('checked');

            var $clone = $element.clone();
            $clone.attr('type','hidden').val('0');
            $clone.insertAfter($element);

            $clone.closest('.cg_view_option_checkbox').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');

        });

        /*    cg_shortcode_multiple_pics_checkboxes: function ($) {

                $("#cg_main_options_content .cg_short_code_multiple_pics_configuration_container .cg_shortcode_checkbox[type='checkbox']").each(function () {


                });

            },*/
    },
    cg_view_option_radio_checked_0: function ($) {

        $("#cg_main_options_content .cg_view_option_radio input[checked='0']").each(function () {

            var $element = $(this);
            $element.removeAttr('checked');
            $element.closest('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');

        });

    },
    cg_view_option_radio_multiple_input_checked_0: function ($) {

        $("#cg_main_options_content .cg_view_option_radio_multiple_input input[checked='0']").each(function () {

            var $element = $(this);
            $element.removeAttr('checked');
            $element.closest('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');

        });

    },
    cg_VotesInTime: function ($) {

        if($("#VotesInTime").prop( "checked" )){
            $("#VotesInTimeQuantityContainer").removeClass("cg_disabled");
            $("#VotesInTimeHoursMinutesContainer").removeClass("cg_disabled");
            $("#VotesInTimeIntervalAlertMessageContainer").removeClass("cg_disabled");
        }
        else{
            $("#VotesInTimeQuantityContainer").addClass("cg_disabled");
            $("#VotesInTimeHoursMinutesContainer").addClass("cg_disabled");
            $("#VotesInTimeIntervalAlertMessageContainer").addClass("cg_disabled");
        }

    },
    cg_ContestStart: function ($) {

        if($("#ContestStart").prop( "checked" )){

            $("#cg_datepicker_start").removeClass("cg_disabled");
            $("#cg_date_hours_contest_start").removeClass("cg_disabled");
            $("#cg_date_mins_contest_start").removeClass("cg_disabled");
            $("#cg_datepicker_start_container").removeClass("cg_disabled");

        }
        else{

            $("#cg_datepicker_start").addClass("cg_disabled");
            $("#cg_date_hours_contest_start").addClass("cg_disabled");
            $("#cg_date_mins_contest_start").addClass("cg_disabled");
            $("#cg_datepicker_start_container").addClass("cg_disabled");

        }

    },
    cg_ContestEnd: function ($) {

        if($("#ContestEnd").prop( "checked" )){

            $("#cg_datepicker").removeClass("cg_disabled");
            $("#cg_datepicker_container").removeClass("cg_disabled");
            $("#cg_date_hours_contest_end").removeClass("cg_disabled");
            $("#cg_date_mins_contest_end").removeClass("cg_disabled");
            $("#ContestEndInstantContainer").removeClass("cg_disabled");
            $("#ContestEndInstant").prop("checked",false);

        }
        else{

            $("#cg_datepicker").addClass("cg_disabled");
            $("#cg_datepicker_container").addClass("cg_disabled");
            $("#cg_date_hours_contest_end").addClass("cg_disabled");
            $("#cg_date_mins_contest_end").addClass("cg_disabled");

        }

    },
    cg_ContestEndInstant: function ($) {

        if($("#ContestEndInstant").prop( "checked" )){

            $("#ContestEnd").prop("checked",false);
            $("#ContestEndContainer").addClass("cg_disabled");
            $("#ContestEndContainer").find(".cg_view_option_checkbox").removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
            $("#cg_datepicker").addClass("cg_disabled");
            $("#cg_datepicker_container").addClass("cg_disabled");
            $("#cg_date_hours_contest_end").addClass("cg_disabled");
            $("#cg_date_mins_contest_end").addClass("cg_disabled");
        }
        else{
            $("#ContestEndContainer").removeClass("cg_disabled");
        }

    },
    cg_AllowRating: function($){

        var $cgVotingOptions = $('#cgVotingOptions');

        if($("#AllowRating").prop( "checked" ) || $("#AllowRating2").prop( "checked" )){

            $cgVotingOptions.find("#RatingOutGalleryContainer").removeClass("cg_disabled");
            $cgVotingOptions.find(".RatingPositionGalleryContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#HideUntilVoteContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#VotesPerUserContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#VoteNotOwnImageContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#ShowOnlyUsersVotesContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#IpBlockContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#VotesPerUserAllVotesUsedHtmlMessageContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#VotePerCategoryContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#VotesPerCategoryContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#RatingPositionGalleryContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#MinusVoteContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#VotesInTimeContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#VotesInTimeQuantityContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#VotesInTimeHoursMinutesContainer").removeClass("cg_disabled");
            $cgVotingOptions.find("#VotesInTimeIntervalAlertMessageContainer").removeClass("cg_disabled");

        }
        else{

            $cgVotingOptions.find("#RatingOutGalleryContainer").addClass("cg_disabled");
            $cgVotingOptions.find(".RatingPositionGalleryContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#HideUntilVoteContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#VotesPerUserContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#VoteNotOwnImageContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#ShowOnlyUsersVotesContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#IpBlockContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#VotesPerUserAllVotesUsedHtmlMessageContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#VotePerCategoryContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#VotesPerCategoryContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#RatingPositionGalleryContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#MinusVoteContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#VotesInTimeContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#VotesInTimeQuantityContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#VotesInTimeHoursMinutesContainer").addClass("cg_disabled");
            $cgVotingOptions.find("#VotesInTimeIntervalAlertMessageContainer").addClass("cg_disabled");

        }


    },
    cg_FbLike: function ($) {
        return;
        if($("#FbLike").prop( "checked" )){
            $("#FbLikeGalleryContainer").removeClass("cg_disabled");
            $("#FbLikeGalleryVoteContainer").removeClass("cg_disabled");
            $("#FbLikeGoToGalleryLinkContainer").removeClass("cg_disabled");
            $("#FbLikeNoShareContainer").removeClass("cg_disabled");
            $("#FbLikeOnlyShareContainer").removeClass("cg_disabled");
        }
        else{
            $("#FbLikeGalleryContainer").addClass("cg_disabled");
            $("#FbLikeGalleryVoteContainer").addClass("cg_disabled");
            $("#FbLikeGoToGalleryLinkContainer").addClass("cg_disabled");
            $("#FbLikeNoShareContainer").addClass("cg_disabled");
            $("#FbLikeOnlyShareContainer").addClass("cg_disabled");
        }

    },
    cg_FbLikeNoShare: function ($,$element) {
        return;

        if($("#FbLikeOnlyShare").prop( "checked" ) && $element.prop('checked')){

            $("#FbLikeOnlyShare").prop("checked",false);
            $("#FbLikeOnlyShareContainer").find('.cg_view_option_checkbox').addClass('cg_view_option_unchecked').removeClass('cg_view_option_checked');

        }

    },
    cg_FbLikeOnlyShare: function ($) {
        if($('#FbLikeOnlyShare').prop('checked')){
            $("#FbLikeGalleryContainer").removeClass("cg_disabled");
            $("#FbLikeGoToGalleryLinkContainer").removeClass("cg_disabled");
        }else{
            $("#FbLikeGalleryContainer").addClass("cg_disabled");
            $("#FbLikeGoToGalleryLinkContainer").addClass("cg_disabled");
        }
    },
    /*    cg_AllowComments: function ($) {

            if($("#AllowComments").prop( "checked" )){

                $(".CommentPositionGalleryContainer").removeClass("cg_disabled");

            }
            else{

                $(".CommentPositionGalleryContainer").addClass("cg_disabled");

            }

        },*/
    cgCheckPreselect: function ($) {

        // goes through all vies here
        $('.RandomSort:not(.cg_shortcode_checkbox_clone)').each(function () {

            var $element = $(this);

            if($element.prop( "checked" )){
                $element.closest('.cg_view').find(".PreselectSortContainer").addClass("cg_disabled");
                $element.closest('.cg_view').find(".cgPreselectSortMessage").removeClass("cg_hide");
            }
            else{
                $element.closest('.cg_view').find(".PreselectSortContainer").removeClass("cg_disabled");
                $element.closest('.cg_view').find(".cgPreselectSortMessage").addClass("cg_hide");
            }

        });

    },
    cg_ShowExifOnLoad: function ($) {

        $('#cg_main_options .ShowExifContainer').each(function () {
            cgJsClassAdmin.options.functions.cg_ShowExif(false,$,$(this));
        });

    },
    cg_ShowExif: function (click,$,$element) {

        var $cg_view = $element.closest('.cg_view');

        if($cg_view.find(".ShowExif:not(.cg_shortcode_checkbox_clone)").prop( "checked")){
            $cg_view.find(".ShowExifOption").removeClass('cg_disabled');// full main container disable
        } else{
            $cg_view.find(".ShowExifOption").addClass('cg_disabled');// full main container disable
        }
    },
    cg_AllowGalleryScriptOnLoad: function ($) {

        $('#cg_main_options .AllowGalleryScript').each(function () {
            cgJsClassAdmin.options.functions.cg_AllowGalleryScript(false,$,$(this));
        });

    },
    cg_AllowGalleryScript: function (click,$,$element) {

        var $cg_view = $element.closest('.cg_view');

        if($cg_view.find(".AllowGalleryScript:not(.cg_shortcode_checkbox_clone)").prop( "checked" ) || $cg_view.find(".SliderFullWindow:not(.cg_shortcode_checkbox_clone)").prop( "checked" )  || $cg_view.find(".BlogLookFullWindow:not(.cg_shortcode_checkbox_clone)").prop( "checked" ) ){

            $cg_view.removeClass('cg_FullSizeImageOutGallery_checked');
            $cg_view.removeClass('cg_OnlyGalleryView_checked');

            $cg_view.find(".GallerySlideOutSliderViewBlogViewContainer").removeClass('cg_disabled');// full main container disable

            $cg_view.find(".FullSizeImageOutGalleryContainer").find('.cg_view_option_radio input').prop('checked',false);
            $cg_view.find(".FullSizeImageOutGalleryContainer").find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $cg_view.find(".FullSizeImageOutGalleryContainer").find('.cg_view_option_radio').addClass('cg_view_option_unchecked');

            $cg_view.find(".OnlyGalleryViewContainer").find('.cg_view_option_radio input').prop('checked',false);
            $cg_view.find(".OnlyGalleryViewContainer").find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $cg_view.find(".OnlyGalleryViewContainer").find('.cg_view_option_radio').addClass('cg_view_option_unchecked');

            if(click && !$cg_view.find(".SliderFullWindow").prop( "checked" )){
                $cg_view.find(".SliderFullWindow").prop('checked',false);
            }
            if(click && !$cg_view.find(".BlogLookFullWindow").prop( "checked" )){
                $cg_view.find(".BlogLookFullWindow").prop('checked',false);
            }
            if($cg_view.find(".AllowGalleryScript").prop( "checked" )){
                $cg_view.find(".SliderFullWindow").prop('checked',false);
                $cg_view.find(".BlogLookFullWindow").prop('checked',false);
                $cg_view.find(".FullSizeSlideOutStartContainer ").removeClass('cg_disabled');
            }
            if($cg_view.find(".SliderFullWindow").prop( "checked" ) ){
                $cg_view.find(".FullSizeSlideOutStartContainer").addClass('cg_disabled');
            }
            if($cg_view.find(".BlogLookFullWindow").prop( "checked" ) ){
                $cg_view.find(".FullSizeSlideOutStartContainer").addClass('cg_disabled');
            }

            $cg_view.find(".AllowCommentsParentContainer").removeClass('cg_disabled');
            $cg_view.find(".EntryLadingPageOptionsParentContainer").removeClass('cg_disabled');
            $cg_view.find(".cg_image_view_other_options_then_comment_options").removeClass('cg_disabled_background_color_e0e0e0');


        }else if(!$cg_view.find(".AllowGalleryScript").prop( "checked" ) && click){

            if(click){
                $cg_view.find(".AllowGalleryScript").prop('checked',true);
            }
        }
        else{

            $cg_view.find(".GallerySlideOutSliderViewBlogViewContainer").addClass('cg_disabled');// full main container disable

            if(click){
                $cg_view.find(".AllowGalleryScript").prop('checked',true);
            }

        }
    },
    cg_SliderFullWindowOnLoad: function ($) {

        $('#cg_main_options .SliderFullWindow').each(function () {
            cgJsClassAdmin.options.functions.cg_SliderFullWindow(false,$,$(this));
        });

    },
    cg_SliderFullWindow: function (click,$,$element) {

        cgJsClassAdmin.options.functions.cg_AllowGalleryScript(false,$,$element);

        var $cg_view = $element.closest('.cg_view');

        if($cg_view.find(".SliderFullWindow:not(.cg_shortcode_checkbox_clone)").prop( "checked" )){

            $cg_view.removeClass('cg_FullSizeImageOutGallery_checked');
            $cg_view.removeClass('cg_OnlyGalleryView_checked');

            $cg_view.find(".AllowGalleryScript").prop('checked',false);
            $cg_view.find(".BlogLookFullWindow").prop('checked',false);
            $cg_view.find(".ForwardToWpPageEntry").prop('checked',false);
            $cg_view.find(".FullSizeSlideOutStartContainer").addClass('cg_disabled');

            $cg_view.find(".AllowCommentsParentContainer").removeClass('cg_disabled');
            $cg_view.find(".EntryLadingPageOptionsParentContainer").removeClass('cg_disabled');
            $cg_view.find(".ForwardToWpPageEntryInNewTabContainer").addClass('cg_hide');
            $cg_view.find(".cg_image_view_other_options_then_comment_options").removeClass('cg_disabled_background_color_e0e0e0');

        }else{

            if(click){
                $cg_view.find(".AllowGalleryScript").prop('checked',false);
                $cg_view.find(".BlogLookFullWindow").prop('checked',false);
                $cg_view.find(".ForwardToWpPageEntry").prop('checked',false);
                $cg_view.find(".SliderFullWindow").prop('checked',true);
                $cg_view.find(".ForwardToWpPageEntryInNewTabContainer").addClass('cg_hide');
                $cg_view.find(".FullSizeSlideOutStartContainer").addClass('cg_disabled');
            }

        }

    },
    cg_BlogLookFullWindowOnLoad: function ($) {

        $('#cg_main_options .BlogLookFullWindow').each(function () {
            cgJsClassAdmin.options.functions.cg_BlogLookFullWindow(false,$,$(this));
        });

    },
    cg_BlogLookFullWindow: function (click,$,$element) {

        cgJsClassAdmin.options.functions.cg_AllowGalleryScript(false,$,$element);

        var $cg_view = $element.closest('.cg_view');

        if($cg_view.find(".BlogLookFullWindow:not(.cg_shortcode_checkbox_clone)").prop( "checked" )){

            $cg_view.removeClass('cg_FullSizeImageOutGallery_checked');
            $cg_view.removeClass('cg_OnlyGalleryView_checked');

            $cg_view.find(".AllowGalleryScript").prop('checked',false);
            $cg_view.find(".SliderFullWindow").prop('checked',false);
            $cg_view.find(".ForwardToWpPageEntry").prop('checked',false);
            $cg_view.find(".FullSizeSlideOutStartContainer").addClass('cg_disabled');
            $cg_view.find(".AllowCommentsParentContainer").removeClass('cg_disabled');
            $cg_view.find(".EntryLadingPageOptionsParentContainer").removeClass('cg_disabled');
            $cg_view.find(".ForwardToWpPageEntryInNewTabContainer").addClass('cg_hide');
            $cg_view.find(".cg_image_view_other_options_then_comment_options").removeClass('cg_disabled_background_color_e0e0e0');

        }else{

            if(click){
                $cg_view.find(".AllowGalleryScript").prop('checked',false);
                $cg_view.find(".SliderFullWindow").prop('checked',false);
                $cg_view.find(".ForwardToWpPageEntry").prop('checked',false);
                $cg_view.find(".BlogLookFullWindow").prop('checked',true);
                $cg_view.find(".ForwardToWpPageEntryInNewTabContainer").addClass('cg_hide');
                $cg_view.find(".FullSizeSlideOutStartContainer").addClass('cg_disabled');
            }

        }

    },
    cg_ForwardToWpPageEntryOnLoad: function ($) {

        $('#cg_main_options .ForwardToWpPageEntry').each(function () {
            cgJsClassAdmin.options.functions.cg_ForwardToWpPageEntry(false,$,$(this));
        });

    },
    cg_ForwardToWpPageEntry: function (click,$,$element) {

        cgJsClassAdmin.options.functions.cg_AllowGalleryScript(false,$,$element);

        var $cg_view = $element.closest('.cg_view');

        if($cg_view.find(".ForwardToWpPageEntry:not(.cg_shortcode_checkbox_clone)").prop( "checked" )){

            $cg_view.removeClass('cg_FullSizeImageOutGallery_checked');
            $cg_view.removeClass('cg_OnlyGalleryView_checked');

            $cg_view.find(".AllowGalleryScript").prop('checked',false);
            $cg_view.find(".SliderFullWindow").prop('checked',false);
            $cg_view.find(".BlogLookFullWindow").prop('checked',false);
            $cg_view.find(".FullSizeSlideOutStartContainer").addClass('cg_disabled');
            $cg_view.find(".AllowCommentsParentContainer").removeClass('cg_disabled');
            $cg_view.find(".EntryLadingPageOptionsParentContainer").removeClass('cg_disabled');
            $cg_view.find(".cg_image_view_other_options_then_comment_options").removeClass('cg_disabled_background_color_e0e0e0');
            $cg_view.find(".ForwardToWpPageEntryInNewTabContainer").removeClass('cg_hide');

        }else{

            if(click){
                $cg_view.find(".AllowGalleryScript").prop('checked',false);
                $cg_view.find(".SliderFullWindow").prop('checked',false);
                $cg_view.find(".BlogLookFullWindow").prop('checked',false);
                $cg_view.find(".ForwardToWpPageEntryInNewTabContainer").addClass('cg_hide');
                $cg_view.find(".FullSizeSlideOutStartContainer").addClass('cg_disabled');
            }

        }

    },
    cg_FullSizeImageOutGalleryOnLoad: function ($) {

        $('#cg_main_options .FullSizeImageOutGallery').each(function () {
            cgJsClassAdmin.options.functions.cg_FullSizeImageOutGallery(false,$,$(this));
        });

    },
    cg_FullSizeImageOutGallery: function (click,$,$element) {

        var $cg_view = $element.closest('.cg_view');

        if($cg_view.find(".FullSizeImageOutGallery:not(.cg_shortcode_checkbox_clone)").prop( "checked" )){
            $cg_view.addClass('cg_FullSizeImageOutGallery_checked');
            $cg_view.removeClass('cg_OnlyGalleryView_checked');

            $cg_view.find(".AllowGalleryScript").prop('checked',false);
            $cg_view.find(".AllowGalleryScriptContainer").find('.cg_view_option_radio_multiple_input').addClass('cg_view_option_unchecked');
            $cg_view.find(".AllowGalleryScriptContainer").find('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_checked');
            $cg_view.find(".OnlyGalleryView").prop('checked',false);
            $cg_view.find(".OnlyGalleryViewContainer").find('.cg_view_option_radio').addClass('cg_view_option_unchecked');
            $cg_view.find(".OnlyGalleryViewContainer").find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $cg_view.find(".SliderFullWindow").prop('checked',false);
            $cg_view.find(".SliderFullWindowContainer").find('.cg_view_option_radio_multiple_input').addClass('cg_view_option_unchecked');
            $cg_view.find(".SliderFullWindowContainer").find('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_checked');
            $cg_view.find(".BlogLookFullWindow").prop('checked',false);
            $cg_view.find(".BlogLookFullWindowContainer").find('.cg_view_option_radio_multiple_input').addClass('cg_view_option_unchecked');
            $cg_view.find(".BlogLookFullWindowContainer").find('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_checked');

            $cg_view.find(".GallerySlideOutSliderViewBlogViewContainer").addClass('cg_disabled');// full main container disable
            $cg_view.find(".AllowCommentsParentContainer").addClass('cg_disabled');
            $cg_view.find(".EntryLadingPageOptionsParentContainer").addClass('cg_disabled');
            $cg_view.find(".cg_image_view_other_options_then_comment_options").addClass('cg_disabled_background_color_e0e0e0');

            // have to be set extra by javascript otherwise visually not set
            $cg_view.find(".FullSizeImageOutGallery").prop('checked',true);
            $cg_view.find(".FullSizeImageOutGalleryContainer").find('.cg_view_option_radio').addClass('cg_view_option_checked');
            $cg_view.find(".FullSizeImageOutGalleryContainer").find('.cg_view_option_radio').removeClass('cg_view_option_unchecked');

        }else{

            $cg_view.removeClass('cg_FullSizeImageOutGallery_checked');

            if(click){
                $cg_view.find(".FullSizeImageOutGallery").prop('checked',true);
                $cg_view.find(".FullSizeImageOutGalleryContainer").find('.cg_view_option_radio').addClass('cg_view_option_checked');
                $cg_view.find(".FullSizeImageOutGalleryContainer").find('.cg_view_option_radio').removeClass('cg_view_option_unchecked');
            }

        }

    },
    cg_OnlyGalleryViewOnLoad: function ($) {

        $('#cg_main_options .OnlyGalleryView').each(function () {
            cgJsClassAdmin.options.functions.cg_OnlyGalleryView(false,$,$(this));
        });

    },
    cg_OnlyGalleryView: function (click,$,$element) {

        var $cg_view = $element.closest('.cg_view');

        if($cg_view.find(".OnlyGalleryView:not(.cg_shortcode_checkbox_clone)").prop( "checked" )){
            $cg_view.addClass('cg_OnlyGalleryView_checked');
            $cg_view.removeClass('cg_FullSizeImageOutGallery_checked');

            $cg_view.find(".AllowGalleryScript").prop('checked',false);
            $cg_view.find(".AllowGalleryScriptContainer").find('.cg_view_option_radio_multiple_input').addClass('cg_view_option_unchecked');
            $cg_view.find(".AllowGalleryScriptContainer").find('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_checked');

            $cg_view.find(".FullSizeImageOutGallery").removeAttr('checked');
            $cg_view.find(".FullSizeImageOutGalleryContainer").find('.cg_view_option_radio').addClass('cg_view_option_unchecked');
            $cg_view.find(".FullSizeImageOutGalleryContainer").find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $cg_view.find(".SliderFullWindow").removeAttr('checked');
            $cg_view.find(".SliderFullWindowContainer").find('.cg_view_option_radio_multiple_input').addClass('cg_view_option_unchecked');
            $cg_view.find(".SliderFullWindowContainer").find('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_checked');
            $cg_view.find(".BlogLookFullWindow").removeAttr('checked');
            $cg_view.find(".BlogLookFullWindowContainer").find('.cg_view_option_radio_multiple_input').addClass('cg_view_option_unchecked');
            $cg_view.find(".BlogLookFullWindowContainer").find('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_checked');
            //$("#FullSizeSlideOutStart").addClass('cg_disabled');
            //    cg_FullSizeGallery(); // Beeinflusst FullSizeSlideOutStart

            $cg_view.find(".GallerySlideOutSliderViewBlogViewContainer").addClass('cg_disabled');// full main container disable
            $cg_view.find(".AllowCommentsParentContainer").addClass('cg_disabled');
            $cg_view.find(".EntryLadingPageOptionsParentContainer").addClass('cg_disabled');
            $cg_view.find(".cg_image_view_other_options_then_comment_options").addClass('cg_disabled_background_color_e0e0e0');


            // have to be set extra by javascript otherwise visually not set
            $cg_view.find(".OnlyGalleryView").prop('checked',true);
            $cg_view.find(".OnlyGalleryViewContainer").find('.cg_view_option_radio').addClass('cg_view_option_checked');
            $cg_view.find(".OnlyGalleryViewContainer").find('.cg_view_option_radio').removeClass('cg_view_option_unchecked');

        }else{
            $cg_view.removeClass('cg_OnlyGalleryView_checked');

            if(click){
                $cg_view.find(".OnlyGalleryView").prop('checked',true);
                $cg_view.find(".OnlyGalleryViewContainer").find('.cg_view_option_radio').addClass('cg_view_option_checked');
                $cg_view.find(".OnlyGalleryViewContainer").find('.cg_view_option_radio').removeClass('cg_view_option_unchecked');
            }
        }

    },
    /*    cg_CheckFullSizeOnLoad: function ($) {

            $('#cg_main_options .FullSizeGallery').each(function () {
                cgJsClassAdmin.options.functions.cg_CheckFullSize($,$(this));
            });

        },
        cg_CheckFullSize: function ($,$element) {

            var $cg_view = $element.closest('.cg_view');

            if($cg_view.find(".FullSizeGallery").prop( "checked" )){
                $cg_view.find(".FullSize").removeClass("cg_disabled");
            }
            else{
                $cg_view.find(".FullSize").addClass("cg_disabled");
            }

        },*/
    cg_MaxResCheckOnLoad: function ($) {

        $('#cg_main_options .cg-allow-res').each(function () {
            cgJsClassAdmin.options.functions.cg_MaxResCheck($,$(this).find('.cg-allow-res-checkbox'));
        });

    },
    cg_MaxResCheck: function ($,$element) {

        if($element.prop('checked')){
            $element.closest('.cg_view_option').find('.cg_allow_res_note').addClass('cg_hide');
        }else{
            $element.closest('.cg_view_option').find('.cg_allow_res_note').removeClass('cg_hide');
        }

    },
    cg_ActivatePostMaxMB: function ($) {

        if($("#ActivatePostMaxMB").prop( "checked" )){
            $("#PostMaxMBContainer").removeClass("cg_disabled");
        }
        else{
            $("#PostMaxMBContainer").addClass("cg_disabled");
        }

    },
    cg_ActivatePostMaxMBfile: function ($) {

        if($("#ActivatePostMaxMBfile").prop( "checked" )){
            $("#PostMaxMBfileContainer").removeClass("cg_disabled");
        }
        else{
            $("#PostMaxMBfileContainer").addClass("cg_disabled");
        }

    },
    cg_ActivateBulkUpload: function ($,$element,isOnClick) {


        if($("#ActivateBulkUpload").prop( "checked" )){

            $("#BulkUploadQuantityContainer").removeClass("cg_disabled");
            $("#BulkUploadMinQuantityContainer").removeClass("cg_disabled");
            $("#BulkUploadTypeContainer").removeClass("cg_disabled");

            //$AdditionalFilesContainer.addClass('cg_disabled');
            if(isOnClick){
                var $AdditionalFilesContainer = $('#AdditionalFilesContainer');
                $AdditionalFilesContainer.find('.cg_view_option_checkbox').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
                $AdditionalFilesContainer.find('.ActivateBulkUpload').prop('checked',false);
                $('#AdditionalFilesCountContainer').addClass('cg_disabled');
                $('#AdditionalFiles').prop('checked',false);
            }

        }
        else{

            $("#BulkUploadQuantityContainer").addClass("cg_disabled");
            $("#BulkUploadMinQuantityContainer").addClass("cg_disabled");
            $("#BulkUploadTypeContainer").addClass("cg_disabled");

        }

    },
    cg_allowUploadJPG: function ($) {
        if($("#AllowUploadJPG").prop( "checked" )){
            $("#MaxResJPGonContainer").removeClass("cg_disabled");
            if($("#MaxResJPGon").prop("checked")){
                $("#MaxResJPGwidthContainer").removeClass("cg_disabled");
                $("#MaxResJPGheightContainer").removeClass("cg_disabled");
            }else{
                $("#MaxResJPGwidthContainer").addClass("cg_disabled");
                $("#MaxResJPGheightContainer").addClass("cg_disabled");
            }
            $("#MinResJPGonContainer").removeClass("cg_disabled");
            if($("#MinResJPGon").prop("checked")){
                $("#MinResJPGwidthContainer").removeClass("cg_disabled");
                $("#MinResJPGheightContainer").removeClass("cg_disabled");
            }else{
                $("#MinResJPGwidthContainer").addClass("cg_disabled");
                $("#MinResJPGheightContainer").addClass("cg_disabled");
            }
        }
        else{
            $("#MaxResJPGonContainer").addClass("cg_disabled");
            $("#MaxResJPGwidthContainer").addClass("cg_disabled");
            $("#MaxResJPGheightContainer").addClass("cg_disabled");
            $("#MinResJPGonContainer").addClass("cg_disabled");
            $("#MinResJPGwidthContainer").addClass("cg_disabled");
            $("#MinResJPGheightContainer").addClass("cg_disabled");
        }
    },
    cg_allowResJPG: function ($) {
        if($("#MaxResJPGon").prop( "checked" )){
            $("#MaxResJPGwidthContainer").removeClass("cg_disabled");
            $("#MaxResJPGheightContainer").removeClass("cg_disabled");
        }
        else{
            $("#MaxResJPGwidthContainer").addClass("cg_disabled");
            $("#MaxResJPGheightContainer").addClass("cg_disabled");
        }
        if($("#MinResJPGon").prop( "checked" )){
            $("#MinResJPGwidthContainer").removeClass("cg_disabled");
            $("#MinResJPGheightContainer").removeClass("cg_disabled");
        }
        else{
            $("#MinResJPGwidthContainer").addClass("cg_disabled");
            $("#MinResJPGheightContainer").addClass("cg_disabled");
        }
    },
    cg_allowUploadPNG: function ($) {
        if($("#AllowUploadPNG").prop( "checked" )){
            $("#MaxResPNGonContainer").removeClass("cg_disabled");
            if($("#MaxResPNGon").prop("checked")){
                $("#MaxResPNGwidthContainer").removeClass("cg_disabled");
                $("#MaxResPNGheightContainer").removeClass("cg_disabled");
            }else{
                $("#MaxResPNGwidthContainer").addClass("cg_disabled");
                $("#MaxResPNGheightContainer").addClass("cg_disabled");
            }
            $("#MinResPNGonContainer").removeClass("cg_disabled");
            if($("#MinResPNGon").prop("checked")){
                $("#MinResPNGwidthContainer").removeClass("cg_disabled");
                $("#MinResPNGheightContainer").removeClass("cg_disabled");
            }else{
                $("#MinResPNGwidthContainer").addClass("cg_disabled");
                $("#MinResPNGheightContainer").addClass("cg_disabled");
            }
        }
        else{
            $("#MaxResPNGonContainer").addClass("cg_disabled");
            $("#MaxResPNGwidthContainer").addClass("cg_disabled");
            $("#MaxResPNGheightContainer").addClass("cg_disabled");
            $("#MinResPNGonContainer").addClass("cg_disabled");
            $("#MinResPNGwidthContainer").addClass("cg_disabled");
            $("#MinResPNGheightContainer").addClass("cg_disabled");
        }
    },
    cg_allowResPNG: function ($) {
        if($("#MaxResPNGon").prop( "checked" )){
            $("#MaxResPNGwidthContainer").removeClass("cg_disabled");
            $("#MaxResPNGheightContainer").removeClass("cg_disabled");
        }
        else{
            $("#MaxResPNGwidthContainer").addClass("cg_disabled");
            $("#MaxResPNGheightContainer").addClass("cg_disabled");
        }
        if($("#MinResPNGon").prop( "checked" )){
            $("#MinResPNGwidthContainer").removeClass("cg_disabled");
            $("#MinResPNGheightContainer").removeClass("cg_disabled");
        }
        else{
            $("#MinResPNGwidthContainer").addClass("cg_disabled");
            $("#MinResPNGheightContainer").addClass("cg_disabled");
        }
    },
    cg_allowUploadGIF: function ($) {
        if($("#AllowUploadGIF").prop( "checked" )){
            $("#MaxResGIFonContainer").removeClass("cg_disabled");
            if($("#MaxResGIFon").prop("checked")){
                $("#MaxResGIFwidthContainer").removeClass("cg_disabled");
                $("#MaxResGIFheightContainer").removeClass("cg_disabled");
            }else{
                $("#MaxResGIFwidthContainer").addClass("cg_disabled");
                $("#MaxResGIFheightContainer").addClass("cg_disabled");
            }
            $("#MinResGIFonContainer").removeClass("cg_disabled");
            if($("#MinResGIFon").prop("checked")){
                $("#MinResGIFwidthContainer").removeClass("cg_disabled");
                $("#MinResGIFheightContainer").removeClass("cg_disabled");
            }else{
                $("#MinResGIFwidthContainer").addClass("cg_disabled");
                $("#MinResGIFheightContainer").addClass("cg_disabled");
            }
        }
        else{
            $("#MaxResGIFonContainer").addClass("cg_disabled");
            $("#MaxResGIFwidthContainer").addClass("cg_disabled");
            $("#MaxResGIFheightContainer").addClass("cg_disabled");
            $("#MinResGIFonContainer").addClass("cg_disabled");
            $("#MinResGIFwidthContainer").addClass("cg_disabled");
            $("#MinResGIFheightContainer").addClass("cg_disabled");
        }
    },
    cg_allowResGIF: function ($) {
        if($("#MaxResGIFon").prop( "checked" )){
            $("#MaxResGIFwidthContainer").removeClass("cg_disabled");
            $("#MaxResGIFheightContainer").removeClass("cg_disabled");
        }
        else{
            $("#MaxResGIFwidthContainer").addClass("cg_disabled");
            $("#MaxResGIFheightContainer").addClass("cg_disabled");
        }
        if($("#MinResGIFon").prop( "checked" )){
            $("#MinResGIFwidthContainer").removeClass("cg_disabled");
            $("#MinResGIFheightContainer").removeClass("cg_disabled");
        }
        else{
            $("#MinResGIFwidthContainer").addClass("cg_disabled");
            $("#MinResGIFheightContainer").addClass("cg_disabled");
        }
    },
    cg_allowUploadICO: function ($) {

        if($("#AllowUploadICO").prop( "checked" )){

            $("#MaxResICOonContainer").removeClass("cg_disabled");
            $("#MaxResICOwidthContainer").removeClass("cg_disabled");
            $("#MaxResICOheightContainer").removeClass("cg_disabled");

        }
        else{

            $("#MaxResICOonContainer").addClass("cg_disabled");
            $("#MaxResICOwidthContainer").addClass("cg_disabled");
            $("#MaxResICOheightContainer").addClass("cg_disabled");

        }

    },
    cg_allowResICO: function ($) {

        if($("#MaxResICOon").prop( "checked" )){

            $("#MaxResICOwidthContainer").removeClass("cg_disabled");
            $("#MaxResICOheightContainer").removeClass("cg_disabled");

        }
        else{

            $("#MaxResICOwidthContainer").addClass("cg_disabled");
            $("#MaxResICOheightContainer").addClass("cg_disabled");

        }

    },
    cg_HeightLookOnLoad: function ($) {

        $('.cg_options_sortable .HeightLook').each(function () {
            cgJsClassAdmin.options.functions.cg_HeightLook(false,$,$(this));
        });

    },
    cg_HeightLook: function (click,$,$element) {

        var $cg_options_sortable = $element.closest('.cg_options_sortable');

        if($cg_options_sortable.find(".HeightLook:not(.cg_shortcode_checkbox_clone)").prop('checked')){

            $cg_options_sortable.find(".HeightLookHeightContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".HeightViewSpaceWidthContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".HeightViewSpaceHeightContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".HeightViewBorderWidthContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".HeightViewBorderColorContainer").removeClass("cg_disabled");

        }else if(!$cg_options_sortable.find(".HeightLook:not(.cg_shortcode_checkbox_clone)").prop( "checked" ) && click && (!$cg_options_sortable.find(".RowLook:not(.cg_shortcode_checkbox_clone)").prop('checked') && !$cg_options_sortable.find(".ThumbLook:not(.cg_shortcode_checkbox_clone)").prop('checked')
            && !$cg_options_sortable.find(".SliderLook:not(.cg_shortcode_checkbox_clone)").prop('checked') && !$cg_options_sortable.find(".BlogLook:not(.cg_shortcode_checkbox_clone)").prop('checked'))){

            $cg_options_sortable.find(".HeightLook").prop('checked',true);
            $cg_options_sortable.find(".HeightLookContainer .cg_shortcode_checkbox_clone").remove();
            $cg_options_sortable.find(".HeightLookContainer").removeClass("cg_disabled");
            $cg_options_sortable.find('.HeightLookContainer .cg_view_option_checkbox').addClass('cg_view_option_checked');
            $cg_options_sortable.find('.HeightLookContainer .cg_view_option_checkbox').removeClass('cg_view_option_unchecked');

        }else{

            $cg_options_sortable.find(".HeightLookHeightContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".HeightViewSpaceWidthContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".HeightViewSpaceHeightContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".HeightViewBorderWidthContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".HeightViewBorderColorContainer").addClass("cg_disabled");

        }

    },
    cg_ThumbLookOnLoad: function ($) {

        $('.cg_options_sortable .ThumbLook').each(function () {
            cgJsClassAdmin.options.functions.cg_ThumbLook(false,$,$(this));
        });

    },
    cg_ThumbLook: function (click,$,$element) {

        var $cg_options_sortable = $element.closest('.cg_options_sortable');

        if($cg_options_sortable.find(".ThumbLook:not(.cg_shortcode_checkbox_clone)").prop('checked')){

            $cg_options_sortable.find(".WidthThumbContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".HeightThumbContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".DistancePicsContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".DistancePicsVContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".ThumbViewBorderWidthContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".ThumbViewBorderColorContainer").removeClass("cg_disabled");

        }else if(!$cg_options_sortable.find(".ThumbLook:not(.cg_shortcode_checkbox_clone)").prop( "checked" ) && click && (!$cg_options_sortable.find(".RowLook:not(.cg_shortcode_checkbox_clone)").prop('checked') && !$cg_options_sortable.find(".HeightLook:not(.cg_shortcode_checkbox_clone)").prop('checked')
            && !$cg_options_sortable.find(".SliderLook:not(.cg_shortcode_checkbox_clone)").prop('checked') && !$cg_options_sortable.find(".BlogLook:not(.cg_shortcode_checkbox_clone)").prop('checked'))){

            $cg_options_sortable.find(".ThumbLook:not(.cg_shortcode_checkbox_clone)").prop('checked',true);
            $cg_options_sortable.find(".ThumbLookContainer .cg_shortcode_checkbox_clone").remove();
            $cg_options_sortable.find(".ThumbLookContainer").removeClass("cg_disabled");
            $cg_options_sortable.find('.ThumbLookContainer .cg_view_option_checkbox').addClass('cg_view_option_checked');
            $cg_options_sortable.find('.ThumbLookContainer .cg_view_option_checkbox').removeClass('cg_view_option_unchecked');
        }
        else{

            $cg_options_sortable.find(".WidthThumbContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".HeightThumbContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".DistancePicsContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".DistancePicsVContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".ThumbViewBorderWidthContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".ThumbViewBorderColorContainer").addClass("cg_disabled");

        }

    },
    cg_RowLookOnLoad: function ($) {

        $('.cg_options_sortable .RowLook').each(function () {
            cgJsClassAdmin.options.functions.cg_RowLook(false,$,$(this));
        });

    },
    cg_RowLook: function (click,$,$element) {

        var $cg_options_sortable = $element.closest('.cg_options_sortable');

        if($cg_options_sortable.find(".RowLook:not(.cg_shortcode_checkbox_clone)").prop('checked')){

            $cg_options_sortable.find(".PicsInRowContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".RowViewSpaceWidthContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".RowViewSpaceHeightContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".RowViewBorderWidthContainer").removeClass("cg_disabled");
            $cg_options_sortable.find(".RowViewBorderColorContainer").removeClass("cg_disabled");

        }else if(!$cg_options_sortable.find(".RowLook:not(.cg_shortcode_checkbox_clone)").prop( "checked" ) && click && (!$cg_options_sortable.find(".ThumbLook:not(.cg_shortcode_checkbox_clone)").prop('checked') && !$cg_options_sortable.find(".HeightLook:not(.cg_shortcode_checkbox_clone)").prop('checked')
            && !$cg_options_sortable.find(".SliderLook:not(.cg_shortcode_checkbox_clone)").prop('checked') && !$cg_options_sortable.find(".BlogLook:not(.cg_shortcode_checkbox_clone)").prop('checked'))
        ){

            $cg_options_sortable.find(".RowLook:not(.cg_shortcode_checkbox_clone)").prop('checked',true);
            $cg_options_sortable.find(".cg_shortcode_checkbox_clone").remove();
            $cg_options_sortable.find(".RowLookContainer .cg_shortcode_checkbox_clone").remove();
            $cg_options_sortable.find(".RowLookContainer").removeClass("cg_disabled");
            $cg_options_sortable.find('.RowLookContainer .cg_view_option_checkbox').addClass('cg_view_option_checked');
            $cg_options_sortable.find('.RowLookContainer .cg_view_option_checkbox').removeClass('cg_view_option_unchecked');

        }
        else{

            $cg_options_sortable.find(".PicsInRowContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".RowViewSpaceWidthContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".RowViewSpaceHeightContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".RowViewBorderWidthContainer").addClass("cg_disabled");
            $cg_options_sortable.find(".RowViewBorderColorContainer").addClass("cg_disabled");

        }

    },
    cg_SliderLookOnLoad: function ($) {

        $('#cg_main_options .SliderLook').each(function () {
            cgJsClassAdmin.options.functions.cg_SliderLook(false,$,$(this));
        });

    },
    cg_SliderLook: function (click,$,$element) {

        var $cg_options_sortable = $element.closest('.cg_options_sortable');

        if(!$cg_options_sortable.find(".SliderLook:not(.cg_shortcode_checkbox_clone)").prop( "checked" ) && click && (!$cg_options_sortable.find(".RowLook:not(.cg_shortcode_checkbox_clone)").prop('checked') && !$cg_options_sortable.find(".HeightLook:not(.cg_shortcode_checkbox_clone)").prop('checked')
            && !$cg_options_sortable.find(".ThumbLook:not(.cg_shortcode_checkbox_clone)").prop('checked') && !$cg_options_sortable.find(".BlogLook:not(.cg_shortcode_checkbox_clone)").prop('checked'))){

            $cg_options_sortable.find(".SliderLook:not(.cg_shortcode_checkbox_clone)").prop('checked',true);
            $cg_options_sortable.find(".cg_shortcode_checkbox_clone").remove();
            $cg_options_sortable.find(".SliderLookContainer .cg_shortcode_checkbox_clone").remove();
            $cg_options_sortable.find(".SliderLookContainer").removeClass("cg_disabled");
            $cg_options_sortable.find('.SliderLookContainer .cg_view_option_checkbox').addClass('cg_view_option_checked');
            $cg_options_sortable.find('.SliderLookContainer .cg_view_option_checkbox').removeClass('cg_view_option_unchecked');

        }

        if($element.prop('checked')){
            $cg_options_sortable.find(".SliderThumbNavContainer").removeClass("cg_disabled");
        }else{
            $cg_options_sortable.find(".SliderThumbNavContainer").addClass("cg_disabled");
        }

    },
    cg_BlogLook: function (click,$,$element) {

        var $cg_options_sortable = $element.closest('.cg_options_sortable');

        if(!$cg_options_sortable.find(".BlogLook:not(.cg_shortcode_checkbox_clone)").prop( "checked" ) && click && (!$cg_options_sortable.find(".RowLook:not(.cg_shortcode_checkbox_clone)").prop('checked') && !$cg_options_sortable.find(".HeightLook:not(.cg_shortcode_checkbox_clone)").prop('checked')
            && !$cg_options_sortable.find(".ThumbLook:not(.cg_shortcode_checkbox_clone)").prop('checked') && !$cg_options_sortable.find(".SliderLook:not(.cg_shortcode_checkbox_clone)").prop('checked'))){

            $cg_options_sortable.find(".BlogLook:not(.cg_shortcode_checkbox_clone)").prop('checked',true);
            $cg_options_sortable.find(".cg_shortcode_checkbox_clone").remove();
            $cg_options_sortable.find(".BlogLookContainer .cg_shortcode_checkbox_clone").remove();
            $cg_options_sortable.find(".BlogLookContainer").removeClass("cg_disabled");
            $cg_options_sortable.find('.BlogLookContainer .cg_view_option_checkbox').addClass('cg_view_option_checked');
            $cg_options_sortable.find('.BlogLookContainer .cg_view_option_checkbox').removeClass('cg_view_option_unchecked');

        }

    },
    checkAfterContactSubmit: function ($) {
        if($('#ConOptForwardAfterContactActive').prop("checked")){
            $( "#ConOptForwardURLContainer" ).removeClass('cg_disabled');
            $( "#ConOptShowConfirmTextAfterContactActive" ).prop('checked',false);
            $( "#ConOptShowConfirmTextAfterContactActiveContainer" ).find('.cg_view_option_radio').addClass('cg_view_option_unchecked').removeClass('cg_view_option_checked');
            $( "#ConOptShowFormAfterContactActiveContainer" ).addClass('cg_disabled');
            $( "#wp-ConOptConfirmTextAfterContact-wrap-Container" ).addClass('cg_disabled');
        } else{
            $( "#ConOptForwardURLContainer" ).addClass('cg_disabled');
            $( "#ConOptShowConfirmTextAfterContactActive" ).prop('checked',true);
            $( "#ConOptShowConfirmTextAfterContactActiveContainer" ).find('.cg_view_option_radio').addClass('cg_view_option_checked').removeClass('cg_view_option_unchecked');
            $( "#ConOptShowFormAfterContactActiveContainer" ).removeClass('cg_disabled');
            $( "#wp-ConOptConfirmTextAfterContact-wrap-Container" ).removeClass('cg_disabled');
        }
    },
    cg_confirm_after_upload: function ($) {

        if($('#cg_confirm_text').prop("checked")){
            $( "#forwardContainer" ).find('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
            $( "#forward" ).prop('checked',false);
            $( "#forward_urlContainer" ).addClass('cg_disabled');
            $( "#wp-confirmation_text-wrap-Container" ).removeClass('cg_disabled');
            $( "#ShowFormAfterUploadContainer" ).removeClass('cg_disabled');
        } else{
            $( "#forwardContainer" ).find('.cg_view_option_radio').addClass('cg_view_option_checked').removeClass('cg_view_option_unchecked');
            $( "#forward_urlContainer" ).removeClass('cg_disabled');
            $( "#wp-confirmation_text-wrap-Container" ).addClass('cg_disabled');
            $( "#forward" ).prop('checked',true);
            $( "#ShowFormAfterUploadContainer" ).addClass('cg_disabled');
        }

    },
    cg_forward_after_upload: function ($) {

        if($('#forward').prop("checked")){

            $( "#cg_confirm_textContainer" ).find('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
            $( "#forward_urlContainer" ).removeClass('cg_disabled');
            $( "#wp-confirmation_text-wrap-Container" ).addClass('cg_disabled');
            $( "#cg_confirm_text" ).prop('checked',false);
            $( "#ShowFormAfterUploadContainer" ).addClass('cg_disabled');

        }
        else{

            $( "#cg_confirm_textContainer" ).find('.cg_view_option_radio').addClass('cg_view_option_checked').removeClass('cg_view_option_unchecked');
            $( "#forward_urlContainer" ).addClass('cg_disabled');
            $( "#wp-confirmation_text-wrap-Container" ).removeClass('cg_disabled');
            $( "#cg_confirm_text" ).prop('checked',true);
            $( "#ShowFormAfterUploadContainer" ).removeClass('cg_disabled');

        }

    },
    cg_inform_admin_after_upload: function ($) {

        if($('#InformAdmin').prop("checked")){
            $( ".cg_inform_admin" ).removeClass('cg_disabled');
        }
        else{
            $( ".cg_inform_admin" ).addClass('cg_disabled');
        }

    },
    cg_CommNoteActive: function ($) {

        if($('#CommNoteActive').prop("checked")){
            $( ".cg_comm_note_option" ).removeClass('cg_disabled');
        }
        else{
            $( ".cg_comm_note_option" ).addClass('cg_disabled');
        }

    },
    cg_after_login: function ($) {

        if($("#ForwardAfterLoginUrlCheck").prop("checked")){

            $( "#ForwardAfterLoginTextCheckContainer" ).find('.cg_view_option_radio').addClass('cg_view_option_unchecked').removeClass('cg_view_option_checked');
            $( "#ForwardAfterLoginUrlContainer" ).removeClass("cg_disabled");
            $( "#wp-ForwardAfterLoginText-wrap-Container" ).addClass("cg_disabled");
            $( "#ForwardAfterLoginTextCheck" ).prop("checked",false);

        }

        else{
            $( "#ForwardAfterLoginTextCheckContainer" ).find('.cg_view_option_radio').addClass('cg_view_option_checked').removeClass('cg_view_option_unchecked');
            $( "#ForwardAfterLoginUrlContainer" ).addClass("cg_disabled");
            $( "#wp-ForwardAfterLoginText-wrap-Container" ).removeClass("cg_disabled");
            $( "#ForwardAfterLoginTextCheck" ).prop("checked",true);

        }

    },
    cg_after_confirm_text: function ($) {

        if($("#ForwardAfterLoginTextCheck").prop("checked")){

            $( "#ForwardAfterLoginUrlCheckContainer" ).find('.cg_view_option_radio').addClass('cg_view_option_unchecked').removeClass('cg_view_option_checked');
            $( "#wp-ForwardAfterLoginText-wrap-Container" ).removeClass("cg_disabled");
            $( "#ForwardAfterLoginUrlContainer" ).addClass("cg_disabled");
            $( "#ForwardAfterLoginUrlCheck" ).prop("checked",false);

        } else{

            $( "#ForwardAfterLoginUrlCheckContainer" ).find('.cg_view_option_radio').addClass('cg_view_option_checked').removeClass('cg_view_option_unchecked');
            $( "#wp-ForwardAfterLoginText-wrap-Container" ).addClass("cg_disabled");
            $( "#ForwardAfterLoginUrlContainer" ).removeClass("cg_disabled");
            $( "#ForwardAfterLoginUrlCheck" ).prop("checked",true);

        }

    },
    cg_mail_confirm_email: function ($) {

        if($("#mConfirmSendConfirm").prop("checked")){

            $( "#mConfirmAdminContainer" ).removeClass("cg_disabled");
            $( "#mConfirmReplyContainer" ).removeClass("cg_disabled");
            $( "#mConfirmCCContainer" ).removeClass("cg_disabled");
            $( "#mConfirmBCCContainer" ).removeClass("cg_disabled");
            $( "#mConfirmHeaderContainer" ).removeClass("cg_disabled");
            $( "#mConfirmURLContainer" ).removeClass("cg_disabled");
            $( "#wp-mConfirmContent-wrap-Container" ).removeClass("cg_disabled");
            $( "#wp-mConfirmConfirmationText-wrap-Container" ).removeClass("cg_disabled");

        }

        else{

            $( "#mConfirmAdminContainer" ).addClass("cg_disabled");
            $( "#mConfirmReplyContainer" ).addClass("cg_disabled");
            $( "#mConfirmCCContainer" ).addClass("cg_disabled");
            $( "#mConfirmBCCContainer" ).addClass("cg_disabled");
            $( "#mConfirmHeaderContainer" ).addClass("cg_disabled");
            $( "#mConfirmURLContainer" ).addClass("cg_disabled");
            $( "#wp-mConfirmContent-wrap-Container" ).addClass("cg_disabled");
            $( "#wp-mConfirmConfirmationText-wrap-Container" ).addClass("cg_disabled");

        }

    },
    cg_image_activation_email: function ($) {
        if($("#InformUsers").prop("checked")){
            $( ".cg_file_activation_email_option" ).removeClass("cg_disabled");
        } else{
            $( ".cg_file_activation_email_option" ).addClass("cg_disabled");
        }
    },
    cg_contact_entry_user_email: function ($) {
        if($("#ConOptInformUserActive").prop("checked")){
            $( ".cg_inform_user_contact_entry" ).removeClass("cg_disabled");
        } else{
            $( ".cg_inform_user_contact_entry" ).addClass("cg_disabled");
        }
    },
    cgRegUserGalleryOnlyOnLoad: function ($) {
        $('.RegUserGalleryOnlyContainer').each(function (){
            cgJsClassAdmin.options.functions.cgRegUserGalleryOnly($,$(this).closest('.cg_view_container'));
        });
    },
    cgRegUserGalleryOnly: function ($,$cg_view_container) {
        if($cg_view_container.find('.RegUserGalleryOnly').prop("checked")){
            $cg_view_container.find('.RegUserGalleryOnlyTextContainer').removeClass("cg_disabled");
        }
        else{
            $cg_view_container.find('.RegUserGalleryOnlyTextContainer').addClass("cg_disabled");
        }
    },
    cg_pro_version_wp_editor_check: function ($) {

        $('.cg-pro-false-container').find('.wp-switch-editor:first-child').click();
        $('.cg-pro-false-container').find('.wp-core-ui.wp-editor-wrap').addClass('cg_disabled');

    },
    cg_user_reocgnising_method: function ($,$CheckMethod) {

        var $CheckIp = $('#CheckIp');
        var $CheckCookie = $('#CheckCookie');
        var $CheckLogin = $('#CheckLogin');
        var $CheckGoogle = $('#CheckGoogle');
        var $CheckIpAndCookie = $('#CheckIpAndCookie');

        if($CheckMethod){
            $CheckMethod.closest('.cg_view').find('.CheckMethod').prop('checked',false).removeAttr('checked');// reset first
            $CheckMethod.prop('checked',true)
        }

        if($CheckIp.prop('checked') || $CheckIp.attr('checked')){
            $('#CheckLoginContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckCookieContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckIpAndCookieContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckGoogleContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckCookieAlertMessageContainer').addClass('cg_disabled');
            $('#CheckCookie').prop('checked',false);
            $('#CheckLogin').prop('checked',false);
            $('#CheckIpAndCookie').prop('checked',false);
            $('#CheckGoogle').prop('checked',false);
        } else if($CheckCookie.prop('checked') || $CheckCookie.attr('checked')){
            $('#CheckCookieAlertMessageContainer').removeClass('cg_disabled');
            $('#CheckIpContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckLoginContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckIpAndCookieContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckGoogleContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckIp').prop('checked',false);
            $('#CheckLogin').prop('checked',false);
            $('#CheckIpAndCookie').prop('checked',false);
            $('#CheckGoogle').prop('checked',false);
        }  else if($CheckIpAndCookie.prop('checked') || $CheckIpAndCookie.attr('checked')){
            $('#CheckCookieAlertMessageContainer').removeClass('cg_disabled');
            $('#CheckIpContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckCookieContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckLoginContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckIp').prop('checked',false);
            $('#CheckCookie').prop('checked',false);
            $('#CheckLogin').prop('checked',false);
            $('#CheckGoogle').prop('checked',false);
        }else if($CheckLogin.prop('checked') || $CheckLogin.attr('checked')){
            $('#CheckCookieAlertMessageContainer').addClass('cg_disabled');
            $('#CheckIpContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckCookieContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckIpAndCookieContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckGoogleContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckIp').prop('checked',false);
            $('#CheckCookie').prop('checked',false);
            $('#CheckIpAndCookie').prop('checked',false);
            $('#CheckGoogle').prop('checked',false);
        }else if($CheckGoogle.prop('checked') || $CheckGoogle.attr('checked')){
            $('#CheckCookieAlertMessageContainer').addClass('cg_disabled');
            $('#CheckIpContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckCookieContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckIpAndCookieContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckLoginContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckIp').prop('checked',false);
            $('#CheckCookie').prop('checked',false);
            $('#CheckIpAndCookie').prop('checked',false);
            $('#CheckLogin').prop('checked',false);
        }

    },
    cg_user_recognising_method_upload: function ($,$CheckMethodUpload) {

        var $CheckIpUpload = $('#CheckIpUpload');
        var $CheckCookieUpload = $('#CheckCookieUpload');
        var $CheckLoginUpload = $('#CheckLoginUpload');
        var $CheckGoogleUpload = $('#CheckGoogleUpload');

        if($CheckMethodUpload){
            $CheckMethodUpload.closest('.cg_view').find('.CheckMethodUpload').prop('checked',false).removeAttr('checked');// reset first
            $CheckMethodUpload.prop('checked',true)
        }

        if($CheckIpUpload.prop('checked') || $CheckIpUpload.attr('checked')){
            $('#CheckLoginUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckCookieUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckGoogleUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#UploadRequiresCookieMessageContainer').addClass('cg_disabled');
            $('#RegUserUploadOnlyTextContainer').addClass('cg_disabled');
            $('#GoogleSignInUserUploadOnlyTextContainer').addClass('cg_disabled');
            $('#CheckCookieUpload').prop('checked',false);
            $('#CheckLoginUpload').prop('checked',false);
            $('#CheckGoogleUpload').prop('checked',false);
        } else if($CheckCookieUpload.prop('checked') || $CheckCookieUpload.attr('checked')){
            $('#UploadRequiresCookieMessageContainer').removeClass('cg_disabled');
            $('#RegUserUploadOnlyTextContainer').addClass('cg_disabled');
            $('#GoogleSignInUserUploadOnlyTextContainer').addClass('cg_disabled');
            $('#CheckIpUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckLoginUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckGoogleUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckIpUpload').prop('checked',false);
            $('#CheckLoginUpload').prop('checked',false);
            $('#CheckGoogleUpload').prop('checked',false);
        }  else if($CheckLoginUpload.prop('checked') || $CheckLoginUpload.attr('checked')){
            $('#UploadRequiresCookieMessageContainer').addClass('cg_disabled');
            $('#RegUserUploadOnlyTextContainer').removeClass('cg_disabled');
            $('#GoogleSignInUserUploadOnlyTextContainer').addClass('cg_disabled');
            $('#CheckIpUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckCookieUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckGoogleUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckIpUpload').prop('checked',false);
            $('#CheckCookieUpload').prop('checked',false);
            $('#CheckGoogleUpload').prop('checked',false);
        } else if($CheckGoogleUpload.prop('checked') || $CheckGoogleUpload.attr('checked')){
            $('#CheckIpUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckLoginUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#CheckCookieUploadContainer').find('.cg_view_option_radio').removeClass('cg_view_option_checked');
            $('#UploadRequiresCookieMessageContainer').addClass('cg_disabled');
            $('#RegUserUploadOnlyTextContainer').addClass('cg_disabled');
            $('#GoogleSignInUserUploadOnlyTextContainer').removeClass('cg_disabled');
            $('#CheckIpUpload').prop('checked',false);
            $('#CheckCookieUpload').prop('checked',false);
            $('#CheckLoginUpload').prop('checked',false);
        }

    },
    cg_set_wpnonce: function ($) {

        $('.cg-rating-reset').each(function () {

            $(this).attr('href',$(this).attr('href')+'&_wpnonce='+$('#_wpnonce').val());

        });

    },
    cg_HideRegFormAfterLogin: function ($) {

        if($('#HideRegFormAfterLogin').prop('checked')){
            $('#HideRegFormAfterLoginShowTextInsteadContainer').removeClass('cg_disabled');
            //   $('#wp-HideRegFormAfterLoginTextToShow-wrap').removeClass('cg_disabled');
        }else{
            $('#HideRegFormAfterLoginShowTextInsteadContainer').addClass('cg_disabled');
            $('#wp-HideRegFormAfterLoginTextToShow-wrap-Container').addClass('cg_disabled');
        }

    },
    cg_HideRegFormAfterLoginShowTextInstead: function ($) {

        if($('#HideRegFormAfterLoginShowTextInstead').prop('checked') && $('#HideRegFormAfterLogin').prop('checked')){
            $('#wp-HideRegFormAfterLoginTextToShow-wrap-Container').removeClass('cg_disabled');
        }else{
            $('#wp-HideRegFormAfterLoginTextToShow-wrap-Container').addClass('cg_disabled');
        }

    },
    cg_LostPasswordMailActiveCheck: function ($) {

        if($('#LostPasswordMailActive').prop('checked')){
            $('#LostPasswordMailAddressorContainer').removeClass('cg_disabled');
            $('#LostPasswordMailReplyContainer').removeClass('cg_disabled');
            $('#LostPasswordMailSubjectContainer').removeClass('cg_disabled');
            $('#wp-LostPasswordMailConfirmation-wrap-Container').removeClass('cg_disabled');
        }else{
            $('#LostPasswordMailAddressorContainer').addClass('cg_disabled');
            $('#LostPasswordMailReplyContainer').addClass('cg_disabled');
            $('#LostPasswordMailSubjectContainer').addClass('cg_disabled');
            $('#wp-LostPasswordMailConfirmation-wrap-Container').addClass('cg_disabled');
        }

    },
    cgAllowSortCheckOnLoad: function ($) {

        $('.AllowSort').each(function () {

            if($(this).prop('checked')){
                $(this).closest('.cg_view').find('.cgAllowSortOptionsContainerMain').removeClass('cg_disabled');
                $(this).closest('.cg_view').find('.cgAllowSortDependsOnMessage').addClass('cg_hide');
            }else{
                $(this).closest('.cg_view').find('.cgAllowSortOptionsContainerMain').addClass('cg_disabled');
                $(this).closest('.cg_view').find('.cgAllowSortDependsOnMessage').addClass('cg_hide');
            }

        });

    },
    cgAllowSortCheck: function ($,$element) {

        if($element.prop('checked')){
            $element.closest('.cg_view').find('.cgAllowSortOptionsContainerMain').removeClass('cg_disabled');
            $element.closest('.cg_view').find('.cgAllowSortDependsOnMessage').addClass('cg_hide');
        }else{
            $element.closest('.cg_view').find('.cgAllowSortOptionsContainerMain').addClass('cg_disabled');
            $element.closest('.cg_view').find('.cgAllowSortDependsOnMessage').addClass('cg_hide');
        }

    },
    modifyUnmodifyImageNamePath: function ($,$element) {

        $element = ($element) ? $element : $('#CustomImageNameContainer').find('#CustomImageName');

        if($element.prop('checked')){
            $('#CustomImageNamePathContainer').removeClass('cg_disabled');
        }else{
            $('#CustomImageNamePathContainer').addClass('cg_disabled');
        }

    },
    allowDisallowAdditionalFiles: function ($,$element) {

        $element = ($element) ? $element : $('#AdditionalFilesContainer').find('#AdditionalFiles');

        var $ActivateBulkUploadContainer = $('#ActivateBulkUploadContainer');

        if($element.prop('checked')){
            $('#AdditionalFilesCountContainer').removeClass('cg_disabled');
            //$ActivateBulkUploadContainer.addClass('cg_disabled');
            $ActivateBulkUploadContainer.find('.cg_view_option_checkbox').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
            $('#BulkUploadMinQuantityContainer').addClass('cg_disabled');
            $('#BulkUploadQuantityContainer').addClass('cg_disabled');
            $('#ActivateBulkUpload').prop('checked',false);
        }else{
            $('#AdditionalFilesCountContainer').addClass('cg_disabled');
            //$ActivateBulkUploadContainer.removeClass('cg_disabled');
            $('#BulkUploadMinQuantityContainer').removeClass('cg_disabled');
            $('#BulkUploadQuantityContainer').removeClass('cg_disabled');
        }

    },
    informUserComment: function ($,$element) {

        $element = ($element) ? $element : $('#InformUserComment');

        if($element.prop('checked')){
            $('.cg_inform_user_comment').removeClass('cg_disabled');
        }else{
            $('.cg_inform_user_comment').addClass('cg_disabled');
        }

    },
    informUserVote: function ($,$element) {

        $element = ($element) ? $element : $('#InformUserVote');

        if($element.prop('checked')){
            $('.cg_inform_user_vote').removeClass('cg_disabled');
        }else{
            $('.cg_inform_user_vote').addClass('cg_disabled');
        }

    },
    informUserUpload: function ($,$element) {

        $element = ($element) ? $element : $('#InformUserUpload');

        if($element.prop('checked')){
            $('.cg_inform_user_upload').removeClass('cg_disabled');
        }else{
            $('.cg_inform_user_upload').addClass('cg_disabled');
        }

    },
    cgVoteMessageSuccessActiveCheck: function ($) {
        if($('#VoteMessageSuccessActive').prop("checked")){
                $('#VoteMessageSuccessTextContainer').removeClass('cg_disabled');
            }else{
                $('#VoteMessageSuccessTextContainer').addClass('cg_disabled');
        }
    },
    cgVoteMessageWarningActiveCheck: function ($) {
        if($('#VoteMessageWarningActive').prop("checked")){
                $('#VoteMessageWarningTextContainer').removeClass('cg_disabled');
            }else{
                $('#VoteMessageWarningTextContainer').addClass('cg_disabled');
            }
    },
   /* cg_RatingVisibleForGalleryNoVotingLoop: function ($) {

        $('.cg_short_code_multiple_pics_configuration_container .RatingVisibleForGalleryNoVoting').each(function (){
            cgJsClassAdmin.options.functions.cg_RatingVisibleForGalleryNoVoting($,$(this).closest('.cg_view_container'));
        });

    },*/
    cg_RatingVisibleForGalleryNoVoting: function ($,$cg_view_container) {
        if($cg_view_container.find('.RatingVisibleForGalleryNoVotingCheckbox').prop("checked")){
            $cg_view_container.find('.cgGalleryNoVotingRatingInputs.cg-allow-sort-option').removeClass('cg_disabled');
            $cg_view_container.find('option.cgGalleryNoVotingRatingInputs').removeClass('cg_hide');
        }else{
            $cg_view_container.find('.cgGalleryNoVotingRatingInputs.cg-allow-sort-option').addClass('cg_disabled');
            $cg_view_container.find('option.cgGalleryNoVotingRatingInputs').addClass('cg_hide');
        }
    },
    cg_EnableSwitchStyleGalleryButtonOnLoad: function ($) {
        $('.EnableSwitchStyleGalleryButtonContainer').each(function (){
            cgJsClassAdmin.options.functions.cg_EnableSwitchStyleGalleryButton($,$(this).closest('.cg_view_container'));
        });
    },
    cg_EnableSwitchStyleGalleryButton: function ($,$cg_view_container) {
        if($cg_view_container.find('.EnableSwitchStyleGalleryButton').prop("checked")){
            $cg_view_container.find('.SwitchStyleGalleryButtonOnlyTopControlsContainer').removeClass('cg_disabled');
        }else{
            $cg_view_container.find('.SwitchStyleGalleryButtonOnlyTopControlsContainer').addClass('cg_disabled');
        }
    },
    cg_EnableSwitchStyleImageViewButtonOnLoad: function ($) {
        $('.EnableSwitchStyleImageViewButtonContainer').each(function (){
            cgJsClassAdmin.options.functions.cg_EnableSwitchStyleImageViewButton($,$(this).closest('.cg_view_container'));
        });
    },
    cg_EnableSwitchStyleImageViewButton: function ($,$cg_view_container) {
        if($cg_view_container.find('.EnableSwitchStyleImageViewButton').prop("checked")){
            $cg_view_container.find('.SwitchStyleImageViewButtonOnlyImageViewContainer').removeClass('cg_disabled');
        }else{
            $cg_view_container.find('.SwitchStyleImageViewButtonOnlyImageViewContainer').addClass('cg_disabled');
        }
    },
    addCheckedUncheckedClasses: function ($) {

        //jQuery('.cg_short_code_single_pic_configuration.cg_short_code_single_pic_configuration_cg_gallery_user').click();

        //setTimeout(function (){

        $('.cg_view_option_checkbox input[type="checkbox"]').each(function (){
            var $element = $(this);
            if($element.prop('checked')){
                $element.closest('.cg_view_option_checkbox').addClass('cg_view_option_checked');
            }else{
                $element.closest('.cg_view_option_checkbox').addClass('cg_view_option_unchecked');
            }
        });

        $('.cg_view_option_radio input[type="radio"]').each(function (){
            var $element = $(this);
            /* if($element.attr('name')=='multiple-pics[cg_gallery_user][general][FullSizeImageOutGallery]'){
                 debugger
             }*/
            if($element.prop('checked')){
                $element.closest('.cg_view_option_radio').addClass('cg_view_option_checked');
            } else{
                $element.closest('.cg_view_option_radio').addClass('cg_view_option_unchecked');
            }
        });

        $('.cg_view_option_radio_multiple_input input[type="radio"]').each(function (){
            var $element = $(this);
            if($element.prop('checked')){
                $element.closest('.cg_view_option_radio_multiple_input').addClass('cg_view_option_checked');
            } else{
                $element.closest('.cg_view_option_radio_multiple_input').addClass('cg_view_option_unchecked');
            }
        });

        //  },3000);



    }
};
