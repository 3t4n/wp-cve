(function ($) {
	var isRequesting = false;
	var elms = {};
	var $elms = {};
	var loading = {};
	var ajax = {};

	var adminBarRemovalRoles = [];

	/**
	 * Call the main functions here.
	 */
	function init() {
		elms.saveButton = document.querySelector("button.save-general-settings");
		elms.savedStatusBar = document.querySelector(
			".general-settings-area .saved-status-bar"
		);
		elms.settingFields = document.querySelectorAll(
			".general-settings-area .general-setting-field"
		);

		setupColorPicker();

		elms.saveButton.addEventListener("click", function (e) {
			e.preventDefault();
			ajax.saveSettings();
		});

		setupAdminBarRemovalRoles();
	}

	/**
	 * Setup color picker for color picker fields.
	 */
	function setupColorPicker() {
		var colorFieldsWithInstantPreview = document.querySelectorAll(
			".color-picker-field.has-instant-preview"
		);

		var colorFieldIdsWithInstantPreview = [];

		colorFieldsWithInstantPreview.forEach(function (el) {
			colorFieldIdsWithInstantPreview.push(el.id);
		});

		$(".color-picker-field").wpColorPicker({
			palettes: true,
			hide: true,
			change: function (e, ui) {
				var id = e.target.id;
				var color;

				if (colorFieldIdsWithInstantPreview.includes(id)) {
					color = ui.color.toString();

					document.dispatchEvent(
						new CustomEvent("swift_control_" + id + "_change", {
							detail: {
								fieldId: id,
								hexColor: color,
							},
							bubbles: false,
						})
					);
				}
			},
		});
	}

	loading.start = function () {
		elms.saveButton.disabled = true;
	};

	loading.stop = function () {
		elms.saveButton.disabled = false;
	};

	/**
	 * Send ajax request to save the settings.
	 */
	ajax.saveSettings = function () {
		if (isRequesting) return;
		isRequesting = true;
		loading.start();

		var data = {};

		data.action = "swift_control_save_general_settings";
		data.nonce = SwiftControl.nonces.saveGeneralSettings;

		[].slice.call(elms.settingFields).forEach(function (field) {
			if (field.type === "checkbox") {
				data[field.name] = field.checked ? 1 : 0;
			} else if (field.tagName.toLowerCase() === "select") {
				if (field.name === "remove_by_roles[]") {
					data[field.name] = adminBarRemovalRoles;
				}
			} else {
				data[field.name] = field.value;
			}
		});

		$.ajax({
			url: ajaxurl,
			type: "post",
			dataType: "json",
			data: data,
		})
			.done(function (r) {
				switchSavedStatus("show");

				// We need some delay to give visual effect.
				setTimeout(function () {
					switchSavedStatus("hide");
				}, 2500);
			})
			.always(function () {
				loading.stop();
				isRequesting = false;
			});
	};

	/**
	 * Switch saved status in the metabox headers.
	 *
	 * @param {string} state Whether or not to show the "Saved" status.
	 *                       Accepted values: "show" or "hide". Default is "show".
	 */
	function switchSavedStatus(state) {
		if (state === "hide") {
			elms.savedStatusBar.classList.remove("is-shown");
		} else {
			elms.savedStatusBar.classList.add("is-shown");
		}
	}

	function setupAdminBarRemovalRoles() {
		$elms.removeAdminBar = $(".swift-control-settings .remove-admin-bar");
		var selectedRoles = [];

		$elms.removeAdminBar.select2();

		setAdminBarRemovalRoles($elms.removeAdminBar.select2("data"));

		$elms.removeAdminBar.on("select2:select", function (e) {
			var roleObjects = $elms.removeAdminBar.select2("data");
			var newSelections = [];

			if (e.params.data.id === "all") {
				$elms.removeAdminBar.val("all");
				$elms.removeAdminBar.trigger("change");
			} else {
				if (roleObjects.length) {
					roleObjects.forEach(function (role) {
						if (role.id !== "all") {
							newSelections.push(role.id);
						}
					});

					$elms.removeAdminBar.val(newSelections);
					$elms.removeAdminBar.trigger("change");
				}
			}

			// Use the modified list.
			setAdminBarRemovalRoles($elms.removeAdminBar.select2("data"));
		});

		$elms.removeAdminBar.on("select2:unselect", function (e) {
			setAdminBarRemovalRoles($elms.removeAdminBar.select2("data"));
		});
	}

	function setAdminBarRemovalRoles(roleObjects) {
		adminBarRemovalRoles = [];

		if (!roleObjects || !roleObjects.length) {
			return;
		}

		roleObjects.forEach(function (role) {
			adminBarRemovalRoles.push(role.id);
		});
	}

	init();
})(jQuery);
