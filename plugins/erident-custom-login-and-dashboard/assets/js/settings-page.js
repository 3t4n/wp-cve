/**
 * This script is intended to handle the settings page.
 *
 * @param {Object} $ jQuery object.
 * @return {Object}
 */
(function ($) {
	/**
	 * The settings form.
	 *
	 * @var HTMLElement
	 */
	var form = document.querySelector(".cldashboard-settings-form");

	/**
	 * The setting fields.
	 *
	 * @var NodeList
	 */
	var fields = document.querySelectorAll(
		".cldashboard-settings-form .general-setting-field"
	);

	/**
	 * The submit button.
	 *
	 * @var HTMLElement
	 */
	var submitButton = document.querySelector(".cldashboard-submit-button");

	/**
	 * The reset button.
	 *
	 * @var HTMLElement
	 */
	var resetButton = document.querySelector(".cldashboard-reset-button");

	/**
	 * The load default values button.
	 *
	 * @var HTMLElement
	 */
	var loadDefaultSettingsButton = document.querySelector(
		".cldashboard-load-defaults-button"
	);

	/**
	 * The submit notice div.
	 *
	 * @var HTMLElement
	 */
	var submitNotice = document.querySelector(".cldashboard-submit-notice");

	/**
	 * The reset notice div.
	 *
	 * @var HTMLElement
	 */
	var resetNotice = document.querySelector(".cldashboard-reset-notice");

	/**
	 * Whether or not the form is currently being submitted.
	 */
	var isProcessing = false;

	/**
	 * Initialize the module, call the main functions.
	 *
	 * This function is the only function that should be called on top level scope.
	 * Other functions are called / hooked from this function.
	 */
	function init() {
		setupColorPicker();
		setupTabsNavigation();

		var bgImageField = document.querySelector(".cldashboard-bg-image-field");
		if (bgImageField) setupMediaField(bgImageField);

		var formBgImageField = document.querySelector(
			".cldashboard-form-bg-image-field"
		);
		if (formBgImageField) setupMediaField(formBgImageField);

		var logoImageField = document.querySelector(
			".cldashboard-logo-image-field"
		);
		if (logoImageField) setupMediaField(logoImageField);

		setupChainingFields();

		if (form) form.addEventListener("submit", onSubmit);
		if (submitButton) submitButton.classList.add("cldashboard-button");

		if (resetButton) {
			resetButton.classList.add("cldashboard-button");
			resetButton.addEventListener("click", onReset);
		}

		if (loadDefaultSettingsButton) {
			loadDefaultSettingsButton.classList.add("cldashboard-button");
			loadDefaultSettingsButton.addEventListener(
				"click",
				onLoadDefaultSettings
			);
		}
	}

	/**
	 * Setup color picker for color picker fields.
	 */
	function setupColorPicker() {
		$(".color-picker-field").wpColorPicker({
			palettes: true,
			hide: true,
		});
	}

	/**
	 * Setup the tabs navigation for settings page.
	 */
	function setupTabsNavigation() {
		$(".heatbox-tab-nav-item").on("click", function () {
			$(".heatbox-tab-nav-item").removeClass("active");
			$(this).addClass("active");

			var link = this.querySelector("a");

			if (link.href.indexOf("#") === -1) return;

			var hashValue = link.href.substring(link.href.indexOf("#") + 1);

			if ("tools" === hashValue) {
				submitButton.classList.add("is-hidden");
				resetButton.classList.add("is-hidden");
				loadDefaultSettingsButton.classList.add("is-hidden");
			} else {
				submitButton.classList.remove("is-hidden");
				resetButton.classList.remove("is-hidden");
				loadDefaultSettingsButton.classList.remove("is-hidden");
			}

			$(".heatbox-form-container .heatbox-admin-panel").css("display", "none");

			$(".heatbox-form-container .cldashboard-" + hashValue + "-panel").css(
				"display",
				"block"
			);
		});

		window.addEventListener("load", function () {
			var hashValue = window.location.hash.substring(1);
			var currentActiveTabMenu;

			if (!hashValue) {
				currentActiveTabMenu = document.querySelector(
					".heatbox-tab-nav-item.active"
				);
				hashValue = currentActiveTabMenu
					? currentActiveTabMenu.dataset.tab
					: "";
				hashValue = hashValue ? hashValue : "login-screen";
			}

			if ("tools" === hashValue) {
				submitButton.classList.add("is-hidden");
				resetButton.classList.add("is-hidden");
				loadDefaultSettingsButton.classList.add("is-hidden");
			} else {
				submitButton.classList.remove("is-hidden");
				resetButton.classList.remove("is-hidden");
				loadDefaultSettingsButton.classList.remove("is-hidden");
			}

			$(".heatbox-tab-nav-item").removeClass("active");
			$(".heatbox-tab-nav-item.cldashboard-" + hashValue + "-panel").addClass(
				"active"
			);

			$(".heatbox-form-container .heatbox-admin-panel").css("display", "none");

			$(".heatbox-form-container .cldashboard-" + hashValue + "-panel").css(
				"display",
				"block"
			);
		});
	}

	/**
	 * Setup media field.
	 */
	function setupMediaField(field) {
		var wpMedia;

		wpMedia = wp
			.media({
				title: "Choose Background Image",
				button: {
					text: "Upload Image",
				},
				multiple: false, // Set this to true to allow multiple files to be selected
			})
			.on("select", function () {
				var attachment = wpMedia.state().get("selection").first().toJSON();
				field.value = attachment.url;
				field.dispatchEvent(new Event("change"));
			});

		var uploadButton = field.parentNode.querySelector(
			".cldashboard-upload-button"
		);

		if (uploadButton) {
			uploadButton.addEventListener("click", function (e) {
				wpMedia.open();
			});
		}

		var clearButton = field.parentNode.querySelector(
			".cldashboard-clear-button"
		);

		if (clearButton) {
			clearButton.addEventListener("click", function (e) {
				field.value = "";
				field.dispatchEvent(new Event("change"));
			});
		}
	}

	/**
	 * Setup fields chaining/ dependency.
	 */
	function setupChainingFields() {
		var selectors = [
			"[data-show-if-field]",
			"[data-hide-if-field]",
			"[data-show-if-field-checked]",
			"[data-show-if-field-unchecked]",
		];

		selectors.forEach(function (selector) {
			var children = document.querySelectorAll(selector);
			if (!children.length) return;

			[].slice.call(children).forEach(function (child) {
				setupChainingEvent(child, selector);
			});
		});
	}

	/**
	 * Setup fields chaining event.
	 *
	 * @param {HTMLElement} child The children element.
	 * @param selector child The selector that belongs to the children element.
	 */
	function setupChainingEvent(child, selector) {
		var parentName = child.getAttribute(
			selector.replace("[", "").replace("]", "")
		);
		var parentField = document.querySelector("#" + parentName);

		var shownDisplayType = window.getComputedStyle(child).display;
		shownDisplayType = shownDisplayType ? shownDisplayType : "block";

		checkChainingState(child, shownDisplayType, parentField);

		if (parentField.classList.contains("use-select2")) {
			$(parentField).on("change", function (e) {
				checkChainingState(child, shownDisplayType, parentField);
			});
		} else {
			parentField.addEventListener("change", function (e) {
				checkChainingState(child, shownDisplayType, parentField);
			});
		}
	}

	/**
	 * Check the children state: shown or hidden.
	 *
	 * @param {HTMLElement} child The children element.
	 * @param string shownDisplayType The display type of child when it's shown (e.g: "flex" or "block").
	 * @param {HTMLElement} parent The parent/ dependency element.
	 */
	function checkChainingState(child, shownDisplayType, parent) {
		var parentTagName = parent.tagName.toLocaleLowerCase();

		if (parentTagName === "input" && parent.type === "checkbox") {
			// Handle "data-show-if-field-checked".
			if (child.hasAttribute("data-show-if-field-checked")) {
				if (parent.checked) {
					child.style.display = shownDisplayType;
				} else {
					child.style.display = "none";
				}
			} else {
				// Handle "data-show-if-field-unchecked".
				if (!parent.checked) {
					child.style.display = shownDisplayType;
				} else {
					child.style.display = "none";
				}
			}

			return;
		}

		var wantedValue = child.hasAttribute("data-show-if-field")
			? child.dataset.showIfValue
			: child.dataset.hideIfValue;
		var parentValue;

		if (parentTagName === "select") {
			if (parent.multiple) {
				parentValue = $(parent).val();
				wantedValue = JSON.parse(wantedValue);
			} else {
				if (parent.selectedIndex > -1) {
					parentValue = parent.options[parent.selectedIndex].value;
				}
			}
		} else {
			parentValue = parent.value;
		}

		// Handle "data-show-if-field".
		if (child.hasAttribute("data-show-if-field")) {
			if (parentValue === wantedValue) {
				child.style.display = shownDisplayType;
			} else {
				child.style.display = "none";
			}
		} else {
			// Handle "data-hide-if-field".
			if (JSON.stringify(parentValue) === JSON.stringify(wantedValue)) {
				child.style.display = "none";
			} else {
				child.style.display = shownDisplayType;
			}
		}
	}

	function startLoading(button) {
		if (button) button.classList.add("is-loading");
	}

	function stopLoading(button) {
		if (button) button.classList.remove("is-loading");
	}

	/**
	 * Function to run on form submit.
	 *
	 * @param Event e The event object.
	 */
	function onSubmit(e) {
		e.preventDefault();
		if (isProcessing) return;
		isProcessing = true;
		startLoading(submitButton);

		var data = {};

		[].slice.call(fields).forEach(function (field) {
			var value = false;

			if (field.tagName.toLowerCase() === "select") {
				if (field.multiple) {
					value = JSON.stringify($(field).val());
				} else {
					if (field.selectedIndex) {
						value = field.options[field.selectedIndex].value;
					} else {
						value = field.value;
					}
				}
			} else {
				if (field.type === "checkbox" || field.type === "radio") {
					if (field.checked) {
						value = field.value;
					}
				} else {
					value = field.value;
				}
			}

			if (value !== false) data[field.name] = value;
		});

		data.action = "cldashboard_save_settings";
		data.nonce = CustomLoginDashboard.nonces.saveSettings;

		$.ajax({
			url: ajaxurl,
			type: "POST",
			data: data,
		})
			.done(function (r) {
				if (!r || !r.success) return;
				submitNotice.classList.add("is-success");
				submitNotice.classList.remove("is-error");
				submitNotice.innerHTML = r.data;
			})
			.fail(function (jqXHR) {
				var errorMesssage = "Something went wrong";

				if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
					errorMesssage = jqXHR.responseJSON.data;
				}

				submitNotice.classList.remove("is-success");
				submitNotice.classList.add("is-error");
				submitNotice.innerHTML = errorMesssage;
			})
			.always(function () {
				submitNotice.classList.add("is-shown");
				isProcessing = false;
				stopLoading(submitButton);

				setTimeout(function () {
					submitNotice.classList.remove("is-shown");
				}, 3000);
			});
	}

	/**
	 * Function to run on reset button press.
	 *
	 * @param Event e The event object.
	 */
	function onReset(e) {
		e.preventDefault();
		if (!confirm(CustomLoginDashboard.dialogs.resetSettingsConfirmation))
			return;
		if (isProcessing) return;
		isProcessing = true;
		startLoading(resetButton);

		var data = {};

		data.action = "cldashboard_reset_settings";
		data.nonce = CustomLoginDashboard.nonces.resetSettings;

		$.ajax({
			url: ajaxurl,
			type: "POST",
			data: data,
		})
			.done(function (r) {
				if (!r || !r.success) return;
				resetForm();
				resetNotice.classList.add("is-success");
				resetNotice.classList.remove("is-error");
				resetNotice.innerHTML = r.data;
			})
			.fail(function (jqXHR) {
				var errorMesssage = "Something went wrong";

				if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
					errorMesssage = jqXHR.responseJSON.data;
				}

				resetNotice.classList.remove("is-success");
				resetNotice.classList.add("is-error");
				resetNotice.innerHTML = errorMesssage;
			})
			.always(function () {
				resetNotice.classList.add("is-shown");
				isProcessing = false;
				stopLoading(resetButton);

				setTimeout(function () {
					resetNotice.classList.remove("is-shown");
					resetNotice.innerHTML = "";
				}, 3000);
			});
	}

	/**
	 * Reset the settings form.
	 */
	function resetForm() {
		// This line alone doesn't reset the form :).
		form.reset();

		[].slice.call(fields).forEach(function (field) {
			if (field.tagName.toLowerCase() === "select") {
				if (field.multiple) {
					$(field).val([]);
				} else {
					field.selectedIndex = 0;
				}
			} else {
				if (field.type === "checkbox" || field.type === "radio") {
					field.checked = false;
				} else {
					field.value = "";
				}
			}
		});

		// Reset the color picker.
		// @link https://github.com/Automattic/Iris/issues/53
		$(".color-alpha").css("background-color", "");
	}

	/**
	 * Function to run on load defaults button press.
	 *
	 * @param Event e The event object.
	 */
	function onLoadDefaultSettings(e) {
		e.preventDefault();
		if (!confirm(CustomLoginDashboard.dialogs.loadDefaultSettingsConfirmation))
			return;
		if (isProcessing) return;
		isProcessing = true;
		startLoading(loadDefaultSettingsButton);

		var data = {};

		data.action = "cldashboard_load_default_settings";
		data.nonce = CustomLoginDashboard.nonces.loadDefaultSettings;

		$.ajax({
			url: ajaxurl,
			type: "POST",
			data: data,
		})
			.done(function (r) {
				if (!r || !r.success) return;
				populateForm(r.data.settings);
				resetNotice.classList.add("is-success");
				resetNotice.classList.remove("is-error");
				resetNotice.innerHTML = r.data.message;
			})
			.fail(function (jqXHR) {
				var errorMesssage = "Something went wrong";

				if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
					errorMesssage = jqXHR.responseJSON.data;
				}

				resetNotice.classList.remove("is-success");
				resetNotice.classList.add("is-error");
				resetNotice.innerHTML = errorMesssage;
			})
			.always(function () {
				resetNotice.classList.add("is-shown");
				isProcessing = false;
				stopLoading(loadDefaultSettingsButton);

				setTimeout(function () {
					resetNotice.classList.remove("is-shown");
					resetNotice.innerHTML = "";
				}, 3000);
			});
	}

	function populateForm(settings) {
		var value;
		var field;
		var colorPickerPreview;

		for (var fieldName in settings) {
			if (Object.hasOwnProperty.call(settings, fieldName)) {
				value = settings[fieldName];
				field = document.getElementById(fieldName);

				if (field) {
					if (field.tagName.toLowerCase() === "select") {
						if (field.multiple) {
							$(field).val(value);
						} else {
							field.value = value;
						}
					} else if (field.type === "checkbox" || field.type === "radio") {
						field.checked = value ? true : false;
					} else {
						field.value = value;

						if (field.classList.contains("color-picker-field")) {
							$(field).wpColorPicker("color", value);

							colorPickerPreview =
								field.parentNode.parentNode.parentNode.querySelector(
									".color-alpha"
								);

							if (colorPickerPreview) {
								colorPickerPreview.style.backgroundColor = value;
							}
						}
					}
				}
			}
		}
	}

	// Run the module.
	init();
})(jQuery);
