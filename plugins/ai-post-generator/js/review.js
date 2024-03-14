jQuery(document).ready(function ($) {
    $('.ai-post-generator-notice-dismiss-permanently').click(function (e) {
        e.preventDefault();
        var data = {
            'action': 'ai_post_generator_dismiss_review',
            'ai_post_generator_review_dismiss': 1
        };
        $.post('admin-ajax.php', data, function (response) {
            $('#ai_post_generator-review-notice').hide();
        });
    });
    $('.ai-post-generator-notice-dismiss-temporarily').click(function (e) {
        e.preventDefault();
        var data = {
            'action': 'ai_post_generator_dismiss_review',
            'ai_post_generator_review_later': 1
        };
        $.post('admin-ajax.php', data, function (response) {
            $('#ai_post_generator-review-notice').hide();
        });
    });
});
