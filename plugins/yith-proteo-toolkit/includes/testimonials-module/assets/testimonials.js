(function ($) {
    $('.testimonial-read-more').on('click', function(){
        var read_more = $(this),
            testimonial_content = read_more.prev();
        testimonial_content.toggleClass('expanded');
        read_more.toggleClass('expanded');
        if( read_more.hasClass('expanded') ) {
            read_more.html(yith_proteo_testimonials.read_less_button_text);
        } else {
            read_more.html(yith_proteo_testimonials.read_more_button_text);
        }
    });
})(jQuery);