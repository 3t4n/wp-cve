dayjs.extend(window.dayjs_plugin_customParseFormat);
var dateFormatMoment = 'DD-MM-YYYY';
jQuery(document).ready(function (){
    jQuery('#pick-up-location').on('change', function (){
        jQuery('#hq-return-location').val(jQuery(this).val());

    });
    jQuery('#reservation_interval').on('change',function(){
        var interval = jQuery(this).val();
        updateReturnDate(interval);
    });
    // init pickup date
    jQuery('#hq_pick_up_date_interval').val(dayjs().add(1, 'day').format(dateFormatMoment));
    var configDateTimeConfig = {
        format: hqRentalsTenantDatetimeFormat.split(' ')[0],
        timepicker: false,
    };
    jQuery("#hq_pick_up_date_interval").datetimepicker(configDateTimeConfig);
    jQuery('#hq_pick_up_date_interval').on('change', function (){
        var interval = jQuery('#reservation_interval').val();
        updateReturnDate(interval)
    });
});
function updateReturnDate(interval){
    if(interval === '1'){
        jQuery('#rate-type').val(rateType12);
        jQuery('#rate-type-id').val(1);
        //jQuery('#hq-widget-form').attr('action', baseURL + '/reservaciones-12-meses/');
        addYearsToReturn(1);
    }
    if(interval === '2'){
        jQuery('#rate-type').val(rateType24);
        jQuery('#rate-type-id').val(2);
        //jQuery('#hq-widget-form').attr('action', baseURL + '/reservaciones-24-meses/');
        addYearsToReturn(2);
    }
    if(interval === '3'){
        jQuery('#rate-type').val(rateType36);
        jQuery('#rate-type-id').val(3);
        //jQuery('#hq-widget-form').attr('action', baseURL + '/reservaciones-36-meses/');
        addYearsToReturn(3);
    }
}
function addMonthsToReturn(months){
    var pickup = jQuery('#hq_pick_up_date_interval');
    jQuery('#hq_return_date').val(
        dayjs(pickup.val(), dateFormatMoment).add(months * 30,'day').format(dateFormatMoment)
    );
}
function addYearsToReturn(years){
    var pickup = jQuery('#hq_pick_up_date_interval');
    jQuery('#hq_return_date').val(
        dayjs(pickup.val(), dateFormatMoment).add(years,'year').format(dateFormatMoment)
    );
}
