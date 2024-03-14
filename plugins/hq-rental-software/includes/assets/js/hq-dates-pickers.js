var momentFormat = 'DD-MM-YYYY';
jQuery(document).ready(function () {
    var configDateTimeConfig = {
        format: hqRentalsTenantDatetimeFormat.split(' ')[0],
        timepicker: false,
    };
    updateReturnLocation()
    jQuery("#hq-pickup-date-time-input").datetimepicker(configDateTimeConfig);
    jQuery("#hq-return-date-time-input").datetimepicker(configDateTimeConfig);
    jQuery("#hq-pickup-date-time-input").val(moment().add(1, 'days').format(momentFormat));
    jQuery("#hq-return-date-time-input").val(moment().add(1, 'days').add(60, 'days').format(momentFormat));
    jQuery("#hq-pickup-date-time-input").on("change", function () {
        updateReturn();
    });
    jQuery("#reservation_interval").on("change", function () {
        updateReturn();
    });
    jQuery("#pick-up-location").on("change", function () {
        updateReturnLocation();
    });
});

function updateReturn() {
    var pickup = jQuery("#hq-pickup-date-time-input").val();
    var months = jQuery("#reservation_interval").val().split('_')[0];
    var days = parseInt(months) * 30;
    jQuery("#hq-return-date-time-input").val(moment(pickup, momentFormat).add(days, 'days').format(momentFormat));
}

function updateReturnLocation() {
    jQuery("#return-location").val(jQuery("#pick-up-location").val());
    jQuery("#return-location-select").val(jQuery("#pick-up-location").val());

}