var CookiesWUSS = Cookies.noConflict();

function wp_user_stylesheet_switcher_setCSS(switcherId, choice) {
	var cssFile = wp_user_stylesheets[switcherId][choice].file;
	document.getElementById('wp_user_stylesheet_switcher_file' + switcherId + '-css').href=cssFile;

	jQuery ("button[class*='wp_user_stylesheet_switcher_icon_"+switcherId+"']").removeClass("wp_user_stylesheet_switcher_active_option");
	jQuery ("button[class*='wp_user_stylesheet_switcher_icon_"+switcherId+"_"+choice+"']").addClass("wp_user_stylesheet_switcher_active_option");
}

function wp_user_stylesheet_switcher_changeCSS(switcherId, choice) {

	var sessionArray = {};
	cookie = CookiesWUSS.getJSON('wp_user_stylesheet_switcher_js');
	if (null != cookie)
		sessionArray = cookie;

	// Check to see if switcher is still available
	if (!(switcherId in wp_user_stylesheets))
		if (switcherId in sessionArray) {
			// Remove cookies to reset choices
			CookiesWUSS.remove('wp_user_stylesheet_switcher_js');
			return;
		}

	// "-1" mean go to next stylesheet
	if (choice == "-1") {
		var nbStylesheets = 0;

		// Count number of stylesheets
		for (stylesheet in wp_user_stylesheets[switcherId])
			nbStylesheets++;

		if (switcherId in sessionArray)
			choice = sessionArray[switcherId];
		else
			choice = wp_user_stylesheets[switcherId]['default'];
		choice++;
		if (choice+1 >= nbStylesheets)
			choice = 0;
	}

	sessionArray[switcherId] = choice;

	if (wp_user_stylesheets[switcherId][choice].file == "Reset") {
		// Reset all switchers to default
		jQuery('link[rel="stylesheet"]').removeAttr('disabled');
		CookiesWUSS.remove('wp_user_stylesheet_switcher_js');
		for (switcher in wp_user_stylesheets) {
			wp_user_stylesheet_switcher_setCSS (switcher, wp_user_stylesheets[switcher]['default']);
		}
	} else {
		if (wp_user_stylesheets[switcherId][choice].file == "Remove")
			// Disable all CSS
			jQuery('link[rel="stylesheet"]').attr('disabled', 'disabled');
		else
			jQuery('link[rel="stylesheet"]').removeAttr('disabled');

		wp_user_stylesheet_switcher_setCSS(switcherId, choice);

		CookiesWUSS.set('wp_user_stylesheet_switcher_js', sessionArray, { expires: 1000 });
	}
}
