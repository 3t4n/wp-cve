(function (api) {

	// When the Customizer is ready
	api.bind('ready', function () {
		// Function to fetch and process the preset JSON data
		function loadPresetSettings(presetName) {
			// Construct the full URL to your JSON file based on the selected preset
			let lpcpluginBaseURL = lpcpluginurl.url;
			let siteURL = lpcpluginurl.site;
			let jsonURL = lpcpluginBaseURL + '/customizer-login-page/login-page-customizer/assets/presets/' + presetName + '.json';

			fetch(jsonURL, {
				headers: {
					'Cache-Control': 'no-cache',
					'Pragma': 'no-cache',
					'Expires': '0'
				}
			})
				.then(response => {
					if (!response.ok) {
						throw new Error('Network response was not ok');
					}
					return response.json();
				})
				.then(presetSettings => {
					if (presetSettings) {
						for (let setting in presetSettings) {
							// Process image paths based on the preset
							switch (presetName) {
								case 'chirp':
									if (setting === "lpc-logo-image") {
										presetSettings[setting] = siteURL + 'assets/presets/images/chirp_logo.png';
									}
									if (setting === "lpc-background-image") {
										presetSettings[setting] = siteURL + 'assets/presets/images/chirp_bg.jpg';
									}
									break;
								case 'education':
									if (setting === "lpc-logo-image") {
										presetSettings[setting] = siteURL + 'assets/presets/images/education_logo.png';
									}
									if (setting === "lpc-form-bg-image") {
										presetSettings[setting] = siteURL + 'assets/presets/images/education_form_bg.jpg';
									}
									break;
							}
							let customizerSettingName = 'lpc_opts[' + setting + ']';
							if (presetSettings.hasOwnProperty(setting) && api(customizerSettingName)) {
								if (customizerSettingName === "lpc_opts[lpc-background-image]") {
									console.log('Setting:', customizerSettingName, 'Value:', presetSettings[setting]);
								}
								api(customizerSettingName).set(presetSettings[setting]);

							} else {
								console.log('Setting:', customizerSettingName, 'does not exist in Customizer or is not direct property of presetSettings.');
							}
						}
					} else {
						console.log('presetSettings is empty or not valid.');
					}
				})
				.catch(error => {
					console.log('There was a problem with the fetch operation:', error.message);
				});
		}

		// Listen for changes on the preset selector
		api.control('lpc_preset_select_control', function (control) {
			control.setting.bind(function (preset) {
				if (preset == 'default') {loadPresetSettings(preset);
				} else {
					console.log('presetSettings is empty or not valid.');
				}
			});
		});
	});

})(wp.customize);
