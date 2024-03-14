(function ($) {
    $(function () {
        
        /* Click function start to display related block as click on menu */
        $('.edac-tabs-trigger').click(function(){
            $('.edac-tabs-trigger').removeClass('edac-active-tab');
            $(this).addClass('edac-active-tab');
            var block_id = 'edac-'+$(this).attr('id');
            $('.edac-blocks-tabs').hide();
            $('#'+block_id).show();
            if((block_id=="edac-how-to-use")||(block_id=="edac-about")||(block_id=="edac-calendar")){$('.edac_setting_form').hide();}
            else{$('.edac_setting_form').show();}
        });// Click function end 
        
        
        /* Display unavailable color as layout selection */
        $('input:radio[name="edac_layout"]').change(function(){
            if($(this).val() == '1'){
                $('.edac-unavailable-color-wrapper').show();
            }else{
                $('.edac-unavailable-color-wrapper').hide();
            }
        });
        if($('input:radio[name="edac_layout"]#edac-first-layout').is(':checked')){
            $('.edac-unavailable-color-wrapper').show();
        }else{
            $('.edac-unavailable-color-wrapper').hide();
        }//End layout selection 
        
              
        
        /* calendar */
        var start_date = $('.edac-from-date').data('from-date');
        var end_date = $('.edac-to-date').data('to-date');
        var from_date = parseInt(start_date);
        var to_date = parseInt(end_date)-from_date;
        var ids = $('#edac-booked-dates').val();
        var ids_array = ids.split(',');
        var eventDates = {};
        for(var i=0; i<ids_array.length; i++){
            var date_array = ids_array[i]
            eventDates[ new Date( date_array )] = new Date( date_array );
        }
        $('.edac-calendar').edacpicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            numberOfMonths: 12,
            yearRange: from_date+":+"+to_date,
            minDate: 0,
            beforeShowDay: function( date ) {
                var highlight = eventDates[date];
                if( highlight ) {
                     return [true, "event", highlight];
                } else {
                     return [true, '', ''];
                }
             }
        }); 
        
        $('.edac-edacpicker-header a').hide();
        $('body').on('click','a.edac-state-default',function(){
            var selector = $(this);
            var date = selector.data('date');
            $('.edac-state-default').removeClass('edac-state-active');
            selector.addClass('edac-state-active');
            var nonce = $('#edac_book_nonce_field').val();
            if(selector.parent().hasClass('event'))
            {
                var remove_smg = $('.edac-dates').data('massage-remove');
                selector.parent().removeClass('event');
            }else{
                var success_smg = $('.edac-dates').data('massage');
                selector.parent().addClass('event');
            }
            $.ajax({
                url: ajaxbook.ajaxurl,
                type: 'post',
                dataType: 'html',
                data: {
                    action:'ajax_book',
                    id: date,
                    nonce:nonce,
                },
                beforeSend: function() {
                    selector.append('<div class="loader"></div>');
                },
                complete: function() {
                   selector.find('.loader').remove();
                },
                success: function( resp ) {
                    //alert(resp);
                }
            });
        });
        
        
        
        $('#edac_unavailable_color').colorpicker();// Unavailable Color of 8Availability Calendar
        
        /* Legend Options */
        $('.edac-legend').change(function () {
            if ($(this).is(':checked')) {
                $('.edac-legend-field').show(200);
            }else{
                $('.edac-legend-field').hide(200);
            }
        });
        if ($('.edac-legend').is(':checked')) {
            $('.edac-legend-field').show();
        }else{
            $('.edac-legend-field').hide();
        }//Legend Options End 
        
        
	});//$(function () end
}(jQuery));