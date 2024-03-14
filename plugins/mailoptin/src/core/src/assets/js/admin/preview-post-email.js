(function ($) {
    $(window).on('load', function () {
        $('[data-postid]').on('click', function (e) {
            var postID = $(this).data('postid');
            $('#email-form').on('submit', function (event) {
                event.preventDefault();
                var formData = $(event.currentTarget).serializeArray();
                var email_address = formData[1].value;
                var mailoptin_email_campaign_id = formData[0].value;
                $.post(
                    ajaxurl,
                    {
                        action: 'mailoptin_send_test_email',
                        email_campaign_id: mailoptin_email_campaign_id,
                        post_id: postID,
                        email: email_address,
                        security: $('#mailoptin-send-test-email-nonce').val()
                    },
                    function () {
                        $('#mailoptin-success').fadeIn().delay(3000).fadeOut();
                        location.reload();
                    }, "json");
            });
        });
    });
})(jQuery);