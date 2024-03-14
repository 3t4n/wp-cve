(function ($) {
    var formatDate = window.hqRentalsTenantDatetimeFormat.split(' ')[0];
    var momentFormat = window.hqMomentDateFormat.split(' ')[0];
    var minimumDaysRental = (window.hqMinimumDaysRental) ? hqMinimumDaysRental : 1;
    var configPickup = {
        format: formatDate,
        closeOnDateSelect: true,
        minDate: moment().format(formatDate),
        timepicker: false,
        step: 30,
    };
    var configReturn = {
        format: formatDate,
        closeOnDateSelect: true,
        minDate: moment().format(formatDate),
        timepicker: false,
        step: 30,
    };
    var pickupDate = jQuery('#hq_pick_up_date').datetimepicker(configPickup);
    var returnDate = jQuery('#hq_return_date').datetimepicker(configReturn);
    //remove events to avoid issue on dates changes
    pickupDate.off('blur');
    returnDate.off('blur');
    jQuery('#hq_pick_up_date').on("change", function(){
        jQuery('#hq_return_date').val(moment(jQuery('#hq_pick_up_date').val(), momentFormat).add(minimumDaysRental , 'days').format(momentFormat));
    });
    jQuery('#hq-pick-up-location').on("change", function(){
        jQuery('#hq-return-location').val(jQuery('#hq-pick-up-location').val()).trigger('change');
    });
    jQuery(document).ready(function(){
        var today = moment().add(15,'minutes').format(momentFormat);
        var tomorrow = moment().add(15,'minutes').add(minimumDaysRental ,'days').format(momentFormat);
        jQuery('#hq_pick_up_date').val(today);
        jQuery('#hq_return_date').val(tomorrow
        );
    });
})(jQuery);