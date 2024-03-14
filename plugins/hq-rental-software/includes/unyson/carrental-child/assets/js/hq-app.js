(function($){
    var config = {
        format: 'd/m/Y',
        timepicker:false,
        minDate: 0
    }
    $("#pick_up_date").datetimepicker(config);
    $("#return_date").datetimepicker(config);
})(jQuery);