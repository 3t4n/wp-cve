jQuery(document).ready(function() {
    jQuery('#jetpack_extras_add_related').click(function(event) {
        event.preventDefault();
        var related = jQuery('.jetpack_extras_twitter_related_input').first().clone();
        jQuery(related).find('input').val('');
        jQuery(related).appendTo('#jetpack_extras_twitter_related');
    });
});
