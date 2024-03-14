jQuery(document).ready(function() {
	jQuery(document)
		.on('click', '#onboarding-save-display-btn', handleSettingSave);

	// A Function send the array of setting to ajax.php
	function handleSettingSave() {
		// Fetch all the settings
		var settings = lasso_lite_helper.fetchAllOptions();

		// Prepare data
		var data = {
			action: 'lasso_lite_store_settings',
			nonce: lassoLiteOptionsData.optionsNonce,
			settings: settings,
		};

		// Send the POST request
		jQuery.post(ajaxurl, data, function (response) {
			console.log('response', response);
		});

		go_to_next_step_action(this);
	}
});

