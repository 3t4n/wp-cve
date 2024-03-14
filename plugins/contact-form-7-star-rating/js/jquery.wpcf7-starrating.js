/*
 ### Contact Form 7 Star Rating v1.9 - 2017 ###
 * Home: http://www.themelogger.com/
 *
 * Licensed under http://en.wikipedia.org/wiki/MIT_License
 ###
*/

;
(function($) {
    $(document).ready(function() {  
        
        $('.starrating').each( function() {        
            var opt = {} ;
            var el = $(this) ;

            if($(this).attr('data-cancel')=='1') {
                opt.required = true ;                
            }    
            
            opt.callback = function(num) {
                if (typeof num === 'undefined') {
                    num = el.attr('data-def');
                }                
                el.find('.starrating_number').html(num);
            }
            el.find('input').rating(opt);
            el.find('input').rating('select',el.attr('data-def'));
        });
        // Added by Profitable Web Projects http://www.profitablewebprojects.com
        // modified to clear on form submit success
        $('.wpcf7-form').on('reset', function() {
            $('.starrating').each(function() {
                $(this).find('.starrating_number').html('');
                $(this).find('input').rating('drain');
            });
        });        
    });
})(jQuery);
        