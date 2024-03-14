jQuery(document).ready(function($){

/*    $(document).on('click', '.cg_view_option', function (e) {
        cgViewOptionCheck(this,e);
    });*/

    $(document).on('focus', '.cg_view_option_input input', function () {
        cgJsClassAdmin.options.vars.focusedInputField = $(this);
    });

    $(document).on('blur', '.cg_view_option_input input', function () {
        cgJsClassAdmin.options.vars.focusedInputField = null;
    });

    // Only numbers allowed
    $(document).on('input','#PicsPerSite,#HeightLookHeight,#HeightViewSpaceWidth,#HeightViewSpaceHeight,#WidthThumb,#HeightThumb,#DistancePics,#DistancePicsV' +
        '#PicsInRow,#RowViewSpaceWidth,#RowViewSpaceHeight',function () {

        // otherwise backspace does not work
        if(this.value==''){
            return;
        }

        if(/^\d+$/.test(this.value)==false){
            this.value=0;
        }

    });

    $(document).on('click',"#SlideHorizontal",function (e) {
        //cgViewOptionCheck(this,e);

        if($(this).is( ":checked" )){
            $(this).prop( "checked", true);
            $("#SlideVertical").prop( "checked", false);
        }else{
            $(this).prop( "checked", false);
            $("#SlideVertical").prop( "checked", true);
        }
    });

    $(document).on('click',"#SlideVertical",function () {
        if($(this).is( ":checked" )){
            $(this).prop( "checked", true);
            $("#SlideHorizontal").prop( "checked", false);
        }else{
            $(this).prop( "checked", false);
            $("#SlideHorizontal").prop( "checked", true);
        }
    });

    $(document).on('click', '#cg_main_options .cg_move_view_to_top', function(e){
        //cgViewOptionCheck(this,e);

        var sortableView = $(this).closest('.cg_options_sortableContainer');
        sortableView.insertBefore(sortableView.prev('.cg_options_sortableContainer'));
        // sortableView.next().find('.cg_move_view_to_top').removeClass('cg_hide');
        //   $('.cg_options_sortableContainer:first-child .cg_move_view_to_bottom, .cg_options_sortableContainer:nth-child(2) .cg_move_view_to_bottom').removeClass('cg_hide');
        //  $('.cg_options_sortableContainer:nth-child(3) .cg_move_view_to_bottom').addClass('cg_hide');

        //  $('.cg_options_sortableContainer:first-child .cg_move_view_to_top').addClass('cg_hide');
        //  $('.cg_options_sortableContainer:nth-child(2) .cg_move_view_to_top').removeClass('cg_hide');
        // $('.cg_options_sortableContainer:nth-child(3) .cg_move_view_to_top').removeClass('cg_hide');

        v = 0;

        $(this).closest('.cg_view').find( ".cg_options_order" ).each(function( i ) {
            v++;
            $(this).empty();
            $(this).append(v+'.');
            //$(this).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
            $(this).attr('id','cg_options_order'+v+'');
        });

        //var sortableViewIndex = sortableView.index()+1;
        //location.href = '#cg_options_order'+sortableViewIndex+'';
        //var scrollTop = $('#cg_options_order'+sortableViewIndex+'').offset().top-55-$('#cg_main_options_tab').outerHeight();
        //$(window).scrollTop(scrollTop-52);
        //  $(window).scrollTop(50);

    });

    $(document).on('click', '#cg_main_options .cg_move_view_to_bottom', function(e){
        //cgViewOptionCheck(this,e);

        var sortableView = $(this).closest('.cg_options_sortableContainer');
        sortableView.insertAfter(sortableView.next('.cg_options_sortableContainer'));
        //    $('.cg_options_sortableContainer:first-child .cg_move_view_to_bottom, .cg_options_sortableContainer:nth-child(2) .cg_move_view_to_bottom').removeClass('cg_hide');
        //   $('.cg_options_sortableContainer:nth-child(3) .cg_move_view_to_bottom').addClass('cg_hide');

        //   $('.cg_options_sortableContainer:first-child .cg_move_view_to_top').addClass('cg_hide');
        //   $('.cg_options_sortableContainer:nth-child(2) .cg_move_view_to_top').removeClass('cg_hide');
        //  $('.cg_options_sortableContainer:nth-child(3) .cg_move_view_to_top').removeClass('cg_hide');

        v = 0;

        $(this).closest('.cg_view').find( ".cg_options_order" ).each(function( i ) {
            v++;
            $(this).empty();
            $(this).append(v+'.');
            //$(this).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
            $(this).attr('id','cg_options_order'+v+'');
        });

        //var sortableViewIndex = sortableView.index()+1;
        //location.href = '#cg_options_order'+sortableViewIndex+'';
        //var scrollTop = $('#cg_options_order'+sortableViewIndex+'').offset().top-55-$('#cg_main_options_tab').outerHeight();
        //$(window).scrollTop(scrollTop-52);
        //  $(window).scrollTop(50);

    });

    // Visual form options here

    $(document).on('click', '#FormInputWidth', function(e){
        //cgViewOptionCheck(this,e);

        if($("#FormInputWidth").is( ":checked" )){
            $(".FormInputWidthExample").css("width","100%");
        }
        else{
            $(".FormInputWidthExample").css("width","auto");
        }

    });

    $(document).on('click', '#FormButtonWidth', function(e){
        //cgViewOptionCheck(this,e);

        if($("#FormButtonWidth").is( ":checked" )){
            $(".FormButtonWidthExample").css("width","100%");
        }
        else{
            $(".FormButtonWidthExample").css("width","auto");
        }

    });

    $(document).on('click', '#FormRoundBorder', function(e){
        //cgViewOptionCheck(this,e);

        if($("#FormRoundBorder").is( ":checked" )){
            $(".FormInputWidthExample").css("border-radius","5px");
            $(".FormButtonWidthExample").css("border-radius","5px");
        }
        else{
            $(".FormInputWidthExample").css("border-radius","0%");
            $(".FormButtonWidthExample").css("border-radius","0%");
        }

    });

    // Visual form options here --- END

    $(document).on('click', '#ThumbViewBorderColor', function(e){
        //cgViewOptionCheck(this,e);
        $(".color-picker").css("top",$("#ThumbViewBorderColor").offset().top+27);
    });
    $(document).on('click', '#HeightViewBorderColor', function(e){
        //cgViewOptionCheck(this,e);
        $(".color-picker").css("top",$("#HeightViewBorderColor").offset().top+27);
    });
    $(document).on('click', '#RowViewBorderColor', function(e){
        //cgViewOptionCheck(this,e);
        $(".color-picker").css("top",$("#RowViewBorderColor").offset().top+27);
    });
    $(document).on('click', '#GalleryBackgroundColor', function(e){
        //cgViewOptionCheck(this,e);
        $(".color-picker").css("top",$("#GalleryBackgroundColor").offset().top+27);
    });


    $( document ).on('change',"#ThumbViewBorderColor",function(e) {
        //cgViewOptionCheck(this,e);
        var opacityThumbView = $('#ThumbViewBorderColor').attr("data-opacity");
        $('#ThumbViewBorderColor').attr("name","ThumbViewBorderColor["+opacityThumbView+"]");
    });

    $( document ).on('change',"#HeightViewBorderColor",function(e) {
        //cgViewOptionCheck(this,e);
        var opacityHeightView = $('#HeightViewBorderColor').attr("data-opacity");
        $('#HeightViewBorderColor').attr("name","HeightViewBorderColor["+opacityHeightView+"]");
    });


    $( document ).on('change',"#RowViewBorderColor",function(e) {
        //cgViewOptionCheck(this,e);
        var opacityRowView = $('#RowViewBorderColor').attr("data-opacity");
        $('#RowViewBorderColor').attr("name","RowViewBorderColor["+opacityRowView+"]");
    });

    $( document ).on('change',"#GalleryBackgroundColor",function(e) {
        //cgViewOptionCheck(this,e);
        var opacityBackgroundColor = $('#GalleryBackgroundColor').attr("data-opacity");
        $('#GalleryBackgroundColor').attr("name","GalleryBackgroundColor["+opacityBackgroundColor+"]");
    });

/*    $( document ).on('change',"#cg_datepicker_start",function() {

        $( "#cg_datepicker_start" ).datepicker("option", "dateFormat", "yy-mm-dd");
    });*/

    $( document ).on('keydown',"#cg_datepicker_start",function() {
        return false;
    });

    /*  $( document ).on('change',"#cg_datepicker",function() {
        $( "#cg_datepicker" ).datepicker("option", "dateFormat", "yy-mm-dd");
     });*/

    $( document ).on('keydown',"#cg_datepicker",function() {
        return false;
    });

    $( document ).on( "input",".cg_date_hours,.cg_date_mins,.cg_date_seconds", function() {

        if(this.value.length>2){
            this.value = this.value.substr(0,2);
            if(this.value.indexOf(0)==0){
                this.value = this.value.substr(1,1);
            }
        }

        if($(this).hasClass('cg_date_hours')){
            if(this.value==25){
                this.value = 0;
            }
            if(this.value==-1){
                this.value = 24;
            }
        }

        if($(this).hasClass('cg_date_mins')){

            if(this.value==60){
                this.value = 0;
            }
            if(this.value==-1){
                this.value = 59;
            }
        }

        if(this.value<10){this.value = '0'+this.value;}

    });

    $( document ).on( "input", ".cg_date_hours_unlimited", function(e) {

        if(this.value==1000){
            this.value = 0;
        }
        if(this.value==-1){
            this.value = 999;
        }

        //if(this.value<10){this.value = '0'+this.value;}

        //	if(this.value<10){this.value = '0'+this.value;}

        if(this.value.length>3){
            this.value = this.value.substr(0,3);
            /*            if(this.value.indexOf(0)==0){
                            this.value = this.value.substr(1,2);
                        }*/
        }

    });

    $( ".cg_date_days" ).on( "input", function() {

        if(this.value==30){
            this.value = 0;
        }
        if(this.value==-1){
            this.value = 30;
        }
        //	if(this.value<10){this.value = '0'+this.value;}

    });

// Check votes in a time

    $(document).on('click',"#VotesInTimeContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_VotesInTime($);
    });


// Check votes in a time --- END

// Check if start contest time is on or not


    $(document).on('click',"#ContestStartContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_ContestStart($);
    });


// Check if start contest time is on or not --- END

// Check if end contest time is on or not



    $(document).on('click',"#ContestEndContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_ContestEnd($);
    });


// Check if end contest time is on or not --- END

// Check if end contest instant is on or not


    $(document).on('click',"#ContestEndInstantContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_ContestEndInstant($);
    });


// Check if end contest instant is on or not --- END

// Check if voting is activated or not

    $(document).on('click',"#AllowRatingContainer",function (e) {
        //cgViewOptionCheck(this,e);

        if($('#AllowRating2').prop( "checked" )){
            $("#AllowRating").prop( "checked", true);
            $("#AllowRating2").prop( "checked", false);
            $("#AllowRating2Container").find('.cg_view_option_checkbox').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
            $("#AllowRating3").removeClass( "cg_disabled");
        }

        if($('#AllowRating').prop( "checked" )){
            $("#AllowRating3").removeClass( "cg_disabled");
        }else{
            $("#AllowRating3").addClass( "cg_disabled");
        }

        cgJsClassAdmin.options.functions.cg_AllowRating($);

    });


    $(document).on('click',"#AllowRating2Container",function (e) {
        //cgViewOptionCheck(this,e);

        if($('#AllowRating').prop( "checked" )){
            $("#AllowRating2").prop( "checked", true);
            $("#AllowRating").prop( "checked", false);
            $("#AllowRatingContainer").find('.cg_view_option_checkbox').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
            $("#AllowRating3").addClass( "cg_disabled");
        }

        if($('#AllowRating').prop( "checked" )){
            $("#AllowRating3").removeClass( "cg_disabled");
        }else{
            $("#AllowRating3").addClass( "cg_disabled");
        }

        cgJsClassAdmin.options.functions.cg_AllowRating($);

    });


// Check if voting is activated or not --- END


// Check if facebook like button is activated or not

    $(document).on('click',"#FbLikeContainer",function (e) {
        return;
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_FbLike($);
    });

// Check if facebook like button is activated or not --- END


// Check FbLikeNoShare

    $(document).on('click',"#FbLikeNoShareContainer",function (e) {
        return;
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_FbLikeNoShare($,$(this).find('#FbLikeNoShare'));
    });

// Check FbLikeNoShare --- END


// Check FbLikeOnlyShare

    $(document).on('click',"#FbLikeOnlyShareContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_FbLikeOnlyShare($,$(this).find('#FbLikeOnlyShare'));
    });

// Check FbLikeOnlyShare --- END

// Check if commenting is activated or not

/*    $(document).on('click',"#AllowCommentsContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_AllowComments($);
    });*/

// Check if commenting is activated or not --- END


// Check preselect

    $(document).on('click',".RandomSortContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cgCheckPreselect($);
    });

// Check preselect --- END

// Show exif

    $(document).on('click', '.ShowExifContainer', function(e){
        cgJsClassAdmin.options.functions.cg_ShowExif(true,$,$(this));
    });

// Show exif --- END

// Check if slider is activated or not

    $(document).on('click',".AllowGalleryScriptContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_AllowGalleryScript(true,$,$(this).find('.AllowGalleryScript:not(.cg_shortcode_checkbox_clone)'));
    });

    $(document).on('click',"#cgBackendGalleryDynamicMessage .AllowGalleryScriptContainer",function ( ) {
        cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();
        var $view = $('.cg_view.cgSinglePicOptions:not(.cg_hide)');
        var $AllowGalleryScriptContainer = $view.find('.AllowGalleryScriptContainer');
        var $element  = $AllowGalleryScriptContainer;
        $AllowGalleryScriptContainer.click();
        // have to be done additionally in this case after click
        cgJsClassAdmin.options.functions.cg_AllowGalleryScript(true,$,$element.find('.AllowGalleryScript:not(.cg_shortcode_checkbox_clone)'));
        $AllowGalleryScriptContainer.get(0).scrollIntoView();
        // has to be done extra
        $view.find('.FullSizeImageOutGallery').prop('checked',false).closest('.cg_view_option_radio').removeClass('cg_view_option_checked');
        $view.find('.OnlyGalleryView').prop('checked',false).closest('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
    });

// Check if slider is activated or not --- END


// Check if SliderFullWindow is activated or not

    $(document).on('click',".SliderFullWindowContainer",function (e) {
        //cgViewOptionCheck(this,e);
        if(!$(this).closest('#cgBackendGalleryDynamicMessage').length){
            cgJsClassAdmin.options.functions.cg_SliderFullWindow(true,$,$(this).find('.SliderFullWindow:not(.cg_shortcode_checkbox_clone)'));
        }
    });

    $(document).on('click',"#cgBackendGalleryDynamicMessage .SliderFullWindowContainer",function ( ) {
        cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();
        var $view = $('.cg_view.cgSinglePicOptions:not(.cg_hide)');
        var $SliderFullWindowContainer = $view.find('.SliderFullWindowContainer');
        var $element  = $SliderFullWindowContainer;
        $SliderFullWindowContainer.click();
        // have to be done additionally in this case after click
        cgJsClassAdmin.options.functions.cg_SliderFullWindow(true,$,$element.find('.SliderFullWindow:not(.cg_shortcode_checkbox_clone)'));
        $SliderFullWindowContainer.get(0).scrollIntoView();
        // has to be done extra
        $view.find('.FullSizeImageOutGallery').prop('checked',false).closest('.cg_view_option_radio').removeClass('cg_view_option_checked');
        $view.find('.OnlyGalleryView').prop('checked',false).closest('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
    });

// Check if SliderFullWindow is activated or not --- END

// Check if BlogLookFullWindow is activated or not

    $(document).on('click',".BlogLookFullWindowContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_BlogLookFullWindow(true,$,$(this).find('.BlogLookFullWindow:not(.cg_shortcode_checkbox_clone)'));
    });

    $(document).on('click',"#cgBackendGalleryDynamicMessage .BlogLookFullWindowContainer",function ( ) {
        cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();
        var $view = $('.cg_view.cgSinglePicOptions:not(.cg_hide)');
        var $BlogLookFullWindowContainer = $view.find('.BlogLookFullWindowContainer');
        var $element  = $BlogLookFullWindowContainer;
        $BlogLookFullWindowContainer.click();
        // have to be done additionally in this case after click
        cgJsClassAdmin.options.functions.cg_BlogLookFullWindow(true,$,$element.find('.BlogLookFullWindow:not(.cg_shortcode_checkbox_clone)'));
        $BlogLookFullWindowContainer.get(0).scrollIntoView();
        // has to be done extra
        $view.find('.FullSizeImageOutGallery').prop('checked',false).closest('.cg_view_option_radio').removeClass('cg_view_option_checked');
        $view.find('.OnlyGalleryView').prop('checked',false).closest('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
    });

// Check if BlogLookFullWindow is activated or not --- END

// Check if BlogLookFullWindow is activated or not

    $(document).on('click',".ForwardToWpPageEntryContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_ForwardToWpPageEntry(true,$,$(this).find('.ForwardToWpPageEntry:not(.cg_shortcode_checkbox_clone)'));
    });

    $(document).on('click',"#cgBackendGalleryDynamicMessage .ForwardToWpPageEntryContainer",function ( ) {
        cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();
        var $view = $('.cg_view.cgSinglePicOptions:not(.cg_hide)');
        var $ForwardToWpPageEntryContainer = $view.find('.ForwardToWpPageEntryContainer');
        var $element  = $ForwardToWpPageEntryContainer;
        $ForwardToWpPageEntryContainer.click();
        // have to be done additionally in this case after click
        cgJsClassAdmin.options.functions.cg_ForwardToWpPageEntry(true,$,$element.find('.ForwardToWpPageEntry:not(.cg_shortcode_checkbox_clone)'));
        $ForwardToWpPageEntryContainer.get(0).scrollIntoView();
        // has to be done extra
        $view.find('.FullSizeImageOutGallery').prop('checked',false).closest('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');;
        $view.find('.OnlyGalleryView').prop('checked',false).closest('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
    });

// Check if BlogLookFullWindow is activated or not --- END

// Check if Full Size Image is activated or not

    $(document).on('click',".FullSizeImageOutGalleryContainer",function (e) {

        if($(this).closest('.cg_view').hasClass('cg_FullSizeImageOutGallery_checked')){

            var cgGalleryDbVersion = parseInt($('#cgGalleryDbVersion').val());
            var customPostTypeHide = '';
            if(cgGalleryDbVersion<21){
                customPostTypeHide = 'cg_hide';
            }

            // find ForwardToWpPageEntryContainer
            var $message = $("" +
                "<div class='cg_main_options' id='cgMessageViewSelect' >\n" +
                "   <div class='cg_view_options_rows_container'>\n" +
                "        <p class='cg_view_options_rows_container_title' style='font-size: 26px;'>Select another view</p>\n" +
                "     </div>" +
                " </div>");

            $message.append(cgJsClassAdmin.options.vars.cg_view_options_row_open_file_image_style);
            $message.find('.ForwardToWpPageEntryContainer').addClass(customPostTypeHide);

            var cg_view_options_row_OnlyGalleryView = '<div class="cg_view_options_rows_container">\n' +
                '<p class="cg_view_options_rows_container_title">Only gallery view</p>\n' +
                '<div class="cg_view_options_row">\n' +
                '            <div class="cg_view_option cg_view_option_100_percent OnlyGalleryViewContainer cg_border_radius_8_px  ">\n' +
                '                <div class="cg_view_option_title">\n' +
                '                    <p>Make entries unclickable<br>Good for displaying entries only<br><span class="cg_view_option_title_note">Files images can not be clicked. Configuration of voting out of gallery is possible. Only for gallery views. Slider and blog view will work as usual.</span></p>\n' +
                '                </div>\n' +
                '                <div class="cg_view_option_radio cg_margin_top_5 cg_view_option_unchecked">\n' +
                '                    <input type="radio" name="OnlyGalleryView" class="OnlyGalleryView">\n' +
                '                </div>\n' +
                '            </div>\n' +
                '       </div>\n' +
                ' </div>';

            $message.append(cg_view_options_row_OnlyGalleryView);

            cgJsClassAdmin.gallery.functions.setAndAppearBackendGalleryDynamicMessage($message,undefined,'cg_backend_action_container');

        }else{
            cgJsClassAdmin.options.functions.cg_FullSizeImageOutGallery(true,$,$(this).find('.FullSizeImageOutGallery:not(.cg_shortcode_checkbox_clone)'));
        }

    });

    $(document).on('click',"#cgBackendGalleryDynamicMessage .FullSizeImageOutGalleryContainer",function ( ) {
        var $view = $('.cg_view.cgSinglePicOptions:not(.cg_hide)');
        cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();
        var $FullSizeImageOutGalleryContainer = $view.find('.FullSizeImageOutGalleryContainer');
        var $element  = $FullSizeImageOutGalleryContainer;
        $FullSizeImageOutGalleryContainer.click();
        // have to be done additionally in this case after click
        cgJsClassAdmin.options.functions.cg_FullSizeImageOutGallery(true,$,$element.find('.FullSizeImageOutGallery:not(.cg_shortcode_checkbox_clone)'));
        $FullSizeImageOutGalleryContainer.get(0).scrollIntoView();
        // has to be done extra
        $view.find('.OnlyGalleryView').prop('checked',false).closest('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
    });

// Check if Full Size Image is activated or not --- END

// Check if full screen can be enabled

/*    $(document).on('click',".FullSizeGallery",function () {
        cgJsClassAdmin.options.functions.cg_CheckFullSize($,$(this));
    });*/


// Check if full screen can be enabled --- END

// Check if only gallery view is activated or not

    $(document).on('click',".OnlyGalleryViewContainer",function (e) {


        if($(this).closest('.cg_view').hasClass('cg_OnlyGalleryView_checked')){

            var cgGalleryDbVersion = parseInt($('#cgGalleryDbVersion').val());
            var customPostTypeHide = '';
            if(cgGalleryDbVersion<21){
                customPostTypeHide = 'cg_hide';
            }

            var $message = $("" +
                "<div class='cg_main_options' id='cgMessageViewSelect' >\n" +
                "   <div class='cg_view_options_rows_container'>\n" +
                "        <p class='cg_view_options_rows_container_title' style='font-size: 26px;'>Select another view</p>\n" +
                "     </div>" +
                " </div>");

            $message.append(cgJsClassAdmin.options.vars.cg_view_options_row_open_file_image_style);
            $message.find('.ForwardToWpPageEntryContainer').addClass(customPostTypeHide);

            var cg_view_options_row_OnlyGalleryView = '<div class="cg_view_options_rows_container">\n' +
                '<p class="cg_view_options_rows_container_title">Original source link only</p>\n' +
            '<div class="cg_view_options_row">\n' +
                '<div class="cg_view_option cg_view_option_100_percent FullSizeImageOutGalleryContainer   cg_border_radius_8_px">\n' +
                '                <div class="cg_view_option_title">\n' +
                '                    <p>Forward directly to original source after clicking an entry from in a gallery<br><span class="cg_view_option_title_note">Configuration of voting out of gallery is possible. Only for gallery views. Slider and blog view will work as usual.</span></p>\n' +
                '                </div>\n' +
                '                <div class="cg_view_option_radio cg_margin_top_5 cg_view_option_unchecked">\n' +
                '                    <input type="radio" name="FullSizeImageOutGallery" class="FullSizeImageOutGallery">\n' +
                '                </div>\n' +
                '            </div>\n' +
                '       </div>\n' +
                ' </div>';

            $message.append(cg_view_options_row_OnlyGalleryView);

            cgJsClassAdmin.gallery.functions.setAndAppearBackendGalleryDynamicMessage($message,undefined,'cg_backend_action_container');

        }else{
            cgJsClassAdmin.options.functions.cg_OnlyGalleryView(true,$,$(this).find('.OnlyGalleryView:not(.cg_shortcode_checkbox_clone)'));
        }
    });

    $(document).on('click',"#cgBackendGalleryDynamicMessage .OnlyGalleryViewContainer",function ( ) {
        var $view = $('.cg_view.cgSinglePicOptions:not(.cg_hide)');
        cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();
        var $OnlyGalleryViewContainer = $view.find('.OnlyGalleryViewContainer');
        var $element  = $OnlyGalleryViewContainer;
        $OnlyGalleryViewContainer.click();
        // have to be done additionally in this case after click
        cgJsClassAdmin.options.functions.cg_OnlyGalleryView(true,$,$element.find('.OnlyGalleryView:not(.cg_shortcode_checkbox_clone)'));
        $OnlyGalleryViewContainer.get(0).scrollIntoView();
        // has to be done extra
        $view.find('.FullSizeImageOutGallery').prop('checked',false).closest('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
    });

// Check if only gallery view is activated or not --- END

// Check if resolution restricted or not

    $(document).on('click',".cg-allow-res",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_MaxResCheck($,$(this).find('.cg-allow-res-checkbox'));
    });

// Check if resolution restricted or not --- END


// Allow only to press numbers as keys in input boxes


    $(document).on('keypress',"#ScaleSizesGalery1, #ScaleSizesGalery2, #DistancePicsV, #DistancePicsV, #PicsInRow, #PicsPerSite,#ThumbViewBorderRadius,#RowViewBorderRadius,#HeightViewBorderRadius,#HeightViewSpaceHeight,#WidthThumb,"+
        "#PostMaxMB, #PostMaxMBfile, #VotesPerUser, #VotesPerCategory, #RegUserMaxUpload, #VotesInTimeQuantity, #BulkUploadQuantity,#BulkUploadMinQuantity, #DistancePics, #MaxResJPGwidth, #MaxResJPGheight, #MaxResPNGwidth, #MaxResPNGheight, #MaxResGIFwidth, #MaxResGIFheight, #MaxResICOwidth, #MaxResICOheight, #cg_row_look_border_width,#cg_height_look_border_width,#HeightViewBorderWidth,.HeightLookHeight",function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            // $("#cg_options_errmsg").html("Only numbers are allowed").show().fadeOut("slow");
            return false;
        }
    });

// Allow only to press numbers as keys in input boxes --- END

// Click input checkboxes

    // Check gallery
    $(document).on('click',"#ScaleSizesGalery",function(e){
        //cgViewOptionCheck(this,e);

        if($("#ScaleSizesGalery").is( ":checked" )){

            $("#ScaleWidthGalery").prop("checked",false);
            $( "#ScaleSizesGalery1" ).attr("disabled",false);
            $( "#ScaleSizesGalery2" ).attr("disabled",false);
            $( "#ScaleSizesGalery1" ).css({ 'background': '#ffffff' });
            $( "#ScaleSizesGalery2" ).css({ 'background': '#ffffff' });

        }

        else{

            $("#ScaleWidthGalery").prop("disabled",false);
            $( "#ScaleSizesGalery1" ).attr("disabled",true);
            $( "#ScaleSizesGalery2" ).attr("disabled",true);
            $( "#ScaleSizesGalery1" ).css({ 'background': '#e0e0e0' });
            $( "#ScaleSizesGalery2" ).css({ 'background': '#e0e0e0' });

            if($("#ScaleWidthGalery").is( ":checked" )){}
            else{
                $( "#ScaleWidthGalery" ).prop("checked",true);
                $( "#ScaleSizesGalery1" ).attr("disabled",false);
                $( "#ScaleSizesGalery1" ).css({ 'background': '#ffffff' });
            }

        }

    });

    $(document).on('click',"#ScaleWidthGalery",function(e){
        //cgViewOptionCheck(this,e);

        if($("#ScaleWidthGalery").is( ":checked" )){
            return;
            $("#ScaleSizesGalery").prop("checked",false);
            $("#ScaleSizesGalery1").prop("disabled",false);
            $("#ScaleSizesGalery2").prop("disabled",true);
            $( "#ScaleSizesGalery1" ).css({ 'background': '#ffffff' });
            $( "#ScaleSizesGalery2" ).css({ 'background': '#e0e0e0' });


        }

        else{
            $("#ScaleWidthGalery").prop("checked",true);


            return;

            $( "#ScaleSizesGalery" ).prop("checked",true);
            $("#ScaleSizesGalery").prop("disabled",false);
            $("#ScaleSizesGalery1").prop("disabled",false);
            $("#ScaleSizesGalery2").prop("disabled",false);
            $( "#ScaleSizesGalery2" ).css({ 'background': '#ffffff' });
            $( "#ScaleSizesGalery1" ).css({ 'background': '#ffffff' });

        }

    });

    // Check gallery END



// Check upload size

    $(document).on('click',"#ActivatePostMaxMBContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_ActivatePostMaxMB($);
    });

    $(document).on('click',"#ActivatePostMaxMBfileContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_ActivatePostMaxMBfile($);
    });

    $(document).on('click',"#ActivateBulkUploadContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_ActivateBulkUpload($,$(this),true);
    });

// Check upload size --- END

// Check resolution

//JPG

    $(document).on('click',"#AllowUploadJPGContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_allowUploadJPG($);
    });


//PNG

    $(document).on('click',"#AllowUploadPNGContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_allowUploadPNG($);
    });


//GIF
    $(document).on('click',"#AllowUploadGIFContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_allowUploadGIF($);
    });

//ICO
    $(document).on('click',"#AllowUploadICOContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_allowUploadICO($);
    });
//JPG


    $(document).on('click',"#MaxResJPGonContainer,#MinResJPGonContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_allowResJPG($);
    });


//PNG

    $(document).on('click',"#MaxResPNGonContainer,#MinResPNGonContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_allowResPNG($);

    });


//GIF

    $(document).on('click',"#MaxResGIFonContainer,#MinResGIFonContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_allowResGIF($);
    });

//ICO

    $(document).on('click',"#MaxResICOonContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_allowResICO($);
    });

// Check resolution END

// Click input checkboxes END

// Check Background color
    $(document).on('click',"#ActivateGalleryBackgroundColor",function(e){
        //cgViewOptionCheck(this,e);
        if($(this).is(":checked")){

            $("#GalleryBackgroundColor").attr("disabled",false);
            $("#GalleryBackgroundColor").css({ 'background': '#ffffff' });

        }

        else{

            $("#GalleryBackgroundColor").attr("disabled",true);
            $("#GalleryBackgroundColor").css({ 'background': '#e0e0e0' });

        }

    });

// Check Background color --- ENDE

    // this one has to be at the top of view option click events
    $(document).on('click',".cg_view_options_and_order_checkbox_container",function(e){
        debugger
        if(!$(this).closest('.cg_view').find('.cg_view_options_and_order_checkbox.cg_view_option_checked').length && !$(this).find('.cg_view_options_and_order_checkbox').prop('checked')){
            cgJsClassAdmin.gallery.functions.setAndAppearBackendGalleryDynamicMessage('At least one view option has to be selected');
        }
    });

    $(document).on('click',".HeightLookContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_HeightLook(true,$,$(this).find('.HeightLook'));

    });

    $(document).on('click',".ThumbLookContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_ThumbLook(true,$,$(this).find('.ThumbLook'));

    });


    $(document).on('click',".RowLookContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_RowLook(true,$,$(this).find('.RowLook'));

    });


    $(document).on('click',".SliderLookContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_SliderLook(true,$,$(this).find('.SliderLook'));

    });

    $(document).on('click',".BlogLookContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_BlogLook(true,$,$(this).find('.BlogLook'));
    });


// Check if Height Look fields are checked or not

// Check if Height Look fields are checked or not --- ENDE


// Check if Row Fields are checked or not

// Check if Row Fields are checked or not  --- END


// Activate in gallery upload form

    /*    $(document).on('click',"#GalleryUpload",function(){

            cgJsClassAdmin.options.functions.checkInGalleryUpload($);

        });*/



// Activate in gallery upload form --- END



// Check if forward upload fields are checked or not

    $(document).on('click',"#cg_confirm_textContainer",function(e){
        cgJsClassAdmin.options.functions.cg_confirm_after_upload($);
    });

    $(document).on('click',"#forwardContainer",function(e){
        cgJsClassAdmin.options.functions.cg_forward_after_upload($);
    });

// checkAfterContactSubmit

    $(document).on('click',"#ConOptForwardAfterContactActiveContainer",function(e){
        var $ConOptShowConfirmTextAfterContactActiveContainer = $('#ConOptShowConfirmTextAfterContactActiveContainer');
        $ConOptShowConfirmTextAfterContactActiveContainer.find('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
        $ConOptShowConfirmTextAfterContactActiveContainer.find('#ConOptShowConfirmTextAfterContactActive').prop('checked',false);
        cgJsClassAdmin.options.functions.checkAfterContactSubmit($);
    });

    $(document).on('click',"#ConOptShowConfirmTextAfterContactActiveContainer",function(e){
        var $ConOptForwardAfterContactActiveContainer = $('#ConOptForwardAfterContactActiveContainer');
        $ConOptForwardAfterContactActiveContainer.find('.cg_view_option_radio').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
        $ConOptForwardAfterContactActiveContainer.find('#ConOptForwardAfterContactActive').prop('checked',false);
        cgJsClassAdmin.options.functions.checkAfterContactSubmit($);
    });

// Check if forward upload fields are checked or not  --- END

    // Check if send InformAdminContainer checked or not

    $(document).on('click',"#InformAdminContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_inform_admin_after_upload($);

    });

    // Check if send comment notification checked or not

    $(document).on('click',"#CommNoteActiveContainer",function(e){
        cgJsClassAdmin.options.functions.cg_CommNoteActive($);
    });

// Check if forward login fields are checked or not

    $(document).on('click',"#ForwardAfterLoginUrlCheckContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_after_login($);

    });

    $(document).on('click',"#ForwardAfterLoginTextCheckContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_after_confirm_text($);

    });


// Check if forward login fields are checked or not  --- END


// Check mail confirm email

    $(document).on('click',"#mConfirmSendConfirmContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_mail_confirm_email($);
    });

// Check mail confirm email --- ENDE

// Check image activation email

    $(document).on('click',"#InformUsersContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_image_activation_email($);
    });

// Check image activation email --- ENDE

// Check contact entry user email
    $(document).on('click',"#ConOptInformUserActiveContainer",function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_contact_entry_user_email($);
    });
// Check contact entry user email --- ENDE

// Check show text instead of upload form or not

/*    var cgRegUserUploadOnly = function(){

        if($('#RegUserUploadOnly').is(":checked")){

           // $( "#wp-RegUserUploadOnlyText-wrap" ).removeClass("cg_disabled");
            $( "#RegUserMaxUpload" ).removeClass("cg_disabled");
            $( "#CheckIpUpload" ).removeClass("cg_disabled");
            $( "#CheckCookieUpload" ).removeClass("cg_disabled");
            $( "#CheckLoginUpload" ).removeClass("cg_disabled");
            $( "#UploadRequiresCookieMessage" ).removeClass("cg_disabled");

        }
        else{

            $( "#wp-RegUserUploadOnlyText-wrap" ).addClass("cg_disabled");
            $( "#RegUserMaxUpload" ).addClass("cg_disabled");
            $( "#CheckIpUpload" ).addClass("cg_disabled");
            $( "#CheckCookieUpload" ).addClass("cg_disabled");
            $( "#CheckLoginUpload" ).addClass("cg_disabled");
            $( "#UploadRequiresCookieMessage" ).addClass("cg_disabled");

        }

    };

    $("#RegUserUploadOnly").click(function() {

        cgRegUserUploadOnly();

    });

    cgRegUserUploadOnly();*/

    $(document).on('click',".CheckMethodUploadContainer",function(e){
        cgJsClassAdmin.options.functions.cg_user_recognising_method_upload($,$(this).find('.CheckMethodUpload'));

    });

    $(document).on('click', '.RegUserGalleryOnlyContainer', function(e){
        cgJsClassAdmin.options.functions.cgRegUserGalleryOnly($,$(this).closest('.cg_view_container'));
    });

    $(document).on('click', '.CheckMethodContainer', function(e){
        cgJsClassAdmin.options.functions.cg_user_reocgnising_method($,$(this).find('.CheckMethod'));
    });

    // reset votes confirm
    $(document).on('click', '#cg_reset_votes2', function(e){

        var confirmText = $('#cg_reset_votes2_explanation').html().trim();
        confirmText = confirmText.split("<br>").join("\r\n");

        if (confirm(confirmText)) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }

    });

    $(document).on('click', '#cg_reset_votes', function(e){

        //cgViewOptionCheck(this,e);

        var confirmText = $('#cg_reset_votes_explanation').html().trim();
        confirmText = confirmText.split("<br>").join("\r\n");

        if (confirm(confirmText)) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }

    });

    $(document).on('click', '#cg_reset_users_votes2', function(e){

        //cgViewOptionCheck(this,e);

        var confirmText = $('#cg_reset_users_votes2_explanation').html().trim();
        confirmText = confirmText.split("<br>").join("\r\n");

        if (confirm(confirmText)) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }

    });

    $(document).on('click', '#cg_reset_users_votes', function(e){

        //cgViewOptionCheck(this,e);

        var confirmText = $('#cg_reset_users_votes_explanation').html().trim();
        confirmText = confirmText.split("<br>").join("\r\n");

        if (confirm(confirmText)) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }

    });

    $(document).on('click', '#cg_reset_admin_votes', function(e){

        //cgViewOptionCheck(this,e);

        var confirmText = $('#cg_reset_admin_votes_explanation').html().trim();
        confirmText = confirmText.split("<br>").join("\r\n");

        if (confirm(confirmText)) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }

    });

    $(document).on('click', '#cg_reset_admin_votes2', function(e){

        //cgViewOptionCheck(this,e);

        var confirmText = $('#cg_reset_admin_votes2_explanation').html().trim();
        confirmText = confirmText.split("<br>").join("\r\n");

        if (confirm(confirmText)) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }

    });

    $(document).on('click', '#HideRegFormAfterLoginContainer', function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_HideRegFormAfterLogin($);
    });

    $(document).on('click', '#HideRegFormAfterLoginShowTextInsteadContainer', function(e){
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_HideRegFormAfterLoginShowTextInstead($);
    });

    /*Tab actions*/

    $(document).on('click','#cgGoTopOptions',function (e) {
        //cgViewOptionCheck(this,e);
        $(window).scrollTop(0);
    });

    $(document).on('click','#cgSaveOptionsNavButton',function (e) {
        //cgViewOptionCheck(this,e);
        $('#cgSaveOptionsButton').click();
    });

    $(document).on('click','#cg_main_options_tab .cg_view_select',function (e) {

        var viewId = $(this).find('.cg_view_select_link').attr('cg-data-view');
        var $view = $(viewId);
        var viewOffsetTop = $view.offset().top;
        var heightWpadminbar = $('#wpadminbar').height();
        var height_cg_main_options_tab = $('#cg_main_options_tab').height();
        var $cg_main_options_tab = $('#cg_main_options_tab');

        if($cg_main_options_tab.hasClass('cg_sticky')){
            var totalOffset = viewOffsetTop-heightWpadminbar-height_cg_main_options_tab-10;
        }else{
            var totalOffset = viewOffsetTop-heightWpadminbar-height_cg_main_options_tab-10-$cg_main_options_tab.outerHeight();
        }

        var $cg_main_options_content = $('#cg_main_options_content');
        $cg_main_options_tab.find('.cg_view_select').removeClass('cg_selected');
        $cg_main_options_content.find('.cg_view_header').removeClass('cg_selected');
        $cg_main_options_tab.addClass('cg_sticky').width(cgJsClassAdmin.index.vars.$cg_main_container.width()-2);
        $(window).scrollTop(totalOffset);
        var $element = $(this);
        $element.addClass('cg_selected');
        setTimeout(function () {
            $cg_main_options_tab.find('.cg_view_select').removeClass('cg_selected');
            $element.addClass('cg_selected');
        },10);


        //var $viewHelper = $('<div id="'+viewHelper+'" class="cg_view_helper"></div>');
        //var $viewHelper = $viewHelper.css('margin-bottom',totalHeight+'px');
        //   $viewHelper.insertBefore($cg_main_options_content.find(view));
        // $cg_main_options_tab.addClass('cg_sticky');
        // document.getElementById(viewHelper).scrollIntoView();
        // setTimeout(function () {
        // $viewHelper.remove(); will be removed on scroll
        //  },10);
    });

    var isCgUploadFieldsSelectSet = false;
    var isCgRegistryFieldsSelectSet = false;

    $( window ).scroll(function() {

        var windowHeight = cgJsClassAdmin.index.vars.windowHeight;
        var windowScrollTop = $(window).scrollTop();

        if(cgJsClassAdmin.index.vars.$cgGoTopOptions){
            if(windowScrollTop>=windowHeight){//then Downscroll
                var marginRight = parseInt(cgJsClassAdmin.index.vars.$cg_main_container.css('margin-right'));
                if(marginRight>=0){
                    cgJsClassAdmin.index.vars.$cgGoTopOptions.css('right',(marginRight+10)+'px');
                }else{
                    cgJsClassAdmin.index.vars.$cgGoTopOptions.css('right',10+'px');
                }
                cgJsClassAdmin.index.vars.$cgGoTopOptions.removeClass('cg_hide');
            }else{
                cgJsClassAdmin.index.vars.$cgGoTopOptions.addClass('cg_hide');
            }
        }

        var $wpadminbar = cgJsClassAdmin.options.vars.$wpadminbar;

        if(cgJsClassAdmin.index.vars.isCreateUploadAreaLoaded){
            var $cgUploadFieldsSelect = cgJsClassAdmin.options.vars.$cgUploadFieldsSelect;
            var windowScrollTop = $(window).scrollTop();

            // console.log('windowScrollTop');
            // console.log(windowScrollTop);

            if((windowScrollTop+50)>cgJsClassAdmin.options.vars.cg_create_upload_container_offset){
                isCgUploadFieldsSelectSet = true;
                //$cgUploadFieldsSelect.addClass('cg_sticky').css({'border-right':'unset','top':cgJsClassAdmin.options.vars.wpadminbarHeight+'px','width':(cgJsClassAdmin.index.vars.$cg_main_container.width()-1)+'px'});
                $cgUploadFieldsSelect.addClass('cg_sticky').css({'top':cgJsClassAdmin.options.vars.wpadminbarHeight+'px','width':(cgJsClassAdmin.index.vars.$cg_main_container.width())+'px'});
            }else{
                //$cgUploadFieldsSelect.removeClass('cg_sticky').css({'top':'','border-right':''} );
                $cgUploadFieldsSelect.removeClass('cg_sticky');
                isCgUploadFieldsSelectSet = false;
            }
            return;
        }

        if(cgJsClassAdmin.index.vars.isCreateRegistryAreaLoaded){
            var $cgRegFormSelect = cgJsClassAdmin.options.vars.$cgRegFormSelect;
            var windowScrollTop = $(window).scrollTop();
            if((windowScrollTop+50)>cgJsClassAdmin.options.vars.cg_registry_form_container_offset){
                isCgRegistryFieldsSelectSet = true;
                //$cgRegFormSelect.addClass('cg_sticky').css({'border-right':'unset','top':cgJsClassAdmin.options.vars.wpadminbarHeight+'px','width':(cgJsClassAdmin.index.vars.$cg_main_container.width()-1)+'px'});
                $cgRegFormSelect.addClass('cg_sticky').css({'top':cgJsClassAdmin.options.vars.wpadminbarHeight+'px','width':(cgJsClassAdmin.index.vars.$cg_main_container.width()+2)+'px'});
            }else{
                //$cgRegFormSelect.removeClass('cg_sticky').css({'top':'','border-right':''} );
                $cgRegFormSelect.removeClass('cg_sticky').css({'top':''} );
                isCgRegistryFieldsSelectSet = false;
            }
            return;
        }

        var $cg_main_options_tab = cgJsClassAdmin.options.vars.$cg_main_options_tab;
        var $cgMainMenuMainTable = cgJsClassAdmin.mainMenu.vars.$cgMainMenuMainTable;
        var $cg_main_options = cgJsClassAdmin.options.vars.$cg_main_options;
        var $cg_main_options_content = cgJsClassAdmin.options.vars.$cg_main_options_content;
        var $cg_view_select_objects = cgJsClassAdmin.options.vars.$cg_view_select_objects;
        var lastScrollTop = cgJsClassAdmin.options.vars.lastScrollTop;
        var clickTime = cgJsClassAdmin.options.vars.clickTime;
        var cg_short_code_multiple_pics_configuration_cg_sticky_removed = false;
        var cg_short_code_single_pic_configuration_buttons_cg_sticky_removed = false;

        if(cgJsClassAdmin.index.vars.isOptionsAreaLoaded){
            var windowHeight = cgJsClassAdmin.index.vars.windowHeight;
            var height_cg_main_options_tab = $cg_main_options_tab.height();
            var heightWpadminbar = $wpadminbar.height();
            var windowScrollTop = $(window).scrollTop();
            var view2_offset_top = $cg_main_options.find('#view2').offset().top;
            var view3_offset_top = $cg_main_options.find('#view3').offset().top;
        }


        if(!$cg_main_options_tab || !$cg_main_options_tab.length){
            return;
        }

        if(windowScrollTop>=$cg_main_options_content.offset().top && windowScrollTop>=lastScrollTop){//then Downscroll
            $cg_main_options_tab.addClass('cg_sticky').css({'top':heightWpadminbar+'px','border-right':'unset'}).width(cgJsClassAdmin.index.vars.$cg_main_container.width()-2);
        }

        if(windowScrollTop<$cg_main_options_content.offset().top && windowScrollTop<lastScrollTop){//then Downscroll
            $cg_main_options_tab.removeClass('cg_sticky').removeAttr('style');
            $cg_main_options.find('.cg_short_code_multiple_pics_configuration_buttons').removeClass('cg_sticky');
            $cg_main_options.find('.cg_short_code_single_pic_configuration_buttons').removeClass('cg_sticky');
        }

        if(windowScrollTop>view2_offset_top-400){
            $cg_main_options.find('.cg_short_code_multiple_pics_configuration_buttons').removeClass('cg_sticky').addClass('cg_hide');
            cg_short_code_multiple_pics_configuration_cg_sticky_removed = true;
        }

        if(windowScrollTop<=view2_offset_top-500){
            $cg_main_options.find('.cg_short_code_multiple_pics_configuration_buttons').removeClass('cg_hide');
        }

        if(windowScrollTop<view2_offset_top){
            $cg_main_options.find('.cg_short_code_single_pic_configuration_buttons').removeClass('cg_sticky');
            cg_short_code_single_pic_configuration_buttons_cg_sticky_removed = true;
        }

        // before view 3 (Gallery options) slithly hide sticky
        if(windowScrollTop>view3_offset_top-300){
            $cg_main_options.find('.cg_short_code_single_pic_configuration_buttons').removeClass('cg_sticky').addClass('cg_hide');
            cg_short_code_single_pic_configuration_buttons_cg_sticky_removed = true;
        }

        if(windowScrollTop<=view3_offset_top-400){
            $cg_main_options.find('.cg_short_code_single_pic_configuration_buttons').removeClass('cg_hide');
        }

        $cg_view_select_objects.each(function () {

            var $cg_view_select = $(this);
            var $viewHeader = $cg_main_options_content.find($cg_view_select.attr('cg-data-view'));
            var viewCount = parseInt($cg_view_select.attr('data-count'));
            var $viewDiv = $cg_main_options.find('.cgViewHelper'+viewCount+':visible').first();

            if(!$viewHeader.length){
                return;
            }
            if(!$viewDiv.length){
                return;
            }

            var cg_view_offsetTop = $viewHeader.offset().top;
            var elementPositionRelatedToWindow = cg_view_offsetTop-windowScrollTop+$viewHeader.outerHeight()+$viewDiv.outerHeight()-height_cg_main_options_tab-heightWpadminbar;
            if(elementPositionRelatedToWindow > 0 && windowScrollTop>=lastScrollTop){//then Downscroll
                var scrollTime = new Date().getTime()-1000;// if scroll time was later then 1 second of click time
                if(scrollTime>clickTime){
                    $cg_view_select_objects.removeClass('cg_selected');
                    $cg_view_select.addClass('cg_selected');

                    if($cg_main_options_tab.hasClass('cg_sticky')){
                        if(viewCount==1){
                            if(!cg_short_code_multiple_pics_configuration_cg_sticky_removed){
                                $cg_main_options.find('.cg_short_code_multiple_pics_configuration_buttons').addClass('cg_sticky').width(cgJsClassAdmin.index.vars.$cg_main_container.width()-2);
                            }
                            $cg_main_options.find('.cg_short_code_single_pic_configuration_buttons').removeClass('cg_sticky');
                        }else if(viewCount==2){
                            $cg_main_options.find('.cg_short_code_multiple_pics_configuration_buttons').removeClass('cg_sticky');
                            if(!cg_short_code_single_pic_configuration_buttons_cg_sticky_removed){
                                $cg_main_options.find('.cg_short_code_single_pic_configuration_buttons').addClass('cg_sticky').width(cgJsClassAdmin.index.vars.$cg_main_container.width()-2);
                            }
                        }else{
                            $cg_main_options.find('.cg_short_code_multiple_pics_configuration_buttons').removeClass('cg_sticky');
                            $cg_main_options.find('.cg_short_code_single_pic_configuration_buttons').removeClass('cg_sticky');
                        }
                    }
                }
                return false;
            }
            var heightCheck = windowHeight/4;
            if(elementPositionRelatedToWindow > heightCheck && windowScrollTop<lastScrollTop){//then Upscroll
                var scrollTime = new Date().getTime()-1000;// if scroll time was later then 1 second of click time
                if(scrollTime>clickTime){
                    $cg_view_select_objects.removeClass('cg_selected');
                    $cg_view_select.addClass('cg_selected');

                    if($cg_main_options_tab.hasClass('cg_sticky')){
                        if(viewCount==1){
                            if(!cg_short_code_multiple_pics_configuration_cg_sticky_removed){
                                $cg_main_options.find('.cg_short_code_multiple_pics_configuration_buttons').addClass('cg_sticky').width(cgJsClassAdmin.index.vars.$cg_main_container.width()-2);
                            }
                            $cg_main_options.find('.cg_short_code_single_pic_configuration_buttons').removeClass('cg_sticky');
                        }else if(viewCount==2){
                            $cg_main_options.find('.cg_short_code_multiple_pics_configuration_buttons').removeClass('cg_sticky');
                            if(!cg_short_code_single_pic_configuration_buttons_cg_sticky_removed){
                                $cg_main_options.find('.cg_short_code_single_pic_configuration_buttons').addClass('cg_sticky').width(cgJsClassAdmin.index.vars.$cg_main_container.width()-2);
                            }
                        }else{
                            $cg_main_options.find('.cg_short_code_multiple_pics_configuration_buttons').removeClass('cg_sticky');
                            $cg_main_options.find('.cg_short_code_single_pic_configuration_buttons').removeClass('cg_sticky');
                        }
                    }

                }
                return false;
            }
        });

        cgJsClassAdmin.options.vars.lastScrollTop = windowScrollTop;

    });


    /*Tab actions - END*/

    // reset votes show info

    $(document).on('hover','#cg_reset_votes2',function (e) {
        e.preventDefault();
        //cgViewOptionCheck(this,e);
        $('#cg_reset_votes2_explanation').toggle();
    });
    $(document).on('hover','#cg_reset_users_votes2',function (e) {
        e.preventDefault();
        //cgViewOptionCheck(this,e);
        $('#cg_reset_users_votes2_explanation').toggle();
    });
    $(document).on('hover','#cg_reset_admin_votes2',function (e) {
        e.preventDefault();
        //cgViewOptionCheck(this,e);
        $('#cg_reset_admin_votes2_explanation').toggle();
    });

    $(document).on('hover','#cg_reset_votes',function (e) {
        e.preventDefault();
        //cgViewOptionCheck(this,e);
        $('#cg_reset_votes_explanation').toggle();
    });
    $(document).on('hover','#cg_reset_users_votes',function (e) {
        e.preventDefault();
        //cgViewOptionCheck(this,e);
        $('#cg_reset_users_votes_explanation').toggle();
    });
    $(document).on('hover','#cg_reset_admin_votes',function (e) {
        e.preventDefault();
        //cgViewOptionCheck(this,e);
        $('#cg_reset_admin_votes_explanation').toggle();
    });

    // reset votes show info --- END

    // go to and blink

    $(document).on('click','a[href="#cgInGalleryUploadFormConfiguration"]',function (e) {
        e.preventDefault();
        //cgViewOptionCheck(this,e);
        var $cgInGalleryUploadFormConfiguration = $('#cgInGalleryUploadFormConfiguration');
        $(window).scrollTop($cgInGalleryUploadFormConfiguration.offset().top-300);
        $cgInGalleryUploadFormConfiguration.addClass('cg_blink');
        setTimeout(function (){
            $cgInGalleryUploadFormConfiguration.removeClass('cg_blink');
        },2000);
    });

    // go to and blink --- END

    // go to cgInGalleryUploadFormButton and blink

    $(document).on('click','a[href="#cgInGalleryUploadFormButton"]',function (e) {
        e.preventDefault();
        //cgViewOptionCheck(this,e);
        var $cgInGalleryUploadFormButton = $('#cg_main_options .cgMultiplePicsOptions.cg_active .GalleryUploadContainer');
        var offsetTop = $cgInGalleryUploadFormButton.offset().top;
        $(window).scrollTop(offsetTop-300);
        $cgInGalleryUploadFormButton.addClass('cg_blink');
        setTimeout(function (){
            $cgInGalleryUploadFormButton.removeClass('cg_blink');
        },2000);
    });

    // go to cgInGalleryUploadFormButton and blink --- END

    // general go to and blink

    $(document).on('click','.cg_go_to_link',function (e) {
        e.preventDefault();
        //cgViewOptionCheck(this,e);
        var $cgGoToTarget = $('.cg_go_to_target'+'[data-cg-go-to-target="'+$(this).attr('data-cg-go-to-link')+'"]:visible');
        var offsetTop = $cgGoToTarget.offset().top;
        $(window).scrollTop(offsetTop-300);
        $cgGoToTarget.addClass('cg_blink');
        setTimeout(function (){
            $cgGoToTarget.removeClass('cg_blink');
        },2000);
    });

    // general go to and blink --- END

    // allow sort options
    $(document).on('click','.cg-allow-sort-option',function (e) {
        e.preventDefault();
        //cgViewOptionCheck(this,e);
        var $element = $(this);
        if($element.hasClass('cg_unchecked')){
            $element.removeClass('cg_unchecked');
            $element.closest('.cg_view').find('.cg-allow-sort-input[value="'+$element.attr('data-cg-target')+'"]').prop('disabled',false);
        }else{
            $element.addClass('cg_unchecked');
            $element.closest('.cg_view').find('.cg-allow-sort-input[value="'+$element.attr('data-cg-target')+'"]').prop('disabled',true);
        }
    });

    // allow sort options --- END

    // allow sort

    $(document).on('click','.AllowSortContainer',function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cgAllowSortCheck($,$(this).find('.AllowSort'));
    });

    // allow sort --- END

    // shortcode options multiple pics

    $(document).on('click','#cg_main_options .cg_short_code_multiple_pics_configuration_cg_gallery',function (e) {
        //cgViewOptionCheck(this,e);
        var $cgMainOptions = $('#cg_main_options');
        $(this).addClass('cg_active');
        $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_cg_gallery_user, .cg_short_code_multiple_pics_configuration_cg_gallery_no_voting, .cg_short_code_multiple_pics_configuration_cg_gallery_winner').removeClass('cg_active');
        $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_container').addClass('cg_hide').removeClass('cg_active');

        var $cgGalleryContainer = $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_cg_gallery_container');

        $cgGalleryContainer.removeClass('cg_hide cg_slide_from_right cg_slide_from_right_animation cg_slide_from_left cg_slide_from_left_animation').addClass('cg_active');

        // slide always from left because on the left side!
        $cgGalleryContainer.addClass('cg_slide_from_left');
        setTimeout(function () {
            $cgGalleryContainer.addClass('cg_slide_from_left_animation');
            cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery'
        },1);

    });

    $(document).on('click','#cg_main_options .cg_short_code_multiple_pics_configuration_cg_gallery_user',function (e) {
        //cgViewOptionCheck(this,e);
        var $cgMainOptions = $('#cg_main_options');
        $(this).addClass('cg_active');
        $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_cg_gallery, .cg_short_code_multiple_pics_configuration_cg_gallery_no_voting, .cg_short_code_multiple_pics_configuration_cg_gallery_winner').removeClass('cg_active');
        $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_container').addClass('cg_hide').removeClass('cg_active');

        var $cgGalleryUserContainer = $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_cg_gallery_user_container');
        $cgGalleryUserContainer.removeClass('cg_hide cg_slide_from_right cg_slide_from_right_animation cg_slide_from_left cg_slide_from_left_animation').addClass('cg_active');

        if(cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass == 'cg_gallery'){
            $cgGalleryUserContainer.addClass('cg_slide_from_right');
            setTimeout(function () {
                $cgGalleryUserContainer.addClass('cg_slide_from_right_animation');
                cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery_user'
            },1);
        }else{
            $cgGalleryUserContainer.addClass('cg_slide_from_left');
            setTimeout(function () {
                $cgGalleryUserContainer.addClass('cg_slide_from_left_animation');
                cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery_user'
            },1);
        }

    });

    $(document).on('click','#cg_main_options .cg_short_code_multiple_pics_configuration_cg_gallery_no_voting',function (e) {
        //cgViewOptionCheck(this,e);
        var $cgMainOptions = $('#cg_main_options');
        $(this).addClass('cg_active');
        $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_cg_gallery, .cg_short_code_multiple_pics_configuration_cg_gallery_user, .cg_short_code_multiple_pics_configuration_cg_gallery_winner').removeClass('cg_active');
        $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_container').addClass('cg_hide').removeClass('cg_active');

        var $cgGalleryUserContainer = $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_cg_gallery_no_voting_container');
        $cgGalleryUserContainer.removeClass('cg_hide cg_slide_from_right cg_slide_from_right_animation cg_slide_from_left cg_slide_from_left_animation').addClass('cg_active');

        if(cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass == 'cg_gallery_winner'){
            $cgGalleryUserContainer.addClass('cg_slide_from_left');
            setTimeout(function () {
                $cgGalleryUserContainer.addClass('cg_slide_from_left_animation');
                cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery_no_voting'
            },1);
        }else{
            $cgGalleryUserContainer.addClass('cg_slide_from_right');
            setTimeout(function () {
                $cgGalleryUserContainer.addClass('cg_slide_from_right_animation');
                cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery_no_voting'
            },1);
        }


    });

    $(document).on('click','#cg_main_options .cg_short_code_multiple_pics_configuration_cg_gallery_winner',function (e) {
        //cgViewOptionCheck(this,e);
        var $cgMainOptions = $('#cg_main_options');
        $(this).addClass('cg_active');
        $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_cg_gallery, .cg_short_code_multiple_pics_configuration_cg_gallery_user, .cg_short_code_multiple_pics_configuration_cg_gallery_no_voting').removeClass('cg_active');
        $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_container').addClass('cg_hide').removeClass('cg_active');

        var $cgGalleryUserContainer = $cgMainOptions.find('.cg_short_code_multiple_pics_configuration_cg_gallery_winner_container');
        $cgGalleryUserContainer.removeClass('cg_hide cg_slide_from_right cg_slide_from_right_animation cg_slide_from_left cg_slide_from_left_animation').addClass('cg_active');

        // slide always from right because on the right side!
        $cgGalleryUserContainer.addClass('cg_slide_from_right');
        setTimeout(function () {
            $cgGalleryUserContainer.addClass('cg_slide_from_right_animation');
            cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery_winner'
        },1);

    });

    // shortcode options multiple pics --- END

    // shortcode options single pic

    $(document).on('click','#cg_main_options .cg_short_code_single_pic_configuration_cg_gallery',function (e) {
        //cgViewOptionCheck(this,e);
        var $cgMainOptions = $('#cg_main_options');
        $(this).addClass('cg_active');
        $cgMainOptions.find('.cg_short_code_single_pic_configuration_cg_gallery_user, .cg_short_code_single_pic_configuration_cg_gallery_no_voting, .cg_short_code_single_pic_configuration_cg_gallery_winner').removeClass('cg_active');
        $cgMainOptions.find('.cg_short_code_single_pic_configuration_container').addClass('cg_hide');

        var $cgGalleryContainer = $cgMainOptions.find('.cg_short_code_single_pic_configuration_cg_gallery_container');

        $cgGalleryContainer.removeClass('cg_hide cg_slide_from_right cg_slide_from_right_animation cg_slide_from_left cg_slide_from_left_animation');

        // slide always from left because on the left side!
        $cgGalleryContainer.addClass('cg_slide_from_left');
        setTimeout(function () {
            $cgGalleryContainer.addClass('cg_slide_from_left_animation');
            cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery'
        },1);

    });

    $(document).on('click','#cg_main_options .cg_short_code_single_pic_configuration_cg_gallery_user',function (e) {
        //cgViewOptionCheck(this,e);
        var $cgMainOptions = $('#cg_main_options');
        $(this).addClass('cg_active');
        $cgMainOptions.find('.cg_short_code_single_pic_configuration_cg_gallery, .cg_short_code_single_pic_configuration_cg_gallery_no_voting, .cg_short_code_single_pic_configuration_cg_gallery_winner').removeClass('cg_active');
        $cgMainOptions.find('.cg_short_code_single_pic_configuration_container').addClass('cg_hide');

        var $cgGalleryUserContainer = $cgMainOptions.find('.cg_short_code_single_pic_configuration_cg_gallery_user_container');
        $cgGalleryUserContainer.removeClass('cg_hide cg_slide_from_right cg_slide_from_right_animation cg_slide_from_left cg_slide_from_left_animation');

        if(cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass == 'cg_gallery'){
            $cgGalleryUserContainer.addClass('cg_slide_from_right');
            setTimeout(function () {
                $cgGalleryUserContainer.addClass('cg_slide_from_right_animation');
                cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery_user'
            },1);
        }else{
            $cgGalleryUserContainer.addClass('cg_slide_from_left');
            setTimeout(function () {
                $cgGalleryUserContainer.addClass('cg_slide_from_left_animation');
                cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery_user'
            },1);
        }

    });

    $(document).on('click','#cg_main_options .cg_short_code_single_pic_configuration_cg_gallery_no_voting',function (e) {
        //cgViewOptionCheck(this,e);
        var $cgMainOptions = $('#cg_main_options');
        $(this).addClass('cg_active');
        $cgMainOptions.find('.cg_short_code_single_pic_configuration_cg_gallery, .cg_short_code_single_pic_configuration_cg_gallery_user, .cg_short_code_single_pic_configuration_cg_gallery_winner').removeClass('cg_active');
        $cgMainOptions.find('.cg_short_code_single_pic_configuration_container').addClass('cg_hide');

        var $cgGalleryUserContainer = $cgMainOptions.find('.cg_short_code_single_pic_configuration_cg_gallery_no_voting_container');
        $cgGalleryUserContainer.removeClass('cg_hide cg_slide_from_right cg_slide_from_right_animation cg_slide_from_left cg_slide_from_left_animation');

        if(cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass == 'cg_gallery_winner'){
            $cgGalleryUserContainer.addClass('cg_slide_from_left');
            setTimeout(function () {
                $cgGalleryUserContainer.addClass('cg_slide_from_left_animation');
                cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery_user'
            },1);
        }else{
            $cgGalleryUserContainer.addClass('cg_slide_from_right');
            setTimeout(function () {
                $cgGalleryUserContainer.addClass('cg_slide_from_right_animation');
                cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery_user'
            },1);
        }

    });

    $(document).on('click','#cg_main_options .cg_short_code_single_pic_configuration_cg_gallery_winner',function (e) {
        //cgViewOptionCheck(this,e);
        var $cgMainOptions = $('#cg_main_options');
        $(this).addClass('cg_active');
        $cgMainOptions.find('.cg_short_code_single_pic_configuration_cg_gallery, .cg_short_code_single_pic_configuration_cg_gallery_user, .cg_short_code_single_pic_configuration_cg_gallery_no_voting').removeClass('cg_active');
        $cgMainOptions.find('.cg_short_code_single_pic_configuration_container').addClass('cg_hide');

        var $cgGalleryUserContainer = $cgMainOptions.find('.cg_short_code_single_pic_configuration_cg_gallery_winner_container');
        $cgGalleryUserContainer.removeClass('cg_hide cg_slide_from_right cg_slide_from_right_animation cg_slide_from_left cg_slide_from_left_animation');

        // slide always from right because on the right side!
        $cgGalleryUserContainer.addClass('cg_slide_from_right');
        setTimeout(function () {
            $cgGalleryUserContainer.addClass('cg_slide_from_right_animation');
            cgJsClassAdmin.options.vars.currentCgShortcodeMultiplePicsActiveClass = 'cg_gallery_winner'
        },1);

    });

    // shortcode options single pic --- END


    // add remove cg_shortcode checkboxes hidden

/*    $(document).on('click','#cg_main_options .cg_shortcode_checkbox',function () {

        var $element = $(this);

        if($element.prop('checked')){
            $element.parent().find('.cg_shortcode_checkbox[type="hidden"]').remove();
        }else{
            var $clone = $element.clone();
            $clone.attr('type','hidden').val('0');
            $clone.addClass('cg_shortcode_checkbox_clone').insertAfter($element);
        }

    });*/

    // add remove cg_shortcode checkboxes hidden --- END

    // modify unmodify imageNamePath

    $(document).on('click','#CustomImageNameContainer',function (e) {
        //cgViewOptionCheck(this,e);
        var $element = $(this);
        cgJsClassAdmin.options.functions.modifyUnmodifyImageNamePath($,$element.find('#CustomImageName'));

    });

    // modify unmodify imageNamePath --- END

    // change cg_icon_upload_input_button and cg_icon_upload_input_yours_remove
    $(document).on('change', '.cg_icon_upload_input_button', function (e) {
        var $field = $(this);
        var $cg_icon_upload_input_container = $field.closest('.cg_icon_upload_input_container');

        $cg_icon_upload_input_container.find('.cg_icon_upload_input_yours_remove ').addClass('cg_hide');
        $cg_icon_upload_input_container.find('.cg_input_error').remove();

        if(!$field[0].files[0]){
            $cg_icon_upload_input_container.find('.cg_icon_upload_input_yours .cg_icon_upload_input_image').removeAttr('style');
            $cg_icon_upload_input_container.find('.cg_icon_upload_input_button_base_64').val('');
            return;
        }

        var file = $field[0].files[0];
        var fileType = file.type;
        var fileSize = file.size;

        if (fileType != 'image/png') {
            $('<p class="cg_input_error">Only PNG allowed</p>').insertAfter($field);
            return;
        }
        if (fileSize > 10000) {
            $('<p class="cg_input_error">Only 10kb maximum size allowed</p>').insertAfter($field);
            return;
        }

        var fileReaderBase64 = new FileReader(file);
        fileReaderBase64.readAsDataURL(file);

        fileReaderBase64.onload = function () {

            var base64url = this.result;

            //Initiate the JavaScript Image object.
            var image = new Image();
            image.src = base64url;
            image.onload = function () {
                var height = this.height;
                var width = this.width;
                if (width > 64) {
                    $('<p class="cg_input_error">Only 64px maximum width allowed</p>').insertAfter($field);
                    return;
                }
                if (height > 64) {
                    $('<p class="cg_input_error">Only 64px maximum height allowed</p>').insertAfter($field);
                    return;
                }
                $cg_icon_upload_input_container.find('.cg_icon_upload_input_yours .cg_icon_upload_input_image').css({
                    'background': 'url("' + base64url + '") no-repeat center',
                    'background-size': 'cover'
                });
                $cg_icon_upload_input_container.find('.cg_icon_upload_input_button_base_64').val(base64url);
                $cg_icon_upload_input_container.find('.cg_icon_upload_input_yours_remove ').removeClass('cg_hide');
            };

        };

    });

    $(document).on('click', '.cg_icon_upload_input_yours_remove ', function (e) {
        var $field = $(this);
        var $cg_icon_upload_input_container = $field.closest('.cg_icon_upload_input_container');
        $cg_icon_upload_input_container.find('.cg_icon_upload_input_yours .cg_icon_upload_input_image').removeAttr('style');
        $cg_icon_upload_input_container.find('.cg_icon_upload_input_button_base_64').val('');
        $cg_icon_upload_input_container.find('.cg_icon_upload_input_button').val('');
        $field.addClass('cg_hide');
    });
    // change cg_icon_upload_input_button and cg_icon_upload_input_yours_remove --- END

    // change VotesPerCategory and uncheck VotePerCategory when changing and vica versa
    $(document).on('click', '#VotePerCategoryContainer ', function (e) {
        //cgViewOptionCheck(this,e);
        $('#VotesPerCategory').val('');
    });
    $(document).on('input', '#VotesPerCategory ', function (e) {
       $('#VotePerCategoryContainer .cg_view_option_checkbox').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
       $('#VotePerCategory').prop('checked',false);
    });
    // change VotesPerCategory and uncheck VotePerCategory when changing and vica versa --- END

    // show textarea if hidden
    $(document).on('click', '#cg_main_options .wp-switch-editor.switch-html ', function (e) {
        //cgViewOptionCheck(this,e);
        $(this).closest('.wp-editor-wrap').find('.wp-editor-container textarea.cg-wp-editor-template').css('visibility','visible');
    });
    // show textarea if hidden --- END

    // VoteMessageSuccessActive
    $(document).on('click', '#VoteMessageSuccessActiveContainer', function (e) {
        cgJsClassAdmin.options.functions.cgVoteMessageSuccessActiveCheck($);
    });
    // VoteMessageSuccessActive --- END

    // VoteMessageWarningActive
    $(document).on('click', '#VoteMessageWarningActiveContainer', function (e) {
        cgJsClassAdmin.options.functions.cgVoteMessageWarningActiveCheck($);
    });
    // VoteMessageWarningActive --- END


// Check RatingVisibleForGalleryNoVoting

    $(document).on('click',".RatingVisibleForGalleryNoVoting",function (e) {
        //cgViewOptionCheck(this,e);

        cgJsClassAdmin.options.functions.cg_RatingVisibleForGalleryNoVoting($,$(this).closest('.cg_view_container'));
    });

// Check RatingVisibleForGalleryNoVoting --- END

// Check EnableSwitchStyleGalleryButton

    $(document).on('click',".EnableSwitchStyleGalleryButtonContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_EnableSwitchStyleGalleryButton($,$(this).closest('.cg_view_container'));
    });

// Check EnableSwitchStyleGalleryButton --- END

// Check EnableSwitchStyleImageViewButton

    $(document).on('click',".EnableSwitchStyleImageViewButtonContainer",function (e) {
        //cgViewOptionCheck(this,e);
        cgJsClassAdmin.options.functions.cg_EnableSwitchStyleImageViewButton($,$(this).closest('.cg_view_container'));
    });

// Check EnableSwitchStyleImageViewButton --- END

// CheckGoogleContainer

    var cgShowDownloadGoogleSignInLib = function (){
        jQuery('#cgGoogleSignInLibDownloadErrorMessage').addClass('cg_hide');
        jQuery('#cgGoogleSignInLibDownloadSuccessMessage').addClass('cg_hide');
        jQuery('#cgGoogleSignInLibDownloadMessage').removeClass('cg_hide');
        jQuery('#cgGoogleSignInLibDownloadMessageBackground').removeClass('cg_hide');
        jQuery('#cgGoogleSignInLibDownloadButtonContainer').removeClass('cg_hide');
        jQuery('#cgGoogleSignInLibDownloadConfirmCheckboxContainer').removeClass('cg_hide');
        jQuery('#cgGoogleSignInLibDownloadConfirmCheckbox').prop('checked',false);
    }

    $(document).on('click',"#CheckGoogleContainer,#CheckGoogleUploadContainer,.GoogleSignInLibModificationRequired",function (e) {

        e.preventDefault();
        if(jQuery('#cgGoogleSignInLibAvailable').val()=='0' || jQuery(this).hasClass('GoogleSignInLibModificationRequired')){
            cgShowDownloadGoogleSignInLib();
        }
    });

    $(document).on('click', '#cgGoogleSignInLibDownloadMessageBackground', function (e) {

        if ($(e.target).closest('#cgGoogleSignInLibDownloadMessage').length || $(e.target).is('#cgGoogleSignInLibDownloadMessage')) {

        } else {
            jQuery('#cgGoogleSignInLibDownloadMessage').addClass('cg_hide');
            jQuery('#cgGoogleSignInLibDownloadMessageBackground').addClass('cg_hide');
            jQuery('#cgGoogleSignInTestClientIdForDomainMessage').addClass('cg_hide');
        }

    });

// CheckGoogleContainer --- END

// GoogleSignInLibDownloadButton

    $(document).on('click',"#cgGoogleSignInLibDownloadButton",function (e) {
        if(!jQuery('#cgGoogleSignInLibDownloadConfirmCheckbox').prop('checked')){
                jQuery('#cgGoogleSignInLibDownloadConfirmCheckboxErrorMessage').removeClass('cg_hide');
        }else{

            var $cgGoogleSignInLibDownloadMessage = jQuery('#cgGoogleSignInLibDownloadMessage');

            $cgGoogleSignInLibDownloadMessage.find('#cgGoogleSignInLibDownloadButtonContainer').addClass('cg_hide');
            $cgGoogleSignInLibDownloadMessage.find('#cgGoogleSignInLibDownloadConfirmCheckboxContainer').addClass('cg_hide');
            $cgGoogleSignInLibDownloadMessage.find('#cgSaveCategoriesLoader').removeClass('cg_hide');// might be added when first time tried and there was error and then have to be added again
            $cgGoogleSignInLibDownloadMessage.find('#cgSaveCategoriesLoaderContainer').removeClass('cg_hide');
            jQuery('#cgGoogleSignInLibDownloadMessageBackground').addClass('cg_pointer_events_none');

            var form = document.getElementById('cgGetGoogleSignInLibForm');
            var formPostData = new FormData(form);

            $.ajax({
                url: 'admin-ajax.php',
                method: 'post',
                data: formPostData,
                dataType: null,
                contentType: false,
                processData: false
            }).done(function (response) {


                $cgGoogleSignInLibDownloadMessage.find('#cgSaveCategoriesLoaderMessage').addClass('cg_hide');
                $cgGoogleSignInLibDownloadMessage.find('#cgSaveCategoriesLoader').addClass('cg_hide');

                console.log('success');
                console.log(response);

                if(response.indexOf('successfull-installed') >= 0){

                    if(jQuery('#cg_main_options').length){// then must be edit options

                        jQuery('#cgGoogleSignInLibAvailable').val(1);
                        jQuery('#cgGoogleSignInLibDownloadSuccessMessage').html(response).removeClass('cg_hide');

                        setTimeout(function (){
                            cgJsClassAdmin.gallery.vars.isHashJustChanged = true;
                            location.hash = location.hash+'&cgGoogleSignInLib=downloaded';
                            $('#cgSaveOptionsButton').click();
                        },5000);

                    }else{

                        jQuery('#GoogleSignInLibModificationRequiredParent').css('padding','0');
                        jQuery('#GoogleSignInLibModificationRequiredParent .cg_pro_version_info_container').css({
                            'border':'none',
                            'padding':'0'
                        });
                        jQuery('.GoogleSignInLibModificationRequired').addClass('cg_hide');
                        // then must be from main-menu.php
                        if(jQuery('#GoogleSignInLibModificationRequiredLink').hasClass('cg-google-sign-in-is-update-only')){
                            jQuery('#cgGoogleSignInLibDownloadSuccessMessage').html('Google sign in authentication library successfully updated.').removeClass('cg_hide');
                        }else{
                            jQuery('#cgGoogleSignInLibDownloadSuccessMessage').html('Google sign in authentication library successfully installed.' +
                                '<br>You can now use Google sign in button.' +
                                '<br>Please check your Login via Google options.').removeClass('cg_hide');
                        }

                        setTimeout(function (){
                            jQuery('#cgGoogleSignInLibDownloadMessageBackground').removeClass('cg_pointer_events_none');
                        },2000);

                    }

                    jQuery('#GoogleSignInLibModificationRequiredContainer').addClass('cg_hide');
                }else{

                    jQuery('#cgGoogleSignInLibDownloadErrorMessage').html(response).removeClass('cg_hide');
                    jQuery('#cgGoogleSignInLibDownloadMessageBackground').removeClass('cg_pointer_events_none');

                }

            }).fail(function (xhr, status, error) {

                $cgGoogleSignInLibDownloadMessage.find('#cgSaveCategoriesLoaderMessage').addClass('cg_hide');
                $cgGoogleSignInLibDownloadMessage.find('#cgSaveCategoriesLoader').addClass('cg_hide');

                console.log('fail');

            }).always(function () {

            });

        }
    });

    $(document).on('change',"#cgGoogleSignInLibDownloadConfirmCheckbox",function (e) {
        if(jQuery(this).prop('checked')){
                jQuery('#cgGoogleSignInLibDownloadConfirmCheckboxErrorMessage').addClass('cg_hide');
        }else{
                jQuery('#cgGoogleSignInLibDownloadConfirmCheckboxErrorMessage').removeClass('cg_hide');
        }
    });

    $(document).on('click',"#cgGoogleSignInLibDownloadMessage .cg_message_close",function (e) {
        jQuery('#cgGoogleSignInLibDownloadMessage').addClass('cg_hide');
        jQuery('#cgGoogleSignInLibDownloadMessageBackground').addClass('cg_hide');
        jQuery('#cgGoogleSignInTestClientIdForDomainMessage').addClass('cg_hide');
    });

// GoogleSignInLibDownloadButton --- END


// cgGoogleSignInTestClientIdForDomain


    // MIGHT BE REQUIRED IN FUTURE - DO NOT DELETE IN THE MOMENT

    var cgGoogleSignInTestUri;

    $(document).on('click',"#cgGoogleSignInTestClientIdForDomain",function (e) {

        return;

        jQuery('#cgGoogleSignInTestClientIdForDomainMessage').removeClass('cg_hide');
        jQuery('#cgGoogleSignInLibDownloadMessageBackground').removeClass('cg_hide');

        $('#cgGoogleClientIdDomainMessage').text($('#GoogleClientId').val());

        var cgGoogleSignInDomain = $('#cgGoogleSignInDomain').text();

        if(cgGoogleSignInDomain.split('//')[1].indexOf('/') > -1){

            var part1 = cgGoogleSignInDomain.split('//')[0];
            var part2 = cgGoogleSignInDomain.split('//')[1].split('/')[0];

            cgGoogleSignInDomain = part1+'//'+part2;
        }

        $('#cgGoogleSignInDomainMessage').text(cgGoogleSignInDomain);

        cgGoogleSignInTestUri = 'https://accounts.google.com/o/oauth2/auth?client_id='+$('#GoogleClientId').val()+'&response_type=token&redirect_uri='+encodeURIComponent(cgGoogleSignInDomain)+'&scope=openid%20profile%20email&time='+new Date().getTime();

    });

    $(document).on('click',"#cgGoogleSignInTestClientIdForDomainButton",function (e) {

        return;

        window.open(cgGoogleSignInTestUri,'MyWindow','width=600,height=300');

    });

    // MIGHT BE REQUIRED IN FUTURE - DO NOT DELETE IN THE MOMENT --- END

    $(document).on('change',"#GoogleClientId",function (e) {

        var $cgGoogleSignInTestClientIdForDomain = $('#cgGoogleSignInTestClientIdForDomain');

        var cgGoogleSignInTestUri = $cgGoogleSignInTestClientIdForDomain.attr('href').split('#')[0];
            console.log(cgGoogleSignInTestUri)
        $cgGoogleSignInTestClientIdForDomain.attr('href',cgGoogleSignInTestUri+'#'+$(this).val().trim())

    });

// cgGoogleSignInTestClientIdForDomain --- END

    // MIGHT BE REQUIRED IN FUTURE - DO NOT DELETE IN THE MOMENT --- END

    $(document).on('click',"#LostPasswordMailActiveContainer",function (e) {
        cgJsClassAdmin.options.functions.cg_LostPasswordMailActiveCheck($);
    });

// cgGoogleSignInTestClientIdForDomain --- END

    // save last multiple options

    $(document).on('change',"#AllowRating3",function (e) {
        localStorage.setItem('cg_AllowRating3_last_used_option_gallery_id_'+$(this).attr('data-cg-gid'),$(this).val());
    });

    // save last multiple options --- END

    $(document).on('click','#AdditionalFilesContainer',function (e) {
        var $element = $(this);
        cgJsClassAdmin.options.functions.allowDisallowAdditionalFiles($,$element.find('#AdditionalFiles'));
    });

    // cgInformUserVoteContainer
    $(document).on('click','#cgInformUserVoteContainer',function (e) {
        var $element = $(this);
        cgJsClassAdmin.options.functions.informUserVote($,$element.find('#InformUserVote'));
    });

    // cgInformUserCommentContainer
    $(document).on('click','#cgInformUserCommentContainer',function (e) {
        var $element = $(this);
        cgJsClassAdmin.options.functions.informUserComment($,$element.find('#InformUserComment'));
    });

    // cg_inform_user_upload
    $(document).on('click','#cgInformUserUploadContainer',function (e) {
        var $element = $(this);
        cgJsClassAdmin.options.functions.informUserUpload($,$element.find('#InformUserUpload'));
    });

    // cg_share_button
    $(document).on('click','.cg_share_button_option',function (e) {
        var $cg_view = $(this).closest('.cg_view');
        var ShareButtonsHiddenInput = '';

        $cg_view.find('.cg_share_button').each(function (){
                if($(this).prop('checked')){
                    if(!ShareButtonsHiddenInput){
                        ShareButtonsHiddenInput += $(this).val();
                    }else{
                        ShareButtonsHiddenInput += ','+$(this).val();
                    }
                }
        });
        $cg_view.find('.ShareButtonsHiddenInput').val(ShareButtonsHiddenInput);
    });

    // CgEntriesOwnSlugName
    var CgEntriesOwnSlugNameOnLoad = $('#CgEntriesOwnSlugName').val();
    $(document).on('input','#CgEntriesOwnSlugName',function (e) {
        if($(this).val()==CgEntriesOwnSlugNameOnLoad){
            $('#CgEntriesOwnSlugNameChanged').prop('disabled',true);
        }else{
            $('#CgEntriesOwnSlugNameChanged').prop('disabled',false);
        }
    });

    // cgGoToShortcodesIntervalConfigurationOptions
    $(document).on('click','#cgGoToShortcodesIntervalConfigurationOptions',function (e) {
        $('#wpwrap').get(0).scrollIntoView();
        var $td_gallery_info_shortcode_conf = $('.td_gallery_info_shortcode_conf');
        $td_gallery_info_shortcode_conf.addClass('cg_blink');
        setTimeout(function (){
            $td_gallery_info_shortcode_conf.removeClass('cg_blink');
        },2000);
    });

    // cgInformAdminAllowActivateDeactivateContainer
    $(document).on('click','#cgInformAdminAllowActivateDeactivateContainer',function (e) {
        var $checkbox = $(this).find('.cg_view_option_checkbox input');

        var $cgInformAdminActivationURLContainer = $('#cgInformAdminActivationURLContainer');
        if($checkbox.prop('checked')){
            $cgInformAdminActivationURLContainer.removeClass('cg_disabled_override');
        }else{
            $cgInformAdminActivationURLContainer.addClass('cg_disabled_override');
        }
    });

});