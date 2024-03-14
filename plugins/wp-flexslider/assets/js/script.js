/**
 * WP Flexslider
 *
 * @author mnmlthms
 * @url mnmlthms
 */
;(function( $ ){

    'use strict';

    $(document).ready(function(){

        var $slider = $('.wp-flexslider');

        if( !$slider.length )
            return;

        if( !$.fn.flexslider )
            return;

        $slider.each(function(){
            var $el = $(this),
            default_args = {
                start: function(slider) {

                    slider.slides.removeClass('before-active after-active')
                    var $current_slide = slider.slides.eq(slider.current_slide);
                    $current_slide.prev().addClass('before-active');
                    $current_slide.next().addClass('after-active');


                },
                before: function(slider){
                    slider.addClass('moving');

                    slider.removeClass('moving-prev moving-next');
                    slider.addClass('moving-' + slider.direction );
                    
                    slider.slides.removeClass('before-active after-active');
                    var $current_slide = slider.slides.eq(slider.animatingTo);
                    $current_slide.prev().addClass('before-active');
                    $current_slide.next().addClass('after-active');
                    
                },
                after: function(slider){
                    slider.removeClass('moving');
                    
                }
            },
            settings = $el.data('flex-settings');
            
            // Merge args to Slider args
            $.extend( default_args, settings );
            
            if( settings ){
                $el.flexslider(settings);
            }

        });

    });

// Works with either jQuery
})( window.jQuery );
