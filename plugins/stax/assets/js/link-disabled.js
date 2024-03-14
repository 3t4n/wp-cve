jQuery(document).ready(function ($) {
    $(document).on('click', 'a, button, input', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
});
