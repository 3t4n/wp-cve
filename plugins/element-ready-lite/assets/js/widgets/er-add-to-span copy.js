(function($) {
    
    /*---------------------------------------
        FIRST WORD BLOCK CSS
    -----------------------------------------*/
    var Element_Ready_Add_Span_To_First_Word = function($scope, $) {
    
        $(".post__meta li").html(function() {
            var text = $(this).text().trim().split(" ");
            var first = text.shift();
            return (text.length > 0 ? "<span class='first__word'>" + first + "</span> " : first) + text.join(" ");
        });
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Post_Carousel.default', Element_Ready_Add_Span_To_First_Word);
    });

})(jQuery);