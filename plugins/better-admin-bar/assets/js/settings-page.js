/**
 * This script is intended to handle the settings page.
 *
 * @param {Object} $ jQuery object.
 * @return {Object}
 */
(function ($) {
	/**
	 * Initialize the module, call the main functions.
	 *
	 * This function is the only function that should be called on top level scope.
	 * Other functions are called / hooked from this function.
	 */
	function init() {
		setupTabsNavigation();
		setupChainingFields();
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
				$(".general-settings-area .submit").hide();
			} else {
				$(".general-settings-area .submit").show();
			}

			$(".heatbox-form-container .heatbox-admin-panel").css("display", "none");

			$(".heatbox-form-container .swift-control-" + hashValue + "-panel").css(
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
				hashValue = hashValue ? hashValue : "settings";
			}

			if ("tools" === hashValue) {
				$(".general-settings-area .submit").hide();
			} else {
				$(".general-settings-area .submit").show();
			}

			$(".heatbox-tab-nav-item").removeClass("active");
			$(".heatbox-tab-nav-item.swift-control-" + hashValue + "-panel").addClass(
				"active"
			);

			$(".heatbox-form-container .heatbox-admin-panel").css("display", "none");

			$(".heatbox-form-container .swift-control-" + hashValue + "-panel").css(
				"display",
				"block"
			);
		});
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

	// Run the module.
	init();
})(jQuery);
