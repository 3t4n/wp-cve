var ewdotpLoadRecaptcha = function() {
	grecaptcha.render('ewd_otp_recaptcha', {
      'sitekey' : ewd_otp_recaptcha.site_key
    });
}