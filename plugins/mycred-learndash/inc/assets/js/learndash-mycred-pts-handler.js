jQuery(document).ready(function ($) {
    $('.myCred_override_hook').on('click', function () {
        show_hide_override($(this));
    });

    show_hide_override($('.myCred_override_hook'));

    function show_hide_override(current_class) {
        if ($(current_class).prop('checked') === true) {
            $('.display-none').show();
        } else {
            $('.display-none').hide();
        }
    }

    $('.learndash-mycred-pts-button').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: LD_MYCRED_Handler.ajax_url,
            data: {
                action: 'pts_handler',
                course: $('.learndash-mycred-pts-button').data('course')
            },
            success: function (data) {
                if (data.success == false) {
                    alert(data.data);
                } else {
                    alert(data.data);
                    location.reload(true);
                }
            }
        });
    });

});