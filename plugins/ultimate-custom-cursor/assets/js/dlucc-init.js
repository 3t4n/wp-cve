;(function ($) {
    "use strict";

    $(document).ready(function () {
        // Custom Cursor
        var data = dlucc_data;
        
        function customCursor() {
            $('body').append('<div class="dl-cursor"></div><div class="dl-fill"></div>').css('cursor', 'none');
            var cursor = $('.dl-cursor'),
                cursorFill = $('.dl-fill'),
                linksCursor = $(data.selectors);

            $(window).on('mousemove', function (e) {
                cursor.css({ 'transform': 'translate(' + (e.clientX - 3) + 'px,' + (e.clientY - 3) + 'px)', 'visibility': 'inherit' });
                cursorFill.css({ 'transform': 'translate(' + (e.clientX - 19) + 'px,' + (e.clientY - 19) + 'px)', 'visibility': 'inherit' });
            });

            $(window).on('mouseout', function () {
                cursor.css('visibility', 'hidden');
                cursorFill.css('visibility', 'hidden');
            });

            linksCursor.each(function () {
                $(this).on('mouseleave', function () {
                    cursorFill.removeClass('cursor-grow');
                });
                $(this).on('mouseover', function () {
                    cursorFill.addClass('cursor-grow');
                });
            });
        }

        if ('enable' === data.status) {
            if ($(window).width() < 768) {
                if ('enable' === data.mobile_status) {
                    customCursor();
                }
            } else {
                customCursor();
            }
        }

    });

})(jQuery);