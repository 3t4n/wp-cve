(function($){

    $.datetimepicker.setDateFormatter({
        parseDate: function (date, format) {
            var d = moment(date, format);
            return d.isValid() ? d.toDate() : false;
        },
        formatDate: function (date, format) {
            return moment(date).format(format);
        },
    });
    var datesConfigs = {
        format: "YYYY-MM-DD",
        formatDate: "YYYY-MM-DD",
        timepicker: false,
        minDate: 0
    };
    var datetimeConfigs = {
        format: "YYYY-MM-DD",
        formatDate: "YYYY-MM-DD",
        timepicker: false,
        minDate: 0
    };
    $("#hq-pick-up-date").datetimepicker(datesConfigs);
    $("#hq-return-date").datetimepicker(datesConfigs);
    $("#pick_up_datetime").datetimepicker(datetimeConfigs);
    $("#return_datetime").datetimepicker(datetimeConfigs);
})(jQuery);