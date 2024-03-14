jQuery(document).ready(function($) {
    $('.open-popup').click(function(e) {
        e.preventDefault();
        $('#my-popup').show();
    });
    $('.close-popup').click(function(e) {
        e.preventDefault();
        $('#my-popup').hide();
    });
});
