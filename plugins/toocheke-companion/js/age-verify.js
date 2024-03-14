jQuery(function () {
    jQuery(document).ready(function () {
        if (document.cookie.match(/^(.*;)?\s*toocheke_age_verification\s*=\s*[^;]+(.*)?$/)) {
            //no cookie
           // console.log('found cookie');
            jQuery('#age-verification-modal').modal('hide');
        }
        else {
            //found cookie
            //console.log('no cookie');
            jQuery('#age-verification-modal').modal('show');
            jQuery('.modal-backdrop').css('opacity', '0.8');

        }

    });

    jQuery('#btn-18-yes').on('click', function (e) {
        e.preventDefault();
        console.log('yes 18');

        jQuery.ajax({
            type: 'POST',
            url: toocheke_ajax_obj.ajax_url,
            data: {
                _ajax_nonce: toocheke_ajax_obj.nonce,
                action: 'toocheke_set_age_verification_cookie'
            },
            success: function (response) {
                jQuery('#age-verification-modal').modal('hide');
                location.reload();
            }
        });
    });
    jQuery('#btn-18-no').on('click', function (e) {
        e.preventDefault();
        jQuery('.modal-body').html('<h3>Sorry!</h3><div class="alert alert-danger">You are not old enough to view this site...</div>');

    });
});