dayjs.extend(window.dayjs_plugin_customParseFormat)
var minimumDayRentals = 1;
jQuery(document).ready(function(){
    const format = flatpickrDateTimeFormat;
    const minDays = 1;
    const formatAsArray = format.split(' ');
    const dateFormat = formatAsArray.shift();
    const timeFormat = formatAsArray.join(' ');
    const dateConfig  = {
        enableTime: true,
        dateFormat: dateFormat + ' ' + timeFormat,
        minDate: dayjs().toDate(),
        step: 15,
        validateOnBlur: false,
        disableMobile: true,
        locale: getLocale(),
        minuteIncrement: 15
    };
    setDefaults(hqMomentDateFormat, minDays);
    var pickupDate = jQuery("#hq-times-pick-up-date").flatpickr(dateConfig);
    var returnDate = jQuery("#hq-times-return-date").flatpickr(dateConfig);
    jQuery("#hq-times-pick-up-date").on("change",function(){
        const dateFormatMoment = hqMomentDateFormat;
        var newDate = dayjs( jQuery("#hq-times-pick-up-date").val(), dateFormatMoment ).add(minimumDayRentals, 'day');
        returnDate.setDate(newDate.toDate());
    });
    jQuery("#hq-times-pick-up-time").on("change",function(){
        jQuery("#hq-times-return-time").val(jQuery("#hq-times-pick-up-time").val());
    });
    jQuery('#hq-pick-up-location').on('change',function(){
        jQuery('#hq-return-location').val(jQuery('#hq-pick-up-location').val());
    })
});
function setDefaults(dateFormat,minimumDayRentals){
    var newDate = dayjs().add(15, 'minutes').add(2,'hours').format(dateFormat);
    var tomorrowDate = dayjs().add(minimumDayRentals, 'day').add(15, 'minutes').add(2,'hours').format(dateFormat);
    if(hqRentalsTenantDatetimeFormat && hqCarRentalSettingDefaultReturnTime){
        newDate = newDate.split(' ')[0] + ' ' + hqCarRentalSettingDefaultPickupTime.setting;
        tomorrowDate = tomorrowDate.split(' ')[0] + ' ' + hqCarRentalSettingDefaultReturnTime.setting;
    }
    var overrideWithNowPickup = hqCarRentalSettingSetDefaultPickupTimeToCurrentTime.setting === '1';
    var overrideWithNowReturn = hqCarRentalSettingSetDefaultReturnTimeToCurrentTime.setting === '1';
    if(overrideWithNowPickup){
        newDate = dayjs().add(5,'minutes').format(dateFormat);
    }
    if(overrideWithNowReturn){
        tomorrowDate = dayjs().add(5,'minutes').format(dateFormat);
    }
    jQuery("#hq-times-pick-up-date").val(newDate);
    jQuery("#hq-times-return-date").val(tomorrowDate);
}
