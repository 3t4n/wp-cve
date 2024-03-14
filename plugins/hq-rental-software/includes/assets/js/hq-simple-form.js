var pickupDatePicker = jQuery('#hq-pick-up-date-button').flatpickr(getDateSetup());
var returnDatePicker = jQuery('#hq-return-date-button').flatpickr(getDateSetup());
jQuery(document).ready(function () {
    var datepickerLibraryDateFormat = 'MM/DD/YYYY';
    var momentFormat = hqMomentDateFormat;
    var momentTimeFormat = hqMomentDateFormat.split(' ').splice(1).join(' ');
    var defaultPickupTimeSystem = hqCarRentalSettingDefaultPickupTime.setting;
    var defaultReturnTimeSystem = hqCarRentalSettingDefaultReturnTime.setting;
    var defaultPickupTime = moment(defaultPickupTimeSystem, momentTimeFormat);
    var defaultReturnTime = moment(defaultReturnTimeSystem, momentTimeFormat);
    var pickupTimePicker = jQuery('#hq-pick-up-time-inner-wrapper').flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat : 'h:i K',
        minuteIncrement: 15,
        defaultHour: defaultPickupTime.hour(),
        defaultMinute: defaultPickupTime.minute(),
        disableMobile: true,
        locale: getLocale(),
        onClose: function(selectedDates, dateStr, instance){
            var momentPickup = moment(selectedDates[0]);
            updateTimeUI(momentPickup, false, instance);
            updateTimeUI(momentPickup, true, instance);
        }
    })
    var returnTimePicker = jQuery('#hq-return-time-inner-wrapper').flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat : 'h:i K',
        minuteIncrement: 15,
        defaultHour: defaultReturnTime.hour(),
        defaultMinute: defaultReturnTime.minute(),
        disableMobile: true,
        locale: getLocale(),
        onClose: function(selectedDates, dateStr, instance){
            var momentReturn = moment(selectedDates[0]);
            updateTimeUI(momentReturn, true, instance);
        },
    });

    jQuery('#hq-pick-up-time-select').on('change', function (event) {
        event.preventDefault();
        var momentPickupTime = moment(jQuery('#hq-pick-up-time-select').val(), 'HH:mm');
        updateTimeUI(momentPickupTime, false);
        updateTimeUI(momentPickupTime, true);
    });
    jQuery('#hq-return-time-select').on('change', function (event) {
        event.preventDefault();
        var momentPickupTime = moment(jQuery('#hq-return-time-select').val(), 'HH:mm');
        updateTimeUI(momentPickupTime, false);
        updateTimeUI(momentPickupTime, true);
    });

    jQuery('#hq-pick-up-date-button').on('click', function (event) {
        event.preventDefault();
    });
    jQuery('#hq-return-date-button').on('click', function (event) {
        event.preventDefault();
    });
    jQuery('#hq-pick-up-time-button').on('click', function (event) {
        event.preventDefault();
    });
    jQuery('#hq-return-time-button').on('click', function (event) {
        event.preventDefault();
    });
    jQuery('.hq-input-wrapper-checkbox').on('click', function (event) {
        event.preventDefault();
        var value = jQuery('#same_locations').val();
        //same-shit
        if (value) {
            jQuery('#same_locations').val("");
            jQuery('.hq-input-wrapper-checkbox i').removeClass('fa-check');
            jQuery('#hq-return-location-wrapper').slideUp();
            jQuery('#pick_up_location_label').html(pickupReturnLocation + '*');
        } else {
            jQuery('#same_locations').val("1");
            jQuery('.hq-input-wrapper-checkbox i').addClass('fa-check');
            jQuery('#hq-return-location-wrapper').slideDown();
            jQuery("#return_location").val(null).trigger("change");
            jQuery('#pick_up_location_label').html(pickUpLocationLabel + '*');
        }
        jQuery('#hq-dates-section').slideDown();
    });
    jQuery('#pick_up_location').on('select2:opening', function (e) {
        if (jQuery('#form_init').val() == '') {
            jQuery('#form_init').val('1');
            jQuery('#hq-dates-section').slideDown();

        }

    });
    jQuery('#hq-simple-form-inner-wrapper').on('submit', function (event) {
        if (!canSubmit()) {
            event.preventDefault();
            jQuery('#hq-alert-message').slideDown();
        }else{
            var vehicleClass = jQuery('#vehicle_class_id').val();
            if(String(vehicleClass) === '0'){
                jQuery('#target_step').val('2');
            }
        }
    });
    initForm(defaultPickupTimeSystem, defaultReturnTimeSystem, momentTimeFormat, pickupTimePicker, returnTimePicker);
    var select2Config = getSelect2Config(true);
    var select2ConfigReturn = getSelect2Config();
    jQuery("#pick_up_location").select2(select2Config);
    jQuery("#return_location").select2(select2ConfigReturn);
    jQuery("#pick_up_location").on('select2:select', function () {
        resolveNameAttr("#pick_up_location", false);
        var value = jQuery('#same_locations').val();
        if (!value) {
            //same from pickup
            var selectedOption = jQuery("#pick_up_location").select2('data')[0];
            var option = new Option(selectedOption.text, selectedOption.id, false, false);
            jQuery("#return_location").append(option);
            jQuery("#return_location").val(selectedOption.id).trigger("change");
            resolveNameAttr("#return_location", true , selectedOption.custom);
        }
    });
    jQuery("#return_location").on('select2:select', function () {
        resolveNameAttr("#return_location", true);
    });
});

function canSubmit() {
    if(jQuery('#target_step').val() == '3'){
        var pickupLocation = jQuery("#pick_up_location").val();
        var returnLocation = jQuery("#return_location").val();
        var sameLocation = jQuery('#same_locations').val();
        return (pickupLocation.length && returnLocation.length) ||
            (pickupLocation.length && (!sameLocation));
    }else{
        var pickupLocation = jQuery("#pick_up_location").val();
        var returnLocation = jQuery("#return_location").val();
        var sameLocation = jQuery('#same_locations').val();
        return (pickupLocation.length && returnLocation.length) ||
            (pickupLocation.length && (!sameLocation));
    }

}

function initForm(defaultPickupTime, defaultReturnTime,timeFormat, pickupTimePicker, returnTimePicker) {
    var overrideWithNowPickup = hqCarRentalSettingSetDefaultPickupTimeToCurrentTime.setting === '1';
    var overrideWithNowReturn = hqCarRentalSettingSetDefaultReturnTimeToCurrentTime.setting === '1';
    var defaultPickupDate = moment().add(1, 'days').add(15, 'minutes');
    var defaultReturnDate = moment().add(3, 'days').add(15, 'minutes');
    var defaultPickupTimeMoment = moment(defaultPickupTime, timeFormat);
    var defaultReturnTimeMoment = moment(defaultReturnTime, timeFormat);
    if (overrideWithNowPickup) {
        defaultPickupDate = moment();
        defaultPickupTimeMoment = moment().add(5, 'minutes');
    }
    if (overrideWithNowReturn) {
        defaultReturnDate = moment();
        defaultReturnTimeMoment = moment().add(5, 'minutes');
    }
    updateDateUI(defaultPickupDate, false);
    updateTimeUI(defaultPickupTimeMoment, false, pickupTimePicker);
    updateDateUI(defaultReturnDate, true);
    updateTimeUI(defaultReturnTimeMoment, true, returnTimePicker);
}

function updateDateUI(momentObject, pickupReturn) {
    if (pickupReturn) {
        jQuery('#return_date_day').html(momentObject.format('DD'));
        jQuery('#return_date_month').html(momentObject.format('MMM'));
        jQuery('#return_date_year').html(momentObject.format('YYYY'));
        jQuery('#return_date').val(momentObject.format('MM/DD/YYYY'));
    } else {
        jQuery('#pick_up_date_day').html(momentObject.format('DD'));
        jQuery('#pick_up_date_month').html(momentObject.format('MMM'));
        jQuery('#pick_up_date_year').html(momentObject.format('YYYY'));
        jQuery('#pick_up_date').val(momentObject.format('MM/DD/YYYY'));
    }
}

function updateTimeUI(momentObject, pickupReturn, instance) {
    if (pickupReturn) {
        jQuery('#return_date_hour').html(momentObject.format('hh'));
        jQuery('#return_date_minutes').html(':' + momentObject.format('mm'));
        jQuery('#return_date_meridian').html(momentObject.format('A'));
        jQuery('#return_time').val(momentObject.format('hh:mm A'));
    } else {
        jQuery('#pick_up_date_hour').html(momentObject.format('hh'));
        jQuery('#pick_up_date_minutes').html(':' + momentObject.format('mm'));
        jQuery('#pick_up_date_meridian').html(momentObject.format('A'));
        jQuery('#pick_up_time').val(momentObject.format('hh:mm A'));
    }
    instance?.setDate(momentObject.toDate());
}

function getSelect2Config(pickupReturnMode = false) {
    return {
        placeholder: selectPlaceholder,
        multiple: true,
        maximumSelectionLength: 1,
        maximumInputLength: 20,
        ajax: {
            url: websiteURL + '/wp-json/hqrentals/plugin/places',
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            },
            processResults: function (data) {
                // Transforms the top-level key of the response object from 'items' to 'results'
                var items = [];
                var locations = getRegularLocations(pickupReturnMode);
                if (Array.isArray(data.data)) {
                    items = data.data.map(function (item) {
                        return {
                            id: item.description,
                            text: item.description,
                            custom: true
                        };
                    });
                }
                var options = locations.concat(items);
                return {
                    results: options
                };
            }
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    }
}
function getRegularLocations(pickupReturnMode){
    var locations = hqRentalsLocations;
    var currentValue = pickupReturnMode ? jQuery('#pick_up_location').parent().find('.select2-search__field').val() : jQuery('#return_location').parent().find('.select2-search__field').val();
    if(Array.isArray(locations) && locations.length > 0){
        return locations.map(function(filteredLocations){
            return {
                id: filteredLocations.id,
                text: filteredLocations.name,
                custom: false
            }
        }).filter(function(location){
            return String(location.text).toLowerCase().indexOf(currentValue.toLowerCase()) > -1;
        });
    }
    return [];
}
function resolveNameAttr(selector, pickupReturn, forceCustom){
    var selectedOption = jQuery(selector).select2('data')[0];
    if(forceCustom){
        jQuery(selector).attr('name',(pickupReturn) ? 'return_location_custom' : 'pick_up_location_custom');
        jQuery('#hq-simple-form-inner-wrapper').append(
            (pickupReturn) ?
                '<input id="custom-location-return" name="return_location" value="custom" type="hidden">' :
                '<input id="custom-location-pickup" name="pick_up_location" value="custom" type="hidden">'
        );
    }else{
        if(selectedOption.custom){
            jQuery(selector).attr('name',(pickupReturn) ? 'return_location_custom' : 'pick_up_location_custom');
            jQuery('#hq-simple-form-inner-wrapper').append(
                (pickupReturn) ?
                    '<input id="custom-location-return" name="return_location" value="custom" type="hidden">' :
                    '<input id="custom-location-pickup" name="pick_up_location" value="custom" type="hidden">'
            );
        }else{
            jQuery(selector).attr('name',(pickupReturn) ? 'return_location' : 'pick_up_location');
            if(pickupReturn){
                jQuery('#custom-location-return').remove();
            }else{
                jQuery('#custom-location-pickup').remove();
            }
        }
    }
}
function resolveOnDatePickerChanges(selectedDates){
    var momentPickup = moment(selectedDates[0]);
    var momentReturn = moment(selectedDates[1]);
    updateDateUI(momentPickup, false);
    updateDateUI(momentReturn, true);
    pickupDatePicker.setDate(selectedDates);
    returnDatePicker.setDate(selectedDates);
}
function getDateSetup(){
    return {
        mode : 'range',
        enableTime: false,
        locale: getLocale(),
        onClose: function(selectedDates, dateStr, instance){
            resolveOnDatePickerChanges(selectedDates);
        }
    };
}