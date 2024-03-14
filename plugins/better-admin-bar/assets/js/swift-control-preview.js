(function ($) {
	if (window.NodeList && !NodeList.prototype.forEach) {
		NodeList.prototype.forEach = Array.prototype.forEach;
	}

	if (!window.swiftControlOpt) return;

	var menu = document.querySelector(".swift-control-widgets");
	var panelsWrapper = document.querySelector(".swift-control-helper-panels");
	var widgetSize = parseInt(swiftControlOpt.size, 10);

	var position = {
		x: swiftControlOpt.position.x,
		x_direction: swiftControlOpt.position.x_direction,
		y: swiftControlOpt.position.y,
		y_direction: swiftControlOpt.position.y_direction,
		y_percentage: swiftControlOpt.position.y_percentage,
	};

	var docWidth;
	var halfHeight;

	function init() {
		if (!menu) return;
		setupPreview();
		setupMenu();
		checkDisabledWidget();
		checkInlineEditing();
		setupWallaceSupport();
		setupPositions();
	}

	function setupPreview() {
		var previewToggle = document.getElementById("swift_control_preview_toggle");

		if (previewToggle) {
			handlePreviewToggleState();
			previewToggle.addEventListener("change", handlePreviewToggleChange);
		}

		$(document).on(
			"click",
			".swift-control-widgets .swift-control-widget-link",
			function (e) {
				e.preventDefault();
			}
		);

		var arrowIndicatorCheckbox = document.getElementById("remove_indicator");

		if (arrowIndicatorCheckbox) {
			arrowIndicatorCheckbox.addEventListener(
				"change",
				handleRemoveIndicatorChange
			);
		}

		var expandPanelCheckbox = document.getElementById("expanded");

		if (expandPanelCheckbox) {
			expandPanelCheckbox.addEventListener("change", handleExpandPanelChange);
		}

		var colorFieldsWithInstantPreview = document.querySelectorAll(
			".color-picker-field.has-instant-preview"
		);

		colorFieldsWithInstantPreview.forEach(function (el) {
			var id = el.id;

			document.addEventListener(
				"swift_control_" + id + "_change",
				handleColorFieldChange
			);
		});
	}

	function handleRemoveIndicatorChange(e) {
		if (!e.target) return;

		if (e.target.checked) {
			menu.classList.remove("has-arrow");
		} else {
			menu.classList.add("has-arrow");
		}
	}

	function handleExpandPanelChange(e) {
		if (!e.target) return;

		if (e.target.checked) {
			menu.classList.add("is-expanded");
		} else {
			menu.classList.remove("is-expanded");
		}
	}

	function handleColorFieldChange(e) {
		if (!e.detail) return;

		var hexColor = e.detail.hexColor;
		var fieldId = e.detail.fieldId;

		var styleTag = document.querySelector(
			'.swift-control-preview-style[data-field-id="' + fieldId + '"]'
		);
		if (!styleTag) return;

		var styleContent = "";

		switch (fieldId) {
			case "setting_button_bg_color":
				styleContent =
					"\
					.swift-control-widgets .swift-control-widget-setting .swift-control-widget-link, .swift-control-widgets .swift-control-widget-setting .swift-control-widget-link:hover {\
						background-color: " +
					hexColor +
					";\
					}\
					.swift-control-widgets .swift-control-widget-setting::after {\
						color: " +
					hexColor +
					"\
					};\
				";

				break;
			case "setting_button_icon_color":
				styleContent =
					"\
					.swift-control-widgets .swift-control-widget-setting a {\
						color: " +
					hexColor +
					";\
					}\
				";

				break;
			case "widget_bg_color":
				styleContent =
					"\
					.swift-control-widgets .swift-control-widget-link {\
						background-color: " +
					hexColor +
					";\
					}\
				";

				break;
			case "widget_bg_color_hover":
				styleContent =
					"\
					.swift-control-widgets .swift-control-widget-link:hover {\
						background-color: " +
					hexColor +
					";\
					}\
				";

				break;
			case "widget_icon_color":
				styleContent =
					"\
					.swift-control-widgets .swift-control-widget-link {\
						color: " +
					hexColor +
					";\
					}\
				";

				break;
		}

		styleTag.innerHTML = styleContent;
	}

	function handlePreviewToggleChange(e) {
		if (!e.target) return;
		var isChecked = e.target.checked;
		handlePreviewToggleState(isChecked);
	}

	function handlePreviewToggleState(checked) {
		if (checked) {
			menu.classList.remove("is-hidden");

			// Trigger window resize event to reposition the menu.
			setTimeout(function () {
				window.dispatchEvent(new Event("resize"));
			}, 10);
		} else {
			menu.classList.add("is-hidden");
		}
	}

	function setupMenu() {
		document
			.querySelector(".swift-control-widgets .swift-control-widget-setting a")
			.addEventListener("click", function (e) {
				e.preventDefault();

				if (!this.parentNode.parentNode.classList.contains("is-dragging")) {
					this.parentNode.parentNode.classList.toggle("is-expanded");
				}
			});
	}

	function checkDisabledWidget() {
		var disabledItems = document.querySelectorAll(
			".swift-control-widgets .swift-control-widget-item.is-disabled a"
		);
		if (!disabledItems.length) return;

		disabledItems.forEach(function (el) {
			el.addEventListener("click", function (e) {
				e.preventDefault();
			});
		});
	}

	function checkInlineEditing() {
		var inlineEdits = document.querySelectorAll(
			".swift-control-widgets .swift-control-widget-item.inline-edit a"
		);
		if (!inlineEdits.length) return;

		inlineEdits.forEach(function (el) {
			el.addEventListener("click", function (e) {
				e.preventDefault();

				if (this.parentNode.classList.contains("wallace-edit")) {
					document.dispatchEvent(
						new CustomEvent("walInlineAdminButtonClicked")
					);
				}
			});
		});
	}

	function hideMenuOnInlineEditing() {
		menu.classList.remove("is-expanded");

		// Hide the whole menu after widget items hidden.
		setTimeout(function () {
			menu.classList.add("is-hidden-mode");
		}, swiftControlOpt.settingButton.hidingDelay);
	}

	function setupWallaceSupport() {
		document.addEventListener("WallaceInlineOpened", function (e) {
			hideMenuOnInlineEditing();
		});

		document.addEventListener("WallaceInlineClosed", function (e) {
			menu.classList.remove("is-hidden-mode");
		});
	}

	function setupPositions() {
		window.addEventListener("load", setupPosition);
		window.addEventListener("resize", setupPosition);
	}

	function setupPosition(e) {
		docWidth = document.documentElement.clientWidth;
		halfHeight = document.documentElement.clientHeight / 2;

		position.x =
			position.x_direction === "left"
				? (position.x = 0)
				: docWidth - widgetSize;
		position.y = (position.y_percentage / 100) * halfHeight;
		position.y =
			position.y > halfHeight
				? halfHeight - swiftControlOpt.size / 2
				: position.y;
		position.y = position.y_direction === "top" ? position.y * -1 : position.y;

		menu.style.webkitTransform = menu.style.transform =
			"translate(" + position.x + "px, " + position.y + "px)";

		if (e.type === "load") {
			menu.classList.remove("is-invisible");
		}
	}

	function showHelperPanels() {
		var fragment = document.createDocumentFragment();
		var panels = [
			createElement("div", "helper-panel vertical-panel left-panel"),
			createElement("div", "helper-panel vertical-panel center-panel"),
			createElement("div", "helper-panel vertical-panel right-panel"),

			createElement("div", "helper-panel horizontal-panel top-middle-panel"),
			createElement("div", "helper-panel horizontal-panel middle-panel"),
			createElement("div", "helper-panel horizontal-panel bottom-middle-panel"),
		];

		for (var i = 0; i < panels.length; i++) {
			// Append child to fragment, not DOM.
			fragment.appendChild(panels[i]);
		}

		// Now append the fragment to the DOM.
		panelsWrapper.appendChild(fragment);
	}

	function createElement(tagName, className, child) {
		var tag = document.createElement(tagName);
		tag.className = className;

		if (child) tag.appendChild(child);
		return tag;
	}

	init();
})(jQuery);
