jQuery(document).ready(function ($) {
    $("#countries_list").on('change', function () {
        wacs_update_table();
    });
    function wacs_update_table() {
        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {"action": "wacs_update_table", "session": $("#countries_list").val()},
            success: function (response) {
                location.reload();
            }
        });
    }
});

