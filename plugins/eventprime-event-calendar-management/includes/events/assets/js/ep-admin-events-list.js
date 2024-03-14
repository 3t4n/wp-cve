jQuery(function($){
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
       //Modifying Date Formate for Date Picker Range
        var lates = date_format;
        if(date_format == 'dd-mm-yy'){
            lates = 'DD-MM-YYYY';
        }else if(date_format == 'mm-dd-yy'){
            lates = 'MM-DD-YYYY';
        }else if(date_format == 'mm-dd-yy'){
            lates = 'MM-DD-YYYY';
        }else if(date_format == 'yy-mm-dd'){
            lates = 'YYYY-MM-DD';
        }else if(date_format == 'dd/mm/yy'){
            lates = 'DD/MM/YYYY';
        }else if(date_format == 'yy/mm/dd'){
            lates = 'YYYY/MM/DD';
        }else if(date_format == 'mm/dd/yy'){
            lates = 'MM/DD/YYYY';
        }else if(date_format == 'dd.mm.yy'){
            lates = 'DD.MM.YYYY';
        }else if(date_format == 'mm.dd.yy'){
            lates = 'MM.DD.YYYY';
        }else if(date_format == 'yy.mm.dd'){
            lates = 'YYYY/MM/DD';
        }
        jQuery('#event_date_picker').daterangepicker({
            autoUpdateInput: false,
            showDropdowns: true,
            singleDatePicker: false,
            locale: {
                format: lates,
                cancelLabel: 'Clear'
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format) + ' - ' + picker.endDate.format(picker.locale.format));
        }).on("cancel.daterangepicker", function (e, picker) {
            picker.element.val('');
        });
     
});