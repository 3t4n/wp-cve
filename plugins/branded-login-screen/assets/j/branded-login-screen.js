jQuery(document).ready(function( $ ) {

	$('#backtoblog a').prop('title','Back to Home Page');

	$('form#loginform').prepend('<h2>Enter your login credentials.</h2><br class="clear">');
	$('form#lostpasswordform').prepend('<h2>Enter the required information.You will receive a new password via e-mail.</h2><br class="clear">');
	$('form#resetpassform').prepend('<h2>Enter your new password below.</h2><br class="clear">');

	$('form#registerform').prepend('<h2>Create your own personalized account. A password will be<br\>e-mailed to you.</h2><br class="clear">');
	$('form').prepend('<p class="ver"><a href="http://brandedlogin.kerrywebster.com">Branded Login Screen 3.2</a></p>');

	//TODO: make the alert boxes look prettier. :)

	$("p.reset-pass:contains('Enter your new password below')").hide();

	$("p.reset-pass:contains('Your password has been reset')").show().addClass('backtologin').removeClass('message').removeClass('reset-pass');
});