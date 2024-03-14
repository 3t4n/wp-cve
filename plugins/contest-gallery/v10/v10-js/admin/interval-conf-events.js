jQuery(document).ready(function ($) {

    // cgShortcodeIntervalConfigurationContainer

    var $clickedDatepicker = undefined;
    var openedShortcode = '';

    $(document).on('click','.td_gallery_info_shortcode_conf_status',function (e) {
        $(this).parent().find('.td_gallery_info_shortcode_conf').click();
    });

    $(document).on('click','.cg_view_option_title_datepicker p > span:first-child',function (e) {
        $clickedDatepicker = $(this).parent().find('.cg_shortcode_interval_datepicker');
        $clickedDatepicker.click();
    });

    $(document).on('click','.cg_view_option_title_datepicker p > span:nth-child(2)',function (e) {
        $clickedDatepicker = $(this);
    });

    var getOptionValue = function (shortcode,jsonOptions,intervalType,$element,valueType,selectedIntervalType,ShortcodeTextOnOff){
        var option = '';
        if(intervalType=='monthly'){
            if(jsonOptions[shortcode] && jsonOptions[shortcode][$element.attr('data-cg-year')] && jsonOptions[shortcode][$element.attr('data-cg-year')][intervalType]  && jsonOptions[shortcode][$element.attr('data-cg-year')][intervalType][$element.attr('data-cg-month')] && jsonOptions[shortcode][$element.attr('data-cg-year')][intervalType][$element.attr('data-cg-month')][valueType]){
                option = jsonOptions[shortcode][$element.attr('data-cg-year')][intervalType][$element.attr('data-cg-month')][valueType];
            }
        }
        if(intervalType=='weekly'){
            if(jsonOptions[shortcode] && jsonOptions[shortcode][$element.attr('data-cg-year')] && jsonOptions[shortcode][$element.attr('data-cg-year')][intervalType] && jsonOptions[shortcode][$element.attr('data-cg-year')][intervalType][valueType]){
                option = jsonOptions[shortcode][$element.attr('data-cg-year')][intervalType][valueType];
            }
        }
        if(intervalType=='daily'){
            if(jsonOptions[shortcode] && jsonOptions[shortcode][$element.attr('data-cg-year')] && jsonOptions[shortcode][$element.attr('data-cg-year')][intervalType] && jsonOptions[shortcode][$element.attr('data-cg-year')][intervalType][valueType]){
                option = jsonOptions[shortcode][$element.attr('data-cg-year')][intervalType][valueType];
            }
        }
        if(selectedIntervalType){
            if(jsonOptions[shortcode] && jsonOptions[shortcode][$element.attr('data-cg-year')] && jsonOptions[shortcode][$element.attr('data-cg-year')]['selectedIntervalType']){
                option = jsonOptions[shortcode][$element.attr('data-cg-year')]['selectedIntervalType'];
            }else{
                option = 'monthly';
            }
        }
        if(ShortcodeTextOnOff){
            if(jsonOptions[shortcode] && jsonOptions[shortcode][ShortcodeTextOnOff]){
                option = jsonOptions[shortcode][ShortcodeTextOnOff];
            }else{
                option = '';
            }
        }
        return option;
    }

    $(document).on('click','.td_gallery_info_shortcode_conf',function () {

        var shortcode = $(this).attr('data-cg-shortcode');
        openedShortcode = shortcode;

        var jsonOptions = cgJsClassAdmin.index.vars.cgOptionsJson.interval;

        cgJsClassAdmin.gallery.functions.showCgBackendBackgroundDrop();
        var $cgShortcodeIntervalConfigurationContainer = $('#cgShortcodeIntervalConfigurationContainer');
        var $cgShortcodeIntervalConfigurationForm = $('#cgShortcodeIntervalConfigurationForm');

        if(!cgJsClassAdmin.index.vars.isShortcodeIntervalDatetpickerLoaded){
            $cgShortcodeIntervalConfigurationContainer.find(".cg_shortcode_interval_datepicker").each(function (){

                var year = $(this).attr('data-cg-year');
                var monthName = $(this).attr('data-cg-month');
                var monthNumber = parseInt($(this).attr('data-cg-month-number'));

                if(monthNumber<10){
                    monthNumber = String('0'+monthNumber);
                }

                var monthLastDay = $(this).attr('data-cg-month-last-day');
                var momentMinDate = year+'-'+monthNumber+'-01';
                var momentMaxDate = year+'-'+monthNumber+'-'+monthLastDay;

              //  console.log('momentMinDate');
           //     console.log(momentMinDate);
      //          console.log('momentMaxDate');
      //          console.log(momentMaxDate);

                var pickerSettings =  {
                    autoUpdateInput: false,
                    opens: 'center',
                    minDate:moment(momentMinDate),
                    maxDate:moment(momentMaxDate),
                };

                if(jsonOptions[openedShortcode] && jsonOptions[openedShortcode][year] && jsonOptions[openedShortcode][year]['monthly'] && jsonOptions[openedShortcode][year]['monthly'][monthName]
                    && jsonOptions[openedShortcode][year]['monthly'][monthName].fromDate  && jsonOptions[openedShortcode][year]['monthly'][monthName].toDate){
                    pickerSettings.startDate = new Date(jsonOptions[openedShortcode][year]['monthly'][monthName].fromDate);
                    pickerSettings.endDate =  new Date(jsonOptions[openedShortcode][year]['monthly'][monthName].toDate);
                }

                $(this).daterangepicker(
                    pickerSettings
                );

            });
        }

        cgJsClassAdmin.options.functions.addCheckedUncheckedClasses(jQuery);

        if(!cgJsClassAdmin.index.vars.isIntervalConfEventsLoaded){
            // events should be initiated only one time!!!
            cgJsClassAdmin.options.functions.initOptionsClickEvents(true);
            cgJsClassAdmin.index.vars.isIntervalConfEventsLoaded = true;
        }

        $cgShortcodeIntervalConfigurationForm.addClass('cg_hide');
        $cgShortcodeIntervalConfigurationContainer.removeClass('cg_hide');

        if(!cgJsClassAdmin.index.vars.isShortcodeIntervalTinyMCEInitialized){
            $cgShortcodeIntervalConfigurationContainer.find('.cg-lds-dual-ring-gallery-hide').removeClass('cg_hide');
            setTimeout(function (){
                $cgShortcodeIntervalConfigurationContainer.find('.cg-lds-dual-ring-gallery-hide').addClass('cg_hide');
                $cgShortcodeIntervalConfigurationForm.removeClass('cg_hide');
            },2000);
        }else{
            $cgShortcodeIntervalConfigurationContainer.find('.cg-lds-dual-ring-gallery-hide').addClass('cg_hide');
            $cgShortcodeIntervalConfigurationForm.removeClass('cg_hide');
        }

        $cgShortcodeIntervalConfigurationContainer.find('.cg_shortcode_conf_title_main').text($(this).attr('data-cg-title-main'));
        $cgShortcodeIntervalConfigurationContainer.find('.cg_shortcode_conf_title_sub').text($(this).attr('data-cg-title-sub'));
        $cgShortcodeIntervalConfigurationContainer.find('.cg_shortcode_conf_activate_type').text($(this).attr('data-cg-shortcode'));

        $cgShortcodeIntervalConfigurationContainer.attr('data-cg-shortcode',shortcode);
        $cgShortcodeIntervalConfigurationContainer.find('.shortcodeType').val(shortcode);

        var jsonOptions = cgJsClassAdmin.index.vars.cgOptionsJson.interval;
        //var jsonOptions = {};

        var $cgShortcodeIntervalConfigurationActivate = $cgShortcodeIntervalConfigurationContainer.find('#cgShortcodeIntervalConfigurationActivate');
        $cgShortcodeIntervalConfigurationActivate.attr('name',shortcode+'[active]').prop('checked',false)
            .closest('.cg_view_option_checkbox').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');

        if(jsonOptions[shortcode] && jsonOptions[shortcode].active=='on'){
            $cgShortcodeIntervalConfigurationActivate.prop('checked',true)
                .closest('.cg_view_option_checkbox').removeClass('cg_view_option_unchecked').addClass('cg_view_option_checked');
        }

        $cgShortcodeIntervalConfigurationContainer.find('.cg_main_options_shortcode_text').each(function (){

            if(cgJsClassAdmin.index.vars.isShortcodeIntervalTinyMCEInitialized){
                $(this).find('.cg_view_option_textarea').each(function (){
                    var id = $(this).attr('id');
                    tinymce.EditorManager.execCommand('mceRemoveEditor', true, id);
                });
            }

            var option = getOptionValue(shortcode,jsonOptions,undefined,$(this),'TextWhenShortcodeIntervalIsOn',undefined,'TextWhenShortcodeIntervalIsOn');
            option = jQuery('<textarea />').html(option).text();
            $(this).find('.cg_view_option_textarea_interval_on').attr('name',shortcode+'[TextWhenShortcodeIntervalIsOn]').val(option);

            if(typeof jsonOptions[shortcode]=='undefined'){
                option = "&lt;p style=&quot;text-align: center;&quot;&gt;&lt;strong&gt;Contest is over&lt;\/strong&gt;&lt;\/p&gt;";
                option = jQuery('<textarea />').html(option).text();
            }else{
                var option = getOptionValue(shortcode,jsonOptions,undefined,$(this),'TextWhenShortcodeIntervalIsOff',undefined,'TextWhenShortcodeIntervalIsOff');
                option = jQuery('<textarea />').html(option).text();
            }

            $(this).find('.cg_view_option_textarea_interval_off').attr('name',shortcode+'[TextWhenShortcodeIntervalIsOff]').val(option);

            if(cgJsClassAdmin.index.vars.isShortcodeIntervalTinyMCEInitialized){
                $(this).find('.cg_view_option_textarea').each(function (){
                    var id = $(this).attr('id');
                    tinymce.EditorManager.execCommand('mceAddEditor', true, id);
                });
            }

            if(!cgJsClassAdmin.index.vars.isShortcodeIntervalTinyMCEInitialized){
                $(this).find('.cg_view_option_textarea').each(function (){
                    console.log('initializeEditor');
                    console.log($(this).attr('id'));
                    var id = $(this).attr('id');
                    cgJsClassAdmin.index.functions.initializeEditor(id);

                });
                cgJsClassAdmin.index.vars.isShortcodeIntervalTinyMCEInitialized = true;
            }

        });

        // has to be done here when all editor are initiated in each
        if(!cgJsClassAdmin.index.vars.isShortcodeIntervalConfTextareasLoaded){
            cgJsClassAdmin.index.vars.isShortcodeIntervalConfTextareasLoaded = true;
        }

        $cgShortcodeIntervalConfigurationContainer.find('.cg_main_options_interval_type').each(function (){
            var option = getOptionValue(shortcode,jsonOptions,undefined,$(this),undefined,true);
            $(this).find('.cg_view_option_radio_multiple_input_field').attr('name',shortcode+'['+$(this).attr('data-cg-year')+'][selectedIntervalType]');
            // uncheck all first
            $(this).find('.cg_view_option_radio_multiple_input').removeClass('cg_view_option_checked').addClass('cg_view_option_unchecked');
            $(this).find('.cg_view_option_radio_multiple_input_field').prop('checked',false);
            // check then
            // checked, checked is for debugger, prop checked checks it really
            $(this).find('.cg_view_option_radio_multiple_input_field[value="'+option+'"]').attr('checked','checked').prop('checked',true).closest('.cg_view_option_radio_multiple_input').addClass('cg_view_option_checked');
            $(this).closest('.cgShortcodeIntervalConfiguration').find('.cg_main_options_shortcode_interval').addClass('cg_hide');
            $(this).closest('.cgShortcodeIntervalConfiguration').find('.cg_main_options_'+option).removeClass('cg_hide');
        });

        $cgShortcodeIntervalConfigurationContainer.find('.cg_shortcode_interval_datepicker_row').each(function (){

            var $cg_shortcode_interval_datepicker_row = $(this);

            var intervalType = $(this).attr('data-cg-interval-type');
            if(intervalType=='monthly'){
                $(this).find('.cg_view_option_select').addClass('cg_hide');
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'fromDate');
                $(this).find('.cg_shortcode_interval_datepicker_input_start').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+']['+$(this).attr('data-cg-month')+'][fromDate]').val(((option) ? option : ''));

                var days = {
                    '0':'Sunday',
                    '1':'Monday',
                    '2':'Tuesday',
                    '3':'Wednesday',
                    '4':'Thursday',
                    '5':'Friday',
                    '6':'Saturday',
                }

                if(option){
                    $(this).find('.cg_view_option_select').removeClass('cg_hide');
                    var startDate = new Date(option);
                    var dayOfMonth = startDate.getDate();
                    if(dayOfMonth<10){
                        dayOfMonth = '0'+String(dayOfMonth);
                    }
                    //var dateStartString = days[picker.startDate._d.getDay()] + ' ' +dayOfMonth;
                    var dateStartString = dayOfMonth + '  (' + days[startDate.getDay()]+')';
                    $cg_shortcode_interval_datepicker_row.find('.cg_shortcode_interval_time_set_date_start').text(dateStartString);
                }
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'toDate');
                $(this).find('.cg_shortcode_interval_datepicker_input_end').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+']['+$(this).attr('data-cg-month')+'][toDate]').val(((option) ? option : ''));
                if(option){
                    var endDate = new Date(option);
                    var dayOfMonth = endDate.getDate();
                    if(dayOfMonth<10){
                        dayOfMonth = '0'+String(dayOfMonth);
                    }
                    //var dateEndString = days[picker.endDate._d.getDay()] + ' ' +dayOfMonth;
                    var dateEndString = dayOfMonth + '  (' + days[endDate.getDay()]+')';
                    $cg_shortcode_interval_datepicker_row.find('.cg_shortcode_interval_time_set_date_end').text(dateEndString);
                }

                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'fromHours');
                $(this).find('.cg-hourspicker-from-left').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+']['+$(this).attr('data-cg-month')+'][fromHours]').val(((option) ? option : '00'));
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'fromMinutes');
                $(this).find('.cg-minutespicker-from-left').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+']['+$(this).attr('data-cg-month')+'][fromMinutes]').val(((option) ? option : '00'));
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'toHours');
                $(this).find('.cg-hourspicker-from-right').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+']['+$(this).attr('data-cg-month')+'][toHours]').val(((option) ? option : '23'));
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'toMinutes');
                $(this).find('.cg-minutespicker-from-right').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+']['+$(this).attr('data-cg-month')+'][toMinutes]').val(((option) ? option : '59'));
            }
            if(intervalType=='weekly'){
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'dayStart');
                $(this).find('.cg_days_select_start').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+'][dayStart]').val(((option) ? option : ''));
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'dayEnd');
                $(this).find('.cg_days_select_end').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+'][dayEnd]').val(((option) ? option : ''));
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'fromHours');
                $(this).find('.cg-hourspicker-from-left').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+'][fromHours]').val(((option) ? option : '00'));
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'fromMinutes');
                $(this).find('.cg-minutespicker-from-left').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+'][fromMinutes]').val(((option) ? option : '00'));
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'toHours');
                $(this).find('.cg-hourspicker-from-right').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+'][toHours]').val(((option) ? option : '23'));
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'toMinutes');
                $(this).find('.cg-minutespicker-from-right').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+'][toMinutes]').val(((option) ? option : '59'));
            }
            if(intervalType=='daily'){
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'fromHours');
                $(this).find('.cg-hourspicker-from-left').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+'][fromHours]').val(((option) ? option : '00'));
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'fromMinutes');
                $(this).find('.cg-minutespicker-from-left').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+'][fromMinutes]').val(((option) ? option : '00'));
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'toHours');
                $(this).find('.cg-hourspicker-from-right').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+'][toHours]').val(((option) ? option : '23'));
                var option = getOptionValue(shortcode,jsonOptions,intervalType,$(this),'toMinutes');
                $(this).find('.cg-minutespicker-from-right').attr('name',shortcode+'['+$(this).attr('data-cg-year')+']['+$(this).attr('data-cg-interval-type')+'][toMinutes]').val(((option) ? option : '59'));
            }
        });

        $('.cg_shortcode_interval_datepicker').on('show.daterangepicker', function(ev, picker) {
            picker.container.addClass('cg_visibility_hidden');
            if(!picker.container.find('drp-calendar.left .active.start-date.available:not(.ends)').length){
                picker.container.find('.drp-calendar.left').addClass('cg_hide');
                picker.container.removeClass('cg_visibility_hidden');
                return;
            }
            if(!picker.container.find('drp-calendar.left .active.start-date.available:not(.ends)').length){
                picker.container.find('.drp-calendar.left').addClass('cg_hide');
                picker.container.removeClass('cg_visibility_hidden');
                return;
            }
        });

        var days = {
            '0':'Sunday',
            '1':'Monday',
            '2':'Tuesday',
            '3':'Wednesday',
            '4':'Thursday',
            '5':'Friday',
            '6':'Saturday',
        }

        $('.cg_shortcode_interval_datepicker').on('apply.daterangepicker', function(ev, picker) {

            var $cg_shortcode_interval_datepicker_row = $clickedDatepicker.closest('.cg_shortcode_interval_datepicker_row');
            $cg_shortcode_interval_datepicker_row.find('.cg_shortcode_interval_datepicker_input_start').val(moment(picker.startDate._d).format('YYYY-MM-DD'));
            $cg_shortcode_interval_datepicker_row.find('.cg_shortcode_interval_datepicker_input_end').val(moment(picker.endDate._d).format('YYYY-MM-DD'));
            $cg_shortcode_interval_datepicker_row.find('.cg_view_option_select').removeClass('cg_hide');
            var dayOfMonth = picker.startDate._d.getDate();
            if(dayOfMonth<10){
                dayOfMonth = '0'+String(dayOfMonth);
            }
            //var dateStartString = days[picker.startDate._d.getDay()] + ' ' +dayOfMonth;
            var dateStartString = dayOfMonth + '  (' + days[picker.startDate._d.getDay()]+')';
            $cg_shortcode_interval_datepicker_row.find('.cg_shortcode_interval_time_set_date_start').text(dateStartString);

            var dayOfMonth = picker.endDate._d.getDate();
            if(dayOfMonth<10){
                dayOfMonth = '0'+String(dayOfMonth);
            }
            //var dateEndString = days[picker.endDate._d.getDay()] + ' ' +dayOfMonth;
            var dateEndString = dayOfMonth + '  (' + days[picker.endDate._d.getDay()]+')';
            $cg_shortcode_interval_datepicker_row.find('.cg_shortcode_interval_time_set_date_end').text(dateEndString);

        });

        $('.cg_shortcode_interval_datepicker').on('cancel.daterangepicker', function(ev, picker) {
            var $cg_shortcode_interval_datepicker_row = $clickedDatepicker.closest('.cg_shortcode_interval_datepicker_row');
            $cg_shortcode_interval_datepicker_row.find('.cg_view_option_select').addClass('cg_hide');
            $cg_shortcode_interval_datepicker_row.find('.cg_shortcode_interval_datepicker_input_start').val('');
            $cg_shortcode_interval_datepicker_row.find('.cg_shortcode_interval_datepicker_input_end').val('');
        });


    });

    $(document).on('click','.cg_shortcode_conf_tab_left, .cg_shortcode_conf_tab_right',function () {

        var year = $(this).attr('data-cg-year');

        $(this).parent().find('> div').removeClass('active');
        $(this).addClass('active');
        $('.cgShortcodeIntervalConfiguration').addClass('cg_hide');
        var $cgShortcodeIntervalConfigurationContainer = $('#cgShortcodeIntervalConfigurationContainer');
        var shortcode = $cgShortcodeIntervalConfigurationContainer.attr('data-cg-shortcode');
        var $cgShortcodeIntervalConfiguration = $('#cgShortcodeIntervalConfiguration'+year);
        $cgShortcodeIntervalConfiguration.removeClass('cg_hide');
        var $cg_main_options_interval_type = $cgShortcodeIntervalConfiguration.find('cg_main_options_interval_type');

        var jsonOptions = cgJsClassAdmin.index.vars.cgOptionsJson.interval;
        //var jsonOptions = {};

        var option = getOptionValue(shortcode,jsonOptions,undefined,$cgShortcodeIntervalConfiguration,undefined,true);
        $cg_main_options_interval_type.find('.cg_view_option_radio_multiple_input_field').attr('name',shortcode+'['+$cg_main_options_interval_type.attr('data-cg-year')+'][selectedIntervalType]');
        $cg_main_options_interval_type.find('.cg_view_option_radio_multiple_input_field[value="'+option+'"]').attr('checked','checked').closest('.cg_view_option_radio_multiple_input').addClass('cg_view_option_checked');
        $cg_main_options_interval_type.find('.cg_main_options_shortcode_interval').addClass('cg_hide');
        $cg_main_options_interval_type.find('.cg_main_options_'+option).removeClass('cg_hide');

    });

    $(document).on('click','#cgShortcodeIntervalConfigurationContainer .cg_view_option_radio_multiple_container',function () {
        var intervalType = $(this).find('.cg_view_option_radio_multiple_input_field').val();
        var $cgShortcodeIntervalConfiguration = $(this).closest('.cgShortcodeIntervalConfiguration');
        $cgShortcodeIntervalConfiguration.find('.cg_main_options_shortcode_interval').addClass('cg_hide');
        $cgShortcodeIntervalConfiguration.find('.cg_main_options_shortcode_interval.cg_main_options_'+intervalType).removeClass('cg_hide');
    });

    // submit interval conf
    // 'submit' has to be done otherwise tinymce not send iframe input data ( does not put it in connected textarea with name )
    $(document).on('submit', '#cgShortcodeIntervalConfigurationForm', function (e) {
        e.preventDefault();

        var form = document.getElementById('cgShortcodeIntervalConfigurationForm');
        var formPostData = new FormData(form);

        var $cgShortcodeIntervalConfigurationContainer = $('#cgShortcodeIntervalConfigurationContainer');
        var $cgShortcodeIntervalConfigurationForm = $cgShortcodeIntervalConfigurationContainer.find('#cgShortcodeIntervalConfigurationForm');
        $cgShortcodeIntervalConfigurationForm.addClass('cg_hide');
        $cgShortcodeIntervalConfigurationContainer.find('.cg-lds-dual-ring-gallery-hide').removeClass('cg_hide');

        setTimeout(function (){
            $.ajax({
                url: 'admin-ajax.php',
                method: 'post',
                data: formPostData,
                dataType: null,
                contentType: false,
                processData: false
            }).done(function (response) {

                $cgShortcodeIntervalConfigurationContainer.find('.cg-lds-dual-ring-gallery-hide').addClass('cg_hide');

                //        debugger
                //console.log('response SUCCESS');
                //console.log(response);

                var parser = new DOMParser();
                var parsedHtml = parser.parseFromString(response, 'text/html');

                jQuery(parsedHtml).find('script[data-cg-processing="true"]').each(function () {
                    var script = jQuery(this).html();
                    eval(script);
                });

                $cgShortcodeIntervalConfigurationContainer.addClass('cg_hide');
                cgJsClassAdmin.gallery.functions.hideCgBackendBackgroundDrop();
                cgJsClassAdmin.gallery.functions.setAndAppearBackendGalleryDynamicMessage('Changes saved',true);

            }).fail(function (xhr, status, error) {
                console.log('response error');
                console.log(error);
            }).always(function () {

                var test = 1;

            });

        },1000);

    });

});