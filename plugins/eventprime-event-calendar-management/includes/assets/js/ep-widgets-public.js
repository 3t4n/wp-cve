jQuery(document).ready(function(e){
   // For Event calendar widget 
    if (jQuery("#ep_calendar_widget").length > 0) {
        // Send ajax request to get all the event start dates
        jQuery.ajax({
            type: "POST",
            url: widgets_obj.ajax_url,
            data: {action: 'ep_load_event_dates'},
            success: function (response) {
                var data = JSON.parse(response);
                var dates = data.start_dates;
                em_show_calendar(dates);
            }
        });
    } 
});
function em_show_calendar(dates) {
    $ = jQuery;
    var date_format = 'yy-mm-dd';
    if( eventprime.global_settings.datepicker_format ) {
        settings_date_format = eventprime.global_settings.datepicker_format;
        if( settings_date_format ) {
            settings_date_format = settings_date_format.split( '&' )[0];
            if( settings_date_format ) {
                date_format = settings_date_format;
            }
        }
    }
    $("#ep_calendar_widget").datepicker({
        onChangeMonthYear: function () {
            setTimeout(em_generate_calendar_html, 40);
            setTimeout(em_change_dp_css, 40);
            return;
        },
        onHover: function () {},
        onSelect: function (dateText, inst) {
            var gotDate = $.inArray(dateText, dates);
            if(gotDate >=0){
                localStorage.setItem("ep_calendar_active", true);
                localStorage.setItem("ep_calendar_date", dateText);
                var page_url = widgets_obj.event_page_url;
                window.location.href = page_url;
            }
            /*if (gotDate >= 0)
            {
                // Accessing only first element to avoid conflict if duplicate element exists on page
                $("#em_start_date:first").val(dateText);
                var search_url = $("form[name='ep_calendar_event_form']:first").attr('action');
                search_url = em_add_param_to_url("ep-search=" + $("input[name='ep-search']:first").val(), search_url);
                search_url = em_add_param_to_url("date=" + dateText, search_url);
                location.href = search_url;
            }*/

        },
        beforeShowDay: function (date) {
            setTimeout(em_change_dp_css, 10);
            setTimeout(em_generate_calendar_html, 10);
            var year = date.getFullYear();
            // months and days are inserted into the array in the form, e.g "01/01/2009", but here the format is "1/1/2009"
            var month = em_padNumber(date.getMonth() + 1);
            var day = em_padNumber(date.getDate());
            // This depends on the datepicker's date format
            var dateString = year + "-" + month + "-" + day;
            if(date_format === 'dd-mm-yy'){
                dateString = day + "-" + month + "-" + year;
            }else if(date_format === 'mm-dd-yy'){
                dateString = month + "-" + day + "-" + year;
            }else if(date_format === 'dd/mm/yy'){
                dateString = day + "/" + month + "/" + year;
            }else if(date_format === 'mm/dd/yy'){
                dateString = month + "/" + day + "/" + year;
            }else if(date_format === 'yy/mm/dd'){
                dateString = year + "/" + month + "/" + year;
            }else if(date_format === 'dd.mm.yy'){
                dateString = day + "." + month + "." + year;
            }else if(date_format === 'mm.dd.yy'){
                dateString = month + "." + day + "." + year;
            }else if(date_format === 'yy.mm.dd'){
                dateString = year + "." + month + "." + year;
            }
            var gotDate = $.inArray(dateString, dates);
            if (gotDate >= 0) {
                // Enable date so it can be deselected. Set style to be highlighted
                return [true, "em-cal-state-highlight"];
            }
            // Dates not in the array are left enabled, but with no extra style
            return [true, ""];
        }, changeMonth: true, changeYear: true, dateFormat: date_format
    });
    em_generate_calendar_html();
    em_change_dp_css();
}
function em_change_dp_css()
{
    $ = jQuery;
    $(".ep_widget_container .ui-datepicker-header").removeClass("ui-widget-header");
    var emColor = $('.ep_widget_container').find('a').css('color');
    $(".em_color").css('color', emColor);
    //$(".ep_widget_container .ui-datepicker-header").css('background-color', emColor);
    $(".ep_widget_container .ui-datepicker-current-day a").css('background-color', emColor);
}
function em_padNumber(number) {
    var ret = new String(number);
    if (ret.length == 1)
        ret = "0" + ret;
    return ret;
}

function em_add_param_to_url(param, url) {
    _url = url;
    _url += (_url.split('?')[1] ? '&' : '?') + param;
    return _url;
}

function em_generate_calendar_html(){
    jQuery('.em-cal-state-highlight').each(function(){
        if(jQuery(this).find('span').length){
            
        } else{
            jQuery(this).append('<span class="ep-calendar-widget-dots"></span>');
        }
    });
}