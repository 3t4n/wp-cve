jQuery(document).ready(function ($) {
    $('img').each(function () {
        var alt = $(this).attr('alt');

        if( alt == null)
           $(this).attr('alt', '');
    });
});