jQuery(document).ready(function ($) {
    $('#ccn-template').change(function () {
        if($('#ccn-template').val() === "") {
            $('#ccn-email-recipients').hide();
        } else {
            $('#ccn-email-recipients').show();
        }
        if ($('#ccn-template').val() === 'author_comment') {
            $('#ccn-email-format').show();
            $('#ccn-protect-author').show();
            $('#ccn-email-author-recipients').show();
            $('#ccn-email-moderator-recipients').hide();
        }
        if($('#ccn-template').val() === 'author_trackback' || $('#ccn-template').val() === 'author_pingback') {
            $('#ccn-email-author-recipients').show();
            $('#ccn-email-moderator-recipients').hide();
            $('#ccn-protect-author').show();
            $('#ccn-email-format').show();
        }
        if ($('#ccn-template').val() === 'moderator_comment' || $('#ccn-template').val() === 'moderator_pingback' || $('#ccn-template').val() === 'moderator_trackback') {
            $('#ccn-allow-author-moderation').show();
            $('#ccn-protect-author').show();
            $('#ccn-email-author-recipients').hide();
            $('#ccn-email-moderator-recipients').show();
            $('#ccn-email-format').show();
        } else {
            $('#ccn-allow-author-moderation').hide();
        }
    });
});
