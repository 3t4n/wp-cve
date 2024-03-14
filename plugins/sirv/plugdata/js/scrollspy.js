(function( $ ){
    'use strict';
    $.fn.scrollSpy = function( options ) {
        let settings = $.extend({
            offset: 0,
            activeClass: "active",
            activeClassPos: 'li'
        }, options );

        let winH = $(window).height();
        let docH = $(document).height();

        let $links = $('a', this);
        let elemPos = [];

        $links.each(function(index, element){
            let hash = $(element).attr('href');
            elemPos.push({top: $(hash)[0].offsetTop});
        });

        $(window).on('scroll', scrollHandler.bind(this));

        function scrollHandler(){
            let currPos = $(window).scrollTop();

            for(let i=0; i < elemPos.length-1; i++){
                if(currPos > elemPos[i].top  - settings.offset && currPos < elemPos[i+1].top) {
                    $(settings.activeClassPos, this).removeClass(settings.activeClass);
                    $(settings.activeClassPos, this).eq(i).addClass(settings.activeClass);
                }
            }
    
            if(currPos + winH == docH) {
                //for last item
                $(settings.activeClassPos, this).removeClass(settings.activeClass);
                $(settings.activeClassPos, this).last().addClass(settings.activeClass);
            }
        }
        
    };
})(jQuery);

