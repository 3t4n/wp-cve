jQuery(document).ready(function ($) {
    $('.xlo-permissions .xlo-trigger').on('click', function () {
        $('.xlo-permissions').toggleClass('xlo-open');
        return false;
    });

    $('.xlo-actions a').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        var source = $this.parents('.xlo-actions').data('source');
        var status = $this.data('status');
        $this.parents('.xlo-actions').find(".xlo_loader").show();

        var nonce = xlo_optin_vars.nonce;
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'xlo_optin_call',
                source: source,
                status: status,
                xlo_nonce: nonce,  // Corrected variable name here
            },
            success: function (result) {
                if (result.status == 'error') {
                    $(".xlo-error-boundary").html("Some error occurred. Please try again later");
                    if (result.hasOwnProperty('message')) {
                        $(".xlo-error-boundary").append(": " + result.message);
                    }
                    $(".xlo-error-boundary").fadeIn(400);
                    $this.parents('.xlo-actions').find(".xlo_loader").hide();
                    setTimeout(function () {
                        $(".xlo-error-boundary").fadeOut(400);
                    }, 3000);
                } else {
                    location.reload();
                }
            }
        });
    });
});
