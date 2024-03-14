jQuery(document).ready(function($) {
    var ajaxUrl = rswpthemes_opt_in.ajaxurl;
    $('#yes-i-would-love-to').on('click', function() {
        $.ajax({
            url: ajaxUrl,
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'collect_email'
            },
            success: function(response) {
                if (response.success) {
                    alert('Thank you for opting in! You will receive periodic email updates.');
                } else {
                    console.error(response.data.error);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
