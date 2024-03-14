(function ($) {
    $(function () {
                
        /* calendar */
        var start_date = $('.edac-date').data('from-date');
        var end_date = $('.edac-date').data('to-date');
        var language = $('.edac-date').data('language');
        var from_date = parseInt(start_date);
        var to_date = parseInt(end_date)-from_date;

    if($('.edac-dates').length > 0){
        var ids_s = $('.edac-dates').val();
        var ids_array = ids_s.split(',');
        var eventDates = {};
        for(var i=0; i<ids_array.length; i++){
            var date_array = ids_array[i];
            eventDates[ new Date( date_array )] = new Date( date_array );
        }
        $.edacpicker.setDefaults( $.edacpicker.regional[ language ] );
        $('.edac-av-calendar').edacpicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            numberOfMonths: 1,
            yearRange: from_date+":+"+to_date,
            minDate: 0,
            duration: 'fast',
            beforeShowDay: function( date ) {
                var highlight = eventDates[date];
                if( highlight ) {
                     return [true, "event", highlight];
                } else {
                     return [true, '', ''];
                }
             }
        },$.edacpicker.regional[ language ]);
        $('.edac-sec-av-calendar').edacpicker ({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            numberOfMonths: 1,
            yearRange: from_date+":+"+to_date,
            minDate: 0,
            duration: 'fast',
            beforeShowDay: function( date ) {
                var highlight = eventDates[date];
                if( highlight ) {
                     return [true, "event", highlight];
                } else {
                     return [true, '', ''];
                }
             }
        },$.edacpicker.regional[ language ]);
        $('a.edac-state-default').click(function(){
            var selector = $(this);
            $('.edac-state-default').removeClass('edac-state-active');
            selector.addClass('edac-state-active');
        });
        
      } 
	});//$(function () end
}(jQuery));