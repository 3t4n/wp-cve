(function ($) {
    "use strict";
    $(document).ready(function () {
        var btnSelector = $('.gosign-simple-teaser-block');

        $(btnSelector).each(function(){
            //check click on button.
            if($(this).find('.readmore').data('matomoeventvalue') == "click") {
                $(this).click(function () {
                    _paq.push(['trackEvent', $(this).find('.readmore').attr('href'), $(this).find('.readmore').data('matomoeventvalue'), $(this).find('.readmore').data('matomoeventname')]);
                });
            }
            //check hover on button
            if($(this).find('.readmore').data('matomoeventvalue') == "hover") {
                $(this).one("mouseover", function () {
                    _paq.push(['trackEvent', $(this).find('.readmore').attr('href'), $(this).find('.readmore').data('matomoeventvalue'), $(this).find('.readmore').data('matomoeventname')]);
                });
            }
        });
    });

})(jQuery);
