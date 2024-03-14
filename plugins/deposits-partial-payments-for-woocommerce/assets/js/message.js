// Deactivation Form
jQuery(document).ready(function () {

    jQuery(document).on("click", function(e) {
        let popup = document.getElementById('awcdp-survey-form');
        let overlay = document.getElementById('awcdp-survey-form-wrap');
        let openButton = document.getElementById('deactivate-deposits-partial-payments-for-woocommerce');
        if(e.target.id == 'awcdp-survey-form-wrap'){
            awcdpClose();
        }
        if(e.target === openButton){
            e.preventDefault();
            popup.style.display = 'block';
            overlay.style.display = 'block';
        }
        if(e.target.id == 'awcdp_skip'){
            e.preventDefault();
            let urlRedirect = document.querySelector('a#deactivate-deposits-partial-payments-for-woocommerce').getAttribute('href');
            window.location = urlRedirect;
        }
        if(e.target.id == 'awcdp_cancel'){
            e.preventDefault();
            awcdpClose();
        }
    });

	function awcdpClose() {
		let popup = document.getElementById('awcdp-survey-form');
        let overlay = document.getElementById('awcdp-survey-form-wrap');
		popup.style.display = 'none';
		overlay.style.display = 'none';
		jQuery('#awcdp-survey-form form')[0].reset();
		jQuery("#awcdp-survey-form form .awcdp-comments").hide();
		jQuery('#awcdp-error').html('');
	}

    jQuery("#awcdp-survey-form form").on('submit', function(e) {
        e.preventDefault();
        let valid = awcdpValidate();
		if (valid) {
            let urlRedirect = document.querySelector('a#deactivate-deposits-partial-payments-for-woocommerce').getAttribute('href');
            let form = jQuery(this);
            let serializeArray = form.serializeArray();
            let actionUrl = 'https://feedback.acowebs.com/plugin.php';
            jQuery.ajax({
                type: "post",
                url: actionUrl,
                data: serializeArray,
                contentType: "application/javascript",
                dataType: 'jsonp',
                beforeSend: function () {
        					jQuery('#awcdp_deactivate').prop( 'disabled', 'disabled' );
        				},
                success: function(data)
                {
                    window.location = urlRedirect;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    window.location = urlRedirect;
                }
            });
        }
    });

    jQuery('#awcdp-survey-form .awcdp-comments textarea').on('keyup', function () {
		awcdpValidate();
	});

    jQuery("#awcdp-survey-form form input[type='radio']").on('change', function(){
        awcdpValidate();
        let val = jQuery(this).val();
        if ( val == 'I found a bug' || val == 'Plugin suddenly stopped working' || val == 'Plugin broke my site' || val == 'Other' || val == 'Plugin doesn\'t meets my requirement' ) {
            jQuery("#awcdp-survey-form form .awcdp-comments").show();
        } else {
            jQuery("#awcdp-survey-form form .awcdp-comments").hide();
        }
    });

    function awcdpValidate() {
		let error = '';
		let reason = jQuery("#awcdp-survey-form form input[name='Reason']:checked").val();
		if ( !reason ) {
			error += 'Please select your reason for deactivation';
		}
		if ( error === '' && ( reason == 'I found a bug' || reason == 'Plugin suddenly stopped working' || reason == 'Plugin broke my site' || reason == 'Other' || reason == 'Plugin doesn\'t meets my requirement' ) ) {
			let comments = jQuery('#awcdp-survey-form .awcdp-comments textarea').val();
			if (comments.length <= 0) {
				error += 'Please specify';
			}
		}
		if ( error !== '' ) {
			jQuery('#awcdp-error').html(error);
			return false;
		}
		jQuery('#awcdp-error').html('');
		return true;
	}

});
